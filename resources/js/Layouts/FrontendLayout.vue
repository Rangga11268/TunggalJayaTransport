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
        class="min-h-screen flex flex-col bg-gray-50 dark:bg-gray-900 transition-colors duration-300"
    >
        <!-- Flash Messages -->
        <FlashMessages />

        <!-- Header -->
        <header
            class="fixed top-0 left-0 right-0 z-50 transition-all duration-300"
            :class="
                isScrolled
                    ? 'bg-white/90 dark:bg-gray-900/90 backdrop-blur-lg shadow-lg'
                    : 'bg-transparent'
            "
        >
            <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16 md:h-20">
                    <!-- Logo -->
                    <Link
                        :href="route('frontend.home')"
                        class="flex items-center space-x-3 group"
                    >
                        <img
                            src="/img/logoNoBg.png"
                            alt="Logo"
                            class="h-10 w-10 md:h-12 md:w-12 transition-transform group-hover:scale-110"
                        />
                        <span
                            class="text-lg md:text-xl font-bold bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent"
                        >
                            Tunggal Jaya
                        </span>
                    </Link>

                    <!-- Desktop Navigation -->
                    <div class="hidden lg:flex items-center space-x-1">
                        <Link
                            v-for="link in navLinks"
                            :key="link.href"
                            :href="route(link.href)"
                            class="px-4 py-2 rounded-full text-sm font-medium transition-all duration-200"
                            :class="
                                isActive(link.href)
                                    ? 'bg-gradient-to-r from-blue-600 to-indigo-600 text-white shadow-lg shadow-blue-500/25'
                                    : 'text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800'
                            "
                        >
                            {{ link.name }}
                        </Link>
                    </div>

                    <!-- Right Side Actions -->
                    <div class="flex items-center space-x-3">
                        <!-- Dark Mode Toggle -->
                        <button
                            @click="toggleDarkMode"
                            class="p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors"
                        >
                            <i
                                :class="
                                    isDarkMode
                                        ? 'fas fa-sun text-yellow-400'
                                        : 'fas fa-moon text-gray-600'
                                "
                            ></i>
                        </button>

                        <!-- Auth Buttons -->
                        <template v-if="!page.props.auth.user">
                            <Link
                                :href="route('login')"
                                class="hidden sm:inline-flex px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-200 hover:text-blue-600 dark:hover:text-blue-400 transition-colors"
                            >
                                Masuk
                            </Link>
                            <Link
                                :href="route('register')"
                                class="hidden sm:inline-flex px-5 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 text-white text-sm font-medium rounded-full hover:shadow-lg hover:shadow-blue-500/25 transition-all duration-200 hover:-translate-y-0.5"
                            >
                                Daftar
                            </Link>
                        </template>
                        <template v-else>
                            <div class="relative group">
                                <button
                                    class="flex items-center space-x-2 p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors"
                                >
                                    <div
                                        class="w-8 h-8 rounded-full bg-gradient-to-r from-blue-600 to-indigo-600 flex items-center justify-center text-white text-sm font-medium"
                                    >
                                        {{
                                            page.props.auth.user.name
                                                .charAt(0)
                                                .toUpperCase()
                                        }}
                                    </div>
                                    <span
                                        class="hidden md:block text-sm font-medium text-gray-700 dark:text-gray-200"
                                    >
                                        {{ page.props.auth.user.name }}
                                    </span>
                                </button>
                                <!-- Dropdown -->
                                <div
                                    class="absolute right-0 mt-2 w-48 py-2 bg-white dark:bg-gray-800 rounded-xl shadow-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 border border-gray-100 dark:border-gray-700"
                                >
                                    <Link
                                        :href="route('profile.edit')"
                                        class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700"
                                    >
                                        <i class="fas fa-user mr-2"></i> Profil
                                    </Link>
                                    <Link
                                        :href="route('booking-history.index')"
                                        class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700"
                                    >
                                        <i class="fas fa-history mr-2"></i>
                                        Riwayat Booking
                                    </Link>
                                    <template
                                        v-if="
                                            page.props.auth.user.role ===
                                            'admin'
                                        "
                                    >
                                        <Link
                                            :href="route('admin.dashboard')"
                                            class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700"
                                        >
                                            <i class="fas fa-cog mr-2"></i>
                                            Admin Panel
                                        </Link>
                                    </template>
                                    <hr
                                        class="my-2 border-gray-200 dark:border-gray-700"
                                    />
                                    <Link
                                        :href="route('logout')"
                                        method="post"
                                        as="button"
                                        class="w-full text-left block px-4 py-2 text-sm text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20"
                                    >
                                        <i class="fas fa-sign-out-alt mr-2"></i>
                                        Keluar
                                    </Link>
                                </div>
                            </div>
                        </template>

                        <!-- Mobile Menu Button -->
                        <button
                            @click="toggleMobileMenu"
                            class="lg:hidden p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors"
                        >
                            <i
                                :class="
                                    mobileMenuOpen
                                        ? 'fas fa-times'
                                        : 'fas fa-bars'
                                "
                                class="text-gray-600 dark:text-gray-200"
                            ></i>
                        </button>
                    </div>
                </div>

                <!-- Mobile Navigation -->
                <transition
                    enter-active-class="transition duration-200 ease-out"
                    enter-from-class="opacity-0 -translate-y-2"
                    enter-to-class="opacity-100 translate-y-0"
                    leave-active-class="transition duration-150 ease-in"
                    leave-from-class="opacity-100 translate-y-0"
                    leave-to-class="opacity-0 -translate-y-2"
                >
                    <div
                        v-if="mobileMenuOpen"
                        class="lg:hidden absolute left-0 right-0 top-full bg-white dark:bg-gray-900 shadow-lg border-t border-gray-100 dark:border-gray-800"
                    >
                        <div class="px-4 py-4 space-y-2">
                            <Link
                                v-for="link in navLinks"
                                :key="link.href"
                                :href="route(link.href)"
                                @click="mobileMenuOpen = false"
                                class="block px-4 py-3 rounded-xl text-sm font-medium transition-colors"
                                :class="
                                    isActive(link.href)
                                        ? 'bg-gradient-to-r from-blue-600 to-indigo-600 text-white'
                                        : 'text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800'
                                "
                            >
                                {{ link.name }}
                            </Link>
                            <hr
                                class="border-gray-200 dark:border-gray-700 my-2"
                            />
                            <template v-if="!page.props.auth.user">
                                <Link
                                    :href="route('login')"
                                    class="block px-4 py-3 text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-xl"
                                >
                                    Masuk
                                </Link>
                                <Link
                                    :href="route('register')"
                                    class="block px-4 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white text-sm font-medium rounded-xl text-center"
                                >
                                    Daftar
                                </Link>
                            </template>
                        </div>
                    </div>
                </transition>
            </nav>
        </header>

        <!-- Main Content -->
        <main class="flex-grow pt-16 md:pt-20">
            <slot />
        </main>

        <!-- Footer -->
        <footer class="bg-gray-900 text-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                    <!-- Company Info -->
                    <div class="col-span-1 md:col-span-2">
                        <div class="flex items-center space-x-3 mb-4">
                            <img
                                src="/img/logoNoBg.png"
                                alt="Logo"
                                class="h-12 w-12"
                            />
                            <span class="text-xl font-bold"
                                >Tunggal Jaya Transport</span
                            >
                        </div>
                        <p class="text-gray-400 mb-4">
                            Transportasi bus nyaman dan terpercaya untuk
                            perjalanan Anda. Armada modern dengan sopir
                            profesional.
                        </p>
                        <div class="flex space-x-4">
                            <a
                                href="#"
                                class="w-10 h-10 rounded-full bg-gray-800 flex items-center justify-center hover:bg-blue-600 transition-colors"
                            >
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a
                                href="#"
                                class="w-10 h-10 rounded-full bg-gray-800 flex items-center justify-center hover:bg-blue-400 transition-colors"
                            >
                                <i class="fab fa-twitter"></i>
                            </a>
                            <a
                                href="#"
                                class="w-10 h-10 rounded-full bg-gray-800 flex items-center justify-center hover:bg-pink-600 transition-colors"
                            >
                                <i class="fab fa-instagram"></i>
                            </a>
                            <a
                                href="#"
                                class="w-10 h-10 rounded-full bg-gray-800 flex items-center justify-center hover:bg-green-500 transition-colors"
                            >
                                <i class="fab fa-whatsapp"></i>
                            </a>
                        </div>
                    </div>

                    <!-- Quick Links -->
                    <div>
                        <h3 class="text-lg font-semibold mb-4">Link Cepat</h3>
                        <ul class="space-y-2">
                            <li>
                                <Link
                                    :href="route('frontend.booking.index')"
                                    class="text-gray-400 hover:text-white transition-colors"
                                    >Pesan Tiket</Link
                                >
                            </li>
                            <li>
                                <Link
                                    :href="route('frontend.routes.index')"
                                    class="text-gray-400 hover:text-white transition-colors"
                                    >Rute</Link
                                >
                            </li>
                            <li>
                                <Link
                                    :href="route('frontend.fleet.index')"
                                    class="text-gray-400 hover:text-white transition-colors"
                                    >Armada</Link
                                >
                            </li>
                            <li>
                                <Link
                                    :href="route('frontend.about')"
                                    class="text-gray-400 hover:text-white transition-colors"
                                    >Tentang Kami</Link
                                >
                            </li>
                        </ul>
                    </div>

                    <!-- Contact -->
                    <div>
                        <h3 class="text-lg font-semibold mb-4">Kontak</h3>
                        <ul class="space-y-3 text-gray-400">
                            <li class="flex items-center space-x-3">
                                <i class="fas fa-phone text-blue-500"></i>
                                <span>+62 812-3456-7890</span>
                            </li>
                            <li class="flex items-center space-x-3">
                                <i class="fas fa-envelope text-blue-500"></i>
                                <span>info@tunggaljaya.com</span>
                            </li>
                            <li class="flex items-start space-x-3">
                                <i
                                    class="fas fa-map-marker-alt text-blue-500 mt-1"
                                ></i>
                                <span
                                    >Jl. Raya Utama No. 123, Surabaya, Jawa
                                    Timur</span
                                >
                            </li>
                        </ul>
                    </div>
                </div>

                <hr class="border-gray-800 my-8" />

                <div
                    class="flex flex-col md:flex-row justify-between items-center text-gray-400 text-sm"
                >
                    <p>
                        &copy; {{ new Date().getFullYear() }} Tunggal Jaya
                        Transport. All rights reserved.
                    </p>
                    <div class="flex space-x-6 mt-4 md:mt-0">
                        <a href="#" class="hover:text-white transition-colors"
                            >Privacy Policy</a
                        >
                        <a href="#" class="hover:text-white transition-colors"
                            >Terms of Service</a
                        >
                    </div>
                </div>
            </div>
        </footer>
    </div>
</template>
