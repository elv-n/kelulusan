<?php
/**
 * Application Configuration
 * Konfigurasi umum aplikasi
 */

// Informasi Aplikasi
define('APP_NAME', 'Pengumuman Kelulusan');
define('APP_TIMEZONE', 'Asia/Jakarta');

// Waktu pengumuman default (fallback jika settings.json belum ada — gunakan waktu sekarang)
define('WAKTU_PENGUMUMAN_DEFAULT', date('Y-m-d H:i:s'));

// Tahun ajaran default
define('TAHUN_AJARAN_DEFAULT', '2024/2025');

// Set timezone
date_default_timezone_set(APP_TIMEZONE);
