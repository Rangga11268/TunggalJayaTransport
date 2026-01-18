<script setup>
import FrontendLayout from "@/Layouts/FrontendLayout.vue";
import { Head, Link } from "@inertiajs/vue3";

defineOptions({ layout: FrontendLayout });

const props = defineProps({
    article: Object,
    relatedArticles: Object,
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
    <Head :title="article.title" />

    <div class="bg-white dark:bg-[#050505] min-h-screen font-sans">
        <!-- Hero Header -->
        <div class="relative h-[60vh] min-h-[500px] overflow-hidden">
            <img
                :src="article.image_url"
                :alt="article.title"
                class="absolute inset-0 w-full h-full object-cover"
            />
            <div
                class="absolute inset-0 bg-gradient-to-t from-[#050505] via-black/60 to-transparent"
            ></div>

            <div class="absolute inset-0 flex items-center">
                <div
                    class="max-w-4xl w-full mx-auto px-4 sm:px-6 lg:px-8 mt-20"
                >
                    <Link
                        :href="route('frontend.news.index')"
                        class="inline-flex items-center text-white/60 hover:text-white mb-8 transition-colors group"
                    >
                        <i
                            class="fas fa-arrow-left mr-3 group-hover:-translate-x-1 transition-transform"
                        ></i>
                        <span
                            class="font-bold font-unbounded text-xs uppercase tracking-widest"
                            >Kembali ke Berita</span
                        >
                    </Link>

                    <div class="mb-6 animate-fade-in-up">
                        <span
                            class="inline-block px-4 py-2 bg-rose-600/90 backdrop-blur-sm text-white text-[10px] font-bold rounded-xl uppercase tracking-widest font-unbounded border border-rose-500/50 shadow-xl shadow-rose-600/20"
                        >
                            {{ article.category?.name || "Umum" }}
                        </span>
                    </div>

                    <h1
                        class="text-2xl sm:text-5xl md:text-6xl font-black text-white leading-tight mb-8 font-unbounded animate-fade-in-up"
                        style="animation-delay: 0.1s"
                    >
                        {{ article.title }}
                    </h1>

                    <div
                        class="flex flex-wrap items-center gap-6 text-gray-300 animate-fade-in-up"
                        style="animation-delay: 0.2s"
                    >
                        <div
                            class="flex items-center bg-white/10 backdrop-blur-md rounded-full px-4 py-2 border border-white/10"
                        >
                            <i
                                class="fas fa-user-circle text-rose-500 mr-2 text-lg"
                            ></i>
                            <span class="font-bold text-sm font-manrope">{{
                                article.author?.name || "Admin"
                            }}</span>
                        </div>
                        <div
                            class="flex items-center bg-white/10 backdrop-blur-md rounded-full px-4 py-2 border border-white/10"
                        >
                            <i
                                class="far fa-calendar-alt text-rose-500 mr-2"
                            ></i>
                            <span class="font-bold text-sm font-manrope">{{
                                formatDate(
                                    article.published_at || article.created_at,
                                )
                            }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
                <!-- Main Content -->
                <div class="lg:col-span-8">
                    <!-- Article Body -->
                    <div
                        class="prose prose-base md:prose-lg prose-rose dark:prose-invert max-w-none font-manrope text-gray-800 dark:text-gray-200"
                    >
                        <!-- We assume content is HTML from a WYSIWYG editor -->
                        <div v-html="article.content"></div>
                    </div>

                    <!-- Share & Tags -->
                    <div
                        class="mt-16 pt-8 border-t border-gray-100 dark:border-white/10"
                    >
                        <p
                            class="text-xs font-bold text-gray-500 dark:text-gray-400 mb-6 uppercase tracking-widest font-unbounded"
                        >
                            Bagikan Artikel Ini
                        </p>
                        <div class="flex gap-4">
                            <button
                                class="w-12 h-12 rounded-full bg-[#1877F2] text-white flex items-center justify-center hover:scale-110 transition-transform shadow-lg"
                            >
                                <i class="fab fa-facebook-f text-lg"></i>
                            </button>
                            <button
                                class="w-12 h-12 rounded-full bg-[#1DA1F2] text-white flex items-center justify-center hover:scale-110 transition-transform shadow-lg"
                            >
                                <i class="fab fa-twitter text-lg"></i>
                            </button>
                            <button
                                class="w-12 h-12 rounded-full bg-[#25D366] text-white flex items-center justify-center hover:scale-110 transition-transform shadow-lg"
                            >
                                <i class="fab fa-whatsapp text-lg"></i>
                            </button>
                            <button
                                class="w-12 h-12 rounded-full bg-gray-900 dark:bg-gray-700 text-white flex items-center justify-center hover:scale-110 transition-transform shadow-lg"
                            >
                                <i class="fas fa-link text-lg"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="lg:col-span-4 space-y-12">
                    <!-- Related News -->
                    <div class="sticky top-24">
                        <div
                            class="bg-gray-50 dark:bg-[#111] rounded-3xl p-8 border border-gray-100 dark:border-white/5 shadow-2xl shadow-gray-200 dark:shadow-none"
                        >
                            <h3
                                class="text-lg font-black text-gray-900 dark:text-white mb-6 flex items-center font-unbounded"
                            >
                                <span
                                    class="w-1.5 h-6 bg-rose-600 rounded-full mr-3"
                                ></span>
                                Artikel Terkait
                            </h3>

                            <div class="space-y-6">
                                <Link
                                    v-for="related in relatedArticles"
                                    :key="related.id"
                                    :href="
                                        route(
                                            'frontend.news.show',
                                            related.slug,
                                        )
                                    "
                                    class="flex group items-start gap-4"
                                >
                                    <div
                                        class="w-20 h-20 flex-shrink-0 rounded-2xl overflow-hidden relative"
                                    >
                                        <img
                                            :src="related.image_url"
                                            :alt="related.title"
                                            class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-500"
                                        />
                                    </div>
                                    <div class="flex-1">
                                        <span
                                            class="inline-block text-[10px] text-rose-600 font-bold uppercase tracking-wider mb-1 font-unbounded"
                                            >{{ related.category?.name }}</span
                                        >
                                        <h4
                                            class="text-sm font-bold text-gray-900 dark:text-white line-clamp-2 group-hover:text-rose-600 transition-colors font-manrope leading-normal"
                                        >
                                            {{ related.title }}
                                        </h4>
                                        <span
                                            class="text-xs text-gray-500 dark:text-gray-400 mt-2 block font-manrope"
                                            >{{
                                                formatDate(
                                                    related.published_at ||
                                                        related.created_at,
                                                )
                                            }}</span
                                        >
                                    </div>
                                </Link>

                                <div
                                    v-if="relatedArticles.length === 0"
                                    class="text-gray-500 text-sm font-manrope text-center py-4"
                                >
                                    Tidak ada artikel terkait ditemukan.
                                </div>
                            </div>

                            <div
                                class="mt-8 pt-6 border-t border-gray-200 dark:border-white/10 text-center"
                            >
                                <Link
                                    :href="route('frontend.news.index')"
                                    class="text-xs font-bold font-unbounded text-rose-600 hover:text-rose-500 uppercase tracking-widest"
                                >
                                    Lihat Semua Berita
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
