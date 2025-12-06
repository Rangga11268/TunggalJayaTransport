<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { Head, useForm } from "@inertiajs/vue3";

const props = defineProps({
    settings: Object,
});

const form = useForm({
    site_name: props.settings.site_name,
    contact_email: props.settings.contact_email,
    contact_phone: props.settings.contact_phone,
    // Add other settings as needed
});

const submit = () => {
    form.post(route("admin.settings.update"), {
        preserveScroll: true,
    });
};
</script>

<template>
    <Head title="Pengaturan Situs" />

    <AdminLayout title="Pengaturan Situs">
        <div class="max-w-4xl mx-auto">
            <div class="mb-8">
                <h2
                    class="text-2xl font-bold text-gray-900 dark:text-white font-serif"
                >
                    Pengaturan Situs
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    Konfigurasi informasi dasar situs web.
                </p>
            </div>

            <form
                @submit.prevent="submit"
                class="grid grid-cols-1 md:grid-cols-3 gap-8"
            >
                <!-- General Info -->
                <div class="md:col-span-2">
                    <div
                        class="bg-white dark:bg-gray-800 rounded-3xl p-8 shadow-xl shadow-gray-100/50 dark:shadow-black/30 border border-gray-100 dark:border-gray-700/50 space-y-6"
                    >
                        <h3
                            class="text-lg font-bold text-gray-900 dark:text-white mb-4 border-b border-gray-100 dark:border-gray-700 pb-4"
                        >
                            Informasi Umum
                        </h3>

                        <!-- Site Name -->
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
                                >Nama Situs</label
                            >
                            <input
                                v-model="form.site_name"
                                type="text"
                                class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-brand-red/50 focus:border-brand-red outline-none transition-all"
                                :class="{
                                    'border-red-500 focus:ring-red-500/50':
                                        form.errors.site_name,
                                }"
                            />
                            <p
                                v-if="form.errors.site_name"
                                class="text-red-500 text-xs mt-1"
                            >
                                {{ form.errors.site_name }}
                            </p>
                        </div>

                        <!-- Contact Email -->
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
                                >Email Kontak</label
                            >
                            <input
                                v-model="form.contact_email"
                                type="email"
                                class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-brand-red/50 focus:border-brand-red outline-none transition-all"
                                :class="{
                                    'border-red-500 focus:ring-red-500/50':
                                        form.errors.contact_email,
                                }"
                            />
                            <p
                                v-if="form.errors.contact_email"
                                class="text-red-500 text-xs mt-1"
                            >
                                {{ form.errors.contact_email }}
                            </p>
                        </div>

                        <!-- Contact Phone -->
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
                                >No. Telepon Kontak</label
                            >
                            <input
                                v-model="form.contact_phone"
                                type="text"
                                class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-brand-red/50 focus:border-brand-red outline-none transition-all"
                                :class="{
                                    'border-red-500 focus:ring-red-500/50':
                                        form.errors.contact_phone,
                                }"
                            />
                            <p
                                v-if="form.errors.contact_phone"
                                class="text-red-500 text-xs mt-1"
                            >
                                {{ form.errors.contact_phone }}
                            </p>
                        </div>

                        <!-- Submit -->
                        <div class="flex justify-end pt-4">
                            <button
                                type="submit"
                                :disabled="form.processing"
                                class="px-8 py-3 rounded-xl bg-brand-red text-white font-bold shadow-lg shadow-brand-red/30 hover:bg-red-700 hover:shadow-brand-red/50 transition-all duration-300 disabled:opacity-70 disabled:cursor-not-allowed flex items-center gap-2"
                            >
                                <i
                                    v-if="form.processing"
                                    class="fas fa-spinner fa-spin"
                                ></i>
                                <span v-else>Simpan Pengaturan</span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Info Box -->
                <div class="md:col-span-1">
                    <div
                        class="bg-blue-50 dark:bg-blue-900/20 rounded-3xl p-6 border border-blue-100 dark:border-blue-900/30"
                    >
                        <div class="flex items-start gap-4 mb-4">
                            <div
                                class="w-10 h-10 rounded-full bg-blue-100 dark:bg-blue-800 flex items-center justify-center text-blue-600 dark:text-blue-300 flex-shrink-0"
                            >
                                <i class="fas fa-info-circle text-xl"></i>
                            </div>
                            <div>
                                <h4
                                    class="font-bold text-gray-900 dark:text-white mb-1"
                                >
                                    Catatan
                                </h4>
                                <p
                                    class="text-sm text-gray-600 dark:text-gray-400"
                                >
                                    Pengaturan ini hanya simulasi untuk saat
                                    ini. Dalam aplikasi nyata, data akan
                                    disimpan ke database atau file konfigurasi.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </AdminLayout>
</template>
