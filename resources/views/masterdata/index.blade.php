@extends('layouts.app')

@section('content')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Sorting dropdowns - automatic navigation on change
    document.querySelectorAll('.sort-select').forEach(function(select) {
        select.addEventListener('change', function() {
            const sortValue = this.value;
            const currentUrl = new URL(window.location.href);
            currentUrl.searchParams.set(this.dataset.sortParam, sortValue);
            window.location.href = currentUrl.toString();
        });
    });
});
</script>
<div class="container" style="margin-top: 40px;">
    <h1><i class="fa fa-database" style="margin-right: 8px;"></i>Master Data</h1>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Kategori Barang --}}
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span>Kategori Barang</span>
            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                <i class="fa fa-plus" style="margin-right: 4px;"></i>Tambah Kategori
            </button>
        </div>
        <div class="card-body p-3">
            @if($categories->isNotEmpty())
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama Kategori</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categories as $category)
                            <tr>
                                <td>{{ $categories->firstItem() + $loop->index }}</td>
                                <td>{{ $category->name }}</td>
                                <td>
                                    <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editCategoryModal{{ $category->id }}">
                                        Edit
                                    </button>
                                    <form action="{{ route('masterdata.category.destroy', $category->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus kategori ini?')">
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>

                            <!-- Edit Category Modal -->
                            <div class="modal fade" id="editCategoryModal{{ $category->id }}" tabindex="-1" aria-labelledby="editCategoryModalLabel{{ $category->id }}" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editCategoryModalLabel{{ $category->id }}">Edit Kategori</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form action="{{ route('masterdata.category.update', $category->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label for="name" class="form-label">Nama Kategori</label>
                                                    <input type="text" class="form-control" id="name" name="name" value="{{ $category->name }}" required>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </tbody>
                </table>
                
                @if($categories->hasPages())
                <div class="pagination-wrapper mt-3">
                    {{ $categories->links('components.custom-pagination') }}
                </div>
                <div class="pagination-info">Menampilkan {{ $categories->firstItem() }} sampai {{ $categories->lastItem() }} dari {{ $categories->total() }} data</div>
                @endif
            @else
                <p>Belum ada data kategori.</p>
            @endif
        </div>
    </div>

    <!-- Add Category Modal -->
    <div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addCategoryModalLabel">Tambah Kategori</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('masterdata.category.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Kategori</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Data Ukuran --}}
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span>Data Ukuran</span>
            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addSizeModal">
                <i class="fa fa-plus" style="margin-right: 4px;"></i>Tambah Ukuran
            </button>
        </div>
        <div class="card-body p-3">
            @if($sizes->count())
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama Ukuran</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sizes as $size)
                            <tr>
                                <td>{{ $sizes->firstItem() + $loop->index }}</td>
                                <td>{{ $size->name }}</td>
                                <td>
                                    <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editSizeModal{{ $size->id }}">
                                        Edit
                                    </button>
                                    <form action="{{ route('masterdata.size.destroy', $size->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus ukuran ini?')">
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>

                            <!-- Edit Size Modal -->
                            <div class="modal fade" id="editSizeModal{{ $size->id }}" tabindex="-1" aria-labelledby="editSizeModalLabel{{ $size->id }}" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editSizeModalLabel{{ $size->id }}">Edit Ukuran</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form action="{{ route('masterdata.size.update', $size->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label for="name" class="form-label">Nama Ukuran</label>
                                                    <input type="text" class="form-control" id="name" name="name" value="{{ $size->name }}" required>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </tbody>
                </table>
                
                @if($sizes->hasPages())
                <div class="pagination-wrapper mt-3">
                    {{ $sizes->links('components.custom-pagination') }}
                </div>
                <div class="pagination-info">Menampilkan {{ $sizes->firstItem() }} sampai {{ $sizes->lastItem() }} dari {{ $sizes->total() }} data</div>
                @endif
            @else
                <p>Belum ada data ukuran.</p>
            @endif
        </div>
    </div>

    <!-- Add Size Modal -->
    <div class="modal fade" id="addSizeModal" tabindex="-1" aria-labelledby="addSizeModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addSizeModalLabel">Tambah Ukuran</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('masterdata.size.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Ukuran</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Data Lantai --}}
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span>Data Lantai</span>
            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addFloorModal">
                <i class="fa fa-plus" style="margin-right: 4px;"></i>Tambah Lantai
            </button>
        </div>
        <div class="card-body p-3">
            @if($floors->count())
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama Lantai</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($floors as $floor)
                            <tr>
                                <td>{{ $floors->firstItem() + $loop->index }}</td>
                                <td>{{ $floor->name }}</td>
                                <td>
                                    <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editFloorModal{{ $floor->id }}">
                                        Edit
                                    </button>
                                    <form action="{{ route('masterdata.floor.destroy', $floor->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus lantai ini?')">
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>

                            <!-- Edit Floor Modal -->
                            <div class="modal fade" id="editFloorModal{{ $floor->id }}" tabindex="-1" aria-labelledby="editFloorModalLabel{{ $floor->id }}" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editFloorModalLabel{{ $floor->id }}">Edit Lantai</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form action="{{ route('masterdata.floor.update', $floor->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label for="name" class="form-label">Nama Lantai</label>
                                                    <input type="text" class="form-control" id="name" name="name" value="{{ $floor->name }}" required>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </tbody>
                </table>
                
                @if($floors->hasPages())
                <div class="pagination-wrapper mt-3">
                    {{ $floors->links('components.custom-pagination') }}
                </div>
                <div class="pagination-info">Menampilkan {{ $floors->firstItem() }} sampai {{ $floors->lastItem() }} dari {{ $floors->total() }} data</div>
                @endif
            @else
                <p>Belum ada data lantai.</p>
            @endif
        </div>
    </div>

    <!-- Add Floor Modal -->
    <div class="modal fade" id="addFloorModal" tabindex="-1" aria-labelledby="addFloorModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addFloorModalLabel">Tambah Lantai</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('masterdata.floor.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Lantai</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Data Barang --}}
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span>Data Barang</span>
            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addProductModal">
                <i class="fa fa-plus" style="margin-right: 4px;"></i>Tambah Barang
            </button>
        </div>
        <div class="card-body p-3">
            @if($products->count())
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>SKU</th>
                            <th>Nama Barang</th>
                            <th>Kategori</th>
                            <th>Ukuran</th>
                            <th>Unit</th>
                            <th>Min Stock</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($products as $product)
                            <tr>
                                <td>{{ $products->firstItem() + $loop->index }}</td>
                                <td>{{ $product->sku }}</td>
                                <td>{{ $product->name }}</td>
                                <td>{{ $product->category->name ?? '-' }}</td>
                                <td>{{ $product->size->name ?? '-' }}</td>
                                <td>{{ $product->unit }}</td>
                                <td>{{ $product->min_stock }}</td>
                                <td>
                                    <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editProductModal{{ $product->id }}">
                                        Edit
                                    </button>
                                    <form action="{{ route('masterdata.product.destroy', $product->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus barang ini?')">
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>

                            <!-- Edit Product Modal -->
                            <div class="modal fade" id="editProductModal{{ $product->id }}" tabindex="-1" aria-labelledby="editProductModalLabel{{ $product->id }}" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editProductModalLabel{{ $product->id }}">Edit Barang</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form action="{{ route('masterdata.product.update', $product->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label for="sku" class="form-label">SKU</label>
                                                    <input type="text" class="form-control" id="sku" name="sku" value="{{ $product->sku }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="name" class="form-label">Nama Barang</label>
                                                    <input type="text" class="form-control" id="name" name="name" value="{{ $product->name }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="category_id" class="form-label">Kategori</label>
                                                    <select class="form-select" id="category_id" name="category_id" required>
                                                        <option value="">Pilih Kategori</option>
                                                        @foreach($categories as $category)
                                                            <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="size_id" class="form-label">Ukuran</label>
                                                    <select class="form-select" id="size_id" name="size_id" required>
                                                        <option value="">Pilih Ukuran</option>
                                                        @foreach($sizes as $size)
                                                            <option value="{{ $size->id }}" {{ $product->size_id == $size->id ? 'selected' : '' }}>{{ $size->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="unit" class="form-label">Unit</label>
                                                    <input type="text" class="form-control" id="unit" name="unit" value="{{ $product->unit }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="min_stock" class="form-label">Min Stock</label>
                                                    <input type="number" class="form-control" id="min_stock" name="min_stock" value="{{ $product->min_stock }}" required>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </tbody>
                </table>
                
                @if($products->hasPages())
                <div class="pagination-wrapper mt-3">
                    {{ $products->links('components.custom-pagination') }}
                </div>
                <div class="pagination-info">Menampilkan {{ $products->firstItem() }} sampai {{ $products->lastItem() }} dari {{ $products->total() }} data</div>
                @endif
            @else
                <p>Belum ada data barang.</p>
            @endif
        </div>
    </div>

    <!-- Add Product Modal -->
<div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addProductModalLabel">Tambah Barang</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('masterdata.product.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Barang</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="category_id" class="form-label">Kategori</label>
                        <select class="form-select" id="category_id" name="category_id" required>
                            <option value="">Pilih Kategori</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="size_id" class="form-label">Ukuran</label>
                        <select class="form-select" id="size_id" name="size_id" required>
                            <option value="">Pilih Ukuran</option>
                            @foreach($sizes as $size)
                                <option value="{{ $size->id }}">{{ $size->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="unit" class="form-label">Unit</label>
                        <input type="text" class="form-control" id="unit" name="unit" required>
                    </div>
                    <div class="mb-3">
                        <label for="min_stock" class="form-label">Min Stock</label>
                        <input type="number" class="form-control" id="min_stock" name="min_stock" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
</div>

@endsection
