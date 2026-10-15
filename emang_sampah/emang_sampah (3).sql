-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 13, 2026 at 07:32 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `emang_sampah`
--

-- --------------------------------------------------------

--
-- Table structure for table `pembelian`
--

CREATE TABLE `pembelian` (
  `id_beli` int(11) NOT NULL,
  `id_pengepul` int(11) NOT NULL,
  `id_sampah` int(11) NOT NULL,
  `berat_kg` decimal(8,2) NOT NULL,
  `total_rp` decimal(12,2) NOT NULL,
  `status` enum('pending','disetujui','ditolak') DEFAULT 'pending',
  `tanggal` datetime DEFAULT current_timestamp(),
  `tanggal_proses` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pembelian`
--

INSERT INTO `pembelian` (`id_beli`, `id_pengepul`, `id_sampah`, `berat_kg`, `total_rp`, `status`, `tanggal`, `tanggal_proses`) VALUES
(1, 4, 1, 2.00, 5000.00, 'disetujui', '2026-09-28 12:52:18', '2026-09-28 12:52:53'),
(2, 4, 1, 5.00, 12500.00, 'disetujui', '2026-10-01 09:23:21', '2026-10-01 09:23:49'),
(3, 4, 1, 1.00, 2500.00, 'disetujui', '2026-10-12 13:28:12', '2026-10-12 13:31:39'),
(4, 4, 4, 100.00, 250000.00, 'disetujui', '2026-10-13 11:02:52', '2026-10-13 11:03:06'),
(5, 4, 4, 500.00, 1250000.00, 'disetujui', '2026-10-13 11:04:19', '2026-10-13 11:04:34'),
(6, 4, 4, 20.00, 50000.00, 'disetujui', '2026-10-13 12:28:19', '2026-10-13 12:28:37');

-- --------------------------------------------------------

--
-- Table structure for table `penarikan`
--

CREATE TABLE `penarikan` (
  `id_tarik` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `jumlah` decimal(12,2) NOT NULL,
  `status` enum('pending','disetujui','ditolak') DEFAULT 'pending',
  `tanggal` datetime DEFAULT current_timestamp(),
  `tanggal_proses` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `penarikan`
--

INSERT INTO `penarikan` (`id_tarik`, `id_user`, `jumlah`, `status`, `tanggal`, `tanggal_proses`) VALUES
(1, 2, 2000.00, 'disetujui', '2026-09-28 13:15:40', '2026-09-28 13:17:18'),
(2, 2, 2000.00, 'ditolak', '2026-09-28 13:18:25', '2026-09-28 13:18:42');

-- --------------------------------------------------------

--
-- Table structure for table `sampah`
--

CREATE TABLE `sampah` (
  `id_sampah` int(11) NOT NULL,
  `jenis` varchar(100) NOT NULL,
  `harga_setor_per_kg` decimal(10,2) NOT NULL,
  `harga_jual_per_kg` decimal(10,2) NOT NULL,
  `stok_kg` decimal(10,2) DEFAULT 0.00
) ;

--
-- Dumping data for table `sampah`
--

INSERT INTO `sampah` (`id_sampah`, `jenis`, `harga_setor_per_kg`, `harga_jual_per_kg`, `stok_kg`) VALUES
(1, 'Plastik', 2000.00, 2500.00, 247.00),
(2, 'Kertas', 1500.00, 1900.00, 0.00),
(4, 'botol', 2000.00, 2500.00, 480.00);

-- --------------------------------------------------------

--
-- Table structure for table `setoran`
--

CREATE TABLE `setoran` (
  `id_setoran` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_sampah` int(11) NOT NULL,
  `berat_kg` decimal(8,2) NOT NULL,
  `total_rp` decimal(12,2) NOT NULL,
  `status` enum('pending','disetujui','ditolak') DEFAULT 'pending',
  `tanggal` datetime DEFAULT current_timestamp(),
  `tanggal_proses` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `setoran`
--

INSERT INTO `setoran` (`id_setoran`, `id_user`, `id_sampah`, `berat_kg`, `total_rp`, `status`, `tanggal`, `tanggal_proses`) VALUES
(1, 2, 1, 3.00, 6000.00, 'disetujui', '2026-09-28 12:45:19', '2026-09-28 12:47:57'),
(2, 2, 1, 7.00, 14000.00, 'disetujui', '2026-10-01 09:01:02', '2026-10-01 09:07:34'),
(3, 3, 1, 90.00, 180000.00, 'disetujui', '2026-10-01 09:43:51', '2026-10-12 13:31:45'),
(4, 2, 4, 900.00, 1800000.00, 'disetujui', '2026-10-12 13:31:18', '2026-10-12 13:31:46'),
(5, 3, 1, 150.00, 300000.00, 'disetujui', '2026-10-13 11:05:34', '2026-10-13 11:05:49'),
(6, 2, 4, 200.00, 400000.00, 'disetujui', '2026-10-13 11:06:28', '2026-10-13 11:06:40'),
(7, 3, 1, 5.00, 10000.00, 'disetujui', '2026-10-13 12:26:28', '2026-10-13 12:26:42');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id_user` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','nasabah','pengepul') NOT NULL,
  `tipe_nasabah` enum('guru','murid') DEFAULT NULL,
  `saldo` decimal(12,2) DEFAULT 0.00
) ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id_user`, `nama`, `username`, `password`, `role`, `tipe_nasabah`, `saldo`) VALUES
(1, 'Admin', 'admin', '$2y$10$qHshmx4duReK7sWQnKeExu/SeGWWb6F1mcCg4bhptkIw.YuIDztsK', 'admin', NULL, 1640000.00),
(2, 'Budi Murid', 'budi', '$2y$10$nsx329Zf1nQ//QxVACb6KuGk.Po92N0WQMnUYYX21yC52iW0iSZtG', 'nasabah', 'murid', 2218000.00),
(3, 'Bu Guru', 'guru1', '$2y$10$JMUYUdr/Fh3DvBm0a.RkM.tPrNniHLu216oPaHZKVz13LXEMQoh3y', 'nasabah', 'guru', 490000.00),
(4, 'Pak Pengepul', 'pengepul', '$2y$10$uCzM7wuH4Vam6qStJq79BOU9osZ8xNnvWyfC0QGsjPItlq09tORmy', 'pengepul', NULL, 50000.00),
(5, 'siti', 'siti', '$2y$10$nMJeCeuTA83z4FYhgkj3GuJ7uAVv81BrwCVzUweL0HJ5J83poQoXW', 'nasabah', 'murid', 0.00);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `pembelian`
--
ALTER TABLE `pembelian`
  ADD PRIMARY KEY (`id_beli`),
  ADD KEY `id_pengepul` (`id_pengepul`),
  ADD KEY `id_sampah` (`id_sampah`);

