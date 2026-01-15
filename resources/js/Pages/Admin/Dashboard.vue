<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { Head, Link } from "@inertiajs/vue3";
import { computed } from "vue";
import VueApexCharts from "vue3-apexcharts";

const props = defineProps({
    totalBookings: Number,
    totalRevenue: Number,
    totalSchedules: Number,
    totalUsers: Number,
    recentBookings: Array,
    // Analytics Data
    revenueTrend: Array,
    popularRoutes: Array,
    peakHours: Array,
    thisWeekRevenue: Number,
    lastWeekRevenue: Number,
    revenueGrowth: Number,
    todayBookings: Number,
    todayRevenue: Number,
});

// Format currency
const formatCurrency = (value) => {
    return new Intl.NumberFormat("id-ID", {
        style: "currency",
        currency: "IDR",
        minimumFractionDigits: 0,
        maximumFractionDigits: 0,
    }).format(value);
};

const formatDate = (dateString) => {
    const date = new Date(dateString);
    return date.toLocaleDateString("id-ID", {
        day: "numeric",
        month: "short",
        hour: "2-digit",
        minute: "2-digit",
    });
};

// Revenue Trend Chart Options
const revenueTrendOptions = computed(() => ({
    chart: {
        type: "area",
        height: 300,
        toolbar: { show: false },
        zoom: { enabled: false },
        fontFamily: "Manrope, sans-serif",
    },
    colors: ["#e11d48"],
    fill: {
        type: "gradient",
        gradient: {
            shadeIntensity: 1,
            opacityFrom: 0.4,
            opacityTo: 0.05,
            stops: [0, 100],
        },
    },
    stroke: { curve: "smooth", width: 3 },
    dataLabels: { enabled: false },
    xaxis: {
        categories: props.revenueTrend?.map((item) => item.date) || [],
        labels: { style: { colors: "#9ca3af", fontSize: "11px" } },
        axisBorder: { show: false },
        axisTicks: { show: false },
    },
    yaxis: {
        labels: {
            style: { colors: "#9ca3af", fontSize: "11px" },
            formatter: (val) => formatCurrency(val),
        },
    },
    grid: { borderColor: "#1f2937", strokeDashArray: 4 },
    tooltip: {
        theme: "dark",
        y: { formatter: (val) => formatCurrency(val) },
    },
}));

const revenueTrendSeries = computed(() => [
    {
        name: "Pendapatan",
        data: props.revenueTrend?.map((item) => item.revenue) || [],
    },
]);

// Popular Routes Chart Options
const popularRoutesOptions = computed(() => ({
    chart: {
        type: "bar",
        height: 250,
        toolbar: { show: false },
        fontFamily: "Manrope, sans-serif",
    },
    plotOptions: {
        bar: { horizontal: true, borderRadius: 6, barHeight: "60%" },
    },
    colors: ["#3b82f6"],
    dataLabels: { enabled: false },
    xaxis: {
        categories: props.popularRoutes?.map((item) => item.route) || [],
        labels: { style: { colors: "#9ca3af", fontSize: "11px" } },
    },
    yaxis: {
        labels: { style: { colors: "#9ca3af", fontSize: "11px" } },
    },
    grid: { borderColor: "#1f2937", strokeDashArray: 4 },
    tooltip: {
        theme: "dark",
        y: {
            formatter: (val, { dataPointIndex }) => {
                const route = props.popularRoutes?.[dataPointIndex];
                return `${val} Booking • ${formatCurrency(
                    route?.revenue || 0
                )}`;
            },
        },
    },
}));

const popularRoutesSeries = computed(() => [
    {
        name: "Booking",
        data: props.popularRoutes?.map((item) => item.bookings) || [],
    },
]);

