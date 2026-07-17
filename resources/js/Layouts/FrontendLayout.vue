<script setup>
import { ref, onMounted, onUnmounted, watch, computed } from "vue";
import { Link, usePage, router } from "@inertiajs/vue3";
import FlashMessages from "@/Components/FlashMessages.vue";
import WhatsAppButton from "@/Components/WhatsAppButton.vue";
import LoginModal from "@/Components/LoginModal.vue";
import RegisterModal from "@/Components/RegisterModal.vue";
import { useMagnetic } from "@/Composables/useMagnetic";
import gsap from "gsap";

const page = usePage();
const pageKey = computed(() => page.url || Date.now());
const isScrolled = ref(false);
const mobileMenuOpen = ref(false);
const mobileServicesOpen = ref(false);
const mobileCompanyOpen = ref(false);
const mobileMenuRef = ref(null);
const menuButtonRef = ref(null);

const showLoginModal = ref(false);
const showRegisterModal = ref(false);

const openLoginModal = () => {
    mobileMenuOpen.value = false;
    showLoginModal.value = true;
};
const openRegisterModal = () => {
    mobileMenuOpen.value = false;
    showRegisterModal.value = true;
};

// Verification banner: dismissed per session via localStorage
const verificationBannerDismissed = ref(
    localStorage.getItem("verification_banner_dismissed") === "1",
);
const showVerificationBanner = computed(
    () =>
        page.props.auth?.user &&
        !page.props.auth.user.phone_verified &&
        !verificationBannerDismissed.value,
);
const dismissVerificationBanner = () => {
    verificationBannerDismissed.value = true;
    localStorage.setItem("verification_banner_dismissed", "1");
};

// Transitions
const onBeforeEnter = (el) => {
    gsap.set(el, {
        opacity: 0,
        y: 20,
    });
};

const onEnter = (el, done) => {
    gsap.to(el, {
        opacity: 1,
        y: 0,
        duration: 0.6,
        ease: "power3.out",
        onComplete: done,
    });
};

const onLeave = (el, done) => {
    gsap.to(el, {
        opacity: 0,
        y: -10,
        duration: 0.4,
        ease: "power3.in",
        onComplete: done,
    });
};


const hoveredMenu = ref(null);

const serviceLinks = [
    {
        name: "Pesan Tiket",
        href: "frontend.booking.index",
        icon: "fas fa-ticket-alt",
        note: "Booking kursi pilihan Anda sekarang.",
        image: "/img/pesanTiketTujago.png",
    },

    {
        name: "Armada Kami",
        href: "frontend.fleet.index",
        icon: "fas fa-bus",
        note: "Eksplorasi armada premium terbaru.",
        image: "/img/heroImg.jpg",
    },
    {
        name: "Sewa Pariwisata",
        href: "frontend.charter.index",
        icon: "fas fa-umbrella-beach",
        note: "Rental bus untuk wisata rombongan.",
        image: "/img/heroImg.jpg",
    },
];

const companyLinks = [
    {
        name: "Tentang Kami",
        href: "frontend.about",
        icon: "far fa-id-card",
        note: "Kisah dedikasi kami sejak 1973.",
    },
    {
        name: "Berita & Update",
        href: "frontend.news.index",
        icon: "far fa-newspaper",
        note: "Info terbaru seputar Tunggal Jaya.",
    },
    {
        name: "Hubungi Kontak",
        href: "frontend.contact",
        icon: "far fa-envelope",
        note: "Layanan bantuan pelanggan 24/7.",
    },
];

onMounted(() => {
    window.addEventListener("scroll", handleScroll);

    // Check for auth query parameter to open modals automatically
    const params = new URLSearchParams(window.location.search);
    if (params.get('auth') === 'login') {
        showLoginModal.value = true;
        // Optionally remove the param from URL without reloading
        window.history.replaceState({}, document.title, window.location.pathname);
    } else if (params.get('auth') === 'register') {
        showRegisterModal.value = true;
        window.history.replaceState({}, document.title, window.location.pathname);
    }
});

onUnmounted(() => {
    window.removeEventListener("scroll", handleScroll);
});

// Close mobile menu on Escape and prevent body scroll when open
const handleGlobalKeydown = (e) => {
    if (e.key === "Escape" && mobileMenuOpen.value) {
        mobileMenuOpen.value = false;
    }
};

watch(mobileMenuOpen, (val) => {
    try {
        if (val) {
            // prevent background scrolling when menu is open
            document.body.style.overflow = "hidden";
            // focus first element inside menu for accessibility
            setTimeout(() => {
                mobileMenuRef.value?.focus?.();
            }, 50);
            window.addEventListener("keydown", handleGlobalKeydown);
        } else {
            document.body.style.overflow = "";
            window.removeEventListener("keydown", handleGlobalKeydown);
            // restore focus to menu button
            setTimeout(() => {
                menuButtonRef.value?.focus?.();
            }, 50);
        }
    } catch (err) {
        // ignore
    }
});

const isServicesActive = computed(() =>
    serviceLinks.some((link) => isActive(link.href)),
);
const isCompanyActive = computed(() =>
    companyLinks.some((link) => isActive(link.href)),
);

