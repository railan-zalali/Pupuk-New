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
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('customer_id')->nullable();
            $table->dateTime('date');
            $table->integer('total_amount')->default(0);
            $table->integer('discount')->default(0);
            $table->integer('paid_amount')->default(0);
            $table->integer('down_payment')->default(0);
            $table->integer('change_amount')->default(0);
            $table->enum('payment_method', ['cash', 'transfer', 'credit']);
            $table->string('vehicle_type')->nullable();
            $table->string('vehicle_number')->nullable();
            $table->string('payment_status')->default('paid');
            $table->enum('status', ['draft', 'completed', 'cancelled'])->default('completed');
            $table->integer('remaining_amount')->default(0);
            $table->timestamp('due_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
