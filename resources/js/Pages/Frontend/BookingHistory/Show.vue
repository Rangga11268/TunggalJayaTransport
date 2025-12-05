<script setup>
import { Head, Link } from "@inertiajs/vue3";
import FrontendLayout from "@/Layouts/FrontendLayout.vue";

defineOptions({ layout: FrontendLayout });

const props = defineProps({
    booking: Object,
});

const formatDate = (dateString) => {
    return new Date(dateString).toLocaleDateString("id-ID", {
        weekday: "long",
        day: "numeric",
        month: "long",
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
        // Fallback if it's just a time string "14:00:00" that Date() might fail on depending on browser
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
    <Head :title="`Detail Booking ${booking.booking_code}`" />

    <div
        class="bg-gray-50 dark:bg-gray-950 min-h-screen py-12 px-4 sm:px-6 lg:px-8 flex items-center justify-center"
    >
        <div class="max-w-3xl w-full">
            <div
                class="bg-white dark:bg-gray-900 rounded-3xl p-8 md:p-12 shadow-2xl shadow-black/5 border border-gray-100 dark:border-gray-800 text-center animate-fade-in-up"
            >
                <!-- Back Link -->
                <div class="text-left mb-6">
                    <Link
                        :href="route('booking-history.index')"
                        class="text-gray-500 hover:text-brand-red transition-colors flex items-center"
                    >
                        <i class="fas fa-arrow-left mr-2"></i> Kembali ke
                        Riwayat
                    </Link>
                </div>

                <!-- Header -->
                <div class="mb-8">
                    <div
                        class="w-20 h-20 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center mx-auto mb-6"
                    >
                        <i
                            class="fas fa-file-invoice text-3xl text-blue-600 dark:text-blue-400"
                        ></i>
                    </div>
                    <h1
                        class="text-3xl font-black text-gray-900 dark:text-white mb-2"
                    >
                        Detail Pemesanan
                    </h1>
                    <p class="text-gray-500">
                        Informasi lengkap perjalanan Anda.
                    </p>
                </div>

                <!-- Booking Details Card -->
                <div
                    class="bg-gray-50 dark:bg-gray-800 rounded-2xl p-6 md:p-8 text-left border border-gray-200 dark:border-gray-700 mb-8"
                >
                    <div
                        class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 pb-6 border-b border-gray-200 dark:border-gray-700"
                    >
                        <div>
                            <p class="text-sm text-gray-500 mb-1">
                                Kode Booking
                            </p>
                            <p
                                class="text-2xl font-black text-brand-red tracking-wider"
                            >
                                {{ booking.booking_code }}
                            </p>
                        </div>
                        <div class="mt-4 md:mt-0 text-right">
                            <span
                                v-if="booking.payment_status === 'paid'"
                                class="inline-flex items-center px-4 py-2 rounded-full text-sm font-bold bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-300"
                            >
                                <i class="fas fa-check-circle mr-2"></i> Lunas
                            </span>
                            <span
                                v-else
                                class="inline-flex items-center px-4 py-2 rounded-full text-sm font-bold bg-yellow-100 text-yellow-800 dark:bg-yellow-900/50 dark:text-yellow-300"
                            >
                                <i class="fas fa-clock mr-2"></i>
                                {{ booking.payment_status }}
                            </span>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3
                                class="font-bold text-gray-900 dark:text-white mb-4 flex items-center"
                            >
                                <i class="fas fa-route text-brand-red mr-3"></i>
                                Detail Perjalanan
                            </h3>
                            <div class="space-y-3 pl-7">
                                <div>
                                    <p class="text-sm text-gray-500">Rute</p>
                                    <p class="font-medium dark:text-gray-200">
                                        {{
                                            booking.schedule?.route?.origin ||
                                            "-"
                                        }}
                                        <i
                                            class="fas fa-arrow-right mx-2 text-xs text-gray-400"
                                        ></i>
                                        {{
                                            booking.schedule?.route
                                                ?.destination || "-"
                                        }}
                                    </p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Tanggal</p>
                                    <p class="font-medium dark:text-gray-200">
                                        {{ formatDate(booking.booking_date) }}
                                    </p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Bus</p>
                                    <p class="font-medium dark:text-gray-200">
                                        {{ booking.schedule?.bus?.name || "-" }}
                                    </p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">
                                        Waktu Keberangkatan
                                    </p>
                                    <p class="font-medium dark:text-gray-200">
                                        {{
                                            formatTime(
                                                booking.schedule?.departure_time
                                            )
                                        }}
                                        WIB
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div>
                            <h3
                                class="font-bold text-gray-900 dark:text-white mb-4 flex items-center"
                            >
                                <i
                                    class="fas fa-user-circle text-brand-red mr-3"
                                ></i>
                                Detail Penumpang
                            </h3>
                            <div class="space-y-3 pl-7">
                                <div>
                                    <p class="text-sm text-gray-500">Nama</p>
                                    <p class="font-medium dark:text-gray-200">
                                        {{ booking.passenger_name }}
                                    </p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Kursi</p>
                                    <p class="font-medium dark:text-gray-200">
                                        {{ booking.seat_numbers }}
                                    </p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">
                                        Total Harga
                                    </p>
                                    <p class="font-bold text-lg text-brand-red">
                                        {{
                                            formatCurrency(booking.total_price)
                                        }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a
                        v-if="booking.payment_status === 'paid'"
                        :href="
                            route(
                                'frontend.booking.download-ticket',
                                booking.id
                            )
                        "
                        class="btn-premium px-8 py-4 w-full sm:w-auto text-center flex items-center justify-center"
                        target="_blank"
                    >
                        <i class="fas fa-download mr-2"></i> Unduh Tiket
                    </a>

                    <!-- Only show Pay button if Pending and using Midtrans -->
                    <Link
                        v-if="
                            booking.payment_status === 'pending' &&
                            booking.midtrans_transaction_id
                        "
                        :href="
                            route('frontend.booking.confirmation', booking.id)
                        "
                        class="btn-premium px-8 py-4 w-full sm:w-auto text-center"
                    >
                        <i class="fas fa-credit-card mr-2"></i> Lanjutkan
                        Pembayaran
                    </Link>
                </div>
            </div>
        </div>
    </div>
</template>
