<script setup lang="ts">
import { ref } from 'vue';

defineProps<{
    show?: boolean;
    title?: string;
    message?: string;
    confirmText?: string;
    confirmClass?: string;
}>();

const emit = defineEmits(['confirm', 'cancel']);
</script>

<template>
    <Teleport to="body">
        <Transition enter-active-class="transition duration-200" enter-from-class="opacity-0" enter-to-class="opacity-100" leave-active-class="transition duration-150" leave-from-class="opacity-100" leave-to-class="opacity-0">
            <div v-if="show" class="fixed inset-0 z-50 flex items-center justify-center p-4" style="color-scheme:light;">
                <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" @click="$emit('cancel')" />
                <Transition enter-active-class="transition duration-200 ease-out" enter-from-class="opacity-0 scale-95" enter-to-class="opacity-100 scale-100">
                    <div v-if="show" class="relative w-full max-w-sm rounded-2xl bg-white p-6 shadow-2xl text-center">
                        <!-- Icon -->
                        <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-red-100">
                            <svg class="h-7 w-7 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </div>
                        <h3 class="mb-2 text-lg font-semibold text-gray-800">{{ title ?? 'Konfirmasi Hapus' }}</h3>
                        <p class="mb-6 text-sm text-gray-500">{{ message ?? 'Apakah Anda yakin ingin menghapus data ini? Tindakan ini tidak dapat dibatalkan.' }}</p>
                        <div class="flex items-center justify-center gap-3">
                            <button @click="$emit('cancel')" class="rounded-xl border border-gray-200 px-5 py-2.5 text-sm font-medium text-gray-600 hover:bg-gray-50 transition">
                                Batal
                            </button>
                            <button @click="$emit('confirm')" :class="confirmClass ?? 'bg-red-600 hover:bg-red-700'" class="rounded-xl px-5 py-2.5 text-sm font-medium text-white transition">
                                {{ confirmText ?? 'Ya, Hapus' }}
                            </button>
                        </div>
                    </div>
                </Transition>
            </div>
        </Transition>
    </Teleport>
</template>
