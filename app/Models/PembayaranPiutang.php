<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PembayaranPiutang extends Model
{
    use HasFactory;

    protected $table = 'pembayaran_piutangs'; // <- PERBAIKAN

    protected $fillable = [
        'penjualan_id',
        'jumlah_bayar',
        'tanggal_bayar'
    ];

    protected $casts = [
        'tanggal_bayar' => 'date',
    ];

    public function penjualan()
    {
        return $this->belongsTo(Penjualan::class);
    }
}