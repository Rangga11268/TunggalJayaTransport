<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { Head, Link } from "@inertiajs/vue3";

const props = defineProps({
    charterBookings: Array,
});

const formatCurrency = (value) => {
    return new Intl.NumberFormat("id-ID", {
        style: "currency",
        currency: "IDR",
        minimumFractionDigits: 0,
    }).format(value);
};

const exportToCSV = () => {
    if (!props.charterBookings || props.charterBookings.length === 0) {
        alert("Tidak ada data untuk diekspor");
        return;
    }
    let csvContent = "data:text/csv;charset=utf-8,";
    csvContent += "Kode Booking,Pemesan,Tgl Keberangkatan,Rute,Armada,Pendapatan,Tgl Dibuat\n";
    props.charterBookings.forEach(item => {
        const routeStr = `"${item.route}"`;
        csvContent += `${item.charter_code},"${item.user_name}",${item.pickup_date},${routeStr},"${item.bus_name}",${item.total_price},${item.created_at}\n`;
    });
    const encodedUri = encodeURI(csvContent);
    const link = document.createElement("a");
    link.setAttribute("href", encodedUri);
    link.setAttribute("download", `laporan_pariwisata_${new Date().toISOString().split('T')[0]}.csv`);
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
};
</script>

<template>
    <Head title="Laporan Pariwisata" />

    <AdminLayout title="Laporan Pariwisata">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h2
                    class="text-2xl font-bold text-gray-900 dark:text-white font-serif"
                >
                    Laporan Pariwisata (Charter)
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    Riwayat pendapatan dan pemesanan penyewaan bus pariwisata.
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
                            <th class="px-6 py-4">Kode Booking</th>
                            <th class="px-6 py-4">Pemesan</th>
                            <th class="px-6 py-4">Tgl Keberangkatan</th>
                            <th class="px-6 py-4">Rute</th>
                            <th class="px-6 py-4">Armada</th>
                            <th class="px-6 py-4 text-right">Pendapatan</th>
                        </tr>
                    </thead>
                    <tbody
                        class="divide-y divide-gray-100 dark:divide-gray-700/50"
                    >
                        <tr
                            v-for="item in charterBookings"
                            :key="item.id"
                            class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors"
                        >
                            <td
                                class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-white"
                            >
                                <div class="font-bold text-brand-red">{{ item.charter_code }}</div>
                                <div class="text-xs text-gray-500 mt-0.5">{{ item.created_at }}</div>
                            </td>
                            <td
                                class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300 font-medium"
                            >
                                {{ item.user_name }}
                            </td>
                            <td class="px-6 py-4 text-sm">
                                {{ item.pickup_date }}
                            </td>
                            <td
                                class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300"
                            >
                                {{ item.route }}
                            </td>
                            <td
                                class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300"
                            >
                                {{ item.bus_name }}
                            </td>
                            <td class="px-6 py-4 text-right font-bold text-gray-900 dark:text-white">
                                {{ formatCurrency(item.total_price) }}
                            </td>
                        </tr>
                        <tr v-if="!charterBookings || charterBookings.length === 0">
                            <td
                                colspan="6"
                                class="px-6 py-12 text-center text-gray-400"
                            >
                                <div class="flex flex-col items-center">
                                    <i
                                        class="fas fa-file-invoice-dollar text-4xl mb-3 opacity-30"
                                    ></i>
                                    <p>Belum ada data penyewaan pariwisata yang lunas.</p>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AdminLayout>
</template>
