<script setup lang="ts">
import { computed } from 'vue';

const props = defineProps<{
    type?: 'success' | 'error' | 'warning' | 'info';
    message?: string;
    show?: boolean;
}>();

defineEmits(['close']);

const config = computed(() => {
    switch (props.type) {
        case 'error':   return { bg: 'bg-red-50', border: 'border-red-200', text: 'text-red-800', icon: 'text-red-500', iconPath: 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z' };
        case 'warning': return { bg: 'bg-yellow-50', border: 'border-yellow-200', text: 'text-yellow-800', icon: 'text-yellow-500', iconPath: 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z' };
        case 'info':    return { bg: 'bg-blue-50', border: 'border-blue-200', text: 'text-blue-800', icon: 'text-blue-500', iconPath: 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z' };
        default:        return { bg: 'bg-green-50', border: 'border-green-200', text: 'text-green-800', icon: 'text-green-500', iconPath: 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z' };
    }
});
</script>

<template>
    <Transition
        enter-active-class="transition duration-300 ease-out"
        enter-from-class="opacity-0 -translate-y-2"
        enter-to-class="opacity-100 translate-y-0"
        leave-active-class="transition duration-200 ease-in"
        leave-from-class="opacity-100 translate-y-0"
        leave-to-class="opacity-0 -translate-y-2"
    >
        <div v-if="show && message" :class="[config.bg, config.border, config.text]" class="mb-4 flex items-start gap-3 rounded-xl border p-4 shadow-sm">
            <svg :class="config.icon" class="mt-0.5 h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="config.iconPath" />
            </svg>
            <p class="flex-1 text-sm font-medium">{{ message }}</p>
            <button @click="$emit('close')" :class="config.icon" class="opacity-60 hover:opacity-100 transition">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
    </Transition>
</template>
