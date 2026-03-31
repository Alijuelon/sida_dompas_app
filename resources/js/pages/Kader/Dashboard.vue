<script setup lang="ts">
import { Head, usePage } from '@inertiajs/vue3';
import { computed, onMounted, ref } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';

const props = defineProps<{
    stats: {
        total_kk: number;
        total_warga: number;
        menunggu: number;
        disetujui: number;
        ditolak: number;
        total_dasawisma: number;
    };
    keluarga_terbaru: any[];
    kader: any;
}>();

const page = usePage();
const userName = computed(() => (page.props as any).auth?.user?.name ?? 'Kader');

// Animated counter
const displayStats = ref({ total_kk: 0, total_warga: 0, menunggu: 0, disetujui: 0 });

function animateCount(key: keyof typeof displayStats.value, target: number) {
    const duration = 1000;
    const steps = 40;
    const step = target / steps;
    let current = 0;
    const timer = setInterval(() => {
        current = Math.min(current + step, target);
        displayStats.value[key] = Math.round(current);
        if (current >= target) clearInterval(timer);
    }, duration / steps);
}

onMounted(() => {
    animateCount('total_kk', props.stats.total_kk);
    animateCount('total_warga', props.stats.total_warga);
    animateCount('menunggu', props.stats.menunggu);
    animateCount('disetujui', props.stats.disetujui);
});

function statusBadge(status: string) {
    return {
        menunggu: 'bg-amber-100 text-amber-700',
        disetujui: 'bg-emerald-100 text-emerald-700',
        ditolak: 'bg-red-100 text-red-700',
    }[status] ?? 'bg-gray-100 text-gray-500';
}

const jam = new Date().getHours();
const greeting = jam < 12 ? 'Selamat Pagi' : jam < 15 ? 'Selamat Siang' : jam < 18 ? 'Selamat Sore' : 'Selamat Malam';
</script>

