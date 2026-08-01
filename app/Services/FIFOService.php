<?php

namespace App\Services;

use App\Models\PembelianDetail;
use App\Models\PenjualanBatch;
use App\Models\Stok;
use Exception;

class FIFOService
{
    /**
     * Proses FIFO untuk pengambilan stok dari batch tertua
     */
    public function prosesFIFO($penjualanDetail, $jenisGasId, $jumlahJual)
    {
        // Ambil semua pembelian detail dengan sisa stok > 0, urut dari yang tertua
        $batches = PembelianDetail::where('jenis_gas_id', $jenisGasId)
            ->where('sisa_stok', '>', 0)
            ->orderBy('id', 'asc') // FIFO: yang pertama masuk
            ->get();

        if ($batches->isEmpty()) {
            throw new Exception('Tidak ada stok tersedia untuk jenis gas ini!');
        }

        $sisaJual = $jumlahJual;

        foreach ($batches as $batch) {
            if ($sisaJual <= 0) break;

            $ambil = min($batch->sisa_stok, $sisaJual);

            // Catat pengambilan di penjualan_batch
            PenjualanBatch::create([
                'penjualan_detail_id' => $penjualanDetail->id,
                'pembelian_detail_id' => $batch->id,
                'jumlah_diambil' => $ambil
            ]);

            // Kurangi sisa stok batch
            $batch->sisa_stok -= $ambil;
            $batch->save();

            $sisaJual -= $ambil;
        }

        if ($sisaJual > 0) {
            throw new Exception('Stok tidak mencukupi untuk FIFO! Sisa kurang: ' . $sisaJual);
        }

        // Kurangi stok utama
        $stok = Stok::where('jenis_gas_id', $jenisGasId)->first();
        if ($stok) {
            $stok->kurangiStok($jumlahJual);
        }

        return true;
    }

    /**
     * Hitung keuntungan dengan metode FIFO
     */
    public function hitungKeuntungan($penjualanId)
    {
        $batches = PenjualanBatch::with(['penjualanDetail', 'pembelianDetail'])
            ->whereHas('penjualanDetail', function($query) use ($penjualanId) {
                $query->where('penjualan_id', $penjualanId);
            })
            ->get();

        $totalKeuntungan = 0;

        foreach ($batches as $batch) {
            $hargaJual = $batch->penjualanDetail->harga_jual_saat_itu;
            $hargaBeli = $batch->pembelianDetail->harga_beli_saat_itu;
            $keuntungan = $batch->jumlah_diambil * ($hargaJual - $hargaBeli);
            $totalKeuntungan += $keuntungan;
        }

        return $totalKeuntungan;
    }

    /**
     * Get sisa stok per batch
     */
    public function getSisaStokBatch($jenisGasId)
    {
        return PembelianDetail::where('jenis_gas_id', $jenisGasId)
            ->where('sisa_stok', '>', 0)
            ->orderBy('id', 'asc')
            ->get();
    }

    /**
     * Cek ketersediaan stok untuk FIFO
     */
    public function cekKetersediaanStok($jenisGasId, $jumlah)
    {
        $totalStok = PembelianDetail::where('jenis_gas_id', $jenisGasId)
            ->where('sisa_stok', '>', 0)
            ->sum('sisa_stok');

        return $totalStok >= $jumlah;
    }
}