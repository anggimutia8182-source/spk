-- phpMyAdmin SQL Dump
-- SPK Identifikasi Jenis Gangguan Tidur Menggunakan Forward Chaining
-- Database: `spk_gangguan_tidur`

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `spk_gangguan_tidur`
--
CREATE DATABASE IF NOT EXISTS `spk_gangguan_tidur` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `spk_gangguan_tidur`;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user`
--

DROP TABLE IF EXISTS `tbl_konsultasi_gejala`;
DROP TABLE IF EXISTS `tbl_konsultasi`;
DROP TABLE IF EXISTS `tbl_rule_gejala`;
DROP TABLE IF EXISTS `tbl_rule`;
DROP TABLE IF EXISTS `tbl_gejala`;
DROP TABLE IF EXISTS `tbl_penyakit`;
DROP TABLE IF EXISTS `tbl_user`;

CREATE TABLE `tbl_user` (
  `id_user` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(20) NOT NULL DEFAULT 'user',
  PRIMARY KEY (`id_user`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_user`
-- Akun Login:
-- admin / admin123 (Pakar/Admin)
-- budi / user123 (Pasien)
-- siti / user123 (Pasien)
-- andi / user123 (Pasien)
-- user / user123 (Pasien Demo)
--

INSERT INTO `tbl_user` (`id_user`, `nama`, `username`, `password`, `role`) VALUES
(1, 'Administrator Pakar', 'admin', '$2y$10$P.RaNoi2TtKjYJzbff3pieKgkt2eKVpSahloMRfMrK8htMf1wzbSW', 'admin'),
(2, 'Budi Santoso', 'budi', '$2y$10$Sruj65ZgbOcfiBRJVmPzQezwIHNrr7FLw.DJPbY2tT2FAamjo5I6q', 'user'),
(3, 'Siti Aminah', 'siti', '$2y$10$Sruj65ZgbOcfiBRJVmPzQezwIHNrr7FLw.DJPbY2tT2FAamjo5I6q', 'user'),
(4, 'Andi Wijaya', 'andi', '$2y$10$Sruj65ZgbOcfiBRJVmPzQezwIHNrr7FLw.DJPbY2tT2FAamjo5I6q', 'user'),
(5, 'Pasien Demo', 'user', '$2y$10$Sruj65ZgbOcfiBRJVmPzQezwIHNrr7FLw.DJPbY2tT2FAamjo5I6q', 'user');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_gejala`
--

CREATE TABLE `tbl_gejala` (
  `kode_gejala` varchar(5) NOT NULL,
  `nama_gejala` varchar(150) NOT NULL,
  `keterangan` text,
  PRIMARY KEY (`kode_gejala`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_gejala`
--

INSERT INTO `tbl_gejala` (`kode_gejala`, `nama_gejala`, `keterangan`) VALUES
('G01', 'Sulit memulai tidur', 'Butuh waktu lama (>30 menit) untuk bisa tertidur'),
('G02', 'Sering terbangun di malam hari', 'Terbangun berkali-kali dan sulit tidur kembali'),
('G03', 'Merasa lelah meski sudah tidur cukup lama', 'Kualitas tidur dirasa tidak memulihkan energi'),
('G04', 'Mendengkur keras saat tidur', 'Suara dengkuran keras dan konsisten'),
('G05', 'Terengah-engah/tersedak saat tidur', 'Napas tersendat mendadak saat tidur'),
('G06', 'Mengalami episode berhenti napas saat tidur', 'Diamati oleh orang lain / alat monitor tidur'),
('G07', 'Kaki terasa tidak nyaman sebelum tidur', 'Sensasi seperti ditarik, gatal, atau kesemutan pada kaki'),
('G08', 'Gerakan kaki tak terkendali saat tidur', 'Kaki bergerak/menyentak secara berulang saat tidur'),
('G09', 'Rasa kantuk berlebihan di siang hari', 'Mengantuk berat meski tidur malam cukup'),
('G10', 'Tertidur tiba-tiba di situasi tidak tepat', 'Tertidur mendadak saat beraktivitas (mis. bekerja, makan)'),
('G11', 'Kehilangan kontrol otot mendadak (cataplexy)', 'Otot melemah tiba-tiba dipicu emosi kuat'),
('G12', 'Berjalan/berbicara saat tidur', 'Aktivitas motorik/verbal terjadi saat tertidur'),
('G13', 'Mimpi buruk berulang', 'Mimpi menakutkan yang sering terjadi dan mengganggu tidur'),
('G14', 'Kesulitan tidur berlangsung lebih dari 1 bulan', 'Durasi gangguan bersifat kronis');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_penyakit`
--

CREATE TABLE `tbl_penyakit` (
  `kode_penyakit` varchar(5) NOT NULL,
  `nama_penyakit` varchar(100) NOT NULL,
  `saran_penanganan` text,
  PRIMARY KEY (`kode_penyakit`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_penyakit`
--

