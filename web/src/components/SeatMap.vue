<script setup>
import { computed } from "vue";

const props = defineProps({
    busCapacity: {
        type: Number,
        required: true,
    },
    occupiedSeats: {
        type: Array,
        default: () => [],
    },
    selectedSeats: {
        type: Array,
        default: () => [],
    },
    mode: {
        type: String,
        default: "view", // 'view' or 'select'
    },
});

const emit = defineEmits(["seat-selected", "seat-deselected"]);

// Generate seat layout (2-3 configuration: 2 left, aisle, 3 right = 5 seats per row)
const seatLayout = computed(() => {
    const rows = Math.ceil(props.busCapacity / 5);
    const layout = [];

    for (let row = 0; row < rows; row++) {
        const rowSeats = [];
        for (let col = 0; col < 5; col++) {
            const seatNumber = row * 5 + col + 1;
            if (seatNumber <= props.busCapacity) {
                rowSeats.push({
                    number: seatNumber,
                    position: col, // 0,1 = left, 2,3,4 = right
                    isOccupied: props.occupiedSeats.includes(seatNumber),
                    isSelected: props.selectedSeats.includes(seatNumber),
                });
            }
        }
        layout.push(rowSeats);
    }

    return layout;
});

const handleSeatClick = (seat) => {
    if (props.mode !== "select") return;
    if (seat.isOccupied) return;

    if (seat.isSelected) {
        emit("seat-deselected", seat.number);
    } else {
        emit("seat-selected", seat.number);
    }
};

const getSeatClass = (seat) => {
    if (seat.isOccupied) {
        return "bg-gray-200 dark:bg-white/5 text-gray-400 cursor-not-allowed opacity-50";
    }
    if (seat.isSelected) {
        return "bg-rose-50 dark:bg-rose-900/10 shadow-lg shadow-rose-600/20 ring-2 ring-rose-600 -translate-y-1";
    }
    return "bg-white dark:bg-[#1a1a1a] text-gray-600 dark:text-gray-400 border border-gray-100 dark:border-white/5";
};

const getSeatIconClass = (seat) => {
    if (seat.isOccupied) return "grayscale";
    if (seat.isSelected) return "sepia-[1] hue-rotate-[300deg] saturate-[2.5]";
    return "dark:brightness-90 hover:brightness-110";
};

const getSeatNumberBadgeClass = (seat) => {
    if (seat.isSelected) return "bg-rose-600 text-white border-rose-600";
    return "bg-gray-100 dark:bg-white/10 text-gray-500 border-gray-200 dark:border-white/10";
};
</script>

