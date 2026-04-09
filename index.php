<?php
require_once 'config/function.php';
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Welcome to <?= $app_name ?></title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --primary-color: #4f46e5;
            --secondary-color: #ec4899;
            --text-dark: #1f2937;
            --text-light: #6b7280;
            --bg-light: #f9fafb;
        }
        
        body {
            font-family: 'Outfit', sans-serif;
            overflow-x: hidden;
            background-color: #fff;
        }

        /* Hero Section */
        .hero-section {
            min-height: 100vh;
            display: flex;
            align-items: center;
            position: relative;
            background: linear-gradient(135deg, #fdfbfb 0%, #ebedee 100%);
            overflow: hidden;
        }

        .hero-shape {
            position: absolute;
            background: linear-gradient(45deg, var(--primary-color), var(--secondary-color));
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.15;
            z-index: 0;
            animation: float 10s infinite ease-in-out;
        }

        .shape-1 { width: 400px; height: 400px; top: -100px; right: -100px; }
        .shape-2 { width: 300px; height: 300px; bottom: -50px; left: -50px; animation-delay: 2s; }

        @keyframes float {
            0% { transform: translateY(0) scale(1); }
            50% { transform: translateY(20px) scale(1.1); }
            100% { transform: translateY(0) scale(1); }
        }

        .hero-content {
            position: relative;
            z-index: 1;
        }

        .hero-title {
            font-size: 3.5rem;
            font-weight: 800;
            line-height: 1.2;
            background: linear-gradient(to right, var(--primary-color), var(--secondary-color));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 1.5rem;
        }

        .hero-subtitle {
            font-size: 1.25rem;
            color: var(--text-light);
            margin-bottom: 2.5rem;
            font-weight: 300;
            max-width: 600px;
        }

        .btn-custom-primary {
            background: var(--primary-color);
            color: white;
            padding: 15px 40px;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s ease;
            border: none;
            box-shadow: 0 10px 20px rgba(79, 70, 229, 0.2);
        }

        .btn-custom-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 30px rgba(79, 70, 229, 0.3);
            background: #4338ca;
            color: white;
        }

        .btn-custom-outline {
            border: 2px solid var(--text-dark);
            color: var(--text-dark);
            padding: 13px 38px;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s ease;
            background: transparent;
            margin-left: 15px;
        }

        .btn-custom-outline:hover {
            background: var(--text-dark);
            color: white;
            transform: translateY(-3px);
        }

        /* Features Section */
        .feature-card {
            background: white;
            padding: 2rem;
            border-radius: 1.5rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            transition: all 0.3s ease;
            border: 1px solid rgba(0,0,0,0.03);
            height: 100%;
        }

        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.08);
            border-color: rgba(79, 70, 229, 0.1);
        }

        .feature-icon {
            width: 60px;
            height: 60px;
            background: rgba(79, 70, 229, 0.1);
            color: var(--primary-color);
            border-radius: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 1.5rem;
        }

        /* Mockup Image */
        .hero-image-wrapper {
            position: relative;
        }
        
        .floating-card {
            position: absolute;
            background: white;
            padding: 15px;
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            display: flex;
            align-items: center;
            gap: 15px;
            animation: float-card 6s ease-in-out infinite;
            z-index: 2;
        }

        .card-1 { top: 10%; left: -20px; animation-delay: 0s; }
        .card-2 { bottom: 15%; right: -20px; animation-delay: 2s; }

        @keyframes float-card {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-15px); }
        }

        .hero-img {
            border-radius: 20px;
            box-shadow: 0 25px 50px rgba(0,0,0,0.15);
            transform: perspective(1000px) rotateY(-5deg) rotateX(5deg);
            transition: transform 0.5s ease;
        }
        
        .hero-img:hover {
            transform: perspective(1000px) rotateY(0) rotateX(0);
        }

        /* Responsive */
        @media (max-width: 991px) {
            .hero-title { font-size: 2.5rem; }
            .hero-section { padding-top: 50px; height: auto; min-height: 100vh; }
            .hero-image-wrapper { margin-top: 50px; }
            .btn-custom-outline { margin-left: 0; margin-top: 15px; display: block; width: 100%; }
            .btn-custom-primary { display: block; width: 100%; }
            .d-flex-mobile { flex-direction: column; }
        }
    </style>
