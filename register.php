<?php require_once 'config/function.php' ?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Daftar Akun - <?= $app_name ?></title>
     <!-- Fonts -->
     <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f3f4f6;
            min-height: 100vh;
        }
        
        .register-container {
            min-height: 100vh;
            display: flex;
        }
        
        .register-sidebar {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 3rem;
            position: relative;
            overflow: hidden;
        }
        
        .register-sidebar::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0) 70%);
            transform: rotate(30deg);
        }
        
        .register-content {
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            /* width: 100%; Removed to allow bootstrap grid to work */
        }
        
        .form-control {
            padding: 0.75rem 1rem;
            border-radius: 0.5rem;
            border: 1px solid #e5e7eb;
            font-size: 0.95rem;
            background-color: #f9fafb;
        }
        
        .form-control:focus {
            box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.1);
            border-color: #10b981;
            background-color: #fff;
        }
        
        .btn-success {
            background-color: #10b981;
            border-color: #10b981;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            border-radius: 0.5rem;
            transition: all 0.2s;
        }
        
        .btn-success:hover {
            background-color: #059669;
            border-color: #059669;
            transform: translateY(-1px);
            box-shadow: 0 4px 6px -1px rgba(16, 185, 129, 0.2);
        }
        
        .brand-logo {
            font-weight: 800;
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
            letter-spacing: -0.05em;
        }
        
        .welcome-text {
            font-size: 1.1rem;
            opacity: 0.9;
            line-height: 1.6;
        }

        @media (max-width: 991.98px) {
            .register-sidebar {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="container-fluid p-0">
        <div class="row g-0 min-vh-100">
            <!-- Left Side: Brand/Illustration -->
            <div class="col-lg-5 col-xl-4 register-sidebar">
                <div style="position: relative; z-index: 1;">
                    <div class="mb-4">
                        <i class="bi bi-person-plus display-4 text-white"></i>
                    </div>
                    <h1 class="brand-logo">Gabung Sekarang</h1>
                    <p class="welcome-text mb-5">Daftarkan akun Anda dan nikmati kemudahan berbelanja kebutuhan jaringan dan teknologi.</p>
                    
                    <div class="d-flex flex-column gap-3">
                        <div class="d-flex align-items-center gap-3 bg-white bg-opacity-10 p-3 rounded-3 border border-white border-opacity-10">
                             <div class="rounded-circle bg-white bg-opacity-20 p-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                <i class="bi bi-tag text-white"></i>
                            </div>
                            <div>
                                <h6 class="mb-0 fw-bold">Penawaran Eksklusif</h6>
                                <small class="opacity-75">Dapatkan harga terbaik</small>
                            </div>
                        </div>
                         <div class="d-flex align-items-center gap-3 bg-white bg-opacity-10 p-3 rounded-3 border border-white border-opacity-10">
                            <div class="rounded-circle bg-white bg-opacity-20 p-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                <i class="bi bi-truck text-white"></i>
                            </div>
                            <div>
                                <h6 class="mb-0 fw-bold">Lacak Pesanan</h6>
                                <small class="opacity-75">Pantau pengiriman real-time</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Right Side: Register Form -->
            <div class="col-lg-7 col-xl-8 register-content">
                <div style="width: 100%; max-width: 600px;">
                    <div class="mb-4 text-center text-lg-start">
                        <h2 class="fw-bold mb-2">Buat Akun Baru</h2>
                        <p class="text-muted">Isi formulir di bawah ini untuk mendaftar</p>
                    </div>
                    
                    <?php
                        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                            $data = [
                                'nama' => $_POST['nama'],
                                'username' => $_POST['username'],
                                'password' => $_POST['password'],
                                'email' => $_POST['email'],
                                'alamat' => $_POST['alamat'],
                                'no_telp' => $_POST['no_telp']
                            ];
                            
                            $result = register_user($data);
                            
                            if ($result['status']) {
                                echo '<div class="alert alert-success border-0 shadow-sm d-flex align-items-center mb-4" role="alert">
                                        <i class="bi bi-check-circle-fill fs-5 me-2"></i>
                                        <div>' . $result['message'] . '</div>
                                      </div>';
                                echo '<script>setTimeout(function() { window.location.href = "login.php"; }, 2000);</script>';
                            } else {
                                echo '<div class="alert alert-danger border-0 shadow-sm d-flex align-items-center mb-4" role="alert">
                                        <i class="bi bi-exclamation-circle-fill fs-5 me-2"></i>
                                        <div>' . $result['message'] . '</div>
                                      </div>';
                            }
                        }
                    ?>
                    
                    <form method="POST" action="">
                        <div class="row g-3">
                            <div class="col-12">
                                <label for="nama" class="form-label fw-medium text-secondary small">NAMA LENGKAP</label>
                                <input class="form-control" id="nama" name="nama" type="text" placeholder="Contoh: John Doe" required />
                            </div>
                            
                            <div class="col-12">
                                <label for="username" class="form-label fw-medium text-secondary small">USERNAME</label>
                                <input class="form-control" id="username" name="username" type="text" placeholder="Contoh: johndoe123" required />
                            </div>
                            
                            <div class="col-12">
                                <label for="email" class="form-label fw-medium text-secondary small">EMAIL</label>
                                <input class="form-control" id="email" name="email" type="email" placeholder="nama@email.com" required />
                            </div>
                            
                            <div class="col-12">
                                <label for="no_telp" class="form-label fw-medium text-secondary small">NOMOR TELEPON</label>
                                <input class="form-control" id="no_telp" name="no_telp" type="tel" placeholder="08123456789" required />
                            </div>
                            
                             <div class="col-12">
                                <label for="password" class="form-label fw-medium text-secondary small">PASSWORD</label>
                                <input class="form-control" id="password" name="password" type="password" placeholder="Buat password yang kuat" required />
                            </div>
                            
                            <div class="col-12">
                                <label for="alamat" class="form-label fw-medium text-secondary small">ALAMAT LENGKAP</label>
                                <textarea class="form-control" id="alamat" name="alamat" placeholder="Masukkan alamat lengkap pengiriman" style="height: 100px" required></textarea>
                            </div>
                        </div>
                        
                        <div class="form-check mt-3 mb-4">
                            <input class="form-check-input" type="checkbox" value="" id="policyCheck" required>
                            <label class="form-check-label text-muted small" for="policyCheck">
                                Saya setuju dengan <a href="#" class="text-success text-decoration-none">Syarat & Ketentuan</a> serta <a href="#" class="text-success text-decoration-none">Kebijakan Privasi</a>.
                            </label>
                        </div>
                        
                        <div class="d-grid mb-4">
                            <button type="submit" class="btn btn-success py-3">
                                <i class="bi bi-person-plus me-2"></i>Daftar Sekarang
                            </button>
                        </div>
                        
                        <div class="text-center">
                            <p class="text-muted small">Sudah punya akun? <a href="login.php" class="text-decoration-none fw-bold text-success">Masuk disini</a></p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>