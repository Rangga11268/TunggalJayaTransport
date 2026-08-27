<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { Head, Link, useForm, router } from "@inertiajs/vue3";
import { ref, computed, watch } from "vue";

const props = defineProps({
    booking: Object,
    schedules: Array,
});

const form = useForm({
    _method: "PUT",
    schedule_id: props.booking.schedule_id,
    passenger_name: props.booking.passenger_name,
    passenger_phone: props.booking.passenger_phone,
    passenger_email: props.booking.passenger_email,
    seat_numbers: props.booking.seat_numbers,
    number_of_seats: props.booking.number_of_seats,
    total_price: props.booking.total_price,
    payment_status: props.booking.payment_status,
    booking_status: props.booking.booking_status,
});

const selectedSchedule = computed(() => {
    return props.schedules.find((s) => s.id === form.schedule_id);
});

// Auto-update price ONLY if schedule changes or seat count changes AND user wants to (maybe add a manual override?)
// Actually, if editing, we shouldn't auto-overwrite price unless explicitly changing params that affect it,
// because admin might have given a custom price.
// But let's keep it simple: if schedule or seat count changes, recalculate.
// Adding a check to avoid overwriting initial load.
watch(
    [() => form.schedule_id, () => form.number_of_seats],
    ([newSchedId, newSeats], [oldSchedId, oldSeats]) => {
        // Only recalculate if values actually changed from what they were initially (or previous state)
        if (
            selectedSchedule.value &&
            (newSchedId !== props.booking.schedule_id ||
                newSeats !== props.booking.number_of_seats)
        ) {
            form.total_price =
                selectedSchedule.value.price * form.number_of_seats;
        }
    }
);

const submit = () => {
    form.post(route("admin.bookings.update", props.booking.id), {
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
    <Head title="Edit Pemesanan" />

    <AdminLayout title="Edit Pemesanan">
        <div class="max-w-4xl mx-auto">
            <div
                class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6 sm:mb-8"
            >
                <div>
                    <h2
                        class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white font-serif"
                    >
                        Edit Pemesanan
                    </h2>
                    <p
                        class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 mt-1"
                    >
                        Perbarui informasi pemesanan:
                        <span class="text-brand-red font-bold">{{
                            booking.booking_code
                        }}</span>
                    </p>
                </div>
                <Link
                    :href="route('admin.bookings.index')"
                    class="px-4 sm:px-5 py-2 sm:py-2.5 rounded-xl bg-gray-100 text-gray-700 font-semibold hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700 transition-all duration-300 flex items-center justify-center gap-2 text-sm sm:text-base w-full sm:w-auto"
                >
                    <i class="fas fa-arrow-left"></i>
                    <span>Kembali</span>
                </Link>
            </div>

            <form @submit.prevent="submit" class="space-y-8">
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
                        Jadwal & Rute
                    </h3>

                    <div class="space-y-4 sm:space-y-6">
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
                            >
                                Jadwal Keberangkatan
                            </label>
                            <select
                                v-model="form.schedule_id"
                                class="w-full px-3 sm:px-4 py-2 sm:py-3 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-brand-red/50 focus:border-brand-red outline-none transition-all text-sm sm:text-base max-w-full"
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
                                class="text-xs text-amber-500 mt-1"
                                v-if="form.schedule_id !== booking.schedule_id"
                            >
                                <i class="fas fa-exclamation-triangle mr-1"></i>
                                Perhatian: Mengubah jadwal dapat mempengaruhi
                                harga dan ketersediaan kursi.
                            </p>
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
                    class="grid grid-cols-1 overflow-hidden md:grid-cols-2 gap-4 sm:gap-6 lg:gap-8"
                >
                    <!-- Passenger -->
                    <div
                        class="bg-white dark:bg-gray-800 rounded-2xl sm:rounded-3xl p-4 sm:p-6 lg:p-8 shadow-xl shadow-gray-100/50 dark:shadow-black/30 border border-gray-100 dark:border-gray-700/50"
                    >
                        <h3
                            class="text-base sm:text-lg font-bold text-gray-900 dark:text-white mb-4 sm:mb-6 flex items-center gap-2"
                        >
                            <i class="fas fa-user text-brand-red"></i>
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
                                    class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-brand-red/50 focus:border-brand-red outline-none transition-all"
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
                                <div class="flex items-center gap-2">
                                    <span
                                        class="text-gray-500 dark:text-gray-400 text-sm"
                                        >Rp</span
                                    >
                                    <input
                                        v-model="form.total_price"
                                        type="number"
                                        min="0"
                                        class="w-full px-4 py-2 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-brand-red font-bold text-lg focus:ring-2 focus:ring-brand-red/50 focus:border-brand-red outline-none transition-all"
                                    />
                                </div>
                                <p class="text-xs text-gray-400 mt-1">
                                    Anda dapat menyesuaikan harga secara manual
                                    jika diperlukan.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Status & Action -->
                <div
                    class="bg-white dark:bg-gray-800 rounded-2xl sm:rounded-3xl p-4 sm:p-6 lg:p-8 shadow-xl shadow-gray-100/50 dark:shadow-black/30 border border-gray-100 dark:border-gray-700/50"
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
                                <option value="refunded">Refund</option>
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
                    class="flex flex-col-reverse sm:flex-row justify-end gap-3 sm:gap-4 sticky bottom-4 z-10"
                >
                    <Link
                        :href="route('admin.bookings.index')"
                        class="px-4 sm:px-6 py-2.5 sm:py-3 rounded-xl border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 font-semibold hover:bg-gray-100 dark:hover:bg-gray-800 transition-all text-center text-sm sm:text-base bg-white dark:bg-gray-800"
                    >
                        Batal
                    </Link>
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="px-6 sm:px-8 py-2.5 sm:py-3 rounded-xl bg-brand-red text-white font-bold shadow-lg shadow-brand-red/30 hover:bg-red-700 hover:shadow-brand-red/50 transition-all duration-300 disabled:opacity-70 disabled:cursor-not-allowed flex items-center justify-center gap-2 text-sm sm:text-base"
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
