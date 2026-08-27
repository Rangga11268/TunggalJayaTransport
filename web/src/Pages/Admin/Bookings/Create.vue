<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import { ref, computed, watch } from "vue";

const props = defineProps({
    schedules: Array, // [{id, name, price, available_seats, bus_capacity}, ...]
    users: Array, // Optional, generic user list if we implement user selection
});

const form = useForm({
    user_id: 1, // Default to admin or specific user ID? Backend requires valid user_id. We might need to fetch users or use a hidden default. Let's assume ID 1 (Admin) for manual bookings if not specified.
    // Ideally we should have a user selection. For now I will hardcode a valid ID or let user input ID? No, dropdown.
    // I'll add a simple user selection if I can fetch them, otherwise I'll default to the logged in admin user if they are also in 'users' table?
    // Let's assume the admin is a User and has ID. We can use the props.auth.user.id but we need to pass it.
    // The controller validation says: 'user_id' => 'required|exists:users,id'.
    // I will use `usePage().props.auth.user.id` as default.
    schedule_id: "",
    passenger_name: "",
    passenger_phone: "",
    passenger_email: "",
    seat_numbers: "",
    number_of_seats: 1,
    total_price: 0,
    payment_status: "pending",
    booking_status: "confirmed", // Manual booking usually confirmed immediately? Or pending.
});

import { usePage } from "@inertiajs/vue3";
const page = usePage();
// Set default user_id to current admin's id
form.user_id = page.props.auth.user.id;

const selectedSchedule = computed(() => {
    return props.schedules.find((s) => s.id === form.schedule_id);
});

// Auto-calculate total price when schedule or seats change
watch([() => form.schedule_id, () => form.number_of_seats], () => {
    if (selectedSchedule.value) {
        form.total_price = selectedSchedule.value.price * form.number_of_seats;
    }
});

const submit = () => {
    form.post(route("admin.bookings.store"), {
        preserveScroll: true,
        onError: () => {
            // Handled by inline errors
        },
    });
};

const formatCurrency = (value) => {
    return new Intl.NumberFormat("id-ID", {
        style: "currency",
        currency: "IDR",
        minimumFractionDigits: 0,
    }).format(value);
};
</script>

