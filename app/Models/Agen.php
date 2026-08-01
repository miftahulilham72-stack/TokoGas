<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agen extends Model
{
    use HasFactory;

    protected $table = 'agens'; // <- nama tabel di database

    protected $fillable = [
        'nama',
        'no_hp',
        'alamat'
    ];

    public function pembelians()
    {
        return $this->hasMany(Pembelian::class);
    }

    public function hargaAgen()
    {
        return $this->hasMany(HargaAgen::class);
    }
}