<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { Head, Link, router, useForm } from "@inertiajs/vue3";
import { ref, watch } from "vue";
import Swal from "sweetalert2";
import axios from "axios";

import { useBulkDelete } from "@/Composables/useBulkDelete.js";

const props = defineProps({
    buses: Object,
    filters: Object,
});

const importForm = useForm({ file: null });
const importData = (e) => {
    importForm.file = e.target.files[0];
    if (!importForm.file) return;
    
    importForm.post(route('admin.import.store', 'buses'), {
        preserveScroll: true,
        onSuccess: () => {
            importForm.reset();
            // Just reload to see changes
            router.reload();
            e.target.value = '';
        }
    });
};

const search = ref(props.filters?.search || "");
const localBuses = ref(props.buses);
const { selectedIds, selectAll } = useBulkDelete(localBuses);
let timeout = null;

watch(search, (value) => {
    clearTimeout(timeout);
    timeout = setTimeout(async () => {
        try {
            const { data } = await axios.get(route("admin.buses.index"), {
                params: { search: value },
                headers: { Accept: "application/json" },
            });
            localBuses.value = data.buses;
            const newUrl = new URL(window.location.href);
            if (value) newUrl.searchParams.set("search", value);
            else newUrl.searchParams.delete("search");
            window.history.replaceState({}, "", newUrl);
        } catch (error) { console.error("Search failed:", error); }
    }, 500);
});

const fetchPage = async (url) => {
    if (!url) return;
    try {
        const { data } = await axios.get(url, { headers: { Accept: "application/json" } });
        localBuses.value = data.buses;
        window.history.replaceState({}, "", url);
        window.scrollTo({ top: 0, behavior: "smooth" });
    } catch (error) { console.error("Pagination failed:", error); }
};

const bulkDelete = () => {
    if (selectedIds.value.length === 0) return;
    Swal.fire({
        title: `Hapus ${selectedIds.value.length} bus?`,
        text: "Data yang dihapus tidak dapat dikembalikan!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
        confirmButtonText: "Ya, hapus semua!",
        cancelButtonText: "Batal",
    }).then((result) => {
        if (result.isConfirmed) {
            axios.post(route("admin.buses.bulk-destroy"), { ids: selectedIds.value, _method: "DELETE" })
                .then(() => {
                    Swal.fire({ icon: "success", title: "Berhasil!", text: `${selectedIds.value.length} bus dihapus.`, timer: 1500, showConfirmButton: false });
                    localBuses.value = {
                        ...localBuses.value,
                        data: localBuses.value.data.filter(b => !selectedIds.value.includes(b.id)),
                        total: localBuses.value.total - selectedIds.value.length,
                    };
                    selectedIds.value = [];
                }).catch(() => Swal.fire({ icon: "error", title: "Gagal!", text: "Terjadi kesalahan." }));
        }
    });
};

const deleteBus = (id) => {
    Swal.fire({
        title: "Apakah Anda yakin?",
        text: "Data bus yang dihapus tidak dapat dikembalikan!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
        confirmButtonText: "Ya, hapus!",
        cancelButtonText: "Batal",
    }).then((result) => {
        if (result.isConfirmed) {
            router.delete(route("admin.buses.destroy", id), {
                preserveScroll: true,
                onSuccess: () => {
                    localBuses.value = {
                        ...localBuses.value,
                        data: localBuses.value.data.filter(b => b.id !== id),
                        total: localBuses.value.total - 1,
                    };
                    selectedIds.value = selectedIds.value.filter(sid => sid !== id);
                },
            });
        }
    });
};

const getStatusBadgeClass = (status) => {
    switch (status) {
        case "active":
            return "bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400 ring-1 ring-green-500/20";
        case "maintenance":
            return "bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400 ring-1 ring-amber-500/20";
        case "inactive":
            return "bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-400 ring-1 ring-gray-500/20";
        default:
            return "bg-gray-100 text-gray-700";
    }
};

const getStatusLabel = (status) => {
    switch (status) {
        case "active":
            return "Aktif";
        case "maintenance":
            return "Perbaikan";
        case "inactive":
            return "Non-Aktif";
        default:
            return status;
    }
};
</script>

