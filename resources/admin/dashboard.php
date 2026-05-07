<main class="flex-grow pt-4 pb-6 overflow-hidden max-h-[calc(100vh-4rem)] flex flex-col">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 w-full flex-grow flex flex-col h-full">
        <!-- Header -->
        <div class="mb-4 flex items-center justify-between flex-shrink-0">
            <div>
                <h2 class="text-2xl font-semibold text-gray-900">Selamat Datang, <?= htmlspecialchars($_SESSION['nama_lengkap']) ?></h2>
                <p class="text-sm text-gray-500 mt-0.5">Dashboard Admin</p>
            </div>
            <div class="flex items-center gap-3">
                <span class="hidden sm:inline-flex items-center justify-center w-10 h-10 rounded-full bg-primary-100 text-primary-700">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2" />
                        <circle cx="12" cy="7" r="4" />
                    </svg>
                </span>
                <a href="logout.php" class="inline-flex items-center gap-2 px-3 py-2 bg-red-50 text-red-600 hover:bg-red-100 border border-red-100 rounded-lg text-sm font-medium transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
                        <polyline points="16 17 21 12 16 7" />
                        <line x1="21" x2="9" y1="12" y2="12" />
                    </svg>
                    Logout
                </a>
            </div>
        </div>

        <div class="flex flex-col flex-grow min-h-0">
            <!-- Top Horizontal: Pengaturan Pengumuman -->
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm relative flex-shrink-0 mb-5">
                <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-emerald-400 to-teal-500 rounded-t-xl"></div>
                <div class="p-4 flex flex-col lg:flex-row items-center justify-between gap-4">
                    <!-- Info Kiri -->
                    <div class="flex flex-col sm:flex-row items-center gap-4 sm:gap-6 w-full lg:w-auto">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-emerald-50 rounded-lg text-emerald-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="3" />
                                    <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-sm font-semibold text-gray-900">Pengaturan Pengumuman</h3>
                            </div>
                        </div>
                        <div class="h-8 w-px bg-gray-200 hidden sm:block"></div>
                        <div class="flex items-center gap-4 bg-gray-50 px-4 py-2 rounded-lg border border-gray-100 w-full sm:w-auto justify-center">
                            <div>
                                <p class="text-[10px] font-medium text-gray-500 uppercase tracking-wide mb-0.5">Waktu</p>
                                <p id="currentWaktuDisplay" class="text-xs font-bold text-gray-900">Memuat...</p>
                            </div>
                            <div class="h-6 w-px bg-gray-200"></div>
                            <div>
                                <p class="text-[10px] font-medium text-gray-500 uppercase tracking-wide mb-0.5">T.A Aktif</p>
                                <p id="currentTahunDisplay" class="text-xs font-bold text-gray-900">Memuat...</p>
                            </div>
                        </div>
                    </div>

                    <!-- Form Kanan -->
                    <form id="formSettings" data-api-url="<?= base_url('api/settings.php') ?>" class="flex flex-col sm:flex-row items-end gap-3 w-full lg:w-auto">
                        <div class="w-full sm:w-auto">
                            <label for="waktuPengumuman" class="block text-[10px] font-medium text-gray-500 uppercase mb-1">Ubah Waktu</label>
                            <input type="datetime-local" id="waktuPengumuman" name="waktu_pengumuman" class="w-full sm:w-48 px-3 py-1.5 text-xs border border-gray-300 rounded-lg focus:ring-1 focus:ring-primary-500 bg-white" required>
                        </div>
                        <div class="w-full sm:w-auto">
                            <label for="tahunAjaran" class="block text-[10px] font-medium text-gray-500 uppercase mb-1">Ubah Tahun</label>
                            <input type="text" id="tahunAjaran" name="tahun_ajaran" placeholder="2024/2025" class="w-full sm:w-28 px-3 py-1.5 text-xs border border-gray-300 rounded-lg focus:ring-1 focus:ring-primary-500 bg-white" required>
                        </div>
                        <button type="submit" id="btnSaveSettings" class="w-full sm:w-auto px-4 py-1.5 bg-emerald-600 text-white text-xs font-medium rounded-lg hover:bg-emerald-700 transition-colors shadow-sm h-[30px] flex items-center justify-center whitespace-nowrap">
                            Simpan Perubahan
                        </button>
                    </form>
                </div>
                <div id="settingsStatus" class="px-4 py-1 text-[11px] font-medium text-center border-t border-gray-100" style="display:none;" role="alert"></div>
            </div>

            <!-- Bottom: Menu Grid -->
            <div class="flex-grow overflow-y-auto pr-1 pb-1">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                    
                    <!-- Column 1 & 2: Data Kelola -->
                    <div class="lg:col-span-2">
                        <h3 class="text-sm font-semibold text-gray-900 mb-3 px-1">Kelola Data Utama</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                            <!-- Import Data -->
                            <a href="import.php" class="group flex flex-col bg-white border border-gray-200 rounded-xl overflow-hidden hover:border-amber-300 hover:shadow-md transition-all duration-200 h-full">
                                <div class="p-3 flex-grow">
                                    <div class="w-8 h-8 rounded-full bg-amber-50 text-amber-600 flex items-center justify-center mb-2 group-hover:scale-110 transition-transform duration-300">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                                            <polyline points="17 8 12 3 7 8" />
                                            <line x1="12" x2="12" y1="3" y2="15" />
                                        </svg>
                                    </div>
                                    <h4 class="font-bold text-gray-900 text-sm">Import Data</h4>
                                    <p class="text-[10px] text-gray-500 mt-0.5">Upload Excel siswa & nilai</p>
                                </div>
                                <div class="bg-gray-50 px-3 py-1.5 border-t border-gray-100 flex items-center justify-between group-hover:bg-amber-50 transition-colors mt-auto">
                                    <span class="text-[11px] font-medium text-amber-600">Mulai Import</span>
                                    <svg class="w-3 h-3 text-amber-600 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M5 12h14" />
                                        <path d="m12 5 7 7-7 7" />
                                    </svg>
                                </div>
                            </a>

                            <!-- Data Kelulusan -->
                            <a href="siswa.php" class="group flex flex-col bg-white border border-gray-200 rounded-xl overflow-hidden hover:border-blue-300 hover:shadow-md transition-all duration-200 h-full">
                                <div class="p-3 flex-grow">
                                    <div class="w-8 h-8 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center mb-2 group-hover:scale-110 transition-transform duration-300">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                                            <circle cx="9" cy="7" r="4" />
                                            <path d="M22 21v-2a4 4 0 0 0-3-3.87" />
                                            <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                                        </svg>
                                    </div>
                                    <h4 class="font-bold text-gray-900 text-sm">Data Kelulusan</h4>
                                    <p class="text-[10px] text-gray-500 mt-0.5">Status kelulusan & cek nilai</p>
                                </div>
                                <div class="bg-gray-50 px-3 py-1.5 border-t border-gray-100 flex items-center justify-between group-hover:bg-blue-50 transition-colors mt-auto">
                                    <span class="text-[11px] font-medium text-blue-600">Kelola Data</span>
                                    <svg class="w-3 h-3 text-blue-600 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M5 12h14" />
                                        <path d="m12 5 7 7-7 7" />
                                    </svg>
                                </div>
                            </a>

                            <!-- Mata Pelajaran -->
                            <a href="skl_mapel.php" class="group flex flex-col bg-white border border-gray-200 rounded-xl overflow-hidden hover:border-purple-300 hover:shadow-md transition-all duration-200 h-full">
                                <div class="p-3 flex-grow">
                                    <div class="w-8 h-8 rounded-full bg-purple-50 text-purple-600 flex items-center justify-center mb-2 group-hover:scale-110 transition-transform duration-300">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                        </svg>
                                    </div>
                                    <h4 class="font-bold text-gray-900 text-sm">Mata Pelajaran</h4>
                                    <p class="text-[10px] text-gray-500 mt-0.5">Struktur mapel SKL</p>
                                </div>
                                <div class="bg-gray-50 px-3 py-1.5 border-t border-gray-100 flex items-center justify-between group-hover:bg-purple-50 transition-colors mt-auto">
                                    <span class="text-[11px] font-medium text-purple-600">Kelola Mapel</span>
                                    <svg class="w-3 h-3 text-purple-600 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M5 12h14" />
                                        <path d="m12 5 7 7-7 7" />
                                    </svg>
                                </div>
                            </a>
                        </div>
                    </div>

                    <!-- Column 3: Cetak & Pengaturan SKL -->
                    <div>
                        <h3 class="text-sm font-semibold text-gray-900 mb-3 px-1">Cetak & Pengaturan</h3>
                        <div class="flex flex-col gap-3">
                            <!-- Cetak SKL -->
                            <a href="skl_cetak.php" class="group flex items-center gap-3 bg-white border border-gray-200 rounded-xl p-3 hover:border-blue-300 hover:shadow-md transition-all">
                                <div class="w-10 h-10 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-900 text-sm">Cetak SKL</h4>
                                    <p class="text-[10px] text-gray-500 mt-0.5">Surat Keterangan Lulus</p>
                                </div>
                            </a>

                            <!-- Cetak Transkrip -->
                            <a href="cetak_transkrip.php" class="group flex items-center gap-3 bg-white border border-gray-200 rounded-xl p-3 hover:border-indigo-300 hover:shadow-md transition-all">
                                <div class="w-10 h-10 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-900 text-sm">Cetak Transkrip</h4>
                                    <p class="text-[10px] text-gray-500 mt-0.5">Transkrip Nilai Akademik</p>
                                </div>
                            </a>

                            <!-- Pengaturan SKL & Transkrip -->
                            <a href="skl_settings.php" class="group flex items-center gap-3 bg-white border border-gray-200 rounded-xl p-3 hover:border-emerald-300 hover:shadow-md transition-all">
                                <div class="w-10 h-10 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-900 text-sm">Pengaturan Kop</h4>
                                    <p class="text-[10px] text-gray-500 mt-0.5">KOP, Kepsek, Tahun Ajaran</p>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>