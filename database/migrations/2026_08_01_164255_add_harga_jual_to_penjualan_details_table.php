<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('penjualan_details', function (Blueprint $table) {
            // Kolom ini sudah ada, tapi kita pastikan tipe datanya decimal
            // Tidak perlu tambah lagi
        });
    }

    public function down(): void
    {
        Schema::table('penjualan_details', function (Blueprint $table) {
            //
        });
    }
};