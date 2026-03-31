<script setup lang="ts">
import { Head, router, usePage } from '@inertiajs/vue3';
import { computed, ref, watch } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';
import SidaAlert from '@/components/SidaAlert.vue';
import { debounce } from 'lodash';

const props = defineProps<{
    verifikasis: {
        data: any[];
        links: any[];
        current_page: number;
        last_page: number;
        total: number;
        per_page: number;
    };
    status_counts: {
        semua: number;
        menunggu: number;
        disetujui: number;
        ditolak: number;
    };
    filter_status: string;
    filter_search: string;
}>();

const page = usePage();
const flash = computed(() => (page.props as any).flash);
const showAlert = ref(true);
watch(() => flash.value, () => { showAlert.value = true; });

const search = ref(props.filter_search);
const statusFilter = ref(props.filter_status);

// Debounced search
const performSearch = debounce(() => {
    router.get('/admin/verifikasi', {
        search: search.value,
        status: statusFilter.value
    }, {
        preserveState: true,
        preserveScroll: true,
        replace: true
    });
}, 500);

watch(search, () => {
    performSearch();
});

function filterByStatus(status: string) {
    statusFilter.value = status;
    router.get('/admin/verifikasi', {
        search: search.value,
        status: status
    }, {
        preserveState: true,
        preserveScroll: true,
        replace: true
    });
}
</script>

