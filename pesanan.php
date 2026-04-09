<?php
include 'header.php';

// Get user's orders
$orders = get_all_orders(['id_user' => $_SESSION['user']['id']]);
?>


<!-- Header -->
<div class="bg-primary-custom py-5 mb-5 text-white">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h1 class="fw-bold">Riwayat Pesanan</h1>
                <p class="lead mb-0 opacity-75">Pantau status pesanan dan pembelian Anda.</p>
            </div>
            <div class="col-md-4 text-md-end mt-3 mt-md-0">
                <a href="akun.php" class="btn btn-outline-light">
                    <i class="bi bi-person me-2"></i>Kembali ke Profil
                </a>
            </div>
        </div>
    </div>
</div>

<section class="mb-5">
    <div class="container">
        <?php if (empty($orders['data'])): ?>
        <div class="text-center py-5">
             <i class="bi bi-box-seam display-1 text-muted mb-4 opacity-50"></i>
            <h2 class="h4 mb-3">Belum Ada Pesanan</h2>
            <p class="text-muted mb-4">Anda belum pernah melakukan pemesanan.</p>
            <a href="index.php" class="btn btn-primary btn-lg shadow-sm">
                <i class="bi bi-bag-plus me-2"></i>Mulai Belanja
            </a>
        </div>
        <?php else: ?>
        <div class="row g-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-header bg-white border-bottom py-3">
                         <h5 class="mb-0 fw-bold"><i class="bi bi-list-check me-2"></i>Daftar Transaksi</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light text-muted small text-uppercase">
                                    <tr>
                                        <th scope="col" class="ps-4 py-3">Order ID</th>
                                        <th scope="col" class="py-3">Tanggal</th>
                                        <th scope="col" class="py-3">Item</th>
                                        <th scope="col" class="py-3">Total Belanja</th>
                                        <th scope="col" class="py-3 text-center">Status</th>
                                        <th scope="col" class="text-end pe-4 py-3">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($orders['data'] as $order): 
                                         $details = get_order_details($order['id']);
                                         $total_qty = 0;
                                         if ($details['status']) {
                                             foreach ($details['data'] as $d) $total_qty += $d['qty'];
                                         }
                                    ?>
                                    <tr>
                                        <td class="ps-4 py-3 fw-bold text-primary">
                                            #<?= $order['kode_invoice'] ?>
                                        </td>
                                        <td><?= date('d M Y H:i', strtotime($order['tanggal'])) ?></td>
                                        <td>
                                            <span class="badge bg-light text-dark border">
                                                <?= $total_qty ?> item
                                            </span>
                                        </td>
                                        <td class="fw-bold">Rp <?= number_format($order['total_harga'], 0, ',', '.') ?></td>
                                        <td class="text-center">
                                            <?php
                                                $status_class = 'bg-secondary';
                                                $status_icon = 'bi-question-circle';
                                                switch($order['status']) {
                                                    case 'pending': $status_class = 'bg-warning text-dark'; $status_icon = 'bi-clock'; break;
                                                    case 'proses': $status_class = 'bg-info text-dark'; $status_icon = 'bi-gear-wide-connected'; break;
                                                    case 'dikirim': $status_class = 'bg-primary'; $status_icon = 'bi-truck'; break;
                                                    case 'selesai': $status_class = 'bg-success'; $status_icon = 'bi-check-circle'; break;
                                                    case 'batal': $status_class = 'bg-danger'; $status_icon = 'bi-x-circle'; break;
                                                }
                                            ?>
                                            <span class="badge <?= $status_class ?> rounded-pill px-3 py-2">
                                                <i class="bi <?= $status_icon ?> me-1"></i><?= ucfirst($order['status']) ?>
                                            </span>
                                        </td>
                                        <td class="text-end pe-4">
                                            <button class="btn btn-outline-primary btn-sm me-1 shadow-sm" onclick="viewOrderDetails(<?= $order['id'] ?>)">
                                                <i class="bi bi-eye"></i> Detail
                                            </button>
                                            <a href="admin/invoice.php?id=<?= $order['id'] ?>" target="_blank" class="btn btn-outline-secondary btn-sm shadow-sm">
                                                <i class="bi bi-printer"></i> Invoice
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</section>

<!-- Order Details Modal -->
<div class="modal fade" id="orderDetailsModal" tabindex="-1" aria-labelledby="orderDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header bg-primary text-white rounded-top-4">
                <h5 class="modal-title" id="orderDetailsModalLabel"><i class="bi bi-receipt me-2"></i>Detail Pesanan</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4" id="orderDetailsContent">
                <!-- Content loaded via AJAX -->
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function viewOrderDetails(orderId) {
    const modal = new bootstrap.Modal(document.getElementById('orderDetailsModal'));
    modal.show();
    
    // Show spinner
    document.getElementById('orderDetailsContent').innerHTML = `
        <div class="text-center py-5">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-2 text-muted">Memuat data...</p>
        </div>
    `;

    // Fetch details
    fetch('get_order_details.php?id=' + orderId)
    .then(res => res.json())
    .then(data => {
        if(data.status) {
            document.getElementById('orderDetailsContent').innerHTML = data.html;
        } else {
            document.getElementById('orderDetailsContent').innerHTML = `
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle me-2"></i>${data.message}
                </div>
            `;
        }
    })
    .catch(err => {
         document.getElementById('orderDetailsContent').innerHTML = `
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-triangle me-2"></i>Gagal mmuat data psanan.
            </div>
        `;
    });
}
</script>

<?php
include 'footer.php';
?>