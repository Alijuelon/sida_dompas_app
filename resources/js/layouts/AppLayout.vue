<script setup lang="ts">
import SidaLayout from '@/layouts/SidaLayout.vue';

withDefaults(defineProps<{ breadcrumbs?: any[] }>(), { breadcrumbs: () => [] });

</script>

<template>
    <SidaLayout>
        <slot />
    </SidaLayout>
</template>
