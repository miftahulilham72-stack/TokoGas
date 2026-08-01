<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Penjualan;
use App\Models\PembayaranPiutang;
use App\Http\Requests\PiutangRequest;
use Carbon\Carbon;
use DB;

class PiutangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $piutangs = Penjualan::where('status_pembayaran', 'hutang')
            ->with(['details.jenisGas'])
            ->get()
            ->filter(function($item) {
                return $item->sisa_piutang > 0;
            });

        return view('piutang.index', compact('piutangs'));
    }

    /**
     * Show the form for paying a debt.
     */
    public function bayar($id)
    {
        $penjualan = Penjualan::findOrFail($id);
        $sisaPiutang = $penjualan->sisa_piutang;
        
        return view('piutang.bayar', compact('penjualan', 'sisaPiutang'));
    }

    /**
     * Process debt payment.
     */
    public function prosesBayar(PiutangRequest $request, $id)
    {
        $penjualan = Penjualan::findOrFail($id);
        $sisaPiutang = $penjualan->sisa_piutang;

        if ($request->jumlah_bayar > $sisaPiutang) {
            return redirect()->back()
                ->with('error', 'Jumlah bayar melebihi sisa piutang!');
        }

        DB::beginTransaction();

        try {
            // Catat pembayaran
            PembayaranPiutang::create([
                'penjualan_id' => $id,
                'jumlah_bayar' => $request->jumlah_bayar,
                'tanggal_bayar' => $request->tanggal_bayar
            ]);

            // Update status penjualan jika lunas
            $sisaBaru = $penjualan->sisa_piutang;
            if ($sisaBaru <= 0) {
                $penjualan->update(['status_pembayaran' => 'lunas']);
            }

            DB::commit();

            $message = 'Pembayaran piutang berhasil!';
            if ($sisaBaru > 0) {
                $message .= ' Sisa piutang: Rp ' . number_format($sisaBaru, 0, ',', '.');
            } else {
                $message .= ' Piutang sudah lunas!';
            }

            return redirect()->route('piutang.index')
                ->with('success', $message);

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Get detail piutang via AJAX
     */
    public function getDetail($id)
    {
        $penjualan = Penjualan::with(['details.jenisGas'])->findOrFail($id);
        $sisaPiutang = $penjualan->sisa_piutang;

        return response()->json([
            'penjualan' => $penjualan,
            'sisa_piutang' => $sisaPiutang
        ]);
    }
}