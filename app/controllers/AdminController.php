<?php
/**
 * AdminController
 * Menangani login, dashboard, import data, dan logout admin
 */

use PhpOffice\PhpSpreadsheet\IOFactory;

require_once BASE_PATH . '/app/middleware/AuthMiddleware.php';

class AdminController
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    /**
     * Halaman login admin
     */
    public function login()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $conn = $this->conn;
        $error = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username']);
            $password = trim($_POST['password']);

            if (!empty($username) && !empty($password)) {
                $stmt = $conn->prepare("SELECT id, username, password, nama_lengkap FROM data_login WHERE username = ?");
                $stmt->bind_param("s", $username);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows === 1) {
                    $user = $result->fetch_assoc();
                    if (password_verify($password, $user['password'])) {
                        $_SESSION['user_id'] = $user['id'];
                        $_SESSION['username'] = $user['username'];
                        $_SESSION['nama_lengkap'] = $user['nama_lengkap'];
                        header("Location: dashboard.php");
                        exit();
                    } else {
                        $error = "Password salah.";
                    }
                } else {
                    $error = "Username tidak ditemukan.";
                }
                $stmt->close();
            } else {
                $error = "Harap isi semua field.";
            }
        }

        $pageCSS = [];
        $pageJS = [asset_url('js/app.js')];

        include BASE_PATH . '/resources/layouts/header.php';
        include BASE_PATH . '/resources/admin/login.php';
        include BASE_PATH . '/resources/layouts/footer.php';
    }

    /**
     * Halaman dashboard admin
     */
    public function dashboard()
    {
        AuthMiddleware::check();

        $pageCSS = [];
        $pageJS = [asset_url('js/app.js'), asset_url('js/admin-settings.js')];

        include BASE_PATH . '/resources/layouts/header.php';
        include BASE_PATH . '/resources/admin/dashboard.php';
        include BASE_PATH . '/resources/layouts/footer.php';
    }

    /**
     * Halaman import data siswa
     */
    public function import()
    {
        AuthMiddleware::check();

        $conn = $this->conn;
        $notif = '';
        $importErrors = [];
        $previewData = null;

        if (isset($_POST['import'])) {
            $file = $_FILES['file_excel']['tmp_name'];

            if (empty($file)) {
                $notif = 'nofile';
            } else {
                $spreadsheet = IOFactory::load($file);
                $sheet = $spreadsheet->getActiveSheet()->toArray();
                $insSiswa = $conn->prepare("
                    INSERT INTO data_siswa (nisn, nis, nama, tempat_lahir, tanggal_lahir, jenis_kelamin, program_keahlian, konsentrasi_keahlian, kelas, nama_ibu, nomor_ijazah, nomor_skl, status_kelulusan, keterangan_status) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                    ON DUPLICATE KEY UPDATE 
                    nis=VALUES(nis), nama=VALUES(nama), tempat_lahir=VALUES(tempat_lahir), tanggal_lahir=VALUES(tanggal_lahir), jenis_kelamin=VALUES(jenis_kelamin), program_keahlian=VALUES(program_keahlian), konsentrasi_keahlian=VALUES(konsentrasi_keahlian), kelas=VALUES(kelas), nama_ibu=VALUES(nama_ibu), nomor_ijazah=VALUES(nomor_ijazah), nomor_skl=VALUES(nomor_skl), status_kelulusan=VALUES(status_kelulusan), keterangan_status=VALUES(keterangan_status)
                ");

                $checkNilai = $conn->prepare("SELECT id FROM skl_nilai WHERE nisn = ? AND mapel_id = ?");
                $updNilai = $conn->prepare("UPDATE skl_nilai SET nilai = ? WHERE nisn = ? AND mapel_id = ?");
                $insNilai = $conn->prepare("INSERT INTO skl_nilai (nisn, mapel_id, nilai) VALUES (?, ?, ?)");

                foreach ($sheet as $key => $row) {
                    if ($key == 0) continue; // skip header

                    $nisn = trim($row[0] ?? '');
                    if (empty($nisn)) {
                        $importErrors[] = "Baris " . ($key + 1) . ": NISN kosong, baris dilewati.";
                        continue;
                    }
                    if (!preg_match('/^\d{10}$/', $nisn)) {
                        $importErrors[] = "Baris " . ($key + 1) . ": NISN '$nisn' tidak valid (harus 10 digit), baris dilewati.";
                        continue;
                    }

                    $nis                  = trim($row[1] ?? '');
                    $nama                 = trim($row[2] ?? '');
                    $tempat_lahir         = trim($row[3] ?? '');
                    $tanggal_lahir_raw    = $row[4] ?? '';

                    if (is_numeric($tanggal_lahir_raw)) {
                        $unix_date = ($tanggal_lahir_raw - 25569) * 86400;
                        $tanggal_lahir = date("Y-m-d", $unix_date);
                    } else {
                        $tanggal_lahir = date("Y-m-d", strtotime($tanggal_lahir_raw));
                    }

                    $jenis_kelamin        = trim($row[5] ?? '');
                    $program_keahlian     = trim($row[6] ?? '');
                    $konsentrasi_keahlian = trim($row[7] ?? '');
                    $kelas                = trim($row[8] ?? '');
                    $nama_ibu             = trim($row[9] ?? '');
                    $nomor_ijazah         = trim($row[10] ?? '-');
                    $nomor_skl            = trim($row[11] ?? '');
                    $status_kelulusan     = trim($row[12] ?? '-');
                    $keterangan_status    = trim($row[13] ?? '');

                    if (!in_array($status_kelulusan, ['LULUS', 'TIDAK LULUS', '-'])) {
                        $status_kelulusan = '-';
                    }

                    try {
                        $insSiswa->bind_param("ssssssssssssss", $nisn, $nis, $nama, $tempat_lahir, $tanggal_lahir, $jenis_kelamin, $program_keahlian, $konsentrasi_keahlian, $kelas, $nama_ibu, $nomor_ijazah, $nomor_skl, $status_kelulusan, $keterangan_status);
                        if (!$insSiswa->execute()) {
                            $importErrors[] = "Baris " . ($key + 1) . " (NISN $nisn): Gagal menyimpan profil. " . $insSiswa->error;
                            continue;
                        }

                        // Import Nilai SKL
                        // Pre-fetch mapels outside the loop for performance if not already done
                        if (!isset($allMapels)) {
                            $resMapel = $conn->query("SELECT id FROM skl_mapel ORDER BY urutan ASC");
                            $allMapels = $resMapel->fetch_all(MYSQLI_ASSOC);
                        }

                        foreach ($allMapels as $mIdx => $m) {
                            $mapelId = $m['id'];
                            $colIndex = 14 + $mIdx; // Starting from column 15 (index 14) in Excel
                            $nilai = trim($row[$colIndex] ?? '');

                            if ($nilai !== '') {
                                $nilaiFloat = (float)str_replace(',', '.', $nilai);
                                
                                $checkNilai->bind_param("si", $nisn, $mapelId);
                                $checkNilai->execute();
                                $resCheck = $checkNilai->get_result();

                                if ($resCheck->num_rows > 0) {
                                    $updNilai->bind_param("dsi", $nilaiFloat, $nisn, $mapelId);
                                    if (!$updNilai->execute()) {
                                        $importErrors[] = "Baris " . ($key + 1) . " (NISN $nisn), Kolom " . ($colIndex + 1) . ": Gagal update nilai.";
                                    }
                                } else {
                                    $insNilai->bind_param("sid", $nisn, $mapelId, $nilaiFloat);
                                    if (!$insNilai->execute()) {
                                        $importErrors[] = "Baris " . ($key + 1) . " (NISN $nisn), Kolom " . ($colIndex + 1) . ": Gagal insert nilai.";
                                    }
                                }
                            }
                        }
                    } catch (Exception $e) {
                        $importErrors[] = "Baris " . ($key + 1) . " (NISN $nisn): " . $e->getMessage();
                    }
                }

                $insSiswa->close();
                $checkNilai->close();
                $updNilai->close();
                $insNilai->close();

                // ── Sheet 2: Import Nilai Transkrip (S1-S6 + N_PSAJ) — Opsional ──
                $sheetCount = $spreadsheet->getSheetCount();
                if ($sheetCount >= 2) {
                    $sheet2 = $spreadsheet->getSheet(1)->toArray();
                    
                    // Pre-fetch mapels if not already done
                    if (!isset($allMapels)) {
                        $resMapel = $conn->query("SELECT id FROM skl_mapel ORDER BY urutan ASC");
                        $allMapels = $resMapel->fetch_all(MYSQLI_ASSOC);
                    }
                    $mapelCount = count($allMapels);

                    $stmtSemester = $conn->prepare("
                        INSERT INTO skl_nilai_semester (nisn, mapel_id, s1, s2, s3, s4, s5, s6) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?) 
                        ON DUPLICATE KEY UPDATE s1=VALUES(s1), s2=VALUES(s2), s3=VALUES(s3), s4=VALUES(s4), s5=VALUES(s5), s6=VALUES(s6)
                    ");

                    // Prepared statement for N_PSAJ upsert into skl_nilai
                    $checkPsaj = $conn->prepare("SELECT id FROM skl_nilai WHERE nisn = ? AND mapel_id = ?");
                    $updPsaj = $conn->prepare("UPDATE skl_nilai SET nilai_psaj = ? WHERE nisn = ? AND mapel_id = ?");
                    $insPsaj = $conn->prepare("INSERT INTO skl_nilai (nisn, mapel_id, nilai_psaj) VALUES (?, ?, ?)");

                    foreach ($sheet2 as $key => $row) {
                        if ($key == 0) continue; // skip header

                        $nisn = trim($row[0] ?? '');
                        if (empty($nisn)) continue;

                        // Verify student exists
                        $checkSiswa = $conn->prepare("SELECT nisn FROM data_siswa WHERE nisn = ?");
                        $checkSiswa->bind_param("s", $nisn);
                        $checkSiswa->execute();
                        if ($checkSiswa->get_result()->num_rows === 0) {
                            $importErrors[] = "Sheet 2, Baris " . ($key + 1) . ": NISN '$nisn' tidak ditemukan di data siswa.";
                            $checkSiswa->close();
                            continue;
                        }
                        $checkSiswa->close();

                        // Each mapel has 7 columns (S1-S6 + N_PSAJ), starting from column index 1
                        for ($mIdx = 0; $mIdx < $mapelCount; $mIdx++) {
                            $mapelId = $allMapels[$mIdx]['id'];
                            $baseCol = 1 + ($mIdx * 7); // Column offset: NISN(0) + mapel*7

                            $s1 = isset($row[$baseCol]) && trim($row[$baseCol]) !== '' ? (float)str_replace(',', '.', trim($row[$baseCol])) : null;
                            $s2 = isset($row[$baseCol + 1]) && trim($row[$baseCol + 1]) !== '' ? (float)str_replace(',', '.', trim($row[$baseCol + 1])) : null;
                            $s3 = isset($row[$baseCol + 2]) && trim($row[$baseCol + 2]) !== '' ? (float)str_replace(',', '.', trim($row[$baseCol + 2])) : null;
                            $s4 = isset($row[$baseCol + 3]) && trim($row[$baseCol + 3]) !== '' ? (float)str_replace(',', '.', trim($row[$baseCol + 3])) : null;
                            $s5 = isset($row[$baseCol + 4]) && trim($row[$baseCol + 4]) !== '' ? (float)str_replace(',', '.', trim($row[$baseCol + 4])) : null;
                            $s6 = isset($row[$baseCol + 5]) && trim($row[$baseCol + 5]) !== '' ? (float)str_replace(',', '.', trim($row[$baseCol + 5])) : null;
                            $psaj = isset($row[$baseCol + 6]) && trim($row[$baseCol + 6]) !== '' ? (float)str_replace(',', '.', trim($row[$baseCol + 6])) : null;

                            // Import semester grades
                            if ($s1 !== null || $s2 !== null || $s3 !== null || $s4 !== null || $s5 !== null || $s6 !== null) {
                                try {
                                    $stmtSemester->bind_param("sidddddd", $nisn, $mapelId, $s1, $s2, $s3, $s4, $s5, $s6);
                                    if (!$stmtSemester->execute()) {
                                        $importErrors[] = "Sheet 2, Baris " . ($key + 1) . " (NISN $nisn), Mapel ID $mapelId: Gagal import semester.";
                                    }
                                } catch (Exception $e) {
                                    $importErrors[] = "Sheet 2, Baris " . ($key + 1) . " (NISN $nisn): " . $e->getMessage();
                                }
                            }

                            // Import N_PSAJ
                            if ($psaj !== null) {
                                try {
                                    $checkPsaj->bind_param("si", $nisn, $mapelId);
                                    $checkPsaj->execute();
                                    $resPsaj = $checkPsaj->get_result();

                                    if ($resPsaj->num_rows > 0) {
                                        $updPsaj->bind_param("dsi", $psaj, $nisn, $mapelId);
                                        $updPsaj->execute();
                                    } else {
                                        $insPsaj->bind_param("sid", $nisn, $mapelId, $psaj);
                                        $insPsaj->execute();
                                    }
                                } catch (Exception $e) {
                                    $importErrors[] = "Sheet 2, Baris " . ($key + 1) . " (NISN $nisn) N_PSAJ: " . $e->getMessage();
                                }
                            }
                        }
                    }
                    $stmtSemester->close();
                    $checkPsaj->close();
                    $updPsaj->close();
                    $insPsaj->close();
                }

                if (count($importErrors) > 0) {
                    $notif = 'partial';
                } else {
                    $notif = 'success';
                }
            }
        }

        // Preview
        if (isset($_POST['preview']) && !empty($_FILES['file_excel']['tmp_name'])) {
            $spreadsheet = IOFactory::load($_FILES['file_excel']['tmp_name']);
            $previewData = $spreadsheet->getActiveSheet()->toArray();
        }

        $pageCSS = [];
        $pageJS = [asset_url('js/app.js')];

        include BASE_PATH . '/resources/layouts/header.php';
        include BASE_PATH . '/resources/admin/import.php';
        include BASE_PATH . '/resources/layouts/footer.php';
    }

    /**
     * Halaman data siswa
     */
    public function siswa()
    {
        AuthMiddleware::check();

        $pageCSS = [];
        $pageJS = [asset_url('js/app.js'), asset_url('js/admin-siswa.js')];

        include BASE_PATH . '/resources/layouts/header.php';
        include BASE_PATH . '/resources/admin/siswa.php';
        include BASE_PATH . '/resources/layouts/footer.php';
    }

    /**
     * Halaman pengaturan SKL
     */
    public function sklSettings()
    {
        AuthMiddleware::check();
        $conn = $this->conn;
        $success = false;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nama_sekolah = $_POST['nama_sekolah'];
            $npsn = $_POST['npsn'];
            $kurikulum = $_POST['kurikulum'];
            $kota_kabupaten = $_POST['kota_kabupaten'];
            $provinsi = $_POST['provinsi'];
            $kepala_sekolah = $_POST['kepala_sekolah'];
            $nip_kepala_sekolah = $_POST['nip_kepala_sekolah'];
            $tanggal_skl = $_POST['tanggal_skl'];
            $tahun_ajaran = $_POST['tahun_ajaran'];

            $stmt = $conn->prepare("UPDATE skl_settings SET nama_sekolah=?, npsn=?, kurikulum=?, kota_kabupaten=?, provinsi=?, kepala_sekolah=?, nip_kepala_sekolah=?, tanggal_skl=?, tahun_ajaran=? WHERE id=1");
            $stmt->bind_param("sssssssss", $nama_sekolah, $npsn, $kurikulum, $kota_kabupaten, $provinsi, $kepala_sekolah, $nip_kepala_sekolah, $tanggal_skl, $tahun_ajaran);
            if ($stmt->execute()) {
                $success = true;
            }
            $stmt->close();
        }

        $res = $conn->query("SELECT * FROM skl_settings WHERE id=1");
        $settings = $res->fetch_assoc();

        $pageCSS = [];
        $pageJS = [asset_url('js/app.js')];

        include BASE_PATH . '/resources/layouts/header.php';
        include BASE_PATH . '/resources/admin/skl_settings.php';
        include BASE_PATH . '/resources/layouts/footer.php';
    }

    /**
     * Halaman manajemen mata pelajaran SKL
     */
    public function sklMapel()
    {
        AuthMiddleware::check();
        $conn = $this->conn;

        if (isset($_POST['add'])) {
            $nama = $_POST['nama_mapel'];
            $kategori = $_POST['kategori'];
            $urutan = $_POST['urutan'];
            $stmt = $conn->prepare("INSERT INTO skl_mapel (nama_mapel, kategori, urutan) VALUES (?, ?, ?)");
            $stmt->bind_param("ssi", $nama, $kategori, $urutan);
            $stmt->execute();
            $stmt->close();
        }

        if (isset($_POST['edit'])) {
            $id = $_POST['id'];
            $nama = $_POST['nama_mapel'];
            $kategori = $_POST['kategori'];
            $urutan = $_POST['urutan'];
            $stmt = $conn->prepare("UPDATE skl_mapel SET nama_mapel = ?, kategori = ?, urutan = ? WHERE id = ?");
            $stmt->bind_param("ssii", $nama, $kategori, $urutan, $id);
            $stmt->execute();
            $stmt->close();
        }

        if (isset($_POST['delete'])) {
            $id = $_POST['id'];
            $stmt = $conn->prepare("DELETE FROM skl_mapel WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $stmt->close();
        }

        $res = $conn->query("SELECT * FROM skl_mapel ORDER BY urutan ASC");
        $mapel = $res->fetch_all(MYSQLI_ASSOC);

        $pageCSS = [];
        $pageJS = [asset_url('js/app.js')];

        include BASE_PATH . '/resources/layouts/header.php';
        include BASE_PATH . '/resources/admin/skl_mapel.php';
        include BASE_PATH . '/resources/layouts/footer.php';
    }

    /**
     * Halaman input nilai SKL + Transkrip (gabungan)
     */
    public function sklNilai()
    {
        AuthMiddleware::check();
        $conn = $this->conn;
        $nisn = $_GET['nisn'] ?? '';

        if (empty($nisn)) {
            header("Location: siswa.php");
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // ── Simpan Nilai SKL + N_PSAJ ──
            if (isset($_POST['nilai'])) {
                foreach ($_POST['nilai'] as $mapel_id => $nilai) {
                    $psaj = isset($_POST['nilai_psaj'][$mapel_id]) && $_POST['nilai_psaj'][$mapel_id] !== '' ? $_POST['nilai_psaj'][$mapel_id] : null;
                    
                    $check = $conn->prepare("SELECT id FROM skl_nilai WHERE nisn = ? AND mapel_id = ?");
                    $check->bind_param("si", $nisn, $mapel_id);
                    $check->execute();
                    $res = $check->get_result();
                    
                    if ($res->num_rows > 0) {
                        $upd = $conn->prepare("UPDATE skl_nilai SET nilai = ?, nilai_psaj = ? WHERE nisn = ? AND mapel_id = ?");
                        $upd->bind_param("ddsi", $nilai, $psaj, $nisn, $mapel_id);
                        $upd->execute();
                        $upd->close();
                    } else {
                        $ins = $conn->prepare("INSERT INTO skl_nilai (nisn, mapel_id, nilai, nilai_psaj) VALUES (?, ?, ?, ?)");
                        $ins->bind_param("sidd", $nisn, $mapel_id, $nilai, $psaj);
                        $ins->execute();
                        $ins->close();
                    }
                    $check->close();
                }
            }

            // ── Simpan Nilai Semester (Transkrip) ──
            if (isset($_POST['semester'])) {
                foreach ($_POST['semester'] as $mapel_id => $sems) {
                    $s1 = ($sems['s1'] !== '') ? $sems['s1'] : null;
                    $s2 = ($sems['s2'] !== '') ? $sems['s2'] : null;
                    $s3 = ($sems['s3'] !== '') ? $sems['s3'] : null;
                    $s4 = ($sems['s4'] !== '') ? $sems['s4'] : null;
                    $s5 = ($sems['s5'] !== '') ? $sems['s5'] : null;
                    $s6 = ($sems['s6'] !== '') ? $sems['s6'] : null;

                    $stmt = $conn->prepare("INSERT INTO skl_nilai_semester (nisn, mapel_id, s1, s2, s3, s4, s5, s6) VALUES (?, ?, ?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE s1=VALUES(s1), s2=VALUES(s2), s3=VALUES(s3), s4=VALUES(s4), s5=VALUES(s5), s6=VALUES(s6)");
                    $stmt->bind_param("sidddddd", $nisn, $mapel_id, $s1, $s2, $s3, $s4, $s5, $s6);
                    $stmt->execute();
                    $stmt->close();
                }
            }

            // ── Update identitas tambahan ──
            $nis = $_POST['nis'];
            $konsentrasi = $_POST['konsentrasi_keahlian'];
            $nomor_skl = $_POST['nomor_skl'];
            $updSiswa = $conn->prepare("UPDATE data_siswa SET nis = ?, konsentrasi_keahlian = ?, nomor_skl = ? WHERE nisn = ?");
            $updSiswa->bind_param("ssss", $nis, $konsentrasi, $nomor_skl, $nisn);
            $updSiswa->execute();
            $updSiswa->close();

            header("Location: skl_nilai.php?nisn=$nisn&status=success");
            exit();
        }

        // ── Fetch data for form ──
        $resSiswa = $conn->prepare("SELECT * FROM data_siswa WHERE nisn = ?");
        $resSiswa->bind_param("s", $nisn);
        $resSiswa->execute();
        $siswa = $resSiswa->get_result()->fetch_assoc();

        $resMapel = $conn->query("SELECT * FROM skl_mapel ORDER BY urutan ASC");
        $mapels = $resMapel->fetch_all(MYSQLI_ASSOC);

        // Nilai SKL + N_PSAJ
        $resNilai = $conn->prepare("SELECT mapel_id, nilai, nilai_psaj FROM skl_nilai WHERE nisn = ?");
        $resNilai->bind_param("s", $nisn);
        $resNilai->execute();
        $nilaiRows = $resNilai->get_result()->fetch_all(MYSQLI_ASSOC);
        $nilaiMap = [];
        $psajMap = [];
        foreach ($nilaiRows as $row) {
            $nilaiMap[$row['mapel_id']] = $row['nilai'];
            $psajMap[$row['mapel_id']] = $row['nilai_psaj'];
        }

        // Nilai Semester (Transkrip)
        $resSemester = $conn->prepare("SELECT * FROM skl_nilai_semester WHERE nisn = ?");
        $resSemester->bind_param("s", $nisn);
        $resSemester->execute();
        $semRows = $resSemester->get_result()->fetch_all(MYSQLI_ASSOC);
        $semesterMap = [];
        foreach ($semRows as $row) {
            $semesterMap[$row['mapel_id']] = $row;
        }

        $pageCSS = [];
        $pageJS = [asset_url('js/app.js')];

        include BASE_PATH . '/resources/layouts/header.php';
        include BASE_PATH . '/resources/admin/skl_nilai.php';
        include BASE_PATH . '/resources/layouts/footer.php';
    }

    /**
     * Halaman cetak SKL (Filter per kelas)
     */
    public function sklCetak()
    {
        AuthMiddleware::check();
        $conn = $this->conn;
        
        $kelas = $_GET['kelas'] ?? '';

        // Get list of classes for filter
        $resKelas = $conn->query("SELECT DISTINCT kelas FROM data_siswa WHERE kelas != '' ORDER BY kelas ASC");
        $kelasList = $resKelas->fetch_all(MYSQLI_ASSOC);

        $siswa = [];
        if ($kelas) {
            $stmt = $conn->prepare("SELECT nisn, nama, kelas, status_kelulusan FROM data_siswa WHERE kelas = ? AND status_kelulusan = 'LULUS' ORDER BY nama ASC");
            $stmt->bind_param("s", $kelas);
            $stmt->execute();
            $siswa = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
            $stmt->close();
        }

        $pageCSS = [];
        $pageJS = [asset_url('js/app.js')];

        include BASE_PATH . '/resources/layouts/header.php';
        include BASE_PATH . '/resources/admin/skl_cetak.php';
        include BASE_PATH . '/resources/layouts/footer.php';
    }

    /**
     * Halaman cetak Transkrip (Filter per kelas)
     */
    public function cetakTranskrip()
    {
        AuthMiddleware::check();
        $conn = $this->conn;
        
        $kelas = $_GET['kelas'] ?? '';

        // Get list of classes for filter
        $resKelas = $conn->query("SELECT DISTINCT kelas FROM data_siswa WHERE kelas != '' ORDER BY kelas ASC");
        $kelasList = $resKelas->fetch_all(MYSQLI_ASSOC);

        $siswa = [];
        if ($kelas) {
            $stmt = $conn->prepare("SELECT nisn, nama, kelas, status_kelulusan FROM data_siswa WHERE kelas = ? AND status_kelulusan = 'LULUS' ORDER BY nama ASC");
            $stmt->bind_param("s", $kelas);
            $stmt->execute();
            $siswa = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
            $stmt->close();
        }

        $pageCSS = [];
        $pageJS = [asset_url('js/app.js')];

        include BASE_PATH . '/resources/layouts/header.php';
        include BASE_PATH . '/resources/admin/cetak_transkrip.php';
        include BASE_PATH . '/resources/layouts/footer.php';
    }

    /**
     * Proses logout
     */
    public function logout()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        session_unset();
        session_destroy();
        header('Location: ' . base_url('admin/login.php'));
        exit();
    }
}
