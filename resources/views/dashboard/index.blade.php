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
            <h1>PTRE Stock Management</h1>
            <p class="second-text">Aplikasi Manajemen stok dan pengambilan</p>
            
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

            <!-- Date Filter Form -->
            <form method="GET" action="{{ route('dashboard') }}" class="mb-4">
                <input type="hidden" name="statistik" value="1">
                <div class="row align-items-end">
                    <div class="col-md-3">
                        <label for="start_date" class="form-label">Tanggal Mulai</label>
                        <input type="date" name="start_date" id="start_date" class="form-control" value="{{ request('start_date', now()->startOfMonth()->format('Y-m-d')) }}">
                    </div>
                    <div class="col-md-3">
                        <label for="end_date" class="form-label">Tanggal Akhir</label>
                        <input type="date" name="end_date" id="end_date" class="form-control" value="{{ request('end_date', now()->format('Y-m-d')) }}">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-filter"></i> Filter
                        </button>
                    </div>
                    <div class="col-md-2">
                        <a href="{{ route('dashboard') }}?statistik" class="btn btn-secondary w-100">
                            <i class="fas fa-reset"></i> Reset
                        </a>
                    </div>
                </div>
            </form>

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
                <div class="col-md-6">
                    <div class="card bg-danger text-white" style="margin-top: 20px;">
                        <div class="card-body">
                            <h5 class="card-title">Jumlah Customer Services</h5>
                            <h2 class="mb-0">{{ $totalPerson }}</h2>
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

                <!-- Bar Chart: Pickups by Floor -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Pengambilan per Lantai</h5>
                        </div>
                        <div class="card-body" style="height: 300px;">
                            <canvas id="floorBarChart"></canvas>
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
        // Get date values from the form
        const startDate = document.getElementById('start_date')?.value || '';
        const endDate = document.getElementById('end_date')?.value || '';
        
        // Only add date params if both dates are provided
        let dateParams = '';
        if (startDate && endDate) {
            dateParams = `?start_date=${startDate}&end_date=${endDate}`;
        }
        
        // Pie Chart - Pickups by Category with custom colors
        fetch('/dashboard/pickups-by-category' + dateParams)
            .then(res => res.json())
            .then(data => {
                // Map category names to specific colors
                const colorMap = {
                    'Produk Pembersih': '#d32828',        // Red
                    'Pengharum & Pewangi': '#2bc34e',    // Green
                    'Alat Kebersihan': '#2b6dd0',        // Blue
                    'Kain & Lap': '#cca739',             // Yellow
                    'Perlengkapan Proteksi': '#6f40c5',   // Purple
                    'Perlatan & Lain-lain': '#ce7731',    // Orange
                    'Plastik & Kemasan': '#4e2896'       // Dark Purple
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

        // Initialize bar chart - Pickups by Floor
        const floorUrl = '/dashboard/pickups-by-floor' + dateParams;
        console.log('Floor chart URL:', floorUrl);
        
        fetch(floorUrl)
            .then(res => {
                console.log('Floor chart response:', res);
                return res.json();
            })
            .then(data => {
                console.log('Floor chart data:', data);
                
                // If no data, show empty arrays
                const labels = data.labels && data.labels.length > 0 ? data.labels : ['No Data'];
                const values = data.values && data.values.length > 0 ? data.values : [0];
                
                const ctx = document.getElementById('floorBarChart');
                
                // Define colors for each floor
                const colors = [
                    '#FF6384', '#00d945', '#FFCE56', '#439ed2', 
                    '#9966FF', '#FF9F40', '#C9CBCF', '#4D5360'
                ];
                
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Jumlah Pengambilan',
                            data: values,
                            backgroundColor: labels.map((_, i) => colors[i % colors.length]),
                            borderWidth: 1
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
                        },
                        plugins: {
                            legend: {
                                display: false
                            }
                        }
                    }
                });
            })
            .catch(err => console.error('Error loading floor chart:', err));
    }
</script>
@endsection

