<?php

namespace App\Http\Controllers;

use App\Models\Person;
use App\Models\Product;
use App\Models\StockBalance;
use App\Models\Pickup;
use App\Models\PickupLine;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Ringkasan data
        $totalProduk = Product::count();
        $avgStok = StockBalance::avg('qty_on_hand');
        $totalStok = StockBalance::sum('qty_on_hand');
        $totalPickup = Pickup::count();
        $totalUser = User::count();
        $totalPerson = Person::count(); // Assuming totalPerson is the same as totalUser

        // Histori pengambilan terbaru (paginated - 10 per page)
        $recentPickups = PickupLine::with([
                'pickup.user',   // relasi ke user
                'pickup.floor',  // relasi ke lantai
                'product'        // relasi ke barang
            ])
            ->latest()
            ->paginate(10);

        // Check if stats should be shown (for direct access via ?statistik)
        $showStats = $request->has('statistik');

        // Kirim data ke view dashboard
        return view('dashboard.index', compact(
            'totalProduk',
            'totalStok',
            'totalPickup',
            'totalUser',
            'totalPerson',
            'avgStok',
            'recentPickups',
            'showStats'
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