<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('harga_juals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jenis_gas_id')->constrained('jenis_gas')->onDelete('cascade');
            $table->enum('tipe_pelanggan', ['toko', 'rumahan']);
            $table->decimal('harga_jual', 15, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('harga_juals');
    }
};