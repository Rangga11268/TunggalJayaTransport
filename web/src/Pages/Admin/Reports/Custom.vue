<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import { ref } from "vue";

const props = defineProps({
    routes: Array,
    buses: Array,
    filters: Object,
    selectedRoute: String,
    selectedBus: String,
    reportData: [Object, Array],
});

const form = useForm({
    report_type: props.filters?.report_type || "bookings",
    start_date:
        props.filters?.start_date || new Date().toISOString().split("T")[0],
    end_date: props.filters?.end_date || new Date().toISOString().split("T")[0],
    route_id: props.filters?.route_id || "",
    bus_id: props.filters?.bus_id || "",
});

const submit = () => {
    form.post(route("admin.reports.custom.generate"), {
        preserveScroll: true,
    });
};

const exportReport = (type) => {
    const params = new URLSearchParams({
        report_type: form.report_type,
        start_date: form.start_date,
        end_date: form.end_date,
        route_id: form.route_id || "",
        bus_id: form.bus_id || "",
    }).toString();

    const routeName =
        type === "pdf"
            ? "admin.reports.custom.export.pdf"
            : "admin.reports.custom.export.excel";
    window.open(route(routeName) + "?" + params, "_blank");
};

const formatCurrency = (value) => {
    return new Intl.NumberFormat("id-ID", {
        style: "currency",
        currency: "IDR",
        minimumFractionDigits: 0,
    }).format(value);
};

// Helper for 'bookings' & 'revenue' date formatting
const formatDate = (dateString) => {
    return new Date(dateString).toLocaleDateString("id-ID", {
        day: "numeric",
        month: "short",
        year: "numeric",
    });
};
</script>

