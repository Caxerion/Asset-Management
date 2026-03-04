<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // Reset auto increment agar ID dimulai dari 1
        DB::statement('ALTER TABLE products AUTO_INCREMENT = 1');
        
        // Hapus data terkait terlebih dahulu (menghindari foreign key error)
        DB::table('inventory_transactions')->delete();
        DB::table('receipt_lines')->delete();
        DB::table('pickup_lines')->delete();
        DB::table('stock_balances')->delete();
        DB::table('products')->delete();

        // Ambil size "Default", fallback ke id=1 kalau tidak ada
        $sizeId = DB::table('sizes')->where('name', 'Default')->value('id') ?? 1;

        // Ambil semua kategori (pastikan kategori sudah ada di tabel categories)
        $categories = DB::table('categories')->pluck('id', 'name');

        // Daftar produk per kategori dengan unit masing-masing
        $products = [

            // 🧴 Produk Pembersih
            'Produk Pembersih' => [
                ['name' => 'Handsoap', 'unit' => 'pcs'],
                ['name' => 'Floor Cleaner', 'unit' => 'pcs'],
                ['name' => 'Glass Cleaner', 'unit' => 'pcs'],
                ['name' => 'Bowl Cleaner', 'unit' => 'pcs'],
                ['name' => 'Carpet Shampoo', 'unit' => 'pcs'],
                ['name' => 'Karbol', 'unit' => 'pcs'],
                ['name' => 'Furniture Polish', 'unit' => 'pcs'],
                ['name' => 'Detergent', 'unit' => 'pcs'],
                ['name' => 'Sunlight', 'unit' => 'pcs'],
                ['name' => 'Bubuk Pembersih PIM B29', 'unit' => 'pcs'],
            ],

            // 🌸 Pengharum & Pewangi
            'Pengharum & Pewangi' => [
                ['name' => 'Pengharum Ruangan (Stella/Glade)', 'unit' => 'pcs'],
                ['name' => 'Bay Fresh', 'unit' => 'pcs'],
                ['name' => 'Stella Gantung', 'unit' => 'pcs'],
                ['name' => 'Kamper Ball', 'unit' => 'pcs'],
                ['name' => 'Meta Chame', 'unit' => 'pcs'],
                ['name' => 'Lemon Pladge', 'unit' => 'pcs'],
            ],

            // 🧹 Alat Kebersihan
            'Alat Kebersihan' => [
                ['name' => 'Bottle Sprayer', 'unit' => 'pcs'],
                ['name' => 'Tapas Hijau', 'unit' => 'pcs'],
                ['name' => 'Dustpan Kaleng', 'unit' => 'pcs'],
                ['name' => 'Dustpan', 'unit' => 'pcs'],
                ['name' => 'Window Washer 35cm', 'unit' => 'pcs'],
                ['name' => 'Refill Window Washer 35cm', 'unit' => 'pcs'],
                ['name' => 'Window Squeege 35cm', 'unit' => 'pcs'],
                ['name' => 'Refill Squeege 35cm', 'unit' => 'pcs'],
                ['name' => 'Pad Holder', 'unit' => 'pcs'],
                ['name' => 'Ragball', 'unit' => 'pcs'],
                ['name' => 'Refill Loby Duster', 'unit' => 'pcs'],
                ['name' => 'Kain Mop Putih', 'unit' => 'pcs'],
                ['name' => 'Kain Mop Biru', 'unit' => 'pcs'],
                ['name' => 'Sikat Tangkai', 'unit' => 'pcs'],
                ['name' => 'Kanebo', 'unit' => 'pcs'],
                ['name' => 'Sapu Nilon', 'unit' => 'pcs'],
                ['name' => 'Pad Merah', 'unit' => 'pcs'],
                ['name' => 'Pad Putih', 'unit' => 'pcs'],
            ],

            // 🪣 Kain & Lap
            'Kain & Lap' => [
                ['name' => 'Lap Handuk Biru', 'unit' => 'pcs'],
                ['name' => 'Lap Handuk Merah', 'unit' => 'pcs'],
                ['name' => 'Lap Majun', 'unit' => 'pcs'],
                ['name' => 'Tissu Roll', 'unit' => 'pcs'],
                ['name' => 'Tissu Towel', 'unit' => 'pcs'],
            ],

            // 🧤 Perlengkapan Proteksi
            'Perlengkapan Proteksi' => [
                ['name' => 'Sarung Tangan Karet', 'unit' => 'pcs'],
                ['name' => 'Jas Hujan', 'unit' => 'pcs'],
            ],

            // ⚡ Peralatan & Lain-lain
            'Peralatan & Lain-lain' => [
                ['name' => 'Wet Floor Sign', 'unit' => 'pcs'],
                ['name' => 'Batrai A2 Alkalin / ABC', 'unit' => 'pcs'],
                ['name' => 'Batu Apung', 'unit' => 'pcs'],
            ],

            // 📦 Plastik & Kemasan
            'Plastik & Kemasan' => [
                ['name' => 'Plastik Polibek Hitam 60x100', 'unit' => 'pcs'],
                ['name' => 'Plastik Polibek Hitam 90x120', 'unit' => 'pcs'],
            ],
        ];

        $insertData = [];
        $skuCounter = 1;

        foreach ($products as $categoryName => $items) {
            $categoryId = $categories[$categoryName] ?? null;

            // Lewati jika kategori tidak ditemukan
            if (!$categoryId) {
                continue;
            }

            foreach ($items as $product) {
                $insertData[] = [
                    'sku' => 'PRD-' . str_pad($skuCounter++, 4, '0', STR_PAD_LEFT),
                    'name' => $product['name'],
                    'category_id' => $categoryId,
                    'size_id' => $sizeId,
                    'unit' => $product['unit'],
                    'min_stock' => 10,
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        // Insert semua data produk
        DB::table('products')->insert($insertData);
    }
}
