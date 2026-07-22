<script setup lang="ts">
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import { computed, ref, watch } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';

const props = defineProps<{
    dasawisma: {
        id: number;
        nama_dasawisma: string;
        rt: string;
        rw: string;
        desa: string;
        kader?: { user?: { name: string } };
    };
    keluargas: {
        data: any[];
        current_page: number;
        last_page: number;
        total: number;
        per_page: number;
        from: number;
        to: number;
        links: { url: string | null; label: string; active: boolean }[];
    };
    filters: { search?: string };
    stats: { total_kk: number; total_anggota: number };
}>();

const page = usePage();
const role = computed(() => (page.props as any).auth?.user?.role ?? '');
const isAdmin = computed(() => role.value === 'admin');

const search = ref(props.filters.search || '');
let searchTimeout: any = null;

watch(search, (val) => {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        const prefix = isAdmin.value ? '/admin' : '/kader';
        router.get(`${prefix}/dasawisma/${props.dasawisma.id}`, { search: val || undefined }, {
            preserveState: true,
            preserveScroll: true,
            replace: true,
        });
    }, 400);
});

function goToPage(url: string | null) {
    if (url) router.get(url, {}, { preserveState: true, preserveScroll: true });
}

function getBackUrl() {
    return isAdmin.value ? '/admin/dasawisma' : '/kader/dasawisma';
}

function getKeluargaUrl(id: number) {
    return `/kader/keluarga/${id}`;
}
</script>

