<script setup>
import { ref, onMounted, computed } from "vue";
import { Link, usePage } from "@inertiajs/vue3";
import FrontendLayout from "@/Layouts/FrontendLayout.vue";

defineOptions({ layout: FrontendLayout });

const props = defineProps({
    featuredRoutes: Array,
    latestNews: Array,
    fleet: Array,
    fleetCount: Number,
    routeCount: Number,
    customerCount: Number,
    origins: Array,
    destinations: Array,
    personalizedRecommendations: Array,
});

const page = usePage();

// Booking form state
const origin = ref("");
const destination = ref("");
const date = ref("");
const busType = ref("all");
const showOriginDropdown = ref(false);
const showDestinationDropdown = ref(false);
const filteredOrigins = ref([]);
const filteredDestinations = ref([]);

// Counter animation
const displayFleetCount = ref(0);
const displayRouteCount = ref(0);
const displayCustomerCount = ref(0);
const statsVisible = ref(false);

// Today's date for min date
const today = computed(() => {
    const now = new Date();
    return now.toISOString().split("T")[0];
});

const busTypes = [
    { id: "all", name: "Semua Tipe", icon: "fas fa-bus" },
    { id: "economy", name: "Ekonomi", icon: "fas fa-coins" },
    { id: "business", name: "Bisnis", icon: "fas fa-briefcase" },
    { id: "executive", name: "Eksekutif", icon: "fas fa-crown" },
];

const features = [
    {
        icon: "fas fa-couch",
        title: "Kursi Premium",
        description:
            "Desain kursi ergonomis dengan leg room luas untuk kenyamanan maksimal",
        gradient: "from-blue-500 to-indigo-600",
        bgGradient: "from-blue-50 to-indigo-50",
    },
    {
        icon: "fas fa-tv",
        title: "Hiburan Modern",
        description:
            "TV LCD layar lebar & sistem audio surround untuk perjalanan menyenangkan",
        gradient: "from-purple-500 to-pink-600",
        bgGradient: "from-purple-50 to-pink-50",
    },
    {
        icon: "fas fa-wifi",
        title: "WiFi Gratis",
        description:
            "Internet berkecepatan tinggi gratis untuk tetap produktif",
        gradient: "from-cyan-500 to-blue-600",
        bgGradient: "from-cyan-50 to-blue-50",
    },
    {
        icon: "fas fa-snowflake",
        title: "AC Premium",
        description:
            "Sistem pendingin udara canggih dengan kontrol suhu optimal",
        gradient: "from-emerald-500 to-teal-600",
        bgGradient: "from-emerald-50 to-teal-50",
    },
];

const filterOrigins = () => {
    if (!origin.value) {
        filteredOrigins.value = props.origins || [];
    } else {
        filteredOrigins.value = (props.origins || []).filter((o) =>
            o.toLowerCase().includes(origin.value.toLowerCase())
        );
    }
    showOriginDropdown.value = true;
};

const filterDestinations = () => {
    if (!destination.value) {
        filteredDestinations.value = props.destinations || [];
    } else {
        filteredDestinations.value = (props.destinations || []).filter((d) =>
            d.toLowerCase().includes(destination.value.toLowerCase())
        );
    }
    showDestinationDropdown.value = true;
};

const selectOrigin = (value) => {
    origin.value = value;
    showOriginDropdown.value = false;
};

const selectDestination = (value) => {
    destination.value = value;
    showDestinationDropdown.value = false;
};

const swapLocations = () => {
    const temp = origin.value;
    origin.value = destination.value;
    destination.value = temp;
};

const animateCounter = (target, end, duration = 2500) => {
    const startTime = performance.now();

    const update = (currentTime) => {
        const elapsed = currentTime - startTime;
        const progress = Math.min(elapsed / duration, 1);
        const easeOut = 1 - Math.pow(1 - progress, 4);

        if (target === "fleet")
            displayFleetCount.value = Math.floor(easeOut * end);
        if (target === "route")
            displayRouteCount.value = Math.floor(easeOut * end);
        if (target === "customer")
            displayCustomerCount.value = Math.floor(easeOut * end);

        if (progress < 1) {
            requestAnimationFrame(update);
        }
    };

    requestAnimationFrame(update);
};

