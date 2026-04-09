<?php
require_once '../config/function.php';

if (!isset($_POST['id']) || !isset($_POST['status'])) {
    echo json_encode(['status' => false, 'message' => 'Parameter tidak lengkap']);
    exit;
}

$id = (int)$_POST['id'];
$status = $_POST['status'];

$result = update_payment_status($id, $status);
echo json_encode($result); 