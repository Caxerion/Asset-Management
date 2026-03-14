@extends('layouts.app')

@section('content')
<div class="container">
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
            <form method="GET" action="{{ route('person.index') }}" class="d-flex gap-2">
                <input type="text" name="q" placeholder="Cari Nama CS..." 
                       class="form-control form-control-sm" style="width: 350px; border-radius: 10px; padding-bottom: 10px; padding-top: 10px;" value="{{ request('q') }}">
                <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-search"></i></button>
                @if(request('q'))
                    <a href="{{ route('person.index') }}" class="btn btn-outline-secondary btn-sm">Reset</a>
                @endif
            </form>
        </div>
    <div class="card">
        <div class="card-body">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th><a class="sort-link" href="{{ route('person.index', [
                        'sort' => 'name',
                        'direction' => ($sortField === 'name' && $sortDirection === 'asc') ? 'desc' : 'asc',
                        'page' => $people->currentPage()]) }}">
                        Nama CS {!! $sortField === 'name' ? ($sortDirection === 'asc' ? '↑' : '↓') : '' !!}
                        </a></th>
                        <th><a class="sort-link" href="{{ route('person.index', [
                        'sort' => 'is_active',
                        'direction' => ($sortField === 'is_active' && $sortDirection === 'asc') ? 'desc' : 'asc',
                        'page' => $people->currentPage()]) }}">
                        Status {!! $sortField === 'is_active' ? ($sortDirection === 'asc' ? '↑' : '↓') : '' !!}
                        </a></th>
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
