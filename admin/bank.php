<?php
include 'header.php';

// Get all bank
$result = get_all_bank();
$bank_list = $result['data'] ?? [];

// Handle Delete
if (isset($_POST['delete_bank'])) {
    $id = $_POST['id'];
    $result = delete_bank($id);
    
    if ($result['status']) {
        echo "<script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: 'Bank berhasil dihapus',
                showConfirmButton: false,
                timer: 1500
            }).then(function() {
                window.location = 'bank.php';
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
<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h1 class="mt-4">Bank</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item">Home</li>
                <li class="breadcrumb-item">Data</li>
                <li class="breadcrumb-item">Pembayaran</li>
                <li class="breadcrumb-item active">Bank</li>
            </ol>
            <hr>
            <!-- Button trigger modal -->
            <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                Tambah Bank
            </button>
            <!-- Modal Tambah -->
            <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="staticBackdropLabel">Tambah Bank Baru</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="" method="POST" enctype="multipart/form-data">
                            <div class="modal-body">
                                <div class="row row-cols-1">
                                    <div class="col mb-3">
                                        <label for="logo" class="form-label">Logo :</label>
                                        <input type="file" class="form-control" id="logo" name="logo" accept="image/*" required>
                                    </div>
                                    <div class="col mb-3">
                                        <label for="nama_bank" class="form-label">Nama Bank :</label>
                                        <input type="text" class="form-control" id="nama_bank" name="nama_bank" placeholder="Masukkan Nama Bank" required>
                                    </div>
                                    <div class="col mb-3">
                                        <label for="no_rekening" class="form-label">No Rekening :</label>
                                        <input type="text" class="form-control" id="no_rekening" name="no_rekening" placeholder="Masukkan No Rekening" required>
                                    </div>
                                    <div class="col mb-3">
                                        <label for="atas_nama" class="form-label">Atas Nama :</label>
                                        <input type="text" class="form-control" id="atas_nama" name="atas_nama" placeholder="Masukkan Atas Nama" required>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" name="tambah_bank" class="btn btn-primary">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-table me-1"></i>
                    Bank
                </div>
                <div class="card-body">
                    <table id="datatablesSimple">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Logo</th>
                                <th>Nama Bank</th>
                                <th>No Rekening</th>
                                <th>Atas Nama</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $no = 1;
                            foreach ($bank_list as $b): 
                            ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td>
                                    <img src="uploads/<?= $b['logo'] ?>" alt="<?= $b['nama_bank'] ?>" style="width: 100%; height: 100px; object-fit: contain;">
                                </td>
                                <td><?= $b['nama_bank'] ?></td>
                                <td><?= $b['no_rekening'] ?></td>
                                <td><?= $b['atas_nama'] ?></td>
                                <td>
                                    <!-- Button trigger modal -->
                                    <button type="button" class="btn btn-warning mb-3" data-bs-toggle="modal" data-bs-target="#staticBackdropUbah<?= $b['id'] ?>">
                                        Ubah
                                    </button>
                                    <!-- Modal Ubah -->
                                    <div class="modal fade" id="staticBackdropUbah<?= $b['id'] ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Ubah Bank</h1>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form action="" method="POST" enctype="multipart/form-data">
                                                    <div class="modal-body">
                                                        <div class="row row-cols-1">
                                                            <div class="col mb-3">
                                                                <label for="logo" class="form-label">Logo :</label>
                                                                <input type="file" class="form-control" id="logo" name="logo" accept="image/*">
                                                                <small class="text-muted">Kosongkan jika tidak ingin mengubah logo</small>
                                                                <input type="hidden" name="logo_lama" value="<?= $b['logo'] ?>">
                                                            </div>
                                                            <div class="col mb-3">
                                                                <label for="nama_bank" class="form-label">Nama Bank :</label>
                                                                <input type="text" class="form-control" id="nama_bank" name="nama_bank" value="<?= $b['nama_bank'] ?>" required>
                                                            </div>
                                                            <div class="col mb-3">
                                                                <label for="no_rekening" class="form-label">No Rekening :</label>
                                                                <input type="text" class="form-control" id="no_rekening" name="no_rekening" value="<?= $b['no_rekening'] ?>" required>
                                                            </div>
                                                            <div class="col mb-3">
                                                                <label for="atas_nama" class="form-label">Atas Nama :</label>
                                                                <input type="text" class="form-control" id="atas_nama" name="atas_nama" value="<?= $b['atas_nama'] ?>" required>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" name="update_bank" class="btn btn-primary">Simpan</button>
                                                        <input type="hidden" name="id" value="<?= $b['id'] ?>">
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Button trigger modal -->
                                    <button type="button" class="btn btn-danger mb-3" data-bs-toggle="modal" data-bs-target="#staticBackdropHapus<?= $b['id'] ?>">
                                        Hapus
                                    </button>
                                    <!-- Modal Hapus -->
                                    <div class="modal fade" id="staticBackdropHapus<?= $b['id'] ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Hapus Bank</h1>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form action="" method="POST">
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            <div class="col mb-3 text-center">
                                                                <h3 class="fw-bold">Anda Yakin Akan Menghapus Bank "<?= $b['nama_bank'] ?>"?</h3>
                                                                <input type="hidden" name="id" value="<?= $b['id'] ?>">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" name="delete_bank" class="btn btn-danger">Hapus</button>
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
if (isset($_POST['tambah_bank'])) {
    $data = [
        'nama_bank' => $_POST['nama_bank'],
        'no_rekening' => $_POST['no_rekening'],
        'atas_nama' => $_POST['atas_nama']
    ];
    
    $file = null;
    if (isset($_FILES['logo']) && $_FILES['logo']['error'] === 0) {
        $file = $_FILES['logo'];
    }
    
    $result = create_bank($data, $file);
    
    if ($result['status']) {
        echo "<script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: 'Bank berhasil ditambahkan',
                showConfirmButton: false,
                timer: 1500
            }).then(function() {
                window.location = 'bank.php';
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
if (isset($_POST['update_bank'])) {
    $id = $_POST['id'];
    $data = [
        'nama_bank' => $_POST['nama_bank'],
        'no_rekening' => $_POST['no_rekening'],
        'atas_nama' => $_POST['atas_nama']
    ];
    
    $file = null;
    if (!empty($_FILES['logo']['name'])) {
        $file = $_FILES['logo'];
    }
    
    $result = update_bank($id, $data, $file);
    
    if ($result['status']) {
        echo "<script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: 'Bank berhasil diupdate',
                showConfirmButton: false,
                timer: 1500
            }).then(function() {
                window.location = 'bank.php';
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