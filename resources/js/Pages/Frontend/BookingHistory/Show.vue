<script setup>
import { Head, Link } from "@inertiajs/vue3";
import FrontendLayout from "@/Layouts/FrontendLayout.vue";

defineOptions({ layout: FrontendLayout });

const props = defineProps({
    booking: Object,
});

const formatDate = (dateString) => {
    return new Date(dateString).toLocaleDateString("id-ID", {
        weekday: "long",
        day: "numeric",
        month: "long",
        year: "numeric",
    });
};

const formatCurrency = (value) => {
    return new Intl.NumberFormat("id-ID", {
        style: "currency",
        currency: "IDR",
        minimumFractionDigits: 0,
    }).format(value);
};

const formatTime = (dateString) => {
    if (!dateString) return "-";
    const date = new Date(dateString);
    if (isNaN(date.getTime())) {
        return dateString.substring(0, 5);
    }
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
    <Head :title="`Detail Booking ${booking.booking_code}`" />

    <div
        class="bg-gray-50 dark:bg-[#050505] min-h-screen font-sans selection:bg-rose-600 selection:text-white pt-24 md:pt-32 pb-24 px-4 sm:px-6 lg:px-8"
    >
        <div class="max-w-4xl mx-auto">
            <!-- Back Button -->
            <div class="mb-10 animate-fade-in-up">
                <Link
                    :href="route('booking-history.index')"
                    class="inline-flex items-center text-gray-400 hover:text-rose-600 transition-colors group font-unbounded text-xs uppercase tracking-widest"
                >
                    <i
                        class="fas fa-arrow-left mr-3 group-hover:-translate-x-1 transition-transform"
                    ></i>
                    Kembali ke Riwayat
                </Link>
            </div>

            <div
                class="bg-white dark:bg-[#111] rounded-[2.5rem] md:rounded-[3rem] p-6 md:p-16 border border-gray-100 dark:border-white/5 shadow-2xl animate-fade-in-up"
                style="animation-delay: 0.1s"
            >
                <!-- Status Badge Header -->
                <div
                    class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 mb-16 pb-12 border-b border-gray-50 dark:border-white/5"
                >
                    <div>
                        <span
                            class="text-[10px] font-black px-3 py-1 rounded-lg bg-gray-50 dark:bg-white/5 text-gray-400 dark:text-gray-500 uppercase tracking-widest font-unbounded border border-gray-100 dark:border-white/10 mb-2 inline-block"
                        >
                            E-Ticket Perjalanan
                        </span>
                        <h1
                            class="text-2xl sm:text-3xl md:text-5xl font-black text-gray-900 dark:text-white font-unbounded"
                        >
                            {{ booking.booking_code }}
                        </h1>
                    </div>

                    <div class="flex flex-col items-start md:items-end">
                        <span
                            v-if="booking.payment_status === 'paid'"
                            class="px-6 py-2 rounded-full bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400 text-sm font-black font-unbounded border border-emerald-100 dark:border-emerald-900/30 shadow-lg shadow-emerald-500/10"
                        >
                            <i class="fas fa-check-circle mr-2"></i> LUNAS
                        </span>
                        <span
                            v-else
                            class="px-6 py-2 rounded-full bg-amber-50 dark:bg-amber-900/20 text-amber-600 dark:text-amber-400 text-sm font-black font-unbounded border border-amber-100 dark:border-amber-900/30 shadow-lg shadow-amber-500/10"
                        >
                            <i class="fas fa-hourglass-half mr-2"></i>
                            {{ booking.payment_status.toUpperCase() }}
                        </span>
                        <span
                            class="text-[10px] text-gray-400 mt-3 font-manrope font-bold"
                            >Terdaftar pada
                            {{ formatDate(booking.created_at) }}</span
                        >
                    </div>
                </div>

                <!-- Main Details Grid -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 md:gap-16">
                    <!-- Journey Details -->
                    <div class="space-y-10">
                        <div>
                            <h3
                                class="text-xs font-black text-gray-400 uppercase tracking-widest font-unbounded mb-6 flex items-center gap-3"
                            >
                                <span
                                    class="w-2 h-2 rounded-full bg-rose-600"
                                ></span>
                                Rincian Perjalanan
                            </h3>

                            <div class="relative pl-8 space-y-10">
                                <!-- Decorative Line -->
                                <div
                                    class="absolute left-[3.5px] top-3 bottom-3 w-[1px] bg-gradient-to-b from-rose-600 via-gray-200 dark:via-white/10 to-rose-600"
                                ></div>

                                <div class="relative">
                                    <div
                                        class="absolute -left-[32px] top-1 w-2 h-2 rounded-full bg-rose-600 border-4 border-white dark:border-[#111] box-content"
                                    ></div>
                                    <div
                                        class="text-[10px] font-bold text-gray-400 uppercase tracking-widest font-unbounded mb-1"
                                    >
                                        Berangkat
                                    </div>
                                    <div
                                        class="text-xl font-black text-gray-900 dark:text-white font-unbounded"
                                    >
                                        {{ booking.schedule?.route?.origin }}
                                    </div>
                                    <div
                                        class="text-sm text-gray-500 font-manrope"
                                    >
                                        {{ formatDate(booking.booking_date) }} •
                                        {{
                                            formatTime(
                                                booking.schedule?.departure_time
                                            )
                                        }}
                                        WIB
                                    </div>
                                </div>

                                <div class="relative">
                                    <div
                                        class="absolute -left-[32px] top-1 w-2 h-2 rounded-full bg-rose-600 border-4 border-white dark:border-[#111] box-content"
                                    ></div>
                                    <div
                                        class="text-[10px] font-bold text-gray-400 uppercase tracking-widest font-unbounded mb-1"
                                    >
                                        Tujuan
                                    </div>
                                    <div
                                        class="text-xl font-black text-gray-900 dark:text-white font-unbounded"
                                    >
                                        {{
                                            booking.schedule?.route?.destination
                                        }}
                                    </div>
                                    <div
                                        class="text-sm text-gray-500 font-manrope"
                                    >
                                        Durasi estimasi:
                                        {{
                                            booking.schedule?.route
                                                ?.formatted_duration || "N/A"
                                        }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div
                            class="grid grid-cols-1 sm:grid-cols-2 gap-8 pt-10 border-t border-gray-50 dark:border-white/5"
                        >
                            <div>
                                <div
                                    class="text-[10px] font-bold text-gray-400 uppercase tracking-widest font-unbounded mb-1"
                                >
                                    Armada
                                </div>
                                <div
                                    class="text-sm font-black text-gray-900 dark:text-white font-unbounded"
                                >
                                    {{ booking.schedule?.bus?.name }}
                                </div>
                                <div
                                    class="text-xs text-rose-600 font-manrope font-bold"
                                >
                                    {{
                                        booking.schedule?.bus?.bus_type ||
                                        "Executive"
                                    }}
                                    Class
                                </div>
                            </div>
                            <div>
                                <div
                                    class="text-[10px] font-bold text-gray-400 uppercase tracking-widest font-unbounded mb-1"
                                >
                                    No. Lambung
                                </div>
                                <div
                                    class="text-sm font-black text-gray-900 dark:text-white font-unbounded"
                                >
                                    {{
                                        booking.schedule?.bus?.plate_number ||
                                        "TJT-01"
                                    }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Passenger & Price -->
                    <div
                        class="bg-gray-50 dark:bg-white/5 rounded-[2rem] p-6 md:p-10 space-y-10 h-fit border border-gray-100 dark:border-white/5"
                    >
                        <div>
                            <h3
                                class="text-xs font-black text-gray-400 uppercase tracking-widest font-unbounded mb-6"
                            >
                                Informasi Penumpang
                            </h3>
                            <div class="space-y-6">
                                <div>
                                    <div
                                        class="text-[10px] font-bold text-gray-400 uppercase tracking-widest font-unbounded mb-1"
                                    >
                                        Nama Penumpang
                                    </div>
                                    <div
                                        class="text-lg font-black text-gray-900 dark:text-white font-unbounded"
                                    >
                                        {{ booking.passenger_name }}
                                    </div>
                                </div>
                                <div
                                    class="flex items-center justify-between gap-4"
                                >
                                    <div>
                                        <div
                                            class="text-[10px] font-bold text-gray-400 uppercase tracking-widest font-unbounded mb-1"
                                        >
                                            Nomor Kursi
                                        </div>
                                        <div
                                            class="text-base md:text-lg font-black text-rose-600 font-unbounded"
                                        >
                                            {{ booking.seat_numbers }}
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <div
                                            class="text-[10px] font-bold text-gray-400 uppercase tracking-widest font-unbounded mb-1"
                                        >
                                            Kontak
                                        </div>
                                        <div
                                            class="text-sm font-black text-gray-900 dark:text-white font-unbounded"
                                        >
                                            {{ booking.passenger_phone || "-" }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div
                            class="pt-8 border-t border-gray-200 dark:border-white/10"
                        >
                            <div class="flex items-center justify-between mb-8">
                                <div
                                    class="text-[10px] font-black text-gray-400 uppercase tracking-widest font-unbounded"
                                >
                                    Total Pembayaran
                                </div>
                                <div
                                    class="text-2xl font-black text-rose-600 font-unbounded"
                                >
                                    {{ formatCurrency(booking.total_price) }}
                                </div>
                            </div>

                            <div class="space-y-4">
                                <a
                                    v-if="booking.payment_status === 'paid'"
                                    :href="
                                        route(
                                            'frontend.booking.download-ticket',
                                            booking.id
                                        )
                                    "
                                    class="flex items-center justify-center gap-3 w-full py-4 bg-rose-600 text-white font-black font-unbounded text-xs rounded-xl shadow-lg shadow-rose-600/30 hover:bg-rose-700 hover:scale-[1.02] transition-all"
                                    target="_blank"
                                >
                                    <i class="fas fa-download"></i> UNDUH
                                    E-TIKET (PDF)
                                </a>

                                <Link
                                    v-if="
                                        booking.payment_status === 'pending' &&
                                        booking.midtrans_transaction_id
                                    "
                                    :href="
                                        route(
                                            'frontend.booking.confirmation',
                                            booking.id
                                        )
                                    "
                                    class="flex items-center justify-center gap-3 w-full py-4 bg-rose-600 text-white font-black font-unbounded text-xs rounded-xl shadow-lg shadow-rose-600/30 hover:bg-rose-700 hover:scale-[1.02] transition-all"
                                >
                                    <i class="fas fa-credit-card"></i> BAYAR
                                    SEKARANG
                                </Link>

                                <button
                                    class="w-full py-4 border border-gray-200 dark:border-white/5 text-gray-400 text-[10px] font-black font-unbounded uppercase rounded-xl hover:bg-gray-50 dark:hover:bg-white/5 transition-all"
                                >
                                    <i class="fas fa-print mr-2"></i> Cetak
                                    Invoice
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer Info -->
            <div
                class="mt-12 text-center animate-fade-in-up"
                style="animation-delay: 0.3s"
            >
                <p
                    class="text-xs text-gray-400 font-manrope leading-relaxed max-w-lg mx-auto"
                >
                    Silakan tunjukkan E-Tiket ini atau berikan Kode Booking
                    kepada petugas di loket keberangkatan minimal 30 menit
                    sebelum jam keberangkatan.
                </p>
            </div>
        </div>
    </div>
</template>