<template>
    <Head title="Laporan Kustom" />

    <AdminLayout title="Laporan Kustom">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h2
                    class="text-2xl font-bold text-gray-900 dark:text-white font-serif"
                >
                    Generate Laporan Kustom
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    Buat laporan spesifik sesuai kebutuhan Anda.
                </p>
            </div>
            <Link
                :href="route('admin.reports.index')"
                class="px-5 py-2.5 rounded-xl bg-gray-100 text-gray-700 font-semibold hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700 transition-all duration-300 flex items-center gap-2"
            >
                <i class="fas fa-arrow-left"></i>
                <span>Kembali</span>
            </Link>
        </div>

        <!-- Filters Form -->
        <div
            class="bg-white dark:bg-gray-800 rounded-3xl p-8 shadow-xl shadow-gray-100/50 dark:shadow-black/30 border border-gray-100 dark:border-gray-700/50 mb-8"
        >
            <form
                @submit.prevent="submit"
                class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-6 items-end"
            >
                <!-- Report Type -->
                <div>
                    <label
                        class="block text-xs font-bold text-gray-500 uppercase mb-2"
                        >Jenis Laporan</label
                    >
                    <select
                        v-model="form.report_type"
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-sm focus:ring-2 focus:ring-brand-red/50 outline-none"
                    >
                        <option value="bookings">Jumlah Pemesanan</option>
                        <option value="revenue">Pendapatan</option>
                        <option value="passengers">Data Penumpang</option>
                    </select>
                </div>

                <!-- Date Range -->
                <div>
                    <label
                        class="block text-xs font-bold text-gray-500 uppercase mb-2"
                        >Dari Tanggal</label
                    >
                    <input
                        type="date"
                        v-model="form.start_date"
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-sm focus:ring-2 focus:ring-brand-red/50 outline-none"
                    />
                </div>
                <div>
                    <label
                        class="block text-xs font-bold text-gray-500 uppercase mb-2"
                        >Sampai Tanggal</label
                    >
                    <input
                        type="date"
                        v-model="form.end_date"
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-sm focus:ring-2 focus:ring-brand-red/50 outline-none"
                    />
                </div>

                <!-- Filters -->
                <div>
                    <label
                        class="block text-xs font-bold text-gray-500 uppercase mb-2"
                        >Filter Rute (Opsional)</label
                    >
                    <select
                        v-model="form.route_id"
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-sm focus:ring-2 focus:ring-brand-red/50 outline-none"
                    >
                        <option value="">Semua Rute</option>
                        <option
                            v-for="route in routes"
                            :key="route.id"
                            :value="route.id"
                        >
                            {{ route.name }}
                        </option>
                    </select>
                </div>
                <div>
                    <label
                        class="block text-xs font-bold text-gray-500 uppercase mb-2"
                        >Filter Armada (Opsional)</label
                    >
                    <select
                        v-model="form.bus_id"
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-sm focus:ring-2 focus:ring-brand-red/50 outline-none"
                    >
                        <option value="">Semua Armada</option>
                        <option
                            v-for="bus in buses"
                            :key="bus.id"
                            :value="bus.id"
                        >
                            {{ bus.name }}
                        </option>
                    </select>
                </div>

                <!-- Submit -->
                <div class="md:col-span-3 lg:col-span-1 lg:col-start-5">
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="w-full px-6 py-2.5 rounded-xl bg-brand-red text-white font-bold shadow-lg shadow-brand-red/30 hover:bg-red-700 transition-all flex items-center justify-center gap-2"
                    >
                        <i
                            v-if="form.processing"
                            class="fas fa-spinner fa-spin"
                        ></i>
                        <span v-else>Generate</span>
                    </button>
                    <p
                        v-if="form.errors.end_date"
                        class="text-red-500 text-xs mt-1 text-center"
                    >
                        {{ form.errors.end_date }}
                    </p>
                </div>
            </form>
        </div>

        <!-- Results -->
        <div
            v-if="reportData && Object.keys(reportData).length > 0"
            class="space-y-6"
        >
            <!-- Export Buttons -->
            <div class="flex justify-end gap-3">
                <button
                    @click="exportReport('excel')"
                    class="px-5 py-2.5 rounded-xl bg-green-600 text-white font-bold shadow-lg shadow-green-600/20 hover:bg-green-700 transition-all flex items-center gap-2"
                >
                    <i class="fas fa-file-excel"></i> Export Excel
                </button>
                <button
                    @click="exportReport('pdf')"
                    class="px-5 py-2.5 rounded-xl bg-red-600 text-white font-bold shadow-lg shadow-red-600/20 hover:bg-red-700 transition-all flex items-center gap-2"
                >
                    <i class="fas fa-file-pdf"></i> Export PDF
                </button>
            </div>

            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Bookings Summary -->
                <template v-if="form.report_type === 'bookings'">
                    <div
                        class="bg-blue-500 text-white rounded-2xl p-6 shadow-lg shadow-blue-500/20"
                    >
                        <p class="text-blue-100 font-medium mb-1">
                            Total Pemesanan
                        </p>
                        <h3 class="text-3xl font-bold">
                            {{ reportData.total_bookings }}
                        </h3>
                    </div>
                    <div
                        class="bg-indigo-500 text-white rounded-2xl p-6 shadow-lg shadow-indigo-500/20"
                    >
                        <p class="text-indigo-100 font-medium mb-1">
                            Total Kursi Terjual
                        </p>
                        <h3 class="text-3xl font-bold">
                            {{ reportData.total_seats }}
                        </h3>
                    </div>
                </template>

                <!-- Revenue Summary -->
                <template v-if="form.report_type === 'revenue'">
                    <div
                        class="bg-green-500 text-white rounded-2xl p-6 shadow-lg shadow-green-500/20"
                    >
                        <p class="text-green-100 font-medium mb-1">
                            Total Pendapatan
                        </p>
                        <h3 class="text-3xl font-bold">
                            {{ formatCurrency(reportData.total_revenue) }}
                        </h3>
                    </div>
                    <div
                        class="bg-emerald-500 text-white rounded-2xl p-6 shadow-lg shadow-emerald-500/20"
                    >
                        <p class="text-emerald-100 font-medium mb-1">
                            Rata-rata Transaksi
                        </p>
                        <h3 class="text-3xl font-bold">
                            {{ formatCurrency(reportData.avg_booking_value) }}
                        </h3>
                    </div>
                </template>

                <!-- Passengers Summary -->
                <template v-if="form.report_type === 'passengers'">
                    <div
                        class="bg-purple-500 text-white rounded-2xl p-6 shadow-lg shadow-purple-500/20"
                    >
                        <p class="text-purple-100 font-medium mb-1">
                            Total Penumpang
                        </p>
                        <h3 class="text-3xl font-bold">
                            {{ reportData.total_passengers }}
                        </h3>
                    </div>
                </template>
            </div>

            <!-- Detail Tables -->
            <div
                class="bg-white dark:bg-gray-800 rounded-3xl shadow-xl shadow-gray-100/50 dark:shadow-black/30 border border-gray-100 dark:border-gray-700/50 overflow-hidden"
            >
                <div class="p-6 border-b border-gray-100 dark:border-gray-700">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                        Rincian Data
                    </h3>
                </div>
                <div class="overflow-x-auto">
                    <!-- Bookings Table -->
                    <table
                        v-if="form.report_type === 'bookings'"
                        class="w-full text-left border-collapse"
                    >
                        <thead
                            class="bg-gray-50/50 dark:bg-gray-900/20 text-gray-500 dark:text-gray-400 text-xs uppercase font-bold tracking-wider"
                        >
                            <tr>
                                <th class="px-6 py-3">Tanggal</th>
                                <th class="px-6 py-3 text-center">
                                    Jumlah Transaksi
                                </th>
                                <th class="px-6 py-3 text-center">
                                    Kursi Terjual
                                </th>
                            </tr>
                        </thead>
                        <tbody
                            class="divide-y divide-gray-100 dark:divide-gray-700/50"
                        >
                            <tr
                                v-for="(
                                    data, date
                                ) in reportData.daily_bookings"
                                :key="date"
                            >
                                <td class="px-6 py-3 font-medium">
                                    {{ formatDate(date) }}
                                </td>
                                <td class="px-6 py-3 text-center">
                                    {{ data.count }}
                                </td>
                                <td class="px-6 py-3 text-center">
                                    {{ data.seats }}
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- Revenue Table -->
                    <table
                        v-if="form.report_type === 'revenue'"
                        class="w-full text-left border-collapse"
                    >
                        <thead
                            class="bg-gray-50/50 dark:bg-gray-900/20 text-gray-500 dark:text-gray-400 text-xs uppercase font-bold tracking-wider"
                        >
                            <tr>
                                <th class="px-6 py-3">Tanggal</th>
                                <th class="px-6 py-3 text-right">Pendapatan</th>
                            </tr>
                        </thead>
                        <tbody
                            class="divide-y divide-gray-100 dark:divide-gray-700/50"
                        >
                            <tr
                                v-for="(data, date) in reportData.daily_revenue"
                                :key="date"
                            >
                                <td class="px-6 py-3 font-medium">
                                    {{ formatDate(date) }}
                                </td>
                                <td
                                    class="px-6 py-3 text-right font-bold text-brand-red"
                                >
                                    {{ formatCurrency(data.revenue) }}
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- Passengers - By Route -->
                    <div
                        v-if="form.report_type === 'passengers'"
                        class="p-6 grid grid-cols-1 md:grid-cols-2 gap-8"
                    >
                        <div>
                            <h4
                                class="font-bold text-gray-900 dark:text-white mb-4"
                            >
                                Penumpang per Rute
                            </h4>
                            <table class="w-full text-left border-collapse">
                                <tbody
                                    class="divide-y divide-gray-100 dark:divide-gray-700/50"
                                >
                                    <tr
                                        v-for="(
                                            count, route
                                        ) in reportData.route_passengers"
                                        :key="route"
                                    >
                                        <td
                                            class="py-2 text-sm text-gray-600 dark:text-gray-300"
                                        >
                                            {{ route }}
                                        </td>
                                        <td
                                            class="py-2 text-sm font-bold text-brand-red text-right"
                                        >
                                            {{ count }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div>
                            <h4
                                class="font-bold text-gray-900 dark:text-white mb-4"
                            >
                                Penumpang per Armada
                            </h4>
                            <table class="w-full text-left border-collapse">
                                <tbody
                                    class="divide-y divide-gray-100 dark:divide-gray-700/50"
                                >
                                    <tr
                                        v-for="(
                                            count, bus
                                        ) in reportData.bus_passengers"
                                        :key="bus"
                                    >
                                        <td
                                            class="py-2 text-sm text-gray-600 dark:text-gray-300"
                                        >
                                            {{ bus }}
                                        </td>
                                        <td
                                            class="py-2 text-sm font-bold text-brand-red text-right"
                                        >
                                            {{ count }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
