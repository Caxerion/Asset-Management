@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Daftar Produk</h1>
    <a href="{{ route('products.create') }}" class="btn btn-primary mb-3">Tambah Produk</a>
    <table class="table">
        <thead>
            <tr>
                <th>SKU</th>
                <th><a class="sort-link" href="{{ route('products.index', [
                        'sort' => 'requested_by',
                        'direction' => ($sortField === 'requested_by' && $sortDirection === 'asc') ? 'desc' : 'asc']) }}">
                        NAMA {!! $sortField === 'requested_by' ? ($sortDirection === 'asc' ? '↑' : '↓') : '' !!}
                </a></th>
                <th><a class="sort-link" href="{{ route('products.index', [
                        'sort' => 'category_id',
                        'direction' => ($sortField === 'category_id' && $sortDirection === 'asc') ? 'desc' : 'asc']) }}">
                        KATEGORI {!! $sortField === 'category_id' ? ($sortDirection === 'asc' ? '↑' : '↓') : '' !!}
                    </a></th>
                <th><a class="sort-link" href="{{ route('products.index', [
                        'sort' => 'size_id',
                        'direction' => ($sortField === 'size_id' && $sortDirection === 'asc') ? 'desc' : 'asc']) }}">
                        UKURAN {!! $sortField === 'size_id' ? ($sortDirection === 'asc' ? '↑' : '↓') : '' !!}
                    </a></th>
                <th><a class="sort-link" href="{{ route('products.index', [
                        'sort' => 'stock',
                        'direction' => ($sortField === 'stock' && $sortDirection === 'asc') ? 'desc' : 'asc']) }}">
                        STOK {!! $sortField === 'stock' ? ($sortDirection === 'asc' ? '↑' : '↓') : '' !!}
                    </a></th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $product)
            <tr>
                <td>{{ $product->sku }}</td>
                <td>{{ $product->name }}</td>
                <td>{{ $product->category->name }}</td>
                <td>{{ $product->size->name }}</td>
                <td>{{ $product->stockBalances->sum('qty_on_hand') }}</td>
                <td>
                    <a href="{{ route('products.show', $product) }}" class="btn btn-info btn-sm">Detail</a>
                    <a href="{{ route('products.edit', $product) }}" class="btn btn-warning btn-sm">Edit</a>
                    <form action="{{ route('products.destroy', $product) }}" method="POST" style="display:inline;">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                    </form>
                </td>
            </tr>
