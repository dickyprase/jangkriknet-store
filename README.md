========================================================================
             JANGKRIKNET STORE - SOURCE CODE & DOKUMENTASI
========================================================================

Terima kasih telah menggunakan aplikasi JangkrikNet Store.
Berikut adalah panduan instalasi dan penggunaan aplikasi ini.

1. PERSIAPAN SISTEM
------------------------------------------------------------------------
Pastikan komputer Anda sudah terinstall aplikasi XAMPP (Apache & MySQL).
- PHP Version: 8.0 ke atas disarankan.
- Database: MySQL / MariaDB.

2. IMPORT DATABASE
------------------------------------------------------------------------
1. Buka XAMPP Control Panel, nyalakan module Apache dan MySQL.
2. Buka browser, akses: http://localhost/phpmyadmin
3. Buat database baru dengan nama: `myrise`
4. Pilih database `myrise` yang baru dibuat.
5. Klik menu "Import" di bagian atas.
6. Klik "Choose File", lalu cari file di folder: `db/myrise.sql`
7. Klik tombol "Import" atau "Go" di bagian bawah.

3. KONFIGURASI KONEKSI (Jika Diperlukan)
------------------------------------------------------------------------
Konfigurasi database ada di file: `config/db.php`
Default setting:
- Host: localhost
- User: root
- Pass: (kosong)
- DB: myrise

4. CARA MENJALANKAN APLIKASI
------------------------------------------------------------------------
1. Pastikan folder project bernama `myrise` berada di dalam `htdocs`.
   Contoh path: `C:\xampp\htdocs\myrise`
2. Buka browser dan akses:
   http://localhost/myrise

5. AKUN LOGIN DEMO
------------------------------------------------------------------------
Berikut adalah akun default yang tersimpan di database (password ter-enkripsi).
Jika password dibawah tidak bisa, silakan Register akun baru untuk User,
atau reset password via database.

[ADMIN]
Username: admin
Password: 123
Role: Full akses ke halaman Admin Dashboard

[USER PELANGGAN]
Username: dicky
Password: 123
Role: Akses ke Katalog & Keranjang Belanja

6. FITUR UTAMA APLIKASI
------------------------------------------------------------------------
[FRONTEND - PELANGGAN]
- Landing Page Modern: Halaman depan dengan animasi ringan & responsif.
- Katalog Produk: Pencarian, kategori, dan detail produk lengkap.
- Keranjang Belanja: Kelola belanjaan dengan mudah.
- Checkout System: Input alamat pengiriman & upload bukti pembayaran.
- User Dashboard: Riwayat pesanan, status pengiriman (Resi), edit profil.
- Cetak Invoice: Download nota pembelian mandiri.

[BACKEND - ADMIN]
- Dashboard Analitik: Grafik Pendapatan & Transaksi Real-time (Line/Doughnut Chart).
- Manajemen Pesanan: Proses pesanan (Terima/Tolak), Input No. Resi.
- Manajemen Produk: Tambah/Edit/Hapus produk & kategori.
- Laporan Keuangan: Filter laporan per tanggal & Export ke PDF.
- Manajemen User: Kelola data pelanggan.

7. CATATAN PENGEMBANG
------------------------------------------------------------------------
- Aplikasi ini dibangun menggunakan PHP Native & MySQL.
- Frontend menggunakan Bootstrap 5 & Custom CSS (Lightweight).
- Folder `admin/` berisi semua logika untuk halaman administrator.
- Folder `config/` berisi koneksi database & fungsi helper.

========================================================================
Copyright © 2025 KinusDigital
========================================================================
