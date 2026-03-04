<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use App\Models\Barang;
use App\Models\RequestPengambilan;
use Illuminate\Http\Request;

class StockController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $stocks = Stock::with(['barang.kategori', 'barang.satuan', 'requestPengambilan'])
            ->paginate(15);
        
        return view('stock.index', compact('stocks'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $barangs = Barang::all();
        $requestPengambilans = RequestPengambilan::where('status_request', false)->get();

        return view('stock.create', compact('barangs', 'requestPengambilans'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'barang_id' => 'required|exists:barang,id',
            'qty' => 'required|integer|min:1',
            'harga' => 'nullable|integer|min:0',
            'request_pengambilan_id' => 'nullable|exists:request_pengambilan,id',
        ]);

        $validated['created_by'] = auth()->id();
        $validated['updated_by'] = null;

        Stock::create($validated);

        // Update barang total_qty
        $barang = Barang::find($validated['barang_id']);
        $barang->increment('total_qty', $validated['qty']);

        return redirect()->route('stock.index')
            ->with('success', 'Stock berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Stock $stock)
    {
        $stock->load(['barang.kategori', 'barang.satuan', 'requestPengambilan', 'createdBy', 'updatedBy']);

        return view('stock.show', compact('stock'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Stock $stock)
    {
        $barangs = Barang::all();
        $requestPengambilans = RequestPengambilan::where('status_request', false)->get();

        return view('stock.edit', compact('stock', 'barangs', 'requestPengambilans'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Stock $stock)
    {
        $validated = $request->validate([
            'barang_id' => 'required|exists:barang,id',
            'qty' => 'required|integer|min:1',
            'harga' => 'nullable|integer|min:0',
            'request_pengambilan_id' => 'nullable|exists:request_pengambilan,id',
        ]);

        $oldQty = $stock->qty;
        $newQty = $validated['qty'];
        $qtyDiff = $newQty - $oldQty;

        $validated['updated_by'] = auth()->id();
        $stock->update($validated);

        // Update barang total_qty
        if ($qtyDiff !== 0) {
            $barang = Barang::find($validated['barang_id']);
            $barang->increment('total_qty', $qtyDiff);
        }

        return redirect()->route('stock.index')
            ->with('success', 'Stock berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Stock $stock)
    {
        $barang = $stock->barang;
        $qty = $stock->qty;

        $stock->delete();

        // Update barang total_qty
        $barang->decrement('total_qty', $qty);

        return redirect()->route('stock.index')
            ->with('success', 'Stock berhasil dihapus!');
    }
}