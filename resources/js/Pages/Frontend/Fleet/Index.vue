<script setup>
import { ref, computed } from "vue";
import { Head, Link } from "@inertiajs/vue3";
import FrontendLayout from "@/Layouts/FrontendLayout.vue";

defineOptions({ layout: FrontendLayout });

const props = defineProps({
    buses: Array,
    facilities: Array,
});

const selectedType = ref("all");
const searchQuery = ref("");

const busTypes = [
    { id: "all", name: "Semua Kelas" },
    { id: "Executive", name: "Executive" },
    { id: "Business", name: "Bisnis" },
    { id: "Economy", name: "Ekonomi" },
];

const filteredBuses = computed(() => {
    return props.buses.filter((bus) => {
        const matchesType =
            selectedType.value === "all" || bus.bus_type === selectedType.value;
        const matchesSearch = bus.name
            .toLowerCase()
            .includes(searchQuery.value.toLowerCase());
        return matchesType && matchesSearch;
    });
});
</script>

<template>
    <Head title="Armada Kami" />

    <div
        class="min-h-screen bg-gray-50 dark:bg-[#050505] font-sans selection:bg-rose-600 selection:text-white"
    >
        <!-- Hero Header -->
        <div
            class="relative pt-24 md:pt-32 pb-20 px-4 sm:px-6 lg:px-8 overflow-hidden"
        >
            <!-- Background Gradients -->
            <div
                class="absolute top-0 right-0 w-[500px] h-[500px] bg-rose-600/5 rounded-full blur-[120px] -z-10"
            ></div>
            <div
                class="absolute bottom-0 left-0 w-[500px] h-[500px] bg-rose-600/5 rounded-full blur-[120px] -z-10"
            ></div>

            <div class="max-w-7xl mx-auto text-center relative z-10">
                <span
                    class="inline-block py-1 px-3 rounded-full bg-rose-50 dark:bg-rose-900/10 text-rose-600 border border-rose-100 dark:border-rose-900/20 text-xs font-bold tracking-widest uppercase mb-6 font-unbounded animate-fade-in-up"
                >
                    Modern & Nyaman
                </span>
                <h1
                    class="text-3xl sm:text-4xl md:text-6xl font-black text-gray-900 dark:text-white mb-6 font-unbounded animate-fade-in-up"
                    style="animation-delay: 0.1s"
                >
                    Armada <span class="text-rose-600">Premium</span>
                </h1>
                <p
                    class="text-lg text-gray-500 dark:text-gray-400 max-w-2xl mx-auto  animate-fade-in-up"
                    style="animation-delay: 0.2s"
                >
                    Jelajahi pilihan armada terbaik kami yang dirancang dengan
                    standar keselamatan tertinggi dan kenyamanan tanpa kompromi.
                </p>
            </div>
        </div>

        <!-- Filter & Search Section (sticky disabled) -->
        <div class="relative px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto mb-16">
            <div
                class="bg-white/80 dark:bg-[#111]/80 backdrop-blur-xl p-4 md:p-6 rounded-2xl shadow-xl shadow-black/5 border border-gray-100 dark:border-white/5 flex flex-col md:flex-row justify-between items-center gap-6 animate-fade-in-up"
                style="animation-delay: 0.3s"
            >
                <!-- Search -->
                <div class="relative w-full md:w-96 group">
                    <div
                        class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none"
                    >
                        <i
                            class="fas fa-search text-gray-400 group-focus-within:text-rose-600 transition-colors"
                        ></i>
                    </div>
                    <input
                        v-model="searchQuery"
                        type="text"
                        placeholder="Cari nama bus..."
                        class="block w-full pl-12 pr-4 py-3 bg-gray-50 dark:bg-[#1a1a1a] border-transparent focus:border-rose-600 focus:bg-white dark:focus:bg-[#0a0a0a] focus:ring-0 rounded-xl text-gray-900 dark:text-white placeholder-gray-500 transition-all "
                    />
                </div>

                <!-- Filters -->
                <div class="flex flex-wrap gap-2 justify-center">
                    <button
                        v-for="type in busTypes"
                        :key="type.id"
                        @click="selectedType = type.id"
                        class="px-5 py-2.5 rounded-xl text-xs font-bold uppercase tracking-wider font-unbounded transition-all duration-300 border"
                        :class="
                            selectedType === type.id
                                ? 'bg-rose-600 text-white border-rose-600 shadow-lg shadow-rose-600/30'
                                : 'bg-transparent text-gray-600 dark:text-gray-400 border-gray-200 dark:border-white/10 hover:border-gray-400 dark:hover:border-white/30'
                        "
                    >
                        {{ type.name }}
                    </button>
                </div>
            </div>
        </div>

        <!-- Main Grid -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-32">
            <!-- Empty State -->
            <div
                v-if="filteredBuses.length === 0"
                class="text-center py-32 border-2 border-dashed border-gray-200 dark:border-white/5 rounded-3xl animate-fade-in-up"
            >
                <div
                    class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-gray-100 dark:bg-white/5 mb-6"
                >
                    <i class="fas fa-bus text-3xl text-gray-400"></i>
                </div>
                <h3
                    class="text-xl font-bold font-unbounded text-gray-900 dark:text-white mb-2"
                >
                    Armada Tidak Ditemukan
                </h3>
                <p class="text-gray-500 dark:text-gray-400 ">
                    Silakan coba kata kunci atau filter lain.
                </p>
                <button
                    @click="
                        selectedType = 'all';
                        searchQuery = '';
                    "
                    class="mt-8 text-rose-600 font-bold text-sm uppercase tracking-wider hover:underline font-unbounded"
                >
                    Reset Semua Filter
                </button>
            </div>

            <!-- Bus Cards -->
            <div
                v-else
                class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8"
            >
                <div
                    v-for="(bus, index) in filteredBuses"
                    :key="bus.id"
                    class="group relative bg-white dark:bg-[#111] rounded-[2rem] overflow-hidden border border-gray-100 dark:border-white/5 hover:border-rose-600/30 transition-all duration-500 hover:shadow-2xl hover:shadow-rose-600/10 flex flex-col h-full animate-fade-in-up"
                    :style="{ animationDelay: `${index * 0.1 + 0.4}s` }"
                >
                    <!-- Image Area -->
                    <div class="relative h-72 overflow-hidden">
                        <img
                            v-if="bus.media && bus.media.length"
                            :src="bus.media[0].original_url"
                            :alt="bus.name"
                            class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                        />
                        <div
                            v-else
                            class="w-full h-full bg-gray-100 dark:bg-[#1a1a1a] flex items-center justify-center"
                        >
                            <i
                                class="fas fa-bus text-4xl text-gray-300 dark:text-white/20"
                            ></i>
                        </div>

                        <!-- Gradient Overlay -->
                        <div
                            class="absolute inset-0 bg-gradient-to-t from-[#111] via-transparent to-transparent opacity-90 transition-opacity duration-300 group-hover:opacity-80"
                        ></div>

                        <!-- Top Badges -->
                        <div
                            class="absolute top-6 left-6 right-6 flex justify-between items-start"
                        >
                            <span
                                class="px-3 py-1 rounded-lg bg-white/10 backdrop-blur-md border border-white/20 text-white text-[10px] font-bold uppercase tracking-widest font-unbounded"
                            >
                                {{ bus.plate_number }}
                            </span>
                            <span
                                v-if="bus.year"
                                class="px-3 py-1 rounded-lg bg-black/50 backdrop-blur-md border border-white/10 text-white text-[10px] font-bold uppercase tracking-widest font-unbounded"
                            >
                                {{ bus.year }}
                            </span>
                        </div>

                        <!-- Bottom Content (Over Image) -->
                        <div class="absolute bottom-6 left-6 right-6 z-10">
                            <h3
                                class="text-xl sm:text-2xl font-black font-unbounded text-white mb-1 group-hover:text-rose-500 transition-colors"
                            >
                                {{ bus.name }}
                            </h3>
                            <span
                                class="inline-block px-3 py-1 rounded bg-rose-600 text-white text-[10px] font-bold uppercase tracking-widest font-unbounded"
                            >
                                {{ bus.bus_type }}
                            </span>
                        </div>
                    </div>

                    <!-- Details Body -->
                    <div class="p-6 md:p-8 flex-grow flex flex-col">
                        <div class="flex items-center gap-4 mb-6">
                            <div
                                class="flex items-center gap-2 bg-gray-50 dark:bg-white/5 px-3 py-2 rounded-lg border border-gray-100 dark:border-white/5"
                            >
                                <img
                                    src="/img/car-seat.png"
                                    alt="seat"
                                    class="w-4 h-4 opacity-50 dark:invert"
                                />
                                <span
                                    class="text-xs font-bold text-gray-700 dark:text-gray-300 "
                                    >{{ bus.capacity }} Seat</span
                                >
                            </div>
                            <div
                                class="flex items-center gap-2 bg-gray-50 dark:bg-white/5 px-3 py-2 rounded-lg border border-gray-100 dark:border-white/5"
                            >
                                <i
                                    class="fas fa-check-circle text-rose-600 text-xs"
                                ></i>
                                <span
                                    class="text-xs font-bold text-gray-700 dark:text-gray-300 "
                                    >Ready</span
                                >
                            </div>
                        </div>

                        <p
                            class="text-sm text-gray-500 dark:text-gray-400  leading-relaxed mb-8 flex-grow line-clamp-3"
                        >
                            {{
                                bus.description ||
                                "Rasakan kenyamanan perjalanan dengan armada premium kami yang dilengkapi berbagai fasilitas modern."
                            }}
                        </p>

                        <div
                            class="pt-6 border-t border-gray-100 dark:border-white/5 flex items-center justify-between"
                        >
                            <div class="flex -space-x-3">
                                <!-- Fake avatars for "social proof" feel -->
                                <div
                                    v-for="i in 3"
                                    :key="i"
                                    class="w-8 h-8 rounded-full border-2 border-white dark:border-[#111] bg-gray-200 dark:bg-[#222] flex items-center justify-center text-[10px] text-gray-500"
                                >
                                    <i class="fas fa-user"></i>
                                </div>
                            </div>
                            <Link
                                :href="route('frontend.booking.index')"
                                class="flex items-center gap-2 text-sm font-bold font-unbounded text-rose-600 hover:text-rose-500 transition-colors group/link"
                            >
                                Pesan Sekarang
                                <i
                                    class="fas fa-arrow-right transform group-hover/link:translate-x-1 transition-transform"
                                ></i>
                            </Link>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Global Facilities Section -->
            <div
                class="mt-32 relative bg-[#111] rounded-[3rem] p-8 md:p-24 overflow-hidden text-center animate-fade-in-up"
            >
                <!-- Decorative Bg -->
                <div
                    class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-full max-w-4xl bg-rose-600/10 blur-[150px] rounded-full pointer-events-none"
                ></div>

                <div class="relative z-10">
                    <span
                        class="inline-block py-1 px-3 rounded-full bg-white/10 text-white border border-white/20 text-[10px] font-bold tracking-widest uppercase mb-6 font-unbounded"
                    >
                        Standar Pelayanan
                    </span>
                    <h2
                        class="text-2xl sm:text-3xl md:text-5xl font-black text-white font-unbounded mb-6"
                    >
                        Fasilitas <span class="text-rose-600">Premium</span>
                    </h2>
                    <p
                        class="text-gray-400 max-w-2xl mx-auto  mb-16"
                    >
                        Kami memastikan setiap perjalanan Anda dilengkapi dengan
                        fasilitas terbaik untuk menjamin kenyamanan dari awal
                        hingga akhir.
                    </p>

                    <div
                        class="grid grid-cols-2 md:grid-cols-4 gap-8 md:gap-12"
                    >
                        <div
                            v-for="facility in facilities"
                            :key="facility.name"
                            class="group"
                        >
                            <div
                                class="w-20 h-20 mx-auto bg-white/5 rounded-2xl border border-white/10 flex items-center justify-center mb-6 group-hover:scale-110 group-hover:bg-rose-600 group-hover:border-rose-600 transition-all duration-300 shadow-xl"
                            >
                                <i
                                    :class="[
                                        facility.icon,
                                        'text-3xl text-gray-400 group-hover:text-white transition-colors',
                                    ]"
                                ></i>
                            </div>
                            <h4
                                class="text-lg font-bold text-white font-unbounded mb-2"
                            >
                                {{ facility.name }}
                            </h4>
                            <p
                                class="text-xs text-gray-500 group-hover:text-gray-300 transition-colors "
                            >
                                {{ facility.description }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
