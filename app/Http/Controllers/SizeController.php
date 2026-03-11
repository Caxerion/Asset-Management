<?php

namespace App\Http\Controllers;

use App\Models\Size;
use Illuminate\Http\Request;

class SizeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $page = $request->page ?? session('ukuran_page', 1);
        $sizes = Size::paginate(7, ['*'], 'page', $page);
        session(['ukuran_page' => $sizes->currentPage()]);
        return view('masterdata.ukuran', compact('sizes'));
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
