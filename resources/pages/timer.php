<section class="timer-bg relative flex flex-col flex-1 w-full items-center justify-center min-h-[75vh] py-20 px-4 overflow-hidden">
    <!-- Dark overlay to make text pop -->
    <div class="absolute inset-0 bg-black/70 pointer-events-none z-10"></div>

    <div class="container max-w-4xl mx-auto relative z-20 flex flex-col items-center">
        <!-- Badge -->
        <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-black/40 backdrop-blur-md border border-white/20 text-white text-xs sm:text-sm font-medium mb-6 sm:mb-10 shadow-sm">
            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
            Tahun Ajaran <?= htmlspecialchars(TAHUN_AJARAN) ?>
        </div>

        <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-white mb-8 sm:mb-12 text-center drop-shadow-md leading-tight">
            Pengumuman kelulusan dimulai dalam
        </h2>

        <!-- Timer blocks container -->
        <div id="countdown-container" class="flex flex-wrap justify-center gap-3 sm:gap-6" data-api-url="<?= base_url('api/check-time.php') ?>">

            <!-- Hari -->
            <div class="flex flex-col items-center">
                <div class="w-20 h-20 sm:w-28 sm:h-28 flex items-center justify-center bg-white rounded-2xl mb-3 sm:mb-4 shadow-[0_8px_30px_rgb(0,0,0,0.5)]">
                    <span id="t-days" class="text-4xl sm:text-6xl font-extrabold text-emerald-700 tracking-tight">00</span>
                </div>
                <span class="text-xs sm:text-sm font-bold text-white uppercase tracking-widest drop-shadow-md">Hari</span>
            </div>

            <!-- Separator -->
            <div class="text-3xl sm:text-5xl font-light text-white/30 self-start mt-4 sm:mt-6 hidden sm:block">:</div>

            <!-- Jam -->
            <div class="flex flex-col items-center">
                <div class="w-20 h-20 sm:w-28 sm:h-28 flex items-center justify-center bg-white rounded-2xl mb-3 sm:mb-4 shadow-[0_8px_30px_rgb(0,0,0,0.5)]">
                    <span id="t-hours" class="text-4xl sm:text-6xl font-extrabold text-emerald-700 tracking-tight">00</span>
                </div>
                <span class="text-xs sm:text-sm font-bold text-white uppercase tracking-widest drop-shadow-md">Jam</span>
            </div>

            <!-- Separator -->
            <div class="text-3xl sm:text-5xl font-light text-white/30 self-start mt-4 sm:mt-6 hidden sm:block">:</div>

            <!-- Menit -->
            <div class="flex flex-col items-center">
                <div class="w-20 h-20 sm:w-28 sm:h-28 flex items-center justify-center bg-white rounded-2xl mb-3 sm:mb-4 shadow-[0_8px_30px_rgb(0,0,0,0.5)]">
                    <span id="t-minutes" class="text-4xl sm:text-6xl font-extrabold text-emerald-700 tracking-tight">00</span>
                </div>
                <span class="text-xs sm:text-sm font-bold text-white uppercase tracking-widest drop-shadow-md">Menit</span>
            </div>

            <!-- Separator -->
            <div class="text-3xl sm:text-5xl font-light text-white/30 self-start mt-4 sm:mt-6 hidden sm:block">:</div>

            <!-- Detik -->
            <div class="flex flex-col items-center group">
                <div class="relative w-20 h-20 sm:w-28 sm:h-28 flex items-center justify-center bg-emerald-500 rounded-2xl mb-3 sm:mb-4 shadow-[0_8px_30px_rgb(16,185,129,0.5)]">
                    <!-- Detik pulse effect -->
                    <div class="absolute inset-0 rounded-2xl border-4 border-emerald-400 animate-ping opacity-50"></div>
                    <span id="t-seconds" class="text-4xl sm:text-6xl font-extrabold text-white tracking-tight">00</span>
                </div>
                <span class="text-xs sm:text-sm font-bold text-emerald-400 uppercase tracking-widest drop-shadow-md">Detik</span>
            </div>
        </div>

        <!-- Status Loading / Done -->
        <h1 id="countdown-status" class="hidden text-3xl sm:text-4xl font-bold text-white tracking-wide mt-12 drop-shadow-lg"></h1>
    </div>


</section>