-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 17 Bulan Mei 2025 pada 16.46
-- Versi server: 8.0.42
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `spk_ahp`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `dokumen_pengajuan`
--

CREATE TABLE `dokumen_pengajuan` (
  `id` int NOT NULL,
  `pengajuan_id` int NOT NULL,
  `nama_dokumen` varchar(100) NOT NULL,
  `jenis_dokumen` enum('KTP','KK','Surat Usaha','Jaminan','Lainnya') NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `uploaded_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `hasil_perhitungan`
--

CREATE TABLE `hasil_perhitungan` (
  `id` int NOT NULL,
  `nasabah_id` int NOT NULL,
  `pengajuan_id` int NOT NULL,
  `nilai_bobot` decimal(10,5) NOT NULL,
  `peringkat` int NOT NULL,
  `status` enum('Diterima','Ditolak') NOT NULL,
  `tanggal_perhitungan` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `hasil_perhitungan`
--

INSERT INTO `hasil_perhitungan` (`id`, `nasabah_id`, `pengajuan_id`, `nilai_bobot`, `peringkat`, `status`, `tanggal_perhitungan`) VALUES
(106, 6, 25, 0.77450, 1, 'Diterima', '2025-05-17 18:58:39'),
(107, 12, 23, 0.72878, 2, 'Diterima', '2025-05-17 18:58:39'),
(108, 14, 22, 0.68306, 3, 'Diterima', '2025-05-17 18:58:39'),
(109, 15, 21, 0.68306, 4, 'Diterima', '2025-05-17 18:58:39'),
(110, 8, 24, 0.63734, 5, 'Diterima', '2025-05-17 18:58:39'),
(111, 16, 20, 0.63734, 6, 'Diterima', '2025-05-17 18:58:39');

-- --------------------------------------------------------

--
-- Struktur dari tabel `kriteria`
--

CREATE TABLE `kriteria` (
  `id` int NOT NULL,
  `nama_kriteria` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `kriteria`
--

INSERT INTO `kriteria` (`id`, `nama_kriteria`) VALUES
(1, 'Kondisi Ekonomi'),
(2, 'Karakter'),
(3, 'Modal'),
(4, 'Kemampuan'),
(5, 'Jaminan');

-- --------------------------------------------------------

--
-- Struktur dari tabel `log_aktivitas`
--

CREATE TABLE `log_aktivitas` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `aktivitas` varchar(255) NOT NULL,
  `detail` text,
  `ip_address` varchar(50) DEFAULT NULL,
  `user_agent` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `nasabah`
--

CREATE TABLE `nasabah` (
  `id` int NOT NULL,
  `kode_nasabah` varchar(20) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `nik` varchar(16) NOT NULL,
  `jenis_kelamin` enum('Laki-laki','Perempuan') NOT NULL,
  `tempat_lahir` varchar(50) NOT NULL,
  `tanggal_lahir` date NOT NULL,
  `alamat` text NOT NULL,
  `no_telepon` varchar(15) NOT NULL,
  `pekerjaan` varchar(100) NOT NULL,
  `penghasilan_bulanan` decimal(12,2) NOT NULL DEFAULT '0.00',
  `jumlah_tanggungan` int DEFAULT '0',
  `status_pernikahan` enum('Belum Menikah','Menikah','Cerai') NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `nasabah`
--

INSERT INTO `nasabah` (`id`, `kode_nasabah`, `nama_lengkap`, `nik`, `jenis_kelamin`, `tempat_lahir`, `tanggal_lahir`, `alamat`, `no_telepon`, `pekerjaan`, `penghasilan_bulanan`, `jumlah_tanggungan`, `status_pernikahan`, `created_at`, `updated_at`) VALUES
(6, '03034', 'Nasibuan', '030302101', 'Laki-laki', 'Jogja', '1999-02-10', 'Jalan Merdeka Selatan', '0987654321', 'Swasta', 10000000.00, 4, 'Menikah', '2025-05-04 12:44:17', '2025-05-04 17:44:17'),
(8, '0207', 'Hasyim', '0202139', 'Laki-laki', 'Bandung', '1999-05-05', 'Jalan Istana Merdeka 1', '0987654325432', 'Swasta', 2000000.00, 2, 'Menikah', '2025-05-05 01:34:16', '2025-05-05 02:01:55'),
(12, '050106', 'Farhan', '01231301019', 'Perempuan', 'Jakarta', '2001-06-05', 'Jalan kesehatan 1', '0987654321', 'PNS', 6000000.00, 2, 'Menikah', '2025-05-05 01:50:03', '2025-05-15 13:50:49'),
(14, '1002', 'Hamim', '10230131', 'Laki-laki', 'Jakarta', '2000-10-10', 'Jalan Kertajati 1', '09876543243', 'PNS', 5000000.00, 2, 'Menikah', '2025-05-05 01:56:28', '2025-05-05 06:56:28'),
(15, '1005', 'Rangga', '1991120313', 'Laki-laki', 'Jakarta', '2000-10-01', 'Jalan Raya Gedong 1', '0987654321543', 'Kuliah', 5000000.00, 2, 'Belum Menikah', '2025-05-05 02:21:15', '2025-05-05 07:21:15'),
(16, '01023012', 'Muhammad Refi', '2132132103102', 'Laki-laki', 'Jakarta barat', '2025-05-16', 'Jakarta barat slipi 2', '098765432121', 'Swasta', 1500000.00, 2, 'Menikah', '2025-05-15 12:15:58', '2025-05-15 17:15:58');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengajuan`
--

CREATE TABLE `pengajuan` (
  `id` int NOT NULL,
  `kode_pengajuan` varchar(20) NOT NULL,
  `nasabah_id` int NOT NULL,
  `tanggal_pengajuan` date NOT NULL,
  `kondisi_ekonomi` varchar(50) DEFAULT NULL,
  `karakter` varchar(50) DEFAULT NULL,
  `modal` varchar(100) DEFAULT NULL,
  `kemampuan` varchar(20) NOT NULL,
  `status_pengajuan` varchar(20) DEFAULT 'Diajukan',
  `jumlah_pinjaman` decimal(15,2) NOT NULL,
  `created_by` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `jaminan` varchar(255) DEFAULT NULL,
  `nilai_jaminan` decimal(15,2) DEFAULT NULL,
  `jangka_waktu` int DEFAULT NULL,
  `tujuan_pinjaman` varchar(255) DEFAULT NULL,
  `status` varchar(50) DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `pengajuan`
--

INSERT INTO `pengajuan` (`id`, `kode_pengajuan`, `nasabah_id`, `tanggal_pengajuan`, `kondisi_ekonomi`, `karakter`, `modal`, `kemampuan`, `status_pengajuan`, `jumlah_pinjaman`, `created_by`, `created_at`, `updated_at`, `jaminan`, `nilai_jaminan`, `jangka_waktu`, `tujuan_pinjaman`, `status`) VALUES
(20, 'PJM-202505-001', 16, '2025-05-15', 'Cukup', 'Lancar', '1500000', '1000000', 'Diterima', 1000000.00, 5, NULL, '2025-05-17 06:58:39', NULL, NULL, NULL, NULL, 'pending'),
(21, 'PJM-202505-002', 15, '2025-05-15', 'Baik', 'Lancar', '5000000', '2000000', 'Diterima', 2000000.00, 5, NULL, '2025-05-17 06:58:39', NULL, NULL, NULL, NULL, 'pending'),
(22, 'PJM-202505-003', 14, '2025-05-15', 'Baik', 'Lancar', '5000000', '2000000', 'Diterima', 2000000.00, 5, NULL, '2025-05-17 06:58:39', NULL, NULL, NULL, NULL, 'pending'),
(23, 'PJM-202505-004', 12, '2025-05-15', 'Baik', 'Lancar', '6000000', '3000000', 'Diterima', 3000000.00, 5, NULL, '2025-05-17 06:58:39', NULL, NULL, NULL, NULL, 'pending'),
(24, 'PJM-202505-005', 8, '2025-05-15', 'Buruk', 'Lancar', '2000000', '150000', 'Diterima', 150000.00, 5, NULL, '2025-05-17 06:58:39', NULL, NULL, NULL, NULL, 'pending'),
(25, 'PJM-202505-006', 6, '2025-05-17', 'Baik', 'Lancar', '5000000', '1500000', 'Diterima', 1500000.00, 6, NULL, '2025-05-17 06:58:39', NULL, NULL, NULL, NULL, 'pending');

-- --------------------------------------------------------

--
-- Struktur dari tabel `perbandingan_ekonomi`
--

CREATE TABLE `perbandingan_ekonomi` (
  `id` int NOT NULL,
  `kriteria_1` int NOT NULL,
  `kriteria_2` int NOT NULL,
  `nilai` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `perbandingan_ekonomi`
--

INSERT INTO `perbandingan_ekonomi` (`id`, `kriteria_1`, `kriteria_2`, `nilai`) VALUES
(31, 1, 2, 3),
(32, 1, 3, 5),
(33, 2, 1, 0.333333),
(34, 2, 3, 1),
(35, 3, 1, 0.2),
(36, 3, 2, 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `perbandingan_jaminan`
--

CREATE TABLE `perbandingan_jaminan` (
  `id` int NOT NULL,
  `kriteria_1` int NOT NULL,
  `kriteria_2` int NOT NULL,
  `nilai` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `perbandingan_jaminan`
--

INSERT INTO `perbandingan_jaminan` (`id`, `kriteria_1`, `kriteria_2`, `nilai`) VALUES
(25, 1, 2, 7),
(26, 1, 3, 1),
(27, 2, 1, 0.142857),
(28, 2, 3, 5),
(29, 3, 1, 1),
(30, 3, 2, 0.2);

-- --------------------------------------------------------

--
-- Struktur dari tabel `perbandingan_karakter`
--

CREATE TABLE `perbandingan_karakter` (
  `id` int NOT NULL,
  `kriteria_1` int NOT NULL,
  `kriteria_2` int NOT NULL,
  `nilai` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `perbandingan_karakter`
--

INSERT INTO `perbandingan_karakter` (`id`, `kriteria_1`, `kriteria_2`, `nilai`) VALUES
(31, 1, 2, 3),
(32, 1, 3, 1),
(33, 2, 1, 0.333333),
(34, 2, 3, 5),
(35, 3, 1, 1),
(36, 3, 2, 0.2);

-- --------------------------------------------------------

--
-- Struktur dari tabel `perbandingan_kemampuan`
--

CREATE TABLE `perbandingan_kemampuan` (
  `id` int NOT NULL,
  `kriteria_1` int NOT NULL,
  `kriteria_2` int NOT NULL,
  `nilai` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `perbandingan_kemampuan`
--

INSERT INTO `perbandingan_kemampuan` (`id`, `kriteria_1`, `kriteria_2`, `nilai`) VALUES
(25, 1, 2, 5),
(26, 1, 3, 1),
(27, 2, 1, 0.2),
(28, 2, 3, 5),
(29, 3, 1, 1),
(30, 3, 2, 0.2);

-- --------------------------------------------------------

--
-- Struktur dari tabel `perbandingan_kriteria`
--

CREATE TABLE `perbandingan_kriteria` (
  `id` int NOT NULL,
  `kriteria_1` int NOT NULL,
  `kriteria_2` int NOT NULL,
  `nilai` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `perbandingan_kriteria`
--

INSERT INTO `perbandingan_kriteria` (`id`, `kriteria_1`, `kriteria_2`, `nilai`) VALUES
(145, 1, 2, 4),
(146, 1, 3, 1),
(147, 1, 4, 1),
(148, 1, 5, 1),
(149, 2, 1, 0.25),
(150, 2, 3, 5),
(151, 2, 4, 1),
(152, 2, 5, 1),
(153, 3, 1, 1),
(154, 3, 2, 0.2),
(155, 3, 4, 4),
(156, 3, 5, 1),
(157, 4, 1, 1),
(158, 4, 2, 1),
(159, 4, 3, 0.25),
(160, 4, 5, 7),
(161, 5, 1, 1),
(162, 5, 2, 1),
(163, 5, 3, 1),
(164, 5, 4, 0.142857);

-- --------------------------------------------------------

--
-- Struktur dari tabel `perbandingan_modal`
--

CREATE TABLE `perbandingan_modal` (
  `id` int NOT NULL,
  `kriteria_1` int NOT NULL,
  `kriteria_2` int NOT NULL,
  `nilai` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `perbandingan_modal`
--

INSERT INTO `perbandingan_modal` (`id`, `kriteria_1`, `kriteria_2`, `nilai`) VALUES
(19, 1, 2, 3),
(20, 1, 3, 2),
(21, 2, 1, 0.333333),
(22, 2, 3, 1),
(23, 3, 1, 0.5),
(24, 3, 2, 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `role_permissions`
--

CREATE TABLE `role_permissions` (
  `id` int NOT NULL,
  `name` varchar(100) NOT NULL,
  `display_name` varchar(100) NOT NULL,
  `module` varchar(50) NOT NULL,
  `description` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `role_permissions`
--

INSERT INTO `role_permissions` (`id`, `name`, `display_name`, `module`, `description`) VALUES
(1, 'user.view', 'Lihat User', 'user', 'Melihat daftar user'),
(2, 'user.add', 'Tambah User', 'user', 'Menambahkan user baru'),
(3, 'user.edit', 'Edit User', 'user', 'Mengubah data user'),
(4, 'user.delete', 'Hapus User', 'user', 'Menghapus user'),
(5, 'role.view', 'Lihat Role', 'role', 'Melihat daftar role'),
(6, 'role.add', 'Tambah Role', 'role', 'Menambahkan role baru'),
(7, 'role.edit', 'Edit Role', 'role', 'Mengubah data role'),
(8, 'role.delete', 'Hapus Role', 'role', 'Menghapus role'),
(9, 'nasabah.view', 'Lihat Nasabah', 'nasabah', 'Melihat daftar nasabah'),
(10, 'nasabah.add', 'Tambah Nasabah', 'nasabah', 'Menambahkan nasabah baru'),
(11, 'nasabah.edit', 'Edit Nasabah', 'nasabah', 'Mengubah data nasabah'),
(12, 'nasabah.delete', 'Hapus Nasabah', 'nasabah', 'Menghapus nasabah'),
(13, 'pengajuan.view', 'Lihat Pengajuan', 'pengajuan', 'Melihat daftar pengajuan'),
(14, 'pengajuan.add', 'Tambah Pengajuan', 'pengajuan', 'Menambahkan pengajuan baru'),
(15, 'pengajuan.edit', 'Edit Pengajuan', 'pengajuan', 'Mengubah data pengajuan'),
(16, 'pengajuan.delete', 'Hapus Pengajuan', 'pengajuan', 'Menghapus pengajuan'),
(17, 'pengajuan.approve', 'Approve Pengajuan', 'pengajuan', 'Menyetujui pengajuan'),
(18, 'pengajuan.reject', 'Reject Pengajuan', 'pengajuan', 'Menolak pengajuan'),
(19, 'bobot.view', 'Lihat Bobot', 'bobot', 'Melihat daftar bobot'),
(20, 'bobot.add', 'Tambah Bobot', 'bobot', 'Menambahkan bobot baru'),
(21, 'bobot.edit', 'Edit Bobot', 'bobot', 'Mengubah data bobot'),
(22, 'bobot.delete', 'Hapus Bobot', 'bobot', 'Menghapus bobot'),
(23, 'perhitungan.view', 'Lihat Perhitungan', 'perhitungan', 'Melihat hasil perhitungan'),
(24, 'perhitungan.calculate', 'Hitung', 'perhitungan', 'Melakukan perhitungan'),
(25, 'perhitungan.export', 'Export Hasil', 'perhitungan', 'Mengexport hasil perhitungan');

-- --------------------------------------------------------

--
-- Struktur dari tabel `role_permission_map`
--

CREATE TABLE `role_permission_map` (
  `role_id` int NOT NULL,
  `permission_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `subkriteria`
--

CREATE TABLE `subkriteria` (
  `id` int NOT NULL,
  `kriteria_id` int NOT NULL,
  `kode_subkriteria` varchar(20) NOT NULL,
  `nama_subkriteria` varchar(100) NOT NULL,
  `nilai` decimal(10,4) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `role` enum('admin','manager','staff') NOT NULL,
  `status` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `nama_lengkap`, `email`, `role`, `status`, `created_at`, `updated_at`) VALUES
(5, 'hasyim2', '$2y$10$H84gsJxdB2pR7SJoFDdEe.i3oKVagKYm7hU34fKlpBNDSOvu8j1Ye', 'Muhammad Hasyim', 'hasyim2@gmail.com', 'admin', 1, '2025-05-05 04:39:10', '2025-05-05 07:00:22'),
(6, 'pedro1', '$2y$10$FLAO/llN9SHLAS7xPtHgW.LEPb/XnjWjce.9SC0LH.ocM23Ikn8N.', 'Pedro Joseph ', 'pedrojoseph@gmail.com', 'admin', 1, '2025-05-05 07:04:47', '2025-05-05 07:04:47'),
(7, 'andri', '$2y$10$u6hw22BKvGOC2Exeu0KwE.8ry5mGtXaVjWW5NcC7fNw5y5S2Y89ji', 'andri sutrisno', 'andri@gmail.com', 'staff', 1, '2025-05-05 07:05:15', '2025-05-05 07:05:15');

-- --------------------------------------------------------

--
-- Struktur dari tabel `user_roles`
--

CREATE TABLE `user_roles` (
  `id` int NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` text,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `dokumen_pengajuan`
--
ALTER TABLE `dokumen_pengajuan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pengajuan_id` (`pengajuan_id`);

--
-- Indeks untuk tabel `hasil_perhitungan`
--
ALTER TABLE `hasil_perhitungan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `nasabah_id` (`nasabah_id`),
  ADD KEY `pengajuan_id` (`pengajuan_id`);

--
-- Indeks untuk tabel `kriteria`
--
ALTER TABLE `kriteria`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `log_aktivitas`
--
ALTER TABLE `log_aktivitas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indeks untuk tabel `nasabah`
--
ALTER TABLE `nasabah`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode_nasabah` (`kode_nasabah`),
  ADD UNIQUE KEY `nik` (`nik`);

--
-- Indeks untuk tabel `pengajuan`
--
ALTER TABLE `pengajuan`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode_pengajuan` (`kode_pengajuan`),
  ADD KEY `nasabah_id` (`nasabah_id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indeks untuk tabel `perbandingan_ekonomi`
--
ALTER TABLE `perbandingan_ekonomi`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `perbandingan_jaminan`
--
ALTER TABLE `perbandingan_jaminan`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `perbandingan_karakter`
--
ALTER TABLE `perbandingan_karakter`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `perbandingan_kemampuan`
--
ALTER TABLE `perbandingan_kemampuan`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `perbandingan_kriteria`
--
ALTER TABLE `perbandingan_kriteria`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kriteria_1` (`kriteria_1`),
  ADD KEY `kriteria_2` (`kriteria_2`);

--
-- Indeks untuk tabel `perbandingan_modal`
--
ALTER TABLE `perbandingan_modal`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `role_permissions`
--
ALTER TABLE `role_permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indeks untuk tabel `role_permission_map`
--
ALTER TABLE `role_permission_map`
  ADD PRIMARY KEY (`role_id`,`permission_id`),
  ADD KEY `permission_id` (`permission_id`);

--
-- Indeks untuk tabel `subkriteria`
--
ALTER TABLE `subkriteria`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode_subkriteria` (`kode_subkriteria`),
  ADD KEY `kriteria_id` (`kriteria_id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indeks untuk tabel `user_roles`
--
ALTER TABLE `user_roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `dokumen_pengajuan`
--
ALTER TABLE `dokumen_pengajuan`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `hasil_perhitungan`
--
ALTER TABLE `hasil_perhitungan`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=112;

--
-- AUTO_INCREMENT untuk tabel `log_aktivitas`
--
ALTER TABLE `log_aktivitas`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `nasabah`
--
ALTER TABLE `nasabah`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT untuk tabel `pengajuan`
--
ALTER TABLE `pengajuan`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT untuk tabel `perbandingan_ekonomi`
--
ALTER TABLE `perbandingan_ekonomi`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT untuk tabel `perbandingan_jaminan`
--
ALTER TABLE `perbandingan_jaminan`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT untuk tabel `perbandingan_karakter`
--
ALTER TABLE `perbandingan_karakter`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT untuk tabel `perbandingan_kemampuan`
--
ALTER TABLE `perbandingan_kemampuan`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT untuk tabel `perbandingan_kriteria`
--
ALTER TABLE `perbandingan_kriteria`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=165;

--
-- AUTO_INCREMENT untuk tabel `perbandingan_modal`
--
ALTER TABLE `perbandingan_modal`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT untuk tabel `role_permissions`
--
ALTER TABLE `role_permissions`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT untuk tabel `subkriteria`
--
ALTER TABLE `subkriteria`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `user_roles`
--
ALTER TABLE `user_roles`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `dokumen_pengajuan`
--
ALTER TABLE `dokumen_pengajuan`
  ADD CONSTRAINT `dokumen_pengajuan_ibfk_1` FOREIGN KEY (`pengajuan_id`) REFERENCES `pengajuan` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `hasil_perhitungan`
--
ALTER TABLE `hasil_perhitungan`
  ADD CONSTRAINT `hasil_perhitungan_ibfk_1` FOREIGN KEY (`nasabah_id`) REFERENCES `nasabah` (`id`),
  ADD CONSTRAINT `hasil_perhitungan_ibfk_2` FOREIGN KEY (`pengajuan_id`) REFERENCES `pengajuan` (`id`);

--
-- Ketidakleluasaan untuk tabel `log_aktivitas`
--
ALTER TABLE `log_aktivitas`
  ADD CONSTRAINT `log_aktivitas_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Ketidakleluasaan untuk tabel `pengajuan`
--
ALTER TABLE `pengajuan`
  ADD CONSTRAINT `pengajuan_ibfk_1` FOREIGN KEY (`nasabah_id`) REFERENCES `nasabah` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pengajuan_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`);

--
-- Ketidakleluasaan untuk tabel `perbandingan_kriteria`
--
ALTER TABLE `perbandingan_kriteria`
  ADD CONSTRAINT `perbandingan_kriteria_ibfk_1` FOREIGN KEY (`kriteria_1`) REFERENCES `kriteria` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `perbandingan_kriteria_ibfk_2` FOREIGN KEY (`kriteria_2`) REFERENCES `kriteria` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `role_permission_map`
--
ALTER TABLE `role_permission_map`
  ADD CONSTRAINT `role_permission_map_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `user_roles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_permission_map_ibfk_2` FOREIGN KEY (`permission_id`) REFERENCES `role_permissions` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `subkriteria`
--
ALTER TABLE `subkriteria`
  ADD CONSTRAINT `subkriteria_ibfk_1` FOREIGN KEY (`kriteria_id`) REFERENCES `kriteria` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
