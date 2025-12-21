<script setup>
import { Head, useForm } from "@inertiajs/vue3";
import FrontendLayout from "@/Layouts/FrontendLayout.vue";
import { computed } from "vue";

defineOptions({ layout: FrontendLayout });

const props = defineProps({
    schedule: Object,
    selectedDate: String,
});

const form = useForm({
    schedule_id: props.schedule.id,
    date: props.selectedDate || "",
    passenger_name: "",
    passenger_email: "",
    passenger_phone: "",
    number_of_seats: 1,
    terms: false,
});

const submit = () => {
    form.post(route("frontend.booking.store"), {
        preserveScroll: true,
    });
};

const formattedDate = computed(() => {
    const date = props.selectedDate
        ? new Date(props.selectedDate)
        : new Date(props.schedule.departure_time);
    return date.toLocaleDateString("id-ID", {
        weekday: "long",
        year: "numeric",
        month: "long",
        day: "numeric",
    });
});

const totalPrice = computed(() => {
    return props.schedule.price * form.number_of_seats;
});

const formatCurrency = (value) => {
    return new Intl.NumberFormat("id-ID", {
        style: "currency",
        currency: "IDR",
        minimumFractionDigits: 0,
    }).format(value);
};

const formatTime = (dateString) => {
    if (!dateString) return "";
    // Kalo udah format jam "14:00", balikin aja
    if (dateString.length === 5 && dateString.includes(":")) return dateString;

    // Kalo bukan, parsing dulu jadi date
    const date = new Date(dateString);
    return date
        .toLocaleTimeString("id-ID", {
            hour: "2-digit",
            minute: "2-digit",
            hour12: false,
        })
        .replace(".", ":");
};
</script>

