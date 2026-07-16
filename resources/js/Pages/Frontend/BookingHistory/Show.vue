<script setup>
import { Head, Link } from "@inertiajs/vue3";
import FrontendLayout from "@/Layouts/FrontendLayout.vue";

defineOptions({ layout: FrontendLayout });

const props = defineProps({
    booking: Object,
});

const formatDate = (dateString, includeTime = false) => {
    if (!dateString) return "-";
    const options = {
        year: "numeric",
        month: "long",
        day: "numeric",
        ...(includeTime && { hour: "2-digit", minute: "2-digit" }),
    };
    return new Date(dateString).toLocaleDateString("id-ID", options);
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

    <div class="min-h-screen bg-[#fcf9f8] pt-28 pb-20 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto">
            <!-- Back -->
            <Link :href="route('booking-history.index')"
                class="inline-flex items-center text-[#454652] hover:text-[#10207a] transition-colors text-sm mb-8">
                <i class="fas fa-arrow-left mr-2"></i> Kembali ke Riwayat
            </Link>

            <!-- Main Card -->
            <div class="bg-white border border-[#ebe7e7] rounded-[12px] p-6 md:p-10 shadow-sm">
                
                <!-- Header -->
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-5 mb-10 pb-8 border-b border-[#ebe7e7]">
                    <div>
                        <span class="text-[10px] font-bold px-2.5 py-1 rounded-lg bg-[#f6f3f2] text-[#454652] uppercase tracking-wider border border-[#ebe7e7] mb-2 inline-block">
                            E-Ticket Perjalanan
                        </span>
                        <h1 class="text-2xl md:text-4xl font-bold text-[#1c1b1b] font-unbounded">{{ booking.booking_code }}</h1>
                    </div>
                    <div class="flex flex-col items-start md:items-end gap-2">
                        <span v-if="booking.payment_status === 'paid'"
                            class="px-4 py-1.5 rounded-full bg-emerald-50 text-emerald-700 text-xs font-bold border border-emerald-200 flex items-center gap-1.5">
                            <i class="fas fa-check-circle"></i> LUNAS
                        </span>
                        <span v-else
                            class="px-4 py-1.5 rounded-full bg-amber-50 text-amber-700 text-xs font-bold border border-amber-200 flex items-center gap-1.5">
                            <i class="fas fa-hourglass-half"></i> {{ booking.payment_status.toUpperCase() }}
                        </span>
                        <span class="text-[11px] text-[#454652]">Terdaftar {{ formatDate(booking.created_at) }}</span>
                    </div>
                </div>

                <!-- Grid -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 md:gap-12">
                    <!-- Journey -->
                    <div class="space-y-8">
                        <div>
                            <h3 class="text-xs font-bold text-[#454652] uppercase tracking-wider mb-5 flex items-center gap-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-[#10207a]"></span>
                                Rincian Perjalanan
                            </h3>

                            <div class="relative pl-7 space-y-8">
                                <div class="absolute left-[3px] top-2 bottom-2 w-[1px] bg-gradient-to-b from-[#10207a] via-[#ebe7e7] to-[#10207a]"></div>

                                <div class="relative">
                                    <div class="absolute -left-[26px] top-1 w-2 h-2 rounded-full bg-[#10207a] border-2 border-white"></div>
                                    <div class="text-[10px] font-bold text-[#454652] uppercase tracking-wider mb-0.5">Berangkat</div>
                                    <div class="text-lg font-bold text-[#1c1b1b]">{{ booking.schedule?.route?.origin }}</div>
                                    <div class="text-sm text-[#454652]">{{ formatDate(booking.booking_date) }} • {{ formatTime(booking.schedule?.departure_time) }} WIB</div>
                                </div>

                                <div class="relative">
                                    <div class="absolute -left-[26px] top-1 w-2 h-2 rounded-full bg-[#10207a] border-2 border-white"></div>
                                    <div class="text-[10px] font-bold text-[#454652] uppercase tracking-wider mb-0.5">Tujuan</div>
                                    <div class="text-lg font-bold text-[#1c1b1b]">{{ booking.schedule?.route?.destination }}</div>
                                    <div class="text-sm text-[#454652]">Durasi: {{ booking.schedule?.route?.formatted_duration || '-' }}</div>
                                </div>
                            </div>
                        </div>

                        <!-- Bus Info -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 pt-6 border-t border-[#ebe7e7]">
                            <div>
                                <div class="text-[10px] font-bold text-[#454652] uppercase tracking-wider mb-1">Armada</div>
                                <div class="text-sm font-bold text-[#1c1b1b]">{{ booking.schedule?.bus?.name || '-' }}</div>
                                <div class="text-xs text-[#10207a] font-semibold">{{ booking.schedule?.bus?.bus_type || 'Executive' }}</div>
                            </div>
                            <div>
                                <div class="text-[10px] font-bold text-[#454652] uppercase tracking-wider mb-1">Nomor Polisi</div>
                                <div class="text-sm font-bold text-[#1c1b1b] font-mono">{{ booking.schedule?.bus?.plate_number || '-' }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Passenger & Price -->
                    <div class="bg-[#fcf9f8] border border-[#ebe7e7] rounded-[12px] p-6 md:p-8 space-y-8 h-fit">
                        <div>
                            <h3 class="text-xs font-bold text-[#454652] uppercase tracking-wider mb-5">Informasi Penumpang</h3>
                            <div class="space-y-5">
                                <div>
                                    <div class="text-[10px] font-bold text-[#454652] uppercase tracking-wider mb-1">Nama</div>
                                    <div class="text-base font-bold text-[#1c1b1b]">{{ booking.passenger_name }}</div>
                                </div>
                                <div class="flex items-center justify-between gap-4">
                                    <div>
                                        <div class="text-[10px] font-bold text-[#454652] uppercase tracking-wider mb-1">Kursi</div>
                                        <div class="text-lg font-bold text-[#10207a]">{{ booking.seat_numbers || '-' }}</div>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-[10px] font-bold text-[#454652] uppercase tracking-wider mb-1">Email</div>
                                        <div class="text-sm font-bold text-[#1c1b1b]">{{ booking.passenger_email }}</div>
                                    </div>
                                </div>
                                <div v-if="booking.passenger_phone">
                                    <div class="text-[10px] font-bold text-[#454652] uppercase tracking-wider mb-1">Telepon</div>
                                    <div class="text-sm font-bold text-[#1c1b1b]">{{ booking.passenger_phone }}</div>
                                </div>
                            </div>
                        </div>

                        <!-- Total -->
                        <div class="pt-6 border-t border-[#ebe7e7]">
                            <div class="flex items-center justify-between mb-6">
                                <div class="text-[10px] font-bold text-[#454652] uppercase tracking-wider">Total Pembayaran</div>
                                <div class="text-xl md:text-2xl font-bold text-[#10207a]">{{ formatCurrency(booking.total_price) }}</div>
                            </div>
                            <div v-if="booking.discount_amount > 0" class="flex items-center justify-between mb-4 text-sm">
                                <span class="text-[#454652]">Diskon</span>
                                <span class="text-emerald-600 font-semibold">-{{ formatCurrency(booking.discount_amount) }}</span>
                            </div>

                            <div class="space-y-3">
                                <a v-if="booking.payment_status === 'paid'" :href="route('frontend.booking.download-ticket', booking.id)" target="_blank"
                                    class="flex items-center justify-center gap-2 w-full py-3.5 bg-[#10207a] text-white rounded-[10px] font-bold text-[13px] hover:bg-[#0c185e] transition-all shadow-sm">
                                    <i class="fas fa-download"></i> Unduh E-Tiket (PDF)
                                </a>

                                <Link v-if="booking.payment_status === 'pending' && booking.midtrans_transaction_id"
                                    :href="route('frontend.booking.confirmation', booking.id)"
                                    class="flex items-center justify-center gap-2 w-full py-3.5 bg-[#10207a] text-white rounded-[10px] font-bold text-[13px] hover:bg-[#0c185e] transition-all shadow-sm">
                                    <i class="fas fa-credit-card"></i> Bayar Sekarang
                                </Link>

                                <button @click="window.print()"
                                    class="w-full py-3.5 border border-[#e5e2e1] text-[#454652] font-semibold text-[12px] rounded-[10px] hover:bg-[#f6f3f2] transition-all">
                                    <i class="fas fa-print mr-1.5"></i> Cetak Invoice
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Info -->
            <p class="text-center text-xs text-[#454652] mt-8 max-w-lg mx-auto">
                Tunjukkan E-Tiket ini atau berikan Kode Booking kepada petugas di loket minimal 30 menit sebelum keberangkatan.
            </p>
        </div>
    </div>
</template>
