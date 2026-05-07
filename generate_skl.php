<?php

/**
 * Generate SKL - Surat Keterangan Lulus
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
    SELECT m.nama_mapel, m.kategori, n.nilai 
    FROM skl_mapel m 
    LEFT JOIN skl_nilai n ON m.id = n.mapel_id AND n.nisn = ?
    ORDER BY m.urutan ASC
");

// formatTanggalIndo() is loaded from app/helpers/functions.php via bootstrap
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SKL - <?= empty($kelas) ? htmlspecialchars($siswaList[0]['nama']) : htmlspecialchars($kelas) ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            line-height: 1.3;
            color: #000;
            margin: 0;
            padding: 0;
            background: #f0f0f0;
        }

        .page {
            width: 215mm;
            height: 330mm;
            padding: 15mm 15mm 10mm 15mm;
            /* Increased top padding */
            margin: 10mm auto;
            background: white;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            box-sizing: border-box;
            position: relative;
            overflow: hidden;
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
                padding: 15mm 15mm 10mm 15mm;
                /* Increased top padding */
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
            margin-bottom: 12px;
        }

        .kop-logo {
            width: 85px;
            height: auto;
        }

        .kop-text {
            text-align: center;
            flex-grow: 1;
            padding: 0 5px;
        }

        .kop-text p {
            margin: 0;
            line-height: 1.1;
            color: #000;
            white-space: nowrap;
        }

        .kop-pemerintah {
            font-size: 12pt;
            font-weight: 400;
        }

        .kop-dinas {
            font-size: 12pt;
            font-weight: 400;
        }

        .kop-sekolah {
            font-size: 15pt;
            font-weight: 700;
        }

        .kop-alamat {
            font-size: 9pt;
            font-weight: 700;
            margin-top: 3px !important;
        }

        .kop-kontak {
            font-size: 9pt;
            font-weight: 700;
        }

        .title {
            text-align: center;
            margin-bottom: 10px;
        }

        .title h3 {
            margin: 0;
            font-size: 13pt;
            text-transform: uppercase;
        }

        .title p {
            margin: 3px 0;
            font-size: 10pt;
        }

        .content {
            font-size: 9pt;
        }

        .content p {
            margin: 5px 0;
        }

        .info-table {
            width: 100%;
            margin-bottom: 8px;
        }

        .info-table td {
            vertical-align: top;
            padding: 0;
        }

        .info-table td:first-child {
            width: 220px;
        }

        .info-table td:nth-child(2) {
            width: 15px;
        }

        .grades-table {
            width: 100%;
            border-collapse: collapse;
            margin: 8px 0;
        }

        .grades-table th,
        .grades-table td {
            border: 1px solid #000;
            padding: 2px 5px;
        }

        .grades-table th {
            background: #f2f2f2;
            font-size: 9pt;
        }

        .grades-table td {
            font-size: 9pt;
        }

        .cat-row {
            font-weight: bold;
            background: #fafafa;
        }

        .footer {
            margin-top: 30px;
            /* Increased space before footer */
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            /* Align to top */
        }

        .photo-box {
            width: 20mm;
            /* Shrink photo box */
            height: 28mm;
            border: 2px solid #000;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 9pt;
            color: #000;
            margin-right: 60px;
            margin-top: 17px;
            /* Align with second line of signature */
        }

        .signature {
            text-align: left;
            width: 280px;
        }

        .signature p {
            margin: 0;
            font-size: 9pt;
        }

        /* Match content font size */
        .sig-space {
            height: 65px;
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
        }
    </style>
</head>

