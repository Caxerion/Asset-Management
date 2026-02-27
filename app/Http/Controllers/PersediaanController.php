<?php

namespace App\Http\Controllers;

use App\Models\Pickup;
use App\Models\PickupLine;
use App\Models\Product;
use App\Models\Floor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PersediaanController extends Controller
{
    // Menampilkan histori pengambilan barang dan stock barang (same page)
    public function index(Request $request)
    {
        // Pickups query
        $pickupQuery = Pickup::with(['user', 'floor', 'items.product']);
        
        // Search filter for histori pengambilan
        if ($request->q) {
            $q = $request->q;
            $pickupQuery->where(function($builder) use ($q) {
                $builder->whereHas('user', fn($u) => $u->where('name', 'like', "%$q%"))
                        ->orWhereHas('floor', fn($f) => $f->where('name', 'like', "%$q%"))
                        ->orWhereHas('items.product', fn($p) => $p->where('name', 'like', "%$q%"));
            });
        }
        
        $pickups = $pickupQuery->latest()->paginate(10);

        // Products query
        $productQuery = Product::with(['category', 'stockBalance']);
        
        // Search filter for stock
        if ($request->q_stock) {
            $q = $request->q_stock;
            $productQuery->where('name', 'like', "%$q%")
                  ->orWhereHas('category', fn($c) => $c->where('name', 'like', "%$q%"));
        }
        
        $products = $productQuery->paginate(6);

        $users = \App\Models\User::all();
        $floors = Floor::all();
        
        // All products for the form in modal
        $allProducts = Product::with('stockBalance')->get();

        return view('persediaan.index', compact('pickups', 'products', 'users', 'floors', 'allProducts'));
    }

    // Form catat pengambilan barang
    public function create()
    {
        $products = Product::all();
        $floors   = Floor::all();
        $users    = \App\Models\User::all();

        return view('persediaan.create', compact('products', 'floors', 'users'));
    }

    // Simpan data pengambilan barang
    public function store(Request $request)
    {
        $request->validate([
            'floor_id' => 'required|exists:floors,id',
            'user_id'  => 'required|exists:users,id',
            'items'    => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.qty'        => 'required|integer|min:1',
        ]);

        // Filter out empty items
        $items = array_filter($request->items, function($item) {
            return !empty($item['product_id']) && !empty($item['qty']) && $item['qty'] > 0;
        });

        if (empty($items)) {
            return redirect()->back()->with('error', 'Pilih minimal satu barang.');
        }

        // Buat catatan pickup
        $pickup = Pickup::create([
            'requested_by' => $request->user_id,
            'floor_id'    => $request->floor_id,
            'pickup_no'   => 'PU-' . time(),
            'pickup_date' => now(),
            'notes'       => $request->notes,
        ]);

        // Loop setiap barang yang diambil
        foreach ($items as $item) {
            $product = Product::findOrFail($item['product_id']);

            // Catat detail pengambilan
            PickupLine::create([
                'pickup_id'  => $pickup->id,
                'product_id' => $product->id,
                'qty'        => $item['qty'],
            ]);
        }

        return redirect()->route('persediaan.index')
            ->with('success', 'Pengambilan barang berhasil dicatat.');
    }

    // Tampilkan detail pengambilan barang
    public function show($id)
    {
        $pickup = Pickup::with(['user', 'floor', 'items.product'])
            ->findOrFail($id);

        return view('persediaan.show', compact('pickup'));
    }
}
