-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 04, 2025 at 01:43 PM
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
-- Table structure for table `jurnalumum`
--

CREATE TABLE `jurnalumum` (
  `id` bigint NOT NULL,
  `idcoa` bigint DEFAULT NULL,
  `idsandi` bigint DEFAULT '0' COMMENT 'dari tabel sandi',
  `idjenisjurnal` bigint NOT NULL,
  `idsumber` bigint DEFAULT '0' COMMENT 'dari tabel nasabah/anggota',
  `idtarget` bigint DEFAULT '0' COMMENT 'ke tabel nasabah/anggota',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `tgltransaksi` date DEFAULT NULL,
  `nomorb` int DEFAULT '1',
  `nomorbukti` varchar(25) DEFAULT NULL,
  `debet` decimal(15,0) DEFAULT '0',
  `kredit` decimal(15,0) DEFAULT '0',
  `tglposting` date DEFAULT NULL,
  `nomorposting` varchar(25) DEFAULT NULL,
  `keterangan` varchar(100) DEFAULT NULL,
  `pemberi` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL COMMENT 'Instansi pemberi',
  `penerima` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL COMMENT 'Instansi penerima',
  `namapenerima` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL COMMENT 'nama penerima' 
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `jurnalumum`
--
ALTER TABLE `jurnalumum`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_jurnalumum_1` (`idcoa`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `jurnalumum`
--
ALTER TABLE `jurnalumum`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `jurnalumum`
--
ALTER TABLE `jurnalumum`
  ADD CONSTRAINT `fk_jurnalumum_1` FOREIGN KEY (`idcoa`) REFERENCES `coa` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
