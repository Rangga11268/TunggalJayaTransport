<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { Head, Link, router } from "@inertiajs/vue3";
import { ref, watch } from "vue";
import Swal from "sweetalert2";
import axios from "axios";

const props = defineProps({
    categories: Object,
    filters: Object,
});

const search = ref(props.filters?.search || "");
const localCategories = ref(props.categories); // Reactive local state for categories data
let timeout = null;

// Search via Axios (No Inertia Reload)
watch(search, (value) => {
    clearTimeout(timeout);
    timeout = setTimeout(async () => {
        try {
            const { data } = await axios.get(route("admin.categories.index"), {
                params: { search: value },
                headers: { Accept: "application/json" },
            });
            localCategories.value = data.categories;

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
        localCategories.value = data.categories;

        // Sync URL with pagination parameter
        window.history.replaceState({}, "", url);
        // Scroll to table top
        window.scrollTo({ top: 0, behavior: "smooth" });
    } catch (error) {
        console.error("Pagination failed:", error);
    }
};

const deleteCategory = (id) => {
    Swal.fire({
        title: "Apakah Anda yakin?",
        text: "Kategori yang dihapus tidak dapat dikembalikan!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
        confirmButtonText: "Ya, hapus!",
        cancelButtonText: "Batal",
    }).then((result) => {
        if (result.isConfirmed) {
            router.delete(route("admin.categories.destroy", id), {
                onSuccess: () => {},
            });
        }
    });
};
</script>

<template>
    <Head title="Manajemen Kategori" />

    <AdminLayout title="Manajemen Kategori">
        <div
            class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8"
        >
            <div>
                <h2
                    class="text-2xl font-bold text-gray-900 dark:text-white font-serif"
                >
                    Kategori Berita
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    Kelompokkan artikel berita agar mudah ditemukan.
                </p>
            </div>

            <div class="flex items-center gap-3">
                <div class="relative">
                    <input
                        type="text"
                        v-model="search"
                        placeholder="Cari kategori..."
                        class="pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm focus:ring-2 focus:ring-brand-red/50 outline-none transition-all w-full md:w-64"
                    />
                    <div
                        class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400"
                    >
                        <i class="fas fa-search"></i>
                    </div>
                </div>

                <Link
                    :href="route('admin.categories.create')"
                    class="px-5 py-2.5 rounded-xl bg-brand-red text-white font-semibold shadow-lg shadow-brand-red/30 hover:bg-red-700 hover:shadow-brand-red/50 transition-all duration-300 flex items-center gap-2 whitespace-nowrap"
                >
                    <i class="fas fa-plus"></i>
                    <span class="hidden md:inline">Tambah Kategori</span>
                </Link>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Categories List -->
            <div
                class="bg-white dark:bg-gray-800 rounded-3xl shadow-xl shadow-gray-100/50 dark:shadow-black/30 border border-gray-100 dark:border-gray-700/50 overflow-hidden"
            >
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead
                            class="bg-gray-50/50 dark:bg-gray-900/20 text-gray-500 dark:text-gray-400 text-xs uppercase font-bold tracking-wider"
                        >
                            <tr>
                                <th class="px-6 py-4">Nama Kategori</th>
                                <th class="px-6 py-4">Jumlah Artikel</th>
                                <th class="px-6 py-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody
                            class="divide-y divide-gray-100 dark:divide-gray-700/50"
                        >
                            <tr
                                v-for="category in localCategories?.data"
                                :key="category.id"
                                class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors"
                            >
                                <td class="px-6 py-4">
                                    <p
                                        class="font-bold text-gray-900 dark:text-white text-sm"
                                    >
                                        {{ category.name }}
                                    </p>
                                    <p
                                        class="text-xs text-gray-500 line-clamp-1 mt-0.5"
                                        v-if="category.parent"
                                    >
                                        Sub: {{ category.parent.name }}
                                    </p>
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="px-3 py-1 rounded-full text-xs font-bold bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300"
                                    >
                                        {{ category.articles_count || 0 }}
                                        Artikel
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div
                                        class="flex items-center justify-end gap-2"
                                    >
                                        <Link
                                            :href="
                                                route(
                                                    'admin.categories.edit',
                                                    category.id,
                                                )
                                            "
                                            class="p-2 rounded-lg text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-colors tooltip"
                                            title="Edit"
                                        >
                                            <i class="fas fa-edit"></i>
                                        </Link>
                                        <button
                                            @click="deleteCategory(category.id)"
                                            class="p-2 rounded-lg text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors tooltip"
                                            title="Hapus"
                                        >
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr v-if="localCategories?.data?.length === 0">
                                <td
                                    colspan="3"
                                    class="px-6 py-12 text-center text-gray-400"
                                >
                                    <div class="flex flex-col items-center">
                                        <i
                                            class="fas fa-tags text-4xl mb-3 opacity-30"
                                        ></i>
                                        <p>Belum ada kategori.</p>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <!-- Pagination -->
                <div
                    class="px-6 py-4 border-t border-gray-100 dark:border-gray-700 flex items-center justify-between"
                    v-if="localCategories?.links?.length > 3"
                >
                    <div class="text-xs text-gray-500">
                        Total {{ localCategories.total }}
                    </div>
                    <div class="flex gap-1">
                        <button
                            v-for="(link, k) in localCategories.links"
                            :key="k"
                            @click.prevent="fetchPage(link.url)"
                            v-html="link.label"
                            :disabled="!link.url"
                            :class="[
                                'px-3 py-1 rounded-lg text-xs font-bold transition-all',
                                link.active
                                    ? 'bg-brand-red text-white shadow-md shadow-brand-red/20'
                                    : 'text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700',
                                !link.url
                                    ? 'opacity-50 cursor-not-allowed'
                                    : '',
                            ]"
                        />
                    </div>
                </div>
            </div>

            <!-- Info Card (Optional side content) -->
            <div class="hidden lg:block">
                <div
                    class="bg-gradient-to-br from-slate-900 to-slate-800 rounded-3xl p-8 text-white shadow-2xl relative overflow-hidden"
                >
                    <div
                        class="absolute top-0 right-0 -mt-10 -mr-10 h-64 w-64 bg-white/5 rounded-full blur-3xl"
                    ></div>
                    <div
                        class="absolute bottom-0 left-0 -mb-10 -ml-10 h-64 w-64 bg-brand-red/20 rounded-full blur-3xl"
                    ></div>

                    <h3 class="text-xl font-bold font-serif mb-4 relative z-10">
                        Tips Kategori
                    </h3>
                    <ul class="space-y-4 relative z-10 text-sm text-gray-300">
                        <li class="flex items-start gap-3">
                            <i
                                class="fas fa-check-circle text-green-400 mt-1"
                            ></i>
                            <p>Gunakan nama kategori yang singkat dan jelas.</p>
                        </li>
                        <li class="flex items-start gap-3">
                            <i
                                class="fas fa-check-circle text-green-400 mt-1"
                            ></i>
                            <p>
                                Kategori membantu pengguna memfilter berita yang
                                relevan.
                            </p>
                        </li>
                        <li class="flex items-start gap-3">
                            <i
                                class="fas fa-exclamation-circle text-amber-400 mt-1"
                            ></i>
                            <p>
                                Kategori yang memiliki artikel tidak dapat
                                dihapus secara langsung.
                            </p>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
