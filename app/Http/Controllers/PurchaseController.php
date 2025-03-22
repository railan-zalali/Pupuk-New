<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Http\Requests\StorePurchaseRequest;
use App\Http\Requests\UpdatePurchaseRequest;
use App\Models\Product;
use App\Models\Supplier;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    public function index()
    {
        $purchases = Purchase::with(['supplier', 'user'])
            ->latest()
            ->paginate(10);

        return view('purchases.index', compact('purchases'));
    }

    public function create()
    {
        $suppliers = Supplier::orderBy('name')->get();
        $products = Product::orderBy('name')->get();

        // Generate nomor invoice
        $lastPurchase = Purchase::whereDate('created_at', Carbon::today())->latest()->first();
        $lastNumber = $lastPurchase ? intval(substr($lastPurchase->invoice_number, -4)) : 0;
        $invoiceNumber = 'PO-' . date('Ymd') . '-' . str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);

        return view('purchases.create', compact('suppliers', 'products', 'invoiceNumber'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'date' => 'required|date',
            'invoice_number' => 'required|unique:purchases,invoice_number',
            'product_id' => 'required|array',
            'product_id.*' => 'exists:products,id',
            'quantity' => 'required|array',
            'quantity.*' => 'integer|min:1',
            'purchase_price' => 'required|array',
            'purchase_price.*' => 'numeric|min:0',
            'notes' => 'nullable|string'
        ]);

        // Hitung total amount menggunakan array_sum dan array_map
        $totalAmount = array_sum(array_map(function ($index) use ($request) {
            return $request->quantity[$index] * $request->purchase_price[$index];
        }, array_keys($request->product_id)));

        // Buat purchase
        $purchase = new Purchase();
        $purchase->invoice_number = $request->invoice_number;
        $purchase->supplier_id = $request->supplier_id;
        $purchase->user_id = auth()->id();
        $purchase->date = $request->date;
        $purchase->total_amount = $totalAmount;
        $purchase->notes = $request->notes;
        $purchase->save();

        $products = Product::findMany($request->product_id);

        foreach ($request->product_id as $key => $productId) {
            $product = $products->firstWhere('id', $productId);
            $quantity = $request->quantity[$key];
            $price = $request->purchase_price[$key];

            // Simpan detail pembelian
            $purchase->purchaseDetails()->create([
                'product_id' => $productId,
                'quantity' => $quantity,
                'purchase_price' => $price,
                'subtotal' => $quantity * $price
            ]);

            // Update stok produk
            $beforeStock = $product->stock;
            $product->stock += $quantity;
            $product->save();

            // Catat pergerakan stok
            $product->stockMovements()->create([
                'type' => 'in',
                'quantity' => $quantity,
                'before_stock' => $beforeStock,
                'after_stock' => $product->stock,
                'reference_type' => 'purchase',
                'reference_id' => $purchase->id,
                'notes' => 'Pembelian dari supplier'
            ]);
        }


        return redirect()
            ->route('purchases.index')
            ->with('success', 'Pembelian berhasil ditambahkan');
    }

    public function show(Purchase $purchase)
    {
        $purchase->load(['supplier', 'purchaseDetails.product']);
        return view('purchases.show', compact('purchase'));
    }

    public function edit(Purchase $purchase)
    {
        // Pembelian tidak bisa diedit
        return redirect()->route('purchases.show', $purchase);
    }

    public function update(Request $request, Purchase $purchase)
    {
        // Pembelian tidak bisa diupdate
        return redirect()->route('purchases.show', $purchase);
    }

    public function destroy(Purchase $purchase)
    {
        // Load purchase details sebelum pembatalan
        $purchase->load('purchaseDetails.product');

        // Batalkan setiap detail pembelian
        $purchase = Purchase::with('purchaseDetails.product')->findOrFail($purchase->id);

        foreach ($purchase->purchaseDetails as $detail) {
            $product = $detail->product;
            $beforeStock = $product->stock;

            // Kurangi stok
            $product->stock -= $detail->quantity;
            $product->save();

            // Catat pergerakan stok
            $product->stockMovements()->create([
                'type' => 'out',
                'quantity' => $detail->quantity,
                'before_stock' => $beforeStock,
                'after_stock' => $product->stock,
                'reference_type' => 'purchase_void',
                'reference_id' => $purchase->id,
                'notes' => 'Pembatalan pembelian'
            ]);
        }


        // Soft delete pembelian
        $purchase->delete();

        return redirect()
            ->route('purchases.index')
            ->with('success', 'Pembelian berhasil dibatalkan');
    }
}
