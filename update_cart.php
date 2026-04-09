<?php
include 'config/function.php';
session_start();

// Check if user is logged in
if (!isset($_SESSION['user'])) {
    echo json_encode([
        'status' => false,
        'message' => 'Silakan login terlebih dahulu'
    ]);
    exit;
}

// Check if request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'status' => false,
        'message' => 'Invalid request method'
    ]);
    exit;
}

// Get cart item ID and quantity
$id_keranjang = isset($_POST['id_keranjang']) ? (int)$_POST['id_keranjang'] : 0;
$qty = isset($_POST['qty']) ? (int)$_POST['qty'] : 0;

if ($id_keranjang <= 0) {
    echo json_encode([
        'status' => false,
        'message' => 'ID keranjang tidak valid'
    ]);
    exit;
}

if ($qty <= 0) {
    echo json_encode([
        'status' => false,
        'message' => 'Jumlah produk tidak valid'
    ]);
    exit;
}

// Update cart quantity
$result = update_cart_qty($id_keranjang, $qty);

// Return JSON response
echo json_encode($result); 