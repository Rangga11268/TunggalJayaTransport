<script setup>
import { Head, Link } from "@inertiajs/vue3";
import FrontendLayout from "@/Layouts/FrontendLayout.vue";
import { onMounted, onUnmounted, ref, computed } from "vue";
import axios from "axios";
import Swal from "sweetalert2";
import html2canvas from "html2canvas";

defineOptions({ layout: FrontendLayout });

const props = defineProps({
    booking: Object,
});

const formatDate = (dateString, format = "long") => {
    if (!dateString) return "Tanggal Belum Tersedia";
    const date = new Date(dateString);
    if (isNaN(date.getTime()) || date.getFullYear() <= 1970)
        return "Tanggal Belum Tersedia";

    if (format === "short") {
        const d = date.getDate().toString().padStart(2, "0");
        const m = (date.getMonth() + 1).toString().padStart(2, "0");
        const y = date.getFullYear();
        return `${d}.${m}.${y}`;
    }

    return date.toLocaleDateString("id-ID", {
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
const ticketRef = ref(null);
const isDownloading = ref(false);

const downloadImage = async () => {
    if (!ticketRef.value || isDownloading.value) return;

    isDownloading.value = true;
    try {
        const canvas = await html2canvas(ticketRef.value, {
            scale: 2,
            backgroundColor: "#f3f4f6", // Match ticket outer bg
            logging: false,
            useCORS: true,
        });

        const image = canvas.toDataURL("image/png");
        const link = document.createElement("a");
        link.href = image;
        link.download = `Tujago_Ticket_${props.booking.booking_code}.png`;
        link.click();

        Swal.fire({
            icon: "success",
            title: "Tiket Disimpan",
            text: "Tiket telah berhasil disimpan ke galeri Anda.",
            timer: 2000,
            showConfirmButton: false,
        });
    } catch (error) {
        console.error("Download failed", error);
        Swal.fire({
            icon: "error",
            title: "Gagal Menyimpan",
            text: "Terjadi kesalahan saat mencoba menyimpan tiket.",
        });
    } finally {
        isDownloading.value = false;
    }
};

const checkPaymentStatus = async (orderId) => {
    if (!orderId || isChecking.value) return;

    isChecking.value = true;

    try {
        const statusResponse = await axios.get(
            route("frontend.payment.status", { orderId: orderId }),
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

// ---- Auto-refresh payment status polling ----
let pollInterval = null;
const lastAutoCheck = ref(null);

onMounted(() => {
    if (
        props.booking.payment_status !== "paid" &&
        props.booking.midtrans_transaction_id
    ) {
        pollInterval = setInterval(async () => {
            if (isChecking.value) return; // Skip if manual check is running
            try {
                const statusResponse = await axios.get(
                    route("frontend.payment.status", {
                        orderId: props.booking.midtrans_transaction_id,
                    }),
                );
                const data = statusResponse.data;
                lastAutoCheck.value = new Date().toLocaleTimeString("id-ID");

                if (data.status === "success") {
                    const trxStatus = data.transaction_status;
                    if (trxStatus === "capture" || trxStatus === "settlement") {
                        clearInterval(pollInterval);
                        Swal.fire({
                            icon: "success",
                            title: "Pembayaran Berhasil!",
                            text: "Pembayaran Anda telah dikonfirmasi.",
                            showConfirmButton: false,
                            timer: 2000,
                        }).then(() => window.location.reload());
                    } else if (
                        trxStatus === "expire" ||
                        trxStatus === "cancel" ||
                        trxStatus === "deny"
                    ) {
                        clearInterval(pollInterval);
                        Swal.fire({
                            icon: "warning",
                            title: "Pembayaran Gagal",
                            text: `Status: ${trxStatus}`,
                            confirmButtonColor: "#e11d48",
                        }).then(() => window.location.reload());
                    }
                }
            } catch (e) {
                // Silent fail — don't disturb user on auto-check errors
                console.warn("Auto-check failed:", e.message);
            }
        }, 10000); // Every 10 seconds
    }
});

onUnmounted(() => {
    if (pollInterval) clearInterval(pollInterval);
});

const routeDescription = computed(() => {
    const s = props.booking?.schedule;
    const r = s?.route;

    if (!r) return "Info Rute Tidak Tersedia";

    if (r.origin && r.destination) return `${r.origin} >> ${r.destination}`;

    if (r.description && r.description.trim() !== "" && r.description !== "-")
        return r.description;

    return "Info Rute Tidak Tersedia";
});

const busName = computed(() => {
    return props.booking?.schedule?.bus?.name || "Info Bus Tidak Tersedia";
});

const busType = computed(() => {
    return props.booking?.schedule?.bus?.bus_type || "Executive";
});

const formatTime = (timeString) => {
    if (!timeString) return "-";
    try {
        let cleanTime = timeString;

        // Remove " WIB" or other suffixes if manual string
        if (typeof timeString === "string") {
            cleanTime = timeString.split(" ")[0]; // Take strictly the ISO part
        }

        const date = new Date(cleanTime);
        if (!isNaN(date.getTime())) {
            return date.toLocaleTimeString("id-ID", {
                hour: "2-digit",
                minute: "2-digit",
                hour12: false,
            });
        }

        // Fallback: regex for HH:mm
        const match = timeString.match(/(\d{2}):(\d{2})/);
        if (match) return `${match[1]}:${match[2]}`;

        return timeString;
    } catch (e) {
        return timeString;
    }
};
</script>

<template>
    <Head title="Pemesanan Berhasil" />

    <div class="min-h-screen bg-[#fcf9f8] pt-28 pb-16 px-4 sm:px-6 lg:px-8 flex flex-col items-center">
        <!-- Decorative bg -->
        <div class="fixed top-0 left-0 w-full h-64 bg-gradient-to-b from-[#10207a]/5 to-transparent pointer-events-none"></div>
        <div class="fixed bottom-0 right-0 w-[300px] h-[300px] md:w-[600px] md:h-[600px] bg-rose-600/5 rounded-full blur-[120px] pointer-events-none"></div>

        <!-- Success Header -->
        <div class="text-center mb-10 z-10 max-w-lg">
            <div class="w-20 h-20 rounded-full bg-white shadow-xl shadow-rose-600/10 flex items-center justify-center mx-auto mb-6 border border-[#ebe7e7]">
                <i v-if="booking.payment_status === 'paid'" class="fas fa-check-circle text-4xl text-emerald-500"></i>
                <i v-else class="fas fa-clock text-4xl text-amber-500"></i>
            </div>
            <h1 class="font-unbounded font-black text-3xl md:text-4xl text-[#1c1b1b] mb-2">
                {{ booking.payment_status === 'paid' ? 'Pemesanan Berhasil!' : 'Menunggu Pembayaran' }}
            </h1>
            <p class="text-[#454652] text-[16px]">
                {{ booking.payment_status === 'paid' ? 'Terima kasih telah memilih Tunggal Jaya Transport.' : 'Mohon selesaikan pembayaran Anda.' }}
            </p>
        </div>

        <div class="max-w-[1000px] w-full z-10">
            <!-- Main Ticket Container -->
            <div ref="ticketRef" class="relative w-full bg-[#f3f4f6] rounded-2xl shadow-2xl shadow-black/10 overflow-hidden flex flex-col md:flex-row min-h-[340px] border border-[#e5e2e1]">
                <!-- Left Stub -->
                <div class="w-full md:w-[90px] bg-[#3d5684] flex items-center justify-center relative flex-shrink-0">
                    <div class="text-white font-mono text-xl tracking-[0.1em] transform md:-rotate-90 whitespace-nowrap py-4 md:py-0">E - TICKET BUS</div>
                </div>

                <!-- Ticket Body -->
                <div class="flex-1 p-8 md:p-10 relative">
                    <div class="mb-10">
                        <h1 class="font-mono text-3xl font-bold text-black tracking-tight">Tunggal Jaya Transport</h1>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                        <div>
                            <span class="block text-[10px] font-mono text-gray-500 uppercase tracking-wider mb-2">PASSENGER</span>
                            <div class="bg-[#e2e8f0] px-3 py-2 text-black font-bold font-mono text-base min-h-[40px] flex items-center">{{ booking.passenger_name.toUpperCase() }}</div>
                        </div>
                        <div>
                            <span class="block text-[10px] font-mono text-gray-500 uppercase tracking-wider mb-2">BUS NAME</span>
                            <div class="bg-[#e2e8f0] px-3 py-2 text-black font-bold font-mono text-base min-h-[40px] flex items-center">{{ busName.toUpperCase() }}</div>
                        </div>
                        <div>
                            <span class="block text-[10px] font-mono text-gray-500 uppercase tracking-wider mb-2">DATE • TIME</span>
                            <div class="bg-[#e2e8f0] px-3 py-2 text-black font-bold font-mono text-base min-h-[40px] flex items-center">
                                {{ formatDate(booking.booking_date, "short") }} • {{ formatTime(booking.schedule?.departure_time) }}
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                        <div>
                            <span class="block text-[10px] font-mono text-gray-500 uppercase tracking-wider mb-2">SEAT</span>
                            <div class="bg-[#e2e8f0] px-3 py-2 text-black font-bold font-mono text-base min-h-[40px] flex items-center">{{ booking.seat_numbers }}</div>
                        </div>
                        <div>
                            <span class="block text-[10px] font-mono text-gray-500 uppercase tracking-wider mb-2">CLASS</span>
                            <div class="bg-[#e2e8f0] px-3 py-2 text-black font-bold font-mono text-base min-h-[40px] flex items-center">{{ busType.toUpperCase() }}</div>
                        </div>
                        <div>
                            <span class="block text-[10px] font-mono text-gray-500 uppercase tracking-wider mb-2">STATUS</span>
                            <div class="bg-[#e2e8f0] px-3 py-2 font-bold font-mono text-base min-h-[40px] flex items-center gap-2">
                                <span v-if="booking.payment_status === 'paid'" class="text-green-600 uppercase flex items-center gap-1"><i class="fas fa-check-circle text-xs"></i> PAID</span>
                                <span v-else class="text-yellow-600 uppercase flex items-center gap-1"><i class="fas fa-clock text-xs"></i> PENDING</span>
                            </div>
                        </div>
                    </div>

                    <div class="mb-8">
                        <span class="block text-[10px] font-mono text-gray-500 uppercase tracking-wider mb-2">TOTAL PAYMENT</span>
                        <div class="bg-[#e2e8f0] px-3 py-2 text-rose-600 font-black font-mono text-xl min-h-[40px] flex items-center justify-between">
                            <span>{{ formatCurrency(booking.total_price) }}</span>
                            <span v-if="booking.discount_amount > 0" class="text-xs text-green-600 bg-green-100 px-2 py-0.5 rounded border border-green-200">
                                HEMAT {{ formatCurrency(booking.discount_amount) }}
                            </span>
                        </div>
                    </div>

                    <div class="mb-8">
                        <span class="block text-[10px] font-mono text-gray-500 uppercase tracking-wider mb-2">ROUTE</span>
                        <div class="bg-[#e2e8f0] px-4 py-3 w-full text-black font-bold font-mono text-lg flex items-center gap-3 border-l-4 border-rose-600">
                            {{ routeDescription.toUpperCase() }}
                        </div>
                    </div>
                </div>

                <!-- Separator -->
                <div class="relative w-full md:w-[2px] h-[2px] md:h-auto bg-transparent border-t-2 md:border-t-0 md:border-l-2 border-dashed border-gray-400 flex-shrink-0 my-4 md:my-0">
                    <div class="absolute -left-[14px] top-[-15px] md:-top-[15px] md:-left-[15px] w-[30px] h-[30px] bg-[#fcf9f8] rounded-full z-10 hidden md:block"></div>
                    <div class="absolute -left-[14px] bottom-[-15px] md:-bottom-[15px] md:-left-[15px] w-[30px] h-[30px] bg-[#fcf9f8] rounded-full z-10 hidden md:block"></div>
                </div>

                <!-- Right Stub -->
                <div class="w-full md:w-[25%] p-6 flex flex-col items-center justify-center bg-[#f3f4f6]">
                    <span class="text-[10px] font-mono text-[#3d5684] tracking-wider mb-4">Scan to check in</span>
                    <div class="bg-white p-2 border-4 border-black mb-4">
                        <div class="flex justify-between w-[120px] h-[80px] overflow-hidden">
                            <div v-for="i in 25" :key="i" class="bg-black h-full" :style="{ width: Math.random() > 0.5 ? '4px' : '2px', marginLeft: '2px' }"></div>
                        </div>
                    </div>
                    <span class="text-xs font-mono font-bold text-[#3d5684] tracking-widest">ID {{ booking.booking_code }}</span>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="mt-12 flex flex-col sm:flex-row justify-center gap-4">
                <Link :href="route('frontend.home')" class="px-8 py-4 rounded-xl bg-white border border-[#e5e2e1] text-[#1c1b1b] font-bold text-[14px] tracking-wide uppercase hover:bg-gray-50 transition-all shadow-sm flex items-center justify-center gap-3">
                    <i class="fas fa-arrow-left"></i> Kembali ke Home
                </Link>

                <a :href="route('frontend.booking.download-ticket', booking.id)" target="_blank" class="px-8 py-4 rounded-xl bg-[#10207a] text-white font-bold text-[14px] tracking-wide uppercase hover:bg-[#0c185e] transition-all shadow-lg shadow-[#10207a]/20 flex items-center justify-center gap-3">
                    <i class="fas fa-file-pdf"></i> Download PDF
                </a>

                <button @click="downloadImage" :disabled="isDownloading" class="px-8 py-4 rounded-xl bg-rose-600 text-white font-bold text-[14px] tracking-wide uppercase hover:bg-rose-700 transition-all shadow-lg shadow-rose-600/20 flex items-center justify-center gap-3">
                    <i :class="[isDownloading ? 'fa-spinner fa-spin' : 'fa-image', 'fas']"></i>
                    {{ isDownloading ? 'Menyimpan...' : 'Simpan ke Galeri' }}
                </button>

                <button v-if="booking.payment_status !== 'paid' && booking.midtrans_transaction_id"
                    @click="checkPaymentStatus(booking.midtrans_transaction_id)" :disabled="isChecking"
                    class="px-8 py-4 rounded-xl bg-amber-500 text-white font-bold text-[14px] tracking-wide uppercase hover:bg-amber-600 transition-all shadow-lg shadow-amber-500/20 flex items-center justify-center gap-3">
                    <i :class="['fas', isChecking ? 'fa-spinner fa-spin' : 'fa-sync-alt']"></i>
                    {{ isChecking ? 'Memeriksa...' : 'Cek Status' }}
                </button>
            </div>

            <p v-if="booking.payment_status !== 'paid' && booking.midtrans_transaction_id" class="text-center text-gray-400 text-[12px] mt-6">
                <i class="fas fa-sync-alt fa-spin mr-1 text-[10px]"></i>
                Auto-check aktif <span v-if="lastAutoCheck">· terakhir {{ lastAutoCheck }}</span>
            </p>

            <p class="text-center text-gray-400 text-[12px] mt-8">
                Simpan tiket ini sebagai bukti pembayaran yang sah.
            </p>
        </div>
    </div>
</template>

<style scoped></style>
