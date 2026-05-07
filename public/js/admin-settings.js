/**
 * Admin Settings JS
 * AJAX save waktu pengumuman & tahun ajaran tanpa refresh halaman
 */

document.addEventListener('DOMContentLoaded', function () {
    var form = document.getElementById('formSettings');
    var inputWaktu = document.getElementById('waktuPengumuman');
    var inputTahun = document.getElementById('tahunAjaran');
    var btnSave = document.getElementById('btnSaveSettings');
    var statusEl = document.getElementById('settingsStatus');
    var currentTimeEl = document.getElementById('currentWaktuDisplay');
    var currentTahunEl = document.getElementById('currentTahunDisplay');

    if (!form) return;

    // Load current settings
    loadCurrentSettings();

    form.addEventListener('submit', function (e) {
        e.preventDefault();
        saveSettings();
    });

    function loadCurrentSettings() {
        fetch(form.dataset.apiUrl, {
            method: 'GET',
            cache: 'no-store',
            headers: { 'Cache-Control': 'no-cache' }
        })
        .then(function (res) { return res.json(); })
        .then(function (data) {
            if (data.success) {
                // Waktu
                if (data.waktu_pengumuman) {
                    var dt = data.waktu_pengumuman.replace(' ', 'T').substring(0, 16);
                    inputWaktu.value = dt;
                    updateWaktuDisplay(data.waktu_pengumuman);
                }
                // Tahun ajaran
                if (data.tahun_ajaran) {
                    inputTahun.value = data.tahun_ajaran;
                    updateTahunDisplay(data.tahun_ajaran);
                }
            }
        })
        .catch(function (err) {
            console.error('Gagal load settings:', err);
        });
    }

    function saveSettings() {
        var waktu = inputWaktu.value;
        var tahun = inputTahun.value.trim();

        if (!waktu) {
            showStatus('Pilih tanggal dan jam terlebih dahulu!', 'error');
            return;
        }
        if (!tahun) {
            showStatus('Isi tahun ajaran terlebih dahulu!', 'error');
            return;
        }

        btnSave.disabled = true;
        btnSave.innerHTML = '<span class="inline-block w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin mr-2"></span>Menyimpan...';

        fetch(form.dataset.apiUrl, {
            method: 'POST',
            cache: 'no-store',
            headers: {
                'Content-Type': 'application/json',
                'Cache-Control': 'no-cache'
            },
            body: JSON.stringify({
                waktu_pengumuman: waktu,
                tahun_ajaran: tahun
            })
        })
        .then(function (res) { return res.json(); })
        .then(function (data) {
            if (data.success) {
                showStatus(data.message, 'success');
                updateWaktuDisplay(data.waktu_pengumuman);
                updateTahunDisplay(data.tahun_ajaran);
            } else {
                showStatus(data.error || 'Gagal menyimpan', 'error');
            }
        })
        .catch(function (err) {
            showStatus('Terjadi kesalahan: ' + err.message, 'error');
        })
        .finally(function () {
            btnSave.disabled = false;
            btnSave.textContent = 'Simpan Pengaturan';
        });
    }

    function showStatus(message, type) {
        var colors = {
            success: 'bg-green-50 border border-green-200 text-green-700',
            error: 'bg-red-50 border border-red-200 text-red-700'
        };

        statusEl.className = 'mt-4 px-4 py-3 rounded-lg text-sm font-medium ' + (colors[type] || colors.error);
        statusEl.textContent = message;
        statusEl.style.display = 'block';
        statusEl.style.opacity = '1';
        statusEl.style.transform = 'none';

        setTimeout(function () {
            statusEl.style.transition = 'opacity 0.4s, transform 0.4s';
            statusEl.style.opacity = '0';
            statusEl.style.transform = 'translateY(-10px)';
            setTimeout(function () {
                statusEl.style.display = 'none';
                statusEl.style.opacity = '1';
                statusEl.style.transform = 'none';
            }, 400);
        }, 4000);
    }

    function updateWaktuDisplay(waktuStr) {
        if (!currentTimeEl) return;
        var options = {
            weekday: 'long', year: 'numeric', month: 'long', day: 'numeric',
            hour: '2-digit', minute: '2-digit', second: '2-digit',
            timeZone: 'Asia/Jakarta'
        };
        var normalized = waktuStr.replace(' ', 'T');
        if (normalized.length === 16) normalized += ':00';
        var dateObj = new Date(normalized);
        if (!isNaN(dateObj.getTime())) {
            currentTimeEl.textContent = dateObj.toLocaleDateString('id-ID', options) + ' WIB';
        } else {
            currentTimeEl.textContent = waktuStr;
        }
    }

    function updateTahunDisplay(tahun) {
        if (!currentTahunEl) return;
        currentTahunEl.textContent = tahun;
    }
});
