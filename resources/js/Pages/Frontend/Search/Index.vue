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
    if (!dateString) return "Tanggal Belum Tersedia";
    const date = new Date(dateString);
    if (isNaN(date.getTime()) || date.getFullYear() <= 1970)
        return "Tanggal Belum Tersedia";
    return date.toLocaleDateString("id-ID", {
        year: "numeric",
        month: "long",
        day: "numeric",
    });
};
</script>

<template>
    <Head :title="`Hasil Pencarian: ${query}`" />

    <div
        class="bg-gray-50 dark:bg-[#050505] min-h-screen font-sans selection:bg-rose-600 selection:text-white"
    >
        <!-- Hero Header -->
        <div
            class="relative pt-32 pb-16 px-4 sm:px-6 lg:px-8 overflow-hidden text-center"
        >
            <!-- Decor -->
            <div
                class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[800px] h-[400px] bg-rose-600/10 rounded-full blur-[120px] -z-10"
            ></div>

            <span
                class="inline-block py-1 px-3 rounded-full bg-rose-50 dark:bg-rose-900/10 text-rose-600 border border-rose-100 dark:border-rose-900/20 text-xs font-bold tracking-widest uppercase mb-6 font-unbounded"
            >
                Ditemukan {{ totalResults }} Hasil
            </span>
            <h1
                class="text-4xl md:text-6xl font-black text-gray-900 dark:text-white mb-6 font-unbounded"
            >
                Hasil <span class="text-rose-600">Pencarian</span>
            </h1>
            <p
                class="text-lg text-gray-500 dark:text-gray-400 max-w-2xl mx-auto font-manrope"
            >
                Menampilkan hasil untuk kata kunci:
                <span class="text-rose-600 font-bold">"{{ query }}"</span>
            </p>
        </div>

        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 pb-32">
            <!-- Search Bar (disabled sticky to avoid blocking on scroll) -->
            <div
                class="relative z-0 bg-white/80 dark:bg-[#111]/80 backdrop-blur-2xl rounded-[1.5rem] p-4 mb-12 border border-gray-100 dark:border-white/5 shadow-2xl"
            >
                <form @submit.prevent="search" class="flex items-center gap-4">
                    <div class="relative flex-grow">
                        <i
                            class="fas fa-search absolute left-5 top-1/2 -translate-y-1/2 text-rose-600"
                        ></i>
                        <input
                            type="text"
                            v-model="form.q"
                            placeholder="Cari berita atau rute..."
                            class="w-full pl-12 pr-4 py-4 rounded-xl bg-gray-50 dark:bg-black/50 border-transparent focus:bg-white focus:dark:bg-black focus:border-rose-500 focus:ring-0 transition-all font-manrope text-gray-900 dark:text-white"
                        />
                    </div>
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="px-8 py-4 bg-rose-600 text-white font-black font-unbounded text-sm rounded-xl shadow-lg shadow-rose-600/30 hover:bg-rose-700 transition-all active:scale-95"
                    >
                        Cari
                    </button>
                </form>
            </div>

            <!-- Results List -->
            <div v-if="results.length > 0" class="space-y-6">
                <div
                    v-for="(result, index) in results"
                    :key="index"
                    class="group bg-white dark:bg-[#111] rounded-[2rem] p-8 border border-gray-100 dark:border-white/5 shadow-sm hover:shadow-2xl hover:shadow-rose-600/10 transition-all duration-500"
                >
                    <Link
                        :href="result.url"
                        class="flex flex-col md:flex-row gap-6 items-start"
                    >
                        <div class="flex-grow">
                            <div class="flex items-center gap-4 mb-4">
                                <span
                                    v-if="result.type === 'news'"
                                    class="px-3 py-1 bg-blue-50 dark:bg-blue-900/10 text-blue-600 text-[10px] font-bold rounded-lg uppercase tracking-widest font-unbounded border border-blue-100 dark:border-blue-900/20"
                                >
                                    Berita & Artikel
                                </span>
                                <span
                                    v-else
                                    class="px-3 py-1 bg-emerald-50 dark:bg-emerald-900/10 text-emerald-600 text-[10px] font-bold rounded-lg uppercase tracking-widest font-unbounded border border-emerald-100 dark:border-emerald-900/20"
                                >
                                    Rute Perjalanan
                                </span>

                                <span
                                    v-if="
                                        result.published_at || result.created_at
                                    "
                                    class="text-xs text-gray-400 font-manrope flex items-center gap-2"
                                >
                                    <i
                                        class="far fa-calendar-alt text-rose-600"
                                    ></i>
                                    {{
                                        formatDate(
                                            result.published_at ||
                                                result.created_at,
                                        )
                                    }}
                                </span>
                            </div>

                            <h3
                                class="text-xl md:text-2xl font-black text-gray-900 dark:text-white mb-3 group-hover:text-rose-600 transition-colors font-unbounded"
                            >
                                {{ result.title }}
                            </h3>

                            <p
                                class="text-gray-500 dark:text-gray-400 font-manrope line-clamp-2 leading-relaxed"
                            >
                                {{ result.excerpt }}
                            </p>
                        </div>

                        <div
                            class="w-12 h-12 rounded-2xl bg-gray-50 dark:bg-[#1a1a1a] flex items-center justify-center text-gray-400 group-hover:bg-rose-600 group-hover:text-white transition-all self-center md:self-center"
                        >
                            <i class="fas fa-arrow-right"></i>
                        </div>
                    </Link>
                </div>
            </div>

            <!-- Empty State -->
            <div
                v-else
                class="text-center py-20 px-4 bg-white dark:bg-[#111] rounded-[2.5rem] border-2 border-dashed border-gray-200 dark:border-white/5"
            >
                <div
                    class="w-20 h-20 bg-gray-50 dark:bg-white/5 rounded-full flex items-center justify-center mx-auto mb-6"
                >
                    <i class="fas fa-search-minus text-3xl text-gray-300"></i>
                </div>
                <h3
                    class="text-2xl font-black font-unbounded text-gray-900 dark:text-white mb-3"
                >
                    Tidak Ada Hasil
                </h3>
                <p
                    class="text-gray-500 dark:text-gray-400 font-manrope max-w-sm mx-auto mb-8"
                >
                    Maaf, kami tidak menemukan hasil yang cocok untuk kata kunci
                    tersebut. Coba gunakan kata kunci lain.
                </p>
                <div class="flex justify-center gap-4">
                    <Link
                        :href="route('frontend.news.index')"
                        class="px-6 py-3 text-sm font-bold font-unbounded text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-900/10 rounded-xl transition-all"
                    >
                        Lihat Berita
                    </Link>
                    <Link
                        :href="route('frontend.routes.index')"
                        class="px-6 py-3 text-sm font-bold font-unbounded text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-900/10 rounded-xl transition-all"
                    >
                        Lihat Rute
                    </Link>
                </div>
            </div>
        </div>
    </div>
</template>
