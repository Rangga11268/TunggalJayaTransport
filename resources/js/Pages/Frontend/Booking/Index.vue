<script setup>
import { ref, watch, nextTick, computed } from "vue";
import { Head, Link, useForm, router } from "@inertiajs/vue3";
import FrontendLayout from "@/Layouts/FrontendLayout.vue";
import axios from "axios";

defineOptions({ layout: FrontendLayout });

const props = defineProps({
    schedules: Array,
    origins: Array,
    destinations: Array,
    validPair: Boolean,
    filters: Object,
});

const form = useForm({
    origin: props.filters.origin || "",
    destination: props.filters.destination || "",
    date: props.filters.date || "",
    class: props.filters.class ? props.filters.class.split(",") : [],
    time: props.filters.time ? props.filters.time.split(",") : [],
});
const showAvailableOnly = ref(true);

// Local reactive state (no Inertia reload)
const localSchedules = ref(props.schedules || []);
const localValidPair = ref(props.validPair);
const localOrigins = ref(props.origins || []);
const localDestinations = ref(props.destinations || []);
const isSearching = ref(false);

// Gabung origins + destinations jadi satu list unik
const allLocations = computed(() => {
    const set = new Set([
        ...(localOrigins.value || []),
        ...(localDestinations.value || []),
    ]);
    return [...set].sort();
});

// Cek apakah ada filter aktif
const hasActiveFilters = computed(() => {
    return (
        form.origin ||
        form.destination ||
        form.date ||
        form.class.length > 0 ||
        form.time.length > 0 ||
        !showAvailableOnly.value
    );
});

// Reset semua filter
const resetFilters = async () => {
    isSwapping.value = true;
    form.origin = "";
    form.destination = "";
    form.date = "";
    form.class = [];
    form.time = [];
    sortBy.value = "availability";
    showAvailableOnly.value = true;

    try {
        isSearching.value = true;
        const { data } = await axios.get(route("frontend.booking.index"), {
            headers: { Accept: "application/json" },
        });
        localSchedules.value = data.schedules || [];
        localValidPair.value = data.validPair;
        localOrigins.value = data.origins || [];
        localDestinations.value = data.destinations || [];
    } catch (e) {
        console.error("Reset failed:", e);
    } finally {
        isSearching.value = false;
        setTimeout(() => {
            isSwapping.value = false;
        }, 100);
    }
};

// Sort state
const sortBy = ref("availability");

// Min date = hari ini (prevent past dates)
const todayDate = computed(() => new Date().toISOString().split("T")[0]);

// Client-side filter: class, time & departure status
const filteredSchedules = computed(() => {
    if (!localSchedules.value) return [];
    let result = [...localSchedules.value];

    // Filter by class
    if (form.class.length > 0) {
        result = result.filter((s) => form.class.includes(s.bus.bus_type));
    }

    // Filter by time range
    if (form.time.length > 0) {
        result = result.filter((s) => {
            const hour = parseInt(s.departure_time.split(":")[0], 10);
            return form.time.some((t) => {
                if (t === "morning") return hour >= 0 && hour < 12;
                if (t === "afternoon") return hour >= 12 && hour < 18;
                if (t === "evening") return hour >= 18 && hour <= 23;
                return false;
            });
        });
    }

    // Filter only available (not departed)
    if (showAvailableOnly.value) {
        result = result.filter((s) => !s.has_departed);
    }

    return result;
});

// Client-side sort
const displaySchedules = computed(() => {
    const list = [...filteredSchedules.value];
    if (sortBy.value === "cheapest") {
        list.sort((a, b) => (a.price || 0) - (b.price || 0));
    } else if (sortBy.value === "fastest") {
        list.sort((a, b) => {
            const parseDur = (d) => {
                if (!d) return 9999;
                const parts = d.match(/(\d+)/g);
                if (!parts) return 9999;
                return parts.length >= 2
                    ? parseInt(parts[0]) * 60 + parseInt(parts[1])
                    : parseInt(parts[0]);
            };
            return parseDur(a.duration) - parseDur(b.duration);
        });
    } else if (sortBy.value === "availability") {
        list.sort((a, b) => getAvailableSeats(b) - getAvailableSeats(a));
    } else {
        // "earliest" — sort by departure_time
        list.sort((a, b) =>
            (a.departure_time || "").localeCompare(b.departure_time || ""),
        );
    }
    return list;
});

