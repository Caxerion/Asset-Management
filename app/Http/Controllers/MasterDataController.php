<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Floor;
use App\Models\Product;
use App\Models\Size;
use Illuminate\Http\Request;

class MasterDataController extends Controller
{
    public function index(Request $request)
    {
        // Get sorting parameters
        $categorySort = $request->get('category_sort', 'name_asc');
        $sizeSort = $request->get('size_sort', 'name_asc');
        $floorSort = $request->get('floor_sort', 'name_asc');
        $productSort = $request->get('product_sort', 'name_asc');

        // Parse sort parameters
        $categorySortParts = $this->parseSort($categorySort);
        $sizeSortParts = $this->parseSort($sizeSort);
        $floorSortParts = $this->parseSort($floorSort);
        $productSortParts = $this->parseSort($productSort);

        // Get pages from session or URL
        $categoryPage = $request->category_page ?? session('masterdata_category_page', 1);
        $floorPage = $request->floor_page ?? session('masterdata_floor_page', 1);
        $productPage = $request->product_page ?? session('masterdata_product_page', 1);
        $sizePage = $request->size_page ?? session('masterdata_size_page', 1);

        // Query with sorting
        $categories = Category::orderBy($categorySortParts['field'], $categorySortParts['direction'])->paginate(10, ['*'], 'category_page', $categoryPage);
        $floors = Floor::orderBy($floorSortParts['field'], $floorSortParts['direction'])->paginate(10, ['*'], 'floor_page', $floorPage);
        $products = Product::with(['category', 'size'])->orderBy($productSortParts['field'], $productSortParts['direction'])->paginate(10, ['*'], 'product_page', $productPage);
        $sizes = Size::orderBy($sizeSortParts['field'], $sizeSortParts['direction'])->paginate(10, ['*'], 'size_page', $sizePage);
        
        // Store current pages in session
        session([
            'masterdata_category_page' => $categories->currentPage(),
            'masterdata_floor_page' => $floors->currentPage(),
            'masterdata_product_page' => $products->currentPage(),
            'masterdata_size_page' => $sizes->currentPage(),
        ]);
        
        return view('masterdata.index', compact('categories', 'floors', 'products', 'sizes', 'categorySort', 'sizeSort', 'floorSort', 'productSort'));
    }

    private function parseSort($sortOption)
    {
        $parts = explode('_', $sortOption);
        $direction = array_pop($parts);
        $field = implode('_', $parts);
        
        if (!in_array($direction, ['asc', 'desc'])) {
            $direction = 'asc';
        }
        
        return ['field' => $field, 'direction' => $direction];
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

        $page = session('masterdata_size_page', 1);
        return redirect()->route('masterdata.index', ['size_page' => $page])->with('success', 'Ukuran berhasil diperbarui.');
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

        $page = session('masterdata_category_page', 1);
        return redirect()->route('masterdata.index', ['category_page' => $page])->with('success', 'Kategori berhasil diperbarui.');
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

        $page = session('masterdata_floor_page', 1);
        return redirect()->route('masterdata.index', ['floor_page' => $page])->with('success', 'Lantai berhasil diperbarui.');
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

        $page = session('masterdata_product_page', 1);
        return redirect()->route('masterdata.index', ['product_page' => $page])->with('success', 'Barang berhasil diperbarui.');
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