// Magnetic Refs
const loginBtn = ref(null);
const registerBtn = ref(null);
const userBtn = ref(null);

useMagnetic(loginBtn);
useMagnetic(registerBtn);
useMagnetic(userBtn);

const handleScroll = () => {
    isScrolled.value = window.scrollY > 20;
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
        class="min-h-screen flex flex-col bg-white dark:bg-[#080808] transition-colors duration-500 font-manrope selection:bg-blue-500/30"
    >
        <!-- Auth Modals -->
        <LoginModal 
            :show="showLoginModal" 
            @close="showLoginModal = false" 
            @switchToRegister="showLoginModal = false; showRegisterModal = true"
        />
        <RegisterModal 
            :show="showRegisterModal" 
            @close="showRegisterModal = false" 
            @switchToLogin="showRegisterModal = false; showLoginModal = true"
        />

        <!-- WhatsApp Floating Button -->
        <WhatsAppButton />

        <!-- Flash Messages -->
        <FlashMessages />

        <!-- Header (Floating Pill - Solid & Minimal) -->
        <header
            class="fixed top-0 left-0 right-0 z-50 transition-all duration-500 ease-in-out px-4 sm:px-6 lg:px-8"
            :class="isScrolled ? 'pt-4' : 'pt-6'"
        >
            <nav
                class="max-w-7xl mx-auto rounded-full transition-all duration-700 relative bg-white/95 backdrop-blur-2xl shadow-xl shadow-black/5 py-2 px-6 border border-gray-100 mx-2 md:mx-auto mt-4"
                :class="isScrolled ? 'translate-y-0' : 'translate-y-2'"
            >
                <div
                    class="flex items-center justify-between relative z-10 h-full px-4 lg:px-0"
                >
                    <!-- Left Navigation -->
                    <div class="hidden lg:flex items-center space-x-2 flex-1">
                        <!-- Dropdown Layanan -->
                        <div
                            class="relative h-full flex items-center"
                            @mouseenter="hoveredMenu = 'services'"
                            @mouseleave="hoveredMenu = null"
                        >
                            <button
                                class="px-5 py-2.5 rounded-full text-sm font-bold transition-all duration-300 flex items-center gap-2 group"
                                :class="
                                    hoveredMenu === 'services' ||
                                    isServicesActive
                                        ? 'text-blue-600 bg-blue-50 dark:bg-blue-900/10'
                                        : 'text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-white/10'
                                "
                            >
                                Layanan
                                <i
                                    class="fas fa-chevron-down text-[10px] transition-transform duration-300"
                                    :class="{
                                        'rotate-180':
                                            hoveredMenu === 'services',
                                    }"
                                ></i>
                            </button>

                            <!-- Services Mega Menu -->
                            <transition
                                enter-active-class="transition duration-300 ease-out"
                                enter-from-class="opacity-0 translate-y-4 scale-95"
                                enter-to-class="opacity-100 translate-y-0 scale-100"
                                leave-active-class="transition duration-200 ease-in"
                                leave-from-class="opacity-100 translate-y-0 scale-100"
                                leave-to-class="opacity-0 translate-y-4 scale-95"
                            >
                                <div
                                    v-if="hoveredMenu === 'services'"
                                    class="absolute left-0 top-full mt-2 w-[500px] bg-white dark:bg-[#0a0a0a] rounded-[2rem] shadow-2xl border border-gray-100 dark:border-white/10 overflow-hidden z-50 p-2"
                                >
                                    <div class="grid grid-cols-12 gap-2">
                                        <!-- Links List -->
                                        <div class="col-span-12 space-y-1">
                                            <Link
                                                v-for="link in serviceLinks"
                                                :key="link.href"
                                                :href="route(link.href)"
                                                class="flex items-center gap-4 p-4 rounded-2xl hover:bg-gray-50 dark:hover:bg-white/5 transition-all group/link"
                                            >
                                                <div
                                                    class="w-12 h-12 rounded-xl bg-gray-50 dark:bg-[#111] flex items-center justify-center text-gray-400 group-hover/link:bg-blue-600 group-hover/link:text-white transition-all shadow-sm"
                                                >
                                                    <i
                                                        :class="link.icon"
                                                        class="text-lg"
                                                    ></i>
                                                </div>
                                                <div class="flex-1">
                                                    <div
                                                        class="text-sm font-black font-unbounded text-gray-900 dark:text-white group-hover/link:text-blue-600 transition-colors"
                                                    >
                                                        {{ link.name }}
                                                    </div>
                                                    <div
                                                        class="text-xs text-gray-500 dark:text-gray-400 font-manrope font-medium"
                                                    >
                                                        {{ link.note }}
                                                    </div>
                                                </div>
                                                <i
                                                    class="fas fa-arrow-right text-[10px] text-gray-300 group-hover/link:text-blue-600 transform transition-all -translate-x-2 group-hover/link:translate-x-0 opacity-0 group-hover/link:opacity-100"
                                                ></i>
                                            </Link>
                                        </div>
                                    </div>
                                </div>
                            </transition>
                        </div>

                        <Link
                            :href="route('frontend.booking.index')"
                            class="px-5 py-2.5 rounded-full text-sm font-bold transition-all duration-300 bg-[#10207a] text-white hover:bg-[#0c185e] shadow-sm hover:shadow-md"
                        >
                            <i class="fas fa-ticket-alt mr-1.5 text-xs"></i> Pesan Tiket
                        </Link>

                        <Link
                            :href="route('frontend.charter.index')"
                            class="px-5 py-2.5 rounded-full text-sm font-bold transition-all duration-300"
                            :class="
                                isActive('frontend.charter.index')
                                    ? 'text-blue-600 bg-blue-50 dark:bg-blue-900/10'
                                    : 'text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-white/10'
                            "
                        >
                            Pariwisata
                        </Link>
                    </div>

                    <!-- Logo Area -->
                    <div
                        class="lg:absolute lg:left-1/2 lg:top-1/2 lg:-translate-x-1/2 lg:-translate-y-1/2 flex items-center h-full"
                    >
                        <Link
                            :href="route('frontend.home')"
                            aria-label="TUJAGO - Beranda"
                            class="flex items-center space-x-3 group"
                        >
                            <!-- Simple Glow -->
                            <div
                                class="absolute inset-x-0 top-1/2 -translate-y-1/2 bg-blue-500 rounded-full blur-2xl opacity-10 group-hover:opacity-30 transition-opacity w-32 h-32 -z-10 mx-auto"
                            ></div>

                            <!-- Logo Img -->
                            <img
                                src="/img/logoNoBg.png"
                                alt="Logo"
                                class="h-8 w-8 md:h-10 md:w-10 transition-transform duration-500 group-hover:scale-110"
                            />
                            <span
                                class="hidden sm:inline-block text-lg md:text-2xl font-black tracking-tighter font-unbounded leading-none text-gray-900 dark:text-white"
                            >
                                TUJAGO
                            </span>
                        </Link>
                    </div>

                    <!-- Right Navigation -->
                    <div
                        class="hidden lg:flex items-center space-x-2 flex-1 justify-end"
                    >
                        <!-- Dropdown Perusahaan -->
                        <div
                            class="relative h-full flex items-center"
                            @mouseenter="hoveredMenu = 'company'"
                            @mouseleave="hoveredMenu = null"
                        >
                            <button
                                class="px-5 py-2.5 rounded-full text-sm font-bold transition-all duration-300 flex items-center gap-2 group"
                                :class="
                                    hoveredMenu === 'company' || isCompanyActive
                                        ? 'text-blue-600 bg-blue-50 dark:bg-blue-900/10'
                                        : 'text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-white/10'
                                "
                            >
                                Perusahaan
                                <i
                                    class="fas fa-chevron-down text-[10px] transition-transform duration-300"
                                    :class="{
                                        'rotate-180': hoveredMenu === 'company',
                                    }"
                                ></i>
                            </button>

                            <!-- Company Mega Menu -->
                            <transition
                                enter-active-class="transition duration-300 ease-out"
                                enter-from-class="opacity-0 translate-y-4 scale-95"
                                enter-to-class="opacity-100 translate-y-0 scale-100"
                                leave-active-class="transition duration-200 ease-in"
                                leave-from-class="opacity-100 translate-y-0 scale-100"
                                leave-to-class="opacity-0 translate-y-4 scale-95"
                            >
                                <div
                                    v-if="hoveredMenu === 'company'"
                                    class="absolute right-0 top-full mt-2 w-[500px] bg-white dark:bg-[#0a0a0a] rounded-[2rem] shadow-2xl border border-gray-100 dark:border-white/10 overflow-hidden z-50 p-2"
                                >
                                    <div class="grid grid-cols-12 gap-2">
                                        <!-- Feature Card -->
                                        <div
                                            class="col-span-5 p-4 flex flex-col justify-between bg-gray-50 dark:bg-[#111] rounded-2xl relative overflow-hidden group/card"
                                        >
                                            <div
                                                class="absolute inset-0 bg-gradient-to-br from-blue-600/10 to-indigo-600/10 opacity-50"
                                            ></div>
                                            <div class="relative z-10">
                                                <div
                                                    class="text-[10px] font-black text-blue-600 uppercase tracking-widest font-unbounded mb-2"
                                                >
                                                    Sejak 1973
                                                </div>
                                                <div
                                                    class="text-sm font-bold text-gray-900 dark:text-white font-unbounded leading-tight mb-2"
                                                >
                                                    Legenda Transportasi
                                                    Nusantara
                                                </div>
                                                <div
                                                    class="text-[10px] text-gray-500 dark:text-gray-400 font-manrope"
                                                >
                                                    Melayani perjalanan Anda
                                                    dengan hati dan teknologi
                                                    modern.
                                                </div>
                                            </div>
                                            <div class="relative z-10 mt-6">
                                                <Link
                                                    :href="
                                                        route('frontend.about')
                                                    "
                                                    class="text-[10px] font-black text-blue-600 uppercase tracking-widest border-b border-blue-600 pb-0.5 hover:text-blue-500 transition-colors"
                                                    >Lihat Cerita &rarr;</Link
                                                >
                                            </div>
                                        </div>
                                        <!-- Links List -->
                                        <div class="col-span-7 space-y-1">
                                            <Link
                                                v-for="link in companyLinks"
                                                :key="link.href"
                                                :href="route(link.href)"
                                                class="flex items-center gap-4 p-4 rounded-2xl hover:bg-gray-50 dark:hover:bg-white/5 transition-all group/link"
                                            >
                                                <div class="flex-1">
                                                    <div
                                                        class="text-sm font-black font-unbounded text-gray-900 dark:text-white group-hover/link:text-blue-600 transition-colors"
                                                    >
                                                        {{ link.name }}
                                                    </div>
                                                    <div
                                                        class="text-xs text-gray-500 dark:text-gray-400 font-manrope font-medium"
                                                    >
                                                        {{ link.note }}
                                                    </div>
                                                </div>
                                            </Link>
                                        </div>
                                    </div>
                                </div>
                            </transition>
                        </div>

                        <!-- Dark Mode & Auth -->
                        <div
                            class="flex items-center space-x-2 pl-4 border-l border-gray-100 dark:border-white/10 ml-2"
                        >
                            <!-- Auth Buttons -->
                            <template v-if="!page.props.auth.user">
                                <button
                                    ref="loginBtn"
                                    @click="openLoginModal"
                                    class="px-5 py-2.5 text-sm font-bold text-gray-700 dark:text-gray-200 hover:text-blue-600 transition-colors"
                                >
                                    Masuk
                                </button>
                                <button
                                    ref="registerBtn"
                                    @click="openRegisterModal"
                                    class="px-6 py-2.5 bg-gray-900 dark:bg-white text-white dark:text-black text-sm font-bold rounded-full shadow-lg hover:scale-105 transition-all duration-300"
                                >
                                    Daftar
                                </button>
                            </template>
                            <template v-else>
                                <div class="relative group">
                                    <button
                                        ref="userBtn"
                                        class="flex items-center space-x-2 p-1 rounded-full border border-gray-200 dark:border-white/10 hover:border-blue-600 transition-all duration-300"
                                    >
                                        <div
                                            class="w-8 h-8 rounded-full bg-blue-600 flex items-center justify-center text-white text-xs font-black font-unbounded"
                                        >
                                            {{
                                                page.props.auth.user.name
                                                    .charAt(0)
                                                    .toUpperCase()
                                            }}
                                        </div>
                                    </button>
                                    <!-- User Dropdown (Small & Clean) -->
                                    <div
                                        class="absolute right-0 mt-4 w-56 py-2 bg-white dark:bg-[#111] rounded-2xl shadow-xl border border-gray-100 dark:border-white/10 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 transform origin-top-right scale-95 group-hover:scale-100 z-50 overflow-hidden"
                                    >
                                        <div
                                            class="px-4 py-3 border-b border-gray-50 dark:border-white/5 mb-1"
                                        >
                                            <p
                                                class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mb-0.5"
                                            >
                                                Hai,
                                            </p>
                                            <p
                                                class="text-xs font-black text-gray-900 dark:text-white truncate font-unbounded"
                                            >
                                                {{
                                                    page.props.auth.user.name.split(
                                                        " ",
                                                    )[0]
                                                }}
                                            </p>
                                        </div>
                                        <Link
                                            :href="route('profile.edit')"
                                            class="flex items-center px-4 py-2.5 text-xs font-bold text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-white/5 hover:text-blue-600 transition-colors"
                                            ><i
                                                class="fas fa-user w-4 mr-2 opacity-50"
                                            ></i
                                            >Profil Saya</Link
                                        >
                                        <Link
                                            :href="
                                                route('booking-history.index')
                                            "
                                            class="flex items-center px-4 py-2.5 text-xs font-bold text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-white/5 hover:text-blue-600 transition-colors"
                                            ><i
                                                class="fas fa-history w-4 mr-2 opacity-50"
                                            ></i
                                            >Riwayat</Link
                                        >
                                        <Link
                                            v-if="
                                                page.props.auth.user.role ===
                                                'admin'
                                            "
                                            :href="route('admin.dashboard')"
                                            class="flex items-center px-4 py-2.5 text-xs font-black text-indigo-600 dark:text-indigo-400 hover:bg-indigo-50 dark:hover:bg-indigo-900/10 transition-colors font-unbounded"
                                            ><i
                                                class="fas fa-cog w-4 mr-2 opacity-50"
                                            ></i
                                            >Panel Admin</Link
                                        >
                                        <div
                                            class="border-t border-gray-50 dark:border-white/5 my-1"
                                        ></div>
                                        <Link
                                            :href="route('logout')"
                                            method="post"
                                            as="button"
                                            class="w-full text-left flex items-center px-4 py-2.5 text-xs font-bold text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/10 transition-colors font-unbounded"
                                            ><i
                                                class="fas fa-sign-out-alt w-4 mr-2 opacity-50"
                                            ></i
                                            >Keluar</Link
                                        >
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>

                    <!-- Mobile Header Actions -->
                    <div class="flex lg:hidden items-center space-x-2">
                        <!-- User Profile/Login (Mobile) -->
                        <template v-if="page.props.auth.user">
                            <Link
                                :href="route('profile.edit')"
                                aria-label="Profile"
                                class="w-11 h-11 rounded-full bg-blue-600 flex items-center justify-center text-white text-[12px] font-black font-unbounded"
                            >
                                {{
                                    page.props.auth.user.name
                                        .charAt(0)
                                        .toUpperCase()
                                }}
                            </Link>
                        </template>
                        <template v-else>
                            <button
                                @click="openLoginModal"
                                aria-label="Login"
                                class="w-11 h-11 rounded-full border border-gray-100 dark:border-white/5 flex items-center justify-center text-gray-500 dark:text-gray-400"
                            >
                                <i class="fas fa-user text-xs"></i>
                            </button>
                        </template>

                        <!-- Mobile Menu Trigger -->
                        <button
                            ref="menuButton"
                            @click="toggleMobileMenu"
                            :aria-expanded="mobileMenuOpen"
                            aria-controls="mobile-menu"
                            aria-label="Toggle menu"
                            class="w-12 h-12 rounded-xl bg-gray-900 dark:bg-white text-white dark:text-gray-900 flex items-center justify-center shadow-lg active:scale-95 transition-all"
                        >
                            <i
                                :class="
                                    mobileMenuOpen
                                        ? 'fas fa-times'
                                        : 'fas fa-bars'
                                "
                                class="text-sm"
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
                        id="mobile-menu"
                        ref="mobileMenuRef"
                        tabindex="-1"
                        class="lg:hidden absolute left-0 right-0 top-full mt-4 mx-2 bg-white/95 dark:bg-[#0a0a0a]/95 md:backdrop-blur-2xl backdrop-blur-sm rounded-[2.5rem] shadow-[0_32px_64px_-16px_rgba(0,0,0,0.2)] border border-gray-100 dark:border-white/10 overflow-hidden z-50 transition-all duration-300"
                    >
                            <div class="p-6 space-y-6 max-h-[80vh] overflow-y-auto">
                                <!-- Mobile CTA -->
                                <Link :href="route('frontend.booking.index')" @click="mobileMenuOpen = false"
                                    class="block w-full text-center py-4 bg-[#10207a] text-white rounded-[14px] font-bold text-sm hover:bg-[#0c185e] transition-all shadow-lg shadow-[#10207a]/20">
                                    <i class="fas fa-ticket-alt mr-2"></i> Pesan Tiket Sekarang
                                </Link>
                                <!-- Mobile Menu Header Info (Quick Stats/Welcome) -->
                            <div
                                class="p-6 bg-gradient-to-br from-blue-600 to-blue-700 rounded-[2rem] text-white flex items-center justify-between shadow-xl shadow-blue-600/20"
                            >
                                <div>
                                    <div
                                        class="text-[10px] font-black uppercase tracking-[0.2em] opacity-80 mb-1"
                                    >
                                        Tunggal Jaya
                                    </div>
                                    <div
                                        class="text-sm font-black font-unbounded leading-none"
                                    >
                                        Premium <br />Transport
                                    </div>
                                </div>
                                <div
                                    class="w-12 h-12 rounded-2xl bg-white/20 backdrop-blur-md flex items-center justify-center"
                                >
                                    <i class="fas fa-bus text-xl"></i>
                                </div>
                            </div>
                            <div class="mb-2">
                                <button
                                    @click="mobileServicesOpen = !mobileServicesOpen"
                                    class="w-full flex items-center justify-between px-6 py-3 group"
                                >
                                    <h3 class="text-[10px] font-black text-blue-600 uppercase tracking-widest font-unbounded text-left mb-0">
                                        Layanan
                                    </h3>
                                    <div class="w-8 h-8 rounded-full bg-gray-50 dark:bg-white/5 flex items-center justify-center text-blue-600 group-hover:bg-blue-50 dark:group-hover:bg-blue-900/20 transition-colors">
                                        <i :class="mobileServicesOpen ? 'fas fa-chevron-up' : 'fas fa-chevron-down'" class="text-[10px] transition-transform duration-300"></i>
                                    </div>
                                </button>
                                <div v-show="mobileServicesOpen" class="space-y-1 px-2 pb-2 origin-top">
                                    <Link
                                        v-for="link in serviceLinks"
                                        :key="link.href"
                                        :href="route(link.href)"
                                        @click="mobileMenuOpen = false"
                                        class="flex items-center gap-4 px-6 py-4 rounded-2xl text-sm font-bold transition-all duration-200"
                                        :class="
                                            isActive(link.href)
                                                ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/20'
                                                : 'text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-white/5'
                                        "
                                    >
                                        <i
                                            :class="link.icon"
                                            class="text-lg opacity-70"
                                        ></i>
                                        {{ link.name }}
                                    </Link>
                                </div>
                            </div>

                            <div class="mb-2">
                                <button
                                    @click="mobileCompanyOpen = !mobileCompanyOpen"
                                    class="w-full flex items-center justify-between px-6 py-3 group"
                                >
                                    <h3 class="text-[10px] font-black text-blue-600 uppercase tracking-widest font-unbounded text-left mb-0">
                                        Perusahaan
                                    </h3>
                                    <div class="w-8 h-8 rounded-full bg-gray-50 dark:bg-white/5 flex items-center justify-center text-blue-600 group-hover:bg-blue-50 dark:group-hover:bg-blue-900/20 transition-colors">
                                        <i :class="mobileCompanyOpen ? 'fas fa-chevron-up' : 'fas fa-chevron-down'" class="text-[10px] transition-transform duration-300"></i>
                                    </div>
                                </button>
                                <div v-show="mobileCompanyOpen" class="space-y-1 px-2 pb-2 origin-top">
                                    <Link
                                        v-for="link in companyLinks"
                                        :key="link.href"
                                        :href="route(link.href)"
                                        @click="mobileMenuOpen = false"
                                        class="flex items-center gap-4 px-6 py-4 rounded-2xl text-sm font-bold transition-all duration-200"
                                        :class="
                                            isActive(link.href)
                                                ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/20'
                                                : 'text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-white/5'
                                        "
                                    >
                                        <i
                                            :class="link.icon"
                                            class="text-lg opacity-70"
                                        ></i>
                                        {{ link.name }}
                                    </Link>
                                </div>
                            </div>

                            <div v-if="page.props.auth.user" class="mb-4">
                                <h3
                                    class="px-6 text-[10px] font-black text-blue-600 uppercase tracking-widest font-unbounded mb-4"
                                >
                                    Akun Saya
                                </h3>
                                <div class="grid grid-cols-2 gap-2 px-4">
                                    <Link
                                        :href="route('profile.edit')"
                                        @click="mobileMenuOpen = false"
                                        class="flex items-center gap-3 p-4 rounded-2xl bg-gray-50 dark:bg-white/5 text-[11px] font-bold text-gray-700 dark:text-gray-200"
                                    >
                                        <i
                                            class="fas fa-user-circle opacity-50"
                                        ></i>
                                        Profil
                                    </Link>
                                    <Link
                                        :href="route('booking-history.index')"
                                        @click="mobileMenuOpen = false"
                                        class="flex items-center gap-3 p-4 rounded-2xl bg-gray-50 dark:bg-white/5 text-[11px] font-bold text-gray-700 dark:text-gray-200"
                                    >
                                        <i
                                            class="fas fa-history opacity-50"
                                        ></i>
                                        Riwayat
                                    </Link>
                                    <Link
                                        v-if="
                                            page.props.auth.user.role ===
                                            'admin'
                                        "
                                        :href="route('admin.dashboard')"
                                        @click="mobileMenuOpen = false"
                                        class="col-span-2 flex items-center gap-3 p-4 rounded-2xl bg-indigo-50 dark:bg-indigo-900/10 text-[11px] font-black text-indigo-600 dark:text-indigo-400 font-unbounded"
                                    >
                                        <i class="fas fa-cog opacity-50"></i>
                                        Panel Admin
                                    </Link>
                                    <Link
                                        :href="route('logout')"
                                        method="post"
                                        as="button"
                                        class="col-span-2 flex items-center gap-3 p-4 rounded-2xl bg-blue-50 dark:bg-blue-900/10 text-[11px] font-black text-blue-600 dark:text-blue-400 font-unbounded"
                                    >
                                        <i
                                            class="fas fa-sign-out-alt opacity-50"
                                        ></i>
                                        Keluar
                                    </Link>
                                </div>
                            </div>

                            <div
                                class="border-t border-gray-100 dark:border-white/10 my-4 pt-2"
                            ></div>

                            <template v-if="!page.props.auth.user">
                                <div class="grid grid-cols-2 gap-3 px-2 pb-4">
                                    <button
                                        @click="openLoginModal"
                                        class="py-4 w-full text-center text-sm font-bold text-gray-900 dark:text-white bg-gray-50 dark:bg-white/5 rounded-[1.5rem]"
                                    >
                                        Masuk
                                    </button>
                                    <button
                                        @click="openRegisterModal"
                                        class="py-4 w-full bg-blue-600 text-white text-center text-sm font-bold rounded-[1.5rem] shadow-lg shadow-blue-600/20"
                                    >
                                        Daftar
                                    </button>
                                </div>
                            </template>
                        </div>
                    </div>
                </transition>
            </nav>
        </header>

        <!-- Phone Verification Banner (Floating Snackbar Concept) -->
        <Transition
            enter-active-class="transition duration-500 ease-out"
            enter-from-class="opacity-0 translate-y-10 scale-95"
            enter-to-class="opacity-100 translate-y-0 scale-100"
            leave-active-class="transition duration-300 ease-in"
            leave-from-class="opacity-100 translate-y-0 scale-100"
            leave-to-class="opacity-0 translate-y-10 scale-95"
        >
            <div
                v-if="showVerificationBanner"
                class="fixed bottom-6 right-6 z-[100] w-[calc(100%-3rem)] sm:w-[450px] overflow-hidden rounded-3xl bg-white/80 dark:bg-black/80 backdrop-blur-2xl border border-amber-200/50 dark:border-amber-500/20 shadow-[0_20px_50px_-12px_rgba(245,158,11,0.3)] group"
            >
                <!-- Shimmer & Pulse Glow -->
                <div
                    class="absolute inset-0 bg-gradient-to-br from-amber-500/5 to-blue-500/5 pointer-events-none"
                ></div>
                <div
                    class="absolute -top-24 -right-24 w-48 h-48 bg-amber-500/10 rounded-full blur-3xl animate-pulse"
                ></div>

                <div class="p-5 sm:p-6 relative">
                    <div class="flex items-start gap-5">
                        <!-- Icon Shield -->
                        <div class="relative shrink-0 mt-1">
                            <div
                                class="absolute inset-0 bg-amber-500/20 rounded-2xl animate-ping opacity-75"
                            ></div>
                            <div
                                class="relative w-12 h-12 bg-gradient-to-br from-amber-500 to-orange-600 rounded-2xl flex items-center justify-center shadow-lg shadow-amber-500/40"
                            >
                                <i
                                    class="fas fa-shield-halved text-white text-xl"
                                ></i>
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="flex-1 min-w-0">
                            <div
                                class="flex items-center justify-between mb-1.5"
                            >
                                <span
                                    class="text-[10px] font-black uppercase tracking-[0.2em] font-unbounded text-amber-600 dark:text-amber-500"
                                >
                                    Verifikasi Diperlukan
                                </span>
                                <button
                                    @click="dismissVerificationBanner"
                                    class="text-gray-400 hover:text-blue-600 transition-colors p-1"
                                    aria-label="Tutup"
                                >
                                    <i class="fas fa-times text-sm"></i>
                                </button>
                            </div>
                            <h4
                                class="text-sm font-black font-unbounded text-gray-900 dark:text-white mb-1.5 leading-snug"
                            >
                                Tingkatkan Keamanan Akun Anda
                            </h4>
                            <p
                                class="text-xs font-semibold font-manrope text-gray-500 dark:text-gray-400 leading-relaxed mb-4"
                            >
                                Nomor HP Anda belum diverifikasi. Selesaikan
                                verifikasi sekarang untuk membuka fitur
                                pemesanan tiket.
                            </p>

                            <!-- Action Link -->
                            <Link
                                :href="route('verification.phone.show')"
                                class="inline-flex items-center justify-center gap-2 w-full sm:w-auto px-6 py-2.5 bg-gray-900 dark:bg-white text-white dark:text-black text-[10px] font-black uppercase tracking-widest font-unbounded rounded-xl hover:bg-blue-600 dark:hover:bg-blue-600 hover:text-white transition-all duration-300 shadow-md active:scale-95"
                            >
                                <i class="fas fa-arrow-right text-[9px]"></i>
                                Verifikasi Sekarang
                            </Link>
                        </div>
                    </div>
                </div>

                <!-- Progress Line Decoration -->
                <div
                    class="h-1 w-full bg-gray-100 dark:bg-white/5 overflow-hidden"
                >
                    <div
                        class="h-full bg-gradient-to-r from-amber-500 to-blue-600 w-1/3 animate-shimmer-progress"
                    ></div>
                </div>
            </div>
        </Transition>

        <!-- Main Content -->
        <main class="flex-grow relative">

            <!-- Page Content -->
            <Transition
                name="page-fade"
                mode="out-in"
                @before-enter="onBeforeEnter"
                @enter="onEnter"
                @leave="onLeave"
            >
                <div :key="pageKey">
                    <slot />
                </div>
            </Transition>
        </main>

        <!-- Footer (Solid & Clean) -->
        <footer
            class="bg-white dark:bg-[#080808] text-gray-900 dark:text-white border-t border-gray-100 dark:border-[#111] transition-colors duration-300 relative overflow-hidden"
        >
            <!-- Background Subtle Elements -->
            <div
                class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-transparent via-blue-600 to-transparent opacity-50"
            ></div>
            <div
                class="absolute -bottom-24 -right-24 w-96 h-96 bg-blue-50 dark:bg-blue-900/5 rounded-full blur-3xl opacity-50 pointer-events-none"
            ></div>

            <div
                class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 relative z-10"
            >
                <div
                    class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-12 lg:gap-8"
                >
                    <!-- Brand Column (Larger) -->
                    <div class="lg:col-span-4">
                        <Link
                            :href="route('frontend.home')"
                            class="flex items-center space-x-4 mb-8 group"
                        >
                            <div
                                class="relative w-16 h-16 flex items-center justify-center bg-gray-50 dark:bg-[#151515] rounded-2xl border border-gray-100 dark:border-[#222] group-hover:border-blue-100 dark:group-hover:border-blue-900/20 transition-all duration-500 overflow-hidden"
                            >
                                <!-- Logo -->
                                <img
                                    src="/img/logoNoBg.png"
                                    alt="TUJAGO Logo"
                                    class="w-12 h-12 object-contain relative z-10 transition-transform duration-500 group-hover:scale-110 drop-shadow-sm"
                                />
                                <div
                                    class="absolute inset-0 bg-blue-600/5 opacity-0 group-hover:opacity-100 transition-opacity"
                                ></div>
                            </div>
                            <div class="flex flex-col">
                                <span
                                    class="text-3xl font-black font-unbounded leading-none tracking-tight"
                                    >TUJAGO</span
                                >
                                <span
                                    class="text-[0.65rem] font-bold text-gray-400 dark:text-gray-500 tracking-[0.3em] uppercase mt-1"
                                    >Tunggal Jaya Go</span
                                >
                            </div>
                        </Link>
                        <p
                            class="text-gray-500 dark:text-gray-400 mb-8 leading-relaxed text-base font-medium pr-6"
                        >
                            Menghubungkan kota, menyatukan cerita. Partner
                            perjalanan premium Anda dengan standar keselamatan
                            tertinggi dan kenyamanan tanpa kompromi sejak 1973.
                        </p>
                        <div class="flex space-x-3">
                            <a
                                v-for="social in [
                                    'facebook-f',
                                    'twitter',
                                    'instagram',
                                    'linkedin-in',
                                ]"
                                href="#"
                                class="w-12 h-12 rounded-full bg-gray-50 dark:bg-[#151515] border border-gray-100 dark:border-[#222] flex items-center justify-center text-gray-400 hover:bg-blue-600 hover:border-blue-600 hover:text-white transition-all duration-300 shadow-sm hover:shadow-lg hover:shadow-blue-600/20"
                            >
                                <i :class="`fab fa-${social} text-lg`"></i>
                            </a>
                        </div>
                    </div>

                    <!-- Company -->
                    <div class="lg:col-span-2">
                        <h3 class="text-lg font-bold font-unbounded mb-8 flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-blue-600"></span>
                            COMPANY
                        </h3>
                        <ul class="space-y-4 text-sm font-medium text-gray-500">
                            <li v-for="item in ['Tentang Kami', 'Karir', 'Berita', 'Kontak']" :key="item">
                                <a href="#" class="hover:text-blue-600 transition-colors flex items-center gap-2 group">
                                    <span class="w-0 overflow-hidden transition-all duration-300 group-hover:w-3 text-blue-600"><i class="fas fa-arrow-right text-[10px]"></i></span>
                                    {{ item }}
                                </a>
                            </li>
                        </ul>
                    </div>

                    <!-- Services -->
                    <div class="lg:col-span-3">
                        <h3 class="text-lg font-bold font-unbounded mb-8 flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-blue-600"></span>
                            SERVICES
                        </h3>
                        <ul class="space-y-4 text-sm font-medium text-gray-500 dark:text-gray-400">
                            <li v-for="item in ['Sewa Bus Pariwisata', 'Tiket Reguler AKAP', 'Layanan Logistik', 'Paket Wisata']" :key="item">
                                <a href="#" class="hover:text-blue-600 dark:hover:text-blue-500 transition-colors flex items-center gap-2 group">
                                    <span class="w-0 overflow-hidden transition-all duration-300 group-hover:w-3 text-blue-600"><i class="fas fa-arrow-right text-[10px]"></i></span>
                                    {{ item }}
                                </a>
                            </li>
                        </ul>
                    </div>

                    <!-- Help -->
                    <div class="lg:col-span-3">
                        <h3 class="text-lg font-bold font-unbounded mb-8 flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-blue-600"></span>
                            HELP
                        </h3>
                        <ul class="space-y-4 text-sm font-medium text-gray-500 dark:text-gray-400">
                            <li v-for="item in ['Pusat Bantuan', 'Syarat & Ketentuan', 'Kebijakan Privasi', 'FAQ']" :key="item">
                                <a href="#" class="hover:text-blue-600 dark:hover:text-blue-500 transition-colors flex items-center gap-2 group">
                                    <span class="w-0 overflow-hidden transition-all duration-300 group-hover:w-3 text-blue-600"><i class="fas fa-arrow-right text-[10px]"></i></span>
                                    {{ item }}
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>

                <div
                    class="border-t border-gray-100 dark:border-white/5 mt-20 pt-8 flex flex-col md:flex-row justify-between items-center text-gray-400 text-sm font-medium"
                >
                    <p>
                        &copy; {{ new Date().getFullYear() }}
                        <strong class="text-gray-900 dark:text-white"
                            >TUJAGO</strong
                        >. Hak Cipta Dilindungi.
                    </p>
                    <div class="flex space-x-8 mt-4 md:mt-0">
                        <a
                            href="#"
                            class="hover:text-blue-600 transition-colors"
                            >Privasi</a
                        >
                        <a
                            href="#"
                            class="hover:text-blue-600 transition-colors"
                            >Syarat & Ketentuan</a
                        >
                        <a
                            href="#"
                            class="hover:text-blue-600 transition-colors"
                            >Cookie</a
                        >
                    </div>
                </div>
            </div>
        </footer>
    </div>
</template>
