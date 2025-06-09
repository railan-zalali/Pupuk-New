<?php

namespace App\Imports;

use App\Models\Product;
use App\Models\ProductUnit;
use App\Models\StockMovement;
use App\Models\UnitOfMeasure;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Illuminate\Support\Facades\DB;
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
            $expireDate = isset($row['expire_date']) ? Carbon::parse($row['expire_date']) : null;

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

                // Jika ada pembaruan stok, buat catatan pergerakan stok
                if ($stock != $existingProduct->stock) {
                    $beforeStock = $existingProduct->stock;
                    $afterStock = $stock;

                    // Update stok produk
                    $existingProduct->update(['stock' => $afterStock]);

                    // Catat pergerakan stok
                    StockMovement::create([
                        'product_id' => $existingProduct->id,
                        'type' => $afterStock > $beforeStock ? 'in' : 'out',
                        'quantity' => abs($afterStock - $beforeStock),
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

            // Buat produk baru
            $product = new Product([
                'category_id' => $row['category_id'] ?? 1, // Default ke kategori pertama jika tidak ada
                'supplier_id' => $row['supplier_id'] ?? 1, // Default ke supplier pertama jika tidak ada
                'name' => $row['nama'],
                'code' => $code,
                'description' => $row['deskripsi'] ?? null,
                'purchase_price' => $purchasePrice,
                'selling_price' => $sellingPrice,
                'stock' => $stock,
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

            // Catat pergerakan stok awal
            StockMovement::create([
                'product_id' => $product->id,
                'type' => 'in',
                'quantity' => $stock,
                'before_stock' => 0,
                'after_stock' => $stock,
                'reference_type' => 'initial',
                'reference_id' => $product->id,
                'notes' => 'Stok awal dari import'
            ]);

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
