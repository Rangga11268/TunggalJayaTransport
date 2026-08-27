<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import { ref, watch } from "vue";

const form = useForm({
    code: "",
    description: "",
    discount_type: "percentage",
    discount_amount: 0,
    max_discount_amount: 0,
    min_purchase_amount: 0,
    start_date: "",
    end_date: "",
    usage_limit: "",
    is_active: true,
});

const submit = () => {
    form.post(route("admin.promo-codes.store"), {
        onSuccess: () => {
            // Success handled by flash message
        },
    });
};

watch(
    () => form.discount_type,
    (newVal) => {
        if (newVal === "fixed") {
            form.max_discount_amount = 0; // Reset max discount if fixed
        }
    }
);
</script>

<template>
    <Head title="Buat Kode Promo" />

    <AdminLayout title="Buat Kode Promo">
        <div class="mb-8">
            <Link
                :href="route('admin.promo-codes.index')"
                class="inline-flex items-center gap-2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition-colors mb-4"
            >
                <i class="fas fa-arrow-left"></i>
                <span>Kembali ke Daftar Promo</span>
            </Link>
            <h2
                class="text-2xl font-bold text-gray-900 dark:text-white font-serif"
            >
                Buat Kode Promo Baru
            </h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                Isi formulir di bawah ini untuk membuat kode promo baru.
            </p>
        </div>

        <div class="max-w-4xl">
            <form
                @submit.prevent="submit"
                class="bg-white dark:bg-gray-800 rounded-3xl shadow-xl shadow-gray-100/50 dark:shadow-black/30 border border-gray-100 dark:border-gray-700/50 p-6 md:p-8"
            >
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Kode Promo -->
                    <div class="col-span-1 md:col-span-2">
                        <label
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
                        >
                            Kode Promo <span class="text-red-500">*</span>
                        </label>
                        <input
                            v-model="form.code"
                            type="text"
                            placeholder="Contoh: LEBARAN2025"
                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-brand-red/50 outline-none transition-all uppercase font-mono tracking-wider"
                            required
                        />
                        <p
                            v-if="form.errors.code"
                            class="text-red-500 text-xs mt-1"
                        >
                            {{ form.errors.code }}
                        </p>
                    </div>

                    <!-- Jenis Diskon -->
                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
                        >
                            Jenis Diskon <span class="text-red-500">*</span>
                        </label>
                        <select
                            v-model="form.discount_type"
                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-brand-red/50 outline-none transition-all"
                        >
                            <option value="percentage">Persentase (%)</option>
                            <option value="fixed">Nominal Tetap (Rp)</option>
                        </select>
                        <p
                            v-if="form.errors.discount_type"
                            class="text-red-500 text-xs mt-1"
                        >
                            {{ form.errors.discount_type }}
                        </p>
                    </div>

                    <!-- Besaran Diskon -->
                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
                        >
                            Besaran Diskon <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <span
                                v-if="form.discount_type === 'fixed'"
                                class="absolute left-4 top-2.5 text-gray-500 dark:text-gray-400"
                                >Rp</span
                            >
                            <input
                                v-model="form.discount_amount"
                                type="number"
                                min="0"
                                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-brand-red/50 outline-none transition-all"
                                :class="{
                                    'pl-10': form.discount_type === 'fixed',
                                    'pr-8': form.discount_type === 'percentage',
                                }"
                                required
                            />
                            <span
                                v-if="form.discount_type === 'percentage'"
                                class="absolute right-4 top-2.5 text-gray-500 dark:text-gray-400"
                                >%</span
                            >
                        </div>
                        <p
                            v-if="form.errors.discount_amount"
                            class="text-red-500 text-xs mt-1"
                        >
                            {{ form.errors.discount_amount }}
                        </p>
                    </div>

                    <!-- Minimal Pembelian -->
                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
                        >
                            Minimal Pembelian
                        </label>
                        <div class="relative">
                            <span
                                class="absolute left-4 top-2.5 text-gray-500 dark:text-gray-400"
                                >Rp</span
                            >
                            <input
                                v-model="form.min_purchase_amount"
                                type="number"
                                min="0"
                                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-brand-red/50 outline-none transition-all pl-10"
                            />
                        </div>
                        <p
                            v-if="form.errors.min_purchase_amount"
                            class="text-red-500 text-xs mt-1"
                        >
                            {{ form.errors.min_purchase_amount }}
                        </p>
                    </div>

                    <!-- Max Diskon (Hanya untuk Persentase) -->
                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
                        >
                            Maksimal Diskon
                            <span
                                v-if="form.discount_type === 'fixed'"
                                class="text-xs font-normal text-gray-400 ml-1"
                                >(Tidak berlaku untuk Nominal Tetap)</span
                            >
                        </label>
                        <div class="relative">
                            <span
                                class="absolute left-4 top-2.5 text-gray-500 dark:text-gray-400"
                                >Rp</span
                            >
                            <input
                                v-model="form.max_discount_amount"
                                type="number"
                                min="0"
                                :disabled="form.discount_type === 'fixed'"
                                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-brand-red/50 outline-none transition-all pl-10 disabled:opacity-50 disabled:cursor-not-allowed"
                            />
                        </div>
                        <p
                            v-if="form.errors.max_discount_amount"
                            class="text-red-500 text-xs mt-1"
                        >
                            {{ form.errors.max_discount_amount }}
                        </p>
                    </div>

                    <!-- Mulai Berlaku -->
                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
                        >
                            Mulai Berlaku
                        </label>
                        <input
                            v-model="form.start_date"
                            type="datetime-local"
                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-brand-red/50 outline-none transition-all"
                        />
                        <p
                            v-if="form.errors.start_date"
                            class="text-red-500 text-xs mt-1"
                        >
                            {{ form.errors.start_date }}
                        </p>
                    </div>

                    <!-- Selesai Berlaku -->
                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
                        >
                            Selesai Berlaku
                        </label>
                        <input
                            v-model="form.end_date"
                            type="datetime-local"
                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-brand-red/50 outline-none transition-all"
                        />
                        <p
                            v-if="form.errors.end_date"
                            class="text-red-500 text-xs mt-1"
                        >
                            {{ form.errors.end_date }}
                        </p>
                    </div>

                    <!-- Batas Penggunaan -->
                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
                        >
                            Batas Penggunaan (Global)
                        </label>
                        <input
                            v-model="form.usage_limit"
                            type="number"
                            min="1"
                            placeholder="Kosongkan jika tidak terbatas"
                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-brand-red/50 outline-none transition-all"
                        />
                        <p class="text-xs text-gray-500 mt-1">
                            Jumlah maksimal kode ini bisa digunakan oleh seluruh
                            user.
                        </p>
                        <p
                            v-if="form.errors.usage_limit"
                            class="text-red-500 text-xs mt-1"
                        >
                            {{ form.errors.usage_limit }}
                        </p>
                    </div>

                    <!-- Status Aktif -->
                    <div class="flex items-center pt-8">
                        <label class="flex items-center gap-3 cursor-pointer">
                            <div class="relative">
                                <input
                                    type="checkbox"
                                    v-model="form.is_active"
                                    class="sr-only peer"
                                />
                                <div
                                    class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-brand-red/20 dark:peer-focus:ring-brand-red/30 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-brand-red"
                                ></div>
                            </div>
                            <span
                                class="text-sm font-medium text-gray-900 dark:text-gray-300"
                                >Aktifkan Kode Promo</span
                            >
                        </label>
                    </div>

                    <!-- Deskripsi -->
                    <div class="col-span-1 md:col-span-2">
                        <label
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
                        >
                            Deskripsi (Opsional)
                        </label>
                        <textarea
                            v-model="form.description"
                            rows="2"
                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-brand-red/50 outline-none transition-all resize-none"
                        ></textarea>
                    </div>
                </div>

                <div class="mt-8 flex justify-end gap-4">
                    <Link
                        :href="route('admin.promo-codes.index')"
                        class="px-6 py-2.5 rounded-xl border border-gray-300 text-gray-700 font-semibold hover:bg-gray-50 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700 transition-colors"
                    >
                        Batal
                    </Link>
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="px-6 py-2.5 rounded-xl bg-brand-red text-white font-bold shadow-lg shadow-brand-red/30 hover:bg-red-700 hover:shadow-brand-red/50 transition-all duration-300 disabled:opacity-70 disabled:cursor-not-allowed"
                    >
                        <span v-if="form.processing">Menyimpan...</span>
                        <span v-else>Simpan Promo</span>
                    </button>
                </div>
            </form>
        </div>
    </AdminLayout>
</template>
