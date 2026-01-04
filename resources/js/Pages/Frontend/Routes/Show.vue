<script setup>
import { Head, Link } from "@inertiajs/vue3";
import FrontendLayout from "@/Layouts/FrontendLayout.vue";
import RouteMap from "@/Components/RouteMap.vue";

defineOptions({ layout: FrontendLayout });

const props = defineProps({
    routeModel: Object,
});

const formatPrice = (price) => {
    return new Intl.NumberFormat("id-ID", {
        style: "currency",
        currency: "IDR",
        minimumFractionDigits: 0,
    }).format(price || 0);
};

const formatTime = (dateString) => {
    if (!dateString) return "";
    if (dateString.length === 5 && dateString.includes(":")) return dateString;

    const date = new Date(dateString);
    return date
        .toLocaleTimeString("id-ID", {
            hour: "2-digit",
            minute: "2-digit",
            hour12: false,
        })
        .replace(".", ":");
};
</script>

<template>
    <Head :title="`${routeModel.origin} - ${routeModel.destination}`" />

    <!-- Hero Header -->
    <div
        class="relative bg-gray-950 min-h-[50vh] flex items-center justify-center overflow-hidden"
    >
        <!-- Background Elements -->
        <div class="absolute inset-0">
            <div
                class="absolute top-0 right-0 w-[500px] h-[500px] bg-rose-600/10 rounded-full blur-[120px]"
            ></div>
            <div
                class="absolute bottom-0 left-0 w-[500px] h-[500px] bg-rose-600/10 rounded-full blur-[120px]"
            ></div>
            <!-- Grid Pattern -->
            <div
                class="absolute inset-0 bg-[linear-gradient(rgba(255,255,255,0.03)_1px,transparent_1px),linear-gradient(90deg,rgba(255,255,255,0.03)_1px,transparent_1px)] bg-[size:40px_40px] opacity-20"
            ></div>
        </div>

        <div
            class="relative z-10 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 text-center sm:text-left"
        >
            <Link
                :href="route('frontend.routes.index')"
                class="inline-flex items-center space-x-2 text-gray-400 hover:text-white transition-colors mb-8 group"
            >
                <i
                    class="fas fa-arrow-left group-hover:-translate-x-1 transition-transform"
                ></i>
                <span
                    class="font-manrope font-bold text-sm uppercase tracking-widest"
                    >Kembali ke Daftar</span
                >
            </Link>

            <div
                class="flex flex-col md:flex-row md:items-end justify-between gap-8"
            >
                <div>
                    <span
                        class="inline-block py-1 px-3 rounded-full bg-rose-900/30 text-rose-500 border border-rose-900/50 text-[10px] font-bold tracking-widest uppercase mb-4 font-unbounded"
                    >
                        Detail Rute
                    </span>
                    <h1
                        class="text-3xl sm:text-4xl md:text-6xl font-black text-white mb-2 font-unbounded leading-tight"
                    >
                        {{ routeModel.origin }}
                        <span
                            class="text-rose-600 mx-2 text-xl sm:text-2xl md:text-4xl align-middle"
                            ><i class="fas fa-arrow-right"></i
                        ></span>
                        {{ routeModel.destination }}
                    </h1>
                    <div
                        class="flex flex-wrap items-center gap-6 text-gray-400 mt-6 font-manrope"
                    >
                        <div
                            class="flex items-center space-x-2 bg-white/5 px-4 py-2 rounded-lg border border-white/10"
                        >
                            <i class="fas fa-clock text-rose-500"></i>
                            <span class="font-bold text-white">{{
                                routeModel.formatted_duration || "6h"
                            }}</span>
                        </div>
                        <div
                            class="flex items-center space-x-2 bg-white/5 px-4 py-2 rounded-lg border border-white/10"
                        >
                            <i class="fas fa-road text-rose-500"></i>
                            <span class="font-bold text-white"
                                >{{ routeModel.distance }} km</span
                            >
                        </div>
                    </div>
                </div>

                <!-- CTA -->
                <div class="flex-shrink-0">
                    <Link
                        :href="route('frontend.booking.index')"
                        class="inline-flex items-center justify-center px-8 py-4 bg-white text-black rounded-xl font-bold font-unbounded text-xs uppercase tracking-widest hover:bg-rose-600 hover:text-white transition-all duration-300 shadow-xl shadow-white/5 hover:shadow-rose-600/30"
                    >
                        Pesan Tiket
                        <i class="fas fa-ticket-alt ml-3"></i>
                    </Link>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="bg-gray-50 dark:bg-[#050505] min-h-screen py-16 font-sans">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
                <!-- Left Column: Details -->
                <div class="lg:col-span-2 space-y-12">
                    <!-- Description -->
                    <div
                        class="bg-white dark:bg-[#111] rounded-3xl p-6 md:p-8 border border-gray-100 dark:border-white/5 shadow-2xl shadow-gray-200 dark:shadow-none"
                    >
                        <h3
                            class="text-xl font-black text-gray-900 dark:text-white mb-6 font-unbounded flex items-center"
                        >
                            <i
                                class="fas fa-info-circle text-rose-600 mr-3"
                            ></i>
                            Tentang Rute Ini
                        </h3>
                        <p
                            class="text-gray-500 dark:text-gray-400 leading-loose font-manrope"
                        >
                            {{
                                routeModel.description ||
                                "Nikmati perjalanan premium yang nyaman dan aman. Rute ini dirancang untuk memberikan pengalaman perjalanan terbaik dengan armada terbaru kami."
                            }}
                        </p>
                    </div>

                    <!-- Schedules -->
                    <div
                        class="bg-white dark:bg-[#111] rounded-3xl p-6 md:p-8 border border-gray-100 dark:border-white/5 shadow-2xl shadow-gray-200 dark:shadow-none"
                    >
                        <div class="flex items-center justify-between mb-8">
                            <h3
                                class="text-xl font-black text-gray-900 dark:text-white font-unbounded flex items-center"
                            >
                                <i
                                    class="fas fa-calendar-alt text-rose-600 mr-3"
                                ></i>
                                Jadwal Tersedia
                            </h3>
                            <span
                                class="px-3 py-1 rounded bg-gray-100 dark:bg-white/10 text-gray-600 dark:text-gray-400 text-xs font-bold font-manrope"
                            >
                                {{
                                    routeModel.available_schedules
                                        ? routeModel.available_schedules.length
                                        : 0
                                }}
                                Opsi
                            </span>
                        </div>

                        <div
                            v-if="
                                routeModel.available_schedules &&
                                routeModel.available_schedules.length > 0
                            "
                            class="space-y-4"
                        >
                            <div
                                v-for="schedule in routeModel.available_schedules"
                                :key="schedule.id"
                                class="group flex flex-col md:flex-row md:items-center justify-between p-6 rounded-2xl bg-gray-50 dark:bg-[#161616] border border-gray-100 dark:border-white/5 hover:border-rose-600 dark:hover:border-rose-600 transition-all duration-300"
                            >
                                <div
                                    class="flex items-center gap-6 mb-4 md:mb-0"
                                >
                                    <div class="text-center min-w-[80px]">
                                        <h4
                                            class="text-2xl font-black text-gray-900 dark:text-white font-unbounded"
                                        >
                                            {{
                                                formatTime(
                                                    schedule.departure_time
                                                )
                                            }}
                                        </h4>
                                        <p
                                            class="text-xs text-rose-600 font-bold uppercase tracking-wider font-manrope"
                                        >
                                            WIB
                                        </p>
                                    </div>
                                    <div
                                        class="w-[1px] h-12 bg-gray-200 dark:bg-white/10"
                                    ></div>
                                    <div>
                                        <h5
                                            class="font-bold text-gray-900 dark:text-white font-manrope text-lg"
                                        >
                                            {{
                                                schedule.bus?.name ||
                                                "Armada TJT"
                                            }}
                                        </h5>
                                        <span
                                            class="text-xs text-gray-500 dark:text-gray-400"
                                            >{{
                                                schedule.bus?.bus_type ||
                                                "Executive"
                                            }}
                                            Class</span
                                        >
                                    </div>
                                </div>

                                <div
                                    class="flex items-center justify-between md:gap-8"
                                >
                                    <div class="text-right">
                                        <p
                                            class="text-xl font-black text-rose-600 font-unbounded"
                                        >
                                            {{ formatPrice(schedule.price) }}
                                        </p>
                                        <p
                                            class="text-[10px] text-gray-400 uppercase tracking-wider"
                                        >
                                            Per Kursi
                                        </p>
                                    </div>
                                    <Link
                                        :href="route('frontend.booking.index')"
                                        class="w-10 h-10 rounded-full bg-white dark:bg-white/10 border border-gray-200 dark:border-white/10 flex items-center justify-center text-rose-600 hover:bg-rose-600 hover:text-white hover:border-rose-600 transition-all duration-300"
                                    >
                                        <i class="fas fa-chevron-right"></i>
                                    </Link>
                                </div>
                            </div>
                        </div>

                        <div
                            v-else
                            class="text-center py-12 border-dashed border-2 border-gray-100 dark:border-white/5 rounded-2xl"
                        >
                            <i
                                class="fas fa-bus-alt text-4xl text-gray-300 mb-4"
                            ></i>
                            <p class="text-gray-500 font-manrope">
                                Belum ada jadwal keberangkatan untuk rute ini.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Sidebar -->
                <div class="space-y-8">
                    <!-- Map Card -->
                    <div
                        class="bg-white dark:bg-[#111] rounded-3xl p-2 border border-gray-100 dark:border-white/5 shadow-2xl shadow-gray-200 dark:shadow-none"
                    >
                        <div
                            class="relative w-full h-80 bg-gray-200 dark:bg-[#1a1a1a] rounded-2xl flex items-center justify-center overflow-hidden group shadow-inner"
                        >
                            <RouteMap
                                :origin="routeModel.origin"
                                :destination="routeModel.destination"
                            />
                        </div>
                    </div>

                    <!-- Info Card -->
                    <div
                        class="bg-gray-900 dark:bg-[#111] rounded-3xl p-8 text-white relative overflow-hidden"
                    >
                        <div
                            class="absolute top-0 right-0 w-32 h-32 bg-rose-600/20 rounded-full blur-3xl -mr-16 -mt-16"
                        ></div>

                        <h4
                            class="text-lg font-black font-unbounded mb-6 flex items-center"
                        >
                            <i class="fas fa-shield-alt text-rose-600 mr-3"></i>
                            Info Perjalanan
                        </h4>

                        <ul
                            class="space-y-4 font-manrope text-sm text-gray-300"
                        >
                            <li class="flex items-start">
                                <i
                                    class="fas fa-check-circle text-rose-600 mt-1 mr-3"
                                ></i>
                                <span
                                    >Check-in min. 30 menit sebelum
                                    keberangkatan.</span
                                >
                            </li>
                            <li class="flex items-start">
                                <i
                                    class="fas fa-check-circle text-rose-600 mt-1 mr-3"
                                ></i>
                                <span>Maksimal bagasi 20kg per penumpang.</span>
                            </li>
                            <li class="flex items-start">
                                <i
                                    class="fas fa-check-circle text-rose-600 mt-1 mr-3"
                                ></i>
                                <span>Dilarang membawa hewan peliharaan.</span>
                            </li>
                        </ul>

                        <div class="mt-8 pt-6 border-t border-white/10">
                            <p
                                class="text-[10px] text-gray-500 uppercase tracking-widest mb-2 font-bold"
                            >
                                Butuh Bantuan?
                            </p>
                            <a
                                href="#"
                                class="flex items-center text-white hover:text-rose-500 transition-colors font-bold"
                            >
                                <i class="fab fa-whatsapp text-lg mr-2"></i>
                                Chat CS Kami
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
