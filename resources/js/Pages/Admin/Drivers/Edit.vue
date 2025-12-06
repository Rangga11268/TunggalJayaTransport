<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { Head, Link, useForm, router } from "@inertiajs/vue3";
import { ref } from "vue";

const props = defineProps({
    driver: Object,
});

const form = useForm({
    _method: "PUT",
    name: props.driver.name,
    employee_id: props.driver.employee_id,
    license_number: props.driver.license_number,
    phone: props.driver.phone,
    email: props.driver.email,
    address: props.driver.address,
    status: props.driver.status,
    image: null,
});

const imagePreview = ref(props.driver.image_url);

const handleImageUpload = (event) => {
    const file = event.target.files[0];
    if (file) {
        form.image = file;
        imagePreview.value = URL.createObjectURL(file);
    }
};

const submit = () => {
    form.post(route("admin.drivers.update", props.driver.id), {
        preserveScroll: true,
        forceFormData: true,
    });
};
</script>

<template>
    <Head title="Edit Driver" />

    <AdminLayout title="Edit Driver">
        <div class="max-w-4xl mx-auto">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h2
                        class="text-2xl font-bold text-gray-900 dark:text-white font-serif"
                    >
                        Edit Driver
                    </h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                        Perbarui informasi supir.
                    </p>
                </div>
                <Link
                    :href="route('admin.drivers.index')"
                    class="px-5 py-2.5 rounded-xl bg-gray-100 text-gray-700 font-semibold hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700 transition-all duration-300 flex items-center gap-2"
                >
                    <i class="fas fa-arrow-left"></i>
                    <span>Kembali</span>
                </Link>
            </div>

            <form
                @submit.prevent="submit"
                class="grid grid-cols-1 md:grid-cols-3 gap-8"
            >
                <!-- Image Upload Section -->
                <div class="md:col-span-1">
                    <div
                        class="bg-white dark:bg-gray-800 rounded-3xl p-6 shadow-xl shadow-gray-100/50 dark:shadow-black/30 border border-gray-100 dark:border-gray-700/50 text-center"
                    >
                        <div class="relative w-40 h-40 mx-auto mb-4 group">
                            <div
                                class="w-full h-full rounded-full overflow-hidden bg-gray-100 dark:bg-gray-700 border-4 border-white dark:border-gray-800 shadow-lg relative"
                            >
                                <img
                                    v-if="imagePreview"
                                    :src="imagePreview"
                                    class="w-full h-full object-cover"
                                />
                                <div
                                    v-else
                                    class="w-full h-full flex items-center justify-center text-gray-400 dark:text-gray-500"
                                >
                                    <i class="fas fa-user-tie text-5xl"></i>
                                </div>

                                <!-- Overlay for upload -->
                                <label
                                    for="image-upload"
                                    class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer text-white font-medium text-sm"
                                >
                                    <i class="fas fa-camera mr-2"></i> Ubah
                                </label>
                            </div>
                        </div>

                        <div class="text-center">
                            <label
                                for="image-upload"
                                class="inline-block px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg text-sm font-medium cursor-pointer hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors"
                            >
                                Pilih Foto
                            </label>
                            <input
                                type="file"
                                id="image-upload"
                                @change="handleImageUpload"
                                class="hidden"
                                accept="image/*"
                            />
                            <p class="text-xs text-gray-500 mt-2">
                                Format: JPG, PNG. Maks: 2MB.
                            </p>
                            <p
                                v-if="form.errors.image"
                                class="text-red-500 text-xs mt-1"
                            >
                                {{ form.errors.image }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Form Fields -->
                <div class="md:col-span-2">
                    <div
                        class="bg-white dark:bg-gray-800 rounded-3xl p-8 shadow-xl shadow-gray-100/50 dark:shadow-black/30 border border-gray-100 dark:border-gray-700/50 space-y-6"
                    >
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Name -->
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
                                    >Nama Lengkap</label
                                >
                                <input
                                    v-model="form.name"
                                    type="text"
                                    placeholder="Nama Lengkap"
                                    class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-brand-red/50 focus:border-brand-red outline-none transition-all"
                                    :class="{
                                        'border-red-500 focus:ring-red-500/50':
                                            form.errors.name,
                                    }"
                                />
                                <p
                                    v-if="form.errors.name"
                                    class="text-red-500 text-xs mt-1"
                                >
                                    {{ form.errors.name }}
                                </p>
                            </div>

                            <!-- Employee ID -->
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
                                    >ID Karyawan</label
                                >
                                <input
                                    v-model="form.employee_id"
                                    type="text"
                                    placeholder="Contoh: DRV001"
                                    class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-brand-red/50 focus:border-brand-red outline-none transition-all"
                                    :class="{
                                        'border-red-500 focus:ring-red-500/50':
                                            form.errors.employee_id,
                                    }"
                                />
                                <p
                                    v-if="form.errors.employee_id"
                                    class="text-red-500 text-xs mt-1"
                                >
                                    {{ form.errors.employee_id }}
                                </p>
                            </div>

                            <!-- License Number -->
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
                                    >Nomor SIM</label
                                >
                                <input
                                    v-model="form.license_number"
                                    type="text"
                                    placeholder="Nomor SIM B1/B2"
                                    class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-brand-red/50 focus:border-brand-red outline-none transition-all"
                                    :class="{
                                        'border-red-500 focus:ring-red-500/50':
                                            form.errors.license_number,
                                    }"
                                />
                                <p
                                    v-if="form.errors.license_number"
                                    class="text-red-500 text-xs mt-1"
                                >
                                    {{ form.errors.license_number }}
                                </p>
                            </div>

                            <!-- Phone -->
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
                                    >No. Telepon</label
                                >
                                <input
                                    v-model="form.phone"
                                    type="text"
                                    placeholder="08123456789"
                                    class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-brand-red/50 focus:border-brand-red outline-none transition-all"
                                    :class="{
                                        'border-red-500 focus:ring-red-500/50':
                                            form.errors.phone,
                                    }"
                                />
                                <p
                                    v-if="form.errors.phone"
                                    class="text-red-500 text-xs mt-1"
                                >
                                    {{ form.errors.phone }}
                                </p>
                            </div>
                        </div>

                        <!-- Email -->
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
                                >Email (Opsional)</label
                            >
                            <input
                                v-model="form.email"
                                type="email"
                                placeholder="email@contoh.com"
                                class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-brand-red/50 focus:border-brand-red outline-none transition-all"
                                :class="{
                                    'border-red-500 focus:ring-red-500/50':
                                        form.errors.email,
                                }"
                            />
                            <p
                                v-if="form.errors.email"
                                class="text-red-500 text-xs mt-1"
                            >
                                {{ form.errors.email }}
                            </p>
                        </div>

                        <!-- Address -->
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
                                >Alamat</label
                            >
                            <textarea
                                v-model="form.address"
                                rows="3"
                                placeholder="Alamat Lengkap"
                                class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-brand-red/50 focus:border-brand-red outline-none transition-all resize-none"
                                :class="{
                                    'border-red-500 focus:ring-red-500/50':
                                        form.errors.address,
                                }"
                            ></textarea>
                            <p
                                v-if="form.errors.address"
                                class="text-red-500 text-xs mt-1"
                            >
                                {{ form.errors.address }}
                            </p>
                        </div>

                        <!-- Status -->
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
                                >Status</label
                            >
                            <select
                                v-model="form.status"
                                class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-brand-red/50 focus:border-brand-red outline-none transition-all"
                            >
                                <option value="active">Aktif</option>
                                <option value="inactive">Tidak Aktif</option>
                            </select>
                            <p
                                v-if="form.errors.status"
                                class="text-red-500 text-xs mt-1"
                            >
                                {{ form.errors.status }}
                            </p>
                        </div>

                        <!-- Actions -->
                        <div
                            class="flex justify-end gap-4 pt-4 border-t border-gray-100 dark:border-gray-700"
                        >
                            <Link
                                :href="route('admin.drivers.index')"
                                class="px-6 py-3 rounded-xl border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 font-semibold hover:bg-gray-100 dark:hover:bg-gray-800 transition-all"
                            >
                                Batal
                            </Link>
                            <button
                                type="submit"
                                :disabled="form.processing"
                                class="px-8 py-3 rounded-xl bg-brand-red text-white font-bold shadow-lg shadow-brand-red/30 hover:bg-red-700 hover:shadow-brand-red/50 transition-all duration-300 disabled:opacity-70 disabled:cursor-not-allowed flex items-center gap-2"
                            >
                                <i
                                    v-if="form.processing"
                                    class="fas fa-spinner fa-spin"
                                ></i>
                                <span v-else>Simpan Perubahan</span>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </AdminLayout>
</template>
