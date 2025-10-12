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
            // Add receipt_date column if it doesn't exist
            if (!Schema::hasColumn('purchase_receipts', 'receipt_date')) {
                $table->date('receipt_date')->nullable()->after('user_id');
            }
            
            // Add receipt_file column if it doesn't exist
            if (!Schema::hasColumn('purchase_receipts', 'receipt_file')) {
                $table->string('receipt_file')->nullable()->after('receipt_number');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('purchase_receipts', function (Blueprint $table) {
            $table->dropColumn(['receipt_date', 'receipt_file']);
        });
    }
};
