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
            $table->unsignedBigInteger('product_id')->nullable()->after('purchase_detail_id');
            $table->integer('quantity')->nullable()->after('received_quantity');
            $table->integer('base_quantity')->nullable()->after('quantity');
            $table->integer('receipt_quantity')->nullable()->after('base_quantity');
            
            // Add foreign key constraint for product_id
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('purchase_receipt_details', function (Blueprint $table) {
            // Drop foreign key first
            $table->dropForeign(['product_id']);
            
            // Drop the added columns
            $table->dropColumn(['product_id', 'quantity', 'base_quantity', 'receipt_quantity']);
        });
    }
};
