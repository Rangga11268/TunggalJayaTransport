<script setup>
import FrontendLayout from "@/Layouts/FrontendLayout.vue";
import { Head, useForm, Link } from "@inertiajs/vue3";
import InputError from "@/Components/InputError.vue";

defineOptions({ layout: FrontendLayout });

const props = defineProps({
    selectedBusType: String,
    bookingData: Object,
    errors: Object, // Validation errors from session
});

const urlParams = new URLSearchParams(window.location.search);

const form = useForm({
    pickup_date: props.bookingData?.pickup_date || urlParams.get('date') || "",
    pickup_time: props.bookingData?.pickup_time || urlParams.get('time') || "",
    return_date: props.bookingData?.return_date || "",
    pickup_location: props.bookingData?.pickup_location || urlParams.get('origin') || "",
    destination: props.bookingData?.destination || urlParams.get('destination') || "",
    passenger_count: props.bookingData?.passenger_count || "",
    institution_name: props.bookingData?.institution_name || "",
    bus_requests: props.bookingData?.bus_requests || [
        { type: 'Big Bus', count: 1, with_legrest: false, seat_configuration: 'Bebas' }
    ],
});

const submit = () => {
    form.post(route("frontend.charter.storeStep1"), {
        preserveScroll: true,
    });
};
</script>

