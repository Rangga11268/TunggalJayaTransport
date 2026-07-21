<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { Head, Link } from "@inertiajs/vue3";
import { ref, watch } from "vue";
import axios from "axios";
import Swal from "sweetalert2";
import { useBulkDelete } from "@/Composables/useBulkDelete.js";
import { router } from "@inertiajs/vue3";

const props = defineProps({
    charters: Object,
    filters: Object,
});

const search = ref(props.filters?.search || "");
const localCharters = ref(props.charters);
const { selectedIds, selectAll } = useBulkDelete(localCharters);
let timeout = null;

const applyFilters = async () => {
    try {
        const { data } = await axios.get(route("admin.charter-bookings.index"), {
            params: { search: search.value || "" },
            headers: { Accept: "application/json" },
        });
        localCharters.value = data.charters;
        const newUrl = new URL(window.location.href);
        if (search.value) newUrl.searchParams.set("search", search.value);
        else newUrl.searchParams.delete("search");
        window.history.replaceState({}, "", newUrl);
    } catch (error) {}
};

watch(search, () => {
    clearTimeout(timeout);
    timeout = setTimeout(() => applyFilters(), 500);
});

const fetchPage = async (url) => {
    if (!url) return;
    try {
        const { data } = await axios.get(url, { headers: { Accept: "application/json" } });
        localCharters.value = data.charters;
        window.history.replaceState({}, "", url);
        window.scrollTo({ top: 0, behavior: "smooth" });
    } catch (error) {}
};

const bulkDelete = () => {
    if (selectedIds.value.length === 0) return;
    Swal.fire({ title: `Hapus ${selectedIds.value.length} booking?`, text: "Data yang dihapus tidak dapat dikembalikan!", icon: "warning",
        showCancelButton: true, confirmButtonColor: "#d33", cancelButtonColor: "#3085d6",
        confirmButtonText: "Ya, hapus semua!", cancelButtonText: "Batal",
    }).then((r) => { if (r.isConfirmed) {
        axios.post(route("admin.charter-bookings.bulk-destroy"), { ids: selectedIds.value, _method: "DELETE" })
            .then(() => {
                Swal.fire({ icon: "success", title: "Berhasil!", text: `${selectedIds.value.length} booking dihapus.`, timer: 1500, showConfirmButton: false });
                localCharters.value = { ...localCharters.value, data: localCharters.value.data.filter(d => !selectedIds.value.includes(d.id)), total: (localCharters.value.total || localCharters.value.data.length) - selectedIds.value.length };
                selectedIds.value = [];
            }).catch(() => Swal.fire({ icon: "error", title: "Gagal!", text: "Terjadi kesalahan." }));
    }});
};

const deleteCharter = (id) => {
    Swal.fire({
        title: "Hapus Booking?",
        text: "Data yang dihapus tidak dapat dikembalikan!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
        confirmButtonText: "Ya, hapus!",
        cancelButtonText: "Batal",
    }).then((result) => {
        if (result.isConfirmed) {
            router.delete(route('admin.charter-bookings.destroy', id), {
                preserveScroll: true,
                onSuccess: () => {
                    localCharters.value.data = localCharters.value.data.filter(c => c.id !== id);
                    selectedIds.value = selectedIds.value.filter(sid => sid !== id);
                }
            });
        }
    });
};

const formatDate = (dateString) => {
    if (!dateString) return "-";
    return new Date(dateString).toLocaleDateString("id-ID", {
        day: "numeric",
        month: "short",
        year: "numeric"
    });
};

const formatRupiah = (value) => {
    if (!value) return "Rp 0";
    return new Intl.NumberFormat("id-ID", { style: "currency", currency: "IDR", minimumFractionDigits: 0 }).format(value);
};

const getStatusBadge = (status) => {
    switch (status) {
        case "pending": return "bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-400";
        case "quoted": return "bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400";
        case "confirmed": return "bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400";
        case "completed": return "bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-400";
        case "cancelled": return "bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400";
        default: return "bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-300";
    }
};

const getStatusText = (status) => {
    switch (status) {
        case "pending": return "Menunggu Harga";
        case "quoted": return "Menunggu DP";
        case "confirmed": return "Dikonfirmasi";
        case "completed": return "Selesai";
        case "cancelled": return "Dibatalkan";
        default: return status;
    }
};

const getPaymentStatusText = (status) => {
    switch (status) {
        case "unpaid": return "Belum Dibayar";
        case "pending": return "Menunggu";
        case "dp_paid": return "DP Lunas";
        case "paid": return "Lunas";
        case "fully_paid": return "Lunas";
        case "partial_paid": return "DP Lunas";
        case "failed": return "Gagal";
        default: return status;
    }
};
</script>

