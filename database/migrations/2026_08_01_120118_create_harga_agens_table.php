<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('harga_agens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agen_id')->constrained('agens')->onDelete('cascade');
            $table->foreignId('jenis_gas_id')->constrained('jenis_gas')->onDelete('cascade');
            $table->decimal('harga_beli', 15, 2);
            $table->date('tanggal_berlaku');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('harga_agens');
    }
};