<?php
include 'header.php';

// Get user data
$user_result = get_user_by_id($_SESSION['user']['id']);
$user_data = $user_result['data'];

// Handle Update
if (isset($_POST['update_profile'])) {
    $data = [
        'nama' => $_POST['nama'],
        'email' => $_POST['email'],
        'no_telp' => $_POST['no_telp'],
        'alamat' => $_POST['alamat']
    ];
    
    // Update password if provided
    if (!empty($_POST['password'])) {
        $data['password'] = $_POST['password'];
    }
    
    $result = update_user($_SESSION['user']['id'], $data);
    
    if ($result['status']) {
        echo "<script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: 'Profil berhasil diperbarui',
                showConfirmButton: false,
                timer: 1500
            }).then(function() {
                window.location = 'akun.php';
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


<!-- Header -->
<div class="bg-primary-custom py-5 mb-5 text-white">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h1 class="fw-bold">Akun Saya</h1>
                <p class="lead mb-0 opacity-75">Kelola informasi profil dan keamanan akun Anda.</p>
            </div>
             <div class="col-md-4 text-md-end mt-3 mt-md-0">
                <a href="pesanan.php" class="btn btn-outline-light">
                    <i class="bi bi-box-seam me-2"></i>Riwayat Pesanan
                </a>
            </div>
        </div>
    </div>
</div>

<section class="mb-5">
    <div class="container">
        <div class="row g-4">
            <!-- Sidebar -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm mb-4 text-center p-4">
                    <div class="mb-3">
                        <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 100px; height: 100px; font-size: 2.5rem;">
                            <?= strtoupper(substr($user_data['nama'], 0, 1)) ?>
                        </div>
                    </div>
                    <h4 class="fw-bold mb-1"><?= $user_data['nama'] ?></h4>
                    <p class="text-muted mb-3"><?= $user_data['email'] ?></p>
                    <div class="d-grid">
                        <a href="logout.php" class="btn btn-outline-danger">
                            <i class="bi bi-box-arrow-right me-2"></i>Logout
                        </a>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom py-3">
                        <h5 class="mb-0 fw-bold"><i class="bi bi-person-gear me-2"></i>Edit Profil</h5>
                    </div>
                    <div class="card-body p-4">
                        <form method="POST" action="">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Username</label>
                                    <input type="text" class="form-control bg-light" value="<?= $user_data['username'] ?>" readonly>
                                    <div class="form-text">Username tidak dapat diubah.</div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Nama Lengkap</label>
                                    <input type="text" class="form-control" name="nama" value="<?= $user_data['nama'] ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Email</label>
                                    <input type="email" class="form-control" name="email" value="<?= $user_data['email'] ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">No. Telepon</label>
                                    <input type="text" class="form-control" name="no_telp" value="<?= $user_data['no_telp'] ?>" required>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Alamat Lengkap</label>
                                    <textarea class="form-control" name="alamat" rows="3" required><?= $user_data['alamat'] ?></textarea>
                                </div>
                                
                                <div class="col-12 mt-4">
                                    <div class="p-3 bg-light rounded border">
                                        <h6 class="fw-bold mb-3"><i class="bi bi-shield-lock me-2"></i>Ganti Password</h6>
                                        <div class="mb-0">
                                            <label class="form-label">Password Baru</label>
                                            <input type="password" class="form-control" name="password" placeholder="Kosongkan jika tidak ingin mengubah password">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="d-grid mt-4">
                                <button type="submit" name="update_profile" class="btn btn-primary btn-lg shadow-sm">
                                    <i class="bi bi-save me-2"></i>Simpan Perubahan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
include 'footer.php';
?>