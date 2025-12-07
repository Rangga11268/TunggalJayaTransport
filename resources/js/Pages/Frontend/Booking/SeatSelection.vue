<script setup>
import { Head, useForm, router } from "@inertiajs/vue3";
import FrontendLayout from "@/Layouts/FrontendLayout.vue";
import { ref, computed, onMounted } from "vue";
import axios from "axios";

defineOptions({ layout: FrontendLayout });

const props = defineProps({
    booking: Object,
    occupiedSeats: Array, // Array of seat numbers [1, 5, ...]
});

// Facilities Data
const facilities = [
    { name: "Karaoke", icon: "fas fa-microphone-alt" },
    { name: "USB Charger", icon: "fas fa-bolt" },
    { name: "TV Android", icon: "fas fa-tv" },
    { name: "AC", icon: "fas fa-snowflake" },
    { name: "Reclining", icon: "fas fa-couch" },
    { name: "Smoking", icon: "fas fa-smoking" },
];

const selectedSeats = ref([]);
const processing = ref(false);
const error = ref(null);
// Initialize selected seats from booking if available
onMounted(() => {
    if (props.booking.seat_numbers) {
        selectedSeats.value = props.booking.seat_numbers.split(",").map(Number);
    }
});

// Seat Map Configuration
const totalSeats = props.booking.schedule.bus.capacity;
const rows = Math.ceil(totalSeats / 5); // 2-3 Layout = 5 seats per row

const isSeatOccupied = (seatNum) => props.occupiedSeats.includes(seatNum);
const isSeatSelected = (seatNum) => selectedSeats.value.includes(seatNum);

const toggleSeat = (seatNum) => {
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
            // Optional: Replace the last selection or warn user
            // currently just strictly limiting to the number of booked seats
            alert(`Anda hanya memesan ${props.booking.number_of_seats} kursi.`);
        }
    }
};

const saveSeats = async () => {
    if (selectedSeats.value.length !== props.booking.number_of_seats) {
        alert(`Harap pilih ${props.booking.number_of_seats} kursi.`);
        return;
    }

    processing.value = true;
    try {
        const response = await axios.post(
            route("frontend.booking.select-seats"),
            {
                booking_id: props.booking.id,
                seat_numbers: selectedSeats.value,
            }
        );

        if (response.data.success) {
            // Success feedback?
        } else {
            alert(response.data.message);
        }
    } catch (e) {
        alert("Gagal menyimpan kursi.");
    } finally {
        processing.value = false;
    }
};

// Payment Logic
const paymentMethod = ref("");
const ewalletType = ref(""); // gopay, shopeepay, dana

