<?php
require_once 'config/function.php';
?>

<!-- Footer -->
<footer class="py-5 bg-dark text-white mt-5">
    <div class="container">
        <div class="row g-4">
            <!-- About Section -->
            <div class="col-lg-4 col-md-6 mb-4 mb-md-0">
                <h5 class="text-white text-uppercase mb-4 fw-bold"><?= $app_name ?></h5>
                <p class="text-secondary">
                    Menyediakan berbagai alat jaringan berkualitas tinggi untuk kebutuhan rumah dan bisnis Anda. Temukan solusi konektivitas terbaik di sini.
                </p>
                <div class="mt-4">
                    <a href="#" class="btn btn-outline-light btn-sm rounded-circle m-1" style="width: 35px; height: 35px;"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="btn btn-outline-light btn-sm rounded-circle m-1" style="width: 35px; height: 35px;"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="btn btn-outline-light btn-sm rounded-circle m-1" style="width: 35px; height: 35px;"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="btn btn-outline-light btn-sm rounded-circle m-1" style="width: 35px; height: 35px;"><i class="fab fa-whatsapp"></i></a>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="col-lg-2 col-md-6 mb-4 mb-md-0">
                <h5 class="text-white text-uppercase mb-4 fw-bold">Tautan</h5>
                <ul class="list-unstyled mb-0">
                    <li class="mb-2">
                        <a href="index.php" class="text-secondary text-decoration-none hover-white">Beranda</a>
                    </li>
                    <li class="mb-2">
                        <a href="produk.php" class="text-secondary text-decoration-none hover-white">Produk</a>
                    </li>
                    <li class="mb-2">
                        <a href="tentang.php" class="text-secondary text-decoration-none hover-white">Tentang Kami</a>
                    </li>
                    <li class="mb-2">
                        <a href="konfirmasi.php" class="text-secondary text-decoration-none hover-white">Konfirmasi Pembayaran</a>
                    </li>
                </ul>
            </div>

            <!-- Contact Section -->
            <div class="col-lg-3 col-md-6 mb-4 mb-md-0">
                <h5 class="text-white text-uppercase mb-4 fw-bold">Hubungi Kami</h5>
                <ul class="list-unstyled mb-0 text-secondary">
                    <li class="mb-2">
                        <i class="fas fa-map-marker-alt me-2 text-primary"></i> Jl. Raya Sidoarjo No. 123
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-envelope me-2 text-primary"></i> info@jangkriknet.com
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-phone me-2 text-primary"></i> +62 812 3456 7890
                    </li>
                </ul>
            </div>
            
            <!-- Payment Methods -->
            <div class="col-lg-3 col-md-6 mb-4 mb-md-0">
                <h5 class="text-white text-uppercase mb-4 fw-bold">Pembayaran</h5>
                <div class="d-flex gap-2 flex-wrap">
                    <div class="bg-white p-2 rounded d-flex align-items-center justify-content-center" style="width: 60px; height: 40px;">
                        <span class="fw-bold text-dark small">BCA</span>
                    </div>
                    <div class="bg-white p-2 rounded d-flex align-items-center justify-content-center" style="width: 60px; height: 40px;">
                        <span class="fw-bold text-dark small">BRI</span>
                    </div>
                    <div class="bg-white p-2 rounded d-flex align-items-center justify-content-center" style="width: 60px; height: 40px;">
                        <span class="fw-bold text-dark small">MANDIRI</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="text-center p-3 mt-4 border-top border-secondary text-secondary small">
        © <?= date('Y') ?> Copyright: <strong class="text-white"><?= $app_name ?></strong>
    </div>
</footer>

<!-- Bootstrap core JS-->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Custom JS -->
<script>
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })

    // Global Add to Cart Function
    function addToCart(productId, qty = 1, event = null) {
        if(event) {
            // Prevent default form submission or link click if event is passed
            event.preventDefault();
            
            // Show loading on the button
            const btn = event.currentTarget;
            const originalContent = btn.innerHTML;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>';
            btn.disabled = true;
            
            // Perform AJAX
            performAddToCart(productId, qty, () => {
                btn.innerHTML = originalContent;
                btn.disabled = false;
            });
        } else {
            // Just perform AJAX
            performAddToCart(productId, qty);
        }
    }

    function performAddToCart(productId, qty, callback = null) {
        const formData = new FormData();
        formData.append('id_produk', productId);
        formData.append('qty', qty);

        fetch('add_to_cart.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: data.message,
                    showConfirmButton: false,
                    timer: 1500,
                    toast: true,
                    position: 'top-end'
                });
                updateCartCount();
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: data.message,
                    confirmButtonColor: '#4f46e5'
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Terjadi kesalahan sistem',
                confirmButtonColor: '#4f46e5'
            });
        })
        .finally(() => {
            if(callback) callback();
        });
    }

    function updateCartCount() {
        fetch('get_cart_count.php')
            .then(response => response.json())
            .then(data => {
                if (data.status) {
                    const badges = document.querySelectorAll('.cart-badge');
                    badges.forEach(badge => {
                        badge.textContent = data.count;
                        badge.style.display = data.count > 0 ? 'inline-block' : 'none';
                    });
                }
            })
            .catch(error => console.error('Error updating cart count:', error));
    }

    // Update cart count on load
    document.addEventListener('DOMContentLoaded', updateCartCount);
</script>

</body>
</html>