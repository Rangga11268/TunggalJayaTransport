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
    if (!dateString) return "Tanggal Belum Tersedia";
    const date = new Date(dateString);
    if (isNaN(date.getTime()) || date.getFullYear() <= 1970)
        return "Tanggal Belum Tersedia";

    return date.toLocaleDateString("id-ID", {
        weekday: "long",
        year: "numeric",
        month: "long",
        day: "numeric",
    });
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
    { deep: true },
);
</script>

<template>
    <Head title="Pesan Tiket" />

    <div
        class="pt-24 md:pt-32 pb-12 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto text-center relative z-10"
    >
        <span
            class="inline-block px-4 py-2 rounded-full bg-rose-600 text-white text-[10px] font-bold tracking-[0.2em] mb-6 animate-fade-in uppercase font-unbounded shadow-lg shadow-rose-600/20"
        >
            Reservasi Online
        </span>
        <h1
            class="font-unbounded font-black text-3xl sm:text-4xl md:text-6xl text-gray-900 dark:text-white mb-6 animate-fade-in-up leading-tight uppercase"
        >
            Pesan
            <span class="text-rose-600">Tiket Anda</span>
        </h1>
        <p
            class="text-lg md:text-xl text-gray-500 dark:text-gray-400 max-w-2xl mx-auto animate-fade-in-up stagger-1 font-manrope leading-relaxed"
        >
            Temukan jadwal terbaik dengan standar kenyamanan tertinggi untuk
            perjalanan Anda bersama TUJAGO.
        </p>
    </div>

    <!-- Booking Interface -->
    <div class="bg-white dark:bg-[#050505] min-h-screen relative z-20 pb-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-8">
            <!-- Search Card (Matched with Home Page Style) -->
            <div
                class="bg-white dark:bg-[#111] rounded-3xl shadow-xl border border-gray-100 dark:border-white/5 p-6 md:p-8 backdrop-blur-xl animate-fade-in-up stagger-2 relative z-30"
            >
                <form @submit.prevent="search">
                    <div
                        class="grid grid-cols-1 md:grid-cols-12 gap-6 items-end relative"
                    >
                        <!-- Origin -->
                        <div class="md:col-span-3 space-y-2 group">
                            <label
                                class="block text-[11px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-2 font-manrope"
                            >
                                Keberangkatan
                            </label>
                            <div class="relative group">
                                <div
                                    class="absolute left-0 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-rose-600 transition-colors pl-5"
                                >
                                    <i
                                        class="fas fa-map-marker-alt text-lg"
                                    ></i>
                                </div>
                                <select
                                    v-model="form.origin"
                                    class="block w-full pl-12 pr-10 py-4 text-lg font-bold border-2 border-gray-100 dark:border-white/10 rounded-2xl bg-gray-50 dark:bg-white/5 focus:border-rose-600 focus:ring-0 transition-all cursor-pointer hover:bg-white dark:hover:bg-white/10 text-gray-900 dark:text-white appearance-none relative z-0 font-manrope focus:bg-white dark:focus:bg-black"
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
                                    class="fas fa-chevron-down absolute right-5 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none text-xs"
                                ></i>
                            </div>

                            <!-- Mobile Swap Button -->
                            <div
                                class="flex md:hidden justify-center -my-3 relative z-10"
                            >
                                <button
                                    type="button"
                                    @click="swapLocations"
                                    class="w-10 h-10 rounded-full bg-white dark:bg-[#222] border-2 border-gray-100 dark:border-white/10 text-gray-400 hover:text-rose-600 hover:border-rose-600 transition-all flex items-center justify-center shadow-md transform active:rotate-180 duration-300"
                                >
                                    <i
                                        class="fas fa-exchange-alt text-xs rotate-90"
                                    ></i>
                                </button>
                            </div>
                        </div>

                        <!-- Swap Button -->
                        <div
                            class="hidden md:flex md:col-span-1 justify-center pb-3 relative"
                        >
                            <button
                                type="button"
                                @click="swapLocations"
                                class="w-12 h-12 rounded-full bg-white dark:bg-[#222] border-2 border-gray-100 dark:border-white/10 text-gray-400 hover:text-rose-600 hover:border-rose-600 transition-all flex items-center justify-center shadow-lg transform hover:rotate-180 duration-300 relative z-20"
                            >
                                <i class="fas fa-exchange-alt text-sm"></i>
                            </button>
                        </div>

                        <!-- Destination -->
                        <div class="md:col-span-3 space-y-2 group">
                            <label
                                class="block text-[11px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-2 font-manrope"
                            >
                                Tujuan
                            </label>
                            <div class="relative group">
                                <div
                                    class="absolute left-0 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-rose-600 transition-colors pl-5"
                                >
                                    <i class="fas fa-location-dot text-lg"></i>
                                </div>
                                <select
                                    v-model="form.destination"
                                    class="block w-full pl-12 pr-10 py-4 text-lg font-bold border-2 border-gray-100 dark:border-white/10 rounded-2xl bg-gray-50 dark:bg-white/5 focus:border-rose-600 focus:ring-0 transition-all cursor-pointer hover:bg-white dark:hover:bg-white/10 text-gray-900 dark:text-white appearance-none relative z-0 font-manrope focus:bg-white dark:focus:bg-black"
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
                                    class="fas fa-chevron-down absolute right-5 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none text-xs"
                                ></i>
                            </div>
                        </div>

                        <!-- Date -->
                        <div class="md:col-span-3 space-y-2 group">
                            <label
                                class="block text-[11px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-2 font-manrope"
                            >
                                Tanggal
                            </label>
                            <div class="relative group">
                                <div
                                    class="absolute left-0 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-rose-600 transition-colors pl-5"
                                >
                                    <i class="fas fa-calendar-alt text-lg"></i>
                                </div>
                                <input
                                    type="date"
                                    v-model="form.date"
                                    class="block w-full pl-12 pr-4 py-4 text-lg font-bold border-2 border-gray-100 dark:border-white/10 rounded-2xl bg-gray-50 dark:bg-white/5 focus:border-rose-600 focus:ring-0 transition-all text-gray-900 dark:text-white placeholder-gray-400 relative z-0 font-manrope focus:bg-white dark:focus:bg-black [color-scheme:light] dark:[color-scheme:dark]"
                                />
                            </div>
                        </div>

                        <!-- Submit -->
                        <div class="md:col-span-2">
                            <button
                                type="submit"
                                :disabled="form.processing"
                                class="w-full h-[62px] bg-rose-600 hover:bg-rose-700 text-white rounded-2xl shadow-lg shadow-rose-600/30 transform transition-all hover:-translate-y-1 active:scale-[0.98] font-bold flex items-center justify-center space-x-2 group font-unbounded uppercase tracking-wider text-sm"
                            >
                                <span class="group-hover:mr-2 transition-all"
                                    >Cari</span
                                >
                                <i
                                    class="fas fa-arrow-right opacity-0 group-hover:opacity-100 transition-all -ml-4 group-hover:ml-0"
                                ></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Layout Grid: Sidebar & Results -->
            <div
                class="grid grid-cols-1 lg:grid-cols-4 gap-8 mt-8 animate-fade-in-up"
            >
                <!-- Sidebar Filters -->
                <div class="hidden lg:block lg:col-span-1 space-y-6">
                    <div
                        class="bg-white dark:bg-[#111] rounded-3xl p-6 border border-gray-100 dark:border-white/5 shadow-sm"
                    >
                        <h3
                            class="font-unbounded font-bold text-gray-900 dark:text-white mb-6 flex items-center text-lg"
                        >
                            <i class="fas fa-filter mr-3 text-rose-600"></i>
                            Filter
                        </h3>

                        <div class="space-y-8">
                            <div>
                                <label
                                    class="text-[11px] font-bold text-gray-400 uppercase mb-4 block tracking-widest font-manrope"
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
                                                class="peer h-5 w-5 cursor-pointer appearance-none rounded-lg border-2 border-gray-200 dark:border-gray-700 transition-all checked:border-rose-600 checked:bg-rose-600 hover:border-rose-400"
                                            />
                                            <i
                                                class="fas fa-check absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 text-white opacity-0 peer-checked:opacity-100 text-xs pointer-events-none"
                                            ></i>
                                        </div>
                                        <span
                                            class="text-sm font-bold text-gray-600 dark:text-gray-300 group-hover:text-rose-600 transition-colors font-manrope"
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
                                                class="peer h-5 w-5 cursor-pointer appearance-none rounded-lg border-2 border-gray-200 dark:border-gray-700 transition-all checked:border-rose-600 checked:bg-rose-600 hover:border-rose-400"
                                            />
                                            <i
                                                class="fas fa-check absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 text-white opacity-0 peer-checked:opacity-100 text-xs pointer-events-none"
                                            ></i>
                                        </div>
                                        <span
                                            class="text-sm font-bold text-gray-600 dark:text-gray-300 group-hover:text-rose-600 transition-colors font-manrope"
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
                                                class="peer h-5 w-5 cursor-pointer appearance-none rounded-lg border-2 border-gray-200 dark:border-gray-700 transition-all checked:border-rose-600 checked:bg-rose-600 hover:border-rose-400"
                                            />
                                            <i
                                                class="fas fa-check absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 text-white opacity-0 peer-checked:opacity-100 text-xs pointer-events-none"
                                            ></i>
                                        </div>
                                        <span
                                            class="text-sm font-bold text-gray-600 dark:text-gray-300 group-hover:text-rose-600 transition-colors font-manrope"
                                            >Economy</span
                                        >
                                    </label>
                                </div>
                            </div>

                            <div
                                class="pt-6 border-t border-gray-100 dark:border-white/5"
                            >
                                <label
                                    class="text-[11px] font-bold text-gray-400 uppercase mb-4 block tracking-widest font-manrope"
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
                                                class="peer h-5 w-5 cursor-pointer appearance-none rounded-lg border-2 border-gray-200 dark:border-gray-700 transition-all checked:border-rose-600 checked:bg-rose-600 hover:border-rose-400"
                                            />
                                            <i
                                                class="fas fa-check absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 text-white opacity-0 peer-checked:opacity-100 text-xs pointer-events-none"
                                            ></i>
                                        </div>
                                        <span
                                            class="text-sm font-bold text-gray-600 dark:text-gray-300 group-hover:text-rose-600 transition-colors font-manrope"
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
                                                class="peer h-5 w-5 cursor-pointer appearance-none rounded-lg border-2 border-gray-200 dark:border-gray-700 transition-all checked:border-rose-600 checked:bg-rose-600 hover:border-rose-400"
                                            />
                                            <i
                                                class="fas fa-check absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 text-white opacity-0 peer-checked:opacity-100 text-xs pointer-events-none"
                                            ></i>
                                        </div>
                                        <span
                                            class="text-sm font-bold text-gray-600 dark:text-gray-300 group-hover:text-rose-600 transition-colors font-manrope"
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
                                                class="peer h-5 w-5 cursor-pointer appearance-none rounded-lg border-2 border-gray-200 dark:border-gray-700 transition-all checked:border-rose-600 checked:bg-rose-600 hover:border-rose-400"
                                            />
                                            <i
                                                class="fas fa-check absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 text-white opacity-0 peer-checked:opacity-100 text-xs pointer-events-none"
                                            ></i>
                                        </div>
                                        <span
                                            class="text-sm font-bold text-gray-600 dark:text-gray-300 group-hover:text-rose-600 transition-colors font-manrope"
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
                        class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 bg-white dark:bg-[#111] p-6 rounded-3xl border border-gray-100 dark:border-white/5 shadow-sm"
                    >
                        <div>
                            <h2
                                v-if="form.origin && form.destination"
                                class="text-xl font-bold font-unbounded text-gray-900 dark:text-white flex items-center"
                            >
                                {{ origin }}
                                <i
                                    class="fas fa-long-arrow-alt-right text-gray-400 mx-3"
                                ></i>
                                {{ destination }}
                            </h2>
                            <h2
                                v-else
                                class="text-xl font-bold font-unbounded text-gray-900 dark:text-white flex items-center"
                            >
                                Semua Jadwal Keberangkatan
                            </h2>
                            <p
                                class="text-sm font-bold text-gray-500 mt-1 font-manrope"
                                v-if="form.date"
                            >
                                <i class="far fa-calendar-alt mr-1"></i>
                                {{ formatDate(form.date) }}
                                <span class="mx-2">•</span>
                                <span class="text-rose-600 font-bold">{{
                                    schedules ? schedules.length : 0
                                }}</span>
                                Bus Tersedia
                            </p>
                        </div>
                        <div
                            class="flex items-center space-x-3 bg-gray-50 dark:bg-white/5 p-1 rounded-xl"
                        >
                            <span
                                class="text-[10px] font-bold text-gray-400 px-2 uppercase tracking-widest"
                                >Urutkan</span
                            >
                            <select
                                class="text-sm font-bold border-none bg-transparent text-gray-900 dark:text-white focus:ring-0 cursor-pointer py-1 pl-2 pr-8 rounded-lg hover:bg-white dark:hover:bg-white/10 transition-colors font-manrope"
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
                        class="bg-red-50 dark:bg-red-900/10 border border-red-100 dark:border-red-800 rounded-3xl p-12 text-center animate-fade-in-up"
                    >
                        <div
                            class="w-20 h-20 bg-red-100 dark:bg-red-900/30 rounded-full flex items-center justify-center mx-auto mb-6 text-red-600 dark:text-red-400 text-3xl"
                        >
                            <i class="fas fa-route"></i>
                        </div>
                        <h3
                            class="font-unbounded font-bold text-red-800 dark:text-red-200 text-xl mb-2"
                        >
                            Rute Tidak Ditemukan
                        </h3>
                        <p
                            class="text-red-600 dark:text-red-300 max-w-md mx-auto font-manrope font-medium"
                        >
                            Mohon maaf, rute perjalanan dari
                            <span class="font-bold">{{ form.origin }}</span> ke
                            <span class="font-bold">{{
                                form.destination
                            }}</span>
                            belum tersedia saat ini.
                        </p>
                    </div>

                    <div
                        v-else-if="schedules && schedules.length === 0"
                        class="bg-white dark:bg-[#111] border-2 border-dashed border-gray-200 dark:border-white/10 rounded-3xl p-16 text-center animate-fade-in-up"
                    >
                        <div
                            class="w-24 h-24 bg-gray-50 dark:bg-white/5 rounded-full flex items-center justify-center mx-auto mb-6 text-gray-300 text-5xl"
                        >
                            <i class="fas fa-bus-alt"></i>
                        </div>
                        <h3
                            class="font-unbounded font-bold text-gray-900 dark:text-white text-xl mb-3"
                        >
                            Tidak Ada Jadwal
                        </h3>
                        <p
                            class="text-gray-500 max-w-md mx-auto font-manrope font-medium"
                        >
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
                            class="group bg-white dark:bg-[#111] rounded-3xl overflow-hidden relative border border-gray-100 dark:border-white/5 shadow-sm hover:shadow-xl hover:shadow-rose-600/10 transition-all duration-300"
                            :style="{ animationDelay: `${index * 0.1}s` }"
                        >
                            <div class="flex flex-col md:flex-row">
                                <!-- Left: Bus & Route Info -->
                                <div class="p-6 md:p-8 flex-1 relative">
                                    <!-- Background watermark -->
                                    <div
                                        class="absolute right-0 bottom-0 opacity-[0.02] text-9xl text-gray-900 dark:text-white pointer-events-none select-none overflow-hidden"
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
                                                class="w-16 h-16 rounded-2xl bg-gray-50 dark:bg-white/5 border border-gray-100 dark:border-white/10 flex items-center justify-center text-gray-400 dark:text-gray-500 text-3xl shrink-0"
                                            >
                                                <i class="fas fa-bus"></i>
                                            </div>
                                            <div>
                                                <h4
                                                    class="text-xl font-black font-unbounded text-gray-900 dark:text-white tracking-tight"
                                                >
                                                    {{ schedule.bus.name }}
                                                </h4>
                                                <div
                                                    class="flex items-center space-x-3 mt-2"
                                                >
                                                    <span
                                                        class="bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 border border-blue-100 dark:border-blue-800 text-[10px] font-bold px-2.5 py-1 rounded-md uppercase tracking-wider"
                                                    >
                                                        {{
                                                            schedule.bus
                                                                .bus_type
                                                        }}
                                                    </span>
                                                    <span
                                                        class="text-xs font-mono font-bold text-gray-400 border border-gray-200 dark:border-white/10 px-1.5 py-0.5 rounded"
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
                                                    class="text-2xl font-black font-unbounded text-gray-900 dark:text-white"
                                                >
                                                    {{
                                                        schedule.departure_time
                                                    }}
                                                </div>
                                                <div
                                                    class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-1 truncate font-manrope"
                                                >
                                                    {{ schedule.route.origin }}
                                                </div>
                                            </div>

                                            <div
                                                class="flex-1 flex flex-col items-center px-4 relative"
                                            >
                                                <div
                                                    class="text-[10px] font-bold text-gray-400 mb-1.5 font-manrope"
                                                >
                                                    {{ schedule.duration }}
                                                </div>
                                                <div
                                                    class="w-full h-0.5 bg-gray-200 dark:bg-gray-800 relative flex items-center justify-between"
                                                >
                                                    <div
                                                        class="w-1.5 h-1.5 rounded-full bg-gray-300 dark:bg-gray-700"
                                                    ></div>
                                                    <i
                                                        class="fas fa-chevron-right text-gray-200 dark:text-gray-800 text-[10px] absolute left-1/2 -translate-x-1/2"
                                                    ></i>
                                                    <div
                                                        class="w-1.5 h-1.5 rounded-full bg-gray-300 dark:bg-gray-700"
                                                    ></div>
                                                </div>
                                                <div
                                                    class="mt-1.5 flex items-center space-x-1"
                                                >
                                                    <i
                                                        class="fas fa-leaf text-green-500 text-[10px]"
                                                    ></i>
                                                    <span
                                                        class="text-[10px] font-bold text-green-600 font-manrope"
                                                        >Langsung</span
                                                    >
                                                </div>
                                            </div>

                                            <div class="text-center w-24">
                                                <div
                                                    class="text-2xl font-black font-unbounded text-gray-900 dark:text-white"
                                                >
                                                    {{ schedule.arrival_time }}
                                                </div>
                                                <div
                                                    class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-1 truncate font-manrope"
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
                                        class="flex flex-wrap gap-2 mt-6 pt-6 border-t border-gray-100 dark:border-white/5"
                                    >
                                        <div
                                            class="flex items-center text-[10px] font-bold uppercase tracking-wider text-gray-500 bg-gray-50 dark:bg-white/5 px-3 py-1.5 rounded-lg border border-gray-100 dark:border-white/5"
                                            title="USB Charger"
                                        >
                                            <i
                                                class="fas fa-bolt mr-2 text-yellow-500"
                                            ></i>
                                            USB
                                        </div>
                                        <div
                                            class="flex items-center text-[10px] font-bold uppercase tracking-wider text-gray-500 bg-gray-50 dark:bg-white/5 px-3 py-1.5 rounded-lg border border-gray-100 dark:border-white/5"
                                            title="Air Conditioner"
                                        >
                                            <i
                                                class="fas fa-snowflake mr-2 text-cyan-500"
                                            ></i>
                                            AC
                                        </div>
                                        <div
                                            class="flex items-center text-[10px] font-bold uppercase tracking-wider text-gray-500 bg-gray-50 dark:bg-white/5 px-3 py-1.5 rounded-lg border border-gray-100 dark:border-white/5"
                                            title="Reclining Seat"
                                        >
                                            <i
                                                class="fas fa-couch mr-2 text-rose-500"
                                            ></i>
                                            Seat 2-2
                                        </div>
                                        <div
                                            class="flex items-center text-[10px] font-bold uppercase tracking-wider text-gray-500 bg-gray-50 dark:bg-white/5 px-3 py-1.5 rounded-lg border border-gray-100 dark:border-white/5"
                                        >
                                            <i
                                                class="fas fa-wifi mr-2 text-violet-500"
                                            ></i>
                                            WIFI
                                        </div>
                                    </div>
                                </div>

                                <!-- Divider (Dotted Line) -->
                                <div
                                    class="relative md:w-px w-full md:h-auto h-px bg-transparent shrink-0"
                                >
                                    <div
                                        class="absolute inset-0 md:border-l-2 border-t-2 border-dashed border-gray-100 dark:border-[#222] m-auto"
                                    ></div>
                                    <!-- Cutout Circles -->
                                    <div
                                        class="absolute -top-3 -left-3 md:-top-3 md:-left-1.5 w-6 h-6 bg-white dark:bg-[#050505] rounded-full z-10 border-b border-gray-100 dark:border-white/5"
                                    ></div>
                                    <div
                                        class="absolute -bottom-3 -left-3 md:-bottom-3 md:-left-1.5 w-6 h-6 bg-white dark:bg-[#050505] rounded-full z-10 border-t border-gray-100 dark:border-white/5"
                                    ></div>
                                </div>

                                <!-- Right: Price & Action -->
                                <div
                                    class="p-6 md:p-8 md:w-72 bg-gray-50/50 dark:bg-white/5 flex flex-col justify-center items-center text-center space-y-5 shrink-0 relative"
                                >
                                    <!-- Departed Overlay -->
                                    <div
                                        v-if="schedule.has_departed"
                                        class="absolute inset-0 bg-white/60 dark:bg-black/60 backdrop-blur-[2px] z-10 flex items-center justify-center p-4"
                                    >
                                        <div
                                            class="bg-rose-600 text-white px-4 py-2 rounded-2xl font-black font-unbounded text-sm shadow-xl transform -rotate-12 border-2 border-white dark:border-black"
                                        >
                                            BERANGKAT
                                        </div>
                                    </div>

                                    <div>
                                        <div
                                            class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2 font-manrope"
                                        >
                                            Harga Tiket
                                        </div>
                                        <div
                                            class="text-3xl font-black text-rose-600 tracking-tight font-unbounded"
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
                                            class="w-full flex items-center justify-center py-4 bg-gray-900 dark:bg-white hover:bg-rose-600 dark:hover:bg-rose-600 text-white dark:text-gray-900 hover:text-white dark:hover:text-white rounded-2xl font-bold font-unbounded text-sm transition-all duration-300 shadow-lg shadow-gray-200 dark:shadow-none group-hover:scale-105"
                                        >
                                            Pilih Tiket
                                        </Link>
                                        <button
                                            v-else
                                            disabled
                                            class="w-full py-4 text-sm bg-gray-200 dark:bg-gray-800 text-gray-400 rounded-2xl font-bold font-unbounded cursor-not-allowed"
                                        >
                                            Habis
                                        </button>

                                        <div
                                            class="text-[10px] font-bold text-gray-400 flex items-center justify-center space-x-1"
                                        >
                                            <i class="fas fa-chair text-xs"></i>
                                            <span>
                                                Sisa
                                                {{ schedule.available_seats }}
                                                Kursi
                                            </span>
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
