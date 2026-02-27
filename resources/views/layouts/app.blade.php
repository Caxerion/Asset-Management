<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Stock Barang & Obat</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

</head>
<body>
    {{-- Header atas --}}
    <nav class="navbar navbar-dark bg-dark">
        <div class="container-fluid d-flex justify-content-between">
            <div class="d-flex align-items-center">
                <span class="navbar-brand mb-0 h1">Stock Barang & Obat</span>
            </div>
            <div class="d-flex align-items-center">
                <a class="nav-link text-white" href="#">🔍</a>
                <a class="nav-link text-white" href="#">🌐</a>
                <a class="nav-link text-white" href="#">⚙️</a>
                <a class="nav-link text-white" href="#">🔔</a>
                <a class="nav-link text-white" href="#">👤</a>
            </div>
        </div>
    </nav>

    {{-- Navbar bawah untuk menu utama --}}
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('dashboard') }}">Dashboard</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="persediaanDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Persediaan
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="persediaanDropdown">
                            <li><a class="dropdown-item" href="{{ route('persediaan.index') }}">Histori</a></li>
                            <li><a class="dropdown-item" href="{{ route('stock.index') }}">Stock</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="masterdataDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Master Data
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="masterdataDropdown">
                            <li><a class="dropdown-item" href="{{ route('masterdata.index') }}">Kategori/Lantai/Supplier</a></li>
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