// Server search via axios (no page reload)
const search = async () => {
    const params = {};
    if (form.origin) params.origin = form.origin;
    if (form.destination) params.destination = form.destination;
    if (form.date) params.date = form.date;

    try {
        isSearching.value = true;
        const { data } = await axios.get(route("frontend.booking.index"), {
            params,
            headers: { Accept: "application/json" },
        });
        localSchedules.value = data.schedules || [];
        localValidPair.value = data.validPair;
        localOrigins.value = data.origins || [];
        localDestinations.value = data.destinations || [];
    } catch (e) {
        console.error("Search failed:", e);
    } finally {
        isSearching.value = false;
    }
};

const formatPrice = (price) => {
    return new Intl.NumberFormat("id-ID", {
        style: "currency",
        currency: "IDR",
        minimumFractionDigits: 0,
    }).format(price || 0);
};

const formatDate = (dateString) => {
    if (!dateString) return "Tanggal Belum Tersedia";
    const date = new Date(dateString);
    if (isNaN(date.getTime()) || date.getFullYear() <= 1970)
        return "Tanggal Belum Tersedia";

    return date.toLocaleDateString("id-ID", {
        weekday: "long",
        year: "numeric",
        month: "long",
        day: "numeric",
    });
};

const getAvailableSeats = (schedule) => {
    const total = schedule.bus.capacity || 0;
    const booked = schedule.booked_seats_count || 0;
    return Math.max(0, total - booked);
};

// Tuker kota asal sama tujuan
const isSwapping = ref(false);
let debounceTimer = null;

const swapLocations = () => {
    isSwapping.value = true;
    clearTimeout(debounceTimer);

    const temp = form.origin;
    form.origin = form.destination;
    form.destination = temp;

    search();

    setTimeout(() => {
        isSwapping.value = false;
    }, 100);
};

// Auto-search saat origin/destination/date berubah (bukan class/time)
watch(
    () => [form.origin, form.destination, form.date],
    () => {
        if (isSwapping.value) return;
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => search(), 300);
    },
);
</script>

