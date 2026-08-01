<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JenisGas;
use App\Models\Stok;
use App\Models\Penjualan;
use App\Models\Pembelian;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Data stok semua jenis gas
        $stokData = JenisGas::with('stok')->get();
        
        // Total stok keseluruhan
        $totalStok = $stokData->sum(function($item) {
            return $item->stok ? $item->stok->jumlah_stok : 0;
        });

        // Penjualan hari ini
        $penjualanHariIni = Penjualan::whereDate('tanggal_jual', Carbon::today())->sum('total_harga');
        
        // Penjualan minggu ini
        $penjualanMingguIni = Penjualan::whereBetween('tanggal_jual', [
            Carbon::now()->startOfWeek(), 
            Carbon::now()->endOfWeek()
        ])->sum('total_harga');

        // Total piutang
        $totalPiutang = Penjualan::where('status_pembayaran', 'hutang')
            ->get()
            ->sum(function($item) {
                return $item->sisa_piutang;
            });

        // Notifikasi stok menipis
        $stokMenipis = $stokData->filter(function($item) {
            return $item->stok && $item->stok->isMenipis();
        });

        // Jumlah penjualan hari ini (tabung)
        $jumlahPenjualanHariIni = Penjualan::whereDate('tanggal_jual', Carbon::today())
            ->with('details')
            ->get()
            ->sum(function($item) {
                return $item->details->sum('jumlah_jual');
            });

        return view('dashboard.index', compact(
            'stokData',
            'totalStok',
            'penjualanHariIni',
            'penjualanMingguIni',
            'totalPiutang',
            'stokMenipis',
            'jumlahPenjualanHariIni'
        ));
    }
}