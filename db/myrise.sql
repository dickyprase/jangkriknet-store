-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 16, 2025 at 06:39 PM
-- Server version: 8.0.30
-- PHP Version: 8.2.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

SET FOREIGN_KEY_CHECKS = 0;

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;



-- --------------------------------------------------------

--
-- Table structure for table `bank`
--

DROP TABLE IF EXISTS `bank`;
CREATE TABLE `bank` (
  `id` int NOT NULL AUTO_INCREMENT,
  `logo` varchar(255) DEFAULT NULL,
  `nama_bank` varchar(128) DEFAULT NULL,
  `no_rekening` varchar(128) DEFAULT NULL,
  `atas_nama` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bank`
--

INSERT INTO `bank` (`id`, `logo`, `nama_bank`, `no_rekening`, `atas_nama`) VALUES
(1, '682605e4679fc7.49749815_logo-bri-1_43.png', 'BRI', '11100391', 'JangkrikNet'),
(2, '682605f2f2ac83.70741707_lg-BCA-1.png', 'BCA', '01331889', 'JangkrikNet');

-- --------------------------------------------------------

--
-- Table structure for table `kategori`
--

DROP TABLE IF EXISTS `kategori`;
CREATE TABLE `kategori` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nama` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kategori`
--

INSERT INTO `kategori` (`id`, `nama`) VALUES
(2, 'Router'),
(3, 'Kabel LAN'),
(4, 'Perkakas');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nama` varchar(128) DEFAULT NULL,
  `username` varchar(128) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `email` varchar(128) DEFAULT NULL,
  `alamat` varchar(128) DEFAULT NULL,
  `no_telp` varchar(20) DEFAULT NULL,
  `role` enum('admin','user') DEFAULT 'user',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `nama`, `username`, `password`, `email`, `alamat`, `no_telp`, `role`) VALUES
