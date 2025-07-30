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

class DraftSaleController extends Controller
{
    /**
     * Display a listing of the draft sales.
     */
    public function index()
    {
        $drafts = Cache::remember('draft_sales', 300, function () {
            return Sale::drafts()
                ->with(['user', 'customer', 'saleDetails.product'])
                ->latest()
                ->get();
        });

        // Group drafts by customer
        $draftsByCustomer = $drafts->groupBy(function ($draft) {
            return $draft->customer ? $draft->customer->nama : 'Tanpa Pelanggan';
        });

        return view('sales.drafts.index', compact('drafts', 'draftsByCustomer'));
    }

    /**
     * Display the specified draft sale.
     */
    public function show(Sale $draft)
    {
        if ($draft->status !== 'draft') {
            return redirect()->route('sales.show', $draft)
                ->with('error', 'Transaksi ini bukan merupakan draft');
        }

        $cacheKey = 'draft_sale_' . $draft->id;

        $draft = Cache::remember($cacheKey, 600, function () use ($draft) {
            return $draft->load([
                'saleDetails.product',
                'saleDetails.productUnit.unit',
                'user',
                'customer'
            ]);
        });

        return view('sales.drafts.show', compact('draft'));
    }

    /**
     * Show the form for editing the specified draft sale.
     */
    public function edit(Sale $draft)
    {
        if ($draft->status !== 'draft') {
            return redirect()->route('sales.edit', $draft)
                ->with('error', 'Transaksi ini bukan merupakan draft');
        }

        // Cache products for 10 minutes
        $products = Cache::remember('available_products', 600, function () {
            return Product::orderBy('name')->get();
        });

        // Ambil data customer tanpa cache
        $customers = Customer::select('id', 'nama', 'kecamatan_nama', 'kabupaten_nama')
            ->orderBy('nama')
            ->get();

        $draft->load([
            'saleDetails.product',
            'saleDetails.productUnit.unit',
            'customer'
        ]);


        $sale = $draft; // Tambahkan baris ini
        return view('sales.edit', compact('sale', 'products', 'customers'));

        // Atau alternatif lain:
        // return view('sales.edit', compact('products', 'customers'))->with('sale', $draft);
    }

