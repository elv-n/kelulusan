<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$isLoggedIn = isset($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= APP_NAME ?> <?= TAHUN_AJARAN ?> — SMK N 1 Wadaslintang</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#f0fdf4',
                            100: '#dcfce7',
                            200: '#bbf7d0',
                            300: '#86efac',
                            400: '#4ade80',
                            500: '#22c55e',
                            600: '#16a34a',
                            700: '#15803d',
                            800: '#166534',
                            900: '#14532d',
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', 'system-ui', '-apple-system', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <!-- Google Font: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- App CSS -->
    <link rel="stylesheet" href="<?= asset_url('css/style.css') ?>">

    <!-- Page-specific CSS -->
    <?php if (!empty($pageCSS)): ?>
        <?php foreach ($pageCSS as $css): ?>
            <link rel="stylesheet" href="<?= $css ?>">
        <?php endforeach; ?>
    <?php endif; ?>
</head>

<body class="flex flex-col min-h-screen bg-gray-50 font-sans text-gray-800 pt-16">
    <!-- Navbar -->
    <nav class="fixed top-0 left-0 right-0 z-50 bg-white border-b border-gray-200">
        <div class="max-w-6xl mx-auto px-4 sm:px-6">
            <div class="flex items-center justify-between h-16">
                <a href="<?= base_url('index.php') ?>" class="text-lg font-semibold text-gray-900 hover:text-primary-700 transition-colors">
                    Kelulusan
                </a>

                <!-- Mobile menu button -->
                <button type="button" id="mobileMenuBtn" class="sm:hidden p-2 rounded-md text-gray-500 hover:text-gray-700 hover:bg-gray-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>

                <!-- Desktop nav -->
                <div class="hidden sm:flex items-center gap-1">
                    <a href="<?= base_url('index.php') ?>" class="px-3 py-2 text-sm font-medium text-gray-600 hover:text-primary-700 rounded-md hover:bg-gray-50 transition-colors">Home</a>
                    <a href="<?= base_url('cari-nisn.php') ?>" class="px-3 py-2 text-sm font-medium text-gray-600 hover:text-primary-700 rounded-md hover:bg-gray-50 transition-colors">Cari NISN</a>
                    <?php if ($isLoggedIn): ?>
                        <a href="<?= base_url('admin/dashboard.php') ?>" class="ml-2 px-4 py-2 text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 rounded-md shadow-sm transition-colors">Admin</a>
                    <?php else: ?>
                        <a href="<?= base_url('admin/login.php') ?>" class="ml-2 px-4 py-2 text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 rounded-md shadow-sm transition-colors">Login</a>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Mobile nav -->
            <div id="mobileMenu" class="sm:hidden hidden border-t border-gray-100 py-2">
                <a href="<?= base_url('index.php') ?>" class="block px-3 py-2 text-sm font-medium text-gray-600 hover:text-primary-700 hover:bg-gray-50 rounded-md">Home</a>
                <a href="<?= base_url('cari-nisn.php') ?>" class="block px-3 py-2 text-sm font-medium text-gray-600 hover:text-primary-700 hover:bg-gray-50 rounded-md">Cari NISN</a>
                <?php if ($isLoggedIn): ?>
                    <a href="<?= base_url('admin/dashboard.php') ?>" class="block px-3 py-2 mt-2 text-sm font-medium text-center text-white bg-primary-600 hover:bg-primary-700 rounded-md transition-colors">Admin</a>
                <?php else: ?>
                    <a href="<?= base_url('admin/login.php') ?>" class="block px-3 py-2 mt-2 text-sm font-medium text-center text-white bg-primary-600 hover:bg-primary-700 rounded-md transition-colors">Login</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>