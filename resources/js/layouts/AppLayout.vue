<script setup lang="ts">
import SidaLayout from '@/layouts/SidaLayout.vue';
import AiChatWidget from '@/components/AiChatWidget.vue';
import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

withDefaults(defineProps<{ breadcrumbs?: any[] }>(), { breadcrumbs: () => [] });

const page = usePage();
const role = computed(() => (page.props as any).auth?.user?.role ?? '');
const showAi = computed(() => role.value === 'admin' || role.value === 'kader' || role.value === 'kades');
</script>

<template>
    <SidaLayout>
        <slot />
        <AiChatWidget v-if="showAi" />
    </SidaLayout>
</template>
