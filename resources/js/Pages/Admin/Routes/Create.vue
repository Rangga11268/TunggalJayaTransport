<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import RouteCoordinatePicker from "@/Components/RouteCoordinatePicker.vue";

const form = useForm({
    name: "",
    origin: "",
    destination: "",
    origin_lat: null,
    origin_lng: null,
    destination_lat: null,
    destination_lng: null,
    waypoints: [],
    distance: "",
    duration: "",
    description: "",
});

const applyCoordinates = (payload) => {
    form.origin_lat = payload.origin_lat;
    form.origin_lng = payload.origin_lng;
    form.destination_lat = payload.destination_lat;
    form.destination_lng = payload.destination_lng;
    form.waypoints = payload.waypoints;
};

const submit = () => {
    form.post(route("admin.routes.store"), {
        preserveScroll: true,
        onError: () => {
            // Errors are automatically handled by the form helper and displayed in template
        },
    });
};
</script>

<template>
    <Head title="Tambah Rute Baru" />

    <AdminLayout title="Tambah Rute Baru">
        <div class="max-w-4xl mx-auto">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h2
                        class="text-2xl font-bold text-gray-900 dark:text-white font-serif"
                    >
                        Tambah Rute Baru
                    </h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                        Buat rute perjalanan baru untuk armada bus.
                    </p>
                </div>
                <Link
                    :href="route('admin.routes.index')"
                    class="px-5 py-2.5 rounded-xl bg-gray-100 text-gray-700 font-semibold hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700 transition-all duration-300 flex items-center gap-2"
                >
                    <i class="fas fa-arrow-left"></i>
                    <span>Kembali</span>
                </Link>
            </div>

            <form @submit.prevent="submit" class="space-y-8">
                <div
                    class="bg-white dark:bg-gray-800 rounded-3xl p-8 shadow-xl shadow-gray-100/50 dark:shadow-black/30 border border-gray-100 dark:border-gray-700/50"
                >
                    <h3
                        class="text-lg font-bold text-gray-900 dark:text-white mb-6 flex items-center gap-2"
                    >
                        <i class="fas fa-map-signs text-brand-red"></i>
                        Detail Rute
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Route Name -->
                        <div class="col-span-2">
                            <label
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
                            >
                                Nama Rute
                            </label>
                            <input
                                v-model="form.name"
                                type="text"
                                placeholder="Contoh: Jakarta - Surabaya (via Tol Trans Jawa)"
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

                        <!-- Origin -->
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
                            >
                                Asal (Keberangkatan)
                            </label>
                            <input
                                v-model="form.origin"
                                type="text"
                                placeholder="Kota Asal"
                                class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-brand-red/50 focus:border-brand-red outline-none transition-all"
                                :class="{
                                    'border-red-500 focus:ring-red-500/50':
                                        form.errors.origin,
                                }"
                            />
                            <p
                                v-if="form.errors.origin"
                                class="text-red-500 text-xs mt-1"
                            >
                                {{ form.errors.origin }}
                            </p>
                        </div>

                        <!-- Destination -->
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
                            >
                                Tujuan
                            </label>
                            <input
                                v-model="form.destination"
                                type="text"
                                placeholder="Kota Tujuan"
                                class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-brand-red/50 focus:border-brand-red outline-none transition-all"
                                :class="{
                                    'border-red-500 focus:ring-red-500/50':
                                        form.errors.destination,
                                }"
                            />
                            <p
                                v-if="form.errors.destination"
                                class="text-red-500 text-xs mt-1"
                            >
                                {{ form.errors.destination }}
                            </p>
                        </div>

                        <!-- Distance -->
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
                            >
                                Jarak Tempuh (km)
                            </label>
                            <input
                                v-model="form.distance"
                                type="number"
                                step="0.1"
                                min="0"
                                placeholder="0"
                                class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-brand-red/50 focus:border-brand-red outline-none transition-all"
                                :class="{
                                    'border-red-500 focus:ring-red-500/50':
                                        form.errors.distance,
                                }"
                            />
                            <p
                                v-if="form.errors.distance"
                                class="text-red-500 text-xs mt-1"
                            >
                                {{ form.errors.distance }}
                            </p>
                        </div>

                        <!-- Duration -->
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
                            >
                                Estimasi Durasi (Menit)
                            </label>
                            <input
                                v-model="form.duration"
                                type="number"
                                min="0"
                                placeholder="0"
                                class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-brand-red/50 focus:border-brand-red outline-none transition-all"
                                :class="{
                                    'border-red-500 focus:ring-red-500/50':
                                        form.errors.duration,
                                }"
                            />
                            <p
                                v-if="form.errors.duration"
                                class="text-red-500 text-xs mt-1"
                            >
                                {{ form.errors.duration }}
                            </p>
                        </div>

                        <!-- Description -->
                        <div class="col-span-2">
                            <label
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
                            >
                                Keterangan Tambahan
                            </label>
                            <textarea
                                v-model="form.description"
                                rows="3"
                                placeholder="Informasi tambahan mengenai rute ini..."
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
                    </div>
                </div>

                <div
                    class="bg-white dark:bg-gray-800 rounded-3xl p-8 shadow-xl shadow-gray-100/50 dark:shadow-black/30 border border-gray-100 dark:border-gray-700/50"
                >
                    <h3
                        class="text-lg font-bold text-gray-900 dark:text-white mb-3 flex items-center gap-2"
                    >
                        <i class="fas fa-map-marked-alt text-brand-red"></i>
                        Koordinat Rute
                    </h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">
                        Gunakan nama asal dan tujuan untuk ambil koordinat
                        otomatis, lalu koreksi titiknya langsung di peta jika
                        perlu.
                    </p>

                    <RouteCoordinatePicker
                        :origin-name="form.origin"
                        :destination-name="form.destination"
                        :origin-lat="form.origin_lat"
                        :origin-lng="form.origin_lng"
                        :destination-lat="form.destination_lat"
                        :destination-lng="form.destination_lng"
                        :waypoints="form.waypoints"
                        @change="applyCoordinates"
                    />
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-end gap-4">
                    <Link
                        :href="route('admin.routes.index')"
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
                        <span v-else>Simpan Rute</span>
                    </button>
                </div>
            </form>
        </div>
    </AdminLayout>
</template>
