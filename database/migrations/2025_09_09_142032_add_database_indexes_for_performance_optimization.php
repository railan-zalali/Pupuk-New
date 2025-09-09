<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add indexes for sales table (only if they don't exist)
        if (Schema::hasTable('sales')) {
            Schema::table('sales', function (Blueprint $table) {
                if (!$this->indexExists('sales', 'idx_sales_status')) {
                    $table->index('status', 'idx_sales_status');
                }
                if (!$this->indexExists('sales', 'idx_sales_payment_method')) {
                    $table->index('payment_method', 'idx_sales_payment_method');
                }
                if (!$this->indexExists('sales', 'idx_sales_payment_status')) {
                    $table->index('payment_status', 'idx_sales_payment_status');
                }
                if (!$this->indexExists('sales', 'idx_sales_due_date')) {
                    $table->index('due_date', 'idx_sales_due_date');
                }
                if (!$this->indexExists('sales', 'idx_sales_status_created_at')) {
                    $table->index(['status', 'created_at'], 'idx_sales_status_created_at');
                }
                if (!$this->indexExists('sales', 'idx_sales_payment_method_status')) {
                    $table->index(['payment_method', 'payment_status'], 'idx_sales_payment_method_status');
                }
                if (!$this->indexExists('sales', 'idx_sales_overdue')) {
                    $table->index(['payment_method', 'payment_status', 'due_date'], 'idx_sales_overdue');
                }
            });
        }

        // Add indexes for products table
        if (Schema::hasTable('products')) {
            Schema::table('products', function (Blueprint $table) {
                if (!$this->indexExists('products', 'idx_products_category_id')) {
                    $table->index('category_id', 'idx_products_category_id');
                }
                if (!$this->indexExists('products', 'idx_products_stock')) {
                    $table->index('stock', 'idx_products_stock');
                }
                if (!$this->indexExists('products', 'idx_products_min_stock')) {
                    $table->index('min_stock', 'idx_products_min_stock');
                }
                if (!$this->indexExists('products', 'idx_products_low_stock')) {
                    $table->index(['stock', 'min_stock'], 'idx_products_low_stock');
                }
            });
        }

        // Add indexes for sale_details table
        if (Schema::hasTable('sale_details')) {
            Schema::table('sale_details', function (Blueprint $table) {
                if (!$this->indexExists('sale_details', 'idx_sale_details_sale_id')) {
                    $table->index('sale_id', 'idx_sale_details_sale_id');
                }
                if (!$this->indexExists('sale_details', 'idx_sale_details_product_id')) {
                    $table->index('product_id', 'idx_sale_details_product_id');
                }
                if (!$this->indexExists('sale_details', 'idx_sale_details_sale_product')) {
                    $table->index(['sale_id', 'product_id'], 'idx_sale_details_sale_product');
                }
            });
        }

        // Add indexes for stock_movements table
        if (Schema::hasTable('stock_movements')) {
            Schema::table('stock_movements', function (Blueprint $table) {
                if (!$this->indexExists('stock_movements', 'idx_stock_movements_product_id')) {
                    $table->index('product_id', 'idx_stock_movements_product_id');
                }
                if (!$this->indexExists('stock_movements', 'idx_stock_movements_type')) {
                    $table->index('movement_type', 'idx_stock_movements_type');
                }
                if (!$this->indexExists('stock_movements', 'idx_stock_movements_created_at')) {
                    $table->index('created_at', 'idx_stock_movements_created_at');
                }
                if (!$this->indexExists('stock_movements', 'idx_stock_movements_product_type')) {
                    $table->index(['product_id', 'movement_type'], 'idx_stock_movements_product_type');
                }
                if (!$this->indexExists('stock_movements', 'idx_stock_movements_product_date')) {
                    $table->index(['product_id', 'created_at'], 'idx_stock_movements_product_date');
                }
            });
        }

        // Add indexes for purchases table
        if (Schema::hasTable('purchases')) {
            Schema::table('purchases', function (Blueprint $table) {
                if (!$this->indexExists('purchases', 'idx_purchases_created_at')) {
                    $table->index('created_at', 'idx_purchases_created_at');
                }
                if (!$this->indexExists('purchases', 'idx_purchases_status')) {
                    $table->index('status', 'idx_purchases_status');
                }
                if (!$this->indexExists('purchases', 'idx_purchases_supplier_id')) {
                    $table->index('supplier_id', 'idx_purchases_supplier_id');
                }
                if (!$this->indexExists('purchases', 'idx_purchases_status_created_at')) {
                    $table->index(['status', 'created_at'], 'idx_purchases_status_created_at');
                }
            });
        }

        // Add indexes for customers table
        if (Schema::hasTable('customers')) {
            Schema::table('customers', function (Blueprint $table) {
                if (!$this->indexExists('customers', 'idx_customers_created_at')) {
                    $table->index('created_at', 'idx_customers_created_at');
                }
                if (!$this->indexExists('customers', 'idx_customers_nama')) {
                    $table->index('nama', 'idx_customers_nama');
                }
            });
        }

        // Add indexes for users table
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                if (!$this->indexExists('users', 'idx_users_created_at')) {
                    $table->index('created_at', 'idx_users_created_at');
                }
            });
        }
    }

    /**
     * Check if index exists
     */
    private function indexExists($table, $indexName)
    {
        $indexes = DB::select("SHOW INDEX FROM {$table}");
        foreach ($indexes as $index) {
            if ($index->Key_name === $indexName) {
                return true;
            }
        }
        return false;
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop indexes for sales table
        Schema::table('sales', function (Blueprint $table) {
            $table->dropIndex('idx_sales_created_at');
            $table->dropIndex('idx_sales_status');
            $table->dropIndex('idx_sales_payment_method');
            $table->dropIndex('idx_sales_payment_status');
            $table->dropIndex('idx_sales_due_date');
            $table->dropIndex('idx_sales_status_created_at');
            $table->dropIndex('idx_sales_payment_method_status');
            $table->dropIndex('idx_sales_overdue');
        });

        // Drop indexes for products table
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex('idx_products_category_id');
            $table->dropIndex('idx_products_stock');
            $table->dropIndex('idx_products_min_stock');
            $table->dropIndex('idx_products_low_stock');
        });

        // Drop indexes for sale_details table
        Schema::table('sale_details', function (Blueprint $table) {
            $table->dropIndex('idx_sale_details_sale_id');
            $table->dropIndex('idx_sale_details_product_id');
            $table->dropIndex('idx_sale_details_sale_product');
        });

        // Drop indexes for stock_movements table
        Schema::table('stock_movements', function (Blueprint $table) {
            $table->dropIndex('idx_stock_movements_product_id');
            $table->dropIndex('idx_stock_movements_type');
            $table->dropIndex('idx_stock_movements_created_at');
            $table->dropIndex('idx_stock_movements_product_type');
            $table->dropIndex('idx_stock_movements_product_date');
        });

        // Drop indexes for purchases table
        Schema::table('purchases', function (Blueprint $table) {
            $table->dropIndex('idx_purchases_created_at');
            $table->dropIndex('idx_purchases_status');
            $table->dropIndex('idx_purchases_supplier_id');
            $table->dropIndex('idx_purchases_status_created_at');
        });

        // Drop indexes for customers table
        Schema::table('customers', function (Blueprint $table) {
            $table->dropIndex('idx_customers_created_at');
            $table->dropIndex('idx_customers_nama');
        });

        // Drop indexes for users table
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex('idx_users_created_at');
        });
    }
};