<template>
    <Head title="Pemesanan Pariwisata" />

    <AdminLayout title="Sewa Pariwisata">
        <div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold font-unbounded text-gray-900 dark:text-white">
                    Pemesanan Pariwisata
                </h2>
                <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">
                    Kelola penawaran harga, penugasan armada, dan status sewa pariwisata.
                </p>
            </div>
            <div class="flex items-center gap-3 w-full md:w-auto">
                <Link :href="route('admin.charter-bookings.create')" class="px-4 py-2.5 bg-brand-red text-white text-sm font-medium rounded-xl hover:bg-red-700 transition-colors flex items-center gap-2 whitespace-nowrap shadow-sm shadow-red-500/20">
                    <i class="fas fa-plus"></i> Tambah Manual
                </Link>

                <div class="relative flex-grow md:w-64 lg:w-80">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                    <input
                        v-model="search"
                        type="text"
                        placeholder="Cari kode atau nama..."
                        class="w-full pl-10 pr-4 py-2 border border-gray-200 dark:border-gray-700 bg-white dark:bg-[#151515] rounded-xl focus:ring-2 focus:ring-brand-red focus:border-brand-red text-sm dark:text-white"
                    />
                </div>
                <button
                    v-if="selectedIds.length > 0"
                    @click="bulkDelete"
                    class="px-4 py-2 bg-red-100 text-red-600 dark:bg-red-900/30 dark:text-red-400 font-medium rounded-xl hover:bg-red-200 dark:hover:bg-red-900/50 transition-colors flex items-center gap-2 whitespace-nowrap"
                >
                    <i class="fas fa-trash-alt"></i> Hapus ({{ selectedIds.length }})
                </button>
            </div>
        </div>

        <div class="bg-white dark:bg-[#151515] rounded-2xl border border-gray-100 dark:border-white/5 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-gray-500 dark:text-gray-400">
                    <thead class="bg-gray-50/50 dark:bg-[#1a1a1a] text-xs font-semibold uppercase text-gray-700 dark:text-gray-300">
                        <tr>
                            <th class="px-6 py-4 w-10">
                                <input type="checkbox" :checked="selectAll" @change="selectAll = !selectAll" class="rounded border-gray-300 text-brand-red shadow-sm focus:ring-brand-red bg-white dark:bg-[#111] dark:border-gray-700" />
                            </th>
                            <th class="px-6 py-4">Kode / Pelanggan</th>
                            <th class="px-6 py-4">Rute & Tanggal</th>
                            <th class="px-6 py-4">Tipe Bus</th>
                            <th class="px-6 py-4">Harga / DP</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                        <tr
                            v-for="charter in localCharters.data"
                            :key="charter.id"
                            class="hover:bg-gray-50/50 dark:hover:bg-white/[0.02] transition-colors"
                        >
                            <td class="px-6 py-4">
                                <input type="checkbox" v-model="selectedIds" :value="charter.id" class="rounded border-gray-300 text-brand-red shadow-sm focus:ring-brand-red bg-white dark:bg-[#111] dark:border-gray-700" />
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-900 dark:text-white">
                                    {{ charter.charter_code }}
                                </div>
                                <div class="text-xs text-gray-500 mt-0.5">
                                    {{ charter.user?.name || "User Terhapus" }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-900 dark:text-white">
                                    {{ charter.pickup_location }} <i class="fas fa-arrow-right mx-1 text-[10px] text-gray-400"></i> {{ charter.destination }}
                                </div>
                                <div class="text-xs text-gray-500 mt-0.5">
                                    {{ formatDate(charter.pickup_date) }} - {{ formatDate(charter.return_date) }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 rounded-md bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 text-xs font-medium border border-gray-200 dark:border-gray-700">
                                    {{ charter.bus_type_requested }}
                                </span>
                                <div class="text-xs text-gray-500 mt-1" v-if="charter.assigned_bus_id">
                                    Armada: {{ charter.assigned_bus?.plate_number }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-900 dark:text-white">
                                    {{ charter.total_price > 0 ? formatRupiah(charter.total_price) : 'Belum Ditentukan' }}
                                </div>
                                <div class="text-xs text-gray-500 mt-0.5" v-if="charter.total_price > 0">
                                    DP: {{ formatRupiah(charter.down_payment) }} ({{ getPaymentStatusText(charter.payment_status) }})
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span
                                    class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium"
                                    :class="getStatusBadge(charter.status)"
                                >
                                    {{ getStatusText(charter.status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right flex justify-end gap-2">
                                <Link
                                    :href="route('admin.charter-bookings.show', charter.id)"
                                    class="w-8 h-8 rounded-lg bg-gray-50 dark:bg-gray-800 text-gray-600 dark:text-gray-400 flex items-center justify-center hover:bg-brand-red hover:text-white transition-colors"
                                    title="Detail & Update"
                                >
                                    <i class="fas fa-eye text-sm"></i>
                                </Link>
                                <button
                                    @click="deleteCharter(charter.id)"
                                    class="w-8 h-8 rounded-lg bg-gray-50 dark:bg-gray-800 text-gray-600 dark:text-gray-400 flex items-center justify-center hover:bg-red-600 hover:text-white transition-colors"
                                    title="Hapus"
                                >
                                    <i class="fas fa-trash text-sm"></i>
                                </button>
                            </td>
                        </tr>
                        <tr v-if="localCharters.data.length === 0">
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                <div class="w-16 h-16 mx-auto bg-gray-50 dark:bg-[#111] rounded-2xl flex items-center justify-center mb-3">
                                    <i class="fas fa-umbrella-beach text-2xl text-gray-400"></i>
                                </div>
                                <p>Tidak ada pesanan pariwisata ditemukan.</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-800 flex items-center justify-between" v-if="localCharters.last_page > 1">
                <span class="text-sm text-gray-500">
                    Menampilkan {{ localCharters.from }} sampai {{ localCharters.to }} dari {{ localCharters.total }}
                </span>
                <div class="flex gap-1">
                    <button
                        v-for="(link, index) in localCharters.links"
                        :key="index"
                        @click="fetchPage(link.url)"
                        :disabled="!link.url || link.active"
                        class="px-3 py-1.5 rounded-lg text-sm font-medium transition-colors"
                        :class="[
                            link.active
                                ? 'bg-brand-red text-white'
                                : 'bg-gray-50 text-gray-600 hover:bg-gray-100 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700',
                            !link.url && 'opacity-50 cursor-not-allowed'
                        ]"
                        v-html="link.label"
                    ></button>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
