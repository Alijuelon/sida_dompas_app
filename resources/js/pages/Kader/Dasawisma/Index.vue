<script setup lang="ts">
import { Head, router, useForm, usePage } from '@inertiajs/vue3';
import { computed, ref, watch } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';
import SidaModal from '@/components/SidaModal.vue';
import SidaConfirm from '@/components/SidaConfirm.vue';
import SidaAlert from '@/components/SidaAlert.vue';

const props = defineProps<{ dasawismas: any[], kaders?: any[] }>();

const page = usePage();
const flash = computed(() => (page.props as any).flash);
const role = computed(() => (page.props as any).auth?.user?.role ?? '');
const showAlert = ref(true);
watch(() => flash.value, () => { showAlert.value = true; });

// Search + Paginate
const search = ref('');
const currentPage = ref(1);
const perPage = 9;
const filtered = computed(() => {
    const q = search.value.toLowerCase();
    return props.dasawismas.filter(d =>
        d.nama_dasawisma?.toLowerCase().includes(q) ||
        d.rt?.toLowerCase().includes(q) ||
        d.rw?.toLowerCase().includes(q)
    );
});
const totalPages = computed(() => Math.max(1, Math.ceil(filtered.value.length / perPage)));
const paginated = computed(() => filtered.value.slice((currentPage.value - 1) * perPage, currentPage.value * perPage));
watch(search, () => { currentPage.value = 1; });

// Confirm Delete
const confirmId = ref<number | null>(null);
const confirmName = ref('');
function openConfirm(id: number, name: string) { confirmId.value = id; confirmName.value = name; }
function doDelete() { router.delete(`/kader/dasawisma/${confirmId.value}`, { preserveScroll: true }); confirmId.value = null; }

// Create Modal
const showCreate = ref(false);
const createForm = useForm({ kader_id: '', nama_dasawisma: '', rt: '', rw: '', desa: 'Dompas', kecamatan: '', kabupaten: '' });
function submitCreate() {
    createForm.post('/kader/dasawisma', { onSuccess: () => { showCreate.value = false; createForm.reset(); }, preserveScroll: true });
}

// Edit Modal
const showEdit = ref(false);
const editId = ref<number | null>(null);
const editForm = useForm({ kader_id: '', nama_dasawisma: '', rt: '', rw: '', desa: '', kecamatan: '', kabupaten: '' });
function openEdit(ds: any) {
    editId.value = ds.id;
    editForm.kader_id = ds.kader_id || '';
    editForm.nama_dasawisma = ds.nama_dasawisma;
    editForm.rt = ds.rt;
    editForm.rw = ds.rw;
    editForm.desa = ds.desa;
    editForm.kecamatan = ds.kecamatan;
    editForm.kabupaten = ds.kabupaten;
    showEdit.value = true;
}
function submitEdit() {
    editForm.put(`/kader/dasawisma/${editId.value}`, { onSuccess: () => { showEdit.value = false; }, preserveScroll: true });
}
</script>

