<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

const props = defineProps({
    buses: Array,
    events: Array
});

// Simple month navigation
const currentDate = ref(new Date());

const currentMonthName = computed(() => {
    return currentDate.value.toLocaleString('id-ID', { month: 'long', year: 'numeric' });
});

const daysInMonth = computed(() => {
    const year = currentDate.value.getFullYear();
    const month = currentDate.value.getMonth();
    const days = new Date(year, month + 1, 0).getDate();
    return Array.from({length: days}, (_, i) => {
        const d = new Date(year, month, i + 1, 12, 0, 0); // avoid timezone shifts
        return {
            date: i + 1,
            fullDate: d.toISOString().split('T')[0],
            isWeekend: d.getDay() === 0 || d.getDay() === 6
        };
    });
});

const prevMonth = () => {
    currentDate.value = new Date(currentDate.value.getFullYear(), currentDate.value.getMonth() - 1, 1);
};

const nextMonth = () => {
    currentDate.value = new Date(currentDate.value.getFullYear(), currentDate.value.getMonth() + 1, 1);
};

const today = () => {
    currentDate.value = new Date();
};

const getEventForBusAndDate = (busId, dateStr) => {
    return props.events.find(e => {
        if (e.resourceId !== busId) return false;
        const start = e.start.split('T')[0];
        const end = e.end.split('T')[0];
        return dateStr >= start && dateStr <= end;
    });
};

const getStatusColor = (status) => {
    switch(status) {
        case 'pending': return 'bg-yellow-500';
        case 'quoted': return 'bg-blue-500';
        case 'confirmed': return 'bg-brand-red';
        case 'completed': return 'bg-green-500';
        default: return 'bg-gray-500';
    }
};
</script>

<template>
    <Head title="Kalender Armada" />
    <AdminLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Kalender Armada (Pariwisata)
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-[#151515] overflow-hidden shadow-xl sm:rounded-2xl border border-gray-100 dark:border-white/5">
                    
                    <div class="p-6 border-b border-gray-100 dark:border-gray-800 flex justify-between items-center">
                        <div class="flex items-center space-x-4">
                            <button @click="prevMonth" class="p-2 rounded-xl bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 transition">
                                <svg class="w-5 h-5 text-gray-600 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                            </button>
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white capitalize">{{ currentMonthName }}</h3>
                            <button @click="nextMonth" class="p-2 rounded-xl bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 transition">
                                <svg class="w-5 h-5 text-gray-600 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                            </button>
                        </div>
                        <div>
                            <button @click="today" class="px-4 py-2 bg-brand-red text-white text-sm rounded-xl font-medium hover:bg-red-700 transition">
                                Bulan Ini
                            </button>
                        </div>
                    </div>

                    <div class="p-6 overflow-x-auto">
                        <div class="min-w-[800px]">
                            <!-- Header Days -->
                            <div class="flex border-b border-gray-200 dark:border-gray-800">
                                <div class="w-48 flex-shrink-0 p-3 font-semibold text-gray-700 dark:text-gray-300 text-sm border-r border-gray-200 dark:border-gray-800">
                                    Armada Bus
                                </div>
                                <div class="flex flex-1">
                                    <div v-for="day in daysInMonth" :key="day.date" 
                                         :class="['flex-1 p-2 text-center text-xs font-medium border-r border-gray-200 dark:border-gray-800', day.isWeekend ? 'bg-gray-50 dark:bg-black/30 text-brand-red' : 'text-gray-500']">
                                        {{ day.date }}
                                    </div>
                                </div>
                            </div>

                            <!-- Bus Rows -->
                            <div v-for="bus in buses" :key="bus.id" class="flex border-b border-gray-200 dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-white/[0.02] transition">
                                <div class="w-48 flex-shrink-0 p-3 text-sm text-gray-900 dark:text-white border-r border-gray-200 dark:border-gray-800 font-medium truncate" :title="bus.title">
                                    {{ bus.title }}
                                </div>
                                <div class="flex flex-1 relative">
                                    <div v-for="day in daysInMonth" :key="day.date" 
                                         :class="['flex-1 relative border-r border-gray-200 dark:border-gray-800', day.isWeekend ? 'bg-gray-50 dark:bg-black/30' : '']">
                                         
                                        <!-- Event Cell -->
                                        <template v-if="getEventForBusAndDate(bus.id, day.fullDate)">
                                            <a :href="getEventForBusAndDate(bus.id, day.fullDate).url" 
                                               :title="getEventForBusAndDate(bus.id, day.fullDate).title"
                                               :class="['absolute inset-y-1 inset-x-0 mx-0.5 rounded opacity-90 hover:opacity-100 block group z-10 transition', getStatusColor(getEventForBusAndDate(bus.id, day.fullDate).status)]">
                                            </a>
                                        </template>
                                    </div>
                                </div>
                            </div>
                            
                            <div v-if="buses.length === 0" class="p-8 text-center text-gray-500">
                                Belum ada armada bus yang aktif.
                            </div>
                        </div>
                    </div>
                    
                    <!-- Legend -->
                    <div class="p-6 bg-gray-50 dark:bg-black/20 border-t border-gray-100 dark:border-gray-800 text-xs text-gray-500 flex flex-wrap gap-4">
                        <span class="font-medium">Keterangan Warna:</span>
                        <div class="flex items-center"><div class="w-3 h-3 bg-yellow-500 rounded mr-1"></div> Menunggu Harga</div>
                        <div class="flex items-center"><div class="w-3 h-3 bg-blue-500 rounded mr-1"></div> Diberi Harga (Tunggu DP)</div>
                        <div class="flex items-center"><div class="w-3 h-3 bg-brand-red rounded mr-1"></div> Dikonfirmasi (DP/Lunas)</div>
                        <div class="flex items-center"><div class="w-3 h-3 bg-green-500 rounded mr-1"></div> Selesai</div>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
