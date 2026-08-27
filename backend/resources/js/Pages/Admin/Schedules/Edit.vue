<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";

const props = defineProps({
    schedule: Object,
    buses: Array,
    routes: Array,
});

const form = useForm({
    bus_id: props.schedule.bus_id,
    route_id: props.schedule.route_id,
    schedule_type: props.schedule.schedule_type,
    departure_date:
        props.schedule.departure_date || new Date().toISOString().split("T")[0],
    departure_time: props.schedule.departure_time,
    arrival_time: props.schedule.arrival_time,
    price: props.schedule.price,
    status: props.schedule.status,
});

const submit = () => {
    form.put(route("admin.schedules.update", props.schedule.id), {
        preserveScroll: true,
        onError: () => {
            // Errors are automatically handled by the form helper and displayed in template
        },
    });
};
</script>

<template>
    <Head title="Edit Jadwal" />

    <AdminLayout title="Edit Jadwal">
        <div class="max-w-4xl mx-auto">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h2
                        class="text-2xl font-bold text-gray-900 dark:text-white font-serif"
                    >
                        Edit Jadwal
                    </h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                        Perbarui detail jadwal keberangkatan.
                    </p>
                </div>
                <Link
                    :href="route('admin.schedules.index')"
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
                        <i class="far fa-calendar-alt text-brand-red"></i>
                        Detail Jadwal
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Bus Selection -->
                        <div class="col-span-2 md:col-span-1">
                            <label
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
                            >
                                Pilih Armada Bus
                            </label>
                            <div class="relative">
                                <select
                                    v-model="form.bus_id"
                                    class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-brand-red/50 focus:border-brand-red outline-none transition-all"
                                    :class="{
                                        'border-red-500 focus:ring-red-500/50':
                                            form.errors.bus_id,
                                    }"
                                >
                                    <option value="" disabled>Pilih Bus</option>
                                    <option
                                        v-for="bus in buses"
                                        :key="bus.id"
                                        :value="bus.id"
                                    >
                                        {{ bus.name }} -
                                        {{ bus.plate_number }} ({{
                                            bus.bus_type
                                        }})
                                    </option>
                                </select>
                            </div>
                            <p
                                v-if="form.errors.bus_id"
                                class="text-red-500 text-xs mt-1"
                            >
                                {{ form.errors.bus_id }}
                            </p>
                        </div>

                        <!-- Route Selection -->
                        <div class="col-span-2 md:col-span-1">
                            <label
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
                            >
                                Pilih Rute Perjalanan
                            </label>
                            <div class="relative">
                                <select
                                    v-model="form.route_id"
                                    class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-brand-red/50 focus:border-brand-red outline-none transition-all"
                                    :class="{
                                        'border-red-500 focus:ring-red-500/50':
                                            form.errors.route_id,
                                    }"
                                >
                                    <option value="" disabled>
                                        Pilih Rute
                                    </option>
                                    <option
                                        v-for="routeItem in routes"
                                        :key="routeItem.id"
                                        :value="routeItem.id"
                                    >
                                        {{ routeItem.name }} ({{
                                            routeItem.origin
                                        }}
                                        - {{ routeItem.destination }})
                                    </option>
                                </select>
                            </div>
                            <p
                                v-if="form.errors.route_id"
                                class="text-red-500 text-xs mt-1"
                            >
                                {{ form.errors.route_id }}
                            </p>
                        </div>

                        <!-- Schedule Type -->
                        <div class="col-span-2">
                            <label
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
                            >
                                Tipe Jadwal
                            </label>
                            <div class="flex flex-wrap gap-4">
                                <label
                                    class="flex items-center gap-2 cursor-pointer"
                                >
                                    <input
                                        type="radio"
                                        v-model="form.schedule_type"
                                        value="daily_recurring"
                                        class="w-4 h-4 text-brand-red focus:ring-brand-red border-gray-300"
                                    />
                                    <span
                                        class="text-gray-700 dark:text-gray-300"
                                        >Rutin (Setiap Hari)</span
                                    >
                                </label>
                                <label
                                    class="flex items-center gap-2 cursor-pointer"
                                >
                                    <input
                                        type="radio"
                                        v-model="form.schedule_type"
                                        value="daily"
                                        class="w-4 h-4 text-brand-red focus:ring-brand-red border-gray-300"
                                    />
                                    <span
                                        class="text-gray-700 dark:text-gray-300"
                                        >Sekali Jalan (Tanggal Tertentu)</span
                                    >
                                </label>
                            </div>
                            <p
                                class="text-xs text-gray-500 mt-1"
                                v-if="form.schedule_type === 'daily_recurring'"
                            >
                                Jadwal ini akan otomatis tersedia setiap hari
                                pada jam yang ditentukan.
                            </p>
                            <p class="text-xs text-gray-500 mt-1" v-else>
                                Jadwal ini hanya berlaku untuk tanggal spesifik
                                yang dipilih.
                            </p>
                        </div>

                        <!-- Departure Date (Only if daily) -->
                        <div
                            class="col-span-2"
                            v-if="form.schedule_type === 'daily'"
                        >
                            <label
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
                            >
                                Tanggal Keberangkatan
                            </label>
                            <input
                                v-model="form.departure_date"
                                type="date"
                                class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-brand-red/50 focus:border-brand-red outline-none transition-all"
                                :class="{
                                    'border-red-500 focus:ring-red-500/50':
                                        form.errors.departure_date,
                                }"
                            />
                            <p
                                v-if="form.errors.departure_date"
                                class="text-red-500 text-xs mt-1"
                            >
                                {{ form.errors.departure_date }}
                            </p>
                        </div>

                        <!-- Departure Time -->
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
                            >
                                Jam Berangkat (WIB)
                            </label>
                            <input
                                v-model="form.departure_time"
                                type="time"
                                class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-brand-red/50 focus:border-brand-red outline-none transition-all"
                                :class="{
                                    'border-red-500 focus:ring-red-500/50':
                                        form.errors.departure_time,
                                }"
                            />
                            <p
                                v-if="form.errors.departure_time"
                                class="text-red-500 text-xs mt-1"
                            >
                                {{ form.errors.departure_time }}
                            </p>
                        </div>

                        <!-- Arrival Time -->
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
                            >
                                Estimasi Jam Tiba (WIB)
                            </label>
                            <input
                                v-model="form.arrival_time"
                                type="time"
                                class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-brand-red/50 focus:border-brand-red outline-none transition-all"
                                :class="{
                                    'border-red-500 focus:ring-red-500/50':
                                        form.errors.arrival_time,
                                }"
                            />
                            <p
                                v-if="form.errors.arrival_time"
                                class="text-red-500 text-xs mt-1"
                            >
                                {{ form.errors.arrival_time }}
                            </p>
                        </div>

                        <!-- Price -->
                        <div class="col-span-2 md:col-span-1">
                            <label
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
                            >
                                Harga Tiket (Rp)
                            </label>
                            <input
                                v-model="form.price"
                                type="number"
                                min="0"
                                placeholder="Example: 250000"
                                class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-brand-red/50 focus:border-brand-red outline-none transition-all"
                                :class="{
                                    'border-red-500 focus:ring-red-500/50':
                                        form.errors.price,
                                }"
                            />
                            <p
                                v-if="form.errors.price"
                                class="text-red-500 text-xs mt-1"
                            >
                                {{ form.errors.price }}
                            </p>
                        </div>

                        <!-- Status -->
                        <div class="col-span-2 md:col-span-1">
                            <label
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
                            >
                                Status Jadwal
                            </label>
                            <div class="relative">
                                <select
                                    v-model="form.status"
                                    class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-brand-red/50 focus:border-brand-red outline-none transition-all"
                                    :class="{
                                        'border-red-500 focus:ring-red-500/50':
                                            form.errors.status,
                                    }"
                                >
                                    <option value="active">Aktif</option>
                                    <option value="delayed">Tertunda</option>
                                    <option value="cancelled">
                                        Dibatalkan
                                    </option>
                                </select>
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

                <!-- Action Buttons -->
                <div class="flex justify-end gap-4">
                    <Link
                        :href="route('admin.schedules.index')"
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
            </form>
        </div>
    </AdminLayout>
</template>
