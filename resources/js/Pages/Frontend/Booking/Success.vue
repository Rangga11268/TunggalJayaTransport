<script setup>
import { Head, Link } from "@inertiajs/vue3";
import FrontendLayout from "@/Layouts/FrontendLayout.vue";
import { onMounted, ref, computed } from "vue";
import axios from "axios";
import Swal from "sweetalert2";

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

const isChecking = ref(false);

const checkPaymentStatus = async (orderId) => {
    if (!orderId || isChecking.value) return;

    isChecking.value = true;

    try {
        const statusResponse = await axios.get(
            route("frontend.payment.status", { orderId: orderId })
        );

        const data = statusResponse.data;

        if (data.status === "success") {
            const trxStatus = data.transaction_status;

            if (trxStatus === "capture" || trxStatus === "settlement") {
                Swal.fire({
                    icon: "success",
                    title: "Pembayaran Berhasil!",
                    text: "Terima kasih, pembayaran Anda telah dikonfirmasi.",
                    showConfirmButton: false,
                    timer: 2000,
                }).then(() => {
                    window.location.reload();
                });
            } else if (trxStatus === "pending" || trxStatus === "not_found") {
                Swal.fire({
                    icon: "info",
                    title: "Menunggu Pembayaran",
                    text: "Sistem mencatat status pembayaran belum selesai.",
                    footer: "<small>Status: " + trxStatus + "</small>",
                    confirmButtonColor: "#e11d48",
                });
            } else if (trxStatus === "expire") {
                Swal.fire({
                    icon: "warning",
                    title: "Pembayaran Kadaluarsa",
                    text: "Waktu pembayaran telah habis.",
                    confirmButtonColor: "#ef4444",
                }).then(() => {
                    window.location.reload();
                });
            } else {
                Swal.fire({
                    icon: "warning",
                    title: "Status belum berubah",
                    text: "Status saat ini: " + trxStatus,
                    confirmButtonColor: "#f59e0b",
                });
            }
        } else {
            Swal.fire({
                icon: "error",
                title: "Gagal Cek Status",
                text: data.message || "Terjadi kesalahan.",
                confirmButtonColor: "#ef4444",
            });
        }
    } catch (error) {
        Swal.fire({
            icon: "error",
            title: "Gagal Cek Status",
            text: error.response?.data?.message || "Gagal menghubungi server.",
            confirmButtonColor: "#ef4444",
        });
    } finally {
        isChecking.value = false;
    }
};

const routeDescription = computed(() => {
    const s = props.booking?.schedule;
    const r = s?.route;

    if (!r) return "Info Rute Tidak Tersedia";
    if (r.description && r.description.trim() !== "" && r.description !== "-")
        return r.description;
    if (r.origin && r.destination) return `${r.origin} - ${r.destination}`;
    return "Info Rute Tidak Tersedia";
});

const busName = computed(() => {
    return props.booking?.schedule?.bus?.name || "Info Bus Tidak Tersedia";
});

const busPlate = computed(() => {
    return props.booking?.schedule?.bus?.plate_number || "-";
});
</script>

