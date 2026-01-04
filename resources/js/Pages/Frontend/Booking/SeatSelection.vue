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
            alert(`Anda hanya memesan ${props.booking.number_of_seats} kursi.`);
        }
    }
};

// Helper to save seats returns success boolean
const saveSeats = async () => {
    if (selectedSeats.value.length !== props.booking.number_of_seats) {
        alert(`Harap pilih ${props.booking.number_of_seats} kursi.`);
        return false;
    }

    processing.value = true;
    try {
        const response = await axios.post(
            route("frontend.booking.select-seats"),
            {
                booking_id: props.booking.id, // Current booking ID
                seat_numbers: selectedSeats.value, // Array [1, 5, etc]
            }
        );

        if (response.data.success) {
            return true;
        } else {
            alert(response.data.message || "Gagal menyimpan kursi.");
            return false;
        }
    } catch (e) {
        console.error("Save Seats Error:", e);
        alert(
            e.response?.data?.message ||
                "Gagal menghubungi server untuk simpan kursi."
        );
        return false;
    } finally {
        processing.value = false;
    }
};

// Payment Logic
const paymentMethod = ref("");
const ewalletType = ref(""); // gopay, shopeepay, dana

const processPayment = async () => {
    // 1. Validation
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

    // 2. Save Seats First
    // We must ensure seats are saved in the DB before payment because invalid/unsaved seats
    // will cause the backend payment controller to reject the request.
    const saved = await saveSeats();
    if (!saved) return; // Stop if saving failed

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

    <!-- Header (Consistent with Booking Index) -->
    <div
        class="pt-32 pb-12 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto text-center relative z-10"
    >
        <span
            class="inline-block px-4 py-2 rounded-full bg-rose-600 text-white text-[10px] font-bold tracking-[0.2em] mb-6 animate-fade-in uppercase font-unbounded shadow-lg shadow-rose-600/20"
        >
            TUNGGAL JAYA "{{ busName }}"
        </span>
        <h1
            class="font-unbounded font-black text-3xl md:text-5xl text-gray-900 dark:text-white mb-4 animate-fade-in-up leading-tight uppercase"
        >
            Pilih
            <span class="text-rose-600">Kursi & Bayar</span>
        </h1>
        <p
            class="text-lg text-gray-500 dark:text-gray-400 max-w-2xl mx-auto animate-fade-in-up stagger-1 font-manrope font-medium"
        >
            Konfigurasi: Seat 2-3 | {{ busType }} | {{ routeDescription }}
        </p>
    </div>

    <div
        class="bg-white dark:bg-[#050505] min-h-screen pb-24 px-4 sm:px-6 lg:px-8 relative z-20"
    >
        <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left: Seat Map -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Facilities Bar -->
                <div
                    class="bg-white dark:bg-[#111] rounded-3xl p-6 border border-gray-100 dark:border-white/5 shadow-sm overflow-hidden"
                >
                    <h3
                        class="text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-4 font-manrope"
                    >
                        Fasilitas Armada
                    </h3>
                    <div
                        class="flex flex-wrap gap-4 justify-center md:justify-start"
                    >
                        <div
                            v-for="(facility, idx) in facilities"
                            :key="idx"
                            class="flex items-center gap-3 px-4 py-2 rounded-xl bg-gray-50 dark:bg-white/5 border border-gray-100 dark:border-white/5 group hover:border-rose-200 dark:hover:border-rose-900/50 transition-all"
                        >
                            <div
                                class="w-8 h-8 rounded-full bg-white dark:bg-white/10 flex items-center justify-center text-gray-400 group-hover:text-rose-600 transition-colors"
                            >
                                <i :class="[facility.icon, 'text-xs']"></i>
                            </div>
                            <span
                                class="text-[10px] font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wide font-manrope group-hover:text-rose-600 transition-colors"
                            >
                                {{ facility.name }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Bus Container -->
                <div
                    class="bg-white dark:bg-[#111] rounded-[3rem] p-4 md:p-6 shadow-xl shadow-gray-200 dark:shadow-none border border-gray-100 dark:border-white/5"
                >
                    <!-- Bus Body -->
                    <div
                        class="bg-gray-50 dark:bg-[#080808] rounded-[2.5rem] p-6 relative min-h-[500px] overflow-hidden border border-gray-200 dark:border-white/5"
                    >
                        <!-- Front Area -->
                        <div
                            class="relative flex justify-between items-start mb-12 border-b-2 border-dashed border-gray-200 dark:border-white/10 pb-6"
                        >
                            <!-- Door -->
                            <div class="flex flex-col items-center opacity-60">
                                <div
                                    class="w-12 h-12 rounded-xl border-2 border-dashed border-gray-300 dark:border-white/20 flex items-center justify-center mb-2"
                                >
                                    <i
                                        class="fas fa-door-open text-gray-400"
                                    ></i>
                                </div>
                                <span
                                    class="text-[10px] font-bold text-gray-400 uppercase tracking-widest font-manrope"
                                    >Pintu</span
                                >
                            </div>

                            <!-- Driver -->
                            <div class="flex flex-col items-center">
                                <div
                                    class="w-16 h-16 bg-gray-200 dark:bg-white/10 rounded-2xl flex items-center justify-center text-gray-400 dark:text-white/50 mb-2 shadow-inner"
                                >
                                    <i
                                        class="fas fa-steering-wheel text-2xl"
                                    ></i>
                                </div>
                                <span
                                    class="text-[10px] font-bold text-gray-400 uppercase tracking-widest font-manrope"
                                    >Sopir</span
                                >
                            </div>
                        </div>

                        <!-- Seat Grid -->
                        <div
                            class="relative z-10 px-0 md:px-8 overflow-x-auto pb-12"
                        >
                            <div
                                class="min-w-[300px] flex flex-col items-center"
                            >
                                <div
                                    v-for="r in rows"
                                    :key="r"
                                    class="flex items-center gap-4 md:gap-8 mb-4"
                                >
                                    <!-- Left Column (2 Seats) -->
                                    <div class="flex gap-3">
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
                                                    class="relative w-12 md:w-14 transition-all duration-300 focus:outline-none"
                                                >
                                                    <!-- Seat Visual -->
                                                    <div
                                                        class="w-full aspect-square rounded-xl flex items-center justify-center relative transition-all duration-300 shadow-sm overflow-hidden p-1"
                                                        :class="[
                                                            isSeatSelected(
                                                                getSeatNumber(
                                                                    r - 1,
                                                                    c - 1
                                                                )
                                                            )
                                                                ? 'bg-rose-50 dark:bg-rose-900/10 shadow-lg shadow-rose-600/20 ring-2 ring-rose-600 -translate-y-1'
                                                                : isSeatOccupied(
                                                                      getSeatNumber(
                                                                          r - 1,
                                                                          c - 1
                                                                      )
                                                                  )
                                                                ? 'bg-gray-200 dark:bg-white/5 text-gray-400 cursor-not-allowed opacity-50'
                                                                : 'bg-white dark:bg-[#1a1a1a] text-gray-600 dark:text-gray-400 hover:bg-rose-50 dark:hover:bg-rose-900/10 hover:text-rose-600 hover:shadow-md border border-gray-100 dark:border-white/5',
                                                        ]"
                                                    >
                                                        <img
                                                            src="/img/car-seat.png"
                                                            class="w-full h-auto object-contain transition-all duration-300"
                                                            :class="[
                                                                isSeatSelected(
                                                                    getSeatNumber(
                                                                        r - 1,
                                                                        c - 1
                                                                    )
                                                                )
                                                                    ? 'sepia-[1] hue-rotate-[300deg] saturate-[2.5]'
                                                                    : isSeatOccupied(
                                                                          getSeatNumber(
                                                                              r -
                                                                                  1,
                                                                              c -
                                                                                  1
                                                                          )
                                                                      )
                                                                    ? 'grayscale'
                                                                    : 'dark:brightness-90 hover:brightness-110',
                                                            ]"
                                                            alt="Seat"
                                                        />

                                                        <!-- Number Badge -->
                                                        <span
                                                            class="absolute -top-1 -right-1 text-[9px] font-bold px-1.5 py-0.5 rounded-md shadow-sm border font-manrope z-10"
                                                            :class="[
                                                                isSeatSelected(
                                                                    getSeatNumber(
                                                                        r - 1,
                                                                        c - 1
                                                                    )
                                                                )
                                                                    ? 'bg-rose-600 text-white border-rose-600'
                                                                    : 'bg-gray-100 dark:bg-white/10 text-gray-500 border-gray-200 dark:border-white/10',
                                                            ]"
                                                        >
                                                            {{
                                                                getSeatNumber(
                                                                    r - 1,
                                                                    c - 1
                                                                )
                                                            }}
                                                        </span>
                                                    </div>
                                                </button>
                                            </div>
                                            <div
                                                v-else
                                                class="w-12 md:w-14"
                                            ></div>
                                        </template>
                                    </div>

                                    <!-- Aisle -->
                                    <div
                                        class="w-8 flex justify-center items-center"
                                    >
                                        <span
                                            class="text-[10px] font-bold text-gray-300 dark:text-white/10 font-manrope"
                                            >{{ r }}</span
                                        >
                                    </div>

                                    <!-- Right Column (3 Seats) -->
                                    <div class="flex gap-3">
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
                                                    class="relative w-12 md:w-14 transition-all duration-300 focus:outline-none"
                                                >
                                                    <!-- Seat Visual -->
                                                    <div
                                                        class="w-full aspect-square rounded-xl flex items-center justify-center relative transition-all duration-300 shadow-sm overflow-hidden p-1"
                                                        :class="[
                                                            isSeatSelected(
                                                                getSeatNumber(
                                                                    r - 1,
                                                                    c + 1
                                                                )
                                                            )
                                                                ? 'bg-rose-50 dark:bg-rose-900/10 shadow-lg shadow-rose-600/20 ring-2 ring-rose-600 -translate-y-1'
                                                                : isSeatOccupied(
                                                                      getSeatNumber(
                                                                          r - 1,
                                                                          c + 1
                                                                      )
                                                                  )
                                                                ? 'bg-gray-200 dark:bg-white/5 text-gray-400 cursor-not-allowed opacity-50'
                                                                : 'bg-white dark:bg-[#1a1a1a] text-gray-600 dark:text-gray-400 hover:bg-rose-50 dark:hover:bg-rose-900/10 hover:text-rose-600 hover:shadow-md border border-gray-100 dark:border-white/5',
                                                        ]"
                                                    >
                                                        <img
                                                            src="/img/car-seat.png"
                                                            class="w-full h-auto object-contain transition-all duration-300"
                                                            :class="[
                                                                isSeatSelected(
                                                                    getSeatNumber(
                                                                        r - 1,
                                                                        c + 1
                                                                    )
                                                                )
                                                                    ? 'sepia-[1] hue-rotate-[300deg] saturate-[2.5]'
                                                                    : isSeatOccupied(
                                                                          getSeatNumber(
                                                                              r -
                                                                                  1,
                                                                              c +
                                                                                  1
                                                                          )
                                                                      )
                                                                    ? 'grayscale'
                                                                    : 'dark:brightness-90 hover:brightness-110',
                                                            ]"
                                                            alt="Seat"
                                                        />

                                                        <!-- Number Badge -->
                                                        <span
                                                            class="absolute -top-1 -right-1 text-[9px] font-bold px-1.5 py-0.5 rounded-md shadow-sm border font-manrope z-10"
                                                            :class="[
                                                                isSeatSelected(
                                                                    getSeatNumber(
                                                                        r - 1,
                                                                        c + 1
                                                                    )
                                                                )
                                                                    ? 'bg-rose-600 text-white border-rose-600'
                                                                    : 'bg-gray-100 dark:bg-white/10 text-gray-500 border-gray-200 dark:border-white/10',
                                                            ]"
                                                        >
                                                            {{
                                                                getSeatNumber(
                                                                    r - 1,
                                                                    c + 1
                                                                )
                                                            }}
                                                        </span>
                                                    </div>
                                                </button>
                                            </div>
                                            <div
                                                v-else
                                                class="w-12 md:w-14"
                                            ></div>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Rear Decoration -->
                        <div
                            class="absolute bottom-0 left-0 w-full h-12 bg-gradient-to-t from-gray-200 dark:from-black to-transparent opacity-50"
                        ></div>
                    </div>

                    <!-- Legend -->
                    <div
                        class="flex justify-center gap-6 flex-wrap mt-8 pt-8 border-t border-gray-100 dark:border-white/5"
                    >
                        <div class="flex items-center gap-3">
                            <div
                                class="w-8 h-8 rounded-lg bg-gray-200 dark:bg-white/5 border border-gray-300 dark:border-white/10 flex items-center justify-center p-1"
                            >
                                <img
                                    src="/img/car-seat.png"
                                    class="w-full h-full object-contain grayscale opacity-50"
                                />
                            </div>
                            <span
                                class="text-[10px] font-bold text-gray-500 uppercase tracking-wider font-manrope"
                                >Terisi</span
                            >
                        </div>
                        <div class="flex items-center gap-3">
                            <div
                                class="w-8 h-8 rounded-lg bg-white dark:bg-[#1a1a1a] border border-gray-200 dark:border-white/10 flex items-center justify-center p-1"
                            >
                                <img
                                    src="/img/car-seat.png"
                                    class="w-full h-full object-contain dark:brightness-90"
                                />
                            </div>
                            <span
                                class="text-[10px] font-bold text-gray-500 uppercase tracking-wider font-manrope"
                                >Tersedia</span
                            >
                        </div>
                        <div class="flex items-center gap-3">
                            <div
                                class="w-8 h-8 rounded-lg bg-rose-50 dark:bg-rose-900/10 border-2 border-rose-600 flex items-center justify-center p-1"
                            >
                                <img
                                    src="/img/car-seat.png"
                                    class="w-full h-full object-contain sepia-[1] hue-rotate-[300deg] saturate-[2.5]"
                                />
                            </div>
                            <span
                                class="text-[10px] font-bold text-rose-600 uppercase tracking-wider font-manrope"
                                >Pilihanmu</span
                            >
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right: Payment & Summary -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Summary Card -->
                <div
                    class="bg-white dark:bg-[#111] rounded-3xl p-6 md:p-8 shadow-xl shadow-gray-100 dark:shadow-none border border-gray-100 dark:border-white/5 sticky top-24"
                >
                    <h3
                        class="font-unbounded font-bold text-lg text-gray-900 dark:text-white mb-6 flex items-center gap-3 uppercase"
                    >
                        <i class="fas fa-clipboard-list text-rose-600"></i>
                        Detail Bayar
                    </h3>

                    <!-- Info Groups -->
                    <div class="space-y-6 mb-8">
                        <div>
                            <span
                                class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block mb-2 font-manrope"
                                >Penumpang</span
                            >
                            <div
                                class="p-3 bg-gray-50 dark:bg-white/5 rounded-xl border border-gray-100 dark:border-white/5"
                            >
                                <span
                                    class="font-bold text-gray-900 dark:text-white font-manrope text-sm"
                                    >{{ booking.passenger_name }}</span
                                >
                            </div>
                        </div>

                        <div>
                            <span
                                class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block mb-2 font-manrope"
                                >Kursi Dipilih</span
                            >
                            <div class="flex flex-wrap gap-2">
                                <span
                                    v-if="selectedSeats.length === 0"
                                    class="text-sm text-gray-400 italic px-2 font-manrope"
                                    >Belum ada kursi dipilih</span
                                >
                                <span
                                    v-for="seat in selectedSeats"
                                    :key="seat"
                                    class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-rose-600 text-white font-black text-sm shadow-lg shadow-rose-600/20 font-unbounded transition-all hover:scale-110"
                                >
                                    {{ seat }}
                                </span>
                            </div>
                        </div>

                        <div
                            class="pt-6 border-t border-dashed border-gray-200 dark:border-white/10"
                        >
                            <span
                                class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block mb-1 font-manrope"
                                >Total Tagihan</span
                            >
                            <span
                                class="font-black text-3xl text-rose-600 tracking-tight font-unbounded"
                                >{{ formatCurrency(booking.total_price) }}</span
                            >
                        </div>
                    </div>

                    <!-- Payment Methods -->
                    <div class="space-y-4">
                        <h4
                            class="text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-3 font-manrope"
                        >
                            Metode Pembayaran
                        </h4>

                        <!-- Transfer -->
                        <label
                            class="group relative flex items-center p-4 border-2 rounded-2xl cursor-pointer transition-all duration-300"
                            :class="[
                                paymentMethod === 'bank_transfer'
                                    ? 'border-rose-600 bg-rose-50 dark:bg-rose-900/10'
                                    : 'border-gray-100 dark:border-white/10 bg-white dark:bg-[#111] hover:border-rose-300 dark:hover:border-rose-800',
                            ]"
                        >
                            <input
                                type="radio"
                                value="bank_transfer"
                                v-model="paymentMethod"
                                class="peer sr-only"
                            />
                            <div
                                class="w-10 h-10 rounded-full flex items-center justify-center mr-4 transition-colors"
                                :class="[
                                    paymentMethod === 'bank_transfer'
                                        ? 'bg-rose-200 dark:bg-rose-800 text-rose-700 dark:text-rose-100'
                                        : 'bg-gray-100 dark:bg-white/5 text-gray-400',
                                ]"
                            >
                                <i class="fas fa-university text-lg"></i>
                            </div>
                            <span
                                class="font-bold text-sm font-manrope"
                                :class="[
                                    paymentMethod === 'bank_transfer'
                                        ? 'text-rose-900 dark:text-rose-100'
                                        : 'text-gray-700 dark:text-gray-300',
                                ]"
                                >Transfer Bank</span
                            >
                            <div
                                v-if="paymentMethod === 'bank_transfer'"
                                class="absolute right-4 w-5 h-5 rounded-full bg-rose-600 flex items-center justify-center text-white text-xs shadow-md"
                            >
                                <i class="fas fa-check"></i>
                            </div>
                        </label>

                        <!-- E-Wallet -->
                        <div
                            class="border-2 rounded-2xl overflow-hidden transition-all duration-300"
                            :class="[
                                paymentMethod === 'e_wallet'
                                    ? 'border-rose-600 bg-rose-50 dark:bg-rose-900/10'
                                    : 'border-gray-100 dark:border-white/10 bg-white dark:bg-[#111]',
                            ]"
                        >
                            <label
                                class="flex items-center p-4 cursor-pointer hover:bg-rose-50 dark:hover:bg-rose-900/5 transition-colors"
                            >
                                <input
                                    type="radio"
                                    value="e_wallet"
                                    v-model="paymentMethod"
                                    class="peer sr-only"
                                />
                                <div
                                    class="w-10 h-10 rounded-full flex items-center justify-center mr-4 transition-colors"
                                    :class="[
                                        paymentMethod === 'e_wallet'
                                            ? 'bg-rose-200 dark:bg-rose-800 text-rose-700 dark:text-rose-100'
                                            : 'bg-gray-100 dark:bg-white/5 text-gray-400',
                                    ]"
                                >
                                    <i class="fas fa-wallet text-lg"></i>
                                </div>
                                <span
                                    class="font-bold text-sm font-manrope"
                                    :class="[
                                        paymentMethod === 'e_wallet'
                                            ? 'text-rose-900 dark:text-rose-100'
                                            : 'text-gray-700 dark:text-gray-300',
                                    ]"
                                    >E-Wallet</span
                                >
                                <div
                                    v-if="paymentMethod === 'e_wallet'"
                                    class="ml-auto w-5 h-5 rounded-full bg-rose-600 flex items-center justify-center text-white text-xs shadow-md"
                                >
                                    <i class="fas fa-check"></i>
                                </div>
                            </label>

                            <div
                                v-if="paymentMethod === 'e_wallet'"
                                class="bg-white/50 dark:bg-black/20 p-4 space-y-3 border-t border-rose-100 dark:border-rose-900/20 animate-fade-in"
                            >
                                <label
                                    v-for="wallet in [
                                        'gopay',
                                        'shopeepay',
                                        'dana',
                                    ]"
                                    :key="wallet"
                                    class="flex items-center justify-between p-3 rounded-xl border cursor-pointer group transition-all"
                                    :class="[
                                        ewalletType === wallet
                                            ? 'bg-white dark:bg-white/10 border-rose-600 shadow-md'
                                            : 'bg-transparent border-transparent hover:bg-white dark:hover:bg-white/5 hover:border-gray-200 dark:hover:border-white/10',
                                    ]"
                                >
                                    <div class="flex items-center">
                                        <input
                                            type="radio"
                                            :value="wallet"
                                            v-model="ewalletType"
                                            class="sr-only"
                                        />
                                        <img
                                            :src="`/img/${wallet}.png`"
                                            :alt="wallet"
                                            class="h-6 object-contain grayscale group-hover:grayscale-0 transition-all"
                                            :class="{
                                                '!grayscale-0':
                                                    ewalletType === wallet,
                                            }"
                                        />
                                        <span
                                            class="ml-3 text-xs font-bold uppercase font-manrope"
                                            :class="[
                                                ewalletType === wallet
                                                    ? 'text-rose-600'
                                                    : 'text-gray-500',
                                            ]"
                                        >
                                            {{ wallet }}
                                        </span>
                                    </div>
                                    <div
                                        v-if="ewalletType === wallet"
                                        class="text-rose-600"
                                    >
                                        <i class="fas fa-check-circle"></i>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- Pay Button -->
                        <button
                            @click="processPayment"
                            :disabled="processing"
                            class="w-full h-[68px] bg-gray-900 dark:bg-white hover:bg-rose-600 dark:hover:bg-rose-600 text-white dark:text-gray-900 hover:text-white dark:hover:text-white rounded-2xl shadow-lg shadow-gray-200 dark:shadow-none transform transition-all hover:-translate-y-1 active:scale-[0.98] font-bold flex items-center justify-center space-x-3 group font-unbounded uppercase tracking-wider text-sm mt-6"
                        >
                            <span v-if="!processing" class="flex items-center">
                                <span>Bayar Sekarang</span>
                                <i
                                    class="fas fa-lock ml-3 group-hover:scale-110 transition-transform"
                                ></i>
                            </span>
                            <span
                                v-else
                                class="flex items-center justify-center"
                            >
                                <i class="fas fa-circle-notch fa-spin mr-3"></i>
                                Memproses...
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
