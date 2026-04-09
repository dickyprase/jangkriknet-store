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

// Check if request is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'status' => false,
        'message' => 'Invalid request method'
    ]);
    exit;
}

// Get product ID and quantity
$id_produk = isset($_POST['id_produk']) ? (int)$_POST['id_produk'] : 0;
$qty = isset($_POST['qty']) ? (int)$_POST['qty'] : 1;

// Validate input
if ($id_produk <= 0 || $qty <= 0) {
    echo json_encode([
        'status' => false,
        'message' => 'Invalid product ID or quantity'
    ]);
    exit;
}

// Add to cart
$result = add_to_cart($_SESSION['user']['id'], $id_produk, $qty);

// Return JSON response
echo json_encode($result); 