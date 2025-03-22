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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('nik')->unique();
            $table->string('nama');
            $table->text('alamat')->nullable();
            $table->string('desa_id');
            $table->string('kecamatan_id');
            $table->string('kabupaten_id');
            $table->string('provinsi_id');
            $table->string('desa_nama');
            $table->string('kecamatan_nama');
            $table->string('kabupaten_nama');
            $table->string('provinsi_nama');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