const processPayment = async () => {
    // 1. Ensure seats are saved locally first or strictly validate
    if (selectedSeats.value.length !== props.booking.number_of_seats) {
        alert("Mohon pilih kursi terlebih dahulu.");
        return;
    }
    if (!paymentMethod.value) {
        alert("Pilih metode pembayaran.");
        return;
    }
    if (paymentMethod.value === "e_wallet" && !ewalletType.value) {
        alert("Pilih jenis E-Wallet.");
        return;
    }

    // Auto-save seats if not explicitly saved?
    // Current logic requires saving seats first in the backend logic, but let's try to do it in one flow or ensure they are saved.
    // The controller checks if seat_numbers is empty.

    // First, save seats explicitly to be safe
    // Note: In a real app, we might chain these or having the saveSeats set the state in DB
    await saveSeats();

    processing.value = true;
    try {
        // Prepare payload
        const payload = {
            booking_id: props.booking.id,
            payment_method:
                paymentMethod.value === "e_wallet"
                    ? ewalletType.value
                    : paymentMethod.value,
        };

        const response = await axios.post(
            route("frontend.booking.process-payment"),
            payload
        );

        if (response.data.success) {
            if (response.data.snap_token) {
                window.snap.pay(response.data.snap_token, {
                    onSuccess: function (result) {
                        router.visit(
                            route("frontend.booking.success", props.booking.id)
                        );
                    },
                    onPending: function (result) {
                        router.visit(
                            route("frontend.booking.success", props.booking.id)
                        );
                    },
                    onError: function (result) {
                        alert("Pembayaran gagal!");
                    },
                    onClose: function () {
                        // customer closed the popup without finishing the payment
                    },
                });
            } else if (response.data.redirect_url) {
                window.location.href = response.data.redirect_url;
            }
        } else {
            alert(response.data.message);
        }
    } catch (e) {
        console.error(e);
        alert(
            "Gagal memproses pembayaran: " +
                (e.response?.data?.message || e.message)
        );
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
    // 2 seats on left, aisle, 3 seats on right
    // Layout: [0, 1] | AISLE | [2, 3, 4]
    // Row 0: 1, 2 | 3, 4, 5
    // Row 1: 6, 7 | 8, 9, 10

    // colIdx: 0, 1 (Left) -- 2, 3, 4 (Right)
    const base = rowIdx * 5;
    if (colIdx < 2) return base + colIdx + 1; // 1-based
    const aisleOffset = colIdx >= 2 ? 0 : 0;
    // Wait, the previous logic was:
    // if colIdx < 2 (0,1) -> base + colIdx + 1
    // if colIdx >= 2 (2,3,4) -> base + colIdx + 1
    // It's the same formula. It just skips visual column index.
    return base + colIdx + 1;
};
// Helper to safely get route description
const routeDescription = computed(() => {
    const s = props.booking?.schedule;
    const r = s?.route;

    if (!r) return "Info Rute Tidak Tersedia";

    // Prioritize specific description if available
    if (
        r.description &&
        r.description.trim() !== "-" &&
        r.description.trim() !== ""
    )
        return r.description;

    // Fallback to Origin - Destination
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
</script>

<template>
    <Head title="Pilih Kursi & Pembayaran" />

    <div
        class="bg-gray-50 dark:bg-slate-900 min-h-screen py-12 px-4 sm:px-6 lg:px-8 font-sans transition-colors duration-300"
    >
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-10">
                <span
                    class="inline-block px-4 py-2 rounded-full bg-cyan-100 text-cyan-600 dark:bg-cyan-500/10 dark:text-cyan-400 text-xs font-bold tracking-[0.2em] mb-4 uppercase border border-cyan-200 dark:border-cyan-500/20 shadow-sm"
                >
                    TUNGGAL JAYA "{{ busName }}"
                </span>
                <h1
                    class="text-3xl md:text-4xl font-black text-gray-900 dark:text-white mb-2 tracking-tight"
                >
                    SEAT 2-3 + {{ busType }}
                </h1>
                <p
                    class="text-gray-500 dark:text-slate-400 font-medium tracking-wide text-sm uppercase"
                >
                    Melayani Rute:
                    {{ routeDescription }}
                </p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left: Seat Map -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Facilities Bar -->
                    <div
                        class="bg-white dark:bg-slate-800 rounded-2xl p-4 border border-gray-200 dark:border-cyan-500/30 shadow-lg dark:shadow-[0_0_20px_rgba(6,182,212,0.15)] relative overflow-hidden transition-colors duration-300"
                    >
                        <div
                            class="relative z-10 flex flex-wrap justify-center gap-6 md:gap-8"
                        >
                            <div
                                v-for="(facility, idx) in facilities"
                                :key="idx"
                                class="flex flex-col items-center group cursor-default"
                            >
                                <div
                                    class="w-10 h-10 rounded-full bg-gray-100 dark:bg-slate-700/50 flex items-center justify-center mb-2 group-hover:bg-cyan-500 group-hover:text-white transition-all duration-300 border border-gray-200 dark:border-slate-600 group-hover:border-cyan-400"
                                >
                                    <i
                                        :class="[
                                            facility.icon,
                                            'text-lg text-gray-500 dark:text-cyan-400 group-hover:text-white transition-colors',
                                        ]"
                                    ></i>
                                </div>
                                <span
                                    class="text-[0.65rem] font-bold text-gray-500 dark:text-slate-400 group-hover:text-cyan-600 dark:group-hover:text-cyan-300 uppercase tracking-wider transition-colors text-center max-w-[60px] leading-tight"
                                    >{{ facility.name }}</span
                                >
                            </div>
                        </div>
                    </div>

                    <!-- Bus Container -->
                    <div
                        class="relative rounded-[2.5rem] p-3 shadow-xl bg-white dark:bg-slate-800 border-2 border-gray-200 dark:border-cyan-500/50 transition-colors duration-300"
                    >
                        <!-- Bus Body -->
                        <div
                            class="bg-gray-100 dark:bg-gray-800/50 rounded-[2rem] p-6 relative min-h-[500px] overflow-hidden border border-gray-200 dark:border-gray-700"
                        >
                            <!-- Bus Floor Texture/Pattern -->
                            <div
                                class="absolute inset-0 opacity-30 dark:opacity-10 bg-[radial-gradient(#000_1px,transparent_1px)] dark:bg-[radial-gradient(#fff_1px,transparent_1px)] [background-size:16px_16px]"
                            ></div>

                            <!-- Front Area -->
                            <div
                                class="relative flex justify-between items-start mb-12 border-b-2 border-dashed border-gray-300 dark:border-gray-600 pb-4"
                            >
                                <!-- Door / Kernet (LEFT) -->
                                <div class="flex flex-col items-center">
                                    <div
                                        class="w-14 h-14 bg-gray-200 dark:bg-gray-700 rounded-xl flex items-center justify-center border-2 border-gray-300 dark:border-gray-600 border-dashed mb-2 opacity-70"
                                    >
                                        <i
                                            class="fas fa-door-open text-2xl text-gray-400 dark:text-gray-500"
                                        ></i>
                                    </div>
                                    <span
                                        class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest"
                                        >Pintu</span
                                    >
                                </div>

                                <!-- Driver (RIGHT - RHD) -->
                                <div class="flex flex-col items-center">
                                    <div
                                        class="w-16 h-16 bg-gray-200 dark:bg-gray-700/50 rounded-2xl flex items-center justify-center border-2 border-gray-300 dark:border-gray-600 mb-2 opacity-80"
                                    >
                                        <i
                                            class="fas fa-steering-wheel text-3xl text-gray-400 dark:text-gray-500"
                                        ></i>
                                    </div>
                                    <span
                                        class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest"
                                        >Sopir</span
                                    >
                                </div>
                            </div>

                            <!-- Seat Grid -->
                            <div
                                class="relative z-10 px-2 sm:px-8 overflow-x-auto"
                            >
                                <div class="min-w-[300px]">
                                    <div
                                        v-for="r in rows"
                                        :key="r"
                                        class="flex items-center justify-center mb-3"
                                    >
                                        <!-- Left Column (2 Seats) -->
                                        <div class="flex gap-2">
                                            <template
                                                v-for="c in 2"
                                                :key="`L-${r}-${c}`"
                                            >
                                                <div
                                                    class="relative group"
                                                    v-if="
                                                        getSeatNumber(
                                                            r - 1,
                                                            c - 1
                                                        ) <= totalSeats
                                                    "
                                                >
                                                    <button
                                                        @click="
                                                            toggleSeat(
                                                                getSeatNumber(
                                                                    r - 1,
                                                                    c - 1
                                                                )
                                                            )
                                                        "
                                                        :disabled="
                                                            isSeatOccupied(
                                                                getSeatNumber(
                                                                    r - 1,
                                                                    c - 1
                                                                )
                                                            )
                                                        "
                                                        class="relative w-12 md:w-14 transition-all duration-300 transform hover:scale-105 active:scale-95 focus:outline-none"
                                                    >
                                                        <!-- Seat Image -->
                                                        <img
                                                            src="/img/car-seat.png"
                                                            class="w-full h-auto drop-shadow-md transition-all duration-300 dark:brightness-90"
                                                            :class="[
                                                                isSeatOccupied(
                                                                    getSeatNumber(
                                                                        r - 1,
                                                                        c - 1
                                                                    )
                                                                )
                                                                    ? 'grayscale opacity-50 brightness-50'
                                                                    : '',
                                                                isSeatSelected(
                                                                    getSeatNumber(
                                                                        r - 1,
                                                                        c - 1
                                                                    )
                                                                )
                                                                    ? 'sepia-[.5] hue-rotate-[320deg] saturate-[3] drop-shadow-[0_0_8px_rgba(255,0,0,0.6)]'
                                                                    : 'hover:drop-shadow-[0_0_5px_rgba(0,0,0,0.3)]',
                                                            ]"
                                                            alt="Seat"
                                                        />

                                                        <!-- Seat Number Badge -->
                                                        <div
                                                            class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-2/3 text-[10px] md:text-xs font-bold text-gray-700 w-5 h-5 flex items-center justify-center"
                                                            :class="
                                                                isSeatSelected(
                                                                    getSeatNumber(
                                                                        r - 1,
                                                                        c - 1
                                                                    )
                                                                )
                                                                    ? '!text-white'
                                                                    : ''
                                                            "
                                                        >
                                                            {{
                                                                getSeatNumber(
                                                                    r - 1,
                                                                    c - 1
                                                                )
                                                            }}
                                                        </div>

                                                        <!-- Selection Indicator (Checkmark) -->
                                                        <div
                                                            v-if="
                                                                isSeatSelected(
                                                                    getSeatNumber(
                                                                        r - 1,
                                                                        c - 1
                                                                    )
                                                                )
                                                            "
                                                            class="absolute -top-1 -right-1 w-5 h-5 bg-brand-red rounded-full flex items-center justify-center text-white text-[10px] shadow-sm animate-bounce-short"
                                                        >
                                                            <i
                                                                class="fas fa-check"
                                                            ></i>
                                                        </div>

                                                        <!-- Occupied Indicator (Cross) -->
                                                        <div
                                                            v-if="
                                                                isSeatOccupied(
                                                                    getSeatNumber(
                                                                        r - 1,
                                                                        c - 1
                                                                    )
                                                                )
                                                            "
                                                            class="absolute inset-0 flex items-center justify-center"
                                                        >
                                                            <i
                                                                class="fas fa-times text-red-600 text-2xl opacity-80"
                                                            ></i>
                                                        </div>
                                                    </button>
                                                </div>
                                                <div
                                                    v-else
                                                    class="w-12 md:w-14"
                                                ></div>
                                                <!-- Empty Spacer -->
                                            </template>
                                        </div>

                                        <!-- Aisle -->
                                        <div
                                            class="w-10 md:w-14 flex justify-center items-center"
                                        >
                                            <span
                                                class="text-[10px] text-gray-400 dark:text-gray-500 font-mono rotate-90 opacity-0 md:opacity-100"
                                                >{{ r }}</span
                                            >
                                        </div>

                                        <!-- Right Column (3 Seats) -->
                                        <div class="flex gap-2">
                                            <template
                                                v-for="c in 3"
                                                :key="`R-${r}-${c}`"
                                            >
                                                <div
                                                    class="relative group"
                                                    v-if="
                                                        getSeatNumber(
                                                            r - 1,
                                                            c + 1
                                                        ) <= totalSeats
                                                    "
                                                >
                                                    <button
                                                        @click="
                                                            toggleSeat(
                                                                getSeatNumber(
                                                                    r - 1,
                                                                    c + 1
                                                                )
                                                            )
                                                        "
                                                        :disabled="
                                                            isSeatOccupied(
                                                                getSeatNumber(
                                                                    r - 1,
                                                                    c + 1
                                                                )
                                                            )
                                                        "
                                                        class="relative w-12 md:w-14 transition-all duration-300 transform hover:scale-105 active:scale-95 focus:outline-none"
                                                    >
                                                        <!-- Seat Image -->
                                                        <img
                                                            src="/img/car-seat.png"
                                                            class="w-full h-auto drop-shadow-md transition-all duration-300 dark:brightness-90"
                                                            :class="[
                                                                isSeatOccupied(
                                                                    getSeatNumber(
                                                                        r - 1,
                                                                        c + 1
                                                                    )
                                                                )
                                                                    ? 'grayscale opacity-50 brightness-50'
                                                                    : '',
                                                                isSeatSelected(
                                                                    getSeatNumber(
                                                                        r - 1,
                                                                        c + 1
                                                                    )
                                                                )
                                                                    ? 'sepia-[.5] hue-rotate-[320deg] saturate-[3] drop-shadow-[0_0_8px_rgba(255,0,0,0.6)]'
                                                                    : 'hover:drop-shadow-[0_0_5px_rgba(0,0,0,0.3)]',
                                                            ]"
                                                            alt="Seat"
                                                        />

                                                        <!-- Seat Number Badge -->
                                                        <div
                                                            class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-2/3 text-[10px] md:text-xs font-bold text-gray-700 w-5 h-5 flex items-center justify-center"
                                                            :class="
                                                                isSeatSelected(
                                                                    getSeatNumber(
                                                                        r - 1,
                                                                        c + 1
                                                                    )
                                                                )
                                                                    ? '!text-white'
                                                                    : ''
                                                            "
                                                        >
                                                            {{
                                                                getSeatNumber(
                                                                    r - 1,
                                                                    c + 1
                                                                )
                                                            }}
                                                        </div>

                                                        <!-- Selection Indicator (Checkmark) -->
                                                        <div
                                                            v-if="
                                                                isSeatSelected(
                                                                    getSeatNumber(
                                                                        r - 1,
                                                                        c + 1
                                                                    )
                                                                )
                                                            "
                                                            class="absolute -top-1 -right-1 w-5 h-5 bg-brand-red rounded-full flex items-center justify-center text-white text-[10px] shadow-sm animate-bounce-short"
                                                        >
                                                            <i
                                                                class="fas fa-check"
                                                            ></i>
                                                        </div>

                                                        <!-- Occupied Indicator (Cross) -->
                                                        <div
                                                            v-if="
                                                                isSeatOccupied(
                                                                    getSeatNumber(
                                                                        r - 1,
                                                                        c + 1
                                                                    )
                                                                )
                                                            "
                                                            class="absolute inset-0 flex items-center justify-center"
                                                        >
                                                            <i
                                                                class="fas fa-times text-red-600 text-2xl opacity-80"
                                                            ></i>
                                                        </div>
                                                    </button>
                                                </div>
                                                <div
                                                    v-else
                                                    class="w-12 md:w-14"
                                                ></div>
                                                <!-- Empty Spacer -->
                                            </template>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Rear Decoration -->
                            <div
                                class="absolute bottom-0 left-0 w-full h-8 bg-gradient-to-t from-gray-300 dark:from-gray-900 to-transparent"
                            ></div>
                        </div>
                    </div>

                    <!-- Legend -->
                    <div
                        class="flex justify-center gap-4 md:gap-8 flex-wrap pt-4"
                    >
                        <div
                            class="flex items-center gap-3 bg-white dark:bg-slate-800/50 px-4 py-2 rounded-lg border border-gray-200 dark:border-slate-700 shadow-sm"
                        >
                            <img
                                src="/img/car-seat.png"
                                class="w-8 h-auto grayscale opacity-50"
                            />
                            <span
                                class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider"
                                >Terisi</span
                            >
                        </div>
                        <div
                            class="flex items-center gap-3 bg-white dark:bg-slate-800/50 px-4 py-2 rounded-lg border border-gray-200 dark:border-slate-700 shadow-sm"
                        >
                            <img src="/img/car-seat.png" class="w-8 h-auto" />
                            <span
                                class="text-xs font-bold text-gray-900 dark:text-gray-200 uppercase tracking-wider"
                                >Tersedia</span
                            >
                        </div>
                        <div
                            class="flex items-center gap-3 bg-white dark:bg-slate-800/50 px-4 py-2 rounded-lg border border-gray-200 dark:border-slate-700 shadow-sm"
                        >
                            <img
                                src="/img/car-seat.png"
                                class="w-8 h-auto sepia-[.5] hue-rotate-[320deg] saturate-[3]"
                            />
                            <span
                                class="text-xs font-bold text-brand-red uppercase tracking-wider"
                                >Pilihanmu</span
                            >
                        </div>
                    </div>
                </div>

                <!-- Right: Payment & Summary -->
                <div class="lg:col-span-1 space-y-6">
                    <!-- Card Utils -->
                    <div
                        class="bg-white dark:bg-slate-800/80 dark:backdrop-blur-md rounded-3xl p-6 shadow-xl border border-gray-200 dark:border-slate-700 sticky top-24 transition-colors duration-300"
                    >
                        <h3
                            class="font-black text-xl text-gray-900 dark:text-white mb-6 flex items-center gap-3"
                        >
                            <i
                                class="fas fa-clipboard-list text-cyan-600 dark:text-cyan-400"
                            ></i>
                            Detail Pemesanan
                        </h3>

                        <div class="space-y-4 mb-6">
                            <div
                                class="bg-gray-50 dark:bg-slate-700/50 p-4 rounded-xl border border-gray-200 dark:border-slate-600/50"
                            >
                                <span
                                    class="text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider block mb-1"
                                    >Penumpang</span
                                >
                                <span
                                    class="font-bold text-gray-900 dark:text-white text-lg"
                                    >{{ booking.passenger_name }}</span
                                >
                            </div>

                            <div
                                class="bg-gray-50 dark:bg-slate-700/50 p-4 rounded-xl border border-gray-200 dark:border-slate-600/50"
                            >
                                <span
                                    class="text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider block mb-1"
                                    >Kursi Dipilih</span
                                >
                                <div
                                    v-if="selectedSeats.length > 0"
                                    class="flex flex-wrap gap-2"
                                >
                                    <span
                                        v-for="seat in selectedSeats"
                                        :key="seat"
                                        class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-brand-red text-white font-bold text-sm shadow-lg shadow-red-900/50"
                                    >
                                        {{ seat }}
                                    </span>
                                </div>
                                <span
                                    v-else
                                    class="text-gray-400 dark:text-slate-500 italic text-sm"
                                    >Belum ada kursi dipilih</span
                                >
                            </div>

                            <div
                                class="bg-gradient-to-r from-cyan-50 to-blue-50 dark:from-cyan-900/40 dark:to-blue-900/40 p-4 rounded-xl border border-cyan-200 dark:border-cyan-500/20"
                            >
                                <span
                                    class="text-xs font-bold text-cyan-600 dark:text-cyan-400 uppercase tracking-wider block mb-1"
                                    >Total Tagihan</span
                                >
                                <span
                                    class="font-black text-3xl text-gray-900 dark:text-white tracking-tight"
                                    >{{
                                        formatCurrency(booking.total_price)
                                    }}</span
                                >
                            </div>
                        </div>

                        <!-- Payment Methods -->
                        <div class="space-y-4">
                            <h4
                                class="text-sm font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider mb-2"
                            >
                                Metode Pembayaran
                            </h4>

                            <!-- Transfer -->
                            <label
                                class="group relative flex items-center p-4 border border-gray-200 dark:border-slate-600 rounded-xl cursor-pointer bg-gray-50 dark:bg-slate-700/30 hover:bg-gray-100 dark:hover:bg-slate-700 hover:border-cyan-500 dark:hover:border-cyan-500 transition-all duration-300"
                                :class="{
                                    'ring-2 ring-cyan-500 bg-white dark:bg-slate-700 !border-cyan-500':
                                        paymentMethod === 'bank_transfer',
                                }"
                            >
                                <input
                                    type="radio"
                                    value="bank_transfer"
                                    v-model="paymentMethod"
                                    class="peer sr-only"
                                />
                                <div
                                    class="w-10 h-10 rounded-full bg-gray-200 dark:bg-slate-600 flex items-center justify-center mr-4 group-hover:bg-cyan-100 dark:group-hover:bg-cyan-500/20 group-hover:text-cyan-600 dark:group-hover:text-cyan-400 transition-colors"
                                >
                                    <i class="fas fa-university text-lg"></i>
                                </div>
                                <span
                                    class="font-bold text-gray-700 dark:text-gray-200 group-hover:text-gray-900 dark:group-hover:text-white"
                                    >Transfer Bank</span
                                >
                                <div
                                    class="absolute right-4 w-4 h-4 rounded-full border-2 border-gray-400 dark:border-slate-500 peer-checked:border-cyan-500 peer-checked:bg-cyan-500 transition-all"
                                ></div>
                            </label>

                            <!-- E-Wallet -->
                            <div
                                class="border border-gray-200 dark:border-slate-600 rounded-xl overflow-hidden bg-gray-50 dark:bg-slate-700/30"
                                :class="{
                                    'ring-2 ring-cyan-500 border-cyan-500':
                                        paymentMethod === 'e_wallet',
                                }"
                            >
                                <label
                                    class="flex items-center p-4 cursor-pointer hover:bg-gray-100 dark:hover:bg-slate-700 transition-colors"
                                >
                                    <input
                                        type="radio"
                                        value="e_wallet"
                                        v-model="paymentMethod"
                                        class="peer sr-only"
                                    />
                                    <div
                                        class="w-10 h-10 rounded-full bg-gray-200 dark:bg-slate-600 flex items-center justify-center mr-4"
                                    >
                                        <i class="fas fa-wallet text-lg"></i>
                                    </div>
                                    <span
                                        class="font-bold text-gray-700 dark:text-gray-200"
                                        >E-Wallet</span
                                    >
                                    <div
                                        class="ml-auto w-4 h-4 rounded-full border-2 border-gray-400 dark:border-slate-500 peer-checked:border-cyan-500 peer-checked:bg-cyan-500 transition-all"
                                    ></div>
                                </label>

                                <div
                                    v-if="paymentMethod === 'e_wallet'"
                                    class="bg-gray-100 dark:bg-slate-800/80 p-4 space-y-2 border-t border-gray-200 dark:border-slate-600 animate-slide-down"
                                >
                                    <label
                                        class="flex items-center justify-between p-2 rounded-lg hover:bg-gray-200 dark:hover:bg-slate-700 cursor-pointer group"
                                    >
                                        <div class="flex items-center">
                                            <input
                                                type="radio"
                                                value="gopay"
                                                v-model="ewalletType"
                                                class="text-cyan-500 focus:ring-cyan-500 bg-gray-300 dark:bg-slate-600 border-gray-400 dark:border-slate-500"
                                            />
                                            <span
                                                class="ml-3 text-sm font-medium text-gray-700 dark:text-slate-300 group-hover:text-black dark:group-hover:text-white"
                                                >GoPay</span
                                            >
                                        </div>
                                        <img
                                            src="/img/payment-logos/gopay.png"
                                            class="h-6 w-auto opacity-70 group-hover:opacity-100"
                                            alt="GoPay"
                                            onerror="this.style.display='none'"
                                        />
                                    </label>
                                    <label
                                        class="flex items-center justify-between p-2 rounded-lg hover:bg-gray-200 dark:hover:bg-slate-700 cursor-pointer group"
                                    >
                                        <div class="flex items-center">
                                            <input
                                                type="radio"
                                                value="shopeepay"
                                                v-model="ewalletType"
                                                class="text-cyan-500 focus:ring-cyan-500 bg-gray-300 dark:bg-slate-600 border-gray-400 dark:border-slate-500"
                                            />
                                            <span
                                                class="ml-3 text-sm font-medium text-gray-700 dark:text-slate-300 group-hover:text-black dark:group-hover:text-white"
                                                >ShopeePay</span
                                            >
                                        </div>
                                    </label>
                                    <label
                                        class="flex items-center justify-between p-2 rounded-lg hover:bg-gray-200 dark:hover:bg-slate-700 cursor-pointer group"
                                    >
                                        <div class="flex items-center">
                                            <input
                                                type="radio"
                                                value="dana"
                                                v-model="ewalletType"
                                                class="text-cyan-500 focus:ring-cyan-500 bg-gray-300 dark:bg-slate-600 border-gray-400 dark:border-slate-500"
                                            />
                                            <span
                                                class="ml-3 text-sm font-medium text-gray-700 dark:text-slate-300 group-hover:text-black dark:group-hover:text-white"
                                                >DANA</span
                                            >
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <button
                            @click="processPayment"
                            :disabled="
                                processing ||
                                selectedSeats.length !== booking.number_of_seats
                            "
                            class="w-full mt-8 py-4 bg-gradient-to-r from-brand-red to-red-600 hover:from-red-600 hover:to-red-700 text-white rounded-xl font-black text-lg shadow-lg shadow-brand-red/30 transform hover:-translate-y-1 transition-all duration-300 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none flex items-center justify-center gap-3"
                        >
                            <span v-if="!processing">BAYAR SEKARANG</span>
                            <span v-else class="flex items-center gap-2">
                                <i class="fas fa-circle-notch fa-spin"></i>
                                MEMPROSES...
                            </span>
                            <i
                                v-if="!processing"
                                class="fas fa-arrow-right"
                            ></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
