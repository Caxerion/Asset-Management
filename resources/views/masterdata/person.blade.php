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
        <h2><i class="fa fa-users" style="margin-right: 8px;"></i>Data Orang</h2>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">
            <i class="fa fa-plus"></i> Tambah Orang
        </button>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-header bg-white py-2 d-flex justify-content-between align-items-center">
            <div class="d-flex gap-2">
                <select name="sort" id="sortSelect" class="form-select form-select-sm" style="width: 180px;">
                    <option value="name_asc" {{ $sortField === 'name' && $sortDirection === 'asc' ? 'selected' : '' }}>Nama (A-Z)</option>
                    <option value="name_desc" {{ $sortField === 'name' && $sortDirection === 'desc' ? 'selected' : '' }}>Nama (Z-A)</option>
                    <option value="id_asc" {{ $sortField === 'id' && $sortDirection === 'asc' ? 'selected' : '' }}>ID (Terlama)</option>
                    <option value="id_desc" {{ $sortField === 'id' && $sortDirection === 'desc' ? 'selected' : '' }}>ID (Terbaru)</option>
                </select>
            </div>
        </div>
    <div class="card">
        <div class="card-body p-3">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama CS</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($people as $key => $person)
                        <tr>
                            <td>{{ $people->firstItem() + $key }}</td>
                            <td>{{ $person->name }}</td>
                            <td>
                                @if($person->is_active)
                                    <span class="badge bg-success">Aktif</span>
                                @else
                                    <span class="badge bg-secondary">Tidak Aktif</span>
                                @endif
                            </td>
                            <td>
                                <!-- contoh tombol edit & hapus -->
                                <form action="{{ route('person.destroy', $person->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger"
                                        onclick="return confirm('Apakah Anda yakin ingin menghapus orang ini?')">
                                        <i class="fa fa-trash"></i> Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">Tidak ada data orang.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{ $people->links('components.custom-pagination') }}
        </div>
    </div>
</div>

<!-- Modal Tambah Orang -->
<div class="modal fade" id="createModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('person.store') }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Tambah Orang</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <label for="name" class="form-label">Nama Orang</label>
                <input type="text" class="form-control" name="name" required>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection
