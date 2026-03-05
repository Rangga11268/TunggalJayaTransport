<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { Head, Link, router } from "@inertiajs/vue3";
import { ref, watch } from "vue";
import Swal from "sweetalert2";
import axios from "axios";

const props = defineProps({
    bookings: Object,
    filters: Object,
});

const search = ref(props.filters?.search || "");
const status = ref(props.filters?.status || "all");
const paymentStatus = ref(props.filters?.payment_status || "all");
const localBookings = ref(props.bookings); // Reactive local state for bookings data

let timeout = null;

// Filtering via Axios (No Inertia Reload)
const applyFilters = async () => {
    try {
        const { data } = await axios.get(route("admin.bookings.index"), {
            params: {
                search: search.value || "",
                status: status.value || "all",
                payment_status: paymentStatus.value || "all",
            },
            headers: { Accept: "application/json" },
        });
        localBookings.value = data.bookings;

        // Sync URL optionally without reloading
        const newUrl = new URL(window.location.href);
        if (search.value) newUrl.searchParams.set("search", search.value);
        else newUrl.searchParams.delete("search");

        if (status.value && status.value !== "all")
            newUrl.searchParams.set("status", status.value);
        else newUrl.searchParams.delete("status");

        if (paymentStatus.value && paymentStatus.value !== "all")
            newUrl.searchParams.set("payment_status", paymentStatus.value);
        else newUrl.searchParams.delete("payment_status");

        window.history.replaceState({}, "", newUrl);
    } catch (error) {
        console.error("Filter failed:", error);
    }
};

watch(search, (value) => {
    clearTimeout(timeout);
    timeout = setTimeout(() => {
        applyFilters();
    }, 500);
});

watch([status, paymentStatus], () => {
    applyFilters();
});

// Pagination via Axios (No Inertia Reload)
const fetchPage = async (url) => {
    if (!url) return;
    try {
        const { data } = await axios.get(url, {
            headers: { Accept: "application/json" },
        });
        localBookings.value = data.bookings;

        // Sync URL with pagination parameter
        window.history.replaceState({}, "", url);
        window.scrollTo({ top: 0, behavior: "smooth" });
    } catch (error) {
        console.error("Pagination failed:", error);
    }
};

const deleteBooking = (id) => {
    Swal.fire({
        title: "Hapus Pemesanan?",
        text: "Data yang dihapus tidak dapat dikembalikan!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
        confirmButtonText: "Ya, hapus!",
        cancelButtonText: "Batal",
    }).then((result) => {
        if (result.isConfirmed) {
            router.delete(route("admin.bookings.destroy", id), {
                onSuccess: () => {
                    // Success handled by layout flash message
                },
            });
        }
    });
};

const formatCurrency = (value) => {
    return new Intl.NumberFormat("id-ID", {
        style: "currency",
        currency: "IDR",
        minimumFractionDigits: 0,
    }).format(value);
};

const formatDate = (dateString, includeTime = false) => {
    const options = {
        year: "numeric",
        month: "short",
        day: "numeric",
        ...(includeTime && { hour: "2-digit", minute: "2-digit" }),
    };
    return new Date(dateString).toLocaleDateString("id-ID", options);
};

const getPaymentStatusBadgeClass = (status) => {
    switch (status) {
        case "paid":
            return "bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400 ring-1 ring-green-500/20";
        case "pending":
            return "bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400 ring-1 ring-amber-500/20";
        case "failed":
            return "bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400 ring-1 ring-red-500/20";
        case "refunded":
            return "bg-gray-100 text-gray-700 dark:bg-gray-900/30 dark:text-gray-400 ring-1 ring-gray-500/20";
        default:
            return "bg-gray-100 text-gray-700";
    }
};

