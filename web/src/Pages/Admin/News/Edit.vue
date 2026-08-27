<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import { ref, onMounted } from "vue";

const props = defineProps({
    article: Object,
    categories: Array,
});

const form = useForm({
    _method: "PUT",
    title: props.article.title || "",
    category_id: props.article.category_id || "",
    content: props.article.content || "",
    excerpt: props.article.excerpt || "",
    featured_image: null,
    is_published: Boolean(props.article.is_published),
});

const imagePreview = ref(props.article.image_url || null);

const handleImageUpload = (event) => {
    const file = event.target.files[0];
    if (file) {
        form.featured_image = file;
        imagePreview.value = URL.createObjectURL(file);
    }
};

const submit = () => {
    form.post(route("admin.news.update", props.article.id), {
        forceFormData: true,
    });
};
</script>

<template>
    <Head title="Edit Berita" />

    <AdminLayout title="Edit Berita">
        <div class="max-w-4xl mx-auto">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2
                        class="text-2xl font-bold text-gray-900 dark:text-white font-serif"
                    >
                        Edit Artikel
                    </h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                        Perbarui konten berita "{{ article.title }}".
                    </p>
                </div>
                <Link
                    :href="route('admin.news.index')"
                    class="px-4 py-2 rounded-xl bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors shadow-sm text-sm font-medium"
                >
                    <i class="fas fa-arrow-left mr-2"></i> Kembali
                </Link>
            </div>

            <form
                @submit.prevent="submit"
                class="bg-white dark:bg-gray-800 rounded-3xl shadow-xl shadow-gray-100/50 dark:shadow-black/30 border border-gray-100 dark:border-gray-700/50 p-8"
            >
                <!-- Title -->
                <div class="mb-6">
                    <label
                        for="title"
                        class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2"
                    >
                        Judul Artikel
                    </label>
                    <input
                        id="title"
                        v-model="form.title"
                        type="text"
                        class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-brand-red focus:border-transparent transition-all"
                        placeholder="Contoh: Pembukaan Rute Baru Jakarta - Surabaya"
                        required
                    />
                    <p
                        v-if="form.errors.title"
                        class="text-red-500 text-xs mt-1"
                    >
                        {{ form.errors.title }}
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <!-- Category -->
                    <div>
                        <label
                            for="category"
                            class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2"
                        >
                            Kategori
                        </label>
                        <div class="relative">
                            <select
                                id="category"
                                v-model="form.category_id"
                                class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-brand-red focus:border-transparent transition-all appearance-none"
                            >
                                <option value="" disabled>
                                    Pilih Kategori
                                </option>
                                <option
                                    v-for="category in categories"
                                    :key="category.id"
                                    :value="category.id"
                                >
                                    {{ category.name }}
                                </option>
                            </select>
                            <div
                                class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-gray-500"
                            >
                                <i class="fas fa-chevron-down text-xs"></i>
                            </div>
                        </div>
                        <p
                            v-if="form.errors.category_id"
                            class="text-red-500 text-xs mt-1"
                        >
                            {{ form.errors.category_id }}
                        </p>
                    </div>

                    <!-- Status Toggle -->
                    <div class="flex flex-col justify-center">
                        <label
                            class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2"
                        >
                            Status Publikasi
                        </label>
                        <div class="flex items-center gap-3">
                            <button
                                type="button"
                                @click="form.is_published = !form.is_published"
                                :class="[
                                    'relative inline-flex h-8 w-14 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-brand-red focus:ring-offset-2',
                                    form.is_published
                                        ? 'bg-green-500'
                                        : 'bg-gray-200 dark:bg-gray-700',
                                ]"
                            >
                                <span class="sr-only">Use setting</span>
                                <span
                                    aria-hidden="true"
                                    :class="[
                                        'pointer-events-none inline-block h-7 w-7 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out',
                                        form.is_published
                                            ? 'translate-x-6'
                                            : 'translate-x-0',
                                    ]"
                                />
                            </button>
                            <span
                                class="text-sm font-medium"
                                :class="
                                    form.is_published
                                        ? 'text-green-600'
                                        : 'text-gray-500'
                                "
                            >
                                {{
                                    form.is_published
                                        ? "Langsung Terbit (Published)"
                                        : "Simpan sebagai Draf"
                                }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Excerpt -->
                <div class="mb-6">
                    <label
                        for="excerpt"
                        class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2"
                    >
                        Ringkasan (Excerpt)
                    </label>
                    <textarea
                        id="excerpt"
                        v-model="form.excerpt"
                        rows="2"
                        class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-brand-red focus:border-transparent transition-all resize-none"
                        placeholder="Ringkasan singkat untuk ditampilkan di kartu berita..."
                    ></textarea>
                    <p class="text-xs text-gray-400 mt-1">
                        Opsional. Jika kosong, akan diambil dari awal konten.
                    </p>
                </div>

                <!-- Content -->
                <div class="mb-6">
                    <label
                        for="content"
                        class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2"
                    >
                        Konten Artikel
                    </label>
                    <textarea
                        id="content"
                        v-model="form.content"
                        rows="12"
                        class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-brand-red focus:border-transparent transition-all"
                        placeholder="Tulis konten berita lengkap di sini..."
                        required
                    ></textarea>
                    <p
                        v-if="form.errors.content"
                        class="text-red-500 text-xs mt-1"
                    >
                        {{ form.errors.content }}
                    </p>
                </div>

                <!-- Featured Image -->
                <div class="mb-8">
                    <label
                        class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2"
                    >
                        Gambar Utama
                    </label>
                    <div
                        class="border-2 border-dashed border-gray-300 dark:border-gray-700 rounded-2xl p-6 flex flex-col items-center justify-center text-center hover:border-brand-red dark:hover:border-brand-red transition-colors cursor-pointer bg-gray-50 dark:bg-gray-900/50"
                        @click="$refs.fileInput.click()"
                    >
                        <input
                            ref="fileInput"
                            type="file"
                            class="hidden"
                            accept="image/*"
                            @change="handleImageUpload"
                        />

                        <div
                            v-if="imagePreview"
                            class="relative w-full max-w-sm mb-4 group"
                        >
                            <img
                                :src="imagePreview"
                                class="w-full h-48 object-cover rounded-lg shadow-md"
                            />
                            <div
                                class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity rounded-lg"
                            >
                                <span class="text-white text-sm font-bold"
                                    >Ganti Gambar</span
                                >
                            </div>
                        </div>
                        <div v-else class="py-4">
                            <div
                                class="h-12 w-12 rounded-full bg-gray-100 dark:bg-gray-800 flex items-center justify-center mx-auto mb-3 text-gray-400"
                            >
                                <i class="fas fa-cloud-upload-alt text-xl"></i>
                            </div>
                            <p
                                class="text-sm font-medium text-gray-900 dark:text-white"
                            >
                                Klik untuk upload gambar baru
                            </p>
                            <p class="text-xs text-gray-500 mt-1">
                                PNG, JPG, WEBP hingga 2MB
                            </p>
                        </div>
                    </div>
                    <p
                        v-if="form.errors.featured_image"
                        class="text-red-500 text-xs mt-1"
                    >
                        {{ form.errors.featured_image }}
                    </p>
                </div>

                <!-- Actions -->
                <div
                    class="flex items-center justify-end gap-4 border-t border-gray-100 dark:border-gray-700 pt-6"
                >
                    <Link
                        :href="route('admin.news.index')"
                        class="px-6 py-3 rounded-xl text-gray-700 dark:text-gray-300 font-bold hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors"
                    >
                        Batal
                    </Link>
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="px-8 py-3 rounded-xl bg-brand-red text-white font-bold shadow-lg shadow-brand-red/30 hover:bg-red-700 hover:shadow-brand-red/50 transition-all transform active:scale-95 disabled:opacity-70 disabled:cursor-not-allowed flex items-center gap-2"
                    >
                        <i
                            v-if="form.processing"
                            class="fas fa-spinner fa-spin"
                        ></i>
                        <span>{{
                            form.processing
                                ? "Menyimpan..."
                                : "Perbarui Artikel"
                        }}</span>
                    </button>
                </div>
            </form>
        </div>
    </AdminLayout>
</template>
