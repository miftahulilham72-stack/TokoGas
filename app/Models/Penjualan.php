<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penjualan extends Model
{
    use HasFactory;

    protected $table = 'penjualans'; // <- PERBAIKAN

    protected $fillable = [
        'tipe_pelanggan',
        'tanggal_jual',
        'status_pembayaran',
        'total_harga',
        'jatuh_tempo',
        'nama_pelanggan'
    ];

    protected $casts = [
        'tanggal_jual' => 'date',
        'jatuh_tempo' => 'date',
    ];

    public function details()
    {
        return $this->hasMany(PenjualanDetail::class);
    }

    public function pembayaranPiutangs()
    {
        return $this->hasMany(PembayaranPiutang::class);
    }

    public function getSisaPiutangAttribute()
    {
        if ($this->status_pembayaran == 'lunas') {
            return 0;
        }

        $totalBayar = $this->pembayaranPiutangs()->sum('jumlah_bayar');
        return $this->total_harga - $totalBayar;
    }

    public function getStatusLunasAttribute()
    {
        return $this->sisa_piutang <= 0;
    }
}