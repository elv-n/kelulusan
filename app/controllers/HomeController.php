<?php
/**
 * HomeController
 * Menangani halaman utama: timer, form cek, dan hasil kelulusan
 */

class HomeController
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function index()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $conn = $this->conn;
        $waktuPengumuman = strtotime(WAKTU_PENGUMUMAN);
        $waktuSekarang = time();
        $showTimer = ($waktuSekarang < $waktuPengumuman);

        $data = null;
        $error = null;
        $isPost = ($_SERVER['REQUEST_METHOD'] === 'POST');

        // Proses form cek kelulusan
        if (!$showTimer && $isPost && isset($_POST['nisn'], $_POST['captcha_answer'])) {
            $nisn = $_POST['nisn'];
            $answer = (int)$_POST['captcha_answer'];
            $expected = isset($_SESSION['captcha_answer']) ? (int)$_SESSION['captcha_answer'] : null;

            if ($expected === null || $answer !== $expected) {
                $error = 'invalid_captcha';
            } else {
                $stmt = $conn->prepare("SELECT * FROM data_siswa WHERE nisn = ?");
                $stmt->bind_param("s", $nisn);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    $data = $result->fetch_assoc();
                    unset($_SESSION['captcha_answer']);
                } else {
                    $error = 'not_found';
                }
                $stmt->close();
            }
        }

        // Generate Math Captcha
        $captcha_question = '';
        if (!$showTimer && (!$isPost || $error !== null)) {
            $ops = ['+', '-', '*', '/'];
            $op = $ops[array_rand($ops)];
            
            if ($op === '+') {
                $num1 = rand(1, 50);
                $num2 = rand(1, 50);
                $ans = $num1 + $num2;
            } elseif ($op === '-') {
                $num1 = rand(10, 99);
                $num2 = rand(1, $num1);
                $ans = $num1 - $num2;
            } elseif ($op === '*') {
                $num1 = rand(2, 9);
                $num2 = rand(2, 11);
                $ans = $num1 * $num2;
            } elseif ($op === '/') {
                $num2 = rand(2, 10);
                $ans = rand(2, 10);
                $num1 = $num2 * $ans;
            }

            $_SESSION['captcha_answer'] = $ans;
            $captcha_question = "$num1 " . str_replace(['*', '/'], ['×', '÷'], $op) . " $num2";
        }

        // Page-specific assets
        $pageCSS = [];
        $pageJS = [asset_url('js/app.js')];

        if ($showTimer) {
            $pageCSS[] = asset_url('css/timer.css');
            $pageJS[] = asset_url('js/timer.js');
        } else {
            $pageJS[] = asset_url('js/form.js');
        }

        // Render
        include BASE_PATH . '/resources/layouts/header.php';
        include BASE_PATH . '/resources/pages/home.php';
        include BASE_PATH . '/resources/layouts/footer.php';
    }
}
