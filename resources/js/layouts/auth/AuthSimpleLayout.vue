<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { home } from '@/routes';

defineProps<{
    title?: string;
    description?: string;
}>();
</script>

<template>
    <div class="flex min-h-svh bg-slate-50 font-sans">
        <!-- Left Panel - Branding (hidden on mobile) -->
        <div class="relative hidden w-1/2 flex-col items-center justify-center overflow-hidden bg-gradient-to-br from-emerald-600 to-emerald-900 p-12 text-white md:flex">
            <!-- Decorative background elements -->
            <div class="absolute -left-[10%] -top-[20%] h-[500px] w-[500px] rounded-full bg-emerald-500/20 blur-3xl"></div>
            <div class="absolute -bottom-[20%] -right-[10%] h-[500px] w-[500px] rounded-full bg-emerald-900/40 blur-3xl"></div>
            
            <div class="relative z-10 max-w-md text-center">
                <!-- Logo Icon -->
                <div class="mx-auto mb-8 flex h-24 w-24 items-center justify-center rounded-3xl border border-white/20 bg-white/10 shadow-2xl backdrop-blur-md">
                    <svg class="h-12 w-12 fill-white" viewBox="0 0 24 24">
                        <path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/>
                    </svg>
                </div>
                <h1 class="mb-4 text-5xl font-extrabold tracking-tight text-white drop-shadow-md">SIDA Dompas</h1>
                <p class="mb-4 text-2xl font-semibold text-emerald-100">Sistem Informasi Dasawisma</p>
                <p class="text-base leading-relaxed text-emerald-50/80">
                    Platform digital terpadu untuk pengelolaan data warga, administrasi dasawisma, dan pelaporan kepala desa di Desa Dompas.
                </p>

                <div class="mt-14 grid grid-cols-3 gap-6 text-center">
                    <div class="rounded-2xl border border-white/10 bg-white/10 p-5 backdrop-blur-sm transition-transform hover:-translate-y-1">
                        <div class="mb-1 text-3xl font-bold">3</div>
                        <div class="text-sm font-medium text-emerald-100">Peran Pengguna</div>
                    </div>
                    <div class="rounded-2xl border border-white/10 bg-white/10 p-5 backdrop-blur-sm transition-transform hover:-translate-y-1">
                        <div class="mb-1 text-3xl font-bold">📋</div>
                        <div class="text-sm font-medium text-emerald-100">Verifikasi Data</div>
                    </div>
                    <div class="rounded-2xl border border-white/10 bg-white/10 p-5 backdrop-blur-sm transition-transform hover:-translate-y-1">
                        <div class="mb-1 text-3xl font-bold">📊</div>
                        <div class="text-sm font-medium text-emerald-100">Laporan Warga</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Panel - Login Form -->
        <div class="flex w-full flex-col items-center justify-center bg-white p-6 md:w-1/2">
            <div class="w-full max-w-md rounded-3xl bg-white p-8 transition-all sm:border sm:border-slate-100 sm:shadow-xl">
                <!-- Mobile Logo -->
                <div class="mb-8 flex flex-col items-center md:hidden">
                    <div class="mb-4 flex h-16 w-16 items-center justify-center rounded-2xl bg-emerald-600 shadow-lg">
                        <svg class="h-8 w-8 fill-white" viewBox="0 0 24 24"><path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/></svg>
                    </div>
                    <h2 class="text-2xl font-bold text-slate-800">SIDA Dompas</h2>
                </div>

                <!-- Title -->
                <div class="mb-8 text-center md:text-left">
                    <h2 class="text-3xl font-extrabold text-slate-800">{{ title }}</h2>
                    <p class="mt-2 text-sm text-slate-500">{{ description }}</p>
                </div>

                <div class="mt-6">
                    <slot />
                </div>

                <p class="mt-10 text-center text-sm font-medium text-slate-400">
                    &copy; 2026 SIDA Dompas &mdash; Desa Dompas
                </p>
            </div>
        </div>
    </div>
</template>
