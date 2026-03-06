@extends('layouts.app')

@section('content')
<style>
    .table thead th {
        position: relative;
        border: none !important;
    }
    .table th:not(:last-child)::after {
        content: '';
        position: absolute;
        right: 0;
        top: 35%;
        height: 30%;
        width: 1px;
        background-color: #dee2e6;
    }
    
    /* Label kecil untuk form */
    .info-label {
        font-weight: 600;
        font-size: 0.85rem;
        color: #333;
        margin-bottom: 4px;
    }
    
    /* Divider horizontal */
    .divider-line {
        border-top: 1px solid #dee2e6;
        margin: 1rem 0;
    }
    
    /* Divider vertical */
    .vertical-divider {
        width: 1px;
        background-color: #dee2e6;
        margin: 0 12px;
    }
    
    /* Tampilan stock / ukuran */
    .stock-display {
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 4px;
        min-height: 38px;
        display: flex;
        align-items: center;
        font-size: 0.9rem;
    }
    
    /* Input jumlah */
    .qty-input {
        max-width: 100px;
    }
    
    /* Section product row */
    .product-selection-section {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }
    
    .product-row {
        display: flex;
        align-items: flex-end;
        gap: 1rem;
        flex-wrap: wrap;
    }
    
    /* Table items */
    .items-table th {
        background-color: #f1f3f5;
        font-size: 0.85rem;
        text-transform: uppercase;
    }
    
    .items-table td {
        vertical-align: middle;
        font-size: 0.9rem;
    }
    
    /* Modal title */
    .modal-form-title {
        font-weight: 600;
        font-size: 1.1rem;
    }
    
    /* Product items horizontal display */
    .product-items-container {
        display: flex;
        flex-wrap: wrap;
        gap: 4px;
        max-width: 300px;
    }
    .product-item-badge {
        display: inline-block;
        margin-bottom: 2px;
    }
    .product-more-indicator {
        color: #6c757d;
        font-size: 0.85rem;
        font-weight: 500;
    }
