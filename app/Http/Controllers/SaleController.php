<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Product;
use App\Models\ProductUnit;
use App\Models\Sale;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class SaleController extends Controller
{
    public function index()
    {
        $sales = Cache::remember('completed_sales_page_' . request('page', 1), 300, function () {
            return Sale::where('status', 'completed')
                ->with(['user', 'customer'])
                ->latest()
                ->paginate(10);
        });

        return view('sales.index', compact('sales'));
    }

    public function drafts()
    {
        $drafts = Cache::remember('draft_sales_page_' . request('page', 1), 300, function () {
            return Sale::where('status', 'draft')
                ->with(['user', 'customer'])
                ->latest()
                ->paginate(10);
        });

        return view('sales.drafts', compact('drafts'));
    }

    public function create()
    {
        // Cache products for 10 minutes
        $products = Cache::remember('available_products', 600, function () {
            return Product::where('stock', '>', 0)->orderBy('name')->get();
        });

        // Ambil data customer tanpa cache
        $customers = Customer::select('id', 'nama', 'kecamatan_nama', 'kabupaten_nama')
            ->orderBy('nama')
            ->get();


        // Generate invoice number
        $lastSale = Sale::whereDate('created_at', Carbon::today())->latest()->first();
        $lastNumber = $lastSale ? intval(substr($lastSale->invoice_number, -4)) : 0;
        $invoiceNumber = 'INV-' . date('Ymd') . '-' . str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);

        return view('sales.create', compact('products', 'customers', 'invoiceNumber'));
    }

    public function store(Request $request)
    {
        // Handle customer creation
        $customerId = $request->customer_id;
        $newCustomerName = $request->new_customer_name;

        if (!empty($newCustomerName)) {
            $customer = Customer::create([
                'nama' => $newCustomerName,
                'nik' => 'TEMP-' . time(),
                'desa_id' => '0',
                'kecamatan_id' => '0',
                'kabupaten_id' => '0',
                'provinsi_id' => '0',
                'desa_nama' => '-',
                'kecamatan_nama' => '-',
                'kabupaten_nama' => '-',
                'provinsi_nama' => '-'
            ]);
            $customerId = $customer->id;

            // Invalidate customers cache
            Cache::forget('all_customers');
        }

        // Validation rules
        $validationRules = [
            'payment_method' => 'required|in:cash,transfer,credit',
            'product_id' => 'required|array',
            'product_id.*' => 'required|exists:products,id',
            'unit_id' => 'required|array',
            'unit_id.*' => 'required|exists:product_units,id',
            'quantity' => 'required|array',
            'quantity.*' => 'required|numeric|min:0.01',
            'selling_price' => 'required|array',
            'selling_price.*' => 'required|numeric|min:0',
        ];

        // Only make vehicle fields required for completed transactions
        if (!$request->has('save_draft')) {
            $validationRules['vehicle_type'] = 'string';
            $validationRules['vehicle_number'] = 'string';
        }

        if ($request->payment_method === 'credit') {
            $validationRules['down_payment'] = 'required|numeric|min:0';
        }

        $request->validate($validationRules);

        // Calculate totals
        $totalAmount = 0;
        foreach ($request->product_id as $key => $productId) {
            $totalAmount += $request->quantity[$key] * $request->selling_price[$key];
        }

        $discount = $request->discount ?? 0;
        $finalTotal = max(0, $totalAmount - $discount);

        // Set transaction status
        $status = $request->has('save_draft') ? 'draft' : 'completed';

        // Payment handling
        $paymentStatus = 'pending';
        $paidAmount = 0;
        $remainingAmount = $finalTotal;
        $dueDate = null;
        $changeAmount = 0;

        if ($status === 'completed') {
            if ($request->payment_method === 'credit') {
                $downPayment = $request->down_payment;
                $paidAmount = $downPayment;
                $remainingAmount = $finalTotal - $downPayment;
                $dueDate = now()->addDays(30); // Default due date

                if ($downPayment >= $finalTotal) {
                    $paymentStatus = 'paid';
                    $remainingAmount = 0;
                    $changeAmount = $downPayment - $finalTotal;
                } else if ($downPayment > 0) {
                    $paymentStatus = 'partial';
                }
            } else {
                $paymentStatus = 'paid';
                $paidAmount = $request->paid_amount ?? $finalTotal;

                // Calculate change amount for cash payments
                if ($paidAmount > $finalTotal) {
                    $changeAmount = $paidAmount - $finalTotal;
                }
            }
        }

        try {
            DB::beginTransaction();

            // Create sale
            $sale = Sale::create([
                'invoice_number' => $request->invoice_number,
                'date' => now(),
                'customer_id' => $customerId,
                'user_id' => auth()->id(),
                'payment_method' => $request->payment_method,
                'payment_status' => $paymentStatus,
                'status' => $status,
                'total_amount' => $totalAmount,
                'discount' => $discount,
                'paid_amount' => $paidAmount,
                'down_payment' => $request->down_payment ?? 0,
                'remaining_amount' => $remainingAmount,
                'change_amount' => $changeAmount,
                'due_date' => $dueDate,
                'notes' => $request->notes,
                'vehicle_type' => $request->vehicle_type ?? null,
                'vehicle_number' => $request->vehicle_number ?? null,
            ]);

            // Create sale details
            foreach ($request->product_id as $key => $productId) {
                $productUnit = ProductUnit::findOrFail($request->unit_id[$key]);
                $quantity = $request->quantity[$key];
                $price = $request->selling_price[$key];
                $product = Product::findOrFail($productId);

                // Calculate base quantity
                $baseQuantity = $quantity * $productUnit->conversion_factor;

                $sale->saleDetails()->create([
                    'product_id' => $productId,
                    'product_unit_id' => $productUnit->id,
                    'unit_id' => $productUnit->unit_id,
                    'quantity' => $quantity,
                    'base_quantity' => $baseQuantity,
                    'price' => $price,
                    'subtotal' => $quantity * $price,
                ]);

                // Update stock only if not a draft
                if ($status !== 'draft') {
                    $beforeStock = $product->stock;
                    $product->decrement('stock', $baseQuantity);

                    // Record stock movement
                    $product->stockMovements()->create([
                        'type' => 'out',
                        'quantity' => $baseQuantity,
                        'before_stock' => $beforeStock,
                        'after_stock' => $product->stock,
                        'reference_type' => 'sale',
                        'reference_id' => $sale->id,
                        'notes' => 'Penjualan produk'
                    ]);

                    // Invalidate product caches
                    Cache::forget('available_products');
                    Cache::forget('product_details_' . $productId);
                }
            }

            DB::commit();

            // Invalidate relevant caches
            if ($status === 'draft') {
                Cache::forget('draft_sales_page_1');
                return redirect()->route('sales.drafts')
                    ->with('success', 'Draft transaksi berhasil disimpan');
            } else {
                Cache::forget('completed_sales_page_1');
                if ($request->payment_method === 'credit' && $paymentStatus !== 'paid') {
                    Cache::forget('credit_sales_list');
                }
                return redirect()->route('sales.show', $sale)
                    ->with('success', 'Transaksi berhasil disimpan');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Sale Error: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);
            return back()->with('error', 'Gagal menyimpan transaksi: ' . $e->getMessage())->withInput();
        }
    }

    public function show(Sale $sale)
    {
        $cacheKey = 'sale_' . $sale->id;

        $sale = Cache::remember($cacheKey, 600, function () use ($sale) {
            return $sale->load([
                'saleDetails.product',
                'saleDetails.productUnit.unit',
                'user',
                'customer'
            ]);
        });

        return view('sales.show', compact('sale'));
    }

    public function edit(Sale $sale)
    {
        if (!$sale->isDraft()) {
            return redirect()->route('sales.index')
                ->with('error', 'Hanya transaksi draft yang dapat diedit');
        }

        // Cache products for 10 minutes
        $products = Cache::remember('available_products', 600, function () {
            return Product::where('stock', '>', 0)->orderBy('name')->get();
        });

        // Cache customers for 30 minutes
        $customers = Cache::remember('all_customers', 1800, function () {
            return Customer::orderBy('nama')->get();
        });

        $sale->load('saleDetails.product', 'saleDetails.productUnit.unit');

        return view('sales.edit', compact('sale', 'products', 'customers'));
    }

    public function update(Request $request, Sale $sale)
    {
        if (!$sale->isDraft()) {
            return redirect()->route('sales.index')
                ->with('error', 'Hanya transaksi draft yang dapat diperbarui');
        }

        try {
            $validated = $request->validate([
                'customer_id' => 'nullable|exists:customers,id',
                'customer_name' => 'nullable|string|max:255',
                'product_id' => 'required|array',
                'product_id.*' => 'exists:products,id',
                'unit_id' => 'required|array',
                'unit_id.*' => 'exists:product_units,id',
                'quantity' => 'required|array',
                'quantity.*' => 'integer|min:1',
                'selling_price' => 'required|array',
                'selling_price.*' => 'numeric|min:0',
                'paid_amount' => 'required|numeric|min:0',
                'discount' => 'nullable|numeric|min:0',
                'payment_method' => 'required|in:cash,transfer,credit',
                'vehicle_type' => 'nullable|string',
                'vehicle_number' => 'nullable|string',
                'notes' => 'nullable|string',
                'complete_transaction' => 'nullable'
            ]);

            // Determine if completing the draft
            $status = $request->has('complete_transaction') ? 'completed' : 'draft';

            // Validate stock only if completing the transaction
            if ($status === 'completed') {
                foreach ($request->product_id as $key => $productId) {
                    $product = Product::find($productId);
                    $productUnit = ProductUnit::find($request->unit_id[$key]);
                    $quantity = $request->quantity[$key];

                    if (!$productUnit) {
                        return back()->with('error', "Unit tidak ditemukan untuk produk: {$product->name}");
                    }

                    // Calculate base quantity
                    $baseQuantity = $quantity * $productUnit->conversion_factor;

                    // Check if there's enough stock
                    if ($baseQuantity > $product->stock) {
                        return back()->with('error', "Stok tidak cukup untuk produk: {$product->name} dalam satuan {$productUnit->unit->name}");
                    }
                }
            }

            // Calculate total amount
            $totalAmount = collect($request->product_id)->map(function ($item, $key) use ($request) {
                return $request->quantity[$key] * $request->selling_price[$key];
            })->sum();

            $discount = $request->discount ?? 0;
            $finalTotal = max(0, $totalAmount - $discount);

            // Handle customer
            $customerId = $request->customer_id;
            if (empty($customerId) && !empty($request->customer_name)) {
                $customer = Customer::create([
                    'nama' => $request->customer_name,
                    'nik' => 'TEMP-' . time(),
                    'desa_id' => '0',
                    'kecamatan_id' => '0',
                    'kabupaten_id' => '0',
                    'provinsi_id' => '0',
                    'desa_nama' => '-',
                    'kecamatan_nama' => '-',
                    'kabupaten_nama' => '-',
                    'provinsi_nama' => '-'
                ]);
                $customerId = $customer->id;

                // Invalidate customers cache
                Cache::forget('all_customers');
            }

            // Set default values
            $downPayment = 0;
            $paidAmount = 0;
            $remainingAmount = $finalTotal;
            $changeAmount = 0;
            $paymentStatus = 'unpaid';
            $dueDate = null;

            // Calculate payment details if completing the transaction
            if ($status === 'completed') {
                if ($request->payment_method === 'credit') {
                    if (empty($customerId)) {
                        return back()->with('error', 'Transaksi dengan metode Hutang harus memilih pelanggan');
                    }

                    if ($request->has('down_payment')) {
                        $downPayment = floatval($request->down_payment);
                    }

                    $paidAmount = $downPayment;
                    $remainingAmount = $finalTotal - $downPayment;

                    if ($downPayment >= $finalTotal) {
                        $paymentStatus = 'paid';
                        $remainingAmount = 0;
                    } elseif ($downPayment > 0) {
                        $paymentStatus = 'partial';
                    }

                    $dueDate = now()->addDays(30);
                } else {
                    $paidAmount = $request->paid_amount;
                    $remainingAmount = 0;
                    $changeAmount = $paidAmount - $finalTotal;
                    $paymentStatus = 'paid';

                    if ($paidAmount < $finalTotal) {
                        return back()->with('error', 'Pembayaran kurang dari total belanja');
                    }
                }
            }

            // Update sale data
            $sale->update([
                'customer_id' => $customerId,
                'total_amount' => $totalAmount,
                'paid_amount' => $paidAmount,
                'discount' => $discount,
                'down_payment' => $downPayment,
                'change_amount' => $changeAmount,
                'payment_method' => $request->payment_method,
                'vehicle_type' => $request->vehicle_type,
                'vehicle_number' => $request->vehicle_number,
                'payment_status' => $paymentStatus,
                'status' => $status,
                'remaining_amount' => $remainingAmount,
                'due_date' => $dueDate,
                'notes' => $request->notes
            ]);

            // Remove old details
            $sale->saleDetails()->delete();

            // Add new details
            foreach ($request->product_id as $key => $productId) {
                $quantity = $request->quantity[$key];
                $price = $request->selling_price[$key];
                $product = Product::find($productId);
                $productUnit = ProductUnit::find($request->unit_id[$key]);

                // Calculate base quantity for stock calculation
                $baseQuantity = $quantity * $productUnit->conversion_factor;

                // Create detail
                $sale->saleDetails()->create([
                    'product_id' => $productId,
                    'product_unit_id' => $productUnit->id,
                    'unit_id' => $productUnit->unit_id,
                    'quantity' => $quantity,
                    'base_quantity' => $baseQuantity,
                    'price' => $price,
                    'subtotal' => $quantity * $price
                ]);

                // Update stock only if completing the transaction
                if ($status === 'completed') {
                    $beforeStock = $product->stock;
                    $product->stock -= $baseQuantity;  // Use baseQuantity instead of quantity
                    $product->save();

                    $product->stockMovements()->create([
                        'type' => 'out',
                        'quantity' => $baseQuantity,  // Use baseQuantity instead of quantity
                        'before_stock' => $beforeStock,
                        'after_stock' => $product->stock,
                        'reference_type' => 'sale',
                        'reference_id' => $sale->id,
                        'notes' => 'Penjualan produk dari draft'
                    ]);

                    // Invalidate product caches
                    Cache::forget('available_products');
                    Cache::forget('product_details_' . $productId);
                }
            }

            // Invalidate sale caches
            Cache::forget('sale_' . $sale->id);
            Cache::forget('draft_sales_page_1');

            if ($status === 'completed') {
                Cache::forget('completed_sales_page_1');
                if ($request->payment_method === 'credit' && $paymentStatus !== 'paid') {
                    Cache::forget('credit_sales_list');
                }

                return redirect()
                    ->route('sales.show', $sale)
                    ->with('success', 'Draft transaksi berhasil diselesaikan');
            } else {
                return redirect()
                    ->route('sales.drafts')
                    ->with('success', 'Draft transaksi berhasil diperbarui');
            }
        } catch (\Exception $e) {
            Log::error('Sale Update Error: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);

            return back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function completeDraft(Sale $sale)
    {
        if (!$sale->isDraft()) {
            return redirect()->route('sales.index')
                ->with('error', 'Hanya transaksi draft yang dapat diselesaikan');
        }

        // Load draft details for editing
        $sale->load('saleDetails.product', 'saleDetails.productUnit.unit');

        // Cache products for 10 minutes
        $products = Cache::remember('available_products', 600, function () {
            return Product::where('stock', '>', 0)->orderBy('name')->get();
        });

        // Cache customers for 30 minutes
        $customers = Cache::remember('all_customers', 1800, function () {
            return Customer::orderBy('nama')->get();
        });

        return view('sales.complete_draft', compact('sale', 'products', 'customers'));
    }

    public function destroy(Sale $sale)
    {
        // Handle differently based on status
        if ($sale->isDraft()) {
            // Simply delete the draft
            $sale->saleDetails()->delete();
            $sale->delete();

            // Invalidate draft cache
            Cache::forget('draft_sales_page_1');

            return redirect()
                ->route('sales.drafts')
                ->with('success', 'Draft transaksi berhasil dihapus');
        }

        // For completed sales, restore stock
        $sale->load(['saleDetails.product']);
        $productIds = [];

        foreach ($sale->saleDetails as $detail) {
            $product = $detail->product;
            $beforeStock = $product->stock;
            $productIds[] = $product->id;

            // Use base_quantity for stock calculation
            $product->increment('stock', $detail->base_quantity);

            $product->stockMovements()->create([
                'type' => 'in',
                'quantity' => $detail->base_quantity,
                'before_stock' => $beforeStock,
                'after_stock' => $product->stock,
                'reference_type' => 'sale_void',
                'reference_id' => $sale->id,
                'notes' => 'Sale void'
            ]);
        }

        $sale->status = 'cancelled';
        $sale->save();
        $sale->delete();

        // Invalidate affected caches
        Cache::forget('completed_sales_page_1');
        Cache::forget('sale_' . $sale->id);
        Cache::forget('available_products');
        if ($sale->payment_method === 'credit' && $sale->payment_status !== 'paid') {
            Cache::forget('credit_sales_list');
        }

        // Invalidate individual product caches
        foreach ($productIds as $productId) {
            Cache::forget('product_details_' . $productId);
        }

        return redirect()
            ->route('sales.index')
            ->with('success', 'Transaksi berhasil dibatalkan');
    }

    public function invoice(Sale $sale)
    {
        if ($sale->isDraft()) {
            return redirect()->route('sales.drafts')
                ->with('error', 'Tidak dapat mencetak invoice untuk transaksi draft');
        }

        $cacheKey = 'sale_invoice_' . $sale->id;

        $sale = Cache::remember($cacheKey, 3600, function () use ($sale) {
            return $sale->load([
                'saleDetails.product',
                'saleDetails.productUnit.unit',
                'user',
                'customer'
            ]);
        });

        return view('sales.invoice', compact('sale'));
    }

    public function invoiceSeeds(Sale $sale)
    {
        if ($sale->isDraft()) {
            return redirect()->route('sales.drafts')
                ->with('error', 'Tidak dapat mencetak invoice untuk transaksi draft');
        }

        $cacheKey = 'sale_invoice_seeds_' . $sale->id;

        $sale = Cache::remember($cacheKey, 3600, function () use ($sale) {
            return $sale->load([
                'saleDetails.product.category',
                'saleDetails.productUnit.unit',
                'user',
                'customer'
            ]);
        });

        return view('sales.invoice_seeds', compact('sale'));
    }

    public function creditSales()
    {
        $creditSales = Cache::remember('credit_sales_list_page_' . request('page', 1), 300, function () {
            return Sale::where('payment_method', 'credit')
                ->where('payment_status', '!=', 'paid')
                ->where('status', 'completed')
                ->with(['customer'])
                ->latest('due_date')
                ->paginate(10);
        });

        return view('sales.credit', compact('creditSales'));
    }

    public function payCredit(Request $request, Sale $sale)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1|max:' . $sale->remaining_amount,
        ]);

        $amount = $request->amount;
        $newPaidAmount = $sale->paid_amount + $amount;
        $newRemainingAmount = $sale->remaining_amount - $amount;

        if ($newRemainingAmount <= 0) {
            $sale->update([
                'paid_amount' => $newPaidAmount,
                'remaining_amount' => 0,
                'payment_status' => 'paid'
            ]);
        } else {
            $sale->update([
                'paid_amount' => $newPaidAmount,
                'remaining_amount' => $newRemainingAmount,
                'payment_status' => 'partial'
            ]);
        }

        // Invalidate caches
        Cache::forget('credit_sales_list');
        Cache::forget('sale_' . $sale->id);
        Cache::forget('sale_invoice_' . $sale->id);

        for ($i = 1; $i <= 5; $i++) {
            Cache::forget('credit_sales_list_page_' . $i);
        }

        return redirect()->route('sales.credit')
            ->with('success', 'Pembayaran sebesar Rp ' . number_format($amount, 0, ',', '.') . ' berhasil dicatat');
    }

    // API untuk mendapatkan product details
    public function getProduct(Product $product)
    {
        $cacheKey = 'product_details_' . $product->id;

        return Cache::remember($cacheKey, 300, function () use ($product) {
            $product->load('units.unit');

            $formattedUnits = $product->units->map(function ($productUnit) use ($product) {
                return [
                    'id' => $productUnit->id,
                    'unit_id' => $productUnit->unit_id,
                    'name' => $productUnit->unit->name,
                    'abbreviation' => $productUnit->unit->abbreviation,
                    'conversion_factor' => $productUnit->conversion_factor,
                    'purchase_price' => $productUnit->purchase_price,
                    'selling_price' => $productUnit->selling_price,
                    'is_default' => $productUnit->is_default,
                    'available_stock' => floor($productUnit->getAvailableStock())
                ];
            });

            return response()->json([
                'product' => $product,
                'units' => $formattedUnits,
                'stock_display' => $product->getFormattedStockDisplay()
            ]);
        });
    }

    /**
     * Get sale details for copying items to new sale
     */
    public function getSaleDetails(Sale $sale)
    {
        $cacheKey = 'sale_details_api_' . $sale->id;

        return Cache::remember($cacheKey, 300, function () use ($sale) {
            // Load details with product and unit
            $sale->load('saleDetails.product', 'saleDetails.productUnit.unit');

            $customer = Customer::find($sale->customer_id);
            return response()->json([
                'sale' => [
                    'id' => $sale->id,
                    'invoice_number' => $sale->invoice_number,
                    'date' => $sale->date,
                    'customer_id' => $sale->customer_id,
                    'total_amount' => $sale->total_amount,
                    'customer' => $customer,
                ],
                'details' => $sale->saleDetails
            ]);
        });
    }
}
