<?php
include 'header.php';

// Get product ID from URL
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Get product details
$product_result = get_produk_by_id($id);

// If product not found, redirect to home
if (!$product_result['status']) {
    echo "<script>window.location = 'index.php';</script>";
    exit;
}

$product = $product_result['data'];

// Get related products (same category, excluding current product)
$related_products = get_all_produk([
    'id_kategori' => $product['id_kategori'],
    'exclude_id' => $id,
    'limit' => 4
]);

// Get all banks for payment
$banks = get_all_bank();
?>


<!-- Breadcrumb -->
<div class="bg-light py-3 border-bottom">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="index.php">Beranda</a></li>
                <li class="breadcrumb-item"><a href="produk.php">Produk</a></li>
                <li class="breadcrumb-item"><a href="produk.php?kategori=<?= $product['id_kategori'] ?>"><?= $product['nama_kategori'] ?></a></li>
                <li class="breadcrumb-item active" aria-current="page"><?= $product['nama'] ?></li>
            </ol>
        </nav>
    </div>
</div>

<!-- Product Detail Section -->
<section class="py-5 bg-white">
    <div class="container">
        <div class="row gx-5">
            <!-- Product Image -->
            <div class="col-lg-6 mb-5 mb-lg-0">
                <div class="card border-0 shadow-sm overflow-hidden">
                    <div class="position-relative bg-light text-center p-4">
                        <img class="img-fluid" src="admin/uploads/<?= $product['gambar'] ?>" alt="<?= $product['nama'] ?>" style="max-height: 500px; object-fit: contain;">
                    </div>
                </div>
            </div>
            
            <!-- Product Info -->
            <div class="col-lg-6">
                <div class="ps-lg-3">
                    <div class="badge bg-primary bg-opacity-10 text-primary mb-2 px-3 py-2 rounded-pill">
                        <?= $product['nama_kategori'] ?>
                    </div>
                    <h1 class="display-6 fw-bold text-dark mb-3"><?= $product['nama'] ?></h1>
                    
                     <div class="mb-4 d-flex align-items-center">
                        <h2 class="fw-bold text-primary mb-0 me-3">Rp <?= number_format($product['harga'], 0, ',', '.') ?></h2>
                         <?php if ($product['stock'] > 10): ?>
                            <span class="badge bg-success rounded-pill"><i class="bi-check-circle me-1"></i>Stok Tersedia</span>
                        <?php elseif ($product['stock'] > 0): ?>
                            <span class="badge bg-warning text-dark rounded-pill"><i class="bi-exclamation-circle me-1"></i>Stok Terbatas: <?= $product['stock'] ?></span>
                        <?php else: ?>
                            <span class="badge bg-danger rounded-pill"><i class="bi-x-circle me-1"></i>Stok Habis</span>
                        <?php endif; ?>
                    </div>
                    
                    <p class="lead text-muted mb-4 fs-6">
                        <?= nl2br($product['deskripsi']) ?>
                    </p>
                    
                    <div class="bg-light p-4 rounded-3 mb-4 border">
                        <div class="row align-items-center">
                            <div class="col-md-4 mb-3 mb-md-0">
                                <label class="form-label fw-bold mb-1">Jumlah</label>
                                <div class="input-group">
                                    <button class="btn btn-outline-secondary" type="button" onclick="decrementValue()">-</button>
                                    <input type="number" class="form-control text-center" id="jumlah_input" value="1" min="1" max="<?= $product['stock'] ?>" readonly>
                                    <button class="btn btn-outline-secondary" type="button" onclick="incrementValue()">+</button>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <?php if ($product['stock'] > 0): ?>
                                <div class="d-grid gap-2">
                                     <button type="button" class="btn btn-lg btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#paymentModal">
                                        <i class="bi-bag-check me-2"></i>Beli Sekarang
                                    </button>
                                    <button type="button" class="btn btn-lg btn-outline-dark" onclick="addToCart(<?= $product['id'] ?>, currentQty, event)">
                                        <i class="bi-cart-plus me-2"></i>Tambah Keranjang
                                    </button>
                                </div>
                                <?php else: ?>
                                <div class="d-grid">
                                    <button class="btn btn-lg btn-secondary" disabled>Stok Habis</button>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex align-items-center text-muted small">
                         <div class="me-4"><i class="bi-shield-check me-2 text-success"></i>Garansi Produk</div>
                         <div class="me-4"><i class="bi-truck me-2 text-primary"></i>Pengiriman Cepat</div>
                         <div><i class="bi-headset me-2 text-warning"></i>Dukungan 24/7</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Payment Modal -->
