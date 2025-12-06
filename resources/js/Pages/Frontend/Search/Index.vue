<script setup>
import { Head, Link, useForm } from "@inertiajs/vue3";
import FrontendLayout from "@/Layouts/FrontendLayout.vue";
import { ref } from "vue";

defineOptions({ layout: FrontendLayout });

const props = defineProps({
    results: Array,
    query: String,
    totalResults: Number,
});

const form = useForm({
    q: props.query || "",
});

const search = () => {
    form.get(route("frontend.search.index"), {
        preserveState: true,
        preserveScroll: true,
    });
};

const formatDate = (dateString) => {
    if (!dateString) return "";
    return new Date(dateString).toLocaleDateString("id-ID", {
        year: "numeric",
        month: "long",
        day: "numeric",
    });
};
</script>

<template>
    <Head :title="`Hasil Pencarian: ${query}`" />

    <!-- Hero Header -->
    <div class="relative bg-primary-950 py-24 overflow-hidden">
        <div class="absolute inset-0">
            <div class="absolute inset-0 hero-pattern opacity-10"></div>
            <div
                class="absolute top-0 right-0 w-1/2 h-full bg-gradient-to-l from-primary-900/50 to-transparent"
            ></div>
        </div>

        <div
            class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center"
        >
            <h1
                class="text-4xl md:text-5xl font-extrabold text-white mb-6 font-serif animate-fade-in-up"
            >
                Hasil Pencarian
            </h1>
            <p
                class="text-lg text-slate-300 max-w-2xl mx-auto animate-fade-in-up stagger-1 leading-relaxed"
            >
                Menampilkan hasil untuk kata kunci:
                <span class="text-brand-red font-bold">"{{ query }}"</span>
            </p>
        </div>
    </div>

    <div
        class="bg-gray-50 dark:bg-gray-900 min-h-screen py-12 -mt-10 relative z-20 rounded-t-[3rem]"
    >
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Search Bar -->
            <div
                class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-6 mb-12 animate-fade-in-up stagger-2 border border-gray-100 dark:border-gray-700"
            >
                <form
                    @submit.prevent="search"
                    class="flex flex-col sm:flex-row gap-4"
                >
                    <div class="relative flex-grow">
                        <i
                            class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"
                        ></i>
                        <input
                            type="text"
                            v-model="form.q"
                            placeholder="Cari berita atau rute..."
                            class="w-full pl-12 pr-4 py-3 rounded-xl border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 focus:ring-2 focus:ring-brand-red/20 focus:border-brand-red transition-all"
                        />
                    </div>
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="px-8 py-3 bg-brand-red text-white font-bold rounded-xl shadow-lg shadow-brand-red/20 hover:bg-red-700 transition-all hover:-translate-y-0.5"
                    >
                        Cari Lagi
                    </button>
                </form>
            </div>

            <!-- Results Count -->
            <div
                class="mb-6 flex items-center justify-between animate-fade-in-up stagger-3"
            >
                <p class="text-gray-600 dark:text-gray-400">
                    Ditemukan
                    <span class="font-bold text-gray-900 dark:text-white">{{
                        totalResults
                    }}</span>
                    hasil
                </p>
            </div>

            <!-- Results List -->
            <div
                v-if="results.length > 0"
                class="space-y-6 animate-fade-in-up stagger-4"
            >
                <div
                    v-for="(result, index) in results"
                    :key="index"
                    class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 border border-gray-100 dark:border-gray-700 group"
                >
                    <Link :href="result.url" class="block">
                        <div class="flex items-start justify-between">
                            <div>
                                <span
                                    v-if="result.type === 'news'"
                                    class="inline-block px-3 py-1 bg-blue-100 text-blue-600 text-xs font-bold rounded-full mb-3 uppercase tracking-wider"
                                >
                                    Berita
                                </span>
                                <span
                                    v-else
                                    class="inline-block px-3 py-1 bg-green-100 text-green-600 text-xs font-bold rounded-full mb-3 uppercase tracking-wider"
                                >
                                    Rute
                                </span>

                                <h3
                                    class="text-xl font-bold text-gray-900 dark:text-white mb-2 group-hover:text-brand-red transition-colors"
                                >
                                    {{ result.title }}
                                </h3>

                                <p
                                    class="text-gray-600 dark:text-gray-300 mb-4 line-clamp-2"
                                >
                                    {{ result.excerpt }}
                                </p>

                                <div
                                    class="flex items-center text-sm text-gray-400"
                                >
                                    <template v-if="result.published_at">
                                        <i class="far fa-calendar-alt mr-2"></i>
                                        {{ formatDate(result.published_at) }}
                                    </template>
                                </div>
                            </div>

                            <div
                                class="h-10 w-10 rounded-full bg-gray-50 dark:bg-gray-700 flex items-center justify-center group-hover:bg-brand-red group-hover:text-white transition-colors ml-4 shrink-0"
                            >
                                <i class="fas fa-chevron-right"></i>
                            </div>
                        </div>
                    </Link>
                </div>
            </div>

            <!-- Empty State -->
            <div
                v-else
                class="text-center py-16 bg-white dark:bg-gray-800 rounded-3xl border-2 border-dashed border-gray-200 dark:border-gray-700 animate-fade-in-up stagger-4"
            >
                <div
                    class="w-20 h-20 bg-gray-50 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-6 text-gray-400 text-3xl"
                >
                    <i class="fas fa-search"></i>
                </div>
                <h3
                    class="text-xl font-bold text-gray-900 dark:text-white mb-2"
                >
                    Tidak ada hasil ditemukan
                </h3>
                <p class="text-gray-500 max-w-md mx-auto">
                    Kami tidak dapat menemukan apa pun yang cocok dengan
                    pencarian Anda. Silakan coba kata kunci lain.
                </p>
            </div>
        </div>
    </div>
</template>
