@extends('layouts.app')
@section('title', 'Beranda')
@section('content')
<div class="container-fluid">
    <!-- Breadcrumb -->
    <div class="nav-item">
        <i class="fas fa-fw fa-tachometer-alt"></i> / <span>Beranda</span>
    </div>

    <!-- Greeting -->
    <h1 class="display-6 mt-4">Hi, {{ Auth::user()->name }}!</h1>
    <p class="lead">"Selamat Datang di Aplikasi Sistem Terstruktur Arsip LLDIKTI WILAYAH IX"</p>

    <!-- Cards Row -->
    <div class="row">
        <!-- Total Documents -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Dokumen</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalPdfCount }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-file-pdf fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Unit Pengelola -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Unit Pengelola</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $unitPengelolaCount }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-sitemap fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Klasifikasi Arsip -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Klasifikasi Arsip</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $klasifikasiCount }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-tags fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Users -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total Pengguna</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $userCount }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="row">
        <!-- Area Chart - Tren Upload Dokumen -->
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Tren Upload Dokumen (6 Bulan Terakhir)</h6>
                    <div class="dropdown no-arrow">
                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown">
                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="monthlyTrendChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pie Chart -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Distribusi Dokumen</h6>
                </div>
                <div class="card-body">
                    <div class="chart-pie pt-4 pb-2">
                        <canvas id="documentPieChart"></canvas>
                    </div>
                    <div class="mt-4 text-center small">
                        <span class="mr-2"><i class="fas fa-circle text-primary"></i> HKT</span>
                        <span class="mr-2"><i class="fas fa-circle text-success"></i> Keuangan</span>
                        <span class="mr-2"><i class="fas fa-circle text-info"></i> Kelembagaan</span>
                        <span class="mr-2"><i class="fas fa-circle text-warning"></i> Kemahasiswaan</span>
                        <span class="mr-2"><i class="fas fa-circle text-danger"></i> Akademik</span>
                        <span class="mr-2"><i class="fas fa-circle text-secondary"></i> SDPT</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bar Chart - Top Unit Pengelola -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Top 5 Unit Pengelola (Berdasarkan Jumlah Dokumen)</h6>
                </div>
                <div class="card-body">
                    <div class="chart-bar">
                        <canvas id="topUnitsChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <div class="row text-center mt-4">
        <div class="col-md-4 mb-4">
            <div class="card shadow h-100 py-3">
                <div class="card-body">
                    <i class="fas fa-folder fa-3x text-primary mb-3"></i>
                    <h5 class="card-title font-weight-bold">TRACKING ARSIP</h5>
                    <p class="card-text">Fitur tracking arsip memungkinkan pengguna melacak dan memantau aktivitas arsip secara real-time.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card shadow h-100 py-3">
                <div class="card-body">
                    <i class="fas fa-folder-open fa-3x text-success mb-3"></i>
                    <h5 class="card-title font-weight-bold">KELOLA ARSIP</h5>
                    <p class="card-text">Fitur untuk mengelola dokumen secara efektif melalui platform digital.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card shadow h-100 py-3">
                <div class="card-body">
                    <i class="fas fa-database fa-3x text-info mb-3"></i>
                    <h5 class="card-title font-weight-bold">DATA MASTER</h5>
                    <p class="card-text">Data master berisi informasi dasar sebagai acuan utama dalam sistem.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Data dari controller
        const documentCounts = @json($documentCounts, JSON_THROW_ON_ERROR);
        const monthlyTrend = @json($monthlyTrend, JSON_THROW_ON_ERROR);
        const topUnits = @json($topUnits, JSON_THROW_ON_ERROR);

        const colors = ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b', '#858796'];

        // 1. Pie Chart - Distribusi Dokumen (dengan label dan persentase)
        const pieCtx = document.getElementById('documentPieChart').getContext('2d');
        const totalDocs = Object.values(documentCounts).reduce((a, b) => a + b, 0);

        new Chart(pieCtx, {
            type: 'pie',
            data: {
                labels: Object.keys(documentCounts),
                datasets: [{
                    data: Object.values(documentCounts),
                    backgroundColor: colors,
                    hoverBackgroundColor: colors,
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                maintainAspectRatio: false,
                legend: {
                    display: true,
                    position: 'bottom',
                },
                tooltips: {
                    backgroundColor: 'rgb(255,255,255)',
                    bodyFontColor: '#858796',
                    borderColor: '#dddfeb',
                    borderWidth: 1,
                    displayColors: true,
                    callbacks: {
                        label: function(tooltipItem, data) {
                            const dataset = data.datasets[tooltipItem.datasetIndex];
                            const value = dataset.data[tooltipItem.index];
                            const label = data.labels[tooltipItem.index];
                            const percentage = ((value / totalDocs) * 100).toFixed(1);
                            return label + ': ' + value + ' dokumen (' + percentage + '%)';
                        }
                    }
                }
            }
        });

        // 2. Area Chart - Tren Upload Dokumen 6 Bulan Terakhir
        const areaCtx = document.getElementById('monthlyTrendChart').getContext('2d');

        new Chart(areaCtx, {
            type: 'line',
            data: {
                labels: monthlyTrend.labels,
                datasets: [{
                    label: 'Jumlah Dokumen',
                    data: monthlyTrend.data,
                    backgroundColor: 'rgba(78, 115, 223, 0.1)',
                    borderColor: 'rgba(78, 115, 223, 1)',
                    borderWidth: 2,
                    pointRadius: 4,
                    pointBackgroundColor: 'rgba(78, 115, 223, 1)',
                    pointBorderColor: '#fff',
                    pointHoverRadius: 6,
                    pointHoverBackgroundColor: 'rgba(78, 115, 223, 1)',
                    pointHoverBorderColor: '#fff',
                    fill: true,
                    tension: 0.3
                }]
            },
            options: {
                maintainAspectRatio: false,
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true,
                            stepSize: Math.ceil(Math.max(...monthlyTrend.data) / 5),
                            callback: function(value) {
                                return value.toLocaleString('id-ID');
                            }
                        },
                        gridLines: {
                            color: 'rgba(0, 0, 0, 0.05)',
                            drawBorder: false
                        }
                    }],
                    xAxes: [{
                        gridLines: {
                            display: false
                        }
                    }]
                },
                legend: {
                    display: true,
                    position: 'top'
                },
                tooltips: {
                    backgroundColor: 'rgb(255,255,255)',
                    bodyFontColor: '#858796',
                    borderColor: '#dddfeb',
                    borderWidth: 1,
                    displayColors: true,
                    callbacks: {
                        label: function(tooltipItem) {
                            return 'Dokumen: ' + tooltipItem.yLabel.toLocaleString('id-ID');
                        }
                    }
                }
            }
        });

        // 3. Bar Chart - Top 5 Unit Pengelola
        const barCtx = document.getElementById('topUnitsChart').getContext('2d');

        new Chart(barCtx, {
            type: 'bar',
            data: {
                labels: topUnits.labels,
                datasets: [{
                    label: 'Jumlah Dokumen',
                    data: topUnits.data,
                    backgroundColor: [
                        'rgba(78, 115, 223, 0.8)',
                        'rgba(28, 200, 138, 0.8)',
                        'rgba(54, 185, 204, 0.8)',
                        'rgba(246, 194, 62, 0.8)',
                        'rgba(231, 74, 59, 0.8)'
                    ],
                    borderColor: [
                        'rgba(78, 115, 223, 1)',
                        'rgba(28, 200, 138, 1)',
                        'rgba(54, 185, 204, 1)',
                        'rgba(246, 194, 62, 1)',
                        'rgba(231, 74, 59, 1)'
                    ],
                    borderWidth: 2,
                    borderRadius: 5
                }]
            },
            options: {
                maintainAspectRatio: false,
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true,
                            stepSize: Math.ceil(Math.max(...topUnits.data) / 5),
                            callback: function(value) {
                                return value.toLocaleString('id-ID');
                            }
                        },
                        gridLines: {
                            color: 'rgba(0, 0, 0, 0.05)',
                            drawBorder: false
                        }
                    }],
                    xAxes: [{
                        gridLines: {
                            display: false
                        },
                        ticks: {
                            maxRotation: 45,
                            minRotation: 0
                        }
                    }]
                },
                legend: {
                    display: false
                },
                tooltips: {
                    backgroundColor: 'rgb(255,255,255)',
                    bodyFontColor: '#858796',
                    borderColor: '#dddfeb',
                    borderWidth: 1,
                    displayColors: true,
                    callbacks: {
                        label: function(tooltipItem) {
                            return 'Dokumen: ' + tooltipItem.yLabel.toLocaleString('id-ID');
                        }
                    }
                }
            }
        });
    });
</script>
@endsection
