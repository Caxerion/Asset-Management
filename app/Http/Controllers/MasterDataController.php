<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Floor;
use App\Models\Product;
use App\Models\Size;
use Illuminate\Http\Request;

class MasterDataController extends Controller
{
    public function index()
    {
        $categories = Category::paginate(10);
        $floors = Floor::paginate(10);
        $products = Product::with(['category', 'size'])->paginate(10);
        $sizes = Size::paginate(10);
        
        return view('masterdata.index', compact('categories', 'floors', 'products', 'sizes'));
    }

    // ================== Size Methods ==================
    
    public function storeSize(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Size::create([
            'name' => $request->name,
        ]);

        return redirect()->route('masterdata.index')->with('success', 'Ukuran berhasil ditambahkan.');
    }

    public function updateSize(Request $request, Size $size)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $size->update([
            'name' => $request->name,
        ]);

        return redirect()->route('masterdata.index')->with('success', 'Ukuran berhasil diperbarui.');
    }

    public function destroySize(Size $size)
    {
        // Check if size has products
        if ($size->products()->count() > 0) {
            return redirect()->route('masterdata.index')->with('error', 'Ukuran tidak dapat dihapus karena masih memiliki produk.');
        }

        $size->delete();

        return redirect()->route('masterdata.index')->with('success', 'Ukuran berhasil dihapus.');
    }

    // ================== Category Methods ==================
    
    public function storeCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Category::create([
            'name' => $request->name,
            'is_active' => true,
        ]);

        return redirect()->route('masterdata.index')->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function updateCategory(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $category->update([
            'name' => $request->name,
        ]);

        return redirect()->route('masterdata.index')->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroyCategory(Category $category)
    {
        // Check if category has products
        if ($category->products()->count() > 0) {
            return redirect()->route('masterdata.index')->with('error', 'Kategori tidak dapat dihapus karena masih memiliki produk.');
        }

        $category->delete();

        return redirect()->route('masterdata.index')->with('success', 'Kategori berhasil dihapus.');
    }

    // ================== Floor Methods ==================
    
    public function storeFloor(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Floor::create([
            'name' => $request->name,
        ]);

        return redirect()->route('masterdata.index')->with('success', 'Lantai berhasil ditambahkan.');
    }

    public function updateFloor(Request $request, Floor $floor)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $floor->update([
            'name' => $request->name,
        ]);

        return redirect()->route('masterdata.index')->with('success', 'Lantai berhasil diperbarui.');
    }

    public function destroyFloor(Floor $floor)
    {
        // Check if floor has stock balances
        if ($floor->stockBalances()->count() > 0) {
            return redirect()->route('masterdata.index')->with('error', 'Lantai tidak dapat dihapus karena masih memiliki stock balance.');
        }

        // Check if floor has pickups
        if ($floor->pickups()->count() > 0) {
            return redirect()->route('masterdata.index')->with('error', 'Lantai tidak dapat dihapus karena masih memiliki pickup.');
        }

        $floor->delete();

        return redirect()->route('masterdata.index')->with('success', 'Lantai berhasil dihapus.');
    }

    // ================== Product Methods ==================
    
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

    public function storeProduct(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'size_id' => 'required|exists:sizes,id',
            'unit' => 'required|string|max:50',
            'min_stock' => 'required|integer|min:0',
        ]);

        Product::create([
            'sku' => $this->generateSKU(),
            'name' => $request->name,
            'category_id' => $request->category_id,
            'size_id' => $request->size_id,
            'unit' => $request->unit,
            'min_stock' => $request->min_stock,
            'is_active' => true,
        ]);

        return redirect()->route('masterdata.index')->with('success', 'Barang berhasil ditambahkan.');
    }

    public function updateProduct(Request $request, Product $product)
    {
        $request->validate([
            'sku' => 'required|string|max:255|unique:products,sku,' . $product->id,
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'size_id' => 'required|exists:sizes,id',
            'unit' => 'required|string|max:50',
            'min_stock' => 'required|integer|min:0',
        ]);

        $product->update([
            'sku' => $request->sku,
            'name' => $request->name,
            'category_id' => $request->category_id,
            'size_id' => $request->size_id,
            'unit' => $request->unit,
            'min_stock' => $request->min_stock,
        ]);

        return redirect()->route('masterdata.index')->with('success', 'Barang berhasil diperbarui.');
    }

    public function destroyProduct(Product $product)
    {
        // Check if product has stock balances
        if ($product->stockBalances()->count() > 0) {
            return redirect()->route('masterdata.index')->with('error', 'Barang tidak dapat dihapus karena masih memiliki stock balance.');
        }

        $product->delete();

        return redirect()->route('masterdata.index')->with('success', 'Barang berhasil dihapus.');
    }
}
