<script setup>
import { ref, onMounted, watch } from "vue";
import { Link, usePage } from "@inertiajs/vue3";
import FlashMessages from "@/Components/FlashMessages.vue";

const page = usePage();
const isScrolled = ref(false);
const mobileMenuOpen = ref(false);
const isDarkMode = ref(false);

const navLinks = [
    { name: "Beranda", href: "frontend.home" },
    { name: "Pesan Tiket", href: "frontend.booking.index" },
    { name: "Rute", href: "frontend.routes.index" },
    { name: "Armada", href: "frontend.fleet.index" },
    { name: "Berita", href: "frontend.news.index" },
    { name: "Tentang Kami", href: "frontend.about" },
    { name: "Kontak", href: "frontend.contact" },
];

onMounted(() => {
    window.addEventListener("scroll", handleScroll);
    isDarkMode.value = localStorage.getItem("darkMode") === "true";
    if (isDarkMode.value) {
        document.documentElement.classList.add("dark");
    }
});

const handleScroll = () => {
    isScrolled.value = window.scrollY > 20;
};

const toggleDarkMode = () => {
    isDarkMode.value = !isDarkMode.value;
    localStorage.setItem("darkMode", isDarkMode.value);
    document.documentElement.classList.toggle("dark");
};

const toggleMobileMenu = () => {
    mobileMenuOpen.value = !mobileMenuOpen.value;
};

const isActive = (routeName) => {
    return route().current(routeName);
};
</script>

