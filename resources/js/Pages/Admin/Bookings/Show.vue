<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { Head, Link } from "@inertiajs/vue3";
import SeatMap from "@/Components/SeatMap.vue";

const props = defineProps({
    booking: Object,
    occupiedSeats: Array,
});

const formatCurrency = (value) => {
    return new Intl.NumberFormat("id-ID", {
        style: "currency",
        currency: "IDR",
        minimumFractionDigits: 0,
    }).format(value);
};

const formatDate = (dateString, includeTime = false) => {
    if (!dateString) return "-";
    const options = {
        year: "numeric",
        month: "long",
        day: "numeric",
        ...(includeTime && { hour: "2-digit", minute: "2-digit" }),
    };
    return new Date(dateString).toLocaleDateString("id-ID", options);
};

const getStatusBadgeClass = (status) => {
    switch (status) {
        case "confirmed":
        case "completed":
        case "paid":
            return "bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400";
        case "pending":
            return "bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400";
        case "cancelled":
        case "failed":
            return "bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400";
        case "refunded":
            return "bg-gray-100 text-gray-700 dark:bg-gray-900/30 dark:text-gray-400";
        default:
            return "bg-gray-100 text-gray-700";
    }
};

const translateStatus = (status) => {
    const map = {
        pending: "Tertunda",
        paid: "Lunas",
        failed: "Gagal",
        refunded: "Dikembalikan",
        confirmed: "Dikonfirmasi",
        cancelled: "Dibatalkan",
        completed: "Selesai",
    };
    return map[status] || status;
};
</script>

