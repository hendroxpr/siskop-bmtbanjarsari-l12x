-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 12, 2025 at 12:54 PM
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
-- Table structure for table `jurnalsimpanan`
--

CREATE TABLE `jurnalsimpanan` (
  `id` bigint NOT NULL,
  `idcoa` bigint DEFAULT NULL,
  `idsandi` bigint DEFAULT '0' COMMENT 'dari tabel sandi',
  `idjenisjurnal` bigint DEFAULT '0' COMMENT 'dari tabel jenisjurnal',
  `idjenissimpanan` bigint DEFAULT '0' COMMENT 'dari tabel jenissimpanan',
  `idnasabah` bigint DEFAULT '0' COMMENT 'ke tabel nasabah',
  `idtarget` bigint DEFAULT NULL COMMENT 'idnasabah yang simpan',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `tgltransaksi` date DEFAULT NULL,
  `nomorb` int DEFAULT '1',
  `nomorbukti` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `debet` decimal(15,0) DEFAULT '0',
  `kredit` decimal(15,0) DEFAULT '0',
  `tglposting` date DEFAULT NULL,
  `nomorposting` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `keterangan` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `hitungujroh` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT 'Belum' COMMENT 'Sudah (sudah dihitung), Belum (belum dihitung)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `jurnalsimpanan`
--
ALTER TABLE `jurnalsimpanan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_jurnalsimpanan_1` (`idcoa`),
  ADD KEY `fk_jurnalsimpanan_2` (`idsandi`),
  ADD KEY `fk_jurnalsimpanan_3` (`idjenisjurnal`),
  ADD KEY `fk_jurnalsimpanan_4` (`idjenissimpanan`),
  ADD KEY `fk_jurnalsimpanan_5` (`idnasabah`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `jurnalsimpanan`
--
ALTER TABLE `jurnalsimpanan`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `jurnalsimpanan`
--
ALTER TABLE `jurnalsimpanan`
  ADD CONSTRAINT `fk_jurnalsimpanan_1` FOREIGN KEY (`idcoa`) REFERENCES `coa` (`id`),
  ADD CONSTRAINT `fk_jurnalsimpanan_2` FOREIGN KEY (`idsandi`) REFERENCES `sandi` (`id`),
  ADD CONSTRAINT `fk_jurnalsimpanan_3` FOREIGN KEY (`idjenisjurnal`) REFERENCES `jenisjurnal` (`id`),
  ADD CONSTRAINT `fk_jurnalsimpanan_4` FOREIGN KEY (`idjenissimpanan`) REFERENCES `jenissimpanan` (`id`),
  ADD CONSTRAINT `fk_jurnalsimpanan_5` FOREIGN KEY (`idnasabah`) REFERENCES `nasabah` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
