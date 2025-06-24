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
        Schema::table('purchase_details', function (Blueprint $table) {
            $table->unsignedBigInteger('unit_id')->after('product_id')->nullable()->comment('Unit of measure for the product');

            $table->integer('conversion_factor')->default(0);
            $table->integer('base_quantity')->default(0)->comment('Quantity converted to base unit');
            $table->foreign('unit_id')->references('id')->on('unit_of_measures');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('purchase_details', function (Blueprint $table) {
            //
        });
    }
};