<template>
    <Head title="Buat Pemesanan Baru" />

    <AdminLayout title="Buat Pemesanan Baru">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div
                class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6 sm:mb-8"
            >
                <div>
                    <h2
                        class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white font-serif"
                    >
                        Buat Pemesanan Manual
                    </h2>
                    <p
                        class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 mt-1"
                    >
                        Form untuk membuat pemesanan tiket secara manual
                        (offline/on-desk).
                    </p>
                </div>
                <Link
                    :href="route('admin.bookings.index')"
                    class="w-full sm:w-auto px-4 sm:px-5 py-2.5 rounded-xl bg-gray-100 text-gray-700 font-semibold hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700 transition-all duration-300 flex items-center justify-center gap-2 text-sm sm:text-base"
                >
                    <i class="fas fa-arrow-left"></i>
                    <span>Kembali</span>
                </Link>
            </div>

            <form
                @submit.prevent="submit"
                class="space-y-4 sm:space-y-6 lg:space-y-8"
            >
                <!-- Info Section -->
                <div
                    class="bg-white dark:bg-gray-800 rounded-2xl sm:rounded-3xl p-4 sm:p-6 lg:p-8 shadow-xl shadow-gray-100/50 dark:shadow-black/30 border border-gray-100 dark:border-gray-700/50"
                >
                    <h3
                        class="text-base sm:text-lg font-bold text-gray-900 dark:text-white mb-4 sm:mb-6 flex items-center gap-2"
                    >
                        <i
                            class="fas fa-route text-brand-red text-sm sm:text-base"
                        ></i>
                        Pilih Jadwal & Rute
                    </h3>

                    <div class="space-y-6">
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
                            >
                                Jadwal Keberangkatan Tersedia
                            </label>
                            <select
                                v-model="form.schedule_id"
                                class="w-full px-3 sm:px-4 py-2 sm:py-3 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-brand-red/50 focus:border-brand-red outline-none transition-all text-sm sm:text-base"
                                :class="{
                                    'border-red-500 focus:ring-red-500/50':
                                        form.errors.schedule_id,
                                }"
                            >
                                <option value="" disabled>Pilih Jadwal</option>
                                <option
                                    v-for="schedule in schedules"
                                    :key="schedule.id"
                                    :value="schedule.id"
                                >
                                    {{ schedule.name }} - Kursi Sisa:
                                    {{ schedule.available_seats }} - Rp
                                    {{ formatCurrency(schedule.price) }}
                                </option>
                            </select>
                            <p
                                v-if="form.errors.schedule_id"
                                class="text-red-500 text-xs mt-1"
                            >
                                {{ form.errors.schedule_id }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Passenger & Detail Section -->
                <div
                    class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6 lg:gap-8"
                >
                    <!-- Passenger -->
                    <div
                        class="bg-white dark:bg-gray-800 rounded-2xl sm:rounded-3xl p-4 sm:p-6 lg:p-8 shadow-xl shadow-gray-100/50 dark:shadow-black/30 border border-gray-100 dark:border-gray-700/50"
                    >
                        <h3
                            class="text-base sm:text-lg font-bold text-gray-900 dark:text-white mb-4 sm:mb-6 flex items-center gap-2"
                        >
                            <i
                                class="fas fa-user text-brand-red text-sm sm:text-base"
                            ></i>
                            Data Penumpang
                        </h3>
                        <div class="space-y-4">
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
                                    >Nama Lengkap</label
                                >
                                <input
                                    v-model="form.passenger_name"
                                    type="text"
                                    placeholder="Nama Penumpang"
                                    class="w-full px-3 sm:px-4 py-2 sm:py-3 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-brand-red/50 focus:border-brand-red outline-none transition-all text-sm sm:text-base"
                                    :class="{
                                        'border-red-500 focus:ring-red-500/50':
                                            form.errors.passenger_name,
                                    }"
                                />
                                <p
                                    v-if="form.errors.passenger_name"
                                    class="text-red-500 text-xs mt-1"
                                >
                                    {{ form.errors.passenger_name }}
                                </p>
                            </div>
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
                                    >No. Telepon / WhatsApp</label
                                >
                                <input
                                    v-model="form.passenger_phone"
                                    type="text"
                                    placeholder="08123456789"
                                    class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-brand-red/50 focus:border-brand-red outline-none transition-all"
                                    :class="{
                                        'border-red-500 focus:ring-red-500/50':
                                            form.errors.passenger_phone,
                                    }"
                                />
                                <p
                                    v-if="form.errors.passenger_phone"
                                    class="text-red-500 text-xs mt-1"
                                >
                                    {{ form.errors.passenger_phone }}
                                </p>
                            </div>
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
                                    >Email</label
                                >
                                <input
                                    v-model="form.passenger_email"
                                    type="email"
                                    placeholder="email@contoh.com"
                                    class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-brand-red/50 focus:border-brand-red outline-none transition-all"
                                    :class="{
                                        'border-red-500 focus:ring-red-500/50':
                                            form.errors.passenger_email,
                                    }"
                                />
                                <p
                                    v-if="form.errors.passenger_email"
                                    class="text-red-500 text-xs mt-1"
                                >
                                    {{ form.errors.passenger_email }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Seats & Payment -->
                    <div
                        class="bg-white dark:bg-gray-800 rounded-3xl p-8 shadow-xl shadow-gray-100/50 dark:shadow-black/30 border border-gray-100 dark:border-gray-700/50"
                    >
                        <h3
                            class="text-lg font-bold text-gray-900 dark:text-white mb-6 flex items-center gap-2"
                        >
                            <i class="fas fa-ticket-alt text-brand-red"></i>
                            Kursi & Pembayaran
                        </h3>
                        <div class="space-y-4">
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
                                    >Jumlah Kursi</label
                                >
                                <input
                                    v-model="form.number_of_seats"
                                    type="number"
                                    min="1"
                                    class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-brand-red/50 focus:border-brand-red outline-none transition-all"
                                    :class="{
                                        'border-red-500 focus:ring-red-500/50':
                                            form.errors.number_of_seats,
                                    }"
                                />
                                <p
                                    v-if="form.errors.number_of_seats"
                                    class="text-red-500 text-xs mt-1"
                                >
                                    {{ form.errors.number_of_seats }}
                                </p>
                            </div>
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
                                    >Nomor Kursi (Dipisah koma)</label
                                >
                                <input
                                    v-model="form.seat_numbers"
                                    type="text"
                                    placeholder="Contoh: 1A,1B"
                                    class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-brand-red/50 focus:border-brand-red outline-none transition-all"
                                    :class="{
                                        'border-red-500 focus:ring-red-500/50':
                                            form.errors.seat_numbers,
                                    }"
                                />
                                <p class="text-xs text-gray-400 mt-1">
                                    Masukkan nomor kursi secara manual.
                                </p>
                                <p
                                    v-if="form.errors.seat_numbers"
                                    class="text-red-500 text-xs mt-1"
                                >
                                    {{ form.errors.seat_numbers }}
                                </p>
                            </div>

                            <div
                                class="pt-4 border-t border-gray-100 dark:border-gray-700"
                            >
                                <label
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
                                    >Total Harga</label
                                >
                                <div class="text-2xl font-bold text-brand-red">
                                    {{ formatCurrency(form.total_price) }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Status & Action -->
                <div
                    class="bg-white dark:bg-gray-800 rounded-3xl p-8 shadow-xl shadow-gray-100/50 dark:shadow-black/30 border border-gray-100 dark:border-gray-700/50"
                >
                    <h3
                        class="text-lg font-bold text-gray-900 dark:text-white mb-6 flex items-center gap-2"
                    >
                        <i class="fas fa-check-circle text-brand-red"></i>
                        Status Pemesanan
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
                                >Status Pembayaran</label
                            >
                            <select
                                v-model="form.payment_status"
                                class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-brand-red/50 focus:border-brand-red outline-none transition-all"
                            >
                                <option value="pending">
                                    Tertunda (Pending)
                                </option>
                                <option value="paid">Lunas (Paid)</option>
                                <option value="failed">Gagal (Failed)</option>
                            </select>
                        </div>
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
                                >Status Booking</label
                            >
                            <select
                                v-model="form.booking_status"
                                class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-brand-red/50 focus:border-brand-red outline-none transition-all"
                            >
                                <option value="pending">Tertunda</option>
                                <option value="confirmed">Dikonfirmasi</option>
                                <option value="completed">Selesai</option>
                                <option value="cancelled">Dibatalkan</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div
                    class="flex flex-col sm:flex-row justify-end gap-3 sm:gap-4"
                >
                    <Link
                        :href="route('admin.bookings.index')"
                        class="w-full sm:w-auto px-6 py-3 rounded-xl border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 font-semibold hover:bg-gray-100 dark:hover:bg-gray-800 transition-all text-center text-sm sm:text-base"
                    >
                        Batal
                    </Link>
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="w-full sm:w-auto px-6 sm:px-8 py-3 rounded-xl bg-brand-red text-white font-bold shadow-lg shadow-brand-red/30 hover:bg-red-700 hover:shadow-brand-red/50 transition-all duration-300 disabled:opacity-70 disabled:cursor-not-allowed flex items-center justify-center gap-2 text-sm sm:text-base"
                    >
                        <i
                            v-if="form.processing"
                            class="fas fa-spinner fa-spin"
                        ></i>
                        <span v-else>Simpan Pemesanan</span>
                    </button>
                </div>
            </form>
        </div>
    </AdminLayout>
</template>
