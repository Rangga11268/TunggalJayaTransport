<script setup>
import FrontendLayout from "@/Layouts/FrontendLayout.vue";
import { Head, Link } from "@inertiajs/vue3";

defineOptions({ layout: FrontendLayout });

const props = defineProps({
    articles: Object,
    categories: Array,
    currentCategory: String, // ID or null
});

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
</script>

<template>
    <Head title="Berita & Artikel" />

    <div
        class="min-h-screen bg-gray-50 dark:bg-[#050505] font-sans selection:bg-rose-600 selection:text-white"
    >
        <!-- Hero Header -->
        <div class="relative pt-32 pb-20 px-4 sm:px-6 lg:px-8 overflow-hidden">
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
                    class="text-4xl md:text-6xl font-black text-gray-900 dark:text-white mb-6 font-unbounded animate-fade-in-up"
                    style="animation-delay: 0.1s"
                >
                    Kabar <span class="text-rose-600">Tunggal Jaya</span>
                </h1>
                <p
                    class="text-lg text-gray-500 dark:text-gray-400 max-w-2xl mx-auto font-manrope animate-fade-in-up"
                    style="animation-delay: 0.2s"
                >
                    Dapatkan informasi terbaru seputar armada, tips perjalanan,
                    promo menarik, dan berita terkini dari kami.
                </p>
            </div>
        </div>

        <!-- Filter Categories -->
        <div
            class="sticky top-24 z-30 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto mb-16 animate-fade-in-up"
            style="animation-delay: 0.3s"
        >
            <div class="flex flex-wrap justify-center gap-3">
                <Link
                    :href="route('frontend.news.index')"
                    class="px-5 py-2.5 rounded-full text-xs font-bold uppercase tracking-wider font-unbounded transition-all duration-300 border"
                    :class="
                        !currentCategory
                            ? 'bg-rose-600 text-white border-rose-600 shadow-lg shadow-rose-600/30'
                            : 'bg-white dark:bg-[#1a1a1a] text-gray-600 dark:text-gray-400 border-gray-200 dark:border-white/10 hover:border-gray-400 dark:hover:border-white/30'
                    "
                >
                    Semua
                </Link>
                <Link
                    v-for="cat in categories"
                    :key="cat.id"
                    :href="route('frontend.news.index', { category: cat.id })"
                    class="px-5 py-2.5 rounded-full text-xs font-bold uppercase tracking-wider font-unbounded transition-all duration-300 border"
                    :class="
                        currentCategory == cat.id
                            ? 'bg-rose-600 text-white border-rose-600 shadow-lg shadow-rose-600/30'
                            : 'bg-white dark:bg-[#1a1a1a] text-gray-600 dark:text-gray-400 border-gray-200 dark:border-white/10 hover:border-gray-400 dark:hover:border-white/30'
                    "
                >
                    {{ cat.name }}
                </Link>
            </div>
        </div>

        <!-- News Grid -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-32">
            <div
                v-if="articles.data.length > 0"
                class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8"
            >
                <Link
                    v-for="(article, index) in articles.data"
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
                    <div class="p-8 flex-grow flex flex-col">
                        <div
                            class="flex items-center gap-3 text-xs text-gray-400 mb-4 font-manrope"
                        >
                            <span
                                ><i
                                    class="far fa-calendar mr-1 text-rose-600"
                                ></i>
                                {{
                                    formatDate(
                                        article.published_at ||
                                            article.created_at
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
                            class="text-sm text-gray-500 dark:text-gray-400 font-manrope line-clamp-3 mb-6 flex-grow"
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
                <p class="text-gray-500 dark:text-gray-400 font-manrope">
                    Belum ada berita atau artikel yang tersedia untuk kategori
                    ini.
                </p>
            </div>

            <!-- Pagination -->
            <div
                v-if="articles.links && articles.data.length > 0"
                class="mt-16 flex justify-center animate-fade-in-up"
                style="animation-delay: 0.5s"
            >
                <div class="flex flex-wrap gap-2">
                    <component
                        :is="link.url ? Link : 'span'"
                        v-for="(link, index) in articles.links"
                        :key="index"
                        :href="link.url"
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
