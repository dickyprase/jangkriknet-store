<?php
include 'header.php';

// Get category ID from URL
$id_kategori = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Get category details
$kategori = get_kategori_by_id($id_kategori);

// If category not found, redirect to home
if (!$kategori['status']) {
    echo "<script>window.location = 'index.php';</script>";
    exit;
}

$kategori = $kategori['data'];

// Get products in this category
$products = get_all_produk(['id_kategori' => $id_kategori]);
?>
<style>
    .product-card {
        transition: transform 0.3s ease;
    }
    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    .product-image {
        height: 200px;
        object-fit: cover;
    }
    .category-badge {
        position: absolute;
        top: 10px;
        right: 10px;
        background: rgba(0,0,0,0.7);
        color: white;
        padding: 5px 10px;
        border-radius: 3px;
        font-size: 0.8rem;
    }
    .stock-badge {
        position: absolute;
        top: 10px;
        left: 10px;
        padding: 5px 10px;
        border-radius: 3px;
        font-size: 0.8rem;
    }
    .in-stock {
        background: rgba(46, 204, 113, 0.9);
        color: white;
    }
    .low-stock {
        background: rgba(241, 196, 15, 0.9);
        color: white;
    }
    .out-of-stock {
        background: rgba(231, 76, 60, 0.9);
        color: white;
    }
    .price {
        color: #2c3e50;
        font-weight: bold;
    }
</style>

<!-- Header-->
<header class="bg-dark py-5" style="background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)),url('admin/assets/img/bg-jaringan.jpg') no-repeat center center fixed; background-size: cover;">
    <div class="container px-4 px-lg-5 my-5">
        <div class="text-center text-white">
            <h1 class="display-4 fw-bolder"><?= $kategori['nama'] ?></h1>
            <p class="lead fw-normal text-white-50 mb-0">Temukan produk <?= $kategori['nama'] ?> terbaik untuk kebutuhan Anda</p>
        </div>
    </div>
</header>

<!-- Section-->
<section class="py-5">
    <div class="container px-4 px-lg-5 mt-5">
        <div class="row gx-4 gx-lg-5 row-cols-1 row-cols-md-2 row-cols-xl-4">
            <?php if ($products['status'] && !empty($products['data'])): ?>
                <?php foreach ($products['data'] as $product): ?>
                <div class="col mb-5">
                    <div class="card h-100 product-card">
                        <!-- Product image-->
                        <img class="card-img-top product-image" src="admin/uploads/<?= $product['gambar'] ?>" alt="<?= $product['nama'] ?>" />
                        <!-- Stock badge -->
                        <?php if ($product['stock'] > 10): ?>
                            <span class="stock-badge in-stock">Tersedia</span>
                        <?php elseif ($product['stock'] > 0): ?>
                            <span class="stock-badge low-stock">Stok Terbatas</span>
                        <?php else: ?>
                            <span class="stock-badge out-of-stock">Habis</span>
                        <?php endif; ?>
                        <!-- Category badge -->
                        <span class="category-badge"><?= $product['nama_kategori'] ?></span>
                        <!-- Product details-->
                        <div class="card-body p-4">
                            <div class="text-center">
                                <!-- Product name-->
                                <h5 class="fw-bolder"><?= $product['nama'] ?></h5>
                                <!-- Product price-->
                                <div class="price mb-3">
                                    Rp <?= number_format($product['harga'], 0, ',', '.') ?>
                                </div>
                            </div>
                        </div>
                        <!-- Product actions-->
                        <div class="card-footer p-4 pt-0 border-top-0 bg-transparent">
                            <div class="text-center">
                                <a class="btn btn-outline-dark mt-auto" href="detail.php?id=<?= $product['id'] ?>">
                                    <i class="bi-eye-fill me-2"></i>Lihat Detail
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12 text-center">
                    <div class="alert alert-info">
                        Belum ada produk dalam kategori ini.
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php
include 'footer.php';
?> 