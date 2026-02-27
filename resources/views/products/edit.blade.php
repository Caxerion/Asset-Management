@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Produk</h1>
    <form action="{{ route('products.update', $product) }}" method="POST">
        @method('PUT')
        @include('products.form')
    </form>
</div>
@endsection