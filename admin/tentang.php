<?php
include 'header.php';
require_once '../config/function.php';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = [
        'judul' => $_POST['judul'],
        'deskripsi' => $_POST['deskripsi'],
        'alamat' => $_POST['alamat'],
        'telepon' => $_POST['telepon'],
        'email' => $_POST['email'],
        'maps_embed' => $_POST['maps_embed']
    ];
    
    $result = update_tentang($data);
    if ($result['status']) {
        echo "<script>alert('Data berhasil diupdate'); window.location.href='tentang.php';</script>";
    } else {
        echo "<script>alert('Gagal mengupdate data: " . $result['message'] . "');</script>";
    }
}

// Get current about data
$tentang = get_tentang();
?>

<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h1 class="mt-4">Tentang Kami</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item">Home</li>
                <li class="breadcrumb-item active">Tentang Kami</li>
            </ol>
            <hr>

            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-edit me-1"></i>
                    Edit Informasi
                </div>
                <div class="card-body">
                    <form method="POST" action="">
                        <div class="mb-3">
                            <label for="judul" class="form-label">Judul</label>
                            <input type="text" class="form-control" id="judul" name="judul" value="<?= $tentang['data']['judul'] ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="deskripsi" class="form-label">Deskripsi</label>
                            <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3" required><?= $tentang['data']['deskripsi'] ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="alamat" class="form-label">Alamat</label>
                            <textarea class="form-control" id="alamat" name="alamat" rows="2" required><?= $tentang['data']['alamat'] ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="telepon" class="form-label">Nomor Telepon/WhatsApp</label>
                            <input type="text" class="form-control" id="telepon" name="telepon" value="<?= $tentang['data']['telepon'] ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?= $tentang['data']['email'] ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="maps_embed" class="form-label">Google Maps Embed Code</label>
                            <textarea class="form-control" id="maps_embed" name="maps_embed" rows="3" required><?= $tentang['data']['maps_embed'] ?></textarea>
                            <small class="text-muted">Paste kode embed dari Google Maps di sini</small>
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <?php include 'footer.php'; ?>