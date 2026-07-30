<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { computed, onMounted, ref } from 'vue';
import VueApexCharts from 'vue3-apexcharts';

const props = defineProps<{
    stats: {
        total_kk: number;
        total_dasawisma: number;
        total_warga: number;
        menunggu: number;
        disetujui: number;
        ditolak: number;
        total_kader: number;
    };
    chart_data: {
        labels: string[];
        total_kk: number[];
        total_warga: number[];
        menunggu: number[];
        total_dasawisma: number[];
        filter: string;
    };
    verifikasi_terbaru: any[];
}>();

// Counter animation
const animated = ref({
    total_kk: 0,
    total_warga: 0,
    menunggu: 0,
    total_dasawisma: 0,
});

function animateCount(key: keyof typeof animated.value, target: number, duration = 1000) {
    const start = Date.now();
    const tick = () => {
        const elapsed = Date.now() - start;
        const progress = Math.min(elapsed / duration, 1);
        const ease = 1 - Math.pow(1 - progress, 3);
        animated.value[key] = Math.round(target * ease);
        if (progress < 1) requestAnimationFrame(tick);
    };
    requestAnimationFrame(tick);
}

onMounted(() => {
    setTimeout(() => {
        animateCount('total_kk', props.stats.total_kk);
        animateCount('total_warga', props.stats.total_warga, 1200);
        animateCount('menunggu', props.stats.menunggu, 800);
        animateCount('total_dasawisma', props.stats.total_dasawisma, 900);
    }, 200);
});

const disetujuiPct = computed(() =>
    props.stats.total_kk ? Math.round((props.stats.disetujui / props.stats.total_kk) * 100) : 0
);
const menungguPct = computed(() =>
    props.stats.total_kk ? Math.round((props.stats.menunggu / props.stats.total_kk) * 100) : 0
);
const ditolakPct = computed(() =>
    props.stats.total_kk ? Math.round((props.stats.ditolak / props.stats.total_kk) * 100) : 0
);

function statusClass(s: string) {
    return {
        menunggu: 'bg-amber-100 text-amber-700 ring-1 ring-amber-200',
        disetujui: 'bg-emerald-100 text-emerald-700 ring-1 ring-emerald-200',
        ditolak: 'bg-red-100 text-red-700 ring-1 ring-red-200',
    }[s] ?? 'bg-gray-100 text-gray-500';
}

// Chart Options Factory
function getChartOptions(color: string, labels: string[]) {
    return {
        chart: {
            type: 'area',
            sparkline: { enabled: true },
            animations: { enabled: true, easing: 'easeinout', speed: 800 }
        },
        stroke: { curve: 'smooth', width: 2 },
        fill: {
            type: 'gradient',
            gradient: {
                shadeIntensity: 1,
                opacityFrom: 0.4,
                opacityTo: 0,
                stops: [0, 100]
            }
        },
        colors: [color],
        labels: labels,
        tooltip: {
            theme: 'light',
            fixed: { enabled: false },
            x: { show: true },
            y: { title: { formatter: () => '' } },
            marker: { show: false }
        }
    };
}

const filter = ref(props.chart_data.filter);

function changeFilter(newFilter: string) {
    filter.value = newFilter;
    router.get('/admin/dashboard', { filter: newFilter }, { preserveState: true, preserveScroll: true, replace: true });
}
</script>

