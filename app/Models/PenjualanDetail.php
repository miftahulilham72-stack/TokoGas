<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenjualanDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'penjualan_id',
        'jenis_gas_id',
        'jumlah_jual',
        'harga_jual_saat_itu',
        'subtotal'
    ];

    // Relasi ke penjualan
    public function penjualan()
    {
        return $this->belongsTo(Penjualan::class);
    }

    // Relasi ke jenis gas
    public function jenisGas()
    {
        return $this->belongsTo(JenisGas::class);
    }

    // Relasi ke penjualan batch (FIFO)
    public function batches()
    {
        return $this->hasMany(PenjualanBatch::class);
    }
}