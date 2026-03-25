@extends('layouts.app')

@section('content')
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
        <h2><i class="fa fa-tags" style="margin-right: 8px;"></i>Data Kategori</h2>
        <div class="d-flex gap-2">
            <select name="sort" id="sortSelect" class="form-select" style="width: 180px;">
                <option value="name_asc" {{ $sortField === 'name' && $sortDirection === 'asc' ? 'selected' : '' }}>Nama (A-Z)</option>
                <option value="name_desc" {{ $sortField === 'name' && $sortDirection === 'desc' ? 'selected' : '' }}>Nama (Z-A)</option>
                <option value="id_asc" {{ $sortField === 'id' && $sortDirection === 'asc' ? 'selected' : '' }}>ID (Terlama)</option>
                <option value="id_desc" {{ $sortField === 'id' && $sortDirection === 'desc' ? 'selected' : '' }}>ID (Terbaru)</option>
            </select>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">
                <i class="fa fa-plus"></i> Tambah Kategori
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
                {{ $categories->links('components.custom-pagination') }}
            </div>
            <div class="pagination-info">Menampilkan {{ $categories->firstItem() }} sampai {{ $categories->lastItem() }} dari {{ $categories->total() }} data</div>
            @endif
        </div>
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
