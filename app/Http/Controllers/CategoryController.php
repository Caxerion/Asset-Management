<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
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

        $page = $request->page ?? session('kategori_page', 1);
        $categories = Category::orderBy($sortField, $sortDirection)->paginate(7, ['*'], 'page', $page);
        session(['kategori_page' => $categories->currentPage()]);
        return view('masterdata.kategori', compact('categories', 'sortField', 'sortDirection'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Category::create([
            'name' => $request->name,
            'is_active' => true,
        ]);

        $page = session('kategori_page', 1);
        return redirect()->route('masterdata.kategori.index', ['page' => $page])->with('success', 'Kategori berhasil ditambahkan.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $category->update([
            'name' => $request->name,
        ]);

        $page = session('kategori_page', 1);
        return redirect()->route('masterdata.kategori.index', ['page' => $page])->with('success', 'Kategori berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        // Check if category has products
        if ($category->products()->count() > 0) {
            $page = session('kategori_page', 1);
            return redirect()->route('masterdata.kategori.index', ['page' => $page])->with('error', 'Kategori tidak dapat dihapus karena masih memiliki produk.');
        }

        $category->delete();

        $page = session('kategori_page', 1);
        return redirect()->route('masterdata.kategori.index', ['page' => $page])->with('success', 'Kategori berhasil dihapus.');
    }
}