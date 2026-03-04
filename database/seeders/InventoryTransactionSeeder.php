<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InventoryTransactionSeeder extends Seeder
{
    public function run(): void
    {
        // Check if products exist first
        $productCount = DB::table('products')->count();
        if ($productCount < 2) {
            return; // Skip if not enough products
        }
        
        DB::table('inventory_transactions')->insert([
            [
                'trans_at' => now(),
                'trans_type' => 'IN',
                'product_id' => 1,
                'floor_id' => 1,
                'quantity' => 100,
                'unit_price' => 15000,
                'currency_code' => 'IDR',
                'notes' => 'Stok awal',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'trans_at' => now(),
                'trans_type' => 'IN',
                'product_id' => 2,
                'floor_id' => 1,
                'quantity' => 200,
                'unit_price' => 3000,
                'currency_code' => 'IDR',
                'notes' => 'Stok awal',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
