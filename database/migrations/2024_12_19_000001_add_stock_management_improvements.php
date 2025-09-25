<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add indexes for better performance on stock-related queries
        // Check if table and indexes don't already exist before creating them
        
        if (Schema::hasTable('product_batches')) {
            if (!$this->indexExists('product_batches', 'idx_product_batches_fifo')) {
                Schema::table('product_batches', function (Blueprint $table) {
                    $table->index(['product_id', 'created_at'], 'idx_product_batches_fifo');
                });
            }
            
            if (!$this->indexExists('product_batches', 'idx_product_batches_fefo')) {
                Schema::table('product_batches', function (Blueprint $table) {
                    $table->index(['product_id', 'expiry_date'], 'idx_product_batches_fefo');
                });
            }
            
            if (!$this->indexExists('product_batches', 'idx_product_batches_stock')) {
                Schema::table('product_batches', function (Blueprint $table) {
                    $table->index(['product_id', 'remaining_quantity'], 'idx_product_batches_stock');
                });
            }
            
            if (!$this->indexExists('product_batches', 'idx_product_batches_selection')) {
                Schema::table('product_batches', function (Blueprint $table) {
                    $table->index(['product_id', 'remaining_quantity', 'created_at'], 'idx_product_batches_selection');
                });
            }
        }

        // Add batch_id column to stock_movements if it doesn't exist
        if (Schema::hasTable('stock_movements') && !Schema::hasColumn('stock_movements', 'batch_id')) {
            Schema::table('stock_movements', function (Blueprint $table) {
                $table->unsignedBigInteger('batch_id')->nullable()->after('product_id');
            });
        }

        // Add missing columns to products table
        if (Schema::hasTable('products')) {
            Schema::table('products', function (Blueprint $table) {
                if (!Schema::hasColumn('products', 'stock_method')) {
                    $table->enum('stock_method', ['auto', 'fifo', 'fefo'])->default('auto')->after('min_stock');
                }
                if (!Schema::hasColumn('products', 'is_perishable')) {
                    $table->boolean('is_perishable')->default(false)->after('stock_method');
                }
                if (!Schema::hasColumn('products', 'requires_expiry_date')) {
                    $table->boolean('requires_expiry_date')->default(false)->after('is_perishable');
                }
                if (!Schema::hasColumn('products', 'actual_stock')) {
                    $table->integer('actual_stock')->default(0)->after('stock');
                }
            });
        }

        if (!$this->indexExists('stock_movements', 'idx_stock_movements_history')) {
            Schema::table('stock_movements', function (Blueprint $table) {
                $table->index(['product_id', 'created_at'], 'idx_stock_movements_history');
            });
        }
        
        if (!$this->indexExists('stock_movements', 'idx_stock_movements_reference')) {
            Schema::table('stock_movements', function (Blueprint $table) {
                $table->index(['reference_type', 'reference_id'], 'idx_stock_movements_reference');
            });
        }
        
        if (Schema::hasColumn('stock_movements', 'batch_id') && !$this->indexExists('stock_movements', 'idx_stock_movements_batch')) {
            Schema::table('stock_movements', function (Blueprint $table) {
                $table->index(['batch_id', 'created_at'], 'idx_stock_movements_batch');
            });
        }

        if (!$this->indexExists('products', 'idx_products_stock_levels')) {
            Schema::table('products', function (Blueprint $table) {
                $table->index(['stock', 'min_stock'], 'idx_products_stock_levels');
            });
        }
        
        if (!$this->indexExists('products', 'idx_products_stock_method')) {
            Schema::table('products', function (Blueprint $table) {
                $table->index('stock_method', 'idx_products_stock_method');
            });
        }
        
        if (!$this->indexExists('products', 'idx_products_expiry')) {
            Schema::table('products', function (Blueprint $table) {
                $table->index(['is_perishable', 'requires_expiry_date'], 'idx_products_expiry');
            });
        }

        // Note: Check constraints are not supported by Laravel Blueprint
        // Data integrity should be enforced at the application level

        // Add comment to stock field indicating it's deprecated
        Schema::table('products', function (Blueprint $table) {
            $table->integer('stock')->comment('DEPRECATED: Use actual_stock calculated from batches instead')->change();
        });
    }

    /**
     * Check if an index exists on a table
     */
    private function indexExists($table, $indexName)
    {
        $indexes = \DB::select("SHOW INDEX FROM {$table} WHERE Key_name = ?", [$indexName]);
        return !empty($indexes);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_batches', function (Blueprint $table) {
            $table->dropIndex('idx_product_batches_fifo');
            $table->dropIndex('idx_product_batches_fefo');
            $table->dropIndex('idx_product_batches_stock');
            $table->dropIndex('idx_product_batches_selection');
        });

        Schema::table('stock_movements', function (Blueprint $table) {
            $table->dropIndex('idx_stock_movements_history');
            $table->dropIndex('idx_stock_movements_reference');
            $table->dropIndex('idx_stock_movements_batch');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex('idx_products_stock_levels');
            $table->dropIndex('idx_products_stock_method');
            $table->dropIndex('idx_products_expiry');
        });

        Schema::table('products', function (Blueprint $table) {
            // Remove comment from stock field
            $table->integer('stock')->comment('')->change();
        });
    }
};