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

    // Generic Scroll Observer
    const scrollObserver = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add("active");
                }
            });
        },
        { threshold: 0.1 }
    );

    document.querySelectorAll(".scroll-reveal").forEach((el) => {
        scrollObserver.observe(el);
    });

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
        class="relative min-h-screen flex items-center justify-center pt-52 pb-40 overflow-hidden"
    >
        <!-- Hero Background -->
        <div class="absolute inset-0 z-0">
            <div
                class="absolute inset-0 bg-gradient-to-b from-black/80 via-black/50 to-black/80 z-10"
            ></div>
            <img
                src="/img/heroImg.jpg"
                alt="Hero Background"
                class="w-full h-full object-cover scale-105 animate-slow-zoom"
            />
        </div>

        <div
            class="relative z-10 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto text-center"
        >
            <span
                class="inline-block px-6 py-2 rounded-full bg-white/10 backdrop-blur-md border border-white/20 text-white font-medium tracking-[0.2em] text-xs mb-8 animate-fade-in uppercase shadow-lg"
            >
                PREMIUM BUS TRANSPORTATION
            </span>
            <h1
                class="text-5xl md:text-7xl lg:text-8xl font-black font-serif text-white mb-8 animate-fade-in-up leading-tight drop-shadow-2xl tracking-tight"
            >
                Elegansi dalam
                <span
                    class="block text-transparent bg-clip-text bg-gradient-to-r from-brand-red via-red-500 to-orange-400"
                    >Setiap Perjalanan</span
                >
            </h1>
            <p
                class="text-lg md:text-xl text-gray-200 max-w-2xl mx-auto animate-fade-in-up stagger-1 mb-12 font-light leading-relaxed"
            >
                Nikmati pengalaman perjalanan bus terbaik dengan armada modern,
                fasilitas lengkap, dan pelayanan profesional dari TUJAGO.
            </p>

            <!-- Search Button -->
            <div
                class="flex flex-wrap justify-center gap-4 animate-fade-in-up stagger-2"
            >
                <Link
                    :href="route('frontend.fleet.index')"
                    class="btn-premium transform hover:scale-105 transition-all"
                >
                    <i class="fas fa-bus mr-2"></i> Lihat Armada
                </Link>
                <Link
                    :href="route('frontend.routes.index')"
                    class="btn-secondary-premium transform hover:scale-105 transition-all"
                >
                    <i class="fas fa-map-marked-alt mr-2"></i> Rute Populer
                </Link>
            </div>
        </div>
    </div>

    <!-- Wave Divider 1 -->
    <div class="relative -mt-24 z-10">
        <svg
            class="fill-white dark:fill-gray-900 w-full h-24 transform scale-y-50 origin-bottom"
            viewBox="0 0 1440 320"
        >
            <path
                fill-opacity="1"
                d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,112C672,96,768,96,864,112C960,128,1056,160,1152,160C1248,160,1344,128,1392,112L1440,96L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"
            ></path>
        </svg>
    </div>

    <!-- Booking Form Section -->
    <section
        class="relative z-20 max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 -mt-24 mb-32"
    >
        <div
            class="bg-white/95 dark:bg-gray-800/95 backdrop-blur-xl rounded-[2rem] p-8 md:p-12 shadow-2xl shadow-black/20 border border-white/50 dark:border-gray-700"
        >
            <form
                :action="route('frontend.booking.index')"
                method="GET"
                class="space-y-8"
            >
                <div
                    class="grid grid-cols-1 md:grid-cols-12 gap-6 items-center"
                >
                    <!-- Origin -->
                    <div class="md:col-span-3 origin-input relative group">
                        <div class="relative z-0 w-full group">
                            <input
                                v-model="origin"
                                @input="filterOrigins"
                                @focus="showOriginDropdown = true"
                                name="origin"
                                type="text"
                                placeholder=" "
                                class="block py-4 pl-12 pr-4 w-full text-base font-bold text-gray-900 dark:text-white bg-gray-50 dark:bg-gray-900 rounded-xl border-none appearance-none focus:outline-none focus:ring-0 focus:bg-white dark:focus:bg-gray-950 focus:shadow-md transition-all peer"
                                autocomplete="off"
                            />
                            <div
                                class="absolute left-3 top-1/2 -translate-y-1/2 w-8 h-8 rounded-lg bg-brand-red/10 flex items-center justify-center text-brand-red transition-all peer-focus:scale-90 peer-focus:bg-brand-red peer-focus:text-white"
                            >
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <label
                                for="origin"
                                class="absolute text-sm font-medium text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] left-12 peer-focus:text-brand-red peer-focus:dark:text-brand-red peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-placeholder-shown:top-4 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 uppercase tracking-wider"
                            >
                                Kota Asal
                            </label>
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
                                class="absolute z-50 w-full mt-2 bg-white dark:bg-gray-800 rounded-xl shadow-2xl border border-gray-100 dark:border-gray-700 max-h-56 overflow-y-auto custom-scrollbar"
                            >
                                <div
                                    v-for="item in filteredOrigins"
                                    :key="item"
                                    @click="selectOrigin(item)"
                                    class="px-4 py-3 cursor-pointer hover:bg-red-50 dark:hover:bg-red-900/20 text-gray-700 dark:text-gray-200 flex items-center gap-3 transition-colors group"
                                >
                                    <i
                                        class="fas fa-location-dot text-gray-300 group-hover:text-brand-red transition-colors"
                                    ></i>
                                    {{ item }}
                                </div>
                            </div>
                        </transition>
                    </div>

                    <!-- Swap Button -->
                    <div
                        class="hidden md:flex md:col-span-1 items-end justify-center pb-2"
                    >
                        <button
                            type="button"
                            @click="swapLocations"
                            class="w-12 h-12 rounded-full bg-white dark:bg-gray-700 border border-gray-100 dark:border-gray-600 text-gray-400 hover:text-brand-red hover:border-brand-red/30 hover:bg-red-50 dark:hover:bg-red-900/20 transition-all flex items-center justify-center transform hover:rotate-180 duration-500 shadow-sm"
                        >
                            <i class="fas fa-exchange-alt"></i>
                        </button>
                    </div>

                    <!-- Destination -->
                    <div class="md:col-span-3 destination-input relative group">
                        <div class="relative z-0 w-full group">
                            <input
                                v-model="destination"
                                @input="filterDestinations"
                                @focus="showDestinationDropdown = true"
                                name="destination"
                                type="text"
                                placeholder=" "
                                class="block py-4 pl-12 pr-4 w-full text-base font-bold text-gray-900 dark:text-white bg-gray-50 dark:bg-gray-900 rounded-xl border-none appearance-none focus:outline-none focus:ring-0 focus:bg-white dark:focus:bg-gray-950 focus:shadow-md transition-all peer"
                                autocomplete="off"
                            />
                            <div
                                class="absolute left-3 top-1/2 -translate-y-1/2 w-8 h-8 rounded-lg bg-orange-500/10 flex items-center justify-center text-orange-500 transition-all peer-focus:scale-90 peer-focus:bg-orange-500 peer-focus:text-white"
                            >
                                <i class="fas fa-location-dot"></i>
                            </div>
                            <label
                                for="destination"
                                class="absolute text-sm font-medium text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] left-12 peer-focus:text-orange-500 peer-focus:dark:text-orange-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-placeholder-shown:top-4 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 uppercase tracking-wider"
                            >
                                Kota Tujuan
                            </label>
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
                                class="absolute z-50 w-full mt-2 bg-white dark:bg-gray-800 rounded-xl shadow-2xl border border-gray-100 dark:border-gray-700 max-h-56 overflow-y-auto custom-scrollbar"
                            >
                                <div
                                    v-for="item in filteredDestinations"
                                    :key="item"
                                    @click="selectDestination(item)"
                                    class="px-4 py-3 cursor-pointer hover:bg-orange-50 dark:hover:bg-orange-900/20 text-gray-700 dark:text-gray-200 flex items-center gap-3 transition-colors group"
                                >
                                    <i
                                        class="fas fa-location-dot text-gray-300 group-hover:text-orange-500 transition-colors"
                                    ></i>
                                    {{ item }}
                                </div>
                            </div>
                        </transition>
                    </div>

                    <!-- Date & Class (Grid Nested) -->
                    <div class="md:col-span-4 grid grid-cols-2 gap-4">
                        <!-- Date -->
                        <div class="relative z-0 w-full group">
                            <input
                                v-model="date"
                                name="date"
                                type="date"
                                :min="today"
                                placeholder=" "
                                class="block py-4 pl-12 pr-4 w-full text-base font-bold text-gray-900 dark:text-white bg-gray-50 dark:bg-gray-900 rounded-xl border-none appearance-none focus:outline-none focus:ring-0 focus:bg-white dark:focus:bg-gray-950 focus:shadow-md transition-all peer"
                            />
                            <div
                                class="absolute left-3 top-1/2 -translate-y-1/2 w-8 h-8 rounded-lg bg-blue-500/10 flex items-center justify-center text-blue-500 transition-all peer-focus:scale-90 peer-focus:bg-blue-500 peer-focus:text-white"
                            >
                                <i class="fas fa-calendar-alt"></i>
                            </div>
                            <label
                                class="absolute text-sm font-medium text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] left-12 peer-focus:text-blue-500 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-placeholder-shown:top-4 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 uppercase tracking-wider"
                            >
                                Tanggal
                            </label>
                        </div>

                        <!-- Bus Type -->
                        <div class="relative z-0 w-full group">
                            <select
                                v-model="busType"
                                name="bus_type"
                                id="bus_type"
                                class="block py-4 pl-12 pr-8 w-full text-base font-bold text-gray-900 dark:text-white bg-gray-50 dark:bg-gray-900 rounded-xl border-none appearance-none focus:outline-none focus:ring-0 focus:bg-white dark:focus:bg-gray-950 focus:shadow-md transition-all peer cursor-pointer"
                                placeholder=" "
                            >
                                <option
                                    v-for="type in busTypes"
                                    :key="type.id"
                                    :value="type.id"
                                >
                                    {{ type.name }}
                                </option>
                            </select>
                            <label
                                for="bus_type"
                                class="absolute text-sm font-medium text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] left-12 peer-focus:text-gold-500 peer-focus:dark:text-gold-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-placeholder-shown:top-4 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 uppercase tracking-wider"
                            >
                                Kelas Buss
                            </label>
                            <div
                                class="absolute left-3 top-1/2 -translate-y-1/2 w-8 h-8 rounded-lg bg-gold-500/10 flex items-center justify-center text-gold-500 transition-all peer-focus:scale-90 peer-focus:bg-gold-500 peer-focus:text-white"
                            >
                                <i class="fas fa-crown"></i>
                            </div>
                            <i
                                class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none text-xs"
                            ></i>
                        </div>
                    </div>

                    <!-- Search Button -->
                    <div class="md:col-span-1 flex items-end">
                        <button
                            type="submit"
                            class="w-full h-[56px] bg-brand-red hover:bg-red-700 text-white rounded-xl shadow-lg hover:shadow-brand-red/40 flex items-center justify-center transition-all duration-300 transform hover:scale-105 active:scale-95 group"
                        >
                            <i
                                class="fas fa-search text-xl group-hover:rotate-90 transition-transform duration-300"
                            ></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-24 bg-gray-50 dark:bg-gray-900 overflow-hidden relative">
        <!-- Scroll Reveal Content -->
        <!-- Decorative blobs -->
        <div
            class="absolute top-0 left-0 w-full h-full overflow-hidden pointer-events-none"
        >
            <div
                class="absolute -top-24 -left-24 w-96 h-96 bg-brand-red/5 rounded-full blur-3xl"
            ></div>
            <div
                class="absolute top-1/2 -right-24 w-64 h-64 bg-orange-500/5 rounded-full blur-3xl"
            ></div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center mb-16">
                <span
                    class="inline-block px-4 py-2 rounded-full bg-brand-red/10 text-brand-red text-xs font-bold tracking-[0.2em] mb-4 uppercase"
                >
                    FASILITAS UNGGULAN
                </span>
                <h2
                    class="text-3xl md:text-5xl font-black text-gray-800 dark:text-white mb-6 font-serif"
                >
                    Kenapa Memilih
                    <span
                        class="text-transparent bg-clip-text bg-gradient-to-r from-brand-red to-orange-500"
                        >TUJAGO?</span
                    >
                </h2>
                <p
                    class="text-gray-600 dark:text-gray-400 max-w-2xl mx-auto text-lg leading-relaxed font-light"
                >
                    Kami berkomitmen memberikan layanan transportasi terbaik
                    dengan berbagai fasilitas premium untuk kenyamanan Anda.
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                <div
                    v-for="(feature, index) in features"
                    :key="index"
                    class="scroll-reveal group bg-white dark:bg-gray-800 p-8 rounded-3xl transition-all duration-300 hover:shadow-2xl hover:shadow-brand-red/10 border border-transparent hover:border-gray-100 dark:hover:border-gray-700 relative overflow-hidden"
                    :style="{
                        animationDelay: `${index * 0.1}s`,
                        transitionDelay: `${index * 0.1}s`,
                    }"
                >
                    <!-- Background blob on hover -->
                    <div
                        class="absolute -top-10 -right-10 w-32 h-32 bg-gray-50 dark:bg-gray-700 rounded-full blur-2xl group-hover:bg-brand-red/5 transition-colors duration-500"
                    ></div>

                    <div
                        class="w-16 h-16 rounded-2xl flex items-center justify-center mb-6 shadow-lg transform group-hover:scale-110 transition-transform duration-300 relative z-10"
                        :class="feature.gradient"
                    >
                        <i
                            :class="feature.icon"
                            class="text-2xl text-white"
                        ></i>
                    </div>
                    <h3
                        class="text-xl font-bold text-gray-800 dark:text-white mb-3 group-hover:text-brand-red transition-colors relative z-10 font-serif"
                    >
                        {{ feature.title }}
                    </h3>
                    <p
                        class="text-gray-500 dark:text-gray-400 leading-relaxed text-sm relative z-10"
                    >
                        {{ feature.description }}
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Wave Divider 2 -->
    <div class="relative z-10 -mt-1 bg-gray-50 dark:bg-gray-900">
        <svg
            class="fill-white dark:fill-gray-950 w-full h-24 transform scale-y-50 origin-top rotate-180"
            viewBox="0 0 1440 320"
        >
            <path
                fill-opacity="1"
                d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,112C672,96,768,96,864,112C960,128,1056,160,1152,160C1248,160,1344,128,1392,112L1440,96L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"
            ></path>
        </svg>
    </div>

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
                        class="inline-block px-4 py-2 rounded-full bg-brand-red/10 text-brand-red text-xs font-bold tracking-[0.2em] mb-4 uppercase"
                    >
                        RUTE TERPOPULER
                    </span>
                    <h2
                        class="text-3xl md:text-5xl font-black text-gray-800 dark:text-white font-serif"
                    >
                        Jelajahi Destinasi Favorit
                    </h2>
                </div>
                <Link
                    :href="route('frontend.routes.index')"
                    class="group inline-flex items-center gap-2 text-brand-red font-bold hover:text-red-700 transition-colors bg-red-50 px-6 py-3 rounded-full hover:bg-red-100"
                >
                    Lihat Semua Rute
                    <i
                        class="fas fa-arrow-right group-hover:translate-x-1 transition-transform"
                    ></i>
                </Link>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div
                    v-for="(busRoute, index) in featuredRoutes"
                    :key="busRoute.id"
                    class="scroll-reveal bg-white dark:bg-gray-900 rounded-3xl p-8 border border-gray-100 dark:border-gray-800 shadow-lg hover:shadow-2xl transition-all duration-300 hover:-translate-y-1 group"
                    :style="{ transitionDelay: `${index * 0.1}s` }"
                >
                    <div
                        class="flex items-center justify-between mb-8 relative"
                    >
                        <!-- Connecting Line -->
                        <div
                            class="absolute top-1/2 left-0 w-full h-0.5 bg-gray-100 dark:bg-gray-800 -z-0"
                        ></div>

                        <div
                            class="relative z-10 bg-white dark:bg-gray-900 pr-2"
                        >
                            <p
                                class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1"
                            >
                                Dari
                            </p>
                            <p
                                class="font-bold text-gray-800 dark:text-white text-xl font-serif"
                            >
                                {{ busRoute.origin }}
                            </p>
                        </div>

                        <div
                            class="relative z-10 w-12 h-12 rounded-full bg-brand-red/5 flex items-center justify-center text-brand-red group-hover:bg-brand-red group-hover:text-white transition-all duration-300 shadow-sm"
                        >
                            <i
                                class="fas fa-arrow-right transform group-hover:rotate-45 transition-transform duration-300"
                            ></i>
                        </div>

                        <div
                            class="relative z-10 bg-white dark:bg-gray-900 pl-2 text-right"
                        >
                            <p
                                class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1"
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
                        class="flex items-center gap-6 py-4 border-t border-gray-100 dark:border-gray-800 mb-6"
                    >
                        <div
                            v-if="busRoute.distance"
                            class="flex items-center gap-2 text-gray-500 dark:text-gray-400"
                        >
                            <i class="fas fa-road text-brand-red/70"></i>
                            <span class="text-sm font-medium"
                                >{{ busRoute.distance }} km</span
                            >
                        </div>
                        <div
                            v-if="busRoute.duration"
                            class="flex items-center gap-2 text-gray-500 dark:text-gray-400"
                        >
                            <i class="fas fa-clock text-orange-500/70"></i>
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
                        class="block w-full text-center py-4 rounded-xl bg-gray-900 dark:bg-white text-white dark:text-gray-900 font-bold hover:bg-brand-red dark:hover:bg-brand-red hover:shadow-lg hover:shadow-brand-red/30 transition-all duration-300"
                    >
                        Pesan Sekarang
                    </Link>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section (Unified Background) -->
    <section
        id="stats-section"
        class="py-24 relative bg-gradient-to-br from-gray-900 via-gray-800 to-black text-white overflow-hidden"
    >
        <div
            class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-5 pointer-events-none"
        ></div>
        <!-- Gradient blobs -->
        <div
            class="absolute top-0 right-0 w-[500px] h-[500px] bg-brand-red/20 rounded-full blur-[100px]"
        ></div>
        <div
            class="absolute bottom-0 left-0 w-[500px] h-[500px] bg-blue-900/20 rounded-full blur-[100px]"
        ></div>

        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div
                class="scroll-reveal grid grid-cols-1 md:grid-cols-3 gap-12 text-center divide-y md:divide-y-0 md:divide-x divide-white/10"
            >
                <div class="group p-8">
                    <div
                        class="text-6xl md:text-7xl font-black mb-4 group-hover:scale-110 transition-transform duration-300 text-transparent bg-clip-text bg-gradient-to-b from-white to-gray-500"
                    >
                        {{ displayFleetCount }}+
                    </div>
                    <p
                        class="text-sm font-bold tracking-[0.3em] uppercase opacity-60 text-brand-red"
                    >
                        Armada Bus
                    </p>
                </div>

                <div class="group p-8">
                    <div
                        class="text-6xl md:text-7xl font-black mb-4 group-hover:scale-110 transition-transform duration-300 text-transparent bg-clip-text bg-gradient-to-b from-white to-gray-500"
                    >
                        {{ displayRouteCount }}+
                    </div>
                    <p
                        class="text-sm font-bold tracking-[0.3em] uppercase opacity-60 text-brand-red"
                    >
                        Rute Perjalanan
                    </p>
                </div>

                <div class="group p-8">
                    <div
                        class="text-6xl md:text-7xl font-black mb-4 group-hover:scale-110 transition-transform duration-300 text-transparent bg-clip-text bg-gradient-to-b from-white to-gray-500"
                    >
                        {{ formatNumber(displayCustomerCount) }}+
                    </div>
                    <p
                        class="text-sm font-bold tracking-[0.3em] uppercase opacity-60 text-brand-red"
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
                    class="inline-block px-4 py-2 rounded-full bg-emerald-100/50 text-emerald-600 text-xs font-bold tracking-[0.2em] mb-4 uppercase"
                >
                    ARMADA KAMI
                </span>
                <h2
                    class="text-3xl md:text-5xl font-black text-gray-800 dark:text-white mb-6 font-serif"
                >
                    Kenyamanan
                    <span
                        class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-500 to-teal-500"
                        >Kelas Atas</span
                    >
                </h2>
                <p
                    class="text-gray-600 dark:text-gray-400 max-w-2xl mx-auto text-lg font-light"
                >
                    Armada bus terbaru dengan standar keamanan dan kenyamanan
                    tertinggi.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div
                    v-for="bus in fleet"
                    :key="bus.id"
                    class="bg-white dark:bg-gray-800 rounded-3xl overflow-hidden group shadow-lg hover:shadow-2xl transition-all duration-500 hover:-translate-y-2"
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
                            class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent opacity-80"
                        ></div>

                        <!-- Type Badge -->
                        <div class="absolute bottom-4 left-4">
                            <span
                                class="px-4 py-1.5 rounded-full bg-white/20 backdrop-blur-md text-white text-xs font-bold border border-white/20 uppercase tracking-wider"
                            >
                                {{ bus.bus_type }}
                            </span>
                        </div>
                    </div>

                    <div class="p-8">
                        <h3
                            class="text-2xl font-bold text-gray-800 dark:text-white mb-4 font-serif group-hover:text-brand-red transition-colors"
                        >
                            {{ bus.name }}
                        </h3>

                        <div class="flex flex-wrap gap-3 mb-8">
                            <span
                                class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-gray-50 dark:bg-gray-700/50 text-gray-600 dark:text-gray-300 text-xs font-bold uppercase tracking-wide"
                            >
                                <img
                                    src="/img/car-seat.png"
                                    alt="seat"
                                    class="w-4 h-4 opacity-70"
                                />
                                {{ bus.capacity }} Kursi
                            </span>
                            <span
                                class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-gray-50 dark:bg-gray-700/50 text-gray-600 dark:text-gray-300 text-xs font-bold uppercase tracking-wide"
                            >
                                <i class="fas fa-id-card text-gray-400"></i>
                                {{ bus.plate_number }}
                            </span>
                        </div>

                        <div
                            class="pt-6 border-t border-gray-100 dark:border-gray-700 flex justify-between items-center"
                        >
                            <div
                                class="flex items-center gap-4 text-gray-500 dark:text-gray-400"
                            >
                                <i
                                    class="fas fa-snowflake hover:text-sky-400 transition-colors"
                                    title="AC"
                                ></i>
                                <i
                                    class="fas fa-wifi hover:text-primary-400 transition-colors"
                                    title="WiFi"
                                ></i>
                                <i
                                    class="fas fa-bolt hover:text-gold-400 transition-colors"
                                    title="USB"
                                ></i>
                            </div>

                            <Link
                                :href="route('frontend.fleet.index')"
                                class="w-10 h-10 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center text-gray-600 dark:text-gray-300 hover:bg-brand-red hover:text-white transition-all transform hover:rotate-45"
                            >
                                <i class="fas fa-arrow-right"></i>
                            </Link>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-center mt-16">
                <Link
                    :href="route('frontend.fleet.index')"
                    class="inline-flex items-center px-8 py-4 bg-white dark:bg-gray-800 text-gray-900 dark:text-white font-bold rounded-full shadow-lg hover:shadow-xl border border-gray-100 dark:border-gray-700 hover:border-brand-red transition-all"
                >
                    Lihat Seluruh Armada
                    <i class="fas fa-arrow-right ml-2 text-brand-red"></i>
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
