<?php
include 'header.php';

// Get all orders
$result_pesanan = get_all_orders();
$pesanan_list = $result_pesanan['data'] ?? [];

// Handle Delete
if (isset($_POST['delete_pesanan'])) {
    $id = $_POST['id'];
    $result = delete_pesanan($id);
    
    if ($result['status']) {
        echo "<script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: 'Pesanan berhasil dihapus',
                showConfirmButton: false,
                timer: 1500
            }).then(function() {
                window.location = 'pesanan.php';
            });
        </script>";
    } else {
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: 'Pesanan gagal dihapus',
                showConfirmButton: false,
                timer: 1500
            });
        </script>";
    }
}
?>
<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h1 class="mt-4">Pesanan</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item">Home</li>
                <li class="breadcrumb-item">Data</li>
                <li class="breadcrumb-item active">Pesanan</li>
            </ol>
            <hr>
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-table me-1"></i>
                    Pesanan
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
                                <td><?= $p['kode_invoice'] ?></td>
                                <td><?= $p['nama_user'] ?></td>
                                <td>Rp <?= number_format($p['total_harga'], 0, ',', '.') ?></td>
                                <td>
                                    <span class="badge <?= get_status_badge_class($p['status']) ?>">
                                        <?= $p['status'] ?>
                                    </span>
                                </td>
                                <td><?= date('d/m/Y H:i', strtotime($p['tanggal'])) ?></td>
                                <td>
                                <a href="invoice.php?id=<?= $p['id'] ?>" target="_blank" class="btn btn-primary mb-3">
                                        Invoice
                                    </a>
                                    <!-- Button trigger modal -->
                                    <button type="button" class="btn btn-info mb-3" data-bs-toggle="modal" data-bs-target="#modalDetail<?= $p['id'] ?>">
                                        Detail
                                    </button>
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
                                                            </tbody>
                                                        </table>
                                                    </div>

                                                    <?php if ($payment_info['status']): ?>
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
                                                        <?php if ($payment_info['data']['bukti_pembayaran']): ?>
                                                        <div class="col-md-6">
                                                            <p><strong>Bukti Pembayaran:</strong></p>
                                                            <img src="../uploads/pembayaran/<?= $payment_info['data']['bukti_pembayaran'] ?>" alt="Bukti Pembayaran" style="max-width: 200px;">
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

                                    <!-- Button trigger modal -->
                                    <button type="button" class="btn btn-warning mb-3" data-bs-toggle="modal" data-bs-target="#staticBackdropUbah<?= $p['id'] ?>">
                                        Update Status
                                    </button>
                                    <!-- Modal Ubah -->
                                    <div class="modal fade" id="staticBackdropUbah<?= $p['id'] ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Update Status Pesanan</h1>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form action="" method="POST">
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label for="status<?= $p['id'] ?>" class="form-label">Status :</label>
                                                            <select name="status" id="status<?= $p['id'] ?>" class="form-select" required onchange="toggleResi(<?= $p['id'] ?>)">
                                                                <option value="Pending" <?= ($p['status'] == 'Pending') ? 'selected' : '' ?>>Pending</option>
                                                                <option value="Proses Pengiriman" <?= ($p['status'] == 'Proses Pengiriman') ? 'selected' : '' ?>>Proses Pengiriman</option>
                                                                <option value="Sukses" <?= ($p['status'] == 'Sukses') ? 'selected' : '' ?>>Sukses</option>
                                                                <option value="Ditolak" <?= ($p['status'] == 'Ditolak') ? 'selected' : '' ?>>Ditolak</option>
                                                            </select>
                                                        </div>
                                                        <div class="mb-3" id="resiContainer<?= $p['id'] ?>" style="display: <?= ($p['status'] == 'Proses Pengiriman') ? 'block' : 'none' ?>;">
                                                            <label for="resi<?= $p['id'] ?>" class="form-label">No. Resi Pengiriman :</label>
                                                            <input type="text" name="resi" id="resi<?= $p['id'] ?>" class="form-control" value="<?= isset($p['resi_pengiriman']) ? $p['resi_pengiriman'] : '' ?>" placeholder="Masukkan nomor resi">
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" name="update_status" class="btn btn-primary">Simpan</button>
                                                        <input type="hidden" name="id" value="<?= $p['id'] ?>">
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Button trigger modal -->
                                    <button type="button" class="btn btn-danger mb-3" data-bs-toggle="modal" data-bs-target="#staticBackdropHapus<?= $p['id'] ?>">
                                        Hapus
                                    </button>
                                    <!-- Modal Hapus -->
                                    <div class="modal fade" id="staticBackdropHapus<?= $p['id'] ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Hapus Pesanan</h1>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form action="" method="POST">
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            <div class="col mb-3 text-center">
                                                                <h3 class="fw-bold">Anda Yakin Akan Menghapus Pesanan "<?= $p['kode_invoice'] ?>"?</h3>
                                                                <input type="hidden" name="id" value="<?= $p['id'] ?>">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" name="delete_pesanan" class="btn btn-danger">Hapus</button>
                                                    </div>
                                                </form>
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
    <?php
    include 'footer.php';
    ?>
</div>

<script>
function toggleResi(id) {
    const status = document.getElementById('status' + id).value;
    const resiContainer = document.getElementById('resiContainer' + id);
    const resiInput = document.getElementById('resi' + id);
    
    if (status === 'Proses Pengiriman') {
        resiContainer.style.display = 'block';
        resiInput.required = true;
    } else {
        resiContainer.style.display = 'none';
        resiInput.required = false;
        resiInput.value = '';
    }
}
</script>

<?php
// Handle Update Status
if (isset($_POST['update_status'])) {
    $id = $_POST['id'];
    $status = $_POST['status'];
    $resi = isset($_POST['resi']) ? $_POST['resi'] : null;
    
    $result = update_order_status($id, $status, $resi);
    
    if ($result['status']) {
        echo "<script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: 'Status pesanan berhasil diupdate',
                showConfirmButton: false,
                timer: 1500
            }).then(function() {
                window.location = 'pesanan.php';
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