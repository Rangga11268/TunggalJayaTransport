<script setup>
import { Head, Link } from "@inertiajs/vue3";
import FrontendLayout from "@/Layouts/FrontendLayout.vue";
import { onMounted, ref, computed } from "vue";
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

    // Explicit format as shown in design "City ? City"
    // Though design shows "?" instead of arrow, I assume it's an encoding issue in the PDF preview user sent.
    // I will use ">>" or "->" for clarity, or just standard dash.
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
        // Handle "2000-01-01T14:00:00.000000Z WIB" or standard ISO
        // First, check if it contains a T and Z or time offsets
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

    <div
        class="bg-gray-100 dark:bg-[#050505] min-h-screen py-12 px-4 sm:px-6 lg:px-8 flex flex-col items-center justify-center relative overflow-hidden font-mono"
    >
        <!-- Success Header (Restored) -->
        <div class="text-center mb-10 z-10">
            <div
                class="w-20 h-20 rounded-full bg-white dark:bg-white/10 shadow-xl shadow-rose-600/10 flex items-center justify-center mx-auto mb-6 animate-bounce-in border border-gray-200 dark:border-white/5"
            >
                <i
                    v-if="booking.payment_status === 'paid'"
                    class="fas fa-check-circle text-4xl text-rose-600"
                ></i>
                <i v-else class="fas fa-clock text-4xl text-yellow-500"></i>
            </div>

            <h1
                class="font-sans font-black text-2xl md:text-4xl text-gray-900 dark:text-white mb-2 uppercase animate-fade-in-up tracking-tight"
            >
                {{
                    booking.payment_status === "paid"
                        ? "Pemesanan Berhasil!"
                        : "Menunggu Pembayaran"
                }}
            </h1>
            <p
                class="font-sans text-gray-500 dark:text-gray-400 animate-fade-in-up stagger-1"
            >
                {{
                    booking.payment_status === "paid"
                        ? "Terima kasih telah memilih Tunggal Jaya Transport."
                        : "Mohon selesaikan pembayaran Anda."
                }}
            </p>
        </div>

        <div class="max-w-[1000px] w-full z-10">
            <!-- Main Ticket Container (mimicking PDF style) -->
            <div
                ref="ticketRef"
                class="relative w-full bg-[#f3f4f6] dark:bg-[#e5e5e5] rounded-xl shadow-2xl overflow-hidden flex flex-col md:flex-row min-h-[340px]"
            >
                <!-- Left Stub (Blue Strip) -->
                <div
                    class="w-full md:w-[90px] bg-[#3d5684] flex items-center justify-center relative flex-shrink-0"
                >
                    <div
                        class="text-white font-mono text-xl tracking-[0.1em] transform md:-rotate-90 whitespace-nowrap py-4 md:py-0"
                    >
                        E - TICKET BUS
                    </div>
                </div>

                <!-- Ticket Body -->
                <div class="flex-1 p-8 md:p-10 relative">
                    <!-- Title -->
                    <div class="mb-10">
                        <h1
                            class="font-mono text-3xl font-bold text-black tracking-tight flex items-center gap-4"
                        >
                            <!-- Logo placeholder if desired, else text -->
                            Tunggal Jaya Transport
                        </h1>
                    </div>

                    <!-- Info Grid -->
                    <div
                        class="grid grid-cols-2 md:grid-cols-4 gap-y-8 gap-x-4 mb-8"
                    >
                        <!-- Passenger -->
                        <div class="col-span-1">
                            <span
                                class="block text-xs font-mono text-gray-500 uppercase tracking-wider mb-2"
                                >PASSENGER</span
                            >
                            <div
                                class="bg-[#e5e7eb] px-3 py-2 text-black font-bold font-mono text-lg shadow-sm inline-block min-w-[120px]"
                            >
                                {{
                                    booking.passenger_name
                                        .toUpperCase()
                                        .substring(0, 15)
                                }}
                            </div>
                        </div>

                        <!-- Date -->
                        <div class="col-span-1">
                            <span
                                class="block text-xs font-mono text-gray-500 uppercase tracking-wider mb-2"
                                >DATE</span
                            >
                            <div
                                class="bg-[#e5e7eb] px-3 py-2 text-black font-bold font-mono text-lg shadow-sm inline-block min-w-[120px]"
                            >
                                {{ formatDate(booking.booking_date, "short") }}
                            </div>
                        </div>

                        <!-- Seat -->
                        <div class="col-span-1">
                            <span
                                class="block text-xs font-mono text-gray-500 uppercase tracking-wider mb-2"
                                >SEAT</span
                            >
                            <div
                                class="bg-[#e5e7eb] px-3 py-2 text-black font-bold font-mono text-lg shadow-sm inline-block min-w-[80px]"
                            >
                                {{ booking.seat_numbers }}
                            </div>
                        </div>

                        <!-- Time -->
                        <div class="col-span-1">
                            <span
                                class="block text-xs font-mono text-gray-500 uppercase tracking-wider mb-2"
                                >TIME</span
                            >
                            <div
                                class="bg-[#e5e7eb] px-3 py-2 text-black font-bold font-mono text-lg shadow-sm inline-block min-w-[80px]"
                            >
                                {{
                                    formatTime(booking.schedule?.departure_time)
                                }}
                            </div>
                        </div>
                    </div>

                    <!-- Route Box -->
                    <div class="mb-8">
                        <span
                            class="block text-xs font-mono text-gray-500 uppercase tracking-wider mb-2"
                            >ROUTE</span
                        >
                        <div
                            class="bg-[#e5e7eb] px-4 py-3 w-full md:w-[90%] text-black font-bold font-mono text-lg shadow-sm flex items-center gap-3"
                        >
                            {{ routeDescription }}
                        </div>
                    </div>

                    <!-- Class & Status -->
                    <div class="flex items-center gap-6">
                        <div>
                            <span
                                class="block text-xs font-mono text-gray-500 uppercase tracking-wider mb-2"
                                >CLASS</span
                            >
                            <span class="font-bold text-black uppercase">{{
                                busType
                            }}</span>
                        </div>
                        <div class="h-8 w-[1px] bg-gray-300"></div>
                        <div>
                            <span
                                class="block text-xs font-mono text-gray-500 uppercase tracking-wider mb-2"
                                >STATUS</span
                            >
                            <span
                                v-if="booking.payment_status === 'paid'"
                                class="text-green-600 font-bold uppercase flex items-center gap-1"
                            >
                                <i class="fas fa-check-circle"></i> PAID
                            </span>
                            <span
                                v-else
                                class="text-yellow-600 font-bold uppercase flex items-center gap-1"
                            >
                                <i class="fas fa-clock"></i> PENDING
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Separation Line -->
                <div
                    class="relative w-full md:w-[2px] h-[2px] md:h-auto bg-transparent border-t-2 md:border-t-0 md:border-l-2 border-dashed border-gray-400 flex-shrink-0 my-4 md:my-0"
                >
                    <!-- Cutout Circles -->
                    <div
                        class="absolute -left-[14px] top-[-15px] md:-top-[15px] md:-left-[15px] w-[30px] h-[30px] bg-gray-100 dark:bg-[#050505] rounded-full z-10 hidden md:block"
                    ></div>
                    <div
                        class="absolute -left-[14px] bottom-[-15px] md:-bottom-[15px] md:-left-[15px] w-[30px] h-[30px] bg-gray-100 dark:bg-[#050505] rounded-full z-10 hidden md:block"
                    ></div>
                </div>

                <!-- Right Stub (Barcode) -->
                <div
                    class="w-full md:w-[25%] p-6 flex flex-col items-center justify-center bg-[#f3f4f6]"
                >
                    <span
                        class="text-[10px] font-mono text-[#3d5684] tracking-wider mb-4"
                        >Scan to check in</span
                    >

                    <!-- Mock Barcode Visual -->
                    <div class="bg-white p-2 border-4 border-black mb-4">
                        <div
                            class="flex justify-between w-[120px] h-[80px] overflow-hidden"
                        >
                            <!-- Generating random bars for visual effect -->
                            <div
                                v-for="i in 25"
                                :key="i"
                                class="bg-black h-full"
                                :style="{
                                    width: Math.random() > 0.5 ? '4px' : '2px',
                                    marginLeft: '2px',
                                }"
                            ></div>
                        </div>
                    </div>

                    <span
                        class="text-xs font-mono font-bold text-[#3d5684] tracking-widest"
                        >ID {{ booking.booking_code }}</span
                    >
                </div>
            </div>

            <!-- Action Buttons Below Ticket -->
            <div class="mt-12 flex flex-col sm:flex-row justify-center gap-4">
                <Link
                    :href="route('frontend.home')"
                    class="btn-secondary px-8 py-4 font-mono font-bold uppercase tracking-wider text-sm flex items-center justify-center bg-gray-800 text-white rounded hover:bg-gray-700 transition"
                >
                    <i class="fas fa-home mr-2"></i> Home
                </Link>

                <a
                    :href="
                        route('frontend.booking.download-ticket', booking.id)
                    "
                    target="_blank"
                    class="btn-primary px-8 py-4 font-mono font-bold uppercase tracking-wider text-sm flex items-center justify-center bg-gray-900 dark:bg-black text-white rounded hover:bg-black/90 transition shadow-lg"
                >
                    <i class="fas fa-file-pdf mr-2"></i> PDF
                </a>

                <button
                    @click="downloadImage"
                    :disabled="isDownloading"
                    class="btn-primary px-8 py-4 font-mono font-bold uppercase tracking-wider text-sm flex items-center justify-center bg-rose-600 text-white rounded hover:bg-rose-700 transition shadow-lg"
                >
                    <i
                        :class="[
                            isDownloading ? 'fa-spinner fa-spin' : 'fa-image',
                            'fas',
                            'mr-2',
                        ]"
                    ></i>
                    {{ isDownloading ? "Saving..." : "Save to Image" }}
                </button>

                <button
                    v-if="
                        booking.payment_status !== 'paid' &&
                        booking.midtrans_transaction_id
                    "
                    @click="checkPaymentStatus(booking.midtrans_transaction_id)"
                    :disabled="isChecking"
                    class="px-8 py-4 font-mono font-bold uppercase tracking-wider text-sm flex items-center justify-center bg-yellow-500 text-white rounded hover:bg-yellow-600 transition shadow-lg"
                >
                    <i
                        :class="[
                            'fas',
                            isChecking ? 'fa-spinner fa-spin' : 'fa-sync-alt',
                            'mr-2',
                        ]"
                    ></i>
                    {{ isChecking ? "Checking..." : "Check Status" }}
                </button>
            </div>

            <p class="text-center text-gray-500 font-mono text-xs mt-8">
                Generated on {{ new Date().toLocaleString() }} <br />
                Simpan tiket ini sebagai bukti pembayaran yang sah.
            </p>
        </div>
    </div>
</template>

<style scoped>
/* Font import for Monospace look if needed, but Tailwind 'font-mono' usually suffices (Courier/Consolas) */
</style>
