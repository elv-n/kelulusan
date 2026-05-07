<?php
/**
 * API — Check Time (Public)
 * Endpoint untuk polling waktu pengumuman dari halaman publik
 * Mengembalikan waktu pengumuman + server time untuk sinkronisasi
 */

define('BASE_PATH', dirname(__DIR__));
require_once BASE_PATH . '/app/config/app.php';
require_once BASE_PATH . '/app/helpers/functions.php';
require_once BASE_PATH . '/app/helpers/settings.php';

// Anti-cache headers agar selalu fresh
header('Content-Type: application/json');
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');

echo json_encode([
    'waktu_pengumuman' => get_setting('waktu_pengumuman', WAKTU_PENGUMUMAN_DEFAULT),
    'tahun_ajaran' => get_setting('tahun_ajaran', TAHUN_AJARAN_DEFAULT),
    'server_time' => date('Y-m-d H:i:s')
]);
