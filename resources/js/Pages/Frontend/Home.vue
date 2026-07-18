<script setup>
import { ref, computed } from "vue";
import { Link, useForm, usePage, Head } from "@inertiajs/vue3";
import FrontendLayout from "@/Layouts/FrontendLayout.vue";

defineOptions({ layout: FrontendLayout });

const props = defineProps({
    featuredRoutes: Array,
    latestNews: Array,
    fleet: Array,
    origins: Array,
    destinations: Array,
});

const page = usePage();

// Form State
const form = useForm({
    origin: "",
    destination: "",
    date: "",
    class: "",
});

const today = new Date().toISOString().split("T")[0];
const activeTab = ref('reguler'); // reguler or charter

const submitSearch = () => {
    form.get(route('frontend.booking.index'));
};

const busTypes = [
    { id: "", name: "Semua Kelas" },
    { id: "Executive", name: "Eksekutif" },
    { id: "Super Executive", name: "Super Eksekutif" },
    { id: "Sleeper", name: "Sleeper" },
];

// Charter Form State
const charterForm = useForm({
    origin: "",
    destination: "",
    date: "",
    time: "",
});

const submitCharter = () => {
    charterForm.get(route('charter.index'));
};

// Fleet filter
const fleetCategory = ref('all');

const filteredFleet = computed(() => {
    if (fleetCategory.value === 'all') return props.fleet?.slice(0, 6) || [];
    return props.fleet?.filter(b => b.bus_category === fleetCategory.value).slice(0, 6) || [];
});

// Find first pariwisata bus with image for the armada section
const pariwisataBusImage = computed(() => {
    const bus = props.fleet?.find(b => b.bus_category === 'pariwisata' && b.image_url);
    return bus?.image_url || '/img/interiorBus.png';
});

// Google Reviews
const reviews = [
    { name: "Alifah Tasya hanie", stars: 5, text: "ASSALAIKUM" },
    { name: "Fahmi Ror", stars: 5, text: null },
    { name: "Richard Phillip Pardamean Hutabarat", stars: 5, text: null },
    { name: "Lipi Yana", stars: 5, text: null },
    { name: "Faiz Dwi", stars: 5, text: null },
    { name: "Yazid Bassid", stars: 5, text: null },
    { name: "Alvaro Zulkarnaen", stars: 5, text: null },
    { name: "Rizky Handi Nugraha", stars: 5, text: null },
    { name: "Yadi Ncex", stars: 5, text: null },
    { name: "Pemuda idaman", stars: 5, text: null },
    { name: "The Flower", stars: 5, text: null },
    { name: "Beben Junior", stars: 5, text: null },
    { name: "Rifka Rifki", stars: 5, text: null },
    { name: "Indiya Wibawa", stars: 5, text: null },
    { name: "daw sty", stars: 5, text: null },
    { name: "Edy Grahamas", stars: 5, text: null },
    { name: "Mirna Wati", stars: 5, text: null },
    { name: "Anak Kecil", stars: 5, text: null },
    { name: "Rumah Burung Cendet", stars: 5, text: null },
    { name: "Yani Suryani", stars: 5, text: null },
    { name: "arisareni", stars: 4, text: "Nyenengin anak, 50rb perbangku keliling kota kuningan.. Mantap lah" },
    { name: "muter muter", stars: 4, text: "Rame banget. Selain banyak pemburu bus artis, banyak pedagang juga di sekitar lokasi. Kendaraan banyak parkir di pinggir jalan. Pas ke sana, garasinya lagi kosong." },
    { name: "Dedi Erwanto", stars: 4, text: "Banyak yang jualan kalau Sabtu dan Minggu, terutama jual stiker bus, anak jadi suka" },
    { name: "Endarto Dany", stars: 4, text: "Luas garasinya, ada toilet mushola nya. Busnya bagus2, krunya ramah." },
    { name: "Arjun Dwi", stars: 4, text: "Lumayan lah ketemu dua bis masih nyari kids panda JB 5" },
    { name: "Apen Supendi", stars: 4, text: "Harusnya jangan ditutup, biar anak-anak bisa bermain" },
    { name: "Neneng Nurhinda", stars: 4, text: "Bis nya bagus tapi jarang ngasih stiker" },
    { name: "Sendadiprana Amar", stars: 4, text: "Lokasi di daerah kuningan dekat dengan objek wisata J&J" },
    { name: "Siti Maysaroh", stars: 4, text: "Daya pernah ketemu bentas" },
    { name: "Ardi Art", stars: 4, text: "Garasi bis rasa tempat wisata." },
    { name: "Muhammad Irsad", stars: 4, text: "Tempat kumpul bocil basuri mania" },
    { name: "Uswatun Hasanah", stars: 4, text: "Tunggal jaya debes" },
    { name: "kvieth 19", stars: 3, text: "Banyak bocil rusuh disana pas saya ke sana cuma ada neptun doang baju nya mahak banget! Topi 75 digarasi pas saturn ke cerbon jualan topi cuma 15 kok!" },
    { name: "Raffrad", stars: 3, text: "Wisata kreatif dan inovatif, dgn sasaran anak2 pengemar telolet." },
    { name: "Ahmad hasan", stars: 3, text: "Po tunggaljaya transport juragan88" },
    { name: "Kang Choen", stars: 3, text: "Buat naek telolet, tapi gak tiap hari ada." },
    { name: "Ari Fahrurrozi", stars: 3, text: "Kurang luas" },
    { name: "O Ke", stars: 3, text: "PRADANA TRANS PAPA MUDA" },
];

