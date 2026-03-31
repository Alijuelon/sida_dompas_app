<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import { computed, ref, watch } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';

const props = defineProps<{
    keluargas: any[];
    stats: {
        total_kk: number;
        total_warga: number;
        balita: number;
        lansia: number;
        laki_laki: number;
        perempuan: number;
    };
    dasawismas: any[];
    rt_list: string[];
    rw_list: string[];
    active_filters: any;
}>();

// Filter State (Backend)
const filterRt = ref(props.active_filters?.rt ?? '');
const filterRw = ref(props.active_filters?.rw ?? '');
const filterDasawisma = ref(props.active_filters?.dasawisma_id ?? '');

function applyFilters() {
    router.get('/admin/laporan', {
        rt: filterRt.value || undefined,
        rw: filterRw.value || undefined,
        dasawisma_id: filterDasawisma.value || undefined,
    }, { preserveState: true });
}

function resetFilters() {
    filterRt.value = '';
    filterRw.value = '';
    filterDasawisma.value = '';
    applyFilters();
}

// Search + Paginate (Frontend)
const search = ref('');
const currentPage = ref(1);
const perPage = 15;

const filteredFamilies = computed(() => {
    const q = search.value.toLowerCase().trim();
    if (!q) return props.keluargas;

    return props.keluargas.filter(kk => {
        // Search in family head / KK number
        const matchKK = (kk.no_kk?.toLowerCase().includes(q) ||
                       kk.nama_kepala_keluarga?.toLowerCase().includes(q));
        if (matchKK) return true;

        // Search in all family members name/NIK
        return kk.anggota_keluargas?.some((member: any) => 
            member.nama_anggota?.toLowerCase().includes(q) ||
            member.nik?.toLowerCase().includes(q)
        );
    });
});

const totalPages = computed(() => Math.max(1, Math.ceil(filteredFamilies.value.length / perPage)));
const paginated = computed(() => filteredFamilies.value.slice((currentPage.value - 1) * perPage, currentPage.value * perPage));

watch(search, () => { currentPage.value = 1; });

function printReport() {
    window.print();
}

function formatDate(dateStr: string) {
    if (!dateStr) return '-';
    const d = new Date(dateStr);
    return d.toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' });
}
</script>

