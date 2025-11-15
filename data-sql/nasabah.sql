-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 12, 2025 at 12:55 PM
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
-- Table structure for table `nasabah`
--

CREATE TABLE `nasabah` (
  `id` bigint NOT NULL,
  `idjenissimpanan` bigint DEFAULT '1',
  `idanggota` bigint DEFAULT NULL,
  `nama` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `norek` varchar(25) NOT NULL,
  `nia` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `nik` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `ecard` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `desain` varchar(20) DEFAULT 'INDIVIDUAL',
  `tandapengenal` varchar(30) DEFAULT 'KTP (KARTU TANDA PENDUDUK)',
  `tgldaftar` date DEFAULT NULL,
  `tglkeluar` date DEFAULT NULL,
  `tgllahir` date DEFAULT NULL,
  `alamat` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `telp` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `aktif` varchar(1) DEFAULT NULL,
  `saldo` decimal(15,0) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `nasabah`
--

INSERT INTO `nasabah` (`id`, `idjenissimpanan`, `idanggota`, `nama`, `norek`, `nia`, `nik`, `ecard`, `desain`, `tandapengenal`, `tgldaftar`, `tglkeluar`, `tgllahir`, `alamat`, `telp`, `aktif`, `saldo`) VALUES
(4, 1, 4, 'SUSI', '2023072601095908703', '202510130001', 'K-202510130001', 'E-202510130001', 'INDIVIDUAL', 'KTP (KARTU TANDA PENDUDUK)', '2023-07-26', NULL, '2034-10-04', 'JL. CEMORO SEWU', '0998877', 'N', '0'),
(6, 2, 3, 'NIA RAMADHANI', '2023072602104915206', '202510120003', 'K-202510120003', 'E-202510120003', 'INDIVIDUAL', 'KTP (KARTU TANDA PENDUDUK)', '2023-07-26', '2023-03-01', '2025-11-05', 'JL. PELANGI NO. 1', '12345689899900', 'N', '0'),
(7, 3, 3, 'NIA RAMADHANI', '2023072603101134888', '202510120003', 'K-202510120003', 'E-202510120003', 'INDIVIDUAL', 'KARTU PELAJAR', '2023-07-26', '2023-03-01', '2025-11-05', 'JL. PELANGI NO. 1', '12345689899900', 'N', '0'),
(14, 1, 3, 'NIA RAMADHANI', '2023072601100146399', '202510120003', 'K-202510120003', 'E-202510120003', 'INDIVIDUAL', 'KTP (KARTU TANDA PENDUDUK)', '2023-07-26', NULL, '2025-11-05', 'JL. PELANGI NO. 1', '12345689899900', 'Y', '0'),
(38, 1, 3, 'NIA RAMADHANI', '2023080501202412664', '202510120003', 'K-202510120003', 'E-202510120003', 'INDIVIDUAL', 'KARTU PELAJAR', '2023-08-05', '2023-03-01', '2025-11-05', 'JL. PELANGI NO. 1', '12345689899900', 'N', '0'),
(39, 1, 6, 'NOPITA', '2023080501202427488', '202510180002', 'K-202510180002', 'E-202510180002', 'INDIVIDUAL', 'KTP (KARTU TANDA PENDUDUK)', '2023-08-05', '2023-03-01', '2025-10-05', 'JL. SEGORO KIDUL', '88888', 'N', '0'),
(40, 1, 3, 'NIA RAMADHANI', '2023080501202004215', '202510120003', 'K-202510120003', 'E-202510120003', 'INDIVIDUAL', 'KTP (KARTU TANDA PENDUDUK)', '2023-08-05', '2023-03-01', '2025-11-05', 'JL. PELANGI NO. 1', '12345689899900', 'N', '0'),
(44, 2, 4, 'SUSI', '2025011402104926878', '202510130001', 'K-202510130001', 'E-202510130001', 'INDIVIDUAL', 'KTP (KARTU TANDA PENDUDUK)', '2025-01-14', NULL, '2034-10-04', 'JL. CEMORO SEWU', '0998877', 'Y', '0'),
(47, 1, 3, 'NIA RAMADHANI', '2025111201061649436', '202510120003', 'K-202510120003', 'E-202510120003', 'INDIVIDUAL', 'KTP (KARTU TANDA PENDUDUK)', '2025-11-12', NULL, '2025-11-05', 'JL. PELANGI NO. 1', '12345689899900', 'Y', '0');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `nasabah`
--
ALTER TABLE `nasabah`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unq4_nasabah` (`norek`),
  ADD KEY `fk_nasabah_2` (`idanggota`),
  ADD KEY `fk_nasabah_1` (`idjenissimpanan`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `nasabah`
--
ALTER TABLE `nasabah`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `nasabah`
--
ALTER TABLE `nasabah`
  ADD CONSTRAINT `fk_nasabah_1` FOREIGN KEY (`idjenissimpanan`) REFERENCES `jenissimpanan` (`id`),
  ADD CONSTRAINT `fk_nasabah_2` FOREIGN KEY (`idanggota`) REFERENCES `anggota` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
