<?php
include 'header.php';
require_once '../config/function.php';

// Get date range from request or default to current month
$start_date = isset($_GET['start_date']) ? $_GET['start_date'] : date('Y-m-01');
$end_date = isset($_GET['end_date']) ? $_GET['end_date'] : date('Y-m-t');

// Get sales report data
$sales_report = get_sales_report($start_date, $end_date);
$total_revenue = get_total_revenue($start_date, $end_date);
?>

<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h1 class="mt-4">Laporan</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item">Home</li>
                <li class="breadcrumb-item">Laporan</li>
                <li class="breadcrumb-item active">Keuangan</li>
            </ol>
            <hr>

            <!-- Filter Section -->
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-filter me-1"></i>
                    Filter Laporan
                </div>
                <div class="card-body">
                    <form method="GET" class="row g-3">
                        <div class="col-md-4">
                            <label for="start_date" class="form-label">Tanggal Mulai</label>
                            <input type="date" class="form-control" id="start_date" name="start_date" value="<?= $start_date ?>">
                        </div>
                        <div class="col-md-4">
                            <label for="end_date" class="form-label">Tanggal Akhir</label>
                            <input type="date" class="form-control" id="end_date" name="end_date" value="<?= $end_date ?>">
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary me-2">Filter</button>
                            <a href="export_pdf.php?start_date=<?= $start_date ?>&end_date=<?= $end_date ?>" class="btn btn-danger">
                                <i class="fas fa-file-pdf me-1"></i> Export PDF
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Summary Cards -->
            <div class="row">
                <div class="col-xl-3 col-md-6">
                    <div class="card bg-primary text-white mb-4">
                        <div class="card-body">
                            <h4>Total Pendapatan</h4>
                            <h2>Rp <?= number_format($total_revenue['data']['total_revenue'], 0, ',', '.') ?></h2>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card bg-success text-white mb-4">
                        <div class="card-body">
                            <h4>Total Transaksi</h4>
                            <h2><?= count($sales_report['data']) ?></h2>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts -->
            <div class="row">
                <div class="col-xl-6">
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-chart-line me-1"></i>
                            Grafik Pendapatan
                        </div>
                        <div class="card-body">
                            <canvas id="revenueChart" width="100%" height="100"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6">
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-chart-bar me-1"></i>
                            Grafik Transaksi
                        </div>
                        <div class="card-body">
                            <canvas id="transactionChart" width="100%" height="100"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Transaction Table -->
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-table me-1"></i>
                    Daftar Transaksi
                </div>
                <div class="card-body">
                    <table id="datatablesSimple" class="table table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Kode Invoice</th>
                                <th>Pelanggan</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Metode Pembayaran</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $no = 1;
                            foreach ($sales_report['data'] as $transaction): 
                            ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= date('d/m/Y H:i', strtotime($transaction['tanggal'])) ?></td>
                                <td><?= $transaction['kode_invoice'] ?></td>
                                <td><?= $transaction['nama_user'] ?></td>
                                <td>Rp <?= number_format($transaction['total_harga'], 0, ',', '.') ?></td>
                                <td>
                                    <span class="badge <?= $transaction['status'] == 'Sukses' ? 'bg-success' : 'bg-warning' ?>">
                                        <?= $transaction['status'] ?>
                                    </span>
                                </td>
                                <td><?= ucfirst($transaction['metode_pembayaran']) ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

    <!-- Chart Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Data untuk chart
        const transactionData = <?= json_encode($sales_report['data']) ?>;
        
        // Fungsi untuk memformat data untuk chart
        function formatChartData(data) {
            const dates = [...new Set(data.map(item => item.tanggal.split(' ')[0]))].sort();
            const revenue = dates.map(date => {
                return data
                    .filter(item => item.tanggal.startsWith(date))
                    .reduce((sum, item) => sum + parseFloat(item.total_harga), 0);
            });
            const transactions = dates.map(date => {
                return data.filter(item => item.tanggal.startsWith(date)).length;
            });
            
            return {
                labels: dates.map(date => new Date(date).toLocaleDateString('id-ID', { weekday: 'short', day: 'numeric' })),
                revenue: revenue,
                transactions: transactions
            };
        }

        const chartData = formatChartData(transactionData);

        // Revenue Chart
        new Chart(document.getElementById('revenueChart'), {
            type: 'line',
            data: {
                labels: chartData.labels,
                datasets: [{
                    label: 'Pendapatan',
                    data: chartData.revenue,
                    fill: true,
                    backgroundColor: 'rgba(13, 110, 253, 0.2)',
                    borderColor: '#0d6efd',
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return 'Rp ' + context.raw.toLocaleString('id-ID');
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + value.toLocaleString('id-ID');
                            }
                        }
                    }
                }
            }
        });

        // Transaction Chart
        new Chart(document.getElementById('transactionChart'), {
            type: 'bar',
            data: {
                labels: chartData.labels,
                datasets: [{
                    label: 'Jumlah Transaksi',
                    data: chartData.transactions,
                    backgroundColor: '#198754',
                    borderColor: '#198754',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
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

        // Print Report Function
        function printReport() {
            window.print();
        }
    </script>

    <?php include 'footer.php'; ?>