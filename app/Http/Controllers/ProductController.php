<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Size;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $page = $request->page ?? session('produk_page', 1);
        
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
        $allowedFields = ['id', 'name', 'sku', 'category_id', 'size_id', 'unit', 'min_stock', 'is_active', 'created_at'];
        if (!in_array($sortField, $allowedFields)) {
            $sortField = 'name';
        }

        $query = Product::query();

        // Search functionality
        if ($request->q) {
            $q = $request->q;
            $query->where(function($query) use ($q) {
                $query->where('name', 'like', "%$q%")
                      ->orWhere('sku', 'like', "%$q%")
                      ->orWhereHas('category', fn($c) => $c->where('name', 'like', "%$q%"))
                      ->orWhereHas('size', fn($s) => $s->where('name', 'like', "%$q%"));
            });
        }
        
        
        $products = $query->with(['category', 'size', 'stockBalances'])
            ->orderBy($sortField, $sortDirection)
            ->paginate(7, ['*'], 'page', $page);
        session(['produk_page' => $products->currentPage()]);
        $categories = Category::all();
        $sizes = Size::all();
        
        return view('masterdata.produk', compact('products', 'categories', 'sizes', 'sortField', 'sortDirection'));
    }

    /**
     * Store a newly created resource in storage.
     */
    private function generateSKU()
    {
        $lastProduct = Product::orderBy('id', 'desc')->first();
        
        if ($lastProduct) {
            // Extract the numeric part from the last SKU (e.g., PRD-0043 -> 43)
            $lastNumber = (int) str_replace('PRD-', '', $lastProduct->sku);
            $newNumber = $lastNumber + 1;
            return 'PRD-' . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
        }
        
        // If no products exist, start with PRD-0001
        return 'PRD-0001';
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'category_id' => 'required|exists:categories,id',
            'size_id' => 'required|exists:sizes,id',
            'unit' => 'required',
            'min_stock' => 'numeric',
            'is_active' => 'boolean'
        ]);

        $validated['sku'] = $this->generateSKU();
        $validated['is_active'] = true;

        $product = Product::create($validated);
        $page = session('produk_page', 1);
        return redirect()->route('masterdata.produk.index', ['page' => $page])->with('success', 'Produk berhasil ditambahkan');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'sku' => 'required',
            'name' => 'required',
            'category_id' => 'required|exists:categories,id',
            'size_id' => 'required|exists:sizes,id',
            'unit' => 'required',
            'min_stock' => 'numeric',
            'is_active' => 'boolean'
        ]);

        $product->update($validated);
        $page = session('produk_page', 1);
        return redirect()->route('masterdata.produk.index', ['page' => $page])->with('success', 'Produk berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $product->delete();
        $page = session('produk_page', 1);
        return redirect()->route('masterdata.produk.index', ['page' => $page])->with('success', 'Produk berhasil dihapus');
    }
}
