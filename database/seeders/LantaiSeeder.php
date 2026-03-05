<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LantaiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('lantai')->insert([
            [
            'nama_lantai' => 'Mezanine',
            'nama_gedung' => 'Graha RE 1',
            'pic_user_id' => 1,
            'created_by' => 1,
            'updated_by' => null,
            'created_at' => now(),
            'updated_at' => now(),
            ],

            [
            'nama_lantai' => 'Lantai 1',
            'nama_gedung' => 'Graha RE 1',
            'pic_user_id' => 1,
            'created_by' => 1,
            'updated_by' => null,
            'created_at' => now(),
            'updated_at' => now(),
            ],

            [
            'nama_lantai' => 'Lantai 2',
            'nama_gedung' => 'Graha RE 1',
            'pic_user_id' => 1,
            'created_by' => 1,
            'updated_by' => null,
            'created_at' => now(),
            'updated_at' => now(),
            ],

            [
            'nama_lantai' => 'Lantai 3',
            'nama_gedung' => 'Graha RE 1',
            'pic_user_id' => 1,
            'created_by' => 1,
            'updated_by' => null,
            'created_at' => now(),
            'updated_at' => now(),
            ],
        ]);
    }
}
