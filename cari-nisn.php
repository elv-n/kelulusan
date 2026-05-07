<?php
/**
 * Entry Point — Cari NISN
 * Pencarian NISN berdasarkan nama dan tanggal lahir
 */

define('BASE_PATH', __DIR__);
require_once BASE_PATH . '/app/bootstrap.php';
require_once BASE_PATH . '/app/controllers/NisnController.php';

$controller = new NisnController($conn);
$controller->index();