<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductBatch;
use App\Models\StockMovement;
use Illuminate\Support\Facades\DB;

class FifoService
{
    /**
     * Menambahkan batch baru saat pembelian produk
     *
     * @param int $productId ID produk
     * @param int $purchaseId ID pembelian
     * @param float $quantity Jumlah produk
     * @param float $purchasePrice Harga beli
     * @param string|null $batchNumber Nomor batch (opsional)
     * @param string|null $expiryDate Tanggal kedaluwarsa (opsional)
     * @return ProductBatch
     */
    public function addBatch($productId, $purchaseId, $quantity, $purchasePrice, $batchNumber = null, $expiryDate = null)
    {
        // Generate batch number jika tidak disediakan
        if (!$batchNumber) {
            $batchNumber = 'BATCH-' . date('Ymd') . '-' . $productId . '-' . uniqid();
        }
        
        // Validasi tanggal kedaluwarsa
        if ($expiryDate) {
            // Pastikan format tanggal valid
            try {
                $expiryDate = is_string($expiryDate) ? \Carbon\Carbon::parse($expiryDate) : $expiryDate;
            } catch (\Exception $e) {
                // Jika format tanggal tidak valid, set ke null
                $expiryDate = null;
            }
        }

        // Buat batch baru
        $batch = ProductBatch::create([
            'product_id' => $productId,
            'purchase_id' => $purchaseId,
            'batch_number' => $batchNumber,
            'quantity' => $quantity,
            'remaining_quantity' => $quantity,
            'purchase_price' => $purchasePrice,
            'production_date' => now(),
            'expiry_date' => $expiryDate,
        ]);

        return $batch;
    }

