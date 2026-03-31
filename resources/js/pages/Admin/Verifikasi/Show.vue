<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';
import SidaModal from '@/components/SidaModal.vue';

const props = defineProps<{ verifikasi: any }>();

const approveForm = useForm({ catatan: '' });
const rejectForm = useForm({ catatan: '' });
const showRejectModal = ref(false);

function approve() {
    if (confirm('Setujui data KK ini?')) {
        approveForm.post(`/admin/verifikasi/${props.verifikasi.id}/approve`);
    }
}

function reject() {
    rejectForm.post(`/admin/verifikasi/${props.verifikasi.id}/reject`, {
        onSuccess: () => { showRejectModal.value = false; }
    });
}
</script>

<template>
    <Head title="Review Verifikasi" />
    <AppLayout>
        <div class="mx-auto max-w-4xl flex flex-col gap-6">
            <div class="mb-2">
                <a href="/admin/verifikasi" class="inline-flex items-center gap-2 text-sm font-medium text-gray-500 hover:text-gray-800 transition">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Kembali ke Daftar Verifikasi
                </a>
            </div>

            <!-- Status Banner -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 rounded-2xl border border-gray-100 bg-white p-6 shadow-sm ring-1 ring-gray-100 transition-all hover:shadow-md">
                <div>
                    <h1 class="text-xl font-black text-gray-800 uppercase tracking-tight">Review Data KK</h1>
                    <p class="text-sm text-gray-500 mt-1 font-medium">
                        Kepala: <span class="font-bold text-gray-800">{{ verifikasi.keluarga?.nama_kepala_keluarga }}</span> 
                        <span class="mx-2 text-gray-300">|</span> 
                        Dasawisma: <span class="font-bold text-gray-800">{{ verifikasi.keluarga?.dasawisma?.nama_dasawisma }}</span>
                    </p>
                </div>
                <div class="flex items-center gap-3">
                    <span class="rounded-full px-4 py-1.5 text-[10px] font-black uppercase tracking-widest shadow-sm ring-1"
                        :class="{ 
                            'bg-amber-100 text-amber-700 ring-amber-200': verifikasi.status_verifikasi === 'menunggu', 
                            'bg-emerald-100 text-emerald-700 ring-emerald-200': verifikasi.status_verifikasi === 'disetujui', 
                            'bg-red-100 text-red-700 ring-red-200': verifikasi.status_verifikasi === 'ditolak' 
                        }">
                        {{ verifikasi.status_verifikasi }}
                    </span>
                    <template v-if="verifikasi.status_verifikasi === 'menunggu'">
                        <button @click="approve" :disabled="approveForm.processing" class="rounded-xl bg-emerald-600 px-6 py-2.5 text-xs font-black text-white hover:bg-emerald-700 disabled:opacity-50 transition-all shadow-sm active:scale-95 shadow-emerald-200">
                            SETUJUI
                        </button>
                        <button @click="showRejectModal = true" class="rounded-xl bg-red-50 text-red-600 border border-red-100 px-6 py-2.5 text-xs font-black hover:bg-red-100 transition-all active:scale-95">
                            TOLAK
                        </button>
                    </template>
                </div>
            </div>

            <!-- Catatan sebelumnya -->
            <div v-if="verifikasi.catatan" class="rounded-xl p-5 text-sm shadow-sm border" :class="verifikasi.status_verifikasi === 'ditolak' ? 'bg-red-50 text-red-700 border-red-100' : 'bg-green-50 text-green-700 border-green-100'">
                <strong class="block mb-1">Catatan Verifikasi:</strong> {{ verifikasi.catatan }}
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Data KK -->
                <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm md:col-span-1">
                    <h2 class="mb-5 text-base font-semibold text-gray-800 flex items-center gap-2">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"/></svg>
                        Informasi KK
                    </h2>
                    <dl class="space-y-4">
                        <div>
                            <dt class="text-xs font-medium text-gray-400 uppercase tracking-wide">Nomor KK</dt>
                            <dd class="mt-1 font-mono text-sm font-medium text-gray-800">{{ verifikasi.keluarga?.no_kk }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium text-gray-400 uppercase tracking-wide">Kepala Keluarga</dt>
                            <dd class="mt-1 text-sm font-medium text-gray-800">{{ verifikasi.keluarga?.nama_kepala_keluarga }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium text-gray-400 uppercase tracking-wide">Dasawisma</dt>
                            <dd class="mt-1 text-sm text-gray-600">{{ verifikasi.keluarga?.dasawisma?.nama_dasawisma }} (RT {{ verifikasi.keluarga?.dasawisma?.rt }}/RW {{ verifikasi.keluarga?.dasawisma?.rw }})</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium text-gray-400 uppercase tracking-wide">Kader Pendata</dt>
                            <dd class="mt-1 text-sm text-gray-600">{{ verifikasi.keluarga?.dasawisma?.kader?.user?.name }}</dd>
                        </div>
                    </dl>
                </div>

                <!-- Data Anggota -->
                <div class="rounded-2xl border border-gray-100 bg-white shadow-sm md:col-span-2 overflow-hidden">
                    <div class="border-b border-gray-100 px-6 py-5 bg-gray-50/50">
                        <h2 class="text-base font-semibold text-gray-800 flex items-center gap-2">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            Anggota Keluarga ({{ verifikasi.keluarga?.anggota_keluargas?.length }})
                        </h2>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm whitespace-nowrap">
                            <thead class="bg-gray-50/50">
                                <tr>
                                    <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-400">NIK & Nama</th>
                                    <th class="px-5 py-3 text-center text-xs font-semibold uppercase tracking-wide text-gray-400">L/P</th>
                                    <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-400">Status</th>
                                    <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-400">Pekerjaan</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                <tr v-for="ak in verifikasi.keluarga?.anggota_keluargas" :key="ak.id" class="hover:bg-gray-50 transition">
                                    <td class="px-5 py-3.5">
                                        <p class="font-medium text-gray-800">{{ ak.nama_anggota }}</p>
                                        <p class="font-mono text-[11px] text-gray-500">{{ ak.nik }}</p>
                                    </td>
                                    <td class="px-5 py-3.5 text-center">
                                        <span class="rounded-md px-2 py-1 text-xs font-medium" :class="ak.jenis_kelamin === 'L' ? 'bg-blue-50 text-blue-600' : 'bg-pink-50 text-pink-600'">
                                            {{ ak.jenis_kelamin }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-3.5 text-gray-600">{{ ak.status_dalam_keluarga }}</td>
                                    <td class="px-5 py-3.5 text-gray-500 text-xs truncate max-w-[120px]">{{ ak.pekerjaan ?? '-' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Tolak (SidaModal) -->
        <SidaModal :show="showRejectModal" title="Tolak Verifikasi KK" max-width="max-w-md" @close="showRejectModal = false">
            <form @submit.prevent="reject">
                <div class="mb-4 rounded-xl bg-red-50 p-4 border border-red-100">
                    <p class="text-sm text-red-700">Berikan catatan alasan penolakan agar kader bisa memperbaiki data KK ini.</p>
                </div>
                <div class="mb-5">
                    <label class="mb-1.5 block text-xs font-medium text-gray-700">Alasan Penolakan <span class="text-red-500">*</span></label>
                    <textarea
                        v-model="rejectForm.catatan"
                        required
                        rows="4"
                        placeholder="Contoh: NIK anggota atas nama Budi tidak valid..."
                        class="w-full rounded-xl border border-gray-200 px-3 py-2.5 text-sm focus:border-red-400 focus:outline-none focus:ring-2 focus:ring-red-100"
                    ></textarea>
                    <p v-if="rejectForm.errors.catatan" class="mt-1 text-xs text-red-600">{{ rejectForm.errors.catatan }}</p>
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" @click="showRejectModal = false" class="rounded-xl border border-gray-200 px-5 py-2.5 text-sm font-medium text-gray-600 hover:bg-gray-50 transition">Batal</button>
                    <button type="submit" :disabled="rejectForm.processing" class="rounded-xl bg-red-600 px-5 py-2.5 text-sm font-medium text-white hover:bg-red-700 disabled:opacity-60 shadow-sm transition">
                        {{ rejectForm.processing ? 'Memproses...' : 'Tolak Data' }}
                    </button>
                </div>
            </form>
        </SidaModal>
    </AppLayout>
</template>
