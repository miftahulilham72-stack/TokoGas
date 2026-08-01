<?php

namespace Tests\Unit;

use App\Http\Controllers\LaporanController;
use Tests\TestCase;

class LaporanControllerTest extends TestCase
{
    public function test_build_laba_data_uses_batch_profit_and_handles_missing_relations(): void
    {
        $penjualan = new \stdClass();
        $penjualan->tanggal_jual = '2026-08-01';
        $penjualan->tipe_pelanggan = 'retail';
        $detail = new \stdClass();
        $detail->harga_jual_saat_itu = 15000;
        $detail->jenisGas = null;
        $batch = new \stdClass();
        $batch->jumlah_diambil = 2;
        $batch->pembelianDetail = null;
        $detail->batches = [$batch];
        $penjualan->details = [$detail];

        $controller = new LaporanController();
        $method = new \ReflectionMethod($controller, 'buildLabaData');
        $method->setAccessible(true);

        $result = $method->invoke($controller, [$penjualan]);

        $this->assertSame(30000.0, $result['totalKeuntungan']);
        $this->assertCount(1, $result['detailLaba']);
        $this->assertSame(2.0, $result['detailLaba'][0]['jumlah']);
        $this->assertSame(15000.0, $result['detailLaba'][0]['harga_jual']);
        $this->assertSame(0.0, $result['detailLaba'][0]['harga_beli']);
        $this->assertSame(30000.0, $result['detailLaba'][0]['keuntungan']);
    }
}
