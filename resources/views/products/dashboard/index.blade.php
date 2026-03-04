@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Persediaan</h1>

    {{-- Tab menu sederhana --}}
    <ul class="nav nav-tabs mb-3">
        <li class="nav-item">
            <a class="nav-link active" href="{{ route('persediaan.index') }}">Histori Pengambilan</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('stock.index') }}">Stock Barang</a>
        </li>
    </ul>

    {{-- Search + tombol catat pengambilan --}}
    <div class="d-flex justify-content-between mb-3">
        <form method="GET" action="{{ route('persediaan.index') }}" class="flex-grow-1 me-2">
            <input type="text" name="q" placeholder="Cari Histori Pengambilan Tanggal/Nama/Barang" 
                   class="form-control" value="{{ request('q') }}">
        </form>
        <a href="{{ route('persediaan.create') }}" class="btn btn-primary">+ Catat Pengambilan</a>
    </div>

    {{-- Tabel histori pengambilan --}}
    <table class="table table-bordered">
        <thead>
            <tr>
                <th style="vertical-align: middle;">#</th>
                <th style="vertical-align: middle;">Nama Pengambil Barang</th>
                <th style="vertical-align: middle;">Nama Barang</th>
                <th style="vertical-align: middle;">Tanggal Pengambilan</th>
                <th style="vertical-align: middle;">Untuk Lantai?</th>
                <th style="vertical-align: middle;">Lihat Detil</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pickups as $pickup)
                <tr>
                    <td>{{ $pickup->id }}</td>
                    <td>{{ $pickup->user->name }} ({{ $pickup->user->email }})</td>
                    <td>
                        @foreach($pickup->items as $line)
                            {{ $line->product->name }} ({{ $line->qty }})<br>
                        @endforeach
                    </td>
                    <td>{{ $pickup->created_at->format('d M Y H:i') }}</td>
                    <td>{{ $pickup->floor->name }}</td>
                    <td>
                        <a href="{{ route('persediaan.show', $pickup->id) }}" class="btn btn-sm btn-info">Detil</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">Belum ada histori pengambilan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Pagination --}}
    <div class="mt-3">
        {{ $pickups->links() }}
    </div>
</div>
@endsection