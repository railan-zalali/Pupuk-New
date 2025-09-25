<?php

namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SyncStockFromBatches extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stock:sync-from-batches 
                            {--dry-run : Show what would be updated without making changes}
                            {--product= : Sync specific product by ID}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync product stock field from actual batch remaining quantities';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $dryRun = $this->option('dry-run');
        $productId = $this->option('product');

        $this->info('Starting stock synchronization from batches...');
        
        if ($dryRun) {
            $this->warn('DRY RUN MODE - No changes will be made');
        }

        // Build query
        $query = Product::with('batches');
        
        if ($productId) {
            $query->where('id', $productId);
        }

        $products = $query->get();
        
        if ($products->isEmpty()) {
            $this->error('No products found to sync.');
            return 1;
        }

        $this->info("Found {$products->count()} products to process.");

        $updated = 0;
        $errors = 0;
        $unchanged = 0;

        $progressBar = $this->output->createProgressBar($products->count());
        $progressBar->start();

        foreach ($products as $product) {
            try {
                $currentStock = $product->stock;
                $actualStock = $product->batches->sum('remaining_quantity');
                
                if ($currentStock != $actualStock) {
                    if (!$dryRun) {
                        $product->update(['stock' => $actualStock]);
                    }
                    
                    $this->newLine();
                    $this->line("Product: {$product->name} (ID: {$product->id})");
                    $this->line("  Current stock: {$currentStock}");
                    $this->line("  Actual stock from batches: {$actualStock}");
                    $this->line("  Difference: " . ($actualStock - $currentStock));
                    
                    if ($dryRun) {
                        $this->line("  [DRY RUN] Would update stock to {$actualStock}");
                    } else {
                        $this->line("  ✓ Updated stock to {$actualStock}");
                    }
                    
                    $updated++;
                } else {
                    $unchanged++;
                }
                
                $progressBar->advance();
                
            } catch (\Exception $e) {
                $this->newLine();
                $this->error("Error processing product {$product->name} (ID: {$product->id}): " . $e->getMessage());
                $errors++;
                $progressBar->advance();
            }
        }

        $progressBar->finish();
        $this->newLine(2);

        // Summary
        $this->info('Stock synchronization completed!');
        $this->table(
            ['Status', 'Count'],
            [
                ['Products updated', $updated],
                ['Products unchanged', $unchanged],
                ['Errors', $errors],
                ['Total processed', $products->count()]
            ]
        );

        if ($dryRun && $updated > 0) {
            $this->warn("Run without --dry-run to apply these changes.");
        }

        return $errors > 0 ? 1 : 0;
    }
}