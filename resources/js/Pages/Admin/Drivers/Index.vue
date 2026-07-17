<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { Head, Link, router } from "@inertiajs/vue3";
import { ref, watch } from "vue";
import Swal from "sweetalert2";
import axios from "axios";
import { useBulkDelete } from "@/Composables/useBulkDelete.js";

const props = defineProps({
    drivers: Object,
    filters: Object,
});

const search = ref(props.filters?.search || "");
const localDrivers = ref(props.drivers);
const { selectedIds, selectAll } = useBulkDelete(localDrivers);
let timeout = null;

const applyFilters = async () => {
    try {
        const { data } = await axios.get(route("admin.drivers.index"), { params: { search: search.value || "" }, headers: { Accept: "application/json" } });
        localDrivers.value = data.drivers;
        const newUrl = new URL(window.location.href);
        if (search.value) newUrl.searchParams.set("search", search.value);
        else newUrl.searchParams.delete("search");
        window.history.replaceState({}, "", newUrl);
    } catch (error) { console.error("Filter failed:", error); }
};

watch(search, (value) => { clearTimeout(timeout); timeout = setTimeout(() => applyFilters(), 500); });

const fetchPage = async (url) => {
    if (!url) return;
    try {
        const { data } = await axios.get(url, { headers: { Accept: "application/json" } });
        localDrivers.value = data.drivers;
        window.history.replaceState({}, "", url);
        window.scrollTo({ top: 0, behavior: "smooth" });
    } catch (error) { console.error("Pagination failed:", error); }
};

const bulkDelete = () => {
    if (selectedIds.value.length === 0) return;
    Swal.fire({
        title: `Hapus ${selectedIds.value.length} driver?`,
        text: "Data yang dihapus tidak dapat dikembalikan!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
        confirmButtonText: "Ya, hapus semua!",
        cancelButtonText: "Batal",
    }).then((result) => {
        if (result.isConfirmed) {
            axios.post(route("admin.drivers.bulk-destroy"), { ids: selectedIds.value, _method: "DELETE" })
                .then(() => {
                    Swal.fire({ icon: "success", title: "Berhasil!", text: `${selectedIds.value.length} driver dihapus.`, timer: 1500, showConfirmButton: false });
                    localDrivers.value = { ...localDrivers.value, data: localDrivers.value.data.filter(d => !selectedIds.value.includes(d.id)), total: localDrivers.value.total - selectedIds.value.length };
                    selectedIds.value = [];
                }).catch(() => Swal.fire({ icon: "error", title: "Gagal!", text: "Terjadi kesalahan." }));
        }
    });
};

const deleteDriver = (id) => {
    Swal.fire({
        title: "Hapus Driver?",
        text: "Data yang dihapus tidak dapat dikembalikan!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
        confirmButtonText: "Ya, hapus!",
        cancelButtonText: "Batal",
    }).then((result) => {
        if (result.isConfirmed) {
            router.delete(route("admin.drivers.destroy", id), {
                preserveScroll: true,
                onSuccess: () => {
                    localDrivers.value = { ...localDrivers.value, data: localDrivers.value.data.filter(d => d.id !== id), total: localDrivers.value.total - 1 };
                    selectedIds.value = selectedIds.value.filter(sid => sid !== id);
                },
            });
        }
    });
};
</script>

