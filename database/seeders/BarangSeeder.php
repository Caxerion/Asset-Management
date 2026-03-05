<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BarangSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        DB::table('barang')->insert([
            // ================= KAIN & LAP =================
            ['nama_barang' => 'Lap Handuk Biru', 'kategori_id' => 4, 'satuan_id' => 1, 'ukuran_id' => null],
            ['nama_barang' => 'Lap Handuk Merah', 'kategori_id' => 4, 'satuan_id' => 1, 'ukuran_id' => null],
            ['nama_barang' => 'Lap Majun', 'kategori_id' => 4, 'satuan_id' => 1, 'ukuran_id' => null],
            ['nama_barang' => 'Tissue Roll', 'kategori_id' => 4, 'satuan_id' => 3, 'ukuran_id' => null],
            ['nama_barang' => 'Tissue Towel', 'kategori_id' => 4, 'satuan_id' => 1, 'ukuran_id' => null],

            // ================= PERLENGKAPAN PROTEKSI =================
            ['nama_barang' => 'Sarung Tangan Karet', 'kategori_id' => 5, 'satuan_id' => 5, 'ukuran_id' => null],
            ['nama_barang' => 'Jas Hujan', 'kategori_id' => 5, 'satuan_id' => 1, 'ukuran_id' => null],

            // ================= PERALATAN & LAIN-LAIN =================
            ['nama_barang' => 'Wet Floor Sign', 'kategori_id' => 6, 'satuan_id' => 1, 'ukuran_id' => null],
            ['nama_barang' => 'Baterai AA Alkalin / ABC', 'kategori_id' => 6, 'satuan_id' => 1, 'ukuran_id' => null],
            ['nama_barang' => 'Batu Apung', 'kategori_id' => 6, 'satuan_id' => 1, 'ukuran_id' => null],

            // ================= PLASTIK & KEMASAN =================
            ['nama_barang' => 'Plastik Polibek Hitam 60x100', 'kategori_id' => 7, 'satuan_id' => 1, 'ukuran_id' => 2],
            ['nama_barang' => 'Plastik Polibek Hitam 90x120', 'kategori_id' => 7, 'satuan_id' => 1, 'ukuran_id' => 3],

            // ================= PRODUK PEMBERSIH =================
            ['nama_barang' => 'Handsoap', 'kategori_id' => 1, 'satuan_id' => 6, 'ukuran_id' => null],
            ['nama_barang' => 'Floor Cleaner', 'kategori_id' => 1, 'satuan_id' => 6, 'ukuran_id' => null],
            ['nama_barang' => 'Glass Cleaner', 'kategori_id' => 1, 'satuan_id' => 6, 'ukuran_id' => null],
            ['nama_barang' => 'Bowl Cleaner', 'kategori_id' => 1, 'satuan_id' => 6, 'ukuran_id' => null],
            ['nama_barang' => 'Carpet Shampoo', 'kategori_id' => 1, 'satuan_id' => 6, 'ukuran_id' => null],
            ['nama_barang' => 'Karbol', 'kategori_id' => 1, 'satuan_id' => 6, 'ukuran_id' => null],
            ['nama_barang' => 'Furniture Polish', 'kategori_id' => 1, 'satuan_id' => 6, 'ukuran_id' => null],
            ['nama_barang' => 'Detergent', 'kategori_id' => 1, 'satuan_id' => 2, 'ukuran_id' => null],
            ['nama_barang' => 'Sunlight', 'kategori_id' => 1, 'satuan_id' => 2, 'ukuran_id' => null],
            ['nama_barang' => 'Bubuk Pembersih PIM R29', 'kategori_id' => 1, 'satuan_id' => 2, 'ukuran_id' => null],

            // ================= PENGHARUM & PEWANGI =================
            ['nama_barang' => 'Pengharum Ruangan Stella/Glade', 'kategori_id' => 2, 'satuan_id' => 6, 'ukuran_id' => null],
            ['nama_barang' => 'Bay Fresh', 'kategori_id' => 2, 'satuan_id' => 6, 'ukuran_id' => null],
            ['nama_barang' => 'Stella Gantung', 'kategori_id' => 2, 'satuan_id' => 1, 'ukuran_id' => null],
            ['nama_barang' => 'Kamper Ball', 'kategori_id' => 2, 'satuan_id' => 1, 'ukuran_id' => null],
            ['nama_barang' => 'Meta Chame', 'kategori_id' => 2, 'satuan_id' => 1, 'ukuran_id' => null],
            ['nama_barang' => 'Lemon Pladge', 'kategori_id' => 2, 'satuan_id' => 6, 'ukuran_id' => null],

            // ================= ALAT KEBERSIHAN =================
            ['nama_barang' => 'Bottle Sprayer', 'kategori_id' => 3, 'satuan_id' => 1, 'ukuran_id' => null],
            ['nama_barang' => 'Tapas Hijau', 'kategori_id' => 3, 'satuan_id' => 1, 'ukuran_id' => null],
            ['nama_barang' => 'Dustpan Kaleng', 'kategori_id' => 3, 'satuan_id' => 1, 'ukuran_id' => null],
            ['nama_barang' => 'Dustpan', 'kategori_id' => 3, 'satuan_id' => 1, 'ukuran_id' => null],
            ['nama_barang' => 'Window Washer 35cm', 'kategori_id' => 3, 'satuan_id' => 1, 'ukuran_id' => 1],
            ['nama_barang' => 'Refill Window Washer 35cm', 'kategori_id' => 3, 'satuan_id' => 1, 'ukuran_id' => 1],
            ['nama_barang' => 'Window Squeegee 35cm', 'kategori_id' => 3, 'satuan_id' => 1, 'ukuran_id' => 1],
            ['nama_barang' => 'Refill Squeegee 35cm', 'kategori_id' => 3, 'satuan_id' => 1, 'ukuran_id' => 1],
            ['nama_barang' => 'Pad Holder', 'kategori_id' => 3, 'satuan_id' => 1, 'ukuran_id' => null],
            ['nama_barang' => 'Ragaball', 'kategori_id' => 3, 'satuan_id' => 1, 'ukuran_id' => null],
            ['nama_barang' => 'Refill Loby Duster', 'kategori_id' => 3, 'satuan_id' => 1, 'ukuran_id' => null],
            ['nama_barang' => 'Kain Mop Putih', 'kategori_id' => 3, 'satuan_id' => 1, 'ukuran_id' => null],
            ['nama_barang' => 'Kain Mop Biru', 'kategori_id' => 3, 'satuan_id' => 1, 'ukuran_id' => null],
            ['nama_barang' => 'Sikat Tangkai', 'kategori_id' => 3, 'satuan_id' => 1, 'ukuran_id' => null],
            ['nama_barang' => 'Kanebo', 'kategori_id' => 3, 'satuan_id' => 1, 'ukuran_id' => null],
            ['nama_barang' => 'Sapu Nilon', 'kategori_id' => 3, 'satuan_id' => 1, 'ukuran_id' => null],
            ['nama_barang' => 'Pad Merah', 'kategori_id' => 3, 'satuan_id' => 1, 'ukuran_id' => null],
            ['nama_barang' => 'Pad Putih', 'kategori_id' => 3, 'satuan_id' => 1, 'ukuran_id' => null],
        ]);

        // set metadata default
        DB::table('barang')->update([
            'created_by' => 1,
            'updated_by' => null,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
    }
}
