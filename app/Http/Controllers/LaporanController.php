<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JenisGas;
use App\Models\Penjualan;
use App\Models\Pembelian;
use App\Models\PenjualanBatch;
use App\Models\Stok;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class LaporanController extends Controller
{
    /**
     * Laporan Stok
     */
    public function stok()
    {
        $stokData = JenisGas::with('stok')->get();
        
        // Hitung rekomendasi beli
        $rekomendasi = [];
        $totalModal = 0;
        
        foreach ($stokData as $item) {
            $stok = $item->stok;
            if ($stok) {
                $butuh = $stok->getRekomendasiBeli();
                if ($butuh > 0) {
                    // Ambil harga beli terakhir
                    $hargaTerakhir = $item->pembelianDetails()
                        ->where('sisa_stok', '>', 0)
                        ->orderBy('id', 'desc')
                        ->first();
                    
                    $harga = $hargaTerakhir ? $hargaTerakhir->harga_beli_saat_itu : 0;
                    $totalModal += $butuh * $harga;
                    
                    $rekomendasi[] = [
                        'jenis_gas' => $item->nama,
                        'stok_sekarang' => $stok->jumlah_stok,
                        'stok_minimum' => $item->stok_minimum,
                        'butuh' => $butuh,
                        'harga' => $harga,
                        'total' => $butuh * $harga
                    ];
                }
            }
        }

        return view('laporan.stok', compact('stokData', 'rekomendasi', 'totalModal'));
    }

    /**
     * Laporan Keuntungan dengan FIFO
     */
    public function laba(Request $request)
    {
        $bulan = $request->bulan ?? Carbon::now()->month;
        $tahun = $request->tahun ?? Carbon::now()->year;

        $penjualans = Penjualan::whereMonth('tanggal_jual', $bulan)
            ->whereYear('tanggal_jual', $tahun)
            ->with(['details.batches.pembelianDetail', 'details.jenisGas'])
            ->get();

        $dataLaba = $this->buildLabaData($penjualans);

        return view('laporan.laba', array_merge($dataLaba, compact(
            'bulan',
            'tahun'
        )));
    }

    /**
     * Laporan Piutang
     */
    public function piutang()
    {
        $piutangs = Penjualan::where('status_pembayaran', 'hutang')
            ->whereHas('details')
            ->with(['details.jenisGas'])
            ->get()
            ->filter(function($item) {
                return $item->sisa_piutang > 0;
            });

        $totalPiutang = $piutangs->sum('sisa_piutang');

        return view('laporan.piutang', compact('piutangs', 'totalPiutang'));
    }

    /**
     * Cetak PDF Laporan Stok
     */
    public function cetakStokPDF()
    {
        $stokData = JenisGas::with('stok')->get();
        
        $pdf = Pdf::loadView('pdf.laporan_stok', compact('stokData'));
        return $pdf->download('laporan_stok_' . Carbon::now()->format('Y-m-d') . '.pdf');
    }

    /**
     * Cetak PDF Laporan Laba
     */
    public function cetakLabaPDF(Request $request)
    {
        $bulan = $request->bulan ?? Carbon::now()->month;
        $tahun = $request->tahun ?? Carbon::now()->year;

        $penjualans = Penjualan::whereMonth('tanggal_jual', $bulan)
            ->whereYear('tanggal_jual', $tahun)
            ->with(['details.batches.pembelianDetail', 'details.jenisGas'])
            ->get();

        $dataLaba = $this->buildLabaData($penjualans);

        $pdf = Pdf::loadView('pdf.laporan_laba', array_merge($dataLaba, compact('bulan', 'tahun')));
        return $pdf->download('laporan_laba_' . Carbon::now()->format('Y-m-d') . '.pdf');
    }

    protected function buildLabaData($penjualans)
    {
        $totalKeuntungan = 0;
        $detailLaba = [];
        $ringkasan = [];

        foreach ($penjualans as $penjualan) {
            $tanggal = $penjualan->tanggal_jual ? Carbon::parse($penjualan->tanggal_jual) : Carbon::now();

            foreach ($penjualan->details ?? [] as $detail) {
                $jenisGasNama = optional($detail->jenisGas)->nama ?? 'Tidak diketahui';

                foreach ($detail->batches ?? [] as $batch) {
                    $jumlahDiambil = (float) ($batch->jumlah_diambil ?? 0);
                    $hargaJual = (float) ($detail->harga_jual_saat_itu ?? 0);
                    $hargaBeli = (float) (optional($batch->pembelianDetail)->harga_beli_saat_itu ?? 0);
                    $keuntungan = $jumlahDiambil * ($hargaJual - $hargaBeli);

                    $totalKeuntungan += $keuntungan;

                    $detailLaba[] = [
                        'tanggal' => $tanggal,
                        'jenis_gas' => $jenisGasNama,
                        'tipe' => $penjualan->tipe_pelanggan ?? '-',
                        'jumlah' => $jumlahDiambil,
                        'harga_jual' => $hargaJual,
                        'harga_beli' => $hargaBeli,
                        'keuntungan' => $keuntungan
                    ];
                }
            }
        }

        foreach ($detailLaba as $item) {
            $key = $item['jenis_gas'];
            if (!isset($ringkasan[$key])) {
                $ringkasan[$key] = [
                    'total_jual' => 0,
                    'total_keuntungan' => 0
                ];
            }

            $ringkasan[$key]['total_jual'] += $item['jumlah'];
            $ringkasan[$key]['total_keuntungan'] += $item['keuntungan'];
        }

        return [
            'detailLaba' => $detailLaba,
            'ringkasan' => $ringkasan,
            'totalKeuntungan' => $totalKeuntungan,
        ];
    }
}