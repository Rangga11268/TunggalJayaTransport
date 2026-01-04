<script setup>
import { ref, watch } from "vue";
import { Head, Link, useForm, router } from "@inertiajs/vue3";
import FrontendLayout from "@/Layouts/FrontendLayout.vue";

defineOptions({ layout: FrontendLayout });

const props = defineProps({
    schedules: Array,
    origins: Array,
    destinations: Array,
    origin: String,
    destination: String,
    validPair: Boolean,
    filters: Object,
});

const form = useForm({
    origin: props.filters.origin || "",
    destination: props.filters.destination || "",
    date: props.filters.date || "",
    class: props.filters.class ? props.filters.class.split(",") : [],
    time: props.filters.time ? props.filters.time.split(",") : [],
});

const search = () => {
    // Ubah array jadi string koma-komaan buat URL
    const params = {
        ...form,
        class: form.class.length ? form.class.join(",") : null,
        time: form.time.length ? form.time.join(",") : null,
    };

    router.get(route("frontend.booking.index"), params, {
        preserveState: true,
        preserveScroll: true,
        only: ["schedules", "validPair", "filters"],
    });
};

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
    };
    return new Date(dateString).toLocaleDateString("id-ID", options);
};

// Tuker kota asal sama tujuan
const swapLocations = () => {
    const temp = form.origin;
    form.origin = form.destination;
    form.destination = temp;
};

// Cari otomatis pas filter ganti
watch(
    () => [form.origin, form.destination, form.date, form.class, form.time],
    () => {
        search();
    },
    { deep: true }
);
</script>

