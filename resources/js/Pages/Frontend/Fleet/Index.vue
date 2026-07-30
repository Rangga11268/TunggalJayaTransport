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
    <Head title="Armada Kami - Tunggal Jaya Transport" />

    <div class="min-h-screen bg-[#fcf9f8] pb-32">
        <!-- Hero Header -->
        <div class="pt-28 pb-10 px-4 sm:px-6 lg:px-8 text-center bg-white border-b border-[#ebe7e7]">
            <div class="max-w-4xl mx-auto">
                <span class="inline-block px-4 py-1.5 rounded-full bg-[#10207a]/10 text-[#10207a] text-[11px] font-bold tracking-widest uppercase mb-4 border border-[#10207a]/20">
                    <i class="fas fa-bus mr-1.5"></i> Armada Tunggal Jaya Transport
                </span>
                <h1 class="font-unbounded font-black text-3xl sm:text-5xl text-[#1c1b1b] mb-3 tracking-tight">
                    Pilihan Armada Terbaik
                </h1>
                <p class="text-[#454652] text-sm sm:text-base max-w-xl mx-auto">
                    Jelajahi armada bus kami yang tangguh dan nyaman. Tersedia untuk bus reguler AKAP maupun sewa pariwisata.
                </p>

                <!-- Category Switcher Tabs -->
                <div class="flex justify-center gap-2 mt-8 flex-wrap">
                    <button @click="selectedCategory = 'all'"
                        class="px-6 py-3 rounded-xl text-xs font-bold uppercase tracking-wider transition-all border flex items-center gap-2"
                        :class="selectedCategory === 'all' ? 'bg-[#10207a] text-white border-[#10207a] shadow-md shadow-[#10207a]/20' : 'bg-gray-50 text-gray-600 border-gray-200 hover:bg-white'">
                        <i class="fas fa-th-large"></i> Semua Armada ({{ buses.length }})
                    </button>
                    <button @click="selectedCategory = 'akap'"
                        class="px-6 py-3 rounded-xl text-xs font-bold uppercase tracking-wider transition-all border flex items-center gap-2"
                        :class="selectedCategory === 'akap' ? 'bg-[#10207a] text-white border-[#10207a] shadow-md shadow-[#10207a]/20' : 'bg-gray-50 text-gray-600 border-gray-200 hover:bg-white'">
                        <i class="fas fa-route text-blue-400"></i> Bus AKAP Reguler
                    </button>
                    <button @click="selectedCategory = 'pariwisata'"
                        class="px-6 py-3 rounded-xl text-xs font-bold uppercase tracking-wider transition-all border flex items-center gap-2"
                        :class="selectedCategory === 'pariwisata' ? 'bg-amber-500 text-white border-amber-500 shadow-md shadow-amber-500/20' : 'bg-gray-50 text-gray-600 border-gray-200 hover:bg-white'">
                        <i class="fas fa-umbrella-beach text-amber-300"></i> Sewa Pariwisata
                    </button>
                </div>
            </div>
        </div>

        <!-- Filter & Search Bar -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 my-8">
            <div class="bg-white border border-[#ebe7e7] rounded-[16px] p-4 shadow-sm flex flex-col md:flex-row justify-between items-center gap-4">
                <!-- Search Input -->
                <div class="relative w-full md:w-80">
                    <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                    <input v-model="searchQuery" type="text" placeholder="Cari nama armada / plat nomor..."
                        class="w-full pl-10 pr-4 py-2.5 bg-[#f6f3f2] border border-gray-200 focus:border-[#10207a] focus:bg-white focus:ring-0 rounded-xl text-gray-800 text-sm placeholder-gray-400 outline-none transition-all" />
                </div>

                <!-- Class Type Chips -->
                <div class="flex flex-wrap gap-1.5 w-full md:w-auto overflow-x-auto pb-1 md:pb-0">
                    <button v-for="type in busTypes" :key="type.id" @click="selectedType = type.id"
                        class="px-3.5 py-2 rounded-xl text-[11px] font-bold uppercase tracking-wider transition-all border shrink-0"
                        :class="selectedType === type.id ? 'bg-[#10207a] text-white border-[#10207a] shadow-sm' : 'bg-gray-50 text-gray-600 border-gray-200 hover:border-gray-300'">
                        {{ type.name }}
                    </button>
                </div>
            </div>
        </div>

        <!-- Fleet Grid -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Empty State -->
            <div v-if="filteredBuses.length === 0" class="bg-white text-center py-20 border-2 border-dashed border-[#ebe7e7] rounded-[16px] shadow-sm">
                <div class="w-16 h-16 rounded-full bg-[#f6f3f2] flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-bus text-2xl text-gray-400"></i>
                </div>
                <h3 class="font-bold text-[#1c1b1b] text-base mb-1">Armada Tidak Ditemukan</h3>
                <p class="text-xs text-[#454652] mb-4">Coba sesuaikan kata kunci pencarian atau filter tipe bus Anda.</p>
                <button @click="selectedType = 'all'; selectedCategory = 'all'; searchQuery = ''"
                    class="px-5 py-2.5 bg-[#10207a] text-white rounded-xl text-xs font-bold hover:bg-[#0c185e] transition-all">
                    Reset Filter
                </button>
            </div>

            <!-- Cards -->
            <div v-else class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <div v-for="bus in filteredBuses" :key="bus.id"
                    class="bg-white rounded-[16px] overflow-hidden border border-[#ebe7e7] shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col group">
                    
                    <!-- Image Area -->
                    <div class="relative h-[220px] overflow-hidden bg-gray-900">
                        <img :src="bus.image_url || '/img/noImg.png'" :alt="bus.name"
                            class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" />
                        <div class="absolute inset-0 bg-gradient-to-t from-black/75 via-black/20 to-transparent"></div>
                        
                        <!-- Badges -->
                        <div class="absolute top-3.5 left-3.5 flex gap-2">
                            <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider text-white border border-white/20 shadow-md backdrop-blur-md"
                                :class="bus.bus_category === 'pariwisata' ? 'bg-amber-500/90' : 'bg-[#10207a]/90'">
                                <i :class="bus.bus_category === 'pariwisata' ? 'fas fa-umbrella-beach mr-1' : 'fas fa-route mr-1'"></i>
                                {{ bus.bus_category === 'pariwisata' ? 'Pariwisata' : 'Bus AKAP' }}
                            </span>
                        </div>

                        <div class="absolute top-3.5 right-3.5">
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase bg-white/90 text-gray-900 shadow-sm backdrop-blur-md">
                                {{ bus.bus_type || 'Executive' }}
                            </span>
                        </div>

                        <!-- Name & Plate on Image -->
                        <div class="absolute bottom-3.5 left-4 right-4 flex items-end justify-between">
                            <div>
                                <h3 class="text-white font-bold text-lg font-unbounded group-hover:text-amber-300 transition-colors drop-shadow-sm">
                                    {{ bus.name }}
                                </h3>
                                <p class="text-white/70 text-xs font-mono">
                                    {{ bus.plate_number }}
                                </p>
                            </div>
                            <span class="text-white/90 text-xs font-bold bg-white/20 backdrop-blur-md px-2.5 py-1 rounded-lg border border-white/20">
                                <i class="fas fa-couch text-amber-300 mr-1"></i> {{ bus.capacity }} Seat
                            </span>
                        </div>
                    </div>

                    <!-- Card Body -->
                    <div class="p-5 flex flex-col flex-grow">
                        <p class="text-xs text-gray-600 leading-relaxed line-clamp-2 mb-4 flex-grow">
                            {{ bus.description || 'Armada bus modern Tunggal Jaya Transport dengan kenyamanan ekstra, suspensi empuk, dan fasilitas lengkap untuk perjalanan Anda.' }}
                        </p>

                        <!-- Highlights Specs -->
                        <div class="grid grid-cols-2 gap-2 mb-5 p-2.5 rounded-xl bg-[#fcf9f8] border border-gray-100 text-[11px] text-[#454652]">
                            <div class="flex items-center gap-1.5">
                                <i class="fas fa-snowflake text-[#10207a]"></i> Full AC & WiFi
                            </div>
                            <div class="flex items-center gap-1.5">
                                <i class="fas fa-chair text-[#10207a]"></i> Reclining Seat
                            </div>
                        </div>

                        <!-- Dual Actions -->
                        <div class="grid grid-cols-2 gap-2 mt-auto">
                            <Link :href="route('frontend.fleet.show', bus.id)"
                                class="py-2.5 px-3 bg-[#f6f3f2] hover:bg-gray-200 text-[#10207a] rounded-xl font-bold text-xs text-center transition-colors">
                                Detail Armada
                            </Link>

                            <Link :href="bus.bus_category === 'pariwisata' ? route('frontend.charter.index') : route('frontend.booking.index')"
                                class="py-2.5 px-3 rounded-xl font-bold text-xs text-center text-white transition-all shadow-sm flex items-center justify-center gap-1.5"
                                :class="bus.bus_category === 'pariwisata' ? 'bg-amber-500 hover:bg-amber-600' : 'bg-[#10207a] hover:bg-[#0c185e]'">
                                {{ bus.bus_category === 'pariwisata' ? 'Sewa Bus' : 'Cari Tiket' }}
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
