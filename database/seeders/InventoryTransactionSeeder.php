<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InventoryTransactionSeeder extends Seeder
{
    public function run(): void
    {
        // Get existing product and floor IDs
        $products = DB::table('products')->pluck('id');
        $floors = DB::table('floors')->pluck('id');
        
        if ($products->isEmpty() || $floors->isEmpty()) {
            return; // Skip if no products or floors
        }
        
        $product1 = $products->first();
        $product2 = $products->get(1, $products->first());
        $floor = $floors->first();
        
        DB::table('inventory_transactions')->insert([
            [
                'trans_at' => now(),
                'trans_type' => 'IN',
                'product_id' => $product1,
                'floor_id' => $floor,
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
                'product_id' => $product2,
                'floor_id' => $floor,
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
