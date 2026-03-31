<script setup lang="ts">
import { Head, Link, router, useForm, usePage } from '@inertiajs/vue3';
import { computed, ref, watch } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';
import SidaConfirm from '@/components/SidaConfirm.vue';
import SidaAlert from '@/components/SidaAlert.vue';
import SidaModal from '@/components/SidaModal.vue';

// Server-side paginated response
const props = defineProps<{
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
    dasawismas: any[];
    filters: { search?: string; dasawisma_id?: string; status?: string };
}>();

const page = usePage();
const flash = computed(() => (page.props as any).flash);
const showAlert = ref(false);
watch(() => flash.value, () => { showAlert.value = true; });

// Local filter state — synced from props
const search = ref(props.filters.search ?? '');
const dasaFilter = ref(props.filters.dasawisma_id ?? '');
const statusFilter = ref(props.filters.status ?? '');

// Debounced server-side filtering
let debounceTimer: ReturnType<typeof setTimeout>;
function applyFilters() {
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(() => {
        router.get('/kader/keluarga', {
            search: search.value || undefined,
            dasawisma_id: dasaFilter.value || undefined,
            status: statusFilter.value || undefined,
        }, { preserveScroll: true, replace: true });
    }, 400);
}

watch([search, dasaFilter, statusFilter], applyFilters);

// Confirm Delete
const confirmId = ref<number | null>(null);
const confirmName = ref('');
function openConfirm(id: number, name: string) { confirmId.value = id; confirmName.value = name; }
function doDelete() { router.delete(`/kader/keluarga/${confirmId.value}`, { preserveScroll: true }); confirmId.value = null; }

// Edit Modal
const showEdit = ref(false);
const editId = ref<number | null>(null);
const editForm = useForm({ dasawisma_id: '' as any, no_kk: '', nama_kepala_keluarga: '' });

function openEdit(kk: any) {
    editId.value = kk.id;
    editForm.dasawisma_id = kk.dasawisma_id;
    editForm.no_kk = kk.no_kk;
    editForm.nama_kepala_keluarga = kk.nama_kepala_keluarga;
    showEdit.value = true;
}
function submitEdit() {
    editForm.put(`/kader/keluarga/${editId.value}`, {
        onSuccess: () => { showEdit.value = false; },
        preserveScroll: true,
    });
}

const statusOptions = [
    { label: 'Semua Status', value: '' },
    { label: 'Menunggu', value: 'menunggu' },
    { label: 'Disetujui', value: 'disetujui' },
    { label: 'Ditolak', value: 'ditolak' },
];

function statusBadge(status: string) {
    return {
        menunggu: 'bg-amber-100 text-amber-700',
        disetujui: 'bg-emerald-100 text-emerald-700',
        ditolak: 'bg-red-100 text-red-700',
    }[status] ?? 'bg-gray-100 text-gray-500';
}

function goToPage(url: string | null) {
    if (url) router.get(url, {}, { preserveScroll: true });
}
</script>

