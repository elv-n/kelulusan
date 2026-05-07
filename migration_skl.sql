-- ============================================
-- Migration: Fitur SKL (Surat Keterangan Lulus)
-- Tanggal: 2026-05-04
-- ============================================

-- 1. Tambah kolom identitas ke data_siswa
ALTER TABLE `data_siswa` 
ADD COLUMN `nis` VARCHAR(20) AFTER `nisn`,
ADD COLUMN `konsentrasi_keahlian` VARCHAR(100) AFTER `program_keahlian`,
ADD COLUMN `nomor_ijazah` VARCHAR(50) DEFAULT '-' AFTER `nama_ibu`,
ADD COLUMN `nomor_skl` VARCHAR(100) DEFAULT NULL AFTER `nomor_ijazah`;

-- 2. Buat tabel pengaturan SKL (Kop, Tanda Tangan, dll)
CREATE TABLE IF NOT EXISTS `skl_settings` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `nama_sekolah` VARCHAR(255) NOT NULL,
  `npsn` VARCHAR(20) NOT NULL,
  `kurikulum` VARCHAR(100) NOT NULL,
  `alamat_sekolah` TEXT,
  `kota_kabupaten` VARCHAR(100) NOT NULL,
  `provinsi` VARCHAR(100) NOT NULL,
  `kepala_sekolah` VARCHAR(255) NOT NULL,
  `nip_kepala_sekolah` VARCHAR(50) NOT NULL,
  `tanggal_skl` DATE NOT NULL,
  `tahun_ajaran` VARCHAR(20) NOT NULL,
  `kop_surat` VARCHAR(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- 3. Buat tabel daftar mata pelajaran SKL
CREATE TABLE IF NOT EXISTS `skl_mapel` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `nama_mapel` VARCHAR(255) NOT NULL,
  `kategori` ENUM('Umum', 'Kejuruan', 'Muatan Lokal') NOT NULL,
  `urutan` INT DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- 4. Buat tabel nilai siswa (FK ke data_siswa via nisn)
CREATE TABLE IF NOT EXISTS `skl_nilai` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `nisn` VARCHAR(20) NOT NULL,
  `mapel_id` INT NOT NULL,
  `nilai` DECIMAL(5,2) NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`nisn`) REFERENCES `data_siswa`(`nisn`) ON DELETE CASCADE,
  FOREIGN KEY (`mapel_id`) REFERENCES `skl_mapel`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- 5. Data default mata pelajaran (Berdasarkan lampiran)
INSERT INTO `skl_mapel` (`nama_mapel`, `kategori`, `urutan`) VALUES
('Pendidikan Agama dan Budi Pekerti', 'Umum', 1),
('Pendidikan Pancasila', 'Umum', 2),
('Bahasa Indonesia', 'Umum', 3),
('Pendidikan Jasmani, Olahraga dan Kesehatan', 'Umum', 4),
('Sejarah', 'Umum', 5),
('Seni dan Budaya', 'Umum', 6),
('Matematika', 'Kejuruan', 7),
('Bahasa Inggris', 'Kejuruan', 8),
('Informatika', 'Kejuruan', 9),
('Projek Ilmu Pengetahuan Alam dan Sosial', 'Kejuruan', 10),
('Dasar-dasar Program Keahlian', 'Kejuruan', 11),
('Konsentrasi Keahlian', 'Kejuruan', 12),
('Projek Kreativitas, Inovasi dan Kewirausahaan', 'Kejuruan', 13),
('Praktik Kerja Lapangan', 'Kejuruan', 14),
('Mata Pelajaran Pilihan', 'Kejuruan', 15),
('Bahasa Jawa', 'Muatan Lokal', 16);

-- 6. Inisialisasi pengaturan default
INSERT INTO `skl_settings` (`nama_sekolah`, `npsn`, `kurikulum`, `alamat_sekolah`, `kota_kabupaten`, `provinsi`, `kepala_sekolah`, `nip_kepala_sekolah`, `tanggal_skl`, `tahun_ajaran`) VALUES
('SMK NEGERI 1 WADA', '12345678', 'Kurikulum Merdeka', 'Jl. Raya No. 1', 'Wonosobo', 'Jawa Tengah', 'Nama Kepala Sekolah', '198001012000011001', '2026-05-04', '2025/2026');
