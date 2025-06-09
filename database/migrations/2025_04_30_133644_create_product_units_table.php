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
        Schema::create('product_units', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('unit_id')->constrained('unit_of_measures')->onDelete('cascade');
            $table->integer('conversion_factor')->default(1);
            $table->integer('purchase_price')->default(0);
            $table->integer('selling_price')->default(0);
            $table->date('expire_date')->nullable();
            $table->boolean('is_default')->default(false);
            $table->timestamps();
            $table->unique(['product_id', 'unit_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_units');
    }
};
