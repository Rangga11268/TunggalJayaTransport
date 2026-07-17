<script setup>
import FrontendLayout from "@/Layouts/FrontendLayout.vue";
import { Head, useForm, Link } from "@inertiajs/vue3";
import { ref } from "vue";
import Swal from "sweetalert2";

defineOptions({ layout: FrontendLayout });

const props = defineProps({
    auth: Object,
    pariwisataBuses: Array,
});

const urlParams = new URLSearchParams(window.location.search);

const form = useForm({
    pickup_date: urlParams.get('date') || "",
    pickup_time: urlParams.get('time') || "",
    return_date: "",
    pickup_location: urlParams.get('origin') || "",
    destination: urlParams.get('destination') || "",
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

    <div class="bg-[#fcf9f8] min-h-screen">
        <!-- Hero -->
        <div class="relative pt-28 pb-12 px-4 sm:px-6 lg:px-8 text-center">
            <span class="inline-block px-4 py-1.5 rounded-full bg-white border border-[#ebe7e7] text-[#10207a] text-[11px] font-bold tracking-widest uppercase mb-5 shadow-sm">
                Layanan Eksklusif
            </span>
            <h1 class="font-unbounded font-black text-4xl md:text-6xl text-[#1c1b1b] mb-4">
                Sewa Bus Pariwisata
            </h1>
            <p class="text-[#454652] text-[16px] max-w-2xl mx-auto">
                Armada eksklusif Tunggal Jaya Transport siap menemani perjalanan wisata Anda dengan kenyamanan dan keamanan tingkat tinggi.
            </p>
            <a href="#form-sewa" class="inline-flex items-center gap-2 mt-8 px-8 py-4 bg-[#10207a] text-white rounded-xl font-bold text-[15px] hover:bg-[#0c185e] transition-all shadow-lg shadow-[#10207a]/20">
                Pesan Sekarang <i class="fas fa-arrow-down"></i>
            </a>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-24" id="form-sewa">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-12">
                <!-- Info -->
                <div class="lg:col-span-5">
                    <h2 class="font-unbounded font-bold text-[#1c1b1b] text-[22px] sm:text-[24px] md:text-[28px] mb-8">Keunggulan<br/>Armada Kami</h2>
                    <div class="space-y-8">
                        <div class="flex gap-5 items-start">
                            <div class="w-14 h-14 rounded-xl bg-white border border-[#ebe7e7] flex items-center justify-center shrink-0 shadow-sm">
                                <i class="fas fa-bus text-lg text-[#10207a]"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-[#1c1b1b] text-[15px] sm:text-[16px] md:text-[18px] mb-1.5">Armada Terbaru</h3>
                                <p class="text-[#454652] text-[14px] leading-relaxed">Semua bus kami adalah keluaran terbaru dengan fasilitas AC, TV, Karaoke, dan bagasi luas. Ada pilihan Big Bus dengan Leg Rest.</p>
                            </div>
                        </div>
                        <div class="flex gap-5 items-start">
                            <div class="w-14 h-14 rounded-xl bg-white border border-[#ebe7e7] flex items-center justify-center shrink-0 shadow-sm">
                                <i class="fas fa-user-tie text-lg text-[#10207a]"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-[#1c1b1b] text-[15px] sm:text-[16px] md:text-[18px] mb-1.5">Kru Profesional</h3>
                                <p class="text-[#454652] text-[14px] leading-relaxed">Pengemudi dan kernet berpengalaman, ramah, dan sangat menguasai berbagai rute pariwisata di Indonesia.</p>
                            </div>
                        </div>
                        <div class="flex gap-5 items-start">
                            <div class="w-14 h-14 rounded-xl bg-white border border-[#ebe7e7] flex items-center justify-center shrink-0 shadow-sm">
                                <i class="fas fa-shield-alt text-lg text-[#10207a]"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-[#1c1b1b] text-[15px] sm:text-[16px] md:text-[15px] sm:text-[16px] md:text-[18px] mb-1.5">Aman & Terawat</h3>
                                <p class="text-[#454652] text-[14px] leading-relaxed">Perawatan rutin selalu kami lakukan sebelum dan sesudah perjalanan untuk menjamin keselamatan penumpang.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form -->
                <div class="lg:col-span-7">
                    <div class="bg-white border border-[#ebe7e7] rounded-[12px] p-8 md:p-10 shadow-sm">
                        <h3 class="font-unbounded font-bold text-[#1c1b1b] text-[24px] mb-1.5">Minta Penawaran Harga</h3>
                        <p class="text-[#454652] text-[14px] mb-8">Lengkapi form berikut dan tim kami akan memberikan penawaran harga terbaik untuk perjalanan Anda.</p>
                        
                        <form @submit.prevent="submit" class="space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                                <div>
                                    <label class="block text-sm font-bold text-[#1c1b1b] mb-1.5">Tanggal Jemput <span class="text-[#10207a]">*</span></label>
                                    <input v-model="form.pickup_date" type="date" required
                                        class="w-full px-4 py-3 bg-[#f6f3f2] border border-[#e5e2e1] focus:border-[#10207a] focus:bg-white focus:ring-0 rounded-[10px] text-[#1c1b1b] text-sm outline-none transition-all" />
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-[#1c1b1b] mb-1.5">Jam Jemput <span class="text-[#10207a]">*</span></label>
                                    <input v-model="form.pickup_time" type="time" required
                                        class="w-full px-4 py-3 bg-[#f6f3f2] border border-[#e5e2e1] focus:border-[#10207a] focus:bg-white focus:ring-0 rounded-[10px] text-[#1c1b1b] text-sm outline-none transition-all" />
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-[#1c1b1b] mb-1.5">Tanggal Selesai <span class="text-[#10207a]">*</span></label>
                                    <input v-model="form.return_date" type="date" required
                                        class="w-full px-4 py-3 bg-[#f6f3f2] border border-[#e5e2e1] focus:border-[#10207a] focus:bg-white focus:ring-0 rounded-[10px] text-[#1c1b1b] text-sm outline-none transition-all" />
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-[#1c1b1b] mb-1.5">Kota Penjemputan <span class="text-[#10207a]">*</span></label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                                        <i class="fas fa-map-marker-alt text-sm"></i>
                                    </div>
                                    <input v-model="form.pickup_location" type="text" placeholder="Misal: Jakarta Selatan" required
                                        class="w-full pl-10 pr-4 py-3 bg-[#f6f3f2] border border-[#e5e2e1] focus:border-[#10207a] focus:bg-white focus:ring-0 rounded-[10px] text-[#1c1b1b] text-sm outline-none transition-all" />
                                </div>
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
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-[#1c1b1b] mb-1.5">Pilih Bus <span class="text-[#10207a]">*</span></label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                                        <i class="fas fa-bus text-sm"></i>
                                    </div>
                                    <select v-model="form.bus_type_requested"
                                        class="w-full pl-10 pr-10 py-3 bg-[#f6f3f2] border border-[#e5e2e1] focus:border-[#10207a] focus:bg-white focus:ring-0 rounded-[10px] text-[#1c1b1b] text-sm outline-none transition-all appearance-none">
                                        <option value="" disabled>Pilih bus...</option>
                                        <option v-for="bus in pariwisataBuses" :key="bus.id" :value="bus.name + ' - ' + bus.capacity + ' Seat'">
                                            {{ bus.name }} — {{ bus.capacity }} Kursi ({{ bus.bus_type }})
                                        </option>
                                    </select>
                                    <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-gray-400">
                                        <i class="fas fa-chevron-down text-sm"></i>
                                    </div>
                                </div>
                                <p v-if="!pariwisataBuses || pariwisataBuses.length === 0" class="text-xs text-amber-600 mt-1.5">
                                    <i class="fas fa-info-circle mr-1"></i> Belum ada bus pariwisata terdaftar. Hubungi admin.
                                </p>
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-[#1c1b1b] mb-1.5">Catatan Tambahan <span class="text-gray-400 font-normal">(Opsional)</span></label>
                                <textarea v-model="form.notes" rows="3" placeholder="Misal: Minta disediakan bantal selimut, atau titik jemput spesifik..."
                                    class="w-full px-4 py-3 bg-[#f6f3f2] border border-[#e5e2e1] focus:border-[#10207a] focus:bg-white focus:ring-0 rounded-[10px] text-[#1c1b1b] text-sm outline-none transition-all resize-none"></textarea>
                            </div>

                            <button type="submit" :disabled="form.processing"
                                class="w-full py-4 bg-[#10207a] text-white rounded-xl font-bold text-[15px] hover:bg-[#0c185e] transition-all shadow-lg shadow-[#10207a]/20 disabled:opacity-50 flex items-center justify-center gap-2">
                                <span v-if="form.processing"><i class="fas fa-spinner fa-spin"></i> Memproses...</span>
                                <span v-else>Kirim Permintaan <i class="fas fa-paper-plane"></i></span>
                            </button>
                            <p class="text-xs text-center text-gray-400 mt-3">
                                <i class="fas fa-info-circle mr-1"></i> Tidak ada biaya untuk meminta penawaran harga.
                            </p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

