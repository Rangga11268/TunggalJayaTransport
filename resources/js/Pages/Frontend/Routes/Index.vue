<script setup>
import { ref, computed } from "vue";
import { Head, Link, usePage } from "@inertiajs/vue3";
import FrontendLayout from "@/Layouts/FrontendLayout.vue";

defineOptions({ layout: FrontendLayout });

const props = defineProps({
    routes: Array,
});

const searchQuery = ref("");

const filteredRoutes = computed(() => {
    if (!props.routes) return [];
    if (!searchQuery.value) return props.routes;

    const query = searchQuery.value.toLowerCase();
    return props.routes.filter((route) => {
        const origin = route.origin ? route.origin.toLowerCase() : "";
        const destination = route.destination
            ? route.destination.toLowerCase()
            : "";
        return origin.includes(query) || destination.includes(query);
    });
});

const formatPrice = (price) => {
    return new Intl.NumberFormat("id-ID", {
        style: "currency",
        currency: "IDR",
        minimumFractionDigits: 0,
    }).format(price || 0);
};
</script>

<template>
    <Head title="Rute Perjalanan" />

    <!-- Clean Title Section -->
    <div class="pt-24 pb-12 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto text-center">
        <span
            class="inline-block px-4 py-1.5 rounded-full bg-primary-50 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400 text-xs font-bold tracking-widest mb-6 animate-fade-in uppercase"
        >
            DESTINASI POPULER
        </span>
        <h1
            class="text-4xl md:text-5xl lg:text-6xl font-black font-serif text-gray-900 dark:text-white mb-6 animate-fade-in-up"
        >
            Jelajahi
            <span
                class="text-transparent bg-clip-text bg-gradient-to-r from-brand-red to-orange-500"
                >Rute Kami</span
            >
        </h1>
        <p
            class="text-lg text-gray-500 dark:text-gray-400 max-w-2xl mx-auto animate-fade-in-up stagger-1"
        >
            Temukan berbagai pilihan rute perjalanan terbaik dengan jadwal
            fleksibel dan armada yang nyaman.
        </p>
    </div>

    <!-- Main Content -->
    <div class="bg-gray-50 dark:bg-gray-950 min-h-screen py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Search Input -->
            <div class="flex justify-center mb-12">
                <div class="relative w-full max-w-lg">
                    <input
                        v-model="searchQuery"
                        type="text"
                        placeholder="Cari kota asal atau tujuan..."
                        class="w-full pl-12 pr-4 py-4 rounded-full bg-white dark:bg-gray-900 border-gray-200 dark:border-gray-700 shadow-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all text-lg"
                    />
                    <i
                        class="fas fa-search absolute left-5 top-1/2 -translate-y-1/2 text-gray-400 text-xl"
                    ></i>
                </div>
            </div>

            <!-- Routes Grid -->
            <div
                v-if="filteredRoutes.length > 0"
                class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-24"
            >
                <div
                    v-for="(routeItem, index) in filteredRoutes"
                    :key="routeItem.id"
                    class="card-premium group hover:-translate-y-2 transition-transform duration-500 flex flex-col h-full"
                    :style="{ animationDelay: `${index * 0.1}s` }"
                >
                    <div class="p-8 flex-grow">
                        <!-- Route Header -->
                        <div class="flex items-center justify-between mb-6">
                            <div class="flex items-center space-x-3">
                                <div
                                    class="w-10 h-10 rounded-full bg-primary-50 dark:bg-primary-900/30 flex items-center justify-center text-primary-600 dark:text-primary-400"
                                >
                                    <i
                                        class="fas fa-map-marker-alt text-lg"
                                    ></i>
                                </div>
                                <div>
                                    <h3
                                        class="font-bold text-gray-800 dark:text-white text-lg"
                                    >
                                        {{ routeItem.origin }}
                                    </h3>
                                    <p
                                        class="text-xs text-gray-500 dark:text-gray-400"
                                    >
                                        Asal
                                    </p>
                                </div>
                            </div>

                            <!-- Connector Line -->
                            <div class="flex-1 px-4 flex flex-col items-center">
                                <div
                                    class="w-full h-0.5 bg-gray-200 dark:bg-gray-700 relative"
                                >
                                    <div
                                        class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 w-2 h-2 rounded-full bg-gray-300 dark:bg-gray-600"
                                    ></div>
                                </div>
                                <span class="text-[10px] text-gray-400 mt-1">{{
                                    routeItem.formatted_duration || "6h 30m"
                                }}</span>
                            </div>

                            <div class="flex items-center space-x-3">
                                <div class="text-right">
                                    <h3
                                        class="font-bold text-gray-800 dark:text-white text-lg"
                                    >
                                        {{ routeItem.destination }}
                                    </h3>
                                    <p
                                        class="text-xs text-gray-500 dark:text-gray-400"
                                    >
                                        Tujuan
                                    </p>
                                </div>
                                <div
                                    class="w-10 h-10 rounded-full bg-brand-red/10 flex items-center justify-center text-brand-red"
                                >
                                    <i
                                        class="fas fa-flag-checkered text-lg"
                                    ></i>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-3 mb-6">
                            <div
                                class="flex items-center text-sm text-gray-600 dark:text-gray-400"
                            >
                                <i
                                    class="fas fa-road w-6 text-center text-gray-400"
                                ></i>
                                <span>Jarak: {{ routeItem.distance }} km</span>
                            </div>
                            <div
                                class="flex items-center text-sm text-gray-600 dark:text-gray-400"
                            >
                                <i
                                    class="fas fa-clock w-6 text-center text-gray-400"
                                ></i>
                                <span
                                    >Durasi:
                                    {{ routeItem.duration }} menit</span
                                >
                            </div>
                            <div
                                class="flex items-center text-sm text-gray-600 dark:text-gray-400"
                            >
                                <i
                                    class="fas fa-bus w-6 text-center text-gray-400"
                                ></i>
                                <span
                                    >{{ routeItem.schedules_count }} Jadwal
                                    Tersedia</span
                                >
                            </div>
                        </div>

                        <p
                            class="text-sm text-gray-500 dark:text-gray-400 line-clamp-2 mb-6"
                        >
                            {{ routeItem.description }}
                        </p>
                    </div>

                    <div
                        class="p-6 bg-gray-50 dark:bg-gray-800/50 border-t border-gray-100 dark:border-gray-800"
                    >
                        <Link
                            :href="route('frontend.routes.show', routeItem.id)"
                            class="btn-premium w-full justify-center group-hover:scale-[1.02]"
                        >
                            Lihat Detail Rute
                        </Link>
                    </div>
                </div>
            </div>

            <!-- Empty State -->
            <div
                v-else
                class="text-center py-24 bg-white dark:bg-gray-900 rounded-3xl border border-dashed border-gray-300 dark:border-gray-700"
            >
                <div
                    class="w-24 h-24 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center mx-auto mb-6"
                >
                    <i class="fas fa-route text-4xl text-gray-400"></i>
                </div>
                <h3
                    class="text-xl font-bold text-gray-800 dark:text-white mb-2"
                >
                    Rute Tidak Ditemukan
                </h3>
                <p class="text-gray-500 dark:text-gray-400">
                    Maaf, kami tidak dapat menemukan rute yang Anda cari.
                </p>
                <button
                    @click="searchQuery = ''"
                    class="mt-6 text-primary-600 font-bold hover:underline"
                >
                    Tampilkan Semua Rute
                </button>
            </div>
        </div>
    </div>
</template>
