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
    return new Date(dateString).toLocaleDateString("id-ID", {
        day: "numeric",
        month: "long",
        year: "numeric",
    });
};
</script>

<template>
    <Head title="Berita & Artikel" />

    <!-- Clean Title Section -->
    <div class="pt-24 pb-12 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto text-center">
        <span
            class="inline-block px-4 py-1.5 rounded-full bg-primary-50 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400 text-xs font-bold tracking-widest mb-6 animate-fade-in uppercase"
        >
            INFORMASI TERBARU
        </span>
        <h1
            class="text-4xl md:text-5xl lg:text-6xl font-black font-serif text-gray-900 dark:text-white mb-6 animate-fade-in-up"
        >
            Berita &
            <span
                class="text-transparent bg-clip-text bg-gradient-to-r from-brand-red to-orange-500"
                >Artikel</span
            >
        </h1>
        <p
            class="text-lg text-gray-500 dark:text-gray-400 max-w-2xl mx-auto animate-fade-in-up stagger-1"
        >
            Kumpulan informasi, tips perjalanan, dan berita terbaru dari TUJAGO.
        </p>
    </div>

    <!-- Main Content -->
    <div class="bg-gray-50 dark:bg-gray-950 min-h-screen py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Categories -->
            <div class="flex flex-wrap justify-center gap-3 mb-10">
                <Link
                    :href="route('frontend.news.index')"
                    class="px-5 py-2 rounded-full text-sm font-bold transition-all border"
                    :class="
                        !currentCategory
                            ? 'bg-brand-red text-white border-brand-red shadow-lg shadow-brand-red/30'
                            : 'bg-white dark:bg-gray-900 text-gray-600 dark:text-gray-400 border-gray-200 dark:border-gray-800 hover:border-brand-red hover:text-brand-red'
                    "
                >
                    Semua
                </Link>
                <Link
                    v-for="currency in categories"
                    :key="currency.id"
                    :href="
                        route('frontend.news.index', { category: currency.id })
                    "
                    class="px-5 py-2 rounded-full text-sm font-bold transition-all border"
                    :class="
                        currentCategory == currency.id
                            ? 'bg-brand-red text-white border-brand-red shadow-lg shadow-brand-red/30'
                            : 'bg-white dark:bg-gray-900 text-gray-600 dark:text-gray-400 border-gray-200 dark:border-gray-800 hover:border-brand-red hover:text-brand-red'
                    "
                >
                    {{ currency.name }}
                </Link>
            </div>

            <!-- News Grid -->
            <div
                v-if="articles.data.length > 0"
                class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8"
            >
                <Link
                    v-for="article in articles.data"
                    :key="article.id"
                    :href="route('frontend.news.show', article.slug)"
                    class="group bg-white dark:bg-gray-900 rounded-3xl overflow-hidden shadow-lg border border-gray-100 dark:border-gray-800 hover:shadow-2xl hover:-translate-y-1 transition-all duration-300"
                >
                    <div class="relative h-56 overflow-hidden">
                        <img
                            :src="article.image_url"
                            :alt="article.title"
                            class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-500"
                        />
                        <div
                            class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"
                        ></div>
                        <div class="absolute bottom-4 left-4">
                            <span
                                class="px-3 py-1 bg-brand-red text-white text-xs font-bold rounded-full shadow-lg"
                            >
                                {{ article.category?.name || "Umum" }}
                            </span>
                        </div>
                    </div>
                    <div class="p-6">
                        <div
                            class="flex items-center text-xs text-gray-500 dark:text-gray-400 mb-3 space-x-3"
                        >
                            <span
                                ><i class="far fa-calendar mr-1"></i>
                                {{ formatDate(article.published_at) }}</span
                            >
                            <span
                                ><i class="far fa-user mr-1"></i>
                                {{ article.author?.name || "Admin" }}</span
                            >
                        </div>
                        <h3
                            class="text-xl font-bold text-gray-900 dark:text-white mb-3 line-clamp-2 group-hover:text-brand-red transition-colors"
                        >
                            {{ article.title }}
                        </h3>
                        <p
                            class="text-gray-600 dark:text-gray-400 text-sm line-clamp-3 mb-4"
                        >
                            {{ article.excerpt }}
                        </p>
                        <span
                            class="text-brand-red font-bold text-sm inline-flex items-center group-hover:underline"
                        >
                            Baca Selengkapnya
                            <i
                                class="fas fa-arrow-right ml-2 opacity-0 -translate-x-2 group-hover:translate-x-0 group-hover:opacity-100 transition-all"
                            ></i>
                        </span>
                    </div>
                </Link>
            </div>

            <!-- Empty State -->
            <div v-else class="text-center py-20">
                <div
                    class="w-20 h-20 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center mx-auto mb-6"
                >
                    <i class="fas fa-newspaper text-3xl text-gray-400"></i>
                </div>
                <h3
                    class="text-lg font-bold text-gray-900 dark:text-white mb-2"
                >
                    Belum ada berita
                </h3>
                <p class="text-gray-500">
                    Belum ada artikel yang tersedia di kategori ini.
                </p>
            </div>

            <!-- Pagination -->
            <div
                v-if="articles.links && articles.data.length > 0"
                class="mt-12 flex justify-center"
            >
                <div class="flex space-x-2">
                    <component
                        :is="link.url ? Link : 'span'"
                        v-for="(link, index) in articles.links"
                        :key="index"
                        :href="link.url"
                        v-html="link.label"
                        class="px-4 py-2 rounded-lg text-sm font-semibold transition-colors"
                        :class="
                            link.active
                                ? 'bg-brand-red text-white shadow-lg shadow-brand-red/30'
                                : !link.url
                                ? 'text-gray-400 cursor-not-allowed bg-gray-100 dark:bg-gray-800'
                                : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700'
                        "
                    />
                </div>
            </div>
        </div>
    </div>
</template>
