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
        Schema::table('products', function (Blueprint $table) {
            // Stock method configuration: 'auto', 'fifo', 'fefo'
            $table->enum('stock_method', ['auto', 'fifo', 'fefo'])->default('auto')->after('min_stock');
            
            // Whether this product requires expiry date tracking
            $table->boolean('requires_expiry_date')->default(false)->after('stock_method');
            
            // Whether this product is perishable (affects FEFO recommendations)
            $table->boolean('is_perishable')->default(false)->after('requires_expiry_date');
            
            // Days before expiry to show warnings (null = use system default)
            $table->integer('expiry_warning_days')->nullable()->after('is_perishable');
            
            // Whether to enforce strict expiry date validation
            $table->boolean('strict_expiry_validation')->default(false)->after('expiry_warning_days');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'stock_method',
                'requires_expiry_date',
                'is_perishable',
                'expiry_warning_days',
                'strict_expiry_validation'
            ]);
        });
    }
};