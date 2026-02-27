@csrf
<div class="mb-3">
    <label for="sku">SKU</label>
    <input type="text" id="sku" name="sku" class="form-control"
           value="{{ old('sku', $product->sku ?? '') }}">
</div>

<div class="mb-3">
    <label for="name">Nama Produk</label>
    <input type="text" id="name" name="name" class="form-control"
           value="{{ old('name', $product->name ?? '') }}">
</div>

<div class="mb-3">
    <label for="category_id">Kategori</label>
    <select id="category_id" name="category_id" class="form-control">
        @foreach($categories as $category)
            <option value="{{ $category->id }}"
                {{ (old('category_id', $product->category_id ?? '') == $category->id) ? 'selected' : '' }}>
                {{ $category->name }}
            </option>
        @endforeach
    </select>
</div>

<div class="mb-3">
    <label for="size_id">Ukuran</label>
    <select id="size_id" name="size_id" class="form-control">
        @foreach($sizes as $size)
            <option value="{{ $size->id }}"
                {{ (old('size_id