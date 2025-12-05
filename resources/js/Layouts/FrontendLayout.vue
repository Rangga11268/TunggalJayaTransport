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

        <!-- Header -->
        <header
            class="fixed top-0 left-0 right-0 z-50 transition-all duration-300"
            :class="
                isScrolled
                    ? 'glass-white dark:glass-dark shadow-lg py-2'
                    : 'bg-transparent py-4'
            "
        >
            <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16">
                    <!-- Logo -->
                    <Link
                        :href="route('frontend.home')"
                        class="flex items-center space-x-3 group"
                    >
                        <div class="relative">
                            <div
                                class="absolute inset-0 bg-blue-500 blur-lg opacity-20 group-hover:opacity-40 transition-opacity"
                            ></div>
                            <img
                                src="/img/logoNoBg.png"
                                alt="Logo"
                                class="h-10 w-10 md:h-12 md:w-12 relative z-10 transition-transform group-hover:scale-110 drop-shadow-md"
                            />
                        </div>
                        <span
                            class="text-xl md:text-2xl font-extrabold tracking-tight"
                        >
                            <span class="text-gradient-ocean">Tunggal</span>
                            <span class="text-gradient-gold">Jaya</span>
                        </span>
                    </Link>

                    <!-- Desktop Navigation -->
                    <div class="hidden lg:flex items-center space-x-1">
                        <Link
                            v-for="link in navLinks"
                            :key="link.href"
                            :href="route(link.href)"
                            class="px-4 py-2 rounded-full text-sm font-semibold transition-all duration-300 relative group overflow-hidden"
                            :class="
                                isActive(link.href)
                                    ? 'text-white bg-gradient-to-r from-primary-600 to-primary-700 shadow-lg shadow-primary-500/30'
                                    : 'text-gray-700 dark:text-gray-200 hover:text-primary-600 dark:hover:text-primary-400'
                            "
                        >
                            <span class="relative z-10">{{ link.name }}</span>
                            <div
                                v-if="!isActive(link.href)"
                                class="absolute inset-0 bg-blue-50 dark:bg-blue-900/20 opacity-0 group-hover:opacity-100 transition-opacity duration-300"
                            ></div>
                        </Link>
                    </div>

                    <!-- Right Side Actions -->
                    <div class="flex items-center space-x-3">
                        <!-- Dark Mode Toggle -->
                        <button
                            @click="toggleDarkMode"
                            class="w-10 h-10 rounded-full flex items-center justify-center hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors focus:outline-none"
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
                                class="hidden sm:inline-flex px-5 py-2 text-sm font-bold text-gray-700 dark:text-gray-200 hover:text-blue-600 dark:hover:text-blue-400 transition-colors"
                            >
                                Masuk
                            </Link>
                            <Link
                                :href="route('register')"
                                class="hidden sm:inline-flex px-6 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 text-white text-sm font-bold rounded-full hover:shadow-lg hover:shadow-blue-500/30 transition-all duration-300 hover:-translate-y-0.5"
                            >
                                Daftar
                            </Link>
                        </template>
                        <template v-else>
                            <div class="relative group">
                                <button
                                    class="flex items-center space-x-2 p-1.5 pr-4 rounded-full border border-gray-200 dark:border-gray-700 hover:border-blue-500 dark:hover:border-blue-500 transition-colors bg-white/50 dark:bg-gray-800/50 backdrop-blur-sm"
                                >
                                    <div
                                        class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white text-sm font-bold shadow-md"
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
                                        class="fas fa-chevron-down text-xs text-gray-400"
                                    ></i>
                                </button>
                                <!-- Dropdown -->
                                <div
                                    class="absolute right-0 mt-2 w-56 py-2 bg-white dark:bg-gray-800 rounded-2xl shadow-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 border border-gray-100 dark:border-gray-700 transform origin-top-right scale-95 group-hover:scale-100 z-50"
                                >
                                    <div
                                        class="px-4 py-3 border-b border-gray-100 dark:border-gray-700 mb-2"
                                    >
                                        <p
                                            class="text-xs text-gray-500 dark:text-gray-400"
                                        >
                                            Login sebagai
                                        </p>
                                        <p
                                            class="text-sm font-bold text-gray-800 dark:text-white truncate"
                                        >
                                            {{ page.props.auth.user.email }}
                                        </p>
                                    </div>

                                    <Link
                                        :href="route('profile.edit')"
                                        class="flex items-center px-4 py-2.5 text-sm text-gray-700 dark:text-gray-200 hover:bg-blue-50 dark:hover:bg-blue-900/20 hover:text-blue-600 dark:hover:text-blue-400 transition-colors"
                                    >
                                        <i
                                            class="fas fa-user w-6 text-center mr-2 text-gray-400"
                                        ></i>
                                        Profil Saya
                                    </Link>
                                    <Link
                                        :href="route('booking-history.index')"
                                        class="flex items-center px-4 py-2.5 text-sm text-gray-700 dark:text-gray-200 hover:bg-blue-50 dark:hover:bg-blue-900/20 hover:text-blue-600 dark:hover:text-blue-400 transition-colors"
                                    >
                                        <i
                                            class="fas fa-history w-6 text-center mr-2 text-gray-400"
                                        ></i>
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
                                            class="flex items-center px-4 py-2.5 text-sm text-indigo-600 dark:text-indigo-400 font-semibold hover:bg-indigo-50 dark:hover:bg-indigo-900/20 transition-colors"
                                        >
                                            <i
                                                class="fas fa-cog w-6 text-center mr-2"
                                            ></i>
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
                                        class="w-full text-left flex items-center px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors"
                                    >
                                        <i
                                            class="fas fa-sign-out-alt w-6 text-center mr-2"
                                        ></i>
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
                        class="lg:hidden absolute left-4 right-4 top-20 glass-white dark:glass-dark rounded-2xl shadow-2xl border border-white/20 overflow-hidden"
                    >
                        <div class="p-4 space-y-2">
                            <Link
                                v-for="link in navLinks"
                                :key="link.href"
                                :href="route(link.href)"
                                @click="mobileMenuOpen = false"
                                class="block px-4 py-3 rounded-xl text-sm font-semibold transition-all duration-200"
                                :class="
                                    isActive(link.href)
                                        ? 'bg-gradient-to-r from-blue-600 to-indigo-600 text-white shadow-lg'
                                        : 'text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800'
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
                                    class="block px-4 py-3 mt-2 bg-gradient-to-r from-blue-600 to-indigo-600 text-white text-sm font-bold rounded-xl text-center shadow-lg"
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
        <main class="flex-grow pt-20">
            <slot />
        </main>

        <!-- Footer -->
        <footer
            class="bg-primary-950 text-white relative overflow-hidden border-t border-white/10"
        >
            <!-- Background Pattern -->
            <div class="absolute inset-0 opacity-5 pointer-events-none">
                <div
                    class="absolute top-0 left-0 w-full h-full bg-[url('/img/pattern.png')] bg-repeat"
                ></div>
            </div>

            <div
                class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 relative z-10"
            >
                <div
                    class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12"
                >
                    <!-- Company Info -->
                    <div class="col-span-1 md:col-span-2">
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
                                <span class="text-gradient-ocean">Tunggal</span>
                                <span class="text-gradient-gold">Jaya</span>
                            </span>
                        </Link>
                        <p
                            class="text-gray-400 mb-8 max-w-md leading-relaxed font-light"
                        >
                            Partner perjalanan terpercaya Anda dengan armada bus
                            modern dan pelayanan premium. Kenyamanan dan
                            keselamatan adalah prioritas utama kami.
                        </p>
                        <div class="flex space-x-4">
                            <a
                                href="#"
                                class="w-10 h-10 rounded-full bg-white/5 border border-white/10 flex items-center justify-center hover:bg-primary-600 hover:border-primary-500 hover:scale-110 transition-all duration-300 group"
                            >
                                <i
                                    class="fab fa-facebook-f group-hover:text-white text-gray-400 transition-colors"
                                ></i>
                            </a>
                            <a
                                href="#"
                                class="w-10 h-10 rounded-full bg-white/5 border border-white/10 flex items-center justify-center hover:bg-sky-500 hover:border-sky-400 hover:scale-110 transition-all duration-300 group"
                            >
                                <i
                                    class="fab fa-twitter group-hover:text-white text-gray-400 transition-colors"
                                ></i>
                            </a>
                            <a
                                href="#"
                                class="w-10 h-10 rounded-full bg-white/5 border border-white/10 flex items-center justify-center hover:bg-pink-600 hover:border-pink-500 hover:scale-110 transition-all duration-300 group"
                            >
                                <i
                                    class="fab fa-instagram group-hover:text-white text-gray-400 transition-colors"
                                ></i>
                            </a>
                            <a
                                href="#"
                                class="w-10 h-10 rounded-full bg-white/5 border border-white/10 flex items-center justify-center hover:bg-green-500 hover:border-green-400 hover:scale-110 transition-all duration-300 group"
                            >
                                <i
                                    class="fab fa-whatsapp group-hover:text-white text-gray-400 transition-colors"
                                ></i>
                            </a>
                        </div>
                    </div>

                    <!-- Quick Links -->
                    <div>
                        <h3
                            class="text-lg font-bold mb-6 text-white border-b-2 border-gold-500 inline-block pb-2 font-serif"
                        >
                            Link Cepat
                        </h3>
                        <ul class="space-y-3">
                            <li
                                v-for="item in [
                                    'Pesan Tiket',
                                    'Rute Perjalanan',
                                    'Info Armada',
                                    'Berita Terkini',
                                    'Tentang Kami',
                                ]"
                                :key="item"
                            >
                                <a
                                    href="#"
                                    class="text-gray-400 hover:text-gold-400 transition-colors flex items-center group"
                                >
                                    <i
                                        class="fas fa-chevron-right text-xs mr-2 opacity-0 group-hover:opacity-100 transition-all transform -translate-x-2 group-hover:translate-x-0 text-gold-500"
                                    ></i>
                                    {{ item }}
                                </a>
                            </li>
                        </ul>
                    </div>

                    <!-- Contact -->
                    <div>
                        <h3
                            class="text-lg font-bold mb-6 text-white border-b-2 border-gold-500 inline-block pb-2 font-serif"
                        >
                            Hubungi Kami
                        </h3>
                        <ul class="space-y-4 text-gray-400">
                            <li class="flex items-start space-x-3 group">
                                <div
                                    class="w-8 h-8 rounded-lg bg-white/5 border border-white/10 flex items-center justify-center flex-shrink-0 group-hover:bg-primary-600 group-hover:border-primary-500 transition-colors"
                                >
                                    <i
                                        class="fas fa-phone text-primary-400 group-hover:text-white transition-colors text-sm"
                                    ></i>
                                </div>
                                <span
                                    class="group-hover:text-gray-300 transition-colors"
                                    >+62 812-3456-7890</span
                                >
                            </li>
                            <li class="flex items-start space-x-3 group">
                                <div
                                    class="w-8 h-8 rounded-lg bg-white/5 border border-white/10 flex items-center justify-center flex-shrink-0 group-hover:bg-primary-600 group-hover:border-primary-500 transition-colors"
                                >
                                    <i
                                        class="fas fa-envelope text-primary-400 group-hover:text-white transition-colors text-sm"
                                    ></i>
                                </div>
                                <span
                                    class="group-hover:text-gray-300 transition-colors"
                                    >info@tunggaljaya.com</span
                                >
                            </li>
                            <li class="flex items-start space-x-3 group">
                                <div
                                    class="w-8 h-8 rounded-lg bg-white/5 border border-white/10 flex items-center justify-center flex-shrink-0 group-hover:bg-primary-600 group-hover:border-primary-500 transition-colors"
                                >
                                    <i
                                        class="fas fa-map-marker-alt text-primary-400 group-hover:text-white transition-colors text-sm"
                                    ></i>
                                </div>
                                <span
                                    class="group-hover:text-gray-300 transition-colors"
                                    >Jl. Raya Utama No. 123, Surabaya, Jawa
                                    Timur</span
                                >
                            </li>
                        </ul>
                    </div>
                </div>

                <div
                    class="border-t border-white/10 mt-12 pt-8 flex flex-col md:flex-row justify-between items-center text-gray-500 text-sm"
                >
                    <p>
                        &copy; {{ new Date().getFullYear() }} Tunggal Jaya
                        Transport. All rights reserved.
                    </p>
                    <div class="flex space-x-6 mt-4 md:mt-0">
                        <a
                            href="#"
                            class="hover:text-gold-400 transition-colors"
                            >Privacy Policy</a
                        >
                        <a
                            href="#"
                            class="hover:text-gold-400 transition-colors"
                            >Terms of Service</a
                        >
                        <a
                            href="#"
                            class="hover:text-gold-400 transition-colors"
                            >Cookie Policy</a
                        >
                    </div>
                </div>
            </div>
        </footer>
    </div>
</template>
