@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fa fa-tags" style="margin-right: 8px;"></i>Data Kategori</h2>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">
            <i class="fa fa-plus"></i> Tambah Kategori
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
                        <th>Nama Kategori</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $key => $category)
                        <tr>
                            <td>{{ $categories->firstItem() + $key }}</td>
                            <td>{{ $category->name }}</td>
                            <td>
                                @if($category->is_active)
                                    <span class="badge bg-success">Aktif</span>
                                @else
                                    <span class="badge bg-secondary">Tidak Aktif</span>
                                @endif
                            </td>
                            <td>
                                <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editModal{{ $category->id }}">
                                    <i class="fa fa-edit"></i> Edit
                                </button>
                                <form action="{{ route('masterdata.kategori.destroy', $category->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus kategori ini?')">
                                        <i class="fa fa-trash"></i> Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>

                        <!-- Edit Modal -->
                        <div class="modal fade" id="editModal{{ $category->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit Kategori</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="{{ route('masterdata.kategori.update', $category->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label for="name" class="form-label">Nama Kategori</label>
                                                <input type="text" class="form-control" id="name" name="name" value="{{ $category->name }}" required>
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
                            <td colspan="4" class="text-center">Tidak ada data kategori.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            
            @if($categories->hasPages())
            <div class="pagination-wrapper mt-3">
                @if($categories->onFirstPage())
                <span class="page-link disabled">‹</span>
                @else
                <a href="{{ $categories->previousPageUrl() }}" class="page-link">‹</a>
                @endif
                @foreach($categories->getUrlRange(1, $categories->lastPage()) as $page => $url)
                @if($page == $categories->currentPage())
                <span class="page-link active">{{ $page }}</span>
                @else
                <a href="{{ $url }}" class="page-link">{{ $page }}</a>
                @endif
                @endforeach
                @if($categories->hasMorePages())
                <a href="{{ $categories->nextPageUrl() }}" class="page-link">›</a>
                @else
                <span class="page-link disabled">›</span>
                @endif
            </div>
            <div class="pagination-info">Menampilkan {{ $categories->firstItem() }} sampai {{ $categories->lastItem() }} dari {{ $categories->total() }} data</div>
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
                <h5 class="modal-title">Tambah Kategori</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('masterdata.kategori.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Kategori</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
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
