<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductUnit;
use App\Models\StockMovement;
use App\Models\Supplier;
use App\Models\UnitOfMeasure;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TestInsertProduct extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:test-insert-product';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test inserting a product to diagnose issues';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Testing product creation...');

        try {
            DB::beginTransaction();

            // Generate a unique product code
            $today = date('Ymd');
            $baseCode = 'PRD-' . $today . '-';

            // Find the highest number for today's products
            $lastProduct = Product::withTrashed()->where('code', 'like', $baseCode . '%')
                ->orderBy('code', 'desc')
                ->first();

            $lastNumber = 0;
            if ($lastProduct) {
                $lastNumber = (int) substr($lastProduct->code, -4);
            }

            // Generate new code
            $newNumber = $lastNumber + 1;
            $productCode = $baseCode . str_pad($newNumber, 4, '0', STR_PAD_LEFT);

            // Make sure it's unique
            while (Product::withTrashed()->where('code', $productCode)->exists()) {
                $newNumber++;
                $productCode = $baseCode . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
            }

            // Get a valid category and supplier
            $category = Category::first();
            if (!$category) {
                $this->error('No categories found! Creating a test category...');
                $category = Category::create(['name' => 'Test Category']);
            }

            $supplier = Supplier::first();
            if (!$supplier) {
                $this->error('No suppliers found! Creating a test supplier...');
                $supplier = Supplier::create(['name' => 'Test Supplier', 'phone' => '123456789', 'address' => 'Test Address']);
            }

            // Get a unit
            $unit = UnitOfMeasure::first();
            if (!$unit) {
                $this->error('No units found! Creating a test unit...');
                $unit = UnitOfMeasure::create([
                    'name' => 'Pieces',
                    'abbreviation' => 'Pcs',
                    'is_base_unit' => true
                ]);
            }

            // Create product
            $this->info('Creating product with these details:');
            $this->info("Code: $productCode");
            $this->info("Category: {$category->name} (ID: {$category->id})");
            $this->info("Supplier: {$supplier->name} (ID: {$supplier->id})");

            $product = Product::create([
                'category_id' => $category->id,
                'supplier_id' => $supplier->id,
                'name' => 'Test Product ' . time(),
                'code' => $productCode,
                'description' => 'Test product description',
                'purchase_price' => 10000,
                'selling_price' => 15000,
                'stock' => 100,
                'min_stock' => 10
            ]);

            if (!$product) {
                throw new \Exception('Failed to create product - no product returned');
            }

            $this->info("Product created with ID: {$product->id}");

            // Create product unit
            $productUnit = ProductUnit::create([
                'product_id' => $product->id,
                'unit_id' => $unit->id,
                'conversion_factor' => 1,
                'purchase_price' => 10000,
                'selling_price' => 15000,
                'is_default' => true
            ]);

            $this->info("Product unit created with ID: {$productUnit->id}");

            // Create stock movement
            $stockMovement = StockMovement::create([
                'product_id' => $product->id,
                'type' => 'in',
                'quantity' => 100,
                'before_stock' => 0,
                'after_stock' => 100,
                'reference_type' => 'initial',
                'reference_id' => $product->id,
                'notes' => 'Initial stock'
            ]);

            $this->info("Stock movement created with ID: {$stockMovement->id}");

            DB::commit();
            $this->info('Success! Product created successfully.');

            // Now try to retrieve it
            $retrievedProduct = Product::find($product->id);
            if ($retrievedProduct) {
                $this->info("Successfully retrieved product with name: {$retrievedProduct->name}");
            } else {
                $this->error("Failed to retrieve product with ID: {$product->id}");
            }
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error('Failed to create product: ' . $e->getMessage());
            $this->error('Error occurred on line: ' . $e->getLine() . ' in ' . $e->getFile());
            Log::error('Error testing product creation: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
        }
    }
}
