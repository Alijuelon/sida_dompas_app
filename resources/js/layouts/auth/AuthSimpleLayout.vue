<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { home } from '@/routes';

defineProps<{
    title?: string;
    description?: string;
}>();
</script>

<template>
    <div
        class="relative flex min-h-screen items-center justify-center overflow-hidden bg-slate-50 p-4 font-sans sm:p-8">
        <!-- Premium Ambient Background -->
        <div class="pointer-events-none absolute inset-0 overflow-hidden">
            <div class="absolute -top-[20%] -right-[10%] h-[70vw] w-[70vw] max-w-[800px] animate-pulse rounded-full bg-emerald-400/20 blur-[100px]"
                style="animation-duration: 8s;"></div>
            <div class="absolute -bottom-[20%] -left-[10%] h-[60vw] w-[60vw] max-w-[600px] animate-pulse rounded-full bg-teal-500/20 blur-[100px]"
                style="animation-duration: 10s; animation-delay: 2s;"></div>
            <div
                class="absolute left-1/2 top-1/2 h-[50vw] w-[50vw] max-w-[500px] -translate-x-1/2 -translate-y-1/2 rounded-full bg-emerald-200/30 blur-[80px]">
            </div>
        </div>

        <!-- Glassmorphism Card -->
        <div
            class="relative z-10 w-full max-w-[460px] overflow-hidden rounded-[2.5rem] border border-white/60 bg-white/70 p-8 shadow-[0_8px_40px_-12px_rgba(0,0,0,0.1)] backdrop-blur-2xl sm:p-10 transition-all duration-300 hover:shadow-[0_16px_60px_-15px_rgba(0,0,0,0.15)]">

            <!-- Branding Header -->
            <div class="mb-8 flex flex-col items-center text-center">
                <div
                    class="group relative mb-5 flex h-20 w-20 items-center justify-center rounded-[1.5rem] bg-gradient-to-br from-emerald-500 to-teal-700 shadow-xl shadow-emerald-500/30 transition-transform duration-500 hover:scale-105">
                    <div
                        class="absolute inset-0 rounded-[1.5rem] bg-white/20 opacity-0 mix-blend-overlay transition-opacity duration-500 group-hover:opacity-100">
                    </div>
                    <svg class="h-10 w-10 fill-white drop-shadow-md" viewBox="0 0 24 24">
                        <path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z" />
                    </svg>
                </div>
                <h1
                    class="bg-gradient-to-br from-slate-900 to-slate-700 bg-clip-text text-3xl font-extrabold tracking-tight text-transparent">
                    SIDA Dompas
                </h1>
                <p class="mt-1.5 text-[0.8rem] font-bold tracking-[0.15em] text-emerald-600/90 uppercase">
                    Sistem Informasi Dasawisma
                </p>
            </div>


            <!-- Form Content -->
            <div class="mt-8">
                <slot />
            </div>

            <p class="mt-10 text-center text-xs font-medium text-slate-400">
                &copy; {{ new Date().getFullYear() }} SIDA Dompas &mdash; Desa Dompas
            </p>
        </div>
    </div>
</template>
