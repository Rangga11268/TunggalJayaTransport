<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import InputError from "@/Components/InputError.vue";
import RouteMap from "@/Components/RouteMap.vue";
import { ref } from "vue";

const props = defineProps({
    charter: Object,
    buses: Array,
});

const form = useForm({
    total_price: props.charter.total_price || '',
    down_payment: props.charter.down_payment || '',
    assigned_bus_ids: props.charter.buses ? props.charter.buses.map(b => b.id) : [],
    status: props.charter.status || 'pending',
    payment_method: props.charter.payment_method || 'manual',
    payment_status: props.charter.payment_status || "unpaid",
    payment_proof: null,
    _method: 'put',
});

const formatRupiahString = (value) => {
    if (!value && value !== 0) return '';
    let strVal = value.toString();
    // if value from db has decimal .00, remove it before parsing digits
    if (strVal.includes('.')) {
        strVal = strVal.split('.')[0];
    }
    const numberString = strVal.replace(/[^,\d]/g, '');
    const split = numberString.split(',');
    const sisa = split[0].length % 3;
    let rupiah = split[0].substr(0, sisa);
    const ribuan = split[0].substr(sisa).match(/\d{3}/gi);

    if (ribuan) {
        const separator = sisa ? '.' : '';
        rupiah += separator + ribuan.join('.');
    }
    return rupiah || '0';
};

const formattedPrice = ref(formatRupiahString(form.total_price));
const formattedDp = ref(formatRupiahString(form.down_payment));

const onPriceChange = (event) => {
    const val = event.target.value.replace(/[^0-9]/g, '');
    const numVal = val ? parseInt(val) : 0;
    
    form.total_price = numVal;
    formattedPrice.value = formatRupiahString(numVal);
    
    // Auto-calculate 30% DP when total price changes
    const autoDp = Math.floor(numVal * 0.3);
    form.down_payment = autoDp;
    formattedDp.value = formatRupiahString(autoDp);
};

const onDpChange = (event) => {
    const val = event.target.value.replace(/[^0-9]/g, '');
    form.down_payment = val ? parseInt(val) : 0;
    formattedDp.value = formatRupiahString(val);
};

const submit = () => {
    form.post(route("admin.charter-bookings.update", props.charter.id), {
        preserveScroll: true,
    });
};

const formatDate = (dateString) => {
    if (!dateString) return "-";
    return new Date(dateString).toLocaleDateString("id-ID", {
        day: "numeric",
        month: "short",
        year: "numeric"
    });
};

const formatRupiah = (value) => {
    if (!value) return "Rp 0";
    return new Intl.NumberFormat("id-ID", { style: "currency", currency: "IDR", minimumFractionDigits: 0 }).format(value);
};
</script>

