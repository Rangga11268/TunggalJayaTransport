<script setup>
import { Head, Link } from "@inertiajs/vue3";
import FrontendLayout from "@/Layouts/FrontendLayout.vue";

defineOptions({ layout: FrontendLayout });

const props = defineProps({
    bookings: Object,
});

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
    // Parse the date string (handles both 2000-01-01T14:00:00... and "14:00:00")
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
</script>

<template>
    <Head title="Riwayat Pemesanan" />

    <!-- Clean Title Section -->
    <div class="pt-24 pb-12 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto text-center">
        <span
            class="inline-block px-4 py-1.5 rounded-full bg-primary-50 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400 text-xs font-bold tracking-widest mb-6 animate-fade-in uppercase"
        >
            RIWAYAT PERJALANAN
        </span>
        <h1
            class="text-4xl md:text-5xl lg:text-6xl font-black font-serif text-gray-900 dark:text-white mb-6 animate-fade-in-up"
        >
            Riwayat
            <span
                class="text-transparent bg-clip-text bg-gradient-to-r from-brand-red to-orange-500"
                >Pemesanan</span
            >
        </h1>
        <p
            class="text-lg text-gray-500 dark:text-gray-400 max-w-2xl mx-auto animate-fade-in-up stagger-1"
        >
            Lihat semua riwayat perjalanan dan status tiket Anda bersama TUJAGO.
        </p>
    </div>

    <div class="bg-gray-50 dark:bg-gray-950 min-h-screen py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Empty State -->
            <div
                v-if="bookings.data.length === 0"
                class="bg-white dark:bg-gray-900 rounded-3xl p-12 text-center shadow-lg border border-gray-100 dark:border-gray-800"
            >
                <div
                    class="w-24 h-24 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center mx-auto mb-6"
                >
                    <i
                        class="fas fa-ticket-alt text-4xl text-gray-300 dark:text-gray-600"
                    ></i>
                </div>
                <h3
                    class="text-xl font-bold text-gray-900 dark:text-white mb-2"
                >
                    Belum ada riwayat pemesanan
                </h3>
                <p class="text-gray-500 mb-8 max-w-md mx-auto">
                    Anda belum melakukan pemesanan tiket apapun. Mulai
                    petualangan Anda bersama TUJAGO sekarang!
                </p>
                <Link
                    :href="route('booking.index')"
                    class="btn-premium px-8 py-3 inline-flex items-center"
                >
                    Pesan Tiket Sekarang
                </Link>
            </div>

            <!-- Booking List -->
            <div v-else class="space-y-6">
                <div
                    v-for="booking in bookings.data"
                    :key="booking.id"
                    class="bg-white dark:bg-gray-900 rounded-2xl p-6 shadow-md border border-gray-100 dark:border-gray-800 hover:shadow-xl transition-shadow duration-300 group"
                >
                    <div class="flex flex-col md:flex-row justify-between">
                        <!-- Left Info -->
                        <div class="mb-4 md:mb-0">
                            <div class="flex items-center mb-3">
                                <span
                                    class="text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-400 mr-3"
                                >
                                    {{ booking.booking_code }}
                                </span>
                                <span
                                    v-if="booking.payment_status === 'paid'"
                                    class="text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400"
                                >
                                    Lunas
                                </span>
                                <span
                                    v-else
                                    class="text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400"
                                >
                                    {{
                                        booking.payment_status === "pending"
                                            ? "Menunggu Pembayaran"
                                            : booking.payment_status
                                    }}
                                </span>
                            </div>

                            <div class="flex items-center mb-2">
                                <div
                                    class="text-lg font-bold text-gray-900 dark:text-white"
                                >
                                    {{ booking.schedule?.route?.origin }}
                                </div>
                                <i
                                    class="fas fa-arrow-right mx-3 text-gray-400 text-sm"
                                ></i>
                                <div
                                    class="text-lg font-bold text-gray-900 dark:text-white"
                                >
                                    {{ booking.schedule?.route?.destination }}
                                </div>
                            </div>

                            <div
                                class="text-sm text-gray-500 flex items-center space-x-4"
                            >
                                <span>
                                    <i class="far fa-calendar mr-2"></i>
                                    {{ formatDate(booking.booking_date) }}
                                </span>
                                <span>
                                    <i class="far fa-clock mr-2"></i>
                                    {{
                                        formatTime(
                                            booking.schedule?.departure_time
                                        )
                                    }}
                                    WIB
                                </span>
                            </div>
                        </div>

                        <!-- Right Info & Action -->
                        <div class="flex flex-col items-end justify-center">
                            <div class="text-xl font-black text-brand-red mb-4">
                                {{ formatCurrency(booking.total_price) }}
                            </div>
                            <Link
                                :href="
                                    route('booking-history.show', booking.id)
                                "
                                class="btn-secondary text-sm px-6 py-2 rounded-xl group-hover:bg-brand-red group-hover:text-white transition-colors"
                            >
                                Lihat Detail
                            </Link>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pagination -->
            <div
                v-if="bookings.next_page_url || bookings.prev_page_url"
                class="mt-8 flex justify-center space-x-4"
            >
                <Link
                    v-if="bookings.prev_page_url"
                    :href="bookings.prev_page_url"
                    class="px-4 py-2 bg-white dark:bg-gray-800 rounded-lg shadow text-gray-700 dark:text-gray-200 hover:bg-gray-50"
                >
                    Previous
                </Link>
                <Link
                    v-if="bookings.next_page_url"
                    :href="bookings.next_page_url"
                    class="px-4 py-2 bg-white dark:bg-gray-800 rounded-lg shadow text-gray-700 dark:text-gray-200 hover:bg-gray-50"
                >
                    Next
                </Link>
            </div>
        </div>
    </div>
</template>
