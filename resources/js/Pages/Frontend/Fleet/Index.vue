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
        <!-- Header -->
        <div class="pt-28 pb-8 px-4 sm:px-6 lg:px-8 text-center">
            <div class="max-w-7xl mx-auto">
                <h2 class="font-unbounded font-bold text-[#1c1b1b] text-[32px] tracking-[-0.32px] m-0">Armada Kami</h2>
                <p class="font-normal text-[#454652] text-[16px] m-0 mt-1">Seluruh armada tunggal jaya transport.</p>
            </div>
        </div>

        <!-- Filter -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-8">
            <div class="bg-white border border-[#ebe7e7] rounded-[12px] p-4 shadow-sm flex flex-col gap-3">
                <div class="flex flex-col md:flex-row justify-between items-center gap-3">
                    <div class="relative w-full md:w-72">
                        <i class="fas fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                        <input v-model="searchQuery" type="text" placeholder="Cari armada..."
                            class="w-full pl-9 pr-3 py-2 bg-[#f6f3f2] border border-gray-200 focus:border-[#10207a] focus:bg-white focus:ring-0 rounded-[10px] text-gray-700 text-sm placeholder-gray-400 outline-none transition-all" />
                    </div>
                    <div class="flex gap-2">
                        <button @click="selectedCategory = 'all'"
                            class="px-4 py-2 rounded-xl text-xs font-bold uppercase tracking-wider transition-all border"
                            :class="selectedCategory === 'all' ? 'bg-[#10207a] text-white border-[#10207a] shadow-lg shadow-[#10207a]/30' : 'bg-white text-gray-600 border-gray-200 hover:border-gray-400'">Semua</button>
                        <button @click="selectedCategory = 'akap'"
                            class="px-4 py-2 rounded-xl text-xs font-bold uppercase tracking-wider transition-all border"
                            :class="selectedCategory === 'akap' ? 'bg-[#10207a] text-white border-[#10207a] shadow-lg shadow-[#10207a]/30' : 'bg-white text-gray-600 border-gray-200 hover:border-gray-400'">AKAP</button>
                        <button @click="selectedCategory = 'pariwisata'"
                            class="px-4 py-2 rounded-xl text-xs font-bold uppercase tracking-wider transition-all border"
                            :class="selectedCategory === 'pariwisata' ? 'bg-[#10207a] text-white border-[#10207a] shadow-lg shadow-[#10207a]/30' : 'bg-white text-gray-600 border-gray-200 hover:border-gray-400'">Pariwisata</button>
                    </div>
                </div>
                <div class="flex flex-wrap gap-2">
                    <button v-for="type in busTypes" :key="type.id" @click="selectedType = type.id"
                        class="px-4 py-1.5 rounded-xl text-xs font-bold uppercase tracking-wider transition-all border"
                        :class="selectedType === type.id ? 'bg-[#10207a] text-white border-[#10207a] shadow-sm shadow-[#10207a]/20' : 'bg-white text-gray-600 border-gray-200 hover:border-gray-400'">
                        {{ type.name }}
                    </button>
                </div>
            </div>
        </div>

        <!-- Grid -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-32">
            <div v-if="filteredBuses.length === 0" class="text-center py-20 border-2 border-dashed border-[#e5e2e1] rounded-[12px]">
                <div class="w-14 h-14 rounded-full bg-[#f6f3f2] flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-bus text-xl text-gray-400"></i>
                </div>
                <p class="text-sm text-[#454652]">Armada tidak ditemukan.</p>
                <button @click="selectedType = 'all'; selectedCategory = 'all'; searchQuery = ''"
                    class="mt-4 text-[#10207a] text-sm font-semibold hover:underline">Reset Filter</button>
            </div>

            <div v-else class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-5 lg:gap-[24px]">
                <div v-for="bus in filteredBuses" :key="bus.id"
                    class="bg-white rounded-[12px] overflow-hidden border border-[#ebe7e7] shadow-sm hover:shadow-lg transition-shadow flex flex-col">
                    <div class="relative h-[200px] overflow-hidden">
                        <img :src="bus.image_url || '/img/noImg.png'" :alt="bus.name"
                            class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" />
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>
                        <div class="absolute bottom-[16px] left-[16px] right-[16px] flex items-center justify-between">
                            <span class="text-white font-bold text-[16px]">{{ bus.name }}</span>
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wide"
                                :class="bus.bus_category === 'pariwisata' ? 'bg-purple-600 text-white' : 'bg-blue-600 text-white'">
                                {{ bus.bus_category === 'pariwisata' ? 'Pariwisata' : 'AKAP' }}
                            </span>
                        </div>
                    </div>
                    <div class="p-[16px] flex flex-col flex-grow">
                        <div class="flex items-center gap-3 text-[13px] text-gray-500 mb-[10px]">
                            <span>{{ bus.bus_type }}</span>
                            <span>&middot;</span>
                            <span>{{ bus.capacity }} Kursi</span>
                            <span>&middot;</span>
                            <span class="font-mono">{{ bus.plate_number }}</span>
                        </div>
                        <p class="text-[14px] text-gray-600 leading-relaxed line-clamp-2 mb-[14px] flex-grow">
                            {{ bus.description || 'Armada premium dengan fasilitas terbaik.' }}
                        </p>
                        <Link :href="bus.bus_category === 'pariwisata' ? route('frontend.charter.index') : route('frontend.booking.index')"
                            class="w-full block text-center py-3 bg-[#10207a] text-white rounded-[10px] font-bold text-[13px] hover:bg-[#0c185e] transition-all shadow-sm">
                            {{ bus.bus_category === 'pariwisata' ? 'Sewa Sekarang' : 'Pesan Tiket' }}
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
