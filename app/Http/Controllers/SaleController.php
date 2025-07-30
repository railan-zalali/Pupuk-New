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
            Cache::forget('all_customers');
        }

        // Check if saving as draft
        $savingAsDraft = $request->has('save_draft') && $request->save_draft == 1;

        // Validation rules
        $validationRules = [
            'product_id' => 'required|array',
            'product_id.*' => 'required|exists:products,id',
            'unit_id' => 'required|array',
            'unit_id.*' => 'required|exists:product_units,id',
            'quantity' => 'required|array',
            'quantity.*' => 'required|numeric|min:0.01',
            'selling_price' => 'required|array',
            'selling_price.*' => 'required|numeric|min:0',
            'payment_method' => 'required|in:cash,transfer,credit',
            'vehicle_type' => 'nullable|string',
            'vehicle_number' => 'nullable|string',
        ];

        // For completed transactions (not drafts), apply additional validation
        if (!$savingAsDraft && $request->payment_method === 'credit') {
            $validationRules['down_payment'] = 'required|numeric|min:0';
            // Customer required for credit
            if (empty($customerId)) {
                return back()->with('error', 'Transaksi dengan metode Kredit harus memilih pelanggan')->withInput();
            }
        }

        $request->validate($validationRules);

        // Calculate totals
        $totalAmount = 0;
        foreach ($request->product_id as $key => $productId) {
            $totalAmount += $request->quantity[$key] * $request->selling_price[$key];
        }

        $discount = $request->discount ?? 0;
        $finalTotal = max(0, $totalAmount - $discount);

        // Payment handling
        $paymentStatus = 'pending';
        $paidAmount = 0;
        $remainingAmount = $finalTotal;
        $dueDate = null;
        $changeAmount = 0;
        $paymentMethod = $request->payment_method;

        if (!$savingAsDraft) {
            if ($request->payment_method === 'credit') {
                $downPayment = $request->down_payment;
                $paidAmount = $downPayment;
                $remainingAmount = $finalTotal - $downPayment;
                $dueDate = now()->addDays(30);

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
                $remainingAmount = 0;
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
                'date' => $request->date ?? now(),
                'customer_id' => $customerId,
                'user_id' => auth()->id(),
                'payment_method' => $paymentMethod,
                'payment_status' => $paymentStatus,
                'status' => $savingAsDraft ? 'draft' : 'completed',
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
                $baseQuantity = $quantity * $productUnit->conversion_factor;

                // Stock validation and update only for completed transactions
                if (!$savingAsDraft) {
                    // Stock validation
                    if ($baseQuantity > $product->stock) {
                        throw new \Exception("Stok tidak cukup untuk produk: {$product->name}");
                    }
                }

                $sale->saleDetails()->create([
                    'product_id' => $productId,
                    'product_unit_id' => $productUnit->id,
                    'unit_id' => $productUnit->unit_id,
                    'quantity' => $quantity,
                    'base_quantity' => $baseQuantity,
                    'price' => $price,
                    'subtotal' => $quantity * $price,
                ]);

                // Update stock only for completed transactions
                if (!$savingAsDraft) {
                    $beforeStock = $product->stock;
                    $product->decrement('stock', $baseQuantity);

                    $product->stockMovements()->create([
                        'type' => 'out',
                        'quantity' => $baseQuantity,
                        'before_stock' => $beforeStock,
                        'after_stock' => $product->stock,
                        'reference_type' => 'sale',
                        'reference_id' => $sale->id,
                        'notes' => 'Penjualan produk'
                    ]);

                    Cache::forget('available_products');
                    Cache::forget('product_details_' . $productId);
                }
            }

            DB::commit();

            // Clear relevant caches
            $this->clearSalesCaches($paymentMethod, $paymentStatus);

            if ($savingAsDraft) {
                return redirect()->route('drafts.index')
                    ->with('success', 'Draft penjualan berhasil disimpan');
            } else {
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

    public function destroy(Sale $sale)
    {
        try {
            DB::beginTransaction();

            // For completed sales, restore stock and mark as cancelled
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
            $sale->delete(); // Soft delete

            DB::commit();

            // Invalidate affected caches
            $this->clearSalesCaches($sale->payment_method, $sale->payment_status);
            Cache::forget('sale_' . $sale->id);
            Cache::forget('available_products');

            // Invalidate individual product caches
            foreach ($productIds as $productId) {
                Cache::forget('product_details_' . $productId);
            }

            return redirect()
                ->route('sales.index')
                ->with('success', 'Transaksi berhasil dibatalkan');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Sale Delete Error: ' . $e->getMessage());
            return back()->with('error', 'Gagal membatalkan transaksi: ' . $e->getMessage());
        }
    }

    /**
     * Generate invoice biasa (non-benih)
     */
    public function invoice(Sale $sale)
    {
        $cacheKey = 'sale_invoice_' . $sale->id;

        $sale = Cache::remember($cacheKey, 3600, function () use ($sale) {
            return $sale->load([
                'saleDetails.product.category',
                'saleDetails.productUnit.unit',
                'user',
                'customer'
            ]);
        });

        // Filter hanya produk non-benih
        $nonSeedItems = $sale->saleDetails->filter(function ($detail) {
            return !$detail->product->category ||
                strtolower($detail->product->category->name) !== 'benih';
        });

        // Generate nomor invoice khusus
        $invoiceNumber = 'INV-' . date('Ymd') . '-' . str_pad($sale->id, 4, '0', STR_PAD_LEFT);

        return view('sales.invoice', compact('sale', 'nonSeedItems', 'invoiceNumber'));
    }

    /**
     * Generate invoice khusus benih
     */
    public function invoiceSeeds(Sale $sale)
    {
        $cacheKey = 'sale_invoice_seeds_' . $sale->id;

        $sale = Cache::remember($cacheKey, 3600, function () use ($sale) {
            return $sale->load([
                'saleDetails.product.category',
                'saleDetails.productUnit.unit',
                'user',
                'customer'
            ]);
        });

        // Filter hanya produk benih
        $seedItems = $sale->saleDetails->filter(function ($detail) {
            return $detail->product->category &&
                strtolower($detail->product->category->name) === 'benih';
        });

        // Jika tidak ada produk benih, redirect ke invoice biasa
        if ($seedItems->isEmpty()) {
            return redirect()->route('sales.invoice', $sale)
                ->with('info', 'Tidak ada produk benih dalam transaksi ini');
        }

        // Generate nomor invoice khusus benih
        $invoiceNumber = 'INV-BNH-' . date('Ymd') . '-' . str_pad($sale->id, 4, '0', STR_PAD_LEFT);

        return view('sales.invoice-seeds', compact('sale', 'seedItems', 'invoiceNumber'));
    }

    /**
     * Generate surat jalan
     */
    public function deliveryNote(Sale $sale)
    {
        $cacheKey = 'sale_delivery_note_' . $sale->id;

        $sale = Cache::remember($cacheKey, 3600, function () use ($sale) {
            return $sale->load([
                'saleDetails.product',
                'saleDetails.productUnit.unit',
                'user',
                'customer'
            ]);
        });

        // Generate nomor surat jalan
        $deliveryNumber = 'SJ-' . date('Ymd') . '-' . str_pad($sale->id, 4, '0', STR_PAD_LEFT);

        return view('sales.delivery-note', compact('sale', 'deliveryNumber'));
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
            $product->load('productUnitsWithUnit');

            $formattedUnits = $product->productUnitsWithUnit->map(function ($productUnit) {
                $unitName = 'N/A';
                $unitAbbreviation = 'N/A';

                try {
                    if ($productUnit->unit) {
                        $unitName = $productUnit->unit->name;
                        $unitAbbreviation = $productUnit->unit->abbreviation;
                    }
                } catch (\Exception $e) {
                    // Fallback jika relasi unit tidak ditemukan
                }

                return [
                    'id' => $productUnit->id,
                    'unit_id' => $productUnit->unit_id,
                    'name' => $unitName,
                    'abbreviation' => $unitAbbreviation,
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

    /**
     * Clear sales-related caches
     */
    private function clearSalesCaches($paymentMethod = null, $paymentStatus = null)
    {
        // Clear completed sales cache
        for ($i = 1; $i <= 5; $i++) {
            Cache::forget('completed_sales_page_' . $i);
        }

        // Clear credit sales cache if relevant
        if ($paymentMethod === 'credit' && $paymentStatus !== 'paid') {
            for ($i = 1; $i <= 5; $i++) {
                Cache::forget('credit_sales_list_page_' . $i);
            }
        }
    }
}
