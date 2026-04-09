<?php
include 'header.php';

// Get all users with role 'user'
$result = get_all_users();
$user_list = array_filter($result['data'] ?? [], function($user) {
    return $user['role'] === 'user';
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
                text: 'Customer berhasil dihapus',
                showConfirmButton: false,
                timer: 1500
            }).then(function() {
                window.location = 'customer.php';
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
            <h1 class="mt-4">Akun Customer</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item">Home</li>
                <li class="breadcrumb-item">Data</li>
                <li class="breadcrumb-item">Akun</li>
                <li class="breadcrumb-item active">Customer</li>
            </ol>
            <hr>
            <!-- Button trigger modal -->
            <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                Tambah Customer
            </button>
            <!-- Modal -->
            <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="staticBackdropLabel">Tambah Customer Baru</h1>
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
                                        <textarea name="alamat" id="alamat" class="form-control" required></textarea>
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
                    Akun Customer
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
                            foreach ($user_list as $u): 
                            ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= $u['nama'] ?></td>
                                <td><?= $u['email'] ?></td>
                                <td><?= $u['no_telp'] ?></td>
                                <td><?= $u['alamat'] ?></td>
                                <td><?= $u['username'] ?></td>
                                <td>
                                    <!-- Button trigger modal -->
                                    <button type="button" class="btn btn-warning mb-3" data-bs-toggle="modal" data-bs-target="#staticBackdropUbah<?= $u['id'] ?>">
                                        Ubah
                                    </button>
                                    <!-- Modal -->
                                    <div class="modal fade" id="staticBackdropUbah<?= $u['id'] ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Ubah Akun Customer</h1>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form action="" method="POST">
                                                    <div class="modal-body">
                                                        <div class="row row-cols-1">
                                                            <div class="mb-3">
                                                                <label for="username">Username :</label>
                                                                <input class="form-control" id="username" name="username" type="text" value="<?= $u['username'] ?>" required />
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="password">Password :</label>
                                                                <input class="form-control" id="password" name="password" type="password" />
                                                                <small class="text-muted">Kosongkan jika tidak ingin mengubah password</small>
                                                            </div>
                                                            <hr>
                                                            <div class="mb-3">
                                                                <label for="nama">Nama :</label>
                                                                <input class="form-control" id="nama" name="nama" type="text" value="<?= $u['nama'] ?>" required />
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="email">Email :</label>
                                                                <input class="form-control" id="email" name="email" type="email" value="<?= $u['email'] ?>" required />
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="no_telp">No. Telp :</label>
                                                                <input class="form-control" id="no_telp" name="no_telp" type="text" value="<?= $u['no_telp'] ?>" required />
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="alamat">Alamat :</label>
                                                                <input class="form-control" id="alamat" name="alamat" type="text" value="<?= $u['alamat'] ?>" required />
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" name="update_user" class="btn btn-primary">Simpan</button>
                                                        <input type="hidden" name="id" value="<?= $u['id'] ?>">
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Button trigger modal -->
                                    <button type="button" class="btn btn-danger mb-3" data-bs-toggle="modal" data-bs-target="#staticBackdropHapus<?= $u['id'] ?>">
                                        Hapus
                                    </button>
                                    <!-- Modal -->
                                    <div class="modal fade" id="staticBackdropHapus<?= $u['id'] ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Hapus Customer</h1>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form action="" method="POST">
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            <div class="col mb-3 text-center">
                                                                <h3 class="fw-bold">Anda Yakin Akan Menghapus Customer "<?= $u['nama'] ?>"?</h3>
                                                                <input type="hidden" name="id" value="<?= $u['id'] ?>">
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
        'role' => 'user'
    ];
    
    $result = register_user($data);
    
    if ($result['status']) {
        echo "<script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: 'Customer berhasil ditambahkan',
                showConfirmButton: false,
                timer: 1500
            }).then(function() {
                window.location = 'customer.php';
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
                text: 'Customer berhasil diupdate',
                showConfirmButton: false,
                timer: 1500
            }).then(function() {
                window.location = 'customer.php';
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