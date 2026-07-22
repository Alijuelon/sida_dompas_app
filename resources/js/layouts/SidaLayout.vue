<script setup lang="ts">
import { Link, usePage, router } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import GlobalAlert from '@/components/GlobalAlert.vue';

const page = usePage();
const user = computed(() => (page.props as any).auth?.user);
const role = computed(() => user.value?.role ?? '');
const sidebarOpen = ref(true);

const adminMenus = [
    {
        label: 'Beranda',
        icon: 'home-group',
        color: 'from-blue-500 to-blue-600',
        items: [
            { title: 'Dashboard', href: '/admin/dashboard', icon: 'home' },
        ]
    },
    {
        label: 'Pendataan',
        icon: 'data-group',
        color: 'from-emerald-500 to-teal-600',
        items: [
            { title: 'Data Dasawisma', href: '/kader/dasawisma', icon: 'office' },
            { title: 'Data KK & Anggota', href: '/kader/keluarga', icon: 'users' },
        ]
    },
    {
        label: 'Pemantauan',
        icon: 'monitor-group',
        color: 'from-amber-500 to-orange-500',
        items: [
            { title: 'Status Verifikasi', href: '/kader/status-verifikasi', icon: 'check-circle' },
        ]
    },
    {
        label: 'Kelola Data',
        icon: 'manage-group',
        color: 'from-emerald-500 to-teal-600',
        items: [
            { title: 'Verifikasi Data', href: '/admin/verifikasi', icon: 'check-circle' },
            { title: 'Manajemen User', href: '/admin/users', icon: 'users' },
        ]
    },
    {
        label: 'Laporan',
        icon: 'report-group',
        color: 'from-purple-500 to-violet-600',
        items: [
            { title: 'Laporan Populasi', href: '/admin/laporan', icon: 'chart-bar' },
        ]
    },
];

const kaderMenus = [
    {
        label: 'Beranda',
        icon: 'home-group',
        color: 'from-blue-500 to-blue-600',
        items: [
            { title: 'Dashboard', href: '/kader/dashboard', icon: 'home' },
        ]
    },
    {
        label: 'Pendataan',
        icon: 'data-group',
        color: 'from-emerald-500 to-teal-600',
        items: [
            { title: 'Data Dasawisma', href: '/kader/dasawisma', icon: 'office' },
            { title: 'Data KK & Anggota', href: '/kader/keluarga', icon: 'users' },
        ]
    },
    {
        label: 'Pemantauan',
        icon: 'monitor-group',
        color: 'from-amber-500 to-orange-500',
        items: [
            { title: 'Status Verifikasi', href: '/kader/status-verifikasi', icon: 'check-circle' },
        ]
    },
    {
        label: 'Laporan',
        icon: 'report-group',
        color: 'from-purple-500 to-violet-600',
        items: [
            { title: 'Laporan Populasi', href: '/kader/laporan', icon: 'chart-bar' },
        ]
    },
];

const kadesMenus = [
    {
        label: 'Beranda',
        icon: 'home-group',
        color: 'from-blue-500 to-blue-600',
        items: [
            { title: 'Dashboard', href: '/kades/dashboard', icon: 'home' },
        ]
    },
    {
        label: 'Informasi',
        icon: 'info-group',
        color: 'from-purple-500 to-violet-600',
        items: [
            { title: 'Laporan Dasawisma', href: '/kades/laporan', icon: 'chart-bar' },
        ]
    },
];

const menus = computed(() => {
    if (role.value === 'admin') return adminMenus;
    if (role.value === 'kader') return kaderMenus;
    if (role.value === 'kades') return kadesMenus;
    return [];
});

const dashboardHref = computed(() => {
    if (role.value === 'admin') return '/admin/dashboard';
    if (role.value === 'kader') return '/kader/dashboard';
    if (role.value === 'kades') return '/kades/dashboard';
    return '/dashboard';
});

const roleLabel = computed(() => {
    const map: Record<string, string> = { admin: 'Administrator', kader: 'Kader Dasawisma', kades: 'Kepala Desa' };
    return map[role.value] ?? role.value;
});

const userInitial = computed(() => user.value?.name?.charAt(0)?.toUpperCase() ?? '?');

function isActive(href: string) {
    return page.url.startsWith(href);
}

function logout() {
    router.post('/logout');
}

