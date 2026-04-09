<?php
include 'header.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $order_id = $_POST['order_id'];
    $status = $_POST['status'];
    
    $result = update_order_status($order_id, $status);
    
    if ($result['status']) {
        echo "<script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: 'Status pesanan berhasil diperbarui',
                showConfirmButton: false,
                timer: 1500
            }).then(function() {
                window.location = 'pesanan.php';
            });
        </script>";
    } else {
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: '" . addslashes($result['message']) . "',
                showConfirmButton: false,
                timer: 1500
            });
        </script>";
    }
} else {
    header('Location: pesanan.php');
    exit();
} 