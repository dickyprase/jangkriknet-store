<?php
include 'header.php';

// Get all orders
$result_pesanan = get_all_orders();
$pesanan_list = $result_pesanan['data'] ?? [];

// Get date range for current month
$start_date = date('Y-m-01');
$end_date = date('Y-m-t');

// Get sales report data (with safety checks)
$sales_report = get_sales_report($start_date, $end_date);
$sales_data = $sales_report['data'] ?? [];

$revenue_result = get_total_revenue($start_date, $end_date);
$total_revenue = 0;
if ($revenue_result['status'] && isset($revenue_result['data']['total_revenue'])) {
    $total_revenue = (float) $revenue_result['data']['total_revenue'];
}

// Count order statuses
$pending_count = 0;
$proses_count = 0;
$sukses_count = 0;
$ditolak_count = 0;
foreach ($pesanan_list as $order) {
    switch ($order['status']) {
        case 'Pending': $pending_count++; break;
        case 'Proses Pengiriman': $proses_count++; break;
        case 'Sukses': $sukses_count++; break;
        case 'Ditolak': $ditolak_count++; break;
    }
}
?>

<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h1 class="mt-4">Dashboard</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item active">Dashboard</li>
            </ol>

            <!-- Summary Cards -->
            <div class="row mb-4">
                <div class="col-xl-3 col-md-6">
                    <div class="card border-start border-primary border-4 shadow-sm mb-3">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="text-uppercase text-muted small fw-bold mb-1">Total Pendapatan</div>
                                    <div class="h5 mb-0 fw-bold text-dark">Rp <?= number_format($total_revenue, 0, ',', '.') ?></div>
                                </div>
                                <div class="text-primary opacity-50">
                                    <i class="fas fa-wallet fa-2x"></i>
                                </div>
                            </div>
                            <div class="text-muted small mt-2"><i class="fas fa-calendar-alt me-1"></i>Bulan ini</div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card border-start border-success border-4 shadow-sm mb-3">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="text-uppercase text-muted small fw-bold mb-1">Total Pesanan</div>
                                    <div class="h5 mb-0 fw-bold text-dark"><?= count($pesanan_list) ?></div>
                                </div>
                                <div class="text-success opacity-50">
                                    <i class="fas fa-shopping-bag fa-2x"></i>
                                </div>
                            </div>
                            <div class="text-muted small mt-2"><i class="fas fa-check-circle me-1"></i>Semua pesanan</div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card border-start border-warning border-4 shadow-sm mb-3">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="text-uppercase text-muted small fw-bold mb-1">Pending</div>
                                    <div class="h5 mb-0 fw-bold text-dark"><?= $pending_count ?></div>
                                </div>
                                <div class="text-warning opacity-50">
                                    <i class="fas fa-clock fa-2x"></i>
                                </div>
                            </div>
                            <div class="text-muted small mt-2"><i class="fas fa-exclamation-circle me-1"></i>Perlu tindakan</div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card border-start border-info border-4 shadow-sm mb-3">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="text-uppercase text-muted small fw-bold mb-1">Sukses</div>
                                    <div class="h5 mb-0 fw-bold text-dark"><?= $sukses_count ?></div>
                                </div>
                                <div class="text-info opacity-50">
                                    <i class="fas fa-check-double fa-2x"></i>
                                </div>
                            </div>
                            <div class="text-muted small mt-2"><i class="fas fa-thumbs-up me-1"></i>Selesai</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts -->
            <div class="row mb-4">
                <div class="col-xl-8">
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-white py-3">
                            <h6 class="m-0 fw-bold text-dark">
                                <i class="fas fa-chart-line me-2 text-primary"></i>Grafik Pendapatan & Pesanan
                            </h6>
                        </div>
                        <div class="card-body">
                            <canvas id="mainChart" height="300"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4">
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-white py-3">
                            <h6 class="m-0 fw-bold text-dark">
                                <i class="fas fa-chart-pie me-2 text-primary"></i>Status Pesanan
                            </h6>
                        </div>
                        <div class="card-body d-flex align-items-center justify-content-center">
                            <canvas id="statusChart" height="250"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Orders Table -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 fw-bold text-dark">
                        <i class="fas fa-table me-2 text-primary"></i>Transaksi Terbaru
                    </h6>
                    <a href="pesanan.php" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
                </div>
                <div class="card-body">
                    <table id="datatablesSimple">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode Invoice</th>
                                <th>Nama Pelanggan</th>
                                <th>Total Harga</th>
                                <th>Status</th>
                                <th>Tanggal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $no = 1;
                            foreach ($pesanan_list as $p): 
                            ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><span class="fw-medium"><?= $p['kode_invoice'] ?></span></td>
                                <td><?= $p['nama_user'] ?></td>
                                <td>Rp <?= number_format($p['total_harga'], 0, ',', '.') ?></td>
                                <td>
                                    <span class="badge <?= get_status_badge_class($p['status']) ?>">
                                        <?= $p['status'] ?>
                                    </span>
                                </td>
                                <td><?= date('d/m/Y H:i', strtotime($p['tanggal'])) ?></td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#modalDetail<?= $p['id'] ?>">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <a href="invoice.php?id=<?= $p['id'] ?>" target="_blank" class="btn btn-sm btn-primary">
                                        <i class="fas fa-file-invoice"></i>
                                    </a>
                                    
                                    <!-- Modal Detail -->
                                    <div class="modal fade" id="modalDetail<?= $p['id'] ?>" tabindex="-1" aria-labelledby="modalDetailLabel<?= $p['id'] ?>" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header bg-light">
                                                    <h5 class="modal-title" id="modalDetailLabel<?= $p['id'] ?>">
                                                        <i class="fas fa-info-circle me-2"></i>Detail Pesanan
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body p-4">
                                                    <?php
                                                    $order_details = get_order_details($p['id']);
                                                    $payment_info = get_payment_by_order_id($p['id']);
                                                    ?>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <h6 class="mb-3">Informasi Pelanggan</h6>
                                                            <p><strong>Nama:</strong> <?= $p['nama_user'] ?></p>
                                                            <p><strong>No. Telp:</strong> <?= $p['no_telp'] ?></p>
                                                            <p><strong>Alamat:</strong> <?= $p['alamat'] ?></p>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <h6 class="mb-3">Informasi Pesanan</h6>
                                                            <p><strong>Kode Invoice:</strong> <?= $p['kode_invoice'] ?></p>
                                                            <p><strong>Tanggal:</strong> <?= date('d/m/Y H:i', strtotime($p['tanggal'])) ?></p>
                                                            <p><strong>Status:</strong> 
                                                                <span class="badge <?= get_status_badge_class($p['status']) ?>">
                                                                    <?= $p['status'] ?>
                                                                </span>
                                                            </p>
                                                            <p><strong>Total:</strong> Rp <?= number_format($p['total_harga'], 0, ',', '.') ?></p>
                                                        </div>
                                                    </div>

                                                    <hr>

                                                    <h6 class="mb-3">Detail Produk</h6>
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered">
                                                            <thead>
                                                                <tr>
                                                                    <th>Produk</th>
                                                                    <th>Harga</th>
                                                                    <th>Qty</th>
                                                                    <th>Subtotal</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php if ($order_details['status'] && !empty($order_details['data'])): ?>
                                                                <?php foreach ($order_details['data'] as $item): ?>
                                                                <tr>
                                                                    <td>
                                                                        <div class="d-flex align-items-center">
                                                                            <img src="uploads/<?= $item['gambar'] ?>" alt="<?= $item['nama_produk'] ?>" style="width: 50px; height: 50px; object-fit: cover; margin-right: 10px;">
                                                                            <?= $item['nama_produk'] ?>
                                                                        </div>
                                                                    </td>
                                                                    <td>Rp <?= number_format($item['harga'], 0, ',', '.') ?></td>
                                                                    <td><?= $item['qty'] ?></td>
                                                                    <td>Rp <?= number_format($item['harga'] * $item['qty'], 0, ',', '.') ?></td>
                                                                </tr>
                                                                <?php endforeach; ?>
                                                                <?php else: ?>
                                                                <tr><td colspan="4" class="text-center text-muted">Tidak ada data</td></tr>
                                                                <?php endif; ?>
                                                            </tbody>
                                                        </table>
                                                    </div>

                                                    <?php if ($payment_info['status'] && !empty($payment_info['data'])): ?>
                                                    <hr>
                                                    <h6 class="mb-3">Informasi Pembayaran</h6>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <p><strong>Metode Pembayaran:</strong> <?= ucfirst($payment_info['data']['metode_pembayaran']) ?></p>
                                                            <p><strong>Status Pembayaran:</strong> 
                                                                <span class="badge <?= $payment_info['data']['status'] == 'valid' ? 'bg-success' : ($payment_info['data']['status'] == 'pending' ? 'bg-warning' : 'bg-danger') ?>">
                                                                    <?= ucfirst($payment_info['data']['status']) ?>
                                                                </span>
                                                            </p>
                                                        </div>
                                                        <?php if (!empty($payment_info['data']['bukti_pembayaran'])): ?>
                                                        <div class="col-md-6">
                                                            <p><strong>Bukti Pembayaran:</strong></p>
                                                            <img src="../uploads/pembayaran/<?= $payment_info['data']['bukti_pembayaran'] ?>" alt="Bukti Pembayaran" style="max-width: 200px;" class="rounded">
                                                        </div>
                                                        <?php endif; ?>
                                                    </div>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="modal-footer bg-light">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                        <i class="fas fa-times me-2"></i>Tutup
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Safe data
        const transactionData = <?= json_encode($sales_data ?: []) ?>;
        
        // Process data for charts
        function processData(data) {
            if (!data || data.length === 0) {
                return { labels: ['Tidak ada data'], revenue: [0], transactions: [0] };
            }

            const dateMap = new Map();
            data.forEach(item => {
                const dateKey = item.tanggal ? item.tanggal.split(' ')[0] : 'Unknown';
                if (!dateMap.has(dateKey)) {
                    dateMap.set(dateKey, { revenue: 0, count: 0 });
                }
                const entry = dateMap.get(dateKey);
                entry.revenue += parseFloat(item.total_harga || 0);
                entry.count += 1;
            });

            const sortedDates = Array.from(dateMap.keys()).sort();
            return {
                labels: sortedDates.map(date => {
                    try { return new Date(date).toLocaleDateString('id-ID', { day: 'numeric', month: 'short' }); } 
                    catch(e) { return date; }
                }),
                revenue: sortedDates.map(date => dateMap.get(date).revenue),
                transactions: sortedDates.map(date => dateMap.get(date).count)
            };
        }

        const chartData = processData(transactionData);

        // Line Chart
        new Chart(document.getElementById('mainChart'), {
            type: 'line',
            data: {
                labels: chartData.labels,
                datasets: [
                    {
                        label: 'Pendapatan (Rp)',
                        data: chartData.revenue,
                        borderColor: '#4f46e5',
                        backgroundColor: 'rgba(79, 70, 229, 0.08)',
                        borderWidth: 2,
                        tension: 0.4,
                        fill: true,
                        pointBackgroundColor: '#4f46e5',
                        pointRadius: 4,
                        yAxisID: 'y'
                    },
                    {
                        label: 'Jumlah Pesanan',
                        data: chartData.transactions,
                        borderColor: '#10b981',
                        backgroundColor: 'rgba(16, 185, 129, 0.08)',
                        borderWidth: 2,
                        tension: 0.4,
                        fill: true,
                        pointBackgroundColor: '#10b981',
                        pointRadius: 4,
                        yAxisID: 'y1'
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: { mode: 'index', intersect: false },
                plugins: {
                    legend: { position: 'top' },
                    tooltip: {
                        callbacks: {
                            label: function(ctx) {
                                if (ctx.dataset.yAxisID === 'y') {
                                    return ctx.dataset.label + ': Rp ' + ctx.raw.toLocaleString('id-ID');
                                }
                                return ctx.dataset.label + ': ' + ctx.raw;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        type: 'linear', display: true, position: 'left',
                        grid: { color: '#f0f0f0' },
                        ticks: {
                            callback: function(v) { return 'Rp ' + (v/1000).toLocaleString('id-ID') + 'k'; }
                        }
                    },
                    y1: {
                        type: 'linear', display: true, position: 'right',
                        grid: { drawOnChartArea: false },
                        ticks: { stepSize: 1 }
                    },
                    x: { grid: { display: false } }
                }
            }
        });

        // Doughnut Chart
        new Chart(document.getElementById('statusChart'), {
            type: 'doughnut',
            data: {
                labels: ['Pending', 'Proses Pengiriman', 'Sukses', 'Ditolak'],
                datasets: [{
                    data: [<?= $pending_count ?>, <?= $proses_count ?>, <?= $sukses_count ?>, <?= $ditolak_count ?>],
                    backgroundColor: ['#f59e0b', '#0ea5e9', '#10b981', '#ef4444'],
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom' }
                },
                cutout: '65%'
            }
        });
    </script>

    <?php include 'footer.php'; ?>