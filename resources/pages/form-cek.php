<section class="w-full py-8 px-4">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
            <div class="grid grid-cols-1 md:grid-cols-2">
                <!-- Image (desktop only) -->
                <div class="hidden md:block">
                    <img class="w-full h-full object-cover" loading="lazy"
                        src="<?= base_url('public/img/1-min.png') ?>" alt="Gambar Sekolah">
                </div>

                <!-- Form -->
                <div class="p-6 md:p-8 flex flex-col justify-center">
                    <div class="mb-6 text-center">
                        <!-- Logo mobile -->
                        <div class="block md:hidden mb-4">
                            <img src="<?= base_url('public/img/logo-min.png') ?>"
                                class="w-16 h-16 mx-auto object-contain" alt="Logo Sekolah" loading="lazy">
                        </div>
                        <h2 class="text-lg sm:text-xl md:text-2xl font-bold text-gray-900 uppercase tracking-wide leading-snug">PENGUMUMAN KELULUSAN<br />KELAS XII</h2>
                        <p class="text-base font-semibold text-primary-700 mt-3">SMK Negeri 1 Wadaslintang</p>
                        <p class="text-sm text-gray-500 mt-0.5">Tahun Ajaran <?= htmlspecialchars(TAHUN_AJARAN) ?></p>
                    </div>

                    <form action="" method="POST" id="formPengumuman">
                        <div class="space-y-4">
                            <div>
                                <label for="nisn" class="block text-sm font-medium text-gray-700 mb-1">
                                    NISN <span class="text-red-500">*</span>
                                </label>
                                <input type="number" name="nisn" id="nisn" placeholder="Masukkan NISN"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                                    required>
                            </div>
                            <div>
                                <label for="captcha_answer" class="block text-sm font-medium text-gray-700 mb-1">
                                    Berapakah hasil dari <strong><?= $captcha_question ?> = ?</strong> <span class="text-red-500">*</span>
                                </label>
                                <input type="number" name="captcha_answer" id="captcha_answer" placeholder="Jawaban"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                                    required>
                            </div>
                            <div>
                                <button id="cekKelulusanBtn" type="submit"
                                    class="w-full px-4 py-2 bg-primary-600 text-white text-sm font-medium rounded-lg hover:bg-primary-700 transition-colors flex items-center justify-center gap-2">
                                    <span id="btnText">Cek Kelulusan</span>
                                    <span id="btnSpinner" class="hidden w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></span>
                                </button>
                            </div>
                        </div>
                    </form>

                    <div class="mt-6 text-right">
                        <a href="<?= base_url('cari-nisn.php') ?>" class="text-sm text-gray-400 hover:text-gray-600 transition-colors">Lupa NISN?</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>