</style>
<script>
document.addEventListener('DOMContentLoaded', function() {
    let itemIndex = 0;
    const itemsBody = document.getElementById('modalItemsBody');
    const productSelect = document.getElementById('modalProductSelect');
    const sizeDisplay = document.getElementById('modalSizeDisplay');
    const stockDisplay = document.getElementById('modalStockDisplay');
    const qtyInput = document.getElementById('modalQtyInput');
    const addItemBtn = document.getElementById('modalAddItemBtn');
    const pickupForm = document.getElementById('modalPickupForm');
    
    // Product data from server-side
    const products = @json($allProducts);
    
    // Update size and stock when product is selected
    productSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        if (selectedOption.value) {
            // Find product to get size and stock
            const product = products.find(p => p.id == selectedOption.value);
            sizeDisplay.textContent = product?.size?.name || '-';
            // Calculate stock from stockBalances relationship
            const stockBalances = product?.stock_balances || [];
            const totalStock = stockBalances.reduce((sum, sb) => sum + (sb.qty_on_hand || 0), 0);
            stockDisplay.textContent = totalStock;
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
        
        // Calculate stock (AWAL = current stock)
        const stockBalances = product?.stock_balances || [];
        const awalStock = stockBalances.reduce((sum, sb) => sum + (sb.qty_on_hand || 0), 0);
        const akhirStock = awalStock - qty;
        
        // Add row to table
        const newRow = document.createElement('tr');
        newRow.className = 'item-row';
        newRow.dataset.productId = productId;
        newRow.dataset.qty = qty;
        newRow.dataset.awal = awalStock;
        newRow.dataset.akhir = akhirStock;
        
        const no = itemsBody.children.length + 1;
        const name = product.name;
        const category = product.category?.name || '-';
        const size = product.size?.name || '-';
        
        newRow.innerHTML = `
            <td>${no}</td>
            <td>${name}</td>
            <td>${category}</td>
            <td>${size}</td>
            <td>${qty}</td>
            <td>${awalStock}</td>
            <td>${akhirStock}</td>
            <td>
                <input type="hidden" name="items[${itemIndex}][product_id]" value="${productId}">
                <input type="hidden" name="items[${itemIndex}][qty]" value="${qty}">
                <input type="hidden" name="items[${itemIndex}][stock_awal]" value="${awalStock}">
                <input type="hidden" name="items[${itemIndex}][stock_akhir]" value="${akhirStock}">
                <button type="button" class="btn btn-danger btn-sm remove-item" title="Hapus">×</button>
            </td>
        `;
        
        itemsBody.appendChild(newRow);
        itemIndex++;
        
        // Debug: log item count
        console.log('Items added:', itemsBody.querySelectorAll('.item-row').length);
        
        // Enable submit button when at least 1 item is added
        const submitBtn = document.getElementById('submitPickupBtn');
        submitBtn.disabled = false;
        console.log('Submit button enabled:', !submitBtn.disabled);
        
        // Reset form
        productSelect.value = '';
        sizeDisplay.textContent = '-';
        stockDisplay.textContent = '0';
        qtyInput.value = 1;
    });
    
    // Remove item row
    itemsBody.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-item')) {
            const row = e.target.closest('tr');
            row.remove();
            
            // Update row numbers and item indices to keep them sequential
            const rows = itemsBody.querySelectorAll('.item-row');
            rows.forEach((row, index) => {
                // Update the row number display
                row.querySelector('td:first-child').textContent = index + 1;
                
                // Update the hidden input names to keep indices sequential
                const productInput = row.querySelector('input[name$="[product_id]"]');
                const qtyInput = row.querySelector('input[name$="[qty]"]');
                
                if (productInput) {
                    productInput.name = `items[${index}][product_id]`;
                }
                if (qtyInput) {
                    qtyInput.name = `items[${index}][qty]`;
                }
            });
            
            // Update the global itemIndex to match the number of rows
            itemIndex = rows.length;
            
            // Disable submit button if no items left
            const submitBtn = document.getElementById('submitPickupBtn');
            submitBtn.disabled = rows.length === 0;
        }
    });
    
    // Form validation - removed manual check since button is now disabled until items are added
    pickupForm.addEventListener('submit', function(e) {
        // Debug: log form submission
        console.log('Form submitting...');
        
        // Check user_id
        const userSelect = document.getElementById('modalUserSelect');
        if (!userSelect.value) {
            e.preventDefault();
            alert('Pilih pengguna terlebih dahulu!');
            return;
        }
        
        // Check floor_id
        const floorSelect = document.getElementById('modalFloorSelect');
        if (!floorSelect.value) {
            e.preventDefault();
            alert('Pilih lantai terlebih dahulu!');
            return;
        }
        
        // The button is disabled until items are added, so this should never fire with empty items
        const rows = itemsBody.querySelectorAll('.item-row');
        if (rows.length === 0) {
            e.preventDefault();
            alert('Tambahkan minimal satu barang!');
            return;
        }
        
        // Debug: log the form data
        const formData = new FormData(pickupForm);
        console.log('Items in form:');
        for (let [key, value] of formData.entries()) {
            if (key.startsWith('items[')) {
                console.log(key, value);
            }
        }
        
        // Form is valid - let it submit naturally
        console.log('Form validated and ready to submit');
    });
    
    // Reset form when modal is closed
    const modal = document.getElementById('catatPengambilanModal');
    modal.addEventListener('hidden.bs.modal', function() {
        itemsBody.innerHTML = '';
        productSelect.value = '';
        sizeDisplay.textContent = '-';
        stockDisplay.textContent = '0';
        qtyInput.value = 1;
        itemIndex = 0;
        // Disable submit button
        const submitBtn = document.getElementById('submitPickupBtn');
        submitBtn.disabled = true;
    });
});
</script>
<div class="container">
    {{-- Breadcrumb style header --}}
    <div class="mb-3">
        <span class="text-muted fs-3">Persediaan /</span>
        <h1 class="d-inline fs-3">Histori Pengambilan</h1>
    </div>

    {{-- Tab menu --}}
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
        <div class="col-md-4 ms-2">
            <form method="GET" action="{{ route('persediaan.index') }}" class="d-flex gap-2">
                <input type="text" name="q" placeholder="Cari..." 
                       class="form-control form-control-sm" value="{{ request('q') }}">
                <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-search"></i></button>
                @if(request('q'))
                    <a href="{{ route('persediaan.index') }}" class="btn btn-outline-secondary btn-sm">Reset</a>
                @endif
            </form>
        </div>
        <div class="col-md-4 text-end me-2">
            <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#catatPengambilanModal">
                <i class="fa fa-plus"></i> Catat Pengambilan
            </button>
            @if(Auth::user() && Auth::user()->hasRole('admin'))
                <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#resetHistoriModal">
                    <i class="fa fa-trash"></i> Reset Histori
                </button>
            @endif
        </div>
    </div>

    {{-- Card with table --}}
    <div class="card">
        <div class="card-header bg-white py-2">
            <h6 class="mb-0">Data Histori Pengambilan</h6>
        </div>
        <div class="card-body p-0">
            <table class="table table-bordered table-striped mb-0" style="padding-top: 20px;">
                <thead>
                    <tr class="no-border">
                        <th style="width: 65px; vertical-align: middle;">#</th>
                        <th style="width: 230px; vertical-align: middle; padding-left: 15px;">NAMA PENGAMBIL</th>
                        <th style="vertical-align: middle; padding-left: 15px;">NAMA BARANG</th>
                        <th style="width: 185px; vertical-align: middle; padding-left: 15px;">TANGGAL PENGAMBILAN</th>
                        <th style="width: 155px; text-align: center; vertical-align: middle;">UNTUK LANTAI?</th>
                        <th style="width: 110px; text-align: left; vertical-align: middle; padding-left: 15px;">LIHAT DETAIL</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pickups as $pickup)
                        <tr>
                            <td style="border-right: 0;">{{ $pickup->id }}</td>
                            <td style="border-left: 0; border-right: 0;">{{ $pickup->user->name ?? 'N/A' }}</td>
                            <td style="border-left: 0; border-right: 0;">
                                @php
                                    $colors = ['primary','success','warning','danger','info'];
                                    $items = collect($pickup->items ?? []);
                                    $maxDisplay = 5;
                                    $displayItems = $items->take($maxDisplay);
                                    $remainingCount = $items->count() - $maxDisplay;
                                @endphp
                                <div class="product-items-container">
                                    @foreach($displayItems as $index => $item)
                                        <span class="badge bg-{{ $colors[$index % count($colors)] }} product-item-badge">
                                            {{ $item->product->name ?? 'N/A' }} ({{ $item->qty }})
                                        </span>
                                    @endforeach
                                    @if($remainingCount > 0)
                                      <a href="{{ route('persediaan.show', $pickup->id) }}" 
   class="product-more-indicator" 
   title="Lihat semua {{ $items->count() }} barang">
                                            +{{ $remainingCount }} lainnya...
                                        </a>
                                    @endif
                                </div>
                            </td>
                            <td style="border-left: 0; fs-5; border-right: 0; padding-left: 10px;">
                                {{ $pickup->created_at->translatedFormat('l, d M Y') }} <br>
                                <small class="text-muted">{{ $pickup->created_at->format('H:i:s') }}</small>
                            </td>
                            <td style="border-left: 0; border-right: 0; padding-left: 10px;">{{ $pickup->floor->name ?? 'N/A' }}</td>
                            <td style="border-left: 0; padding-left: 15px;">
                                <a href="{{ route('persediaan.show', $pickup->id) }}" class="text-dark" title="Lihat Detail" style="display: inline-block; width: 28px; height: 28px; line-height: 28px; text-align: center;"><i class="fa fa-eye" style="font-size: 16px;"></i></a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">Belum ada histori pengambilan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Pagination --}}
    <div class="mt-3">
        {{ $pickups->links() }}
    </div>
