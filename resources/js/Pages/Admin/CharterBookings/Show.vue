<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";

const props = defineProps({
    charter: Object,
    buses: Array,
});

const form = useForm({
    total_price: props.charter.total_price || 0,
    down_payment: props.charter.down_payment || 0,
    assigned_bus_id: props.charter.assigned_bus_id || "",
    status: props.charter.status || "pending",
});

const submit = () => {
    form.put(route("admin.charter-bookings.update", props.charter.id), {
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
                <div class="bg-white dark:bg-[#151515] rounded-2xl border border-gray-100 dark:border-white/5 p-6 shadow-sm">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Informasi Perjalanan</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Titik Jemput</p>
                            <p class="font-medium text-gray-900 dark:text-white">{{ charter.pickup_location }}</p>
                            <p class="text-sm text-gray-500 mt-2 mb-1">Tanggal Jemput</p>
                            <p class="font-medium text-gray-900 dark:text-white">{{ formatDate(charter.pickup_date) }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Tujuan</p>
                            <p class="font-medium text-gray-900 dark:text-white">{{ charter.destination }}</p>
                            <p class="text-sm text-gray-500 mt-2 mb-1">Tanggal Pulang</p>
                            <p class="font-medium text-gray-900 dark:text-white">{{ formatDate(charter.return_date) }}</p>
                        </div>
                    </div>
                    
                    <div class="mt-6 p-4 bg-gray-50 dark:bg-white/[0.02] rounded-xl border border-gray-100 dark:border-white/5">
                        <p class="text-sm text-gray-500 mb-1">Catatan dari Pelanggan:</p>
                        <p class="text-sm text-gray-900 dark:text-gray-300 whitespace-pre-line">{{ charter.notes || "Tidak ada catatan." }}</p>
                    </div>
                </div>
            </div>

            <!-- Management Form -->
            <div class="bg-white dark:bg-[#151515] rounded-2xl border border-gray-100 dark:border-white/5 p-6 shadow-sm self-start">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Manajemen Pesanan</h3>
                
                <form @submit.prevent="submit" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tipe Bus Diminta</label>
                        <input type="text" disabled :value="charter.bus_type_requested" class="w-full px-4 py-2 bg-gray-50 dark:bg-black/50 border border-gray-200 dark:border-gray-700 rounded-xl text-gray-500 text-sm" />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Total Harga Sewa (Rp)</label>
                        <input v-model="form.total_price" type="number" class="w-full px-4 py-2 bg-white dark:bg-[#151515] border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-brand-red focus:border-brand-red text-sm dark:text-white" />
                        <p class="text-xs text-gray-500 mt-1">Isi harga untuk memberikan penawaran ke pelanggan.</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Wajib DP (Rp)</label>
                        <input v-model="form.down_payment" type="number" class="w-full px-4 py-2 bg-white dark:bg-[#151515] border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-brand-red focus:border-brand-red text-sm dark:text-white" />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Pilih Armada (Opsional)</label>
                        <select v-model="form.assigned_bus_id" class="w-full px-4 py-2 bg-white dark:bg-[#151515] border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-brand-red focus:border-brand-red text-sm dark:text-white">
                            <option value="">Belum ditentukan</option>
                            <option v-for="bus in buses" :key="bus.id" :value="bus.id">
                                {{ bus.plate_number }} - {{ bus.name }} ({{ bus.bus_type }})
                            </option>
                        </select>
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