<template>
    <div
        class="min-h-screen flex flex-col bg-gray-50 dark:bg-gray-900 transition-colors duration-300 font-sans"
    >
        <!-- Flash Messages -->
        <FlashMessages />

        <!-- Header (Floating Pill) -->
        <header
            class="fixed top-0 left-0 right-0 z-50 transition-all duration-500 ease-in-out px-4 sm:px-6 lg:px-8"
            :class="isScrolled ? 'pt-4' : 'pt-2'"
        >
            <nav
                class="max-w-7xl mx-auto rounded-full transition-all duration-500 relative group/nav"
                :class="
                    isScrolled
                        ? 'bg-white/90 dark:bg-gray-900/90 backdrop-blur-2xl shadow-xl shadow-brand-red/5 py-2.5 px-6'
                        : 'bg-transparent py-4 px-0'
                "
            >
                <!-- Gradient Border Effect -->
                <div
                    class="absolute inset-0 rounded-full p-[1px] bg-gradient-to-r from-white/20 via-white/50 to-white/20 dark:from-gray-700/30 dark:via-gray-600/50 dark:to-gray-700/30 -z-10 opacity-0 transition-opacity duration-500"
                    :class="{ 'opacity-100': isScrolled }"
                >
                    <div
                        class="w-full h-full bg-white/90 dark:bg-gray-900/90 rounded-full"
                    ></div>
                </div>

                <div class="flex items-center justify-between relative z-10">
                    <!-- Logo -->
                    <Link
                        :href="route('frontend.home')"
                        class="flex items-center space-x-3 group"
                    >
                        <div
                            class="relative w-12 h-12 md:w-14 md:h-14 flex items-center justify-center"
                        >
                            <!-- Saturn Rings -->
                            <div
                                class="logo-ring absolute w-[160%] h-[160%] animate-ring-spin"
                            ></div>
                            <div
                                class="logo-ring absolute w-[140%] h-[140%] animate-ring-spin"
                                style="animation-duration: 15s"
                            ></div>

                            <!-- Logo Image -->
                            <img
                                src="/img/logoNoBg.png"
                                alt="Logo"
                                class="h-10 w-10 md:h-12 md:w-12 relative z-10 transition-transform duration-500 group-hover:scale-110 drop-shadow-lg"
                            />
                        </div>
                        <span
                            class="text-xl md:text-2xl font-extrabold tracking-tight font-serif transition-all duration-300 group-hover:tracking-normal"
                        >
                            <span class="text-brand-red">Tunggal</span>
                            <span class="text-brand-dark">Jaya</span>
                        </span>
                    </Link>

                    <!-- Desktop Navigation -->
                    <div class="hidden lg:flex items-center space-x-1">
                        <Link
                            v-for="link in navLinks"
                            :key="link.href"
                            :href="route(link.href)"
                            class="px-5 py-2.5 rounded-full text-sm font-bold transition-all duration-300 relative group overflow-hidden"
                            :class="
                                isActive(link.href)
                                    ? 'text-brand-red bg-brand-red/10 ring-1 ring-brand-red/20'
                                    : 'text-gray-600 dark:text-gray-300 hover:text-brand-red dark:hover:text-brand-red hover:bg-brand-red/5'
                            "
                        >
                            <span class="relative z-10">{{ link.name }}</span>
                            <!-- Active Glow -->
                            <div
                                v-if="isActive(link.href)"
                                class="absolute inset-0 bg-brand-red/5 blur-md"
                            ></div>
                        </Link>
                    </div>

                    <!-- Right Side Actions -->
                    <div class="flex items-center space-x-3">
                        <!-- Dark Mode Toggle -->
                        <button
                            @click="toggleDarkMode"
                            class="w-10 h-10 rounded-full flex items-center justify-center hover:bg-gray-100 dark:hover:bg-gray-800 transition-all duration-300 focus:outline-none hover:rotate-12"
                        >
                            <i
                                :class="
                                    isDarkMode
                                        ? 'fas fa-sun text-yellow-400 text-lg'
                                        : 'fas fa-moon text-indigo-600 text-lg'
                                "
                            ></i>
                        </button>

                        <!-- Auth Buttons -->
                        <template v-if="!page.props.auth.user">
                            <Link
                                :href="route('login')"
                                class="hidden sm:inline-flex px-5 py-2 text-sm font-bold text-gray-700 dark:text-gray-200 hover:text-brand-red dark:hover:text-brand-red transition-colors"
                            >
                                Masuk
                            </Link>
                            <Link
                                :href="route('register')"
                                class="hidden sm:inline-flex px-6 py-2.5 bg-gradient-to-r from-brand-red to-red-700 text-white text-sm font-bold rounded-full shadow-lg shadow-brand-red/30 hover:shadow-brand-red/50 transition-all duration-300 hover:-translate-y-0.5 hover:brightness-110"
                            >
                                Daftar
                            </Link>
                        </template>
                        <template v-else>
                            <div class="relative group">
                                <button
                                    class="flex items-center space-x-2 p-1.5 pr-4 rounded-full border border-gray-200 dark:border-gray-700 hover:border-brand-red dark:hover:border-brand-red transition-all duration-300 bg-white/50 dark:bg-gray-800/50 backdrop-blur-sm group-hover:shadow-lg group-hover:shadow-brand-red/10"
                                >
                                    <div
                                        class="w-8 h-8 rounded-full bg-gradient-to-br from-brand-red to-red-700 flex items-center justify-center text-white text-sm font-bold shadow-md"
                                    >
                                        {{
                                            page.props.auth.user.name
                                                .charAt(0)
                                                .toUpperCase()
                                        }}
                                    </div>
                                    <span
                                        class="hidden md:block text-sm font-semibold text-gray-700 dark:text-gray-200"
                                    >
                                        {{
                                            page.props.auth.user.name.split(
                                                " "
                                            )[0]
                                        }}
                                    </span>
                                    <i
                                        class="fas fa-chevron-down text-xs text-gray-400 transition-transform duration-300 group-hover:rotate-180"
                                    ></i>
                                </button>
                                <!-- Dropdown -->
                                <div
                                    class="absolute right-0 mt-4 w-60 py-2 bg-white dark:bg-gray-800 rounded-2xl shadow-2xl shadow-brand-red/10 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 border border-gray-100 dark:border-gray-700 transform origin-top-right scale-95 group-hover:scale-100 z-50"
                                >
                                    <div
                                        class="px-4 py-4 border-b border-gray-100 dark:border-gray-700 mb-2 bg-gray-50/50 dark:bg-gray-800/50"
                                    >
                                        <p
                                            class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider font-bold mb-1"
                                        >
                                            Akun
                                        </p>
                                        <p
                                            class="text-sm font-bold text-gray-800 dark:text-white truncate"
                                        >
                                            {{ page.props.auth.user.email }}
                                        </p>
                                    </div>

                                    <Link
                                        :href="route('profile.edit')"
                                        class="flex items-center px-4 py-3 text-sm text-gray-700 dark:text-gray-200 hover:bg-red-50 dark:hover:bg-red-900/20 hover:text-brand-red dark:hover:text-brand-red transition-colors"
                                    >
                                        <div
                                            class="w-8 h-8 rounded-lg bg-red-50 dark:bg-red-900/20 flex items-center justify-center mr-3 text-brand-red"
                                        >
                                            <i class="fas fa-user"></i>
                                        </div>
                                        Profil Saya
                                    </Link>
                                    <Link
                                        :href="route('booking-history.index')"
                                        class="flex items-center px-4 py-3 text-sm text-gray-700 dark:text-gray-200 hover:bg-red-50 dark:hover:bg-red-900/20 hover:text-brand-red dark:hover:text-brand-red transition-colors"
                                    >
                                        <div
                                            class="w-8 h-8 rounded-lg bg-red-50 dark:bg-red-900/20 flex items-center justify-center mr-3 text-brand-red"
                                        >
                                            <i class="fas fa-history"></i>
                                        </div>
                                        Riwayat Booking
                                    </Link>

                                    <template
                                        v-if="
                                            page.props.auth.user.role ===
                                            'admin'
                                        "
                                    >
                                        <div
                                            class="my-2 border-t border-gray-100 dark:border-gray-700"
                                        ></div>
                                        <Link
                                            :href="route('admin.dashboard')"
                                            class="flex items-center px-4 py-3 text-sm text-indigo-600 dark:text-indigo-400 font-semibold hover:bg-indigo-50 dark:hover:bg-indigo-900/20 transition-colors"
                                        >
                                            <div
                                                class="w-8 h-8 rounded-lg bg-indigo-50 dark:bg-indigo-900/20 flex items-center justify-center mr-3 text-indigo-600"
                                            >
                                                <i class="fas fa-cog"></i>
                                            </div>
                                            Admin Panel
                                        </Link>
                                    </template>

                                    <div
                                        class="my-2 border-t border-gray-100 dark:border-gray-700"
                                    ></div>
                                    <Link
                                        :href="route('logout')"
                                        method="post"
                                        as="button"
                                        class="w-full text-left flex items-center px-4 py-3 text-sm text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors"
                                    >
                                        <div
                                            class="w-8 h-8 rounded-lg bg-red-50 dark:bg-red-900/20 flex items-center justify-center mr-3 text-red-600"
                                        >
                                            <i class="fas fa-sign-out-alt"></i>
                                        </div>
                                        Keluar
                                    </Link>
                                </div>
                            </div>
                        </template>

                        <!-- Mobile Menu Button -->
                        <button
                            @click="toggleMobileMenu"
                            class="lg:hidden w-10 h-10 rounded-full hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors flex items-center justify-center"
                        >
                            <i
                                :class="
                                    mobileMenuOpen
                                        ? 'fas fa-times'
                                        : 'fas fa-bars'
                                "
                                class="text-lg text-gray-700 dark:text-gray-200"
                            ></i>
                        </button>
                    </div>
                </div>

                <!-- Mobile Navigation -->
                <transition
                    enter-active-class="transition duration-300 ease-out"
                    enter-from-class="opacity-0 -translate-y-4 scale-95"
                    enter-to-class="opacity-100 translate-y-0 scale-100"
                    leave-active-class="transition duration-200 ease-in"
                    leave-from-class="opacity-100 translate-y-0 scale-100"
                    leave-to-class="opacity-0 -translate-y-4 scale-95"
                >
                    <div
                        v-if="mobileMenuOpen"
                        class="lg:hidden absolute left-0 right-0 top-full mt-4 mx-4 glass-white dark:glass-dark rounded-2xl shadow-2xl shadow-brand-red/10 border border-white/20 overflow-hidden"
                    >
                        <div class="p-4 space-y-2">
                            <Link
                                v-for="link in navLinks"
                                :key="link.href"
                                :href="route(link.href)"
                                @click="mobileMenuOpen = false"
                                class="block px-4 py-3 rounded-xl text-sm font-bold transition-all duration-200"
                                :class="
                                    isActive(link.href)
                                        ? 'bg-brand-red text-white shadow-lg shadow-brand-red/30'
                                        : 'text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-brand-red'
                                "
                            >
                                <div class="flex items-center justify-between">
                                    {{ link.name }}
                                    <i
                                        v-if="isActive(link.href)"
                                        class="fas fa-chevron-right text-xs"
                                    ></i>
                                </div>
                            </Link>

                            <div
                                class="border-t border-gray-100 dark:border-gray-700 my-2 pt-2"
                            ></div>

                            <template v-if="!page.props.auth.user">
                                <Link
                                    :href="route('login')"
                                    class="block px-4 py-3 text-sm font-semibold text-center text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-xl"
                                >
                                    Masuk
                                </Link>
                                <Link
                                    :href="route('register')"
                                    class="block px-4 py-3 mt-2 bg-gradient-to-r from-brand-red to-red-700 text-white text-sm font-bold rounded-xl text-center shadow-lg shadow-brand-red/30"
                                >
                                    Daftar Sekarang
                                </Link>
                            </template>
                        </div>
                    </div>
                </transition>
            </nav>
        </header>

        <!-- Main Content -->
        <main class="flex-grow pt-24">
            <slot />
        </main>

        <!-- Footer -->
        <footer
            class="bg-gray-900 text-white relative overflow-hidden border-t border-white/10"
        >
            <!-- Background Pattern -->
            <div class="absolute inset-0 opacity-10 pointer-events-none">
                <div
                    class="absolute -top-24 -left-24 w-96 h-96 bg-brand-red/20 rounded-full blur-[100px]"
                ></div>
                <div
                    class="absolute bottom-0 right-0 w-80 h-80 bg-blue-600/20 rounded-full blur-[100px]"
                ></div>
            </div>

            <div
                class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 relative z-10"
            >
                <div
                    class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 lg:gap-12"
                >
                    <!-- Company Info -->
                    <div>
                        <Link
                            :href="route('frontend.home')"
                            class="flex items-center space-x-3 mb-6"
                        >
                            <img
                                src="/img/logoNoBg.png"
                                alt="Logo"
                                class="h-12 w-12 brightness-200"
                            />
                            <span class="text-2xl font-bold font-serif">
                                <span class="text-brand-red">Tunggal</span>
                                <span class="text-brand-dark">Jaya</span>
                            </span>
                        </Link>
                        <p
                            class="text-gray-400 mb-8 leading-relaxed font-light text-sm"
                        >
                            Partner perjalanan terpercaya Anda dengan armada bus
                            modern dan pelayanan premium. Kenyamanan dan
                            keselamatan adalah prioritas utama kami sejak 1973.
                        </p>
                        <div class="flex space-x-3">
                            <a
                                href="#"
                                class="w-9 h-9 rounded-full bg-white/5 border border-white/10 flex items-center justify-center hover:bg-brand-red hover:border-brand-red hover:scale-110 transition-all duration-300 group"
                            >
                                <i
                                    class="fab fa-facebook-f group-hover:text-white text-gray-400 transition-colors text-sm"
                                ></i>
                            </a>
                            <a
                                href="#"
                                class="w-9 h-9 rounded-full bg-white/5 border border-white/10 flex items-center justify-center hover:bg-sky-500 hover:border-sky-400 hover:scale-110 transition-all duration-300 group"
                            >
                                <i
                                    class="fab fa-twitter group-hover:text-white text-gray-400 transition-colors text-sm"
                                ></i>
                            </a>
                            <a
                                href="#"
                                class="w-9 h-9 rounded-full bg-white/5 border border-white/10 flex items-center justify-center hover:bg-pink-600 hover:border-pink-500 hover:scale-110 transition-all duration-300 group"
                            >
                                <i
                                    class="fab fa-instagram group-hover:text-white text-gray-400 transition-colors text-sm"
                                ></i>
                            </a>
                            <a
                                href="#"
                                class="w-9 h-9 rounded-full bg-white/5 border border-white/10 flex items-center justify-center hover:bg-blue-700 hover:border-blue-600 hover:scale-110 transition-all duration-300 group"
                            >
                                <i
                                    class="fab fa-linkedin-in group-hover:text-white text-gray-400 transition-colors text-sm"
                                ></i>
                            </a>
                        </div>
                    </div>

                    <!-- Quick Links -->
                    <div>
                        <h3
                            class="text-lg font-bold mb-6 text-white border-b-2 border-brand-red inline-block pb-2"
                        >
                            Link Cepat
                        </h3>
                        <ul class="space-y-3 text-sm">
                            <li
                                v-for="(item, index) in [
                                    {
                                        name: 'Tentang Kami',
                                        route: 'frontend.about',
                                    },
                                    {
                                        name: 'Rute Perjalanan',
                                        route: 'frontend.routes.index',
                                    },
                                    {
                                        name: 'Info Armada',
                                        route: 'frontend.fleet.index',
                                    },
                                    {
                                        name: 'Berita & Update',
                                        route: 'frontend.news.index',
                                    },
                                    {
                                        name: 'Hubungi Kami',
                                        route: 'frontend.contact',
                                    },
                                ]"
                                :key="index"
                            >
                                <Link
                                    :href="route(item.route)"
                                    class="text-gray-400 hover:text-brand-red transition-colors flex items-center group"
                                >
                                    <i
                                        class="fas fa-chevron-right text-[10px] mr-2 opacity-0 group-hover:opacity-100 transition-all transform -translate-x-2 group-hover:translate-x-0 text-brand-red"
                                    ></i>
                                    {{ item.name }}
                                </Link>
                            </li>
                        </ul>
                    </div>

                    <!-- Services (From Blade) -->
                    <div>
                        <h3
                            class="text-lg font-bold mb-6 text-white border-b-2 border-brand-red inline-block pb-2"
                        >
                            Layanan
                        </h3>
                        <ul class="space-y-3 text-sm">
                            <li
                                class="flex items-center text-gray-400 hover:text-white transition-colors"
                            >
                                <i class="fas fa-bus mr-3 text-brand-red"></i>
                                Transportasi Kota
                            </li>
                            <li
                                class="flex items-center text-gray-400 hover:text-white transition-colors"
                            >
                                <i class="fas fa-route mr-3 text-brand-red"></i>
                                Perjalanan Antar Kota
                            </li>
                            <li
                                class="flex items-center text-gray-400 hover:text-white transition-colors"
                            >
                                <i class="fas fa-plane mr-3 text-brand-red"></i>
                                Antar Jemput Bandara
                            </li>
                            <li
                                class="flex items-center text-gray-400 hover:text-white transition-colors"
                            >
                                <i
                                    class="fas fa-map-marked-alt mr-3 text-brand-red"
                                ></i>
                                Paket Wisata
                            </li>
                            <li
                                class="flex items-center text-gray-400 hover:text-white transition-colors"
                            >
                                <i
                                    class="fas fa-building mr-3 text-brand-red"
                                ></i>
                                Perjalanan Bisnis
                            </li>
                        </ul>
                    </div>

                    <!-- Contact -->
                    <div>
                        <h3
                            class="text-lg font-bold mb-6 text-white border-b-2 border-brand-red inline-block pb-2"
                        >
                            Hubungi Kami
                        </h3>
                        <ul class="space-y-4 text-gray-400 text-sm">
                            <li class="flex items-start space-x-3 group">
                                <div class="mt-1 flex-shrink-0">
                                    <i
                                        class="fas fa-map-marker-alt text-brand-red group-hover:text-white transition-colors"
                                    ></i>
                                </div>
                                <span
                                    class="group-hover:text-gray-300 transition-colors leading-relaxed"
                                >
                                    Jl. Transportation No. 123, Jakarta 12345
                                </span>
                            </li>
                            <li class="flex items-center space-x-3 group">
                                <div class="flex-shrink-0">
                                    <i
                                        class="fas fa-phone-alt text-brand-red group-hover:text-white transition-colors"
                                    ></i>
                                </div>
                                <span
                                    class="group-hover:text-gray-300 transition-colors"
                                >
                                    +62 21 1234 5678
                                </span>
                            </li>
                            <li class="flex items-center space-x-3 group">
                                <div class="flex-shrink-0">
                                    <i
                                        class="fas fa-envelope text-brand-red group-hover:text-white transition-colors"
                                    ></i>
                                </div>
                                <span
                                    class="group-hover:text-gray-300 transition-colors"
                                >
                                    info@tunggaljayatransport.com
                                </span>
                            </li>
                            <li class="flex items-center space-x-3 group">
                                <div class="flex-shrink-0">
                                    <i
                                        class="fas fa-clock text-brand-red group-hover:text-white transition-colors"
                                    ></i>
                                </div>
                                <span
                                    class="group-hover:text-gray-300 transition-colors"
                                >
                                    Senin - Jumat, 08:00 - 20:00
                                </span>
                            </li>
                        </ul>
                    </div>
                </div>

                <div
                    class="border-t border-white/10 mt-12 pt-8 flex flex-col md:flex-row justify-between items-center text-gray-500 text-sm"
                >
                    <p>
                        &copy; {{ new Date().getFullYear() }}
                        <span
                            class="text-white hover:text-brand-red cursor-pointer transition-colors"
                            >Tunggal Jaya Transport</span
                        >. All rights reserved.
                    </p>
                    <div class="flex space-x-6 mt-4 md:mt-0">
                        <a
                            href="#"
                            class="hover:text-brand-red transition-colors"
                            >Privacy Policy</a
                        >
                        <a
                            href="#"
                            class="hover:text-brand-red transition-colors"
                            >Terms of Service</a
                        >
                        <a
                            href="#"
                            class="hover:text-brand-red transition-colors"
                            >Cookie Policy</a
                        >
                    </div>
                </div>
            </div>
        </footer>
    </div>
</template>
