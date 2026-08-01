<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HargaAgen extends Model
{
    use HasFactory;

    protected $table = 'harga_agens'; // <- PERBAIKAN

    protected $fillable = [
        'agen_id',
        'jenis_gas_id',
        'harga_beli',
        'tanggal_berlaku'
    ];

    public function agen()
    {
        return $this->belongsTo(Agen::class);
    }

    public function jenisGas()
    {
        return $this->belongsTo(JenisGas::class);
    }
}