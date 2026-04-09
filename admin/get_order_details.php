<?php
include 'header.php';

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if order_id is set
if (!isset($_GET['order_id'])) {
    echo '<div class="alert alert-danger">ID Pesanan tidak valid</div>';
    exit;
}

$order_id = (int)$_GET['order_id'];

// Get order details
$order = get_order_by_id($order_id);

if (!$order['status']) {
    echo '<div class="alert alert-danger">Pesanan tidak ditemukan</div>';
    exit;
}

$order = $order['data'];
$details = get_order_details($order_id);

if (!$details['status']) {
    echo '<div class="alert alert-danger">Gagal mengambil detail pesanan</div>';
    exit;
}
?>

<style>
.product-image {
    width: 40px;
    height: 40px;
    object-fit: cover;
    border-radius: 4px;
    border: 1px solid #dee2e6;
}
.product-info {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}
.product-name {
    font-size: 0.9rem;
    line-height: 1.2;
    margin: 0;
}
.table-sm td, .table-sm th {
    padding: 0.5rem;
    vertical-align: middle;
}
.card {
    border: none;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    margin-bottom: 1rem;
}
.card-title {
    color: #495057;
    font-weight: 600;
    font-size: 0.95rem;
}
.badge {
    font-size: 0.75rem;
    padding: 0.35em 0.65em;
}
</style>

<div class="row">
    <!-- Order Information -->
    <div class="col-12 mb-3">
        <div class="card">
            <div class="card-body">
                <h6 class="card-title mb-3">
                    <i class="fas fa-info-circle me-2"></i>Informasi Pesanan
                </h6>
                <table class="table table-sm">
                    <tr>
                        <td width="40%">ID Pesanan</td>
                        <td>: #<?= $order['kode_invoice'] ?></td>
                    </tr>
                    <tr>
                        <td>Tanggal</td>
                        <td>: <?= date('d/m/Y H:i', strtotime($order['tanggal'])) ?></td>
                    </tr>
                    <tr>
                        <td>Status</td>
                        <td>: <span class="badge <?= get_status_badge_class($order['status']) ?>"><?= $order['status'] ?></span></td>
                    </tr>
                    <tr>
                        <td>Total</td>
                        <td>: Rp <?= number_format($order['total_harga'], 0, ',', '.') ?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <!-- Shipping Information -->
    <div class="col-12 mb-3">
        <div class="card">
            <div class="card-body">
                <h6 class="card-title mb-3">
                    <i class="fas fa-truck me-2"></i>Informasi Pengiriman
                </h6>
                <table class="table table-sm">
                    <tr>
                        <td width="40%">Nama</td>
                        <td>: <?= $order['nama_user'] ?></td>
                    </tr>
                    <tr>
                        <td>No. HP</td>
                        <td>: <?= $order['no_telp'] ?></td>
                    </tr>
                    <tr>
                        <td>Alamat</td>
                        <td>: <?= $order['alamat'] ?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <!-- Product List -->
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h6 class="card-title mb-3">
                    <i class="fas fa-shopping-cart me-2"></i>Daftar Produk
                </h6>
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th style="width: 50%">Produk</th>
                                <th class="text-center" style="width: 15%">Qty</th>
                                <th class="text-end" style="width: 17.5%">Harga</th>
                                <th class="text-end" style="width: 17.5%">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($details['data'] as $item): ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="uploads/<?= $item['gambar'] ?>" 
                                             alt="<?= $item['nama_produk'] ?>" 
                                             class="me-2"
                                             style="width: 40px; height: 40px; object-fit: cover; border-radius: 4px;">
                                        <span><?= $item['nama_produk'] ?></span>
                                    </div>
                                </td>
                                <td class="text-center"><?= $item['qty'] ?></td>
                                <td class="text-end">Rp <?= number_format($item['harga'], 0, ',', '.') ?></td>
                                <td class="text-end">Rp <?= number_format($item['qty'] * $item['harga'], 0, ',', '.') ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="3" class="text-end">Total:</th>
                                <th class="text-end">Rp <?= number_format($order['total_harga'], 0, ',', '.') ?></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div> 