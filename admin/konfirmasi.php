<?php
include 'header.php';
require_once '../config/function.php';

// Get pending payments
$query = "SELECT p.*, u.nama as nama_user, u.no_telp, u.alamat, pm.status as status_pembayaran, pm.bukti_pembayaran, pm.metode_pembayaran, pm.nominal_pembayaran, pm.tanggal as tanggal_pembayaran
          FROM pembayaran pm
          JOIN pesanan p ON pm.id_pesanan = p.id
          JOIN user u ON p.id_user = u.id
          ORDER BY pm.tanggal DESC";

$result = query_all($query);
$orders = $result;

// Handle confirmation
if (isset($_POST['confirm_payment'])) {
    $order_id = $_POST['order_id'];
    $action = $_POST['action']; // 'confirm' or 'reject'
    
    if ($action == 'confirm') {
        $resi = isset($_POST['resi']) ? $_POST['resi'] : null;
        $result = update_payment_status($order_id, 'valid', $resi);
    } else {
        $result = update_payment_status($order_id, 'rejected');
    }
    
    if ($result['status']) {
        echo "<script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: 'Status pembayaran berhasil diperbarui',
                showConfirmButton: false,
                timer: 1500
            }).then(function() {
                window.location = 'konfirmasi.php';
            });
        </script>";
    } else {
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: '" . addslashes($result['message']) . "',
                showConfirmButton: false,
                timer: 1500
            });
        </script>";
    }
}
?>
<!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h1 class="mt-4">Konfirmasi</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item">Home</li>
                <li class="breadcrumb-item">Data</li>
                <li class="breadcrumb-item">Pembayaran</li>
                <li class="breadcrumb-item active">Konfirmasi</li>
            </ol>
            <hr>
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-table me-1"></i>
                    Konfirmasi Pembayaran
                </div>
                <div class="card-body">
                    <?php if (empty($orders['data'])): ?>
                    <div class="text-center py-4">
                        <i class="fas fa-check-circle text-success" style="font-size: 3rem;"></i>
                        <h4 class="mt-3">Tidak Ada Pembayaran yang Perlu Dikonfirmasi</h4>
                        <p class="text-muted">Semua pembayaran telah diproses</p>
                    </div>
                    <?php else: ?>
                    <table id="datatablesSimple">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>ID Pesanan</th>
                                <th>Nama</th>
                                <th>Alamat</th>
                                <th>No. HP</th>
                                <th>Tanggal</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $no = 1;
                            foreach ($orders['data'] as $order): 
                            ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td>#<?= $order['kode_invoice'] ?></td>
                                <td><?= $order['nama_user'] ?></td>
                                <td><?= $order['alamat'] ?></td>
                                <td><?= $order['no_telp'] ?></td>
                                <td><?= date('d/m/Y H:i', strtotime($order['tanggal_pembayaran'])) ?></td>
                                <td>Rp <?= number_format($order['nominal_pembayaran'], 0, ',', '.') ?></td>
                                <td>
                                    <?php
                                    $status_class = '';
                                    $status_text = '';
                                    switch($order['status_pembayaran']) {
                                        case 'pending':
                                            $status_class = 'bg-warning';
                                            $status_text = 'Pending';
                                            break;
                                        case 'valid':
                                            $status_class = 'bg-success';
                                            $status_text = 'Valid';
                                            break;
                                        case 'rejected':
                                            $status_class = 'bg-danger';
                                            $status_text = 'Ditolak';
                                            break;
                                        default:
                                            $status_class = 'bg-secondary';
                                            $status_text = 'Unknown';
                                    }
                                    ?>
                                    <span class="badge <?= $status_class ?>"><?= $status_text ?></span>
                                </td>
                                <td>
                                    <?php if ($order['bukti_pembayaran']): ?>
                                    <button type="button" class="btn btn-warning btn-sm mb-2" data-bs-toggle="modal" data-bs-target="#paymentModal<?= $order['id'] ?>">
                                        <i class="fas fa-eye me-1"></i> Cek Pembayaran
                                    </button>
                                    <?php else: ?>
                                    <button type="button" class="btn btn-secondary btn-sm mb-2" disabled>
                                        <i class="fas fa-clock me-1"></i> Menunggu Bukti
                                    </button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            
                            <!-- Payment Modal -->
                            <div class="modal fade" id="paymentModal<?= $order['id'] ?>" tabindex="-1" aria-labelledby="paymentModalLabel<?= $order['id'] ?>" aria-hidden="true">
                                <div class="modal-dialog modal-md modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="paymentModalLabel<?= $order['id'] ?>">
                                                Cek Pembayaran - #<?= $order['kode_invoice'] ?>
                                            </h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-12 mb-3">
                                                    <label class="form-label">Bukti Pembayaran:</label>
                                                    <img src="../uploads/pembayaran/<?= $order['bukti_pembayaran'] ?>" 
                                                         alt="Bukti Pembayaran" 
                                                         class="img-fluid rounded">
                                                </div>
                                                <div class="col-12">
                                                    <div class="alert alert-info">
                                                        <h6 class="alert-heading">Informasi Pembayaran:</h6>
                                                        <p class="mb-1">Metode: <?= ucfirst($order['metode_pembayaran']) ?></p>
                                                        <p class="mb-1">Nominal: Rp <?= number_format($order['nominal_pembayaran'], 0, ',', '.') ?></p>
                                                        <p class="mb-0">Tanggal: <?= date('d/m/Y H:i', strtotime($order['tanggal_pembayaran'])) ?></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <form method="POST" action="" class="d-inline">
                                                <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                                                <input type="hidden" name="action" value="confirm">
                                                
                                                <div class="mb-3 text-start">
                                                    <label for="resi<?= $order['id'] ?>" class="form-label">No. Resi Pengiriman <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" id="resi<?= $order['id'] ?>" name="resi" required placeholder="Masukkan nomor resi">
                                                </div>

                                                <button type="submit" name="confirm_payment" class="btn btn-success">
                                                    <i class="fas fa-check me-1"></i> Konfirmasi & Kirim
                                                </button>
                                            </form>
                                            <form method="POST" action="" class="d-inline">
                                                <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                                                <input type="hidden" name="action" value="reject">
                                                <button type="submit" name="confirm_payment" class="btn btn-danger">
                                                    <i class="fas fa-times me-1"></i> Tolak
                                                </button>
                                            </form>
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                <i class="fas fa-times me-1"></i> Tutup
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>
    <?php
    include 'footer.php';
    ?>
</div>

<script>
$(document).ready(function() {
    $('#datatablesSimple').DataTable({
        language: {
            search: "Cari:",
            lengthMenu: "Tampilkan _MENU_ data per halaman",
            zeroRecords: "Tidak ada data yang ditemukan",
            info: "Menampilkan halaman _PAGE_ dari _PAGES_",
            infoEmpty: "Tidak ada data yang tersedia",
            infoFiltered: "(difilter dari _MAX_ total data)",
            paginate: {
                first: "Pertama",
                last: "Terakhir",
                next: "Selanjutnya",
                previous: "Sebelumnya"
            }
        },
        order: [[5, 'desc']], // Sort by date column by default
        pageLength: 10
    });
});
</script>