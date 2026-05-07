<section class="py-6 px-4">
    <div class="max-w-2xl md:max-w-3xl mx-auto">
        <!-- Logo Mobile -->

        <?php
        $isLulus = $data['status_kelulusan'] === 'LULUS';
        $isTidakLulus = $data['status_kelulusan'] === 'TIDAK LULUS';
        $isBelum = $data['status_kelulusan'] === '-';

        if ($isLulus) {
            $statusBg = 'bg-emerald-600';
            $statusText = 'LULUS';
        } elseif ($isTidakLulus) {
            $statusBg = 'bg-red-600';
            $statusText = 'TIDAK LULUS';
        } else {
            $statusBg = 'bg-amber-500';
            $statusText = '-';
        }
        ?>

        <!-- Unified Card -->
        <div class="bg-white border border-gray-200 rounded-2xl shadow-lg overflow-hidden mb-6">

            <!-- Header -->
            <div class="px-6 py-5 border-b border-gray-100 text-center bg-gray-50/50">
                <h2 class="text-xl font-bold text-gray-900 tracking-tight">Hasil Kelulusan</h2>
                <p class="text-xs font-medium text-gray-500 mt-1 uppercase tracking-wider">Tahun Ajaran <?= htmlspecialchars(TAHUN_AJARAN) ?></p>
            </div>

            <!-- Identitas -->
            <div class="px-2 sm:px-4 py-2">
                <table class="w-full text-sm">
                    <tbody class="divide-y divide-gray-100/80">
                        <tr>
                            <th class="px-4 py-1.5 text-left text-xs font-semibold text-gray-500 align-top w-1/3 whitespace-nowrap">NISN</th>
                            <td class="px-4 py-1.5 text-right font-bold text-gray-900"><?= htmlspecialchars($data['nisn']) ?></td>
                        </tr>
                        <tr>
                            <th class="px-4 py-1.5 text-left text-xs font-semibold text-gray-500 align-top whitespace-nowrap">Nama Lengkap</th>
                            <td class="px-4 py-1.5 text-right font-bold text-gray-900"><?= htmlspecialchars($data['nama']) ?></td>
                        </tr>
                        <tr>
                            <th class="px-4 py-1.5 text-left text-xs font-semibold text-gray-500 align-top whitespace-nowrap">Tempat, Tgl Lahir</th>
                            <?php
                            $bulanIndo = [
                                1 => 'Januari', 2 => 'Februari', 3 => 'Maret',
                                4 => 'April', 5 => 'Mei', 6 => 'Juni',
                                7 => 'Juli', 8 => 'Agustus', 9 => 'September',
                                10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                            ];
                            $tgl = strtotime($data['tanggal_lahir']);
                            $tanggalFormatted = date('d', $tgl) . ' ' . $bulanIndo[(int)date('n', $tgl)] . ' ' . date('Y', $tgl);
                            ?>
                            <td class="px-4 py-1.5 text-right font-medium text-gray-800"><?= htmlspecialchars($data['tempat_lahir']) . ', ' . $tanggalFormatted ?></td>
                        </tr>
                        <tr>
                            <th class="px-4 py-1.5 text-left text-xs font-semibold text-gray-500 align-top whitespace-nowrap">Jenis Kelamin</th>
                            <td class="px-4 py-1.5 text-right font-medium text-gray-800"><?= htmlspecialchars($data['jenis_kelamin']) ?></td>
                        </tr>
                        <tr>
                            <th class="px-4 py-1.5 text-left text-xs font-semibold text-gray-500 align-top whitespace-nowrap">Program Keahlian</th>
                            <td class="px-4 py-1.5 text-right font-medium text-gray-800"><?= htmlspecialchars($data['program_keahlian']) ?></td>
                        </tr>
                        <tr>
                            <th class="px-4 py-1.5 text-left text-xs font-semibold text-gray-500 align-top whitespace-nowrap">Kelas</th>
                            <td class="px-4 py-1.5 text-right font-medium text-gray-800"><?= htmlspecialchars($data['kelas']) ?></td>
                        </tr>
                        <tr>
                            <th class="px-4 py-1.5 text-left text-xs font-semibold text-gray-500 align-top whitespace-nowrap">Nama Ibu</th>
                            <td class="px-4 py-1.5 text-right font-medium text-gray-800"><?= htmlspecialchars($data['nama_ibu']) ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Status Banner -->
            <div class="<?= $statusBg ?> px-6 py-5 text-center mt-2">
                <span class="block text-xs font-bold text-white/80 uppercase tracking-widest mb-1">Status Anda</span>
                <h3 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">
                    <?= $statusText ?>
                </h3>
            </div>
        </div>

        <!-- Keterangan Card (Tampil jika ada dan tidak lulus) -->
        <?php if (!$isLulus && !empty($data['keterangan_status'])): ?>
            <div class="bg-white border border-gray-200 rounded-2xl shadow-lg overflow-hidden mb-6">
                <div class="px-5 py-3 bg-red-50 border-b border-red-100 text-red-800 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0 text-red-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="8" x2="12" y2="12"></line>
                        <line x1="12" y1="16" x2="12.01" y2="16"></line>
                    </svg>
                    <span class="font-bold text-sm uppercase tracking-wide">Catatan</span>
                </div>
                <div class="p-5 text-sm text-gray-700 leading-relaxed bg-gray-50/30">
                    <?= nl2br(htmlspecialchars($data['keterangan_status'])) ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- Tombol Kembali -->
        <div class="text-center mt-6">
            <a href="<?= base_url('index.php') ?>"
                class="inline-flex items-center gap-2 px-6 py-2.5 bg-white border border-gray-300 text-gray-700 text-sm font-semibold rounded-xl hover:bg-gray-50 hover:text-gray-900 transition-colors shadow-sm focus:ring-2 focus:ring-gray-200 focus:outline-none">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="19" y1="12" x2="5" y2="12"></line>
                    <polyline points="12 19 5 12 12 5"></polyline>
                </svg>
                Kembali ke Beranda
            </a>
        </div>

    </div>
</section>

<!-- Toast notification for status -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var status = "<?= $data['status_kelulusan'] ?>";

        if (status === "LULUS") {
            showToast('Selamat! Anda dinyatakan LULUS', 'success', 5000);
        } else if (status === "TIDAK LULUS") {
            showToast('Maaf, Anda dinyatakan TIDAK LULUS', 'error', 5000);
        } else if (status === "-") {
            showToast('Status belum dinyatakan, segera penuhi kewajiban Anda', 'warning', 5000);
        }
    });
</script>