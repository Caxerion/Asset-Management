<?php

namespace App\Http\Controllers;

use App\Models\Person;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class PersonController extends Controller
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
        $allowedFields = ['id', 'name', 'is_active', 'created_at'];
        if (!in_array($sortField, $allowedFields)) {
            $sortField = 'name';
        }

        $query = Person::query();
        
        // Search functionality
        if ($request->q) {
            $q = $request->q;
            $query->where(function($query) use ($q) {
                $query->where('name', 'like', "%$q%");
            });
        }
        
        $page = $request->page ?? session('person_page', 1);
        $people = $query->orderBy($sortField, $sortDirection)->paginate(10, ['*'], 'page', $page);
        session(['person_page' => $people->currentPage()]);
        return view('masterdata.person', compact('people', 'sortField', 'sortDirection'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Person::create([
            'name' => $request->name,
            'is_active' => true,
        ]);

        $page = session('person_page', 1);
        return redirect()->route('person.index', ['page' => $page])->with('success', 'Data orang berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $person = Person::findOrFail($id);
        $person->delete();

        $page = session('person_page', 1);
        return redirect()->route('person.index', ['page' => $page])->with('success', 'Data orang berhasil dihapus!');
    }
}
