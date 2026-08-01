<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisGas extends Model
{
    use HasFactory;

    protected $table = 'jenis_gas'; // <- PERBAIKAN

    protected $fillable = [
        'nama',
        'stok_minimum'
    ];

    public function stok()
    {
        return $this->hasOne(Stok::class);
    }

    public function hargaJual()
    {
        return $this->hasMany(HargaJual::class);
    }

    public function pembelianDetails()
    {
        return $this->hasMany(PembelianDetail::class);
    }

    public function penjualanDetails()
    {
        return $this->hasMany(PenjualanDetail::class);
    }

    public function getStokSekarangAttribute()
    {
        return $this->stok ? $this->stok->jumlah_stok : 0;
    }

    public function getHargaJual($tipePelanggan)
    {
        $harga = $this->hargaJual()->where('tipe_pelanggan', $tipePelanggan)->first();
        return $harga ? $harga->harga_jual : 0;
    }
}