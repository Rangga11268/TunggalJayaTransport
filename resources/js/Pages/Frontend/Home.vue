<script setup>
import { ref, onMounted, computed, watch } from "vue";
import { Link, usePage } from "@inertiajs/vue3";
import FrontendLayout from "@/Layouts/FrontendLayout.vue";
import { gsap } from "gsap";
import { ScrollTrigger } from "gsap/ScrollTrigger";
import { useMagnetic } from "@/Composables/useMagnetic";

// Register ScrollTrigger
gsap.registerPlugin(ScrollTrigger);

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

// Form State
const origin = ref("");
const destination = ref("");
const date = ref("");
const busType = ref("");
const showOriginDropdown = ref(false);
const showDestinationDropdown = ref(false);
const filteredOrigins = ref([]);
const filteredDestinations = ref([]);

// Magnetic Refs
const heroBtn1 = ref(null);
const heroBtn2 = ref(null);
const searchBtn = ref(null);
const ctaBtn1 = ref(null);
const ctaBtn2 = ref(null);

useMagnetic(heroBtn1);
useMagnetic(heroBtn2);
useMagnetic(searchBtn);
useMagnetic(ctaBtn1);
useMagnetic(ctaBtn2);

// UI State
const isScrolled = ref(false);
const currentHeroImage = ref(0);
const heroImages = [
    "/img/heroImg.jpg", // Ensure this exists or use a fallback
    // Add more if available
];

const busTypes = [
    { id: "", name: "All Classes", icon: "fas fa-layer-group" },
    { id: "Executive", name: "Executive Class", icon: "fas fa-crown" },
    { id: "Business", name: "Business Class", icon: "fas fa-briefcase" },
    { id: "Economy", name: "Economy Class", icon: "fas fa-piggy-bank" },
];

const features = [
    {
        title: "Leg Rest",
        desc: "Maksimal selunjur",
        icon: "fas fa-couch",
        color: "text-rose-400",
        bg: "bg-rose-400/10",
        colSpan: "col-span-1 md:col-span-2",
    },
    {
        title: "Entertain",
        desc: "Audio Video on Demand",
        icon: "fas fa-tv",
        color: "text-blue-400",
        bg: "bg-blue-400/10",
        colSpan: "col-span-1",
    },
    {
        title: "Snack",
        desc: "Gratis snack & minum",
        icon: "fas fa-cookie-bite",
        color: "text-amber-400",
        bg: "bg-amber-400/10",
        colSpan: "col-span-1",
    },
    {
        title: "Charger",
        desc: "USB Port tiap kursi",
        icon: "fas fa-bolt",
        color: "text-emerald-400",
        bg: "bg-emerald-400/10",
        colSpan: "col-span-1 md:col-span-2",
    },
];

const formatDate = (dateString) => {
    if (!dateString) return "Tanggal Belum Tersedia";
    const date = new Date(dateString);
    if (isNaN(date.getTime()) || date.getFullYear() <= 1970)
        return "Tanggal Belum Tersedia";
    return date.toLocaleDateString("id-ID", {
        day: "numeric",
        month: "long",
        year: "numeric",
    });
};

// Computed
const today = computed(() => new Date().toISOString().split("T")[0]);

// Methods
const filterOrigins = () => {
    if (!origin.value) {
        filteredOrigins.value = props.origins || [];
    } else {
        filteredOrigins.value = (props.origins || []).filter((o) =>
            o.toLowerCase().includes(origin.value.toLowerCase()),
        );
    }
    showOriginDropdown.value = true;
};

const filterDestinations = () => {
    if (!destination.value) {
        filteredDestinations.value = props.destinations || [];
    } else {
        filteredDestinations.value = (props.destinations || []).filter((d) =>
            d.toLowerCase().includes(destination.value.toLowerCase()),
        );
    }
    showDestinationDropdown.value = true;
};

const selectOrigin = (val) => {
    origin.value = val;
    showOriginDropdown.value = false;
};

const selectDestination = (val) => {
    destination.value = val;
    showDestinationDropdown.value = false;
};

const swapLocations = () => {
    [origin.value, destination.value] = [destination.value, origin.value];
};

const formatPrice = (price) => {
    return new Intl.NumberFormat("id-ID", {
        style: "currency",
        currency: "IDR",
        minimumFractionDigits: 0,
    }).format(price);
};

