<main class="flex-grow py-8">
    <div class="max-w-6xl mx-auto px-4 sm:px-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-2xl font-semibold text-gray-900">Mata Pelajaran SKL</h2>
                <p class="text-sm text-gray-500 mt-1">Kelola daftar mata pelajaran yang tampil di SKL</p>
            </div>
            <a href="dashboard.php" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                &larr; Dashboard
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Form Tambah -->
            <div class="lg:col-span-1">
                <form action="" method="POST" id="mapelForm" class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm sticky top-8">
                    <h3 id="formTitle" class="text-lg font-medium text-gray-900 mb-4">Tambah Mata Pelajaran</h3>
                    <input type="hidden" name="id" id="mapelId">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Mata Pelajaran</label>
                            <input type="text" name="nama_mapel" id="namaMapel" required
                                class="w-full px-4 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                            <select name="kategori" id="kategoriMapel" required
                                class="w-full px-4 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white">
                                <option value="Umum">Mata Pelajaran Umum</option>
                                <option value="Kejuruan">Mata Pelajaran Kejuruan</option>
                                <option value="Muatan Lokal">Muatan Lokal</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Urutan Tampil</label>
                            <input type="number" name="urutan" id="urutanMapel" value="<?= count($mapel) + 1 ?>" required
                                class="w-full px-4 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                        </div>
                        <div class="flex flex-col gap-2">
                            <button type="submit" name="add" id="submitBtn" class="w-full inline-flex items-center justify-center px-4 py-2 bg-primary-600 text-white font-medium rounded-lg hover:bg-primary-700 transition-all">
                                Tambah Mapel
                            </button>
                            <button type="button" id="cancelBtn" onclick="resetForm()" class="hidden w-full inline-flex items-center justify-center px-4 py-2 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-all">
                                Batal
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Tabel Daftar -->
            <div class="lg:col-span-2">
                <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-200">
                                <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase w-10"></th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase w-20">Urutan</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Mata Pelajaran</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kategori</th>
                                <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase w-20">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="sortable-mapel" class="divide-y divide-gray-100">
                            <?php foreach ($mapel as $row): ?>
                            <tr class="hover:bg-gray-50 transition-colors" data-id="<?= $row['id'] ?>">
                                <td class="px-4 py-3 text-center cursor-move text-gray-400">
                                    <svg class="w-5 h-5 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"></path>
                                    </svg>
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-600 mapel-urutan"><?= $row['urutan'] ?></td>
                                <td class="px-4 py-3 text-sm font-medium text-gray-900"><?= htmlspecialchars($row['nama_mapel']) ?></td>
                                <td class="px-4 py-3 text-sm">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                        <?= $row['kategori'] == 'Umum' ? 'bg-blue-100 text-blue-800' : ($row['kategori'] == 'Kejuruan' ? 'bg-purple-100 text-purple-800' : 'bg-orange-100 text-orange-800') ?>">
                                        <?= $row['kategori'] ?>
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <button type="button" 
                                            onclick="editMapel(<?= $row['id'] ?>, '<?= addslashes($row['nama_mapel']) ?>', '<?= $row['kategori'] ?>', <?= $row['urutan'] ?>)"
                                            class="text-blue-500 hover:text-blue-700" title="Edit Nama">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </button>
                                        <form action="" method="POST" onsubmit="return confirm('Hapus mata pelajaran ini?')">
                                            <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                            <button type="submit" name="delete" class="text-red-500 hover:text-red-700" title="Hapus">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- SortableJS -->
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const el = document.getElementById('sortable-mapel');
    if (el) {
        Sortable.create(el, {
            animation: 150,
            handle: '.cursor-move',
            ghostClass: 'bg-primary-50',
            onEnd: function() {
                const ids = [];
                const rows = el.querySelectorAll('tr');
                
                rows.forEach((row, index) => {
                    ids.push(row.getAttribute('data-id'));
                    const urutanCell = row.querySelector('.mapel-urutan');
                    if (urutanCell) urutanCell.textContent = index + 1;
                });

                fetch('<?= base_url("api/mapel.php") ?>', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ action: 'reorder', ids: ids })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) showToast('Urutan berhasil diperbarui', 'success');
                    else showToast(data.error || 'Gagal memperbarui urutan', 'error');
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('Terjadi kesalahan jaringan', 'error');
                });
            }
        });
    }
});

function editMapel(id, nama, kategori, urutan) {
    document.getElementById('formTitle').textContent = 'Edit Mata Pelajaran';
    document.getElementById('mapelId').value = id;
    document.getElementById('namaMapel').value = nama;
    document.getElementById('kategoriMapel').value = kategori;
    document.getElementById('urutanMapel').value = urutan;
    
    const submitBtn = document.getElementById('submitBtn');
    submitBtn.name = 'edit';
    submitBtn.textContent = 'Simpan Perubahan';
    submitBtn.classList.remove('bg-primary-600', 'hover:bg-primary-700');
    submitBtn.classList.add('bg-blue-600', 'hover:bg-blue-700');
    
    document.getElementById('cancelBtn').classList.remove('hidden');
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function resetForm() {
    document.getElementById('formTitle').textContent = 'Tambah Mata Pelajaran';
    document.getElementById('mapelId').value = '';
    document.getElementById('mapelForm').reset();
    
    const submitBtn = document.getElementById('submitBtn');
    submitBtn.name = 'add';
    submitBtn.textContent = 'Tambah Mapel';
    submitBtn.classList.add('bg-primary-600', 'hover:bg-primary-700');
    submitBtn.classList.remove('bg-blue-600', 'hover:bg-blue-700');
    
    document.getElementById('cancelBtn').classList.add('hidden');
}
</script>

