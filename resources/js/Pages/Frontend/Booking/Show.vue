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
    const rawDate = props.selectedDate || props.schedule.departure_time;
    if (!rawDate) return "Tanggal Belum Tersedia";
    const date = new Date(rawDate);
    if (isNaN(date.getTime()) || date.getFullYear() <= 1970)
        return "Tanggal Belum Tersedia";

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

    <!-- Clean Header (Matched with Booking Index) -->
    <div
        class="pt-24 md:pt-32 pb-12 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto text-center relative z-10"
    >
        <h1
            class="font-unbounded font-black text-3xl md:text-5xl text-gray-900 dark:text-white mb-4 animate-fade-in-up leading-tight uppercase"
        >
            Data
            <span class="text-rose-600">Penumpang</span>
        </h1>
        <p
            class="text-lg text-gray-500 dark:text-gray-400 max-w-2xl mx-auto animate-fade-in-up stagger-1 font-manrope font-medium"
        >
            Lengkapi data diri Anda untuk melanjutkan pemesanan tiket
            perjalanan.
        </p>
    </div>

    <div
        class="bg-white dark:bg-[#050505] min-h-screen pb-24 px-4 sm:px-6 lg:px-8 relative z-20"
    >
        <div class="max-w-6xl mx-auto grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column: Trip Summary -->
            <div class="lg:col-span-1 space-y-6">
                <div
                    class="bg-white dark:bg-[#111] rounded-3xl p-6 md:p-8 shadow-xl shadow-gray-100 dark:shadow-none border border-gray-100 dark:border-white/5 lg:sticky top-24 transform transition-all hover:scale-[1.01] duration-300"
                >
                    <h3
                        class="font-unbounded font-bold text-gray-900 dark:text-white mb-8 flex items-center text-lg uppercase tracking-wide"
                    >
                        <i class="fas fa-ticket-alt text-rose-600 mr-3"></i>
                        Ringkasan
                    </h3>

                    <!-- Route Info -->
                    <div class="space-y-4">
                        <div
                            class="flex items-start relative pb-8 border-l-2 border-dashed border-gray-200 dark:border-white/10 ml-2 pl-8"
                        >
                            <div
                                class="absolute -left-[9px] top-0 w-4 h-4 rounded-full border-4 border-white dark:border-[#111] bg-rose-600 shadow-lg shadow-rose-600/30"
                            ></div>
                            <div>
                                <p
                                    class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2 font-manrope"
                                >
                                    Keberangkatan
                                </p>
                                <h4
                                    class="text-xl font-black font-unbounded text-gray-900 dark:text-white leading-none mb-1"
                                >
                                    {{ schedule.route.origin }}
                                </h4>
                                <p
                                    class="text-sm font-bold text-gray-500 font-manrope"
                                >
                                    {{ formatTime(schedule.departure_time) }}
                                    WIB
                                </p>
                                <p
                                    class="text-[11px] font-bold text-gray-400 mt-1 font-manrope bg-gray-50 dark:bg-white/5 px-2 py-1 rounded inline-block"
                                >
                                    {{ formattedDate }}
                                </p>
                            </div>
                        </div>

                        <div class="flex items-start relative ml-2 pl-8">
                            <div
                                class="absolute -left-[9px] top-0 w-4 h-4 rounded-full border-4 border-white dark:border-[#111] bg-gray-900 dark:bg-white"
                            ></div>
                            <div>
                                <p
                                    class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2 font-manrope"
                                >
                                    Tujuan
                                </p>
                                <h4
                                    class="text-xl font-black font-unbounded text-gray-900 dark:text-white leading-none mb-1"
                                >
                                    {{ schedule.route.destination }}
                                </h4>
                                <p
                                    class="text-sm font-bold text-gray-500 font-manrope"
                                >
                                    {{ formatTime(schedule.arrival_time) }} WIB
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="h-px bg-gray-100 dark:bg-white/5 my-8 relative">
                        <div
                            class="absolute -left-8 -top-3 w-6 h-6 bg-white dark:bg-[#050505] rounded-full"
                        ></div>
                        <div
                            class="absolute -right-8 -top-3 w-6 h-6 bg-white dark:bg-[#050505] rounded-full"
                        ></div>
                    </div>

                    <!-- Bus Info -->
                    <div class="flex items-center space-x-5 mb-8">
                        <div
                            class="w-14 h-14 rounded-2xl bg-gray-50 dark:bg-white/5 border border-gray-100 dark:border-white/10 flex items-center justify-center text-gray-400 text-2xl"
                        >
                            <i class="fas fa-bus"></i>
                        </div>
                        <div>
                            <p
                                class="text-base font-black font-unbounded text-gray-900 dark:text-white uppercase"
                            >
                                {{ schedule.bus.name }}
                            </p>
                            <p
                                class="text-[10px] text-rose-600 font-bold uppercase tracking-widest bg-rose-50 dark:bg-rose-900/20 px-2 py-1 rounded-lg inline-block mt-1 border border-rose-100 dark:border-rose-900/30 font-manrope"
                            >
                                {{ schedule.bus.bus_type }}
                            </p>
                        </div>
                    </div>

                    <!-- Price Info -->
                    <div
                        class="bg-gray-50 dark:bg-white/5 rounded-2xl p-5 border border-gray-100 dark:border-white/5"
                    >
                        <div class="flex justify-between items-center mb-3">
                            <span
                                class="text-xs font-bold text-gray-400 uppercase tracking-wider font-manrope"
                                >Harga / Kursi</span
                            >
                            <span
                                class="font-bold text-gray-900 dark:text-white font-manrope"
                                >{{ formatCurrency(schedule.price) }}</span
                            >
                        </div>
                        <div
                            class="flex justify-between items-center pt-3 border-t border-gray-200 dark:border-white/10"
                        >
                            <span
                                class="font-bold text-gray-900 dark:text-white font-unbounded"
                                >Total</span
                            >
                            <span
                                class="text-2xl font-black text-rose-600 font-unbounded"
                                >{{ formatCurrency(totalPrice) }}</span
                            >
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Passenger Form -->
            <div class="lg:col-span-2">
                <div
                    class="bg-white dark:bg-[#111] rounded-3xl p-6 md:p-10 shadow-xl shadow-gray-100 dark:shadow-none border border-gray-100 dark:border-white/5 relative overflow-hidden"
                >
                    <!-- Decorative BG -->
                    <div
                        class="absolute top-0 right-0 w-64 h-64 bg-rose-600/5 rounded-full blur-3xl -mr-32 -mt-32 pointer-events-none"
                    ></div>

                    <form @submit.prevent="submit" class="space-y-8 relative">
                        <!-- Personal Details -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="md:col-span-2 group">
                                <label
                                    class="block text-[11px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-3 font-manrope transition-colors group-focus-within:text-rose-600"
                                    >Nama Lengkap (Sesuai KTP)</label
                                >
                                <div class="relative">
                                    <div
                                        class="absolute left-0 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-rose-600 transition-colors pl-5"
                                    >
                                        <i class="fas fa-user text-lg"></i>
                                    </div>
                                    <input
                                        v-model="form.passenger_name"
                                        type="text"
                                        required
                                        placeholder="Contoh: Budi Santoso"
                                        class="block w-full pl-14 pr-4 py-4 text-lg font-bold border-2 border-gray-100 dark:border-white/10 rounded-2xl bg-gray-50 dark:bg-white/5 focus:border-rose-600 focus:ring-0 transition-all text-gray-900 dark:text-white placeholder-gray-400 font-manrope focus:bg-white dark:focus:bg-black"
                                    />
                                </div>
                                <p
                                    v-if="form.errors.passenger_name"
                                    class="text-rose-500 text-xs mt-2 font-bold flex items-center"
                                >
                                    <i
                                        class="fas fa-exclamation-circle mr-1"
                                    ></i>
                                    {{ form.errors.passenger_name }}
                                </p>
                            </div>

                            <div class="group">
                                <label
                                    class="block text-[11px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-3 font-manrope transition-colors group-focus-within:text-rose-600"
                                    >Email</label
                                >
                                <div class="relative">
                                    <div
                                        class="absolute left-0 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-rose-600 transition-colors pl-5"
                                    >
                                        <i class="fas fa-envelope text-lg"></i>
                                    </div>
                                    <input
                                        v-model="form.passenger_email"
                                        type="email"
                                        required
                                        pattern="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$"
                                        @blur="
                                            form.passenger_email =
                                                form.passenger_email
                                                    .trim()
                                                    .toLowerCase()
                                        "
                                        placeholder="contoh@email.com"
                                        class="block w-full pl-14 pr-4 py-4 text-lg font-bold border-2 border-gray-100 dark:border-white/10 rounded-2xl bg-gray-50 dark:bg-white/5 focus:border-rose-600 focus:ring-0 transition-all text-gray-900 dark:text-white placeholder-gray-400 font-manrope focus:bg-white dark:focus:bg-black"
                                    />
                                </div>
                                <p
                                    v-if="form.errors.passenger_email"
                                    class="text-rose-500 text-xs mt-2 font-bold flex items-center"
                                >
                                    <i
                                        class="fas fa-exclamation-circle mr-1"
                                    ></i>
                                    {{ form.errors.passenger_email }}
                                </p>
                            </div>

                            <div class="group">
                                <label
                                    class="block text-[11px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-3 font-manrope transition-colors group-focus-within:text-rose-600"
                                    >Nomor Telepon / WhatsApp</label
                                >
                                <div class="relative">
                                    <div
                                        class="absolute left-0 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-rose-600 transition-colors pl-5"
                                    >
                                        <i class="fas fa-phone text-lg"></i>
                                    </div>
                                    <input
                                        v-model="form.passenger_phone"
                                        type="tel"
                                        required
                                        pattern="[0-9]{10,13}"
                                        @input="
                                            form.passenger_phone =
                                                form.passenger_phone.replace(
                                                    /[^0-9]/g,
                                                    ''
                                                )
                                        "
                                        placeholder="08xxxxxxxxxx"
                                        class="block w-full pl-14 pr-4 py-4 text-lg font-bold border-2 border-gray-100 dark:border-white/10 rounded-2xl bg-gray-50 dark:bg-white/5 focus:border-rose-600 focus:ring-0 transition-all text-gray-900 dark:text-white placeholder-gray-400 font-manrope focus:bg-white dark:focus:bg-black"
                                    />
                                </div>
                                <p
                                    v-if="form.errors.passenger_phone"
                                    class="text-rose-500 text-xs mt-2 font-bold flex items-center"
                                >
                                    <i
                                        class="fas fa-exclamation-circle mr-1"
                                    ></i>
                                    {{ form.errors.passenger_phone }}
                                </p>
                            </div>
                        </div>

                        <!-- Seat Quantity -->
                        <div class="group">
                            <label
                                class="block text-[11px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-3 font-manrope transition-colors group-focus-within:text-rose-600"
                                >Jumlah Kursi</label
                            >
                            <div class="relative">
                                <div
                                    class="absolute left-0 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-rose-600 transition-colors pl-5"
                                >
                                    <i class="fas fa-users text-lg"></i>
                                </div>
                                <select
                                    v-model="form.number_of_seats"
                                    class="block w-full pl-14 pr-10 py-4 text-lg font-bold border-2 border-gray-100 dark:border-white/10 rounded-2xl bg-gray-50 dark:bg-white/5 focus:border-rose-600 focus:ring-0 transition-all cursor-pointer hover:bg-white dark:hover:bg-white/10 text-gray-900 dark:text-white appearance-none relative z-0 font-manrope focus:bg-white dark:focus:bg-black"
                                >
                                    <option v-for="n in 5" :key="n" :value="n">
                                        {{ n }} Kursi
                                    </option>
                                </select>
                                <i
                                    class="fas fa-chevron-down absolute right-5 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none text-xs"
                                ></i>
                            </div>
                            <p
                                class="text-xs font-bold text-gray-400 mt-2 flex items-center font-manrope"
                            >
                                <i class="fas fa-info-circle mr-1"></i>
                                Maksimal 5 kursi per pemesanan
                            </p>
                        </div>

                        <!-- Terms -->
                        <div
                            class="pt-6 border-t border-gray-100 dark:border-white/5"
                        >
                            <label
                                class="flex items-start space-x-3 cursor-pointer group"
                            >
                                <div class="relative flex items-center mt-0.5">
                                    <input
                                        v-model="form.terms"
                                        type="checkbox"
                                        required
                                        class="peer h-6 w-6 cursor-pointer appearance-none rounded-lg border-2 border-gray-200 dark:border-white/10 transition-all checked:border-rose-600 checked:bg-rose-600 hover:border-rose-400"
                                    />
                                    <i
                                        class="fas fa-check absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 text-white opacity-0 peer-checked:opacity-100 text-xs pointer-events-none"
                                    ></i>
                                </div>
                                <span
                                    class="text-sm font-medium text-gray-500 dark:text-gray-400 font-manrope leading-relaxed"
                                >
                                    Saya setuju dengan
                                    <a
                                        href="#"
                                        class="text-rose-600 font-bold hover:underline"
                                        >Syarat & Ketentuan</a
                                    >
                                    serta
                                    <a
                                        href="#"
                                        class="text-rose-600 font-bold hover:underline"
                                        >Kebijakan Privasi</a
                                    >
                                    yang berlaku di TUJAGO.
                                </span>
                            </label>
                            <p
                                v-if="form.errors.terms"
                                class="text-rose-500 text-xs mt-2 font-bold"
                            >
                                {{ form.errors.terms }}
                            </p>
                        </div>

                        <!-- Submit Button -->
                        <div class="pt-4">
                            <button
                                type="submit"
                                :disabled="form.processing"
                                class="w-full h-16 md:h-[68px] bg-rose-600 hover:bg-rose-700 text-white rounded-2xl shadow-lg shadow-rose-600/30 transform transition-all hover:-translate-y-1 active:scale-[0.98] font-bold flex items-center justify-center space-x-3 group font-unbounded uppercase tracking-wider text-sm md:text-base"
                            >
                                <span
                                    v-if="!form.processing"
                                    class="flex items-center"
                                >
                                    <span>Lanjut Pilih Kursi</span>
                                    <i
                                        class="fas fa-arrow-right ml-3 group-hover:translate-x-1 transition-transform"
                                    ></i>
                                </span>
                                <span
                                    v-else
                                    class="flex items-center justify-center"
                                >
                                    <i
                                        class="fas fa-circle-notch fa-spin mr-3"
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
