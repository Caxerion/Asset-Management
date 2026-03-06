<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockBalance;
use App\Models\PickupLine;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Ringkasan data
        $totalProduk = Product::count();
        $totalStok   = StockBalance::sum('qty_on_hand');

        // Histori pengambilan terbaru (10 terakhir)
        $recentPickups = PickupLine::with([
                'pickup.user',   // relasi ke user
                'pickup.floor',  // relasi ke lantai
                'product'        // relasi ke barang
            ])
            ->latest()
            ->take(10)
            ->get();

        // Kirim data ke view dashboard
        return view('dashboard.index', compact(
            'totalProduk',
            'totalStok',
            'recentPickups'
        ));
    }
}