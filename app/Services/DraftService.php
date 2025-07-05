<?php

namespace App\Services;

use App\Models\Sale;
use App\Models\Product;
use App\Models\ProductUnit;
use App\Models\Customer;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class DraftSaleService
{
    /**
     * Auto-save draft sale
     */
    public function autoSaveDraft(array $data): array
    {
        try {
            DB::beginTransaction();

            // Validate minimum requirements
            $this->validateAutoSaveData($data);

            // Calculate totals
            $totals = $this->calculateTotalsFromItems($data['items'], $data['discount'] ?? 0);

            if (!empty($data['draft_id'])) {
                $sale = $this->updateExistingDraft($data['draft_id'], $data, $totals);
            } else {
                $sale = $this->createNewDraft($data, $totals);
            }

            // Update sale details
            $this->updateDraftSaleDetails($sale, $data['items']);

            DB::commit();

            // Clear relevant caches
            Cache::forget('draft_sales_page_1');

            return [
                'success' => true,
                'message' => 'Draft berhasil disimpan otomatis',
                'draft_id' => $sale->id,
                'saved_at' => now()->format('H:i:s'),
                'expires_at' => $sale->created_at->addDays(30)->format('Y-m-d H:i:s')
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Auto-save draft error: ' . $e->getMessage(), [
                'data' => $data,
                'user_id' => auth()->id()
            ]);

            return [
                'success' => false,
                'message' => 'Gagal menyimpan draft: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Complete a draft sale
     */
    public function completeDraft(Sale $draft, array $data): array
    {
        try {
            if (!$draft->isDraft()) {
                throw new \Exception('Sale ini bukan draft');
            }

            if ($draft->isExpired()) {
                throw new \Exception('Draft sudah expired');
            }

            DB::beginTransaction();

            // Validate stock availability
            $this->validateStockAvailability($data);

            // Calculate totals and payment
            $totals = $this->calculateTotals($data);
            $paymentData = $this->calculatePaymentData($data, $totals);

            // Handle customer
            $customerId = $this->handleCustomer($data);

            // Update the draft to completed
            $this->updateDraftToCompleted($draft, $data, $customerId, $totals, $paymentData);

            // Update sale details and stock
            $this->updateSaleDetailsAndStock($draft, $data, true);

            DB::commit();

            // Clear caches
            $this->clearRelevantCaches($draft, $paymentData);

            return [
                'success' => true,
                'message' => 'Draft berhasil diselesaikan',
                'sale_id' => $draft->id
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Complete draft error: ' . $e->getMessage(), [
                'draft_id' => $draft->id,
                'data' => $data,
                'user_id' => auth()->id()
            ]);

            return [
                'success' => false,
                'message' => 'Gagal menyelesaikan draft: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Clean expired drafts
     */
    public function cleanExpiredDrafts(int $days = 30): array
    {
        try {
            $expiredDrafts = Sale::expiredDrafts($days)->get();

            if ($expiredDrafts->isEmpty()) {
                return [
                    'success' => true,
                    'message' => 'Tidak ada draft yang expired',
                    'deleted_count' => 0
                ];
            }

            $deletedCount = 0;

            foreach ($expiredDrafts as $draft) {
                try {
                    $draft->saleDetails()->delete();
                    $draft->forceDelete();
                    $deletedCount++;
                } catch (\Exception $e) {
                    Log::error("Failed to delete expired draft {$draft->id}: " . $e->getMessage());
                }
            }

            // Clear caches
            Cache::flush(); // More aggressive cache clearing for cleanup

            Log::info("Expired drafts cleanup completed", [
                'deleted_count' => $deletedCount,
                'total_found' => $expiredDrafts->count(),
                'days' => $days
            ]);

            return [
                'success' => true,
                'message' => "Berhasil menghapus {$deletedCount} draft yang expired",
                'deleted_count' => $deletedCount
            ];
        } catch (\Exception $e) {
            Log::error('Clean expired drafts error: ' . $e->getMessage());

            return [
                'success' => false,
                'message' => 'Gagal membersihkan draft expired: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Get draft statistics
     */
    public function getDraftStatistics(): array
    {
        $totalDrafts = Sale::drafts()->count();
        $expiringSoon = Sale::expiringSoon(3)->count();
        $expired = Sale::expiredDrafts()->count();
        $withStockIssues = 0;

        // Count drafts with stock issues
        Sale::drafts()->with('saleDetails.product')->chunk(50, function ($drafts) use (&$withStockIssues) {
            foreach ($drafts as $draft) {
                if ($draft->hasStockIssues()) {
                    $withStockIssues++;
                }
            }
        });

        return [
            'total_drafts' => $totalDrafts,
            'expiring_soon' => $expiringSoon,
            'expired' => $expired,
            'with_stock_issues' => $withStockIssues,
            'healthy_drafts' => $totalDrafts - $expiringSoon - $expired - $withStockIssues
        ];
    }

    /**
     * Validate data for auto-save
     */
    private function validateAutoSaveData(array $data): void
    {
        if (empty($data['invoice_number'])) {
            throw new \Exception('Invoice number is required');
        }

        if (empty($data['items']) || !is_array($data['items'])) {
            throw new \Exception('Items are required');
        }

        foreach ($data['items'] as $index => $item) {
            if (empty($item['product_id'])) {
                throw new \Exception("Product ID is required for item {$index}");
            }

            if (empty($item['unit_id'])) {
                throw new \Exception("Unit ID is required for item {$index}");
            }

            if (!isset($item['quantity']) || $item['quantity'] <= 0) {
                throw new \Exception("Valid quantity is required for item {$index}");
            }

            if (!isset($item['selling_price']) || $item['selling_price'] < 0) {
                throw new \Exception("Valid selling price is required for item {$index}");
            }
        }
    }

    /**
     * Calculate totals from items array
     */
    private function calculateTotalsFromItems(array $items, float $discount = 0): array
    {
        $totalAmount = 0;

        foreach ($items as $item) {
            $quantity = floatval($item['quantity'] ?? 0);
            $price = floatval($item['selling_price'] ?? 0);
            $totalAmount += $quantity * $price;
        }

        $finalTotal = max(0, $totalAmount - $discount);

        return [
            'total_amount' => $totalAmount,
            'discount' => $discount,
            'final_total' => $finalTotal
        ];
    }

    /**
     * Calculate totals from request data
     */
    private function calculateTotals(array $data): array
    {
        $totalAmount = 0;

        if (isset($data['product_id']) && is_array($data['product_id'])) {
            foreach ($data['product_id'] as $index => $productId) {
                $quantity = floatval($data['quantity'][$index] ?? 0);
                $price = floatval($data['selling_price'][$index] ?? 0);
                $totalAmount += $quantity * $price;
            }
        }

        $discount = floatval($data['discount'] ?? 0);
        $finalTotal = max(0, $totalAmount - $discount);

        return [
            'total_amount' => $totalAmount,
            'discount' => $discount,
            'final_total' => $finalTotal
        ];
    }

    /**
     * Calculate payment data
     */
    private function calculatePaymentData(array $data, array $totals): array
    {
        $paymentMethod = $data['payment_method'] ?? 'cash';
        $finalTotal = $totals['final_total'];

        $paymentData = [
            'payment_method' => $paymentMethod,
            'payment_status' => 'pending',
            'paid_amount' => 0,
            'down_payment' => 0,
            'remaining_amount' => $finalTotal,
            'change_amount' => 0,
            'due_date' => null
        ];

        if ($paymentMethod === 'credit') {
            $downPayment = floatval($data['down_payment'] ?? 0);

            $paymentData['down_payment'] = $downPayment;
            $paymentData['paid_amount'] = $downPayment;
            $paymentData['remaining_amount'] = $finalTotal - $downPayment;
            $paymentData['due_date'] = now()->addDays(30);

            if ($downPayment >= $finalTotal) {
                $paymentData['payment_status'] = 'paid';
                $paymentData['remaining_amount'] = 0;
                $paymentData['change_amount'] = $downPayment - $finalTotal;
            } elseif ($downPayment > 0) {
                $paymentData['payment_status'] = 'partial';
            }
        } else {
            $paidAmount = floatval($data['paid_amount'] ?? $finalTotal);

            if ($paidAmount < $finalTotal) {
                throw new \Exception('Jumlah pembayaran kurang dari total belanja');
            }

            $paymentData['paid_amount'] = $paidAmount;
            $paymentData['remaining_amount'] = 0;
            $paymentData['change_amount'] = $paidAmount - $finalTotal;
            $paymentData['payment_status'] = 'paid';
        }

        return $paymentData;
    }

    /**
     * Handle customer creation/selection
     */
    private function handleCustomer(array $data): ?int
    {
        $customerId = $data['customer_id'] ?? null;

        if (!empty($data['new_customer_name']) || !empty($data['customer_name'])) {
            $customerName = $data['new_customer_name'] ?? $data['customer_name'];

            $customer = Customer::create([
                'nama' => $customerName,
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

        return $customerId;
    }

    /**
     * Validate stock availability
     */
    private function validateStockAvailability(array $data): void
    {
        if (!isset($data['product_id']) || !is_array($data['product_id'])) {
            return;
        }

        foreach ($data['product_id'] as $index => $productId) {
            $product = Product::find($productId);
            $productUnit = ProductUnit::find($data['unit_id'][$index] ?? null);

            if (!$product || !$productUnit) {
                continue;
            }

            $quantity = floatval($data['quantity'][$index] ?? 0);
            $baseQuantity = $quantity * $productUnit->conversion_factor;

            if ($baseQuantity > $product->stock) {
                throw new \Exception("Stok tidak cukup untuk {$product->name}. Tersedia: {$product->stock}, Diminta: {$baseQuantity}");
            }
        }
    }

    /**
     * Update existing draft
     */
    private function updateExistingDraft(int $draftId, array $data, array $totals): Sale
    {
        $sale = Sale::where('id', $draftId)
            ->where('status', 'draft')
            ->where('user_id', auth()->id())
            ->lockForUpdate()
            ->first();

        if (!$sale) {
            throw new \Exception('Draft tidak ditemukan atau tidak dapat diakses');
        }

        if ($sale->isExpired()) {
            throw new \Exception('Draft sudah expired');
        }

        $sale->update([
            'customer_id' => $data['customer_id'] ?? null,
            'payment_method' => $data['payment_method'] ?? 'cash',
            'vehicle_type' => $data['vehicle_type'] ?? null,
            'vehicle_number' => $data['vehicle_number'] ?? null,
            'discount' => $totals['discount'],
            'notes' => $data['notes'] ?? null,
            'total_amount' => $totals['total_amount'],
            'remaining_amount' => $totals['final_total'],
        ]);

        return $sale;
    }

    /**
     * Create new draft
     */
    private function createNewDraft(array $data, array $totals): Sale
    {
        return Sale::create([
            'invoice_number' => $data['invoice_number'],
            'date' => now(),
            'customer_id' => $data['customer_id'] ?? null,
            'user_id' => auth()->id(),
            'payment_method' => $data['payment_method'] ?? 'cash',
            'vehicle_type' => $data['vehicle_type'] ?? null,
            'vehicle_number' => $data['vehicle_number'] ?? null,
            'status' => 'draft',
            'payment_status' => 'pending',
            'total_amount' => $totals['total_amount'],
            'discount' => $totals['discount'],
            'paid_amount' => 0,
            'remaining_amount' => $totals['final_total'],
            'notes' => $data['notes'] ?? null,
        ]);
    }

    /**
     * Update draft sale details
     */
    private function updateDraftSaleDetails(Sale $sale, array $items): void
    {
        // Delete existing details
        $sale->saleDetails()->delete();

        // Add new details
        foreach ($items as $item) {
            if (empty($item['product_id']) || empty($item['unit_id'])) {
                continue;
            }

            $productUnit = ProductUnit::find($item['unit_id']);
            if (!$productUnit) {
                continue;
            }

            $quantity = floatval($item['quantity'] ?? 1);
            $price = floatval($item['selling_price'] ?? 0);
            $baseQuantity = $quantity * $productUnit->conversion_factor;

            $sale->saleDetails()->create([
                'product_id' => $item['product_id'],
                'product_unit_id' => $item['unit_id'],
                'unit_id' => $productUnit->unit_id,
                'quantity' => $quantity,
                'base_quantity' => $baseQuantity,
                'price' => $price,
                'subtotal' => $quantity * $price,
            ]);
        }
    }

    /**
     * Update draft to completed status
     */
    private function updateDraftToCompleted(Sale $draft, array $data, ?int $customerId, array $totals, array $paymentData): void
    {
        $draft->update([
            'customer_id' => $customerId,
            'date' => now(),
            'status' => 'completed',
            'total_amount' => $totals['total_amount'],
            'discount' => $totals['discount'],
            'paid_amount' => $paymentData['paid_amount'],
            'down_payment' => $paymentData['down_payment'],
            'change_amount' => $paymentData['change_amount'],
            'payment_method' => $paymentData['payment_method'],
            'payment_status' => $paymentData['payment_status'],
            'remaining_amount' => $paymentData['remaining_amount'],
            'due_date' => $paymentData['due_date'],
            'vehicle_type' => $data['vehicle_type'] ?? null,
            'vehicle_number' => $data['vehicle_number'] ?? null,
            'notes' => $data['notes'] ?? null,
        ]);
    }

    /**
     * Update sale details and stock
     */
    private function updateSaleDetailsAndStock(Sale $sale, array $data, bool $updateStock = false): void
    {
        // Remove old details
        $sale->saleDetails()->delete();

        // Add new details
        foreach ($data['product_id'] as $index => $productId) {
            $quantity = floatval($data['quantity'][$index]);
            $price = floatval($data['selling_price'][$index]);
            $product = Product::findOrFail($productId);
            $productUnit = ProductUnit::findOrFail($data['unit_id'][$index]);

            $baseQuantity = $quantity * $productUnit->conversion_factor;

            $sale->saleDetails()->create([
                'product_id' => $productId,
                'product_unit_id' => $productUnit->id,
                'unit_id' => $productUnit->unit_id,
                'quantity' => $quantity,
                'base_quantity' => $baseQuantity,
                'price' => $price,
                'subtotal' => $quantity * $price
            ]);

            // Update stock if required
            if ($updateStock) {
                $this->updateProductStock($product, $baseQuantity, $sale, 'out');
            }
        }
    }

    /**
     * Update product stock
     */
    private function updateProductStock(Product $product, float $baseQuantity, Sale $sale, string $type): void
    {
        $beforeStock = $product->stock;

        if ($type === 'out') {
            $product->decrement('stock', $baseQuantity);
            $notes = 'Penjualan produk';
        } else {
            $product->increment('stock', $baseQuantity);
            $notes = 'Pembatalan penjualan';
        }

        $product->stockMovements()->create([
            'type' => $type,
            'quantity' => $baseQuantity,
            'before_stock' => $beforeStock,
            'after_stock' => $product->stock,
            'reference_type' => 'sale',
            'reference_id' => $sale->id,
            'notes' => $notes
        ]);

        // Clear product cache
        Cache::forget('available_products');
        Cache::forget('product_details_' . $product->id);
    }

    /**
     * Clear relevant caches
     */
    private function clearRelevantCaches(Sale $sale, array $paymentData): void
    {
        // Clear sales caches
        for ($i = 1; $i <= 5; $i++) {
            Cache::forget('completed_sales_page_' . $i);
            Cache::forget('draft_sales_page_' . $i);
        }

        // Clear credit sales cache if relevant
        if ($paymentData['payment_method'] === 'credit' && $paymentData['payment_status'] !== 'paid') {
            for ($i = 1; $i <= 5; $i++) {
                Cache::forget('credit_sales_list_page_' . $i);
            }
        }

        Cache::forget('sale_' . $sale->id);
    }
}
