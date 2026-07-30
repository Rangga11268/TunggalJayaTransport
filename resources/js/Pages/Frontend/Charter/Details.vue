<script setup>
import FrontendLayout from "@/Layouts/FrontendLayout.vue";
import { Head, useForm, Link } from "@inertiajs/vue3";
import Swal from "sweetalert2";
import LocationAutocomplete from "@/Components/LocationAutocomplete.vue";
import { ref, watch, onMounted, nextTick } from "vue";
import "leaflet/dist/leaflet.css";
import L from "leaflet";

defineOptions({ layout: FrontendLayout });

const props = defineProps({
    bookingData: Object,
});

const form = useForm({
    pickup_date: props.bookingData?.pickup_date || "",
    pickup_time: props.bookingData?.pickup_time || "",
    return_date: props.bookingData?.return_date || "",
    pickup_location: props.bookingData?.pickup_location || "",
    destination: props.bookingData?.destination || "",
    bus_requests: props.bookingData?.bus_requests || [],
    institution_name: props.bookingData?.institution_name || "",
    bus_id: props.bookingData?.bus_id || null,
    
    pickup_lat: null,
    pickup_lng: null,
    pickup_address: "",
    destination_lat: null,
    destination_lng: null,
    destination_address: "",
    passenger_count: props.bookingData?.passenger_count || "",
    notes: "",
});

const mapContainer = ref(null);
let mapInstance = null;
let originMarker = null;
let destinationMarker = null;

const initMap = () => {
    if (!mapContainer.value) return;
    if (mapInstance) {
        mapInstance.remove();
    }
    
    mapInstance = L.map(mapContainer.value).setView([-2.5489, 118.0149], 5);
    
    L.tileLayer("https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png", {
        attribution: '&copy; <a href="https://carto.com/">Carto</a>'
    }).addTo(mapInstance);

    updateMapMarkers();
};

const updateMapMarkers = () => {
    if (!mapInstance) return;

    if (originMarker) originMarker.remove();
    if (destinationMarker) destinationMarker.remove();

    const bounds = [];

    if (form.pickup_lat && form.pickup_lng) {
        originMarker = L.marker([form.pickup_lat, form.pickup_lng]).addTo(mapInstance)
            .bindPopup(`<strong>Asal</strong><br>${form.pickup_address}`);
        bounds.push([form.pickup_lat, form.pickup_lng]);
    }

    if (form.destination_lat && form.destination_lng) {
        destinationMarker = L.marker([form.destination_lat, form.destination_lng]).addTo(mapInstance)
            .bindPopup(`<strong>Tujuan</strong><br>${form.destination_address}`);
        bounds.push([form.destination_lat, form.destination_lng]);
    }

    if (bounds.length > 0) {
        mapInstance.fitBounds(bounds, { padding: [50, 50], maxZoom: 14 });
    }
};

const handleLocationSelect = (type, location) => {
    if (type === 'pickup') {
        form.pickup_lat = location.lat;
        form.pickup_lng = location.lng;
        if (!form.pickup_address) {
            form.pickup_address = location.name;
        }
    } else {
        form.destination_lat = location.lat;
        form.destination_lng = location.lng;
        if (!form.destination_address) {
            form.destination_address = location.name;
        }
    }
    
    nextTick(() => {
        if (!mapInstance && mapContainer.value) {
            initMap();
        } else {
            updateMapMarkers();
        }
    });
};

const submit = () => {
    if (!form.pickup_address || !form.destination_address) {
        Swal.fire({
            icon: 'warning',
            title: 'Alamat Belum Lengkap',
            text: 'Harap isi detail alamat penjemputan dan tujuan.',
            confirmButtonColor: '#E11D48',
        });
        return;
    }

    // Default fallback lat/lng if user typed manual custom address without selecting pin
    if (!form.pickup_lat) {
        form.pickup_lat = -6.2088;
        form.pickup_lng = 106.8456;
    }
    if (!form.destination_lat) {
        form.destination_lat = -6.9175;
        form.destination_lng = 107.6191;
    }

    form.post(route("frontend.charter.store"), {
        preserveScroll: true,
        onSuccess: () => {
            // Handled by redirect to success page in controller
        },
    });
};
</script>

