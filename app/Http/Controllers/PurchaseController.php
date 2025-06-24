<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductUnit;
use App\Models\Purchase;
use App\Models\PurchaseDetail;
use App\Models\PurchaseReceipt;
use App\Models\StockMovement;
use App\Models\Supplier;
use App\Models\UnitOfMeasure;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class PurchaseController extends Controller
{
    /**
     * Display a listing of purchases.
     */
    public function index()
    {
        // Eager load relationships to avoid N+1 queries
        $purchases = Purchase::with(['supplier', 'user'])
            ->orderBy('date', 'desc')
            ->paginate(15);

        return view('purchases.index', compact('purchases'));
    }

    /**
     * Show the form for creating a new purchase.
     */
    public function create()
    {
        $suppliers = Supplier::orderBy('name')->get();

        // Ambil semua produk dengan relasi units dan category
        $products = Product::with(['units', 'category'])->get();

        // Convert products ke format yang lebih mudah digunakan di JavaScript
        $productsForJs = $products->map(function ($product) {
            // Identifikasi unit dasar
            $baseUnit = $product->units->where('pivot.is_default', true)->first()
                ?? $product->units->first();

            return [
                'id' => $product->id,
                'name' => $product->name,
                'code' => $product->code,
                'image' => $product->image ? asset('storage/' . $product->image) : asset('img/product-placeholder.png'),
                'category_id' => $product->category_id,
                'category_name' => $product->category->name,
                'supplier_id' => $product->supplier_id,
                'stock' => $product->stock,
                'min_stock' => $product->min_stock,
                'base_unit' => $baseUnit ? $baseUnit->abbreviation : '',
                'description' => $product->description,
                'units' => $product->units->map(function ($unit) {
                    return [
                        'id' => $unit->id,
                        'name' => $unit->name,
                        'abbreviation' => $unit->abbreviation,
                        'conversion_factor' => $unit->pivot->conversion_factor,
                        'purchase_price' => $unit->pivot->purchase_price,
                        'selling_price' => $unit->pivot->selling_price,
                        'barcode' => $unit->pivot->barcode,
                        'is_default' => (bool)$unit->pivot->is_default,
                    ];
                })->toArray(),
            ];
        })->toArray();

        // Generate invoice number
        $lastPurchase = Purchase::orderBy('id', 'desc')->first();
        $lastId = $lastPurchase ? $lastPurchase->id : 0;
        $nextId = $lastId + 1;

        $invoiceNumber = 'PO-' . date('Ymd') . '-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);

        return view('purchases.create', compact('suppliers', 'products', 'productsForJs', 'invoiceNumber'));
    }


    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'supplier_id' => 'required|exists:suppliers,id',
    //         'date' => 'required|date',
    //         'product_id' => 'required|array|min:1',
    //         'product_id.*' => 'required|exists:products,id',
    //         'quantity' => 'required|array|min:1',
    //         'quantity.*' => 'required|integer|min:1',
    //         'purchase_price' => 'required|array|min:1',
    //         'purchase_price.*' => 'required|numeric|min:0',
    //         'notes' => 'nullable|string',
    //     ]);

    //     try {
    //         // Create the purchase
    //         $purchase = new Purchase();
    //         $purchase->invoice_number = $this->generateInvoiceNumber();
    //         $purchase->supplier_id = $request->supplier_id;
    //         $purchase->user_id = Auth::id();
    //         $purchase->date = $request->date;
    //         $purchase->status = 'pending';
    //         $purchase->notes = $request->notes;

    //         // Calculate total amount
    //         $totalAmount = 0;
    //         for ($i = 0; $i < count($request->product_id); $i++) {
    //             $totalAmount += $request->quantity[$i] * $request->purchase_price[$i];
    //         }
    //         $purchase->total_amount = $totalAmount;
    //         $purchase->save();

    //         // Create purchase details
    //         for ($i = 0; $i < count($request->product_id); $i++) {
    //             $detail = new PurchaseDetail();
    //             $detail->purchase_id = $purchase->id;
    //             $detail->product_id = $request->product_id[$i];
    //             $detail->quantity = $request->quantity[$i];
    //             $detail->received_quantity = 0;
    //             $detail->purchase_price = $request->purchase_price[$i];
    //             $detail->subtotal = $request->quantity[$i] * $request->purchase_price[$i];
    //             $detail->save();

    //             // Update product supplier price if needed
    //             $product = Product::find($request->product_id[$i]);
    //             $supplier = Supplier::find($request->supplier_id);

    //             // Check if relationship exists and update price
    //             if (!$supplier->products()->where('product_id', $product->id)->exists()) {
    //                 $supplier->products()->attach($product->id, [
    //                     'purchase_price' => $request->purchase_price[$i]
    //                 ]);
    //             } else {
    //                 // Update the pivot if price has changed
    //                 $supplier->products()->updateExistingPivot($product->id, [
    //                     'purchase_price' => $request->purchase_price[$i]
    //                 ]);
    //             }
    //         }

    //         return redirect()->route('purchases.show', $purchase)
    //             ->with('success', 'Pembelian berhasil dibuat.');
    //     } catch (\Exception $e) {
    //         Log::error('Purchase creation failed: ' . $e->getMessage());
    //         return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()])->withInput();
    //     }
    // }


    public function store(Request $request)
    {
        try {
            // Mulai transaksi database
            DB::beginTransaction();

            $validated = $request->validate([
                'supplier_id' => 'required|exists:suppliers,id',
                'date' => 'required|date',
                'due_date' => 'required|date|after_or_equal:date',
                'reference_number' => 'nullable|string|max:255',
                'product_id' => 'required|array',
                'product_id.*' => 'required|exists:products,id',
                'quantity' => 'required|array',
                'quantity.*' => 'required|numeric|min:0',
                'unit_id' => 'required|array',
                'unit_id.*' => 'required|exists:unit_of_measures,id',
                'purchase_price' => 'required|array',
                'purchase_price.*' => 'required|numeric|min:0',
                'conversion_factor' => 'required|array',
                'conversion_factor.*' => 'required|numeric|min:0',
                'notes' => 'nullable|string',
            ]);

            // Validasi tidak ada duplikat produk + unit yang identik
            $productUnitPairs = [];
            foreach ($request->product_id as $index => $productId) {
                $unitId = $request->unit_id[$index];
                $pair = $productId . '-' . $unitId;

                if (in_array($pair, $productUnitPairs)) {
                    return back()->withInput()->with('error', 'Terdapat kombinasi produk dan unit yang sama. Silakan gabungkan jumlahnya atau pilih unit berbeda.');
                }

                $productUnitPairs[] = $pair;
            }

            // Create purchase
            $purchase = Purchase::create([
                'invoice_number' => $this->generateInvoiceNumber(),
                'supplier_id' => $request->supplier_id,
                'user_id' => Auth::id(),
                'date' => $request->date,
                'due_date' => $request->due_date,
                'reference_number' => $request->reference_number,
                'status' => 'pending',
                'notes' => $request->notes,
                'total_amount' => 0,
            ]);

            $totalAmount = 0;

            // Add purchase details
            foreach ($request->product_id as $index => $productId) {
                $quantity = $request->quantity[$index];
                $unitId = $request->unit_id[$index];
                $purchasePrice = $request->purchase_price[$index];
                $conversionFactor = $request->conversion_factor[$index];

                $productUnit = ProductUnit::where('product_id', $productId)
                    ->where('unit_id', $unitId)
                    ->first();

                if (!$productUnit) {
                    DB::rollBack();
                    return back()->withInput()->withErrors([
                        "unit_id.{$index}" => "Unit ini tidak valid untuk produk tersebut"
                    ]);
                }

                // Periksa kecocokan conversion factor
                if (abs($productUnit->conversion_factor - $request->conversion_factor[$index]) > 0.00001) {
                    DB::rollBack();
                    return back()->withInput()->withErrors([
                        "conversion_factor.{$index}" => "Faktor konversi tidak cocok dengan yang terdaftar ({$productUnit->conversion_factor})"
                    ]);
                }

                // Calculate base quantity (in the product's base unit)
                $baseQuantity = $quantity * $conversionFactor;

                $subtotal = $quantity * $purchasePrice;
                $totalAmount += $subtotal;

                $purchase->purchaseDetails()->create([
                    'product_id' => $productId,
                    'unit_id' => $unitId,
                    'quantity' => $quantity,
                    'base_quantity' => $baseQuantity,
                    'received_quantity' => 0,
                    'purchase_price' => $purchasePrice,
                    'subtotal' => $subtotal,
                    'conversion_factor' => $conversionFactor,
                ]);

                // Update product supplier price if needed
                $product = Product::find($productId);
                $supplier = Supplier::find($request->supplier_id);

                // Check if relationship exists and update price
                if (!$supplier->products()->where('product_id', $productId)->exists()) {
                    $supplier->products()->attach($productId, [
                        'purchase_price' => $purchasePrice
                    ]);
                } else {
                    // Update the pivot if price has changed
                    $supplier->products()->updateExistingPivot($productId, [
                        'purchase_price' => $purchasePrice
                    ]);
                }
            }

            // Update total amount
            $purchase->update(['total_amount' => $totalAmount]);

            DB::commit();
            return redirect()->route('purchases.show', $purchase)
                ->with('success', 'Pembelian berhasil dibuat.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Purchase creation error: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified purchase.
     */
    public function show(Purchase $purchase)
    {
        // Eager load relationships to avoid N+1 queries
        $purchase->load(['supplier', 'user', 'purchaseDetails.product', 'receipts.receiptDetails']);

        return view('purchases.show', compact('purchase'));
    }

    /**
     * Show the form for editing the specified purchase.
     */
    public function edit(Purchase $purchase)
    {
        if ($purchase->status !== 'pending') {
            return redirect()->route('purchases.show', $purchase)
                ->with('error', 'Pembelian yang sudah diproses tidak dapat diedit.');
        }

        $purchase->load('purchaseDetails.product');
        $suppliers = Supplier::orderBy('name')->get();
        $products = Product::with('category')->orderBy('name')->get();

        return view('purchases.edit', compact('purchase', 'suppliers', 'products'));
    }

    /**
     * Update the specified purchase in storage.
     */
    public function update(Request $request, Purchase $purchase)
    {
        if ($purchase->status !== 'pending') {
            return redirect()->route('purchases.show', $purchase)
                ->with('error', 'Pembelian yang sudah diproses tidak dapat diubah.');
        }

        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'date' => 'required|date',
            'products' => 'required|array|min:1',
            'products.*.id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
            'products.*.price' => 'required|numeric|min:0',
        ]);

        try {
            // Begin transaction
            DB::beginTransaction();

            // Calculate total amount
            $totalAmount = 0;
            foreach ($request->products as $product) {
                $totalAmount += $product['quantity'] * $product['price'];
            }

            // Update purchase
            $purchase->update([
                'supplier_id' => $request->supplier_id,
                'date' => Carbon::parse($request->date),
                'total_amount' => $totalAmount,
                'notes' => $request->notes,
            ]);

            // Delete existing purchase details
            $purchase->purchaseDetails()->delete();

            // Create new purchase details
            foreach ($request->products as $item) {
                PurchaseDetail::create([
                    'purchase_id' => $purchase->id,
                    'product_id' => $item['id'],
                    'quantity' => $item['quantity'],
                    'received_quantity' => 0,
                    'purchase_price' => $item['price'],
                    'subtotal' => $item['quantity'] * $item['price'],
                ]);
            }

            DB::commit();
            return redirect()->route('purchases.show', $purchase)
                ->with('success', 'Pembelian berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Purchase update failed: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Remove the specified purchase from storage.
     */
    public function destroy(Purchase $purchase)
    {
        if ($purchase->status !== 'pending') {
            return redirect()->route('purchases.show', $purchase)
                ->with('error', 'Pembelian yang sudah diproses tidak dapat dihapus.');
        }

        try {
            $purchase->delete();
            return redirect()->route('purchases.index')
                ->with('success', 'Pembelian berhasil dihapus.');
        } catch (\Exception $e) {
            Log::error('Purchase deletion failed: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    /**
     * Show the form for creating a receipt for a purchase.
     */
    public function createReceipt(Purchase $purchase)
    {
        if ($purchase->isReceived()) {
            return redirect()->route('purchases.show', $purchase)
                ->with('error', 'Pembelian ini sudah diterima sepenuhnya.');
        }

        $purchase->load(['supplier', 'purchaseDetails.product']);

        // Generate receipt number based on date and sequence
        $today = now()->format('Ymd');
        $lastReceipt = PurchaseReceipt::where('receipt_number', 'like', "RCV-{$today}-%")
            ->orderBy('receipt_number', 'desc')
            ->first();

        $sequence = 1;
        if ($lastReceipt) {
            $parts = explode('-', $lastReceipt->receipt_number);
            $sequence = intval(end($parts)) + 1;
        }

        $receiptNumber = "RCV-{$today}-" . str_pad($sequence, 4, '0', STR_PAD_LEFT);

        return view('purchases.receipt', compact('purchase', 'receiptNumber'));
    }

    /**
     * Store a newly created receipt in storage.
     */
    public function storeReceipt(Request $request, Purchase $purchase)
    {
        if ($purchase->isReceived()) {
            return redirect()->route('purchases.show', $purchase)
                ->with('error', 'Pembelian ini sudah diterima sepenuhnya.');
        }

        $request->validate([
            'receipt_date' => 'required|date',
            'receipt_number' => 'required|string|max:255',
            'items' => 'required|array',
            'items.*.purchase_detail_id' => 'required|exists:purchase_details,id',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.received_quantity' => 'required|integer|min:0',
            'notes' => 'nullable|string',
            'receipt_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        // Validasi server-side minimal 1 item diterima
        $receivedItems = collect($request->items)->filter(fn($item) => $item['received_quantity'] > 0);
        if ($receivedItems->isEmpty()) {
            return back()->withErrors(['error' => 'Setidaknya satu item harus diterima'])->withInput();
        }

        try {
            // Begin transaction
            DB::beginTransaction();

            // Create receipt
            $receipt = new PurchaseReceipt();
            $receipt->purchase_id = $purchase->id;
            $receipt->user_id = Auth::id();
            $receipt->receipt_number = $request->receipt_number;
            $receipt->receipt_date = $request->receipt_date;
            $receipt->notes = $request->notes;

            // Handle file upload
            if ($request->hasFile('receipt_file')) {
                $file = $request->file('receipt_file');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->storeAs('receipts', $filename, 'public');
                $receipt->receipt_file = $filename;
            }

            $receipt->save();

            $allReceived = true;
            $totalReceived = 0;

            foreach ($request->items as $item) {
                if ($item['received_quantity'] > 0) {
                    $purchaseDetail = PurchaseDetail::findOrFail($item['purchase_detail_id']);
                    $product = Product::findOrFail($item['product_id']);

                    // Validate that received quantity doesn't exceed remaining quantity
                    $remainingQty = $purchaseDetail->quantity - $purchaseDetail->received_quantity;
                    $receivedQty = min($item['received_quantity'], $remainingQty);

                    // TAMBAHKAN BARIS INI - Hitung jumlah berdasarkan konversi
                    $baseQuantityReceived = $receivedQty * $purchaseDetail->conversion_factor;

                    // Create receipt detail
                    $receipt->receiptDetails()->create([
                        'purchase_detail_id' => $purchaseDetail->id,
                        'product_id' => $item['product_id'],
                        'received_quantity' => $receivedQty,
                    ]);

                    // Update purchase detail received quantity
                    $purchaseDetail->received_quantity += $receivedQty;
                    $purchaseDetail->save();

                    if ($purchaseDetail->received_quantity < $purchaseDetail->quantity) {
                        $allReceived = false;
                    }

                    // Store the current stock before updating
                    $beforeStock = $product->stock;

                    // UBAH BARIS INI - Gunakan baseQuantityReceived, bukan receivedQty
                    // Update product stock
                    $product->stock += $baseQuantityReceived; // Menggunakan jumlah yang telah dikonversi
                    $product->save();

                    // Update stock movement juga
                    StockMovement::create([
                        'product_id' => $product->id,
                        'type' => 'in',
                        'quantity' => $baseQuantityReceived, // Ubah ini juga
                        'before_stock' => $beforeStock,
                        'after_stock' => $product->stock,
                        'reference_type' => 'purchase_receipt',
                        'reference_id' => $receipt->id,
                        'notes' => "Penerimaan pembelian #{$purchase->invoice_number}"
                    ]);

                    $totalReceived += $receivedQty;
                }
            }

            // Update purchase status
            $purchase->status = $allReceived ? 'received' : 'partially_received';
            $purchase->save();

            $this->createReceiptNotification($receipt, $purchase, $totalReceived);

            DB::commit();
            return redirect()->route('purchases.show', $purchase)
                ->with('success', "Penerimaan barang berhasil dicatat. $totalReceived item telah diterima.");
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Receipt creation failed: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()])->withInput();
        }
    }
    /**
     * Generate a unique invoice number.
     */
    private function generateInvoiceNumber()
    {
        $prefix = 'PO-' . date('Ymd');
        $lastPurchase = Purchase::where('invoice_number', 'like', $prefix . '%')
            ->withoutTrashed()
            ->orderBy('invoice_number', 'desc')
            ->first();

        if ($lastPurchase) {
            $lastNumber = intval(substr($lastPurchase->invoice_number, -4));
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        $invoiceNumber = $prefix . '-' . str_pad($newNumber, 4, '0', STR_PAD_LEFT);

        // Check if the generated invoice number already exists (including trashed records)
        // If it does, increment until we find a unique one
        while (Purchase::withTrashed()->where('invoice_number', $invoiceNumber)->exists()) {
            $newNumber++;
            $invoiceNumber = $prefix . '-' . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
        }

        return $invoiceNumber;
    }
    // public function getProductsBySupplier($supplierId)
    // {
    //     $products = Product::with(['suppliers' => function($query) use ($supplierId) {
    //         $query->where('supplier_id', $supplierId);
    //     }, 'units'])
    //     ->whereHas('suppliers', function($query) use ($supplierId) {
    //         $query->where('supplier_id', $supplierId);
    //     })
    //     ->orWhere(function($query) {
    //         // Include products not associated with any supplier
    //         $query->whereDoesntHave('suppliers');
    //     })
    //     ->get();

    //     // Add stock information to each product
    //     $products->each(function ($product) {
    //         $product->stock = $product->current_stock;
    //     });

    //     return response()->json($products);
    // }
    /**
     * Get units for a specific product
     */
    // public function getProductUnits($productId)
    // {
    //     $product = Product::with(['units' => function($query) {
    //         $query->withPivot('conversion_factor', 'purchase_price', 'is_default');
    //     }])->findOrFail($productId);

    //     // Get default unit
    //     $defaultUnit = $product->units->firstWhere('pivot.is_default', 1);
    //     $defaultUnitName = $defaultUnit ? $defaultUnit->abbreviation : 'pcs';

    //     // Get last purchase information
    //     $lastPurchase = DB::table('purchase_details')
    //         ->join('purchases', 'purchase_details.purchase_id', '=', 'purchases.id')
    //         ->where('purchase_details.product_id', $productId)
    //         ->where('purchases.status', 'completed')
    //         ->orderBy('purchases.purchase_date', 'desc')
    //         ->select('purchases.purchase_date', 'purchase_details.purchase_price')
    //         ->first();

    //     $lastPurchaseInfo = null;
    //     if ($lastPurchase) {
    //         $lastPurchaseInfo = [
    //             'date' => \Carbon\Carbon::parse($lastPurchase->purchase_date)->format('d M Y'),
    //             'price' => $lastPurchase->purchase_price
    //         ];
    //     }

    //     return response()->json([
    //         'units' => $product->units,
    //         'stock' => $product->current_stock,
    //         'default_unit' => $defaultUnitName,
    //         'last_purchase' => $lastPurchaseInfo
    //     ]);
    // }
    public function processReceipt(Request $request, Purchase $purchase)
    {
        try {
            // Mulai transaksi database
            DB::beginTransaction();

            // Validate request
            $validated = $request->validate([
                'date' => 'required|date',
                'notes' => 'nullable|string',
                'detail_id' => 'required|array',
                'detail_id.*' => 'required|exists:purchase_details,id',
                'received_quantity' => 'required|array',
                'received_quantity.*' => 'required|numeric|min:0',
            ]);

            // Validasi minimal 1 item diterima
            $receivedItems = collect($request->received_quantity)->filter(fn($qty) => $qty > 0);
            if ($receivedItems->isEmpty()) {
                return back()->withErrors(['error' => 'Setidaknya satu item harus diterima'])->withInput();
            }

            // Create receipt
            $receipt = PurchaseReceipt::create([
                'purchase_id' => $purchase->id,
                'user_id' => Auth::id(),
                'receipt_number' => 'RCV-' . date('Ymd') . '-' . str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT),
                'receipt_date' => $request->date,
                'notes' => $request->notes,
            ]);

            $allReceived = true;
            $totalReceived = 0;

            // Process each detail
            foreach ($request->detail_id as $index => $detailId) {
                $receivedQuantity = $request->received_quantity[$index];

                if ($receivedQuantity <= 0) {
                    continue;
                }

                $detail = PurchaseDetail::findOrFail($detailId);

                // Validate that received quantity doesn't exceed remaining quantity
                $remainingQty = $detail->quantity - $detail->received_quantity;
                if ($receivedQuantity > $remainingQty) {
                    DB::rollBack();
                    return back()->withErrors(['error' => 'Jumlah penerimaan melebihi sisa yang belum diterima'])->withInput();
                }

                // Calculate the base quantity received (using the conversion factor)
                $baseQuantityReceived = $receivedQuantity * $detail->conversion_factor;

                // Create receipt detail
                $receiptDetail = $receipt->details()->create([
                    'purchase_detail_id' => $detailId,
                    'product_id' => $detail->product_id,
                    'quantity' => $receivedQuantity,
                    'base_quantity' => $baseQuantityReceived,
                ]);

                // Get product and update stock
                $product = Product::findOrFail($detail->product_id);
                $beforeStock = $product->stock;
                $product->increment('stock', $baseQuantityReceived);

                // Create stock movement record
                StockMovement::create([
                    'product_id' => $detail->product_id,
                    'quantity' => $baseQuantityReceived,
                    'type' => 'in',
                    'before_stock' => $beforeStock,
                    'after_stock' => $product->stock,
                    'reference_id' => $receipt->id,
                    'reference_type' => 'purchase_receipt',
                    'notes' => 'Penerimaan barang pembelian #' . $purchase->invoice_number,
                ]);

                // Update received quantity on purchase detail
                $detail->increment('received_quantity', $receivedQuantity);

                // Check if this item is fully received
                if ($detail->received_quantity < $detail->quantity) {
                    $allReceived = false;
                }

                $totalReceived += $receivedQuantity;
            }

            // Update purchase status if all items are received
            if ($allReceived) {
                $purchase->update(['status' => 'received']);
            } else {
                $purchase->update(['status' => 'partially_received']);
            }

            DB::commit();
            return redirect()->route('purchases.show', $purchase)
                ->with('success', "Penerimaan barang berhasil diproses. $totalReceived item telah diterima.");
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Receipt processing error: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()])->withInput();
        }
    }
    // public function searchProducts(Request $request)
    // {
    //     $query = $request->input('query');
    //     $supplierId = $request->input('supplier_id');

    //     if (empty($supplierId)) {
    //         return response()->json([]);
    //     }

    //     $products = Product::where('name', 'like', "%{$query}%")
    //         ->whereHas('suppliers', function ($q) use ($supplierId) {
    //             $q->where('supplier_id', $supplierId);
    //         })
    //         ->orWhereDoesntHave('suppliers') // Include products not associated with any supplier
    //         ->with(['suppliers' => function ($q) use ($supplierId) {
    //             $q->where('supplier_id', $supplierId);
    //         }, 'units'])
    //         ->limit(10)
    //         ->get();

    //     // Add stock information to each product
    //     $products->each(function ($product) {
    //         $product->stock = $product->current_stock;
    //     });

    //     return response()->json($products);
    // }
    public function getProductsBySupplier($supplierId)
    {
        $products = Product::with(['suppliers' => function ($query) use ($supplierId) {
            $query->where('supplier_id', $supplierId);
        }, 'units'])
            ->whereHas('suppliers', function ($query) use ($supplierId) {
                $query->where('supplier_id', $supplierId);
            })
            ->orWhere(function ($query) {
                // Include products not associated with any supplier
                $query->whereDoesntHave('suppliers');
            })
            ->get();

        // Add stock information to each product
        $products->each(function ($product) {
            $product->stock = $product->current_stock;
        });

        return response()->json($products);
    }

    /**
     * Get product units and stock information
     */
    public function getProductUnits($productId)
    {
        try {
            $product = Product::with(['units' => function ($query) {
                $query->withPivot('conversion_factor', 'purchase_price', 'is_default');
            }])->findOrFail($productId);

            // Get default unit
            $defaultUnit = $product->units->firstWhere('pivot.is_default', 1);
            $defaultUnitName = $defaultUnit ? $defaultUnit->abbreviation : 'pcs';

            // Get last purchase information
            $lastPurchase = DB::table('purchase_details')
                ->join('purchases', 'purchase_details.purchase_id', '=', 'purchases.id')
                ->where('purchase_details.product_id', $productId)
                ->whereIn('purchases.status', ['received', 'partially_received'])
                ->orderBy('purchases.date', 'desc')
                ->select('purchases.date', 'purchase_details.purchase_price')
                ->first();

            $lastPurchaseInfo = null;
            if ($lastPurchase) {
                $lastPurchaseInfo = [
                    'date' => Carbon::parse($lastPurchase->date)->format('d M Y'),
                    'price' => $lastPurchase->purchase_price
                ];
            }

            return response()->json([
                'units' => $product->units,
                'stock' => $product->stock,
                'default_unit' => $defaultUnitName,
                'last_purchase' => $lastPurchaseInfo,
                'min_stock' => $product->min_stock,
                'has_low_stock' => $product->stock < $product->min_stock
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Gagal mendapatkan informasi produk: ' . $e->getMessage()
            ], 500);
        }
    }


    /**
     * Search products by name for a specific supplier
     */
    public function searchProducts(Request $request)
    {
        $query = $request->input('query');
        $supplierId = $request->input('supplier_id');

        if (empty($supplierId)) {
            return response()->json([]);
        }

        // Search by name, code, or category
        $products = Product::where(function ($q) use ($query) {
            $q->where('name', 'like', "%{$query}%")
                ->orWhere('code', 'like', "%{$query}%");
        })
            ->with(['category', 'suppliers' => function ($q) use ($supplierId) {
                $q->where('supplier_id', $supplierId);
            }, 'units' => function ($q) {
                $q->wherePivot('is_default', 1);
            }])
            ->whereHas('suppliers', function ($q) use ($supplierId) {
                $q->where('supplier_id', $supplierId);
            })
            ->orWhere(function ($q) use ($query) {
                // Include products not associated with any supplier
                $q->where('name', 'like', "%{$query}%")
                    ->whereDoesntHave('suppliers');
            })
            ->limit(10)
            ->get();

        // Add additional info to each product
        $products->each(function ($product) {
            // Get current stock
            $product->stock = $product->stock;

            // Get default unit
            $defaultUnit = $product->units->first();
            $product->default_unit = $defaultUnit ? $defaultUnit->abbreviation : 'pcs';

            // Get supplier-specific price if available
            $supplierInfo = $product->suppliers->first();
            if ($supplierInfo && isset($supplierInfo->pivot->purchase_price)) {
                $product->purchase_price = $supplierInfo->pivot->purchase_price;
            } else {
                // Use default purchase price if no supplier-specific price
                $product->purchase_price = $product->purchase_price;
            }

            // Add category info
            $product->category_name = $product->category ? $product->category->name : '';

            // Add low stock indicator
            $product->is_low_stock = $product->stock < $product->min_stock;
        });

        return response()->json($products);
    }
    public function findProductByBarcode(Request $request)
    {
        $barcode = $request->input('barcode');

        if (!$barcode) {
            return response()->json([
                'success' => false,
                'message' => 'Barcode tidak diberikan'
            ]);
        }

        // Cari di tabel product_units
        $productUnit = ProductUnit::where('barcode', $barcode)->first();

        if (!$productUnit) {
            return response()->json([
                'success' => false,
                'message' => 'Produk dengan barcode tersebut tidak ditemukan'
            ]);
        }

        // Load product dan unit
        $product = $productUnit->product;
        $unit = $productUnit->unit;

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Produk tidak ditemukan'
            ]);
        }

        return response()->json([
            'success' => true,
            'product' => [
                'id' => $product->id,
                'name' => $product->name,
                'code' => $product->code
            ],
            'unit' => [
                'id' => $unit->id,
                'name' => $unit->name,
                'abbreviation' => $unit->abbreviation
            ],
            'conversion_factor' => $productUnit->conversion_factor
        ]);
    }
    protected function createReceiptNotification($receipt, $purchase, $totalItems)
    {
        // Notifikasi untuk admin/manajer
        $adminUsers = User::whereHas('roles', function ($q) {
            $q->where('name', 'admin')->orWhere('name', 'manager');
        })->get();

        foreach ($adminUsers as $admin) {
            Notification::create([
                'user_id' => $admin->id,
                'title' => 'Penerimaan Barang Baru',
                'content' => "Penerimaan untuk pembelian #{$purchase->invoice_number} telah dicatat. {$totalItems} item diterima.",
                'type' => 'purchase_receipt',
                'reference_id' => $receipt->id,
                'read' => false
            ]);
        }

        // Notifikasi untuk stok yang perlu perhatian
        $lowStockProducts = [];

        foreach ($receipt->receiptDetails as $detail) {
            $product = $detail->product;

            // Check if product stock is below minimum after receipt
            if ($product->stock < $product->min_stock) {
                $lowStockProducts[] = $product;
            }
        }

        if (count($lowStockProducts) > 0) {
            $productNames = collect($lowStockProducts)->pluck('name')->implode(', ');

            foreach ($adminUsers as $admin) {
                Notification::create([
                    'user_id' => $admin->id,
                    'title' => 'Peringatan Stok Rendah',
                    'content' => "Setelah penerimaan, produk berikut masih memiliki stok di bawah minimum: {$productNames}",
                    'type' => 'low_stock',
                    'reference_id' => null,
                    'read' => false
                ]);
            }
        }
    }
    public function findByBarcode(Request $request)
    {
        $barcode = $request->input('barcode');
        $supplierId = $request->input('supplier_id');

        if (!$barcode) {
            return response()->json([
                'success' => false,
                'message' => 'Barcode tidak diberikan'
            ]);
        }

        // Cari di tabel product_units
        $productUnit = DB::table('product_units')
            ->where('barcode', $barcode)
            ->first();

        if (!$productUnit) {
            return response()->json([
                'success' => false,
                'message' => 'Produk dengan barcode tersebut tidak ditemukan'
            ]);
        }

        // Load product
        $product = Product::find($productUnit->product_id);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Produk tidak ditemukan'
            ]);
        }

        // Cek apakah produk tersedia untuk supplier yang dipilih
        if ($supplierId) {
            $hasSupplier = DB::table('product_supplier')
                ->where('product_id', $product->id)
                ->where('supplier_id', $supplierId)
                ->exists();

            if (!$hasSupplier) {
                return response()->json([
                    'success' => false,
                    'message' => 'Produk ini tidak terkait dengan supplier yang dipilih'
                ]);
            }
        }

        // Load unit
        $unit = UnitOfMeasure::find($productUnit->unit_id);

        return response()->json([
            'success' => true,
            'product' => [
                'id' => $product->id,
                'name' => $product->name,
                'code' => $product->code,
                'stock' => $product->stock
            ],
            'unit' => $unit ? [
                'id' => $unit->id,
                'name' => $unit->name,
                'abbreviation' => $unit->abbreviation
            ] : null,
            'conversion_factor' => $productUnit->conversion_factor,
            'default_price' => $productUnit->purchase_price
        ]);
    }
}
