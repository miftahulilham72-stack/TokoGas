<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Agen;
use App\Models\JenisGas;
use App\Models\Pembelian;
use App\Models\PembelianDetail;
use App\Models\Stok;
use App\Models\HargaAgen;

class PembelianEditTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_update_purchase_and_recalculate_stock(): void
    {
        $agen = Agen::create(['nama' => 'Agen Test', 'no_hp' => '0812', 'alamat' => 'Alamat']);
        $jenisGas = JenisGas::create(['nama' => '3kg', 'stok_minimum' => 2]);

        $pembelian = Pembelian::create([
            'agen_id' => $agen->id,
            'tanggal_beli' => '2026-08-01',
            'no_faktur' => 'F-001',
            'total_harga' => 100000,
        ]);

        PembelianDetail::create([
            'pembelian_id' => $pembelian->id,
            'jenis_gas_id' => $jenisGas->id,
            'jumlah_beli' => 2,
            'harga_beli_saat_itu' => 50000,
            'sisa_stok' => 2,
        ]);

        Stok::create([
            'jenis_gas_id' => $jenisGas->id,
            'jumlah_stok' => 2,
        ]);

        HargaAgen::create([
            'agen_id' => $agen->id,
            'jenis_gas_id' => $jenisGas->id,
            'harga_beli' => 50000,
            'tanggal_berlaku' => '2026-08-01',
        ]);

        $response = $this->withSession(['is_logged_in' => true, 'role' => 'admin'])->put(route('pembelian.update', $pembelian), [
            'agen_id' => $agen->id,
            'tanggal_beli' => '2026-08-02',
            'no_faktur' => 'F-002',
            'items' => [[
                'jenis_gas_id' => $jenisGas->id,
                'jumlah' => 3,
                'harga_beli' => 45000,
            ]],
        ]);

        $response->assertRedirect(route('pembelian.index'));
        $response->assertSessionHas('success');

        $pembelian->refresh();
        $this->assertEquals(1, $pembelian->details()->count());
        $this->assertEquals(135000, $pembelian->total_harga);
        $this->assertEquals(3, $pembelian->details()->first()->jumlah_beli);

        $stok = Stok::where('jenis_gas_id', $jenisGas->id)->first();
        $this->assertNotNull($stok);
        $this->assertEquals(3, $stok->jumlah_stok);
    }
}
