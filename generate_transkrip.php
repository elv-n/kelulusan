<?php

/**
 * Generate Transkrip Akademik
 * Render as printable HTML
 */

define('BASE_PATH', __DIR__);
require_once BASE_PATH . '/app/bootstrap.php';

$nisn = $_GET['nisn'] ?? '';
$kelas = $_GET['kelas'] ?? '';

if (empty($nisn) && empty($kelas)) {
    die("NISN atau Kelas tidak ditentukan.");
}

// Fetch Settings
$resSettings = $conn->query("SELECT * FROM skl_settings WHERE id=1");
$settings = $resSettings->fetch_assoc();

// Fetch Students
$siswaList = [];
if (!empty($nisn)) {
    $stmtSiswa = $conn->prepare("SELECT * FROM data_siswa WHERE nisn = ?");
    $stmtSiswa->bind_param("s", $nisn);
    $stmtSiswa->execute();
    $siswaList = $stmtSiswa->get_result()->fetch_all(MYSQLI_ASSOC);
} else if (!empty($kelas)) {
    $stmtSiswa = $conn->prepare("SELECT * FROM data_siswa WHERE kelas = ? AND status_kelulusan = 'LULUS' ORDER BY nama ASC");
    $stmtSiswa->bind_param("s", $kelas);
    $stmtSiswa->execute();
    $siswaList = $stmtSiswa->get_result()->fetch_all(MYSQLI_ASSOC);
}

if (empty($siswaList)) {
    die("Data siswa tidak ditemukan atau tidak ada siswa yang LULUS di kelas tersebut.");
}

// Prepare statement for grades
$stmtNilai = $conn->prepare("
    SELECT 
        m.nama_mapel, 
        m.kategori, 
        n.nilai_psaj,
        s.s1, s.s2, s.s3, s.s4, s.s5, s.s6
    FROM skl_mapel m 
    LEFT JOIN skl_nilai n ON m.id = n.mapel_id AND n.nisn = ?
    LEFT JOIN skl_nilai_semester s ON m.id = s.mapel_id AND s.nisn = ?
    ORDER BY m.urutan ASC
");

// formatTanggalIndo() and formatNilai() are loaded from app/helpers/functions.php via bootstrap
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transkrip - <?= empty($kelas) ? htmlspecialchars($siswaList[0]['nama']) : htmlspecialchars($kelas) ?></title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            line-height: 1.2;
            color: #000;
            margin: 0;
            padding: 0;
            background: #f0f0f0;
        }

        .page {
            width: 215mm;
            min-height: 330mm;
            padding: 15mm 15mm 10mm 15mm;
            margin: 10mm auto;
            background: white;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            box-sizing: border-box;
            position: relative;
        }

        @media print {
            body {
                background: none;
            }

            .page {
                margin: 0;
                box-shadow: none;
                width: 215mm;
                height: 330mm;
                padding: 10mm 15mm;
            }

            .no-print {
                display: none;
            }

            @page {
                size: 215mm 330mm;
                margin: 0;
            }

            .page-break {
                page-break-after: always;
            }
        }

        .kop-surat {
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 3.5px solid #000;
            padding-bottom: 2px;
            margin-bottom: 10px;
        }

        .kop-logo {
            width: 80px;
            height: auto;
        }

        .kop-text {
            text-align: center;
            flex-grow: 1;
        }

        .kop-text p {
            margin: 0;
            line-height: 1.1;
            font-weight: bold;
        }

        .kop-pemerintah {
            font-size: 11pt;
            font-weight: normal !important;
        }

        .kop-dinas {
            font-size: 11pt;
            font-weight: normal !important;
        }

        .kop-sekolah {
            font-size: 14pt;
        }

        .kop-alamat {
            font-size: 8pt;
            margin-top: 3px !important;
        }

        .title {
            text-align: center;
            margin: 10px 0;
        }

        .title h3 {
            margin: 0;
            font-size: 12pt;
            text-transform: uppercase;
        }

        .identity-container {
            font-size: 10pt;
            margin-bottom: 18px;
            margin-top: 10px;
        }

        .identity-table {
            width: 100%;
            border-collapse: collapse;
            white-space: nowrap;
        }

        .identity-table td {
            vertical-align: top;
            padding: 3px 0;
        }

        .identity-table td:first-child {
            width: 220px;
        }

        .identity-table td:nth-child(2) {
            width: 15px;
        }

        .grades-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 9.5pt;
        }

        .grades-table th,
        .grades-table td {
            border: 1px solid #000;
            padding: 5px 5px;
        }

        .grades-table th {
            background: #f2f2f2;
        }

        .cat-row td {
            font-weight: bold;
            background: #f9fafb;
            padding: 6px 5px !important;
        }

        .empty-cell {
            background-color: #d1d5db;
        }

        .footer {
            margin-top: 20px;
            display: flex;
            justify-content: flex-end;
        }

        .signature {
            text-align: left;
            width: 250px;
            font-size: 10pt;
        }

        .signature p {
            margin: 0;
            line-height: 1.5;
        }

        .sig-space {
            height: 80px;
        }

        .no-print-btn {
            position: fixed;
            bottom: 20px;
            right: 20px;
            padding: 10px 20px;
            background: #10b981;
            color: white;
            border: none;
            border-radius: 50px;
            font-weight: bold;
            cursor: pointer;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            z-index: 99;
        }
    </style>
