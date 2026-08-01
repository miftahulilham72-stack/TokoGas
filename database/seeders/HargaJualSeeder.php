<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\JenisGas;
use App\Models\HargaJual;

class HargaJualSeeder extends Seeder
{
    public function run(): void
    {
        $harga = [
            [
                'nama' => '3kg', 
                'toko' => 17500, 
                'rumahan' => 20000
            ],
            [
                'nama' => '5kg (Blue Gas)', 
                'toko' => 155000, 
                'rumahan' => 170000
            ],
            [
                'nama' => '5.5kg (Pink Gas)', 
                'toko' => 155000, 
                'rumahan' => 170000
            ],
            [
                'nama' => '12kg', 
                'toko' => 240000, 
                'rumahan' => 250000
            ],
        ];

        foreach ($harga as $h) {
            $jenis = JenisGas::where('nama', $h['nama'])->first();
            
            if ($jenis) {
                // Harga ke Toko
                HargaJual::updateOrCreate(
                    [
                        'jenis_gas_id' => $jenis->id,
                        'tipe_pelanggan' => 'toko',
                    ],
                    ['harga_jual' => $h['toko']]
                );
                
                // Harga ke Rumahan
                HargaJual::updateOrCreate(
                    [
                        'jenis_gas_id' => $jenis->id,
                        'tipe_pelanggan' => 'rumahan',
                    ],
                    ['harga_jual' => $h['rumahan']]
                );
            }
        }
    }
}