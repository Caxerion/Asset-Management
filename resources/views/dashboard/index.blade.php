@extends('layouts.app')
<style>
    body{
        
    }
    .hero{
        position: relative;
        top: 0;
        left:0;
        min-height: 100vh;
        width: 100%;
        background-position: center;
        background-size: cover;
        background-repeat: no-repeat;
        background-image: linear-gradient(
            rgba(195, 194, 194, 0.4),
            rgba(245, 245, 245, 0.325)
        ),url(/assets/img/grahare.jpg);
    }
    .hero-content{
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        width: 100%;
        min-height: 100%;
        padding: 2rem;
    }
    .hero-text{
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 1rem;
        backdrop-filter: blur(3px);
    }
    .second-text p{
        font-size: 10px;
        text-align: center;
    }
    
    /* Toggle Button */
    .toggle-stats-btn {
        margin-top: 1rem;
        padding: 12px 30px;
        font-size: 16px;
        border-radius: 25px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
    }
    .toggle-stats-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(102, 126, 234, 0.6);
    }
    .toggle-stats-btn i {
        margin-right: 8px;
        transition: transform 0.3s ease;
    }
    .toggle-stats-btn.active i {
        transform: rotate(180deg);
    }
    
    /* Home Section - slides out to left */
    .home-section {
        transition: transform 0.5s ease-in-out, opacity 0.5s ease-in-out;
        transform: translateX(0);
        opacity: 1;
    }
    .home-section.hidden {
        transform: translateX(-100%);
        opacity: 0;
        position: absolute;
    }
    
    /* Stats Section - displayed as standalone page */
    .stats-section {
        background: rgba(255, 255, 255, 0.95);
        padding: 2rem;
        min-height: calc(100vh - 60px);
    }
    .stats-content {
        padding: 0;
    }
    .stats-content {
        padding: 80px 2rem 2rem 2rem;
        background: rgba(255, 255, 255, 0.9);
        min-height: 100%;
    }
    
    /* Close button for stats */
    .close-stats-btn {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 101;
        padding: 10px 20px;
        font-size: 14px;
        border-radius: 20px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        cursor: pointer;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
    }
    .close-stats-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(102, 126, 234, 0.6);
    }
    
    /* Chart sizing */
    #categoryPieChart {
        max-width: 300px;
        max-height: 300px;
        margin: 0 auto;
    }
    #periodBarChart {
        max-height: 300px;
    }
</style>
@section('content')
<!-- Home Section (only shown when no statistik parameter) -->
@if(!isset($showStats) || !$showStats)
<div class="hero home-section" id="homeSection">
    <div class="hero-content">
        <div class="hero-text">
            <h1>Selamat Datang di Dashboard</h1>
            <p class="second-text">Silakan pilih menu dari navigasi di atas.</p>
            
            <!-- Toggle Button -->
            <a href="{{ route('dashboard') }}?statistik" class="toggle-stats-btn">
                <i class="fas fa-chevron-down"></i> Lihat Statistik
            </a>
        </div>
    </div>
</div>
@endif

