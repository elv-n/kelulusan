<main class="flex-grow flex items-center justify-center py-8 px-4">
    <section class="w-full max-w-md mx-auto">
        <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
            <div class="p-8">
                <div class="text-center mb-6">
                    <h2 class="text-2xl font-semibold text-gray-900">Cek NISN</h2>
                </div>

                <?php if (!empty($alertMessage)): ?>
                    <div id="alertMessage"
                         class="mb-4 px-4 py-3 rounded-lg text-sm font-medium border
                         <?= $alertType === 'success' ? 'bg-green-50 border-green-200 text-green-700' : 'bg-red-50 border-red-200 text-red-700' ?>">
                        <?= $alertMessage ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="">
                    <div class="space-y-4">
                        <div>
                            <label for="kelasSelect" class="block text-sm font-medium text-gray-700 mb-1">
                                Kelas <span class="text-red-500">*</span>
                            </label>
                            <select id="kelasSelect" name="kelas" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white" required>
                                <option value="">-- Pilih Kelas --</option>
                                <?php foreach ($kelasOptions as $kelas): ?>
                                    <option value="<?= htmlspecialchars($kelas) ?>"
                                        <?= (isset($_POST['kelas']) && $_POST['kelas'] == $kelas) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($kelas) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div>
                            <label for="namaSelect" class="block text-sm font-medium text-gray-700 mb-1">
                                Nama Lengkap <span class="text-red-500">*</span>
                            </label>
                            <select id="namaSelect" name="nama" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white" required disabled>
                                <option value="">-- Pilih Kelas Terlebih Dahulu --</option>
                                <?php
                                // Restore selected name if available and a class was selected
                                if (isset($_POST['kelas']) && isset($_POST['nama']) && isset($kelasNamaMap[$_POST['kelas']])) {
                                    foreach ($kelasNamaMap[$_POST['kelas']] as $nama) {
                                        $selected = ($_POST['nama'] == $nama) ? 'selected' : '';
                                        echo "<option value=\"{$nama}\" {$selected}>{$nama}</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>

                        <div>
                            <label for="tgl" class="block text-sm font-medium text-gray-700 mb-1">
                                Tanggal Lahir <span class="text-red-500">*</span>
                            </label>
                            <input type="date" id="tgl" name="tgl"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                                   value="<?= isset($_POST['tgl']) ? htmlspecialchars($_POST['tgl']) : '' ?>" required>
                        </div>

                        <div>
                            <button type="submit"
                                    class="w-full px-4 py-2 bg-primary-600 text-white text-sm font-medium rounded-lg hover:bg-primary-700 transition-colors">
                                Cek NISN
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
</main>

<script>
    window.kelasNamaMap = <?= json_encode($kelasNamaMap) ?>;
</script>
