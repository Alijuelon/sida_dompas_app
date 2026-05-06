<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import Heading from '@/components/Heading.vue';
import { Button } from '@/components/ui/button';
import { Separator } from '@/components/ui/separator';
import { useCurrentUrl } from '@/composables/useCurrentUrl';
import { toUrl } from '@/lib/utils';
import { edit as editProfile } from '@/routes/profile';
import { edit as editSecurity } from '@/routes/security';
import type { NavItem } from '@/types';

const sidebarNavItems: NavItem[] = [
    {
        title: 'Profile',
        href: editProfile(),
    },
    {
        title: 'Security',
        href: editSecurity(),
    },
];

const { isCurrentOrParentUrl } = useCurrentUrl();
</script>

<template>
    <div class="px-4 py-8 md:px-8 lg:px-12 max-w-7xl mx-auto">
        <div class="mb-8">
            <h1 class="text-3xl font-extrabold tracking-tight text-slate-900">Pengaturan Akun</h1>
            <p class="mt-2 text-base text-slate-500">Kelola profil, keamanan, dan preferensi akun Anda.</p>
        </div>

        <div class="flex flex-col lg:flex-row lg:space-x-8">
            <aside class="w-full lg:w-64 shrink-0">
                <nav
                    class="flex lg:flex-col lg:space-y-2 overflow-x-auto pb-4 lg:pb-0 space-x-2 lg:space-x-0 no-scrollbar"
                    aria-label="Settings"
                >
                    <Button
                        v-for="item in sidebarNavItems"
                        :key="toUrl(item.href)"
                        variant="ghost"
                        :class="[
                            'lg:w-full justify-start whitespace-nowrap shrink-0 rounded-xl transition-all',
                            isCurrentOrParentUrl(item.href) ? 'bg-emerald-50 text-emerald-700 font-semibold shadow-sm border border-emerald-100' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900',
                        ]"
                        as-child
                    >
                        <Link :href="item.href">
                            <div class="flex items-center">
                                <span class="mr-3 h-2 w-2 rounded-full" :class="isCurrentOrParentUrl(item.href) ? 'bg-emerald-500' : 'bg-transparent'"></span>
                                {{ item.title }}
                            </div>
                        </Link>
                    </Button>
                </nav>
            </aside>

            <div class="flex-1 max-w-3xl">
                <div class="rounded-3xl bg-white p-6 sm:p-10 shadow-xl shadow-slate-200/40 ring-1 ring-slate-100">
                    <section class="space-y-12">
                        <slot />
                    </section>
                </div>
            </div>
        </div>
    </div>
</template>
