@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Detail Produk</h1>

    <div class="card">
        <div class="card-body">
            <p><strong>SKU:</strong> {{ $product->sku }}</p>
            <p><strong>Nama:</strong> {{ $product->name }}</p>
            <p><strong>Kategori:</strong> {{ $product->category->name }}</p>
            <p><strong>Ukuran:</strong> {{ $product->size->name }}</p>
            <p><strong>Stok:</strong> {{ $product->stockBalances->sum('qty_on_hand') }}</p>
        </div>
    </div>

    <a href="{{ route('products.index') }}" class="btn btn-secondary mt-3">Kembali ke Daftar Produk</a>
</div>
@endsection