</head>
<body>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="hero-shape shape-1"></div>
        <div class="hero-shape shape-2"></div>

        <div class="container position-relative z-1">
            <div class="row align-items-center">
                <div class="col-lg-6" data-aos="fade-up" data-aos-duration="1000">
                    <div class="hero-content">
                        <span class="badge bg-light text-primary mb-3 px-3 py-2 rounded-pill fw-bold border border-primary border-opacity-10">
                            <i class="fas fa-rocket me-2"></i>Platform E-Commerce Terpercaya
                        </span>
                        <h1 class="hero-title">Belanja Cerdas,<br>Hidup Lebih Praktis.</h1>
                        <p class="hero-subtitle">
                            Temukan ribuan produk pilihan dengan kualitas terbaik. Nikmati pengalaman belanja online yang aman, cepat, dan menyenangkan bersama <?= $app_name ?>.
                        </p>
                        
                        <div class="d-flex d-flex-mobile">
                            <a href="home.php" class="btn btn-custom-primary">
                                <i class="fas fa-shopping-bag me-2"></i>Mulai Belanja
                            </a>
                            <?php if (isset($_SESSION['user'])): ?>
                                <a href="home.php" class="btn btn-custom-outline">
                                    <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                                </a>
                            <?php else: ?>
                                <a href="login.php" class="btn btn-custom-outline">
                                    <i class="fas fa-sign-in-alt me-2"></i>Masuk Akun
                                </a>
                            <?php endif; ?>
                        </div>
                        
                        <div class="mt-5 d-flex gap-4">
                            <div class="d-flex align-items-center">
                                <h3 class="fw-bold mb-0 me-2 text-dark">5k+</h3>
                                <small class="text-muted lh-1">Produk<br>Terjual</small>
                            </div>
                            <div class="border-start border-2 h-100 mx-2"></div>
                            <div class="d-flex align-items-center">
                                <h3 class="fw-bold mb-0 me-2 text-dark">1k+</h3>
                                <small class="text-muted lh-1">Pelanggan<br>Puas</small>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-6" data-aos="fade-left" data-aos-duration="1000" data-aos-delay="200">
                    <div class="hero-image-wrapper p-4">
                        <!-- Floating Elements -->
                        <div class="floating-card card-1">
                            <div class="bg-success bg-opacity-10 p-2 rounded-circle text-success">
                                <i class="fas fa-check"></i>
                            </div>
                            <div>
                                <h6 class="mb-0 fw-bold">Pembayaran Aman</h6>
                                <small class="text-muted">Terverifikasi</small>
                            </div>
                        </div>
                        
                        <div class="floating-card card-2">
                             <div class="bg-primary bg-opacity-10 p-2 rounded-circle text-primary">
                                <i class="fas fa-shipping-fast"></i>
                            </div>
                            <div>
                                <h6 class="mb-0 fw-bold">Pengiriman Cepat</h6>
                                <small class="text-muted">Express Delivery</small>
                            </div>
                        </div>

                        <!-- Main Image Placeholder (You can replace src with actual screenshot) -->
                        <img src="https://images.unsplash.com/photo-1483985988355-763728e1935b?ixlib=rb-4.0.3&auto=format&fit=crop&w=1470&q=80" alt="Shopping Experience" class="img-fluid hero-img">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-5 bg-white mb-5">
        <div class="container py-5">
            <div class="row g-4">
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="0">
                    <div class="feature-card text-center">
                        <div class="feature-icon mx-auto">
                            <i class="fas fa-truck-fast"></i>
                        </div>
                        <h4 class="fw-bold mb-3">Pengiriman Cepat</h4>
                        <p class="text-muted">Kami bekerja sama dengan logistik terbaik untuk memastikan barang sampai tepat waktu.</p>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="feature-card text-center">
                       <div class="feature-icon mx-auto">
                            <i class="fas fa-shield-halved"></i>
                        </div>
                        <h4 class="fw-bold mb-3">Jaminan Keamanan</h4>
                        <p class="text-muted">Transaksi Anda dilindungi dengan sistem keamanan enkripsi terkini.</p>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="feature-card text-center">
                        <div class="feature-icon mx-auto">
                            <i class="fas fa-headset"></i>
                        </div>
                        <h4 class="fw-bold mb-3">Layanan 24/7</h4>
                        <p class="text-muted">Tim support kami siap membantu kendala Anda kapan saja, di mana saja.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Scripts -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            once: true,
            offset: 100,
            duration: 800,
        });
    </script>
</body>
</html>