<template>
    <Head title="Pesan Sewa Bus - Langkah 1" />

    <div class="bg-[#fcf9f8] min-h-screen pb-24">
        <!-- Header -->
        <div class="pt-28 pb-8 px-4 sm:px-6 lg:px-8 text-center bg-white border-b border-[#ebe7e7]">
            <div class="max-w-3xl mx-auto">
                <div class="flex items-center justify-center gap-4 text-sm font-bold mb-4">
                    <span class="text-[#10207a]">1. Info Dasar</span>
                    <i class="fas fa-chevron-right text-gray-300 text-[10px]"></i>
                    <span class="text-gray-400">2. Detail Penjemputan</span>
                </div>
                <h1 class="font-unbounded font-black text-3xl md:text-4xl text-[#1c1b1b]">
                    Form Pemesanan
                </h1>
            </div>
        </div>

        <div class="max-w-7xl mx-auto mt-8 px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                <!-- Summary Sidebar -->
                <div class="lg:col-span-4 lg:order-2">
                    <div class="bg-white border border-[#ebe7e7] rounded-[12px] p-6 shadow-sm sticky top-24">
                        <h3 class="font-unbounded font-bold text-[#1c1b1b] text-xl mb-4">Ringkasan Pesanan</h3>
                        
                        <div class="space-y-4">
                            <div class="flex justify-between pb-4 border-b border-gray-100">
                                <span class="text-gray-500 text-sm">Pesanan Armada</span>
                                <span class="font-bold text-[#1c1b1b] text-sm">Big Bus ({{ form.bus_requests.reduce((sum, r) => sum + r.count, 0) }} Unit)</span>
                            </div>
                            <div class="flex justify-between pb-4 border-b border-gray-100">
                                <span class="text-gray-500 text-sm">Tanggal</span>
                                <span class="font-bold text-[#1c1b1b] text-sm text-right">
                                    {{ form.pickup_date || '-' }}<br>
                                    s/d {{ form.return_date || '-' }}
                                </span>
                            </div>
                            <div class="flex justify-between pb-4 border-b border-gray-100">
                                <span class="text-gray-500 text-sm">Rute</span>
                                <span class="font-bold text-[#1c1b1b] text-sm text-right">
                                    {{ form.pickup_location || '-' }} <i class="fas fa-arrow-right mx-1 text-gray-300"></i> {{ form.destination || '-' }}
                                </span>
                            </div>
                        </div>

                        <Link :href="route('frontend.charter.index')"
                            class="w-full mt-6 py-3 border-2 border-[#10207a] text-[#10207a] rounded-xl font-bold text-[14px] hover:bg-[#10207a] hover:text-white transition-all flex items-center justify-center">
                            Lihat Katalog Pariwisata
                        </Link>
                    </div>
                </div>

                <!-- Form Details -->
                <div class="lg:col-span-8 lg:order-1">
                    <div class="bg-white border border-[#ebe7e7] rounded-[12px] p-6 md:p-8 shadow-sm">
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
                            
                            <!-- Institution / Organisasi -->
                            <div class="mt-5">
                                <label class="block text-sm font-bold text-[#1c1b1b] mb-1.5">Asal Instansi / Sekolah / Biro <span class="text-gray-400 font-normal">(Opsional)</span></label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                                        <i class="fas fa-building text-sm"></i>
                                    </div>
                                    <input v-model="form.institution_name" type="text" placeholder="Misal: SMAN 1 Jakarta / PT Maju Jaya"
                                        class="w-full pl-10 pr-4 py-3 bg-[#f6f3f2] border border-[#e5e2e1] focus:border-[#10207a] focus:bg-white focus:ring-0 rounded-[10px] text-[#1c1b1b] text-sm outline-none transition-all" />
                                </div>
                                <InputError :message="form.errors.institution_name" class="mt-2" />
                            </div>
                            <!-- Custom Bus Type Requested & Count (Dynamic) -->
                            <div class="mt-5 space-y-4">
                                <div class="flex items-center justify-between">
                                    <label class="block text-sm font-bold text-[#1c1b1b]">Armada yang Dipesan <span class="text-[#10207a]">*</span></label>
                                </div>
                                <div v-for="(req, index) in form.bus_requests" :key="index" class="p-4 bg-white border border-gray-100 rounded-xl shadow-sm relative">
                                    <h4 class="font-bold text-[#10207a] text-sm mb-3 border-b border-gray-100 pb-2">Konfigurasi Bus {{ index + 1 }}</h4>
                                    <button v-if="form.bus_requests.length > 1" type="button" @click="form.bus_requests.splice(index, 1)" class="absolute top-4 right-4 w-7 h-7 bg-red-100 text-red-500 rounded-full flex items-center justify-center hover:bg-red-500 hover:text-white transition-colors">
                                        <i class="fas fa-times text-xs"></i>
                                    </button>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div class="hidden">
                                            <input type="hidden" v-model="req.type" value="Big Bus" />
                                        </div>
                                        <div class="col-span-1 md:col-span-2">
                                            <label class="block text-xs text-gray-500 mb-1">Jumlah Unit (Dengan konfigurasi ini)</label>
                                            <input v-model="req.count" type="number" min="1" required
                                                class="w-full px-4 py-2.5 bg-[#f6f3f2] border border-[#e5e2e1] focus:border-[#10207a] focus:bg-white focus:ring-0 rounded-[10px] text-[#1c1b1b] text-sm outline-none transition-all" />
                                        </div>
                                        <div class="col-span-1 md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-4 mt-2">
                                            <div>
                                                <label class="block text-xs text-gray-500 mb-1">Fasilitas Tambahan</label>
                                                <label class="flex items-center cursor-pointer mt-2">
                                                    <input type="checkbox" v-model="req.with_legrest" class="rounded border-gray-300 text-[#10207a] focus:ring-[#10207a]" />
                                                    <span class="ml-2 text-sm text-[#1c1b1b]">Pakai Leg Rest</span>
                                                </label>
                                            </div>
                                            <div>
                                                <label class="block text-xs text-gray-500 mb-1">Konfigurasi Kursi</label>
                                                <select v-model="req.seat_configuration" class="w-full px-4 py-2.5 bg-[#f6f3f2] border border-[#e5e2e1] focus:border-[#10207a] focus:bg-white focus:ring-0 rounded-[10px] text-[#1c1b1b] text-sm outline-none transition-all">
                                                    <option value="Bebas">Bebas (Standard)</option>
                                                    <option value="2-2">2-2 (Kanan 2, Kiri 2)</option>
                                                    <option value="3-2">3-2 (Kanan 3, Kiri 2)</option>
                                                </select>
                                            </div>
                                        </div>
                                        </div>
                                </div>
                                <button type="button" @click="form.bus_requests.push({ type: 'Big Bus', count: 1, with_legrest: false, seat_configuration: 'Bebas' })" class="w-full py-2.5 border-2 border-dashed border-[#10207a]/30 text-[#10207a] rounded-xl hover:bg-[#10207a]/5 font-medium transition-colors text-sm flex items-center justify-center gap-2">
                                    <i class="fas fa-plus"></i> Tambah Konfigurasi Berbeda <span class="hidden md:inline text-xs font-normal opacity-75">(Misal: Beda Fasilitas)</span>
                                </button>
                                <InputError :message="form.errors.bus_requests" class="mt-2" />
                            </div>
                            
                            <!-- Passenger Count -->
                            <div class="pt-4 border-t border-[#ebe7e7]">
                                <label class="block text-sm font-bold text-[#1c1b1b] mb-1.5">Total Penumpang Keseluruhan <span class="text-gray-400 font-normal text-xs">(Semua bus digabung)</span> <span class="text-[#10207a]">*</span></label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                                        <i class="fas fa-users text-sm"></i>
                                    </div>
                                    <input v-model="form.passenger_count" type="number" min="1" required placeholder="Total penumpang dari seluruh armada..."
                                        class="w-full pl-10 pr-4 py-3 bg-[#f6f3f2] border border-[#e5e2e1] focus:border-[#10207a] focus:bg-white focus:ring-0 rounded-[10px] text-[#1c1b1b] text-sm outline-none transition-all" />
                                </div>
                                <InputError :message="form.errors.passenger_count" class="mt-1" />
                            </div>

                            <div class="pt-4 border-t border-[#f0edec] flex justify-end gap-3 mt-6">
                                <button type="submit" :disabled="form.processing"
                                    class="w-full md:w-auto px-8 py-3 bg-[#10207a] text-white rounded-xl font-bold hover:bg-[#0c185e] transition-all disabled:opacity-50 flex items-center justify-center gap-2">
                                    <span v-if="form.processing"><i class="fas fa-spinner fa-spin"></i> Lanjut</span>
                                    <span v-else>Selanjutnya (Lengkapi Detail) <i class="fas fa-arrow-right"></i></span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
