<!-- <!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Persediaan</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
</head>
<body>
    <div class="sidebar">
        <h2>Stock Barang & Obat</h2>
        <a href="#">Dashboard</a>
        <a href="#">Persediaan</a>
        <a href="#">Master Data</a>
        <a href="#">Histori Pengambilan</a>
        <a href="#">Stock Barang</a>
        <a href="#">Profile Settings</a>
        <a href="{{ route('logout') }}" 
           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
           Logout
        </a>
    </div>

    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>

    <div class="content">
        <div class="header">
            <h1>Dashboard</h1>
            <a href="#" class="btn btn-primary">+ Catat Pengambilan</a>
        </div>

        <div class="dashboard-grid">
            <div class="card">
                <h3>Total Produk</h3>
                <p>{{ \App\Models\Product::count() }}</p>
            </div>
            <div class="card">
                <h3>Total Stok</h3>
                <p>{{ \App\Models\StockBalance::sum('qty_on_hand') }}</p>
            </div>
        </div>

        <div class="table-container">
            <h3>Histori Pengambilan</h3>
            <div class="search-bar">
                <input type="text" placeholder="Cari Histori Pengambilan Tanggal/Nama/Barang">
            </div>
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama Pengambil Barang</th>
                        <th>Nama Barang</th>
                        <th>Tanggal Pengambilan</th>
                        <th>Lantai</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentPickups as $pickupLine)
                        <tr>
                            <td>{{ $pickupLine->pickup->id }}</td>
                            <td>{{ $pickupLine->pickup->user->name }}</td>
                            <td>
                                <span class="label">{{ $pickupLine->product->name }}</span>
                                ({{ $pickupLine->qty }})
                            </td>
                            <td>{{ $pickupLine->pickup->created_at->format('d M Y H:i') }}</td>
                            <td>{{ $pickupLine->pickup->floor->name }}</td>
                            <td><a href="#" class="btn btn-secondary">Detail</a></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">Belum ada histori pengambilan</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</body>
</html> -->