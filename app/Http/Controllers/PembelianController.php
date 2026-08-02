<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Agen;
use App\Models\JenisGas;
use App\Models\Pembelian;
use App\Models\PembelianDetail;
use App\Models\Stok;
use App\Models\HargaAgen;
use App\Http\Requests\PembelianRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PembelianController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pembelians = Pembelian::with(['agen', 'details.jenisGas'])->orderBy('tanggal_beli', 'desc')->get();
        return view('pembelian.index', compact('pembelians'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $agens = Agen::all();
        $jenisGases = JenisGas::all();
        return view('pembelian.create', compact('agens', 'jenisGases'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PembelianRequest $request)
    {
        DB::beginTransaction();

        try {
            // Create pembelian header
            $pembelian = Pembelian::create([
                'agen_id' => $request->agen_id,
                'tanggal_beli' => $request->tanggal_beli,
                'no_faktur' => $request->no_faktur,
                'total_harga' => 0 // akan diupdate nanti
            ]);

            $totalHarga = 0;

            // Process each item
            foreach ($request->items as $item) {
                $subtotal = $item['jumlah'] * $item['harga_beli'];
                $totalHarga += $subtotal;

                // Create pembelian detail (for FIFO)
                $detail = PembelianDetail::create([
                    'pembelian_id' => $pembelian->id,
                    'jenis_gas_id' => $item['jenis_gas_id'],
                    'jumlah_beli' => $item['jumlah'],
                    'harga_beli_saat_itu' => $item['harga_beli'],
                    'sisa_stok' => $item['jumlah'] // initial sisa_stok = jumlah beli
                ]);

                // Update or create stok
                $stok = Stok::where('jenis_gas_id', $item['jenis_gas_id'])->first();
                if ($stok) {
                    $stok->tambahStok($item['jumlah']);
                } else {
                    Stok::create([
                        'jenis_gas_id' => $item['jenis_gas_id'],
                        'jumlah_stok' => $item['jumlah']
                    ]);
                }

                // Save harga agen history
                HargaAgen::create([
                    'agen_id' => $request->agen_id,
                    'jenis_gas_id' => $item['jenis_gas_id'],
                    'harga_beli' => $item['harga_beli'],
                    'tanggal_berlaku' => $request->tanggal_beli
                ]);
            }

            // Update total harga
            $pembelian->update(['total_harga' => $totalHarga]);

            DB::commit();

            return redirect()->route('pembelian.index')
                ->with('success', 'Pembelian berhasil dicatat! Stok telah ditambahkan.');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pembelian $pembelian)
    {
        $pembelian->load(['agen', 'details.jenisGas']);
        $agens = Agen::all();
        $jenisGases = JenisGas::all();

        return view('pembelian.edit', compact('pembelian', 'agens', 'jenisGases'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pembelian $pembelian)
    {
        $request->validate([
            'agen_id' => 'required|exists:agens,id',
            'tanggal_beli' => 'required|date',
            'no_faktur' => 'nullable|string|max:255',
            'items' => 'required|array|min:1',
            'items.*.jenis_gas_id' => 'required|exists:jenis_gas,id',
            'items.*.jumlah' => 'required|integer|min:1',
            'items.*.harga_beli' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();

        try {
            foreach ($pembelian->details as $detail) {
                $stok = Stok::where('jenis_gas_id', $detail->jenis_gas_id)->first();
                if ($stok) {
                    $stok->kurangiStok($detail->jumlah_beli);
                }
            }

            $pembelian->details()->delete();

            $pembelian->update([
                'agen_id' => $request->agen_id,
                'tanggal_beli' => $request->tanggal_beli,
                'no_faktur' => $request->no_faktur,
                'total_harga' => 0,
            ]);

            $totalHarga = 0;

            foreach ($request->items as $item) {
                $subtotal = $item['jumlah'] * $item['harga_beli'];
                $totalHarga += $subtotal;

                PembelianDetail::create([
                    'pembelian_id' => $pembelian->id,
                    'jenis_gas_id' => $item['jenis_gas_id'],
                    'jumlah_beli' => $item['jumlah'],
                    'harga_beli_saat_itu' => $item['harga_beli'],
                    'sisa_stok' => $item['jumlah'],
                ]);

                $stok = Stok::where('jenis_gas_id', $item['jenis_gas_id'])->first();
                if ($stok) {
                    $stok->tambahStok($item['jumlah']);
                } else {
                    Stok::create([
                        'jenis_gas_id' => $item['jenis_gas_id'],
                        'jumlah_stok' => $item['jumlah'],
                    ]);
                }

                HargaAgen::updateOrCreate(
                    [
                        'agen_id' => $request->agen_id,
                        'jenis_gas_id' => $item['jenis_gas_id'],
                    ],
                    [
                        'harga_beli' => $item['harga_beli'],
                        'tanggal_berlaku' => $request->tanggal_beli,
                    ]
                );
            }

            $pembelian->update(['total_harga' => $totalHarga]);

            DB::commit();

            return redirect()->route('pembelian.index')
                ->with('success', 'Pembelian berhasil diupdate!');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Pembelian $pembelian)
    {
        $pembelian->load(['agen', 'details.jenisGas']);
        return view('pembelian.show', compact('pembelian'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pembelian $pembelian)
    {
        DB::beginTransaction();

        try {
            // Restore stok
            foreach ($pembelian->details as $detail) {
                $stok = Stok::where('jenis_gas_id', $detail->jenis_gas_id)->first();
                if ($stok) {
                    $stok->kurangiStok($detail->jumlah_beli);
                }
            }

            // Delete details and pembelian
            $pembelian->details()->delete();
            $pembelian->delete();

            DB::commit();

            return redirect()->route('pembelian.index')
                ->with('success', 'Pembelian berhasil dihapus! Stok telah dikembalikan.');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Get harga agen terbaru via AJAX
     */
    public function getHargaAgen(Request $request)
    {
        $harga = HargaAgen::where('agen_id', $request->agen_id)
            ->where('jenis_gas_id', $request->jenis_gas_id)
            ->orderBy('tanggal_berlaku', 'desc')
            ->first();

        return response()->json([
            'harga' => $harga ? $harga->harga_beli : 0
        ]);
    }
}