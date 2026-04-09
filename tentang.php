<?php
include 'header.php';
$tentang = get_tentang();
?>

<!-- Header-->
<header style="background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)),url('admin/assets/img/bg-jaringan.jpg') no-repeat center center fixed; background-size: cover; padding: 15vh 0;">
    <div class="container px-4 px-lg-5 my-5">
        <div class="text-center text-white">
            <h1 class="display-3 fw-bolder"><?= $tentang['data']['judul'] ?></h1>
            <p class="lead fw-normal mb-0">Temukan informasi lengkap tentang kami dan cara menghubungi kami</p>
        </div>
    </div>
</header>

<!-- Main Content -->
<div class="container my-5">
    <div class="row g-4">
        <!-- About Section -->
        <div class="col-lg-6">
            <div class="card shadow-sm h-100">
                <div class="card-body p-4">
                    <h2 class="card-title h3 mb-4 text-primary">Tentang Kami</h2>
                    <p class="card-text mb-4"><?= $tentang['data']['deskripsi'] ?></p>
                    
                    <div class="contact-info mt-4">
                        <h3 class="h4 mb-4 text-primary">Kontak Kami</h3>
                        <div class="d-flex align-items-center mb-3">
                            <div class="icon-box bg-primary text-white rounded-circle p-3 me-3">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div>
                                <h5 class="mb-1">Alamat</h5>
                                <p class="mb-0"><?= $tentang['data']['alamat'] ?></p>
                            </div>
                        </div>
                        <div class="d-flex align-items-center mb-3">
                            <div class="icon-box bg-primary text-white rounded-circle p-3 me-3">
                                <i class="fas fa-phone"></i>
                            </div>
                            <div>
                                <h5 class="mb-1">Telepon</h5>
                                <p class="mb-0"><?= $tentang['data']['telepon'] ?></p>
                            </div>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="icon-box bg-primary text-white rounded-circle p-3 me-3">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div>
                                <h5 class="mb-1">Email</h5>
                                <p class="mb-0"><?= $tentang['data']['email'] ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Map Section -->
        <div class="col-lg-6">
            <div class="card shadow-sm h-100">
                <div class="card-body p-4">
                    <h3 class="card-title h4 mb-4 text-primary">Lokasi Kami</h3>
                    <div class="map-container rounded overflow-hidden">
                        <?= $tentang['data']['maps_embed'] ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.icon-box {
    width: 45px;
    height: 45px;
    display: flex;
    align-items: center;
    justify-content: center;
}
.map-container {
    position: relative;
    padding-bottom: 56.25%; /* 16:9 Aspect Ratio */
    height: 0;
    overflow: hidden;
}
.map-container iframe {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    border: 0;
}
</style>

<?php include 'footer.php'; ?>