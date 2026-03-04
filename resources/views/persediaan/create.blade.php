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
    .materialize-redesign .ml-3 { margin-left: 15px; }
    .materialize-redesign .mr-2 { margin-right: 10px; }
    
    /* Custom styles for the new form layout */
    .form-title {
        text-align: center;
        font-size: 28px !important;
        font-weight: bold !important;
        color: #1565c0 !important;
        margin-bottom: 30px !important;
    }
    
    .info-section {
        background-color: #f5f5f5;
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 20px;
    }
    
    .info-section .input-field {
        margin-bottom: 0;
    }
    
    .info-label {
        font-weight: 600;
        color: #333;
        margin-bottom: 5px;
        display: block;
    }
    
    .divider-line {
        height: 2px;
        background-color: #ddd;
        margin: 20px 0;
    }
    
    .product-selection-section {
        background-color: #fff;
        padding: 15px;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
    }
    
    .product-row {
        display: flex;
        align-items: center;
        gap: 15px;
        flex-wrap: wrap;
    }
    
    .product-row > div {
        flex: 1;
        min-width: 150px;
    }
    
    .vertical-divider {
        width: 2px;
        height: 50px;
        background-color: #ccc;
        margin: 0 10px;
    }
    
    .stock-display {
        background-color: #e3f2fd;
        padding: 10px;
        border-radius: 4px;
        text-align: center;
        font-weight: bold;
        color: #1565c0;
    }
    
    .qty-input {
        text-align: center;
        font-weight: bold;
    }
    
    .table-container {
        margin-top: 20px;
    }
    
    .notes-section {
        margin-top: 20px;
    }
    
    .action-buttons {
        margin-top: 30px;
        display: flex;
        justify-content: space-between;
    }
    
    .inline-select {
        width: 100%;
    }
</style>
@endsection

