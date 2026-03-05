<?php

namespace App\Http\Controllers;

use App\Models\InventoryTransaction;
use Illuminate\Http\Request;

class InventoryTransactionController extends Controller
{
    public function index()
    {
        return InventoryTransaction::with('product','floor')->get();
    }

    public function store(Request $request)
    {
        $transaction = InventoryTransaction::create($request->validate([
            'product_id' => 'required|exists:products,id',
            'floor_id' => 'required|exists:floors,id',
            'trans_type' => 'required|string',
            'quantity' => 'required|numeric',
            'trans_at' => 'required|date'
        ]));
        return response()->json($transaction, 201);
    }

    public function show(InventoryTransaction $inventoryTransaction)
    {
        return $inventoryTransaction->load('product','floor');
    }

    public function update(Request $request, InventoryTransaction $inventoryTransaction)
    {
        $inventoryTransaction->update($request->validate([
            'trans_type' => 'required|string',
            'quantity' => 'required|numeric',
            'trans_at' => 'required|date'
        ]));
        return $inventoryTransaction;
    }

    public function destroy(InventoryTransaction $inventoryTransaction)
    {
        $inventoryTransaction->delete();
        return response()->noContent();
    }
}