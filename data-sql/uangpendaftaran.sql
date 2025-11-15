-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 02, 2025 at 09:04 AM
-- Server version: 8.0.30
-- PHP Version: 8.3.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_siskop_bmtbanjarsari`
--

-- --------------------------------------------------------

--
-- Table structure for table `uangpendaftaran`
--

CREATE TABLE `uangpendaftaran` (
  `id` bigint NOT NULL,
  `idanggota` bigint DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `tgltransaksi` date DEFAULT NULL,
  `nomorb` int DEFAULT '1',
  `nomorbukti` varchar(25) DEFAULT NULL,
  `jml` decimal(15,0) DEFAULT '0',
  `tglposting` date DEFAULT NULL,
  `nomorposting` varchar(25) DEFAULT NULL,
  `keterangan` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `uangpendaftaran`
--
ALTER TABLE `uangpendaftaran`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_uangpendaftaran_1` (`idanggota`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `uangpendaftaran`
--
ALTER TABLE `uangpendaftaran`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `uangpendaftaran`
--
ALTER TABLE `uangpendaftaran`
  ADD CONSTRAINT `fk_uangpendaftaran_1` FOREIGN KEY (`idanggota`) REFERENCES `anggota` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