<template>
    <Head :title="`Detail Dasawisma - ${dasawisma.nama_dasawisma}`" />
    <AppLayout>
        <div class="mx-auto max-w-6xl">

            <!-- Back button + Header -->
            <div class="mb-6 flex items-center justify-between">
                <div>
                    <Link :href="getBackUrl()" class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-emerald-600 transition mb-2">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                        Kembali ke Daftar
                    </Link>
                    <h1 class="text-2xl font-bold text-gray-800">Detail Dasawisma</h1>
                    <p class="text-sm text-gray-500">Informasi lengkap dan daftar keluarga</p>
                </div>
            </div>

            <!-- Info Card -->
            <div class="mb-6 rounded-2xl bg-white p-6 shadow-sm border border-gray-100">
                <div class="flex flex-col md:flex-row md:items-center gap-6">
                    <!-- Icon -->
                    <div class="flex h-16 w-16 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-emerald-400 to-teal-600 shadow-lg shadow-emerald-500/20">
                        <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"/></svg>
                    </div>

                    <!-- Details -->
                    <div class="flex-1 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div>
                            <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">Nama Dasawisma</p>
                            <p class="mt-1 text-lg font-bold text-gray-800">{{ dasawisma.nama_dasawisma }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">Wilayah</p>
                            <p class="mt-1 text-sm font-semibold text-gray-700">RT {{ dasawisma.rt }} / RW {{ dasawisma.rw }}</p>
                            <p class="text-xs text-gray-500">Desa {{ dasawisma.desa }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">Kader Penanggung Jawab</p>
                            <div class="mt-1 flex items-center gap-2">
                                <div class="flex h-7 w-7 items-center justify-center rounded-full bg-emerald-100 text-emerald-700 text-xs font-bold">
                                    {{ dasawisma.kader?.user?.name?.charAt(0)?.toUpperCase() || '?' }}
                                </div>
                                <span class="text-sm font-semibold text-gray-700">{{ dasawisma.kader?.user?.name || '-' }}</span>
                            </div>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">Total Keluarga</p>
                            <p class="mt-1 text-2xl font-extrabold text-emerald-600">{{ stats.total_kk }}</p>
                            <p class="text-xs text-gray-500">{{ stats.total_anggota }} anggota</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="mb-6 grid grid-cols-2 gap-4">
                <div class="rounded-2xl bg-gradient-to-br from-emerald-500 to-teal-600 p-5 text-white shadow-lg shadow-emerald-500/20">
                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-white/20">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </div>
                        <div>
                            <p class="text-2xl font-extrabold">{{ stats.total_kk }}</p>
                            <p class="text-xs font-medium text-emerald-100">Kartu Keluarga</p>
                        </div>
                    </div>
                </div>
                <div class="rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-600 p-5 text-white shadow-lg shadow-blue-500/20">
                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-white/20">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"/></svg>
                        </div>
                        <div>
                            <p class="text-2xl font-extrabold">{{ stats.total_anggota }}</p>
                            <p class="text-xs font-medium text-blue-100">Total Anggota</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- KK List Section -->
            <div class="rounded-2xl bg-white shadow-sm border border-gray-100 overflow-hidden">
                <!-- Header + Search -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 p-5 border-b border-gray-100">
                    <div class="flex items-center gap-3">
                        <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-emerald-50">
                            <svg class="h-4.5 w-4.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        </div>
                        <div>
                            <h2 class="text-lg font-bold text-gray-800">Daftar Keluarga (KK)</h2>
                            <p class="text-xs text-gray-500">{{ keluargas.total }} KK pada dasawisma ini</p>
                        </div>
                    </div>
                    <div class="relative w-full sm:w-72">
                        <svg class="absolute left-3 top-2.5 h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        <input v-model="search" type="text" placeholder="Cari nama KRT, No. KK, RT, RW..." class="w-full rounded-xl border border-gray-200 bg-gray-50 py-2.5 pl-9 pr-4 text-sm focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-100 focus:bg-white transition" />
                    </div>
                </div>

                <!-- Table -->
                <div class="overflow-x-auto">
                    <table v-if="keluargas.data.length > 0" class="w-full text-sm">
                        <thead>
                            <tr class="bg-gray-50/80 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                <th class="px-5 py-3">No</th>
                                <th class="px-5 py-3">No. KK</th>
                                <th class="px-5 py-3">Kepala Keluarga</th>
                                <th class="px-5 py-3">RT/RW</th>
                                <th class="px-5 py-3">Jml Anggota</th>
                                <th class="px-5 py-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            <tr v-for="(kk, idx) in keluargas.data" :key="kk.id" class="hover:bg-emerald-50/30 transition">
                                <td class="px-5 py-3.5 text-gray-500">{{ (keluargas.from || 0) + idx }}</td>
                                <td class="px-5 py-3.5 font-mono text-xs text-gray-600">{{ kk.no_kk }}</td>
                                <td class="px-5 py-3.5">
                                    <p class="font-semibold text-gray-800">{{ kk.nama_kepala_keluarga }}</p>
                                </td>
                                <td class="px-5 py-3.5 text-gray-500">{{ kk.rt || '-' }} / {{ kk.rw || '-' }}</td>
                                <td class="px-5 py-3.5">
                                    <span class="inline-flex items-center rounded-full bg-emerald-50 px-2.5 py-0.5 text-xs font-semibold text-emerald-700">
                                        {{ kk.anggota_keluargas_count ?? 0 }} orang
                                    </span>
                                </td>
                                <td class="px-5 py-3.5 text-center">
                                    <Link :href="getKeluargaUrl(kk.id)" class="inline-flex items-center gap-1.5 rounded-lg bg-emerald-50 px-3 py-1.5 text-xs font-medium text-emerald-700 hover:bg-emerald-100 transition">
                                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                                        Detail KK
                                    </Link>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- Empty -->
                    <div v-else class="py-16 text-center">
                        <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-gray-100">
                            <svg class="h-7 w-7 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        </div>
                        <p class="text-gray-500 font-medium">{{ search ? 'Tidak ada KK yang cocok dengan pencarian.' : 'Belum ada keluarga terdaftar di dasawisma ini.' }}</p>
                        <p v-if="!search" class="text-xs text-gray-400 mt-1">Tambahkan KK melalui menu Data Keluarga.</p>
                    </div>
                </div>

                <!-- Pagination -->
                <div v-if="keluargas.last_page > 1" class="flex items-center justify-between border-t border-gray-100 px-5 py-3.5 bg-gray-50/50">
                    <p class="text-xs text-gray-500">
                        Menampilkan <span class="font-semibold text-gray-700">{{ keluargas.from }}</span>–<span class="font-semibold text-gray-700">{{ keluargas.to }}</span> dari <span class="font-semibold text-gray-700">{{ keluargas.total }}</span>
                    </p>
                    <div class="flex gap-1">
                        <button
                            v-for="link in keluargas.links"
                            :key="link.label"
                            @click="goToPage(link.url)"
                            :disabled="!link.url"
                            class="rounded-lg px-3 py-1.5 text-xs font-medium transition"
                            :class="link.active ? 'bg-emerald-600 text-white shadow-sm' : 'text-gray-600 hover:bg-white disabled:opacity-40'"
                            v-html="link.label"
                        />
                    </div>
                </div>
            </div>

        </div>
    </AppLayout>
</template>
