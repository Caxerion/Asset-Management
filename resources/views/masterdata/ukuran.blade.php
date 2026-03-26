@extends('layouts.app')

@section('content')
<style>
    .masterdata-table th {
        border-bottom: 1px solid #dee2e6 !important;
    }
    .masterdata-table td {
        border-left: none !important;
        border-right: none !important;
    }
    .masterdata-table thead th {
        border-left: 1px solid #dee2e6 !important;
        border-right: 1px solid #dee2e6 !important;
    }
    .masterdata-table thead th:first-child {
        border-left: none !important;
    }
    .masterdata-table thead th:last-child {
        border-right: none !important;
    }
</style>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const sortSelect = document.getElementById('sortSelect');
    if (sortSelect) {
        sortSelect.addEventListener('change', function() {
            const sortValue = this.value;
            const currentUrl = new URL(window.location.href);
            currentUrl.searchParams.set('sort', sortValue);
            window.location.href = currentUrl.toString();
        });
    }
});
</script>
<div class="container" style="margin-top: 40px;">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fa fa-tape" style="margin-right: 8px;"></i>Data Ukuran</h2>
        <div class="d-flex gap-2">
            <select name="sort" id="sortSelect" class="form-select" style="width: 200px;">
                <option value="default" {{ $sortField === 'default' ? 'selected' : '' }}>Default</option>
                <option value="kg_desc" {{ $sortField === 'kg' && $sortDirection === 'desc' ? 'selected' : '' }}>kg</option>
                <option value="g_desc" {{ $sortField === 'g' && $sortDirection === 'desc' ? 'selected' : '' }}>g</option>
                <option value="ml_desc" {{ $sortField === 'ml' && $sortDirection === 'desc' ? 'selected' : '' }}>ml</option>
                <option value="L_desc" {{ $sortField === 'L' && $sortDirection === 'desc' ? 'selected' : '' }}>L</option>
                <option value="cm_desc" {{ $sortField === 'cm' && $sortDirection === 'desc' ? 'selected' : '' }}>cm</option>
                <option value="inch_desc" {{ $sortField === 'inch' && $sortDirection === 'desc' ? 'selected' : '' }}>inch</option>
                <option value="items_desc" {{ $sortField === 'items' && $sortDirection === 'desc' ? 'selected' : '' }}>items</option>
            </select>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">
                <i class="fa fa-plus"></i> Tambah Ukuran
            </button>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif


    <div class="card">
        <div class="card-body p-3">
            <table class="table table-bordered masterdata-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Ukuran</th>
                        <th>Jumlah Produk</th>
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
                                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editModal{{ $size->id }}">
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
            @endif
        </div>
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
