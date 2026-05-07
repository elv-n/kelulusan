<?php
/**
 * Entry Point — Home
 * Pengumuman Kelulusan SMK N 1 Wadaslintang
 */

define('BASE_PATH', __DIR__);
require_once BASE_PATH . '/app/bootstrap.php';
require_once BASE_PATH . '/app/controllers/HomeController.php';

$controller = new HomeController($conn);
$controller->index();