const getBookingStatusBadgeClass = (status) => {
    switch (status) {
        case "confirmed":
        case "completed":
            return "bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400 ring-1 ring-blue-500/20";
        case "pending":
            return "bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400 ring-1 ring-yellow-500/20";
        case "cancelled":
            return "bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400 ring-1 ring-red-500/20";
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
    <Head title="Manajemen Pemesanan" />

    <AdminLayout title="Manajemen Pemesanan">
        <div
            class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6 sm:mb-8 px-4 sm:px-0"
        >
            <div>
                <h2
                    class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white font-serif"
                >
                    Daftar Pemesanan
                </h2>
                <p
                    class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 mt-1"
                >
                    Kelola data pemesanan tiket, verifikasi pembayaran, dan
                    status perjalanan.
                </p>
            </div>

            <div class="flex flex-col sm:flex-row gap-3 px-4 sm:px-0">
                <!-- Filters -->
                <select
                    v-model="status"
                    class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm focus:ring-2 focus:ring-brand-red/50 outline-none transition-all px-4 py-2.5"
                >
                    <option value="all">Semua Status</option>
                    <option value="pending">Tertunda (Booking)</option>
                    <option value="confirmed">Dikonfirmasi</option>
                    <option value="cancelled">Dibatalkan</option>
                    <option value="completed">Selesai</option>
                </select>

                <select
                    v-model="paymentStatus"
                    class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm focus:ring-2 focus:ring-brand-red/50 outline-none transition-all px-4 py-2.5"
                >
                    <option value="all">Semua Pembayaran</option>
                    <option value="pending">Belum Bayar</option>
                    <option value="paid">Lunas</option>
                    <option value="failed">Gagal</option>
                    <option value="refunded">Refund</option>
                </select>

                <div class="relative">
                    <input
                        type="text"
                        v-model="search"
                        placeholder="Cari Kode/Nama/Email..."
                        class="pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm focus:ring-2 focus:ring-brand-red/50 outline-none transition-all w-full md:w-64"
                    />
                    <div
                        class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400"
                    >
                        <i class="fas fa-search"></i>
                    </div>
                </div>

                <Link
                    :href="route('admin.bookings.create')"
                    class="px-4 sm:px-5 py-2.5 rounded-xl bg-brand-red text-white font-semibold shadow-lg shadow-brand-red/30 hover:bg-red-700 hover:shadow-brand-red/50 transition-all duration-300 flex items-center justify-center gap-2 whitespace-nowrap text-sm sm:text-base"
                >
                    <i class="fas fa-plus"></i>
                    <span>Booking Manual</span>
                </Link>
            </div>
        </div>

        <div
            class="bg-white dark:bg-gray-800 rounded-2xl sm:rounded-3xl shadow-xl shadow-gray-100/50 dark:shadow-black/30 border border-gray-100 dark:border-gray-700/50 overflow-hidden mx-4 sm:mx-0"
        >
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead
                        class="bg-gray-50/50 dark:bg-gray-900/20 text-gray-500 dark:text-gray-400 text-xs uppercase font-bold tracking-wider"
                    >
                        <tr>
                            <th class="px-6 py-4">Kode & Tanggal</th>
                            <th class="px-6 py-4">Penumpang</th>
                            <th class="px-6 py-4">Rute & Bus</th>
                            <th class="px-6 py-4">Total</th>
                            <th class="px-6 py-4">Pembayaran</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody
                        class="divide-y divide-gray-100 dark:divide-gray-700/50"
                    >
                        <tr
                            v-for="booking in localBookings?.data"
                            :key="booking.id"
                            class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors"
                        >
                            <td class="px-6 py-4 text-sm">
                                <div
                                    class="font-bold text-gray-900 dark:text-white"
                                >
                                    {{ booking.booking_code }}
                                </div>
                                <div class="text-xs text-gray-500">
                                    {{ formatDate(booking.created_at) }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-col gap-1">
                                    <div
                                        class="font-semibold text-gray-900 dark:text-white text-sm"
                                    >
                                        {{ booking.passenger_name }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        {{ booking.passenger_phone }}
                                    </div>
                                    <div
                                        class="text-xs text-gray-500 truncate max-w-[150px]"
                                    >
                                        {{ booking.passenger_email }}
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-col gap-1">
                                    <div
                                        class="text-sm font-medium text-gray-900 dark:text-white"
                                    >
                                        {{ booking.schedule?.route?.origin }} -
                                        {{
                                            booking.schedule?.route?.destination
                                        }}
                                    </div>
                                    <div
                                        class="text-xs text-gray-500 flex items-center gap-1"
                                    >
                                        <i class="fas fa-bus text-gray-400"></i>
                                        {{ booking.schedule?.bus?.name }}
                                    </div>
                                    <div
                                        class="text-xs text-brand-red font-medium"
                                    >
                                        {{
                                            formatDate(
                                                booking.departure_time,
                                                true,
                                            )
                                        }}
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div
                                    class="font-bold text-gray-900 dark:text-white text-sm"
                                >
                                    {{ formatCurrency(booking.total_price) }}
                                </div>
                                <div class="text-xs text-gray-500">
                                    {{
                                        (Array.isArray(booking.seat_numbers)
                                            ? booking.seat_numbers
                                            : (
                                                  booking.seat_numbers || ""
                                              ).split(",")
                                        ).filter(Boolean).length
                                    }}
                                    Kursi
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span
                                    :class="[
                                        'px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-wide',
                                        getPaymentStatusBadgeClass(
                                            booking.payment_status,
                                        ),
                                    ]"
                                >
                                    {{
                                        translateStatus(booking.payment_status)
                                    }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span
                                    :class="[
                                        'px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-wide',
                                        getBookingStatusBadgeClass(
                                            booking.booking_status,
                                        ),
                                    ]"
                                >
                                    {{
                                        translateStatus(booking.booking_status)
                                    }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div
                                    class="flex items-center justify-end gap-2"
                                >
                                    <Link
                                        :href="
                                            route(
                                                'admin.bookings.show',
                                                booking.id,
                                            )
                                        "
                                        class="p-2 rounded-lg text-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors tooltip"
                                        title="Lihat Detail"
                                    >
                                        <i class="fas fa-eye"></i>
                                    </Link>
                                    <Link
                                        :href="
                                            route(
                                                'admin.bookings.edit',
                                                booking.id,
                                            )
                                        "
                                        class="p-2 rounded-lg text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-colors tooltip"
                                        title="Edit"
                                        v-if="booking.status !== 'cancelled'"
                                    >
                                        <i class="fas fa-edit"></i>
                                    </Link>
                                    <button
                                        @click="deleteBooking(booking.id)"
                                        class="p-2 rounded-lg text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors tooltip"
                                        title="Hapus"
                                    >
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="localBookings?.data?.length === 0">
                            <td
                                colspan="7"
                                class="px-6 py-12 text-center text-gray-400"
                            >
                                <div class="flex flex-col items-center">
                                    <i
                                        class="fas fa-ticket-alt text-4xl mb-3 opacity-30"
                                    ></i>
                                    <p>Belum ada data pemesanan.</p>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div
                class="px-4 sm:px-6 py-4 border-t border-gray-100 dark:border-gray-700 flex flex-col sm:flex-row items-center justify-between gap-3"
                v-if="localBookings?.links?.length > 3"
            >
                <div class="text-xs text-gray-500 text-center sm:text-left">
                    Menampilkan {{ localBookings.from }} -
                    {{ localBookings.to }} dari {{ localBookings.total }} data
                </div>
                <div class="flex flex-wrap gap-1 justify-center">
                    <template v-for="(link, k) in localBookings.links" :key="k">
                        <button
                            v-if="link.url"
                            @click.prevent="fetchPage(link.url)"
                            v-html="link.label"
                            :class="[
                                'px-3 py-1 rounded-lg text-xs font-bold transition-all',
                                link.active
                                    ? 'bg-brand-red text-white shadow-md shadow-brand-red/20'
                                    : 'text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700',
                            ]"
                        />
                        <span
                            v-else
                            v-html="link.label"
                            class="px-3 py-1 rounded-lg text-xs font-bold text-gray-400 cursor-not-allowed opacity-50"
                        ></span>
                    </template>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
