<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { home } from '@/routes';

defineProps<{
    title?: string;
    description?: string;
}>();
</script>

<template>
    <div class="flex min-h-svh">
        <!-- Left Panel - Branding (hidden on mobile) -->
        <div class="hidden w-1/2 flex-col items-center justify-center bg-gradient-to-br from-green-700 to-green-900 p-12 text-white md:flex">
            <div class="max-w-md text-center">
                <!-- Logo Icon -->
                <div class="mx-auto mb-8 flex h-20 w-20 items-center justify-center rounded-2xl bg-white/20 shadow-lg">
                    <svg class="h-12 w-12 fill-white" viewBox="0 0 24 24">
                        <path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/>
                    </svg>
                </div>
                <h1 class="mb-3 text-4xl font-bold tracking-tight">SIDA Dompas</h1>
                <p class="mb-2 text-xl font-medium text-green-200">Sistem Informasi Dasawisma</p>
                <p class="text-sm leading-relaxed text-green-100/80">
                    Platform digital untuk pengelolaan data warga, administrasi dasawisma, dan pelaporan kepala desa di Desa Dompas.
                </p>

                <div class="mt-12 grid grid-cols-3 gap-6 text-center">
                    <div class="rounded-xl bg-white/10 p-4">
                        <div class="text-2xl font-bold">3</div>
                        <div class="text-xs text-green-200">Peran Pengguna</div>
                    </div>
                    <div class="rounded-xl bg-white/10 p-4">
                        <div class="text-2xl font-bold">📋</div>
                        <div class="text-xs text-green-200">Verifikasi Data</div>
                    </div>
                    <div class="rounded-xl bg-white/10 p-4">
                        <div class="text-2xl font-bold">📊</div>
                        <div class="text-xs text-green-200">Laporan Warga</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Panel - Login Form -->
        <div class="flex w-full flex-col items-center justify-center bg-gray-50 p-6 md:w-1/2 dark:bg-gray-900">
            <div class="w-full max-w-sm">
                <!-- Mobile Logo -->
                <div class="mb-8 flex flex-col items-center md:hidden">
                    <div class="mb-3 flex h-14 w-14 items-center justify-center rounded-2xl bg-green-700 shadow">
                        <svg class="h-8 w-8 fill-white" viewBox="0 0 24 24"><path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/></svg>
                    </div>
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white">SIDA Dompas</h2>
                </div>

                <!-- Title -->
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">{{ title }}</h2>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ description }}</p>
                </div>

                <slot />

                <p class="mt-8 text-center text-xs text-gray-400">
                    © 2026 SIDA Dompas — Desa Dompas
                </p>
            </div>
        </div>
    </div>
</template>
