@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fa fa-layer-group" style="margin-right: 8px;"></i>Data Lantai</h2>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">
            <i class="fa fa-plus"></i> Tambah Lantai
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
                        <th>Nama Lantai</th>
                        <th>Jumlah Stock Balance</th>
                        <th>Jumlah Pickup</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($floors as $key => $floor)
                        <tr>
                            <td>{{ $floors->firstItem() + $key }}</td>
                            <td>{{ $floor->name }}</td>
                            <td>{{ $floor->stockBalances()->count() }}</td>
                            <td>{{ $floor->pickups()->count() }}</td>
                            <td>
                                <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editModal{{ $floor->id }}">
                                    <i class="fa fa-edit"></i> Edit
                                </button>
                                <form action="{{ route('masterdata.lantai.destroy', $floor->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus lantai ini?')">
                                        <i class="fa fa-trash"></i> Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>

                        <!-- Edit Modal -->
                        <div class="modal fade" id="editModal{{ $floor->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit Lantai</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="{{ route('masterdata.lantai.update', $floor->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label for="name" class="form-label">Nama Lantai</label>
                                                <input type="text" class="form-control" id="name" name="name" value="{{ $floor->name }}" required>
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
                            <td colspan="5" class="text-center">Tidak ada data lantai.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            
            @if($floors->hasPages())
            <div class="pagination-wrapper mt-3">
                @if($floors->onFirstPage())
                <span class="page-link disabled">‹</span>
                @else
                <a href="{{ $floors->previousPageUrl() }}" class="page-link">‹</a>
                @endif
                @foreach($floors->getUrlRange(1, $floors->lastPage()) as $page => $url)
                @if($page == $floors->currentPage())
                <span class="page-link active">{{ $page }}</span>
                @else
                <a href="{{ $url }}" class="page-link">{{ $page }}</a>
                @endif
                @endforeach
                @if($floors->hasMorePages())
                <a href="{{ $floors->nextPageUrl() }}" class="page-link">›</a>
                @else
                <span class="page-link disabled">›</span>
                @endif
            </div>
            <div class="pagination-info">Menampilkan {{ $floors->firstItem() }} sampai {{ $floors->lastItem() }} dari {{ $floors->total() }} data</div>
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
                <h5 class="modal-title">Tambah Lantai</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('masterdata.lantai.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Lantai</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required placeholder="Contoh: Lantai 1, Lantai 2">
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
