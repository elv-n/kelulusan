<?php
/**
 * API — Siswa (Admin Only)
 * GET: Ambil daftar siswa (dengan search)
 * POST: Update status kelulusan
 */

define('BASE_PATH', dirname(__DIR__));
require_once BASE_PATH . '/app/config/app.php';
require_once BASE_PATH . '/app/helpers/functions.php';
require_once BASE_PATH . '/app/helpers/settings.php';
require_once BASE_PATH . '/app/config/database.php';

header('Content-Type: application/json');
header('Cache-Control: no-cache, no-store, must-revalidate');

// Auth check
session_start();
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'Unauthorized']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);

    // ── Hapus semua data ──
    if (isset($input['action']) && $input['action'] === 'delete_all') {
        if ($conn->query("TRUNCATE TABLE data_siswa")) {
            echo json_encode(['success' => true, 'message' => 'Semua data siswa berhasil dihapus']);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'error' => 'Gagal menghapus data: ' . $conn->error]);
        }
        exit;
    }

    // ── Update status kelulusan ──

    if (empty($input['nisn']) || !isset($input['status_kelulusan'])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'NISN dan status_kelulusan wajib diisi']);
        exit;
    }

    $nisn = $input['nisn'];
    $status = $input['status_kelulusan'];

    // Validasi status
    $allowedStatus = ['LULUS', 'TIDAK LULUS', '-'];
    if (!in_array($status, $allowedStatus)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'Status tidak valid. Pilih: LULUS, TIDAK LULUS, atau -']);
        exit;
    }

    $keterangan = isset($input['keterangan_status']) ? trim($input['keterangan_status']) : null;

    // Jika LULUS, kosongkan keterangan
    if ($status === 'LULUS') {
        $keterangan = null;
    }

    $stmt = $conn->prepare("UPDATE data_siswa SET status_kelulusan = ?, keterangan_status = ? WHERE nisn = ?");
    $stmt->bind_param("sss", $status, $keterangan, $nisn);

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0 || $stmt->affected_rows === 0) {
            // affected_rows bisa 0 jika value sama, tapi tetap sukses
            echo json_encode([
                'success' => true,
                'message' => 'Status kelulusan berhasil diperbarui',
                'nisn' => $nisn,
                'status_kelulusan' => $status,
                'keterangan_status' => $keterangan
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'error' => 'Data siswa dengan NISN tersebut tidak ditemukan'
            ]);
        }
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'error' => 'Gagal mengupdate data']);
    }

    $stmt->close();

} else {
    // ── GET: Ambil daftar siswa ──
    $search = isset($_GET['search']) ? trim($_GET['search']) : '';
    $kelas = isset($_GET['kelas']) ? trim($_GET['kelas']) : '';
    $status = isset($_GET['status']) ? trim($_GET['status']) : '';

    $sql = "SELECT nisn, nama, tempat_lahir, tanggal_lahir, jenis_kelamin, program_keahlian, kelas, nama_ibu, status_kelulusan, keterangan_status FROM data_siswa WHERE 1=1";
    $params = [];
    $types = '';

    if (!empty($search)) {
        $sql .= " AND (nama LIKE ? OR nisn LIKE ?)";
        $searchParam = "%$search%";
        $params[] = $searchParam;
        $params[] = $searchParam;
        $types .= 'ss';
    }

    if (!empty($kelas)) {
        $sql .= " AND kelas = ?";
        $params[] = $kelas;
        $types .= 's';
    }

    if (!empty($status)) {
        $sql .= " AND status_kelulusan = ?";
        $params[] = $status;
        $types .= 's';
    }

    $sql .= " ORDER BY kelas ASC, nama ASC";

    $stmt = $conn->prepare($sql);
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    $result = $stmt->get_result();

    $siswa = [];
    while ($row = $result->fetch_assoc()) {
        $siswa[] = $row;
    }

    // Ambil daftar kelas untuk filter
    $kelasResult = $conn->query("SELECT DISTINCT kelas FROM data_siswa ORDER BY kelas ASC");
    $kelasList = [];
    while ($row = $kelasResult->fetch_assoc()) {
        $kelasList[] = $row['kelas'];
    }

    // Hitung statistik
    $statsResult = $conn->query("SELECT 
        COUNT(*) as total,
        SUM(CASE WHEN status_kelulusan = 'LULUS' THEN 1 ELSE 0 END) as lulus,
        SUM(CASE WHEN status_kelulusan = 'TIDAK LULUS' THEN 1 ELSE 0 END) as tidak_lulus,
        SUM(CASE WHEN status_kelulusan = '-' THEN 1 ELSE 0 END) as belum
        FROM data_siswa");
    $stats = $statsResult->fetch_assoc();

    echo json_encode([
        'success' => true,
        'data' => $siswa,
        'kelas_list' => $kelasList,
        'stats' => $stats,
        'total' => count($siswa)
    ]);

    $stmt->close();
}

$conn->close();
