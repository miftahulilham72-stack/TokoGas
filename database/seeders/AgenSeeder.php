<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Agen;

class AgenSeeder extends Seeder
{
    public function run(): void
    {
        $agens = [
            ['nama' => 'Agen Gas Utama', 'no_hp' => '08123456789', 'alamat' => 'Jl. Raya No. 1'],
            ['nama' => 'Agen Gas Sejahtera', 'no_hp' => '08129876543', 'alamat' => 'Jl. Mawar No. 2'],
        ];

        foreach ($agens as $a) {
            Agen::create($a);
        }
    }
}