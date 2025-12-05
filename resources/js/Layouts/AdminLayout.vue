<script setup>
import { ref, onMounted, onUnmounted, computed } from "vue";
import { Link, usePage } from "@inertiajs/vue3";
import { router } from "@inertiajs/vue3";

const props = defineProps({
    title: String,
});

const page = usePage();
const user = computed(() => page.props.auth.user);

// Sidebar State
const sidebarOpen = ref(window.innerWidth >= 1024);
const isMobile = ref(window.innerWidth < 1024);

// Dropdown States
const contentOpen = ref(false);
const transportOpen = ref(false);
const usersOpen = ref(false);
const systemOpen = ref(false);
const userDropdownOpen = ref(false);

// Initialize dropdowns based on current route
onMounted(() => {
    checkActiveRoutes();
    window.addEventListener("resize", handleResize);
});

onUnmounted(() => {
    window.removeEventListener("resize", handleResize);
});

const handleResize = () => {
    isMobile.value = window.innerWidth < 1024;
    if (window.innerWidth >= 1024) {
        sidebarOpen.value = true;
    } else {
        sidebarOpen.value = false;
    }
};

const checkActiveRoutes = () => {
    if (
        route().current("admin.news.*") ||
        route().current("admin.categories.*")
    ) {
        contentOpen.value = true;
    }
    if (
        route().current("admin.buses.*") ||
        route().current("admin.routes.*") ||
        route().current("admin.schedules.*") ||
        route().current("admin.schedule-management.*")
    ) {
        transportOpen.value = true;
    }
    if (
        route().current("admin.users.*") ||
        route().current("admin.drivers.*") ||
        route().current("admin.conductors.*")
    ) {
        usersOpen.value = true;
    }
    if (
        route().current("admin.settings.*") ||
        route().current("admin.reports.*")
    ) {
        // reports in separate link but maybe grouped later
    }
};

const logout = () => {
    router.post(route("logout"));
};
</script>