<template>
    <Head title="Data Dasawisma" />
    <AppLayout>
        <SidaConfirm :show="confirmId !== null" :message="`Hapus dasawisma '${confirmName}'?`" @confirm="doDelete" @cancel="confirmId = null" />
        <SidaAlert :show="showAlert" :type="flash?.error ? 'error' : 'success'" :message="flash?.success || flash?.error" @close="showAlert = false" />

        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Data Dasawisma</h1>
                <p class="text-sm text-gray-500">Kelola kelompok dasawisma wilayah Anda</p>
            </div>
            <button @click="showCreate = true" class="inline-flex items-center gap-2 rounded-xl bg-green-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-green-700 shadow-sm transition">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Tambah Dasawisma
            </button>
        </div>

        <!-- Search -->
        <div class="mb-4">
            <div class="relative max-w-xs">
                <svg class="absolute left-3 top-2.5 h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input v-model="search" type="text" placeholder="Cari nama, RT, RW..." class="w-full rounded-xl border border-gray-200 bg-white py-2.5 pl-9 pr-4 text-sm shadow-sm focus:border-green-400 focus:outline-none focus:ring-2 focus:ring-green-100" />
            </div>
        </div>

        <!-- Card Grid -->
        <div v-if="paginated.length === 0" class="rounded-2xl bg-white p-12 text-center shadow-sm text-gray-400">
            {{ search ? 'Tidak ada dasawisma yang cocok.' : 'Belum ada data. Tambah dasawisma pertama Anda.' }}
        </div>
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-3">
            <div v-for="ds in paginated" :key="ds.id" class="group rounded-2xl bg-white p-5 shadow-sm hover:shadow-md transition">
                <div class="mb-3 flex items-start justify-between">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-green-50">
                        <svg class="h-5 w-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"/></svg>
                    </div>
                    <div class="flex gap-2 opacity-0 group-hover:opacity-100 transition">
                        <button @click="openEdit(ds)" class="rounded-lg bg-yellow-50 px-3 py-1 text-xs font-medium text-yellow-700 hover:bg-yellow-100 transition">Edit</button>
                        <button @click="openConfirm(ds.id, ds.nama_dasawisma)" class="rounded-lg bg-red-50 px-3 py-1 text-xs font-medium text-red-600 hover:bg-red-100 transition">Hapus</button>
                    </div>
                </div>
                <h3 class="text-base font-semibold text-gray-800">{{ ds.nama_dasawisma }}</h3>
                <p class="text-xs text-gray-400 mt-0.5">RT {{ ds.rt }} / RW {{ ds.rw }} · {{ ds.desa }}</p>
                <div class="mt-3 pt-3 border-t border-gray-50 text-xs text-gray-400">
                    {{ ds.keluargas_count ?? ds.keluargas?.length ?? 0 }} KK terdaftar
                </div>
            </div>
        </div>

        <!-- Pagination -->
        <div v-if="totalPages > 1" class="mt-4 flex items-center justify-between">
            <p class="text-xs text-gray-400">{{ filtered.length }} dasawisma ditemukan</p>
            <div class="flex gap-1">
                <button @click="currentPage--" :disabled="currentPage === 1" class="rounded-xl px-3 py-1.5 text-xs font-medium text-gray-600 hover:bg-white disabled:opacity-40 transition">← Prev</button>
                <button v-for="p in totalPages" :key="p" @click="currentPage = p" class="rounded-xl px-3 py-1.5 text-xs font-medium transition" :class="p === currentPage ? 'bg-green-600 text-white' : 'bg-white text-gray-600 hover:bg-white'">{{ p }}</button>
                <button @click="currentPage++" :disabled="currentPage === totalPages" class="rounded-xl px-3 py-1.5 text-xs font-medium text-gray-600 hover:bg-white disabled:opacity-40 transition">Next →</button>
            </div>
        </div>

        <!-- Modal Create -->
        <SidaModal :show="showCreate" title="Tambah Dasawisma" @close="showCreate = false">
            <form @submit.prevent="submitCreate" class="space-y-4">
                <div v-if="role === 'admin'">
                    <label class="mb-1 block text-xs font-medium text-gray-700">Kader</label>
                    <select v-model="createForm.kader_id" required class="w-full rounded-xl border border-gray-200 px-3 py-2.5 text-sm focus:border-green-400 focus:outline-none focus:ring-2 focus:ring-green-100">
                        <option value="">Pilih Kader</option>
                        <option v-for="k in kaders" :key="k.id" :value="k.id">{{ k.user?.name }}</option>
                    </select>
                </div>
                <div>
                    <label class="mb-1 block text-xs font-medium text-gray-700">Nama Dasawisma</label>
                    <input v-model="createForm.nama_dasawisma" required class="w-full rounded-xl border border-gray-200 px-3 py-2.5 text-sm focus:border-green-400 focus:outline-none focus:ring-2 focus:ring-green-100" />
                    <p v-if="createForm.errors.nama_dasawisma" class="mt-1 text-xs text-red-600">{{ createForm.errors.nama_dasawisma }}</p>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="mb-1 block text-xs font-medium text-gray-700">RT</label>
                        <input v-model="createForm.rt" required class="w-full rounded-xl border border-gray-200 px-3 py-2.5 text-sm focus:border-green-400 focus:outline-none" />
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-medium text-gray-700">RW</label>
                        <input v-model="createForm.rw" required class="w-full rounded-xl border border-gray-200 px-3 py-2.5 text-sm focus:border-green-400 focus:outline-none" />
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-medium text-gray-700">Desa</label>
                        <input v-model="createForm.desa" class="w-full rounded-xl border border-gray-200 px-3 py-2.5 text-sm focus:border-green-400 focus:outline-none" />
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-medium text-gray-700">Kecamatan</label>
                        <input v-model="createForm.kecamatan" class="w-full rounded-xl border border-gray-200 px-3 py-2.5 text-sm focus:border-green-400 focus:outline-none" />
                    </div>
                </div>
                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" @click="showCreate = false" class="rounded-xl border border-gray-200 px-5 py-2.5 text-sm font-medium text-gray-600 hover:bg-gray-50 transition">Batal</button>
                    <button type="submit" :disabled="createForm.processing" class="rounded-xl bg-green-600 px-5 py-2.5 text-sm font-medium text-white hover:bg-green-700 disabled:opacity-60 transition">
                        {{ createForm.processing ? 'Menyimpan...' : 'Simpan' }}
                    </button>
                </div>
            </form>
        </SidaModal>

        <!-- Modal Edit -->
        <SidaModal :show="showEdit" title="Edit Dasawisma" @close="showEdit = false">
            <form @submit.prevent="submitEdit" class="space-y-4">
                <div v-if="role === 'admin'">
                    <label class="mb-1 block text-xs font-medium text-gray-700">Kader</label>
                    <select v-model="editForm.kader_id" required class="w-full rounded-xl border border-gray-200 px-3 py-2.5 text-sm focus:border-green-400 focus:outline-none focus:ring-2 focus:ring-green-100">
                        <option value="">Pilih Kader</option>
                        <option v-for="k in kaders" :key="k.id" :value="k.id">{{ k.user?.name }}</option>
                    </select>
                </div>
                <div>
                    <label class="mb-1 block text-xs font-medium text-gray-700">Nama Dasawisma</label>
                    <input v-model="editForm.nama_dasawisma" required class="w-full rounded-xl border border-gray-200 px-3 py-2.5 text-sm focus:border-green-400 focus:outline-none focus:ring-2 focus:ring-green-100" />
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="mb-1 block text-xs font-medium text-gray-700">RT</label>
                        <input v-model="editForm.rt" class="w-full rounded-xl border border-gray-200 px-3 py-2.5 text-sm focus:border-green-400 focus:outline-none" />
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-medium text-gray-700">RW</label>
                        <input v-model="editForm.rw" class="w-full rounded-xl border border-gray-200 px-3 py-2.5 text-sm focus:border-green-400 focus:outline-none" />
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-medium text-gray-700">Desa</label>
                        <input v-model="editForm.desa" class="w-full rounded-xl border border-gray-200 px-3 py-2.5 text-sm focus:border-green-400 focus:outline-none" />
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-medium text-gray-700">Kecamatan</label>
                        <input v-model="editForm.kecamatan" class="w-full rounded-xl border border-gray-200 px-3 py-2.5 text-sm focus:border-green-400 focus:outline-none" />
                    </div>
                </div>
                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" @click="showEdit = false" class="rounded-xl border border-gray-200 px-5 py-2.5 text-sm font-medium text-gray-600 hover:bg-gray-50 transition">Batal</button>
                    <button type="submit" :disabled="editForm.processing" class="rounded-xl bg-green-600 px-5 py-2.5 text-sm font-medium text-white hover:bg-green-700 disabled:opacity-60 transition">
                        {{ editForm.processing ? 'Menyimpan...' : 'Simpan Perubahan' }}
                    </button>
                </div>
            </form>
        </SidaModal>
    </AppLayout>
</template>
