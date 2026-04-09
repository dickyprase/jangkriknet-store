<?php
include 'config/function.php';
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}
if ($_SESSION['role'] === 'admin') {
    header('Location: admin/index.php');
    exit();
}

// Get all categories
$result_kategori = get_all_kategori();
$kategori_list = $result_kategori['data'] ?? [];

// Helper to check active link
function is_active($page) {
    $current = basename($_SERVER['PHP_SELF']);
    return $current === $page ? 'active' : '';
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="Toko Online Alat Jaringan Terbaik" />
    <meta name="author" content="" />
    <title><?= $app_name ?></title>
    
    <!-- Favicon-->
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet" />
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
    
    <!-- Custom CSS -->
    <link href="css/custom.css" rel="stylesheet" />
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
    function updateCartCount() {
        fetch('get_cart_count.php')
            .then(response => response.json())
            .then(data => {
                if (data.status) {
                    const cartBadge = document.querySelector('.cart-badge');
                    if (cartBadge) {
                        cartBadge.textContent = data.count;
                        // Hide badge if count is 0
                        if (parseInt(data.count) === 0) {
                            cartBadge.style.display = 'none';
                        } else {
                            cartBadge.style.display = 'inline-block';
                        }
                    }
                }
            })
            .catch(error => console.error('Error updating cart count:', error));
    }

    // Update cart count when page loads
    document.addEventListener('DOMContentLoaded', function() {
        updateCartCount();
    });
    </script>
</head>

<body>
    <!-- Navigation-->
    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container">
            <a class="navbar-brand" href="home.php">
                <i class="fas fa-network-wired me-2"></i><?= $app_name ?>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link <?= is_active('home.php') ?>" href="home.php">Beranda</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle <?= is_active('produk.php') || is_active('kategori.php') ? 'active' : '' ?>" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Kategori
                        </a>
                        <ul class="dropdown-menu shadow-custom border-0" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="produk.php">Semua Produk</a></li>
                            <li><hr class="dropdown-divider" /></li>
                            <?php foreach ($kategori_list as $kategori): ?>
                            <li>
                                <a class="dropdown-item" href="kategori.php?id=<?= $kategori['id'] ?>">
                                    <?= $kategori['nama'] ?>
                                </a>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= is_active('tentang.php') ?>" href="tentang.php">Tentang Kami</a>
                    </li>
                </ul>
                
                <form class="d-flex me-3" role="search" action="produk.php" method="GET">
                    <div class="input-group">
                        <input class="form-control border-end-0" type="search" name="keyword" placeholder="Cari produk..." aria-label="Search" style="border-radius: 0.5rem 0 0 0.5rem;" />
                        <button class="btn btn-primary" type="submit" style="border-radius: 0 0.5rem 0.5rem 0;">
                            <i class="bi-search"></i>
                        </button>
                    </div>
                </form>
                
                <div class="d-flex align-items-center">
                    <a class="btn btn-outline-primary position-relative me-3" href="keranjang.php">
                        <i class="bi-cart-fill"></i>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger cart-badge" style="display: none;">
                            0
                            <span class="visually-hidden">unread messages</span>
                        </span>
                    </a>
                    
                    <div class="dropdown">
                        <a class="btn btn-light dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <div class="bg-primary-custom text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">
                                <i class="bi-person-fill"></i>
                            </div>
                            <span class="fw-semibold d-none d-lg-inline"><?= $_SESSION['user']['nama'] ?></span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow-custom border-0">
                            <li><a class="dropdown-item" href="akun.php"><i class="bi-person me-2"></i>Akun Saya</a></li>
                            <li><a class="dropdown-item" href="pesanan.php"><i class="bi-bag me-2"></i>Pesanan Saya</a></li>
                            <li><hr class="dropdown-divider" /></li>
                            <li><a class="dropdown-item text-danger" href="logout.php"><i class="bi-box-arrow-right me-2"></i>Keluar</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </nav>