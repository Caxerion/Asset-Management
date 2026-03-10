<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Stock Barang & Obat</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> -->
    <link rel="stylesheet" href="assets/fontsAwesome/css/all.min.css">
    
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
    <style>
        footer{
          padding: 3rem 7% 1.5rem 7%;
          background-color: #1e293b;
          color: #e2e8f0;
        }
        footer .footer-inner{
          display: flex;
          justify-content: space-between;
          align-items: flex-start;
          width: 100%;
          gap: 2rem;
          flex-wrap: wrap;
        }
        .footer-brand, .footer-links, .footer-contact, .footer-social{
            flex: 1;
            min-width: 200px;
        }
        .footer-brand h4{
            color: #fff;
            font-weight: 600;
            margin-bottom: 0.75rem;
            font-size: 1.25rem;
        }
        .footer-brand p{
            font-size: 0.9rem;
            color: #94a3b8;
            line-height: 1.6;
        }
        .footer-links h5, .footer-contact h5, .footer-social h5{
            color: #fff;
            font-weight: 600;
            margin-bottom: 1rem;
            font-size: 1rem;
            border-bottom: 2px solid #3b82f6;
            display: inline-block;
            padding-bottom: 0.5rem;
        }
        .footer-links ul{
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .footer-links ul li{
            margin-bottom: 0.5rem;
        }
        .footer-links a{
            color: #94a3b8;
            text-decoration: none;
            transition: color 0.2s ease;
        }
        .footer-links a:hover{
            color: #3b82f6;
        }
        .footer-contact p{
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
            color: #94a3b8;
        }
        .footer-contact i{
            color: #3b82f6;
            margin-right: 0.5rem;
        }
        .footer-social a{
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background-color: #334155;
            color: #e2e8f0;
            text-decoration: none;
            margin-right: 0.5rem;
            transition: all 0.2s ease;
        }
        .footer-social a:hover{
            background-color: #3b82f6;
            color: #fff;
            transform: translateY(-2px);
        }
        .footer-credit{
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 1px solid #334155;
        }
        .footer-credit p{
          font-size: 0.875rem;
          color: #64748b;
          margin: 0;
        }
    </style>
</head>
<!-- <body style="background-color: #e2d8fe;"> -->
    {{-- Header atas --}}
    <nav class="navbar navbar-expand-lg" style="background-color: #c7cacf;">
        <div class="container-fluid">
            <div class="d-flex align-items-center">
                <img src="/assets/img/logo-RE.png" alt="logo" class="d-inline-block align-text-top ms-4" width="40" height="50">
                <span class="navbar-brand mb-0 h1 ms-4">Stock Barang & Obat</span>
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
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container-fluid ms-4">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item me">
                        <a class="nav-link px-2 {{ Request::is('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}"  style="color:midnightblue;"><i class="fa fa-home"></i>Dashboard</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="persediaanDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="color:midnightblue;">
                       <i class="fa fa-clipboard-list"></i>Persediaan
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="persediaanDropdown">
                            <li><a class="dropdown-item" href="{{ route('persediaan.index') }}" ><i class="fa fa-link" style="margin-right: 8px;"></i>Histori</a></li>
                            <li><a class="dropdown-item" href="{{ route('stock.index') }}"><i class="fa-solid fa-list" style="margin-right:8px;"></i>Stock</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle px-2" href="#" id="masterdataDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="color:midnightblue;">
                            <i class="fa fa-database"></i>Master Data
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="masterdataDropdown">
                            <li><a class="dropdown-item" href="{{ route('masterdata.kategori.index') }}"><i class="fa fa-tags" style="margin-right:8px;"></i>Kategori</a></li>
                            <li><a class="dropdown-item" href="{{ route('masterdata.ukuran.index') }}"><i class="fa fa-tape" style="margin-right:8px;"></i>Ukuran</a></li>
                            <li><a class="dropdown-item" href="{{ route('masterdata.lantai.index') }}"><i class="fa fa-layer-group" style="margin-right:8px;"></i>Lantai</a></li>
                            <li><a class="dropdown-item" href="{{ route('masterdata.produk.index') }}"><i class="fa fa-box" style="margin-right:8px;"></i>Produk</a></li>
                            <li><a class="dropdown-item" href="{{ route('person.index') }}"><i class="fa fa-user" style="margin-right:8px;"></i>Daftar CS</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    {{-- Konten halaman --}}
    
    @yield('content')
    
    <!-- footer -->
    @if(Request::is('dashboard'))
    <footer>
      <div class="footer-inner">
         <div class="footer-brand">
           <h4>Stock Barang & Obat</h4>
           <p>Sistem pengelolaan inventaris dan persediaan barang untuk kebutuhan operasional.</p>
         </div>
         <div class="footer-links">
             <h5>Navigasi</h5>
             <ul>
                 <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                 <li><a href="{{ route('persediaan.index') }}">Histori Persediaan</a></li>
                 <li><a href="{{ route('stock.index') }}">Stock Barang</a></li>
                 <li><a href="{{ route('masterdata.produk.index') }}">Master Data</a></li>
             </ul>
         </div>
         <div class="footer-contact">
             <h5>Kontak</h5>
             <p><i class="fas fa-envelope"></i> support@company.com</p>
             <p><i class="fas fa-phone"></i> +62 xxx xxxx</p>
             <p><i class="fas fa-map-marker-alt"></i> Jakarta, Indonesia</p>
         </div>
         <div class="footer-social">
             <h5>Ikuti Kami</h5>
             <a href="#" title="Instagram"><i class="fab fa-instagram"></i></a>
             <a href="#" title="Facebook"><i class="fab fa-facebook"></i></a>
             <a href="#" title="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
         </div>
       </div>
       <div class="footer-credit">
           <p>&copy; {{ date('Y') }} Stock Barang & Obat. All rights reserved.</p>
       </div>
    </footer>
    @endif
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
