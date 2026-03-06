@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Master Data</h1>

    {{-- Kategori Barang --}}
    <div class="card mb-4">
        <div class="card-header">Kategori Barang</div>
        <div class="card-body">
            @if($categories->count())
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama Kategori</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($categories as $category)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $category->name }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p>Belum ada data kategori.</p>
            @endif
        </div>
    </div>

    {{-- Data Lantai --}}
    <div class="card mb-4">
        <div class="card-header">Data Lantai</div>
        <div class="card-body">
            @if($floors->count())
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama Lantai</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($floors as $floor)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $floor->name }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p>Belum ada data lantai.</p>
            @endif
        </div>
    </div>

</div>
@endsection
