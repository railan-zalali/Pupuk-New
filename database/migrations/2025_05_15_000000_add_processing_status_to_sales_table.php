<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Ubah tipe enum untuk menambahkan status 'processing'
        DB::statement("ALTER TABLE sales MODIFY COLUMN status ENUM('draft', 'processing', 'completed', 'cancelled') DEFAULT 'completed'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Kembalikan ke status enum sebelumnya
        DB::statement("ALTER TABLE sales MODIFY COLUMN status ENUM('draft', 'completed', 'cancelled') DEFAULT 'completed'");
    }
};