<?php
include 'config/function.php';
session_start();

// Check if user is logged in
if (!isset($_SESSION['user'])) {
    echo "<script>alert('Silakan login terlebih dahulu!'); window.location = 'login.php';</script>";
    exit;
}

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo "<script>alert('Invalid request!'); window.location = 'index.php';</script>";
    exit;
}

// Validate required fields
if (!isset($_POST['id_bank']) || empty($_POST['id_bank'])) {
    echo "<script>alert('Silakan pilih metode pembayaran!'); window.location = 'checkout.php';</script>";
    exit;
}

if (!isset($_FILES['bukti_pembayaran']) || $_FILES['bukti_pembayaran']['error'] === UPLOAD_ERR_NO_FILE) {
    echo "<script>alert('Silakan upload bukti pembayaran!'); window.location = 'checkout.php';</script>";
    exit;
}

// Check if this is a direct purchase from detail.php
if (isset($_POST['id_produk']) && isset($_POST['jumlah'])) {
    $id_produk = (int)$_POST['id_produk'];
    $jumlah = (int)$_POST['jumlah'];
    
    // Validate product and stock
    $produk = get_produk_by_id($id_produk);
    if (!$produk['status']) {
        echo "<script>alert('Produk tidak ditemukan!'); window.location = 'index.php';</script>";
        exit;
    }
    
    if ($produk['data']['stock'] < $jumlah) {
        echo "<script>alert('Stok tidak mencukupi!'); window.location = 'detail.php?id=" . $id_produk . "';</script>";
        exit;
    }
    
    // Create order directly
    $total_harga = $produk['data']['harga'] * $jumlah;
    $kode_invoice = generate_invoice_code();
    $tanggal = date('Y-m-d H:i:s');
    
    // Start transaction
    global $conn;
    $conn->begin_transaction();
    
    try {
        // Create order
        $query = "INSERT INTO pesanan (id_user, kode_invoice, total_harga, status, tanggal) 
                  VALUES (" . $_SESSION['user']['id'] . ", '$kode_invoice', $total_harga, 'Pending', '$tanggal')";
        $result = execute_query($query);
        
        if (!$result['status']) {
            throw new Exception('Gagal membuat pesanan: ' . $result['message']);
        }
        
        $id_pesanan = $result['insert_id'];
        
        // Add order detail
        $query = "INSERT INTO detail_pesanan (id_pesanan, id_produk, qty, harga) 
                  VALUES ($id_pesanan, $id_produk, $jumlah, " . $produk['data']['harga'] . ")";
        $result = execute_query($query);
        
        if (!$result['status']) {
            throw new Exception('Gagal menambahkan detail pesanan: ' . $result['message']);
        }
        
        // Update product stock
        $query = "UPDATE produk SET stock = stock - $jumlah WHERE id = $id_produk";
        $result = execute_query($query);
        
        if (!$result['status']) {
            throw new Exception('Gagal mengupdate stok produk: ' . $result['message']);
        }
        
        // Create payment entry
        $query = "INSERT INTO pembayaran (id_pesanan, nominal_pembayaran, tanggal, status, metode_pembayaran) 
                  VALUES ($id_pesanan, $total_harga, '$tanggal', 'pending', 'transfer')";
        $result = execute_query($query);
        
        if (!$result['status']) {
            throw new Exception('Gagal membuat entri pembayaran: ' . $result['message']);
        }
        
        // Upload payment proof
        $upload_result = upload_payment_proof($id_pesanan, $_FILES);
        if (!$upload_result['status']) {
            throw new Exception('Gagal upload bukti pembayaran: ' . $upload_result['message']);
        }
        
        // Commit transaction
        $conn->commit();
        
        // Redirect to order page
        echo "<script>alert('Pesanan berhasil dibuat! Silakan tunggu konfirmasi dari admin.'); window.location = 'pesanan.php';</script>";
        exit;
        
    } catch (Exception $e) {
        // Rollback transaction if error
        $conn->rollback();
        echo "<script>alert('" . $e->getMessage() . "'); window.location = 'detail.php?id=" . $id_produk . "';</script>";
        exit;
    }
} else {
    // Process checkout from cart
    $cart_items = get_cart($_SESSION['user']['id']);
    if (!$cart_items['status'] || empty($cart_items['data'])) {
        echo "<script>alert('Keranjang kosong!'); window.location = 'keranjang.php';</script>";
        exit;
    }
    
    // Create order
    $result = create_order($_SESSION['user']['id'], 'transfer');
    if (!$result['status']) {
        echo "<script>alert('Gagal membuat pesanan: " . $result['message'] . "'); window.location = 'checkout.php';</script>";
        exit;
    }
    
    $id_pesanan = $result['data']['id_pesanan'];
    
    // Upload payment proof
    $upload_result = upload_payment_proof($id_pesanan, $_FILES);
    if (!$upload_result['status']) {
        echo "<script>alert('Gagal upload bukti pembayaran: " . $upload_result['message'] . "'); window.location = 'checkout.php';</script>";
        exit;
    }
    
    // Redirect to order detail page
    echo "<script>alert('Pesanan berhasil dibuat! Silakan tunggu konfirmasi dari admin.'); window.location = 'pesanan.php';</script>";
    exit;
} 