<?php
include 'header.php';

// Handle Delete
if (isset($_POST['delete_kategori'])) {
    $id = $_POST['id'];
    $result = delete_kategori($id);
    
    if ($result['status']) {
        echo "<script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: 'Kategori berhasil dihapus',
                showConfirmButton: false,
                timer: 1500
            }).then(function() {
                window.location = 'kategori.php';
            });
        </script>";
    } else {
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: '" . addslashes($result['message']) . "',
                showConfirmButton: false,
                timer: 3000
            });
        </script>";
    }
}

// Get all kategori
$result = get_all_kategori();
$kategori = $result['data'] ?? [];
?>
<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h1 class="mt-4">Kategori Produk</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item">Home</li>
                <li class="breadcrumb-item">Data</li>
                <li class="breadcrumb-item active">Kategori Produk</li>
            </ol>
            <hr>
            <!-- Button trigger modal -->
            <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                Tambah Kategori
            </button>
            <!-- Modal Tambah -->
            <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="staticBackdropLabel">Tambah Kategori Baru</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="" method="POST">
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col mb-3">
                                        <label for="nama" class="form-label">Kategori :</label>
                                        <input type="text" class="form-control" id="nama" name="nama" placeholder="Masukkan Kategori" required>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" name="tambah_kategori" class="btn btn-primary">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-table me-1"></i>
                    Kategori Produk
                </div>
                <div class="card-body">
                    <table id="datatablesSimple">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kategori</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $no = 1;
                            foreach ($kategori as $k): 
                            ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= $k['nama'] ?></td>
                                <td>
                                    <!-- Button trigger modal -->
                                    <button type="button" class="btn btn-warning mb-3" data-bs-toggle="modal" data-bs-target="#staticBackdropUbah<?= $k['id'] ?>">
                                        Ubah
                                    </button>
                                    <!-- Modal Ubah -->
                                    <div class="modal fade" id="staticBackdropUbah<?= $k['id'] ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Ubah Kategori</h1>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form action="" method="POST">
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            <div class="col mb-3">
                                                                <label for="nama" class="form-label">Kategori :</label>
                                                                <input type="text" class="form-control" id="nama" name="nama" value="<?= $k['nama'] ?>" required>
                                                                <input type="hidden" name="id" value="<?= $k['id'] ?>">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" name="update_kategori" class="btn btn-primary">Simpan</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Button trigger modal -->
                                    <button type="button" class="btn btn-danger mb-3" data-bs-toggle="modal" data-bs-target="#staticBackdropHapus<?= $k['id'] ?>">
                                        Hapus
                                    </button>
                                    <!-- Modal Hapus -->
                                    <div class="modal fade" id="staticBackdropHapus<?= $k['id'] ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Hapus Kategori</h1>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form action="" method="POST">
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            <div class="col mb-3 text-center">
                                                                <h3 class="fw-bold">Anda Yakin Akan Menghapus Kategori "<?= $k['nama'] ?>"?</h3>
                                                                <input type="hidden" name="id" value="<?= $k['id'] ?>">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" name="delete_kategori" class="btn btn-danger">Hapus</button>
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
if (isset($_POST['tambah_kategori'])) {
    $nama = $_POST['nama'];
    $result = create_kategori($nama);
    
    if ($result['status']) {
        echo "<script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: 'Kategori berhasil ditambahkan',
                showConfirmButton: false,
                timer: 1500
            }).then(function() {
                window.location = 'kategori.php';
            });
        </script>";
    } else {
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: 'Kategori gagal ditambahkan',
                showConfirmButton: false,
                timer: 1500
            });
        </script>";
    }
}

// Handle Update
if (isset($_POST['update_kategori'])) {
    $id = $_POST['id'];
    $nama = $_POST['nama'];
    $result = update_kategori($id, $nama);
    
    if ($result['status']) {
        echo "<script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: 'Kategori berhasil diupdate',
                showConfirmButton: false,
                timer: 1500
            }).then(function() {
                window.location = 'kategori.php';
            });
        </script>";
    } else {
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: 'Kategori gagal diupdate',
                showConfirmButton: false,
                timer: 1500
            });
        </script>";
    }
}
?>