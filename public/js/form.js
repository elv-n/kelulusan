/**
 * Form.js — Form interaction + auto-sync waktu pengumuman
 * Submit spinner, date auto-fill, dan polling perubahan waktu
 */

document.addEventListener('DOMContentLoaded', function () {
    // --- Submit Button Spinner ---
    var form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function (e) {
            var btnText = document.getElementById('btnText');
            var btnSpinner = document.getElementById('btnSpinner');

            if (btnText && btnSpinner) {
                btnText.classList.add('hidden');
                btnSpinner.classList.remove('hidden');

                e.preventDefault();
                setTimeout(function () {
                    e.target.submit();
                }, 500);
            }
        });
    }



    // --- Auto-sync: poll waktu pengumuman setiap 15 detik ---
    var checkTimeUrl = getBaseUrl() + '/api/check-time.php';
    var currentWaktu = null;

    // Ambil waktu awal
    fetchTime();
    setInterval(fetchTime, 15000);

    function fetchTime() {
        fetch(checkTimeUrl + '?_=' + Date.now(), {
            method: 'GET',
            cache: 'no-store',
            headers: { 'Cache-Control': 'no-cache' }
        })
        .then(function (res) { return res.json(); })
        .then(function (data) {
            if (!data.waktu_pengumuman) return;

            var serverWaktu = data.waktu_pengumuman;
            var waktuMs = new Date(serverWaktu.replace(' ', 'T')).getTime();
            var now = new Date().getTime();

            // Jika waktu berubah ke masa depan, reload ke timer
            if (currentWaktu !== null && serverWaktu !== currentWaktu && waktuMs > now) {
                console.log('[Form] Waktu diubah ke masa depan, reload...');
                location.reload();
                return;
            }

            currentWaktu = serverWaktu;
        })
        .catch(function () {
            // Silent fail — polling akan coba lagi
        });
    }

    // Helper: detect base URL
    function getBaseUrl() {
        var scripts = document.querySelectorAll('script[src]');
        for (var i = 0; i < scripts.length; i++) {
            var src = scripts[i].src;
            var idx = src.indexOf('/public/js/');
            if (idx !== -1) {
                return src.substring(0, idx);
            }
        }
        return window.location.origin + window.location.pathname.replace(/\/[^\/]*$/, '');
    }
});
