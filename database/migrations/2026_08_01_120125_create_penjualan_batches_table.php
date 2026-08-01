<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penjualan_batches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penjualan_detail_id')->constrained('penjualan_details')->onDelete('cascade');
            $table->foreignId('pembelian_detail_id')->constrained('pembelian_details')->onDelete('cascade');
            $table->integer('jumlah_diambil');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penjualan_batches');
    }
};