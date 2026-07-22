<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { dashboard, login } from '@/routes';

withDefaults(
    defineProps<{
        canRegister?: boolean;
    }>(),
    {
        canRegister: false,
    },
);
</script>

<template>
    <Head title="Selamat Datang - Aplikasi Pendataan Warga TP-PKK" />

    <div class="relative min-h-screen w-full overflow-x-hidden bg-slate-50 text-slate-800 dark:bg-zinc-950 dark:text-slate-100 font-sans selection:bg-emerald-500/30">
        <!-- Background Effects -->
        <div class="pointer-events-none absolute -top-20 -left-20 h-96 w-96 rounded-full bg-emerald-400/20 blur-3xl mix-blend-multiply dark:mix-blend-screen animate-pulse"></div>
        <div class="pointer-events-none absolute top-40 -right-20 h-[30rem] w-[30rem] rounded-full bg-teal-400/20 blur-3xl mix-blend-multiply dark:mix-blend-screen animate-pulse" style="animation-delay: 2s;"></div>
        <div class="pointer-events-none absolute -bottom-40 left-1/3 h-80 w-80 rounded-full bg-cyan-400/20 blur-3xl mix-blend-multiply dark:mix-blend-screen animate-pulse" style="animation-delay: 4s;"></div>

        <!-- Header -->
        <header class="relative z-10 w-full px-6 py-4 md:px-12 md:py-6 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-emerald-400 to-teal-600 shadow-lg shadow-emerald-500/30">
                    <svg class="h-6 w-6 text-white drop-shadow" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-[14px] font-black leading-tight tracking-wide text-emerald-900 dark:text-emerald-100">TP-PKK</p>
                    <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-emerald-600 dark:text-emerald-400">Desa Dompas</p>
                </div>
            </div>

            <nav class="flex items-center gap-4">
                <Link
                    v-if="$page.props.auth.user"
                    :href="dashboard()"
                    class="rounded-full bg-emerald-600 px-6 py-2 text-sm font-semibold text-white shadow-md transition-all hover:bg-emerald-700 hover:shadow-lg hover:shadow-emerald-500/30 dark:bg-emerald-500 dark:hover:bg-emerald-400"
                >
                    Dashboard
                </Link>
                <template v-else>
                    <Link
                        :href="login()"
                        class="rounded-full bg-emerald-600 px-6 py-2 text-sm font-semibold text-white shadow-md transition-all hover:-translate-y-0.5 hover:bg-emerald-700 hover:shadow-lg hover:shadow-emerald-500/30 dark:bg-emerald-500 dark:hover:bg-emerald-400"
                    >
                        Masuk
                    </Link>
                </template>
            </nav>
        </header>

        <!-- Main Content -->
        <main class="relative z-10 flex flex-col items-center justify-center min-h-[calc(100vh-160px)] px-6 text-center">
            
            <!-- Hero Section -->
            <div class="max-w-4xl mx-auto space-y-8 animate-fade-in-up">
                <div class="mx-auto inline-flex items-center gap-2 rounded-full border border-emerald-200 bg-emerald-50/50 px-4 py-1.5 text-xs font-semibold text-emerald-700 backdrop-blur-md dark:border-emerald-800/50 dark:bg-emerald-900/20 dark:text-emerald-300">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex h-2 w-2 rounded-full bg-emerald-500"></span>
                    </span>
                    Sistem Digitalisasi Desa
                </div>

                <h1 class="text-4xl md:text-5xl font-extrabold tracking-tight text-slate-900 dark:text-white leading-tight">
                    Aplikasi Pendataan Warga TP-PKK <br class="hidden md:block"/>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-500 to-teal-500 text-2xl md:text-4xl mt-2 block">Berbasis Website Pada Dasawisma Desa Dompas</span>
                </h1>

                <p class="mx-auto max-w-2xl text-lg md:text-xl text-slate-600 dark:text-slate-300 leading-relaxed">
                    Platform digital terpadu untuk pendataan kependudukan yang cepat, akurat, dan transparan mulai dari tingkat lingkungan keluarga (Dasawisma).
                </p>

                <div class="flex flex-col sm:flex-row items-center justify-center gap-4 pt-4">
                    <Link
                        v-if="$page.props.auth.user"
                        :href="dashboard()"
                        class="w-full sm:w-auto rounded-full bg-gradient-to-r from-emerald-500 to-teal-600 px-8 py-3.5 text-base font-semibold text-white shadow-xl shadow-emerald-500/20 transition-all hover:-translate-y-1 hover:shadow-2xl hover:shadow-emerald-500/40"
                    >
                        Masuk ke Dashboard
                    </Link>
                    <Link
                        v-else
                        :href="login()"
                        class="w-full sm:w-auto rounded-full bg-gradient-to-r from-emerald-500 to-teal-600 px-8 py-3.5 text-base font-semibold text-white shadow-xl shadow-emerald-500/20 transition-all hover:-translate-y-1 hover:shadow-2xl hover:shadow-emerald-500/40"
                    >
                        Masuk ke Sistem
                    </Link>
                    
                    <a
                        href="#fitur"
                        class="w-full sm:w-auto rounded-full border-2 border-slate-200 px-8 py-3 text-base font-semibold text-slate-700 transition-all hover:border-emerald-500 hover:text-emerald-600 dark:border-zinc-800 dark:text-slate-300 dark:hover:border-emerald-500 dark:hover:text-emerald-400"
                    >
                        Pelajari Fitur
                    </a>
                </div>
            </div>

            <!-- Features Grid -->
            <div id="fitur" class="mx-auto mt-32 grid w-full max-w-6xl gap-8 pb-20 md:grid-cols-3">
                <!-- Card 1 -->
                <div class="group relative rounded-3xl border border-slate-200 bg-white/50 p-8 text-left shadow-lg backdrop-blur-xl transition-all hover:-translate-y-2 hover:shadow-xl dark:border-zinc-800 dark:bg-zinc-900/50">
                    <div class="mb-6 flex h-14 w-14 items-center justify-center rounded-2xl bg-emerald-100 text-emerald-600 transition-transform group-hover:scale-110 dark:bg-emerald-900/50 dark:text-emerald-400">
                        <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                        </svg>
                    </div>
                    <h3 class="mb-3 text-xl font-bold text-slate-900 dark:text-white">Pendataan Mudah</h3>
                    <p class="leading-relaxed text-slate-600 dark:text-slate-400">
                        Kader Dasawisma dapat melakukan input data warga, rumah tangga, dan kegiatan kelompok dengan formulir digital yang super ringan dan responsif.
                    </p>
                </div>

                <!-- Card 2 -->
                <div class="group relative rounded-3xl border border-slate-200 bg-white/50 p-8 text-left shadow-lg backdrop-blur-xl transition-all hover:-translate-y-2 hover:shadow-xl dark:border-zinc-800 dark:bg-zinc-900/50">
                    <div class="mb-6 flex h-14 w-14 items-center justify-center rounded-2xl bg-teal-100 text-teal-600 transition-transform group-hover:scale-110 dark:bg-teal-900/50 dark:text-teal-400">
                        <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="mb-3 text-xl font-bold text-slate-900 dark:text-white">Verifikasi Tersistem</h3>
                    <p class="leading-relaxed text-slate-600 dark:text-slate-400">
                        Data yang masuk akan melalui proses verifikasi berjenjang oleh Admin dan Kepala Desa untuk menjamin keakuratan informasi.
                    </p>
                </div>

                <!-- Card 3 -->
                <div class="group relative rounded-3xl border border-slate-200 bg-white/50 p-8 text-left shadow-lg backdrop-blur-xl transition-all hover:-translate-y-2 hover:shadow-xl dark:border-zinc-800 dark:bg-zinc-900/50">
                    <div class="mb-6 flex h-14 w-14 items-center justify-center rounded-2xl bg-cyan-100 text-cyan-600 transition-transform group-hover:scale-110 dark:bg-cyan-900/50 dark:text-cyan-400">
                        <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <h3 class="mb-3 text-xl font-bold text-slate-900 dark:text-white">Pelaporan Otomatis</h3>
                    <p class="leading-relaxed text-slate-600 dark:text-slate-400">
                        Dashboard analitik dan rekap laporan data Dasawisma dapat langsung dicetak secara real-time kapan saja dibutuhkan oleh pengguna.
                    </p>
                </div>
            </div>
            
        </main>

        <!-- Footer -->
        <footer class="relative z-10 w-full border-t border-slate-200 bg-white/50 py-8 text-center backdrop-blur-xl dark:border-zinc-800 dark:bg-zinc-950/50">
            <p class="text-sm font-medium text-slate-500 dark:text-slate-400">
                &copy; {{ new Date().getFullYear() }} Pemerintah Desa Dompas. Hak Cipta Dilindungi.
            </p>
        </footer>
    </div>
</template>

<style>
@keyframes fade-in-up {
    from {
        opacity: 0;
        transform: translateY(30px) scale(0.95);
    }
    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}
.animate-fade-in-up {
    animation: fade-in-up 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}

/* Smooth scrolling for anchor links */
html {
    scroll-behavior: smooth;
}
</style>
