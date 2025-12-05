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
    return base + colIdx + 1;
};
</script>

<template>
    <Head title="Pilih Kursi & Pembayaran" />

    <div
        class="bg-gray-50 dark:bg-gray-950 min-h-screen py-12 px-4 sm:px-6 lg:px-8"
    >
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-10">
                <h1
                    class="text-3xl font-bold text-gray-900 dark:text-white mb-2"
                >
                    Pilih Kursi
                </h1>
                <p class="text-gray-500">
                    Silakan pilih kursi yang tersedia untuk perjalanan Anda
                </p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left: Seat Map -->
                <div class="lg:col-span-2 space-y-8">
                    <div
                        class="bg-white dark:bg-gray-900 rounded-3xl p-6 md:p-10 shadow-xl shadow-black/5 border border-gray-100 dark:border-gray-800"
                    >
                        <!-- Driver Area -->
                        <div class="flex justify-center mb-10">
                            <div
                                class="bg-gray-800 text-white px-8 py-2 rounded-xl text-sm font-bold shadow-lg"
                            >
                                <i class="fas fa-steering-wheel mr-2"></i>
                                DRIVER
                            </div>
                        </div>

                        <!-- Map -->
                        <div class="flex justify-center overflow-x-auto">
                            <div class="p-4 min-w-[300px]">
                                <div
                                    v-for="r in rows"
                                    :key="r"
                                    class="flex items-center justify-center mb-4 gap-8"
                                >
                                    <!-- Left Column (2 Seats) -->
                                    <div class="flex gap-2">
                                        <template
                                            v-for="c in 2"
                                            :key="`L-${r}-${c}`"
                                        >
                                            <button
                                                v-if="
                                                    getSeatNumber(
                                                        r - 1,
                                                        c - 1
                                                    ) <= totalSeats
                                                "
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
                                                :class="[
                                                    'w-12 h-12 rounded-lg flex items-center justify-center text-sm font-bold transition-all relative group',
                                                    isSeatOccupied(
                                                        getSeatNumber(
                                                            r - 1,
                                                            c - 1
                                                        )
                                                    )
                                                        ? 'bg-gray-200 text-gray-400 cursor-not-allowed'
                                                        : isSeatSelected(
                                                              getSeatNumber(
                                                                  r - 1,
                                                                  c - 1
                                                              )
                                                          )
                                                        ? 'bg-brand-red text-white shadow-lg shadow-brand-red/30 scale-105 ring-2 ring-brand-red ring-offset-2 dark:ring-offset-gray-900'
                                                        : 'bg-white border-2 border-gray-200 hover:border-brand-red text-gray-600 hover:text-brand-red',
                                                ]"
                                            >
                                                <i
                                                    class="fas fa-couch text-lg mb-1"
                                                    :class="{
                                                        'text-white':
                                                            isSeatSelected(
                                                                getSeatNumber(
                                                                    r - 1,
                                                                    c - 1
                                                                )
                                                            ),
                                                    }"
                                                ></i>
                                                <span
                                                    class="absolute top-0 right-0 text-[8px] p-0.5 bg-gray-100 dark:bg-gray-800 rounded-bl-md"
                                                    >{{
                                                        getSeatNumber(
                                                            r - 1,
                                                            c - 1
                                                        )
                                                    }}</span
                                                >
                                            </button>
                                            <div v-else class="w-12 h-12"></div>
                                            <!-- Spacer -->
                                        </template>
                                    </div>

                                    <!-- Aisle -->
                                    <div
                                        class="w-8 flex justify-center h-12 items-center"
                                    >
                                        <div
                                            class="h-full w-px border-l border-dashed border-gray-300"
                                        ></div>
                                    </div>

                                    <!-- Right Column (3 Seats) -->
                                    <div class="flex gap-2">
                                        <template
                                            v-for="c in 3"
                                            :key="`R-${r}-${c}`"
                                        >
                                            <button
                                                v-if="
                                                    getSeatNumber(
                                                        r - 1,
                                                        c + 1
                                                    ) <= totalSeats
                                                "
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
                                                :class="[
                                                    'w-12 h-12 rounded-lg flex items-center justify-center text-sm font-bold transition-all relative group',
                                                    isSeatOccupied(
                                                        getSeatNumber(
                                                            r - 1,
                                                            c + 1
                                                        )
                                                    )
                                                        ? 'bg-gray-200 text-gray-400 cursor-not-allowed'
                                                        : isSeatSelected(
                                                              getSeatNumber(
                                                                  r - 1,
                                                                  c + 1
                                                              )
                                                          )
                                                        ? 'bg-brand-red text-white shadow-lg shadow-brand-red/30 scale-105 ring-2 ring-brand-red ring-offset-2 dark:ring-offset-gray-900'
                                                        : 'bg-white border-2 border-gray-200 hover:border-brand-red text-gray-600 hover:text-brand-red',
                                                ]"
                                            >
                                                <i
                                                    class="fas fa-couch text-lg mb-1"
                                                    :class="{
                                                        'text-white':
                                                            isSeatSelected(
                                                                getSeatNumber(
                                                                    r - 1,
                                                                    c + 1
                                                                )
                                                            ),
                                                    }"
                                                ></i>
                                                <span
                                                    class="absolute top-0 right-0 text-[8px] p-0.5 bg-gray-100 dark:bg-gray-800 rounded-bl-md"
                                                    >{{
                                                        getSeatNumber(
                                                            r - 1,
                                                            c + 1
                                                        )
                                                    }}</span
                                                >
                                            </button>
                                            <div v-else class="w-12 h-12"></div>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Legend -->
                        <div class="flex justify-center gap-6 mt-8 flex-wrap">
                            <div class="flex items-center gap-2">
                                <div
                                    class="w-6 h-6 rounded border-2 border-gray-200 bg-white"
                                ></div>
                                <span class="text-sm text-gray-600"
                                    >Terisi</span
                                >
                            </div>
                            <div class="flex items-center gap-2">
                                <div class="w-6 h-6 rounded bg-gray-200"></div>
                                <span class="text-sm text-gray-600"
                                    >Tidak Tersedia</span
                                >
                            </div>
                            <div class="flex items-center gap-2">
                                <div
                                    class="w-6 h-6 rounded bg-brand-red shadow-md shadow-brand-red/30"
                                ></div>
                                <span class="text-sm font-bold text-brand-red"
                                    >Pilihan Anda</span
                                >
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right: Payment & Summary -->
                <div class="lg:col-span-1 space-y-6">
                    <!-- Booking Summary -->
                    <div
                        class="bg-white dark:bg-gray-900 rounded-3xl p-6 shadow-xl shadow-black/5 border border-gray-100 dark:border-gray-800"
                    >
                        <h3
                            class="font-bold text-gray-900 dark:text-white mb-4"
                        >
                            Detail Pemesanan
                        </h3>
                        <div class="text-sm space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-500">Nama</span>
                                <span
                                    class="font-medium text-gray-900 dark:text-gray-200"
                                    >{{ booking.passenger_name }}</span
                                >
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Kursi Dipilih</span>
                                <span class="font-bold text-brand-red">{{
                                    selectedSeats.length > 0
                                        ? selectedSeats.join(", ")
                                        : "-"
                                }}</span>
                            </div>
                        </div>
                        <div
                            class="mt-4 pt-4 border-t border-gray-100 dark:border-gray-800"
                        >
                            <div
                                class="flex justify-between items-center text-lg font-black text-gray-900 dark:text-white"
                            >
                                <span>Total Bayar</span>
                                <span>{{
                                    formatCurrency(booking.total_price)
                                }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Methods -->
                    <div
                        class="bg-white dark:bg-gray-900 rounded-3xl p-6 shadow-xl shadow-black/5 border border-gray-100 dark:border-gray-800"
                    >
                        <h3
                            class="font-bold text-gray-900 dark:text-white mb-4"
                        >
                            Metode Pembayaran
                        </h3>

                        <div class="space-y-3">
                            <!-- Transfer -->
                            <label
                                class="flex items-center p-3 border border-gray-200 dark:border-gray-700 rounded-xl cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors"
                                :class="{
                                    'ring-2 ring-brand-red border-transparent':
                                        paymentMethod === 'bank_transfer',
                                }"
                            >
                                <input
                                    type="radio"
                                    value="bank_transfer"
                                    v-model="paymentMethod"
                                    class="text-brand-red focus:ring-brand-red"
                                />
                                <span
                                    class="ml-3 font-medium text-gray-700 dark:text-gray-200"
                                    >Transfer Bank</span
                                >
                            </label>

                            <!-- Credit Card -->
                            <label
                                class="flex items-center p-3 border border-gray-200 dark:border-gray-700 rounded-xl cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors"
                                :class="{
                                    'ring-2 ring-brand-red border-transparent':
                                        paymentMethod === 'credit_card',
                                }"
                            >
                                <input
                                    type="radio"
                                    value="credit_card"
                                    v-model="paymentMethod"
                                    class="text-brand-red focus:ring-brand-red"
                                />
                                <span
                                    class="ml-3 font-medium text-gray-700 dark:text-gray-200"
                                    >Kartu Kredit</span
                                >
                            </label>

                            <!-- E-Wallet -->
                            <div
                                class="border border-gray-200 dark:border-gray-700 rounded-xl overflow-hidden"
                                :class="{
                                    'ring-2 ring-brand-red border-transparent':
                                        paymentMethod === 'e_wallet',
                                }"
                            >
                                <label
                                    class="flex items-center p-3 cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors"
                                >
                                    <input
                                        type="radio"
                                        value="e_wallet"
                                        v-model="paymentMethod"
                                        class="text-brand-red focus:ring-brand-red"
                                    />
                                    <span
                                        class="ml-3 font-medium text-gray-700 dark:text-gray-200"
                                        >E-Wallet</span
                                    >
                                </label>

                                <div
                                    v-if="paymentMethod === 'e_wallet'"
                                    class="bg-gray-50 dark:bg-gray-800 p-3 space-y-2 border-t border-gray-100 dark:border-gray-700"
                                >
                                    <label class="flex items-center">
                                        <input
                                            type="radio"
                                            value="gopay"
                                            v-model="ewalletType"
                                            class="text-brand-red focus:ring-brand-red"
                                        />
                                        <span
                                            class="ml-2 text-sm text-gray-600 dark:text-gray-400"
                                            >GoPay</span
                                        >
                                    </label>
                                    <label class="flex items-center">
                                        <input
                                            type="radio"
                                            value="shopeepay"
                                            v-model="ewalletType"
                                            class="text-brand-red focus:ring-brand-red"
                                        />
                                        <span
                                            class="ml-2 text-sm text-gray-600 dark:text-gray-400"
                                            >ShopeePay</span
                                        >
                                    </label>
                                    <label class="flex items-center">
                                        <input
                                            type="radio"
                                            value="dana"
                                            v-model="ewalletType"
                                            class="text-brand-red focus:ring-brand-red"
                                        />
                                        <span
                                            class="ml-2 text-sm text-gray-600 dark:text-gray-400"
                                            >DANA</span
                                        >
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
                            class="btn-premium w-full mt-6 py-4 shadow-xl shadow-brand-red/20 disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                            <span v-if="!processing">Bayar Sekarang</span>
                            <span v-else
                                ><i class="fas fa-circle-notch fa-spin"></i>
                                Memproses...</span
                            >
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