<template>
    <Head title="Pesan Tiket - Tunggal Jaya Transport" />

    <div class="relative w-full min-h-screen bg-[#fcf9f8]  pb-24">
        <!-- HEADER / SEARCH SECTION -->
        <div class="pt-[140px] px-8 lg:px-16 pb-8 bg-white shadow-sm mb-8 flex justify-center">
            <div class="max-w-[1280px] w-full">
                <!-- Search Console -->
                <div class="bg-white border border-[#f0edec] border-solid flex flex-col md:flex-row gap-6 items-end p-6 rounded-[16px] shadow-sm">
                    <div class="flex flex-col gap-2 flex-1 w-full">
                        <label class="font-bold text-[#454652] text-[12px] tracking-[0.6px] uppercase">Dari</label>
                        <select v-model="form.origin" class="w-full bg-white border border-[#c6c5d3] rounded-[8px] pl-4 pr-10 py-3 font-semibold text-[#1c1b1b] focus:ring-2 focus:ring-[#10207a] outline-none cursor-pointer appearance-none">
                            <option value="" disabled>Pilih Kota Asal</option>
                            <option v-for="opt in allLocations" :key="opt" :value="opt">{{ opt }}</option>
                        </select>
                    </div>

                    <div class="flex items-center justify-center pb-2 px-2 cursor-pointer text-gray-500 hover:text-[#10207a] transition-colors" @click="swapLocations">
                        <i class="fas fa-exchange-alt"></i>
                    </div>

                    <div class="flex flex-col gap-2 flex-1 w-full">
                        <label class="font-bold text-[#454652] text-[12px] tracking-[0.6px] uppercase">Ke</label>
                        <select v-model="form.destination" class="w-full bg-white border border-[#c6c5d3] rounded-[8px] pl-4 pr-10 py-3 font-semibold text-[#1c1b1b] focus:ring-2 focus:ring-[#10207a] outline-none cursor-pointer appearance-none">
                            <option value="" disabled>Pilih Kota Tujuan</option>
                            <option v-for="opt in allLocations" :key="opt" :value="opt">{{ opt }}</option>
                        </select>
                    </div>

                    <div class="flex flex-col gap-2 flex-1 w-full">
                        <label class="font-bold text-[#454652] text-[12px] tracking-[0.6px] uppercase">Tanggal</label>
                        <input v-model="form.date" type="date" :min="todayDate" class="w-full bg-white border border-[#c6c5d3] rounded-[8px] px-4 py-3 font-semibold text-[#1c1b1b] focus:ring-2 focus:ring-[#10207a] outline-none cursor-pointer">
                    </div>
                </div>
            </div>
        </div>

        <!-- MAIN LAYOUT (Filters + Schedules) -->
        <div class="max-w-[1280px] mx-auto px-8 lg:px-16 flex flex-col md:flex-row gap-8">
            
            <!-- LEFT SIDEBAR: FILTERS -->
            <div class="w-full md:w-[280px] shrink-0 bg-[#f6f3f2] border border-[#c6c5d3] rounded-[8px] p-6 h-fit">
                <div class="flex flex-col gap-6">
                    <div class="flex justify-between items-center">
                        <h3 class="font-semibold text-[#1c1b1b] text-[14px] tracking-[0.7px] uppercase">Filters</h3>
                        <button v-if="hasActiveFilters" @click="resetFilters" class="text-rose-600 text-[12px] font-bold uppercase tracking-wider hover:underline">Reset</button>
                    </div>
                    
                    <div class="flex flex-col gap-4">
                        <h4 class="font-bold text-[#454652] text-[12px] tracking-[0.6px] uppercase">Waktu Keberangkatan</h4>
                        <div class="flex flex-col gap-3">
                            <label class="flex items-center gap-3 cursor-pointer group">
                                <input type="checkbox" v-model="form.time" value="morning" class="w-4 h-4 rounded border-[#767683] text-[#10207a] focus:ring-[#10207a]">
                                <span class="font-normal text-[#1c1b1b] text-[14px] group-hover:text-[#10207a]">Pagi (00:00 - 11:59)</span>
                            </label>
                            <label class="flex items-center gap-3 cursor-pointer group">
                                <input type="checkbox" v-model="form.time" value="afternoon" class="w-4 h-4 rounded border-[#767683] text-[#10207a] focus:ring-[#10207a]">
                                <span class="font-normal text-[#1c1b1b] text-[14px] group-hover:text-[#10207a]">Siang (12:00 - 17:59)</span>
                            </label>
                            <label class="flex items-center gap-3 cursor-pointer group">
                                <input type="checkbox" v-model="form.time" value="evening" class="w-4 h-4 rounded border-[#767683] text-[#10207a] focus:ring-[#10207a]">
                                <span class="font-normal text-[#1c1b1b] text-[14px] group-hover:text-[#10207a]">Malam (18:00 - 23:59)</span>
                            </label>
                        </div>
                    </div>

                    <div class="border-t border-[#c6c5d3] pt-6 flex flex-col gap-4">
                        <h4 class="font-bold text-[#454652] text-[12px] tracking-[0.6px] uppercase">Status Keberangkatan</h4>
                        <label class="flex items-center gap-3 cursor-pointer group">
                            <input type="checkbox" v-model="showAvailableOnly" class="w-4 h-4 rounded border-[#767683] text-[#10207a] focus:ring-[#10207a]">
                            <span class="font-normal text-[#1c1b1b] text-[14px] group-hover:text-[#10207a]">Hanya tersedia</span>
                        </label>
                    </div>

                    <div class="border-t border-[#c6c5d3] pt-6 flex flex-col gap-4">
                        <h4 class="font-bold text-[#454652] text-[12px] tracking-[0.6px] uppercase">Kelas Bus</h4>
                        <div class="flex flex-col gap-3">
                            <label class="flex items-center gap-3 cursor-pointer group">
                                <input type="checkbox" v-model="form.class" value="Executive" class="w-4 h-4 rounded border-[#767683] text-[#10207a] focus:ring-[#10207a]">
                                <span class="font-normal text-[#1c1b1b] text-[14px] group-hover:text-[#10207a]">Eksekutif</span>
                            </label>
                            <label class="flex items-center gap-3 cursor-pointer group">
                                <input type="checkbox" v-model="form.class" value="Super Executive" class="w-4 h-4 rounded border-[#767683] text-[#10207a] focus:ring-[#10207a]">
                                <span class="font-normal text-[#1c1b1b] text-[14px] group-hover:text-[#10207a]">Super Eksekutif</span>
                            </label>
                            <label class="flex items-center gap-3 cursor-pointer group">
                                <input type="checkbox" v-model="form.class" value="Sleeper" class="w-4 h-4 rounded border-[#767683] text-[#10207a] focus:ring-[#10207a]">
                                <span class="font-normal text-[#1c1b1b] text-[14px] group-hover:text-[#10207a]">Sleeper</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- RIGHT CONTENT: SCHEDULES LIST -->
            <div class="flex-1 w-full flex flex-col gap-6">
                
                <div class="flex justify-between items-center border-b border-[#c6c5d3] pb-4">
                    <p class="font-semibold text-[#1c1b1b] text-[16px] m-0">Menampilkan {{ displaySchedules.length }} jadwal tersedia</p>
                    <div class="flex items-center gap-2">
                        <label class="font-bold text-[#454652] text-[12px] tracking-[0.6px] uppercase">Urutkan:</label>
                        <select v-model="sortBy" class="bg-transparent border-none font-semibold text-[#10207a] cursor-pointer outline-none text-[14px]">
                            <option value="earliest">Waktu Paling Awal</option>
                            <option value="availability">Ketersediaan Kursi</option>
                            <option value="cheapest">Harga Termurah</option>
                            <option value="fastest">Durasi Tercepat</option>
                        </select>
                    </div>
                </div>

                <!-- Loader -->
                <div v-if="isSearching" class="py-12 flex justify-center">
                    <div class="w-10 h-10 border-4 border-gray-200 border-t-[#10207a] rounded-full animate-spin"></div>
                </div>

                <!-- Empty State -->
                <div v-else-if="displaySchedules.length === 0" class="py-20 flex flex-col items-center justify-center bg-white rounded-[16px] border border-[#f0edec]">
                    <i class="fas fa-bus-slash text-4xl text-gray-300 mb-4"></i>
                    <p class="font-semibold text-[#1c1b1b] text-[18px]">Tidak ada jadwal yang sesuai</p>
                    <p class="text-[#454652] mt-2">Coba ubah tanggal keberangkatan atau filter Anda.</p>
                </div>

                <!-- Schedule Cards -->
                <div v-else class="flex flex-col gap-6">
                    <div v-for="schedule in displaySchedules" :key="schedule.id" class="bg-white border border-[#ebe7e7] rounded-[16px] p-6 flex flex-col md:flex-row gap-6 hover:shadow-lg transition-shadow">
                        <!-- Left Info -->
                        <div class="flex-1 flex flex-col gap-4 border-b md:border-b-0 md:border-r border-[#ebe7e7] pb-4 md:pb-0 md:pr-6">
                            <div class="flex justify-between items-center">
                                <span class="bg-[#dfe0ff] text-[#000e5e] px-3 py-1 rounded-[4px] font-bold text-[12px] tracking-wider uppercase">
                                    {{ schedule.bus?.bus_type || 'Executive' }}
                                </span>
                                <span class="font-medium text-[#454652] text-[14px] flex items-center gap-1">
                                    <i class="fas fa-chair text-gray-400"></i>
                                    {{ getAvailableSeats(schedule) }} Kursi Tersedia
                                </span>
                            </div>

                            <div class="flex flex-row items-center gap-6 py-2">
                                <div class="flex flex-col items-end">
                                    <span class="font-bold text-[#1c1b1b] text-[20px]">{{ schedule.departure_time?.slice(0, 5) }}</span>
                                    <span class="text-[#454652] text-[14px]">{{ schedule.route?.origin }}</span>
                                </div>
                                <div class="flex-1 flex flex-col items-center gap-1 relative text-[#454652] text-[12px]">
                                    <span>{{ schedule.duration }}</span>
                                    <div class="w-full h-[1px] border-t border-dashed border-[#c6c5d3] relative">
                                        <div class="absolute -left-1 -top-1 w-2 h-2 rounded-full border border-[#c6c5d3] bg-white"></div>
                                        <div class="absolute -right-1 -top-1 w-2 h-2 rounded-full bg-[#10207a]"></div>
                                    </div>
                                    <span v-if="!schedule.is_direct" class="text-rose-500 font-medium">1 Transit</span>
                                    <span v-else class="text-emerald-500 font-medium">Langsung</span>
                                </div>
                                <div class="flex flex-col items-start">
                                    <span class="font-bold text-[#1c1b1b] text-[20px]">{{ schedule.arrival_time?.slice(0, 5) }}</span>
                                    <span class="text-[#454652] text-[14px]">{{ schedule.route?.destination }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Right Info -->
                        <div class="w-full md:w-[220px] flex flex-col items-start md:items-end justify-center gap-4">
                            <div class="flex flex-col items-start md:items-end w-full">
                                <span class="font-bold text-[#10207a] text-[24px]">{{ formatPrice(schedule.price) }}</span>
                                <span class="text-[#454652] text-[12px]">/ kursi</span>
                            </div>
                            <Link v-if="!schedule.has_departed && getAvailableSeats(schedule) > 0" :href="route('frontend.booking.show', { id: schedule.id, date: form.date })" class="w-full bg-[#10207a] text-white py-3 rounded-[8px] font-semibold text-[14px] text-center hover:bg-[#0c185e] transition-colors">
                                Pilih Kursi
                            </Link>
                            <button v-else-if="schedule.has_departed" disabled class="w-full bg-gray-300 text-gray-500 py-3 rounded-[8px] font-semibold text-[14px] text-center cursor-not-allowed">
                                Sudah Berangkat
                            </button>
                            <button v-else disabled class="w-full bg-rose-100 text-rose-500 py-3 rounded-[8px] font-semibold text-[14px] text-center cursor-not-allowed">
                                Habis Terjual
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
