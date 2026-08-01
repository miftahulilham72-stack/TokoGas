<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HargaJual extends Model
{
    use HasFactory;

    protected $table = 'harga_juals'; // <- PERBAIKAN

    protected $fillable = [
        'jenis_gas_id',
        'tipe_pelanggan',
        'harga_jual'
    ];

    public function jenisGas()
    {
        return $this->belongsTo(JenisGas::class);
    }
}