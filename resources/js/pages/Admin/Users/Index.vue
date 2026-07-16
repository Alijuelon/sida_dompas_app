<script setup lang="ts">
import { Head, router, useForm, usePage } from '@inertiajs/vue3';
import { computed, ref, watch } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';
import SidaModal from '@/components/SidaModal.vue';
import SidaConfirm from '@/components/SidaConfirm.vue';
import SidaAlert from '@/components/SidaAlert.vue';

const props = defineProps<{
    users: any[];
    dasawismas?: any[];
}>();

const page = usePage();
const flash = computed(() => (page.props as any).flash);
const showAlert = ref(true);
watch(() => flash.value, () => { showAlert.value = true; });

// Search + Paginate (frontend)
const search = ref('');
const currentPage = ref(1);
const perPage = 10;

const filtered = computed(() => {
    const q = search.value.toLowerCase();
    return props.users.filter(u =>
        u.name?.toLowerCase().includes(q) ||
        u.username?.toLowerCase().includes(q) ||
        u.role?.toLowerCase().includes(q)
    );
});
const totalPages = computed(() => Math.max(1, Math.ceil(filtered.value.length / perPage)));
const paginated = computed(() => {
    const start = (currentPage.value - 1) * perPage;
    return filtered.value.slice(start, start + perPage);
});
watch(search, () => { currentPage.value = 1; });

// Confirm delete
const confirmId = ref<number | null>(null);
const confirmName = ref('');
function openConfirm(id: number, name: string) {
    confirmId.value = id;
    confirmName.value = name;
}
function doDelete() {
    router.delete(`/admin/users/${confirmId.value}`, { preserveScroll: true });
    confirmId.value = null;
}

// Toggle status
function toggleStatus(id: number) {
    router.patch(`/admin/users/${id}/toggle-status`, {}, { preserveScroll: true });
}

// Create modal
const showCreate = ref(false);
const createForm = useForm({
    name: '', username: '', email: '', password: '', role: 'kader',
    wilayah: '', rt: '', rw: '', no_hp: '',
});
function submitCreate() {
    createForm.post('/admin/users', {
        onSuccess: () => { showCreate.value = false; createForm.reset(); },
        preserveScroll: true,
    });
}

// Edit modal
const showEdit = ref(false);
const editId = ref<number | null>(null);
const editForm = useForm({ name: '', username: '', email: '', role: 'kader', wilayah: '', rt: '', rw: '', no_hp: '', status: true });
function openEdit(user: any) {
    editId.value = user.id;
    editForm.name = user.name;
    editForm.username = user.username;
    editForm.email = user.email;
    editForm.role = user.role;
    editForm.wilayah = user.kader?.wilayah ?? '';
    editForm.rt = user.kader?.rt ?? '';
    editForm.rw = user.kader?.rw ?? '';
    editForm.no_hp = user.kader?.no_hp ?? '';
    editForm.status = user.status;
    showEdit.value = true;
}
function submitEdit() {
    editForm.put(`/admin/users/${editId.value}`, {
        onSuccess: () => { showEdit.value = false; },
        preserveScroll: true,
    });
}
</script>