<template>
    <Head title="Laporan & Statistik" />
    <AppLayout>
        <!-- Header -->
        <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between print:hidden">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Laporan & Statistik Desa</h1>
                <p class="text-sm text-gray-500">Data rekapitulasi warga yang telah diverifikasi</p>
            </div>
            <button @click="printReport" class="inline-flex items-center gap-2 rounded-xl bg-gray-800 px-4 py-2.5 text-sm font-medium text-white shadow-sm hover:bg-gray-900 transition">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                Cetak Laporan
            </button>
        </div>

        <!-- Print Header (Hidden on screen) -->
        <div class="hidden print:block mb-8 text-center">
            <h1 class="text-2xl font-bold">Laporan Rekapitulasi Data Warga</h1>
            <p class="text-gray-600">Sistem Informasi Dasawisma Dompas</p>
            <div class="mt-4 flex flex-wrap justify-center gap-x-6 gap-y-2 text-sm font-medium border-t border-b border-gray-200 py-3">
                <p v-if="props.active_filters?.rt">RT: <span class="text-gray-800">{{ props.active_filters.rt }}</span></p>
                <p v-if="props.active_filters?.rw">RW: <span class="text-gray-800">{{ props.active_filters.rw }}</span></p>
                <p v-if="props.active_filters?.dasawisma_id">Dasawisma: <span class="text-gray-800">{{ dasawismas.find(d => d.id == props.active_filters.dasawisma_id)?.nama_dasawisma }}</span></p>
                <p v-if="search">Pencarian: <span class="text-gray-800">"{{ search }}"</span></p>
                <p v-if="!props.active_filters?.rt && !props.active_filters?.rw && !props.active_filters?.dasawisma_id && !search" class="text-gray-500">Semua Data Desa</p>
            </div>
        </div>

        <!-- Statistik Cards -->
        <div class="mb-6 grid grid-cols-2 gap-4 md:grid-cols-3 xl:grid-cols-6 print:hidden">
            <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
                <p class="text-3xl font-bold text-blue-600">{{ stats.total_kk }}</p>
                <p class="text-xs font-medium text-gray-500 mt-1 uppercase tracking-wide">Total KK</p>
            </div>
            <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
                <p class="text-3xl font-bold text-green-600">{{ stats.total_warga }}</p>
                <p class="text-xs font-medium text-gray-500 mt-1 uppercase tracking-wide">Total Warga</p>
            </div>
            <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
                <p class="text-3xl font-bold text-yellow-600">{{ stats.balita }}</p>
                <p class="text-xs font-medium text-gray-500 mt-1 uppercase tracking-wide">Balita (<5 th)</p>
            </div>
            <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
                <p class="text-3xl font-bold text-purple-600">{{ stats.lansia }}</p>
                <p class="text-xs font-medium text-gray-500 mt-1 uppercase tracking-wide">Lansia (≥60 th)</p>
            </div>
            <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
                <p class="text-3xl font-bold text-indigo-600">{{ stats.laki_laki }}</p>
                <p class="text-xs font-medium text-gray-500 mt-1 uppercase tracking-wide">Laki-Laki</p>
            </div>
            <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
                <p class="text-3xl font-bold text-pink-600">{{ stats.perempuan }}</p>
                <p class="text-xs font-medium text-gray-500 mt-1 uppercase tracking-wide">Perempuan</p>
            </div>
        </div>

        <!-- Filter / Search Bar (Hidden in Print) -->
        <div class="mb-6 rounded-2xl border border-gray-100 bg-white p-4 shadow-sm print:hidden">
            <div class="flex flex-wrap items-end gap-4">
                <div class="flex-1 min-w-[200px]">
                    <label class="mb-1.5 block text-xs font-medium text-gray-600">Pencarian (Frontend)</label>
                    <div class="relative">
                        <svg class="absolute left-3 top-2.5 h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        <input v-model="search" type="text" placeholder="Cari No. KK atau nama..." class="w-full rounded-xl border border-gray-200 bg-gray-50 py-2.5 pl-9 pr-4 text-sm focus:border-green-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-green-100 transition" />
                    </div>
                </div>
                <div class="w-full sm:w-auto h-px bg-gray-200 sm:w-px sm:h-10 mx-2"></div>
                <div class="w-full sm:w-32">
                    <label class="mb-1.5 block text-xs font-medium text-gray-600">Filter RT</label>
                    <select v-model="filterRt" class="w-full rounded-xl border border-gray-200 bg-white py-2.5 px-3 text-sm focus:border-green-400 focus:outline-none focus:ring-2 focus:ring-green-100">
                        <option value="">Semua</option>
                        <option v-for="rt in rt_list" :key="rt" :value="rt">{{ rt }}</option>
                    </select>
                </div>
                <div class="w-full sm:w-32">
                    <label class="mb-1.5 block text-xs font-medium text-gray-600">Filter RW</label>
                    <select v-model="filterRw" class="w-full rounded-xl border border-gray-200 bg-white py-2.5 px-3 text-sm focus:border-green-400 focus:outline-none focus:ring-2 focus:ring-green-100">
                        <option value="">Semua</option>
                        <option v-for="rw in rw_list" :key="rw" :value="rw">{{ rw }}</option>
                    </select>
                </div>
                <div class="w-full sm:w-48">
                    <label class="mb-1.5 block text-xs font-medium text-gray-600">Filter Dasawisma</label>
                    <select v-model="filterDasawisma" class="w-full rounded-xl border border-gray-200 bg-white py-2.5 px-3 text-sm focus:border-green-400 focus:outline-none focus:ring-2 focus:ring-green-100">
                        <option value="">Semua</option>
                        <option v-for="ds in dasawismas" :key="ds.id" :value="ds.id">{{ ds.nama_dasawisma }}</option>
                    </select>
                </div>
                <div class="flex gap-2 w-full sm:w-auto mt-4 sm:mt-0">
                    <button @click="applyFilters" class="flex-1 rounded-xl bg-green-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-green-700 transition">Terapkan</button>
                    <button v-if="filterRt || filterRw || filterDasawisma" @click="resetFilters" class="flex-1 rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm font-medium text-gray-600 hover:bg-gray-50 transition">Reset</button>
                </div>
            </div>
        </div>

        <!-- Tabel Rekapitulasi (Hanya di Layar) -->
        <div class="rounded-2xl bg-white shadow-sm overflow-hidden border border-gray-100 print:hidden">
            <table class="w-full text-sm whitespace-nowrap">
                <thead>
                    <tr class="border-b border-gray-100 bg-gray-50">
                        <th class="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">No. KK</th>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Kepala Keluarga</th>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Dasawisma</th>
                        <th class="px-5 py-3.5 text-center text-xs font-semibold uppercase tracking-wide text-gray-500">RT/RW</th>
                        <th class="px-5 py-3.5 text-center text-xs font-semibold uppercase tracking-wide text-gray-500">Jml Anggota</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    <tr v-if="paginated.length === 0">
                        <td colspan="5" class="py-12 text-center text-gray-400">Tidak ada data laporan ditemukan.</td>
                    </tr>
                    <tr v-for="kk in paginated" :key="'screen-'+kk.id" class="hover:bg-gray-50 transition">
                        <td class="px-5 py-3.5 font-mono text-xs text-gray-600">{{ kk.no_kk }}</td>
                        <td class="px-5 py-3.5 font-medium text-gray-800">{{ kk.nama_kepala_keluarga }}</td>
                        <td class="px-5 py-3.5 text-gray-600">{{ kk.dasawisma?.nama_dasawisma }}</td>
                        <td class="px-5 py-3.5 text-center text-gray-600 border-x border-transparent">{{ kk.dasawisma?.rt }} / {{ kk.dasawisma?.rw }}</td>
                        <td class="px-5 py-3.5 text-center">
                            <span class="rounded-full bg-blue-50 px-2.5 py-1 text-xs font-medium text-blue-700 font-bold">
                                {{ kk.anggota_keluargas?.length || 0 }} Orang
                            </span>
                        </td>
                    </tr>
                </tbody>
            </table>
            <!-- Pagination -->
            <div v-if="totalPages > 1" class="flex items-center justify-between border-t border-gray-100 px-5 py-3">
                <p class="text-xs text-gray-400">Menampilkan {{ paginated.length }} dari {{ filteredFamilies.length }} KK</p>
                <div class="flex gap-1">
                    <button @click="currentPage--" :disabled="currentPage === 1" class="rounded-lg px-3 py-1.5 text-xs font-medium text-gray-600 hover:bg-gray-100 disabled:opacity-40 transition">← Prev</button>
                    <button v-for="p in totalPages" :key="p" @click="currentPage = p" class="rounded-lg px-3 py-1.5 text-xs font-medium transition" :class="p === currentPage ? 'bg-green-600 text-white' : 'text-gray-600 hover:bg-gray-100'">{{ p }}</button>
                    <button @click="currentPage++" :disabled="currentPage === totalPages" class="rounded-lg px-3 py-1.5 text-xs font-medium text-gray-600 hover:bg-gray-100 disabled:opacity-40 transition">Next →</button>
                </div>
            </div>
        </div>

        <!-- Tabel Data Penduduk (Hanya Saat Print) -->
        <div class="hidden print:block w-full">
            <table class="w-full text-sm whitespace-nowrap border-collapse border border-gray-800">
                <thead>
                    <tr class="bg-gray-100 border-b border-gray-800">
                        <th class="px-2 py-1.5 text-left border border-gray-800 text-xs font-semibold text-gray-800">No. KK</th>
                        <th class="px-2 py-1.5 text-left border border-gray-800 text-xs font-semibold text-gray-800">NIK</th>
                        <th class="px-2 py-1.5 text-left border border-gray-800 text-xs font-semibold text-gray-800">Nama Warga</th>
                        <th class="px-2 py-1.5 text-center border border-gray-800 text-xs font-semibold text-gray-800">L/P</th>
                        <th class="px-2 py-1.5 text-left border border-gray-800 text-xs font-semibold text-gray-800">Tgl Lahir</th>
                        <th class="px-2 py-1.5 text-left border border-gray-800 text-xs font-semibold text-gray-800">Hubungan</th>
                        <th class="px-2 py-1.5 text-left border border-gray-800 text-xs font-semibold text-gray-800">Dasawisma</th>
                        <th class="px-2 py-1.5 text-center border border-gray-800 text-xs font-semibold text-gray-800">RT/RW</th>
                    </tr>
                </thead>
                <tbody>
                    <template v-if="filteredFamilies.length === 0">
                        <tr>
                            <td colspan="8" class="px-2 py-4 text-center border border-gray-800 text-gray-500">Tidak ada data penduduk ditemukan.</td>
                        </tr>
                    </template>
                    <template v-else>
                        <template v-for="kk in filteredFamilies" :key="'print-kk-'+kk.id">
                            <tr v-for="anggota in kk.anggota_keluargas" :key="'print-anggota-'+anggota.id" class="break-inside-avoid">
                                <td class="px-2 py-1 border border-gray-800 font-mono text-[11px] align-top">{{ kk.no_kk }}</td>
                                <td class="px-2 py-1 border border-gray-800 font-mono text-[11px] align-top">{{ anggota.nik }}</td>
                                <td class="px-2 py-1 border border-gray-800 text-[11px] align-top">{{ anggota.nama_anggota }}</td>
                                <td class="px-2 py-1 border border-gray-800 text-[11px] text-center align-top">{{ anggota.jenis_kelamin }}</td>
                                <td class="px-2 py-1 border border-gray-800 text-[11px] align-top whitespace-nowrap">{{ formatDate(anggota.tanggal_lahir) }}</td>
                                <td class="px-2 py-1 border border-gray-800 text-[11px] align-top">{{ anggota.status_dalam_keluarga }}</td>
                                <td class="px-2 py-1 border border-gray-800 text-[11px] align-top">{{ kk.dasawisma?.nama_dasawisma }}</td>
                                <td class="px-2 py-1 border border-gray-800 text-[11px] text-center align-top">{{ kk.dasawisma?.rt }}/{{ kk.dasawisma?.rw }}</td>
                            </tr>
                        </template>
                    </template>
                </tbody>
            </table>
        </div>
    </AppLayout>
</template>
