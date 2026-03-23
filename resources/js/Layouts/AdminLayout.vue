<script setup>
import { ref, onMounted, onUnmounted, computed, watch } from "vue";
import { Link, usePage } from "@inertiajs/vue3";
import { router } from "@inertiajs/vue3";
import axios from "axios";

// Global Loading State
const isLoading = ref(false);

const props = defineProps({
    title: String,
});

const page = usePage();
const user = computed(() => page.props.auth.user);

// Sidebar State
const sidebarOpen = ref(window.innerWidth >= 1024);
const isMobile = ref(window.innerWidth < 1024);
const searchQuery = ref("");

// Dropdown States
const contentOpen = ref(false);
const transportOpen = ref(false);
const usersOpen = ref(false);
const userDropdownOpen = ref(false);
const notificationDropdownOpen = ref(false);

const markAllAsRead = async () => {
    try {
        await axios.post(
            route("admin.notifications.markAllRead"),
            {},
            {
                headers: { Accept: "application/json" },
            },
        );

        // Update local state directly to instantly show changes
        page.props.auth.unread_notifications_count = 0;
        if (page.props.auth.notifications) {
            page.props.auth.notifications.forEach((n) => {
                n.read_at = new Date().toISOString();
            });
        }

        addToast(
            "Semua notifikasi telah ditandai sebagai sudah dibaca.",
            "success",
        );
    } catch (error) {
        console.error("Gagal menandai semua notifikasi:", error);
    }
};

// Initialize router events for loading state
router.on('start', () => {
    isLoading.value = true;
});

router.on('finish', () => {
    setTimeout(() => {
        isLoading.value = false;
    }, 300); // Slight delay for smoother transition
});

// Initialize dropdowns based on current route
onMounted(() => {
    checkActiveRoutes();
    window.addEventListener("resize", handleResize);

    // Initialize Dark Mode
    if (
        localStorage.theme === "dark" ||
        (!("theme" in localStorage) &&
            window.matchMedia("(prefers-color-scheme: dark)").matches)
    ) {
        isDark.value = true;
        document.documentElement.classList.add("dark");
    } else {
        isDark.value = false;
        document.documentElement.classList.remove("dark");
    }
});

// Dark Mode State
const isDark = ref(false);

const toggleDarkMode = () => {
    isDark.value = !isDark.value;
    if (isDark.value) {
        document.documentElement.classList.add("dark");
        localStorage.setItem("theme", "dark");
    } else {
        document.documentElement.classList.remove("dark");
        localStorage.setItem("theme", "light");
    }
};

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
        route().current("admin.schedules.*")
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
};

const logout = () => {
    router.post(route("logout"));
};

// Toast Notification System
const toasts = ref([]);

const addToast = (message, type = "success") => {
    const id = Date.now();
    toasts.value.push({
        id,
        message,
        type,
    });

    // Auto dismiss
    setTimeout(() => {
        removeToast(id);
    }, 5000);
};

const removeToast = (id) => {
    toasts.value = toasts.value.filter((t) => t.id !== id);
};

// Flash Message Watchers
const flashSuccess = computed(() => page.props.flash.success);
const flashError = computed(() => page.props.flash.error);

watch(
    flashSuccess,
    (newValue) => {
        if (newValue) {
            addToast(newValue, "success");
        }
    },
    { immediate: true },
);

watch(
    flashError,
    (newValue) => {
        if (newValue) {
            addToast(newValue, "error");
        }
    },
    { immediate: true },
);
</script>

