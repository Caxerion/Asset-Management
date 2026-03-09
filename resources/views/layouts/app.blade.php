<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Stock Barang & Obat</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    
    <style>
        .pagination-wrapper {
            text-align: center;
            margin-top: 20px;
        }
        
        .pagination-wrapper .page-link {
            display: inline-block !important;
            min-width: 36px;
            height: 36px;
            line-height: 34px;
            padding: 0 10px;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            background: #fff;
            color: #334155;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.2s ease;
            margin: 0 2px;
        }
        
        .pagination-wrapper .page-link:hover:not(.disabled):not(.active) {
            background: #f1f5f9;
            border-color: #cbd5e1;
            color: #1e293b;
        }
        
        .pagination-wrapper .page-link.active {
            background: #3b82f6;
            border-color: #3b82f6;
            color: #fff;
        }
        
        .pagination-wrapper .page-link.disabled {
            color: #94a3b8;
            cursor: not-allowed;
            opacity: 0.6;
        }
        
        .pagination-info {
            font-size: 13px;
            color: #64748b;
            margin-top: 10px;
        }
    </style>

</head>
<body>
    {{-- Header atas --}}
    <nav class="navbar navbar-light bg-light">
        <div class="container-fluid d-flex justify-content-between">
            <img src="assets/img/logo-RE.png" alt="logo" class="d-inline-block align-text-top" width="40" height="50">
            <div class="d-flex align-items-center">
                <span class="navbar-brand mb-0 h1">Stock Barang & Obat</span>
            </div>
            <div class="d-flex align-items-center">
                <span class="text-black me-3">👤 {{ auth()->user()->name ?? 'Guest' }}</span>
                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-dark">Logout</button>
                </form>
            </div>
        </div>
    </nav>

    {{-- Navbar bawah untuk menu utama --}}
    <nav class="navbar navbar-expand-lg navbar-secondary bg-secondary">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('dashboard') }}"  style="color:white;">Dashboard</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="persediaanDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false"  style="color:white;">
                            Persediaan
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="persediaanDropdown">
                            <li><a class="dropdown-item" href="{{ route('persediaan.index') }}" >Histori</a></li>
                            <li><a class="dropdown-item" href="{{ route('stock.index') }}">Stock</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="masterdataDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="color:white;">
                            Master Data
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="masterdataDropdown">
                            <li><a class="dropdown-item" href="{{ route('masterdata.kategori.index') }}">Kategori</a></li>
                            <li><a class="dropdown-item" href="{{ route('masterdata.ukuran.index') }}">Ukuran</a></li>
                            <li><a class="dropdown-item" href="{{ route('masterdata.lantai.index') }}">Lantai</a></li>
                            <li><a class="dropdown-item" href="{{ route('masterdata.produk.index') }}">Produk</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    {{-- Konten halaman --}}
    <main class="py-4">
        @yield('content')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
