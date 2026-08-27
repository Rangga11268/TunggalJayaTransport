<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { Head, Link } from "@inertiajs/vue3";

const props = defineProps({
    occupancyData: Array,
});

const exportToCSV = () => {
    if (!props.occupancyData || props.occupancyData.length === 0) {
        alert("Tidak ada data untuk diekspor");
        return;
    }
    let csvContent = "data:text/csv;charset=utf-8,";
    csvContent += "Tanggal & Jam,Rute,Armada,Plat Nomor,Kapasitas,Terisi,Okupansi (%)\n";
    props.occupancyData.forEach(item => {
        const routeStr = `"${item.route}"`; // Handle commas in route names
        csvContent += `${item.date},${routeStr},${item.bus_name},${item.plate_number},${item.capacity},${item.booked_seats},${item.occupancy_rate}\n`;
    });
    const encodedUri = encodeURI(csvContent);
    const link = document.createElement("a");
    link.setAttribute("href", encodedUri);
    link.setAttribute("download", `laporan_okupansi_${new Date().toISOString().split('T')[0]}.csv`);
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
};
</script>

<template>
    <Head title="Laporan Okupansi" />

    <AdminLayout title="Laporan Okupansi">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h2
                    class="text-2xl font-bold text-gray-900 dark:text-white font-serif"
                >
                    Laporan Okupansi
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    Tingkat keterisian kursi armada per jadwal keberangkatan
                    (Terakhir 50 Jadwal).
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

        <div
            class="bg-white dark:bg-gray-800 rounded-3xl shadow-xl shadow-gray-100/50 dark:shadow-black/30 border border-gray-100 dark:border-gray-700/50 overflow-hidden"
        >
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead
                        class="bg-gray-50/50 dark:bg-gray-900/20 text-gray-500 dark:text-gray-400 text-xs uppercase font-bold tracking-wider"
                    >
                        <tr>
                            <th class="px-6 py-4">Tanggal & Jam</th>
                            <th class="px-6 py-4">Rute</th>
                            <th class="px-6 py-4">Armada</th>
                            <th class="px-6 py-4 text-center">Kapasitas</th>
                            <th class="px-6 py-4 text-center">Terisi</th>
                            <th class="px-6 py-4">Okupansi</th>
                        </tr>
                    </thead>
                    <tbody
                        class="divide-y divide-gray-100 dark:divide-gray-700/50"
                    >
                        <tr
                            v-for="item in occupancyData"
                            :key="item.id"
                            class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors"
                        >
                            <td
                                class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-white"
                            >
                                {{ item.date }}
                            </td>
                            <td
                                class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300"
                            >
                                {{ item.route }}
                            </td>
                            <td class="px-6 py-4">
                                <div
                                    class="text-sm font-bold text-gray-900 dark:text-white"
                                >
                                    {{ item.bus_name }}
                                </div>
                                <div class="text-xs text-gray-500">
                                    {{ item.plate_number }}
                                </div>
                            </td>
                            <td
                                class="px-6 py-4 text-center text-sm font-medium"
                            >
                                {{ item.capacity }}
                            </td>
                            <td
                                class="px-6 py-4 text-center text-sm font-medium text-brand-red"
                            >
                                {{ item.booked_seats }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="flex-1 h-2 bg-gray-100 dark:bg-gray-700 rounded-full overflow-hidden"
                                    >
                                        <div
                                            class="h-full rounded-full transition-all duration-500"
                                            :class="{
                                                'bg-red-500':
                                                    item.occupancy_rate < 30,
                                                'bg-yellow-500':
                                                    item.occupancy_rate >= 30 &&
                                                    item.occupancy_rate < 70,
                                                'bg-green-500':
                                                    item.occupancy_rate >= 70,
                                            }"
                                            :style="{
                                                width: `${item.occupancy_rate}%`,
                                            }"
                                        ></div>
                                    </div>
                                    <span
                                        class="text-xs font-bold w-12 text-right"
                                        >{{ item.occupancy_rate }}%</span
                                    >
                                </div>
                            </td>
                        </tr>
                        <tr v-if="occupancyData.length === 0">
                            <td
                                colspan="6"
                                class="px-6 py-12 text-center text-gray-400"
                            >
                                <div class="flex flex-col items-center">
                                    <i
                                        class="fas fa-bus text-4xl mb-3 opacity-30"
                                    ></i>
                                    <p>Belum ada data jadwal/bookinng.</p>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AdminLayout>
</template>