function closeSidebarMobile() {
    if (typeof window !== 'undefined' && window.innerWidth < 1024) {
        sidebarOpen.value = false;
    }
}

const pageTitle = computed(() => {
    const segments = page.url.split('/').filter(Boolean);
    const last = segments.at(-1) ?? '';
    const titleMap: Record<string, string> = {
        dashboard: 'Dashboard',
        dasawisma: 'Data Dasawisma',
        keluarga: 'Data KK & Anggota',
        'status-verifikasi': 'Status Verifikasi',
        verifikasi: 'Verifikasi Data',
        users: 'Manajemen User',
        laporan: 'Laporan',
        create: 'Tambah Baru',
        edit: 'Edit Data',
    };
    return titleMap[last] ?? last.replace(/-/g, ' ');
});
</script>

<template>
    <div class="flex min-h-screen font-sans bg-slate-50 dark:bg-background text-slate-900 dark:text-foreground">
        <!-- ==================== SIDEBAR ==================== -->
        <!-- Mobile Overlay -->
        <div 
            v-if="sidebarOpen" 
            class="fixed inset-0 z-20 bg-gray-900/50 backdrop-blur-sm lg:hidden"
            @click="sidebarOpen = false"
        ></div>

        <aside
            class="fixed inset-y-0 left-0 z-30 flex flex-col bg-white border-r border-slate-200 shadow-[4px_0_24px_rgba(0,0,0,0.02)] transition-all duration-300 ease-in-out print:hidden"
            :class="[
                sidebarOpen ? 'translate-x-0 w-64' : '-translate-x-full w-64 lg:w-0 lg:translate-x-0 lg:overflow-hidden'
            ]"
        >
            <!-- ===== LOGO ===== -->
            <div class="flex h-20 shrink-0 items-center justify-start border-b border-slate-100 px-6">
                <Link href="/" class="flex items-center gap-3 transition-transform hover:scale-105">
                    <!-- Logo Icon -->
                    <div class="logo-container relative flex h-10 w-10 shrink-0 items-center justify-center">
                        <div class="relative flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-600 shadow-sm logo-icon">
                            <svg class="h-5 w-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-xl font-extrabold tracking-tight text-slate-800">TP-PKK</span>
                        <span class="text-[10px] font-bold uppercase tracking-[0.25em] text-emerald-600">Dompas</span>
                    </div>
                </Link>
            </div>

            <!-- ===== Navigation ===== -->
            <nav class="flex-1 overflow-y-auto px-4 py-6 space-y-8" style="scrollbar-width: none;">
                <div v-for="group in menus" :key="group.label" class="space-y-2">
                    <!-- Group Label -->
                    <div class="px-3">
                        <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">
                            {{ group.label }}
                        </p>
                    </div>
                    <!-- Menu Items -->
                    <div class="space-y-1">
                        <Link
                            v-for="item in group.items"
                            :key="item.href"
                            :href="item.href"
                            @click="closeSidebarMobile"
                            class="group flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-semibold transition-all duration-200"
                            :class="isActive(item.href)
                                ? 'bg-emerald-50 text-emerald-700'
                                : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900'"
                        >
                            <!-- Icon -->
                            <div class="flex shrink-0 items-center justify-center transition-colors"
                                :class="isActive(item.href) ? 'text-emerald-600' : 'text-slate-400 group-hover:text-emerald-500'">
                                <svg v-if="item.icon === 'home'" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                </svg>
                                <svg v-else-if="item.icon === 'check-circle'" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <svg v-else-if="item.icon === 'users'" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                <svg v-else-if="item.icon === 'chart-bar'" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                </svg>
                                <svg v-else class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                            </div>

                            <span class="truncate">{{ item.title }}</span>
                        </Link>
                    </div>
                </div>
            </nav>

            <!-- ===== User Footer ===== -->
            <div class="shrink-0 border-t border-slate-100 p-4">
                <div class="flex items-center gap-3 rounded-2xl bg-slate-50 p-2.5 border border-slate-100/50">
                    <!-- Avatar -->
                    <div class="relative flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-emerald-100 text-sm font-bold text-emerald-700">
                        {{ userInitial }}
                        <!-- Online dot -->
                        <span class="absolute -bottom-0.5 -right-0.5 h-3 w-3 rounded-full border-2 border-white bg-green-500"></span>
                    </div>
                    <div class="min-w-0 flex-1">
                        <Link href="/settings/profile" class="block transition hover:opacity-80">
                            <p class="truncate text-sm font-semibold text-slate-800">{{ user?.name }}</p>
                        </Link>
                        <p class="text-[11px] font-medium text-slate-500">{{ roleLabel }}</p>
                    </div>
                    <Link
                        href="/settings/profile"
                        title="Pengaturan"
                        class="shrink-0 rounded-lg p-2 text-slate-400 transition hover:bg-slate-200 hover:text-slate-700"
                    >
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </Link>
                    <button
                        @click="logout"
                        title="Keluar"
                        class="shrink-0 rounded-lg p-2 text-slate-400 transition hover:bg-red-50 hover:text-red-600"
                    >
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                    </button>
                </div>
            </div>
        </aside>

        <!-- ==================== MAIN ==================== -->
        <div
            class="flex min-w-0 flex-1 flex-col transition-all duration-300 ease-in-out print:m-0 print:w-full"
            :class="sidebarOpen ? 'lg:ml-64' : 'ml-0'"
        >
            <!-- Topbar -->
            <header
                class="sticky top-0 z-20 flex h-16 shrink-0 items-center justify-between border-b border-gray-200 dark:border-white/10 px-5 shadow-sm print:hidden bg-white/95 dark:bg-zinc-950/95 backdrop-blur-md"
            >
                <div class="flex items-center gap-4">
                    <button
                        @click="sidebarOpen = !sidebarOpen"
                        class="rounded-xl p-2 text-gray-500 dark:text-gray-400 transition hover:bg-gray-100 dark:hover:bg-white/10 hover:text-gray-700 dark:hover:text-gray-200"
                    >
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                    <!-- Breadcrumb -->
                    <div class="flex items-center gap-1.5 text-sm">
                        <Link :href="dashboardHref" class="text-gray-400 transition hover:text-emerald-600 dark:hover:text-emerald-400">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                        </Link>
                        <svg class="h-3.5 w-3.5 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                        <span class="font-semibold capitalize text-gray-700 dark:text-gray-200">{{ pageTitle }}</span>
                    </div>
                </div>

                <!-- Right -->
                <div class="flex items-center gap-3">
                    <span class="hidden rounded-full bg-emerald-50 dark:bg-emerald-900/30 px-3 py-1 text-xs font-bold text-emerald-700 dark:text-emerald-400 ring-1 ring-emerald-200 dark:ring-emerald-800 sm:inline-flex">
                        {{ roleLabel }}
                    </span>
                    <div class="h-8 w-px bg-gray-200 dark:bg-white/10"></div>
                    <div class="flex h-8 w-8 items-center justify-center rounded-xl bg-gradient-to-br from-emerald-500 to-teal-600 text-sm font-bold text-white shadow-sm">
                        {{ userInitial }}
                    </div>
                    <span class="hidden text-sm font-semibold text-gray-700 dark:text-gray-200 lg:block">{{ user?.name }}</span>
                </div>
            </header>

            <!-- Content -->
            <main
                class="flex-1 overflow-y-auto p-6 print:p-0 print:bg-white"
            >
                <slot />
            </main>
        </div>
        <GlobalAlert />
    </div>
</template>

<style scoped>
/* Animasi logo saat pertama muncul */
.logo-container {
    animation: logoEntrance 0.8s cubic-bezier(0.34, 1.56, 0.64, 1) both;
}

@keyframes logoEntrance {
    from { transform: scale(0) rotate(-180deg); opacity: 0; }
    to   { transform: scale(1) rotate(0deg); opacity: 1; }
}

/* Ring pulsing */
.logo-ring {
    animation: ringPulse 3s ease-in-out infinite;
}

@keyframes ringPulse {
    0%, 100% { transform: scale(1); opacity: 1; }
    50%       { transform: scale(1.12); opacity: 0.6; }
}

/* Icon hover spin */
.logo-icon {
    transition: transform 0.5s cubic-bezier(0.34, 1.56, 0.64, 1);
}

.logo-container:hover .logo-icon {
    transform: rotate(15deg) scale(1.1);
}

/* Menu card hover */
.menu-card {
    transition: background 0.2s;
}

.menu-card:hover {
    background: rgba(255,255,255,0.08) !important;
}

/* Custom scrollbar sidebar */
nav::-webkit-scrollbar { width: 3px; }
nav::-webkit-scrollbar-track { background: transparent; }
nav::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.15); border-radius: 99px; }
</style>
