<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import { computed, ref, watch } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';

const props = defineProps<{
    keluargas: any[];
    stats: {
        total_kk: number;
        total_warga: number;
        balita_laki: number;
        balita_perempuan: number;
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
    router.get(window.location.pathname, {
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

function downloadPdf() {
    const url = new URL(window.location.href);
    url.pathname = url.pathname.replace(/\/$/, '') + '/download-pdf';
    
    // Gunakan window.location.href agar jika terjadi error (seperti session expired),
    // browser akan menampilkannya dengan jelas, dan jika sukses akan langsung terdownload.
    window.location.href = url.toString();
}

function printReport() {
    window.print();
}

function formatDate(dateStr: string) {
    if (!dateStr) return '-';
    const d = new Date(dateStr);
    return d.toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' });
}

const totals = computed(() => {
    let t = {
        kk: 0, l: 0, p: 0,
        bl: 0, bp: 0, pus: 0, wus: 0, bumil: 0, busui: 0, lansia: 0, buta: 0, khusus: 0,
        r_sehat: 0, r_tdksehat: 0, r_sampah: 0, r_spal: 0, r_jamban: 0, r_pmi: 0,
        a_pdam: 0, a_sumur: 0, a_dr: 0, m_beras: 0, m_nonberas: 0,
        k_up2k: 0, k_tanah: 0, k_industri: 0, k_bakti: 0
    };
    filteredFamilies.value.forEach((kk: any) => {
        t.kk += kk.jumlah_kk || 0;
        t.l += kk.jumlah_laki_laki || 0;
        t.p += kk.jumlah_perempuan || 0;
        t.bl += kk.jumlah_balita_laki || 0;
        t.bp += kk.jumlah_balita_perempuan || 0;
        t.pus += kk.jumlah_pus || 0;
        t.wus += kk.jumlah_wus || 0;
        t.bumil += kk.jumlah_ibu_hamil || 0;
        t.busui += kk.jumlah_ibu_menyusui || 0;
        t.lansia += kk.jumlah_lansia || 0;
        t.buta += kk.jumlah_buta || 0;
        t.khusus += kk.jumlah_berkebutuhan_khusus || 0;

        if (kk.sehat_layak_huni) t.r_sehat++; else t.r_tdksehat++;
        if (kk.memiliki_tempat_sampah) t.r_sampah++;
        if (kk.memiliki_spal) t.r_spal++;
        if (kk.memiliki_jamban) t.r_jamban++;
        if (kk.menempel_stiker_p4k) t.r_pmi++;

        if (kk.sumber_air === 'PDAM') t.a_pdam++;
        if (kk.sumber_air === 'Sumur') t.a_sumur++;
        if (kk.sumber_air === 'Lainnya' || kk.sumber_air === 'Sungai') t.a_dr++;

        if (kk.makanan_pokok === 'Beras') t.m_beras++;
        if (kk.makanan_pokok && kk.makanan_pokok !== 'Beras') t.m_nonberas++;

        if (kk.ikut_up2k) t.k_up2k++;
        if (kk.ikut_pekarangan) t.k_tanah++;
        if (kk.ikut_industri) t.k_industri++;
        if (kk.ikut_kerja_bakti) t.k_bakti++;
    });
    return t;
});
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
            <div class="flex gap-2">
                <button @click="downloadPdf" class="inline-flex items-center gap-2 rounded-xl bg-red-600 px-4 py-2.5 text-sm font-medium text-white shadow-sm hover:bg-red-700 transition">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Download PDF
                </button>
                <button @click="printReport" class="inline-flex items-center gap-2 rounded-xl bg-gray-800 px-4 py-2.5 text-sm font-medium text-white shadow-sm hover:bg-gray-900 transition">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    Cetak Laporan
                </button>
            </div>
        </div>

        <!-- Print Header (Hidden on screen) -->
        <div class="hidden print:block mb-8 text-center">
            <h1 class="text-2xl font-bold">Laporan Rekapitulasi Data Warga</h1>
            <p class="text-gray-600">Aplikasi Pendataan Warga TP-PKK Berbasis Website Pada Dasawisma Desa Dompas</p>
            <div class="mt-4 flex flex-wrap justify-center gap-x-6 gap-y-2 text-sm font-medium border-t border-b border-gray-200 py-3">
                <p v-if="props.active_filters?.rt">RT: <span class="text-gray-800">{{ props.active_filters.rt }}</span></p>
                <p v-if="props.active_filters?.rw">RW: <span class="text-gray-800">{{ props.active_filters.rw }}</span></p>
                <p v-if="props.active_filters?.dasawisma_id">Dasawisma: <span class="text-gray-800">{{ dasawismas.find(d => d.id == props.active_filters.dasawisma_id)?.nama_dasawisma }}</span></p>
                <p v-if="search">Pencarian: <span class="text-gray-800">"{{ search }}"</span></p>
                <p v-if="!props.active_filters?.rt && !props.active_filters?.rw && !props.active_filters?.dasawisma_id && !search" class="text-gray-500">Semua Data Desa</p>
            </div>
        </div>

        <!-- Statistik Cards -->
        <div class="mb-6 grid grid-cols-2 gap-4 md:grid-cols-4 xl:grid-cols-7 print:hidden">
            <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
                <p class="text-3xl font-bold text-blue-600">{{ stats.total_kk }}</p>
                <p class="text-xs font-medium text-gray-500 mt-1 uppercase tracking-wide">Total KK</p>
            </div>
            <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
                <p class="text-3xl font-bold text-green-600">{{ stats.total_warga }}</p>
                <p class="text-xs font-medium text-gray-500 mt-1 uppercase tracking-wide">Total Warga</p>
            </div>
            <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
                <p class="text-3xl font-bold text-sky-600">{{ stats.balita_laki }}</p>
                <p class="text-xs font-medium text-gray-500 mt-1 uppercase tracking-wide">Balita Laki-laki</p>
            </div>
            <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
                <p class="text-3xl font-bold text-pink-600">{{ stats.balita_perempuan }}</p>
                <p class="text-xs font-medium text-gray-500 mt-1 uppercase tracking-wide">Balita Perempuan</p>
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

        <!-- Tabel Rekapitulasi (Screen & Print) -->
        <div class="rounded-2xl bg-white shadow-sm border border-gray-100 print:border-none print:shadow-none overflow-hidden print:overflow-visible">
            <div class="overflow-x-auto print:overflow-visible">
                <table class="w-full text-sm whitespace-nowrap border-collapse print:text-[8px]">
                    <thead>
                        <tr class="bg-gray-50 print:bg-transparent">
                            <th rowspan="2" class="px-2 py-2 text-center text-[10px] print:text-[6px] font-bold uppercase text-gray-600 print:text-black border border-gray-200 print:border-gray-800">NO</th>
                            <th rowspan="2" class="px-3 py-2 text-left text-[10px] print:text-[6px] font-bold uppercase text-gray-600 print:text-black border border-gray-200 print:border-gray-800 sticky left-0 z-10 bg-gray-50 print:static print:bg-transparent min-w-[150px]">NAMA KEPALA RUMAH TANGGA</th>
                            <th rowspan="2" class="px-2 py-2 text-center text-[10px] print:text-[6px] font-bold uppercase text-gray-600 print:text-black border border-gray-200 print:border-gray-800">JML<br>KK</th>
                            <th colspan="2" class="px-2 py-1 text-center text-[10px] print:text-[6px] font-bold uppercase text-gray-600 print:text-black border border-gray-200 print:border-gray-800">TOTAL</th>
                            <th colspan="2" class="px-2 py-1 text-center text-[10px] print:text-[6px] font-bold uppercase text-gray-600 print:text-black border border-gray-200 print:border-gray-800">BALITA</th>
                            <th rowspan="2" class="px-2 py-2 text-center text-[10px] print:text-[6px] font-bold uppercase text-gray-600 print:text-black border border-gray-200 print:border-gray-800">PUS</th>
                            <th rowspan="2" class="px-2 py-2 text-center text-[10px] print:text-[6px] font-bold uppercase text-gray-600 print:text-black border border-gray-200 print:border-gray-800">WUS</th>
                            <th rowspan="2" class="px-2 py-2 text-center text-[10px] print:text-[6px] font-bold uppercase text-gray-600 print:text-black border border-gray-200 print:border-gray-800">IBU<br>HAMIL</th>
                            <th rowspan="2" class="px-2 py-2 text-center text-[10px] print:text-[6px] font-bold uppercase text-gray-600 print:text-black border border-gray-200 print:border-gray-800">IBU<br>MENYUSUI</th>
                            <th rowspan="2" class="px-2 py-2 text-center text-[10px] print:text-[6px] font-bold uppercase text-gray-600 print:text-black border border-gray-200 print:border-gray-800">LANSIA</th>
                            <th rowspan="2" class="px-2 py-2 text-center text-[10px] print:text-[6px] font-bold uppercase text-gray-600 print:text-black border border-gray-200 print:border-gray-800">BUTA</th>
                            <th rowspan="2" class="px-2 py-2 text-center text-[10px] print:text-[6px] font-bold uppercase text-gray-600 print:text-black border border-gray-200 print:border-gray-800">BERKEBUTUHAN<br>KHUSUS</th>
                            <th colspan="6" class="px-2 py-1 text-center text-[10px] print:text-[6px] font-bold uppercase text-gray-600 print:text-black border border-gray-200 print:border-gray-800">KRITERIA RUMAH</th>
                            <th colspan="3" class="px-2 py-1 text-center text-[10px] print:text-[6px] font-bold uppercase text-gray-600 print:text-black border border-gray-200 print:border-gray-800">SUMBER AIR KELUARGA</th>
                            <th colspan="2" class="px-2 py-1 text-center text-[10px] print:text-[6px] font-bold uppercase text-gray-600 print:text-black border border-gray-200 print:border-gray-800">MAKANAN POKOK</th>
                            <th colspan="4" class="px-2 py-1 text-center text-[10px] print:text-[6px] font-bold uppercase text-gray-600 print:text-black border border-gray-200 print:border-gray-800">KEGIATAN WARGA</th>
                            <th rowspan="2" class="px-2 py-2 text-center text-[10px] print:text-[6px] font-bold uppercase text-gray-600 print:text-black border border-gray-200 print:border-gray-800">KET</th>
                        </tr>
                        <tr class="bg-gray-50 print:bg-transparent">
                            <th class="px-2 py-1 text-center text-[10px] print:text-[6px] font-bold uppercase text-gray-600 print:text-black border border-gray-200 print:border-gray-800">L</th>
                            <th class="px-2 py-1 text-center text-[10px] print:text-[6px] font-bold uppercase text-gray-600 print:text-black border border-gray-200 print:border-gray-800">P</th>
                            <th class="px-2 py-1 text-center text-[10px] print:text-[6px] font-bold uppercase text-gray-600 print:text-black border border-gray-200 print:border-gray-800">L</th>
                            <th class="px-2 py-1 text-center text-[10px] print:text-[6px] font-bold uppercase text-gray-600 print:text-black border border-gray-200 print:border-gray-800">P</th>
                            
                            <th class="px-2 py-1 text-center text-[10px] print:text-[6px] font-bold uppercase text-gray-600 print:text-black border border-gray-200 print:border-gray-800">SEHAT<br>LAYAK<br>HUNI</th>
                            <th class="px-2 py-1 text-center text-[10px] print:text-[6px] font-bold uppercase text-gray-600 print:text-black border border-gray-200 print:border-gray-800">TDK<br>SEHAT</th>
                            <th class="px-2 py-1 text-center text-[10px] print:text-[6px] font-bold uppercase text-gray-600 print:text-black border border-gray-200 print:border-gray-800">TMP<br>SAMPAH</th>
                            <th class="px-2 py-1 text-center text-[10px] print:text-[6px] font-bold uppercase text-gray-600 print:text-black border border-gray-200 print:border-gray-800">SPAL</th>
                            <th class="px-2 py-1 text-center text-[10px] print:text-[6px] font-bold uppercase text-gray-600 print:text-black border border-gray-200 print:border-gray-800">JAMBAN</th>
                            <th class="px-2 py-1 text-center text-[10px] print:text-[6px] font-bold uppercase text-gray-600 print:text-black border border-gray-200 print:border-gray-800">STIKER<br>PMI</th>
                            
                            <th class="px-2 py-1 text-center text-[10px] print:text-[6px] font-bold uppercase text-gray-600 print:text-black border border-gray-200 print:border-gray-800">PDAM</th>
                            <th class="px-2 py-1 text-center text-[10px] print:text-[6px] font-bold uppercase text-gray-600 print:text-black border border-gray-200 print:border-gray-800">SUMUR</th>
                            <th class="px-2 py-1 text-center text-[10px] print:text-[6px] font-bold uppercase text-gray-600 print:text-black border border-gray-200 print:border-gray-800">DLL</th>
                            
                            <th class="px-2 py-1 text-center text-[10px] print:text-[6px] font-bold uppercase text-gray-600 print:text-black border border-gray-200 print:border-gray-800">BERAS</th>
                            <th class="px-2 py-1 text-center text-[10px] print:text-[6px] font-bold uppercase text-gray-600 print:text-black border border-gray-200 print:border-gray-800">NON<br>BERAS</th>
                            
                            <th class="px-2 py-1 text-center text-[10px] print:text-[6px] font-bold uppercase text-gray-600 print:text-black border border-gray-200 print:border-gray-800">UP2K</th>
                            <th class="px-2 py-1 text-center text-[10px] print:text-[6px] font-bold uppercase text-gray-600 print:text-black border border-gray-200 print:border-gray-800">MNFT<br>TANAH</th>
                            <th class="px-2 py-1 text-center text-[10px] print:text-[6px] font-bold uppercase text-gray-600 print:text-black border border-gray-200 print:border-gray-800">INDUSTRI<br>RUMAH TANGGA</th>
                            <th class="px-2 py-1 text-center text-[10px] print:text-[6px] font-bold uppercase text-gray-600 print:text-black border border-gray-200 print:border-gray-800">KERJA<br>BAKTI</th>
                        </tr>
                    </thead>
                    <tbody class="print:hidden">
                        <tr v-if="paginated.length === 0">
                            <td colspan="30" class="py-12 text-center text-gray-400 border border-gray-200">Tidak ada data laporan ditemukan.</td>
                        </tr>
                        <template v-else>
                            <tr v-for="(kk, idx) in paginated" :key="'screen-'+kk.id" class="hover:bg-gray-50 transition">
                                <td class="px-2 py-2 text-center text-xs text-gray-600 border border-gray-200">{{ ((currentPage - 1) * perPage) + idx + 1 }}</td>
                                <td class="px-3 py-2 text-left text-xs font-medium text-gray-800 border border-gray-200 sticky left-0 z-10 bg-white group-hover:bg-gray-50">{{ kk.nama_kepala_keluarga?.toUpperCase() }}</td>
                                <td class="px-2 py-2 text-center text-xs text-gray-600 border border-gray-200">{{ kk.jumlah_kk || '-' }}</td>
                                <td class="px-2 py-2 text-center text-xs text-gray-600 border border-gray-200">{{ kk.jumlah_laki_laki || '-' }}</td>
                                <td class="px-2 py-2 text-center text-xs text-gray-600 border border-gray-200">{{ kk.jumlah_perempuan || '-' }}</td>
                                <td class="px-2 py-2 text-center text-xs text-gray-600 border border-gray-200">{{ kk.jumlah_balita_laki || '-' }}</td>
                                <td class="px-2 py-2 text-center text-xs text-gray-600 border border-gray-200">{{ kk.jumlah_balita_perempuan || '-' }}</td>
                                <td class="px-2 py-2 text-center text-xs text-gray-600 border border-gray-200">{{ kk.jumlah_pus || '-' }}</td>
                                <td class="px-2 py-2 text-center text-xs text-gray-600 border border-gray-200">{{ kk.jumlah_wus || '-' }}</td>
                                <td class="px-2 py-2 text-center text-xs text-gray-600 border border-gray-200">{{ kk.jumlah_ibu_hamil || '-' }}</td>
                                <td class="px-2 py-2 text-center text-xs text-gray-600 border border-gray-200">{{ kk.jumlah_ibu_menyusui || '-' }}</td>
                                <td class="px-2 py-2 text-center text-xs text-gray-600 border border-gray-200">{{ kk.jumlah_lansia || '-' }}</td>
                                <td class="px-2 py-2 text-center text-xs text-gray-600 border border-gray-200">{{ kk.jumlah_buta || '-' }}</td>
                                <td class="px-2 py-2 text-center text-xs text-gray-600 border border-gray-200">{{ kk.jumlah_berkebutuhan_khusus || '-' }}</td>
                                
                                <td class="px-2 py-2 text-center text-xs font-bold text-gray-600 border border-gray-200"><i v-if="kk.sehat_layak_huni" class="fa-solid fa-check text-green-600"></i><span v-else>-</span></td>
                                <td class="px-2 py-2 text-center text-xs font-bold text-gray-600 border border-gray-200"><i v-if="!kk.sehat_layak_huni" class="fa-solid fa-check text-green-600"></i><span v-else>-</span></td>
                                <td class="px-2 py-2 text-center text-xs font-bold text-gray-600 border border-gray-200"><i v-if="kk.memiliki_tempat_sampah" class="fa-solid fa-check text-green-600"></i><span v-else>-</span></td>
                                <td class="px-2 py-2 text-center text-xs font-bold text-gray-600 border border-gray-200"><i v-if="kk.memiliki_spal" class="fa-solid fa-check text-green-600"></i><span v-else>-</span></td>
                                <td class="px-2 py-2 text-center text-xs font-bold text-gray-600 border border-gray-200"><i v-if="kk.memiliki_jamban" class="fa-solid fa-check text-green-600"></i><span v-else>-</span></td>
                                <td class="px-2 py-2 text-center text-xs font-bold text-gray-600 border border-gray-200"><i v-if="kk.menempel_stiker_p4k" class="fa-solid fa-check text-green-600"></i><span v-else>-</span></td>
                                
                                <td class="px-2 py-2 text-center text-xs font-bold text-gray-600 border border-gray-200"><i v-if="kk.sumber_air === 'PDAM'" class="fa-solid fa-check text-green-600"></i><span v-else>-</span></td>
                                <td class="px-2 py-2 text-center text-xs font-bold text-gray-600 border border-gray-200"><i v-if="kk.sumber_air === 'Sumur'" class="fa-solid fa-check text-green-600"></i><span v-else>-</span></td>
                                <td class="px-2 py-2 text-center text-xs font-bold text-gray-600 border border-gray-200"><i v-if="(kk.sumber_air === 'Lainnya' || kk.sumber_air === 'Sungai')" class="fa-solid fa-check text-green-600"></i><span v-else>-</span></td>
                                
                                <td class="px-2 py-2 text-center text-xs font-bold text-gray-600 border border-gray-200"><i v-if="kk.makanan_pokok === 'Beras'" class="fa-solid fa-check text-green-600"></i><span v-else>-</span></td>
                                <td class="px-2 py-2 text-center text-xs font-bold text-gray-600 border border-gray-200"><i v-if="(kk.makanan_pokok !== 'Beras' && kk.makanan_pokok)" class="fa-solid fa-check text-green-600"></i><span v-else>-</span></td>
                                
                                <td class="px-2 py-2 text-center text-xs font-bold text-gray-600 border border-gray-200"><i v-if="kk.ikut_up2k" class="fa-solid fa-check text-green-600"></i><span v-else>-</span></td>
                                <td class="px-2 py-2 text-center text-xs font-bold text-gray-600 border border-gray-200"><i v-if="kk.ikut_pekarangan" class="fa-solid fa-check text-green-600"></i><span v-else>-</span></td>
                                <td class="px-2 py-2 text-center text-xs font-bold text-gray-600 border border-gray-200"><i v-if="kk.ikut_industri" class="fa-solid fa-check text-green-600"></i><span v-else>-</span></td>
                                <td class="px-2 py-2 text-center text-xs font-bold text-gray-600 border border-gray-200"><i v-if="kk.ikut_kerja_bakti" class="fa-solid fa-check text-green-600"></i><span v-else>-</span></td>
                                <td class="px-2 py-2 text-center text-xs text-gray-600 border border-gray-200">-</td>
                            </tr>
                        </template>
                    </tbody>
                    <tbody class="hidden print:table-row-group">
                        <tr v-if="filteredFamilies.length === 0">
                            <td colspan="30" class="py-12 text-center text-[8px] text-gray-900 border border-gray-800">Tidak ada data laporan ditemukan.</td>
                        </tr>
                        <template v-else>
                            <tr v-for="(kk, idx) in filteredFamilies" :key="'print-'+kk.id">
                                <td class="px-1 py-1 text-center text-[7px] text-black border border-gray-800">{{ idx + 1 }}</td>
                                <td class="px-2 py-1 text-left text-[7px] font-medium text-black border border-gray-800 truncate max-w-[120px]">{{ kk.nama_kepala_keluarga?.toUpperCase() }}</td>
                                <td class="px-1 py-1 text-center text-[7px] text-black border border-gray-800">{{ kk.jumlah_kk || '-' }}</td>
                                <td class="px-1 py-1 text-center text-[7px] text-black border border-gray-800">{{ kk.jumlah_laki_laki || '-' }}</td>
                                <td class="px-1 py-1 text-center text-[7px] text-black border border-gray-800">{{ kk.jumlah_perempuan || '-' }}</td>
                                <td class="px-1 py-1 text-center text-[7px] text-black border border-gray-800">{{ kk.jumlah_balita_laki || '-' }}</td>
                                <td class="px-1 py-1 text-center text-[7px] text-black border border-gray-800">{{ kk.jumlah_balita_perempuan || '-' }}</td>
                                <td class="px-1 py-1 text-center text-[7px] text-black border border-gray-800">{{ kk.jumlah_pus || '-' }}</td>
                                <td class="px-1 py-1 text-center text-[7px] text-black border border-gray-800">{{ kk.jumlah_wus || '-' }}</td>
                                <td class="px-1 py-1 text-center text-[7px] text-black border border-gray-800">{{ kk.jumlah_ibu_hamil || '-' }}</td>
                                <td class="px-1 py-1 text-center text-[7px] text-black border border-gray-800">{{ kk.jumlah_ibu_menyusui || '-' }}</td>
                                <td class="px-1 py-1 text-center text-[7px] text-black border border-gray-800">{{ kk.jumlah_lansia || '-' }}</td>
                                <td class="px-1 py-1 text-center text-[7px] text-black border border-gray-800">{{ kk.jumlah_buta || '-' }}</td>
                                <td class="px-1 py-1 text-center text-[7px] text-black border border-gray-800">{{ kk.jumlah_berkebutuhan_khusus || '-' }}</td>
                                
                                <td class="px-1 py-1 text-center text-[7px] font-bold text-black border border-gray-800"><i v-if="kk.sehat_layak_huni" class="fa-solid fa-check text-green-600"></i><span v-else>-</span></td>
                                <td class="px-1 py-1 text-center text-[7px] font-bold text-black border border-gray-800"><i v-if="!kk.sehat_layak_huni" class="fa-solid fa-check text-green-600"></i><span v-else>-</span></td>
                                <td class="px-1 py-1 text-center text-[7px] font-bold text-black border border-gray-800"><i v-if="kk.memiliki_tempat_sampah" class="fa-solid fa-check text-green-600"></i><span v-else>-</span></td>
                                <td class="px-1 py-1 text-center text-[7px] font-bold text-black border border-gray-800"><i v-if="kk.memiliki_spal" class="fa-solid fa-check text-green-600"></i><span v-else>-</span></td>
                                <td class="px-1 py-1 text-center text-[7px] font-bold text-black border border-gray-800"><i v-if="kk.memiliki_jamban" class="fa-solid fa-check text-green-600"></i><span v-else>-</span></td>
                                <td class="px-1 py-1 text-center text-[7px] font-bold text-black border border-gray-800"><i v-if="kk.menempel_stiker_p4k" class="fa-solid fa-check text-green-600"></i><span v-else>-</span></td>
                                
                                <td class="px-1 py-1 text-center text-[7px] font-bold text-black border border-gray-800"><i v-if="kk.sumber_air === 'PDAM'" class="fa-solid fa-check text-green-600"></i><span v-else>-</span></td>
                                <td class="px-1 py-1 text-center text-[7px] font-bold text-black border border-gray-800"><i v-if="kk.sumber_air === 'Sumur'" class="fa-solid fa-check text-green-600"></i><span v-else>-</span></td>
                                <td class="px-1 py-1 text-center text-[7px] font-bold text-black border border-gray-800"><i v-if="(kk.sumber_air === 'Lainnya' || kk.sumber_air === 'Sungai')" class="fa-solid fa-check text-green-600"></i><span v-else>-</span></td>
                                
                                <td class="px-1 py-1 text-center text-[7px] font-bold text-black border border-gray-800"><i v-if="kk.makanan_pokok === 'Beras'" class="fa-solid fa-check text-green-600"></i><span v-else>-</span></td>
                                <td class="px-1 py-1 text-center text-[7px] font-bold text-black border border-gray-800"><i v-if="(kk.makanan_pokok !== 'Beras' && kk.makanan_pokok)" class="fa-solid fa-check text-green-600"></i><span v-else>-</span></td>
                                
                                <td class="px-1 py-1 text-center text-[7px] font-bold text-black border border-gray-800"><i v-if="kk.ikut_up2k" class="fa-solid fa-check text-green-600"></i><span v-else>-</span></td>
                                <td class="px-1 py-1 text-center text-[7px] font-bold text-black border border-gray-800"><i v-if="kk.ikut_pekarangan" class="fa-solid fa-check text-green-600"></i><span v-else>-</span></td>
                                <td class="px-1 py-1 text-center text-[7px] font-bold text-black border border-gray-800"><i v-if="kk.ikut_industri" class="fa-solid fa-check text-green-600"></i><span v-else>-</span></td>
                                <td class="px-1 py-1 text-center text-[7px] font-bold text-black border border-gray-800"><i v-if="kk.ikut_kerja_bakti" class="fa-solid fa-check text-green-600"></i><span v-else>-</span></td>
                                <td class="px-1 py-1 text-center text-[7px] text-black border border-gray-800">-</td>
                            </tr>
                        </template>
                    </tbody>
                    <tfoot class="print:table-footer-group">
                        <tr class="font-bold bg-gray-100 print:bg-transparent border-t-2 border-gray-300 print:border-gray-800">
                            <td colspan="2" class="px-3 py-2 text-left text-[10px] print:text-[7px] text-gray-800 print:text-black border border-gray-200 print:border-gray-800 sticky left-0 z-10 bg-gray-100 print:static print:bg-transparent">JUMLAH</td>
                            <td class="px-2 py-2 text-center text-xs print:text-[7px] text-gray-800 print:text-black border border-gray-200 print:border-gray-800">{{ totals.kk || '-' }}</td>
                            <td class="px-2 py-2 text-center text-xs print:text-[7px] text-gray-800 print:text-black border border-gray-200 print:border-gray-800">{{ totals.l || '-' }}</td>
                            <td class="px-2 py-2 text-center text-xs print:text-[7px] text-gray-800 print:text-black border border-gray-200 print:border-gray-800">{{ totals.p || '-' }}</td>
                            <td class="px-2 py-2 text-center text-xs print:text-[7px] text-gray-800 print:text-black border border-gray-200 print:border-gray-800">{{ totals.bl || '-' }}</td>
                            <td class="px-2 py-2 text-center text-xs print:text-[7px] text-gray-800 print:text-black border border-gray-200 print:border-gray-800">{{ totals.bp || '-' }}</td>
                            <td class="px-2 py-2 text-center text-xs print:text-[7px] text-gray-800 print:text-black border border-gray-200 print:border-gray-800">{{ totals.pus || '-' }}</td>
                            <td class="px-2 py-2 text-center text-xs print:text-[7px] text-gray-800 print:text-black border border-gray-200 print:border-gray-800">{{ totals.wus || '-' }}</td>
                            <td class="px-2 py-2 text-center text-xs print:text-[7px] text-gray-800 print:text-black border border-gray-200 print:border-gray-800">{{ totals.bumil || '-' }}</td>
                            <td class="px-2 py-2 text-center text-xs print:text-[7px] text-gray-800 print:text-black border border-gray-200 print:border-gray-800">{{ totals.busui || '-' }}</td>
                            <td class="px-2 py-2 text-center text-xs print:text-[7px] text-gray-800 print:text-black border border-gray-200 print:border-gray-800">{{ totals.lansia || '-' }}</td>
                            <td class="px-2 py-2 text-center text-xs print:text-[7px] text-gray-800 print:text-black border border-gray-200 print:border-gray-800">{{ totals.buta || '-' }}</td>
                            <td class="px-2 py-2 text-center text-xs print:text-[7px] text-gray-800 print:text-black border border-gray-200 print:border-gray-800">{{ totals.khusus || '-' }}</td>
                            
                            <td class="px-2 py-2 text-center text-xs print:text-[7px] text-gray-800 print:text-black border border-gray-200 print:border-gray-800">{{ totals.r_sehat || '-' }}</td>
                            <td class="px-2 py-2 text-center text-xs print:text-[7px] text-gray-800 print:text-black border border-gray-200 print:border-gray-800">{{ totals.r_tdksehat || '-' }}</td>
                            <td class="px-2 py-2 text-center text-xs print:text-[7px] text-gray-800 print:text-black border border-gray-200 print:border-gray-800">{{ totals.r_sampah || '-' }}</td>
                            <td class="px-2 py-2 text-center text-xs print:text-[7px] text-gray-800 print:text-black border border-gray-200 print:border-gray-800">{{ totals.r_spal || '-' }}</td>
                            <td class="px-2 py-2 text-center text-xs print:text-[7px] text-gray-800 print:text-black border border-gray-200 print:border-gray-800">{{ totals.r_jamban || '-' }}</td>
                            <td class="px-2 py-2 text-center text-xs print:text-[7px] text-gray-800 print:text-black border border-gray-200 print:border-gray-800">{{ totals.r_pmi || '-' }}</td>
                            
                            <td class="px-2 py-2 text-center text-xs print:text-[7px] text-gray-800 print:text-black border border-gray-200 print:border-gray-800">{{ totals.a_pdam || '-' }}</td>
                            <td class="px-2 py-2 text-center text-xs print:text-[7px] text-gray-800 print:text-black border border-gray-200 print:border-gray-800">{{ totals.a_sumur || '-' }}</td>
                            <td class="px-2 py-2 text-center text-xs print:text-[7px] text-gray-800 print:text-black border border-gray-200 print:border-gray-800">{{ totals.a_dr || '-' }}</td>
                            
                            <td class="px-2 py-2 text-center text-xs print:text-[7px] text-gray-800 print:text-black border border-gray-200 print:border-gray-800">{{ totals.m_beras || '-' }}</td>
                            <td class="px-2 py-2 text-center text-xs print:text-[7px] text-gray-800 print:text-black border border-gray-200 print:border-gray-800">{{ totals.m_nonberas || '-' }}</td>
                            
                            <td class="px-2 py-2 text-center text-xs print:text-[7px] text-gray-800 print:text-black border border-gray-200 print:border-gray-800">{{ totals.k_up2k || '-' }}</td>
                            <td class="px-2 py-2 text-center text-xs print:text-[7px] text-gray-800 print:text-black border border-gray-200 print:border-gray-800">{{ totals.k_tanah || '-' }}</td>
                            <td class="px-2 py-2 text-center text-xs print:text-[7px] text-gray-800 print:text-black border border-gray-200 print:border-gray-800">{{ totals.k_industri || '-' }}</td>
                            <td class="px-2 py-2 text-center text-xs print:text-[7px] text-gray-800 print:text-black border border-gray-200 print:border-gray-800">{{ totals.k_bakti || '-' }}</td>
                            <td class="px-2 py-2 text-center text-xs print:text-[7px] text-gray-800 print:text-black border border-gray-200 print:border-gray-800">-</td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- Pagination -->
            <div v-if="totalPages > 1" class="flex items-center justify-between border-t border-gray-100 px-5 py-3 print:hidden">
                <p class="text-xs text-gray-400">Menampilkan {{ paginated.length }} dari {{ filteredFamilies.length }} KK</p>
                <div class="flex gap-1">
                    <button @click="currentPage--" :disabled="currentPage === 1" class="rounded-lg px-3 py-1.5 text-xs font-medium text-gray-600 hover:bg-gray-100 disabled:opacity-40 transition">← Prev</button>
                    <button v-for="p in totalPages" :key="p" @click="currentPage = p" class="rounded-lg px-3 py-1.5 text-xs font-medium transition" :class="p === currentPage ? 'bg-green-600 text-white' : 'text-gray-600 hover:bg-gray-100'">{{ p }}</button>
                    <button @click="currentPage++" :disabled="currentPage === totalPages" class="rounded-lg px-3 py-1.5 text-xs font-medium text-gray-600 hover:bg-gray-100 disabled:opacity-40 transition">Next →</button>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped>
@media print {
    * {
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }
    table, th, td {
        border: 1px solid black !important;
        color: black !important;
    }
    thead th {
        background-color: #f3f4f6 !important;
        font-weight: bold !important;
    }
    tfoot td {
        background-color: #f3f4f6 !important;
        font-weight: bold !important;
    }
}
</style>
