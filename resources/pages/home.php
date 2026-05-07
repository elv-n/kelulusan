<main class="flex-grow flex items-center justify-center">
    <?php if ($showTimer): ?>
        <?php include BASE_PATH . '/resources/pages/timer.php'; ?>
    <?php elseif ($isPost && $data): ?>
        <?php include BASE_PATH . '/resources/pages/hasil-cek.php'; ?>
    <?php elseif ($isPost && $error === 'not_found'): ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                showToast('Data tidak ditemukan! Periksa kembali NISN Anda.', 'error', 4000);
                setTimeout(function() {
                    window.location = '<?= base_url("index.php") ?>';
                }, 2500);
            });
        </script>
    <?php elseif ($isPost && $error === 'invalid_captcha'): ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                showToast('Jawaban salah! Silakan coba lagi.', 'error', 4000);
                setTimeout(function() {
                    window.location = '<?= base_url("index.php") ?>';
                }, 2500);
            });
        </script>
    <?php else: ?>
        <?php include BASE_PATH . '/resources/pages/form-cek.php'; ?>
    <?php endif; ?>
</main>

<?php if (!($isPost && $data)): ?>
    <?php include BASE_PATH . '/resources/pages/himbauan.php'; ?>
<?php endif; ?>