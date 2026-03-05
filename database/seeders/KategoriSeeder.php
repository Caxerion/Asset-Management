<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('kategori')->insert([
            [
            'nama_kategori' => 'Produk Pembersih',
            'created_by' => 1,
            'updated_by' => null,
            'created_at' => now(),
            'updated_at' => now(),
            ],

            [
            'nama_kategori' => 'Pengharum & Pewangi', 
            'created_by' => 1, 
            'updated_by'=> null,
            'created_at' => now(),
            'updated_at' => now(),
            ],

            [
            'nama_kategori' => 'Alat Kebersihan', 
            'created_by' => 1, 
            'updated_by' => null,
            'created_at' => now(),
            'updated_at' => now(),
            ],

            [
            'nama_kategori' => 'Kain & Lap', 
            'created_by' => 1, 
            'updated_by' => null,
            'created_at' => now(),
            'updated_at' => now(),
            ],

            [
            'nama_kategori' => 'Perlengkapan Proteksi', 
            'created_by' => 1, 
            'updated_by'=> null,
            'created_at' => now(),
            'updated_at' => now(),
            ],

            [
            'nama_kategori' => 'Peralatan & Lain-lain', 
            'created_by' => 1, 
            'updated_by'=> null,
            'created_at' => now(),
            'updated_at' => now(),
            ],

            [
            'nama_kategori' => 'Plastik & Kemasan', 
            'created_by' => 1, 
            'updated_by'=> null,
            'created_at' => now(),
            'updated_at' => now(),
            ],
        ]);
    }
}