<template>
    <Head title="Data KK & Anggota" />
    <AppLayout>
        <SidaConfirm
            :show="confirmId !== null"
            :message="`Hapus data KK '${confirmName}'? Semua anggota keluarga terkait akan ikut terhapus permanen.`"
            @confirm="doDelete"
            @cancel="confirmId = null"
        />
        <SidaAlert :show="showAlert" :type="flash?.error ? 'error' : 'success'" :message="flash?.success || flash?.error" @close="showAlert = false" />

        <!-- Header -->
        <div class="mb-5 flex flex-wrap items-center justify-between gap-3">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Data KK & Anggota Keluarga</h1>
                <p class="mt-0.5 text-sm text-gray-500">
                    Menampilkan <strong>{{ keluargas.from ?? 0 }}–{{ keluargas.to ?? 0 }}</strong> dari <strong>{{ keluargas.total }}</strong> KK terdaftar
                </p>
            </div>
            <a href="/kader/keluarga/create"
                class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700 hover:shadow-md active:scale-95">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah KK
            </a>
        </div>

        <!-- Filter Bar -->
        <div class="mb-4 flex flex-wrap items-center gap-3">
            <div class="relative min-w-[220px] flex-1">
                <svg class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input
                    v-model="search"
                    type="text"
                    placeholder="Cari No.KK atau nama..."
                    class="w-full rounded-xl border border-gray-200 bg-white py-2.5 pl-9 pr-4 text-sm shadow-sm transition focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-100"
                />
            </div>
            <select v-model="dasaFilter" class="rounded-xl border border-gray-200 bg-white py-2.5 px-4 text-sm shadow-sm transition focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-100">
                <option value="">Semua Dasawisma</option>
                <option v-for="ds in dasawismas" :key="ds.id" :value="ds.id">{{ ds.nama_dasawisma }} (RT {{ ds.rt }}/RW {{ ds.rw }})</option>
            </select>
            <select v-model="statusFilter" class="rounded-xl border border-gray-200 bg-white py-2.5 px-4 text-sm shadow-sm transition focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-100">
                <option v-for="s in statusOptions" :key="s.value" :value="s.value">{{ s.label }}</option>
            </select>
        </div>

        <!-- Table -->
        <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-gray-100">
            <div class="overflow-x-auto">
                <table class="w-full text-sm whitespace-nowrap">
                    <thead>
                        <tr class="border-b border-gray-100 bg-gray-50/70">
                            <th class="px-5 py-4 text-left text-[11px] font-semibold uppercase tracking-wide text-gray-400">No. KK</th>
                            <th class="px-5 py-4 text-left text-[11px] font-semibold uppercase tracking-wide text-gray-400">Kepala Keluarga</th>
                            <th class="px-5 py-4 text-left text-[11px] font-semibold uppercase tracking-wide text-gray-400">Dasawisma</th>
                            <th class="px-5 py-4 text-center text-[11px] font-semibold uppercase tracking-wide text-gray-400">Anggota</th>
                            <th class="px-5 py-4 text-left text-[11px] font-semibold uppercase tracking-wide text-gray-400">Status</th>
                            <th class="px-5 py-4 text-center text-[11px] font-semibold uppercase tracking-wide text-gray-400">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        <tr v-if="keluargas.data.length === 0">
                            <td colspan="6" class="py-12 text-center text-gray-400">
                                <div class="flex flex-col items-center gap-2">
                                    <svg class="h-10 w-10 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    <span class="text-sm font-medium text-gray-400">Tidak ada data ditemukan</span>
                                </div>
                            </td>
                        </tr>
                        <tr v-for="kk in keluargas.data" :key="kk.id" class="group transition hover:bg-emerald-50/30">
                            <td class="px-5 py-3.5 font-mono text-xs text-gray-400">{{ kk.no_kk }}</td>
                            <td class="px-5 py-3.5 font-semibold text-gray-800">{{ kk.nama_kepala_keluarga }}</td>
                            <td class="px-5 py-3.5 text-xs text-gray-500">{{ kk.dasawisma?.nama_dasawisma }}</td>
                            <td class="px-5 py-3.5 text-center">
                                <span class="rounded-full bg-blue-50 px-2.5 py-1 text-xs font-semibold text-blue-600">
                                    {{ kk.anggota_keluargas_count ?? kk.jumlah_anggota ?? 0 }} jiwa
                                </span>
                            </td>
                            <td class="px-5 py-3.5">
                                <span :class="statusBadge(kk.verifikasi?.status_verifikasi ?? '')" class="inline-flex items-center gap-1 rounded-full px-3 py-1 text-[11px] font-semibold capitalize">
                                    <span v-if="kk.verifikasi?.status_verifikasi === 'menunggu'" class="h-1.5 w-1.5 animate-pulse rounded-full bg-amber-500"></span>
                                    {{ kk.verifikasi?.status_verifikasi ?? 'belum' }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5">
                                <div class="flex items-center justify-center gap-1.5">
                                    <a :href="`/kader/keluarga/${kk.id}`"
                                        class="rounded-lg bg-blue-50 px-3 py-1.5 text-[11px] font-semibold text-blue-700 transition hover:bg-blue-100">
                                        Detail
                                    </a>
                                    <button @click="openEdit(kk)"
                                        class="rounded-lg bg-amber-50 px-3 py-1.5 text-[11px] font-semibold text-amber-700 transition hover:bg-amber-100">
                                        Edit
                                    </button>
                                    <button @click="openConfirm(kk.id, kk.nama_kepala_keluarga)"
                                        class="rounded-lg bg-red-50 px-3 py-1.5 text-[11px] font-semibold text-red-600 transition hover:bg-red-100">
                                        Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Server-side Pagination -->
            <div class="flex flex-wrap items-center justify-between gap-3 border-t border-gray-100 bg-white px-5 py-3">
                <p class="text-xs text-gray-400">
                    Halaman <strong class="text-gray-600">{{ keluargas.current_page }}</strong> dari <strong class="text-gray-600">{{ keluargas.last_page }}</strong>
                </p>
                <div class="flex flex-wrap gap-1">
                    <button
                        v-for="link in keluargas.links"
                        :key="link.label"
                        @click="goToPage(link.url)"
                        :disabled="!link.url"
                        v-html="link.label"
                        :class="[
                            'rounded-lg px-3 py-1.5 text-xs font-medium transition',
                            link.active ? 'bg-emerald-600 text-white shadow-sm' : 'text-gray-600 hover:bg-gray-100',
                            !link.url ? 'cursor-not-allowed opacity-40' : '',
                        ]"
                    ></button>
                </div>
            </div>
        </div>

        <!-- Edit Modal -->
        <SidaModal :show="showEdit" title="Edit Data KK" max-width="max-w-md" @close="showEdit = false">
            <form @submit.prevent="submitEdit">
                <div class="mb-4 flex items-start gap-2.5 rounded-xl bg-amber-50 p-3.5 text-xs text-amber-800 ring-1 ring-amber-200">
                    <svg class="mt-0.5 h-4 w-4 shrink-0 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    <span>Mengedit data KK akan mereset status verifikasi menjadi <strong>Menunggu</strong>.</span>
                </div>
                <div class="space-y-4">
                    <div>
                        <label class="mb-1.5 block text-xs font-semibold text-gray-600">Pilih Dasawisma <span class="text-red-500">*</span></label>
                        <select v-model="editForm.dasawisma_id" required class="w-full rounded-xl border border-gray-200 px-3 py-2.5 text-sm focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-100 transition">
                            <option v-for="ds in dasawismas" :key="ds.id" :value="ds.id">{{ ds.nama_dasawisma }} (RT {{ ds.rt }}/RW {{ ds.rw }})</option>
                        </select>
                        <p v-if="editForm.errors.dasawisma_id" class="mt-1 text-xs text-red-500">{{ editForm.errors.dasawisma_id }}</p>
                    </div>
                    <div>
                        <label class="mb-1.5 block text-xs font-semibold text-gray-600">Nomor KK (16 digit) <span class="text-red-500">*</span></label>
                        <input v-model="editForm.no_kk" type="text" maxlength="16" required class="w-full rounded-xl border border-gray-200 px-3 py-2.5 font-mono text-sm focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-100 transition" />
                        <p v-if="editForm.errors.no_kk" class="mt-1 text-xs text-red-500">{{ editForm.errors.no_kk }}</p>
                    </div>
                    <div>
                        <label class="mb-1.5 block text-xs font-semibold text-gray-600">Nama Kepala Keluarga <span class="text-red-500">*</span></label>
                        <input v-model="editForm.nama_kepala_keluarga" type="text" required class="w-full rounded-xl border border-gray-200 px-3 py-2.5 text-sm focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-100 transition" />
                        <p v-if="editForm.errors.nama_kepala_keluarga" class="mt-1 text-xs text-red-500">{{ editForm.errors.nama_kepala_keluarga }}</p>
                    </div>
                </div>
                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" @click="showEdit = false" class="rounded-xl border border-gray-200 px-5 py-2.5 text-sm font-medium text-gray-600 hover:bg-gray-50 transition">Batal</button>
                    <button type="submit" :disabled="editForm.processing" class="rounded-xl bg-emerald-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-emerald-700 disabled:opacity-60 transition">
                        {{ editForm.processing ? 'Menyimpan...' : 'Simpan Perubahan' }}
                    </button>
                </div>
            </form>
        </SidaModal>
    </AppLayout>
</template>
