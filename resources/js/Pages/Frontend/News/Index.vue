<script setup>
import { ref, onMounted } from "vue";
import FrontendLayout from "@/Layouts/FrontendLayout.vue";
import { Head, Link } from "@inertiajs/vue3";
import axios from "axios";

defineOptions({ layout: FrontendLayout });

const props = defineProps({
    articles: Object,
    categories: Array,
    currentCategory: String, // ID or null
});

const localArticles = ref(props.articles);
const currentCat = ref(props.currentCategory);
const isLoading = ref(false);

const formatDate = (dateString) => {
    if (!dateString) return "Tanggal Belum Tersedia";
    const date = new Date(dateString);
    if (isNaN(date.getTime()) || date.getFullYear() <= 1970)
        return "Tanggal Belum Tersedia";

    return date.toLocaleDateString("id-ID", {
        day: "numeric",
        month: "long",
        year: "numeric",
    });
};

const fetchArticles = async (page = 1, categoryId = null) => {
    isLoading.value = true;
    currentCat.value = categoryId;

    try {
        const response = await axios.get(route("frontend.news.index"), {
            params: {
                page: page,
                category: categoryId,
            },
        });

        localArticles.value = response.data.articles;

        // Update URL without reloading
        const url = new URL(window.location);
        if (categoryId) url.searchParams.set("category", categoryId);
        else url.searchParams.delete("category");

        if (page > 1) url.searchParams.set("page", page);
        else url.searchParams.delete("page");

        window.history.pushState({}, "", url);
    } catch (error) {
        console.error("Failed to fetch articles:", error);
    } finally {
        isLoading.value = false;
    }
};
</script>

<template>
    <Head title="Berita & Artikel" />

    <div class="min-h-screen bg-[#fcf9f8]">
        <!-- Hero -->
        <div class="pt-28 pb-12 px-4 sm:px-6 lg:px-8 text-center">
            <span class="inline-block px-4 py-1.5 rounded-full bg-white border border-[#ebe7e7] text-[#10207a] text-[11px] font-bold tracking-widest uppercase mb-5 shadow-sm">
                Wawasan & Update
            </span>
            <h1 class="font-unbounded font-black text-4xl md:text-5xl text-[#1c1b1b] mb-3">Kabar Tunggal Jaya</h1>
            <p class="text-[#454652] text-[16px] max-w-xl mx-auto">
                Dapatkan informasi terbaru seputar armada, tips perjalanan, promo menarik, dan berita terkini.
            </p>
        </div>

        <!-- Filter Categories -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-10">
            <div class="flex flex-wrap justify-center gap-2">
                <button @click.prevent="fetchArticles(1, null)"
                    class="px-5 py-2 rounded-xl text-xs font-bold uppercase tracking-wider transition-all border"
                    :class="!currentCat ? 'bg-[#10207a] text-white border-[#10207a] shadow-sm' : 'bg-white text-gray-600 border-gray-200 hover:border-gray-400'">
                    Semua
                </button>
                <button v-for="cat in categories" :key="cat.id" @click.prevent="fetchArticles(1, cat.id)"
                    class="px-5 py-2 rounded-xl text-xs font-bold uppercase tracking-wider transition-all border"
                    :class="currentCat == cat.id ? 'bg-[#10207a] text-white border-[#10207a] shadow-sm' : 'bg-white text-gray-600 border-gray-200 hover:border-gray-400'">
                    {{ cat.name }}
                </button>
            </div>
        </div>

        <!-- News Grid -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-32 relative">
            <div v-if="isLoading" class="absolute inset-0 bg-white/50 backdrop-blur-[1px] z-20 flex items-center justify-center rounded-[12px]">
                <div class="w-10 h-10 border-4 border-[#10207a] border-t-transparent rounded-full animate-spin"></div>
            </div>

            <div v-if="localArticles.data.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                <Link v-for="(article, index) in localArticles.data" :key="article.id"
                    :href="route('frontend.news.show', article.slug)"
                    class="bg-white rounded-[12px] overflow-hidden border border-[#ebe7e7] hover:shadow-md transition-shadow group flex flex-col">
                    <div class="relative h-52 overflow-hidden">
                        <img :src="article.image_url" :alt="article.title"
                            class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" />
                        <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-transparent to-transparent"></div>
                        <div class="absolute top-3 left-3">
                            <span class="px-2.5 py-1 rounded-lg bg-white/20 backdrop-blur-sm border border-white/30 text-white text-[10px] font-bold uppercase tracking-wider">
                                {{ article.category?.name || 'Umum' }}
                            </span>
                        </div>
                    </div>
                    <div class="p-4 flex flex-col flex-grow">
                        <div class="flex items-center gap-3 text-xs text-gray-400 mb-3">
                            <span>{{ formatDate(article.published_at || article.created_at) }}</span>
                            <span class="w-1 h-1 rounded-full bg-gray-300"></span>
                            <span>{{ article.author?.name || 'Admin' }}</span>
                        </div>
                        <h3 class="font-bold text-[15px] text-[#1c1b1b] mb-2 leading-snug line-clamp-2">{{ article.title }}</h3>
                        <p class="text-sm text-[#454652] line-clamp-2 mb-4 flex-grow">{{ article.excerpt }}</p>
                        <div class="pt-3 border-t border-[#ebe7e7] flex items-center justify-between">
                            <span class="text-xs font-bold text-[#10207a] uppercase tracking-wider">Baca Artikel</span>
                            <div class="w-7 h-7 rounded-full bg-[#f6f3f2] flex items-center justify-center group-hover:bg-[#10207a] group-hover:text-white transition-all">
                                <i class="fas fa-arrow-right text-[10px]"></i>
                            </div>
                        </div>
                    </div>
                </Link>
            </div>

            <div v-else class="text-center py-24 border-2 border-dashed border-[#e5e2e1] rounded-[12px]">
                <div class="w-16 h-16 rounded-full bg-[#f6f3f2] flex items-center justify-center mx-auto mb-5">
                    <i class="fas fa-newspaper text-2xl text-gray-400"></i>
                </div>
                <h3 class="text-lg font-bold text-[#1c1b1b] mb-1">Belum Ada Artikel</h3>
                <p class="text-sm text-[#454652]">Belum ada berita untuk kategori ini.</p>
            </div>

            <!-- Pagination -->
            <div v-if="localArticles.links && localArticles.data.length > 0" class="mt-10 flex justify-center">
                <div class="flex flex-wrap gap-2">
                    <button v-for="(link, index) in localArticles.links" :key="index"
                        @click.prevent="link.url ? fetchArticles(new URL(link.url).searchParams.get('page'), currentCat) : null"
                        v-html="link.label"
                        class="px-4 py-2 rounded-[10px] text-sm font-bold transition-all"
                        :class="link.active ? 'bg-[#10207a] text-white shadow-sm' : !link.url ? 'text-gray-300 cursor-not-allowed' : 'bg-white text-gray-600 border border-[#e5e2e1] hover:border-[#10207a] hover:text-[#10207a]'">
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