<template>
    <Head title="Manajemen User" />
    <AppLayout>
        <SidaConfirm
            :show="confirmId !== null"
            :message="`Yakin hapus user '${confirmName}'? Aksi ini tidak dapat dibatalkan.`"
            @confirm="doDelete"
            @cancel="confirmId = null"
        />
        <SidaAlert :show="showAlert" :type="flash?.error ? 'error' : 'success'" :message="flash?.success || flash?.error" @close="showAlert = false" />

        <!-- Header -->
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Manajemen User</h1>
                <p class="text-sm text-gray-500">Kelola akun kader dan kepala desa</p>
            </div>
            <button @click="showCreate = true" class="inline-flex items-center gap-2 rounded-xl bg-green-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-green-700 shadow-sm transition">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Tambah User
            </button>
        </div>

        <!-- Search -->
        <div class="mb-4">
            <div class="relative max-w-xs">
                <svg class="absolute left-3 top-2.5 h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input v-model="search" type="text" placeholder="Cari nama, username, role..." class="w-full rounded-xl border border-gray-200 bg-white py-2.5 pl-9 pr-4 text-sm text-gray-700 shadow-sm focus:border-green-400 focus:outline-none focus:ring-2 focus:ring-green-100" />
            </div>
        </div>

        <!-- Tabel -->
        <div class="rounded-2xl bg-white shadow-sm overflow-hidden">
            <table class="w-full text-sm whitespace-nowrap">
                <thead>
                    <tr class="border-b border-gray-100 bg-gray-50">
                        <th class="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wide text-gray-400">Nama</th>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wide text-gray-400">Username</th>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wide text-gray-400">Role</th>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wide text-gray-400">Wilayah</th>
                        <th class="px-5 py-3.5 text-center text-xs font-semibold uppercase tracking-wide text-gray-400">Status</th>
                        <th class="px-5 py-3.5 text-center text-xs font-semibold uppercase tracking-wide text-gray-400">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    <tr v-if="paginated.length === 0">
                        <td colspan="6" class="py-12 text-center text-gray-400">Tidak ada data ditemukan</td>
                    </tr>
                    <tr v-for="user in paginated" :key="user.id" class="hover:bg-gray-50 transition">
                        <td class="px-5 py-3.5">
                            <div class="flex items-center gap-3">
                                <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-green-100 text-sm font-bold text-green-700">{{ user.name?.charAt(0)?.toUpperCase() }}</div>
                                <span class="font-medium text-gray-800">{{ user.name }}</span>
                            </div>
                        </td>
                        <td class="px-5 py-3.5 font-mono text-xs text-gray-500">{{ user.username }}</td>
                        <td class="px-5 py-3.5">
                            <span class="rounded-full px-2.5 py-1 text-xs font-medium" :class="{ 'bg-red-100 text-red-700': user.role === 'admin', 'bg-blue-100 text-blue-700': user.role === 'kader', 'bg-purple-100 text-purple-700': user.role === 'kades' }">
                                {{ user.role }}
                            </span>
                        </td>
                        <td class="px-5 py-3.5 text-gray-500 text-xs">
                            <template v-if="user.kader?.wilayah || user.kader?.rt || user.kader?.rw">
                                <span v-if="user.kader?.wilayah" class="font-medium text-gray-700">{{ user.kader.wilayah }}</span>
                                <span v-if="user.kader?.wilayah && (user.kader?.rt || user.kader?.rw)"> - </span>
                                <span v-if="user.kader?.rt || user.kader?.rw">RT {{ user.kader?.rt || '-' }} / RW {{ user.kader?.rw || '-' }}</span>
                            </template>
                            <span v-else>-</span>
                        </td>
                        <td class="px-5 py-3.5 text-center">
                            <button @click="toggleStatus(user.id)" class="rounded-full px-3 py-1 text-xs font-medium transition" :class="user.status ? 'bg-green-100 text-green-700 hover:bg-green-200' : 'bg-gray-100 text-gray-500 hover:bg-gray-200'">
                                {{ user.status ? 'Aktif' : 'Nonaktif' }}
                            </button>
                        </td>
                        <td class="px-5 py-3.5 text-center">
                            <div class="flex items-center justify-center gap-1.5">
                                <button @click="openEdit(user)" class="rounded-lg bg-yellow-50 px-3 py-1.5 text-xs font-medium text-yellow-700 hover:bg-yellow-100 transition">Edit</button>
                                <button @click="openConfirm(user.id, user.name)" class="rounded-lg bg-red-50 px-3 py-1.5 text-xs font-medium text-red-700 hover:bg-red-100 transition">Hapus</button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
            <!-- Pagination -->
            <div v-if="totalPages > 1" class="flex items-center justify-between border-t border-gray-100 px-5 py-3">
                <p class="text-xs text-gray-400">Menampilkan {{ paginated.length }} dari {{ filtered.length }} user</p>
                <div class="flex gap-1">
                    <button @click="currentPage--" :disabled="currentPage === 1" class="rounded-lg px-3 py-1.5 text-xs font-medium text-gray-600 hover:bg-gray-100 disabled:opacity-40 transition">← Prev</button>
                    <button v-for="p in totalPages" :key="p" @click="currentPage = p" class="rounded-lg px-3 py-1.5 text-xs font-medium transition" :class="p === currentPage ? 'bg-green-600 text-white' : 'text-gray-600 hover:bg-gray-100'">{{ p }}</button>
                    <button @click="currentPage++" :disabled="currentPage === totalPages" class="rounded-lg px-3 py-1.5 text-xs font-medium text-gray-600 hover:bg-gray-100 disabled:opacity-40 transition">Next →</button>
                </div>
            </div>
        </div>

        <!-- Modal Create -->
        <SidaModal :show="showCreate" title="Tambah User Baru" max-width="max-w-xl" @close="showCreate = false">
            <form @submit.prevent="submitCreate" class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="mb-1 block text-xs font-medium text-gray-700">Nama Lengkap</label>
                        <input v-model="createForm.name" type="text" required class="w-full rounded-xl border border-gray-200 px-3 py-2.5 text-sm focus:border-green-400 focus:outline-none focus:ring-2 focus:ring-green-100" />
                        <p v-if="createForm.errors.name" class="mt-1 text-xs text-red-600">{{ createForm.errors.name }}</p>
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-medium text-gray-700">Username</label>
                        <input v-model="createForm.username" type="text" required class="w-full rounded-xl border border-gray-200 px-3 py-2.5 text-sm focus:border-green-400 focus:outline-none focus:ring-2 focus:ring-green-100" />
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-medium text-gray-700">Email</label>
                        <input v-model="createForm.email" type="email" required class="w-full rounded-xl border border-gray-200 px-3 py-2.5 text-sm focus:border-green-400 focus:outline-none focus:ring-2 focus:ring-green-100" />
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-medium text-gray-700">Password</label>
                        <input v-model="createForm.password" type="password" required class="w-full rounded-xl border border-gray-200 px-3 py-2.5 text-sm focus:border-green-400 focus:outline-none focus:ring-2 focus:ring-green-100" />
                    </div>
                    <div class="col-span-2">
                        <label class="mb-1 block text-xs font-medium text-gray-700">Role</label>
                        <select v-model="createForm.role" class="w-full rounded-xl border border-gray-200 px-3 py-2.5 text-sm focus:border-green-400 focus:outline-none">
                            <option value="kader">Kader</option>
                            <option value="kades">Kepala Desa</option>
                        </select>
                    </div>
                    <template v-if="createForm.role === 'kader'">
                        <div class="col-span-2">
                            <label class="mb-1 block text-xs font-medium text-gray-700">Wilayah</label>
                            <input v-model="createForm.wilayah" type="text" class="w-full rounded-xl border border-gray-200 px-3 py-2.5 text-sm focus:border-green-400 focus:outline-none" />
                        </div>
                        <div>
                            <label class="mb-1 block text-xs font-medium text-gray-700">RT</label>
                            <input v-model="createForm.rt" type="text" class="w-full rounded-xl border border-gray-200 px-3 py-2.5 text-sm focus:border-green-400 focus:outline-none" />
                        </div>
                        <div>
                            <label class="mb-1 block text-xs font-medium text-gray-700">RW</label>
                            <input v-model="createForm.rw" type="text" class="w-full rounded-xl border border-gray-200 px-3 py-2.5 text-sm focus:border-green-400 focus:outline-none" />
                        </div>
                    </template>
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
        <SidaModal :show="showEdit" title="Edit User" max-width="max-w-xl" @close="showEdit = false">
            <form @submit.prevent="submitEdit" class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="mb-1 block text-xs font-medium text-gray-700">Nama Lengkap</label>
                        <input v-model="editForm.name" type="text" required class="w-full rounded-xl border border-gray-200 px-3 py-2.5 text-sm focus:border-green-400 focus:outline-none focus:ring-2 focus:ring-green-100" />
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-medium text-gray-700">Username</label>
                        <input v-model="editForm.username" type="text" required class="w-full rounded-xl border border-gray-200 px-3 py-2.5 text-sm focus:border-green-400 focus:outline-none focus:ring-2 focus:ring-green-100" />
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-medium text-gray-700">Email</label>
                        <input v-model="editForm.email" type="email" required class="w-full rounded-xl border border-gray-200 px-3 py-2.5 text-sm focus:border-green-400 focus:outline-none focus:ring-2 focus:ring-green-100" />
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-medium text-gray-700">Role</label>
                        <select v-model="editForm.role" class="w-full rounded-xl border border-gray-200 px-3 py-2.5 text-sm focus:border-green-400 focus:outline-none">
                            <option value="kader">Kader</option>
                            <option value="kades">Kepala Desa</option>
                        </select>
                    </div>
                    <template v-if="editForm.role === 'kader'">
                        <div class="col-span-2">
                            <label class="mb-1 block text-xs font-medium text-gray-700">Wilayah</label>
                            <input v-model="editForm.wilayah" type="text" class="w-full rounded-xl border border-gray-200 px-3 py-2.5 text-sm focus:border-green-400 focus:outline-none" />
                        </div>
                        <div>
                            <label class="mb-1 block text-xs font-medium text-gray-700">RT</label>
                            <input v-model="editForm.rt" type="text" class="w-full rounded-xl border border-gray-200 px-3 py-2.5 text-sm focus:border-green-400 focus:outline-none" />
                        </div>
                        <div>
                            <label class="mb-1 block text-xs font-medium text-gray-700">RW</label>
                            <input v-model="editForm.rw" type="text" class="w-full rounded-xl border border-gray-200 px-3 py-2.5 text-sm focus:border-green-400 focus:outline-none" />
                        </div>
                    </template>
                    <div class="col-span-2 flex items-center gap-3">
                        <label class="text-xs font-medium text-gray-700">Status Aktif</label>
                        <button type="button" @click="editForm.status = !editForm.status" class="rounded-full px-4 py-1 text-xs font-medium transition" :class="editForm.status ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500'">
                            {{ editForm.status ? 'Aktif' : 'Nonaktif' }}
                        </button>
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
