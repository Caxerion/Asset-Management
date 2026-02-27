<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockBalance;
use App\Models\Floor;
use Illuminate\Http\Request;

class StockController extends Controller
{
    // Menampilkan daftar stok barang
    public function index(Request $request)
    {
        $query = Product::with(['category', 'stockBalance']);

        if ($request->q) {
            $q = $request->q;
            $query->where('name','like',"%$q%")
                  ->orWhereHas('category', fn($c) => $c->where('name','like',"%$q%"));
        }

        $products = $query->paginate(6);
        $floors = Floor::all();

        return view('stock.index', compact('products', 'floors'));
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
            'floor_id' => 'required|exists:floors,id',
        ]);

        $product = Product::findOrFail($productId);

        $stockBalance = StockBalance::firstOrNew([
            'product_id' => $product->id,
            'floor_id' => $request->floor_id
        ]);
        $stockBalance->qty_on_hand = ($stockBalance->qty_on_hand ?? 0) + $request->qty;
        $stockBalance->save();

        return redirect()->route('stock.index')->with('success','Stock berhasil ditambahkan.');
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
            'floor_id' => 'required|exists:floors,id',
        ]);

        $product = Product::findOrFail($id);

        $stockBalance = StockBalance::firstOrNew([
            'product_id' => $product->id,
            'floor_id' => $request->floor_id
        ]);
        $stockBalance->qty_on_hand = ($stockBalance->qty_on_hand ?? 0) + $request->qty;
        $stockBalance->save();

        return redirect()->route('stock.index')->with('success','Stock berhasil ditambahkan.');
    }
}
