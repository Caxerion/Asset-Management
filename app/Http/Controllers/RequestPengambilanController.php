<?php

namespace App\Http\Controllers;

use App\Models\RequestPengambilan;
use App\Models\Lantai;
use App\Models\Barang;
use App\Models\DetailRequestPengambilan;
use Illuminate\Http\Request;

class RequestPengambilanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();
        $query = RequestPengambilan::with(['lantai', 'user', 'details']);

        // Filter based on role
        if ($user->role === 'staff') {
            $query->where('user_id', $user->id);
        } elseif ($user->role === 'pic') {
            $query->whereHas('lantai', function ($q) use ($user) {
                $q->where('pic_user_id', $user->id);
            });
        }

        $requests = $query->paginate(15);

        return view('request-pengambilan.index', compact('requests'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $lantais = Lantai::all();
        $barangs = Barang::with(['kategori', 'satuan'])->get();

        return view('request-pengambilan.create', compact('lantais', 'barangs'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'lantai_id' => 'required|exists:lantai,id',
            'barang' => 'required|array|min:1',
            'barang.*.id' => 'required|exists:barang,id',
            'barang.*.qty' => 'required|integer|min:1',
            'barang.*.note' => 'nullable|string',
        ]);

        $requestPengambilan = RequestPengambilan::create([
            'lantai_id' => $validated['lantai_id'],
            'user_id' => auth()->id(),
            'status_request' => false,
            'created_by' => auth()->id(),
            'updated_by' => null,
        ]);

        // Create detail entries
        foreach ($validated['barang'] as $item) {
            DetailRequestPengambilan::create([
                'request_pengambilan_id' => $requestPengambilan->id,
                'barang_id' => $item['id'],
                'qty' => $item['qty'],
                'note' => $item['note'] ?? null,
                'created_by' => auth()->id(),
                'updated_by' => null,
            ]);
        }

        return redirect()->route('request-pengambilan.index')
            ->with('success', 'Request pengambilan berhasil dibuat!');
    }

    /**
     * Display the specified resource.
     */
    public function show(RequestPengambilan $requestPengambilan)
    {
        $requestPengambilan->load(['lantai', 'user', 'details.barang', 'createdBy', 'updatedBy']);

        return view('request-pengambilan.show', compact('requestPengambilan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(RequestPengambilan $requestPengambilan)
    {
        // Only pending requests can be edited
        if ($requestPengambilan->status_request) {
            return redirect()->route('request-pengambilan.index')
                ->with('error', 'Tidak dapat mengedit request yang sudah disetujui!');
        }

        $lantais = Lantai::all();
        $barangs = Barang::with(['kategori', 'satuan'])->get();
        $requestPengambilan->load('details');

        return view('request-pengambilan.edit', compact('requestPengambilan', 'lantais', 'barangs'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, RequestPengambilan $requestPengambilan)
    {
        if ($requestPengambilan->status_request) {
            return redirect()->route('request-pengambilan.index')
                ->with('error', 'Tidak dapat mengubah request yang sudah disetujui!');
        }

        $validated = $request->validate([
            'lantai_id' => 'required|exists:lantai,id',
            'barang' => 'required|array|min:1',
            'barang.*.id' => 'required|exists:barang,id',
            'barang.*.qty' => 'required|integer|min:1',
            'barang.*.note' => 'nullable|string',
        ]);

        $requestPengambilan->update([
            'lantai_id' => $validated['lantai_id'],
            'updated_by' => auth()->id(),
        ]);

        // Delete old details and create new ones
        $requestPengambilan->details()->delete();

        foreach ($validated['barang'] as $item) {
            DetailRequestPengambilan::create([
                'request_pengambilan_id' => $requestPengambilan->id,
                'barang_id' => $item['id'],
                'qty' => $item['qty'],
                'note' => $item['note'] ?? null,
                'created_by' => auth()->id(),
                'updated_by' => null,
            ]);
        }

        return redirect()->route('request-pengambilan.index')
            ->with('success', 'Request pengambilan berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RequestPengambilan $requestPengambilan)
    {
        if ($requestPengambilan->status_request) {
            return redirect()->route('request-pengambilan.index')
                ->with('error', 'Tidak dapat menghapus request yang sudah disetujui!');
        }

        $requestPengambilan->delete();

        return redirect()->route('request-pengambilan.index')
            ->with('success', 'Request pengambilan berhasil dihapus!');
    }

    /**
     * Approve request (PIC only)
     */
    public function approve(RequestPengambilan $requestPengambilan)
    {
        $user = auth()->user();

        // Check if user is the PIC for this lantai
        if ($requestPengambilan->lantai->pic_user_id !== $user->id && $user->role !== 'admin') {
            return redirect()->back()
                ->with('error', 'Anda tidak berhak menyetujui request ini!');
        }

        $requestPengambilan->update([
            'status_request' => true,
            'updated_by' => auth()->id(),
        ]);

        return redirect()->route('request-pengambilan.show', $requestPengambilan)
            ->with('success', 'Request berhasil disetujui!');
    }

    /**
     * Reject request (PIC only)
     */
    public function reject(RequestPengambilan $requestPengambilan)
    {
        $user = auth()->user();

        if ($requestPengambilan->lantai->pic_user_id !== $user->id && $user->role !== 'admin') {
            return redirect()->back()
                ->with('error', 'Anda tidak berhak menolak request ini!');
        }

        $requestPengambilan->update([
            'status_request' => false,
            'updated_by' => auth()->id(),
        ]);

        return redirect()->route('request-pengambilan.show', $requestPengambilan)
            ->with('success', 'Request berhasil ditolak!');
    }
}