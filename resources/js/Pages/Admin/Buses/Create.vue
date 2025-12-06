<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import { ref, computed } from "vue";

const props = defineProps({
    drivers: Array,
    conductors: Array,
    assignedDrivers: Array,
    assignedConductors: Array,
});

const form = useForm({
    name: "",
    plate_number: "",
    bus_type: "",
    capacity: "",
    description: "",
    year: new Date().getFullYear(),
    status: "active",
    drivers: [],
    conductors: [],
    image: null,
});

const imagePreview = ref(null);

const handleImageUpload = (event) => {
    const file = event.target.files[0];
    if (file) {
        form.image = file;
        imagePreview.value = URL.createObjectURL(file);
    }
};

const submit = () => {
    form.post(route("admin.buses.store"), {
        preserveScroll: true,
        onError: () => {
            // Errors are automatically handled by the form helper and displayed in template
        },
    });
};

const availableDrivers = computed(() => {
    return props.drivers.filter(
        (driver) => !props.assignedDrivers.includes(driver.id)
    );
});

const availableConductors = computed(() => {
    return props.conductors.filter(
        (conductor) => !props.assignedConductors.includes(conductor.id)
    );
});
</script>

<template>
    <Head title="Tambah Armada Baru" />

    <AdminLayout title="Tambah Armada Baru">
        <div class="max-w-4xl mx-auto">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h2
                        class="text-2xl font-bold text-gray-900 dark:text-white font-serif"
                    >
                        Tambah Armada Baru
                    </h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                        Lengkapi form berikut untuk menambahkan bus baru.
                    </p>
                </div>
                <Link
                    :href="route('admin.buses.index')"
                    class="px-5 py-2.5 rounded-xl bg-gray-100 text-gray-700 font-semibold hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700 transition-all duration-300 flex items-center gap-2"
                >
                    <i class="fas fa-arrow-left"></i>
                    <span>Kembali</span>
                </Link>
            </div>

            <form @submit.prevent="submit" class="space-y-8">
                <!-- Bus Info Card -->
                <div
                    class="bg-white dark:bg-gray-800 rounded-3xl p-8 shadow-xl shadow-gray-100/50 dark:shadow-black/30 border border-gray-100 dark:border-gray-700/50"
                >
                    <h3
                        class="text-lg font-bold text-gray-900 dark:text-white mb-6 flex items-center gap-2"
                    >
                        <i class="fas fa-bus text-brand-red"></i>
                        Informasi Utama
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Bus Name -->
                        <div class="col-span-2">
                            <label
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
                            >
                                Nama Bus / Julukan
                            </label>
                            <input
                                v-model="form.name"
                                type="text"
                                placeholder="Contoh: Super Executive 01"
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

                        <!-- Plate Number -->
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
                            >
                                Nomor Polisi
                            </label>
                            <input
                                v-model="form.plate_number"
                                type="text"
                                placeholder="AA 1234 XX"
                                class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-brand-red/50 focus:border-brand-red outline-none transition-all"
                                :class="{
                                    'border-red-500 focus:ring-red-500/50':
                                        form.errors.plate_number,
                                }"
                            />
                            <p
                                v-if="form.errors.plate_number"
                                class="text-red-500 text-xs mt-1"
                            >
                                {{ form.errors.plate_number }}
                            </p>
                        </div>

                        <!-- Bus Type -->
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
                            >
                                Tipe Bus (Kelas)
                            </label>
                            <select
                                v-model="form.bus_type"
                                class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-brand-red/50 focus:border-brand-red outline-none transition-all"
                                :class="{
                                    'border-red-500 focus:ring-red-500/50':
                                        form.errors.bus_type,
                                }"
                            >
                                <option value="" disabled>
                                    Pilih Tipe Bus
                                </option>
                                <option value="Economy">Economy</option>
                                <option value="VIP">VIP</option>
                                <option value="Executive">Executive</option>
                                <option value="Super Executive">
                                    Super Executive
                                </option>
                                <option value="Sleeper">Sleeper</option>
                            </select>
                            <p
                                v-if="form.errors.bus_type"
                                class="text-red-500 text-xs mt-1"
                            >
                                {{ form.errors.bus_type }}
                            </p>
                        </div>

                        <!-- Capacity -->
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
                            >
                                Kapasitas Kursi
                            </label>
                            <input
                                v-model="form.capacity"
                                type="number"
                                min="1"
                                placeholder="Jumlah kursi"
                                class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-brand-red/50 focus:border-brand-red outline-none transition-all"
                                :class="{
                                    'border-red-500 focus:ring-red-500/50':
                                        form.errors.capacity,
                                }"
                            />
                            <p
                                v-if="form.errors.capacity"
                                class="text-red-500 text-xs mt-1"
                            >
                                {{ form.errors.capacity }}
                            </p>
                        </div>

                        <!-- Year -->
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
                            >
                                Tahun Pembuatan
                            </label>
                            <input
                                v-model="form.year"
                                type="number"
                                min="1900"
                                placeholder="Tahun"
                                class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-brand-red/50 focus:border-brand-red outline-none transition-all"
                                :class="{
                                    'border-red-500 focus:ring-red-500/50':
                                        form.errors.year,
                                }"
                            />
                            <p
                                v-if="form.errors.year"
                                class="text-red-500 text-xs mt-1"
                            >
                                {{ form.errors.year }}
                            </p>
                        </div>

                        <!-- Description -->
                        <div class="col-span-2">
                            <label
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
                            >
                                Deskripsi / Fasilitas
                            </label>
                            <textarea
                                v-model="form.description"
                                rows="3"
                                placeholder="Jelaskan fasilitas bus ini..."
                                class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-brand-red/50 focus:border-brand-red outline-none transition-all"
                                :class="{
                                    'border-red-500 focus:ring-red-500/50':
                                        form.errors.description,
                                }"
                            ></textarea>
                            <p
                                v-if="form.errors.description"
                                class="text-red-500 text-xs mt-1"
                            >
                                {{ form.errors.description }}
                            </p>
                        </div>

                        <!-- Status -->
                        <div class="col-span-2">
                            <label
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
                            >
                                Status Operasional
                            </label>
                            <div class="flex gap-4">
                                <label
                                    class="flex items-center gap-2 cursor-pointer p-3 rounded-xl border border-gray-200 dark:border-gray-700 has-[:checked]:border-green-500 has-[:checked]:bg-green-50 dark:has-[:checked]:bg-green-900/20 transition-all"
                                >
                                    <input
                                        type="radio"
                                        v-model="form.status"
                                        value="active"
                                        class="text-green-600 focus:ring-green-500"
                                    />
                                    <span
                                        class="text-sm font-medium text-gray-700 dark:text-gray-300"
                                        >Aktif</span
                                    >
                                </label>
                                <label
                                    class="flex items-center gap-2 cursor-pointer p-3 rounded-xl border border-gray-200 dark:border-gray-700 has-[:checked]:border-amber-500 has-[:checked]:bg-amber-50 dark:has-[:checked]:bg-amber-900/20 transition-all"
                                >
                                    <input
                                        type="radio"
                                        v-model="form.status"
                                        value="maintenance"
                                        class="text-amber-600 focus:ring-amber-500"
                                    />
                                    <span
                                        class="text-sm font-medium text-gray-700 dark:text-gray-300"
                                        >Perbaikan</span
                                    >
                                </label>
                                <label
                                    class="flex items-center gap-2 cursor-pointer p-3 rounded-xl border border-gray-200 dark:border-gray-700 has-[:checked]:border-gray-500 has-[:checked]:bg-gray-50 dark:has-[:checked]:bg-gray-800 transition-all"
                                >
                                    <input
                                        type="radio"
                                        v-model="form.status"
                                        value="inactive"
                                        class="text-gray-600 focus:ring-gray-500"
                                    />
                                    <span
                                        class="text-sm font-medium text-gray-700 dark:text-gray-300"
                                        >Non-Aktif</span
                                    >
                                </label>
                            </div>
                            <p
                                v-if="form.errors.status"
                                class="text-red-500 text-xs mt-1"
                            >
                                {{ form.errors.status }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Assignment & Image Card -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Crew Assignment -->
                    <div
                        class="bg-white dark:bg-gray-800 rounded-3xl p-8 shadow-xl shadow-gray-100/50 dark:shadow-black/30 border border-gray-100 dark:border-gray-700/50"
                    >
                        <h3
                            class="text-lg font-bold text-gray-900 dark:text-white mb-6 flex items-center gap-2"
                        >
                            <i class="fas fa-users-cog text-brand-red"></i>
                            Penugasan Kru
                        </h3>

                        <div class="space-y-6">
                            <!-- Drivers -->
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
                                >
                                    Pilih Sopir (Opsional)
                                </label>
                                <select
                                    multiple
                                    v-model="form.drivers"
                                    class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-brand-red/50 focus:border-brand-red outline-none transition-all h-32"
                                >
                                    <option
                                        v-for="driver in availableDrivers"
                                        :key="driver.id"
                                        :value="driver.id"
                                    >
                                        {{ driver.name }}
                                    </option>
                                    <option
                                        v-if="availableDrivers.length === 0"
                                        disabled
                                    >
                                        Tidak ada sopir tersedia
                                    </option>
                                </select>
                                <p class="text-xs text-gray-400 mt-1">
                                    Tahan tombol Ctrl (Cmd di Mac) untuk memilih
                                    lebih dari satu.
                                </p>
                                <p
                                    v-if="form.errors.drivers"
                                    class="text-red-500 text-xs mt-1"
                                >
                                    {{ form.errors.drivers }}
                                </p>
                            </div>

                            <!-- Conductors -->
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
                                >
                                    Pilih Kondektur (Opsional)
                                </label>
                                <select
                                    multiple
                                    v-model="form.conductors"
                                    class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-brand-red/50 focus:border-brand-red outline-none transition-all h-32"
                                >
                                    <option
                                        v-for="conductor in availableConductors"
                                        :key="conductor.id"
                                        :value="conductor.id"
                                    >
                                        {{ conductor.name }}
                                    </option>
                                    <option
                                        v-if="availableConductors.length === 0"
                                        disabled
                                    >
                                        Tidak ada kondektur tersedia
                                    </option>
                                </select>
                                <p class="text-xs text-gray-400 mt-1">
                                    Tahan tombol Ctrl (Cmd di Mac) untuk memilih
                                    lebih dari satu.
                                </p>
                                <p
                                    v-if="form.errors.conductors"
                                    class="text-red-500 text-xs mt-1"
                                >
                                    {{ form.errors.conductors }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Image Upload -->
                    <div
                        class="bg-white dark:bg-gray-800 rounded-3xl p-8 shadow-xl shadow-gray-100/50 dark:shadow-black/30 border border-gray-100 dark:border-gray-700/50"
                    >
                        <h3
                            class="text-lg font-bold text-gray-900 dark:text-white mb-6 flex items-center gap-2"
                        >
                            <i class="fas fa-image text-brand-red"></i>
                            Foto Bus
                        </h3>

                        <div
                            class="flex flex-col items-center justify-center p-6 border-2 border-dashed border-gray-300 dark:border-gray-700 rounded-2xl bg-gray-50 dark:bg-gray-900/50 hover:bg-gray-100 dark:hover:bg-gray-800/50 transition-colors cursor-pointer relative overflow-hidden group"
                        >
                            <input
                                type="file"
                                @change="handleImageUpload"
                                class="absolute inset-0 opacity-0 cursor-pointer w-full h-full z-10"
                                accept="image/*"
                            />

                            <div
                                v-if="imagePreview"
                                class="relative w-full h-48 rounded-lg overflow-hidden"
                            >
                                <img
                                    :src="imagePreview"
                                    class="w-full h-full object-cover"
                                />
                                <div
                                    class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity"
                                >
                                    <p class="text-white text-sm font-medium">
                                        <i class="fas fa-edit mr-2"></i>Ganti
                                        Foto
                                    </p>
                                </div>
                            </div>

                            <div v-else class="text-center py-8">
                                <div
                                    class="w-16 h-16 bg-gray-200 dark:bg-gray-800 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-400"
                                >
                                    <i
                                        class="fas fa-cloud-upload-alt text-2xl"
                                    ></i>
                                </div>
                                <p
                                    class="text-sm font-medium text-gray-900 dark:text-white mb-1"
                                >
                                    Klik atau drag foto ke sini
                                </p>
                                <p
                                    class="text-xs text-gray-500 dark:text-gray-400"
                                >
                                    PNG, JPG, JPEG (Max. 2MB)
                                </p>
                            </div>
                        </div>
                        <p
                            v-if="form.errors.image"
                            class="text-red-500 text-xs mt-2 text-center"
                        >
                            {{ form.errors.image }}
                        </p>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-end gap-4">
                    <Link
                        :href="route('admin.buses.index')"
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
                        <span v-else>Simpan Armada</span>
                    </button>
                </div>
            </form>
        </div>
    </AdminLayout>
</template>
