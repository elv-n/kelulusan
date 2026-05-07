/**
 * Admin Siswa JS
 * Load data siswa, search/filter, update status + keterangan via AJAX
 */

document.addEventListener("DOMContentLoaded", function () {
  var apiUrl = document.getElementById("siswaTable").dataset.apiUrl;
  var searchInput = document.getElementById("searchSiswa");
  var filterKelas = document.getElementById("filterKelas");
  var filterStatus = document.getElementById("filterStatus");
  var tableBody = document.getElementById("siswaTableBody");
  var totalBadge = document.getElementById("totalSiswa");
  var lulusBadge = document.getElementById("totalLulus");
  var tidakLulusBadge = document.getElementById("totalTidakLulus");
  var belumBadge = document.getElementById("totalBelum");
  var loadingRow = document.getElementById("loadingRow");

  var debounceTimer = null;

  // Initial load
  loadSiswa();

  // Search with debounce
  searchInput.addEventListener("input", function () {
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(loadSiswa, 400);
  });

  // Filters
  filterKelas.addEventListener("change", loadSiswa);
  filterStatus.addEventListener("change", loadSiswa);

  function loadSiswa() {
    var search = searchInput.value.trim();
    var kelas = filterKelas.value;
    var status = filterStatus.value;

    var params = new URLSearchParams();
    if (search) params.set("search", search);
    if (kelas) params.set("kelas", kelas);
    if (status) params.set("status", status);
    params.set("_", Date.now());

    tableBody.innerHTML = "";
    loadingRow.style.display = "";

    fetch(apiUrl + "?" + params.toString(), {
      method: "GET",
      cache: "no-store",
      headers: { "Cache-Control": "no-cache" },
    })
      .then(function (res) {
        return res.json();
      })
      .then(function (response) {
        loadingRow.style.display = "none";
        if (!response.success) {
          tableBody.innerHTML =
            '<tr><td colspan="8" class="text-center text-red-600 py-4 text-sm">Gagal memuat data</td></tr>';
          return;
        }
        renderTable(response.data);
        updateStats(response.stats);
        populateKelasFilter(response.kelas_list);
      })
      .catch(function (err) {
        loadingRow.style.display = "none";
        tableBody.innerHTML =
          '<tr><td colspan="8" class="text-center text-red-600 py-4 text-sm">Error: ' + err.message + "</td></tr>";
      });
  }

  function renderTable(data) {
    if (data.length === 0) {
      tableBody.innerHTML =
        '<tr><td colspan="8" class="text-center text-gray-400 py-8 text-sm">Tidak ada data ditemukan</td></tr>';
      return;
    }

    var html = "";
    data.forEach(function (s, index) {
      var isLulus = s.status_kelulusan === "LULUS";
      var isTidakLulus = s.status_kelulusan === "TIDAK LULUS";
      var isBelum = s.status_kelulusan === "-";
      var rowBg = isLulus ? "" : isTidakLulus ? "bg-red-50" : "bg-amber-50";

      html += '<tr class="border-b border-gray-100 hover:bg-gray-50 ' + rowBg + '" id="row-' + s.nisn + '">';
      html += '<td class="px-3 py-2 text-center text-xs text-gray-400">' + (index + 1) + "</td>";
      html += '<td class="px-3 py-2 text-sm font-mono text-gray-600">' + escapeHtml(s.nisn) + "</td>";
      html += '<td class="px-3 py-2 text-sm font-medium text-gray-900">' + escapeHtml(s.nama) + "</td>";
      html +=
        '<td class="px-3 py-2 text-xs text-gray-500">' +
        escapeHtml(s.tempat_lahir) +
        ", " +
        formatDate(s.tanggal_lahir) +
        "</td>";
      html += '<td class="px-3 py-2 text-left text-xs text-gray-500">' + escapeHtml(s.jenis_kelamin) + "</td>";
      html += '<td class="px-3 py-2 text-left text-xs text-gray-500">' + escapeHtml(s.program_keahlian) + "</td>";
      html +=
        '<td class="px-3 py-2 text-left whitespace-nowrap"><span class="inline-block px-2 py-0.5 text-xs font-medium bg-gray-100 text-gray-600 rounded">' +
        escapeHtml(s.kelas) +
        "</span></td>";

      // Status + keterangan column
      html += '<td class="px-3 py-2">';
      html += '<div class="flex flex-col gap-1">';
      html += '<div class="flex items-center gap-2">';
      html +=
        '<select class="status-select px-2 py-1 border border-gray-300 rounded text-sm bg-white focus:outline-none focus:ring-1 focus:ring-green-500" data-nisn="' +
        s.nisn +
        '" style="width: 140px;">';
      html += '<option value="LULUS"' + (isLulus ? " selected" : "") + ">LULUS</option>";
      html += '<option value="TIDAK LULUS"' + (isTidakLulus ? " selected" : "") + ">TIDAK LULUS</option>";
      html += '<option value="-"' + (isBelum ? " selected" : "") + ">BELUM LULUS</option>";
      html += "</select>";
      html += '<span class="save-indicator" id="indicator-' + s.nisn + '"></span>';
      html += "</div>";

      // Keterangan input (visible for non-LULUS)
      var showKet = !isLulus;
      var ketValue = s.keterangan_status || "";
      html += '<div class="ket-wrapper" id="ket-wrap-' + s.nisn + '" style="' + (showKet ? "" : "display:none;") + '">';
      html += '<div class="flex gap-1 items-start">';
      html +=
        '<textarea class="ket-input flex-1 px-2 py-1 border border-gray-300 rounded text-sm focus:outline-none focus:ring-1 focus:ring-green-500 resize-y min-h-[32px]" data-nisn="' +
        s.nisn +
        '" rows="2"';
      html += ' placeholder="Keterangan...">' + escapeHtml(ketValue) + "</textarea>";
      html +=
        '<button class="ket-save px-2 py-1 border border-gray-300 rounded text-xs text-gray-600 hover:bg-gray-50 h-8" data-nisn="' +
        s.nisn +
        '" type="button" title="Simpan keterangan">';
      html += "&#10003;";
      html += "</button>";
      html += "</div></div>";

      html += "</div></td>";

      html += "</tr>";
    });

    tableBody.innerHTML = html;
    bindEvents();
  }

  function bindEvents() {
    // Status dropdown change
    tableBody.querySelectorAll(".status-select").forEach(function (select) {
      select.addEventListener("change", function () {
        var nisn = this.dataset.nisn;
        var status = this.value;
        var ketWrap = document.getElementById("ket-wrap-" + nisn);
        var ketInput = tableBody.querySelector('.ket-input[data-nisn="' + nisn + '"]');

        // Show/hide keterangan
        if (status === "LULUS") {
          ketWrap.style.display = "none";
          ketInput.value = "";
        } else {
          ketWrap.style.display = "";
        }

        updateStatus(nisn, status, status === "LULUS" ? "" : ketInput.value);
      });
    });

    // Keterangan save button
    tableBody.querySelectorAll(".ket-save").forEach(function (btn) {
      btn.addEventListener("click", function () {
        var nisn = this.dataset.nisn;
        var select = tableBody.querySelector('.status-select[data-nisn="' + nisn + '"]');
        var ketInput = tableBody.querySelector('.ket-input[data-nisn="' + nisn + '"]');
        updateStatus(nisn, select.value, ketInput.value);
      });
    });

    // Keterangan enter key
    tableBody.querySelectorAll(".ket-input").forEach(function (input) {
      input.addEventListener("keydown", function (e) {
        if (e.key === "Enter") {
          e.preventDefault();
          var nisn = this.dataset.nisn;
          var select = tableBody.querySelector('.status-select[data-nisn="' + nisn + '"]');
          updateStatus(nisn, select.value, this.value);
        }
      });
    });
  }

  function updateStatus(nisn, status, keterangan) {
    var indicator = document.getElementById("indicator-" + nisn);
    var row = document.getElementById("row-" + nisn);
    var select = tableBody.querySelector('.status-select[data-nisn="' + nisn + '"]');

    indicator.innerHTML =
      '<span class="inline-block w-4 h-4 border-2 border-green-600 border-t-transparent rounded-full animate-spin"></span>';
    select.disabled = true;

    fetch(apiUrl, {
      method: "POST",
      cache: "no-store",
      headers: { "Content-Type": "application/json", "Cache-Control": "no-cache" },
      body: JSON.stringify({
        nisn: nisn,
        status_kelulusan: status,
        keterangan_status: keterangan || "",
      }),
    })
      .then(function (res) {
        return res.json();
      })
      .then(function (data) {
        select.disabled = false;
        if (data.success) {
          indicator.innerHTML = '<span class="text-green-600 text-sm font-bold">&#10003;</span>';

          // Update row styling
          row.className = "border-b border-gray-100 hover:bg-gray-50";
          if (status === "TIDAK LULUS") row.className += " bg-red-50";
          else if (status === "-") row.className += " bg-amber-50";

          updateStatsFromDOM();

          setTimeout(function () {
            indicator.style.transition = "opacity 0.5s";
            indicator.style.opacity = "0";
            setTimeout(function () {
              indicator.innerHTML = "";
              indicator.style.opacity = "1";
            }, 500);
          }, 2000);
        } else {
          indicator.innerHTML =
            '<span class="text-red-600 text-sm font-bold" title="' + (data.error || "Gagal") + '">&#10007;</span>';
        }
      })
      .catch(function (err) {
        select.disabled = false;
        indicator.innerHTML =
          '<span class="text-red-600 text-sm font-bold" title="' + err.message + '">&#10007;</span>';
      });
  }

  function updateStats(stats) {
    if (!stats) return;
    totalBadge.textContent = stats.total || 0;
    lulusBadge.textContent = stats.lulus || 0;
    if (tidakLulusBadge) tidakLulusBadge.textContent = stats.tidak_lulus || 0;
    belumBadge.textContent = stats.belum || 0;
  }

  function updateStatsFromDOM() {
    var selects = tableBody.querySelectorAll(".status-select");
    var lulus = 0,
      tidakLulus = 0,
      belum = 0;
    selects.forEach(function (sel) {
      if (sel.value === "LULUS") lulus++;
      else if (sel.value === "TIDAK LULUS") tidakLulus++;
      else belum++;
    });
    totalBadge.textContent = selects.length;
    lulusBadge.textContent = lulus;
    if (tidakLulusBadge) tidakLulusBadge.textContent = tidakLulus;
    belumBadge.textContent = belum;
  }

  function populateKelasFilter(kelasList) {
    if (filterKelas.options.length > 1) return;
    kelasList.forEach(function (k) {
      var opt = document.createElement("option");
      opt.value = k;
      opt.textContent = k;
      filterKelas.appendChild(opt);
    });
  }

  function formatDate(dateStr) {
    if (!dateStr) return "-";
    var d = new Date(dateStr);
    if (isNaN(d.getTime())) return dateStr;
    var months = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
    return d.getDate() + " " + months[d.getMonth()] + " " + d.getFullYear();
  }

  function escapeHtml(str) {
    if (!str) return "";
    var div = document.createElement("div");
    div.textContent = str;
    return div.innerHTML;
  }

  function escapeAttr(str) {
    if (!str) return "";
    return str
      .replace(/&/g, "&amp;")
      .replace(/"/g, "&quot;")
      .replace(/'/g, "&#39;")
      .replace(/</g, "&lt;")
      .replace(/>/g, "&gt;");
  }

  window.confirmDeleteAll = function () {
    if (
      confirm(
        "PERINGATAN BAHAYA!\n\nApakah Anda yakin ingin MENGHAPUS SEMUA DATA SISWA?\nTindakan ini bersifat permanen dan tidak dapat dibatalkan."
      )
    ) {
      if (confirm("Apakah Anda benar-benar yakin? Semua data akan hilang.")) {
        tableBody.innerHTML = "";
        loadingRow.style.display = "";

        fetch(apiUrl, {
          method: "POST",
          cache: "no-store",
          headers: { "Content-Type": "application/json", "Cache-Control": "no-cache" },
          body: JSON.stringify({ action: "delete_all" }),
        })
          .then(function (res) {
            return res.json();
          })
          .then(function (data) {
            if (data.success) {
              alert("Sukses: Semua data siswa berhasil dikosongkan.");
              // Reload table and stats
              loadSiswa();
            } else {
              alert("Gagal: " + (data.error || "Terjadi kesalahan"));
              loadSiswa();
            }
          })
          .catch(function (err) {
            alert("Error: " + err.message);
            loadSiswa();
          });
      }
    }
  };
});
