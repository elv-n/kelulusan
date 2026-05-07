<main class="flex-grow py-8">
    <div class="max-w-6xl mx-auto px-4 sm:px-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
            <div>
                <h2 class="text-2xl font-semibold text-gray-900">Cetak SKL</h2>
                <p class="text-sm text-gray-500 mt-1">Cetak Surat Keterangan Lulus per kelas</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="dashboard.php" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                    &larr; Dashboard
                </a>
            </div>
        </div>

        <!-- Filter Kelas -->
        <div class="bg-white border border-gray-200 rounded-xl p-6 mb-6 shadow-sm">
            <form action="" method="GET" class="flex flex-col md:flex-row items-end gap-3">
                <div class="flex-grow">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Kelas</label>
                    <select name="kelas" required onchange="this.form.submit()"
                        class="w-full px-4 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white">
                        <option value="">-- Pilih Kelas --</option>
                        <?php foreach ($kelasList as $k): ?>
                            <option value="<?= htmlspecialchars($k['kelas']) ?>" <?= $kelas == $k['kelas'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($k['kelas']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="w-full md:w-auto">
                    <button type="submit"
                        class="w-full md:w-auto inline-flex items-center justify-center px-4 py-2 text-sm bg-primary-600 text-white font-medium rounded-lg hover:bg-primary-700 transition-colors shadow-sm">
                        Tampilkan Siswa
                    </button>
                </div>
            </form>
        </div>

        <?php if ($kelas): ?>
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-200 flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900">Daftar Siswa Kelas: <?= htmlspecialchars($kelas) ?></h3>
                        <p class="text-sm text-gray-500">Hanya menampilkan siswa dengan status <strong>LULUS</strong></p>
                    </div>
                    <div>
                        <a href="../generate_skl.php?kelas=<?= urlencode($kelas) ?>" target="_blank" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors shadow-sm">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                            </svg>
                            Cetak Semua (Kelas <?= htmlspecialchars($kelas) ?>)
                        </a>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-200 text-left">
                                <th class="px-3 py-2 text-xs font-medium text-gray-500 uppercase w-12 text-center">No</th>
                                <th class="px-3 py-2 text-xs font-medium text-gray-500 uppercase w-32">NISN</th>
                                <th class="px-3 py-2 text-xs font-medium text-gray-500 uppercase">Nama Siswa</th>
                                <th class="px-3 py-2 text-center text-xs font-medium text-gray-500 uppercase w-48">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <?php if (empty($siswa)): ?>
                                <tr>
                                    <td colspan="4" class="px-6 py-12 text-center text-gray-400 italic">Tidak ada siswa lulus di kelas ini.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($siswa as $index => $s): ?>
                                    <tr class="hover:bg-gray-50 transition-colors border-b border-gray-100 last:border-0">
                                        <td class="px-3 py-1.5 text-xs text-center text-gray-400"><?= $index + 1 ?></td>
                                        <td class="px-3 py-1.5 text-xs font-mono text-gray-600"><?= htmlspecialchars($s['nisn']) ?></td>
                                        <td class="px-3 py-1.5 text-sm font-medium text-gray-900"><?= htmlspecialchars($s['nama']) ?></td>
                                        <td class="px-3 py-1.5 text-center">
                                            <div class="flex items-center justify-center gap-1.5">
                                                <a href="skl_nilai.php?nisn=<?= $s['nisn'] ?>" class="inline-flex items-center px-2 py-1 bg-blue-50 text-blue-700 text-[11px] font-bold rounded hover:bg-blue-100 transition-colors border border-blue-100">
                                                    Edit Nilai
                                                </a>
                                                <a href="../generate_skl.php?nisn=<?= $s['nisn'] ?>" target="_blank" class="inline-flex items-center px-2 py-1 bg-emerald-50 text-emerald-700 text-[11px] font-bold rounded hover:bg-emerald-100 transition-colors border border-emerald-100">
                                                    Cetak SKL
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php else: ?>
            <div class="text-center py-20 bg-white border border-dashed border-gray-300 rounded-xl">
                <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada kelas dipilih</h3>
                <p class="mt-1 text-sm text-gray-500">Silakan pilih kelas untuk menampilkan daftar siswa yang akan dicetak SKL-nya.</p>
            </div>
        <?php endif; ?>
    </div>
</main>