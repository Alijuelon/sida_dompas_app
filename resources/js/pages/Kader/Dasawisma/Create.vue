<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/kader/dashboard' },
    { title: 'Data Dasawisma', href: '/kader/dasawisma' },
    { title: 'Tambah', href: '#' },
];

const form = useForm({
    nama_dasawisma: '',
    rt: '',
    rw: '',
    desa: 'Desa Dompas',
});

function submit() {
    form.post('/kader/dasawisma');
}
</script>

<template>
    <Head title="Tambah Dasawisma" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="mx-auto max-w-xl p-6">
            <div class="mb-6">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Tambah Dasawisma</h1>
                <p class="text-sm text-gray-500">Isi data dasawisma baru di wilayah Anda</p>
            </div>

            <form @submit.prevent="submit" class="space-y-5 rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Nama Dasawisma <span class="text-red-500">*</span></label>
                    <input v-model="form.nama_dasawisma" type="text" placeholder="cth: Dasawisma Melati" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white" />
                    <p v-if="form.errors.nama_dasawisma" class="mt-1 text-xs text-red-500">{{ form.errors.nama_dasawisma }}</p>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">RT <span class="text-red-500">*</span></label>
                        <input v-model="form.rt" type="text" placeholder="01" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white" />
                        <p v-if="form.errors.rt" class="mt-1 text-xs text-red-500">{{ form.errors.rt }}</p>
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">RW <span class="text-red-500">*</span></label>
                        <input v-model="form.rw" type="text" placeholder="01" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white" />
                        <p v-if="form.errors.rw" class="mt-1 text-xs text-red-500">{{ form.errors.rw }}</p>
                    </div>
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Desa <span class="text-red-500">*</span></label>
                    <input v-model="form.desa" type="text" placeholder="Nama desa" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white" />
                    <p v-if="form.errors.desa" class="mt-1 text-xs text-red-500">{{ form.errors.desa }}</p>
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <button type="submit" :disabled="form.processing" class="rounded-lg bg-blue-600 px-5 py-2 text-sm font-medium text-white hover:bg-blue-700 disabled:opacity-50">
                        {{ form.processing ? 'Menyimpan...' : 'Simpan' }}
                    </button>
                    <a href="/kader/dasawisma" class="text-sm text-gray-500 hover:text-gray-700">Batal</a>
                </div>
            </form>
        </div>
    </AppLayout>
</template>
