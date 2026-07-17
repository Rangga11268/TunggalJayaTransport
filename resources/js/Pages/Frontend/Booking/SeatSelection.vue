<script setup>
import { Head, useForm, router } from "@inertiajs/vue3";
import FrontendLayout from "@/Layouts/FrontendLayout.vue";
import { ref, computed, onMounted, onUnmounted } from "vue";
import axios from "axios";
import Swal from "sweetalert2";

defineOptions({ layout: FrontendLayout });

const props = defineProps({
    booking: Object,
    occupiedSeats: Array,
    bookingExpiresAt: String, // ISO8601 expiry timestamp (created_at + 30 min)
});

// ---- Countdown Timer ----
const countdown = ref({ minutes: 30, seconds: 0, expired: false });
let countdownInterval = null;

const updateCountdown = () => {
    if (!props.bookingExpiresAt) return;
    const diff = Math.floor(
        (new Date(props.bookingExpiresAt) - Date.now()) / 1000,
    );
    if (diff <= 0) {
        countdown.value = { minutes: 0, seconds: 0, expired: true };
        clearInterval(countdownInterval);
        return;
    }
    countdown.value = {
        minutes: Math.floor(diff / 60),
        seconds: diff % 60,
        expired: false,
    };
};

const countdownColor = computed(() => {
    const total = countdown.value.minutes * 60 + countdown.value.seconds;
    if (total <= 120) return "text-red-600"; // ≤ 2 menit
    if (total <= 300) return "text-amber-600"; // ≤ 5 menit
    return "text-emerald-600";
});

const countdownBg = computed(() => {
    const total = countdown.value.minutes * 60 + countdown.value.seconds;
    if (total <= 120)
        return "bg-red-50 border-red-200";
    if (total <= 300)
        return "bg-amber-50 border-amber-200";
    return "bg-emerald-50 border-emerald-200";
});

const padZero = (n) => String(n).padStart(2, "0");

const selectedSeats = ref([]);
const processing = ref(false);
const error = ref(null);

const onBeforeUnload = (e) => {
    if (selectedSeats.value.length > 0) {
        e.preventDefault();
        e.returnValue = "";
    }
};

onMounted(() => {
    if (props.booking.seat_numbers) {
        selectedSeats.value = props.booking.seat_numbers.split(",").map(Number);
    }
    // Start countdown
    updateCountdown();
    countdownInterval = setInterval(updateCountdown, 1000);
    // Prevent accidental page leave
    window.addEventListener("beforeunload", onBeforeUnload);
});

onUnmounted(() => {
    clearInterval(countdownInterval);
    window.removeEventListener("beforeunload", onBeforeUnload);
});

// Seat Map Configuration
const totalSeats = props.booking.schedule.bus.capacity;
const rows = Math.ceil(totalSeats / 5); // 2-3 Layout = 5 seats per row

// Ensure occupiedSeats are integers for comparison
const occupiedSeatsInt = computed(() =>
    props.occupiedSeats.map((s) => parseInt(s)),
);

const isSeatOccupied = (seatNum) =>
    occupiedSeatsInt.value.includes(parseInt(seatNum));
const isSeatSelected = (seatNum) => selectedSeats.value.includes(seatNum);

const toggleSeat = (seatNum) => {
    if (countdown.value.expired) return;
    if (isSeatOccupied(seatNum)) return;

    const index = selectedSeats.value.indexOf(seatNum);
    if (index !== -1) {
        // Deselect
        selectedSeats.value.splice(index, 1);
    } else {
        // Select
        if (selectedSeats.value.length < props.booking.number_of_seats) {
            selectedSeats.value.push(seatNum);
        } else {
            Swal.fire({
                icon: "warning",
                title: "Batas Kursi",
                text: `Anda hanya memesan ${props.booking.number_of_seats} kursi.`,
                confirmButtonColor: "#10207a",
            });
        }
    }
};

