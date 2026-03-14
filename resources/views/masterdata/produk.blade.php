@extends('layouts.app')

@section('content')
<div class="container">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fa fa-box" style="margin-right: 8px;"></i>Data Produk</h2>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">
            <i class="fa fa-plus"></i> Tambah Produk
        </button>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif


    <div class="card-header bg-white py-2 d-flex justify-content-between align-items-center">
        <form method="GET" action="{{ route('masterdata.produk.index') }}" class="d-flex gap-2">
            <input type="text" name="q" placeholder="Cari Nama Produk..." 
                    class="form-control form-control-sm" style="width: 350px; border-radius: 10px; padding-bottom: 10px; padding-top: 10px;" value="{{ request('q') }}">
            <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-search"></i></button>
            @if(request('q'))
                <a href="{{ route('masterdata.produk.index') }}" class="btn btn-outline-secondary btn-sm">Reset</a>
            @endif
        </form>
    </div>


    <div class="card">
        <div class="card-body">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th><a href="{{ route('masterdata.produk.index', ['sort' => 'id', 'direction' => ($sortField === 'id' && $sortDirection === 'asc') ? 'desc' : 'asc', 'page' => $products->currentPage()]) }}">
                        SKU
                        {!! $sortField === 'id' ? ($sortDirection === 'asc' ? '↑' : '↓') : '' !!}
                        </a> </th>
                        <th><a class="sort-link" href="{{ route('masterdata.produk.index', [
                        'sort' => 'name',
                        'direction' => ($sortField === 'name' && $sortDirection === 'asc') ? 'desc' : 'asc',
                        'page' => $products->currentPage()]) }}">
                        Nama {!! $sortField === 'name' ? ($sortDirection === 'asc' ? '↑' : '↓') : '' !!}
                        </a></th>
                        <th><a class="sort-link" href="{{ route('masterdata.produk.index', [
                        'sort' => 'category_id',
                        'direction' => ($sortField === 'category_id' && $sortDirection === 'asc') ? 'desc' : 'asc',
                        'page' => $products->currentPage()]) }}">
                        Kategori {!! $sortField === 'category_id' ? ($sortDirection === 'asc' ? '↑' : '↓') : '' !!}
                        </a></th>
                        <th><a class="sort-link" href="{{ route('masterdata.produk.index', [
                        'sort' => 'size_id',
                        'direction' => ($sortField === 'size_id' && $sortDirection === 'asc') ? 'desc' : 'asc',
                        'page' => $products->currentPage()]) }}">
                        Ukuran {!! $sortField === 'size_id' ? ($sortDirection === 'asc' ? '↑' : '↓') : '' !!}
                        </a></th>
                        <th><a class="sort-link" href="{{ route('masterdata.produk.index', [
                        'sort' => 'unit',
                        'direction' => ($sortField === 'unit' && $sortDirection === 'asc') ? 'desc' : 'asc',
                        'page' => $products->currentPage()]) }}">
                        Unit {!! $sortField === 'unit' ? ($sortDirection === 'asc' ? '↑' : '↓') : '' !!}
                        </a></th>
                        <th><a class="sort-link" href="{{ route('masterdata.produk.index', [
                        'sort' => 'min_stock',
                        'direction' => ($sortField === 'min_stock' && $sortDirection === 'asc') ? 'desc' : 'asc',
                        'page' => $products->currentPage()]) }}">
                        Min Stock {!! $sortField === 'min_stock' ? ($sortDirection === 'asc' ? '↑' : '↓') : '' !!}
                        </a></th>
                        <th><a class="sort-link" href="{{ route('masterdata.produk.index', [
                        'sort' => 'is_active',
                        'direction' => ($sortField === 'is_active' && $sortDirection === 'asc') ? 'desc' : 'asc',
                        'page' => $products->currentPage()]) }}">
                        Status {!! $sortField === 'is_active' ? ($sortDirection === 'asc' ? '↑' : '↓') : '' !!}
                        </a></th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $key => $product)
                        <tr>
                            <td>{{ $products->firstItem() + $key }}</td>
                            <td>{{ $product->sku }}</td>
                            <td>{{ $product->name }}</td>
                            <td>{{ $product->category->name ?? '-' }}</td>
                            <td>{{ $product->size->name ?? '-' }}</td>
                            <td>{{ $product->unit }}</td>
                            <td>{{ $product->min_stock }}</td>
                            <td>
                                @if($product->is_active)
                                    <span class="badge bg-success">Aktif</span>
                                @else
                                    <span class="badge bg-secondary">Tidak Aktif</span>
                                @endif
                            </td>
                            <td>
                                <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editModal{{ $product->id }}">
                                    <i class="fa fa-edit"></i> Edit
                                </button>
                                <form action="{{ route('masterdata.produk.destroy', $product->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus produk ini?')">
                                        <i class="fa fa-trash"></i> Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>

                        <!-- Edit Modal -->
                        <div class="modal fade" id="editModal{{ $product->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit Produk</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="{{ route('masterdata.produk.update', $product->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label for="sku" class="form-label">SKU</label>
                                                <input type="text" class="form-control" id="sku" name="sku" value="{{ $product->sku }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="name" class="form-label">Nama Produk</label>
                                                <input type="text" class="form-control" id="name" name="name" value="{{ $product->name }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="category_id" class="form-label">Kategori</label>
                                                <select class="form-select" id="category_id" name="category_id" required>
                                                    @foreach($categories as $category)
                                                        <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label for="size_id" class="form-label">Ukuran</label>
                                                <select class="form-select" id="size_id" name="size_id" required>
                                                    @foreach($sizes as $size)
                                                        <option value="{{ $size->id }}" {{ $product->size_id == $size->id ? 'selected' : '' }}>{{ $size->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label for="unit" class="form-label">Unit</label>
                                                <input type="text" class="form-control" id="unit" name="unit" value="{{ $product->unit }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="min_stock" class="form-label">Min Stock</label>
                                                <input type="number" class="form-control" id="min_stock" name="min_stock" value="{{ $product->min_stock }}" required>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-primary">Perbarui</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center">Tidak ada data produk.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            
            @if($products->hasPages())
            <div class="pagination-wrapper mt-3">
                {{ $products->links('components.custom-pagination') }}
            </div>
            <div class="pagination-info">Menampilkan {{ $products->firstItem() }} sampai {{ $products->lastItem() }} dari {{ $products->total() }} data</div>
            @endif
        </div>
    </div>

    <div class="mt-3">
        <a href="{{ route('dashboard') }}" class="btn btn-secondary">
            <i class="fa fa-arrow-left"></i> Kembali ke Dashboard
        </a>
    </div>
</div>

<!-- Create Modal -->
<div class="modal fade" id="createModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Produk</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('masterdata.produk.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Produk</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="category_id" class="form-label">Kategori</label>
                        <select class="form-select @error('category_id') is-invalid @enderror" id="category_id" name="category_id" required>
                            <option value="">Pilih Kategori</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="size_id" class="form-label">Ukuran</label>
                        <select class="form-select @error('size_id') is-invalid @enderror" id="size_id" name="size_id" required>
                            <option value="">Pilih Ukuran</option>
                            @foreach($sizes as $size)
                                <option value="{{ $size->id }}">{{ $size->name }}</option>
                            @endforeach
                        </select>
                        @error('size_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="unit" class="form-label">Unit</label>
                        <input type="text" class="form-control @error('unit') is-invalid @enderror" id="unit" name="unit" value="{{ old('unit') }}" required placeholder="Contoh: pcs, box">
                        @error('unit')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="min_stock" class="form-label">Min Stock</label>
                        <input type="number" class="form-control @error('min_stock') is-invalid @enderror" id="min_stock" name="min_stock" value="{{ old('min_stock', 0) }}" required>
                        @error('min_stock')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
