<main class="flex-grow py-8">
    <div class="max-w-6xl mx-auto px-4 sm:px-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-2xl font-semibold text-gray-900">Input Nilai</h2>
                <p class="text-sm text-gray-500 mt-1">Siswa: <span class="font-bold text-gray-700"><?= htmlspecialchars($siswa['nama']) ?></span> (<?= htmlspecialchars($siswa['nisn']) ?>)</p>
            </div>
            <a href="siswa.php" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                &larr; Kembali ke Daftar Siswa
            </a>
        </div>

        <?php if (isset($_GET['status']) && $_GET['status'] == 'success'): ?>
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-4 rounded-lg mb-6 shadow-sm">
            <div class="flex items-center justify-between flex-wrap gap-4">
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-green-500" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    <span class="font-medium text-lg">Semua nilai berhasil disimpan!</span>
                </div>
                <div class="flex gap-2">
                    <a href="<?= base_url('generate_skl.php?nisn=' . $siswa['nisn']) ?>" target="_blank" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-bold hover:bg-blue-700 transition-colors shadow-sm">
                        Cetak SKL
                    </a>
                    <a href="<?= base_url('generate_transkrip.php?nisn=' . $siswa['nisn']) ?>" target="_blank" class="bg-primary-600 text-white px-4 py-2 rounded-lg text-sm font-bold hover:bg-primary-700 transition-colors shadow-sm">
                        Cetak Transkrip
                    </a>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <form action="" method="POST" class="space-y-6">
            <!-- Data Identitas Tambahan -->
            <div class="bg-white border border-gray-200 rounded-xl overflow-hidden shadow-sm p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4 border-b border-gray-100 pb-2">Identitas Tambahan</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">NIS (Nomor Induk Siswa)</label>
                        <input type="text" name="nis" value="<?= htmlspecialchars($siswa['nis'] ?? '') ?>" placeholder="Sesuai Buku Induk"
                            class="w-full px-4 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Konsentrasi Keahlian</label>
                        <input type="text" name="konsentrasi_keahlian" value="<?= htmlspecialchars($siswa['konsentrasi_keahlian'] ?? $siswa['program_keahlian']) ?>"
                            class="w-full px-4 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nomor SKL</label>
                        <input type="text" name="nomor_skl" value="<?= htmlspecialchars($siswa['nomor_skl'] ?? '400.3.11.1/') ?>"
                            class="w-full px-4 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                    </div>
                </div>
            </div>

            <!-- Input Nilai Gabungan: SKL + Transkrip -->
            <div class="bg-white border border-gray-200 rounded-xl overflow-hidden shadow-sm">
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-200 flex items-center justify-between">
                    <h3 class="text-lg font-medium text-gray-900">Nilai Mata Pelajaran</h3>
                     <span class="text-xs text-gray-400 italic">Nilai SKL + N_PSAJ (Transkrip) + Nilai Semester (S1-S6)</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-200 text-gray-500 font-bold uppercase text-[10px] tracking-wider">
                                <th class="px-4 py-3 text-left">Mata Pelajaran</th>
                                <th class="px-2 py-3 text-center w-20 bg-blue-50 text-blue-700">Nilai SKL</th>
                                <th class="px-2 py-3 text-center w-20 bg-emerald-50 text-emerald-700">N_PSAJ</th>
                                <th class="px-2 py-3 text-center w-16">S1</th>
                                <th class="px-2 py-3 text-center w-16">S2</th>
                                <th class="px-2 py-3 text-center w-16">S3</th>
                                <th class="px-2 py-3 text-center w-16">S4</th>
                                <th class="px-2 py-3 text-center w-16">S5</th>
                                <th class="px-2 py-3 text-center w-16">S6</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <?php 
                            $currentKategori = '';
                            foreach ($mapels as $m): 
                                $sem = $semesterMap[$m['id']] ?? [];
                                if ($currentKategori != $m['kategori']):
                                    $currentKategori = $m['kategori'];
                            ?>
                                <tr class="bg-gray-50">
                                    <td colspan="9" class="px-4 py-2 font-bold text-gray-500 text-[11px] uppercase">
                                        <?= $currentKategori === 'Muatan Lokal' ? 'Muatan Lokal' : 'Mata Pelajaran ' . $currentKategori ?>
                                    </td>
                                </tr>
                            <?php endif; ?>
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-4 py-2 font-medium text-gray-700"><?= $m['nama_mapel'] ?></td>
                                <td class="px-1 py-2 text-center bg-blue-50/30">
                                    <input type="number" step="0.01" name="nilai[<?= $m['id'] ?>]" 
                                        value="<?= $nilaiMap[$m['id']] ?? '' ?>" required
                                        class="w-full px-2 py-1.5 text-center text-sm border border-blue-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white">
                                </td>
                                <td class="px-1 py-2 text-center bg-emerald-50/30">
                                    <input type="number" step="0.01" name="nilai_psaj[<?= $m['id'] ?>]" 
                                        value="<?= $psajMap[$m['id']] ?? '' ?>"
                                        class="w-full px-2 py-1.5 text-center text-sm border border-emerald-200 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 bg-white">
                                </td>
                                <?php for($i=1; $i<=6; $i++): ?>
                                <td class="px-1 py-2 text-center">
                                    <input type="number" step="0.01" name="semester[<?= $m['id'] ?>][s<?= $i ?>]" 
                                        value="<?= isset($sem['s'.$i]) ? (float)$sem['s'.$i] : '' ?>"
                                        class="w-full px-2 py-1.5 text-center text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                                </td>
                                <?php endfor; ?>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <div class="px-6 py-6 bg-gray-50 border-t border-gray-200 text-right">
                    <button type="submit" class="inline-flex items-center px-10 py-3 bg-primary-600 text-white font-bold rounded-xl hover:bg-primary-700 transition-all shadow-lg shadow-primary-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M7.707 10.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 11.586V4a1 1 0 10-2 0v7.586l-1.293-1.293z" />
                            <path d="M5 5a2 2 0 00-2 2v8a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-1.586l-1 1H5z" />
                        </svg>
                        Simpan Semua Nilai
                    </button>
                </div>
            </div>
        </form>
    </div>
</main>
