<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Size;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['category','size','stockBalances'])->get();
        return view('products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        $sizes = Size::all();
        return view('products.create', compact('categories','sizes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'sku' => 'required|unique:products',
            'name' => 'required',
            'category_id' => 'required|exists:categories,id',
            'size_id' => 'required|exists:sizes,id',
            'unit' => 'required',
            'min_stock' => 'numeric',
            'is_active' => 'boolean'
        ]);

        $product = Product::create($validated);
        return redirect()->route('products.index')->with('success','Produk berhasil ditambahkan');
    }

    public function show(Product $product)
    {
        $product->load('category','size','stockBalances');
        return view('products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        $sizes = Size::all();
        return view('products.edit', compact('product','categories','sizes'));
    }

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
        return redirect()->route('products.index')->with('success','Produk berhasil diupdate');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('products.index')->with('success','Produk berhasil dihapus');
    }
}