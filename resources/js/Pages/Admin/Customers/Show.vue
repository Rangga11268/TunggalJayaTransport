<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { Head, Link } from "@inertiajs/vue3";

const props = defineProps({
    customer: Object,
    bookings: Object,
    routePreferences: Array,
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
    <Head :title="`Detail Pelanggan - ${customer.passenger_name}`" />

    <AdminLayout :title="`Detail Pelanggan - ${customer.passenger_name}`">
        <!-- Back Button -->
        <div class="mb-6">
            <Link
                :href="route('admin.customers.index')"
                class="inline-flex items-center gap-2 text-gray-600 dark:text-gray-400 hover:text-brand-red dark:hover:text-white transition-colors"
            >
                <i class="fas fa-arrow-left"></i>
                Kembali ke Daftar Pelanggan
            </Link>
        </div>

        <!-- Customer Profile Header -->
        <div
            class="bg-white dark:bg-gray-800 rounded-3xl p-8 shadow-xl shadow-gray-100/50 dark:shadow-black/30 border border-gray-100 dark:border-gray-700/50 mb-8"
        >
            <div class="flex items-start gap-6">
                <div
                    class="h-20 w-20 rounded-full bg-gradient-to-br from-brand-red to-red-800 flex items-center justify-center text-white font-black text-3xl shadow-lg shadow-brand-red/20"
                >
                    {{ customer.passenger_name.charAt(0).toUpperCase() }}
                </div>
                <div class="flex-1">
                    <h2
                        class="text-2xl font-black text-gray-900 dark:text-white font-unbounded"
                    >
                        {{ customer.passenger_name }}
                    </h2>
                    <div class="flex flex-wrap gap-4 mt-3 text-sm">
                        <div
                            class="flex items-center gap-2 text-gray-600 dark:text-gray-300"
                        >
                            <i class="fas fa-envelope"></i>
                            {{ customer.passenger_email }}
                        </div>
                        <div
                            class="flex items-center gap-2 text-gray-600 dark:text-gray-300"
                        >
                            <i class="fas fa-phone"></i>
                            {{ customer.passenger_phone }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats Grid -->
            <div
                class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-6 pt-6 border-t border-gray-200 dark:border-gray-700"
            >
                <div class="text-center">
                    <p
                        class="text-xs text-gray-500 uppercase font-bold tracking-wider mb-1"
                    >
                        Total Booking
                    </p>
                    <p
                        class="text-2xl font-black text-gray-900 dark:text-white font-unbounded"
                    >
                        {{ customer.total_bookings }}
                    </p>
                </div>
                <div class="text-center">
                    <p
                        class="text-xs text-gray-500 uppercase font-bold tracking-wider mb-1"
                    >
                        Total Pengeluaran
                    </p>
                    <p class="text-lg font-black text-brand-red font-unbounded">
                        {{ formatCurrency(customer.total_spent) }}
                    </p>
                </div>
                <div class="text-center">
                    <p
                        class="text-xs text-gray-500 uppercase font-bold tracking-wider mb-1"
                    >
                        Lunas
                    </p>
                    <p
                        class="text-2xl font-black text-green-600 dark:text-green-400 font-unbounded"
                    >
                        {{ customer.paid_bookings }}
                    </p>
                </div>
                <div class="text-center">
                    <p
                        class="text-xs text-gray-500 uppercase font-bold tracking-wider mb-1"
                    >
                        Pending/Dibatalkan
                    </p>
                    <p
                        class="text-2xl font-black text-gray-500 dark:text-gray-400 font-unbounded"
                    >
                        {{
                            customer.pending_bookings +
                            customer.cancelled_bookings
                        }}
                    </p>
                </div>
            </div>

            <!-- Timeline -->
            <div
                class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-6 pt-6 border-t border-gray-200 dark:border-gray-700"
            >
                <div>
                    <p
                        class="text-xs text-gray-500 uppercase font-bold tracking-wider mb-1"
                    >
                        Pelanggan Sejak
                    </p>
                    <p class="text-sm text-gray-700 dark:text-gray-300">
                        {{ formatDate(customer.first_booking_at) }}
                    </p>
                </div>
                <div>
                    <p
                        class="text-xs text-gray-500 uppercase font-bold tracking-wider mb-1"
                    >
                        Terakhir Booking
                    </p>
                    <p class="text-sm text-gray-700 dark:text-gray-300">
                        {{ formatDate(customer.last_booking_at) }}
                    </p>
                </div>
            </div>

            <!-- Route Preferences -->
            <div
                v-if="routePreferences.length > 0"
                class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700"
            >
                <h3
                    class="text-sm font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-3"
                >
                    Rute Favorit
                </h3>
                <div class="flex flex-wrap gap-2">
                    <span
                        v-for="route in routePreferences"
                        :key="route.route_name"
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-brand-red/10 text-brand-red font-semibold text-sm"
                    >
                        <i class="fas fa-route"></i>
                        {{ route.route_name }}
                        <span class="text-xs opacity-75"
                            >({{ route.booking_count }}x)</span
                        >
                    </span>
                </div>
            </div>
        </div>

        <!-- Booking History -->
        <div
            class="bg-white dark:bg-gray-800 rounded-3xl shadow-xl shadow-gray-100/50 dark:shadow-black/30 border border-gray-100 dark:border-gray-700/50 overflow-hidden"
        >
            <div
                class="px-8 py-6 border-b border-gray-200 dark:border-gray-700"
            >
                <h3
                    class="text-lg font-black text-gray-900 dark:text-white font-unbounded"
                >
                    Riwayat Pemesanan
                </h3>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 dark:bg-gray-900/50">
                        <tr>
                            <th
                                class="px-6 py-4 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider"
                            >
                                Kode Booking
                            </th>
                            <th
                                class="px-6 py-4 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider"
                            >
                                Rute
                            </th>
                            <th
                                class="px-6 py-4 text-center text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider"
                            >
                                Tanggal
                            </th>
                            <th
                                class="px-6 py-4 text-right text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider"
                            >
                                Total
                            </th>
                            <th
                                class="px-6 py-4 text-center text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider"
                            >
                                Status
                            </th>
                            <th
                                class="px-6 py-4 text-center text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider"
                            >
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody
                        class="divide-y divide-gray-200 dark:divide-gray-700"
                    >
                        <tr
                            v-for="booking in bookings.data"
                            :key="booking.id"
                            class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors"
                        >
                            <td class="px-6 py-4">
                                <p
                                    class="font-bold text-gray-900 dark:text-white font-mono"
                                >
                                    {{ booking.booking_code }}
                                </p>
                            </td>
                            <td class="px-6 py-4">
                                <p
                                    class="font-semibold text-gray-900 dark:text-white"
                                >
                                    {{ booking.schedule?.route?.origin }} →
                                    {{ booking.schedule?.route?.destination }}
                                </p>
                                <p class="text-xs text-gray-500 mt-1">
                                    {{ booking.schedule?.bus?.name }}
                                </p>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <p
                                    class="text-sm text-gray-700 dark:text-gray-300"
                                >
                                    {{ formatDate(booking.created_at) }}
                                </p>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <p
                                    class="font-black text-brand-red font-unbounded"
                                >
                                    {{ formatCurrency(booking.total_price) }}
                                </p>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span
                                    :class="[
                                        'px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wide',
                                        getStatusBadgeClass(
                                            booking.payment_status
                                        ),
                                    ]"
                                >
                                    {{
                                        translateStatus(booking.payment_status)
                                    }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <Link
                                    :href="
                                        route('admin.bookings.show', booking.id)
                                    "
                                    class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 font-semibold hover:bg-gray-200 dark:hover:bg-gray-600 transition-all text-xs"
                                >
                                    <i class="fas fa-eye"></i>
                                    Detail
                                </Link>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div
                v-if="bookings.links && bookings.links.length > 3"
                class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 flex items-center justify-between"
            >
                <div class="text-sm text-gray-500 dark:text-gray-400">
                    Menampilkan {{ bookings.from }} - {{ bookings.to }} dari
                    {{ bookings.total }} booking
                </div>
                <div class="flex gap-2">
                    <template
                        v-for="link in bookings.links"
                        :key="link?.label || Math.random()"
                    >
                        <Link
                            v-if="link && link.url"
                            :href="link.url"
                            v-html="link.label"
                            :class="[
                                'px-4 py-2 rounded-xl font-semibold transition-all duration-300',
                                link.active
                                    ? 'bg-brand-red text-white'
                                    : 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600',
                            ]"
                        ></Link>
                        <span
                            v-else-if="link && !link.url"
                            v-html="link.label"
                            class="px-4 py-2 rounded-xl font-semibold bg-gray-100 dark:bg-gray-700 text-gray-400 dark:text-gray-500 opacity-50 cursor-not-allowed"
                        ></span>
                    </template>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
