<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { Head, Link } from "@inertiajs/vue3";
import { onMounted, ref, computed } from "vue";
import Chart from "chart.js/auto";

const props = defineProps({
    salesData: Array,
    chartData: Array,
    recentBookings: Array,
});

const chartCanvas = ref(null);
let salesChart = null;

const totalRevenue = computed(() => {
    return props.salesData.reduce(
        (sum, item) => sum + parseFloat(item.total),
        0
    );
});

const formatCurrency = (value) => {
    return new Intl.NumberFormat("id-ID", {
        style: "currency",
        currency: "IDR",
        minimumFractionDigits: 0,
    }).format(value);
};

const exportToCSV = () => {
    if (!props.salesData || props.salesData.length === 0) {
        alert("Tidak ada data untuk diekspor");
        return;
    }
    let csvContent = "data:text/csv;charset=utf-8,";
    csvContent += "Tanggal,Total Penjualan\n";
    props.salesData.forEach(item => {
        csvContent += `${item.date},${item.total}\n`;
    });
    const encodedUri = encodeURI(csvContent);
    const link = document.createElement("a");
    link.setAttribute("href", encodedUri);
    link.setAttribute("download", `laporan_penjualan_${new Date().toISOString().split('T')[0]}.csv`);
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
};

onMounted(() => {
    if (chartCanvas.value) {
        const ctx = chartCanvas.value.getContext("2d");

        // Gradient
        const gradient = ctx.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, "rgba(239, 68, 68, 0.5)"); // brand-red with opacity
        gradient.addColorStop(1, "rgba(239, 68, 68, 0.0)");

        salesChart = new Chart(ctx, {
            type: "line",
            data: {
                labels: props.chartData.map((d) => d.date),
                datasets: [
                    {
                        label: "Pendapatan",
                        data: props.chartData.map((d) => d.total),
                        borderColor: "#ef4444",
                        backgroundColor: gradient,
                        borderWidth: 3,
                        pointBackgroundColor: "#ffffff",
                        pointBorderColor: "#ef4444",
                        pointBorderWidth: 3,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        fill: true,
                        tension: 0.4,
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false,
                    },
                    tooltip: {
                        mode: "index",
                        intersect: false,
                        callbacks: {
                            label: function (context) {
                                let label = context.dataset.label || "";
                                if (label) {
                                    label += ": ";
                                }
                                if (context.parsed.y !== null) {
                                    label += new Intl.NumberFormat("id-ID", {
                                        style: "currency",
                                        currency: "IDR",
                                    }).format(context.parsed.y);
                                }
                                return label;
                            },
                        },
                    },
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: "rgba(0, 0, 0, 0.05)",
                            drawBorder: false,
                        },
                        ticks: {
                            callback: function (value) {
                                if (value >= 1000000) {
                                    return value / 1000000 + "Jt";
                                } else if (value >= 1000) {
                                    return value / 1000 + "rb";
                                }
                                return value;
                            },
                        },
                    },
                    x: {
                        grid: {
                            display: false,
                        },
                    },
                },
                interaction: {
                    mode: "nearest",
                    axis: "x",
                    intersect: false,
                },
            },
        });
    }
});
</script>

<template>
    <Head title="Laporan Penjualan" />

    <AdminLayout title="Laporan Penjualan">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h2
                    class="text-2xl font-bold text-gray-900 dark:text-white font-serif"
                >
                    Laporan Penjualan
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    Ringkasan pendapatan transaksi 30 hari terakhir.
                </p>
            </div>
            <div class="flex items-center gap-3">
                <button
                    @click="exportToCSV"
                    class="px-5 py-2.5 rounded-xl bg-brand-red text-white font-semibold hover:bg-red-700 transition-all duration-300 flex items-center gap-2 shadow-lg shadow-red-500/30"
                >
                    <i class="fas fa-file-export"></i>
                    <span>Export CSV</span>
                </button>
                <Link
                    :href="route('admin.reports.index')"
                    class="px-5 py-2.5 rounded-xl bg-gray-100 text-gray-700 font-semibold hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700 transition-all duration-300 flex items-center gap-2"
                >
                    <i class="fas fa-arrow-left"></i>
                    <span>Kembali</span>
                </Link>
            </div>
        </div>

        <!-- Total Revenue Card -->
        <div
            class="bg-gradient-to-br from-brand-red to-red-600 rounded-3xl p-8 text-white shadow-xl shadow-red-500/20 mb-8 relative overflow-hidden"
        >
            <div
                class="absolute top-0 right-0 -mr-16 -mt-16 w-64 h-64 rounded-full bg-white opacity-10"
            ></div>
            <div class="relative z-10">
                <p class="text-red-100 font-medium mb-1">
                    Total Pendapatan (30 Hari)
                </p>
                <h3 class="text-4xl font-bold">
                    {{ formatCurrency(totalRevenue) }}
                </h3>
                <div
                    class="mt-4 flex items-center gap-2 text-sm text-red-100 bg-white/10 px-3 py-1.5 rounded-lg w-fit"
                >
                    <i class="fas fa-calendar-alt"></i>
                    <span>Berdasarkan data transaksi 'paid'</span>
                </div>
            </div>
        </div>

        <!-- Chart -->
        <div
            class="bg-white dark:bg-gray-800 rounded-3xl p-6 shadow-xl shadow-gray-100/50 dark:shadow-black/30 border border-gray-100 dark:border-gray-700/50 mb-8"
        >
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-6">
                Grafik Penjualan
            </h3>
            <div class="h-80 w-full">
                <canvas ref="chartCanvas"></canvas>
            </div>
        </div>

        <!-- Recent Bookings Table -->
        <div
            class="bg-white dark:bg-gray-800 rounded-3xl shadow-xl shadow-gray-100/50 dark:shadow-black/30 border border-gray-100 dark:border-gray-700/50 overflow-hidden"
        >
            <div class="p-6 border-b border-gray-100 dark:border-gray-700">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                    Transaksi Terakhir
                </h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead
                        class="bg-gray-50/50 dark:bg-gray-900/20 text-gray-500 dark:text-gray-400 text-xs uppercase font-bold tracking-wider"
                    >
                        <tr>
                            <th class="px-6 py-4">Kode</th>
                            <th class="px-6 py-4">Pemesan</th>
                            <th class="px-6 py-4">Rute</th>
                            <th class="px-6 py-4">Total</th>
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
                            <td class="px-6 py-4 font-medium text-brand-red">
                                #{{ booking.booking_code }}
                            </td>
                            <td class="px-6 py-4 text-gray-900 dark:text-white">
                                {{ booking.user_name }}
                            </td>
                            <td
                                class="px-6 py-4 text-gray-600 dark:text-gray-300 text-sm"
                            >
                                {{ booking.route }}
                            </td>
                            <td
                                class="px-6 py-4 font-bold text-gray-900 dark:text-white"
                            >
                                {{ formatCurrency(booking.total_price) }}
                            </td>
                            <td class="px-6 py-4 text-gray-500 text-sm">
                                {{ booking.created_at }}
                            </td>
                        </tr>
                        <tr v-if="recentBookings.length === 0">
                            <td
                                colspan="5"
                                class="px-6 py-8 text-center text-gray-400"
                            >
                                Belum ada data transaksi.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AdminLayout>
</template>