// Helper to save seats returns success boolean
const saveSeats = async () => {
    if (selectedSeats.value.length !== props.booking.number_of_seats) {
        Swal.fire({
            icon: "warning",
            title: "Kursi Belum Lengkap",
            text: `Anda baru memilih ${selectedSeats.value.length} dari ${props.booking.number_of_seats} kursi yang dipesan.`,
            confirmButtonColor: "#10207a",
        });
        return false;
    }

    processing.value = true;
    try {
        const response = await axios.post(
            route("frontend.booking.select-seats"),
            {
                booking_id: props.booking.id,
                seat_numbers: selectedSeats.value,
            },
        );

        if (response.data.success) {
            return true;
        } else {
            Swal.fire({
                icon: "error",
                title: "Gagal Simpan Kursi",
                text: response.data.message || "Gagal menyimpan kursi.",
                confirmButtonColor: "#10207a",
            });
            return false;
        }
    } catch (e) {
        console.error("Save Seats Error:", e);
        Swal.fire({
            icon: "error",
            title: "Koneksi Gagal",
            text:
                e.response?.data?.message ||
                "Gagal menghubungi server untuk simpan kursi.",
            confirmButtonColor: "#10207a",
        });
        return false;
    } finally {
        processing.value = false;
    }
};

// Payment Logic
const paymentMethod = ref("");
const ewalletType = ref(""); // gopay, shopeepay, dana

// Promo Code Logic
const promoCode = ref("");
const promoCodeId = ref(null);
const discountAmount = ref(0);
const promoMessage = ref("");
const promoValid = ref(false);
const promoLoading = ref(false);

const finalPrice = computed(() => {
    return Math.max(0, props.booking.total_price - discountAmount.value);
});

const validatePromo = async () => {
    if (!promoCode.value) return;
    promoLoading.value = true;
    promoMessage.value = "";

    try {
        const response = await axios.post(route("api.promo.validate"), {
            code: promoCode.value,
            total_amount: props.booking.total_price,
        });

        if (response.data.valid) {
            promoValid.value = true;
            discountAmount.value = response.data.discount_amount;
            promoCodeId.value = response.data.promo_code_id;
            promoMessage.value = response.data.message;
        }
    } catch (e) {
        promoValid.value = false;
        discountAmount.value = 0;
        promoCodeId.value = null;
        promoMessage.value =
            e.response?.data?.message || "Kode promo tidak valid.";
    } finally {
        promoLoading.value = false;
    }
};

const resetPromo = () => {
    promoCode.value = "";
    promoCodeId.value = null;
    discountAmount.value = 0;
    promoValid.value = false;
    promoMessage.value = "";
};

const processPayment = async () => {
    // 0. Guard: block if countdown expired
    if (countdown.value.expired) {
        Swal.fire({
            icon: "error",
            title: "Waktu Habis",
            text: "Waktu pemesanan Anda telah habis. Silakan buat pemesanan baru.",
            confirmButtonColor: "#10207a",
        });
        return;
    }

    // 1. Validation
    if (selectedSeats.value.length !== props.booking.number_of_seats) {
        Swal.fire({
            icon: "warning",
            title: "Kursi Belum Lengkap",
            text: `Anda baru memilih ${selectedSeats.value.length} dari ${props.booking.number_of_seats} kursi yang dipesan.`,
            confirmButtonColor: "#10207a",
        });
        return;
    }
    if (!paymentMethod.value) {
        Swal.fire({
            icon: "warning",
            title: "Metode Pembayaran",
            text: "Pilih metode pembayaran.",
            confirmButtonColor: "#10207a",
        });
        return;
    }
    if (paymentMethod.value === "e_wallet" && !ewalletType.value) {
        Swal.fire({
            icon: "warning",
            title: "E-Wallet",
            text: "Pilih jenis E-Wallet.",
            confirmButtonColor: "#10207a",
        });
        return;
    }

    const saved = await saveSeats();
    if (!saved) return;

    // Remove beforeunload guard during payment redirect
    window.removeEventListener("beforeunload", onBeforeUnload);

    processing.value = true;
    try {
        const payload = {
            booking_id: props.booking.id,
            payment_method:
                paymentMethod.value === "e_wallet"
                    ? ewalletType.value
                    : paymentMethod.value,
            promo_code_id: promoCodeId.value,
        };

        const response = await axios.post(
            route("frontend.booking.process-payment"),
            payload,
        );

        if (response.data.success) {
            if (response.data.snap_token) {
                window.snap.pay(response.data.snap_token, {
                    onSuccess: function (result) {
                        router.visit(
                            route("frontend.booking.success", props.booking.id),
                        );
                    },
                    onPending: function (result) {
                        router.visit(
                            route("frontend.booking.success", props.booking.id),
                        );
                    },
                    onError: function (result) {
                        Swal.fire({
                            icon: "error",
                            title: "Pembayaran Gagal",
                            text: "Terjadi kesalahan saat memproses pembayaran.",
                            confirmButtonColor: "#10207a",
                        });
                    },
                    onClose: function () {
                        // customer closed the popup without finishing the payment
                        window.addEventListener("beforeunload", onBeforeUnload);
                    },
                });
            } else if (response.data.redirect_url) {
                window.location.href = response.data.redirect_url;
            }
        } else {
            Swal.fire({
                icon: "error",
                title: "Gagal",
                text: response.data.message,
                confirmButtonColor: "#10207a",
            });
            window.addEventListener("beforeunload", onBeforeUnload);
        }
    } catch (e) {
        console.error(e);
        Swal.fire({
            icon: "error",
            title: "Gagal Memproses",
            text:
                "Gagal memproses pembayaran: " +
                (e.response?.data?.message || e.message),
            confirmButtonColor: "#10207a",
        });
        window.addEventListener("beforeunload", onBeforeUnload);
    } finally {
        processing.value = false;
    }
};

