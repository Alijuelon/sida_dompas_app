<script setup lang="ts">
import { Head, usePage } from '@inertiajs/vue3';
import { computed, ref, watch } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';

defineProps<{
    verifikasis: any;
}>();

function statusBadge(status: string) {
    return {
        menunggu: 'bg-amber-100 text-amber-700 ring-1 ring-amber-200',
        disetujui: 'bg-emerald-100 text-emerald-700 ring-1 ring-emerald-200',
        ditolak: 'bg-red-100 text-red-700 ring-1 ring-red-200',
    }[status] ?? 'bg-gray-100 text-gray-500';
}

function statusIcon(status: string) {
    if (status === 'disetujui') return '✓';
    if (status === 'ditolak') return '✗';
    return '⏳';
}

function formatDate(d: string) {
    if (!d) return '';
    return new Date(d).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
}
</script>

<template>
    <Head title="Status Verifikasi" />
    <AppLayout>
        <!-- Header -->
        <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Status Verifikasi</h1>
                <p class="mt-0.5 text-sm text-gray-500">Pantau status data KK yang telah Anda kiriman ke admin</p>
            </div>
            <a href="/kader/keluarga/create"
                class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700 active:scale-95">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Input KK Baru
            </a>
        </div>

        <!-- Empty State -->
        <div v-if="verifikasis.data.length === 0"
            class="flex flex-col items-center gap-3 rounded-2xl bg-white py-16 text-center shadow-sm ring-1 ring-gray-100">
            <svg class="h-12 w-12 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <p class="text-sm font-medium text-gray-400">Belum ada data verifikasi</p>
            <a href="/kader/keluarga/create" class="mt-1 inline-flex items-center gap-1.5 rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-emerald-700">
                Input KK Pertama
            </a>
        </div>

        <!-- List -->
        <div v-else class="space-y-4">
            <div
                v-for="v in verifikasis.data"
                :key="v.id"
                class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 transition"
                :class="{
                    'ring-amber-200': v.status_verifikasi === 'menunggu',
                    'ring-emerald-200': v.status_verifikasi === 'disetujui',
                    'ring-red-200': v.status_verifikasi === 'ditolak',
                    'ring-gray-100': !v.status_verifikasi,
                }"
            >
                <!-- Top bar color indicator -->
                <div class="h-1 w-full"
                    :class="{
                        'bg-amber-400': v.status_verifikasi === 'menunggu',
                        'bg-emerald-400': v.status_verifikasi === 'disetujui',
                        'bg-red-400': v.status_verifikasi === 'ditolak',
                    }"
                ></div>
                <div class="p-5">
                    <div class="flex items-start justify-between gap-4">
                        <div class="min-w-0">
                            <div class="flex items-center gap-2.5">
                                <span class="text-xl leading-none">{{ statusIcon(v.status_verifikasi) }}</span>
                                <h3 class="truncate font-bold text-gray-800">{{ v.keluarga?.nama_kepala_keluarga }}</h3>
                            </div>
                            <p class="mt-1 font-mono text-xs text-gray-400">{{ v.keluarga?.no_kk }}</p>
                            <div class="mt-2 flex flex-wrap items-center gap-2 text-xs text-gray-500">
                                <span class="inline-flex items-center gap-1">
                                    <svg class="h-3 w-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"/></svg>
                                    {{ v.keluarga?.dasawisma?.nama_dasawisma }}
                                </span>
                                <span v-if="v.keluarga?.dasawisma" class="text-gray-300">·</span>
                                <span>RT {{ v.keluarga?.dasawisma?.rt }} / RW {{ v.keluarga?.dasawisma?.rw }}</span>
                            </div>
                        </div>

                        <div class="shrink-0 text-right">
                            <span :class="statusBadge(v.status_verifikasi)" class="inline-flex items-center gap-1.5 rounded-full px-3 py-1.5 text-xs font-bold capitalize">
                                <span v-if="v.status_verifikasi === 'menunggu'" class="h-1.5 w-1.5 animate-pulse rounded-full bg-amber-500"></span>
                                {{ v.status_verifikasi }}
                            </span>
                            <p v-if="v.tanggal_verifikasi" class="mt-1.5 text-[11px] text-gray-400">{{ formatDate(v.tanggal_verifikasi) }}</p>
                            <p v-if="v.admin?.user?.name" class="text-[11px] text-gray-400">oleh <span class="font-medium">{{ v.admin.user.name }}</span></p>
                        </div>
                    </div>

                    <!-- Catatan jika ditolak -->
                    <div v-if="v.status_verifikasi === 'ditolak' && v.catatan"
                        class="mt-4 flex items-start gap-2.5 rounded-xl bg-red-50 p-3.5 ring-1 ring-red-200">
                        <svg class="mt-0.5 h-4 w-4 shrink-0 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <div>
                            <p class="text-xs font-bold text-red-700">Catatan Admin:</p>
                            <p class="mt-0.5 text-xs text-red-600">{{ v.catatan }}</p>
                            <a :href="`/kader/keluarga/${v.keluarga_id}`" class="mt-2 inline-flex items-center gap-1 text-xs font-bold text-red-700 underline hover:text-red-900">
                                Perbaiki data →
                            </a>
                        </div>
                    </div>

                    <!-- Success message jika disetujui -->
                    <div v-if="v.status_verifikasi === 'disetujui'"
                        class="mt-4 flex items-center gap-2 rounded-xl bg-emerald-50 px-3.5 py-3 ring-1 ring-emerald-200">
                        <svg class="h-4 w-4 shrink-0 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <p class="text-xs font-semibold text-emerald-700">Data KK ini sudah diverifikasi dan tercatat resmi</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pagination -->
        <div v-if="verifikasis.total > verifikasis.per_page" class="mt-5 flex items-center justify-between">
            <p class="text-xs text-gray-400">{{ verifikasis.total }} total data verifikasi</p>
            <div class="flex gap-1.5">
                <a v-if="verifikasis.prev_page_url" :href="verifikasis.prev_page_url"
                    class="rounded-xl border border-gray-200 bg-white px-4 py-2 text-xs font-semibold text-gray-600 transition hover:bg-gray-50">← Prev</a>
                <a v-if="verifikasis.next_page_url" :href="verifikasis.next_page_url"
                    class="rounded-xl border border-gray-200 bg-white px-4 py-2 text-xs font-semibold text-gray-600 transition hover:bg-gray-50">Next →</a>
            </div>
        </div>
    </AppLayout>
</template>
