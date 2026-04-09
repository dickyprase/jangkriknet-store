<?php
include 'config/function.php';
session_start();

// Check if user is logged in
if (!isset($_SESSION['user'])) {
    echo json_encode([
        'status' => false,
        'message' => 'User not logged in',
        'count' => 0
    ]);
    exit;
}

// Get cart items
$cart_items = get_cart($_SESSION['user']['id']);

// Calculate total items
$total_items = 0;
if ($cart_items['status'] && !empty($cart_items['data'])) {
    foreach ($cart_items['data'] as $item) {
        $total_items += $item['qty'];
    }
}

// Return JSON response
echo json_encode([
    'status' => true,
    'message' => 'Cart count retrieved successfully',
    'count' => $total_items
]); 