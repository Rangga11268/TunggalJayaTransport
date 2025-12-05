<script setup>
import { Head, Link } from "@inertiajs/vue3";
import FrontendLayout from "@/Layouts/FrontendLayout.vue";
import { onMounted } from "vue";

defineOptions({ layout: FrontendLayout });

const props = defineProps({
    booking: Object,
});

console.log("Booking Data:", props.booking);

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
</script>

<template>
    <Head title="Pemesanan Berhasil" />

    <div
        class="bg-gray-50 dark:bg-gray-950 min-h-screen py-12 px-4 sm:px-6 lg:px-8 flex items-center justify-center"
    >
        <div class="max-w-3xl w-full">
            <div
                class="bg-white dark:bg-gray-900 rounded-3xl p-8 md:p-12 shadow-2xl shadow-black/5 border border-gray-100 dark:border-gray-800 text-center animate-fade-in-up"
            >
                <!-- Icon -->
                <div class="mb-8">
                    <div
                        class="w-24 h-24 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center mx-auto mb-6"
                    >
                        <i
                            class="fas fa-check text-4xl text-green-600 dark:text-green-400"
                        ></i>
                    </div>
                    <h1
                        class="text-3xl md:text-4xl font-black text-gray-900 dark:text-white mb-4"
                    >
                        Pemesanan Berhasil!
                    </h1>
                    <p class="text-gray-500 text-lg">
                        Terima kasih telah melakukan pemesanan tiket di Tunggal
                        Jaya Transport.
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
                                <i class="fas fa-clock mr-2"></i> Menunggu
                                Pembayaran
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
                                        ({{
                                            booking.schedule?.bus
                                                ?.plate_number || "-"
                                        }})
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
                    <Link
                        :href="route('frontend.home')"
                        class="btn-premium px-8 py-4 w-full sm:w-auto text-center"
                    >
                        <i class="fas fa-home mr-2"></i> Kembali ke Beranda
                    </Link>
                    <a
                        :href="
                            route(
                                'frontend.booking.download-ticket',
                                booking.id
                            )
                        "
                        class="btn-secondary px-8 py-4 w-full sm:w-auto text-center flex items-center justify-center"
                        target="_blank"
                    >
                        <i class="fas fa-download mr-2"></i> Unduh Tiket
                    </a>
                </div>
            </div>
        </div>
    </div>
</template>
