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
        Schema::create('sale_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sale_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained();
            $table->unsignedBigInteger('product_unit_id')->nullable();
            $table->unsignedBigInteger('unit_id')->nullable();
            $table->integer('quantity');
            $table->integer('base_quantity')->comment('Quantity in base unit for stock calculation');
            $table->integer('price');
            $table->integer('subtotal');
            $table->timestamps();

            $table->foreign('product_unit_id')->references('id')->on('product_units')->onDelete('set null');
            $table->foreign('unit_id')->references('id')->on('unit_of_measures')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sale_details');
    }
};
