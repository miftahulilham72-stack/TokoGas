<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JenisGas;
use App\Models\Penjualan;
use App\Models\PenjualanDetail;
use App\Models\Stok;
use App\Models\HargaJual;
use App\Services\FIFOService;
use App\Http\Requests\PenjualanRequest;
use Carbon\Carbon;
use DB;

class PenjualanController extends Controller
{
    protected $fifoService;

    public function __construct(FIFOService $fifoService)
    {
        $this->fifoService = $fifoService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $penjualans = Penjualan::with(['details.jenisGas'])
            ->orderBy('tanggal_jual', 'desc')
            ->get();
        return view('penjualan.index', compact('penjualans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $jenisGases = JenisGas::with('stok')->get();
        $hargaJuals = HargaJual::with('jenisGas')->get();
        return view('penjualan.create', compact('jenisGases', 'hargaJuals'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PenjualanRequest $request)
    {
        DB::beginTransaction();

        try {
            // Create penjualan header
            $penjualan = Penjualan::create([
                'tipe_pelanggan' => $request->tipe_pelanggan,
                'tanggal_jual' => $request->tanggal_jual,
                'status_pembayaran' => $request->status_pembayaran,
                'nama_pelanggan' => $request->nama_pelanggan,
                'jatuh_tempo' => $request->jatuh_tempo,
                'total_harga' => 0
            ]);

            $totalHarga = 0;

            // Process each item
            foreach ($request->items as $item) {
                $subtotal = $item['jumlah'] * $item['harga_jual'];
                $totalHarga += $subtotal;

                // Cek stok
                $stok = Stok::where('jenis_gas_id', $item['jenis_gas_id'])->first();
                if (!$stok || $stok->jumlah_stok < $item['jumlah']) {
                    throw new \Exception('Stok untuk ' . $item['jenis_gas_id'] . ' tidak mencukupi!');
                }

                // Create penjualan detail
                $detail = PenjualanDetail::create([
                    'penjualan_id' => $penjualan->id,
                    'jenis_gas_id' => $item['jenis_gas_id'],
                    'jumlah_jual' => $item['jumlah'],
                    'harga_jual_saat_itu' => $item['harga_jual'],
                    'subtotal' => $subtotal
                ]);

                // FIFO: Ambil dari batch tertua - Gunakan service
                $this->fifoService->prosesFIFO($detail, $item['jenis_gas_id'], $item['jumlah']);
            }

            // Update total harga
            $penjualan->update(['total_harga' => $totalHarga]);

            DB::commit();

            $message = 'Penjualan berhasil dicatat!';
            if ($request->status_pembayaran == 'hutang') {
                $message .= ' Pelanggan ' . $request->nama_pelanggan . ' berhutang Rp ' . number_format($totalHarga, 0, ',', '.');
            }

            return redirect()->route('penjualan.index')
                ->with('success', $message);

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Get harga jual via AJAX
     */
    public function getHargaJual(Request $request)
    {
        $harga = HargaJual::where('jenis_gas_id', $request->jenis_gas_id)
            ->where('tipe_pelanggan', $request->tipe_pelanggan)
            ->first();

        return response()->json([
            'harga' => $harga ? $harga->harga_jual : 0
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Penjualan $penjualan)
    {
        DB::beginTransaction();

        try {
            // Kembalikan stok
            foreach ($penjualan->details as $detail) {
                $stok = Stok::where('jenis_gas_id', $detail->jenis_gas_id)->first();
                if ($stok) {
                    $stok->tambahStok($detail->jumlah_jual);
                }
                
                // Hapus batch FIFO
                $detail->batches()->delete();
            }

            // Hapus detail dan penjualan
            $penjualan->details()->delete();
            $penjualan->delete();

            DB::commit();

            return redirect()->route('penjualan.index')
                ->with('success', 'Penjualan berhasil dihapus! Stok dikembalikan.');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}