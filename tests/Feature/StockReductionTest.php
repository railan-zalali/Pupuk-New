<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Product;
use App\Models\ProductBatch;
use App\Models\StockMovement;
use App\Models\Category;
use App\Models\UnitOfMeasure;
use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;

class StockReductionTest extends TestCase
{
    use RefreshDatabase;

    public function test_stock_reduction_logic()
    {
        // Create necessary dependencies
        $category = Category::factory()->create();
        $unitOfMeasure = UnitOfMeasure::factory()->create();
        
        // Create a product with initial stock
        $product = Product::factory()->create([
            'category_id' => $category->id,
            'stock' => 100
        ]);

        // Create some batches for the product
        ProductBatch::factory()->create([
            'product_id' => $product->id,
            'quantity' => 50,
            'remaining_quantity' => 50,
            'batch_number' => 'BATCH001'
        ]);
        
        ProductBatch::factory()->create([
            'product_id' => $product->id,
            'quantity' => 50,
            'remaining_quantity' => 50,
            'batch_number' => 'BATCH002'
        ]);

        // Refresh product to get updated actual_stock
        $product->syncStockFromBatches();
        $product->refresh();
        
        $initialStock = $product->actual_stock;
        $reductionAmount = 30;

        // Test stock reduction using FifoService directly
        if (class_exists('\App\Services\FifoService')) {
            $fifoService = new \App\Services\FifoService();
            
            // Test that we can reduce stock
            $fifoService->reduceStock(
                $product->id,
                $reductionAmount,
                'adjustment',
                $product->id,
                'Test stock reduction'
            );

            // Refresh product and check stock
            $product->syncStockFromBatches();
            $product->refresh();
            
            // Check that actual_stock is reduced correctly
            $this->assertEquals($initialStock - $reductionAmount, $product->actual_stock);
            
            // Check that batches are updated correctly (FIFO should reduce from first batch)
            $batches = ProductBatch::where('product_id', $product->id)
                ->orderBy('created_at')
                ->get();
                
            $this->assertEquals(20, $batches->first()->remaining_quantity); // 50 - 30 = 20
            $this->assertEquals(50, $batches->last()->remaining_quantity);  // unchanged
            
            // Check that StockMovement was created
            $stockMovement = StockMovement::where('product_id', $product->id)
                ->where('type', 'out')
                ->where('reference_type', 'adjustment')
                ->first();
                
            $this->assertNotNull($stockMovement);
            $this->assertEquals($reductionAmount, $stockMovement->quantity);
        } else {
            $this->markTestSkipped('FifoService class not found');
        }
    }

    public function test_stock_reduction_insufficient_stock()
    {
        // Create necessary dependencies
        $category = Category::factory()->create();
        
        // Create a product with limited stock
        $product = Product::factory()->create([
            'category_id' => $category->id,
            'stock' => 10
        ]);

        // Create a batch with limited stock
        ProductBatch::factory()->create([
            'product_id' => $product->id,
            'quantity' => 10,
            'remaining_quantity' => 10
        ]);

        $product->syncStockFromBatches();
        $product->refresh();

        // Test that FifoService throws exception for insufficient stock
        if (class_exists('\App\Services\FifoService')) {
            $fifoService = new \App\Services\FifoService();
            
            $this->expectException(\Exception::class);
            $this->expectExceptionMessage('Stok tidak cukup');
            
            $fifoService->reduceStock(
                $product->id,
                20, // More than available
                'adjustment',
                $product->id,
                'Test insufficient stock'
            );
        } else {
            $this->markTestSkipped('FifoService class not found');
        }
    }

    public function test_product_controller_stock_reduction_logic()
    {
        // Create necessary dependencies
        $category = Category::factory()->create();
        
        // Create a product with initial stock
        $product = Product::factory()->create([
            'category_id' => $category->id,
            'stock' => 100
        ]);

        // Create some batches for the product
        ProductBatch::factory()->create([
            'product_id' => $product->id,
            'quantity' => 60,
            'remaining_quantity' => 60,
            'batch_number' => 'BATCH001'
        ]);
        
        ProductBatch::factory()->create([
            'product_id' => $product->id,
            'quantity' => 40,
            'remaining_quantity' => 40,
            'batch_number' => 'BATCH002'
        ]);

        $product->syncStockFromBatches();
        $product->refresh();
        
        $initialStock = $product->actual_stock;
        
        // Test the logic that would be used in ProductController
        $quantity = 25;
        
        // Check if we have sufficient stock
        $this->assertGreaterThanOrEqual($quantity, $product->actual_stock);
        
        // Simulate the FifoService call
        if (class_exists('\App\Services\FifoService')) {
            $fifoService = new \App\Services\FifoService();
            $fifoService->reduceStock(
                $product->id,
                $quantity,
                'adjustment',
                $product->id,
                'Stock adjustment - reduction'
            );
            
            // Sync and refresh as done in ProductController
            $product->syncStockFromBatches();
            $product->refresh();
            
            // Verify the stock was reduced correctly
            $this->assertEquals($initialStock - $quantity, $product->actual_stock);
            
            // Verify batches were updated correctly
            $batches = ProductBatch::where('product_id', $product->id)
                ->orderBy('created_at')
                ->get();
                
            $this->assertEquals(35, $batches->first()->remaining_quantity); // 60 - 25 = 35
            $this->assertEquals(40, $batches->last()->remaining_quantity);  // unchanged
        } else {
            $this->markTestSkipped('FifoService class not found');
        }
    }
}