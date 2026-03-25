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
        // Get sorting parameters from combined field (e.g., name_asc, id_desc)
        $sortOption = $request->get('sort', 'name_asc');
        $sortParts = explode('_', $sortOption);
        
        // Last part is the direction, everything else is the field
        $sortDirection = array_pop($sortParts);
        $sortField = implode('_', $sortParts);
        
        // Validate sort direction
        if (!in_array($sortDirection, ['asc', 'desc'])) {
            $sortDirection = 'asc';
        }
        
        // Validate sort field
        $allowedFields = ['id', 'name', 'is_active', 'created_at', 'stock_balance_count', 'pickup_count'];
        if (!in_array($sortField, $allowedFields)) {
            $sortField = 'name';
        }
        $page = $request->page ?? session('lantai_page', 1);
        
        // Build query with count subqueries
        $floors = Floor::select('floors.*')
            ->selectSub(function ($query) {
                $query->from('stock_balances')
                    ->whereColumn('stock_balances.floor_id', 'floors.id')
                    ->selectRaw('COUNT(*)');
            }, 'stock_balance_count')
            ->selectSub(function ($query) {
                $query->from('pickups')
                    ->whereColumn('pickups.floor_id', 'floors.id')
                    ->selectRaw('COUNT(*)');
            }, 'pickup_count');
        
        // Apply sorting
        if ($sortField === 'stock_balance_count') {
            $floors = $floors->orderBy('stock_balance_count', $sortDirection);
        } elseif ($sortField === 'pickup_count') {
            $floors = $floors->orderBy('pickup_count', $sortDirection);
        } else {
            $floors = $floors->orderBy($sortField, $sortDirection);
        }
        
        $floors = $floors->paginate(7, ['*'], 'page', $page);
        session(['lantai_page' => $floors->currentPage()]);
        return view('masterdata.lantai', compact('floors', 'sortField', 'sortDirection'));
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