<template>
    <Head title="Detail Penjemputan - Sewa Pariwisata" />

    <div class="bg-[#fcf9f8] min-h-screen pb-24">
        <!-- Header -->
        <div class="pt-28 pb-8 px-4 sm:px-6 lg:px-8 text-center bg-white border-b border-[#ebe7e7]">
            <div class="max-w-3xl mx-auto">
                <div class="flex items-center justify-center gap-4 text-sm font-bold mb-4">
                    <span class="text-emerald-600"><i class="fas fa-check-circle mr-1"></i> 1. Info Dasar</span>
                    <i class="fas fa-chevron-right text-gray-300 text-[10px]"></i>
                    <span class="text-[#10207a]">2. Detail Penjemputan</span>
                </div>
                <h1 class="font-unbounded font-black text-3xl md:text-4xl text-[#1c1b1b]">
                    Detail Alamat & Lokasi
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
                                <span class="text-gray-500 text-sm">Kota Jemput</span>
                                <span class="font-bold text-[#1c1b1b] text-sm text-right">{{ form.pickup_location || '-' }}</span>
                            </div>
                            <div class="flex justify-between pb-4 border-b border-gray-100">
                                <span class="text-gray-500 text-sm">Kota Tujuan</span>
                                <span class="font-bold text-[#1c1b1b] text-sm text-right">{{ form.destination || '-' }}</span>
                            </div>
                            <div class="flex justify-between pb-4 border-b border-gray-100">
                                <span class="text-gray-500 text-sm">Tanggal</span>
                                <span class="font-bold text-[#1c1b1b] text-sm text-right">
                                    {{ form.pickup_date || '-' }}<br>
                                    s/d {{ form.return_date || '-' }}
                                </span>
                            </div>
                        </div>

                        <Link :href="route('frontend.charter.step1')"
                            class="w-full mt-6 py-3 border-2 border-[#10207a] text-[#10207a] rounded-xl font-bold text-[14px] hover:bg-[#10207a] hover:text-white transition-all flex items-center justify-center">
                            <i class="fas fa-arrow-left mr-2"></i> Kembali ke Step 1
                        </Link>
                    </div>
                </div>

                <!-- Form Details -->
                <div class="lg:col-span-8 lg:order-1">
                    <div class="bg-white border border-[#ebe7e7] rounded-[12px] p-6 md:p-8 shadow-sm">
                        <form @submit.prevent="submit" class="space-y-8">
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-bold text-[#1c1b1b] mb-1.5">Lokasi Penjemputan <span class="text-[#10207a]">*</span></label>
                                    <LocationAutocomplete 
                                        v-model="form.pickup_location" 
                                        placeholder="Cari titik penjemputan..."
                                        @select="(loc) => handleLocationSelect('pickup', loc)"
                                    />
                                    <div class="mt-3">
                                        <label class="block text-xs font-bold text-[#454652] mb-1">Detail Alamat / Patokan Penjemputan <span class="text-[#10207a]">*</span></label>
                                        <textarea 
                                            v-model="form.pickup_address" 
                                            rows="3"
                                            placeholder="Contoh: Jl. Ahmad Yani No. 45, RT 02 RW 03 (Depan Masjid Agung / Lobby Hotel)"
                                            class="w-full px-3 py-2 text-xs bg-[#f6f3f2] border border-[#e5e2e1] focus:border-[#10207a] focus:bg-white rounded-[10px] text-[#1c1b1b] outline-none transition-all resize-none"
                                        ></textarea>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-[#1c1b1b] mb-1.5">Lokasi Tujuan <span class="text-[#10207a]">*</span></label>
                                    <LocationAutocomplete 
                                        v-model="form.destination" 
                                        placeholder="Cari titik tujuan..."
                                        @select="(loc) => handleLocationSelect('destination', loc)"
                                    />
                                    <div class="mt-3">
                                        <label class="block text-xs font-bold text-[#454652] mb-1">Detail Alamat / Patokan Tujuan <span class="text-[#10207a]">*</span></label>
                                        <textarea 
                                            v-model="form.destination_address" 
                                            rows="3"
                                            placeholder="Contoh: Area Parkir Barat Pantai Pangandaran / Hotel Santika Room Ballroom"
                                            class="w-full px-3 py-2 text-xs bg-[#f6f3f2] border border-[#e5e2e1] focus:border-[#10207a] focus:bg-white rounded-[10px] text-[#1c1b1b] outline-none transition-all resize-none"
                                        ></textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- Map Preview -->
                            <div v-if="form.pickup_lat || form.destination_lat" class="mt-4">
                                <h3 class="font-unbounded font-bold text-[#1c1b1b] text-sm mb-2">Preview Peta Lokasi</h3>
                                <div ref="mapContainer" class="h-[300px] w-full rounded-xl overflow-hidden border border-[#ebe7e7] z-0 relative"></div>
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-[#1c1b1b] mb-1.5">Catatan Tambahan <span class="text-gray-400 font-normal">(Opsional)</span></label>
                                <textarea v-model="form.notes" rows="3" placeholder="Misal: Minta disediakan bantal selimut, lagu karaoke, dll..."
                                    class="w-full px-4 py-3 bg-[#f6f3f2] border border-[#e5e2e1] focus:border-[#10207a] focus:bg-white focus:ring-0 rounded-[10px] text-[#1c1b1b] text-sm outline-none transition-all resize-none"></textarea>
                            </div>

                            <div class="flex gap-4">
                                <Link :href="route('frontend.charter.step1')"
                                    class="w-1/3 py-4 bg-white border border-gray-300 text-gray-700 rounded-xl font-bold text-[15px] hover:bg-gray-50 transition-all flex items-center justify-center gap-2">
                                    Kembali
                                </Link>
                                <button type="submit" :disabled="form.processing"
                                    class="w-2/3 py-4 bg-[#10207a] text-white rounded-xl font-bold text-[15px] hover:bg-[#0c185e] transition-all shadow-lg shadow-[#10207a]/20 disabled:opacity-50 flex items-center justify-center gap-2">
                                    <span v-if="form.processing"><i class="fas fa-spinner fa-spin"></i> Memproses...</span>
                                    <span v-else>Kirim Permintaan <i class="fas fa-paper-plane"></i></span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
