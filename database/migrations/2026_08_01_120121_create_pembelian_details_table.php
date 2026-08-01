<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pembelian_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pembelian_id')->constrained('pembelians')->onDelete('cascade');
            $table->foreignId('jenis_gas_id')->constrained('jenis_gas')->onDelete('cascade');
            $table->integer('jumlah_beli');
            $table->decimal('harga_beli_saat_itu', 15, 2);
            $table->integer('sisa_stok'); // untuk FIFO tracking
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pembelian_details');
    }
};