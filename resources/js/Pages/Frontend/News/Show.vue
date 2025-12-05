<script setup>
import FrontendLayout from "@/Layouts/FrontendLayout.vue";
import { Head, Link } from "@inertiajs/vue3";

defineOptions({ layout: FrontendLayout });

const props = defineProps({
    article: Object,
    relatedArticles: Object, // Could be array or collection
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
    <Head :title="article.title" />

    <div class="bg-white dark:bg-gray-950 min-h-screen">
        <!-- Hero Header -->
        <div class="relative h-[50vh] min-h-[400px]">
            <img 
                :src="article.image_url" 
                :alt="article.title" 
                class="w-full h-full object-cover"
            >
            <div class="absolute inset-0 bg-gradient-to-t from-black via-black/50 to-transparent"></div>
            <div class="absolute inset-0 flex items-center justify-center">
                 <div class="max-w-4xl px-4 sm:px-6 lg:px-8 text-center">
                     <Link :href="route('frontend.news.index')" class="inline-flex items-center text-white/80 hover:text-white mb-6 transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i> Kembali ke Berita
                     </Link>
                     <div class="mb-4">
                        <span class="px-4 py-1.5 bg-brand-red text-white text-xs font-bold rounded-full uppercase tracking-wider shadow-lg">
                            {{ article.category?.name || 'Umum' }}
                        </span>
                     </div>
                     <h1 class="text-3xl sm:text-4xl md:text-5xl font-black text-white leading-tight mb-6 drop-shadow-lg">
                        {{ article.title }}
                     </h1>
                     <div class="flex items-center justify-center text-gray-300 text-sm sm:text-base space-x-6">
                        <div class="flex items-center">
                            <div class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center mr-2">
                                <i class="fas fa-user text-xs"></i>
                            </div>
                            {{ article.author?.name || 'Admin' }}
                        </div>
                        <div class="flex items-center">
                             <div class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center mr-2">
                                <i class="fas fa-calendar text-xs"></i>
                            </div>
                            {{ formatDate(article.published_at) }}
                        </div>
                     </div>
                 </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
                <!-- Main Content -->
                <div class="lg:col-span-2">
                    <article class="prose prose-lg prose-red dark:prose-invert max-w-none">
                        <div v-html="article.content"></div>
                    </article>
                    
                    <!-- Share & Tags Placeholder -->
                    <div class="mt-12 pt-8 border-t border-gray-100 dark:border-gray-800">
                        <p class="text-sm font-bold text-gray-900 dark:text-white mb-4">Bagikan Artikel:</p>
                        <div class="flex space-x-2">
                            <button class="w-10 h-10 rounded-full bg-blue-600 text-white flex items-center justify-center hover:bg-blue-700 transition-colors"><i class="fab fa-facebook-f"></i></button>
                            <button class="w-10 h-10 rounded-full bg-sky-500 text-white flex items-center justify-center hover:bg-sky-600 transition-colors"><i class="fab fa-twitter"></i></button>
                            <button class="w-10 h-10 rounded-full bg-green-500 text-white flex items-center justify-center hover:bg-green-600 transition-colors"><i class="fab fa-whatsapp"></i></button>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="lg:col-span-1">
                    <div class="sticky top-24">
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-6 border-l-4 border-brand-red pl-4">
                            Artikel Terkait
                        </h3>
                        
                        <div class="space-y-6">
                            <Link 
                                v-for="related in relatedArticles" 
                                :key="related.id"
                                :href="route('frontend.news.show', related.slug)"
                                class="flex group"
                            >
                                <div class="w-24 h-24 flex-shrink-0 rounded-xl overflow-hidden">
                                    <img 
                                        :src="related.image_url" 
                                        :alt="related.title" 
                                        class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-500"
                                    >
                                </div>
                                <div class="ml-4 flex flex-col justify-center">
                                    <span class="text-xs text-brand-red font-bold mb-1">{{ related.category?.name }}</span>
                                    <h4 class="text-sm font-bold text-gray-900 dark:text-white line-clamp-2 group-hover:text-brand-red transition-colors">
                                        {{ related.title }}
                                    </h4>
                                    <span class="text-xs text-gray-500 mt-1">{{ formatDate(related.published_at) }}</span>
                                </div>
                            </Link>

                            <div v-if="relatedArticles.length === 0" class="text-gray-500 text-sm">
                                Tidak ada artikel terkait.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
