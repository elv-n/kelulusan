<main class="flex-grow px-4 py-8">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
            <div>
                <h2 class="text-2xl font-semibold text-gray-900">Data Kelulusan</h2>
                <p class="text-sm text-gray-500 mt-1">Lihat data dan status kelulusan siswa</p>
            </div>
            <div class="flex flex-col sm:flex-row items-center gap-2">
                <button type="button" onclick="confirmDeleteAll()" class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 bg-red-50 text-red-700 border border-red-200 text-sm font-medium rounded-lg hover:bg-red-100 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="3 6 5 6 21 6"></polyline>
                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                    </svg>
                    Kosongkan Data
                </button>
                <a href="dashboard.php" class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 border border-gray-300 text-sm font-medium text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                    &larr; Dashboard
                </a>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-6">
            <div class="bg-white border border-gray-200 rounded-lg px-4 py-3">
                <p class="text-xs text-gray-400 font-medium">Total</p>
                <p class="text-2xl font-bold text-gray-900 mt-1" id="totalSiswa">-</p>
            </div>
            <div class="bg-white border border-gray-200 rounded-lg px-4 py-3">
                <p class="text-xs text-gray-400 font-medium">Lulus</p>
                <p class="text-2xl font-bold text-green-600 mt-1" id="totalLulus">-</p>
            </div>
            <div class="bg-white border border-gray-200 rounded-lg px-4 py-3">
                <p class="text-xs text-gray-400 font-medium">Tidak Lulus</p>
                <p class="text-2xl font-bold text-red-600 mt-1" id="totalTidakLulus">-</p>
            </div>
            <div class="bg-white border border-gray-200 rounded-lg px-4 py-3">
                <p class="text-xs text-gray-400 font-medium">Belum Lulus</p>
                <p class="text-2xl font-bold text-amber-600 mt-1" id="totalBelum">-</p>
            </div>
        </div>

        <!-- Search & Filters -->
        <div class="bg-white border border-gray-200 rounded-lg p-4 mb-4">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-3 items-end">
                <div class="md:col-span-5">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Cari Siswa</label>
                    <input type="text" id="searchSiswa" placeholder="Ketik nama atau NISN..."
                        class="w-full px-4 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                        autocomplete="off">
                </div>
                <div class="md:col-span-3">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kelas</label>
                    <select id="filterKelas" class="w-full px-4 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white">
                        <option value="">Semua Kelas</option>
                    </select>
                </div>
                <div class="md:col-span-3">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select id="filterStatus" class="w-full px-4 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white">
                        <option value="">Semua Status</option>
                        <option value="LULUS">Lulus</option>
                        <option value="TIDAK LULUS">Tidak Lulus</option>
                        <option value="-">Belum Lulus</option>
                    </select>
                </div>
                <div class="md:col-span-1">
                    <button type="button"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm text-gray-500 hover:bg-gray-50 transition-colors"
                        onclick="document.getElementById('searchSiswa').value='';document.getElementById('filterKelas').value='';document.getElementById('filterStatus').value='';document.getElementById('searchSiswa').dispatchEvent(new Event('input'));"
                        title="Reset filter">
                        Reset
                    </button>
                </div>
            </div>
        </div>

        <!-- Data Table -->
        <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full" id="siswaTable" data-api-url="<?= base_url('api/siswa.php') ?>">
                    <thead>
                        <tr class="border-b border-gray-200 bg-gray-50">
                            <th class="px-3 py-3 text-center text-xs font-medium text-gray-500 uppercase w-12">No</th>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase w-28">NISN</th>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase">TTL</th>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase w-12">JK</th>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jurusan</th>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase w-16">Kelas</th>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase w-56">Status & Keterangan</th>
                        </tr>
                    </thead>
                    <tbody id="siswaTableBody" class="divide-y divide-gray-100">
                    </tbody>
                </table>

                <!-- Loading State -->
                <div id="loadingRow" class="text-center py-12">
                    <div class="inline-block w-6 h-6 border-2 border-primary-600 border-t-transparent rounded-full animate-spin mb-3"></div>
                    <p class="text-sm text-gray-400">Memuat data siswa...</p>
                </div>
            </div>
        </div>

        <p class="text-xs text-gray-400 mt-3 text-center">
            Ubah status langsung dari dropdown. Untuk status Belum atau Tidak Lulus, isi keterangan lalu tekan Enter.
        </p>
    </div>
</main>