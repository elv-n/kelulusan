-- 1. Buat tabel nilai per semester
CREATE TABLE IF NOT EXISTS `skl_nilai_semester` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `nisn` VARCHAR(20) NOT NULL,
  `mapel_id` INT NOT NULL,
  `s1` DECIMAL(5,2) DEFAULT NULL,
  `s2` DECIMAL(5,2) DEFAULT NULL,
  `s3` DECIMAL(5,2) DEFAULT NULL,
  `s4` DECIMAL(5,2) DEFAULT NULL,
  `s5` DECIMAL(5,2) DEFAULT NULL,
  `s6` DECIMAL(5,2) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_student_mapel` (`nisn`, `mapel_id`),
  FOREIGN KEY (`nisn`) REFERENCES `data_siswa`(`nisn`) ON DELETE CASCADE,
  FOREIGN KEY (`mapel_id`) REFERENCES `skl_mapel`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
