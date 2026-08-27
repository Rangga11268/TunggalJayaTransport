<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import LocationAutocomplete from "@/Components/LocationAutocomplete.vue";
import RouteMap from "@/Components/RouteMap.vue";
import axios from "axios";

const form = useForm({
    name: "",
    origin: "",
    origin_address: "",
    destination: "",
    destination_address: "",
    origin_lat: null,
    origin_lng: null,
    destination_lat: null,
    destination_lng: null,
    waypoints: [],
    distance: "",
    duration: "",
    description: "",
});

const calculateRouteStats = async () => {
    if (form.origin_lat && form.origin_lng && form.destination_lat && form.destination_lng) {
        try {
            const url = `https://router.project-osrm.org/route/v1/driving/${form.origin_lng},${form.origin_lat};${form.destination_lng},${form.destination_lat}?overview=false`;
            const res = await axios.get(url);
            if (res.data && res.data.routes && res.data.routes[0]) {
                const routeData = res.data.routes[0];
                form.distance = (routeData.distance / 1000).toFixed(1);
                form.duration = Math.round(routeData.duration / 60);
            }
        } catch (e) {
            console.error("OSRM calculation failed", e);
        }
    }
    
    if (!form.name && form.origin && form.destination) {
        const cleanOrigin = form.origin.split(',')[0];
        const cleanDest = form.destination.split(',')[0];
        form.name = `${cleanOrigin} - ${cleanDest}`;
    }
};

const handleLocationSelect = (type, loc) => {
    if (type === 'origin') {
        form.origin = loc.name.split(',')[0];
        form.origin_address = loc.name;
        form.origin_lat = loc.lat;
        form.origin_lng = loc.lng;
    } else {
        form.destination = loc.name.split(',')[0];
        form.destination_address = loc.name;
        form.destination_lat = loc.lat;
        form.destination_lng = loc.lng;
    }
    calculateRouteStats();
};

const submit = () => {
    form.post(route("admin.routes.store"), {
        preserveScroll: true,
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
                        Pilih lokasi asal & tujuan untuk mendapatkan koordinat dan estimasi jarak/durasi otomatis.
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
                        <!-- Origin Autocomplete -->
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
                            >
                                Asal (Kota / Keberangkatan)
                            </label>
                            <LocationAutocomplete
                                v-model="form.origin"
                                placeholder="Cari kota / lokasi asal..."
                                @select="(loc) => handleLocationSelect('origin', loc)"
                            />
                            <p
                                v-if="form.errors.origin"
                                class="text-red-500 text-xs mt-1"
                            >
                                {{ form.errors.origin }}
                            </p>
                            <div class="mt-2">
                                <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 mb-1">
                                    Detail Alamat / Patokan Asal
                                </label>
                                <textarea
                                    v-model="form.origin_address"
                                    rows="2"
                                    placeholder="Contoh: Terminal Kampung Rambutan, Loket 5 / Garasi Utama"
                                    class="w-full px-3 py-2 text-xs rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white outline-none"
                                ></textarea>
                            </div>
                        </div>

                        <!-- Destination Autocomplete -->
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
                            >
                                Tujuan (Kota / Kedatangan)
                            </label>
                            <LocationAutocomplete
                                v-model="form.destination"
                                placeholder="Cari kota / lokasi tujuan..."
                                @select="(loc) => handleLocationSelect('destination', loc)"
                            />
                            <p
                                v-if="form.errors.destination"
                                class="text-red-500 text-xs mt-1"
                            >
                                {{ form.errors.destination }}
                            </p>
                            <div class="mt-2">
                                <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 mb-1">
                                    Detail Alamat / Patokan Tujuan
                                </label>
                                <textarea
                                    v-model="form.destination_address"
                                    rows="2"
                                    placeholder="Contoh: Terminal Purabaya Bungurasih, Jalur 3"
                                    class="w-full px-3 py-2 text-xs rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white outline-none"
                                ></textarea>
                            </div>
                        </div>

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
                                placeholder="Contoh: Jakarta - Surabaya"
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

                        <!-- Distance -->
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
                            >
                                Jarak Tempuh (km) <span class="text-xs text-brand-red font-normal">(Otomatis)</span>
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
                                Estimasi Durasi (Menit) <span class="text-xs text-brand-red font-normal">(Otomatis)</span>
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

                <!-- Automatic Map Preview -->
                <div
                    v-if="form.origin_lat || form.destination_lat"
                    class="bg-white dark:bg-gray-800 rounded-3xl p-6 shadow-xl shadow-gray-100/50 dark:shadow-black/30 border border-gray-100 dark:border-gray-700/50 overflow-hidden"
                >
                    <h3
                        class="text-base font-bold text-gray-900 dark:text-white mb-3 flex items-center gap-2"
                    >
                        <i class="fas fa-map-marked-alt text-brand-red"></i>
                        Preview Peta Rute
                    </h3>
                    <div class="h-[320px] w-full rounded-2xl overflow-hidden border border-gray-200 dark:border-gray-700">
                        <RouteMap
                            :origin="form.origin"
                            :destination="form.destination"
                            :origin-lat="form.origin_lat"
                            :origin-lng="form.origin_lng"
                            :destination-lat="form.destination_lat"
                            :destination-lng="form.destination_lng"
                            :waypoints="form.waypoints"
                        />
                    </div>
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
