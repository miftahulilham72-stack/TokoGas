<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PembelianDetail extends Model
{
    use HasFactory;

    protected $table = 'pembelian_details';

    protected $fillable = [
        'pembelian_id',
        'jenis_gas_id',
        'jumlah_beli',
        'harga_beli_saat_itu',
        'sisa_stok'
    ];

    public function pembelian()
    {
        return $this->belongsTo(Pembelian::class);
    }

    public function jenisGas()
    {
        return $this->belongsTo(JenisGas::class);
    }

    public function penjualanBatches()
    {
        return $this->hasMany(PenjualanBatch::class);
    }

    public function getStokTersisaAttribute()
    {
        return $this->sisa_stok;
    }
}