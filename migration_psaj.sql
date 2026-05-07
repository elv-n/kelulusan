-- ============================================
-- Migration: Tambah kolom nilai_psaj ke skl_nilai
-- N_PSAJ (Nilai Penilaian Sumatif Akhir Jenjang) untuk Transkrip
-- Berbeda dengan nilai SKL yang tercetak di Surat Keterangan Lulus
-- ============================================

ALTER TABLE `skl_nilai`
ADD COLUMN `nilai_psaj` DECIMAL(5,2) DEFAULT NULL AFTER `nilai`;
