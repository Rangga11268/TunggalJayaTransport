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
        gradient: "bg-gradient-to-br from-primary-500 to-primary-700",
    },
    {
        icon: "fas fa-tv",
        title: "Hiburan Modern",
        description:
            "TV LCD layar lebar & sistem audio surround untuk perjalanan menyenangkan",
        gradient: "bg-gradient-to-br from-secondary-500 to-secondary-700",
    },
    {
        icon: "fas fa-wifi",
        title: "WiFi Gratis",
        description:
            "Internet berkecepatan tinggi gratis untuk tetap produktif",
        gradient: "bg-gradient-to-br from-gold-500 to-gold-700",
    },
    {
        icon: "fas fa-snowflake",
        title: "AC Premium",
        description:
            "Sistem pendingin udara canggih dengan kontrol suhu optimal",
        gradient: "bg-gradient-to-br from-emerald-500 to-teal-600",
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

    <!-- Clean Title Section (Home Variant with Hero Image) -->
    <div
        class="relative min-h-[60vh] flex items-center justify-center pt-32 pb-20"
    >
        <!-- Hero Background -->
        <div class="absolute inset-0 z-0">
            <div class="absolute inset-0 bg-black/60 z-10"></div>
            <img
                src="/img/heroImg.jpg"
                alt="Hero Background"
                class="w-full h-full object-cover"
            />
        </div>

        <div
            class="relative z-10 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto text-center"
        >
            <span
                class="inline-block px-4 py-1.5 rounded-full bg-white/10 backdrop-blur-sm border border-white/20 text-white/90 text-xs font-bold tracking-widest mb-6 animate-fade-in uppercase"
            >
                LAYANAN 24/7 TERSEDIA
            </span>
            <h1
                class="text-5xl md:text-6xl lg:text-7xl font-black font-serif text-white mb-8 animate-fade-in-up leading-tight drop-shadow-sm"
            >
                Elegansi dalam
                <span
                    class="text-transparent bg-clip-text bg-gradient-to-r from-brand-red to-orange-500"
                    >Setiap Perjalanan</span
                >
            </h1>
            <p
                class="text-xl text-gray-200 max-w-2xl mx-auto animate-fade-in-up stagger-1 mb-10 font-light"
            >
                Nikmati pengalaman perjalanan bus terbaik dengan armada modern,
                fasilitas lengkap, dan pelayanan profesional dari TUJAGO.
            </p>

            <!-- CTA (Optional, kept minimal) -->
            <div
                class="flex flex-wrap justify-center gap-4 animate-fade-in-up stagger-2"
            >
                <Link
                    :href="route('frontend.fleet.index')"
                    class="px-8 py-3 rounded-full bg-brand-red text-white font-bold hover:bg-red-700 transition-all shadow-lg shadow-brand-red/30"
                >
                    <i class="fas fa-bus mr-2"></i> Lihat Armada
                </Link>
            </div>
        </div>
    </div>

    <!-- Booking Form Section -->
    <section class="relative z-20 max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 mb-24">
        <div
            class="bg-white dark:bg-gray-800 rounded-3xl p-8 md:p-10 shadow-xl border border-gray-100 dark:border-gray-700"
        >
            <form
                :action="route('frontend.booking.index')"
                method="GET"
                class="space-y-6"
            >
                <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
                    <!-- Origin -->
                    <div class="md:col-span-3 origin-input relative group">
                        <label
                            class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2"
                        >
                            Kota Asal
                        </label>
                        <div class="relative">
                            <input
                                v-model="origin"
                                @input="filterOrigins"
                                @focus="showOriginDropdown = true"
                                name="origin"
                                type="text"
                                placeholder="Pilih kota asal..."
                                class="input-premium pl-12 border-gray-200 focus:border-primary-500 focus:ring-primary-500"
                                autocomplete="off"
                            />
                            <i
                                class="fas fa-map-marker-alt absolute left-4 top-1/2 -translate-y-1/2 text-primary-500 text-lg"
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
                                    class="px-4 py-3 cursor-pointer hover:bg-primary-50 dark:hover:bg-primary-900/30 text-gray-700 dark:text-gray-200 flex items-center gap-3 transition-colors"
                                >
                                    <i
                                        class="fas fa-location-dot text-primary-500"
                                    ></i>
                                    {{ item }}
                                </div>
                            </div>
                        </transition>
                    </div>

                    <!-- Swap Button -->
                    <div
                        class="hidden md:flex md:col-span-1 items-end justify-center pb-1"
                    >
                        <button
                            type="button"
                            @click="swapLocations"
                            class="w-10 h-10 rounded-full bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400 hover:bg-primary-100 hover:text-primary-600 dark:hover:bg-primary-900/30 dark:hover:text-primary-400 transition-colors flex items-center justify-center transform hover:rotate-180 duration-300"
                        >
                            <i class="fas fa-exchange-alt"></i>
                        </button>
                    </div>

                    <!-- Destination -->
                    <div class="md:col-span-3 destination-input relative group">
                        <label
                            class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2"
                        >
                            Kota Tujuan
                        </label>
                        <div class="relative">
                            <input
                                v-model="destination"
                                @input="filterDestinations"
                                @focus="showDestinationDropdown = true"
                                name="destination"
                                type="text"
                                placeholder="Pilih kota tujuan..."
                                class="input-premium pl-12 border-gray-200 focus:border-secondary-500 focus:ring-secondary-500"
                                autocomplete="off"
                            />
                            <i
                                class="fas fa-location-dot absolute left-4 top-1/2 -translate-y-1/2 text-secondary-500 text-lg"
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
                                    class="px-4 py-3 cursor-pointer hover:bg-secondary-50 dark:hover:bg-secondary-900/30 text-gray-700 dark:text-gray-200 flex items-center gap-3 transition-colors"
                                >
                                    <i
                                        class="fas fa-location-dot text-secondary-500"
                                    ></i>
                                    {{ item }}
                                </div>
                            </div>
                        </transition>
                    </div>

                    <!-- Date -->
                    <div class="md:col-span-2">
                        <label
                            class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2"
                        >
                            Tanggal
                        </label>
                        <div class="relative">
                            <input
                                v-model="date"
                                name="date"
                                type="date"
                                :min="today"
                                class="input-premium pl-12 border-gray-200 focus:border-primary-500 focus:ring-primary-500"
                            />
                            <i
                                class="fas fa-calendar-alt absolute left-4 top-1/2 -translate-y-1/2 text-primary-500 text-lg"
                            ></i>
                        </div>
                    </div>

                    <!-- Bus Type -->
                    <div class="md:col-span-2">
                        <label
                            class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2"
                        >
                            Kelas
                        </label>
                        <div class="relative">
                            <select
                                v-model="busType"
                                name="bus_type"
                                class="input-premium pl-12 appearance-none cursor-pointer border-gray-200 focus:border-gold-500 focus:ring-gold-500"
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
                                class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none text-xs"
                            ></i>
                            <i
                                class="fas fa-crown absolute left-4 top-1/2 -translate-y-1/2 text-gold-500 text-lg"
                            ></i>
                        </div>
                    </div>

                    <!-- Search Button -->
                    <div class="md:col-span-1 flex items-end">
                        <button
                            type="submit"
                            class="w-full h-[50px] btn-premium rounded-xl shadow-lg hover:shadow-primary-500/30 flex items-center justify-center"
                        >
                            <i class="fas fa-search text-xl"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-24 bg-gray-50 dark:bg-gray-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <span
                    class="inline-block px-4 py-2 rounded-full bg-primary-100 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400 text-sm font-bold mb-4 tracking-wide"
                >
                    FASILITAS UNGGULAN
                </span>
                <h2
                    class="text-3xl md:text-4xl lg:text-5xl font-extrabold text-gray-800 dark:text-white mb-6 font-serif"
                >
                    Kenapa Memilih
                    <span
                        class="text-transparent bg-clip-text bg-gradient-to-r from-primary-600 to-secondary-600"
                        >TUJAGO?</span
                    >
                </h2>
                <p
                    class="text-gray-600 dark:text-gray-400 max-w-2xl mx-auto text-lg leading-relaxed"
                >
                    Kami berkomitmen memberikan layanan transportasi terbaik
                    dengan berbagai fasilitas premium untuk kenyamanan Anda.
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                <div
                    v-for="(feature, index) in features"
                    :key="index"
                    class="card-premium p-8 text-center group hover:bg-white dark:hover:bg-gray-800 relative overflow-hidden"
                    :style="{ animationDelay: `${index * 0.1}s` }"
                >
                    <!-- Background blob on hover -->
                    <div
                        class="absolute -top-10 -right-10 w-32 h-32 bg-primary-500/10 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-500"
                    ></div>

                    <div
                        class="feature-icon-box mx-auto mb-6 shadow-lg text-white relative z-10"
                        :class="feature.gradient"
                    >
                        <i :class="feature.icon" class="text-2xl"></i>
                    </div>
                    <h3
                        class="text-xl font-bold text-gray-800 dark:text-white mb-3 group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors relative z-10 font-serif"
                    >
                        {{ feature.title }}
                    </h3>
                    <p
                        class="text-gray-600 dark:text-gray-400 leading-relaxed text-sm relative z-10"
                    >
                        {{ feature.description }}
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Popular Routes Section -->
    <section class="py-24 bg-white dark:bg-gray-950 relative">
        <!-- Decorative elements -->
        <div
            class="absolute top-0 right-0 w-1/3 h-full bg-gradient-to-l from-gray-50 to-transparent dark:from-gray-900 pointer-events-none"
        ></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div
                class="flex flex-col md:flex-row justify-between items-end gap-6 mb-16"
            >
                <div>
                    <span
                        class="inline-block px-4 py-2 rounded-full bg-secondary-100 dark:bg-secondary-900/30 text-secondary-600 dark:text-secondary-400 text-sm font-bold mb-4 tracking-wide"
                    >
                        RUTE TERPOPULER
                    </span>
                    <h2
                        class="text-3xl md:text-4xl font-extrabold text-gray-800 dark:text-white font-serif"
                    >
                        Jelajahi Destinasi Favorit
                    </h2>
                </div>
                <Link
                    :href="route('frontend.routes.index')"
                    class="group inline-flex items-center gap-2 text-primary-600 dark:text-primary-400 font-bold hover:text-primary-700 transition-colors"
                >
                    Lihat Semua Rute
                    <i
                        class="fas fa-arrow-right group-hover:translate-x-1 transition-transform"
                    ></i>
                </Link>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div
                    v-for="busRoute in featuredRoutes"
                    :key="busRoute.id"
                    class="card-premium group hover:border-primary-500/30"
                >
                    <div class="p-8">
                        <div class="flex items-center justify-between mb-8">
                            <div class="flex-1">
                                <p
                                    class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1"
                                >
                                    Dari
                                </p>
                                <p
                                    class="font-bold text-gray-800 dark:text-white text-xl font-serif"
                                >
                                    {{ busRoute.origin }}
                                </p>
                            </div>

                            <div class="px-4">
                                <div
                                    class="w-10 h-10 rounded-full bg-primary-50 dark:bg-primary-900/30 flex items-center justify-center text-primary-600 dark:text-primary-400 group-hover:bg-primary-600 group-hover:text-white transition-colors duration-300"
                                >
                                    <i class="fas fa-arrow-right"></i>
                                </div>
                            </div>

                            <div class="flex-1 text-right">
                                <p
                                    class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1"
                                >
                                    Ke
                                </p>
                                <p
                                    class="font-bold text-gray-800 dark:text-white text-xl font-serif"
                                >
                                    {{ busRoute.destination }}
                                </p>
                            </div>
                        </div>

                        <div
                            class="flex items-center gap-6 py-4 border-t border-gray-100 dark:border-gray-700 mb-6"
                        >
                            <div
                                v-if="busRoute.distance"
                                class="flex items-center gap-2 text-gray-500 dark:text-gray-400"
                            >
                                <i class="fas fa-road text-primary-500"></i>
                                <span class="text-sm font-medium"
                                    >{{ busRoute.distance }} km</span
                                >
                            </div>
                            <div
                                v-if="busRoute.duration"
                                class="flex items-center gap-2 text-gray-500 dark:text-gray-400"
                            >
                                <i class="fas fa-clock text-secondary-500"></i>
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
                            class="block w-full text-center py-4 rounded-xl bg-gray-900 dark:bg-white text-white dark:text-gray-900 font-bold hover:bg-primary-600 dark:hover:bg-primary-500 dark:hover:text-white transition-all duration-300 shadow-lg hover:shadow-primary-500/30"
                        >
                            Pesan Sekarang
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section (Unified Background) -->
    <section
        id="stats-section"
        class="py-20 relative bg-brand-red text-white overflow-hidden"
    >
        <div
            class="absolute inset-0 opacity-10 pattern-dots pointer-events-none"
        ></div>
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div
                class="grid grid-cols-1 md:grid-cols-3 gap-12 text-center divide-y md:divide-y-0 md:divide-x divide-white/20"
            >
                <div class="group p-4">
                    <div
                        class="text-5xl md:text-6xl font-black mb-2 group-hover:scale-110 transition-transform duration-300"
                    >
                        {{ displayFleetCount }}+
                    </div>
                    <p
                        class="text-lg font-medium tracking-widest uppercase opacity-80"
                    >
                        Armada Bus
                    </p>
                </div>

                <div class="group p-4">
                    <div
                        class="text-5xl md:text-6xl font-black mb-2 group-hover:scale-110 transition-transform duration-300"
                    >
                        {{ displayRouteCount }}+
                    </div>
                    <p
                        class="text-lg font-medium tracking-widest uppercase opacity-80"
                    >
                        Rute Perjalanan
                    </p>
                </div>

                <div class="group p-4">
                    <div
                        class="text-5xl md:text-6xl font-black mb-2 group-hover:scale-110 transition-transform duration-300"
                    >
                        {{ formatNumber(displayCustomerCount) }}+
                    </div>
                    <p
                        class="text-lg font-medium tracking-widest uppercase opacity-80"
                    >
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
                    class="inline-block px-4 py-2 rounded-full bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 text-sm font-bold mb-4 tracking-wide"
                >
                    ARMADA KAMI
                </span>
                <h2
                    class="text-3xl md:text-4xl lg:text-5xl font-extrabold text-gray-800 dark:text-white mb-6 font-serif"
                >
                    Kenyamanan
                    <span
                        class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-500 to-teal-500"
                        >Kelas Atas</span
                    >
                </h2>
                <p
                    class="text-gray-600 dark:text-gray-400 max-w-2xl mx-auto text-lg"
                >
                    Armada bus terbaru dengan standar keamanan dan kenyamanan
                    tertinggi.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div
                    v-for="bus in fleet"
                    :key="bus.id"
                    class="card-premium overflow-hidden group"
                >
                    <div
                        class="h-64 bg-gray-200 dark:bg-gray-800 relative overflow-hidden"
                    >
                        <img
                            v-if="bus.media && bus.media.length"
                            :src="bus.media[0].original_url"
                            :alt="bus.name"
                            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700"
                        />
                        <div
                            v-else
                            class="w-full h-full flex flex-col items-center justify-center text-gray-400 bg-gray-100 dark:bg-gray-800"
                        >
                            <i class="fas fa-bus text-5xl mb-4"></i>
                            <span class="text-sm font-medium"
                                >No Image Available</span
                            >
                        </div>

                        <!-- Overlay gradient -->
                        <div
                            class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-60"
                        ></div>

                        <!-- Type Badge -->
                        <div class="absolute bottom-4 left-4">
                            <span
                                class="px-3 py-1 rounded-lg bg-white/20 backdrop-blur-md text-white text-sm font-bold border border-white/20"
                            >
                                {{ bus.bus_type }}
                            </span>
                        </div>
                    </div>

                    <div class="p-6">
                        <h3
                            class="text-xl font-bold text-gray-800 dark:text-white mb-4 font-serif"
                        >
                            {{ bus.name }}
                        </h3>

                        <div class="flex flex-wrap gap-3 mb-6">
                            <span
                                class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300 text-sm font-medium"
                            >
                                <img
                                    src="/img/car-seat.png"
                                    alt="seat"
                                    class="w-4 h-4 opacity-70"
                                />
                                {{ bus.capacity }} Kursi
                            </span>
                            <span
                                class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300 text-sm font-medium"
                            >
                                <i class="fas fa-id-card text-gray-400"></i>
                                {{ bus.plate_number }}
                            </span>
                        </div>

                        <div
                            class="pt-6 border-t border-gray-100 dark:border-gray-700"
                        >
                            <div
                                class="flex items-center gap-4 text-sm text-gray-500 dark:text-gray-400"
                            >
                                <span class="flex items-center gap-1.5">
                                    <i
                                        class="fas fa-snowflake text-sky-400"
                                    ></i>
                                    AC
                                </span>
                                <span class="flex items-center gap-1.5">
                                    <i class="fas fa-wifi text-primary-400"></i>
                                    WiFi
                                </span>
                                <span class="flex items-center gap-1.5">
                                    <i class="fas fa-bolt text-gold-400"></i>
                                    USB
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-center mt-16">
                <Link :href="route('frontend.fleet.index')" class="btn-premium">
                    Lihat Seluruh Armada
                </Link>
            </div>
        </div>
    </section>

    <!-- Latest News -->
    <section
        v-if="latestNews && latestNews.length"
        class="py-24 bg-white dark:bg-gray-950"
    >
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div
                class="flex flex-col md:flex-row justify-between items-end gap-6 mb-16"
            >
                <div>
                    <span
                        class="inline-block px-4 py-2 rounded-full bg-gold-100 dark:bg-gold-900/30 text-gold-600 dark:text-gold-400 text-sm font-bold mb-4 tracking-wide"
                    >
                        BERITA TERKINI
                    </span>
                    <h2
                        class="text-3xl md:text-4xl font-extrabold text-gray-800 dark:text-white font-serif"
                    >
                        Info & Promo Terbaru
                    </h2>
                </div>
                <Link
                    :href="route('frontend.news.index')"
                    class="group inline-flex items-center gap-2 text-primary-600 dark:text-primary-400 font-bold hover:text-primary-700 transition-colors"
                >
                    Lihat Semua Berita
                    <i
                        class="fas fa-arrow-right group-hover:translate-x-1 transition-transform"
                    ></i>
                </Link>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <article
                    v-for="news in latestNews"
                    :key="news.id"
                    class="card-premium overflow-hidden group"
                >
                    <div
                        class="h-56 bg-gray-200 dark:bg-gray-800 relative overflow-hidden"
                    >
                        <img
                            v-if="news.media && news.media.length"
                            :src="news.media[0].original_url"
                            :alt="news.title"
                            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700"
                        />
                        <div
                            v-else
                            class="w-full h-full flex items-center justify-center bg-gradient-to-br from-gold-400 to-primary-500"
                        >
                            <i
                                class="fas fa-newspaper text-white/50 text-5xl"
                            ></i>
                        </div>

                        <!-- Date Badge -->
                        <div class="absolute top-4 left-4">
                            <span
                                class="px-3 py-1 rounded-lg bg-white/90 backdrop-blur-sm text-gray-900 text-xs font-bold shadow-sm"
                            >
                                {{
                                    new Date(
                                        news.created_at
                                    ).toLocaleDateString("id-ID", {
                                        day: "numeric",
                                        month: "short",
                                    })
                                }}
                            </span>
                        </div>
                    </div>

                    <div class="p-6">
                        <h3
                            class="text-lg font-bold text-gray-800 dark:text-white mb-3 line-clamp-2 group-hover:text-primary-600 transition-colors font-serif"
                        >
                            {{ news.title }}
                        </h3>
                        <p
                            class="text-gray-500 dark:text-gray-400 text-sm line-clamp-2 mb-4"
                        >
                            {{
                                news.content ||
                                "Klik untuk membaca selengkapnya..."
                            }}
                        </p>
                        <Link
                            :href="route('frontend.news.show', news.slug)"
                            class="inline-flex items-center gap-2 text-primary-600 dark:text-primary-400 font-bold text-sm hover:gap-3 transition-all"
                        >
                            Baca Selengkapnya
                            <i class="fas fa-arrow-right text-xs"></i>
                        </Link>
                    </div>
                </article>
            </div>
        </div>
    </section>
</template>
