<script setup lang="ts">
import { computed, watch, ref } from 'vue';
import { usePage } from '@inertiajs/vue3';

const page = usePage();

const show = ref(false);
const message = ref('');
const type = ref<'success' | 'error' | 'warning' | 'info'>('info');

const config = computed(() => {
    switch (type.value) {
        case 'error':   return { border: 'border-red-500', text: 'text-red-800', icon: 'text-red-500', iconPath: 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z', bgIcon: 'bg-red-100' };
        case 'warning': return { border: 'border-amber-500', text: 'text-amber-800', icon: 'text-amber-500', iconPath: 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z', bgIcon: 'bg-amber-100' };
        case 'info':    return { border: 'border-blue-500', text: 'text-blue-800', icon: 'text-blue-500', iconPath: 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z', bgIcon: 'bg-blue-100' };
        default:        return { border: 'border-emerald-500', text: 'text-emerald-800', icon: 'text-emerald-500', iconPath: 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z', bgIcon: 'bg-emerald-100' };
    }
});

let timeoutId: any = null;

const role = computed(() => (page.props as any).auth?.user?.role ?? '');

function triggerAlert(t: any, msg: string) {
    // Hide popup for admin role as they already have inline notifications
    if (role.value === 'admin') return;

    type.value = t;
    message.value = msg;
    show.value = true;
    
    if (timeoutId) clearTimeout(timeoutId);
    timeoutId = setTimeout(() => {
        show.value = false;
    }, 5000);
}

// Watch inertia flash messages globally
watch(() => page.props.flash, (flash: any) => {
    if (flash?.success) triggerAlert('success', flash.success);
    else if (flash?.error) triggerAlert('error', flash.error);
    else if (flash?.warning) triggerAlert('warning', flash.warning);
}, { deep: true });

// Watch validation errors globally
watch(() => page.props.errors, (errors: any) => {
    if (errors && Object.keys(errors).length > 0) {
        // Gabungkan semua pesan error jika ada banyak
        const firstError = Object.values(errors)[0] as string;
        triggerAlert('error', firstError);
    }
}, { deep: true });
</script>

<template>
    <div class="fixed top-4 left-4 right-4 z-[9999] flex flex-col items-end pointer-events-none sm:left-auto sm:top-6 sm:right-6">
        <Transition
            enter-active-class="transform ease-out duration-300 transition"
            enter-from-class="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-4"
            enter-to-class="translate-y-0 opacity-100 sm:translate-x-0"
            leave-active-class="transition ease-in duration-200"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0 translate-x-4"
        >
            <div v-if="show" class="pointer-events-auto w-full max-w-sm overflow-hidden rounded-2xl bg-white/95 backdrop-blur shadow-2xl ring-1 ring-black/5 flex items-center p-4 border-l-4" :class="config.border">
                <div class="flex-shrink-0 flex items-center justify-center h-10 w-10 rounded-full" :class="config.bgIcon">
                    <svg :class="config.icon" class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="config.iconPath" />
                    </svg>
                </div>
                <div class="ml-4 w-0 flex-1">
                    <p class="text-sm font-bold text-slate-800 mb-0.5">
                        {{ type === 'success' ? 'Berhasil' : type === 'error' ? 'Terjadi Kesalahan' : 'Peringatan' }}
                    </p>
                    <p class="text-[13px] font-medium leading-tight text-slate-500">{{ message }}</p>
                </div>
                <div class="ml-4 flex flex-shrink-0">
                    <button @click="show = false" class="inline-flex rounded-lg p-1.5 text-slate-400 hover:bg-slate-100 hover:text-slate-600 transition-colors">
                        <span class="sr-only">Tutup</span>
                        <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
            </div>
        </Transition>
    </div>
</template>
