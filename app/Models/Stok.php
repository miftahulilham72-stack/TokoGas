<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stok extends Model
{
    use HasFactory;

    protected $table = 'stoks'; // <- PERBAIKAN

    protected $fillable = [
        'jenis_gas_id',
        'jumlah_stok'
    ];

    public function jenisGas()
    {
        return $this->belongsTo(JenisGas::class);
    }

    public function tambahStok($jumlah)
    {
        $this->jumlah_stok += $jumlah;
        $this->save();
        return $this;
    }

    public function kurangiStok($jumlah)
    {
        if ($this->jumlah_stok < $jumlah) {
            throw new \Exception('Stok tidak mencukupi!');
        }
        $this->jumlah_stok -= $jumlah;
        $this->save();
        return $this;
    }

    public function isMenipis()
    {
        $minimum = $this->jenisGas->stok_minimum;
        return $this->jumlah_stok < $minimum;
    }

    public function getRekomendasiBeli()
    {
        $minimum = $this->jenisGas->stok_minimum;
        if ($this->jumlah_stok < $minimum) {
            return $minimum - $this->jumlah_stok;
        }
        return 0;
    }
}