<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StockBalanceSeeder extends Seeder
{
    public function run(): void
    {
        // Clear existing stock balances first
        DB::table('stock_balances')->delete();
        
        // Get all products
        $products = DB::table('products')->get();
        
        // Add global stock (without floor) for each product
        foreach ($products as $product) {
            DB::table('stock_balances')->insert([
                'product_id' => $product->id,
                'floor_id' => null,  // Global stock, not tied to any floor
                'qty_on_hand' => rand(50, 200),  // Add random stock between 50-200
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        
        echo "Stock balances seeded with initial global stock!\n";
    }
}
