<?php
session_start();
require_once 'config/function.php';

// Check if user is logged in
if (!isset($_SESSION['user'])) {
    echo json_encode([
        'status' => false,
        'message' => 'Unauthorized access',
        'html' => null
    ]);
    exit;
}

// Check if order ID is provided
if (!isset($_GET['id'])) {
    echo json_encode([
        'status' => false,
        'message' => 'Order ID is required',
        'html' => null
    ]);
    exit;
}

$order_id = (int)$_GET['id'];

// Get order details
$order = get_order_by_id($order_id);
if (!$order['status']) {
    echo json_encode([
        'status' => false,
        'message' => 'Order not found',
        'html' => null
    ]);
    exit;
}

// Check if order belongs to user
if ($order['data']['id_user'] != $_SESSION['user']['id']) {
    echo json_encode([
        'status' => false,
        'message' => 'Unauthorized access',
        'html' => null
    ]);
    exit;
}

// Get order items
$items = get_order_details($order_id);
if (!$items['status']) {
    echo json_encode([
        'status' => false,
        'message' => 'Failed to get order items',
        'html' => null
    ]);
    exit;
}

// Get payment info
$payment = get_payment_by_order_id($order_id);

// Helper for badges
function get_status_badge($status) {
    switch($status) {
        case 'pending': return '<span class="badge bg-warning text-dark rounded-pill"><i class="bi bi-clock me-1"></i>Pending</span>';
        case 'proses': return '<span class="badge bg-info text-dark rounded-pill"><i class="bi bi-gear-wide-connected me-1"></i>Diproses</span>';
        case 'dikirim': return '<span class="badge bg-primary rounded-pill"><i class="bi bi-truck me-1"></i>Dikirim</span>';
        case 'selesai': return '<span class="badge bg-success rounded-pill"><i class="bi bi-check-circle me-1"></i>Selesai</span>';
        case 'batal': return '<span class="badge bg-danger rounded-pill"><i class="bi bi-x-circle me-1"></i>Dibatalkan</span>';
        default: return '<span class="badge bg-secondary rounded-pill">' . ucfirst($status) . '</span>';
    }
}

// Build HTML
$html = '<div class="container-fluid p-0">';

// Order Info Section
$html .= '
<div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-light">
        <h6 class="mb-0 fw-bold">Informasi Pesanan</h6>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6 mb-3 mb-md-0">
                <table class="table table-borderless table-sm mb-0">
                    <tr>
                        <td class="text-muted w-40">Order ID</td>
                        <td class="fw-bold">#' . $order['data']['kode_invoice'] . '</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Tanggal</td>
                        <td>' . date('d M Y H:i', strtotime($order['data']['tanggal'])) . '</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Status</td>
                        <td>' . get_status_badge($order['data']['status']) . '</td>
                    </tr>';
                    
                    if (!empty($order['data']['resi_pengiriman'])) {
                        $html .= '
                        <tr>
                            <td class="text-muted">No. Resi</td>
                            <td class="fw-bold text-primary">' . $order['data']['resi_pengiriman'] . '</td>
                        </tr>';
                    }
                    
                    $html .= '
                    <tr>
                        <td class="text-muted">Total</td>
                        <td class="fw-bold fs-5 text-primary">Rp ' . number_format($order['data']['total_harga'], 0, ',', '.') . '</td>
                    </tr>
                </table>
            </div>
            <div class="col-md-6">
                <table class="table table-borderless table-sm mb-0">
                     <tr>
                        <td class="text-muted w-40">Penerima</td>
                        <td class="fw-bold">' . $order['data']['nama_user'] . '</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Telepon</td>
                        <td>' . $order['data']['no_telp'] . '</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Alamat</td>
                        <td>' . $order['data']['alamat'] . '</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>';

// Products Section
$html .= '
<div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-light">
        <h6 class="mb-0 fw-bold">Detail Produk</h6>
    </div>
    <div class="list-group list-group-flush">';

    $subtotal_all = 0;
    foreach ($items['data'] as $item) {
        $subtotal = $item['qty'] * $item['harga'];
        $subtotal_all += $subtotal;
        $html .= '
        <div class="list-group-item p-3">
            <div class="d-flex align-items-center">
                <img src="admin/uploads/' . $item['gambar'] . '" alt="' . $item['nama_produk'] . '" class="rounded border" style="width: 60px; height: 60px; object-fit: contain;">
                <div class="ms-3 flex-grow-1">
                    <h6 class="mb-1 text-truncate" style="max-width: 250px;">' . $item['nama_produk'] . '</h6>
                    <small class="text-muted">' . $item['qty'] . ' x Rp ' . number_format($item['harga'], 0, ',', '.') . '</small>
                </div>
                <div class="fw-bold text-end">
                    Rp ' . number_format($subtotal, 0, ',', '.') . '
                </div>
            </div>
        </div>';
    }

$html .= '
        <div class="list-group-item p-3 bg-light">
             <div class="d-flex justify-content-between align-items-center">
                <span class="fw-bold">Total Pembayaran</span>
                <span class="fs-5 fw-bold text-primary">Rp ' . number_format($order['data']['total_harga'], 0, ',', '.') . '</span>
            </div>
        </div>
    </div>
</div>';

// Payment Proof Section
if ($payment && isset($payment['data']['bukti_pembayaran'])) {
    $html .= '
    <div class="card shadow-sm border-0">
        <div class="card-header bg-light">
            <h6 class="mb-0 fw-bold">Bukti Pembayaran</h6>
        </div>
        <div class="card-body text-center">
            <img src="uploads/pembayaran/' . $payment['data']['bukti_pembayaran'] . '" 
                 alt="Bukti Pembayaran" 
                 class="img-fluid rounded border shadow-sm" 
                 style="max-height: 300px;">
        </div>
    </div>';
}

$html .= '</div>';

echo json_encode([
    'status' => true,
    'message' => 'Success',
    'html' => $html
]);