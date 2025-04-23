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
        $products = Product::with('category')->orderBy('name')->get();
        $invoiceNumber = $this->generateInvoiceNumber();

        return view('purchases.create', compact('suppliers', 'products', 'invoiceNumber'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'date' => 'required|date',
            'product_id' => 'required|array|min:1',
            'product_id.*' => 'required|exists:products,id',
            'quantity' => 'required|array|min:1',
            'quantity.*' => 'required|integer|min:1',
            'purchase_price' => 'required|array|min:1',
            'purchase_price.*' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        try {
            // Create the purchase
            $purchase = new Purchase();
            $purchase->invoice_number = $this->generateInvoiceNumber();
            $purchase->supplier_id = $request->supplier_id;
            $purchase->user_id = Auth::id();
            $purchase->date = $request->date;
            $purchase->status = 'pending';
            $purchase->notes = $request->notes;

            // Calculate total amount
            $totalAmount = 0;
            for ($i = 0; $i < count($request->product_id); $i++) {
                $totalAmount += $request->quantity[$i] * $request->purchase_price[$i];
            }
            $purchase->total_amount = $totalAmount;
            $purchase->save();

            // Create purchase details
            for ($i = 0; $i < count($request->product_id); $i++) {
                $detail = new PurchaseDetail();
                $detail->purchase_id = $purchase->id;
                $detail->product_id = $request->product_id[$i];
                $detail->quantity = $request->quantity[$i];
                $detail->received_quantity = 0;
                $detail->purchase_price = $request->purchase_price[$i];
                $detail->subtotal = $request->quantity[$i] * $request->purchase_price[$i];
                $detail->save();

                // Update product supplier price if needed
                $product = Product::find($request->product_id[$i]);
                $supplier = Supplier::find($request->supplier_id);

                // Check if relationship exists and update price
                if (!$supplier->products()->where('product_id', $product->id)->exists()) {
                    $supplier->products()->attach($product->id, [
                        'purchase_price' => $request->purchase_price[$i]
                    ]);
                } else {
                    // Update the pivot if price has changed
                    $supplier->products()->updateExistingPivot($product->id, [
                        'purchase_price' => $request->purchase_price[$i]
                    ]);
                }
            }

            return redirect()->route('purchases.show', $purchase)
                ->with('success', 'Pembelian berhasil dibuat.');
        } catch (\Exception $e) {
            Log::error('Purchase creation failed: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()])->withInput();
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

    return view('purchases.receipt', compact('purchase'));
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
                    'product_id' => $item['product_id'],
                    'reference_id' => $receipt->id,
                    'reference_type' => 'purchase_receipt',
                    'quantity' => $receivedQty,
                    'movement_type' => 'in',
                    'type' => 'purchase',
                    'before_stock' => $beforeStock,
                    'after_stock' => $product->stock,
                    'notes' => 'Penerimaan pembelian #' . $purchase->invoice_number
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
        Log::error('Receipt creation failed: ' . $e->getMessage());
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
    public function getProductsBySupplier(Supplier $supplier)
    {
        try {
            // Get all products with their supplier relationship
            $products = Product::with(['suppliers' => function($query) use ($supplier) {
                $query->where('suppliers.id', $supplier->id);
            }])->get();

            // Make sure the response is properly formatted
            return response()->json($products, 200, [
                'Content-Type' => 'application/json'
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching products by supplier: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
