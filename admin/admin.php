<?php
include 'header.php';

// Get all users with role 'admin'
$result = get_all_users();
$admin_list = array_filter($result['data'] ?? [], function($user) {
    return $user['role'] === 'admin';
});

// Handle Delete
if (isset($_POST['delete_user'])) {
    $id = $_POST['id'];
    $result = delete_user($id);
    
    if ($result['status']) {
        echo "<script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: 'Admin berhasil dihapus',
                showConfirmButton: false,
                timer: 1500
            }).then(function() {
                window.location = 'admin.php';
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
            <h1 class="mt-4">Akun Admin</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item">Home</li>
                <li class="breadcrumb-item">Data</li>
                <li class="breadcrumb-item">Akun</li>
                <li class="breadcrumb-item active">Admin</li>
            </ol>
            <hr>
            <!-- Button trigger modal -->
            <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                Tambah Admin
            </button>
            <!-- Modal -->
            <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="staticBackdropLabel">Tambah Admin Baru</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="" method="POST">
                            <div class="modal-body">
                                <div class="row row-cols-1">
                                    <div class="mb-3">
                                        <label for="username">Username :</label>
                                        <input class="form-control" id="username" name="username" type="text" required />
                                    </div>
                                    <div class="mb-3">
                                        <label for="password">Password :</label>
                                        <input class="form-control" id="password" name="password" type="password" required />
                                    </div>
                                    <hr>
                                    <div class="mb-3">
                                        <label for="nama">Nama :</label>
                                        <input class="form-control" id="nama" name="nama" type="text" required />
                                    </div>
                                    <div class="mb-3">
                                        <label for="email">Email :</label>
                                        <input class="form-control" id="email" name="email" type="email" required />
                                    </div>
                                    <div class="mb-3">
                                        <label for="no_telp">No. Telp :</label>
                                        <input class="form-control" id="no_telp" name="no_telp" type="text" required />
                                    </div>
                                    <div class="mb-3">
                                        <label for="alamat">Alamat :</label>
                                        <input class="form-control" id="alamat" name="alamat" type="text" required />
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" name="tambah_user" class="btn btn-primary">Tambah</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-table me-1"></i>
                    Akun Admin
                </div>
                <div class="card-body">
                    <table id="datatablesSimple">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>No. Telp</th>
                                <th>Alamat</th>
                                <th>Username</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $no = 1;
                            foreach ($admin_list as $a): 
                            ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= $a['nama'] ?></td>
                                <td><?= $a['email'] ?></td>
                                <td><?= $a['no_telp'] ?></td>
                                <td><?= $a['alamat'] ?></td>
                                <td><?= $a['username'] ?></td>
                                <td>
                                    <!-- Button trigger modal -->
                                    <button type="button" class="btn btn-warning mb-3" data-bs-toggle="modal" data-bs-target="#staticBackdropUbah<?= $a['id'] ?>">
                                        Ubah
                                    </button>
                                    <!-- Modal -->
                                    <div class="modal fade" id="staticBackdropUbah<?= $a['id'] ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Ubah Akun Admin</h1>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form action="" method="POST">
                                                    <div class="modal-body">
                                                        <div class="row row-cols-1">
                                                            <div class="mb-3">
                                                                <label for="username">Username :</label>
                                                                <input class="form-control" id="username" name="username" type="text" value="<?= $a['username'] ?>" required />
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="password">Password :</label>
                                                                <input class="form-control" id="password" name="password" type="password" />
                                                                <small class="text-muted">Kosongkan jika tidak ingin mengubah password</small>
                                                            </div>
                                                            <hr>
                                                            <div class="mb-3">
                                                                <label for="nama">Nama :</label>
                                                                <input class="form-control" id="nama" name="nama" type="text" value="<?= $a['nama'] ?>" required />
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="email">Email :</label>
                                                                <input class="form-control" id="email" name="email" type="email" value="<?= $a['email'] ?>" required />
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="no_telp">No. Telp :</label>
                                                                <input class="form-control" id="no_telp" name="no_telp" type="text" value="<?= $a['no_telp'] ?>" required />
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="alamat">Alamat :</label>
                                                                <input class="form-control" id="alamat" name="alamat" type="text" value="<?= $a['alamat'] ?>" required />
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" name="update_user" class="btn btn-primary">Simpan</button>
                                                        <input type="hidden" name="id" value="<?= $a['id'] ?>">
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Button trigger modal -->
                                    <button type="button" class="btn btn-danger mb-3" data-bs-toggle="modal" data-bs-target="#staticBackdropHapus<?= $a['id'] ?>">
                                        Hapus
                                    </button>
                                    <!-- Modal -->
                                    <div class="modal fade" id="staticBackdropHapus<?= $a['id'] ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Hapus Admin</h1>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form action="" method="POST">
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            <div class="col mb-3 text-center">
                                                                <h3 class="fw-bold">Anda Yakin Akan Menghapus Admin "<?= $a['nama'] ?>"?</h3>
                                                                <input type="hidden" name="id" value="<?= $a['id'] ?>">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" name="delete_user" class="btn btn-danger">Hapus</button>
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
if (isset($_POST['tambah_user'])) {
    $data = [
        'username' => $_POST['username'],
        'password' => $_POST['password'],
        'nama' => $_POST['nama'],
        'email' => $_POST['email'],
        'no_telp' => $_POST['no_telp'],
        'alamat' => $_POST['alamat'],
        'role' => 'admin'
    ];
    
    $result = register_user($data);
    
    if ($result['status']) {
        echo "<script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: 'Admin berhasil ditambahkan',
                showConfirmButton: false,
                timer: 1500
            }).then(function() {
                window.location = 'admin.php';
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
if (isset($_POST['update_user'])) {
    $id = $_POST['id'];
    $data = [
        'username' => $_POST['username'],
        'nama' => $_POST['nama'],
        'email' => $_POST['email'],
        'no_telp' => $_POST['no_telp'],
        'alamat' => $_POST['alamat']
    ];
    
    // Add password only if it's not empty
    if (!empty($_POST['password'])) {
        $data['password'] = $_POST['password'];
    }
    
    $result = update_user($id, $data);
    
    if ($result['status']) {
        echo "<script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: 'Admin berhasil diupdate',
                showConfirmButton: false,
                timer: 1500
            }).then(function() {
                window.location = 'admin.php';
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