<body>
    <button class="no-print no-print-btn" onclick="window.print()">Cetak SKL</button>

    <?php
    $totalSiswa = count($siswaList);
    foreach ($siswaList as $index => $siswa):
        // Fetch Grades
        $stmtNilai->bind_param("s", $siswa['nisn']);
        $stmtNilai->execute();
        $gradesRows = $stmtNilai->get_result()->fetch_all(MYSQLI_ASSOC);

        $grades = ['Umum' => [], 'Kejuruan' => [], 'Muatan Lokal' => []];
        $totalNilai = 0;
        $countNilai = 0;
        foreach ($gradesRows as $row) {
            $grades[$row['kategori']][] = $row;
            if ($row['nilai'] !== null) {
                $totalNilai += $row['nilai'];
                $countNilai++;
            }
        }
        $rataRata = $countNilai > 0 ? $totalNilai / $countNilai : 0;
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
                <h3>SURAT KETERANGAN LULUS</h3>
                <p>Nomor: <?= htmlspecialchars($siswa['nomor_skl'] ?? '400.3.11.1/............') ?></p>
            </div>

            <div class="content">
                <p align="justify">Yang bertanda tangan di bawah ini, Kepala <?= htmlspecialchars($settings['nama_sekolah']) ?> <?= htmlspecialchars($settings['kota_kabupaten']) ?>, Provinsi Jawa Tengah menerangkan bahwa:</p>

                <table class="info-table">
                    <tr>
                        <td>Satuan Pendidikan</td>
                        <td>:</td>
                        <td><?= htmlspecialchars($settings['nama_sekolah']) ?></td>
                    </tr>
                    <tr>
                        <td>Nomor Pokok Satuan Pendidikan</td>
                        <td>:</td>
                        <td><?= htmlspecialchars($settings['npsn']) ?></td>
                    </tr>
                    <tr>
                        <td>Nama Lengkap</td>
                        <td>:</td>
                        <td><?= htmlspecialchars($siswa['nama']) ?></td>
                    </tr>
                    <tr>
                        <td>Tempat, Tanggal Lahir</td>
                        <td>:</td>
                        <td><?= htmlspecialchars($siswa['tempat_lahir']) ?>, <?= formatTanggalIndo($siswa['tanggal_lahir']) ?></td>
                    </tr>
                    <tr>
                        <td>Nomor Induk Siswa Nasional</td>
                        <td>:</td>
                        <td><?= htmlspecialchars($siswa['nisn']) ?></td>
                    </tr>
                    <tr>
                        <td>Nomor Ijazah</td>
                        <td>:</td>
                        <td><?= htmlspecialchars($siswa['nomor_ijazah'] ?? '-') ?></td>
                    </tr>
                    <tr>
                        <td>Tanggal Kelulusan</td>
                        <td>:</td>
                        <td><?= formatTanggalIndo($settings['tanggal_skl']) ?></td>
                    </tr>
                    <tr>
                        <td>Kurikulum</td>
                        <td>:</td>
                        <td><?= htmlspecialchars($settings['kurikulum']) ?></td>
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

                <p align="justify">Dinyatakan <b>LULUS</b> dari Satuan Pendidikan berdasarkan kriteria kelulusan <?= htmlspecialchars($settings['nama_sekolah']) ?> <?= htmlspecialchars($settings['kota_kabupaten']) ?> Tahun Ajaran <?= htmlspecialchars($settings['tahun_ajaran']) ?>, dengan nilai sebagai berikut :</p>

                <table class="grades-table">
                    <thead>
                        <tr>
                            <th width="40">No.</th>
                            <th>Mata Pelajaran</th>
                            <th width="100">Nilai</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        foreach (['Umum', 'Kejuruan', 'Muatan Lokal'] as $kat):
                            if (!empty($grades[$kat])):
                        ?>
                                <tr class="cat-row">
                                    <td colspan="3"><?= $kat === 'Muatan Lokal' ? '' : 'Mata Pelajaran ' ?><?= $kat ?></td>
                                </tr>
                                <?php foreach ($grades[$kat] as $g): ?>
                                    <tr>
                                        <td align="center" valign="top"><?= $no++ ?>.</td>
                                        <td>
                                            <?php if ($g['nama_mapel'] === 'Seni Rupa'): ?>
                                                Seni dan Budaya<br>
                                                <span>Seni Rupa</span>
                                            <?php else: ?>
                                                <?= htmlspecialchars($g['nama_mapel']) ?>
                                            <?php endif; ?>
                                        </td>
                                        <td align="center" valign="top">
                                            <?php if ($g['nama_mapel'] === 'Seni Rupa'): ?><br><?php endif; ?>
                                            <?= $g['nilai'] !== null ? number_format($g['nilai'], 2, ',', '.') : '-' ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                        <?php
                            endif;
                        endforeach;
                        ?>
                        <tr class="cat-row">
                            <td colspan="2" align="center">Rata-rata</td>
                            <td align="center"><?= number_format($rataRata, 2, ',', '.') ?></td>
                        </tr>
                    </tbody>
                </table>

                <p align="justify">Surat Keterangan Lulus ini berlaku sementara sampai dengan diterbitkannya Ijazah Tahun Ajaran <?= htmlspecialchars($settings['tahun_ajaran']) ?>, untuk menjadikan maklum bagi yang berkepentingan.</p>

                <div class="footer">
                    <div style="flex: 1;"></div> <!-- Empty space to push photo box to center-left -->
                    <div>
                        <div class="photo-box">Foto 3x4</div>
                    </div>
                    <div class="signature">
                        <p><?= htmlspecialchars($settings['kota_kabupaten']) ?>, <?= formatTanggalIndo($settings['tanggal_skl']) ?></p>
                        <p>Kepala <?= htmlspecialchars($settings['nama_sekolah']) ?></p>
                        <div class="sig-space"></div>
                        <p><strong><?= htmlspecialchars($settings['kepala_sekolah']) ?></strong></p>
                        <p>NIP <?= htmlspecialchars($settings['nip_kepala_sekolah']) ?></p>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</body>

</html>