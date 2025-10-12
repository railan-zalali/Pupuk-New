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
        Schema::table('purchase_receipts', function (Blueprint $table) {
            // Check if received_date column exists before modifying it
            if (Schema::hasColumn('purchase_receipts', 'received_date')) {
                $table->datetime('received_date')->nullable()->change();
            } else {
                // Add the column if it doesn't exist
                $table->datetime('received_date')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('purchase_receipts', function (Blueprint $table) {
            // Only revert if the column exists
            if (Schema::hasColumn('purchase_receipts', 'received_date')) {
                $table->datetime('received_date')->nullable(false)->change();
            }
        });
    }
};