    /**
     * Update the specified draft sale in storage.
     */
    public function update(Request $request, Sale $draft)
    {
        if ($draft->status !== 'draft') {
            return redirect()->route('sales.index')
                ->with('error', 'Transaksi ini bukan merupakan draft');
        }

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

        $request->validate($validationRules);

        // Calculate totals
        $totalAmount = 0;
        foreach ($request->product_id as $key => $productId) {
            $totalAmount += $request->quantity[$key] * $request->selling_price[$key];
        }

        $discount = $request->discount ?? 0;
        $finalTotal = max(0, $totalAmount - $discount);

        try {
            DB::beginTransaction();

            // Update draft sale
            $draft->update([
                'date' => $request->date ?? now(),
                'customer_id' => $customerId,
                'payment_method' => $request->payment_method,
                'total_amount' => $totalAmount,
                'discount' => $discount,
                'notes' => $request->notes,
                'vehicle_type' => $request->vehicle_type ?? null,
                'vehicle_number' => $request->vehicle_number ?? null,
            ]);

            // Delete existing sale details
            $draft->saleDetails()->delete();

            // Create new sale details
            foreach ($request->product_id as $key => $productId) {
                $productUnit = ProductUnit::findOrFail($request->unit_id[$key]);
                $quantity = $request->quantity[$key];
                $price = $request->selling_price[$key];
                $product = Product::findOrFail($productId);
                $baseQuantity = $quantity * $productUnit->conversion_factor;

                // Reduce stock when saving as draft
                $beforeStock = $product->stock;

                // Check if stock is sufficient
                if ($baseQuantity > $product->stock) {
                    throw new \Exception("Stok tidak cukup untuk produk: {$product->name}");
                }

                // Decrement stock
                $product->decrement('stock', $baseQuantity);

                // Create stock movement record
                $product->stockMovements()->create([
                    'type' => 'out',
                    'quantity' => $baseQuantity,
                    'before_stock' => $beforeStock,
                    'after_stock' => $product->stock,
                    'reference_type' => 'draft_sale',
                    'reference_id' => $draft->id,
                    'notes' => 'Draft penjualan produk'
                ]);

                $draft->saleDetails()->create([
                    'product_id' => $productId,
                    'product_unit_id' => $productUnit->id,
                    'unit_id' => $productUnit->unit_id,
                    'quantity' => $quantity,
                    'base_quantity' => $baseQuantity,
                    'price' => $price,
                    'subtotal' => $quantity * $price,
                ]);

                Cache::forget('available_products');
                Cache::forget('product_details_' . $productId);
            }

            DB::commit();

            // Clear relevant caches
            Cache::forget('draft_sales');
            Cache::forget('draft_sale_' . $draft->id);

            return redirect()->route('drafts.index')
                ->with('success', 'Draft penjualan berhasil diperbarui');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Draft Sale Update Error: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);
            return back()->with('error', 'Gagal memperbarui draft: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Process the draft sale to a completed sale.
     */
    public function process(Request $request, Sale $draft)
    {
        if ($draft->status !== 'draft') {
            return redirect()->route('sales.index')
                ->with('error', 'Transaksi ini bukan merupakan draft');
        }

        // Validation rules for payment
        $validationRules = [
            'payment_method' => 'required|in:cash,transfer,credit',
        ];

        // For credit payment, validate down payment
        if ($request->payment_method === 'credit') {
            $validationRules['down_payment'] = 'required|numeric|min:0';
            // Customer required for credit
            if (empty($draft->customer_id)) {
                return back()->with('error', 'Transaksi dengan metode Kredit harus memilih pelanggan')->withInput();
            }
        }

        $request->validate($validationRules);

        // Payment handling
        $paymentStatus = 'pending';
        $paidAmount = 0;
        $remainingAmount = $draft->total_amount;
        $dueDate = null;
        $changeAmount = 0;
        $paymentMethod = $request->payment_method;

        if ($request->payment_method === 'credit') {
            $downPayment = $request->down_payment;
            $paidAmount = $downPayment;
            $remainingAmount = $draft->total_amount - $downPayment;
            $dueDate = now()->addDays(30);

            if ($downPayment >= $draft->total_amount) {
                $paymentStatus = 'paid';
                $remainingAmount = 0;
                $changeAmount = $downPayment - $draft->total_amount;
            } else if ($downPayment > 0) {
                $paymentStatus = 'partial';
            }
        } else {
            $paymentStatus = 'paid';
            $paidAmount = $request->paid_amount ?? $draft->total_amount;
            $remainingAmount = 0;
            if ($paidAmount > $draft->total_amount) {
                $changeAmount = $paidAmount - $draft->total_amount;
            }
        }

        try {
            DB::beginTransaction();

            // Update draft to completed sale
            $draft->update([
                'payment_method' => $paymentMethod,
                'payment_status' => $paymentStatus,
                'status' => 'completed',
                'paid_amount' => $paidAmount,
                'down_payment' => $request->down_payment ?? 0,
                'remaining_amount' => $remainingAmount,
                'change_amount' => $changeAmount,
                'due_date' => $dueDate,
            ]);

            // Stock is already reduced when saving as draft, no need to reduce again
            // Just update the stock movement reference type
            $stockMovements = DB::table('stock_movements')
                ->where('reference_type', 'draft_sale')
                ->where('reference_id', $draft->id)
                ->update(['reference_type' => 'sale']);

            DB::commit();

            // Clear relevant caches
            Cache::forget('draft_sales');
            Cache::forget('completed_sales');
            Cache::forget('draft_sale_' . $draft->id);

            return redirect()->route('sales.show', $draft)
                ->with('success', 'Draft berhasil diproses menjadi transaksi');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Draft Sale Process Error: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);
            return back()->with('error', 'Gagal memproses draft: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified draft sale from storage.
     */
    public function destroy(Sale $draft)
    {
        if ($draft->status !== 'draft') {
            return redirect()->route('sales.index')
                ->with('error', 'Transaksi ini bukan merupakan draft');
        }

        try {
            DB::beginTransaction();

            // Restore stock for each product in the draft
            $draft->load(['saleDetails.product']);
            $productIds = [];

            foreach ($draft->saleDetails as $detail) {
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
                    'reference_type' => 'draft_sale_void',
                    'reference_id' => $draft->id,
                    'notes' => 'Draft sale void'
                ]);
            }

            $draft->status = 'cancelled';
            $draft->save();
            $draft->delete(); // Soft delete

            DB::commit();

            // Invalidate affected caches
            Cache::forget('draft_sales');
            Cache::forget('draft_sale_' . $draft->id);
            Cache::forget('available_products');

            // Invalidate individual product caches
            foreach ($productIds as $productId) {
                Cache::forget('product_details_' . $productId);
            }

            return redirect()
                ->route('drafts.index')
                ->with('success', 'Draft transaksi berhasil dihapus');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Draft Sale Delete Error: ' . $e->getMessage());
            return back()->with('error', 'Gagal menghapus draft: ' . $e->getMessage());
        }
    }
}
