<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { Head, Link, router } from "@inertiajs/vue3";
import { ref, watch } from "vue";
import Swal from "sweetalert2";
import axios from "axios";
import { useBulkDelete } from "@/Composables/useBulkDelete.js";

const props = defineProps({
    schedules: Object,
    filters: Object,
});

const search = ref(props.filters?.search || "");
const localSchedules = ref(props.schedules);
const { selectedIds, selectAll } = useBulkDelete(localSchedules);
let timeout = null;

const bulkDelete = () => {
    if (selectedIds.value.length === 0) return;
    Swal.fire({
        title: `Hapus ${selectedIds.value.length} jadwal?`,
        text: "Data yang dihapus tidak dapat dikembalikan!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
        confirmButtonText: "Ya, hapus semua!",
        cancelButtonText: "Batal",
    }).then((result) => {
        if (result.isConfirmed) {
            axios.delete(route("admin.schedules.bulk-destroy"), { data: { ids: selectedIds.value } })
                .then(() => {
                    Swal.fire({ icon: "success", title: "Berhasil!", text: `${selectedIds.value.length} jadwal dihapus.`, timer: 1500, showConfirmButton: false });
                    localSchedules.value = {
                        ...localSchedules.value,
                        data: localSchedules.value.data.filter(s => !selectedIds.value.includes(s.id)),
                        total: localSchedules.value.total - selectedIds.value.length,
                    };
                    selectedIds.value = [];
                }).catch(() => Swal.fire({ icon: "error", title: "Gagal!", text: "Terjadi kesalahan." }));
        }
    });
};

// Search via Axios (No Inertia Reload)
watch(search, (value) => {
    clearTimeout(timeout);
    timeout = setTimeout(async () => {
        try {
            const { data } = await axios.get(route("admin.schedules.index"), {
                params: { search: value },
                headers: { Accept: "application/json" },
            });
            localSchedules.value = data.schedules;

            // Sync URL optionally without reloading
            const newUrl = new URL(window.location.href);
            if (value) {
                newUrl.searchParams.set("search", value);
            } else {
                newUrl.searchParams.delete("search");
            }
            window.history.replaceState({}, "", newUrl);
        } catch (error) {
            console.error("Search failed:", error);
        }
    }, 500);
});

// Pagination via Axios (No Inertia Reload)
const fetchPage = async (url) => {
    if (!url) return;
    try {
        const { data } = await axios.get(url, {
            headers: { Accept: "application/json" },
        });
        localSchedules.value = data.schedules;

        // Sync URL with pagination parameter
        window.history.replaceState({}, "", url);
        window.scrollTo({ top: 0, behavior: "smooth" });
    } catch (error) {
        console.error("Pagination failed:", error);
    }
};

