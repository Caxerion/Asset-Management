<?php

namespace App\Http\Controllers;

use App\Models\Receipt;
use Illuminate\Http\Request;

class ReceiptController extends Controller
{
    public function index()
    {
        return Receipt::with('product','floor')->get();
    }

    public function store(Request $request)
    {
        $receipt = Receipt::create($request->validate([
            'product_id' => 'required|exists:products,id',
            'floor_id' => 'required|exists:floors,id',
            'qty' => 'required|numeric',
            'received_at' => 'required|date'
        ]));
        return response()->json($receipt, 201);
    }

    public function show(Receipt $receipt)
    {
        return $receipt->load('product','floor');
    }

    public function update(Request $request, Receipt $receipt)
    {
        $receipt->update($request->validate([
            'qty' => 'required|numeric',
            'received_at' => 'required|date'
        ]));
        return $receipt;
    }

    public function destroy(Receipt $receipt)
    {
        $receipt->delete();
        return response()->noContent();
    }
}