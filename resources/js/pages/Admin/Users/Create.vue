<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Manajemen User', href: '/admin/users' },
    { title: 'Tambah User', href: '#' },
];

const form = useForm({
    name: '',
    username: '',
    email: '',
    password: '',
    password_confirmation: '',
    role: 'kader',
    status: true,
    nama_kader: '',
    wilayah: '',
    no_hp: '',
});

function submit() {
    form.post('/admin/users');
}
</script>

<template>
    <Head title="Tambah User" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="mx-auto max-w-2xl p-6">
            <div class="mb-6">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Tambah User Baru</h1>
                <p class="text-sm text-gray-500">Buat akun baru untuk Kader atau Kepala Desa</p>
            </div>

            <form @submit.prevent="submit" class="space-y-5 rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Nama Lengkap *</label>
                        <input v-model="form.name" type="text" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none dark:border-gray-600 dark:bg-gray-700 dark:text-white" />
                        <p v-if="form.errors.name" class="mt-1 text-xs text-red-500">{{ form.errors.name }}</p>
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Username *</label>
                        <input v-model="form.username" type="text" class="w-full rounded-lg border border-gray-300 px-3 py-2 font-mono text-sm focus:border-blue-500 focus:outline-none dark:border-gray-600 dark:bg-gray-700 dark:text-white" />
                        <p v-if="form.errors.username" class="mt-1 text-xs text-red-500">{{ form.errors.username }}</p>
                    </div>
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Email (opsional)</label>
                    <input v-model="form.email" type="email" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none dark:border-gray-600 dark:bg-gray-700 dark:text-white" />
                    <p v-if="form.errors.email" class="mt-1 text-xs text-red-500">{{ form.errors.email }}</p>
                </div>

                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Password *</label>
                        <input v-model="form.password" type="password" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none dark:border-gray-600 dark:bg-gray-700 dark:text-white" />
                        <p v-if="form.errors.password" class="mt-1 text-xs text-red-500">{{ form.errors.password }}</p>
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Konfirmasi Password *</label>
                        <input v-model="form.password_confirmation" type="password" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none dark:border-gray-600 dark:bg-gray-700 dark:text-white" />
                    </div>
                </div>

                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Role *</label>
                        <select v-model="form.role" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                            <option value="kader">Kader Dasawisma</option>
                            <option value="kades">Kepala Desa</option>
                        </select>
                    </div>
                    <div class="flex items-end gap-2">
                        <label class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300">
                            <input v-model="form.status" type="checkbox" class="rounded" />
                            Akun Aktif
                        </label>
                    </div>
                </div>

                <!-- Data Kader (tampil hanya jika role kader) -->
                <template v-if="form.role === 'kader'">
                    <hr class="border-gray-200 dark:border-gray-700" />
                    <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Data Kader</p>
                    <div class="grid gap-4 md:grid-cols-2">
                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-600 dark:text-gray-400">Nama Kader *</label>
                            <input v-model="form.nama_kader" type="text" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none dark:border-gray-600 dark:bg-gray-700 dark:text-white" />
                            <p v-if="form.errors.nama_kader" class="mt-1 text-xs text-red-500">{{ form.errors.nama_kader }}</p>
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-600 dark:text-gray-400">No. HP</label>
                            <input v-model="form.no_hp" type="text" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none dark:border-gray-600 dark:bg-gray-700 dark:text-white" />
                        </div>
                        <div class="md:col-span-2">
                            <label class="mb-1 block text-sm font-medium text-gray-600 dark:text-gray-400">Wilayah</label>
                            <input v-model="form.wilayah" type="text" placeholder="cth: RT 01 / RW 02" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none dark:border-gray-600 dark:bg-gray-700 dark:text-white" />
                        </div>
                    </div>
                </template>

                <div class="flex items-center gap-3 pt-2">
                    <button type="submit" :disabled="form.processing" class="rounded-lg bg-blue-600 px-5 py-2 text-sm font-medium text-white hover:bg-blue-700 disabled:opacity-50">
                        {{ form.processing ? 'Menyimpan...' : 'Simpan' }}
                    </button>
                    <a href="/admin/users" class="text-sm text-gray-500 hover:text-gray-700">Batal</a>
                </div>
            </form>
        </div>
    </AppLayout>
</template>
