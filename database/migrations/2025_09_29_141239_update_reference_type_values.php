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
        // Update reference_type from 'sale' to 'App\Models\Sale'
        DB::table('stock_movements')
            ->where('reference_type', 'sale')
            ->update(['reference_type' => 'App\Models\Sale']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert reference_type from 'App\Models\Sale' back to 'sale'
        DB::table('stock_movements')
            ->where('reference_type', 'App\Models\Sale')
            ->update(['reference_type' => 'sale']);
    }
};