<template>
    <Head title="Pemesanan Berhasil" />

    <div
        class="bg-gray-50 dark:bg-[#050505] min-h-screen py-24 px-4 sm:px-6 lg:px-8 flex items-center justify-center relative overflow-hidden"
    >
        <!-- Background Decorations -->
        <div
            class="absolute top-0 left-0 w-full h-full pointer-events-none opacity-50 overflow-hidden"
        >
            <div
                class="absolute -top-[20%] -right-[10%] w-[600px] h-[600px] rounded-full bg-rose-600/5 blur-[120px]"
            ></div>
            <div
                class="absolute -bottom-[20%] -left-[10%] w-[500px] h-[500px] rounded-full bg-rose-600/5 blur-[100px]"
            ></div>
        </div>

        <div class="max-w-3xl w-full relative z-10">
            <div class="text-center mb-12">
                <!-- Success Icon -->
                <div
                    class="w-24 h-24 rounded-3xl bg-white dark:bg-white/5 shadow-2xl shadow-rose-600/10 flex items-center justify-center mx-auto mb-8 animate-bounce-in border border-gray-100 dark:border-white/10"
                >
                    <i
                        v-if="booking.payment_status === 'paid'"
                        class="fas fa-check-circle text-5xl text-rose-600"
                    ></i>
                    <i v-else class="fas fa-clock text-5xl text-yellow-500"></i>
                </div>

                <h1
                    class="font-unbounded font-black text-3xl md:text-5xl text-gray-900 dark:text-white mb-4 uppercase animate-fade-in-up"
                >
                    {{
                        booking.payment_status === "paid"
                            ? "Pemesanan Berhasil!"
                            : "Menunggu Pembayaran"
                    }}
                </h1>
                <p
                    class="text-lg text-gray-500 dark:text-gray-400 font-manrope animate-fade-in-up stagger-1"
                >
                    {{
                        booking.payment_status === "paid"
                            ? "Terima kasih telah memilih Tunggal Jaya Transport."
                            : "Mohon selesaikan pembayaran Anda."
                    }}
                </p>
            </div>

            <!-- Digital Ticket Stub -->
            <div
                class="bg-white dark:bg-[#111] rounded-3xl shadow-2xl shadow-gray-200 dark:shadow-none border border-gray-100 dark:border-white/5 overflow-hidden animate-fade-in-up stagger-2"
            >
                <!-- Stub Header -->
                <div
                    class="bg-gray-900 dark:bg-black p-6 md:p-8 flex justify-between items-center relative overflow-hidden"
                >
                    <div class="relative z-10">
                        <span
                            class="inline-block px-3 py-1 rounded bg-rose-600 text-white text-[10px] font-bold tracking-widest uppercase mb-2 font-unbounded"
                        >
                            Boarding Pass
                        </span>
                        <h2
                            class="text-white font-unbounded font-black text-xl uppercase tracking-wider"
                        >
                            {{ booking.booking_code }}
                        </h2>
                    </div>
                    <div class="relative z-10 text-right">
                        <div
                            v-if="booking.payment_status === 'paid'"
                            class="text-green-400 font-bold font-manrope flex items-center gap-2"
                        >
                            <i class="fas fa-check-circle"></i> LUNAS
                        </div>
                        <div
                            v-else
                            class="text-yellow-400 font-bold font-manrope flex items-center gap-2"
                        >
                            <i class="fas fa-clock"></i> PENDING
                        </div>
                    </div>

                    <!-- Decorative Circle -->
                    <div
                        class="absolute -right-12 -top-12 w-40 h-40 bg-white/5 rounded-full blur-2xl"
                    ></div>
                </div>

                <!-- Stub Body -->
                <div class="p-6 md:p-10 relative">
                    <!-- Perforated Circles (Cutout effect) -->
                    <div
                        class="absolute -left-3 top-[-12px] w-6 h-6 rounded-full bg-gray-50 dark:bg-[#050505]"
                    ></div>
                    <div
                        class="absolute -right-3 top-[-12px] w-6 h-6 rounded-full bg-gray-50 dark:bg-[#050505]"
                    ></div>

                    <div
                        class="grid grid-cols-1 md:grid-cols-2 gap-8 md:gap-12"
                    >
                        <!-- Left: Route Info -->
                        <div class="space-y-6">
                            <div>
                                <span
                                    class="text-[10px] font-bold text-gray-400 uppercase tracking-widest font-manrope block mb-1"
                                    >Rute Perjalanan</span
                                >
                                <p
                                    class="font-unbounded font-bold text-gray-900 dark:text-white text-lg leading-tight"
                                >
                                    {{ routeDescription }}
                                </p>
                            </div>
                            <div>
                                <span
                                    class="text-[10px] font-bold text-gray-400 uppercase tracking-widest font-manrope block mb-1"
                                    >Armada</span
                                >
                                <p
                                    class="font-manrope font-bold text-gray-900 dark:text-white"
                                >
                                    {{ busName }}
                                </p>
                                <p
                                    class="text-xs text-rose-600 font-bold bg-rose-50 dark:bg-rose-900/10 px-2 py-1 rounde inline-block mt-1"
                                >
                                    {{ busPlate }}
                                </p>
                            </div>
                        </div>

                        <!-- Right: Date & Pax -->
                        <div class="space-y-6">
                            <div>
                                <span
                                    class="text-[10px] font-bold text-gray-400 uppercase tracking-widest font-manrope block mb-1"
                                    >Jadwal Keberangkatan</span
                                >
                                <p
                                    class="font-unbounded font-bold text-gray-900 dark:text-white text-lg"
                                >
                                    {{ formatDate(booking.booking_date) }}
                                </p>
                                <p
                                    class="text-sm text-gray-500 mt-1 font-manrope"
                                >
                                    {{ booking.schedule?.departure_time }} WIB
                                </p>
                            </div>
                            <div class="flex gap-8">
                                <div>
                                    <span
                                        class="text-[10px] font-bold text-gray-400 uppercase tracking-widest font-manrope block mb-1"
                                        >Kursi</span
                                    >
                                    <p
                                        class="font-black text-rose-600 font-unbounded text-xl"
                                    >
                                        {{ booking.seat_numbers }}
                                    </p>
                                </div>
                                <div>
                                    <span
                                        class="text-[10px] font-bold text-gray-400 uppercase tracking-widest font-manrope block mb-1"
                                        >Total</span
                                    >
                                    <p
                                        class="font-black text-gray-900 dark:text-white font-unbounded text-xl"
                                    >
                                        {{
                                            formatCurrency(booking.total_price)
                                        }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Pending Action -->
                    <div
                        v-if="booking.payment_status !== 'paid'"
                        class="mt-8 pt-8 border-t border-dashed border-gray-200 dark:border-white/10"
                    >
                        <div
                            class="flex items-center justify-between p-4 bg-yellow-50 dark:bg-yellow-900/10 rounded-2xl border border-yellow-100 dark:border-yellow-900/20"
                        >
                            <div class="flex items-center gap-3">
                                <i
                                    class="fas fa-exclamation-circle text-yellow-600 text-xl"
                                ></i>
                                <div>
                                    <p
                                        class="text-sm font-bold text-yellow-800 dark:text-yellow-200 font-manrope"
                                    >
                                        Pembayaran Belum Dikonfirmasi?
                                    </p>
                                    <p
                                        class="text-xs text-yellow-700 dark:text-yellow-300/70"
                                    >
                                        Klik tombol cek status jika sudah
                                        transfer.
                                    </p>
                                </div>
                            </div>
                            <button
                                v-if="booking.midtrans_transaction_id"
                                @click="
                                    checkPaymentStatus(
                                        booking.midtrans_transaction_id
                                    )
                                "
                                :disabled="isChecking"
                                class="px-4 py-2 bg-yellow-400 hover:bg-yellow-500 text-yellow-900 rounded-xl font-bold text-xs uppercase tracking-wider font-unbounded transition-all shadow-lg shadow-yellow-400/20"
                            >
                                <i
                                    :class="[
                                        'fas',
                                        isChecking
                                            ? 'fa-circle-notch fa-spin'
                                            : 'fa-sync-alt',
                                        'mr-2',
                                    ]"
                                ></i>
                                {{ isChecking ? "Cek..." : "Cek Status" }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div
                class="mt-12 flex flex-col sm:flex-row justify-center gap-4 animate-fade-in-up stagger-3"
            >
                <Link
                    :href="route('frontend.home')"
                    class="group relative px-8 py-4 bg-white dark:bg-[#111] text-gray-900 dark:text-white rounded-2xl font-bold font-unbounded uppercase tracking-wider text-sm shadow-lg shadow-gray-200 dark:shadow-none hover:-translate-y-1 transition-all duration-300 border border-gray-100 dark:border-white/10 flex items-center justify-center"
                >
                    <i
                        class="fas fa-home mr-3 text-gray-400 group-hover:text-rose-600 transition-colors"
                    ></i>
                    Ke Beranda
                </Link>

                <a
                    :href="
                        route('frontend.booking.download-ticket', booking.id)
                    "
                    target="_blank"
                    class="group relative px-8 py-4 bg-rose-600 text-white rounded-2xl font-bold font-unbounded uppercase tracking-wider text-sm shadow-xl shadow-rose-600/30 hover:-translate-y-1 transition-all duration-300 flex items-center justify-center overflow-hidden"
                >
                    <div
                        class="absolute inset-0 bg-white/20 translate-y-full group-hover:translate-y-0 transition-transform duration-300"
                    ></div>
                    <i class="fas fa-ticket-alt mr-3"></i>
                    Unduh E-Tiket
                </a>
            </div>
        </div>
    </div>
</template>
