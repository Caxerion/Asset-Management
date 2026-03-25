<?php

namespace App\Http\Controllers;

use App\Models\StockBalance;
use Illuminate\Http\Request;

class StockBalanceController extends Controller
{
    public function index()
    {
        return StockBalance::with('product','floor')->get();
    }

    public function store(Request $request)
    {
        $stock = StockBalance::create($request->validate([
            'product_id' => 'required|exists:products,id',
            'floor_id' => 'required|exists:floors,id',
            'qty_on_hand' => 'required|numeric'
        ]));
        return response()->json($stock, 201);
    }

    public function show(StockBalance $stockBalance)
    {
        return $stockBalance->load('product','floor');
    }

    public function update(Request $request, StockBalance $stockBalance)
    {
        $stockBalance->update($request->validate([
            'qty_on_hand' => 'required|numeric'
        ]));
        return $stockBalance;
    }

    public function destroy(StockBalance $stockBalance)
    {
        $stockBalance->delete();
        return response()->noContent();
    }

    /**
     * Reset all stock quantities to 0
     */
    public function resetAll()
    {
        StockBalance::query()->update(['qty_on_hand' => 0]);
        return redirect()->route('stock.index')->with('success', 'Semua stock berhasil direset menjadi 0. Tapi kamu masih bisa isi lagi kok');
    }
}