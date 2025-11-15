-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 10, 2025 at 04:15 PM
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
-- Table structure for table `jenissimpanan`
--

CREATE TABLE `jenissimpanan` (
  `id` bigint NOT NULL,
  `kode` varchar(2) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT '01',
  `jenissimpanan` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `idcoasetord` bigint DEFAULT NULL,
  `idcoasetork` bigint DEFAULT NULL,
  `idcoatarikd` bigint DEFAULT NULL,
  `idcoatarikk` bigint DEFAULT NULL,
  `idcoatfkeluard` bigint DEFAULT NULL,
  `idcoatfkeluark` bigint DEFAULT NULL,
  `idcoatfmasukd` bigint DEFAULT NULL,
  `idcoatfmasukk` bigint DEFAULT NULL,
  `idcoaadmind` bigint DEFAULT NULL,
  `idcoaadmink` bigint DEFAULT NULL,
  `idcoaujrahd` bigint DEFAULT NULL,
  `idcoaujrahk` bigint DEFAULT NULL,
  `keterangan` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `idjenisjurnalsetord` bigint DEFAULT NULL,
  `idjenisjurnalsetork` bigint DEFAULT NULL,
  `idjenisjurnaltarikd` bigint DEFAULT NULL,
  `idjenisjurnaltarikk` bigint DEFAULT NULL,
  `idjenisjurnaltfkeluard` bigint DEFAULT NULL,
  `idjenisjurnaltfkeluark` bigint DEFAULT NULL,
  `idjenisjurnaltfmasukd` bigint DEFAULT NULL,
  `idjenisjurnaltfmasukk` bigint DEFAULT NULL,
  `idjenisjurnaladmind` bigint DEFAULT NULL,
  `idjenisjurnaladmink` bigint DEFAULT NULL,
  `idjenisjurnalujrahd` bigint DEFAULT NULL,
  `idjenisjurnalujrahk` bigint DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `jenissimpanan`
--

INSERT INTO `jenissimpanan` (`id`, `kode`, `jenissimpanan`, `idcoasetord`, `idcoasetork`, `idcoatarikd`, `idcoatarikk`, `idcoatfkeluard`, `idcoatfkeluark`, `idcoatfmasukd`, `idcoatfmasukk`, `idcoaadmind`, `idcoaadmink`, `idcoaujrahd`, `idcoaujrahk`, `keterangan`, `idjenisjurnalsetord`, `idjenisjurnalsetork`, `idjenisjurnaltarikd`, `idjenisjurnaltarikk`, `idjenisjurnaltfkeluard`, `idjenisjurnaltfkeluark`, `idjenisjurnaltfmasukd`, `idjenisjurnaltfmasukk`, `idjenisjurnaladmind`, `idjenisjurnaladmink`, `idjenisjurnalujrahd`, `idjenisjurnalujrahk`) VALUES
(1, '01', 'Si Syariah', 40, 6, 6, 40, 6, 6, 6, 6, NULL, NULL, NULL, NULL, 'Simpanan Syariah', 5, 1, 1, 4, 1, 1, 1, 1, NULL, NULL, NULL, NULL),
(2, '02', 'Si Walimah', 40, 18, 18, 40, 18, 18, 18, 18, NULL, NULL, NULL, NULL, 'Simpanan Walimah', 5, 1, 1, 4, 1, 1, 1, 1, NULL, NULL, NULL, NULL),
(3, '03', 'Si Hanum', 40, 20, 20, 40, 20, 20, 20, 20, NULL, NULL, NULL, NULL, 'Simpanan Haji dan Umroh', 5, 1, 1, 4, 1, 1, 1, 1, NULL, NULL, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `jenissimpanan`
--
ALTER TABLE `jenissimpanan`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unq1_jenissimpanan` (`jenissimpanan`),
  ADD KEY `fk_jenissimpanan_3` (`idcoatarikd`),
  ADD KEY `fk_jenissimpanan_4` (`idcoatarikk`),
  ADD KEY `fk_jenissimpanan_5` (`idcoatfmasukd`),
  ADD KEY `fk_jenissimpanan_6` (`idcoatfmasukk`),
  ADD KEY `fk_jenissimpanan_7` (`idcoatfkeluard`),
  ADD KEY `fk_jenissimpanan_8` (`idcoatfkeluark`),
  ADD KEY `fk_jenissimpanan_9` (`idjenisjurnalsetord`),
  ADD KEY `fk_jenissimpanan_10` (`idjenisjurnalsetork`),
  ADD KEY `fk_jenissimpanan_11` (`idjenisjurnaltarikd`),
  ADD KEY `fk_jenissimpanan_12` (`idjenisjurnaltarikk`),
  ADD KEY `fk_jenissimpanan_13` (`idjenisjurnaltfkeluard`),
  ADD KEY `fk_jenissimpanan_14` (`idjenisjurnaltfkeluark`),
  ADD KEY `fk_jenissimpanan_15` (`idjenisjurnaltfmasukd`),
  ADD KEY `fk_jenissimpanan_16` (`idjenisjurnaltfmasukk`),
  ADD KEY `fk_jenissimpanan_01` (`idcoasetord`),
  ADD KEY `fk_jenissimpanan_17` (`idcoaadmind`),
  ADD KEY `fk_jenissimpanan_18` (`idcoaadmink`),
  ADD KEY `fk_jenissimpanan_19` (`idcoaujrahd`),
  ADD KEY `fk_jenissimpanan_20` (`idcoaujrahk`),
  ADD KEY `fk_jenissimpanan_21` (`idjenisjurnaladmind`),
  ADD KEY `fk_jenissimpanan_22` (`idjenisjurnaladmink`),
  ADD KEY `fk_jenissimpanan_23` (`idjenisjurnalujrahd`),
  ADD KEY `fk_jenissimpanan_24` (`idjenisjurnalujrahk`),
  ADD KEY `fk_jenissimpanan_2` (`idcoasetork`) USING BTREE;

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `jenissimpanan`
--
ALTER TABLE `jenissimpanan`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `jenissimpanan`
--
ALTER TABLE `jenissimpanan`
  ADD CONSTRAINT `fk_jenissimpanan_1` FOREIGN KEY (`idcoasetord`) REFERENCES `coa` (`id`),
  ADD CONSTRAINT `fk_jenissimpanan_11` FOREIGN KEY (`idjenisjurnaltarikd`) REFERENCES `jenisjurnal` (`id`),
  ADD CONSTRAINT `fk_jenissimpanan_12` FOREIGN KEY (`idjenisjurnaltarikk`) REFERENCES `jenisjurnal` (`id`),
  ADD CONSTRAINT `fk_jenissimpanan_13` FOREIGN KEY (`idjenisjurnaltfkeluard`) REFERENCES `jenisjurnal` (`id`),
  ADD CONSTRAINT `fk_jenissimpanan_14` FOREIGN KEY (`idjenisjurnaltfkeluark`) REFERENCES `jenisjurnal` (`id`),
  ADD CONSTRAINT `fk_jenissimpanan_15` FOREIGN KEY (`idjenisjurnaltfmasukd`) REFERENCES `jenisjurnal` (`id`),
  ADD CONSTRAINT `fk_jenissimpanan_16` FOREIGN KEY (`idjenisjurnaltfmasukk`) REFERENCES `jenisjurnal` (`id`),
  ADD CONSTRAINT `fk_jenissimpanan_17` FOREIGN KEY (`idcoaadmind`) REFERENCES `coa` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `fk_jenissimpanan_18` FOREIGN KEY (`idcoaadmink`) REFERENCES `coa` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `fk_jenissimpanan_19` FOREIGN KEY (`idcoaujrahd`) REFERENCES `coa` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `fk_jenissimpanan_2` FOREIGN KEY (`idcoasetork`) REFERENCES `coa` (`id`),
  ADD CONSTRAINT `fk_jenissimpanan_20` FOREIGN KEY (`idcoaujrahk`) REFERENCES `coa` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `fk_jenissimpanan_21` FOREIGN KEY (`idjenisjurnaladmind`) REFERENCES `jenisjurnal` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `fk_jenissimpanan_22` FOREIGN KEY (`idjenisjurnaladmink`) REFERENCES `jenisjurnal` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `fk_jenissimpanan_23` FOREIGN KEY (`idjenisjurnalujrahd`) REFERENCES `jenisjurnal` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `fk_jenissimpanan_24` FOREIGN KEY (`idjenisjurnalujrahk`) REFERENCES `jenisjurnal` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `fk_jenissimpanan_3` FOREIGN KEY (`idcoatarikd`) REFERENCES `coa` (`id`),
  ADD CONSTRAINT `fk_jenissimpanan_4` FOREIGN KEY (`idcoatarikk`) REFERENCES `coa` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
