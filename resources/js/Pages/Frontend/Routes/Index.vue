<script setup>
import { ref, computed } from "vue";
import { Head, Link } from "@inertiajs/vue3";
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
</script>

<template>
    <Head title="Rute Perjalanan" />

    <div
        class="min-h-screen bg-gray-50 dark:bg-[#050505] font-sans selection:bg-rose-600 selection:text-white"
    >
        <!-- Hero Header -->
        <div class="relative pt-32 pb-20 px-4 sm:px-6 lg:px-8 overflow-hidden">
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
                    Destinasi Populer
                </span>
                <h1
                    class="text-4xl md:text-6xl font-black text-gray-900 dark:text-white mb-6 font-unbounded animate-fade-in-up"
                    style="animation-delay: 0.1s"
                >
                    Jelajahi <span class="text-rose-600">Rute Kami</span>
                </h1>
                <p
                    class="text-lg text-gray-500 dark:text-gray-400 max-w-2xl mx-auto font-manrope animate-fade-in-up"
                    style="animation-delay: 0.2s"
                >
                    Temukan berbagai pilihan rute perjalanan terbaik dengan
                    jadwal fleksibel dan armada yang nyaman dari Tunggal Jaya
                    Transport.
                </p>

                <!-- Search Bar -->
                <div
                    class="mt-12 max-w-2xl mx-auto animate-fade-in-up"
                    style="animation-delay: 0.3s"
                >
                    <div class="relative group">
                        <div
                            class="absolute inset-0 bg-rose-600/20 rounded-2xl blur-xl group-hover:bg-rose-600/30 transition-all opacity-50 group-hover:opacity-100"
                        ></div>
                        <div
                            class="relative bg-white dark:bg-[#111] border border-gray-100 dark:border-white/10 rounded-2xl p-2 flex items-center shadow-2xl shadow-black/5"
                        >
                            <i
                                class="fas fa-search text-gray-400 text-xl ml-4"
                            ></i>
                            <input
                                v-model="searchQuery"
                                type="text"
                                placeholder="Cari kota asal atau tujuan..."
                                class="w-full bg-transparent border-none focus:ring-0 text-gray-900 dark:text-white placeholder-gray-400 text-lg py-3 px-4 font-manrope"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content Grid -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-24">
            <div
                v-if="filteredRoutes.length > 0"
                class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8"
            >
                <div
                    v-for="(routeItem, index) in filteredRoutes"
                    :key="routeItem.id"
                    class="group relative bg-white dark:bg-[#111] rounded-3xl p-8 border border-gray-100 dark:border-white/5 hover:border-rose-600/30 dark:hover:border-rose-600/30 transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl hover:shadow-rose-600/10 flex flex-col h-full animate-fade-in-up"
                    :style="{ animationDelay: `${index * 0.1 + 0.4}s` }"
                >
                    <!-- Route Visual -->
                    <div
                        class="flex items-center justify-between mb-8 relative"
                    >
                        <!-- Origin -->
                        <div class="flex flex-col items-start z-10">
                            <span
                                class="text-xs text-gray-400 font-manrope font-bold uppercase tracking-wider mb-1"
                                >Asal</span
                            >
                            <h3
                                class="text-xl font-black font-unbounded text-gray-900 dark:text-white leading-tight"
                            >
                                {{ routeItem.origin }}
                            </h3>
                        </div>

                        <!-- Connector -->
                        <div
                            class="flex-1 mx-4 flex flex-col items-center justify-center gap-2"
                        >
                            <div
                                class="relative w-full flex items-center justify-center"
                            >
                                <div
                                    class="absolute inset-x-0 h-[2px] bg-gray-100 dark:bg-white/10 group-hover:bg-rose-600/20 transition-colors"
                                ></div>
                                <div
                                    class="relative z-10 w-8 h-8 rounded-full bg-gray-50 dark:bg-[#1a1a1a] border border-gray-100 dark:border-white/10 flex items-center justify-center group-hover:scale-110 group-hover:border-rose-600 transition-all duration-300"
                                >
                                    <i
                                        class="fas fa-arrow-right text-gray-400 text-xs group-hover:text-rose-600 transition-colors"
                                    ></i>
                                </div>
                            </div>
                            <span
                                class="text-[10px] font-bold text-gray-400 block text-center"
                                >{{
                                    routeItem.formatted_duration ||
                                    "Check Schedule"
                                }}</span
                            >
                        </div>

                        <!-- Destination -->
                        <div class="flex flex-col items-end z-10 text-right">
                            <span
                                class="text-xs text-gray-400 font-manrope font-bold uppercase tracking-wider mb-1"
                                >Tujuan</span
                            >
                            <h3
                                class="text-xl font-black font-unbounded text-gray-900 dark:text-white leading-tight"
                            >
                                {{ routeItem.destination }}
                            </h3>
                        </div>
                    </div>

                    <!-- Details Stats -->
                    <div class="grid grid-cols-2 gap-4 mb-8">
                        <div class="bg-gray-50 dark:bg-white/5 rounded-2xl p-4">
                            <i class="fas fa-road text-rose-600 mb-2 block"></i>
                            <p class="text-xs text-gray-400 font-manrope">
                                Jarak
                            </p>
                            <p
                                class="font-bold text-gray-900 dark:text-white font-unbounded"
                            >
                                {{ routeItem.distance }} km
                            </p>
                        </div>
                        <div class="bg-gray-50 dark:bg-white/5 rounded-2xl p-4">
                            <i class="fas fa-bus text-rose-600 mb-2 block"></i>
                            <p class="text-xs text-gray-400 font-manrope">
                                Trip
                            </p>
                            <p
                                class="font-bold text-gray-900 dark:text-white font-unbounded"
                            >
                                {{ routeItem.schedules_count }} Jadwal
                            </p>
                        </div>
                    </div>

                    <p
                        class="text-gray-500 dark:text-gray-400 text-sm font-manrope line-clamp-2 mb-8 flex-grow"
                    >
                        {{
                            routeItem.description ||
                            "Rute perjalanan nyaman dengan armada premium Tunggal Jaya Transport."
                        }}
                    </p>

                    <!-- Action -->
                    <Link
                        :href="route('frontend.routes.show', routeItem.id)"
                        class="w-full py-4 rounded-xl bg-gray-900 dark:bg-white text-white dark:text-black font-bold font-unbounded text-xs uppercase tracking-widest text-center hover:bg-rose-600 dark:hover:bg-rose-600 hover:text-white transition-all duration-300 shadow-lg shadow-gray-200 dark:shadow-none hover:shadow-rose-600/30"
                    >
                        Lihat Detail
                    </Link>
                </div>
            </div>

            <!-- Empty State -->
            <div v-else class="text-center py-24">
                <div
                    class="inline-flex items-center justify-center p-6 rounded-full bg-gray-100 dark:bg-white/5 mb-6"
                >
                    <i
                        class="fas fa-search-location text-4xl text-gray-400"
                    ></i>
                </div>
                <h3
                    class="text-xl font-bold font-unbounded text-gray-900 dark:text-white mb-2"
                >
                    Rute Tidak Ditemukan
                </h3>
                <p
                    class="text-gray-500 dark:text-gray-400 max-w-md mx-auto mb-8 font-manrope"
                >
                    Maaf, rute yang Anda cari belum tersedia atau coba kata
                    kunci lain.
                </p>
                <button
                    @click="searchQuery = ''"
                    class="px-6 py-3 rounded-xl bg-rose-600 text-white font-bold font-unbounded text-xs uppercase tracking-widest hover:bg-rose-700 transition"
                >
                    Reset Pencarian
                </button>
            </div>
        </div>
    </div>
</template>
