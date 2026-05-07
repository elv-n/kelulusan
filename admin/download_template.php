<?php
/**
 * Script untuk mengunduh template Excel Import Data Siswa & Nilai
 * Dinamis berdasarkan mapel yang ada di database.
 */

define('BASE_PATH', dirname(__DIR__));
require_once BASE_PATH . '/app/bootstrap.php';
require_once BASE_PATH . '/app/middleware/AuthMiddleware.php';

AuthMiddleware::check();

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;

global $conn;

// 1. Get Mapel List
$resMapel = $conn->query("SELECT * FROM skl_mapel ORDER BY urutan ASC");
$mapels = $resMapel->fetch_all(MYSQLI_ASSOC);

$spreadsheet = new Spreadsheet();

// ==========================================
// SHEET 1: Identitas & Nilai SKL
// ==========================================
$sheet1 = $spreadsheet->getActiveSheet();
$sheet1->setTitle('Sheet1');

// Fixed Headers
$headers1 = [
    'NISN', 'NIS', 'Nama', 'Tempat Lahir', 'Tanggal Lahir (YYYY-MM-DD)', 
    'Jenis Kelamin (L/P)', 'Program Keahlian', 'Konsentrasi Keahlian', 'Kelas', 
    'Nama Ibu', 'Nomor Ijazah', 'Nomor SKL', 'Status Kelulusan (LULUS/TIDAK LULUS/-)', 'Keterangan Status'
];

// Mapel Headers (SKL)
foreach ($mapels as $m) {
    $headers1[] = $m['nama_mapel'];
}

use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

// Write headers to Sheet 1
$colIndex = 1;
foreach ($headers1 as $header) {
    $colLetter = Coordinate::stringFromColumnIndex($colIndex);
    $sheet1->setCellValue($colLetter . '1', $header);
    $sheet1->getColumnDimension($colLetter)->setAutoSize(true);
    $colIndex++;
}

// Style Header Sheet 1
$highestColumn1 = $sheet1->getHighestColumn();
$sheet1->getStyle("A1:{$highestColumn1}1")->applyFromArray([
    'font' => ['bold' => true],
    'fill' => [
        'fillType' => Fill::FILL_SOLID,
        'startColor' => ['rgb' => 'DCE6F1']
    ]
]);


// ==========================================
// SHEET 2: Nilai Transkrip (S1-S6 + N_PSAJ)
// ==========================================
$sheet2 = $spreadsheet->createSheet();
$sheet2->setTitle('Sheet2');

$headers2 = ['NISN'];

// Mapel Headers (S1-S6 + N_PSAJ)
foreach ($mapels as $m) {
    $headers2[] = $m['nama_mapel'] . ' S1';
    $headers2[] = $m['nama_mapel'] . ' S2';
    $headers2[] = $m['nama_mapel'] . ' S3';
    $headers2[] = $m['nama_mapel'] . ' S4';
    $headers2[] = $m['nama_mapel'] . ' S5';
    $headers2[] = $m['nama_mapel'] . ' S6';
    $headers2[] = $m['nama_mapel'] . ' N_PSAJ';
}

// Write headers to Sheet 2
$colIndex = 1;
foreach ($headers2 as $header) {
    $colLetter = Coordinate::stringFromColumnIndex($colIndex);
    $sheet2->setCellValue($colLetter . '1', $header);
    $colIndex++;
}

// Set auto size only for NISN to avoid huge file, others fixed width
$sheet2->getColumnDimension('A')->setAutoSize(true);

// Style Header Sheet 2
$highestColumn2 = $sheet2->getHighestColumn();
$sheet2->getStyle("A1:{$highestColumn2}1")->applyFromArray([
    'font' => ['bold' => true],
    'fill' => [
        'fillType' => Fill::FILL_SOLID,
        'startColor' => ['rgb' => 'E2EFDA']
    ]
]);

// Set active sheet back to 1
$spreadsheet->setActiveSheetIndex(0);

// ==========================================
// Output File
// ==========================================
$filename = 'Template_Import_Kelulusan_v2.xlsx';

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="' . $filename . '"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit();