<template>
    <Head title="Manajemen Driver" />

    <AdminLayout title="Manajemen Driver">
        <div
            class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8"
        >
            <div>
                <h2
                    class="text-2xl font-bold text-gray-900 dark:text-white font-serif"
                >
                    Daftar Supir (Driver)
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    Kelola data supir armada bus.
                </p>
            </div>

            <div class="flex items-center gap-3">
                <div class="relative">
                    <input
                        type="text"
                        v-model="search"
                        placeholder="Cari Nama/ID/SIM..."
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
                <Link :href="route('admin.drivers.create')"
                    class="px-5 py-2.5 rounded-xl bg-brand-red text-white font-semibold shadow-lg shadow-brand-red/30 hover:bg-red-700 hover:shadow-brand-red/50 transition-all duration-300 flex items-center gap-2 whitespace-nowrap"
                >
                    <i class="fas fa-plus"></i>
                    <span class="hidden md:inline">Tambah Driver</span>
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
                            <th class="px-6 py-4">Driver</th>
                            <th class="px-6 py-4">ID & SIM</th>
                            <th class="px-6 py-4">Kontak</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody
                        class="divide-y divide-gray-100 dark:divide-gray-700/50"
                    >
                        <tr v-for="driver in localDrivers?.data" :key="driver.id"
                            class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors"
                            :class="{'bg-brand-red/5': selectedIds.includes(driver.id)}">
                            <td class="px-4 py-4">
                                <input type="checkbox" :value="driver.id" v-model="selectedIds" class="w-4 h-4 rounded border-gray-300 text-brand-red focus:ring-brand-red cursor-pointer" />
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="h-10 w-10 rounded-full bg-gray-100 overflow-hidden flex-shrink-0"
                                    >
                                        <img
                                            v-if="driver.image_url"
                                            :src="driver.image_url"
                                            alt="Foto Driver"
                                            class="h-full w-full object-cover"
                                        />
                                        <div
                                            v-else
                                            class="h-full w-full flex items-center justify-center text-gray-400"
                                        >
                                            <i class="fas fa-user"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div
                                            class="font-bold text-gray-900 dark:text-white"
                                        >
                                            {{ driver.name }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div
                                    class="text-sm font-medium text-gray-900 dark:text-white"
                                >
                                    {{ driver.employee_id }}
                                </div>
                                <div class="text-xs text-gray-500">
                                    SIM: {{ driver.license_number }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div
                                    class="text-sm text-gray-600 dark:text-gray-300"
                                >
                                    {{ driver.phone }}
                                </div>
                                <div class="text-xs text-gray-500">
                                    {{ driver.email || "-" }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span
                                    :class="[
                                        'px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-wide',
                                        driver.status === 'active'
                                            ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400'
                                            : 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400',
                                    ]"
                                >
                                    {{
                                        driver.status === "active"
                                            ? "Aktif"
                                            : "Tidak Aktif"
                                    }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div
                                    class="flex items-center justify-end gap-2"
                                >
                                    <Link
                                        :href="
                                            route(
                                                'admin.drivers.edit',
                                                driver.id,
                                            )
                                        "
                                        class="p-2 rounded-lg text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-colors tooltip"
                                        title="Edit"
                                    >
                                        <i class="fas fa-edit"></i>
                                    </Link>
                                    <button
                                        @click="deleteDriver(driver.id)"
                                        class="p-2 rounded-lg text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors tooltip"
                                        title="Hapus"
                                    >
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="localDrivers?.data?.length === 0">
                            <td
                                colspan="6"
                                class="px-6 py-12 text-center text-gray-400"
                            >
                                <div class="flex flex-col items-center">
                                    <i
                                        class="fas fa-id-card text-4xl mb-3 opacity-30"
                                    ></i>
                                    <p>Belum ada data driver.</p>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div
                class="px-6 py-4 border-t border-gray-100 dark:border-gray-700 flex items-center justify-between"
                v-if="localDrivers?.links?.length > 3"
            >
                <div class="text-xs text-gray-500">
                    Menampilkan {{ localDrivers.from }} -
                    {{ localDrivers.to }} dari {{ localDrivers.total }} data
                </div>
                <div class="flex gap-1">
                    <template v-for="(link, k) in localDrivers.links" :key="k">
                        <button
                            v-if="link.url"
                            @click.prevent="fetchPage(link.url)"
                            v-html="link.label"
                            :class="[
                                'px-3 py-1 rounded-lg text-xs font-bold transition-all',
                                link.active
                                    ? 'bg-brand-red text-white shadow-md shadow-brand-red/20'
                                    : 'text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700',
                            ]"
                        />
                        <span
                            v-else
                            v-html="link.label"
                            class="px-3 py-1 rounded-lg text-xs font-bold text-gray-400 cursor-not-allowed opacity-50"
                        ></span>
                    </template>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
