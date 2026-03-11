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
        $page = $request->page ?? session('kategori_page', 1);
        $categories = Category::paginate(7, ['*'], 'page', $page);
        session(['kategori_page' => $categories->currentPage()]);
        return view('masterdata.kategori', compact('categories'));
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
