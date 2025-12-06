<script setup>
import { Head, Link } from "@inertiajs/vue3";
import FrontendLayout from "@/Layouts/FrontendLayout.vue";

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

const formatDate = (dateString) => {
    if (!dateString) return "";
    const options = {
        weekday: "long",
        year: "numeric",
        month: "long",
        day: "numeric",
        hour: "2-digit",
        minute: "2-digit",
    };
    return new Date(dateString).toLocaleDateString("id-ID", options);
};

const formatTime = (dateString) => {
    if (!dateString) return "";
    // If it's already a time string like "14:00", return it
    if (dateString.length === 5 && dateString.includes(":")) return dateString;

    // Otherwise parse it as date
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
    <div class="relative bg-primary-950 py-24 overflow-hidden">
        <!-- Background Elements -->
        <div class="absolute inset-0">
            <div class="absolute inset-0 hero-pattern opacity-10"></div>
            <div
                class="absolute top-0 right-0 w-1/2 h-full bg-gradient-to-l from-primary-900/50 to-transparent"
            ></div>
            <!-- Stars -->
            <div class="stars absolute inset-0 opacity-50"></div>
        </div>

        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <Link
                :href="route('frontend.routes.index')"
                class="inline-flex items-center space-x-2 text-gray-400 hover:text-white transition-colors mb-6"
            >
                <i class="fas fa-arrow-left"></i>
                <span>Kembali ke Daftar Rute</span>
            </Link>

            <div
                class="flex flex-col md:flex-row md:items-center justify-between"
            >
                <div>
                    <span
                        class="inline-block px-3 py-1 rounded-full bg-white/10 backdrop-blur-md border border-white/20 text-white/90 text-xs font-bold mb-4 tracking-wide"
                    >
                        DETAIL RUTE
                    </span>
                    <h1
                        class="text-3xl md:text-4xl lg:text-5xl font-extrabold text-white mb-2 font-serif"
                    >
                        {{ routeModel.origin }}
                        <span class="text-gray-400 mx-2 text-2xl md:text-4xl"
                            ><i class="fas fa-long-arrow-alt-right"></i
                        ></span>
                        {{ routeModel.destination }}
                    </h1>
                    <div class="flex items-center space-x-6 text-gray-300 mt-4">
                        <div class="flex items-center space-x-2">
                            <i class="fas fa-clock text-gold-400"></i>
                            <span>{{
                                routeModel.formatted_duration || "6 Jam"
                            }}</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <i class="fas fa-road text-gold-400"></i>
                            <span>{{ routeModel.distance }} km</span>
                        </div>
                    </div>
                </div>
                <div class="mt-6 md:mt-0">
                    <Link
                        :href="route('frontend.booking.index')"
                        class="btn-premium"
                    >
                        Pesan Tiket Sekarang
                    </Link>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="bg-gray-50 dark:bg-gray-950 min-h-screen py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left Column: Details & Map -->
                <div class="lg:col-span-2 space-y-8">
                    <!-- Description -->
                    <div
                        class="bg-white dark:bg-gray-900 rounded-3xl p-8 shadow-lg border border-gray-100 dark:border-gray-800"
                    >
                        <h3
                            class="text-xl font-bold text-gray-800 dark:text-white mb-4"
                        >
                            Tentang Rute Ini
                        </h3>
                        <p
                            class="text-gray-600 dark:text-gray-400 leading-relaxed"
                        >
                            {{
                                routeModel.description ||
                                "Nikmati perjalanan yang nyaman dan aman dari " +
                                    routeModel.origin +
                                    " menuju " +
                                    routeModel.destination +
                                    " bersama TUJAGO. Rute ini menawarkan pemandangan indah dan fasilitas lengap di setiap armada kami."
                            }}
                        </p>
                    </div>

                    <!-- Map Placeholder -->
                    <div
                        class="bg-white dark:bg-gray-900 rounded-3xl p-2 shadow-lg border border-gray-100 dark:border-gray-800 overflow-hidden"
                    >
                        <div
                            class="relative w-full h-80 bg-gray-200 dark:bg-gray-800 rounded-2xl flex items-center justify-center"
                        >
                            <!-- This would be a real map component (Google Maps / Leaflet) -->
                            <div
                                class="absolute inset-0 opacity-20"
                                style="
                                    background-image: url('https://upload.wikimedia.org/wikipedia/commons/e/ec/World_map_blank_without_borders.svg');
                                    background-size: cover;
                                    background-position: center;
                                "
                            ></div>

                            <div class="text-center relative z-10">
                                <div
                                    class="w-16 h-16 bg-white dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4 shadow-lg animate-bounce"
                                >
                                    <i
                                        class="fas fa-map-marked-alt text-2xl text-primary-600"
                                    ></i>
                                </div>
                                <h4
                                    class="text-lg font-bold text-gray-700 dark:text-gray-300"
                                >
                                    Peta Rute
                                </h4>
                                <p class="text-sm text-gray-500">
                                    Visualisasi rute perjalanan
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Available Schedules -->
                    <div
                        class="bg-white dark:bg-gray-900 rounded-3xl p-8 shadow-lg border border-gray-100 dark:border-gray-800"
                    >
                        <div class="flex items-center justify-between mb-6">
                            <h3
                                class="text-xl font-bold text-gray-800 dark:text-white"
                            >
                                Jadwal Keberangkatan
                            </h3>
                            <span class="text-sm text-gray-500"
                                >{{
                                    routeModel.available_schedules
                                        ? routeModel.available_schedules.length
                                        : 0
                                }}
                                jadwal ditemukan</span
                            >
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
                                class="flex flex-col md:flex-row md:items-center justify-between p-4 rounded-xl border border-gray-100 dark:border-gray-700 hover:border-primary-200 dark:hover:border-primary-800 hover:bg-primary-50/50 dark:hover:bg-primary-900/10 transition-all group"
                            >
                                <div
                                    class="flex items-center space-x-4 mb-4 md:mb-0"
                                >
                                    <div
                                        class="flex flex-col items-center min-w-[60px]"
                                    >
                                        <span
                                            class="text-lg font-bold text-gray-800 dark:text-white"
                                            >{{
                                                formatTime(
                                                    schedule.departure_time
                                                )
                                            }}</span
                                        >
                                        <span class="text-xs text-gray-500"
                                            >Berangkat</span
                                        >
                                    </div>
                                    <div
                                        class="w-px h-10 bg-gray-200 dark:bg-gray-700"
                                    ></div>
                                    <div>
                                        <h4
                                            class="font-bold text-gray-800 dark:text-white"
                                        >
                                            {{
                                                schedule.bus?.name ||
                                                "Armada TJT"
                                            }}
                                        </h4>
                                        <span
                                            class="inline-block px-2 py-0.5 mt-1 rounded text-xs font-bold bg-primary-100 text-primary-700 dark:bg-primary-900/30 dark:text-primary-400"
                                        >
                                            {{
                                                schedule.bus?.bus_type ||
                                                "Executive"
                                            }}
                                        </span>
                                    </div>
                                </div>

                                <div
                                    class="flex items-center justify-between md:space-x-8"
                                >
                                    <div class="text-right">
                                        <p
                                            class="text-lg font-bold text-brand-red"
                                        >
                                            {{ formatPrice(schedule.price) }}
                                        </p>
                                        <p class="text-xs text-gray-500">
                                            /kursi
                                        </p>
                                    </div>
                                    <Link
                                        :href="route('frontend.booking.index')"
                                        class="px-4 py-2 bg-gray-900 dark:bg-white text-white dark:text-gray-900 rounded-lg text-sm font-bold hover:bg-gray-800 focus:ring-2 focus:ring-offset-2 focus:ring-gray-900 transition-all"
                                    >
                                        Pilih
                                    </Link>
                                </div>
                            </div>
                        </div>

                        <div v-else class="text-center py-12">
                            <i
                                class="fas fa-calendar-times text-4xl text-gray-300 mb-3"
                            ></i>
                            <p class="text-gray-500">
                                Tidak ada jadwal tersedia saat ini.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Info Card -->
                <div class="space-y-6">
                    <div
                        class="bg-white dark:bg-gray-900 rounded-3xl p-6 shadow-lg border border-gray-100 dark:border-gray-800 sticky top-24"
                    >
                        <h3
                            class="text-lg font-bold text-gray-800 dark:text-white mb-4 flex items-center"
                        >
                            <i
                                class="fas fa-info-circle text-primary-500 mr-2"
                            ></i>
                            Info Penting
                        </h3>
                        <ul class="space-y-4">
                            <li
                                class="flex items-start text-sm text-gray-600 dark:text-gray-400"
                            >
                                <i
                                    class="fas fa-check text-green-500 mt-1 mr-3"
                                ></i>
                                <span
                                    >Harap datang 30 menit sebelum
                                    keberangkatan.</span
                                >
                            </li>
                            <li
                                class="flex items-start text-sm text-gray-600 dark:text-gray-400"
                            >
                                <i
                                    class="fas fa-check text-green-500 mt-1 mr-3"
                                ></i>
                                <span
                                    >Tunjukkan e-tiket kepada petugas saat
                                    check-in.</span
                                >
                            </li>
                            <li
                                class="flex items-start text-sm text-gray-600 dark:text-gray-400"
                            >
                                <i
                                    class="fas fa-check text-green-500 mt-1 mr-3"
                                ></i>
                                <span
                                    >Barang bawaan maksimal 20kg per
                                    penumpang.</span
                                >
                            </li>
                            <li
                                class="flex items-start text-sm text-gray-600 dark:text-gray-400"
                            >
                                <i
                                    class="fas fa-check text-green-500 mt-1 mr-3"
                                ></i>
                                <span
                                    >Dilarang membawa hewan peliharaan & barang
                                    berbahaya.</span
                                >
                            </li>
                        </ul>

                        <div
                            class="mt-6 pt-6 border-t border-gray-100 dark:border-gray-700"
                        >
                            <p class="text-xs text-center text-gray-500">
                                Butuh bantuan?
                            </p>
                            <a
                                href="#"
                                class="block w-full py-2 mt-2 text-center border border-gray-200 dark:border-gray-700 rounded-xl text-gray-600 dark:text-gray-300 font-bold hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors"
                            >
                                <i class="fab fa-whatsapp mr-2"></i> Hubungi CS
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
