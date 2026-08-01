<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JenisGasSeeder extends Seeder
{
    public function run(): void
    {
        $jenis = [
            ['nama' => '3kg', 'stok_minimum' => 30],
            ['nama' => '5kg (Blue Gas)', 'stok_minimum' => 2],
            ['nama' => '5.5kg (Pink Gas)', 'stok_minimum' => 2],
            ['nama' => '12kg', 'stok_minimum' => 2],
        ];

        DB::table('jenis_gas')->insert($jenis);
    }
}