<template>
    <div
        class="flex min-h-screen bg-gray-50 dark:bg-gray-950 font-sans text-gray-900 dark:text-gray-100"
    >
        <!-- Sidebar -->
        <aside
            :class="[
                'bg-slate-900 text-white transition-all duration-300 z-40 flex flex-col shadow-2xl border-r border-slate-800',
                isMobile
                    ? sidebarOpen
                        ? 'fixed inset-y-0 left-0 w-72'
                        : 'fixed inset-y-0 left-0 w-0 overflow-hidden'
                    : sidebarOpen
                    ? 'w-72 relative'
                    : 'w-20 relative',
            ]"
        >
            <!-- Logo Section -->
            <div
                class="h-20 flex items-center justify-between px-6 border-b border-slate-800 bg-slate-950/50 backdrop-blur-sm"
            >
                <div v-show="!isMobile && !sidebarOpen" class="mx-auto">
                    <img
                        src="/img/logoNoBg.png"
                        alt="Logo"
                        class="h-10 w-auto drop-shadow-lg"
                    />
                </div>
                <div
                    v-show="sidebarOpen"
                    class="flex items-center gap-3 transition-opacity duration-300 overflow-hidden whitespace-nowrap"
                >
                    <img
                        src="/img/logoNoBg.png"
                        alt="Logo"
                        class="h-10 w-auto drop-shadow-md"
                    />
                    <div class="flex flex-col">
                        <span
                            class="font-bold text-lg tracking-wide leading-tight font-serif text-white"
                            >Tunggal Jaya</span
                        >
                        <span
                            class="text-[10px] text-gray-400 font-medium tracking-widest uppercase"
                            >Admin Panel</span
                        >
                    </div>
                </div>
                <button
                    v-if="isMobile"
                    @click="sidebarOpen = false"
                    class="text-gray-400 hover:text-white focus:outline-none transition-colors"
                >
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <!-- Navigation -->
            <nav
                class="flex-1 overflow-y-auto py-6 px-4 space-y-2 custom-scrollbar"
            >
                <!-- Dashboard -->
                <Link
                    :href="route('admin.dashboard')"
                    :class="[
                        'flex items-center px-4 py-3.5 rounded-xl transition-all duration-200 group relative overflow-hidden',
                        route().current('admin.dashboard')
                            ? 'bg-gradient-to-r from-brand-red to-red-700 text-white shadow-lg shadow-brand-red/20 translate-x-1'
                            : 'text-slate-400 hover:bg-slate-800 hover:text-white',
                    ]"
                >
                    <i
                        class="fas fa-chart-pie text-lg w-6 text-center z-10"
                    ></i>
                    <span
                        :class="[
                            'ml-3 font-medium whitespace-nowrap transition-all duration-300 z-10',
                            !sidebarOpen && !isMobile
                                ? 'opacity-0 hidden'
                                : 'opacity-100',
                        ]"
                        >Dasbor</span
                    >
                </Link>

                <div class="pt-4 pb-2" v-show="sidebarOpen || isMobile">
                    <p
                        class="px-4 text-[10px] font-bold text-slate-500 uppercase tracking-widest"
                    >
                        Manajemen
                    </p>
                </div>
                <div
                    class="my-2 border-t border-slate-800"
                    v-show="!sidebarOpen && !isMobile"
                ></div>

                <!-- Content Management -->
                <div>
                    <button
                        @click="contentOpen = !contentOpen"
                        :class="[
                            'w-full flex items-center justify-between px-4 py-3.5 rounded-xl transition-colors group',
                            contentOpen
                                ? 'bg-slate-800/50 text-white'
                                : 'text-slate-400 hover:bg-slate-800 hover:text-white',
                        ]"
                    >
                        <div class="flex items-center">
                            <i
                                class="fas fa-layer-group text-lg w-6 text-center"
                            ></i>
                            <span
                                :class="[
                                    'ml-3 font-medium whitespace-nowrap',
                                    !sidebarOpen && !isMobile ? 'hidden' : '',
                                ]"
                                >Konten</span
                            >
                        </div>
                        <i
                            :class="[
                                'fas text-xs transition-transform duration-300 text-slate-500 group-hover:text-slate-300',
                                contentOpen ? 'rotate-180' : '',
                                !sidebarOpen && !isMobile ? 'hidden' : '',
                            ]"
                            class="fa-chevron-down"
                        ></i>
                    </button>
                    <div
                        v-show="
                            (contentOpen && sidebarOpen) ||
                            (contentOpen && isMobile)
                        "
                        class="mt-1 space-y-1 pl-12 pr-2 overflow-hidden transition-all duration-300"
                    >
                        <Link
                            :href="route('admin.news.index')"
                            :class="[
                                'flex items-center px-3 py-2.5 rounded-lg text-sm transition-all duration-200 border-l-2',
                                route().current('admin.news.*')
                                    ? 'border-brand-red text-white bg-slate-800'
                                    : 'border-transparent text-slate-400 hover:text-white hover:bg-slate-800/50',
                            ]"
                        >
                            Berita & Artikel
                        </Link>
                    </div>
                </div>

                <!-- Transport Management -->
                <div>
                    <button
                        @click="transportOpen = !transportOpen"
                        :class="[
                            'w-full flex items-center justify-between px-4 py-3.5 rounded-xl transition-colors group',
                            transportOpen
                                ? 'bg-slate-800/50 text-white'
                                : 'text-slate-400 hover:bg-slate-800 hover:text-white',
                        ]"
                    >
                        <div class="flex items-center">
                            <i
                                class="fas fa-bus-alt text-lg w-6 text-center"
                            ></i>
                            <span
                                :class="[
                                    'ml-3 font-medium whitespace-nowrap',
                                    !sidebarOpen && !isMobile ? 'hidden' : '',
                                ]"
                                >Transportasi</span
                            >
                        </div>
                        <i
                            :class="[
                                'fas text-xs transition-transform duration-300 text-slate-500 group-hover:text-slate-300',
                                transportOpen ? 'rotate-180' : '',
                                !sidebarOpen && !isMobile ? 'hidden' : '',
                            ]"
                            class="fa-chevron-down"
                        ></i>
                    </button>
                    <div
                        v-show="
                            (transportOpen && sidebarOpen) ||
                            (transportOpen && isMobile)
                        "
                        class="mt-1 space-y-1 pl-12 pr-2"
                    >
                        <Link
                            :href="route('admin.buses.index')"
                            :class="[
                                'flex items-center px-3 py-2.5 rounded-lg text-sm transition-all duration-200 border-l-2',
                                route().current('admin.buses.*')
                                    ? 'border-brand-red text-white bg-slate-800'
                                    : 'border-transparent text-slate-400 hover:text-white hover:bg-slate-800/50',
                            ]"
                        >
                            Armada Bus
                        </Link>
                        <Link
                            :href="route('admin.routes.index')"
                            :class="[
                                'flex items-center px-3 py-2.5 rounded-lg text-sm transition-all duration-200 border-l-2',
                                route().current('admin.routes.*')
                                    ? 'border-brand-red text-white bg-slate-800'
                                    : 'border-transparent text-slate-400 hover:text-white hover:bg-slate-800/50',
                            ]"
                        >
                            Rute Perjalanan
                        </Link>
                        <Link
                            :href="route('admin.schedules.index')"
                            :class="[
                                'flex items-center px-3 py-2.5 rounded-lg text-sm transition-all duration-200 border-l-2',
                                route().current('admin.schedules.*')
                                    ? 'border-brand-red text-white bg-slate-800'
                                    : 'border-transparent text-slate-400 hover:text-white hover:bg-slate-800/50',
                            ]"
                        >
                            Jadwal Keberangkatan
                        </Link>
                        <Link
                            :href="route('admin.schedule-management.index')"
                            :class="[
                                'flex items-center px-3 py-2.5 rounded-lg text-sm transition-all duration-200 border-l-2',
                                route().current('admin.schedule-management.*')
                                    ? 'border-brand-red text-white bg-slate-800'
                                    : 'border-transparent text-slate-400 hover:text-white hover:bg-slate-800/50',
                            ]"
                        >
                            Papan Jadwal
                        </Link>
                    </div>
                </div>

                <!-- Bookings -->
                <Link
                    :href="route('admin.bookings.index')"
                    :class="[
                        'flex items-center px-4 py-3.5 rounded-xl transition-all duration-200 group relative overflow-hidden',
                        route().current('admin.bookings.*')
                            ? 'bg-gradient-to-r from-brand-red to-red-700 text-white shadow-lg shadow-brand-red/20 translate-x-1'
                            : 'text-slate-400 hover:bg-slate-800 hover:text-white',
                    ]"
                >
                    <i
                        class="fas fa-ticket-alt text-lg w-6 text-center z-10"
                    ></i>
                    <span
                        :class="[
                            'ml-3 font-medium whitespace-nowrap transition-opacity duration-300 z-10',
                            !sidebarOpen && !isMobile
                                ? 'opacity-0 hidden'
                                : 'opacity-100',
                        ]"
                        >Pemesanan</span
                    >
                </Link>

                <div class="pt-4 pb-2" v-show="sidebarOpen || isMobile">
                    <p
                        class="px-4 text-[10px] font-bold text-slate-500 uppercase tracking-widest"
                    >
                        Pengguna & Sistem
                    </p>
                </div>
                <div
                    class="my-2 border-t border-slate-800"
                    v-show="!sidebarOpen && !isMobile"
                ></div>

                <!-- User Management -->
                <div>
                    <button
                        @click="usersOpen = !usersOpen"
                        :class="[
                            'w-full flex items-center justify-between px-4 py-3.5 rounded-xl transition-colors group',
                            usersOpen
                                ? 'bg-slate-800/50 text-white'
                                : 'text-slate-400 hover:bg-slate-800 hover:text-white',
                        ]"
                    >
                        <div class="flex items-center">
                            <i class="fas fa-users text-lg w-6 text-center"></i>
                            <span
                                :class="[
                                    'ml-3 font-medium whitespace-nowrap',
                                    !sidebarOpen && !isMobile ? 'hidden' : '',
                                ]"
                                >Pengguna & Staf</span
                            >
                        </div>
                        <i
                            :class="[
                                'fas text-xs transition-transform duration-300 text-slate-500 group-hover:text-slate-300',
                                usersOpen ? 'rotate-180' : '',
                                !sidebarOpen && !isMobile ? 'hidden' : '',
                            ]"
                            class="fa-chevron-down"
                        ></i>
                    </button>
                    <div
                        v-show="
                            (usersOpen && sidebarOpen) ||
                            (usersOpen && isMobile)
                        "
                        class="mt-1 space-y-1 pl-12 pr-2"
                    >
                        <Link
                            :href="route('admin.users.index')"
                            :class="[
                                'flex items-center px-3 py-2.5 rounded-lg text-sm transition-all duration-200 border-l-2',
                                route().current('admin.users.*')
                                    ? 'border-brand-red text-white bg-slate-800'
                                    : 'border-transparent text-slate-400 hover:text-white hover:bg-slate-800/50',
                            ]"
                        >
                            Pelanggan
                        </Link>
                        <Link
                            :href="route('admin.drivers.index')"
                            :class="[
                                'flex items-center px-3 py-2.5 rounded-lg text-sm transition-all duration-200 border-l-2',
                                route().current('admin.drivers.*')
                                    ? 'border-brand-red text-white bg-slate-800'
                                    : 'border-transparent text-slate-400 hover:text-white hover:bg-slate-800/50',
                            ]"
                        >
                            Sopir
                        </Link>
                        <Link
                            :href="route('admin.conductors.index')"
                            :class="[
                                'flex items-center px-3 py-2.5 rounded-lg text-sm transition-all duration-200 border-l-2',
                                route().current('admin.conductors.*')
                                    ? 'border-brand-red text-white bg-slate-800'
                                    : 'border-transparent text-slate-400 hover:text-white hover:bg-slate-800/50',
                            ]"
                        >
                            Kondektur
                        </Link>
                    </div>
                </div>

                <!-- Reports -->
                <Link
                    :href="route('admin.reports.index')"
                    :class="[
                        'flex items-center px-4 py-3.5 rounded-xl transition-all duration-200 group relative overflow-hidden',
                        route().current('admin.reports.*')
                            ? 'bg-gradient-to-r from-brand-red to-red-700 text-white shadow-lg shadow-brand-red/20 translate-x-1'
                            : 'text-slate-400 hover:bg-slate-800 hover:text-white',
                    ]"
                >
                    <i
                        class="fas fa-chart-line text-lg w-6 text-center z-10"
                    ></i>
                    <span
                        :class="[
                            'ml-3 font-medium whitespace-nowrap transition-all duration-300 z-10',
                            !sidebarOpen && !isMobile
                                ? 'opacity-0 hidden'
                                : 'opacity-100',
                        ]"
                        >Laporan</span
                    >
                </Link>

                <!-- Settings -->
                <Link
                    :href="route('admin.settings.index')"
                    :class="[
                        'flex items-center px-4 py-3.5 rounded-xl transition-all duration-200 group relative overflow-hidden',
                        route().current('admin.settings.*')
                            ? 'bg-gradient-to-r from-brand-red to-red-700 text-white shadow-lg shadow-brand-red/20 translate-x-1'
                            : 'text-slate-400 hover:bg-slate-800 hover:text-white',
                    ]"
                >
                    <i class="fas fa-cog text-lg w-6 text-center z-10"></i>
                    <span
                        :class="[
                            'ml-3 font-medium whitespace-nowrap transition-all duration-300 z-10',
                            !sidebarOpen && !isMobile
                                ? 'opacity-0 hidden'
                                : 'opacity-100',
                        ]"
                        >Pengaturan</span
                    >
                </Link>
            </nav>

            <!-- User Profile (Bottom) -->
            <div
                class="p-4 border-t border-slate-800 mt-auto"
                v-if="sidebarOpen"
            >
                <div
                    class="flex items-center gap-3 p-3 rounded-xl bg-slate-800/50"
                >
                    <div
                        class="h-10 w-10 rounded-full bg-gradient-to-br from-brand-red to-red-600 flex items-center justify-center text-white font-bold shadow-md"
                    >
                        {{ user.name.charAt(0).toUpperCase() }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-bold text-white truncate">
                            {{ user.name }}
                        </p>
                        <p class="text-[10px] text-slate-400 truncate">
                            {{ user.email }}
                        </p>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Mobile Overlay -->
        <div
            v-if="isMobile && sidebarOpen"
            class="fixed inset-0 bg-black/60 backdrop-blur-sm z-30 transition-opacity"
            @click="sidebarOpen = false"
        ></div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden relative">
            <!-- Topbar (Glassmorphism) -->
            <header
                class="sticky top-0 z-20 h-20 px-6 flex items-center justify-between bg-white/80 dark:bg-gray-950/80 backdrop-blur-md border-b border-gray-200 dark:border-gray-800/50"
            >
                <div class="flex items-center gap-4">
                    <button
                        @click="sidebarOpen = !sidebarOpen"
                        class="p-2 rounded-lg text-gray-500 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-800 focus:outline-none transition-colors"
                    >
                        <i
                            :class="
                                sidebarOpen ? 'fas fa-indent' : 'fas fa-outdent'
                            "
                            class="text-xl"
                        ></i>
                    </button>
                    <div>
                        <h1
                            class="text-xl font-bold text-gray-800 dark:text-white tracking-tight font-serif"
                        >
                            {{ title || "Dasbor" }}
                        </h1>
                        <p
                            class="text-xs text-gray-500 dark:text-gray-400 font-medium tracking-wide"
                        >
                            Selamat datang kembali,
                            {{ user.name.split(" ")[0] }}!
                        </p>
                    </div>
                </div>

                <!-- Right Actions -->
                <div class="flex items-center gap-4">
                    <!-- Notifications -->
                    <button
                        class="relative p-2 rounded-lg text-gray-500 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-800 transition-colors"
                    >
                        <i class="far fa-bell text-xl"></i>
                        <span
                            class="absolute top-2 right-2 h-2 w-2 rounded-full bg-red-500 ring-2 ring-white dark:ring-gray-900"
                        ></span>
                    </button>

                    <!-- View Website -->
                    <Link
                        :href="route('frontend.home')"
                        class="hidden md:flex items-center gap-2 px-4 py-2 rounded-full bg-gray-100 dark:bg-gray-900 text-gray-600 dark:text-gray-300 text-sm font-semibold hover:bg-brand-red hover:text-white hover:shadow-lg hover:shadow-brand-red/20 transition-all duration-300"
                    >
                        <i class="fas fa-globe"></i>
                        <span>Lihat Website</span>
                    </Link>

                    <!-- User Dropdown -->
                    <div class="relative">
                        <button
                            @click="userDropdownOpen = !userDropdownOpen"
                            class="flex items-center gap-2 focus:outline-none"
                        >
                            <div
                                class="h-10 w-10 rounded-full bg-gradient-to-br from-brand-red to-red-600 p-[2px] cursor-pointer hover:scale-105 transition-transform"
                            >
                                <div
                                    class="h-full w-full rounded-full bg-white dark:bg-gray-900 flex items-center justify-center"
                                >
                                    <span class="font-bold text-brand-red">{{
                                        user.name.charAt(0).toUpperCase()
                                    }}</span>
                                </div>
                            </div>
                        </button>

                        <!-- Dropdown Content -->
                        <div
                            v-if="userDropdownOpen"
                            class="absolute right-0 mt-3 w-56 bg-white dark:bg-gray-900 rounded-2xl shadow-xl shadow-gray-200/50 dark:shadow-black/50 border border-gray-100 dark:border-gray-800 py-2 z-50 transform origin-top-right transition-all animate-fade-in-up"
                        >
                            <div
                                class="px-4 py-3 border-b border-gray-100 dark:border-gray-800"
                            >
                                <p
                                    class="text-sm font-bold text-gray-900 dark:text-white"
                                >
                                    {{ user.name }}
                                </p>
                                <p
                                    class="text-xs text-gray-500 dark:text-gray-400 truncate"
                                >
                                    {{ user.email }}
                                </p>
                            </div>

                            <div class="p-2">
                                <Link
                                    :href="route('profile.edit')"
                                    class="flex items-center gap-3 px-3 py-2 text-sm text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors"
                                >
                                    <i
                                        class="fas fa-user-circle text-gray-400"
                                    ></i>
                                    Profil Saya
                                </Link>
                                <Link
                                    :href="route('frontend.home')"
                                    class="flex items-center gap-3 px-3 py-2 text-sm text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors"
                                >
                                    <i
                                        class="fas fa-external-link-alt text-gray-400"
                                    ></i>
                                    Ke Website
                                </Link>
                            </div>

                            <div
                                class="border-t border-gray-100 dark:border-gray-800 my-1"
                            ></div>

                            <div class="p-2">
                                <button
                                    @click="logout"
                                    class="w-full flex items-center gap-3 px-3 py-2 text-sm text-red-600 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors"
                                >
                                    <i class="fas fa-sign-out-alt"></i> Keluar
                                </button>
                            </div>
                        </div>

                        <!-- Click Outside -->
                        <div
                            v-if="userDropdownOpen"
                            class="fixed inset-0 z-40"
                            @click="userDropdownOpen = false"
                        ></div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main
                class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50/50 dark:bg-gray-950 p-6 md:p-8"
            >
                <!-- Flash Messages with Modern Alert Style -->
                <transition-group
                    enter-active-class="transition duration-400 ease-out"
                    enter-from-class="transform -translate-y-4 opacity-0 scale-95"
                    enter-to-class="transform translate-y-0 opacity-100 scale-100"
                    leave-active-class="transition duration-200 ease-in"
                    leave-from-class="transform translate-y-0 opacity-100 scale-100"
                    leave-to-class="transform -translate-y-4 opacity-0 scale-95"
                >
                    <div
                        v-if="$page.props.flash.success"
                        key="success"
                        class="mb-8 flex items-center w-full max-w-3xl mx-auto p-4 rounded-2xl bg-white dark:bg-gray-900 border border-green-100 dark:border-green-900/50 shadow-lg shadow-green-500/10"
                    >
                        <div
                            class="flex-shrink-0 h-10 w-10 rounded-full bg-green-100 dark:bg-green-900/40 flex items-center justify-center text-green-500"
                        >
                            <i class="fas fa-check"></i>
                        </div>
                        <div class="ml-4 flex-1">
                            <h3
                                class="text-sm font-bold text-gray-900 dark:text-white font-serif"
                            >
                                Berhasil
                            </h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                {{ $page.props.flash.success }}
                            </p>
                        </div>
                        <button
                            class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200"
                        >
                            <i class="fas fa-times"></i>
                        </button>
                    </div>

                    <div
                        v-if="$page.props.flash.error"
                        key="error"
                        class="mb-8 flex items-center w-full max-w-3xl mx-auto p-4 rounded-2xl bg-white dark:bg-gray-900 border border-red-100 dark:border-red-900/50 shadow-lg shadow-red-500/10"
                    >
                        <div
                            class="flex-shrink-0 h-10 w-10 rounded-full bg-red-100 dark:bg-red-900/40 flex items-center justify-center text-red-500"
                        >
                            <i class="fas fa-exclamation"></i>
                        </div>
                        <div class="ml-4 flex-1">
                            <h3
                                class="text-sm font-bold text-gray-900 dark:text-white font-serif"
                            >
                                Gagal
                            </h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                {{ $page.props.flash.error }}
                            </p>
                        </div>
                        <button
                            class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200"
                        >
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </transition-group>

                <slot />
            </main>
        </div>
    </div>
</template>

<style scoped>
/* Custom Scrollbar for sidebar */
.custom-scrollbar::-webkit-scrollbar {
    width: 6px;
}
.custom-scrollbar::-webkit-scrollbar-track {
    background: transparent;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
    background-color: rgba(255, 255, 255, 0.1);
    border-radius: 3px;
}
.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background-color: rgba(255, 255, 255, 0.2);
}
</style>
