<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSizeSeeder extends Seeder
{
    public function run(): void
    {
        // Clear existing product_sizes
        DB::table('product_sizes')->delete();

        // First ensure all sizes exist
        $sizesData = [
            // Default size
            'Default',
            
            // 🧴 Produk Pembersih sizes
            '250 ml', '500 ml', '1 L', '5 L',
            '300 ml',
            '500 g', '1 kg', '2 kg',
            '250 ml', '400 ml', '800 ml',
            '500 g', '1 kg',

            // 🌸 Pengharum & Pewangi sizes
            '70 g', '150 g',
            '10 g', '20 g',
            'isi 10', 'isi 20',
            '100 g', '200 g',
            '300 ml',

            // 🧹 Alat Kebersihan sizes
            '25 cm', '35 cm', '45 cm',
            '16 inch', '20 inch',
            'Medium', 'Large',

            // 🪣 Kain & Lap sizes
            '30x30 cm', '40x60 cm',
            '50 m', '100 m',
            '150 items', '200 items',

            // 🧤 Perlengkapan Proteksi sizes
            'S', 'M', 'L',
            'M', 'L', 'XL',

            // 📦 Plastik & Kemasan sizes
            '60x100', '90x120',
        ];

        // Insert unique sizes
        $existingSizes = DB::table('sizes')->pluck('id', 'name');
        $sizeIds = [];

        foreach (array_unique($sizesData) as $sizeName) {
            if (!isset($existingSizes[$sizeName])) {
                $id = DB::table('sizes')->insertGetId([
                    'name' => $sizeName,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $sizeIds[$sizeName] = $id;
            } else {
                $sizeIds[$sizeName] = $existingSizes[$sizeName];
            }
        }

        // Now map products to their sizes
        $productSizes = [
            // 🧴 Produk Pembersih
            'Handsoap' => ['250 ml', '500 ml', '1 L'],
            'Floor Cleaner' => ['500 ml', '1 L', '5 L'],
            'Glass Cleaner' => ['500 ml', '1 L'],
            'Bowl Cleaner' => ['500 ml', '1 L'],
            'Carpet Shampoo' => ['1 L', '5 L'],
            'Karbol' => ['500 ml', '1 L', '5 L'],
            'Furniture Polish' => ['300 ml'],
            'Detergent' => ['500 g', '1 kg', '2 kg'],
            'Sunlight' => ['250 ml', '400 ml', '800 ml'],
            'Bubuk Pembersih PIM B29' => ['500 g', '1 kg'],

            // 🌸 Pengharum & Pewangi
            'Pengharum Ruangan (Stella/Glade)' => ['70 g', '150 g'],
            'Bay Fresh' => ['10 g', '20 g'],
            'Stella Gantung' => ['10 g', '20 g'],
            'Kamper Ball' => ['isi 10', 'isi 20'],
            'Meta Chame' => ['100 g', '200 g'],
            'Lemon Pladge' => ['300 ml'],

            // 🧹 Alat Kebersihan - Updated with proper sizes
            'Bottle Sprayer' => ['250 cm', '300 cm', '1 L'],
            'Tapas Hijau' => ['10x15 cm'],
            'Dustpan Kaleng' => ['20 cm', '30 cm'],
            'Dustpan' => ['Default'],
            'Window Washer 35cm' => ['25 cm', '35 cm', '45 cm'],
            'Refill Window Washer 35cm' => ['25 cm', '35 cm', '45 cm'],
            'Window Squeege 35cm' => ['25 cm', '35 cm', '45 cm'],
            'Refill Squeege 35cm' => ['25 cm', '35 cm', '45 cm'],
            'Pad Holder' => ['Default'],
            'Ragball' => ['Default'],
            'Refill Loby Duster' => ['Default'],
            'Sikat Tangkai' => ['Default'],
            'Kanebo' => ['Default'],
            'Sapu Nilon' => ['Default'],
            'Pad Merah' => ['16 inch', '20 inch'],
            'Pad Putih' => ['16 inch', '20 inch'],
            'Kain Mop Putih' => ['Medium', 'Large'],
            'Kain Mop Biru' => ['Medium', 'Large'],

            // 🪣 Kain & Lap
            'Lap Handuk Biru' => ['30x30 cm', '40x60 cm'],
            'Lap Handuk Merah' => ['30x30 cm', '40x60 cm'],
            'Lap Majun' => ['30x30 cm'],
            'Tissu Roll' => ['50 m', '100 m'],
            'Tissu Towel' => ['150 items', '200 items'],

            // 🧤 Perlengkapan Proteksi
            'Sarung Tangan Karet' => ['S', 'M', 'L'],
            'Jas Hujan' => ['M', 'L', 'XL'],

            // 📦 Plastik & Kemasan
            'Plastik Polibek Hitam 60x100' => ['60x100'],
            'Plastik Polibek Hitam 90x120' => ['90x120'],
        ];

        // Get all products
        $products = DB::table('products')->pluck('id', 'name');

        // Insert product_sizes pivot data
        $insertData = [];
        foreach ($productSizes as $productName => $sizeNames) {
            $productId = $products[$productName] ?? null;
            
            if (!$productId) {
                continue;
            }

            foreach ($sizeNames as $sizeName) {
                $sizeId = $sizeIds[$sizeName] ?? null;
                if ($sizeId) {
                    $insertData[] = [
                        'product_id' => $productId,
                        'size_id' => $sizeId,
                    ];
                }
            }
        }

        DB::table('product_sizes')->insert($insertData);

        $this->command->info('Product sizes seeded successfully!');
    }
}