<?php
/**
 * Settings Helper
 * Baca/tulis pengaturan dari storage/settings.json
 */

if (!function_exists('get_setting')) {
    /**
     * Ambil nilai setting berdasarkan key
     */
    function get_setting($key, $default = null)
    {
        $file = BASE_PATH . '/storage/settings.json';
        if (!file_exists($file)) return $default;

        $content = file_get_contents($file);
        $settings = json_decode($content, true);

        return $settings[$key] ?? $default;
    }
}

if (!function_exists('set_setting')) {
    /**
     * Simpan nilai setting
     */
    function set_setting($key, $value)
    {
        $file = BASE_PATH . '/storage/settings.json';
        $settings = file_exists($file)
            ? json_decode(file_get_contents($file), true)
            : [];

        $settings[$key] = $value;

        return file_put_contents(
            $file,
            json_encode($settings, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
        );
    }
}
