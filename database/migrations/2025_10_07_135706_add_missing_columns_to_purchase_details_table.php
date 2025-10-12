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
        Schema::table('purchase_details', function (Blueprint $table) {
            if (!Schema::hasColumn('purchase_details', 'base_quantity')) {
                $table->decimal('base_quantity', 10, 2)->default(0)->after('quantity');
            }
            if (!Schema::hasColumn('purchase_details', 'conversion_factor')) {
                $table->decimal('conversion_factor', 10, 4)->default(1)->after('subtotal');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('purchase_details', function (Blueprint $table) {
            $table->dropColumn(['base_quantity', 'conversion_factor']);
        });
    }
};
