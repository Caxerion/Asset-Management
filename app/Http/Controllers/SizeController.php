<?php

namespace App\Http\Controllers;

use App\Models\Size;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class SizeController extends Controller
{
    /**
     * Extract unit type from size name
     */
    private function extractUnitType($name)
    {
        $name = trim($name);
        
        // Check for specific unit patterns
        if (preg_match('/\d+\s*L\b/i', $name)) return 'L';  // Liter
        if (preg_match('/\d+\s*ml\b/i', $name)) return 'ml'; // Milliliter
        if (preg_match('/\d+\s*kg\b/i', $name)) return 'kg'; // Kilogram
        if (preg_match('/\d+\s*g\b(?!\r)/i', $name)) return 'g';   // Gram (negative lookahead to avoid matching "kg")
        if (preg_match('/\d+\s*m\b(?!\r)/i', $name)) return 'm';    // Meter
        if (preg_match('/\d+\s*cm\b/i', $name)) return 'cm';  // Centimeter
        if (preg_match('/\d+\s*inch\b/i', $name)) return 'inch'; // Inch
        if (preg_match('/\d+\s*items?\b/i', $name)) return 'items'; // Items
        
        // Check for dimensions like 30x30 cm
        if (preg_match('/\d+x+\d+\s*cm\b/i', $name)) return 'cm2';
        
        // Check for dimensions like 60x100
        if (preg_match('/\d+x+\d+\b/', $name)) return 'dimension';
        
        // Size letters (S, M, L, XL)
        if (preg_match('/^(S|M|L|XL|XXL|XXXL)$/i', $name)) return 'size';
        
        // Check for inch with fraction
        if (preg_match('/1\/2\s*inch\b/i', $name)) return 'inch';
        
        return 'other';
    }

    /**
     * Extract numeric value from size name
     */
    private function extractNumericValue($name)
    {
        // Match numbers at the beginning of the string
        if (preg_match('/^([\d.]+)/', $name, $matches)) {
            return (float) $matches[1];
        }
        // Match fractions like 1/2
        if (preg_match('/1\/2/', $name)) {
            return 0.5;
        }
        return 0;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {   
        // Get search query
        $search = $request->get('search');
        
        // Get sorting parameters - format: unitType_direction (e.g., kg_asc, g_desc) or "default"
        $sortOption = $request->get('sort', 'default');
        
        // Check if default (show all by ID)
        if ($sortOption === 'default') {
            $page = $request->page ?? session('ukuran_page', 1);
            
            $query = Size::orderBy('id', 'asc');
            
            // Apply search filter
            if ($search) {
                $query->where('name', 'like', '%' . $search . '%');
            }
            
            $sizes = $query->paginate(7, ['*'], 'page', $page);
            
            session(['ukuran_page' => $sizes->currentPage()]);
            return view('masterdata.ukuran', compact('sizes', 'search'))->with(['sortField' => 'default', 'sortDirection' => 'asc']);
        }
        
        // Parse sort option for unit-specific sorting
        $parts = explode('_', $sortOption);
        $sortDirection = array_pop($parts);
        $sortUnit = implode('_', $parts);
        
        // Validate sort direction
        if (!in_array($sortDirection, ['asc', 'desc'])) {
            $sortDirection = 'asc';
        }
        
        // Valid unit types
        $validUnits = ['kg', 'g', 'ml', 'L', 'cm', 'inch', 'items', 'm', 'size', 'dimension', 'cm2', 'other'];
        if (!in_array($sortUnit, $validUnits)) {
            $sortUnit = 'kg'; // Default to kg
        }

        $page = $request->page ?? session('ukuran_page', 1);
        
        // Get all sizes
        $query = Size::query();
        
        // Apply search filter
        if ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        }
        
        $sizes = $query->get();
        
        // Add unit_type and unit_value to each item
        $sizes = $sizes->map(function ($size) {
            $size->unit_type = $this->extractUnitType($size->name);
            $size->unit_value = $this->extractNumericValue($size->name);
            return $size;
        });
        
        // Filter by unit type
        $sizes = $sizes->filter(function ($size) use ($sortUnit) {
            return $size->unit_type === $sortUnit;
        });
        
        // Sort by numeric value within the unit type
        $sizes = $sizes->sort(function ($a, $b) use ($sortDirection) {
            return $sortDirection === 'asc' 
                ? $a->unit_value - $b->unit_value 
                : $b->unit_value - $a->unit_value;
        });
        
        // Paginate manually
        $perPage = 7;
        $total = $sizes->count();
        $currentPage = $page;
        $items = $sizes->forPage($currentPage, $perPage);
        
        $sizes = new \Illuminate\Pagination\LengthAwarePaginator(
            $items,
            $total,
            $perPage,
            $currentPage,
            ['path' => route('masterdata.ukuran.index')]
        );
        
        // Pass sort field and direction for the view
        $sortField = $sortUnit;
        
        session(['ukuran_page' => $sizes->currentPage()]);
        return view('masterdata.ukuran', compact('sizes', 'sortField', 'sortDirection', 'search'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Size::create([
            'name' => $request->name,
        ]);

        $page = session('ukuran_page', 1);
        return redirect()->route('masterdata.ukuran.index', ['page' => $page])->with('success', 'Ukuran berhasil ditambahkan.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Size $size)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $size->update([
            'name' => $request->name,
        ]);

        $page = session('ukuran_page', 1);
        return redirect()->route('masterdata.ukuran.index', ['page' => $page])->with('success', 'Ukuran berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Size $size)
    {
        // Check if size has products
        if ($size->products()->count() > 0) {
            $page = session('ukuran_page', 1);
            return redirect()->route('masterdata.ukuran.index', ['page' => $page])->with('error', 'Ukuran tidak dapat dihapus karena masih memiliki produk.');
        }

        $size->delete();

        $page = session('ukuran_page', 1);
        return redirect()->route('masterdata.ukuran.index', ['page' => $page])->with('success', 'Ukuran berhasil dihapus.');
    }
}
