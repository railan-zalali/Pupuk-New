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
        Schema::create('purchase_receipt_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_receipt_id')->constrained()->onDelete('cascade');
            $table->foreignId('purchase_detail_id')->constrained();
            $table->foreignId('product_id')->constrained();
            $table->integer('received_quantity')->default(0);
            $table->date('expire_date')->nullable();
            $table->integer('base_quantity')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_receipt_details');
    }
};