<template>
    <Head title="Dashboard Admin" />
    <AppLayout>
        <!-- Header -->
        <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between animate-fade-in">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Dashboard Administrator</h1>
                <p class="mt-0.5 text-sm text-gray-500">Selamat datang di Aplikasi Pendataan Warga TP-PKK Berbasis Website Pada Dasawisma Desa Dompas</p>
            </div>
            
            <!-- Global Chart Filter -->
            <div class="flex items-center gap-1 rounded-lg bg-white p-1 shadow-sm ring-1 ring-gray-200">
                <button @click="changeFilter('harian')" :class="['px-4 py-1.5 text-xs font-semibold rounded-md transition-all', filter === 'harian' ? 'bg-indigo-50 text-indigo-700 ring-1 ring-indigo-200' : 'text-gray-500 hover:text-gray-800 hover:bg-gray-50']">Harian</button>
                <button @click="changeFilter('bulanan')" :class="['px-4 py-1.5 text-xs font-semibold rounded-md transition-all', filter === 'bulanan' ? 'bg-indigo-50 text-indigo-700 ring-1 ring-indigo-200' : 'text-gray-500 hover:text-gray-800 hover:bg-gray-50']">Bulanan</button>
                <button @click="changeFilter('tahunan')" :class="['px-4 py-1.5 text-xs font-semibold rounded-md transition-all', filter === 'tahunan' ? 'bg-indigo-50 text-indigo-700 ring-1 ring-indigo-200' : 'text-gray-500 hover:text-gray-800 hover:bg-gray-50']">Tahunan</button>
            </div>
        </div>

        <!-- Stat Cards with Charts -->
        <div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <!-- Total KK -->
            <div class="group relative overflow-hidden rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-100 transition-all duration-300 hover:-translate-y-0.5 hover:shadow-md flex flex-col justify-between h-36">
                <div class="flex items-start justify-between z-10">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-gray-400">Total KK</p>
                        <p class="mt-1 text-3xl font-bold text-gray-800 tabular-nums">{{ animated.total_kk }}</p>
                    </div>
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-50 transition group-hover:bg-blue-100">
                        <svg class="h-5 w-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                        </svg>
                    </div>
                </div>
                <div class="absolute bottom-0 left-0 right-0 h-16 pointer-events-none opacity-80 group-hover:opacity-100 transition-opacity">
                    <VueApexCharts type="area" height="100%" :options="getChartOptions('#3b82f6', chart_data.labels)" :series="[{ name: 'KK Baru', data: chart_data.total_kk }]" />
                </div>
            </div>

            <!-- Total Warga -->
            <div class="group relative overflow-hidden rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-100 transition-all duration-300 hover:-translate-y-0.5 hover:shadow-md flex flex-col justify-between h-36">
                <div class="flex items-start justify-between z-10">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-gray-400">Total Warga</p>
                        <p class="mt-1 text-3xl font-bold text-gray-800 tabular-nums">{{ animated.total_warga }}</p>
                    </div>
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-50 transition group-hover:bg-emerald-100">
                        <svg class="h-5 w-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                </div>
                <div class="absolute bottom-0 left-0 right-0 h-16 pointer-events-none opacity-80 group-hover:opacity-100 transition-opacity">
                    <VueApexCharts type="area" height="100%" :options="getChartOptions('#10b981', chart_data.labels)" :series="[{ name: 'Warga Baru', data: chart_data.total_warga }]" />
                </div>
            </div>

            <!-- Menunggu -->
            <div class="group relative overflow-hidden rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-100 transition-all duration-300 hover:-translate-y-0.5 hover:shadow-md flex flex-col justify-between h-36">
                <div class="flex items-start justify-between z-10">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-gray-400">Menunggu Verifikasi</p>
                        <p class="mt-1 text-3xl font-bold text-amber-600 tabular-nums">{{ animated.menunggu }}</p>
                    </div>
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-50 transition group-hover:bg-amber-100">
                        <svg class="h-5 w-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <div class="absolute bottom-0 left-0 right-0 h-16 pointer-events-none opacity-80 group-hover:opacity-100 transition-opacity">
                    <VueApexCharts type="area" height="100%" :options="getChartOptions('#f59e0b', chart_data.labels)" :series="[{ name: 'Pengajuan', data: chart_data.menunggu }]" />
                </div>
            </div>

            <!-- Dasawisma -->
            <div class="group relative overflow-hidden rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-100 transition-all duration-300 hover:-translate-y-0.5 hover:shadow-md flex flex-col justify-between h-36">
                <div class="flex items-start justify-between z-10">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-gray-400">Total Dasawisma</p>
                        <p class="mt-1 text-3xl font-bold text-gray-800 tabular-nums">{{ animated.total_dasawisma }}</p>
                    </div>
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-purple-50 transition group-hover:bg-purple-100">
                        <svg class="h-5 w-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                </div>
                <div class="absolute bottom-0 left-0 right-0 h-16 pointer-events-none opacity-80 group-hover:opacity-100 transition-opacity">
                    <VueApexCharts type="area" height="100%" :options="getChartOptions('#8b5cf6', chart_data.labels)" :series="[{ name: 'Dasawisma Baru', data: chart_data.total_dasawisma }]" />
                </div>
            </div>
        </div>

        <!-- Bottom Grid -->
        <div class="grid grid-cols-1 gap-5 xl:grid-cols-3">
            
            <!-- Tabel Verifikasi Terbaru (Kiri) -->
            <div class="xl:col-span-2 space-y-5">
                
                <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-100">
                    <div class="mb-4 flex items-center justify-between">
                        <h2 class="text-base font-semibold text-gray-800">Verifikasi Terbaru</h2>
                        <a href="/admin/verifikasi" class="flex items-center gap-1 text-xs font-semibold text-emerald-600 hover:underline">
                            Lihat semua
                            <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm whitespace-nowrap">
                            <thead>
                                <tr class="border-b border-gray-100">
                                    <th class="pb-3 text-left text-[11px] font-semibold uppercase tracking-wide text-gray-400">No. KK</th>
                                    <th class="pb-3 text-left text-[11px] font-semibold uppercase tracking-wide text-gray-400">Kepala KK</th>
                                    <th class="pb-3 text-left text-[11px] font-semibold uppercase tracking-wide text-gray-400">Dasawisma</th>
                                    <th class="pb-3 text-left text-[11px] font-semibold uppercase tracking-wide text-gray-400">Status</th>
                                    <th class="pb-3 text-left text-[11px] font-semibold uppercase tracking-wide text-gray-400">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                <tr v-if="verifikasi_terbaru.length === 0">
                                    <td colspan="5" class="py-8 text-center text-sm text-gray-400">Belum ada data verifikasi</td>
                                </tr>
                                <tr v-for="v in verifikasi_terbaru" :key="v.id" class="group transition hover:bg-gray-50/50">
                                    <td class="py-3 font-mono text-xs text-gray-400">{{ v.keluarga?.no_kk }}</td>
                                    <td class="py-3 font-medium text-gray-800">{{ v.keluarga?.nama_kepala_keluarga }}</td>
                                    <td class="py-3 text-xs text-gray-500">{{ v.keluarga?.dasawisma?.nama_dasawisma }}</td>
                                    <td class="py-3">
                                        <span class="rounded-full px-2.5 py-1 text-[11px] font-semibold" :class="statusClass(v.status_verifikasi)">
                                            {{ v.status_verifikasi }}
                                        </span>
                                    </td>
                                    <td class="py-3">
                                        <a :href="`/admin/verifikasi/${v.id}`" class="text-xs font-medium text-emerald-600 hover:underline">
                                            Review &rarr;
                                        </a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Status Verifikasi Bar Chart Card -->
                <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-100">
                    <h2 class="mb-4 text-base font-semibold text-gray-800">Status Verifikasi Keseluruhan</h2>
                    <div class="space-y-4">
                        <!-- Disetujui -->
                        <div>
                            <div class="mb-1.5 flex justify-between text-sm">
                                <span class="flex items-center gap-1.5 text-gray-600">
                                    <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                                    Disetujui
                                </span>
                                <span class="font-bold text-emerald-600">{{ stats.disetujui }} <span class="font-normal text-gray-400">({{ disetujuiPct }}%)</span></span>
                            </div>
                            <div class="h-2 overflow-hidden rounded-full bg-gray-100">
                                <div class="h-2 rounded-full bg-gradient-to-r from-emerald-400 to-emerald-600 transition-all duration-1000" :style="`width:${disetujuiPct}%`"></div>
                            </div>
                        </div>
                        <!-- Menunggu -->
                        <div>
                            <div class="mb-1.5 flex justify-between text-sm">
                                <span class="flex items-center gap-1.5 text-gray-600">
                                    <span class="h-2 w-2 animate-pulse rounded-full bg-amber-400"></span>
                                    Menunggu
                                </span>
                                <span class="font-bold text-amber-600">{{ stats.menunggu }} <span class="font-normal text-gray-400">({{ menungguPct }}%)</span></span>
                            </div>
                            <div class="h-2 overflow-hidden rounded-full bg-gray-100">
                                <div class="h-2 rounded-full bg-gradient-to-r from-amber-300 to-amber-500 transition-all duration-1000" :style="`width:${menungguPct}%`"></div>
                            </div>
                        </div>
                        <!-- Ditolak -->
                        <div>
                            <div class="mb-1.5 flex justify-between text-sm">
                                <span class="flex items-center gap-1.5 text-gray-600">
                                    <span class="h-2 w-2 rounded-full bg-red-400"></span>
                                    Ditolak
                                </span>
                                <span class="font-bold text-red-500">{{ stats.ditolak }} <span class="font-normal text-gray-400">({{ ditolakPct }}%)</span></span>
                            </div>
                            <div class="h-2 overflow-hidden rounded-full bg-gray-100">
                                <div class="h-2 rounded-full bg-gradient-to-r from-red-300 to-red-500 transition-all duration-1000" :style="`width:${ditolakPct}%`"></div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Fitur Utama & Quick Actions (Kanan) -->
            <div class="xl:col-span-1 space-y-5">
                
                <!-- Quick Actions Grid -->
                <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-100 h-full">
                    <h2 class="mb-5 text-base font-semibold text-gray-800">Menu Fitur Utama</h2>
                    <div class="grid grid-cols-2 gap-4">
                        
                        <!-- Verifikasi -->
                        <a href="/admin/verifikasi" class="group flex flex-col items-center justify-center rounded-2xl border border-gray-100 bg-gray-50/50 p-4 text-center transition-all duration-300 hover:border-emerald-200 hover:bg-emerald-50 hover:shadow-md hover:-translate-y-1">
                            <div class="relative mb-3 flex h-12 w-12 items-center justify-center rounded-2xl bg-emerald-100 text-emerald-600 transition-colors duration-300 group-hover:bg-emerald-500 group-hover:text-white">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span v-if="stats.menunggu > 0" class="absolute -right-1.5 -top-1.5 flex h-5 w-5 items-center justify-center rounded-full bg-red-500 text-[10px] font-bold text-white ring-2 ring-white">
                                    {{ stats.menunggu }}
                                </span>
                            </div>
                            <span class="text-xs font-semibold text-gray-600 group-hover:text-emerald-700">Verifikasi KK</span>
                        </a>

                        <!-- Manajemen User -->
                        <a href="/admin/users" class="group flex flex-col items-center justify-center rounded-2xl border border-gray-100 bg-gray-50/50 p-4 text-center transition-all duration-300 hover:border-blue-200 hover:bg-blue-50 hover:shadow-md hover:-translate-y-1">
                            <div class="mb-3 flex h-12 w-12 items-center justify-center rounded-2xl bg-blue-100 text-blue-600 transition-colors duration-300 group-hover:bg-blue-500 group-hover:text-white">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                </svg>
                            </div>
                            <span class="text-xs font-semibold text-gray-600 group-hover:text-blue-700">Data User</span>
                        </a>

                        <!-- Dasawisma -->
                        <a href="/admin/dasawisma" class="group flex flex-col items-center justify-center rounded-2xl border border-gray-100 bg-gray-50/50 p-4 text-center transition-all duration-300 hover:border-purple-200 hover:bg-purple-50 hover:shadow-md hover:-translate-y-1">
                            <div class="mb-3 flex h-12 w-12 items-center justify-center rounded-2xl bg-purple-100 text-purple-600 transition-colors duration-300 group-hover:bg-purple-500 group-hover:text-white">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                            </div>
                            <span class="text-xs font-semibold text-gray-600 group-hover:text-purple-700">Dasawisma</span>
                        </a>

                        <!-- Laporan -->
                        <a href="/admin/laporan" class="group flex flex-col items-center justify-center rounded-2xl border border-gray-100 bg-gray-50/50 p-4 text-center transition-all duration-300 hover:border-orange-200 hover:bg-orange-50 hover:shadow-md hover:-translate-y-1">
                            <div class="mb-3 flex h-12 w-12 items-center justify-center rounded-2xl bg-orange-100 text-orange-600 transition-colors duration-300 group-hover:bg-orange-500 group-hover:text-white">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                </svg>
                            </div>
                            <span class="text-xs font-semibold text-gray-600 group-hover:text-orange-700">Laporan</span>
                        </a>

                    </div>
                    
                    <div class="mt-6 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 p-5 text-white shadow-md">
                        <h3 class="font-semibold">Bantuan & Informasi</h3>
                        <p class="mt-1 text-xs text-indigo-100">Jika mengalami kendala, hubungi teknisi atau baca dokumentasi sistem.</p>
                        <button class="mt-3 rounded-lg bg-white/20 px-4 py-1.5 text-xs font-semibold backdrop-blur-sm transition hover:bg-white/30">
                            Pusat Bantuan
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </AppLayout>
</template>
