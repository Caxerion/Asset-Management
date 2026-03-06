<?php

namespace App\Http\Controllers;

use App\Models\Floor;
use Illuminate\Http\Request;

class FloorController extends Controller
{
    public function index()
    {
        return Floor::all();
    }

    public function store(Request $request)
    {
        $floor = Floor::create($request->validate(['name' => 'required']));
        return response()->json($floor, 201);
    }

    public function show(Floor $floor)
    {
        return $floor;
    }

    public function update(Request $request, Floor $floor)
    {
        $floor->update($request->validate(['name' => 'required']));
        return $floor;
    }

    public function destroy(Floor $floor)
    {
        $floor->delete();
        return response()->noContent();
    }
}