// Animations
onMounted(() => {
    filteredOrigins.value = props.origins || [];
    filteredDestinations.value = props.destinations || [];

    // GSAP Hero Animation
    gsap.from(".hero-text-char", {
        y: 100,
        opacity: 0,
        duration: 1,
        stagger: 0.05,
        ease: "power4.out",
    });

    gsap.from(".hero-console", {
        y: 50,
        opacity: 0,
        duration: 1,
        delay: 0.5,
        ease: "power3.out",
    });

    // Scroll Trigger for Bento Grid
    gsap.utils.toArray(".bento-item").forEach((item, i) => {
        gsap.from(item, {
            scrollTrigger: {
                trigger: item,
                start: "top 85%",
            },
            y: 50,
            opacity: 0,
            duration: 0.8,
            delay: i * 0.1,
            ease: "back.out(1.7)",
        });
    });

    // Close dropdowns on click outside
    document.addEventListener("click", (e) => {
        if (!e.target.closest(".origin-group"))
            showOriginDropdown.value = false;
        if (!e.target.closest(".destination-group"))
            showDestinationDropdown.value = false;
    });
});
</script>

<template>
    <Head title="Future Travel" />

    <div
        class="bg-gray-50 dark:bg-[#050505] min-h-screen text-gray-900 dark:text-gray-100 font-manrope selection:bg-rose-600 selection:text-white overflow-x-hidden transition-colors duration-300"
    >
        <!-- HERO SECTION -->
        <section
            class="relative min-h-screen flex items-center justify-center px-4 sm:px-6 lg:px-8 py-20 overflow-hidden"
        >
            <!-- Minimal Background -->
            <div class="absolute inset-0 z-0 select-none">
                <!-- Solid Overlays -->
                <div
                    class="absolute inset-0 bg-white/90 dark:bg-black/70 z-10 transition-colors duration-300"
                ></div>
                <img
                    src="/img/heroImg.jpg"
                    alt="Background"
                    class="w-full h-full object-cover grayscale opacity-40 dark:opacity-50 scale-105 animate-subtle-zoom"
                />
            </div>

            <div
                class="relative z-20 w-full max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-12 gap-12 items-center"
            >
                <!-- Hero Typography -->
                <div class="lg:col-span-7 space-y-8 text-center lg:text-left">
                    <div
                        class="inline-flex items-center gap-3 px-5 py-2.5 rounded-full bg-white dark:bg-white/10 border border-gray-200 dark:border-white/10 backdrop-blur-md mb-4 shadow-sm"
                    >
                        <span
                            class="w-2 h-2 rounded-full bg-rose-600 animate-pulse"
                        ></span>
                        <span
                            class="text-xs font-bold tracking-[0.2em] font-unbounded text-rose-600 dark:text-white uppercase"
                            >Revolusi Perjalanan Darat</span
                        >
                    </div>

                    <h1
                        class="text-4xl sm:text-7xl lg:text-8xl font-black font-unbounded leading-[0.9] tracking-tight text-gray-900 dark:text-white"
                    >
                        <div class="overflow-hidden">
                            <span class="hero-text-char inline-block"
                                >JELAJAH</span
                            >
                        </div>
                        <!-- Solid Red Emphasis -->
                        <div class="overflow-hidden">
                            <span
                                class="hero-text-char inline-block text-rose-600"
                                >TANPA BATAS</span
                            >
                        </div>
                    </h1>

                    <p
                        class="text-lg text-gray-600 dark:text-gray-400 max-w-xl mx-auto lg:mx-0 font-medium leading-relaxed"
                    >
                        Spesialis AKAP Kuningan - Jakarta & Rangkasbitung via
                        berbagai jalur strategis. Hadir dengan layanan bus malam
                        premium dan divisi pariwisata yang siap menemani setiap
                        momen perjalanan Anda dengan kenyamanan maksimal.
                    </p>

                    <div
                        class="flex flex-wrap gap-4 justify-center lg:justify-start pt-6"
                    >
                        <!-- Solid High-Contrast Button -->
                        <Link
                            ref="heroBtn1"
                            :href="route('frontend.fleet.index')"
                            class="group relative px-10 py-5 bg-rose-600 text-white font-unbounded font-bold rounded-full overflow-hidden transition-all hover:bg-rose-700 hover:scale-[1.02] shadow-xl shadow-rose-600/20 inline-flex items-center"
                        >
                            <span class="relative z-10 flex items-center gap-2">
                                Lihat Armada
                                <i
                                    class="fas fa-arrow-right text-sm -rotate-45 group-hover:rotate-0 transition-transform"
                                ></i>
                            </span>
                        </Link>
                        <Link
                            ref="heroBtn2"
                            :href="route('frontend.routes.index')"
                            class="px-10 py-5 bg-transparent border-2 border-gray-200 dark:border-white/20 text-gray-900 dark:text-white font-unbounded font-bold rounded-full hover:bg-gray-100 dark:hover:bg-white/10 transition-all inline-flex items-center"
                        >
                            Rute Aktif
                        </Link>
                    </div>
                </div>

                <!-- Travel Console (Clean Glass) -->
                <div class="lg:col-span-5 hero-console">
                    <div
                        class="bg-white/80 dark:bg-[#111]/80 backdrop-blur-2xl border border-white/20 dark:border-white/10 p-8 rounded-[2.5rem] shadow-2xl shadow-gray-200/50 dark:shadow-black/50 relative overflow-hidden transition-colors duration-300"
                    >
                        <h3
                            class="text-2xl font-unbounded font-bold text-gray-900 dark:text-white mb-8 flex items-center gap-3"
                        >
                            <span
                                class="w-8 h-8 rounded-full bg-rose-600 flex items-center justify-center text-white text-sm"
                            >
                                <i class="fas fa-ticket-alt"></i>
                            </span>
                            Pesan Tiket
                        </h3>

                        <form
                            :action="route('frontend.booking.index')"
                            method="GET"
                            class="space-y-6 relative z-10"
                        >
                            <!-- Origin -->
                            <div class="relative origin-group">
                                <label
                                    class="text-[11px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-2 block"
                                    >Keberangkatan</label
                                >
                                <div class="relative group">
                                    <div
                                        class="absolute left-0 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-rose-600 transition-colors pl-4"
                                    >
                                        <i
                                            class="fas fa-map-marker-alt text-lg"
                                        ></i>
                                    </div>
                                    <input
                                        v-model="origin"
                                        @input="filterOrigins"
                                        @focus="showOriginDropdown = true"
                                        type="text"
                                        name="origin"
                                        class="w-full bg-gray-50 dark:bg-white/5 border-2 border-transparent focus:border-rose-600 rounded-2xl py-4 pl-12 pr-4 text-lg font-bold text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:bg-white dark:focus:bg-black transition-all font-manrope"
                                        placeholder="Dari mana?"
                                        autocomplete="off"
                                    />
                                    <!-- Dropdown -->
                                    <div
                                        v-if="
                                            showOriginDropdown &&
                                            filteredOrigins.length
                                        "
                                        class="absolute top-full left-0 right-0 mt-2 bg-white dark:bg-[#1a1a1a] border border-gray-100 dark:border-white/10 rounded-2xl shadow-xl z-50 max-h-60 overflow-y-auto p-2"
                                    >
                                        <div
                                            v-for="city in filteredOrigins"
                                            :key="city"
                                            @click="selectOrigin(city)"
                                            class="px-4 py-3 rounded-xl hover:bg-gray-50 dark:hover:bg-white/5 cursor-pointer text-gray-700 dark:text-gray-200 font-medium transition-colors"
                                        >
                                            {{ city }}
                                        </div>
                                    </div>
                                </div>

                                <!-- Swap Button (Absolute to Origin, Centered in Gap) -->
                                <div
                                    class="absolute right-4 top-[calc(100%+12px)] -translate-y-1/2 z-30"
                                >
                                    <button
                                        type="button"
                                        @click="swapLocations"
                                        class="w-10 h-10 rounded-full bg-white dark:bg-[#222] border-2 border-gray-100 dark:border-[#333] text-gray-400 hover:text-rose-600 hover:border-rose-600 transition-all flex items-center justify-center shadow-lg transform hover:rotate-180 duration-300"
                                    >
                                        <i
                                            class="fas fa-exchange-alt text-sm"
                                        ></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Destination -->
                            <div class="relative destination-group">
                                <label
                                    class="text-[11px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-2 block"
                                    >Tujuan</label
                                >
                                <div class="relative group">
                                    <div
                                        class="absolute left-0 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-rose-600 transition-colors pl-4"
                                    >
                                        <i
                                            class="fas fa-location-dot text-lg"
                                        ></i>
                                    </div>
                                    <input
                                        v-model="destination"
                                        @input="filterDestinations"
                                        @focus="showDestinationDropdown = true"
                                        type="text"
                                        name="destination"
                                        class="w-full bg-gray-50 dark:bg-white/5 border-2 border-transparent focus:border-rose-600 rounded-2xl py-4 pl-12 pr-4 text-lg font-bold text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:bg-white dark:focus:bg-black transition-all font-manrope"
                                        placeholder="Mau kemana?"
                                        autocomplete="off"
                                    />
                                    <!-- Dropdown -->
                                    <div
                                        v-if="
                                            showDestinationDropdown &&
                                            filteredDestinations.length
                                        "
                                        class="absolute top-full left-0 right-0 mt-2 bg-white dark:bg-[#1a1a1a] border border-gray-100 dark:border-white/10 rounded-2xl shadow-xl z-50 max-h-60 overflow-y-auto p-2"
                                    >
                                        <div
                                            v-for="city in filteredDestinations"
                                            :key="city"
                                            @click="selectDestination(city)"
                                            class="px-4 py-3 rounded-xl hover:bg-gray-50 dark:hover:bg-white/5 cursor-pointer text-gray-700 dark:text-gray-200 font-medium transition-colors"
                                        >
                                            {{ city }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <!-- Date -->
                                <div class="relative">
                                    <label
                                        class="text-[11px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-2 block"
                                        >Tanggal</label
                                    >
                                    <input
                                        v-model="date"
                                        name="date"
                                        type="date"
                                        :min="today"
                                        class="w-full bg-gray-50 dark:bg-white/5 border-2 border-transparent focus:border-rose-600 rounded-2xl py-4 pl-4 pr-2 text-gray-900 dark:text-white font-bold focus:outline-none focus:bg-white dark:focus:bg-black transition-all font-manrope [color-scheme:light] dark:[color-scheme:dark]"
                                    />
                                </div>

                                <!-- Class -->
                                <div class="relative">
                                    <label
                                        class="text-[11px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-2 block"
                                        >Kelas</label
                                    >
                                    <select
                                        v-model="busType"
                                        name="class"
                                        class="w-full bg-gray-50 dark:bg-white/5 border-2 border-transparent focus:border-rose-600 rounded-2xl py-4 pl-4 pr-8 text-gray-900 dark:text-white font-bold focus:outline-none focus:bg-white dark:focus:bg-black transition-all font-manrope appearance-none cursor-pointer"
                                    >
                                        <option
                                            v-for="t in busTypes"
                                            :key="t.id"
                                            :value="t.id"
                                            class="bg-white dark:bg-[#222]"
                                        >
                                            {{ t.name }}
                                        </option>
                                    </select>
                                    <i
                                        class="fas fa-chevron-down absolute right-4 top-[3.2rem] text-gray-400 text-xs pointer-events-none"
                                    ></i>
                                </div>
                            </div>

                            <button
                                type="submit"
                                ref="searchBtn"
                                class="w-full py-5 bg-rose-600 text-white font-unbounded font-bold rounded-2xl shadow-xl shadow-rose-600/30 hover:bg-rose-700 hover:scale-[1.02] active:scale-[0.98] transition-all duration-300 flex items-center justify-center gap-3 group"
                            >
                                <i
                                    class="fas fa-search group-hover:rotate-90 transition-transform duration-300"
                                ></i>
                                <span>Cari Jadwal Perjalanan</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </section>

        <!-- BENTO FEATURES (Solid & Clean) -->
        <section class="py-24 relative z-10">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div
                    class="mb-16 flex flex-col md:flex-row md:items-end justify-between gap-6"
                >
                    <div>
                        <h2
                            class="text-3xl md:text-5xl font-black font-unbounded text-gray-900 dark:text-white mb-4"
                        >
                            Standar <span class="text-rose-600">Premium</span>
                        </h2>
                        <p
                            class="text-gray-500 dark:text-gray-400 max-w-xl text-lg font-medium"
                        >
                            Meningkatkan kenyamanan perjalanan Anda dengan
                            fasilitas kelas satu dan kebersihan terjamin.
                        </p>
                    </div>
                </div>

                <div
                    class="grid grid-cols-1 md:grid-cols-4 gap-6 auto-rows-[220px]"
                >
                    <!-- Stats Block (Solid Rose) -->
                    <div
                        class="bento-item col-span-1 md:col-span-2 row-span-2 bg-rose-600 rounded-[2.5rem] p-10 flex flex-col justify-between relative overflow-hidden group shadow-2xl shadow-rose-900/20"
                    >
                        <!-- Abstract Clean Shapes -->
                        <div
                            class="absolute -right-10 -top-10 w-64 h-64 bg-rose-500 rounded-full opacity-50 blur-3xl"
                        ></div>
                        <i
                            class="fas fa-bus text-[10rem] text-rose-800 opacity-20 absolute -bottom-8 -right-8 rotate-[-15deg] group-hover:rotate-0 transition-transform duration-700 ease-out"
                        ></i>

                        <div class="relative z-10">
                            <div class="flex items-center gap-3 mb-6">
                                <span
                                    class="w-3 h-3 rounded-full bg-white animate-pulse"
                                ></span>
                                <span
                                    class="text-rose-200 font-bold tracking-widest text-xs uppercase"
                                    >Status Armada</span
                                >
                            </div>
                            <div
                                class="text-6xl md:text-8xl font-black font-unbounded text-white tracking-tighter"
                            >
                                {{ fleetCount
                                }}<span class="text-3xl md:text-4xl align-top"
                                    >+</span
                                >
                            </div>
                        </div>
                        <div
                            class="text-rose-100 font-medium text-xl max-w-xs relative z-10 leading-snug"
                        >
                            Armada modern beroperasi setiap hari di rute
                            Trans-Jawa.
                        </div>
                    </div>

                    <!-- Feature Items (Clean Cards) -->
                    <div
                        v-for="(feat, i) in features"
                        :key="i"
                        class="bento-item rounded-[2.5rem] p-8 bg-white dark:bg-[#111] hover:bg-gray-50 dark:hover:bg-[#151515] transition-all border border-gray-100 dark:border-[#222] group shadow-sm hover:shadow-md flex flex-col justify-between"
                        :class="feat.colSpan"
                    >
                        <div
                            class="w-14 h-14 rounded-2xl bg-gray-50 dark:bg-[#222] flex items-center justify-center text-2xl mb-4 group-hover:scale-110 transition-transform duration-300"
                        >
                            <!-- Solid Colors for icons, no gradients -->
                            <i
                                :class="[feat.icon]"
                                class="text-gray-900 dark:text-white"
                            ></i>
                        </div>
                        <div>
                            <h3
                                class="font-unbounded font-bold text-gray-900 dark:text-white text-xl mb-2"
                            >
                                {{ feat.title }}
                            </h3>
                            <p
                                class="text-gray-500 dark:text-gray-400 text-sm font-medium leading-relaxed"
                            >
                                {{ feat.desc }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- SERVICE EXCELLENCE (AKAP & Tourism) -->
        <section
            class="py-24 bg-gray-50 dark:bg-[#080808] relative overflow-hidden"
        >
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div
                    class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center"
                >
                    <div class="space-y-8">
                        <div>
                            <span
                                class="text-rose-600 font-black tracking-widest text-xs uppercase font-unbounded mb-4 block"
                                >Our Specialization</span
                            >
                            <h2
                                class="text-4xl md:text-5xl font-black font-unbounded text-gray-900 dark:text-white leading-tight"
                            >
                                Menghubungkan
                                <span class="text-rose-600">Kuningan</span> ke
                                Ibu Kota
                            </h2>
                        </div>
                        <p
                            class="text-gray-500 dark:text-gray-400 text-lg leading-relaxed font-medium"
                        >
                            Sebagai Po yang lahir dari kecintaan pada layanan
                            transportasi, kami mendedikasikan diri sebagai
                            spesialis rute
                            <span
                                class="text-gray-900 dark:text-white font-bold"
                                >Kuningan - Jakarta</span
                            >
                            via berbagai jalur strategis serta menjangkau
                            <span
                                class="text-gray-900 dark:text-white font-bold"
                                >Rangkasbitung</span
                            >. Kami memastikan setiap penumpang mendapatkan
                            pengalaman "Executive Treatment" di setiap kilometer
                            perjalanan.
                        </p>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div
                                class="p-6 bg-white dark:bg-[#111] rounded-3xl border border-gray-100 dark:border-white/5 shadow-sm"
                            >
                                <i
                                    class="fas fa-map-marked-alt text-rose-600 text-2xl mb-4"
                                ></i>
                                <h4
                                    class="font-unbounded font-bold text-gray-900 dark:text-white mb-2 text-sm uppercase"
                                >
                                    AKAP Division
                                </h4>
                                <p class="text-xs text-gray-500 font-medium">
                                    Layanan bus malam reguler dengan sasis
                                    premium dan ketepatan waktu tinggi.
                                </p>
                            </div>
                            <div
                                class="p-6 bg-white dark:bg-[#111] rounded-3xl border border-gray-100 dark:border-white/5 shadow-sm"
                            >
                                <i
                                    class="fas fa-umbrella-beach text-rose-600 text-2xl mb-4"
                                ></i>
                                <h4
                                    class="font-unbounded font-bold text-gray-900 dark:text-white mb-2 text-sm uppercase"
                                >
                                    Tourism Division
                                </h4>
                                <p class="text-xs text-gray-500 font-medium">
                                    Sewa bus pariwisata untuk kebutuhan grup,
                                    ziarah, dan perjalanan wisata keluarga.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="relative">
                        <div
                            class="aspect-[4/5] rounded-[3rem] overflow-hidden shadow-2xl relative group"
                        >
                            <img
                                src="/img/heroImg.jpg"
                                alt="Fleet Service"
                                class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                            />
                            <div
                                class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent"
                            ></div>
                            <div class="absolute bottom-10 left-10 right-10">
                                <div
                                    class="p-6 bg-white/10 backdrop-blur-xl border border-white/20 rounded-2xl"
                                >
                                    <div class="flex items-center gap-4">
                                        <div
                                            class="w-12 h-12 rounded-full bg-rose-600 flex items-center justify-center text-white"
                                        >
                                            <i class="fas fa-shield-alt"></i>
                                        </div>
                                        <div>
                                            <p
                                                class="text-white font-bold uppercase tracking-widest text-[10px]"
                                            >
                                                Security Guarantee
                                            </p>
                                            <p class="text-rose-100 text-xs">
                                                Asuransi penumpang & Driver
                                                tersertifikasi
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Floating Badge -->
                        <div
                            class="absolute -top-6 -right-6 w-32 h-32 bg-white dark:bg-[#111] rounded-full flex items-center justify-center shadow-xl border border-gray-100 dark:border-white/5 animate-bounce-slow"
                        >
                            <div class="text-center">
                                <p
                                    class="text-[10px] font-black font-unbounded text-rose-600 uppercase"
                                >
                                    Since
                                </p>
                                <p
                                    class="text-2xl font-black font-unbounded text-gray-900 dark:text-white"
                                >
                                    1973
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- POPULAR ROUTES (Clean Tickets) -->
        <section
            class="py-24 border-t border-gray-100 dark:border-[#111] bg-white dark:bg-[#080808]"
        >
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-end justify-between mb-16">
                    <div>
                        <h2
                            class="text-3xl font-black font-unbounded text-gray-900 dark:text-white"
                        >
                            Rute Populer
                        </h2>
                        <p
                            class="text-gray-500 dark:text-gray-400 mt-2 font-medium"
                        >
                            Destinasi favorit pilihan pelanggan kami.
                        </p>
                    </div>
                    <Link
                        :href="route('frontend.routes.index')"
                        class="hidden md:inline-flex items-center gap-3 text-rose-600 font-bold hover:text-rose-700 transition-colors"
                    >
                        Lihat Semua <i class="fas fa-arrow-right"></i>
                    </Link>
                </div>

                <div
                    class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8"
                >
                    <div
                        v-for="(busRoute, i) in featuredRoutes"
                        :key="busRoute.id"
                        class="group relative bg-white dark:bg-[#111] rounded-[2rem] border border-gray-100 dark:border-[#222] hover:border-rose-600 dark:hover:border-rose-600 transition-all duration-300 hover:-translate-y-2 overflow-hidden"
                    >
                        <div class="p-8 h-full flex flex-col">
                            <div
                                class="flex items-center justify-between mb-12 relative z-10"
                            >
                                <div class="text-left w-2/5">
                                    <div
                                        class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mb-2"
                                    >
                                        DARI
                                    </div>
                                    <div
                                        class="text-xl font-unbounded font-bold text-gray-900 dark:text-white leading-tight"
                                    >
                                        {{ busRoute.origin }}
                                    </div>
                                </div>
                                <div class="w-1/5 flex justify-center">
                                    <div
                                        class="w-12 h-12 rounded-full bg-gray-50 dark:bg-[#222] flex items-center justify-center text-gray-400 group-hover:text-rose-600 group-hover:bg-rose-50 dark:group-hover:bg-rose-900/20 transition-all"
                                    >
                                        <i
                                            class="fas fa-arrow-right transform -rotate-45 group-hover:rotate-0 transition-transform duration-300"
                                        ></i>
                                    </div>
                                </div>
                                <div class="text-right w-2/5">
                                    <div
                                        class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mb-2"
                                    >
                                        KE
                                    </div>
                                    <div
                                        class="text-xl font-unbounded font-bold text-gray-900 dark:text-white leading-tight"
                                    >
                                        {{ busRoute.destination }}
                                    </div>
                                </div>
                            </div>

                            <div class="mt-auto">
                                <Link
                                    :href="
                                        route('frontend.booking.index', {
                                            origin: busRoute.origin,
                                            destination: busRoute.destination,
                                        })
                                    "
                                    class="block w-full py-4 bg-gray-50 dark:bg-[#1a1a1a] hover:bg-rose-600 hover:text-white text-center rounded-xl text-gray-900 dark:text-white font-bold transition-all duration-300"
                                >
                                    Cek Ketersediaan
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- FLEET SHOWREEL (Visual Gallery) -->
        <section
            class="py-24 bg-gray-50 dark:bg-[#050505] relative overflow-hidden"
        >
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2
                        class="text-3xl md:text-5xl font-black font-unbounded text-gray-900 dark:text-white mb-4"
                    >
                        Premium <span class="text-rose-600">Showreel</span>
                    </h2>
                    <p class="text-gray-500 dark:text-gray-400 font-medium">
                        Visualisasi armada Tunggal Jaya yang siap mengantar
                        Anda.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div
                        v-for="(bus, index) in fleet.slice(0, 3)"
                        :key="bus.id"
                        class="group relative aspect-[4/5] rounded-[2.5rem] overflow-hidden shadow-lg bento-item"
                        :class="index === 1 ? 'md:translate-y-12' : ''"
                    >
                        <img
                            :src="bus.image_url || '/img/heroImg.jpg'"
                            :alt="bus.name"
                            class="w-full h-full object-cover md:grayscale group-hover:grayscale-0 transition-all duration-700"
                        />
                        <!-- Overlay: Always visible on mobile, hover on desktop -->
                        <div
                            class="absolute inset-0 bg-gradient-to-t from-rose-900/90 via-rose-900/20 to-transparent opacity-100 md:opacity-0 md:group-hover:opacity-100 transition-opacity duration-500 flex flex-col justify-end p-6 md:p-8"
                        >
                            <h4
                                class="text-white font-unbounded font-bold text-lg md:text-xl mb-1 md:mb-2"
                            >
                                {{ bus.name }}
                            </h4>
                            <p
                                class="text-rose-100 text-[10px] md:text-sm leading-relaxed"
                            >
                                {{ bus.bus_type }} Class dengan fasilitas
                                premium untuk kenyamanan Anda.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- LATEST NEWS SECTION (Unique Poster Style) -->
        <section
            v-if="latestNews && latestNews.length > 0"
            class="py-32 bg-white dark:bg-[#080808] relative overflow-hidden"
        >
            <!-- Background Accents -->
            <div
                class="absolute top-0 right-0 w-1/3 h-full bg-rose-600/5 -skew-x-12 translate-x-1/2"
            ></div>

            <div class="max-w-7xl mx-auto px-6 relative z-10">
                <div
                    class="flex flex-col md:flex-row md:items-end justify-between mb-20 gap-8"
                >
                    <div class="max-w-2xl animate-on-scroll">
                        <h2
                            class="text-5xl md:text-7xl font-black text-gray-900 dark:text-white font-unbounded leading-none mb-6"
                        >
                            NEWS & <br /><span class="text-rose-600"
                                >POSTERS.</span
                            >
                        </h2>
                        <p
                            class="text-gray-500 dark:text-gray-400 text-lg font-medium max-w-md"
                        >
                            Informasi terkini dan update terbaru dari layanan
                            Tunggal Jaya Transport.
                        </p>
                    </div>
                    <Link
                        :href="route('frontend.news.index')"
                        class="group flex items-center gap-4 text-rose-600 font-black font-unbounded text-sm uppercase tracking-widest animate-on-scroll"
                    >
                        Explore All
                        <i
                            class="fas fa-arrow-right transform group-hover:translate-x-2 transition-transform"
                        ></i>
                    </Link>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-12 items-start">
                    <Link
                        v-for="(news, index) in latestNews.slice(0, 3)"
                        :key="news.id"
                        :href="route('frontend.news.show', news.slug)"
                        class="group relative flex flex-col animate-on-scroll"
                        :class="index === 1 ? 'md:mt-24' : ''"
                    >
                        <!-- Large Index Number -->
                        <div
                            class="absolute -top-12 -left-4 text-8xl font-black font-unbounded text-gray-100 dark:text-white/5 pointer-events-none group-hover:text-rose-600/10 transition-colors duration-500"
                        >
                            0{{ index + 1 }}
                        </div>

                        <!-- Poster Card -->
                        <div
                            class="relative aspect-[3/4] overflow-hidden border-[12px] border-white dark:border-[#111] shadow-2xl transition-transform duration-700 group-hover:-rotate-2 group-hover:scale-[1.02]"
                        >
                            <img
                                :src="news.image_url"
                                :alt="news.title"
                                class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition-all duration-700"
                            />
                            <!-- Overlay Info -->
                            <div
                                class="absolute inset-0 bg-gradient-to-t from-gray-900 via-gray-900/20 to-transparent opacity-90 group-hover:opacity-100 transition-opacity"
                            ></div>
                            <div class="absolute bottom-0 left-0 right-0 p-8">
                                <span
                                    class="text-[10px] font-black font-unbounded text-rose-500 uppercase tracking-widest mb-2 block"
                                >
                                    {{ news.category?.name || "Official" }}
                                </span>
                                <h3
                                    class="text-xl font-bold font-unbounded text-white leading-tight group-hover:text-rose-400 transition-colors"
                                >
                                    {{ news.title }}
                                </h3>
                            </div>
                        </div>

                        <!-- Metadata below -->
                        <div
                            class="mt-8 flex items-center justify-between border-b border-gray-100 dark:border-white/10 pb-4"
                        >
                            <span
                                class="text-xs font-bold text-gray-400 uppercase tracking-tighter italic"
                            >
                                {{
                                    formatDate(
                                        news.published_at || news.created_at,
                                    )
                                }}
                            </span>
                            <div
                                class="relative w-8 h-px bg-gray-200 dark:bg-white/10 overflow-hidden"
                            >
                                <div
                                    class="absolute inset-0 bg-rose-600 translate-x-[-100%] group-hover:translate-x-0 transition-transform duration-500"
                                ></div>
                            </div>
                        </div>
                    </Link>
                </div>
            </div>
        </section>
        <section
            class="relative py-40 bg-rose-600 overflow-hidden flex items-center justify-center"
        >
            <!-- Background Marquee - More visible and layered -->
            <div
                class="absolute inset-0 flex flex-col justify-between pointer-events-none select-none opacity-20 text-rose-900 overflow-hidden py-10"
            >
                <div
                    class="text-[5rem] md:text-[10rem] font-black font-unbounded whitespace-nowrap animate-marquee leading-none"
                >
                    TUNGGAL JAYA TRANSPORT &bullet; KEAMANAN &bullet; KENYAMANAN
                    &bullet;
                </div>
                <div
                    class="text-[5rem] md:text-[10rem] font-black font-unbounded whitespace-nowrap animate-marquee-reverse leading-none ml-20"
                >
                    &bullet; PREMIUM CLASS &bullet; EKSEKUTIF &bullet; WISATA
                    &bullet;
                </div>
            </div>

            <div class="relative z-10 max-w-5xl mx-auto px-6 text-center">
                <div
                    class="inline-block mb-6 px-6 py-2 rounded-full border-2 border-white/30 bg-white/10 backdrop-blur-md text-white font-bold tracking-widest text-xs uppercase"
                >
                    Siap Berangkat?
                </div>

                <h2
                    class="text-4xl sm:text-6xl md:text-8xl font-black font-unbounded text-white mb-8 leading-tight tracking-tight drop-shadow-lg"
                >
                    MULAI <br />
                    PERJALANANMU
                </h2>

                <p
                    class="text-xl text-rose-50 font-medium mb-12 max-w-2xl mx-auto leading-relaxed"
                >
                    Bergabunglah dengan ribuan penumpang yang telah merasakan
                    kenyamanan maksimal. Pesan tiketmu sekarang, semudah satu
                    klik.
                </p>

                <div
                    class="flex flex-col sm:flex-row items-center justify-center gap-6"
                >
                    <Link
                        ref="ctaBtn1"
                        :href="route('frontend.booking.index')"
                        class="w-full sm:w-auto px-12 py-6 bg-white text-rose-600 rounded-full font-black font-unbounded text-lg hover:scale-105 transition-transform shadow-[0_20px_50px_rgba(0,0,0,0.3)] hover:shadow-[0_25px_60px_rgba(0,0,0,0.4)] flex items-center justify-center gap-3"
                    >
                        PESAN SEKARANG <i class="fas fa-paper-plane"></i>
                    </Link>
                    <Link
                        ref="ctaBtn2"
                        :href="route('frontend.fleet.index')"
                        class="w-full sm:w-auto px-12 py-6 bg-rose-800/40 border-2 border-white/20 backdrop-blur-sm text-white rounded-full font-bold font-unbounded text-lg hover:bg-rose-800/60 transition-colors flex items-center justify-center gap-3"
                    >
                        Lihat Armada
                    </Link>
                </div>
            </div>
        </section>
    </div>
</template>

<style scoped>
@keyframes subtle-zoom {
    0% {
        transform: scale(1.05);
    }
    100% {
        transform: scale(1.15);
    }
}

.animate-subtle-zoom {
    animation: subtle-zoom 20s infinite alternate linear;
}

@keyframes marquee {
    0% {
        transform: translateX(0);
    }
    100% {
        transform: translateX(-50%);
    }
}

.animate-marquee {
    animation: marquee 30s linear infinite;
}

@keyframes marquee-reverse {
    0% {
        transform: translateX(-50%);
    }
    100% {
        transform: translateX(0);
    }
}

.animate-marquee-reverse {
    animation: marquee-reverse 35s linear infinite;
}
</style>
