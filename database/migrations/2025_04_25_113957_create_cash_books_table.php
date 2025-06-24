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
        Schema::create('cash_books', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->string('description');
            $table->string('reference_number')->nullable();
            // $table->decimal('debit', 15, 2)->default(0);
            $table->integer('debit')->default(0);
            // $table->decimal('credit', 15, 2)->default(0);
            $table->integer('credit')->default(0);
            // $table->decimal('balance', 15, 2);
            $table->integer('balance')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cash_books');
    }
};
