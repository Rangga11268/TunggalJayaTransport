<script setup>
import { ref, computed } from 'vue';

const props = defineProps({
    busId: {
        type: [Number, String],
        required: true
    },
    bookedDates: {
        type: Array,
        default: () => []
    }
});

const today = new Date();
const currentMonth = ref(today.getMonth());
const currentYear = ref(today.getFullYear());

const monthNames = [
    "Januari", "Februari", "Maret", "April", "Mei", "Juni",
    "Juli", "Agustus", "September", "Oktober", "November", "Desember"
];
const dayNames = ["Min", "Sen", "Sel", "Rab", "Kam", "Jum", "Sab"];

// Helper to check if a date is booked for the given bus
const isDateBooked = (dateStr) => {
    return props.bookedDates.some(booking => {
        if (String(booking.bus_id) !== String(props.busId)) return false;
        return dateStr >= booking.pickup_date && dateStr <= booking.return_date;
    });
};

const getDaysInMonth = (year, month) => {
    return new Date(year, month + 1, 0).getDate();
};

const getFirstDayOfMonth = (year, month) => {
    return new Date(year, month, 1).getDay();
};

const generateCalendar = (year, month) => {
    const daysInMonth = getDaysInMonth(year, month);
    const firstDay = getFirstDayOfMonth(year, month);
    const days = [];

    // Empty cells before the 1st
    for (let i = 0; i < firstDay; i++) {
        days.push({ empty: true });
    }

    // Days of the month
    for (let i = 1; i <= daysInMonth; i++) {
        const d = new Date(year, month, i);
        // adjust to local timezone string YYYY-MM-DD
        const dateStr = [
            d.getFullYear(),
            String(d.getMonth() + 1).padStart(2, '0'),
            String(d.getDate()).padStart(2, '0')
        ].join('-');
        
        days.push({
            empty: false,
            date: i,
            dateStr: dateStr,
            isPast: d < new Date(today.setHours(0,0,0,0)),
            isBooked: isDateBooked(dateStr)
        });
    }
    return days;
};

const nextMonth = () => {
    if (currentMonth.value === 11) {
        currentMonth.value = 0;
        currentYear.value++;
    } else {
        currentMonth.value++;
    }
};

const prevMonth = () => {
    if (currentMonth.value === 0) {
        currentMonth.value = 11;
        currentYear.value--;
    } else {
        currentMonth.value--;
    }
};

const canGoPrev = computed(() => {
    // Prevent navigating to months before the current real month
    const realToday = new Date();
    if (currentYear.value > realToday.getFullYear()) return true;
    if (currentYear.value === realToday.getFullYear() && currentMonth.value > realToday.getMonth()) return true;
    return false;
});

const calendarDays = computed(() => generateCalendar(currentYear.value, currentMonth.value));
</script>

<template>
    <div class="bg-white rounded-[12px] border border-[#ebe7e7] shadow-sm p-4 w-full">
        <!-- Header -->
        <div class="flex items-center justify-between mb-4">
            <button @click="prevMonth" type="button" :disabled="!canGoPrev" 
                class="p-2 rounded-full hover:bg-gray-100 disabled:opacity-30 transition-colors">
                <i class="fas fa-chevron-left text-[#454652]"></i>
            </button>
            <h4 class="font-unbounded font-bold text-[#1c1b1b]">
                {{ monthNames[currentMonth] }} {{ currentYear }}
            </h4>
            <button @click="nextMonth" type="button"
                class="p-2 rounded-full hover:bg-gray-100 transition-colors">
                <i class="fas fa-chevron-right text-[#454652]"></i>
            </button>
        </div>

        <!-- Days Header -->
        <div class="grid grid-cols-7 gap-1 mb-2">
            <div v-for="day in dayNames" :key="day" class="text-center text-xs font-bold text-[#454652] py-1">
                {{ day }}
            </div>
        </div>

        <!-- Calendar Grid -->
        <div class="grid grid-cols-7 gap-1">
            <div v-for="(day, idx) in calendarDays" :key="idx" 
                class="aspect-square flex items-center justify-center rounded-[6px] text-sm relative"
                :class="[
                    day.empty ? '' : 'border border-transparent',
                    day.isPast ? 'text-gray-300' : (day.isBooked ? 'bg-red-50 text-red-600 font-bold border-red-200' : 'bg-[#f6f3f2] text-[#1c1b1b] hover:bg-[#10207a] hover:text-white cursor-pointer')
                ]"
            >
                <template v-if="!day.empty">
                    {{ day.date }}
                    <!-- Tooltip for booked -->
                    <div v-if="day.isBooked" class="absolute -top-1 -right-1 w-2 h-2 rounded-full bg-red-500"></div>
                </template>
            </div>
        </div>
        
        <!-- Legend -->
        <div class="flex items-center justify-center gap-4 mt-4 pt-4 border-t border-[#ebe7e7] text-xs">
            <div class="flex items-center gap-1.5">
                <div class="w-3 h-3 rounded bg-[#f6f3f2]"></div>
                <span class="text-gray-500">Tersedia</span>
            </div>
            <div class="flex items-center gap-1.5">
                <div class="w-3 h-3 rounded bg-red-100 border border-red-200 relative">
                    <div class="absolute -top-0.5 -right-0.5 w-1.5 h-1.5 rounded-full bg-red-500"></div>
                </div>
                <span class="text-gray-500">Dipesan</span>
            </div>
        </div>
    </div>
</template>
