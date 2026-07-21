<script setup>
import FrontendLayout from "@/Layouts/FrontendLayout.vue";
import { Head, useForm } from "@inertiajs/vue3";
import { ref, computed } from "vue";
import Swal from "sweetalert2";
import InputError from "@/Components/InputError.vue";
import CharterAvailabilityCalendar from "@/Components/CharterAvailabilityCalendar.vue";

defineOptions({ layout: FrontendLayout });

const props = defineProps({
    auth: Object,
    pariwisataBuses: Array,
    bookedDates: Array,
    errors: Object, // Validation errors from session
});

const urlParams = new URLSearchParams(window.location.search);

const form = useForm({
    pickup_date: urlParams.get('date') || "",
    pickup_time: urlParams.get('time') || "",
    return_date: "",
    pickup_location: urlParams.get('origin') || "",
    destination: urlParams.get('destination') || "",
    bus_type_requested: "",
    bus_id: "",
});

import { router } from "@inertiajs/vue3";

const activeCalendarBusId = ref(null);

const groupedBuses = computed(() => {
    if (!props.pariwisataBuses) return [];
    const groups = {};
    props.pariwisataBuses.forEach(bus => {
        const type = bus.bus_type || 'Unknown Type';
        if (!groups[type]) {
            groups[type] = {
                id: type,
                bus_type: type,
                name: type + (bus.capacity ? ` (${bus.capacity} Seat)` : ''),
                capacity: bus.capacity,
                description: `Pilihan armada ${type} dengan fasilitas pariwisata eksekutif yang mengutamakan kenyamanan dan keamanan untuk perjalanan rombongan.`,
                image_url: bus.image_url,
                units: []
            };
        }
        groups[type].units.push(bus);
    });
    return Object.values(groups);
});

const openBookingForm = (group) => {
    if (!props.auth.user) {
        Swal.fire({
            icon: 'warning',
            title: 'Harap Login',
            text: 'Anda harus login terlebih dahulu untuk menyewa bus pariwisata.',
            confirmButtonText: 'Tutup',
            confirmButtonColor: '#E11D48',
        });
        return;
    }
    
    router.visit(route('frontend.charter.step1', { bus_type: group.name }));
};

const toggleCalendar = (busId) => {
    if (activeCalendarBusId.value === busId) {
        activeCalendarBusId.value = null;
    } else {
        activeCalendarBusId.value = busId;
    }
};
</script>

