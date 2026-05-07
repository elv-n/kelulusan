/**
 * Cari-NISN.js — Tom Select initialization & Alert fade
 * Halaman pencarian NISN
 */

document.addEventListener('DOMContentLoaded', function () {
    // --- Vanilla JS Cascading Dropdown ---
    var kelasSelect = document.getElementById('kelasSelect');
    var namaSelect = document.getElementById('namaSelect');

    if (kelasSelect && namaSelect) {
        kelasSelect.addEventListener('change', function() {
            var selectedKelas = this.value;
            
            // Hapus opsi nama yang ada kecuali opsi pertama
            while (namaSelect.options.length > 1) {
                namaSelect.remove(1);
            }

            if (selectedKelas && window.kelasNamaMap && window.kelasNamaMap[selectedKelas]) {
                // Isi opsi nama
                var names = window.kelasNamaMap[selectedKelas];
                names.forEach(function(nama) {
                    var option = document.createElement('option');
                    option.value = nama;
                    option.textContent = nama;
                    namaSelect.appendChild(option);
                });
                
                // Aktifkan dropdown nama
                namaSelect.disabled = false;
                namaSelect.options[0].textContent = '-- Pilih Nama Lengkap --';
            } else {
                // Jika kelas kosong, disable nama
                namaSelect.disabled = true;
                namaSelect.options[0].textContent = '-- Pilih Kelas Terlebih Dahulu --';
            }
        });

        // Handle initial load state
        if (!kelasSelect.value) {
            namaSelect.disabled = true;
            if(namaSelect.options.length > 0) {
                namaSelect.options[0].textContent = '-- Pilih Kelas Terlebih Dahulu --';
            }
        } else {
            namaSelect.disabled = false;
            if(namaSelect.options.length > 0 && namaSelect.options[0].value === "") {
                namaSelect.options[0].textContent = '-- Pilih Nama Lengkap --';
            }
        }
    }

    // --- Auto-fade Alert ---
    var alertElement = document.getElementById('alertMessage');
    if (alertElement && alertElement.classList.contains('text-red-700')) {
        setTimeout(function () {
            alertElement.style.transition = "opacity 0.5s, transform 0.5s";
            alertElement.style.opacity = "0";
            alertElement.style.transform = "translateY(-20px)";
            setTimeout(function () {
                alertElement.remove();
            }, 500);
        }, 3000);
    }
});
