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
        Schema::create('product_batches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained();
            $table->string('batch_number');
            $table->integer('quantity');
            $table->integer('remaining_quantity');
            $table->date('production_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->decimal('purchase_price', 15, 2)->default(0);
            $table->foreignId('purchase_id')->nullable()->constrained();
            $table->timestamps();
            
            // Indeks untuk pencarian cepat
            $table->index(['product_id', 'remaining_quantity']);
            $table->index(['product_id', 'expiry_date']);
        });
        
        // Tambahkan kolom batch_id ke tabel stock_movements
        Schema::table('stock_movements', function (Blueprint $table) {
            $table->foreignId('batch_id')->nullable()->after('product_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stock_movements', function (Blueprint $table) {
            $table->dropColumn('batch_id');
        });
        
        Schema::dropIfExists('product_batches');
    }
};