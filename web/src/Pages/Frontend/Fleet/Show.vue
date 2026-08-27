<script setup>
import { Head, Link } from "@inertiajs/vue3";
import FrontendLayout from "@/Layouts/FrontendLayout.vue";

defineOptions({ layout: FrontendLayout });

const props = defineProps({
    bus: Object,
    relatedBuses: Array,
    facilities: Array,
});

const isPariwisata = props.bus.bus_category === 'pariwisata';

const getCategoryBadgeClass = (category) => {
    return category === 'pariwisata'
        ? 'bg-amber-500/10 text-amber-600 border-amber-500/20 dark:bg-amber-400/10 dark:text-amber-400'
        : 'bg-[#10207a]/10 text-[#10207a] border-[#10207a]/20 dark:bg-blue-400/10 dark:text-blue-400';
};
</script>

<template>
    <Head :title="`Detail Armada - ${bus.name}`" />

    <div class="min-h-screen bg-[#fcf9f8] pt-28 pb-32">
        <!-- Breadcrumb & Back -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-6">
            <div class="flex items-center justify-between">
                <Link :href="route('frontend.fleet.index')" class="inline-flex items-center gap-2 text-sm font-bold text-[#454652] hover:text-[#10207a] transition-colors">
                    <i class="fas fa-arrow-left"></i> Kembali ke Daftar Armada
                </Link>

                <div class="flex items-center gap-2">
                    <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider border" :class="getCategoryBadgeClass(bus.bus_category)">
                        <i :class="isPariwisata ? 'fas fa-umbrella-beach mr-1' : 'fas fa-route mr-1'"></i>
                        {{ isPariwisata ? 'Layanan Pariwisata / Charter' : 'Layanan AKAP (Rute Reguler)' }}
                    </span>
                    <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-gray-100 text-gray-800 border border-gray-200">
                        {{ bus.bus_type || 'Executive' }}
                    </span>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                <!-- Left Section: Bus Images & Specs -->
                <div class="lg:col-span-8 space-y-8">
                    <!-- Image Card -->
                    <div class="bg-white border border-[#ebe7e7] rounded-[16px] overflow-hidden shadow-sm">
                        <div class="relative h-[320px] sm:h-[420px] bg-gray-900">
                            <img :src="bus.image_url || '/img/noImg.png'" :alt="bus.name" class="w-full h-full object-cover" />
                            <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>
                            
                            <div class="absolute bottom-6 left-6 right-6 flex items-end justify-between">
                                <div>
                                    <span class="inline-block px-3 py-1 rounded-lg text-xs font-bold uppercase tracking-wider mb-2 text-white"
                                        :class="isPariwisata ? 'bg-amber-600' : 'bg-[#10207a]'">
                                        {{ isPariwisata ? 'Charter & Wisata' : 'Antar Kota Antar Provinsi' }}
                                    </span>
                                    <h1 class="text-2xl sm:text-4xl font-bold font-unbounded text-white drop-shadow-md">
                                        {{ bus.name }}
                                    </h1>
                                    <p class="text-white/80 text-sm mt-1 font-mono">
                                        No. Polisi: {{ bus.plate_number }}
                                    </p>
                                </div>
                                <div class="hidden sm:block text-right">
                                    <span class="text-white/70 text-xs block uppercase tracking-wider">Kapasitas Kursi</span>
                                    <span class="text-2xl font-bold text-white font-unbounded">{{ bus.capacity }} Seat</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Fast Specs Overview Grid -->
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                        <div class="bg-white border border-[#ebe7e7] p-4 rounded-[12px] text-center shadow-sm">
                            <i class="fas fa-couch text-[#10207a] text-xl mb-1"></i>
                            <span class="block text-[11px] text-[#454652] uppercase font-bold tracking-wider">Kapasitas</span>
                            <span class="text-base font-bold text-[#1c1b1b]">{{ bus.capacity }} Kursi</span>
                        </div>
                        <div class="bg-white border border-[#ebe7e7] p-4 rounded-[12px] text-center shadow-sm">
                            <i class="fas fa-layer-group text-[#10207a] text-xl mb-1"></i>
                            <span class="block text-[11px] text-[#454652] uppercase font-bold tracking-wider">Kelas Armada</span>
                            <span class="text-base font-bold text-[#1c1b1b]">{{ bus.bus_type }}</span>
                        </div>
                        <div class="bg-white border border-[#ebe7e7] p-4 rounded-[12px] text-center shadow-sm">
                            <i class="fas fa-tags text-[#10207a] text-xl mb-1"></i>
                            <span class="block text-[11px] text-[#454652] uppercase font-bold tracking-wider">Kategori</span>
                            <span class="text-base font-bold text-[#1c1b1b] uppercase">{{ bus.bus_category }}</span>
                        </div>
                        <div class="bg-white border border-[#ebe7e7] p-4 rounded-[12px] text-center shadow-sm">
                            <i class="fas fa-calendar-alt text-[#10207a] text-xl mb-1"></i>
                            <span class="block text-[11px] text-[#454652] uppercase font-bold tracking-wider">Tahun Rilis</span>
                            <span class="text-base font-bold text-[#1c1b1b]">{{ bus.year || 'Terbaru' }}</span>
                        </div>
                    </div>

                    <!-- Description Card -->
                    <div class="bg-white border border-[#ebe7e7] rounded-[16px] p-6 md:p-8 shadow-sm">
                        <h3 class="font-unbounded font-bold text-[#1c1b1b] text-lg mb-4 flex items-center gap-2">
                            <i class="fas fa-[#10207a] fa-info-circle"></i> Deskripsi & Informasi Armada
                        </h3>
                        <p class="text-[#454652] leading-relaxed text-sm sm:text-base">
                            {{ bus.description || 'Armada bus premium Tunggal Jaya Transport dirancang untuk memberikan pengalaman perjalanan yang aman, halus, dan sangat nyaman. Dilengkapi dengan suspensi udara modern, kabin yang senyap, serta deretan fasilitas terlengkap untuk memastikan Anda tiba di tujuan dalam kondisi segar.' }}
                        </p>
                    </div>

                    <!-- Facilities Section -->
                    <div class="bg-white border border-[#ebe7e7] rounded-[16px] p-6 md:p-8 shadow-sm">
                        <h3 class="font-unbounded font-bold text-[#1c1b1b] text-lg mb-6 flex items-center gap-2">
                            <i class="fas fa-concierge-bell text-[#10207a]"></i> Fasilitas Unggulan Armada
                        </h3>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div v-for="facility in facilities" :key="facility.name"
                                class="flex items-start gap-3.5 p-3.5 rounded-xl bg-[#fcf9f8] border border-gray-100 hover:border-gray-300 transition-colors">
                                <div class="w-10 h-10 rounded-lg bg-[#10207a]/10 text-[#10207a] flex items-center justify-center shrink-0">
                                    <i :class="facility.icon" class="text-base"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-[#1c1b1b] text-sm">{{ facility.name }}</h4>
                                    <p class="text-xs text-[#454652] mt-0.5">{{ facility.desc }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Sidebar: Action Card & Schedule/Category Info -->
                <div class="lg:col-span-4 space-y-6">
                    <!-- Action Box -->
                    <div class="bg-white border border-[#ebe7e7] rounded-[16px] p-6 shadow-sm sticky top-28 space-y-6">
                        <div class="p-4 rounded-xl" :class="isPariwisata ? 'bg-amber-500/10 border border-amber-500/20' : 'bg-[#10207a]/10 border border-[#10207a]/20'">
                            <span class="text-xs font-bold uppercase tracking-wider block mb-1" :class="isPariwisata ? 'text-amber-700' : 'text-[#10207a]'">
                                {{ isPariwisata ? 'Sewa Wisata & Event' : 'Tiket Ruler AKAP' }}
                            </span>
                            <p class="text-xs text-gray-700">
                                {{ isPariwisata 
                                    ? 'Armada ini siap disewa untuk keperluan acara keluarga, instansi, study tour, dan liburan rombongan.' 
                                    : 'Armada ini beroperasi secara reguler melayani rute Antar Kota Antar Provinsi sesuai jadwal penyeberangan.' 
                                }}
                            </p>
                        </div>

                        <div>
                            <span class="text-xs font-bold text-gray-400 uppercase tracking-wider block mb-1">Status Operasional</span>
                            <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                SIAP BEROPERASI
                            </span>
                        </div>

                        <div class="pt-4 border-t border-gray-100 space-y-3">
                            <Link v-if="isPariwisata" :href="route('frontend.charter.index')"
                                class="w-full py-4 bg-amber-500 text-white rounded-xl font-bold text-center block text-sm hover:bg-amber-600 transition-all shadow-md shadow-amber-500/20">
                                <i class="fas fa-umbrella-beach mr-2"></i> Sewa Bus Ini Sekarang
                            </Link>
                            <Link v-else :href="route('frontend.booking.index')"
                                class="w-full py-4 bg-[#10207a] text-white rounded-xl font-bold text-center block text-sm hover:bg-[#0c185e] transition-all shadow-md shadow-[#10207a]/20">
                                <i class="fas fa-ticket-alt mr-2"></i> Cari Jadwal Tiket AKAP
                            </Link>

                            <a href="https://wa.me/6281234567890?text=Halo%20Tunggal%20Jaya,%20saya%20ingin%20tanya%20mengenai%20armada%20" target="_blank"
                                class="w-full py-3.5 border-2 border-emerald-600 text-emerald-700 rounded-xl font-bold text-center block text-xs hover:bg-emerald-50 transition-all">
                                <i class="fab fa-whatsapp mr-1.5 text-base"></i> Hubungi CS via WhatsApp
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Related Fleet Section -->
            <div v-if="relatedBuses && relatedBuses.length > 0" class="mt-16 pt-12 border-t border-[#ebe7e7]">
                <h3 class="font-unbounded font-bold text-[#1c1b1b] text-xl mb-6">Armada Sejenis Lainnya</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div v-for="relBus in relatedBuses" :key="relBus.id"
                        class="bg-white rounded-[14px] overflow-hidden border border-[#ebe7e7] shadow-sm hover:shadow-md transition-all flex flex-col">
                        <div class="h-44 relative overflow-hidden">
                            <img :src="relBus.image_url || '/img/noImg.png'" :alt="relBus.name" class="w-full h-full object-cover" />
                            <span class="absolute top-3 right-3 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider text-white"
                                :class="relBus.bus_category === 'pariwisata' ? 'bg-amber-500' : 'bg-[#10207a]'">
                                {{ relBus.bus_category }}
                            </span>
                        </div>
                        <div class="p-4 flex flex-col flex-grow">
                            <h4 class="font-bold text-[#1c1b1b] text-base mb-1">{{ relBus.name }}</h4>
                            <p class="text-xs text-gray-500 mb-4">{{ relBus.bus_type }} • {{ relBus.capacity }} Seat</p>
                            <Link :href="route('frontend.fleet.show', relBus.id)"
                                class="w-full mt-auto py-2.5 bg-[#f6f3f2] hover:bg-[#10207a] hover:text-white text-[#10207a] rounded-lg font-bold text-xs text-center transition-colors">
                                Lihat Detail
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
