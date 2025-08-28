<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Product;
use App\Models\ProductUnit;
use App\Models\Sale;
use App\Services\FifoService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class SaleController extends Controller
{
    public function index()
    {
        $sales = Sale::where('status', 'completed')
            ->with(['user', 'customer'])
            ->latest()
            ->paginate(10);

        return view('sales.index', compact('sales'));
    }

    public function create(Request $request)
    {
        // Check if we're loading a draft
        $draft = null;
        if ($request->has('draft_id')) {
            $draft = Sale::with(['saleDetails.product', 'saleDetails.productUnit', 'customer'])
                ->where('id', $request->draft_id)
                ->whereIn('status', ['draft', 'processing'])
                ->first();

            // If draft exists but saleDetails are empty, check session for stored details
            if ($draft && $draft->saleDetails->isEmpty() && session()->has('draft_details')) {
                // Attach session-stored details to the draft object
                $draft->setRelation('saleDetails', session('draft_details'));
                // Clear the session after using it
                session()->forget('draft_details');
            }
        }

        // Get all products, not just those with stock > 0, to ensure draft products are available
        $products = Product::orderBy('name')->get();

        // Ambil data customer tanpa cache
        $customers = Customer::select('id', 'nama', 'kecamatan_nama', 'kabupaten_nama')
            ->orderBy('nama')
            ->get();

        // Generate invoice number
        $invoiceNumber = $this->generateUniqueInvoiceNumber();

        // Pass the draft to the view if it exists

        return view('sales.create', compact('products', 'customers', 'invoiceNumber', 'draft'));
    }

    public function store(Request $request)
    {
        // Check if saving as draft
        $savingAsDraft = $request->has('save_draft');

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
        $paymentMethod = $request->payment_method ?? 'cash'; // Default to cash for drafts

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
            
            // Jika pembayaran kredit belum lunas, set status transaksi menjadi 'pending'
            if ($remainingAmount > 0) {
                $status = 'pending';
            } else {
                $status = $savingAsDraft ? 'draft' : 'completed';
            }
        } else {
            $paymentStatus = 'paid';
            $paidAmount = $request->paid_amount ?? $finalTotal;
            $remainingAmount = 0;
            if ($paidAmount > $finalTotal) {
                $changeAmount = $paidAmount - $finalTotal;
            }
        }

        try {
            DB::beginTransaction();

            // Generate a unique invoice number if not provided or if it already exists
            $invoiceNumber = $request->invoice_number;
            if (empty($invoiceNumber) || Sale::withTrashed()->where('invoice_number', $invoiceNumber)->exists()) {
                $invoiceNumber = $this->generateUniqueInvoiceNumber();
            }

            // Create sale
            $sale = Sale::create([
                'invoice_number' => $invoiceNumber,
                'date' => $request->date ?? now(),
                'customer_id' => $customerId,
                'user_id' => auth()->id(),
                'payment_method' => $paymentMethod,
                'payment_status' => $paymentStatus,
                'status' => isset($status) ? $status : ($savingAsDraft ? 'draft' : 'completed'),
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
                'draft_id' => $request->draft_id ?? null, // Track original draft if processing from draft
                'is_draft_processed' => false, // Track if this draft has been processed
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

                // Update stock menggunakan FIFO untuk transaksi selesai
                if (!$savingAsDraft) {
                    // Gunakan FIFO Service untuk mengurangi stok
                    $fifoService = new FifoService();
                    $usedBatches = $fifoService->reduceStock(
                        $productId,
                        $baseQuantity,
                        'sale',
                        $sale->id,
                        'Penjualan produk'
                    );
                } else {
                    // Untuk draft, kita tidak mengurangi stok fisik
                    // Hanya catat pergerakan stok untuk referensi
                    $product->stockMovements()->create([
                        'type' => 'draft_out',
                        'quantity' => $baseQuantity,
                        'before_stock' => $product->stock,
                        'after_stock' => $product->stock, // Tidak mengurangi stok fisik

                        'reference_type' => 'draft_sale',
                        'reference_id' => $sale->id,
                        'notes' => 'Draft penjualan produk'
                    ]);
                }
            }

            DB::commit();

            if ($savingAsDraft) {
                return redirect()->route('sales.drafts')
                    ->with('success', 'Draft penjualan berhasil disimpan');
            } else {
                // Jika ini adalah transaksi dari draft, update status draft asli menjadi completed
                if ($request->draft_id) {
                    $draft = Sale::find($request->draft_id);
                    if ($draft) {
                        $draft->update(['status' => 'completed']);
                    }
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
        $sale->load([
            'saleDetails.product',
            'saleDetails.productUnit.unit',
            'user',
            'customer'
        ]);

        // Load draft information if this sale was created from a draft
        if ($sale->draft_id) {
            $sale->load('draft');
        }

        // Load final sale information if this is a processed draft
        if ($sale->status === 'processed') {
            $sale->load('finalSale');
        }

        return view('sales.show', compact('sale'));
    }

    /**
     * Display a listing of draft sales.
     */
    public function drafts(Request $request)
    {
        $query = Sale::where('status', 'draft')
            ->with(['user', 'customer', 'saleDetails']);

        // Filter berdasarkan tanggal jika ada
        if ($request->has('date_from') && $request->has('date_to')) {
            $query->whereBetween('date', [
                $request->date_from . ' 00:00:00',
                $request->date_to . ' 23:59:59'
            ]);
        }

        // Filter berdasarkan customer jika ada
        if ($request->has('customer_id') && $request->customer_id) {
            $query->where('customer_id', $request->customer_id);
        }

        // Filter berdasarkan status jika ada
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        $drafts = $query->latest()->paginate(10);

        // Count items for each draft
        foreach ($drafts as $draft) {
            $draft->item_count = $draft->saleDetails->count();
        }

        $customers = Customer::orderBy('nama')->get();

        return view('sales.drafts', compact('drafts', 'customers'));
    }

    /**
     * Process a draft sale to complete it
     */
    public function completeDraft(Sale $sale)
    {
        if ($sale->status !== 'draft') {
            return back()->with('error', 'Hanya draft yang dapat diproses');
        }

        // Cek apakah draft sudah diproses sebelumnya
        if ($sale->is_draft_processed) {
            return back()->with('error', 'Draft ini sudah diproses sebelumnya');
        }

        try {
            DB::beginTransaction();

            // Load sale details to ensure we have all the data
            $sale->load(['saleDetails.product', 'saleDetails.productUnit', 'customer']);

            // Verifikasi ketersediaan stok sebelum melanjutkan
            $insufficientStockProducts = [];
            foreach ($sale->saleDetails as $detail) {
                $product = $detail->product;
                $currentStock = $product->stock;

                // Kita perlu memeriksa apakah stok masih mencukupi
                // Kita tidak perlu mengurangi kuantitas draft karena sudah dikurangi
                if ($currentStock < 0) {
                    $insufficientStockProducts[] = [
                        'name' => $product->name,
                        'needed' => $detail->base_quantity,
                        'available' => $currentStock + $detail->base_quantity // Tambahkan kembali kuantitas draft untuk menampilkan yang tersedia sebenarnya
                    ];
                }
            }

            // Jika ada produk dengan stok tidak mencukupi, tampilkan error
            if (count($insufficientStockProducts) > 0) {
                DB::rollBack();

                $errorMessage = 'Stok tidak mencukupi untuk produk berikut:<ul>';
                foreach ($insufficientStockProducts as $product) {
                    $errorMessage .= "<li>{$product['name']} (Tersedia: {$product['available']}, Dibutuhkan: {$product['needed']})</li>";
                }
                $errorMessage .= '</ul>';

                return back()->with('error', $errorMessage);
            }

            // Pastikan semua produk dalam draft masih tersedia di database
            foreach ($sale->saleDetails as $detail) {
                $product = Product::find($detail->product_id);
                if (!$product) {
                    DB::rollBack();
                    return back()->with('error', 'Produk dengan ID ' . $detail->product_id . ' tidak ditemukan. Draft tidak dapat diproses.');
                }
            }

            // Tandai draft sebagai diproses untuk mencegah pemrosesan duplikat
            $sale->update(['is_draft_processed' => true, 'status' => 'completed']);
            
            // Perbarui stok menggunakan FIFO untuk setiap produk dalam draft
            foreach ($sale->saleDetails as $detail) {
                $product = $detail->product;
                $baseQuantity = $detail->base_quantity;
                
                // Gunakan FIFO Service untuk mengurangi stok
                $fifoService = new FifoService();
                $usedBatches = $fifoService->reduceStock(
                    $detail->product_id,
                    $baseQuantity,
                    'sale',
                    $sale->id,
                    'Penjualan produk dari draft'
                );
            }

            DB::commit();

            // Redirect ke halaman index tanpa membuat transaksi baru
            // Ini mencegah duplikasi transaksi karena draft sudah diubah menjadi transaksi selesai
            return redirect()->route('sales.index')
                ->with('success', 'Transaksi berhasil diselesaikan.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Draft Processing Error: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'sale_id' => $sale->id
            ]);

            return back()->with('error', 'Gagal memproses draft: ' . $e->getMessage());
        }
    }

    public function destroy(Sale $sale)
    {
        try {
            DB::beginTransaction();

            // Restore stock for both completed sales and drafts
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
                    'reference_type' => $sale->status === 'draft' ? 'draft_void' : 'sale_void',
                    'reference_id' => $sale->id,
                    'notes' => $sale->status === 'draft' ? 'Draft dibatalkan' : 'Transaksi dibatalkan'
                ]);
            }

            $sale->status = 'cancelled';
            $sale->save();
            $sale->delete(); // Soft delete

            DB::commit();

            $redirectRoute = $sale->status === 'draft' ? 'sales.drafts' : 'sales.index';
            $message = $sale->status === 'draft' ? 'Draft berhasil dibatalkan' : 'Transaksi berhasil dibatalkan';

            return redirect()
                ->route($redirectRoute)
                ->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Sale Delete Error: ' . $e->getMessage());
            return back()->with('error', 'Gagal membatalkan transaksi: ' . $e->getMessage());
        }
    }

    /**
     * Generate a unique invoice number that doesn't exist in the database
     */
    private function generateUniqueInvoiceNumber()
    {
        $prefix = 'INV-' . date('Ymd');
        $lastSale = Sale::where('invoice_number', 'like', $prefix . '%')
            ->withTrashed() // Include soft-deleted records to avoid duplicates
            ->orderBy('invoice_number', 'desc')
            ->first();

        if ($lastSale) {
            $lastNumber = intval(substr($lastSale->invoice_number, -4));
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        $invoiceNumber = $prefix . '-' . str_pad($newNumber, 4, '0', STR_PAD_LEFT);

        // Check if the generated invoice number already exists (including trashed records)
        // If it does, increment until we find a unique one
        while (Sale::withTrashed()->where('invoice_number', $invoiceNumber)->exists()) {
            $newNumber++;
            $invoiceNumber = $prefix . '-' . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
        }

        return $invoiceNumber;
    }

    /**
     * Generate invoice biasa (non-benih)
     */
    public function invoice(Sale $sale)
    {
        $sale->load([
            'saleDetails.product.category',
            'saleDetails.productUnit.unit',
            'user',
            'customer'
        ]);

        // Filter hanya produk non-benih
        $nonSeedItems = $sale->saleDetails->filter(function ($detail) {
            return !$detail->product->category ||
                strtolower($detail->product->category->name) !== 'benih';
        });

        // Generate nomor invoice khusus
        $invoiceNumber = 'INV-' . date('Ymd') . '-' . str_pad($sale->id, 4, '0', STR_PAD_LEFT);
        
        // Ambil data pengaturan toko
        $storeSetting = \App\Models\StoreSetting::first();

        return view('sales.invoice', compact('sale', 'nonSeedItems', 'invoiceNumber', 'storeSetting'));
    }

    /**
     * Generate invoice khusus benih
     */
    public function invoiceSeeds(Sale $sale)
    {
        $sale->load([
            'saleDetails.product.category',
            'saleDetails.productUnit.unit',
            'user',
            'customer'
        ]);

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
        
        // Ambil data pengaturan toko
        $storeSetting = \App\Models\StoreSetting::first();

        return view('sales.invoice-seeds', compact('sale', 'seedItems', 'invoiceNumber', 'storeSetting'));
    }

    /**
     * Generate surat jalan
     */
    public function deliveryNote(Sale $sale)
    {
        $sale->load([
            'saleDetails.product',
            'saleDetails.productUnit.unit',
            'user',
            'customer'
        ]);

        // Generate nomor surat jalan
        $deliveryNumber = 'SJ-' . date('Ymd') . '-' . str_pad($sale->id, 4, '0', STR_PAD_LEFT);
        
        // Ambil data pengaturan toko
        $storeSetting = \App\Models\StoreSetting::first();

        return view('sales.delivery-note', compact('sale', 'deliveryNumber', 'storeSetting'));
    }

    public function creditSales()
    {
        $creditSales = Sale::where('payment_method', 'credit')
            ->where('payment_status', '!=', 'paid')
            ->where('status', 'completed')
            ->with(['customer'])
            ->latest('due_date')
            ->paginate(10);

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

        // No cache invalidation needed

        return redirect()->route('sales.credit')
            ->with('success', 'Pembayaran sebesar Rp ' . number_format($amount, 0, ',', '.') . ' berhasil dicatat');
    }

    // API untuk mendapatkan product details
    public function getProduct(Product $product)
    {
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
    }

    /**
     * Get sale details for copying items to new sale
     */
    public function getSaleDetails(Sale $sale)
    {
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
    }

    /**
     * Update draft notes
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Sale  $sale
     * @return \Illuminate\Http\Response
     */
    public function updateNotes(Request $request, Sale $sale)
    {
        // Verify this is a draft
        if ($sale->status !== 'draft') {
            return redirect()->route('sales.drafts')
                ->with('error', 'Hanya draft yang dapat diperbarui catatannya.');
        }

        // Update the notes
        $sale->update([
            'notes' => $request->notes
        ]);

        return redirect()->route('sales.drafts')
            ->with('success', 'Catatan draft berhasil diperbarui.');
    }

    /**
     * Show the form for editing the specified draft.
     *
     * @param  \App\Models\Sale  $sale
     * @return \Illuminate\Http\Response
     */
    public function edit(Sale $sale)
    {
        // Verify this is a draft
        if ($sale->status !== 'draft') {
            return redirect()->route('sales.drafts')
                ->with('error', 'Hanya draft yang dapat diedit.');
        }

        // Redirect to create form with draft_id parameter
        return redirect()->route('sales.create', ['draft_id' => $sale->id]);
    }

    // Metode clearSalesCaches telah dihapus karena tidak lagi diperlukan
}