<template>
    <Head title="Manajemen Armada" />

    <AdminLayout title="Manajemen Armada">
        <div
            class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8"
        >
            <div>
                <h2
                    class="text-2xl font-bold text-gray-900 dark:text-white font-serif"
                >
                    Daftar Armada
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    Kelola data bus, status armada, dan penugasan kru.
                </p>
            </div>

            <div class="flex items-center gap-3">
                <div class="relative">
                    <input
                        type="text"
                        v-model="search"
                        placeholder="Cari armada..."
                        class="pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm focus:ring-2 focus:ring-brand-red/50 outline-none transition-all w-full md:w-64"
                    />
                    <div
                        class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400"
                    >
                        <i class="fas fa-search"></i>
                    </div>
                </div>

                <button v-if="selectedIds.length > 0" @click="bulkDelete"
                    class="px-4 py-2.5 rounded-xl bg-red-600 text-white font-semibold shadow-sm hover:bg-red-700 transition-all flex items-center gap-2 whitespace-nowrap text-sm">
                    <i class="fas fa-trash-alt"></i> Hapus ({{ selectedIds.length }})
                </button>
                <label class="px-5 py-2.5 rounded-xl bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 font-semibold hover:bg-gray-200 dark:hover:bg-gray-600 transition-all duration-300 flex items-center gap-2 cursor-pointer">
                    <i class="fas fa-file-csv"></i>
                    <span class="hidden md:inline">Import CSV</span>
                    <input type="file" class="hidden" accept=".csv" @change="importData" />
                </label>

                <Link :href="route('admin.buses.create')"
                    class="px-5 py-2.5 rounded-xl bg-brand-red text-white font-semibold shadow-lg shadow-brand-red/30 hover:bg-red-700 hover:shadow-brand-red/50 transition-all duration-300 flex items-center gap-2 whitespace-nowrap">
                    <i class="fas fa-plus"></i>
                    <span class="hidden md:inline">Tambah Bus</span>
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
                            <th class="px-4 py-4 w-10">
                                <input type="checkbox" v-model="selectAll" class="w-4 h-4 rounded border-gray-300 text-brand-red focus:ring-brand-red cursor-pointer" />
                            </th>
                            <th class="px-6 py-4">Armada</th>
                            <th class="px-6 py-4">Info Teknis</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4">Kru Bertugas</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody
                        class="divide-y divide-gray-100 dark:divide-gray-700/50"
                    >
                        <tr v-for="bus in localBuses?.data" :key="bus.id"
                            class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors"
                            :class="{'bg-brand-red/5': selectedIds.includes(bus.id)}">
                            <td class="px-4 py-4">
                                <input type="checkbox" :value="bus.id" v-model="selectedIds" class="w-4 h-4 rounded border-gray-300 text-brand-red focus:ring-brand-red cursor-pointer" />
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-4">
                                    <div
                                        class="h-12 w-16 flex-shrink-0 bg-gray-100 rounded-lg overflow-hidden"
                                    >
                                        <img
                                            v-if="bus.image_url"
                                            :src="bus.image_url"
                                            class="w-full h-full object-cover"
                                            alt="Bus Preview"
                                        />
                                        <div
                                            v-else
                                            class="w-full h-full flex items-center justify-center bg-gray-200 text-gray-400"
                                        >
                                            <i class="fas fa-bus"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <p
                                            class="font-bold text-gray-900 dark:text-white text-sm"
                                        >
                                            {{ bus.name }}
                                        </p>
                                        <p
                                            class="text-xs text-gray-500 font-mono mt-0.5"
                                        >
                                            {{ bus.plate_number }}
                                        </p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-col gap-1">
                                    <span
                                        class="text-sm text-gray-700 dark:text-gray-300"
                                        >{{ bus.bus_type }}</span
                                    >
                                    <div class="flex items-center gap-2">
                                        <span
                                            class="px-2 py-0.5 rounded-full text-[10px] font-bold"
                                            :class="bus.bus_category === 'pariwisata'
                                                ? 'bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400'
                                                : 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400'"
                                        >
                                            {{ bus.bus_category === 'pariwisata' ? 'Pariwisata' : 'AKAP' }}
                                        </span>
                                        <span class="text-xs text-gray-500"
                                            >{{ bus.capacity }} Kursi</span
                                        >
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span
                                    :class="[
                                        'px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-wide',
                                        getStatusBadgeClass(bus.status),
                                    ]"
                                >
                                    {{ getStatusLabel(bus.status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="space-y-1">
                                    <div
                                        v-if="
                                            bus.drivers &&
                                            bus.drivers.length > 0
                                        "
                                        class="flex items-center gap-2 text-xs text-gray-600 dark:text-gray-300"
                                    >
                                        <i
                                            class="fas fa-id-card w-4 text-gray-400"
                                        ></i>
                                        <span>{{
                                            bus.drivers
                                                .map((d) => d.name)
                                                .join(", ")
                                        }}</span>
                                    </div>
                                    <div
                                        v-else
                                        class="flex items-center gap-2 text-xs text-gray-400 italic"
                                    >
                                        <i class="fas fa-id-card w-4"></i>
                                        <span>Tanpa Sopir</span>
                                    </div>

                                    <div
                                        v-if="
                                            bus.conductors &&
                                            bus.conductors.length > 0
                                        "
                                        class="flex items-center gap-2 text-xs text-gray-600 dark:text-gray-300"
                                    >
                                        <i
                                            class="fas fa-user-tag w-4 text-gray-400"
                                        ></i>
                                        <span>{{
                                            bus.conductors
                                                .map((c) => c.name)
                                                .join(", ")
                                        }}</span>
                                    </div>
                                    <div
                                        v-else
                                        class="flex items-center gap-2 text-xs text-gray-400 italic"
                                    >
                                        <i class="fas fa-user-tag w-4"></i>
                                        <span>Tanpa Kondektur</span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div
                                    class="flex items-center justify-end gap-2"
                                >
                                    <Link
                                        :href="
                                            route('admin.buses.edit', bus.id)
                                        "
                                        class="p-2 rounded-lg text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-colors tooltip"
                                        title="Edit"
                                    >
                                        <i class="fas fa-edit"></i>
                                    </Link>
                                    <button
                                        @click="deleteBus(bus.id)"
                                        class="p-2 rounded-lg text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors tooltip"
                                        title="Hapus"
                                    >
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="localBuses?.data?.length === 0">
                            <td
                                colspan="6"
                                class="px-6 py-12 text-center text-gray-400"
                            >
                                <div class="flex flex-col items-center">
                                    <i
                                        class="fas fa-bus text-4xl mb-3 opacity-30"
                                    ></i>
                                    <p>Belum ada data armada.</p>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div
                class="px-6 py-4 border-t border-gray-100 dark:border-gray-700 flex items-center justify-between"
                v-if="localBuses?.links?.length > 3"
            >
                <div class="text-xs text-gray-500">
                    Menampilkan {{ localBuses.from }} - {{ localBuses.to }} dari
                    {{ localBuses.total }} data
                </div>
                <div class="flex gap-1">
                    <button
                        v-for="(link, k) in localBuses.links"
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
