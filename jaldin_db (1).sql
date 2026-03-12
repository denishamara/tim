-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Mar 12, 2026 at 02:04 PM
-- Server version: 8.0.30
-- PHP Version: 8.2.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `jaldin_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `approval_logs`
--

CREATE TABLE `approval_logs` (
  `id` bigint UNSIGNED NOT NULL,
  `perjalanan_id` bigint UNSIGNED NOT NULL,
  `approved_by` bigint UNSIGNED NOT NULL,
  `role` enum('direktur','admin','keuangan') COLLATE utf8mb4_general_ci NOT NULL,
  `status` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `catatan` text COLLATE utf8mb4_general_ci,
  `approved_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `approval_logs`
--

INSERT INTO `approval_logs` (`id`, `perjalanan_id`, `approved_by`, `role`, `status`, `catatan`, `approved_at`) VALUES
(1, 3, 2, 'direktur', 'approved_1', 'Disetujui oleh direktur.', '2026-03-04 03:45:04'),
(2, 3, 1, 'admin', 'processed_admin', 'Perincian biaya telah disiapkan oleh admin, menunggu persetujuan direktur.', '2026-03-04 03:49:03'),
(3, 3, 2, 'direktur', 'approved_2', 'Disetujui oleh direktur.', '2026-03-04 03:49:45'),
(4, 3, 3, 'keuangan', 'completed', 'sdtfyguh', '2026-03-04 03:50:41'),
(5, 4, 2, 'direktur', 'approved_1', 'Disetujui oleh direktur.', '2026-03-04 04:21:39'),
(6, 4, 1, 'admin', 'processed_admin', 'Perincian biaya telah disiapkan oleh admin, menunggu persetujuan direktur.', '2026-03-04 04:34:19'),
(7, 4, 2, 'direktur', 'approved_2', 'Disetujui oleh direktur.', '2026-03-04 04:35:50'),
(8, 4, 3, 'keuangan', 'completed', 'yes', '2026-03-04 04:37:21'),
(9, 5, 2, 'direktur', 'approved_1', 'Disetujui oleh direktur.', '2026-03-05 02:20:23'),
(10, 5, 1, 'admin', 'processed_admin', 'Perincian biaya telah disiapkan oleh admin, menunggu persetujuan direktur.', '2026-03-05 02:46:29'),
(11, 5, 2, 'direktur', 'approved_2', 'Disetujui oleh direktur.', '2026-03-05 02:47:27'),
(12, 5, 3, 'keuangan', 'completed', 'yesgsvsdh', '2026-03-05 02:48:20'),
(13, 6, 2, 'direktur', 'rejected_1', 'Besok saja', '2026-03-05 02:57:18'),
(14, 7, 2, 'direktur', 'approved_1', 'Disetujui oleh direktur.', '2026-03-09 03:07:03'),
(15, 7, 1, 'admin', 'processed_admin', 'Perincian biaya telah disiapkan oleh admin, menunggu persetujuan direktur.', '2026-03-09 03:10:25'),
(16, 7, 2, 'direktur', 'approved_2', 'Disetujui oleh direktur.', '2026-03-09 03:10:44'),
(17, 7, 3, 'keuangan', 'completed', 'beres', '2026-03-09 03:46:49'),
(18, 10, 2, 'direktur', 'approved_1', 'Disetujui oleh direktur.', '2026-03-12 02:16:52'),
(19, 10, 1, 'admin', 'processed_admin', 'Perincian biaya telah disiapkan oleh admin, menunggu persetujuan direktur.', '2026-03-12 02:21:22'),
(20, 10, 2, 'direktur', 'rejected_2', 'Reschedule', '2026-03-12 02:27:37'),
(21, 11, 2, 'direktur', 'approved_1', 'Disetujui oleh direktur.', '2026-03-12 02:35:25'),
(22, 11, 1, 'admin', 'processed_admin', 'Perincian biaya telah disiapkan oleh admin, menunggu persetujuan direktur.', '2026-03-12 02:45:34'),
(23, 11, 2, 'direktur', 'approved_2', 'Disetujui oleh direktur.', '2026-03-12 02:46:14'),
(24, 12, 2, 'direktur', 'approved_1', 'Disetujui oleh direktur.', '2026-03-12 02:52:42'),
(25, 12, 1, 'admin', 'processed_admin', 'Perincian biaya telah disiapkan oleh admin, menunggu persetujuan direktur.', '2026-03-12 02:54:37'),
(26, 12, 2, 'direktur', 'approved_2', 'Disetujui oleh direktur.', '2026-03-12 02:54:58'),
(27, 13, 2, 'direktur', 'approved_1', 'Disetujui oleh direktur.', '2026-03-12 06:11:41'),
(28, 13, 1, 'admin', 'processed_admin', 'Perincian biaya telah disiapkan oleh admin, menunggu persetujuan direktur.', '2026-03-12 06:15:37'),
(29, 13, 2, 'direktur', 'approved_2', 'Disetujui oleh direktur.', '2026-03-12 06:15:52');

