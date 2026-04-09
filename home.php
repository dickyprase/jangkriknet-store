<?php
include 'header.php';

// Get latest products (4 items)
$latest_products = get_all_produk(['limit' => 4]);

// Get best selling products (8 items)
$best_selling = get_best_selling_products(8);

// Get all categories
$categories = get_all_kategori();
?>

<!-- Hero Section -->
<header class="py-5 bg-light border-bottom mb-4 hero-section" style="background-image: url('admin/assets/img/bg-jaringan.jpg');">
    <div class="container hero-content">
        <div class="text-center my-5">
            <h1 class="fw-bolder display-4 mb-3">Selamat Datang di <?= $app_name ?></h1>
            <p class="lead mb-4 fs-4">Temukan Solusi Jaringan Terbaik untuk Kebutuhan Rumah dan Bisnis Anda.</p>
            <a href="produk.php" class="btn btn-warning btn-lg px-5 fw-bold shadow-lg">Belanja Sekarang</a>
        </div>
    </div>
</header>

<!-- Categories Section -->
<section class="py-5 bg-white">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="mb-0 border-start border-4 border-primary ps-3">Kategori Pilihan</h3>
        </div>
        <div class="row g-4">
            <?php foreach ($categories['data'] as $category): ?>
            <div class="col-6 col-md-3">
                <a href="produk.php?kategori=<?= $category['id'] ?>" class="text-decoration-none">
                    <div class="category-card h-100 p-4">
                        <div class="category-icon">
                            <i class="bi-hdd-network"></i>
                        </div>
                        <h5 class="fw-bold text-dark m-0"><?= $category['nama'] ?></h5>
                    </div>
                </a>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Latest Products Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="mb-0 border-start border-4 border-primary ps-3">Produk Terbaru</h3>
            <a href="produk.php" class="btn btn-outline-primary btn-sm">Lihat Semua <i class="bi-arrow-right"></i></a>
        </div>
        
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
            <?php foreach ($latest_products['data'] as $product): ?>
            <div class="col">
                <div class="card h-100 position-relative">
                    <!-- Badges -->
                    <div class="position-absolute top-0 start-0 p-3" style="z-index: 2;">
                        <?php if ($product['stock'] <= 0): ?>
                            <span class="badge bg-danger rounded-pill">Habis</span>
                        <?php elseif ($product['stock'] < 5): ?>
                            <span class="badge bg-warning rounded-pill text-dark">Sisa Dikit</span>
                        <?php else: ?>
                            <span class="badge bg-success rounded-pill">Tersedia</span>
                        <?php endif; ?>
                    </div>
                    
                    <div class="position-absolute top-0 end-0 p-3" style="z-index: 2;">
                         <span class="badge bg-primary bg-opacity-75 rounded-pill"><?= $product['nama_kategori'] ?></span>
                    </div>

                    <!-- Product Image -->
                    <div class="product-img-wrapper p-3">
                        <img src="admin/uploads/<?= $product['gambar'] ?>" alt="<?= $product['nama'] ?>" />
                    </div>
                    
                    <!-- Product Details -->
                    <div class="card-body p-4 d-flex flex-column">
                        <h5 class="card-title fw-bold text-truncate"><?= $product['nama'] ?></h5>
                        <div class="mb-3">
                            <span class="fs-5 fw-bold text-primary">Rp <?= number_format($product['harga'], 0, ',', '.') ?></span>
                        </div>
                        <p class="card-text text-muted small flex-fill">
                            <?= substr(strip_tags($product['deskripsi']), 0, 50) ?>...
                        </p>
                    </div>
                    
                    <!-- Actions -->
                    <div class="card-footer p-4 pt-0 border-top-0 bg-transparent">
                            <div class="d-grid gap-2">
                                <a class="btn btn-outline-primary" href="detail.php?id=<?= $product['id'] ?>">
                                    Detail
                                </a>
                                <button type="button" class="btn btn-primary" onclick="addToCart(<?= $product['id'] ?>, 1, event)" <?= $product['stock'] <= 0 ? 'disabled' : '' ?>>
                                    <i class="bi-cart-plus me-1"></i> Beli
                                </button>
                            </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Best Seller Section -->
<section class="py-5 bg-white">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="mb-0 border-start border-4 border-warning ps-3">Produk Terlaris</h3>
        </div>
        
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
           <?php foreach ($best_selling['data'] as $product): ?>
            <div class="col">
                <div class="card h-100 border-0 shadow-sm">
                    <!-- Badges -->
                     <div class="position-absolute top-0 start-0 p-3" style="z-index: 2;">
                        <span class="badge bg-warning text-dark rounded-pill"><i class="bi-star-fill me-1"></i>Terlaris</span>
                    </div>
                    
                    <!-- Product Image -->
                    <div class="product-img-wrapper p-3">
                        <img src="admin/uploads/<?= $product['gambar'] ?>" alt="<?= $product['nama'] ?>" />
                    </div>
                    
                    <!-- Product Details -->
                    <div class="card-body p-4">
                        <h6 class="card-title fw-bold text-truncate mb-2"><?= $product['nama'] ?></h6>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fw-bold text-primary">Rp <?= number_format($product['harga'], 0, ',', '.') ?></span>
                            <small class="text-muted"><i class="bi-basket me-1"></i><?= $product['total_terjual'] ?> Terjual</small>
                        </div>
                    </div>
                    
                     <!-- Actions -->
                    <div class="card-footer p-3 border-top-0 bg-transparent text-center">
                        <a href="detail.php?id=<?= $product['id'] ?>" class="btn btn-sm btn-outline-dark w-100 stretched-link">Lihat Produk</a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Call to Action -->
<section class="py-5 bg-primary-custom text-white">
    <div class="container text-center">
        <h2 class="mb-4">Butuh Bantuan Memilih Alat Jaringan?</h2>
        <p class="lead mb-4">Tim kami siap membantu Anda menemukan solusi yang tepat.</p>
        <a href="https://wa.me/6281234567890" target="_blank" class="btn btn-light btn-lg fw-bold text-primary">
            <i class="fab fa-whatsapp me-2"></i>Hubungi Kami di WhatsApp
        </a>
    </div>
</section>

<?php
include 'footer.php';
?>