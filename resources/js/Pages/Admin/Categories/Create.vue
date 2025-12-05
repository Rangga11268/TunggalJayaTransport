<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";

const form = useForm({
    name: "",
    description: "",
});

const submit = () => {
    form.post(route("admin.categories.store"));
};
</script>

<template>
    <Head title="Buat Kategori Baru" />

    <AdminLayout title="Tambah Kategori">
        <div class="max-w-xl mx-auto">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2
                        class="text-2xl font-bold text-gray-900 dark:text-white font-serif"
                    >
                        Kategori Baru
                    </h2>
                </div>
                <Link
                    :href="route('admin.categories.index')"
                    class="px-4 py-2 rounded-xl bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors shadow-sm text-sm font-medium"
                >
                    <i class="fas fa-arrow-left mr-2"></i> Kembali
                </Link>
            </div>

            <form
                @submit.prevent="submit"
                class="bg-white dark:bg-gray-800 rounded-3xl shadow-xl shadow-gray-100/50 dark:shadow-black/30 border border-gray-100 dark:border-gray-700/50 p-8"
            >
                <div class="mb-6">
                    <label
                        for="name"
                        class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2"
                    >
                        Nama Kategori
                    </label>
                    <input
                        id="name"
                        v-model="form.name"
                        type="text"
                        class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-brand-red focus:border-transparent transition-all"
                        placeholder="Contoh: Berita Utama"
                        required
                    />
                    <p
                        v-if="form.errors.name"
                        class="text-red-500 text-xs mt-1"
                    >
                        {{ form.errors.name }}
                    </p>
                </div>

                <div class="mb-8">
                    <label
                        for="description"
                        class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2"
                    >
                        Deskripsi
                    </label>
                    <textarea
                        id="description"
                        v-model="form.description"
                        rows="4"
                        class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-brand-red focus:border-transparent transition-all resize-none"
                        placeholder="Deskripsi singkat kategori..."
                    ></textarea>
                    <p
                        v-if="form.errors.description"
                        class="text-red-500 text-xs mt-1"
                    >
                        {{ form.errors.description }}
                    </p>
                </div>

                <div
                    class="flex items-center justify-end gap-4 border-t border-gray-100 dark:border-gray-700 pt-6"
                >
                    <Link
                        :href="route('admin.categories.index')"
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
                            form.processing ? "Menyimpan..." : "Simpan Kategori"
                        }}</span>
                    </button>
                </div>
            </form>
        </div>
    </AdminLayout>
</template>
