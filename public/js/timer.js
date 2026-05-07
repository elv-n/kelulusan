/**
 * Timer.js — Countdown timer dengan auto-sync
 * Polls server setiap 10 detik untuk cek perubahan waktu pengumuman
 * Otomatis update tanpa refresh halaman
 */

document.addEventListener('DOMContentLoaded', function () {
    var countdownEl = document.getElementById('countdown-container');
    if (!countdownEl) return;

    // Ambil API URL dari data attribute atau default
    var checkTimeUrl = countdownEl.dataset.apiUrl || getBaseUrl() + '/api/check-time.php';
    var targetTime = null;
    var countdownInterval = null;
    var pollInterval = null;

    // Initial load dari server
    fetchAnnouncementTime();

    // Poll setiap 10 detik untuk cek perubahan
    pollInterval = setInterval(fetchAnnouncementTime, 10000);

    function fetchAnnouncementTime() {
        fetch(checkTimeUrl + '?_=' + Date.now(), {
            method: 'GET',
            cache: 'no-store',
            headers: { 'Cache-Control': 'no-cache', 'Pragma': 'no-cache' }
        })
        .then(function (res) { return res.json(); })
        .then(function (data) {
            if (data.waktu_pengumuman) {
                var newTarget = new Date(data.waktu_pengumuman.replace(' ', 'T')).getTime();

                // Cek apakah waktu berubah
                if (targetTime !== null && targetTime !== newTarget) {
                    console.log('[Timer] Waktu pengumuman diperbarui!');

                    // Jika waktu sudah lewat, reload halaman ke form
                    var now = new Date().getTime();
                    if (newTarget <= now) {
                        console.log('[Timer] Waktu sudah lewat, reload...');
                        location.reload();
                        return;
                    }
                }

                targetTime = newTarget;

                // Start countdown jika belum jalan
                if (!countdownInterval) {
                    startCountdown();
                }
            }
        })
        .catch(function (err) {
            console.warn('[Timer] Gagal fetch waktu:', err.message);
        });
    }

    function startCountdown() {
        // Clear interval lama jika ada
        if (countdownInterval) clearInterval(countdownInterval);

        countdownInterval = setInterval(function () {
            if (targetTime === null) return;

            var now = new Date().getTime();
            var distance = targetTime - now;

            if (distance <= 0) {
                clearInterval(countdownInterval);
                clearInterval(pollInterval);
                var elContainer = document.getElementById('countdown-container');
                var elStatus = document.getElementById('countdown-status');
                if(elContainer) elContainer.style.display = 'none';
                if(elStatus) {
                    elStatus.style.display = 'block';
                    elStatus.textContent = "Pengumuman telah dimulai!";
                }

                // Reload halaman agar tampil form
                setTimeout(function () {
                    location.reload();
                }, 2000);
            } else {
                var days = Math.floor(distance / (1000 * 60 * 60 * 24));
                var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                var seconds = Math.floor((distance % (1000 * 60)) / 1000);

                var elDays = document.getElementById('t-days');
                var elHours = document.getElementById('t-hours');
                var elMins = document.getElementById('t-minutes');
                var elSecs = document.getElementById('t-seconds');
                
                if(elDays) elDays.textContent = days.toString().padStart(2, '0');
                if(elHours) elHours.textContent = hours.toString().padStart(2, '0');
                if(elMins) elMins.textContent = minutes.toString().padStart(2, '0');
                if(elSecs) elSecs.textContent = seconds.toString().padStart(2, '0');
            }
        }, 1000);
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
        // Fallback: guess from current URL
        return window.location.origin + window.location.pathname.replace(/\/[^\/]*$/, '');
    }
});
