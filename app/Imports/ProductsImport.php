<?php

namespace App\Imports;

use App\Models\Product;
use App\Models\ProductBatch;
use App\Models\ProductUnit;
use App\Models\StockMovement;
use App\Models\UnitOfMeasure;
use App\Services\FifoService;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use App\Helpers\DateValidationHelper;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Throwable;

class ProductsImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnError, WithBatchInserts
{
    use SkipsErrors;

    /**
     * Import produk dari file Excel/CSV
     * 
     * Logika import:
     * 1. Cek produk existing berdasarkan kode (jika ada)
     * 2. Jika tidak ditemukan, cek berdasarkan nama produk
     * 3. Jika produk sudah ada, update data dan tambahkan stock (tidak membuat duplikat)
     * 4. Jika produk belum ada, buat produk baru
     *
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        DB::beginTransaction();
        try {
            // Inisialisasi FifoService
            $fifoService = new FifoService();

            // Periksa apakah produk dengan kode atau nama ini sudah ada
            $existingProduct = null;
            if (!empty($row['kode'])) {
                $existingProduct = Product::where('code', $row['kode'])->first();
            }
            
            // Jika tidak ditemukan berdasarkan kode, cari berdasarkan nama
            if (!$existingProduct && !empty($row['nama'])) {
                $existingProduct = Product::where('name', $row['nama'])->first();
            }

            // Unit default
            $unitId = $row['unit_id'] ?? null;
            if (!$unitId && isset($row['unit_nama'])) {
                $unit = UnitOfMeasure::where('name', $row['unit_nama'])->first();
                $unitId = $unit ? $unit->id : UnitOfMeasure::first()->id;
            } else {
                $unitId = UnitOfMeasure::first()->id;
            }

            $purchasePrice = $row['purchase_price'] ?? 0;
            $sellingPrice = $row['selling_price'] ?? 0;
            $stock = $row['stock'] ?? 0;
            $minStock = $row['min_stock'] ?? 0;

            // Validasi dan parsing tanggal kedaluwarsa menggunakan DateValidationHelper
            $expireDate = null;
            if (isset($row['expire_date']) && !empty($row['expire_date'])) {
                try {
                    // Jika berupa angka (Excel date format), konversi terlebih dahulu
                    if (is_numeric($row['expire_date'])) {
                        $expireDate = Carbon::instance(Date::excelToDateTimeObject($row['expire_date']));
                    } else {
                        // Gunakan DateValidationHelper untuk parsing format yang fleksibel
                        $dateString = trim($row['expire_date']);
                        
                        // Coba format YYYY-MM-DD
                        if (preg_match('/^(\d{4})-(\d{1,2})-(\d{1,2})$/', $dateString, $matches)) {
                            $expireDate = Carbon::createFromDate($matches[1], $matches[2], $matches[3]);
                        }
                        // Coba format DD-MM-YYYY
                        elseif (preg_match('/^(\d{1,2})-(\d{1,2})-(\d{4})$/', $dateString, $matches)) {
                            $expireDate = Carbon::createFromDate($matches[3], $matches[2], $matches[1]);
                        }
                        // Coba format DD/MM/YYYY atau MM/DD/YYYY
                        elseif (preg_match('/^(\d{1,2})\/(\d{1,2})\/(\d{4})$/', $dateString, $matches)) {
                            // Untuk membedakan MM/DD/YYYY dari DD/MM/YYYY, kita cek apakah nilai pertama > 12
                            if ($matches[1] > 12) {
                                // Jika nilai pertama > 12, maka ini format DD/MM/YYYY
                                $expireDate = Carbon::createFromDate($matches[3], $matches[2], $matches[1]);
                            } else {
                                // Jika nilai pertama <= 12, anggap sebagai MM/DD/YYYY
                                $expireDate = Carbon::createFromDate($matches[3], $matches[1], $matches[2]);
                            }
                        }
                        // Fallback: coba parse langsung
                        else {
                            $expireDate = Carbon::parse($dateString);
                        }
                    }
                } catch (\Exception $e) {
                    // Jika parsing gagal, set ke null (akan divalidasi oleh rules)
                    $expireDate = null;
                }
            }

            // Ambil nomor batch jika ada
            $batchNumber = $row['batch_number'] ?? null;

            if ($existingProduct) {
                // Update produk yang sudah ada
                $updateData = [
                    'category_id' => $row['category_id'] ?? $existingProduct->category_id,
                    'supplier_id' => $row['supplier_id'] ?? $existingProduct->supplier_id,
                    'name' => $row['nama'] ?? $existingProduct->name,
                    'description' => $row['deskripsi'] ?? $existingProduct->description,
                    'purchase_price' => $purchasePrice,
                    'selling_price' => $sellingPrice,
                    'min_stock' => $minStock,
                ];
                
                // Update kode jika ada dan belum ada kode sebelumnya atau kode berbeda
                if (!empty($row['kode']) && ($existingProduct->code != $row['kode'])) {
                    $updateData['code'] = $row['kode'];
                }
                
                $existingProduct->update($updateData);

                // Jika ada pembaruan stok, tambahkan ke stok existing (tidak membuat produk duplikat)
                if ($stock > 0) {
                    $beforeStock = $existingProduct->stock;
                    $afterStock = $beforeStock + $stock;

                    // Update stok produk
                    $existingProduct->update(['stock' => $afterStock]);

                    // Buat batch baru menggunakan FifoService
                    $batch = $fifoService->addBatch(
                        $existingProduct->id,
                        null, // Tidak ada purchase_id karena ini import
                        $stock,
                        $purchasePrice,
                        $batchNumber,
                        $expireDate
                    );

                    // Catat pergerakan stok
                    StockMovement::create([
                        'product_id' => $existingProduct->id,
                        'batch_id' => $batch->id,
                        'type' => 'in',
                        'quantity' => $stock,
                        'before_stock' => $beforeStock,
                        'after_stock' => $afterStock,
                        'reference_type' => 'import',
                        'reference_id' => $existingProduct->id,
                        'notes' => 'Import stok produk'
                    ]);
                }

                // Update atau buat unit produk default
                $productUnit = ProductUnit::where('product_id', $existingProduct->id)
                    ->where('is_default', true)
                    ->first();

                if ($productUnit) {
                    $productUnit->update([
                        'unit_id' => $unitId,
                        'conversion_factor' => 1, // Faktor konversi default
                        'purchase_price' => $purchasePrice,
                        'selling_price' => $sellingPrice,
                        'expire_date' => $expireDate,
                    ]);
                } else {
                    ProductUnit::create([
                        'product_id' => $existingProduct->id,
                        'unit_id' => $unitId,
                        'conversion_factor' => 1, // Faktor konversi default
                        'purchase_price' => $purchasePrice,
                        'selling_price' => $sellingPrice,
                        'expire_date' => $expireDate,
                        'is_default' => true,
                    ]);
                }

                DB::commit();
                return null; // Tidak perlu membuat model baru
            }

            // Generate kode produk jika tidak ada
            $code = $row['kode'] ?? ('PRD' . date('Ymd') . rand(1000, 9999));

            // Buat produk baru dengan stok awal 0 (akan ditambahkan melalui batch)
            $product = new Product([
                'category_id' => $row['category_id'] ?? 1, // Default ke kategori pertama jika tidak ada
                'supplier_id' => $row['supplier_id'] ?? 1, // Default ke supplier pertama jika tidak ada
                'name' => $row['nama'],
                'code' => $code,
                'description' => $row['deskripsi'] ?? null,
                'purchase_price' => $purchasePrice,
                'selling_price' => $sellingPrice,
                'stock' => 0, // Mulai dengan 0, akan diupdate setelah batch dibuat
                'min_stock' => $minStock,
            ]);

            // Simpan produk untuk mendapatkan ID
            $product->save();

            // Buat unit produk default
            ProductUnit::create([
                'product_id' => $product->id,
                'unit_id' => $unitId,
                'conversion_factor' => 1, // Faktor konversi default
                'purchase_price' => $purchasePrice,
                'selling_price' => $sellingPrice,
                'expire_date' => $expireDate,
                'is_default' => true,
            ]);

            if ($stock > 0) {
                // Update stok produk
                $product->update(['stock' => $stock]);

                // Buat batch baru menggunakan FifoService
                $batch = $fifoService->addBatch(
                    $product->id,
                    null, // Tidak ada purchase_id karena ini import
                    $stock,
                    $purchasePrice,
                    $batchNumber,
                    $expireDate
                );

                // Catat pergerakan stok awal
                StockMovement::create([
                    'product_id' => $product->id,
                    'batch_id' => $batch->id,
                    'type' => 'in',
                    'quantity' => $stock,
                    'before_stock' => 0,
                    'after_stock' => $stock,
                    'reference_type' => 'initial',
                    'reference_id' => $product->id,
                    'notes' => 'Stok awal dari import'
                ]);
            }

            DB::commit();
            return $product;
        } catch (Throwable $e) {
            DB::rollBack();
            $this->onError($e);
            return null;
        }
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'nama' => 'required|string',
            'kode' => 'nullable|string',
            'purchase_price' => 'nullable|numeric',
            'selling_price' => 'nullable|numeric',
            'stock' => 'nullable|integer',
            'min_stock' => 'nullable|integer',
            'category_id' => 'nullable|integer',
            'supplier_id' => 'nullable|integer',
            'batch_number' => 'nullable|string',
            'expire_date' => DateValidationHelper::getExpireDateRule(),
            'unit_nama' => 'nullable|string',
        ];
    }

    /**
     * @return array
     */
    public function customValidationMessages()
    {
        return [
            'nama.required' => 'Nama produk wajib diisi',
            'purchase_price.numeric' => 'Harga beli harus berupa angka',
            'selling_price.numeric' => 'Harga jual harus berupa angka',
            'stock.integer' => 'Stok harus berupa angka bulat',
            'min_stock.integer' => 'Stok minimal harus berupa angka bulat',
            'expire_date' => 'Tanggal kedaluwarsa harus berupa format tanggal yang valid (contoh: 2024-12-31, 31-12-2024, atau 31/12/2024)',
        ];
    }

    /**
     * @return int
     */
    public function batchSize(): int
    {
        return 100;
    }
}
