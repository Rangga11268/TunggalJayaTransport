<script setup>
import { Head, Link } from "@inertiajs/vue3";
import FrontendLayout from "@/Layouts/FrontendLayout.vue";

defineOptions({ layout: FrontendLayout });

import { ref } from "vue";

const props = defineProps({
    bookings: Object,
    charter_bookings: Object,
});

const activeTab = ref('reguler');

const formatDate = (dateString) => {
    return new Date(dateString).toLocaleDateString("id-ID", {
        weekday: "short",
        day: "numeric",
        month: "short",
        year: "numeric",
    });
};

const formatCurrency = (value) => {
    return new Intl.NumberFormat("id-ID", {
        style: "currency",
        currency: "IDR",
        minimumFractionDigits: 0,
    }).format(value);
};

const formatTime = (dateString) => {
    if (!dateString) return "-";
    const date = new Date(dateString);
    if (isNaN(date.getTime())) {
        return dateString.substring(0, 5);
    }
    return date
        .toLocaleTimeString("id-ID", {
            hour: "2-digit",
            minute: "2-digit",
            hour12: false,
        })
        .replace(".", ":");
};

const getStatusBadge = (status) => {
    switch (status) {
        case "pending": return "bg-amber-100 text-amber-800 border-amber-200 dark:bg-amber-900/30 dark:text-amber-400 dark:border-amber-900/20";
        case "quoted": return "bg-blue-100 text-blue-800 border-blue-200 dark:bg-blue-900/30 dark:text-blue-400 dark:border-blue-900/20";
        case "confirmed": return "bg-emerald-100 text-emerald-800 border-emerald-200 dark:bg-emerald-900/30 dark:text-emerald-400 dark:border-emerald-900/20";
        case "completed": return "bg-purple-100 text-purple-800 border-purple-200 dark:bg-purple-900/30 dark:text-purple-400 dark:border-purple-900/20";
        case "cancelled": return "bg-red-100 text-red-800 border-red-200 dark:bg-red-900/30 dark:text-red-400 dark:border-red-900/20";
        default: return "bg-gray-100 text-gray-800 border-gray-200 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-700";
    }
};
</script>