<template>
    <Head title="Pesan Tiket" />

    <!-- Hero Header (Matched with Routes Page) -->
    <!-- Clean Title Section -->
    <div class="pt-24 pb-6 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto text-center">
        <span
            class="inline-block px-4 py-1.5 rounded-full bg-primary-50 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400 text-xs font-bold tracking-widest mb-6 animate-fade-in uppercase"
        >
            RESERVASI ONLINE
        </span>
        <h1
            class="text-4xl md:text-5xl lg:text-6xl font-black font-serif text-gray-900 dark:text-white mb-6 animate-fade-in-up"
        >
            Pesan
            <span
                class="text-transparent bg-clip-text bg-gradient-to-r from-brand-red to-orange-500"
                >Tiket Anda</span
            >
        </h1>
        <p
            class="text-lg text-gray-500 dark:text-gray-400 max-w-2xl mx-auto animate-fade-in-up stagger-1"
        >
            Temukan jadwal terbaik dengan standar kenyamanan tertinggi untuk
            perjalanan Anda bersama TUJAGO.
        </p>
    </div>

    <!-- Booking Interface -->
    <div class="bg-gray-50 dark:bg-gray-950 min-h-screen relative z-20 pb-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Search Card (Matched with Home Page Style) -->
            <div
                class="bg-white dark:bg-gray-900 rounded-3xl shadow-2xl shadow-black/20 border border-gray-100 dark:border-gray-800 p-6 md:p-10 backdrop-blur-xl animate-fade-in-up stagger-2"
            >
                <form @submit.prevent="search">
                    <div
                        class="grid grid-cols-1 md:grid-cols-12 gap-6 items-end"
                    >
                        <!-- Origin -->
                        <div class="md:col-span-3 space-y-2 group">
                            <label
                                class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2"
                            >
                                DARI
                            </label>
                            <div class="relative">
                                <i
                                    class="fas fa-map-marker-alt absolute left-4 top-1/2 -translate-y-1/2 text-brand-red text-lg z-10"
                                ></i>
                                <select
                                    v-model="form.origin"
                                    class="block w-full pl-12 pr-10 py-4 text-base font-bold border-gray-200 dark:border-gray-700 rounded-2xl bg-gray-50 dark:bg-gray-800 focus:ring-2 focus:ring-brand-red/20 focus:border-brand-red transition-all cursor-pointer hover:bg-white dark:hover:bg-gray-700 text-gray-800 dark:text-white appearance-none relative z-0"
                                >
                                    <option value="" disabled>
                                        Pilih Kota Asal
                                    </option>
                                    <option
                                        v-for="opt in origins"
                                        :key="opt"
                                        :value="opt"
                                    >
                                        {{ opt }}
                                    </option>
                                </select>
                                <i
                                    class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none text-xs"
                                ></i>
                            </div>
                        </div>

                        <!-- Swap Button -->
                        <div
                            class="hidden md:flex md:col-span-1 justify-center pb-3"
                        >
                            <button
                                type="button"
                                @click="swapLocations"
                                class="w-10 h-10 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center text-gray-400 hover:text-brand-red pars transition-colors cursor-pointer transform hover:rotate-180 duration-300"
                            >
                                <i class="fas fa-exchange-alt"></i>
                            </button>
                        </div>

                        <!-- Destination -->
                        <div class="md:col-span-3 space-y-2 group">
                            <label
                                class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2"
                            >
                                KE
                            </label>
                            <div class="relative">
                                <i
                                    class="fas fa-location-dot absolute left-4 top-1/2 -translate-y-1/2 text-brand-red text-lg z-10"
                                ></i>
                                <select
                                    v-model="form.destination"
                                    class="block w-full pl-12 pr-10 py-4 text-base font-bold border-gray-200 dark:border-gray-700 rounded-2xl bg-gray-50 dark:bg-gray-800 focus:ring-2 focus:ring-brand-red/20 focus:border-brand-red transition-all cursor-pointer hover:bg-white dark:hover:bg-gray-700 text-gray-800 dark:text-white appearance-none relative z-0"
                                >
                                    <option value="" disabled>
                                        Pilih Kota Tujuan
                                    </option>
                                    <option
                                        v-for="opt in destinations"
                                        :key="opt"
                                        :value="opt"
                                    >
                                        {{ opt }}
                                    </option>
                                </select>
                                <i
                                    class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none text-xs"
                                ></i>
                            </div>
                        </div>

                        <!-- Date -->
                        <div class="md:col-span-3 space-y-2 group">
                            <label
                                class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2"
                            >
                                TANGGAL
                            </label>
                            <div class="relative">
                                <i
                                    class="fas fa-calendar-alt absolute left-4 top-1/2 -translate-y-1/2 text-brand-red text-lg z-10"
                                ></i>
                                <input
                                    type="date"
                                    v-model="form.date"
                                    class="block w-full pl-12 pr-4 py-4 text-base font-bold border-gray-200 dark:border-gray-700 rounded-2xl bg-gray-50 dark:bg-gray-800 focus:ring-2 focus:ring-brand-red/20 focus:border-brand-red transition-all text-gray-800 dark:text-white placeholder-gray-400 relative z-0"
                                />
                            </div>
                        </div>

                        <!-- Submit -->
                        <div class="md:col-span-2">
                            <button
                                type="submit"
                                :disabled="form.processing"
                                class="w-full h-[58px] bg-brand-red hover:bg-red-700 text-white rounded-2xl shadow-xl shadow-brand-red/20 transform transition-all hover:-translate-y-1 active:scale-[0.98] font-bold flex items-center justify-center space-x-2 group"
                            >
                                <i class="fas fa-search text-lg"></i>
                                <span>Cari Tiket</span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Layout Grid: Sidebar & Results -->
            <div
                class="grid grid-cols-1 lg:grid-cols-4 gap-8 mt-12 animate-fade-in-up"
            >
                <!-- Sidebar Filters -->
                <div class="hidden lg:block lg:col-span-1 space-y-6">
                    <div
                        class="bg-white dark:bg-gray-900 rounded-3xl p-6 border border-gray-100 dark:border-gray-800 shadow-sm sticky top-24"
                    >
                        <h3
                            class="font-bold text-gray-800 dark:text-white mb-6 flex items-center text-lg"
                        >
                            <i class="fas fa-filter mr-3 text-brand-red"></i>
                            Filter
                        </h3>

                        <div class="space-y-6">
                            <div>
                                <label
                                    class="text-xs font-bold text-gray-400 uppercase mb-3 block tracking-wider"
                                    >Kelas Layanan</label
                                >
                                <div class="space-y-3">
                                    <label
                                        class="flex items-center space-x-3 cursor-pointer group"
                                    >
                                        <div class="relative flex items-center">
                                            <input
                                                type="checkbox"
                                                v-model="form.class"
                                                value="Executive"
                                                class="peer h-5 w-5 cursor-pointer appearance-none rounded-md border border-gray-300 transition-all checked:border-brand-red checked:bg-brand-red hover:border-brand-red"
                                            />
                                            <i
                                                class="fas fa-check absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 text-white opacity-0 peer-checked:opacity-100 text-xs pointer-events-none"
                                            ></i>
                                        </div>
                                        <span
                                            class="text-sm font-medium text-gray-600 dark:text-gray-300 group-hover:text-brand-red transition-colors"
                                            >Executive</span
                                        >
                                    </label>
                                    <label
                                        class="flex items-center space-x-3 cursor-pointer group"
                                    >
                                        <div class="relative flex items-center">
                                            <input
                                                type="checkbox"
                                                v-model="form.class"
                                                value="Suites Class"
                                                class="peer h-5 w-5 cursor-pointer appearance-none rounded-md border border-gray-300 transition-all checked:border-brand-red checked:bg-brand-red hover:border-brand-red"
                                            />
                                            <i
                                                class="fas fa-check absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 text-white opacity-0 peer-checked:opacity-100 text-xs pointer-events-none"
                                            ></i>
                                        </div>
                                        <span
                                            class="text-sm font-medium text-gray-600 dark:text-gray-300 group-hover:text-brand-red transition-colors"
                                            >Suites Class</span
                                        >
                                    </label>
                                    <label
                                        class="flex items-center space-x-3 cursor-pointer group"
                                    >
                                        <div class="relative flex items-center">
                                            <input
                                                type="checkbox"
                                                v-model="form.class"
                                                value="Economy"
                                                class="peer h-5 w-5 cursor-pointer appearance-none rounded-md border border-gray-300 transition-all checked:border-brand-red checked:bg-brand-red hover:border-brand-red"
                                            />
                                            <i
                                                class="fas fa-check absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 text-white opacity-0 peer-checked:opacity-100 text-xs pointer-events-none"
                                            ></i>
                                        </div>
                                        <span
                                            class="text-sm font-medium text-gray-600 dark:text-gray-300 group-hover:text-brand-red transition-colors"
                                            >Economy</span
                                        >
                                    </label>
                                </div>
                            </div>

                            <div
                                class="pt-6 border-t border-gray-100 dark:border-gray-800"
                            >
                                <label
                                    class="text-xs font-bold text-gray-400 uppercase mb-3 block tracking-wider"
                                    >Waktu Berangkat</label
                                >
                                <div class="space-y-3">
                                    <label
                                        class="flex items-center space-x-3 cursor-pointer group"
                                    >
                                        <div class="relative flex items-center">
                                            <input
                                                type="checkbox"
                                                v-model="form.time"
                                                value="morning"
                                                class="peer h-5 w-5 cursor-pointer appearance-none rounded-md border border-gray-300 transition-all checked:border-brand-red checked:bg-brand-red hover:border-brand-red"
                                            />
                                            <i
                                                class="fas fa-check absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 text-white opacity-0 peer-checked:opacity-100 text-xs pointer-events-none"
                                            ></i>
                                        </div>
                                        <span
                                            class="text-sm font-medium text-gray-600 dark:text-gray-300 group-hover:text-brand-red transition-colors"
                                            >Pagi (00:00 - 12:00)</span
                                        >
                                    </label>
                                    <label
                                        class="flex items-center space-x-3 cursor-pointer group"
                                    >
                                        <div class="relative flex items-center">
                                            <input
                                                type="checkbox"
                                                v-model="form.time"
                                                value="afternoon"
                                                class="peer h-5 w-5 cursor-pointer appearance-none rounded-md border border-gray-300 transition-all checked:border-brand-red checked:bg-brand-red hover:border-brand-red"
                                            />
                                            <i
                                                class="fas fa-check absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 text-white opacity-0 peer-checked:opacity-100 text-xs pointer-events-none"
                                            ></i>
                                        </div>
                                        <span
                                            class="text-sm font-medium text-gray-600 dark:text-gray-300 group-hover:text-brand-red transition-colors"
                                            >Sore (12:00 - 18:00)</span
                                        >
                                    </label>
                                    <label
                                        class="flex items-center space-x-3 cursor-pointer group"
                                    >
                                        <div class="relative flex items-center">
                                            <input
                                                type="checkbox"
                                                v-model="form.time"
                                                value="evening"
                                                class="peer h-5 w-5 cursor-pointer appearance-none rounded-md border border-gray-300 transition-all checked:border-brand-red checked:bg-brand-red hover:border-brand-red"
                                            />
                                            <i
                                                class="fas fa-check absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 text-white opacity-0 peer-checked:opacity-100 text-xs pointer-events-none"
                                            ></i>
                                        </div>
                                        <span
                                            class="text-sm font-medium text-gray-600 dark:text-gray-300 group-hover:text-brand-red transition-colors"
                                            >Malam (18:00 - 00:00)</span
                                        >
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Results List -->
                <div class="lg:col-span-3 space-y-6">
                    <!-- Header -->
                    <div
                        class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 bg-white dark:bg-gray-900 p-6 rounded-3xl border border-gray-100 dark:border-gray-800 shadow-sm"
                    >
                        <div>
                            <h2
                                v-if="form.origin && form.destination"
                                class="text-xl font-bold text-gray-900 dark:text-white flex items-center"
                            >
                                {{ origin }}
                                <i
                                    class="fas fa-long-arrow-alt-right text-gray-400 mx-3"
                                ></i>
                                {{ destination }}
                            </h2>
                            <h2
                                v-else
                                class="text-xl font-bold text-gray-900 dark:text-white flex items-center"
                            >
                                Semua Jadwal Keberangkatan
                            </h2>
                            <p
                                class="text-sm font-medium text-gray-500 mt-1"
                                v-if="form.date"
                            >
                                <i class="far fa-calendar-alt mr-1"></i>
                                {{ formatDate(form.date) }}
                                <span class="mx-2">•</span>
                                <span class="text-brand-red font-bold">{{
                                    schedules ? schedules.length : 0
                                }}</span>
                                Bus Tersedia
                            </p>
                        </div>
                        <div
                            class="flex items-center space-x-3 bg-gray-50 dark:bg-gray-800 p-1 rounded-xl"
                        >
                            <span
                                class="text-xs font-bold text-gray-500 px-2 uppercase tracking-wide"
                                >Urutkan</span
                            >
                            <select
                                class="text-sm font-bold border-none bg-transparent text-gray-800 dark:text-white focus:ring-0 cursor-pointer py-1 pl-2 pr-8 rounded-lg hover:bg-white dark:hover:bg-gray-700 transition-colors"
                            >
                                <option>Paling Awal</option>
                                <option>Termurah</option>
                                <option>Tercepat</option>
                            </select>
                        </div>
                    </div>

                    <!-- Loading / Error States -->
                    <div
                        v-if="form.origin && form.destination && !validPair"
                        class="bg-red-50 dark:bg-red-900/10 border border-red-100 dark:border-red-800 rounded-3xl p-10 text-center animate-fade-in-up"
                    >
                        <div
                            class="w-20 h-20 bg-red-100 dark:bg-red-900/30 rounded-full flex items-center justify-center mx-auto mb-6 text-red-600 dark:text-red-400 text-3xl"
                        >
                            <i class="fas fa-route"></i>
                        </div>
                        <h3
                            class="font-bold text-red-800 dark:text-red-200 text-xl mb-2"
                        >
                            Rute Tidak Ditemukan
                        </h3>
                        <p
                            class="text-red-600 dark:text-red-300 max-w-md mx-auto"
                        >
                            Mohon maaf, rute perjalanan dari
                            <span class="font-bold">{{ form.origin }}</span> ke
                            <span class="font-bold">{{
                                form.destination
                            }}</span>
                            belum tersedia saat ini.
                        </p>
                        <button
                            class="mt-6 px-6 py-2.5 bg-white border border-red-200 text-red-700 text-sm font-bold rounded-xl hover:bg-red-50 hover:shadow-md transition-all"
                        >
                            Lihat Peta Rute
                        </button>
                    </div>

                    <div
                        v-else-if="schedules && schedules.length === 0"
                        class="bg-white dark:bg-gray-900 border-2 border-dashed border-gray-200 dark:border-gray-800 rounded-3xl p-16 text-center animate-fade-in-up"
                    >
                        <div
                            class="w-24 h-24 bg-gray-50 dark:bg-gray-800 rounded-full flex items-center justify-center mx-auto mb-6 text-gray-300 text-5xl"
                        >
                            <i class="fas fa-bus-alt"></i>
                        </div>
                        <h3
                            class="font-bold text-gray-900 dark:text-white text-xl mb-3"
                        >
                            Tidak Ada Jadwal
                        </h3>
                        <p class="text-gray-500 max-w-md mx-auto">
                            Tidak ada keberangkatan bus yang ditemukan untuk
                            tanggal yang dipilih. Silakan coba cari tanggal
                            lain.
                        </p>
                    </div>

                    <!-- Ticket Cards -->
                    <div v-else class="space-y-6">
                        <div
                            v-for="(schedule, index) in schedules"
                            :key="schedule.id"
                            class="group bg-white dark:bg-gray-900 rounded-3xl shadow-sm hover:shadow-2xl hover:shadow-primary-900/10 hover:-translate-y-1 border border-gray-100 dark:border-gray-800 transition-all duration-300 overflow-hidden relative"
                            :style="{ animationDelay: `${index * 0.1}s` }"
                        >
                            <div class="flex flex-col md:flex-row">
                                <!-- Left: Bus & Route Info -->
                                <div class="p-8 flex-1 relative">
                                    <!-- Background watermark -->
                                    <div
                                        class="absolute right-0 bottom-0 opacity-[0.03] text-9xl text-gray-900 pointer-events-none select-none overflow-hidden"
                                    >
                                        <i class="fas fa-bus"></i>
                                    </div>

                                    <div
                                        class="flex flex-col md:flex-row justify-between md:items-center gap-8 relative z-10"
                                    >
                                        <!-- Bus Identity -->
                                        <div
                                            class="flex items-center space-x-5"
                                        >
                                            <div
                                                class="w-16 h-16 rounded-2xl bg-gradient-to-br from-primary-50 to-white dark:from-gray-800 dark:to-gray-900 border border-primary-100 dark:border-gray-700 flex items-center justify-center text-primary-600 dark:text-primary-400 text-3xl shadow-inner shrink-0"
                                            >
                                                <i class="fas fa-bus"></i>
                                            </div>
                                            <div>
                                                <h4
                                                    class="text-xl font-extrabold text-gray-900 dark:text-white tracking-tight"
                                                >
                                                    {{ schedule.bus.name }}
                                                </h4>
                                                <div
                                                    class="flex items-center space-x-3 mt-1.5"
                                                >
                                                    <span
                                                        class="bg-brand-red/10 text-brand-red text-[11px] font-bold px-2.5 py-1 rounded-md uppercase tracking-wider"
                                                    >
                                                        {{
                                                            schedule.bus
                                                                .bus_type
                                                        }}
                                                    </span>
                                                    <span
                                                        class="text-xs font-mono font-medium text-gray-400 border border-gray-200 dark:border-gray-700 px-1.5 py-0.5 rounded"
                                                        >{{
                                                            schedule.bus
                                                                .plate_number
                                                        }}</span
                                                    >
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Departure > Arrival Flow -->
                                        <div
                                            class="flex items-center flex-1 justify-center px-4 md:px-8 mt-4 md:mt-0"
                                        >
                                            <div class="text-center w-24">
                                                <div
                                                    class="text-2xl font-black text-gray-900 dark:text-white"
                                                >
                                                    {{
                                                        schedule.departure_time
                                                    }}
                                                </div>
                                                <div
                                                    class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-1 truncate"
                                                >
                                                    {{ schedule.route.origin }}
                                                </div>
                                            </div>

                                            <div
                                                class="flex-1 flex flex-col items-center px-4 relative"
                                            >
                                                <div
                                                    class="text-[10px] font-bold text-gray-400 mb-1.5"
                                                >
                                                    {{ schedule.duration }}
                                                </div>
                                                <div
                                                    class="w-full h-0.5 bg-gray-200 dark:bg-gray-700 relative flex items-center justify-between"
                                                >
                                                    <div
                                                        class="w-2 h-2 rounded-full bg-gray-300 dark:bg-gray-600 border-2 border-white dark:border-gray-900"
                                                    ></div>
                                                    <i
                                                        class="fas fa-chevron-right text-gray-300 text-[10px] absolute left-1/2 -translate-x-1/2"
                                                    ></i>
                                                    <div
                                                        class="w-2 h-2 rounded-full bg-gray-300 dark:bg-gray-600 border-2 border-white dark:border-gray-900"
                                                    ></div>
                                                </div>
                                                <div
                                                    class="mt-1.5 flex items-center space-x-1"
                                                >
                                                    <i
                                                        class="fas fa-leaf text-green-500 text-[10px]"
                                                    ></i>
                                                    <span
                                                        class="text-[10px] font-bold text-green-600"
                                                        >Langsung</span
                                                    >
                                                </div>
                                            </div>

                                            <div class="text-center w-24">
                                                <div
                                                    class="text-2xl font-black text-gray-900 dark:text-white"
                                                >
                                                    {{ schedule.arrival_time }}
                                                </div>
                                                <div
                                                    class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-1 truncate"
                                                >
                                                    {{
                                                        schedule.route
                                                            .destination
                                                    }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Facilities Line -->
                                    <div
                                        class="flex gap-4 mt-6 pt-6 border-t border-gray-100 dark:border-gray-800/50"
                                    >
                                        <div
                                            class="flex items-center text-xs font-semibold text-gray-500 bg-gray-50 dark:bg-gray-800 px-3 py-1.5 rounded-lg"
                                            title="USB Charger"
                                        >
                                            <i
                                                class="fas fa-bolt mr-2 text-gold-500"
                                            ></i>
                                            USB Charger
                                        </div>
                                        <div
                                            class="flex items-center text-xs font-semibold text-gray-500 bg-gray-50 dark:bg-gray-800 px-3 py-1.5 rounded-lg"
                                            title="Air Conditioner"
                                        >
                                            <i
                                                class="fas fa-snowflake mr-2 text-sky-500"
                                            ></i>
                                            Full AC
                                        </div>
                                        <div
                                            class="flex items-center text-xs font-semibold text-gray-500 bg-gray-50 dark:bg-gray-800 px-3 py-1.5 rounded-lg"
                                            title="Reclining Seat"
                                        >
                                            <i
                                                class="fas fa-couch mr-2 text-brand-red"
                                            ></i>
                                            2-2 Seater
                                        </div>
                                    </div>
                                </div>

                                <!-- Divider (Dotted Line) -->
                                <div
                                    class="relative md:w-px w-full md:h-auto h-0.5 bg-transparent shrink-0"
                                >
                                    <div
                                        class="absolute inset-0 md:border-l border-t-2 border-dashed border-gray-200 dark:border-gray-700 m-auto"
                                    ></div>
                                    <!-- Cutout Circles -->
                                    <div
                                        class="absolute -top-3 -left-3 md:-top-3 md:-left-1.5 w-6 h-6 bg-gray-50 dark:bg-gray-950 rounded-full z-10"
                                    ></div>
                                    <div
                                        class="absolute -bottom-3 -left-3 md:-bottom-3 md:-left-1.5 w-6 h-6 bg-gray-50 dark:bg-gray-950 rounded-full z-10"
                                    ></div>
                                </div>

                                <!-- Right: Price & Action -->
                                <div
                                    class="p-8 md:w-72 bg-gray-50/80 dark:bg-gray-800/20 flex flex-col justify-center items-center text-center space-y-5 shrink-0 relative"
                                >
                                    <!-- Departed Overlay -->
                                    <div
                                        v-if="schedule.has_departed"
                                        class="absolute inset-0 bg-white/50 dark:bg-gray-900/50 backdrop-blur-[1px] z-10 flex items-center justify-center"
                                    >
                                        <div
                                            class="bg-red-600 text-white px-6 py-2 rounded-full font-bold shadow-lg transform -rotate-12 border-2 border-white"
                                        >
                                            SUDAH BERANGKAT
                                        </div>
                                    </div>

                                    <div>
                                        <div
                                            class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1"
                                        >
                                            Harga per kursi
                                        </div>
                                        <div
                                            class="text-3xl font-black text-brand-red tracking-tight"
                                            :class="{
                                                'opacity-50 grayscale':
                                                    schedule.has_departed,
                                            }"
                                        >
                                            {{ formatPrice(schedule.price) }}
                                        </div>
                                    </div>

                                    <div class="w-full space-y-3">
                                        <Link
                                            v-if="!schedule.has_departed"
                                            :href="
                                                route('frontend.booking.show', {
                                                    id: schedule.id,
                                                    date: form.date,
                                                })
                                            "
                                            class="btn-premium w-full justify-center py-3.5 text-base shadow-lg shadow-brand-red/20 group-hover:scale-105 transition-transform"
                                        >
                                            Pilih Tiket
                                        </Link>
                                        <button
                                            v-else
                                            disabled
                                            class="w-full py-3.5 text-base bg-gray-300 dark:bg-gray-700 text-gray-500 rounded-xl font-bold cursor-not-allowed"
                                        >
                                            Tidak Tersedia
                                        </button>

                                        <div
                                            class="text-xs font-semibold text-gray-500 flex items-center justify-center bg-white dark:bg-gray-800 py-2 rounded-lg border border-gray-200 dark:border-gray-700"
                                        >
                                            <i
                                                class="fas fa-chair text-gray-400 mr-2"
                                            ></i>
                                            Sisa
                                            {{ schedule.available_seats }} Kursi
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
