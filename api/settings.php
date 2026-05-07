<?php
/**
 * API — Settings (Admin Only)
 * GET: Ambil pengaturan
 * POST: Update pengaturan (waktu_pengumuman, tahun_ajaran)
 */

define('BASE_PATH', dirname(__DIR__));
require_once BASE_PATH . '/app/config/app.php';
require_once BASE_PATH . '/app/helpers/functions.php';
require_once BASE_PATH . '/app/helpers/settings.php';

header('Content-Type: application/json');
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');

// Auth check
session_start();
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'Unauthorized']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Update settings
    $input = json_decode(file_get_contents('php://input'), true);

    if (empty($input['waktu_pengumuman'])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'waktu_pengumuman wajib diisi']);
        exit;
    }

    if (empty($input['tahun_ajaran'])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'tahun_ajaran wajib diisi']);
        exit;
    }

    // Validasi format datetime
    $waktu = $input['waktu_pengumuman'];
    $dt = DateTime::createFromFormat('Y-m-d H:i:s', $waktu);
    if (!$dt) {
        $dt = DateTime::createFromFormat('Y-m-d\TH:i', $waktu);
        if ($dt) {
            $waktu = $dt->format('Y-m-d H:i:s');
        } else {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'Format tanggal tidak valid']);
            exit;
        }
    }

    $tahunAjaran = trim($input['tahun_ajaran']);

    set_setting('waktu_pengumuman', $waktu);
    set_setting('tahun_ajaran', $tahunAjaran);

    echo json_encode([
        'success' => true,
        'waktu_pengumuman' => $waktu,
        'tahun_ajaran' => $tahunAjaran,
        'message' => 'Pengaturan berhasil diperbarui'
    ]);
} else {
    // Get current settings
    echo json_encode([
        'success' => true,
        'waktu_pengumuman' => get_setting('waktu_pengumuman', WAKTU_PENGUMUMAN_DEFAULT),
        'tahun_ajaran' => get_setting('tahun_ajaran', TAHUN_AJARAN_DEFAULT)
    ]);
}
