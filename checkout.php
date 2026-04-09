<?php
include 'header.php';

// Get cart items and total
$cart_items_result = get_cart($_SESSION['user']['id']);
$cart_items = $cart_items_result['data'] ?? [];

$cart_total_result = get_cart_total($_SESSION['user']['id']);
$cart_total = $cart_total_result['data']['total'] ?? 0;

// Get user data
$user_result = get_user_by_id($_SESSION['user']['id']);
$user = $user_result['data'] ?? [];

// Get all banks
$banks = get_all_bank();

// If cart is empty, redirect to cart page
if (empty($cart_items)) {
    echo "<script>window.location = 'keranjang.php';</script>";
    exit;
}
?>


<!-- Breadcrumb -->
<div class="bg-light py-3 border-bottom">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="index.php">Beranda</a></li>
                <li class="breadcrumb-item"><a href="keranjang.php">Keranjang</a></li>
                <li class="breadcrumb-item active" aria-current="page">Checkout</li>
            </ol>
        </nav>
    </div>
</div>

<section class="py-5 bg-white">
    <div class="container">
        <div class="text-center mb-5">
            <h1 class="fw-bold">Checkout Pesanan</h1>
            <p class="text-muted">Lengkapi informasi pembayaran Anda untuk menyelesaikan pesanan.</p>
        </div>
        
        <form id="checkoutForm" action="process_checkout.php" method="POST" enctype="multipart/form-data">
            <div class="row g-5">
                <!-- Left Column: Order Summary -->
                <div class="col-lg-5 order-lg-2">
                    <div class="card border-0 shadow-sm bg-light">
                        <div class="card-header bg-transparent border-bottom py-3">
                            <h5 class="mb-0 fw-bold text-primary">Ringkasan Pesanan</h5>
                        </div>
                        <div class="card-body">
                            <ul class="list-group list-group-flush mb-3 rounded">
                                <?php foreach ($cart_items as $item): ?>
                                <li class="list-group-item d-flex justify-content-between lh-sm py-3 bg-transparent">
                                    <div class="d-flex">
                                        <div class="me-3 position-relative">
                                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-secondary">
                                                <?= $item['qty'] ?>
                                            </span>
                                            <img src="admin/uploads/<?= $item['gambar'] ?>" alt="<?= $item['nama_produk'] ?>" class="rounded border bg-white" style="width: 50px; height: 50px; object-fit: contain;">
                                        </div>
                                        <div>
                                            <h6 class="my-0 text-truncate" style="max-width: 150px;"><?= $item['nama_produk'] ?></h6>
                                            <small class="text-muted">Rp <?= number_format($item['harga'], 0, ',', '.') ?></small>
                                        </div>
                                    </div>
                                    <span class="text-muted fw-bold">Rp <?= number_format($item['harga'] * $item['qty'], 0, ',', '.') ?></span>
                                </li>
                                <?php endforeach; ?>
                            </ul>
                            
                            <div class="d-flex justify-content-between mb-3 border-top pt-3">
                                <span class="fw-bold">Total Pembayaran</span>
                                <strong class="fs-4 text-primary">Rp <?= number_format($cart_total, 0, ',', '.') ?></strong>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Payment & Shipping Info -->
                <div class="col-lg-7 order-lg-1">
                    <h4 class="mb-3 fw-bold">Informasi Pengiriman</h4>
                    <div class="row g-3 mb-4">
                        <div class="col-12">
                            <label class="form-label text-muted small text-uppercase fw-bold">Nama Lengkap</label>
                            <input type="text" class="form-control" value="<?= $user['nama'] ?>" readonly>
                        </div>
                        <div class="col-12">
                            <label class="form-label text-muted small text-uppercase fw-bold">Nomor Telepon</label>
                            <input type="text" class="form-control" value="<?= $user['no_telp'] ?>" readonly>
                        </div>
                        <div class="col-12">
                            <label class="form-label text-muted small text-uppercase fw-bold">Alamat Pengiriman</label>
                            <textarea class="form-control" rows="3" readonly><?= $user['alamat'] ?></textarea>
                            <div class="form-text"><i class="bi bi-info-circle me-1"></i>Untuk mengubah alamat, silakan edit di halaman profil.</div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <h4 class="mb-3 fw-bold">Metode Pembayaran</h4>
                    <div class="row g-3 mb-4">
                        <?php foreach ($banks['data'] as $bank): ?>
                        <div class="col-md-6">
                            <label class="card h-100 cursor-pointer bank-card-option border-2 p-3" style="cursor: pointer;">
                                <div class="d-flex align-items-center mb-2">
                                    <input type="radio" name="bank_option" class="form-check-input me-3" value="<?= $bank['id'] ?>" onchange="selectBank(<?= $bank['id'] ?>)">
                                    <img src="admin/uploads/<?= $bank['logo'] ?>" alt="<?= $bank['nama_bank'] ?>" style="height: 30px;">
                                </div>
                                <div class="ms-4 ps-2">
                                    <div class="fw-bold"><?= $bank['nama_bank'] ?></div>
                                    <div class="text-primary font-monospace fs-5"><?= $bank['no_rekening'] ?></div>
                                    <div class="small text-muted">a.n <?= $bank['atas_nama'] ?></div>
                                </div>
                            </label>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <input type="hidden" name="id_bank" id="selectedBank" required>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Upload Bukti Transfer</label>
                        <input class="form-control form-control-lg" name="bukti_pembayaran" type="file" accept="image/*" required>
                        <div class="form-text small">Pastikan foto bukti transfer terlihat jelas. Format: JPG, PNG.</div>
                    </div>

                    <hr class="my-4">

                    <button class="btn btn-primary btn-lg w-100 py-3 fw-bold shadow-sm" type="submit" onclick="return validateCheckout()">
                        <i class="bi bi-check-circle-fill me-2"></i>Konfirmasi & Bayar Sekarang
                    </button>
                    <div class="text-center mt-3 text-muted small">
                        Dengan melanjutkan, Anda menyetujui syarat & ketentuan kami.
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>

<script>
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

function validateCheckout() {
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