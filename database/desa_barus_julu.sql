create database desa_barus_julu;
use desa_barus_julu;

-- TABEL USERS
CREATE TABLE `users` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(50) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `nama_lengkap` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100) DEFAULT NULL,
  `no_hp` VARCHAR(15) DEFAULT NULL,
  `role` ENUM('admin','warga') NOT NULL DEFAULT 'warga',
  `status` ENUM('pending','aktif','nonaktif') NOT NULL DEFAULT 'pending',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `kartu_keluarga` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `no_kk` VARCHAR(16) NOT NULL UNIQUE,
  `kepala_keluarga` VARCHAR(100) NOT NULL,
  `alamat` TEXT NOT NULL,
  `rt` VARCHAR(5) NOT NULL,
  `rw` VARCHAR(5) NOT NULL,
  `dusun` VARCHAR(50) DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `penduduk` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `nik` VARCHAR(16) NOT NULL UNIQUE,
  `no_kk` VARCHAR(16) NOT NULL,
  `nama_lengkap` VARCHAR(100) NOT NULL,
  `jenis_kelamin` ENUM('L','P') NOT NULL,
  `tempat_lahir` VARCHAR(50) NOT NULL,
  `tanggal_lahir` DATE NOT NULL,
  `agama` VARCHAR(20) NOT NULL,
  `status_perkawinan` VARCHAR(30) NOT NULL,
  `pekerjaan` VARCHAR(50) NOT NULL,
  `alamat` TEXT NOT NULL,
  `rt` VARCHAR(5) NOT NULL,
  `rw` VARCHAR(5) NOT NULL,
  `hubungan_keluarga` VARCHAR(30) NOT NULL,
  `status_kependudukan` ENUM('aktif','pindah','meninggal') NOT NULL DEFAULT 'aktif',
  `user_id` INT(11) DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_penduduk_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `mutasi_masuk` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `penduduk_id` INT(11) NOT NULL,
  `jenis_mutasi` ENUM('pindah_datang','kelahiran') NOT NULL,
  `tanggal_mutasi` DATE NOT NULL,
  `asal_desa` VARCHAR(100) DEFAULT NULL,
  `keterangan` TEXT DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_mm_penduduk` FOREIGN KEY (`penduduk_id`) REFERENCES `penduduk`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `mutasi_keluar` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `penduduk_id` INT(11) NOT NULL,
  `jenis_mutasi` ENUM('pindah_keluar','kematian') NOT NULL,
  `tanggal_mutasi` DATE NOT NULL,
  `tujuan_desa` VARCHAR(100) DEFAULT NULL,
  `keterangan` TEXT DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_mk_penduduk` FOREIGN KEY (`penduduk_id`) REFERENCES `penduduk`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `jenis_surat` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `kode_surat` VARCHAR(20) NOT NULL UNIQUE,
  `nama_surat` VARCHAR(100) NOT NULL,
  `syarat` TEXT DEFAULT NULL,
  `aktif` TINYINT(1) DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `jenis_surat` (`kode_surat`, `nama_surat`, `syarat`) VALUES
('SKD', 'Surat Keterangan Domisili', 'Fotokopi KTP, Fotokopi KK, Surat Pengantar RT'),
('SKTM', 'Surat Keterangan Tidak Mampu', 'Fotokopi KTP, Fotokopi KK, Surat Pengantar RT'),
('SKU', 'Surat Keterangan Usaha', 'Fotokopi KTP, Fotokopi KK, Foto Tempat Usaha'),
('SKP', 'Surat Pengantar Pindah', 'Fotokopi KTP, Fotokopi KK, Surat Pengantar RT'),
('SKK', 'Surat Keterangan Kelahiran', 'Fotokopi KTP Orang Tua, Fotokopi KK, Surat Lahir'),
('SKKM', 'Surat Keterangan Kematian', 'Fotokopi KTP Almarhum, Fotokopi KK, Surat Kematian'),
('SPN', 'Surat Pengantar Nikah (N1-N4)', 'Fotokopi KTP, KK, Akta Lahir, Pas Foto'),
('SKBM', 'Surat Keterangan Belum Menikah', 'Fotokopi KTP, Fotokopi KK'),
('SKJD', 'Surat Keterangan Janda/Duda', 'Fotokopi KTP, KK, Akta Kematian/Cerai'),
('SKKH', 'Surat Keterangan Kehilangan', 'Fotokopi KTP, KK, Surat Polisi'),
('SPSKCK', 'Surat Pengantar SKCK', 'Fotokopi KTP, KK, Pas Foto 4x6'),
('SKPEN', 'Surat Keterangan Penghasilan', 'Fotokopi KTP, Fotokopi KK'),
('SKKT', 'Surat Keterangan Kepemilikan Tanah', 'Fotokopi KTP, KK, Sertifikat Tanah'),
('SIK', 'Surat Izin Keramaian', 'Fotokopi KTP, KK, Surat Permohonan'),
('SKU2', 'Surat Keterangan Umum', 'Fotokopi KTP, Fotokopi KK');

CREATE TABLE `pengajuan_surat` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `penduduk_id` INT(11) NOT NULL,
  `jenis_surat_id` INT(11) NOT NULL,
  `tanggal_ajuan` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `keperluan` TEXT NOT NULL,
  `status` ENUM('menunggu','diproses','disetujui','ditolak') NOT NULL DEFAULT 'menunggu',
  `catatan_admin` TEXT DEFAULT NULL,
  `tanggal_selesai` DATETIME DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_ps_penduduk` FOREIGN KEY (`penduduk_id`) REFERENCES `penduduk`(`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_ps_jenis` FOREIGN KEY (`jenis_surat_id`) REFERENCES `jenis_surat`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `lampiran_pengajuan` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `pengajuan_id` INT(11) NOT NULL,
  `nama_file` VARCHAR(255) NOT NULL,
  `path_file` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_lp_pengajuan` FOREIGN KEY (`pengajuan_id`) REFERENCES `pengajuan_surat`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `tanda_tangan` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `user_id` INT(11) NOT NULL,
  `file_ttd` VARCHAR(255) NOT NULL,
  `jabatan` VARCHAR(100) NOT NULL,
  `aktif` TINYINT(1) DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_ttd_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `surat_terbit` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `pengajuan_id` INT(11) NOT NULL,
  `nomor_surat` VARCHAR(50) NOT NULL UNIQUE,
  `file_pdf` VARCHAR(255) NOT NULL,
  `qr_code` VARCHAR(255) DEFAULT NULL,
  `hash_verifikasi` VARCHAR(255) NOT NULL UNIQUE,
  `tanggal_terbit` DATE NOT NULL,
  `ttd_id` INT(11) DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_st_pengajuan` FOREIGN KEY (`pengajuan_id`) REFERENCES `pengajuan_surat`(`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_st_ttd` FOREIGN KEY (`ttd_id`) REFERENCES `tanda_tangan`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `users` (`username`, `password`, `nama_lengkap`, `role`, `status`) VALUES
('admin', '$2y$10$e0MYzXyjpJS7Pd0RVvHwHe1HlW7kJ8W9yYqK5RZ8G7W3LhVqf3i', 'Administrator Desa', 'admin', 'aktif');