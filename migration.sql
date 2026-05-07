-- ============================================
-- Migration: Optimasi Database smknwada_kelulusan
-- Tanggal: 2026-05-03
-- ============================================

-- ────────────────────────────────────────────
-- STEP 1: Backup dulu (opsional, jalankan manual)
-- ────────────────────────────────────────────
-- mysqldump -u root smknwada_kelulusan > backup_kelulusan.sql

-- ────────────────────────────────────────────
-- STEP 2: Hapus data sampah / bukan siswa
-- NISN siswa valid = 10 digit angka (contoh: 0042769556)
-- ────────────────────────────────────────────

-- Cek dulu berapa yang akan dihapus (opsional)
-- SELECT COUNT(*) AS total_sampah FROM data_siswa WHERE nisn NOT REGEXP '^[0-9]{10}$';

-- Hapus data sampah
DELETE FROM data_siswa WHERE nisn NOT REGEXP '^[0-9]{10}$';

-- ────────────────────────────────────────────
-- STEP 3: Ubah ENUM status_kelulusan
-- Dari: LULUS, -
-- Ke:   LULUS, TIDAK LULUS, -
-- ────────────────────────────────────────────
ALTER TABLE `data_siswa`
  MODIFY `status_kelulusan` enum('LULUS','TIDAK LULUS','-')
  CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci
  NOT NULL DEFAULT '-';

-- ────────────────────────────────────────────
-- STEP 4: Tambah kolom keterangan_status
-- Untuk menjelaskan alasan status '-' atau 'TIDAK LULUS'
-- ────────────────────────────────────────────
ALTER TABLE `data_siswa`
  ADD COLUMN `keterangan_status` text
  CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci
  DEFAULT NULL
  AFTER `status_kelulusan`;

-- ────────────────────────────────────────────
-- VERIFIKASI
-- ────────────────────────────────────────────
-- Cek struktur tabel
-- DESCRIBE data_siswa;

-- Cek jumlah data tersisa
-- SELECT COUNT(*) AS total_siswa FROM data_siswa;

-- Cek distribusi status
-- SELECT status_kelulusan, COUNT(*) AS jumlah FROM data_siswa GROUP BY status_kelulusan;
