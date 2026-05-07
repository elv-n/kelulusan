<?php
/**
 * Auth Middleware
 * Cek apakah user sudah login, redirect jika belum
 */

class AuthMiddleware
{
    /**
     * Pastikan user sudah login, redirect ke login jika belum
     */
    public static function check()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . base_url('admin/login.php'));
            exit();
        }
    }
}