onMounted(() => {
    filteredOrigins.value = props.origins || [];
    filteredDestinations.value = props.destinations || [];

    // Intersection observer for stats counter
    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting && !statsVisible.value) {
                    statsVisible.value = true;
                    setTimeout(
                        () => animateCounter("fleet", props.fleetCount || 25),
                        100
                    );
                    setTimeout(
                        () => animateCounter("route", props.routeCount || 50),
                        300
                    );
                    setTimeout(
                        () =>
                            animateCounter(
                                "customer",
                                props.customerCount || 10000
                            ),
                        500
                    );
                }
            });
        },
        { threshold: 0.3 }
    );

    const statsSection = document.getElementById("stats-section");
    if (statsSection) observer.observe(statsSection);

    // Close dropdowns on outside click
    document.addEventListener("click", (e) => {
        if (!e.target.closest(".origin-input"))
            showOriginDropdown.value = false;
        if (!e.target.closest(".destination-input"))
            showDestinationDropdown.value = false;
    });
});

const formatNumber = (num) => {
    return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
};
</script>

<template>
    <Head title="Beranda" />

    <!-- Hero Section -->
    <section class="relative min-h-screen flex items-center overflow-hidden">
        <!-- Animated Background -->
        <div class="absolute inset-0 gradient-hero">
            <!-- Animated blobs -->
            <div
                class="blob w-96 h-96 bg-blue-500/30 top-20 -left-20"
                style="animation-delay: 0s"
            ></div>
            <div
                class="blob w-80 h-80 bg-purple-500/20 bottom-20 right-10"
                style="animation-delay: 2s"
            ></div>
            <div
                class="blob w-64 h-64 bg-cyan-500/20 top-1/2 left-1/3"
                style="animation-delay: 4s"
            ></div>

            <!-- Pattern overlay -->
            <div class="absolute inset-0 hero-pattern"></div>

            <!-- Background image with overlay -->
            <img
                src="/img/heroImg.jpg"
                alt="Hero Background"
                class="absolute inset-0 w-full h-full object-cover opacity-20 mix-blend-overlay"
            />

            <!-- Gradient overlay -->
            <div
                class="absolute inset-0 bg-gradient-to-b from-transparent via-[#0c1445]/50 to-[#0c1445]"
            ></div>
        </div>

        <!-- Content -->
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-32">
            <div class="max-w-3xl">
                <!-- Badge -->
                <div
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/10 backdrop-blur-sm border border-white/20 mb-6 animate-fade-in-down"
                >
                    <span
                        class="w-2 h-2 rounded-full bg-green-400 animate-pulse"
                    ></span>
                    <span class="text-sm font-medium text-white/90"
                        >Layanan 24/7 Tersedia</span
                    >
                </div>

                <!-- Title -->
                <h1
                    class="hero-title text-4xl md:text-5xl lg:text-6xl font-extrabold text-white mb-6 leading-tight animate-fade-in-down stagger-1"
                >
                    Perjalanan
                    <span class="text-gradient-gold">Premium</span> dengan
                    Kenyamanan <span class="text-gradient-ocean">Maksimal</span>
                </h1>

                <!-- Subtitle -->
                <p
                    class="text-lg md:text-xl text-blue-100/90 mb-10 max-w-2xl leading-relaxed animate-fade-in-up stagger-2"
                >
                    Rasakan pengalaman perjalanan bus terbaik dengan armada
                    modern, sopir profesional, dan pelayanan bintang 5.
                </p>

                <!-- CTA Buttons -->
                <div class="flex flex-wrap gap-4 animate-fade-in-up stagger-3">
                    <Link
                        :href="route('frontend.booking.index')"
                        class="btn-premium animate-pulse-glow"
                    >
                        <span class="flex items-center gap-2">
                            <i class="fas fa-ticket-alt"></i>
                            Pesan Tiket Sekarang
                        </span>
                    </Link>
                    <Link
                        :href="route('frontend.fleet.index')"
                        class="btn-secondary-premium"
                    >
                        <i class="fas fa-bus mr-2"></i>
                        Jelajahi Armada
                    </Link>
                </div>

                <!-- Trust badges -->
                <div
                    class="flex flex-wrap items-center gap-6 mt-12 animate-fade-in-up stagger-4"
                >
                    <div class="flex items-center gap-2 text-white/80">
                        <i class="fas fa-shield-alt text-green-400"></i>
                        <span class="text-sm">Aman & Terpercaya</span>
                    </div>
                    <div class="flex items-center gap-2 text-white/80">
                        <i class="fas fa-clock text-blue-400"></i>
                        <span class="text-sm">Tepat Waktu</span>
                    </div>
                    <div class="flex items-center gap-2 text-white/80">
                        <i class="fas fa-star text-yellow-400"></i>
                        <span class="text-sm">Rating 4.9/5</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Scroll indicator -->
        <div
            class="absolute bottom-8 left-1/2 transform -translate-x-1/2 animate-bounce-soft"
        >
            <div
                class="w-8 h-14 rounded-full border-2 border-white/40 flex items-start justify-center p-2"
            >
                <div class="w-1.5 h-3 bg-white/60 rounded-full"></div>
            </div>
            <span class="block text-center text-xs text-white/50 mt-2"
                >Scroll</span
            >
        </div>
    </section>

    <!-- Booking Form Section -->
    <section
        class="relative z-20 -mt-28 max-w-6xl mx-auto px-4 sm:px-6 lg:px-8"
    >
        <div
            class="glass-white rounded-3xl shadow-2xl p-8 md:p-10 border border-white/50"
        >
            <!-- Form Header -->
            <div class="text-center mb-8">
                <div
                    class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600 mb-4"
                >
                    <i class="fas fa-search text-white text-xl"></i>
                </div>
                <h2
                    class="text-2xl md:text-3xl font-bold text-gray-800 dark:text-white"
                >
                    Cari Jadwal Perjalanan
                </h2>
                <p class="text-gray-500 dark:text-gray-400 mt-2">
                    Temukan tiket bus untuk tujuan Anda
                </p>
            </div>

            <form
                :action="route('frontend.booking.index')"
                method="GET"
                class="space-y-6"
            >
                <div class="grid grid-cols-1 md:grid-cols-12 gap-4 md:gap-6">
                    <!-- Origin -->
                    <div class="md:col-span-3 origin-input relative">
                        <label
                            class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2"
                        >
                            <i
                                class="fas fa-map-marker-alt text-indigo-500 mr-2"
                            ></i
                            >Kota Asal
                        </label>
                        <div class="relative">
                            <input
                                v-model="origin"
                                @input="filterOrigins"
                                @focus="showOriginDropdown = true"
                                name="origin"
                                type="text"
                                placeholder="Pilih kota asal..."
                                class="input-premium"
                                autocomplete="off"
                            />
                            <i
                                class="fas fa-map-marker-alt absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"
                            ></i>
                        </div>
                        <!-- Dropdown -->
                        <transition
                            enter-active-class="transition duration-200 ease-out"
                            enter-from-class="opacity-0 scale-95"
                            enter-to-class="opacity-100 scale-100"
                            leave-active-class="transition duration-150 ease-in"
                            leave-from-class="opacity-100 scale-100"
                            leave-to-class="opacity-0 scale-95"
                        >
                            <div
                                v-if="
                                    showOriginDropdown && filteredOrigins.length
                                "
                                class="absolute z-50 w-full mt-2 bg-white dark:bg-gray-800 rounded-xl shadow-xl border border-gray-100 dark:border-gray-700 max-h-56 overflow-y-auto"
                            >
                                <div
                                    v-for="item in filteredOrigins"
                                    :key="item"
                                    @click="selectOrigin(item)"
                                    class="px-4 py-3 cursor-pointer hover:bg-indigo-50 dark:hover:bg-indigo-900/30 text-gray-700 dark:text-gray-200 flex items-center gap-3 transition-colors"
                                >
                                    <i
                                        class="fas fa-location-dot text-indigo-500"
                                    ></i>
                                    {{ item }}
                                </div>
                            </div>
                        </transition>
                    </div>

                    <!-- Swap Button -->
                    <div
                        class="hidden md:flex md:col-span-1 items-end justify-center pb-3"
                    >
                        <button
                            type="button"
                            @click="swapLocations"
                            class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 text-white flex items-center justify-center hover:scale-110 transition-transform shadow-lg"
                        >
                            <i class="fas fa-exchange-alt"></i>
                        </button>
                    </div>

                    <!-- Destination -->
                    <div class="md:col-span-3 destination-input relative">
                        <label
                            class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2"
                        >
                            <i
                                class="fas fa-flag-checkered text-purple-500 mr-2"
                            ></i
                            >Kota Tujuan
                        </label>
                        <div class="relative">
                            <input
                                v-model="destination"
                                @input="filterDestinations"
                                @focus="showDestinationDropdown = true"
                                name="destination"
                                type="text"
                                placeholder="Pilih kota tujuan..."
                                class="input-premium"
                                autocomplete="off"
                            />
                            <i
                                class="fas fa-flag-checkered absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"
                            ></i>
                        </div>
                        <!-- Dropdown -->
                        <transition
                            enter-active-class="transition duration-200 ease-out"
                            enter-from-class="opacity-0 scale-95"
                            enter-to-class="opacity-100 scale-100"
                            leave-active-class="transition duration-150 ease-in"
                            leave-from-class="opacity-100 scale-100"
                            leave-to-class="opacity-0 scale-95"
                        >
                            <div
                                v-if="
                                    showDestinationDropdown &&
                                    filteredDestinations.length
                                "
                                class="absolute z-50 w-full mt-2 bg-white dark:bg-gray-800 rounded-xl shadow-xl border border-gray-100 dark:border-gray-700 max-h-56 overflow-y-auto"
                            >
                                <div
                                    v-for="item in filteredDestinations"
                                    :key="item"
                                    @click="selectDestination(item)"
                                    class="px-4 py-3 cursor-pointer hover:bg-purple-50 dark:hover:bg-purple-900/30 text-gray-700 dark:text-gray-200 flex items-center gap-3 transition-colors"
                                >
                                    <i
                                        class="fas fa-location-dot text-purple-500"
                                    ></i>
                                    {{ item }}
                                </div>
                            </div>
                        </transition>
                    </div>

                    <!-- Date -->
                    <div class="md:col-span-2">
                        <label
                            class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2"
                        >
                            <i class="fas fa-calendar text-cyan-500 mr-2"></i
                            >Tanggal Berangkat
                        </label>
                        <div class="relative">
                            <input
                                v-model="date"
                                name="date"
                                type="date"
                                :min="today"
                                class="input-premium"
                            />
                            <i
                                class="fas fa-calendar-alt absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"
                            ></i>
                        </div>
                    </div>

                    <!-- Bus Type -->
                    <div class="md:col-span-2">
                        <label
                            class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2"
                        >
                            <i class="fas fa-bus text-emerald-500 mr-2"></i>Tipe
                            Bus
                        </label>
                        <div class="relative">
                            <select
                                v-model="busType"
                                name="bus_type"
                                class="input-premium appearance-none cursor-pointer"
                            >
                                <option
                                    v-for="type in busTypes"
                                    :key="type.id"
                                    :value="type.id"
                                >
                                    {{ type.name }}
                                </option>
                            </select>
                            <i
                                class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none"
                            ></i>
                            <i
                                class="fas fa-bus absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"
                            ></i>
                        </div>
                    </div>

                    <!-- Search Button -->
                    <div class="md:col-span-1 flex items-end">
                        <button
                            type="submit"
                            class="w-full h-[52px] btn-premium rounded-xl"
                        >
                            <span
                                class="flex items-center justify-center gap-2"
                            >
                                <i class="fas fa-search"></i>
                                <span class="hidden lg:inline">Cari</span>
                            </span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </section>

    <!-- Features Section -->
    <section
        class="py-24 bg-gradient-to-b from-gray-50 to-white dark:from-gray-900 dark:to-gray-800"
    >
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <span
                    class="inline-block px-4 py-2 rounded-full bg-indigo-100 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 text-sm font-semibold mb-4"
                >
                    FASILITAS UNGGULAN
                </span>
                <h2
                    class="text-3xl md:text-4xl lg:text-5xl font-extrabold text-gray-800 dark:text-white mb-4"
                >
                    Kenapa Memilih
                    <span class="text-gradient">Tunggal Jaya?</span>
                </h2>
                <p
                    class="text-gray-600 dark:text-gray-400 max-w-2xl mx-auto text-lg"
                >
                    Kami berkomitmen memberikan layanan transportasi terbaik
                    dengan berbagai fasilitas premium.
                </p>
            </div>

            <div
                class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 lg:gap-8"
            >
                <div
                    v-for="(feature, index) in features"
                    :key="index"
                    class="card-premium p-8 text-center group"
                    :style="{ animationDelay: `${index * 0.1}s` }"
                >
                    <div
                        class="feature-icon-box mx-auto mb-6 bg-gradient-to-br shadow-lg"
                        :class="feature.gradient"
                    >
                        <i
                            :class="feature.icon"
                            class="text-white text-2xl"
                        ></i>
                    </div>
                    <h3
                        class="text-xl font-bold text-gray-800 dark:text-white mb-3"
                    >
                        {{ feature.title }}
                    </h3>
                    <p class="text-gray-600 dark:text-gray-400 leading-relaxed">
                        {{ feature.description }}
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Popular Routes Section -->
    <section class="py-24 bg-white dark:bg-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div
                class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-12"
            >
                <div>
                    <span
                        class="inline-block px-4 py-2 rounded-full bg-purple-100 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400 text-sm font-semibold mb-4"
                    >
                        RUTE UNGGULAN
                    </span>
                    <h2
                        class="text-3xl md:text-4xl font-extrabold text-gray-800 dark:text-white"
                    >
                        Rute Populer
                    </h2>
                    <p class="text-gray-600 dark:text-gray-400 mt-2">
                        Destinasi favorit pilihan pelanggan kami
                    </p>
                </div>
                <Link
                    :href="route('frontend.routes.index')"
                    class="inline-flex items-center gap-2 text-indigo-600 dark:text-indigo-400 font-semibold hover:gap-4 transition-all"
                >
                    Lihat Semua Rute <i class="fas fa-arrow-right"></i>
                </Link>
            </div>

            <div
                class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8"
            >
                <div
                    v-for="busRoute in featuredRoutes"
                    :key="busRoute.id"
                    class="group relative bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500 rounded-3xl p-1 card-hover"
                >
                    <div
                        class="bg-white dark:bg-gray-800 rounded-[1.4rem] p-6 h-full"
                    >
                        <div class="flex items-center justify-between mb-6">
                            <div class="flex-1">
                                <p
                                    class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1"
                                >
                                    Dari
                                </p>
                                <p
                                    class="font-bold text-gray-800 dark:text-white text-lg"
                                >
                                    {{ busRoute.origin }}
                                </p>
                            </div>
                            <div
                                class="flex-shrink-0 w-16 flex items-center justify-center"
                            >
                                <div class="relative">
                                    <div
                                        class="w-12 h-12 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center"
                                    >
                                        <i class="fas fa-bus text-white"></i>
                                    </div>
                                    <i
                                        class="fas fa-arrow-right absolute -right-4 top-1/2 -translate-y-1/2 text-indigo-500"
                                    ></i>
                                </div>
                            </div>
                            <div class="flex-1 text-right">
                                <p
                                    class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1"
                                >
                                    Ke
                                </p>
                                <p
                                    class="font-bold text-gray-800 dark:text-white text-lg"
                                >
                                    {{ busRoute.destination }}
                                </p>
                            </div>
                        </div>

                        <div
                            class="flex items-center justify-center gap-6 py-4 border-y border-gray-100 dark:border-gray-700 mb-6"
                        >
                            <div
                                v-if="busRoute.distance"
                                class="flex items-center gap-2 text-gray-600 dark:text-gray-400"
                            >
                                <i class="fas fa-road text-indigo-500"></i>
                                <span class="text-sm font-medium"
                                    >{{ busRoute.distance }} km</span
                                >
                            </div>
                            <div
                                v-if="busRoute.duration"
                                class="flex items-center gap-2 text-gray-600 dark:text-gray-400"
                            >
                                <i class="fas fa-clock text-purple-500"></i>
                                <span class="text-sm font-medium"
                                    >{{
                                        Math.round(busRoute.duration / 60)
                                    }}
                                    jam</span
                                >
                            </div>
                        </div>

                        <Link
                            :href="
                                route('frontend.booking.index', {
                                    origin: busRoute.origin,
                                    destination: busRoute.destination,
                                })
                            "
                            class="block w-full text-center py-3.5 rounded-xl bg-gradient-to-r from-indigo-500 to-purple-600 text-white font-semibold hover:from-indigo-600 hover:to-purple-700 transition-all duration-300 shadow-lg shadow-indigo-500/25 hover:shadow-xl hover:shadow-indigo-500/30"
                        >
                            <i class="fas fa-ticket-alt mr-2"></i>Pesan Tiket
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section id="stats-section" class="py-24 relative overflow-hidden">
        <!-- Background -->
        <div class="absolute inset-0 gradient-hero">
            <div class="absolute inset-0 hero-pattern opacity-30"></div>
            <div
                class="blob w-96 h-96 bg-cyan-500/20 -top-20 -right-20"
                style="animation-delay: 0s"
            ></div>
            <div
                class="blob w-80 h-80 bg-purple-500/20 bottom-10 left-20"
                style="animation-delay: 3s"
            ></div>
        </div>

        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <span
                    class="inline-block px-4 py-2 rounded-full bg-white/10 backdrop-blur-sm text-white text-sm font-semibold mb-4"
                >
                    PENCAPAIAN KAMI
                </span>
                <h2
                    class="text-3xl md:text-4xl lg:text-5xl font-extrabold text-white mb-4"
                >
                    Dipercaya oleh Ribuan Pelanggan
                </h2>
                <p class="text-blue-200/80 max-w-2xl mx-auto text-lg">
                    Statistik yang membuktikan komitmen kami dalam memberikan
                    layanan terbaik
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div
                    class="text-center p-10 rounded-3xl glass border border-white/20 group hover:bg-white/20 transition-all duration-300"
                >
                    <div
                        class="w-20 h-20 mx-auto mb-6 rounded-2xl bg-gradient-to-br from-cyan-400 to-blue-500 flex items-center justify-center shadow-lg shadow-cyan-500/30 group-hover:scale-110 transition-transform"
                    >
                        <i class="fas fa-bus text-white text-3xl"></i>
                    </div>
                    <div class="stats-number mb-2">
                        {{ displayFleetCount }}+
                    </div>
                    <p class="text-lg text-blue-200 font-medium">
                        Armada Bus Modern
                    </p>
                </div>

                <div
                    class="text-center p-10 rounded-3xl glass border border-white/20 group hover:bg-white/20 transition-all duration-300"
                >
                    <div
                        class="w-20 h-20 mx-auto mb-6 rounded-2xl bg-gradient-to-br from-purple-400 to-pink-500 flex items-center justify-center shadow-lg shadow-purple-500/30 group-hover:scale-110 transition-transform"
                    >
                        <i class="fas fa-route text-white text-3xl"></i>
                    </div>
                    <div class="stats-number mb-2">
                        {{ displayRouteCount }}+
                    </div>
                    <p class="text-lg text-blue-200 font-medium">
                        Rute Tersedia
                    </p>
                </div>

                <div
                    class="text-center p-10 rounded-3xl glass border border-white/20 group hover:bg-white/20 transition-all duration-300"
                >
                    <div
                        class="w-20 h-20 mx-auto mb-6 rounded-2xl bg-gradient-to-br from-amber-400 to-orange-500 flex items-center justify-center shadow-lg shadow-amber-500/30 group-hover:scale-110 transition-transform"
                    >
                        <i class="fas fa-users text-white text-3xl"></i>
                    </div>
                    <div class="stats-number mb-2">
                        {{ formatNumber(displayCustomerCount) }}+
                    </div>
                    <p class="text-lg text-blue-200 font-medium">
                        Pelanggan Puas
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Fleet Section -->
    <section class="py-24 bg-gray-50 dark:bg-gray-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <span
                    class="inline-block px-4 py-2 rounded-full bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 text-sm font-semibold mb-4"
                >
                    ARMADA KAMI
                </span>
                <h2
                    class="text-3xl md:text-4xl lg:text-5xl font-extrabold text-gray-800 dark:text-white mb-4"
                >
                    Bus Armada <span class="text-gradient">Premium</span>
                </h2>
                <p
                    class="text-gray-600 dark:text-gray-400 max-w-2xl mx-auto text-lg"
                >
                    Dipelihara dengan standar tertinggi untuk kenyamanan
                    perjalanan Anda
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div
                    v-for="bus in fleet"
                    :key="bus.id"
                    class="card-premium overflow-hidden group"
                >
                    <div
                        class="h-52 bg-gradient-to-br from-indigo-100 to-purple-100 dark:from-gray-700 dark:to-gray-600 flex items-center justify-center relative overflow-hidden"
                    >
                        <img
                            v-if="bus.media && bus.media.length"
                            :src="bus.media[0].original_url"
                            :alt="bus.name"
                            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                        />
                        <div
                            v-else
                            class="flex flex-col items-center text-indigo-400"
                        >
                            <i class="fas fa-bus text-6xl mb-2"></i>
                            <span class="text-sm opacity-60"
                                >Gambar tidak tersedia</span
                            >
                        </div>

                        <!-- Overlay badge -->
                        <div class="absolute top-4 left-4">
                            <span
                                class="px-3 py-1.5 rounded-full bg-white/90 dark:bg-gray-800/90 backdrop-blur-sm text-sm font-semibold text-indigo-600 dark:text-indigo-400"
                            >
                                {{ bus.bus_type }}
                            </span>
                        </div>
                    </div>
                    <div class="p-6">
                        <h3
                            class="text-xl font-bold text-gray-800 dark:text-white mb-3"
                        >
                            {{ bus.name }}
                        </h3>
                        <div class="flex flex-wrap gap-2 mb-4">
                            <span
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 text-sm font-medium"
                            >
                                <img
                                    src="/img/car-seat.png"
                                    alt="seat"
                                    class="w-4 h-4"
                                />
                                {{ bus.capacity }} kursi
                            </span>
                            <span
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 text-sm font-medium"
                            >
                                <i class="fas fa-id-card"></i>
                                {{ bus.plate_number }}
                            </span>
                        </div>
                        <div
                            class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400"
                        >
                            <i class="fas fa-check-circle text-green-500"></i>
                            <span>AC | WiFi | USB Charging</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-center mt-12">
                <Link :href="route('frontend.fleet.index')" class="btn-premium">
                    <span class="flex items-center gap-2">
                        <i class="fas fa-bus"></i>
                        Lihat Semua Armada
                    </span>
                </Link>
            </div>
        </div>
    </section>

    <!-- Latest News -->
    <section
        v-if="latestNews && latestNews.length"
        class="py-24 bg-white dark:bg-gray-800"
    >
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div
                class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-12"
            >
                <div>
                    <span
                        class="inline-block px-4 py-2 rounded-full bg-orange-100 dark:bg-orange-900/30 text-orange-600 dark:text-orange-400 text-sm font-semibold mb-4"
                    >
                        BERITA & UPDATE
                    </span>
                    <h2
                        class="text-3xl md:text-4xl font-extrabold text-gray-800 dark:text-white"
                    >
                        Berita Terbaru
                    </h2>
                    <p class="text-gray-600 dark:text-gray-400 mt-2">
                        Informasi dan promo terkini
                    </p>
                </div>
                <Link
                    :href="route('frontend.news.index')"
                    class="inline-flex items-center gap-2 text-indigo-600 dark:text-indigo-400 font-semibold hover:gap-4 transition-all"
                >
                    Semua Berita <i class="fas fa-arrow-right"></i>
                </Link>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <article
                    v-for="news in latestNews"
                    :key="news.id"
                    class="card-premium overflow-hidden group"
                >
                    <div
                        class="h-52 bg-gradient-to-br from-orange-400 to-pink-500 flex items-center justify-center relative overflow-hidden"
                    >
                        <img
                            v-if="news.media && news.media.length"
                            :src="news.media[0].original_url"
                            :alt="news.title"
                            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                        />
                        <i
                            v-else
                            class="fas fa-newspaper text-white/80 text-5xl"
                        ></i>
                    </div>
                    <div class="p-6">
                        <p
                            class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400 mb-3"
                        >
                            <i class="far fa-calendar-alt"></i>
                            {{
                                new Date(news.created_at).toLocaleDateString(
                                    "id-ID",
                                    {
                                        day: "numeric",
                                        month: "long",
                                        year: "numeric",
                                    }
                                )
                            }}
                        </p>
                        <h3
                            class="text-lg font-bold text-gray-800 dark:text-white mb-3 line-clamp-2 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors"
                        >
                            {{ news.title }}
                        </h3>
                        <Link
                            :href="route('frontend.news.show', news.slug)"
                            class="inline-flex items-center gap-2 text-indigo-600 dark:text-indigo-400 font-semibold hover:gap-4 transition-all text-sm"
                        >
                            Baca Selengkapnya
                            <i class="fas fa-arrow-right text-xs"></i>
                        </Link>
                    </div>
                </article>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-24 relative overflow-hidden">
        <div
            class="absolute inset-0 bg-gradient-to-br from-indigo-600 via-purple-600 to-pink-600"
        >
            <div class="absolute inset-0 hero-pattern opacity-20"></div>
            <div class="blob w-96 h-96 bg-white/10 -top-20 -left-20"></div>
            <div
                class="blob w-80 h-80 bg-white/10 bottom-10 right-20"
                style="animation-delay: 2s"
            ></div>
        </div>

        <div
            class="relative z-10 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center"
        >
            <div
                class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/10 backdrop-blur-sm border border-white/20 mb-8"
            >
                <i class="fas fa-gift text-yellow-300"></i>
                <span class="text-white text-sm font-medium"
                    >Promo Spesial - Diskon hingga 30%!</span
                >
            </div>

            <h2
                class="text-3xl md:text-4xl lg:text-5xl font-extrabold text-white mb-6 leading-tight"
            >
                Siap untuk Perjalanan
                <span class="text-gradient-gold">Tak Terlupakan?</span>
            </h2>
            <p class="text-lg md:text-xl text-white/80 mb-10 max-w-2xl mx-auto">
                Pesan tiket bus sekarang dan nikmati perjalanan nyaman dengan
                harga terbaik bersama Tunggal Jaya Transport.
            </p>

            <div class="flex flex-col sm:flex-row justify-center gap-4">
                <Link
                    :href="route('frontend.booking.index')"
                    class="inline-flex items-center justify-center gap-2 px-8 py-4 bg-white text-indigo-600 font-bold rounded-full hover:bg-gray-100 transition-all shadow-xl hover:shadow-2xl hover:-translate-y-1"
                >
                    <i class="fas fa-ticket-alt"></i>
                    Pesan Tiket Sekarang
                </Link>
                <Link
                    :href="route('frontend.contact')"
                    class="inline-flex items-center justify-center gap-2 px-8 py-4 bg-transparent border-2 border-white/40 text-white font-bold rounded-full hover:bg-white hover:text-indigo-600 transition-all hover:-translate-y-1"
                >
                    <i class="fas fa-phone"></i>
                    Hubungi Kami
                </Link>
            </div>
        </div>
    </section>
</template>

