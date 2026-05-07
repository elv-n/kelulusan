<?php

/**
 * Helper Functions
 * Fungsi-fungsi pembantu yang dipakai di seluruh aplikasi
 */

if (!function_exists('base_url')) {
    /**
     * Generate base URL relatif terhadap project root
     */
    function base_url($path = '')
    {
        $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https" : "http";
        $host = $_SERVER['HTTP_HOST'];

        $documentRoot = realpath($_SERVER['DOCUMENT_ROOT']);
        $projectRoot = realpath(BASE_PATH);
        $basePath = str_replace('\\', '/', str_replace($documentRoot, '', $projectRoot));

        $base = $scheme . '://' . $host . $basePath;
        $base = rtrim($base, '/');

        return $path ? $base . '/' . ltrim($path, '/') : $base;
    }
}

if (!function_exists('asset_url')) {
    /**
     * Generate URL ke folder public assets
     */
    function asset_url($path = '')
    {
        return base_url('public/' . ltrim($path, '/'));
    }
}
if (!function_exists('formatTanggalIndo')) {
    /**
     * Format tanggal ke format Indonesia (contoh: 20 Januari 2008)
     */
    function formatTanggalIndo($date)
    {
        $months = [
            1 => 'Januari',
            'Februari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'Juli',
            'Agustus',
            'September',
            'Oktober',
            'November',
            'Desember'
        ];
        $d = date('j', strtotime($date));
        $m = $months[(int)date('n', strtotime($date))];
        $y = date('Y', strtotime($date));
        return "$d $m $y";
    }
}

if (!function_exists('formatNilai')) {
    /**
     * Format angka nilai (tanpa desimal)
     */
    function formatNilai($val)
    {
        return ($val !== null && $val !== '') ? number_format((float)$val, 0, ',', '.') : '';
    }
}

if (!function_exists('get_bidang_keahlian')) {
    /**
     * Mapping Program Keahlian ke Bidang Keahlian
     */
    function get_bidang_keahlian($program)
    {
        $map = [
            'Teknik Otomotif' => 'Teknologi Manufaktur dan Rekayasa',
            'Teknik Ketenagalistrikan' => 'Energi dan Pertambangan',
            'Agribisnis Perikanan' => 'Agribisnis dan Agriteknologi',
            'Pengembangan Perangkat Lunak dan Gim' => 'Teknologi Informasi',
            'Busana' => 'Seni dan Ekonomi Kreatif'
        ];

        return $map[$program] ?? '-';
    }
}
