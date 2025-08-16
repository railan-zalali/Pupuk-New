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
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Throwable;

class ProductsImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnError, WithBatchInserts
{
    use SkipsErrors;

    /**
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

            // Periksa apakah produk dengan kode ini sudah ada
            $existingProduct = Product::where('code', $row['kode'])->first();

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

            // Validasi dan parsing tanggal kedaluwarsa
            $expireDate = null;
            if (isset($row['expire_date']) && !empty($row['expire_date'])) {
                try {
                    // Coba berbagai format tanggal yang mungkin
                    if (is_numeric($row['expire_date'])) {
                        // Jika berupa angka, coba konversi dari Excel date format
                        $expireDate = Carbon::instance(Date::excelToDateTimeObject($row['expire_date']));
                    } else {
                        // Coba parse sebagai string tanggal
                        $expireDate = Carbon::parse($row['expire_date']);
                    }

                    // Validasi bahwa tanggal kedaluwarsa harus di masa depan
                    if ($expireDate->isPast()) {
                        // Jika tanggal sudah lewat, set ke null atau tambahkan 1 tahun
                        $expireDate = Carbon::now()->addYear();
                    }
                } catch (\Exception $e) {
                    // Jika format tanggal tidak valid, coba format manual
                    try {
                        // Coba format DD-MM-YYYY atau YYYY-MM-DD
                        if (preg_match('/^(\d{4})-(\d{1,2})-(\d{1,2})$/', $row['expire_date'], $matches)) {
                            $expireDate = Carbon::createFromDate($matches[1], $matches[2], $matches[3]);
                        } elseif (preg_match('/^(\d{1,2})-(\d{1,2})-(\d{4})$/', $row['expire_date'], $matches)) {
                            $expireDate = Carbon::createFromDate($matches[3], $matches[2], $matches[1]);
                        } else {
                            $expireDate = null;
                        }
                    } catch (\Exception $e) {
                        // Jika masih gagal, set ke null
                        $expireDate = null;
                    }
                }
            }

            // Ambil nomor batch jika ada
            $batchNumber = $row['batch_number'] ?? null;

            if ($existingProduct) {
                // Update produk yang sudah ada
                $existingProduct->update([
                    'category_id' => $row['category_id'] ?? $existingProduct->category_id,
                    'supplier_id' => $row['supplier_id'] ?? $existingProduct->supplier_id,
                    'name' => $row['nama'] ?? $existingProduct->name,
                    'description' => $row['deskripsi'] ?? $existingProduct->description,
                    'purchase_price' => $purchasePrice,
                    'selling_price' => $sellingPrice,
                    'min_stock' => $minStock,
                ]);

                // Jika ada pembaruan stok, buat batch baru dan catat pergerakan stok
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
            'expire_date' => 'nullable',
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
            'expire_date.date' => 'Tanggal kedaluwarsa harus berupa format tanggal yang valid (YYYY-MM-DD)',
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
