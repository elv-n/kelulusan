<!-- Himbauan Kelulusan — Alert Modal -->
<div id="himbauanBackdrop" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-[60] transition-opacity duration-300" onclick="closeHimbauan()"></div>

<div id="himbauanModal" class="fixed inset-0 z-[61] flex items-center justify-center p-4 pointer-events-none">
    <div class="himbauan-modal bg-white rounded-2xl shadow-2xl w-full max-w-md pointer-events-auto overflow-hidden">

        <!-- Alert Header (amber) -->
        <div class="bg-amber-50 border-b border-amber-200 px-5 py-4 flex items-start gap-3">
            <span class="shrink-0 flex items-center justify-center w-9 h-9 rounded-full bg-amber-100 mt-0.5">
                <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"></path>
                </svg>
            </span>
            <div class="flex-1">
                <h3 class="text-sm font-bold text-amber-900">Himbauan Kelulusan</h3>
                <p class="text-xs text-amber-700 mt-0.5">Seluruh siswa WAJIB menjaga ketertiban dan keamanan</p>
            </div>
            <button type="button" onclick="closeHimbauan()" class="shrink-0 w-7 h-7 flex items-center justify-center rounded-md hover:bg-amber-200/60 text-amber-400 hover:text-amber-600 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <!-- List sederhana -->
        <div class="px-5 py-4">
            <ul class="space-y-2.5 text-sm text-gray-700">
                <li class="flex items-start gap-2.5">
                    <span class="shrink-0 w-5 h-5 flex items-center justify-center rounded-full bg-red-100 text-red-500 mt-0.5">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </span>
                    <span><strong class="text-red-600">DILARANG</strong> melakukan konvoi atau arak-arakan di jalan raya.</span>
                </li>
                <li class="flex items-start gap-2.5">
                    <span class="shrink-0 w-5 h-5 flex items-center justify-center rounded-full bg-red-100 text-red-500 mt-0.5">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </span>
                    <span><strong class="text-red-600">DILARANG</strong> melakukan coret-coret seragam maupun fasilitas umum.</span>
                </li>
                <li class="flex items-start gap-2.5">
                    <span class="shrink-0 w-5 h-5 flex items-center justify-center rounded-full bg-red-100 text-red-500 mt-0.5">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </span>
                    <span><strong class="text-red-600">DILARANG</strong> melakukan tindakan melanggar hukum (balap liar, knalpot tidak standar, konsumsi zat terlarang, dll).</span>
                </li>
                <li class="flex items-start gap-2.5">
                    <span class="shrink-0 w-5 h-5 flex items-center justify-center rounded-full bg-emerald-100 text-emerald-500 mt-0.5">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    </span>
                    <span><strong class="text-emerald-600">WAJIB</strong> menjaga nama baik sekolah dan diri sendiri.</span>
                </li>
            </ul>
        </div>

        <!-- Footer -->
        <div class="px-5 pb-5">
            <div class="rounded-lg bg-primary-50 border border-primary-100 p-3 text-center mb-3">
                <p class="text-xs font-medium text-primary-800 leading-relaxed">
                    Rayakan kelulusan secara <strong>bijak</strong>, <strong>aman</strong>, dan <strong>bertanggung jawab</strong>.
                </p>
            </div>
            <button type="button" onclick="closeHimbauan()" class="w-full py-2.5 rounded-xl bg-gray-100 hover:bg-gray-200 text-sm font-semibold text-gray-700 transition-colors">
                Saya Mengerti
            </button>
        </div>

    </div>
</div>

<!-- Tombol buka ulang -->
<button type="button" id="himbauanReopen" onclick="openHimbauan()" class="fixed bottom-6 left-6 z-50 hidden items-center gap-2 px-4 py-2.5 rounded-full bg-white/95 backdrop-blur-md border border-amber-200 shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-300 himbauan-toggle-pulse">
    <span class="flex items-center justify-center w-6 h-6 rounded-full bg-amber-100">
        <svg class="w-3.5 h-3.5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>
    </span>
    <span class="text-xs font-bold text-gray-700">Himbauan</span>
</button>

<script>
function closeHimbauan() {
    var modal = document.querySelector('.himbauan-modal');
    var backdrop = document.getElementById('himbauanBackdrop');
    var reopen = document.getElementById('himbauanReopen');
    modal.style.opacity = '0';
    modal.style.transform = 'scale(0.95) translateY(10px)';
    backdrop.style.opacity = '0';
    setTimeout(function() {
        document.getElementById('himbauanModal').style.display = 'none';
        backdrop.style.display = 'none';
        reopen.classList.remove('hidden');
        reopen.classList.add('flex');
        document.body.style.overflow = '';
    }, 250);
}
function openHimbauan() {
    var wrap = document.getElementById('himbauanModal');
    var modal = document.querySelector('.himbauan-modal');
    var backdrop = document.getElementById('himbauanBackdrop');
    var reopen = document.getElementById('himbauanReopen');
    reopen.classList.add('hidden');
    reopen.classList.remove('flex');
    backdrop.style.display = '';
    wrap.style.display = '';
    document.body.style.overflow = 'hidden';
    requestAnimationFrame(function() {
        backdrop.style.opacity = '1';
        modal.style.opacity = '1';
        modal.style.transform = 'scale(1) translateY(0)';
    });
}
document.body.style.overflow = 'hidden';
</script>
