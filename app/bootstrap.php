<?php
/**
 * Application Bootstrap
 * Di-require oleh semua entry point setelah mendefinisikan BASE_PATH
 */

require_once BASE_PATH . '/app/config/app.php';
require_once BASE_PATH . '/app/helpers/functions.php';
require_once BASE_PATH . '/app/helpers/settings.php';
require_once BASE_PATH . '/app/config/database.php';
require_once BASE_PATH . '/vendor/autoload.php';

// Load pengaturan dari settings (dynamic, bisa diubah admin)
define('WAKTU_PENGUMUMAN', get_setting('waktu_pengumuman', WAKTU_PENGUMUMAN_DEFAULT));
define('TAHUN_AJARAN', get_setting('tahun_ajaran', TAHUN_AJARAN_DEFAULT));
