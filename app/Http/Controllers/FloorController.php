<?php

namespace App\Http\Controllers;

use App\Models\Floor;
use Illuminate\Http\Request;

class FloorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $page = $request->page ?? session('lantai_page', 1);
        $floors = Floor::paginate(7, ['*'], 'page', $page);
        session(['lantai_page' => $floors->currentPage()]);
        return view('masterdata.lantai', compact('floors'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Floor::create([
            'name' => $request->name,
        ]);

        $page = session('lantai_page', 1);
        return redirect()->route('masterdata.lantai.index', ['page' => $page])->with('success', 'Lantai berhasil ditambahkan.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Floor $floor)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $floor->update([
            'name' => $request->name,
        ]);

        $page = session('lantai_page', 1);
        return redirect()->route('masterdata.lantai.index', ['page' => $page])->with('success', 'Lantai berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Floor $floor)
    {
        // Check if floor has stock balances
        if ($floor->stockBalances()->count() > 0) {
            $page = session('lantai_page', 1);
            return redirect()->route('masterdata.lantai.index', ['page' => $page])->with('error', 'Lantai tidak dapat dihapus karena masih memiliki stock balance.');
        }

        // Check if floor has pickups
        if ($floor->pickups()->count() > 0) {
            $page = session('lantai_page', 1);
            return redirect()->route('masterdata.lantai.index', ['page' => $page])->with('error', 'Lantai tidak dapat dihapus karena masih memiliki pickup.');
        }

        $floor->delete();

        $page = session('lantai_page', 1);
        return redirect()->route('masterdata.lantai.index', ['page' => $page])->with('success', 'Lantai berhasil dihapus.');
    }
}
