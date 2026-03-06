<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FloorSeeder extends Seeder
{
    public function run(): void
    {
        // Disable foreign key checks to allow truncation of tables with FK constraints
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        
        // Clear existing floors first
        DB::table('floors')->truncate();
        
        DB::table('floors')->insert([
            ['name' => 'Mezanine'],
            ['name' => 'Lantai 1'],
            ['name' => 'Lantai 2'],
            ['name' => 'Lantai 3'],
        ]);
        
        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
}
