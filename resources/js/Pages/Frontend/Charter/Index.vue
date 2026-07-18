<script setup>
import FrontendLayout from "@/Layouts/FrontendLayout.vue";
import { Head, useForm } from "@inertiajs/vue3";
import { ref } from "vue";
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

const showModal = ref(false);
const activeCalendarBusId = ref(null);

const openBookingForm = (bus) => {
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
    
    form.bus_id = bus.id;
    form.bus_type_requested = bus.name + " - " + bus.capacity + " Seat";
    form.clearErrors(); // clear inertia errors
    showModal.value = true;
};

const toggleCalendar = (busId) => {
    if (activeCalendarBusId.value === busId) {
        activeCalendarBusId.value = null;
    } else {
        activeCalendarBusId.value = busId;
    }
};

const submit = () => {
    form.post(route("frontend.charter.storeStep1"), {
        preserveScroll: true,
        onSuccess: () => {
            showModal.value = false;
        }
    });
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
            
            <div v-if="pariwisataBuses && pariwisataBuses.length > 0" class="flex flex-col gap-6">
                <!-- Bus Card -->
                <div v-for="bus in pariwisataBuses" :key="bus.id" class="bg-white border border-[#ebe7e7] rounded-[16px] overflow-hidden shadow-sm flex flex-col md:flex-row transition-shadow hover:shadow-md">
                    <!-- Image -->
                    <div class="md:w-1/3 bg-gray-100 flex-shrink-0 min-h-[200px] relative">
                        <img v-if="bus.image_url" :src="bus.image_url" :alt="bus.name" class="absolute inset-0 w-full h-full object-cover" />
                        <div v-else class="absolute inset-0 w-full h-full flex items-center justify-center text-gray-400 bg-gray-200">
                            <i class="fas fa-bus text-4xl"></i>
                        </div>
                        <div class="absolute top-4 left-4 bg-[#dfe0ff] text-[#000e5e] px-3 py-1 rounded-[6px] font-bold text-[12px] uppercase">
                            {{ bus.bus_type }}
                        </div>
                    </div>
                    
                    <!-- Content -->
                    <div class="p-6 md:p-8 flex flex-col flex-1">
                        <div class="flex flex-col md:flex-row md:items-start justify-between gap-4 mb-4">
                            <div>
                                <h3 class="font-unbounded font-bold text-[#1c1b1b] text-xl mb-1">{{ bus.name }}</h3>
                                <p class="text-[#454652] text-sm"><i class="fas fa-users mr-1.5 text-gray-400"></i> Kapasitas {{ bus.capacity }} Kursi</p>
                            </div>
                        </div>
                        
                        <p class="text-[#454652] text-sm leading-relaxed mb-6 flex-1">
                            {{ bus.description || 'Armada pariwisata eksekutif yang mengutamakan kenyamanan dan keamanan, cocok untuk berbagai keperluan perjalanan jauh.' }}
                        </p>
                        
                        <!-- Actions -->
                        <div class="flex flex-col sm:flex-row gap-3 pt-6 border-t border-[#f0edec] items-center">
                            <button @click="toggleCalendar(bus.id)" class="w-full sm:w-1/2 py-3 bg-white border-2 border-[#10207a] text-[#10207a] rounded-xl font-bold text-[14px] hover:bg-gray-50 transition-all flex items-center justify-center gap-2">
                                <i class="far fa-calendar-alt"></i> Cek Ketersediaan
                            </button>
                            <button @click="openBookingForm(bus)" class="w-full sm:w-1/2 py-3 bg-[#10207a] text-white rounded-xl font-bold text-[14px] hover:bg-[#0c185e] transition-all flex items-center justify-center gap-2">
                                <i class="fas fa-check-circle"></i> Pesan Bus Ini
                            </button>
                        </div>
                        
                        <!-- Expandable Calendar -->
                        <div v-show="activeCalendarBusId === bus.id" class="mt-6 pt-6 border-t border-dashed border-[#ebe7e7]">
                            <h4 class="font-bold text-[#1c1b1b] text-sm mb-3">Jadwal Ketersediaan: {{ bus.name }}</h4>
                            <div class="md:w-2/3 mx-auto">
                                <CharterAvailabilityCalendar :busId="bus.id" :bookedDates="bookedDates" />
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

        <!-- Booking Modal -->
        <div v-if="showModal" class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" @click="showModal = false"></div>
            <div class="bg-white rounded-[20px] shadow-2xl w-full max-w-3xl relative z-10 max-h-[90vh] overflow-y-auto">
                <div class="p-6 md:p-8">
                    <div class="flex items-center justify-between mb-6 pb-4 border-b border-[#f0edec]">
                        <h3 class="font-unbounded font-bold text-[#1c1b1b] text-[20px]">Form Pemesanan (Step 1)</h3>
                        <button @click="showModal = false" class="w-8 h-8 flex items-center justify-center rounded-full bg-gray-100 hover:bg-gray-200 text-gray-500 transition-colors">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    
                    <p class="text-[#454652] text-sm mb-6">Bus Terpilih: <strong>{{ form.bus_type_requested }}</strong></p>
                    
                    <form @submit.prevent="submit" class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-sm font-bold text-[#1c1b1b] mb-1.5">Tanggal Jemput <span class="text-[#10207a]">*</span></label>
                                <input v-model="form.pickup_date" type="date" required
                                    class="w-full px-4 py-3 bg-[#f6f3f2] border border-[#e5e2e1] focus:border-[#10207a] focus:bg-white focus:ring-0 rounded-[10px] text-[#1c1b1b] text-sm outline-none transition-all" />
                                <InputError :message="form.errors.pickup_date" class="mt-2" />
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-[#1c1b1b] mb-1.5">Jam Jemput <span class="text-[#10207a]">*</span></label>
                                <input v-model="form.pickup_time" type="time" required
                                    class="w-full px-4 py-3 bg-[#f6f3f2] border border-[#e5e2e1] focus:border-[#10207a] focus:bg-white focus:ring-0 rounded-[10px] text-[#1c1b1b] text-sm outline-none transition-all" />
                                <InputError :message="form.errors.pickup_time" class="mt-2" />
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-[#1c1b1b] mb-1.5">Tanggal Selesai <span class="text-[#10207a]">*</span></label>
                                <input v-model="form.return_date" type="date" required :min="form.pickup_date"
                                    class="w-full px-4 py-3 bg-[#f6f3f2] border border-[#e5e2e1] focus:border-[#10207a] focus:bg-white focus:ring-0 rounded-[10px] text-[#1c1b1b] text-sm outline-none transition-all" />
                                <InputError :message="form.errors.return_date" class="mt-2" />
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-sm font-bold text-[#1c1b1b] mb-1.5">Kota Penjemputan <span class="text-[#10207a]">*</span></label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                                        <i class="fas fa-map-marker-alt text-sm"></i>
                                    </div>
                                    <input v-model="form.pickup_location" type="text" placeholder="Misal: Jakarta Selatan" required
                                        class="w-full pl-10 pr-4 py-3 bg-[#f6f3f2] border border-[#e5e2e1] focus:border-[#10207a] focus:bg-white focus:ring-0 rounded-[10px] text-[#1c1b1b] text-sm outline-none transition-all" />
                                </div>
                                <InputError :message="form.errors.pickup_location" class="mt-2" />
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-[#1c1b1b] mb-1.5">Kota / Tempat Tujuan <span class="text-[#10207a]">*</span></label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                                        <i class="fas fa-location-arrow text-sm"></i>
                                    </div>
                                    <input v-model="form.destination" type="text" placeholder="Misal: Bandung, Lembang" required
                                        class="w-full pl-10 pr-4 py-3 bg-[#f6f3f2] border border-[#e5e2e1] focus:border-[#10207a] focus:bg-white focus:ring-0 rounded-[10px] text-[#1c1b1b] text-sm outline-none transition-all" />
                                </div>
                                <InputError :message="form.errors.destination" class="mt-2" />
                            </div>
                        </div>
                        
                        <!-- Check Bus Availability based on dates -->
                        <div v-if="form.errors.bus_id" class="p-4 rounded-xl bg-red-50 text-red-600 border border-red-100 flex gap-3 text-sm">
                            <i class="fas fa-exclamation-circle mt-0.5"></i>
                            <div>
                                <p class="font-bold">Bus tidak tersedia pada tanggal ini!</p>
                                <p>{{ form.errors.bus_id }}</p>
                            </div>
                        </div>
                        
                        <div class="pt-4 border-t border-[#f0edec] flex justify-end gap-3">
                            <button type="button" @click="showModal = false" class="px-6 py-3 bg-gray-100 text-gray-700 rounded-xl font-bold hover:bg-gray-200 transition-colors">
                                Batal
                            </button>
                            <button type="submit" :disabled="form.processing"
                                class="px-8 py-3 bg-[#10207a] text-white rounded-xl font-bold hover:bg-[#0c185e] transition-all disabled:opacity-50 flex items-center gap-2">
                                <span v-if="form.processing"><i class="fas fa-spinner fa-spin"></i> Lanjut</span>
                                <span v-else>Selanjutnya (Lengkapi Detail) <i class="fas fa-arrow-right"></i></span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</template>
