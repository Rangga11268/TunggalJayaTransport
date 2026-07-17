<script setup>
import { Head, useForm, usePage } from "@inertiajs/vue3";
import FrontendLayout from "@/Layouts/FrontendLayout.vue";
import { computed, ref } from "vue";

defineOptions({ layout: FrontendLayout });

const props = defineProps({
    schedule: Object,
    selectedDate: String,
});

// Pre-fill from logged-in user profile
const authUser = usePage().props.auth?.user;

const form = useForm({
    schedule_id: props.schedule.id,
    date: props.selectedDate || "",
    passenger_name: authUser?.name || "",
    passenger_email: authUser?.email || "",
    passenger_phone: authUser?.phone || "",
    number_of_seats: 1,
    terms: false,
});

const submit = () => {
    form.post(route("frontend.booking.store"), {
        preserveScroll: true,
    });
};

const formattedDate = computed(() => {
    const rawDate = props.selectedDate || props.schedule.departure_time;
    if (!rawDate) return "Tanggal Belum Tersedia";
    const date = new Date(rawDate);
    if (isNaN(date.getTime()) || date.getFullYear() <= 1970)
        return "Tanggal Belum Tersedia";

    return date.toLocaleDateString("id-ID", {
        weekday: "long",
        year: "numeric",
        month: "long",
        day: "numeric",
    });
});

const totalPrice = computed(() => {
    return props.schedule.price * form.number_of_seats;
});

const formatCurrency = (value) => {
    return new Intl.NumberFormat("id-ID", {
        style: "currency",
        currency: "IDR",
        minimumFractionDigits: 0,
    }).format(value);
};

// Modal Refs
const showTermsModal = ref(false);
const showPrivacyModal = ref(false);

const formatTime = (dateString) => {
    if (!dateString) return "";
    if (dateString.length === 5 && dateString.includes(":")) return dateString;

    const date = new Date(dateString);
    return date
        .toLocaleTimeString("id-ID", {
            hour: "2-digit",
            minute: "2-digit",
            hour12: false,
        })
        .replace(".", ":");
};

const busName = computed(() => {
    return props.schedule?.bus?.name || "TUNGGAL JAYA";
});

const busType = computed(() => {
    return props.schedule?.bus?.bus_type || "EXECUTIVE";
});
</script>

