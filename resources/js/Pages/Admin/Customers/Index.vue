<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { Head, Link, router } from "@inertiajs/vue3";
import { ref, watch } from "vue";

const props = defineProps({
    customers: Object,
    filters: Object,
});

const search = ref(props.filters.search || "");

// Debounced search
let searchTimeout = null;
watch(search, (value) => {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        router.get(
            route("admin.customers.index"),
            { search: value },
            { preserveState: true, replace: true }
        );
    }, 300);
});

const formatCurrency = (value) => {
    return new Intl.NumberFormat("id-ID", {
        style: "currency",
        currency: "IDR",
        minimumFractionDigits: 0,
    }).format(value);
};

const formatDate = (dateString) => {
    if (!dateString) return "-";
    return new Date(dateString).toLocaleDateString("id-ID", {
        day: "numeric",
        month: "short",
        year: "numeric",
    });
};
</script>

<template>
    <Head title="Manajemen Pelanggan" />

    <AdminLayout title="Pelanggan">
        <!-- Header -->
        <div class="mb-8">
            <h2
                class="text-2xl font-black text-gray-900 dark:text-white font-unbounded"
            >
                Data Pelanggan
            </h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                Kelola dan pantau aktivitas pelanggan
            </p>
        </div>

        <!-- Search Bar -->
        <div class="mb-6">
            <div class="relative max-w-md">
                <input
                    v-model="search"
                    type="text"
                    placeholder="Cari nama, email, atau telepon..."
                    class="w-full pl-12 pr-4 py-3 rounded-2xl border-2 border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:border-brand-red focus:ring-0 transition-all"
                />
                <i
                    class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"
                ></i>
            </div>
        </div>

        <!-- Customers Table -->
        <div
            class="bg-white dark:bg-gray-800 rounded-2xl sm:rounded-3xl shadow-xl shadow-gray-100/50 dark:shadow-black/30 border border-gray-100 dark:border-gray-700/50 overflow-hidden mx-4 sm:mx-0"
        >
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead
                        class="bg-gray-50 dark:bg-gray-900/50 border-b border-gray-200 dark:border-gray-700"
                    >
                        <tr>
                            <th
                                class="px-6 py-4 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider"
                            >
                                Pelanggan
                            </th>
                            <th
                                class="px-6 py-4 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider"
                            >
                                Kontak
                            </th>
                            <th
                                class="px-6 py-4 text-center text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider"
                            >
                                Total Booking
                            </th>
                            <th
                                class="px-6 py-4 text-right text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider"
                            >
                                Total Pengeluaran
                            </th>
                            <th
                                class="px-6 py-4 text-center text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider"
                            >
                                Terakhir Booking
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
                            v-for="customer in customers.data"
                            :key="customer.passenger_email"
                            class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors"
                        >
                            <td class="px-6 py-4">
                                <div>
                                    <p
                                        class="font-bold text-gray-900 dark:text-white"
                                    >
                                        {{ customer.passenger_name }}
                                    </p>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm">
                                    <p class="text-gray-600 dark:text-gray-300">
                                        {{ customer.passenger_email }}
                                    </p>
                                    <p
                                        class="text-gray-500 dark:text-gray-400 mt-1"
                                    >
                                        {{ customer.passenger_phone }}
                                    </p>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400"
                                >
                                    {{ customer.total_bookings }} Booking
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <p
                                    class="font-black text-brand-red font-unbounded"
                                >
                                    {{ formatCurrency(customer.total_spent) }}
                                </p>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <p
                                    class="text-sm text-gray-600 dark:text-gray-300"
                                >
                                    {{ formatDate(customer.last_booking_at) }}
                                </p>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <Link
                                    v-if="customer.passenger_email"
                                    :href="
                                        route(
                                            'admin.customers.show',
                                            customer.passenger_email
                                        )
                                    "
                                    class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-brand-red text-white font-semibold hover:bg-red-700 transition-all duration-300"
                                >
                                    <i class="fas fa-eye"></i>
                                    Lihat Detail
                                </Link>
                                <span
                                    v-else
                                    class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-gray-300 text-gray-500 font-semibold cursor-not-allowed"
                                >
                                    <i class="fas fa-ban"></i>
                                    Tidak Ada Email
                                </span>
                            </td>
                        </tr>
                        <tr
                            v-if="customers.data.length === 0"
                            class="hover:bg-gray-50 dark:hover:bg-gray-700/50"
                        >
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center gap-3">
                                    <i
                                        class="fas fa-users text-4xl text-gray-300 dark:text-gray-600"
                                    ></i>
                                    <p class="text-gray-500 dark:text-gray-400">
                                        Tidak ada data pelanggan
                                    </p>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div
                v-if="customers.links && customers.links.length > 3"
                class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 flex items-center justify-between"
            >
                <div class="text-sm text-gray-500 dark:text-gray-400">
                    Menampilkan {{ customers.from }} - {{ customers.to }} dari
                    {{ customers.total }} pelanggan
                </div>
                <div class="flex gap-2">
                    <template
                        v-for="link in customers.links"
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
