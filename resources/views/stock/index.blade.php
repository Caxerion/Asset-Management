@extends('layouts.app')

@section('content')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const sortSelect = document.getElementById('sortSelect');
        const searchInput = document.getElementById('searchInput');
        let searchTimeout;

        // Sorting dropdown - automatic navigation on change
        if (sortSelect) {
            sortSelect.addEventListener('change', function() {
                const sortValue = this.value;
                const currentUrl = new URL(window.location.href);
                currentUrl.searchParams.set('sort', sortValue);
                window.location.href = currentUrl.toString();
            });
        }

        // Search input - automatic filtering on type (debounced)
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                const searchValue = this.value.toLowerCase();
                
                searchTimeout = setTimeout(function() {
                    const rows = document.querySelectorAll('.stock-table tbody tr');
                    rows.forEach(function(row) {
                        const text = row.textContent.toLowerCase();
                        row.style.display = text.includes(searchValue) ? '' : 'none';
                    });
                }, 300);
            });
        }
    });
</script>
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
</style>
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Check for success message - Stock reset
    @if(session('success') && str_contains(session('success'), 'direset'))
        Swal.fire({
            icon: 'success',
            title: 'Stock Di Reset',
            text: '{{ session('success') }}',
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'OK'
        });
    @endif
    
    // Check for success message - Stock added
    @if(session('success') && str_contains(session('success'), 'ditambahkan'))
        Swal.fire({
            icon: 'success',
            title: 'Stock Ditambah',
            text: '{{ session('success') }}',
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'OK'
        });
    @endif
    
    // Check for error message
    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Gagal',
            text: '{{ session('error') }}',
            confirmButtonColor: '#d33',
            confirmButtonText: 'OK'
        });
    @endif
});
</script>
<div class="container pt-3">
    {{-- Breadcrumb style header --}}
    <div class="mb-3">
        <span class="text-muted fs-5"><i class="fa fa-clipboard-list" style="margin-right: 2px;"></i>Persediaan/</span>
        <span class="d-inline fs-5"><i class="fa fa-list" style="margin-right: 4px;"></i>Stock Barang</span>
    </div>

    {{-- Tab menu --}}
    <ul class="nav nav-tabs mb-3">
        <li class="nav-item">
            <a class="nav-link" href="{{ route('persediaan.index') }}"><i class="fa fa-link" style="margin-right: 4px;"></i>Histori Pengambilan</a>
        </li>
        <li class="nav-item">
            <a class="nav-link active" href="{{ route('stock.index') }}"><i class="fa fa-list" style="margin-right: 4px;"></i>Stock Barang</a>
        </li>
    </ul>

    {{-- Card with table --}}
    <div class="card">
        <div class="card-header bg-white py-2 d-flex justify-content-between align-items-center">
            <div class="d-flex gap-2 align-items-center">
                <div class="input-group" style="width: 300px;">
                    <span class="input-group-text bg-white border-end-0" style="border-radius: 10px 0 0 10px;"><i class="fa fa-search"></i></span>
                    <input type="text" id="searchInput" placeholder="Cari Stock Barang..." 
                           class="form-control form-control-sm border-start-0" style="border-radius: 0 10px 10px 0;">
                </div>
                <select name="sort" id="sortSelect" class="form-select form-select-sm" style="width: 200px; border-radius: 10px;">
                    <option value="id_asc" {{ $sortField === 'id' && $sortDirection === 'asc' ? 'selected' : '' }}>Default (1-10)</option>
                    <option value="id_desc" {{ $sortField === 'id' && $sortDirection === 'desc' ? 'selected' : '' }}>Terakhir Ditambahkan</option>
                    <option value="stock_desc" {{ $sortField === 'stock' && $sortDirection === 'desc' ? 'selected' : '' }}>Stock Terbanyak</option>
                    <option value="stock_asc" {{ $sortField === 'stock' && $sortDirection === 'asc' ? 'selected' : '' }}>Stock Tersedikit</option>
                </select>
            </div>
            <div class="d-flex gap-2 align-items-center">
                <!-- <h6 class="mb-0">Data Stock Barang</h6> -->
                <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#resetStockModal" style="border-radius: 6px; padding-bottom:10px; padding-top:10px;">
                    <i class="fa fa-rotate-left"></i> Reset Semua Stock
                </button>
            </div>
        </div>
        <div class="card-body p-0">
            <table class="table table-bordered table-striped mb-0 stock-table">
                <thead>
                    <tr>
                        <th style="width: 50px;">#</th>
                        <th class="fs-6" style="width: 195px; text-align: left;">KATEGORI BARANG</th>
                        <th style="text-align: left;">NAMA BARANG</th>
                        <th style="width: 180px; text-align: left;">UKURAN BARANG</th>
                        <th class="fs-6" style="width: 160px;">STOCK TERSEDIA SAAT INI</th>
                        <th style="width: 120px;">TAMBAH STOCK</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                        <tr>
                            <td>#{{ $product->id }}</td>
                            <td>
                                <span class="badge bg-primary">{{ $product->category->name ?? '-' }}</span>
                            </td>
                            <td>{{ $product->name }}</td>
                            <td>{{ $product->size->name ?? '-' }}</td>
                            <td>
                                @if($product->stock_balance > 10)
                                    <span class="badge bg-success">{{ $product->stock_balance }} {{ $product->unit }}</span>
                                @elseif($product->stock_balance > 0)
                                    <span class="badge bg-warning text-dark">{{ $product->stock_balance }} {{ $product->unit }}</span>
                                @else
                                    <span class="badge bg-danger">{{ $product->stock_balance }} {{ $product->unit }}</span>
                                @endif
                            </td>
                            <td>
                                <button type="button" class="btn btn-sm btn-success" 
                                        data-bs-toggle="modal" data-bs-target="#tambahStockModal{{ $product->id }}">
                                    + Tambah
                                </button>
                            </td>
                        </tr>

                        {{-- Modal Tambah Stock --}}
                        <div class="modal fade" id="tambahStockModal{{ $product->id }}" tabindex="-1" aria-hidden="true">
                          <div class="modal-dialog">
                            <div class="modal-content">
                              <div class="modal-header">
                                <h5 class="modal-title">Tambah Stock: {{ $product->name }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                              </div>
                              <div class="modal-body">
                                <form action="{{ route('stock.add', $product->id) }}" method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <label class="form-label">Jumlah Tambahan</label>
                                        <input type="number" name="qty" class="form-control" required>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-success">Simpan</button>
                                    </div>
                                </form>
                              </div>
                            </div>
                          </div>
                        </div>
                    @empty
                        <tr>
                            <td colspan="6">Belum ada data stok barang.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            {{-- Pagination inside table --}}
            @if($products->hasPages())
            <div class="pagination-wrapper mt-3">
                {{ $products->links('components.custom-pagination') }}
            </div>
            <div class="pagination-info">Menampilkan {{ $products->firstItem() }} sampai {{ $products->lastItem() }} dari {{ $products->total() }} data</div>
            @endif
        </div>
    </div>

    {{-- Reset Stock Confirmation Modal --}}
    <div class="modal fade" id="resetStockModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Reset Semua Stock</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin mereset semua stock menjadi 0?</p>
                    <p class="text-danger">Tindakan ini tidak dapat dibatalkan!</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <form action="{{ route('stock-balances.resetAll') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-danger">Ya, Reset Semua</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
