<main class="flex-grow">
    <section class="py-8">
        <div class="max-w-6xl mx-auto px-4 sm:px-6">
            <div class="mb-6 text-center">
                <h2 class="text-2xl font-semibold text-gray-900">Import Data Siswa & Nilai</h2>
                <p class="text-sm text-gray-500 mt-1">Sheet 1: Identitas + Nilai SKL &nbsp;|&nbsp; Sheet 2 (opsional): Nilai Transkrip S1-S6</p>
                <div class="mt-4">
                    <a href="download_template.php" class="inline-flex items-center px-4 py-2 bg-emerald-50 text-emerald-700 border border-emerald-200 text-sm font-medium rounded-lg hover:bg-emerald-100 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        Download Template Excel
                    </a>
                </div>
            </div>

            <form method="POST" enctype="multipart/form-data" class="bg-white border border-gray-200 rounded-lg p-6">
                <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
                    <div class="md:col-span-8">
                        <label for="file_excel" class="block text-sm font-medium text-gray-700 mb-1">Upload File Excel (.xlsx)</label>
                        <input type="file" id="file_excel" name="file_excel" accept=".xlsx"
                            class="w-full h-[42px] px-3 py-2 border border-gray-300 rounded-lg text-sm file:mr-4 file:py-1 file:px-3 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100"
                            required>
                    </div>
                    <div class="md:col-span-2">
                        <button type="submit" name="preview"
                            class="w-full h-[42px] px-4 border border-gray-300 text-sm font-medium text-gray-700 rounded-lg hover:bg-gray-50 transition-colors flex items-center justify-center">
                            Preview
                        </button>
                    </div>
                    <div class="md:col-span-2">
                        <button type="submit" name="import"
                            class="w-full h-[42px] px-4 bg-primary-600 text-white text-sm font-medium rounded-lg hover:bg-primary-700 transition-colors flex items-center justify-center">
                            Import
                        </button>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row items-center justify-center mt-6 pt-6 border-t border-gray-100">
                    <a href="dashboard.php" class="text-sm text-gray-500 hover:text-gray-700 transition-colors underline underline-offset-2">
                        Kembali ke Dashboard
                    </a>
                </div>
            </form>

        </div>

        <?php if (!empty($importErrors)): ?>
            <div class="max-w-6xl mx-auto px-4 sm:px-6 mt-6">
                <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-md shadow-sm">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">Terdapat beberapa masalah saat import:</h3>
                            <div class="mt-2 text-sm text-red-700">
                                <ul class="list-disc pl-5 space-y-1 max-h-60 overflow-y-auto pr-4">
                                    <?php foreach ($importErrors as $error): ?>
                                        <li><?= htmlspecialchars($error) ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <?php if (!empty($previewData) && count($previewData) > 0): ?>
            <div class="max-w-6xl mx-auto px-4 sm:px-6 mt-8">
                <h3 class="text-lg font-medium text-gray-900 text-center mb-4">Preview Data Siswa</h3>
                <div class="bg-white border border-gray-200 rounded-lg overflow-hidden overflow-x-auto">
                    <table class="w-full">
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
                        <tbody class="divide-y divide-gray-100">
                            <?php foreach ($previewData as $index => $row): ?>
                                <?php if ($index == 0) continue; ?>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-3 py-2 text-center text-xs text-gray-400"><?= $index ?></td>
                                    <td class="px-3 py-2 text-sm font-mono text-gray-600"><?= htmlspecialchars($row[0]) ?></td>
                                    <td class="px-3 py-2 text-sm font-medium text-gray-900"><?= htmlspecialchars($row[2]) ?></td>
                                    <td class="px-3 py-2 text-xs text-gray-500"><?= htmlspecialchars($row[3]) ?>, <?= htmlspecialchars($row[4]) ?></td>
                                    <td class="px-3 py-2 text-left text-xs text-gray-500"><?= htmlspecialchars($row[5]) ?></td>
                                    <td class="px-3 py-2 text-left text-xs text-gray-500"><?= htmlspecialchars($row[6]) ?></td>
                                    <td class="px-3 py-2 text-left whitespace-nowrap"><span class="inline-block px-2 py-0.5 text-xs font-medium bg-gray-100 text-gray-600 rounded"><?= htmlspecialchars($row[8]) ?></span></td>
                                    <td class="px-3 py-2 text-left text-xs font-medium text-gray-700"><?= htmlspecialchars($row[12]) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            </div>
        <?php endif; ?>
    </section>
</main>

<!-- Toast Notifications (replaces SweetAlert2) -->
<?php if ($notif == 'nofile'): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            showToast('Pilih file terlebih dahulu!', 'warning');
        });
    </script>
<?php elseif ($notif == 'success'): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            showToast('Import data berhasil tanpa error!', 'success');
            setTimeout(function() {
                window.location = 'dashboard.php';
            }, 2000);
        });
    </script>
<?php elseif ($notif == 'partial'): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            showToast('Proses selesai, namun terdapat error pada beberapa data. Cek rinciannya.', 'warning');
        });
    </script>
<?php endif; ?>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('form');
        form.addEventListener('submit', function(e) {
            if (e.submitter && e.submitter.name === 'import') {
                const btn = e.submitter;
                btn.innerHTML = `<svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Memproses...`;
                btn.classList.add('opacity-75', 'cursor-not-allowed');
                setTimeout(() => {
                    btn.disabled = true;
                }, 50);
            } else if (e.submitter && e.submitter.name === 'preview') {
                const btn = e.submitter;
                btn.innerHTML = 'Memuat...';
                btn.classList.add('opacity-75', 'cursor-not-allowed');
                setTimeout(() => {
                    btn.disabled = true;
                }, 50);
            }
        });
    });
</script>