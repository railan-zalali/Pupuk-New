<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\PurchaseReceipt;
use App\Models\PurchaseDetail;
use App\Models\PurchaseReceiptDetail;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchaseReceiptController extends Controller
{
    public function store(Request $request, Purchase $purchase)
    {
        try {
            DB::beginTransaction();

            $validated = $request->validate([
                'receipt_date' => 'required|date',
                'details' => 'required|array',
                'details.*.purchase_detail_id' => 'required|exists:purchase_details,id',
                'details.*.received_quantity' => 'required|integer|min:1',
                'details.*.expire_date' => 'nullable|date',
            ]);

            // Create receipt
            $receipt = PurchaseReceipt::create([
                'purchase_id' => $purchase->id,
                'receipt_date' => $validated['receipt_date'],
            ]);

            // Create receipt details and update stock
            foreach ($validated['details'] as $detail) {
                $purchaseDetail = PurchaseDetail::find($detail['purchase_detail_id']);

                // Create receipt detail
                $receiptDetail = PurchaseReceiptDetail::create([
                    'purchase_receipt_id' => $receipt->id,
                    'purchase_detail_id' => $detail['purchase_detail_id'],
                    'received_quantity' => $detail['received_quantity'],
                    'expire_date' => $detail['expire_date'] ?? null,
                ]);

                // Update product stock
                $product = $purchaseDetail->product;
                $oldStock = $product->stock;
                $newStock = $oldStock + $detail['received_quantity'];

                $product->update([
                    'stock' => $newStock,
                    'expire_date' => $detail['expire_date'] ?? $product->expire_date,
                ]);

                // Create stock movement
                StockMovement::create([
                    'product_id' => $product->id,
                    'type' => 'in',
                    'quantity' => $detail['received_quantity'],
                    'before_stock' => $oldStock,
                    'after_stock' => $newStock,
                    'reference_type' => 'purchase_receipt',
                    'reference_id' => $receipt->id,
                    'notes' => 'Purchase receipt #' . $receipt->id,
                ]);
            }

            DB::commit();

            return redirect()->route('purchases.show', $purchase)
                ->with('success', 'Penerimaan barang berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }
}