<template>
    <div
        class="flex min-h-screen bg-gray-50 dark:bg-gray-950 font-manrope text-gray-900 dark:text-gray-100"
    >
        <!-- Sidebar Overlay (Mobile) -->
        <div
            v-if="isMobile && sidebarOpen"
            @click="sidebarOpen = false"
            class="fixed inset-0 bg-black/50 backdrop-blur-sm z-30 transition-opacity duration-300"
        ></div>

        <!-- Sidebar -->
        <aside
            :class="[
                'bg-slate-50 dark:bg-[#050505] text-gray-800 dark:text-white transition-all duration-300 z-40 flex flex-col shadow-2xl border-r border-slate-200 dark:border-white/5',
                isMobile
                    ? sidebarOpen
                        ? 'fixed inset-y-0 left-0 w-72'
                        : 'fixed inset-y-0 left-0 w-0 overflow-hidden'
                    : sidebarOpen
                      ? 'w-72 relative'
                      : 'w-20 relative',
            ]"
        >
            <!-- Background Gradient Texture (Dark Mode Only) -->
            <div
                class="absolute inset-0 bg-gradient-to-br from-[#111] via-[#050505] to-[#0a0a0a] pointer-events-none opacity-0 dark:opacity-100 transition-opacity duration-300"
            ></div>
            <!-- Subtle Red Glow at bottom -->
            <div
                class="absolute bottom-0 left-0 w-full h-32 bg-gradient-to-t from-brand-red/5 dark:from-brand-red/10 to-transparent pointer-events-none opacity-50"
            ></div>

            <!-- Logo Section -->
            <div
                class="h-24 flex items-center justify-between px-6 border-b border-slate-200 dark:border-white/5 bg-slate-50/95 dark:bg-[#050505]/95 backdrop-blur-md relative z-10 transition-colors duration-300"
            >
                <div v-show="!isMobile && !sidebarOpen" class="mx-auto">
                    <img
                        src="/img/logoNoBg.png"
                        alt="Logo"
                        class="h-10 w-auto drop-shadow-[0_0_15px_rgba(220,38,38,0.5)] transition-transform hover:scale-110 duration-300"
                    />
                </div>
                <div
                    v-show="sidebarOpen"
                    class="flex items-center gap-3 transition-opacity duration-300 overflow-hidden whitespace-nowrap"
                >
                    <div class="relative group">
                        <div
                            class="absolute -inset-2 bg-brand-red/20 rounded-full blur-md opacity-0 group-hover:opacity-100 transition duration-500"
                        ></div>
                        <img
                            src="/img/logoNoBg.png"
                            alt="Logo"
                            class="relative h-11 w-auto drop-shadow-md"
                        />
                    </div>

                    <div class="flex flex-col">
                        <span
                            class="text-2xl font-black font-unbounded tracking-tighter italic bg-clip-text text-transparent bg-gradient-to-r from-brand-red via-red-500 to-orange-500 transform -skew-x-6 drop-shadow-sm"
                        >
                            TUJAGO
                        </span>
                        <span
                            class="text-[0.6rem] font-bold text-gray-500 dark:text-gray-400 tracking-[0.2em] uppercase ml-0.5"
                        >
                            Tunggal Jaya Go
                        </span>
                    </div>
                </div>
                <button
                    v-if="isMobile"
                    @click="sidebarOpen = false"
                    class="text-gray-400 hover:text-gray-600 dark:hover:text-white focus:outline-none transition-colors p-2 hover:bg-gray-100 dark:hover:bg-white/5 rounded-lg"
                >
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <!-- Search (Sidebar) -->
            <div class="px-4 py-4 relative z-10" v-show="sidebarOpen">
                <div class="relative group">
                    <input
                        type="text"
                        v-model="searchQuery"
                        placeholder="Cari menu..."
                        class="w-full bg-white dark:bg-[#1a1a1a] text-gray-800 dark:text-gray-300 text-sm rounded-xl pl-10 pr-4 py-2.5 focus:outline-none focus:ring-1 focus:ring-brand-red/50 border border-slate-200 dark:border-white/5 focus:border-brand-red/30 transition-all placeholder-gray-500 dark:placeholder-gray-600 shadow-sm dark:shadow-none"
                    />
                    <div
                        class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-500 dark:text-gray-600 group-focus-within:text-brand-red transition-colors"
                    >
                        <i class="fas fa-search"></i>
                    </div>
                </div>
            </div>

            <!-- Navigation -->
            <nav
                class="flex-1 overflow-y-auto px-4 space-y-1 custom-scrollbar pb-6 relative z-10"
            >
                <!-- Dashboard -->
                <Link
                    :href="route('admin.dashboard')"
                    :class="[
                        'flex items-center px-4 py-3.5 rounded-xl transition-all duration-300 group relative mb-2',
                        route().current('admin.dashboard')
                            ? 'bg-gradient-to-r from-brand-red to-red-800 text-white shadow-lg shadow-brand-red/25'
                            : 'text-gray-600 dark:text-gray-500 hover:bg-gray-100 dark:hover:bg-white/5 hover:text-brand-red dark:hover:text-white',
                    ]"
                >
                    <div
                        class="absolute left-0 w-1 h-8 bg-brand-red dark:bg-white rounded-r-full opacity-0 transition-all duration-300"
                        :class="
                            route().current('admin.dashboard')
                                ? 'opacity-100'
                                : ''
                        "
                    ></div>
                    <i
                        class="fas fa-chart-pie text-lg w-6 text-center z-10 transition-transform group-hover:scale-110 duration-300"
                        :class="
                            route().current('admin.dashboard')
                                ? 'text-white'
                                : 'text-gray-400 dark:text-gray-500 group-hover:text-brand-red dark:group-hover:text-white'
                        "
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

                    <!-- Tooltip for collapsed -->
                    <div
                        v-show="!sidebarOpen && !isMobile"
                        class="absolute left-full top-1/2 -translate-y-1/2 ml-2 bg-white dark:bg-[#1a1a1a] text-gray-800 dark:text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 pointer-events-none transition-opacity whitespace-nowrap z-50 shadow-xl border border-gray-200 dark:border-white/10"
                    >
                        Dasbor
                    </div>
                </Link>

                <!-- Section: Management -->
                <div class="pt-4 pb-2" v-show="sidebarOpen || isMobile">
                    <p
                        class="px-4 text-[10px] font-black font-unbounded text-gray-400 dark:text-gray-600 uppercase tracking-widest flex items-center gap-2"
                    >
                        <span
                            class="w-1.5 h-1.5 rounded-full bg-brand-red/50"
                        ></span>
                        Manajemen
                    </p>
                </div>
                <div
                    class="my-2 border-t border-slate-200 dark:border-white/5"
                    v-show="!sidebarOpen && !isMobile"
                ></div>

                <!-- Content Management -->
                <div>
                    <button
                        @click="contentOpen = !contentOpen"
                        :class="[
                            'w-full flex items-center justify-between px-4 py-3.5 rounded-xl transition-all duration-200 group',
                            contentOpen
                                ? 'bg-white dark:bg-white/5 text-gray-900 dark:text-white shadow-sm dark:shadow-none'
                                : 'text-gray-600 dark:text-gray-500 hover:bg-white dark:hover:bg-white/5 hover:text-brand-red dark:hover:text-white hover:shadow-sm dark:hover:shadow-none',
                        ]"
                    >
                        <div class="flex items-center">
                            <i
                                class="fas fa-layer-group text-lg w-6 text-center transition-transform group-hover:scale-110 duration-300"
                                :class="
                                    contentOpen
                                        ? 'text-brand-red'
                                        : 'text-gray-400 dark:text-gray-500 group-hover:text-brand-red dark:group-hover:text-white'
                                "
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
                                'fas text-xs transition-transform duration-300 text-gray-400 dark:text-gray-600 group-hover:text-gray-600 dark:group-hover:text-gray-400',
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
                        class="mt-1 space-y-1 pl-4 pr-2"
                    >
                        <div
                            class="border-l border-slate-200 dark:border-white/10 pl-8 space-y-1 py-1"
                        >
                            <Link
                                :href="route('admin.news.index')"
                                :class="[
                                    'block py-2 text-sm transition-all duration-200 hover:translate-x-1',
                                    route().current('admin.news.*')
                                        ? 'text-brand-red dark:text-white font-bold'
                                        : 'text-gray-500 dark:text-gray-500 hover:text-brand-red dark:hover:text-white',
                                ]"
                            >
                                <span class="flex items-center gap-2">
                                    <span
                                        class="w-1.5 h-1.5 rounded-full"
                                        :class="
                                            route().current('admin.news.*')
                                                ? 'bg-brand-red'
                                                : 'bg-slate-300 dark:bg-gray-700'
                                        "
                                    ></span>
                                    Berita & Artikel
                                </span>
                            </Link>
                            <Link
                                :href="route('admin.categories.index')"
                                :class="[
                                    'block py-2 text-sm transition-all duration-200 hover:translate-x-1',
                                    route().current('admin.categories.*')
                                        ? 'text-brand-red dark:text-white font-bold'
                                        : 'text-gray-500 dark:text-gray-500 hover:text-brand-red dark:hover:text-white',
                                ]"
                            >
                                <span class="flex items-center gap-2">
                                    <span
                                        class="w-1.5 h-1.5 rounded-full"
                                        :class="
                                            route().current(
                                                'admin.categories.*',
                                            )
                                                ? 'bg-brand-red'
                                                : 'bg-slate-300 dark:bg-gray-700'
                                        "
                                    ></span>
                                    Kategori
                                </span>
                            </Link>
                        </div>
                    </div>
                </div>

                <!-- Transport Management -->
                <div>
                    <button
                        @click="transportOpen = !transportOpen"
                        :class="[
                            'w-full flex items-center justify-between px-4 py-3.5 rounded-xl transition-all duration-200 group',
                            transportOpen
                                ? 'bg-white dark:bg-white/5 text-gray-900 dark:text-white shadow-sm dark:shadow-none'
                                : 'text-gray-600 dark:text-gray-500 hover:bg-white dark:hover:bg-white/5 hover:text-brand-red dark:hover:text-white hover:shadow-sm dark:hover:shadow-none',
                        ]"
                    >
                        <div class="flex items-center">
                            <i
                                class="fas fa-bus-alt text-lg w-6 text-center transition-transform group-hover:scale-110 duration-300"
                                :class="
                                    transportOpen
                                        ? 'text-amber-500'
                                        : 'text-gray-400 dark:text-gray-500 group-hover:text-brand-red dark:group-hover:text-white'
                                "
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
                                'fas text-xs transition-transform duration-300 text-gray-400 dark:text-gray-600 group-hover:text-gray-600 dark:group-hover:text-gray-400',
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
                        class="mt-1 space-y-1 pl-4 pr-2"
                    >
                        <div
                            class="border-l border-slate-200 dark:border-white/10 pl-8 space-y-1 py-1"
                        >
                            <Link
                                :href="route('admin.buses.index')"
                                :class="[
                                    'block py-2 text-sm transition-all duration-200 hover:translate-x-1',
                                    route().current('admin.buses.*')
                                        ? 'text-brand-red dark:text-white font-bold'
                                        : 'text-gray-500 dark:text-gray-500 hover:text-brand-red dark:hover:text-white',
                                ]"
                            >
                                <span class="flex items-center gap-2">
                                    <span
                                        class="w-1.5 h-1.5 rounded-full"
                                        :class="
                                            route().current('admin.buses.*')
                                                ? 'bg-brand-red'
                                                : 'bg-slate-300 dark:bg-gray-700'
                                        "
                                    ></span>
                                    Armada Bus
                                </span>
                            </Link>
                            <Link
                                :href="route('admin.routes.index')"
                                :class="[
                                    'block py-2 text-sm transition-all duration-200 hover:translate-x-1',
                                    route().current('admin.routes.*')
                                        ? 'text-brand-red dark:text-white font-bold'
                                        : 'text-gray-500 dark:text-gray-500 hover:text-brand-red dark:hover:text-white',
                                ]"
                            >
                                <span class="flex items-center gap-2">
                                    <span
                                        class="w-1.5 h-1.5 rounded-full"
                                        :class="
                                            route().current('admin.routes.*')
                                                ? 'bg-brand-red'
                                                : 'bg-slate-300 dark:bg-gray-700'
                                        "
                                    ></span>
                                    Rute Perjalanan
                                </span>
                            </Link>
                            <Link
                                :href="route('admin.schedules.index')"
                                :class="[
                                    'block py-2 text-sm transition-all duration-200 hover:translate-x-1',
                                    route().current('admin.schedules.*')
                                        ? 'text-brand-red dark:text-white font-bold'
                                        : 'text-gray-500 dark:text-gray-500 hover:text-brand-red dark:hover:text-white',
                                ]"
                            >
                                <span class="flex items-center gap-2">
                                    <span
                                        class="w-1.5 h-1.5 rounded-full"
                                        :class="
                                            route().current('admin.schedules.*')
                                                ? 'bg-brand-red'
                                                : 'bg-slate-300 dark:bg-gray-700'
                                        "
                                    ></span>
                                    Jadwal
                                </span>
                            </Link>
                        </div>
                    </div>
                </div>

                <!-- Promo & Discounts -->
                <Link
                    :href="route('admin.promo-codes.index')"
                    :class="[
                        'flex items-center px-4 py-3.5 rounded-xl transition-all duration-300 group relative mb-1',
                        route().current('admin.promo-codes.*')
                            ? 'bg-gradient-to-r from-brand-red to-red-800 text-white shadow-lg shadow-brand-red/25'
                            : 'text-gray-600 dark:text-gray-500 hover:bg-gray-100 dark:hover:bg-white/5 hover:text-brand-red dark:hover:text-white',
                    ]"
                >
                    <div
                        class="absolute left-0 w-1 h-8 bg-brand-red dark:bg-white rounded-r-full opacity-0 transition-all duration-300"
                        :class="
                            route().current('admin.promo-codes.*')
                                ? 'opacity-100'
                                : ''
                        "
                    ></div>
                    <i
                        class="fas fa-tags text-lg w-6 text-center z-10 transition-transform group-hover:scale-110 duration-300"
                        :class="
                            route().current('admin.promo-codes.*')
                                ? 'text-white'
                                : 'text-gray-400 dark:text-gray-500 group-hover:text-brand-red dark:group-hover:text-white'
                        "
                    ></i>
                    <span
                        :class="[
                            'ml-3 font-medium whitespace-nowrap transition-all duration-300 z-10',
                            !sidebarOpen && !isMobile
                                ? 'opacity-0 hidden'
                                : 'opacity-100',
                        ]"
                        >Promo & Diskon</span
                    >

                    <!-- Tooltip for collapsed -->
                    <div
                        v-show="!sidebarOpen && !isMobile"
                        class="absolute left-full top-1/2 -translate-y-1/2 ml-2 bg-white dark:bg-[#1a1a1a] text-gray-800 dark:text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 pointer-events-none transition-opacity whitespace-nowrap z-50 shadow-xl border border-gray-200 dark:border-white/10"
                    >
                        Promo & Diskon
                    </div>
                </Link>

                <!-- Bookings -->
                <Link
                    :href="route('admin.bookings.index')"
                    :class="[
                        'flex items-center px-4 py-3.5 rounded-xl transition-all duration-200 group relative my-1',
                        route().current('admin.bookings.*')
                            ? 'bg-gradient-to-r from-brand-red to-red-800 text-white shadow-lg shadow-brand-red/25'
                            : 'text-gray-600 dark:text-gray-500 hover:bg-white dark:hover:bg-white/5 hover:text-brand-red dark:hover:text-white hover:shadow-sm dark:hover:shadow-none',
                    ]"
                >
                    <i
                        class="fas fa-ticket-alt text-lg w-6 text-center z-10 transition-transform group-hover:scale-110 duration-300"
                        :class="
                            route().current('admin.bookings.*')
                                ? 'text-white'
                                : 'text-gray-400 dark:text-gray-500 group-hover:text-brand-red dark:group-hover:text-white'
                        "
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
                    <!-- Notification Badge -->
                    <span
                        v-if="sidebarOpen"
                        class="ml-auto bg-brand-red text-white text-[10px] font-bold px-2 py-0.5 rounded shadow-lg shadow-brand-red/40"
                        >Baru</span
                    >
                </Link>

                <!-- Section: Users & System -->
                <div class="pt-4 pb-2" v-show="sidebarOpen || isMobile">
                    <p
                        class="px-4 text-[10px] font-black font-unbounded text-gray-400 dark:text-gray-600 uppercase tracking-widest flex items-center gap-2"
                    >
                        <span
                            class="w-1.5 h-1.5 rounded-full bg-brand-red/50"
                        ></span>
                        Pengguna & Sistem
                    </p>
                </div>
                <div
                    class="my-2 border-t border-slate-200 dark:border-white/5"
                    v-show="!sidebarOpen && !isMobile"
                ></div>

                <!-- User Management -->
                <div>
                    <button
                        @click="usersOpen = !usersOpen"
                        :class="[
                            'w-full flex items-center justify-between px-4 py-3.5 rounded-xl transition-all duration-200 group',
                            usersOpen
                                ? 'bg-white dark:bg-white/5 text-gray-900 dark:text-white shadow-sm dark:shadow-none'
                                : 'text-gray-600 dark:text-gray-500 hover:bg-white dark:hover:bg-white/5 hover:text-brand-red dark:hover:text-white hover:shadow-sm dark:hover:shadow-none',
                        ]"
                    >
                        <div class="flex items-center">
                            <i
                                class="fas fa-users text-lg w-6 text-center transition-transform group-hover:scale-110 duration-300"
                                :class="
                                    usersOpen
                                        ? 'text-purple-400'
                                        : 'text-gray-400 dark:text-gray-500 group-hover:text-brand-red dark:group-hover:text-white'
                                "
                            ></i>
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
                                'fas text-xs transition-transform duration-300 text-gray-400 dark:text-gray-600 group-hover:text-gray-600 dark:group-hover:text-gray-400',
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
                        class="mt-1 space-y-1 pl-4 pr-2"
                    >
                        <div
                            class="border-l border-slate-200 dark:border-white/10 pl-8 space-y-1 py-1"
                        >
                            <Link
                                :href="route('admin.users.index')"
                                :class="[
                                    'block py-2 text-sm transition-all duration-200 hover:translate-x-1',
                                    route().current('admin.users.*')
                                        ? 'text-brand-red dark:text-white font-bold'
                                        : 'text-gray-500 dark:text-gray-500 hover:text-brand-red dark:hover:text-white',
                                ]"
                            >
                                <span class="flex items-center gap-2">
                                    <span
                                        class="w-1.5 h-1.5 rounded-full"
                                        :class="
                                            route().current('admin.users.*')
                                                ? 'bg-brand-red'
                                                : 'bg-slate-300 dark:bg-gray-700'
                                        "
                                    ></span>
                                    Admin & Staff
                                </span>
                            </Link>
                            <Link
                                :href="route('admin.customers.index')"
                                :class="[
                                    'block py-2 text-sm transition-all duration-200 hover:translate-x-1',
                                    route().current('admin.customers.*')
                                        ? 'text-brand-red dark:text-white font-bold'
                                        : 'text-gray-500 dark:text-gray-500 hover:text-brand-red dark:hover:text-white',
                                ]"
                            >
                                <span class="flex items-center gap-2">
                                    <span
                                        class="w-1.5 h-1.5 rounded-full"
                                        :class="
                                            route().current('admin.customers.*')
                                                ? 'bg-brand-red'
                                                : 'bg-slate-300 dark:bg-gray-700'
                                        "
                                    ></span>
                                    Pelanggan
                                </span>
                            </Link>
                            <Link
                                :href="route('admin.drivers.index')"
                                :class="[
                                    'block py-2 text-sm transition-all duration-200 hover:translate-x-1',
                                    route().current('admin.drivers.*')
                                        ? 'text-brand-red dark:text-white font-bold'
                                        : 'text-gray-500 dark:text-gray-500 hover:text-brand-red dark:hover:text-white',
                                ]"
                            >
                                <span class="flex items-center gap-2">
                                    <span
                                        class="w-1.5 h-1.5 rounded-full"
                                        :class="
                                            route().current('admin.drivers.*')
                                                ? 'bg-brand-red'
                                                : 'bg-slate-300 dark:bg-gray-700'
                                        "
                                    ></span>
                                    Sopir
                                </span>
                            </Link>
                            <Link
                                :href="route('admin.conductors.index')"
                                :class="[
                                    'block py-2 text-sm transition-all duration-200 hover:translate-x-1',
                                    route().current('admin.conductors.*')
                                        ? 'text-brand-red dark:text-white font-bold'
                                        : 'text-gray-500 dark:text-gray-500 hover:text-brand-red dark:hover:text-white',
                                ]"
                            >
                                <span class="flex items-center gap-2">
                                    <span
                                        class="w-1.5 h-1.5 rounded-full"
                                        :class="
                                            route().current(
                                                'admin.conductors.*',
                                            )
                                                ? 'bg-brand-red'
                                                : 'bg-slate-300 dark:bg-gray-700'
                                        "
                                    ></span>
                                    Kondektur
                                </span>
                            </Link>
                        </div>
                    </div>
                </div>

                <!-- Reports -->
                <Link
                    :href="route('admin.reports.index')"
                    :class="[
                        'flex items-center px-4 py-3.5 rounded-xl transition-all duration-200 group relative my-1',
                        route().current('admin.reports.*')
                            ? 'bg-gradient-to-r from-brand-red to-red-800 text-white shadow-lg shadow-brand-red/25'
                            : 'text-gray-600 dark:text-gray-500 hover:bg-white dark:hover:bg-white/5 hover:text-brand-red dark:hover:text-white hover:shadow-sm dark:hover:shadow-none',
                    ]"
                >
                    <i
                        class="fas fa-chart-line text-lg w-6 text-center z-10 transition-transform group-hover:scale-110 duration-300"
                        :class="
                            route().current('admin.reports.*')
                                ? 'text-white'
                                : 'text-gray-400 dark:text-gray-500 group-hover:text-brand-red dark:group-hover:text-white'
                        "
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
                        'flex items-center px-4 py-3.5 rounded-xl transition-all duration-200 group relative my-1',
                        route().current('admin.settings.*')
                            ? 'bg-gradient-to-r from-brand-red to-red-800 text-white shadow-lg shadow-brand-red/25'
                            : 'text-gray-600 dark:text-gray-500 hover:bg-white dark:hover:bg-white/5 hover:text-brand-red dark:hover:text-white hover:shadow-sm dark:hover:shadow-none',
                    ]"
                >
                    <i
                        class="fas fa-cog text-lg w-6 text-center z-10 transition-transform group-hover:scale-110 duration-300"
                        :class="
                            route().current('admin.settings.*')
                                ? 'text-white'
                                : 'text-gray-400 dark:text-gray-500 group-hover:text-brand-red dark:group-hover:text-white'
                        "
                    ></i>
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

            <!-- User Profile (Bottom) with advanced blur -->
            <div
                class="p-4 border-t border-slate-200 dark:border-white/5 mt-auto relative z-20"
                v-if="sidebarOpen"
            >
                <div
                    class="flex items-center gap-3 p-3 rounded-2xl bg-white dark:bg-white/5 border border-slate-200 dark:border-white/5 hover:bg-slate-50 dark:hover:bg-white/10 transition-colors cursor-pointer group shadow-sm dark:shadow-none"
                >
                    <div class="relative">
                        <div
                            class="h-10 w-10 rounded-full bg-gradient-to-br from-brand-red to-red-800 flex items-center justify-center text-white font-bold shadow-lg shadow-brand-red/20 ring-2 ring-[#0f172a] group-hover:ring-brand-red transition-all"
                        >
                            {{ user.name.charAt(0).toUpperCase() }}
                        </div>
                        <div
                            class="absolute bottom-0 right-0 h-3 w-3 rounded-full bg-green-500 border-2 border-[#050505]"
                        ></div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p
                            class="text-sm font-black font-unbounded text-gray-900 dark:text-white truncate group-hover:text-brand-red transition-colors"
                        >
                            {{ user.name }}
                        </p>
                        <p
                            class="text-[10px] font-bold font-manrope text-gray-500 dark:text-gray-400 truncate"
                        >
                            Administrator
                        </p>
                    </div>
                    <button
                        class="text-gray-500 hover:text-brand-red dark:hover:text-white transition-colors"
                    >
                        <i class="fas fa-sign-out-alt"></i>
                    </button>
                </div>
            </div>
        </aside>

        <!-- Mobile Overlay -->
        <div
            v-if="isMobile && sidebarOpen"
            class="fixed inset-0 bg-[#000]/80 backdrop-blur-md z-30 transition-opacity"
            @click="sidebarOpen = false"
        ></div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden relative">
            <!-- Topbar (Glassmorphism) -->
            <header
                class="sticky top-0 z-20 h-20 px-6 flex items-center justify-between bg-white/80 dark:bg-gray-950/80 backdrop-blur-md border-b border-gray-200 dark:border-gray-800/50 transition-all duration-300"
            >
                <div class="flex items-center gap-4">
                    <button
                        @click="sidebarOpen = !sidebarOpen"
                        class="p-2 rounded-xl text-gray-500 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-800 focus:outline-none transition-colors"
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
                            class="text-xl font-black text-gray-800 dark:text-white tracking-tight font-unbounded"
                        >
                            {{ title || "Dasbor" }}
                        </h1>
                        <p
                            class="text-xs text-gray-500 dark:text-gray-400 font-medium tracking-wide"
                        >
                            <span
                                class="inline-block w-2 h-2 rounded-full bg-green-500 mr-1 animate-pulse"
                            ></span>
                            Sistem Siap
                        </p>
                    </div>
                </div>

                <!-- Right Actions -->
                <div class="flex items-center gap-4">
                    <!-- Dark Mode Toggle -->
                    <button
                        @click="toggleDarkMode"
                        class="p-2.5 rounded-xl text-gray-500 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-800 transition-colors"
                    >
                        <i
                            :class="
                                isDark
                                    ? 'fas fa-sun text-amber-500'
                                    : 'fas fa-moon'
                            "
                            class="text-xl"
                        ></i>
                    </button>

                    <!-- Notifications -->
                    <div class="relative">
                        <button
                            @click="
                                notificationDropdownOpen =
                                    !notificationDropdownOpen
                            "
                            class="relative p-2.5 rounded-xl text-gray-500 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-800 transition-colors group"
                        >
                            <i
                                class="far fa-bell text-xl group-hover:animate-swing"
                            ></i>
                            <span
                                v-if="
                                    $page.props.auth
                                        .unread_notifications_count > 0
                                "
                                class="absolute top-2 right-2 h-4 w-4 rounded-full bg-red-500 text-[10px] text-white flex items-center justify-center ring-2 ring-white dark:ring-gray-950 animate-bounce font-bold"
                            >
                                {{
                                    $page.props.auth.unread_notifications_count
                                }}
                            </span>
                        </button>

                        <!-- Notification Dropdown -->
                        <div
                            v-if="notificationDropdownOpen"
                            class="fixed inset-x-4 top-20 sm:absolute sm:right-0 sm:top-auto sm:inset-x-auto sm:mt-4 w-auto sm:w-96 bg-white dark:bg-gray-900 rounded-2xl sm:rounded-3xl shadow-2xl shadow-gray-200/50 dark:shadow-black/50 border border-gray-100 dark:border-gray-800 py-2 z-50 transform origin-top sm:origin-top-right transition-all animate-fade-in-up"
                        >
                            <div
                                class="px-4 py-3 border-b border-gray-100 dark:border-gray-800 flex justify-between items-center"
                            >
                                <h3
                                    class="font-black font-unbounded text-gray-900 dark:text-white"
                                >
                                    Notifikasi
                                </h3>
                                <button
                                    v-if="
                                        $page.props.auth
                                            .unread_notifications_count > 0
                                    "
                                    @click="markAllAsRead"
                                    class="text-xs text-brand-red hover:text-red-700 font-medium"
                                >
                                    Tandai semua dibaca
                                </button>
                            </div>

                            <div
                                class="max-h-80 overflow-y-auto custom-scrollbar"
                            >
                                <template
                                    v-if="
                                        $page.props.auth.notifications &&
                                        $page.props.auth.notifications.length >
                                            0
                                    "
                                >
                                    <div
                                        v-for="notification in $page.props.auth
                                            .notifications"
                                        :key="notification.id"
                                        class="p-4 border-b border-gray-50 dark:border-gray-800/50 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors relative"
                                        :class="
                                            !notification.read_at
                                                ? 'bg-red-50/50 dark:bg-red-900/10'
                                                : ''
                                        "
                                    >
                                        <div class="flex gap-3">
                                            <div class="flex-shrink-0 mt-1">
                                                <div
                                                    class="w-8 h-8 rounded-full bg-brand-red/10 text-brand-red flex items-center justify-center"
                                                >
                                                    <i
                                                        class="fas fa-ticket-alt text-xs"
                                                    ></i>
                                                </div>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p
                                                    class="text-sm font-black font-unbounded text-gray-900 dark:text-white truncate"
                                                >
                                                    {{
                                                        notification.data
                                                            .message
                                                    }}
                                                </p>
                                                <p
                                                    class="text-xs text-gray-500 mt-0.5 font-manrope font-bold"
                                                >
                                                    {{
                                                        notification.data.route
                                                    }}
                                                </p>
                                                <p
                                                    class="text-[10px] text-gray-400 mt-1"
                                                >
                                                    {{
                                                        new Date(
                                                            notification.created_at,
                                                        ).toLocaleString(
                                                            "id-ID",
                                                            {
                                                                day: "numeric",
                                                                month: "short",
                                                                hour: "2-digit",
                                                                minute: "2-digit",
                                                            },
                                                        )
                                                    }}
                                                </p>
                                            </div>
                                            <div
                                                v-if="!notification.read_at"
                                                class="flex-shrink-0"
                                            >
                                                <span
                                                    class="w-2 h-2 bg-brand-red rounded-full block mt-2"
                                                ></span>
                                            </div>
                                        </div>
                                        <Link
                                            v-if="
                                                notification.data &&
                                                notification.data.booking_id
                                            "
                                            :href="
                                                route(
                                                    'admin.notifications.markAsRead',
                                                    notification.id,
                                                )
                                            "
                                            method="post"
                                            :data="{
                                                redirect_to: route(
                                                    'admin.bookings.show',
                                                    notification.data.booking_id.toString(),
                                                ),
                                            }"
                                            class="absolute inset-0 z-10"
                                        ></Link>
                                    </div>
                                </template>
                                <div
                                    v-else
                                    class="p-8 text-center text-gray-500"
                                >
                                    <i
                                        class="far fa-bell-slash text-2xl mb-2 opacity-50"
                                    ></i>
                                    <p class="text-xs">
                                        Tidak ada notifikasi baru
                                    </p>
                                </div>
                            </div>

                            <div
                                class="p-2 border-t border-gray-100 dark:border-gray-800 text-center"
                            >
                                <Link
                                    :href="route('admin.notifications.index')"
                                    class="text-xs text-gray-500 hover:text-brand-red font-medium transition-colors"
                                >
                                    Lihat Semua Notifikasi
                                </Link>
                            </div>
                        </div>

                        <!-- Click Outside -->
                        <div
                            v-if="notificationDropdownOpen"
                            class="fixed inset-0 z-40 bg-black/20 backdrop-blur-sm sm:bg-transparent sm:backdrop-blur-none"
                            @click="notificationDropdownOpen = false"
                        ></div>
                    </div>

                    <!-- View Website -->
                    <Link
                        :href="route('frontend.home')"
                        class="hidden md:flex items-center gap-2 px-5 py-2.5 rounded-full bg-gray-100 dark:bg-gray-900 text-gray-600 dark:text-gray-300 text-sm font-semibold hover:bg-brand-red hover:text-white hover:shadow-lg hover:shadow-brand-red/30 transition-all duration-300"
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
                                class="h-11 w-11 rounded-full bg-gradient-to-br from-brand-red to-red-600 p-[2px] cursor-pointer hover:scale-105 transition-transform shadow-md"
                            >
                                <div
                                    class="h-full w-full rounded-full bg-white dark:bg-gray-900 flex items-center justify-center"
                                >
                                    <span class="font-bold text-brand-red/80">{{
                                        user.name.charAt(0).toUpperCase()
                                    }}</span>
                                </div>
                            </div>
                        </button>

                        <!-- Dropdown Content -->
                        <div
                            v-if="userDropdownOpen"
                            class="absolute right-0 mt-4 w-60 bg-white dark:bg-gray-900 rounded-3xl shadow-2xl shadow-gray-200/50 dark:shadow-black/50 border border-gray-100 dark:border-gray-800 py-3 z-50 transform origin-top-right transition-all animate-fade-in-up"
                        >
                            <div
                                class="absolute -top-2 right-4 w-4 h-4 bg-white dark:bg-gray-900 rotate-45 border-l border-t border-gray-100 dark:border-gray-800"
                            ></div>
                            <div
                                class="px-5 py-3 border-b border-gray-100 dark:border-gray-800 relative z-10"
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

                            <div class="p-2 relative z-10">
                                <Link
                                    :href="route('profile.edit')"
                                    class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors"
                                >
                                    <i
                                        class="fas fa-user-circle text-gray-400 w-5"
                                    ></i>
                                    Profil Saya
                                </Link>
                                <Link
                                    :href="route('frontend.home')"
                                    class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors"
                                >
                                    <i
                                        class="fas fa-external-link-alt text-gray-400 w-5"
                                    ></i>
                                    Ke Website
                                </Link>
                            </div>

                            <div
                                class="border-t border-gray-100 dark:border-gray-800 my-1 relative z-10"
                            ></div>

                            <div class="p-2 relative z-10">
                                <button
                                    @click="logout"
                                    class="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-red-600 rounded-xl hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors font-medium"
                                >
                                    <i class="fas fa-sign-out-alt w-5"></i>
                                    Keluar
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
                <!-- Global Toast Notifications Stack -->
                <div
                    class="fixed top-24 right-6 z-50 flex flex-col gap-3 pointer-events-none w-full max-w-sm"
                >
                    <TransitionGroup
                        enter-active-class="transition ease-out duration-300 transform"
                        enter-from-class="translate-x-full opacity-0"
                        enter-to-class="translate-x-0 opacity-100"
                        leave-active-class="transition ease-in duration-200 transform"
                        leave-from-class="translate-x-0 opacity-100"
                        leave-to-class="translate-x-full opacity-0"
                    >
                        <div
                            v-for="toast in toasts"
                            :key="toast.id"
                            class="pointer-events-auto w-full backdrop-blur-xl rounded-2xl p-4 shadow-2xl border border-white/20 relative overflow-hidden group hover:-translate-x-1 transition-transform cursor-pointer"
                            :class="[
                                toast.type === 'success'
                                    ? 'bg-white/90 dark:bg-gray-900/90 border-green-500/20'
                                    : 'bg-white/90 dark:bg-gray-900/90 border-red-500/20',
                            ]"
                            @click="removeToast(toast.id)"
                        >
                            <!-- Side Indicator Line -->
                            <div
                                class="absolute left-0 top-0 bottom-0 w-1"
                                :class="
                                    toast.type === 'success'
                                        ? 'bg-gradient-to-b from-green-400 to-green-600'
                                        : 'bg-gradient-to-b from-red-400 to-red-600'
                                "
                            ></div>

                            <div class="flex items-start gap-4 pl-2">
                                <div class="flex-shrink-0 mt-0.5">
                                    <div
                                        class="w-8 h-8 rounded-full flex items-center justify-center shadow-lg"
                                        :class="
                                            toast.type === 'success'
                                                ? 'bg-green-100 dark:bg-green-900/50 text-green-600'
                                                : 'bg-red-100 dark:bg-red-900/50 text-red-600'
                                        "
                                    >
                                        <i
                                            :class="
                                                toast.type === 'success'
                                                    ? 'fas fa-check'
                                                    : 'fas fa-exclamation'
                                            "
                                        ></i>
                                    </div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h4
                                        class="text-sm font-bold truncate"
                                        :class="
                                            toast.type === 'success'
                                                ? 'text-green-700 dark:text-green-400'
                                                : 'text-red-700 dark:text-red-400'
                                        "
                                    >
                                        {{
                                            toast.type === "success"
                                                ? "Berhasil!"
                                                : "Terjadi Kesalahan"
                                        }}
                                    </h4>
                                    <p
                                        class="text-xs text-gray-600 dark:text-gray-300 mt-1 leading-relaxed font-medium"
                                    >
                                        {{ toast.message }}
                                    </p>
                                </div>
                                <button
                                    class="text-gray-400 hover:text-gray-600 dark:hover:text-white transition-colors"
                                >
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>

                            <!-- Shimmer Effect -->
                            <div
                                class="absolute inset-0 -translate-x-full group-hover:animate-shimmer bg-gradient-to-r from-transparent via-white/10 to-transparent z-10"
                            ></div>
                        </div>
                    </TransitionGroup>
                </div>
                
                <!-- Skeleton Loading State -->
                <div v-if="isLoading" class="w-full space-y-6 animate-pulse">
                    <!-- Page Header Skeleton -->
                    <div class="flex flex-col md:flex-row justify-between gap-4 mb-6">
                        <div class="bg-gray-200 dark:bg-white/5 rounded-xl h-10 w-48"></div>
                        <div class="bg-gray-200 dark:bg-white/5 rounded-xl h-10 w-32"></div>
                    </div>
                    
                    <!-- Metrics Row Skeleton -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                        <div v-for="i in 4" :key="i" class="bg-gray-200 dark:bg-white/5 rounded-2xl h-28 w-full shadow-sm"></div>
                    </div>
                    
                    <!-- Main Content Skeleton -->
                    <div class="bg-gray-200 dark:bg-white/5 rounded-3xl h-[400px] w-full shadow-sm"></div>
                </div>

                <!-- Actual Page Content -->
                <div v-show="!isLoading">
                    <slot />
                </div>
            </main>
        </div>
    </div>
</template>

<style scoped>
/* Custom Scrollbar for sidebar */
.custom-scrollbar::-webkit-scrollbar {
    width: 4px;
}
.custom-scrollbar::-webkit-scrollbar-track {
    background: transparent;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
    background-color: rgba(255, 255, 255, 0.1);
    border-radius: 4px;
}
.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background-color: rgba(255, 255, 255, 0.2);
}

@keyframes swing {
    20% {
        transform: rotate(15deg);
    }
    40% {
        transform: rotate(-10deg);
    }
    60% {
        transform: rotate(5deg);
    }
    80% {
        transform: rotate(-5deg);
    }
    100% {
        transform: rotate(0deg);
    }
}

.animate-swing {
    animation: swing 1s ease-in-out;
}
</style>
