<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SizeSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('sizes')->insert([
            [
                'name' => 'S',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'M',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'L',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'XL',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => '1/2 inch',
                'created_at' => now(),
                'updated_at' => now(),
            ],
// catatan jika ingin memperbarui ukuran lewat codingan silahkan tambahkan barang dibawah ini
[
    'name' => '250 ml',
    'created_at' => now(),
    'updated_at' => now(),
],
[
    'name' => '500 ml',
    'created_at' => now(),
    'updated_at' => now(),
],
[
    'name' => '1 L',
    'created_at' => now(),
    'updated_at' => now(),
],
[
    'name' => '5 L',
    'created_at' => now(),
    'updated_at' => now(),
],
[
    'name' => '300 ml',
    'created_at' => now(),
    'updated_at' => now(),
],
[
    'name' => '500 g',
    'created_at' => now(),
    'updated_at' => now(),
],
[
    'name' => '1 kg',
    'created_at' => now(),
    'updated_at' => now(),
],
[
    'name' => '2 kg',
    'created_at' => now(),
    'updated_at' => now(),
],
[
    'name' => '250 ml',
    'created_at' => now(),
    'updated_at' => now(),
],
[
    'name' => '400 ml',
    'created_at' => now(),
    'updated_at' => now(),
],
[
    'name' => '800 ml',
    'created_at' => now(),
    'updated_at' => now(),
],
[
    'name' => '70 g',
    'created_at' => now(),
    'updated_at' => now(),
],
[
    'name' => '150 g',
    'created_at' => now(),
    'updated_at' => now(),
],
[
    'name' => '10 g',
    'created_at' => now(),
    'updated_at' => now(),
],
[
    'name' => '20 g',
    'created_at' => now(),
    'updated_at' => now(),
],
[
    'name' => '10 items',
    'created_at' => now(),
    'updated_at' => now(),
],
[
    'name' => '20 items',
    'created_at' => now(),
    'updated_at' => now(),
],
[
    'name' => '100 g',
    'created_at' => now(),
    'updated_at' => now(),
],
[
    'name' => '200 g',
    'created_at' => now(),
    'updated_at' => now(),
],
[
    'name' => '25 cm',
    'created_at' => now(),
    'updated_at' => now(),
],
[
    'name' => '35 cm',
    'created_at' => now(),
    'updated_at' => now(),
],
[
    'name' => '45 cm',
    'created_at' => now(),
    'updated_at' => now(),
],
[
    'name' => '16 inch',
    'created_at' => now(),
    'updated_at' => now(),
],
[
    'name' => '20 inch',
    'created_at' => now(),
    'updated_at' => now(),
],
[
    'name' => '30x30 cm',
    'created_at' => now(),
    'updated_at' => now(),
],
[
    'name' => '40x60 cm',
    'created_at' => now(),
    'updated_at' => now(),
],
[
    'name' => '50 m',
    'created_at' => now(),
    'updated_at' => now(),
],
[
    'name' => '100 m',
    'created_at' => now(),
    'updated_at' => now(),
],
[
    'name' => '150 items',
    'created_at' => now(),
    'updated_at' => now(),
],
[
    'name' => '200 items',
    'created_at' => now(),
    'updated_at' => now(),
],
[
    'name' => '60x100',
    'created_at' => now(),
    'updated_at' => now(),
],
[
    'name' => '90x120',
    'created_at' => now(),
    'updated_at' => now(),
],

        ]);
    }
}

