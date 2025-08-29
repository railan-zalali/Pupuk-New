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
     * Mengurangi stok menggunakan metode FIFO atau FEFO
     *
     * @param int $productId ID produk
     * @param float $quantity Jumlah yang akan dikurangi
     * @param string $referenceType Tipe referensi (sale, adjustment, dll)
     * @param int $referenceId ID referensi
     * @param string $notes Catatan
     * @param string $method Metode pengurangan stok ('fifo' atau 'fefo')
     * @return array Array dari batch yang digunakan dan jumlahnya
     */
    public function reduceStock($productId, $quantity, $referenceType, $referenceId, $notes = '', $method = 'fifo')
    {
        // Ambil produk
        $product = Product::findOrFail($productId);

        // Validasi stok cukup
        if ($product->stock < $quantity) {
            throw new \Exception("Stok tidak cukup untuk produk: {$product->name}");
        }

        // Ambil batch yang tersedia dengan urutan berdasarkan metode
        $query = ProductBatch::where('product_id', $productId)
            ->where('remaining_quantity', '>', 0);
            
        // Pilih metode pengurangan stok (FIFO atau FEFO)
        if (strtolower($method) === 'fefo' && $this->hasExpiredBatches($productId)) {
            // First Expired, First Out - prioritaskan batch yang akan kedaluwarsa lebih dulu
            $batches = $query->orderBy('expiry_date', 'asc')->get();
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
                    'notes' => $notes . ($method === 'fefo' ? ' (FEFO)' : ' (FIFO)')
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
     * Memeriksa apakah produk memiliki batch dengan tanggal kedaluwarsa
     *
     * @param int $productId ID produk
     * @return bool
     */
    public function hasExpiredBatches($productId)
    {
        return ProductBatch::where('product_id', $productId)
            ->where('remaining_quantity', '>', 0)
            ->whereNotNull('expiry_date')
            ->exists();
    }
    
    /**
     * Mendapatkan batch yang akan kedaluwarsa dalam waktu dekat
     *
     * @param int $days Jumlah hari ke depan
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getExpiringBatches($days = 30)
    {
        $expiryDate = now()->addDays($days);
        
        return ProductBatch::whereNotNull('expiry_date')
            ->where('remaining_quantity', '>', 0)
            ->where('expiry_date', '<=', $expiryDate)
            ->where('expiry_date', '>=', now())
            ->orderBy('expiry_date', 'asc')
            ->get();
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