<div class="modal fade" id="paymentModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <form id="paymentForm" action="process_checkout.php" method="POST" enctype="multipart/form-data" class="w-100">
            <input type="hidden" name="id_produk" value="<?= $product['id'] ?>">
            <input type="hidden" name="jumlah" id="jumlah_hidden" value="1">
            
            <div class="modal-content rounded-4 border-0 overflow-hidden shadow-lg">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="paymentModalLabel"><i class="bi-credit-card me-2"></i>Checkout Langsung</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body p-0">
                    <div class="row g-0">
                        <!-- Left Side: Payment Info -->
                        <div class="col-lg-7 p-4 border-end">
                            <h6 class="fw-bold mb-3 text-secondary text-uppercase ls-1">Informasi Pengiriman & Pembayaran</h6>
                            
                            <!-- User Info -->
                            <div class="bg-light p-3 rounded mb-4 border">
                                <div class="d-flex mb-2">
                                    <span class="text-muted me-2 w-25">Penerima:</span>
                                    <span class="fw-medium"><?= $_SESSION['user']['nama'] ?></span>
                                </div>
                                <div class="d-flex mb-2">
                                    <span class="text-muted me-2 w-25">Telepon:</span>
                                    <span class="fw-medium"><?= $_SESSION['user']['no_telp'] ?></span>
                                </div>
                                <div class="d-flex">
                                    <span class="text-muted me-2 w-25">Alamat:</span>
                                    <span class="fw-medium"><?= $_SESSION['user']['alamat'] ?></span>
                                </div>
                            </div>
                            
                            <!-- Payment Method -->
                            <h6 class="fw-bold mb-3 text-secondary text-uppercase ls-1">Metode Transfer Bank</h6>
                            <div class="row g-3 mb-4">
                                <?php foreach ($banks['data'] as $bank): ?>
                                <div class="col-md-6">
                                    <label class="bank-card-option card h-100 cursor-pointer border-2" style="cursor: pointer;">
                                        <div class="card-body p-3">
                                            <div class="d-flex align-items-center mb-2">
                                                <input type="radio" name="bank_option" class="form-check-input me-3" value="<?= $bank['id'] ?>" onchange="selectBank(<?= $bank['id'] ?>)">
                                                <img src="admin/uploads/<?= $bank['logo'] ?>" alt="<?= $bank['nama_bank'] ?>" style="height: 30px;">
                                            </div>
                                            <div class="ms-4 ps-2">
                                                <div class="fw-bold"><?= $bank['nama_bank'] ?></div>
                                                <div class="text-primary font-monospace fs-5"><?= $bank['no_rekening'] ?></div>
                                                <div class="small text-muted">a.n <?= $bank['atas_nama'] ?></div>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                                <?php endforeach; ?>
                            </div>
                            <input type="hidden" name="id_bank" id="selectedBank" required>
                            
                            <!-- Payment Proof -->
                             <div class="mb-3">
                                <label class="form-label fw-bold">Upload Bukti Transfer</label>
                                <input class="form-control" name="bukti_pembayaran" type="file" accept="image/*" required />
                                <div class="form-text small"><i class="bi-info-circle me-1"></i>Format: JPG, PNG. Pastikan foto terlihat jelas.</div>
                            </div>
                        </div>
                        
                        <!-- Right Side: Order Summary -->
                        <div class="col-lg-5 bg-light p-4">
                            <h6 class="fw-bold mb-4 text-secondary text-uppercase ls-1">Ringkasan Pesanan</h6>
                            
                            <div class="d-flex align-items-center mb-4">
                                <img src="admin/uploads/<?= $product['gambar'] ?>" class="rounded border bg-white p-1 me-3" style="width: 80px; height: 80px; object-fit: contain;">
                                <div>
                                    <h6 class="fw-bold mb-1"><?= $product['nama'] ?></h6>
                                    <div class="text-primary fw-bold">Rp <?= number_format($product['harga'], 0, ',', '.') ?></div>
                                </div>
                            </div>
                            
                            <div class="border-top border-bottom py-3 mb-4">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Harga Satuan</span>
                                    <span>Rp <?= number_format($product['harga'], 0, ',', '.') ?></span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Jumlah</span>
                                    <span id="summary_qty">1</span>
                                </div>
                                <div class="d-flex justify-content-between fw-bold fs-5 mt-3 text-dark">
                                    <span>Total Bayar</span>
                                    <span id="summary_total">Rp <?= number_format($product['harga'], 0, ',', '.') ?></span>
                                </div>
                            </div>
                            
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg" onclick="return validatePaymentForm()">
                                    <i class="bi-lock-fill me-2"></i>Bayar Sekarang
                                </button>
                                <div class="text-center mt-2 small text-muted">
                                    <i class="bi-shield-lock me-1"></i>Pembayaran Aman & Terenkripsi
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Related Products -->
<?php if (!empty($related_products['data'])): ?>
<section class="py-5 bg-light border-top">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold">Produk Terkait</h3>
        </div>
        <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4 g-4">
            <?php foreach ($related_products['data'] as $related): ?>
            <div class="col">
                <a href="detail.php?id=<?= $related['id'] ?>" class="text-decoration-none text-dark">
                    <div class="card h-100 border-0 shadow-sm product-card">
                         <div class="position-absolute top-0 start-0 p-3" style="z-index: 2;">
                            <?php if ($related['stock'] > 0): ?>
                                <span class="badge bg-success rounded-pill">Tersedia</span>
                            <?php else: ?>
                                <span class="badge bg-danger rounded-pill">Habis</span>
                            <?php endif; ?>
                        </div>
                        <div class="bg-white p-3 text-center" style="height: 200px;">
                            <img src="admin/uploads/<?= $related['gambar'] ?>" class="img-fluid h-100" style="object-fit: contain;">
                        </div>
                        <div class="card-body p-3">
                            <h6 class="card-title fw-bold text-truncate"><?= $related['nama'] ?></h6>
                             <div class="text-primary fw-bold">Rp <?= number_format($related['harga'], 0, ',', '.') ?></div>
                        </div>
                    </div>
                </a>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<script>
