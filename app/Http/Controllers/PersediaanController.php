<?php

namespace App\Http\Controllers;

use App\Models\Pickup;
use App\Models\PickupLine;
use App\Models\Product;
use App\Models\Floor;
use App\Models\Person;
use App\Models\InventoryTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PersediaanController extends Controller
{
    // Menampilkan histori pengambilan barang dan stock barang (same page)
    public function index(Request $request)
    {   
        // Get sorting parameters
        $sortField = $request->get('sort', 'id');
        $sortDirection = $request->get('direction', 'desc');
        
        if (!in_array($sortDirection, ['asc', 'desc'])) {
            $sortDirection = 'desc';
        }

        // Products query
        $productQuery = Product::with(['category', 'stockBalance']);
        // Pickups query - ensure items relationship is always eager loaded with product
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
        
        // Apply sorting - ensure sortField is allowed
        $allowedSortFields = ['id', 'created_at', 'updated_at', 'requested_by', 'floor_id', 'items_count'];
        if (!in_array($sortField, $allowedSortFields)) {
            $sortField = 'id';
        }
        
        // Handle sorting by user name (requires join or orderBy on relationship)
        if ($sortField === 'requested_by') {
            $pickups = $pickupQuery->select('pickups.*')
                ->join('users', 'pickups.requested_by', '=', 'users.id')
                ->orderBy('users.name', $sortDirection)
                ->paginate(10);
        } elseif ($sortField === 'floor_id') {
            $pickups = $pickupQuery->select('pickups.*')
                ->join('floors', 'pickups.floor_id', '=', 'floors.id')
                ->orderBy('floors.name', $sortDirection)
                ->paginate(10);
        } elseif ($sortField === 'items_count') {
            $pickups = $pickupQuery->select('pickups.*')
                ->leftJoin('pickup_lines', 'pickups.id', '=', 'pickup_lines.pickup_id')
                ->groupBy('pickups.id')
                ->orderByRaw('COUNT(pickup_lines.id) ' . $sortDirection)
                ->paginate(10);
        } else {
            $pickups = $pickupQuery->orderBy($sortField, $sortDirection)->paginate(10);
        }
        
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

        $users = Person::where('is_active', true)->get();
        $floors = Floor::all();
        
        // All products for the form in modal - eager load relationships and append stock_balance
        $allProducts = Product::with(['category', 'size', 'stockBalances'])->get();

        return view('persediaan.index', compact('pickups', 'products', 'users', 'floors', 'allProducts', 'sortField', 'sortDirection'));
    }

    // Form catat pengambilan barang
    public function create()
    {
        $products   = Product::with(['category', 'size', 'stockBalances'])->get();
        $floors     = Floor::all();
        $users      = Person::where('is_active', true)->get();
        $categories = \App\Models\Category::all();
        $sizes      = \App\Models\Size::all();

        return view('persediaan.create', compact('products', 'floors', 'users', 'categories', 'sizes'));
    }

    // Simpan data pengambilan barang
    public function store(Request $request)
    {
        // Debug: Log incoming request
        \Log::info('Pickup form submitted:', $request->all());
        
        $request->validate([
            'floor_id' => 'required|exists:floors,id',
            'user_id'  => 'required|exists:people,id',
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

        // Validasi stock tersedia sebelum memproses - check ALL floors
        foreach ($items as $item) {
            // Get total stock from ALL floors for this product
            $availableStock = \App\Models\StockBalance::where('product_id', $item['product_id'])
                ->sum('qty_on_hand');
            
            if ($item['qty'] > $availableStock) {
                $product = \App\Models\Product::find($item['product_id']);
                return redirect()->back()->with('error', 
                    'Stock untuk "' . ($product->name ?? 'produk') . '" tidak mencukupi. '
                    . 'Tersedia: ' . $availableStock . ', Diminta: ' . $item['qty']
                );
            }
        }

        // Get authenticated user
        $userId = Auth::id();
        
        // Buat catatan pickup
        $pickup = Pickup::create([
            'requested_by' => $request->user_id,
            'floor_id'    => $request->floor_id,
            'pickup_no'   => 'PU-' . time() . '-' . rand(1000, 9999),
            'pickup_date' => now(),
            'notes'       => $request->notes,
            'created_by'  => $userId,
            'updated_by'  => $userId,
        ]);
        
        \Log::info('Pickup created:', ['id' => $pickup->id, 'pickup_no' => $pickup->pickup_no]);

        // Loop setiap barang yang diambil
        foreach ($items as $item) {
            $product = \App\Models\Product::findOrFail($item['product_id']);

            // Catat detail pengambilan
            $pickupLine = PickupLine::create([
                'pickup_id'  => $pickup->id,
                'product_id' => $product->id,
                'qty'        => $item['qty'],
            ]);
            
            \Log::info('PickupLine created:', ['id' => $pickupLine->id]);

            // Kurangi stock - get from ALL floors and deduct from any that has stock
            $stockBalances = \App\Models\StockBalance::where('product_id', $item['product_id'])
                ->where('qty_on_hand', '>', 0)
                ->orderBy('floor_id')  // Order by floor_id to prioritize specific floors over null
                ->get();
            
            $remainingQty = $item['qty'];
            
            foreach ($stockBalances as $stockBalance) {
                if ($remainingQty <= 0) break;
                
                $deductFromThis = min($remainingQty, $stockBalance->qty_on_hand);
                $stockBalance->qty_on_hand = max(0, $stockBalance->qty_on_hand - $deductFromThis);
                $stockBalance->save();
                $remainingQty -= $deductFromThis;
                
                \Log::info('Stock deducted from floor ' . $stockBalance->floor_id . ': ' . $deductFromThis);
            }

            // Create inventory transaction record for history
            $inventoryTrans = InventoryTransaction::create([
                'product_id'    => $product->id,
                'floor_id'      => $request->floor_id,
                'trans_type'    => 'OUT',
                'quantity'      => $item['qty'],
                'trans_at'      => now(),
                'pickup_id'     => $pickup->id,
                'pickup_line_id'=> $pickupLine->id,
                'notes'         => 'Pengambilan barang: ' . $product->name,
            ]);
            
            \Log::info('InventoryTransaction created:', ['id' => $inventoryTrans->id]);
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

    // Hapus data pengambilan barang
    public function destroy($id)
    {
        $pickup = Pickup::with('items')->findOrFail($id);
        
        // Kembalikan stock sebelum menghapus
        foreach ($pickup->items as $item) {
            // Try selected floor first, then fall back to no floor
            $stockBalance = \App\Models\StockBalance::where('product_id', $item->product_id)
                ->where('floor_id', $pickup->floor_id)
                ->first();
            
            if (!$stockBalance) {
                // Try to find stock without floor
                $stockBalance = \App\Models\StockBalance::where('product_id', $item->product_id)
                    ->whereNull('floor_id')
                    ->first();
            }
            
            if ($stockBalance) {
                $stockBalance->qty_on_hand += $item->qty;
                $stockBalance->save();
            }
        }
        
        // Hapus juga item-item terkait
        $pickup->items()->delete();
        
        $pickup->delete();

        return redirect()->route('persediaan.index')
            ->with('success', 'Data pengambilan barang berhasil dihapus dan stock dikembalikan.');
    }

    // Reset/Delete all pickup records (admin only)
    public function reset()
    {
        // Check if user is admin
        if (!Auth::user()->hasRole('admin')) {
            return redirect()->route('persediaan.index')
                ->with('error', 'Anda tidak memiliki akses untuk melakukan reset data.');
        }
        
        // Disable foreign key checks and truncate tables
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        
        // Delete all pickup lines first
        PickupLine::truncate();
        
        // Delete all pickups
        Pickup::truncate();
        
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
        
        return redirect()->route('persediaan.index')
            ->with('success', 'Semua histori pengambilan berhasil direset.');
    }
}
