<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            CategorySeeder::class,
            SizeSeeder::class,
            FloorSeeder::class,
            UserSeeder::class,
            ProductSeeder::class,
            RoleSeeder::class,
            StockBalanceSeeder::class,
            PickupSeeder::class,
            InventoryTransactionSeeder::class,
        ]);

        $this->call([
            UserSeeder::class,
            KategoriSeeder::class,
            SatuanSeeder::class,
            UkuranSeeder::class,
            BarangSeeder::class,
            LantaiSeeder::class,
        ]);
    }
}