<template>
    <Head :title="`Detail Pemesanan #${booking.booking_code}`" />

    <AdminLayout title="Detail Pemesanan">
        <div class="max-w-4xl mx-auto">
            <!-- Header -->
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h2
                        class="text-2xl font-bold text-gray-900 dark:text-white font-serif flex items-center gap-3"
                    >
                        <span>#{{ booking.booking_code }}</span>
                        <span
                            :class="[
                                'px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wide',
                                getStatusBadgeClass(booking.booking_status),
                            ]"
                        >
                            {{ translateStatus(booking.booking_status) }}
                        </span>
                    </h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                        Dibuat pada {{ formatDate(booking.created_at, true) }}
                    </p>
                </div>
                <div class="flex gap-3">
                    <Link
                        :href="route('admin.bookings.index')"
                        class="px-5 py-2.5 rounded-xl bg-gray-100 text-gray-700 font-semibold hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700 transition-all duration-300 flex items-center gap-2"
                    >
                        <i class="fas fa-arrow-left"></i>
                        <span>Kembali</span>
                    </Link>
                    <Link
                        :href="route('admin.bookings.edit', booking.id)"
                        class="px-5 py-2.5 rounded-xl bg-blue-600 text-white font-semibold hover:bg-blue-700 transition-all duration-300 flex items-center gap-2"
                        v-if="booking.booking_status !== 'cancelled'"
                    >
                        <i class="fas fa-edit"></i>
                        <span>Edit</span>
                    </Link>
                </div>
            </div>

            <!-- Content Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Passenger Info -->
                <div
                    class="bg-white dark:bg-gray-800 rounded-3xl p-8 shadow-xl shadow-gray-100/50 dark:shadow-black/30 border border-gray-100 dark:border-gray-700/50"
                >
                    <h3
                        class="text-lg font-bold text-gray-900 dark:text-white mb-6 flex items-center gap-2"
                    >
                        <i class="fas fa-user-circle text-brand-red"></i>
                        Informasi Penumpang
                    </h3>
                    <div class="space-y-4">
                        <div>
                            <p
                                class="text-xs text-gray-500 uppercase tracking-wider font-semibold"
                            >
                                Nama Lengkap
                            </p>
                            <p
                                class="text-gray-900 dark:text-white font-medium text-lg"
                            >
                                {{ booking.passenger_name }}
                            </p>
                        </div>
                        <div>
                            <p
                                class="text-xs text-gray-500 uppercase tracking-wider font-semibold"
                            >
                                Nomor Telepon
                            </p>
                            <p
                                class="text-gray-900 dark:text-white font-medium"
                            >
                                {{ booking.passenger_phone }}
                            </p>
                        </div>
                        <div>
                            <p
                                class="text-xs text-gray-500 uppercase tracking-wider font-semibold"
                            >
                                Email
                            </p>
                            <p
                                class="text-gray-900 dark:text-white font-medium"
                            >
                                {{ booking.passenger_email }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Payment Info -->
                <div
                    class="bg-white dark:bg-gray-800 rounded-3xl p-8 shadow-xl shadow-gray-100/50 dark:shadow-black/30 border border-gray-100 dark:border-gray-700/50"
                >
                    <h3
                        class="text-lg font-bold text-gray-900 dark:text-white mb-6 flex items-center gap-2"
                    >
                        <i class="fas fa-receipt text-brand-red"></i>
                        Informasi Pembayaran
                    </h3>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <p
                                class="text-xs text-gray-500 uppercase tracking-wider font-semibold"
                            >
                                Total Tagihan
                            </p>
                            <p class="text-brand-red font-bold text-xl">
                                {{ formatCurrency(booking.total_price) }}
                            </p>
                        </div>
                        <div class="flex justify-between items-center">
                            <p
                                class="text-xs text-gray-500 uppercase tracking-wider font-semibold"
                            >
                                Status Pembayaran
                            </p>
                            <span
                                :class="[
                                    'px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wide',
                                    getStatusBadgeClass(booking.payment_status),
                                ]"
                            >
                                {{ translateStatus(booking.payment_status) }}
                            </span>
                        </div>
                        <div v-if="booking.payment_started_at">
                            <p
                                class="text-xs text-gray-500 uppercase tracking-wider font-semibold"
                            >
                                Waktu Pembayaran
                            </p>
                            <p class="text-gray-700 dark:text-gray-300 text-sm">
                                {{
                                    formatDate(booking.payment_started_at, true)
                                }}
                            </p>
                        </div>
                        <div
                            v-if="booking.midtrans_transaction_id"
                            class="pt-2 border-t border-gray-100 dark:border-gray-700"
                        >
                            <p
                                class="text-xs text-gray-500 uppercase tracking-wider font-semibold"
                            >
                                Transaction ID
                            </p>
                            <p
                                class="text-gray-600 dark:text-gray-400 font-mono text-xs"
                            >
                                {{ booking.midtrans_transaction_id }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Trip Details (Full Width) -->
                <div
                    class="col-span-1 md:col-span-2 bg-white dark:bg-gray-800 rounded-3xl p-8 shadow-xl shadow-gray-100/50 dark:shadow-black/30 border border-gray-100 dark:border-gray-700/50"
                >
                    <h3
                        class="text-lg font-bold text-gray-900 dark:text-white mb-6 flex items-center gap-2"
                    >
                        <i class="fas fa-bus-alt text-brand-red"></i>
                        Detail Perjalanan
                    </h3>

                    <div class="flex flex-col md:flex-row gap-8 items-start">
                        <!-- Route & Time -->
                        <div class="flex-1 space-y-6">
                            <div class="flex items-center gap-4">
                                <div class="flex flex-col items-center">
                                    <div
                                        class="w-3 h-3 rounded-full bg-brand-red"
                                    ></div>
                                    <div
                                        class="w-0.5 h-12 bg-gray-200 dark:bg-gray-700 my-1"
                                    ></div>
                                    <div
                                        class="w-3 h-3 rounded-full bg-brand-red"
                                    ></div>
                                </div>
                                <div class="space-y-6">
                                    <div>
                                        <p
                                            class="text-lg font-bold text-gray-900 dark:text-white"
                                        >
                                            {{ booking.schedule.route.origin }}
                                        </p>
                                        <p class="text-sm text-gray-500">
                                            Berangkat
                                        </p>
                                    </div>
                                    <div>
                                        <p
                                            class="text-lg font-bold text-gray-900 dark:text-white"
                                        >
                                            {{
                                                booking.schedule.route
                                                    .destination
                                            }}
                                        </p>
                                        <p class="text-sm text-gray-500">
                                            Tujuan
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <p
                                    class="text-xs text-gray-500 uppercase tracking-wider font-semibold mb-1"
                                >
                                    Waktu Keberangkatan
                                </p>
                                <p
                                    class="text-xl font-bold text-gray-900 dark:text-white"
                                >
                                    {{
                                        formatDate(booking.departure_time, true)
                                    }}
                                    WIB
                                </p>
                            </div>
                        </div>

                        <!-- Bus & Seats -->
                        <div class="flex-1 space-y-6">
                            <div>
                                <p
                                    class="text-xs text-gray-500 uppercase tracking-wider font-semibold mb-1"
                                >
                                    Armada
                                </p>
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-12 h-12 rounded-xl bg-gray-100 dark:bg-gray-700 flex items-center justify-center"
                                    >
                                        <i
                                            class="fas fa-bus text-gray-600 dark:text-gray-300 text-xl"
                                        ></i>
                                    </div>
                                    <div>
                                        <p
                                            class="font-bold text-gray-900 dark:text-white"
                                        >
                                            {{ booking.schedule.bus.name }}
                                        </p>
                                        <p class="text-sm text-gray-500">
                                            {{ booking.schedule.bus.bus_type }}
                                            Class
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <p
                                    class="text-xs text-gray-500 uppercase tracking-wider font-semibold mb-2"
                                >
                                    Kursi({{ booking.number_of_seats }})
                                </p>
                                <div class="flex flex-wrap gap-2">
                                    <span
                                        v-for="seat in booking.seat_numbers.split(
                                            ','
                                        )"
                                        :key="seat"
                                        class="w-10 h-10 rounded-lg bg-brand-red text-white font-bold flex items-center justify-center shadow-md shadow-brand-red/20"
                                    >
                                        {{ seat }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Seat Map Visualization -->
            <div
                class="bg-white dark:bg-gray-800 rounded-3xl p-8 shadow-xl shadow-gray-100/50 dark:shadow-black/30 border border-gray-100 dark:border-gray-700/50 mt-8"
            >
                <h3
                    class="text-lg font-black text-gray-900 dark:text-white font-unbounded mb-6"
                >
                    Peta Kursi
                </h3>
                <SeatMap
                    :bus-capacity="booking.schedule.bus.capacity"
                    :occupied-seats="occupiedSeats"
                    :selected-seats="
                        booking.seat_numbers
                            .split(',')
                            .map((s) => parseInt(s.trim()))
                    "
                    mode="view"
                />
            </div>
        </div>
    </AdminLayout>
</template>
