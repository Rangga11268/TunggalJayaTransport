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

    <div class="min-h-screen bg-[#fcf9f8] pb-32">
        <!-- Header -->
        <div class="pt-28 pb-8 px-4 sm:px-6 lg:px-8 text-center">
            <span class="inline-block px-4 py-1.5 rounded-full bg-white border border-[#ebe7e7] text-[#10207a] text-[11px] font-bold tracking-widest uppercase mb-5 shadow-sm">
                Dashboard Penumpang
            </span>
            <h1 class="font-unbounded font-black text-4xl md:text-5xl text-[#1c1b1b] mb-3">Riwayat Perjalanan</h1>
            <p class="text-[#454652] text-[16px] max-w-xl mx-auto">Pantau status tiket dan kelola riwayat perjalanan Anda di satu tempat.</p>
        </div>

        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Stats Summary -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                <div class="bg-white border border-[#ebe7e7] rounded-[12px] p-5 shadow-sm text-center">
                    <div class="text-2xl font-bold text-[#1c1b1b] font-unbounded">{{ bookings.data?.length || 0 }}</div>
                    <div class="text-[11px] text-[#454652] uppercase tracking-wider font-semibold mt-1">Total Tiket</div>
                </div>
                <div class="bg-white border border-[#ebe7e7] rounded-[12px] p-5 shadow-sm text-center">
                    <div class="text-2xl font-bold text-emerald-600 font-unbounded">{{ bookings.data?.filter(b => b.payment_status === 'paid').length || 0 }}</div>
                    <div class="text-[11px] text-[#454652] uppercase tracking-wider font-semibold mt-1">Lunas</div>
                </div>
                <div class="bg-white border border-[#ebe7e7] rounded-[12px] p-5 shadow-sm text-center">
                    <div class="text-2xl font-bold text-amber-600 font-unbounded">{{ bookings.data?.filter(b => b.payment_status === 'pending').length || 0 }}</div>
                    <div class="text-[11px] text-[#454652] uppercase tracking-wider font-semibold mt-1">Menunggu</div>
                </div>
                <div class="bg-white border border-[#ebe7e7] rounded-[12px] p-5 shadow-sm text-center">
                    <div class="text-2xl font-bold text-[#10207a] font-unbounded">{{ charter_bookings?.data?.length || 0 }}</div>
                    <div class="text-[11px] text-[#454652] uppercase tracking-wider font-semibold mt-1">Sewa Bus</div>
                </div>
            </div>

            <!-- Tabs -->
            <div class="flex gap-2 mb-8">
                <button @click="activeTab = 'reguler'"
                    class="px-5 py-2.5 rounded-xl text-xs font-bold uppercase tracking-wider transition-all border"
                    :class="activeTab === 'reguler' ? 'bg-[#10207a] text-white border-[#10207a] shadow-sm' : 'bg-white text-gray-600 border-gray-200 hover:border-gray-400'">
                    Tiket Reguler
                </button>
                <button @click="activeTab = 'charter'"
                    class="px-5 py-2.5 rounded-xl text-xs font-bold uppercase tracking-wider transition-all border"
                    :class="activeTab === 'charter' ? 'bg-[#10207a] text-white border-[#10207a] shadow-sm' : 'bg-white text-gray-600 border-gray-200 hover:border-gray-400'">
                    Sewa Pariwisata
                </button>
            </div>

            <!-- Empty State -->
            <div v-if="(activeTab === 'reguler' && (!bookings.data || bookings.data.length === 0)) || (activeTab === 'charter' && (!charter_bookings?.data || charter_bookings.data.length === 0))"
                class="bg-white border border-[#ebe7e7] rounded-[12px] p-16 text-center shadow-sm">
                <div class="w-20 h-20 bg-[#f6f3f2] rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="text-3xl text-gray-300" :class="activeTab === 'reguler' ? 'fas fa-ticket-alt' : 'fas fa-umbrella-beach'"></i>
                </div>
                <h3 class="text-xl font-bold text-[#1c1b1b] mb-3">Belum Ada {{ activeTab === 'reguler' ? 'Tiket' : 'Sewa Pariwisata' }}</h3>
                <p class="text-[#454652] text-sm mb-8">Mulai perjalanan baru sekarang!</p>
                <Link :href="activeTab === 'reguler' ? route('frontend.booking.index') : route('frontend.charter.index')"
                    class="inline-block px-8 py-3.5 bg-[#10207a] text-white rounded-[10px] font-bold text-[14px] hover:bg-[#0c185e] transition-all shadow-sm">
                    {{ activeTab === 'reguler' ? 'Pesan Tiket' : 'Sewa Bus Pariwisata' }}
                </Link>
            </div>

            <!-- Booking List -->
            <div v-else class="space-y-4">
                <!-- Reguler -->
                <div v-if="activeTab === 'reguler'" class="space-y-4">
                    <div v-for="booking in bookings.data" :key="booking.id"
                        class="bg-white border border-[#ebe7e7] rounded-[12px] p-5 md:p-6 shadow-sm hover:shadow-md transition-shadow">
                        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-5">
                            <div class="flex-grow w-full md:w-auto">
                                <!-- Top row: code + status -->
                                <div class="flex items-center gap-2.5 mb-4 flex-wrap">
                                    <span class="text-[10px] font-bold px-2.5 py-1 rounded-lg bg-[#f6f3f2] text-[#454652] uppercase tracking-wider border border-[#ebe7e7]">
                                        {{ booking.booking_code }}
                                    </span>
                                    <span v-if="booking.payment_status === 'paid'"
                                        class="text-[10px] font-bold px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-700 uppercase tracking-wider border border-emerald-200">
                                        Lunas
                                    </span>
                                    <span v-else
                                        class="text-[10px] font-bold px-2.5 py-1 rounded-lg bg-amber-50 text-amber-700 uppercase tracking-wider border border-amber-200">
                                        {{ booking.payment_status === 'pending' ? 'Menunggu' : booking.payment_status }}
                                    </span>
                                    <span class="text-[10px] font-bold px-2.5 py-1 rounded-lg uppercase tracking-wider border"
                                        :class="booking.booking_status === 'confirmed' ? 'bg-blue-50 text-blue-700 border-blue-200' : booking.booking_status === 'completed' ? 'bg-purple-50 text-purple-700 border-purple-200' : 'bg-gray-50 text-gray-600 border-gray-200'">
                                        {{ booking.booking_status }}
                                    </span>
                                </div>

                                <!-- Route -->
                                <div class="flex items-center gap-3 mb-4">
                                    <div class="font-bold text-[#1c1b1b] text-[18px] truncate">{{ booking.schedule?.route?.origin }}</div>
                                    <div class="flex flex-col items-center shrink-0">
                                        <div class="w-10 h-[2px] bg-[#10207a]/20 relative">
                                            <div class="absolute right-0 -top-[4px] w-[10px] h-[10px] rounded-full bg-[#10207a]"></div>
                                        </div>
                                    </div>
                                    <div class="font-bold text-[#1c1b1b] text-[18px] text-right truncate">{{ booking.schedule?.route?.destination }}</div>
                                </div>

                                <!-- Meta -->
                                <div class="flex flex-wrap items-center gap-4 text-[13px] text-[#454652]">
                                    <span class="flex items-center gap-1.5"><i class="far fa-calendar-alt text-[#10207a] text-[11px]"></i> {{ formatDate(booking.booking_date) }}</span>
                                    <span class="flex items-center gap-1.5"><i class="far fa-clock text-[#10207a] text-[11px]"></i> {{ formatTime(booking.schedule?.departure_time) }} WIB</span>
                                    <span class="flex items-center gap-1.5"><i class="fas fa-bus text-[#10207a] text-[11px]"></i> {{ booking.schedule?.bus?.name || 'Armada' }}</span>
                                    <span class="flex items-center gap-1.5"><i class="fas fa-chair text-[#10207a] text-[11px]"></i> {{ booking.seat_numbers || '-' }}</span>
                                </div>
                            </div>

                            <!-- Price & Action -->
                            <div class="flex flex-row md:flex-col items-center md:items-end justify-between md:border-l md:border-[#ebe7e7] md:pl-6 w-full md:w-auto gap-4 md:gap-3">
                                <div class="text-right">
                                    <div class="text-[10px] font-bold text-[#454652] uppercase tracking-wider">Total</div>
                                    <div class="text-xl font-bold text-[#10207a] font-unbounded">{{ formatCurrency(booking.total_price) }}</div>
                                </div>
                                <Link :href="route('booking-history.show', booking.id)"
                                    class="px-6 py-2.5 bg-[#10207a] text-white rounded-[10px] font-bold text-[12px] hover:bg-[#0c185e] transition-all shadow-sm text-center whitespace-nowrap">
                                    Detail
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Charter -->
                <div v-if="activeTab === 'charter'" class="space-y-4">
                    <div v-for="charter in charter_bookings.data" :key="charter.id"
                        class="bg-white border border-[#ebe7e7] rounded-[12px] p-5 md:p-6 shadow-sm hover:shadow-md transition-shadow">
                        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-5">
                            <div class="flex-grow w-full md:w-auto">
                                <div class="flex items-center gap-2.5 mb-4 flex-wrap">
                                    <span class="text-[10px] font-bold px-2.5 py-1 rounded-lg bg-[#f6f3f2] text-[#454652] uppercase tracking-wider border border-[#ebe7e7]">
                                        {{ charter.charter_code }}
                                    </span>
                                    <span class="text-[10px] font-bold px-2.5 py-1 rounded-lg uppercase tracking-wider border"
                                        :class="getStatusBadge(charter.status)">
                                        {{ charter.status }}
                                    </span>
                                </div>

                                <div class="flex items-center gap-3 mb-4">
                                    <div class="font-bold text-[#1c1b1b] text-[18px] truncate">{{ charter.pickup_location }}</div>
                                    <div class="flex flex-col items-center shrink-0">
                                        <div class="w-10 h-[2px] bg-[#10207a]/20 relative">
                                            <div class="absolute right-0 -top-[4px] w-[10px] h-[10px] rounded-full bg-[#10207a]"></div>
                                        </div>
                                    </div>
                                    <div class="font-bold text-[#1c1b1b] text-[18px] text-right truncate">{{ charter.destination }}</div>
                                </div>

                                <div class="flex flex-wrap items-center gap-4 text-[13px] text-[#454652]">
                                    <span class="flex items-center gap-1.5"><i class="far fa-calendar-alt text-[#10207a] text-[11px]"></i> {{ formatDate(charter.pickup_date) }} - {{ formatDate(charter.return_date) }}</span>
                                    <span class="flex items-center gap-1.5"><i class="fas fa-bus text-[#10207a] text-[11px]"></i> {{ charter.bus_type_requested }}</span>
                                </div>
                            </div>

                            <div class="flex flex-row md:flex-col items-center md:items-end justify-between md:border-l md:border-[#ebe7e7] md:pl-6 w-full md:w-auto gap-4 md:gap-3">
                                <div class="text-right">
                                    <div class="text-[10px] font-bold text-[#454652] uppercase tracking-wider">Total / DP</div>
                                    <div class="text-lg font-bold text-[#10207a] font-unbounded">{{ charter.total_price > 0 ? formatCurrency(charter.total_price) : 'Menunggu' }}</div>
                                    <div v-if="charter.down_payment > 0" class="text-[11px] text-[#454652]">DP: {{ formatCurrency(charter.down_payment) }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pagination -->
            <div v-if="activeTab === 'reguler' && (bookings.next_page_url || bookings.prev_page_url)"
                class="mt-10 flex justify-center gap-3">
                <Link v-if="bookings.prev_page_url" :href="bookings.prev_page_url"
                    class="px-6 py-3 bg-white border border-[#ebe7e7] rounded-[10px] text-[#454652] font-semibold text-[13px] hover:border-gray-300 transition-all shadow-sm">
                    <i class="fas fa-chevron-left mr-1.5"></i> Sebelumnya
                </Link>
                <Link v-if="bookings.next_page_url" :href="bookings.next_page_url"
                    class="px-6 py-3 bg-white border border-[#ebe7e7] rounded-[10px] text-[#454652] font-semibold text-[13px] hover:border-gray-300 transition-all shadow-sm">
                    Selanjutnya <i class="fas fa-chevron-right ml-1.5"></i>
                </Link>
            </div>
        </div>
    </div>
</template>