const formatCurrency = (value) => {
    return new Intl.NumberFormat("id-ID", {
        style: "currency",
        currency: "IDR",
        minimumFractionDigits: 0,
    }).format(value);
};

// Layout Utils
const getSeatNumber = (rowIdx, colIdx) => {
    // colIdx: 0, 1 (Left) -- 2, 3, 4 (Right)
    const base = rowIdx * 5;
    return base + colIdx + 1; // 1-based
};

// Helper to safely get route description
const routeDescription = computed(() => {
    const s = props.booking?.schedule;
    const r = s?.route;
    if (!r) return "Info Rute Tidak Tersedia";
    if (r.description && r.description.trim() !== "-" && r.description.trim() !== "")
        return r.description;
    if (r.origin && r.destination) {
        return `${r.origin} - ${r.destination}`;
    }
    return "Info Rute Tidak Tersedia";
});

const busName = computed(() => {
    return props.booking?.schedule?.bus?.name || "TUNGGAL JAYA";
});

const busType = computed(() => {
    return props.booking?.schedule?.bus?.bus_type || "EXECUTIVE";
});

const formatTime = (timeStr) => {
    if (!timeStr) return "";
    // If it's a full ISO date string
    if (timeStr.includes("T")) {
        const date = new Date(timeStr);
        if (!isNaN(date.getTime())) {
            return date.toLocaleTimeString('id-ID', {hour: '2-digit', minute:'2-digit', hour12: false}).replace('.', ':');
        }
    }
    // If it's just "HH:mm:ss"
    return timeStr.substring(0, 5);
};
</script>

