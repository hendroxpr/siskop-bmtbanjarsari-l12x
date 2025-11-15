-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 07, 2025 at 09:10 AM
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
-- Table structure for table `jurnalpinjaman`
--

CREATE TABLE `jurnalpinjaman` (
  `id` bigint NOT NULL,
  `kode` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL COMMENT 'kode pinjaman. Contoh 20251103.02.0000001',
  `idcoa` bigint DEFAULT NULL,
  `idsandi` bigint DEFAULT '0' COMMENT 'dari tabel sandi',
  `idjenisjurnal` bigint DEFAULT '0' COMMENT 'dari tabel jenisjurnal',
  `idjenispinjaman` bigint DEFAULT '0' COMMENT 'dari tabel jenispinjaman',
  `idanggota` bigint DEFAULT '0' COMMENT 'ke tabel anggota',
  `idtarget` bigint DEFAULT NULL COMMENT 'id jurnalpinjaman saat pengajuan pinjaman ',
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
  `tipe` int DEFAULT '1' COMMENT 'tipe : 1 bulanan, 2 bulanan, 3 bulanan dll',
  `xangsuran` int DEFAULT '1' COMMENT 'angsuran berapa kali',
  `nilaiangsuran` decimal(15,0) DEFAULT '0' COMMENT 'nilai tiap angsuran',
  `ke` int DEFAULT '0' COMMENT 'angsuran ke-n',
  `ujroh` decimal(15,0) DEFAULT '0' COMMENT 'ujroh angsuran',
  `jatuhtempo` date DEFAULT NULL COMMENT 'tgl jatuh tempo',
  `lb` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT 'Belum', COMMENT 'Lunas, belum'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `jurnalpinjaman`
--
ALTER TABLE `jurnalpinjaman`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `jurnalpinjaman`
--
ALTER TABLE `jurnalpinjaman`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
