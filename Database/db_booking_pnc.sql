-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Aug 31, 2026 at 03:12 PM
-- Server version: 8.0.46
-- PHP Version: 8.3.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_booking_pnc`
--

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_general_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2026_05_04_014549_create_password_reset_tokens_table', 1),
(2, '2026_05_04_015309_create_password_reset_tokens_table', 2),
(3, '2026_05_04_015654_create_password_reset_tokens_table', 3),
(4, '2026_05_04_020617_create_password_reset_tokens_table', 4);

-- --------------------------------------------------------

--
-- Table structure for table `ms_paket`
--

CREATE TABLE `ms_paket` (
  `id_paket` int NOT NULL,
  `ms_sub_kategori_paket_id_sub_kategori_paket` int NOT NULL,
  `nama_paket` varchar(40) NOT NULL,
  `deskripsi_paket` varchar(100) DEFAULT NULL,
  `maksimal_orang` smallint NOT NULL,
  `is_active` tinyint DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `ms_paket`
--

INSERT INTO `ms_paket` (`id_paket`, `ms_sub_kategori_paket_id_sub_kategori_paket`, `nama_paket`, `deskripsi_paket`, `maksimal_orang`, `is_active`, `updated_at`, `created_at`) VALUES
(8, 0, 'Paket PS3', NULL, 2, 1, '2026-05-23 07:13:33', '2026-05-23 07:13:33'),
(9, 1, 'Mini Karaoke', '010101', 2, 1, '2026-07-07 07:45:03', '2026-07-07 07:45:03'),
(10, 2, 'American Football', '1234', 2, 1, '2026-07-07 07:45:48', '2026-07-07 07:45:48'),
(11, 1, 'Happy Deal Karaoke', 'aa', 12, 1, '2026-07-07 07:54:49', '2026-07-07 07:54:49'),
(12, 2, 'Gaming private 3', NULL, 2, 1, '2026-08-07 13:24:45', '2026-08-07 13:24:45'),
(13, 2, 'Lovers', 'AC', 2, 1, '2026-08-19 08:00:23', '2026-08-19 08:00:23');

-- --------------------------------------------------------

--
-- Table structure for table `ms_pengaturan`
--

CREATE TABLE `ms_pengaturan` (
  `id_pengaturan` int NOT NULL,
  `wifi_ssid` varchar(80) DEFAULT NULL,
  `wifi_password` varchar(50) DEFAULT NULL,
  `nama_toko` varchar(150) DEFAULT NULL,
  `alamat_toko` varchar(200) DEFAULT NULL,
  `slogan_header` varchar(100) DEFAULT NULL,
  `ig` varchar(100) DEFAULT NULL,
  `wa` varchar(100) DEFAULT NULL,
  `tiktok` varchar(100) DEFAULT NULL,
  `logo_struk` varchar(255) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `ms_pengaturan`
--

INSERT INTO `ms_pengaturan` (`id_pengaturan`, `wifi_ssid`, `wifi_password`, `nama_toko`, `alamat_toko`, `slogan_header`, `ig`, `wa`, `tiktok`, `logo_struk`, `updated_at`, `created_at`) VALUES
(1, 'Play n Chill WiFi', 'pnc12345', 'Play n Chill Jogja', 'Jl. Margobawero NO. 47\r\nKota Madiun', 'Play, Chill, Repeats', '@PlayNChill.id', '096543212376', '@PlayNChill.id', '1783779225_PNCLOGO.jpg', '2026-08-31 14:04:25', '2026-07-10 14:48:13');

-- --------------------------------------------------------

--
-- Table structure for table `ms_permainan`
--

CREATE TABLE `ms_permainan` (
  `id_permainan` int NOT NULL,
  `nama_permainan` varchar(30) DEFAULT NULL,
  `gambar` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `ms_permainan`
--

INSERT INTO `ms_permainan` (`id_permainan`, `nama_permainan`, `gambar`) VALUES
(12, 'FC 26', 'images/game/game_1787131382_6a8575f660026.jpg'),
(13, 'Formula 1', 'images/game/game_1787131395_6a857603cf6e4.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `ms_produk`
--

CREATE TABLE `ms_produk` (
  `id_produk` int NOT NULL,
  `ms_sub_kategori_produk_id_sub_kategori_produk` int NOT NULL,
  `nama_produk` varchar(30) DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `harga_jual` int DEFAULT NULL,
  `harga_beli` int DEFAULT NULL,
  `sku` varchar(10) DEFAULT NULL,
  `stock` int DEFAULT NULL,
  `is_active` tinyint DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `ms_produk`
--

INSERT INTO `ms_produk` (`id_produk`, `ms_sub_kategori_produk_id_sub_kategori_produk`, `nama_produk`, `foto`, `harga_jual`, `harga_beli`, `sku`, `stock`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 1, 'Indomie Goreng', '1786087636_macot.jpg', 6000, 3000, 'PNC-121', 4, 1, '2026-07-09 08:07:14', '2026-08-26 15:38:36'),
(2, 2, 'Top Kopi Gula Aren', '1783584625_default-profile.png', 5000, 2000, 'PNC-112', 89, 1, '2026-07-09 08:10:25', '2026-08-31 14:06:00'),
(3, 2, 'Top Kopi Gula Aren', '1783584792_default.jpg', 5000, 2000, 'PNC-113', 0, 1, '2026-07-09 08:13:12', '2026-08-19 14:51:47'),
(4, 3, 'Seblak', '1783585580_1985.png', 6000, 3000, 'PNC-115', -5, 1, '2026-07-09 08:26:20', '2026-08-28 13:40:19'),
(5, 4, 'Nasi Goreng Ikan', '1783585687_Banner.jpg', 14000, 7000, 'PNC-222', 1, 1, '2026-07-09 08:28:07', '2026-08-26 15:35:33'),
(6, 6, 'Ketupat Majalengka', '1783585967_aktivitas_santri.png', 10000, 5000, 'PNC-551', 89979, 1, '2026-07-09 08:32:47', '2026-08-26 15:38:36'),
(7, 7, 'Americano', '1783605557_Top 20 Car Logos Of All Time.webp', 200000, 90000, 'PNC-890', 871, 1, '2026-07-09 13:59:17', '2026-08-31 14:46:55'),
(8, 1, 'The Milo', '1786087604_Screenshot 2026-08-04 200349.png', 20000, 10000, 'PNC-987', 8978, 1, '2026-07-13 08:24:54', '2026-08-31 14:46:55'),
(9, 1, 'Kopi Ngawi', '1786087582_Screenshot 2026-08-03 142856.png', 200000, 90000, 'PNC-9873', 8976, 1, '2026-07-13 08:26:41', '2026-08-31 14:46:55'),
(10, 8, 'Stk', '1786522464_Flowchart .drawio.png', 20000, 10000, 'PNCJ32', 4, 1, '2026-08-12 08:14:24', '2026-08-31 14:06:00'),
(11, 9, 'Dave MustEaten', '1787131353_dave.jpeg', 6000, 90000, 'PNCJ-L091', 76, 1, '2026-08-19 09:21:05', '2026-08-23 16:35:50');

-- --------------------------------------------------------

--
-- Table structure for table `ms_ruangan`
--

CREATE TABLE `ms_ruangan` (
  `id_ruangan` int NOT NULL,
  `nama_ruangan` varchar(20) NOT NULL,
  `kategori` enum('REGULAR','PRIVATE-ROOM') DEFAULT NULL,
  `perangkat` enum('PS3','PS4','PS5','Nintendo Switch') DEFAULT NULL,
  `is_active` tinyint DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `galeri` varchar(225) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `ms_ruangan`
--

INSERT INTO `ms_ruangan` (`id_ruangan`, `nama_ruangan`, `kategori`, `perangkat`, `is_active`, `updated_at`, `galeri`, `created_at`) VALUES
(13, 'R01', 'REGULAR', 'PS3', 1, '2026-08-16 18:10:11', 'images/ruangan/ruangan_1786903811_6a81fd03edf4d.jpg', '2026-05-23 07:12:52'),
(14, 'P01', 'PRIVATE-ROOM', 'PS5', 1, '2026-07-07 07:43:55', NULL, '2026-07-07 07:43:55'),
(15, 'P-03', 'PRIVATE-ROOM', 'Nintendo Switch', 1, '2026-08-07 13:17:38', NULL, '2026-08-07 13:17:38');

-- --------------------------------------------------------

--
-- Table structure for table `ms_ruangan_ms_permainan`
--

CREATE TABLE `ms_ruangan_ms_permainan` (
  `id_ruangan` int NOT NULL,
  `id_permainan` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `ms_ruangan_ms_permainan`
--

INSERT INTO `ms_ruangan_ms_permainan` (`id_ruangan`, `id_permainan`) VALUES
(13, 12),
(14, 12),
(13, 13),
(14, 13),
(15, 13);

-- --------------------------------------------------------

--
-- Table structure for table `ms_sub_kategori_paket`
--

CREATE TABLE `ms_sub_kategori_paket` (
  `id_sub_kategori_paket` int NOT NULL,
  `nama_sub_kategori` varchar(50) NOT NULL,
  `is_active` tinyint DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `ms_sub_kategori_paket`
--

INSERT INTO `ms_sub_kategori_paket` (`id_sub_kategori_paket`, `nama_sub_kategori`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Mini Karaoke', 1, '2026-07-07 07:45:03', '2026-07-07 07:45:03'),
(2, 'Gaming Private', 1, '2026-07-07 07:45:48', '2026-07-07 07:45:48');

-- --------------------------------------------------------

--
-- Table structure for table `ms_sub_kategori_produk`
--

CREATE TABLE `ms_sub_kategori_produk` (
  `id_sub_kategori_produk` int NOT NULL,
  `kategori_produk` varchar(50) DEFAULT NULL,
  `sub_kategori_produk` enum('Makanan ringan','Makanan berat','Minuman') DEFAULT NULL,
  `is_active` tinyint DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `ms_sub_kategori_produk`
--

INSERT INTO `ms_sub_kategori_produk` (`id_sub_kategori_produk`, `kategori_produk`, `sub_kategori_produk`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Cowork', 'Minuman', 1, '2026-07-09 08:07:14', '2026-07-09 08:07:14'),
(2, 'Internal', 'Minuman', 1, '2026-07-09 08:10:25', '2026-07-09 08:10:25'),
(3, 'Internal', 'Makanan berat', 1, '2026-07-09 08:26:20', '2026-07-09 08:26:20'),
(4, 'Cowork', 'Makanan berat', 1, '2026-07-09 08:28:07', '2026-07-09 08:28:07'),
(5, 'Eksternal', 'Makanan ringan', 1, '2026-07-09 08:32:47', '2026-07-09 08:32:47'),
(6, 'Internal', 'Makanan ringan', 1, '2026-07-09 09:02:19', '2026-07-09 09:02:19'),
(7, 'Cowork', 'Makanan ringan', 1, '2026-07-09 13:59:17', '2026-07-09 13:59:17'),
(8, 'Eksternal', 'Minuman', 1, '2026-08-12 08:14:24', '2026-08-12 08:14:24'),
(9, 'Tradisional', 'Minuman', 1, '2026-08-19 09:21:05', '2026-08-19 09:21:05');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `password_reset_tokens`
--

INSERT INTO `password_reset_tokens` (`email`, `token`, `created_at`) VALUES
('superadmin@gmail.com', '$2y$12$vHX68FcVxoRC0R/6OT10OerapOhfybckRz2tb55q.6W8Ummt.caAO', '2026-05-11 02:17:05');

-- --------------------------------------------------------

--
-- Table structure for table `penetapan_harga`
--

CREATE TABLE `penetapan_harga` (
  `id_penetapan_harga` int NOT NULL,
  `id_ruangan` int NOT NULL,
  `id_paket` int NOT NULL,
  `harga` int DEFAULT NULL,
  `durasi_jam` int DEFAULT NULL,
  `tipe_hari` enum('harian','akhir_pekan','liburan') DEFAULT NULL,
  `sku` varchar(10) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `penetapan_harga`
--

INSERT INTO `penetapan_harga` (`id_penetapan_harga`, `id_ruangan`, `id_paket`, `harga`, `durasi_jam`, `tipe_hari`, `sku`, `updated_at`, `created_at`) VALUES
(46, 13, 8, 20000, 1, 'harian', NULL, '2026-05-23 07:13:33', '2026-05-23 07:13:33'),
(47, 13, 8, 38000, 2, 'harian', NULL, '2026-05-23 07:13:33', '2026-05-23 07:13:33'),
(48, 14, 9, 20000, 1, 'akhir_pekan', NULL, '2026-07-07 07:45:03', '2026-07-07 07:45:03'),
(49, 14, 9, 30000, 2, 'akhir_pekan', NULL, '2026-07-07 07:45:03', '2026-07-07 07:45:03'),
(50, 14, 9, 42000, 3, 'akhir_pekan', NULL, '2026-07-07 07:45:03', '2026-07-07 07:45:03'),
(51, 14, 10, 12000, 1, 'akhir_pekan', NULL, '2026-07-07 07:45:48', '2026-07-07 07:45:48'),
(52, 14, 10, 24000, 2, 'akhir_pekan', NULL, '2026-07-07 07:45:48', '2026-07-07 07:45:48'),
(53, 14, 10, 43000, 3, 'akhir_pekan', NULL, '2026-07-07 07:45:48', '2026-07-07 07:45:48'),
(54, 14, 11, 20000, 1, 'harian', NULL, '2026-07-07 07:54:49', '2026-07-07 07:54:49'),
(55, 14, 11, 30000, 2, 'harian', NULL, '2026-07-07 07:54:49', '2026-07-07 07:54:49'),
(56, 14, 12, 10000, 1, 'harian', NULL, '2026-08-07 13:24:45', '2026-08-07 13:24:45'),
(57, 14, 12, 22000, 2, 'harian', NULL, '2026-08-07 13:24:45', '2026-08-07 13:24:45'),
(58, 15, 12, 10000, 1, 'harian', NULL, '2026-08-07 13:24:45', '2026-08-07 13:24:45'),
(59, 15, 12, 22000, 2, 'harian', NULL, '2026-08-07 13:24:45', '2026-08-07 13:24:45');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `user_id` int DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_general_ci,
  `payload` longtext COLLATE utf8mb4_general_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tr_nomor_urut_harian`
--

