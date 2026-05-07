# Sistem Informasi Kelulusan

<img width="1162" height="708" alt="2026-05-07_093455" src="https://github.com/user-attachments/assets/cc34cc3d-a85b-4107-84ef-05e660c8e08d" />


**Sistem Informasi Kelulusan** adalah platform berbasis web yang dirancang untuk memfasilitasi pengumuman hasil kelulusan siswa secara online, aman, dan efisien. Aplikasi ini dilengkapi dengan fitur penghitungan mundur (countdown), verifikasi keamanan, serta pembuatan dokumen otomatis seperti SKL (Surat Keterangan Lulus) dan Transkrip Nilai.

---

## Fitur Utama

### Panel Admin
- **Dashboard Modern**: Statistik cepat data siswa dan status kelulusan.
- **Manajemen Data Siswa**: CRUD (Create, Read, Update, Delete) data siswa dengan mudah.
- **Import/Export Excel**: Integrasi dengan `PhpSpreadsheet` untuk mempermudah pemrosesan data massal.
- **Pengaturan SKL & Transkrip**: Konfigurasi mata pelajaran dan komponen nilai.
- **Urutan Mapel Dinamis**: Fitur drag-and-drop untuk mengatur urutan mata pelajaran pada dokumen cetak.
- **Kontrol Pengumuman**: Pengaturan waktu pengumuman yang dapat disesuaikan (Real-time countdown).

### Halaman Publik (Siswa)
- **Countdown Timer**: Menampilkan waktu mundur hingga detik-detik pengumuman.
- **Cek Kelulusan Cepat**: Siswa hanya perlu memasukkan NISN untuk melihat hasil.
- **Keamanan Captcha**: Sistem Math-Captcha untuk mencegah bot dan akses yang tidak diinginkan.
- **Unduh Dokumen Mandiri**: Siswa yang dinyatakan lulus dapat langsung mengunduh SKL dan Transkrip dalam format PDF/Cetak.

---

## Teknologi yang Digunakan

| Komponen | Teknologi |
| --- | --- |
| **Backend** | PHP 8.x (Native MVC Architecture) |
| **Database** | MySQL / MariaDB |
| **Frontend** | HTML5, CSS3, JavaScript (Vanilla/Custom) |
| **Libraries** | [PhpSpreadsheet](https://github.com/PHPOffice/PhpSpreadsheet) |
| **Server** | XAMPP / Apache / Nginx |

---

## Instalasi

1. **Clone Repositori**
   ```bash
   git clone https://github.com/username/kelulusan.git
   ```

2. **Pindahkan ke Server Lokal**
   Salin folder project ke direktori `htdocs` (XAMPP) atau `www` (Wamp/Nginx).

3. **Konfigurasi Database**
   - Buat database baru di phpMyAdmin (misal: `db_kelulusan`).
   - Import file `smknwada_kelulusan.sql` atau file migration `.sql` yang tersedia.
   - Sesuaikan konfigurasi database di `app/config/database.php`.

4. **Instal Dependensi**
   Pastikan Anda telah menginstal Composer, lalu jalankan:
   ```bash
   composer install
   ```

5. **Akses Aplikasi**
   Buka browser dan akses `http://localhost/kelulusan`.

---

## Lisensi

Project ini dikembangkan untuk mempermudah proses administrasi sekolah. Silakan digunakan dan dikembangkan lebih lanjut sesuai kebutuhan.
