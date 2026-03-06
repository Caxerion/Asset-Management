@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Tambah Produk</h1>
    <form action="{{ route('products.store') }}" method="POST">
        @include('products.form')
    </form>
</div>
@endsection