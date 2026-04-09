<?php
include 'header.php';

// Get search parameters
$keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';
$kategori_id = isset($_GET['kategori']) ? (int)$_GET['kategori'] : 0;
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'newest';

// Get all categories for filter
$categories = get_all_kategori();

// Prepare filters
$filters = [];
if ($keyword) $filters['keyword'] = $keyword;
if ($kategori_id) $filters['id_kategori'] = $kategori_id;

// TODO: Implement sorting in backend function if needed, for now just fetch all
$products = get_all_produk($filters);

// Helper to get category name
function get_category_name($id, $categories) {
    foreach ($categories['data'] as $cat) {
        if ($cat['id'] == $id) return $cat['nama'];
    }
    return 'Semua Kategori';
}

$current_category = $kategori_id ? get_category_name($kategori_id, $categories) : 'Semua Produk';
?>

<!-- Header -->
<header class="bg-dark py-5" style="background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('admin/assets/img/bg-jaringan.jpg') no-repeat center center; background-size: cover;">
    <div class="container px-4 px-lg-5 my-5">
        <div class="text-center text-white">
            <h1 class="display-4 fw-bolder"><?= htmlspecialchars($current_category) ?></h1>
            <p class="lead fw-normal text-white-50 mb-0">Temukan produk jaringan berkualitas untuk kebutuhan Anda</p>
        </div>
    </div>
</header>

<!-- Main Content -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <!-- Sidebar Filters -->
            <div class="col-lg-3 mb-4">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0"><i class="bi-funnel me-2"></i>Filter Pencarian</h5>
                    </div>
                    <div class="card-body">
                        <form action="" method="GET">
                            <?php if($keyword): ?>
                                <input type="hidden" name="keyword" value="<?= htmlspecialchars($keyword) ?>">
                            <?php endif; ?>
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold">Kategori</label>
                                <div class="list-group list-group-flush">
                                    <a href="produk.php<?= $keyword ? '?keyword='.$keyword : '' ?>" class="list-group-item list-group-item-action <?= $kategori_id == 0 ? 'active' : '' ?>">
                                        Semua Kategori
                                    </a>
                                    <?php foreach ($categories['data'] as $cat): ?>
                                    <a href="produk.php?kategori=<?= $cat['id'] ?><?= $keyword ? '&keyword='.$keyword : '' ?>" class="list-group-item list-group-item-action <?= $kategori_id == $cat['id'] ? 'active' : '' ?>">
                                        <?= $cat['nama'] ?>
                                    </a>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            
                            <hr>
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold">Cari Nama Produk</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" name="keyword" placeholder="Cari..." value="<?= htmlspecialchars($keyword) ?>">
                                    <button class="btn btn-primary" type="submit"><i class="bi-search"></i></button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Product Grid -->
            <div class="col-lg-9">
                <!-- Toolbar -->
                <div class="d-flex justify-content-between align-items-center mb-4 p-3 bg-white rounded shadow-sm border">
                    <div>
                        Menampilkan <span class="fw-bold"><?= isset($products['data']) ? count($products['data']) : 0 ?></span> produk
                    </div>
                   <!-- Sorting (Visual only for now if backend doesn't support it yet) -->
                   <div>
                       <select class="form-select form-select-sm" onchange="location = this.value;">
                           <option value="produk.php?sort=newest" <?= $sort == 'newest' ? 'selected' : '' ?>>Terbaru</option>
                           <option value="produk.php?sort=price_low" <?= $sort == 'price_low' ? 'selected' : '' ?>>Harga Terendah</option>
                           <option value="produk.php?sort=price_high" <?= $sort == 'price_high' ? 'selected' : '' ?>>Harga Tertinggi</option>
                       </select>
                   </div>
                </div>

                <div class="row row-cols-1 row-cols-md-2 row-cols-xl-3 g-4">
                    <?php if (isset($products['data']) && !empty($products['data'])): ?>
                        <?php foreach ($products['data'] as $product): ?>
                        <div class="col">
                            <div class="card h-100 position-relative shadow-custom border-0">
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
                                
                                <!-- Product Image -->
                                <div class="product-img-wrapper p-3 bg-light rounded-top">
                                    <img src="admin/uploads/<?= $product['gambar'] ?>" alt="<?= $product['nama'] ?>" />
                                </div>
                                
                                <!-- Product Details -->
                                <div class="card-body p-4 d-flex flex-column">
                                    <small class="text-muted mb-1"><?= $product['nama_kategori'] ?></small>
                                    <h5 class="card-title fw-bold text-truncate mb-2">
                                        <a href="detail.php?id=<?= $product['id'] ?>" class="text-dark text-decoration-none stretched-link">
                                            <?= $product['nama'] ?>
                                        </a>
                                    </h5>
                                    <div class="mb-3">
                                        <span class="fs-5 fw-bold text-primary">Rp <?= number_format($product['harga'], 0, ',', '.') ?></span>
                                    </div>
                                    <p class="card-text text-muted small flex-fill">
                                        <?= substr(strip_tags($product['deskripsi']), 0, 60) ?>...
                                    </p>
                                </div>
                                
                                <!-- Actions -->
                                <div class="card-footer p-4 pt-0 border-top-0 bg-transparent" style="position: relative; z-index: 2;">
                                    <div class="d-grid gap-2">
                                        <button type="button" class="btn btn-primary" onclick="addToCart(<?= $product['id'] ?>, 1, event)" <?= $product['stock'] <= 0 ? 'disabled' : '' ?>>
                                            <i class="bi-cart-plus me-1"></i> Beli Sekarang
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="col-12">
                             <div class="text-center py-5">
                                <img src="assets/no-product.svg" alt="" style="width: 150px; opacity: 0.5;" onerror="this.style.display='none'">
                                <h4 class="mt-4 text-muted">Produk Tidak Ditemukan</h4>
                                <p class="text-muted">Coba kata kunci lain atau reset filter pencarian Anda.</p>
                                <a href="produk.php" class="btn btn-outline-primary mt-2">Reset Filter</a>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
                
                <!-- Pagination (Static for now as simple PHP native usually fetches all) -->
                <!-- If needed, backend pagination logic would go here -->
            </div>
        </div>
    </div>
</section>

<?php
include 'footer.php';
?>