    /**
     * Mengurangi stok menggunakan metode FIFO atau FEFO dengan logika otomatis
     *
     * @param int $productId ID produk
     * @param float $quantity Jumlah yang akan dikurangi
     * @param string $referenceType Tipe referensi (sale, adjustment, dll)
     * @param int $referenceId ID referensi
     * @param string $notes Catatan
     * @param string|null $method Metode pengurangan stok ('fifo', 'fefo', atau 'auto')
     * @return array Array dari batch yang digunakan dan jumlahnya
     */
    public function reduceStock($productId, $quantity, $referenceType, $referenceId, $notes = '', $method = 'auto')
    {
        // Ambil produk
        $product = Product::findOrFail($productId);

        // Validasi stok cukup
        if ($product->stock < $quantity) {
            throw new \Exception("Stok tidak cukup untuk produk: {$product->name}");
        }

        // Tentukan metode otomatis jika diperlukan
        $selectedMethod = $this->determineOptimalMethod($productId, $method);

        // Ambil batch yang tersedia dengan urutan berdasarkan metode
        $query = ProductBatch::where('product_id', $productId)
            ->where('remaining_quantity', '>', 0);
            
        // Pilih metode pengurangan stok berdasarkan logika yang ditingkatkan
        if ($selectedMethod === 'fefo') {
            // First Expired, First Out - prioritaskan batch yang akan kedaluwarsa lebih dulu
            $batches = $query->whereNotNull('expiry_date')
                ->orderBy('expiry_date', 'asc')
                ->orderBy('created_at', 'asc') // Jika tanggal kedaluwarsa sama, gunakan FIFO
                ->get();
                
            // Jika tidak ada batch dengan expiry date, fallback ke FIFO
            if ($batches->isEmpty()) {
                $batches = $query->orderBy('created_at', 'asc')->get();
                $selectedMethod = 'fifo';
            }
        } else {
            // First In, First Out - default
            $batches = $query->orderBy('created_at', 'asc')->get();
        }

        $remainingQuantity = $quantity;
        $usedBatches = [];
        $beforeStock = $product->stock;

        // Mulai transaksi database
        DB::beginTransaction();

        try {
            foreach ($batches as $batch) {
                if ($remainingQuantity <= 0) {
                    break;
                }

                // Hitung berapa banyak yang diambil dari batch ini
                $quantityFromBatch = min($remainingQuantity, $batch->remaining_quantity);

                // Update batch
                $batch->remaining_quantity -= $quantityFromBatch;
                $batch->save();

                // Catat pergerakan stok
                $stockMovement = StockMovement::create([
                    'product_id' => $productId,
                    'batch_id' => $batch->id,
                    'type' => 'out',
                    'quantity' => $quantityFromBatch,
                    'before_stock' => $beforeStock,
                    'after_stock' => $beforeStock - $quantityFromBatch,
                    'reference_type' => $referenceType,
                    'reference_id' => $referenceId,
                    'notes' => $notes . ' (' . strtoupper($selectedMethod) . ')'
                ]);

                // Update untuk iterasi berikutnya
                $remainingQuantity -= $quantityFromBatch;
                $beforeStock -= $quantityFromBatch;

                // Tambahkan ke array batch yang digunakan
                $usedBatches[] = [
                    'batch' => $batch,
                    'quantity' => $quantityFromBatch
                ];
            }

            // Update stok produk
            $product->decrement('stock', $quantity);

            DB::commit();
            return $usedBatches;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Mendapatkan informasi batch untuk produk
     *
     * @param int $productId ID produk
     * @param string $method Metode pengurutan ('fifo' atau 'fefo')
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getBatchesForProduct($productId, $method = 'fifo')
    {
        $query = ProductBatch::where('product_id', $productId)
            ->where('remaining_quantity', '>', 0);
            
        if (strtolower($method) === 'fefo') {
            return $query->orderBy('expiry_date', 'asc')->get();
        } else {
            return $query->orderBy('created_at', 'asc')->get();
        }
    }
    
    /**
     * Menentukan metode optimal untuk pengurangan stok
     *
     * @param int $productId ID produk
     * @param string $requestedMethod Metode yang diminta ('fifo', 'fefo', 'auto')
     * @return string Metode yang akan digunakan ('fifo' atau 'fefo')
     */
    public function determineOptimalMethod($productId, $requestedMethod = 'auto')
    {
        // Jika metode spesifik diminta, gunakan itu
        if (in_array(strtolower($requestedMethod), ['fifo', 'fefo'])) {
            return strtolower($requestedMethod);
        }

        // Logika otomatis untuk menentukan metode terbaik
        $hasExpiryBatches = $this->hasExpiryBatches($productId);
        $hasNearExpiryBatches = $this->hasNearExpiryBatches($productId, 30); // 30 hari ke depan
        $hasCriticalExpiryBatches = $this->hasNearExpiryBatches($productId, 7); // 7 hari ke depan

        // Prioritas FEFO jika:
        // 1. Ada batch yang akan kedaluwarsa dalam 7 hari (kritis)
        // 2. Ada batch yang akan kedaluwarsa dalam 30 hari dan lebih dari 50% batch memiliki expiry date
        if ($hasCriticalExpiryBatches) {
            return 'fefo';
        }

        if ($hasNearExpiryBatches && $this->getExpiryBatchPercentage($productId) > 0.5) {
            return 'fefo';
        }

        // Default ke FIFO jika tidak ada kondisi khusus
        return 'fifo';
    }

    /**
     * Memeriksa apakah produk memiliki batch dengan tanggal kedaluwarsa
     *
     * @param int $productId ID produk
     * @return bool
     */
    public function hasExpiryBatches($productId)
    {
        return ProductBatch::where('product_id', $productId)
            ->where('remaining_quantity', '>', 0)
            ->whereNotNull('expiry_date')
            ->exists();
    }

    /**
     * Memeriksa apakah produk memiliki batch dengan tanggal kedaluwarsa (alias untuk backward compatibility)
     *
     * @param int $productId ID produk
     * @return bool
     */
    public function hasExpiredBatches($productId)
    {
        return $this->hasExpiryBatches($productId);
    }

    /**
     * Memeriksa apakah produk memiliki batch yang akan kedaluwarsa dalam waktu tertentu
     *
     * @param int $productId ID produk
     * @param int $days Jumlah hari ke depan
     * @return bool
     */
    public function hasNearExpiryBatches($productId, $days = 30)
    {
        $expiryDate = now()->addDays($days);
        
        return ProductBatch::where('product_id', $productId)
            ->where('remaining_quantity', '>', 0)
            ->whereNotNull('expiry_date')
            ->where('expiry_date', '<=', $expiryDate)
            ->where('expiry_date', '>=', now())
            ->exists();
    }

    /**
     * Mendapatkan persentase batch yang memiliki tanggal kedaluwarsa
     *
     * @param int $productId ID produk
     * @return float Persentase (0.0 - 1.0)
     */
    public function getExpiryBatchPercentage($productId)
    {
        $totalBatches = ProductBatch::where('product_id', $productId)
            ->where('remaining_quantity', '>', 0)
            ->count();

        if ($totalBatches === 0) {
            return 0.0;
        }

        $expiryBatches = ProductBatch::where('product_id', $productId)
            ->where('remaining_quantity', '>', 0)
            ->whereNotNull('expiry_date')
            ->count();

        return $expiryBatches / $totalBatches;
    }
    
    /**
     * Mendapatkan batch yang akan kedaluwarsa dalam waktu dekat
     *
     * @param int $days Jumlah hari ke depan
     * @param bool $includeProduct Apakah menyertakan informasi produk
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getExpiringBatches($days = 30, $includeProduct = false)
    {
        $expiryDate = now()->addDays($days);
        
        $query = ProductBatch::whereNotNull('expiry_date')
            ->where('remaining_quantity', '>', 0)
            ->where('expiry_date', '<=', $expiryDate)
            ->where('expiry_date', '>=', now())
            ->orderBy('expiry_date', 'asc');
            
        if ($includeProduct) {
            $query->with('product');
        }
        
        return $query->get();
    }

    /**
     * Mendapatkan batch yang sudah kedaluwarsa
     *
     * @param bool $includeProduct Apakah menyertakan informasi produk
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getExpiredBatches($includeProduct = false)
    {
        $query = ProductBatch::whereNotNull('expiry_date')
            ->where('remaining_quantity', '>', 0)
            ->where('expiry_date', '<', now())
            ->orderBy('expiry_date', 'asc');
            
        if ($includeProduct) {
            $query->with('product');
        }
        
        return $query->get();
    }

    /**
     * Mendapatkan statistik kedaluwarsa untuk dashboard
     *
     * @return array
     */
    public function getExpiryStatistics()
    {
        $now = now();
        
        return [
            'expired' => ProductBatch::whereNotNull('expiry_date')
                ->where('remaining_quantity', '>', 0)
                ->where('expiry_date', '<', $now)
                ->count(),
            'expiring_7_days' => ProductBatch::whereNotNull('expiry_date')
                ->where('remaining_quantity', '>', 0)
                ->where('expiry_date', '>=', $now)
                ->where('expiry_date', '<=', $now->copy()->addDays(7))
                ->count(),
            'expiring_30_days' => ProductBatch::whereNotNull('expiry_date')
                ->where('remaining_quantity', '>', 0)
                ->where('expiry_date', '>=', $now)
                ->where('expiry_date', '<=', $now->copy()->addDays(30))
                ->count(),
            'total_with_expiry' => ProductBatch::whereNotNull('expiry_date')
                ->where('remaining_quantity', '>', 0)
                ->count(),
        ];
    }

    /**
     * Mengembalikan stok saat pembatalan transaksi dengan metode FIFO
     *
     * @param int $productId ID produk
     * @param float $quantity Jumlah yang akan dikembalikan
     * @param string $referenceType Tipe referensi (sale_void, draft_void, dll)
     * @param int $referenceId ID referensi
     * @param string $notes Catatan
     * @return array Array dari batch yang diperbarui
     */
    public function restoreStock($productId, $quantity, $referenceType, $referenceId, $notes = '')
    {
        // Ambil produk
        $product = Product::findOrFail($productId);
        $beforeStock = $product->stock;
        $updatedBatches = [];

        // Mulai transaksi database
        DB::beginTransaction();

        try {
            // Cari pergerakan stok keluar terkait dengan referensi asli
            // Misalnya, jika ini pembatalan penjualan, cari pergerakan stok 'out' dengan reference_type 'sale'
            $originalReferenceType = str_replace('_void', '', $referenceType);
            
            // Ambil pergerakan stok keluar yang terkait dengan transaksi asli
            $stockMovements = StockMovement::where('product_id', $productId)
                ->where('type', 'out')
                ->where('reference_type', $originalReferenceType)
                ->where('reference_id', $referenceId)
                ->orderBy('created_at', 'desc') // Terbaru dulu, karena kita akan mengembalikan stok dalam urutan terbalik dari FIFO
                ->get();

            // Jika tidak ada pergerakan stok yang ditemukan, kembalikan stok secara langsung
            if ($stockMovements->isEmpty()) {
                // Update stok produk
                $product->increment('stock', $quantity);
                
                // Catat pergerakan stok
                StockMovement::create([
                    'product_id' => $productId,
                    'type' => 'in',
                    'quantity' => $quantity,
                    'before_stock' => $beforeStock,
                    'after_stock' => $beforeStock + $quantity,
                    'reference_type' => $referenceType,
                    'reference_id' => $referenceId,
                    'notes' => $notes
                ]);
            } else {
                // Kembalikan stok ke batch yang sesuai berdasarkan pergerakan stok sebelumnya
                foreach ($stockMovements as $movement) {
                    // Jika ada batch_id, kembalikan stok ke batch tersebut
                    if ($movement->batch_id) {
                        $batch = ProductBatch::find($movement->batch_id);
                        if ($batch) {
                            // Kembalikan stok ke batch
                            $batch->remaining_quantity += $movement->quantity;
                            $batch->save();
                            
                            // Catat pergerakan stok
                            StockMovement::create([
                                'product_id' => $productId,
                                'batch_id' => $batch->id,
                                'type' => 'in',
                                'quantity' => $movement->quantity,
                                'before_stock' => $beforeStock,
                                'after_stock' => $beforeStock + $movement->quantity,
                                'reference_type' => $referenceType,
                                'reference_id' => $referenceId,
                                'notes' => $notes . ' (Batch: ' . $batch->batch_number . ')'
                            ]);
                            
                            $updatedBatches[] = [
                                'batch' => $batch,
                                'quantity' => $movement->quantity
                            ];
                            
                            // Update untuk iterasi berikutnya
                            $beforeStock += $movement->quantity;
                        }
                    }
                }
                
                // Update stok produk
                $product->increment('stock', $quantity);
            }

            DB::commit();
            return $updatedBatches;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
