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

    <div
        class="min-h-screen bg-gray-50 dark:bg-[#050505] font-sans selection:bg-rose-600 selection:text-white"
    >
        <!-- Hero Header -->
        <div
            class="relative pt-24 md:pt-32 pb-20 px-4 sm:px-6 lg:px-8 overflow-hidden"
        >
            <!-- Background Gradients -->
            <div
                class="absolute top-0 right-0 w-[500px] h-[500px] bg-rose-600/5 rounded-full blur-[120px] -z-10"
            ></div>
            <div
                class="absolute bottom-0 left-0 w-[500px] h-[500px] bg-rose-600/5 rounded-full blur-[120px] -z-10"
            ></div>

            <div class="max-w-7xl mx-auto text-center relative z-10">
                <span
                    class="inline-block py-1 px-3 rounded-full bg-rose-50 dark:bg-rose-900/10 text-rose-600 border border-rose-100 dark:border-rose-900/20 text-xs font-bold tracking-widest uppercase mb-6 font-unbounded animate-fade-in-up"
                >
                    Wawasan & Update
                </span>
                <h1
                    class="text-3xl sm:text-4xl md:text-6xl font-black text-gray-900 dark:text-white mb-6 font-unbounded animate-fade-in-up"
                    style="animation-delay: 0.1s"
                >
                    Kabar <span class="text-rose-600">Tunggal Jaya</span>
                </h1>
                <p
                    class="text-lg text-gray-500 dark:text-gray-400 max-w-2xl mx-auto  animate-fade-in-up"
                    style="animation-delay: 0.2s"
                >
                    Dapatkan informasi terbaru seputar armada, tips perjalanan,
                    promo menarik, dan berita terkini dari kami.
                </p>
            </div>
        </div>

        <!-- Filter Categories (sticky disabled) -->
        <div
            class="relative px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto mb-16 animate-fade-in-up"
            style="animation-delay: 0.3s"
        >
            <div class="flex flex-wrap justify-center gap-3">
                <button
                    @click.prevent="fetchArticles(1, null)"
                    class="px-5 py-2.5 rounded-full text-xs font-bold uppercase tracking-wider font-unbounded transition-all duration-300 border"
                    :class="
                        !currentCat
                            ? 'bg-rose-600 text-white border-rose-600 shadow-lg shadow-rose-600/30'
                            : 'bg-white dark:bg-[#1a1a1a] text-gray-600 dark:text-gray-400 border-gray-200 dark:border-white/10 hover:border-gray-400 dark:hover:border-white/30'
                    "
                >
                    Semua
                </button>
                <button
                    v-for="cat in categories"
                    :key="cat.id"
                    @click.prevent="fetchArticles(1, cat.id)"
                    class="px-5 py-2.5 rounded-full text-xs font-bold uppercase tracking-wider font-unbounded transition-all duration-300 border"
                    :class="
                        currentCat == cat.id
                            ? 'bg-rose-600 text-white border-rose-600 shadow-lg shadow-rose-600/30'
                            : 'bg-white dark:bg-[#1a1a1a] text-gray-600 dark:text-gray-400 border-gray-200 dark:border-white/10 hover:border-gray-400 dark:hover:border-white/30'
                    "
                >
                    {{ cat.name }}
                </button>
            </div>
        </div>

        <!-- News Grid -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-32 relative">
            <!-- Loading Overlay -->
            <div
                v-if="isLoading"
                class="absolute inset-0 bg-white/50 dark:bg-[#050505]/50 backdrop-blur-[2px] z-20 flex items-center justify-center rounded-[3rem]"
            >
                <div
                    class="w-12 h-12 border-4 border-rose-600 border-t-transparent rounded-full animate-spin"
                ></div>
            </div>

            <div
                v-if="localArticles.data.length > 0"
                class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8"
            >
                <Link
                    v-for="(article, index) in localArticles.data"
                    :key="article.id"
                    :href="route('frontend.news.show', article.slug)"
                    class="group relative bg-white dark:bg-[#111] rounded-[2rem] overflow-hidden border border-gray-100 dark:border-white/5 hover:border-rose-600/30 transition-all duration-500 hover:shadow-2xl hover:shadow-rose-600/10 flex flex-col h-full animate-fade-in-up"
                    :style="{ animationDelay: `${index * 0.1 + 0.4}s` }"
                >
                    <!-- Image -->
                    <div class="relative h-64 overflow-hidden">
                        <img
                            :src="article.image_url"
                            :alt="article.title"
                            class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700"
                        />
                        <div
                            class="absolute inset-0 bg-gradient-to-t from-[#111] via-transparent to-transparent opacity-80"
                        ></div>

                        <!-- Category Badge -->
                        <div class="absolute top-4 left-4">
                            <span
                                class="px-3 py-1 rounded-lg bg-white/10 backdrop-blur-md border border-white/20 text-white text-[10px] font-bold uppercase tracking-widest font-unbounded"
                            >
                                {{ article.category?.name || "Umum" }}
                            </span>
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="p-6 md:p-8 flex-grow flex flex-col">
                        <div
                            class="flex items-center gap-3 text-xs text-gray-400 mb-4 "
                        >
                            <span
                                ><i
                                    class="far fa-calendar mr-1 text-rose-600"
                                ></i>
                                {{
                                    formatDate(
                                        article.published_at ||
                                            article.created_at,
                                    )
                                }}</span
                            >
                            <span
                                class="w-1 h-1 rounded-full bg-gray-600"
                            ></span>
                            <span>{{ article.author?.name || "Admin" }}</span>
                        </div>

                        <h3
                            class="text-xl font-bold font-unbounded text-gray-900 dark:text-white mb-4 leading-tight group-hover:text-rose-600 transition-colors"
                        >
                            {{ article.title }}
                        </h3>

                        <p
                            class="text-sm text-gray-500 dark:text-gray-400  line-clamp-3 mb-6 flex-grow"
                        >
                            {{ article.excerpt }}
                        </p>

                        <div
                            class="pt-6 border-t border-gray-100 dark:border-white/5 flex items-center justify-between"
                        >
                            <span
                                class="text-xs font-bold font-unbounded text-rose-600 group-hover:underline uppercase tracking-wider"
                                >Baca Artikel</span
                            >
                            <div
                                class="w-8 h-8 rounded-full bg-gray-50 dark:bg-white/5 flex items-center justify-center group-hover:bg-rose-600 group-hover:text-white transition-all duration-300"
                            >
                                <i class="fas fa-arrow-right text-xs"></i>
                            </div>
                        </div>
                    </div>
                </Link>
            </div>

            <!-- Empty State -->
            <div
                v-else
                class="text-center py-32 border-2 border-dashed border-gray-200 dark:border-white/5 rounded-3xl animate-fade-in-up"
            >
                <div
                    class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-gray-100 dark:bg-white/5 mb-6"
                >
                    <i class="fas fa-newspaper text-3xl text-gray-400"></i>
                </div>
                <h3
                    class="text-xl font-bold font-unbounded text-gray-900 dark:text-white mb-2"
                >
                    Belum Ada Artikel
                </h3>
                <p class="text-gray-500 dark:text-gray-400 ">
                    Belum ada berita atau artikel yang tersedia untuk kategori
                    ini.
                </p>
            </div>

            <!-- Pagination -->
            <div
                v-if="localArticles.links && localArticles.data.length > 0"
                class="mt-16 flex justify-center animate-fade-in-up"
                style="animation-delay: 0.5s"
            >
                <div class="flex flex-wrap gap-2">
                    <button
                        v-for="(link, index) in localArticles.links"
                        :key="index"
                        @click.prevent="
                            link.url
                                ? fetchArticles(
                                      new URL(link.url).searchParams.get(
                                          'page',
                                      ),
                                      currentCat,
                                  )
                                : null
                        "
                        v-html="link.label"
                        class="px-4 py-2 rounded-xl text-sm font-bold font-unbounded transition-all duration-300"
                        :class="
                            link.active
                                ? 'bg-rose-600 text-white shadow-lg shadow-rose-600/30'
                                : !link.url
                                  ? 'text-gray-400 cursor-not-allowed bg-gray-100 dark:bg-white/5'
                                  : 'bg-white dark:bg-[#1a1a1a] text-gray-600 dark:text-gray-400 border border-gray-200 dark:border-white/10 hover:border-rose-600 dark:hover:border-rose-600 hover:text-rose-600'
                        "
                    />
                </div>
            </div>
        </div>
    </div>
</template>