<!-- Stats Section (only shown when statistik parameter is present) -->
@if(isset($showStats) && $showStats)
<div class="stats-section" id="statsSection">
    <div class="stats-content">
        <div class="mb-3">
            <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12">
                    <h2 class="mb-4">Statistik Pengambilan</h2>
                </div>
            </div>

            <!-- Summary Cards -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <h5 class="card-title">Total Produk</h5>
                            <h2 class="mb-0">{{ $totalProduk }}</h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <h5 class="card-title">Total Stok</h5>
                            <h2 class="mb-0">{{ $totalStok }}</h2>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Row -->
            <div class="row mb-4">
                <!-- Pie Chart: Pickups by Category -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Pengambilan per Kategori</h5>
                        </div>
                        <div class="card-body">
                            <canvas id="categoryPieChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Bar Chart: Pickups by Period -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Pengambilan per Periode</h5>
                            <select id="periodSelector" class="form-select form-select-sm" style="width: auto;" onchange="updateBarChart(this.value)">
                                <option value="daily">Harian</option>
                                <option value="weekly">Mingguan</option>
                                <option value="monthly" selected>Bulanan</option>
                            </select>
                        </div>
                        <div class="card-body">
                            <canvas id="periodBarChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Pickups -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Riwayat Pengambilan Terbaru</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>No. Pengambilan</th>
                                            <th>Tanggal</th>
                                            <th>Peminta</th>
                                            <th>Lantai</th>
                                            <th>Produk</th>
                                            <th>Jumlah</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($recentPickups as $pickupLine)
                                        <tr>
                                            <td>{{ $pickupLine->pickup->pickup_no ?? '-' }}</td>
                                            <td>{{ $pickupLine->pickup->pickup_date ?? '-' }}</td>
                                            <td>{{ $pickupLine->pickup->user->name ?? '-' }}</td>
                                            <td>{{ $pickupLine->pickup->floor->name ?? '-' }}</td>
                                            <td>{{ $pickupLine->product->name ?? '-' }}</td>
                                            <td>{{ $pickupLine->qty }}</td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="6" class="text-center">Tidak ada data pengambilan</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            
                            <!-- Pagination -->
                            @if($recentPickups->hasPages())
                            <div class="mt-3">
                                {{ $recentPickups->links('components.custom-pagination') }}
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Font Awesome for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    let chartsLoaded = false;
    
    // Auto-open stats section if ?statistik parameter is present
    document.addEventListener('DOMContentLoaded', function() {
        @if(isset($showStats) && $showStats)
            // Load charts when stats section is shown
            if (!chartsLoaded) {
                loadCharts();
                chartsLoaded = true;
            }
        @endif
    });
    
    function loadCharts() {
        // Pie Chart - Pickups by Category with custom colors
        fetch('/dashboard/pickups-by-category')
            .then(res => res.json())
            .then(data => {
                // Map category names to specific colors
                const colorMap = {
                    'Produk Pembersih': '#FF0000',        // Red
                    'Pengharum & Pewangi': '#28a745',    // Green
                    'Alat Kebersihan': '#0d6efd',        // Blue
                    'Kain & Lap': '#ffc107',             // Yellow
                    'Perlengkapan Proteksi': '#6f42c1',   // Purple
                    'Perlatan & Lain-lain': '#fd7e14',    // Orange
                    'Plastik & Kemasan': '#6f42c1'       // Purple
                };
                
                const backgroundColor = data.labels.map(label => colorMap[label] || '#999999');
                
                new Chart(document.getElementById('categoryPieChart'), {
                    type: 'pie',
                    data: {
                        labels: data.labels,
                        datasets: [{
                            data: data.values,
                            backgroundColor: backgroundColor
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'bottom'
                            }
                        }
                    }
                });
            })
            .catch(err => console.error('Error loading pie chart:', err));

        // Initialize bar chart with default (monthly)
        updateBarChart('monthly');
    }

    // Bar Chart - Pickups by Period
    let barChart;

    function updateBarChart(period) {
        fetch(`/dashboard/pickups-by-period?period=${period}`)
            .then(res => res.json())
            .then(data => {
                const ctx = document.getElementById('periodBarChart');
                
                if (barChart) {
                    barChart.destroy();
                }
                
                barChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: data.labels,
                        datasets: [{
                            label: 'Jumlah Pengambilan',
                            data: data.values,
                            backgroundColor: 'rgba(54, 162, 235, 0.2)',
                            borderColor: '#36A2EB',
                            borderWidth: 2,
                            fill: true,
                            tension: 0.3
                        }]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    stepSize: 1
                                }
                            }
                        }
                    }
                });
            })
            .catch(err => console.error('Error loading bar chart:', err));
    }
</script>
@endsection

