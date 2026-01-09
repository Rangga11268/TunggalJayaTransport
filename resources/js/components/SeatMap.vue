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
        return "bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 cursor-not-allowed border-red-300 dark:border-red-700";
    }
    if (seat.isSelected) {
        return "bg-blue-500 text-white border-blue-600 cursor-pointer hover:bg-blue-600 shadow-lg shadow-blue-500/30";
    }
    if (props.mode === "select") {
        return "bg-green-50 dark:bg-green-900/20 text-green-700 dark:text-green-400 border-green-300 dark:border-green-700 cursor-pointer hover:bg-green-100 dark:hover:bg-green-900/30 hover:scale-110 transition-all";
    }
    return "bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400 border-gray-300 dark:border-gray-600";
};
</script>

<template>
    <div class="seat-map-container">
        <!-- Legend -->
        <div
            class="flex flex-wrap gap-4 mb-6 p-4 bg-gray-50 dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700"
        >
            <div class="flex items-center gap-2">
                <div
                    class="w-8 h-8 rounded-lg bg-green-50 dark:bg-green-900/20 border-2 border-green-300 dark:border-green-700"
                ></div>
                <span
                    class="text-sm font-semibold text-gray-700 dark:text-gray-300"
                    >Tersedia</span
                >
            </div>
            <div class="flex items-center gap-2">
                <div
                    class="w-8 h-8 rounded-lg bg-red-100 dark:bg-red-900/30 border-2 border-red-300 dark:border-red-700"
                ></div>
                <span
                    class="text-sm font-semibold text-gray-700 dark:text-gray-300"
                    >Terisi</span
                >
            </div>
            <div
                v-if="selectedSeats.length > 0"
                class="flex items-center gap-2"
            >
                <div
                    class="w-8 h-8 rounded-lg bg-blue-500 border-2 border-blue-600"
                ></div>
                <span
                    class="text-sm font-semibold text-gray-700 dark:text-gray-300"
                    >Dipilih</span
                >
            </div>
        </div>

        <!-- Bus Front Indicator -->
        <div class="text-center mb-4">
            <div
                class="inline-block px-6 py-2 bg-gray-200 dark:bg-gray-700 rounded-t-2xl"
            >
                <i
                    class="fas fa-steering-wheel mr-2 text-gray-600 dark:text-gray-400"
                ></i>
                <span class="font-bold text-sm text-gray-700 dark:text-gray-300"
                    >DEPAN BUS</span
                >
            </div>
        </div>

        <!-- Seat Grid (2-3 Layout) -->
        <div
            class="bg-white dark:bg-gray-800 border-4 border-gray-300 dark:border-gray-600 rounded-3xl p-6 shadow-xl"
        >
            <div class="space-y-3">
                <div
                    v-for="(row, rowIndex) in seatLayout"
                    :key="rowIndex"
                    class="flex items-center justify-center gap-3"
                >
                    <!-- Left seats (2 seats) -->
                    <div class="flex gap-3">
                        <div
                            v-for="seat in row.filter((s) => s.position < 2)"
                            :key="seat.number"
                            @click="handleSeatClick(seat)"
                            :class="[
                                'seat relative flex flex-col items-center justify-center w-14 h-14 rounded-lg border-2 font-bold text-xs transition-all duration-200',
                                getSeatClass(seat),
                            ]"
                        >
                            <i
                                class="fas text-lg mb-1"
                                :class="
                                    seat.isOccupied
                                        ? 'fa-user-slash'
                                        : seat.isSelected
                                        ? 'fa-user-check'
                                        : 'fa-chair'
                                "
                            ></i>
                            <span>{{ seat.number }}</span>
                        </div>
                    </div>

                    <!-- Aisle -->
                    <div class="flex items-center justify-center px-4">
                        <div
                            class="h-12 w-px bg-gray-300 dark:bg-gray-600"
                        ></div>
                    </div>

                    <!-- Right seats (3 seats) -->
                    <div class="flex gap-3">
                        <div
                            v-for="seat in row.filter((s) => s.position >= 2)"
                            :key="seat.number"
                            @click="handleSeatClick(seat)"
                            :class="[
                                'seat relative flex flex-col items-center justify-center w-14 h-14 rounded-lg border-2 font-bold text-xs transition-all duration-200',
                                getSeatClass(seat),
                            ]"
                        >
                            <i
                                class="fas text-lg mb-1"
                                :class="
                                    seat.isOccupied
                                        ? 'fa-user-slash'
                                        : seat.isSelected
                                        ? 'fa-user-check'
                                        : 'fa-chair'
                                "
                            ></i>
                            <span>{{ seat.number }}</span>
                        </div>
                    </div>

                    <!-- Row number indicator -->
                    <div
                        class="text-xs font-bold text-gray-400 ml-2 min-w-[60px]"
                    >
                        Baris {{ rowIndex + 1 }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Summary -->
        <div
            class="mt-6 p-4 bg-gray-50 dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700"
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
