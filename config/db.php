<?php


// Konfigurasi Database
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'myrise'; // Menggunakan nama database yang benar

// Membuat koneksi
$conn = mysqli_connect($host, $username, $password, $database);

// Cek koneksi
if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

// Set karakter encoding
if (!mysqli_set_charset($conn, "utf8")) {
    die("Error setting charset: " . mysqli_error($conn));
}


?> 