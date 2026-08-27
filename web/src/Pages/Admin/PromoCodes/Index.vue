<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { Head, Link, router } from "@inertiajs/vue3";
import { ref, watch } from "vue";
import Swal from "sweetalert2";
import axios from "axios";
import { useBulkDelete } from "@/Composables/useBulkDelete.js";

const props = defineProps({
    promoCodes: Object,
    filters: Object,
});

const search = ref(props.filters?.search || "");
const localPromoCodes = ref(props.promoCodes);
const { selectedIds, selectAll } = useBulkDelete(localPromoCodes);
let timeout = null;

const bulkDelete = () => {
    if (selectedIds.value.length === 0) return;
    Swal.fire({ title: `Hapus ${selectedIds.value.length} promo?`, text: "Data yang dihapus tidak dapat dikembalikan!", icon: "warning",
        showCancelButton: true, confirmButtonColor: "#d33", cancelButtonColor: "#3085d6",
        confirmButtonText: "Ya, hapus semua!", cancelButtonText: "Batal",
    }).then((r) => { if (r.isConfirmed) {
        axios.post(route("admin.promo-codes.bulk-destroy"), { ids: selectedIds.value, _method: "DELETE" })
            .then(() => {
                Swal.fire({ icon: "success", title: "Berhasil!", text: `${selectedIds.value.length} promo dihapus.`, timer: 1500, showConfirmButton: false });
                localPromoCodes.value = { ...localPromoCodes.value, data: localPromoCodes.value.data.filter(d => !selectedIds.value.includes(d.id)), total: localPromoCodes.value.total - selectedIds.value.length };
                selectedIds.value = [];
            }).catch(() => Swal.fire({ icon: "error", title: "Gagal!", text: "Terjadi kesalahan." }));
    }});
};// Search via Axios (No Inertia Reload)
watch(search, (value) => {
    clearTimeout(timeout);
    timeout = setTimeout(async () => {
        try {
            const { data } = await axios.get(route("admin.promo-codes.index"), {
                params: { search: value },
                headers: { Accept: "application/json" },
            });
            localPromoCodes.value = data.promoCodes;

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
        localPromoCodes.value = data.promoCodes;

        // Sync URL with pagination parameter
        window.history.replaceState({}, "", url);
        window.scrollTo({ top: 0, behavior: "smooth" });
    } catch (error) {
        console.error("Pagination failed:", error);
    }
};

const deletePromoCode = (id) => {
    Swal.fire({
        title: "Apakah Anda yakin?",
        text: "Kode promo yang dihapus tidak dapat dikembalikan!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
        confirmButtonText: "Ya, hapus!",
        cancelButtonText: "Batal",
    }).then((result) => {
        if (result.isConfirmed) {
            router.delete(route("admin.promo-codes.destroy", id), {
                preserveScroll: true,
                onSuccess: () => {
                    localPromoCodes.value = { ...localPromoCodes.value, data: localPromoCodes.value.data.filter(d => d.id !== id), total: localPromoCodes.value.total - 1 };
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
        maximumFractionDigits: 0,
    }).format(value);
};

const formatDate = (dateString) => {
    if (!dateString) return "-";
    return new Date(dateString).toLocaleDateString("id-ID", {
        day: "numeric",
        month: "short",
        year: "numeric",
    });
};
</script>

<template>
    <Head title="Manajemen Promo" />

    <AdminLayout title="Manajemen Promo">
        <div
            class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8"
        >
            <div>
                <h2
                    class="text-2xl font-bold text-gray-900 dark:text-white font-serif"
                >
                    Kode Promo
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    Kelola kode promo dan diskon untuk pelanggan.
                </p>
            </div>

            <div class="flex items-center gap-3">
                <div class="relative">
                    <input
                        type="text"
                        v-model="search"
                        placeholder="Cari kode..."
                        class="pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm focus:ring-2 focus:ring-brand-red/50 outline-none transition-all w-full md:w-64"
                    />
                    <div
                        class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400"
                    >
                        <i class="fas fa-search"></i>
                    </div>
                </div>

                <button v-if="selectedIds.length > 0" @click="bulkDelete" class="px-4 py-2.5 rounded-xl bg-red-600 text-white font-semibold shadow-sm hover:bg-red-700 transition-all flex items-center gap-2 whitespace-nowrap text-sm"><i class="fas fa-trash-alt"></i> Hapus ({{ selectedIds.length }})</button>
                <Link
                    :href="route('admin.promo-codes.create')"
                    class="px-5 py-2.5 rounded-xl bg-brand-red text-white font-semibold shadow-lg shadow-brand-red/30 hover:bg-red-700 hover:shadow-brand-red/50 transition-all duration-300 flex items-center gap-2 whitespace-nowrap"
                >
                    <i class="fas fa-plus"></i>
                    <span class="hidden md:inline">Buat Promo</span>
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
                            <th class="px-6 py-4">Kode</th>
                            <th class="px-6 py-4">Diskon</th>
                            <th class="px-6 py-4">Masa Berlaku</th>
                            <th class="px-6 py-4">Penggunaan</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody
                        class="divide-y divide-gray-100 dark:divide-gray-700/50"
                    >
                        <tr v-for="promo in localPromoCodes?.data" :key="promo.id"
                            class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors"
                            :class="{'bg-brand-red/5': selectedIds.includes(promo.id)}">
                            <td class="px-4 py-4">
                                <input type="checkbox" :value="promo.id" v-model="selectedIds" class="w-4 h-4 rounded border-gray-300 text-brand-red focus:ring-brand-red cursor-pointer" />
                            </td>
                            <td class="px-6 py-4">
                                <div>
                                    <p
                                        class="font-bold text-gray-900 dark:text-white text-sm font-mono tracking-wider"
                                    >
                                        {{ promo.code }}
                                    </p>
                                    <p
                                        class="text-xs text-gray-500 mt-0.5 truncate max-w-[200px]"
                                    >
                                        {{ promo.description || "-" }}
                                    </p>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold"
                                    :class="
                                        promo.discount_type === 'percentage'
                                            ? 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300'
                                            : 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300'
                                    "
                                >
                                    {{
                                        promo.discount_type === "percentage"
                                            ? promo.discount_amount + "%"
                                            : formatCurrency(
                                                  promo.discount_amount,
                                              )
                                    }}
                                </span>
                                <div
                                    v-if="promo.min_purchase_amount > 0"
                                    class="text-xs text-gray-400 mt-1"
                                >
                                    Min:
                                    {{
                                        formatCurrency(
                                            promo.min_purchase_amount,
                                        )
                                    }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div
                                    class="text-xs text-gray-600 dark:text-gray-300 flex flex-col gap-1"
                                >
                                    <span v-if="promo.start_date"
                                        >Mulai:
                                        {{ formatDate(promo.start_date) }}</span
                                    >
                                    <span v-else>Mulai: -</span>
                                    <span v-if="promo.end_date"
                                        >Selesai:
                                        {{ formatDate(promo.end_date) }}</span
                                    >
                                    <span v-else>Selesai: -</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div
                                    class="text-sm text-gray-700 dark:text-gray-300"
                                >
                                    {{ promo.usage_count }}
                                    <span v-if="promo.usage_limit"
                                        >/ {{ promo.usage_limit }}</span
                                    >
                                </div>
                                <div
                                    v-if="promo.usage_limit"
                                    class="w-24 h-1.5 bg-gray-200 rounded-full mt-1 overflow-hidden"
                                >
                                    <div
                                        class="h-full bg-brand-red rounded-full"
                                        :style="{
                                            width:
                                                Math.min(
                                                    (promo.usage_count /
                                                        promo.usage_limit) *
                                                        100,
                                                    100,
                                                ) + '%',
                                        }"
                                    ></div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span
                                    :class="[
                                        'px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-wide',
                                        promo.is_active
                                            ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400 ring-1 ring-green-500/20'
                                            : 'bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-400 ring-1 ring-gray-500/20',
                                    ]"
                                >
                                    {{
                                        promo.is_active ? "Aktif" : "Non-Aktif"
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
                                                'admin.promo-codes.edit',
                                                promo.id,
                                            )
                                        "
                                        class="p-2 rounded-lg text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-colors tooltip"
                                        title="Edit"
                                    >
                                        <i class="fas fa-edit"></i>
                                    </Link>
                                    <button
                                        @click="deletePromoCode(promo.id)"
                                        class="p-2 rounded-lg text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors tooltip"
                                        title="Hapus"
                                    >
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="localPromoCodes?.data?.length === 0">
                            <td
                                colspan="7"
                                class="px-6 py-12 text-center text-gray-400"
                            >
                                <div class="flex flex-col items-center">
                                    <i
                                        class="fas fa-ticket-alt text-4xl mb-3 opacity-30"
                                    ></i>
                                    <p>Belum ada kode promo.</p>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div
                class="px-6 py-4 border-t border-gray-100 dark:border-gray-700 flex items-center justify-between"
                v-if="localPromoCodes?.links?.length > 3"
            >
                <div class="text-xs text-gray-500">
                    Menampilkan {{ localPromoCodes.from }} -
                    {{ localPromoCodes.to }} dari
                    {{ localPromoCodes.total }} data
                </div>
                <div class="flex gap-1">
                    <button
                        v-for="(link, k) in localPromoCodes.links"
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