const activeReviews = computed(() => {
    return reviews.filter(r => r.stars >= 4).slice(0, 28);
});

const getStars = (count) => {
    return Array(5).fill(0).map((_, i) => i < count);
};

const getInitial = (name) => {
    return name.charAt(0).toUpperCase();
};
</script>

<template>
    <Head title="Beranda - Tunggal Jaya Transport" />

    <div class="relative size-full bg-[#fcf9f8]">
        <!-- HERO SECTION -->
        <div class="relative flex items-center justify-center left-0 min-h-[600px] lg:min-h-[800px] pb-20 lg:pb-[128px] pt-24 lg:pt-[160px] right-0 top-0 overflow-hidden">
            <div class="absolute inset-0">
                <img class="absolute block h-full w-full object-cover z-0" src="/img/kylorenParwis.webp" alt="Hero Image" />
                <div class="absolute inset-0 bg-gradient-to-r from-black/80 to-black/30"></div>
                <div class="absolute bottom-0 left-0 w-full h-48 bg-gradient-to-t from-[#fcf9f8] to-transparent"></div>
            </div>

            <div class="max-w-[1280px] w-full relative z-10 px-4 sm:px-6 lg:px-16">
                <div class="flex flex-col gap-4 sm:gap-6 items-start max-w-[600px]">
                    <h1 class="font-unbounded font-extrabold text-white leading-tight text-3xl sm:text-4xl md:text-5xl lg:text-[54px] tracking-[-0.03em]">
                        Perjalanan Nyaman,<br>Tiba Tepat Waktu
                    </h1>
                    <p class="text-base sm:text-lg md:text-xl text-white/90 max-w-[540px] leading-relaxed">
                        Pesan tiket bus AKAP kelas eksekutif atau sewa armada bus pariwisata premium untuk perjalanan yang tak terlupakan bersama Tunggal Jaya.
                    </p>
                </div>

                <!-- Booking Console -->
                <div class="mt-8 sm:mt-12 bg-white border border-[#f0edec] flex flex-col gap-4 sm:gap-[24px] items-center max-w-[1000px] p-3 sm:p-[13px] rounded-[16px] shadow-xl relative z-10 w-full">
                    <div class="bg-[#f6f3f2] rounded-[8px] shrink-0 w-full">
                        <div class="flex p-[4px] gap-1 w-full">
                            <button @click="activeTab = 'reguler'" :class="[activeTab === 'reguler' ? 'bg-white drop-shadow-sm text-[#10207a]' : 'text-[#454652] hover:bg-gray-100']" class="flex-1 flex items-center justify-center gap-1.5 px-3 sm:px-[32px] py-[10px] sm:py-[12px] rounded-[4px] font-semibold text-[13px] sm:text-[14px] transition-colors">
                                <i class="fas fa-ticket-alt text-xs sm:text-sm"></i>
                                Tiket Reguler
                            </button>
                            <button @click="activeTab = 'charter'" :class="[activeTab === 'charter' ? 'bg-white drop-shadow-sm text-[#10207a]' : 'text-[#454652] hover:bg-gray-100']" class="flex-1 flex items-center justify-center gap-1.5 px-3 sm:px-[32px] py-[10px] sm:py-[12px] rounded-[4px] font-semibold text-[13px] sm:text-[14px] transition-colors">
                                <i class="fas fa-bus text-xs sm:text-sm"></i>
                                Sewa Bus
                            </button>
                        </div>
                    </div>

                    <!-- Search Form -->
                    <div class="relative w-full">
                        <form v-if="activeTab === 'reguler'" @submit.prevent="submitSearch" class="flex flex-col md:flex-row gap-3 md:gap-[16px] items-stretch md:items-end p-3 sm:p-[16px]">
                            <div class="flex-1 flex flex-col gap-1.5 sm:gap-[8px]">
                                <label class="font-bold text-[#454652] text-[11px] sm:text-[12px] tracking-[0.6px] uppercase">Dari</label>
                                <select v-model="form.origin" class="w-full bg-white border border-[#c6c5d3] rounded-[8px] px-3 sm:px-4 py-3 sm:py-4 font-semibold text-[#1c1b1b] text-sm focus:ring-2 focus:ring-[#10207a] outline-none cursor-pointer appearance-none">
                                    <option value="" disabled>Pilih Kota Asal</option>
                                    <option v-for="origin in origins" :key="origin" :value="origin">{{ origin }}</option>
                                </select>
                            </div>

                            <div class="hidden md:flex items-center justify-center pt-6 sm:pt-8">
                                <div class="bg-[#f0edec] rounded-full p-2 text-[#454652] cursor-pointer hover:bg-gray-200">
                                    <i class="fas fa-exchange-alt"></i>
                                </div>
                            </div>

                            <div class="flex-1 flex flex-col gap-1.5 sm:gap-[8px]">
                                <label class="font-bold text-[#454652] text-[11px] sm:text-[12px] tracking-[0.6px] uppercase">Ke</label>
                                <select v-model="form.destination" class="w-full bg-white border border-[#c6c5d3] rounded-[8px] px-3 sm:px-4 py-3 sm:py-4 font-semibold text-[#1c1b1b] text-sm focus:ring-2 focus:ring-[#10207a] outline-none cursor-pointer appearance-none">
                                    <option value="" disabled>Pilih Kota Tujuan</option>
                                    <option v-for="dest in destinations" :key="dest" :value="dest">{{ dest }}</option>
                                </select>
                            </div>

                            <div class="flex-1 flex flex-col gap-1.5 sm:gap-[8px]">
                                <label class="font-bold text-[#454652] text-[11px] sm:text-[12px] tracking-[0.6px] uppercase">Tanggal</label>
                                <input v-model="form.date" type="date" :min="today" class="w-full bg-white border border-[#c6c5d3] rounded-[8px] px-3 sm:px-4 py-3 sm:py-4 font-semibold text-[#1c1b1b] text-sm focus:ring-2 focus:ring-[#10207a] outline-none cursor-pointer">
                            </div>

                            <button type="submit" class="bg-[#10207a] hover:bg-[#0c185e] text-white px-6 sm:px-8 py-3 sm:py-4 rounded-[8px] font-semibold text-[14px] sm:text-[16px] transition-colors md:h-[58px] flex items-center justify-center gap-2">
                                <i class="fas fa-search"></i>
                                Cari
                            </button>
                        </form>

                        <form v-if="activeTab === 'charter'" @submit.prevent="submitCharter" class="flex flex-col gap-4 p-4 sm:p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
                                <!-- Keberangkatan -->
                                <div class="flex flex-col gap-1.5 sm:gap-[8px]">
                                    <label class="font-bold text-[#454652] text-[11px] sm:text-[12px] tracking-[0.6px] uppercase">Kota Keberangkatan</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                                            <i class="fas fa-map-marker-alt text-sm"></i>
                                        </div>
                                        <input v-model="charterForm.origin" type="text" placeholder="Pilih kota keberangkatan" required
                                            class="w-full bg-white border border-[#c6c5d3] rounded-[8px] pl-10 pr-4 py-3 sm:py-4 font-semibold text-[#1c1b1b] text-sm focus:ring-2 focus:ring-[#10207a] outline-none">
                                    </div>
                                </div>
                                
                                <!-- Tujuan -->
                                <div class="flex flex-col gap-1.5 sm:gap-[8px]">
                                    <label class="font-bold text-[#454652] text-[11px] sm:text-[12px] tracking-[0.6px] uppercase">Kota Tujuan</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                                            <i class="fas fa-location-arrow text-sm"></i>
                                        </div>
                                        <input v-model="charterForm.destination" type="text" placeholder="Pilih kota tujuan" required
                                            class="w-full bg-white border border-[#c6c5d3] rounded-[8px] pl-10 pr-4 py-3 sm:py-4 font-semibold text-[#1c1b1b] text-sm focus:ring-2 focus:ring-[#10207a] outline-none">
                                    </div>
                                </div>
                                
                                <!-- Tanggal Mulai -->
                                <div class="flex flex-col gap-1.5 sm:gap-[8px]">
                                    <label class="font-bold text-[#454652] text-[11px] sm:text-[12px] tracking-[0.6px] uppercase">Tanggal Mulai Sewa</label>
                                    <input v-model="charterForm.date" type="date" :min="today" required
                                        class="w-full bg-white border border-[#c6c5d3] rounded-[8px] px-3 sm:px-4 py-3 sm:py-4 font-semibold text-[#1c1b1b] text-sm focus:ring-2 focus:ring-[#10207a] outline-none">
                                </div>

                                <!-- Jam Mulai -->
                                <div class="flex flex-col gap-1.5 sm:gap-[8px]">
                                    <label class="font-bold text-[#454652] text-[11px] sm:text-[12px] tracking-[0.6px] uppercase">Jam Mulai Sewa</label>
                                    <input v-model="charterForm.time" type="time" required
                                        class="w-full bg-white border border-[#c6c5d3] rounded-[8px] px-3 sm:px-4 py-3 sm:py-4 font-semibold text-[#1c1b1b] text-sm focus:ring-2 focus:ring-[#10207a] outline-none">
                                </div>
                            </div>

                            <button type="submit" class="w-full mt-2 bg-[#25D366] hover:bg-[#128C7E] text-white py-3 sm:py-4 rounded-[8px] font-bold text-[14px] sm:text-[16px] transition-colors md:h-[58px] flex items-center justify-center gap-2">
                                <i class="fas fa-search text-lg"></i>
                                Cari Bus Wisata
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>  

        <!-- POPULAR ROUTES SECTION -->
        <div class="bg-[#f8f9fa] flex flex-col items-center py-16 md:py-24 lg:py-32">
            <div class="flex flex-col gap-6 md:gap-10 lg:gap-[48px] items-start max-w-[1280px] px-4 sm:px-6 lg:px-16 w-full">
                <div class="flex items-end justify-between w-full">
                    <div class="flex flex-col gap-[8px] items-start">
                        <h2 class="font-unbounded font-bold text-[#1c1b1b] text-[24px] sm:text-[28px] md:text-[32px] tracking-[-0.32px] m-0">Rute Perjalanan Populer</h2>
                        <p class="font-normal text-[#454652] text-[16px] m-0">Pilihan destinasi favorit penumpang kami.</p>
                    </div>
                    <div class="flex gap-[8px] items-start hidden sm:flex">
                        <button class="bg-white border border-[#c6c5d3] flex items-center justify-center rounded-[12px] size-[40px] hover:bg-gray-50 transition-colors"><i class="fas fa-arrow-left text-gray-500"></i></button>
                        <button class="bg-white border border-[#c6c5d3] flex items-center justify-center rounded-[12px] size-[40px] hover:bg-gray-50 transition-colors"><i class="fas fa-arrow-right text-gray-500"></i></button>
                    </div>
                </div>

                <div class="w-full overflow-x-auto pb-8 snap-x">
                    <div class="flex gap-3 md:gap-[24px] w-max">
                        <div v-for="item in featuredRoutes" :key="item.id"
                            class="bg-white border border-[#ebe7e7] drop-shadow-sm rounded-[8px] w-[280px] sm:w-[320px] md:w-[360px] flex flex-col p-4 sm:p-6 snap-center">
                            <div class="flex items-center justify-between w-full mb-6">
                                <span class="bg-[#dfe0ff] text-[#000e5e] px-3 py-1 rounded-[4px] font-bold text-[12px] tracking-wider uppercase">{{ item.bus_type || 'Executive' }}</span>
                                <i class="fas fa-star text-yellow-400"></i>
                            </div>
                            <div class="flex items-center gap-4 mb-4">
                                <span class="font-unbounded font-semibold text-[#1c1b1b] text-[18px] min-w-0 truncate">{{ item.origin }}</span>
                                <i class="fas fa-arrow-right text-gray-400 shrink-0"></i>
                                <span class="font-unbounded font-semibold text-[#1c1b1b] text-[18px] min-w-0 truncate">{{ item.destination }}</span>
                            </div>
                            <p class="font-normal text-[#454652] text-[16px] mb-6">Keberangkatan tersedia setiap hari</p>
                            <div class="border-t border-[#ebe7e7] pt-4 mt-auto">
                                <p class="font-bold text-[#454652] text-[12px] tracking-wider uppercase mb-1">Mulai Dari</p>
                                <p class="font-unbounded font-semibold text-[#10207a] text-[24px]">{{ item.starting_price ? 'Rp ' + new Intl.NumberFormat('id-ID').format(item.starting_price) : '-' }}</p>
                                <Link :href="route('frontend.booking.index', { origin: item.origin, destination: item.destination })" class="mt-4 w-full bg-[#10207a] text-white py-3 rounded-[8px] font-semibold text-[14px] text-center block hover:bg-[#0c185e] transition-colors">
                                    Pesan Tiket
                                </Link>
                    </div>
                </div>
            </div>
        </div>
    </div>
            </div>
        </div>

        <!-- TESTIMONIALS SECTION -->
        <div class="bg-[#fcf9f8] overflow-hidden py-16">
            <div class="text-center mb-10 px-4">
                <span class="inline-block px-3 py-1 rounded-full bg-white border border-[#ebe7e7] text-[#10207a] text-[9px] font-bold tracking-widest uppercase mb-3 shadow-sm">Google Reviews</span>
                <h2 class="font-unbounded font-bold text-[28px] text-[#1c1b1b]">Apa Kata Mereka</h2>
                <p class="text-[#454652] text-[14px] mt-1">Pengalaman nyata dari pelanggan setia Tunggal Jaya Transport.</p>
            </div>

            <!-- Row 1 - scroll kiri -->
            <div class="relative overflow-hidden mb-6">
                <div class="flex gap-4 animate-scroll-left">
                    <template v-for="i in 3" :key="'r1-'+i">
                        <div v-for="r in activeReviews" :key="r.name + i"
                            class="flex-shrink-0 w-[320px] bg-white border border-[#ebe7e7] rounded-[12px] p-5 shadow-sm">
                            <div class="flex items-center gap-1 mb-2">
                                <i v-for="(filled, s) in getStars(r.stars)" :key="s"
                                    class="text-[11px]" :class="filled ? 'fas fa-star text-amber-400' : 'far fa-star text-gray-300'"></i>
                            </div>
                            <p v-if="r.text" class="text-[13px] text-[#454652] leading-relaxed line-clamp-3 mb-3">"{{ r.text }}"</p>
                            <p v-else class="text-[13px] text-gray-400 italic mb-3">Tidak ada komentar</p>
                            <div class="flex items-center gap-2.5 pt-2 border-t border-[#ebe7e7]">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold text-white shrink-0"
                                    :class="r.stars >= 5 ? 'bg-green-600' : r.stars >= 4 ? 'bg-lime-600' : 'bg-amber-500'">
                                    {{ getInitial(r.name) }}
                                </div>
                                <span class="text-sm font-semibold text-[#1c1b1b] truncate">{{ r.name }}</span>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Row 2 - scroll kanan -->
            <div class="relative overflow-hidden">
                <div class="flex gap-4 animate-scroll-right">
                    <template v-for="i in 3" :key="'r2-'+i">
                        <div v-for="r in [...activeReviews].reverse()" :key="r.name + 'rev' + i"
                            class="flex-shrink-0 w-[320px] bg-white border border-[#ebe7e7] rounded-[12px] p-5 shadow-sm">
                            <div class="flex items-center gap-1 mb-2">
                                <i v-for="(filled, s) in getStars(r.stars)" :key="s"
                                    class="text-[11px]" :class="filled ? 'fas fa-star text-amber-400' : 'far fa-star text-gray-300'"></i>
                            </div>
                            <p v-if="r.text" class="text-[13px] text-[#454652] leading-relaxed line-clamp-3 mb-3">"{{ r.text }}"</p>
                            <p v-else class="text-[13px] text-gray-400 italic mb-3">Tidak ada komentar</p>
                            <div class="flex items-center gap-2.5 pt-2 border-t border-[#ebe7e7]">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold text-white shrink-0"
                                    :class="r.stars >= 5 ? 'bg-green-600' : r.stars >= 4 ? 'bg-lime-600' : 'bg-amber-500'">
                                    {{ getInitial(r.name) }}
                                </div>
                                <span class="text-sm font-semibold text-[#1c1b1b] truncate">{{ r.name }}</span>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>
        <!-- CARA PEMESANAN SECTION -->
        <div class="bg-white py-12 md:py-16 lg:py-20">
            <div class="max-w-[1280px] mx-auto px-4 sm:px-6 lg:px-16">
                <div class="text-center mb-10 md:mb-12">
                    <span class="inline-block px-3 py-1 rounded-full bg-[#f6f3f2] border border-[#ebe7e7] text-[#454652] text-[9px] font-bold tracking-widest uppercase mb-3">Panduan</span>
                    <h2 class="font-unbounded font-bold text-[24px] sm:text-[28px] md:text-[32px] text-[#1c1b1b]">Cara Pemesanan Tiket</h2>
                    <p class="text-[#454652] text-sm sm:text-base mt-1">Ikuti langkah mudah berikut untuk memesan tiket atau sewa bus.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 md:gap-12">
                    <!-- Tiket Reguler -->
                    <div class="bg-[#fcf9f8] border border-[#ebe7e7] rounded-[12px] p-6 md:p-8">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-10 h-10 rounded-[10px] bg-[#10207a]/10 flex items-center justify-center">
                                <i class="fas fa-ticket-alt text-sm text-[#10207a]"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-[16px] text-[#1c1b1b]">Tiket Reguler AKAP</h3>
                                <p class="text-xs text-[#454652]">Perjalanan antarkota</p>
                            </div>
                        </div>
                        <div class="space-y-5">
                            <div class="flex gap-4">
                                <div class="w-7 h-7 rounded-full bg-[#10207a] text-white text-xs font-bold flex items-center justify-center shrink-0 mt-0.5">1</div>
                                <div>
                                    <p class="font-semibold text-sm text-[#1c1b1b]">Cari Jadwal</p>
                                    <p class="text-xs text-[#454652] mt-0.5">Pilih kota asal, tujuan, dan tanggal keberangkatan.</p>
                                </div>
                            </div>
                            <div class="flex gap-4">
                                <div class="w-7 h-7 rounded-full bg-[#10207a] text-white text-xs font-bold flex items-center justify-center shrink-0 mt-0.5">2</div>
                                <div>
                                    <p class="font-semibold text-sm text-[#1c1b1b]">Pilih Jadwal & Kursi</p>
                                    <p class="text-xs text-[#454652] mt-0.5">Pilih jadwal favorit dan tentukan kursi Anda.</p>
                                </div>
                            </div>
                            <div class="flex gap-4">
                                <div class="w-7 h-7 rounded-full bg-[#10207a] text-white text-xs font-bold flex items-center justify-center shrink-0 mt-0.5">3</div>
                                <div>
                                    <p class="font-semibold text-sm text-[#1c1b1b]">Bayar & Dapatkan Tiket</p>
                                    <p class="text-xs text-[#454652] mt-0.5">Bayar via transfer/QRIS dan unduh e-tiket Anda.</p>
                                </div>
                            </div>
                        </div>
                        <Link :href="route('frontend.booking.index')" class="mt-6 inline-flex items-center gap-1.5 text-sm font-semibold text-[#10207a] hover:underline">
                            Pesan Tiket <i class="fas fa-arrow-right text-xs"></i>
                        </Link>
                    </div>

                    <!-- Sewa Pariwisata -->
                    <div class="bg-[#fcf9f8] border border-[#ebe7e7] rounded-[12px] p-6 md:p-8">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-10 h-10 rounded-[10px] bg-[#10207a]/10 flex items-center justify-center">
                                <i class="fas fa-bus text-sm text-[#10207a]"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-[16px] text-[#1c1b1b]">Sewa Bus Pariwisata</h3>
                                <p class="text-xs text-[#454652]">Perjalanan rombongan & wisata</p>
                            </div>
                        </div>
                        <div class="space-y-5">
                            <div class="flex gap-4">
                                <div class="w-7 h-7 rounded-full bg-[#10207a] text-white text-xs font-bold flex items-center justify-center shrink-0 mt-0.5">1</div>
                                <div>
                                    <p class="font-semibold text-sm text-[#1c1b1b]">Isi Form Permintaan</p>
                                    <p class="text-xs text-[#454652] mt-0.5">Tentukan tujuan, tanggal, dan durasi sewa.</p>
                                </div>
                            </div>
                            <div class="flex gap-4">
                                <div class="w-7 h-7 rounded-full bg-[#10207a] text-white text-xs font-bold flex items-center justify-center shrink-0 mt-0.5">2</div>
                                <div>
                                    <p class="font-semibold text-sm text-[#1c1b1b]">Dapatkan Penawaran</p>
                                    <p class="text-xs text-[#454652] mt-0.5">Tim kami akan menghubungi dengan penawaran harga.</p>
                                </div>
                            </div>
                            <div class="flex gap-4">
                                <div class="w-7 h-7 rounded-full bg-[#10207a] text-white text-xs font-bold flex items-center justify-center shrink-0 mt-0.5">3</div>
                                <div>
                                    <p class="font-semibold text-sm text-[#1c1b1b]">Konfirmasi & Berangkat</p>
                                    <p class="text-xs text-[#454652] mt-0.5">Setujui penawaran dan nikmati perjalanan.</p>
                                </div>
                            </div>
                        </div>
                        <Link :href="route('frontend.charter.index')" class="mt-6 inline-flex items-center gap-1.5 text-sm font-semibold text-[#10207a] hover:underline">
                            Sewa Sekarang <i class="fas fa-arrow-right text-xs"></i>
                        </Link>
                    </div>
                </div>
            </div>
        </div>

        <!-- BERITA & UPDATE SECTION -->
        <div class="bg-[#fcf9f8] flex flex-col items-center py-16 md:py-24 lg:py-32">
            <div class="flex flex-col gap-6 md:gap-10 lg:gap-[48px] items-start max-w-[1280px] px-4 sm:px-6 lg:px-16 w-full">
                <div class="flex items-end justify-between w-full">
                    <div class="flex flex-col gap-[8px] items-start">
                        <h2 class=" font-unboundedfont-bold text-[#1c1b1b] text-[32px] tracking-[-0.32px] m-0">Berita & Update</h2>
                        <p class="font-normal text-[#454652] text-[16px] m-0">Informasi terbaru seputar layanan dan promo Tunggal Jaya.</p>
                    </div>
                    <a href="#" class="flex items-center gap-[4px] text-[#10207a] hover:underline font-semibold text-[16px]">
                        Lihat Semua
                        <i class="fas fa-chevron-right text-xs"></i>
                    </a>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 md:gap-6 lg:gap-[32px] w-full">
                    <Link :href="route('frontend.news.show', news.slug)" v-for="news in latestNews" :key="news.id" class="flex flex-col gap-[24px] items-start w-full group cursor-pointer">
                        <div class="relative overflow-hidden rounded-[8px] w-full shadow-sm">
                            <img :src="news.image_url" :alt="news.title" class="w-full h-[204px] object-cover group-hover:scale-105 transition-transform duration-300 bg-gray-200" />
                            <div class="absolute top-[16px] left-[16px] bg-[#10207a] text-white px-[12px] py-[4px] rounded-[2px] font-bold text-[12px] tracking-[0.6px] uppercase">
                                {{ news.category?.name || 'BERITA' }}
                            </div>
                        </div>
                        <div class="flex flex-col gap-[8px] items-start w-full">
                            <span class="text-[#454652] text-[16px]">{{ new Date(news.published_at || news.created_at).toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' }) }}</span>
                            <h3 class="font-bold text-[#1c1b1b] text-[18px] group-hover:text-[#10207a] transition-colors line-clamp-2">{{ news.title }}</h3>
                            <p class="text-[#454652] text-[16px] m-0 line-clamp-2">{{ news.excerpt || (news.safe_content ? news.safe_content.substring(0, 100) + '...' : '') }}</p>
                        </div>
                    </Link>
                </div>
            </div>
        </div>

        <!-- DAFTAR ARMADA SECTION -->
        <div class="bg-[#fcf9f8] flex flex-col items-center py-12 md:py-16 lg:py-24">
            <div class="flex flex-col gap-6 md:gap-10 lg:gap-[48px] items-start max-w-[1280px] px-4 sm:px-6 lg:px-16 w-full">
                <div class="flex items-end justify-between w-full">
                    <div class="flex flex-col gap-[8px] items-start">
                        <h2 class="font-unbounded font-bold text-[#1c1b1b] text-[24px] sm:text-[28px] md:text-[32px] tracking-[-0.32px] m-0">Daftar Armada</h2>
                        <p class="font-normal text-[#454652] text-[16px] m-0">Seluruh armada tunggal jaya transport.</p>
                    </div>
                    <Link :href="route('frontend.fleet.index')" class="flex items-center gap-[4px] text-[#10207a] hover:underline font-semibold text-[16px]">
                        Lihat Semua
                        <i class="fas fa-chevron-right text-xs"></i>
                    </Link>
                </div>

                <!-- Category Filter -->
                <div class="flex gap-3">
                    <button @click="fleetCategory = 'all'" class="px-5 py-2 rounded-xl text-xs font-bold uppercase tracking-wider transition-all duration-300 border"
                        :class="fleetCategory === 'all' ? 'bg-[#10207a] text-white border-[#10207a] shadow-lg shadow-[#10207a]/30' : 'bg-white text-gray-600 border-gray-200 hover:border-gray-400'">
                        Semua
                    </button>
                    <button @click="fleetCategory = 'akap'" class="px-5 py-2 rounded-xl text-xs font-bold uppercase tracking-wider transition-all duration-300 border"
                        :class="fleetCategory === 'akap' ? 'bg-[#10207a] text-white border-[#10207a] shadow-lg shadow-[#10207a]/30' : 'bg-white text-gray-600 border-gray-200 hover:border-gray-400'">
                        AKAP
                    </button>
                    <button @click="fleetCategory = 'pariwisata'" class="px-5 py-2 rounded-xl text-xs font-bold uppercase tracking-wider transition-all duration-300 border"
                        :class="fleetCategory === 'pariwisata' ? 'bg-[#10207a] text-white border-[#10207a] shadow-lg shadow-[#10207a]/30' : 'bg-white text-gray-600 border-gray-200 hover:border-gray-400'">
                        Pariwisata
                    </button>
                </div>

                <div v-if="filteredFleet.length > 0" class="grid grid-cols-1 md:grid-cols-3 gap-4 md:gap-6 lg:gap-[24px] w-full">
                    <div v-for="bus in filteredFleet" :key="bus.id"
                        class="bg-white rounded-[12px] overflow-hidden border border-[#ebe7e7] shadow-sm hover:shadow-lg transition-shadow group">
                        <div class="relative h-[200px] overflow-hidden">
                            <img :src="bus.image_url || '/img/noImg.png'" :alt="bus.name"
                                class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" />
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>
                            <div class="absolute bottom-[16px] left-[16px] right-[16px] flex items-center justify-between">
                                <span class="text-white font-bold text-[16px]">{{ bus.name }}</span>
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wide bg-[#10207a] text-white">
                                    {{ bus.bus_category === 'pariwisata' ? 'Pariwisata' : 'AKAP' }}
                                </span>
                            </div>
                        </div>
                        <div class="p-[16px]">
                            <div class="flex items-center gap-3 text-[13px] text-gray-500 mb-[10px]">
                                <span>{{ bus.bus_type }}</span>
                                <span>&middot;</span>
                                <span>{{ bus.capacity }} Kursi</span>
                                <span>&middot;</span>
                                <span class="font-mono">{{ bus.plate_number }}</span>
                            </div>
                            <p class="text-[14px] text-gray-600 leading-relaxed line-clamp-2 mb-[14px]">
                                {{ bus.description || 'Armada premium dengan fasilitas terbaik.' }}
                            </p>
                            <Link :href="bus.bus_category === 'pariwisata' ? route('frontend.charter.index') : route('frontend.booking.index')"
                                class="text-[#10207a] font-semibold text-[13px] hover:underline flex items-center gap-1">
                                {{ bus.bus_category === 'pariwisata' ? 'Sewa Sekarang' : 'Pesan Tiket' }}
                                <i class="fas fa-arrow-right text-xs"></i>
                            </Link>
                        </div>
                    </div>
                </div>
                <div v-else class="w-full text-center py-12 text-gray-400">
                    <i class="fas fa-bus text-4xl mb-4 opacity-30"></i>
                    <p>Tidak ada armada ditemukan.</p>
                </div>
            </div>
        </div>

        <!-- ARMADA PREMIUM KAMI SECTION -->
        <div class="bg-[#111111] flex flex-col items-center py-16 md:py-24 lg:py-32">
            <div class="flex flex-col gap-8 md:gap-16 lg:gap-[96px] items-center max-w-[1280px] px-4 sm:px-6 lg:px-16 w-full">
                
                <!-- Section Header -->
                <div class="flex flex-col gap-[24px] items-center text-center w-full max-w-3xl">
                    <div class="border border-[#767683] px-[16px] py-[6px] rounded-[12px]">
                        <span class="font-bold text-[#c6c5d3] text-[12px] tracking-[1.2px] uppercase">ARMADA PREMIUM KAMI</span>
                    </div>
                    <h2 class="font-unbounded font-black text-[28px] sm:text-[36px] md:text-[40px] lg:text-[48px] text-white tracking-[-0.96px] m-0 leading-tight">Kemewahan dalam Setiap Perjalanan</h2>
                    <p class="text-[16px] text-[#e5e2e1] opacity-90 m-0 leading-relaxed">
                        Pilih layanan yang sesuai dengan kebutuhan Anda. Dari perjalanan antarkota kelas eksekutif hingga sewa bus pariwisata premium.
                    </p>
                </div>

                <!-- Fleet Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 md:gap-10 lg:gap-[64px] w-full">
                    <!-- Kelas Eksekutif -->
                    <div class="flex flex-col w-full group">
                        <div class="relative h-[400px] w-full rounded-[16px] overflow-hidden mb-[32px] shadow-2xl border border-white/10">
                            <img src="/img/primadona.webp" alt="Kelas Eksekutif" class="absolute w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" />
                            <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/20 to-transparent"></div>
                            <div class="absolute bottom-[24px] left-[24px] right-[24px]">
                                <h3 class="font-unbounded text-white text-[18px] sm:text-[20px] md:text-[24px] font-bold">Kelas Eksekutif (AKAP)</h3>
                            </div>
                        </div>
                        <p class="text-[#e5e2e1] text-[16px] opacity-90 leading-[26px] mb-[24px]">
                            Nikmati pengalaman perjalanan yang tak terlupakan dengan armada Jetbus 3+ terbaru kami. Dilengkapi dengan suspensi udara, kursi ergonomis yang luas, dan fasilitas hiburan personal untuk memastikan kenyamanan Anda dari kota asal hingga tujuan.
                        </p>
                        <ul class="flex flex-col gap-[12px] mb-[32px] text-white text-[16px]">
                            <li class="flex items-center gap-[12px]">
                                <i class="fas fa-check-circle text-[#f3e72b]"></i> Mesin Hino/Scania Terbaru
                            </li>
                            <li class="flex items-center gap-[12px]">
                                <i class="fas fa-check-circle text-[#f3e72b]"></i> Fasilitas Toilet Bersih & Kabin Kedap Suara
                            </li>
                        </ul>
                        <Link :href="route('frontend.booking.index')" class="border border-white hover:bg-white hover:text-black text-white px-[32px] py-[12px] rounded-[12px] font-semibold text-[14px] text-center tracking-wide uppercase transition-colors self-start">
                            Cek Jadwal & Tiket
                        </Link>
                    </div>

                    <!-- Bus Pariwisata -->
                    <div class="flex flex-col w-full group">
                        <div class="relative h-[400px] w-full rounded-[16px] overflow-hidden mb-[32px] shadow-2xl border border-white/10">
                            <img :src="pariwisataBusImage" alt="Sewa Bus Pariwisata" class="absolute w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" />
                            <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/20 to-transparent"></div>
                            <div class="absolute bottom-[24px] left-[24px] right-[24px]">
                                <h3 class="font-unbounded text-white text-[18px] sm:text-[20px] md:text-[24px] font-bold">Sewa Bus Pariwisata</h3>
                            </div>
                        </div>
                        <p class="text-[#e5e2e1] text-[16px] opacity-90 leading-[26px] mb-[24px]">
                            Hadirkan pengalaman tak terlupakan untuk rombongan Anda dengan armada Big Bus premium kami. Dilengkapi fasilitas eksekutif untuk kenyamanan maksimal sepanjang perjalanan dengan rute yang dapat disesuaikan.
                        </p>
                        <ul class="flex flex-col gap-[12px] mb-[32px] text-white text-[16px]">
                            <li class="flex items-center gap-[12px]">
                                <i class="fas fa-check-circle text-[#f3e72b]"></i> Rute Fleksibel Sesuai Itinerary
                            </li>
                            <li class="flex items-center gap-[12px]">
                                <i class="fas fa-check-circle text-[#f3e72b]"></i> Leg rest, AVOD, & Toilet
                            </li>
                        </ul>
                        <Link :href="route('frontend.charter.index')" class="bg-white text-black hover:bg-gray-200 px-[32px] py-[12px] rounded-[12px] font-semibold text-[14px] text-center tracking-wide uppercase transition-colors self-start shadow-md">
                            Pesan Sekarang
                        </Link>
                    </div>
                </div>

                <!-- CTA Row -->
                <div class="flex flex-col md:flex-row items-center gap-[24px] w-full justify-center">
                    <Link :href="route('frontend.booking.index')" class="bg-white text-black hover:bg-gray-200 px-[40px] py-[16px] rounded-[14px] font-bold text-[16px] tracking-wide uppercase transition-colors shadow-xl">
                        <i class="fas fa-ticket-alt mr-2"></i> Pesan Tiket AKAP
                    </Link>
                    <Link :href="route('frontend.charter.index')" class="border border-white hover:bg-white hover:text-black text-white px-[40px] py-[16px] rounded-[14px] font-bold text-[16px] tracking-wide uppercase transition-colors">
                        <i class="fas fa-bus mr-2"></i> Sewa Bus Pariwisata
                    </Link>
                    <Link :href="route('frontend.fleet.index')" class="border border-[#767683] hover:border-white text-[#c6c5d3] hover:text-white px-[40px] py-[16px] rounded-[14px] font-bold text-[16px] tracking-wide uppercase transition-colors">
                        <i class="fas fa-info-circle mr-2"></i> Lihat Armada
                    </Link>
                </div>
            </div>
        </div>
</template>

<style scoped>
.snap-x {
    scroll-snap-type: x mandatory;
    scroll-behavior: smooth;
    -webkit-overflow-scrolling: touch;
}

@keyframes scrollLeft {
    0% { transform: translateX(0); }
    100% { transform: translateX(-50%); }
}

@keyframes scrollRight {
    0% { transform: translateX(-50%); }
    100% { transform: translateX(0); }
}

.animate-scroll-left {
    animation: scrollLeft 60s linear infinite;
    width: fit-content;
}

.animate-scroll-right {
    animation: scrollRight 60s linear infinite;
    width: fit-content;
}

.animate-scroll-left:hover,
.animate-scroll-right:hover {
    animation-play-state: paused;
}
</style>
