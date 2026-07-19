<script setup>
import { Head, Link, router } from "@inertiajs/vue3";
import FrontendLayout from "@/Layouts/FrontendLayout.vue";
import { onMounted, ref, onUnmounted, computed } from "vue";
import Swal from "sweetalert2";

defineOptions({ layout: FrontendLayout });

const props = defineProps({
    charter: Object,
});

const timeLeft = ref("");
let timerInterval = null;

const formatCountdown = (ms) => {
    if (ms <= 0) return { text: "Waktu Habis", expired: true };
    const days = Math.floor(ms / (1000 * 60 * 60 * 24));
    const hours = Math.floor((ms % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    const minutes = Math.floor((ms % (1000 * 60 * 60)) / (1000 * 60));
    if (days > 0) return { text: `${days} hari ${hours} jam`, expired: false };
    if (hours > 0) return { text: `${hours} jam ${minutes} menit`, expired: false };
    return { text: `${minutes} menit`, expired: false };
};

onMounted(() => {
    // DP timer: 24 jam dari updated_at
    if (props.charter.status === 'quoted' && ['pending', 'unpaid'].includes(props.charter.payment_status)) {
        const updatedTime = new Date(props.charter.updated_at).getTime();
        const expiryTime = updatedTime + (24 * 60 * 60 * 1000);

        const updateTimer = () => {
            const now = new Date().getTime();
            const { text, expired } = formatCountdown(expiryTime - now);
            timeLeft.value = text;
            if (expired) { clearInterval(timerInterval); window.location.reload(); }
        };
        updateTimer();
        timerInterval = setInterval(updateTimer, 1000);
    }

    // Pelunasan timer: sampai pickup_date (harus lunas sebelum berangkat)
    if (props.charter.payment_status === 'dp_paid' && props.charter.pickup_date) {
        const deadline = new Date(props.charter.pickup_date).getTime();
        const updateTimer = () => {
            const { text, expired } = formatCountdown(pickupTime - Date.now());
            timeLeft.value = expired ? 'Jatuh tempo' : 'Lunas sebelum: ' + text;
            if (expired) clearInterval(timerInterval);
        };
        updateTimer();
        timerInterval = setInterval(updateTimer, 60000); // update per menit
    }
    // Load Midtrans Snap script if it's not loaded yet
    if (!document.getElementById("midtrans-script")) {
        const script = document.createElement("script");
        script.src = "https://app.sandbox.midtrans.com/snap/snap.js";
        script.setAttribute("data-client-key", "SB-Mid-client-0zM3qJg1B3d_g-yV");
        script.id = "midtrans-script";
        document.head.appendChild(script);
    }
});

onUnmounted(() => {
    if (timerInterval) clearInterval(timerInterval);
});

const formatDate = (dateString, includeTime = false) => {
    if (!dateString) return "-";
    const options = {
        year: "numeric",
        month: "long",
        day: "numeric",
        ...(includeTime && { hour: "2-digit", minute: "2-digit" }),
    };
    return new Date(dateString).toLocaleDateString("id-ID", options);
};

const formatCurrency = (value) => {
    return new Intl.NumberFormat("id-ID", {
        style: "currency",
        currency: "IDR",
        minimumFractionDigits: 0,
    }).format(value);
};

const payCharter = async (type) => {
    try {
        const response = await fetch(route('charter-bookings.pay', props.charter.id), {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
            },
            body: JSON.stringify({ type: type, payment_method: 'gopay' })
        });
        
        const data = await response.json();
        
        if (data.status === 'success' && data.snap_token) {
            window.snap.pay(data.snap_token, {
                onSuccess: function(result) {
                    fetch(route('charter-bookings.pay', props.charter.id), {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                        },
                        body: JSON.stringify({ type: 'verify', order_id: result.order_id })
                    }).then(() => {
                        Swal.fire({
                            icon: 'success',
                            title: 'Pembayaran Berhasil!',
                            text: 'Terima kasih, pembayaran Anda telah kami terima.',
                            confirmButtonColor: '#10B981',
                        }).then(() => {
                            window.location.reload();
                        });
                    }).catch(() => {
                        window.location.reload();
                    });
                },
                onPending: function(result) {
                    Swal.fire({
                        icon: 'info',
                        title: 'Menunggu Pembayaran',
                        text: 'Silakan selesaikan pembayaran Anda.',
                        confirmButtonColor: '#3B82F6',
                    }).then(() => window.location.reload());
                },
                onError: function(result) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Pembayaran Gagal',
                        text: 'Terjadi kesalahan saat memproses pembayaran.',
                        confirmButtonColor: '#EF4444',
                    });
                },
                onClose: function() {
                    console.log('Customer closed the popup without finishing the payment');
                }
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: data.message || 'Gagal memproses pembayaran.',
            });
        }
    } catch (error) {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Terjadi kesalahan sistem.',
        });
    }
};
</script>