INSERT INTO `tbl_penyakit` (`kode_penyakit`, `nama_penyakit`, `saran_penanganan`) VALUES
('P01', 'Insomnia', 'Terapkan sleep hygiene, hindari kafein malam hari, dan konsultasi ke dokter jika gangguan berlanjut.'),
('P02', 'Sleep Apnea (Apnea Tidur)', 'Periksa ke dokter spesialis paru/THT, evaluasi berat badan, gunakan posisi tidur miring, dan pertimbangkan terapi CPAP.'),
('P03', 'Restless Legs Syndrome (RLS)', 'Cek kadar zat besi dan ferritin darah, kurangi konsumsi kafein/nikotin, lakukan peregangan kaki ringan sebelum tidur, dan konsultasi ke dokter spesialis saraf.'),
('P04', 'Narkolepsi', 'Jadwalkan tidur siang singkat yang teratur, pertahankan pola tidur disiplin, hindari mengemudi saat kantuk berat, dan konsultasi ke dokter spesialis saraf untuk terapi farmakologis.'),
('P05', 'Parasomnia', 'Ciptakan lingkungan tidur yang aman (jauhkan benda tajam), kelola tingkat stres, pertahankan jadwal istirahat rutin, dan hubungi profesional medis jika membahayakan keselamatan diri.');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_rule`
--

CREATE TABLE `tbl_rule` (
  `id_rule` varchar(5) NOT NULL,
  `kode_penyakit` varchar(5) NOT NULL,
  PRIMARY KEY (`id_rule`),
  KEY `fk_rule_penyakit` (`kode_penyakit`),
  CONSTRAINT `fk_rule_penyakit` FOREIGN KEY (`kode_penyakit`) REFERENCES `tbl_penyakit` (`kode_penyakit`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_rule`
--

INSERT INTO `tbl_rule` (`id_rule`, `kode_penyakit`) VALUES
('R1', 'P01'),
('R2', 'P02'),
('R3', 'P03'),
('R4', 'P04'),
('R5', 'P05');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_rule_gejala`
--

CREATE TABLE `tbl_rule_gejala` (
  `id_rule` varchar(5) NOT NULL,
  `kode_gejala` varchar(5) NOT NULL,
  PRIMARY KEY (`id_rule`,`kode_gejala`),
  KEY `fk_rule_gejala_gejala` (`kode_gejala`),
  CONSTRAINT `fk_rule_gejala_gejala` FOREIGN KEY (`kode_gejala`) REFERENCES `tbl_gejala` (`kode_gejala`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_rule_gejala_rule` FOREIGN KEY (`id_rule`) REFERENCES `tbl_rule` (`id_rule`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_rule_gejala`
--

INSERT INTO `tbl_rule_gejala` (`id_rule`, `kode_gejala`) VALUES
('R1', 'G01'),
('R1', 'G02'),
('R1', 'G14'),
('R2', 'G04'),
('R2', 'G05'),
('R2', 'G06'),
('R3', 'G07'),
('R3', 'G08'),
('R4', 'G09'),
('R4', 'G10'),
('R4', 'G11'),
('R5', 'G12'),
('R5', 'G13');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_konsultasi`
--

CREATE TABLE `tbl_konsultasi` (
  `id_konsultasi` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `no_rm` varchar(20) DEFAULT NULL,
  `nama_pasien` varchar(100) DEFAULT NULL,
  `usia` int(11) DEFAULT NULL,
  `jenis_kelamin` varchar(20) DEFAULT NULL,
  `keluhan` text,
  `tanggal` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `hasil_diagnosa` varchar(5) DEFAULT NULL,
  PRIMARY KEY (`id_konsultasi`),
  KEY `fk_konsultasi_user` (`id_user`),
  KEY `fk_konsultasi_penyakit` (`hasil_diagnosa`),
  CONSTRAINT `fk_konsultasi_penyakit` FOREIGN KEY (`hasil_diagnosa`) REFERENCES `tbl_penyakit` (`kode_penyakit`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_konsultasi_user` FOREIGN KEY (`id_user`) REFERENCES `tbl_user` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_konsultasi`
--

INSERT INTO `tbl_konsultasi` (`id_konsultasi`, `id_user`, `no_rm`, `nama_pasien`, `usia`, `jenis_kelamin`, `keluhan`, `tanggal`, `hasil_diagnosa`) VALUES
(1, 2, 'RM-0001', 'Budi Santoso', 35, 'Laki-laki', 'Sulit tidur dan sering terbangun malam hari', '2026-09-01 10:15:00', 'P01'),
(2, 3, 'RM-0002', 'Siti Aminah', 42, 'Perempuan', 'Mendengkur keras dan sering tersedak saat tidur', '2026-09-05 14:30:00', 'P02'),
(3, 4, 'RM-0003', 'Andi Wijaya', 28, 'Laki-laki', 'Kaki terasa tidak nyaman dan bergerak tak terkendali saat hendak tidur', '2026-09-10 09:20:00', 'P03'),
(4, 5, 'RM-0004', 'Dewi Lestari', 24, 'Perempuan', 'Sering mengantuk hebat di siang hari dan tiba-tiba tertidur', '2026-09-14 11:45:00', 'P04'),
(5, 5, 'RM-0005', 'Rudi Hartono', 31, 'Laki-laki', 'Merasa lelah meski sudah tidur lama', '2026-09-18 16:10:00', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_konsultasi_gejala`
--

CREATE TABLE `tbl_konsultasi_gejala` (
  `id_konsultasi` int(11) NOT NULL,
  `kode_gejala` varchar(5) NOT NULL,
  PRIMARY KEY (`id_konsultasi`,`kode_gejala`),
  KEY `fk_konsultasi_gejala_gejala` (`kode_gejala`),
  CONSTRAINT `fk_konsultasi_gejala_gejala` FOREIGN KEY (`kode_gejala`) REFERENCES `tbl_gejala` (`kode_gejala`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_konsultasi_gejala_konsultasi` FOREIGN KEY (`id_konsultasi`) REFERENCES `tbl_konsultasi` (`id_konsultasi`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_konsultasi_gejala`
--

INSERT INTO `tbl_konsultasi_gejala` (`id_konsultasi`, `kode_gejala`) VALUES
(1, 'G01'),
(1, 'G02'),
(1, 'G14'),
(2, 'G04'),
(2, 'G05'),
(2, 'G06'),
(3, 'G07'),
(3, 'G08'),
(4, 'G09'),
(4, 'G10'),
(4, 'G11'),
(5, 'G03');

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
