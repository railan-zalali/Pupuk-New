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
        Schema::table('sales', function (Blueprint $table) {
            // Add composite index for draft queries
            $table->index(['status', 'created_at'], 'idx_draft_status_created');

            // Add index for payment status queries
            $table->index(['payment_method', 'payment_status'], 'idx_payment_method_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropIndex('idx_draft_status_created');
            $table->dropIndex('idx_payment_method_status');
        });
    }
};
