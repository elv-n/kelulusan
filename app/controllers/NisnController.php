<?php
/**
 * NisnController
 * Menangani pencarian NISN berdasarkan nama dan tanggal lahir
 */

class NisnController
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function index()
    {
        $conn = $this->conn;
        $alertMessage = '';
        $alertType = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nama'], $_POST['tgl'])) {
            $nama = trim($_POST['nama']);
            $tgl = $_POST['tgl'];

            if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $tgl)) {
                $alertMessage = "Format tanggal tidak valid.";
                $alertType = 'danger';
            } else {
                $stmt = $conn->prepare("SELECT nisn FROM data_siswa WHERE nama = ? AND tanggal_lahir = ?");
                $stmt->bind_param("ss", $nama, $tgl);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    $data = $result->fetch_assoc();
                    $nisn = htmlspecialchars($data['nisn']);
                    $alertMessage = "NISN Anda adalah: <strong>$nisn</strong>";
                    $alertType = 'success';
                } else {
                    $alertMessage = "Data tidak ditemukan. Periksa kembali Tanggal Lahir.";
                    $alertType = 'danger';
                }
                $stmt->close();
            }
        }

        // Ambil daftar kelas dan pemetaan nama berdasarkan kelas
        $kelasOptions = [];
        $kelasNamaMap = [];
        
        $query = $conn->query("SELECT kelas, nama FROM data_siswa WHERE kelas IS NOT NULL AND kelas != '' ORDER BY kelas ASC, nama ASC");
        if ($query) {
            while ($row = $query->fetch_assoc()) {
                $kelas = trim($row['kelas']);
                $nama = trim($row['nama']);
                
                if (!in_array($kelas, $kelasOptions)) {
                    $kelasOptions[] = $kelas;
                }
                
                if (!isset($kelasNamaMap[$kelas])) {
                    $kelasNamaMap[$kelas] = [];
                }
                $kelasNamaMap[$kelas][] = htmlspecialchars($nama);
            }
        }

        // Page-specific assets
        $pageCSS = [
            'https://cdn.jsdelivr.net/npm/tom-select/dist/css/tom-select.min.css'
        ];
        $pageJS = [
            asset_url('js/app.js'),
            'https://cdn.jsdelivr.net/npm/tom-select/dist/js/tom-select.complete.min.js',
            asset_url('js/cari-nisn.js')
        ];

        // Render
        include BASE_PATH . '/resources/layouts/header.php';
        include BASE_PATH . '/resources/pages/cari-nisn.php';
        include BASE_PATH . '/resources/layouts/footer.php';
    }
}