<template>
    <Head title="Isi Data Penumpang - Tunggal Jaya Transport" />

    <div class="bg-gray-50 dark:bg-[#050505] min-h-screen text-gray-900 dark:text-white relative pb-24 font-sans selection:bg-blue-600 selection:text-white">
        
        <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8 pt-[140px] pb-12">
            
            <!-- Step Indicator -->
            <div class="flex items-center justify-center w-full mb-16 flex-wrap gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 rounded-full bg-blue-600 flex items-center justify-center text-white font-black text-sm shadow-lg shadow-blue-600/30">1</div>
                    <span class="font-black text-blue-600 text-sm tracking-widest uppercase font-unbounded">Info Penumpang</span>
                </div>
                <div class="w-16 h-px bg-blue-600/20"></div>
                <div class="flex items-center gap-4 opacity-50">
                    <div class="w-10 h-10 rounded-full border-2 border-gray-300 dark:border-white/20 flex items-center justify-center font-bold text-sm">2</div>
                    <span class="font-bold text-gray-400 text-sm tracking-widest uppercase font-unbounded">Pilih Kursi</span>
                </div>
                <div class="w-16 h-px bg-gray-200 dark:bg-white/10"></div>
                <div class="flex items-center gap-4 opacity-50">
                    <div class="w-10 h-10 rounded-full border-2 border-gray-300 dark:border-white/20 flex items-center justify-center font-bold text-sm">3</div>
                    <span class="font-bold text-gray-400 text-sm tracking-widest uppercase font-unbounded">Pembayaran</span>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
                
                <!-- LEFT COLUMN: Passenger Form -->
                <div class="lg:col-span-8 flex flex-col gap-6">
                    <div class="bg-white dark:bg-[#111] rounded-[2rem] p-10 border border-gray-100 dark:border-white/5 shadow-xl shadow-blue-900/5 relative overflow-hidden">
                        <!-- Decorative Blob -->
                        <div class="absolute -top-24 -right-24 w-64 h-64 bg-blue-50 dark:bg-blue-900/10 rounded-full blur-3xl -z-10"></div>

                        <h2 class="font-unbounded text-2xl font-black text-gray-900 dark:text-white mb-2 tracking-tight">Data Penumpang</h2>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-10">Pastikan data yang diisi sesuai dengan identitas (KTP/SIM) penumpang yang berangkat.</p>
                        
                        <form @submit.prevent="submit" class="flex flex-col gap-8">
                            
                            <div class="flex flex-col gap-3 relative">
                                <label class="font-bold text-gray-700 dark:text-gray-300 text-[11px] tracking-widest uppercase font-unbounded">Nama Lengkap (Sesuai KTP)</label>
                                <div class="relative group">
                                    <div class="absolute left-0 top-1/2 -translate-y-1/2 text-gray-400 pl-5 group-focus-within:text-blue-600 transition-colors">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <input v-model="form.passenger_name" type="text" required placeholder="Contoh: Budi Santoso" class="w-full bg-gray-50 dark:bg-black/50 border border-gray-200 dark:border-white/10 rounded-2xl pl-12 pr-5 py-4 font-semibold text-gray-900 dark:text-white focus:bg-white focus:dark:bg-black focus:border-blue-500 focus:ring-0 transition-all">
                                </div>
                                <p v-if="form.errors.passenger_name" class="text-red-500 text-xs mt-1 font-bold">{{ form.errors.passenger_name }}</p>
                            </div>

                            <div class="flex flex-col gap-3 relative">
                                <label class="font-bold text-gray-700 dark:text-gray-300 text-[11px] tracking-widest uppercase font-unbounded">Email</label>
                                <div class="relative group">
                                    <div class="absolute left-0 top-1/2 -translate-y-1/2 text-gray-400 pl-5 group-focus-within:text-blue-600 transition-colors">
                                        <i class="fas fa-envelope"></i>
                                    </div>
                                    <input v-model="form.passenger_email" type="email" required pattern="[a-zA-Z0-9._%+\-]+@[a-zA-Z0-9.\-]+\.[a-zA-Z]{2,}$" @blur="form.passenger_email = form.passenger_email.trim().toLowerCase()" placeholder="contoh@email.com" class="w-full bg-gray-50 dark:bg-black/50 border border-gray-200 dark:border-white/10 rounded-2xl pl-12 pr-5 py-4 font-semibold text-gray-900 dark:text-white focus:bg-white focus:dark:bg-black focus:border-blue-500 focus:ring-0 transition-all">
                                </div>
                                <p v-if="form.errors.passenger_email" class="text-red-500 text-xs mt-1 font-bold">{{ form.errors.passenger_email }}</p>
                            </div>

                            <div class="flex flex-col gap-3 relative">
                                <label class="font-bold text-gray-700 dark:text-gray-300 text-[11px] tracking-widest uppercase font-unbounded">Nomor Telepon / WhatsApp</label>
                                <div class="relative group">
                                    <div class="absolute left-0 top-1/2 -translate-y-1/2 text-gray-400 pl-5 group-focus-within:text-blue-600 transition-colors">
                                        <i class="fas fa-phone"></i>
                                    </div>
                                    <input v-model="form.passenger_phone" type="tel" required pattern="(\+62|0)[0-9]{9,12}" @input="form.passenger_phone = form.passenger_phone.replace(/[^0-9+]/g, '')" placeholder="08xxxxxxxxxx atau +62xxx" class="w-full bg-gray-50 dark:bg-black/50 border border-gray-200 dark:border-white/10 rounded-2xl pl-12 pr-5 py-4 font-semibold text-gray-900 dark:text-white focus:bg-white focus:dark:bg-black focus:border-blue-500 focus:ring-0 transition-all">
                                </div>
                                <p v-if="form.errors.passenger_phone" class="text-red-500 text-xs mt-1 font-bold">{{ form.errors.passenger_phone }}</p>
                            </div>

                            <div class="flex flex-col gap-3 relative">
                                <label class="font-bold text-gray-700 dark:text-gray-300 text-[11px] tracking-widest uppercase font-unbounded">Jumlah Kursi</label>
                                <div class="relative group">
                                    <div class="absolute left-0 top-1/2 -translate-y-1/2 text-gray-400 pl-5 group-focus-within:text-blue-600 transition-colors">
                                        <i class="fas fa-users"></i>
                                    </div>
                                    <select v-model="form.number_of_seats" class="w-full bg-gray-50 dark:bg-black/50 border border-gray-200 dark:border-white/10 rounded-2xl pl-12 pr-10 py-4 font-semibold text-gray-900 dark:text-white focus:bg-white focus:dark:bg-black focus:border-blue-500 focus:ring-0 appearance-none cursor-pointer transition-all">
                                        <option v-for="n in 5" :key="n" :value="n">{{ n }} Kursi</option>
                                    </select>
                                    <i class="fas fa-chevron-down absolute right-5 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none text-xs"></i>
                                </div>
                                <p class="text-[11px] text-gray-500 mt-1"><i class="fas fa-info-circle mr-1"></i> Maksimal 5 kursi per pemesanan</p>
                            </div>

                            <div class="pt-6 border-t border-gray-100 dark:border-white/5">
                                <label class="flex items-start gap-4 cursor-pointer group">
                                    <input v-model="form.terms" type="checkbox" required class="mt-1 w-5 h-5 rounded border-gray-300 text-blue-600 focus:ring-blue-600 bg-gray-50">
                                    <span class="text-sm font-medium text-gray-600 dark:text-gray-400 leading-relaxed group-hover:text-gray-900 dark:group-hover:text-white transition-colors">
                                        Saya setuju dengan 
                                        <button type="button" @click.prevent="showTermsModal = true" class="text-blue-600 font-bold hover:underline">Syarat & Ketentuan</button> 
                                        serta 
                                        <button type="button" @click.prevent="showPrivacyModal = true" class="text-blue-600 font-bold hover:underline">Kebijakan Privasi</button> 
                                        yang berlaku.
                                    </span>
                                </label>
                                <p v-if="form.errors.terms" class="text-red-500 text-xs mt-2 font-bold">{{ form.errors.terms }}</p>
                            </div>

                            <button type="submit" :disabled="form.processing" class="w-full bg-blue-600 text-white py-4 rounded-2xl font-black font-unbounded text-sm hover:bg-blue-700 transition-all disabled:opacity-50 mt-2 flex items-center justify-center gap-3 shadow-xl shadow-blue-600/30 active:scale-[0.98]">
                                <i v-if="form.processing" class="fas fa-spinner fa-spin"></i>
                                <span>{{ form.processing ? 'Memproses...' : 'Lanjut Pilih Kursi' }}</span>
                                <i class="fas fa-arrow-right ml-1"></i>
                            </button>
                        </form>
                    </div>
                </div>

                <!-- RIGHT COLUMN: Journey Summary -->
                <div class="lg:col-span-4 flex flex-col gap-6">
                    <div class="bg-blue-600 rounded-[2rem] p-8 flex flex-col gap-8 sticky top-32 shadow-2xl shadow-blue-900/30 text-white overflow-hidden relative">
                        <!-- Abstract decorative shapes -->
                        <div class="absolute -right-8 -top-8 w-32 h-32 bg-white/10 rounded-full blur-2xl"></div>
                        <div class="absolute -left-12 -bottom-12 w-48 h-48 bg-black/20 rounded-full blur-3xl"></div>

                        <div class="relative z-10">
                            <h3 class="font-black text-sm tracking-widest uppercase font-unbounded text-blue-200 mb-6">Ringkasan Perjalanan</h3>
                            
                            <div class="flex flex-col gap-1 border-b border-white/20 pb-6 mb-6">
                                <p class="font-bold text-2xl font-unbounded">{{ schedule.route.origin }}</p>
                                <div class="flex items-center gap-3 text-blue-300 py-1">
                                    <div class="w-1 h-1 rounded-full bg-white"></div>
                                    <div class="flex-1 h-px bg-gradient-to-r from-white/0 via-white/50 to-white/0"></div>
                                    <i class="fas fa-bus text-sm"></i>
                                    <div class="flex-1 h-px bg-gradient-to-r from-white/0 via-white/50 to-white/0"></div>
                                    <div class="w-1 h-1 rounded-full bg-white"></div>
                                </div>
                                <p class="font-bold text-2xl font-unbounded text-right">{{ schedule.route.destination }}</p>
                                <div class="mt-4 flex items-center gap-2 text-xs font-bold uppercase tracking-widest bg-white/10 w-fit px-3 py-1.5 rounded-lg border border-white/10">
                                    {{ busType }}
                                </div>
                            </div>

                            <!-- Time & Date -->
                            <div class="flex flex-col gap-4 mb-6">
                                <div class="flex items-start gap-4">
                                    <div class="w-10 h-10 rounded-xl bg-white/10 border border-white/20 flex items-center justify-center flex-shrink-0 mt-1">
                                        <i class="fas fa-calendar-day text-blue-200"></i>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-[11px] text-blue-200 font-bold uppercase tracking-widest">Tanggal</span>
                                        <span class="font-bold text-base">{{ formattedDate }}</span>
                                    </div>
                                </div>
                                <div class="flex items-start gap-4">
                                    <div class="w-10 h-10 rounded-xl bg-white/10 border border-white/20 flex items-center justify-center flex-shrink-0 mt-1">
                                        <i class="fas fa-clock text-blue-200"></i>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-[11px] text-blue-200 font-bold uppercase tracking-widest">Waktu</span>
                                        <span class="font-bold text-base">{{ formatTime(schedule.departure_time) }} - {{ formatTime(schedule.arrival_time) }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Price Breakdown -->
                            <div class="border-t border-white/20 pt-6 flex flex-col gap-4 bg-white/5 -mx-8 px-8 pb-8 -mb-8 mt-2">
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-blue-200">Harga per Kursi</span>
                                    <span class="font-bold">{{ formatCurrency(schedule.price) }}</span>
                                </div>
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-blue-200">Jumlah Penumpang</span>
                                    <span class="font-bold">{{ form.number_of_seats }}x</span>
                                </div>
                                <div class="flex justify-between items-end mt-2 pt-4 border-t border-white/10">
                                    <span class="font-bold text-xs uppercase tracking-widest text-blue-200">Total Harga</span>
                                    <span class="font-black text-2xl font-unbounded text-white">{{ formatCurrency(totalPrice) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Modals -->
    <Teleport to="body">
        <Transition name="modal">
            <div v-if="showTermsModal" class="fixed inset-0 z-[9999] flex items-center justify-center p-4">
                <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" @click="showTermsModal = false"></div>
                <div class="relative bg-white rounded-3xl max-w-2xl w-full max-h-[80vh] overflow-y-auto p-8 shadow-2xl border border-gray-200">
                    <button @click="showTermsModal = false" class="absolute top-4 right-4 w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center text-gray-500 hover:bg-[#f6f3f2] hover:text-[#1c1b1b] transition-colors">
                        <i class="fas fa-times"></i>
                    </button>
                    <h2 class=" font-unboundedfont-bold text-2xl text-[#1c1b1b] mb-6 uppercase">Syarat & Ketentuan</h2>
                    <div class="text-sm text-[#454652] space-y-4">
                        <p><strong>1. Pemesanan Tiket</strong><br />Tiket yang sudah dipesan dan dibayar tidak dapat dibatalkan atau di-refund. Penumpang wajib menyimpan kode booking sebagai bukti pemesanan.</p>
                        <p><strong>2. Keberangkatan</strong><br />Penumpang wajib hadir minimal 30 menit sebelum jam keberangkatan. Keterlambatan bukan tanggung jawab Tunggal Jaya Transport.</p>
                        <p><strong>3. Bagasi</strong><br />Setiap penumpang diperbolehkan membawa bagasi maksimal 20 kg. Barang berharga adalah tanggung jawab penumpang.</p>
                        <p><strong>4. Pembatalan oleh Operator</strong><br />Apabila terjadi pembatalan jadwal oleh operator, penumpang berhak mendapat pengembalian dana penuh atau reschedule tanpa biaya tambahan.</p>
                        <p><strong>5. Perilaku Penumpang</strong><br />Penumpang dilarang merokok di dalam bus (kecuali area yang disediakan), membawa barang berbahaya, atau mengganggu kenyamanan penumpang lain.</p>
                    </div>
                </div>
            </div>
        </Transition>

        <Transition name="modal">
            <div v-if="showPrivacyModal" class="fixed inset-0 z-[9999] flex items-center justify-center p-4">
                <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" @click="showPrivacyModal = false"></div>
                <div class="relative bg-white rounded-3xl max-w-2xl w-full max-h-[80vh] overflow-y-auto p-8 shadow-2xl border border-gray-200">
                    <button @click="showPrivacyModal = false" class="absolute top-4 right-4 w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center text-gray-500 hover:bg-[#f6f3f2] hover:text-[#1c1b1b] transition-colors">
                        <i class="fas fa-times"></i>
                    </button>
                    <h2 class=" font-unboundedfont-bold text-2xl text-[#1c1b1b] mb-6 uppercase">Kebijakan Privasi</h2>
                    <div class="text-sm text-[#454652] space-y-4">
                        <p><strong>1. Data yang Dikumpulkan</strong><br />Kami mengumpulkan data pribadi seperti nama, email, dan nomor telepon hanya untuk keperluan pemesanan tiket dan komunikasi terkait perjalanan.</p>
                        <p><strong>2. Penggunaan Data</strong><br />Data Anda digunakan untuk memproses pemesanan, mengirim konfirmasi tiket, dan memberikan informasi terkait jadwal perjalanan.</p>
                        <p><strong>3. Keamanan Data</strong><br />Kami menerapkan enkripsi dan protokol keamanan untuk melindungi data pribadi Anda dari akses tidak sah.</p>
                        <p><strong>4. Pihak Ketiga</strong><br />Data pembayaran diproses melalui gateway pembayaran (Midtrans) yang memiliki sertifikasi keamanan PCI-DSS.</p>
                        <p><strong>5. Hak Pengguna</strong><br />Anda berhak meminta penghapusan data pribadi dengan menghubungi tim support kami.</p>
                    </div>
                </div>
            </div>
        </Transition>
    </Teleport>
</template>

<style scoped>
.modal-enter-active,
.modal-leave-active {
    transition: opacity 0.3s ease;
}
.modal-enter-from,
.modal-leave-to {
    opacity: 0;
}
</style>
