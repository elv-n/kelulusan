<?php
/**
 * Entry Point — Admin Import
 */

define('BASE_PATH', dirname(__DIR__));
require_once BASE_PATH . '/app/bootstrap.php';
require_once BASE_PATH . '/app/controllers/AdminController.php';

$controller = new AdminController($conn);
$controller->import();