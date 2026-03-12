<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockBalance;
use App\Models\Pickup;
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

    public function getPickupsByCategory()
{
    $data = PickupLine::with('product.category')
        ->get()
        ->groupBy('product.category.name')
        ->map(fn($items) => $items->count());
    
    return response()->json([
        'labels' => $data->keys(),
        'values' => $data->values()
    ]);
}
public function getPickupsByPeriod(Request $request)
{
    $period = $request->get('period', 'monthly'); // monthly, weekly, daily
    
    $query = Pickup::query();
    
    switch ($period) {
        case 'daily':
            $data = $query->selectRaw('DATE(pickup_date) as period, COUNT(*) as total')
                ->groupBy('period')
                ->orderBy('period')
                ->get();
            break;
        case 'weekly':
            $data = $query->selectRaw('YEARWEEK(pickup_date) as period, COUNT(*) as total')
                ->groupBy('period')
                ->orderBy('period')
                ->get();
            break;
        default: // monthly
            $data = $query->selectRaw('DATE_FORMAT(pickup_date, "%Y-%m") as period, COUNT(*) as total')
                ->groupBy('period')
                ->orderBy('period')
                ->get();
    }
    
    return response()->json([
        'labels' => $data->pluck('period'),
        'values' => $data->pluck('total')
    ]);
}

}