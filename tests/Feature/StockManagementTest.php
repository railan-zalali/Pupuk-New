<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\ProductBatch;
use App\Models\Category;
use App\Models\UnitOfMeasure;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StockManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_product_actual_stock_calculation()
    {
        // Create necessary dependencies
        $category = Category::factory()->create();
        
        // Create a product
        $product = Product::factory()->create([
            'category_id' => $category->id,
            'stock' => 0
        ]);

        // Create batches with different quantities
        ProductBatch::factory()->create([
            'product_id' => $product->id,
            'quantity' => 100,
            'remaining_quantity' => 80
        ]);
        
        ProductBatch::factory()->create([
            'product_id' => $product->id,
            'quantity' => 50,
            'remaining_quantity' => 30
        ]);

        // Refresh the product to get updated relationships
        $product->refresh();

        // Test that actual_stock returns sum of remaining quantities
        $this->assertEquals(110, $product->actual_stock);
    }

    public function test_sync_stock_from_batches()
    {
        // Create necessary dependencies
        $category = Category::factory()->create();
        
        // Create a product with incorrect stock
        $product = Product::factory()->create([
            'category_id' => $category->id,
            'stock' => 999 // Incorrect stock value
        ]);

        // Create batches
        ProductBatch::factory()->create([
            'product_id' => $product->id,
            'quantity' => 100,
            'remaining_quantity' => 75
        ]);

        // Sync stock from batches
        $product->syncStockFromBatches();

        // Verify stock is now correct
        $this->assertEquals(75, $product->stock);
        $this->assertEquals(75, $product->actual_stock);
    }
}