const deleteSchedule = (id) => {
    Swal.fire({
        title: "Apakah Anda yakin?",
        text: "Data jadwal yang dihapus tidak dapat dikembalikan!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
        confirmButtonText: "Ya, hapus!",
        cancelButtonText: "Batal",
    }).then((result) => {
        if (result.isConfirmed) {
            router.delete(route("admin.schedules.destroy", id), {
                preserveScroll: true,
                onSuccess: () => {
                    localSchedules.value = {
                        ...localSchedules.value,
                        data: localSchedules.value.data.filter(s => s.id !== id),
                        total: localSchedules.value.total - 1,
                    };
                    selectedIds.value = selectedIds.value.filter(sid => sid !== id);
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

const getStatusBadgeClass = (status) => {
    switch (status) {
        case "active":
            return "bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400 ring-1 ring-green-500/20";
        case "delayed":
            return "bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400 ring-1 ring-amber-500/20";
        case "cancelled":
            return "bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400 ring-1 ring-red-500/20";
        default:
            return "bg-gray-100 text-gray-700";
    }
};

const getStatusLabel = (status) => {
    switch (status) {
        case "active":
            return "Aktif";
        case "delayed":
            return "Tertunda";
        case "cancelled":
            return "Dibatalkan";
        default:
            return status;
    }
};
</script>

<template>
    <Head title="Manajemen Jadwal & Tarif" />

    <AdminLayout title="Manajemen Jadwal">
        <div
            class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8"
        >
            <div>
                <h2
                    class="text-2xl font-bold text-gray-900 dark:text-white font-serif"
                >
                    Jadwal Keberangkatan
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    Kelola jadwal perjalanan, tarif tiket, dan ketersediaan
                    armada.
                </p>
            </div>

            <div class="flex items-center gap-3">
                <div class="relative">
                    <input type="text" v-model="search" placeholder="Cari (Bus/Rute)..."
                        class="pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm focus:ring-2 focus:ring-brand-red/50 outline-none transition-all w-full md:w-64" />
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                        <i class="fas fa-search"></i>
                    </div>
                </div>
                <button v-if="selectedIds.length > 0" @click="bulkDelete"
                    class="px-4 py-2.5 rounded-xl bg-red-600 text-white font-semibold shadow-sm hover:bg-red-700 transition-all flex items-center gap-2 whitespace-nowrap text-sm">
                    <i class="fas fa-trash-alt"></i> Hapus ({{ selectedIds.length }})
                </button>
                <Link :href="route('admin.schedules.create')"
                    class="px-5 py-2.5 rounded-xl bg-brand-red text-white font-semibold shadow-lg shadow-brand-red/30 hover:bg-red-700 hover:shadow-brand-red/50 transition-all duration-300 flex items-center gap-2 whitespace-nowrap">
                    <i class="fas fa-plus"></i>
                    <span class="hidden md:inline">Buat Jadwal</span>
                </Link>
            </div>
        </div>

        <div
            class="bg-white dark:bg-gray-800 rounded-3xl shadow-xl shadow-gray-100/50 dark:shadow-black/30 border border-gray-100 dark:border-gray-700/50 overflow-hidden"
        >
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-gray-50/50 dark:bg-gray-900/20 text-gray-500 dark:text-gray-400 text-xs uppercase font-bold tracking-wider">
                        <tr>
                            <th class="px-4 py-4 w-10">
                                <input type="checkbox" v-model="selectAll"
                                    class="w-4 h-4 rounded border-gray-300 text-brand-red focus:ring-brand-red cursor-pointer" />
                            </th>
                            <th class="px-6 py-4">Armada & Rute</th>
                            <th class="px-6 py-4">Waktu Keberangkatan</th>
                            <th class="px-6 py-4">Tarif</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody
                        class="divide-y divide-gray-100 dark:divide-gray-700/50"
                    >
                    <tr v-for="schedule in localSchedules?.data" :key="schedule.id"
                        class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors"
                        :class="{'bg-brand-red/5': selectedIds.includes(schedule.id)}">
                        <td class="px-4 py-4">
                            <input type="checkbox" :value="schedule.id" v-model="selectedIds"
                                class="w-4 h-4 rounded border-gray-300 text-brand-red focus:ring-brand-red cursor-pointer" />
                        </td>
                        <td class="px-6 py-4">
                                <div class="flex flex-col gap-1">
                                    <div
                                        class="font-bold text-gray-900 dark:text-white text-sm flex items-center gap-2"
                                    >
                                        <i class="fas fa-bus text-gray-400"></i>
                                        <span>{{
                                            schedule.bus
                                                ? schedule.bus.name
                                                : "Unknown Bus"
                                        }}</span>
                                    </div>
                                    <div
                                        class="text-xs text-gray-500 flex items-center gap-2"
                                    >
                                        <i
                                            class="fas fa-route text-gray-400"
                                        ></i>
                                        <span>{{
                                            schedule.route
                                                ? schedule.route.name
                                                : "Unknown Route"
                                        }}</span>
                                    </div>
                                    <div
                                        v-if="
                                            schedule.schedule_type ===
                                            'daily_recurring'
                                        "
                                        class="mt-1"
                                    >
                                        <span
                                            class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400 uppercase tracking-wide"
                                        >
                                            Rutin (Harian)
                                        </span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-col gap-1">
                                    <div
                                        class="text-sm font-semibold text-gray-900 dark:text-white"
                                    >
                                        {{ schedule.time_only_departure }}
                                        <span
                                            class="text-xs font-normal text-gray-500"
                                            >WIB</span
                                        >
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        {{
                                            schedule.formatted_departure
                                                .split(" ")
                                                .slice(0, 3)
                                                .join(" ")
                                        }}
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-brand-red text-sm">
                                    {{ formatCurrency(schedule.price) }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span
                                    :class="[
                                        'px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-wide',
                                        getStatusBadgeClass(schedule.status),
                                    ]"
                                >
                                    {{ getStatusLabel(schedule.status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div
                                    class="flex items-center justify-end gap-2"
                                >
                                    <Link
                                        :href="
                                            route(
                                                'admin.schedules.edit',
                                                schedule.id,
                                            )
                                        "
                                        class="p-2 rounded-lg text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-colors tooltip"
                                        title="Edit"
                                        v-if="schedule.status !== 'departed'"
                                    >
                                        <i class="fas fa-edit"></i>
                                    </Link>
                                    <button
                                        @click="deleteSchedule(schedule.id)"
                                        class="p-2 rounded-lg text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors tooltip"
                                        title="Hapus"
                                    >
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="localSchedules?.data?.length === 0">
                            <td
                                colspan="6"
                                class="px-6 py-12 text-center text-gray-400"
                            >
                                <div class="flex flex-col items-center">
                                    <i
                                        class="fas fa-calendar-alt text-4xl mb-3 opacity-30"
                                    ></i>
                                    <p>Belum ada jadwal keberangkatan.</p>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div
                class="px-6 py-4 border-t border-gray-100 dark:border-gray-700 flex items-center justify-between"
                v-if="localSchedules?.links?.length > 3"
            >
                <div class="text-xs text-gray-500">
                    Menampilkan {{ localSchedules.from }} -
                    {{ localSchedules.to }} dari {{ localSchedules.total }} data
                </div>
                <div class="flex gap-1">
                    <button
                        v-for="(link, k) in localSchedules.links"
                        :key="k"
                        @click.prevent="fetchPage(link.url)"
                        v-html="link.label"
                        :disabled="!link.url"
                        :class="[
                            'px-3 py-1 rounded-lg text-xs font-bold transition-all',
                            link.active
                                ? 'bg-brand-red text-white shadow-md shadow-brand-red/20'
                                : 'text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700',
                            !link.url ? 'opacity-50 cursor-not-allowed' : '',
                        ]"
                    />
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
