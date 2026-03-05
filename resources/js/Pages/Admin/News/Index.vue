<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { Head, Link, router, usePage } from "@inertiajs/vue3";
import { ref, computed, watch } from "vue";
import Swal from "sweetalert2";
import axios from "axios";

const props = defineProps({
    articles: Object,
    filters: Object,
});

const search = ref(props.filters?.search || "");
const localArticles = ref(props.articles); // Reactive local state for articles data
let timeout = null;

// Search via Axios (No Inertia Reload)
watch(search, (value) => {
    clearTimeout(timeout);
    timeout = setTimeout(async () => {
        try {
            const { data } = await axios.get(route("admin.news.index"), {
                params: { search: value },
                headers: { Accept: "application/json" },
            });
            localArticles.value = data.articles;

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
        localArticles.value = data.articles;

        // Sync URL with pagination parameter
        window.history.replaceState({}, "", url);
        window.scrollTo({ top: 0, behavior: "smooth" });
    } catch (error) {
        console.error("Pagination failed:", error);
    }
};

const deleteArticle = (id) => {
    Swal.fire({
        title: "Apakah Anda yakin?",
        text: "Artikel yang dihapus tidak dapat dikembalikan!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
        confirmButtonText: "Ya, hapus!",
        cancelButtonText: "Batal",
    }).then((result) => {
        if (result.isConfirmed) {
            router.delete(route("admin.news.destroy", id), {
                onSuccess: () => {
                    Swal.fire(
                        "Terhapus!",
                        "Artikel berhasil dihapus.",
                        "success",
                    );
                },
            });
        }
    });
};

const formatDate = (dateString) => {
    return new Date(dateString).toLocaleDateString("id-ID", {
        day: "numeric",
        month: "short",
        year: "numeric",
        hour: "2-digit",
        minute: "2-digit",
    });
};
</script>

<template>
    <Head title="Manajemen Berita" />

    <AdminLayout title="Manajemen Berita">
        <div
            class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8"
        >
            <div>
                <h2
                    class="text-2xl font-bold text-gray-900 dark:text-white font-serif"
                >
                    Daftar Berita
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    Kelola artikel, berita, dan informasi terbaru.
                </p>
            </div>

            <div class="flex items-center gap-3">
                <div class="relative">
                    <input
                        type="text"
                        v-model="search"
                        placeholder="Cari berita..."
                        class="pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm focus:ring-2 focus:ring-brand-red/50 outline-none transition-all w-full md:w-64"
                    />
                    <div
                        class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400"
                    >
                        <i class="fas fa-search"></i>
                    </div>
                </div>

                <Link
                    :href="route('admin.news.create')"
                    class="px-5 py-2.5 rounded-xl bg-brand-red text-white font-semibold shadow-lg shadow-brand-red/30 hover:bg-red-700 hover:shadow-brand-red/50 transition-all duration-300 flex items-center gap-2 whitespace-nowrap"
                >
                    <i class="fas fa-plus"></i>
                    <span class="hidden md:inline">Tulis Artikel</span>
                </Link>
            </div>
        </div>

        <!-- Table Card -->
        <div
            class="bg-white dark:bg-gray-800 rounded-3xl shadow-xl shadow-gray-100/50 dark:shadow-black/30 border border-gray-100 dark:border-gray-700/50 overflow-hidden"
        >
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead
                        class="bg-gray-50/50 dark:bg-gray-900/20 text-gray-500 dark:text-gray-400 text-xs uppercase font-bold tracking-wider"
                    >
                        <tr>
                            <th class="px-6 py-4">Judul</th>
                            <th class="px-6 py-4">Kategori</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4">Tanggal</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody
                        class="divide-y divide-gray-100 dark:divide-gray-700/50"
                    >
                        <tr
                            v-for="article in localArticles?.data"
                            :key="article.id"
                            class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors"
                        >
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-4">
                                    <div
                                        class="h-12 w-16 flex-shrink-0 bg-gray-100 rounded-lg overflow-hidden"
                                    >
                                        <img
                                            v-if="
                                                article.image_url &&
                                                article.image_url.startsWith(
                                                    'http',
                                                )
                                            "
                                            :src="article.image_url"
                                            class="w-full h-full object-cover"
                                            alt="Thumbnail"
                                        />
                                        <div
                                            v-else
                                            class="w-full h-full flex items-center justify-center bg-gray-200 text-gray-400"
                                        >
                                            <i class="fas fa-image"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <p
                                            class="font-bold text-gray-900 dark:text-white text-sm line-clamp-1"
                                        >
                                            {{ article.title }}
                                        </p>
                                        <p
                                            class="text-xs text-gray-500 line-clamp-1 mt-0.5"
                                        >
                                            {{ article.excerpt }}
                                        </p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span
                                    class="px-3 py-1 rounded-full text-xs font-bold bg-blue-50 text-blue-600 dark:bg-blue-900/20 dark:text-blue-400 border border-blue-100 dark:border-blue-900/30"
                                >
                                    {{
                                        article.category?.name ||
                                        "Uncategorized"
                                    }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span
                                    :class="[
                                        'px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-wide',
                                        article.is_published
                                            ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400 ring-1 ring-green-500/20'
                                            : 'bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-400 ring-1 ring-gray-500/20',
                                    ]"
                                >
                                    {{
                                        article.is_published
                                            ? "Published"
                                            : "Draft"
                                    }}
                                </span>
                            </td>
                            <td
                                class="px-6 py-4 text-xs font-medium text-gray-500 dark:text-gray-400"
                            >
                                {{ formatDate(article.created_at) }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div
                                    class="flex items-center justify-end gap-2"
                                >
                                    <Link
                                        :href="
                                            route('admin.news.edit', article.id)
                                        "
                                        class="p-2 rounded-lg text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-colors tooltip"
                                        title="Edit"
                                    >
                                        <i class="fas fa-edit"></i>
                                    </Link>
                                    <button
                                        @click="deleteArticle(article.id)"
                                        class="p-2 rounded-lg text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors tooltip"
                                        title="Hapus"
                                    >
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="localArticles?.data?.length === 0">
                            <td
                                colspan="5"
                                class="px-6 py-12 text-center text-gray-400"
                            >
                                <div class="flex flex-col items-center">
                                    <i
                                        class="fas fa-newspaper text-4xl mb-3 opacity-30"
                                    ></i>
                                    <p>Belum ada berita yang ditambahkan.</p>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div
                class="px-6 py-4 border-t border-gray-100 dark:border-gray-700 flex items-center justify-between"
                v-if="localArticles?.links?.length > 3"
            >
                <div class="text-xs text-gray-500">
                    Menampilkan {{ localArticles.from }} -
                    {{ localArticles.to }} dari {{ localArticles.total }} data
                </div>
                <div class="flex gap-1">
                    <button
                        v-for="(link, k) in localArticles.links"
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
