<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Size;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $page = $request->page ?? session('produk_page', 1);
        $products = Product::with(['category', 'size', 'stockBalances'])->paginate(7, ['*'], 'page', $page);
        session(['produk_page' => $products->currentPage()]);
        $categories = Category::all();
        $sizes = Size::all();
        return view('masterdata.produk', compact('products', 'categories', 'sizes'));
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
