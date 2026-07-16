<script setup>
import FrontendLayout from "@/Layouts/FrontendLayout.vue";
import { Head, useForm, Link } from "@inertiajs/vue3";
import { ref } from "vue";
import Swal from "sweetalert2";

const props = defineProps({
    auth: Object,
});

const form = useForm({
    pickup_date: "",
    return_date: "",
    pickup_location: "",
    destination: "",
    bus_type_requested: "Big Bus",
    notes: "",
});

const submit = () => {
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

    form.post(route("charter.store"), {
        preserveScroll: true,
        onSuccess: () => {
            Swal.fire({
                icon: 'success',
                title: 'Permintaan Terkirim!',
                text: 'Tim kami akan segera menghubungi Anda dengan penawaran harga.',
                confirmButtonColor: '#E11D48',
            });
            form.reset();
        },
    });
};
</script>

<template>
    <Head title="Sewa Pariwisata" />

    <FrontendLayout>
        <!-- Hero Section -->
        <div class="relative min-h-[60vh] flex items-center justify-center overflow-hidden">
            <div class="absolute inset-0 z-0 bg-gray-900">
                <img src="/img/heroImg.jpg" alt="Tunggal Jaya Pariwisata" class="w-full h-full object-cover object-center filter brightness-[0.35] contrast-125" />
                <div class="absolute inset-0 bg-gradient-to-t from-gray-50 via-transparent to-transparent dark:from-[#050505]"></div>
            </div>
            
            <div class="relative z-10 text-center px-4 max-w-4xl mx-auto mt-20">
                <span class="inline-block py-2 px-5 rounded-full bg-rose-600/30 text-rose-300 border border-rose-500/30 text-xs font-bold tracking-widest uppercase mb-6 backdrop-blur-md shadow-lg">
                    Layanan Eksklusif
                </span>
                <h1 class="text-5xl md:text-7xl font-black text-white font-unbounded mb-6 leading-tight drop-shadow-2xl">
                    Sewa Bus Pariwisata
                </h1>
                <p class="text-xl text-gray-200 mb-12 max-w-2xl mx-auto font-medium drop-shadow-md">
                    Armada eksklusif Tunggal Jaya Transport siap menemani perjalanan wisata Anda dengan kenyamanan dan keamanan tingkat tinggi.
                </p>
                <a href="#form-sewa" class="inline-flex items-center justify-center gap-3 px-10 py-5 bg-rose-600 hover:bg-rose-700 text-white rounded-full font-black text-lg transition-all hover:scale-105 active:scale-95 shadow-[0_10px_40px_-10px_rgba(225,29,72,0.8)]">
                    Pesan Sekarang <i class="fas fa-arrow-down"></i>
                </a>
            </div>
        </div>

        <div class="bg-gray-50 dark:bg-[#050505] min-h-screen">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 relative z-10" id="form-sewa">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-16">
                    <!-- Info Section -->
                    <div class="lg:col-span-5">
                        <h2 class="text-4xl font-black font-unbounded text-gray-900 dark:text-white mb-10 leading-tight">Keunggulan<br/><span class="text-rose-600">Armada Kami</span></h2>
                        
                        <div class="space-y-10">
                            <div class="flex gap-6 items-start group">
                                <div class="w-16 h-16 rounded-3xl bg-white dark:bg-[#111] border border-gray-100 dark:border-white/5 text-rose-600 flex items-center justify-center shrink-0 shadow-xl group-hover:bg-rose-600 group-hover:text-white transition-all duration-300">
                                    <i class="fas fa-bus text-2xl"></i>
                                </div>
                                <div>
                                    <h3 class="text-2xl font-black text-gray-900 dark:text-white mb-3 font-unbounded">Armada Terbaru</h3>
                                    <p class="text-gray-500 dark:text-gray-400 leading-relaxed font-medium">Semua bus kami adalah keluaran terbaru dengan fasilitas AC, TV, Karaoke, dan bagasi luas. Ada pilihan Big Bus dengan Leg Rest.</p>
                                </div>
                            </div>
                            
                            <div class="flex gap-6 items-start group">
                                <div class="w-16 h-16 rounded-3xl bg-white dark:bg-[#111] border border-gray-100 dark:border-white/5 text-rose-600 flex items-center justify-center shrink-0 shadow-xl group-hover:bg-rose-600 group-hover:text-white transition-all duration-300">
                                    <i class="fas fa-user-tie text-2xl"></i>
                                </div>
                                <div>
                                    <h3 class="text-2xl font-black text-gray-900 dark:text-white mb-3 font-unbounded">Kru Profesional</h3>
                                    <p class="text-gray-500 dark:text-gray-400 leading-relaxed font-medium">Pengemudi dan kernet berpengalaman, ramah, dan sangat menguasai berbagai rute pariwisata di Indonesia.</p>
                                </div>
                            </div>
                            
                            <div class="flex gap-6 items-start group">
                                <div class="w-16 h-16 rounded-3xl bg-white dark:bg-[#111] border border-gray-100 dark:border-white/5 text-rose-600 flex items-center justify-center shrink-0 shadow-xl group-hover:bg-rose-600 group-hover:text-white transition-all duration-300">
                                    <i class="fas fa-shield-alt text-2xl"></i>
                                </div>
                                <div>
                                    <h3 class="text-2xl font-black text-gray-900 dark:text-white mb-3 font-unbounded">Aman & Terawat</h3>
                                    <p class="text-gray-500 dark:text-gray-400 leading-relaxed font-medium">Perawatan rutin selalu kami lakukan sebelum dan sesudah perjalanan untuk menjamin keselamatan penumpang.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form Section -->
                    <div class="lg:col-span-7">
                        <div class="bg-white dark:bg-[#111] p-10 md:p-14 rounded-[3rem] border border-gray-100 dark:border-white/5 shadow-2xl">
                            <h3 class="text-3xl font-black font-unbounded text-gray-900 dark:text-white mb-2">Minta Penawaran Harga</h3>
                            <p class="text-gray-500 dark:text-gray-400 mb-10 font-medium">Lengkapi form berikut dan tim kami akan memberikan penawaran harga terbaik untuk perjalanan Anda.</p>
                            
                            <form @submit.prevent="submit" class="space-y-8">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                    <div class="space-y-3">
                                        <label class="block text-sm font-bold text-gray-900 dark:text-white uppercase tracking-wider">Tanggal Jemput <span class="text-rose-600">*</span></label>
                                        <div class="relative">
                                            <input v-model="form.pickup_date" type="date" required class="w-full pl-5 pr-12 py-4 bg-gray-50 dark:bg-black/50 border-2 border-gray-100 dark:border-white/5 rounded-2xl focus:ring-0 focus:border-rose-600 dark:focus:border-rose-600 dark:text-white font-medium outline-none transition-colors" />
                                        </div>
                                    </div>
                                    <div class="space-y-3">
                                        <label class="block text-sm font-bold text-gray-900 dark:text-white uppercase tracking-wider">Tanggal Selesai <span class="text-rose-600">*</span></label>
                                        <input v-model="form.return_date" type="date" required class="w-full pl-5 pr-12 py-4 bg-gray-50 dark:bg-black/50 border-2 border-gray-100 dark:border-white/5 rounded-2xl focus:ring-0 focus:border-rose-600 dark:focus:border-rose-600 dark:text-white font-medium outline-none transition-colors" />
                                    </div>
                                </div>

                                <div class="space-y-3">
                                    <label class="block text-sm font-bold text-gray-900 dark:text-white uppercase tracking-wider">Kota Penjemputan <span class="text-rose-600">*</span></label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                                            <i class="fas fa-map-marker-alt text-gray-400"></i>
                                        </div>
                                        <input v-model="form.pickup_location" type="text" placeholder="Misal: Jakarta Selatan" required class="w-full pl-12 pr-5 py-4 bg-gray-50 dark:bg-black/50 border-2 border-gray-100 dark:border-white/5 rounded-2xl focus:ring-0 focus:border-rose-600 dark:focus:border-rose-600 dark:text-white font-medium outline-none transition-colors" />
                                    </div>
                                </div>

                                <div class="space-y-3">
                                    <label class="block text-sm font-bold text-gray-900 dark:text-white uppercase tracking-wider">Kota / Tempat Tujuan <span class="text-rose-600">*</span></label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                                            <i class="fas fa-location-arrow text-gray-400"></i>
                                        </div>
                                        <input v-model="form.destination" type="text" placeholder="Misal: Bandung, Lembang" required class="w-full pl-12 pr-5 py-4 bg-gray-50 dark:bg-black/50 border-2 border-gray-100 dark:border-white/5 rounded-2xl focus:ring-0 focus:border-rose-600 dark:focus:border-rose-600 dark:text-white font-medium outline-none transition-colors" />
                                    </div>
                                </div>

                                <div class="space-y-3">
                                    <label class="block text-sm font-bold text-gray-900 dark:text-white uppercase tracking-wider">Pilih Tipe Bus <span class="text-rose-600">*</span></label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                                            <i class="fas fa-bus text-gray-400"></i>
                                        </div>
                                        <select v-model="form.bus_type_requested" class="w-full pl-12 pr-5 py-4 bg-gray-50 dark:bg-black/50 border-2 border-gray-100 dark:border-white/5 rounded-2xl focus:ring-0 focus:border-rose-600 dark:focus:border-rose-600 dark:text-white font-medium outline-none transition-colors appearance-none">
                                            <option value="Big Bus">Big Bus (50 Seat)</option>
                                            <option value="Big Bus (Leg Rest)">Big Bus VIP (Leg Rest)</option>
                                            <option value="Medium Bus">Medium Bus (31 Seat)</option>
                                        </select>
                                        <div class="absolute inset-y-0 right-0 pr-5 flex items-center pointer-events-none">
                                            <i class="fas fa-chevron-down text-gray-400"></i>
                                        </div>
                                    </div>
                                </div>

                                <div class="space-y-3">
                                    <label class="block text-sm font-bold text-gray-900 dark:text-white uppercase tracking-wider">Catatan Tambahan <span class="text-gray-400 normal-case">(Opsional)</span></label>
                                    <textarea v-model="form.notes" rows="4" placeholder="Misal: Minta disediakan bantal selimut, atau titik jemput spesifik..." class="w-full px-5 py-4 bg-gray-50 dark:bg-black/50 border-2 border-gray-100 dark:border-white/5 rounded-2xl focus:ring-0 focus:border-rose-600 dark:focus:border-rose-600 dark:text-white font-medium outline-none transition-colors resize-none"></textarea>
                                </div>

                                <div class="pt-4">
                                    <button type="submit" :disabled="form.processing" class="w-full py-5 bg-rose-600 hover:bg-rose-700 text-white rounded-2xl font-black text-lg font-unbounded transition-all hover:scale-[1.02] shadow-[0_10px_30px_-10px_rgba(225,29,72,0.6)] disabled:opacity-50 disabled:scale-100 flex items-center justify-center gap-3">
                                        <span v-if="form.processing"><i class="fas fa-spinner fa-spin"></i> Memproses...</span>
                                        <span v-else>Kirim Permintaan <i class="fas fa-paper-plane ml-2"></i></span>
                                    </button>
                                    <p class="text-sm text-center text-gray-400 mt-4 font-medium">
                                        <i class="fas fa-info-circle mr-1 text-gray-300"></i> Tidak ada biaya untuk meminta penawaran harga.
                                    </p>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </FrontendLayout>
</template>

