<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Kategori;
use App\Models\Satuan;
use App\Models\Ukuran;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $barangs = Barang::with(['kategori', 'satuan', 'ukuran'])
         ->paginate(15);

        return view('barang.index', compact('barangs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kategoris = Kategori::all();
        $satuans = Satuan::all();
        $ukurans = Ukuran::all();

        return view('barang.create', compact('kategoris', 'satuans', 'ukurans'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_barang' => 'required|string|max:255|unique:barang',
            'kategori_id' => 'required|exists:kategori,id',
            'satuan_id' => 'required|exists:satuan,id',
            'ukuran_id' => 'nullable|exists:ukuran,id',
            'total_qty' => 'required|integer|min:0',
        ]);

        $validated['created_by'] = auth()->id();
        $validated['updated_by'] = null;

        Barang::create($validated);

        return redirect()->route('barang.index')
            ->with('success', 'Barang berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $barang->load(['kategori', 'satuan', 'ukuran', 'stocks']);

        return view('barag.show', compact('barang'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $kategoris = Kategori::all();
        $satuans = Satuan::all();
        $ukurans = Ukuran::all();

        return view('barang.edit', compact('barang', 'kategoris', 'satuans', 'ukurans'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
         $validated = $request->validate([
            'nama_barang' => 'required|string|max:255|unique:barang,nama_barang,' . $barang->id,
            'kategori_id' => 'required|exists:kategori,id',
            'satuan_id' => 'required|exists:satuan,id',
            'ukuran_id' => 'nullable|exists:ukuran,id',
            'total_qty' => 'required|integer|min:0',
         ]);

         $validated['updated_by'] = auth()->id();

         $barang->update($validated);

         return redirect()->route(barang.index)
           ->with('success, Barang berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $barang->delete();
            return redirect()->route('barang.index')
                ->with('success', 'Barang berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->route('barang.index')
                ->with('error', 'Tidak dapat menghapus barang yang memiliki stock!');
        }
    }
}
