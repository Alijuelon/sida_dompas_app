<script setup lang="ts">
import { Head, usePage } from '@inertiajs/vue3';
import { computed, onMounted, ref } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';

const props = defineProps<{
    stats: {
        total_kk: number;
        total_warga: number;
        total_dasawisma: number;
        disetujui: number;
    };
}>();

const page = usePage();
const userName = computed(() => (page.props as any).auth?.user?.name ?? 'Kepala Desa');

const displayStats = ref({ total_kk: 0, total_warga: 0, total_dasawisma: 0, disetujui: 0 });

function animateCount(key: keyof typeof displayStats.value, target: number) {
    const steps = 40;
    const step = target / steps;
    let current = 0;
    const timer = setInterval(() => {
        current = Math.min(current + step, target);
        displayStats.value[key] = Math.round(current);
        if (current >= target) clearInterval(timer);
    }, 1000 / steps);
}

onMounted(() => {
    animateCount('total_kk', props.stats.total_kk);
    animateCount('total_warga', props.stats.total_warga);
    animateCount('total_dasawisma', props.stats.total_dasawisma);
    animateCount('disetujui', props.stats.disetujui);
});

const rateVerified = computed(() =>
    props.stats.total_kk > 0 ? Math.round((props.stats.disetujui / props.stats.total_kk) * 100) : 0
);
</script>

<template>
    <Head title="Dashboard Kepala Desa" />
    <AppLayout>
        <!-- Welcome Banner -->
        <div class="mb-6 overflow-hidden rounded-2xl shadow-sm" style="background: linear-gradient(135deg, #1e1b4b 0%, #4338ca 60%, #6366f1 100%);">
            <div class="px-7 py-6">
                <p class="text-sm font-medium text-indigo-300">Kepala Desa</p>
                <h1 class="mt-0.5 text-2xl font-black text-white">{{ userName }} 🏛️</h1>
                <p class="mt-1 text-sm text-indigo-200">Ringkasan data kependudukan Desa Dompas</p>
                <div class="mt-5 flex items-center gap-6 text-white">
                    <div class="text-center">
                        <p class="text-3xl font-black">{{ stats.total_kk }}</p>
                        <p class="text-xs text-indigo-300">Total KK</p>
                    </div>
                    <div class="h-10 w-px bg-indigo-500"></div>
                    <div class="text-center">
                        <p class="text-3xl font-black">{{ stats.total_warga }}</p>
                        <p class="text-xs text-indigo-300">Total Warga</p>
                    </div>
                    <div class="h-10 w-px bg-indigo-500"></div>
                    <div class="text-center">
                        <p class="text-3xl font-black">{{ rateVerified }}%</p>
                        <p class="text-xs text-indigo-300">Terverifikasi</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stat Cards -->
        <div class="mb-6 grid grid-cols-2 gap-4 lg:grid-cols-4">
            <div class="flex items-center gap-4 rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-100 transition hover:-translate-y-0.5 hover:shadow-md">
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-blue-50">
                    <svg class="h-6 w-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/></svg>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-400">Total KK</p>
                    <p class="mt-0.5 text-3xl font-black text-gray-800">{{ displayStats.total_kk }}</p>
                    <p class="text-[11px] text-gray-400">Kartu Keluarga</p>
                </div>
            </div>

            <div class="flex items-center gap-4 rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-100 transition hover:-translate-y-0.5 hover:shadow-md">
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-emerald-50">
                    <svg class="h-6 w-6 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-400">Total Warga</p>
                    <p class="mt-0.5 text-3xl font-black text-gray-800">{{ displayStats.total_warga }}</p>
                    <p class="text-[11px] text-gray-400">Jiwa tercatat</p>
                </div>
            </div>

            <div class="flex items-center gap-4 rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-100 transition hover:-translate-y-0.5 hover:shadow-md">
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-purple-50">
                    <svg class="h-6 w-6 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"/></svg>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-400">Dasawisma</p>
                    <p class="mt-0.5 text-3xl font-black text-gray-800">{{ displayStats.total_dasawisma }}</p>
                    <p class="text-[11px] text-gray-400">Kelompok aktif</p>
                </div>
            </div>

            <div class="flex items-center gap-4 rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-100 transition hover:-translate-y-0.5 hover:shadow-md">
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-emerald-50">
                    <svg class="h-6 w-6 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-400">Terverifikasi</p>
                    <p class="mt-0.5 text-3xl font-black text-emerald-600">{{ displayStats.disetujui }}</p>
                    <p class="text-[11px] text-gray-400">Sudah disetujui</p>
                </div>
            </div>
        </div>

        <!-- Progress Verifikasi + Aksi -->
        <div class="grid gap-5 lg:grid-cols-2">
            <!-- Progress -->
            <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-gray-100">
                <div class="border-b border-gray-100 px-5 py-4">
                    <h2 class="text-sm font-bold text-gray-700">Progress Verifikasi Data</h2>
                </div>
                <div class="p-6">
                    <div class="mb-3 flex items-center justify-between">
                        <p class="text-sm font-medium text-gray-600">Tingkat Verifikasi</p>
                        <p class="text-lg font-black text-emerald-600">{{ rateVerified }}%</p>
                    </div>
                    <div class="h-3 overflow-hidden rounded-full bg-gray-100">
                        <div
                            class="h-full rounded-full transition-all duration-1000 ease-out"
                            style="background: linear-gradient(90deg, #059669, #10b981);"
                            :style="`width: ${rateVerified}%`"
                        ></div>
                    </div>
                    <p class="mt-2 text-xs text-gray-400">{{ stats.disetujui }} dari {{ stats.total_kk }} KK telah diverifikasi</p>

                    <div class="mt-6 grid grid-cols-2 gap-3">
                        <div class="rounded-xl bg-emerald-50 p-4 text-center">
                            <p class="text-2xl font-black text-emerald-700">{{ stats.disetujui }}</p>
                            <p class="text-xs text-emerald-600">Disetujui</p>
                        </div>
                        <div class="rounded-xl bg-gray-50 p-4 text-center">
                            <p class="text-2xl font-black text-gray-600">{{ stats.total_kk - stats.disetujui }}</p>
                            <p class="text-xs text-gray-500">Belum/Proses</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Aksi Cepat Kades -->
            <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-gray-100">
                <div class="border-b border-gray-100 px-5 py-4">
                    <h2 class="text-sm font-bold text-gray-700">Aksi Cepat</h2>
                </div>
                <div class="p-4 space-y-3">
                    <a href="/kades/laporan"
                        class="flex items-center gap-3.5 rounded-xl border border-gray-100 p-4 transition hover:border-blue-200 hover:bg-blue-50">
                        <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-blue-100">
                            <svg class="h-5 w-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-800">Laporan Populasi</p>
                            <p class="text-xs text-gray-400">Lihat & cetak laporan data warga per dasawisma</p>
                        </div>
                        <svg class="ml-auto h-4 w-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>

                <!-- Info card -->
                <div class="m-4 overflow-hidden rounded-xl p-5" style="background: linear-gradient(135deg, #1e1b4b, #4338ca);">
                    <p class="text-sm font-semibold text-white">SIDA-Dompas</p>
                    <p class="mt-1.5 text-xs text-indigo-200">Sistem Informasi Dasawisma — Membantu pengelolaan data warga secara digital dan transparan.</p>
                    <div class="mt-4 flex items-center gap-1">
                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                        <p class="text-[11px] text-indigo-300 font-medium">Sistem berjalan normal</p>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