</div>


{{-- Modal Reset Histori Pengambilan (Admin Only) --}}
<div class="modal fade" id="resetHistoriModal" tabindex="-1" aria-labelledby="resetHistoriLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="resetHistoriLabel">Reset Histori Pengambilan</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
      </div>
      <div class="modal-body">
        <p class="text-danger">Apakah Anda yakin ingin menghapus semua histori pengambilan barang?</p>
        <p class="text-warning">Tindakan ini tidak dapat dibatalkan!</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <form action="{{ route('persediaan.reset') }}" method="POST" style="display: inline;">
            @csrf
            <button type="submit" class="btn btn-danger">Ya, Reset Semua</button>
        </form>
      </div>
    </div>
  </div>
</div>

{{-- Modal Catat Pengambilan --}}
<div class="modal fade" id="catatPengambilanModal" tabindex="-1" aria-labelledby="catatPengambilanLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-form-title" id="catatPengambilanLabel">Form Pengambilan Barang</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
      </div>
      <div class="modal-body">
        <form action="{{ route('persediaan.store') }}" method="POST" id="modalPickupForm">
            @csrf

            {{-- Informasi + Dropdown --}}
            <div class="row mb-3">
                <div class="col-md-3">
                    <div class="bg-primary text-white p-3 rounded" style="min-height: 100%;">
                        <h6 class="mb-0 fw-bold">INFORMASI</h6>
                        <p class="mb-0">Tanggal: {{ now()->translatedFormat('l, d M Y H:i') }}</p>
                        <p class="mb-0">No Catatan: #{{ isset($pickup) ? $pickup->id : 'otomatis' }}</p>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="modalUserSelect" class="form-label">Pengambilan Untuk Siapa?</label>
                            <select name="user_id" id="modalUserSelect" class="form-select form-select-sm" required>
                                <option value="">-- Pilih Pengguna --</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="modalFloorSelect" class="form-label">Pengambilan Untuk Lantai?</label>
                            <select name="floor_id" id="modalFloorSelect" class="form-select form-select-sm" required>
                                <option value="">-- Pilih Lantai --</option>
                                @foreach($floors as $floor)
                                    <option value="{{ $floor->id }}">{{ $floor->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <hr>

            {{-- Pilih Barang --}}
            <div class="row align-items-end mb-3">
                <div class="col-md-4">
                    <label for="modalProductSelect" class="form-label">Cari & Pilih Barang</label>
                    <select name="product_id" id="modalProductSelect" class="form-select">
                        <option value="">-- Pilih Barang --</option>
                        @foreach($allProducts as $product)
                            <option value="{{ $product->id }}" 
                                    data-size="{{ $product->size ?? '-' }}" 
                                    data-stock="{{ $product->stock_balance }}">
                                {{ $product->name }} ({{ $product->category->name ?? '-' }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Ukuran Barang</label>
                    <div class="form-control-plaintext" id="modalSizeDisplay">-</div>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Stock Tersedia</label>
                    <div class="form-control-plaintext" id="modalStockDisplay">0</div>
                </div>
                <div class="col-md-2">
                    <label for="modalQtyInput" class="form-label">Ambil Sebanyak</label>
                    <input type="number" id="modalQtyInput" name="qty" class="form-control" min="1" value="1">
                </div>
                <div class="col-md-2">
                    <button type="button" id="modalAddItemBtn" class="btn btn-success w-100">
                        <i class="fa fa-plus"></i> Tambah Barang
                    </button>
                </div>
            </div>

            <hr>

            {{-- Tabel Barang Ditambahkan --}}
            <div class="table-responsive">
                <table class="table table-bordered" id="modalItemsTable">
                    <thead>
                        <tr>
                            <th style="width: 50px;">NO</th>
                            <th>NAMA BARANG</th>
                            <th>KATEGORI</th>
                            <th style="width: 110px;">UKURAN</th>
                            <th style="width: 100px;">QTY</th>
                            <th style="width: 100px;">AWAL</th>
                            <th style="width: 100px;">AKHIR</th>
                            <th style="width: 80px;">AKSI</th>
                        </tr>
                    </thead>
                    <tbody id="modalItemsBody">
                        {{-- Item akan ditambahkan via JS --}}
                    </tbody>
                </table>
            </div>

            {{-- Catatan --}}
            <div class="mb-3">
                <label for="notes" class="form-label">Catatan (opsional)</label>
                <textarea name="notes" id="notes" class="form-control" rows="2" placeholder="Tambahkan catatan jika diperlukan..."></textarea>
            </div>

            {{-- Tombol --}}
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-success" id="submitPickupBtn" disabled>Proses Pengambilan Barang</button>
            </div>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- <div class="modal fade" id="catatPengambilanModal" tabindex="-1" aria-labelledby="catatPengambilanLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-form-title" id="catatPengambilanLabel">Form Pengambilan Barang</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
      </div>
      <div class="modal-body">
        <form action="{{ route('persediaan.store') }}" method="POST" id="modalPickupForm">
            @csrf

            {{-- Row with INFORMASI on left and dropdowns on right --}}
            <div class="row mb-3">
                {{-- Left: INFORMASI in blue box --}}
                <div class="col-md-3">
                    <div class="bg-primary text-white p-3 rounded" style="min-height: 100%;">
                        <h6 class="mb-0 fw-bold">INFORMASI</h6>
                    </div>
                </div>
                
                {{-- Right: Pengambilan fields --}}
                <div class="col-md-9">
                    <div class="row">
                        <div class="col-md-6">
                            <label class="info-label small">Pengambilan untuk siapa?</label>
                            <select name="user_id" id="modalUserSelect" class="form-control form-control-sm" required>
                                <option value="" disabled selected>-- Pilih Pengguna --</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="info-label small">Pengambilan untuk lantai?</label>
                            <select name="floor_id" id="modalFloorSelect" class="form-control form-control-sm" required>
                                <option value="" disabled selected>-- Pilih Lantai --</option>
                                @foreach($floors as $floor)
                                    <option value="{{ $floor->id }}">{{ $floor->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            
            {{-- Horizontal Line --}}
            <div class="divider-line"></div>
            
            {{-- Product Selection Section --}}
            <div class="product-selection-section">
                <div class="product-row">
                    {{-- Cari & Pilih Barang --}}
                    <div style="flex: 2; min-width: 200px;">
                        <label class="info-label">Cari & pilih barang</label>
                        <select name="product_id" id="modalProductSelect" class="form-control">
                            <option value="" disabled selected>-- Pilih Barang --</option>
                            @foreach($allProducts as $product)
                                <option value="{{ $product->id }}" data-size="{{ $product->size->name ?? '-' }}">
                                    {{ $product->name }} ({{ $product->category->name ?? '-' }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    {{-- Ukuran Barang --}}
                    <div>
                        <label class="info-label">Ukuran Barang</label>
                        <div class="stock-display" id="modalSizeDisplay" style="padding: 8px;">-</div>
                    </div>
                    
                    {{-- Stock Tersedia --}}
                    <div>
                        <label class="info-label">Stock tersedia</label>
                        <div class="stock-display" id="modalStockDisplay" style="padding: 8px;">0</div>
                    </div>
                    
                    {{-- Ambil Sebanyak --}}
                    <div>
                        <label class="info-label">Ambil sebanyak</label>
                        <input type="number" id="modalQtyInput" class="form-control qty-input" min="1" value="1">
                    </div>
                    
                    {{-- Vertical Divider --}}
                    <div class="vertical-divider"></div>
                    
                    {{-- Tambah Barang Button --}}
                    <div>
                        <label class="info-label">&nbsp;</label>
                        <button type="button" id="modalAddItemBtn" class="btn btn-success">
                            <i class="fa fa-plus"></i> Tambah Barang
                        </button>
                    </div>
                </div>
            </div>
            
            {{-- Horizontal Line --}}
            <div class="divider-line"></div>
            
            {{-- Table of Added Items --}}
            <div class="items-table-container">
                <table class="table table-bordered items-table" id="modalItemsTable">
                    <thead>
                        <tr>
                            <th style="width: 50px;">No</th>
                            <th>Nama Barang</th>
                            <th>Kategori</th>
                            <th>Ukuran</th>
                            <th style="width: 100px;">Jumlah</th>
                            <th style="width: 80px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="modalItemsBody">
                        {{-- Items will be added here dynamically --}}
                    </tbody>
                </table>
            </div>
            
            {{-- Catatan (Opsional) --}}
            <div class="mt-3">
                <label class="info-label">Catatan (opsional)</label>
                <textarea name="notes" class="form-control" placeholder="Tambahkan catatan jika diperlukan..."></textarea>
            </div>

            {{-- Tombol --}}
            <div class="modal-footer mt-4">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-success">Proses Pengambilan Barang</button>
            </div>
        </form>
      </div>
    </div>
  </div>
</div> -->

@endsection

