<script setup>
import { ref } from 'vue';
import { useForm, Link, Head } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Swal from 'sweetalert2';

defineOptions({ layout: AdminLayout });

const props = defineProps({
    users: Array,
    buses: Array,
});

const form = useForm({
    // User fields
    customer_name: '',
    customer_email: '',
    customer_phone: '',
    institution_name: '',

    // Booking fields
    bus_requests: [
        { type: 'Big Bus', count: 1, with_legrest: false, seat_configuration: 'Bebas' }
    ],
    assigned_bus_ids: [],
    passenger_count: '',
    pickup_date: '',
    pickup_time: '',
    return_date: '',
    pickup_location: '',
    destination: '',
    passenger_count: '',
    total_price: 0,
    down_payment: 0,
    status: 'completed',
    payment_status: 'paid',
    payment_method: 'manual',
    notes: '',
});

const submit = () => {


    form.post(route('admin.charter-bookings.store'), {
        preserveScroll: true,
        onSuccess: () => {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: 'Data sewa pariwisata manual berhasil ditambahkan.',
                showConfirmButton: false,
                timer: 1500
            });
        },
    });
};
</script>

<template>
    <Head title="Tambah Sewa Pariwisata" />

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900">Tambah Sewa Pariwisata Manual</h1>
            <p class="text-sm text-gray-500 mt-1">Masukkan data pesanan pariwisata yang sudah ada (offline)</p>
        </div>
        <Link :href="route('admin.charter-bookings.index')" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
            Kembali
        </Link>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <form @submit.prevent="submit" class="p-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Data Pelanggan -->
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4 border-b pb-2">1. Data Pelanggan</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap Pemesan <span class="text-red-500">*</span></label>
                            <input type="text" v-model="form.customer_name" required class="w-full rounded-lg border-gray-300 focus:border-brand-red focus:ring-brand-red" />
                            <div v-if="form.errors.customer_name" class="text-red-500 text-xs mt-1">{{ form.errors.customer_name }}</div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Instansi / Organisasi <span class="text-gray-400 font-normal text-xs">(Opsional)</span></label>
                            <input type="text" v-model="form.institution_name" class="w-full rounded-lg border-gray-300 focus:border-brand-red focus:ring-brand-red" placeholder="Misal: SMAN 1 Jakarta" />
                            <div v-if="form.errors.institution_name" class="text-red-500 text-xs mt-1">{{ form.errors.institution_name }}</div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">No. WhatsApp <span class="text-red-500">*</span></label>
                            <input type="text" v-model="form.customer_phone" required class="w-full rounded-lg border-gray-300 focus:border-brand-red focus:ring-brand-red" />
                            <div v-if="form.errors.customer_phone" class="text-red-500 text-xs mt-1">{{ form.errors.customer_phone }}</div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email <span class="text-gray-400 font-normal text-xs">(Opsional)</span></label>
                            <input type="email" v-model="form.customer_email" class="w-full rounded-lg border-gray-300 focus:border-brand-red focus:ring-brand-red" />
                            <div v-if="form.errors.customer_email" class="text-red-500 text-xs mt-1">{{ form.errors.customer_email }}</div>
                        </div>
                    </div>
                </div>

                <!-- Data Armada & Perjalanan -->
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4 border-b pb-2">2. Data Armada & Perjalanan</h3>
                    
                    <div class="space-y-4">
                        <div v-for="(req, index) in form.bus_requests" :key="index" class="p-4 border border-gray-200 rounded-xl relative bg-gray-50">
                            <h4 class="font-bold text-gray-800 text-sm mb-3 border-b border-gray-200 pb-2">Konfigurasi Bus {{ index + 1 }}</h4>
                            <button v-if="form.bus_requests.length > 1" type="button" @click="form.bus_requests.splice(index, 1)" class="absolute top-4 right-4 text-red-500 hover:text-red-700">
                                <i class="fas fa-times"></i>
                            </button>
                            <div class="grid grid-cols-2 gap-4">
                                <div class="hidden">
                                    <input type="hidden" v-model="req.type" value="Big Bus" />
                                </div>
                                <div class="col-span-2">
                                    <label class="block text-xs font-medium text-gray-500 mb-1">Jumlah Unit (Dengan konfigurasi ini)</label>
                                    <input v-model="req.count" type="number" min="1" required class="w-full rounded-lg border-gray-300 focus:border-brand-red text-sm" />
                                </div>
                                <div class="col-span-2 grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="flex items-center cursor-pointer mt-6">
                                            <input type="checkbox" v-model="req.with_legrest" class="rounded border-gray-300 text-brand-red focus:ring-brand-red" />
                                            <span class="ml-2 text-sm text-gray-700">Pakai Leg Rest</span>
                                        </label>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-500 mb-1">Konfigurasi Kursi</label>
                                        <select v-model="req.seat_configuration" class="w-full rounded-lg border-gray-300 focus:border-brand-red text-sm">
                                            <option value="Bebas">Bebas (Standard)</option>
                                            <option value="2-2">2-2 (Kanan 2, Kiri 2)</option>
                                            <option value="3-2">3-2 (Kanan 3, Kiri 2)</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <button type="button" @click="form.bus_requests.push({ type: 'Big Bus', count: 1, with_legrest: false, seat_configuration: 'Bebas' })" class="w-full py-2 border border-dashed border-gray-300 text-gray-600 rounded-lg hover:bg-gray-50 text-sm">
                            <i class="fas fa-plus mr-1"></i> Tambah Konfigurasi Berbeda (Misal: Beda Fasilitas)
                        </button>

                        <div class="mt-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Total Penumpang Keseluruhan <span class="text-gray-400 font-normal text-xs">(Semua bus digabung)</span></label>
                            <input type="number" v-model="form.passenger_count" min="1" class="w-full rounded-lg border-gray-300 focus:border-brand-red focus:ring-brand-red mb-4" placeholder="Misal: 100" />
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Armada (Opsional, bisa lebih dari 1)</label>
                            <select multiple v-model="form.assigned_bus_ids" class="w-full rounded-lg border-gray-300 focus:border-brand-red focus:ring-brand-red min-h-[120px]">
                                <option v-for="bus in buses" :key="bus.id" :value="bus.id">
                                    {{ bus.name }} - {{ bus.capacity }} Seat ({{ bus.plate_number }})
                                </option>
                            </select>
                            <p class="text-xs text-gray-500 mt-1">Tahan tombol Ctrl (Windows) atau Command (Mac) untuk memilih lebih dari 1 armada.</p>
                            <div v-if="form.errors.assigned_bus_ids" class="text-red-500 text-xs mt-1">{{ form.errors.assigned_bus_ids }}</div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Jemput <span class="text-red-500">*</span></label>
                                <input type="date" v-model="form.pickup_date" required class="w-full rounded-lg border-gray-300 focus:border-brand-red focus:ring-brand-red" />
                                <div v-if="form.errors.pickup_date" class="text-red-500 text-xs mt-1">{{ form.errors.pickup_date }}</div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Jam Jemput <span class="text-red-500">*</span></label>
                                <input type="time" v-model="form.pickup_time" required class="w-full rounded-lg border-gray-300 focus:border-brand-red focus:ring-brand-red" />
                                <div v-if="form.errors.pickup_time" class="text-red-500 text-xs mt-1">{{ form.errors.pickup_time }}</div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Selesai <span class="text-red-500">*</span></label>
                            <input type="date" v-model="form.return_date" :min="form.pickup_date" required class="w-full rounded-lg border-gray-300 focus:border-brand-red focus:ring-brand-red" />
                            <div v-if="form.errors.return_date" class="text-red-500 text-xs mt-1">{{ form.errors.return_date }}</div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Lokasi Jemput <span class="text-red-500">*</span></label>
                                <input type="text" v-model="form.pickup_location" required class="w-full rounded-lg border-gray-300 focus:border-brand-red focus:ring-brand-red" />
                                <div v-if="form.errors.pickup_location" class="text-red-500 text-xs mt-1">{{ form.errors.pickup_location }}</div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Tujuan <span class="text-red-500">*</span></label>
                                <input type="text" v-model="form.destination" required class="w-full rounded-lg border-gray-300 focus:border-brand-red focus:ring-brand-red" />
                                <div v-if="form.errors.destination" class="text-red-500 text-xs mt-1">{{ form.errors.destination }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Data Pembayaran & Status -->
                <div class="lg:col-span-2">
                    <h3 class="text-lg font-medium text-gray-900 mb-4 border-b pb-2">3. Pembayaran & Status</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Harga Total (Rp) <span class="text-red-500">*</span></label>
                            <input type="number" v-model="form.total_price" required min="0" class="w-full rounded-lg border-gray-300 focus:border-brand-red focus:ring-brand-red" />
                            <div v-if="form.errors.total_price" class="text-red-500 text-xs mt-1">{{ form.errors.total_price }}</div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Sudah Dibayar (DP/Lunas) Rp</label>
                            <input type="number" v-model="form.down_payment" min="0" class="w-full rounded-lg border-gray-300 focus:border-brand-red focus:ring-brand-red" />
                            <div v-if="form.errors.down_payment" class="text-red-500 text-xs mt-1">{{ form.errors.down_payment }}</div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status Pembayaran <span class="text-red-500">*</span></label>
                            <select v-model="form.payment_status" required class="w-full rounded-lg border-gray-300 focus:border-brand-red focus:ring-brand-red">
                                <option value="unpaid">Belum Dibayar (Unpaid)</option>
                                <option value="dp_paid">DP Dibayar</option>
                                <option value="paid">Lunas (Paid)</option>
                            </select>
                            <div v-if="form.errors.payment_status" class="text-red-500 text-xs mt-1">{{ form.errors.payment_status }}</div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status Pesanan <span class="text-red-500">*</span></label>
                            <select v-model="form.status" required class="w-full rounded-lg border-gray-300 focus:border-brand-red focus:ring-brand-red">
                                <option value="confirmed">Dikonfirmasi (Confirmed)</option>
                                <option value="completed">Selesai (Completed)</option>
                            </select>
                            <div v-if="form.errors.status" class="text-red-500 text-xs mt-1">{{ form.errors.status }}</div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Catatan Tambahan</label>
                        <textarea v-model="form.notes" rows="3" class="w-full rounded-lg border-gray-300 focus:border-brand-red focus:ring-brand-red"></textarea>
                    </div>
                </div>
            </div>

            <div class="mt-8 pt-6 border-t flex justify-end">
                <button type="submit" :disabled="form.processing" class="px-6 py-2 bg-brand-red text-white rounded-lg hover:bg-red-700 font-medium disabled:opacity-50 flex items-center gap-2">
                    <span v-if="form.processing"><i class="fas fa-spinner fa-spin"></i> Menyimpan...</span>
                    <span v-else>Simpan Pemesanan</span>
                </button>
            </div>
        </form>
    </div>
</template>
