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
use App\Services\FifoService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use App\Helpers\DateValidationHelper;

class PurchaseController extends Controller
{
    /**
     * Display a listing of purchases.
     */
    public function index()
    {
        // Eager load relationships to avoid N+1 queries
        $purchases = Purchase::with(['supplier', 'user', 'purchaseGroup'])
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

        // Ambil semua produk seperti di SalesController - tanpa filter supplier
        $products = Product::orderBy('name')->get();

        // Generate invoice number
        $lastPurchase = Purchase::orderBy('id', 'desc')->first();
        $lastId = $lastPurchase ? $lastPurchase->id : 0;
        $nextId = $lastId + 1;

        $invoiceNumber = 'PO-' . date('Ymd') . '-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);

        return view('purchases.create', compact('suppliers', 'products', 'invoiceNumber'));
    }





    public function store(Request $request)
    {
        try {
            // Log the incoming request for debugging
            Log::info('Purchase store method called', [
                'request_data' => $request->all(),
                'user_id' => Auth::id()
            ]);

            // Mulai transaksi database
            DB::beginTransaction();

            $validated = $request->validate([
                'date' => 'required|date',
                'due_date' => 'required|date|after_or_equal:date',
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

            Log::info('Purchase validation passed', ['validated_data' => $validated]);

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

            // Group products by supplier
            Log::info('Starting to group products by supplier');
            $supplierGroups = [];
            
            foreach ($request->product_id as $index => $productId) {
                Log::info('Processing product', ['product_id' => $productId, 'index' => $index]);
                
                $product = Product::find($productId);
                
                // Get supplier for this product
                $supplierId = null;
                
                // First check if product has a direct supplier_id
                if ($product->supplier_id) {
                    $supplierId = $product->supplier_id;
                    Log::info('Product supplier', [
                        'product_id' => $productId, 
                        'supplier_id' => $supplierId,
                        'from_pivot' => false,
                        'from_product' => $product->supplier_id
                    ]);
                } else {
                    // Check pivot table for supplier relationship
                    $supplierRelation = $product->suppliers()->first();
                    if ($supplierRelation) {
                        $supplierId = $supplierRelation->id;
                        Log::info('Product supplier', [
                            'product_id' => $productId, 
                            'supplier_id' => $supplierId,
                            'from_pivot' => true
                        ]);
                    }
                }

                // If no supplier found, create a default "Unknown Supplier" entry
                if (!$supplierId) {
                    $supplierId = 'unknown';
                    Log::warning('No supplier found for product', ['product_id' => $productId]);
                }

                // Group by supplier
                if (!isset($supplierGroups[$supplierId])) {
                    $supplierGroups[$supplierId] = [];
                }

                $supplierGroups[$supplierId][] = [
                    'product_id' => $productId,
                    'quantity' => $request->quantity[$index],
                    'unit_id' => $request->unit_id[$index],
                    'purchase_price' => $request->purchase_price[$index],
                    'conversion_factor' => $request->conversion_factor[$index],
                ];
            }

            Log::info('Products grouped by supplier', ['groups' => array_keys($supplierGroups)]);

            // Create a purchase group first
            $purchaseGroup = \App\Models\PurchaseGroup::create([
                'group_number' => $this->generateGroupNumber(),
                'user_id' => Auth::id(),
                'date' => $request->date,
                'due_date' => $request->due_date,
                'notes' => $request->notes,
                'total_amount' => 0,
                'status' => 'draft'
            ]);

            $groupTotalAmount = 0;
            $createdPurchases = [];

            // Create separate purchase orders for each supplier
            foreach ($supplierGroups as $supplierId => $products) {
                Log::info('Creating purchase for supplier', ['supplier_id' => $supplierId, 'product_count' => count($products)]);

                $purchase = Purchase::create([
                    'purchase_number' => $this->generatePurchaseNumber($supplierId),
                    'purchase_group_id' => $purchaseGroup->id,
                    'supplier_id' => $supplierId === 'unknown' ? null : $supplierId,
                    'user_id' => Auth::id(),
                    'date' => $request->date,
                    'due_date' => $request->due_date,
                    'status' => 'pending',
                    'notes' => $request->notes,
                    'total_amount' => 0,
                ]);

                $purchaseTotalAmount = 0;

                // Add purchase details for this supplier
                foreach ($products as $productData) {
                    $productUnit = ProductUnit::where('product_id', $productData['product_id'])
                        ->where('unit_id', $productData['unit_id'])
                        ->first();

                    if (!$productUnit) {
                        DB::rollBack();
                        return back()->withInput()->withErrors([
                            "unit_id" => "Unit tidak valid untuk produk tersebut"
                        ]);
                    }

                    // Periksa kecocokan conversion factor
                    if (abs($productUnit->conversion_factor - $productData['conversion_factor']) > 0.00001) {
                        DB::rollBack();
                        return back()->withInput()->withErrors([
                            "conversion_factor" => "Faktor konversi tidak cocok dengan yang terdaftar ({$productUnit->conversion_factor})"
                        ]);
                    }

                    // Calculate base quantity (in the product's base unit)
                    $baseQuantity = $productData['quantity'] * $productData['conversion_factor'];

                    $subtotal = $productData['quantity'] * $productData['purchase_price'];
                    $purchaseTotalAmount += $subtotal;

                    $purchase->purchaseDetails()->create([
                        'product_id' => $productData['product_id'],
                        'unit_id' => $productData['unit_id'],
                        'quantity' => $productData['quantity'],
                        'base_quantity' => $baseQuantity,
                        'received_quantity' => 0,
                        'purchase_price' => $productData['purchase_price'],
                        'subtotal' => $subtotal,
                        'conversion_factor' => $productData['conversion_factor'],
                    ]);

                    // Update product supplier price if needed and supplier exists
                    if ($supplierId !== 'unknown') {
                        $supplier = Supplier::find($supplierId);
                        if ($supplier) {
                            // Check if relationship exists and update price
                            if (!$supplier->products()->where('product_id', $productData['product_id'])->exists()) {
                                $supplier->products()->attach($productData['product_id'], [
                                    'purchase_price' => $productData['purchase_price']
                                ]);
                            } else {
                                // Update the pivot if price has changed
                                $supplier->products()->updateExistingPivot($productData['product_id'], [
                                    'purchase_price' => $productData['purchase_price']
                                ]);
                            }
                        }
                    }
                }

                // Update purchase total amount
                $purchase->update(['total_amount' => $purchaseTotalAmount]);
                $groupTotalAmount += $purchaseTotalAmount;
                $createdPurchases[] = $purchase;

                Log::info('Purchase created', [
                    'purchase_id' => $purchase->id,
                    'supplier_id' => $supplierId,
                    'total_amount' => $purchaseTotalAmount
                ]);
            }

            // Update purchase group total amount
            $purchaseGroup->update(['total_amount' => $groupTotalAmount]);

            DB::commit();

            Log::info('Purchase group created successfully', [
                'group_id' => $purchaseGroup->id,
                'total_purchases' => count($createdPurchases),
                'total_amount' => $groupTotalAmount
            ]);

            return redirect()->route('purchases.group.show', $purchaseGroup)
                ->with('success', 'Pembelian berhasil dibuat. ' . count($createdPurchases) . ' purchase order telah dibuat untuk ' . count($supplierGroups) . ' supplier.');
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
     * Display the specified purchase group.
     */
    public function showGroup(\App\Models\PurchaseGroup $purchaseGroup)
    {
        $purchaseGroup->load([
            'purchases.supplier', 
            'purchases.purchaseDetails.product', 
            'purchases.purchaseDetails.unit',
            'user'
        ]);
        
        return view('purchases.group-show', compact('purchaseGroup'));
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
            'items.*.expire_date' => DateValidationHelper::getExpireDateRule(),
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

                    // Hitung jumlah berdasarkan konversi
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
                    
                    // Buat batch baru menggunakan FIFO Service
                    $fifoService = new FifoService();
                    $batch = $fifoService->addBatch(
                        $product->id,
                        $purchase->id,
                        $baseQuantityReceived,
                        $purchaseDetail->purchase_price,
                        null, // Batch number akan digenerate otomatis
                        $item['expire_date'] ?? null  // Ambil expiry date dari form input
                    );

                    if ($purchaseDetail->received_quantity < $purchaseDetail->quantity) {
                        $allReceived = false;
                    }

                    // Store the current stock before updating
                    $beforeStock = $product->actual_stock;

                    // Sync product stock with batches (FifoService already handles batch creation)
                    $product->syncStockFromBatches();
                    $product->refresh(); // Refresh to get updated actual_stock

                    // Update stock movement
                    StockMovement::create([
                        'product_id' => $product->id,
                        'type' => 'in',
                        'quantity' => $baseQuantityReceived,
                        'before_stock' => $beforeStock,
                        'after_stock' => $product->actual_stock,
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

    private function generateGroupNumber()
    {
        $today = now()->format('Ymd');
        $prefix = 'PG-' . $today;

        // Cari nomor terakhir untuk hari ini
        $lastGroup = \App\Models\PurchaseGroup::where('group_number', 'like', $prefix . '%')
            ->orderBy('group_number', 'desc')
            ->first();

        if (!$lastGroup) {
            $number = 1;
        } else {
            // Ambil 4 digit terakhir dan tambahkan 1
            $lastNumber = (int) substr($lastGroup->group_number, -4);
            $number = $lastNumber + 1;
        }

        $groupNumber = $prefix . str_pad($number, 4, '0', STR_PAD_LEFT);

        // Pastikan nomor unik
        while (\App\Models\PurchaseGroup::where('group_number', $groupNumber)->exists()) {
            $number++;
            $groupNumber = $prefix . str_pad($number, 4, '0', STR_PAD_LEFT);
        }

        return $groupNumber;
    }

    private function generatePurchaseNumber($supplierId)
    {
        $today = now()->format('Ymd');
        
        // Get supplier code for prefix
        $supplierCode = 'UNK'; // Default for unknown supplier
        if ($supplierId !== 'unknown' && $supplierId) {
            $supplier = Supplier::find($supplierId);
            if ($supplier) {
                // Use first 3 characters of supplier name or code
                $supplierCode = strtoupper(substr(preg_replace('/[^A-Za-z0-9]/', '', $supplier->name), 0, 3));
                if (strlen($supplierCode) < 3) {
                    $supplierCode = str_pad($supplierCode, 3, '0', STR_PAD_RIGHT);
                }
            }
        }
        
        $prefix = 'PO-' . $supplierCode . '-' . $today;

        // Cari nomor terakhir untuk supplier dan hari ini
        $lastPurchase = Purchase::where('purchase_number', 'like', $prefix . '%')
            ->orderBy('purchase_number', 'desc')
            ->first();

        if (!$lastPurchase) {
            $number = 1;
        } else {
            // Ambil 4 digit terakhir dan tambahkan 1
            $lastNumber = (int) substr($lastPurchase->purchase_number, -4);
            $number = $lastNumber + 1;
        }

        $purchaseNumber = $prefix . '-' . str_pad($number, 4, '0', STR_PAD_LEFT);

        // Pastikan nomor unik
        while (Purchase::where('purchase_number', $purchaseNumber)->exists()) {
            $number++;
            $purchaseNumber = $prefix . '-' . str_pad($number, 4, '0', STR_PAD_LEFT);
        }

        return $purchaseNumber;
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
                $beforeStock = $product->actual_stock;
                $product->increment('stock', $baseQuantityReceived);
                
                // Sync stock from batches to ensure consistency
                $product->syncStockFromBatches();
                $product->refresh(); // Refresh to get updated actual_stock

                // Create stock movement record
                StockMovement::create([
                    'product_id' => $detail->product_id,
                    'quantity' => $baseQuantityReceived,
                    'type' => 'in',
                    'before_stock' => $beforeStock,
                    'after_stock' => $product->actual_stock,
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
            $product->stock = $product->actual_stock;
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
                'stock' => $product->actual_stock,
                'default_unit' => $defaultUnitName,
                'last_purchase' => $lastPurchaseInfo,
                'min_stock' => $product->min_stock,
                'has_low_stock' => $product->actual_stock < $product->min_stock
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
            $product->stock = $product->actual_stock;

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
            $product->is_low_stock = $product->actual_stock < $product->min_stock;
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
            if ($product->actual_stock < $product->min_stock) {
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
                'stock' => $product->actual_stock
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

    /**
     * Print the specified purchase.
     */
    public function print(Purchase $purchase)
    {
        // Eager load relationships for printing
        $purchase->load([
            'supplier', 
            'user', 
            'purchaseDetails.product', 
            'purchaseDetails.unit',
            'receipts.receiptDetails'
        ]);

        return view('purchases.print', compact('purchase'));
    }
}
