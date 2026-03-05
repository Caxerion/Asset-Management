<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UkuranSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        DB::table('ukuran')->insert([
            ['nama_ukuran' => '35 cm'],
            ['nama_ukuran' => '60 x 100'],
            ['nama_ukuran' => '90 x 120'],
            ['nama_ukuran' => '500 ml'],
            ['nama_ukuran' => '1 Liter'],
            ['nama_ukuran' => '5 Liter'],
        ]);

        DB::table('ukuran')->update([
            'created_by' => 1,
            'updated_by' => null,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
    }
}
