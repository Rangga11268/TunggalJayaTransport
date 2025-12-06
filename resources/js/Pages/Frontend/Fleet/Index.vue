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
    { id: "Economy", name: "Ekonomi" },
    { id: "Business", name: "Bisnis" },
    { id: "Executive", name: "Eksekutif" },
    { id: "Suite", name: "Suite Class" },
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

const formatNumber = (num) => {
    return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
};
</script>

<template>
    <Head title="Armada Kami" />

    <!-- Clean Title Section -->
    <div class="pt-32 pb-12 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto text-center">
        <span
            class="inline-block px-4 py-1.5 rounded-full bg-primary-50 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400 text-xs font-bold tracking-widest mb-6 animate-fade-in uppercase"
        >
            MODERN & NYAMAN
        </span>
        <h1
            class="text-4xl md:text-5xl lg:text-6xl font-black font-serif text-gray-900 dark:text-white mb-6 animate-fade-in-up"
        >
            Armada
            <span
                class="text-transparent bg-clip-text bg-gradient-to-r from-brand-red to-orange-500"
                >Premium Kami</span
            >
        </h1>
        <p
            class="text-lg text-gray-500 dark:text-gray-400 max-w-2xl mx-auto animate-fade-in-up stagger-1"
        >
            Pilihan armada terbaik dengan standar keselamatan dan kenyamanan
            tinggi untuk pengalaman perjalanan yang tak terlupakan.
        </p>
    </div>

    <!-- Main Content -->
    <div class="bg-gray-50 dark:bg-gray-950 min-h-screen py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Filter Section -->
            <div
                class="flex flex-col md:flex-row justify-between items-center gap-6 mb-12 sticky top-24 z-30 bg-white/80 dark:bg-gray-900/80 backdrop-blur-xl p-6 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-800"
            >
                <!-- Search -->
                <div class="relative w-full md:w-96">
                    <input
                        v-model="searchQuery"
                        type="text"
                        placeholder="Cari nama bus..."
                        class="w-full pl-12 pr-4 py-3 rounded-xl bg-gray-50 dark:bg-gray-800 border-gray-200 dark:border-gray-700 focus:ring-primary-500 focus:border-primary-500 transition-all"
                    />
                    <i
                        class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"
                    ></i>
                </div>

                <!-- Type Filters -->
                <div class="flex flex-wrap gap-2 justify-center">
                    <button
                        v-for="type in busTypes"
                        :key="type.id"
                        @click="selectedType = type.id"
                        class="px-4 py-2 rounded-full text-sm font-bold transition-all duration-300"
                        :class="
                            selectedType === type.id
                                ? 'bg-brand-red text-white shadow-lg shadow-brand-red/30 scale-105'
                                : 'bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-700'
                        "
                    >
                        {{ type.name }}
                    </button>
                </div>
            </div>

            <!-- Empty State -->
            <div
                v-if="filteredBuses.length === 0"
                class="text-center py-24 bg-white dark:bg-gray-900 rounded-3xl border border-dashed border-gray-300 dark:border-gray-700"
            >
                <div
                    class="w-24 h-24 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center mx-auto mb-6"
                >
                    <i class="fas fa-bus text-4xl text-gray-400"></i>
                </div>
                <h3
                    class="text-xl font-bold text-gray-800 dark:text-white mb-2"
                >
                    Armada Tidak Ditemukan
                </h3>
                <p class="text-gray-500 dark:text-gray-400">
                    Coba ubah filter atau kata kunci pencarian Anda.
                </p>
                <button
                    @click="
                        selectedType = 'all';
                        searchQuery = '';
                    "
                    class="mt-6 text-primary-600 font-bold hover:underline"
                >
                    Reset Filter
                </button>
            </div>

            <!-- Bus Grid -->
            <div
                v-else
                class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-24"
            >
                <div
                    v-for="(bus, index) in filteredBuses"
                    :key="bus.id"
                    class="card-premium overflow-hidden group hover:-translate-y-2 transition-transform duration-500"
                    :style="{ animationDelay: `${index * 0.1}s` }"
                >
                    <div
                        class="relative h-64 bg-gray-200 dark:bg-gray-800 overflow-hidden"
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
                            <i class="fas fa-bus text-5xl mb-4 opacity-50"></i>
                        </div>

                        <!-- Gradient Overlay -->
                        <div
                            class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent opacity-80"
                        ></div>

                        <!-- Badge -->
                        <div class="absolute top-4 right-4">
                            <span
                                class="px-3 py-1 rounded-lg bg-black/50 backdrop-blur-md text-white text-xs font-bold border border-white/20 shadow-lg"
                            >
                                {{ bus.plate_number }}
                            </span>
                        </div>

                        <!-- Type Badge -->
                        <div class="absolute bottom-4 left-4">
                            <span
                                class="px-3 py-1 rounded-lg bg-primary-600 text-white text-sm font-bold shadow-lg"
                            >
                                {{ bus.bus_type }}
                            </span>
                        </div>
                    </div>

                    <div class="p-6">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h3
                                    class="text-xl font-bold text-gray-800 dark:text-white font-serif"
                                >
                                    {{ bus.name }}
                                </h3>
                                <p
                                    class="text-sm text-gray-500 dark:text-gray-400"
                                >
                                    {{
                                        bus.year
                                            ? `Tahun ${bus.year}`
                                            : "Armada Terbaru"
                                    }}
                                </p>
                            </div>
                        </div>

                        <div class="flex flex-wrap gap-2 mb-6">
                            <span
                                class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400 text-xs font-bold"
                            >
                                <img
                                    src="/img/car-seat.png"
                                    alt="seat"
                                    class="w-3.5 h-3.5 opacity-70"
                                />
                                {{ bus.capacity }} Seat
                            </span>
                            <span
                                class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 text-xs font-bold"
                            >
                                <i class="fas fa-check-circle text-[10px]"></i>
                                Available
                            </span>
                        </div>

                        <p
                            class="text-gray-600 dark:text-gray-400 text-sm mb-6 line-clamp-2"
                        >
                            {{
                                bus.description ||
                                "Nikmati perjalanan nyaman dengan fasilitas lengkap bersama TUJAGO."
                            }}
                        </p>

                        <div
                            class="pt-6 border-t border-gray-100 dark:border-gray-800 flex justify-between items-center"
                        >
                            <div class="flex -space-x-2">
                                <div
                                    v-for="i in 3"
                                    :key="i"
                                    class="w-8 h-8 rounded-full bg-gray-200 border-2 border-white dark:border-gray-900 flex items-center justify-center text-[10px] text-gray-500"
                                >
                                    <i class="fas fa-user"></i>
                                </div>
                            </div>
                            <Link
                                :href="route('frontend.booking.index')"
                                class="text-primary-600 dark:text-primary-400 font-bold text-sm hover:underline"
                            >
                                Pesan Tiket
                            </Link>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Facilities Section -->
            <div
                class="bg-white dark:bg-gray-900 rounded-3xl p-8 md:p-12 shadow-xl border border-gray-100 dark:border-gray-800"
            >
                <div class="text-center mb-12">
                    <h2
                        class="text-3xl font-bold text-gray-800 dark:text-white font-serif mb-4"
                    >
                        Fasilitas Standar
                    </h2>
                    <p
                        class="text-gray-500 dark:text-gray-400 max-w-2xl mx-auto"
                    >
                        Setiap armada kami dilengkapi dengan fasilitas standar
                        untuk menjamin kenyamanan Anda selama perjalanan.
                    </p>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                    <div
                        v-for="facility in facilities"
                        :key="facility.name"
                        class="text-center group"
                    >
                        <div
                            class="w-16 h-16 mx-auto bg-gray-50 dark:bg-gray-800 rounded-2xl flex items-center justify-center mb-4 group-hover:bg-primary-50 dark:group-hover:bg-primary-900/20 transition-colors duration-300"
                        >
                            <i
                                :class="[
                                    facility.icon,
                                    'text-2xl text-gray-400 group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors',
                                ]"
                            ></i>
                        </div>
                        <h4
                            class="font-bold text-gray-800 dark:text-white mb-1"
                        >
                            {{ facility.name }}
                        </h4>
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            {{ facility.description }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