<template>
    <div class="seat-map-container font-manrope">
        <!-- Legend -->
        <div
            class="flex flex-wrap gap-4 mb-8 p-4 bg-white dark:bg-[#111] rounded-2xl border border-gray-100 dark:border-white/5 justify-center sm:justify-start shadow-sm"
        >
            <div class="flex items-center gap-2">
                <div
                    class="w-6 h-6 rounded-lg bg-white dark:bg-[#1a1a1a] border border-gray-100 dark:border-white/10"
                ></div>
                <span
                    class="text-xs font-bold text-gray-500 uppercase tracking-wider"
                    >Tersedia</span
                >
            </div>
            <div class="flex items-center gap-2">
                <div
                    class="w-6 h-6 rounded-lg bg-gray-200 dark:bg-white/5 opacity-50"
                ></div>
                <span
                    class="text-xs font-bold text-gray-500 uppercase tracking-wider"
                    >Terisi</span
                >
            </div>
            <div class="flex items-center gap-2">
                <div
                    class="w-6 h-6 rounded-lg bg-rose-50 dark:bg-rose-900/10 border-2 border-rose-600"
                ></div>
                <span
                    class="text-xs font-bold text-rose-600 uppercase tracking-wider"
                    >Pilihan</span
                >
            </div>
        </div>

        <!-- Bus Visual Wrapper -->
        <div
            class="bg-white dark:bg-[#111] rounded-[3rem] p-4 md:p-8 shadow-xl border border-gray-100 dark:border-white/5"
        >
            <div
                class="bg-gray-50 dark:bg-[#080808] rounded-[2.5rem] p-6 border border-gray-200 dark:border-white/5"
            >
                <!-- Bus Front -->
                <div
                    class="flex justify-between items-center mb-10 border-b-2 border-dashed border-gray-200 dark:border-white/10 pb-6"
                >
                    <!-- Door -->
                    <div class="flex flex-col items-center opacity-40">
                        <div
                            class="w-10 h-10 rounded-xl border-2 border-dashed border-gray-300 dark:border-white/20 flex items-center justify-center mb-1"
                        >
                            <i class="fas fa-door-open text-sm"></i>
                        </div>
                        <span
                            class="text-[9px] font-black uppercase tracking-widest text-gray-400"
                            >Pintu</span
                        >
                    </div>

                    <!-- Steering -->
                    <div class="flex flex-col items-center opacity-60">
                        <div
                            class="w-12 h-12 bg-white dark:bg-white/5 rounded-2xl flex items-center justify-center shadow-inner mb-1"
                        >
                            <i class="fas fa-steering-wheel text-gray-400"></i>
                        </div>
                        <span
                            class="text-[9px] font-black uppercase tracking-widest text-gray-400"
                            >Kemudi</span
                        >
                    </div>
                </div>

                <!-- Seat Grid -->
                <div class="space-y-4">
                    <div
                        v-for="(row, rowIndex) in seatLayout"
                        :key="rowIndex"
                        class="flex items-center justify-center gap-4 md:gap-8"
                    >
                        <!-- Left seats (2 seats) -->
                        <div class="flex gap-3">
                            <div
                                v-for="seat in row.filter(
                                    (s) => s.position < 2,
                                )"
                                :key="seat.number"
                                @click="handleSeatClick(seat)"
                                :class="[
                                    'relative w-12 md:w-14 aspect-square rounded-xl flex items-center justify-center transition-all duration-300 p-1 overflow-hidden',
                                    getSeatClass(seat),
                                ]"
                            >
                                <img
                                    src="/img/car-seat.png"
                                    class="w-full h-auto object-contain transition-all duration-300"
                                    :class="getSeatIconClass(seat)"
                                    alt="Seat"
                                />
                                <span
                                    class="absolute -top-1 -right-1 text-[9px] font-bold px-1.5 py-0.5 rounded-md shadow-sm border z-10"
                                    :class="getSeatNumberBadgeClass(seat)"
                                >
                                    {{ seat.number }}
                                </span>
                            </div>
                        </div>

                        <!-- Aisle -->
                        <div class="w-8 flex justify-center items-center">
                            <span
                                class="text-[10px] font-black text-gray-300 dark:text-white/10 border-b border-gray-300 dark:border-white/10"
                            >
                                {{ rowIndex + 1 }}
                            </span>
                        </div>

                        <!-- Right seats (3 seats) -->
                        <div class="flex gap-3">
                            <div
                                v-for="seat in row.filter(
                                    (s) => s.position >= 2,
                                )"
                                :key="seat.number"
                                @click="handleSeatClick(seat)"
                                :class="[
                                    'relative w-12 md:w-14 aspect-square rounded-xl flex items-center justify-center transition-all duration-300 p-1 overflow-hidden',
                                    getSeatClass(seat),
                                ]"
                            >
                                <img
                                    src="/img/car-seat.png"
                                    class="w-full h-auto object-contain transition-all duration-300"
                                    :class="getSeatIconClass(seat)"
                                    alt="Seat"
                                />
                                <span
                                    class="absolute -top-1 -right-1 text-[9px] font-bold px-1.5 py-0.5 rounded-md shadow-sm border z-10"
                                    :class="getSeatNumberBadgeClass(seat)"
                                >
                                    {{ seat.number }}
                                </span>
                                <!-- Checkmark for Selected -->
                                <i
                                    v-if="seat.isSelected"
                                    class="fas fa-check-circle absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 text-white text-base drop-shadow-md z-20"
                                ></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Summary -->
        <div
            class="mt-8 p-6 bg-white dark:bg-[#111] rounded-3xl border border-gray-100 dark:border-white/5 shadow-sm"
        >
            <div class="grid grid-cols-3 gap-4 text-center">
                <div>
                    <p class="text-xs text-gray-500 font-semibold uppercase">
                        Total Kursi
                    </p>
                    <p
                        class="text-2xl font-black text-gray-900 dark:text-white"
                    >
                        {{ busCapacity }}
                    </p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 font-semibold uppercase">
                        Terisi
                    </p>
                    <p
                        class="text-2xl font-black text-red-600 dark:text-red-400"
                    >
                        {{ occupiedSeats.length }}
                    </p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 font-semibold uppercase">
                        Tersedia
                    </p>
                    <p
                        class="text-2xl font-black text-green-600 dark:text-green-400"
                    >
                        {{ busCapacity - occupiedSeats.length }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.seat {
    user-select: none;
}
</style>
