<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseDetail;
use App\Models\PurchaseReceipt;
use App\Models\StockMovement;
use App\Models\Supplier;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
                // Jangan gunakan relasi supplier, cukup ambil ID saja
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
            $validated = $request->validate([
                'supplier_id' => 'required|exists:suppliers,id',
                'date' => 'required|date',
                'due_date' => 'required|date|after_or_equal:date',
                'reference_number' => 'nullable|string|max:255',
                'product_id' => 'required|array',
                'product_id.*' => 'required|exists:products,id',
                'quantity' => 'required|array',
                'quantity.*' => 'required|numeric|min:0.01',
                'unit_id' => 'required|array',
                'unit_id.*' => 'required|exists:unit_of_measures,id',
                'purchase_price' => 'required|array',
                'purchase_price.*' => 'required|numeric|min:0',
                'conversion_factor' => 'required|array',
                'conversion_factor.*' => 'required|numeric|min:0.00001',
                'notes' => 'nullable|string',
            ]);

            // Create purchase
            $purchase = Purchase::create([
                'supplier_id' => $request->supplier_id,
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

                // Calculate base quantity (in the product's base unit)
                $baseQuantity = $quantity * $conversionFactor;

                $subtotal = $quantity * $purchasePrice;
                $totalAmount += $subtotal;

                $purchase->details()->create([
                    'product_id' => $productId,
                    'unit_id' => $unitId,
                    'quantity' => $quantity,
                    'base_quantity' => $baseQuantity, // Store the converted quantity
                    'purchase_price' => $purchasePrice,
                    'subtotal' => $subtotal,
                    'conversion_factor' => $conversionFactor,
                ]);
            }

            // Update total amount
            $purchase->update(['total_amount' => $totalAmount]);

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

                    // Update product stock
                    $product->stock += $receivedQty;
                    $product->save();

                    // Create stock movement record with proper quotes around string values
                    StockMovement::create([
                        'product_id' => $product->id,
                        'type' => 'in',
                        'quantity' => $receivedQty,
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
        // Validate request
        $validated = $request->validate([
            'date' => 'required|date',
            'notes' => 'nullable|string',
            'detail_id' => 'required|array',
            'detail_id.*' => 'required|exists:purchase_details,id',
            'received_quantity' => 'required|array',
            'received_quantity.*' => 'required|numeric|min:0',
        ]);

        // Create receipt
        $receipt = PurchaseReceipt::create([
            'purchase_id' => $purchase->id,
            'date' => $request->date,
            'notes' => $request->notes,
        ]);

        // Process each detail
        foreach ($request->detail_id as $index => $detailId) {
            $receivedQuantity = $request->received_quantity[$index];

            if ($receivedQuantity <= 0) {
                continue;
            }

            $detail = PurchaseDetail::findOrFail($detailId);

            // Calculate the base quantity received (using the conversion factor)
            $baseQuantityReceived = $receivedQuantity * $detail->conversion_factor;

            // Create receipt detail
            $receiptDetail = $receipt->details()->create([
                'purchase_detail_id' => $detailId,
                'product_id' => $detail->product_id,
                'quantity' => $receivedQuantity,
                'base_quantity' => $baseQuantityReceived,
            ]);

            // Update stock - use the base quantity for stock updates
            StockMovement::create([
                'product_id' => $detail->product_id,
                'quantity' => $baseQuantityReceived,
                'type' => 'purchase',
                'reference_id' => $receipt->id,
                'reference_type' => 'purchase_receipt',
                'notes' => 'Penerimaan barang pembelian #' . $purchase->id,
            ]);

            // Update received quantity on purchase detail
            $detail->increment('received_quantity', $receivedQuantity);
        }

        // Check if all items are received
        $allReceived = $purchase->details()->get()->every(function ($detail) {
            return $detail->isFullyReceived();
        });

        // Update purchase status if all items are received
        if ($allReceived) {
            $purchase->update(['status' => 'completed']);
        } else {
            $purchase->update(['status' => 'partial']);
        }

        return redirect()->route('purchases.show', $purchase)
            ->with('success', 'Penerimaan barang berhasil diproses.');
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
            ->where('purchases.status', 'completed')
            ->orderBy('purchases.purchase_date', 'desc')
            ->select('purchases.purchase_date', 'purchase_details.purchase_price')
            ->first();

        $lastPurchaseInfo = null;
        if ($lastPurchase) {
            $lastPurchaseInfo = [
                'date' => Carbon::parse($lastPurchase->purchase_date)->format('d M Y'),
                'price' => $lastPurchase->purchase_price
            ];
        }

        return response()->json([
            'units' => $product->units,
            'stock' => $product->current_stock,
            'default_unit' => $defaultUnitName,
            'last_purchase' => $lastPurchaseInfo
        ]);
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

        $products = Product::where('name', 'like', "%{$query}%")
            ->whereHas('suppliers', function ($q) use ($supplierId) {
                $q->where('supplier_id', $supplierId);
            })
            ->orWhere(function ($q) use ($query, $supplierId) {
                $q->where('name', 'like', "%{$query}%")
                    ->whereDoesntHave('suppliers');
            })
            ->with(['suppliers' => function ($q) use ($supplierId) {
                $q->where('supplier_id', $supplierId);
            }, 'units'])
            ->limit(10)
            ->get();

        // Add stock information to each product
        $products->each(function ($product) {
            $product->stock = $product->current_stock;

            // Get the supplier-specific price if available
            $supplierInfo = $product->suppliers->first();
            if ($supplierInfo && isset($supplierInfo->pivot->purchase_price)) {
                $product->purchase_price = $supplierInfo->pivot->purchase_price;
            }
        });

        return response()->json($products);
    }
}