<template>
    <Head :title="`Detail Sewa Bus ${charter.charter_code}`" />

    <div class="min-h-screen bg-[#fcf9f8] pt-28 pb-20 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto">
            <!-- Back -->
            <Link :href="route('booking-history.index')"
                class="inline-flex items-center text-[#454652] hover:text-[#10207a] transition-colors text-sm mb-8">
                <i class="fas fa-arrow-left mr-2"></i> Kembali ke Riwayat
            </Link>

            <!-- Main Card -->
            <div class="bg-white border border-[#ebe7e7] rounded-[12px] p-6 md:p-10 shadow-sm">
                
                <!-- Header -->
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-5 mb-10 pb-8 border-b border-[#ebe7e7]">
                    <div>
                        <span class="text-[10px] font-bold px-2.5 py-1 rounded-lg bg-[#f6f3f2] text-[#454652] uppercase tracking-wider border border-[#ebe7e7] mb-2 inline-block">
                            Sewa Pariwisata
                        </span>
                        <h1 class="text-2xl md:text-4xl font-bold text-[#1c1b1b] font-unbounded">{{ charter.charter_code }}</h1>
                    </div>
                    <div class="flex flex-col items-start md:items-end gap-2">
                        <span v-if="charter.payment_status === 'paid'"
                            class="px-4 py-1.5 rounded-full bg-emerald-50 text-emerald-700 text-xs font-bold border border-emerald-200 flex items-center gap-1.5">
                            <i class="fas fa-check-circle"></i> LUNAS
                        </span>
                        <span v-else-if="charter.payment_status === 'partial' || charter.payment_status === 'dp_paid'"
                            class="px-4 py-1.5 rounded-full bg-blue-50 text-blue-700 text-xs font-bold border border-blue-200 flex items-center gap-1.5">
                            <i class="fas fa-info-circle"></i> DP LUNAS
                        </span>
                        <span v-else-if="charter.status === 'cancelled' || timeLeft === 'Waktu Habis'"
                            class="px-4 py-1.5 rounded-full bg-red-50 text-red-700 text-xs font-bold border border-red-200 flex items-center gap-1.5">
                            <i class="fas fa-times-circle"></i> DIBATALKAN
                        </span>
                        <span v-else
                            class="px-4 py-1.5 rounded-full bg-amber-50 text-amber-700 text-xs font-bold border border-amber-200 flex items-center gap-1.5">
                            <i class="fas fa-hourglass-half"></i> {{ charter.payment_status ? charter.payment_status.toUpperCase() : 'BELUM BAYAR' }}
                        </span>
                        <span class="text-[11px] text-[#454652]">Dipesan {{ formatDate(charter.created_at) }}</span>
                    </div>
                </div>

                <!-- Grid -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 md:gap-12">
                    <!-- Journey -->
                    <div class="space-y-8">
                        <div>
                            <h3 class="text-xs font-bold text-[#454652] uppercase tracking-wider mb-5 flex items-center gap-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-[#10207a]"></span>
                                Rincian Perjalanan
                            </h3>

                            <div class="relative pl-7 space-y-8">
                                <div class="absolute left-[3px] top-2 bottom-2 w-[1px] bg-gradient-to-b from-[#10207a] via-[#ebe7e7] to-[#10207a]"></div>

                                <div class="relative">
                                    <div class="absolute -left-[26px] top-1 w-2 h-2 rounded-full bg-[#10207a] border-2 border-white"></div>
                                    <div class="text-[10px] font-bold text-[#454652] uppercase tracking-wider mb-0.5">Penjemputan</div>
                                    <div class="text-lg font-bold text-[#1c1b1b]">{{ charter.pickup_location }}</div>
                                    <div class="text-sm text-[#454652]">{{ formatDate(charter.pickup_date) }} • {{ charter.pickup_time || '-' }}</div>
                                </div>

                                <div class="relative">
                                    <div class="absolute -left-[26px] top-1 w-2 h-2 rounded-full bg-[#10207a] border-2 border-white"></div>
                                    <div class="text-[10px] font-bold text-[#454652] uppercase tracking-wider mb-0.5">Tujuan & Kepulangan</div>
                                    <div class="text-lg font-bold text-[#1c1b1b]">{{ charter.destination }}</div>
                                    <div class="text-sm text-[#454652]">{{ formatDate(charter.return_date) }}</div>
                                </div>
                            </div>
                        </div>

                        <!-- Bus Info -->
                        <div class="grid grid-cols-1 gap-5 pt-6 border-t border-[#ebe7e7]">
                            <div>
                                <div class="text-[10px] font-bold text-[#454652] uppercase tracking-wider mb-1">Armada Yang Diminta</div>
                                <div class="text-sm font-bold text-[#1c1b1b]">{{ charter.bus_type_requested || 'Bebas' }} ({{ charter.passenger_count || '-' }} Penumpang)</div>
                            </div>
                            <div v-if="charter.assigned_bus">
                                <div class="text-[10px] font-bold text-[#454652] uppercase tracking-wider mb-1">Armada Ditugaskan</div>
                                <div class="text-sm font-bold text-[#1c1b1b]">{{ charter.assigned_bus.name }} ({{ charter.assigned_bus.plate_number }})</div>
                                <div class="text-xs text-[#10207a] font-semibold">{{ charter.assigned_bus.bus_type }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Passenger & Price -->
                    <div class="bg-[#fcf9f8] border border-[#ebe7e7] rounded-[12px] p-6 md:p-8 space-y-8 h-fit">
                        <div>
                            <h3 class="text-xs font-bold text-[#454652] uppercase tracking-wider mb-5">Detail Harga</h3>
                            
                            <div v-if="charter.status === 'pending' && !charter.total_price">
                                <p class="text-sm text-gray-500 italic">Harga sedang dihitung oleh Admin. Kami akan mengabari Anda melalui WhatsApp segera setelah harga tersedia.</p>
                            </div>
                            
                            <div class="space-y-4" v-else>
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-[#454652]">Total Harga Sewa</span>
                                    <span class="font-bold text-[#1c1b1b]">{{ formatCurrency(charter.total_price) }}</span>
                                </div>
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-[#454652]">Wajib DP (Uang Muka)</span>
                                    <span class="font-bold text-[#1c1b1b]">{{ formatCurrency(charter.down_payment) }}</span>
                                </div>
                                <div class="flex justify-between items-center text-sm text-brand-red font-bold">
                                    <span>Sisa Pelunasan</span>
                                    <span>{{ formatCurrency(charter.total_price - charter.down_payment) }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div v-if="charter.status === 'cancelled' || timeLeft === 'Waktu Habis'" class="pt-6 border-t border-[#ebe7e7]">
                            <div class="bg-red-50 border border-red-200 text-red-800 rounded-xl p-4 text-sm font-bold flex items-center justify-center gap-2">
                                <i class="fas fa-times-circle text-lg"></i> 
                                Pemesanan Dibatalkan
                            </div>
                            <p class="text-xs text-center text-red-600 mt-2">Batas waktu pembayaran (24 jam) telah habis.</p>
                        </div>
                        <div v-else-if="charter.total_price > 0 && charter.payment_status !== 'paid'" class="pt-6 border-t border-[#ebe7e7]">
                            <div v-if="charter.payment_method === 'manual'" class="bg-blue-50 border border-blue-200 text-blue-800 rounded-xl p-4 text-sm">
                                <p class="font-bold mb-2"><i class="fas fa-info-circle mr-1"></i> Pembayaran Manual</p>
                                <p class="mb-4">Silakan lakukan pembayaran sesuai kesepakatan dan kirimkan bukti transfer ke WhatsApp Admin kami. <br><br><strong>Catatan:</strong> Pembayaran DP maksimal 1x24 Jam setelah tagihan dibuat. Jika melewati batas waktu, pesanan otomatis hangus.</p>
                                <div v-if="timeLeft && ['pending', 'unpaid'].includes(charter.payment_status)" class="mb-4 text-brand-red font-bold flex items-center gap-2">
                                    <i class="far fa-clock"></i> Sisa Waktu: {{ timeLeft }}
                                </div>
                                <a :href="'https://wa.me/628123456789?text=Halo%20Admin,%20saya%20ingin%20konfirmasi%20pembayaran%20sewa%20bus%20' + charter.charter_code" target="_blank" class="block w-full text-center py-2 bg-emerald-500 text-white font-bold rounded-lg hover:bg-emerald-600 transition-colors">
                                    <i class="fab fa-whatsapp mr-1"></i> Konfirmasi ke Admin
                                </a>
                            </div>
                            
                            <div v-else class="space-y-3">
                                <div v-if="charter.payment_status !== 'partial' && charter.payment_status !== 'dp_paid'">
                                    <div class="bg-amber-50 border border-amber-200 text-amber-800 rounded-xl p-3 mb-4 text-xs">
                                        <strong>Penting:</strong> Batas waktu pembayaran DP adalah 1x24 jam sejak harga diberikan. Jika tidak dibayar, pesanan akan dibatalkan otomatis.
                                        <div v-if="timeLeft" class="mt-2 text-brand-red font-bold text-sm flex items-center gap-1.5">
                                            <i class="far fa-clock"></i> Sisa Waktu: {{ timeLeft }}
                                        </div>
                                    </div>
                                    <button @click="payCharter('dp')" class="w-full py-3 bg-[#10207a] text-white rounded-xl font-bold text-sm hover:bg-[#0c185e] transition-all flex items-center justify-center gap-2" :disabled="timeLeft === 'Waktu Habis'">
                                        Bayar DP ({{ formatCurrency(charter.down_payment) }})
                                    </button>
                                    <button @click="payCharter('full')" class="w-full py-3 mt-3 bg-gray-100 text-[#10207a] border border-[#10207a]/20 rounded-xl font-bold text-sm hover:bg-gray-200 transition-all flex items-center justify-center gap-2" :disabled="timeLeft === 'Waktu Habis'">
                                        Bayar Lunas ({{ formatCurrency(charter.total_price) }})
                                    </button>
                                </div>
                                <div v-else>
                                    <div class="bg-amber-50 border border-amber-200 text-amber-800 rounded-xl p-3 mb-4 text-xs">
                                        <strong>Penting:</strong> Pelunasan harus dilakukan sebelum keberangkatan ({{ formatDate(charter.pickup_date) }}). Jika melewati batas, pesanan dapat dibatalkan.
                                        <div v-if="timeLeft" class="mt-2 text-brand-red font-bold text-sm flex items-center gap-1.5">
                                            <i class="far fa-clock"></i> {{ timeLeft }}
                                        </div>
                                    </div>
                                    <button @click="payCharter('pelunasan')" class="w-full py-3 bg-[#10207a] text-white rounded-xl font-bold text-sm hover:bg-[#0c185e] transition-all flex items-center justify-center gap-2">
                                        Bayar Pelunasan ({{ formatCurrency(charter.total_price - charter.down_payment) }})
                                    </button>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>
</template>
