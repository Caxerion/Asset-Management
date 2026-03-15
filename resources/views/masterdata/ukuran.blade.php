@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fa fa-tape" style="margin-right: 8px;"></i>Data Ukuran</h2>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">
            <i class="fa fa-plus"></i> Tambah Ukuran
        </button>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card">
        <div class="card-body">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th><a class="sort-link" href="{{ route('masterdata.ukuran.index', [
                        'sort' => 'name',
                        'direction' => ($sortField === 'name' && $sortDirection === 'asc') ? 'desc' : 'asc',
                        'page' => $sizes->currentPage()]) }}">
                        Nama Ukuran {!! $sortField === 'name' ? ($sortDirection === 'asc' ? '↑' : '↓') : '' !!}
                        </a></th>
                        <th><a href="{{ route('masterdata.ukuran.index', ['sort' => 'id', 'direction' => ($sortField === 'id' && $sortDirection === 'asc') ? 'desc' : 'asc', 'page' => $sizes->currentPage()]) }}">
                        Jumlah Produk
                        {!! $sortField === 'id' ? ($sortDirection === 'asc' ? '↑' : '↓') : '' !!}
                        </a> </th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sizes as $key => $size)
                        <tr>
                            <td>{{ $sizes->firstItem() + $key }}</td>
                            <td>{{ $size->name }}</td>
                            <td>{{ $size->products()->count() }}</td>
                            <td>
                                <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editModal{{ $size->id }}">
                                    <i class="fa fa-edit"></i> Edit
                                </button>
                                <form action="{{ route('masterdata.ukuran.destroy', $size->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus ukuran ini?')">
                                        <i class="fa fa-trash"></i> Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>

                        <!-- Edit Modal -->
                        <div class="modal fade" id="editModal{{ $size->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit Ukuran</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="{{ route('masterdata.ukuran.update', $size->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label for="name" class="form-label">Nama Ukuran</label>
                                                <input type="text" class="form-control" id="name" name="name" value="{{ $size->name }}" required>
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
                            <td colspan="4" class="text-center">Tidak ada data ukuran.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            
            @if($sizes->hasPages())
            <div class="pagination-wrapper mt-3">
                {{ $sizes->links('components.custom-pagination') }}
            </div>
            <div class="pagination-info">Menampilkan {{ $sizes->firstItem() }} sampai {{ $sizes->lastItem() }} dari {{ $sizes->total() }} data</div>
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
                <h5 class="modal-title">Tambah Ukuran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('masterdata.ukuran.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Ukuran</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required placeholder="isi ukuran">
                        @error('name')
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
