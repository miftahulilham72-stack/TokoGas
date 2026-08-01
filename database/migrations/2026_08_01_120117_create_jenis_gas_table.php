<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jenis_gas', function (Blueprint $table) {
            $table->id();
            $table->string('nama'); // "3kg", "5kg (Blue Gas)", "5.5kg (Pink Gas)", "12kg"
            $table->integer('stok_minimum')->default(2); // default 2, khusus 3kg diisi 30
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jenis_gas');
    }
};