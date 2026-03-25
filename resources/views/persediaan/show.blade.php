@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Detail Pengambilan Barang</h1>

    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Pickup No: {{ $pickup->pickup_no }}</h5>
            <p><strong>Nama Pengambil:</strong> {{ $pickup->user->name }}</p>
            <p><strong>Tanggal Pengambilan:</strong> {{ $pickup->created_at->format('d M Y H:i') }}</p>
            <p><strong>Lantai:</strong> {{ $pickup->floor->name }}</p>
        </div>
    </div>

    <h3 class="mt-4">Daftar Barang</h3>
    <table class="table table-bordered table-striped mb-0 histori-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Kode Barang</th>
                <th>Nama Barang</th>
                <th>Jumlah</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pickup->items as $index => $item)
                <tr>
                    <td>#{{ $index + 1 }}</td>
                    <td>{{ $item->product->sku ?? '-' }}</td>
                    <td>{{ $item->product->name }}</td>
                    <td>{{ $item->qty }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <a href="{{ route('persediaan.index') }}" class="btn btn-secondary">Kembali</a>
    
    <form action="{{ route('persediaan.destroy', $pickup->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data pengambilan barang ini? Stock akan dikembalikan.')">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger">Hapus</button>
    </form>
</div>
@endsection