</head>

<body>
    <button class="no-print no-print-btn" onclick="window.print()">Cetak Transkrip</button>

    <?php
    $totalSiswa = count($siswaList);
    foreach ($siswaList as $index => $siswa):
        // Fetch Grades per student
        $stmtNilai->bind_param("ss", $siswa['nisn'], $siswa['nisn']);
        $stmtNilai->execute();
        $gradesRows = $stmtNilai->get_result()->fetch_all(MYSQLI_ASSOC);

        $grades = ['Umum' => [], 'Kejuruan' => [], 'Muatan Lokal' => []];
        foreach ($gradesRows as $row) {
            $grades[$row['kategori']][] = $row;
        }
        $isLast = ($index === $totalSiswa - 1);
    ?>

        <div class="page <?= !$isLast ? 'page-break' : '' ?>">
            <!-- Header / Kop -->
            <div class="kop-surat">
                <img src="logo_jateng.png" class="kop-logo" alt="Logo Jateng">
                <div class="kop-text">
                    <p class="kop-pemerintah">PEMERINTAH PROVINSI JAWA TENGAH</p>
                    <p class="kop-sekolah">DINAS PENDIDIKAN</p>
                    <p class="kop-sekolah">SEKOLAH MENENGAH KEJURUAN </p>
                    <p class="kop-sekolah">NEGERI 1 WADASLINTANG</p>
                    <p class="kop-alamat">Jalan Somogede Kilometer 0,3 Trimulyo, Wadaslintang, Wonosobo, Jawa Tengah</p>
                    <p class="kop-alamat">Kode Pos 56365, Telepon 0286- 5802211 Faksimile 0286- 5802211</p>
                    <p class="kop-alamat">laman: https://smkn1wadaslintang.sch.id. Pos-el: smkn1.wadaslintang@yahoo.com</p>
                </div>
                <img src="logo_sekolah.png" class="kop-logo" alt="Logo Sekolah">
            </div>

            <div class="title">
                <h3>TRANSKRIP NILAI</h3>
            </div>

            <div class="identity-container">
                <br>
                <table class="identity-table">
                    <tr>
                        <td>Nama Siswa</td>
                        <td>:</td>
                        <td><b><?= htmlspecialchars($siswa['nama']) ?></b></td>
                    </tr>
                    <tr>
                        <td>NIS / NISN</td>
                        <td>:</td>
                        <td><?= htmlspecialchars($siswa['nis'] ?? '-') ?> / <?= htmlspecialchars($siswa['nisn']) ?></td>
                    </tr>
                    <tr>
                        <td>Tempat, Tanggal Lahir</td>
                        <td>:</td>
                        <td><?= htmlspecialchars($siswa['tempat_lahir']) ?>, <?= formatTanggalIndo($siswa['tanggal_lahir']) ?></td>
                    </tr>
                    <tr>
                        <td>Bidang Keahlian</td>
                        <td>:</td>
                        <td><?= htmlspecialchars(get_bidang_keahlian($siswa['program_keahlian'])) ?></td>
                    </tr>
                    <tr>
                        <td>Program Keahlian</td>
                        <td>:</td>
                        <td><?= htmlspecialchars($siswa['program_keahlian']) ?></td>
                    </tr>
                    <tr>
                        <td>Konsentrasi Keahlian</td>
                        <td>:</td>
                        <td><?= htmlspecialchars($siswa['konsentrasi_keahlian'] ?? '-') ?></td>
                    </tr>
                </table>
            </div>

            <table class="grades-table">
                <thead>
                    <tr>
                        <th rowspan="2" width="30">NO</th>
                        <th rowspan="2">MATA PELAJARAN</th>
                        <th colspan="6">SEMESTER</th>
                        <th rowspan="2" width="60">*) N_PSAJ</th>
                    </tr>
                    <tr>
                        <th width="30">I</th>
                        <th width="30">II</th>
                        <th width="30">III</th>
                        <th width="30">IV</th>
                        <th width="30">V</th>
                        <th width="30">VI</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    $catNames = [
                        'Umum' => 'Mata Pelajaran Umum',
                        'Kejuruan' => 'Mata Pelajaran Kejuruan',
                        'Muatan Lokal' => 'Muatan Lokal'
                    ];
                    foreach ($catNames as $katKey => $katTitle):
                        if (!empty($grades[$katKey])):
                    ?>
                            <tr class="cat-row">
                                <td colspan="9"><?= $katTitle ?></td>
                            </tr>
                            <?php foreach ($grades[$katKey] as $g): ?>
                                <tr>
                                    <td align="center"><?= $no++ ?></td>
                                    <td><?= htmlspecialchars($g['nama_mapel']) ?></td>
                                    <?php for ($i = 1; $i <= 6; $i++):
                                        $val = $g['s' . $i];
                                        $isEmpty = ($val === null || $val === '');
                                    ?>
                                        <td align="center" class="<?= $isEmpty ? 'empty-cell' : '' ?>">
                                            <?= formatNilai($val) ?>
                                        </td>
                                    <?php endfor;
                                    $valPsaj = $g['nilai_psaj'];
                                    $isPsajEmpty = ($valPsaj === null || $valPsaj === '');
                                    ?>
                                    <td align="center" class="<?= $isPsajEmpty ? 'empty-cell' : '' ?>"><?= formatNilai($valPsaj) ?></td>
                                </tr>
                            <?php endforeach; ?>
                    <?php
                        endif;
                    endforeach;
                    ?>
                </tbody>
            </table>

            <p style="font-size: 8pt; margin-top: 5px;">*) N_PSAJ (Nilai Penilaian Sumatif Akhir Jenjang)</p>

            <div class="footer">
                <div class="signature">
                    <p><?= htmlspecialchars($settings['kota_kabupaten']) ?>, <?= formatTanggalIndo($settings['tanggal_skl']) ?></p>
                    <p>Kepala <?= htmlspecialchars($settings['nama_sekolah']) ?></p>
                    <div class="sig-space"></div>
                    <p><strong><?= htmlspecialchars($settings['kepala_sekolah']) ?></strong></p>
                    <p>NIP <?= htmlspecialchars($settings['nip_kepala_sekolah']) ?></p>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</body>

</html>