@extends('layouts.app')

@section('head')
<!-- Materialize CSS -->
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
<style>
    .materialize-redesign { margin-top: 20px; }
    .materialize-redesign .card { border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1) !important; }
    .materialize-redesign .card-title { margin-bottom: 20px; font-size: 24px !important; font-weight: bold !important; }
    .materialize-redesign .input-field label { font-size: 14px; }
    .materialize-redesign table.highlight > tbody > tr:hover { background-color: #f5f5f5; }
    .materialize-redesign .btn { border-radius: 4px; }
    .materialize-redesign .btn-floating.btn-small { width: 32px; height: 32px; }
    .materialize-redesign .btn-floating.btn-small i { line-height: 32px; }
    .materialize-redesign select.browser-default { 
        border: 1px solid #ccc; 
        padding: 5px; 
        border-radius: 3px;
        height: 40px;
    }
    .materialize-redesign .card-panel { border-radius: 4px; }
    .materialize-redesign .ml-2 { margin-left: 10px; }
</style>
@endsection

@section('content')
<div class="materialize-redesign">
    <div class="container">
        <div class="row">
            <div class="col s12 m10 offset-m1">
                <div class="card">
                    <div class="card-content">
                        <span class="card-title center-align blue-text text-darken-4">
                            Form Pengambilan Barang
                        </span>
                        
                        <form action="{{ route('persediaan.store') }}" method="POST" id="pickupForm">
                            @csrf
                            
                            <div class="row">
                                <div class="col s12 m6">
                                    <div class="input-field">
                                        <select name="user_id" id="user_id" required>
                                            <option value="" disabled selected>-- Pilih Pengguna --</option>
                                            @foreach($users as $user)
                                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                                            @endforeach
                                        </select>
                                        <label for="user_id">Nama Pengambil</label>
                                    </div>
                                </div>
                                <div class="col s12 m6">
                                    <div class="input-field">
                                        <select name="floor_id" id="floor_id" required>
                                            <option value="" disabled selected>-- Pilih Lantai --</option>
                                            @foreach($floors as $floor)
                                                <option value="{{ $floor->id }}">{{ $floor->name }}</option>
                                            @endforeach
                                        </select>
                                        <label for="floor_id">Lantai / Lokasi</label>
                                    </div>
                                </div>
                            </div>
                                <div class="col s12 m6">
                                    <div class="input-field">
                                        <input type="date" name="pickup_date" id="pickup_date" value="{{ date(l, 'Y-m-d') }}" required>
                                        <label for="pickup_date" class="active">Tanggal Pengambilan</label>
                                    </div>
                                </div>
                            </div>

                            <div class="row" style="margin-bottom: 10px;">
                                <div class="col s12">
                                    <a class="btn-floating btn-small waves-effect waves-light green" id="addItem" title="Tambah Barang">
                                        <i class="material-icons">add</i>
                                    </a>
                                    <span class="ml-2" style="vertical-align: middle; font-weight: 500;">Daftar Barang</span>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col s12">
                                    <table class="highlight responsive-table" id="itemsTable">
                                        <thead>
                                            <tr>
                                                <th class="center-align" style="width: 50%;">Nama Barang</th>
                                                <th class="center-align" style="width: 30%;">Jumlah</th>
                                                <th class="center-align" style="width: 20%;">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody id="itemsBody">
                                            <tr class="item-row">
                                                <td>
                                                    <select name="items[0][product_id]" class="browser-default product-select" required>
                                                        <option value="" disabled selected>-- Pilih Barang --</option>
                                                        @foreach($products as $product)
                                                            <option value="{{ $product->id }}">{{ $product->name }} ({{ $product->category->name ?? '-' }})</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td>
                                                    <div class="input-field" style="margin: 0;">
                                                        <input type="number" name="items[0][qty]" class="center-align" min="1" value="1" required>
                                                    </div>
                                                </td>
                                                <td class="center-align">
                                                    <a class="btn-floating btn-small waves-effect red lighten-2 remove-item" title="Hapus">
                                                        <i class="material-icons">delete</i>
                                                    </a>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            @if($products->isEmpty())
                                <div class="card-panel yellow lighten-4 yellow-text text-darken-4">
                                    <i class="material-icons">warning</i> Tidak ada barang tersedia. Silakan tambah barang di Master Data terlebih dahulu.
                                </div>
                            @endif

                            <div class="row">
                                <div class="col s12">
                                    <div class="input-field">
                                        <textarea name="notes" id="notes" class="materialize-textarea" placeholder="Tambahkan catatan jika diperlukan..."></textarea>
                                        <label for="notes">Catatan (Opsional)</label>
                                    </div>
                                </div>
                            </div>

                            <div class="row" style="margin-top: 30px;">
                                <div class="col s6">
                                    <a href="{{ route('persediaan.index') }}" class="btn waves-effect waves-light grey">
                                        <i class="material-icons left">arrow_back</i> Kembali
                                    </a>
                                </div>
                                <div class="col s6 right-align">
                                    <button type="submit" class="btn waves-effect waves-light blue darken-4" {{ $products->isEmpty() ? 'disabled' : '' }}>
                                        <i class="material-icons left">save</i> Simpan
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Materialize JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize materialize select
    var elems = document.querySelectorAll('select');
    var instances = M.FormSelect.init(elems);
    
    let itemIndex = 1;
    const itemsBody = document.getElementById('itemsBody');
    const addItemBtn = document.getElementById('addItem');

    // Add new item row
    addItemBtn.addEventListener('click', function() {
        const newRow = document.createElement('tr');
        newRow.className = 'item-row';
        newRow.innerHTML = `
            <td>
                <select name="items[${itemIndex}][product_id]" class="browser-default product-select" required>
                    <option value="" disabled selected>-- Pilih Barang --</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}">{{ $product->name }} ({{ $product->category->name ?? '-' }})</option>
                    @endforeach
                </select>
            </td>
            <td>
                <div class="input-field" style="margin: 0;">
                    <input type="number" name="items[${itemIndex}][qty]" class="center-align" min="1" value="1" required>
                </div>
            </td>
            <td class="center-align">
                <a class="btn-floating btn-small waves-effect red lighten-2 remove-item" title="Hapus">
                    <i class="material-icons">delete</i>
                </a>
            </td>
        `;
        itemsBody.appendChild(newRow);
        itemIndex++;
    });

    // Remove item row
    itemsBody.addEventListener('click', function(e) {
        if (e.target.closest('.remove-item')) {
            const row = e.target.closest('tr');
            if (itemsBody.querySelectorAll('.item-row').length > 1) {
                row.remove();
            } else {
                alert('Minimal harus ada satu barang!');
            }
        }
    });

    // Form validation
    document.getElementById('pickupForm').addEventListener('submit', function(e) {
        const rows = itemsBody.querySelectorAll('.item-row');
        let hasValidItems = false;
        
        rows.forEach(row => {
            const productSelect = row.querySelector('.product-select');
            const qtyInput = row.querySelector('input[type="number"]');
            if (productSelect.value && qtyInput.value > 0) {
                hasValidItems = true;
            }
        });

        if (!hasValidItems) {
            e.preventDefault();
            alert('Pilih minimal satu barang dengan jumlah yang valid!');
        }
    });
});
</script>
@endsection