<template>
    <Head title="Sewa Pariwisata" />

    <div class="bg-[#fcf9f8] min-h-screen pb-24">
        <!-- Hero -->
        <div class="relative pt-28 pb-12 px-4 sm:px-6 lg:px-8 text-center bg-gradient-to-b from-white to-[#fcf9f8]">
            <span class="inline-block px-4 py-1.5 rounded-full bg-white border border-[#ebe7e7] text-[#10207a] text-[11px] font-bold tracking-widest uppercase mb-5 shadow-sm">
                Layanan Eksklusif
            </span>
            <h1 class="font-unbounded font-black text-4xl md:text-6xl text-[#1c1b1b] mb-4">
                Sewa Bus Pariwisata
            </h1>
            <p class="text-[#454652] text-[16px] max-w-2xl mx-auto">
                Armada eksklusif Tunggal Jaya Transport siap menemani perjalanan wisata Anda dengan kenyamanan dan keamanan tingkat tinggi.
            </p>
        </div>

        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between mb-8">
                <h2 class="font-unbounded font-bold text-[#1c1b1b] text-[24px]">Katalog Armada Pariwisata</h2>
            </div>
            
            <div v-if="groupedBuses && groupedBuses.length > 0" class="flex flex-col gap-6">
                <!-- Bus Group Card -->
                <div v-for="group in groupedBuses" :key="group.id" class="bg-white border border-[#ebe7e7] rounded-[16px] overflow-hidden shadow-sm flex flex-col md:flex-row transition-shadow hover:shadow-md">
                    <!-- Image -->
                    <div class="md:w-1/3 bg-gray-100 flex-shrink-0 min-h-[200px] relative">
                        <img v-if="group.image_url" :src="group.image_url" :alt="group.name" class="absolute inset-0 w-full h-full object-cover" />
                        <div v-else class="absolute inset-0 w-full h-full flex items-center justify-center text-gray-400 bg-gray-200">
                            <i class="fas fa-bus text-4xl"></i>
                        </div>
                        <div class="absolute top-4 left-4 bg-[#dfe0ff] text-[#000e5e] px-3 py-1 rounded-[6px] font-bold text-[12px] uppercase">
                            {{ group.bus_type }}
                        </div>
                    </div>
                    
                    <!-- Content -->
                    <div class="p-6 md:p-8 flex flex-col flex-1">
                        <div class="flex flex-col md:flex-row md:items-start justify-between gap-4 mb-4">
                            <div>
                                <h3 class="font-unbounded font-bold text-[#1c1b1b] text-xl mb-1">{{ group.name }}</h3>
                                <p class="text-[#454652] text-sm"><i class="fas fa-users mr-1.5 text-gray-400"></i> Kapasitas {{ group.capacity }} Kursi</p>
                            </div>
                        </div>
                        
                        <p class="text-[#454652] text-sm leading-relaxed mb-6">
                            {{ group.description }}
                        </p>

                        <!-- Available Units -->
                        <div class="mb-6 flex-1">
                            <h4 class="font-bold text-[#1c1b1b] text-sm mb-3">Tersedia {{ group.units.length }} Unit Armada:</h4>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div v-for="unit in group.units" :key="unit.id" class="p-3 bg-gray-50 border border-gray-100 rounded-lg flex items-center justify-between">
                                    <div>
                                        <div class="font-bold text-sm text-[#1c1b1b]">{{ unit.name }}</div>
                                        <div class="text-xs text-gray-500">{{ unit.plate_number }}</div>
                                    </div>
                                    <button @click="toggleCalendar(unit.id)" class="text-xs font-bold text-[#10207a] hover:underline bg-white px-3 py-1.5 rounded border border-[#10207a]">
                                        <i class="far fa-calendar-alt mr-1"></i> Jadwal
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Actions -->
                        <div class="flex gap-3 pt-6 border-t border-[#f0edec] items-center">
                            <button @click="openBookingForm(group)" class="w-full py-3 bg-[#10207a] text-white rounded-xl font-bold text-[14px] hover:bg-[#0c185e] transition-all flex items-center justify-center gap-2">
                                <i class="fas fa-check-circle"></i> Pesan Tipe Bus Ini
                            </button>
                        </div>
                        
                        <!-- Expandable Calendar (Appears outside the list, shared dynamically) -->
                        <div v-for="unit in group.units" :key="'cal-' + unit.id">
                            <div v-show="activeCalendarBusId === unit.id" class="mt-6 pt-6 border-t border-dashed border-[#ebe7e7]">
                                <div class="flex items-center justify-between mb-3">
                                    <h4 class="font-bold text-[#1c1b1b] text-sm">Jadwal Ketersediaan: {{ unit.name }} ({{ unit.plate_number }})</h4>
                                    <button @click="toggleCalendar(unit.id)" class="text-gray-400 hover:text-red-500"><i class="fas fa-times"></i></button>
                                </div>
                                <div class="md:w-2/3 mx-auto">
                                    <CharterAvailabilityCalendar :busId="unit.id" :bookedDates="bookedDates" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div v-else class="text-center py-20 bg-white border border-[#ebe7e7] rounded-[16px]">
                <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-bus-slash text-3xl text-gray-400"></i>
                </div>
                <h3 class="font-unbounded font-bold text-gray-900 text-lg mb-2">Belum Ada Armada</h3>
                <p class="text-gray-500">Saat ini tidak ada bus pariwisata yang tersedia.</p>
            </div>
        </div>



    </div>
</template>
