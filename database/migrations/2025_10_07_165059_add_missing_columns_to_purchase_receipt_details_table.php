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
        Schema::table('purchase_receipt_details', function (Blueprint $table) {
            // Add missing columns that are in the model but not in the database
            if (!Schema::hasColumn('purchase_receipt_details', 'product_id')) {
                $table->unsignedBigInteger('product_id')->nullable()->after('purchase_detail_id');
                // Add foreign key constraint for product_id
                $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            }
            
            if (!Schema::hasColumn('purchase_receipt_details', 'quantity')) {
                $table->integer('quantity')->nullable()->after('received_quantity');
            }
            
            if (!Schema::hasColumn('purchase_receipt_details', 'base_quantity')) {
                $table->integer('base_quantity')->nullable()->after('quantity');
            }
            
            if (!Schema::hasColumn('purchase_receipt_details', 'receipt_quantity')) {
                $table->integer('receipt_quantity')->nullable()->after('base_quantity');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('purchase_receipt_details', function (Blueprint $table) {
            // Drop foreign key first if column exists
            if (Schema::hasColumn('purchase_receipt_details', 'product_id')) {
                $table->dropForeign(['product_id']);
            }
            
            // Drop columns that exist
            $columns = [];
            foreach (['product_id', 'quantity', 'base_quantity', 'receipt_quantity'] as $column) {
                if (Schema::hasColumn('purchase_receipt_details', $column)) {
                    $columns[] = $column;
                }
            }
            
            if (!empty($columns)) {
                $table->dropColumn($columns);
            }
        });
    }
};
