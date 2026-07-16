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
const selectedCategory = ref("all");
const searchQuery = ref("");

const busTypes = [
    { id: "all", name: "Semua Kelas" },
    { id: "Executive", name: "Executive" },
    { id: "Super Executive", name: "Super Executive" },
    { id: "Sleeper", name: "Sleeper" },
    { id: "VIP", name: "VIP" },
];

const filteredBuses = computed(() => {
    return props.buses.filter((bus) => {
        const matchesType =
            selectedType.value === "all" || bus.bus_type === selectedType.value;
        const matchesCategory =
            selectedCategory.value === "all" || bus.bus_category === selectedCategory.value;
        const matchesSearch = bus.name
            .toLowerCase()
            .includes(searchQuery.value.toLowerCase());
        return matchesType && matchesCategory && matchesSearch;
    });
});
</script>

<template>
    <Head title="Armada Kami" />

    <div class="min-h-screen bg-[#fcf9f8]">
        <!-- Hero Header -->
        <div class="relative pt-28 md:pt-36 pb-20 px-4 sm:px-6 lg:px-8 overflow-hidden">
            <div class="absolute top-0 right-0 w-[600px] h-[600px] bg-rose-600/5 rounded-full blur-[120px] pointer-events-none"></div>
            <div class="max-w-7xl mx-auto text-center relative">
                <span class="inline-block px-4 py-1.5 rounded-full bg-white border border-[#ebe7e7] text-rose-600 text-[11px] font-bold tracking-widest uppercase mb-6 shadow-sm">
                    Modern & Nyaman
                </span>
                <h1 class="font-unbounded font-black text-4xl md:text-6xl text-[#1c1b1b] mb-4">
                    Armada <span class="text-rose-600">Premium</span>
                </h1>
                <p class="text-[#454652] text-[16px] md:text-[18px] max-w-2xl mx-auto">
                    Jelajahi pilihan armada terbaik kami yang dirancang dengan standar keselamatan tertinggi dan kenyamanan tanpa kompromi.
                </p>
            </div>
        </div>

        <!-- Filter & Search Section -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-12">
            <div class="bg-white border border-[#ebe7e7] rounded-2xl p-5 md:p-6 shadow-sm flex flex-col gap-5">
                <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                    <div class="relative w-full md:w-96">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                        <input v-model="searchQuery" type="text" placeholder="Cari nama bus..."
                            class="w-full pl-12 pr-4 py-3 bg-[#f6f3f2] border border-[#e5e2e1] focus:border-rose-600 focus:bg-white focus:ring-0 rounded-xl text-[#1c1b1b] placeholder-gray-400 transition-all outline-none" />
                    </div>
                    <div class="flex gap-2">
                        <button @click="selectedCategory = 'all'" class="px-5 py-2.5 rounded-xl text-xs font-bold uppercase tracking-wider transition-all duration-300 border"
                            :class="selectedCategory === 'all' ? 'bg-rose-600 text-white border-rose-600 shadow-md shadow-rose-600/20' : 'bg-white text-[#454652] border-[#e5e2e1] hover:border-rose-300'">
                            Semua
                        </button>
                        <button @click="selectedCategory = 'akap'" class="px-5 py-2.5 rounded-xl text-xs font-bold uppercase tracking-wider transition-all duration-300 border"
                            :class="selectedCategory === 'akap' ? 'bg-blue-600 text-white border-blue-600 shadow-md shadow-blue-600/20' : 'bg-white text-[#454652] border-[#e5e2e1] hover:border-blue-300'">
                            AKAP
                        </button>
                        <button @click="selectedCategory = 'pariwisata'" class="px-5 py-2.5 rounded-xl text-xs font-bold uppercase tracking-wider transition-all duration-300 border"
                            :class="selectedCategory === 'pariwisata' ? 'bg-purple-600 text-white border-purple-600 shadow-md shadow-purple-600/20' : 'bg-white text-[#454652] border-[#e5e2e1] hover:border-purple-300'">
                            Pariwisata
                        </button>
                    </div>
                </div>
                <div class="flex flex-wrap gap-2 justify-center md:justify-start">
                    <button v-for="type in busTypes" :key="type.id" @click="selectedType = type.id"
                        class="px-5 py-2 rounded-xl text-xs font-bold uppercase tracking-wider transition-all duration-300 border"
                        :class="selectedType === type.id
                            ? 'bg-rose-600 text-white border-rose-600 shadow-sm shadow-rose-600/20'
                            : 'bg-white text-[#454652] border-[#e5e2e1] hover:border-rose-300'">
                        {{ type.name }}
                    </button>
                </div>
            </div>
        </div>

        <!-- Main Grid -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-32">
            <div v-if="filteredBuses.length === 0" class="text-center py-32 border-2 border-dashed border-[#e5e2e1] rounded-3xl">
                <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-[#f6f3f2] mb-6">
                    <i class="fas fa-bus text-3xl text-gray-400"></i>
                </div>
                <h3 class="text-xl font-bold text-[#1c1b1b] mb-2">Armada Tidak Ditemukan</h3>
                <p class="text-[#454652]">Silakan coba kata kunci atau filter lain.</p>
                <button @click="selectedType = 'all'; selectedCategory = 'all'; searchQuery = ''"
                    class="mt-8 text-rose-600 font-bold text-sm uppercase tracking-wider hover:underline">
                    Reset Semua Filter
                </button>
            </div>

            <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div v-for="(bus, index) in filteredBuses" :key="bus.id"
                    class="bg-white rounded-2xl overflow-hidden border border-[#ebe7e7] hover:border-rose-200 transition-all duration-300 hover:shadow-lg hover:shadow-rose-600/5 flex flex-col">
                    
                    <!-- Image Area -->
                    <div class="relative h-64 overflow-hidden">
                        <img v-if="bus.media && bus.media.length" :src="bus.media[0].original_url" :alt="bus.name"
                            class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" />
                        <img v-else src="/img/noImg.png" alt="No Image" class="w-full h-full object-cover opacity-60" />
                        <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-transparent to-transparent"></div>

                        <!-- Top Badges -->
                        <div class="absolute top-4 left-4 right-4 flex justify-between items-start">
                            <span class="px-2.5 py-1 rounded-lg bg-white/20 backdrop-blur-sm border border-white/30 text-white text-[10px] font-bold uppercase tracking-wider">
                                {{ bus.plate_number }}
                            </span>
                            <span class="px-2.5 py-1 rounded-lg text-[10px] font-bold uppercase tracking-wider"
                                :class="bus.bus_category === 'pariwisata' ? 'bg-purple-600/90 text-white' : 'bg-blue-600/90 text-white'">
                                {{ bus.bus_category === 'pariwisata' ? 'Pariwisata' : 'AKAP' }}
                            </span>
                        </div>

                        <!-- Bottom Content (Over Image) -->
                        <div class="absolute bottom-4 left-4 right-4">
                            <h3 class="text-xl font-bold text-white mb-1">{{ bus.name }}</h3>
                            <span class="inline-block px-2.5 py-0.5 rounded bg-rose-600 text-white text-[10px] font-bold uppercase tracking-wider">
                                {{ bus.bus_type }}
                            </span>
                        </div>
                    </div>

                    <!-- Details Body -->
                    <div class="p-5 flex-grow flex flex-col">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="flex items-center gap-2 bg-[#f6f3f2] px-3 py-1.5 rounded-lg border border-[#ebe7e7]">
                                <img src="/img/car-seat.png" alt="seat" class="w-4 h-4 opacity-50" />
                                <span class="text-xs font-bold text-[#454652]">{{ bus.capacity }} Seat</span>
                            </div>
                            <div class="flex items-center gap-2 bg-[#f6f3f2] px-3 py-1.5 rounded-lg border border-[#ebe7e7]">
                                <i class="fas fa-check-circle text-rose-600 text-xs"></i>
                                <span class="text-xs font-bold text-[#454652]">Ready</span>
                            </div>
                            <span v-if="bus.year" class="text-xs text-gray-400 font-mono">{{ bus.year }}</span>
                        </div>

                        <p class="text-sm text-[#454652] leading-relaxed mb-6 flex-grow line-clamp-3">
                            {{ bus.description || "Rasakan kenyamanan perjalanan dengan armada premium kami yang dilengkapi berbagai fasilitas modern." }}
                        </p>

                        <div class="pt-4 border-t border-[#ebe7e7] flex items-center justify-between">
                            <Link :href="route('frontend.booking.index')"
                                class="flex items-center gap-2 text-sm font-bold text-rose-600 hover:text-rose-700 transition-colors">
                                Pesan Sekarang
                                <i class="fas fa-arrow-right text-xs"></i>
                            </Link>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Facilities Section -->
            <div class="mt-24 bg-white border border-[#ebe7e7] rounded-3xl p-8 md:p-16 overflow-hidden text-center relative">
                <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full max-w-4xl h-full bg-rose-600/5 blur-[120px] rounded-full pointer-events-none"></div>
                <div class="relative">
                    <span class="inline-block px-4 py-1.5 rounded-full bg-[#f6f3f2] border border-[#ebe7e7] text-[#454652] text-[10px] font-bold tracking-widest uppercase mb-6">
                        Standar Pelayanan
                    </span>
                    <h2 class="font-unbounded font-black text-3xl md:text-4xl text-[#1c1b1b] mb-4">
                        Fasilitas <span class="text-rose-600">Premium</span>
                    </h2>
                    <p class="text-[#454652] max-w-2xl mx-auto mb-12">
                        Kami memastikan setiap perjalanan Anda dilengkapi dengan fasilitas terbaik untuk menjamin kenyamanan dari awal hingga akhir.
                    </p>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-8 md:gap-10">
                        <div v-for="facility in facilities" :key="facility.name" class="group">
                            <div class="w-16 h-16 mx-auto bg-[#f6f3f2] rounded-2xl border border-[#ebe7e7] flex items-center justify-center mb-4 group-hover:bg-rose-600 group-hover:border-rose-600 transition-all duration-300 shadow-sm">
                                <i :class="[facility.icon, 'text-xl text-[#454652] group-hover:text-white transition-colors']"></i>
                            </div>
                            <h4 class="text-base font-bold text-[#1c1b1b] mb-1">{{ facility.name }}</h4>
                            <p class="text-xs text-[#454652]">{{ facility.description }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
