/*
SQLyog Ultimate v8.3 
MySQL - 5.7.25 : Database - te_tools_v2_db
*********************************************************************
*/


/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`te_tools_v2_db` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `te_tools_v2_db`;

/*Table structure for table `com_email` */

DROP TABLE IF EXISTS `com_email`;

CREATE TABLE `com_email` (
  `email_id` varchar(2) NOT NULL,
  `email_name` varchar(100) DEFAULT NULL,
  `email_address` varchar(50) DEFAULT NULL,
  `smtp_host` varchar(50) DEFAULT NULL,
  `smtp_port` varchar(5) DEFAULT NULL,
  `smtp_username` varchar(50) DEFAULT NULL,
  `smtp_password` varchar(50) DEFAULT NULL,
  `use_smtp` enum('1','0') DEFAULT '1',
  `use_authorization` enum('1','0') DEFAULT '1',
  `mdb` varchar(10) DEFAULT NULL,
  `mdb_name` varchar(50) DEFAULT NULL,
  `mdd` datetime DEFAULT NULL,
  PRIMARY KEY (`email_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `com_email` */

LOCK TABLES `com_email` WRITE;

insert  into `com_email`(`email_id`,`email_name`,`email_address`,`smtp_host`,`smtp_port`,`smtp_username`,`smtp_password`,`use_smtp`,`use_authorization`,`mdb`,`mdb_name`,`mdd`) values ('01','[No Reply] PT. Time Excelindo - Management Tools','','smtpgw.dephub.go.id','25','pelayanan.ditkapel@dephub.go.id','kapal2018','1','1',NULL,NULL,NULL);

UNLOCK TABLES;

/*Table structure for table `com_group` */

DROP TABLE IF EXISTS `com_group`;

CREATE TABLE `com_group` (
  `group_id` varchar(2) NOT NULL,
  `group_name` varchar(50) DEFAULT NULL,
  `group_desc` varchar(100) DEFAULT NULL,
  `mdb` varchar(10) DEFAULT NULL,
  `mdb_name` varchar(50) DEFAULT NULL,
  `mdd` datetime DEFAULT NULL,
  PRIMARY KEY (`group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `com_group` */

LOCK TABLES `com_group` WRITE;

insert  into `com_group`(`group_id`,`group_name`,`group_desc`,`mdb`,`mdb_name`,`mdd`) values ('01','Developer','Kelompok Pengguna Khusus Developer Aplikasi','1605200001',NULL,'2016-06-01 15:11:41'),('02','User','Kelompok Pengguna Umum',NULL,NULL,NULL);

UNLOCK TABLES;

/*Table structure for table `com_menu` */

DROP TABLE IF EXISTS `com_menu`;

CREATE TABLE `com_menu` (
  `nav_id` varchar(10) NOT NULL,
  `portal_id` varchar(2) DEFAULT NULL,
  `parent_id` varchar(10) DEFAULT NULL,
  `nav_title` varchar(50) DEFAULT NULL,
  `nav_desc` varchar(100) DEFAULT NULL,
  `nav_url` varchar(100) DEFAULT NULL,
  `nav_no` int(11) unsigned DEFAULT NULL,
  `active_st` enum('1','0') DEFAULT '1',
  `display_st` enum('1','0') DEFAULT '1',
  `nav_icon` varchar(50) DEFAULT NULL,
  `mdb` varchar(10) DEFAULT NULL,
  `mdb_name` varchar(50) DEFAULT NULL,
  `mdd` datetime DEFAULT NULL,
  PRIMARY KEY (`nav_id`),
  KEY `FK_com_menu_p` (`portal_id`),
  CONSTRAINT `com_menu_ibfk_1` FOREIGN KEY (`portal_id`) REFERENCES `com_portal` (`portal_id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `com_menu` */

LOCK TABLES `com_menu` WRITE;

insert  into `com_menu`(`nav_id`,`portal_id`,`parent_id`,`nav_title`,`nav_desc`,`nav_url`,`nav_no`,`active_st`,`display_st`,`nav_icon`,`mdb`,`mdb_name`,`mdd`) values ('1000000001','10','0','Dashboard','-','home/welcome/dashboard',1,'1','1','fa fa-home','1605200001',NULL,'2018-02-03 15:19:34'),('1000000002','10','0','Settings','-','#',11,'1','1','fa fa-gear','1',NULL,'2018-03-25 10:34:25'),('1000000003','10','1000000002','System','-','#',99,'1','1','','1605200001',NULL,'2018-03-07 11:21:32'),('1000000004','10','1000000003','Application Portal','-','settings/sistem/portal',991,'1','1','','1605200001',NULL,'2018-03-07 11:21:56'),('1000000005','10','1000000003','Groups','-','settings/sistem/groups',992,'1','1','','1605200001',NULL,'2018-03-07 11:22:04'),('1000000006','10','1000000003','Roles','-','settings/sistem/roles',993,'1','1','','1605200001',NULL,'2018-03-07 11:22:11'),('1000000007','10','1000000003','Navigation','-','settings/sistem/menu',994,'1','1','','1605200001',NULL,'2018-03-07 11:22:17'),('1000000008','10','1000000003','Email Settings','-','settings/sistem/email',996,'1','1','','1605200001',NULL,'2018-03-07 11:23:19'),('1000000009','10','1000000003','Permissions','-','settings/sistem/permissions',995,'1','1','','1605200001',NULL,'2018-03-07 11:22:23'),('1000000010','10','1000000003','CRUD Template','-','settings/sistem/template',997,'1','1','','1605200001',NULL,'2018-03-07 11:23:23'),('1000000011','10','1000000001','My Profile','-','home/welcome/profile',11,'1','1','','1',NULL,'2018-03-24 12:40:49'),('1000000012','10','0','Task Manager','-','#',3,'1','1','fa fa-edit','1605200001',NULL,'2018-03-07 14:20:14'),('1000000013','10','1000000012','Kepegawaian','-','#',31,'1','1','','1605200001',NULL,'2018-03-07 14:20:20'),('1000000014','10','0','My Task','-','#',2,'1','1','fa fa-tasks','1',NULL,'2018-03-19 06:35:26'),('1000000015','10','1000000014','Jobs & Project','-','#',21,'1','1','','1605200001',NULL,'2018-03-07 14:26:59'),('1000000016','10','1000000015','Catatan Harian','-','home/task/daily_notes',211,'1','1','','1605200001',NULL,'2018-03-07 14:28:44'),('1000000017','10','1000000015','Laporan Perjalanan Dinas','-','home/task/lpj',212,'1','1','','1605200001',NULL,'2018-03-07 14:28:49'),('1000000018','10','1000000014','Kepegawaian','-','#',22,'1','1','','1605200001',NULL,'2018-03-07 14:29:16'),('1000000019','10','1000000018','Pengajuan Ijin','-','home/task/permit',221,'1','1','','1605200001',NULL,'2018-03-07 14:29:42'),('1000000020','10','1000000018','Pengajuan Cuti','-','home/task/leave',222,'1','1','','1605200001',NULL,'2018-03-07 14:30:00'),('1000000021','10','1000000013','Pengajuan Ijin','-','kepegawaian/ijin/pengajuan',311,'1','1','','1605200001',NULL,'2018-03-07 14:32:25'),('1000000022','10','1000000013','Pengajuan Cuti','-','kepegawaian/cuti/pengajuan',312,'1','1','','1605200001',NULL,'2018-03-07 14:32:54'),('1000000023','10','1000000013','Pengajuan Lembur','-','kepegawaian/lembur/pengajuan',313,'1','1','','1605200001',NULL,'2018-03-07 14:33:15'),('1000000024','10','1000000013','Pengajuan Jaldin','-','kepegawaian/jaldin/pengajuan',314,'1','1','','1',NULL,'2018-03-19 06:34:54'),('1000000025','10','0','Task Approval','-','#',5,'1','1','fa fa-check','1605200001',NULL,'2018-03-07 15:29:15'),('1000000026','10','1000000025','Ijin Pegawai','-','#',51,'1','1','','1605200001',NULL,'2018-03-07 16:48:35'),('1000000027','10','0','Report & Analytics','-','#',10,'1','1','fa  fa-bar-chart','1',NULL,'2018-03-25 10:34:28'),('1000000028','10','1000000027','Laporan Kepegawaian','-','#',91,'1','1','','1605200001',NULL,'2018-03-07 15:10:00'),('1000000029','10','1000000028','Data Pegawai','-','laporan/kepegawaian/pegawai',911,'1','1','','1605200001',NULL,'2018-03-07 15:10:44'),('1000000030','10','1000000028','Presensi Karyawan','-','laporan/kepegawaian/presensi',912,'1','1','','1605200001',NULL,'2018-03-07 15:11:07'),('1000000031','10','1000000028','Rekapitulasi Lembur','-','laporan/kepegawaian/lembur',913,'1','1','','1605200001',NULL,'2018-03-07 15:11:31'),('1000000032','10','1000000028','Rekapitulasi Ijin','-','laporan/kepegawaian/ijin',914,'1','1','','1605200001',NULL,'2018-03-07 15:11:53'),('1000000033','10','1000000028','Rekapitulasi Cuti','-','laporan/kepegawaian/leave',915,'1','1','','1605200001',NULL,'2018-03-07 15:12:09'),('1000000034','10','0','E-HR','-','#',6,'1','1','fa fa-users','1',NULL,'2018-03-24 11:48:02'),('1000000035','10','1000000034','Data Kepegawaian','-','#',61,'1','1','','1605200001',NULL,'2018-03-07 15:22:16'),('1000000036','10','1000000034','Data Pendukung','-','#',63,'1','1','','1',NULL,'2018-03-24 11:48:48'),('1000000037','10','1000000036','Struktur Organisasi','-','kepegawaian/master/struktur_organisasi',621,'1','1','','1',NULL,'2018-03-24 10:44:59'),('1000000038','10','1000000035','Data Karyawan','-','kepegawaian/master/pegawai',610,'1','1','','1',NULL,'2018-03-24 10:44:29'),('1000000039','10','1000000036','Jabatan Struktural','-','kepegawaian/master/jabatan_struktural',622,'1','1','','1',NULL,'2018-03-24 10:45:50'),('1000000040','10','1000000035','Data Pejabat / Pimpinan','-','kepegawaian/master/pegawai_jabatan',611,'1','1','','1',NULL,'2018-03-24 10:49:37'),('1000000041','10','1000000036','Mesin Presensi','-','kepegawaian/master/presensi',629,'1','1','','1',NULL,'2018-03-24 10:59:59'),('1000000042','10','0','Task Monitoring','-','#',4,'1','1','fa fa-search','1605200001',NULL,'2018-03-07 15:31:21'),('1000000043','10','1000000042','Kepegawaian','-','#',41,'1','1','','1605200001',NULL,'2018-03-07 15:31:57'),('1000000044','10','1000000043','Pengajuan Ijin','-','kepegawaian/ijin/monitoring',411,'1','1','','1605200001',NULL,'2018-03-07 15:32:50'),('1000000045','10','1000000043','Pengajuan Cuti','-','kepegawaian/cuti/monitoring',412,'1','1','','1605200001',NULL,'2018-03-07 15:33:08'),('1000000046','10','1000000043','Pengajuan Lembur','-','kepegawaian/lembur/monitoring',413,'1','1','','1605200001',NULL,'2018-03-07 15:33:35'),('1000000047','10','1000000043','Pengajuan Jaldin','-','kepegawaian/jaldin/monitoring',414,'1','1','','1',NULL,'2018-03-19 06:35:04'),('1000000048','10','1000000025','Cuti Pegawai','-','#',52,'1','1','','1605200001',NULL,'2018-03-07 16:49:48'),('1000000049','10','1000000025','Lembur','-','#',53,'1','1','','1605200001',NULL,'2018-03-07 16:50:28'),('1000000050','10','1000000026','Persetujuan Pimpinan','-','kepegawaian/ijin/pimpinan',511,'1','1','','1605200001',NULL,'2018-03-07 16:49:33'),('1000000051','10','1000000048','Persetujuan Pimpinan','-','kepegawaian/cuti/pimpinan',521,'1','1','','1605200001',NULL,'2018-03-07 16:50:09'),('1000000052','10','1000000049','Persetujuan Pimpinan','-','kepegawaian/lembur/pimpinan',531,'1','1','','1605200001',NULL,'2018-03-07 16:50:50'),('1000000053','10','1000000059','Persetujuan Pimpinan','-','kepegawaian/jaldin/pimpinan',541,'1','1','','1605200001',NULL,'2018-03-07 16:51:50'),('1000000054','10','1000000026','Persetujuan HRD','-','kepegawaian/ijin/hrd',512,'1','1','','1605200001',NULL,'2018-03-07 16:52:33'),('1000000055','10','1000000048','Persetujuan HRD','-','kepegawaian/cuti/hrd',522,'1','1','','1605200001',NULL,'2018-03-07 16:52:46'),('1000000056','10','1000000049','Persetujuan HRD','-','kepegawaian/lembur/hrd',532,'1','1','','1605200001',NULL,'2018-03-07 16:53:01'),('1000000057','10','1000000059','Advance Perjalanan Dinas','-','kepegawaian/jaldin/advance',542,'1','1','','1605200001',NULL,'2018-03-07 16:53:18'),('1000000058','10','1000000059','Verifikasi LPJ - HRD','-','kepegawaian/jaldin/verifikasi_hrd',545,'1','1','','1605200001',NULL,'2018-03-07 17:02:02'),('1000000059','10','1000000025','Perjalanan Dinas','-','#',54,'1','1','','1605200001',NULL,'2018-03-07 16:51:33'),('1000000060','10','1000000059','Verifikasi Advance','-','kepegawaian/jaldin/advance_verifikasi',543,'1','1','','1605200001',NULL,'2018-03-07 16:58:41'),('1000000061','10','1000000059','Pengambilan Advance','-','kepegawaian/jaldin/advance_pengambilan',544,'1','1','','1605200001',NULL,'2018-03-07 16:58:27'),('1000000062','10','1000000059','Verifikasi LPJ - Keuangan','-','kepegawaian/jaldin/verifikasi_keuangan',546,'1','1','','1605200001',NULL,'2018-03-07 17:02:08'),('1000000063','10','1000000059','Sisa / Kekurangan Jaldin','-','kepegawaian/jaldin/sisa_kekurangan',547,'1','1','','1605200001',NULL,'2018-03-07 17:03:05'),('1000000064','10','0','Finances','-','#',7,'1','1','fa  fa-file-text','1',NULL,'2018-03-24 14:49:09'),('1000000065','10','0','E-Projects','-','#',9,'1','1','fa fa-clipboard','1',NULL,'2018-03-24 14:49:01'),('1000000066','10','1000000065','Bendera / Perusahaan','-','project/master/perusahaan',81,'1','1','','1605200001',NULL,'2018-03-07 17:13:18'),('1000000067','10','1000000065','Client Data','-','project/master/client',82,'1','1','','1605200001',NULL,'2018-03-07 17:13:37'),('1000000068','10','1000000065','Project','-','#',83,'1','1','','1',NULL,'2018-03-24 14:38:15'),('1000000070','10','1000000068','Project Documentation','-','project/tools/documentation',832,'1','1','','1',NULL,'2018-03-24 14:38:59'),('1000000071','10','1000000068','Project Notes & Timeline','-','project/tools/notes',833,'1','1','','1',NULL,'2018-03-24 14:40:07'),('1000000072','10','1000000068','Project Budgeting','-','project/tools/rab',835,'1','1','','1',NULL,'2018-03-24 14:41:47'),('1000000074','10','1000000065','Penagihan & Pembayaran','-','#',87,'1','1','','1',NULL,'2018-03-24 14:42:50'),('1000000075','10','1000000074','Daftar Penagihan','-','project/tools/penagihan',871,'1','1','','1605200001',NULL,'2018-03-07 17:21:55'),('1000000076','10','1000000074','Daftar Pembayaran','-','project/tools/pembayaran',872,'1','1','','1605200001',NULL,'2018-03-07 17:22:11'),('1000000077','10','1000000036','Jabatan Fungsional','-','kepegawaian/master/jabatan_fungsional',623,'1','1','','1',NULL,'2018-03-24 10:49:27'),('1000000078','10','1000000036','Jadwal / Hari Kerja','-','kepegawaian/master/jadwal_kerja',624,'1','1','','1',NULL,'2018-03-24 11:00:26'),('1000000079','10','1000000036','Hari Libur','-','kepegawaian/master/hari_libur',625,'1','1','','1',NULL,'2018-03-24 11:01:04'),('1000000080','10','1000000035','Status Karyawan','-','kepegawaian/master/status_karyawan',613,'1','1','','1',NULL,'2018-03-24 11:03:30'),('1000000081','10','1000000035','Kehadiran Karyawan','-','kepegawaian/master/kehadiran',614,'1','1','','1',NULL,'2018-03-24 11:04:18'),('1000000082','10','1000000035','Kuota Cuti Karyawan','-','kepegawaian/master/kuota_cuti',615,'1','1','','1',NULL,'2018-03-24 11:13:25'),('1000000083','10','1000000035','Unit Kerja Karyawan','-','kepegawaian/master/pegawai_unit_kerja',616,'1','1','','1',NULL,'2018-03-24 11:05:41'),('1000000084','10','1000000064','Data Perencanaan','-','#',71,'1','1','','1',NULL,'2018-03-25 10:34:59'),('1000000085','10','1000000064','Data Pengeluaran','-','#',72,'1','1','','1',NULL,'2018-03-25 10:35:06'),('1000000086','10','0','Accounting','-','#',8,'1','1','fa fa-indent','1',NULL,'2018-03-24 15:00:28'),('1000000087','10','1000000034','Data Penggajian','-','#',62,'1','1','','1',NULL,'2018-03-24 11:49:05'),('1000000088','10','1000000087','Daftar Gaji Karyawan','-','kepegawaian/master/gaji',621,'1','1','','1',NULL,'2018-03-24 12:34:12'),('1000000089','10','1000000087','Gaji Bulanan Karyawan','-','kepegawaian/master/gaji_bulanan',622,'1','1','','1',NULL,'2018-03-24 12:34:45'),('1000000090','10','1000000018','Gaji Bulanan Karyawan','-','home/task/gaji',223,'1','1','','1',NULL,'2018-03-24 12:38:43'),('1000000091','10','1000000001','HR Overview','-','home/welcome/hr',12,'1','1','','1',NULL,'2018-03-24 12:43:02'),('1000000092','10','1000000001','Projects Overview','-','home/welcome/projects',13,'1','1','','1',NULL,'2018-03-24 12:43:10'),('1000000093','10','1000000001','Finances & Accounting','-','home/welcome/finances',14,'1','1','','1',NULL,'2018-03-24 12:43:21'),('1000000094','10','1000000012','Rencana & Anggaran','-','#',32,'1','1','','1',NULL,'2018-03-24 13:14:55'),('1000000095','10','1000000094','RKA Manajemen','-','project/rka_manajemen/pengajuan',321,'1','1','','1',NULL,'2018-03-24 13:27:17'),('1000000096','10','1000000094','RKA Project','-','project/rka_project/pengajuan',322,'1','1','','1',NULL,'2018-03-24 13:27:30'),('1000000097','10','1000000042','Rencana & Anggaran','RKA Manajemen','#',42,'1','1','','1',NULL,'2018-03-24 13:15:04'),('1000000098','10','1000000097','RKA Manajemen','-','project/rka_manajemen/monitoring',421,'1','1','','1',NULL,'2018-03-24 13:28:15'),('1000000099','10','1000000097','RKA Project','-','project/rka_project/monitoring',422,'1','1','','1',NULL,'2018-03-24 13:28:24'),('1000000100','10','1000000001','Selamat Datang','-','home/welcome/dashboard',10,'1','1','','1',NULL,'2018-03-24 13:07:32'),('1000000101','10','1000000012','Advance & Pembelian','-','#',33,'1','1','','1',NULL,'2018-03-24 13:10:19'),('1000000102','10','1000000101','Advance Umum','-','keuangan/advance_umum/pengajuan',331,'1','1','','1',NULL,'2018-03-24 13:36:29'),('1000000103','10','1000000101','Advance Perjalanan Dinas','-','keuangan/advance_jaldin/pengajuan',332,'1','1','','1',NULL,'2018-03-24 13:36:43'),('1000000104','10','1000000101','Permintaan Barang <= 1jt','-','keuangan/pembelian_kurang_satu_juta/pengajuan',333,'1','1','','1',NULL,'2018-03-24 13:37:50'),('1000000105','10','1000000101','Permintaan Barang > 1jt','-','keuangan/pembelian_lebih_satu_juta/pengajuan',334,'1','1','','1',NULL,'2018-03-24 13:38:03'),('1000000106','10','1000000042','Advance & Pembelian','-','#',43,'1','1','','1',NULL,'2018-03-24 13:15:39'),('1000000107','10','1000000106','Advance Umum','-','keuangan/advance_umum/monitoring',431,'1','1','','1',NULL,'2018-03-24 13:38:22'),('1000000108','10','1000000106','Advance Perjalanan Dinas','-','keuangan/advance_jaldin/monitoring',432,'1','1','','1',NULL,'2018-03-24 13:38:34'),('1000000109','10','1000000106','Permintaan Barang <= 1jt','-','keuangan/pembelian_kurang_satu_juta/monitoring',433,'1','1','','1',NULL,'2018-03-24 13:38:46'),('1000000110','10','1000000106','Permintaan Barang > 1jt','-','keuangan/pembelian_lebih_satu_juta/monitoring',434,'1','1','','1',NULL,'2018-03-24 13:39:01'),('1000000111','10','1000000025','RKA Manajemen','-','#',55,'1','1','','1',NULL,'2018-03-24 13:18:14'),('1000000112','10','1000000025','RKA Project','-','#',56,'1','1','','1',NULL,'2018-03-24 13:18:23'),('1000000113','10','1000000025','Advance Umum','-','#',57,'1','1','','1',NULL,'2018-03-24 13:18:41'),('1000000114','10','1000000025','Advance Perjalanan Dinas','-','#',58,'1','1','','1',NULL,'2018-03-24 13:18:51'),('1000000115','10','1000000025','Permintaan Barang <= 1jt','-','#',59,'1','1','','1',NULL,'2018-03-24 13:19:01'),('1000000116','10','1000000025','Permintaan Barang > 1jt','-','#',60,'1','1','','1',NULL,'2018-03-24 13:19:31'),('1000000117','10','1000000111','Persetujuan oleh Pimpinan','-','project/rka_manajemen/pimpinan',551,'1','1','','1',NULL,'2018-03-24 13:54:02'),('1000000118','10','1000000112','Persetujuan oleh Pimpinan','-','project/rka_project/pimpinan',561,'1','1','','1',NULL,'2018-03-24 13:54:08'),('1000000119','10','1000000113','Persetujuan oleh Pimpinan','-','keuangan/advance_umum/pimpinan',571,'1','1','','1',NULL,'2018-03-24 13:54:33'),('1000000120','10','1000000114','Verifikasi oleh HR','-','keuangan/advance_jaldin/hrd',582,'1','1','','1',NULL,'2018-03-24 13:57:50'),('1000000121','10','1000000115','Persetujuan Pimpinan','-','keuangan/pembelian_kurang_satu_juta/pimpinan',591,'1','1','','1',NULL,'2018-03-24 14:19:31'),('1000000122','10','1000000116','Persetujuan Pimpinan','-','keuangan/pembelian_lebih_satu_juta/pimpinan',601,'1','1','','1',NULL,'2018-03-24 14:19:45'),('1000000123','10','1000000111','Persetujuan oleh Keuangan','-','project/rka_manajemen/keuangan',552,'1','1','','1',NULL,'2018-03-24 13:48:35'),('1000000124','10','1000000111','Persetujuan oleh Dirut','-','project/rka_manajemen/dirut',553,'1','1','','1',NULL,'2018-03-24 13:47:52'),('1000000125','10','1000000112','Persetujuan oleh Keuangan','-','project/rka_project/keuangan',562,'1','1','','1',NULL,'2018-03-24 13:50:28'),('1000000126','10','1000000112','Persetujuan oleh Dirut','-','project/rka_project/dirut',563,'1','1','','1',NULL,'2018-03-24 13:50:21'),('1000000127','10','1000000113','Persetujuan oleh Keuangan','-','keuangan/advance_umum/keuangan',572,'1','1','','1',NULL,'2018-03-24 13:58:53'),('1000000128','10','1000000113','Persetujuan oleh Dirut','-','keuangan/advance_umum/dirut',573,'1','1','','1',NULL,'2018-03-24 13:59:27'),('1000000129','10','1000000113','Pencairan Dana','-','keuangan/advance_umum/kasir',574,'1','1','','1',NULL,'2018-03-24 14:00:47'),('1000000130','10','1000000113','Sisa Advance Umum','-','keuangan/advance_umum/pengembalian',575,'1','1','','1',NULL,'2018-03-24 14:18:56'),('1000000131','10','1000000012','LPJ','-','#',34,'1','1','','1',NULL,'2018-03-24 14:13:37'),('1000000132','10','1000000131','Advance Umum','-','keuangan/advance_umum/lpj',341,'1','1','','1',NULL,'2018-03-24 14:10:49'),('1000000133','10','1000000131','Advance Perjalanan Dinas','-','keuangan/advance_jaldin/lpj',342,'1','1','','1',NULL,'2018-03-24 14:11:28'),('1000000134','10','1000000131','Permintaan Barang <= 1jt','-','keuangan/pembelian_kurang_satu_juta/lpj',343,'1','1','343','1',NULL,'2018-03-24 14:12:28'),('1000000135','10','1000000131','Permintaan Barang > 1jt','-','keuangan/pembelian_lebih_satu_juta/lpj',344,'1','1','','1',NULL,'2018-03-24 14:13:10'),('1000000136','10','1000000114','Persetujuan oleh Keuangan','-','keuangan/advance_jaldin/keuangan',583,'1','1','','1',NULL,'2018-03-24 14:16:21'),('1000000137','10','1000000114','Persetujuan oleh Dirut','-','keuangan/advance_jaldin/dirut',584,'1','1','','1',NULL,'2018-03-24 14:17:11'),('1000000138','10','1000000114','Pencairan Dana','-','keuangan/advance_jaldin/kasir',585,'1','1','','1',NULL,'2018-03-24 14:17:41'),('1000000139','10','1000000114','Sisa Advance Jaldin','-','keuangan/advance_jaldin/pengembalian',586,'1','1','','1',NULL,'2018-03-24 14:18:20'),('1000000140','10','1000000115','Persetujuan oleh Keuangan','-','keuangan/pembelian_kurang_satu_juta/keuangan',592,'1','1','','1',NULL,'2018-03-24 14:22:15'),('1000000141','10','1000000115','Verifikasi oleh GA','-','keuangan/pembelian_kurang_satu_juta/ga',593,'1','1','','1',NULL,'2018-03-24 14:22:40'),('1000000142','10','1000000115','Pencairan Dana','-','keuangan/pembelian_kurang_satu_juta/kasir',594,'1','1','','1',NULL,'2018-03-24 14:23:54'),('1000000143','10','1000000115','Sisa Pengeluaran Dana','-','keuangan/pembelian_kurang_satu_juta/pengembalian',595,'1','1','','1',NULL,'2018-03-24 14:24:44'),('1000000144','10','1000000116','Persetujuan oleh Keuangan','-','keuangan/pembelian_lebih_satu_juta/keuangan',602,'1','1','','1',NULL,'2018-03-24 14:25:46'),('1000000145','10','1000000116','Persetujuan oleh Dirut','-','keuangan/pembelian_lebih_satu_juta/dirut',603,'1','1','','1',NULL,'2018-03-24 14:26:13'),('1000000146','10','1000000116','Verifikasi oleh GA','-','keuangan/pembelian_lebih_satu_juta/ga',604,'1','1','','1',NULL,'2018-03-24 14:26:39'),('1000000147','10','1000000116','Pencairan Dana','-','keuangan/pembelian_lebih_satu_juta/kasir',605,'1','1','','1',NULL,'2018-03-24 14:27:02'),('1000000148','10','1000000116','Sisa Pengeluaran Dana','-','keuangan/pembelian_lebih_satu_juta/pengembalian',606,'1','1','','1',NULL,'2018-03-24 14:27:25'),('1000000149','10','1000000068','Project Data','-','project/master/project',831,'1','1','','1',NULL,'2018-03-24 14:38:42'),('1000000150','10','1000000084','RKA Manajemen','-','keuangan/rencana/manajemen',711,'1','1','','1',NULL,'2018-03-24 14:45:34'),('1000000151','10','1000000084','RKA Project','-','keuangan/rencana/project',712,'1','1','','1',NULL,'2018-03-24 14:45:51'),('1000000152','10','1000000085','Advance Umum','-','keuangan/transaksi/advance_umum',721,'1','1','','1',NULL,'2018-03-24 14:47:02'),('1000000153','10','1000000085','Advance Perjalanan Dinas','-','keuangan/transaksi/advance_jaldin',722,'1','1','','1',NULL,'2018-03-24 14:47:28'),('1000000154','10','1000000085','Pembelian Barang','-','keuangan/transaksi/pembelian',723,'1','1','','1',NULL,'2018-03-24 14:47:54'),('1000000155','10','1000000086','Data Pendukung','-','#',89,'1','1','','1',NULL,'2018-03-25 10:36:02'),('1000000156','10','1000000086','Periode Akuntansi','-','akuntansi/jurnal/periode',81,'1','1','','1',NULL,'2018-03-25 10:37:02'),('1000000157','10','1000000086','Saldo Awal','-','akuntansi/jurnal/saldo_awal',82,'1','1','','1',NULL,'2018-03-25 10:37:20'),('1000000158','10','1000000086','Jurnal','-','#',83,'1','1','','1',NULL,'2018-03-25 10:37:41'),('1000000159','10','1000000158','Jurnal Umum','-','akuntansi/jurnal/umum',831,'1','1','','1',NULL,'2018-03-25 10:37:52'),('1000000160','10','1000000158','Jurnal Penyesuaian','-','akuntansi/jurnal/penyesuaian',832,'1','1','','1',NULL,'2018-03-25 10:38:16'),('1000000161','10','1000000158','Jurnal Penutup','-','akuntansi/jurnal/penutup',833,'1','1','','1',NULL,'2018-03-25 10:38:35'),('1000000162','10','1000000086','Buku Besar','-','#',84,'1','1','','1',NULL,'2018-03-25 10:39:17'),('1000000163','10','1000000162','Buku Besar Utama','-','akuntansi/buku_besar/utama',841,'1','1','','1',NULL,'2018-03-25 10:39:30'),('1000000164','10','1000000162','Buku Besar Pembantu','-','akuntansi/buku_besar/pembantu',842,'1','1','','1',NULL,'2018-03-25 10:39:50'),('1000000165','10','1000000086','Neraca','-','#',85,'1','1','','1',NULL,'2018-03-25 10:40:15'),('1000000166','10','1000000165','Neraca Saldo','-','akuntansi/neraca/saldo',851,'1','1','','1',NULL,'2018-03-25 10:40:30'),('1000000167','10','1000000165','Neraca Lajur','-','akuntansi/neraca/lajur',852,'1','1','','1',NULL,'2018-03-25 10:40:48'),('1000000168','10','1000000165','Neraca Saldo Penutupan','-','akuntansi/neraca/penutupan',853,'1','1','','1',NULL,'2018-03-25 10:41:06'),('1000000169','10','1000000027','Laporan Keuangan','-','#',92,'1','1','','1',NULL,'2018-03-25 10:42:55'),('1000000170','10','1000000169','Realisasi Anggaran','-','laporan/keuangan/realisasi_anggaran',921,'1','1','','1',NULL,'2018-03-25 10:43:44'),('1000000171','10','1000000169','Neraca','-','laporan/keuangan/neraca',922,'1','1','','1',NULL,'2018-03-25 10:44:06'),('1000000172','10','1000000169','Arus Kas','-','laporan/keuangan/arus_kas',923,'1','1','','1',NULL,'2018-03-25 10:44:21'),('1000000173','10','1000000155','Bagan Akun','-','akuntansi/pendukung/bagan_akun',891,'1','1','','1',NULL,'2018-03-25 10:46:13'),('1000000174','10','1000000155','Format Laporan','-','akuntansi/pendukung/format_laporan',892,'1','1','','1',NULL,'2018-03-25 10:47:16'),('1000000175','10','1000000068','Project Kontrak / Termin','-','project/tools/kontrak',834,'1','1','','1804170001',NULL,'2018-05-04 09:38:43');

UNLOCK TABLES;

/*Table structure for table `com_portal` */

DROP TABLE IF EXISTS `com_portal`;

CREATE TABLE `com_portal` (
  `portal_id` varchar(2) NOT NULL,
  `portal_nm` varchar(50) DEFAULT NULL,
  `site_title` varchar(100) DEFAULT NULL,
  `site_desc` varchar(100) DEFAULT NULL,
  `meta_desc` varchar(255) DEFAULT NULL,
  `meta_keyword` varchar(255) DEFAULT NULL,
  `create_by` varchar(50) DEFAULT NULL,
  `create_date` datetime DEFAULT NULL,
  PRIMARY KEY (`portal_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `com_portal` */

LOCK TABLES `com_portal` WRITE;

insert  into `com_portal`(`portal_id`,`portal_nm`,`site_title`,`site_desc`,`meta_desc`,`meta_keyword`,`create_by`,`create_date`) values ('10','Private Area','PT. Time Excelindo','Management Tools | PT. Time Excelindo','','',NULL,NULL);

UNLOCK TABLES;

/*Table structure for table `com_preferences` */

DROP TABLE IF EXISTS `com_preferences`;

CREATE TABLE `com_preferences` (
  `pref_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `pref_group` varchar(50) DEFAULT NULL,
  `pref_nm` varchar(50) DEFAULT NULL,
  `pref_label` varchar(50) DEFAULT NULL,
  `pref_value` text,
  `mdb` varchar(50) DEFAULT NULL,
  `mdd` datetime DEFAULT NULL,
  PRIMARY KEY (`pref_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `com_preferences` */

LOCK TABLES `com_preferences` WRITE;

UNLOCK TABLES;

/*Table structure for table `com_reset_pass` */

DROP TABLE IF EXISTS `com_reset_pass`;

CREATE TABLE `com_reset_pass` (
  `data_id` varchar(20) NOT NULL,
  `email` varchar(50) DEFAULT NULL,
  `nomor_telepon` varchar(50) DEFAULT NULL,
  `nama_lengkap` varchar(50) DEFAULT NULL,
  `jabatan` varchar(50) DEFAULT NULL,
  `request_date` datetime DEFAULT NULL,
  `request_st` enum('waiting','done') DEFAULT NULL,
  `request_expired` datetime DEFAULT NULL,
  `request_key` varchar(50) DEFAULT NULL,
  `response_by` varchar(10) DEFAULT NULL,
  `response_date` datetime DEFAULT NULL,
  `response_notes` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`data_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `com_reset_pass` */

LOCK TABLES `com_reset_pass` WRITE;

UNLOCK TABLES;

/*Table structure for table `com_role` */

DROP TABLE IF EXISTS `com_role`;

CREATE TABLE `com_role` (
  `role_id` varchar(5) NOT NULL,
  `group_id` varchar(2) DEFAULT NULL,
  `role_nm` varchar(100) DEFAULT NULL,
  `role_desc` varchar(100) DEFAULT NULL,
  `default_page` varchar(50) DEFAULT NULL,
  `mdb` varchar(50) DEFAULT NULL,
  `mdd` datetime DEFAULT NULL,
  PRIMARY KEY (`role_id`),
  KEY `group_id` (`group_id`),
  CONSTRAINT `com_role_ibfk_1` FOREIGN KEY (`group_id`) REFERENCES `com_group` (`group_id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `com_role` */

LOCK TABLES `com_role` WRITE;

insert  into `com_role`(`role_id`,`group_id`,`role_nm`,`role_desc`,`default_page`,`mdb`,`mdd`) values ('01001','01','Systems Administrator','Pengelola aplikasi, menu dan hak akses','home/welcome/dashboard','1605200001','2016-05-22 18:56:37'),('02001','02','Department Staff',NULL,'home/welcome/dashboard',NULL,NULL),('02002','02','Department Officer',NULL,'home/welcome/dashboard',NULL,NULL),('02003','02','Department Leader',NULL,'home/welcome/dashboard',NULL,NULL),('02004','02','Finance Staff',NULL,'home/welcome/dashboard',NULL,NULL),('02005','02','Finance Manager',NULL,'home/welcome/dashboard',NULL,NULL),('02006','02','Human Resources Staff',NULL,'home/welcome/dashboard',NULL,NULL),('02007','02','Human Resources Manager',NULL,'home/welcome/dashboard',NULL,NULL),('02008','02','Staff GA','Staff GA','home/welcome/dashboard','1804170001','2021-09-07 16:17:26'),('02009','02','GA Manager','GA Manager','home/welcome/dashboard','1804170001','2021-09-07 16:18:05');

UNLOCK TABLES;

/*Table structure for table `com_role_menu` */

DROP TABLE IF EXISTS `com_role_menu`;

CREATE TABLE `com_role_menu` (
  `role_id` varchar(5) NOT NULL,
  `nav_id` varchar(10) NOT NULL,
  `role_tp` varchar(4) NOT NULL DEFAULT '1111',
  PRIMARY KEY (`nav_id`,`role_id`),
  KEY `role_id` (`role_id`),
  CONSTRAINT `com_role_menu_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `com_role` (`role_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `com_role_menu_ibfk_2` FOREIGN KEY (`nav_id`) REFERENCES `com_menu` (`nav_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `com_role_menu` */

LOCK TABLES `com_role_menu` WRITE;

insert  into `com_role_menu`(`role_id`,`nav_id`,`role_tp`) values ('01001','1000000001','1111'),('02001','1000000001','1111'),('02002','1000000001','1111'),('02003','1000000001','1111'),('02004','1000000001','1111'),('02005','1000000001','1111'),('02006','1000000001','1111'),('02007','1000000001','1111'),('01001','1000000002','1111'),('01001','1000000003','1111'),('01001','1000000004','1111'),('01001','1000000005','1111'),('01001','1000000006','1111'),('01001','1000000007','1111'),('01001','1000000008','1111'),('01001','1000000009','1111'),('01001','1000000010','1111'),('01001','1000000011','1111'),('02001','1000000011','1111'),('02002','1000000011','1111'),('02003','1000000011','1111'),('02004','1000000011','1111'),('02005','1000000011','1111'),('02006','1000000011','1111'),('02007','1000000011','1111'),('01001','1000000012','1111'),('02002','1000000012','1111'),('02006','1000000012','1111'),('02007','1000000012','1111'),('01001','1000000013','1111'),('02002','1000000013','1111'),('02006','1000000013','1111'),('02007','1000000013','1111'),('01001','1000000014','1111'),('02001','1000000014','1111'),('02002','1000000014','1111'),('02003','1000000014','1111'),('02004','1000000014','1111'),('02005','1000000014','1111'),('02006','1000000014','1111'),('02007','1000000014','1111'),('01001','1000000015','1111'),('01001','1000000016','1111'),('01001','1000000017','1111'),('01001','1000000018','1111'),('02001','1000000018','1111'),('02002','1000000018','1111'),('02003','1000000018','1111'),('02004','1000000018','1111'),('02005','1000000018','1111'),('02006','1000000018','1111'),('02007','1000000018','1111'),('01001','1000000019','1111'),('02001','1000000019','1111'),('02002','1000000019','1111'),('02003','1000000019','1111'),('02004','1000000019','1111'),('02005','1000000019','1111'),('02006','1000000019','1111'),('02007','1000000019','1111'),('01001','1000000020','1111'),('02001','1000000020','1111'),('02002','1000000020','1111'),('02003','1000000020','1111'),('02004','1000000020','1111'),('02005','1000000020','1111'),('02006','1000000020','1111'),('02007','1000000020','1111'),('01001','1000000021','1111'),('02002','1000000021','1111'),('02006','1000000021','1111'),('02007','1000000021','1111'),('01001','1000000022','1111'),('02002','1000000022','1111'),('02006','1000000022','1111'),('02007','1000000022','1111'),('01001','1000000023','1111'),('02002','1000000023','1111'),('02006','1000000023','1111'),('02007','1000000023','1111'),('01001','1000000024','1111'),('02002','1000000024','1111'),('02006','1000000024','1111'),('02007','1000000024','1111'),('01001','1000000025','1111'),('02003','1000000025','1111'),('02004','1000000025','1111'),('02005','1000000025','1111'),('02006','1000000025','1111'),('02007','1000000025','1111'),('01001','1000000026','1111'),('02003','1000000026','1111'),('02005','1000000026','1111'),('02007','1000000026','1111'),('01001','1000000027','1111'),('01001','1000000028','1111'),('01001','1000000029','1111'),('01001','1000000030','1111'),('01001','1000000031','1111'),('01001','1000000032','1111'),('01001','1000000033','1111'),('01001','1000000034','1111'),('01001','1000000035','1111'),('01001','1000000036','1111'),('01001','1000000037','1111'),('01001','1000000038','1111'),('01001','1000000039','1111'),('01001','1000000040','1111'),('01001','1000000041','1111'),('01001','1000000042','1111'),('02002','1000000042','1111'),('02003','1000000042','1111'),('02006','1000000042','1111'),('02007','1000000042','1111'),('01001','1000000043','1111'),('02002','1000000043','1111'),('02003','1000000043','1111'),('02006','1000000043','1111'),('02007','1000000043','1111'),('01001','1000000044','1111'),('02002','1000000044','1111'),('02003','1000000044','1111'),('02006','1000000044','1111'),('02007','1000000044','1111'),('01001','1000000045','1111'),('02002','1000000045','1111'),('02003','1000000045','1111'),('02006','1000000045','1111'),('02007','1000000045','1111'),('01001','1000000046','1111'),('02002','1000000046','1111'),('02003','1000000046','1111'),('02006','1000000046','1111'),('02007','1000000046','1111'),('01001','1000000047','1111'),('02002','1000000047','1111'),('02003','1000000047','1111'),('02006','1000000047','1111'),('02007','1000000047','1111'),('01001','1000000048','1111'),('02003','1000000048','1111'),('02005','1000000048','1111'),('02007','1000000048','1111'),('01001','1000000049','1111'),('02003','1000000049','1111'),('02005','1000000049','1111'),('02007','1000000049','1111'),('01001','1000000050','1111'),('02003','1000000050','1111'),('02005','1000000050','1111'),('02007','1000000050','1111'),('01001','1000000051','1111'),('02003','1000000051','1111'),('02005','1000000051','1111'),('02007','1000000051','1111'),('01001','1000000052','1111'),('02003','1000000052','1111'),('02005','1000000052','1111'),('02007','1000000052','1111'),('01001','1000000053','1111'),('02003','1000000053','1111'),('02005','1000000053','1111'),('02007','1000000053','1111'),('01001','1000000054','1111'),('02007','1000000054','1111'),('01001','1000000055','1111'),('02007','1000000055','1111'),('01001','1000000056','1111'),('02007','1000000056','1111'),('01001','1000000057','1111'),('02006','1000000057','1111'),('02007','1000000057','1111'),('01001','1000000058','1111'),('02006','1000000058','1111'),('02007','1000000058','1111'),('01001','1000000059','1111'),('02003','1000000059','1111'),('02004','1000000059','1111'),('02005','1000000059','1111'),('02006','1000000059','1111'),('02007','1000000059','1111'),('01001','1000000060','1111'),('02005','1000000060','1111'),('01001','1000000061','1111'),('02004','1000000061','1111'),('01001','1000000062','1111'),('02005','1000000062','1111'),('01001','1000000063','1111'),('02004','1000000063','1111'),('01001','1000000064','1111'),('01001','1000000065','1111'),('01001','1000000066','1111'),('01001','1000000067','1111'),('01001','1000000068','1111'),('01001','1000000070','1111'),('01001','1000000071','1111'),('01001','1000000072','1111'),('01001','1000000074','1111'),('01001','1000000075','1111'),('01001','1000000076','1111'),('01001','1000000077','1111'),('01001','1000000078','1111'),('01001','1000000079','1111'),('01001','1000000080','1111'),('01001','1000000081','1111'),('01001','1000000082','1111'),('01001','1000000083','1111'),('01001','1000000084','1111'),('01001','1000000085','1111'),('01001','1000000086','1111'),('01001','1000000087','1111'),('01001','1000000088','1111'),('01001','1000000089','1111'),('01001','1000000090','1111'),('02001','1000000090','1111'),('02002','1000000090','1111'),('02003','1000000090','1111'),('02004','1000000090','1111'),('02005','1000000090','1111'),('02006','1000000090','1111'),('02007','1000000090','1111'),('01001','1000000091','1111'),('02001','1000000091','1111'),('02002','1000000091','1111'),('02003','1000000091','1111'),('02004','1000000091','1111'),('02005','1000000091','1111'),('02006','1000000091','1111'),('02007','1000000091','1111'),('01001','1000000092','1111'),('02001','1000000092','1111'),('02002','1000000092','1111'),('02003','1000000092','1111'),('02004','1000000092','1111'),('02005','1000000092','1111'),('02006','1000000092','1111'),('02007','1000000092','1111'),('01001','1000000093','1111'),('02001','1000000093','1111'),('02002','1000000093','1111'),('02003','1000000093','1111'),('02004','1000000093','1111'),('02005','1000000093','1111'),('02006','1000000093','1111'),('02007','1000000093','1111'),('01001','1000000094','1111'),('01001','1000000095','1111'),('01001','1000000096','1111'),('01001','1000000097','1111'),('01001','1000000098','1111'),('01001','1000000099','1111'),('01001','1000000100','1111'),('02001','1000000100','1111'),('02002','1000000100','1111'),('02003','1000000100','1111'),('02004','1000000100','1111'),('02005','1000000100','1111'),('02006','1000000100','1111'),('02007','1000000100','1111'),('01001','1000000101','1111'),('01001','1000000102','1111'),('01001','1000000103','1111'),('01001','1000000104','1111'),('01001','1000000105','1111'),('01001','1000000106','1111'),('01001','1000000107','1111'),('01001','1000000108','1111'),('01001','1000000109','1111'),('01001','1000000110','1111'),('01001','1000000111','1111'),('01001','1000000112','1111'),('01001','1000000113','1111'),('01001','1000000114','1111'),('01001','1000000115','1111'),('02003','1000000115','1111'),('01001','1000000116','1111'),('02003','1000000116','1111'),('01001','1000000117','1111'),('01001','1000000118','1111'),('01001','1000000119','1111'),('01001','1000000120','1111'),('01001','1000000121','1111'),('02003','1000000121','1111'),('01001','1000000122','1111'),('02003','1000000122','1111'),('01001','1000000123','1111'),('01001','1000000124','1111'),('01001','1000000125','1111'),('01001','1000000126','1111'),('01001','1000000127','1111'),('01001','1000000128','1111'),('01001','1000000129','1111'),('01001','1000000130','1111'),('01001','1000000131','1111'),('01001','1000000132','1111'),('01001','1000000133','1111'),('01001','1000000134','1111'),('01001','1000000135','1111'),('01001','1000000136','1111'),('01001','1000000137','1111'),('01001','1000000138','1111'),('01001','1000000139','1111'),('01001','1000000140','1111'),('01001','1000000141','1111'),('01001','1000000142','1111'),('01001','1000000143','1111'),('01001','1000000144','1111'),('01001','1000000145','1111'),('01001','1000000146','1111'),('01001','1000000147','1111'),('01001','1000000148','1111'),('01001','1000000149','1111'),('01001','1000000150','1111'),('01001','1000000151','1111'),('01001','1000000152','1111'),('01001','1000000153','1111'),('01001','1000000154','1111'),('01001','1000000155','1111'),('01001','1000000156','1111'),('01001','1000000157','1111'),('01001','1000000158','1111'),('01001','1000000159','1111'),('01001','1000000160','1111'),('01001','1000000161','1111'),('01001','1000000162','1111'),('01001','1000000163','1111'),('01001','1000000164','1111'),('01001','1000000165','1111'),('01001','1000000166','1111'),('01001','1000000167','1111'),('01001','1000000168','1111'),('01001','1000000169','1111'),('01001','1000000170','1111'),('01001','1000000171','1111'),('01001','1000000172','1111'),('01001','1000000173','1111'),('01001','1000000174','1111'),('01001','1000000175','1111');

UNLOCK TABLES;

/*Table structure for table `com_role_user` */

DROP TABLE IF EXISTS `com_role_user`;

CREATE TABLE `com_role_user` (
  `user_id` varchar(10) NOT NULL,
  `role_id` varchar(5) NOT NULL,
  `role_default` enum('1','2') DEFAULT '2',
  `role_display` enum('1','0') DEFAULT '1',
  PRIMARY KEY (`user_id`,`role_id`),
  KEY `role_id` (`role_id`),
  CONSTRAINT `com_role_user_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `com_user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `com_role_user_ibfk_2` FOREIGN KEY (`role_id`) REFERENCES `com_role` (`role_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `com_role_user` */

LOCK TABLES `com_role_user` WRITE;

insert  into `com_role_user`(`user_id`,`role_id`,`role_default`,`role_display`) values ('1804170001','01001','1','1'),('1804170001','02003','2','1'),('1804170002','02007','2','1'),('1804170003','02005','2','1'),('1804170004','02003','2','1'),('1804170005','02004','2','1'),('1804170006','02006','2','1'),('1804170007','02002','2','1'),('1804170008','02001','2','1'),('1804170009','02001','2','1');

UNLOCK TABLES;

/*Table structure for table `com_user` */

DROP TABLE IF EXISTS `com_user`;

CREATE TABLE `com_user` (
  `user_id` varchar(10) NOT NULL,
  `user_alias` varchar(50) DEFAULT NULL,
  `user_name` varchar(50) DEFAULT NULL,
  `user_pass` varchar(255) DEFAULT NULL,
  `user_key` varchar(50) DEFAULT NULL,
  `user_mail` varchar(50) DEFAULT NULL,
  `user_img_name` varchar(255) DEFAULT NULL,
  `user_img_path` varchar(255) DEFAULT NULL,
  `user_st` enum('1','0','2') DEFAULT '0',
  `user_completed` enum('1','0') DEFAULT '0',
  `mdb` varchar(10) DEFAULT NULL,
  `mdb_name` varchar(50) DEFAULT NULL,
  `mdd` datetime DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `user_name` (`user_name`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `com_user` */

LOCK TABLES `com_user` WRITE;

insert  into `com_user`(`user_id`,`user_alias`,`user_name`,`user_pass`,`user_key`,`user_mail`,`user_img_name`,`user_img_path`,`user_st`,`user_completed`,`mdb`,`mdb_name`,`mdd`) values ('1804170001','Welly WSP','welly.wsp@excelindo.co.id','$2y$10$Ev0fEjRQUiszlP3owm/RSewr/0ePPSqZyndbNrlvy8Tok6dOtHYLm',NULL,'welly.wsp@excelindo.co.id','1605200001.jpg',NULL,'1','1','1','Welly Widodo Sindu Putra','2014-08-06 10:00:52'),('1804170002','Para Anto Wijanarko','para','Q3NOB11Gbbg46EqS5xj0DVKBHmYIpk9z0f7IxTPOu6+XUrtIAzBCquZmqe1wAs1ReTtl0sx5aDnkrplBiRrbJg==','617780269','para.aw@excelindo.co.id',NULL,NULL,'1','0','1','Welly Widodo Sindu Putra','2018-04-17 13:41:39'),('1804170003','Aulia Shofi','aulia','eE/pypCCLOQkmsunZeWKUmH0yWj5IqFyIbbnzpsUGw1xZg7JYgQZC4MUNW9L7DWKn2uyX9dJ7tQoHwyhnBNnEA==','237614550','aulia.s@excelindo.co.id',NULL,NULL,'1','0','1804170001','Welly Widodo Sindu Putra','2018-04-17 13:50:46'),('1804170004','Agung Budi Prasetyo','agung','6Zy9QU1+Ir47uQdvBFCsqOMPaV8FJND1c91cjAvfn/ID/FDAnJxol3VgJ6w7fXvE2mqFMydUH761L6Dsr7GFsA==','1106018765','agung.bp@excelindo.co.id',NULL,NULL,'1','0','1804170001','Welly Widodo Sindu Putra','2018-04-17 13:51:41'),('1804170005','Adellia Winda Putri','adellia','COD9DYPM0Mwk94qXJ8SVgR/drI8+FDX1Vz+bpxFrPaA/i49M8+yImlpq+RD6ctSpA+VEMlcgNOPF6TpveFhkNg==','861022591','adellia.wp@excelindo.co.id',NULL,NULL,'1','0','1804170001','Welly Widodo Sindu Putra','2018-04-17 13:52:49'),('1804170006','Irma Suwarning Dyastuti','irma','5lfphPgV/+JoG7H0DdkHfbC1LykCmDOdXmYd1+Pic4u3WSLdcUD4qBYq1hgWOQELTDFsOJ5AbKFBdkq6gb+eUw==','621174897','irma.sd@excelindo.co.id',NULL,NULL,'1','0','1804170001','Welly Widodo Sindu Putra','2018-04-17 13:54:23'),('1804170007','Rini','rini','8g4mwPKKHu6CzORojembcxY1T9YLuKTeNou7h3KUbK4Z3LDTBt+reTWF+cWYSN2cXxprMYFdc+g0cjYkUCS62A==','1751439473','rini@excelindo.co.id',NULL,NULL,'1','0','1804170001','Welly Widodo Sindu Putra','2018-04-17 13:55:01'),('1804170008','Salmuasih','sal','v1yPMYfluN89Al+5wnbZ/bZmUf+aFq797E6Nc9PSfOYvcyzmSLgGhHbhzByTrLnj0sED0NWaxs0+njDTrzXkkw==','1860929394','salmuasih@excelindo.co.id',NULL,NULL,'1','0','1804170001','Welly Widodo Sindu Putra','2018-04-17 14:02:31'),('1804170009','Muhammad Sulaiman Yusuf','yusuf','Hw7xDsNlbyQ8ARn/4UqYTJ+mKboy+hnW7BKCFx9IeAxZeCFYQmSyFaO9ZHs+Q8SCfTCp6WEQRIBdQAvWDCSdxg==','835732774','sulaiman.y@excelindo.co.id',NULL,NULL,'1','0','1804170001','Welly Widodo Sindu Putra','2018-04-17 14:04:06'),('1810220001','asdadsad','latip','TGrt3w0DrqyZ7PlvT9JOon+Hv/Z09fpZvuh9s68WahdOwOP/fkJZSTg4a4WL+5kFRolW7P1nEd+tyR+d2BLGIQ==','1363633047','ajisanthoshol@gmail.com',NULL,NULL,'0','0','1804170001','Welly WSP','2018-10-22 14:32:09');

UNLOCK TABLES;

/*Table structure for table `com_user_login` */

DROP TABLE IF EXISTS `com_user_login`;

CREATE TABLE `com_user_login` (
  `user_id` varchar(10) NOT NULL,
  `login_date` datetime NOT NULL,
  `logout_date` datetime DEFAULT NULL,
  `ip_address` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`user_id`,`login_date`),
  CONSTRAINT `com_user_login_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `com_user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `com_user_login` */

LOCK TABLES `com_user_login` WRITE;

insert  into `com_user_login`(`user_id`,`login_date`,`logout_date`,`ip_address`) values ('1','2018-03-06 14:08:10','2018-03-06 17:04:12','::1'),('1','2018-03-07 08:52:42',NULL,'::1'),('1','2018-03-12 08:59:19',NULL,'::1'),('1','2018-03-13 12:09:11',NULL,'::1'),('1','2018-03-15 10:58:02',NULL,'::1'),('1','2018-03-16 09:52:29',NULL,'::1'),('1','2018-03-18 10:41:31',NULL,'::1'),('1','2018-03-19 06:12:54',NULL,'::1'),('1','2018-03-24 09:45:22',NULL,'::1'),('1','2018-03-25 10:01:16',NULL,'::1'),('1','2018-03-26 16:26:46',NULL,'::1'),('1','2018-03-27 08:48:19',NULL,'::1'),('1','2018-04-17 13:33:07',NULL,'::1'),('1804170001','2018-04-18 11:33:37',NULL,'::1'),('1804170001','2018-04-26 07:56:55',NULL,'::1'),('1804170001','2018-05-02 09:43:55',NULL,'::1'),('1804170001','2021-09-03 15:41:00',NULL,'127.0.0.1'),('1804170001','2021-09-07 13:32:00',NULL,'127.0.0.1'),('1804170001','2021-09-08 09:58:54',NULL,'127.0.0.1');

UNLOCK TABLES;

/*Table structure for table `com_user_super` */

DROP TABLE IF EXISTS `com_user_super`;

CREATE TABLE `com_user_super` (
  `user_id` varchar(10) NOT NULL,
  PRIMARY KEY (`user_id`),
  CONSTRAINT `com_user_super_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `com_user` (`user_id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `com_user_super` */

LOCK TABLES `com_user_super` WRITE;

insert  into `com_user_super`(`user_id`) values ('1');

UNLOCK TABLES;

/*Table structure for table `data_akun` */

DROP TABLE IF EXISTS `data_akun`;

CREATE TABLE `data_akun` (
  `kode_akun` varchar(8) NOT NULL,
  `perusahaan_id` varchar(5) NOT NULL,
  `group_kode` varchar(2) DEFAULT NULL,
  `kode_akun_alias` varchar(8) DEFAULT NULL,
  `nama_akun` varchar(500) DEFAULT NULL,
  `level_akun` enum('1','2','3','4','5','6') DEFAULT NULL,
  `penjelasan` varchar(500) DEFAULT NULL,
  `mdb` varchar(10) DEFAULT NULL,
  `mdb_name` varchar(50) DEFAULT NULL,
  `mdd` datetime DEFAULT NULL,
  PRIMARY KEY (`kode_akun`,`perusahaan_id`),
  KEY `group_kode` (`group_kode`),
  KEY `perusahaan_id` (`perusahaan_id`),
  CONSTRAINT `data_akun_ibfk_1` FOREIGN KEY (`group_kode`) REFERENCES `data_akun_group` (`group_kode`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `data_akun` */

LOCK TABLES `data_akun` WRITE;

UNLOCK TABLES;

/*Table structure for table `data_akun_group` */

DROP TABLE IF EXISTS `data_akun_group`;

CREATE TABLE `data_akun_group` (
  `group_kode` varchar(2) NOT NULL,
  `group_name` varchar(50) DEFAULT NULL,
  `group_jenis` enum('debet','kredit') DEFAULT NULL,
  `mdb` varchar(10) DEFAULT NULL,
  `mdb_name` varchar(50) DEFAULT NULL,
  `mdd` datetime DEFAULT NULL,
  PRIMARY KEY (`group_kode`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `data_akun_group` */

LOCK TABLES `data_akun_group` WRITE;

insert  into `data_akun_group`(`group_kode`,`group_name`,`group_jenis`,`mdb`,`mdb_name`,`mdd`) values ('01','ASET','debet',NULL,NULL,NULL),('02','MODAL','kredit',NULL,NULL,NULL),('03','HUTANG','kredit',NULL,NULL,NULL),('04','PENDAPATAN','kredit',NULL,NULL,NULL),('05','BEBAN / BIAYA','debet',NULL,NULL,NULL);

UNLOCK TABLES;

/*Table structure for table `data_akun_perusahaan` */

DROP TABLE IF EXISTS `data_akun_perusahaan`;

CREATE TABLE `data_akun_perusahaan` (
  `data_id` varchar(10) NOT NULL,
  `kode_akun` varchar(8) NOT NULL,
  `struktur_cd` varchar(10) NOT NULL,
  PRIMARY KEY (`data_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `data_akun_perusahaan` */

LOCK TABLES `data_akun_perusahaan` WRITE;

UNLOCK TABLES;

/*Table structure for table `data_clock_blacklist` */

DROP TABLE IF EXISTS `data_clock_blacklist`;

CREATE TABLE `data_clock_blacklist` (
  `clock_blacklist_id` int(11) NOT NULL AUTO_INCREMENT,
  `clock` time DEFAULT NULL,
  `clock_blacklist_active` enum('yes','no') DEFAULT 'no',
  `mdb` varchar(30) DEFAULT '1010',
  `mdd` datetime DEFAULT NULL,
  PRIMARY KEY (`clock_blacklist_id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=latin1;

/*Data for the table `data_clock_blacklist` */

LOCK TABLES `data_clock_blacklist` WRITE;

insert  into `data_clock_blacklist`(`clock_blacklist_id`,`clock`,`clock_blacklist_active`,`mdb`,`mdd`) values (1,'01:00:00','no','1010',NULL),(2,'02:00:00','no','1010',NULL),(3,'03:00:00','no','1010',NULL),(4,'04:00:00','no','1010',NULL),(5,'05:00:00','no','1010',NULL),(6,'06:00:00','no','1010',NULL),(7,'07:00:00','no','1010',NULL),(8,'08:00:00','yes','1010',NULL),(9,'09:00:00','yes','1010',NULL),(10,'10:00:00','no','1010',NULL),(11,'11:00:00','no','1010',NULL),(12,'12:00:00','no','1010',NULL),(13,'13:00:00','no','1010',NULL),(14,'14:00:00','no','1010',NULL),(15,'15:00:00','no','1010',NULL),(16,'16:00:00','yes','1010',NULL),(17,'17:00:00','yes','1010',NULL),(18,'18:00:00','no','1010',NULL),(19,'19:00:00','no','1010',NULL),(20,'20:00:00','no','1010',NULL),(21,'21:00:00','no','1010',NULL),(22,'22:00:00','no','1010',NULL),(23,'23:00:00','no','1010',NULL),(24,'24:00:00','no','1010',NULL),(25,'00:00:00','no','1010',NULL);

UNLOCK TABLES;

/*Table structure for table `data_hari_kerja` */

DROP TABLE IF EXISTS `data_hari_kerja`;

CREATE TABLE `data_hari_kerja` (
  `jadwal_id` varchar(5) NOT NULL,
  `hari_kerja_id` char(1) NOT NULL,
  `jam_total` time DEFAULT NULL,
  `jadwal_masuk_awal` time DEFAULT NULL,
  `jadwal_pulang_awal` time DEFAULT NULL,
  `jadwal_masuk_akhir` time DEFAULT NULL,
  `jadwal_pulang_akhir` time DEFAULT NULL,
  `mdb` varchar(10) DEFAULT NULL,
  `mdb_name` varchar(50) DEFAULT NULL,
  `mdd` datetime DEFAULT NULL,
  PRIMARY KEY (`hari_kerja_id`,`jadwal_id`),
  KEY `jadwal_id` (`jadwal_id`),
  CONSTRAINT `data_hari_kerja_ibfk_1` FOREIGN KEY (`jadwal_id`) REFERENCES `data_jadwal_kerja` (`jadwal_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `data_hari_kerja` */

LOCK TABLES `data_hari_kerja` WRITE;

UNLOCK TABLES;

/*Table structure for table `data_hari_libur` */

DROP TABLE IF EXISTS `data_hari_libur`;

CREATE TABLE `data_hari_libur` (
  `libur_id` varchar(12) NOT NULL COMMENT '2016.08.01.1',
  `libur_tanggal` date DEFAULT NULL,
  `libur_jenis` enum('NASIONAL','KANTOR') DEFAULT 'NASIONAL',
  `libur_judul` varchar(100) DEFAULT NULL,
  `libur_keterangan` varchar(255) DEFAULT NULL,
  `mdb` varchar(10) DEFAULT NULL,
  `mdb_name` varchar(50) DEFAULT NULL,
  `mdd` datetime DEFAULT NULL,
  PRIMARY KEY (`libur_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `data_hari_libur` */

LOCK TABLES `data_hari_libur` WRITE;

UNLOCK TABLES;

/*Table structure for table `data_jabatan_fungsional` */

DROP TABLE IF EXISTS `data_jabatan_fungsional`;

CREATE TABLE `data_jabatan_fungsional` (
  `jabatan_fungsional_id` varchar(5) NOT NULL,
  `jabatan_nama` varchar(100) DEFAULT NULL,
  `jabatan_alias` varchar(50) DEFAULT NULL,
  `jabatan_keterangan` text,
  `jabatan_level` int(11) unsigned DEFAULT NULL,
  `mdb` varchar(10) DEFAULT NULL,
  `mdb_name` varchar(50) DEFAULT NULL,
  `mdd` datetime DEFAULT NULL,
  PRIMARY KEY (`jabatan_fungsional_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `data_jabatan_fungsional` */

LOCK TABLES `data_jabatan_fungsional` WRITE;

UNLOCK TABLES;

/*Table structure for table `data_jabatan_struktural` */

DROP TABLE IF EXISTS `data_jabatan_struktural`;

CREATE TABLE `data_jabatan_struktural` (
  `jabatan_struktural_id` varchar(15) NOT NULL,
  `struktur_cd` varchar(10) DEFAULT NULL,
  `jabatan_nama` varchar(100) DEFAULT NULL,
  `jabatan_alias` varchar(50) DEFAULT NULL,
  `jabatan_keterangan` text,
  `jabatan_level` int(11) unsigned DEFAULT NULL,
  `mdb` varchar(10) DEFAULT NULL,
  `mdb_name` varchar(50) DEFAULT NULL,
  `mdd` datetime DEFAULT NULL,
  PRIMARY KEY (`jabatan_struktural_id`),
  KEY `struktur_cd` (`struktur_cd`),
  CONSTRAINT `data_jabatan_struktural_ibfk_1` FOREIGN KEY (`struktur_cd`) REFERENCES `data_struktur_organisasi` (`struktur_cd`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `data_jabatan_struktural` */

LOCK TABLES `data_jabatan_struktural` WRITE;

insert  into `data_jabatan_struktural`(`jabatan_struktural_id`,`struktur_cd`,`jabatan_nama`,`jabatan_alias`,`jabatan_keterangan`,`jabatan_level`,`mdb`,`mdb_name`,`mdd`) values ('001.00.00.01','001.00.00','DIREKTUR UTAMA','DIRUT',NULL,1,NULL,NULL,NULL),('001.01.00.01','001.01.00','GENERAL MANAGER','GM',NULL,2,NULL,NULL,NULL),('001.01.01.01','001.01.01','KOORDINATOR','KOORDINATOR',NULL,3,NULL,NULL,NULL),('001.01.02.01','001.01.02','KOORDINATOR','KOORDINATOR',NULL,3,NULL,NULL,NULL),('001.01.03.01','001.01.03','KOORDINATOR','KOORDINATOR',NULL,3,NULL,NULL,NULL),('001.02.00.01','001.02.00','GENERAL MANAGER','GM',NULL,2,NULL,NULL,NULL);

UNLOCK TABLES;

/*Table structure for table `data_jadwal_kerja` */

DROP TABLE IF EXISTS `data_jadwal_kerja`;

CREATE TABLE `data_jadwal_kerja` (
  `jadwal_id` varchar(5) NOT NULL,
  `jadwal_tahun` year(4) DEFAULT NULL,
  `jadwal_nama` varchar(50) DEFAULT NULL,
  `jadwal_status` enum('normal','khusus') DEFAULT NULL,
  `jadwal_mulai` date DEFAULT NULL,
  `jadwal_selesai` date DEFAULT NULL,
  `mdb` varchar(10) DEFAULT NULL,
  `mdb_name` varchar(50) DEFAULT NULL,
  `mdd` datetime DEFAULT NULL,
  PRIMARY KEY (`jadwal_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `data_jadwal_kerja` */

LOCK TABLES `data_jadwal_kerja` WRITE;

UNLOCK TABLES;

/*Table structure for table `data_jenis_cuti` */

DROP TABLE IF EXISTS `data_jenis_cuti`;

CREATE TABLE `data_jenis_cuti` (
  `jenis_id` varchar(5) NOT NULL,
  `jenis_cuti` varchar(50) DEFAULT NULL,
  `jumlah_cuti_min` int(11) unsigned DEFAULT NULL,
  `jumlah_cuti_max` int(11) unsigned DEFAULT NULL,
  `mdb` varchar(10) DEFAULT NULL,
  `mdb_name` varchar(50) DEFAULT NULL,
  `mdd` datetime DEFAULT NULL,
  PRIMARY KEY (`jenis_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `data_jenis_cuti` */

LOCK TABLES `data_jenis_cuti` WRITE;

insert  into `data_jenis_cuti`(`jenis_id`,`jenis_cuti`,`jumlah_cuti_min`,`jumlah_cuti_max`,`mdb`,`mdb_name`,`mdd`) values ('CT.01','TAHUNAN',12,14,'1',NULL,'2018-03-13 15:50:58'),('CT.02','MENIKAH',0,3,'1',NULL,'2018-03-13 15:51:05'),('CT.03','MELAHIRKAN',0,90,'1',NULL,'2018-03-13 15:51:11');

UNLOCK TABLES;

/*Table structure for table `data_jenis_dokumen` */

DROP TABLE IF EXISTS `data_jenis_dokumen`;

CREATE TABLE `data_jenis_dokumen` (
  `jenis_id` varchar(5) NOT NULL,
  `doc_name` varchar(50) DEFAULT NULL,
  `doc_desc` varchar(255) DEFAULT NULL,
  `mdb` varchar(10) DEFAULT NULL,
  `mdb_name` varchar(50) DEFAULT NULL,
  `mdd` datetime DEFAULT NULL,
  PRIMARY KEY (`jenis_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `data_jenis_dokumen` */

LOCK TABLES `data_jenis_dokumen` WRITE;

UNLOCK TABLES;

/*Table structure for table `data_jenis_izin` */

DROP TABLE IF EXISTS `data_jenis_izin`;

CREATE TABLE `data_jenis_izin` (
  `jenis_id` varchar(5) NOT NULL,
  `jenis_izin` varchar(50) DEFAULT NULL,
  `mdb` varchar(10) DEFAULT NULL,
  `mdb_name` varchar(50) DEFAULT NULL,
  `mdd` datetime DEFAULT NULL,
  PRIMARY KEY (`jenis_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `data_jenis_izin` */

LOCK TABLES `data_jenis_izin` WRITE;

insert  into `data_jenis_izin`(`jenis_id`,`jenis_izin`,`mdb`,`mdb_name`,`mdd`) values ('IZ.01','TIDAK MASUK KERJA','1',NULL,'2018-03-13 15:50:11'),('IZ.02','TIDAK MELAKUKAN PRESENSI','1',NULL,'2018-03-13 15:50:13'),('IZ.03','DATANG TERLAMBAT','1',NULL,'2018-03-13 15:50:13'),('IZ.04','PULANG LEBIH AWAL','1',NULL,'2018-03-13 15:50:14'),('IZ.05','MENINGGALKAN KERJA SELAMA JAM KERJA','1',NULL,'2018-03-13 15:50:15');

UNLOCK TABLES;

/*Table structure for table `data_jenis_kegiatan` */

DROP TABLE IF EXISTS `data_jenis_kegiatan`;

CREATE TABLE `data_jenis_kegiatan` (
  `jenis_kode_kegiatan` varchar(1) NOT NULL,
  `nama_kegiatan` varchar(255) DEFAULT NULL,
  `mdb` varchar(10) DEFAULT NULL,
  `mdb_name` varchar(50) DEFAULT NULL,
  `mdd` datetime DEFAULT NULL,
  PRIMARY KEY (`jenis_kode_kegiatan`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `data_jenis_kegiatan` */

LOCK TABLES `data_jenis_kegiatan` WRITE;

insert  into `data_jenis_kegiatan`(`jenis_kode_kegiatan`,`nama_kegiatan`,`mdb`,`mdb_name`,`mdd`) values ('A','DUKUNGAN MANAJEMEN DAN TEKNIS',NULL,NULL,NULL),('B','PROJECTS',NULL,NULL,NULL);

UNLOCK TABLES;

/*Table structure for table `data_jenis_output` */

DROP TABLE IF EXISTS `data_jenis_output`;

CREATE TABLE `data_jenis_output` (
  `jenis_kode_output` varchar(5) NOT NULL,
  `nama_output` varchar(255) DEFAULT NULL,
  `mdb` varchar(10) DEFAULT NULL,
  `mdb_name` varchar(50) DEFAULT NULL,
  `mdd` datetime DEFAULT NULL,
  PRIMARY KEY (`jenis_kode_output`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `data_jenis_output` */

LOCK TABLES `data_jenis_output` WRITE;

insert  into `data_jenis_output`(`jenis_kode_output`,`nama_output`,`mdb`,`mdb_name`,`mdd`) values ('00001','Output 1',NULL,NULL,NULL),('00002','Output 2',NULL,NULL,NULL),('00003','Output 3',NULL,NULL,NULL);

UNLOCK TABLES;

/*Table structure for table `data_jenis_pengeluaran` */

DROP TABLE IF EXISTS `data_jenis_pengeluaran`;

CREATE TABLE `data_jenis_pengeluaran` (
  `jenis_id` varchar(5) NOT NULL,
  `jenis_biaya` varchar(100) DEFAULT NULL,
  `mdb` varchar(10) DEFAULT NULL,
  `mdb_name` varchar(50) DEFAULT NULL,
  `mdd` datetime DEFAULT NULL,
  PRIMARY KEY (`jenis_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `data_jenis_pengeluaran` */

LOCK TABLES `data_jenis_pengeluaran` WRITE;

insert  into `data_jenis_pengeluaran`(`jenis_id`,`jenis_biaya`,`mdb`,`mdb_name`,`mdd`) values ('00001','Biaya 1',NULL,NULL,NULL),('00002','Biaya 2',NULL,NULL,NULL);

UNLOCK TABLES;

/*Table structure for table `data_jenis_program` */

DROP TABLE IF EXISTS `data_jenis_program`;

CREATE TABLE `data_jenis_program` (
  `jenis_kode_progran` varchar(3) NOT NULL,
  `nama_program` varchar(255) DEFAULT NULL,
  `mdb` varchar(10) DEFAULT NULL,
  `mdb_name` varchar(50) DEFAULT NULL,
  `mdd` datetime DEFAULT NULL,
  PRIMARY KEY (`jenis_kode_progran`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `data_jenis_program` */

LOCK TABLES `data_jenis_program` WRITE;

insert  into `data_jenis_program`(`jenis_kode_progran`,`nama_program`,`mdb`,`mdb_name`,`mdd`) values ('MK','Program Pengembangan dan Operasional Marketing',NULL,NULL,NULL),('SD','Program Pengembangan dan Produksi Perangkat Lunak',NULL,NULL,NULL);

UNLOCK TABLES;

/*Table structure for table `data_mesin_presensi` */

DROP TABLE IF EXISTS `data_mesin_presensi`;

CREATE TABLE `data_mesin_presensi` (
  `mesin_id` varchar(2) NOT NULL,
  `mesin_ip` varchar(20) DEFAULT NULL,
  `mesin_lokasi` varchar(50) DEFAULT NULL,
  `mdb` varchar(10) DEFAULT NULL,
  `mdb_name` varchar(50) DEFAULT NULL,
  `mdd` datetime DEFAULT NULL,
  PRIMARY KEY (`mesin_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `data_mesin_presensi` */

LOCK TABLES `data_mesin_presensi` WRITE;

insert  into `data_mesin_presensi`(`mesin_id`,`mesin_ip`,`mesin_lokasi`,`mdb`,`mdb_name`,`mdd`) values ('01','127.0.0.1',NULL,'1010',NULL,'2018-05-18 09:07:31'),('02','180.214.246.154',NULL,'1010',NULL,'2018-08-15 10:52:18');

UNLOCK TABLES;

/*Table structure for table `data_perusahaan` */

DROP TABLE IF EXISTS `data_perusahaan`;

CREATE TABLE `data_perusahaan` (
  `struktur_cd` varchar(10) NOT NULL,
  `perusahaan_nama` varchar(100) DEFAULT NULL,
  `perusahaan_alamat` varchar(255) DEFAULT NULL,
  `perusahaan_kota` varchar(50) DEFAULT NULL,
  `perusahaan_propinsi` varchar(50) DEFAULT NULL,
  `perusahaan_email` varchar(50) DEFAULT NULL,
  `perusahaan_telepon` varchar(50) DEFAULT NULL,
  `npwp_nomor` varchar(50) DEFAULT NULL,
  `npwp_tanggal` date DEFAULT NULL,
  `logo_file_path` varchar(255) DEFAULT NULL,
  `logo_file_name` varchar(255) DEFAULT NULL,
  `mdb` varchar(10) DEFAULT NULL,
  `mdb_name` varchar(50) DEFAULT NULL,
  `mdd` datetime DEFAULT NULL,
  PRIMARY KEY (`struktur_cd`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `data_perusahaan` */

LOCK TABLES `data_perusahaan` WRITE;

UNLOCK TABLES;

/*Table structure for table `data_perusahaan_rekening` */

DROP TABLE IF EXISTS `data_perusahaan_rekening`;

CREATE TABLE `data_perusahaan_rekening` (
  `alamat_id` varchar(20) NOT NULL,
  `perusahaan_id` varchar(5) DEFAULT NULL,
  `kode_akun` varchar(10) DEFAULT NULL,
  `nama_bank` varchar(100) DEFAULT NULL,
  `nomor_rekening` varchar(50) DEFAULT NULL,
  `is_used` enum('1','0') DEFAULT '1',
  `mdb` varchar(10) DEFAULT NULL,
  `mdb_name` varchar(50) DEFAULT NULL,
  `mdd` datetime DEFAULT NULL,
  PRIMARY KEY (`alamat_id`),
  KEY `perusahaan_id` (`perusahaan_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `data_perusahaan_rekening` */

LOCK TABLES `data_perusahaan_rekening` WRITE;

UNLOCK TABLES;

/*Table structure for table `data_presensi` */

DROP TABLE IF EXISTS `data_presensi`;

CREATE TABLE `data_presensi` (
  `presensi_id` varchar(20) NOT NULL,
  `mesin_id` varchar(2) DEFAULT NULL,
  `mesin_ip` varchar(20) DEFAULT NULL,
  `presensi_nip` varchar(10) DEFAULT NULL,
  `presensi_tanggal` date DEFAULT NULL,
  `presensi_waktu` time DEFAULT NULL,
  `presensi_status` enum('IN','OUT') DEFAULT NULL,
  `mdb` varchar(10) DEFAULT NULL,
  `mdb_name` varchar(50) DEFAULT NULL,
  `mdd` datetime DEFAULT NULL,
  PRIMARY KEY (`presensi_id`),
  KEY `mesin_id` (`mesin_id`),
  CONSTRAINT `data_presensi_ibfk_1` FOREIGN KEY (`mesin_id`) REFERENCES `data_mesin_presensi` (`mesin_id`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `data_presensi` */

LOCK TABLES `data_presensi` WRITE;

UNLOCK TABLES;

/*Table structure for table `data_struktur_organisasi` */

DROP TABLE IF EXISTS `data_struktur_organisasi`;

CREATE TABLE `data_struktur_organisasi` (
  `struktur_cd` varchar(10) NOT NULL,
  `struktur_induk` varchar(10) DEFAULT NULL,
  `struktur_nama` varchar(100) DEFAULT NULL,
  `struktur_singkatan` varchar(50) DEFAULT NULL,
  `struktur_keterangan` varchar(255) DEFAULT NULL,
  `struktur_level` int(11) unsigned DEFAULT NULL,
  `struktur_level_label` enum('PERUSAHAAN','DEPARTEMEN','DIVISI','SEKSI','KANTOR CABANG','KANTOR REPRESENTATIF') DEFAULT NULL,
  `struktur_kode` varchar(3) DEFAULT NULL,
  `mdb` varchar(10) DEFAULT NULL,
  `mdb_name` varchar(50) DEFAULT NULL,
  `mdd` datetime DEFAULT NULL,
  PRIMARY KEY (`struktur_cd`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `data_struktur_organisasi` */

LOCK TABLES `data_struktur_organisasi` WRITE;

insert  into `data_struktur_organisasi`(`struktur_cd`,`struktur_induk`,`struktur_nama`,`struktur_singkatan`,`struktur_keterangan`,`struktur_level`,`struktur_level_label`,`struktur_kode`,`mdb`,`mdb_name`,`mdd`) values ('001.00.00',NULL,'PT. TIME EXCELINDO','TE','-',0,'PERUSAHAAN','TE',NULL,NULL,NULL),('001.01.00','001.00.00','SOFTWARE DEVELOPMENT','SOFTDEV','-',1,'DEPARTEMEN','SD',NULL,NULL,NULL),('001.01.01','001.01.00','DIVISI RISET & PENGEMBANGAN TEKNOLOGI','SOFTDEV RISET','-',2,'DIVISI','SD',NULL,NULL,NULL),('001.01.02','001.01.00','DIVISI PRODUKSI','SOFTDEV PRODUKSI','-',2,'DIVISI','SD',NULL,NULL,NULL),('001.01.03','001.01.00','DIVISI MANAJEMEN','SOFTDEV MANAJEMEN','-',2,'DIVISI','SD',NULL,NULL,NULL),('001.02.00','001.00.00','INFRASTUKTUR','INFRA','-',1,'DEPARTEMEN','IS',NULL,NULL,NULL),('001.03.00','001.00.00','INTERNET SERVICES PROVIDER','ISP','-',1,'DEPARTEMEN','TC',NULL,NULL,NULL),('001.04.00','001.00.00','MARKETING','MK','-',1,'DEPARTEMEN','MK',NULL,NULL,NULL),('001.05.00','001.00.00','HUMAN RESOURCE','HRD','-',1,'DEPARTEMEN','HR',NULL,NULL,NULL),('001.06.00','001.00.00','FINANCES','FA','-',1,'DEPARTEMEN','FA',NULL,NULL,NULL),('001.07.00','001.00.00','PROJECT ADMINISTRATION','ADMIN','-',1,'DEPARTEMEN','AD',NULL,NULL,NULL),('001.09.00','001.00.00','GENERAL AFFAIRS','GA','-',1,'DEPARTEMEN','GA',NULL,NULL,NULL),('002.00.00',NULL,'BMT AL-MADINA','BMT','-',0,'PERUSAHAAN','BMT',NULL,NULL,NULL),('003.00.00',NULL,'CV. TERA TEKNO SOLUSI','TTS','-',0,'PERUSAHAAN','TS',NULL,NULL,NULL),('004.00.00',NULL,'CV. ADIDAYA PERKASA TEKNOLOGI','APT','-',0,'PERUSAHAAN','APT',NULL,NULL,NULL),('005.00.00',NULL,'PT. FORTIS SOLUTION','FORTIS','-',0,'PERUSAHAAN','FTS',NULL,NULL,NULL);

UNLOCK TABLES;

/*Table structure for table `jurnal` */

DROP TABLE IF EXISTS `jurnal`;

CREATE TABLE `jurnal` (
  `jurnal_id` varchar(15) NOT NULL,
  `tahun_index` year(4) DEFAULT NULL,
  `jenis_kode` varchar(2) DEFAULT NULL,
  `bulan_index` int(11) unsigned DEFAULT NULL,
  `jurnal_tanggal` date DEFAULT NULL,
  `jurnal_uraian` varchar(255) DEFAULT NULL,
  `jurnal_catatan` text,
  `jurnal_references_id` varchar(20) DEFAULT NULL,
  `verifikasi_status` enum('draft','verified') DEFAULT 'draft',
  `verifikasi_catatan` text,
  `verifikasi_by` varchar(10) DEFAULT NULL,
  `verifikasi_by_name` varchar(50) DEFAULT NULL,
  `verifikasi_date` datetime DEFAULT NULL,
  `create_mdb` varchar(10) DEFAULT NULL,
  `create_mdb_name` varchar(50) DEFAULT NULL,
  `create_mdd` datetime DEFAULT NULL,
  `mdb` varchar(10) DEFAULT NULL,
  `mdb_name` varchar(50) DEFAULT NULL,
  `mdd` datetime DEFAULT NULL,
  PRIMARY KEY (`jurnal_id`),
  KEY `jenis_kode` (`jenis_kode`),
  KEY `jurnal_ibfk_1` (`tahun_index`),
  CONSTRAINT `jurnal_ibfk_1` FOREIGN KEY (`tahun_index`) REFERENCES `jurnal_tahun` (`tahun_index`) ON UPDATE CASCADE,
  CONSTRAINT `jurnal_ibfk_2` FOREIGN KEY (`jenis_kode`) REFERENCES `jurnal_jenis` (`jenis_kode`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `jurnal` */

LOCK TABLES `jurnal` WRITE;

UNLOCK TABLES;

/*Table structure for table `jurnal_item` */

DROP TABLE IF EXISTS `jurnal_item`;

CREATE TABLE `jurnal_item` (
  `item_id` varchar(20) NOT NULL,
  `jurnal_id` varchar(15) DEFAULT NULL,
  `bp_kode` varchar(2) DEFAULT NULL,
  `kode_akun` varchar(8) DEFAULT NULL,
  `item_uraian` varchar(255) DEFAULT NULL,
  `item_jenis` enum('debet','kredit') DEFAULT NULL,
  `item_value` double unsigned DEFAULT NULL,
  `mdb` varchar(10) DEFAULT NULL,
  `mdb_name` varchar(50) DEFAULT NULL,
  `mdd` datetime DEFAULT NULL,
  PRIMARY KEY (`item_id`),
  KEY `jurnal_id` (`jurnal_id`),
  KEY `bp_kode` (`bp_kode`),
  CONSTRAINT `jurnal_item_ibfk_1` FOREIGN KEY (`jurnal_id`) REFERENCES `jurnal` (`jurnal_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `jurnal_item_ibfk_2` FOREIGN KEY (`bp_kode`) REFERENCES `jurnal_pembantu` (`bp_kode`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `jurnal_item` */

LOCK TABLES `jurnal_item` WRITE;

UNLOCK TABLES;

/*Table structure for table `jurnal_jenis` */

DROP TABLE IF EXISTS `jurnal_jenis`;

CREATE TABLE `jurnal_jenis` (
  `jenis_kode` varchar(2) NOT NULL,
  `jenis_jurnal` varchar(100) DEFAULT NULL,
  `mdb` varchar(10) DEFAULT NULL,
  `mdb_name` varchar(50) DEFAULT NULL,
  `mdd` datetime DEFAULT NULL,
  PRIMARY KEY (`jenis_kode`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `jurnal_jenis` */

LOCK TABLES `jurnal_jenis` WRITE;

insert  into `jurnal_jenis`(`jenis_kode`,`jenis_jurnal`,`mdb`,`mdb_name`,`mdd`) values ('00','Saldo Awal',NULL,NULL,NULL),('01','Jurnal Umum',NULL,NULL,NULL),('02','Jurnal Pengeluaran Kas',NULL,NULL,NULL),('03','Jurnal Penerimaan Kas',NULL,NULL,NULL),('11','Jurnal Penyesuaian',NULL,NULL,NULL),('99','Jurnal Penutup',NULL,NULL,NULL);

UNLOCK TABLES;

/*Table structure for table `jurnal_pembantu` */

DROP TABLE IF EXISTS `jurnal_pembantu`;

CREATE TABLE `jurnal_pembantu` (
  `bp_kode` varchar(2) NOT NULL,
  `bp_judul` varchar(100) DEFAULT NULL,
  `mdb` varchar(10) DEFAULT NULL,
  `mdb_name` varchar(50) DEFAULT NULL,
  `mdd` datetime DEFAULT NULL,
  PRIMARY KEY (`bp_kode`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `jurnal_pembantu` */

LOCK TABLES `jurnal_pembantu` WRITE;

insert  into `jurnal_pembantu`(`bp_kode`,`bp_judul`,`mdb`,`mdb_name`,`mdd`) values ('01','Buku Besar Pembantu Hutang',NULL,NULL,NULL),('02','Buku Besar Pembantu Piutang',NULL,NULL,NULL);

UNLOCK TABLES;

/*Table structure for table `jurnal_periode` */

DROP TABLE IF EXISTS `jurnal_periode`;

CREATE TABLE `jurnal_periode` (
  `periode_id` varchar(5) NOT NULL,
  `tahun_index` year(4) DEFAULT NULL,
  `periode_jenis` enum('bulan','triwulan','caturwulan','semester') DEFAULT NULL,
  `periode_label` varchar(50) DEFAULT NULL,
  `periode_awal` date DEFAULT NULL,
  `periode_akhir` date DEFAULT NULL,
  `mdb` varchar(10) DEFAULT NULL,
  `mdb_name` varchar(50) DEFAULT NULL,
  `mdd` datetime DEFAULT NULL,
  PRIMARY KEY (`periode_id`),
  KEY `tahun_index` (`tahun_index`),
  CONSTRAINT `jurnal_periode_ibfk_1` FOREIGN KEY (`tahun_index`) REFERENCES `jurnal_tahun` (`tahun_index`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `jurnal_periode` */

LOCK TABLES `jurnal_periode` WRITE;

UNLOCK TABLES;

/*Table structure for table `jurnal_tahun` */

DROP TABLE IF EXISTS `jurnal_tahun`;

CREATE TABLE `jurnal_tahun` (
  `tahun_index` year(4) NOT NULL,
  `tahun_label` varchar(50) DEFAULT NULL,
  `periode_awal` date DEFAULT NULL,
  `periode_akhir` date DEFAULT NULL,
  `tahun_default` enum('yes','no') DEFAULT 'no',
  `mdb` varchar(10) DEFAULT NULL,
  `mdb_name` varchar(50) DEFAULT NULL,
  `mdd` datetime DEFAULT NULL,
  PRIMARY KEY (`tahun_index`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `jurnal_tahun` */

LOCK TABLES `jurnal_tahun` WRITE;

insert  into `jurnal_tahun`(`tahun_index`,`tahun_label`,`periode_awal`,`periode_akhir`,`tahun_default`,`mdb`,`mdb_name`,`mdd`) values (2017,'PERIODE TAHUN 2017','2017-01-01','2017-12-31','yes','1705150001','admin','2017-11-21 11:39:14');

UNLOCK TABLES;

/*Table structure for table `laporan` */

DROP TABLE IF EXISTS `laporan`;

CREATE TABLE `laporan` (
  `laporan_id` varchar(5) NOT NULL,
  `laporan_judul` varchar(255) DEFAULT NULL,
  `laporan_default` enum('yes','no') DEFAULT 'yes',
  `laporan_status` enum('standard','custom') DEFAULT 'standard',
  `laporan_url` varchar(100) DEFAULT NULL,
  `mdb` varchar(10) DEFAULT NULL,
  `mdb_name` varchar(50) DEFAULT NULL,
  `mdd` datetime DEFAULT NULL,
  PRIMARY KEY (`laporan_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `laporan` */

LOCK TABLES `laporan` WRITE;

insert  into `laporan`(`laporan_id`,`laporan_judul`,`laporan_default`,`laporan_status`,`laporan_url`,`mdb`,`mdb_name`,`mdd`) values ('00002','Neraca','yes','standard',NULL,'1705150001','admin','2017-11-02 08:44:07'),('00003','Neraca','yes','standard',NULL,'1705150001','admin','2017-11-16 10:51:35'),('00005','Laporan Arus Kas','yes','standard',NULL,'1705150001','admin','2017-11-07 04:57:07'),('00006','Laporan Arus Kas','yes','standard',NULL,'1705150001','admin','2017-11-16 11:06:06'),('00007','Laporan Perubahan Ekuitas','yes','standard',NULL,'1705150001','admin','2017-11-16 12:57:45'),('00008','Laporan Realisasi Anggaran','yes','standard',NULL,'1705150001','admin','2017-11-16 13:04:16'),('00009','Laporan Operasional','yes','standard',NULL,'1705150001','admin','2017-11-16 13:06:53'),('00010','Laporan Perubahan Saldo Anggaran Lebih','yes','standard',NULL,'1705150001','admin','2017-11-16 13:21:30'),('00011','Laporan Operasional','yes','standard',NULL,'1705150001','admin','2017-11-16 13:45:22'),('00012','Laporan Perubahan Ekuitas','yes','standard',NULL,'1705150001','admin','2017-11-16 14:06:22'),('00013','Laporan Realisasi Anggaran','yes','standard',NULL,'1705150001','admin','2017-11-16 14:09:33'),('00014','Laporan Perubahan Saldo Anggaran Lebih','yes','standard',NULL,'1705150001','admin','2017-11-16 14:17:04'),('00016','Laporan Aktivitas','yes','standard',NULL,'1705150001','admin','2017-11-17 04:43:56'),('00017','TEST MB SOFI','yes','standard',NULL,'1705150001','admin','2017-11-17 11:02:11');

UNLOCK TABLES;

/*Table structure for table `laporan_format` */

DROP TABLE IF EXISTS `laporan_format`;

CREATE TABLE `laporan_format` (
  `format_id` varchar(10) NOT NULL,
  `laporan_id` varchar(5) DEFAULT NULL,
  `format_judul` varchar(255) DEFAULT NULL,
  `format_level` enum('1','2','3','4','5') DEFAULT NULL,
  `format_jenis` enum('debet','kredit') DEFAULT NULL,
  `format_urutan` int(11) unsigned DEFAULT NULL,
  `mdb` varchar(10) DEFAULT NULL,
  `mdb_name` varchar(50) DEFAULT NULL,
  `mdd` datetime DEFAULT NULL,
  PRIMARY KEY (`format_id`),
  KEY `laporan_id` (`laporan_id`),
  CONSTRAINT `laporan_format_ibfk_1` FOREIGN KEY (`laporan_id`) REFERENCES `laporan` (`laporan_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `laporan_format` */

LOCK TABLES `laporan_format` WRITE;

insert  into `laporan_format`(`format_id`,`laporan_id`,`format_judul`,`format_level`,`format_jenis`,`format_urutan`,`mdb`,`mdb_name`,`mdd`) values ('0000200001','00002','ASET','1','debet',1,'1705150001','admin','2017-11-07 09:52:49'),('0000200002','00002','ASET LANCAR','2','debet',2,'1705150001','admin','2017-11-07 09:54:03'),('0000200003','00002','Kas dan Setara Kas','3','debet',3,'1705150001','admin','2017-11-07 09:55:37'),('0000200004','00002','Kas di Bendahara Pengeluaran','4','debet',4,'1705150001','admin','2017-11-07 09:57:18'),('0000200005','00002','kas di Bendahara Penerimaan','4','debet',5,'1705150001','admin','2017-11-07 09:57:44'),('0000200006','00002','Deposito Sampai dengan 3 Bulan','4','debet',6,'1705150001','admin','2017-11-07 09:58:23'),('0000200007','00002','Investasi Jangka Pendek','3','debet',7,'1705150001','admin','2017-11-08 01:49:40'),('0000200008','00002','Deposito 3 s.d 12 Bulan','4','debet',8,'1705150001','admin','2017-11-08 01:49:59'),('0000200009','00002','Piutang','3','debet',9,'1705150001','admin','2017-11-08 01:51:00'),('0000200010','00002','Piutang Layanan Kesehatan','4','debet',10,'1705150001','admin','2017-11-08 01:51:20'),('0000200011','00002','Piutang Lain-Lain','4','debet',11,'1705150001','admin','2017-11-08 01:51:38'),('0000200012','00002','Penyisihan Piutang Usaha Tak Tertagih','4','debet',12,'1705150001','admin','2017-11-08 01:52:03'),('0000200013','00002','Persediaan','3','debet',13,'1705150001','admin','2017-11-08 01:55:42'),('0000200014','00002','Inventaris Kantor dan Persediaan Rumah Tangga','4','debet',14,'1705150001','admin','2017-11-08 01:56:13'),('0000200015','00002','ASET NON LANCAR','2','debet',16,'1705150001','admin','2017-11-08 02:00:46'),('0000200016','00002','Aset Tetap','3','debet',17,'1705150001','admin','2017-11-08 02:01:31'),('0000200017','00002','Tanah','4','debet',18,'1705150001','admin','2017-11-08 02:02:06'),('0000200018','00002','Gedung dan Banggunan','4','debet',19,'1705150001','admin','2017-11-08 02:02:35'),('0000200019','00002','Peralatan dan Mesin','4','debet',20,'1705150001','admin','2017-11-08 02:02:59'),('0000200020','00002','Mobil layanan kesehatan','4','debet',21,'1705150001','admin','2017-11-08 02:03:30'),('0000200021','00002','Jalan dan Jembatan','4','debet',22,'1705150001','admin','2017-11-08 02:03:56'),('0000200022','00002','Bangunan Air Irigasi','4','debet',23,'1705150001','admin','2017-11-08 02:04:20'),('0000200023','00002','Jaringan','4','debet',24,'1705150001','admin','2017-11-08 02:04:49'),('0000200024','00002','Aset Tetap Lainnya','3','debet',25,'1705150001','admin','2017-11-08 02:05:21'),('0000200025','00002','Konstruksi Dalam Pengerjaan','4','debet',26,'1705150001','admin','2017-11-08 02:05:45'),('0000200026','00002','Akumulasi Penyusutan Aset Tetap','3','debet',27,'1705150001','admin','2017-11-08 03:29:49'),('0000200027','00002','Akumulasi Penyusutan Gedung dan Bangunan','4','debet',28,'1705150001','admin','2017-11-08 03:30:09'),('0000200028','00002','Akumulasi Penyusutan Peralatan dan Mesin','4','debet',29,'1705150001','admin','2017-11-08 03:30:28'),('0000200029','00002','Akumulasi Penyusutan Jalan, Irigasi dan Jaringan','4','debet',30,'1705150001','admin','2017-11-08 03:30:51'),('0000200030','00002','Akumulasi Penyusutan Irigasi','4','debet',31,'1705150001','admin','2017-11-08 03:31:14'),('0000200031','00002','Akumulasi Penyusutan Jaringan','4','debet',32,'1705150001','admin','2017-11-08 03:31:31'),('0000200032','00002','Akumulasi Penyusutan Aset Tetap Lainnya','4','debet',33,'1705150001','admin','2017-11-08 03:31:54'),('0000200033','00002','Aset Tak Berwujud','3','debet',34,'1705150001','admin','2017-11-08 03:35:52'),('0000200034','00002','Aset Tetap yang Tidak Digunakan','3','debet',35,'1705150001','admin','2017-11-08 03:36:13'),('0000200035','00002','Akumulasi Amortisasi Aset Tak Berwujud','3','debet',36,'1705150001','admin','2017-11-08 03:36:37'),('0000200036','00002','Akumulasi Penyusutan Aset Tetap yang Tidak Digunakan','3','debet',37,'1705150001','admin','2017-11-08 03:37:00'),('0000200037','00002','Aset Lainnya','3','debet',38,'1705150001','admin','2017-11-08 03:40:52'),('0000200038','00002','Aset Kerja Sama Operasi','4','debet',39,'1705150001','admin','2017-11-08 03:41:11'),('0000200039','00002','Akumulasi Penyusutan Aset Kerja Sama Operasi','4','debet',40,'1705150001','admin','2017-11-08 03:41:35'),('0000200040','00002','Kewajiban','1','debet',43,'1705150001','admin','2017-11-17 02:53:09'),('0000200041','00002','Kewajiban Jangka Pendek','2','debet',44,'1705150001','admin','2017-11-17 02:47:51'),('0000200042','00002','Utang Usaha Jangka Pendek','3','debet',45,'1705150001','admin','2017-11-17 02:48:12'),('0000200043','00002','Utang Pajak','3','debet',46,'1705150001','admin','2017-11-17 02:48:24'),('0000200044','00002','Beban Yang Masih Harus Dibayar','3','debet',47,'1705150001','admin','2017-11-17 02:48:40'),('0000200045','00002','Beban Utilitas Layanan Kesehatan Yang Masih Harus Dibayar','3','debet',48,'1705150001','admin','2017-11-17 02:49:09'),('0000200046','00002','Pendapatan Diterima Dimuka','3','debet',49,'1705150001','admin','2017-11-17 02:49:31'),('0000200047','00002','Bagian Lancar Utang Jangka Panjang','3','debet',50,'1705150001','admin','2017-11-17 02:49:59'),('0000200048','00002','Utang pada Kas Umum Negara','3','debet',51,'1705150001','admin','2017-11-17 02:50:20'),('0000200049','00002','Ekuitas','1','debet',53,'1705150001','admin','2017-11-17 02:53:24'),('0000200050','00002','Ekuitas Tidak Terikat','2','debet',54,'1705150001','admin','2017-11-17 02:53:57'),('0000200051','00002','Ekuitas Awal','3','debet',55,'1705150001','admin','2017-11-17 02:54:09'),('0000200052','00002','Ekuitas Donasi','3','debet',56,'1705150001','admin','2017-11-17 02:54:22'),('0000200053','00002','Surplus / Defisit Periode Lalu','3','debet',57,'1705150001','admin','2017-11-17 02:54:41'),('0000200054','00002','Surplus Periode Berjalan','3','debet',58,'1705150001','admin','2017-11-17 02:55:01'),('0000200055','00002','Penyesuaian atas Ekuitas','3','debet',59,'1705150001','admin','2017-11-17 02:55:20'),('0000200056','00002','Selisih Kenaikan Nilai Aset Tetap','3','debet',60,'1705150001','admin','2017-11-17 02:55:43'),('0000200057','00002','Ekuitas Tetap','2','debet',61,'1705150001','admin','2017-11-17 02:55:55'),('0000200058','00002','Ekuitas Terikat Temporer','3','debet',62,'1705150001','admin','2017-11-17 02:56:16'),('0000200059','00002','Ekuitas Terikat Permanen','3','debet',63,'1705150001','admin','2017-11-17 02:56:32'),('0000200060','00002','JUMLAH ASET LANCAR','1','debet',15,'1705150001','admin','2017-11-22 04:39:54'),('0000200061','00002','JUMLAH ASET NON LANCAR','1','debet',41,'1705150001','admin','2017-11-22 04:46:41'),('0000200062','00002','JUMLAH ASET','1','debet',42,'1705150001','admin','2017-11-22 04:49:20'),('0000200063','00002','JUMLAH KEWAJIBAN','1','debet',52,'1705150001','admin','2017-11-22 04:52:56'),('0000200064','00002','JUMLAH EKUITAS','1','debet',64,'1705150001','admin','2017-11-22 04:57:58'),('0000200065','00002','JUMLAH KEWAJIBAN DAN EKUITAS','1','debet',65,'1705150001','admin','2017-11-22 04:58:17'),('0000300001','00003','ASET','1','debet',1,'1705150001','admin','2017-11-14 05:05:21'),('0000300002','00003','ASET LANCAR','2','debet',2,'1705150001','admin','2017-11-14 05:05:49'),('0000300004','00003','Kas di Bendahara Pengeluaran','3','debet',4,'1705150001','admin','2017-11-14 05:07:14'),('0000300007','00003','Kas lainnya dan Setara Kas','3','debet',7,'1705150001','admin','2017-11-16 12:32:03'),('0000300008','00003','Kas pada Badan Layanan Umum','3','debet',8,'1705150001','admin','2017-11-16 12:32:21'),('0000300009','00003','Investasi Jangka Pendek-BLU','3','debet',9,'1705150001','admin','2017-11-16 12:32:45'),('0000300010','00003','Piutang dari Kegiatan Operasional BLU','3','debet',10,'1705150001','admin','2017-11-16 12:33:12'),('0000300011','00003','Piutang dari Kegiatan Non Operasional BLU','3','debet',11,'1705150001','admin','2017-11-16 12:33:39'),('0000300012','00003','Penyisihan Piutang Tidak Tertagih - Piutang dari Kegiatan Operasional BLU','3','debet',12,'1705150001','admin','2017-11-16 12:34:22'),('0000300013','00003','Penyisihan Piutang Tidak Tertagih - Piutang dari Kegiatan Non Operasional BLU','3','debet',13,'1705150001','admin','2017-11-16 12:35:10'),('0000300014','00003','Persediaan','3','debet',14,'1705150001','admin','2017-11-16 12:35:23'),('0000300015','00003','Investasi Jangka Panjang','3','debet',15,'1705150001','admin','2017-11-16 12:37:26'),('0000300016','00003','Aset Tetap','2','debet',16,'1705150001','admin','2017-11-16 12:37:46'),('0000300017','00003','Tanah','3','debet',17,'1705150001','admin','2017-11-16 12:37:59'),('0000300018','00003','Peralatan dan Mesin','3','debet',18,'1705150001','admin','2017-11-16 12:38:13'),('0000300019','00003','Gedung dan Bangunan','3','debet',19,'1705150001','admin','2017-11-16 12:38:30'),('0000300020','00003','Jalan, Irigasi, dan Jaringan','3','debet',20,'1705150001','admin','2017-11-16 12:38:52'),('0000300021','00003','Akumulasi Penyusutan - Peralatan dan Mesin','3','debet',21,'1705150001','admin','2017-11-16 12:39:18'),('0000300022','00003','Akumulasi Penyusutan - Gedung dan Bangunan','3','debet',22,'1705150001','admin','2017-11-16 12:39:40'),('0000300023','00003','Akumulasi Penyusutan - Jalan, Irigasi, dan Jaringan','3','debet',23,'1705150001','admin','2017-11-16 12:40:03'),('0000300024','00003','Piutang Jangka Pendek','3','debet',24,'1705150001','admin','2017-11-16 12:40:21'),('0000300025','00003','Aset Lainnya','2','debet',25,'1705150001','admin','2017-11-16 12:40:34'),('0000300026','00003','Kewajiban','1','debet',26,'1705150001','admin','2017-11-16 12:45:26'),('0000300027','00003','Kewajiban Jangka Pendek','2','debet',27,'1705150001','admin','2017-11-16 12:45:49'),('0000300028','00003','Uang Muka dari KPPN','3','debet',28,'1705150001','admin','2017-11-16 12:46:03'),('0000300029','00003','Utang Pajak','3','debet',29,'1705150001','admin','2017-11-16 12:46:18'),('0000300030','00003','Kewajiban Jangka Panjang','2','debet',30,'1705150001','admin','2017-11-16 12:49:43'),('0000300031','00003','Ekuitas','1','debet',31,'1705150001','admin','2017-11-16 12:50:20'),('0000300032','00003','Ekuitas','2','debet',32,'1705150001','admin','2017-11-17 07:42:44'),('0000500001','00005','ARUS KAS DARI AKTIVITAS OPERASI','1','debet',1,'1705150001','admin','2017-11-16 11:08:02'),('0000500002','00005','Arus Masuk Kas','2','debet',2,'1705150001','admin','2017-11-16 11:08:35'),('0000500003','00005','Pendapatan Usaha Dari Jasa Layanan Masyarakat','3','debet',3,'1705150001','admin','2017-11-17 03:46:20'),('0000500004','00005','Pendapatan dari Jasa Usaha Layanan Lainnya','3','debet',4,'1705150001','admin','2017-11-17 03:46:54'),('0000500005','00005','Pendapatan RM','3','debet',5,'1705150001','admin','2017-11-17 03:47:35'),('0000500006','00005','Hibah','3','debet',6,'1705150001','admin','2017-11-17 05:11:26'),('0000500007','00005','Arus Keluar Kas','2','debet',7,'1705150001','admin','2017-11-17 05:11:45'),('0000500008','00005','Beban Layanan','3','debet',8,'1705150001','admin','2017-11-17 05:11:57'),('0000500009','00005','Beban Adm &amp; Umum','3','debet',9,'1705150001','admin','2017-11-17 05:12:14'),('0000500010','00005','Beban Lainnya','3','debet',10,'1705150001','admin','2017-11-17 05:12:27'),('0000500011','00005','Pembelian Persediaan','3','debet',11,'1705150001','admin','2017-11-17 05:13:11'),('0000500012','00005','Arus Kas dari Aktivitas Investasi','1','debet',12,'1705150001','admin','2017-11-17 05:13:57'),('0000500013','00005','Arus Masuk Kas','2','debet',13,'1705150001','admin','2017-11-17 05:14:08'),('0000500014','00005','Keuntungan Surat Berharga','3','debet',14,'1705150001','admin','2017-11-17 05:14:24'),('0000500015','00005','Keuntungan Penjualan Aset Tetap','3','debet',15,'1705150001','admin','2017-11-17 05:14:41'),('0000500016','00005','Arus Keluar Kas','2','debet',16,'1705150001','admin','2017-11-17 05:14:56'),('0000500017','00005','Pembelian Aset Tetap','3','debet',17,'1705150001','admin','2017-11-17 05:15:13'),('0000500018','00005','Kerugian Penjualan Aset Tetap','3','debet',18,'1705150001','admin','2017-11-17 05:15:31'),('0000500019','00005','Arus Kas dari Aktivitas Pendanaan','1','debet',19,'1705150001','admin','2017-11-17 05:15:46'),('0000500020','00005','Arus Masuk Kas','2','debet',20,'1705150001','admin','2017-11-17 05:15:58'),('0000500021','00005','Penerimaan Kas APBN','3','debet',21,'1705150001','admin','2017-11-17 05:16:14'),('0000500022','00005','Penerimaan Kas Sumber Lainnya','3','debet',22,'1705150001','admin','2017-11-17 05:16:29'),('0000500023','00005','Penerimaan Kas Dari Modal Donasi','3','debet',23,'1705150001','admin','2017-11-17 05:16:47'),('0000500024','00005','Penerimaan Kas Dari Pinjaman Jangka Panjang','3','debet',24,'1705150001','admin','2017-11-17 05:17:21'),('0000500025','00005','Arus Keluar Kas','2','debet',25,'1705150001','admin','2017-11-17 05:17:40'),('0000500026','00005','Pengembalian kepada KUN','3','debet',26,'1705150001','admin','2017-11-17 05:17:56'),('0000500027','00005','Pembayaran Pokok Pinjaman','3','debet',27,'1705150001','admin','2017-11-17 05:18:13'),('0000500028','00005','Pemberian Pinjaman','3','debet',28,'1705150001','admin','2017-11-17 05:18:26'),('0000600001','00006','Arus Kas dari Aktivitas Operasi','1','debet',1,'1705150001','admin','2017-11-16 13:27:46'),('0000600002','00006','Arus Masuk Kas','2','debet',2,'1705150001','admin','2017-11-16 13:27:59'),('0000600003','00006','Pendapatan dari Alokasi APBN','3','debet',3,'1705150001','admin','2017-11-16 13:29:49'),('0000600004','00006','Pendapatan dari Jasa Layanan kepada Masyarakat','3','debet',4,'1705150001','admin','2017-11-16 13:30:13'),('0000600005','00006','Pendapatan dari Jasa Layanan Kepada Entitas Lain','3','debet',5,'1705150001','admin','2017-11-16 13:31:13'),('0000600006','00006','Pendapatan dari Hasil Kerja Sama','3','debet',6,'1705150001','admin','2017-11-16 13:31:40'),('0000600007','00006','Pendapatan dari Hibah','3','debet',7,'1705150001','admin','2017-11-16 13:31:52'),('0000600008','00006','Pendapatan Usaha Lainnya','3','debet',8,'1705150001','admin','2017-11-16 13:32:05'),('0000600009','00006','Arus Keluar Kas','2','debet',9,'1705150001','admin','2017-11-16 13:32:23'),('0000600010','00006','Pembayaran Pegawai','3','debet',10,'1705150001','admin','2017-11-16 13:32:45'),('0000600011','00006','Pembayaran Jasa','3','debet',11,'1705150001','admin','2017-11-16 13:33:00'),('0000600012','00006','Pembayaran Pemeliharaan','3','debet',12,'1705150001','admin','2017-11-16 13:33:12'),('0000600013','00006','Pembayaran Barang dan Jasa Kekhususan BLU','3','debet',13,'1705150001','admin','2017-11-16 13:33:34'),('0000600014','00006','Pembayaran Perjalanan Dinas','3','debet',14,'1705150001','admin','2017-11-16 13:34:12'),('0000600015','00006','Pembayaran Bantuan Sosial','3','debet',15,'1705150001','admin','2017-11-16 13:34:25'),('0000600016','00006','Arus Kas Bersih Dari Aktivitas Operasi','1','debet',16,NULL,NULL,NULL),('0000600017','00006','Arus Masuk Kas','2','debet',17,'1705150001','admin','2017-11-16 13:35:15'),('0000600018','00006','Penjualan atas Tanah','3','debet',18,'1705150001','admin','2017-11-16 13:35:29'),('0000600019','00006','Penjualan atas Peralatan dan Mesin','3','debet',19,'1705150001','admin','2017-11-16 13:35:49'),('0000600020','00006','Penjualan atas Gedung dan Bangunan','3','debet',20,'1705150001','admin','2017-11-16 13:36:07'),('0000600021','00006','Penjualan atas Jalan, Irigasi, dan Jaringan','3','debet',21,'1705150001','admin','2017-11-16 13:36:37'),('0000600022','00006','Penerimaan Kembali Investasi yang Berasal dari APBN (BA BUN Investasi)','3','debet',22,'1705150001','admin','2017-12-18 10:57:46'),('0000600025','00006','Arus Keluar Kas','2','debet',25,'1705150001','admin','2017-11-16 13:39:45'),('0000600026','00006','Perolehan atas Tanah','3','debet',26,'1705150001','admin','2017-12-18 10:59:18'),('0000600051','00006','Pendapatan dari Pengembalian Belanja BLU TAYL','3','debet',8,'1705150001','admin','2017-12-18 10:48:00'),('0000600052','00006','Pendapatan PNBP Umum','3','debet',8,'1705150001','admin','2017-12-18 10:48:16'),('0000600053','00006','Pembayaran Barang','3','debet',10,'1705150001','admin','2017-12-18 10:49:27'),('0000600054','00006','Pembayaran Barang Menghasilkan Persediaan','3','debet',12,NULL,NULL,NULL),('0000600055','00006','Pembayaran Barang untuk Dijual/ Diserahkan kepada Masyarakat','3','debet',15,NULL,NULL,NULL),('0000600056','00006','Pembayaran Pengembalian Pendapatan BLU TAYL','3','debet',15,NULL,NULL,NULL),('0000600057','00006','Penyetoran PNBP ke Kas Negara','3','debet',15,NULL,NULL,NULL),('0000600058','00006','Arus Kas dari Aktivitas Investasi','1','debet',16,'1705150001','admin','2017-11-16 13:35:01'),('0000700001','00007','Ekuitas Awal','1','debet',1,'1705150001','admin','2017-11-16 12:58:14'),('0000700002','00007','Surplus / defisit-LO','1','debet',2,'1705150001','admin','2017-11-16 12:58:42'),('0000700003','00007','Koreksi Yang Menambah/ Mengurangi Ekuitas Yang Antara Lain Berasal Dari Dampak Kumulatif / Perubahan Kebijakan Akuntansi / Kesalahan Mendasar','1','debet',3,'1705150001','admin','2017-12-20 02:44:44'),('0000700005','00007','Koreksi Nilai Persediaan','2','debet',5,'1705150001','admin','2017-11-16 12:59:55'),('0000700006','00007','Selisih Revaluasi Aset Tetap','2','debet',6,'1705150001','admin','2017-11-16 13:00:19'),('0000700007','00007','Koreksi Nilai Aset Tetap Non Revaluasi','2','debet',7,'1705150001','admin','2017-11-16 13:00:39'),('0000700008','00007','Koreksi Lain - lain','2','debet',8,'1705150001','admin','2017-12-20 02:49:50'),('0000700009','00007','Transaksi Antar Entitas','1','debet',9,'1705150001','admin','2017-11-16 13:01:44'),('0000700010','00007','Kenaikan / Penurunan Ekuitas','1','debet',10,'1705150001','admin','2017-11-16 13:02:03'),('0000700011','00007','Ekuitas Akhir','1','debet',11,'1705150001','admin','2017-11-16 13:02:14'),('0000700012','00007','Penyesuaian Nilai Aset','2','debet',5,NULL,NULL,NULL),('0000800001','00008','Pendapatan Negara dan Hibah','1','debet',1,'1705150001','admin','2017-12-18 09:59:46'),('0000800002','00008','Penerimaan Dalam Negeri','2','debet',2,'1705150001','admin','2017-12-18 10:00:08'),('0000800003','00008','Penerimaan Perpajakan','3','debet',3,'1705150001','admin','2017-12-18 10:00:30'),('0000800004','00008','Penerimaan Negara Bukan Pajak','3','debet',4,'1705150001','admin','2017-12-18 10:01:01'),('0000800005','00008','Hibah','2','debet',5,'1705150001','admin','2017-12-18 10:01:17'),('0000800007','00008','Belanja','1','debet',7,'1705150001','admin','2017-11-16 13:09:25'),('0000800009','00008','Belanja Pegawai','2','debet',9,'1705150001','admin','2017-12-18 10:02:01'),('0000800010','00008','Belanja Barang','2','debet',10,'1705150001','admin','2017-12-18 10:02:16'),('0000800013','00008','Belanja Modal','2','debet',13,'1705150001','admin','2017-11-16 13:10:37'),('0000800014','00008','Pembayaran Bunga Hutang','2','debet',14,'1705150001','admin','2017-12-18 10:03:13'),('0000800015','00008','Subsidi','2','debet',15,'1705150001','admin','2017-12-18 10:03:31'),('0000800016','00008','Hibah','2','debet',16,'1705150001','admin','2017-12-18 10:03:48'),('0000800017','00008','Bantuan Sosial','2','debet',17,'1705150001','admin','2017-12-18 10:04:06'),('0000800018','00008','Belanja Lain-lain','2','debet',18,'1705150001','admin','2017-12-18 10:04:28'),('0000800019','00008','Surplus/ (Defisit)','1','debet',19,'1705150001','admin','2017-12-18 10:04:55'),('0000800021','00008','Surplus/ (Defisit) (A-B)','2','debet',21,'1705150001','admin','2017-12-18 10:05:24'),('0000800022','00008','SILPA / (SIKPA)','1','debet',22,'1705150001','admin','2017-12-18 10:06:06'),('0000900001','00009','Kegiatan Operasional','1','kredit',1,'1705150001','admin','2017-11-16 13:09:12'),('0000900002','00009','Pendapatan Operasional','2','kredit',2,'1705150001','admin','2017-12-18 10:08:47'),('0000900003','00009','Pendapatan Alokasi APBN','3','kredit',3,'1705150001','admin','2017-12-18 10:09:23'),('0000900004','00009','Pendapatan Jasa Layanan dari Masyarakat','3','kredit',4,'1705150001','admin','2017-12-18 10:09:56'),('0000900005','00009','Pendapatan Jasa Layanan dari Entitas Lain','3','kredit',5,'1705150001','admin','2017-12-18 10:10:28'),('0000900006','00009','Pendapatan Hibah BLU','3','kredit',6,'1705150001','admin','2017-12-18 10:10:48'),('0000900007','00009','Pendapatan Hasil Kerja Sama BLU','3','kredit',7,'1705150001','admin','2017-12-18 10:11:17'),('0000900008','00009','Pendapatan BLU Lainnya','3','kredit',8,'1705150001','admin','2017-12-18 10:11:37'),('0000900009','00009','Beban Operasional','2','debet',9,'1705150001','admin','2017-12-18 10:11:52'),('0000900010','00009','Beban Pegawai','3','debet',10,'1705150001','admin','2017-11-16 13:17:39'),('0000900011','00009','Beban Persediaan','3','debet',11,'1705150001','admin','2017-11-16 13:17:50'),('0000900012','00009','Beban Barang dan Jasa','3','debet',12,'1705150001','admin','2017-12-18 10:12:13'),('0000900013','00009','Beban Pemeliharaan','3','debet',13,'1705150001','admin','2017-11-16 13:18:14'),('0000900014','00009','Beban Perjalanan Dinas','3','debet',14,'1705150001','admin','2017-12-18 10:12:40'),('0000900015','00009','Beban Barang untuk Dijual/ Diserahkan kepada Masyarakat','3','debet',15,'1705150001','admin','2017-12-18 10:13:19'),('0000900016','00009','Beban Bantuan Sosial','3','debet',16,'1705150001','admin','2017-12-18 10:13:37'),('0000900017','00009','Beban Penyusutan dan Amortisasi','3','debet',17,'1705150001','admin','2017-12-18 10:14:02'),('0000900018','00009','Beban Penyisihan Piutang Tak Tertagih','3','debet',18,'1705150001','admin','2017-12-18 10:16:16'),('0000900019','00009','Surplus / (Defisit) dari Kegiatan Operasional','1','debet',19,'1705150001','admin','2017-12-18 10:17:46'),('0000900020','00009','Kegiatan Non Operasional','1','debet',20,'1705150001','admin','2017-12-18 10:18:17'),('0000900021','00009','Surplus / (Defisit) Penjualan Aset Non Lancar','2','debet',21,'1705150001','admin','2017-12-18 10:19:14'),('0000900022','00009','Pendapatan Pelepasan Aset Non Lancar','3','kredit',22,'1705150001','admin','2017-12-18 10:20:05'),('0000900023','00009','Beban Pelepasan Aset Non Lancar','3','debet',23,'1705150001','admin','2017-12-18 10:20:28'),('0000900024','00009','Surplus / (Defisit) dari Kegiatan Non Operasional Lainnya','2','debet',24,'1705150001','admin','2017-12-18 10:21:26'),('0000900025','00009','Pendapatan Kegiatan Non Operasional Lainnya','3','kredit',25,'1705150001','admin','2017-12-18 10:25:12'),('0000900026','00009','Beban Kegiatan Non Operasional Lainnya','3','debet',26,'1705150001','admin','2017-12-18 10:25:43'),('0000900027','00009','Surplus/ (Defisit) dari Kegiatan Non Operasional','1','debet',27,NULL,NULL,NULL),('0000900028','00009','Surplus/ (Defisit) - LO','1','debet',28,NULL,NULL,NULL),('0001000001','00010','Saldo Anggaran Lebih Awal (SAL Awal)','1','debet',1,'1705150001','admin','2017-12-18 10:33:49'),('0001000002','00010','Penggunaan SAL','1','debet',2,'1705150001','admin','2017-11-16 13:22:23'),('0001000004','00010','Sisa Lebih / Kurang Pembiayaan Anggaran (SiLPA/SiKPA)','1','debet',4,'1705150001','admin','2017-11-16 13:23:03'),('0001000005','00010','Penyesuaian SiLPA/ SiKPA','1','debet',5,'1705150001','admin','2017-11-16 13:23:43'),('0001000006','00010','Penyesuaian Transaksi BLU dengan BUN','2','debet',6,'1705150001','admin','2017-11-16 13:24:32'),('0001000007','00010','Pendapatan Alokasi APBN','3','kredit',7,'1705150001','admin','2017-11-16 13:24:51'),('0001000008','00010','Penyetoran PNBP ke Kas Negara','3','debet',8,NULL,NULL,NULL),('0001000009','00010','Penyetoran Surplus BLU ke Kas Negara','3','debet',9,'1705150001','admin','2017-12-18 10:37:22'),('0001000010','00010','Pengembalian Pendapatan BLU TAYL','2','debet',10,'1705150001','admin','2017-12-18 10:37:46'),('0001000011','00010','Sisa Lebih/ Kurang Pembiayaan Anggaran (SiLPA/ SiKPA) Setelah Penyesuaian','1','debet',11,'1705150001','admin','2017-12-18 10:38:30'),('0001000012','00010','Koreksi Kesalahan Pembukuan Tahun Sebelumnya','1','debet',12,'1705150001','admin','2017-12-18 10:38:57'),('0001000013','00010','Lain-lain','1','debet',13,'1705150001','admin','2017-12-18 10:39:08'),('0001000014','00010','Saldo Anggaran Lebih Akhir','1','debet',14,'1705150001','admin','2017-12-18 10:39:27'),('0001100001','00011','Kegiatan Operasional','1','debet',1,'1705150001','admin','2017-11-16 13:45:42'),('0001100002','00011','Pendapatan','2','debet',2,'1705150001','admin','2017-11-16 13:45:50'),('0001100003','00011','Pendapatan Jasa Layanan dari Masyarakat','3','debet',3,'1705150001','admin','2017-11-16 13:46:11'),('0001100006','00011','Pendapatan Hibah BLU','3','debet',6,'1705150001','admin','2017-12-18 08:36:53'),('0001100008','00011','Pendapatan APBN / APBD','3','debet',8,'1705150001','admin','2017-11-16 13:47:16'),('0001100009','00011','Beban','2','debet',9,'1705150001','admin','2017-11-16 13:47:25'),('0001100010','00011','Beban Pegawai','3','debet',10,'1705150001','admin','2017-11-16 13:47:53'),('0001100012','00011','Beban Barang dan Jasa','3','debet',12,'1705150001','admin','2017-12-18 08:42:56'),('0001100013','00011','Beban Pemeliharaan','3','debet',13,'1705150001','admin','2017-11-16 13:48:48'),('0001100015','00011','Beban Perjalanan Dinas','3','debet',15,'1705150001','admin','2017-11-16 13:49:12'),('0001100018','00011','Kegiatan Non Operasional','1','debet',18,'1705150001','admin','2017-11-16 13:49:54'),('0001100019','00011','Surplus / Defisit dari Kegiatan Non Operasional','3','debet',19,'1705150001','admin','2017-12-18 08:49:30'),('0001100021','00011','Surplus / Defisit sebelum POS Luar Biasa','3','debet',21,'1705150001','admin','2017-12-18 08:50:29'),('0001100022','00011','Pos Luar Biasa','1','debet',22,'1705150001','admin','2017-11-16 13:50:40'),('0001100023','00011','Pendapatan Luar Biasa','3','debet',23,'1705150001','admin','2017-11-16 13:50:55'),('0001100024','00011','Beban Luar Biasa','3','debet',24,'1705150001','admin','2017-11-16 13:51:08'),('0001200001','00012','Ekuitas Awal','1','debet',1,'1705150001','admin','2017-11-16 14:06:45'),('0001200002','00012','Surplus / defisit-LO','1','debet',2,'1705150001','admin','2017-11-16 14:06:56'),('0001200003','00012','Koreksi Yang Menambah / Mengurangi Ekuitas','1','debet',3,'1705150001','admin','2017-11-16 14:07:13'),('0001200004','00012','Penyesuaian Nilai Aset','2','debet',4,'1705150001','admin','2017-11-16 14:07:35'),('0001200005','00012','Koreksi Nilai Persediaan','2','debet',5,'1705150001','admin','2017-11-16 14:07:50'),('0001200006','00012','Selisih Revaluasi Aset Tetap','2','debet',6,'1705150001','admin','2017-11-16 14:08:08'),('0001200007','00012','Koreksi Nilai Aset Tetap Non Revaluasi','2','debet',7,'1705150001','admin','2017-11-16 14:08:28'),('0001200008','00012','Lain - lain','2','debet',8,'1705150001','admin','2017-11-16 14:08:36'),('0001200009','00012','Transaksi Antar Entitas','1','debet',9,'1705150001','admin','2017-11-16 14:08:46'),('0001200010','00012','Kenaikan / Penurunan Ekuitas','1','debet',10,'1705150001','admin','2017-11-16 14:08:56'),('0001200011','00012','Ekuitas Akhir','1','debet',11,'1705150001','admin','2017-11-16 14:09:06'),('0001300001','00013','Pendapatan Negara dan Hibah','1','debet',1,'1705150001','admin','2017-12-18 09:00:33'),('0001300002','00013','Pendapatan Penerimaan Negara Bukan Pajak','2','debet',2,'1705150001','admin','2017-12-18 09:01:12'),('0001300003','00013','Pendapatan Badan Layanan Umum','3','debet',3,'1705150001','admin','2017-12-18 09:01:47'),('0001300004','00013','Pendapatan Jasa Layanan Umum','4','debet',4,'1705150001','admin','2017-12-18 09:02:10'),('0001300005','00013','Pendapatan Hibah Terikat - Uang','4','debet',5,'1705150001','admin','2017-12-18 09:02:39'),('0001300007','00013','Belanja Negara','1','debet',7,'1705150001','admin','2017-12-18 09:03:19'),('0001300008','00013','Belanja Pegawai','2','debet',8,'1705150001','admin','2017-12-18 09:03:48'),('0001300009','00013','Belanja Barang dan Jasa','2','debet',9,'1705150001','admin','2017-12-18 09:04:14'),('0001300010','00013','Belanja Badan Layanan Umum','3','debet',10,'1705150001','admin','2017-12-18 09:04:44'),('0001300011','00013','Belanja Gaji dan Tunjangan','4','debet',11,'1705150001','admin','2017-12-18 09:05:12'),('0001300012','00013','Belanja Jasa','4','debet',12,'1705150001','admin','2017-12-18 09:05:32'),('0001300013','00013','Belanja Pemeliharaan','4','debet',13,'1705150001','admin','2017-12-18 09:06:13'),('0001300014','00013','Belanja Perjalanan','4','debet',14,'1705150001','admin','2017-12-18 09:06:37'),('0001300015','00013','Belanja Modal','2','debet',15,'1705150001','admin','2017-12-18 09:07:11'),('0001300016','00013','Belanja Modal Badan Layanan Umum','3','debet',16,'1705150001','admin','2017-12-18 09:08:15'),('0001300017','00013','Belanja Modal Peralatan dan Mesin BLU','4','debet',17,'1705150001','admin','2017-12-18 09:08:59'),('0001300019','00013','PEMBIAYAAN','1','debet',19,'1705150001','admin','2017-11-16 14:14:22'),('0001300020','00013','Penerimaan Pembiayaan','2','debet',20,'1705150001','admin','2017-12-18 09:10:28'),('0001300025','00013','Pengeluaran Pembiayaan','2','debet',25,'1705150001','admin','2017-12-18 09:29:24'),('0001400001','00014','Saldo Anggaran Lebih (SAL) BLU Awal','1','debet',1,'1705150001','admin','2017-11-17 04:10:29'),('0001400002','00014','Penggunaan SAL','1','debet',2,'1705150001','admin','2017-11-17 04:10:53'),('0001400003','00014','SISA LEBIH / KURANG PEMBIAYAAN ANGGARAN (SILPA/SIKPA) (B)','1','debet',3,'1705150001','admin','2017-11-17 04:09:31'),('0001400007','00014','Penyesuaian (SiLPA/SiKPA) :','1','debet',7,'1705150001','admin','2017-11-17 04:03:17'),('0001400008','00014','Penyesuaian Transaksi BLU dengan BUN (C)','2','debet',8,'1705150001','admin','2017-11-17 04:07:10'),('0001400009','00014','Pendapatan alokasi APBN','3','debet',9,'1705150001','admin','2017-11-17 04:07:35'),('0001400010','00014','Penyetoran PNBP ke Kas Negara','3','debet',10,'1705150001','admin','2017-11-17 04:08:08'),('0001400011','00014','Penyetoran surplus BLU ke Kas Negara','3','debet',11,'1705150001','admin','2017-11-17 04:08:35'),('0001400012','00014','Pengembalian pendapatan BLU TAYL (D)','2','debet',12,'1705150001','admin','2017-11-17 04:09:01'),('0001400013','00014','Sisa Lebih/Kurang Pembiayaan Anggaran (SiLPA/SiKPA) (E = B+C+D)','1','debet',13,'1705150001','admin','2017-11-17 04:13:39'),('0001400014','00014','Koreksi Kesalahan Pembukuan Tahun Sebelumnya (G)','1','debet',14,'1705150001','admin','2017-11-17 04:14:53'),('0001400015','00014','Lain - Lain (H)','1','debet',15,'1705150001','admin','2017-11-17 04:15:13'),('0001600001','00016','PENDAPATAN  USAHA DARI JASA LAYANAN','1','debet',1,'1705150001','admin','2017-11-17 04:45:12'),('0001600002','00016','Pendapatan Usaha Dari Jasa Layanan Kesehatan','2','debet',2,'1705150001','admin','2017-11-17 04:50:04'),('0001600003','00016','Pendapatan dari Jasa Usaha Layanan Lainnya','2','debet',3,'1705150001','admin','2017-11-17 04:50:22'),('0001600004','00016','PENDAPATAN APBN','1','debet',4,'1705150001','admin','2017-11-17 04:50:48'),('0001600005','00016','Pendapatan APBN','2','debet',5,'1705150001','admin','2017-11-17 04:51:06'),('0001600006','00016','Pendapatan RM','2','debet',6,'1705150001','admin','2017-11-17 04:51:21'),('0001600007','00016','PENDAPATAN USAHA LAINNYA','1','debet',7,'1705150001','admin','2017-11-17 04:51:54'),('0001600008','00016','Pendapatan Kerjasama Layanan BLU Dengan Pihak Lain','2','debet',8,'1705150001','admin','2017-11-17 04:52:28'),('0001600009','00016','Pendapatan Sewa','2','debet',9,'1705150001','admin','2017-11-17 04:52:47'),('0001600010','00016','Pendapatan Jasa Lembaga Keuangan','2','debet',10,'1705150001','admin','2017-11-17 04:53:23'),('0001600011','00016','Pendapatan Lain-Lain','2','debet',11,'1705150001','admin','2017-11-17 04:53:43'),('0001600012','00016','PENDAPATAN HIBAH','1','debet',12,'1705150001','admin','2017-11-17 04:55:34'),('0001600013','00016','Hibah','2','debet',13,'1705150001','admin','2017-11-17 04:55:51'),('0001600014','00016','BEBAN','1','debet',14,'1705150001','admin','2017-11-17 04:58:05'),('0001600015','00016','Beban Layanan','2','debet',15,'1705150001','admin','2017-11-17 04:58:34'),('0001600016','00016','Beban Pegawai Layanan Kesehatan','3','debet',16,'1705150001','admin','2017-11-17 04:59:08'),('0001600017','00016','Beban Pegawai PNS','4','debet',17,'1705150001','admin','2017-11-17 05:00:08'),('0001600018','00016','Beban Pegawai Non PNS','4','debet',18,'1705150001','admin','2017-11-17 05:00:34'),('0001600019','00016','Beban Bahan','3','debet',19,'1705150001','admin','2017-11-17 05:00:55'),('0001600020','00016','Beban Perlengkapan dan Keperluan Harian layanan kesehatan','4','debet',20,'1705150001','admin','2017-11-17 05:01:29'),('0001600021','00016','Beban Jasa layanan kesehatan','3','debet',21,'1705150001','admin','2017-11-17 05:02:42'),('0001600022','00016','Beban Jasa Tenaga Ahli layanan kesehatan','4','debet',22,'1705150001','admin','2017-11-17 05:03:26'),('0001600023','00016','Beban Sewa layanan kesehatan','4','debet',23,'1705150001','admin','2017-11-17 05:04:02'),('0001600024','00016','Beban Pemeliharaan layanan kesehatan','3','debet',24,'1705150001','admin','2017-11-17 05:04:50'),('0001600025','00016','Beban Pemeliharaan Gedung layanan kesehatan','4','debet',25,'1705150001','admin','2017-11-17 05:05:32'),('0001600026','00016','Beban Pemeliharaan Peralatan layanan kesehatan','4','debet',26,'1705150001','admin','2017-11-17 05:06:08'),('0001600027','00016','Beban Pemeliharaan Mesin layanan kesehatan','4','debet',27,'1705150001','admin','2017-11-17 05:06:47'),('0001600028','00016','Beban Penyusutan dan Amortisasi layanan kesehatan','3','debet',28,'1705150001','admin','2017-11-17 05:07:39'),('0001600029','00016','Beban Utilitas layanan kesehatan','3','debet',29,'1705150001','admin','2017-11-17 05:08:29'),('0001600030','00016','Beban Air, Telepon, Listrik layanan kesehatan','4','debet',30,'1705150001','admin','2017-11-17 05:09:22'),('0001600031','00016','Beban layanan kesehatan lain-lain','3','debet',31,'1705150001','admin','2017-11-17 05:10:01'),('0001600032','00016','Beban Perjalanan Dinas layanan kesehatan','4','debet',32,'1705150001','admin','2017-11-17 05:10:19'),('0001600033','00016','Beban Penyisihan Piutang layanan kesehatan Tak Tertagih','4','debet',33,'1705150001','admin','2017-11-17 05:10:53'),('0001600034','00016','Beban Umum dan Administrasi','2','debet',34,'1705150001','admin','2017-11-17 05:11:29'),('0001600035','00016','Beban Pegawai Kantor','3','debet',35,'1705150001','admin','2017-11-17 05:11:50'),('0001600036','00016','Beban Administrasi Perkantoran','3','debet',36,'1705150001','admin','2017-11-17 05:12:16'),('0001600037','00016','Beban Perlengkapan dan Keperluan Harian Kantor','4','debet',37,'1705150001','admin','2017-11-17 05:12:42'),('0001600038','00016','Beban Jasa Kantor','3','debet',38,'1705150001','admin','2017-11-17 05:13:04'),('0001600039','00016','Beban Jasa Tenaga Ahli Kantor','4','debet',39,'1705150001','admin','2017-11-17 05:13:41'),('0001600040','00016','Beban Sewa Kantor','4','debet',40,'1705150001','admin','2017-11-17 05:14:05'),('0001600041','00016','Beban Pemeliharaan Kantor','3','debet',41,'1705150001','admin','2017-11-17 05:14:26'),('0001600042','00016','Beban Pemeliharaan Gedung Kantor','4','debet',42,'1705150001','admin','2017-11-17 05:14:46'),('0001600043','00016','Beban Pemeliharaan Peralatan Kantor','4','debet',43,'1705150001','admin','2017-11-17 05:15:23'),('0001600044','00016','Beban Pemeliharaan Mesin Kantor','4','debet',44,'1705150001','admin','2017-11-17 05:15:53'),('0001600045','00016','Beban Penyusutan dan Amortisasi Kantor','3','debet',45,'1705150001','admin','2017-11-17 05:16:16'),('0001600046','00016','Beban Utilitas Kantor','3','debet',46,'1705150001','admin','2017-11-17 05:16:39'),('0001600047','00016','Beban Air, Telepon, Listrik Kantor','4','debet',47,'1705150001','admin','2017-11-17 05:16:59'),('0001600048','00016','Beban Kantor Lain-Lain','3','debet',48,'1705150001','admin','2017-11-17 05:17:18'),('0001600049','00016','Beban Perjalanan Dinas Kantor','4','debet',49,'1705150001','admin','2017-11-17 05:17:41'),('0001600050','00016','Beban Kantor Lainnya','4','debet',50,'1705150001','admin','2017-11-17 05:18:23'),('0001600051','00016','Beban Lainnya','2','debet',51,'1705150001','admin','2017-11-17 07:49:48'),('0001600052','00016','Beban Bunga','3','debet',52,'1705150001','admin','2017-11-17 07:50:03'),('0001600053','00016','Beban Administrasi Bank','3','debet',53,'1705150001','admin','2017-11-17 07:50:26'),('0001600054','00016','Beban Buku Cek','4','debet',54,'1705150001','admin','2017-11-17 07:51:01'),('0001600055','00016','Beban Pajak Jasa Giro','4','debet',55,'1705150001','admin','2017-11-17 07:51:30'),('0001600056','00016','Beban Mesin EDC','4','debet',56,'1705150001','admin','2017-11-17 07:51:50'),('0001600057','00016','KEUNTUNGAN DAN KERUGIAN','1','debet',57,'1705150001','admin','2017-11-17 07:56:29'),('0001600058','00016','Keuntungan Penjualan Aset','2','debet',58,'1705150001','admin','2017-11-17 07:57:28'),('0001600059','00016','Keuntungan Penjualan Aset','3','debet',59,'1705150001','admin','2017-11-17 07:57:52'),('0001600060','00016','Kerugian Penjualan Aset','2','debet',60,'1705150001','admin','2017-11-17 07:58:09'),('0001600061','00016','Kerugian Penjualan Aset','3','debet',61,'1705150001','admin','2017-11-17 07:58:24'),('0001600062','00016','POS-POS LUAR BIASA','1','debet',62,'1705150001','admin','2017-11-17 07:59:59'),('0001600063','00016','Keuntungan dan Pendapatan Luar Biasa','2','debet',63,'1705150001','admin','2017-11-17 08:00:17'),('0001600064','00016','Keuntungan Luar Biasa','3','debet',64,'1705150001','admin','2017-11-17 08:00:32'),('0001600065','00016','Kerugian dan Pendapatan Luar Biasa','2','debet',65,'1705150001','admin','2017-11-17 08:02:11'),('0001600066','00016','Kerugian Luar Biasa','3','debet',66,'1705150001','admin','2017-11-17 08:02:29'),('0001700001','00017','ASET LANCAR','1','kredit',NULL,'1705150001','admin','2017-11-17 11:02:43'),('0001700002','00017','KAS TE','2','kredit',NULL,'1705150001','admin','2017-11-17 11:03:42');

UNLOCK TABLES;

/*Table structure for table `laporan_format_item` */

DROP TABLE IF EXISTS `laporan_format_item`;

CREATE TABLE `laporan_format_item` (
  `item_id` varchar(20) NOT NULL,
  `format_id` varchar(10) DEFAULT NULL,
  `kode_akun` varchar(8) DEFAULT NULL,
  `item_jenis` enum('debet','kredit') DEFAULT NULL,
  `mdb` varchar(10) DEFAULT NULL,
  `mdb_name` varchar(50) DEFAULT NULL,
  `mdd` datetime DEFAULT NULL,
  PRIMARY KEY (`item_id`),
  KEY `format_id` (`format_id`),
  CONSTRAINT `laporan_format_item_ibfk_1` FOREIGN KEY (`format_id`) REFERENCES `laporan_format` (`format_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `laporan_format_item` */

LOCK TABLES `laporan_format_item` WRITE;

insert  into `laporan_format_item`(`item_id`,`format_id`,`kode_akun`,`item_jenis`,`mdb`,`mdb_name`,`mdd`) values ('000020000400001','0000200004','','debet','1705150001','admin','2017-11-17 04:09:06'),('000020000500001','0000200005','','debet','1705150001','admin','2017-11-17 04:09:51'),('000020000600001','0000200006','','debet','1705150001','admin','2017-11-17 04:11:23'),('000020000800001','0000200008','','debet','1705150001','admin','2017-11-17 04:12:13'),('000020001000001','0000200010','','debet','1705150001','admin','2017-11-17 04:12:52'),('000020001100001','0000200011','','debet','1705150001','admin','2017-11-17 04:13:33'),('000020001200001','0000200012','','debet','1705150001','admin','2017-11-17 04:14:18'),('000020001300001','0000200013','','debet','1705150001','admin','2017-11-17 04:15:04'),('000020001400001','0000200014','','debet','1705150001','admin','2017-11-17 04:16:12'),('000020001700001','0000200017','','debet','1705150001','admin','2017-11-17 04:16:55'),('000020001800001','0000200018','','debet','1705150001','admin','2017-11-17 04:19:22'),('000020001900001','0000200019','','debet','1705150001','admin','2017-11-17 04:20:08'),('000020002000001','0000200020','','debet','1705150001','admin','2017-11-17 04:20:56'),('000020002100001','0000200021','','debet','1705150001','admin','2017-11-17 04:21:41'),('000020002200001','0000200022','','debet','1705150001','admin','2017-11-17 04:22:23'),('000020002300001','0000200023','','debet','1705150001','admin','2017-11-17 04:23:13'),('000020002400001','0000200024','','debet','1705150001','admin','2017-11-17 04:24:01'),('000020002500001','0000200025','','debet','1705150001','admin','2017-11-17 04:25:05'),('000020002600001','0000200026','','debet','1705150001','admin','2017-11-17 04:25:57'),('000020002700001','0000200027','','debet','1705150001','admin','2017-11-17 04:27:06'),('000020002800001','0000200028','','debet','1705150001','admin','2017-11-17 04:31:58'),('000020002900001','0000200029','','debet','1705150001','admin','2017-11-17 04:38:03'),('000020003000001','0000200030','','debet','1705150001','admin','2017-11-17 04:38:52'),('000020003100001','0000200031','','debet','1705150001','admin','2017-11-17 04:39:37'),('000020003200001','0000200032','','debet','1705150001','admin','2017-11-17 04:40:54'),('000020003300001','0000200033','','debet','1705150001','admin','2017-11-17 04:43:29'),('000020003400001','0000200034','','debet','1705150001','admin','2017-11-17 04:44:35'),('000020003500001','0000200035','','debet','1705150001','admin','2017-11-17 04:45:21'),('000020003600001','0000200036','','debet','1705150001','admin','2017-11-22 05:35:55'),('000020003700001','0000200037','','debet','1705150001','admin','2017-11-17 04:46:56'),('000020003800001','0000200038','','debet','1705150001','admin','2017-11-17 04:47:34'),('000020003900001','0000200039','','debet','1705150001','admin','2017-11-17 05:06:04'),('000020004200001','0000200042','','debet','1705150001','admin','2017-11-17 04:50:56'),('000020004300001','0000200043','','debet','1705150001','admin','2017-11-17 04:51:41'),('000020004400001','0000200044','','debet','1705150001','admin','2017-11-17 04:52:40'),('000020004500001','0000200045','','debet','1705150001','admin','2017-11-17 04:53:29'),('000020004600001','0000200046','','debet','1705150001','admin','2017-11-17 04:54:14'),('000020004700001','0000200047','','debet','1705150001','admin','2017-11-17 04:55:19'),('000020004800001','0000200048','','debet','1705150001','admin','2017-11-17 04:56:27'),('000020005100001','0000200051','','debet','1705150001','admin','2017-11-17 04:57:36'),('000020005200001','0000200052','','debet','1705150001','admin','2017-11-17 04:58:30'),('000020005300001','0000200053','','debet','1705150001','admin','2017-11-17 04:59:35'),('000020005400001','0000200054','','debet','1705150001','admin','2017-11-17 05:00:30'),('000020005500001','0000200055','','debet','1705150001','admin','2017-11-17 05:01:37'),('000020005600001','0000200056','','debet','1705150001','admin','2017-11-17 05:02:35'),('000020005800001','0000200058','','debet','1705150001','admin','2017-11-17 05:03:34'),('000020005900001','0000200059','','debet','1705150001','admin','2017-11-17 05:04:38'),('000020006000001','0000200060','','debet','1705150001','admin','2017-11-22 05:00:38'),('000020006000002','0000200060','','debet','1705150001','admin','2017-11-22 05:00:59'),('000020006000003','0000200060','','debet','1705150001','admin','2017-11-22 05:01:20'),('000020006000004','0000200060','','debet','1705150001','admin','2017-11-22 05:01:46'),('000020006000005','0000200060','','debet','1705150001','admin','2017-11-22 05:02:16'),('000020006000006','0000200060','','debet','1705150001','admin','2017-11-22 05:02:35'),('000020006000007','0000200060','','debet','1705150001','admin','2017-11-22 05:03:06'),('000020006000008','0000200060','','debet','1705150001','admin','2017-11-22 05:12:52'),('000020006100001','0000200061','','debet','1705150001','admin','2017-11-22 05:14:50'),('000020006100002','0000200061','','debet','1705150001','admin','2017-11-22 05:15:11'),('000020006100003','0000200061','','debet','1705150001','admin','2017-11-22 05:15:48'),('000020006100004','0000200061','','debet','1705150001','admin','2017-11-22 05:16:14'),('000020006100005','0000200061','','debet','1705150001','admin','2017-11-22 05:16:30'),('000020006100006','0000200061','','debet','1705150001','admin','2017-11-22 05:16:50'),('000020006100007','0000200061','','debet','1705150001','admin','2017-11-22 05:17:09'),('000020006100008','0000200061','','debet','1705150001','admin','2017-11-22 05:17:25'),('000020006100009','0000200061','','debet','1705150001','admin','2017-11-22 05:18:57'),('000020006100010','0000200061','','debet','1705150001','admin','2017-11-22 05:19:31'),('000020006100011','0000200061','','debet','1705150001','admin','2017-11-22 05:19:57'),('000020006100012','0000200061','','debet','1705150001','admin','2017-11-22 05:20:22'),('000020006100013','0000200061','','debet','1705150001','admin','2017-11-22 05:20:42'),('000020006100014','0000200061','','debet','1705150001','admin','2017-11-22 05:21:14'),('000020006100015','0000200061','','debet','1705150001','admin','2017-11-22 05:21:35'),('000020006200001','0000200062','','debet','1705150001','admin','2017-11-22 08:29:00'),('000020006200002','0000200062','','debet','1705150001','admin','2017-11-22 08:29:14'),('000020006200003','0000200062','','debet','1705150001','admin','2017-11-22 08:29:40'),('000020006200004','0000200062','','debet','1705150001','admin','2017-11-22 08:30:02'),('000020006200005','0000200062','','debet','1705150001','admin','2017-11-22 08:30:19'),('000020006200006','0000200062','','debet','1705150001','admin','2017-11-22 08:30:33'),('000020006200007','0000200062','','debet','1705150001','admin','2017-11-22 08:30:46'),('000020006200008','0000200062','','debet','1705150001','admin','2017-11-22 08:30:59'),('000020006200009','0000200062','','debet','1705150001','admin','2017-11-22 08:31:27'),('000020006200010','0000200062','','debet','1705150001','admin','2017-11-22 08:31:41'),('000020006200011','0000200062','','debet','1705150001','admin','2017-11-22 08:31:56'),('000020006200012','0000200062','','debet','1705150001','admin','2017-11-22 08:32:53'),('000020006200013','0000200062','','debet','1705150001','admin','2017-11-22 08:33:07'),('000020006200014','0000200062','','debet','1705150001','admin','2017-11-22 08:33:24'),('000020006200015','0000200062','','debet','1705150001','admin','2017-11-22 08:33:36'),('000020006200016','0000200062','','debet','1705150001','admin','2017-11-22 08:33:50'),('000020006200017','0000200062','','debet','1705150001','admin','2017-11-22 08:34:03'),('000020006200018','0000200062','','debet','1705150001','admin','2017-11-22 08:34:39'),('000020006200019','0000200062','','debet','1705150001','admin','2017-11-22 08:34:52'),('000020006200020','0000200062','','debet','1705150001','admin','2017-11-22 08:35:08'),('000020006200021','0000200062','','debet','1705150001','admin','2017-11-22 08:35:26'),('000020006200022','0000200062','','debet','1705150001','admin','2017-11-22 08:35:52'),('000020006300001','0000200063','','kredit','1705150001','admin','2017-11-22 08:42:45'),('000020006300002','0000200063','','kredit','1705150001','admin','2017-11-22 08:43:02'),('000020006300003','0000200063','','kredit','1705150001','admin','2017-11-22 08:43:15'),('000020006300004','0000200063','','kredit','1705150001','admin','2017-11-22 08:44:16'),('000020006300005','0000200063','','kredit','1705150001','admin','2017-11-22 08:44:33'),('000020006300006','0000200063','','kredit','1705150001','admin','2017-11-22 08:44:43'),('000020006400001','0000200064','','kredit','1705150001','admin','2017-11-22 08:45:21'),('000020006400002','0000200064','','kredit','1705150001','admin','2017-11-22 08:45:32'),('000020006400003','0000200064','','kredit','1705150001','admin','2017-11-22 08:45:44'),('000020006400004','0000200064','','kredit','1705150001','admin','2017-11-22 08:45:57'),('000020006400005','0000200064','','kredit','1705150001','admin','2017-11-22 08:46:10'),('000030000400001','0000300004','113991','debet','1705150001','admin','2017-11-17 03:29:18'),('000030000700001','0000300007','113991','debet','1705150001','admin','2017-11-17 03:29:18'),('000030000800001','0000300008','113991','debet','1705150001','admin','2017-11-17 03:29:18'),('000030000900001','0000300009','113991','debet','1705150001','admin','2017-11-17 03:29:18'),('000030001000001','0000300010','113991','debet','1705150001','admin','2017-11-17 03:29:18'),('000030001100001','0000300011','113991','debet','1705150001','admin','2017-11-17 03:29:18'),('000030001200001','0000300012','116691','debet','1705150001','admin','2017-11-17 03:30:36'),('000030001300001','0000300013','116691','debet','1705150001','admin','2017-11-17 03:31:16'),('000030001400001','0000300014','115','debet','1705150001','admin','2017-11-17 03:32:38'),('000030001700001','0000300017','131311','debet','1705150001','admin','2017-11-17 03:35:57'),('000030001800001','0000300018','132311','debet','1705150001','admin','2017-11-17 03:36:28'),('000030001900001','0000300019','133311','debet','1705150001','admin','2017-11-17 03:36:48'),('000030002000001','0000300020','134311','debet','1705150001','admin','2017-11-17 03:37:20'),('000030002100001','0000300021','137121','debet','1705150001','admin','2017-11-17 03:37:44'),('000030002200001','0000300022','137221','debet','1705150001','admin','2017-11-17 03:38:03'),('000030002300001','0000300023','137321','debet','1705150001','admin','2017-11-17 03:38:23'),('000030002400001','0000300024','1159','debet','1705150001','admin','2017-11-17 03:38:58'),('000030002800001','0000300028','2195','debet','1705150001','admin','2017-11-17 03:39:32'),('000030002900001','0000300029','11317','debet','1705150001','admin','2017-11-17 03:39:59'),('000030003200001','0000300032','391111','debet','1705150001','admin','2017-11-17 07:43:22'),('000050000300001','0000500003','','debet','1705150001','admin','2017-11-17 03:45:21'),('000050000400001','0000500004','','debet','1705150001','admin','2017-11-17 03:49:18'),('000050000500001','0000500005','','debet','1705150001','admin','2017-11-17 03:47:18'),('000050000600001','0000500006','','debet','1705150001','admin','2017-11-17 05:19:04'),('000050000800001','0000500008','','debet','1705150001','admin','2017-11-17 05:19:32'),('000050000900001','0000500009','','debet','1705150001','admin','2017-11-17 05:21:02'),('000050001000001','0000500010','','debet','1705150001','admin','2017-11-17 05:21:28'),('000050001100001','0000500011','','debet','1705150001','admin','2017-11-17 05:22:13'),('000050002100001','0000500021','','debet','1705150001','admin','2017-11-17 07:49:55'),('000050002600001','0000500026','','debet','1705150001','admin','2017-11-17 07:50:20'),('000080000900001','0000800009','51','debet','1705150001','admin','2017-11-17 10:07:54'),('000080001000001','0000800010','52','debet','1705150001','admin','2017-11-17 10:08:43'),('000110000300001','0001100003','113991','debet','1705150001','admin','2017-11-17 03:29:18'),('000110000600001','0001100006','','kredit','1705150001','admin','2017-12-18 08:36:37'),('000110000800001','0001100008','','debet','1705150001','admin','2017-12-18 08:37:51'),('000110001000002','0001100010','','debet','1705150001','admin','2017-12-18 08:39:05'),('000110001200001','0001100012','','debet','1705150001','admin','2017-12-18 08:44:35'),('000120000100001','0001200001','','debet','1705150001','admin','2017-11-17 07:58:50'),('000120000200001','0001200002','','debet','1705150001','admin','2017-11-17 07:59:26'),('000120000500001','0001200005','','debet','1705150001','admin','2017-11-17 08:05:00'),('000140000900001','0001400009','','debet','1705150001','admin','2017-11-17 04:17:41'),('000160000200001','0001600002','','debet','1705150001','admin','2017-11-17 05:20:20'),('000160000300001','0001600003','','debet','1705150001','admin','2017-11-17 05:22:51'),('000160000500001','0001600005','','debet','1705150001','admin','2017-11-17 05:23:19'),('000160000600001','0001600006','','debet','1705150001','admin','2017-11-17 05:23:47'),('000160000800001','0001600008','','debet','1705150001','admin','2017-11-17 05:24:41'),('000160000900001','0001600009','','debet','1705150001','admin','2017-11-17 05:25:09'),('000160001000001','0001600010','','debet','1705150001','admin','2017-11-17 05:25:33'),('000160001100001','0001600011','','debet','1705150001','admin','2017-11-17 05:26:01'),('000160001300001','0001600013','','debet','1705150001','admin','2017-11-17 05:26:32'),('000160001600001','0001600016','','debet','1705150001','admin','2017-11-17 05:27:23'),('000160001700001','0001600017','','debet','1705150001','admin','2017-11-17 05:27:43'),('000160001800001','0001600018','','debet','1705150001','admin','2017-11-17 05:27:59'),('000160001900001','0001600019','','debet','1705150001','admin','2017-11-17 05:28:37'),('000160002000001','0001600020','','debet','1705150001','admin','2017-11-17 05:29:03'),('000160002100001','0001600021','','debet','1705150001','admin','2017-11-17 05:29:38'),('000160002200001','0001600022','','debet','1705150001','admin','2017-11-17 05:30:09'),('000160002300001','0001600023','','debet','1705150001','admin','2017-11-17 05:31:14'),('000160002400001','0001600024','','debet','1705150001','admin','2017-11-17 05:32:44'),('000160002500001','0001600025','','debet','1705150001','admin','2017-11-17 05:33:18'),('000160002600001','0001600026','','debet','1705150001','admin','2017-11-17 05:34:09'),('000160002700001','0001600027','','debet','1705150001','admin','2017-11-17 05:34:29'),('000160002800001','0001600028','','debet','1705150001','admin','2017-11-17 05:35:37'),('000160002900001','0001600029','','debet','1705150001','admin','2017-11-17 05:35:58'),('000160003000001','0001600030','','debet','1705150001','admin','2017-11-17 05:36:12'),('000160003100001','0001600031','','debet','1705150001','admin','2017-11-17 05:36:51'),('000160003200001','0001600032','','debet','1705150001','admin','2017-11-17 05:37:08'),('000160003300001','0001600033','','debet','1705150001','admin','2017-11-17 05:37:27'),('000160003500001','0001600035','','debet','1705150001','admin','2017-11-17 07:37:49'),('000160003600001','0001600036','','debet','1705150001','admin','2017-11-17 07:38:50'),('000160003700001','0001600037','','debet','1705150001','admin','2017-11-17 07:41:26'),('000160003800001','0001600038','','debet','1705150001','admin','2017-11-17 07:42:15'),('000160003900001','0001600039','','debet','1705150001','admin','2017-11-17 07:43:03'),('000160004000001','0001600040','','debet','1705150001','admin','2017-11-17 07:43:26'),('000160004100001','0001600041','','debet','1705150001','admin','2017-11-17 07:44:37'),('000160004200001','0001600042','','debet','1705150001','admin','2017-11-17 07:45:11'),('000160004300001','0001600043','','debet','1705150001','admin','2017-11-17 07:45:41'),('000160004400001','0001600044','','debet','1705150001','admin','2017-11-17 07:46:11'),('000160004500001','0001600045','','debet','1705150001','admin','2017-11-17 07:47:01'),('000160004600001','0001600046','','debet','1705150001','admin','2017-11-17 07:47:28'),('000160004700001','0001600047','','debet','1705150001','admin','2017-11-17 07:47:53'),('000160004800001','0001600048','','debet','1705150001','admin','2017-11-17 07:48:36'),('000160004900001','0001600049','','debet','1705150001','admin','2017-11-17 07:48:57'),('000160005000001','0001600050','','debet','1705150001','admin','2017-11-17 07:49:17'),('000160005200001','0001600052','','debet','1705150001','admin','2017-11-17 07:53:07'),('000160005300001','0001600053','','debet','1705150001','admin','2017-11-17 07:53:49'),('000160005400001','0001600054','','debet','1705150001','admin','2017-11-17 07:54:12'),('000160005500001','0001600055','','debet','1705150001','admin','2017-11-17 07:54:42'),('000160005600001','0001600056','','debet','1705150001','admin','2017-11-17 07:55:00'),('000160005900001','0001600059','','debet','1705150001','admin','2017-11-17 07:59:05'),('000160006100001','0001600061','','debet','1705150001','admin','2017-11-17 07:59:21'),('000160006400001','0001600064','','debet','1705150001','admin','2017-11-17 08:03:11'),('000160006600001','0001600066','','debet','1705150001','admin','2017-11-17 08:03:27'),('000170000200001','0001700002','','kredit','1705150001','admin','2017-11-17 11:05:43');

UNLOCK TABLES;

/*Table structure for table `pegawai` */

DROP TABLE IF EXISTS `pegawai`;

CREATE TABLE `pegawai` (
  `user_id` varchar(10) NOT NULL,
  `pegawai_nip` varchar(10) DEFAULT NULL,
  `pegawai_status` enum('working','resign') DEFAULT 'working',
  `nama_lengkap` varchar(100) DEFAULT NULL,
  `jenis_kelamin` enum('L','P') DEFAULT NULL,
  `nomor_identitas` varchar(50) DEFAULT NULL,
  `jenis_identitas` varchar(50) DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `tempat_lahir` varchar(50) DEFAULT NULL,
  `alamat_ktp` varchar(100) DEFAULT NULL,
  `alamat_tinggal` varchar(100) DEFAULT NULL,
  `nomor_telepon` varchar(50) DEFAULT NULL,
  `foto_path` varchar(255) DEFAULT NULL,
  `foto_name` varchar(255) DEFAULT NULL,
  `edu_instansi_nm` varchar(50) DEFAULT NULL,
  `edu_instansi_address` varchar(100) DEFAULT NULL,
  `edu_grade` varchar(10) DEFAULT NULL,
  `edu_spezialitation` varchar(100) DEFAULT NULL,
  `edu_graduation_year` year(4) DEFAULT NULL,
  `tanggal_masuk` date DEFAULT NULL,
  `tanggal_keluar` date DEFAULT NULL,
  `struktur_cd` varchar(10) DEFAULT NULL,
  `jabatan_struktural_st` enum('1','0') DEFAULT '0',
  `jabatan_struktural_id` varchar(15) DEFAULT NULL,
  `mdb` varchar(10) DEFAULT NULL,
  `mdb_name` varchar(50) DEFAULT NULL,
  `mdd` datetime DEFAULT NULL,
  `jabatan_fungsional_id` varchar(15) DEFAULT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `pegawai` */

LOCK TABLES `pegawai` WRITE;

insert  into `pegawai`(`user_id`,`pegawai_nip`,`pegawai_status`,`nama_lengkap`,`jenis_kelamin`,`nomor_identitas`,`jenis_identitas`,`tanggal_lahir`,`tempat_lahir`,`alamat_ktp`,`alamat_tinggal`,`nomor_telepon`,`foto_path`,`foto_name`,`edu_instansi_nm`,`edu_instansi_address`,`edu_grade`,`edu_spezialitation`,`edu_graduation_year`,`tanggal_masuk`,`tanggal_keluar`,`struktur_cd`,`jabatan_struktural_st`,`jabatan_struktural_id`,`mdb`,`mdb_name`,`mdd`,`jabatan_fungsional_id`) values ('1804170001',NULL,'working','Welly Widodo Sindu Putra',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'001.01.00','0',NULL,'1804170001','Welly Widodo Sindu Putra','2018-04-17 13:48:17',NULL),('1804170002',NULL,'working','Para Anto Wijanarko',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'001.05.00','0',NULL,'1804170001','Welly Widodo Sindu Putra','2018-04-17 13:41:02',NULL),('1804170003',NULL,'working','Aulia Shofi',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'001.06.00','0',NULL,'1804170001','Welly Widodo Sindu Putra','2018-04-17 13:50:37',NULL),('1804170004',NULL,'working','Agung Budi Prasetyo',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'001.08.00','0',NULL,'1804170001','Welly Widodo Sindu Putra','2018-04-17 13:51:29',NULL),('1804170005',NULL,'working','Adellia Winda Putri',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'001.06.00','0',NULL,'1804170001','Welly Widodo Sindu Putra','2018-04-17 13:52:40',NULL),('1804170006',NULL,'working','Irma Suwarning Dyastuti',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'001.05.00','0',NULL,'1804170001','Welly Widodo Sindu Putra','2018-04-17 13:54:16',NULL),('1804170007',NULL,'working','Rini',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'001.01.00','0',NULL,'1804170001','Welly Widodo Sindu Putra','2018-04-17 13:54:54',NULL),('1804170008',NULL,'working','Salmuasih',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'001.01.00','0',NULL,'1804170001','Welly Widodo Sindu Putra','2018-04-17 14:02:17',NULL),('1804170009',NULL,'working','Muhammad Sulaiman Yusuf',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'001.01.00','0',NULL,'1804170001','Welly Widodo Sindu Putra','2018-04-17 14:03:54',NULL),('1810220001',NULL,'working','asdadsad',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'0','0',NULL,'1804170001','Welly WSP','2018-10-22 14:31:37',NULL);

UNLOCK TABLES;

/*Table structure for table `pegawai_cuti` */

DROP TABLE IF EXISTS `pegawai_cuti`;

CREATE TABLE `pegawai_cuti` (
  `cuti_id` varchar(20) NOT NULL,
  `user_id` varchar(10) DEFAULT NULL,
  `jenis_id` varchar(5) DEFAULT NULL,
  `struktur_cd` varchar(10) DEFAULT NULL,
  `cuti_nomor` varchar(20) DEFAULT NULL,
  `cuti_uraian` varchar(255) DEFAULT NULL,
  `cuti_pic` varchar(10) DEFAULT NULL,
  `cuti_pic_name` varchar(50) DEFAULT NULL,
  `cuti_tanggal_mulai` date DEFAULT NULL,
  `cuti_tanggal_selesai` date DEFAULT NULL,
  `cuti_status` enum('waiting','approved','rejected','draft') DEFAULT 'draft',
  `cuti_send_by` varchar(10) DEFAULT NULL,
  `cuti_send_by_name` varchar(50) DEFAULT NULL,
  `cuti_send_date` datetime DEFAULT NULL,
  `mdb` varchar(10) DEFAULT NULL,
  `mdb_name` varchar(50) DEFAULT NULL,
  `mdd` datetime DEFAULT NULL,
  PRIMARY KEY (`cuti_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `pegawai_cuti_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `pegawai` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `pegawai_cuti` */

LOCK TABLES `pegawai_cuti` WRITE;

insert  into `pegawai_cuti`(`cuti_id`,`user_id`,`jenis_id`,`struktur_cd`,`cuti_nomor`,`cuti_uraian`,`cuti_pic`,`cuti_pic_name`,`cuti_tanggal_mulai`,`cuti_tanggal_selesai`,`cuti_status`,`cuti_send_by`,`cuti_send_by_name`,`cuti_send_date`,`mdb`,`mdb_name`,`mdd`) values ('20180425000000000001','1804170001','CT.01','001.01.00','000001/CUTI/IV/2018','TESTING PROSES PENGAJUAN CUTI','1804170008','Salmuasih','2018-04-30','2018-04-30','waiting','1804170001','Welly WSP','2018-04-25 10:51:25','1804170001','Welly WSP','2018-04-25 10:59:42'),('20180502000000000002','1804170001','CT.01','001.01.00','000002/CUTI/V/2018','TESTING APLIKASI DARI TASK','1804170008','Salmuasih','2018-05-02','2018-05-02','draft','1804170001','Welly WSP','2018-04-25 11:13:20','1804170001','Welly WSP','2018-04-26 11:41:57'),('20180504000000000003','1804170001','CT.01','001.01.00','000003/CUTI/V/2018','TESTING REJECT PIMPINAN','1804170008','Salmuasih','2018-05-04','2018-05-04','waiting','1804170001','Welly WSP','2018-04-25 16:12:58','1804170001','Welly WSP','2018-04-25 16:15:29');

UNLOCK TABLES;

/*Table structure for table `pegawai_cuti_kuota` */

DROP TABLE IF EXISTS `pegawai_cuti_kuota`;

CREATE TABLE `pegawai_cuti_kuota` (
  `user_id` varchar(10) NOT NULL,
  `jenis_id` varchar(5) NOT NULL,
  `tahun` year(4) NOT NULL,
  `total` int(11) unsigned DEFAULT NULL,
  `mdb` varchar(10) DEFAULT NULL,
  `mdb_name` varchar(50) DEFAULT NULL,
  `mdd` datetime DEFAULT NULL,
  PRIMARY KEY (`user_id`,`jenis_id`,`tahun`),
  CONSTRAINT `pegawai_cuti_kuota_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `pegawai` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `pegawai_cuti_kuota` */

LOCK TABLES `pegawai_cuti_kuota` WRITE;

insert  into `pegawai_cuti_kuota`(`user_id`,`jenis_id`,`tahun`,`total`,`mdb`,`mdb_name`,`mdd`) values ('1804170001','CT.01',2018,14,NULL,NULL,NULL);

UNLOCK TABLES;

/*Table structure for table `pegawai_cuti_process` */

DROP TABLE IF EXISTS `pegawai_cuti_process`;

CREATE TABLE `pegawai_cuti_process` (
  `process_id` varchar(20) NOT NULL,
  `cuti_id` varchar(20) DEFAULT NULL,
  `flow_id` varchar(5) DEFAULT NULL,
  `flow_revisi_id` varchar(5) DEFAULT NULL,
  `process_references_id` varchar(20) DEFAULT NULL,
  `process_st` enum('waiting','approve','reject') DEFAULT 'waiting',
  `action_st` enum('process','done') DEFAULT 'process',
  `catatan` text,
  `mdb` varchar(10) DEFAULT NULL,
  `mdb_name` varchar(50) DEFAULT NULL,
  `mdd` datetime DEFAULT NULL,
  `mdb_finish` varchar(10) DEFAULT NULL,
  `mdb_finish_name` varchar(50) DEFAULT NULL,
  `mdd_finish` datetime DEFAULT NULL,
  PRIMARY KEY (`process_id`),
  KEY `cuti_id` (`cuti_id`),
  CONSTRAINT `pegawai_cuti_process_ibfk_1` FOREIGN KEY (`cuti_id`) REFERENCES `pegawai_cuti` (`cuti_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `pegawai_cuti_process` */

LOCK TABLES `pegawai_cuti_process` WRITE;

insert  into `pegawai_cuti_process`(`process_id`,`cuti_id`,`flow_id`,`flow_revisi_id`,`process_references_id`,`process_st`,`action_st`,`catatan`,`mdb`,`mdb_name`,`mdd`,`mdb_finish`,`mdb_finish_name`,`mdd_finish`) values ('',NULL,NULL,NULL,NULL,'waiting','process',NULL,NULL,NULL,NULL,NULL,NULL,NULL),('15246282856188','20180425000000000001','11001',NULL,NULL,'approve','done',NULL,'1804170001','Welly WSP','2018-04-25 10:51:25','1804170001','Welly WSP','2018-04-25 11:01:36'),('152462865335','20180425000000000001','11002',NULL,NULL,'reject','done','REJECT','1804170001','Welly WSP','2018-04-25 10:57:33','1804170001','Welly WSP','2018-04-25 10:59:42'),('15246287825299','20180425000000000001','11001','11002','152462865335','approve','done',NULL,'1804170001','Welly WSP','2018-04-25 10:59:42','1804170001','Welly WSP','2018-04-25 11:01:36'),('15246288962206','20180425000000000001','11002','11001','15246287825299','approve','done','OK APPROVE','1804170001','Welly WSP','2018-04-25 11:01:36','1804170001','Welly WSP','2018-04-25 11:02:44'),('15246289649608','20180425000000000001','11003','11002','15246288962206','waiting','process',NULL,'1804170001','Welly WSP','2018-04-25 11:02:44',NULL,NULL,NULL),('15246296002616','20180502000000000002','11001',NULL,NULL,'waiting','process',NULL,'1804170001','Welly WSP','2018-04-25 11:13:20','1804170001','Welly WSP','2018-04-25 11:13:48'),('15246475787892','20180504000000000003','11001',NULL,NULL,'approve','done',NULL,'1804170001','Welly WSP','2018-04-25 16:12:58','1804170001','Welly WSP','2018-04-25 16:25:59'),('15246476165804','20180504000000000003','11002',NULL,NULL,'reject','done','REJECT','1804170001','Welly WSP','2018-04-25 16:13:36','1804170001','Welly WSP','2018-04-25 16:15:29'),('15246477295812','20180504000000000003','11001','11002','15246476165804','approve','done',NULL,'1804170001','Welly WSP','2018-04-25 16:15:29','1804170001','Welly WSP','2018-04-25 16:25:59'),('15246483592218','20180504000000000003','11002','11001','15246477295812','waiting','process',NULL,'1804170001','Welly WSP','2018-04-25 16:25:59',NULL,NULL,NULL);

UNLOCK TABLES;

/*Table structure for table `pegawai_cuti_tanggal` */

DROP TABLE IF EXISTS `pegawai_cuti_tanggal`;

CREATE TABLE `pegawai_cuti_tanggal` (
  `cuti_id` varchar(20) NOT NULL,
  `cuti_tanggal` date NOT NULL,
  PRIMARY KEY (`cuti_id`,`cuti_tanggal`),
  CONSTRAINT `pegawai_cuti_tanggal_ibfk_1` FOREIGN KEY (`cuti_id`) REFERENCES `pegawai_cuti` (`cuti_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `pegawai_cuti_tanggal` */

LOCK TABLES `pegawai_cuti_tanggal` WRITE;

insert  into `pegawai_cuti_tanggal`(`cuti_id`,`cuti_tanggal`) values ('20180425000000000001','2018-04-30'),('20180502000000000002','2018-05-02'),('20180504000000000003','2018-05-04');

UNLOCK TABLES;

/*Table structure for table `pegawai_izin` */

DROP TABLE IF EXISTS `pegawai_izin`;

CREATE TABLE `pegawai_izin` (
  `izin_id` varchar(20) NOT NULL,
  `user_id` varchar(10) DEFAULT NULL,
  `jenis_id` varchar(5) DEFAULT NULL,
  `struktur_cd` varchar(10) DEFAULT NULL,
  `izin_nomor` varchar(20) DEFAULT NULL,
  `izin_uraian` varchar(255) DEFAULT NULL,
  `izin_tanggal` date DEFAULT NULL,
  `izin_waktu_mulai` time DEFAULT NULL,
  `izin_waktu_selesai` time DEFAULT NULL,
  `izin_status` enum('waiting','approved','rejected','draft') DEFAULT 'draft',
  `izin_send_by` varchar(10) DEFAULT NULL,
  `izin_send_by_name` varchar(50) DEFAULT NULL,
  `izin_send_date` datetime DEFAULT NULL,
  `mdb` varchar(10) DEFAULT NULL,
  `mdb_name` varchar(50) DEFAULT NULL,
  `mdd` datetime DEFAULT NULL,
  PRIMARY KEY (`izin_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `pegawai_izin_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `pegawai` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `pegawai_izin` */

LOCK TABLES `pegawai_izin` WRITE;

insert  into `pegawai_izin`(`izin_id`,`user_id`,`jenis_id`,`struktur_cd`,`izin_nomor`,`izin_uraian`,`izin_tanggal`,`izin_waktu_mulai`,`izin_waktu_selesai`,`izin_status`,`izin_send_by`,`izin_send_by_name`,`izin_send_date`,`mdb`,`mdb_name`,`mdd`) values ('20180417000000000001','1804170008','IZ.01',NULL,NULL,'main','2018-04-18','00:00:00','00:00:00','waiting','1804170008','Salmuasih','2018-04-17 14:24:27','1804170008','Salmuasih','2018-04-17 14:24:27'),('20180502000000000002','1804170001','IZ.01','001.01.00','000002/IJIN/V/2018','TESTING APLIKASI DARI MY TASK','2018-05-02','00:00:00','00:00:00','waiting','1804170001','Welly WSP','2018-04-25 10:36:03','1804170001','Welly WSP','2018-04-25 10:39:48'),('20180504000000000003','1804170001','IZ.01','001.01.00','000003/IJIN/V/2018','TESTING TASK MANAJER','2018-05-04',NULL,NULL,'waiting','1804170001','Welly WSP','2018-04-25 15:43:02','1804170001','Welly WSP','2018-04-25 15:47:10');

UNLOCK TABLES;

/*Table structure for table `pegawai_izin_process` */

DROP TABLE IF EXISTS `pegawai_izin_process`;

CREATE TABLE `pegawai_izin_process` (
  `process_id` varchar(20) NOT NULL,
  `izin_id` varchar(20) DEFAULT NULL,
  `flow_id` varchar(5) DEFAULT NULL,
  `flow_revisi_id` varchar(5) DEFAULT NULL,
  `process_references_id` varchar(20) DEFAULT NULL,
  `process_st` enum('waiting','approve','reject') DEFAULT 'waiting',
  `action_st` enum('process','done') DEFAULT 'process',
  `catatan` text,
  `mdb` varchar(10) DEFAULT NULL,
  `mdb_name` varchar(50) DEFAULT NULL,
  `mdd` datetime DEFAULT NULL,
  `mdb_finish` varchar(10) DEFAULT NULL,
  `mdb_finish_name` varchar(50) DEFAULT NULL,
  `mdd_finish` datetime DEFAULT NULL,
  PRIMARY KEY (`process_id`),
  KEY `izin_id` (`izin_id`),
  CONSTRAINT `pegawai_izin_process_ibfk_1` FOREIGN KEY (`izin_id`) REFERENCES `pegawai_izin` (`izin_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `pegawai_izin_process` */

LOCK TABLES `pegawai_izin_process` WRITE;

insert  into `pegawai_izin_process`(`process_id`,`izin_id`,`flow_id`,`flow_revisi_id`,`process_references_id`,`process_st`,`action_st`,`catatan`,`mdb`,`mdb_name`,`mdd`,`mdb_finish`,`mdb_finish_name`,`mdd_finish`) values ('15239498675702','20180417000000000001','12002',NULL,NULL,'waiting','process',NULL,'1804170008','Salmuasih','2018-04-17 14:24:27','1804170008','Salmuasih','2018-04-17 14:24:31'),('15246273631701','20180502000000000002','12001',NULL,NULL,'approve','done',NULL,'1804170001','Welly WSP','2018-04-25 10:36:03','1804170001','Welly WSP','2018-04-26 08:06:05'),('15246457828589','20180504000000000003','12001',NULL,NULL,'approve','done',NULL,'1804170001','Welly WSP','2018-04-25 15:43:02','1804170001','Welly WSP','2018-04-25 15:48:04'),('15246460843321','20180504000000000003','12002',NULL,NULL,'approve','done','OK','1804170001','Welly WSP','2018-04-25 15:48:04','1804170001','Welly WSP','2018-04-25 15:57:06'),('1524646594596','20180504000000000003','12003',NULL,NULL,'waiting','process',NULL,'1804170001','Welly WSP','2018-04-25 15:56:34',NULL,NULL,NULL),('15247047658108','20180502000000000002','12002',NULL,NULL,'waiting','process',NULL,'1804170001','Welly WSP','2018-04-26 08:06:05',NULL,NULL,NULL);

UNLOCK TABLES;

/*Table structure for table `pegawai_jabatan_fungsional` */

DROP TABLE IF EXISTS `pegawai_jabatan_fungsional`;

CREATE TABLE `pegawai_jabatan_fungsional` (
  `data_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` varchar(10) DEFAULT NULL,
  `jabatan_fungsional_id` varchar(15) DEFAULT NULL,
  `tanggal_mulai` date DEFAULT NULL,
  `tanggal_selesai` date DEFAULT NULL,
  `jabatan_status` enum('1','0') DEFAULT '0',
  `jabatan_default` enum('1','0') DEFAULT '0',
  `mdb` varchar(10) DEFAULT NULL,
  `mdb_name` varchar(50) DEFAULT NULL,
  `mdd` datetime DEFAULT NULL,
  PRIMARY KEY (`data_id`),
  KEY `jabatan_fungsional_id` (`jabatan_fungsional_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `pegawai_jabatan_fungsional_ibfk_1` FOREIGN KEY (`jabatan_fungsional_id`) REFERENCES `data_jabatan_fungsional` (`jabatan_fungsional_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `pegawai_jabatan_fungsional_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `pegawai` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `pegawai_jabatan_fungsional` */

LOCK TABLES `pegawai_jabatan_fungsional` WRITE;

UNLOCK TABLES;

/*Table structure for table `pegawai_jabatan_struktural` */

DROP TABLE IF EXISTS `pegawai_jabatan_struktural`;

CREATE TABLE `pegawai_jabatan_struktural` (
  `data_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` varchar(10) DEFAULT NULL,
  `jabatan_struktural_id` varchar(15) DEFAULT NULL,
  `tanggal_sk` date DEFAULT NULL,
  `nomor_sk` varchar(50) DEFAULT NULL,
  `pejabat_sk` varchar(50) DEFAULT NULL,
  `tanggal_mulai` date DEFAULT NULL,
  `tanggal_selesai` date DEFAULT NULL,
  `jabatan_status` enum('1','0') DEFAULT '0',
  `jabatan_default` enum('1','0') DEFAULT '0',
  `lampiran_file_path` varchar(255) DEFAULT NULL,
  `lampiran_file_name` varchar(255) DEFAULT NULL,
  `mdb` varchar(10) DEFAULT NULL,
  `mdb_name` varchar(50) DEFAULT NULL,
  `mdd` datetime DEFAULT NULL,
  PRIMARY KEY (`data_id`),
  KEY `jabatan_struktural_id` (`jabatan_struktural_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `pegawai_jabatan_struktural_ibfk_1` FOREIGN KEY (`jabatan_struktural_id`) REFERENCES `data_jabatan_struktural` (`jabatan_struktural_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `pegawai_jabatan_struktural_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `pegawai` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `pegawai_jabatan_struktural` */

LOCK TABLES `pegawai_jabatan_struktural` WRITE;

UNLOCK TABLES;

/*Table structure for table `pegawai_kehadiran` */

DROP TABLE IF EXISTS `pegawai_kehadiran`;

CREATE TABLE `pegawai_kehadiran` (
  `kehadiran_id` varchar(20) NOT NULL COMMENT 'ini formatnya apa?',
  `user_id` varchar(10) DEFAULT NULL,
  `kehadiran_tanggal` date DEFAULT NULL,
  `kehadiran_jadwal_masuk` time DEFAULT NULL,
  `kehadiran_jadwal_pulang` time DEFAULT NULL,
  `kehadiran_jam_masuk` time DEFAULT NULL,
  `kehadiran_jam_pulang` time DEFAULT NULL,
  `kehadiran_keterangan` varchar(100) DEFAULT NULL,
  `kehadiran_status` enum('manual','automatic') DEFAULT NULL,
  `kehadiran_verified` enum('1','0') DEFAULT '1' COMMENT '1. final / 0. waiting / 2. canceled',
  `jam_kerja` time DEFAULT NULL,
  `keterlambatan` time DEFAULT NULL COMMENT 'satuan detik.',
  `pulang_cepat` time DEFAULT NULL,
  `mesin_id` varchar(2) DEFAULT NULL,
  `mesin_ip` varchar(20) DEFAULT NULL,
  `mdb` varchar(10) DEFAULT NULL,
  `mdb_name` varchar(50) DEFAULT NULL,
  `mdd` datetime DEFAULT NULL,
  PRIMARY KEY (`kehadiran_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `pegawai_kehadiran_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `pegawai` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `pegawai_kehadiran` */

LOCK TABLES `pegawai_kehadiran` WRITE;

UNLOCK TABLES;

/*Table structure for table `pegawai_lembur` */

DROP TABLE IF EXISTS `pegawai_lembur`;

CREATE TABLE `pegawai_lembur` (
  `user_id` varchar(10) NOT NULL,
  `overtime_id` varchar(20) NOT NULL,
  `mdb` varchar(10) DEFAULT NULL,
  `mdb_name` varchar(50) DEFAULT NULL,
  `mdd` datetime DEFAULT NULL,
  PRIMARY KEY (`user_id`,`overtime_id`),
  KEY `overtime_id` (`overtime_id`),
  CONSTRAINT `pegawai_lembur_ibfk_1` FOREIGN KEY (`overtime_id`) REFERENCES `surat_lembur` (`overtime_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `pegawai_lembur_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `pegawai` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `pegawai_lembur` */

LOCK TABLES `pegawai_lembur` WRITE;

insert  into `pegawai_lembur`(`user_id`,`overtime_id`,`mdb`,`mdb_name`,`mdd`) values ('1804170001','20180425000000000001',NULL,NULL,NULL),('1804170001','20180501000000000005',NULL,NULL,NULL),('1804170007','20180425000000000002',NULL,NULL,NULL),('1804170007','20180426000000000006',NULL,NULL,NULL),('1804170007','20180426000000000008',NULL,NULL,NULL),('1804170007','20180501000000000005',NULL,NULL,NULL),('1804170007','20180517000000000010',NULL,NULL,NULL),('1804170008','20180425000000000001',NULL,NULL,NULL),('1804170008','20180425000000000002',NULL,NULL,NULL),('1804170008','20180425000000000003',NULL,NULL,NULL),('1804170008','20180425000000000004',NULL,NULL,NULL),('1804170008','20180426000000000007',NULL,NULL,NULL),('1804170008','20180426000000000009',NULL,NULL,NULL),('1804170008','20180501000000000005',NULL,NULL,NULL),('1804170009','20180425000000000004',NULL,NULL,NULL),('1804170009','20180426000000000006',NULL,NULL,NULL);

UNLOCK TABLES;

/*Table structure for table `pegawai_status` */

DROP TABLE IF EXISTS `pegawai_status`;

CREATE TABLE `pegawai_status` (
  `user_id` varchar(10) NOT NULL,
  `tahun` year(4) NOT NULL,
  `pegawai_status` enum('working','resign') DEFAULT 'working',
  `tanggal_keluar` date DEFAULT NULL,
  `catatan` text,
  `mdb` varchar(10) DEFAULT NULL,
  `mdb_name` varchar(50) DEFAULT NULL,
  `mdd` datetime DEFAULT NULL,
  PRIMARY KEY (`user_id`,`tahun`),
  CONSTRAINT `pegawai_status_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `pegawai` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `pegawai_status` */

LOCK TABLES `pegawai_status` WRITE;

insert  into `pegawai_status`(`user_id`,`tahun`,`pegawai_status`,`tanggal_keluar`,`catatan`,`mdb`,`mdb_name`,`mdd`) values ('1804170002',2018,'working',NULL,NULL,'1','Welly Widodo Sindu Putra','2018-04-17 13:41:03'),('1804170003',2018,'working',NULL,NULL,'1804170001','Welly Widodo Sindu Putra','2018-04-17 13:50:37'),('1804170004',2018,'working',NULL,NULL,'1804170001','Welly Widodo Sindu Putra','2018-04-17 13:51:29'),('1804170005',2018,'working',NULL,NULL,'1804170001','Welly Widodo Sindu Putra','2018-04-17 13:52:40'),('1804170006',2018,'working',NULL,NULL,'1804170001','Welly Widodo Sindu Putra','2018-04-17 13:54:16'),('1804170007',2018,'working',NULL,NULL,'1804170001','Welly Widodo Sindu Putra','2018-04-17 13:54:54'),('1804170008',2018,'working',NULL,NULL,'1804170001','Welly Widodo Sindu Putra','2018-04-17 14:02:17'),('1804170009',2018,'working',NULL,NULL,'1804170001','Welly Widodo Sindu Putra','2018-04-17 14:03:54'),('1810220001',2018,'working',NULL,NULL,'1804170001','Welly WSP','2018-10-22 14:31:37');

UNLOCK TABLES;

/*Table structure for table `pegawai_tugas_harian` */

DROP TABLE IF EXISTS `pegawai_tugas_harian`;

CREATE TABLE `pegawai_tugas_harian` (
  `tugas_id` varchar(20) NOT NULL,
  `user_id` varchar(10) DEFAULT NULL,
  `struktur_cd` varchar(10) DEFAULT NULL,
  `project_id` varchar(10) DEFAULT NULL,
  `tugas_judul` varchar(50) DEFAULT NULL,
  `tugas_uraian` varchar(255) DEFAULT NULL,
  `tugas_link` varchar(100) DEFAULT NULL,
  `tugas_tanggal` date DEFAULT NULL,
  `mdb` varchar(10) DEFAULT NULL,
  `mdb_name` varchar(50) DEFAULT NULL,
  `mdd` datetime DEFAULT NULL,
  PRIMARY KEY (`tugas_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `pegawai_tugas_harian` */

LOCK TABLES `pegawai_tugas_harian` WRITE;

UNLOCK TABLES;

/*Table structure for table `pegawai_unit_kerja` */

DROP TABLE IF EXISTS `pegawai_unit_kerja`;

CREATE TABLE `pegawai_unit_kerja` (
  `data_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` varchar(10) DEFAULT NULL,
  `struktur_cd` varchar(10) DEFAULT NULL,
  `tanggal_sk` date DEFAULT NULL,
  `nomor_sk` varchar(50) DEFAULT NULL,
  `pejabat_sk` varchar(50) DEFAULT NULL,
  `unit_kerja_status` enum('1','0') DEFAULT '0',
  `lampiran_file_path` varchar(255) DEFAULT NULL,
  `lampiran_file_name` varchar(255) DEFAULT NULL,
  `mdb` varchar(10) DEFAULT NULL,
  `mdb_name` varchar(50) DEFAULT NULL,
  `mdd` datetime DEFAULT NULL,
  PRIMARY KEY (`data_id`),
  KEY `struktur_cd` (`struktur_cd`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `pegawai_unit_kerja_ibfk_1` FOREIGN KEY (`struktur_cd`) REFERENCES `data_struktur_organisasi` (`struktur_cd`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `pegawai_unit_kerja_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `pegawai` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;

/*Data for the table `pegawai_unit_kerja` */

LOCK TABLES `pegawai_unit_kerja` WRITE;

insert  into `pegawai_unit_kerja`(`data_id`,`user_id`,`struktur_cd`,`tanggal_sk`,`nomor_sk`,`pejabat_sk`,`unit_kerja_status`,`lampiran_file_path`,`lampiran_file_name`,`mdb`,`mdb_name`,`mdd`) values (1,'1804170001','001.01.00','0000-00-00','-','','1',NULL,NULL,'1804170001','Welly Widodo Sindu Putra','2018-04-17 13:56:13'),(2,'1804170007','001.01.00','2018-04-02','-','','1',NULL,NULL,'1804170001','Welly Widodo Sindu Putra','2018-04-17 13:59:50'),(3,'1804170002','001.05.00','2018-04-02','-','','1',NULL,NULL,'1804170001','Welly Widodo Sindu Putra','2018-04-17 14:00:23'),(4,'1804170006','001.05.00','2018-04-02','-','','1',NULL,NULL,'1804170001','Welly Widodo Sindu Putra','2018-04-17 14:00:45'),(5,'1804170003','001.06.00','2018-04-02','-','','1',NULL,NULL,'1804170001','Welly Widodo Sindu Putra','2018-04-17 14:01:06'),(7,'1804170005','001.06.00','2018-04-02','-','','1',NULL,NULL,'1804170001','Welly Widodo Sindu Putra','2018-04-17 14:01:54'),(8,'1804170009','001.01.00','2018-04-02','-','','1',NULL,NULL,'1804170001','Welly Widodo Sindu Putra','2018-04-17 14:04:43'),(9,'1804170008','001.01.00','2018-04-02','-','','1',NULL,NULL,'1804170001','Welly Widodo Sindu Putra','2018-04-17 14:05:01');

UNLOCK TABLES;

/*Table structure for table `project_budget_group` */

DROP TABLE IF EXISTS `project_budget_group`;

CREATE TABLE `project_budget_group` (
  `group_id` varchar(5) NOT NULL,
  `group_title` varchar(100) DEFAULT NULL,
  `group_number` int(11) unsigned DEFAULT NULL,
  `mdb` varchar(10) DEFAULT NULL,
  `mdb_name` varchar(50) DEFAULT NULL,
  `mdd` datetime DEFAULT NULL,
  PRIMARY KEY (`group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `project_budget_group` */

LOCK TABLES `project_budget_group` WRITE;

insert  into `project_budget_group`(`group_id`,`group_title`,`group_number`,`mdb`,`mdb_name`,`mdd`) values ('BG.00','Biaya Operasional',1,NULL,NULL,NULL),('BG.02','Biaya Marketing',2,NULL,NULL,NULL),('BG.03','Biaya Peralatan dan Infrastruktur',3,NULL,NULL,NULL),('BG.04','Biaya Lelang',4,NULL,NULL,NULL);

UNLOCK TABLES;

/*Table structure for table `project_kontrak` */

DROP TABLE IF EXISTS `project_kontrak`;

CREATE TABLE `project_kontrak` (
  `kontrak_id` varchar(20) NOT NULL,
  `project_id` varchar(10) DEFAULT NULL,
  `struktur_cd` varchar(10) DEFAULT NULL,
  `judul_kontrak` varchar(255) DEFAULT NULL,
  `nomor_kontrak` varchar(50) DEFAULT NULL,
  `tanggal_kontrak` date DEFAULT NULL,
  `tanggal_selesai` date DEFAULT NULL,
  `nilai_pajak` double unsigned DEFAULT NULL,
  `nilai_kontrak` double unsigned DEFAULT NULL,
  `lama_penyelesaian` int(11) unsigned DEFAULT NULL,
  `jumlah_termin` int(11) unsigned DEFAULT '1',
  `create_by` varchar(10) DEFAULT NULL,
  `create_by_name` varchar(50) DEFAULT NULL,
  `create_date` datetime DEFAULT NULL,
  `mdb` varchar(10) DEFAULT NULL,
  `mdb_name` varchar(50) DEFAULT NULL,
  `mdd` datetime DEFAULT NULL,
  PRIMARY KEY (`kontrak_id`),
  KEY `perusahaan_id` (`struktur_cd`),
  KEY `project_id` (`project_id`),
  CONSTRAINT `project_kontrak_ibfk_3` FOREIGN KEY (`project_id`) REFERENCES `projects` (`project_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `project_kontrak` */

LOCK TABLES `project_kontrak` WRITE;

UNLOCK TABLES;

/*Table structure for table `projects` */

DROP TABLE IF EXISTS `projects`;

CREATE TABLE `projects` (
  `project_id` varchar(10) NOT NULL,
  `client_id` varchar(5) DEFAULT NULL,
  `struktur_cd` varchar(10) DEFAULT NULL,
  `jenis_kode_kegiatan` varchar(1) DEFAULT NULL,
  `project_name` varchar(255) NOT NULL,
  `project_alias` varchar(50) DEFAULT NULL,
  `project_desc` varchar(255) DEFAULT NULL,
  `project_start` date DEFAULT NULL,
  `project_end` date DEFAULT NULL,
  `project_st` varchar(30) DEFAULT NULL,
  `mdb` varchar(10) DEFAULT NULL,
  `mdb_name` varchar(50) DEFAULT NULL,
  `mdd` datetime DEFAULT NULL,
  PRIMARY KEY (`project_id`),
  KEY `client_id` (`client_id`),
  CONSTRAINT `projects_ibfk_2` FOREIGN KEY (`client_id`) REFERENCES `projects_clients` (`client_id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `projects` */

LOCK TABLES `projects` WRITE;

insert  into `projects`(`project_id`,`client_id`,`struktur_cd`,`jenis_kode_kegiatan`,`project_name`,`project_alias`,`project_desc`,`project_start`,`project_end`,`project_st`,`mdb`,`mdb_name`,`mdd`) values ('0000000001','00001','001.01.00','B','PEMBUATAN APLIKASI EDAPEM','SD-DAPEM-MANDIRI-2013','Menghitung Tunjangan Hari Tua','2013-09-01','2013-10-31','Closed','20',NULL,'2014-04-25 09:26:13'),('0000000002','00018','001.01.00','B','SISTEM EVATUASI KAPASITAS DAN ANALISA PEMETAAN RUTE','SD-PEMETAAN-DEPHUBUD-2013','Sistem Evaluasi Kapasitas dan Analisa Pemetaan Rute dan Jaringan Angkutan Udara Nasional','2013-06-18','2013-10-18','Maintenance','20',NULL,'2014-04-22 09:45:26'),('0000000005','00002','001.01.00','B','SISTEM INFORMASI BASIS DATA SINGLE INSPECTION','SD-SINGLE-SOETTA-2013','Pengadaan Sistem Informasi Basis Data Single Inspection Lingkungan Kerja Kantor OToritas Bandar Udar','2013-10-31','2014-04-28','Maintenance','16',NULL,'2014-09-02 16:13:27'),('0000000006','00002','001.01.00','B','Aplikasi SMS Center','SD-SMSCENTER-SOETTA-2013','SMS Center aplikasi dengan antar muka web dan engine sms java yang memberikan fasilitas untuk menerima dan mengirimkan pesan dalam bentuk sms yang berfungsi untuk memudahkan layanan pengaduan dan informasi mengenai Pas.','2013-05-14','2013-09-30','Closed','20',NULL,'2014-04-22 10:03:57'),('0000000007','00011','001.01.00','B','APLIKASI TEKNOLOGI INFORMASI DAN KOMUNIKASI ANGKUTAN UDARA DAN PESAWAT UDARA','SD-TIK-BALI-2013','Aplikasi Teknologi Informasi Dan Komunikasi Kantor Otoritas Bandar Udara wilayah IV','2013-11-10','2014-05-08','Maintenance','20',NULL,'2014-04-22 10:17:45'),('0000000008','00007','001.01.00','B','SISTEM INFORMASI MANAJEMEN INVESTIGASI','SD-SIMVEST-KNKT-2013','Sistem Informasi Manajemen Investigasi Komite Nasional Keselamatan Transportasi','2013-09-12','2014-04-30','Maintenance','20',NULL,'2014-05-26 11:32:42'),('0000000009','00002','5','B','TRACKING MONITORING PAS BANDAR UDARA','SD-TRACKING-SOETTA-2013','Tracking Monitoring Pas Bandar Udara','2013-11-20','2014-11-14','Maintenance','1',NULL,'2014-04-26 10:01:24'),('0000000010','00012','001.01.00','B','APLIKASI TERMASUK JARINGAN PELAYANAN PAS BANDAR UDARA','SD-SIMPAS-BALIKPAPAN-2013-FS','Pembuatan jaringan dan aplikasi PAS Bandara','2013-12-18','2014-06-15','Maintenance','16',NULL,'2015-04-06 14:38:42'),('0000000011','00008','001.01.00','B','PENGEMBANGAN LAYANAN SISTEM INFORMASI TERPADU','SD-WEBSITE-MUSI-2013','Pengembangan Layanan Sistem Informasi Publik Terpadu Kabupaten Musi Banyuasin','2013-09-24','2013-11-24','Closed','20',NULL,'2014-04-22 10:23:43'),('0000000012','00002','001.01.00','B','APLIKASI PAS BANDAR UDARA','SD-APMS1-SOETTA-2013','-','2013-10-01','2013-10-01','Closed','20',NULL,'2014-04-22 10:04:32'),('0000000016','00002','001.01.00','B','PENGEMBANGAN APLIKASI PAS BANDAR UDARA','SD-APMS2-SOETTA-2013','-','2013-09-30','2013-12-28','Maintenance','20',NULL,'2014-04-22 10:05:08'),('0000000017','00001','001.01.00','B','APLIKASI PERHITUNGAN POOLING BENEFIT NOTIONAL POLLING','SD-NOPOLLING-MANDIRI-2013','Mandiri Notional Polling','2013-10-01','2013-10-07','Closed','20',NULL,'2014-04-22 09:41:40'),('0000000018','00001','001.01.00','B','APLIKASI CONVERTER UBP DAN TAX PAYMENT REPORT','SD-UBP-MANDIRI-2013','-','2013-10-02','2013-10-06','Closed','20',NULL,'2014-04-22 09:43:19'),('0000000019','00001','001.01.00','B','APLIKASI FEE GENERATOR','SD-MFG-MANDIRI-2013','Aplikasi MFG Mandiri','2013-10-01','2013-11-01','Closed','20',NULL,'2014-04-25 09:25:54'),('0000000020','00002','001.01.00','B','Aplikasi Screening Keamanan Bandar Udara','SD-SCREENING-SOETTA-2013','Open','2013-11-14','2013-12-31','Maintenance','20',NULL,'2014-04-25 09:23:58'),('0000000021','00011','001.01.00','B','SISTEM INFORMASI MANAJEMEN PAS BANDAR UDARA','SD-SIMPAS-BALI-2013','Aplikasi pengolahan Pas Bandara','2013-10-08','2013-10-08','Closed','20',NULL,'2014-04-22 10:18:49'),('0000000022','00014','001.01.00','B','PENGADAAN PERALATAN PENUNJANG PAS BANDARA','SD-CBT-MANADO-2013','PENGADAAN PERALATAN PENUNJANG PAS BANDARA','2013-08-01','2013-12-31','Maintenance','1',NULL,'2014-04-25 09:27:00'),('0000000023','00015','001.01.00','B','SISTEM INFORMASI MANAJEMEN PAJAK DAERAH','SD-SIMPADA-KEBUMEN-2013','Sistem Informasi Pajak Daerah','2013-08-01','2013-08-01','Closed','20',NULL,'2014-04-22 09:46:58'),('0000000024','00016','001.01.00','B','UNFPA (Training Android)','UNFPA','Training to Develop Android Based Aplication for Questionnaire','2013-09-23','2013-09-27','closed','20',NULL,'2013-10-10 10:50:12'),('0000000025','00001','001.01.00','B','PNBP-POLRI','SD-PNBPPOLRI-MANDIRI-2013','Sistem Pelaporan Pembayaran PNBP-POLRI','2013-10-21','2013-11-20','Closed','20',NULL,'2014-04-22 09:43:55'),('0000000026','00019','001.01.00','B','Website PPE Kalimantan','SD-WEBSITE-PPEKAL-2013','Website','2013-11-30','2013-11-30','Closed','20',NULL,'2014-04-25 09:25:34'),('0000000027','00020','001.01.00','B','SISTEM INFORMASI MANAJEMEN ASSET','SD-SIMASSET-AMIKOM-2014','Project Sudah Selesai','2014-01-01','2014-05-31','Closed','20',NULL,'2014-06-02 11:29:59'),('0000000028','00006','001.01.00','B','TE SOFTDEV','SD-RISETTETOOLS-TE-2014','Pekerjaan-pekerjaan diluar project','2014-01-01','2014-12-31','Development','1',NULL,'2014-04-25 11:08:13'),('0000000029','00006','001.01.00','B','Riset Template TE','SD-RISETTEMPLATETE-TE-2014','Riset Template TE','2014-01-01','2015-01-31','Development','1',NULL,'2014-04-25 11:16:01'),('0000000030','00002','5','B','NATIONAL SINGLE WINDOWS AIRPORTNET','IS-NSWPEMELIHARAAN-SOETTA-2014','NSW','2014-02-03','2014-02-28','Marketing','20',NULL,'2014-05-26 15:51:09'),('0000000037','00023','6','B','ISP NTT INDONESIA','TC-ISP-NTT-2014','-','2014-04-05','2015-04-13','Maintenance','87',NULL,'2014-10-14 13:50:42'),('0000000038','00024','6','B','ISP AMPTA','TC-ISP-AMPTA-2014','-','2014-04-05',NULL,'Marketing','20',NULL,'2014-04-22 10:58:03'),('0000000039','00025','6','B','ISP INDOMEDIA SALATIGA','TC-INDOMEDIA-SALATIGA-2014','-','2014-04-05',NULL,'Marketing','20',NULL,'2014-04-22 14:13:37'),('0000000040','00026','6','B','ISP USM','TC-ISP-USM-2014','-','2014-04-05',NULL,'Marketing','20',NULL,'2014-04-22 14:00:15'),('0000000041','00002','6','B','INTERNET KOBU I JAKARTA','TC-INTERNET-KOBU1-2014','-','2014-04-05',NULL,'Development','20',NULL,'2014-04-22 13:39:27'),('0000000042','00011','6','B','INTERNET OTBAND BALI','TC-INTERNET-BALI-2014','Baru akan up kembali target bulan September','2014-04-05',NULL,'Canceled','87',NULL,'2014-04-25 11:55:23'),('0000000043','00018','6','B','INTERNET DEPHUBUD','TC-INTERNET-DEPHUBUD-2014','-','2014-04-05',NULL,'Marketing','20',NULL,'2014-04-22 14:10:51'),('0000000044','00027','7','B','E-Catalog LKPP','MK-ECATALOG-LKPP-2014','-','2014-04-05',NULL,'Marketing','20',NULL,'2014-04-22 14:57:32'),('0000000045','00032','6','B','INTERNET XL','TC-INTERNET-XL-2014','-','2014-04-05',NULL,'Development','20',NULL,'2014-04-22 14:00:47'),('0000000046','00006','6','B','TE ISP','TC-ISP-TE-2014','Pekerjaan-pekerjaan yang terkait dengan tugas ISP','2014-01-01','2014-12-31','Development','20',NULL,'2014-04-22 10:58:22'),('0000000047','00006','2','A','TE FINANCES','FA-Keu-TE-2014','-','2014-01-01','2014-12-31','Development','20',NULL,'2014-04-22 10:47:40'),('0000000048','00008','5','B','INFRASTRUKTUR FIBER OPTIK','IS-FIBEROPTIK-MUBA-2014-TE','-','2014-04-05',NULL,'Marketing','100',NULL,'2014-09-17 10:49:01'),('0000000049','00002','5','B','CCTV OTBAND SOETTA','IS-CCTV-SOETTA-2014-TE','-','2014-04-05',NULL,'Maintenance','100',NULL,'2015-06-20 11:08:11'),('0000000050','00002','5','B','SISKOCC OTBAND SOETTA','IS-SISKOCC-SOETTA-2014','-','2014-04-05',NULL,'Maintenance','20',NULL,'2014-04-25 08:41:55'),('0000000051','00040','5','B','CCTV OTBAND PADANG','IS-CCTV-PADANG-2014','-','2014-04-05',NULL,'Maintenance','20',NULL,'2014-04-22 13:48:49'),('0000000052','00041','5','B','SECURITY SERVER MELON','IS-SECURITYSERVER-MELON-2014','-','2014-04-05',NULL,'Maintenance','20',NULL,'2014-04-22 13:58:45'),('0000000053','00006','3','A','TE HRD','HR-HRD-TE-2014','Pekerjaan-pekerjaan yang terkait dengan tugas HRD','2014-01-01','2014-12-31','Development','20',NULL,'2014-04-22 15:43:37'),('0000000054','00040','001.01.00','B','PENGEMBANGAN SISTEM INFORMASI PAS BANDARA','SD-SIMPAS-PADANG-2014-APT','SIMPAS PADANG','2014-06-18','2014-12-14','Development','16',NULL,'2014-08-26 10:35:21'),('0000000055','00014','001.01.00','B','Pengadaan & instalasi Sistem Komputerisasi Terpadu PAS Bandara Beserta Aplikasinya','SD-SIMPAS-MANADO-2014-TE','Menunggu waktu lelang','2014-08-01','2014-12-30','Development','16',NULL,'2014-08-26 10:36:18'),('0000000056','00047','001.01.00','B','E-MONEV','SD-EMONEV-KEMENPU-2014','E-MONEV','2014-04-21','2014-05-31','Development','16',NULL,'2014-06-24 09:58:37'),('0000000057','00011','001.01.00','B','PEMBUATAN APLIKASI PAS BOOKING PELAYANAN PAS BANDARA','SD-PASBOOKING-BALI-2014-SK','SIMPAS BOOKING BALI','2014-04-07',NULL,'Development','16',NULL,'2015-04-13 09:19:54'),('0000000058','00011','001.01.00','B','SISTEM INFORMASI KEPEGAWAIAN','SD-SIMPEG-BALI-2014-APT','SIMPEG BALI','2014-07-08','2014-12-04','Development','16',NULL,'2014-10-07 15:08:35'),('0000000059','00011','001.01.00','B','SISTIM ANTRIAN PELAYANAN PUBLIK','SD-ANTRIAN-BALI-2014-DM','ANTRIAN SIMPAS BALI (CV. Diginet Media)','2014-07-08','2014-10-05','Development','16',NULL,'2014-08-26 10:34:33'),('0000000060','00011','001.01.00','B','PEMBUATAN APLIKASI DATABASE AUDITOR INSPECTOR P2BU','SD-INSPECTOR-BALI-2014-FS','INSPEKTOR BALI','2014-04-07',NULL,'Development','16',NULL,'2014-08-26 10:30:08'),('0000000061','00018','001.01.00','B','APLIKASI FLIGHT APPROVAL ONLINE ANGUD','SD-FAONLINE-DEPHUBUD-2014','FA ONLINE ANGUD - Penunjukan Langsung','2014-04-05',NULL,'Failed','20',NULL,'2014-05-26 11:29:46'),('0000000062','00006','7','B','TE MARKETING JAKARTA','MK-MARKETING-TEJKT-2014','Pekerjaan-pekerjaan yang terkait dengan tugas MARKETING Jakarta','2014-04-05',NULL,'Development','20',NULL,'2014-04-22 14:04:55'),('0000000063','00002','001.01.00','B','APLIKASI PAS BANDAR UDARA BERBASIS ONLINE','SD-APMSONLINE-SOETTA-2014-TE','Pekerjaan Project 6 bulan, start 3 Juni 2014','2014-06-03','2014-11-03','Development','16',NULL,'2014-08-26 10:29:19'),('0000000064','00011','001.01.00','B','PENGEMBANGAN DATA CENTER DAN SYSTEM APLIKASI SURAT MENYURAT SECARA TERPADU','SD-EARSIP-BALI-2014-BOC','PL CV. Bendera Bali Orange Communication','2014-07-08','2014-09-05','Development','16',NULL,'2014-08-26 10:38:09'),('0000000065','00011','001.01.00','B','PENGEMBANGAN WEBSITE KANTOR OTORITAS BANDARA WILAYAH IV','SD-WEBINFO-BALI-2014','WEB INFO BALI','2014-04-07','2014-04-30','Failed','1',NULL,'2014-04-25 10:13:29'),('0000000066','00006','7','B','Pengembangan Website','MK-WEBSITE-TE-2014','Pembuatan dan Pengembangan Website TE','2014-02-17',NULL,'Development','20',NULL,'2014-04-22 14:57:57'),('0000000067','00018','4','B','STUDY SISTEM PELAYANAN PADA JASA PENUNJANG ANGKUTAN UDARA','AD-STUDYSISLAYAN-DEPHUBUD-2014','STUDY SISTEM PELAYANAN PADA  JASA PENUNJANG ANGKUTAN UDARA','2014-04-07','2014-04-30','Canceled','61',NULL,'2014-05-02 14:52:34'),('0000000068','00006','4','B','PROJECT ADMINISTRATION','AD-ADMIN-TE-2014','Pekerjaan-pekerjaan yang terkait dengan tugas ADMINISTRASI PROJECT','2014-04-07',NULL,'Lelang','20',NULL,'2014-04-22 15:00:06'),('0000000069','00006','5','B','TE INFRASTRUKTUR','IS-INFRA-TE-2014','Pekerjaan-pekerjaan yang terkait dengan tugas infrastruktur','2014-04-01','2014-12-31','Development','20',NULL,'2014-04-22 14:06:05'),('0000000070','00006','7','B','TE MARKETING','MK-MARKETING-TE-2014','Pekerjaan-pekerjaan yang terkait dengan tugas marketing','2014-04-01','2014-12-31','Development','20',NULL,'2014-04-22 09:30:05'),('0000000071','00006','9','B','TECS','CS-TECS-TE-2014','Pekerjaan-pekerjaan TECS','2014-04-01','2014-12-31','Development','20',NULL,'2014-04-22 15:13:32'),('0000000072','00060','5','B','LAN Gedung Perkantoran &  Gedung Operasional','IS-LANAP2-PADANG-2014','Proposal Pekerjaan','2014-04-07',NULL,'Marketing','20',NULL,'2014-04-22 14:05:45'),('0000000074','00043','9','B','Pengadaan Hardware Software Project PT MSV','CS-PARTPC-MSV-2014','Pengadaan Peripheral dan Komputer Disain','2014-04-02','2014-04-19','Marketing','20',NULL,'2014-04-22 15:13:38'),('0000000075','00065','9','B','Pengadaan Komputer Lab XII STMIK AMIKOM','CS-PCLABXII-LABKOM-2014','Pengadaan Komputer Desktop dengan peripheral pendukung','2014-04-14','2014-04-30','Development','20',NULL,'2014-04-22 15:13:25'),('0000000076','00037','6','B','Dedicated IIX Nusantara SMG','TC-DEDICATEDIIX-DHECYBER-2014','Internet 2 Mbps','2011-08-01','2014-08-11','Maintenance','20',NULL,'2014-04-22 14:54:46'),('0000000077','00026','6','B','Dedicated Internet USM','TC-DEDICATED-USM-2014','Dedicated Internet 10 Mbps','2011-09-01','2014-09-01','Maintenance','20',NULL,'2014-04-22 14:55:10'),('0000000078','00002','6','B','Internet Otband JKT','TC-INTERNET-KOBU1-2014','Internet 5 Mbps','2013-01-02','2015-01-02','Maintenance','20',NULL,'2014-04-22 14:44:31'),('0000000079','00067','6','B','Internet PDAM TGR','TC-INTERNET-PDAMTGR-2014','Internet 2 Mbps','2013-01-14','2015-01-14','Maintenance','20',NULL,'2014-04-22 14:46:31'),('0000000080','00048','6','B','Dedicated Internet UNissula','TC-DEDICATED-UNISSULA-2014','Dedicated Internet 30 Mbps','2013-02-28','2015-02-28','Maintenance','20',NULL,'2014-04-22 14:52:21'),('0000000081','00045','6','B','Dedicated Internet SKill','TC-DEDICATED-SKILL-2014','Dedicated Internet 10 Mbps','2013-03-04','2015-03-04','Maintenance','20',NULL,'2014-04-22 14:50:08'),('0000000082','00020','6','B','Dedicated Interdet Amikom','TC-DEDICATED-AMIKOM-2014','Dedicated Internet 40 Mbps Total','2013-04-01','2015-04-01','Maintenance','20',NULL,'2014-04-22 14:31:10'),('0000000083','00022','6','B','Dedicated Internet Apikri','TC-DEDICATED-APIKRI-2014','Dedicated Internet  1 Mbps','2013-05-01','2015-05-01','Maintenance','20',NULL,'2014-04-22 14:33:39'),('0000000084','00034','6','B','Dedicated Internet Bumitel','TC-DEDICATED-BUMITEL-2014','Dedicated Internet 1,5 Mbps','2013-05-01','2015-05-01','Maintenance','20',NULL,'2014-04-22 14:37:09'),('0000000085','00038','6','B','Dedicated Internet Hamzanwadi','TC-DEDICATED-HAMZANWANDI-2014','Dedicated Internet 10 Mbps','2013-05-14','2014-05-14','Maintenance','20',NULL,'2014-04-22 14:39:10'),('0000000086','00046','6','B','Dedicated Internet SMA 1 Sleman','TC-DEDICATED-SMA1SLEMAN-2014','Dedicated Internet 2 Mbps','2013-05-21',NULL,'Maintenance','20',NULL,'2014-04-22 14:51:23'),('0000000087','00044','6','B','Dedicated Internet STIE SBI','TC-DEDICATED-STIESBI-2014','Dedicated Internet 1,5 Mbps','2013-07-04',NULL,'Maintenance','20',NULL,'2014-04-22 14:51:54'),('0000000088','00052','6','B','Dedicated Internet Pogung','TC-DEDICATED-POGUNG-2014','Dedicated Internet 1 Mbps','2013-08-15',NULL,'Maintenance','20',NULL,'2014-04-22 14:47:28'),('0000000089','00051','6','B','Dedicated Internet UWMY','TC-DEDICATED-UWMY-2014','Dedicated Internet 2 Mbps','2013-08-19',NULL,'Maintenance','20',NULL,'2014-04-22 14:53:24'),('0000000090','00053','6','B','Dedicated Internet Victoria Hotel','TC-DEDICATED-VICTORIA-2014','Dedicated Internet 1 Mbps','2013-08-31',NULL,'Maintenance','20',NULL,'2014-04-22 14:53:52'),('0000000091','00029','6','B','Dedicated Internet ABA','TC-DEDICATED-ABA-2014','Dedicated Internet 2 Mbps','2013-11-25',NULL,'Maintenance','20',NULL,'2014-04-22 14:30:42'),('0000000092','00033','6','B','Dedicated Internet Bethesda','TC-DEDICATED-BETHESDA-2014','Dedicated Internet  5 Mbps','2013-11-25',NULL,'Maintenance','20',NULL,'2014-04-22 14:34:06'),('0000000093','00050','6','B','Dedicated Internet sabo','TC-DEDICATED-SABO-2014','Dedicated Internet 3 Mbps','2013-12-30',NULL,'Maintenance','20',NULL,'2014-04-22 14:49:42'),('0000000094','00035','6','B','Dedicated Internet BPTP','TC-INTERNET-BPTP-2014','Dedicated Internet 2 Mbps','2014-01-08',NULL,'Maintenance','20',NULL,'2014-04-22 14:10:18'),('0000000095','00028','6','B','Dedicated Internet STPP','TC-DEDICATED-STPP-2014','Dedicated Internet 4,5 Mbps','2014-01-24',NULL,'Maintenance','20',NULL,'2014-04-22 14:19:37'),('0000000096','00030','6','B','Dedicated Internet Akprind','TC-INTERNET-AKPRIND-2014','Dedicated Internet 10 Mbps','2014-04-01',NULL,'Maintenance','20',NULL,'2014-04-22 14:14:07'),('0000000097','00043','6','B','Internet MSV','TC-INTERNET-MSV-2014','Internet 10 Mbps','2014-04-01',NULL,'Maintenance','20',NULL,'2014-04-22 14:17:42'),('0000000098','00007','6','B','Dedicated Internet KNKT','TC-INTERNET-KNKT-2014','Dedicated Internet 2 Mbps','2014-04-01',NULL,'Maintenance','20',NULL,'2014-04-22 14:14:29'),('0000000099','00036','6','B','Internet SERDADU','TC-DEDICATED-SERDADU-2014','Dedicated Internet 1,5 Mbps','2014-04-01',NULL,'Maintenance','20',NULL,'2014-04-22 14:19:13'),('0000000100','00039','6','B','Internet & Colo Imation','TC-INTERNETCOLO-IMATION-2014','Internet dan Colocation','2014-04-01',NULL,'Maintenance','20',NULL,'2014-04-22 14:11:18'),('0000000101','00069','6','B','internet PlazaNet','TC-INTERNET-PLAZANET-2014','internet 1 Mbps','2014-04-01',NULL,'Maintenance','20',NULL,'2014-04-22 14:18:13'),('0000000102','00042','6','B','SoHo Linus','TC-SOHO-LINUS-2014','SoHo','2014-04-01',NULL,'Maintenance','20',NULL,'2014-04-22 14:16:10'),('0000000103','00031','6','B','SoHo Arundina','TC-SOHO-ARUNDINA-2014','SoHo','2014-04-01',NULL,'Maintenance','20',NULL,'2014-04-22 14:16:28'),('0000000104','00070','6','B','Internet Mineral and Coalt Studio','TC-INTERNET-MCS02-2014','Internet Mineral and Coalt Studio 2 Mbps','2014-04-22','2014-04-30','Maintenance','20',NULL,'2014-04-22 14:17:17'),('0000000106','00057','7','B','COMPRO2014','MK-COMPRO-TEMK-2014','Company Profile','2014-04-21','2014-04-25','Development','20',NULL,'2014-04-22 14:58:27'),('0000000108','00006','8','B','BMT AL MADINA','MK-BMT-TE-2014','Pekerjaan-pekerjaan yang terkait dengan tugas BMT AL MADINA','2014-01-01','2014-12-31','Development','20',NULL,'2014-04-22 14:02:32'),('0000000109','00011','001.01.00','B','Pengadaan Software dan Hardware Slot Time','SD-SLOTTIME-BALI-2014-TE','-','2014-04-19',NULL,'Development','16',NULL,'2014-08-26 10:30:51'),('0000000110','00020','9','B','Pengadaan Komputer Lab XII STMIK AMIKOM','Pengadaan PC Lab XII','Pengadaan Komputer PC','2014-04-22','2014-05-12','Lelang','107',NULL,'2014-04-22 15:28:05'),('0000000111','00018','001.01.00','B','Database Perijinan Usaha Penerbangan','SD-IJINUSBANG-DEPHUBUD-2014-TTS','PL','2014-06-05','2014-07-04','Development','16',NULL,'2014-08-26 10:24:32'),('0000000112','00018','001.01.00','B','DATABASE TENAGA KERJA ASING PENERBANGAN','SD-TENAKERBANG-DEPHUBUD-2014-APT','PL','2014-04-17','2014-05-16','Development','16',NULL,'2014-08-26 10:25:02'),('0000000113','00047','001.01.00','B','GIS SOSEKLING PU','SD-GISSOSEKLING-KEMENPU-2014-APT','KEMUNGKINAN AKAN DI PL','2014-04-23',NULL,'Marketing','16',NULL,'2014-08-26 10:29:04'),('0000000114','00018','001.01.00','B','Pembuatan Aplikasi Database Penerbangan Berjadwal Domestik Dan internasional Terintegrasi Dengan Sistem Informasi Angkutan Udara Online','SD-DAUDOMINT-DEPHUBUD-2014-TE','Pembuatan Aplikasi Database Penerbangan Berjadwal Domestik Dan internasional Terintegrasi Dengan Sis','2014-06-03','2014-11-29','Development','16',NULL,'2014-08-26 10:27:34'),('0000000115','00074','9','B','Pengadaan Komputer dan Perlengkapannya dan Perlengkapan Kantor','Peng PC dan Alat kantor','Pengadaan Komputer PC, dan Alat kantor','2014-02-28','2014-04-15','Lelang','107',NULL,'2014-04-23 13:53:09'),('0000000116','00001','001.01.00','B','Project otomasi report untuk unit kerja DSTB (Decision Support Transaction Banking)','SD-DSTB-MANDIRI-2014','FAILED. diambil oleh IT nya sendiri','2014-04-24','2014-06-11','Failed','20',NULL,'2014-06-11 14:46:42'),('0000000117','00076','001.01.00','B','Sistem Management','SD-VDMS-2014','Website profile dan aplikasi sistem','2014-04-24',NULL,'Marketing','1',NULL,'2014-04-25 10:18:18'),('0000000118','00075','5','B','IT Assessment Universitas Janabadra','IS-AssesJBD2014','Analisa dan security system dan hardware','2014-04-24',NULL,'Marketing','87',NULL,'2014-04-24 14:15:12'),('0000000119','00006','001.01.00','B','Riset Aplikasi Mobile - Android','SD-RISETMOBILEANDROID-TE-2014','Riset Aplikasi Mobile - Android','2014-04-01',NULL,'Development','1',NULL,'2014-04-25 10:59:58'),('0000000120','00006','001.01.00','B','Riset Aplikasi Mobile - Phonegap','SD-RISETMOBILEPHONEGAP-TE-2014','Riset Aplikasi Mobile - Phonegap','2014-04-01',NULL,'Development','1',NULL,'2014-04-25 11:00:09'),('0000000121','00001','001.01.00','B','PEMBUATAN APLIKASI JASA GIRO','SD-JASAGIRO-MANDIRI-2014','masih dlm proses inisiasi & persiapan dokumen oleh Bank Mandiri. (Perkiraan projek utk bulan Juni)','2014-04-25','2014-05-26','Failed','20',NULL,'2014-05-26 11:30:06'),('0000000122','00001','001.01.00','B','INVOICE','SD-INVOICE-MANDIRI-2014-TE','Msh dlm proses penawaran. Resource: mb Nurul','2014-04-25',NULL,'Marketing','16',NULL,'2014-08-26 10:23:04'),('0000000123','00006','001.01.00','B','Sistem Perijinan Terpadu','SD-RISETSIMPAS-TE-2014','Sistem Perijinan Terpadu - Core System','2014-04-01',NULL,'Development','1',NULL,'2014-04-25 11:08:01'),('0000000124','00001','001.01.00','B','PENGEMBANGAN E-DAPEM','SD-EDAPEM2-MANDIRI-2014-TE','dlm proses inisiasi & persiapan dokumen oleh pihak mandiri. (Perkiraan project utk bulan Juni)','2014-04-25',NULL,'Marketing','16',NULL,'2014-08-26 10:23:45'),('0000000125','00006','001.01.00','B','Sistem Manajemen Antrian','SD-RISETANTRIAN-TE-2014','Sistem Manajemen Antrian','2014-04-02',NULL,'Development','1',NULL,'2014-04-25 11:07:52'),('0000000126','00006','001.01.00','B','Sistem Informasi Manajemen Arsip dan Surat Kantor - E-Arsip','SD-RISETEARSIP-TE-2014','Sistem Informasi Manajemen Arsip dan Surat Kantor - E-Arsip','2014-04-01',NULL,'Development','1',NULL,'2014-04-25 11:09:18'),('0000000127','00006','001.01.00','B','SMS Engine','SD-RISETSMSENGINE-TE-2014','SMS Engine','2014-04-01',NULL,'Development','1',NULL,'2014-04-25 11:10:43'),('0000000128','00006','001.01.00','B','Sistem Informasi geografi - GIS','SD-RISETGIS-TE-2014','GIS','2014-04-01',NULL,'Development','1',NULL,'2014-04-25 11:11:51'),('0000000129','00006','001.01.00','B','AUTO GRABBER ENGINE','SD-RISETAUTOGRAB-TE-2014','AUTO GRABBER ENGINE','2014-04-01',NULL,'Development','1',NULL,'2014-04-25 11:14:05'),('0000000130','00006','001.01.00','B','MYSQL TUNING PROCEDURE','SD-RISETMYSQLTUNING-TE-2014','MYSQL TUNING PROCEDURE','2014-04-02',NULL,'Development','1',NULL,'2014-04-25 11:14:48'),('0000000131','00006','001.01.00','B','TEMPLATESOETTA','SD-RISETTEMPLATEBALI-TE-2014','TEMPLATESOETTA','2014-04-01',NULL,'Development','1',NULL,'2014-04-25 11:17:08'),('0000000132','00002','001.01.00','B','SIM EXECUTIF SUMMARY PAS BANDAR UDARA','SD-APMSSUMMARY-SOETTA-2014','SIM EXECUTIF SUMMARY PAS BANDAR UDARA','2014-04-01',NULL,'Lelang','20',NULL,'2014-05-06 10:44:28'),('0000000133','00002','001.01.00','B','WEBSITE SOETTA','SD-WEBSITE-SOETTA-2014-FS','WEBSITE SOETTA','2014-04-01',NULL,'Lelang','16',NULL,'2014-08-26 10:29:45'),('0000000134','00002','001.01.00','B','STUDY NSW','SD-STUDYNSW-SOETTA-2014','lelang','2014-04-25',NULL,'Lelang','61',NULL,'2014-07-21 09:59:47'),('0000000135','00078','5','B','Banwidth Manager','HS-01-BW','Penawaran BW Manager dan content filtering','2014-04-29','2014-04-29','Marketing','85',NULL,'2014-04-29 10:05:19'),('0000000136','00007','5','B','Pengadaan PC Laptop dan Printer','KNKT-01-PC','pc 10, laptop 10,printer epson L550 5, printer A3 5','2014-04-29','2014-04-29','Marketing','85',NULL,'2014-04-29 10:10:37'),('0000000137','00079','9','B','Cek Pekerjaan Dahulu','TS-DINSOSNA-GK-2014','Survey Pekerjaan Dahulu','2014-04-30','2014-04-30','Development','107',NULL,'2014-05-16 09:02:39'),('0000000138','00055','6','B','Internet POLDA','TC-INTERNET-POLDA-2014','Internet POLDA 1,5 Mbps','2014-03-20','2015-03-20','Maintenance','62',NULL,'2014-05-02 08:04:01'),('0000000140','00082','5','B','Monitoring lelang LPSE Magelang','IS-LPSE-MGL-2014','Monitoring tahap marketing','2014-05-02','2014-05-09','Closed','100',NULL,'2014-08-09 11:48:01'),('0000000141','00083','7','B','Sistem Akademik (Outsource)','SD-SIAKAD-2014','Sistem Akademik IC','2014-04-30','2014-06-09','Failed','87',NULL,'2014-10-03 10:57:17'),('0000000142','00020','9','B','Belanja Langsung Kebutuhan LAB AMIKOM Bln Maret-April 2014','Blj Lsg Lab Amikom Apr\'14','Belanja Langsung utk  12 Lab komputer di AMIKOM','2014-04-14','2014-05-05','Marketing','107',NULL,'2014-05-03 14:02:15'),('0000000143','00043','9','B','Belanja Langsung Kebutuhan MSV  Anggaran Maret2014','Blj Lsg MSV via RT Amikom','Order Kubutuhan UPS 16kVA, Rack server, memory ECC, TV Plasma 60\", Wless phone, Digitizer dst.','2014-04-24','2014-05-10','Marketing','107',NULL,'2014-05-03 14:07:25'),('0000000145','00006','001.01.00','B','Softdev Koordinasi dan Strategi','SD-SOFTDEV-TE-2014','Softdev Koordinasi dan Strategi','2014-05-06',NULL,'Development','1',NULL,'2014-05-06 14:07:06'),('0000000146','00084','5','B','LPSE FEB UGM','IS-LPSEFEBUGM-Jog-2014','RAB melebihi HPS','2014-05-06','2014-05-13','Failed','100',NULL,'2014-05-07 14:25:29'),('0000000147','00074','9','B','PENGADAAN MIC CONFERENCE RAPAT','TS- Inst Mic Conf Rapat','Pelaksanaan  pekerjaan & Installasi Mic','2014-05-08','2014-06-09','Marketing','107',NULL,'2014-05-17 11:05:16'),('0000000148','00043','9','B','Belanja Langsung Kebutuhan MSV  Anggaran April 2014','Blj Lsg MSV(Apr\'14) via RT Amikom','Belanja Langsung SamsungSmart PC, Epson L350, HD ekt 1TB, FD16GB, PowerBank 5200MAh, HDD 2.5\", Ram E','2014-05-07',NULL,'Marketing','107',NULL,'2014-05-07 10:44:02'),('0000000149','00085','9','B','VOIP DISDUK CAPIL Kab.KABUMEN','TS-DISPENDUKCAPIL-KBM-2014','Start 26 Mei 2014','2014-05-08','2014-05-22','Development','107',NULL,'2014-05-14 15:50:43'),('0000000150','00086','9','B','Inisiasi Pengadaan Komputer dan Peralatan Kantor','TS-Inisiasi PekerjaanDppkad','Inisiasi ke Dppkad','2014-09-15',NULL,'Marketing','107',NULL,'2014-05-08 13:54:29'),('0000000151','00087','5','B','Pengadaan Alat Pengolah Data','IS-LPSE-SALATIGA-2014','Start 10 Mei 2014','2014-05-10','2014-05-14','Closed','100',NULL,'2014-05-17 11:40:18'),('0000000152','00089','5','B','Assesment IT','IS-ASSITUNTID-2014','Assesment IT','2014-05-08',NULL,'Marketing','65',NULL,'2014-05-12 10:34:37'),('0000000153','00088','6','B','Internet','TC-AMIKOMPWT-2014','Dedicated internet dengan FO 10 Mbps','2014-01-31','2015-03-31','Maintenance','87',NULL,'2014-10-13 14:03:57'),('0000000155','00091','6','B','Internet','TC-INT-BP2T','Internet 4Mbps','2014-01-01','2014-12-31','Maintenance','84',NULL,'2014-05-13 08:22:31'),('0000000156','00092','5','B','IT Assesment','IS-AssesITKemenlh-JOGJA-2014','start Jumat 16 Mei 2014','2014-05-16','2014-05-28','Marketing','100',NULL,'2014-05-14 15:34:47'),('0000000157','00002','5','B','IS-LAN-SOETTA-GD2','IS-SOETTA-LAN','Lan P2B','2014-05-04',NULL,'Development','85',NULL,'2014-05-15 22:18:23'),('0000000158','00002','5','B','is-otband-server pas','is-otband-server-pas','pembelian processor, ram, heatsink, PSU','2014-05-18','2014-05-30','Development','85',NULL,'2014-05-16 22:59:51'),('0000000159','00014','001.01.00','B','PENGADAAN APLIKASI DATA KEARSIPAN DAN TATA PERSURATAN','SD-EARSIP-MANADO-2014-APT','PENGADAAN APLIKASI DATA KEARSIPAN DAN TATA PERSURATAN','2014-08-01','2014-10-30','Development','16',NULL,'2014-08-26 10:37:06'),('0000000160','00093','001.08.00','A','Presentasi Time Excelindo','BDC-BTN','Presentasi Perbankan','2014-05-13','2014-05-13','Marketing','103',NULL,'2014-05-19 11:46:21'),('0000000161','00002','001.08.00','B','INISIASI KAK WEBSITE KOBU I','BDC-WEBSITE KOBU I','Mempersiapkan KAK + RAB Website 2014','2014-05-17','2014-05-17','Marketing','103',NULL,'2014-05-19 12:07:47'),('0000000162','00002','001.08.00','B','INISIASI KAK EKSEKUTIF SUMMARY KOBU I','BDC- EKSEKUTIF SUMMARY KOBU I','Kesiapan untuk ULP dan Lelang','2014-05-18','2014-05-18','Marketing','103',NULL,'2014-05-19 12:06:50'),('0000000163','00001','001.08.00','B','BDC-EDAPEM2-MANDIRI-2014','BDC-EDAPEM2','Pengembangan Edapem','2014-05-19','2014-05-19','Marketing','103',NULL,'2014-05-19 12:20:45'),('0000000164','00001','001.08.00','B','DSTB MANDIRI 2014','BDC-DSTB MANDIRI 2014','Project Warehousing Database','2014-05-19','2014-05-19','Marketing','103',NULL,'2014-05-19 12:18:47'),('0000000165','00001','001.08.00','B','INVOICE PICK UP','BDC-INVOICE MANDIRI 2014','Project Invoice Pick Up Invoice','2014-05-19','2014-05-19','Marketing','103',NULL,'2014-05-19 13:11:38'),('0000000166','00094','001.08.00','B','Akuisisi Software Bank Micro Finance','BDC-SiBOS','Software Bank Micro Finance','2014-05-19','2014-05-31','Marketing','103',NULL,'2014-05-19 13:39:00'),('0000000167','00002','001.08.00','A','Kajian NSW','BDC-Kajian NSW','Kajian NSW 2014','2014-05-19','2014-05-19','Marketing','103',NULL,'2014-05-19 14:53:34'),('0000000168','00095','001.08.00','B','Sistem Informasi Kearsipan','BDC-KA Arsip','Sistem Informasi Kearsipan','2014-05-19','2014-05-19','Marketing','103',NULL,'2014-05-19 15:19:15'),('0000000169','00019','001.08.00','B','Sistem Informasi Lingkungan','BDC - PPE','Sistem Informasi Lingkungan','2014-05-19','2014-05-19','Marketing','103',NULL,'2014-05-19 15:32:03'),('0000000170','00097','001.01.00','B','CAPI','SD-CAPI-BNPB-2014','CAPI','2014-05-19',NULL,'Marketing','20',NULL,'2014-05-21 09:28:40'),('0000000171','00007','001.01.00','B','PELATIHAN WEB KNKT','SD-WEBKNKT-KNKT-2014','PELATIHAN WEB KNKT','2014-05-19','2014-05-26','Failed','20',NULL,'2014-05-26 11:31:51'),('0000000172','00001','001.08.00','B','Pengembangan MCM Converter untuk memunculkan BIC code pada transaksi TT','BDC - MANDIRI MCM2','Pengembangan MCM Converter untuk memunculkan BIC code pada transaksi TT','2014-05-20','2014-05-20','Marketing','103',NULL,'2014-05-20 05:44:44'),('0000000173','00007','001.08.00','B','Maintenance Pengisian Website Content','BDC-KNKT Website','Maintenance Pengisian Website Content','2014-05-28','2014-05-30','Marketing','103',NULL,'2014-05-20 05:52:26'),('0000000174','00001','001.08.00','B','Converter','BDC-Converter Mandiri LT24','Converter  di lantai 24 Oleh Mas Yosi','2014-05-20','2014-05-21','Marketing','103',NULL,'2014-05-20 05:56:42'),('0000000175','00098','5','B','Pengadaan PC dan Notebook','IS-PCNOTEBOOKMIPAUGM-TE-2014','Start 21 Mei','2014-05-21','2014-05-28','Failed','100',NULL,'2014-06-02 09:01:44'),('0000000176','00099','5','B','CCTV AMIKOM','IS-CCTVAMIKOM-YKT-2014','On going','2014-05-23','2014-06-30','Development','100',NULL,'2014-05-26 08:24:06'),('0000000177','00018','001.08.00','B','EMONEV PL','BDC-EMONEV HUBUD JALDIN','APLIKASI JALDIN','2014-05-23','2014-05-23','Marketing','103',NULL,'2014-05-23 14:18:30'),('0000000178','00100','5','B','Survery Maintenance FO','IS-FODISHUB-PWJ-2014','dana 10jt tdk cukup','2014-05-26','2014-05-26','Failed','100',NULL,'2014-06-16 13:53:01'),('0000000179','00085','5','B','Teleconference (VOIP)','IS-DISPENDUKCAPIL-KBM-2014','baru akan bertemu PIC pada Jumat 30 Mei 2014','2014-05-30','2014-05-31','Development','100',NULL,'2014-08-09 12:22:11'),('0000000180','00001','001.01.00','B','Mandiri Cash Management jilid II','SD-MCM2-MANDIRI-2014-TE','Tahap Development','2014-06-24','2014-07-16','Development','16',NULL,'2014-08-26 10:23:13'),('0000000181','00001','001.01.00','B','BPJS - Autodebet Converter','SD-bpjsconverter-mandiri-2014-TE','masih tahap menunggu requirement proposal oleh ms Agung','2014-05-26',NULL,'Marketing','16',NULL,'2014-08-26 10:22:54'),('0000000182','00018','001.01.00','B','PENGEMBANGAN POSKO ANGKUTAN UDARA LEBARAN & NATAL','SD-POSKOANGUD-DEPHUBUD-2014-DSC','STATUS MSH DI MARKETING, PERSIAPKAN RAB PENAWARAN','2014-05-28',NULL,'Development','16',NULL,'2014-08-26 10:28:31'),('0000000183','00018','001.01.00','B','E MONEV DEPHUBUD','SD-EMONEVANGUD-DEPHUBUD-2014','PROJECT DARI PAK HANDOKO, KONFIRMASI KEPASTIANNNYA','2014-05-28',NULL,'Marketing','20',NULL,'2014-05-28 09:36:07'),('0000000184','00095','001.01.00','B','E Arsip DJKA','SD-EARSIPDJKA-DJKA-2014','MASIH DLM TAHAP MARKETING','2014-05-28',NULL,'Marketing','20',NULL,'2014-05-28 13:41:49'),('0000000185','00001','001.01.00','B','APLIKASI ASABRI','SD-ASABRI-MANDIRI-2014','MASIH DLM TAHAP MARKETING','2014-06-02',NULL,'Marketing','20',NULL,'2014-06-02 11:13:13'),('0000000186','00102','5','B','Visitasi','IS-PURABARUTAMA-KUDUS-2014','Star visitasi 9 Juni 2014','2014-06-10','2014-06-10','Marketing','100',NULL,'2014-06-09 11:59:48'),('0000000187','00103','9','B','Pek Pengadaan PC Desktop All-In-One','TS- Peng Komp All-In-One','Pesanan Pengadaan Komputer All-In-One','2014-06-07','2014-06-11','Marketing','107',NULL,'2014-06-09 12:19:30'),('0000000188','00043','9','B','Belanja Langsung Kebutuhan MSV  Anggaran Mei 2014','TS- Blj Lsg MSV (Jun\'14) via RT Amikom','Pembelian monitor LED, konverter-2, Hdd, konvert Hdd dll.','2014-06-09','2014-06-16','Marketing','107',NULL,'2014-06-09 15:30:32'),('0000000189','00104','9','B','Maintenance Komputer Lab MM Dasar [12 unit]','TS- Maintenance PC di AKRB','Jasa Service KOmputer PC di AKRB','2014-06-10','2014-06-12','Maintenance','107',NULL,'2014-06-10 09:45:57'),('0000000190','00105','5','B','Labelling,Instalasi SwithHub dan Maping Lokal Jaringan','IS-BPK-YKT-2014','Labelling,Instalasi SwithHub dan Maping Lokal Jaringan','2014-06-03',NULL,'Development','100',NULL,'2014-06-18 11:27:42'),('0000000192','00107','5','B','Infrastruktur jaringan poltekes smbas','INF-Jaringan FO-Sambas','Pembangunan infrastruktur jaringan','2014-06-22',NULL,'Marketing','85',NULL,'2014-06-25 09:52:53'),('0000000194','00108','001.08.00','B','erp','bdc-erp','inisiasi','2014-06-23','2014-06-23','Marketing','103',NULL,'2014-06-26 19:14:32'),('0000000195','00109','001.08.00','B','tracking, mobile app, sms','bdc-laut dephub 17','presentasi','2014-06-26','2014-06-26','Marketing','103',NULL,'2014-06-26 19:26:37'),('0000000196','00110','5','B','INTERNET OTBAN WIL X MERAUKE','IS-INETOTBANX-MERAUKE-2014','Tawaran balik','2014-06-30','2014-07-05','Marketing','100',NULL,'2014-06-28 12:00:34'),('0000000197','00111','9','B','Inisiasi kegiatan Belanja Langsung server','TS- Blj Lsg Inpektorat Tmg','Inisiasi Belanja Langsing dan Assesment proposal Sewa Printer','2014-07-06',NULL,'Marketing','107',NULL,'2014-07-01 11:39:12'),('0000000198','00018','4','B','Studi  Penyusunan Laporan Akuntabilitas Kinerja Instansi   Pemerintah (LAKIP) Berbasis Elektronik (E-LAKIP)','AD-E LAKIP-ANGUD-2014','pekerjaan non create','2014-06-20','2014-07-24','Lelang','61',NULL,'2014-07-02 09:57:03'),('0000000199','00112','4','B','Sistem Pembelajaran E-Learning  Politeknik Pelayaran Surabaya Tahun 2014','AD-E LEARNING-POLTEKPEL SBY-2014','pekerjaan non create','2014-06-20','2014-07-31','Lelang','61',NULL,'2014-07-02 10:04:37'),('0000000200','00011','001.01.00','B','WEBSITE BALI','SD-WEBSITE-BALI-2014','PL','2014-07-04',NULL,'Marketing','20',NULL,'2014-07-04 10:47:12'),('0000000201','00046','6','B','Reinstalasi Kelistrikan SMA Negeri 1 Sleman','TC-Kelistrikan-SMA1SLM-2014','Instalas kelistrikan Lab komputer dan ruangan lainnya','2014-07-22','2014-08-03','Development','87',NULL,'2014-07-22 10:43:02'),('0000000202','00012','5','B','INF-Jaringan gedung','BPN-jaringan gedung','Pekerjaan instalasi jaringan gedung','2014-08-08',NULL,'Marketing','85',NULL,'2014-08-08 09:40:17'),('0000000203','00035','5','B','Pemasangan Penangkal Petir dan Grounding','IS-PETIR-2014','Pemasangan Penangkal Petir dan Grounding','2014-08-05',NULL,'Marketing','87',NULL,'2014-08-08 10:22:45'),('0000000204','00106','5','B','FO','IS-POLIMDO-MANADO-2014-TE','Marketing','2014-08-11',NULL,'Marketing','100',NULL,'2015-02-10 11:58:46'),('0000000205','00113','001.01.00','B','SIMPEG RSUD TIDAR','SD-SIMPEG-RSUD-TIDAR-2014','Sistem informasi kepegawaian PNS di RSUD Tidar (Magelang)','2014-08-16','2014-08-20','Marketing','21',NULL,'2014-08-18 15:54:42'),('0000000206','00105','5','B','Lanjut','IS-BPK2-YKT-2014','Pekerjaan Tahap II','2014-08-11',NULL,'Development','100',NULL,'2014-08-26 14:59:27'),('0000000207','00085','9','B','Installasi dan Pengadaan IP Phone 8 Titik','TS- Inst IP Phone','Pekerjaan Pengadaan dan Installasi IP Phone di 8 Lokasi','2014-07-21',NULL,'Marketing','107',NULL,'2014-08-26 15:29:19'),('0000000208','00018','001.01.00','B','APLIKASI INTEGRASI SISTEM INFORMASI DATA PRODUKSI ANGKUTAN UDARA','SD-DAU-DEPHUBUD-2014-APT','APLIKASI INTEGRASI SISTEM INFORMASI DATA PRODUKSI ANGKUTAN UDARA','2014-09-03','2014-12-01','Development','20',NULL,'2014-11-08 09:23:25'),('0000000209','00018','001.01.00','B','APLIKASI INTEGRASI SISTEM INFORMASI DATA BANDARA UDARA','SD-DBU-DEPHUBUD-2014-TE','APLIKASI INTEGRASI SISTEM INFORMASI DATA BANDARA UDARA','2014-09-01','2014-11-29','Development','20',NULL,'2014-11-08 09:23:38'),('0000000210','00115','6','B','DEDICATED 4 Mbps','TC-DEDICATED-DAMAIPRO-2014','DEDICATED 4 Mbps','2014-10-01','2015-10-01','Maintenance','62',NULL,'2014-10-01 14:10:55'),('0000000211','00114','6','B','SoHo Up To 1 Mbps','TC-SOHO-TBNOTO-2014','SoHo Up To 1 Mbps','2014-09-22','2015-09-22','Maintenance','62',NULL,'2014-10-01 14:13:09'),('0000000212','00116','6','B','DEDICATED 1 Mbps','TC-DEDICATED-AANG-2014','DEDICATED 1 Mbps','2014-09-10','2015-09-10','Maintenance','62',NULL,'2014-10-01 14:15:07'),('0000000213','00006','3','B','MR-ISO-TE-2014','MR-ISO-TE-2014','MR-ISO-TE-2014','2014-10-06','2014-10-06','Development','16',NULL,'2014-10-06 10:13:53'),('0000000214','00117','6','B','Dedicated Internet','TC-DEDICATED-IPUII-2014','Bandwidth 2 Mbps','2014-10-09',NULL,'Marketing','87',NULL,'2014-10-13 13:59:46'),('0000000215','00119','6','B','Dedicated Internet','TC-DEDICATED-RK-2014','1 Mb = 750','2014-10-15','2015-10-14','Maintenance','87',NULL,'2014-10-18 13:19:21'),('0000000216','00120','6','B','Instalasi LAN dan Internet SMKN 1 Purworejo','TC-ISP-SMKN1Pur-2014','internet dedicated 10 Mbps','2014-10-20','2015-10-20','Development','62',NULL,'2014-10-20 08:49:08'),('0000000217','00121','9','B','Pengadaan Router Keg Pengembangan Jaringan','TS- Pengadaan Router','Pengadaan Barang dan transfer Knowledge','2014-10-01','2014-11-02','Development','107',NULL,'2014-10-27 16:15:09'),('0000000218','00011','001.08.00','B','Datawarehouse','BDC-Datawarehouse','Project Datawarehouse 2015','2014-11-04','2014-11-05','Marketing','103',NULL,'2014-10-31 13:31:32'),('0000000219','00086','9','B','Pek Pengadaan Line Matrix Printer','TS- Pengadaan Printronix','Pengadaan Barang Printronix','2014-10-30','2014-11-29','Marketing','107',NULL,'2014-11-04 13:12:09'),('0000000220','00018','001.01.00','B','FLIGHT CLEARANCE','SD-FLIGHTCLEAR-DEPHUBUD-2014-TE','FLIGHT CLEARANCE','2014-11-08','2014-11-30','Development','16',NULL,'2015-05-02 09:09:14'),('0000000221','00122','7','B','Leased line Telkom','MK-LLTelkomYK-2014','vendor leased line','2014-09-01',NULL,'Development','65',NULL,'2014-11-12 11:20:14'),('0000000222','00011','001.01.00','B','SD-WAREHOUSE-BALI-2015','SD-WAREHOUSE-BALI-2015-TE','SD-WAREHOUSE-BALI-2015','2015-01-01','2015-01-01','Failed','16',NULL,'2015-09-15 14:42:35'),('0000000223','00123','5','B','SERVER BACKUP','IS-SERVER-BALI-2015-TTS','TAHAP MOU','2014-11-18',NULL,'Development','100',NULL,'2015-02-11 12:05:46'),('0000000225','00124','6','B','Dedicated Internet Night Speed','TC-NS-CYBER-2014','Kapasitas siang 2 Mbps, Malam 4 Mbps','2014-11-25','2015-11-30','Maintenance','87',NULL,'2014-11-21 09:28:40'),('0000000226','00125','6','B','Dedicated Internet','TC-ISP-NURBIZ-2014','Kapasitas 1 Mbps','2014-11-24','2015-11-30','Maintenance','87',NULL,'2014-11-21 09:33:28'),('0000000227','00018','001.01.00','B','SD-SPJ-DEPHUBUD-2014-APT','SD-SPJ-DEPHUBUD-2014-APT','SD-SPJ-DEPHUBUD-2014-APT / KAMPEN','2014-12-01','2014-12-01','Development','16',NULL,'2014-12-23 15:29:36'),('0000000228','00117','6','B','Dedicated Internet','TC-DEDICATED-IPFE-2014','Kapasitas 4 Mbps','2014-11-24',NULL,'Marketing','87',NULL,'2014-11-26 15:01:18'),('0000000229','00018','5','B','RELOKASI SERVER ANGKUTAN UDARA DEPHUBUD','IS-SERVERANGUD-DEPHUBUD-2014-TE','SPK sedang ditagihkan','2014-12-15','2014-12-31','Development','100',NULL,'2014-12-15 09:47:00'),('0000000230','00002','5','B','PEMELIHARAAN SERVER','IS-PEMELIHARAANSERVER-SOETA-2014-TTS','SUDAH MULAI BELANJA BARANG','2014-12-15','2014-12-31','Development','100',NULL,'2014-12-16 09:44:59'),('0000000231','00014','5','B','Pembuatan Sistem LAN Manado','IS-LAN-MANADO-TTS-2014','start','2014-11-10','2015-01-15','Development','100',NULL,'2014-12-29 09:00:38'),('0000000232','00050','6','B','Internet dedicated 4 Mbps','TC-DEDICATED-SABO-2015','Upgrade Kapasitas','2015-01-02','2016-01-01','Maintenance','87',NULL,'2015-01-02 14:07:29'),('0000000233','00006','6','B','TE ISP','TC-ISP-TE-2015','Pekerjaan-pekerjaan yang terkait dengan tugas ISP','2015-01-01','2015-12-31','Maintenance','62',NULL,'2015-01-14 11:02:38'),('0000000234','00028','6','B','Dedicated Internet STPP','TC-DEDICATED-STPP-2015','Dedicated Internet 6 Mbps','2015-01-01','2015-12-31','Maintenance','62',NULL,'2015-01-07 10:22:56'),('0000000235','00046','6','B','Dedicated Internet 8 Mbps','TC-DEDICATED-SMANSA-2015','Dedicated Internet 8 Mbps','2015-01-01','2015-12-31','Maintenance','62',NULL,'2015-01-08 09:50:18'),('0000000236','00057','7','B','Kegiatan Marketing tahun 2015','MK-MARKETING-2015','Tahun 2015','2015-01-02','2015-12-31','Marketing','87',NULL,'2015-01-09 13:45:56'),('0000000237','00006','7','B','Pengembangan dan Maintenance Website','WEBSITE-2015','Pengembangan dan Maintenance Website','2015-01-01','2015-12-31','Maintenance','87',NULL,'2015-01-12 13:31:19'),('0000000238','00091','6','B','Dedicated Internet BP2T 4 Mbps','TC-INT-BP2T-2015','Dedicated Internet BP2T 4 Mbps','2015-01-01','2015-12-31','Maintenance','62',NULL,'2015-01-14 10:39:09'),('0000000239','00035','6','B','Dedicated Internet BPTP 3 Mbps','TC-INTERNET-BPTP-2015','Dedicated Internet BPTP 3 Mbps','2015-01-01','2015-12-31','Maintenance','62',NULL,'2015-01-14 10:40:26'),('0000000240','00070','6','B','Internet 2 Mbps','TC-INTERNET-MCS02-2015','Internet 2 Mbps','2015-01-01','2015-12-31','Maintenance','62',NULL,'2015-01-14 10:41:29'),('0000000241','00124','6','B','Dedicated Internet Night Speed','TC-NS-CYBER-2015','Speed 2 Mbps siang 4 Mbps malam','2015-01-01','2015-12-31','Maintenance','62',NULL,'2015-01-14 10:43:28'),('0000000242','00117','6','B','Dedicated Internet 4 Mbps','TC-DEDICATED-IPFE-2015','Dedicated Internet 4 Mbps','2015-01-01','2015-12-31','Maintenance','62',NULL,'2015-01-14 10:45:05'),('0000000243','00117','6','B','Dedicated Internet 4 Mbps','TC-DEDICATED-IPUII-2015','Dedicated Internet 4 Mbps','2015-01-01','2015-12-31','Maintenance','62',NULL,'2015-01-14 10:45:45'),('0000000244','00030','6','B','Internet 10 Mbps','TC-INTERNET-AKPRIND-2015','Internet 10 Mbps','2015-01-01','2015-12-31','Maintenance','62',NULL,'2015-01-14 10:47:35'),('0000000245','00120','6','B','Internet 10 Mbps','TC-ISP-SMKN1PUR-2015','Internet 10 Mbps','2015-01-01','2015-12-31','Maintenance','62',NULL,'2015-01-14 10:49:09'),('0000000246','00026','6','B','Internet 15 Mbps','TC-ISP-USM-2015','Internet 15 Mbps','2015-01-01','2015-12-31','Maintenance','62',NULL,'2015-01-14 10:53:09'),('0000000247','00045','6','B','Internet 10 Mbps','TC-DEDICATED-SKILL-2015','Internet 10 Mbps','2015-01-01','2015-12-31','Maintenance','62',NULL,'2015-01-14 10:54:19'),('0000000248','00048','6','B','Internet 30 Mbps','TC-DEDICATED-UNISSULA-2015','Internet 30 Mbps','2015-01-01','2015-12-31','Maintenance','62',NULL,'2015-01-14 10:55:16'),('0000000249','00051','6','B','Internet 2 Mbps','TC-DEDICATED-UWMY-2015','Internet 2 Mbps','2015-01-01','2015-12-31','Maintenance','62',NULL,'2015-01-14 10:56:02'),('0000000250','00057','7','B','Audit ISO','MK-ISO-2015','Pekerjaan Administrasi yang berkaitan dengan ISO 9001:2008','2015-01-01','2015-12-31','Maintenance','87',NULL,'2015-02-03 11:26:32'),('0000000251','00127','001.08.00','B','Wasdal & Slot','BDC - Wasdal & Slot','Marketingan Presentasi','2015-02-10','2015-02-10','Marketing','103',NULL,'2015-02-10 00:06:10'),('0000000252','00128','6','B','Internet 4 Mbps','TC-INT-UIIHukum-2015','Dedicated Internet 4 Mbps','2015-02-10','2016-02-10','Maintenance','62',NULL,'2015-02-10 08:51:09'),('0000000253','00129','001.08.00','B','Simpas','BDC - Simpas','Mempersiapkan Dokumen Lelang dan Kebutuhan2nya','2015-02-15','2015-02-17','Lelang','103',NULL,'2015-02-11 13:43:50'),('0000000254','00130','5','B','INFRASTRUKTUR','IS-INKA-MADIUN-2015-TE','INISIASI','2015-02-12','2015-02-28','Marketing','100',NULL,'2015-02-11 15:53:12'),('0000000255','00002','5','B','INISIASI PEKERJAAN GILANG LAKSANA','IS-LINKKARGO-SOETA-2015-TE','ON DOING','2015-02-13','2015-02-13','Closed','86',NULL,'2015-10-21 14:58:30'),('0000000256','00131','9','B','Maintenance Printer dan IT Produk','TS-Maintenance Dppkad GK','Maintenanace Printer Dot Matirx','2015-02-13','2015-02-13','Maintenance','107',NULL,'2015-02-13 11:06:25'),('0000000257','00132','5','B','PEKERJAAN WLAN OTBAN II','IS-WLAN-OTBANMEDAN-2015-TTS','MASIH INISIASI','2015-02-17','2015-02-24','Closed','86',NULL,'2015-10-21 14:59:55'),('0000000258','00072','2','A','FINANCE 2015','TE-FA-KEU-2015','FINANCE','2015-01-01','2015-12-31','Development','91',NULL,'2015-02-17 11:23:49'),('0000000259','00006','4','B','PROJECT ADMINISTRATION','AD-ADMIN-TE-2015','Pekerjaan-pekerjaan yang terkait dengan tugas ADMINISTRASI PROJECT','2015-01-01','2016-12-31','Lelang','88',NULL,'2016-09-06 09:18:46'),('0000000260','00002','001.01.00','B','SD-SIMKAMPEN-SOETTA-2015','SD-SIMKAMPEN-SOETTA-2015','Sistem Informasi Manajemen Keamanan Bandar Udara','2015-02-25','2015-02-25','Failed','16',NULL,'2015-09-15 14:42:19'),('0000000261','00110','001.01.00','B','SD-SIMPAS-MERAUKE-2015','SD-SIMPAS-MERAUKE-2015','Sistem Informasi Manajemen Pas Bandara','2015-02-27','2015-02-27','Failed','16',NULL,'2015-09-15 14:43:37'),('0000000262','00033','6','B','Internet 10 Mbps','TC-DEDICATED-BETHESDA-2015','Dedicated Int 10 Mbps','2015-03-02','2016-03-02','Maintenance','62',NULL,'2015-03-02 08:52:04'),('0000000263','00002','5','B','PENGADAAN PERALATAN SCREENING KEAMANAN BANDAR','IS-CBT-SOETA-2015-TTS','persiapan lelang','2015-03-13','2015-03-20','Maintenance','86',NULL,'2015-10-21 15:00:13'),('0000000264','00006','5','B','Infra 2015','IS-INFRA-TE-2015','Pengembangan Div. Infra','2015-03-23','2015-12-31','Development','86',NULL,'2015-03-23 11:10:04'),('0000000265','00057','5','B','Kementrian PU','MK-MARKETING-KemenPU-2015','Instalasi Server','2015-03-24','2015-03-28','Maintenance','86',NULL,'2015-03-23 11:45:45'),('0000000266','00041','5','B','SECURITY SERVER MELON','IS-SECURITYSERVER-MELON-2015','Visit','2015-03-30','2015-12-31','Maintenance','86',NULL,'2015-03-23 11:48:11'),('0000000267','00134','001.01.00','B','Pengembangan Aplikasi Manajemen Usaha Koperasi (e-Koperasi)','SD-EKOPERASI-KEMENKOP-2015-TE','Pengembangan Aplikasi Manajemen Usaha Koperasi (e-Koperasi)','2015-04-24','2015-09-23','Closed','138',NULL,'2015-12-30 10:24:03'),('0000000268','00135','5','B','Pengadaan Peralatan Pengolah Data Kementerian Koordinator Bidang Perekonomian Tahun 2015','IS-OLAHDATA-KEMENKO-2015-TE','LELANG','2015-03-27','2015-04-03','Failed','100',NULL,'2015-04-17 15:20:39'),('0000000269','00001','001.01.00','B','CSA BMRI','SD-CSABMRI-MANDIRI-2015-TE','CSA BMRI','2015-03-27','2015-03-27','Development','16',NULL,'2015-09-15 14:41:16'),('0000000270','00047','001.01.00','B','PENGEMBANGAN SISTEM INFORMASI MANAJEMEN KEPEGAWAIAN (SIMKA) BADAN LITBANG','SD-SIMKA-KEMENPU-2015','PENGEMBANGAN SISTEM INFORMASI MANAJEMEN KEPEGAWAIAN (SIMKA) BADAN LITBANG','2015-03-30','2015-03-30','Development','16',NULL,'2015-09-15 14:41:52'),('0000000271','00136','9','B','Maintenance  CCTV dan  Elektric','TS- Maintenance Pemkab Sleman','Miantain di Kominfo dan Pdam','2015-04-01','2015-04-02','Maintenance','107',NULL,'2015-04-02 09:03:27'),('0000000272','00002','5','B','Pembangunan LAN Lt.3 Otban Soeta','IS-LAN-SOETA-2015-TE','mulai kerja segera','2015-04-11','2015-04-30','Closed','86',NULL,'2015-10-21 15:00:38'),('0000000273','00137','5','B','INFRASTRUKTUR','IS-POLTESA-KALBAR-2015-TE','STARTING BEGIN','2015-04-17','2015-04-30','Marketing','100',NULL,'2015-04-17 12:33:19'),('0000000274','00138','5','B','INFRASTRUKTUR','IS-POLITALA-KALSEL-2015-TE','STARTING BEGIN','2015-04-17','2015-04-30','Marketing','100',NULL,'2015-04-17 12:32:06'),('0000000275','00139','5','B','INFRASTRUKTUR API MADIUN','IS-API-MADIUN-2015-TE','Inisiasi','2015-04-21','2015-04-30','Marketing','100',NULL,'2015-04-21 10:30:23'),('0000000276','00018','4','B','Pembuatan video company profile','AD-VICOMPRO-DEPHUBUD-2015-TTS','Pembuatan video company profile','2015-05-03','2015-09-13','Development','16',NULL,'2015-08-03 14:22:06'),('0000000277','00140','5','B','INFRASTRUKTUR','IS-DPR-RI-2015-TE','INISIASI','2015-04-30','2015-05-31','Marketing','100',NULL,'2015-05-09 08:55:55'),('0000000278','00029','9','B','Service/Maintenance  HardWare','TS-Maintenance Aba-Yk','Service Komp PC Lab','2015-05-13','2015-05-15','Maintenance','107',NULL,'2015-05-12 14:05:11'),('0000000279','00141','5','B','Sistem IT Rumah Sakit','IS-RSI-MADIUN-2015-TE','inisiasi','2015-05-19','2015-05-21','Marketing','100',NULL,'2015-05-18 11:09:05'),('0000000280','00105','9','B','Installasi LAN dan Electrik','TS- Installasi LAN dan Listrik','Installasi Jaringan Lan antar gedung dan AP','2015-05-15','2015-05-21','Maintenance','107',NULL,'2015-05-19 13:15:14'),('0000000281','00135','5','B','Optimalisasi Mail Server Exchange Kemenko','IS-MAILSERVER-KEMENKO-2015-TE','SPK sudah turun sejak April','2015-04-10','2015-07-10','Development','100',NULL,'2015-05-23 13:04:43'),('0000000282','00002','5','B','MIGRASI CCTV CARGO SOETA','IS-MIGRASICCTV-SOETA-2015-APT','PL - UTANG','2015-05-01','2015-05-23','Closed','86',NULL,'2015-10-21 14:59:21'),('0000000283','00142','4','B','Film & Animation','AD-FILM-SARIHUSADA-JAKARTA-2015-TE','Film & Animation','2015-05-21','2015-06-15','Development','16',NULL,'2015-05-28 15:40:37'),('0000000284','00014','001.01.00','B','Pengadaan Aplikasi Aset Management','SD-SIMASET-MANADO-2015-TE','Pengadaan Aplikasi Aset Management','2015-05-29','2015-05-29','Development','16',NULL,'2015-09-15 14:43:01'),('0000000285','00014','001.01.00','B','PENGADAAN SISTEM INFORMASI SHORT MESSAGE SERVICE CENTER','SD-SMSCENTER-MANADO-2015-FS','PENGADAAN SISTEM INFORMASI SHORT MESSAGE SERVICE CENTER','2015-05-29','2015-05-29','Development','16',NULL,'2015-09-15 14:43:16'),('0000000286','00143','9','B','Pek Pengadaan Peralatan Studio Visual','TS-Peng Peralatan Studio  Ratih TV','(Ratih TV) Bag Humas &Protkl  Setda Kbm','2015-04-06','2015-06-29','Development','107',NULL,'2015-06-09 08:13:52'),('0000000287','00144','7','B','Infrastruktur jaringan fiberoptik','MK-FO-DISDUKCAPIL-CILACAP-2015-TE','Inisiasi','2015-06-09','2015-06-30','Marketing','100',NULL,'2015-06-09 12:17:12'),('0000000288','00099','9','B','Pengadaan Komputer Lab-2  UPT Amikom','TS- Peng Komp PC Lab2','Pengadaan KOmputer PC [H/w- Perakitan- Installasi]  80unit','2015-06-15','2015-06-29','Marketing','107',NULL,'2015-06-15 14:08:12'),('0000000289','00018','001.01.00','B','SD-MAINTENANCEFA-DEPHUBUD-2015','SD-MAINTENANCEFA-DEPHUBUD-2015','Maintenance Flight Approval','2015-06-30','2015-06-30','Development','16',NULL,'2015-06-20 13:11:02'),('0000000290','00018','001.01.00','B','SD-MAINTENANCEIR-DEPHUBUD-2015','SD-MAINTENANCEIR-DEPHUBUD-2015','Maintenance Ijin Rute','2015-06-30','2015-06-30','Development','16',NULL,'2015-06-20 13:11:44'),('0000000291','00018','001.01.00','B','SD-MAINTENANCEPOSKO-DEPHUBUD-2015','SD-MAINTENANCEPOSKO-DEPHUBUD-2015','Maintenance Aplikasi Posko Angkutan Udara Lebaran, Natal dan Tahun Baru 2015/2016','2015-06-30','2015-06-30','Development','16',NULL,'2015-06-20 13:13:41'),('0000000292','00145','7','B','INFRASTRUKTUR','MK-BPS-GORONTALO-2015-TE','INISIASI','2015-07-01','2015-07-03','Marketing','100',NULL,'2015-07-01 10:03:02'),('0000000293','00014','5','B','Pengadaan Back-Up Server Kantor Otoritas Bandar Udara Wilayah VIII, 1 Paket','IS-BackupServer-Manado-2015-TTS','menang','2015-07-29','2015-10-22','Closed','86',NULL,'2015-10-21 14:59:07'),('0000000294','00002','001.01.00','B','SD-MAINTENANCEAPMSONLINE-SOETTA-2015','SD-MAINTENANCEAPMSONLINE-SOETTA-2015','SD-MAINTENANCEAPMSONLINE-SOETTA-2015','2015-08-01','2015-08-31','Development','16',NULL,'2015-08-15 11:17:37'),('0000000295','00148','5','B','Pembangunan Tower','IS-PEMBANGUNANTOWER-2015-TE','Inisiasi','2015-08-25','2015-09-25','Marketing','128',NULL,'2015-08-18 10:46:28'),('0000000296','00149','001.01.00','B','SD-SIMAKADEMIK-ITUSMEDIA-2015','SD-SIMAKADEMIK-ITUSMEDIA-2015','Sistem Akademik SMK Purworejo','2015-07-01','2015-09-30','Development','16',NULL,'2015-08-18 14:10:30'),('0000000297','00131','9','B','Pengadaan Kertas Continues Form dan Ribbon Printronix','TS-Peng NCR & Ribbon Printronix','Pengadaan ATK di Dppkad GK','2015-08-10','2015-09-10','Marketing','107',NULL,'2015-08-19 10:29:13'),('0000000298','00150','5','B','Perencaan jaringan elektrikal gedung media center/Data Center','IS-DATACENTER-SLEMAN-2015-TTS','Development','2015-08-21','2015-09-19','Development','128',NULL,'2015-09-17 14:36:49'),('0000000299','00151','9','B','Jasa Services  TA.2015','TS- Services Desktop & Internet','Jasa Service  Desktop PC & Check  internet','2015-08-24','2015-08-25','Maintenance','107',NULL,'2015-08-24 09:25:40'),('0000000300','00086','9','B','Pengadaan Komputer PC, NB dan Printer','TS-Peng PC NB & Printer','Pek Pengadaan PC, NB dan Printer  di Dppkad Kab Tmgg','2015-08-20','2015-09-04','Marketing','107',NULL,'2015-08-26 08:58:05'),('0000000301','00152','001.01.00','B','SD-AVSEC-RADININTENII-2015','SD-AVSEC-RADININTENII-2015','PENGADAAN PERLENGKAPAN DAN PERALATAN AVSEC','2015-09-01','2015-09-01','Lelang','16',NULL,'2015-09-01 09:16:35'),('0000000302','00002','001.01.00','B','SD-SINGLEINSPECTION-SOETA-2015','SD-SINGLEINSPECTION-SOETA-2015','Pengembangan Basis Data Single Inspection','2015-09-01','2015-09-01','Lelang','16',NULL,'2015-09-01 09:19:12'),('0000000303','00002','001.01.00','B','SD-TERPADU-SOETTA-2015','SD-TERPADU-SOETTA-2015','PENGADAAN PERANGKAT SISTEM TERPADU','2015-09-01','2015-09-01','Lelang','16',NULL,'2015-09-01 09:20:17'),('0000000304','00153','7','B','MK-MRI-RSBATAM-2015','MK-MRI-RSBATAM-2015','MK-MRI-RSBATAM-2015','2015-09-02','2015-09-02','Development','16',NULL,'2015-09-02 10:24:52'),('0000000305','00134','001.01.00','B','SD-IUMK-KEMENKOP-2015-TE','SD-IUMK-KEMENKOP-2015-TE','ANDROID IUMK','2015-09-04','2015-12-03','Closed','16',NULL,'2016-01-23 13:38:39'),('0000000306','00086','9','B','Belanja Langsung Ribbon Printronix','TS- Blj Lsg Ribbon Printronix','Belanja Langsung Accecorie (Ribbon) Printronix','2015-09-16','2015-09-16','Marketing','107',NULL,'2015-09-15 14:20:40'),('0000000307','00154','9','B','Assesment  Pekerjaan di Konsorsium PLTU Tanjung Jati B','TS- Assesment Pekerjaan','Kegiatan Assesment','2015-09-25','2015-09-26','Marketing','107',NULL,'2015-09-23 11:15:16'),('0000000308','00018','4','B','Pembuatan Video Profil Pusat Penelitian dan Pengembangan Perhubungan Udara','AD-VIPRO-LITBANG-2015-TTS','Video Profil LITBANG','2015-09-22','2015-12-05','Lelang','117',NULL,'2015-09-30 15:28:21'),('0000000309','00155','5','B','Instalasi jaringan dan Wifi','IS-LAFAYETTE-JOGJA-2015-TE','Instalasi Jaringan dan Wifi','2015-10-05','2015-11-09','Development','128',NULL,'2015-10-12 13:06:16'),('0000000310','00018','001.01.00','B','SD-SPJANGUD-DEPHUBUD-2015','SD-SPJANGUD-DEPHUBUD-2015-TTS','SD-SPJANGUD-DEPHUBUD-2015','2015-10-01','2015-10-01','Development','16',NULL,'2015-11-10 11:19:08'),('0000000311','00002','5','B','SISTEM MANAJEMEN TERPADU','IS-TERPADU-SOETA-2015-APT','Start','2015-10-19','2015-12-25','Development','100',NULL,'2015-10-17 11:13:56'),('0000000312','00156','001.01.00','B','Aviation Security (AVSEC)','SD-AVSEC-LAMPUNG-2015-TE','Start','2015-10-19','2015-11-30','Development','100',NULL,'2015-10-17 11:25:59'),('0000000313','00157','5','B','Pengadaan dan Pemasangan IP PABX di Gedung Operation Building (OB) Perum LPPNPI Cabang Denpasar','IS-PABX-BALI-2015-APT','Lelang','2015-10-21','2015-11-21','Lelang','65',NULL,'2015-10-28 08:48:41'),('0000000314','00158','5','B','Survey','IS-UNMUL-SURVEY-2015-TE','Inisiasi','2015-10-21','2015-11-21','Marketing','128',NULL,'2015-10-21 08:35:27'),('0000000315','00159','5','B','PENATAAN JARINGAN','IS-BPPTKG-JOGJA-2015-TTS','pl','2015-10-12','2015-12-15','Development','86',NULL,'2015-10-21 15:00:26'),('0000000316','00160','5','B','Instalasi Jaringan Program Administrasi','IS-MACANAN-KLATEN-2015-TE','Instalasi Jaringan','2015-11-01','2015-12-01','Development','128',NULL,'2015-11-03 09:46:30'),('0000000317','00105','9','B','TS- Maintenance Rutin Kantor BPK  DIY- Semesteran','TS- Mainten Kantor BPK','Pek. Maintenance & Installasi','2015-02-09','2015-12-24','Marketing','107',NULL,'2015-11-07 05:33:28'),('0000000318','00161','9','B','Maintenance DotMatrix Printer','TS-Mainten Printr Dotmatrix','Maintenance Printer','2015-11-11','2015-11-12','Maintenance','107',NULL,'2015-11-11 12:34:21'),('0000000319','00162','6','B','Instalasi LAN dan Internet','TC-LANINTERNET-BTPjkt-2015','Instalasi LAN dan Internet Balai Teknik Penerbangan','2015-11-09','2016-12-15','Development','62',NULL,'2015-11-12 10:28:58'),('0000000320','00163','5','B','Penyusunan Blueprint Tata Kelola Teknologi Informasi Kabupaten Blora','IS-BLUEPRINT-BLORA-2015-TTS','Blueprint Tata Kelola Teknologi Informasi','2015-11-16','2015-12-31','Development','128',NULL,'2015-11-18 09:46:44'),('0000000321','00001','001.01.00','B','DASHBOARD DINKES CSA MANDIRI','SD-DINKESCSA-MANDIRI-2015','DASHBOARD DINKES CSA MANDIRI','2015-11-18','2015-11-18','Development','16',NULL,'2015-11-18 09:52:56'),('0000000322','00164','5','B','Pekerjaan Pengadaan Perangkat Pengolah Data dan Komunikasi','IS-DATAKOM-P4TKMTK-2015','Pekerjaan Pengadaan Perangkat Pengolah Data dan Komunikasi','2015-11-28','2015-11-28','Development','16',NULL,'2015-11-28 11:16:05'),('0000000323','00165','001.08.00','B','Pemeliharaan jaringan & aplikasi medical check up','BD-MAINTENANCEJAR-BKP-2015-APT','pemeliharaan jaringan & aplikasi medical check up','2015-11-28','2015-11-28','Development','16',NULL,'2015-11-28 11:21:56'),('0000000324','00166','6','B','LAN dan Internet','TC-LAN&INTERNET-SMA1TEMON-2015','Dedicated 5 Mbps','2015-12-07','2016-12-07','Marketing','62',NULL,'2015-12-05 08:58:47'),('0000000325','00002','5','B','Pengadaan UPS','IS-UPS-SOETTA-2015-TTS','Pengadaan UPS','2015-11-30','2015-12-29','Development','128',NULL,'2015-12-05 09:23:05'),('0000000326','00002','5','B','Pemeliharaan Server','IS-PEMSERVER-SOETTA-2015-TE','Pemeliharaan Server','2015-11-30','2015-12-29','Development','128',NULL,'2015-12-05 09:25:10'),('0000000327','00167','5','B','Pengadaan PC Apple iMac ME086','IS-IMAC-FMIPAUGM-2015-TTS','Pengadaan PC IMAC FMIPA UGM','2015-12-01','2015-12-31','Marketing','128',NULL,'2015-12-15 11:46:55'),('0000000328','00001','001.01.00','B','CSA RS MH dan Edu Sawit','SD-RSMHCSA-MANDIRI-2015','CSA RS MH dan Edu Sawit','2015-12-15','2016-01-09','Development','16',NULL,'2015-12-15 13:38:25'),('0000000329','00168','7','B','KOMPUTER DAN JARINGAN SIM TANGSEL','MK-DISHUBKOMINFO-TANGSEL-2015-APT','sblm 18 des harus terinstal','2015-12-12','2015-12-18','Development','100',NULL,'2015-12-16 09:39:31'),('0000000330','00168','7','B','KOMPUTER DAN JARINGAN SIM TANGSEL','MK-DISHUBKOMINFO-TANGSEL-2015-TTS','sblm 18 des harus terinstal','2015-12-12','2015-12-18','Development','100',NULL,'2015-12-16 09:40:58'),('0000000331','00169','5','B','Inisiasi','IS-IAIN-PALU-2016-TE','Survey','2016-01-01','2016-01-31','Marketing','128',NULL,'2016-01-04 13:01:30'),('0000000332','00072','2','B','TE-FA-KEU-2016','TE-FA-KEU-2016','Manajemen','2016-01-01','2016-12-31','Development','67',NULL,'2016-01-02 14:20:01'),('0000000333','00170','7','B','CBT','MK-CBT POLDA SURABAYA','Keperluan Untuk Test Akpol','2016-01-06','2016-01-07','Marketing','103',NULL,'2016-01-03 22:22:18'),('0000000334','00057','7','B','MK-MARKETING-TE-2016','MK-MARKETING-TE-2016','MK-MARKETING-TE-2016','2016-01-04','2016-12-31','Marketing','16',NULL,'2016-01-04 10:14:56'),('0000000335','00006','6','B','ISP 2016','TC-ISP-TE-2016','Internet','2016-01-01','2016-12-31','Development','62',NULL,'2016-01-04 14:44:28'),('0000000336','00171','5','B','Pengadaan Barang dan/atau Jasa Pekerjaan Pengadaan Elektonik Passenger Charge (ePSC) Tahun 2015','IS-EPSC-PELINDO-2016-TE','LELANG','2015-11-13','2016-01-13','Lelang','117',NULL,'2016-01-05 09:55:53'),('0000000337','00086','9','B','Belanja Maintenance PC','TS-Mainten Dppkad Tmgg','Belanja Langsung Maintenance KOmputer PC','2016-01-07','2016-01-07','Maintenance','107',NULL,'2016-01-06 16:32:57'),('0000000338','00171','001.01.00','B','SD-EPSC-PELINDO-2016-TE','SD-EPSC-PELINDO-2016-TE','SD-EPSC-PELINDO-2016-TE','2016-01-01','2016-01-01','Canceled','16',NULL,'2016-11-25 10:13:14'),('0000000339','00123','001.01.00','B','SD-DBINTERN-BALI-2016-TE','SD-DBINTERN-BALI-2016-TE','Pengembangan Database Intern Penunjang Kegiatan Operasional dan Manajemen','2016-04-28','2016-09-23','Development','16',NULL,'2016-05-09 11:56:32'),('0000000340','00172','13','B','TE-GA & BILLING-2016','TE-GA & BILLING-2016','GA, BILLING & PURCHASE ORDER','2016-01-01','2016-12-31','Development','91',NULL,'2016-01-14 15:48:24'),('0000000341','00173','9','B','Assesment Pekerjaan Infrastruktur di UMTAS','TS-Assmt Infra Umtas','Assesment  Pekerjaan','2016-01-17','2016-01-30','Marketing','107',NULL,'2016-01-15 10:41:20'),('0000000342','00174','001.08.00','A','Koordinasi Seluruh Pekerjaan','BDC-2016','Koordinasi Seluruh Pekerjaan','2016-01-25','2016-02-02','Marketing','103',NULL,'2016-01-22 13:46:51'),('0000000343','00006','3','A','HRD 2016','HR-HRD-TE-2016','HRD internal 2016','2016-01-01','2016-12-31','Maintenance','62',NULL,'2016-01-23 08:57:31'),('0000000344','00018','001.01.00','B','SD-EPLANNING-DEPHUBUD-2016-TTS','SD-EPLANNING-DEPHUBUD-2016-TTS','SD-EPLANNING-DEPHUBUD-2016-TTS','2016-01-27','2016-03-31','Development','16',NULL,'2016-10-13 10:19:37'),('0000000345','00175','5','B','Perencanaan Pengadaan Sarana dan Prasarana Keamanan','IS-STPI-CURUG-2016-TE','Perencanaan Pengadaan Sarana dan Prasarana Keamanan','2016-01-18','2016-02-19','Marketing','156',NULL,'2016-08-23 08:37:44'),('0000000346','00134','001.01.00','B','SD-EOFFICE-KEMENKOP-2016-TE','SD-EOFFICE-KEMENKOP-2016-TE','Pembuatan Aplikasi Perkantoran Deputi Pembiayaan Online','2016-04-18','2016-08-18','Development','16',NULL,'2016-10-13 10:20:50'),('0000000347','00134','001.01.00','B','SD-EPROPOSAL-KEMENKOP-2016-TE','SD-EPROPOSAL-KEMENKOP-2016-TE','PEMBUATAN SISTEM INFORMASI DEPUTI BIDANG PEMBIAYAAN','2016-03-29','2016-07-27','Development','16',NULL,'2016-10-13 10:21:05'),('0000000348','00176','5','B','Instalasi Jaringan Antar Gedung Indonesia Power','IS-ADIPALA-CILACAP-2016-TE','Inisiasi','2016-03-13','2016-03-31','Canceled','156',NULL,'2016-07-19 15:28:19'),('0000000349','00155','5','B','Instalasi Jaringan dan Wifi','IS-LAFAYETTE-JOGJA-2016-TE','Instalasi Jaringan dan Wifi','2016-03-07','2016-03-31','Development','128',NULL,'2016-03-12 08:47:50'),('0000000351','00179','001.01.00','B','SD-ETICKET-EFISIENSI-2016','SD-ETICKET-EFISIENSI-2016','SD-ETICKET-EFISIENSI-2016','2016-03-30','2016-03-30','Failed','16',NULL,'2016-10-13 14:58:22'),('0000000352','00018','001.01.00','B','SD-PERINTISANGUD-DEPHUBUD-2016','SD-PERINTISANGUD-DEPHUBUD-2016','SD-PERINTISANGUD-DEPHUBUD-2016','2016-04-04','2016-04-16','Development','16',NULL,'2016-04-06 08:42:17'),('0000000353','00021','001.01.00','B','SD-RISET-SOFTDEV-2016','SD-RISET-SOFTDEV-2016','SD-RISET-SOFTDEV-2016','2016-01-01','2016-12-31','Development','16',NULL,'2016-04-06 08:49:36'),('0000000354','00180','001.01.00','B','SD-KEUANGAN-LAMCOCOA-2016','SD-KEUANGAN-LAMCOCOA-2016','SD-KEUANGAN-LAMCOCOA-2016','2016-04-01','2016-05-31','Failed','16',NULL,'2016-10-13 14:58:45'),('0000000355','00047','001.01.00','B','SD-SIMKA-KEMENPU-2016','SD-SIMKA-KEMENPU-2016','SD-SIMKA-KEMENPU-2016','2016-05-03','2016-05-03','Development','16',NULL,'2016-05-03 11:14:42'),('0000000356','00181','9','B','Assesment  Revitalisasi Network DAOP 4 PT.KAI','TS-Assmt Networking DAOP4','Assesment Revitalisasi Network DAOP4','2016-05-18','2016-05-18','Development','107',NULL,'2016-05-17 16:16:38'),('0000000357','00018','001.01.00','B','SD-PEMETAANANGUD-DEPHUBUD-2016','SD-PEMETAANANGUD-DEPHUBUD-2016','SD-PEMETAANANGUD-DEPHUBUD-2016','2016-05-18','2016-05-18','Development','16',NULL,'2016-05-18 15:11:39'),('0000000359','00018','001.01.00','B','SD-FAIRANGUD-DEPHUBUD-2016','SD-FAIRANGUD-DEPHUBUD-2016','FA dan IR Angkutan Udara','2016-05-23','2016-05-23','Development','16',NULL,'2016-05-23 14:15:26'),('0000000360','00157','5','B','Voice Recording Airnav Bali','IS-AIRNAV-BALI-2016-TTS','Voice Recording Airnav Bali','2016-05-31','2016-05-31','Development','156',NULL,'2016-11-18 08:53:51'),('0000000361','00018','001.01.00','B','SD-EPERFOMANCE-DEPHUBUD-2016-APT','SD-EPERFOMANCE-DEPHUBUD-2016-APT','Pengadaan Pengembangan E-Performance','2016-06-13','2016-08-26','Development','16',NULL,'2016-10-13 10:19:55'),('0000000362','00018','001.01.00','B','SD-KAPALONLINE-DEPHUBUD-2016-DSC','SD-KAPALONLINE-DEPHUBUD-2016-DSC','Pendadaan Pengembangan Aplikasi Pendaftaran Kapal Online','2016-06-10','2016-08-26','Development','16',NULL,'2016-10-13 10:20:06'),('0000000363','00018','001.01.00','B','SD-EARSIPTU-DEPHUBUD-2016-APT','SD-EARSIPTU-DEPHUBUD-2016-APT','Tata Laksana Persuratan','2016-06-20','2016-10-31','Development','16',NULL,'2016-10-13 10:17:36'),('0000000364','00182','5','B','Jaringan LAN dan Wifi','IS-WLAN-KAIDAOP4-2016-TE','Jaringan LAN dan Wifi','2016-06-22','2016-06-22','Marketing','156',NULL,'2016-06-21 12:40:13'),('0000000365','00012','001.01.00','B','SD-UPDATETARIF-BALIKPAPAN-2016','SD-UPDATETARIF-BALIKPAPAN-2016-FS','pendampingan perubahan modul tarif PNBP pada aplikasi pas bandara','2016-06-21','2016-06-30','Development','16',NULL,'2016-06-27 09:52:32'),('0000000366','00018','001.01.00','B','SD-POSKO-DEPHUBUD-2016','SD-POSKO-DEPHUBUD-2016','SD-POSKO-DEPHUBUD-2016','2016-06-30','2016-06-30','Development','16',NULL,'2016-10-13 10:18:47'),('0000000367','00156','001.01.00','B','SD-EOFFICE-RADININTENII-2016-APT','SD-EOFFICE-RADININTENII-2016-APT','Pengadaan Aplikasi Database Administrasi Perkantoran','2016-07-15','2016-07-15','Development','16',NULL,'2016-07-15 11:44:37'),('0000000368','00156','001.01.00','B','SD-SIMPEG-RADININTENII-2016-FS','SD-SIMPEG-RADININTENII-2016-FS','Pengadaan System Monitoring Pengolah Data','2016-07-15','2016-07-15','Development','16',NULL,'2016-07-15 11:45:38'),('0000000369','00183','5','B','Pemasangan CCTV','IS-CCTV-RSUDWONOSARI-2016-TTS','Pemasangan CCTV','2016-07-22','2016-07-25','Development','156',NULL,'2016-08-05 11:00:42'),('0000000370','00183','5','B','Antrian','IS-ANTRIAN-RSUDWONOSARI-2016-TTS','Antrian','2016-07-24','2016-07-26','Development','156',NULL,'2016-08-05 11:00:20'),('0000000371','00134','001.08.00','B','PENGADAAN MEDIA INFORMASI ELEKTRONIK','BDC-KUKM-2016-APT','On doing','2016-08-02','2016-08-16','Development','100',NULL,'2016-08-06 13:04:52'),('0000000372','00184','9','B','TS-Assesment Pekerjaan Jangka Menengah','TS-Assmt Pekerjaan Mid-Range','Menggali dan Mengkaji Bakal Pekerjaan KSO Aplikasi Database','2016-09-04','2016-12-24','Marketing','107',NULL,'2016-08-08 15:56:07'),('0000000373','00185','5','B','Pengadaan Peralatan dan Sistem Pemantauan Kualitas Udara','IS-BLH-JAMBI-2016-HB','Pengadaan Peralatan dan Sistem Pemantauan Kualitas Udara','2016-08-09','2016-08-09','Marketing','156',NULL,'2016-08-09 14:16:56'),('0000000374','00018','001.01.00','B','SD-FALOCALANGUD-DEPHUBUD-2016','SD-FALOCALANGUD-DEPHUBUD-2016','SD-FALOCALANGUD-DEPHUBUD-2016','2016-08-01','2016-08-01','Development','16',NULL,'2016-08-11 08:54:35'),('0000000375','00018','001.01.00','B','SD-RPTKAANGUD-DEPHUBUD-2016','SD-RPTKAANGUD-DEPHUBUD-2016','SD-RPTKAANGUD-DEPHUBUD-2016','2016-08-01','2016-08-01','Development','16',NULL,'2016-08-11 09:01:05'),('0000000376','00186','5','B','Pengadaan IT','IS-KANTORPOS-SURABAYA-2016','PENGADAAN BARANG IT','2016-08-15','2016-08-31','Marketing','128',NULL,'2016-08-12 14:31:25'),('0000000377','00175','5','B','Revitalisasi jaringan LAN dan Wifi','IS-LANWIFI-STPICURUG-2016-TTS','Revitalisasi jaringan LAN dan Wifi','2016-08-18','2016-08-27','Development','156',NULL,'2016-08-23 08:39:29'),('0000000378','00187','5','B','Pengadaan dan Instalasi Mesin Presensi Kantor Muhammadiyah Yogyakarta','IS-PRESENSI-MUHJOGJA-2016-TE','Pengadaan dan Instalasi Mesin Presensi Kantor Muhammadiyah Yogyakarta','2016-08-27','2016-08-27','Development','156',NULL,'2016-08-29 09:44:08'),('0000000379','00188','5','B','Instalasi server','IS-MACANAN-SERVER-2016-TE','Instalasi server','2016-08-29','2016-08-31','Development','128',NULL,'2016-08-29 15:29:21'),('0000000380','00059','4','B','Lelang','AD-ADMIN-TE-2016','Pekerjaan-pekerjaan yang terkait dengan tugas ADMINISTRASI PROJECT','2016-09-06','2016-12-31','Lelang','88',NULL,'2016-09-06 09:03:11'),('0000000381','00189','5','B','SOA','IS-SOA-AirnavJakarta-2016-TE','SOA','2016-09-09','2016-09-30','Development','156',NULL,'2016-09-09 16:01:40'),('0000000382','00021','001.01.00','B','SD-SOFTDEV-TE-2016','SD-SOFTDEV-TE-2016','Pekerjaan softdev diluar project','2016-01-01','2016-09-30','Development','16',NULL,'2016-09-27 23:25:29'),('0000000383','00190','9','B','Pek Pengadaan Peralatan Sound System Auditorium','TS- Peng Peralatan Sound RSUD Kbm','Pek. Pengadaan Peralatan Sound System Auditorium RSUD Dr Soedirman Kebumen','2016-09-20','2016-10-25','Marketing','107',NULL,'2016-09-30 10:13:10'),('0000000384','00183','5','B','Pemasangan Proyektor','IS-PROYEKTOR-RSUDWONOSARI-2016-TTS','Pemasangan Proyektor','2016-10-03','2016-10-03','Development','156',NULL,'2016-10-03 10:08:52'),('0000000385','00002','001.01.00','B','SD-UPDATEPAS-SOETTA-2016-APT','SD-UPDATEPAS-SOETTA-2016-APT','Pemeliharaan Aplikasi Pas Bandar Udara','2016-08-02','2016-08-26','Development','16',NULL,'2016-10-06 10:56:32'),('0000000386','00189','001.08.00','B','Pelatihan Website menggunakan Code Igniter','BDC-TRAINING-AIRNAV-2016-TE','Pelatihan Website','2016-10-10','2016-10-13','Development','138',NULL,'2016-10-11 10:53:29'),('0000000387','00191','6','B','Pengadaan dan Instalasi CCTV','TC-CCTV-GMF-2016-TE','Pengadaan dan instalasi cctv','2016-10-08','2016-10-09','Maintenance','138',NULL,'2016-10-11 11:03:21'),('0000000388','00192','9','B','Belanja Infrastruktur (Server dan Rack Server)','TS-PL Belanja Server','Belanja Langsung Kebutuhan Server, Rack server dan','2016-10-10','2016-10-31','Marketing','107',NULL,'2016-10-12 13:24:58'),('0000000389','00193','9','B','Pengadaan Perlatan Multimedia','TS- Peng Peralatan Multimedia','Lelang Umum Pekerjaan Pengadaan Peralatan Multimedia','2016-10-03','2016-10-31','Lelang','107',NULL,'2016-10-15 09:48:18'),('0000000390','00099','9','B','Maintenance Berat (Lab & RT)','TS-Maintenance PC Lab','Pekerjaan Maintenance dan Installasi OS di Lab Komp','2016-10-12','2016-10-18','Maintenance','107',NULL,'2016-10-15 09:54:24'),('0000000391','00183','5','B','Mesin Presensi','IS-PRESENSI-RSUDWONOSARI-2016-TTS','Mesin Presensi','2016-10-18','2016-10-25','Development','156',NULL,'2016-10-17 10:33:39'),('0000000392','00190','9','B','Pengadaan Dan Installasi Ultra Short-Trow Projektor & Motorized Screen','TS- Peng&Instl ShortTrow Projektor& Screen','Pekerjaan Peng& Installasi ShortTrow Projektor & Motorized Screen','2016-10-26','2016-10-26','Development','107',NULL,'2016-10-27 08:52:46'),('0000000393','00194','9','B','Inisiasi Bakal Pekerjaan 2017','TS- Inisiasi ke Kominfo Klaten','Inisiasi Pekerjaan di KomInfo Klaten','2016-10-10','2016-12-26','Development','107',NULL,'2016-10-27 09:01:39'),('0000000394','00195','001.01.00','B','SD-SIAKAD-POLINDRA-2016','SD-SIAKAD-POLINDRA-2016','Sistem Akademik Polindra','2016-10-27','2016-12-31','Development','16',NULL,'2016-10-27 09:37:07'),('0000000395','00196','6','B','Pengadaan Server Sekretariat DPRD Blora','TC-SERVER-SEKDPRDBLORA-2016-APT','BDC-Yuda','2016-11-01','2016-12-01','Maintenance','67',NULL,'2016-11-02 11:27:27'),('0000000396','00196','6','B','Instalasi Jaringan Sekretariat DPRD Blora','TC-JARINGAN-SEKDPRDBLORA-2016-TTS','BDC-Yuda','2016-11-01','2016-12-01','Maintenance','67',NULL,'2016-11-02 11:26:51'),('0000000397','00197','5','B','Pengadaan Barang IT','IS-POS1-SEMARANG-2016-TTS','Pengadaan Barang IT PT POS Semarang I','2016-11-01','2016-11-08','Development','156',NULL,'2016-12-05 13:16:42'),('0000000398','00131','9','B','PENGADAAN BELANJA KERTAS CONTINEOUS FORM','TS- BL KERTAS CONTNS F-B5','Pengadaan Kebutuhan ATK berupa Kertas Contineous Form& Ribbon Printronik','2016-10-17','2016-11-28','Development','107',NULL,'2016-11-02 09:18:21'),('0000000399','00194','9','B','Pengadaan Belanja Komp Server, PC & Inst Server','TS- Peng Blj Server, PC dan Inst.Server','Pek. Belanja Komp Server, PC dan Installasi Server','2016-10-24','2016-11-28','Marketing','107',NULL,'2016-11-02 09:30:35'),('0000000400','00099','9','B','Pengadaan Komputer PC Lab-6 UPT Amikom','TS- Peng Komp PC Lab-6 UPT','Pek. Pengadaan Komp PC Lab-6 (81 unit) UPT Amikom','2016-10-24','2016-11-30','Marketing','107',NULL,'2016-11-02 09:34:57'),('0000000401','00121','9','B','Pek. Pengadaan Barang Modal & Peralatan Mesin','TS- Pek Peng Brg Modal& Peratn Mesin','Pek. Belanja Printer, Notebok, Laptop dan OS.','2016-10-03','2016-11-22','Marketing','107',NULL,'2016-11-02 12:48:45'),('0000000402','00001','6','B','Pengadaan Terpadu Infrastruktur RS Fatmawati Ruang Server to Gd Prof.Soelarto','TC-JARINGAN-CSAFATMAWATI-2016','Doni Wahyudi','2016-11-03','2016-12-03','Maintenance','62',NULL,'2016-11-03 10:53:16'),('0000000403','00058','5','B','INFRA','IS-TE-2016','INFRA','2016-11-18','2016-11-18','Development','156',NULL,'2016-11-18 12:15:07'),('0000000404','00006','3','B','ISO 2015','MR-ISO TE- 2016','Upgrade ISO 2015','2014-09-26','2016-12-31','Development','75',NULL,'2016-11-25 08:09:08'),('0000000405','00197','5','B','Pengadaan Barang IT PT POS Semarang II','IS-POS2-SEMARANG-2016-TE','Pengadaan Barang IT PT POS Semarang II','2016-12-01','2016-12-05','Development','156',NULL,'2016-12-05 13:50:44'),('0000000406','00199','9','B','Pengadaan Belanja Peripheral Jaringan Komp','TS- Pek Blj Peripheral Network','Pek. Pengadaan Peripheral Jaringan Komputer','2016-12-05','2016-12-30','Marketing','107',NULL,'2016-12-07 11:42:18'),('0000000407','00200','5','B','Pengadaan Barang IT','IS-POS-PADANG-2016-TE','Pengadaan Barang IT','2016-12-06','2016-12-31','Development','128',NULL,'2016-12-13 13:46:13'),('0000000408','00201','9','B','Inisiasi dan Survey Bakal Pek.Installasi Power BackUp','TS-Assmt & Survey ke Sari Roti','Bakal Pek Installasi Power BackUp di PT Sari Roti','2016-12-15','2016-12-16','Maintenance','107',NULL,'2016-12-17 11:00:09'),('0000000409','00002','5','B','Pengadaan Printer PAS Visitor','IS-PASVISITOR-SOETTA-2016-TE','Pengadaan PAS Visitor','2016-12-19','2016-12-23','Development','128',NULL,'2016-12-28 15:55:48'),('0000000410','00086','9','B','Pengadaan Komp Server, PC, Printer dan UPS','TS- Peng Server,PC Printer & Ups','PL Belanja Server, Komp PC, Printer & UPS','2016-11-27','2016-12-25','Development','107',NULL,'2016-12-21 09:17:32'),('0000000411','00202','6','B','Instalasi Jaringan Internet','TC-INT-KALIBRASI-2017','Instalasi Jaringan Internet','2017-01-01','2017-12-31','Marketing','16',NULL,'2016-12-24 09:33:58'),('0000000412','00002','5','B','Pengadaan Peralatan Penunjang PAS ONLINE','IS-PASONLINE-SOETTA-2016-FORTIS','Pengadaan Peralatan Penunjang PAS ONLINE','2016-12-28','2017-01-28','Development','128',NULL,'2016-12-28 15:51:03'),('0000000413','00192','9','B','Installasi System PABX di STTKD','TS- Inst PABX STTKD','Pek Installasi Sustem PABX di STTKD','2016-12-26','2017-01-07','Development','107',NULL,'2016-12-29 17:15:27'),('0000000414','00163','6','B','Internet Kominfo Blora','TC-INTERNET-DPPKKIBLORA-2017','20 Mbps','2017-01-02','2017-12-31','Development','62',NULL,'2017-01-03 08:38:21'),('0000000415','00006','6','B','ISP Internet 2017','TC-ISP-TE-2017','ISP Internet 2017','2017-01-01','2017-12-31','Maintenance','62',NULL,'2016-12-30 10:03:04'),('0000000416','00204','9','A','Realisasi Tax Amnesty 2016','TS- Tax Amnesty','Proses realisasi TA 2016','2016-11-07','2017-01-06','Development','107',NULL,'2016-12-31 09:14:59'),('0000000417','00170','001.01.00','B','SD-CBT-POLDAJATIM-2017-TE','SD-CBT-POLDAJATIM-2017-TE','CBT Kenaikan Pangkat','2017-01-04','2017-01-04','Development','16',NULL,'2017-03-17 09:03:36'),('0000000418','00006','001.01.00','B','SD-MANAJEMEN-SOFTDEV-2017','SD-MANAJEMEN-SOFTDEV-2017','Pekerjaan / Tugas Manajemen Softdev tahun 2017','2017-01-01','2017-12-31','Development','16',NULL,'2017-01-05 15:33:07'),('0000000419','00057','7','B','MARKETING','MK-MARKETING-TE-2017','MARKETING','2017-01-01','2017-12-31','Marketing','120',NULL,'2017-01-09 13:36:01'),('0000000420','00072','2','B','TE-FA-KEU-2017','TE-FA-KEU-2017','Back Office','2017-01-01','2017-01-31','Development','67',NULL,'2017-01-10 08:29:53'),('0000000421','00205','001.08.00','B','BDC','BDC-2017','Operasional BDC','2017-01-01','2017-12-31','Marketing','138',NULL,'2017-01-10 10:32:21'),('0000000422','00134','001.01.00','B','SD-UPDATEDPO-KEMENKOP-2017-TE','SD-UPDATEDPO-KEMENKOP-2017-TE','Pengembangan Aplikasi Deputi Pembiayaan Online','2017-01-09','2017-04-10','Development','16',NULL,'2017-01-10 11:31:43'),('0000000423','00134','001.01.00','B','SD-UPDATEEKOP-KEMENKOP-2017-TE','SD-UPDATEEKOP-KEMENKOP-2017-TE','Pengembangan Aplikasi E-Koperasi (Usaha Simpan Pinjam oleh Koperasi)','2017-02-08','2017-07-10','Development','16',NULL,'2017-03-17 09:07:03'),('0000000424','00002','001.01.00','B','SD-PASVISITOR-SOETTA-2016','SD-PASVISITOR-SOETTA-2016','Pengembangan Aplikasi Pas Visitor','2016-12-01','2016-12-31','Development','16',NULL,'2017-01-16 11:15:47'),('0000000425','00058','5','B','Infra','IS-TE-2017','Infra','2017-01-16','2017-01-16','Development','156',NULL,'2017-01-16 13:59:25'),('0000000426','00006','3','A','HR-HRD-TE-2017','HR-HRD-TE-2017','HR-HRD-TE-2017','2017-01-01','2017-12-31','Development','16',NULL,'2017-01-23 08:12:23'),('0000000427','00088','9','B','Inisiasi  Bakal Belanja Workstation 2017','TS- Realisasi Belanja Workstation 2017 [PT AMPU]','Bakal Belanja Workstation 2017','2017-01-22','2017-03-31','Marketing','107',NULL,'2017-06-20 10:31:02'),('0000000428','00204','9','B','REHAB BERAT  DIV TEKNIS TE-TOKO','TS- Rehab Berat Gedung Teknis','REHAB  BERAT','2017-01-28','2017-01-30','Maintenance','107',NULL,'2017-01-27 17:03:04'),('0000000429','00197','5','B','Pengadaan Perbaikan Jaringan LAN','IS-LAN-POSSEMARANG-2017-TE','Pengadaan Perbaikan Jaringan LAN Bantul & Wonosari','2017-01-30','2017-01-30','Development','156',NULL,'2017-11-23 09:56:22'),('0000000430','00086','9','B','Belanja  Perangkat Komputer [Awal Tahun/T-1]','TS- Belanja Perangkat Komputer','Belanja KOmputer PC, Notebook dan Printer','2017-01-30','2017-02-28','Marketing','107',NULL,'2017-02-01 09:37:09'),('0000000431','00165','001.01.00','B','E-OFFICE','SD-EOFFICE-BKP-2017','JAKARTA','2017-02-01','2017-05-31','Development','16',NULL,'2017-09-26 14:57:57'),('0000000432','00202','001.01.00','B','SD-EOFFICE-KALIBRASI-2017','SD-EOFFICE-KALIBRASI-2017','Pekerjaan E-Office Kalibrasi (Bonus dari internet)','2017-02-16','2017-02-17','Development','16',NULL,'2017-02-13 12:04:17'),('0000000433','00202','001.01.00','B','SD-SUPPLYCHAIN-KALIBRASI-2017','SD-SUPPLYCHAIN-KALIBRASI-2017','SD-SUPPLYCHAIN-KALIBRASI-2017','2017-02-01','2017-02-01','Marketing','16',NULL,'2017-02-16 11:11:55'),('0000000434','00172','13','B','TE-GA & BILLING 2017','TE-GA & BILLING 2017','GENERAL AFFAIRS DEPARTMENT','2017-01-01','2017-12-31','Development','91',NULL,'2017-02-17 14:44:46'),('0000000436','00121','9','B','Pengadaan Komputer Server','TS- Belanja Komp Server','Pek PL Belanja Komputer Server dan Pendukungnya','2017-02-15','2017-03-30','Marketing','107',NULL,'2017-02-24 16:10:46'),('0000000437','00194','9','B','Inisiasi  Belanja Kebutuhan IT KOmp di Triwulqn-I','TS- Insiasi Pekerjaan di T-1 Diskominfo Klaten','Inisiasi  Bakal pekerjaan di Triwuan I- Diskominfo Klaten','2017-02-27','2017-03-31','Marketing','107',NULL,'2017-02-28 09:18:13'),('0000000438','00086','9','B','Belanja Maintenance Rutin 3 bulanan','TS- Maintenance Rutin Bulanan Thn-2017','Pekerjaan Maintenance Rutin  Tahun 2017 di BPPKAD Tmgg','2017-02-01','2017-03-30','Maintenance','107',NULL,'2017-02-28 09:20:50'),('0000000439','00099','9','B','Maintenance Rutin Triwulan-1 (Jan-Feb-Mar)','TS- Maintenance Rutin T-1 Amikom (UPT-RT-IC)','Pekerjaan Maintenance Rutin di Triwulan-1  Amikom','2017-01-16','2017-03-31','Maintenance','107',NULL,'2017-02-28 09:33:26'),('0000000440','00018','001.01.00','B','SD-MTCEPERFORMANCE-DEPHUBUD-2017-APT','SD-MTCEPERFORMANCE-DEPHUBUD-2017-APT','Pekerjaan Maintenance E-Performance Pustikom','2017-03-15','2017-05-15','Marketing','16',NULL,'2017-03-17 09:39:10'),('0000000441','00018','001.01.00','B','SD-MTCEPLANNING-DEPHUBUD-2017-TTS','SD-MTCEPLANNING-DEPHUBUD-2017-TTS','Pekerjaan Maintenance E-Planning Pustikom','2017-03-08','2017-05-08','Development','16',NULL,'2017-03-17 09:16:39'),('0000000442','00206','9','B','Belanja Modal Pengadaan Alat Pendingin','TS- Peng. Alat Pendingin','Pengadaan Fan-Blower, AC Standing dan AC Split','2017-03-15','2017-04-30','Marketing','107',NULL,'2017-03-16 17:24:51'),('0000000443','00165','001.01.00','B','SD-ANTRIAN-BKP-2017-FS','SD-ANTRIAN-BKP-2017-FS','Pemeliharaan Numerator Antrian Medex (semula TE, terus ganti FS)','2017-02-28','2017-07-08','Development','16',NULL,'2017-12-19 14:23:23'),('0000000444','00165','001.01.00','B','SD-MEDEX-BKP-2017-TE','SD-MEDEX-BKP-2017-TE','Pemeliharaan Database Medical Check Up','2017-02-27','2017-07-09','Development','16',NULL,'2017-03-17 09:11:17'),('0000000445','00168','5','B','Pengadaan Rak Server & Router','IS-RakServer-KominfoTangsel-APT-2017','Pengadaan Rak Server & Router','2017-04-07','2017-04-07','Canceled','156',NULL,'2017-10-25 09:32:12'),('0000000446','00203','6','B','Revitalisasi jaringan kominfo kabupaten blora','TC-REVJAR-BLORA-2017-TTS','tahun 2017','2017-04-01','2017-05-31','Maintenance','62',NULL,'2017-04-25 13:35:09'),('0000000447','00203','001.01.00','B','SD-WEB-BLORA-2017-AP','SD-WEB-BLORA-2017-AP','Pengadaan Modul Web PemKab Blora','2017-04-17','2017-06-15','Development','16',NULL,'2017-04-27 13:53:13'),('0000000448','00207','001.01.00','B','SD-RKA-ANRI-2017','SD-RKA-ANRI-2017','Aplikasi Rencana Kegiatan Anggaran','2017-04-27','2017-04-27','Development','16',NULL,'2017-04-27 13:44:14'),('0000000449','00208','001.08.00','B','BDC-IPANGAN-BUMR-2017','BDC-IPANGAN-BUMR-2017','Masterplan I-Pangan Kedepan aplikasi dan infra kita pegang','2017-05-02','2017-07-10','Development','16',NULL,'2017-06-12 13:37:22'),('0000000450','00190','9','B','Inisiasi  Bakal Pek  T3  2017 RSUD  Dr Soedirman','TS- Inisiasi Pek T3 2017 Rsud Dr Soedirman','Inisiasi :  Presentasi Bakal Pek  TV Information Display','2017-05-04','2017-08-30','Development','107',NULL,'2017-08-09 15:10:02'),('0000000451','00001','5','B','INSTALASI INFRASTRUKTUR MARS RS. FATMAWATI','IS-INFRAMARS-FATMAWATI-2017-','INSTALASI INFRASTRUKTUR MARS RS. FATMAWATI','2017-05-09','2017-05-09','Development','156',NULL,'2017-05-09 09:12:23'),('0000000452','00018','001.01.00','B','SD-DIRANGUD-KEMENHUB-2017','SD-DIRANGUD-KEMENHUB-2017','Seluruh pekerjaan di Dirangud Kemenhub','2017-01-02','2017-12-29','Marketing','16',NULL,'2017-05-22 09:07:16'),('0000000453','00001','001.01.00','B','SD-CSARSF-MANDIRI-2017-TE','SD-CSARSF-MANDIRI-2017-TE','CSA MHAs RSUP Fatmawati','2017-05-29','2017-06-30','Development','16',NULL,'2017-05-29 11:40:08'),('0000000454','00203','6','B','Instalasi Mail Server','TC-MailServer-Blora-2017-TTS','-','2017-06-01','2017-07-01','Development','62',NULL,'2017-06-05 13:03:52'),('0000000455','00193','9','B','Pengadaan  Peralatan  Multimedia [Lelang Umum]','TS- Peng Peralatan MulMed','Pekerjaan Lelang Umum, Pengadaan Peralatan Multimedia dan Networking','2017-07-06','2017-08-31','Lelang','107',NULL,'2017-07-07 11:36:43'),('0000000456','00018','001.01.00','B','SD-POSKO-KEMENHUB-2017','SD-POSKO-KEMENHUB-2017','Posko lebaran 2017','2017-06-15','2017-07-11','Development','16',NULL,'2017-07-14 09:46:45'),('0000000457','00127','001.01.00','B','SD-PASONLINE-MAKASSAR-2017-APT','SD-PASONLINE-MAKASSAR-2017-APT','PENGEMBANGAN APLIKASI E-OFFICE PERMOHONAN PAS','2017-07-19','2017-09-17','Development','16',NULL,'2017-07-24 12:18:28'),('0000000458','00168','5','B','Jaringan FO Kominfo Tangsel','IS-FO-KominfoTangsel-2017-TE','Pembelian alat gedung 3','2017-07-25','2017-08-15','Closed','128',NULL,'2018-01-30 10:28:57'),('0000000459','00168','5','B','FO Kominfo Tangsel','IS-FO-KominfoTangsel-2017-TTS','Pembelian alat gedung 2','2017-07-25','2017-08-15','Closed','128',NULL,'2018-01-30 10:28:47'),('0000000460','00168','5','B','FO Kominfo Tangsel','IS-FO-KOMINFOTANGSEL-2017-FS','jasa konsultan gedung 2','2017-07-25','2017-08-15','Closed','128',NULL,'2018-01-04 08:16:25'),('0000000461','00168','5','B','FO Kominfo Tangsel','IS-FO-KOMINFOTANGSEL-2017-APT','Jasa Konsultan geudng 3','2017-07-25','2017-08-15','Closed','128',NULL,'2018-01-04 08:16:14'),('0000000462','00165','5','B','Pengadaan & pemasangan Finger print beserta  instalasi','IS-FINGER-BKP-2017-APT','Pengadaan & pemasangan Finger print beserta  instalasi','2017-08-01','2017-10-01','Development','156',NULL,'2017-08-01 14:11:22'),('0000000463','00002','001.01.00','B','SD-PASMINGGUAN-SOETTA-2017','SD-PASMINGGUAN-SOETTA-2017','SD-PASMINGGUAN-SOETTA-2017','2017-08-07','2017-09-08','Failed','16',NULL,'2017-12-04 09:40:33'),('0000000464','00192','9','B','Belanja Rutin Bulanan 2017','TS- Bj IT Bulanan Sttkd2017','Belanja IT Bulanan Tahun 2017','2017-07-03','2017-11-30','Marketing','107',NULL,'2017-08-07 09:11:18'),('0000000465','00165','001.01.00','B','SD-SIMKEU-BKP-2017-TTS','SD-SIMKEU-BKP-2017-TTS','SD-SIMKEU-BKP-2017-TTs','2017-07-21','2017-11-10','Development','16',NULL,'2018-02-12 10:51:50'),('0000000466','00030','6','B','Revitalisasi Data Center dan Migrasi Server IST Akprind','TC-REVDCMIGRASI-AKPRIND-2017','Revitalisasi Data Center dan Migrasi Server IST Akprind - Dony Sambodo','2017-09-06','2017-09-20','Maintenance','62',NULL,'2017-09-04 11:40:50'),('0000000467','00209','5','B','Relokasi server ANGUD','IS-RELOKASISERVER-ANGUD-2017-FORTIS','Relokasi server ANGUD','2017-09-13','2017-09-20','Development','128',NULL,'2017-09-11 13:37:06'),('0000000468','00210','9','B','Penyediaan Sar-Pras IT di Bimtek Pengelolaan Gudang','TS-Sewa IT di Sby','Penyewaan Sarana Prasarana IT (NB- Printer- Electrical- Solusi WiFi)','2017-09-22','2017-09-30','Maintenance','107',NULL,'2017-09-23 13:22:57'),('0000000469','00165','001.01.00','B','SD-WEB-BKP-2017','SD-WEB-BKP-2017','SD-WEB-BKP-2017','2017-09-25','2017-09-25','Development','16',NULL,'2017-09-25 14:16:58'),('0000000470','00197','5','B','Pengadaan pembelian langsung gun reader, pc dan printer','IS-PEMBELIAN-POSSEMARANG-2017-TE','Pengadaan pembelian langsung gun reader, pc dan printer','2017-10-04','2017-10-25','Development','156',NULL,'2017-10-04 12:42:21'),('0000000471','00165','5','B','Pengadaan Server di BKP','IS-SERVER-BKP-2017-TTS','Pengadaan Server di BKP','2017-10-05','2017-10-12','Development','128',NULL,'2017-10-06 08:50:07'),('0000000472','00100','9','B','Peng Peralatan Peng JarKom','TS- Peng Alat JarKom','PL  Pengadaan Peralatan Jaringan Komputer','2017-10-09','2017-11-30','Marketing','107',NULL,'2017-10-12 17:32:53'),('0000000473','00197','5','B','Revitalisasi Jaringan LAN POS Wates','IS-LAN-WATES-2017-TE','Revitalisasi Jaringan LAN POS Wates','2017-10-13','2017-10-20','Development','128',NULL,'2017-10-13 15:51:42'),('0000000474','00197','5','B','Revitalisasi Jaringan LAN POS Klaten','IS-LAN-KLATEN-2017-TE','Revitalisasi Jaringan LAN POS Klaten','2017-10-13','2017-10-20','Development','128',NULL,'2017-10-13 15:52:35'),('0000000475','00208','001.01.00','B','SD-IPANGAN-BUMR-2017','SD-IPANGAN-BUMR-2017','SD-IPANGAN-BUMR-2017','2017-10-16','2017-12-30','Development','20',NULL,'2017-10-16 11:32:48'),('0000000476','00059','4','B','TE - PROJECT ADMINISTRATION','AD-ADMIN-TE-2017','admin project','2017-01-01','2017-12-31','Development','61',NULL,'2017-10-16 13:29:45'),('0000000477','00211','9','B','Inisiasi Data Center Kominfo Mgl-Kab','TS- Inisiasi Ke KomInfo 2017','Inisiasi Pek Akhir tahun & awal tahun 2018','2017-10-02','2017-12-30','Marketing','107',NULL,'2017-10-17 05:33:30'),('0000000478','00100','9','B','Assesment Pekerjaan Tahun 2018','TS- Inisiasi Pek DisKominfo Pwrj 2018','Assesment pekerjaan tahun 2018 di Diskominfo Pwrj','2017-10-16','2017-12-20','Development','107',NULL,'2017-10-17 20:51:31'),('0000000479','00165','5','B','Pengadaan CCTV','IS-CCTV-BKP-2017','Pengadaan CCTV di BKP','2017-10-19','2017-10-31','Development','128',NULL,'2017-10-19 08:27:15'),('0000000480','00100','9','B','Pek Jasa Perencanaan FO Tahap-II Des 2017','TS- Implementasi Pek Jasa Perencanaan FO','Pekerjaan Jasa Perencanaan FO Tahap-II  Diskominfo Purworejo','2017-10-19','2017-12-25','Development','107',NULL,'2017-10-21 13:00:54'),('0000000481','00127','5','B','Pengadaan Kios-K Otban Wil. V Makassar','IS-KIOSK-OTBANVMAKASSAR-2017-APT','Pengadaan Kios-K Otban Wil. V Makassar','2017-10-23','2017-10-24','Development','156',NULL,'2017-10-23 15:57:24'),('0000000482','00012','5','B','Pengadaan Server Otban Balikpapan','IS-Server-OtbanBalikpapan-2017-APT','Pengadaan Server Otban Balikpapan','2017-10-25','2017-10-31','Development','156',NULL,'2017-11-13 11:14:47'),('0000000483','00012','5','B','Relokasi jaringan PAS Otban Balikpapan','IS-PAS-OtbanBalikpapan-2017-APT','Relokasi jaringan PAS Otban Balikpapan','2017-10-25','2017-10-25','Development','156',NULL,'2017-11-23 09:55:42'),('0000000484','00002','001.01.00','B','SD-LISENSIPERSONEL-SOETTA-2017-TTS','SD-LISENSIPERSONEL-SOETTA-2017-TTS','SD-LISENSIPERSONEL-SOETTA-2017-TTS','2017-10-10','2017-12-10','Development','16',NULL,'2017-11-02 12:12:52'),('0000000485','00002','001.01.00','B','SD-SIMPONI-SOETTA-2017-FS','SD-SIMPONI-SOETTA-2017-FS','PEKERJAAN BIAYA INTEGRASI ANTARA SISTEM PAS ONLINE DENGAN SIMPONI','2017-10-10','2017-11-10','Development','16',NULL,'2017-11-02 12:14:08'),('0000000486','00002','001.01.00','B','SD-UPDATEPAS-SOETTA-2017-APT','SD-UPDATEPAS-SOETTA-2017-APT','UPDATE SISTEM PAS ONLINE','2017-10-10','2017-11-10','Development','16',NULL,'2017-11-02 12:15:04'),('0000000487','00203','6','B','Pengadaan UPS Server untuk Aplikasi Integrasi','TC-PENGADAANUPSSERVER-BLORA-2017-TE','Pengadaan UPS & Server','2017-11-09','2017-11-29','Development','168',NULL,'2017-11-03 08:18:18'),('0000000488','00203','6','B','Pengadaan Server untuk Aplikasi Integrasi','TC-PENGADAANSERVER-BLORA-2017-TTS','Pengadaan Server','2017-11-09','2017-11-29','Development','168',NULL,'2017-11-03 08:18:47'),('0000000489','00168','5','B','FO Kecamatan Tangsel','IS-FOKECAMATAN-KOMINFOTANGSEL-2017','FO Kecamatan Tangsel','2017-11-06','2017-11-15','Development','128',NULL,'2017-12-08 10:35:39'),('0000000490','00212','5','B','Perencanaan data center','IS-DATA-CENTER-PALEMBANG-2017-APT','Perencanaan data center','2017-11-09','2017-11-27','Development','156',NULL,'2017-11-15 15:46:10'),('0000000491','00197','5','B','Revitalisasi Jaringan LAN Pos Kebumen','IS-LAN-KEBUMEN-2017-TE','Revitalisasi Jaringan LAN Pos Kebumen','2017-11-17','2017-11-30','Development','156',NULL,'2017-11-15 08:53:42'),('0000000492','00197','5','B','Revitalisasi Jaringan LAN Pos Purworejo','IS-LAN-PURWOREJO-2017-TE','Revitalisasi Jaringan LAN Pos Purworejo','2017-11-17','2017-11-30','Development','156',NULL,'2017-11-27 15:39:57'),('0000000493','00002','5','B','PENGADAAN SARANA DAN PRASARANA APLIKASI PAS VISITOR','IS-PASTERM2-SOETTA-2017-FS','PENGADAAN SARANA DAN PRASARANA APLIKASI PAS VISITOR','2017-11-17','2017-11-30','Development','156',NULL,'2017-11-15 15:18:46'),('0000000494','00123','001.01.00','B','SD-SURVEY-BALI-2017','SD-SURVEY-BALI-2017','Aplikasi Survey Pelayanan','2017-11-01','2017-11-01','Development','16',NULL,'2017-11-16 16:41:41'),('0000000496','00100','9','B','Blj Perlengkapan KOmputer Langsung Kec.Pituruh','TS- Blj Lsg KOmp Kec.Pituruh','Belanja Langsung Projektor, NB, Printer dan SmartPhone','2017-11-25','2017-12-10','Marketing','107',NULL,'2017-11-30 09:23:53'),('0000000497','00168','5','B','Instalasi FO Dukcapil dan Bappeda','IS-FODUKCAPILBAPEDA-KOMINFOTANGSEL-2017','Instalasi FO Dukcapil dan Bappeda','2017-12-01','2017-12-31','Development','128',NULL,'2017-12-05 09:33:06'),('0000000498','00211','9','B','Pengadaan Panel Listrik dan Grounding Ruang Server','TS- Pek ME-DC','Pekerjan ME [Mechanical Electrical] Pengadaan Panel Listrik dan Grounding  Data Center','2017-11-13','2017-12-29','Marketing','107',NULL,'2017-12-07 17:21:13'),('0000000499','00047','001.01.00','B','SD-HUKDIS-KEMENPU-2017','SD-HUKDIS-KEMENPU-2017','Kegiatan Pengelolaan Administrasi Kepegawaian (Perseorangan a.n. Mas Agung))','2017-12-01','2017-12-31','Development','16',NULL,'2017-12-19 14:26:50'),('0000000500','00168','5','B','Pengadaan Perangkat Zone Director AP','IS-ZD-KOMINFOTANGSEL-2017','Pengadaan Perangkat Zone Director AP','2017-12-01','2017-12-10','Development','128',NULL,'2017-12-20 15:11:23'),('0000000501','00215','6','B','internet Akprind','TC-Internet-Akprind-2018','Internet','2018-01-01','2018-12-31','Development','62',NULL,'2018-01-02 10:39:09'),('0000000502','00099','6','B','Internet AMIKOM','TC-Internet-Amikom-2018','Internet','2018-01-01','2018-12-31','Development','62',NULL,'2018-01-02 10:40:19'),('0000000503','00152','6','B','Internet USM','TC-Internet-USM-2018','Internet','2018-01-01','2018-12-31','Development','62',NULL,'2018-01-02 10:41:09'),('0000000504','00216','6','B','Internet Soetta','TC-Internet-Soetta-2018','Internet','2018-01-01','2018-12-31','Development','62',NULL,'2018-01-02 10:41:52'),('0000000505','00217','6','B','Internet UII Hukum','TC-Internet-UIIHukum-2018','Internet','2018-01-01','2018-12-31','Development','62',NULL,'2018-01-02 10:43:05'),('0000000506','00218','6','B','Internet UII Bahasa','TC-Internet-UIIBahasa-2018','Internet','2018-01-01','2018-12-31','Development','62',NULL,'2018-01-02 10:43:56'),('0000000507','00022','6','B','Internet Apikri','TC-Internet-Apikri-2018','Internet','2018-01-01','2018-12-31','Development','62',NULL,'2018-01-02 10:44:42'),('0000000508','00219','6','B','Internet MCS','TC-Internet-MCS-2018','Internet','2018-01-01','2018-12-31','Development','62',NULL,'2018-01-02 10:45:31'),('0000000509','00042','6','B','Internet Linus','TC-Internet-Linus-2018','Internet','2018-01-01','2018-12-31','Development','62',NULL,'2018-01-02 10:49:20'),('0000000510','00220','6','B','Internet Pak Yanto','TC-Internet-PakYanto-2018','Internet','2018-01-01','2018-12-31','Development','62',NULL,'2018-01-02 10:50:09'),('0000000511','00221','6','B','Internet Bandara Curug Budiharto','TC-Internet-BandaraCurug-2018','Internet','2018-01-01','2018-12-31','Development','62',NULL,'2018-01-02 10:51:16'),('0000000512','00222','6','B','Internet Dirnav Lt23','TC-Internet-Dirnavlt23-2018','Internet','2018-01-01','2018-12-31','Development','62',NULL,'2018-01-02 10:52:06'),('0000000513','00223','6','B','Internet SMK Batik Purworejo','TC-Internet-SMKBatik-2018','Internet','2018-01-01','2018-12-31','Development','62',NULL,'2018-01-02 10:53:05'),('0000000514','00006','6','B','Internet TE','TC-ISP-TE-2018','Internet ISP','2018-01-01','2018-12-31','Development','62',NULL,'2018-01-02 10:56:35'),('0000000515','00115','6','B','Internet Damai','TC-Internet-Damai-2018','Internet','2018-01-01','2018-12-31','Development','62',NULL,'2018-01-02 10:58:14'),('0000000516','00054','6','B','Internet MGTV','TC-Internet-MGTV-2018','Internet','2018-01-01','2018-12-31','Development','62',NULL,'2018-01-02 10:58:52'),('0000000517','00052','6','B','Internet Kost Pogung','TC-Internet-kostpogung-2018','Internet','2018-01-01','2018-12-31','Development','62',NULL,'2018-01-02 10:59:37'),('0000000518','00044','6','B','Internet SBI','TC-Internet-SBI-2018','Internet','2018-01-01','2018-12-31','Development','62',NULL,'2018-01-02 11:01:34'),('0000000519','00224','6','B','Internet Dephub Lt20 Hukum','TC-Internet-hukum20-2018','Internet','2018-01-01','2018-12-31','Development','62',NULL,'2018-01-02 11:02:24'),('0000000520','00225','6','B','Internet Dephub Ditbandara Lt24','TC-Internet-Ditban24-2018','Internet','2018-01-01','2018-12-31','Development','62',NULL,'2018-01-02 11:03:48'),('0000000521','00091','6','B','Internet BP2T','TC-Internet-BP2T-2018','Internet','2018-01-01','2018-12-31','Development','62',NULL,'2018-01-02 11:05:33'),('0000000522','00007','6','B','Internet KNKT','TC-Internet-KNKT-2018','Internet','2018-01-01','2018-12-31','Development','62',NULL,'2018-01-02 11:06:20'),('0000000523','00165','6','B','Internet Balai Kesehatan Penerbangan','TC-Internet-BKP-2018','Internet','2018-01-01','2018-12-31','Development','62',NULL,'2018-01-02 11:07:16'),('0000000524','00226','6','B','Internet Oketiket','TC-Internet-Oketiket-2018','Internet','2018-01-01','2018-12-31','Development','62',NULL,'2018-01-02 11:24:16'),('0000000525','00031','6','B','Internet Arundina','TC-Internet-Arundina-2018','Internet','2018-01-01','2018-12-31','Development','62',NULL,'2018-01-02 11:24:59'),('0000000526','00227','6','B','Internet Pak Kusnawi','Tc-Internet-Kusnawi-2018','Internet','2018-01-01','2018-12-31','Development','62',NULL,'2018-01-02 11:31:41'),('0000000527','00162','6','B','Internet Balai Teknik Penerbangan','TC-Internet-BTP-2018','Internet','2018-01-01','2018-12-31','Development','62',NULL,'2018-01-02 11:32:35'),('0000000528','00228','6','B','Internet Sekdirjen Lt20','TC-Internet-SekdirjenLt20-2018','Internet','2018-01-01','2018-12-31','Development','62',NULL,'2018-01-02 11:33:31'),('0000000529','00229','6','B','Internet SMP N 2 Ngaglik','TC-Internet-SMPN2Ngaglik-2018','Internet','2018-01-01','2018-12-31','Development','62',NULL,'2018-01-02 11:34:18'),('0000000530','00006','6','B','TE Rolling BSD','TC-RollingBSD-TE-2018','Rolling Maintenance','2018-01-01','2018-12-31','Development','62',NULL,'2018-01-02 11:36:48'),('0000000531','00043','6','B','Internet MSV','TC-Internet-MSV-2018','Internet','2018-01-01','2018-12-31','Development','62',NULL,'2018-01-02 11:38:14'),('0000000532','00092','6','B','Internet KLH','TC-Internet-KLH-2018','Internet','2018-01-01','2018-12-31','Development','62',NULL,'2018-01-02 11:39:00'),('0000000533','00261','6','B','Internet Tangsel','TC-Internet-Diskominfotangsel-2018','Internet 24 bulan 10.800.000.000','2018-01-01','2018-12-31','Development','62',NULL,'2018-02-05 10:10:26'),('0000000534','00259','6','B','Internet Surgika','TC-Internet-Surgika-2018','Internet','2018-01-01','2018-12-31','Development','62',NULL,'2018-01-02 11:46:45'),('0000000535','00230','6','B','Internet Bu Yanti','TC-Internet-Buyanti-2018','Internet','2018-01-01','2018-12-31','Development','62',NULL,'2018-01-02 15:05:00'),('0000000536','00231','6','B','Internet  Soho Syamsudin','TC-Internet-Syamsudin-2018','Internet','2018-01-01','2018-12-31','Development','62',NULL,'2018-01-02 15:06:09'),('0000000537','00232','6','B','Internet indoscreen','TC-Internet-indoscreen-2018','Internet','2018-01-01','2018-12-31','Development','62',NULL,'2018-01-02 15:06:52'),('0000000538','00233','6','B','Internet  SMK N 1 Nanggulan','TC-Internet-SMKN1Nanggulan-2018','Internet','2018-01-01','2018-12-31','Development','62',NULL,'2018-01-02 15:09:36'),('0000000539','00234','6','B','Internet UMP','TC-Internet-UMP-2018','Internet','2018-01-01','2018-12-31','Development','62',NULL,'2018-01-02 15:43:29'),('0000000540','00180','6','B','Internet  Lam Cocoa','TC-Internet-Lamcoco-2018','Internet','2018-01-01','2018-12-31','Development','62',NULL,'2018-01-02 15:44:12'),('0000000541','00235','6','B','Internet SMK Diponegoro','TC-Internet-SMKDiponegoro-2018','Internet','2018-01-01','2018-12-31','Development','62',NULL,'2018-01-02 15:44:55'),('0000000542','00236','6','B','Internet Pak Fauzi','TC-Internet-Fauzi-2018','Internet','2018-01-01','2018-12-31','Development','62',NULL,'2018-01-02 15:45:47'),('0000000543','00237','6','B','Internet  Semesta Indovest','TC-Internet-Indovest-2018','Internet','2018-01-01','2018-12-31','Development','62',NULL,'2018-01-02 15:46:40'),('0000000544','00203','6','B','Internet  Diskominfo Blora','TC-Internet-diskominfoblora-2018','Internet','2018-01-01','2018-12-31','Development','62',NULL,'2018-01-02 15:47:39'),('0000000545','00202','6','B','Internet  Balai Kalibrasi','TC-Internet-kalibrasi-2018','Internet','2018-01-01','2018-12-31','Development','62',NULL,'2018-01-02 15:48:31'),('0000000546','00183','6','B','Internet  RSUD Wonosari','TC-Internet-RSUDWonosari-2018','Internet','2018-01-01','2018-12-31','Development','62',NULL,'2018-01-02 15:49:16'),('0000000547','00238','6','B','Internet','TC-Internet-Evaluasi21-2018','Internet','2018-01-01','2018-12-31','Development','62',NULL,'2018-01-02 15:51:19'),('0000000548','00239','6','B','Internet  Perencanaan','TC-Internet-Perencanaan21-2018','Internet','2018-01-01','2018-12-31','Development','62',NULL,'2018-01-02 15:52:03'),('0000000549','00038','6','B','Internet Hamzanwadi','TC-Internet-Hamzanwadi-2018','Internet','2018-01-01','2018-12-31','Development','62',NULL,'2018-01-02 15:52:48'),('0000000550','00241','6','B','Internet  Biro Kerjasama Lt 3','TC-Internet-Birokerjasama-2018','Internet','2018-01-01','2018-12-31','Development','62',NULL,'2018-01-02 15:53:35'),('0000000551','00242','6','B','Internet  SMK N 1 Temon','Tc-Internet-SMKN1Temon-2018','Internet','2018-01-01','2018-12-31','Development','62',NULL,'2018-01-02 15:54:53'),('0000000552','00243','6','B','Internet  MTS Piyungan','TC-Internet-MTS7-2018','Internet','2018-01-01','2018-12-31','Development','62',NULL,'2018-01-02 15:55:44'),('0000000553','00072','2','B','FA-2018','FA-2018','Operasional Finance','2018-01-01','2018-12-31','Development','67',NULL,'2018-01-03 14:55:43'),('0000000554','00006','3','A','HRD 2018','HR-HRD-TE 2018','HRD','2018-01-01','2018-12-31','Development','75',NULL,'2018-01-04 09:01:01'),('0000000555','00263','6','B','Internet Dedicated 2 Mbps','TC-Internet-Pratama-2018','Internet','2018-01-04','2018-12-04','Development','62',NULL,'2018-01-04 10:25:11'),('0000000556','00264','6','B','Internet Soho','TC-Internet-sohoridwan-2018','Internet','2018-01-03','2018-12-31','Development','62',NULL,'2018-01-04 10:31:06'),('0000000557','00058','5','B','Anggaran IS 2018','IS-2018','Anggaran Infrastruktur Tahun 2018','2018-01-01','2018-12-31','Development','128',NULL,'2018-01-05 08:51:23'),('0000000558','00244','6','B','Internet SMA N 6 Purworejo','TC-Internet-SMAN6Purworejo-2018','Internet','2018-01-05','2018-12-31','Development','62',NULL,'2018-01-05 09:14:08'),('0000000559','00245','6','B','Internet Dinas Perijinan Purworejo','TC-Internet-PerijinanPurworejo-2018','Internet','2018-01-05','2018-12-31','Development','62',NULL,'2018-01-05 09:16:19'),('0000000560','00246','6','B','Internet Soho Pak Wawan','TC-Soho-Wawan-2018','Internet','2018-01-05','2018-12-31','Development','62',NULL,'2018-01-05 09:17:42'),('0000000561','00247','6','B','Internet Soho Pak Muhtadi','TC-Soho-Muhtadi-2018','internet','2018-01-01','2018-12-31','Development','62',NULL,'2018-01-05 09:18:26'),('0000000562','00248','6','B','Internet soho wedomartani','TC-Soho-Wedomartani-2018','Internet','2018-01-01','2018-12-31','Development','62',NULL,'2018-01-05 09:21:19'),('0000000563','00249','6','B','Internet Soho Rina','TC-Soho-Rina-2018','Internet','2018-01-01','2018-12-31','Development','62',NULL,'2018-01-05 09:22:29'),('0000000564','00250','6','B','Internet Soho Heri','TC-Soho-Heri-2018','Internet','2018-01-01','2018-12-31','Maintenance','62',NULL,'2018-01-05 09:24:20'),('0000000565','00251','6','B','Internet Mungkid','TC-Internet-SMKMungkid-2018','Internet','2018-01-01','2018-12-31','Development','62',NULL,'2018-01-05 09:25:35'),('0000000566','00252','6','B','Internet SMA N 1 Girimulyo','TC-Internet-SMAN1Giri-2018','Internet','2018-01-01','2018-12-31','Development','62',NULL,'2018-01-05 09:28:39'),('0000000567','00035','6','B','Internet BPTP','TC-internet-BPTP-2018','Internet','2018-01-01','2018-12-31','Development','62',NULL,'2018-01-05 09:29:17'),('0000000568','00240','6','B','Internet FO ANGUD','TC-Internet-Angud21-2018','Internet 60 Mbps','2018-01-01','2018-12-31','Development','62',NULL,'2018-01-05 09:31:38'),('0000000569','00253','6','B','Internet SD IT Salman Al Farizi','TC-Internet-SDSalman-2018','Internet','2018-01-01','2018-12-31','Development','62',NULL,'2018-01-05 09:32:40'),('0000000570','00195','6','B','internet Polindra','TC-Internet-Polindra-2018','Internet','2018-01-01','2018-12-31','Development','62',NULL,'2018-01-05 09:34:05'),('0000000571','00254','6','B','Internet Kost Shaidah','TC-Soho-Shaidah-2018','Internet','2018-01-01','2018-12-31','Development','62',NULL,'2018-01-05 09:34:56'),('0000000572','00255','6','B','Internet SMP N 3 Depok','TC-internet-SMPN3Depok-2018','Internet','2018-01-01','2018-12-31','Development','62',NULL,'2018-01-05 09:36:14'),('0000000573','00045','6','B','Internet Skill','TC-Internet-Skill-2018','Internet','2018-01-02','2018-12-31','Development','62',NULL,'2018-01-05 09:36:55'),('0000000574','00257','6','B','Internet Thoqriq','TC-Internet-Jogtron-2018','Internet','2018-01-01','2018-12-31','Development','62',NULL,'2018-01-05 09:37:51'),('0000000575','00033','6','B','Internet Bethesda','TC-Internet-Bethesda-2018','Internet','2018-01-01','2018-12-31','Development','62',NULL,'2018-01-05 09:38:46'),('0000000576','00256','6','B','Internet SMA N 7 Purworejo','TC-Internet-SMAN7Purworejo-2018','Internet','2018-01-01','2018-12-31','Development','62',NULL,'2018-01-05 09:39:44'),('0000000577','00046','6','B','Internet SMA N 1 Sleman','TC-Internet-SMASleman-2018','Internet','2018-01-01','2018-12-31','Development','62',NULL,'2018-01-05 09:40:45'),('0000000578','00166','6','B','Internet SMA N 1 Temon','TC-Internet-SMATemon-2018','Internet','2018-01-01','2018-12-31','Development','62',NULL,'2018-01-05 09:41:59'),('0000000579','00262','6','B','Internet SMP Salman Al Farizi','TC-Internet-SMPSalman-2018','Internet','2018-01-01','2018-12-31','Development','62',NULL,'2018-01-05 09:44:07'),('0000000580','00260','6','B','Internet Jogja Scrummy','TC-Internet-Scrummy-2018','Internet','2018-01-01','2018-12-31','Development','62',NULL,'2018-01-05 09:44:49'),('0000000581','00258','6','B','Internet Transtra','TC-Internet-Transtra-2018','Internet','2018-01-01','2018-12-31','Development','62',NULL,'2018-01-05 09:45:28'),('0000000582','00265','6','B','Internet FO Pusbang','TC-Internet-PusbangSDM-2018','Internet','2018-01-01','2018-12-31','Development','62',NULL,'2018-01-05 09:47:30'),('0000000583','00225','6','B','Revitalisasi Jaringan Kantor DBU Lt24','TC-RevJar-DBULt24-2018','-','2018-01-08','2018-12-31','Marketing','62',NULL,'2018-01-08 11:35:32'),('0000000584','00240','6','B','Revitaslisasi Jaringan Angud Lt21','TC-RevJar-AngudLT21-2018','-','2018-01-08','2018-01-31','Marketing','62',NULL,'2018-01-08 11:36:13'),('0000000585','00224','6','B','Revitalisasi Jaringan Kantor Hukum Lt20','TC-RevJar-HukumLt20-2018','-','2018-01-08','2018-01-31','Marketing','62',NULL,'2018-01-08 11:36:59'),('0000000586','00261','6','B','Belanja Modal Laptop Monitoring Jaringan','TC-Monitoring-DISKOMINFOTANGSEL-2018','19.782.000','2018-01-08','2018-01-31','Development','62',NULL,'2018-02-05 10:07:54'),('0000000587','00221','6','B','Instalasi CCTV','TC-CCTV-Budiharto-2018','Pembuatan RAB oleh Teknis (8 Jan 2018)','2018-01-08','2018-01-31','Development','62',NULL,'2018-01-08 11:38:56'),('0000000588','00266','6','B','Internet','TC-Internet-OtbandCargo-2018','-','2018-01-08','2018-12-31','Development','62',NULL,'2018-01-08 11:41:24'),('0000000589','00202','6','B','Instalasi internal','TC-LAN-BalaiKalibrasi-2018','-','2018-01-08','2018-01-31','Marketing','62',NULL,'2018-01-08 11:42:41'),('0000000590','00268','7','B','MK-MARKETING-2018','MK-MARKETING-2018','MARKETING','2018-01-01','2018-12-31','Development','194',NULL,'2018-01-10 15:42:19'),('0000000591','00267','7','B','Pembuatan Video Animasi','MK-VIDEO-NAVIGASI-2017','Pembuatan Video Animasi','2017-11-21','2018-01-31','Development','194',NULL,'2018-01-10 15:02:14'),('0000000592','00006','001.01.00','B','SD-MANAJEMEN-TE-2018','SD-MANAJEMEN-TE-2018','Pekerjaan Softdev Manajemen','2018-01-01','2018-12-31','Development','16',NULL,'2018-01-11 08:19:05'),('0000000593','00199','9','B','Inisiasi Pekerjaan Di Triwulan-I 2018 Diskominfo Mglg-Kab','TS- Inisiasi Pek di T1-2018 Kominfo Mgl','Inisiasi  Pekerjaan Awal Tahun Diskominfo Mgl-Kab','2018-01-08','2018-03-30','Development','107',NULL,'2018-01-11 09:16:51'),('0000000594','00269','001.01.00','B','SD-DITKAPEL-KEMENHUB-2018','SD-DITKAPEL-KEMENHUB-2018','Pekerjaan Cluster DITKAPEL','2018-01-01','2018-12-31','Marketing','16',NULL,'2018-01-12 16:13:10'),('0000000595','00216','001.01.00','B','SD-SOETTA-KEMENHUB-2018','SD-SOETTA-KEMENHUB-2018','Pekerjaan Cluster KOBU I - SOETTA','2018-01-01','2018-12-31','Marketing','16',NULL,'2018-01-12 16:27:13'),('0000000596','00270','6','B','Internet soho up to 20 Mbps Agustinus','TC-SOHO-AGUSTINUS-2018','reseller','2018-01-12','2018-12-31','Development','62',NULL,'2018-01-15 08:54:25'),('0000000597','00271','6','B','Masih survey','TC-Internet-Kontraktor-2018','-','2018-01-10','2018-12-31','Marketing','62',NULL,'2018-01-15 08:58:36'),('0000000598','00272','6','B','Internet, masih survey','TC-ISP-SurgikaSemarang-2018','Internet','2018-01-22','2018-12-31','Marketing','62',NULL,'2018-01-22 08:54:29'),('0000000599','00273','6','B','Internet, masih survey','TC-ISP-KelPurworejo-2018','Internet','2018-01-22','2018-12-31','Marketing','62',NULL,'2018-01-22 08:55:45'),('0000000600','00100','9','B','Inisiasi Pekerjaan di Triwulan-I 2018 Diskominfo Pwrj','TS- Inisiasi Pek di T1-2018 Kominfo Pwrj','Inisiasi Pekerjaan di Triwulan-1 2018','2018-01-15','2018-03-31','Maintenance','107',NULL,'2018-01-23 10:25:37'),('0000000601','00172','13','B','TE-GA&BILLING-2018','TE-GA&BILLING-2018','PEKERJAAN GA BILLING & PURCHASING','2018-01-01','2018-12-31','Development','91',NULL,'2018-01-23 15:29:36'),('0000000602','00245','6','B','Penarikan Kabel dan Instalasi AP','TC-LAN-Dinasperijinanpwrj-2018','-','2018-01-29','2018-12-31','Development','62',NULL,'2018-01-29 11:48:47'),('0000000603','00261','6','B','Belanja Modal Peralatan Jaringan LAN','TC-LAN-DiskominfoTangsel-2018','200.000.000','2018-02-01','2018-03-31','Development','62',NULL,'2018-02-05 10:09:06'),('0000000604','00261','6','B','WAN Diskominfo Tangsel 190.880.000','TC-WAN-DiskominfoTangsel-2018','PEMELIHARAAN JARINGAN WAN (WIDE AREA NETWORK) KOTA TANGERANG SELATAN','2018-02-01','2018-03-31','Development','62',NULL,'2018-02-05 10:08:36'),('0000000605','00261','6','B','Pengadaan Antenna Sectoral 90 Derajat','TC-Sektoral-Diskominfotangsel-2017','Pengadaan Antenna Sectoral 90 Derajat','2017-11-01','2018-02-10','Maintenance','62',NULL,'2018-02-05 10:51:31'),('0000000606','00261','6','B','Migrasi Jaringan Tangsekl','TC-MigrasiNet-DiskominfoTangsel-2018','-','2018-02-01','2018-04-30','Development','62',NULL,'2018-02-07 13:36:57'),('0000000607','00274','6','B','SOHO Botika up to 5 Mbps','TC-SOHO-Botika-2018','-','2018-02-01','2018-12-31','Development','62',NULL,'2018-02-12 10:45:26'),('0000000608','00006','6','B','Projek Eksternal - Non ISP','TC-Eksternal-TE-2018','Penguji,','2018-02-01','2018-12-31','Development','62',NULL,'2018-02-14 08:34:06'),('0000000609','00040','6','B','Internet Otband Padang 2018 FO Icon','TC-Internet-OtbandPadang-2018','-','2018-02-01','2018-12-31','Development','62',NULL,'2018-02-14 08:42:53'),('0000000610','00194','9','B','Inisiasi Pekerjaan di Triwulan-1  2018 Diskominfo Klaten','TS- Inisiasi Pek di T1-2018 Kominfo Klaten','Inisiasi  Pek Awal Tahun (triwulan-1)','2018-02-02','2018-03-31','Marketing','107',NULL,'2018-02-20 14:23:49'),('0000000611','00261','6','B','Backup Link','TC-BackupLink-DiskominfoTangsel-2018','Backup Link - anggaran perubahan','2018-02-23','2018-12-31','Development','62',NULL,'2018-02-23 10:42:03'),('0000000612','00261','6','B','Server Monitoring','TC-ServerMonitoring-DiskominfoTangsel-2018','Server Monitoring - anggaran perubahan','2018-02-23','2018-12-31','Development','62',NULL,'2018-02-23 10:42:56'),('0000000613','00276','6','B','Masih survey','TC-INTERNET-TUGASEKAWAN-2018','-','2018-02-26','2018-02-26','Marketing','150',NULL,'2018-02-23 11:46:41'),('0000000614','00277','5','B','Perancangan Data Center dan Jaringan','IS-DATACENTER-SLEMAN-2018-TTS','Belanja Pengadaan Komputer Mainframe/server','2018-01-31','2018-05-31','Development','194',NULL,'2018-03-01 15:59:42'),('0000000615','00261','6','B','Captive Portal','TC-CAPTIVEPORTAL-DISKOMINFOTANGSEL-2018','Captive Portal','2018-03-02','2018-12-31','Development','62',NULL,'2018-03-02 15:51:44'),('0000000616','00278','5','B','Perencanaan Data Center dan Jaringan','IS-DC-RSUDSleman-2018-TTS','Perencanaan Data Center dan Jaringan','2018-01-31','2018-04-01','Development','128',NULL,'2018-03-05 09:05:04'),('0000000617','00059','4','B','ADMIN PROJECT','AD-ADMIN-TE-2018','Internal Admin','2018-01-01','2018-12-31','Development','88',NULL,'2018-03-05 10:10:50'),('0000000618','00202','6','B','Penarikan FO Gd Utama ke Ruang Simulator','TC-FOGedungUtamaSimulator-Kalibrasi-2018','-','2018-03-05','2018-03-31','Maintenance','62',NULL,'2018-03-05 11:59:15'),('0000000619','00168','5','B','FO OPD Tangsel (Dishub)','IS-FO-DISHUBTANGSEL-2018-','FO OPD Tangsel (Dishub)','2018-03-06','2018-03-31','Development','156',NULL,'2018-03-06 10:34:37');

UNLOCK TABLES;

/*Table structure for table `projects_budget_detail` */

DROP TABLE IF EXISTS `projects_budget_detail`;

CREATE TABLE `projects_budget_detail` (
  `detail_id` varchar(20) NOT NULL,
  `item_id` varchar(20) DEFAULT NULL,
  `detail_no` int(11) unsigned DEFAULT NULL,
  `detail_uraian` varchar(255) DEFAULT NULL,
  `detail_volume` int(11) unsigned DEFAULT NULL,
  `detail_satuan` varchar(50) DEFAULT NULL,
  `detail_harga` double unsigned DEFAULT NULL,
  `detail_sub_total` double unsigned DEFAULT NULL,
  `mdb` varchar(10) DEFAULT NULL,
  `mdb_name` varchar(50) DEFAULT NULL,
  `mdd` datetime DEFAULT NULL,
  PRIMARY KEY (`detail_id`),
  KEY `item_id` (`item_id`),
  CONSTRAINT `projects_budget_detail_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `projects_budget_item` (`item_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `projects_budget_detail` */

LOCK TABLES `projects_budget_detail` WRITE;

UNLOCK TABLES;

/*Table structure for table `projects_budget_item` */

DROP TABLE IF EXISTS `projects_budget_item`;

CREATE TABLE `projects_budget_item` (
  `item_id` varchar(20) NOT NULL,
  `plan_id` varchar(20) DEFAULT NULL,
  `group_id` varchar(5) DEFAULT NULL,
  `kode_akun` varchar(8) DEFAULT NULL,
  `perusahaan_id` varchar(5) DEFAULT NULL,
  `item_no` int(11) unsigned DEFAULT NULL,
  `item_uraian` varchar(255) DEFAULT NULL,
  `item_volume` int(11) unsigned DEFAULT NULL,
  `item_satuan` varchar(50) DEFAULT NULL,
  `item_harga` double unsigned DEFAULT NULL,
  `item_total` double unsigned DEFAULT NULL,
  `mdb` varchar(10) DEFAULT NULL,
  `mdb_name` varchar(50) DEFAULT NULL,
  `mdd` datetime DEFAULT NULL,
  PRIMARY KEY (`item_id`),
  KEY `plan_id` (`plan_id`),
  KEY `group_id` (`group_id`),
  CONSTRAINT `projects_budget_item_ibfk_1` FOREIGN KEY (`plan_id`) REFERENCES `projects_budget_plan` (`plan_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `projects_budget_item_ibfk_2` FOREIGN KEY (`group_id`) REFERENCES `project_budget_group` (`group_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `projects_budget_item` */

LOCK TABLES `projects_budget_item` WRITE;

UNLOCK TABLES;

/*Table structure for table `projects_budget_plan` */

DROP TABLE IF EXISTS `projects_budget_plan`;

CREATE TABLE `projects_budget_plan` (
  `plan_id` varchar(20) NOT NULL,
  `plan_references_id` varchar(20) DEFAULT NULL,
  `project_id` varchar(10) DEFAULT NULL,
  `nilai_pendapatan` double unsigned DEFAULT NULL,
  `nilai_pajak` double unsigned DEFAULT NULL,
  `nilai_anggaran` double unsigned DEFAULT NULL,
  `nilai_biaya` double unsigned DEFAULT NULL,
  `catatan` text,
  `send_status` enum('draft','process','done') DEFAULT 'draft',
  `send_by` varchar(10) DEFAULT NULL,
  `send_by_name` varchar(50) DEFAULT NULL,
  `send_date` datetime DEFAULT NULL,
  `plan_status` enum('approved','rejected','waiting') DEFAULT 'waiting',
  `kode_output` varchar(12) DEFAULT NULL,
  `create_by` varchar(10) DEFAULT NULL,
  `create_by_name` varchar(50) DEFAULT NULL,
  `create_date` datetime DEFAULT NULL,
  `mdb` varchar(10) DEFAULT NULL,
  `mdb_name` varchar(50) DEFAULT NULL,
  `mdd` datetime DEFAULT NULL,
  PRIMARY KEY (`plan_id`),
  KEY `project_id` (`project_id`),
  CONSTRAINT `projects_budget_plan_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `projects` (`project_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `projects_budget_plan` */

LOCK TABLES `projects_budget_plan` WRITE;

UNLOCK TABLES;

/*Table structure for table `projects_budget_process` */

DROP TABLE IF EXISTS `projects_budget_process`;

CREATE TABLE `projects_budget_process` (
  `process_id` varchar(20) NOT NULL,
  `plan_id` varchar(20) DEFAULT NULL,
  `flow_id` varchar(5) DEFAULT NULL,
  `flow_revisi_id` varchar(5) DEFAULT NULL,
  `process_references_id` varchar(20) DEFAULT NULL,
  `process_st` enum('waiting','approve','reject') DEFAULT 'waiting',
  `action_st` enum('process','done') DEFAULT 'process',
  `catatan` text,
  `mdb` varchar(10) DEFAULT NULL,
  `mdb_name` varchar(50) DEFAULT NULL,
  `mdd` datetime DEFAULT NULL,
  `mdb_finish` varchar(10) DEFAULT NULL,
  `mdb_finish_name` varchar(50) DEFAULT NULL,
  `mdd_finish` datetime DEFAULT NULL,
  PRIMARY KEY (`process_id`),
  KEY `plan_id` (`plan_id`),
  CONSTRAINT `projects_budget_process_ibfk_1` FOREIGN KEY (`plan_id`) REFERENCES `projects_budget_plan` (`plan_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `projects_budget_process` */

LOCK TABLES `projects_budget_process` WRITE;

UNLOCK TABLES;

/*Table structure for table `projects_client_alamat` */

DROP TABLE IF EXISTS `projects_client_alamat`;

CREATE TABLE `projects_client_alamat` (
  `alamat_id` varchar(20) NOT NULL,
  `client_id` varchar(5) DEFAULT NULL,
  `alamat_kepada` varchar(100) DEFAULT NULL,
  `alamat_kantor` text,
  `alamat_default` enum('1','0') DEFAULT '0',
  `mdb` varchar(10) DEFAULT NULL,
  `mdb_name` varchar(50) DEFAULT NULL,
  `mdd` datetime DEFAULT NULL,
  PRIMARY KEY (`alamat_id`),
  KEY `client_id` (`client_id`),
  CONSTRAINT `projects_client_alamat_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `projects_clients` (`client_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `projects_client_alamat` */

LOCK TABLES `projects_client_alamat` WRITE;

UNLOCK TABLES;

/*Table structure for table `projects_clients` */

DROP TABLE IF EXISTS `projects_clients`;

CREATE TABLE `projects_clients` (
  `client_id` varchar(5) NOT NULL,
  `client_nm` varchar(50) DEFAULT NULL,
  `client_desc` varchar(100) DEFAULT NULL,
  `client_address` varchar(100) DEFAULT NULL,
  `client_city` varchar(50) DEFAULT NULL,
  `mdb` varchar(10) DEFAULT NULL,
  `mdb_name` varchar(50) DEFAULT NULL,
  `mdd` datetime DEFAULT NULL,
  PRIMARY KEY (`client_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `projects_clients` */

LOCK TABLES `projects_clients` WRITE;

insert  into `projects_clients`(`client_id`,`client_nm`,`client_desc`,`client_address`,`client_city`,`mdb`,`mdb_name`,`mdd`) values ('00001','BANK MANDIRI','PT. Bank Mandiri Persero Tbk','Plaza Mandiri - Jl. Jenderal Gatot Subroto','Jakarta','1',NULL,'2013-10-05 10:45:00'),('00002','KOBU I - Jakarta','Kantor Otoritas Bandar Udara Wilayah I','Jl. Medan Merdeka Barat No. 8','Jakarta','20',NULL,'2013-10-07 14:28:30'),('00006','TE','PT. Time Excelindo','Jl. Ringroad Utara, Depok, Sleman, Yogyakarta','Sleman','1',NULL,'2013-10-04 14:50:11'),('00007','KNKT','Komite Nasional Keselamatan Transportasi','Jl. Medan Merdeka Timur No. 5 Jakarta','Jakarta','1',NULL,'2013-10-27 11:42:57'),('00008','PEMKAB MUBA','Pemkab Musi Banyuasin','Jl. Merdeka Lk. VI I Kel. Serasan Kaya','Musi Banyuasin','1',NULL,'2013-10-04 15:31:09'),('00011','KOBU IV - BALI','KOBU IV - BALI','Jl. Juanda No. 1 Tuban Denpasar - Bali','Bali','100',NULL,'2014-11-18 10:49:40'),('00012','KOBU VII - Balikpapan','Kantor Otoritas Bandar Udara Wilayah VII','Jl. Marsma R Iswahyudi No.1 Balikpapan','Balikpapan','20',NULL,'2013-10-07 14:33:56'),('00014','KOBU VIII - Manado','Kantor  Otoritas  Bandar  Udara  Wilayah  VIII','Kota Manado','Manado','1',NULL,'2014-03-26 11:55:55'),('00015','DPPKAD KEBUMEN','DPPKAD Kab Kebumen','Jl. Veteran no. 2 Kebumen 54311','Kebumen','1',NULL,'2014-03-26 11:53:20'),('00016','UNFPA','United Nation Population fund','Jl. M.H. Thamrin Kav. 3  Jakarta 10250','Jakarta','1',NULL,'2014-03-26 11:51:47'),('00018','DEPHUBUD','Departemen Perhubungan Udara','Jl Merdeka Barat','Jakarta','1',NULL,'2013-10-27 12:34:52'),('00019','PPE - KALIMANTAN','Pusat Pengelolaan Ekoregion','Jl. Jenderal Sudirman No. 19A Balikpapan - Kalimantan Timur 76111','Kalimantan','1',NULL,'2014-03-26 11:50:16'),('00020','AMIKOM','STMIK AMIKOM','Ring Road Utara,Condong Catur, Depok, Sleman','Yogyakarta','1',NULL,'2014-03-26 11:59:33'),('00021','SOFTDEV','Software Development Department','Ring Road Utara,Condong Catur, Depok, Sleman','Yogyakarta','16',NULL,'2014-01-07 16:05:40'),('00022','APIKRI','KSU APIKRI','Jl. Imogiri','Bantul','87',NULL,'2014-04-04 14:12:36'),('00023','NTT Indonesia','NTT Indonesia','-','JAKARTA','82',NULL,'2014-04-05 08:51:07'),('00024','AMPTA','AMPTA','Yogyakarta','YOGYAKARTA','82',NULL,'2014-04-05 10:27:41'),('00025','INDOMEDIA SALATIGA','INDOMEDIA SALATIGA','-','SALATIGA','82',NULL,'2014-04-05 08:58:00'),('00026','USM','Universitas Semarang','Jalan Arteri Soekarno Hatta Tlogosari Semarang Jawa Tengah','SEMARANG','62',NULL,'2014-04-17 16:27:45'),('00027','LKPP','LKPP','-','JAKARTA','65',NULL,'2014-04-05 09:15:46'),('00028','STPP','Sekolah Tinggi Penyuluhan Pertanian','Jl. Kusumanegara no.2 Yogyakarta','Yogyakarta','62',NULL,'2014-04-05 10:02:00'),('00029','ABA Sinema','Akademi Bahasa Asing','Jl. Bantul 77 Yogyakarta','Yogyakarta','62',NULL,'2014-04-05 10:04:37'),('00030','IST Akprind','Institus Sains & Teknologi Akprind','Jl. Kalisahak No. 28 Kompleks Balapan Tromol Pos 45 Yogyakarta 55222','Yogyakarta','62',NULL,'2014-04-05 10:06:11'),('00031','Arundina','Arundina','Purworejo','Purworejo','62',NULL,'2014-04-05 10:07:13'),('00032','XL','Excelindo','-','JAKARTA','82',NULL,'2014-04-05 10:07:39'),('00033','Bethesda','Rumah Sakit Bethesda','Jl. Jendral Sudirman 70 Yogyakarta 55224','Yogyakarta','62',NULL,'2014-04-05 10:10:00'),('00034','Bumitel','Bumitel','Jl. Kabupaten km 1,5 no 883','Sleman','62',NULL,'2014-04-05 10:16:54'),('00035','BPTP','Balai Pengkajian Teknologi Pertanian','Jl. Stadion Maguwoharjo No. 22 Wedomartani Ngemplak Sleman Yogyakarta','Sleman','62',NULL,'2014-04-05 10:18:46'),('00036','Serdadu','Serdadu Game Online','Jalan Kaliurang km 10.5 (Sinduharjo, Ngaglik), Sleman, DI Yogyakarta','Sleman','62',NULL,'2014-04-05 10:20:30'),('00037','Dhecyber Nusantara SMG','Dhecyber Flow Indonesia, PT','Simpang Lima Semarang Jawa Tengah','Semarang','62',NULL,'2014-04-05 10:22:35'),('00038','Hamzanwadi','STKIP Hamzanwadi','Jalan TGKH. M. Zainuddin Abdul Madjid No.70 PancorSelong 83612 Lombok Timur Nusa Tenggara Barat','Lombok','62',NULL,'2014-04-05 10:24:37'),('00039','Imation','Imation','Jl. Puri Permata Gg Manggis no 9','Sleman','62',NULL,'2014-04-05 10:27:46'),('00040','KOBU VI - PADANG','KOBU VI - PADANG','-','PADANG','82',NULL,'2014-04-05 10:42:24'),('00041','PT. MELON INDONESIA','PT. MELON INDONESIA','-','JAKARTA','100',NULL,'2014-04-07 15:24:39'),('00042','Linus','Linus Tour and Travel','Jalan Imogiri Barat Km 4/25, Bangunharjo, Sewon, Yogyakarta 55187 DI Yogyakarta','Yogyakarta','62',NULL,'2014-04-05 10:44:24'),('00043','MSV','Mataram Surya Visi','GRHA STMIK Amikom Yogyakarta','Yogyakarta','62',NULL,'2014-04-05 10:47:46'),('00044','STIE SBI','STIE SBI','Jl. Ring Road Utara Condong Catur, Depok, Sleman 55283','Yogyakarta','62',NULL,'2014-04-05 10:48:56'),('00045','Skill','PT Skill','Jl S Supriyadi 21-J Kalicari, Pedurungan Semarang 50198 Jawa Tengah','Semarang','62',NULL,'2014-04-05 10:54:09'),('00046','SMA N 1 Sleman','SMA Negeri 1 Sleman','Jl. Magelang KM 14 Caturharjo Sleman 55515','Sleman','62',NULL,'2014-04-05 10:56:10'),('00047','KEMENPU','KEMENPU KEHUMASAN','-','JAKARTA','82',NULL,'2014-04-05 10:56:32'),('00048','Unissula','Fakultas Kedokteran Universitas Islam Sultan Agung','Fakultas Kedokteran Universitas Islam Sultan Agung Semarang Jawa Tengah','Semarang','62',NULL,'2014-04-05 10:59:03'),('00050','Sabo','Balai Sabo Yogyakarta','Sopalan, Maguwohardjo, Depok, Sleman Yogyakarta 55282','Yogyakarta','62',NULL,'2014-04-05 11:01:47'),('00051','UWMY','Universitas Widya Mataram Yogyakarta','Jalan Kadipaten Kulon nDalem Mangkubumen KT III/237 Yogyakarta 55132','Yogyakarta','62',NULL,'2014-04-05 11:03:35'),('00052','Pogung','Kost Pogung','Pogung','Sleman','62',NULL,'2014-04-05 11:04:36'),('00053','Victoria','Victoria Hotel','Jl. Laksda Adisutjipto Km. 5 Sleman (Belakang Hotel Ambarrukmo)','Yogyakarta','62',NULL,'2014-04-05 11:06:35'),('00054','MGTV','MGTV Yogyakarta','Depok, Sleman, DI Yogyakarta','Yogyakarta','62',NULL,'2014-04-05 11:08:19'),('00055','POLDA','Kantor Administrasi POLDA DI Yogyakarta','Jl. Ring Road Utara Condong Catur, Depok, Sleman','Yogyakarta','62',NULL,'2014-04-05 11:09:31'),('00056','Indomedia','Indomedia','Salatiga','Salatiga','62',NULL,'2014-04-05 11:10:07'),('00057','TE-Marketing','TE-Marketing','Yogyakarta','Yogyakarta','82',NULL,'2014-04-07 09:42:17'),('00058','TE - Infrastruktur','TE - Infrastruktur','Yogyakarta','Yogyakarta','100',NULL,'2014-04-07 14:22:33'),('00059','TE - PROJECT ADMINISTRATION','TE - PROJECT ADMINISTRATION','YOGYAKARTA','YOGYAKARTA','61',NULL,'2014-04-07 14:51:28'),('00060','PT. ANGKASA PURA 2 - PADANG','PT. ANGKASA PURA 2 - PADANG','Ketaping Padang Pariaman 25171','padang','85',NULL,'2014-04-07 16:27:11'),('00063','FO Banyuasin','FO Banyuasin','Banyuasin, SumSel','Banyuasin, SumSel','100',NULL,'2014-04-08 08:47:15'),('00065','UPT Lab Komputer','UPT STMIK AMIKOM','Jl RingRoad Utara COncat  Sleman','Sleman','107',NULL,'2014-04-15 11:54:41'),('00066','Mataram Surya Visi (MSV)','PT MSV','Jl RingRoad Utara COncat  Sleman','Sleman','107',NULL,'2014-04-15 11:56:30'),('00067','PDAM Tangerang','PDAM Tangerang','Rt/Rw, 001/005 Kelurahan Gaga Kecamatan  Larangan Kabupaten/kota Tangerang','Tangerang','62',NULL,'2014-04-17 16:35:35'),('00069','PlazaNet','PlazaNet','Plosokuning, Sleman','Sleman','62',NULL,'2014-04-18 08:20:38'),('00070','MCS','Mineral and Coalt Studio','Plemburan, Sleman','Sleman','62',NULL,'2014-04-19 13:48:37'),('00071','Dishub Purworejo','Pokja   Pengadaan   Barang/Jasa   Dinas Perhubungan Komunikasi Dan Informatika','Jl. Gajah Mada Km 7 Purworejo','Kabupaten Purworejo','88',NULL,'2014-04-21 10:34:41'),('00072','Finance','Finance','Kampus AMIKOM Gd.3 Lt.1','Yogyakarta','67',NULL,'2018-01-03 14:54:10'),('00073','DINKES','Dinas Kesehatan Jawa Tengah','Semarang','Semarang','87',NULL,'2014-04-22 12:37:29'),('00074','Bag UMUM Setda','SETDA  Kebumen','Jl Veteran No.2  Kebumen','Kebumen','107',NULL,'2014-04-23 13:48:23'),('00075','Janabadra','Universitas Janabadra','Yogyakarta','Yogyakarta','87',NULL,'2014-04-24 13:31:49'),('00076','Vandeventermaas','VDMS','Jl Purwanggan no 9','Yogyakarta','87',NULL,'2014-04-24 13:34:00'),('00078','HighScope Indonesia','HighScope Indonesia Institute Licence','Jl. TB Simatupang No. 8 Cilandak Barat Jakarta 12430','Jakarta','85',NULL,'2014-04-29 10:02:02'),('00079','DINSOSNAKERTRAN PEMKAB GK','DINSOSNAKERTRAN PEMKAB GK','Jl.K.H.Agus Salim No.125, Kepek, Wonosari, Gunungkidul','GUNUNG KIDUL','100',NULL,'2014-04-29 13:01:32'),('00082','LPSE MAGELANG','LPSE MAGELANG','Jl. Letnan Tukiyat No.59  Kota Mungkid, Magelang','MAGELANG','100',NULL,'2014-05-02 11:25:28'),('00083','UNISRI','Universitas Slamet Riyadi Surakarta','Jl. Sumpah Pemuda no 18','Solo','65',NULL,'2014-05-12 10:31:14'),('00084','LPSE FEB UGM','LPSE FEB UGM','Bulaksumur Yogyakarta','Yogyakarta','100',NULL,'2014-05-06 16:04:04'),('00085','DISPENDUKCAPIL KAB KEBUMEN','DISPENDUKCAPIL KAB KEBUMEN','Jl. H.M Sarbini No 21 Kebumen 54311','Kebumen','100',NULL,'2014-05-24 10:53:24'),('00086','BPPKAD  TMGG','Badan PPKAD  Kab. Temanggung','Jl Jend.Soedirman No 2  Temanggung','Temanggung','107',NULL,'2017-02-01 09:35:10'),('00087','LPSE Salatiga','LPSE Salatiga','Gedung Komplek Sekretariat Daerah Jl. Letjen. Sukowati No.51 Salatiga 50724LPSE Salatiga','SALATIGA','100',NULL,'2014-05-10 10:40:29'),('00088','AMIKOM PWT','STMIK AMIKOM Purwokerto','Jalan Let.Jend. Pol. Sumarto (Depan SPN) Purwokerto','purwokerto','65',NULL,'2014-05-12 10:28:56'),('00089','UNTID','Universitas Tidar Magelang','Magelang','Magelang','65',NULL,'2014-05-12 10:29:32'),('00090','SMA 1 Sayegan','SMA 1 Sayegan','Yogyakarta','Yogyakarta','65',NULL,'2014-05-12 10:30:17'),('00091','BP2T','BP2T','Jl. Raya Serpong Km. 12 Serpong, Kota Tangerang Selatan 15232, Telp. (021) 53150119 / 53150120','Tangerang Selatan','84',NULL,'2014-05-13 08:18:03'),('00092','KEMENTERIAN LINGKUNGAN HIDUP DIY','KEMENLH DIY','Jl. Ring Road Barat no.100, Nusupan, Nogotirto, Gamping, Sleman, Yogyakarta','Yogyakarta','100',NULL,'2014-05-14 15:30:19'),('00093','Bank BTN Syariah','Bank BTN Syariah','Jl. Gajah Mada No.1','Jakarta','103',NULL,'2014-05-19 08:09:32'),('00094','SiBOS','SiBOS Micro Finance','Tegal','Tegal','103',NULL,'2014-05-19 13:37:29'),('00095','Direktorat Jenderal Kereta Api','DJKAI','Merdeka Barat','Jakarta','1',NULL,'2014-06-09 13:31:51'),('00096','PPE KALIMANTAN','Pusat Pengelolaan Ekoregion Kalimantan','Jl. Jendral Sudirman No. 19A Balikpapan - Kalimantan Timur 76111','BALIKPAPAN','1',NULL,'2014-06-09 13:31:18'),('00097','BNPB','Badan Nasional Penanggulangan Bencana','Jl. Juanda 36, Jakarta Pusat, DKI Jakarta 10120','Jakarta','16',NULL,'2014-05-19 16:07:30'),('00098','FAK MIPA UGM','FAK MIPA UGM','Bulaksumur','Yogyakarta','100',NULL,'2014-05-21 13:41:00'),('00099','AMIKOM YOGYAKARTA','AMIKOM YOGYAKARTA','Ringroad Utara Yogyakarta','Yogyakarta','100',NULL,'2014-05-23 09:16:34'),('00100','DISKOMINFO PURWOREJO','DINAS KOMINFO KAB PURWOREJO','PURWOREJO','PURWOREJO','107',NULL,'2017-10-12 17:30:43'),('00102','pt pura barutama kudus','pt pura barutama kudus','Jl. AKBP. Agil Kusumadya 203. Kudus 59346, Central Java','kudus','100',NULL,'2014-06-09 11:58:16'),('00103','Bank Muammalat Kas Amikom','Bank Muammalat KCP AMIKOM','Gd.V  Stmik Amikom   Ringroad Utara','Sleman','107',NULL,'2014-06-10 11:36:26'),('00104','AKRB Jogja','Akademi Komunikasi Radya Binatama Jogja','Jl Janti  Sleman','Jogja','107',NULL,'2014-06-10 09:43:21'),('00105','BPK - DIY','Kantor Badan Pemeriksa Keuangan Perwakilan Yogyakarta','Jalan HOS Cokroaminoto No. 52 Yogyakarta','Yogyakarta','107',NULL,'2015-05-19 13:12:12'),('00106','Politeknik Negeri Manado','Politeknik Negeri Manado','Jl.Manado','Manado','85',NULL,'2014-07-02 15:47:42'),('00107','Poltekes Sambas','Poltekes Sambas','Jl.Sambas','Sambas','85',NULL,'2014-06-24 14:55:16'),('00108','balai diklat esdm','kementerian esdm','gatsu, samping bulog pancoran','jakarta','103',NULL,'2014-06-26 19:12:43'),('00109','biro perencanaan laut','biro laut dephub lantai 17','merdeka barat','jakarta','103',NULL,'2014-06-26 19:24:37'),('00110','KOBU X - MERAUKE','KOBU X - MERAUKE','Jl. PGT Kompleks Bandar Udara Mopah Merauke','MERAUKE','16',NULL,'2015-02-27 14:06:54'),('00111','Inspektorat TMG','INSPEKTORAT  KAB. TEMANGGUNG','Jl. Jend A.Yani  No.32, Temanggung','Temanggung','107',NULL,'2014-07-01 11:36:05'),('00112','POLTEKPEL-SBY','POLITEKNIK PELAYARAN SURABAYA','Jl. Gunung Anyar Boulevard no 1,  Surabaya','SURABAYA','61',NULL,'2014-07-02 10:02:29'),('00113','RSUD TIDAR','RSUD TIDAR','Jl.Tidar No. 30A Magelang','Magelang','21',NULL,'2014-08-18 14:54:03'),('00114','TBNoto','TB Noto Saputro','Selokan mataram','Sleman','62',NULL,'2014-10-01 14:07:54'),('00115','Damai Pro','Damai Production','Condong Catur','Sleman','62',NULL,'2014-10-01 14:08:49'),('00116','AANG','Anggoro','Condong Catur','Sleman','62',NULL,'2014-10-01 14:13:44'),('00117','IP UII','International Program Universitas Islam Indonesia','Jl. Kaliurang KM.14','Sleman, Yogyakarta','87',NULL,'2014-10-13 13:58:29'),('00118','NTT','PT. NTT Indonesia','Jakarta','Jakarta','87',NULL,'2014-10-14 11:34:27'),('00119','RK','PT. Ruang Kerja','Perumahan Seturan','Seturan','87',NULL,'2014-10-18 13:18:07'),('00120','SMKN1Purworejo','SMK N 1 Purworejo','Purworejo','Purworejo','62',NULL,'2014-10-20 08:46:34'),('00121','PPPE Jawa  K-LHK','Pusat Pengendalian Pembangunan Ekoregion Jawa  K-LHK','Jl Rongroad Barat 100  Nogotirto  Sleman  DIY','Yogyakarta','107',NULL,'2016-11-02 12:45:29'),('00122','Telkom YK','Telkom Yogyakarta','kota baru','Yogyakarta','65',NULL,'2014-11-12 11:18:43'),('00123','KOBU IV - BALI','KOBU IV - BALI','JL. JUANDA NO. 1 TUBAN DENPASAR - BALI','BALI','100',NULL,'2014-11-18 10:49:49'),('00124','cybernet','Warnet Cyber Net','Mojohuro Rt5, Sriharjo, Imogiri','Bantul','87',NULL,'2014-11-21 09:25:10'),('00125','Nurbiz','Nurbiz (Mas Ivan)','Tegal rejo Rt 32/Rw 12Minomartani, Ngaglik','Sleman','87',NULL,'2014-11-21 09:26:56'),('00126','ICON','PT Indonesia Comnets Plus','GI Krapyak Jl. Siliwangi 379, Krapyak. Semarang Jawa Tengah','Semarang','65',NULL,'2015-01-14 09:38:48'),('00127','Kobu V - Makasar','Kantor Otoritas Bandar Udara Wilayah V','Jl. Otoritas Bandara No.5 Makassar','Makasar','103',NULL,'2015-02-10 00:05:12'),('00128','IP UII Hukum','IP UII Hukum','Jl tamansiswa Yogyakarta','Yogyakarta','62',NULL,'2015-02-10 08:48:58'),('00129','KOBU X - Merauke','Kantor Otoritas Bandar Udara Wilayah X','Jl. PGT Kompleks Bandar Udara Mopah Merauke','papua barat','103',NULL,'2015-02-11 13:41:56'),('00130','PT INKA (persero)','PT INKA (persero)','MADIUN','MADIUN','100',NULL,'2015-02-11 15:50:15'),('00131','DPPKAD  GunungKidul','Dinas Pendapatan Pengelolaan Keuangan Kab GngKidul','Jln. BrigJend Katamso  No.1    Wonosari  GK','Wonosari','107',NULL,'2015-02-13 11:03:11'),('00132','KOBU II MEDAN','KOBU II MEDAN','Bandar Udara Kuala Namu - Deli Serdang, Sumatera Utara','MEDAN','100',NULL,'2015-02-17 09:04:54'),('00134','KEMENKOP','KEMENTERIAN KOPERASI DAN USAHA KECIL DAN MENENGAH','Jl. H.R. Rasuna Said Kav. 3-4, Kuningan, Jakarta 12940','JAKARTA','16',NULL,'2015-03-24 15:53:53'),('00135','KEMENKO','Kementerian Koordinator Bidang Perekonomian','JL. Lapangan Banteng Timur No.2-4 - Jakarta Pusat (Kota)','Jakarta Pusat','100',NULL,'2015-03-27 11:50:01'),('00136','PEMKAB SLEMAN','PEMDA KAB Sleman','Jl  Parasamya   Tridadi  Sleman','SLeman  DIY','107',NULL,'2015-04-02 09:01:16'),('00137','POLTESA','POLTESA','Jl Raya Sejangkung Kawasan Pendidikan Tinggi Sambas Kalimantan Barat 79400','KALIMANTAN BARAT','100',NULL,'2015-04-17 12:24:33'),('00138','POLITALA','POLITALA','Jl. A. Yani Km.06 Desa Panggung Kec. Pelaihari, Kab. Tanah Laut Kalimantan Selatan 70815','Kalimantan Selatan','100',NULL,'2015-04-17 12:29:37'),('00139','Akademi Perkeretaapian Indonesia','DEPHUBUD MADIUN','Jl. Tirta Raya, Madiun, Jawa Timur','MADIUN','100',NULL,'2015-04-23 11:04:31'),('00140','DPR RI','DPR RI','JAKARTA PUSAT','JAKARTA PUSAT','100',NULL,'2015-05-09 08:54:28'),('00141','RSI Siti \'Aisyah','RSI Siti \'Aisyah','JL.MAYJEND SUNGKONO NO.30-38 MADIUN','MADIUN','100',NULL,'2015-05-18 11:06:17'),('00142','PT. SARIHUSADA GENERASI MAHARDHIKA','PT. SARIHUSADA GENERASI MAHARDHIKA','-','JAKARTA','16',NULL,'2015-05-28 15:28:01'),('00143','Pemkab Kebumen','Setda  Pemkab Kebumen','jl Veteran  no.2 Kebumen','Kebumen','107',NULL,'2015-06-09 08:07:45'),('00144','DISDUKCAPIL CAPIL CILACAP','DISDUKCAPIL CAPIL CILACAP','Jalan Kalimantan No. 72, Kecamatan Cilacap Tengah, Jawa Tengah','CILACAP','100',NULL,'2015-06-09 11:06:40'),('00145','BADAN STATISTIK NASIONAL','BADAN STATISTIK NASIONAL','gorontalo','gorontalo','100',NULL,'2015-07-01 09:58:32'),('00146','DPRD GK','DPRD','-','Gunungkidul','86',NULL,'2015-08-04 08:24:49'),('00148','PRIVAT CLIENT','PRIVAT CLIENT','MAUMERE','MAUMERE','128',NULL,'2015-08-18 10:35:49'),('00149','CV. Itusmedia','CV. Itusmedia','Jl. Wadaslintang Km 7 Desa Balingasal RT 01/02 Padureso Kebumen','Kebumen','16',NULL,'2015-08-18 14:09:11'),('00150','Dishub Kominfo Kabupaten Sleman','Dishub Kominfo Kabupaten Sleman','jl. Pringgodiningrat beran kidul sleman','Sleman','128',NULL,'2015-08-21 14:24:35'),('00151','MSV OutDoor','MSV OutDoor  Jogja','Jl. Nyi Pambayun Kotagede   Yogya','Kota Jogja','107',NULL,'2015-08-24 09:23:44'),('00152','Radin Inten II','Radin Inten II','Jl. H. Alamsyah Ratu Prawiranegara Km. 28, Lampung, 35214, Indonesia','Lampung','16',NULL,'2015-09-01 09:09:23'),('00153','RSUD Embung Fatimah Batam','RSUD Embung Fatimah Batam','Jalan R. Soeprapto. Blok D 1 9. Batu Aji.','Batam','16',NULL,'2015-09-02 10:21:41'),('00154','MITSUI Japan, PT','PT MITSUI  [Konsorsium PLTU Tanjung Jati B]','Ds.Tubanan  Kec.Kembang  Jepara','Jepara','107',NULL,'2015-09-23 11:12:42'),('00155','Hotel Lafayette','Hotel Lafayette','Jl. Ring Road Utara','Yogyakarta','128',NULL,'2015-10-12 13:02:59'),('00156','UPT BANDAR UDARA RADEN INTEN II LAMPUNG','UPT BANDAR UDARA RADEN INTEN II LAMPUNG','Jl Raya Branti. Negara Ratu, Negara Ratu. Bandarlampung 35362, Lampung','LAMPUNG','100',NULL,'2015-10-17 11:22:16'),('00157','Airnav Bali','Airnav Bali','Bali','Bali','156',NULL,'2016-05-31 10:57:31'),('00158','Universitas Mulawarman','Universitas Mulawarman','Jl. Kuaro, Kalimantan Timur 75119','Kalimantan Timur','128',NULL,'2015-10-21 08:33:59'),('00159','BPPTKG Jogja','BPPTKG','Jl. Cendana No.15, Daerah Istimewa Yogyakarta 55166','Jogja','86',NULL,'2015-10-21 14:54:44'),('00160','PT. MJC','PT. Macanan Jaya Cemerlang','Klaten','Klaten','128',NULL,'2015-11-03 09:43:45'),('00161','Kantor Kec.Ngemplak','Kantor Kecamatan Ngemplak','Jn Alternatif  Prambanan-tempel Km.12  Widodomartani ngemplak','Sleman','107',NULL,'2015-11-11 12:31:12'),('00162','BTP','Balai Teknik Penerbangan','Jakarta','Jakarta','62',NULL,'2015-11-12 10:26:29'),('00163','DPPKKI BLORA','Dinas Perhubungan Pariwisata Kebudayaan Komunikasi & Informatika','BLORA','BLORA','62',NULL,'2017-01-03 08:38:47'),('00164','P4TK Matematika','PPPPTK Matematika','Jalan Kaliurang KM.6, Sambisari, Condong Catur, Depok, Sleman Sub-District, Special Region of Yogyak','Sleman','16',NULL,'2015-11-28 11:19:43'),('00165','BKP','Balai Kesehatan Penerbangan','Blok B-11, Jl. Angkasa, Kemayoran, Kota Jakarta Pusat, Daerah Khusus Ibukota Jakarta 10720, Indonesi','Jakarta','16',NULL,'2015-11-28 11:19:28'),('00166','SMAN1TEMON','SMA Negeri 1 Temon','Temon, Kulonprogo','Kulonprogo','62',NULL,'2015-12-05 08:55:01'),('00167','FMIPA UGM','FMIPA UGM','Yogyakarta','Yogyakarta','128',NULL,'2015-12-15 11:44:50'),('00168','DISHUBKOMINFO TANGSEL','DISHUBKOMINFO TANGSEL','Tangerang Selatan','Tangerang Selatan','100',NULL,'2015-12-16 09:32:32'),('00169','IAIN Palu','IAIN Palu','Jl. Diponegoro No. 23 Palu 94221','Palu','128',NULL,'2015-12-31 10:31:41'),('00170','POLDA JATIM','POLDA JATIM','Jl. Akhmad Yani 116','SURABAYA','16',NULL,'2017-02-02 09:37:32'),('00171','PELINDO','PT.PELABUHAN INDONESIA IV (PERSERO)','MAKASSAR','MAKASSAR','117',NULL,'2016-01-05 09:50:09'),('00172','TE-GENERAL AFFAIRS','TE-GA','YOGYAKARTA','YOGYAKARTA','91',NULL,'2016-01-06 09:01:22'),('00173','Universitas Muhammadiyah Tasikmalaya','UMTAS','Jl.Tamansari  Gobras, Kota TasikMalaya   Jabar','Kota Tasikmalaya','107',NULL,'2016-01-15 10:36:33'),('00174','BDC-2016','OPERASIONAL TAHUNAN','BSD-JOGJA','BSD-JOGJA','103',NULL,'2016-01-22 13:45:39'),('00175','STPI CURUG','STPI CURUG','Jl. Raya PLP Curug, Kec. Tangerang, Banten, Indonesia','Banten','128',NULL,'2016-02-09 10:13:02'),('00176','Indonesia Power Adipala','Indonesia Power Adipala','Cilacap','Cilacap','128',NULL,'2016-03-12 07:52:07'),('00179','PO. EFISIENSI','PO. EFISIENSI','Jl.raya Wonosari Km. 6 - 081903269227','Kebumen','16',NULL,'2016-03-30 09:05:11'),('00180','Lam Cocoa','PT. Lam Cocoa','Yogyakarta','Yogyakarta','16',NULL,'2016-04-07 09:34:36'),('00181','KAI DAOP4','KAI DAOP 4','Jl MH.Thamrin No 2  Semarang','Semarang','107',NULL,'2016-05-17 16:13:32'),('00182','KAI DAOP 4 SEMARANG','KAI DAOP 4 SEMARANG','SEMARANG','SEMARANG','156',NULL,'2016-06-21 12:29:09'),('00183','RSUD WONOSARI','RSUD WONOSARI','Wonosari, Yogyakarta','Yogyakarta','156',NULL,'2016-07-22 13:32:44'),('00184','DISDIKPORA  PROV.JAWA TENGAH','DINAS PENDIDIKAN PO-RA  WIL.PROV. JAWA TENGAH','Jl.Pemuda No.134  Semarang','SEMARANG','107',NULL,'2016-08-08 15:52:39'),('00185','BLH Jambi','BLH Jambi','Jambi','Jambi','156',NULL,'2016-08-09 09:25:11'),('00186','Kantor Pos Regional Jawa Timur','Kantor Pos Regional Jawa Timur','Surabaya','Surabaya','128',NULL,'2016-08-12 14:23:33'),('00187','Kantor Muhammadiyah Yogyakarta','Kantor Muhammadiyah Yogyakarta','Jogja','Jogja','156',NULL,'2016-08-29 09:42:13'),('00188','macanan','macananjaya cemerlang','klaten','klaten','128',NULL,'2016-08-29 15:28:15'),('00189','Airnav Jakarta','Airnav Jakarta','Jakarta','Jakarta','156',NULL,'2016-09-09 16:00:37'),('00190','RSUD  Kebumen','SKPD RSUD Dr Soedirman  Kebumen','Jl Lingkar Selatan Muktisari  Kebumen','Kebumen  Jawa Tengah','107',NULL,'2016-09-30 10:10:09'),('00191','Garuda Management Facility','GMF','Bandara Soekarno Hatta Hangar 2','Tangerang','138',NULL,'2016-10-11 10:56:24'),('00192','Sekolah Tinggi Teknologi Kedirgantaraan [STTKD]','STTKD  Yogyakarta','Jl Parang tritis  Sewon Yogyakarta','Yogyakarta','107',NULL,'2016-12-29 17:11:05'),('00193','KOPERTIS Wil.V','Kopertis Wil V k Kemenristek Dikti','Jl Tentara Pelajar No.13, Bumijo Yogyakarta','Yogyakarta','107',NULL,'2016-10-15 09:46:35'),('00194','DISKOMINFO Klaten Kab.','Dinas Komunikasi dan Informatika  Kab.Klaten','JL.     KOTA  KLATEN','Klaten','107',NULL,'2017-02-28 09:15:25'),('00195','POLINDRA','POLINDRA','Jl. Lohbener Lama No.08, Lohbener, Indramayu, Kabupaten Indramayu, Jawa Barat 45252','Indramayu','16',NULL,'2016-10-27 09:36:07'),('00196','SEKDPRDBLORA','Sekretariat DPRD Kabupaten Blora','Blora - Jawa Tengah','Blora','62',NULL,'2016-11-01 11:01:08'),('00197','PT.POS Semarang','PT.POS Semarang','Semarang','Semarang','128',NULL,'2016-11-01 13:58:59'),('00198','TE INFRA','INFRA','Jogja','Jogja','156',NULL,'2016-11-18 11:49:19'),('00199','DInas KOM INFO  Kab Magelang','DISKOMINFO  KAB. MAGELANG','Jl. Letnan Tukiyat  Mungkid, Magelang','MAGELANG','107',NULL,'2016-12-07 11:39:59'),('00200','PT. POS PADANG','PT.POS Padang','Padang','Padang','128',NULL,'2016-12-13 13:44:08'),('00201','NIPPON INDOSARI, PT','SARI ROTI','Kompleks Industri Wijaya Kusuma, Jl Wijaya III, TUGU, Semarang','SEMARANG','107',NULL,'2016-12-17 10:59:23'),('00202','BBKalibrasi','Balai Besar Kalibrasi Fasilitas Penerbangan','Jl. Raya PLP Curug Legok, Tangerang - 15820','Curug','62',NULL,'2018-01-02 08:54:11'),('00203','KOMINFO BLORA','KOMINFO KABUPATEN BLORA','Blora - Jateng','Blora','62',NULL,'2016-12-30 09:42:59'),('00204','TE-Comp Shop','TECS/ TTS','Jln Nusa Indah No.2J  CondongCatur Depok Sleman, Jln RingRoad Utara No.16 Caturtunggal Depok Sleman','Sleman Yogyakarta','107',NULL,'2016-12-31 09:11:16'),('00205','BDC-2017','TE-BDC','Jl. Ring Road Utara','Yogyakarta','138',NULL,'2017-01-10 10:31:29'),('00206','Bagian Umum  SETDA  Kab Kebumen','Bag Umum Setda  Kbmn','Jln Veteran No 2  Kebumen','Kebumen  Jawa Tengah','107',NULL,'2017-03-16 17:22:55'),('00207','ANRI','ANRI','Jl. Ampera Raya No.7, RT.3/RW.4, Cilandak Tim., Ps. Minggu, Kota Jakarta Selatan, DKI Jakarta','JAKARTA','16',NULL,'2017-04-27 13:43:21'),('00208','BUMR Pangan Terhubung','IPangan','Sukabumi','Sukabumi','103',NULL,'2017-05-02 08:42:59'),('00209','direktorat angkatan udara','direktorat angkatan udara','jakarta','jakarta','128',NULL,'2017-09-11 13:35:43'),('00210','DJ-PDN','Dir Sarana Distribus & Logistik, DitJen Perdagangan Dalam Negeri','Jl. M. I. Ridwan Rais, No. 5, Jakarta Pusat 10110','Jakarta','107',NULL,'2017-09-23 13:20:05'),('00211','DISKOMINFO MAGELANG Kab','DINAS KOMINFO  KAB MAGELANG','Kompleks Setda Jl Letnan Tukiyat, Mungkid','Mungkid Magelang','107',NULL,'2017-10-17 05:31:34'),('00212','Dinkominfo palembang','Dinkominfo palembang','Palembang','Palembang','83',NULL,'2017-11-09 08:52:27'),('00213','Amikom','Universitas Amikom Yogyakarta','Jl. Padjajaran Caturtunggal, Depok, Sleman, DI Yogyakarta','Sleman','62',NULL,'2018-01-02 08:43:35'),('00214','USM','Universitas Semarang','Jawa Tengah','Semarang','62',NULL,'2018-01-02 08:44:05'),('00215','Akprind','IST Akprind Yogyakarta','Jl Kalisahak Yogyakarta','Yogyakarta','62',NULL,'2018-01-02 08:45:20'),('00216','Soetta','Otband Soetta','Tangerang','Tangerang','62',NULL,'2018-01-02 08:47:47'),('00217','UIIHukum','UII Fakultas Hukum','Jl. Tamansiswa Yogyakarta','Yogyakarta','62',NULL,'2018-01-02 08:49:53'),('00218','UIIBahasa','UII Fakultas Bahasa Inggris','Jl, Kaliurang km 14 Sleman','Sleman','62',NULL,'2018-01-02 08:50:34'),('00219','MCS','Mineral and Coal Studio','Plemburan Sleman','Sleman','62',NULL,'2018-01-02 08:52:28'),('00220','Pak Yanto Amikom','Pak Yanto Amikom','Sleman','Sleman','62',NULL,'2018-01-02 10:15:20'),('00221','BCurug','Bandara Curug Budiharto','Tangerang','Tangerang','62',NULL,'2018-01-02 10:15:51'),('00222','Dirnav23','Direktorat Navigasi Penerbangan Lt23','Jakarta','Jakarta','62',NULL,'2018-01-02 10:16:27'),('00223','SMK Batik Purworejo','SMK Batik Purworejo','Purworejo','Purworejo','62',NULL,'2018-01-02 10:16:57'),('00224','Hukum20','Dephub Lt20 Dirjen Hukum','Jakarta','Jakarta','62',NULL,'2018-01-02 10:17:31'),('00225','Ditban24','Dephub Lt24 Ditbandara','Jakarta','Jakarta','62',NULL,'2018-01-02 10:17:59'),('00226','Ruang Kerja Oketiket','Ruang Kerja Oketiket','Sleman','Sleman','62',NULL,'2018-01-02 10:18:24'),('00227','Kusnawi Amikom','Pak Kusnawi Amikom','Sleman','Sleman','62',NULL,'2018-01-02 10:18:57'),('00228','SekDirJen Lt20','SekDirJen Lt20','Jakarta','Jakarta','62',NULL,'2018-01-02 10:19:14'),('00229','SMP N 2 Ngaglik','SMP N 2 Ngaglik','Sleman','Sleman','62',NULL,'2018-01-02 10:19:35'),('00230','Yanti Amikom','Bu Yanti Amikom','Sleman','Sleman','62',NULL,'2018-01-02 10:20:02'),('00231','Soho Syamsudin','Soho Syamsudin','Sleman','Sleman','62',NULL,'2018-01-02 10:20:22'),('00232','SOHO Indoscreen','SOHO Indoscreen','Bantul','Bantul','62',NULL,'2018-01-02 10:20:44'),('00233','SMK N 1 Nanggulan','SMK N 1 Nanggulan','Kulon Progo','Kulon Progo','62',NULL,'2018-01-02 10:21:12'),('00234','UMP','Universitas Muhammadiyah Purworejo','Purworejo','Purworejo','62',NULL,'2018-01-02 10:21:39'),('00235','SMK Diponegoro Sleman','SMK Diponegoro Sleman','Sleman','Sleman','62',NULL,'2018-01-02 10:22:06'),('00236','Pak Fauzi Amikom','Pak Fauzi Amikom','Yogyakarta','Yogyakarta','62',NULL,'2018-01-02 10:23:37'),('00237','Semesta Indovest','Semesta Indovest','Tangerang','BSD','62',NULL,'2018-01-02 10:23:53'),('00238','Dephub Evaluasi dan Pengembangan Lt21','Dephub Evaluasi dan Pengembangan Lt21','Jakarta','Jakarta','62',NULL,'2018-01-02 10:24:43'),('00239','Dephub Perencanaan dan Mutasi Lt21','Dephub Perencanaan dan Mutasi Lt21','Jakarta','Jakarta','62',NULL,'2018-01-02 10:25:10'),('00240','Angud Lt21','DAU ANGUD LT21','Jakarta','Jakarta','62',NULL,'2018-01-02 10:25:27'),('00241','Biro Kerjasama Dephub Lt 3','Biro Kerjasama Dephub Lt 3','Jakarta','Jakarta','62',NULL,'2018-01-02 10:27:06'),('00242','SMK N 1 Temon','SMK N 1 Temon','Kulon Progo','Kulon Progo','62',NULL,'2018-01-02 10:27:27'),('00243','MTS N 7 Bantul Yogyakarta','MTS N 7 Bantul Yogyakarta','Piyungan Bantul','Bantul','62',NULL,'2018-01-02 10:27:56'),('00244','SMAN 6 PURWOREJO','SMAN 6 PURWOREJO','Purworejo','Purworejo','62',NULL,'2018-01-02 10:28:14'),('00245','Dinas Perijinan Purworejo','Dinas Perijinan Purworejo','Purworejo','Purworejo','62',NULL,'2018-01-02 10:28:33'),('00246','Soho Pak Wawan','Soho Pak Wawan','Jogja','Jogja','62',NULL,'2018-01-02 10:29:11'),('00247','Soho Pak Muhtadi','Soho Pak Muhtadi','Sleman','Sleman','62',NULL,'2018-01-02 10:29:34'),('00248','Balai Desa Wedomartani','Balai Desa Wedomartani','Sleman','Sleman','62',NULL,'2018-01-02 10:30:13'),('00249','Soho Rina','Soho Rina','Sleman','Sleman','62',NULL,'2018-01-02 10:30:28'),('00250','SOHO HEri','Soho Heri','Sleman','Sleman','62',NULL,'2018-01-02 10:30:52'),('00251','SMK Muhammadiyah Mungkid','SMK Muhammadiyah Mungkid','Magelang','Magelang','62',NULL,'2018-01-02 10:31:15'),('00252','SMA N 1 Girimulyo','SMA N 1 Girimulyo','Kulon Progo','Kulon Progo','62',NULL,'2018-01-02 10:31:31'),('00253','SD Salman Al Faridzi','SD Salman Al Faridzi','Sleman','Sleman','62',NULL,'2018-01-02 10:31:50'),('00254','SOHO Kost Shaidah','SOHO Kost Shaidah','COncat','Concat','62',NULL,'2018-01-02 10:32:10'),('00255','SMP N 3 Depok','SMP N 3 Depok','Depok','Depok','62',NULL,'2018-01-02 10:32:35'),('00256','SMA N 7 Purworejo','SMA N 7 Purworejo','Purworejo','Purworejo','62',NULL,'2018-01-02 10:32:56'),('00257','Jogjatronik Thoriq','Jogjatronik Thoriq','Jogjatronik Mall','Jogja','62',NULL,'2018-01-02 10:33:21'),('00258','Transtra','Transtra Permada','Jl. Monjali','Sleman','62',NULL,'2018-01-02 11:42:34'),('00259','Surgika','PT Surgika Alkesindo','Sleman','Sleman','62',NULL,'2018-01-02 11:42:56'),('00260','Jogja Scrummy','Jogja Scrummy','Sleman','Sleman','62',NULL,'2018-01-02 11:43:17'),('00261','Diskominfo Tangsel','Dinas Komunikasi dan Informatika Pemerintah Kota Tangerang Selatan','Tangsel','Tangsel','62',NULL,'2018-01-02 11:44:15'),('00262','SMP Salman Al Farizi','SMP Salman Al Farizi','Sleman','Sleman','62',NULL,'2018-01-02 11:44:50'),('00263','PRATAMA','Pratama Mayang Apriyadi','Komplek Ruko Tugu Mulia, Jl Anggajaya, Depok, Sleman','Sleman','62',NULL,'2018-01-04 10:23:44'),('00264','Soho Ridwan','SOHO RIDWAN','Sleman','Sleman','62',NULL,'2018-01-04 10:30:18'),('00265','Pusbang Curug','Pusat Pengembangan SDM Curug','Tangsel','Tangsel','62',NULL,'2018-01-05 09:46:34'),('00266','OtbandCargo','Otband Soetta Cargo','Tangerang','Tangerang','62',NULL,'2018-01-08 11:40:51'),('00267','Dephub Lt. 23','Navigasi','Jakarta','Jakarta','194',NULL,'2018-01-10 14:29:58'),('00268','marketing','marketing','jakarta','Jakarta','194',NULL,'2018-01-10 15:41:19'),('00269','DITKAPEL','Direktorat Perkapalan dan Kepelautan','Gd. Karya Lantai 12,  Jl. Medan Merdeka Barat No. 8, Jakarta Pusat-10110','Jakarta','16',NULL,'2018-01-12 14:29:48'),('00270','SOHO Agustinus','SOHO AGUSTINUS','Magelang','Magelang','62',NULL,'2018-01-15 08:53:32'),('00271','Kontraktor Wonsa','Kontraktor Wonsa','Wonosari, Gunung Kidul','Wonosari','62',NULL,'2018-01-15 08:56:45'),('00272','Surgika Semarang','PT SURGIKA SEMARANG','Jateng','Semarang','62',NULL,'2018-01-22 08:53:23'),('00273','Kelurahan Purworejo','Kecamatan Banyu  Urip Kelurahan Purworejo','Purworejo','Purworejo','62',NULL,'2018-01-22 09:17:30'),('00274','SOHO Botika','SOHO BOTIKA','perumnas seturan blok E.III, No.50, seturan yogyakarta','Sleman','62',NULL,'2018-02-12 10:43:51'),('00275','DISKOMINFO KLATEN Kab','Dinas KOMINFO  KAB  KLATEN','Jl. Pemuda  Klate [Kota]','Klaten','107',NULL,'2018-02-20 14:20:50'),('00276','CV. Tuga Sekawan','CV. Tuga Sekawan','Paingan, Trasan, Magelang, Jawa Tengah','Magelang','150',NULL,'2018-02-23 11:41:07'),('00277','RSUD Sleman','Cicik','Jalan Bhayangkara No. 48 Triharjo','Sleman','194',NULL,'2018-03-01 15:41:26'),('00278','RSUD Sleman','RSUD Sleman','Jl. Bayangkara No.48, Triharjo, Kec. Sleman, Kabupaten Sleman, Daerah Istimewa Yogyakarta 55514','Sleman','128',NULL,'2018-03-05 09:02:30');

UNLOCK TABLES;

/*Table structure for table `projects_clients_pic` */

DROP TABLE IF EXISTS `projects_clients_pic`;

CREATE TABLE `projects_clients_pic` (
  `pic_id` varchar(20) NOT NULL,
  `client_id` varchar(5) DEFAULT NULL,
  `pic_name` varchar(50) DEFAULT NULL,
  `pic_position` varchar(50) DEFAULT NULL,
  `pic_phone_number` varchar(50) DEFAULT NULL,
  `pic_email` varchar(50) DEFAULT NULL,
  `mdb` varchar(10) DEFAULT NULL,
  `mdb_name` varchar(50) DEFAULT NULL,
  `mdd` datetime DEFAULT NULL,
  PRIMARY KEY (`pic_id`),
  KEY `client_id` (`client_id`),
  CONSTRAINT `projects_clients_pic_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `projects_clients` (`client_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `projects_clients_pic` */

LOCK TABLES `projects_clients_pic` WRITE;

insert  into `projects_clients_pic`(`pic_id`,`client_id`,`pic_name`,`pic_position`,`pic_phone_number`,`pic_email`,`mdb`,`mdb_name`,`mdd`) values ('1','14','-','','','','16',NULL,'2013-10-08 09:24:19'),('10','19','-','','','','16',NULL,'2013-11-30 12:36:27'),('100','47','Masmian Mahida','Bidang Sumber Daya','081298259305','masmian.mahida@pu.go.id','16',NULL,'2014-06-14 11:05:09'),('101','1','Nina Kirana','MCM','08111244302','','16',NULL,'2014-06-23 11:57:13'),('102','1','Yoshi','ASABRI','08562225613','','16',NULL,'2014-06-23 11:57:34'),('103','106','Agung','','','','85',NULL,'2014-06-24 10:01:37'),('104','107','Agung technograpia','','','','85',NULL,'2014-06-24 14:55:16'),('105','108','bayu nugroho','kabag perencanaan','','','103',NULL,'2014-06-26 19:12:43'),('106','109','yerro','it','','','103',NULL,'2014-06-26 19:24:37'),('107','110','OTBAN WIL X MERAUKE','','','','100',NULL,'2014-06-28 11:57:37'),('108','111','Bp Pamuji','','0293-491124','','107',NULL,'2014-07-01 11:36:05'),('109','112','-','','031-8714673','info@bp2ip-surabaya.ac.id','61',NULL,'2014-07-02 10:02:29'),('11','20','Bu Rahma','-','0815 7810 0111','','16',NULL,'2014-01-06 08:52:58'),('110','113','ARIS','','','','21',NULL,'2014-08-18 14:54:03'),('111','2','Fadil','Staff TU','081213625251','ayusahfadil@yahoo.co.id','16',NULL,'2014-08-21 14:39:13'),('112','114','TB','','','','62',NULL,'2014-10-01 14:07:54'),('113','115','Damai','','','','62',NULL,'2014-10-01 14:08:49'),('114','116','Aang','','','','62',NULL,'2014-10-01 14:13:44'),('115','117','Husni','','','','87',NULL,'2014-10-13 13:58:29'),('116','118','Diah Nikmahayati','','+62 87738764768','n.diah@ntt.co.id','87',NULL,'2014-10-14 11:34:27'),('117','119','Rianto','','','','87',NULL,'2014-10-18 13:18:07'),('118','120','Kepsek','','','','62',NULL,'2014-10-20 08:46:34'),('119','121','Bpk  SUgeng (Ndableg)','Kasubid II','','','107',NULL,'2014-10-27 16:11:39'),('12','21','Rini','Project Officer','0274-4477820','softdev@excelindo.co.id','16',NULL,'2014-01-07 16:05:40'),('120','122','Kamilatun Nikmah','','','','65',NULL,'2014-11-12 11:18:43'),('121','123','OTBAN BALI','','( 0361) 9351710','','100',NULL,'2014-11-18 10:39:26'),('122','124','Turseno','Owner','081931766918','sensen_yellow@yahoo.com','87',NULL,'2014-11-21 09:25:10'),('123','125','Ivan','pengurus','087782725777','fedroza@gmail.com','87',NULL,'2014-11-21 09:26:56'),('124','126','Mba Ira','','','','65',NULL,'2015-01-14 09:38:48'),('125','127','Pak Nur','PPK','','','103',NULL,'2015-02-10 00:04:16'),('126','128','Husni','','','','62',NULL,'2015-02-10 08:48:58'),('127','129','Pak Joko','PPK','081322061024','','103',NULL,'2015-02-11 13:41:56'),('128','130','PT INKA (persero)','','','','100',NULL,'2015-02-11 15:50:15'),('129','131','Bpk BEJO','Pejabat Pengadaan','0274-391083','','107',NULL,'2015-02-13 11:03:11'),('13','22','Syaifudin','','08122717567','','62',NULL,'2014-04-05 08:42:58'),('130','132','KOBU II MEDAN','','','','100',NULL,'2015-02-17 09:04:54'),('132','134','-','','','','16',NULL,'2015-03-24 15:53:02'),('133','135','KEMENKO','','','','100',NULL,'2015-03-27 11:50:01'),('134','136','Kominfo; BKD; Pdam; Dpkad','Komplit','','','107',NULL,'2015-04-02 09:01:16'),('135','137','POLTESA','','(0562) 392592','akademik@poltesa.ac.id','100',NULL,'2015-04-17 12:24:33'),('136','138','POLITALA','','(0512) 21537','mail@politala.ac.id','100',NULL,'2015-04-17 12:29:37'),('137','139','(0351) 474777','','(0351) 474777','','100',NULL,'2015-04-21 10:29:11'),('138','140','DPR RI','','','','100',NULL,'2015-05-09 08:54:28'),('139','141','RSI Siti \'Aisyah','','(0351) 464822','','100',NULL,'2015-05-18 11:06:17'),('14','23','Norico','-','-','','82',NULL,'2014-04-05 08:51:07'),('140','142','-','-','','','16',NULL,'2015-05-28 15:28:01'),('141','47','Taufik','Puslitbang','085718071543','','16',NULL,'2015-06-04 15:45:00'),('142','47','Trisno','','0813 1446 8345','','16',NULL,'2015-06-04 15:45:15'),('143','47','Tri','','08563602280','','16',NULL,'2015-06-04 15:45:28'),('144','143','Muchriyanto','Kepala  Ratih TV Kebumen','','','107',NULL,'2015-06-09 08:07:45'),('145','144','DISDUKCAPIL CAPIL','','','','100',NULL,'2015-06-09 11:04:13'),('146','145','BADAN STATISTIK NASIONAL','','','','100',NULL,'2015-07-01 09:58:32'),('147','146','-','','','','86',NULL,'2015-08-04 08:24:21'),('149','148','Agung Yulianto Nugroho','Technoforia','','','128',NULL,'2015-08-18 10:35:19'),('15','24','Mas Aris','','','','82',NULL,'2014-04-05 08:55:41'),('150','149','Tusmiyati, S.Kom','Direktur Utama','087837590075','ingat.tust@gmail.com','16',NULL,'2015-08-18 14:09:11'),('151','150','Aris bintoro','PPK','','','128',NULL,'2015-08-21 14:24:35'),('152','151','Sinar Sabno (Gonang)','Direktur','','','107',NULL,'2015-08-24 09:23:44'),('153','152','-','-','+62 721 7697114','','16',NULL,'2015-09-01 09:09:23'),('154','153','-','','','','16',NULL,'2015-09-02 10:21:41'),('155','154','Mr.Dono','','0291-427049','','107',NULL,'2015-09-23 11:12:42'),('156','155','Hotel Lafayette','','','','128',NULL,'2015-10-12 13:02:59'),('157','156','UPT BANDAR UDARA RADEN INTEN II LAMPUNG','','(0721) 7697114','','100',NULL,'2015-10-17 11:22:16'),('158','157','Airnav Bali','','','','128',NULL,'2015-10-20 10:40:46'),('159','158','Unmul','','','','128',NULL,'2015-10-21 08:33:59'),('16','25','Eko','','','','82',NULL,'2014-04-05 08:58:00'),('160','159','Widi Prasita','staff IT','(0274) 514192','widi.prasita@esdm.go.id','86',NULL,'2015-10-21 14:54:44'),('161','160','PT. Macanan Jaya Cemerlang','','','','128',NULL,'2015-11-03 09:43:45'),('162','161','Ibu Herni','','','','107',NULL,'2015-11-11 12:31:12'),('163','162','BTP','','','','62',NULL,'2015-11-12 10:26:29'),('164','163','DPPKKI','','','','128',NULL,'2015-11-18 09:19:48'),('165','164','-','-','+62 274 881717','','16',NULL,'2015-11-28 11:12:48'),('166','165','-','','','','16',NULL,'2015-11-28 11:19:28'),('167','166','SMA 1 Temon','','','','62',NULL,'2015-12-05 08:55:01'),('168','167','FMIPA UGM','','','','128',NULL,'2015-12-15 11:44:50'),('169','168','DISHUBKOMINFO TANGSEL','','','','100',NULL,'2015-12-16 09:32:32'),('17','26','EKO','','','','82',NULL,'2014-04-05 09:00:07'),('170','169','IAIN Palu','','','','128',NULL,'2015-12-31 10:31:41'),('171','170','Bapak Ridwan','Kepala SDM POLDA','08124956777','diapers_jtm@yahoo.co.id','103',NULL,'2016-01-03 22:19:27'),('172','171','PELINDO','','','','117',NULL,'2016-01-05 09:50:09'),('173','172','YANS SAFARID HUDHA','KOORDINATOR','083867007706','safarid@te.net.id','91',NULL,'2016-01-06 09:01:22'),('174','173','Ir Muh Taufiq M.Eng','Ka Puskom','','','107',NULL,'2016-01-15 10:36:33'),('175','174','AGUNG','','','','103',NULL,'2016-01-22 13:43:45'),('176','175','STPI Curug','','','','128',NULL,'2016-02-09 10:13:02'),('177','176','Indonesia Power Adipala','','','','128',NULL,'2016-03-12 07:52:07'),('18','27','EKO','','','','65',NULL,'2014-04-05 09:15:46'),('180','179','-','-','-','123@test.com','16',NULL,'2016-03-30 09:05:11'),('181','180','-','','','','16',NULL,'2016-04-07 09:34:36'),('182','181','Kristiawan','','','','107',NULL,'2016-05-17 16:13:32'),('183','182','KAI DAOP 4 SEMARANG','','','','156',NULL,'2016-06-21 12:29:09'),('184','183','RSUD WONOSARI','','','','156',NULL,'2016-07-22 13:32:44'),('185','184','Pak Marwan','Staff Ahli  Kepala','','','107',NULL,'2016-08-08 15:52:39'),('186','185','BLH Jambi','','','','156',NULL,'2016-08-09 09:25:11'),('187','186','Kantor Pos Regional Jawa Timur','','','','128',NULL,'2016-08-12 14:23:33'),('188','187','Kantor Muhammadiyah Yogyakarta','','','','156',NULL,'2016-08-29 09:42:13'),('189','188','macananjaya cemerlang','','','','128',NULL,'2016-08-29 15:28:15'),('19','28','Pujo Santoso','','081802701420','','62',NULL,'2014-04-05 10:02:00'),('190','189','Airnav Jakarta','','','','156',NULL,'2016-09-09 16:00:37'),('191','190','Muh Taufik Hidayat  AP','PPKom','','','107',NULL,'2016-09-30 10:10:09'),('192','191','Abdul Hamid','','','','138',NULL,'2016-10-11 10:56:24'),('193','192','Mr Bambang IPDA','Pjs IT  STTKD','','','107',NULL,'2016-10-12 13:13:43'),('194','193','Bp Imam, Bp Annafi','ULP Kopertis','','','107',NULL,'2016-10-15 09:46:35'),('195','194','Pinandita Bima','Administrator PDE','08562982982','','107',NULL,'2016-10-27 08:59:27'),('196','195','-','','','','16',NULL,'2016-10-27 09:35:40'),('197','196','Andi K','','','','62',NULL,'2016-11-01 11:01:08'),('198','197','PT.POS Semarang','','','','128',NULL,'2016-11-01 13:58:59'),('199','198','Infra','','','','156',NULL,'2016-11-18 11:49:19'),('2','15','-','','','','16',NULL,'2013-10-09 08:39:07'),('20','29','Eko','','08174115292','','62',NULL,'2014-04-05 10:04:37'),('200','199','Mr. MUSTARI','KaBid  PDE & Multimedia','','','107',NULL,'2016-12-07 11:39:59'),('201','200','PT.POS Padang','','','','128',NULL,'2016-12-13 13:44:08'),('202','201','Brian Bp.','','089622229006','','107',NULL,'2016-12-14 15:26:42'),('203','202','-','','','','16',NULL,'2016-12-24 09:28:27'),('204','203','Yuda','','','','62',NULL,'2016-12-30 09:42:59'),('205','204','Budiono','','','','107',NULL,'2016-12-31 09:08:44'),('206','205','Agung Budi Prasetio','GM BDC','','','138',NULL,'2017-01-10 09:22:35'),('207','194','Heri P','KaBid  Infrastruktur','','hweriw@gmail.com','107',NULL,'2017-02-28 09:14:22'),('208','206','Dwi Setyawati  Bu','Ka Sie SarPras  Bag Umum','','','107',NULL,'2017-03-16 17:22:56'),('209','207','-','','','','16',NULL,'2017-04-27 13:43:21'),('21','30','Catur Iswahyudi','','0816686130','','62',NULL,'2014-04-05 10:06:11'),('210','208','Ir Luwarso','Direktur utama','+62 812-8197-1404','Luwarso@bumrpangan.com','103',NULL,'2017-05-02 08:42:59'),('211','209','direktorat angkatan udara','','','','128',NULL,'2017-09-11 13:35:43'),('212','210','Immanuel','Kasie SarPras - Dir Sarana Distribus & Logistik','62 - 021 - 3858171','','107',NULL,'2017-09-23 13:20:05'),('213','211','Redy  F','Pejabat Pengadaan','','','107',NULL,'2017-10-17 05:31:34'),('214','212','Ibu sherly','','','','83',NULL,'2017-11-09 08:52:27'),('215','213','Agung IC','','','','62',NULL,'2018-01-02 08:43:35'),('216','214','Surono','','','','62',NULL,'2018-01-02 08:44:05'),('217','215','Farid Akprind','','','','62',NULL,'2018-01-02 08:45:20'),('218','216','Tonny','','','','62',NULL,'2018-01-02 08:47:47'),('219','217','Dinar','','','','62',NULL,'2018-01-02 08:49:53'),('22','31','Agung','','081328759406','','62',NULL,'2014-04-05 10:07:13'),('220','218','Dinar','','','','62',NULL,'2018-01-02 08:50:34'),('221','219','Arif','','','','62',NULL,'2018-01-02 08:52:28'),('222','220','Yanto','','','','62',NULL,'2018-01-02 10:15:20'),('223','221','.','','','','62',NULL,'2018-01-02 10:15:51'),('224','222','-','','','','62',NULL,'2018-01-02 10:16:27'),('225','223','-','','','','62',NULL,'2018-01-02 10:16:57'),('226','224','-','','','','62',NULL,'2018-01-02 10:17:31'),('227','225','-','','','','62',NULL,'2018-01-02 10:17:59'),('228','226','-','','','','62',NULL,'2018-01-02 10:18:24'),('229','227','Kusnawi','','','','62',NULL,'2018-01-02 10:18:57'),('23','32','SUGENG','','','','82',NULL,'2014-04-05 10:07:39'),('230','228','-','','','','62',NULL,'2018-01-02 10:19:14'),('231','229','-','','','','62',NULL,'2018-01-02 10:19:35'),('232','230','-','','','','62',NULL,'2018-01-02 10:20:02'),('233','231','-','','','','62',NULL,'2018-01-02 10:20:22'),('234','232','-','','','','62',NULL,'2018-01-02 10:20:44'),('235','233','-','','','','62',NULL,'2018-01-02 10:21:13'),('236','234','-','','','','62',NULL,'2018-01-02 10:21:39'),('237','235','-','','','','62',NULL,'2018-01-02 10:22:06'),('238','236','-','','','','62',NULL,'2018-01-02 10:23:37'),('239','237','-','','','','62',NULL,'2018-01-02 10:23:53'),('24','33','Stenly','','08156880203','','62',NULL,'2014-04-05 10:10:00'),('240','238','-','','','','62',NULL,'2018-01-02 10:24:43'),('241','239','-','','','','62',NULL,'2018-01-02 10:25:10'),('242','240','-','','','','62',NULL,'2018-01-02 10:25:27'),('243','241','-','','','','62',NULL,'2018-01-02 10:27:06'),('244','242','-','','','','62',NULL,'2018-01-02 10:27:28'),('245','243','-','','','','62',NULL,'2018-01-02 10:27:56'),('246','244','-','','','','62',NULL,'2018-01-02 10:28:14'),('247','245','-','','','','62',NULL,'2018-01-02 10:28:33'),('248','246','-','','','','62',NULL,'2018-01-02 10:29:11'),('249','247','-','','','','62',NULL,'2018-01-02 10:29:34'),('25','34','Ari','','0817464841','','62',NULL,'2014-04-05 10:16:54'),('250','248','-','','','','62',NULL,'2018-01-02 10:30:13'),('251','249','-','-','','','62',NULL,'2018-01-02 10:30:28'),('252','250','-','','','','62',NULL,'2018-01-02 10:30:52'),('253','251','-','','','','62',NULL,'2018-01-02 10:31:15'),('254','252','-','','','','62',NULL,'2018-01-02 10:31:31'),('255','253','-','','','','62',NULL,'2018-01-02 10:31:50'),('256','254','-','','','','62',NULL,'2018-01-02 10:32:10'),('257','255','-','','','','62',NULL,'2018-01-02 10:32:35'),('258','256','-','','','','62',NULL,'2018-01-02 10:32:56'),('259','257','-','','','','62',NULL,'2018-01-02 10:33:21'),('26','35','Suharno','','08121522200','','62',NULL,'2014-04-05 10:18:46'),('260','258','-','','','','62',NULL,'2018-01-02 11:42:34'),('261','259','-','','','','62',NULL,'2018-01-02 11:42:56'),('262','260','-','','','','62',NULL,'2018-01-02 11:43:17'),('263','261','-','','','','62',NULL,'2018-01-02 11:44:15'),('264','262','-','','','','62',NULL,'2018-01-02 11:44:50'),('265','263','-','','','','62',NULL,'2018-01-04 10:23:44'),('266','264','-','','','','62',NULL,'2018-01-04 10:30:18'),('267','265','-','','','','62',NULL,'2018-01-05 09:46:34'),('268','266','-','','','','62',NULL,'2018-01-08 11:40:52'),('269','267','Bayu Sekti','Staff Teknis','','','194',NULL,'2018-01-10 14:29:59'),('27','36','Maryadi','','02748221563','','62',NULL,'2014-04-05 10:20:30'),('270','268','Nanang FK','','','','194',NULL,'2018-01-10 15:41:19'),('271','269','Hairul','-','+62 81314221003','kosong@kosong.com','16',NULL,'2018-01-12 14:29:48'),('272','270','-','','','','62',NULL,'2018-01-15 08:53:32'),('273','271','-','','','','62',NULL,'2018-01-15 08:56:45'),('274','272','-','','','','62',NULL,'2018-01-22 08:53:23'),('275','273','-','','','','62',NULL,'2018-01-22 08:55:03'),('276','274','-','','','','62',NULL,'2018-02-12 10:43:51'),('277','275','Endro P Pak','Sek Din','','','107',NULL,'2018-02-20 14:20:50'),('278','276','Deddy','','','','150',NULL,'2018-02-23 11:41:07'),('279','277','Cicik','','081578544280','','194',NULL,'2018-03-01 15:41:26'),('28','37','CS Dhecyber','','02152921111','','62',NULL,'2014-04-05 10:22:35'),('280','278','RSUD Sleman','','','','128',NULL,'2018-03-05 09:02:30'),('29','38','Jamal','','081915623972','','62',NULL,'2014-04-05 10:24:37'),('3','16','-','','','','16',NULL,'2013-10-10 09:57:03'),('30','39','Vino Renaldo','','087738081970','','62',NULL,'2014-04-05 10:27:46'),('31','40','YUDHA','','','','82',NULL,'2014-04-05 10:42:24'),('32','41','Rijal','','','','82',NULL,'2014-04-05 10:44:08'),('33','42','Twinky','','0274418509','','62',NULL,'2014-04-05 10:44:24'),('34','43','Suparwoto','','081328742429','','62',NULL,'2014-04-05 10:47:46'),('35','44','Surawan','','081578760952','','62',NULL,'2014-04-05 10:48:56'),('36','45','Harno','','085640029400','','62',NULL,'2014-04-05 10:54:09'),('37','46','Adian','','087739151789','','62',NULL,'2014-04-05 10:56:10'),('38','47','AGUNG BUDI','','','','82',NULL,'2014-04-05 10:56:32'),('39','48','Udi Mulyanto','','085713711942','','62',NULL,'2014-04-05 10:59:03'),('41','50','Indra','','0816684902','','62',NULL,'2014-04-05 11:01:47'),('42','51','Yuni','','085725383031','','62',NULL,'2014-04-05 11:03:35'),('43','52','Putut','','08161629633','','62',NULL,'2014-04-05 11:04:36'),('44','53','Agus','','087838248639','','62',NULL,'2014-04-05 11:06:35'),('45','54','Eko Arif','','085878586662','','62',NULL,'2014-04-05 11:08:19'),('46','55','Totok','','08122797908','','62',NULL,'2014-04-05 11:09:31'),('47','56','Rofiq','','08156583545','','62',NULL,'2014-04-05 11:10:07'),('48','57','Eko','','','','82',NULL,'2014-04-07 09:42:17'),('49','58','Syahrizal','','','','82',NULL,'2014-04-07 09:44:22'),('5','18','Rio Permana','Staff','','rio.permana@gmail.com','16',NULL,'2013-11-28 10:47:10'),('50','59','ADI SUSANTO','','','','61',NULL,'2014-04-07 14:51:28'),('51','60','ANDRI','','+6282171872261','','85',NULL,'2014-04-07 16:26:19'),('54','63','FO Banyuasin','','','','100',NULL,'2014-04-08 08:47:15'),('56','65','Tristanto Aji SKom','Kepala UPT','','ajik@amikom.ac.id','107',NULL,'2014-04-15 11:54:41'),('57','66','Suparwoto, SKom','WaDir','','qmp0el007@gmail.com','107',NULL,'2014-04-15 11:56:30'),('58','26','Surono','','081390914629','','62',NULL,'2014-04-17 16:28:06'),('59','67','Brata','','081519854666','','62',NULL,'2014-04-17 16:35:35'),('6','18','Departemen DAU','','','simdau_data@yahoo.com','16',NULL,'2013-11-28 10:45:31'),('61','69','Khoirudin','','','','62',NULL,'2014-04-18 08:20:38'),('62','70','Balrian','','08122322364','','62',NULL,'2014-04-19 13:48:37'),('63','71','Blm Ada','','','','88',NULL,'2014-04-21 10:34:41'),('64','72','Octania','','','','20',NULL,'2014-04-22 10:43:06'),('65','73','Agung','','081325767665','','87',NULL,'2014-04-22 12:37:29'),('66','74','Bp Angga','Kepala Seksie','08171267642','','107',NULL,'2014-04-23 13:48:23'),('67','75','Natsir','','','','87',NULL,'2014-04-24 13:31:49'),('68','76','Pungki','','','','87',NULL,'2014-04-24 13:34:00'),('7','18','Departemen Sistem Informasi','','','sisfo.dau@dephub.go.id','16',NULL,'2013-11-28 10:45:59'),('70','78','Darmawan','Procurement','','','85',NULL,'2014-04-29 10:02:02'),('71','79','DISNAKERTRANS PEMKAB GK','','','','100',NULL,'2014-04-29 12:37:26'),('74','82','LPSE MAGELANG','','(0293)-788922','','100',NULL,'2014-05-02 11:25:28'),('75','83','Ibu Anita Triana','dekan','081393429605','','87',NULL,'2014-05-03 09:14:21'),('76','84','LPSE FEB UGM','','','','100',NULL,'2014-05-06 16:04:04'),('77','85','CAPIL KAB KEBUMEN','','Telp./ Fax. (0287) 381567','','100',NULL,'2014-05-08 10:55:56'),('78','86','Ibu Anie','Kepala Bidang','','botebodol@gmail.com','107',NULL,'2014-05-08 11:47:04'),('79','87','LPSE Salatiga','','(0298) 326767, 314428','','100',NULL,'2014-05-10 10:40:29'),('8','18','Feri','Staff Produksi','','mr_feriyadi@yahoo.co.id','16',NULL,'2013-11-28 10:59:02'),('80','88','Eko','','085726639991','','65',NULL,'2014-05-12 10:28:56'),('81','89','Aris','','','','65',NULL,'2014-05-12 10:29:32'),('82','90','Kepsek','','','','65',NULL,'2014-05-12 10:30:17'),('83','91','Asep','Ka. UPT','081906475111','tubagusasep@gmail.com','84',NULL,'2014-05-13 08:18:03'),('84','92','KEMENLH DIY','','','','100',NULL,'2014-05-14 15:30:19'),('85','93','Herry','IT Development Manager','0216336789 ext 1130','herry@btn.co.id','103',NULL,'2014-05-19 08:08:40'),('86','94','Agus Bahari','Owner','085842258977','agusbisnis@yahoo.com','103',NULL,'2014-05-19 13:37:29'),('87','95','Ali','IT','081210108581','valdy_ali@yahoo.com','103',NULL,'2014-05-19 15:06:51'),('88','96','Eko Budhiarto','Staff IT','085298002228','ppekalimantan@gmail.com','103',NULL,'2014-05-19 15:30:51'),('89','97','Narwawi Pramudiarta','','','','16',NULL,'2014-05-19 16:07:30'),('9','18','Rahmat','Staff Produksi','081 321 4880 38','eskalapamuda@gmail.com','16',NULL,'2013-11-28 10:59:11'),('90','98','FAK MIPA UGM','','','','100',NULL,'2014-05-21 13:41:00'),('91','99','AMIKOM YOGYAKARTA','AMIKOM YOGYAKARTA','','','100',NULL,'2014-05-23 09:16:34'),('92','100','dishubkominfo PURWOREJO','','','','100',NULL,'2014-05-23 15:50:48'),('94','102','pt pura barutama kudus','','+62 291 444 361-5','','100',NULL,'2014-06-09 11:58:16'),('95','103','Yan Lutfianto','Pj Lokal','','','107',NULL,'2014-06-09 12:17:02'),('96','104','Bu Ratri','Kepala BAU','','','107',NULL,'2014-06-10 09:43:21'),('97','105','Kurniawan Muhammad','Koord. SubBag Umum','','','107',NULL,'2015-05-19 13:11:39'),('98','47','Bastin','Staff Bidang','081802513619','bastin13620@gmail.com','16',NULL,'2014-06-14 11:03:08'),('99','47','Della','Asisten Keuangan','08161805124','dellarisaastari@gmail.com','16',NULL,'2014-06-14 11:03:40');

UNLOCK TABLES;

/*Table structure for table `projects_doc` */

DROP TABLE IF EXISTS `projects_doc`;

CREATE TABLE `projects_doc` (
  `doc_id` varchar(20) NOT NULL,
  `project_id` varchar(10) DEFAULT NULL,
  `jenis_id` varchar(5) DEFAULT NULL,
  `doc_notes` varchar(100) DEFAULT NULL,
  `doc_st` enum('waiting','done') DEFAULT 'waiting',
  `mdb` varchar(10) DEFAULT NULL,
  `mdb_name` varchar(50) DEFAULT NULL,
  `mdd` datetime DEFAULT NULL,
  PRIMARY KEY (`doc_id`),
  KEY `FK_projects_doc_p` (`project_id`),
  KEY `jenis_id` (`jenis_id`),
  CONSTRAINT `projects_doc_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `projects` (`project_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `projects_doc_ibfk_2` FOREIGN KEY (`jenis_id`) REFERENCES `data_jenis_dokumen` (`jenis_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `projects_doc` */

LOCK TABLES `projects_doc` WRITE;

insert  into `projects_doc`(`doc_id`,`project_id`,`jenis_id`,`doc_notes`,`doc_st`,`mdb`,`mdb_name`,`mdd`) values ('10','0000000114',NULL,'jadwal lelang','done','61',NULL,'2014-05-03 09:02:18'),('100','0000000048',NULL,'format exel','done','61',NULL,'2014-09-22 10:30:14'),('101','0000000048',NULL,'','done','61',NULL,'2014-09-22 10:31:07'),('102','0000000048',NULL,'','done','61',NULL,'2014-09-22 10:32:03'),('103','0000000063',NULL,'','done','117',NULL,'2014-11-11 16:09:59'),('104','0000000055',NULL,'','done','117',NULL,'2014-11-11 16:21:39'),('106','0000000159',NULL,'-','done','117',NULL,'2014-11-11 16:24:53'),('109','0000000132',NULL,'','done','61',NULL,'2014-11-11 15:53:00'),('11','0000000109',NULL,'jadwal lelang','done','61',NULL,'2014-05-03 09:13:50'),('110','0000000132',NULL,'','done','61',NULL,'2014-11-11 15:53:25'),('111','0000000132',NULL,'','done','61',NULL,'2014-11-11 15:53:45'),('112','0000000132',NULL,'','done','61',NULL,'2014-11-11 15:54:02'),('114','0000000063',NULL,'','done','117',NULL,'2014-11-13 10:50:42'),('115','0000000063',NULL,'','done','117',NULL,'2014-11-13 10:53:34'),('116','0000000063',NULL,'','done','117',NULL,'2014-11-13 11:00:56'),('117','0000000132',NULL,'','done','117',NULL,'2014-11-13 11:04:31'),('118','0000000132',NULL,'','done','117',NULL,'2014-11-13 11:07:47'),('119','0000000132',NULL,'','done','117',NULL,'2014-11-13 11:09:10'),('12','0000000059',NULL,'KAK Sistim Antrian Pelayanan Publik','waiting','20',NULL,'2014-05-06 10:39:05'),('120','0000000133',NULL,'','done','117',NULL,'2014-11-13 11:16:50'),('121','0000000109',NULL,'','done','117',NULL,'2014-11-13 11:21:13'),('122','0000000109',NULL,'','done','117',NULL,'2014-11-13 11:22:36'),('123','0000000054',NULL,'','done','117',NULL,'2014-11-13 11:27:05'),('124','0000000055',NULL,'','done','117',NULL,'2014-11-13 11:31:33'),('125','0000000055',NULL,'','done','117',NULL,'2014-11-13 11:33:39'),('126','0000000159',NULL,'','done','117',NULL,'2014-11-13 11:37:14'),('127','0000000159',NULL,'','done','117',NULL,'2014-11-13 11:38:10'),('129','0000000167',NULL,'.','done','117',NULL,'2014-12-05 09:25:17'),('130','0000000167',NULL,'-','done','117',NULL,'2014-12-05 09:18:32'),('131','0000000257',NULL,'','done','86',NULL,'2015-05-07 10:17:53'),('132','0000000263',NULL,'','done','86',NULL,'2015-05-07 10:34:11'),('133','0000000315',NULL,'','done','86',NULL,'2015-11-03 15:14:37'),('14','0000000057',NULL,'KAK Pas Boking','waiting','20',NULL,'2014-05-06 13:47:12'),('15','0000000055',NULL,'KAK Pas Manado','waiting','20',NULL,'2014-05-06 14:37:50'),('17','0000000054',NULL,'KAK PAS PADANG','waiting','20',NULL,'2014-05-07 09:56:59'),('18','0000000114',NULL,'List Tenaga Ahli','waiting','20',NULL,'2014-05-07 09:59:25'),('19','0000000160',NULL,'Presentasi BTN','done','103',NULL,'2014-05-19 08:30:56'),('21','0000000161',NULL,'KAK + RAB WEBSITE KOBU I 2014','done','1',NULL,'2014-05-19 10:45:02'),('25','0000000162',NULL,'INISIASI KAK EKSEKUTIF SUMMARY KOBU I','done','1',NULL,'2014-05-19 12:05:44'),('26','0000000163',NULL,'Quotation EDAPEM 2','done','103',NULL,'2014-05-19 12:24:11'),('27','0000000164',NULL,'Quotation DSTB','done','103',NULL,'2014-05-19 12:31:32'),('28','0000000165',NULL,'Quotation Pick Up Invoice','waiting','103',NULL,'2014-05-19 13:15:43'),('29','0000000167',NULL,'Sedang Menunggu Lelang, Sudah di Deliver ke Gilang untuk di bawa ke ULP','done','1',NULL,'2014-05-19 14:58:26'),('32','0000000168',NULL,'Dokumen Penawaran','done','103',NULL,'2014-05-19 15:18:57'),('33','0000000177',NULL,'Aplikasi Emonev Jaldin','done','103',NULL,'2014-05-23 14:20:39'),('34','0000000184',NULL,'KAK E Arsip','waiting','20',NULL,'2014-05-28 14:27:31'),('35','0000000184',NULL,'RAB E Arsip','waiting','20',NULL,'2014-05-28 14:28:26'),('36','0000000183',NULL,'Dok.Penawaran','done','20',NULL,'2014-06-13 08:16:48'),('37','0000000054',NULL,'jadwal lelang','done','20',NULL,'2014-06-02 15:04:36'),('38','0000000063',NULL,'-','done','61',NULL,'2014-06-03 09:18:12'),('39','0000000063',NULL,'','done','61',NULL,'2014-06-03 09:19:41'),('40','0000000058',NULL,'','done','61',NULL,'2014-06-03 09:21:27'),('41','0000000058',NULL,'','done','61',NULL,'2014-06-03 09:22:12'),('42','0000000060',NULL,'','done','61',NULL,'2014-06-03 09:23:20'),('43','0000000060',NULL,'','done','61',NULL,'2014-06-03 09:23:35'),('44','0000000060',NULL,'','done','61',NULL,'2014-06-05 10:43:47'),('45','0000000058',NULL,'','done','61',NULL,'2014-06-05 10:44:28'),('46','0000000114',NULL,'','done','61',NULL,'2014-06-11 14:04:36'),('47','0000000114',NULL,'revisi penawaran yg sudah include pajak','done','61',NULL,'2014-06-11 14:08:50'),('48','0000000114',NULL,'','done','61',NULL,'2014-06-17 14:33:41'),('49','0000000114',NULL,'','done','61',NULL,'2014-06-17 14:33:26'),('50','0000000054',NULL,'','done','61',NULL,'2014-06-11 14:41:08'),('51','0000000054',NULL,'','done','61',NULL,'2014-06-11 14:43:21'),('52','0000000054',NULL,'','done','61',NULL,'2014-06-11 15:08:44'),('53','0000000122',NULL,'Rfp, fsd, penawaran','done','12',NULL,'2014-06-13 08:27:46'),('54','0000000060',NULL,'RAB Penawaran Inspector','done','20',NULL,'2014-07-03 10:06:23'),('55','0000000109',NULL,'Dok.Pemilihan Slottime','done','20',NULL,'2014-06-12 09:58:40'),('56','0000000109',NULL,'List tenaga Ahli','done','20',NULL,'2014-06-12 10:01:23'),('57','0000000109',NULL,'RAB Slottime penawaran','done','20',NULL,'2014-06-12 10:01:51'),('59','0000000109',NULL,'DOKTEK Pemenang','done','20',NULL,'2014-06-12 10:25:49'),('60','0000000109',NULL,'','done','61',NULL,'2014-06-12 13:59:06'),('61','0000000063',NULL,'','done','61',NULL,'2014-06-12 15:58:06'),('62','0000000063',NULL,'','done','61',NULL,'2014-06-12 16:00:45'),('63','0000000063',NULL,'','done','61',NULL,'2014-06-12 16:02:38'),('64','0000000122',NULL,'Dokumen Project','done','12',NULL,'2014-06-13 08:29:05'),('65','0000000060',NULL,'','done','61',NULL,'2014-06-16 08:43:21'),('66','0000000058',NULL,'','done','61',NULL,'2014-06-16 08:44:04'),('67','0000000054',NULL,'','done','61',NULL,'2014-06-17 15:18:33'),('68','0000000054',NULL,'','done','61',NULL,'2014-06-17 15:18:58'),('69','0000000122',NULL,'BAhan Negosiasi Project Cash Pick Up / Invoice','done','20',NULL,'2014-06-18 15:09:37'),('7','0000000114',NULL,'Dok_Seleksi_Umum_DAO','done','61',NULL,'2014-05-03 08:48:10'),('70','0000000180',NULL,'Bahan Negosiasi Project MCM','done','20',NULL,'2014-06-18 15:10:36'),('71','0000000133',NULL,'','done','61',NULL,'2014-06-19 08:38:15'),('72','0000000133',NULL,'yang fix nunggu upload panitia di lpse','waiting','61',NULL,'2014-06-19 08:39:50'),('73','0000000133',NULL,'','done','61',NULL,'2014-06-19 08:40:46'),('74','0000000055',NULL,'','done','61',NULL,'2014-06-23 15:37:39'),('75','0000000159',NULL,'','done','61',NULL,'2014-06-23 15:38:32'),('80','0000000058',NULL,'','done','61',NULL,'2014-07-04 13:45:33'),('82','0000000133',NULL,'','done','61',NULL,'2014-07-08 08:59:33'),('83','0000000133',NULL,'','done','61',NULL,'2014-07-08 09:08:46'),('84','0000000159',NULL,'','done','61',NULL,'2014-07-10 14:34:57'),('85','0000000055',NULL,'','done','61',NULL,'2014-07-10 14:40:39'),('86','0000000055',NULL,'','done','61',NULL,'2014-07-14 12:54:54'),('87','0000000159',NULL,'','done','61',NULL,'2014-07-14 12:55:48'),('88','0000000134',NULL,'','done','61',NULL,'2014-07-21 10:00:25'),('89','0000000134',NULL,'','done','61',NULL,'2014-07-21 10:00:44'),('90','0000000134',NULL,'','done','61',NULL,'2014-07-21 10:21:37'),('91','0000000055',NULL,'','done','61',NULL,'2014-07-21 10:27:13'),('92','0000000055',NULL,'','done','61',NULL,'2014-07-21 10:27:58'),('93','0000000159',NULL,'','done','61',NULL,'2014-07-21 10:28:56'),('94','0000000159',NULL,'','done','61',NULL,'2014-07-21 11:02:04'),('95','0000000054',NULL,'Kontrak simpas padang','done','20',NULL,'2014-08-07 09:26:48'),('96','0000000054',NULL,'SP Pas Padang','done','20',NULL,'2014-08-07 09:27:09'),('97','0000000054',NULL,'SSKK Pas Padang','done','20',NULL,'2014-08-07 09:27:49'),('98','0000000048',NULL,'dokumen leang','done','61',NULL,'2014-09-22 10:28:25'),('99','0000000048',NULL,'','done','61',NULL,'2014-09-22 10:29:04');

UNLOCK TABLES;

/*Table structure for table `projects_doc_files` */

DROP TABLE IF EXISTS `projects_doc_files`;

CREATE TABLE `projects_doc_files` (
  `files_id` varchar(20) NOT NULL,
  `doc_id` varchar(20) DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `file_name` varchar(255) DEFAULT NULL,
  `mdb` varchar(10) DEFAULT NULL,
  `mdb_name` varchar(50) DEFAULT NULL,
  `mdd` datetime DEFAULT NULL,
  PRIMARY KEY (`files_id`),
  KEY `doc_id` (`doc_id`),
  CONSTRAINT `projects_doc_files_ibfk_1` FOREIGN KEY (`doc_id`) REFERENCES `projects_doc` (`doc_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `projects_doc_files` */

LOCK TABLES `projects_doc_files` WRITE;

UNLOCK TABLES;

/*Table structure for table `projects_invoices` */

DROP TABLE IF EXISTS `projects_invoices`;

CREATE TABLE `projects_invoices` (
  `invoices_id` varchar(20) NOT NULL,
  `termin_id` varchar(20) DEFAULT NULL,
  `invoices_nomor` varchar(50) DEFAULT NULL,
  `invoices_bulan` varchar(2) DEFAULT NULL,
  `invoices_tahun` year(4) DEFAULT NULL,
  `invoices_tanggal` date DEFAULT NULL,
  `invoices_jatuh_tempo` date DEFAULT NULL,
  `invoices_uraian` text,
  `invoices_jumlah` double unsigned DEFAULT NULL,
  `invoices_pajak_ppn` double unsigned DEFAULT NULL,
  `invoices_pajak_pph` double unsigned DEFAULT NULL,
  `invoices_total` double unsigned DEFAULT NULL,
  `invoices_status` enum('draft','process','paid','cancel') DEFAULT 'draft',
  `create_by` varchar(10) DEFAULT NULL,
  `create_by_name` varchar(50) DEFAULT NULL,
  `create_date` datetime DEFAULT NULL,
  `mdb` varchar(10) DEFAULT NULL,
  `mdb_name` varchar(50) DEFAULT NULL,
  `mdd` datetime DEFAULT NULL,
  PRIMARY KEY (`invoices_id`),
  KEY `termin_id` (`termin_id`),
  CONSTRAINT `projects_invoices_ibfk_1` FOREIGN KEY (`termin_id`) REFERENCES `projects_termin` (`termin_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `projects_invoices` */

LOCK TABLES `projects_invoices` WRITE;

UNLOCK TABLES;

/*Table structure for table `projects_kuitansi` */

DROP TABLE IF EXISTS `projects_kuitansi`;

CREATE TABLE `projects_kuitansi` (
  `invoices_id` varchar(20) NOT NULL,
  `jenis_pembayaran` enum('tunai','transfer') DEFAULT NULL,
  `kuitansi_nomor` varchar(50) DEFAULT NULL,
  `kuitansi_tanggal` date DEFAULT NULL,
  `kuitansi_dari_bank` varchar(100) DEFAULT NULL,
  `kuitansi_dari_rekening` varchar(50) DEFAULT NULL,
  `kuitansi_ke_bank` varchar(100) DEFAULT NULL,
  `kuitansi_ke_rekening` varchar(50) DEFAULT NULL,
  `kuitansi_status` enum('lunas','draft') DEFAULT 'draft',
  `mdb` varchar(10) DEFAULT NULL,
  `mdb_name` varchar(50) DEFAULT NULL,
  `mdd` datetime DEFAULT NULL,
  PRIMARY KEY (`invoices_id`),
  CONSTRAINT `projects_kuitansi_ibfk_1` FOREIGN KEY (`invoices_id`) REFERENCES `projects_invoices` (`invoices_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `projects_kuitansi` */

LOCK TABLES `projects_kuitansi` WRITE;

UNLOCK TABLES;

/*Table structure for table `projects_notes` */

DROP TABLE IF EXISTS `projects_notes`;

CREATE TABLE `projects_notes` (
  `note_id` varchar(20) NOT NULL,
  `project_id` varchar(10) DEFAULT NULL,
  `note_desc` text,
  `note_start_date` date DEFAULT NULL,
  `note_due_date` date DEFAULT NULL,
  `note_finish_date` date DEFAULT NULL,
  `note_st` enum('done','waiting') DEFAULT 'waiting',
  `mdb` varchar(10) DEFAULT NULL,
  `mdb_name` varchar(50) DEFAULT NULL,
  `mdd` datetime DEFAULT NULL,
  PRIMARY KEY (`note_id`),
  KEY `project_id` (`project_id`),
  CONSTRAINT `projects_notes_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `projects` (`project_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `projects_notes` */

LOCK TABLES `projects_notes` WRITE;

insert  into `projects_notes`(`note_id`,`project_id`,`note_desc`,`note_start_date`,`note_due_date`,`note_finish_date`,`note_st`,`mdb`,`mdb_name`,`mdd`) values ('1','106','Finishing version 1','2014-04-25',NULL,NULL,'done','87',NULL,'2014-04-25 11:57:59'),('10','67','STATUS CLOSING BARTER PEKERJAAN APLIKASI','2014-05-02',NULL,NULL,'done','61',NULL,'2014-05-02 11:45:42'),('12','147','Implementasi Bulan Mei,  pembayaran  bulan September 2014','2014-05-26',NULL,NULL,'done','107',NULL,'2014-05-17 11:15:03'),('13','160','Sudah di lakukan presentasi, tinggal menindaklanjuti kembali','2014-05-13',NULL,NULL,'done','103',NULL,'2014-05-19 08:35:47'),('14','161','Menunggu Proses ULP dan Siap Lelang','2014-05-19',NULL,NULL,'done','103',NULL,'2014-05-19 10:17:30'),('15','162','Sudah di Deliver ke Gilang, untuk proses ULP dan Lelang','2014-05-19',NULL,NULL,'done','103',NULL,'2014-05-19 12:12:05'),('16','163','Sedang Menunggu SPK','2014-05-19',NULL,NULL,'done','103',NULL,'2014-05-19 12:21:27'),('17','164','Sedang Menunggu SPK','2014-05-19',NULL,NULL,'done','103',NULL,'2014-05-19 12:30:15'),('18','165','Sedang Menunggu Proses Bidding Document','2014-05-19',NULL,NULL,'waiting','103',NULL,'2014-05-19 13:12:33'),('19','167','Sudah di Deliver ke Gilang, untuk proses ULP dan Lelang','2014-05-19',NULL,NULL,'done','103',NULL,'2014-05-19 14:53:59'),('2','122','Masih dlm Proses Penawaran. Persiapan Resource : mb Nurul.','2014-05-31',NULL,NULL,'waiting','20',NULL,'2014-05-28 11:51:26'),('20','168','Data sudah di deliver ke mas ali, selaku staff IT nya','2014-05-19',NULL,NULL,'done','103',NULL,'2014-05-19 15:09:55'),('21','172','low process nya sbb :  1. Nasabah generate file dari sistem SAP dalam bentuk txt (contoh terlampir t','2014-05-20',NULL,NULL,'waiting','103',NULL,'2014-05-20 05:45:16'),('22','109','Persiapan Pembuatan Dokumen Teknis','2014-05-22',NULL,NULL,'waiting','20',NULL,'2014-05-22 10:03:41'),('23','177','Masih Proses Pengajuan ke pak handoko','2014-05-23',NULL,NULL,'done','103',NULL,'2014-05-23 14:19:12'),('24','27','Tahap pembuatan user manual dan input data lama utk aplikasi sim asset','2014-05-30',NULL,NULL,'waiting','20',NULL,'2014-05-28 11:39:59'),('25','27','Persiapan Laporan Hasil Kerja ke Amikom','2014-05-31',NULL,NULL,'waiting','20',NULL,'2014-05-28 11:39:40'),('26','181','Tahap Requrement Proposal oleh ms Agung, Resource dari tim riset,','2014-06-01',NULL,NULL,'waiting','20',NULL,'2014-05-28 11:44:57'),('27','117','Penawaran Sudah diajukan ke VDMS. Pihak VDMS sdng proses pengajuan ke Belanda. ditunggu hasilnya','2014-05-30',NULL,NULL,'done','20',NULL,'2014-05-30 10:35:57'),('28','117','Jika di ACC, kemungkinan projek akan dilaksanakan Juli, agustus atau bahkan september 2014.','2014-05-30',NULL,NULL,'done','20',NULL,'2014-05-30 10:37:14'),('29','117','Project di Follow Up oleh mba Vidya sampai keluar hasil penawaran dr VDMS.','2014-05-30',NULL,NULL,'done','20',NULL,'2014-05-30 10:38:52'),('3','110','Pelaksanaan     by SPK No. 024/R.T/STMIK AMIKOM/IV/2014','2014-04-22',NULL,NULL,'done','107',NULL,'2014-04-26 09:29:17'),('30','56','Buat Jadwal untuk membahas perkembangan project emonev yg slsai pd akhir mei','2014-05-31',NULL,NULL,'waiting','20',NULL,'2014-05-30 11:03:53'),('31','184','Diperlukan informasi kepastian project dari ms Agung','2014-05-30',NULL,NULL,'done','20',NULL,'2014-05-30 11:12:06'),('33','183','Jangka Waktu pengerjaan  3 Bulan. Konfirmasikan kapan SPK nya.','2014-06-01',NULL,NULL,'waiting','20',NULL,'2014-05-30 11:22:16'),('34','182','Resource: Adit. Jangka Waktu Pengerjaan kurang dari 1 bulan.','2014-05-30',NULL,NULL,'done','20',NULL,'2014-05-30 11:40:39'),('35','55','Persiapan Pembuatan Dokumen Teknis','2014-06-07',NULL,NULL,'waiting','20',NULL,'2014-05-30 15:00:56'),('36','187','Pesanan Pengadaan Komputer AiO HP  6 Unit','2014-06-10',NULL,NULL,'done','107',NULL,'2014-06-09 12:20:30'),('37','188','Realisasi Belanja Barang2 Pesanan','2014-06-09',NULL,NULL,'done','107',NULL,'2014-06-09 15:28:14'),('38','189','Kegiatan Maintenance dan Service Komputer PC','2014-06-10',NULL,NULL,'done','107',NULL,'2014-06-10 09:47:01'),('39','122','Status sudah masuk penawaran','2014-06-11',NULL,NULL,'done','103',NULL,'2014-06-11 15:29:20'),('4','56','Pengembangan aplikasi bagian input','2014-05-02',NULL,NULL,'done','20',NULL,'2014-05-30 11:03:00'),('41','197','Permohonan Pembayaran','2014-09-19',NULL,NULL,'waiting','107',NULL,'2014-09-09 09:15:49'),('42','217','Pembicara dalam workshop tentang teknologi jaringan di Acara PPEJ di Malang','2014-10-29',NULL,NULL,'done','107',NULL,'2014-10-29 11:33:50'),('43','219','Pelaksanaan Pengiriman Barang dan Pemeriksaan','2014-11-03',NULL,NULL,'done','107',NULL,'2014-11-06 13:25:37'),('44','288','Pengadan Harware - Perakitan PC - Installasi PC  (Paket Express 2 mgg)','2015-06-15',NULL,NULL,'done','107',NULL,'2015-06-15 14:09:47'),('45','455','Pelaksanaan Pekerjaan  dari tanggal 14 sd 31 agustus','2017-08-14',NULL,NULL,'done','107',NULL,'2017-08-09 15:11:28'),('5','56','Analisis bagian SPJ','2014-05-02',NULL,NULL,'done','20',NULL,'2014-05-30 11:02:56'),('6','56','Pengembangan aplikasi bagian SPJ','2014-05-09',NULL,NULL,'done','20',NULL,'2014-05-30 11:02:50'),('7','56','Analisis bagian Report & Rekapitulasi','2014-05-09',NULL,NULL,'done','20',NULL,'2014-05-30 11:02:45'),('8','56','Pengembangan aplikasi bagian Report & Rekapitulasi','2014-05-16',NULL,NULL,'done','20',NULL,'2014-05-30 11:02:40'),('9','56','Pertemuan dengan pihak KEMENPU','2014-05-16',NULL,NULL,'done','20',NULL,'2014-05-30 11:02:34');

UNLOCK TABLES;

/*Table structure for table `projects_termin` */

DROP TABLE IF EXISTS `projects_termin`;

CREATE TABLE `projects_termin` (
  `termin_id` varchar(20) NOT NULL,
  `kontrak_id` varchar(20) DEFAULT NULL,
  `termin_nomor` int(11) unsigned DEFAULT NULL,
  `termin_nilai` double unsigned DEFAULT NULL,
  `termin_tanggal` date DEFAULT NULL,
  `termin_bulan` varchar(2) DEFAULT NULL,
  `termin_tahun` year(4) DEFAULT NULL,
  `termin_uraian` varchar(255) DEFAULT NULL,
  `termin_status` enum('waiting','cancel','lunas') DEFAULT NULL,
  `mdb` varchar(10) DEFAULT NULL,
  `mdb_name` varchar(50) DEFAULT NULL,
  `mdd` datetime DEFAULT NULL,
  PRIMARY KEY (`termin_id`),
  KEY `kontrak_id` (`kontrak_id`),
  CONSTRAINT `projects_termin_ibfk_1` FOREIGN KEY (`kontrak_id`) REFERENCES `project_kontrak` (`kontrak_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `projects_termin` */

LOCK TABLES `projects_termin` WRITE;

UNLOCK TABLES;

/*Table structure for table `projects_users` */

DROP TABLE IF EXISTS `projects_users`;

CREATE TABLE `projects_users` (
  `project_id` varchar(10) NOT NULL,
  `user_id` varchar(10) NOT NULL,
  `mdb` varchar(10) DEFAULT NULL,
  `mdb_name` varchar(50) DEFAULT NULL,
  `mdd` datetime DEFAULT NULL,
  PRIMARY KEY (`project_id`,`user_id`),
  CONSTRAINT `projects_users_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `projects` (`project_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `projects_users` */

LOCK TABLES `projects_users` WRITE;

UNLOCK TABLES;

/*Table structure for table `rencana_detail` */

DROP TABLE IF EXISTS `rencana_detail`;

CREATE TABLE `rencana_detail` (
  `detail_id` varchar(20) NOT NULL,
  `kode_item` varchar(10) DEFAULT NULL,
  `detail_no` int(11) unsigned DEFAULT NULL,
  `detail_uraian` varchar(255) DEFAULT NULL,
  `detail_volume` int(11) unsigned DEFAULT NULL,
  `detail_satuan` varchar(50) DEFAULT NULL,
  `detail_harga` double unsigned DEFAULT NULL,
  `detail_sub_total` double unsigned DEFAULT NULL,
  `mdb` varchar(10) DEFAULT NULL,
  `mdb_name` varchar(50) DEFAULT NULL,
  `mdd` datetime DEFAULT NULL,
  PRIMARY KEY (`detail_id`),
  KEY `item_id` (`kode_item`),
  CONSTRAINT `rencana_detail_ibfk_1` FOREIGN KEY (`kode_item`) REFERENCES `rencana_item` (`kode_item`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `rencana_detail` */

LOCK TABLES `rencana_detail` WRITE;

UNLOCK TABLES;

/*Table structure for table `rencana_item` */

DROP TABLE IF EXISTS `rencana_item`;

CREATE TABLE `rencana_item` (
  `kode_item` varchar(10) NOT NULL,
  `kode_rev` varchar(7) NOT NULL,
  `kode_output` varchar(12) DEFAULT NULL,
  `kode_akun` varchar(8) DEFAULT NULL,
  `perusahaan_id` varchar(5) DEFAULT NULL,
  `item_no` int(11) unsigned DEFAULT NULL,
  `item_uraian` varchar(500) DEFAULT NULL,
  `item_jumlah` int(11) unsigned DEFAULT NULL,
  `item_satuan` varchar(50) DEFAULT NULL,
  `item_harga` double unsigned DEFAULT NULL,
  `item_total` double unsigned DEFAULT NULL,
  `mdb` varchar(10) DEFAULT NULL,
  `mdb_name` varchar(50) DEFAULT NULL,
  `mdd` datetime DEFAULT NULL,
  PRIMARY KEY (`kode_item`,`kode_rev`),
  KEY `kode_output` (`kode_output`),
  KEY `kode_akun` (`kode_akun`),
  KEY `rencana_item_ibfk_2` (`kode_akun`,`perusahaan_id`),
  CONSTRAINT `rencana_item_ibfk_1` FOREIGN KEY (`kode_output`) REFERENCES `rencana_output` (`kode_output`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `rencana_item_ibfk_2` FOREIGN KEY (`kode_akun`, `perusahaan_id`) REFERENCES `data_akun` (`kode_akun`, `perusahaan_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `rencana_item` */

LOCK TABLES `rencana_item` WRITE;

UNLOCK TABLES;

/*Table structure for table `rencana_kegiatan` */

DROP TABLE IF EXISTS `rencana_kegiatan`;

CREATE TABLE `rencana_kegiatan` (
  `kode_kegiatan` varchar(10) NOT NULL,
  `kode_program` varchar(8) DEFAULT NULL,
  `jenis_kode_kegiatan` varchar(1) DEFAULT NULL,
  `mdb` varchar(10) DEFAULT NULL,
  `mdb_name` varchar(50) DEFAULT NULL,
  `mdd` datetime DEFAULT NULL,
  PRIMARY KEY (`kode_kegiatan`),
  KEY `jenis_kode_kegiatan` (`jenis_kode_kegiatan`),
  KEY `kode_program` (`kode_program`),
  CONSTRAINT `rencana_kegiatan_ibfk_1` FOREIGN KEY (`jenis_kode_kegiatan`) REFERENCES `data_jenis_kegiatan` (`jenis_kode_kegiatan`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `rencana_kegiatan_ibfk_2` FOREIGN KEY (`kode_program`) REFERENCES `rencana_program` (`kode_program`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `rencana_kegiatan` */

LOCK TABLES `rencana_kegiatan` WRITE;

insert  into `rencana_kegiatan`(`kode_kegiatan`,`kode_program`,`jenis_kode_kegiatan`,`mdb`,`mdb_name`,`mdd`) values ('2018.SD.A','2018.SD','A',NULL,NULL,NULL),('2018.SD.B','2018.SD','B',NULL,NULL,NULL);

UNLOCK TABLES;

/*Table structure for table `rencana_output` */

DROP TABLE IF EXISTS `rencana_output`;

CREATE TABLE `rencana_output` (
  `kode_output` varchar(12) NOT NULL,
  `kode_kegiatan` varchar(10) DEFAULT NULL,
  `jenis_kode_output` varchar(5) DEFAULT NULL,
  `project_id` varchar(10) DEFAULT NULL,
  `mdb` varchar(10) DEFAULT NULL,
  `mdb_name` varchar(50) DEFAULT NULL,
  `mdd` datetime DEFAULT NULL,
  PRIMARY KEY (`kode_output`),
  KEY `kode_kegiatan` (`kode_kegiatan`),
  KEY `jenis_kode_output` (`jenis_kode_output`),
  KEY `project_id` (`project_id`),
  CONSTRAINT `rencana_output_ibfk_1` FOREIGN KEY (`kode_kegiatan`) REFERENCES `rencana_kegiatan` (`kode_kegiatan`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `rencana_output_ibfk_2` FOREIGN KEY (`jenis_kode_output`) REFERENCES `data_jenis_output` (`jenis_kode_output`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `rencana_output_ibfk_3` FOREIGN KEY (`project_id`) REFERENCES `projects` (`project_id`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `rencana_output` */

LOCK TABLES `rencana_output` WRITE;

insert  into `rencana_output`(`kode_output`,`kode_kegiatan`,`jenis_kode_output`,`project_id`,`mdb`,`mdb_name`,`mdd`) values ('2018.SD.A.01','2018.SD.A','00001','0000000001',NULL,NULL,NULL);

UNLOCK TABLES;

/*Table structure for table `rencana_program` */

DROP TABLE IF EXISTS `rencana_program`;

CREATE TABLE `rencana_program` (
  `kode_program` varchar(8) NOT NULL,
  `struktur_cd` varchar(10) DEFAULT NULL,
  `tahun` year(4) DEFAULT NULL,
  `nama_program` varchar(255) DEFAULT NULL,
  `mdb` varchar(10) DEFAULT NULL,
  `mdb_name` varchar(50) DEFAULT NULL,
  `mdd` datetime DEFAULT NULL,
  PRIMARY KEY (`kode_program`),
  KEY `rencana_program_ibfk_1` (`struktur_cd`),
  CONSTRAINT `rencana_program_ibfk_1` FOREIGN KEY (`struktur_cd`) REFERENCES `data_struktur_organisasi` (`struktur_cd`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `rencana_program` */

LOCK TABLES `rencana_program` WRITE;

insert  into `rencana_program`(`kode_program`,`struktur_cd`,`tahun`,`nama_program`,`mdb`,`mdb_name`,`mdd`) values ('2018.SD','001.01.00',2018,'Program Pengembangan dan Produksi Perangkat Lunak',NULL,NULL,NULL);

UNLOCK TABLES;

/*Table structure for table `suplier_alamat` */

DROP TABLE IF EXISTS `suplier_alamat`;

CREATE TABLE `suplier_alamat` (
  `alamat_kode` varchar(8) NOT NULL,
  `suplier_kode` varchar(4) DEFAULT NULL,
  `alamat` varchar(255) DEFAULT NULL,
  `kota` varchar(50) DEFAULT NULL,
  `propinsi` varchar(50) DEFAULT NULL,
  `negara_kode` varchar(2) DEFAULT NULL,
  `negara_nama` varchar(50) DEFAULT NULL,
  `kode_pos` varchar(50) DEFAULT NULL,
  `mdb` varchar(10) DEFAULT NULL,
  `mdb_name` varchar(50) DEFAULT NULL,
  `mdd` datetime DEFAULT NULL,
  PRIMARY KEY (`alamat_kode`),
  KEY `suplier_alamat_ibfk_1` (`suplier_kode`),
  CONSTRAINT `suplier_alamat_ibfk_1` FOREIGN KEY (`suplier_kode`) REFERENCES `suplier_data` (`suplier_kode`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `suplier_alamat` */

LOCK TABLES `suplier_alamat` WRITE;

insert  into `suplier_alamat`(`alamat_kode`,`suplier_kode`,`alamat`,`kota`,`propinsi`,`negara_kode`,`negara_nama`,`kode_pos`,`mdb`,`mdb_name`,`mdd`) values ('17102500','0010','Jl. Ring Road Utara Condong Catur','Sleman','Yogyakarta','ID','INDONESIA','55283','1705150001','admin','2017-10-25 08:33:43'),('17112325','0020','44','444','4444','ID','INDONESIA','52222','1705150001','admin','2017-11-23 10:31:02');

UNLOCK TABLES;

/*Table structure for table `suplier_data` */

DROP TABLE IF EXISTS `suplier_data`;

CREATE TABLE `suplier_data` (
  `suplier_kode` varchar(4) NOT NULL,
  `jenis_id` varchar(2) DEFAULT NULL,
  `suplier_nama` varchar(100) DEFAULT NULL,
  `suplier_npwp` varchar(25) DEFAULT NULL,
  `suplier_email` varchar(50) DEFAULT NULL,
  `suplier_telepon` varchar(50) DEFAULT NULL,
  `mdb` varchar(10) DEFAULT NULL,
  `mdb_name` varchar(50) DEFAULT NULL,
  `mdd` datetime DEFAULT NULL,
  PRIMARY KEY (`suplier_kode`),
  KEY `jenis_id` (`jenis_id`),
  CONSTRAINT `suplier_data_ibfk_1` FOREIGN KEY (`jenis_id`) REFERENCES `suplier_jenis` (`jenis_id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `suplier_data` */

LOCK TABLES `suplier_data` WRITE;

insert  into `suplier_data`(`suplier_kode`,`jenis_id`,`suplier_nama`,`suplier_npwp`,`suplier_email`,`suplier_telepon`,`mdb`,`mdb_name`,`mdd`) values ('0010','02','PT. TIME EXCELINDO','02.205.740.0-542.000','sp2dhatpen@gmail.com','02165867830','1705150001','admin','2017-11-14 11:14:58'),('0020','02','TTTT','34.343.434.3-434.343','C@GMAIL.COM','32EFEFEFDFDFF','1705150001','admin','2017-11-23 10:32:19');

UNLOCK TABLES;

/*Table structure for table `suplier_jenis` */

DROP TABLE IF EXISTS `suplier_jenis`;

CREATE TABLE `suplier_jenis` (
  `jenis_id` varchar(2) NOT NULL,
  `jenis_suplier` varchar(50) DEFAULT NULL,
  `mdb` varchar(10) DEFAULT NULL,
  `mdb_name` varchar(50) DEFAULT NULL,
  `mdd` datetime DEFAULT NULL,
  PRIMARY KEY (`jenis_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `suplier_jenis` */

LOCK TABLES `suplier_jenis` WRITE;

insert  into `suplier_jenis`(`jenis_id`,`jenis_suplier`,`mdb`,`mdb_name`,`mdd`) values ('01','SATUAN KERJA',NULL,NULL,NULL),('02','PENYEDIA BARANG DAN JASA',NULL,NULL,NULL),('03','PEGAWAI',NULL,NULL,NULL),('04','BA 999',NULL,NULL,NULL),('05','TRANSFER DAERAH',NULL,NULL,NULL),('06','PENERUSAN PINJAMAN',NULL,NULL,NULL);

UNLOCK TABLES;

/*Table structure for table `suplier_rekening` */

DROP TABLE IF EXISTS `suplier_rekening`;

CREATE TABLE `suplier_rekening` (
  `rekening_kode` varchar(12) NOT NULL,
  `alamat_kode` varchar(8) DEFAULT NULL,
  `bank_jenis` enum('Bank Dalam Negeri','Bank Luar Negeri') DEFAULT NULL,
  `bank_kode` varchar(15) DEFAULT NULL,
  `bank_nama` varchar(100) DEFAULT NULL,
  `bank_singkatan` varchar(50) DEFAULT NULL,
  `rekening_nama` varchar(50) DEFAULT NULL,
  `rekening_nomor` varchar(20) DEFAULT NULL,
  `mdb` varchar(10) DEFAULT NULL,
  `mdb_name` varchar(50) DEFAULT NULL,
  `mdd` datetime DEFAULT NULL,
  PRIMARY KEY (`rekening_kode`),
  KEY `alamat_kode` (`alamat_kode`),
  CONSTRAINT `suplier_rekening_ibfk_1` FOREIGN KEY (`alamat_kode`) REFERENCES `suplier_alamat` (`alamat_kode`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `suplier_rekening` */

LOCK TABLES `suplier_rekening` WRITE;

insert  into `suplier_rekening`(`rekening_kode`,`alamat_kode`,`bank_jenis`,`bank_kode`,`bank_nama`,`bank_singkatan`,`rekening_nama`,`rekening_nomor`,`mdb`,`mdb_name`,`mdd`) values ('1710250001','17102500','Bank Dalam Negeri','520009000990','Bank Negara Indonesia','BNI','Time Excelindo BNI','0191879749','1705150001','admin','2017-10-25 08:37:07'),('1711230002','17112325','Bank Dalam Negeri','520009000990','Bank Negara Indonesia','BNI','KASD','1188292828283','1705150001','admin','2017-11-23 10:39:51'),('1711230003','17112325','Bank Dalam Negeri','520009000990','Bank Negara Indonesia','BNI','KASD','1188292828283','1705150001','admin','2017-11-23 10:42:50');

UNLOCK TABLES;

/*Table structure for table `surat_lembur` */

DROP TABLE IF EXISTS `surat_lembur`;

CREATE TABLE `surat_lembur` (
  `overtime_id` varchar(20) NOT NULL,
  `struktur_cd` varchar(10) DEFAULT NULL,
  `project_id` varchar(10) DEFAULT NULL,
  `overtime_date` date DEFAULT NULL,
  `overtime_start` time DEFAULT NULL,
  `overtime_end` time DEFAULT NULL,
  `overtime_reason` text,
  `overtime_st` enum('draft','waiting','approved','rejected') DEFAULT 'draft',
  `overtime_send_by` varchar(10) DEFAULT NULL,
  `overtime_send_by_name` varchar(50) DEFAULT NULL,
  `overtime_send_date` datetime DEFAULT NULL,
  `rekap_st` enum('1','0') DEFAULT '0',
  `mdb` varchar(10) DEFAULT NULL,
  `mdb_name` varchar(50) DEFAULT NULL,
  `mdd` datetime DEFAULT NULL,
  PRIMARY KEY (`overtime_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `surat_lembur` */

LOCK TABLES `surat_lembur` WRITE;

insert  into `surat_lembur`(`overtime_id`,`struktur_cd`,`project_id`,`overtime_date`,`overtime_start`,`overtime_end`,`overtime_reason`,`overtime_st`,`overtime_send_by`,`overtime_send_by_name`,`overtime_send_date`,`rekap_st`,`mdb`,`mdb_name`,`mdd`) values ('20180425000000000001','001.01.00','595','2018-04-02','17:00:00','21:00:00','TESTING APLIKASI TASK MANAGER','approved',NULL,NULL,NULL,'0','1804170001','Welly WSP','2018-04-25 16:19:06'),('20180425000000000002','001.01.00','367','2018-04-25','17:00:00','21:00:00','AWEWE','approved','1804170001','Welly WSP','2018-04-25 16:29:11','0','1804170001','Welly WSP','2018-04-25 16:30:54'),('20180425000000000003','001.01.00','448','2018-04-25','18:00:00','21:00:00','ALOO','approved','1804170001','Welly WSP','2018-04-26 10:13:04','0','1804170001','Welly WSP','2018-04-26 10:14:15'),('20180425000000000004','001.01.00','433','2018-04-25','18:00:00','21:00:00','SAASS','approved','1804170001','Welly WSP','2018-04-25 16:52:47','0','1804170001','Welly WSP','2018-04-25 16:55:29'),('20180426000000000006','001.01.00','417','2018-04-26','17:00:00','21:00:00','POLDAJATIM','waiting','1804170001','Welly WSP','2018-04-26 08:41:32','0','1804170001','Welly WSP','2018-04-26 08:21:40'),('20180426000000000007','001.01.00','441','2018-04-26','20:00:00','21:00:00','DEPHUB LEMBUR','rejected','1804170001','Welly WSP','2018-04-26 10:09:40','0','1804170001','Welly WSP','2018-04-26 10:23:39'),('20180426000000000008','001.01.00','339','2018-04-26','20:00:00','22:00:00','BALI LEMBUR','waiting','1804170001','Welly WSP','2018-04-26 09:30:44','0','1804170001','Welly WSP','2018-04-26 09:13:01'),('20180426000000000009','001.01.00','433','2018-04-26','21:00:00','22:00:00','KALIBRASI','waiting','1804170001','Welly WSP','2018-04-26 11:53:22','0','1804170001','Welly WSP','2018-04-26 11:39:27'),('20180501000000000005','001.01.00','443','2018-05-01','17:00:00','21:00:00','TESTING APLIKASI PROSES PENGAJUAN LEMBUR','waiting','1804170001','Welly WSP','2018-04-26 10:17:31','0','1804170001','Welly WSP','2018-04-26 08:16:02'),('20180517000000000010','001.01.00','339','2018-05-17','17:00:00','22:00:00','TES MEI','waiting','1804170001','Welly WSP','2018-04-26 12:00:19','0','1804170001','Welly WSP','2018-04-26 11:59:36');

UNLOCK TABLES;

/*Table structure for table `surat_lembur_process` */

DROP TABLE IF EXISTS `surat_lembur_process`;

CREATE TABLE `surat_lembur_process` (
  `process_id` varchar(20) NOT NULL,
  `overtime_id` varchar(20) DEFAULT NULL,
  `flow_id` varchar(5) DEFAULT NULL,
  `flow_revisi_id` varchar(5) DEFAULT NULL,
  `process_references_id` varchar(20) DEFAULT NULL,
  `process_st` enum('waiting','approve','reject') DEFAULT 'waiting',
  `action_st` enum('process','done') DEFAULT 'process',
  `catatan` text,
  `mdb` varchar(10) DEFAULT NULL,
  `mdb_name` varchar(50) DEFAULT NULL,
  `mdd` datetime DEFAULT NULL,
  `mdb_finish` varchar(10) DEFAULT NULL,
  `mdb_finish_name` varchar(50) DEFAULT NULL,
  `mdd_finish` datetime DEFAULT NULL,
  PRIMARY KEY (`process_id`),
  KEY `overtime_id` (`overtime_id`),
  CONSTRAINT `surat_lembur_process_ibfk_1` FOREIGN KEY (`overtime_id`) REFERENCES `surat_lembur` (`overtime_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `surat_lembur_process` */

LOCK TABLES `surat_lembur_process` WRITE;

insert  into `surat_lembur_process`(`process_id`,`overtime_id`,`flow_id`,`flow_revisi_id`,`process_references_id`,`process_st`,`action_st`,`catatan`,`mdb`,`mdb_name`,`mdd`,`mdb_finish`,`mdb_finish_name`,`mdd_finish`) values ('15246369079155','20180425000000000001','13001',NULL,NULL,'approve','done',NULL,'1804170001','Welly WSP','2018-04-25 13:15:07','1804170001','Welly WSP','2018-04-25 13:47:37'),('15246388575982','20180425000000000001','13002',NULL,NULL,'approve','done','TES','1804170001','Welly WSP','2018-04-25 13:47:37','1804170001','Welly WSP','2018-04-25 16:18:24'),('15246479042911','20180425000000000001','13003',NULL,'15246388575982','approve','done','','1804170001','Welly WSP','2018-04-25 16:18:24','1804170001','Welly WSP','2018-04-25 16:19:06'),('15246481827897','20180425000000000002','13001',NULL,NULL,'approve','done',NULL,'1804170001','Welly WSP','2018-04-25 16:23:02','1804170001','Welly WSP','2018-04-25 16:29:11'),('15246485519555','20180425000000000002','13002',NULL,'15246481827897','approve','done','TES','1804170001','Welly WSP','2018-04-25 16:29:11','1804170001','Welly WSP','2018-04-25 16:30:04'),('15246486045438','20180425000000000002','13003',NULL,'15246485519555','approve','done','OKE','1804170001','Welly WSP','2018-04-25 16:30:04','1804170001','Welly WSP','2018-04-25 16:30:54'),('15246493776686','20180425000000000003','13001',NULL,NULL,'approve','done',NULL,'1804170001','Welly WSP','2018-04-25 16:42:57','1804170001','Welly WSP','2018-04-26 10:13:04'),('15246494955025','20180425000000000003','13002',NULL,'15246493776686','reject','done','','1804170001','Welly WSP','2018-04-25 16:44:55','1804170001','Welly WSP','2018-04-25 16:48:40'),('15246497204144','20180425000000000003','13001','13002',NULL,'approve','done',NULL,'1804170001','Welly WSP','2018-04-25 16:48:40','1804170001','Welly WSP','2018-04-26 10:13:04'),('15246498467577','20180425000000000004','13001',NULL,NULL,'approve','done',NULL,'1804170001','Welly WSP','2018-04-25 16:50:46','1804170001','Welly WSP','2018-04-25 16:52:47'),('15246499673265','20180425000000000004','13002',NULL,'15246498467577','approve','done','o','1804170001','Welly WSP','2018-04-25 16:52:47','1804170001','Welly WSP','2018-04-25 16:54:28'),('15246500687912','20180425000000000004','13003',NULL,'15246499673265','approve','done','oooo','1804170001','Welly WSP','2018-04-25 16:54:28','1804170001','Welly WSP','2018-04-25 16:55:29'),('15247053623925','20180501000000000005','13001',NULL,NULL,'approve','done',NULL,'1804170001','Welly WSP','2018-04-26 08:16:02','1804170001','Welly WSP','2018-04-26 10:17:31'),('15247057006605','20180426000000000006','13001',NULL,NULL,'approve','done',NULL,'1804170001','Welly WSP','2018-04-26 08:21:40','1804170001','Welly WSP','2018-04-26 08:41:32'),('15247061993597','20180426000000000007','13001',NULL,NULL,'approve','done',NULL,'1804170001','Welly WSP','2018-04-26 08:29:59','1804170001','Welly WSP','2018-04-26 10:09:40'),('1524706892581','20180426000000000006','13002',NULL,'15247057006605','approve','done','','1804170001','Welly WSP','2018-04-26 08:41:32','1804170001','Welly WSP','2018-04-26 08:57:54'),('15247078747396','20180426000000000006','13003',NULL,'1524706892581','waiting','process',NULL,'1804170001','Welly WSP','2018-04-26 08:57:54',NULL,NULL,NULL),('15247087810691','20180426000000000008','13001',NULL,NULL,'approve','done',NULL,'1804170001','Welly WSP','2018-04-26 09:13:01','1804170001','Welly WSP','2018-04-26 09:30:44'),('15247098449841','20180426000000000008','13002',NULL,'15247087810691','approve','done','','1804170001','Welly WSP','2018-04-26 09:30:44','1804170001','Welly WSP','2018-04-26 09:31:13'),('15247098739247','20180426000000000008','13003',NULL,'15247098449841','waiting','process',NULL,'1804170001','Welly WSP','2018-04-26 09:31:13',NULL,NULL,NULL),('1524712180171','20180426000000000007','13002',NULL,'15247061993597','reject','done','TEST REJECT DATA','1804170001','Welly WSP','2018-04-26 10:09:40','1804170001','Welly WSP','2018-04-26 10:23:39'),('1524712384412','20180425000000000003','13002',NULL,'15246493776686','approve','done','','1804170001','Welly WSP','2018-04-26 10:13:04','1804170001','Welly WSP','2018-04-26 10:13:55'),('15247124359492','20180425000000000003','13003',NULL,'15246494955025','approve','done','','1804170001','Welly WSP','2018-04-26 10:13:55','1804170001','Welly WSP','2018-04-26 10:14:15'),('15247126512092','20180501000000000005','13002',NULL,'15247053623925','waiting','process',NULL,'1804170001','Welly WSP','2018-04-26 10:17:31',NULL,NULL,NULL),('15247130194519','20180426000000000007','13001','13002',NULL,'waiting','process',NULL,'1804170001','Welly WSP','2018-04-26 10:23:39',NULL,NULL,NULL),('15247175674527','20180426000000000009','13001',NULL,NULL,'approve','done',NULL,'1804170001','Welly WSP','2018-04-26 11:39:27','1804170001','Welly WSP','2018-04-26 11:53:22'),('15247184026909','20180426000000000009','13002',NULL,'15247175674527','waiting','process',NULL,'1804170001','Welly WSP','2018-04-26 11:53:22',NULL,NULL,NULL),('15247187766022','20180517000000000010','13001',NULL,NULL,'approve','done',NULL,'1804170001','Welly WSP','2018-04-26 11:59:36','1804170001','Welly WSP','2018-04-26 12:00:19'),('15247188192211','20180517000000000010','13002',NULL,'15247187766022','approve','done','DADADA','1804170001','Welly WSP','2018-04-26 12:00:19','1804170001','Welly WSP','2018-04-26 12:14:54'),('15247196946286','20180517000000000010','13003',NULL,'15247188192211','waiting','process',NULL,'1804170001','Welly WSP','2018-04-26 12:14:54',NULL,NULL,NULL);

UNLOCK TABLES;

/*Table structure for table `surat_tugas` */

DROP TABLE IF EXISTS `surat_tugas`;

CREATE TABLE `surat_tugas` (
  `spt_id` varchar(20) NOT NULL,
  `user_id` varchar(10) DEFAULT NULL,
  `struktur_cd` varchar(10) DEFAULT NULL,
  `project_id` varchar(10) DEFAULT NULL,
  `kode_item` varchar(10) DEFAULT NULL,
  `tanggal_berangkat` date DEFAULT NULL,
  `tanggal_pulang` date DEFAULT NULL,
  `waktu_berangkat` time DEFAULT NULL,
  `waktu_pulang` time DEFAULT NULL,
  `lokasi_tujuan` varchar(100) DEFAULT NULL,
  `uraian_tugas` varchar(255) DEFAULT NULL,
  `spt_status` enum('draft','waiting','approved','rejected') DEFAULT NULL,
  `spt_send_by` varchar(10) DEFAULT NULL,
  `spt_send_by_name` varchar(50) DEFAULT NULL,
  `spt_send_date` datetime DEFAULT NULL,
  `mdb` varchar(10) DEFAULT NULL,
  `mdb_name` varchar(50) DEFAULT NULL,
  `mdd` datetime DEFAULT NULL,
  PRIMARY KEY (`spt_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `surat_tugas_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `pegawai` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `surat_tugas` */

LOCK TABLES `surat_tugas` WRITE;

insert  into `surat_tugas`(`spt_id`,`user_id`,`struktur_cd`,`project_id`,`kode_item`,`tanggal_berangkat`,`tanggal_pulang`,`waktu_berangkat`,`waktu_pulang`,`lokasi_tujuan`,`uraian_tugas`,`spt_status`,`spt_send_by`,`spt_send_by_name`,`spt_send_date`,`mdb`,`mdb_name`,`mdd`) values ('20180000000000000001','1804170001','001.01.00','133','1','2018-04-02','2018-04-06','18:00:00','08:00:00','Jakarta','Testing','approved',NULL,NULL,NULL,'1804170001','Welly WSP','2018-04-24 09:06:40'),('20180000000000000002','1804170007','001.01.00','65','1','2018-04-02','2018-04-06','18:00:00','08:00:00','Jakarta','Testing','waiting',NULL,NULL,NULL,'1804170001','Welly WSP','2018-04-19 11:02:01'),('20180000000000000003','1804170009','001.01.00','26','1','2018-05-01','2018-05-04','07:00:00','18:50:00','ENTAH','TESTING','waiting','1804170001','Welly WSP','2018-04-25 15:54:55','1804170001','Welly WSP','2018-04-20 09:04:39'),('20180000000000000004','1804170008','001.01.00','11','1','2018-05-01','2018-05-04','08:00:00','20:00:00','LOKASI','URAIAN','waiting',NULL,NULL,NULL,'1804170001','Welly WSP','2018-04-20 14:42:10'),('20180000000000000005','1804170001','001.01.00','180','','2018-05-01','2018-05-04','07:00:00','20:00:00','Jakarta','TESTING TERAKHIR SEMUA PROSES','approved','1804170001','Welly WSP','2018-04-24 13:52:55','1804170001','Welly WSP','2018-04-24 14:12:22');

UNLOCK TABLES;

/*Table structure for table `surat_tugas_advance` */

DROP TABLE IF EXISTS `surat_tugas_advance`;

CREATE TABLE `surat_tugas_advance` (
  `advance_id` varchar(20) NOT NULL,
  `spt_id` varchar(20) DEFAULT NULL,
  `jenis_id` varchar(5) DEFAULT NULL,
  `keterangan` varchar(255) DEFAULT NULL,
  `jumlah` double unsigned DEFAULT NULL,
  `kredit_status` enum('1','0') DEFAULT '0',
  `mdb` varchar(10) DEFAULT NULL,
  `mdb_name` varchar(50) DEFAULT NULL,
  `mdd` datetime DEFAULT NULL,
  PRIMARY KEY (`advance_id`),
  KEY `spt_id` (`spt_id`),
  KEY `jenis_id` (`jenis_id`),
  CONSTRAINT `surat_tugas_advance_ibfk_1` FOREIGN KEY (`spt_id`) REFERENCES `surat_tugas` (`spt_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `surat_tugas_advance_ibfk_2` FOREIGN KEY (`jenis_id`) REFERENCES `data_jenis_pengeluaran` (`jenis_id`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `surat_tugas_advance` */

LOCK TABLES `surat_tugas_advance` WRITE;

insert  into `surat_tugas_advance`(`advance_id`,`spt_id`,`jenis_id`,`keterangan`,`jumlah`,`kredit_status`,`mdb`,`mdb_name`,`mdd`) values ('20180000000000000001','20180000000000000002','00001','keterangan 1',100000,'1','1804170001','Welly WSP','2018-04-20 08:05:46'),('20180000000000000002','20180000000000000002','00002','keterangan 2',200000,'1','1804170001','Welly WSP','2018-04-20 08:06:08'),('20180000000000000003','20180000000000000004','00001','keterangan',100000,'1','1804170001','Welly WSP','2018-04-20 10:24:30'),('20180000000000000004','20180000000000000004','00002','keterangan 2',300000,'1',NULL,NULL,NULL),('20180000000000000005','20180000000000000001','00001','KETERANGAN',100000,'1','1804170001','Welly WSP','2018-04-20 15:49:59'),('20180000000000000006','20180000000000000005','00001','makan',200000,'1','1804170001','Welly WSP','2018-04-24 13:57:22'),('20180000000000000007','20180000000000000005','00002','cadangan',500000,'1','1804170001','Welly WSP','2018-04-24 13:59:24');

UNLOCK TABLES;

/*Table structure for table `surat_tugas_lpj` */

DROP TABLE IF EXISTS `surat_tugas_lpj`;

CREATE TABLE `surat_tugas_lpj` (
  `lpj_id` varchar(20) NOT NULL,
  `spt_id` varchar(20) DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `jenis_id` varchar(10) DEFAULT NULL,
  `keterangan` varchar(255) DEFAULT NULL,
  `debit` double unsigned DEFAULT NULL,
  `kredit` double unsigned DEFAULT NULL,
  `mdb` varchar(10) DEFAULT NULL,
  `mdb_name` varchar(50) DEFAULT NULL,
  `mdd` datetime DEFAULT NULL,
  PRIMARY KEY (`lpj_id`),
  KEY `spt_id` (`spt_id`),
  KEY `jenis_id` (`jenis_id`),
  CONSTRAINT `surat_tugas_lpj_ibfk_1` FOREIGN KEY (`spt_id`) REFERENCES `surat_tugas` (`spt_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `surat_tugas_lpj_ibfk_2` FOREIGN KEY (`jenis_id`) REFERENCES `data_jenis_pengeluaran` (`jenis_id`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `surat_tugas_lpj` */

LOCK TABLES `surat_tugas_lpj` WRITE;

insert  into `surat_tugas_lpj`(`lpj_id`,`spt_id`,`tanggal`,`jenis_id`,`keterangan`,`debit`,`kredit`,`mdb`,`mdb_name`,`mdd`) values ('20180000000000000001','20180000000000000001','2018-04-01','00002','1',100000,0,'1804170001','Welly WSP','2018-04-23 14:26:46'),('20180000000000000002','20180000000000000001','2018-04-03','00002','2',50000,0,'1804170001','Welly WSP','2018-04-23 14:26:55'),('20180000000000000003','20180000000000000001','2018-04-03','00002','3',50000,0,'1804170001','Welly WSP','2018-04-23 14:27:03'),('20180000000000000004','20180000000000000005','2018-05-01','00002','taksi',130000,0,'1804170001','Welly WSP','2018-04-24 14:06:27'),('20180000000000000005','20180000000000000005','2018-05-01','00001','makan',200000,0,'1804170001','Welly WSP','2018-04-24 14:06:53'),('20180000000000000006','20180000000000000005','2018-05-04','00002','tol',35000,0,'1804170001','Welly WSP','2018-04-24 14:07:24'),('20180000000000000007','20180000000000000005','2018-05-04','00002','taksi',130000,0,'1804170001','Welly WSP','2018-04-24 14:08:01'),('20180000000000000008','20180000000000000005','2018-05-04','00002','uang saku',365000,0,'1804170001','Welly WSP','2018-04-24 14:09:56');

UNLOCK TABLES;

/*Table structure for table `surat_tugas_process` */

DROP TABLE IF EXISTS `surat_tugas_process`;

CREATE TABLE `surat_tugas_process` (
  `process_id` varchar(20) NOT NULL,
  `spt_id` varchar(20) DEFAULT NULL,
  `flow_id` varchar(5) DEFAULT NULL,
  `flow_revisi_id` varchar(5) DEFAULT NULL,
  `process_references_id` varchar(20) DEFAULT NULL,
  `process_st` enum('waiting','approve','reject') DEFAULT 'waiting',
  `action_st` enum('process','done') DEFAULT 'process',
  `catatan` text,
  `mdb` varchar(10) DEFAULT NULL,
  `mdb_name` varchar(50) DEFAULT NULL,
  `mdd` datetime DEFAULT NULL,
  `mdb_finish` varchar(10) DEFAULT NULL,
  `mdb_finish_name` varchar(50) DEFAULT NULL,
  `mdd_finish` datetime DEFAULT NULL,
  PRIMARY KEY (`process_id`),
  KEY `spt_id` (`spt_id`),
  CONSTRAINT `surat_tugas_process_ibfk_1` FOREIGN KEY (`spt_id`) REFERENCES `surat_tugas` (`spt_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `surat_tugas_process` */

LOCK TABLES `surat_tugas_process` WRITE;

insert  into `surat_tugas_process`(`process_id`,`spt_id`,`flow_id`,`flow_revisi_id`,`process_references_id`,`process_st`,`action_st`,`catatan`,`mdb`,`mdb_name`,`mdd`,`mdb_finish`,`mdb_finish_name`,`mdd_finish`) values ('20180000000000000001','20180000000000000001','14001',NULL,NULL,'approve','done',NULL,'1804170001','Welly WSP','2018-04-19 10:10:42','1804170001','Welly WSP','2018-04-19 10:04:15'),('20180000000000000002','20180000000000000001','14002',NULL,NULL,'reject','done',NULL,'1804170001','Welly WSP','2018-04-19 10:13:15','1804170001','Welly WSP','2018-04-19 11:04:29'),('20180000000000000003','20180000000000000002','14001',NULL,NULL,'approve','done',NULL,'1804170001','Welly WSP','2018-04-19 11:01:04','1804170001','Welly WSP','2018-04-19 11:04:01'),('20180000000000000004','20180000000000000002','14002',NULL,NULL,'approve','done',NULL,'1804170001','Welly WSP','2018-04-19 11:02:01','1804170001','Welly WSP','2018-04-19 11:04:00'),('20180000000000000005','20180000000000000001','14001','14002','20180000000000000002','approve','done',NULL,'1804170001','Welly WSP','2018-04-19 11:24:29','1804170001','Welly WSP','2018-04-20 09:04:28'),('20180000000000000006','20180000000000000002','14003',NULL,NULL,'approve','done',NULL,'1804170001','Welly WSP','2018-04-19 11:25:00','1804170001','Welly WSP','2018-04-24 09:04:35'),('20180000000000000007','20180000000000000003','14001',NULL,NULL,'approve','done',NULL,'1804170001','Welly WSP','2018-04-19 11:46:59','1804170001','Welly WSP','2018-04-19 11:04:50'),('20180000000000000008','20180000000000000003','14002',NULL,NULL,'reject','done',NULL,'1804170001','Welly WSP','2018-04-19 11:49:50','1804170001','Welly WSP','2018-04-19 11:04:34'),('20180000000000000009','20180000000000000003','14001','14002','20180000000000000008','approve','done',NULL,'1804170001','Welly WSP','2018-04-19 11:52:34','1804170001','Welly WSP','2018-04-25 15:04:55'),('20180000000000000012','20180000000000000001','14002',NULL,NULL,'approve','done',NULL,'1804170001','Welly WSP','2018-04-20 09:23:28','1804170001','Welly WSP','2018-04-20 15:04:43'),('20180000000000000013','20180000000000000004','14001',NULL,NULL,'approve','done',NULL,'1804170001','Welly WSP','2018-04-20 10:22:27','1804170001','Welly WSP','2018-04-20 10:04:30'),('20180000000000000014','20180000000000000004','14002',NULL,'20180000000000000013','approve','done',NULL,'1804170001','Welly WSP','2018-04-20 10:22:30','1804170001','Welly WSP','2018-04-20 10:04:30'),('20180000000000000015','20180000000000000004','14003',NULL,'20180000000000000014','approve','done',NULL,'1804170001','Welly WSP','2018-04-20 10:23:30','1804170001','Welly WSP','2018-04-20 10:04:37'),('20180000000000000016','20180000000000000004','14004',NULL,'20180000000000000015','approve','done',NULL,'1804170001','Welly WSP','2018-04-20 10:24:37','1804170001','Welly WSP','2018-04-20 11:04:36'),('20180000000000000017','20180000000000000004','14005',NULL,'20180000000000000016','approve','done',NULL,'1804170001','Welly WSP','2018-04-20 11:36:36','1804170001','Welly WSP','2018-04-24 09:04:50'),('20180000000000000018','20180000000000000001','14003',NULL,'20180000000000000012','approve','done',NULL,'1804170001','Welly WSP','2018-04-20 15:49:43','1804170001','Welly WSP','2018-04-20 15:04:03'),('20180000000000000019','20180000000000000001','14004',NULL,'20180000000000000018','approve','done',NULL,'1804170001','Welly WSP','2018-04-20 15:50:03','1804170001','Welly WSP','2018-04-20 15:04:15'),('20180000000000000020','20180000000000000001','14005',NULL,'20180000000000000019','approve','done',NULL,'1804170001','Welly WSP','2018-04-20 15:50:15','1804170001','Welly WSP','2018-04-20 15:04:23'),('20180000000000000021','20180000000000000001','14006',NULL,'20180000000000000020','approve','done',NULL,'1804170001','Welly WSP','2018-04-20 15:50:23','1804170001','Welly WSP','2018-04-23 13:04:08'),('20180000000000000022','20180000000000000001','14007',NULL,'20180000000000000021','approve','done',NULL,'1804170001','Welly WSP','2018-04-23 13:38:08','1804170001','Welly WSP','2018-04-23 15:04:34'),('20180000000000000023','20180000000000000001','14008',NULL,'20180000000000000022','approve','done',NULL,'1804170001','Welly WSP','2018-04-23 15:46:34','1804170001','Welly WSP','2018-04-24 08:04:42'),('20180000000000000024','20180000000000000001','14009',NULL,'20180000000000000023','approve','done',NULL,'1804170001','Welly WSP','2018-04-24 08:50:42','1804170001','Welly WSP','2018-04-24 09:04:40'),('20180000000000000025','20180000000000000002','14004',NULL,'20180000000000000006','waiting','process',NULL,'1804170001','Welly WSP','2018-04-24 09:13:35',NULL,NULL,NULL),('20180000000000000026','20180000000000000004','14006',NULL,'20180000000000000017','waiting','process',NULL,'1804170001','Welly WSP','2018-04-24 09:15:50',NULL,NULL,NULL),('20180000000000000027','20180000000000000005','14001',NULL,NULL,'approve','done',NULL,'1804170001','Welly WSP','2018-04-24 13:51:24','1804170001','Welly WSP','2018-04-24 13:04:55'),('20180000000000000028','20180000000000000005','14002',NULL,'20180000000000000027','approve','done',NULL,'1804170001','Welly WSP','2018-04-24 13:52:55','1804170001','Welly WSP','2018-04-24 13:04:48'),('20180000000000000029','20180000000000000005','14003',NULL,'20180000000000000028','approve','done',NULL,'1804170001','Welly WSP','2018-04-24 13:53:48','1804170001','Welly WSP','2018-04-24 14:04:28'),('20180000000000000030','20180000000000000005','14004',NULL,'20180000000000000029','approve','done',NULL,'1804170001','Welly WSP','2018-04-24 14:03:28','1804170001','Welly WSP','2018-04-24 14:04:46'),('20180000000000000031','20180000000000000005','14005',NULL,'20180000000000000030','approve','done',NULL,'1804170001','Welly WSP','2018-04-24 14:04:46','1804170001','Welly WSP','2018-04-24 14:04:18'),('20180000000000000032','20180000000000000005','14006',NULL,'20180000000000000031','approve','done',NULL,'1804170001','Welly WSP','2018-04-24 14:05:18','1804170001','Welly WSP','2018-04-24 14:04:51'),('20180000000000000033','20180000000000000005','14007',NULL,'20180000000000000032','approve','done',NULL,'1804170001','Welly WSP','2018-04-24 14:08:51','1804170001','Welly WSP','2018-04-24 14:04:39'),('20180000000000000034','20180000000000000005','14008',NULL,'20180000000000000033','approve','done',NULL,'1804170001','Welly WSP','2018-04-24 14:10:39','1804170001','Welly WSP','2018-04-24 14:04:16'),('20180000000000000035','20180000000000000005','14009',NULL,'20180000000000000034','approve','done',NULL,'1804170001','Welly WSP','2018-04-24 14:11:16','1804170001','Welly WSP','2018-04-24 14:04:22'),('20180000000000000036','20180000000000000003','14002',NULL,'20180000000000000009','waiting','process',NULL,'1804170001','Welly WSP','2018-04-25 15:54:55',NULL,NULL,NULL);

UNLOCK TABLES;

/*Table structure for table `surat_tugas_tanggal` */

DROP TABLE IF EXISTS `surat_tugas_tanggal`;

CREATE TABLE `surat_tugas_tanggal` (
  `spt_id` varchar(20) NOT NULL,
  `tanggal` date NOT NULL,
  `mdb` varchar(10) DEFAULT NULL,
  `mdb_name` varchar(50) DEFAULT NULL,
  `mdd` datetime DEFAULT NULL,
  PRIMARY KEY (`spt_id`,`tanggal`),
  CONSTRAINT `surat_tugas_tanggal_ibfk_1` FOREIGN KEY (`spt_id`) REFERENCES `surat_tugas` (`spt_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `surat_tugas_tanggal` */

LOCK TABLES `surat_tugas_tanggal` WRITE;

insert  into `surat_tugas_tanggal`(`spt_id`,`tanggal`,`mdb`,`mdb_name`,`mdd`) values ('20180000000000000001','2018-04-02','1804170001','Welly WSP','2018-04-19 10:10:42'),('20180000000000000001','2018-04-03','1804170001','Welly WSP','2018-04-19 10:10:42'),('20180000000000000001','2018-04-04','1804170001','Welly WSP','2018-04-19 10:10:42'),('20180000000000000001','2018-04-05','1804170001','Welly WSP','2018-04-19 10:10:42'),('20180000000000000001','2018-04-06','1804170001','Welly WSP','2018-04-19 10:10:42'),('20180000000000000002','2018-04-02','1804170001','Welly WSP','2018-04-19 11:01:03'),('20180000000000000002','2018-04-03','1804170001','Welly WSP','2018-04-19 11:01:03'),('20180000000000000002','2018-04-04','1804170001','Welly WSP','2018-04-19 11:01:03'),('20180000000000000002','2018-04-05','1804170001','Welly WSP','2018-04-19 11:01:03'),('20180000000000000002','2018-04-06','1804170001','Welly WSP','2018-04-19 11:01:04'),('20180000000000000003','2018-05-01','1804170001','Welly WSP','2018-04-19 11:46:58'),('20180000000000000003','2018-05-02','1804170001','Welly WSP','2018-04-19 11:46:58'),('20180000000000000003','2018-05-03','1804170001','Welly WSP','2018-04-19 11:46:58'),('20180000000000000003','2018-05-04','1804170001','Welly WSP','2018-04-19 11:46:58'),('20180000000000000004','2018-05-01','1804170001','Welly WSP','2018-04-20 10:22:26'),('20180000000000000004','2018-05-02','1804170001','Welly WSP','2018-04-20 10:22:27'),('20180000000000000004','2018-05-03','1804170001','Welly WSP','2018-04-20 10:22:27'),('20180000000000000004','2018-05-04','1804170001','Welly WSP','2018-04-20 10:22:27'),('20180000000000000005','2018-05-01','1804170001','Welly WSP','2018-04-24 13:51:24'),('20180000000000000005','2018-05-02','1804170001','Welly WSP','2018-04-24 13:51:24'),('20180000000000000005','2018-05-03','1804170001','Welly WSP','2018-04-24 13:51:24'),('20180000000000000005','2018-05-04','1804170001','Welly WSP','2018-04-24 13:51:24');

UNLOCK TABLES;

/*Table structure for table `task_flow` */

DROP TABLE IF EXISTS `task_flow`;

CREATE TABLE `task_flow` (
  `flow_id` varchar(5) NOT NULL,
  `group_id` varchar(2) DEFAULT NULL,
  `role_id` varchar(30) DEFAULT NULL,
  `role_name` varchar(50) DEFAULT NULL,
  `task_name` varchar(100) DEFAULT NULL,
  `task_desc` varchar(255) DEFAULT NULL,
  `task_link` varchar(50) DEFAULT NULL,
  `task_number` int(11) unsigned DEFAULT NULL,
  `task_label_approve` varchar(50) DEFAULT NULL,
  `task_label_reject` varchar(50) DEFAULT NULL,
  `task_label_waiting` varchar(50) DEFAULT NULL,
  `mdb` varchar(10) DEFAULT NULL,
  `mdb_name` varchar(50) DEFAULT NULL,
  `mdd` datetime DEFAULT NULL,
  PRIMARY KEY (`flow_id`),
  KEY `group_id` (`group_id`),
  CONSTRAINT `task_flow_ibfk_1` FOREIGN KEY (`group_id`) REFERENCES `task_group` (`group_id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `task_flow` */

LOCK TABLES `task_flow` WRITE;

insert  into `task_flow`(`flow_id`,`group_id`,`role_id`,`role_name`,`task_name`,`task_desc`,`task_link`,`task_number`,`task_label_approve`,`task_label_reject`,`task_label_waiting`,`mdb`,`mdb_name`,`mdd`) values ('11001','11',NULL,'Department Officer','Pengajuan Cuti','Pengajuan Cuti',NULL,1,'-','-','Sedang Diproses',NULL,NULL,NULL),('11002','11',NULL,'Department Leader','Persetujuan Cuti Pimpinan','Persetujuan Cuti Pimpinan',NULL,2,'Disetujui','Ditolak','Sedang Diproses',NULL,NULL,NULL),('11003','11',NULL,'HRD','Persetujuan Cuti HRD','Persetujuan Cuti HRD',NULL,3,'Disetujui','Ditolak','Sedang Diproses',NULL,NULL,NULL),('12001','12',NULL,'Department Officer','Pengajuan Ijin','Pengajuan Ijin',NULL,1,'-','-','Sedang Diproses',NULL,NULL,NULL),('12002','12',NULL,'Department Leader','Persetujuan Ijin Pimpinan','Persetujuan Ijin Pimpinan',NULL,2,'Disetujui','Ditolak','Sedang Diproses',NULL,NULL,NULL),('12003','12',NULL,'HRD','Persetujuan Ijin HRD','Persetujuan Ijin HRD',NULL,3,'Disetujui','Ditolak','Sedang Diproses',NULL,NULL,NULL),('13001','13',NULL,'Department Officer','Pengajuan Lembur','Pengajuan Lembur',NULL,1,'-','-','Sedang Diproses',NULL,NULL,NULL),('13002','13',NULL,'Department Leader','Persetujuan Lembur Pimpinan','Persetujuan Lembur Pimpinan',NULL,2,'Disetujui','Ditolak','Sedang Diproses',NULL,NULL,NULL),('13003','13',NULL,'HRD','Persetujuan Lembur HRD','Persetujuan Lembur HRD',NULL,3,'Disetujui','Ditolak','Sedang Diproses',NULL,NULL,NULL),('14001','14',NULL,'Department Officer','Pengajuan Jaldin','Pengajuan Jaldin',NULL,1,'-','-','Sedang Diproses',NULL,NULL,NULL),('14002','14',NULL,'Department Leader','Persetujuan Jaldin Pimpinan','Persetujuan Jaldin Pimpinan',NULL,2,'Disetujui','Ditolak','Sedang Diproses',NULL,NULL,NULL),('14003','14',NULL,'HRD','Advance Perjalanan Dinas','Advance Perjalanan Dinas',NULL,3,'Sudah Dibuatkan','-','Sedang Diproses',NULL,NULL,NULL),('14004','14',NULL,'Finance Manager','Verifikasi Advance','Verifikasi Advance',NULL,4,'Sudah Diverifikasi','Ditolak / Revisi','Sedang Diproses',NULL,NULL,NULL),('14005','14',NULL,'Finance Staff','Pengambilan Advance','Pengambilan Advance',NULL,5,'Sudah Diambil','-','Sedang Diproses',NULL,NULL,NULL),('14006','14',NULL,'Karyawan','LPJ Perjalanan Dinas','LPJ Perjalanan Dinas',NULL,6,'-','-','Sedang Diproses',NULL,NULL,NULL),('14007','14',NULL,'HRD','Verifikasi LPJ - HRD','Verifikasi LPJ - HRD',NULL,7,'Sudah Diverifikasi','Ditolak / Revisi','Sedang Diproses',NULL,NULL,NULL),('14008','14',NULL,'Finance Manager','Verifikasi LPJ - Keuangan','Verifikasi LPJ - Keuangan',NULL,8,'Sudah Diverifikasi','Ditolak / Revisi','Sedang Diproses',NULL,NULL,NULL),('14009','14',NULL,'Finance Staff','Sisa / Kekurangan Jaldin','Sisa / Kekurangan Jaldin',NULL,9,'Lengkap','-','Sedang Diproses',NULL,NULL,NULL),('15001','15',NULL,'Department Officer','Pengajuan RKA Manajemen','Pengajuan RKA Manajemen',NULL,1,'-','-','Sedang Diproses',NULL,NULL,NULL),('15002','15',NULL,'Department Leader','Persetujuan Pimpinan','Persetujuan Pimpinan',NULL,2,'Disetujui','Ditolak','Sedang Diproses',NULL,NULL,NULL),('15003','15',NULL,'Finance Manager','Persetujuan Keuangan','Persetujuan Keuangan',NULL,3,'Disetujui','Ditolak','Sedang Diproses',NULL,NULL,NULL),('15004','15',NULL,'Dirut','Persetujuan Dirut','Persetujuan Dirut',NULL,4,'Disetujui','Ditolak','Sedang Diproses',NULL,NULL,NULL),('16001','16',NULL,'Department Officer','Pengajuan RKA Project','Pengajuan RKA Project',NULL,1,'-','-','Sedang Diproses',NULL,NULL,NULL),('16002','16',NULL,'Department Leader','Persetujuan Pimpinan','Persetujuan Pimpinan',NULL,2,'Disetujui','Ditolak','Sedang Diproses',NULL,NULL,NULL),('16003','16',NULL,'Finance Manager','Persetujuan Keuangan','Persetujuan Keuangan',NULL,3,'Disetujui','Ditolak','Sedang Diproses',NULL,NULL,NULL),('16004','16',NULL,'Dirut','Persetujuan Dirut','Persetujuan Dirut',NULL,4,'Disetujui','Ditolak','Sedang Diproses',NULL,NULL,NULL),('21001','21',NULL,'Department Officer','Pengajuan Advance Umum','Pengajuan Advance Umum',NULL,1,'-','-','Sedang Diproses',NULL,NULL,NULL),('21002','21',NULL,'Department Leader','Persetujuan Pimpinan','Persetujuan Pimpinan',NULL,2,'Disetujui','Ditolak','Sedang Diproses',NULL,NULL,NULL),('21003','21',NULL,'Finance Manager','Persetujuan Keuangan','Persetujuan Keuangan',NULL,3,'Disetujui','Ditolak','Sedang Diproses',NULL,NULL,NULL),('21004','21',NULL,'Dirut','Persetujuan Dirut','Persetujuan Dirut',NULL,4,'Disetujui','Ditolak','Sedang Diproses',NULL,NULL,NULL),('21005','21',NULL,'Finance Staff','Pencairan Dana','Pencairan Dana',NULL,5,'Sudah Dicairkan','-','Sedang Diproses',NULL,NULL,NULL),('21006','21',NULL,'Department Office','LPJ Advance Umum','LPJ Advance Umum',NULL,6,'-','-','Sedang Diproses',NULL,NULL,NULL),('21007','21',NULL,'Finance Staff','Sisa Advance Umum','Sisa Advance Umum',NULL,7,'Lengkap','-','Sedang Diproses',NULL,NULL,NULL),('22001','22',NULL,'Department Officer','Pengajuan Advance Perjalanan Dinas','Pengajuan Advance Perjalanan Dinas',NULL,1,'-','-','Sedang Diproses',NULL,NULL,NULL),('22002','22',NULL,'HRD','Verifikasi HRD','Verifikasi HRD',NULL,2,'Sudah Diverifikasi','Ditolak / Revisi','Sedang Diproses',NULL,NULL,NULL),('22003','22',NULL,'Finance Manager','Persetujuan Keuangan','Persetujuan Keuangan',NULL,3,'Disetujui','Ditolak','Sedang Diproses',NULL,NULL,NULL),('22004','22',NULL,'Dirut','Persetujuan Dirut','Persetujuan Dirut',NULL,4,'Disetujui','Ditolak','Sedang Diproses',NULL,NULL,NULL),('22005','22',NULL,'Finance Staff','Pencairan Dana','Pencairan Dana',NULL,5,'Sudah Dicairkan','-','Sedang Diproses',NULL,NULL,NULL),('22006','22',NULL,'Department Officer','LPJ Advance Jaldin','LPJ Advance Jaldin',NULL,6,'-','-','Sedang Diproses',NULL,NULL,NULL),('22007','22',NULL,'Finance Staff','Sisa Advance Jaldin','Sisa Advance Jaldin',NULL,7,'Lengkap','-','Sedang Diproses',NULL,NULL,NULL),('23001','23',NULL,'Department Officer','Pengajuan Permintaan Barang','Pengajuan Permintaan Barang',NULL,1,'-','-','Sedang Diproses',NULL,NULL,NULL),('23002','23',NULL,'Department Leader','Persetujuan Pimpinan','Persetujuan Pimpinan',NULL,2,'Disetujui','Ditolak','Sedang Diproses',NULL,NULL,NULL),('23003','23',NULL,'Finance Manager','Persetujuan Keuangan','Persetujuan Keuangan',NULL,3,'Disetujui','Ditolak','Sedang Diproses',NULL,NULL,NULL),('23004','23',NULL,'GA','Verifikasi GA','Verifikasi GA',NULL,4,'Sudah Diverifikasi','Ditolak / Revisi','Sedang Diproses',NULL,NULL,NULL),('23005','23',NULL,'Finance Staff','Pencairan Dana','Pencairan Dana',NULL,5,'Sudah Dicairkan','Ditolak / Revisi','Sedang Diproses',NULL,NULL,NULL),('23006','23',NULL,'Department Officer','LPJ Permintaan Barang','LPJ Permintaan Barang',NULL,6,'-','-','Sedang Diproses',NULL,NULL,NULL),('23007','23',NULL,'Finance Staff','Sisa Pengeluaran Dana','Sisa Pengeluaran Dana',NULL,7,'Lengkap','-','Sedang Diproses',NULL,NULL,NULL),('24001','24',NULL,'Department Officer','Pengajuan Permintaan Barang','Pengajuan Permintaan Barang',NULL,1,'-','-','Sedang Diproses',NULL,NULL,NULL),('24002','24',NULL,'Department Leader','Persetujuan Pimpinan','Persetujuan Pimpinan',NULL,2,'Disetujui','Ditolak','Sedang Diproses',NULL,NULL,NULL),('24003','24',NULL,'Finance Manager','Persetujuan Keuangan','Persetujuan Keuangan',NULL,3,'Disetujui','Ditolak','Sedang Diproses',NULL,NULL,NULL),('24004','24',NULL,'Dirut','Persetujuan Dirut','Persetujuan Dirut',NULL,4,'Disetujui','Ditolak','Sedang Diproses',NULL,NULL,NULL),('24005','24',NULL,'GA','Verifikasi GA','Verifikasi GA',NULL,5,'Sudah Diverifikasi','Ditolak / Revisi','Sedang Diproses',NULL,NULL,NULL),('24006','24',NULL,'Finance Staff','Pencairan Dana','Pencairan Dana',NULL,6,'Sudah Dicairkan','Ditolak / Revisi','Sedang Diproses',NULL,NULL,NULL),('24007','24',NULL,'Department Officer','LPJ Permintaan Barang','LPJ Permintaan Barang',NULL,7,'-','-','Sedang Diproses',NULL,NULL,NULL),('24008','24',NULL,'Finance Staff','Sisa Pengeluaran Dana','Sisa Pengeluaran Dana',NULL,8,'Lengkap','-','Sedang Diproses',NULL,NULL,NULL);

UNLOCK TABLES;

/*Table structure for table `task_group` */

DROP TABLE IF EXISTS `task_group`;

CREATE TABLE `task_group` (
  `group_id` varchar(2) NOT NULL,
  `group_type` varchar(50) DEFAULT NULL,
  `group_name` varchar(50) DEFAULT NULL,
  `group_desc` varchar(255) DEFAULT NULL,
  `mdb` varchar(10) DEFAULT NULL,
  `mdb_name` varchar(50) DEFAULT NULL,
  `mdd` datetime DEFAULT NULL,
  PRIMARY KEY (`group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `task_group` */

LOCK TABLES `task_group` WRITE;

insert  into `task_group`(`group_id`,`group_type`,`group_name`,`group_desc`,`mdb`,`mdb_name`,`mdd`) values ('11','HR','Pengajuan Cuti',NULL,NULL,NULL,NULL),('12','HR','Pengajuan Ijin',NULL,NULL,NULL,NULL),('13','HR','Pengajuan Lembur',NULL,NULL,NULL,NULL),('14','HR','Pengajuan Perjalanan Dinas',NULL,NULL,NULL,NULL),('15','FN','RKA Manajemen',NULL,NULL,NULL,NULL),('16','FN','RKA Project',NULL,NULL,NULL,NULL),('21','FN','Advance Umum',NULL,NULL,NULL,NULL),('22','FN','Advance Perjalanan Dinas',NULL,NULL,NULL,NULL),('23','FN','Permintaan Barang <= 1jt',NULL,NULL,NULL,NULL),('24','FN','Permintaan Barang > 1jt',NULL,NULL,NULL,NULL);

UNLOCK TABLES;

/*Table structure for table `trx_advance` */

DROP TABLE IF EXISTS `trx_advance`;

CREATE TABLE `trx_advance` (
  `trx_id` varchar(15) NOT NULL,
  `group_id` varchar(2) DEFAULT NULL,
  `kode_item` varchar(10) DEFAULT NULL,
  `ref_id` varchar(20) DEFAULT NULL COMMENT 'ref surat tugas',
  `struktur_cd` varchar(10) DEFAULT NULL,
  `advance_no` int(11) DEFAULT NULL,
  `advance_tanggal` date DEFAULT NULL,
  `advance_bulan` varchar(2) DEFAULT NULL,
  `advance_tahun` year(4) DEFAULT NULL,
  `advance_uraian` varchar(255) DEFAULT NULL,
  `advance_total_requested` double unsigned DEFAULT NULL,
  `advance_total_approved` double unsigned DEFAULT NULL,
  `advance_status` enum('draft','waiting','approved','rejected') DEFAULT 'draft',
  `send_by` varchar(10) DEFAULT NULL,
  `send_by_name` varchar(50) DEFAULT NULL,
  `send_date` datetime DEFAULT NULL,
  `mdb` varchar(10) DEFAULT NULL,
  `mdb_name` varchar(50) DEFAULT NULL,
  `mdd` datetime DEFAULT NULL,
  PRIMARY KEY (`trx_id`),
  KEY `group_id` (`group_id`),
  KEY `trx_advance_ibfk_2` (`kode_item`),
  CONSTRAINT `trx_advance_ibfk_1` FOREIGN KEY (`group_id`) REFERENCES `task_group` (`group_id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `trx_advance` */

LOCK TABLES `trx_advance` WRITE;

insert  into `trx_advance`(`trx_id`,`group_id`,`kode_item`,`ref_id`,`struktur_cd`,`advance_no`,`advance_tanggal`,`advance_bulan`,`advance_tahun`,`advance_uraian`,`advance_total_requested`,`advance_total_approved`,`advance_status`,`send_by`,`send_by_name`,`send_date`,`mdb`,`mdb_name`,`mdd`) values ('180710000000001','21','2018000001',NULL,'001.00.00',1,'2018-07-10','07',2018,'advance umum test 1',1000000,800000,'draft',NULL,NULL,NULL,NULL,NULL,NULL);

UNLOCK TABLES;

/*Table structure for table `trx_advance_lpj` */

DROP TABLE IF EXISTS `trx_advance_lpj`;

CREATE TABLE `trx_advance_lpj` (
  `lpj_id` varchar(20) NOT NULL,
  `trx_id` varchar(15) DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `uraian` varchar(255) DEFAULT NULL,
  `debit` double unsigned DEFAULT NULL,
  `kredit` double unsigned DEFAULT NULL,
  `mdb` varchar(10) DEFAULT NULL,
  `mdb_name` varchar(50) DEFAULT NULL,
  `mdd` datetime DEFAULT NULL,
  PRIMARY KEY (`lpj_id`),
  KEY `trx_id` (`trx_id`),
  CONSTRAINT `trx_advance_lpj_ibfk_1` FOREIGN KEY (`trx_id`) REFERENCES `trx_advance` (`trx_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `trx_advance_lpj` */

LOCK TABLES `trx_advance_lpj` WRITE;

UNLOCK TABLES;

/*Table structure for table `trx_advance_pembelian` */

DROP TABLE IF EXISTS `trx_advance_pembelian`;

CREATE TABLE `trx_advance_pembelian` (
  `data_id` varchar(20) NOT NULL,
  `trx_id` varchar(15) DEFAULT NULL,
  `item_uraian` varchar(255) DEFAULT NULL,
  `item_jumlah` int(11) unsigned DEFAULT NULL,
  `item_satuan` varchar(50) DEFAULT NULL,
  `item_total` double unsigned DEFAULT NULL,
  `mdb` varchar(10) DEFAULT NULL,
  `mdb_name` varchar(50) DEFAULT NULL,
  `mdd` datetime DEFAULT NULL,
  PRIMARY KEY (`data_id`),
  KEY `trx_id` (`trx_id`),
  CONSTRAINT `trx_advance_pembelian_ibfk_1` FOREIGN KEY (`trx_id`) REFERENCES `trx_advance` (`trx_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `trx_advance_pembelian` */

LOCK TABLES `trx_advance_pembelian` WRITE;

UNLOCK TABLES;

/*Table structure for table `trx_advance_process` */

DROP TABLE IF EXISTS `trx_advance_process`;

CREATE TABLE `trx_advance_process` (
  `process_id` varchar(20) NOT NULL,
  `trx_id` varchar(15) DEFAULT NULL,
  `flow_id` varchar(5) DEFAULT NULL,
  `flow_revisi_id` varchar(5) DEFAULT NULL,
  `process_references_id` varchar(20) DEFAULT NULL,
  `process_st` enum('waiting','approve','reject') DEFAULT 'waiting',
  `action_st` enum('process','done') DEFAULT 'process',
  `catatan` text,
  `mdb` varchar(10) DEFAULT NULL,
  `mdb_name` varchar(50) DEFAULT NULL,
  `mdd` datetime DEFAULT NULL,
  `mdb_finish` varchar(10) DEFAULT NULL,
  `mdb_finish_name` varchar(50) DEFAULT NULL,
  `mdd_finish` datetime DEFAULT NULL,
  PRIMARY KEY (`process_id`),
  KEY `overtime_id` (`trx_id`),
  CONSTRAINT `trx_advance_process_ibfk_1` FOREIGN KEY (`trx_id`) REFERENCES `trx_advance` (`trx_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `trx_advance_process` */

LOCK TABLES `trx_advance_process` WRITE;

UNLOCK TABLES;

/*Table structure for table `trx_advance_rincian` */

DROP TABLE IF EXISTS `trx_advance_rincian`;

CREATE TABLE `trx_advance_rincian` (
  `data_id` varchar(20) NOT NULL,
  `trx_id` varchar(15) DEFAULT NULL,
  `item_uraian` varchar(255) DEFAULT NULL,
  `item_jumlah` int(11) unsigned DEFAULT NULL,
  `item_total` double unsigned DEFAULT NULL,
  `item_keterangan` text,
  `mdb` varchar(10) DEFAULT NULL,
  `mdb_name` varchar(50) DEFAULT NULL,
  `mdd` datetime DEFAULT NULL,
  PRIMARY KEY (`data_id`),
  KEY `trx_id` (`trx_id`),
  CONSTRAINT `trx_advance_rincian_ibfk_1` FOREIGN KEY (`trx_id`) REFERENCES `trx_advance` (`trx_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `trx_advance_rincian` */

LOCK TABLES `trx_advance_rincian` WRITE;

UNLOCK TABLES;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;