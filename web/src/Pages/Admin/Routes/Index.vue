<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { Head, Link, router } from "@inertiajs/vue3";
import { ref, watch } from "vue";
import Swal from "sweetalert2";
import axios from "axios";
import { useBulkDelete } from "@/Composables/useBulkDelete.js";

const props = defineProps({
    routes: Object,
    filters: Object,
});

const search = ref(props.filters?.search || "");
const localRoutes = ref(props.routes);
const { selectedIds, selectAll } = useBulkDelete(localRoutes);
let timeout = null;

watch(search, (value) => {
    clearTimeout(timeout);
    timeout = setTimeout(async () => {
        try {
            const { data } = await axios.get(route("admin.routes.index"), { params: { search: value }, headers: { Accept: "application/json" } });
            localRoutes.value = data.routes;
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
        localRoutes.value = data.routes;
        window.history.replaceState({}, "", url);
        window.scrollTo({ top: 0, behavior: "smooth" });
    } catch (error) { console.error("Pagination failed:", error); }
};

const bulkDelete = () => {
    if (selectedIds.value.length === 0) return;
    Swal.fire({
        title: `Hapus ${selectedIds.value.length} rute?`,
        text: "Data yang dihapus tidak dapat dikembalikan!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
        confirmButtonText: "Ya, hapus semua!",
        cancelButtonText: "Batal",
    }).then((result) => {
        if (result.isConfirmed) {
            axios.post(route("admin.routes.bulk-destroy"), { ids: selectedIds.value, _method: "DELETE" })
                .then(() => {
                    Swal.fire({ icon: "success", title: "Berhasil!", text: `${selectedIds.value.length} rute dihapus.`, timer: 1500, showConfirmButton: false });
                    localRoutes.value = { ...localRoutes.value, data: localRoutes.value.data.filter(d => !selectedIds.value.includes(d.id)), total: localRoutes.value.total - selectedIds.value.length };
                    selectedIds.value = [];
                }).catch(() => Swal.fire({ icon: "error", title: "Gagal!", text: "Terjadi kesalahan." }));
        }
    });
};

const deleteRoute = (id) => {
    Swal.fire({
        title: "Apakah Anda yakin?",
        text: "Data rute yang dihapus tidak dapat dikembalikan!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
        confirmButtonText: "Ya, hapus!",
        cancelButtonText: "Batal",
    }).then((result) => {
        if (result.isConfirmed) {
            router.delete(route("admin.routes.destroy", id), {
                preserveScroll: true,
                onSuccess: () => {
                    localRoutes.value = { ...localRoutes.value, data: localRoutes.value.data.filter(d => d.id !== id), total: localRoutes.value.total - 1 };
                    selectedIds.value = selectedIds.value.filter(sid => sid !== id);
                },
            });
        }
    });
};

const formatDuration = (minutes) => {
    if (!minutes) return "-";
    const hours = Math.floor(minutes / 60);
    const mins = minutes % 60;
    if (hours > 0) {
        return `${hours} Jam ${mins} Menit`;
    }
    return `${mins} Menit`;
};
</script>

<template>
    <Head title="Manajemen Rute" />

    <AdminLayout title="Manajemen Rute">
        <div
            class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8"
        >
            <div>
                <h2
                    class="text-2xl font-bold text-gray-900 dark:text-white font-serif"
                >
                    Daftar Rute Perjalanan
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    Kelola rute perjalanan armada bus.
                </p>
            </div>

            <div class="flex items-center gap-3">
                <div class="relative">
                    <input
                        type="text"
                        v-model="search"
                        placeholder="Cari rute..."
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
                <Link :href="route('admin.routes.create')"
                    class="px-5 py-2.5 rounded-xl bg-brand-red text-white font-semibold shadow-lg shadow-brand-red/30 hover:bg-red-700 hover:shadow-brand-red/50 transition-all duration-300 flex items-center gap-2 whitespace-nowrap"
                >
                    <i class="fas fa-plus"></i>
                    <span class="hidden md:inline">Tambah Rute</span>
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
                            <th class="px-6 py-4">Nama Rute</th>
                            <th class="px-6 py-4">Asal & Tujuan</th>
                            <th class="px-6 py-4">Jarak & Durasi</th>
                            <th class="px-6 py-4">Deskripsi</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody
                        class="divide-y divide-gray-100 dark:divide-gray-700/50"
                    >
                        <tr v-for="routeItem in localRoutes?.data" :key="routeItem.id"
                            class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors"
                            :class="{'bg-brand-red/5': selectedIds.includes(routeItem.id)}">
                            <td class="px-4 py-4">
                                <input type="checkbox" :value="routeItem.id" v-model="selectedIds" class="w-4 h-4 rounded border-gray-300 text-brand-red focus:ring-brand-red cursor-pointer" />
                            </td>
                            <td class="px-6 py-4">
                                <div
                                    class="font-bold text-gray-900 dark:text-white text-sm"
                                >
                                    {{ routeItem.name }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-col gap-1">
                                    <div
                                        class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300"
                                    >
                                        <i
                                            class="fas fa-map-marker-alt text-green-500 w-4"
                                        ></i>
                                        <span>{{ routeItem.origin }}</span>
                                    </div>
                                    <div
                                        class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300"
                                    >
                                        <i
                                            class="fas fa-flag-checkered text-red-500 w-4"
                                        ></i>
                                        <span>{{ routeItem.destination }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-col gap-1">
                                    <div
                                        class="text-sm text-gray-700 dark:text-gray-300"
                                        v-if="routeItem.distance"
                                    >
                                        {{ routeItem.distance }} km
                                    </div>
                                    <div
                                        class="text-xs text-gray-500"
                                        v-if="routeItem.duration"
                                    >
                                        <i class="far fa-clock mr-1"></i>
                                        {{ formatDuration(routeItem.duration) }}
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <p
                                    class="text-sm text-gray-600 dark:text-gray-400 line-clamp-2"
                                    :title="routeItem.description"
                                >
                                    {{ routeItem.description || "-" }}
                                </p>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div
                                    class="flex items-center justify-end gap-2"
                                >
                                    <Link
                                        :href="
                                            route(
                                                'admin.routes.edit',
                                                routeItem.id,
                                            )
                                        "
                                        class="p-2 rounded-lg text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-colors tooltip"
                                        title="Edit"
                                    >
                                        <i class="fas fa-edit"></i>
                                    </Link>
                                    <button
                                        @click="deleteRoute(routeItem.id)"
                                        class="p-2 rounded-lg text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors tooltip"
                                        title="Hapus"
                                    >
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="localRoutes?.data?.length === 0">
                            <td
                                colspan="6"
                                class="px-6 py-12 text-center text-gray-400"
                            >
                                <div class="flex flex-col items-center">
                                    <i
                                        class="fas fa-route text-4xl mb-3 opacity-30"
                                    ></i>
                                    <p>Belum ada data rute.</p>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div
                class="px-6 py-4 border-t border-gray-100 dark:border-gray-700 flex items-center justify-between"
                v-if="localRoutes?.links?.length > 3"
            >
                <div class="text-xs text-gray-500">
                    Menampilkan {{ localRoutes.from }} -
                    {{ localRoutes.to }} dari {{ localRoutes.total }} data
                </div>
                <div class="flex gap-1">
                    <button
                        v-for="(link, k) in localRoutes.links"
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
