<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Data Dasawisma', href: '/kader/dasawisma' },
    { title: 'Edit', href: '#' },
];

const props = defineProps<{
    dasawisma: {
        id: number;
        nama_dasawisma: string;
        rt: string;
        rw: string;
        desa: string;
    };
}>();

const form = useForm({
    nama_dasawisma: props.dasawisma.nama_dasawisma,
    rt: props.dasawisma.rt,
    rw: props.dasawisma.rw,
    desa: props.dasawisma.desa,
});

function submit() {
    form.put(`/kader/dasawisma/${props.dasawisma.id}`);
}
</script>

<template>
    <Head title="Edit Dasawisma" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="mx-auto max-w-xl p-6">
            <div class="mb-6">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Edit Dasawisma</h1>
                <p class="text-sm text-gray-500">Perbarui data dasawisma</p>
            </div>

            <form @submit.prevent="submit" class="space-y-5 rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Nama Dasawisma</label>
                    <input v-model="form.nama_dasawisma" type="text" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white" />
                    <p v-if="form.errors.nama_dasawisma" class="mt-1 text-xs text-red-500">{{ form.errors.nama_dasawisma }}</p>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">RT</label>
                        <input v-model="form.rt" type="text" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white" />
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">RW</label>
                        <input v-model="form.rw" type="text" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white" />
                    </div>
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Desa</label>
                    <input v-model="form.desa" type="text" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white" />
                </div>
                <div class="flex items-center gap-3 pt-2">
                    <button type="submit" :disabled="form.processing" class="rounded-lg bg-blue-600 px-5 py-2 text-sm font-medium text-white hover:bg-blue-700 disabled:opacity-50">
                        {{ form.processing ? 'Menyimpan...' : 'Simpan Perubahan' }}
                    </button>
                    <a href="/kader/dasawisma" class="text-sm text-gray-500 hover:text-gray-700">Batal</a>
                </div>
            </form>
        </div>
    </AppLayout>
</template>