<template>
    <Head title="Isi Data Penumpang" />

    <!-- Compact Hero -->
    <div class="relative bg-primary-950 py-20 overflow-hidden">
        <div class="absolute inset-0">
            <div class="absolute inset-0 hero-pattern opacity-10"></div>
            <div class="stars absolute inset-0 opacity-30"></div>
        </div>
        <div
            class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center"
        >
            <h1
                class="text-3xl md:text-4xl font-extrabold text-white font-serif mb-2"
            >
                Data Penumpang
            </h1>
            <p class="text-gray-300">
                Lengkapi data diri Anda untuk melanjutkan pemesanan
            </p>
        </div>
    </div>

    <div
        class="bg-gray-50 dark:bg-gray-950 min-h-screen py-12 px-4 sm:px-6 lg:px-8 -mt-8 relative z-20"
    >
        <div class="max-w-6xl mx-auto grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column: Trip Summary -->
            <div class="lg:col-span-1 space-y-6">
                <div
                    class="bg-white dark:bg-gray-900 rounded-3xl p-6 shadow-xl shadow-black/5 border border-gray-100 dark:border-gray-800 sticky top-24"
                >
                    <h3
                        class="text-lg font-bold text-gray-900 dark:text-white mb-6 flex items-center"
                    >
                        <i class="fas fa-ticket-alt text-brand-red mr-3"></i>
                        Ringkasan Perjalanan
                    </h3>

                    <!-- Route Info -->
                    <div class="space-y-4">
                        <div
                            class="flex items-start relative pb-6 border-l-2 border-dashed border-gray-200 dark:border-gray-700 ml-2 pl-6"
                        >
                            <div
                                class="absolute -left-[9px] top-0 w-4 h-4 rounded-full border-2 border-brand-red bg-white dark:bg-gray-900"
                            ></div>
                            <div>
                                <p
                                    class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1"
                                >
                                    Keberangkatan
                                </p>
                                <h4
                                    class="text-lg font-bold text-gray-900 dark:text-white"
                                >
                                    {{ schedule.route.origin }}
                                </h4>
                                <p class="text-sm font-medium text-gray-500">
                                    {{ formatTime(schedule.departure_time) }}
                                    WIB
                                </p>
                                <p class="text-xs text-gray-400 mt-1">
                                    {{ formattedDate }}
                                </p>
                            </div>
                        </div>

                        <div class="flex items-start relative ml-2 pl-6">
                            <div
                                class="absolute -left-[9px] top-0 w-4 h-4 rounded-full bg-brand-red border-2 border-brand-red"
                            ></div>
                            <div>
                                <p
                                    class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1"
                                >
                                    Tujuan
                                </p>
                                <h4
                                    class="text-lg font-bold text-gray-900 dark:text-white"
                                >
                                    {{ schedule.route.destination }}
                                </h4>
                                <p class="text-sm font-medium text-gray-500">
                                    {{ formatTime(schedule.arrival_time) }} WIB
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="h-px bg-gray-100 dark:bg-gray-800 my-6"></div>

                    <!-- Bus Info -->
                    <div class="flex items-center space-x-4 mb-6">
                        <div
                            class="w-12 h-12 rounded-xl bg-gray-50 dark:bg-gray-800 flex items-center justify-center text-gray-400 text-xl"
                        >
                            <i class="fas fa-bus"></i>
                        </div>
                        <div>
                            <p
                                class="text-sm font-bold text-gray-900 dark:text-white"
                            >
                                {{ schedule.bus.name }}
                            </p>
                            <p
                                class="text-xs text-brand-red font-bold uppercase tracking-wide bg-brand-red/10 px-2 py-0.5 rounded inline-block mt-1"
                            >
                                {{ schedule.bus.bus_type }}
                            </p>
                        </div>
                    </div>

                    <!-- Price Info -->
                    <div class="bg-gray-50 dark:bg-gray-800/50 rounded-2xl p-4">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-sm text-gray-500"
                                >Harga / Kursi</span
                            >
                            <span
                                class="font-bold text-gray-900 dark:text-white"
                                >{{ formatCurrency(schedule.price) }}</span
                            >
                        </div>
                        <div
                            class="flex justify-between items-center text-lg font-black text-brand-red pt-2 border-t border-gray-200 dark:border-gray-700"
                        >
                            <span>Total</span>
                            <span>{{ formatCurrency(totalPrice) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Passenger Form -->
            <div class="lg:col-span-2">
                <div
                    class="bg-white dark:bg-gray-900 rounded-3xl p-6 md:p-8 shadow-xl shadow-black/5 border border-gray-100 dark:border-gray-800"
                >
                    <form @submit.prevent="submit" class="space-y-6">
                        <!-- Personal Details -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="md:col-span-2">
                                <label
                                    class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2"
                                    >Nama Lengkap</label
                                >
                                <div class="relative">
                                    <i
                                        class="fas fa-user absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"
                                    ></i>
                                    <input
                                        v-model="form.passenger_name"
                                        type="text"
                                        required
                                        placeholder="Masukkan nama lengkap (sesuai KTP)"
                                        class="input-premium pl-12 w-full"
                                    />
                                </div>
                                <p
                                    v-if="form.errors.passenger_name"
                                    class="text-red-500 text-xs mt-1"
                                >
                                    {{ form.errors.passenger_name }}
                                </p>
                            </div>

                            <div>
                                <label
                                    class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2"
                                    >Email</label
                                >
                                <div class="relative">
                                    <i
                                        class="fas fa-envelope absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"
                                    ></i>
                                    <input
                                        v-model="form.passenger_email"
                                        type="email"
                                        required
                                        placeholder="contoh@email.com"
                                        class="input-premium pl-12 w-full"
                                    />
                                </div>
                                <p
                                    v-if="form.errors.passenger_email"
                                    class="text-red-500 text-xs mt-1"
                                >
                                    {{ form.errors.passenger_email }}
                                </p>
                            </div>

                            <div>
                                <label
                                    class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2"
                                    >Nomor Telepon</label
                                >
                                <div class="relative">
                                    <i
                                        class="fas fa-phone absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"
                                    ></i>
                                    <input
                                        v-model="form.passenger_phone"
                                        type="tel"
                                        required
                                        placeholder="08xxxxxxxxxx"
                                        class="input-premium pl-12 w-full"
                                    />
                                </div>
                                <p
                                    v-if="form.errors.passenger_phone"
                                    class="text-red-500 text-xs mt-1"
                                >
                                    {{ form.errors.passenger_phone }}
                                </p>
                            </div>
                        </div>

                        <!-- Seat Quantity -->
                        <div>
                            <label
                                class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2"
                                >Jumlah Kursi</label
                            >
                            <div class="relative">
                                <i
                                    class="fas fa-users absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"
                                ></i>
                                <select
                                    v-model="form.number_of_seats"
                                    class="input-premium pl-12 w-full appearance-none"
                                >
                                    <option v-for="n in 5" :key="n" :value="n">
                                        {{ n }} Kursi
                                    </option>
                                </select>
                                <i
                                    class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none text-xs"
                                ></i>
                            </div>
                            <p class="text-xs text-gray-500 mt-2 italic">
                                * Maksimal 5 kursi per pemesanan
                            </p>
                            <p
                                v-if="form.errors.number_of_seats"
                                class="text-red-500 text-xs mt-1"
                            >
                                {{ form.errors.number_of_seats }}
                            </p>
                        </div>

                        <!-- Terms -->
                        <div
                            class="pt-4 border-t border-gray-100 dark:border-gray-800"
                        >
                            <label
                                class="flex items-start space-x-3 cursor-pointer group"
                            >
                                <div class="relative flex items-center mt-0.5">
                                    <input
                                        v-model="form.terms"
                                        type="checkbox"
                                        required
                                        class="peer h-5 w-5 cursor-pointer appearance-none rounded-md border border-gray-300 transition-all checked:border-brand-red checked:bg-brand-red hover:border-brand-red"
                                    />
                                    <i
                                        class="fas fa-check absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 text-white opacity-0 peer-checked:opacity-100 text-xs pointer-events-none"
                                    ></i>
                                </div>
                                <span
                                    class="text-sm text-gray-600 dark:text-gray-400"
                                >
                                    Saya setuju dengan
                                    <a
                                        href="#"
                                        class="text-brand-red font-bold hover:underline"
                                        >Syarat & Ketentuan</a
                                    >
                                    serta
                                    <a
                                        href="#"
                                        class="text-brand-red font-bold hover:underline"
                                        >Kebijakan Privasi</a
                                    >
                                    yang berlaku.
                                </span>
                            </label>
                            <p
                                v-if="form.errors.terms"
                                class="text-red-500 text-xs mt-1"
                            >
                                {{ form.errors.terms }}
                            </p>
                        </div>

                        <!-- Submit Button -->
                        <div class="pt-4">
                            <button
                                type="submit"
                                :disabled="form.processing"
                                class="btn-premium w-full text-lg py-4 shadow-xl shadow-brand-red/20"
                            >
                                <span
                                    v-if="!form.processing"
                                    class="flex items-center justify-center"
                                >
                                    Lanjut Pilih Kursi
                                    <i class="fas fa-arrow-right ml-2"></i>
                                </span>
                                <span
                                    v-else
                                    class="flex items-center justify-center"
                                >
                                    <i
                                        class="fas fa-circle-notch fa-spin mr-2"
                                    ></i>
                                    Memproses...
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>
