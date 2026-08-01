<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JenisGas;
use App\Models\Penjualan;
use App\Models\Pembelian;
use App\Models\PenjualanBatch;
use App\Models\Stok;
use Carbon\Carbon;
use PDF;
use DB;

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
     * Laporan Keuntungan per Hari dengan FIFO
     */
    public function laba(Request $request)
    {
        $bulan = $request->bulan ?? Carbon::now()->month;
        $tahun = $request->tahun ?? Carbon::now()->year;
        $tanggal = $request->tanggal;

        // Ambil semua penjualan
        $query = Penjualan::with(['details.batches.pembelianDetail', 'details.jenisGas'])
            ->whereMonth('tanggal_jual', $bulan)
            ->whereYear('tanggal_jual', $tahun);

        // Filter jika ada tanggal spesifik
        if ($tanggal) {
            $query->whereDate('tanggal_jual', $tanggal);
        }

        $penjualans = $query->get();

        // Data per hari
        $labaPerHari = [];
        $totalKeuntungan = 0;
        $detailLaba = [];

        foreach ($penjualans as $penjualan) {
            $tanggalKey = $penjualan->tanggal_jual->format('Y-m-d');
            
            if (!isset($labaPerHari[$tanggalKey])) {
                $labaPerHari[$tanggalKey] = [
                    'tanggal' => $penjualan->tanggal_jual,
                    'total_penjualan' => 0,
                    'total_pembelian' => 0,
                    'keuntungan' => 0,
                    'detail' => []
                ];
            }

            foreach ($penjualan->details as $detail) {
                foreach ($detail->batches as $batch) {
                    $hargaJual = $detail->harga_jual_saat_itu;
                    $hargaBeli = $batch->pembelianDetail->harga_beli_saat_itu;
                    $keuntungan = $batch->jumlah_diambil * ($hargaJual - $hargaBeli);
                    
                    $labaPerHari[$tanggalKey]['total_penjualan'] += ($batch->jumlah_diambil * $hargaJual);
                    $labaPerHari[$tanggalKey]['total_pembelian'] += ($batch->jumlah_diambil * $hargaBeli);
                    $labaPerHari[$tanggalKey]['keuntungan'] += $keuntungan;
                    $totalKeuntungan += $keuntungan;

                    $detailLaba[] = [
                        'tanggal' => $penjualan->tanggal_jual,
                        'jenis_gas' => $detail->jenisGas->nama,
                        'tipe' => $penjualan->tipe_pelanggan,
                        'jumlah' => $batch->jumlah_diambil,
                        'harga_jual' => $hargaJual,
                        'harga_beli' => $hargaBeli,
                        'keuntungan' => $keuntungan
                    ];
                }
            }
        }

        // Urutkan berdasarkan tanggal
        ksort($labaPerHari);

        // Ringkasan per jenis gas
        $ringkasan = [];
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

        return view('laporan.laba', compact(
            'labaPerHari',
            'detailLaba',
            'ringkasan',
            'totalKeuntungan',
            'bulan',
            'tahun',
            'tanggal'
        ));
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
        
        $pdf = PDF::loadView('pdf.laporan_stok', compact('stokData'));
        return $pdf->download('laporan_stok_' . Carbon::now()->format('Y-m-d') . '.pdf');
    }

    /**
     * Cetak PDF Laporan Laba per Hari
     */
    public function cetakLabaPDF(Request $request)
    {
        $bulan = $request->bulan ?? Carbon::now()->month;
        $tahun = $request->tahun ?? Carbon::now()->year;
        $tanggal = $request->tanggal;

        $query = Penjualan::with(['details.batches.pembelianDetail', 'details.jenisGas'])
            ->whereMonth('tanggal_jual', $bulan)
            ->whereYear('tanggal_jual', $tahun);

        if ($tanggal) {
            $query->whereDate('tanggal_jual', $tanggal);
        }

        $penjualans = $query->get();

        $labaPerHari = [];
        $totalKeuntungan = 0;
        $detailLaba = [];

        foreach ($penjualans as $penjualan) {
            $tanggalKey = $penjualan->tanggal_jual->format('Y-m-d');
            
            if (!isset($labaPerHari[$tanggalKey])) {
                $labaPerHari[$tanggalKey] = [
                    'tanggal' => $penjualan->tanggal_jual,
                    'total_penjualan' => 0,
                    'total_pembelian' => 0,
                    'keuntungan' => 0,
                    'detail' => []
                ];
            }

            foreach ($penjualan->details as $detail) {
                foreach ($detail->batches as $batch) {
                    $hargaJual = $detail->harga_jual_saat_itu;
                    $hargaBeli = $batch->pembelianDetail->harga_beli_saat_itu;
                    $keuntungan = $batch->jumlah_diambil * ($hargaJual - $hargaBeli);
                    
                    $labaPerHari[$tanggalKey]['total_penjualan'] += ($batch->jumlah_diambil * $hargaJual);
                    $labaPerHari[$tanggalKey]['total_pembelian'] += ($batch->jumlah_diambil * $hargaBeli);
                    $labaPerHari[$tanggalKey]['keuntungan'] += $keuntungan;
                    $totalKeuntungan += $keuntungan;

                    $detailLaba[] = [
                        'tanggal' => $penjualan->tanggal_jual,
                        'jenis_gas' => $detail->jenisGas->nama,
                        'tipe' => $penjualan->tipe_pelanggan,
                        'jumlah' => $batch->jumlah_diambil,
                        'harga_jual' => $hargaJual,
                        'harga_beli' => $hargaBeli,
                        'keuntungan' => $keuntungan
                    ];
                }
            }
        }

        ksort($labaPerHari);

        $ringkasan = [];
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

        $pdf = PDF::loadView('pdf.laporan_laba', compact(
            'labaPerHari', 
            'detailLaba', 
            'ringkasan', 
            'totalKeuntungan', 
            'bulan', 
            'tahun',
            'tanggal'
        ));
        
        $namaFile = 'laporan_laba_' . ($tanggal ?? $bulan . '-' . $tahun) . '.pdf';
        return $pdf->download($namaFile);
    }
}