--
-- Indexes for table `penarikan`
--
ALTER TABLE `penarikan`
  ADD PRIMARY KEY (`id_tarik`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `sampah`
--
ALTER TABLE `sampah`
  ADD PRIMARY KEY (`id_sampah`);

--
-- Indexes for table `setoran`
--
ALTER TABLE `setoran`
  ADD PRIMARY KEY (`id_setoran`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_sampah` (`id_sampah`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `pembelian`
--
ALTER TABLE `pembelian`
  MODIFY `id_beli` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `penarikan`
--
ALTER TABLE `penarikan`
  MODIFY `id_tarik` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `sampah`
--
ALTER TABLE `sampah`
  MODIFY `id_sampah` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `setoran`
--
ALTER TABLE `setoran`
  MODIFY `id_setoran` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `pembelian`
--
ALTER TABLE `pembelian`
  ADD CONSTRAINT `pembelian_ibfk_1` FOREIGN KEY (`id_pengepul`) REFERENCES `users` (`id_user`),
  ADD CONSTRAINT `pembelian_ibfk_2` FOREIGN KEY (`id_sampah`) REFERENCES `sampah` (`id_sampah`);

--
-- Constraints for table `penarikan`
--
ALTER TABLE `penarikan`
  ADD CONSTRAINT `penarikan_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`);

--
-- Constraints for table `setoran`
--
ALTER TABLE `setoran`
  ADD CONSTRAINT `setoran_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`),
  ADD CONSTRAINT `setoran_ibfk_2` FOREIGN KEY (`id_sampah`) REFERENCES `sampah` (`id_sampah`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
