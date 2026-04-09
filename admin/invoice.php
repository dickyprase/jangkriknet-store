<?php
require_once '../config/function.php';

// Check if ID is provided
if (!isset($_GET['id'])) {
    die('ID Pesanan tidak ditemukan');
}

$id = (int)$_GET['id'];

// Get order details
$order = get_order_by_id($id);
if (!$order['status']) {
    die('Pesanan tidak ditemukan');
}

// Get order items
$items = get_order_details($id);
if (!$items['status']) {
    die('Detail pesanan tidak ditemukan');
}

// Get payment info
$payment = get_payment_by_order_id($id);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #<?php echo $order['data']['kode_invoice']; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .invoice-container {
            max-width: 800px;
            margin: 50px auto;
            background: white;
            padding: 30px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .invoice-header {
            border-bottom: 2px solid #eee;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }
        .invoice-title {
            color: #333;
            font-size: 24px;
            font-weight: bold;
        }
        .invoice-details {
            margin: 20px 0;
        }
        .invoice-table {
            width: 100%;
            margin: 20px 0;
        }
        .invoice-table th {
            background-color: #f8f9fa;
        }
        .text-right {
            text-align: right;
        }
        @media print {
            body {
                background-color: white;
            }
            .invoice-container {
                box-shadow: none;
                margin: 0;
                padding: 15px;
            }
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="invoice-container">
            <div class="invoice-header">
                <div class="row">
                    <div class="col-6">
                        <h1 class="invoice-title">INVOICE</h1>
                        <p class="mb-1">No: <?php echo $order['data']['kode_invoice']; ?></p>
                        <p class="mb-1">Tanggal: <?php echo date('d/m/Y', strtotime($order['data']['tanggal'])); ?></p>
                    </div>
                    <div class="col-6 text-end">
                        <h2><?php echo $app_name; ?></h2>
                        <p class="mb-1">Jl. Contoh No. 123</p>
                        <p class="mb-1">Jakarta, Indonesia</p>
                        <p class="mb-1">Telp: (021) 123-4567</p>
                    </div>
                </div>
            </div>

            <div class="invoice-details">
                <div class="row">
                    <div class="col-6">
                        <h5>Informasi Pelanggan:</h5>
                        <p class="mb-1">Nama: <?php echo $order['data']['nama_user']; ?></p>
                        <p class="mb-1">Alamat: <?php echo $order['data']['alamat']; ?></p>
                        <p class="mb-1">No. Telp: <?php echo $order['data']['no_telp']; ?></p>
                    </div>
                    <div class="col-6 text-end">
                        <h5>Status Pesanan:</h5>
                        <p class="mb-1">Status: <?php echo $order['data']['status']; ?></p>
                        <p class="mb-1">Metode Pembayaran: <?php echo $payment['status'] ? $payment['data']['metode_pembayaran'] : '-'; ?></p>
                    </div>
                </div>
            </div>

            <table class="table invoice-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Produk</th>
                        <th class="text-end">Harga</th>
                        <th class="text-end">Jumlah</th>
                        <th class="text-end">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    foreach ($items['data'] as $item): 
                    ?>
                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td><?php echo $item['nama_produk']; ?></td>
                        <td class="text-end">Rp. <?php echo number_format($item['harga'], 0, ',', '.'); ?></td>
                        <td class="text-end"><?php echo $item['qty']; ?></td>
                        <td class="text-end">Rp. <?php echo number_format($item['harga'] * $item['qty'], 0, ',', '.'); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="4" class="text-end"><strong>Total:</strong></td>
                        <td class="text-end"><strong>Rp. <?php echo number_format($order['data']['total_harga'], 0, ',', '.'); ?></strong></td>
                    </tr>
                </tfoot>
            </table>

            <div class="row mt-4">
                <div class="col-12">
                    <p class="mb-1"><strong>Catatan:</strong></p>
                    <p class="mb-1">1. Invoice ini adalah bukti pembayaran yang sah</p>
                    <p class="mb-1">2. Simpan invoice ini untuk keperluan pengembalian barang</p>
                </div>
            </div>

            <div class="row mt-4 no-print">
                <div class="col-12 text-center">
                    <button onclick="window.print()" class="btn btn-primary">Cetak Invoice</button>
                    <a href="index.php" class="btn btn-secondary">Kembali</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html> 