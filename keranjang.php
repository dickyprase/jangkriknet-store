<?php
include 'header.php';

// Get cart items
$cart_items_result = get_cart($_SESSION['user']['id']);
$cart_items = $cart_items_result['data'] ?? [];

$cart_total_result = get_cart_total($_SESSION['user']['id']);
$cart_total = $cart_total_result['data']['total'] ?? 0;
?>


<!-- Header -->
<div class="bg-light py-5 mb-5">
    <div class="container">
        <div class="text-center text-dark">
            <h1 class="display-4 fw-bolder">Keranjang Belanja</h1>
            <p class="lead fw-normal text-muted mb-0">Kelola item belanjaan Anda sebelum checkout.</p>
        </div>
    </div>
</div>

<section class="mb-5">
    <div class="container">
        <?php if (empty($cart_items)): ?>
            <div class="text-center py-5">
                <i class="bi bi-cart-x display-1 text-muted mb-4 opacity-50"></i>
                <h2 class="h4 mb-3">Keranjang Anda Kosong</h2>
                <p class="text-muted mb-4">Sepertinya Anda belum menambahkan produk apapun.</p>
                <a href="produk.php" class="btn btn-primary btn-lg shadow-sm">
                    <i class="bi bi-arrow-left me-2"></i>Mulai Belanja
                </a>
            </div>
        <?php else: ?>
            <div class="row g-4">
                <!-- Cart Items List -->
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
                        <div class="card-header bg-white border-bottom py-3">
                            <h5 class="mb-0 fw-bold"><i class="bi bi-bag me-2"></i>Daftar Item (<?= count($cart_items) ?>)</h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table align-middle mb-0 table-hover">
                                    <thead class="bg-light text-muted small text-uppercase">
                                        <tr>
                                            <th scope="col" class="ps-4 py-3" style="width: 40%;">Produk</th>
                                            <th scope="col" class="py-3" style="width: 20%;">Harga</th>
                                            <th scope="col" class="py-3" style="width: 20%;">Jumlah</th>
                                            <th scope="col" class="py-3 text-end pe-4" style="width: 20%;">Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($cart_items as $item): ?>
                                        <tr>
                                            <td class="ps-4 py-3">
                                                <div class="d-flex align-items-center">
                                                    <a href="detail.php?id=<?= $item['id_produk'] ?>">
                                                        <img src="admin/uploads/<?= $item['gambar'] ?>" alt="<?= $item['nama_produk'] ?>" class="rounded me-3 border" style="width: 64px; height: 64px; object-fit: contain;">
                                                    </a>
                                                    <div>
                                                        <a href="detail.php?id=<?= $item['id_produk'] ?>" class="text-dark text-decoration-none fw-bold d-block text-truncate" style="max-width: 200px;">
                                                            <?= $item['nama_produk'] ?>
                                                        </a>
                                                        <button class="btn btn-link btn-sm text-danger p-0 text-decoration-none mt-1" onclick="removeFromCart(<?= $item['id'] ?>)">
                                                            <i class="bi bi-trash me-1"></i>Hapus
                                                        </button>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="fw-medium">Rp <?= number_format($item['harga'], 0, ',', '.') ?></td>
                                            <td>
                                                <div class="input-group input-group-sm" style="width: 100px;">
                                                    <button class="btn btn-outline-secondary" type="button" onclick="updateQty(<?= $item['id'] ?>, -1)">-</button>
                                                    <input type="text" class="form-control text-center bg-white" value="<?= $item['qty'] ?>" readonly>
                                                    <button class="btn btn-outline-secondary" type="button" onclick="updateQty(<?= $item['id'] ?>, 1)">+</button>
                                                </div>
                                            </td>
                                            <td class="text-end fw-bold pe-4">
                                                Rp <?= number_format($item['harga'] * $item['qty'], 0, ',', '.') ?>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="mt-3">
                         <a href="produk.php" class="btn btn-outline-dark">
                            <i class="bi bi-arrow-left me-2"></i>Lanjut Belanja
                        </a>
                    </div>
                </div>

                <!-- Cart Summary -->
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm rounded-3">
                        <div class="card-header bg-white border-bottom py-3">
                             <h5 class="mb-0 fw-bold">Ringkasan Pesanan</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Total Harga (<?= count($cart_items) ?> item)</span>
                                <span class="fw-bold">Rp <?= number_format($cart_total, 0, ',', '.') ?></span>
                            </div>
                            <hr class="my-3">
                            <div class="d-flex justify-content-between mb-4">
                                <span class="h5 fw-bold mb-0">Total Belanja</span>
                                <span class="h5 fw-bold text-primary mb-0">Rp <?= number_format($cart_total, 0, ',', '.') ?></span>
                            </div>
                            <div class="d-grid">
                                <a href="checkout.php" class="btn btn-primary btn-lg shadow-sm">
                                    Checkout <i class="bi bi-arrow-right ms-2"></i>
                                </a>
                            </div>
                            <div class="mt-3 text-center small text-muted">
                                <i class="bi bi-shield-lock me-1"></i>Transaksi Aman & Terenkripsi
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

<script>
function updateQty(cartId, change) {
    // Find current qty
    const btn = event.target;
    const input = btn.parentElement.querySelector('input');
    let newQty = parseInt(input.value) + change;
    
    if(newQty < 1) return;

    // Send AJAX
    fetch('update_cart.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `id_keranjang=${cartId}&qty=${newQty}`
    })
    .then(res => res.json())
    .then(data => {
        if(data.status) {
            location.reload();
        } else {
            Swal.fire('Gagal', data.message, 'error');
        }
    });
}

function removeFromCart(cartId) {
    Swal.fire({
        title: 'Hapus Item?',
        text: "Item ini akan dihapus dari keranjang.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Ya, Hapus',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch('remove_from_cart.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `id_keranjang=${cartId}`
            })
            .then(res => res.json())
            .then(data => {
                if(data.status) {
                    Swal.fire('Berhasil', 'Item dihapus', 'success').then(() => location.reload());
                } else {
                    Swal.fire('Gagal', data.message, 'error');
                }
            });
        }
    })
}
</script>

<?php
include 'footer.php';
?>