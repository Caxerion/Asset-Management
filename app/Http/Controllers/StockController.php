<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockBalance;
use App\Models\Floor;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class StockController extends Controller
{
    // Menampilkan daftar stok barang
    public function index(Request $request)
    {
        // Get sorting parameters from combined field (e.g., id_asc, stock_desc)
        $sortOption = $request->get('sort', 'id_asc');
        $sortParts = explode('_', $sortOption);
        
        // Last part is the direction, everything else is the field
        $sortDirection = array_pop($sortParts);
        $sortField = implode('_', $sortParts);
        
        if (!in_array($sortDirection, ['asc', 'desc'])) {
            $sortDirection = 'asc';
        }

        $query = Product::with(['category', 'size', 'stockBalances']);

        // Apply sorting
        if ($sortField === 'stock') {
            // Sort by total stock (sum of qty_on_hand from stock_balances)
            $query->select('products.*')
                  ->leftJoin('stock_balances', 'products.id', '=', 'stock_balances.product_id')
                  ->groupBy('products.id')
                  ->orderByRaw('COALESCE(SUM(stock_balances.qty_on_hand), 0) ' . $sortDirection);
        } else {
            // Default sort by ID
            $query->orderBy('id', $sortDirection);
        }
        
        // Get products with pagination (6 per page)
        $products = $query->paginate(6);
        
        $floors = Floor::all();

        return view('stock.index', compact('products', 'floors', 'sortField', 'sortDirection'));
    }

    // Form tambah stok barang - redirect to stock index (view not implemented)
    public function create($productId)
    {
        return redirect()->route('stock.index')->with('info', 'Fitur tambah stock tersedia di halaman Stock.');
    }

    // Simpan penambahan stok barang
    public function store(Request $request, $productId)
    {
        $request->validate([
            'qty' => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($productId);

        // Get Mezanine floor ID as default (or first floor)
        $mezanineFloor = \App\Models\Floor::where('name', 'Mezanine')->first();
        $floorId = $request->floor_id ?: ($mezanineFloor ? $mezanineFloor->id : null);

        // Add stock with specific floor (or default to Mezanine)
        $stockBalance = StockBalance::firstOrNew([
            'product_id' => $product->id,
            'floor_id' => $floorId,
        ]);
        $stockBalance->qty_on_hand = ($stockBalance->qty_on_hand ?? 0) + $request->qty;
        $stockBalance->save();

        // Preserve pagination when redirecting
        $page = session('stock_page', 1);
        return redirect()->route('stock.index', ['page' => $page])->with('success','Stock berhasil ditambahkan.');
    }

    // Detail barang - redirect to stock index (view not implemented)
    public function show($id)
    {
        return redirect()->route('stock.index')->with('info', 'Detail barang dapat dilihat di halaman Stock.');
    }

    // Edit barang - redirect to stock index (view not implemented)
    public function edit($id)
    {
        return redirect()->route('stock.index')->with('info', 'Fitur edit barang tersedia di halaman Stock.');
    }

    // Tambah stok via modal
    public function add(Request $request, $id)
    {
        $request->validate([
            'qty' => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($id);

        // Add stock without specific floor (global stock)
        $stockBalance = StockBalance::firstOrNew([
            'product_id' => $product->id,
            'floor_id' => null,
        ]);
        $stockBalance->qty_on_hand = ($stockBalance->qty_on_hand ?? 0) + $request->qty;
        $stockBalance->save();

        // Preserve pagination when redirecting
        $page = session('stock_page', 1);
        return redirect()->route('stock.index', ['page' => $page])->with('success','Stock berhasil ditambahkan.');
    }
}