// Peak Hours Chart Options
const peakHoursOptions = computed(() => ({
    chart: {
        type: "bar",
        height: 200,
        toolbar: { show: false },
        fontFamily: "Manrope, sans-serif",
    },
    plotOptions: {
        bar: { borderRadius: 4, columnWidth: "70%" },
    },
    colors: ["#10b981"],
    dataLabels: { enabled: false },
    xaxis: {
        categories: props.peakHours?.map((item) => item.hour) || [],
        labels: {
            style: { colors: "#9ca3af", fontSize: "10px" },
            rotate: -45,
            rotateAlways: true,
        },
    },
    yaxis: {
        labels: { style: { colors: "#9ca3af", fontSize: "11px" } },
    },
    grid: { borderColor: "#1f2937", strokeDashArray: 4 },
    tooltip: { theme: "dark" },
}));

const peakHoursSeries = computed(() => [
    {
        name: "Booking",
        data: props.peakHours?.map((item) => item.count) || [],
    },
]);
</script>

<template>
    <Head title="Dasbor Admin" />

    <AdminLayout title="Ringkasan Dasbor">
        <div
            class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-6 sm:mb-10"
        >
            <div
                class="bg-white dark:bg-gray-800 rounded-2xl sm:rounded-3xl p-4 sm:p-6 shadow-xl shadow-gray-100/50 dark:shadow-black/30 border border-gray-100 dark:border-gray-700/50 group hover:-translate-y-1 transition-transform duration-300"
            >
                <div class="flex items-center justify-between mb-4">
                    <div
                        class="h-12 w-12 rounded-2xl bg-blue-50 dark:bg-blue-900/20 flex items-center justify-center text-blue-500 group-hover:scale-110 transition-transform duration-300"
                    >
                        <i class="fas fa-ticket-alt text-xl"></i>
                    </div>
                    <span
                        class="text-xs font-bold text-gray-400 uppercase tracking-wider"
                        >Pemesanan</span
                    >
                </div>
                <div>
                    <h3
                        class="text-2xl sm:text-3xl font-black text-gray-900 dark:text-white mt-1 font-unbounded"
                    >
                        {{ totalBookings }}
                    </h3>
                    <p class="text-sm text-gray-500 mt-1">
                        Total pesanan diproses
                    </p>
                </div>
            </div>

            <!-- Revenue -->
            <div
                class="bg-white dark:bg-gray-800 rounded-3xl p-6 shadow-xl shadow-gray-100/50 dark:shadow-black/30 border border-gray-100 dark:border-gray-700/50 group hover:-translate-y-1 transition-transform duration-300"
            >
                <div class="flex items-center justify-between mb-4">
                    <div
                        class="h-12 w-12 rounded-2xl bg-emerald-50 dark:bg-emerald-900/20 flex items-center justify-center text-emerald-500 group-hover:scale-110 transition-transform duration-300"
                    >
                        <i class="fas fa-wallet text-xl"></i>
                    </div>
                    <span
                        class="text-xs font-bold text-gray-400 uppercase tracking-wider"
                        >Pendapatan</span
                    >
                </div>
                <div>
                    <h3
                        class="text-xl sm:text-2xl xl:text-3xl font-black text-gray-900 dark:text-white mt-1 tracking-tight font-unbounded break-words"
                    >
                        {{ formatCurrency(totalRevenue) }}
                    </h3>
                    <p class="text-sm text-gray-500 mt-1">Total pemasukan</p>
                </div>
            </div>

            <!-- Active Routes -->
            <div
                class="bg-white dark:bg-gray-800 rounded-2xl sm:rounded-3xl p-4 sm:p-6 shadow-xl shadow-gray-100/50 dark:shadow-black/30 border border-gray-100 dark:border-gray-700/50 group hover:-translate-y-1 transition-transform duration-300"
            >
                <div class="flex items-center justify-between mb-4">
                    <div
                        class="h-12 w-12 rounded-2xl bg-amber-50 dark:bg-amber-900/20 flex items-center justify-center text-amber-500 group-hover:scale-110 transition-transform duration-300"
                    >
                        <i class="fas fa-route text-xl"></i>
                    </div>
                    <span
                        class="text-xs font-bold text-gray-400 uppercase tracking-wider"
                        >Rute Aktif</span
                    >
                </div>
                <div>
                    <h3
                        class="text-2xl sm:text-3xl font-black text-gray-900 dark:text-white mt-1 font-unbounded"
                    >
                        {{ totalSchedules }}
                    </h3>
                    <p class="text-sm text-gray-500 mt-1">
                        Jadwal tersedia saat ini
                    </p>
                </div>
            </div>

            <!-- Total Users -->
            <div
                class="bg-white dark:bg-gray-800 rounded-2xl sm:rounded-3xl p-4 sm:p-6 shadow-xl shadow-gray-100/50 dark:shadow-black/30 border border-gray-100 dark:border-gray-700/50 group hover:-translate-y-1 transition-transform duration-300"
            >
                <div class="flex items-center justify-between mb-4">
                    <div
                        class="h-12 w-12 rounded-2xl bg-purple-50 dark:bg-purple-900/20 flex items-center justify-center text-purple-500 group-hover:scale-110 transition-transform duration-300"
                    >
                        <i class="fas fa-users text-xl"></i>
                    </div>
                    <span
                        class="text-xs font-bold text-gray-400 uppercase tracking-wider"
                        >Pengguna</span
                    >
                </div>
                <div>
                    <h3
                        class="text-2xl sm:text-3xl font-black text-gray-900 dark:text-white mt-1 font-unbounded"
                    >
                        {{ totalUsers }}
                    </h3>
                    <p class="text-sm text-gray-500 mt-1">
                        Pelanggan terdaftar
                    </p>
                </div>
            </div>
        </div>

        <!-- Analytics Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-10">
            <!-- Revenue Trend Chart -->
            <div
                class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-3xl p-6 shadow-xl shadow-gray-100/50 dark:shadow-black/30 border border-gray-100 dark:border-gray-700/50"
            >
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3
                            class="text-lg font-black text-gray-900 dark:text-white font-unbounded"
                        >
                            Trend Pendapatan
                        </h3>
                        <p class="text-xs text-gray-500">30 hari terakhir</p>
                    </div>
                    <div
                        class="flex items-center gap-2 px-3 py-1.5 rounded-full text-xs font-bold"
                        :class="
                            revenueGrowth >= 0
                                ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400'
                                : 'bg-rose-100 text-rose-700 dark:bg-rose-900/30 dark:text-rose-400'
                        "
                    >
                        <i
                            :class="
                                revenueGrowth >= 0
                                    ? 'fas fa-arrow-up'
                                    : 'fas fa-arrow-down'
                            "
                        ></i>
                        {{ Math.abs(revenueGrowth) }}% vs minggu lalu
                    </div>
                </div>
                <VueApexCharts
                    v-if="revenueTrend?.length"
                    type="area"
                    height="300"
                    :options="revenueTrendOptions"
                    :series="revenueTrendSeries"
                />
                <div
                    v-else
                    class="h-[300px] flex items-center justify-center text-gray-400"
                >
                    <div class="text-center">
                        <i
                            class="fas fa-chart-line text-4xl mb-2 opacity-30"
                        ></i>
                        <p class="text-sm">Belum ada data pendapatan</p>
                    </div>
                </div>
            </div>

            <!-- Today's Stats Card -->
            <div
                class="bg-gradient-to-br from-rose-600 to-rose-700 rounded-3xl p-6 shadow-xl shadow-rose-600/20 text-white"
            >
                <div class="flex items-center gap-3 mb-6">
                    <div
                        class="h-12 w-12 rounded-2xl bg-white/20 flex items-center justify-center"
                    >
                        <i class="fas fa-calendar-day text-xl"></i>
                    </div>
                    <div>
                        <h3 class="font-black font-unbounded">Hari Ini</h3>
                        <p class="text-xs text-rose-200">
                            {{
                                new Date().toLocaleDateString("id-ID", {
                                    weekday: "long",
                                    day: "numeric",
                                    month: "long",
                                })
                            }}
                        </p>
                    </div>
                </div>

                <div class="space-y-4">
                    <div class="bg-white/10 rounded-2xl p-4 backdrop-blur-sm">
                        <p class="text-xs text-rose-200 mb-1">
                            Booking Hari Ini
                        </p>
                        <p class="text-3xl font-black font-unbounded">
                            {{ todayBookings }}
                        </p>
                    </div>
                    <div class="bg-white/10 rounded-2xl p-4 backdrop-blur-sm">
                        <p class="text-xs text-rose-200 mb-1">
                            Pendapatan Hari Ini
                        </p>
                        <p class="text-2xl font-black font-unbounded">
                            {{ formatCurrency(todayRevenue) }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Second Row: Popular Routes & Peak Hours -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-10">
            <!-- Popular Routes Chart -->
            <div
                class="bg-white dark:bg-gray-800 rounded-3xl p-6 shadow-xl shadow-gray-100/50 dark:shadow-black/30 border border-gray-100 dark:border-gray-700/50"
            >
                <div class="mb-6">
                    <h3
                        class="text-lg font-black text-gray-900 dark:text-white font-unbounded"
                    >
                        Rute Populer
                    </h3>
                    <p class="text-xs text-gray-500">
                        Top 5 rute berdasarkan jumlah booking
                    </p>
                </div>
                <VueApexCharts
                    v-if="popularRoutes?.length"
                    type="bar"
                    height="250"
                    :options="popularRoutesOptions"
                    :series="popularRoutesSeries"
                />
                <div
                    v-else
                    class="h-[250px] flex items-center justify-center text-gray-400"
                >
                    <div class="text-center">
                        <i class="fas fa-route text-4xl mb-2 opacity-30"></i>
                        <p class="text-sm">Belum ada data rute</p>
                    </div>
                </div>
            </div>

            <!-- Peak Hours Chart -->
            <div
                class="bg-white dark:bg-gray-800 rounded-3xl p-6 shadow-xl shadow-gray-100/50 dark:shadow-black/30 border border-gray-100 dark:border-gray-700/50"
            >
                <div class="mb-6">
                    <h3
                        class="text-lg font-black text-gray-900 dark:text-white font-unbounded"
                    >
                        Jam Sibuk
                    </h3>
                    <p class="text-xs text-gray-500">
                        Distribusi booking berdasarkan jam
                    </p>
                </div>
                <VueApexCharts
                    v-if="peakHours?.length"
                    type="bar"
                    height="200"
                    :options="peakHoursOptions"
                    :series="peakHoursSeries"
                />
                <div
                    v-else
                    class="h-[200px] flex items-center justify-center text-gray-400"
                >
                    <div class="text-center">
                        <i class="fas fa-clock text-4xl mb-2 opacity-30"></i>
                        <p class="text-sm">Belum ada data jam sibuk</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top 3 Routes & Recent Bookings Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 sm:gap-10">
            <!-- Recent Activity -->
            <div
                class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-2xl sm:rounded-3xl shadow-xl shadow-gray-100/50 dark:shadow-black/30 border border-gray-100 dark:border-gray-700/50 overflow-hidden flex flex-col"
            >
                <div
                    class="p-6 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center bg-gray-50/50 dark:bg-gray-800/50"
                >
                    <div>
                        <h3
                            class="text-lg font-black text-gray-900 dark:text-white font-unbounded"
                        >
                            Pemesanan Terbaru
                        </h3>
                        <p class="text-xs text-gray-500">
                            Aktivitas transaksi terkini
                        </p>
                    </div>

                    <Link
                        :href="route('admin.bookings.index')"
                        class="px-4 py-2 rounded-full bg-white dark:bg-gray-700 text-xs font-bold text-gray-600 dark:text-gray-300 border border-gray-200 dark:border-gray-600 hover:bg-gray-50 hover:text-brand-red transition-all shadow-sm"
                    >
                        Lihat Semua
                    </Link>
                </div>
                <div class="overflow-x-auto flex-1">
                    <table class="w-full text-left border-collapse">
                        <thead
                            class="bg-gray-50/50 dark:bg-gray-900/20 text-gray-500 dark:text-gray-400 text-[10px] uppercase font-bold tracking-wider"
                        >
                            <tr>
                                <th class="px-6 py-4">Kode</th>
                                <th class="px-6 py-4">Info Rute</th>
                                <th class="px-6 py-4">Pelanggan</th>
                                <th class="px-6 py-4">Status</th>
                                <th class="px-6 py-4">Tanggal</th>
                            </tr>
                        </thead>
                        <tbody
                            class="divide-y divide-gray-100 dark:divide-gray-700/50"
                        >
                            <tr
                                v-for="booking in recentBookings"
                                :key="booking.id"
                                class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors"
                            >
                                <td class="px-6 py-4">
                                    <span
                                        class="font-mono text-xs font-bold text-gray-900 dark:text-white bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded"
                                    >
                                        #{{ booking.booking_code }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div
                                        class="flex flex-col"
                                        v-if="booking.schedule?.route"
                                    >
                                        <span
                                            class="text-sm font-bold text-gray-800 dark:text-gray-200"
                                            >{{
                                                booking.schedule.route.origin
                                            }}</span
                                        >
                                        <div
                                            class="flex items-center gap-1 text-[10px] text-gray-400"
                                        >
                                            <i class="fas fa-arrow-right"></i>
                                            <span>{{
                                                booking.schedule.route
                                                    .destination
                                            }}</span>
                                        </div>
                                    </div>
                                    <span
                                        v-else
                                        class="italic text-gray-400 text-xs"
                                        >Rute Dihapus</span
                                    >
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="h-8 w-8 rounded-full bg-gradient-to-br from-gray-200 to-gray-300 flex items-center justify-center text-xs font-bold text-gray-600"
                                        >
                                            {{
                                                booking.passenger_name.charAt(0)
                                            }}
                                        </div>
                                        <span
                                            class="text-sm font-medium text-gray-700 dark:text-gray-300"
                                            >{{ booking.passenger_name }}</span
                                        >
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        :class="[
                                            'px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-wide',
                                            booking.payment_status === 'paid'
                                                ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400 ring-1 ring-emerald-500/20'
                                                : booking.payment_status ===
                                                  'pending'
                                                ? 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400 ring-1 ring-amber-500/20'
                                                : 'bg-rose-100 text-rose-700 dark:bg-rose-900/30 dark:text-rose-400 ring-1 ring-rose-500/20',
                                        ]"
                                    >
                                        {{ booking.payment_status }}
                                    </span>
                                </td>
                                <td
                                    class="px-6 py-4 text-xs font-medium text-gray-500 dark:text-gray-400"
                                >
                                    {{ formatDate(booking.created_at) }}
                                </td>
                            </tr>
                            <tr v-if="recentBookings.length === 0">
                                <td
                                    colspan="5"
                                    class="px-6 py-12 text-center text-gray-400"
                                >
                                    <div class="flex flex-col items-center">
                                        <i
                                            class="fas fa-inbox text-4xl mb-3 opacity-30"
                                        ></i>
                                        <p>Belum ada aktivitas pemesanan.</p>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Quick Actions -->
            <div
                class="bg-white dark:bg-gray-800 rounded-2xl sm:rounded-3xl p-5 sm:p-8 shadow-xl shadow-gray-100/50 dark:shadow-black/30 border border-gray-100 dark:border-gray-700/50 h-fit"
            >
                <h3
                    class="text-lg font-black text-gray-900 dark:text-white mb-6 flex items-center gap-2 font-unbounded"
                >
                    <i class="fas fa-bolt text-yellow-400"></i> Aksi Cepat
                </h3>
                <div class="grid grid-cols-2 gap-3 sm:gap-4">
                    <Link
                        :href="route('admin.bookings.create')"
                        class="p-3 sm:p-4 rounded-xl sm:rounded-2xl bg-gray-50 dark:bg-gray-700/50 hover:bg-blue-50 hover:dark:bg-blue-900/20 border border-transparent hover:border-blue-200 dark:hover:border-blue-800 transition-all duration-300 group flex flex-col items-center text-center gap-2"
                    >
                        <div
                            class="h-10 w-10 full rounded-xl bg-blue-100 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400 flex items-center justify-center group-hover:scale-110 transition-transform"
                        >
                            <i class="fas fa-plus"></i>
                        </div>
                        <span
                            class="text-xs font-bold text-gray-600 dark:text-gray-300 group-hover:text-blue-600"
                            >Buat Pesanan</span
                        >
                    </Link>

                    <Link
                        :href="route('admin.news.create')"
                        class="p-3 sm:p-4 rounded-xl sm:rounded-2xl bg-gray-50 dark:bg-gray-700/50 hover:bg-emerald-50 hover:dark:bg-emerald-900/20 border border-transparent hover:border-emerald-200 dark:hover:border-emerald-800 transition-all duration-300 group flex flex-col items-center text-center gap-2"
                    >
                        <div
                            class="h-10 w-10 full rounded-xl bg-emerald-100 dark:bg-emerald-900/40 text-emerald-600 dark:text-emerald-400 flex items-center justify-center group-hover:scale-110 transition-transform"
                        >
                            <i class="fas fa-pen-nib"></i>
                        </div>
                        <span
                            class="text-xs font-bold text-gray-600 dark:text-gray-300 group-hover:text-emerald-600"
                            >Terbitkan Berita</span
                        >
                    </Link>

                    <Link
                        :href="route('admin.schedules.index')"
                        class="p-3 sm:p-4 rounded-xl sm:rounded-2xl bg-gray-50 dark:bg-gray-700/50 hover:bg-amber-50 hover:dark:bg-amber-900/20 border border-transparent hover:border-amber-200 dark:hover:border-amber-800 transition-all duration-300 group flex flex-col items-center text-center gap-2"
                    >
                        <div
                            class="h-10 w-10 full rounded-xl bg-amber-100 dark:bg-amber-900/40 text-amber-600 dark:text-amber-400 flex items-center justify-center group-hover:scale-110 transition-transform"
                        >
                            <i class="fas fa-calendar-plus"></i>
                        </div>
                        <span
                            class="text-xs font-bold text-gray-600 dark:text-gray-300 group-hover:text-amber-600"
                            >Atur Jadwal</span
                        >
                    </Link>

                    <Link
                        :href="route('admin.reports.index')"
                        class="p-3 sm:p-4 rounded-xl sm:rounded-2xl bg-gray-50 dark:bg-gray-700/50 hover:bg-purple-50 hover:dark:bg-purple-900/20 border border-transparent hover:border-purple-200 dark:hover:border-purple-800 transition-all duration-300 group flex flex-col items-center text-center gap-2"
                    >
                        <div
                            class="h-10 w-10 full rounded-xl bg-purple-100 dark:bg-purple-900/40 text-purple-600 dark:text-purple-400 flex items-center justify-center group-hover:scale-110 transition-transform"
                        >
                            <i class="fas fa-file-invoice-dollar"></i>
                        </div>
                        <span
                            class="text-xs font-bold text-gray-600 dark:text-gray-300 group-hover:text-purple-600"
                            >Lihat Laporan</span
                        >
                    </Link>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