// Logic for Quantity and Subtotal
const hargaSatuan = <?= $product['harga'] ?>;
const maxStock = <?= $product['stock'] ?>;
let currentQty = 1;

function updateSummary() {
    document.getElementById('jumlah_input').value = currentQty;
    document.getElementById('jumlah_hidden').value = currentQty;
    document.getElementById('summary_qty').innerText = currentQty;
    
    const total = hargaSatuan * currentQty;
    document.getElementById('summary_total').innerText = 'Rp ' + total.toLocaleString('id-ID');
}

function incrementValue() {
    if (currentQty < maxStock) {
        currentQty++;
        updateSummary();
    }
}

function decrementValue() {
    if (currentQty > 1) {
        currentQty--;
        updateSummary();
    }
}

function selectBank(bankId) {
    document.getElementById('selectedBank').value = bankId;
    
    // Highlight selected card
    document.querySelectorAll('.bank-card-option').forEach(el => {
        el.classList.remove('border-primary', 'bg-primary', 'bg-opacity-10');
        el.classList.add('border-2');
    });
    
    const radio = document.querySelector(`input[value="${bankId}"]`);
    if(radio) {
        const card = radio.closest('.bank-card-option');
        card.classList.add('border-primary', 'bg-primary', 'bg-opacity-10');
    }
}

function validatePaymentForm() {
    const selectedBank = document.getElementById('selectedBank').value;
    const buktiPembayaran = document.querySelector('input[name="bukti_pembayaran"]').files[0];
    
    if (!selectedBank) {
        Swal.fire('Error', 'Silakan pilih metode pembayaran', 'error');
        return false;
    }
    
    if (!buktiPembayaran) {
         Swal.fire('Error', 'Silakan upload bukti transfer', 'error');
        return false;
    }
    
    Swal.fire({
        title: 'Memproses Pesanan',
        text: 'Mohon tunggu sebentar...',
        allowOutsideClick: false,
        didOpen: () => Swal.showLoading()
    });
    
    return true;
}


</script>

<?php
include 'footer.php';
?>