<template>
    <Head title="Dashboard Kader" />
    <AppLayout>
        <!-- Welcome Banner -->
        <div class="mb-6 overflow-hidden rounded-2xl shadow-sm" style="background: linear-gradient(135deg, #064e3b 0%, #047857 70%, #059669 100%);">
            <div class="px-7 py-6">
                <p class="text-sm font-medium text-emerald-300">{{ greeting }},</p>
                <h1 class="mt-0.5 text-2xl font-black text-white">{{ userName }} 👋</h1>
                <p class="mt-1 text-sm text-emerald-200">
                    {{ kader ? `Wilayah: ${kader.wilayah ?? `${stats.total_dasawisma} Dasawisma`}` : 'Dashboard Kader Dasawisma' }}
                </p>
                <div class="mt-4 flex items-center gap-4 text-white/90 text-sm">
                    <a href="/kader/keluarga/create"
                        class="inline-flex items-center gap-1.5 rounded-xl bg-white/15 px-4 py-2 font-semibold text-white backdrop-blur-sm transition hover:bg-white/25 active:scale-95">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Input KK Baru
                    </a>
                    <a href="/kader/keluarga"
                        class="inline-flex items-center gap-1.5 rounded-xl px-4 py-2 font-medium text-emerald-200 transition hover:text-white">
                        Lihat Semua KK →
                    </a>
                </div>
            </div>
        </div>

        <!-- Stat Cards -->
        <div class="mb-6 grid grid-cols-2 gap-4 lg:grid-cols-4">
            <!-- Total KK -->
            <div class="flex items-center gap-4 rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-100 transition hover:-translate-y-0.5 hover:shadow-md">
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-blue-50">
                    <svg class="h-6 w-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/></svg>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-400">Total KK</p>
                    <p class="mt-0.5 text-3xl font-black text-gray-800">{{ displayStats.total_kk }}</p>
                </div>
            </div>

            <!-- Total Warga -->
            <div class="flex items-center gap-4 rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-100 transition hover:-translate-y-0.5 hover:shadow-md">
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-emerald-50">
                    <svg class="h-6 w-6 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-400">Total Warga</p>
                    <p class="mt-0.5 text-3xl font-black text-gray-800">{{ displayStats.total_warga }}</p>
                </div>
            </div>

            <!-- Menunggu -->
            <div class="flex items-center gap-4 rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-100 transition hover:-translate-y-0.5 hover:shadow-md">
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-amber-50">
                    <svg class="h-6 w-6 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-400">Menunggu</p>
                    <p class="mt-0.5 text-3xl font-black text-amber-600">{{ displayStats.menunggu }}</p>
                </div>
            </div>

            <!-- Disetujui -->
            <div class="flex items-center gap-4 rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-100 transition hover:-translate-y-0.5 hover:shadow-md">
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-emerald-50">
                    <svg class="h-6 w-6 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-400">Disetujui</p>
                    <p class="mt-0.5 text-3xl font-black text-emerald-600">{{ displayStats.disetujui }}</p>
                </div>
            </div>
        </div>

        <!-- Quick Actions + KK Terbaru -->
        <div class="grid gap-5 lg:grid-cols-3">
            <!-- Quick Actions -->
            <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-gray-100">
                <div class="border-b border-gray-100 px-5 py-4">
                    <h2 class="text-sm font-bold text-gray-700">Aksi Cepat</h2>
                </div>
                <div class="p-4 space-y-2">
                    <a href="/kader/keluarga/create"
                        class="flex items-center gap-3 rounded-xl bg-emerald-50 p-3.5 transition hover:bg-emerald-100">
                        <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-emerald-600">
                            <svg class="h-4 w-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-800">Input KK Baru</p>
                            <p class="text-xs text-gray-400">Daftarkan kartu keluarga</p>
                        </div>
                    </a>
                    <a href="/kader/dasawisma"
                        class="flex items-center gap-3 rounded-xl bg-blue-50 p-3.5 transition hover:bg-blue-100">
                        <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-blue-600">
                            <svg class="h-4 w-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"/></svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-800">Data Dasawisma</p>
                            <p class="text-xs text-gray-400">Kelola wilayah dasawisma</p>
                        </div>
                    </a>
                    <a href="/kader/status-verifikasi"
                        class="flex items-center gap-3 rounded-xl bg-amber-50 p-3.5 transition hover:bg-amber-100">
                        <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-amber-500">
                            <svg class="h-4 w-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-800">Status Verifikasi</p>
                            <p class="text-xs text-gray-400">
                                <span v-if="stats.menunggu > 0" class="text-amber-600 font-semibold">{{ stats.menunggu }} menunggu review</span>
                                <span v-else>Semua KK sudah ditinjau</span>
                            </p>
                        </div>
                    </a>
                </div>
            </div>

            <!-- KK Terbaru -->
            <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-gray-100 lg:col-span-2">
                <div class="flex items-center justify-between border-b border-gray-100 px-5 py-4">
                    <h2 class="text-sm font-bold text-gray-700">KK Terbaru</h2>
                    <a href="/kader/keluarga" class="text-xs font-semibold text-emerald-600 hover:text-emerald-700">Lihat semua →</a>
                </div>
                <div v-if="keluarga_terbaru.length === 0" class="py-12 text-center">
                    <svg class="mx-auto h-10 w-10 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <p class="mt-2 text-sm text-gray-400">Belum ada data KK</p>
                    <a href="/kader/keluarga/create" class="mt-3 inline-block text-xs font-semibold text-emerald-600 hover:underline">+ Input KK Pertama</a>
                </div>
                <table v-else class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-50 bg-gray-50/70">
                            <th class="px-5 py-3 text-left text-[11px] font-semibold uppercase tracking-wide text-gray-400">Kepala KK</th>
                            <th class="px-5 py-3 text-center text-[11px] font-semibold uppercase tracking-wide text-gray-400">Anggota</th>
                            <th class="px-5 py-3 text-left text-[11px] font-semibold uppercase tracking-wide text-gray-400">Status</th>
                            <th class="px-5 py-3 text-center text-[11px] font-semibold uppercase tracking-wide text-gray-400">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        <tr v-for="kk in keluarga_terbaru" :key="kk.id" class="transition hover:bg-emerald-50/30">
                            <td class="px-5 py-3.5">
                                <p class="font-semibold text-gray-800">{{ kk.nama_kepala_keluarga }}</p>
                                <p class="font-mono text-[11px] text-gray-400">{{ kk.no_kk }}</p>
                            </td>
                            <td class="px-5 py-3.5 text-center">
                                <span class="rounded-full bg-blue-50 px-2.5 py-1 text-xs font-semibold text-blue-600">{{ kk.jumlah_anggota }} jiwa</span>
                            </td>
                            <td class="px-5 py-3.5">
                                <span :class="statusBadge(kk.verifikasi?.status_verifikasi ?? '')" class="inline-flex items-center gap-1 rounded-full px-2.5 py-1 text-[11px] font-semibold capitalize">
                                    <span v-if="kk.verifikasi?.status_verifikasi === 'menunggu'" class="h-1.5 w-1.5 animate-pulse rounded-full bg-amber-500"></span>
                                    {{ kk.verifikasi?.status_verifikasi ?? 'belum' }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5 text-center">
                                <a :href="`/kader/keluarga/${kk.id}`" class="rounded-lg bg-emerald-50 px-3 py-1.5 text-[11px] font-semibold text-emerald-700 transition hover:bg-emerald-100">Detail</a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AppLayout>
</template>
