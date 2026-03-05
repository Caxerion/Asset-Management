<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        DB::table('categories')->insert([
            ['name' => 'Produk Pembersih', 'is_active' => true],
            ['name' => 'Pengharum & Pewangi', 'is_active' => true],
            ['name' => 'Alat Kebersihan', 'is_active' => true],
            ['name' => 'Kain & Lap', 'is_active' => true],
            ['name' => 'Perlengkapan Proteksi', 'is_active' => true],
            ['name' => 'Perlatan & Lain-lain', 'is_active' => true], 
            ['name' => 'Plastik & Kemasan', 'is_active' => true],
        ]);
    }
}
