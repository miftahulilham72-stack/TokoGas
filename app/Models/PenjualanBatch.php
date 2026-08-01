<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\PenjualanDetail;
use App\Models\PembelianDetail;

class PenjualanBatch extends Model
{
    use HasFactory;

    protected $table = 'penjualan_batches'; // <- PERBAIKAN

    protected $fillable = [
        'penjualan_detail_id',
        'pembelian_detail_id',
        'jumlah_diambil'
    ];

    public function penjualanDetail()
    {
        return $this->belongsTo(PenjualanDetail::class);
    }

    public function pembelianDetail()
    {
        return $this->belongsTo(PembelianDetail::class);
    }

    public function getHargaPokokAttribute()
    {
        return $this->pembelianDetail->harga_beli_saat_itu;
    }

    public function getSubtotalPokokAttribute()
    {
        return $this->jumlah_diambil * $this->harga_pokok;
    }
}