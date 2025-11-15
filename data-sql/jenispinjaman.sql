-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 06, 2025 at 03:24 AM
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
-- Table structure for table `jenispinjaman`
--

CREATE TABLE `jenispinjaman` (
  `id` bigint NOT NULL,
  `kode` varchar(2) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `jenispinjaman` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `ujroha` decimal(15,0) DEFAULT '1' COMMENT 'pembilang ujroh',
  `ujrohb` decimal(15,0) DEFAULT '1' COMMENT 'pembilang ujroh',
  `idcoa01d` bigint DEFAULT NULL COMMENT 'd-pokok',
  `idcoa01k` bigint DEFAULT NULL COMMENT 'k-pokok',
  `idcoa02d` bigint DEFAULT NULL COMMENT 'd-angsur',
  `idcoa02k` bigint DEFAULT NULL COMMENT 'k-angsur',
  `idcoa03d` bigint DEFAULT NULL COMMENT 'd-ujroh',
  `idcoa03k` bigint DEFAULT NULL COMMENT 'k-ujroh',
  `idcoa04d` bigint DEFAULT NULL COMMENT 'd-admin',
  `idcoa04k` bigint DEFAULT NULL COMMENT 'k-admin',
  `idcoa05d` bigint DEFAULT NULL COMMENT 'd-diskon',
  `idcoa05k` bigint DEFAULT NULL COMMENT 'k-diskon',
  `idcoa06d` bigint DEFAULT NULL COMMENT 'd-denda',
  `idcoa06k` bigint DEFAULT NULL COMMENT 'k-denda',
  `idjenisjurnal01d` bigint DEFAULT NULL,
  `idjenisjurnal01k` bigint DEFAULT NULL,
  `idjenisjurnal02d` bigint DEFAULT NULL,
  `idjenisjurnal02k` bigint DEFAULT NULL,
  `idjenisjurnal03d` bigint DEFAULT NULL,
  `idjenisjurnal03k` bigint DEFAULT NULL,
  `idjenisjurnal04d` bigint DEFAULT NULL,
  `idjenisjurnal04k` bigint DEFAULT NULL,
  `idjenisjurnal05d` bigint DEFAULT NULL,
  `idjenisjurnal05k` bigint DEFAULT NULL,
  `idjenisjurnal06d` bigint DEFAULT NULL,
  `idjenisjurnal06k` bigint DEFAULT NULL,
  `keterangan` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `jenispinjaman`
--
ALTER TABLE `jenispinjaman`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_jenispinjaman_coa01d` (`idcoa01d`),
  ADD KEY `fk_jenispinjaman_coa01k` (`idcoa01k`),
  ADD KEY `fk_jenispinjaman_coa02d` (`idcoa02d`),
  ADD KEY `fk_jenispinjaman_coa02k` (`idcoa02k`),
  ADD KEY `fk_jenispinjaman_coa03d` (`idcoa03d`),
  ADD KEY `fk_jenispinjaman_coa03k` (`idcoa03k`),
  ADD KEY `fk_jenispinjaman_coa04d` (`idcoa04d`),
  ADD KEY `fk_jenispinjaman_coa04k` (`idcoa04k`),
  ADD KEY `fk_jenispinjaman_coa05d` (`idcoa05d`),
  ADD KEY `fk_jenispinjaman_coa05k` (`idcoa05k`),
  ADD KEY `fk_jenispinjaman_coa06d` (`idcoa06d`),
  ADD KEY `fk_jenispinjaman_coa06k` (`idcoa06k`),
  ADD KEY `fk_jenispinjaman_jj01d` (`idjenisjurnal01d`),
  ADD KEY `fk_jenispinjaman_jj01k` (`idjenisjurnal01k`),
  ADD KEY `fk_jenispinjaman_jj02d` (`idjenisjurnal02d`),
  ADD KEY `fk_jenispinjaman_jj02k` (`idjenisjurnal02k`),
  ADD KEY `fk_jenispinjaman_jj03d` (`idjenisjurnal03d`),
  ADD KEY `fk_jenispinjaman_jj03k` (`idjenisjurnal03k`),
  ADD KEY `fk_jenispinjaman_jj04d` (`idjenisjurnal04d`),
  ADD KEY `fk_jenispinjaman_jj04k` (`idjenisjurnal04k`),
  ADD KEY `fk_jenispinjaman_jj05d` (`idjenisjurnal05d`),
  ADD KEY `fk_jenispinjaman_jj05k` (`idjenisjurnal05k`),
  ADD KEY `fk_jenispinjaman_jj06d` (`idjenisjurnal06d`),
  ADD KEY `fk_jenispinjaman_jj06k` (`idjenisjurnal06k`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `jenispinjaman`
--
ALTER TABLE `jenispinjaman`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `jenispinjaman`
--
ALTER TABLE `jenispinjaman`
  ADD CONSTRAINT `fk_jenispinjaman_coa01d` FOREIGN KEY (`idcoa01d`) REFERENCES `coa` (`id`),
  ADD CONSTRAINT `fk_jenispinjaman_coa01k` FOREIGN KEY (`idcoa01k`) REFERENCES `coa` (`id`),
  ADD CONSTRAINT `fk_jenispinjaman_coa02d` FOREIGN KEY (`idcoa02d`) REFERENCES `coa` (`id`),
  ADD CONSTRAINT `fk_jenispinjaman_coa02k` FOREIGN KEY (`idcoa02k`) REFERENCES `coa` (`id`),
  ADD CONSTRAINT `fk_jenispinjaman_coa03d` FOREIGN KEY (`idcoa03d`) REFERENCES `coa` (`id`),
  ADD CONSTRAINT `fk_jenispinjaman_coa03k` FOREIGN KEY (`idcoa03k`) REFERENCES `coa` (`id`),
  ADD CONSTRAINT `fk_jenispinjaman_coa04d` FOREIGN KEY (`idcoa04d`) REFERENCES `coa` (`id`),
  ADD CONSTRAINT `fk_jenispinjaman_coa04k` FOREIGN KEY (`idcoa04k`) REFERENCES `coa` (`id`),
  ADD CONSTRAINT `fk_jenispinjaman_coa05d` FOREIGN KEY (`idcoa05d`) REFERENCES `coa` (`id`),
  ADD CONSTRAINT `fk_jenispinjaman_coa05k` FOREIGN KEY (`idcoa05k`) REFERENCES `coa` (`id`),
  ADD CONSTRAINT `fk_jenispinjaman_coa06d` FOREIGN KEY (`idcoa06d`) REFERENCES `coa` (`id`),
  ADD CONSTRAINT `fk_jenispinjaman_coa06k` FOREIGN KEY (`idcoa06k`) REFERENCES `coa` (`id`),
  ADD CONSTRAINT `fk_jenispinjaman_jj01d` FOREIGN KEY (`idjenisjurnal01d`) REFERENCES `jenisjurnal` (`id`),
  ADD CONSTRAINT `fk_jenispinjaman_jj01k` FOREIGN KEY (`idjenisjurnal01k`) REFERENCES `jenisjurnal` (`id`),
  ADD CONSTRAINT `fk_jenispinjaman_jj02d` FOREIGN KEY (`idjenisjurnal02d`) REFERENCES `jenisjurnal` (`id`),
  ADD CONSTRAINT `fk_jenispinjaman_jj02k` FOREIGN KEY (`idjenisjurnal02k`) REFERENCES `jenisjurnal` (`id`),
  ADD CONSTRAINT `fk_jenispinjaman_jj03d` FOREIGN KEY (`idjenisjurnal03d`) REFERENCES `jenisjurnal` (`id`),
  ADD CONSTRAINT `fk_jenispinjaman_jj03k` FOREIGN KEY (`idjenisjurnal03k`) REFERENCES `jenisjurnal` (`id`),
  ADD CONSTRAINT `fk_jenispinjaman_jj04d` FOREIGN KEY (`idjenisjurnal04d`) REFERENCES `jenisjurnal` (`id`),
  ADD CONSTRAINT `fk_jenispinjaman_jj04k` FOREIGN KEY (`idjenisjurnal04k`) REFERENCES `jenisjurnal` (`id`),
  ADD CONSTRAINT `fk_jenispinjaman_jj05d` FOREIGN KEY (`idjenisjurnal05d`) REFERENCES `jenisjurnal` (`id`),
  ADD CONSTRAINT `fk_jenispinjaman_jj05k` FOREIGN KEY (`idjenisjurnal05k`) REFERENCES `jenisjurnal` (`id`),
  ADD CONSTRAINT `fk_jenispinjaman_jj06d` FOREIGN KEY (`idjenisjurnal06d`) REFERENCES `jenisjurnal` (`id`),
  ADD CONSTRAINT `fk_jenispinjaman_jj06k` FOREIGN KEY (`idjenisjurnal06k`) REFERENCES `jenisjurnal` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
