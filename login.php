<?php 
require_once 'config/function.php';
session_start();

if (isset($_SESSION['user'])) {
    if ($_SESSION['role'] === 'admin') {
        header('Location: admin/index.php');
    } else {
        header('Location: home.php');
    }
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Login - <?= $app_name ?></title>
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
        
        .login-container {
            min-height: 100vh;
            display: flex;
        }
        
        .login-sidebar {
            background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%);
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 3rem;
            position: relative;
            overflow: hidden;
        }
        
        .login-sidebar::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0) 70%);
            transform: rotate(30deg);
        }
        
        .login-content {
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
        }
        
        .form-control:focus {
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
            border-color: #4f46e5;
        }
        
        .btn-primary {
            background-color: #4f46e5;
            border-color: #4f46e5;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            border-radius: 0.5rem;
            transition: all 0.2s;
        }
        
        .btn-primary:hover {
            background-color: #4338ca;
            border-color: #4338ca;
            transform: translateY(-1px);
            box-shadow: 0 4px 6px -1px rgba(79, 70, 229, 0.2);
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
            .login-sidebar {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="container-fluid p-0">
        <div class="row g-0 min-vh-100">
            <!-- Left Side: Brand/Illustration -->
            <div class="col-lg-5 col-xl-4 login-sidebar">
                <div style="position: relative; z-index: 1;">
                    <div class="mb-4">
                        <i class="bi bi-hdd-network display-4 text-white"></i>
                    </div>
                    <h1 class="brand-logo"><?= $app_name ?></h1>
                    <p class="welcome-text mb-5">Solusi manajemen toko dan inventaris modern, cepat, dan terpercaya untuk bisnis Anda.</p>
                    
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-white bg-opacity-10 p-3 rounded-3 border border-white border-opacity-10">
                            <i class="bi bi-shield-check fs-4 mb-2 d-block"></i>
                            <span class="small fw-medium">Aman & Terjamin</span>
                        </div>
                        <div class="bg-white bg-opacity-10 p-3 rounded-3 border border-white border-opacity-10">
                            <i class="bi bi-lightning-charge fs-4 mb-2 d-block"></i>
                            <span class="small fw-medium">Performa Cepat</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Right Side: Login Form -->
            <div class="col-lg-7 col-xl-8 login-content">
                <div style="width: 100%; max-width: 450px;">
                    <div class="mb-5 text-center text-lg-start">
                        <h2 class="fw-bold mb-2">Selamat Datang Kembali</h2>
                        <p class="text-muted">Masuk ke akun Anda untuk melanjutkan</p>
                    </div>
                    
                    <?php
                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        $username = $_POST['username'];
                        $password = $_POST['password'];
                        
                        $result = login($username, $password);
                        
                        if ($result['status']) {
                            $_SESSION['user_id'] = $result['data']['id'];
                            $_SESSION['user'] = $result['data'];
                            $_SESSION['role'] = $result['data']['role'];
                            $_SESSION['username'] = $result['data']['username'];
                            $_SESSION['nama'] = $result['data']['nama'];
                            
                            if ($result['data']['role'] === 'admin') {
                                echo '<script>window.location.href = "admin/index.php";</script>';
                            } else {
                                echo '<script>window.location.href = "home.php";</script>';
                            }
                        } else {
                            echo '<div class="alert alert-danger border-0 shadow-sm d-flex align-items-center mb-4" role="alert">
                                    <i class="bi bi-exclamation-circle-fill fs-5 me-2"></i>
                                    <div>' . $result['message'] . '</div>
                                  </div>';
                        }
                    }
                    ?>
                    
                    <form method="POST" action="">
                        <div class="mb-4">
                            <label for="username" class="form-label fw-medium text-secondary small">USERNAME</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-person"></i></span>
                                <input class="form-control border-start-0 ps-0 bg-light" id="username" name="username" type="text" placeholder="Masukkan username Anda" required />
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <label for="password" class="form-label fw-medium text-secondary small">PASSWORD</label>
                            </div>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-lock"></i></span>
                                <input class="form-control border-start-0 ps-0 bg-light" id="password" name="password" type="password" placeholder="Masukkan password Anda" required />
                            </div>
                        </div>
                        
                         <div class="mb-4 d-flex justify-content-between align-items-center">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="rememberMe">
                                <label class="form-check-label text-sm text-muted" for="rememberMe">
                                    Ingat Saya
                                </label>
                            </div>
                             <a href="#" class="text-decoration-none small text-primary fw-medium">Lupa Password?</a>
                        </div>
                        
                        <div class="d-grid mb-4">
                            <button type="submit" class="btn btn-primary py-3">
                                Masuk Sekarang
                            </button>
                        </div>
                        
                        <div class="text-center">
                            <p class="text-muted small">Belum punya akun? <a href="register.php" class="text-decoration-none fw-bold text-primary">Daftar disini</a></p>
                        </div>
                        
                        <div class="text-center mt-5">
                            <a href="index.php" class="text-decoration-none text-muted small d-inline-flex align-items-center hover-primary">
                                <i class="bi bi-arrow-left me-1"></i> Kembali ke Beranda
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>