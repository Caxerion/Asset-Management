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
        $totalProduk = (int) Product::count();
        $avgStok = (int) StockBalance::avg('qty_on_hand');
        $totalStok = (int) StockBalance::sum('qty_on_hand');
        $totalPickup = (int) Pickup::count();
        $totalUser = User::count();
        $totalPerson = Person::count(); // Assuming totalPerson is the same as totalUser

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

// Get pickups by floor for bar chart
public function getPickupsByFloor(Request $request)
{
    $startDate = $request->get('start_date');
    $endDate = $request->get('end_date');
    
    $query = Pickup::query()->whereNotNull('floor_id');
    
    if ($startDate && $endDate) {
        $query->whereBetween('pickup_date', [$startDate, $endDate]);
    }
    
    $pickups = $query->with('floor')->get();
    
    // Define the floor order
    $floorOrder = ['Mezanine', 'Lantai 1', 'Lantai 2', 'Lantai 3'];
    
    // Group by floor name manually to avoid null issues
    $grouped = [];
    foreach ($pickups as $pickup) {
        if ($pickup->floor && $pickup->floor->name) {
            $floorName = $pickup->floor->name;
            if (!isset($grouped[$floorName])) {
                $grouped[$floorName] = 0;
            }
            $grouped[$floorName]++;
        }
    }
    
    // Sort by floor order, then get labels and values
    $sortedLabels = [];
    $sortedValues = [];
    
    foreach ($floorOrder as $floor) {
        $sortedLabels[] = $floor;
        $sortedValues[] = $grouped[$floor] ?? 0;
    }
    
    // Add any floors not in the predefined order
    foreach ($grouped as $floor => $count) {
        if (!in_array($floor, $floorOrder)) {
            $sortedLabels[] = $floor;
            $sortedValues[] = $count;
        }
    }
    
    return response()->json([
        'labels' => $sortedLabels,
        'values' => $sortedValues
    ]);
}

}