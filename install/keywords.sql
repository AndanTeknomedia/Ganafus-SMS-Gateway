-- --------------------------------------------------------
-- Host:                         localhost
-- Server version:               10.0.15-MariaDB - mariadb.org binary distribution
-- Server OS:                    Win32
-- HeidiSQL Version:             9.1.0.4867
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
-- Dumping data for table freeinkubator.sms_keywords: 5 rows
/*!40000 ALTER TABLE `sms_keywords` DISABLE KEYS */;
INSERT INTO `sms_keywords` (`id`, `keyword`, `function_name`, `file_name`, `description`, `format_sms`, `contoh_sms`, `active`, `kategori`, `candidates`) VALUES
	(24129571183919111, 'UNKNOWN', 'my_hook_unknown_function', 'C:/xeroxl/UniServerZ/vhosts/inkubator-local/sms-daemon-hooks/hook-template-ad921d60486366258809553a3db49a4a.php', 'SMS Tidak Valid', '', '', 'Y', 'Inbox', NULL),
	(24132448392577026, 'TEST', 'my_hook_test_function', 'C:/xeroxl/UniServerZ/vhosts/inkubator-local/sms-daemon-hooks/hook-template-098f6bcd4621d373cade4e832627b4f6.php', 'System Test', 'TEST', 'TEST', 'Y', 'Inkubator bayi', NULL),
	(24132448392577030, 'STOK', 'my_hook_stok_function', 'C:/xeroxl/UniServerZ/vhosts/inkubator-local/sms-daemon-hooks/hook-template-ce7129b555fd0208c1751956ecab4952.php', 'Cek stok inkubator yang tersedia.', 'STOK', 'STOK', 'Y', 'Inkubator bayi', NULL),
	(24132448392577033, 'INFO', 'my_hook_info_function', 'C:/xeroxl/UniServerZ/vhosts/inkubator-local/sms-daemon-hooks/hook-template-caf9b6b99962bf5c2264824231d7a40c.php', 'Informasi peminjaman inkubator', 'INFO*KATAKUNCI', 'INFO*STOK', 'Y', 'Inkubator bayi', NULL),
	(24134418943705089, 'PINJAM', 'my_hook_pinjam_function', 'C:/xeroxl/UniServerZ/vhosts/inkubator-local/sms-daemon-hooks/hook-template-d76630a1a369ff64a5464e247b9b0098.php', 'SMS Peminjaman Inkubator', 'PINJAM*NAMA_BAYI*TGL_LAHIR*TGL_PULANG_RS*CM_PJGLAHIR*KG_BERATLAHIR*<SEHAT/SAKIT>*NAMA_RS*NM_DOKTER/BIDAN*NO_KK*ALAMAT*NAMA_IBU*NAMA_AYAH', 'PINJAM*DIAN KHAMSAWARNI*21/09/2015*23/09/2015*28*3,2*SEHAT*RSU Wahidin*Dr. Marhamah, Sp.OG*9288299288*BTN Hamzy E8/A*RINA MAWARNI*ARIFIN ADINEGORO', 'Y', 'Inkubator bayi', NULL);
/*!40000 ALTER TABLE `sms_keywords` ENABLE KEYS */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