@section('content')
<div class="materialize-redesign">
    <div class="container">
        <div class="row">
            <div class="col s12 m10 offset-m1">
                <div class="card">
                    <div class="card-content">
                        <!-- Title - Centered at top -->
                        <h5 class="form-title">Form Pengambilan Barang</h5>
                        
                        <form action="{{ route('persediaan.store') }}" method="POST" id="pickupForm">
                            @csrf
                            
                            <!-- Informasi Section (Left side) -->
                            <div class="info-section">
                                <div class="row" style="margin-bottom: 0;">
                                    <div class="col s12 m6">
                                        <label class="info-label">Pengambilan untuk siapa?</label>
                                        <select name="user_id" id="user_id" class="browser-default" required>
                                            <option value="" disabled selected>-- Pilih Pengguna --</option>
                                            @foreach($users as $user)
                                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col s12 m6">
                                        <label class="info-label">Pengambilan untuk lantai?</label>
                                        <select name="floor_id" id="floor_id" class="browser-default" required>
                                            <option value="" disabled selected>-- Pilih Lantai --</option>
                                            @foreach($floors as $floor)
                                                <option value="{{ $floor->id }}">{{ $floor->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Horizontal Line -->
                            <div class="divider-line"></div>
                            
                            <!-- Product Selection Section -->
                            <div class="product-selection-section">
                                <div class="product-row">
                                    <!-- Cari & Pilih Barang -->
                                    <div style="flex: 2; min-width: 250px;">
                                        <label class="info-label">Cari & pilih barang</label>
                                        <select name="product_id" id="productSelect" class="browser-default inline-select">
                                            <option value="" disabled selected>-- Pilih Barang --</option>
                                            @foreach($products as $product)
                                                <option value="{{ $product->id }}" 
                                                        data-size="{{ $product->size->name ?? '-' }}"
                                                        data-stock="{{ $product->stockBalances->sum('qty_on_hand') }}">
                                                    {{ $product->name }} ({{ $product->category->name ?? '-' }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    
                                    <!-- Ukuran Barang -->
                                    <div>
                                        <label class="info-label">Ukuran Barang</label>
                                        <div class="stock-display" id="sizeDisplay">-</div>
                                    </div>
                                    
                                    <!-- Stock Tersedia -->
                                    <div>
                                        <label class="info-label">Stock tersedia</label>
                                        <div class="stock-display" id="stockDisplay">0</div>
                                    </div>
                                    
                                    <!-- Ambil Sebanyak -->
                                    <div>
                                        <label class="info-label">Ambil sebanyak</label>
                                        <input type="number" id="qtyInput" class="browser-default qty-input" min="1" value="1" style="border: 1px solid #ccc; padding: 8px; border-radius: 4px; height: 42px;">
                                    </div>
                                    
                                    <!-- Vertical Divider -->
                                    <div class="vertical-divider"></div>
                                    
                                    <!-- Tambah Barang Button -->
                                    <div>
                                        <label class="info-label">&nbsp;</label>
                                        <button type="button" id="addItemBtn" class="btn waves-effect waves-light green" style="height: 42px; line-height: 42px;">
                                            <i class="material-icons left">add</i> Tambah Barang
                                        </button>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Horizontal Line -->
                            <div class="divider-line"></div>
                            
                            <!-- Table of Added Items -->
                            <div class="table-container">
                                <table class="highlight responsive-table" id="itemsTable">
                                    <thead>
                                        <tr>
                                            <th class="center-align">No</th>
                                            <th class="center-align">Kode Barang</th>
                                            <th class="center-align">Nama Barang</th>
                                            <th class="center-align">Kategori</th>
                                            <th class="center-align">Ukuran</th>
                                            <th class="center-align">Jumlah</th>
                                            <th class="center-align">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="itemsBody">
                                        <!-- Items will be added here dynamically -->
                                    </tbody>
                                </table>
                                
                                @if($products->isEmpty())
                                    <div class="card-panel yellow lighten-4 yellow-text text-darken-4" style="margin-top: 15px;">
                                        <i class="material-icons">warning</i> Tidak ada barang tersedia. Silakan tambah barang di Master Data terlebih dahulu.
                                    </div>
                                @endif
                            </div>
                            
                            <!-- Catatan (Opsional) -->
                            <div class="notes-section">
                                <div class="row">
                                    <div class="col s12">
                                        <div class="input-field">
                                            <textarea name="notes" id="notes" class="materialize-textarea" placeholder="Tambahkan catatan jika diperlukan..."></textarea>
                                            <label for="notes">Catatan (opsional)</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Action Buttons -->
                            <div class="action-buttons">
                                <div>
                                    <a href="{{ route('persediaan.index') }}" class="btn waves-effect waves-light grey">
                                        <i class="material-icons left">arrow_back</i> Kembali
                                    </a>
                                </div>
                                <div>
                                    <button type="submit" class="btn waves-effect waves-light blue darken-4" id="submitBtn" {{ $products->isEmpty() ? 'disabled' : '' }}>
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
    let itemIndex = 0;
    const itemsBody = document.getElementById('itemsBody');
    const productSelect = document.getElementById('productSelect');
    const sizeDisplay = document.getElementById('sizeDisplay');
    const stockDisplay = document.getElementById('stockDisplay');
    const qtyInput = document.getElementById('qtyInput');
    const addItemBtn = document.getElementById('addItemBtn');
    
    // Product data from server-side
    const products = @json($products);
    
    // Update size and stock when product is selected
    productSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        if (selectedOption.value) {
            sizeDisplay.textContent = selectedOption.dataset.size || '-';
            stockDisplay.textContent = selectedOption.dataset.stock || '0';
        } else {
            sizeDisplay.textContent = '-';
            stockDisplay.textContent = '0';
        }
    });
    
    // Add item to table
    addItemBtn.addEventListener('click', function() {
        const productId = productSelect.value;
        const qty = parseInt(qtyInput.value);
        
        if (!productId) {
            alert('Pilih barang terlebih dahulu!');
            return;
        }
        
        if (!qty || qty < 1) {
            alert('Masukkan jumlah yang valid!');
            return;
        }
        
        // Find product details
        const product = products.find(p => p.id == productId);
        if (!product) return;
        
        const stock = parseInt(product.stock_balances?.reduce((sum, sb) => sum + sb.qty_on_hand, 0) || 0);
        
        // Check stock availability
        // Get existing qty for this product
        const existingItems = itemsBody.querySelectorAll('.item-row');
        let existingQty = 0;
        existingItems.forEach(row => {
            if (row.dataset.productId == productId) {
                existingQty += parseInt(row.dataset.qty);
            }
        });
        
        if ((existingQty + qty) > stock) {
            alert('Jumlah yang diminta melebihi stock tersedia! Stock tersedia: ' + (stock - existingQty));
            return;
        }
        
        // Add row to table
        const newRow = document.createElement('tr');
        newRow.className = 'item-row';
        newRow.dataset.productId = productId;
        newRow.dataset.qty = qty;
        
        const no = itemsBody.children.length + 1;
        const sku = product.sku || '-';
        const name = product.name;
        const category = product.category?.name || '-';
        const size = product.size?.name || '-';
        
        newRow.innerHTML = `
            <td class="center-align">${no}</td>
            <td class="center-align">${sku}</td>
            <td class="center-align">${name}</td>
            <td class="center-align">${category}</td>
            <td class="center-align">${size}</td>
            <td class="center-align">${qty}</td>
            <td class="center-align">
                <input type="hidden" name="items[${itemIndex}][product_id]" value="${productId}">
                <input type="hidden" name="items[${itemIndex}][qty]" value="${qty}">
                <a class="btn-floating btn-small waves-effect red lighten-2 remove-item" title="Hapus">
                    <i class="material-icons">delete</i>
                </a>
            </td>
        `;
        
        itemsBody.appendChild(newRow);
        itemIndex++;
        
        // Reset form
        productSelect.value = '';
        sizeDisplay.textContent = '-';
        stockDisplay.textContent = '0';
        qtyInput.value = 1;
        
        // Re-initialize Materialize select
        M.FormSelect.init(productSelect);
    });
    
    // Remove item row
    itemsBody.addEventListener('click', function(e) {
        if (e.target.closest('.remove-item')) {
            const row = e.target.closest('tr');
            row.remove();
            
            // Update row numbers
            const rows = itemsBody.querySelectorAll('.item-row');
            rows.forEach((row, index) => {
                row.querySelector('td:first-child').textContent = index + 1;
            });
        }
    });
    
    // Form validation
    document.getElementById('pickupForm').addEventListener('submit', function(e) {
        const rows = itemsBody.querySelectorAll('.item-row');
        
        if (rows.length === 0) {
            e.preventDefault();
            alert('Tambahkan minimal satu barang!');
        }
    });
});
</script>
@endsection
