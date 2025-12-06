<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { Head, Link } from "@inertiajs/vue3";

const reports = [
    {
        title: "Laporan Penjualan",
        description:
            "Lihat grafik penjualan, total pendapatan, dan riwayat transaksi.",
        icon: "fa-chart-line",
        route: "admin.reports.sales",
        color: "bg-blue-500",
    },
    {
        title: "Laporan Okupansi",
        description: "Analisis tingkat keterisian kursi per armada dan rute.",
        icon: "fa-bus",
        route: "admin.reports.occupancy",
        color: "bg-green-500",
    },
    {
        title: "Laporan Kustom",
        description:
            "Generate laporan spesifik berdasarkan tanggal, rute, dan armada.",
        icon: "fa-filter",
        route: "admin.reports.custom",
        color: "bg-purple-500",
    },
];
</script>

<template>
    <Head title="Laporan & Analisis" />

    <AdminLayout title="Laporan & Analisis">
        <div class="mb-8">
            <h2
                class="text-2xl font-bold text-gray-900 dark:text-white font-serif"
            >
                Laporan & Analisis
            </h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                Pilih jenis laporan yang ingin Anda lihat.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <Link
                v-for="(report, index) in reports"
                :key="index"
                :href="route(report.route)"
                class="group relative bg-white dark:bg-gray-800 rounded-3xl p-8 shadow-xl shadow-gray-100/50 dark:shadow-black/30 border border-gray-100 dark:border-gray-700/50 overflow-hidden hover:scale-[1.02] transition-transform duration-300"
            >
                <div
                    class="absolute top-0 right-0 -mr-8 -mt-8 w-32 h-32 rounded-full opacity-10 transition-transform group-hover:scale-150 duration-500"
                    :class="report.color"
                ></div>

                <div class="relative z-10">
                    <div
                        class="w-14 h-14 rounded-2xl flex items-center justify-center text-white text-2xl mb-6 shadow-lg"
                        :class="[
                            report.color,
                            `shadow-${report.color.split('-')[1]}-500/30`,
                        ]"
                    >
                        <i class="fas" :class="report.icon"></i>
                    </div>

                    <h3
                        class="text-xl font-bold text-gray-900 dark:text-white mb-2"
                    >
                        {{ report.title }}
                    </h3>
                    <p
                        class="text-sm text-gray-500 dark:text-gray-400 leading-relaxed"
                    >
                        {{ report.description }}
                    </p>
                </div>

                <div
                    class="absolute bottom-8 right-8 text-gray-300 dark:text-gray-600 group-hover:translate-x-2 transition-transform duration-300"
                >
                    <i class="fas fa-arrow-right text-xl"></i>
                </div>
            </Link>
        </div>
    </AdminLayout>
</template>
