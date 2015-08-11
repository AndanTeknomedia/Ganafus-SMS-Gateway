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

-- Dumping structure for table freeinkubator.configs
DROP TABLE IF EXISTS `configs`;
CREATE TABLE IF NOT EXISTS `configs` (
  `config_name` varchar(50) NOT NULL,
  `config_value` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`config_name`),
  UNIQUE KEY `config_name` (`config_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Dumping data for table freeinkubator.configs: 1 rows
/*!40000 ALTER TABLE `configs` DISABLE KEYS */;
INSERT INTO `configs` (`config_name`, `config_value`) VALUES
	('last_processed_valid_sms_id', '24138317649936463'),
	('sms_to_process_per_minute', '50');
/*!40000 ALTER TABLE `configs` ENABLE KEYS */;


-- Dumping structure for table freeinkubator.daemons
DROP TABLE IF EXISTS `daemons`;
CREATE TABLE IF NOT EXISTS `daemons` (
  `Start` text NOT NULL,
  `Info` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Dumping data for table freeinkubator.daemons: 0 rows
/*!40000 ALTER TABLE `daemons` DISABLE KEYS */;
/*!40000 ALTER TABLE `daemons` ENABLE KEYS */;


-- Dumping structure for table freeinkubator.frontend_comments
DROP TABLE IF EXISTS `frontend_comments`;
CREATE TABLE IF NOT EXISTS `frontend_comments` (
  `id` bigint(20) NOT NULL DEFAULT '0',
  `pub_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `nama` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `url` varchar(200) DEFAULT '',
  `content` text,
  `comment_to_table` varchar(20) NOT NULL DEFAULT '',
  `comment_to_id` bigint(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- Dumping data for table freeinkubator.frontend_comments: 3 rows
/*!40000 ALTER TABLE `frontend_comments` DISABLE KEYS */;
INSERT INTO `frontend_comments` (`id`, `pub_date`, `nama`, `email`, `url`, `content`, `comment_to_table`, `comment_to_id`) VALUES
	(24079200277233674, '2015-06-25 22:03:49', 'Haidir', 'm.haidir@yahoo.co.id', '', 'Hanya tes juga bro', 'frontend_posts', 24079200277233670),
	(24079200277233676, '2015-06-25 23:15:03', 'Mamamia', 'mamamia@gmail.com', 'http://banda-naira.cf/', 'Testongngng', 'frontend_posts', 24079200277233670),
	(24079200277233678, '2015-06-25 22:03:35', 'Joko Rivai', 'jokorb@yahoo.co.uk', 'http://kppdi.ga', 'Tes komentar...', 'frontend_posts', 24079200277233670);
/*!40000 ALTER TABLE `frontend_comments` ENABLE KEYS */;


-- Dumping structure for table freeinkubator.frontend_posts
DROP TABLE IF EXISTS `frontend_posts`;
CREATE TABLE IF NOT EXISTS `frontend_posts` (
  `id` bigint(20) NOT NULL DEFAULT '0',
  `pub_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `title` varchar(255) NOT NULL DEFAULT '',
  `content` text,
  `excerpt` text,
  `flag` varchar(20) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Dumping data for table freeinkubator.frontend_posts: 3 rows
/*!40000 ALTER TABLE `frontend_posts` DISABLE KEYS */;
INSERT INTO `frontend_posts` (`id`, `pub_date`, `title`, `content`, `excerpt`, `flag`) VALUES
	(24079200277233670, '2015-06-25 20:24:10', 'Tentang Inkubator Bayi Gratis', 'Anda belum mengatur konfigurasi SMS Gateway.', 'Anda belum mengatur konfigurasi SMS Gateway.', 'about'),
	(24079200277233671, '2015-06-25 20:24:19', 'Cara Melakukan Peminjaman', 'Anda belum mengatur konfigurasi SMS Gateway.', 'Anda belum mengatur konfigurasi SMS Gateway.', 'howto'),
	(24079200277233672, '2015-06-25 20:24:24', 'Syarat &amp; Ketentuan', 'Anda belum mengatur konfigurasi SMS Gateway.', 'Anda belum mengatur konfigurasi SMS Gateway.', 'tos');
/*!40000 ALTER TABLE `frontend_posts` ENABLE KEYS */;


-- Dumping structure for table freeinkubator.gammu
DROP TABLE IF EXISTS `gammu`;
CREATE TABLE IF NOT EXISTS `gammu` (
  `Version` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Dumping data for table freeinkubator.gammu: 1 rows
/*!40000 ALTER TABLE `gammu` DISABLE KEYS */;
INSERT INTO `gammu` (`Version`) VALUES
	(13);
/*!40000 ALTER TABLE `gammu` ENABLE KEYS */;


-- Dumping structure for table freeinkubator.gateway_plugin
DROP TABLE IF EXISTS `gateway_plugin`;
CREATE TABLE IF NOT EXISTS `gateway_plugin` (
  `id` int(20) NOT NULL DEFAULT '0',
  `plugin_name` varchar(50) NOT NULL,
  `plugin_main_file` varchar(200) NOT NULL,
  `plugin_description` text,
  `plugin_active` enum('Y','N') NOT NULL DEFAULT 'N',
  `plugin_author` varchar(200) NOT NULL DEFAULT '',
  `plugin_url` varchar(200) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `plugin_name` (`plugin_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table freeinkubator.gateway_plugin: ~0 rows (approximately)
/*!40000 ALTER TABLE `gateway_plugin` DISABLE KEYS */;
/*!40000 ALTER TABLE `gateway_plugin` ENABLE KEYS */;


-- Dumping structure for table freeinkubator.inbox
DROP TABLE IF EXISTS `inbox`;
CREATE TABLE IF NOT EXISTS `inbox` (
  `UpdatedInDB` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `ReceivingDateTime` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `Text` text NOT NULL,
  `SenderNumber` varchar(20) NOT NULL DEFAULT '',
  `Coding` enum('Default_No_Compression','Unicode_No_Compression','8bit','Default_Compression','Unicode_Compression') NOT NULL DEFAULT 'Default_No_Compression',
  `UDH` text NOT NULL,
  `SMSCNumber` varchar(20) NOT NULL DEFAULT '',
  `Class` int(11) NOT NULL DEFAULT '-1',
  `TextDecoded` text,
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `RecipientID` text NOT NULL,
  `Processed` enum('false','true') NOT NULL DEFAULT 'false',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=4020 DEFAULT CHARSET=utf8;

-- Dumping data for table freeinkubator.inbox: 21 rows
/*!40000 ALTER TABLE `inbox` DISABLE KEYS */;
INSERT INTO `inbox` (`UpdatedInDB`, `ReceivingDateTime`, `Text`, `SenderNumber`, `Coding`, `UDH`, `SMSCNumber`, `Class`, `TextDecoded`, `ID`, `RecipientID`, `Processed`) VALUES
	('2015-08-01 15:19:46', '2015-08-01 14:19:36', '0049006E0066006F002A00730074006F006B', '+6282345798006', 'Default_No_Compression', '', '+6281100000', -1, 'Info*stok', 4013, 'ZTE MF636', 'false'),
	('2015-08-01 15:42:25', '2015-08-01 14:42:15', '0049006E0066006F002A0074006500730074', '+6282345798006', 'Default_No_Compression', '', '+6281100000', -1, 'Info*test', 4014, 'ZTE MF636', 'false'),
	('2015-08-01 15:16:08', '2015-08-01 14:15:59', '0049006E0066006F002A00730074006F006B', '+6282345798006', 'Default_No_Compression', '', '+6281100000', -1, 'Info*stok', 4012, 'ZTE MF636', 'false'),
	('2015-08-01 15:06:34', '2015-08-01 15:06:34', '0069006E0066006F002A00730074006F006B', '+6282345798006', 'Default_No_Compression', '', '+6289644000001', -1, 'info*stok', 4010, 'ZTE MF636', 'false'),
	('2015-08-01 15:13:22', '2015-08-01 14:13:12', '0049006E0066006F002A00730074006F006B', '+6282345798006', 'Default_No_Compression', '', '+6281100000', -1, 'Info*stok', 4011, 'ZTE MF636', 'false'),
	('2015-08-01 14:59:26', '2015-08-01 14:59:26', '0069006E0066006F002A0074006500730074', '+6282345798006', 'Default_No_Compression', '', '+6289644000001', -1, 'info*test', 4009, 'ZTE MF636', 'false'),
	('2015-08-01 14:55:35', '2015-08-01 13:55:25', '0049006E0066006F', '+6282345798006', 'Default_No_Compression', '', '+6281100000', -1, 'Info', 4007, 'ZTE MF636', 'false'),
	('2015-08-01 14:56:55', '2015-08-01 14:56:55', '0069006E0066006F002A0074006500730074', '+6282345798006', 'Default_No_Compression', '', '+6289644000001', -1, 'info*test', 4008, 'ZTE MF636', 'false'),
	('2015-08-01 14:52:15', '2015-08-01 13:52:06', '0049006E0066006F', '+6282345798006', 'Default_No_Compression', '', '+6281100000', -1, 'Info', 4006, 'ZTE MF636', 'false'),
	('2015-08-01 14:47:44', '2015-08-01 13:47:36', '00530074006F006B', '+6282345798006', 'Default_No_Compression', '', '+6281100000', -1, 'Stok', 4005, 'ZTE MF636', 'false'),
	('2015-08-01 14:32:37', '2015-08-01 13:32:28', '0054006500730074', '+6282345798006', 'Default_No_Compression', '', '+6281100000', -1, 'Test', 4003, 'ZTE MF636', 'false'),
	('2015-08-01 14:40:20', '2015-08-01 13:40:11', '00530074006F006B', '+6282345798006', 'Default_No_Compression', '', '+6281100000', -1, 'Stok', 4004, 'ZTE MF636', 'false'),
	('2015-08-01 14:25:40', '2015-08-01 13:25:31', '0054006500730074', '+6282345798006', 'Default_No_Compression', '', '+6281100000', -1, 'Test', 4001, 'ZTE MF636', 'false'),
	('2015-08-01 14:27:06', '2015-08-01 13:26:56', '0054006500730074', '+6282345798006', 'Default_No_Compression', '', '+6281100000', -1, 'Test', 4002, 'ZTE MF636', 'false'),
	('2015-08-01 13:49:36', '2015-08-01 12:49:26', '0054006500730074', '+6282345798006', 'Default_No_Compression', '', '+6281100000', -1, 'Test', 3999, 'ZTE MF636', 'false'),
	('2015-08-01 14:08:31', '2015-08-01 13:08:21', '005400650073002000730061006A0061', '+6282345798006', 'Default_No_Compression', '', '+6281100000', -1, 'Tes saja', 4000, 'ZTE MF636', 'false'),
	('2015-08-01 15:44:23', '2015-08-01 14:44:14', '0054006500730074', '+6282345798006', 'Default_No_Compression', '', '+6281100000', -1, 'Test', 4015, 'ZTE MF636', 'false'),
	('2015-08-02 23:06:01', '2015-08-02 22:05:58', '0054006500730074', '+6282345798006', 'Default_No_Compression', '', '+6281100000', -1, 'Test', 4016, 'ZTE MF636', 'false'),
	('2015-08-05 15:53:58', '2015-08-05 14:53:54', '00500069006E006A0061006D002A0074006500730074', '+6282345798006', 'Default_No_Compression', '', '+6281100000', -1, 'Pinjam*test', 4017, 'ZTE MF636', 'false'),
	('2015-08-05 16:11:31', '2015-08-05 15:11:26', '00500069006E006A0061006D002A0074006500730074', '+6282345798006', 'Default_No_Compression', '', '+6281100000', -1, 'Pinjam*test', 4018, 'ZTE MF636', 'false'),
	('2015-08-05 16:13:28', '2015-08-05 16:13:28', '00700069006E006A0061006D002A006C006100670069', '+6282345798006', 'Default_No_Compression', '', '+6289644000001', -1, 'pinjam*lagi', 4019, 'ZTE MF636', 'false');
/*!40000 ALTER TABLE `inbox` ENABLE KEYS */;


-- Dumping structure for table freeinkubator.inkubator_kembali
DROP TABLE IF EXISTS `inkubator_kembali`;
CREATE TABLE IF NOT EXISTS `inkubator_kembali` (
  `id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `kode_pinjam` varchar(20) NOT NULL DEFAULT '',
  `id_inkubator` bigint(20) NOT NULL DEFAULT '0',
  `tgl_kembali` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `jumlah_kembali` int(2) NOT NULL DEFAULT '1',
  `status_kembali` enum('Ditunda','Diterima','Ditolak') NOT NULL DEFAULT 'Ditunda',
  `tgl_update_status_kembali` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `keterangan_status_kembali` varchar(200) NOT NULL DEFAULT '',
  `berat_kembali` decimal(10,2) NOT NULL DEFAULT '0.00',
  `panjang_kembali` decimal(10,2) NOT NULL DEFAULT '0.00',
  `kondisi_kembali` enum('SEHAT','SAKIT') NOT NULL DEFAULT 'SEHAT',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Dumping data for table freeinkubator.inkubator_kembali: 0 rows
/*!40000 ALTER TABLE `inkubator_kembali` DISABLE KEYS */;
/*!40000 ALTER TABLE `inkubator_kembali` ENABLE KEYS */;


-- Dumping structure for table freeinkubator.inkubator_master
DROP TABLE IF EXISTS `inkubator_master`;
CREATE TABLE IF NOT EXISTS `inkubator_master` (
  `id` bigint(20) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `jumlah` int(2) NOT NULL DEFAULT '0',
  `panjang` int(2) NOT NULL DEFAULT '0' COMMENT 'cm',
  `lebar` int(2) NOT NULL DEFAULT '0' COMMENT 'cm',
  `tinggi` int(2) NOT NULL DEFAULT '0' COMMENT 'cm',
  `berat` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT 'kg',
  `tipe` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Dumping data for table freeinkubator.inkubator_master: 2 rows
/*!40000 ALTER TABLE `inkubator_master` DISABLE KEYS */;
INSERT INTO `inkubator_master` (`id`, `nama`, `jumlah`, `panjang`, `lebar`, `tinggi`, `berat`, `tipe`) VALUES
	(24070524711731200, 'Inkubator Bayi Maspion', 10, 120, 45, 100, 8.50, 'Roda, Pemanas Ganda'),
	(24070524711731201, 'Inkubator Bayi HappyBaby', 46, 140, 52, 110, 10.50, NULL);
/*!40000 ALTER TABLE `inkubator_master` ENABLE KEYS */;


-- Dumping structure for table freeinkubator.inkubator_monitoring
DROP TABLE IF EXISTS `inkubator_monitoring`;
CREATE TABLE IF NOT EXISTS `inkubator_monitoring` (
  `id` int(20) NOT NULL,
  `kode_pinjam` varchar(20) NOT NULL DEFAULT '',
  `tgl_input` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `panjang_bayi` decimal(10,2) NOT NULL DEFAULT '0.00',
  `berat_bayi` decimal(10,2) NOT NULL DEFAULT '0.00',
  `kondisi` enum('SEHAT','SAKIT') NOT NULL DEFAULT 'SEHAT',
  `skor` decimal(10,2) NOT NULL DEFAULT '0.00',
  `keterangan` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Dumping data for table freeinkubator.inkubator_monitoring: 0 rows
/*!40000 ALTER TABLE `inkubator_monitoring` DISABLE KEYS */;
/*!40000 ALTER TABLE `inkubator_monitoring` ENABLE KEYS */;


-- Dumping structure for table freeinkubator.inkubator_pinjam
DROP TABLE IF EXISTS `inkubator_pinjam`;
CREATE TABLE IF NOT EXISTS `inkubator_pinjam` (
  `id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `kode_pinjam` varchar(20) NOT NULL DEFAULT '',
  `id_inkubator` bigint(20) NOT NULL DEFAULT '0',
  `tgl_pinjam` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `nama_bayi` varchar(75) NOT NULL DEFAULT '',
  `kembar` enum('Y','N') NOT NULL DEFAULT 'N',
  `tgl_lahir` date DEFAULT NULL,
  `berat_lahir` decimal(10,2) NOT NULL DEFAULT '0.00',
  `panjang_lahir` decimal(10,2) NOT NULL DEFAULT '0.00',
  `kondisi` enum('SEHAT','SAKIT') NOT NULL DEFAULT 'SEHAT',
  `rumah_sakit` varchar(50) NOT NULL DEFAULT '',
  `nama_dokter` varchar(75) NOT NULL DEFAULT '',
  `tgl_pulang` date DEFAULT NULL,
  `no_kk` varchar(50) NOT NULL DEFAULT '',
  `alamat` text,
  `nama_ibu` varchar(50) NOT NULL DEFAULT '',
  `hp_ibu` varchar(20) NOT NULL DEFAULT '',
  `email_ibu` varchar(50) NOT NULL DEFAULT '',
  `nama_ayah` varchar(50) NOT NULL DEFAULT '',
  `hp_ayah` varchar(20) NOT NULL DEFAULT '',
  `email_ayah` varchar(50) NOT NULL DEFAULT '',
  `jumlah_pinjam` int(2) NOT NULL DEFAULT '1',
  `status_pinjam` enum('Ditunda','Disetujui','Ditolak') NOT NULL DEFAULT 'Ditunda',
  `tgl_update_status_pinjam` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `keterangan_status_pinjam` varchar(200) NOT NULL DEFAULT '',
  `konfirmasi` enum('Y','N') NOT NULL DEFAULT 'N',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- Dumping data for table freeinkubator.inkubator_pinjam: 0 rows
/*!40000 ALTER TABLE `inkubator_pinjam` DISABLE KEYS */;
/*!40000 ALTER TABLE `inkubator_pinjam` ENABLE KEYS */;


-- Dumping structure for table freeinkubator.modem_baudrates
DROP TABLE IF EXISTS `modem_baudrates`;
CREATE TABLE IF NOT EXISTS `modem_baudrates` (
  `baudrate` int(8) NOT NULL,
  `default` enum('Y','N') NOT NULL DEFAULT 'N',
  PRIMARY KEY (`baudrate`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Dumping data for table freeinkubator.modem_baudrates: 12 rows
/*!40000 ALTER TABLE `modem_baudrates` DISABLE KEYS */;
INSERT INTO `modem_baudrates` (`baudrate`, `default`) VALUES
	(300, 'N'),
	(1200, 'N'),
	(2400, 'N'),
	(4800, 'N'),
	(9600, 'N'),
	(19200, 'N'),
	(38400, 'N'),
	(57600, 'N'),
	(115200, 'Y'),
	(230400, 'N'),
	(460800, 'N'),
	(921600, 'N');
/*!40000 ALTER TABLE `modem_baudrates` ENABLE KEYS */;


-- Dumping structure for table freeinkubator.modem_flowcontrols
DROP TABLE IF EXISTS `modem_flowcontrols`;
CREATE TABLE IF NOT EXISTS `modem_flowcontrols` (
  `flwcontrol` varchar(20) NOT NULL,
  `default` enum('Y','N') NOT NULL DEFAULT 'N',
  PRIMARY KEY (`flwcontrol`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Dumping data for table freeinkubator.modem_flowcontrols: 4 rows
/*!40000 ALTER TABLE `modem_flowcontrols` DISABLE KEYS */;
INSERT INTO `modem_flowcontrols` (`flwcontrol`, `default`) VALUES
	('DSR/DTR', 'N'),
	('DTS/CTS', 'N'),
	('None', 'N'),
	('XON/XOFF', 'Y');
/*!40000 ALTER TABLE `modem_flowcontrols` ENABLE KEYS */;


-- Dumping structure for table freeinkubator.modem_gateway
DROP TABLE IF EXISTS `modem_gateway`;
CREATE TABLE IF NOT EXISTS `modem_gateway` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `nama_modem` varchar(50) NOT NULL DEFAULT '',
  `nama_port` varchar(25) NOT NULL,
  `mode` varchar(5) NOT NULL DEFAULT 'at',
  `baudrate` int(8) NOT NULL DEFAULT '115200',
  `parity` varchar(10) NOT NULL DEFAULT 'None',
  `stopbits` varchar(3) NOT NULL DEFAULT '1',
  `flowcontrol` varchar(20) NOT NULL DEFAULT 'XON/XOFF',
  `gammu_path` varchar(200) NOT NULL DEFAULT '',
  `gammu_log_file` varchar(200) NOT NULL DEFAULT '',
  `smsd_log_file` varchar(200) NOT NULL DEFAULT '',
  `gammu_config_file` varchar(200) NOT NULL DEFAULT '',
  `service_name` varchar(50) NOT NULL DEFAULT 'inkubator-gammu-service',
  `smsc` varchar(50) NOT NULL DEFAULT '',
  `use_log` enum('Y','N') NOT NULL DEFAULT 'N',
  `php_path` varchar(200) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=59 DEFAULT CHARSET=utf8;

-- Dumping data for table freeinkubator.modem_gateway: 1 rows
/*!40000 ALTER TABLE `modem_gateway` DISABLE KEYS */;
INSERT INTO `modem_gateway` (`id`, `nama_modem`, `nama_port`, `mode`, `baudrate`, `parity`, `stopbits`, `flowcontrol`, `gammu_path`, `gammu_log_file`, `smsd_log_file`, `gammu_config_file`, `service_name`, `smsc`, `use_log`, `php_path`) VALUES
	(58, 'ZTE MF636', 'com11', 'at', 921600, 'none', '1', 'XON/XOFF', 'C:\\xeroxl\\UniServerZ\\vhosts\\inkubator-local\\Gammu-1.32.0-Windows\\bin', 'C:\\xeroxl\\UniServerZ\\vhosts\\inkubator-local\\Gammu-1.32.0-Windows\\bin\\gammu-log.log', 'C:\\xeroxl\\UniServerZ\\vhosts\\inkubator-local\\Gammu-1.32.0-Windows\\bin\\smsd-log.log', 'C:\\xeroxl\\UniServerZ\\vhosts\\inkubator-local\\Gammu-1.32.0-Windows\\bin\\gammu-config.cfg', 'inkubator-gammu-service', '+6289644000001', 'N', 'C:\\xeroxl\\UniServerZ\\core\\php54');
/*!40000 ALTER TABLE `modem_gateway` ENABLE KEYS */;


-- Dumping structure for table freeinkubator.modem_modes
DROP TABLE IF EXISTS `modem_modes`;
CREATE TABLE IF NOT EXISTS `modem_modes` (
  `mode` varchar(5) NOT NULL,
  `default` enum('Y','N') NOT NULL DEFAULT 'N',
  PRIMARY KEY (`mode`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Dumping data for table freeinkubator.modem_modes: 4 rows
/*!40000 ALTER TABLE `modem_modes` DISABLE KEYS */;
INSERT INTO `modem_modes` (`mode`, `default`) VALUES
	('at', 'Y'),
	('bt', 'N'),
	('irda', 'N'),
	('usb', 'N');
/*!40000 ALTER TABLE `modem_modes` ENABLE KEYS */;


-- Dumping structure for table freeinkubator.modem_parities
DROP TABLE IF EXISTS `modem_parities`;
CREATE TABLE IF NOT EXISTS `modem_parities` (
  `parity` varchar(10) NOT NULL,
  `default` enum('Y','N') NOT NULL DEFAULT 'N',
  PRIMARY KEY (`parity`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Dumping data for table freeinkubator.modem_parities: 5 rows
/*!40000 ALTER TABLE `modem_parities` DISABLE KEYS */;
INSERT INTO `modem_parities` (`parity`, `default`) VALUES
	('Even', 'N'),
	('Mark', 'N'),
	('None', 'Y'),
	('Odd', 'N'),
	('Space', 'N');
/*!40000 ALTER TABLE `modem_parities` ENABLE KEYS */;


-- Dumping structure for table freeinkubator.modem_stopbits
DROP TABLE IF EXISTS `modem_stopbits`;
CREATE TABLE IF NOT EXISTS `modem_stopbits` (
  `stopbits` varchar(3) NOT NULL,
  `default` enum('Y','N') NOT NULL DEFAULT 'N',
  PRIMARY KEY (`stopbits`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Dumping data for table freeinkubator.modem_stopbits: 3 rows
/*!40000 ALTER TABLE `modem_stopbits` DISABLE KEYS */;
INSERT INTO `modem_stopbits` (`stopbits`, `default`) VALUES
	('1', 'Y'),
	('1.5', 'N'),
	('2', 'N');
/*!40000 ALTER TABLE `modem_stopbits` ENABLE KEYS */;


-- Dumping structure for table freeinkubator.outbox
DROP TABLE IF EXISTS `outbox`;
CREATE TABLE IF NOT EXISTS `outbox` (
  `UpdatedInDB` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `InsertIntoDB` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `SendingDateTime` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `SendBefore` time NOT NULL DEFAULT '23:59:59',
  `SendAfter` time NOT NULL DEFAULT '00:00:00',
  `Text` text,
  `DestinationNumber` varchar(20) NOT NULL DEFAULT '',
  `Coding` enum('Default_No_Compression','Unicode_No_Compression','8bit','Default_Compression','Unicode_Compression') NOT NULL DEFAULT 'Default_No_Compression',
  `UDH` text,
  `Class` int(11) DEFAULT '-1',
  `TextDecoded` text,
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `MultiPart` enum('false','true') DEFAULT 'false',
  `RelativeValidity` int(11) DEFAULT '-1',
  `SenderID` varchar(255) DEFAULT NULL,
  `SendingTimeOut` timestamp NULL DEFAULT '0000-00-00 00:00:00',
  `DeliveryReport` enum('default','yes','no') DEFAULT 'default',
  `CreatorID` text NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `outbox_date` (`SendingDateTime`,`SendingTimeOut`),
  KEY `outbox_sender` (`SenderID`)
) ENGINE=MyISAM AUTO_INCREMENT=219 DEFAULT CHARSET=utf8;

-- Dumping data for table freeinkubator.outbox: 0 rows
/*!40000 ALTER TABLE `outbox` DISABLE KEYS */;
/*!40000 ALTER TABLE `outbox` ENABLE KEYS */;


-- Dumping structure for table freeinkubator.outbox_multipart
DROP TABLE IF EXISTS `outbox_multipart`;
CREATE TABLE IF NOT EXISTS `outbox_multipart` (
  `Text` text,
  `Coding` enum('Default_No_Compression','Unicode_No_Compression','8bit','Default_Compression','Unicode_Compression') NOT NULL DEFAULT 'Default_No_Compression',
  `UDH` text,
  `Class` int(11) DEFAULT '-1',
  `TextDecoded` text,
  `ID` int(10) unsigned NOT NULL DEFAULT '0',
  `SequencePosition` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`ID`,`SequencePosition`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Dumping data for table freeinkubator.outbox_multipart: 0 rows
/*!40000 ALTER TABLE `outbox_multipart` DISABLE KEYS */;
/*!40000 ALTER TABLE `outbox_multipart` ENABLE KEYS */;


-- Dumping structure for table freeinkubator.outbox_tmp
DROP TABLE IF EXISTS `outbox_tmp`;
CREATE TABLE IF NOT EXISTS `outbox_tmp` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `DestinationNumber` varchar(20) NOT NULL DEFAULT '',
  `TextDecoded` text,
  `SenderID` varchar(255) DEFAULT NULL,
  `CreatorID` text NOT NULL,
  `Text` text,
  PRIMARY KEY (`id`),
  KEY `outbox_sender` (`SenderID`)
) ENGINE=MyISAM AUTO_INCREMENT=170 DEFAULT CHARSET=utf8;

-- Dumping data for table freeinkubator.outbox_tmp: 31 rows
/*!40000 ALTER TABLE `outbox_tmp` DISABLE KEYS */;
INSERT INTO `outbox_tmp` (`id`, `DestinationNumber`, `TextDecoded`, `SenderID`, `CreatorID`, `Text`) VALUES
	(157, '+6282345798006', 'Format SMS: ', 'ZTE MF636', 'Gammu 1.32.0', '0046006F0072006D0061007400200053004D0053003A0020'),
	(158, '+6282345798006', 'Contoh SMS: ', 'ZTE MF636', 'Gammu 1.32.0', '0043006F006E0074006F006800200053004D0053003A0020'),
	(156, '+6282345798006', 'Contoh SMS: ', 'ZTE MF636', 'Gammu 1.32.0', '0043006F006E0074006F006800200053004D0053003A0020'),
	(154, '+6282345798006', 'Contoh SMS: STOK', 'ZTE MF636', 'Gammu 1.32.0', '0043006F006E0074006F006800200053004D0053003A002000530054004F004B'),
	(155, '+6282345798006', 'Format SMS: ', 'ZTE MF636', 'Gammu 1.32.0', '0046006F0072006D0061007400200053004D0053003A0020'),
	(153, '+6282345798006', 'Format SMS: STOK', 'ZTE MF636', 'Gammu 1.32.0', '0046006F0072006D0061007400200053004D0053003A002000530054004F004B'),
	(151, '+6282345798006', 'Format SMS: TEST', 'ZTE MF636', 'Gammu 1.32.0', '0046006F0072006D0061007400200053004D0053003A00200054004500530054'),
	(152, '+6282345798006', 'Contoh SMS: TEST', 'ZTE MF636', 'Gammu 1.32.0', '0043006F006E0074006F006800200053004D0053003A00200054004500530054'),
	(150, '+6282345798006', 'Kata kunci tersedia: STOK,TEST', 'ZTE MF636', 'Gammu 1.32.0', '004B0061007400610020006B0075006E00630069002000740065007200730065006400690061003A002000530054004F004B002C0054004500530054'),
	(149, '+6282345798006', 'Terimakasih. Ketik INFO*KATAKUNCI untuk bantuan.', 'ZTE MF636', 'Gammu 1.32.0', '0054006500720069006D0061006B0061007300690068002E0020004B006500740069006B00200049004E0046004F002A004B004100540041004B0055004E0043004900200075006E00740075006B002000620061006E007400750061006E002E'),
	(148, '+6282345798006', 'Kata kunci tersedia: STOK,TEST', 'ZTE MF636', 'Gammu 1.32.0', '004B0061007400610020006B0075006E00630069002000740065007200730065006400690061003A002000530054004F004B002C0054004500530054'),
	(146, '+6282345798006', 'Kata kunci tersedia: ', 'ZTE MF636', 'Gammu 1.32.0', '004B0061007400610020006B0075006E00630069002000740065007200730065006400690061003A0020'),
	(147, '+6282345798006', 'Terimakasih. Ketik INFO*KATAKUNCI untuk bantuan.', 'ZTE MF636', 'Gammu 1.32.0', '0054006500720069006D0061006B0061007300690068002E0020004B006500740069006B00200049004E0046004F002A004B004100540041004B0055004E0043004900200075006E00740075006B002000620061006E007400750061006E002E'),
	(145, '+6282345798006', 'Terimakasih. Ketik *KATAKUNCI untuk bantuan.', 'ZTE MF636', 'Gammu 1.32.0', '0054006500720069006D0061006B0061007300690068002E0020004B006500740069006B0020002A004B004100540041004B0055004E0043004900200075006E00740075006B002000620061006E007400750061006E002E'),
	(143, '+6282345798006', 'Inkubator tersedia: 56 buah.', 'ZTE MF636', 'Gammu 1.32.0', '0049006E006B0075006200610074006F0072002000740065007200730065006400690061003A00200035003600200062007500610068002E'),
	(144, '+6282345798006', 'Inkubator tersedia: 56 buah.', 'ZTE MF636', 'Gammu 1.32.0', '0049006E006B0075006200610074006F0072002000740065007200730065006400690061003A00200035003600200062007500610068002E'),
	(139, '+6282345798006', 'tes saja', 'ZTE MF636', 'Gammu 1.32.0', '007400650073002000730061006A0061'),
	(140, '+6282345798006', 'Your SMS has been processed.', 'ZTE MF636', 'Gammu 1.32.0', '0059006F0075007200200053004D005300200068006100730020006200650065006E002000700072006F006300650073007300650064002E'),
	(141, '+6282345798006', 'Test OK.  v. siap.', 'ZTE MF636', 'Gammu 1.32.0', '00540065007300740020004F004B002E002000200076002E00200073006900610070002E'),
	(142, '+6282345798006', 'Test OK. Inkubator Bayi v.1.0.0 siap.', 'ZTE MF636', 'Gammu 1.32.0', '00540065007300740020004F004B002E00200049006E006B0075006200610074006F00720020004200610079006900200076002E0031002E0030002E003000200073006900610070002E'),
	(159, '+6282345798006', 'Format SMS: STOK', 'ZTE MF636', 'Gammu 1.32.0', '0046006F0072006D0061007400200053004D0053003A002000530054004F004B'),
	(160, '+6282345798006', 'Contoh SMS: STOK', 'ZTE MF636', 'Gammu 1.32.0', '0043006F006E0074006F006800200053004D0053003A002000530054004F004B'),
	(161, '+6282345798006', 'Format SMS: TEST', 'ZTE MF636', 'Gammu 1.32.0', '0046006F0072006D0061007400200053004D0053003A00200054004500530054'),
	(162, '+6282345798006', 'Contoh SMS: TEST', 'ZTE MF636', 'Gammu 1.32.0', '0043006F006E0074006F006800200053004D0053003A00200054004500530054'),
	(163, '+6282345798006', 'Test OK. Inkubator Bayi v.1.0.0 siap.', 'ZTE MF636', 'Gammu 1.32.0', '00540065007300740020004F004B002E00200049006E006B0075006200610074006F00720020004200610079006900200076002E0031002E0030002E003000200073006900610070002E'),
	(164, '+6282345798006', 'Test OK. Inkubator Bayi v.1.0.0 siap.', 'ZTE MF636', 'Gammu 1.32.0', '00540065007300740020004F004B002E00200049006E006B0075006200610074006F00720020004200610079006900200076002E0031002E0030002E003000200073006900610070002E'),
	(165, '+6282345798006', '1/2. SMS tidak valid. Jumlah parameter data harus 13.', 'ZTE MF636', 'Gammu 1.32.0', '0031002F0032002E00200053004D005300200074006900640061006B002000760061006C00690064002E0020004A0075006D006C0061006800200070006100720061006D006500740065007200200064006100740061002000680061007200750073002000310033002E'),
	(166, '+6282345798006', '2/2. Contoh SMS: PINJAM*DIAN KHAMSAWARNI*21/09/2015*23/09/2015*28*3,2*SEHAT*RSU Wahidin*Dr. Marhamah, Sp.OG*9288299288*BTN Hamzy E8/A*RINA MAWARNI*ARIFIN ADINEGORO', 'ZTE MF636', 'Gammu 1.32.0', '0032002F0032002E00200043006F006E0074006F006800200053004D0053003A002000500049004E004A0041004D002A004400490041004E0020004B00480041004D00530041005700410052004E0049002A00320031002F00300039002F0032003000310035002A00320033002F00300039002F0032003000310035002A00320038002A0033002C0032002A00530045004800410054002A0052005300550020005700610068006900640069006E002A00440072002E0020004D0061007200680061006D00610068002C002000530070002E004F0047002A0039003200380038003200390039003200380038002A00420054004E002000480061006D007A0079002000450038002F0041002A00520049004E00410020004D0041005700410052004E0049002A00410052004900460049004E0020004100440049004E00450047004F0052004F'),
	(167, '+6282345798006', 'Peminjaman sedang diproses. Kode Pinjam: 323431-333833-33', 'ZTE MF636', 'Gammu 1.32.0', '00500065006D0069006E006A0061006D0061006E00200073006500640061006E006700200064006900700072006F007300650073002E0020004B006F00640065002000500069006E006A0061006D003A0020003300320033003400330031002D003300330033003800330033002D00330033'),
	(168, '+6282345798006', 'Peminjaman sedang diproses. Kode Pinjam: 323431-333833-34', 'ZTE MF636', 'Gammu 1.32.0', '00500065006D0069006E006A0061006D0061006E00200073006500640061006E006700200064006900700072006F007300650073002E0020004B006F00640065002000500069006E006A0061006D003A0020003300320033003400330031002D003300330033003800330033002D00330034'),
	(169, '+6282345798006', 'testing', 'ZTE MF636', 'Gammu 1.32.0', '00740065007300740069006E0067');
/*!40000 ALTER TABLE `outbox_tmp` ENABLE KEYS */;


-- Dumping structure for table freeinkubator.pbk
DROP TABLE IF EXISTS `pbk`;
CREATE TABLE IF NOT EXISTS `pbk` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `GroupID` int(11) NOT NULL DEFAULT '-1',
  `Name` text NOT NULL,
  `Number` text NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Dumping data for table freeinkubator.pbk: 0 rows
/*!40000 ALTER TABLE `pbk` DISABLE KEYS */;
/*!40000 ALTER TABLE `pbk` ENABLE KEYS */;


-- Dumping structure for table freeinkubator.pbk_groups
DROP TABLE IF EXISTS `pbk_groups`;
CREATE TABLE IF NOT EXISTS `pbk_groups` (
  `Name` text NOT NULL,
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Dumping data for table freeinkubator.pbk_groups: 0 rows
/*!40000 ALTER TABLE `pbk_groups` DISABLE KEYS */;
/*!40000 ALTER TABLE `pbk_groups` ENABLE KEYS */;


-- Dumping structure for table freeinkubator.phones
DROP TABLE IF EXISTS `phones`;
CREATE TABLE IF NOT EXISTS `phones` (
  `ID` text NOT NULL,
  `UpdatedInDB` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `InsertIntoDB` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `TimeOut` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `Send` enum('yes','no') NOT NULL DEFAULT 'no',
  `Receive` enum('yes','no') NOT NULL DEFAULT 'no',
  `IMEI` varchar(35) NOT NULL,
  `Client` text NOT NULL,
  `Battery` int(11) NOT NULL DEFAULT '-1',
  `Signal` int(11) NOT NULL DEFAULT '-1',
  `Sent` int(11) NOT NULL DEFAULT '0',
  `Received` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`IMEI`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Dumping data for table freeinkubator.phones: 3 rows
/*!40000 ALTER TABLE `phones` DISABLE KEYS */;
INSERT INTO `phones` (`ID`, `UpdatedInDB`, `InsertIntoDB`, `TimeOut`, `Send`, `Receive`, `IMEI`, `Client`, `Battery`, `Signal`, `Sent`, `Received`) VALUES
	('Samsung GT-S3353', '2015-06-24 22:32:17', '2015-06-24 22:32:17', '2015-06-24 22:32:27', 'yes', 'yes', '354769041007590', 'Gammu 1.32.0, Windows Server 2007 SP1, GCC 4.7, MinGW 3.11', -1, -1, 0, 0),
	('ZTE MF636', '2015-08-05 16:15:50', '2015-08-05 15:53:18', '2015-08-05 16:16:00', 'yes', 'yes', '353924031116068', 'Gammu 1.32.0, Windows Server 2007 SP1, GCC 4.7, MinGW 3.11', 100, 51, 6, 2),
	('ZTE MF636', '2015-07-09 00:58:06', '2015-07-09 00:56:46', '2015-07-09 00:58:16', 'yes', 'yes', '354828044983746', 'Gammu 1.32.0, Windows Server 2007 SP1, GCC 4.7, MinGW 3.11', 64, 30, 0, 0);
/*!40000 ALTER TABLE `phones` ENABLE KEYS */;


-- Dumping structure for table freeinkubator.sentitems
DROP TABLE IF EXISTS `sentitems`;
CREATE TABLE IF NOT EXISTS `sentitems` (
  `UpdatedInDB` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `InsertIntoDB` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `SendingDateTime` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `DeliveryDateTime` timestamp NULL DEFAULT NULL,
  `Text` text NOT NULL,
  `DestinationNumber` varchar(20) NOT NULL DEFAULT '',
  `Coding` enum('Default_No_Compression','Unicode_No_Compression','8bit','Default_Compression','Unicode_Compression') NOT NULL DEFAULT 'Default_No_Compression',
  `UDH` text NOT NULL,
  `SMSCNumber` varchar(20) NOT NULL DEFAULT '',
  `Class` int(11) NOT NULL DEFAULT '-1',
  `TextDecoded` text,
  `ID` int(10) unsigned NOT NULL DEFAULT '0',
  `SenderID` varchar(255) NOT NULL,
  `SequencePosition` int(11) NOT NULL DEFAULT '1',
  `Status` enum('SendingOK','SendingOKNoReport','SendingError','DeliveryOK','DeliveryFailed','DeliveryPending','DeliveryUnknown','Error') NOT NULL DEFAULT 'SendingOK',
  `StatusError` int(11) NOT NULL DEFAULT '-1',
  `TPMR` int(11) NOT NULL DEFAULT '-1',
  `RelativeValidity` int(11) NOT NULL DEFAULT '-1',
  `CreatorID` text NOT NULL,
  PRIMARY KEY (`ID`,`SequencePosition`),
  KEY `sentitems_date` (`DeliveryDateTime`),
  KEY `sentitems_tpmr` (`TPMR`),
  KEY `sentitems_dest` (`DestinationNumber`),
  KEY `sentitems_sender` (`SenderID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Dumping data for table freeinkubator.sentitems: 32 rows
/*!40000 ALTER TABLE `sentitems` DISABLE KEYS */;
INSERT INTO `sentitems` (`UpdatedInDB`, `InsertIntoDB`, `SendingDateTime`, `DeliveryDateTime`, `Text`, `DestinationNumber`, `Coding`, `UDH`, `SMSCNumber`, `Class`, `TextDecoded`, `ID`, `SenderID`, `SequencePosition`, `Status`, `StatusError`, `TPMR`, `RelativeValidity`, `CreatorID`) VALUES
	('2015-07-01 13:48:46', '2015-08-01 13:48:38', '2015-08-01 13:48:46', NULL, '007400650073002000730061006A0061', '+6282345798006', 'Default_No_Compression', '', '+62818445009', -1, 'tes saja', 188, 'ZTE MF636', 1, 'SendingOKNoReport', -1, 2, 255, 'Gammu 1.32.0'),
	('2015-08-01 14:09:23', '2015-08-01 14:09:01', '2015-08-01 14:09:23', NULL, '0059006F0075007200200053004D005300200068006100730020006200650065006E002000700072006F006300650073007300650064002E', '+6282345798006', 'Default_No_Compression', '', '+62818445009', -1, 'Your SMS has been processed.', 189, 'ZTE MF636', 1, 'SendingOKNoReport', -1, 3, 255, 'Gammu 1.32.0'),
	('2015-08-01 14:28:29', '2015-08-01 14:28:01', '2015-08-01 14:28:29', NULL, '00540065007300740020004F004B002E002000200076002E00200073006900610070002E', '+6282345798006', 'Default_No_Compression', '', '+62818445009', -1, 'Test OK.  v. siap.', 190, 'ZTE MF636', 1, 'SendingOKNoReport', -1, 4, 255, 'Gammu 1.32.0'),
	('2015-08-01 14:33:34', '2015-08-01 14:33:01', '2015-08-01 14:33:34', NULL, '00540065007300740020004F004B002E00200049006E006B0075006200610074006F00720020004200610079006900200076002E0031002E0030002E003000200073006900610070002E', '+6282345798006', 'Default_No_Compression', '', '+62818445009', -1, 'Test OK. Inkubator Bayi v.1.0.0 siap.', 191, 'ZTE MF636', 1, 'SendingOKNoReport', -1, 5, 255, 'Gammu 1.32.0'),
	('2015-08-01 14:41:10', '2015-08-01 14:41:01', '2015-08-01 14:41:10', NULL, '0049006E006B0075006200610074006F0072002000740065007200730065006400690061003A00200035003600200062007500610068002E', '+6282345798006', 'Default_No_Compression', '', '+62818445009', -1, 'Inkubator tersedia: 56 buah.', 192, 'ZTE MF636', 1, 'SendingOKNoReport', -1, 6, 255, 'Gammu 1.32.0'),
	('2015-08-01 14:48:22', '2015-08-01 14:48:01', '2015-08-01 14:48:22', NULL, '0049006E006B0075006200610074006F0072002000740065007200730065006400690061003A00200035003600200062007500610068002E', '+6282345798006', 'Default_No_Compression', '', '+62818445009', -1, 'Inkubator tersedia: 56 buah.', 193, 'ZTE MF636', 1, 'SendingOKNoReport', -1, 7, 255, 'Gammu 1.32.0'),
	('2015-08-01 14:53:26', '2015-08-01 14:53:01', '2015-08-01 14:53:26', NULL, '004B0061007400610020006B0075006E00630069002000740065007200730065006400690061003A0020', '+6282345798006', 'Default_No_Compression', '', '+62818445009', -1, 'Kata kunci tersedia: ', 195, 'ZTE MF636', 1, 'SendingOKNoReport', -1, 8, 255, 'Gammu 1.32.0'),
	('2015-08-01 14:53:31', '2015-08-01 14:53:01', '2015-08-01 14:53:31', NULL, '0054006500720069006D0061006B0061007300690068002E0020004B006500740069006B0020002A004B004100540041004B0055004E0043004900200075006E00740075006B002000620061006E007400750061006E002E', '+6282345798006', 'Default_No_Compression', '', '+62818445009', -1, 'Terimakasih. Ketik *KATAKUNCI untuk bantuan.', 194, 'ZTE MF636', 1, 'SendingOKNoReport', -1, 9, 255, 'Gammu 1.32.0'),
	('2015-08-01 14:56:06', '2015-08-01 14:56:01', '2015-08-01 14:56:06', NULL, '004B0061007400610020006B0075006E00630069002000740065007200730065006400690061003A002000530054004F004B002C0054004500530054', '+6282345798006', 'Default_No_Compression', '', '+62818445009', -1, 'Kata kunci tersedia: STOK,TEST', 197, 'ZTE MF636', 1, 'SendingOKNoReport', -1, 10, 255, 'Gammu 1.32.0'),
	('2015-08-01 14:56:11', '2015-08-01 14:56:01', '2015-08-01 14:56:11', NULL, '0054006500720069006D0061006B0061007300690068002E0020004B006500740069006B00200049004E0046004F002A004B004100540041004B0055004E0043004900200075006E00740075006B002000620061006E007400750061006E002E', '+6282345798006', 'Default_No_Compression', '', '+62818445009', -1, 'Terimakasih. Ketik INFO*KATAKUNCI untuk bantuan.', 196, 'ZTE MF636', 1, 'SendingOKNoReport', -1, 11, 255, 'Gammu 1.32.0'),
	('2015-08-01 14:57:16', '2015-08-01 14:57:01', '2015-08-01 14:57:16', NULL, '004B0061007400610020006B0075006E00630069002000740065007200730065006400690061003A002000530054004F004B002C0054004500530054', '+6282345798006', 'Default_No_Compression', '', '+62818445009', -1, 'Kata kunci tersedia: STOK,TEST', 199, 'ZTE MF636', 1, 'SendingOKNoReport', -1, 12, 255, 'Gammu 1.32.0'),
	('2015-08-01 14:57:21', '2015-08-01 14:57:01', '2015-08-01 14:57:21', NULL, '0054006500720069006D0061006B0061007300690068002E0020004B006500740069006B00200049004E0046004F002A004B004100540041004B0055004E0043004900200075006E00740075006B002000620061006E007400750061006E002E', '+6282345798006', 'Default_No_Compression', '', '+62818445009', -1, 'Terimakasih. Ketik INFO*KATAKUNCI untuk bantuan.', 198, 'ZTE MF636', 1, 'SendingOKNoReport', -1, 13, 255, 'Gammu 1.32.0'),
	('2015-08-01 15:00:26', '2015-08-01 15:00:01', '2015-08-01 15:00:26', NULL, '0046006F0072006D0061007400200053004D0053003A00200054004500530054', '+6282345798006', 'Default_No_Compression', '', '+62818445009', -1, 'Format SMS: TEST', 200, 'ZTE MF636', 1, 'SendingOKNoReport', -1, 14, 255, 'Gammu 1.32.0'),
	('2015-08-01 15:00:31', '2015-08-01 15:00:01', '2015-08-01 15:00:31', NULL, '0043006F006E0074006F006800200053004D0053003A00200054004500530054', '+6282345798006', 'Default_No_Compression', '', '+62818445009', -1, 'Contoh SMS: TEST', 201, 'ZTE MF636', 1, 'SendingOKNoReport', -1, 15, 255, 'Gammu 1.32.0'),
	('2015-08-01 15:07:06', '2015-08-01 15:07:01', '2015-08-01 15:07:06', NULL, '0043006F006E0074006F006800200053004D0053003A002000530054004F004B', '+6282345798006', 'Default_No_Compression', '', '+62818445009', -1, 'Contoh SMS: STOK', 203, 'ZTE MF636', 1, 'SendingOKNoReport', -1, 16, 255, 'Gammu 1.32.0'),
	('2015-08-01 15:07:11', '2015-08-01 15:07:01', '2015-08-01 15:07:11', NULL, '0046006F0072006D0061007400200053004D0053003A002000530054004F004B', '+6282345798006', 'Default_No_Compression', '', '+62818445009', -1, 'Format SMS: STOK', 202, 'ZTE MF636', 1, 'SendingOKNoReport', -1, 17, 255, 'Gammu 1.32.0'),
	('2015-08-01 15:14:16', '2015-08-01 15:14:01', '2015-08-01 15:14:16', NULL, '0043006F006E0074006F006800200053004D0053003A0020', '+6282345798006', 'Default_No_Compression', '', '+62818445009', -1, 'Contoh SMS: ', 205, 'ZTE MF636', 1, 'SendingOKNoReport', -1, 18, 255, 'Gammu 1.32.0'),
	('2015-08-01 15:14:20', '2015-08-01 15:14:01', '2015-08-01 15:14:20', NULL, '0046006F0072006D0061007400200053004D0053003A0020', '+6282345798006', 'Default_No_Compression', '', '+62818445009', -1, 'Format SMS: ', 204, 'ZTE MF636', 1, 'SendingOKNoReport', -1, 19, 255, 'Gammu 1.32.0'),
	('2015-08-01 15:17:26', '2015-08-01 15:17:01', '2015-08-01 15:17:26', NULL, '0043006F006E0074006F006800200053004D0053003A0020', '+6282345798006', 'Default_No_Compression', '', '+62818445009', -1, 'Contoh SMS: ', 207, 'ZTE MF636', 1, 'SendingOKNoReport', -1, 20, 255, 'Gammu 1.32.0'),
	('2015-08-01 15:17:31', '2015-08-01 15:17:01', '2015-08-01 15:17:31', NULL, '0046006F0072006D0061007400200053004D0053003A0020', '+6282345798006', 'Default_No_Compression', '', '+62818445009', -1, 'Format SMS: ', 206, 'ZTE MF636', 1, 'SendingOKNoReport', -1, 21, 255, 'Gammu 1.32.0'),
	('2015-08-01 15:20:06', '2015-08-01 15:20:01', '2015-08-01 15:20:06', NULL, '0043006F006E0074006F006800200053004D0053003A002000530054004F004B', '+6282345798006', 'Default_No_Compression', '', '+62818445009', -1, 'Contoh SMS: STOK', 209, 'ZTE MF636', 1, 'SendingOKNoReport', -1, 22, 255, 'Gammu 1.32.0'),
	('2015-08-01 15:20:10', '2015-08-01 15:20:01', '2015-08-01 15:20:10', NULL, '0046006F0072006D0061007400200053004D0053003A002000530054004F004B', '+6282345798006', 'Default_No_Compression', '', '+62818445009', -1, 'Format SMS: STOK', 208, 'ZTE MF636', 1, 'SendingOKNoReport', -1, 23, 255, 'Gammu 1.32.0'),
	('2015-08-01 15:43:15', '2015-08-01 15:43:01', '2015-08-01 15:43:15', NULL, '0043006F006E0074006F006800200053004D0053003A00200054004500530054', '+6282345798006', 'Default_No_Compression', '', '+62818445009', -1, 'Contoh SMS: TEST', 211, 'ZTE MF636', 1, 'SendingOKNoReport', -1, 24, 255, 'Gammu 1.32.0'),
	('2015-08-01 15:43:20', '2015-08-01 15:43:01', '2015-08-01 15:43:20', NULL, '0046006F0072006D0061007400200053004D0053003A00200054004500530054', '+6282345798006', 'Default_No_Compression', '', '+62818445009', -1, 'Format SMS: TEST', 210, 'ZTE MF636', 1, 'SendingOKNoReport', -1, 25, 255, 'Gammu 1.32.0'),
	('2015-08-01 15:45:09', '2015-08-01 15:45:01', '2015-08-01 15:45:09', NULL, '00540065007300740020004F004B002E00200049006E006B0075006200610074006F00720020004200610079006900200076002E0031002E0030002E003000200073006900610070002E', '+6282345798006', 'Default_No_Compression', '', '+62818445009', -1, 'Test OK. Inkubator Bayi v.1.0.0 siap.', 212, 'ZTE MF636', 1, 'SendingOKNoReport', -1, 26, 255, 'Gammu 1.32.0'),
	('2015-08-02 23:07:20', '2015-08-02 23:07:01', '2015-08-02 23:07:20', NULL, '00540065007300740020004F004B002E00200049006E006B0075006200610074006F00720020004200610079006900200076002E0031002E0030002E003000200073006900610070002E', '+6282345798006', 'Default_No_Compression', '', '+62818445009', -1, 'Test OK. Inkubator Bayi v.1.0.0 siap.', 213, 'ZTE MF636', 1, 'SendingOKNoReport', -1, 27, 255, 'Gammu 1.32.0'),
	('2015-08-05 15:54:24', '2015-08-05 15:54:02', '2015-08-05 15:54:24', NULL, '0032002F0032002E00200043006F006E0074006F006800200053004D0053003A002000500049004E004A0041004D002A004400490041004E0020004B00480041004D00530041005700410052004E0049002A00320031002F00300039002F0032003000310035002A00320033002F00300039002F0032003000310035002A00320038002A0033002C0032002A00530045004800410054002A0052005300550020005700610068006900640069006E002A00440072002E0020004D0061007200680061006D00610068002C002000530070002E004F0047002A0039003200380038003200390039003200380038002A00420054004E002000480061006D007A0079002000450038002F0041002A00520049004E00410020004D0041005700410052004E0049002A00410052004900460049004E', '+6282345798006', 'Default_No_Compression', '0500030A0201', '+62818445009', -1, '2/2. Contoh SMS: PINJAM*DIAN KHAMSAWARNI*21/09/2015*23/09/2015*28*3,2*SEHAT*RSU Wahidin*Dr. Marhamah, Sp.OG*9288299288*BTN Hamzy E8/A*RINA MAWARNI*ARIFIN', 215, 'ZTE MF636', 1, 'SendingOKNoReport', -1, 28, 255, 'Gammu 1.32.0'),
	('2015-08-05 15:54:27', '2015-08-05 15:54:02', '2015-08-05 15:54:27', NULL, '0020004100440049004E00450047004F0052004F', '+6282345798006', 'Default_No_Compression', '0500030A0202', '+62818445009', -1, ' ADINEGORO', 215, 'ZTE MF636', 2, 'SendingOKNoReport', -1, 29, 255, 'Gammu 1.32.0'),
	('2015-08-05 15:54:32', '2015-08-05 15:54:02', '2015-08-05 15:54:32', NULL, '0031002F0032002E00200053004D005300200074006900640061006B002000760061006C00690064002E0020004A0075006D006C0061006800200070006100720061006D006500740065007200200064006100740061002000680061007200750073002000310033002E', '+6282345798006', 'Default_No_Compression', '', '+62818445009', -1, '1/2. SMS tidak valid. Jumlah parameter data harus 13.', 214, 'ZTE MF636', 1, 'SendingOKNoReport', -1, 30, 255, 'Gammu 1.32.0'),
	('2015-08-05 16:12:33', '2015-08-05 16:12:01', '2015-08-05 16:12:33', NULL, '00500065006D0069006E006A0061006D0061006E00200073006500640061006E006700200064006900700072006F007300650073002E0020004B006F00640065002000500069006E006A0061006D003A0020003300320033003400330031002D003300330033003800330033002D00330033', '+6282345798006', 'Default_No_Compression', '', '+62818445009', -1, 'Peminjaman sedang diproses. Kode Pinjam: 323431-333833-33', 216, 'ZTE MF636', 1, 'SendingError', -1, -1, 255, 'Gammu 1.32.0'),
	('2015-08-05 16:14:34', '2015-08-05 16:14:01', '2015-08-05 16:14:34', NULL, '00500065006D0069006E006A0061006D0061006E00200073006500640061006E006700200064006900700072006F007300650073002E0020004B006F00640065002000500069006E006A0061006D003A0020003300320033003400330031002D003300330033003800330033002D00330034', '+6282345798006', 'Default_No_Compression', '', '+62818445009', -1, 'Peminjaman sedang diproses. Kode Pinjam: 323431-333833-34', 217, 'ZTE MF636', 1, 'SendingError', -1, -1, 255, 'Gammu 1.32.0'),
	('2015-08-02 23:07:20', '2015-08-05 16:15:01', '2015-08-05 16:15:35', NULL, '00740065007300740069006E0067', '+6282345798006', 'Default_No_Compression', '', '+62818445009', -1, 'testing', 218, 'ZTE MF636', 1, 'SendingError', -1, -1, 255, 'Gammu 1.32.0');
/*!40000 ALTER TABLE `sentitems` ENABLE KEYS */;


-- Dumping structure for table freeinkubator.sms_keywords
DROP TABLE IF EXISTS `sms_keywords`;
CREATE TABLE IF NOT EXISTS `sms_keywords` (
  `id` bigint(20) NOT NULL DEFAULT '0',
  `keyword` varchar(50) NOT NULL,
  `function_name` varchar(100) NOT NULL,
  `file_name` varchar(200) NOT NULL DEFAULT '',
  `description` text,
  `format_sms` text,
  `contoh_sms` text,
  `active` enum('Y','N') DEFAULT 'N',
  `kategori` varchar(100) NOT NULL DEFAULT 'Inbox',
  `candidates` text COMMENT 'JSON',
  PRIMARY KEY (`id`),
  UNIQUE KEY `keyword` (`keyword`),
  UNIQUE KEY `function_name` (`function_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Dumping data for table freeinkubator.sms_keywords: 5 rows
/*!40000 ALTER TABLE `sms_keywords` DISABLE KEYS */;
INSERT INTO `sms_keywords` (`id`, `keyword`, `function_name`, `file_name`, `description`, `format_sms`, `contoh_sms`, `active`, `kategori`, `candidates`) VALUES
	(24129571183919111, 'UNKNOWN', 'my_hook_unknown_function', 'C:/xeroxl/UniServerZ/vhosts/inkubator-local/sms-daemon-hooks/hook-template-ad921d60486366258809553a3db49a4a.php', 'SMS Tidak Valid', '', '', 'Y', 'Inbox', NULL),
	(24132448392577026, 'TEST', 'my_hook_test_function', 'C:/xeroxl/UniServerZ/vhosts/inkubator-local/sms-daemon-hooks/hook-template-098f6bcd4621d373cade4e832627b4f6.php', 'System Test', 'TEST', 'TEST', 'Y', 'Inkubator bayi', NULL),
	(24132448392577030, 'STOK', 'my_hook_stok_function', 'C:/xeroxl/UniServerZ/vhosts/inkubator-local/sms-daemon-hooks/hook-template-ce7129b555fd0208c1751956ecab4952.php', 'Cek stok inkubator yang tersedia.', 'STOK', 'STOK', 'Y', 'Inkubator bayi', NULL),
	(24132448392577033, 'INFO', 'my_hook_info_function', 'C:/xeroxl/UniServerZ/vhosts/inkubator-local/sms-daemon-hooks/hook-template-caf9b6b99962bf5c2264824231d7a40c.php', 'Informasi peminjaman inkubator', 'INFO*KATAKUNCI', 'INFO*STOK', 'Y', 'Inkubator bayi', NULL),
	(24134418943705089, 'PINJAM', 'my_hook_pinjam_function', 'C:/xeroxl/UniServerZ/vhosts/inkubator-local/sms-daemon-hooks/hook-template-d76630a1a369ff64a5464e247b9b0098.php', 'SMS Peminjaman Inkubator', 'PINJAM*NAMA_BAYI*TGL_LAHIR*TGL_PULANG_RS*CM_PJGLAHIR*KG_BERATLAHIR*<SEHAT/SAKIT>*NAMA_RS*NM_DOKTER/BIDAN*NO_KK*ALAMAT*NAMA_IBU*NAMA_AYAH', 'PINJAM*DIAN KHAMSAWARNI*21/09/2015*23/09/2015*28*3,2*SEHAT*RSU Wahidin*Dr. Marhamah, Sp.OG*9288299288*BTN Hamzy E8/A*RINA MAWARNI*ARIFIN ADINEGORO', 'Y', 'Inkubator bayi', NULL);
/*!40000 ALTER TABLE `sms_keywords` ENABLE KEYS */;


-- Dumping structure for table freeinkubator.sms_valid
DROP TABLE IF EXISTS `sms_valid`;
CREATE TABLE IF NOT EXISTS `sms_valid` (
  `id` bigint(20) NOT NULL DEFAULT '0',
  `udh` varchar(20) NOT NULL DEFAULT '',
  `waktu_terima` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `pengirim` varchar(50) NOT NULL DEFAULT '',
  `sms` text NOT NULL,
  `jenis` varchar(30) NOT NULL DEFAULT 'UNKNOWN',
  `param_count` int(1) NOT NULL DEFAULT '0',
  `diproses` enum('UDH','Diproses','Ditunda','Dibalas') NOT NULL DEFAULT 'Ditunda',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Dumping data for table freeinkubator.sms_valid: 21 rows
/*!40000 ALTER TABLE `sms_valid` DISABLE KEYS */;
INSERT INTO `sms_valid` (`id`, `udh`, `waktu_terima`, `pengirim`, `sms`, `jenis`, `param_count`, `diproses`) VALUES
	(24132448392577029, '', '2015-08-01 14:32:37', '+6282345798006', 'Test', 'TEST', 0, 'Dibalas'),
	(24132448392577031, '', '2015-08-01 14:40:20', '+6282345798006', 'Stok', 'STOK', 0, 'Dibalas'),
	(24132448392577032, '', '2015-08-01 14:47:44', '+6282345798006', 'Stok', 'STOK', 0, 'Dibalas'),
	(24132448392577034, '', '2015-08-01 14:52:15', '+6282345798006', 'Info', 'INFO', 0, 'Dibalas'),
	(24132448392577035, '', '2015-08-01 14:55:35', '+6282345798006', 'Info', 'INFO', 0, 'Dibalas'),
	(24132448392577036, '', '2015-08-01 14:56:55', '+6282345798006', 'info*test', 'INFO', 0, 'Dibalas'),
	(24132448392577037, '', '2015-08-01 14:59:26', '+6282345798006', 'info*test', 'INFO', 0, 'Dibalas'),
	(24132448392577038, '', '2015-08-01 15:06:34', '+6282345798006', 'info*stok', 'INFO', 0, 'Dibalas'),
	(24132448392577039, '', '2015-08-01 15:13:22', '+6282345798006', 'Info*stok', 'INFO', 0, 'Dibalas'),
	(24132448392577040, '', '2015-08-01 15:16:08', '+6282345798006', 'Info*stok', 'INFO', 0, 'Dibalas'),
	(24132448392577041, '', '2015-08-01 15:19:46', '+6282345798006', 'Info*stok', 'INFO', 0, 'Dibalas'),
	(24132448392577042, '', '2015-08-01 15:42:25', '+6282345798006', 'Info*test', 'INFO', 0, 'Dibalas'),
	(24132448392577028, '', '2015-08-01 14:27:06', '+6282345798006', 'Test', 'TEST', 0, 'Dibalas'),
	(24132448392577027, '', '2015-08-01 14:25:40', '+6282345798006', 'Test', 'TEST', 0, 'Diproses'),
	(24132448392577025, '', '2015-08-01 14:08:31', '+6282345798006', 'Tes saja', 'UNKNOWN', 0, 'Dibalas'),
	(24132448392577024, '', '2015-08-01 13:49:36', '+6282345798006', 'Test', 'UNKNOWN', 0, 'Dibalas'),
	(24132448392577043, '', '2015-08-01 15:44:23', '+6282345798006', 'Test', 'TEST', 0, 'Dibalas'),
	(24134418943705088, '', '2015-08-02 23:06:01', '+6282345798006', 'Test', 'TEST', 0, 'Dibalas'),
	(24138317649936384, '', '2015-08-05 15:53:58', '+6282345798006', 'Pinjam*test', 'PINJAM', 0, 'Dibalas'),
	(24138317649936452, '', '2015-08-05 16:11:31', '+6282345798006', 'Pinjam*test', 'PINJAM', 0, 'Dibalas'),
	(24138317649936463, '', '2015-08-05 16:13:28', '+6282345798006', 'pinjam*lagi', 'PINJAM', 0, 'Dibalas');
/*!40000 ALTER TABLE `sms_valid` ENABLE KEYS */;


-- Dumping structure for table freeinkubator.users
DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_name` varchar(30) NOT NULL,
  `group_id` char(3) NOT NULL DEFAULT '',
  `user_password` varchar(32) NOT NULL COMMENT 'md5()',
  `email` varchar(30) NOT NULL,
  `first_name` varchar(50) DEFAULT '',
  `last_name` varchar(50) DEFAULT '',
  `image_path` varchar(50) NOT NULL DEFAULT 'mr-x.png' COMMENT 'filename.ext only',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `user_name` (`user_name`)
) ENGINE=MyISAM AUTO_INCREMENT=23985714693668894 DEFAULT CHARSET=utf8;

-- Dumping data for table freeinkubator.users: 3 rows
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` (`id`, `user_name`, `group_id`, `user_password`, `email`, `first_name`, `last_name`, `image_path`) VALUES
	(23985714693668883, 'jokorivai', 'dev', '1c4d3efaf239b9c3bb348bbbe8d98ab5', 'buyutjokorivai@gmail.com', 'Joko', 'Rivai', 'jokorb.png'),
	(23985714693668892, 'yusufaja', 'adm', '1299e2547c8a060867e83781b12e43bd', 'myusuf85@gmail.com', 'Muhammad Yusuf', 'Basri', 'mr-x.png'),
	(23985714693668893, 'admin', 'adm', '21232f297a57a5a743894a0e4a801fc3', 'admin@foo.com', 'Administrator', '', 'mr-x.png');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;


-- Dumping structure for table freeinkubator.user_groups
DROP TABLE IF EXISTS `user_groups`;
CREATE TABLE IF NOT EXISTS `user_groups` (
  `id` char(3) NOT NULL,
  `parent_group_id` char(3) NOT NULL DEFAULT '',
  `group_name` varchar(30) NOT NULL,
  `group_description` varchar(255) DEFAULT '',
  `group_active` enum('Y','N') NOT NULL DEFAULT 'Y',
  PRIMARY KEY (`id`),
  UNIQUE KEY `group_name` (`group_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Dumping data for table freeinkubator.user_groups: 4 rows
/*!40000 ALTER TABLE `user_groups` DISABLE KEYS */;
INSERT INTO `user_groups` (`id`, `parent_group_id`, `group_name`, `group_description`, `group_active`) VALUES
	('adm', '', 'Administrator', 'System Administrator', 'Y'),
	('dev', '', 'Developer', 'System Devs', 'Y'),
	('gst', 'opr', 'Guest', 'User tanpa login', 'Y'),
	('opr', 'adm', 'Operator', 'Operator', 'Y');
/*!40000 ALTER TABLE `user_groups` ENABLE KEYS */;


-- Dumping structure for view freeinkubator.vw_inkubator_perkembangan
DROP VIEW IF EXISTS `vw_inkubator_perkembangan`;
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `vw_inkubator_perkembangan` (
	`id` BIGINT(20) UNSIGNED NOT NULL,
	`nama` VARCHAR(50) NOT NULL COLLATE 'utf8_general_ci',
	`kode_pinjam` VARCHAR(20) NOT NULL COLLATE 'utf8_general_ci',
	`id_inkubator` BIGINT(20) NOT NULL,
	`tgl_pinjam` TIMESTAMP NOT NULL,
	`nama_bayi` VARCHAR(75) NOT NULL COLLATE 'utf8_general_ci',
	`kembar` ENUM('Y','N') NOT NULL COLLATE 'utf8_general_ci',
	`tgl_lahir` DATE NULL,
	`berat_lahir` DECIMAL(10,2) NOT NULL,
	`panjang_lahir` DECIMAL(10,2) NOT NULL,
	`kondisi` ENUM('SEHAT','SAKIT') NOT NULL COLLATE 'utf8_general_ci',
	`rumah_sakit` VARCHAR(50) NOT NULL COLLATE 'utf8_general_ci',
	`nama_dokter` VARCHAR(75) NOT NULL COLLATE 'utf8_general_ci',
	`tgl_pulang` DATE NULL,
	`no_kk` VARCHAR(50) NOT NULL COLLATE 'utf8_general_ci',
	`alamat` TEXT NULL COLLATE 'utf8_general_ci',
	`nama_ibu` VARCHAR(50) NOT NULL COLLATE 'utf8_general_ci',
	`hp_ibu` VARCHAR(20) NOT NULL COLLATE 'utf8_general_ci',
	`email_ibu` VARCHAR(50) NOT NULL COLLATE 'utf8_general_ci',
	`nama_ayah` VARCHAR(50) NOT NULL COLLATE 'utf8_general_ci',
	`hp_ayah` VARCHAR(20) NOT NULL COLLATE 'utf8_general_ci',
	`email_ayah` VARCHAR(50) NOT NULL COLLATE 'utf8_general_ci',
	`jumlah_pinjam` INT(2) NOT NULL,
	`status_pinjam` ENUM('Ditunda','Disetujui','Ditolak') NOT NULL COLLATE 'utf8_general_ci',
	`tgl_update_status_pinjam` TIMESTAMP NOT NULL,
	`keterangan_status_pinjam` VARCHAR(200) NOT NULL COLLATE 'utf8_general_ci',
	`konfirmasi` ENUM('Y','N') NOT NULL COLLATE 'utf8_general_ci',
	`jumlah_data_monitor` BIGINT(21) NOT NULL,
	`jumlah_skor_monitor` DECIMAL(32,2) NULL,
	`perkembangan` VARCHAR(7) NULL COLLATE 'utf8_general_ci'
) ENGINE=MyISAM;


-- Dumping structure for view freeinkubator.vw_inkubator_pinjam
DROP VIEW IF EXISTS `vw_inkubator_pinjam`;
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `vw_inkubator_pinjam` (
	`id` BIGINT(20) UNSIGNED NOT NULL,
	`nama` VARCHAR(50) NOT NULL COLLATE 'utf8_general_ci',
	`kode_pinjam` VARCHAR(20) NOT NULL COLLATE 'utf8_general_ci',
	`id_inkubator` BIGINT(20) NOT NULL,
	`tgl_pinjam` TIMESTAMP NOT NULL,
	`nama_bayi` VARCHAR(75) NOT NULL COLLATE 'utf8_general_ci',
	`kembar` ENUM('Y','N') NOT NULL COLLATE 'utf8_general_ci',
	`tgl_lahir` DATE NULL,
	`berat_lahir` DECIMAL(10,2) NOT NULL,
	`panjang_lahir` DECIMAL(10,2) NOT NULL,
	`kondisi` ENUM('SEHAT','SAKIT') NOT NULL COLLATE 'utf8_general_ci',
	`rumah_sakit` VARCHAR(50) NOT NULL COLLATE 'utf8_general_ci',
	`nama_dokter` VARCHAR(75) NOT NULL COLLATE 'utf8_general_ci',
	`tgl_pulang` DATE NULL,
	`no_kk` VARCHAR(50) NOT NULL COLLATE 'utf8_general_ci',
	`alamat` TEXT NULL COLLATE 'utf8_general_ci',
	`nama_ibu` VARCHAR(50) NOT NULL COLLATE 'utf8_general_ci',
	`hp_ibu` VARCHAR(20) NOT NULL COLLATE 'utf8_general_ci',
	`email_ibu` VARCHAR(50) NOT NULL COLLATE 'utf8_general_ci',
	`nama_ayah` VARCHAR(50) NOT NULL COLLATE 'utf8_general_ci',
	`hp_ayah` VARCHAR(20) NOT NULL COLLATE 'utf8_general_ci',
	`email_ayah` VARCHAR(50) NOT NULL COLLATE 'utf8_general_ci',
	`jumlah_pinjam` INT(2) NOT NULL,
	`status_pinjam` ENUM('Ditunda','Disetujui','Ditolak') NOT NULL COLLATE 'utf8_general_ci',
	`tgl_update_status_pinjam` TIMESTAMP NOT NULL,
	`keterangan_status_pinjam` VARCHAR(200) NOT NULL COLLATE 'utf8_general_ci',
	`konfirmasi` ENUM('Y','N') NOT NULL COLLATE 'utf8_general_ci',
	`tgl_kembali` TIMESTAMP NULL,
	`berat_kembali` DECIMAL(10,2) NULL,
	`panjang_kembali` DECIMAL(10,2) NULL,
	`kondisi_kembali` ENUM('SEHAT','SAKIT') NULL COLLATE 'utf8_general_ci',
	`jumlah_kembali` INT(2) NULL,
	`status_kembali` ENUM('Ditunda','Diterima','Ditolak') NULL COLLATE 'utf8_general_ci',
	`tgl_update_status_kembali` TIMESTAMP NULL,
	`keterangan_status_kembali` VARCHAR(200) NULL COLLATE 'utf8_general_ci'
) ENGINE=MyISAM;


-- Dumping structure for view freeinkubator.vw_inkubator_tersedia
DROP VIEW IF EXISTS `vw_inkubator_tersedia`;
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `vw_inkubator_tersedia` (
	`id` BIGINT(20) NOT NULL,
	`nama` VARCHAR(50) NOT NULL COLLATE 'utf8_general_ci',
	`panjang` INT(2) NOT NULL COMMENT 'cm',
	`lebar` INT(2) NOT NULL COMMENT 'cm',
	`tinggi` INT(2) NOT NULL COMMENT 'cm',
	`berat` DECIMAL(10,2) NOT NULL COMMENT 'kg',
	`tipe` TEXT NULL COLLATE 'utf8_general_ci',
	`stok_inkubator` DECIMAL(34,0) NULL
) ENGINE=MyISAM;


-- Dumping structure for view freeinkubator.vw_user_login
DROP VIEW IF EXISTS `vw_user_login`;
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `vw_user_login` (
	`id` BIGINT(20) NOT NULL,
	`user_name` VARCHAR(30) NOT NULL COLLATE 'utf8_general_ci',
	`group_id` CHAR(3) NOT NULL COLLATE 'utf8_general_ci',
	`user_password` VARCHAR(32) NOT NULL COMMENT 'md5()' COLLATE 'utf8_general_ci',
	`email` VARCHAR(30) NOT NULL COLLATE 'utf8_general_ci',
	`first_name` VARCHAR(50) NULL COLLATE 'utf8_general_ci',
	`last_name` VARCHAR(50) NULL COLLATE 'utf8_general_ci',
	`image_path` VARCHAR(50) NOT NULL COMMENT 'filename.ext only' COLLATE 'utf8_general_ci',
	`group_name` VARCHAR(30) NOT NULL COLLATE 'utf8_general_ci',
	`group_description` VARCHAR(255) NULL COLLATE 'utf8_general_ci',
	`group_active` ENUM('Y','N') NOT NULL COLLATE 'utf8_general_ci'
) ENGINE=MyISAM;


-- Dumping structure for trigger freeinkubator.frontend_comments_before_insert
DROP TRIGGER IF EXISTS `frontend_comments_before_insert`;
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION';
DELIMITER //
CREATE TRIGGER `frontend_comments_before_insert` BEFORE INSERT ON `frontend_comments` FOR EACH ROW BEGIN
	set NEW.id = UUID_SHORT();	
END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;


-- Dumping structure for trigger freeinkubator.frontend_posts_before_insert
DROP TRIGGER IF EXISTS `frontend_posts_before_insert`;
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION';
DELIMITER //
CREATE TRIGGER `frontend_posts_before_insert` BEFORE INSERT ON `frontend_posts` FOR EACH ROW BEGIN
	set NEW.id = UUID_SHORT();	
	if coalesce(NEW.excerpt,'') = ''  then
		set NEW.excerpt = left(NEW.content, 100);
	end if;
END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;


-- Dumping structure for trigger freeinkubator.gateway_plugin_before_insert
DROP TRIGGER IF EXISTS `gateway_plugin_before_insert`;
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION';
DELIMITER //
CREATE TRIGGER `gateway_plugin_before_insert` BEFORE INSERT ON `gateway_plugin` FOR EACH ROW BEGIN
	set NEW.id = UUID_SHORT();
END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;


-- Dumping structure for trigger freeinkubator.inbox_after_insert
DROP TRIGGER IF EXISTS `inbox_after_insert`;
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION';
DELIMITER //
CREATE TRIGGER `inbox_after_insert` AFTER INSERT ON `inbox` FOR EACH ROW BEGIN
	declare smstext text;
	declare smsudh varchar(20);
	declare udh_prefix, udh_urut, udh_urut_old, udh_count varchar(20);
	declare pos integer;
	declare sms_keyword varchar(20);
	
	declare delimiter char(1) default '*';	
	/*
	declare KW_TEST varchar(4) default 'TEST';
	declare KW_INFO varchar(4) default 'INFO';
	declare KW_PINJAM varchar(6) default 'PINJAM';
	declare KW_KEMBALI varchar(7) default 'KEMBALI';
	declare KW_MONITOR varchar(7) default 'MONITOR';
	*/
	declare KW_UNKNOWN varchar(7) default 'UNKNOWN';	
	
	declare lengkap 	bool default false;
	declare param		bigint(20) default 0;
	declare uid			bigint(20) default 0;
	
	-- set delimiter = (select config_value from configs where config_name = 'KEYWORD_DELIMITER');
	-- Jika nomor pengirim kurang dari 8 karakter, jangan proses!
	if length(NEW.SenderNumber)>8 then	
		set smsudh = coalesce(NEW.UDH,'');
		if smsudh = '' then
			-- single SMS found, langsung cari posisi keyword:
			set smstext = trim(leading ' ' from NEW.TextDecoded);	
			set pos = POSITION(delimiter IN smstext);
			if pos <= 0 then -- not found
				if POSITION(' ' IN smstext)>0 then
					-- set keyword = upper(SUBSTR(sms,1,POSITION(' ' IN sms)-1));
					set sms_keyword = KW_UNKNOWN;
				else
					set sms_keyword = upper(smstext);
				end if;
			else
				-- keyword found, validate it:
				set sms_keyword = upper(substr(smstext, 1, pos-1));
			end if;
			
			if sms_keyword in (
				/*
				KW_TEST,
				KW_INFO,		
				KW_PINJAM,
				KW_KEMBALI,
				KW_MONITOR
				*/
				select upper(keyword) from sms_keywords
			) then
				-- update sms_valid set jenis = keyword where id = uid;
				insert into sms_valid values (uid, '', CURRENT_TIMESTAMP(), NEW.SenderNumber, NEW.TextDecoded, sms_keyword, 0, 'Ditunda');
			else
				-- invalid keyword
				-- update sms_valid set jenis = KW_UNKNOWN where id = uid;
				insert into sms_valid values (uid, '', CURRENT_TIMESTAMP(), NEW.SenderNumber, NEW.TextDecoded, KW_UNKNOWN, 0, 'Ditunda');				
			end if;
		else
			-- multipart SMS found, process it by UDH:
			-- contoh udh: 050003D20301, udh_prefix = 050003D203, udh_urut = 01; all in 1 byte hex.
			set uid = UUID_SHORT();
			set udh_prefix = left(smsudh, 10);
			set udh_count = left(right(smsudh,4),2);
			set udh_urut = right(smsudh,2);
			-- set param = (select param_count from sms_valid where left(udh,10) = udh_prefix);
			
			set smstext = coalesce((select sms from sms_valid where left(udh,10) = udh_prefix),'');
			set udh_urut_old = coalesce((select right(udh,2) from sms_valid where left(udh,10) = udh_prefix),'00');
			
			-- sisip part di posisi udh_urut			
			if  udh_urut < udh_urut_old then
				set smstext = concat(new.TextDecoded, smstext);
			else
				set smstext = concat(smstext, new.TextDecoded);
			end if;
			if udh_urut_old = '00' then
				insert into sms_valid values (uid, NEW.UDH, CURRENT_TIMESTAMP(), NEW.SenderNumber, smstext, KW_UNKNOWN, 0, 'Ditunda');
			else
				update sms_valid set sms = smstext, udh = NEW.UDH where left(udh,10) = udh_prefix;
			end if;
			-- jika sudah komplit, cek keyword dan update sms :
			if udh_urut = udh_count then 
				set pos = POSITION(delimiter IN smstext);
				if pos <= 0 then -- not found
					if POSITION(' ' IN smstext)>0 then
						-- set keyword = upper(SUBSTR(sms,1,POSITION(' ' IN sms)-1));
						set sms_keyword = KW_UNKNOWN;
					else
						set sms_keyword = upper(smstext);
					end if;
				else
					-- keyword found, validate it:
					set sms_keyword = upper(substr(smstext, 1, pos-1));
				end if;			
				
				if sms_keyword in (
					/*
					KW_TEST,
					KW_INFO,		
					KW_PINJAM,
					KW_KEMBALI,
					KW_MONITOR
					*/
					select upper(keyword) from sms_keywords
				) then
					update sms_valid set jenis = sms_keyword where left(udh,10) = udh_prefix;
				else
					update sms_valid set jenis = KW_UNKNOWN where left(udh,10) = udh_prefix;
				end if;			
				
			end if;
		end if;
	end if;
END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;


-- Dumping structure for trigger freeinkubator.inbox_timestamp
DROP TRIGGER IF EXISTS `inbox_timestamp`;
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION';
DELIMITER //
CREATE TRIGGER `inbox_timestamp` BEFORE INSERT ON `inbox` FOR EACH ROW BEGIN
    IF NEW.ReceivingDateTime = '0000-00-00 00:00:00' THEN
        SET NEW.ReceivingDateTime = CURRENT_TIMESTAMP();
    END IF;
END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;


-- Dumping structure for trigger freeinkubator.inkubator_kembali_after_insert
DROP TRIGGER IF EXISTS `inkubator_kembali_after_insert`;
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION';
DELIMITER //
CREATE TRIGGER `inkubator_kembali_after_insert` AFTER INSERT ON `inkubator_kembali` FOR EACH ROW BEGIN
	declare bayi varchar(100);
	set bayi = (select p.nama_bayi from inkubator_pinjam p where p.kode_pinjam = NEW.kode_pinjam);
	insert into inkubator_monitoring 
		(kode_pinjam, tgl_input, panjang_bayi, berat_bayi, kondisi, keterangan)
	values
		(NEW.kode_pinjam, curent_timestamp, NEW.panjang_kembali, NEW.berat_kembali, NEW.kondisi_kembali, concat('Status akhir ', bayi));
END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;


-- Dumping structure for trigger freeinkubator.inkubator_monitoring_after_insert
DROP TRIGGER IF EXISTS `inkubator_monitoring_after_insert`;
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION';
DELIMITER //
CREATE TRIGGER `inkubator_monitoring_after_insert` AFTER INSERT ON `inkubator_monitoring` FOR EACH ROW BEGIN
        	set @xid = UUID_SHORT();        	
        END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;


-- Dumping structure for trigger freeinkubator.inkubator_monitoring_before_insert
DROP TRIGGER IF EXISTS `inkubator_monitoring_before_insert`;
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION';
DELIMITER //
CREATE TRIGGER `inkubator_monitoring_before_insert` BEFORE INSERT ON `inkubator_monitoring` FOR EACH ROW BEGIN
	set NEW.id = UUID_SHORT();
	case when NEW.kondisi = 'SEHAT' then
		set NEW.skor = 1;
	else
		set NEW.skor = 0;
	end case;
END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;


-- Dumping structure for trigger freeinkubator.inkubator_monitoring_before_update
DROP TRIGGER IF EXISTS `inkubator_monitoring_before_update`;
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION';
DELIMITER //
CREATE TRIGGER `inkubator_monitoring_before_update` BEFORE UPDATE ON `inkubator_monitoring` FOR EACH ROW BEGIN
	set NEW.id = UUID_SHORT();
	case when NEW.kondisi = 'SEHAT' then
		set NEW.skor = 1;
	else
		set NEW.skor = 0;
	end case;
END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;


-- Dumping structure for trigger freeinkubator.inkubator_pinjam_after_delete
DROP TRIGGER IF EXISTS `inkubator_pinjam_after_delete`;
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION';
DELIMITER //
CREATE TRIGGER `inkubator_pinjam_after_delete` AFTER DELETE ON `inkubator_pinjam` FOR EACH ROW BEGIN
	delete from inkubator_monitoring  where kode_pinjam = OLD.kode_pinjam;
	delete from inkubator_kembali  where kode_pinjam = OLD.kode_pinjam;
END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;


-- Dumping structure for trigger freeinkubator.outbox_timestamp
DROP TRIGGER IF EXISTS `outbox_timestamp`;
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION';
DELIMITER //
CREATE TRIGGER `outbox_timestamp` BEFORE INSERT ON `outbox` FOR EACH ROW BEGIN
    IF NEW.InsertIntoDB = '0000-00-00 00:00:00' THEN
        SET NEW.InsertIntoDB = CURRENT_TIMESTAMP();
    END IF;
    IF NEW.SendingDateTime = '0000-00-00 00:00:00' THEN
        SET NEW.SendingDateTime = CURRENT_TIMESTAMP();
    END IF;
    IF NEW.SendingTimeOut = '0000-00-00 00:00:00' THEN
        SET NEW.SendingTimeOut = CURRENT_TIMESTAMP();
    END IF;
END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;


-- Dumping structure for trigger freeinkubator.outbox_tmp_after_insert
DROP TRIGGER IF EXISTS `outbox_tmp_after_insert`;
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION';
DELIMITER //
CREATE TRIGGER `outbox_tmp_after_insert` AFTER INSERT ON `outbox_tmp` FOR EACH ROW BEGIN
	declare pjg, jumlah, urut, i, ihex, pjghex, lid integer;
	declare part, strdata varchar(255);
	declare hexdata text;	
	declare flag varchar(2);
	
	set pjg = length(NEW.TextDecoded);
	if pjg > 160 then
		set jumlah = (pjg div 153);
		set flag = lpad(hex(RAND()*256), 2, '0');
		if (pjg - (153*jumlah))<>0 then
			set jumlah = jumlah + 1;
		end if;
		set urut   	= 0;
		set i			= 0;
		set lid = 0;
		while i < jumlah do
			set i = i + 1;
			set urut = urut + 1;
			set part = substr(NEW.TextDecoded, (i-1)*153+1, 153);
			set hexdata = substr(NEW.Text, (i-1)*153*4+1, 153*4);
			if urut =  1 then
				insert into outbox(            
					Text,
					DestinationNumber,
					UDH,
					TextDecoded,
					MultiPart,
					RelativeValidity,
					SenderID,
					CreatorID
					) values (    
					hexdata,
					NEW.DestinationNumber,
					concat('050003', flag, lpad(hex(jumlah),2,'0'),lpad(hex(urut),2,'0')),
					part,
					'true',
					255,
					NEW.SenderID,
					NEW.CreatorID
					);
				set lid = LAST_INSERT_ID();
			else
				insert into outbox_multipart(            
					Text,
					UDH,
					TextDecoded,
					ID,
					SequencePosition
					) values (    
					hexdata,
					concat('050003', flag, lpad(hex(jumlah),2,'0'),lpad(hex(urut),2,'0')),
					part,
					lid,
					urut
					);
			end if;
		end while;
	else
		insert into outbox(            
		    Text,
		    DestinationNumber,
			 UDH,
		    TextDecoded,
		    MultiPart,
		    RelativeValidity,
		    SenderID,
		    CreatorID
		    ) values (    
		    NEW.Text,
		    NEW.DestinationNumber,
		    '',
		    NEW.TextDecoded,
		    'false',
		    255,
		    NEW.SenderID,
		    NEW.CreatorID
		    );	
	end if;
END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;


-- Dumping structure for trigger freeinkubator.outbox_tmp_before_insert
DROP TRIGGER IF EXISTS `outbox_tmp_before_insert`;
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION';
DELIMITER //
CREATE TRIGGER `outbox_tmp_before_insert` BEFORE INSERT ON `outbox_tmp` FOR EACH ROW BEGIN
	declare i integer;
	declare hexdata text;
	set i = 1;
	set hexdata = '';
	while i <= length(NEW.TextDecoded) do				
		set hexdata = concat(hexdata, lpad( hex( ord( substr(NEW.TextDecoded, i,1) )) ,4,'0' ) );
		set i = i + 1;
	end while;
	set NEW.Text = hexdata;
END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;


-- Dumping structure for trigger freeinkubator.phones_timestamp
DROP TRIGGER IF EXISTS `phones_timestamp`;
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION';
DELIMITER //
CREATE TRIGGER `phones_timestamp` BEFORE INSERT ON `phones` FOR EACH ROW BEGIN
    IF NEW.InsertIntoDB = '0000-00-00 00:00:00' THEN
        SET NEW.InsertIntoDB = CURRENT_TIMESTAMP();
    END IF;
    IF NEW.TimeOut = '0000-00-00 00:00:00' THEN
        SET NEW.TimeOut = CURRENT_TIMESTAMP();
    END IF;
END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;


-- Dumping structure for trigger freeinkubator.pinjam_after_insert
DROP TRIGGER IF EXISTS `pinjam_after_insert`;
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION';
DELIMITER //
CREATE TRIGGER `pinjam_after_insert` AFTER INSERT ON `inkubator_pinjam` FOR EACH ROW BEGIN
	insert into inkubator_monitoring 
		(kode_pinjam, tgl_input, panjang_bayi, berat_bayi, kondisi, keterangan)
	values
		(NEW.kode_pinjam, curent_timestamp, NEW.panjang_lahir, NEW.berat_lahir, NEW.kondisi, concat('Status awal ', NEW.nama_bayi));
END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;


-- Dumping structure for trigger freeinkubator.pinjam_before_insert
DROP TRIGGER IF EXISTS `pinjam_before_insert`;
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION';
DELIMITER //
CREATE TRIGGER `pinjam_before_insert` BEFORE INSERT ON `inkubator_pinjam` FOR EACH ROW BEGIN
	set NEW.id = UUID_SHORT();
	set NEW.kode_pinjam = hex(NEW.id);
END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;


-- Dumping structure for trigger freeinkubator.sentitems_timestamp
DROP TRIGGER IF EXISTS `sentitems_timestamp`;
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION';
DELIMITER //
CREATE TRIGGER `sentitems_timestamp` BEFORE INSERT ON `sentitems` FOR EACH ROW BEGIN
    IF NEW.InsertIntoDB = '0000-00-00 00:00:00' THEN
        SET NEW.InsertIntoDB = CURRENT_TIMESTAMP();
    END IF;
    IF NEW.SendingDateTime = '0000-00-00 00:00:00' THEN
        SET NEW.SendingDateTime = CURRENT_TIMESTAMP();
    END IF;
END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;


-- Dumping structure for trigger freeinkubator.sms_keywords_after_insert
DROP TRIGGER IF EXISTS `sms_keywords_after_insert`;
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION';
DELIMITER //
CREATE TRIGGER `sms_keywords_after_insert` AFTER INSERT ON `sms_keywords` FOR EACH ROW BEGIN
	-- 
END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;


-- Dumping structure for trigger freeinkubator.sms_keywords_before_insert
DROP TRIGGER IF EXISTS `sms_keywords_before_insert`;
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION';
DELIMITER //
CREATE TRIGGER `sms_keywords_before_insert` BEFORE INSERT ON `sms_keywords` FOR EACH ROW BEGIN
	set NEW.id = UUID_SHORT();
	set NEW.keyword = UPPER(NEW.keyword);
END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;


-- Dumping structure for trigger freeinkubator.sms_valid_before_insert
DROP TRIGGER IF EXISTS `sms_valid_before_insert`;
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION';
DELIMITER //
CREATE TRIGGER `sms_valid_before_insert` BEFORE INSERT ON `sms_valid` FOR EACH ROW BEGIN
	set NEW.id = UUID_SHORT();
END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;


-- Dumping structure for view freeinkubator.vw_inkubator_perkembangan
DROP VIEW IF EXISTS `vw_inkubator_perkembangan`;
-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `vw_inkubator_perkembangan`;
CREATE ALGORITHM=UNDEFINED DEFINER=`inkubator`@`localhost` VIEW `vw_inkubator_perkembangan` AS select 
        	p.id,
        	i.nama,
        	p.kode_pinjam,
        	p.id_inkubator,
        	p.tgl_pinjam,
        	p.nama_bayi,
        	p.kembar,
        	p.tgl_lahir,
        	p.berat_lahir,
        	p.panjang_lahir,
        	p.kondisi,
        	p.rumah_sakit,
        	p.nama_dokter,
        	p.tgl_pulang,
        	p.no_kk,
        	p.alamat,
        	p.nama_ibu,
        	p.hp_ibu,
        	p.email_ibu,
        	p.nama_ayah,
        	p.hp_ayah,
        	p.email_ayah,
        	p.jumlah_pinjam,
        	p.status_pinjam,
        	p.tgl_update_status_pinjam,
        	p.keterangan_status_pinjam,
        	p.konfirmasi,
        	coalesce(count(m.id),0) as jumlah_data_monitor,
        	coalesce(sum(coalesce(m.skor,0)),0) as jumlah_skor_monitor,
        	(case 
        		when (coalesce(sum(coalesce(m.skor,0)),0) - coalesce(count(m.id),0) ) >= 0 then 
        			'Positif'
        		else 
        			'Negatif'	
        	end) as perkembangan
        from inkubator_pinjam p
        inner join inkubator_master i on i.id = p.id_inkubator
        left join inkubator_monitoring m on m.kode_pinjam = p.kode_pinjam 
        group by p.kode_pinjam ; ;


-- Dumping structure for view freeinkubator.vw_inkubator_pinjam
DROP VIEW IF EXISTS `vw_inkubator_pinjam`;
-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `vw_inkubator_pinjam`;
CREATE ALGORITHM=UNDEFINED DEFINER=`inkubator`@`localhost` VIEW `vw_inkubator_pinjam` AS select 
        	p.id,
        	i.nama,
        	p.kode_pinjam,
        	p.id_inkubator,
        	p.tgl_pinjam,
        	p.nama_bayi,
        	p.kembar,
        	p.tgl_lahir,
        	p.berat_lahir,
        	p.panjang_lahir,
        	p.kondisi,
        	p.rumah_sakit,
        	p.nama_dokter,
        	p.tgl_pulang,
        	p.no_kk,
        	p.alamat,
        	p.nama_ibu,
        	p.hp_ibu,
        	p.email_ibu,
        	p.nama_ayah,
        	p.hp_ayah,
        	p.email_ayah,
        	p.jumlah_pinjam,
        	p.status_pinjam,
        	p.tgl_update_status_pinjam,
        	p.keterangan_status_pinjam,
        	p.konfirmasi,
        	k.tgl_kembali,
        	k.berat_kembali,
        	k.panjang_kembali,
        	k.kondisi_kembali,
        	k.jumlah_kembali,
        	k.status_kembali,
        	k.tgl_update_status_kembali,
        	k.keterangan_status_kembali
        	
        from inkubator_pinjam p
        inner join inkubator_master i on i.id = p.id_inkubator
        left join inkubator_kembali k on k.kode_pinjam = p.kode_pinjam ; ;


-- Dumping structure for view freeinkubator.vw_inkubator_tersedia
DROP VIEW IF EXISTS `vw_inkubator_tersedia`;
-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `vw_inkubator_tersedia`;
CREATE ALGORITHM=UNDEFINED DEFINER=`inkubator`@`localhost` VIEW `vw_inkubator_tersedia` AS select 
        	i.id,
        	i.nama,
        	i.panjang,
        	i.lebar,
        	i.tinggi,
        	i.berat,
        	i.tipe,
        	(i.jumlah - sum(coalesce(p.jumlah_pinjam,0)) + sum(coalesce(k.jumlah_kembali,0)) ) as stok_inkubator
        from inkubator_master i
        left join inkubator_pinjam p on p.id_inkubator = i.id and p.status_pinjam = 'Disetujui'
        left join inkubator_kembali k on k.id_inkubator = i.id and k.status_kembali = 'Diterima'
        group by i.id ;


-- Dumping structure for view freeinkubator.vw_user_login
DROP VIEW IF EXISTS `vw_user_login`;
-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `vw_user_login`;
CREATE ALGORITHM=UNDEFINED DEFINER=`inkubator`@`localhost` VIEW `vw_user_login` AS SELECT 
				
	u.*,
	g.group_name,
	g.group_description,
	g.group_active
from users u
inner join user_groups g on g.id = u.group_id ;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
