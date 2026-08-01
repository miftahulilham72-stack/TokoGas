<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penjualans', function (Blueprint $table) {
            $table->id();
            $table->enum('tipe_pelanggan', ['toko', 'rumahan']);
            $table->date('tanggal_jual');
            $table->enum('status_pembayaran', ['lunas', 'hutang'])->default('lunas');
            $table->decimal('total_harga', 15, 2);
            $table->date('jatuh_tempo')->nullable(); // jika hutang
            $table->string('nama_pelanggan')->nullable(); // untuk toko yang hutang
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penjualans');
    }
};