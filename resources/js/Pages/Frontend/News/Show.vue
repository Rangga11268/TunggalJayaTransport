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

    <div class="min-h-screen bg-[#fcf9f8]">
        <!-- Hero -->
        <div class="relative h-[50vh] min-h-[400px] overflow-hidden">
            <img :src="article.image_url" :alt="article.title" class="absolute inset-0 w-full h-full object-cover" />
            <div class="absolute inset-0 bg-gradient-to-t from-[#1c1b1b] via-black/50 to-transparent"></div>
            <div class="absolute inset-0 flex items-end">
                <div class="max-w-5xl w-full mx-auto px-4 sm:px-6 lg:px-8 pb-12">
                    <Link :href="route('frontend.news.index')"
                        class="inline-flex items-center text-white/70 hover:text-white mb-6 transition-colors text-sm">
                        <i class="fas fa-arrow-left mr-2"></i> Kembali ke Berita
                    </Link>
                    <div class="mb-4">
                        <span class="inline-block px-3 py-1 rounded-lg bg-[#10207a]/90 text-white text-[10px] font-bold uppercase tracking-wider">
                            {{ article.category?.name || 'Umum' }}
                        </span>
                    </div>
                    <h1 class="text-2xl sm:text-4xl md:text-5xl font-bold text-white leading-tight font-unbounded">{{ article.title }}</h1>
                    <div class="flex items-center gap-4 mt-4 text-white/80 text-sm">
                        <span><i class="far fa-user-circle mr-1.5"></i>{{ article.author?.name || 'Admin' }}</span>
                        <span><i class="far fa-calendar-alt mr-1.5"></i>{{ formatDate(article.published_at || article.created_at) }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
                <!-- Content -->
                <div class="lg:col-span-8">
                    <div class="text-[#454652] text-[15px] leading-relaxed" v-html="article.safe_content"></div>

                    <div class="mt-12 pt-6 border-t border-[#ebe7e7]">
                        <p class="text-xs font-bold text-[#454652] uppercase tracking-wider mb-4">Bagikan Artikel Ini</p>
                        <div class="flex gap-3">
                            <button class="w-10 h-10 rounded-full bg-[#1877F2] text-white flex items-center justify-center hover:scale-105 transition-transform text-sm"><i class="fab fa-facebook-f"></i></button>
                            <button class="w-10 h-10 rounded-full bg-[#1DA1F2] text-white flex items-center justify-center hover:scale-105 transition-transform text-sm"><i class="fab fa-twitter"></i></button>
                            <button class="w-10 h-10 rounded-full bg-[#25D366] text-white flex items-center justify-center hover:scale-105 transition-transform text-sm"><i class="fab fa-whatsapp"></i></button>
                            <button class="w-10 h-10 rounded-full bg-gray-800 text-white flex items-center justify-center hover:scale-105 transition-transform text-sm"><i class="fas fa-link"></i></button>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="lg:col-span-4">
                    <div class="bg-white border border-[#ebe7e7] rounded-[12px] p-6 shadow-sm">
                        <h3 class="font-bold text-[15px] text-[#1c1b1b] mb-5 flex items-center">
                            <span class="w-1 h-5 bg-[#10207a] rounded-full mr-3"></span>
                            Artikel Terkait
                        </h3>
                        <div class="space-y-5">
                            <Link v-for="related in relatedArticles" :key="related.id"
                                :href="route('frontend.news.show', related.slug)"
                                class="flex group items-start gap-3">
                                <div class="w-16 h-16 flex-shrink-0 rounded-[10px] overflow-hidden">
                                    <img :src="related.image_url" :alt="related.title" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" />
                                </div>
                                <div class="flex-1 min-w-0">
                                    <span class="inline-block text-[10px] text-[#10207a] font-bold uppercase tracking-wider mb-1">{{ related.category?.name }}</span>
                                    <h4 class="text-sm font-bold text-[#1c1b1b] group-hover:text-[#10207a] transition-colors line-clamp-2 leading-snug">{{ related.title }}</h4>
                                    <span class="text-xs text-[#454652] mt-1 block">{{ formatDate(related.published_at || related.created_at) }}</span>
                                </div>
                            </Link>
                            <div v-if="!relatedArticles || relatedArticles.length === 0" class="text-sm text-gray-400 text-center py-4">Tidak ada artikel terkait.</div>
                        </div>
                        <div class="mt-6 pt-5 border-t border-[#ebe7e7] text-center">
                            <Link :href="route('frontend.news.index')" class="text-xs font-bold text-[#10207a] uppercase tracking-wider hover:underline">Lihat Semua Berita</Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
