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

const isNewUser = ref(false);

const form = useForm({
    // User fields
    user_id: '',
    customer_name: '',
    customer_email: '',
    customer_phone: '',
    institution_name: '',

    // Booking fields
    assigned_bus_ids: [],
    bus_count: 1,
    bus_type_requested: '',
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
    // If not new user, clear the new user fields
    if (!isNewUser.value) {
        form.customer_name = '';
        form.customer_email = '';
        form.customer_phone = '';
    } else {
        form.user_id = '';
    }

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
                    
                    <div class="mb-4">
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" v-model="isNewUser" class="rounded border-gray-300 text-brand-red focus:ring-brand-red" />
                            <span class="ml-2 text-sm text-gray-700">Pelanggan Baru (Belum ada di sistem)</span>
                        </label>
                    </div>

                    <div v-if="!isNewUser" class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Pelanggan <span class="text-red-500">*</span></label>
                        <select v-model="form.user_id" :required="!isNewUser" class="w-full rounded-lg border-gray-300 focus:border-brand-red focus:ring-brand-red">
                            <option value="">-- Pilih Pelanggan --</option>
                            <option v-for="user in users" :key="user.id" :value="user.id">
                                {{ user.name }} ({{ user.phone || user.email }})
                            </option>
                        </select>
                        <div v-if="form.errors.user_id" class="text-red-500 text-xs mt-1">{{ form.errors.user_id }}</div>
                    </div>

                    <div v-if="isNewUser" class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap Pemesan <span class="text-red-500">*</span></label>
                            <input type="text" v-model="form.customer_name" :required="isNewUser" class="w-full rounded-lg border-gray-300 focus:border-brand-red focus:ring-brand-red" />
                            <div v-if="form.errors.customer_name" class="text-red-500 text-xs mt-1">{{ form.errors.customer_name }}</div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Instansi / Organisasi <span class="text-gray-400 font-normal text-xs">(Opsional)</span></label>
                            <input type="text" v-model="form.institution_name" class="w-full rounded-lg border-gray-300 focus:border-brand-red focus:ring-brand-red" placeholder="Misal: SMAN 1 Jakarta" />
                            <div v-if="form.errors.institution_name" class="text-red-500 text-xs mt-1">{{ form.errors.institution_name }}</div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">No. WhatsApp <span class="text-red-500">*</span></label>
                            <input type="text" v-model="form.customer_phone" :required="isNewUser" class="w-full rounded-lg border-gray-300 focus:border-brand-red focus:ring-brand-red" />
                            <div v-if="form.errors.customer_phone" class="text-red-500 text-xs mt-1">{{ form.errors.customer_phone }}</div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email (Opsional)</label>
                            <input type="email" v-model="form.customer_email" class="w-full rounded-lg border-gray-300 focus:border-brand-red focus:ring-brand-red" />
                            <div v-if="form.errors.customer_email" class="text-red-500 text-xs mt-1">{{ form.errors.customer_email }}</div>
                        </div>
                    </div>
                </div>

                <!-- Data Armada & Perjalanan -->
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4 border-b pb-2">2. Data Armada & Perjalanan</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tipe Bus Diminta</label>
                            <input type="text" v-model="form.bus_type_requested" class="w-full rounded-lg border-gray-300 focus:border-brand-red focus:ring-brand-red mb-4" placeholder="Misal: Big Bus" />

                            <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah Bus <span class="text-red-500">*</span></label>
                            <input type="number" v-model="form.bus_count" required min="1" class="w-full rounded-lg border-gray-300 focus:border-brand-red focus:ring-brand-red mb-4" />

                            <label class="block text-sm font-medium text-gray-700 mb-1">Total Penumpang Keseluruhan <span class="text-gray-400 font-normal text-xs">(Semua bus digabung)</span></label>
                            <input type="number" v-model="form.passenger_count" min="1" class="w-full rounded-lg border-gray-300 focus:border-brand-red focus:ring-brand-red mb-4" placeholder="Misal: 100" />

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
