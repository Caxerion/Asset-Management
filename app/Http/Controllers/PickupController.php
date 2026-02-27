<?php

namespace App\Http\Controllers;

use App\Models\Pickup;
use Illuminate\Http\Request;

class PickupController extends Controller
{
    public function index()
    {
        return Pickup::with('product','floor')->get();
    }

    public function store(Request $request)
    {
        $pickup = Pickup::create($request->validate([
            'product_id' => 'required|exists:products,id',
            'floor_id' => 'required|exists:floors,id',
            'qty' => 'required|numeric',
            'picked_at' => 'required|date'
        ]));
        return response()->json($pickup, 201);
    }

    public function show(Pickup $pickup)
    {
        return $pickup->load('product','floor');
    }

    public function update(Request $request, Pickup $pickup)
    {
        $pickup->update($request->validate([
            'qty' => 'required|numeric',
            'picked_at' => 'required|date'
        ]));
        return $pickup;
    }

    public function destroy(Pickup $pickup)
    {
        $pickup->delete();
        return response()->noContent();
    }
}