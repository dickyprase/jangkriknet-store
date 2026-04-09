<?php
include 'header.php';

// Get all kategori for dropdown
$result_kategori = get_all_kategori();
$kategori_list = $result_kategori['data'] ?? [];

// Get all produk
$result_produk = get_all_produk();
$produk_list = $result_produk['data'] ?? [];

// Handle Delete
if (isset($_POST['delete_produk'])) {
    $id = $_POST['id'];
    $result = delete_produk($id);
    
    if ($result['status']) {
        echo "<script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: 'Produk berhasil dihapus',
                showConfirmButton: false,
                timer: 1500
            }).then(function() {
                window.location = 'produk.php';
            });
        </script>";
    } else {
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: 'Produk gagal dihapus',
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
            <h1 class="mt-4">Produk</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item">Home</li>
                <li class="breadcrumb-item">Data</li>
                <li class="breadcrumb-item active">Produk</li>
            </ol>
            <hr>
            <!-- Button trigger modal -->
            <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                Tambah Produk
            </button>
            <!-- Modal Tambah -->
            <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="staticBackdropLabel">Tambah Produk Baru</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="" method="POST" enctype="multipart/form-data">
                            <div class="modal-body">
                                <div class="row row-cols-1">
                                    <div class="col mb-3">
                                        <label for="gambar" class="form-label">Gambar :</label>
                                        <input type="file" class="form-control" id="gambar" name="gambar" accept="image/*" required>
                                    </div>
                                    <div class="col mb-3">
                                        <label for="nama" class="form-label">Produk :</label>
                                        <input type="text" class="form-control" id="nama" name="nama" placeholder="Masukkan Nama Produk" required>
                                    </div>
                                    <div class="col mb-3">
                                        <label for="id_kategori" class="form-label">Kategori :</label>
                                        <select name="id_kategori" id="id_kategori" class="form-select" required>
                                            <option value="">-- Pilih Kategori --</option>
                                            <?php foreach ($kategori_list as $k): ?>
                                            <option value="<?= $k['id'] ?>"><?= $k['nama'] ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="col mb-3">
                                        <label for="deskripsi" class="form-label">Deskripsi :</label>
                                        <textarea name="deskripsi" class="form-control" id="deskripsi" required></textarea>
                                    </div>
                                    <div class="col mb-3">
                                        <label for="harga" class="form-label">Harga :</label>
                                        <input type="number" class="form-control" id="harga" name="harga" placeholder="Masukkan Harga" required>
                                    </div>
                                    <div class="col mb-3">
                                        <label for="stock" class="form-label">Stok :</label>
                                        <input type="number" class="form-control" id="stock" name="stock" placeholder="Masukkan Stok" required>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" name="tambah_produk" class="btn btn-primary">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-table me-1"></i>
                    Produk
                </div>
                <div class="card-body">
                    <table id="datatablesSimple">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Foto</th>
                                <th>Produk</th>
                                <th>Stock</th>
                                <th>Kategori</th>
                                <th>Deskripsi</th>
                                <th>Harga</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $no = 1;
                            foreach ($produk_list as $p): 
                            ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td>
                                    <img src="uploads/<?= $p['gambar'] ?>" alt="<?= $p['nama'] ?>" style="width: 100%; height: 100px; object-fit: contain;">
                                </td>
                                <td><?= $p['nama'] ?></td>
                                <td><?= $p['stock'] ?></td>
                                <td><?= $p['nama_kategori'] ?></td>
                                <td><div class="description-cell" title="<?= htmlspecialchars($p['deskripsi']) ?>"><?= $p['deskripsi'] ?></div></td>
                                <td>Rp <?= number_format($p['harga'], 0, ',', '.') ?></td>
                                <td>
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
                                                        <i class="fas fa-info-circle me-2"></i>Detail Produk
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body p-4">
                                                    <div class="row">
                                                        <div class="col-md-5">
                                                            <img src="uploads/<?= $p['gambar'] ?>" alt="<?= $p['nama'] ?>" class="modal-detail-img">
                                                        </div>
                                                        <div class="col-md-7 modal-detail-content">
                                                            <h4 class="modal-detail-title"><?= $p['nama'] ?></h4>
                                                            
                                                            <div class="modal-detail-info">
                                                                <span class="modal-detail-label">Kategori:</span>
                                                                <span class="modal-detail-value"><?= $p['nama_kategori'] ?></span>
                                                            </div>
                                                            
                                                            <div class="modal-detail-info">
                                                                <span class="modal-detail-label">Harga:</span>
                                                                <span class="modal-detail-value">Rp <?= number_format($p['harga'], 0, ',', '.') ?></span>
                                                            </div>
                                                            
                                                            <div class="modal-detail-info">
                                                                <span class="modal-detail-label">Stok:</span>
                                                                <span class="modal-detail-value">
                                                                    <?php if ($p['stock'] > 10): ?>
                                                                        <span class="badge bg-success"><?= $p['stock'] ?> Tersedia</span>
                                                                    <?php elseif ($p['stock'] > 0): ?>
                                                                        <span class="badge bg-warning"><?= $p['stock'] ?> Stok Terbatas</span>
                                                                    <?php else: ?>
                                                                        <span class="badge bg-danger">Habis</span>
                                                                    <?php endif; ?>
                                                                </span>
                                                            </div>

                                                            <div class="modal-detail-description">
                                                                <h6 class="mb-3"><i class="fas fa-align-left me-2"></i>Deskripsi Produk</h6>
                                                                <p><?= nl2br($p['deskripsi']) ?></p>
                                                            </div>
                                                        </div>
                                                    </div>
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
                                        Ubah
                                    </button>
                                    <!-- Modal Ubah -->
                                    <div class="modal fade" id="staticBackdropUbah<?= $p['id'] ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Ubah Produk</h1>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form action="" method="POST" enctype="multipart/form-data">
                                                    <div class="modal-body">
                                                        <div class="row row-cols-1">
                                                            <div class="col mb-3">
                                                                <label for="gambar" class="form-label">Gambar :</label>
                                                                <input type="file" class="form-control" id="gambar" name="gambar" accept="image/*">
                                                                <small class="text-muted">Kosongkan jika tidak ingin mengubah gambar</small>
                                                                <input type="hidden" name="gambar_lama" value="<?= $p['gambar'] ?>">
                                                            </div>
                                                            <div class="col mb-3">
                                                                <label for="nama" class="form-label">Produk :</label>
                                                                <input type="text" class="form-control" id="nama" name="nama" value="<?= $p['nama'] ?>" required>
                                                            </div>
                                                            <div class="col mb-3">
                                                                <label for="id_kategori" class="form-label">Kategori :</label>
                                                                <select name="id_kategori" id="id_kategori" class="form-select" required>
                                                                    <option value="">-- Pilih Kategori --</option>
                                                                    <?php foreach ($kategori_list as $k): ?>
                                                                    <option value="<?= $k['id'] ?>" <?= ($k['id'] == $p['id_kategori']) ? 'selected' : '' ?>><?= $k['nama'] ?></option>
                                                                    <?php endforeach; ?>
                                                                </select>
                                                            </div>
                                                            <div class="col mb-3">
                                                                <label for="deskripsi" class="form-label">Deskripsi :</label>
                                                                <textarea name="deskripsi" class="form-control" id="deskripsi" required><?= $p['deskripsi'] ?></textarea>
                                                            </div>
                                                            <div class="col mb-3">
                                                                <label for="harga" class="form-label">Harga :</label>
                                                                <input type="number" class="form-control" id="harga" name="harga" value="<?= $p['harga'] ?>" required>
                                                            </div>
                                                            <div class="col mb-3">
                                                                <label for="stock" class="form-label">Stock :</label>
                                                                <input type="number" class="form-control" id="stock" name="stock" value="<?= $p['stock'] ?>" required>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" name="update_produk" class="btn btn-primary">Simpan</button>
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
                                                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Hapus Produk</h1>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form action="" method="POST">
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            <div class="col mb-3 text-center">
                                                                <h3 class="fw-bold">Anda Yakin Akan Menghapus Produk "<?= $p['nama'] ?>"?</h3>
                                                                <input type="hidden" name="id" value="<?= $p['id'] ?>">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" name="delete_produk" class="btn btn-danger">Hapus</button>
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

<?php
// Handle Tambah
if (isset($_POST['tambah_produk'])) {
    $data = [
        'nama' => $_POST['nama'],
        'id_kategori' => $_POST['id_kategori'],
        'deskripsi' => $_POST['deskripsi'],
        'harga' => $_POST['harga'],
        'stock' => $_POST['stock']
    ];
    
    $file = null;
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === 0) {
        $file = $_FILES['gambar'];
    }
    
    $result = create_produk($data, $file);
    
    if ($result['status']) {
        echo "<script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: 'Produk berhasil ditambahkan',
                showConfirmButton: false,
                timer: 1500
            }).then(function() {
                window.location = 'produk.php';
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

// Handle Update
if (isset($_POST['update_produk'])) {
    $id = $_POST['id'];
    $data = [
        'nama' => $_POST['nama'],
        'id_kategori' => $_POST['id_kategori'],
        'deskripsi' => $_POST['deskripsi'],
        'harga' => $_POST['harga'],
        'stock' => $_POST['stock']
    ];
    
    $file = null;
    if (!empty($_FILES['gambar']['name'])) {
        $file = $_FILES['gambar'];
    }
    
    $result = update_produk($id, $data, $file);
    
    if ($result['status']) {
        echo "<script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: 'Produk berhasil diupdate',
                showConfirmButton: false,
                timer: 1500
            }).then(function() {
                window.location = 'produk.php';
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
<style>
    .description-cell {
        max-width: 200px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        display: block;
    }
    .modal-detail-img {
        width: 100%;
        height: 250px;
        object-fit: cover;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .modal-detail-content {
        padding: 15px;
    }
    .modal-detail-title {
        color: #2c3e50;
        font-size: 1.5rem;
        font-weight: 600;
        margin-bottom: 1rem;
        border-bottom: 2px solid #3498db;
        padding-bottom: 0.5rem;
    }
    .modal-detail-info {
        margin-bottom: 0.5rem;
    }
    .modal-detail-label {
        color: #7f8c8d;
        font-weight: 500;
        margin-right: 0.5rem;
    }
    .modal-detail-value {
        color: #2c3e50;
        font-weight: 600;
    }
    .modal-detail-description {
        background: #f8f9fa;
        padding: 15px;
        border-radius: 8px;
        margin-top: 1rem;
    }
    .modal-detail-description p {
        margin-bottom: 0;
        line-height: 1.6;
        color: #34495e;
    }
</style>