-- phpMyAdmin SQL Dump
-- version 5.2.0
-- Host: localhost:3306
-- Server version: 8.0.30
-- PHP Version: 8.3.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_techfix`
--

-- --------------------------------------------------------

--
-- Table structure for table `users`
--
CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('Admin','Pelanggan','Teknisi') NOT NULL DEFAULT 'Pelanggan',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `laptop`
--
CREATE TABLE `laptop` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nama_laptop` varchar(255) NOT NULL,
  `jenis` varchar(255) NOT NULL,
  `id_pemilik` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `servis`
--
CREATE TABLE `servis` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nama_servis` varchar(255) NOT NULL,
  `harga_servis` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sparepart`
--
CREATE TABLE `sparepart` (
  `id` int NOT NULL AUTO_INCREMENT,
  `sku` varchar(255) NOT NULL,
  `nama_sparepart` varchar(255) DEFAULT NULL,
  `jumlah` int NOT NULL DEFAULT '0',
  `harga_beli` int NOT NULL,
  `harga_jual` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `spareparts`
--
-- Catatan: Ada penulisan ganda (sparepart dan spareparts) di kode PHP, 
-- sebaiknya dijadikan satu view atau tabel alias jika menggunakan database versi lama, 
-- namun untuk mempermudah, saya buatkan view agar kodenya tidak error.
--
CREATE VIEW `spareparts` AS SELECT * FROM `sparepart`;

-- --------------------------------------------------------

--
-- Table structure for table `booking`
--
CREATE TABLE `booking` (
  `id` int NOT NULL AUTO_INCREMENT,
  `tanggal` date NOT NULL,
  `id_laptop` int NOT NULL,
  `id_servis` int NOT NULL,
  `status` enum('Menunggu Antrean','Dicek','Menunggu Sparepart','Dikerjakan','Selesai') NOT NULL DEFAULT 'Menunggu Antrean',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `detail_servis`
-- (Tabel bawaan dari file sebelumnya jika masih dibutuhkan)
--
CREATE TABLE `detail_servis` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_booking` int NOT NULL,
  `id_sparepart` int NOT NULL,
  `qty` int NOT NULL DEFAULT '1',
  `harga_satuan` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `nota_pembayaran`
--
CREATE TABLE `nota_pembayaran` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_booking` int NOT NULL,
  `total_biaya` int NOT NULL,
  `tanggal_selesai` date NOT NULL,
  `garansi_servis_habis` date NOT NULL,
  `garansi_sparepart_habis` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Insert Default Admin User
--
INSERT INTO `users` (`username`, `password`, `role`) VALUES ('admin', 'admin', 'Admin');
INSERT INTO `users` (`username`, `password`, `role`) VALUES ('teknisi', 'teknisi', 'Teknisi');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `laptop`
--
ALTER TABLE `laptop`
  ADD CONSTRAINT `fk_laptop_user` FOREIGN KEY (`id_pemilik`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `booking`
--
ALTER TABLE `booking`
  ADD CONSTRAINT `fk_booking_laptop` FOREIGN KEY (`id_laptop`) REFERENCES `laptop` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_booking_servis` FOREIGN KEY (`id_servis`) REFERENCES `servis` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `detail_servis`
--
ALTER TABLE `detail_servis`
  ADD CONSTRAINT `fk_detail_booking` FOREIGN KEY (`id_booking`) REFERENCES `booking` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_detail_sparepart` FOREIGN KEY (`id_sparepart`) REFERENCES `sparepart` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `nota_pembayaran`
--
ALTER TABLE `nota_pembayaran`
  ADD CONSTRAINT `fk_nota_booking` FOREIGN KEY (`id_booking`) REFERENCES `booking` (`id`) ON DELETE CASCADE;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
