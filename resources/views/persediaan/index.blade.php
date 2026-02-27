@extends('layouts.app')

@section('content')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let itemIndex = 1;
    const itemsContainer = document.getElementById('itemsContainer');
    const addItemBtn = document.getElementById('addItem');

    // Add new item row
    addItemBtn.addEventListener('click', function() {
        const newRow = document.createElement('div');
        newRow.className = 'item-row d-flex align-items-center mb-2';
        newRow.innerHTML = `
            <select name="items[${itemIndex}][product_id]" class="form-control product-select" required>
                <option value="">-- Pilih Barang --</option>
                @foreach($allProducts as $product)
                    <option value="{{ $product->id }}">{{ $product->name }} ({{ $product->unit }}) - Stok: {{ $product->stock_balance }}</option>
                @endforeach
            </select>
            <input type="number" name="items[${itemIndex}][qty]" class="form-control ms-2" placeholder="Qty" style="width:100px;" min="1" value="1" required>
            <button type="button" class="btn btn-danger btn-sm ms-2 remove-item" title="Hapus">×</button>
        `;
        itemsContainer.appendChild(newRow);
        itemIndex++;
    });

    // Remove item row
    itemsContainer.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-item')) {
            const row = e.target.closest('.item-row');
            if (itemsContainer.querySelectorAll('.item-row').length > 1) {
                row.remove();
            } else {
                alert('Minimal harus ada satu barang!');
            }
        }
    });

    // Form validation
    const pickupForm = document.querySelector('#catatPengambilanModal form');
    pickupForm.addEventListener('submit', function(e) {
        const rows = itemsContainer.querySelectorAll('.item-row');
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
        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#catatPengambilanModal">
            + Catat Pengambilan
        </button>
    </div>

    {{-- Card with table --}}
    <div class="card">
        <div class="card-header bg-white py-2">
            <h6 class="mb-0">Data Histori Pengambilan</h6>
        </div>
        <div class="card-body p-0">
            <table class="table table-bordered table-striped mb-0">
                <thead>
                    <tr>
                        <th style="width: 65px;">#</th>
                        <th style="width: 230px;">NAMA PENGAMBIL</th>
                        <th >NAMA BARANG</th>
                        <th style="width: 180px;">TANGGAL PENGAMBILAN</th>
                        <th style="width: 145px;">UNTUK LANTAI?</th>
                        <th style="width: 110px;">LIHAT DETAIL</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pickups as $pickup)
                        <tr>
                            <td>{{ $pickup->id }}</td>
                            <td>{{ $pickup->user->name }}</td>
                            <td>
                                @php
                                    $colors = ['primary','success','warning','danger','info'];
                                @endphp
                                @foreach($pickup->items as $index => $item)
                                    <span class="badge bg-{{ $colors[$index % count($colors)] }}">
                                        {{ $item->product->name }} ({{ $item->qty }})
                                    </span><br>
                                @endforeach
                            </td>
                            <td>
                                {{ $pickup->created_at->translatedFormat('l, d M Y') }} <br>
                                <small class="text-muted">{{ $pickup->created_at->format('H:i:s') }}</small>
                            </td>
                            <td>{{ $pickup->floor->name }}</td>
                            <td>
                                <a href="{{ route('persediaan.show', $pickup->id) }}" class="btn btn-sm btn-info"><i class="fa-solid fa-eye"></i></a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">Belum ada histori pengambilan.</td>
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


{{-- Modal Catat Pengambilan --}}
<div class="modal fade" id="catatPengambilanModal" tabindex="-1" aria-labelledby="catatPengambilanLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="catatPengambilanLabel">Form Pengambilan Barang</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
      </div>
      <div class="modal-body">
        <form action="{{ route('persediaan.store') }}" method="POST">
            @csrf

            {{-- Informasi catatan --}}
            <div class="mb-3">
                <p><strong>Nomer Catatan Pengambilan:</strong> #PU- (AUTO)</p>
                <p><strong>Tanggal:</strong> {{ now()->translatedFormat('l, d M Y H:i:s') }}</p>
            </div>

            {{-- Pengambilan untuk siapa --}}
            <div class="mb-3">
                <label class="form-label">Pengambilan Untuk Siapa?</label>
                <select name="user_id" class="form-control" required>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Pengambilan untuk lantai --}}
            <div class="mb-3">
                <label class="form-label">Pengambilan Untuk Lantai?</label>
                <select name="floor_id" class="form-control" required>
                    @foreach($floors as $floor)
                        <option value="{{ $floor->id }}">{{ $floor->name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Catatan --}}
            <div class="mb-3">
                <label class="form-label">Catatan (Opsional)</label>
                <textarea name="notes" class="form-control" placeholder="Tambahkan catatan jika diperlukan..."></textarea>
            </div>

            {{-- Barang yang diambil --}}
            <div class="mb-3">
                <label class="form-label">Barang yang diambil</label>
                <div id="itemsContainer">
                    <div class="item-row d-flex align-items-center mb-2">
                        <select name="items[0][product_id]" class="form-control product-select" required>
                            <option value="">-- Pilih Barang --</option>
                            @foreach($allProducts as $product)
                                <option value="{{ $product->id }}">{{ $product->name }} ({{ $product->unit }}) - Stok: {{ $product->stock_balance }}</option>
                            @endforeach
                        </select>
                        <input type="number" name="items[0][qty]" class="form-control ms-2" placeholder="Qty" style="width:100px;" min="1" value="1" required>
                        <button type="button" class="btn btn-danger btn-sm ms-2 remove-item" title="Hapus">×</button>
                    </div>
                </div>
                <button type="button" class="btn btn-outline-primary btn-sm mt-2" id="addItem">+ Tambah Barang</button>
            </div>

            {{-- Tombol --}}
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-success">Proses Pengambilan Barang</button>
            </div>
        </form>
      </div>
    </div>
  </div>
</div>

@endsection
