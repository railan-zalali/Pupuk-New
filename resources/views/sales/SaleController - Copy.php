<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Product;
use App\Models\Sale;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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

    public function drafts()
    {
        $drafts = Sale::where('status', 'draft')
            ->with(['user', 'customer'])
            ->latest()
            ->paginate(10);
        return view('sales.drafts', compact('drafts'));
    }

    public function create()
    {
        $products = Product::where('stock', '>', 0)->orderBy('name')->get();
        $customers = Customer::orderBy('nama')->get();

        // Generate invoice number
        $lastSale = Sale::whereDate('created_at', Carbon::today())->latest()->first();
        $lastNumber = $lastSale ? intval(substr($lastSale->invoice_number, -4)) : 0;
        $invoiceNumber = 'INV-' . date('Ymd') . '-' . str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);

        return view('sales.create', compact('products', 'customers', 'invoiceNumber'));
    }

    public function store(Request $request)
    {
        try {
            // Validasi dasar - tetap sama
            $validated = $request->validate([
                'invoice_number' => 'required|unique:sales',
                'customer_id' => 'nullable|exists:customers,id',
                'customer_name' => 'nullable|string|max:255',
                'product_id' => 'required|array',
                'product_id.*' => 'exists:products,id',
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
                'save_as_draft' => 'nullable'
            ]);

            // Determine status (draft or completed)
            $status = $request->has('save_as_draft') ? 'draft' : 'completed';

            // Only validate stock if not saving as draft
            if ($status !== 'draft') {
                foreach ($request->product_id as $key => $productId) {
                    $product = Product::find($productId);
                    if ($product->stock < $request->quantity[$key]) {
                        return back()->with('error', "Stok tidak cukup untuk produk: {$product->name}");
                    }
                }
            }

            // Hitung total - tetap sama
            $totalAmount = collect($request->product_id)->map(function ($item, $key) use ($request) {
                return $request->quantity[$key] * $request->selling_price[$key];
            })->sum();

            $discount = $request->discount ?? 0;
            $finalTotal = max(0, $totalAmount - $discount);

            // Handle customer baru jika diisi - tetap sama
            $customerId = $request->customer_id;
            if (empty($customerId) && !empty($request->customer_name)) {
                // Buat customer baru
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
            }

            // Set default values for draft mode
            $downPayment = 0;
            $paidAmount = 0;
            $remainingAmount = $finalTotal;
            $changeAmount = 0;
            $paymentStatus = 'unpaid';
            $dueDate = null;

            // Only calculate payment details if not draft
            if ($status !== 'draft') {
                // Perbaikan untuk metode pembayaran credit
                if ($request->payment_method === 'credit') {
                    // Validasi pelanggan harus dipilih untuk transaksi kredit
                    if (empty($customerId)) {
                        return back()->with('error', 'Transaksi dengan metode Hutang harus memilih pelanggan');
                    }

                    // Akses down_payment 
                    if ($request->has('down_payment')) {
                        $downPayment = floatval($request->down_payment);
                    }

                    $paidAmount = $downPayment;
                    $remainingAmount = $finalTotal - $downPayment;
                    $changeAmount = 0;

                    // Tentukan payment status
                    if ($downPayment >= $finalTotal) {
                        $paymentStatus = 'paid';
                        $remainingAmount = 0;
                    } elseif ($downPayment > 0) {
                        $paymentStatus = 'partial';
                    } else {
                        $paymentStatus = 'unpaid';
                    }

                    // Set jatuh tempo (30 hari dari sekarang)
                    $dueDate = now()->addDays(30);
                } else {
                    // Untuk cash/transfer - tetap sama
                    $paidAmount = $request->paid_amount;
                    $remainingAmount = 0;
                    $changeAmount = $paidAmount - $finalTotal;
                    $paymentStatus = 'paid';

                    // Validasi pembayaran penuh untuk cash/transfer
                    if ($paidAmount < $finalTotal) {
                        return back()->with('error', 'Pembayaran kurang dari total belanja');
                    }
                }
            }

            // Data yang akan disimpan
            $saleData = [
                'invoice_number' => $request->invoice_number,
                'customer_id' => $customerId,
                'user_id' => auth()->id(),
                'date' => now(),
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
            ];

            // Buat transaksi penjualan
            $sale = Sale::create($saleData);

            // Simpan detail
            foreach ($request->product_id as $key => $productId) {
                $quantity = $request->quantity[$key];
                $price = $request->selling_price[$key];
                $product = Product::find($productId);

                // Simpan detail
                $sale->saleDetails()->create([
                    'product_id' => $productId,
                    'quantity' => $quantity,
                    'selling_price' => $price,
                    'subtotal' => $quantity * $price
                ]);

                // Update stok hanya jika bukan draft
                if ($status !== 'draft') {
                    $beforeStock = $product->stock;
                    $product->stock -= $quantity;
                    $product->save();

                    // Catat pergerakan stok
                    $product->stockMovements()->create([
                        'type' => 'out',
                        'quantity' => $quantity,
                        'before_stock' => $beforeStock,
                        'after_stock' => $product->stock,
                        'reference_type' => 'sale',
                        'reference_id' => $sale->id,
                        'notes' => 'Penjualan produk'
                    ]);
                }
            }

            if ($status === 'draft') {
                return redirect()
                    ->route('sales.drafts')
                    ->with('success', 'Draft transaksi berhasil disimpan');
            } else {
                return redirect()
                    ->route('sales.show', $sale)
                    ->with('success', 'Transaksi berhasil');
            }
        } catch (\Exception $e) {
            // Tampilkan pesan error lebih detail
            Log::error('Sale Error: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'payment_method' => $request->payment_method ?? 'unknown',
                'request_data' => $request->all()
            ]);

            return back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function show(Sale $sale)
    {
        $sale->load(['saleDetails.product', 'user', 'customer']);
        return view('sales.show', compact('sale'));
    }

    public function edit(Sale $sale)
    {
        if (!$sale->isDraft()) {
            return redirect()->route('sales.index')
                ->with('error', 'Hanya transaksi draft yang dapat diedit');
        }

        $products = Product::where('stock', '>', 0)->orderBy('name')->get();
        $customers = Customer::orderBy('nama')->get();
        $sale->load('saleDetails.product');

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
                    if ($product->stock < $request->quantity[$key]) {
                        return back()->with('error', "Stok tidak cukup untuk produk: {$product->name}");
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

                // Create detail
                $sale->saleDetails()->create([
                    'product_id' => $productId,
                    'quantity' => $quantity,
                    'selling_price' => $price,
                    'subtotal' => $quantity * $price
                ]);

                // Update stock only if completing the transaction
                if ($status === 'completed') {
                    $beforeStock = $product->stock;
                    $product->stock -= $quantity;
                    $product->save();

                    $product->stockMovements()->create([
                        'type' => 'out',
                        'quantity' => $quantity,
                        'before_stock' => $beforeStock,
                        'after_stock' => $product->stock,
                        'reference_type' => 'sale',
                        'reference_id' => $sale->id,
                        'notes' => 'Penjualan produk dari draft'
                    ]);
                }
            }

            if ($status === 'completed') {
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
        // Validasi input
        $request->validate([
            'customer_id' => 'nullable|exists:customers,id',
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
            'payment_method' => 'required|in:cash,credit',
            'down_payment' => 'nullable|numeric|min:0',
            'total' => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            // Update data penjualan
            $sale->customer_id = $request->customer_id;
            $sale->payment_method = $request->payment_method;
            $sale->down_payment = $request->payment_method === 'credit' ? ($request->down_payment ?? 0) : 0;
            $sale->total = $request->total;

            // Jika "Simpan sebagai Draft" dipilih
            if ($request->input('save_draft')) {
                // Hapus item lama
                $sale->items()->delete();

                // Tambahkan item baru
                foreach ($request->items as $item) {
                    $sale->items()->create([
                        'product_id' => $item['product_id'],
                        'quantity' => $item['quantity'],
                        'price' => $item['price'],
                        'subtotal' => $item['quantity'] * $item['price'],
                    ]);
                }

                $sale->status = 'draft'; // Pastikan status tetap draft
                $sale->save();

                DB::commit();
                return redirect()->route('sales.index')->with('success', 'Draft transaksi berhasil diperbarui.');
            }

            // Jika "Selesaikan Transaksi" dipilih
            if ($request->input('complete')) {
                // Validasi tambahan untuk kredit
                if ($request->payment_method === 'credit' && !$request->customer_id) {
                    throw new \Exception('Pelanggan wajib dipilih untuk metode pembayaran kredit.');
                }

                // Cek stok untuk semua item
                foreach ($request->items as $item) {
                    $product = Product::findOrFail($item['product_id']);
                    if ($product->stock < $item['quantity']) {
                        throw new \Exception("Stok produk {$product->name} tidak cukup. Tersedia: {$product->stock}");
                    }
                }

                // Hapus item lama
                $sale->items()->delete();

                // Tambahkan item baru dan kurangi stok
                foreach ($request->items as $item) {
                    $product = Product::findOrFail($item['product_id']);
                    $sale->items()->create([
                        'product_id' => $item['product_id'],
                        'quantity' => $item['quantity'],
                        'price' => $item['price'],
                        'subtotal' => $item['quantity'] * $item['price'],
                    ]);

                    // Kurangi stok
                    $product->decrement('stock', $item['quantity']);
                }

                $sale->status = 'completed'; // Ubah status menjadi selesai
                $sale->save();

                DB::commit();
                return redirect()->route('sales.index')->with('success', 'Transaksi berhasil diselesaikan.');
            }

            throw new \Exception('Aksi tidak valid.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    public function destroy(Sale $sale)
    {
        // Handle differently based on status
        if ($sale->isDraft()) {
            // Simply delete the draft
            $sale->saleDetails()->delete();
            $sale->delete();
            return redirect()
                ->route('sales.drafts')
                ->with('success', 'Draft transaksi berhasil dihapus');
        }

        // Existing code for completed sales
        $sale->load(['saleDetails.product']);

        foreach ($sale->saleDetails as $detail) {
            $product = $detail->product;
            $beforeStock = $product->stock;

            $product->increment('stock', $detail->quantity);

            $product->stockMovements()->create([
                'type' => 'in',
                'quantity' => $detail->quantity,
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

        return redirect()
            ->route('sales.index')
            ->with('success', 'Transaksi berhasil dibatalkan');
    }

    // Existing methods
    public function invoice(Sale $sale)
    {
        if ($sale->isDraft()) {
            return redirect()->route('sales.drafts')
                ->with('error', 'Tidak dapat mencetak invoice untuk transaksi draft');
        }

        $sale->load(['saleDetails.product', 'user']);
        return view('sales.invoice', compact('sale'));
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

        return redirect()->route('sales.credit')
            ->with('success', 'Pembayaran sebesar Rp ' . number_format($amount, 0, ',', '.') . ' berhasil dicatat');
    }

    // API untuk mendapatkan product details
    public function getProduct(Product $product)
    {
        return response()->json([
            'id' => $product->id,
            'name' => $product->name,
            'selling_price' => $product->selling_price,
            'stock' => $product->stock
        ]);
    }
}