<template>
    <Head title="Detail Pariwisata" />

    <AdminLayout title="Detail Pariwisata">
        <div class="mb-6 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <Link
                    :href="route('admin.charter-bookings.index')"
                    class="w-10 h-10 rounded-xl bg-white dark:bg-[#151515] border border-gray-200 dark:border-white/10 flex items-center justify-center text-gray-500 hover:text-brand-red transition-colors"
                >
                    <i class="fas fa-arrow-left"></i>
                </Link>
                <div>
                    <h2 class="text-2xl font-bold font-unbounded text-gray-900 dark:text-white">
                        Order #{{ charter.charter_code }}
                    </h2>
                    <p class="text-sm text-gray-500">Dipesan oleh {{ charter.user?.name }} pada {{ formatDate(charter.created_at) }}</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Order Information -->
            <div class="lg:col-span-2 space-y-6">
                <!-- User Information -->
                <div class="bg-white dark:bg-[#151515] rounded-2xl border border-gray-100 dark:border-white/5 p-6 shadow-sm">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Informasi Pemesan</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Nama Pemesan</p>
                            <p class="font-medium text-gray-900 dark:text-white">{{ charter.user?.name || '-' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Nomor HP / WhatsApp</p>
                            <p class="font-medium text-gray-900 dark:text-white">
                                <a v-if="charter.user?.phone" :href="'https://wa.me/' + charter.user.phone.replace(/[^0-9]/g, '').replace(/^0/, '62')" target="_blank" class="text-emerald-600 hover:underline flex items-center gap-1.5">
                                    <i class="fab fa-whatsapp"></i> {{ charter.user.phone }}
                                </a>
                                <span v-else>-</span>
                            </p>
                        </div>
                        <div class="md:col-span-2">
                            <p class="text-sm text-gray-500 mb-1">Email</p>
                            <p class="font-medium text-gray-900 dark:text-white">{{ charter.user?.email || '-' }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-[#151515] rounded-2xl border border-gray-100 dark:border-white/5 p-6 shadow-sm">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Informasi Perjalanan</h3>
                    
                    <div class="mb-6 h-[300px] w-full rounded-xl overflow-hidden border border-gray-200 dark:border-white/10" v-if="charter.pickup_lat && charter.destination_lat">
                        <RouteMap 
                            :origin="charter.pickup_location"
                            :originLat="charter.pickup_lat"
                            :originLng="charter.pickup_lng"
                            :destination="charter.destination"
                            :destinationLat="charter.destination_lat"
                            :destinationLng="charter.destination_lng"
                        />
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Tipe & Jumlah Bus Diminta</p>
                            <p class="font-medium text-gray-900 dark:text-white mb-2">{{ charter.bus_type_requested }} <span class="text-brand-red font-bold ml-1">({{ charter.bus_count }} Unit)</span></p>
                            
                            <p class="text-sm text-gray-500 mb-1">Armada Ditetapkan</p>
                            <p class="font-medium text-gray-900 dark:text-white text-sm mb-4">
                                <span v-if="charter.buses && charter.buses.length > 0">
                                    <span v-for="(bus, index) in charter.buses" :key="bus.id" class="inline-block bg-gray-100 dark:bg-gray-800 rounded px-2 py-1 mr-1 mb-1 text-xs">
                                        {{ bus.name }} ({{ bus.plate_number }})
                                    </span>
                                </span>
                                <span v-else class="text-yellow-600">Belum ada armada ditetapkan</span>
                            </p>

                            <p class="text-sm text-gray-500 mb-1">Titik Jemput (Kota)</p>
                            <p class="font-medium text-gray-900 dark:text-white mb-2">{{ charter.pickup_location }}</p>
                            
                            <p class="text-sm text-gray-500 mb-1">Alamat Lengkap Jemput</p>
                            <p class="font-medium text-gray-900 dark:text-white text-sm mb-4">{{ charter.pickup_address || '-' }}</p>
                            
                            <p class="text-sm text-gray-500 mt-2 mb-1">Tanggal Jemput</p>
                            <p class="font-medium text-gray-900 dark:text-white mb-2">{{ formatDate(charter.pickup_date) }} <span class="text-gray-500">({{ charter.pickup_time || '-' }})</span></p>
                            
                            <p class="text-sm text-gray-500 mt-2 mb-1">Jumlah Penumpang</p>
                            <p class="font-medium text-gray-900 dark:text-white">{{ charter.passenger_count ? charter.passenger_count + ' Orang' : '-' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Tujuan (Kota)</p>
                            <p class="font-medium text-gray-900 dark:text-white mb-2">{{ charter.destination }}</p>
                            <p class="text-sm text-gray-500 mb-1">Alamat Lengkap Tujuan</p>
                            <p class="font-medium text-gray-900 dark:text-white text-sm mb-4">{{ charter.destination_address || '-' }}</p>

                            <p class="text-sm text-gray-500 mt-2 mb-1">Tanggal Pulang</p>
                            <p class="font-medium text-gray-900 dark:text-white">{{ formatDate(charter.return_date) }}</p>
                        </div>
                    </div>
                    
                    <div class="mt-6 p-4 bg-gray-50 dark:bg-white/[0.02] rounded-xl border border-gray-100 dark:border-white/5">
                        <p class="text-sm text-gray-500 mb-1">Catatan Tambahan:</p>
                        <p class="text-sm text-gray-900 dark:text-gray-300 whitespace-pre-line">{{ charter.notes || "Tidak ada catatan." }}</p>
                    </div>
                </div>
            </div>

            <!-- Management Form -->
            <div class="bg-white dark:bg-[#151515] rounded-2xl border border-gray-100 dark:border-white/5 p-6 shadow-sm self-start">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Manajemen Pesanan</h3>
                
                <form @submit.prevent="submit" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tipe & Jumlah Bus Diminta</label>
                        <input type="text" disabled :value="charter.bus_type_requested + ' (' + charter.bus_count + ' Unit)'" class="w-full px-4 py-2 bg-gray-50 dark:bg-black/50 border border-gray-200 dark:border-gray-700 rounded-xl text-gray-500 text-sm" />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Total Harga Sewa (Rp)</label>
                        <input :value="formattedPrice" @input="onPriceChange" type="text" class="w-full px-4 py-2 bg-white dark:bg-[#151515] border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-brand-red focus:border-brand-red text-sm dark:text-white" />
                        <p class="text-xs text-gray-500 mt-1">Isi harga untuk memberikan penawaran ke pelanggan.</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Wajib DP (Rp)</label>
                        <input :value="formattedDp" @input="onDpChange" type="text" class="w-full px-4 py-2 bg-white dark:bg-[#151515] border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-brand-red focus:border-brand-red text-sm dark:text-white" />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Pilih Armada (Bisa lebih dari 1)</label>
                        <select multiple v-model="form.assigned_bus_ids" class="w-full px-4 py-2 bg-white dark:bg-[#151515] border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-brand-red focus:border-brand-red text-sm dark:text-white min-h-[120px]">
                            <option v-for="bus in buses" :key="bus.id" :value="bus.id">
                                {{ bus.plate_number }} - {{ bus.name }} ({{ bus.bus_type }})
                            </option>
                        </select>
                        <p class="text-xs text-gray-500 mt-1">Tahan tombol Ctrl (Windows) atau Command (Mac) untuk memilih lebih dari 1 armada.</p>
                        <InputError :message="form.errors.assigned_bus_ids" class="mt-2" />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status Sewa</label>
                        <select v-model="form.status" class="w-full px-4 py-2 bg-white dark:bg-[#151515] border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-brand-red focus:border-brand-red text-sm dark:text-white">
                            <option value="pending">Menunggu Harga</option>
                            <option value="quoted">Sudah Diberi Harga (Menunggu DP)</option>
                            <option value="confirmed">Dikonfirmasi (Sudah DP/Lunas)</option>
                            <option value="completed">Selesai Berjalan</option>
                            <option value="cancelled">Dibatalkan</option>
                        </select>
                    </div>

                    <div class="pt-4 mt-4 border-t border-gray-100 dark:border-gray-800">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tipe Pembayaran</label>
                        <select v-model="form.payment_method" class="w-full px-4 py-2 bg-white dark:bg-[#151515] border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-brand-red focus:border-brand-red text-sm dark:text-white">
                            <option value="system">Sistem Otomatis (Midtrans)</option>
                            <option value="manual">Manual (Transfer ke Admin)</option>
                        </select>
                        <p v-if="form.payment_method === 'system'" class="text-xs text-gray-500 mt-1">Status pembayaran otomatis diperbarui saat user membayar DP/Pelunasan via Midtrans.</p>
                    </div>

                    <div v-if="form.payment_method === 'manual'">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status Pembayaran (Manual)</label>
                        <select v-model="form.payment_status" class="w-full px-4 py-2 bg-white dark:bg-[#151515] border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-brand-red focus:border-brand-red text-sm dark:text-white">
                            <option value="unpaid">Belum Dibayar</option>
                            <option value="dp_paid">DP Lunas</option>
                            <option value="fully_paid">Lunas</option>
                            <option value="failed">Gagal</option>
                        </select>
                    </div>

                    <div v-if="form.payment_method === 'manual'">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Bukti Pembayaran Manual</label>
                        <input type="file" @change="e => form.payment_proof = e.target.files[0]" class="w-full px-4 py-2 bg-white dark:bg-[#151515] border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-brand-red focus:border-brand-red text-sm dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-red-50 file:text-brand-red hover:file:bg-red-100" />
                        <InputError :message="form.errors.payment_proof" class="mt-2" />
                        
                        <div v-if="charter.payment_proof" class="mt-3">
                            <p class="text-xs text-gray-500 mb-2">Bukti Saat Ini:</p>
                            <a :href="'/' + charter.payment_proof" target="_blank" class="block rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700 max-w-[200px]">
                                <img :src="'/' + charter.payment_proof" alt="Bukti Pembayaran" class="w-full h-auto object-cover" />
                            </a>
                        </div>
                    </div>

                    <div class="pt-4 mt-4 border-t border-gray-100 dark:border-gray-800">
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="w-full py-2.5 px-4 bg-brand-red hover:bg-red-700 text-white rounded-xl font-medium transition-colors flex items-center justify-center gap-2"
                        >
                            <span v-if="form.processing"><i class="fas fa-spinner fa-spin"></i> Menyimpan...</span>
                            <span v-else>Simpan Perubahan</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AdminLayout>
</template>
