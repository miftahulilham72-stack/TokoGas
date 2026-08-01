<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembelian extends Model
{
    use HasFactory;

    protected $table = 'pembelians'; // <- PERBAIKAN

    protected $fillable = [
        'agen_id',
        'tanggal_beli',
        'total_harga',
        'no_faktur'
    ];

    protected $casts = [
        'tanggal_beli' => 'date',
    ];

    public function agen()
    {
        return $this->belongsTo(Agen::class);
    }

    public function details()
    {
        return $this->hasMany(PembelianDetail::class);
    }
}