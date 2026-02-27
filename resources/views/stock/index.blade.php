@extends('layouts.app')

@section('content')
<div class="container">
    {{-- Breadcrumb style header --}}
    <div class="mb-3">
        <span class="text-muted fs-3">Persediaan /</span>
        <h1 class="d-inline fs-3">Stock Barang</h1>
    </div>

    {{-- Tab menu --}}
    <ul class="nav nav-tabs mb-3">
        <li class="nav-item">
            <a class="nav-link" href="{{ route('persediaan.index') }}">Histori Pengambilan</a>
        </li>
        <li class="nav-item">
            <a class="nav-link active" href="{{ route('stock.index') }}">Stock Barang</a>
        </li>
    </ul>

    {{-- Search --}}
    <div class="d-flex justify-content-between mb-3">
        <div class="col-md-4">
            <form method="GET" action="{{ route('stock.index') }}" class="d-flex gap-2">
                <input type="text" name="q" placeholder="Cari..." 
                       class="form-control form-control-sm" value="{{ request('q') }}">
                <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-search"></i></button>
                @if(request('q'))
                    <a href="{{ route('stock.index') }}" class="btn btn-outline-secondary btn-sm">Reset</a>
                @endif
            </form>
        </div>
    </div>

    {{-- Card with table --}}
    <div class="card">
        <div class="card-header bg-white py-2">
            <h6 class="mb-0">Data Stock Barang</h6>
        </div>
        <div class="card-body p-0">
            <table class="table table-bordered table-striped mb-0">
                <thead>
                    <tr>
                        <th style="width: 50px;">#</th>
                        <th class="fs-6" style="width: 195px; text-align: left">KATEGORI BARANG</th>
                        <th style="text-align: left">NAMA BARANG</th>
                        <th style="width: 180px; text-align: left">UKURAN BARANG</th>
                        <th class="fs-6" style="width: 160px;">STOCK TERSEDIA SAAT INI</th>
                        <th style="width: 120px;">TAMBAH STOCK</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                        <tr>
                            <td>#{{ $product->id }}</td>
                            <td>
                                <span class="badge bg-primary">{{ $product->category->name ?? '-' }}</span>
                            </td>
                            <td>{{ $product->name }}</td>
                            <td>{{ $product->size->name ?? '-' }}</td>
                            <td>
                                @if($product->stock_balance > 10)
                                    <span class="badge bg-success">{{ $product->stock_balance }} {{ $product->unit }}</span>
                                @elseif($product->stock_balance > 0)
                                    <span class="badge bg-warning text-dark">{{ $product->stock_balance }} {{ $product->unit }}</span>
                                @else
                                    <span class="badge bg-danger">{{ $product->stock_balance }} {{ $product->unit }}</span>
                                @endif
                            </td>
                            <td>
                                <button type="button" class="btn btn-sm btn-success" 
                                        data-bs-toggle="modal" data-bs-target="#tambahStockModal{{ $product->id }}">
                                    + Tambah
                                </button>
                            </td>
                        </tr>

                        {{-- Modal Tambah Stock --}}
                        <div class="modal fade" id="tambahStockModal{{ $product->id }}" tabindex="-1" aria-hidden="true">
                          <div class="modal-dialog">
                            <div class="modal-content">
                              <div class="modal-header">
                                <h5 class="modal-title">Tambah Stock: {{ $product->name }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                              </div>
                              <div class="modal-body">
                                <form action="{{ route('stock.add', $product->id) }}" method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <label class="form-label">Lantai</label>
                                        <select name="floor_id" class="form-control" required>
                                            @foreach($floors as $floor)
                                                <option value="{{ $floor->id }}">{{ $floor->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Jumlah Tambahan</label>
                                        <input type="number" name="qty" class="form-control" required>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-success">Simpan</button>
                                    </div>
                                </form>
                              </div>
                            </div>
                          </div>
                        </div>
                    @empty
                        <tr>
                            <td colspan="6">Belum ada data stok barang.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Pagination --}}
    <div class="mt-3">
        {{ $products->links() }}
    </div>
</div>
@endsection