-- --------------------------------------------------------

--
-- Table structure for table `dokumen_perjalanan`
--

CREATE TABLE `dokumen_perjalanan` (
  `id` bigint UNSIGNED NOT NULL,
  `perjalanan_id` bigint UNSIGNED NOT NULL,
  `nama_file` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `path_file` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `uploaded_by` bigint UNSIGNED NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dokumen_perjalanan`
--

INSERT INTO `dokumen_perjalanan` (`id`, `perjalanan_id`, `nama_file`, `path_file`, `uploaded_by`, `created_at`) VALUES
(2, 3, 'LOGO RSUD Soedirman.jpg', 'uploads/1772595814_df35436fea1a7a2191d4.jpg', 4, '2026-03-04 03:43:34'),
(3, 4, 'logo RSU cepu.jpg', 'uploads/1772597968_8b4c34e48c3064b6a176.jpg', 4, '2026-03-04 04:19:28'),
(4, 5, 'logo_rs_tidar.jpg', 'uploads/1772677012_c48f6a512d906fadc59d.jpg', 4, '2026-03-05 02:16:52'),
(5, 6, 'logo RSU cepu.jpg', 'uploads/1772679156_25f30cb18b9877f9bb0d.jpg', 4, '2026-03-05 02:52:36'),
(6, 7, 'logo RSU cepu.jpg', 'uploads/1773025581_d01ca4219e38afbb6a73.jpg', 4, '2026-03-09 03:06:21'),
(9, 10, 'Screenshot 2026-03-11 125019.png', 'uploads/1773281466_eb3c232f6c31c8be45c2.png', 6, '2026-03-12 02:11:06'),
(10, 11, 'Screenshot 2024-11-14 195439.png', 'uploads/1773282897_bd0aae22e2d26aa1bd1b.png', 6, '2026-03-12 02:34:57'),
(11, 12, 'logo RSU cepu.jpg', 'uploads/1773283946_d197bae35d94fc8d6e6a.jpg', 7, '2026-03-12 02:52:26'),
(12, 13, 'logo_rs_tidar.jpg', 'uploads/1773295878_3c9e3aa4a1e78511609e.jpg', 7, '2026-03-12 06:11:18');

-- --------------------------------------------------------

--
-- Table structure for table `jenis_biaya`
--

CREATE TABLE `jenis_biaya` (
  `id` bigint UNSIGNED NOT NULL,
  `nama` varchar(150) COLLATE utf8mb4_general_ci NOT NULL,
  `satuan_default` varchar(50) COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'Kali',
  `harga_default` decimal(15,2) NOT NULL DEFAULT '0.00',
  `keterangan` text COLLATE utf8mb4_general_ci,
  `butuh_kendaraan` tinyint NOT NULL DEFAULT '0' COMMENT '1 = wajib pilih kendaraan',
  `aktif` tinyint NOT NULL DEFAULT '1',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jenis_biaya`
--

INSERT INTO `jenis_biaya` (`id`, `nama`, `satuan_default`, `harga_default`, `keterangan`, `butuh_kendaraan`, `aktif`, `created_at`, `updated_at`) VALUES
(1, 'BBM PP', 'PP', '30000.00', 'Biaya bahan bakar pergi-pulang', 1, 1, '2026-03-09 09:51:14', '2026-03-09 02:55:24'),
(2, 'BBM Di Lokasi', 'Liter', '15000.00', 'Biaya bahan bakar operasional di lokasi', 1, 1, '2026-03-09 09:51:14', '2026-03-09 02:54:47'),
(3, 'Uang Makan', 'Kali', '18000.00', 'Uang makan harian', 0, 1, '2026-03-09 09:51:14', '2026-03-09 03:08:49'),
(4, 'Biaya Penginapan', 'Malam', '0.00', 'Biaya hotel / penginapan', 0, 1, '2026-03-09 09:51:14', '2026-03-09 09:51:14'),
(5, 'Biaya Transportasi', 'Kali', '0.00', 'Biaya tol, tiket, dll', 0, 1, '2026-03-09 09:51:14', '2026-03-09 09:51:14'),
(6, 'Parkir', 'Kali', '10000.00', 'Biaya parkir kendaraan', 1, 1, '2026-03-09 09:51:14', '2026-03-09 02:55:38'),
(7, 'Lainnya', 'Kali', '0.00', 'Biaya lain-lain', 0, 1, '2026-03-09 09:51:14', '2026-03-09 09:51:14');

-- --------------------------------------------------------

--
-- Table structure for table `kendaraan`
--

CREATE TABLE `kendaraan` (
  `id` bigint UNSIGNED NOT NULL,
  `nama_kendaraan` varchar(150) COLLATE utf8mb4_general_ci NOT NULL,
  `nomor_polisi` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `jenis` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `keterangan` text COLLATE utf8mb4_general_ci,
  `aktif` tinyint NOT NULL DEFAULT '1',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kendaraan`
--

INSERT INTO `kendaraan` (`id`, `nama_kendaraan`, `nomor_polisi`, `jenis`, `keterangan`, `aktif`, `created_at`, `updated_at`) VALUES
(1, 'Mitsubishi Mirage', 'B 1744 EYC', 'Kendaraan Roda 4', 'Kendaraan operasional kantor', 1, '2026-03-04 11:09:11', '2026-03-04 11:09:11'),
(2, 'Toyota Avanza', 'B 2201 XYZ', 'Kendaraan Roda 4', 'Kendaraan operasional kantor', 1, '2026-03-04 11:09:11', '2026-03-05 02:22:52'),
(3, 'Honda Vario', 'B 5510 ABC', 'Kendaraan Roda 2', 'Kendaraan operasional kantor', 1, '2026-03-04 11:09:11', '2026-03-05 02:23:17'),
(4, 'Motor Beat', 'AB 4178 JB', 'Kendaraan Roda 2', 'Kendaraan operasional kantor', 1, '2026-03-05 02:22:44', '2026-03-05 02:22:44');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` bigint UNSIGNED NOT NULL,
  `version` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `class` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `group` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `namespace` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `time` int NOT NULL,
  `batch` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `version`, `class`, `group`, `namespace`, `time`, `batch`) VALUES
(6, '2026-03-03-044313', 'App\\Database\\Migrations\\CreateUsers', 'default', 'App', 1772594228, 1),
(7, '2026-03-03-044451', 'App\\Database\\Migrations\\CreatePerjalananDinas', 'default', 'App', 1772594228, 1),
(8, '2026-03-03-044521', 'App\\Database\\Migrations\\CreateRincianBiaya', 'default', 'App', 1772594228, 1),
(9, '2026-03-03-044605', 'App\\Database\\Migrations\\CreateApprovalLogs', 'default', 'App', 1772594228, 1),
(10, '2026-03-03-044635', 'App\\Database\\Migrations\\CreateDokumenPerjalanan', 'default', 'App', 1772594228, 1),
(11, '2026-03-04-010000', 'App\\Database\\Migrations\\CreateKendaraan', 'default', 'App', 1772597337, 2),
(12, '2026-03-04-010001', 'App\\Database\\Migrations\\AddFieldsRincianBiaya', 'default', 'App', 1772597337, 2),
(13, '2026-03-04-010002', 'App\\Database\\Migrations\\AddCatatanPerjalananDinas', 'default', 'App', 1772597337, 2),
(14, '2026-03-05-000001', 'App\\Database\\Migrations\\CreatePasswordResets', 'default', 'App', 1773024674, 3),
(15, '2026-03-09-000001', 'App\\Database\\Migrations\\CreateJenisBiaya', 'default', 'App', 1773024674, 3),
(16, '2026-03-09-000002', 'App\\Database\\Migrations\\AddJenisBiayaIdToRincianBiaya', 'default', 'App', 1773025366, 4),
(17, '2026-03-12-000001', 'App\\Database\\Migrations\\CreatePerjalananPeserta', 'default', 'App', 1773281451, 5),
(18, '2026-03-12-000002', 'App\\Database\\Migrations\\AddPhoneAddressToUsers', 'default', 'App', 1773281568, 6);

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `id` int UNSIGNED NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `perjalanan_dinas`
--

CREATE TABLE `perjalanan_dinas` (
  `id` bigint UNSIGNED NOT NULL,
  `nomor_surat` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `tujuan` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `kota_tujuan` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `tanggal_berangkat` date NOT NULL,
  `jam_berangkat` varchar(10) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tanggal_pulang` date NOT NULL,
  `jam_pulang` varchar(10) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `keperluan` text COLLATE utf8mb4_general_ci NOT NULL,
  `catatan` text COLLATE utf8mb4_general_ci,
  `total_pengajuan` decimal(15,2) NOT NULL DEFAULT '0.00',
  `status` enum('draft','approved_1','rejected_1','processed_admin','approved_2','rejected_2','sent_finance','completed') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'draft',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `perjalanan_dinas`
--

INSERT INTO `perjalanan_dinas` (`id`, `nomor_surat`, `user_id`, `tujuan`, `kota_tujuan`, `tanggal_berangkat`, `jam_berangkat`, `tanggal_pulang`, `jam_pulang`, `keperluan`, `catatan`, `total_pengajuan`, `status`, `created_at`, `updated_at`) VALUES
(3, 'SPD/2026/03/0003', 4, 'RS Panembahan', 'Bantul', '2026-03-07', NULL, '2026-03-08', NULL, 'Meeting Client', NULL, '251000.00', 'completed', '2026-03-04 03:43:34', '2026-03-04 03:50:41'),
(4, 'SPD/2026/03/0002', 4, 'RS Nurhidayah', 'Bantul', '2026-03-05', '07:20', '2026-03-06', '11:20', 'Meeting Client', 'Kamis, 5 Maret 2026\r\nRs Nurhidayah (follow up)\r\n\r\nJumat, 6 Maret 2026\r\nRs Nurhidayah (follow up)', '253000.00', 'completed', '2026-03-04 04:19:28', '2026-03-04 04:37:21'),
(5, 'SPD/2026/03/0003', 4, 'RS PKU Muhammadiyah Kotagede', 'Bantul', '2026-03-10', '09:20', '2026-03-11', '12:20', 'Meeting Client', 'Selasa, 10 Maret 2026\r\nRS PKU Muhammadiyah Kotagede\r\nMeeting client \r\n\r\nRabu, 11 Maret 2026\r\nRS PKU Muhammadiyah Kotagede \r\nMeeting client part 2\r\n', '15000.00', 'completed', '2026-03-05 02:16:52', '2026-03-05 02:48:20'),
(6, 'SPD/2026/03/0004', 4, 'RS Nurhidayah', 'Bantul', '2026-03-12', '10:55', '2026-03-12', '19:55', 'Penawaran SIMRS', 'Kamis, 12 Maret 2026\r\nRS Nurhidayah\r\nPenawaran SIMRS', '0.00', 'rejected_1', '2026-03-05 02:52:36', '2026-03-05 02:57:18'),
(7, 'SPD/2026/03/0005', 4, 'RS Cepit', 'Jawa Timur', '2026-03-10', '07:05', '2026-03-10', '17:10', 'Marketing', 'Selasa, 10 Maret 2026\r\nRS Cepit \r\nMarketing with Vira', '38000.00', 'completed', '2026-03-09 03:06:20', '2026-03-09 03:46:49'),
(10, 'SPD/2026/03/0008', 6, 'RS Panembahan', 'Bandung', '2026-03-13', '10:10', '2026-03-14', '11:10', 'Marketing SIMRS', 'Jumat, 13 Maret 2026\r\nRs Panembahan\r\nRapat\r\nSabtu, 14 Maret 2026\r\nRs Panembahan\r\nTTD', '210000.00', 'rejected_2', '2026-03-12 02:11:06', '2026-03-12 02:27:37'),
(11, 'SPD/2026/03/0007', 6, 'RS Wirosaban', 'Jakarta', '2026-03-13', '10:35', '2026-03-14', '11:35', 'Penawaran SIMRS', 'Jumat, 13 Maret 2026\r\nRs Wirosaban\r\nRapat\r\n\r\nSabtu, 14 Maret 2026\r\nRs Wirosaban\r\nRapat part 2', '1240000.00', 'approved_2', '2026-03-12 02:34:57', '2026-03-12 02:46:14'),
(12, 'SPD/2026/03/0008', 7, 'RS Maju', 'Surabaya', '2026-03-15', '09:50', '2026-03-16', '09:51', 'Marketing', 'Minggu, 15 Maret 2026\r\nRS Maju\r\nPromosi SIMRS\r\n\r\nSenin, 16 Maret 2026\r\nRS Maju\r\nMeeting', '60000.00', 'approved_2', '2026-03-12 02:52:26', '2026-03-12 02:54:58'),
(13, 'SPD/2026/03/0009', 7, 'RS Sukamaju', 'Surabaya', '2026-03-15', '10:10', '2026-03-16', '10:10', 'Marketing', '', '305000.00', 'approved_2', '2026-03-12 06:11:18', '2026-03-12 06:15:52');

-- --------------------------------------------------------

--
-- Table structure for table `perjalanan_peserta`
--

CREATE TABLE `perjalanan_peserta` (
  `id` bigint UNSIGNED NOT NULL,
  `perjalanan_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `jabatan` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Jabatan peserta saat perjalanan',
  `keterangan` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Keterangan tambahan untuk peserta',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `perjalanan_peserta`
--

INSERT INTO `perjalanan_peserta` (`id`, `perjalanan_id`, `user_id`, `jabatan`, `keterangan`, `created_at`, `updated_at`) VALUES
(1, 10, 4, NULL, NULL, '2026-03-12 02:11:06', '2026-03-12 02:11:06'),
(2, 10, 6, NULL, NULL, '2026-03-12 02:11:06', '2026-03-12 02:11:06'),
(3, 11, 4, NULL, NULL, '2026-03-12 02:34:57', '2026-03-12 02:34:57'),
(4, 11, 6, NULL, NULL, '2026-03-12 02:34:57', '2026-03-12 02:34:57'),
(5, 12, 6, NULL, NULL, '2026-03-12 02:52:26', '2026-03-12 02:52:26'),
(6, 12, 7, NULL, NULL, '2026-03-12 02:52:26', '2026-03-12 02:52:26'),
(7, 13, 4, NULL, NULL, '2026-03-12 06:11:18', '2026-03-12 06:11:18'),
(8, 13, 6, NULL, NULL, '2026-03-12 06:11:18', '2026-03-12 06:11:18'),
(9, 13, 7, NULL, NULL, '2026-03-12 06:11:18', '2026-03-12 06:11:18');

-- --------------------------------------------------------

--
-- Table structure for table `rincian_biaya`
--

CREATE TABLE `rincian_biaya` (
  `id` bigint UNSIGNED NOT NULL,
  `perjalanan_id` bigint UNSIGNED NOT NULL,
  `jenis_biaya_id` bigint UNSIGNED DEFAULT NULL,
  `judul` varchar(150) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `keterangan` text COLLATE utf8mb4_general_ci,
  `kendaraan_id` bigint UNSIGNED DEFAULT NULL,
  `uraian` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `qty` int NOT NULL,
  `satuan` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `harga` decimal(15,2) NOT NULL,
  `total` decimal(15,2) NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rincian_biaya`
--

INSERT INTO `rincian_biaya` (`id`, `perjalanan_id`, `jenis_biaya_id`, `judul`, `keterangan`, `kendaraan_id`, `uraian`, `qty`, `satuan`, `harga`, `total`, `created_at`, `updated_at`) VALUES
(1, 3, NULL, NULL, NULL, NULL, 'Uang Makan', 2, 'Kali', '18000.00', '36000.00', '2026-03-04 03:47:44', '2026-03-04 03:47:44'),
(2, 3, NULL, NULL, NULL, NULL, 'Biaya Penginapan', 1, 'Malam', '200000.00', '200000.00', '2026-03-04 03:48:40', '2026-03-04 03:48:40'),
(3, 3, NULL, NULL, NULL, NULL, 'BBM', 1, 'PP', '15000.00', '15000.00', '2026-03-04 03:48:58', '2026-03-04 03:48:58'),
(4, 4, NULL, 'Biaya Penginapan', 'Hotel ', NULL, 'Hotel ', 1, 'Malam', '170000.00', '170000.00', '2026-03-04 04:26:21', '2026-03-04 04:26:21'),
(5, 4, NULL, 'BBM PP', 'Honda Vario (B 5510 ABC)', 3, 'Honda Vario (B 5510 ABC)', 1, 'PP', '15000.00', '15000.00', '2026-03-04 04:33:19', '2026-03-04 04:33:19'),
(6, 4, NULL, 'Uang Makan', 'M.Nizar Zulmi Syaifullah', NULL, 'M.Nizar Zulmi Syaifullah', 4, 'Kali', '17000.00', '68000.00', '2026-03-04 04:34:15', '2026-03-04 04:34:15'),
(7, 5, NULL, 'BBM Dilokasi', 'Motor Beat (AB 4178 JB)', 4, 'Motor Beat (AB 4178 JB)', 1, 'PP', '15000.00', '15000.00', '2026-03-05 02:46:24', '2026-03-05 02:46:24'),
(8, 7, 1, 'BBM PP', 'Motor Beat (AB 4178 JB)', 4, 'Motor Beat (AB 4178 JB)', 1, 'PP', '20000.00', '20000.00', '2026-03-09 03:08:03', '2026-03-09 03:08:03'),
(10, 7, 3, 'Uang Makan', 'Naspad', NULL, 'Naspad', 1, 'Kali', '18000.00', '18000.00', '2026-03-09 03:10:19', '2026-03-09 03:10:19'),
(12, 10, 1, 'BBM PP', 'Toyota Avanza (B 2201 XYZ)', 2, 'Toyota Avanza (B 2201 XYZ)', 1, 'PP', '30000.00', '30000.00', '2026-03-12 02:18:22', '2026-03-12 02:18:22'),
(14, 10, 3, 'Uang Makan', 'Vira Elinda Putri, M. Nizar Zulmi Syaifullah', NULL, 'Vira Elinda Putri, M. Nizar Zulmi Syaifullah', 10, 'Kali', '18000.00', '180000.00', '2026-03-12 02:21:09', '2026-03-12 02:21:09'),
(15, 11, 3, 'Uang Makan', 'Vira Elinda Putri', NULL, 'Vira Elinda Putri', 5, 'Kali', '18000.00', '90000.00', '2026-03-12 02:36:37', '2026-03-12 02:36:37'),
(16, 11, 3, 'Uang Makan', 'M. Nizar Zulmi Syaifullah', NULL, 'M. Nizar Zulmi Syaifullah', 5, 'Kali', '18000.00', '90000.00', '2026-03-12 02:36:37', '2026-03-12 02:36:37'),
(17, 11, 1, 'BBM PP', 'Motor Beat (AB 4178 JB)', 4, 'Motor Beat (AB 4178 JB)', 1, 'PP', '30000.00', '30000.00', '2026-03-12 02:44:17', '2026-03-12 02:44:17'),
(18, 11, 1, 'BBM PP', 'Honda Vario (B 5510 ABC)', 3, 'Honda Vario (B 5510 ABC)', 1, 'PP', '30000.00', '30000.00', '2026-03-12 02:44:17', '2026-03-12 02:44:17'),
(19, 11, 4, 'Biaya Penginapan', 'Vira Elinda Putri', NULL, 'Vira Elinda Putri', 1, 'Malam', '500000.00', '500000.00', '2026-03-12 02:45:19', '2026-03-12 02:45:19'),
(20, 11, 4, 'Biaya Penginapan', 'M. Nizar Zulmi Syaifullah', NULL, 'M. Nizar Zulmi Syaifullah', 1, 'Malam', '500000.00', '500000.00', '2026-03-12 02:45:19', '2026-03-12 02:45:19'),
(21, 12, 1, 'BBM PP', 'Honda Vario (B 5510 ABC)', 3, 'Honda Vario (B 5510 ABC)', 1, 'PP', '30000.00', '30000.00', '2026-03-12 02:53:33', '2026-03-12 02:53:33'),
(22, 12, 1, 'BBM PP', 'Motor Beat (AB 4178 JB)', 4, 'Motor Beat (AB 4178 JB)', 1, 'PP', '30000.00', '30000.00', '2026-03-12 02:53:33', '2026-03-12 02:53:33'),
(23, 13, 2, 'BBM Di Lokasi', 'Toyota Avanza (B 2201 XYZ)', 2, 'Toyota Avanza (B 2201 XYZ)', 1, 'Liter', '15000.00', '15000.00', '2026-03-12 06:13:26', '2026-03-12 06:13:26'),
(24, 13, 3, 'Uang Makan', 'Denisha Amara', NULL, 'Denisha Amara', 5, 'Kali', '18000.00', '90000.00', '2026-03-12 06:14:22', '2026-03-12 06:14:22'),
(25, 13, 3, 'Uang Makan', 'Vira Elinda Putri', NULL, 'Vira Elinda Putri', 5, 'Kali', '18000.00', '90000.00', '2026-03-12 06:14:22', '2026-03-12 06:14:22'),
(26, 13, 3, 'Uang Makan', 'M. Nizar Zulmi Syaifullah', NULL, 'M. Nizar Zulmi Syaifullah', 5, 'Kali', '18000.00', '90000.00', '2026-03-12 06:14:22', '2026-03-12 06:14:22'),
(27, 13, 5, 'Biaya Transportasi', 'Tol Prambanan - Demak', NULL, 'Tol Prambanan - Demak', 1, 'Kali', '10000.00', '10000.00', '2026-03-12 06:15:33', '2026-03-12 06:15:33'),
(28, 13, 5, 'Biaya Transportasi', 'Tol Ngawi - Prambanan', NULL, 'Tol Ngawi - Prambanan', 1, 'Kali', '10000.00', '10000.00', '2026-03-12 06:15:33', '2026-03-12 06:15:33');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_general_ci,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `role` enum('pegawai','admin','direktur','keuangan') COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `phone`, `address`, `password`, `role`, `created_at`, `updated_at`) VALUES
(1, 'Admin Sistem', 'admin@jaldin.com', NULL, NULL, '$2y$10$to4sPMxQ/GzsqG/XBpf4jeA2KfrDDEukLsREKe5palkhohJPxKChC', 'admin', '2026-03-04 10:17:09', '2026-03-04 10:17:09'),
(2, 'Direktur Utama', 'direktur@jaldin.com', NULL, NULL, '$2y$10$A/Kbd20oi0czdzj47pCkr.jhj3qfNvmdKjQCw2.VQZO0kX9Zzo/2K', 'direktur', '2026-03-04 10:17:09', '2026-03-04 10:17:09'),
(3, 'Staff Keuangan', 'keuangan@jaldin.com', NULL, NULL, '$2y$10$l2wNPMqxU4T9CA7.r306zeKDoIoseKV8L/qqQiWuldUbZ/v1PfOFy', 'keuangan', '2026-03-04 10:17:09', '2026-03-04 10:17:09'),
(4, 'M. Nizar Zulmi Syaifullah', 'nizar@jaldin.com', NULL, NULL, '$2y$10$Q2r7/1en05u06E2sRIPcYOUQP/wRYxBD30tnjNGKzPnLi0qodijNG', 'pegawai', '2026-03-04 10:17:09', '2026-03-04 10:17:09'),
(5, 'Muhammad Mahfud Sahal', 'mahfud@jaldin.com', NULL, NULL, '$2y$10$L5XzbmcCmZu.5CzDVmz0bulH9ZvEw1BiwcZb8PA1Lp6U8b7mY3ECa', 'pegawai', '2026-03-04 10:17:09', '2026-03-04 10:17:09'),
(6, 'Vira Elinda Putri', 'viraelindaa@gmail.com', NULL, NULL, '$2y$10$D2y0ArAnOj1B7KpZasxxxOgy7lVC.6pbtkchkRxXfEmrCs6MdNram', 'pegawai', '2026-03-12 02:04:19', '2026-03-12 02:04:19'),
(7, 'Denisha Amara', 'denishamara07@gmail.com', NULL, NULL, '$2y$10$kG4oxGcqpp/0CV8VRooiC.rppKPBU2GWLQV4u/m9le0WJm3ik73QS', 'pegawai', '2026-03-12 02:50:11', '2026-03-12 02:50:11');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `approval_logs`
--
ALTER TABLE `approval_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `approval_logs_perjalanan_id_foreign` (`perjalanan_id`),
  ADD KEY `approval_logs_approved_by_foreign` (`approved_by`);

--
-- Indexes for table `dokumen_perjalanan`
--
ALTER TABLE `dokumen_perjalanan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `dokumen_perjalanan_perjalanan_id_foreign` (`perjalanan_id`),
  ADD KEY `dokumen_perjalanan_uploaded_by_foreign` (`uploaded_by`);

--
-- Indexes for table `jenis_biaya`
--
ALTER TABLE `jenis_biaya`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kendaraan`
--
ALTER TABLE `kendaraan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `email` (`email`),
  ADD KEY `token` (`token`);

--
-- Indexes for table `perjalanan_dinas`
--
ALTER TABLE `perjalanan_dinas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `perjalanan_dinas_user_id_foreign` (`user_id`);

--
-- Indexes for table `perjalanan_peserta`
--
ALTER TABLE `perjalanan_peserta`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `perjalanan_id_user_id` (`perjalanan_id`,`user_id`),
  ADD KEY `perjalanan_peserta_user_id_foreign` (`user_id`);

--
-- Indexes for table `rincian_biaya`
--
ALTER TABLE `rincian_biaya`
  ADD PRIMARY KEY (`id`),
  ADD KEY `rincian_biaya_perjalanan_id_foreign` (`perjalanan_id`),
  ADD KEY `rincian_biaya` (`jenis_biaya_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `approval_logs`
--
ALTER TABLE `approval_logs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `dokumen_perjalanan`
--
ALTER TABLE `dokumen_perjalanan`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `jenis_biaya`
--
ALTER TABLE `jenis_biaya`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `kendaraan`
--
ALTER TABLE `kendaraan`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `perjalanan_dinas`
--
ALTER TABLE `perjalanan_dinas`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `perjalanan_peserta`
--
ALTER TABLE `perjalanan_peserta`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `rincian_biaya`
--
ALTER TABLE `rincian_biaya`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `approval_logs`
--
ALTER TABLE `approval_logs`
  ADD CONSTRAINT `approval_logs_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `approval_logs_perjalanan_id_foreign` FOREIGN KEY (`perjalanan_id`) REFERENCES `perjalanan_dinas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `dokumen_perjalanan`
--
ALTER TABLE `dokumen_perjalanan`
  ADD CONSTRAINT `dokumen_perjalanan_perjalanan_id_foreign` FOREIGN KEY (`perjalanan_id`) REFERENCES `perjalanan_dinas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `dokumen_perjalanan_uploaded_by_foreign` FOREIGN KEY (`uploaded_by`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `perjalanan_dinas`
--
ALTER TABLE `perjalanan_dinas`
  ADD CONSTRAINT `perjalanan_dinas_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `perjalanan_peserta`
--
ALTER TABLE `perjalanan_peserta`
  ADD CONSTRAINT `perjalanan_peserta_perjalanan_id_foreign` FOREIGN KEY (`perjalanan_id`) REFERENCES `perjalanan_dinas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `perjalanan_peserta_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `rincian_biaya`
--
ALTER TABLE `rincian_biaya`
  ADD CONSTRAINT `rincian_biaya` FOREIGN KEY (`jenis_biaya_id`) REFERENCES `jenis_biaya` (`id`) ON DELETE SET NULL ON UPDATE SET NULL,
  ADD CONSTRAINT `rincian_biaya_perjalanan_id_foreign` FOREIGN KEY (`perjalanan_id`) REFERENCES `perjalanan_dinas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