(1, 'Admin', 'admin', '$2y$10$leLUI5N6RHNSAT6K11zybel5FNatnbU8FPLJ0C4tCsrGYNjAO1rX.', 'admin@gmail.com', 'sidoarjo', '08931938', 'admin'),
(2, 'dicky', 'dicky', '$2y$10$0K7iDg3/eIx0TQknH4cT1OI72u2QZwhYGwZYuF5Yu7F36oo9eGsvm', 'dicky@gmail.com', 'sidoarjo\r\n', '089898', 'user'),
(3, 'gimull', 'gimul', '$2y$10$n8BvyEhMqNR1ACcTFXOHIuFu8bPfzImBXHu1TvMdlk4EMkteJpzj2', 'gimul@gmail.com', 'sidoarjo', '08934387', 'user'),
(4, 'admin2', 'admin2', '$2y$10$sK3PbvKyi/eu/8UdYMJcg.k7B8Nla8ZzpZQzPR5nE4QnGT2XT4h.q', 'admin2@gmail.com', 'sidoarjo', '081931938', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `produk`
--

DROP TABLE IF EXISTS `produk`;
CREATE TABLE `produk` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_kategori` int DEFAULT NULL,
  `nama` varchar(128) DEFAULT NULL,
  `gambar` varchar(255) DEFAULT NULL,
  `deskripsi` text,
  `harga` decimal(10,2) DEFAULT NULL,
  `stock` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_kategori` (`id_kategori`),
  CONSTRAINT `produk_ibfk_1` FOREIGN KEY (`id_kategori`) REFERENCES `kategori` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `produk`
--

INSERT INTO `produk` (`id`, `id_kategori`, `nama`, `gambar`, `deskripsi`, `harga`, `stock`) VALUES
(4, 2, 'TP-Link Routerr', '682603286f4ce1.78212475_0a6f988f-e7f4-4325-8c21-8df39d5b98dc.jpeg', 'Router Wi-Fi 6 Dual Band AC2100 menawarkan kecepatan internet tinggi dan konektivitas yang lebih stabil untuk kebutuhan rumah atau kantor kecil. Dengan dukungan teknologi Wi-Fi 6 (802.11ax), router ini mampu menyediakan kecepatan hingga 2100 Mbps, mengoptimalkan bandwidth untuk perangkat yang lebih banyak tanpa mengorbankan performa. Dilengkapi dengan fitur dual-band, router ini beroperasi pada dua frekuensi (2,4 GHz dan 5 GHz) untuk memberikan pilihan koneksi yang lebih cepat dan lebih efisien. Fitur keamanan seperti WPA3 dan firewall memastikan perlindungan data dan privasi pengguna, sementara pengaturan yang mudah membuatnya ideal untuk pengguna rumahan atau bisnis kecil.\\r\\n\\r\\nFitur Utama:\\r\\n\\r\\nKecepatan hingga 2100 Mbps untuk koneksi yang cepat dan stabil\\r\\n\\r\\nDual-band (2,4 GHz dan 5 GHz) untuk koneksi yang fleksibel dan optimal\\r\\n\\r\\nMendukung Wi-Fi 6 untuk performa lebih baik pada jaringan dengan banyak perangkat\\r\\n\\r\\nFitur keamanan WPA3 dan firewall untuk perlindungan data\\r\\n\\r\\nPengaturan mudah dan kompatibel dengan berbagai perangkat rumah pintar', '120000.00', 2),
(5, 2, 'Ruijee Router', '68260442f22ea8.68689945_images.jpeg', 'Router Gigabit Mesh Wi-Fi 6 adalah solusi jaringan canggih untuk rumah atau kantor besar yang membutuhkan jangkauan luas dan kecepatan tinggi. Dengan teknologi mesh, router ini menghubungkan beberapa perangkat untuk menciptakan jaringan yang lebih stabil dan menjangkau area yang lebih luas tanpa gangguan sinyal. Dengan kecepatan hingga 3000 Mbps dan dukungan Wi-Fi 6, router ini menawarkan performa tinggi bahkan pada perangkat yang banyak terhubung secara simultan. Fitur QoS (Quality of Service) memungkinkan pengguna untuk memprioritaskan aplikasi atau perangkat tertentu, seperti video streaming atau gaming, agar mendapatkan bandwidth yang lebih besar. Dengan pengaturan yang mudah dan antarmuka aplikasi yang intuitif, router ini cocok untuk rumah atau bisnis yang membutuhkan performa jaringan terbaik.\\r\\n\\r\\nFitur Utama:\\r\\n\\r\\nKecepatan hingga 3000 Mbps untuk pengalaman internet yang super cepat\\r\\n\\r\\nTeknologi Mesh untuk jangkauan Wi-Fi yang lebih luas dan koneksi tanpa gangguan\\r\\n\\r\\nDukungan Wi-Fi 6 untuk konektivitas lebih cepat dan efisien pada perangkat banyak\\r\\n\\r\\nFitur QoS untuk memprioritaskan aplikasi dan perangkat tertentu\\r\\n\\r\\nPengaturan mudah melalui aplikasi dengan antarmuka intuitif', '119000.00', 0),
(6, 4, 'Tang Crimping 1', '68260f6fa85dd5.57702426_e9983115036d6640aecc153a75305174.jpeg', 'Tang krimping manual premium ini dirancang untuk memberikan kinerja maksimal dalam proses penyambungan kabel dengan konektor terminal. Dengan pegangan ergonomis yang nyaman dan konstruksi yang tahan lama, tang ini cocok digunakan untuk keperluan sehari-hari maupun untuk pekerjaan profesional. Terbuat dari material baja berkualitas tinggi, tang krimping ini memberikan daya cengkeram yang kuat dan presisi tinggi, memastikan sambungan yang rapat dan aman antara kabel dan konektor. Dengan desain kompak dan mudah digunakan, tang krimping manual premium ini ideal untuk pekerjaan instalasi listrik, perbaikan elektronik, dan proyek DIY.\\r\\n\\r\\nFitur Utama:\\r\\n\\r\\nPegangan ergonomis untuk kenyamanan penggunaan\\r\\n\\r\\nDaya cengkeram yang kuat untuk hasil krimping presisi\\r\\n\\r\\nTerbuat dari material baja anti karat untuk ketahanan yang lebih lama\\r\\n\\r\\n', '12000.00', 9),
(7, 4, 'Tang Crimping 2', '68260f9c828980.84712206_images (1).jpeg', 'Tang krimping hidraulik otomatis ini adalah alat canggih yang dirancang untuk memberikan kekuatan krimping yang lebih tinggi dengan sedikit usaha. Menggunakan sistem hidraulik, alat ini dapat mengerjakan krimping pada kabel dan konektor terminal secara efisien dan dengan tekanan yang merata. Dengan mekanisme otomatis, tang ini memudahkan pengguna untuk mencapai hasil yang konsisten dan profesional, tanpa memerlukan tenaga fisik yang besar. Ideal untuk penggunaan industri atau proyek dengan volume tinggi, tang krimping hidraulik ini memastikan sambungan kabel yang kuat dan aman, cocok untuk aplikasi listrik dan telekomunikasi.\\r\\n\\r\\nFitur Utama:\\r\\n\\r\\nSistem hidraulik untuk tekanan krimping yang lebih tinggi dan efisien\\r\\n\\r\\nProses krimping otomatis untuk hasil yang konsisten dan rapat\\r\\n\\r\\nCocok untuk kabel besar dan konektor terminal berukuran besar\\r\\n\\r\\nDirancang untuk penggunaan industri atau proyek dengan volume tinggi\\r\\n\\r\\nPegangan yang nyaman dan aman untuk penggunaan jangka panjang', '90000.00', 8),
(8, 3, 'Kabel LAN CAT6', '68260fec0c3e23.22719818_kabel-lan-cat6e-3m.jpg', 'Kabel LAN Cat 5e U/FTP adalah solusi kabel jaringan berkualitas tinggi yang dirancang untuk mendukung kecepatan transfer data hingga 1000 Mbps (Gigabit Ethernet). Kabel ini menggunakan teknologi U/FTP (Unshielded Foiled Twisted Pair), di mana setiap pasangan kabel dibungkus dengan pelindung foil untuk mengurangi interferensi elektromagnetik (EMI) dan meningkatkan kestabilan sinyal. Dengan konstruksi berkualitas tinggi, kabel ini ideal untuk instalasi jaringan rumah, kantor, atau gedung komersial, memberikan performa andal dan kecepatan jaringan yang optimal.\\r\\n\\r\\nFitur Utama:\\r\\n\\r\\nKecepatan transfer data hingga 1 Gbps untuk kebutuhan jaringan Gigabit Ethernet\\r\\n\\r\\nDilengkapi pelindung foil untuk mengurangi gangguan elektromagnetik (EMI)\\r\\n\\r\\nIdeal untuk penggunaan di rumah, kantor, dan lingkungan bisnis\\r\\n\\r\\nKabel fleksibel dan mudah dipasang di berbagai aplikasi jaringan\\r\\n\\r\\nMendukung protokol jaringan standar seperti 10/100/1000Base-T', '119000.00', 8),
(9, 3, 'Kabel LAN CAT4', '6826100d6225d0.88306151_id-11134207-7r98q-lmjsnkcerf2ff2.jpeg', 'Kabel LAN Cat 6 S/FTP adalah kabel jaringan berkinerja tinggi yang dirancang untuk memenuhi tuntutan jaringan dengan kecepatan transfer data yang lebih tinggi, hingga 10 Gbps pada jarak pendek. Menggunakan teknologi S/FTP (Shielded Foiled Twisted Pair), kabel ini memiliki lapisan pelindung ganda, yaitu pelindung foil di setiap pasangan kabel dan pelindung braiding untuk mengurangi interferensi eksternal dan memastikan transmisi data yang stabil dan cepat. Kabel ini ideal untuk penggunaan jaringan yang membutuhkan bandwidth tinggi, seperti instalasi server, pusat data, dan aplikasi video streaming.\\r\\n\\r\\nFitur Utama:\\r\\n\\r\\nMendukung kecepatan transfer data hingga 10 Gbps untuk jaringan berkecepatan tinggi\\r\\n\\r\\nLapisan pelindung ganda (foil dan braiding) untuk mengurangi interferensi dan gangguan elektromagnetik (EMI)\\r\\n\\r\\nCocok untuk aplikasi seperti video konferensi, gaming online, dan pusat data\\r\\n\\r\\nMampu menangani bandwidth tinggi dan memastikan transmisi data tanpa gangguan\\r\\n\\r\\nIdeal untuk jaringan komersial, kantor besar, dan infrastruktur TI skala besar', '93000.00', 7);

-- --------------------------------------------------------

--
-- Table structure for table `pesanan`
--

DROP TABLE IF EXISTS `pesanan`;
CREATE TABLE `pesanan` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_user` int DEFAULT NULL,
  `kode_invoice` varchar(6) DEFAULT NULL,
  `total_harga` decimal(10,2) DEFAULT NULL,
  `status` enum('Pending','Sukses','Ditolak','Proses Pengiriman') DEFAULT 'Pending',
  `tanggal` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `id_user` (`id_user`),
  CONSTRAINT `pesanan_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pesanan`
--

INSERT INTO `pesanan` (`id`, `id_user`, `kode_invoice`, `total_harga`, `status`, `tanggal`) VALUES
(1, 2, 'EFCKHJ', '357000.00', 'Sukses', '2025-05-30 23:36:32'),
(2, 2, 'BJ2U06', '93000.00', 'Sukses', '2025-05-15 23:36:32');

-- --------------------------------------------------------

--
-- Table structure for table `detail_pesanan`
--

DROP TABLE IF EXISTS `detail_pesanan`;
CREATE TABLE `detail_pesanan` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_pesanan` int DEFAULT NULL,
  `id_produk` int DEFAULT NULL,
  `qty` int DEFAULT NULL,
  `harga` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_pesanan` (`id_pesanan`),
  KEY `id_produk` (`id_produk`),
  CONSTRAINT `detail_pesanan_ibfk_1` FOREIGN KEY (`id_pesanan`) REFERENCES `pesanan` (`id`),
  CONSTRAINT `detail_pesanan_ibfk_2` FOREIGN KEY (`id_produk`) REFERENCES `produk` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `detail_pesanan`
--

INSERT INTO `detail_pesanan` (`id`, `id_pesanan`, `id_produk`, `qty`, `harga`) VALUES
(1, 1, 5, 2, '119000.00'),
(2, 1, 8, 1, '119000.00'),
(3, 2, 9, 1, '93000.00');

-- --------------------------------------------------------

--
-- Table structure for table `keranjang`
--

DROP TABLE IF EXISTS `keranjang`;
CREATE TABLE `keranjang` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_produk` int DEFAULT NULL,
  `id_user` int DEFAULT NULL,
  `qty` int DEFAULT NULL,
  `harga` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_user` (`id_user`,`id_produk`),
  KEY `id_produk` (`id_produk`),
  CONSTRAINT `keranjang_ibfk_1` FOREIGN KEY (`id_produk`) REFERENCES `produk` (`id`),
  CONSTRAINT `keranjang_ibfk_2` FOREIGN KEY (`id_user`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pembayaran`
--

DROP TABLE IF EXISTS `pembayaran`;
CREATE TABLE `pembayaran` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_pesanan` int DEFAULT NULL,
  `nominal_pembayaran` decimal(10,2) DEFAULT NULL,
  `bukti_pembayaran` varchar(255) DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `status` enum('valid','pending','rejected') DEFAULT 'pending',
  `metode_pembayaran` enum('transfer','COD','e-wallet') DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_pesanan` (`id_pesanan`),
  CONSTRAINT `pembayaran_ibfk_1` FOREIGN KEY (`id_pesanan`) REFERENCES `pesanan` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pembayaran`
--

INSERT INTO `pembayaran` (`id`, `id_pesanan`, `nominal_pembayaran`, `bukti_pembayaran`, `tanggal`, `status`, `metode_pembayaran`) VALUES
(1, 1, '357000.00', '6826159c0e34f2.18149029_sddefault.jpg', '2025-05-30', 'valid', 'transfer'),
(2, 2, '93000.00', '68261697a4a919.68706693_satoru-gojo-jujutsu-kaisen-uhdpaper.com-hd-3.2769.jpg', '2025-05-15', 'valid', 'transfer');

-- --------------------------------------------------------

--
-- Table structure for table `tentang`
--

DROP TABLE IF EXISTS `tentang`;
CREATE TABLE `tentang` (
  `id` int NOT NULL AUTO_INCREMENT,
  `judul` varchar(255) NOT NULL,
  `deskripsi` text NOT NULL,
  `alamat` text NOT NULL,
  `telepon` varchar(20) NOT NULL,
  `email` varchar(100) NOT NULL,
  `maps_embed` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tentang`
--

INSERT INTO `tentang` (`id`, `judul`, `deskripsi`, `alamat`, `telepon`, `email`, `maps_embed`, `created_at`, `updated_at`) VALUES
(1, 'Tentang Kami', 'JangkriknetStore adalah toko yang menyediakan berbagai produk berkualitas dengan harga yang terjangkau. Kami berkomitmen untuk memberikan pelayanan terbaik kepada setiap pelanggan kami.', 'Jl. Contoh No. 123, Kota, Provinsi', '081234567890', 'jangkriknet@gmail.com', '<iframe src=\"https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3955.983475909864!2d112.71428707436539!3d-7.467075673608052!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dd7e6d4952b144b%3A0x311db776bc8bca8b!2sMuhammadiyah%20University%20of%20Sidoarjo!5e0!3m2!1sen!2sid!4v1747419917849!5m2!1sen!2sid\" width=\"600\" height=\"450\" style=\"border:0;\" allowfullscreen=\"\" loading=\"lazy\" referrerpolicy=\"no-referrer-when-downgrade\"></iframe>', '2025-05-16 18:02:57', '2025-05-16 18:25:51');

SET FOREIGN_KEY_CHECKS = 1;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