<template>
    <Head title="Verifikasi Data" />
    <AppLayout>
        <SidaAlert :show="showAlert" :type="flash?.error ? 'error' : 'success'" :message="flash?.success || flash?.error" @close="showAlert = false" />

        <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Verifikasi Data KK</h1>
                <p class="mt-1 text-sm text-gray-500">Tinjau dan proses data keluarga yang dikirim oleh kader</p>
            </div>
            
            <div class="flex items-center gap-2">
                <div class="relative w-64">
                    <svg class="absolute left-3 top-2.5 h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input 
                        v-model="search" 
                        type="text" 
                        placeholder="Cari No. KK atau Nama..." 
                        class="w-full rounded-xl border border-gray-200 bg-white py-2 pl-9 pr-4 text-sm shadow-sm transition-all focus:border-emerald-400 focus:outline-none focus:ring-4 focus:ring-emerald-50" 
                    />
                </div>
            </div>
        </div>

        <!-- Filter Tabs -->
        <div class="mb-6 flex flex-wrap items-center gap-2 border-b border-gray-100 pb-1">
            <button 
                @click="filterByStatus('')"
                class="relative px-4 py-2 text-sm font-semibold transition-all"
                :class="statusFilter === '' ? 'text-emerald-700' : 'text-gray-500 hover:text-gray-700'"
            >
                Semua
                <span class="ml-1.5 rounded-full bg-gray-100 px-2 py-0.5 text-[10px] text-gray-600">{{ status_counts.semua }}</span>
                <div v-if="statusFilter === ''" class="absolute bottom-0 left-0 h-0.5 w-full bg-emerald-600"></div>
            </button>
            <button 
                @click="filterByStatus('menunggu')"
                class="relative px-4 py-2 text-sm font-semibold transition-all"
                :class="statusFilter === 'menunggu' ? 'text-amber-700' : 'text-gray-500 hover:text-gray-700'"
            >
                Menunggu
                <span class="ml-1.5 rounded-full bg-amber-100 px-2 py-0.5 text-[10px] text-amber-700 font-bold">{{ status_counts.menunggu }}</span>
                <div v-if="statusFilter === 'menunggu'" class="absolute bottom-0 left-0 h-0.5 w-full bg-amber-500"></div>
            </button>
            <button 
                @click="filterByStatus('disetujui')"
                class="relative px-4 py-2 text-sm font-semibold transition-all"
                :class="statusFilter === 'disetujui' ? 'text-emerald-700' : 'text-gray-500 hover:text-gray-700'"
            >
                Disetujui
                <span class="ml-1.5 rounded-full bg-emerald-100 px-2 py-0.5 text-[10px] text-emerald-700 font-bold">{{ status_counts.disetujui }}</span>
                <div v-if="statusFilter === 'disetujui'" class="absolute bottom-0 left-0 h-0.5 w-full bg-emerald-500"></div>
            </button>
            <button 
                @click="filterByStatus('ditolak')"
                class="relative px-4 py-2 text-sm font-semibold transition-all"
                :class="statusFilter === 'ditolak' ? 'text-red-700' : 'text-gray-500 hover:text-gray-700'"
            >
                Ditolak
                <span class="ml-1.5 rounded-full bg-red-100 px-2 py-0.5 text-[10px] text-red-700 font-bold">{{ status_counts.ditolak }}</span>
                <div v-if="statusFilter === 'ditolak'" class="absolute bottom-0 left-0 h-0.5 w-full bg-red-500"></div>
            </button>
        </div>

        <!-- Table -->
        <div class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm transition-all hover:shadow-md">
            <div class="overflow-x-auto">
                <table class="w-full text-sm whitespace-nowrap">
                    <thead>
                        <tr class="border-b border-gray-50 bg-gray-50/50">
                            <th class="px-5 py-4 text-left text-[11px] font-bold uppercase tracking-wider text-gray-400">Kepala Keluarga</th>
                            <th class="px-5 py-4 text-left text-[11px] font-bold uppercase tracking-wider text-gray-400">Dasawisma / Kader</th>
                            <th class="px-5 py-4 text-center text-[11px] font-bold uppercase tracking-wider text-gray-400">Anggota</th>
                            <th class="px-5 py-4 text-left text-[11px] font-bold uppercase tracking-wider text-gray-400">Status</th>
                            <th class="px-5 py-4 text-center text-[11px] font-bold uppercase tracking-wider text-gray-400">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        <tr v-if="verifikasis.data.length === 0">
                            <td colspan="5" class="py-16 text-center">
                                <div class="flex flex-col items-center gap-3">
                                    <svg class="h-12 w-12 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                    </svg>
                                    <p class="text-sm font-medium text-gray-400">Tidak ada data verifikasi ditemukan</p>
                                </div>
                            </td>
                        </tr>
                        <tr v-for="v in verifikasis.data" :key="v.id" class="group transition-all hover:bg-emerald-50/30">
                            <td class="px-5 py-4">
                                <div class="flex flex-col">
                                    <span class="font-bold text-gray-800">{{ v.keluarga?.nama_kepala_keluarga }}</span>
                                    <span class="mt-0.5 font-mono text-[10px] tracking-tight text-gray-400">{{ v.keluarga?.no_kk }}</span>
                                </div>
                            </td>
                            <td class="px-5 py-4">
                                <div class="flex flex-col">
                                    <span class="text-xs font-semibold text-gray-600">{{ v.keluarga?.dasawisma?.nama_dasawisma }}</span>
                                    <span class="mt-0.5 text-[10px] text-gray-400">Kader: {{ v.keluarga?.dasawisma?.kader?.user?.name }}</span>
                                </div>
                            </td>
                            <td class="px-5 py-4 text-center">
                                <span class="rounded-full bg-blue-50 px-2.5 py-1 text-[11px] font-bold text-blue-600">{{ v.keluarga?.anggota_keluargas?.length ?? 0 }} Jiwa</span>
                            </td>
                            <td class="px-5 py-4">
                                <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-[10px] font-black uppercase tracking-wider shadow-sm"
                                    :class="{ 
                                        'bg-amber-100 text-amber-700 ring-1 ring-amber-200': v.status_verifikasi === 'menunggu', 
                                        'bg-emerald-100 text-emerald-700 ring-1 ring-emerald-200': v.status_verifikasi === 'disetujui', 
                                        'bg-red-100 text-red-700 ring-1 ring-red-200': v.status_verifikasi === 'ditolak' 
                                    }">
                                    <span v-if="v.status_verifikasi === 'menunggu'" class="h-1.5 w-1.5 animate-pulse rounded-full bg-amber-500"></span>
                                    <span v-if="v.status_verifikasi === 'disetujui'" class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                    {{ v.status_verifikasi }}
                                </span>
                            </td>
                            <td class="px-5 py-4 text-center">
                                <a 
                                    :href="`/admin/verifikasi/${v.id}`" 
                                    class="inline-flex items-center gap-1.5 rounded-xl border border- emerald-100 bg-white px-4 py-2 text-xs font-bold text-emerald-700 shadow-sm transition-all hover:bg-emerald-600 hover:text-white active:scale-95 group-hover:border-emerald-200"
                                >
                                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    Review
                                </a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Server-side Pagination -->
            <div class="flex items-center justify-between border-t border-gray-50 bg-gray-50/30 px-5 py-4">
                <p class="text-[11px] font-bold text-gray-400 uppercase tracking-widest">
                    Menampilkan {{ verifikasis.data.length }} dari {{ verifikasis.total }} Data
                </p>
                
                <div class="flex gap-2">
                    <template v-for="(link, k) in verifikasis.links" :key="k">
                        <div v-if="link.url === null" class="rounded-xl border border-gray-100 bg-white px-3 py-1.5 text-xs text-gray-300 select-none" v-html="link.label"></div>
                        <a 
                            v-else 
                            @click.prevent="router.get(link.url!, { search, status: statusFilter }, { preserveState: true, preserveScroll: true })"
                            href="#" 
                            class="rounded-xl border px-3 py-1.5 text-xs font-bold transition-all hover:shadow-sm"
                            :class="link.active ? 'bg-emerald-600 text-white border-emerald-600' : 'bg-white text-gray-600 border-gray-200 hover:border-emerald-300 hover:text-emerald-700'"
                            v-html="link.label"
                        ></a>
                    </template>
                </div>
            </div>
        </div>

        <!-- Info Section -->
        <div class="mt-6 rounded-2xl bg-emerald-50/50 p-4 border border-emerald-100/50">
            <div class="flex items-start gap-3">
                <div class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-emerald-100 text-emerald-600">
                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <p class="text-xs font-bold text-emerald-800 uppercase tracking-tight">Informasi Verifikasi</p>
                    <p class="mt-0.5 text-xs leading-relaxed text-emerald-700/80">Data yang telah disetujui akan langsung masuk ke dalam laporan populasi desa dan dapat dilihat oleh Kepala Desa. Mohon teliti dalam meninjau NIK dan Nama Anggota Keluarga sebelum melakukan persetujuan.</p>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped>
.divide-y > :not([hidden]) ~ :not([hidden]) {
    border-top-width: 1px;
    border-color: rgb(249 250 251);
}
</style>