CREATE TABLE `tr_nomor_urut_harian` (
  `id` int NOT NULL,
  `tanggal` date NOT NULL,
  `urutan_terakhir` int UNSIGNED NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tr_nomor_urut_harian`
--

INSERT INTO `tr_nomor_urut_harian` (`id`, `tanggal`, `urutan_terakhir`, `created_at`, `updated_at`) VALUES
(1, '2026-08-31', 11, '2026-08-31 08:43:50', '2026-08-31 15:09:03');

-- --------------------------------------------------------

--
-- Table structure for table `tr_pos`
--

CREATE TABLE `tr_pos` (
  `id_pos` int NOT NULL,
  `id_transaksi` int DEFAULT NULL,
  `id_pengguna` int DEFAULT NULL,
  `id_admin` int DEFAULT NULL,
  `total_pos` int DEFAULT NULL,
  `sumber_pesanan` enum('Kasir','Online') DEFAULT NULL,
  `status_pesanan` enum('Menunggu','Selesai','Dibatalkan') DEFAULT NULL,
  `status_pembayaran` enum('belum-bayar','sudah-bayar','lunas','kadaluarsa') DEFAULT 'belum-bayar',
  `catatan` varchar(50) DEFAULT NULL,
  `nomor_nota` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `metode_pembayaran` enum('QRIS','TUNAI') DEFAULT NULL,
  `nama_pelanggan` varchar(60) DEFAULT NULL,
  `uang_diterima` int DEFAULT NULL,
  `kembalian` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tr_pos`
--

INSERT INTO `tr_pos` (`id_pos`, `id_transaksi`, `id_pengguna`, `id_admin`, `total_pos`, `sumber_pesanan`, `status_pesanan`, `status_pembayaran`, `catatan`, `nomor_nota`, `created_at`, `updated_at`, `metode_pembayaran`, `nama_pelanggan`, `uang_diterima`, `kembalian`) VALUES
(25, NULL, 111, NULL, 36005, 'Online', 'Selesai', 'lunas', '$request->catatan', NULL, '2026-07-12 14:18:25', '2026-07-12 14:21:54', NULL, NULL, NULL, NULL),
(26, NULL, 111, NULL, 12005, 'Online', 'Dibatalkan', 'kadaluarsa', 'MemeGG', NULL, '2026-07-12 14:34:53', '2026-07-12 15:59:04', NULL, NULL, NULL, NULL),
(27, NULL, 111, NULL, 210005, 'Online', 'Dibatalkan', 'kadaluarsa', 'MMK', NULL, '2026-07-12 15:42:31', '2026-07-12 15:59:04', NULL, NULL, NULL, NULL),
(28, 91, 1, NULL, 448010, 'Online', 'Dibatalkan', 'kadaluarsa', NULL, NULL, '2026-07-12 15:46:30', '2026-07-12 16:17:34', NULL, NULL, NULL, NULL),
(29, 92, 119, NULL, 14000, 'Kasir', 'Dibatalkan', 'kadaluarsa', NULL, NULL, '2026-07-12 16:06:26', '2026-07-12 16:33:55', NULL, NULL, NULL, NULL),
(30, 86, 116, NULL, 220005, 'Kasir', 'Dibatalkan', 'kadaluarsa', NULL, NULL, '2026-07-12 16:07:07', '2026-07-12 16:33:55', NULL, NULL, NULL, NULL),
(31, NULL, 111, NULL, 20005, 'Online', 'Dibatalkan', 'kadaluarsa', NULL, NULL, '2026-07-12 16:19:09', '2026-07-13 07:26:47', NULL, NULL, NULL, NULL),
(32, 86, 116, NULL, 80000, 'Kasir', 'Dibatalkan', 'kadaluarsa', NULL, NULL, '2026-07-12 16:27:24', '2026-07-13 07:26:47', NULL, NULL, NULL, NULL),
(33, 93, 120, NULL, 230000, 'Kasir', 'Dibatalkan', 'kadaluarsa', NULL, NULL, '2026-07-13 06:15:54', '2026-07-13 07:26:47', NULL, NULL, NULL, NULL),
(34, 94, 121, NULL, 410000, 'Kasir', 'Dibatalkan', 'kadaluarsa', NULL, NULL, '2026-07-13 06:19:35', '2026-07-13 07:26:47', NULL, NULL, NULL, NULL),
(35, 93, 120, NULL, 5, 'Kasir', 'Selesai', 'sudah-bayar', NULL, NULL, '2026-07-13 06:52:21', '2026-07-13 08:27:05', NULL, NULL, NULL, NULL),
(36, 95, 123, NULL, 42000, 'Kasir', 'Dibatalkan', 'kadaluarsa', NULL, NULL, '2026-07-13 06:58:19', '2026-07-13 07:26:47', NULL, NULL, NULL, NULL),
(37, 96, 124, NULL, 200005, 'Kasir', 'Dibatalkan', 'kadaluarsa', NULL, NULL, '2026-07-13 07:27:41', '2026-07-14 03:52:20', NULL, NULL, NULL, NULL),
(38, NULL, 111, NULL, 30005, 'Online', 'Dibatalkan', 'kadaluarsa', '--', NULL, '2026-07-13 07:30:59', '2026-07-13 07:49:22', NULL, NULL, NULL, NULL),
(39, 97, 125, NULL, 224000, 'Kasir', 'Selesai', 'sudah-bayar', NULL, NULL, '2026-07-13 07:47:19', '2026-07-13 08:40:00', NULL, NULL, NULL, NULL),
(40, NULL, 134, NULL, 220000, 'Kasir', 'Dibatalkan', 'kadaluarsa', 'pp', NULL, '2026-07-13 09:56:00', '2026-07-14 03:52:20', 'TUNAI', NULL, NULL, NULL),
(41, 102, 111, NULL, 2260000, 'Online', 'Dibatalkan', 'kadaluarsa', NULL, NULL, '2026-07-13 14:55:28', '2026-07-13 15:35:48', NULL, NULL, NULL, NULL),
(42, 103, 135, NULL, 214000, 'Kasir', 'Dibatalkan', 'kadaluarsa', NULL, NULL, '2026-07-13 15:49:10', '2026-07-14 03:52:20', 'QRIS', NULL, NULL, NULL),
(43, 105, 1, NULL, 420000, 'Online', 'Dibatalkan', 'kadaluarsa', NULL, NULL, '2026-07-13 15:57:52', '2026-07-14 03:52:20', 'TUNAI', NULL, NULL, NULL),
(44, 108, 111, NULL, 88000, 'Online', 'Dibatalkan', 'kadaluarsa', NULL, NULL, '2026-07-14 03:43:56', '2026-07-14 04:02:44', NULL, NULL, NULL, NULL),
(45, 109, 111, NULL, 35000, 'Online', 'Dibatalkan', 'kadaluarsa', NULL, NULL, '2026-07-14 03:55:34', '2026-07-14 04:20:33', NULL, NULL, NULL, NULL),
(46, 110, 1, NULL, 200000, 'Online', 'Dibatalkan', 'kadaluarsa', NULL, NULL, '2026-07-14 03:59:52', '2026-07-14 04:20:33', NULL, NULL, NULL, NULL),
(47, 111, 1, NULL, 20000, 'Online', 'Dibatalkan', 'kadaluarsa', NULL, NULL, '2026-07-14 04:05:55', '2026-07-14 04:59:22', NULL, NULL, NULL, NULL),
(48, 112, 111, NULL, 380000, 'Online', 'Dibatalkan', 'kadaluarsa', NULL, NULL, '2026-07-14 04:12:29', '2026-07-14 04:59:22', NULL, NULL, NULL, NULL),
(49, 113, 1, NULL, 92000, 'Online', 'Dibatalkan', 'kadaluarsa', NULL, NULL, '2026-08-07 13:05:17', '2026-08-07 13:32:58', NULL, NULL, NULL, NULL),
(50, NULL, 1, NULL, 220000, 'Online', 'Dibatalkan', 'kadaluarsa', NULL, NULL, '2026-08-07 13:06:23', '2026-08-07 13:32:58', NULL, NULL, NULL, NULL),
(51, 114, 138, NULL, 200000, 'Kasir', 'Selesai', 'lunas', NULL, NULL, '2026-08-07 13:14:40', '2026-08-07 13:34:37', 'QRIS', NULL, NULL, NULL),
(52, NULL, 1, NULL, 200000, 'Online', 'Dibatalkan', 'kadaluarsa', NULL, NULL, '2026-08-07 13:34:14', '2026-08-12 06:03:27', NULL, NULL, NULL, NULL),
(53, 117, 142, NULL, 5000, 'Kasir', 'Dibatalkan', 'kadaluarsa', NULL, NULL, '2026-08-13 04:56:49', '2026-08-13 13:45:46', 'QRIS', NULL, NULL, NULL),
(54, 122, 147, 36, 40000, 'Kasir', 'Dibatalkan', 'kadaluarsa', NULL, NULL, '2026-08-13 13:43:20', '2026-08-17 12:51:52', 'QRIS', NULL, NULL, NULL),
(55, NULL, 148, 36, 20000, 'Kasir', 'Dibatalkan', 'kadaluarsa', NULL, NULL, '2026-08-13 13:44:18', '2026-08-17 12:51:52', 'QRIS', NULL, NULL, NULL),
(56, 123, 1, 36, 20000, 'Online', 'Dibatalkan', 'kadaluarsa', NULL, NULL, '2026-08-13 13:45:42', '2026-08-17 12:51:52', NULL, NULL, NULL, NULL),
(57, NULL, 1, 36, 20000, 'Online', 'Selesai', 'lunas', NULL, NULL, '2026-08-13 13:47:33', '2026-08-13 13:48:19', NULL, NULL, NULL, NULL),
(58, NULL, 51, 36, 489000, 'Online', 'Selesai', 'lunas', NULL, NULL, '2026-08-19 13:42:08', '2026-08-19 13:47:13', NULL, NULL, NULL, NULL),
(59, NULL, 51, 36, 759000, 'Online', 'Selesai', 'lunas', NULL, NULL, '2026-08-19 13:45:26', '2026-08-19 13:47:04', NULL, NULL, NULL, NULL),
(60, 125, 51, 36, 498000, 'Online', 'Dibatalkan', 'kadaluarsa', NULL, NULL, '2026-08-19 14:35:22', '2026-08-19 14:51:29', NULL, NULL, NULL, NULL),
(61, 126, 51, 36, 63000, 'Online', 'Dibatalkan', 'kadaluarsa', NULL, NULL, '2026-08-19 14:37:14', '2026-08-19 14:56:05', NULL, NULL, NULL, NULL),
(62, NULL, 51, 36, 220000, 'Online', 'Dibatalkan', 'kadaluarsa', NULL, NULL, '2026-08-19 14:38:58', '2026-08-19 14:56:05', NULL, NULL, NULL, NULL),
(63, NULL, 51, 36, 880000, 'Online', 'Dibatalkan', 'kadaluarsa', NULL, NULL, '2026-08-19 14:43:50', '2026-08-19 15:25:14', NULL, NULL, NULL, NULL),
(64, NULL, 51, 36, 61000, 'Online', 'Dibatalkan', 'kadaluarsa', NULL, NULL, '2026-08-19 14:51:47', '2026-08-19 15:25:14', 'QRIS', NULL, NULL, NULL),
(65, 154, 35, NULL, 200000, 'Online', 'Dibatalkan', 'kadaluarsa', NULL, NULL, '2026-08-23 15:32:07', '2026-08-23 15:49:15', 'TUNAI', NULL, NULL, NULL),
(66, 158, 35, NULL, 16000, 'Online', 'Dibatalkan', 'kadaluarsa', NULL, NULL, '2026-08-23 15:43:52', '2026-08-23 16:00:39', 'QRIS', NULL, NULL, NULL),
(67, 160, 35, NULL, 205000, 'Online', 'Dibatalkan', 'kadaluarsa', NULL, NULL, '2026-08-23 15:45:03', '2026-08-23 16:00:39', 'TUNAI', NULL, NULL, NULL),
(68, 163, 35, NULL, 48000, 'Online', 'Dibatalkan', 'kadaluarsa', NULL, NULL, '2026-08-23 16:00:37', '2026-08-24 14:53:14', NULL, NULL, NULL, NULL),
(69, 167, 35, 36, 48000, 'Online', 'Selesai', 'lunas', NULL, NULL, '2026-08-23 16:13:40', '2026-08-24 15:38:35', NULL, NULL, NULL, NULL),
(70, 174, 201, 36, 426000, 'Kasir', 'Dibatalkan', 'kadaluarsa', NULL, NULL, '2026-08-23 16:35:50', '2026-08-25 15:01:52', 'TUNAI', NULL, NULL, NULL),
(71, 180, 1, NULL, 48000, 'Online', 'Dibatalkan', 'kadaluarsa', NULL, NULL, '2026-08-25 14:59:42', '2026-08-26 13:25:46', NULL, NULL, NULL, NULL),
(72, 181, 1, NULL, 48000, 'Online', 'Dibatalkan', 'kadaluarsa', NULL, NULL, '2026-08-25 15:01:49', '2026-08-26 13:25:46', NULL, NULL, NULL, NULL),
(73, NULL, 162, 36, 440000, 'Kasir', 'Menunggu', 'lunas', '-', NULL, '2026-08-26 14:23:28', '2026-08-26 14:23:28', 'TUNAI', NULL, NULL, NULL),
(74, NULL, 207, 36, 420000, 'Kasir', 'Menunggu', 'lunas', '-', NULL, '2026-08-26 14:30:36', '2026-08-26 14:30:36', 'TUNAI', NULL, NULL, NULL),
(75, NULL, 208, 36, 440000, 'Kasir', 'Menunggu', 'lunas', 'Antar Ke Ruangan VVIP', NULL, '2026-08-26 14:35:01', '2026-08-26 14:35:01', 'TUNAI', NULL, NULL, NULL),
(76, NULL, 209, 36, 430000, 'Kasir', 'Menunggu', 'lunas', '-', NULL, '2026-08-26 14:42:39', '2026-08-26 14:42:39', 'TUNAI', NULL, NULL, NULL),
(77, NULL, 162, 36, 430000, 'Kasir', 'Menunggu', 'lunas', '-', NULL, '2026-08-26 14:48:36', '2026-08-26 14:48:36', 'TUNAI', NULL, NULL, NULL),
(78, NULL, 173, 36, 456000, 'Kasir', 'Menunggu', 'lunas', '-', NULL, '2026-08-26 14:53:38', '2026-08-26 14:53:38', 'TUNAI', NULL, NULL, NULL),
(79, NULL, 173, 36, 450000, 'Kasir', 'Menunggu', 'lunas', 'Lorep Ipsum Dolor Sit Amet ...', NULL, '2026-08-26 14:57:39', '2026-08-26 14:57:39', 'TUNAI', NULL, NULL, NULL),
(80, NULL, 173, 36, 1090000, 'Kasir', 'Menunggu', 'lunas', 'Lorem Ipsum Dolor Sit Amet ...', NULL, '2026-08-26 15:02:36', '2026-08-26 15:02:36', 'TUNAI', NULL, NULL, NULL),
(81, NULL, 210, 36, 680000, 'Kasir', 'Menunggu', 'lunas', 'Lorem ipsum dolor sit amet laboratur ...', NULL, '2026-08-26 15:08:09', '2026-08-26 15:08:09', 'TUNAI', NULL, NULL, NULL),
(82, NULL, 210, 36, 450000, 'Kasir', 'Menunggu', 'lunas', 'Lorem ipsum dolor sit amet laboratur ...', NULL, '2026-08-26 15:08:20', '2026-08-26 15:08:20', 'TUNAI', NULL, NULL, NULL),
(83, NULL, 173, 36, 430000, 'Kasir', 'Menunggu', 'lunas', 'Lorem ipsum dolor sit amet laborator ...', NULL, '2026-08-26 15:09:31', '2026-08-26 15:09:31', 'TUNAI', NULL, NULL, NULL),
(84, NULL, 173, 36, 460000, 'Kasir', 'Menunggu', 'lunas', 'Lorem ipsum dolor sit amet laboratur ...', NULL, '2026-08-26 15:11:54', '2026-08-26 15:11:54', 'TUNAI', NULL, NULL, NULL),
(85, NULL, 173, 36, 470000, 'Kasir', 'Menunggu', 'lunas', 'Lorem ipsum dolor sit amet laboratur ...', NULL, '2026-08-26 15:14:17', '2026-08-26 15:14:17', 'TUNAI', NULL, NULL, NULL),
(86, NULL, 173, 36, 450000, 'Kasir', 'Menunggu', 'lunas', 'Lorem ipsum dolor sit amet laoratur ...', NULL, '2026-08-26 15:17:37', '2026-08-26 15:17:37', 'TUNAI', NULL, NULL, NULL),
(87, NULL, 173, 36, 460000, 'Kasir', 'Menunggu', 'lunas', 'Lorem ipsum dolor sit amet laboratur ...', NULL, '2026-08-26 15:35:33', '2026-08-26 15:35:33', 'TUNAI', NULL, NULL, NULL),
(88, NULL, NULL, 36, 256000, 'Kasir', 'Menunggu', 'lunas', '-', NULL, '2026-08-26 15:38:36', '2026-08-26 15:38:37', 'TUNAI', 'Hannnoch123', NULL, NULL),
(89, 182, 1, 36, 48000, 'Online', 'Dibatalkan', 'kadaluarsa', NULL, NULL, '2026-08-28 13:40:19', '2026-08-31 13:35:09', 'TUNAI', NULL, NULL, NULL),
(90, 184, 212, 36, 5000, 'Kasir', 'Menunggu', 'lunas', NULL, NULL, '2026-08-28 14:22:01', '2026-08-28 14:22:01', 'TUNAI', NULL, NULL, NULL),
(95, NULL, NULL, 36, 425000, 'Kasir', 'Menunggu', 'lunas', '-', '260831/PNC01/005', '2026-08-31 14:06:00', '2026-08-31 14:06:00', 'TUNAI', 'kardi', NULL, NULL),
(96, NULL, NULL, 36, 420000, 'Kasir', 'Menunggu', 'lunas', '-', '260831/PNC01/007', '2026-08-31 14:46:55', '2026-08-31 14:46:55', 'QRIS', 'Git', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tr_pos_detail`
--

CREATE TABLE `tr_pos_detail` (
  `id_pos_detail` int NOT NULL,
  `id_pos` int NOT NULL,
  `id_produk` int NOT NULL,
  `jumlah` int DEFAULT NULL,
  `harga_satuan` int DEFAULT NULL,
  `subtotal` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tr_pos_detail`
--

INSERT INTO `tr_pos_detail` (`id_pos_detail`, `id_pos`, `id_produk`, `jumlah`, `harga_satuan`, `subtotal`) VALUES
(26, 14, 7, 1, 200000, 200000),
(27, 15, 4, 2, 6000, 12000),
(28, 15, 5, 2, 14000, 28000),
(29, 15, 6, 1, 10000, 10000),
(30, 15, 2, 1, 5, 5),
(31, 16, 4, 2, 6000, 12000),
(32, 17, 4, 1, 6000, 6000),
(33, 18, 4, 2, 6000, 12000),
(34, 19, 4, 1, 6000, 6000),
(35, 19, 5, 1, 14000, 14000),
(36, 19, 6, 1, 10000, 10000),
(37, 20, 2, 1, 5, 5),
(38, 20, 4, 2, 6000, 12000),
(39, 21, 4, 1, 6000, 6000),
(40, 21, 2, 1, 5, 5),
(41, 21, 5, 1, 14000, 14000),
(42, 22, 2, 1, 5, 5),
(43, 22, 4, 1, 6000, 6000),
(44, 22, 5, 1, 14000, 14000),
(45, 23, 5, 1, 14000, 14000),
(46, 23, 6, 1, 10000, 10000),
(47, 23, 2, 1, 5, 5),
(48, 24, 5, 2, 14000, 28000),
(49, 24, 6, 4, 10000, 40000),
(50, 25, 2, 1, 5, 5),
(51, 25, 4, 6, 6000, 36000),
(52, 26, 2, 1, 5, 5),
(53, 26, 4, 2, 6000, 12000),
(54, 27, 2, 1, 5, 5),
(55, 27, 6, 1, 10000, 10000),
(56, 27, 7, 1, 200000, 200000),
(57, 28, 2, 2, 5, 10),
(58, 28, 4, 1, 6000, 6000),
(59, 28, 5, 3, 14000, 42000),
(60, 28, 7, 2, 200000, 400000),
(61, 29, 5, 1, 14000, 14000),
(62, 30, 2, 1, 5, 5),
(63, 30, 4, 1, 6000, 6000),
(64, 30, 5, 1, 14000, 14000),
(65, 30, 7, 1, 200000, 200000),
(66, 31, 2, 1, 5, 5),
(67, 31, 4, 1, 6000, 6000),
(68, 31, 5, 1, 14000, 14000),
(69, 32, 6, 8, 10000, 80000),
(70, 33, 4, 1, 6000, 6000),
(71, 33, 5, 1, 14000, 14000),
(72, 33, 6, 1, 10000, 10000),
(73, 33, 7, 1, 200000, 200000),
(74, 34, 6, 1, 10000, 10000),
(75, 34, 7, 2, 200000, 400000),
(76, 35, 2, 1, 5, 5),
(77, 36, 5, 3, 14000, 42000),
(78, 37, 2, 1, 5, 5),
(79, 37, 7, 1, 200000, 200000),
(80, 38, 2, 1, 5, 5),
(81, 38, 4, 1, 6000, 6000),
(82, 38, 5, 1, 14000, 14000),
(83, 38, 6, 1, 10000, 10000),
(84, 39, 5, 1, 14000, 14000),
(85, 39, 6, 1, 10000, 10000),
(86, 39, 7, 1, 200000, 200000),
(87, 40, 4, 1, 6000, 6000),
(88, 40, 5, 1, 14000, 14000),
(89, 40, 9, 1, 200000, 200000),
(90, 41, 9, 7, 200000, 1400000),
(91, 41, 8, 3, 20000, 60000),
(92, 41, 7, 4, 200000, 800000),
(93, 42, 5, 1, 14000, 14000),
(94, 42, 9, 1, 200000, 200000),
(95, 43, 7, 1, 200000, 200000),
(96, 43, 8, 1, 20000, 20000),
(97, 43, 9, 1, 200000, 200000),
(98, 44, 1, 1, 35000, 35000),
(99, 44, 4, 1, 48000, 48000),
(100, 44, 2, 1, 5000, 5000),
(101, 45, 1, 1, 35000, 35000),
(102, 46, 9, 1, 200000, 200000),
(103, 47, 8, 1, 20000, 20000),
(104, 48, 1, 4, 35000, 140000),
(105, 48, 4, 5, 48000, 240000),
(106, 49, 8, 4, 20000, 80000),
(107, 49, 1, 2, 6000, 12000),
(108, 50, 6, 2, 10000, 20000),
(109, 50, 9, 1, 200000, 200000),
(110, 51, 7, 1, 200000, 200000),
(111, 52, 7, 1, 200000, 200000),
(112, 53, 3, 1, 5000, 5000),
(113, 54, 10, 2, 20000, 40000),
(114, 55, 10, 1, 20000, 20000),
(115, 56, 10, 1, 20000, 20000),
(116, 57, 10, 1, 20000, 20000),
(117, 58, 2, 2, 5000, 10000),
(118, 58, 3, 3, 5000, 15000),
(119, 58, 4, 2, 6000, 12000),
(120, 58, 7, 2, 200000, 400000),
(121, 58, 11, 2, 6000, 12000),
(122, 58, 10, 2, 20000, 40000),
(123, 59, 10, 2, 20000, 40000),
(124, 59, 6, 3, 10000, 30000),
(125, 59, 7, 3, 200000, 600000),
(126, 59, 3, 1, 5000, 5000),
(127, 59, 4, 2, 6000, 12000),
(128, 59, 1, 2, 6000, 12000),
(129, 59, 8, 3, 20000, 60000),
(130, 60, 4, 1, 48000, 48000),
(131, 60, 1, 1, 35000, 35000),
(132, 60, 2, 1, 5000, 5000),
(133, 60, 6, 1, 10000, 10000),
(134, 60, 7, 2, 200000, 400000),
(135, 61, 4, 1, 48000, 48000),
(136, 61, 3, 3, 5000, 15000),
(137, 62, 6, 2, 10000, 20000),
(138, 62, 7, 1, 200000, 200000),
(139, 63, 2, 2, 5000, 10000),
(140, 63, 3, 2, 5000, 10000),
(141, 63, 7, 3, 200000, 600000),
(142, 63, 6, 2, 10000, 20000),
(143, 63, 5, 1, 14000, 14000),
(144, 63, 9, 1, 200000, 200000),
(145, 63, 10, 1, 20000, 20000),
(146, 63, 11, 1, 6000, 6000),
(147, 64, 2, 1, 5000, 5000),
(148, 64, 3, 2, 5000, 10000),
(149, 64, 4, 1, 6000, 6000),
(150, 64, 8, 2, 20000, 40000),
(151, 65, 9, 1, 200000, 200000),
(152, 66, 4, 1, 6000, 6000),
(153, 66, 6, 1, 10000, 10000),
(154, 67, 2, 1, 5000, 5000),
(155, 67, 9, 1, 200000, 200000),
(156, 68, 4, 1, 48000, 48000),
(157, 69, 4, 1, 48000, 48000),
(158, 70, 7, 2, 200000, 400000),
(159, 70, 8, 1, 20000, 20000),
(160, 70, 11, 1, 6000, 6000),
(161, 71, 4, 1, 48000, 48000),
(162, 72, 4, 1, 48000, 48000),
(163, 73, 7, 1, 200000, 200000),
(164, 73, 8, 1, 20000, 20000),
(165, 73, 9, 1, 200000, 200000),
(166, 73, 10, 1, 20000, 20000),
(167, 74, 7, 1, 200000, 200000),
(168, 74, 9, 1, 200000, 200000),
(169, 74, 10, 1, 20000, 20000),
(170, 75, 7, 1, 200000, 200000),
(171, 75, 8, 1, 20000, 20000),
(172, 75, 9, 1, 200000, 200000),
(173, 75, 10, 1, 20000, 20000),
(174, 76, 6, 1, 10000, 10000),
(175, 76, 7, 1, 200000, 200000),
(176, 76, 9, 1, 200000, 200000),
(177, 76, 10, 1, 20000, 20000),
(178, 77, 6, 1, 10000, 10000),
(179, 77, 7, 1, 200000, 200000),
(180, 77, 9, 1, 200000, 200000),
(181, 77, 10, 1, 20000, 20000),
(182, 78, 1, 1, 6000, 6000),
(183, 78, 6, 1, 10000, 10000),
(184, 78, 7, 1, 200000, 200000),
(185, 78, 8, 1, 20000, 20000),
(186, 78, 9, 1, 200000, 200000),
(187, 78, 10, 1, 20000, 20000),
(188, 79, 6, 1, 10000, 10000),
(189, 79, 7, 1, 200000, 200000),
(190, 79, 8, 1, 20000, 20000),
(191, 79, 9, 1, 200000, 200000),
(192, 79, 10, 1, 20000, 20000),
(193, 80, 6, 1, 10000, 10000),
(194, 80, 7, 2, 200000, 400000),
(195, 80, 8, 2, 20000, 40000),
(196, 80, 9, 3, 200000, 600000),
(197, 80, 10, 2, 20000, 40000),
(198, 81, 6, 2, 10000, 20000),
(199, 81, 7, 1, 200000, 200000),
(200, 81, 8, 2, 20000, 40000),
(201, 81, 9, 2, 200000, 400000),
(202, 81, 10, 1, 20000, 20000),
(203, 82, 6, 1, 10000, 10000),
(204, 82, 7, 1, 200000, 200000),
(205, 82, 8, 1, 20000, 20000),
(206, 82, 9, 1, 200000, 200000),
(207, 82, 10, 1, 20000, 20000),
(208, 83, 6, 1, 10000, 10000),
(209, 83, 7, 1, 200000, 200000),
(210, 83, 9, 1, 200000, 200000),
(211, 83, 10, 1, 20000, 20000),
(212, 84, 1, 1, 6000, 6000),
(213, 84, 5, 1, 14000, 14000),
(214, 84, 7, 1, 200000, 200000),
(215, 84, 8, 1, 20000, 20000),
(216, 84, 9, 1, 200000, 200000),
(217, 84, 10, 1, 20000, 20000),
(218, 85, 1, 1, 6000, 6000),
(219, 85, 5, 1, 14000, 14000),
(220, 85, 6, 1, 10000, 10000),
(221, 85, 7, 1, 200000, 200000),
(222, 85, 8, 1, 20000, 20000),
(223, 85, 9, 1, 200000, 200000),
(224, 85, 10, 1, 20000, 20000),
(225, 86, 6, 1, 10000, 10000),
(226, 86, 7, 1, 200000, 200000),
(227, 86, 8, 1, 20000, 20000),
(228, 86, 9, 1, 200000, 200000),
(229, 86, 10, 1, 20000, 20000),
(230, 87, 1, 1, 6000, 6000),
(231, 87, 5, 1, 14000, 14000),
(232, 87, 7, 1, 200000, 200000),
(233, 87, 8, 1, 20000, 20000),
(234, 87, 9, 1, 200000, 200000),
(235, 87, 10, 1, 20000, 20000),
(236, 88, 1, 1, 6000, 6000),
(237, 88, 6, 1, 10000, 10000),
(238, 88, 8, 1, 20000, 20000),
(239, 88, 9, 1, 200000, 200000),
(240, 88, 10, 1, 20000, 20000),
(241, 89, 4, 1, 48000, 48000),
(242, 95, 2, 1, 5000, 5000),
(243, 95, 7, 1, 200000, 200000),
(244, 95, 9, 1, 200000, 200000),
(245, 95, 10, 1, 20000, 20000),
(246, 96, 7, 1, 200000, 200000),
(247, 96, 8, 1, 20000, 20000),
(248, 96, 9, 1, 200000, 200000);

-- --------------------------------------------------------

--
-- Table structure for table `tr_transaksi`
--

CREATE TABLE `tr_transaksi` (
  `id_transaksi` int NOT NULL,
  `id_penetapan_harga` int NOT NULL,
  `id_pengguna` int NOT NULL,
  `id_admin` int DEFAULT NULL,
  `kode_sewa` varchar(20) NOT NULL,
  `nomor_nota` varchar(50) DEFAULT NULL,
  `waktu_mulai` datetime DEFAULT NULL,
  `waktu_selesai` datetime DEFAULT NULL,
  `total_harga` int DEFAULT NULL,
  `metode_pembayaran` enum('QRIS','TUNAI') DEFAULT NULL,
  `opsi_pembayaran` enum('full','dp') DEFAULT NULL,
  `jumlah_dp` int DEFAULT NULL,
  `status_sewa` enum('ditahan','dikonfirmasi','dibatalkan','selesai') DEFAULT NULL,
  `status_pembayaran` enum('menunggu','dp','lunas','refund') DEFAULT NULL,
  `catatan_pembayaran` varchar(100) DEFAULT NULL,
  `sisa_bayar` int DEFAULT NULL,
  `uang_diterima` int DEFAULT NULL,
  `kembalian` int DEFAULT NULL,
  `sumber_booking` enum('Kasir','Online') DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `struk_created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `tr_transaksi`
--

INSERT INTO `tr_transaksi` (`id_transaksi`, `id_penetapan_harga`, `id_pengguna`, `id_admin`, `kode_sewa`, `nomor_nota`, `waktu_mulai`, `waktu_selesai`, `total_harga`, `metode_pembayaran`, `opsi_pembayaran`, `jumlah_dp`, `status_sewa`, `status_pembayaran`, `catatan_pembayaran`, `sisa_bayar`, `uang_diterima`, `kembalian`, `sumber_booking`, `updated_at`, `created_at`, `struk_created_at`) VALUES
(96, 53, 124, NULL, 'PNC-20260713-81ND', NULL, '2026-07-13 15:26:00', '2026-07-13 18:26:00', 43000, 'QRIS', 'full', NULL, 'selesai', 'lunas', NULL, 0, NULL, NULL, 'Kasir', '2026-07-13 13:26:36', '2026-07-13 07:27:41', NULL),
(97, 52, 125, NULL, 'PNC-20260713-SKFW', NULL, '2026-07-13 11:51:00', '2026-07-13 13:51:00', 24000, 'QRIS', 'full', NULL, 'selesai', 'lunas', NULL, 0, NULL, NULL, 'Kasir', '2026-07-13 07:49:00', '2026-07-13 07:47:19', '2026-07-13 07:47:19'),
(100, 47, 1, NULL, 'PNC-20260713-L5M0', NULL, '2026-07-17 22:00:00', '2026-07-18 00:00:00', 38000, NULL, 'full', NULL, 'dibatalkan', 'menunggu', 'Waktu pembayaran habis!', 0, NULL, NULL, 'Online', '2026-07-13 14:58:21', '2026-07-13 14:26:02', NULL),
(102, 46, 111, NULL, 'PNC-20260713-5TRJ', NULL, '2026-08-15 13:52:00', '2026-08-15 14:52:00', 20000, NULL, 'full', NULL, 'dibatalkan', 'refund', 'show', 0, NULL, NULL, 'Online', '2026-07-13 15:55:57', '2026-07-13 14:55:28', NULL),
(103, 54, 135, NULL, 'PNC-20260713-BL2Q', NULL, '2026-07-09 03:51:00', '2026-07-09 04:51:00', 20000, 'QRIS', 'full', NULL, 'selesai', 'lunas', '234rt', 0, NULL, NULL, 'Kasir', '2026-07-13 15:55:15', '2026-07-13 15:49:10', '2026-07-13 15:49:10'),
(104, 49, 1, NULL, 'PNC-20260713-W4Z3', NULL, '2026-07-14 14:00:00', '2026-07-14 16:00:00', 30000, NULL, 'full', NULL, 'dibatalkan', 'menunggu', 'gt', 0, NULL, NULL, 'Online', '2026-07-13 15:56:11', '2026-07-13 15:55:37', NULL),
(105, 47, 1, NULL, 'PNC-20260713-BFQU', NULL, '2026-08-01 14:00:00', '2026-08-01 16:00:00', 38000, 'TUNAI', 'dp', 10000, 'selesai', 'lunas', NULL, 0, NULL, NULL, 'Online', '2026-08-07 06:38:18', '2026-07-13 15:56:47', '2026-07-13 15:57:52'),
(106, 51, 136, NULL, 'PNC-20260713-RFJQ', NULL, '2026-07-13 04:08:00', '2026-07-13 05:08:00', 12000, 'TUNAI', 'full', NULL, 'selesai', 'lunas', NULL, 0, NULL, NULL, 'Kasir', '2026-07-14 03:43:29', '2026-07-13 16:09:04', '2026-07-13 16:09:04'),
(107, 48, 137, NULL, 'PNC-20260713-W4CA', NULL, '2026-07-13 20:17:00', '2026-07-13 21:17:00', 20000, 'TUNAI', 'full', NULL, 'selesai', 'lunas', NULL, 0, NULL, NULL, 'Kasir', '2026-07-14 03:43:29', '2026-07-13 16:13:54', '2026-07-13 16:13:54'),
(108, 46, 111, NULL, 'PNC-20260714-2MFN', NULL, '2026-07-14 16:30:00', '2026-07-14 17:30:00', 20000, NULL, 'full', NULL, 'dibatalkan', 'menunggu', 'Waktu pembayaran habis!', 0, NULL, NULL, 'Online', '2026-07-14 04:15:36', '2026-07-14 03:43:56', NULL),
(109, 46, 111, NULL, 'PNC-20260714-NNAS', NULL, '2026-07-14 19:00:00', '2026-07-14 20:00:00', 20000, NULL, 'dp', 10000, 'dibatalkan', 'dp', 'Waktu pembayaran habis!', 10000, NULL, NULL, 'Online', '2026-07-14 04:28:14', '2026-07-14 03:55:34', NULL),
(110, 46, 1, NULL, 'PNC-20260714-UK7G', NULL, '2026-07-14 21:00:00', '2026-07-14 22:00:00', 20000, NULL, 'dp', 10000, 'selesai', 'dp', NULL, 10000, NULL, NULL, 'Online', '2026-08-07 06:38:18', '2026-07-14 03:59:52', NULL),
(111, 48, 1, NULL, 'PNC-20260714-C6GR', NULL, '2026-07-14 19:30:00', '2026-07-14 20:30:00', 20000, NULL, 'dp', 5000, 'dibatalkan', 'dp', 'Waktu pembayaran habis!', 15000, NULL, NULL, 'Online', '2026-07-14 04:38:39', '2026-07-14 04:05:55', NULL),
(112, 47, 111, NULL, 'PNC-20260714-CLAS', NULL, '2026-07-14 14:00:00', '2026-07-14 16:00:00', 38000, NULL, 'dp', 20000, 'dibatalkan', 'dp', 'Waktu pembayaran habis!', 18000, NULL, NULL, 'Online', '2026-07-14 04:42:30', '2026-07-14 04:12:29', NULL),
(113, 47, 1, NULL, 'PNC-20260807-TRHB', NULL, '2026-08-09 13:00:00', '2026-08-09 15:00:00', 38000, NULL, 'full', NULL, 'selesai', 'lunas', NULL, 0, NULL, NULL, 'Online', '2026-08-12 08:50:14', '2026-08-07 13:05:17', NULL),
(114, 51, 138, NULL, 'PNC-20260807-TMXF', NULL, '2026-08-05 20:15:00', '2026-08-05 21:15:00', 12000, 'QRIS', 'full', NULL, 'selesai', 'lunas', NULL, 0, NULL, NULL, 'Kasir', '2026-08-07 13:25:55', '2026-08-07 13:14:40', '2026-08-07 13:14:40'),
(115, 59, 140, NULL, 'PNC-20260813-TMLO', NULL, '2026-08-14 14:47:00', '2026-08-14 16:47:00', 22000, 'TUNAI', 'full', NULL, 'selesai', 'lunas', 'master of puppet', 0, NULL, NULL, 'Kasir', '2026-08-16 18:09:21', '2026-08-13 04:44:40', '2026-08-13 04:44:40'),
(117, 46, 142, NULL, 'PNC-20260813-M1HY', NULL, '2026-08-15 14:59:00', '2026-08-15 15:59:00', 20000, 'QRIS', 'full', NULL, 'selesai', 'lunas', NULL, 0, NULL, NULL, 'Kasir', '2026-08-16 18:09:21', '2026-08-13 04:56:48', '2026-08-13 04:56:49'),
(118, 53, 143, 36, 'PNC-20260813-KP5R', NULL, '2026-08-17 15:07:00', '2026-08-17 18:07:00', 43000, 'TUNAI', 'full', NULL, 'selesai', 'lunas', NULL, 0, NULL, NULL, 'Kasir', '2026-08-17 12:51:16', '2026-08-13 05:04:12', '2026-08-13 05:04:12'),
(119, 59, 144, 36, 'PNC-20260813-XYAJ', NULL, '2026-08-29 17:06:00', '2026-08-29 19:06:00', 22000, 'QRIS', 'full', NULL, 'selesai', 'lunas', NULL, 0, NULL, NULL, 'Kasir', '2026-08-31 07:45:58', '2026-08-13 05:07:07', '2026-08-13 05:07:07'),
(120, 59, 145, 36, 'PNC-20260813-6NNB', NULL, '2026-08-15 16:11:00', '2026-08-15 18:11:00', 22000, 'TUNAI', 'full', NULL, 'selesai', 'lunas', 'tuturut, max verstappen tututurut', 0, NULL, NULL, 'Kasir', '2026-08-16 18:09:21', '2026-08-13 05:08:16', '2026-08-13 05:08:16'),
(122, 59, 147, 36, 'PNC-20260813-2AJW', NULL, '2026-08-15 23:45:00', '2026-08-16 01:45:00', 22000, 'QRIS', 'full', NULL, 'selesai', 'lunas', NULL, 0, NULL, NULL, 'Kasir', '2026-08-16 18:09:21', '2026-08-13 13:43:20', '2026-08-13 13:43:20'),
(123, 46, 1, 36, 'PNC-20260813-SGVH', NULL, '2026-08-14 21:00:00', '2026-08-14 22:00:00', 20000, NULL, 'full', NULL, 'selesai', 'lunas', NULL, 0, NULL, NULL, 'Online', '2026-08-16 18:09:21', '2026-08-13 13:45:42', NULL),
(124, 59, 149, 36, 'PNC-20260819-CZ0E', NULL, '2026-08-20 17:35:00', '2026-08-20 19:35:00', 22000, 'TUNAI', 'full', NULL, 'selesai', 'lunas', NULL, 0, NULL, NULL, 'Kasir', '2026-08-23 07:23:14', '2026-08-19 09:26:52', '2026-08-19 09:26:52'),
(125, 46, 51, 36, 'PNC-20260819-ETFR', NULL, '2026-08-19 22:00:00', '2026-08-19 23:00:00', 20000, NULL, 'full', NULL, 'selesai', 'lunas', NULL, 0, NULL, NULL, 'Online', '2026-08-23 07:23:14', '2026-08-19 14:35:22', NULL),
(126, 47, 51, 36, 'PNC-20260819-CH9I', NULL, '2026-08-20 14:30:00', '2026-08-20 16:30:00', 38000, NULL, 'dp', 18995, 'selesai', 'dp', NULL, 19005, NULL, NULL, 'Online', '2026-08-23 07:23:14', '2026-08-19 14:37:14', NULL),
(127, 58, 150, 36, 'PNC-20260823-OW4P', NULL, '2026-08-24 19:28:00', '2026-08-24 20:28:00', 10000, 'TUNAI', 'full', NULL, 'selesai', 'lunas', NULL, 0, NULL, NULL, 'Kasir', '2026-08-24 13:29:06', '2026-08-23 07:23:49', '2026-08-23 07:23:49'),
(128, 47, 151, 36, 'PNC-20260823-DBE9', NULL, '2026-08-29 19:37:00', '2026-08-29 21:37:00', 38000, 'TUNAI', 'full', NULL, 'selesai', 'lunas', NULL, 0, NULL, NULL, 'Kasir', '2026-08-31 07:45:58', '2026-08-23 07:37:59', '2026-08-23 07:37:59'),
(129, 59, 152, 36, 'PNC-20260823-RT7Y', NULL, '2026-08-23 18:47:00', '2026-08-23 20:47:00', 22000, 'TUNAI', 'full', NULL, 'selesai', 'lunas', NULL, 0, NULL, NULL, 'Kasir', '2026-08-23 14:09:48', '2026-08-23 07:43:56', '2026-08-23 07:43:56'),
(130, 50, 153, 36, 'PNC-20260823-M8KB', NULL, '2026-08-28 19:53:00', '2026-08-28 22:53:00', 42000, 'QRIS', 'full', NULL, 'selesai', 'lunas', NULL, 0, NULL, NULL, 'Kasir', '2026-08-31 07:45:58', '2026-08-23 07:48:51', '2026-08-23 07:48:51'),
(131, 57, 154, 36, 'PNC-20260823-ETXN', NULL, '2026-08-23 20:13:00', '2026-08-23 22:13:00', 22000, 'TUNAI', 'full', NULL, 'selesai', 'lunas', NULL, 0, NULL, NULL, 'Kasir', '2026-08-23 15:16:23', '2026-08-23 08:08:44', '2026-08-23 08:08:44'),
(132, 59, 155, 36, 'PNC-20260823-BTSR', NULL, '2026-09-04 20:24:00', '2026-09-04 22:24:00', 22000, 'QRIS', 'full', NULL, 'dikonfirmasi', 'lunas', NULL, 0, NULL, NULL, 'Kasir', '2026-08-23 08:19:54', '2026-08-23 08:19:54', '2026-08-23 08:19:54'),
(133, 59, 156, 36, 'PNC-20260823-HR7P', NULL, '2026-10-02 19:32:00', '2026-10-02 21:32:00', 22000, 'QRIS', 'full', NULL, 'dikonfirmasi', 'lunas', NULL, 0, NULL, NULL, 'Kasir', '2026-08-23 08:28:45', '2026-08-23 08:28:45', '2026-08-23 08:28:45'),
(134, 59, 158, 36, 'PNC-20260823-EKOO', NULL, '2026-08-28 01:29:00', '2026-08-28 03:29:00', 22000, 'QRIS', 'full', NULL, 'selesai', 'lunas', NULL, 0, NULL, NULL, 'Kasir', '2026-08-28 13:40:02', '2026-08-23 08:37:25', '2026-08-23 08:37:25'),
(135, 55, 160, 36, 'PNC-20260823-TKBA', NULL, '2026-08-28 23:00:00', '2026-08-29 01:00:00', 30000, 'QRIS', 'full', NULL, 'selesai', 'lunas', NULL, 0, NULL, NULL, 'Kasir', '2026-08-31 07:45:58', '2026-08-23 09:11:42', '2026-08-23 09:11:42'),
(136, 58, 161, 36, 'PNC-20260823-CYQN', NULL, '2026-08-25 21:23:00', '2026-08-25 22:23:00', 10000, 'TUNAI', 'full', NULL, 'selesai', 'lunas', 'Sama LC 1', 0, NULL, NULL, 'Kasir', '2026-08-26 13:33:18', '2026-08-23 13:23:12', '2026-08-23 13:23:12'),
(137, 59, 162, 36, 'PNC-20260823-D1QY', NULL, '2026-08-27 22:33:00', '2026-08-28 00:33:00', 22000, 'TUNAI', 'full', NULL, 'selesai', 'lunas', '-', 0, NULL, NULL, 'Kasir', '2026-08-28 13:40:02', '2026-08-23 13:34:03', '2026-08-23 13:34:03'),
(138, 56, 163, 36, 'PNC-20260823-QDSX', NULL, '2026-08-23 23:38:00', '2026-08-24 00:38:00', 10000, 'TUNAI', 'full', NULL, 'selesai', 'lunas', NULL, 0, NULL, NULL, 'Kasir', '2026-08-23 16:13:23', '2026-08-23 13:35:15', '2026-08-23 13:35:15'),
(139, 57, 162, 36, 'PNC-20260823-GGNE', NULL, '2026-08-23 12:49:00', '2026-08-23 14:49:00', 22000, 'TUNAI', 'full', NULL, 'selesai', 'lunas', '-', 0, NULL, NULL, 'Kasir', '2026-08-23 14:09:48', '2026-08-23 13:45:44', '2026-08-23 13:45:44'),
(140, 55, 164, 36, 'PNC-20260823-ZXN7', NULL, '2026-08-23 01:02:00', '2026-08-23 03:02:00', 30000, 'TUNAI', 'full', NULL, 'selesai', 'lunas', NULL, 0, NULL, NULL, 'Kasir', '2026-08-23 14:09:48', '2026-08-23 13:59:02', '2026-08-23 13:59:02'),
(141, 55, 166, 36, 'PNC-20260823-Z3L8', NULL, '2026-08-23 06:18:00', '2026-08-23 08:18:00', 30000, 'QRIS', 'full', NULL, 'selesai', 'lunas', NULL, 0, NULL, NULL, 'Kasir', '2026-08-23 14:09:48', '2026-08-23 14:09:39', '2026-08-23 14:09:39'),
(142, 57, 167, 36, 'PNC-20260823-D6CD', NULL, '2026-08-31 02:18:00', '2026-08-31 04:18:00', 22000, 'QRIS', 'full', NULL, 'selesai', 'lunas', NULL, 0, NULL, NULL, 'Kasir', '2026-08-31 07:45:58', '2026-08-23 14:12:26', '2026-08-23 14:12:26'),
(143, 59, 168, 36, 'PNC-20260823-F6ZM', NULL, '2026-08-23 00:19:00', '2026-08-23 02:19:00', 22000, 'TUNAI', 'full', NULL, 'selesai', 'lunas', NULL, 0, NULL, NULL, 'Kasir', '2026-08-23 14:17:24', '2026-08-23 14:16:37', '2026-08-23 14:16:37'),
(144, 59, 169, 36, 'PNC-20260823-8VZ6', NULL, '2026-09-01 12:29:00', '2026-09-01 14:29:00', 22000, 'TUNAI', 'full', NULL, 'dikonfirmasi', 'lunas', NULL, 0, NULL, NULL, 'Kasir', '2026-08-23 14:27:59', '2026-08-23 14:27:59', '2026-08-23 14:27:59'),
(145, 47, 170, 36, 'PNC-20260823-9JHC', NULL, '2026-08-23 01:38:00', '2026-08-23 03:38:00', 38000, 'QRIS', 'full', NULL, 'selesai', 'lunas', NULL, 0, NULL, NULL, 'Kasir', '2026-08-23 14:36:09', '2026-08-23 14:34:08', '2026-08-23 14:34:08'),
(146, 58, 171, 36, 'PNC-20260823-Q0FJ', NULL, '2026-08-26 14:54:00', '2026-08-26 15:54:00', 10000, 'TUNAI', 'full', NULL, 'selesai', 'lunas', '-', 0, NULL, NULL, 'Kasir', '2026-08-26 13:33:18', '2026-08-23 14:49:15', '2026-08-23 14:49:15'),
(147, 58, 172, 36, 'PNC-20260823-ZCVO', NULL, '2026-08-31 23:09:00', '2026-09-01 00:09:00', 10000, 'TUNAI', 'full', NULL, 'dikonfirmasi', 'lunas', '-', 0, NULL, NULL, 'Kasir', '2026-08-23 15:09:43', '2026-08-23 15:09:43', '2026-08-23 15:09:43'),
(148, 56, 173, 36, 'PNC-20260823-OD7E', NULL, '2026-09-02 22:17:00', '2026-09-02 23:17:00', 10000, 'TUNAI', 'full', NULL, 'dikonfirmasi', 'lunas', '-', 0, NULL, NULL, 'Kasir', '2026-08-23 15:12:42', '2026-08-23 15:12:42', '2026-08-23 15:12:42'),
(149, 47, 173, 36, 'PNC-20260823-GJGL', NULL, '2026-09-05 12:15:00', '2026-09-05 14:15:00', 38000, 'TUNAI', 'full', NULL, 'dikonfirmasi', 'lunas', '-', 0, NULL, NULL, 'Kasir', '2026-08-23 15:13:27', '2026-08-23 15:13:27', '2026-08-23 15:13:27'),
(150, 47, 174, 36, 'PNC-20260823-LHFQ', NULL, '2026-09-04 12:22:00', '2026-09-04 14:22:00', 38000, 'TUNAI', 'full', NULL, 'dikonfirmasi', 'lunas', '-', 0, NULL, NULL, 'Kasir', '2026-08-23 15:20:09', '2026-08-23 15:20:09', '2026-08-23 15:20:09'),
(151, 48, 176, 36, 'PNC-20260823-OMDT', NULL, '2026-08-23 22:26:00', '2026-08-23 23:26:00', 20000, 'TUNAI', 'full', NULL, 'selesai', 'lunas', '-', 0, NULL, NULL, 'Kasir', '2026-08-23 16:13:11', '2026-08-23 15:21:29', '2026-08-23 15:21:29'),
(152, 59, 162, 36, 'PNC-20260823-FZHU', NULL, '2026-09-04 15:27:00', '2026-09-04 17:27:00', 22000, 'TUNAI', 'full', NULL, 'dikonfirmasi', 'lunas', '-', 0, NULL, NULL, 'Kasir', '2026-08-23 15:24:27', '2026-08-23 15:24:27', '2026-08-23 15:24:27'),
(153, 47, 162, 36, 'PNC-20260823-21K3', NULL, '2026-09-11 13:27:00', '2026-09-11 15:27:00', 38000, 'TUNAI', 'full', NULL, 'dikonfirmasi', 'lunas', '-', 0, NULL, NULL, 'Kasir', '2026-08-23 15:27:11', '2026-08-23 15:27:11', '2026-08-23 15:27:11'),
(154, 59, 35, NULL, 'PNC-20260823-9EJK', NULL, '2026-08-23 22:30:00', '2026-08-24 00:30:00', 22000, 'TUNAI', 'full', NULL, 'selesai', 'lunas', NULL, 0, NULL, NULL, 'Online', '2026-08-24 13:25:12', '2026-08-23 15:29:02', '2026-08-23 15:32:07'),
(155, 47, 162, 36, 'PNC-20260823-JMII', NULL, '2026-09-10 22:29:00', '2026-09-11 00:29:00', 38000, 'TUNAI', 'full', NULL, 'dikonfirmasi', 'lunas', '-', 0, NULL, NULL, 'Kasir', '2026-08-23 15:29:32', '2026-08-23 15:29:32', '2026-08-23 15:29:32'),
(156, 46, 176, 36, 'PNC-20260823-WPHV', NULL, '2026-09-09 22:30:00', '2026-09-09 23:30:00', 20000, 'TUNAI', 'full', NULL, 'dikonfirmasi', 'lunas', '-', 0, NULL, NULL, 'Kasir', '2026-08-23 15:30:44', '2026-08-23 15:30:44', '2026-08-23 15:30:44'),
(157, 59, 176, 36, 'PNC-20260823-UNKY', NULL, '2026-09-08 22:32:00', '2026-09-09 00:32:00', 22000, 'TUNAI', 'full', NULL, 'dikonfirmasi', 'lunas', '-', 0, NULL, NULL, 'Kasir', '2026-08-23 15:32:37', '2026-08-23 15:32:37', '2026-08-23 15:32:37'),
(158, 47, 35, 36, 'PNC-20260823-LH4B', NULL, '2026-08-23 23:00:00', '2026-08-24 01:00:00', 38000, 'QRIS', 'full', NULL, 'selesai', 'lunas', NULL, 0, NULL, NULL, 'Online', '2026-08-23 16:12:59', '2026-08-23 15:43:34', '2026-08-23 15:43:52'),
(159, 59, 162, 36, 'PNC-20260823-XKOJ', NULL, '2026-09-17 22:44:00', '2026-09-18 00:44:00', 22000, 'TUNAI', 'full', NULL, 'dikonfirmasi', 'lunas', 'Memek Tembem 1', 0, NULL, NULL, 'Kasir', '2026-08-23 15:44:17', '2026-08-23 15:44:17', '2026-08-23 15:44:17'),
(160, 47, 35, NULL, 'PNC-20260823-D6WH', NULL, '2026-08-29 22:00:00', '2026-08-30 00:00:00', 38000, 'TUNAI', 'full', NULL, 'selesai', 'lunas', NULL, 0, NULL, NULL, 'Online', '2026-08-31 07:45:58', '2026-08-23 15:44:49', '2026-08-23 15:45:03'),
(161, 56, 177, 36, 'PNC-20260823-PIKK', NULL, '2026-09-04 14:51:00', '2026-09-04 15:51:00', 10000, 'TUNAI', 'full', NULL, 'dikonfirmasi', 'lunas', '-', 0, NULL, NULL, 'Kasir', '2026-08-23 15:51:53', '2026-08-23 15:51:53', '2026-08-23 15:51:53'),
(162, 47, 35, 36, 'PNC-20260823-PGBB', NULL, '2026-10-01 14:30:00', '2026-10-01 16:30:00', 38000, 'TUNAI', 'full', NULL, 'dikonfirmasi', 'lunas', NULL, 0, NULL, NULL, 'Online', '2026-08-23 15:57:02', '2026-08-23 15:56:29', '2026-08-23 15:57:02'),
(163, 47, 35, 36, 'PNC-20260823-WHNM', NULL, '2026-08-31 22:00:00', '2026-09-01 00:00:00', 38000, 'QRIS', 'full', NULL, 'dikonfirmasi', 'lunas', NULL, 0, NULL, NULL, 'Online', '2026-08-23 16:00:57', '2026-08-23 16:00:37', '2026-08-23 16:00:57'),
(164, 57, 178, 36, 'PNC-20260823-XWQY', NULL, '2026-09-10 23:07:00', '2026-09-11 01:07:00', 22000, 'TUNAI', 'full', NULL, 'dikonfirmasi', 'lunas', '-', 0, NULL, NULL, 'Kasir', '2026-08-23 16:08:03', '2026-08-23 16:08:03', '2026-08-23 16:08:03'),
(165, 55, 179, 36, 'PNC-20260823-BG3O', NULL, '2026-08-31 12:09:00', '2026-08-31 14:09:00', 30000, 'TUNAI', 'full', NULL, 'selesai', 'lunas', '-', 0, NULL, NULL, 'Kasir', '2026-08-23 16:12:47', '2026-08-23 16:09:46', '2026-08-23 16:09:46'),
(166, 55, 180, 36, 'PNC-20260823-IBVA', NULL, '2026-09-04 23:12:00', '2026-09-05 01:12:00', 30000, 'QRIS', 'full', NULL, 'dikonfirmasi', 'lunas', '-', 0, NULL, NULL, 'Kasir', '2026-08-23 16:12:34', '2026-08-23 16:12:34', '2026-08-23 16:12:34'),
(167, 47, 35, 36, 'PNC-20260823-C1GA', NULL, '2026-08-23 23:30:00', '2026-08-24 01:30:00', 38000, 'TUNAI', 'full', NULL, 'selesai', 'lunas', NULL, 0, NULL, NULL, 'Online', '2026-08-24 13:25:12', '2026-08-23 16:13:40', '2026-08-23 16:14:03'),
(168, 54, 181, 36, 'PNC-20260823-JHDR', NULL, '2026-09-05 23:19:00', '2026-09-06 00:19:00', 20000, 'TUNAI', 'full', NULL, 'dikonfirmasi', 'lunas', '-', 0, NULL, NULL, 'Kasir', '2026-08-23 16:15:22', '2026-08-23 16:15:22', '2026-08-23 16:15:22'),
(169, 57, 196, 36, 'PNC-20260823-PWEI', NULL, '2026-09-17 23:34:00', '2026-09-18 01:34:00', 22000, 'TUNAI', 'full', NULL, 'dikonfirmasi', 'lunas', '-', 0, NULL, NULL, 'Kasir', '2026-08-23 16:32:13', '2026-08-23 16:32:13', '2026-08-23 16:32:13'),
(174, 56, 201, 36, 'PNC-20260823-ISZE', NULL, '2026-09-15 23:34:00', '2026-09-16 00:34:00', 10000, 'TUNAI', 'full', NULL, 'dikonfirmasi', 'lunas', 'Titep Rokok Marlong isi 20', 0, NULL, NULL, 'Kasir', '2026-08-23 16:35:50', '2026-08-23 16:35:50', '2026-08-23 16:35:50'),
(175, 50, 202, 36, 'PNC-20260824-JBRU', NULL, '2026-08-24 00:36:00', '2026-08-24 03:36:00', 42000, 'QRIS', 'full', NULL, 'selesai', 'lunas', 'Menantang rasi bintang', 0, NULL, NULL, 'Kasir', '2026-08-24 13:34:34', '2026-08-24 13:32:20', '2026-08-24 13:32:20'),
(176, 57, 203, 36, 'PNC-20260824-JZHC', NULL, '2026-08-24 20:35:00', '2026-08-24 22:35:00', 22000, 'TUNAI', 'full', NULL, 'selesai', 'lunas', NULL, 0, NULL, NULL, 'Kasir', '2026-08-25 14:05:52', '2026-08-24 13:36:04', '2026-08-24 13:36:04'),
(177, 57, 173, 36, 'PNC-20260824-GV4E', NULL, '2026-09-09 12:55:00', '2026-09-09 14:55:00', 22000, 'TUNAI', 'full', NULL, 'dikonfirmasi', 'lunas', '-', 0, NULL, NULL, 'Kasir', '2026-08-24 13:51:42', '2026-08-24 13:51:42', '2026-08-24 13:51:42'),
(178, 47, 204, 36, 'PNC-20260824-21MN', NULL, '2026-09-17 22:39:00', '2026-09-18 00:39:00', 38000, 'QRIS', 'full', NULL, 'dikonfirmasi', 'lunas', NULL, 0, NULL, NULL, 'Kasir', '2026-08-24 14:39:55', '2026-08-24 14:39:55', '2026-08-24 14:39:55'),
(179, 59, 205, 36, 'PNC-20260825-QZPS', NULL, '2026-08-25 02:12:00', '2026-08-25 04:12:00', 22000, 'QRIS', 'full', NULL, 'selesai', 'lunas', NULL, 0, NULL, NULL, 'Kasir', '2026-08-25 14:59:02', '2026-08-25 14:06:39', '2026-08-25 14:06:39'),
(180, 47, 1, 36, 'PNC-20260825-P8YM', NULL, '2026-08-25 22:00:00', '2026-08-26 00:00:00', 38000, 'QRIS', 'full', NULL, 'selesai', 'lunas', NULL, 0, NULL, NULL, 'Online', '2026-08-25 15:01:21', '2026-08-25 14:59:42', '2026-08-25 15:00:18'),
(181, 47, 1, 36, 'PNC-20260825-4QEH', NULL, '2026-08-25 22:30:00', '2026-08-26 00:30:00', 38000, 'TUNAI', 'full', NULL, 'selesai', 'lunas', NULL, 0, NULL, NULL, 'Online', '2026-08-26 13:33:18', '2026-08-25 15:01:49', '2026-08-25 15:02:22'),
(182, 47, 1, 36, 'PNC-20260828-V27J', NULL, '2026-08-28 21:00:00', '2026-08-28 23:00:00', 38000, 'TUNAI', 'full', NULL, 'selesai', 'lunas', NULL, 0, NULL, NULL, 'Online', '2026-08-31 07:45:58', '2026-08-28 13:40:19', '2026-08-28 13:40:43'),
(183, 59, 211, 36, 'PNC-20260828-KPIY', NULL, '2026-08-28 02:23:00', '2026-08-28 04:23:00', 22000, 'QRIS', 'full', NULL, 'selesai', 'lunas', NULL, 0, NULL, NULL, 'Kasir', '2026-08-28 14:17:40', '2026-08-28 14:17:14', '2026-08-28 14:17:14'),
(184, 57, 212, 36, 'PNC-20260828-NI71', NULL, '2026-08-28 02:27:00', '2026-08-28 04:27:00', 22000, 'TUNAI', 'full', NULL, 'selesai', 'lunas', 'Lek siren', 0, NULL, NULL, 'Kasir', '2026-08-28 14:22:15', '2026-08-28 14:22:01', '2026-08-28 14:22:01'),
(192, 58, 220, 36, 'PNC-20260831-L9SJ', NULL, '2026-08-31 20:39:00', '2026-08-31 21:39:00', 10000, 'TUNAI', 'full', NULL, 'selesai', 'lunas', NULL, 0, NULL, NULL, 'Kasir', '2026-08-31 14:43:23', '2026-08-31 08:43:50', '2026-08-31 08:43:51'),
(193, 51, 221, 36, 'PNC-20260831-EBDT', NULL, '2026-08-31 19:53:00', '2026-08-31 20:53:00', 12000, 'TUNAI', 'full', NULL, 'selesai', 'lunas', NULL, 0, NULL, NULL, 'Kasir', '2026-08-31 13:53:36', '2026-08-31 08:49:41', '2026-08-31 08:49:41'),
(194, 53, 223, 36, 'PNC-20260831-48YN', '260831/PNC01/003', '2026-09-01 22:01:00', '2026-09-02 01:01:00', 43000, 'TUNAI', 'full', NULL, 'dikonfirmasi', 'lunas', NULL, 0, NULL, NULL, 'Kasir', '2026-08-31 08:55:22', '2026-08-31 08:55:22', '2026-08-31 08:55:22'),
(195, 59, 224, 36, 'PNC-20260831-XGHE', '260831/PNC01/004', '2026-08-27 20:01:00', '2026-08-27 22:01:00', 22000, 'TUNAI', 'full', NULL, 'selesai', 'lunas', NULL, 0, NULL, NULL, 'Kasir', '2026-08-31 08:58:08', '2026-08-31 08:57:29', '2026-08-31 08:57:29'),
(198, 59, 225, 36, 'PNC-20260831-KPXO', '260831/PNC01/006', '2026-08-29 00:46:00', '2026-08-29 02:46:00', 22000, 'TUNAI', 'full', NULL, 'selesai', 'lunas', NULL, 0, 50000, 28000, 'Kasir', '2026-08-31 14:46:51', '2026-08-31 14:44:51', '2026-08-31 14:44:51'),
(199, 57, 226, 36, 'PNC-20260831-YKTH', '260831/PNC01/008', '2026-09-03 21:52:00', '2026-09-03 23:52:00', 22000, 'TUNAI', 'full', NULL, 'dikonfirmasi', 'lunas', NULL, 0, 68000, 46000, 'Kasir', '2026-08-31 14:48:43', '2026-08-31 14:48:43', '2026-08-31 14:48:43'),
(200, 59, 227, 36, 'PNC-20260831-JVFL', '260831/PNC01/009', '2026-08-07 22:05:00', '2026-08-08 00:05:00', 22000, 'TUNAI', 'full', NULL, 'dikonfirmasi', 'lunas', NULL, 0, 100000, 78000, 'Kasir', '2026-08-31 15:06:00', '2026-08-31 15:06:00', '2026-08-31 15:06:00'),
(201, 59, 228, 36, 'PNC-20260831-PIUV', '260831/PNC01/010', '2026-08-31 00:09:00', '2026-08-31 02:09:00', 22000, 'TUNAI', 'full', NULL, 'dikonfirmasi', 'lunas', NULL, 0, 100000, 78000, 'Kasir', '2026-08-31 15:06:43', '2026-08-31 15:06:43', '2026-08-31 15:06:43'),
(202, 59, 230, 36, 'PNC-20260831-SNVQ', '260831/PNC01/011', '2026-08-31 05:15:00', '2026-08-31 07:15:00', 22000, 'TUNAI', 'full', NULL, 'dikonfirmasi', 'lunas', NULL, 0, 100000, 78000, 'Kasir', '2026-08-31 15:09:03', '2026-08-31 15:09:03', '2026-08-31 15:09:03');

-- --------------------------------------------------------

--
-- Table structure for table `t_galeri`
--

CREATE TABLE `t_galeri` (
  `id_galeri` int NOT NULL,
  `judul_foto` varchar(50) DEFAULT NULL,
  `deskripsi_foto` varchar(255) DEFAULT NULL,
  `kategori` enum('Reguler','Private - Gaming','Private - Nonton','Private - Karaoke','banner') DEFAULT NULL,
  `file_foto` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_active` tinyint DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `t_galeri`
--

INSERT INTO `t_galeri` (`id_galeri`, `judul_foto`, `deskripsi_foto`, `kategori`, `file_foto`, `created_at`, `updated_at`, `is_active`) VALUES
(4, 'Gaming Private Room', 'Lorem ipsum dolor sit amet consectetur adipiscing elit, soluta magnam reprehenderit reiciendis sed recusandae sit facilis nisi facilis saepe incididunt facere irure voluptas excepturi et.', 'Private - Nonton', 'galeri_1783429001.jpg', '2026-07-07 12:56:41', '2026-07-07 14:50:18', 1),
(5, 'Coba Galeri', 'Lorem ipsum dolor sit amet consectetur adipiscing elit, soluta magnam reprehenderit reiciendis sed recusandae sit facilis nisi facilis saepe incididunt facere irure voluptas excepturi et.', 'Reguler', 'galeri_1783847811.jpg', '2026-07-07 13:17:17', '2026-07-12 09:16:51', 0),
(11, 'Gamink', NULL, 'banner', 'images/banner/banner_1783481513_6a4dc4a92aa28.png', '2026-07-08 03:31:53', '2026-07-08 03:31:53', 1),
(12, 'Regular', NULL, 'banner', 'images/banner/banner_1783481732_6a4dc584aace8.png', '2026-07-08 03:35:32', '2026-07-08 03:50:07', 1),
(16, '000', NULL, 'banner', 'images/banner/banner_1783486138_6a4dd6bab2cbe.png', '2026-07-08 04:48:58', '2026-07-09 08:51:32', 1);

-- --------------------------------------------------------

--
-- Table structure for table `t_video`
--

CREATE TABLE `t_video` (
  `id_video` int NOT NULL,
  `thumbnail` varchar(255) DEFAULT NULL,
  `link-video` varchar(255) DEFAULT NULL,
  `is_active` tinyint DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `t_video`
--

INSERT INTO `t_video` (`id_video`, `thumbnail`, `link-video`, `is_active`, `created_at`, `updated_at`) VALUES
(5, 'video_1783485339.png', 'https://youtu.be/uqg9VQ0OYrk?si=Exd0lHdsq6Fzp94P', 1, '2026-07-08 04:35:39', '2026-07-09 06:41:43'),
(7, 'video_1783573094.jpg', 'https://www.youtube.com/watch?v=LXb3EKWsInQ', 1, '2026-07-09 04:34:32', '2026-07-09 06:46:20');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id_pengguna` int NOT NULL,
  `nama_pengguna` varchar(50) DEFAULT NULL,
  `email` varchar(30) DEFAULT NULL,
  `password` varchar(60) DEFAULT NULL,
  `no_hp` varchar(15) DEFAULT NULL,
  `status` tinyint DEFAULT NULL,
  `google_id` varchar(255) DEFAULT NULL,
  `alamat` varchar(50) DEFAULT NULL,
  `role` enum('pelanggan','admin','superadmin') DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id_pengguna`, `nama_pengguna`, `email`, `password`, `no_hp`, `status`, `google_id`, `alamat`, `role`, `updated_at`, `created_at`, `email_verified_at`, `remember_token`) VALUES
(1, 'Fahrizal Nur S', 'rizalkhadam@gmail.com', '$2y$12$4TvbKgCy77arVeqRIS2oh.RmKd37/yeK6JpxHzrO275IsLQ21ZfZS', '081234567891', 1, '104918399775433644622', 'Ngawi', 'pelanggan', '2026-05-29 12:57:04', '2026-04-30 04:56:40', '2026-04-30 04:56:40', NULL),
(2, 'Irfan Listiawan', 'playnchillmadiun@gmail.com', '$2y$12$TCVdpJ3UaoUTpzaJvmfLFupZFYsdaTCtJa/F0A0tyOBKetJBc2DIG', NULL, 1, NULL, NULL, 'superadmin', '2026-05-23 06:53:43', '2026-04-30 12:04:53', '2026-04-30 18:46:04', NULL),
(35, 'satoruu', 'fahrizalnurs11@gmail.com', '$2y$12$9b6ObwsXX0.owGC2THcm8etFYX/bTLyGaY8drua4Q6tnD8tzqAIM.', '0987890000', 1, '107610001289998662110', NULL, 'pelanggan', '2026-05-15 05:50:39', '2026-05-11 04:33:41', '2026-05-11 06:47:30', NULL),
(36, 'Lampard', 'admin1@gmail.com', '$2y$12$GUGk3.8K5F6Us48dxazzveD3LelzBEyOwFvr/1GHaunX4VyF/0BWm', '192209877890', 1, NULL, 'Madiun', 'admin', '2026-08-27 14:10:17', '2026-05-15 02:04:03', '2026-05-15 02:04:03', NULL),
(41, 'Lando Norris', 'acefakeboy@gmail.com', '$2y$12$xHQz0HvLtDhFaSWd8bXK0.0gPEz14nrb0na2BYwk6/WzqXQBv0s.y', NULL, 1, '102513410322077425695', 'ngawi', 'pelanggan', '2026-05-29 01:22:10', '2026-05-23 05:37:37', '2026-05-29 01:22:10', NULL),
(42, 'Bim123', 'bimoae07@gmail.com', '$2y$12$pxOcQ91uhEMT8hs1W5ztu.I0wFUWvAHqPDit4W4kk5xGR7Lb70UFa', '089504778686', 1, '114556008056314760840', 'Jl. Gajah Suro', 'pelanggan', '2026-06-03 03:09:58', '2026-05-23 05:41:43', '2026-05-23 05:41:43', 'ryc5QccGaBaS3QPIZD9rPJFMVKXn0Jr7dIgXivSkXCTy4aL1kojEgV6wDoM1'),
(43, 'anomali xyz', 'anomalixyz276@gmail.com', '$2y$12$ooagO/YHXSEyKR081qIdEu1P67Cl90i.52Bnk4mogIJisMlhMBlyq', NULL, 1, '108214561038948718284', NULL, 'pelanggan', '2026-05-23 05:46:36', '2026-05-23 05:46:36', '2026-05-23 05:46:36', NULL),
(44, 'Adi', 'adi315429@gmail.com', '$2y$12$20ejLmIQkitRDp7rprGd4.JsersYp7hRHrdvORaRHBL5LIB38.jBy', '082335436100', 1, '113199573483638130332', NULL, 'pelanggan', '2026-05-29 02:29:29', '2026-05-28 16:02:34', '2026-05-29 02:29:29', NULL),
(45, 'Frenzy Yagami', 'frenzyyagami@gmail.com', '$2y$12$.QANl3OGXfBEIPLrf8.O4evphlDifphkBypUGJ/aCel2gr6Sutl3u', NULL, 1, '109310907528814785028', NULL, 'pelanggan', '2026-05-29 01:24:39', '2026-05-29 01:24:39', '2026-05-29 01:24:39', NULL),
(46, 'Onigiri Bentoo', 'onigiribentoo@gmail.com', '$2y$12$gkanJzQYuqGVlYIRwzuiieuMdAqYPdWM0F6lmE8uN6aEUOWtLI.nq', NULL, 1, '106216430826405353335', NULL, 'pelanggan', '2026-06-07 00:19:24', '2026-06-07 00:19:24', '2026-06-07 00:19:24', NULL),
(47, 'Yumi Sw', 'yumisw00@gmail.com', '$2y$12$mrlcBpMeQ/kISU6ExUV1YODg6JUywOMMebFsvZldd0T0v5/Haj0Gm', NULL, 1, '111509466755565046907', NULL, 'pelanggan', '2026-06-08 15:55:16', '2026-06-08 15:55:16', '2026-06-08 15:55:16', NULL),
(48, 'fia', 'fiadesica@gmail.com', '$2y$12$jdyVNyh2mK.A/aSvd3xG/eGhW8GRoHuzEvKBeicPpxI/seM0GsTwS', '085743861489', 1, NULL, NULL, 'pelanggan', '2026-06-12 08:34:20', '2026-06-12 08:33:45', '2026-06-12 08:34:20', NULL),
(49, 'super', 'superadmin@gmail.com', '$2y$12$zCnFvA11LQ/JgW08CDHq0.jg36ziuGE2/FlZT71W8T3C3zDbACA0C', NULL, 1, NULL, NULL, 'superadmin', '2026-08-07 06:39:14', '2026-07-07 07:20:13', '2026-07-07 07:20:13', NULL),
(50, 'George Russel', 'verstappen@gmail.com', '$2y$12$zeKLMQ8h9KkVBvvKEvr80u6eaNtcGVW91KfC1//SZ2QdY7Ctfn3hW', NULL, 1, NULL, NULL, 'admin', '2026-08-19 13:58:24', '2026-07-07 07:20:33', '2026-07-07 07:20:33', NULL),
(51, 'Martinez', 'mmk2@gmail.com', '$2y$12$9rB1Ie0KCNpMs3Q8aG2wT.LhNEmW01Oz1ukDlB/ysbsz1NcHf4u6y', '0812345678911', 1, NULL, NULL, 'pelanggan', '2026-07-08 07:48:58', '2026-07-08 03:07:27', '2026-07-08 03:07:27', NULL),
(52, 'gojo satoru', NULL, NULL, '19090', 1, NULL, NULL, 'pelanggan', '2026-08-07 06:40:51', '2026-07-09 04:17:26', NULL, NULL),
(53, 'Alex putellas', NULL, NULL, NULL, 1, NULL, NULL, 'pelanggan', '2026-08-07 06:40:57', '2026-07-09 08:44:23', NULL, NULL),
(54, 'gojo satoru', NULL, NULL, '1919', NULL, NULL, NULL, 'pelanggan', '2026-07-09 09:08:58', '2026-07-09 09:08:58', NULL, NULL),
(55, 'leclerc', NULL, NULL, NULL, NULL, NULL, NULL, 'pelanggan', '2026-07-09 09:15:31', '2026-07-09 09:15:31', NULL, NULL),
(57, 'Rafid Andino', 'rafidsanz77@gmail.com', '$2y$12$DAz0e8moENgYyhtAyRdbTujmbCrSpwzmFgOWHKHpRr0Aiiah3rPWy', '085755791370', 0, '108646718467781163748', NULL, 'pelanggan', '2026-08-07 06:40:37', '2026-07-09 16:45:12', '2026-07-09 16:45:11', NULL),
(74, 'mmg', NULL, NULL, NULL, NULL, NULL, NULL, 'pelanggan', '2026-07-10 14:50:20', '2026-07-10 14:50:20', NULL, NULL),
(75, 'Alex putellas', NULL, NULL, NULL, NULL, NULL, NULL, 'pelanggan', '2026-07-10 14:50:43', '2026-07-10 14:50:43', NULL, NULL),
(76, 'the man who can\'t be moved', NULL, NULL, NULL, NULL, NULL, NULL, 'pelanggan', '2026-07-10 14:58:20', '2026-07-10 14:58:20', NULL, NULL),
(77, 'leclerc', NULL, NULL, '1919111', NULL, NULL, NULL, 'pelanggan', '2026-07-10 15:07:22', '2026-07-10 15:07:22', NULL, NULL),
(78, 'alexandraaa', NULL, NULL, NULL, NULL, NULL, NULL, 'pelanggan', '2026-07-10 15:09:46', '2026-07-10 15:09:46', NULL, NULL),
(95, 'Bottas', NULL, NULL, NULL, NULL, NULL, NULL, 'pelanggan', '2026-07-10 16:43:37', '2026-07-10 16:43:37', NULL, NULL),
(96, 'L2', NULL, NULL, NULL, NULL, NULL, NULL, 'pelanggan', '2026-07-10 17:01:55', '2026-07-10 17:01:55', NULL, NULL),
(98, 'leclerc', NULL, NULL, NULL, NULL, NULL, NULL, 'pelanggan', '2026-07-10 17:03:31', '2026-07-10 17:03:31', NULL, NULL),
(100, 'Alex putellas', NULL, NULL, '12345678', NULL, NULL, NULL, 'pelanggan', '2026-07-10 17:14:25', '2026-07-10 17:14:25', NULL, NULL),
(101, 'Bang E', NULL, NULL, NULL, NULL, NULL, NULL, 'pelanggan', '2026-07-10 17:22:53', '2026-07-10 17:22:53', NULL, NULL),
(103, 'booking', NULL, NULL, NULL, NULL, NULL, NULL, 'pelanggan', '2026-07-10 17:32:04', '2026-07-10 17:32:04', NULL, NULL),
(104, 'Alex putellas', NULL, NULL, NULL, NULL, NULL, NULL, 'pelanggan', '2026-07-10 17:33:13', '2026-07-10 17:33:13', NULL, NULL),
(105, 'mama', NULL, NULL, NULL, NULL, NULL, NULL, 'pelanggan', '2026-07-10 17:35:01', '2026-07-10 17:35:01', NULL, NULL),
(106, 'Alex putellas', NULL, NULL, '0985', NULL, NULL, NULL, 'pelanggan', '2026-07-11 03:27:07', '2026-07-11 03:27:07', NULL, NULL),
(107, 'kfe', NULL, NULL, NULL, NULL, NULL, NULL, 'pelanggan', '2026-07-11 03:36:42', '2026-07-11 03:36:42', NULL, NULL),
(109, 'leclerc', NULL, NULL, NULL, NULL, NULL, NULL, 'pelanggan', '2026-07-11 03:50:47', '2026-07-11 03:50:47', NULL, NULL),
(110, 'Lando', NULL, NULL, NULL, NULL, NULL, NULL, 'pelanggan', '2026-07-11 03:55:05', '2026-07-11 03:55:05', NULL, NULL),
(111, 'TI_2C_Rafid Dwi Putra Andino', 'rizaluchiha77@gmail.com', '$2y$12$Y6xRdGFZS0LOy8TlSLSh9OF4uNSQzgbwZlzjwnBLYzDVZYRfrYWSa', '083132364231', 0, '108515450866870551146', NULL, 'pelanggan', '2026-08-07 06:40:41', '2026-07-11 04:03:26', '2026-07-11 04:03:26', NULL),
(112, 'Bang Eo', NULL, NULL, NULL, NULL, NULL, NULL, 'pelanggan', '2026-07-11 04:33:15', '2026-07-11 04:33:15', NULL, NULL),
(113, 'leclerc', NULL, NULL, '12314', NULL, NULL, NULL, 'pelanggan', '2026-07-11 04:48:50', '2026-07-11 04:48:50', NULL, NULL),
(115, 'mamao', NULL, NULL, NULL, NULL, NULL, NULL, 'pelanggan', '2026-07-11 04:51:04', '2026-07-11 04:51:04', NULL, NULL),
(116, 'Alex putellaso', NULL, NULL, '12211', NULL, NULL, NULL, 'pelanggan', '2026-07-12 13:41:22', '2026-07-12 13:41:22', NULL, NULL),
(119, 'Bang E', NULL, NULL, NULL, NULL, NULL, NULL, 'pelanggan', '2026-07-12 15:50:14', '2026-07-12 15:50:14', NULL, NULL),
(120, 'BackStreet Boys', NULL, NULL, NULL, NULL, NULL, NULL, 'pelanggan', '2026-07-13 06:15:54', '2026-07-13 06:15:54', NULL, NULL),
(121, 'Drake', NULL, NULL, NULL, NULL, NULL, NULL, 'pelanggan', '2026-07-13 06:19:35', '2026-07-13 06:19:35', NULL, NULL),
(123, 'Bang E', NULL, NULL, NULL, NULL, NULL, NULL, 'pelanggan', '2026-07-13 06:58:19', '2026-07-13 06:58:19', NULL, NULL),
(124, 'Dua Lipa', NULL, NULL, NULL, NULL, NULL, NULL, 'pelanggan', '2026-07-13 07:27:41', '2026-07-13 07:27:41', NULL, NULL),
(125, 'One Call Away', NULL, NULL, NULL, NULL, NULL, NULL, 'pelanggan', '2026-07-13 07:47:19', '2026-07-13 07:47:19', NULL, NULL),
(134, 'mekmo', NULL, NULL, NULL, NULL, NULL, NULL, 'pelanggan', '2026-07-13 09:56:00', '2026-07-13 09:56:00', NULL, NULL),
(135, 'doa lamat-lamat', 'a@gmail.com', NULL, '1234567', NULL, NULL, NULL, 'pelanggan', '2026-08-07 06:45:03', '2026-07-13 15:49:10', NULL, NULL),
(136, 'leclerc', NULL, NULL, NULL, NULL, NULL, NULL, 'pelanggan', '2026-07-13 16:09:04', '2026-07-13 16:09:04', NULL, NULL),
(137, 'dave mustaine', 'daveheadache@gmail.com', NULL, NULL, NULL, NULL, NULL, 'pelanggan', '2026-08-07 06:40:06', '2026-07-13 16:13:54', NULL, NULL),
(138, 'leclerc', NULL, NULL, NULL, NULL, NULL, NULL, 'pelanggan', '2026-08-07 13:14:40', '2026-08-07 13:14:40', NULL, NULL),
(139, 'George Russel', 'f@gmail.com', '$2y$12$pEYS0ZhKHLASLg3uiEoEMuCsl9y7ADyJzR8tGAQ8mQ6N3csXw6X6S', NULL, 0, NULL, NULL, 'superadmin', '2026-08-19 14:20:48', '2026-08-07 13:47:15', '2026-08-07 13:47:15', NULL),
(140, 'James', 'jameshetfield@gmail.com', NULL, NULL, NULL, NULL, NULL, 'pelanggan', '2026-08-13 04:44:40', '2026-08-13 04:44:40', NULL, NULL),
(142, 'Mr. Morgan', 'arthur@gmail.com', NULL, NULL, NULL, NULL, NULL, 'pelanggan', '2026-08-13 04:56:48', '2026-08-13 04:56:48', NULL, NULL),
(143, 'duth', NULL, NULL, NULL, NULL, NULL, NULL, 'pelanggan', '2026-08-13 05:04:12', '2026-08-13 05:04:12', NULL, NULL),
(144, 'mamamacriminal', NULL, NULL, NULL, NULL, NULL, NULL, 'pelanggan', '2026-08-13 05:07:07', '2026-08-13 05:07:07', NULL, NULL),
(145, 'Josh Verstappen', NULL, NULL, NULL, NULL, NULL, NULL, 'pelanggan', '2026-08-13 05:08:16', '2026-08-13 05:08:16', NULL, NULL),
(147, 'Joshua', NULL, NULL, NULL, NULL, NULL, NULL, 'pelanggan', '2026-08-13 13:43:20', '2026-08-13 13:43:20', NULL, NULL),
(148, 'leclerc00', NULL, NULL, NULL, NULL, NULL, NULL, 'pelanggan', '2026-08-13 13:44:18', '2026-08-13 13:44:18', NULL, NULL),
(149, 'Bearnut', NULL, NULL, NULL, NULL, NULL, NULL, 'pelanggan', '2026-08-19 09:26:52', '2026-08-19 09:26:52', NULL, NULL),
(150, 'mamam', NULL, NULL, NULL, NULL, NULL, NULL, 'pelanggan', '2026-08-23 07:23:49', '2026-08-23 07:23:49', NULL, NULL),
(151, 'dutch van der linde', NULL, NULL, NULL, NULL, NULL, NULL, 'pelanggan', '2026-08-23 07:37:59', '2026-08-23 07:37:59', NULL, NULL),
(152, 'Satoru kw', NULL, NULL, NULL, NULL, NULL, NULL, 'pelanggan', '2026-08-23 07:43:55', '2026-08-23 07:43:55', NULL, NULL),
(153, 'Concrete Angel', NULL, NULL, NULL, NULL, NULL, NULL, 'pelanggan', '2026-08-23 07:48:51', '2026-08-23 07:48:51', NULL, NULL),
(154, 'Madison', NULL, NULL, NULL, NULL, NULL, NULL, 'pelanggan', '2026-08-23 08:08:44', '2026-08-23 08:08:44', NULL, NULL),
(155, '9 naga', NULL, NULL, NULL, NULL, NULL, NULL, 'pelanggan', '2026-08-23 08:19:54', '2026-08-23 08:19:54', NULL, NULL),
(156, 'Antony Gordon - kidul kali', NULL, NULL, NULL, NULL, NULL, NULL, 'pelanggan', '2026-08-23 08:28:45', '2026-08-23 08:28:45', NULL, NULL),
(158, 'Sir Alex Ferguson', NULL, NULL, NULL, NULL, NULL, NULL, 'pelanggan', '2026-08-23 08:37:25', '2026-08-23 08:37:25', NULL, NULL),
(160, 'nanas', NULL, NULL, NULL, NULL, NULL, NULL, 'pelanggan', '2026-08-23 09:11:42', '2026-08-23 09:11:42', NULL, NULL),
(161, 'Jump Boat', NULL, NULL, '0987654345', NULL, NULL, NULL, 'pelanggan', '2026-08-23 13:23:12', '2026-08-23 13:23:12', NULL, NULL),
(162, 'Sucipto', NULL, NULL, '09876543212', NULL, NULL, NULL, 'pelanggan', '2026-08-23 13:34:03', '2026-08-23 13:34:03', NULL, NULL),
(163, 'Nanas Bernat', NULL, NULL, NULL, NULL, NULL, NULL, 'pelanggan', '2026-08-23 13:35:15', '2026-08-23 13:35:15', NULL, NULL),
(164, 'Bearnut', NULL, NULL, NULL, NULL, NULL, NULL, 'pelanggan', '2026-08-23 13:59:02', '2026-08-23 13:59:02', NULL, NULL),
(166, 'bearnuttt e nya satu', NULL, NULL, NULL, NULL, NULL, NULL, 'pelanggan', '2026-08-23 14:09:39', '2026-08-23 14:09:39', NULL, NULL),
(167, 'Bearrnus nus loml xixixi', NULL, NULL, NULL, NULL, NULL, NULL, 'pelanggan', '2026-08-23 14:12:26', '2026-08-23 14:12:26', NULL, NULL),
(168, 'Bernadya', NULL, NULL, NULL, NULL, NULL, NULL, 'pelanggan', '2026-08-23 14:16:37', '2026-08-23 14:16:37', NULL, NULL),
(169, 'kachuk', 'kachuk@gmail.com', NULL, '09876545234', NULL, NULL, NULL, 'pelanggan', '2026-08-23 14:27:59', '2026-08-23 14:27:59', NULL, NULL),
(170, 'Axl Rose KW', NULL, NULL, NULL, NULL, NULL, NULL, 'pelanggan', '2026-08-23 14:34:07', '2026-08-23 14:34:07', NULL, NULL),
(171, 'Toretto', 'dom@gmail.com', NULL, '0987655678', NULL, NULL, NULL, 'pelanggan', '2026-08-23 14:49:15', '2026-08-23 14:49:15', NULL, NULL),
(172, 'Heavy Game', NULL, NULL, '098765234', NULL, NULL, NULL, 'pelanggan', '2026-08-23 15:09:43', '2026-08-23 15:09:43', NULL, NULL),
(173, 'Octopus', NULL, NULL, '098765432123', NULL, NULL, NULL, 'pelanggan', '2026-08-23 15:12:42', '2026-08-23 15:12:42', NULL, NULL),
(174, 'Mantra', NULL, NULL, '0987654321234', NULL, NULL, NULL, 'pelanggan', '2026-08-23 15:20:09', '2026-08-23 15:20:09', NULL, NULL),
(176, 'Eclipse', NULL, NULL, '0987654321', NULL, NULL, NULL, 'pelanggan', '2026-08-23 15:21:29', '2026-08-23 15:21:29', NULL, NULL),
(177, 'Tadji', NULL, NULL, '098765432', NULL, NULL, NULL, 'pelanggan', '2026-08-23 15:51:53', '2026-08-23 15:51:53', NULL, NULL),
(178, 'Paimen', NULL, NULL, '123456789', NULL, NULL, NULL, 'pelanggan', '2026-08-23 16:08:03', '2026-08-23 16:08:03', NULL, NULL),
(179, 'Prapto', NULL, NULL, NULL, NULL, NULL, NULL, 'pelanggan', '2026-08-23 16:09:46', '2026-08-23 16:09:46', NULL, NULL),
(180, 'Tukul', NULL, NULL, NULL, NULL, NULL, NULL, 'pelanggan', '2026-08-23 16:12:34', '2026-08-23 16:12:34', NULL, NULL),
(181, 'Tomblok', NULL, NULL, NULL, NULL, NULL, NULL, 'pelanggan', '2026-08-23 16:15:22', '2026-08-23 16:15:22', NULL, NULL),
(196, 'Adji', NULL, NULL, '09876543222', NULL, NULL, NULL, 'pelanggan', '2026-08-23 16:32:13', '2026-08-23 16:32:13', NULL, NULL),
(201, 'Rafid Dwi Putra Andino', NULL, NULL, '087863963135', NULL, NULL, NULL, 'pelanggan', '2026-08-23 16:35:50', '2026-08-23 16:35:50', NULL, NULL),
(202, 'Lek sirin', NULL, NULL, NULL, NULL, NULL, NULL, 'pelanggan', '2026-08-24 13:32:20', '2026-08-24 13:32:20', NULL, NULL),
(203, 'Kasno', NULL, NULL, NULL, NULL, NULL, NULL, 'pelanggan', '2026-08-24 13:36:04', '2026-08-24 13:36:04', NULL, NULL),
(204, 'Mas Amba', NULL, NULL, NULL, NULL, NULL, NULL, 'pelanggan', '2026-08-24 14:39:55', '2026-08-24 14:39:55', NULL, NULL),
(205, 'Suraji', NULL, NULL, NULL, NULL, NULL, NULL, 'pelanggan', '2026-08-25 14:06:39', '2026-08-25 14:06:39', NULL, NULL),
(207, 'Bakekok', NULL, NULL, '0987654', NULL, NULL, NULL, 'pelanggan', '2026-08-26 14:30:36', '2026-08-26 14:30:36', NULL, NULL),
(208, 'Sulastri', NULL, NULL, '098765432122', NULL, NULL, NULL, 'pelanggan', '2026-08-26 14:35:01', '2026-08-26 14:35:01', NULL, NULL),
(209, 'Paijo', NULL, NULL, '09876543234', NULL, NULL, NULL, 'pelanggan', '2026-08-26 14:42:39', '2026-08-26 14:42:39', NULL, NULL),
(210, 'Wassalom', NULL, NULL, '09876543345', NULL, NULL, NULL, 'pelanggan', '2026-08-26 15:08:09', '2026-08-26 15:08:09', NULL, NULL),
(211, 'OODK', NULL, NULL, NULL, NULL, NULL, NULL, 'pelanggan', '2026-08-28 14:17:14', '2026-08-28 14:17:14', NULL, NULL),
(212, 'FAridSteveee', NULL, NULL, NULL, NULL, NULL, NULL, 'pelanggan', '2026-08-28 14:22:01', '2026-08-28 14:22:01', NULL, NULL),
(220, 'Siriiin Farid Stevy Asta', NULL, NULL, NULL, NULL, NULL, NULL, 'pelanggan', '2026-08-31 08:43:50', '2026-08-31 08:43:50', NULL, NULL),
(221, 'Quintaro', NULL, NULL, NULL, NULL, NULL, NULL, 'pelanggan', '2026-08-31 08:49:41', '2026-08-31 08:49:41', NULL, NULL),
(223, 'Almost Rock Barely Art', NULL, NULL, NULL, NULL, NULL, NULL, 'pelanggan', '2026-08-31 08:55:22', '2026-08-31 08:55:22', NULL, NULL),
(224, 'Hindia', NULL, NULL, NULL, NULL, NULL, NULL, 'pelanggan', '2026-08-31 08:57:29', '2026-08-31 08:57:29', NULL, NULL),
(225, 'Sirim fardi', NULL, NULL, NULL, NULL, NULL, NULL, 'pelanggan', '2026-08-31 14:44:51', '2026-08-31 14:44:51', NULL, NULL),
(226, 'oiuytre', NULL, NULL, NULL, NULL, NULL, NULL, 'pelanggan', '2026-08-31 14:48:43', '2026-08-31 14:48:43', NULL, NULL),
(227, 'udin', NULL, NULL, NULL, NULL, NULL, NULL, 'pelanggan', '2026-08-31 15:06:00', '2026-08-31 15:06:00', NULL, NULL),
(228, 'petot', NULL, NULL, NULL, NULL, NULL, NULL, 'pelanggan', '2026-08-31 15:06:43', '2026-08-31 15:06:43', NULL, NULL),
(230, 'pante', NULL, NULL, NULL, NULL, NULL, NULL, 'pelanggan', '2026-08-31 15:09:03', '2026-08-31 15:09:03', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ms_paket`
--
ALTER TABLE `ms_paket`
  ADD PRIMARY KEY (`id_paket`),
  ADD KEY `fk_ms_paket_ms_sub_kategori_paket1_idx` (`ms_sub_kategori_paket_id_sub_kategori_paket`);

--
-- Indexes for table `ms_pengaturan`
--
ALTER TABLE `ms_pengaturan`
  ADD PRIMARY KEY (`id_pengaturan`);

--
-- Indexes for table `ms_permainan`
--
ALTER TABLE `ms_permainan`
  ADD PRIMARY KEY (`id_permainan`);

--
-- Indexes for table `ms_produk`
--
ALTER TABLE `ms_produk`
  ADD PRIMARY KEY (`id_produk`),
  ADD KEY `fk_ms_produk_ms_sub_kategori_produk1_idx` (`ms_sub_kategori_produk_id_sub_kategori_produk`);

--
-- Indexes for table `ms_ruangan`
--
ALTER TABLE `ms_ruangan`
  ADD PRIMARY KEY (`id_ruangan`),
  ADD UNIQUE KEY `nama_ruangan_UNIQUE` (`nama_ruangan`);

--
-- Indexes for table `ms_ruangan_ms_permainan`
--
ALTER TABLE `ms_ruangan_ms_permainan`
  ADD PRIMARY KEY (`id_ruangan`,`id_permainan`),
  ADD KEY `fk_ms_ruangan_has_ms_game_ms_game1_idx` (`id_permainan`),
  ADD KEY `fk_ms_ruangan_has_ms_game_ms_ruangan1_idx` (`id_ruangan`);

--
-- Indexes for table `ms_sub_kategori_paket`
--
ALTER TABLE `ms_sub_kategori_paket`
  ADD PRIMARY KEY (`id_sub_kategori_paket`);

--
-- Indexes for table `ms_sub_kategori_produk`
--
ALTER TABLE `ms_sub_kategori_produk`
  ADD PRIMARY KEY (`id_sub_kategori_produk`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `penetapan_harga`
--
ALTER TABLE `penetapan_harga`
  ADD PRIMARY KEY (`id_penetapan_harga`),
  ADD KEY `fk_ms_pricing_ms_ruangan1_idx` (`id_ruangan`),
  ADD KEY `fk_ms_pricing_ms_paket1_idx` (`id_paket`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tr_nomor_urut_harian`
--
ALTER TABLE `tr_nomor_urut_harian`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tr_nomor_urut_harian_tanggal_unique` (`tanggal`);

--
-- Indexes for table `tr_pos`
--
ALTER TABLE `tr_pos`
  ADD PRIMARY KEY (`id_pos`),
  ADD KEY `fk_tr_pos_tr_transaksi1_idx` (`id_transaksi`),
  ADD KEY `fk_tr_pos_users1_idx` (`id_pengguna`),
  ADD KEY `fk_tr_pos_admin_idx` (`id_admin`);

--
-- Indexes for table `tr_pos_detail`
--
ALTER TABLE `tr_pos_detail`
  ADD PRIMARY KEY (`id_pos_detail`),
  ADD KEY `fk_tr_pos_detail_tr_pos1_idx` (`id_pos`),
  ADD KEY `fk_tr_pos_detail_ms_produk1_idx` (`id_produk`);

--
-- Indexes for table `tr_transaksi`
--
ALTER TABLE `tr_transaksi`
  ADD PRIMARY KEY (`id_transaksi`),
  ADD UNIQUE KEY `kode_booking_UNIQUE` (`kode_sewa`),
  ADD KEY `fk_tr_transaksi_ms_pricing1_idx` (`id_penetapan_harga`),
  ADD KEY `fk_tr_transaksi_ms_pengguna1_idx` (`id_pengguna`),
  ADD KEY `fk_tr_transaksi_admin_idx` (`id_admin`);

--
-- Indexes for table `t_galeri`
--
ALTER TABLE `t_galeri`
  ADD PRIMARY KEY (`id_galeri`);

--
-- Indexes for table `t_video`
--
ALTER TABLE `t_video`
  ADD PRIMARY KEY (`id_video`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_pengguna`),
  ADD UNIQUE KEY `google_id_UNIQUE` (`google_id`),
  ADD UNIQUE KEY `email_UNIQUE` (`email`),
  ADD UNIQUE KEY `no_hp_UNIQUE` (`no_hp`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `ms_paket`
--
ALTER TABLE `ms_paket`
  MODIFY `id_paket` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `ms_permainan`
--
ALTER TABLE `ms_permainan`
  MODIFY `id_permainan` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `ms_produk`
--
ALTER TABLE `ms_produk`
  MODIFY `id_produk` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `ms_ruangan`
--
ALTER TABLE `ms_ruangan`
  MODIFY `id_ruangan` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `ms_sub_kategori_paket`
--
ALTER TABLE `ms_sub_kategori_paket`
  MODIFY `id_sub_kategori_paket` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `ms_sub_kategori_produk`
--
ALTER TABLE `ms_sub_kategori_produk`
  MODIFY `id_sub_kategori_produk` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `penetapan_harga`
--
ALTER TABLE `penetapan_harga`
  MODIFY `id_penetapan_harga` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT for table `tr_nomor_urut_harian`
--
ALTER TABLE `tr_nomor_urut_harian`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `tr_pos`
--
ALTER TABLE `tr_pos`
  MODIFY `id_pos` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=97;

--
-- AUTO_INCREMENT for table `tr_pos_detail`
--
ALTER TABLE `tr_pos_detail`
  MODIFY `id_pos_detail` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=249;

--
-- AUTO_INCREMENT for table `tr_transaksi`
--
ALTER TABLE `tr_transaksi`
  MODIFY `id_transaksi` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=203;

--
-- AUTO_INCREMENT for table `t_galeri`
--
ALTER TABLE `t_galeri`
  MODIFY `id_galeri` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `t_video`
--
ALTER TABLE `t_video`
  MODIFY `id_video` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id_pengguna` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=231;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `ms_paket`
--
ALTER TABLE `ms_paket`
  ADD CONSTRAINT `fk_ms_paket_ms_sub_kategori_paket1` FOREIGN KEY (`ms_sub_kategori_paket_id_sub_kategori_paket`) REFERENCES `ms_sub_kategori_paket` (`id_sub_kategori_paket`);

--
-- Constraints for table `ms_produk`
--
ALTER TABLE `ms_produk`
  ADD CONSTRAINT `fk_ms_produk_ms_sub_kategori_produk1` FOREIGN KEY (`ms_sub_kategori_produk_id_sub_kategori_produk`) REFERENCES `ms_sub_kategori_produk` (`id_sub_kategori_produk`);

--
-- Constraints for table `ms_ruangan_ms_permainan`
--
ALTER TABLE `ms_ruangan_ms_permainan`
  ADD CONSTRAINT `fk_ms_ruangan_has_ms_game_ms_game1` FOREIGN KEY (`id_permainan`) REFERENCES `ms_permainan` (`id_permainan`),
  ADD CONSTRAINT `fk_ms_ruangan_has_ms_game_ms_ruangan1` FOREIGN KEY (`id_ruangan`) REFERENCES `ms_ruangan` (`id_ruangan`);

--
-- Constraints for table `penetapan_harga`
--
ALTER TABLE `penetapan_harga`
  ADD CONSTRAINT `fk_ms_pricing_ms_paket1` FOREIGN KEY (`id_paket`) REFERENCES `ms_paket` (`id_paket`),
  ADD CONSTRAINT `fk_ms_pricing_ms_ruangan1` FOREIGN KEY (`id_ruangan`) REFERENCES `ms_ruangan` (`id_ruangan`);

--
-- Constraints for table `tr_pos`
--
ALTER TABLE `tr_pos`
  ADD CONSTRAINT `fk_tr_pos_admin` FOREIGN KEY (`id_admin`) REFERENCES `users` (`id_pengguna`),
  ADD CONSTRAINT `fk_tr_pos_tr_transaksi1` FOREIGN KEY (`id_transaksi`) REFERENCES `tr_transaksi` (`id_transaksi`),
  ADD CONSTRAINT `fk_tr_pos_users1` FOREIGN KEY (`id_pengguna`) REFERENCES `users` (`id_pengguna`);

--
-- Constraints for table `tr_pos_detail`
--
ALTER TABLE `tr_pos_detail`
  ADD CONSTRAINT `fk_tr_pos_detail_ms_produk1` FOREIGN KEY (`id_produk`) REFERENCES `ms_produk` (`id_produk`),
  ADD CONSTRAINT `fk_tr_pos_detail_tr_pos1` FOREIGN KEY (`id_pos`) REFERENCES `tr_pos` (`id_pos`);

--
-- Constraints for table `tr_transaksi`
--
ALTER TABLE `tr_transaksi`
  ADD CONSTRAINT `fk_tr_transaksi_admin` FOREIGN KEY (`id_admin`) REFERENCES `users` (`id_pengguna`),
  ADD CONSTRAINT `fk_tr_transaksi_ms_pengguna1` FOREIGN KEY (`id_pengguna`) REFERENCES `users` (`id_pengguna`),
  ADD CONSTRAINT `fk_tr_transaksi_ms_pricing1` FOREIGN KEY (`id_penetapan_harga`) REFERENCES `penetapan_harga` (`id_penetapan_harga`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