<template>
    <Head title="Riwayat Pemesanan" />

    <div
        class="bg-gray-50 dark:bg-[#050505] min-h-screen font-sans selection:bg-rose-600 selection:text-white pb-32"
    >
        <!-- Dashboard Header -->
        <div
            class="relative pt-24 md:pt-32 pb-12 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto text-center overflow-hidden"
        >
            <!-- Abstract Decor -->
            <div
                class="absolute top-0 right-0 w-[400px] h-[400px] bg-rose-600/5 rounded-full blur-[100px] -z-10"
            ></div>

            <span
                class="inline-block py-1 px-3 rounded-full bg-rose-50 dark:bg-rose-900/10 text-rose-600 border border-rose-100 dark:border-rose-900/20 text-xs font-bold tracking-widest uppercase mb-6 font-unbounded"
            >
                Dashboard Penumpang
            </span>
            <h1
                class="text-3xl sm:text-4xl md:text-6xl font-black text-gray-900 dark:text-white mb-6 font-unbounded"
            >
                Riwayat <span class="text-rose-600">Perjalanan</span>
            </h1>
            <p
                class="text-lg text-gray-500 dark:text-gray-400 max-w-2xl mx-auto font-manrope"
            >
                Pantau status tiket dan kelola riwayat perjalanan Anda di satu
                tempat yang aman.
            </p>
        </div>

        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
            
            <!-- Tabs -->
            <div class="flex flex-wrap justify-center gap-4 mb-10">
                <button 
                    @click="activeTab = 'reguler'" 
                    class="px-6 py-3 rounded-full font-bold transition-all"
                    :class="activeTab === 'reguler' ? 'bg-rose-600 text-white shadow-lg shadow-rose-600/30' : 'bg-white dark:bg-[#111] text-gray-500 hover:text-gray-900 dark:hover:text-white'"
                >
                    Tiket Reguler
                </button>
                <button 
                    @click="activeTab = 'charter'" 
                    class="px-6 py-3 rounded-full font-bold transition-all"
                    :class="activeTab === 'charter' ? 'bg-rose-600 text-white shadow-lg shadow-rose-600/30' : 'bg-white dark:bg-[#111] text-gray-500 hover:text-gray-900 dark:hover:text-white'"
                >
                    Sewa Pariwisata
                </button>
            </div>

            <!-- Empty State -->
            <div
                v-if="(activeTab === 'reguler' && bookings.data.length === 0) || (activeTab === 'charter' && charter_bookings?.data.length === 0)"
                class="bg-white dark:bg-[#111] rounded-[2.5rem] p-16 text-center shadow-xl border border-gray-100 dark:border-white/5"
            >
                <div
                    class="w-24 h-24 bg-gray-50 dark:bg-white/5 rounded-full flex items-center justify-center mx-auto mb-8"
                >
                    <i
                        class="text-4xl text-gray-200 dark:text-white/10"
                        :class="activeTab === 'reguler' ? 'fas fa-ticket-alt' : 'fas fa-umbrella-beach'"
                    ></i>
                </div>
                <h3
                    class="text-2xl font-black font-unbounded text-gray-900 dark:text-white mb-4"
                >
                    Belum Ada {{ activeTab === 'reguler' ? 'Tiket' : 'Sewa Pariwisata' }}
                </h3>
                <p
                    class="text-gray-500 dark:text-gray-400 mb-10 max-w-sm mx-auto font-manrope"
                >
                    Sepertinya Anda belum memiliki riwayat pemesanan di kategori ini. Mulai perjalanan baru sekarang!
                </p>
                <Link
                    :href="activeTab === 'reguler' ? route('booking.index') : route('frontend.charter.index')"
                    class="inline-flex py-4 px-10 bg-rose-600 text-white font-black font-unbounded rounded-2xl shadow-lg shadow-rose-600/30 hover:bg-rose-700 hover:scale-[1.02] transition-all duration-300"
                >
                    {{ activeTab === 'reguler' ? 'Pesan Tiket Sekarang' : 'Sewa Bus Pariwisata' }}
                </Link>
            </div>

            <!-- Booking List -->
            <div v-else class="space-y-8">
                <!-- Reguler List -->
                <div v-if="activeTab === 'reguler'" class="space-y-8">
                    <div
                        v-for="booking in bookings.data"
                    :key="booking.id"
                    class="group relative bg-white dark:bg-[#111] rounded-[2.5rem] p-6 md:p-8 border border-gray-100 dark:border-white/5 shadow-sm hover:shadow-2xl hover:shadow-rose-600/10 transition-all duration-500"
                >
                    <div
                        class="flex flex-col md:flex-row justify-between items-start md:items-center gap-8"
                    >
                        <!-- Route & Time Flow -->
                        <div class="flex-grow">
                            <div class="flex items-center gap-3 mb-6">
                                <span
                                    class="text-[10px] font-black px-3 py-1 rounded-lg bg-gray-50 dark:bg-white/5 text-gray-400 dark:text-gray-500 uppercase tracking-widest font-unbounded border border-gray-100 dark:border-white/10"
                                >
                                    {{ booking.booking_code }}
                                </span>

                                <span
                                    v-if="booking.payment_status === 'paid'"
                                    class="text-[10px] font-black px-3 py-1 rounded-lg bg-emerald-50 dark:bg-emerald-900/10 text-emerald-600 dark:text-emerald-400 uppercase tracking-widest font-unbounded border border-emerald-100 dark:border-emerald-900/20"
                                >
                                    Lunas
                                </span>
                                <span
                                    v-else
                                    class="text-[10px] font-black px-3 py-1 rounded-lg bg-amber-50 dark:bg-amber-900/10 text-amber-600 dark:text-amber-400 uppercase tracking-widest font-unbounded border border-amber-100 dark:border-amber-900/20"
                                >
                                    {{
                                        booking.payment_status === "pending"
                                            ? "Menunggu"
                                            : booking.payment_status
                                    }}
                                </span>
                            </div>

                            <div
                                class="flex items-center gap-4 md:gap-6 mb-6 overflow-hidden"
                            >
                                <div
                                    class="text-lg md:text-2xl font-black text-gray-900 dark:text-white font-unbounded leading-none truncate"
                                >
                                    {{ booking.schedule?.route?.origin }}
                                </div>
                                <div
                                    class="flex flex-col items-center justify-center flex-shrink-0 w-8 md:w-12"
                                >
                                    <div
                                        class="h-[2px] w-full bg-rose-600/20 relative"
                                    >
                                        <div
                                            class="absolute right-0 -top-[3px] w-2 h-2 rounded-full bg-rose-600 shadow-[0_0_10px_rgba(225,29,72,0.8)]"
                                        ></div>
                                    </div>
                                </div>
                                <div
                                    class="text-lg md:text-2xl font-black text-gray-900 dark:text-white font-unbounded leading-none text-right truncate"
                                >
                                    {{ booking.schedule?.route?.destination }}
                                </div>
                            </div>

                            <div
                                class="flex flex-wrap items-center gap-6 text-sm text-gray-400 font-manrope font-bold"
                            >
                                <div class="flex items-center gap-2">
                                    <i
                                        class="far fa-calendar-alt text-rose-600"
                                    ></i>
                                    {{ formatDate(booking.booking_date) }}
                                </div>
                                <div class="flex items-center gap-2">
                                    <i class="far fa-clock text-rose-600"></i>
                                    {{
                                        formatTime(
                                            booking.schedule?.departure_time
                                        )
                                    }}
                                    WIB
                                </div>
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-bus text-rose-600"></i>
                                    {{
                                        booking.schedule?.bus?.name ||
                                        "Armada Utama"
                                    }}
                                </div>
                            </div>
                        </div>

                        <!-- Price & Action -->
                        <div
                            class="flex flex-col items-end md:border-l md:border-gray-100 md:dark:border-white/5 md:pl-10 w-full md:w-auto"
                        >
                            <div
                                class="text-xs font-bold text-gray-400 uppercase tracking-widest font-unbounded mb-1"
                            >
                                Total Bayar
                            </div>
                            <div
                                class="text-2xl md:text-3xl font-black text-rose-600 font-unbounded mb-6"
                            >
                                {{ formatCurrency(booking.total_price) }}
                            </div>
                            <Link
                                :href="
                                    route('booking-history.show', booking.id)
                                "
                                class="w-full md:w-auto py-3 px-8 bg-black dark:bg-white text-white dark:text-black font-black font-unbounded text-xs rounded-xl hover:bg-rose-600 hover:text-white dark:hover:bg-rose-600 dark:hover:text-white transition-all duration-300 text-center"
                            >
                                Detail Tiket
                            </Link>
                        </div>
                    </div>
                </div>
                </div>

                <!-- Charter List -->
                <div v-if="activeTab === 'charter'" class="space-y-8">
                    <div
                        v-for="charter in charter_bookings.data"
                        :key="charter.id"
                        class="group relative bg-white dark:bg-[#111] rounded-[2.5rem] p-6 md:p-8 border border-gray-100 dark:border-white/5 shadow-sm hover:shadow-2xl hover:shadow-rose-600/10 transition-all duration-500"
                    >
                        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-8">
                            <div class="flex-grow">
                                <div class="flex items-center gap-3 mb-6">
                                    <span class="text-[10px] font-black px-3 py-1 rounded-lg bg-gray-50 dark:bg-white/5 text-gray-400 dark:text-gray-500 uppercase tracking-widest font-unbounded border border-gray-100 dark:border-white/10">
                                        {{ charter.charter_code }}
                                    </span>
                                    <span
                                        class="text-[10px] font-black px-3 py-1 rounded-lg uppercase tracking-widest font-unbounded border"
                                        :class="getStatusBadge(charter.status)"
                                    >
                                        {{ charter.status }}
                                    </span>
                                </div>

                                <div class="flex items-center gap-4 md:gap-6 mb-6 overflow-hidden">
                                    <div class="text-lg md:text-2xl font-black text-gray-900 dark:text-white font-unbounded leading-none truncate">
                                        {{ charter.pickup_location }}
                                    </div>
                                    <div class="flex flex-col items-center justify-center flex-shrink-0 w-8 md:w-12">
                                        <div class="h-[2px] w-full bg-rose-600/20 relative">
                                            <div class="absolute right-0 -top-[3px] w-2 h-2 rounded-full bg-rose-600 shadow-[0_0_10px_rgba(225,29,72,0.8)]"></div>
                                        </div>
                                    </div>
                                    <div class="text-lg md:text-2xl font-black text-gray-900 dark:text-white font-unbounded leading-none text-right truncate">
                                        {{ charter.destination }}
                                    </div>
                                </div>

                                <div class="flex flex-wrap items-center gap-6 text-sm text-gray-400 font-manrope font-bold">
                                    <div class="flex items-center gap-2">
                                        <i class="far fa-calendar-alt text-rose-600"></i>
                                        {{ formatDate(charter.pickup_date) }} - {{ formatDate(charter.return_date) }}
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <i class="fas fa-bus text-rose-600"></i>
                                        {{ charter.bus_type_requested }}
                                    </div>
                                </div>
                            </div>

                            <div class="flex flex-col items-end md:border-l md:border-gray-100 md:dark:border-white/5 md:pl-10 w-full md:w-auto">
                                <div class="text-xs font-bold text-gray-400 uppercase tracking-widest font-unbounded mb-1">
                                    Total / DP
                                </div>
                                <div class="text-xl md:text-2xl font-black text-rose-600 font-unbounded mb-1">
                                    {{ charter.total_price > 0 ? formatCurrency(charter.total_price) : 'Menunggu Harga' }}
                                </div>
                                <div class="text-xs text-gray-500 mb-6" v-if="charter.down_payment > 0">
                                    DP: {{ formatCurrency(charter.down_payment) }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pagination -->
            <div
                v-if="bookings.next_page_url || bookings.prev_page_url"
                class="mt-16 flex justify-center gap-4"
            >
                <Link
                    v-if="bookings.prev_page_url"
                    :href="bookings.prev_page_url"
                    class="px-8 py-4 bg-white dark:bg-[#111] rounded-2xl border border-gray-100 dark:border-white/5 text-gray-600 dark:text-gray-300 font-black font-unbounded text-xs hover:border-rose-600 transition-all shadow-xl"
                >
                    <i class="fas fa-chevron-left mr-2"></i> Sebelumnya
                </Link>
                <Link
                    v-if="bookings.next_page_url"
                    :href="bookings.next_page_url"
                    class="px-8 py-4 bg-white dark:bg-[#111] rounded-2xl border border-gray-100 dark:border-white/5 text-gray-600 dark:text-gray-300 font-black font-unbounded text-xs hover:border-rose-600 transition-all shadow-xl"
                >
                    Selanjutnya <i class="fas fa-chevron-right ml-2"></i>
                </Link>
            </div>
        </div>
    </div>
</template>
