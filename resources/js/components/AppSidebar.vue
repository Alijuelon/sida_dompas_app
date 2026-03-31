<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import AppLogo from '@/components/AppLogo.vue';
import NavFooter from '@/components/NavFooter.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar';
import type { NavItem } from '@/types';

const page = usePage();
const user = computed(() => (page.props as any).auth?.user);
const role = computed(() => user.value?.role ?? '');

// Menu Admin
const adminNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: '/admin/dashboard',
        icon: null as any,
    },
    {
        title: 'Verifikasi Data',
        href: '/admin/verifikasi',
        icon: null as any,
    },
    {
        title: 'Manajemen User',
        href: '/admin/users',
        icon: null as any,
    },
    {
        title: 'Laporan',
        href: '/admin/laporan',
        icon: null as any,
    },
];

// Menu Kader
const kaderNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: '/kader/dashboard',
        icon: null as any,
    },
    {
        title: 'Data Dasawisma',
        href: '/kader/dasawisma',
        icon: null as any,
    },
    {
        title: 'Data KK & Anggota',
        href: '/kader/keluarga',
        icon: null as any,
    },
    {
        title: 'Status Verifikasi',
        href: '/kader/status-verifikasi',
        icon: null as any,
    },
];

// Menu Kades
const kadesNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: '/kades/dashboard',
        icon: null as any,
    },
    {
        title: 'Laporan Dasawisma',
        href: '/kades/laporan',
        icon: null as any,
    },
];

const mainNavItems = computed(() => {
    if (role.value === 'admin') return adminNavItems;
    if (role.value === 'kader') return kaderNavItems;
    if (role.value === 'kades') return kadesNavItems;
    return [];
});

const dashboardHref = computed(() => {
    if (role.value === 'admin') return '/admin/dashboard';
    if (role.value === 'kader') return '/kader/dashboard';
    if (role.value === 'kades') return '/kades/dashboard';
    return '/dashboard';
});

const footerNavItems: NavItem[] = [];
</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="dashboardHref">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <!-- Role Badge -->
            <div class="px-4 py-2">
                <span
                    v-if="role"
                    class="rounded-full px-3 py-1 text-xs font-semibold uppercase tracking-wide"
                    :class="{
                        'bg-red-100 text-red-800': role === 'admin',
                        'bg-blue-100 text-blue-800': role === 'kader',
                        'bg-purple-100 text-purple-800': role === 'kades',
                    }"
                >
                    {{ role }}
                </span>
            </div>

            <!-- Dynamic Nav -->
            <nav class="px-2 py-1">
                <ul class="space-y-1">
                    <li v-for="item in mainNavItems" :key="item.href">
                        <Link
                            :href="item.href"
                            class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-colors"
                            :class="
                                $page.url.startsWith(item.href)
                                    ? 'bg-blue-50 text-blue-700 dark:bg-blue-900/20 dark:text-blue-400'
                                    : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-300 dark:hover:bg-gray-700 dark:hover:text-white'
                            "
                        >
                            {{ item.title }}
                        </Link>
                    </li>
                </ul>
            </nav>
        </SidebarContent>

        <SidebarFooter>
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