<template>
    <Head title="Pilih Kursi - Tunggal Jaya Transport" />

    <div class="bg-white min-h-screen  text-[#1c1b1b] relative pb-24">
        
        <div class="max-w-[1280px] mx-auto px-4 sm:px-8 lg:px-16 pt-[140px] pb-8">
            
            <!-- Step Indicator -->
            <div class="flex items-center justify-center w-full mb-12 flex-wrap gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-8 h-8 rounded-full border border-[#c6c5d3] flex items-center justify-center text-[#454652] font-bold text-xs">1</div>
                    <span class="font-semibold text-[#454652] text-sm tracking-wide uppercase">Schedule</span>
                </div>
                <div class="w-16 h-px bg-[#c6c5d3]"></div>
                <div class="flex items-center gap-4">
                    <div class="w-8 h-8 rounded-full bg-[#10207a] text-white flex items-center justify-center font-bold text-xs">2</div>
                    <span class="font-bold text-[#10207a] text-sm tracking-wide uppercase">Seat Selection</span>
                </div>
                <div class="w-16 h-px bg-[#c6c5d3]"></div>
                <div class="flex items-center gap-4">
                    <div class="w-8 h-8 rounded-full border border-[#c6c5d3] flex items-center justify-center text-[#767683] font-bold text-xs">3</div>
                    <span class="font-semibold text-[#767683] text-sm tracking-wide uppercase">Payment</span>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                
                <!-- LEFT COLUMN: Bus Layout -->
                <div class="lg:col-span-8 flex flex-col gap-8">
                    
                    <!-- Legend -->
                    <div class="bg-white border border-gray-200 shadow-sm rounded-lg p-6 flex flex-wrap gap-8 items-center justify-center">
                        <div class="flex items-center gap-2">
                            <div class="w-6 h-6 border border-[#c6c5d3] bg-white rounded-sm"></div>
                            <span class="font-semibold text-sm">Available</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="w-6 h-6 bg-[#10207a] rounded-sm"></div>
                            <span class="font-semibold text-sm">Selected</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="w-6 h-6 bg-[#ebe7e7] rounded-sm text-gray-400 flex items-center justify-center"><i class="fas fa-times text-xs"></i></div>
                            <span class="font-semibold text-sm">Occupied</span>
                        </div>
                    </div>

                    <!-- Bus Container -->
                    <div class="bg-[#f6f3f2] rounded-3xl p-8 flex justify-center border border-[#c6c5d3]">
                        <div class="bg-white border border-[#c6c5d3] rounded-3xl p-6 min-w-[300px] w-full max-w-md mx-auto relative shadow-inner">
                            
                            <!-- Driver & Door Area -->
                            <div class="flex justify-between items-start border-b-2 border-dashed border-[#c6c5d3] pb-6 mb-8">
                                <div class="flex flex-col items-center">
                                    <div class="w-12 h-12 border-2 border-[#c6c5d3] border-dashed rounded-lg flex items-center justify-center text-[#c6c5d3] mb-2">
                                        <i class="fas fa-door-open"></i>
                                    </div>
                                    <span class="text-[10px] font-bold text-[#767683] tracking-widest uppercase">Pintu</span>
                                </div>
                                <div class="flex flex-col items-center">
                                    <div class="w-16 h-16 bg-[#f6f3f2] border border-[#c6c5d3] rounded-2xl flex items-center justify-center text-[#767683] mb-2">
                                        <i class="fas fa-steering-wheel text-2xl"></i>
                                    </div>
                                    <span class="text-[10px] font-bold text-[#767683] tracking-widest uppercase">Sopir</span>
                                </div>
                            </div>

                            <!-- Seat Grid -->
                            <div class="flex flex-col gap-6 items-center">
                                <div v-for="r in rows" :key="r" class="flex gap-12 w-full justify-center">
                                    
                                    <!-- Left Side (2 seats) -->
                                    <div class="flex gap-3">
                                        <template v-for="c in 2" :key="`L-${r}-${c}`">
                                            <button 
                                                v-if="getSeatNumber(r - 1, c - 1) <= totalSeats"
                                                @click="toggleSeat(getSeatNumber(r - 1, c - 1))"
                                                :disabled="isSeatOccupied(getSeatNumber(r - 1, c - 1))"
                                                class="w-12 h-12 rounded-lg border flex items-center justify-center font-bold text-sm transition-colors relative"
                                                :class="[
                                                    isSeatOccupied(getSeatNumber(r - 1, c - 1)) 
                                                        ? 'bg-[#ebe7e7] border-transparent text-gray-400 cursor-not-allowed'
                                                        : isSeatSelected(getSeatNumber(r - 1, c - 1))
                                                            ? 'bg-[#10207a] border-[#10207a] text-white'
                                                            : 'bg-white border-[#c6c5d3] text-[#454652] hover:border-[#10207a] hover:text-[#10207a]'
                                                ]"
                                            >
                                                {{ getSeatNumber(r - 1, c - 1) }}
                                            </button>
                                        </template>
                                    </div>
                                    
                                    <!-- Aisle -->
                                    <div class="w-4 shrink-0"></div>

                                    <!-- Right Side (3 seats) -->
                                    <div class="flex gap-3">
                                        <template v-for="c in 3" :key="`R-${r}-${c}`">
                                            <button 
                                                v-if="getSeatNumber(r - 1, c + 1) <= totalSeats"
                                                @click="toggleSeat(getSeatNumber(r - 1, c + 1))"
                                                :disabled="isSeatOccupied(getSeatNumber(r - 1, c + 1))"
                                                class="w-12 h-12 rounded-lg border flex items-center justify-center font-bold text-sm transition-colors relative"
                                                :class="[
                                                    isSeatOccupied(getSeatNumber(r - 1, c + 1)) 
                                                        ? 'bg-[#ebe7e7] border-transparent text-gray-400 cursor-not-allowed'
                                                        : isSeatSelected(getSeatNumber(r - 1, c + 1))
                                                            ? 'bg-[#10207a] border-[#10207a] text-white'
                                                            : 'bg-white border-[#c6c5d3] text-[#454652] hover:border-[#10207a] hover:text-[#10207a]'
                                                ]"
                                            >
                                                {{ getSeatNumber(r - 1, c + 1) }}
                                            </button>
                                        </template>
                                    </div>

                                </div>
                            </div>
                            
                            <!-- Exit Door -->
                            <div class="flex justify-start items-end border-t-2 border-dashed border-[#c6c5d3] pt-6 mt-8">
                                <div class="flex flex-col items-center">
                                    <div class="w-12 h-12 border-2 border-[#c6c5d3] border-dashed rounded-lg flex items-center justify-center text-[#c6c5d3] mb-2">
                                        <i class="fas fa-door-open"></i>
                                    </div>
                                    <span class="text-[10px] font-bold text-[#767683] tracking-widest uppercase">Pintu Keluar</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- RIGHT COLUMN: Journey Summary & Payment -->
                <div class="lg:col-span-4 flex flex-col gap-6">
                    
                    <div class="bg-[#f6f3f2] border border-[#c6c5d3] rounded-[8px] p-6 flex flex-col gap-6 sticky top-28">
                        <h3 class="font-bold text-[18px]">Ringkasan Perjalanan</h3>
                        
                        <div class="flex justify-between items-center border-b border-[#c6c5d3] pb-4">
                            <div>
                                <p class="font-bold text-[16px]">{{ props.booking.schedule.route.origin }} <i class="fas fa-arrow-right mx-2 text-gray-400"></i> {{ props.booking.schedule.route.destination }}</p>
                                <p class="text-[14px] text-[#454652] mt-1">{{ busType }}</p>
                            </div>
                        </div>

                        <!-- Time & Date -->
                        <div class="flex items-center gap-4 py-2">
                            <div class="w-12 h-12 rounded-full bg-white border border-[#c6c5d3] flex items-center justify-center text-[#10207a]">
                                <i class="fas fa-calendar-alt"></i>
                            </div>
                            <div class="flex flex-col">
                                <span class="font-bold text-[#1c1b1b] text-[14px]">{{ formatTime(props.booking.schedule.departure_time) }} - {{ formatTime(props.booking.schedule.arrival_time) }}</span>
                                <span class="text-[#454652] text-[12px]">{{ props.booking.booking_date }}</span>
                            </div>
                        </div>

                        <!-- Seats Status -->
                        <div class="border-t border-[#c6c5d3] pt-4">
                            <div class="flex justify-between items-center mb-2">
                                <span class="font-semibold text-[14px] text-[#454652]">Kursi Dipilih:</span>
                                <span class="font-bold text-[#10207a]">{{ selectedSeats.length }} / {{ props.booking.number_of_seats }}</span>
                            </div>
                            <div class="flex flex-wrap gap-2 mb-4">
                                <div v-for="seat in selectedSeats" :key="seat" class="px-3 py-1 bg-[#dfe0ff] text-[#000e5e] rounded-[4px] font-bold text-xs">
                                    {{ seat }}
                                </div>
                                <div v-if="selectedSeats.length === 0" class="text-sm text-gray-400 italic">Belum ada kursi yang dipilih</div>
                            </div>
                        </div>

                        <!-- Timer -->
                        <div v-if="bookingExpiresAt" class="flex flex-col gap-2 bg-white rounded-[8px] p-4 border" :class="countdownBg">
                            <div class="flex items-center justify-between" :class="countdownColor">
                                <span class="font-semibold text-sm">Selesaikan dalam:</span>
                                <div class="font-bold tracking-widest text-[16px] flex items-center gap-2">
                                    <i class="fas fa-clock"></i>
                                    <span v-if="!countdown.expired">{{ padZero(countdown.minutes) }}:{{ padZero(countdown.seconds) }}</span>
                                    <span v-else>Waktu Habis</span>
                                </div>
                            </div>
                        </div>

                        <!-- Promo Code -->
                        <div class="flex flex-col gap-2">
                            <label class="font-bold text-[#454652] text-[12px] tracking-[0.6px] uppercase">Kode Promo</label>
                            <div class="flex gap-2">
                                <input v-model="promoCode" type="text" placeholder="Masukkan kode promo" class="w-full bg-white border border-[#c6c5d3] rounded-[8px] px-4 py-3 font-semibold focus:ring-2 focus:ring-[#10207a] outline-none">
                                <button type="button" @click="validatePromo" :disabled="promoLoading || !promoCode" class="bg-[#1c1b1b] text-white px-6 py-3 rounded-[8px] font-semibold text-sm hover:bg-black transition-colors disabled:opacity-50">Terapkan</button>
                            </div>
                            <p v-if="promoMessage" :class="promoValid ? 'text-emerald-600' : 'text-red-500'" class="text-xs font-semibold mt-1">{{ promoMessage }}</p>
                            <button v-if="promoValid" @click="resetPromo" class="text-xs text-red-500 font-semibold self-start hover:underline">Hapus Promo</button>
                        </div>

                        <!-- Payment Methods -->
                        <div class="flex flex-col gap-2">
                            <label class="font-bold text-[#454652] text-[12px] tracking-[0.6px] uppercase">Metode Pembayaran</label>
                            <select v-model="paymentMethod" class="w-full bg-white border border-[#c6c5d3] rounded-[8px] px-4 py-3 font-semibold focus:ring-2 focus:ring-[#10207a] outline-none">
                                <option value="" disabled>Pilih Pembayaran</option>
                                <option value="bank_transfer">Transfer Bank (Virtual Account)</option>
                                <option value="e_wallet">E-Wallet (GoPay, Dana, ShopeePay)</option>
                                <option value="credit_card">Kartu Kredit / Debit</option>
                            </select>
                            
                            <select v-if="paymentMethod === 'e_wallet'" v-model="ewalletType" class="w-full bg-white border border-[#c6c5d3] rounded-[8px] px-4 py-3 font-semibold focus:ring-2 focus:ring-[#10207a] outline-none mt-2">
                                <option value="" disabled>Pilih Jenis E-Wallet</option>
                                <option value="gopay">GoPay</option>
                                <option value="shopeepay">ShopeePay</option>
                                <option value="dana">DANA</option>
                            </select>
                        </div>

                        <div class="border-t border-[#c6c5d3] pt-4 mt-2">
                            <div class="flex justify-between items-center mb-2">
                                <span class="font-normal text-[#454652]">Subtotal</span>
                                <span class="font-semibold text-[#1c1b1b]">{{ formatCurrency(props.booking.total_price) }}</span>
                            </div>
                            <div v-if="discountAmount > 0" class="flex justify-between items-center mb-2 text-emerald-600">
                                <span class="font-normal">Diskon</span>
                                <span class="font-semibold">-{{ formatCurrency(discountAmount) }}</span>
                            </div>
                            <div class="flex justify-between items-center mt-4">
                                <span class="font-bold text-[16px]">Total Bayar</span>
                                <span class="font-black text-[#10207a] text-[24px]">{{ formatCurrency(finalPrice) }}</span>
                            </div>
                        </div>

                        <button 
                            @click="processPayment" 
                            :disabled="processing || countdown.expired"
                            class="w-full bg-[#10207a] text-white py-4 rounded-[8px] font-semibold text-[16px] hover:bg-[#0c185e] transition-colors disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2"
                        >
                            <i v-if="processing" class="fas fa-spinner fa-spin"></i>
                            <span>{{ processing ? 'Memproses...' : 'Lanjut ke Pembayaran' }}</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
