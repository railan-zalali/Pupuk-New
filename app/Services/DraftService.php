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

class DraftService
{
    /**
     * Auto-save a draft sale
     */
    public function autoSave(array $data): array
    {
        try {
            DB::beginTransaction();

            $saleId = $data['sale_id'] ?? null;
            $totalAmount = collect($data['items'])->sum(function ($item) {
                return $item['quantity'] * $item['selling_price'];
            });

            if ($saleId) {
                // Update existing draft
                $sale = Sale::find($saleId);
                if (!$sale || !$sale->isDraft()) {
                    throw new \Exception('Draft tidak ditemukan atau sudah diselesaikan');
                }

                $sale->update([
                    'total_amount' => $totalAmount,
                    'discount' => $data['discount'] ?? 0,
                    'customer_id' => $data['customer_id'] ?? null,
                    'notes' => $data['notes'] ?? null,
                    'vehicle_type' => $data['vehicle_type'] ?? null,
                    'vehicle_number' => $data['vehicle_number'] ?? null,
                    'payment_method' => $data['payment_method'] ?? 'cash',
                    'updated_at' => now(),
                ]);

                // Update items
                $sale->saleDetails()->delete();
            } else {
                // Create new draft
                $sale = Sale::create([
                    'invoice_number' => $data['invoice_number'],
                    'date' => now(),
                    'customer_id' => $data['customer_id'] ?? null,
                    'user_id' => auth()->id(),
                    'payment_method' => $data['payment_method'] ?? 'cash',
                    'payment_status' => 'pending',
                    'status' => 'draft',
                    'total_amount' => $totalAmount,
                    'discount' => $data['discount'] ?? 0,
                    'paid_amount' => 0,
                    'down_payment' => 0,
                    'remaining_amount' => $totalAmount - ($data['discount'] ?? 0),
                    'change_amount' => 0,
                    'notes' => $data['notes'] ?? null,
                    'vehicle_type' => $data['vehicle_type'] ?? null,
                    'vehicle_number' => $data['vehicle_number'] ?? null,
                ]);
            }

            // Save items
            foreach ($data['items'] as $item) {
                $productUnit = ProductUnit::findOrFail($item['unit_id']);
                $baseQuantity = $item['quantity'] * $productUnit->conversion_factor;

                $sale->saleDetails()->create([
                    'product_id' => $item['product_id'],
                    'product_unit_id' => $productUnit->id,
                    'unit_id' => $productUnit->unit_id,
                    'quantity' => $item['quantity'],
                    'base_quantity' => $baseQuantity,
                    'price' => $item['selling_price'],
                    'subtotal' => $item['quantity'] * $item['selling_price'],
                ]);
            }

            DB::commit();

            // Clear draft caches
            $this->clearDraftCaches();

            return [
                'success' => true,
                'sale_id' => $sale->id,
                'message' => 'Draft berhasil disimpan otomatis',
                'saved_at' => now()->format('H:i:s')
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Auto-save Draft Error: ' . $e->getMessage());

            return [
                'success' => false,
                'message' => 'Gagal menyimpan draft: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Load a draft for editing
     */
    public function loadDraft(int $draftId): array
    {
        $sale = Sale::with(['saleDetails.product', 'saleDetails.productUnit.unit', 'customer'])
            ->where('id', $draftId)
            ->where('status', 'draft')
            ->first();

        if (!$sale) {
            throw new \Exception('Draft tidak ditemukan');
        }

        return [
            'sale' => $sale,
            'items' => $sale->saleDetails->map(function ($detail) {
                return [
                    'product_id' => $detail->product_id,
                    'product_name' => $detail->product->name,
                    'unit_id' => $detail->product_unit_id,
                    'unit_name' => $detail->productUnit->unit->name,
                    'quantity' => $detail->quantity,
                    'selling_price' => $detail->price,
                    'subtotal' => $detail->subtotal,
                ];
            })
        ];
    }

    /**
     * Duplicate a draft
     */
    public function duplicateDraft(int $draftId): array
    {
        $draft = Sale::with('saleDetails')->where('id', $draftId)->where('status', 'draft')->first();

        if (!$draft) {
            throw new \Exception('Draft tidak ditemukan');
        }

        try {
            DB::beginTransaction();

            // Generate new invoice number
            $newInvoiceNumber = $this->generateInvoiceNumber();

            // Create duplicate draft
            $newDraft = Sale::create([
                'invoice_number' => $newInvoiceNumber,
                'date' => now(),
                'customer_id' => $draft->customer_id,
                'user_id' => auth()->id(),
                'payment_method' => $draft->payment_method,
                'payment_status' => 'pending',
                'status' => 'draft',
                'total_amount' => $draft->total_amount,
                'discount' => $draft->discount,
                'paid_amount' => 0,
                'down_payment' => 0,
                'remaining_amount' => $draft->total_amount - $draft->discount,
                'change_amount' => 0,
                'notes' => $draft->notes ? 'Copy: ' . $draft->notes : 'Duplikasi dari ' . $draft->invoice_number,
                'vehicle_type' => $draft->vehicle_type,
                'vehicle_number' => $draft->vehicle_number,
            ]);

            // Duplicate sale details
            foreach ($draft->saleDetails as $detail) {
                $newDraft->saleDetails()->create([
                    'product_id' => $detail->product_id,
                    'product_unit_id' => $detail->product_unit_id,
                    'unit_id' => $detail->unit_id,
                    'quantity' => $detail->quantity,
                    'base_quantity' => $detail->base_quantity,
                    'price' => $detail->price,
                    'subtotal' => $detail->subtotal,
                ]);
            }

            DB::commit();

            // Clear draft caches
            $this->clearDraftCaches();

            return [
                'success' => true,
                'message' => 'Draft berhasil diduplikasi',
                'new_draft_id' => $newDraft->id,
                'new_invoice_number' => $newInvoiceNumber
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Duplicate draft error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Bulk complete drafts
     */
    public function bulkComplete(array $draftIds): array
    {
        $drafts = Sale::whereIn('id', $draftIds)
            ->where('status', 'draft')
            ->with(['saleDetails.product', 'saleDetails.productUnit'])
            ->get();

        if ($drafts->count() !== count($draftIds)) {
            throw new \Exception('Beberapa transaksi yang dipilih bukan draft atau tidak ditemukan');
        }

        $completedCount = 0;
        $failedDrafts = [];
        $errors = [];

        DB::beginTransaction();

        try {
            foreach ($drafts as $draft) {
                try {
                    // Validate stock for each draft
                    $stockValidation = $this->validateDraftStock($draft);
                    if (!$stockValidation['valid']) {
                        $failedDrafts[] = $draft->invoice_number;
                        $errors[] = $stockValidation['message'];
                        continue;
                    }

                    // Complete the draft
                    $this->completeDraft($draft);
                    $completedCount++;
                } catch (\Exception $e) {
                    $failedDrafts[] = $draft->invoice_number;
                    $errors[] = "Draft {$draft->invoice_number}: " . $e->getMessage();
                    Log::error('Error completing draft in bulk operation', [
                        'draft_id' => $draft->id,
                        'error' => $e->getMessage()
                    ]);
                }
            }

            if ($completedCount === 0) {
                throw new \Exception('Tidak ada draft yang berhasil diselesaikan. ' . implode('; ', $errors));
            }

            DB::commit();

            // Clear caches
            $this->clearDraftCaches();
            $this->clearSalesCaches();

            $message = "Berhasil menyelesaikan {$completedCount} draft";
            if (count($failedDrafts) > 0) {
                $message .= ". Gagal: " . implode(', ', $failedDrafts);
            }

            return [
                'success' => true,
                'message' => $message,
                'completed_count' => $completedCount,
                'failed_count' => count($failedDrafts),
                'failed_drafts' => $failedDrafts
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Bulk delete drafts
     */
    public function bulkDelete(array $draftIds): array
    {
        $drafts = Sale::whereIn('id', $draftIds)
            ->where('status', 'draft')
            ->get();

        if ($drafts->count() !== count($draftIds)) {
            throw new \Exception('Beberapa transaksi yang dipilih bukan draft atau tidak ditemukan');
        }

        DB::beginTransaction();

        try {
            foreach ($drafts as $draft) {
                // Delete sale details first
                $draft->saleDetails()->delete();

                // Delete the draft
                $draft->delete();
            }

            DB::commit();

            // Clear relevant caches
            $this->clearDraftCaches();

            return [
                'success' => true,
                'message' => "Berhasil menghapus {$drafts->count()} draft",
                'deleted_count' => $drafts->count()
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Clean up old drafts
     */
    public function cleanupOldDrafts(int $daysOld = 30): array
    {
        $cutoffDate = now()->subDays($daysOld);

        $oldDrafts = Sale::where('status', 'draft')
            ->where('created_at', '<', $cutoffDate)
            ->get();

        DB::beginTransaction();

        try {
            $deletedCount = 0;
            foreach ($oldDrafts as $draft) {
                $draft->saleDetails()->delete();
                $draft->delete();
                $deletedCount++;
            }

            DB::commit();

            // Clear caches
            $this->clearDraftCaches();

            Log::info('Old drafts cleanup completed', [
                'days_old' => $daysOld,
                'deleted_count' => $deletedCount
            ]);

            return [
                'success' => true,
                'deleted_count' => $deletedCount,
                'message' => "Berhasil membersihkan {$deletedCount} draft lama"
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Cleanup old drafts error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Get draft statistics
     */
    public function getStatistics(): array
    {
        $cacheKey = 'draft_statistics';

        return Cache::remember($cacheKey, 300, function () {
            $today = now()->startOfDay();
            $weekAgo = now()->subDays(7);

            $drafts = Sale::where('status', 'draft')->get();

            return [
                'total_drafts' => $drafts->count(),
                'total_value' => $drafts->sum('total_amount'),
                'today_drafts' => $drafts->where('created_at', '>=', $today)->count(),
                'old_drafts' => $drafts->where('created_at', '<', $weekAgo)->count(),
                'avg_draft_value' => $drafts->count() > 0 ? $drafts->avg('total_amount') : 0,
                'largest_draft' => $drafts->max('total_amount') ?: 0,
                'oldest_draft' => $drafts->min('created_at'),
                'newest_draft' => $drafts->max('created_at'),
            ];
        });
    }

    /**
     * Export drafts data
     */
    public function exportDrafts(string $format = 'array'): array
    {
        $drafts = Sale::where('status', 'draft')
            ->with(['customer', 'user', 'saleDetails.product'])
            ->latest('created_at')
            ->get();

        $exportData = $drafts->map(function ($draft) {
            return [
                'invoice_number' => $draft->invoice_number,
                'created_at' => $draft->created_at->format('d/m/Y H:i'),
                'customer_name' => $draft->customer ? $draft->customer->nama : 'Pelanggan Umum',
                'total_amount' => $draft->total_amount,
                'discount' => $draft->discount,
                'final_total' => $draft->total_amount - $draft->discount,
                'items_count' => $draft->saleDetails->count(),
                'payment_method' => ucfirst($draft->payment_method),
                'notes' => $draft->notes ?: '-',
                'created_by' => $draft->user->name,
                'vehicle_info' => $draft->vehicle_type && $draft->vehicle_number ?
                    $draft->vehicle_type . ' - ' . $draft->vehicle_number : '-',
                'age_in_days' => $draft->created_at->diffInDays(now()),
            ];
        });

        return $exportData->toArray();
    }

    /**
     * Validate stock for draft before completion
     */
    private function validateDraftStock(Sale $draft): array
    {
        foreach ($draft->saleDetails as $detail) {
            $product = $detail->product;
            $baseQuantity = $detail->base_quantity;

            if ($baseQuantity > $product->stock) {
                return [
                    'valid' => false,
                    'message' => "Stok tidak cukup untuk produk {$product->name} di draft {$draft->invoice_number}. Dibutuhkan: {$baseQuantity}, Tersedia: {$product->stock}"
                ];
            }
        }

        return ['valid' => true];
    }

    /**
     * Complete a single draft transaction
     */
    private function completeDraft(Sale $draft): void
    {
        // Set default payment values for completing draft
        $finalTotal = $draft->total_amount - $draft->discount;

        // Update draft to completed status with default cash payment
        $draft->update([
            'status' => 'completed',
            'payment_method' => $draft->payment_method ?: 'cash',
            'payment_status' => 'paid',
            'paid_amount' => $finalTotal,
            'remaining_amount' => 0,
            'date' => now(),
        ]);

        // Update stock for each item
        foreach ($draft->saleDetails as $detail) {
            $product = $detail->product;
            $baseQuantity = $detail->base_quantity;

            $beforeStock = $product->stock;
            $product->decrement('stock', $baseQuantity);

            // Record stock movement
            $product->stockMovements()->create([
                'type' => 'out',
                'quantity' => $baseQuantity,
                'before_stock' => $beforeStock,
                'after_stock' => $product->stock,
                'reference_type' => 'sale',
                'reference_id' => $draft->id,
                'notes' => 'Bulk completion of draft transaction'
            ]);

            // Clear product cache
            Cache::forget('product_details_' . $product->id);
        }

        Cache::forget('available_products');
    }

    /**
     * Generate a new invoice number
     */
    private function generateInvoiceNumber(): string
    {
        $lastSale = Sale::whereDate('created_at', Carbon::today())->latest()->first();
        $lastNumber = $lastSale ? intval(substr($lastSale->invoice_number, -4)) : 0;
        return 'INV-' . date('Ymd') . '-' . str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Clear draft-related caches
     */
    private function clearDraftCaches(): void
    {
        for ($i = 1; $i <= 10; $i++) {
            Cache::forget('draft_sales_page_' . $i);
        }
        Cache::forget('draft_statistics');
    }

    /**
     * Clear sales-related caches
     */
    private function clearSalesCaches(): void
    {
        for ($i = 1; $i <= 10; $i++) {
            Cache::forget('completed_sales_page_' . $i);
            Cache::forget('credit_sales_list_page_' . $i);
        }
    }
}
