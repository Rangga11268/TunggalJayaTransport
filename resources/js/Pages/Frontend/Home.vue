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
    destination: "",
    date: "",
    duration: "1",
});

const openWhatsAppInquiry = () => {
    const dest = charterForm.destination || "-";
    const date = charterForm.date || "-";
    const text = `Halo Tunggal Jaya, saya ingin bertanya tentang sewa bus pariwisata.\n\nTujuan: ${dest}\nTanggal: ${date}\nDurasi: ${charterForm.duration} Hari\n\nMohon info ketersediaan dan harganya. Terima kasih.`;
    window.open(`https://wa.me/6281234567890?text=${encodeURIComponent(text)}`, '_blank');
};
</script>

<template>
    <Head title="Beranda - Tunggal Jaya Transport" />

    <div class="relative size-full  bg-[#fcf9f8]">
        <!-- HERO SECTION -->
        <div class="relative content-stretch flex items-center justify-center left-0 min-h-[800px] pb-[128px] pt-[192px] right-0 top-0">
            <div class="absolute content-stretch flex flex-col inset-0 items-start justify-center">
                <div class="flex-[1_0_0] min-h-px relative w-full">
                    <div class="absolute inset-0 overflow-hidden pointer-events-none">
                        <!-- Hero Background (Replacing local asset with original image style) -->
                        <img class="absolute block h-full left-0 object-cover top-0 w-full z-0" src="/img/primadona.webp" alt="Hero Image" />
                    </div>
                </div>
                <div class="absolute bg-gradient-to-r from-[rgba(0,0,0,0.8)] inset-0 to-[rgba(0,0,0,0)] via-1/2 via-[rgba(0,0,0,0.5)]"></div>
                <div class="absolute bg-gradient-to-t bottom-0 from-[#fcf9f8] h-[128px] left-0 to-[rgba(252,249,248,0)] w-full"></div>
            </div>

            <div class="flex-[1_0_0] max-w-[1280px] w-full min-w-px relative px-16">
                <div class="content-stretch flex flex-col gap-[24px] items-start max-w-[672px]">
                    <div class="content-stretch flex flex-col items-start relative shrink-0 w-full">
                        <div class="flex flex-col font-unbounded font-extrabold justify-center leading-[0] relative shrink-0 text-[56px] text-white tracking-[-1.4px] w-full">
                            <p class="leading-[61.6px] mb-0">Perjalanan Nyaman,</p>
                            <p class="leading-[61.6px]">Tiba Tepat Waktu</p>
                        </div>
                    </div>
                    <div class="content-stretch flex flex-col items-start max-w-[576px] relative shrink-0 w-full">
                        <div class="flex flex-col font-normal justify-center leading-[0] relative shrink-0 text-[20px] text-[rgba(255,255,255,0.9)]">
                            <p class="leading-[30px] mb-0">Pesan tiket bus AKAP kelas eksekutif atau sewa armada bus</p>
                            <p class="leading-[30px] mb-0">pariwisata premium untuk perjalanan yang tak terlupakan</p>
                            <p class="leading-[30px]">bersama Tunggal Jaya.</p>
                        </div>
                    </div>
                </div>

                <!-- Booking Console -->
                <div class="mt-12 bg-white border border-[#f0edec] border-solid content-stretch flex flex-col gap-[24px] items-center max-w-[1000px] p-[13px] rounded-[16px] shadow-xl relative z-10 w-full">
                    <div class="bg-[#f6f3f2] relative rounded-[8px] shrink-0">
                        <div class="bg-clip-padding border-0 border-[transparent] border-solid content-stretch flex items-start p-[4px] relative size-full">
                            <button @click="activeTab = 'reguler'" :class="[activeTab === 'reguler' ? 'bg-white drop-shadow-sm text-[#10207a]' : 'text-[#454652] hover:bg-gray-100']" class="content-stretch flex gap-[8px] items-center justify-center px-[32px] py-[12px] relative rounded-[4px] shrink-0 font-semibold text-[14px] transition-colors">
                                <i class="fas fa-ticket-alt"></i>
                                Tiket Reguler
                            </button>
                            <button @click="activeTab = 'charter'" :class="[activeTab === 'charter' ? 'bg-white drop-shadow-sm text-[#10207a]' : 'text-[#454652] hover:bg-gray-100']" class="content-stretch flex gap-[8px] items-center justify-center px-[32px] py-[12px] relative rounded-[4px] shrink-0 font-semibold text-[14px] transition-colors">
                                <i class="fas fa-bus"></i>
                                Sewa Bus
                            </button>
                        </div>
                    </div>

                    <!-- Search Form -->
                    <div class="relative shrink-0 w-full">
                        <form v-if="activeTab === 'reguler'" @submit.prevent="submitSearch" class="bg-clip-padding border-0 border-[transparent] border-solid flex gap-[16px] items-end p-[16px] relative size-full">
                            <div class="flex flex-[1_0_0] flex-col gap-[8px] items-start min-w-px">
                                <label class="font-bold text-[#454652] text-[12px] tracking-[0.6px] uppercase">Dari</label>
                                <select v-model="form.origin" class="w-full bg-white border border-[#c6c5d3] rounded-[8px] pl-4 pr-10 py-4 font-semibold text-[#1c1b1b] focus:ring-2 focus:ring-[#10207a] outline-none cursor-pointer appearance-none">
                                    <option value="" disabled>Pilih Kota Asal</option>
                                    <option v-for="origin in origins" :key="origin" :value="origin">{{ origin }}</option>
                                </select>
                            </div>

                            <div class="flex items-center justify-center pt-8">
                                <div class="bg-[#f0edec] rounded-full p-2 text-[#454652] cursor-pointer hover:bg-gray-200">
                                    <i class="fas fa-exchange-alt"></i>
                                </div>
                            </div>

                            <div class="flex flex-[1_0_0] flex-col gap-[8px] items-start min-w-px">
                                <label class="font-bold text-[#454652] text-[12px] tracking-[0.6px] uppercase">Ke</label>
                                <select v-model="form.destination" class="w-full bg-white border border-[#c6c5d3] rounded-[8px] pl-4 pr-10 py-4 font-semibold text-[#1c1b1b] focus:ring-2 focus:ring-[#10207a] outline-none cursor-pointer appearance-none">
                                    <option value="" disabled>Pilih Kota Tujuan</option>
                                    <option v-for="dest in destinations" :key="dest" :value="dest">{{ dest }}</option>
                                </select>
                            </div>

                            <div class="flex flex-[1_0_0] flex-col gap-[8px] items-start min-w-px">
                                <label class="font-bold text-[#454652] text-[12px] tracking-[0.6px] uppercase">Tanggal</label>
                                <input v-model="form.date" type="date" :min="today" class="w-full bg-white border border-[#c6c5d3] rounded-[8px] pl-4 pr-4 py-4 font-semibold text-[#1c1b1b] focus:ring-2 focus:ring-[#10207a] outline-none cursor-pointer">
                            </div>

                            <button type="submit" class="bg-[#10207a] hover:bg-[#0c185e] text-white px-8 py-4 rounded-[8px] font-semibold text-[16px] transition-colors h-[58px] flex items-center justify-center gap-2">
                                <i class="fas fa-search"></i>
                                Cari
                            </button>
                        </form>

                        <form v-if="activeTab === 'charter'" @submit.prevent="openWhatsAppInquiry" class="bg-clip-padding border-0 border-[transparent] border-solid flex gap-[16px] items-end p-[16px] relative size-full">
                            <div class="flex flex-[1_0_0] flex-col gap-[8px] items-start min-w-px">
                                <label class="font-bold text-[#454652] text-[12px] tracking-[0.6px] uppercase">Tujuan Wisata</label>
                                <input v-model="charterForm.destination" type="text" placeholder="Contoh: Bali, Jogja..." class="w-full bg-white border border-[#c6c5d3] rounded-[8px] pl-4 pr-4 py-4 font-semibold text-[#1c1b1b] focus:ring-2 focus:ring-[#10207a] outline-none">
                            </div>
                            
                            <div class="flex flex-[1_0_0] flex-col gap-[8px] items-start min-w-px">
                                <label class="font-bold text-[#454652] text-[12px] tracking-[0.6px] uppercase">Tanggal Berangkat</label>
                                <input v-model="charterForm.date" type="date" :min="today" class="w-full bg-white border border-[#c6c5d3] rounded-[8px] pl-4 pr-4 py-4 font-semibold text-[#1c1b1b] focus:ring-2 focus:ring-[#10207a] outline-none">
                            </div>

                            <div class="flex flex-[1_0_0] flex-col gap-[8px] items-start min-w-px">
                                <label class="font-bold text-[#454652] text-[12px] tracking-[0.6px] uppercase">Durasi (Hari)</label>
                                <select v-model="charterForm.duration" class="w-full bg-white border border-[#c6c5d3] rounded-[8px] pl-4 pr-10 py-4 font-semibold text-[#1c1b1b] focus:ring-2 focus:ring-[#10207a] outline-none appearance-none">
                                    <option value="1">1 Hari</option>
                                    <option value="2">2 Hari</option>
                                    <option value="3">3 Hari</option>
                                    <option value="4">4+ Hari</option>
                                </select>
                            </div>

                            <button type="submit" class="bg-[#25D366] hover:bg-[#128C7E] text-white px-8 py-4 rounded-[8px] font-semibold text-[16px] transition-colors h-[58px] flex items-center justify-center gap-2">
                                <i class="fab fa-whatsapp text-lg"></i>
                                Tanya Admin
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- POPULAR ROUTES SECTION -->
        <div class="bg-[#f8f9fa] flex flex-col items-center py-[128px]">
            <div class="flex flex-col gap-[48px] items-start max-w-[1280px] px-[64px] w-full">
                <div class="flex items-end justify-between w-full">
                    <div class="flex flex-col gap-[8px] items-start">
                        <h2 class="font-unbounded font-bold text-[#1c1b1b] text-[32px] tracking-[-0.32px] m-0">Rute Perjalanan Populer</h2>
                        <p class="font-normal text-[#454652] text-[16px] m-0">Pilihan destinasi favorit penumpang kami.</p>
                    </div>
                    <div class="flex gap-[8px] items-start hidden sm:flex">
                        <button class="bg-white border border-[#c6c5d3] flex items-center justify-center rounded-[12px] size-[40px] hover:bg-gray-50 transition-colors"><i class="fas fa-arrow-left text-gray-500"></i></button>
                        <button class="bg-white border border-[#c6c5d3] flex items-center justify-center rounded-[12px] size-[40px] hover:bg-gray-50 transition-colors"><i class="fas fa-arrow-right text-gray-500"></i></button>
                    </div>
                </div>

                <div class="w-full overflow-x-auto pb-8 snap-x">
                    <div class="flex gap-[24px] w-max">
                        <!-- Example Card 1 -->
                        <div class="bg-white border border-[#ebe7e7] drop-shadow-sm rounded-[8px] w-[360px] flex flex-col p-6 snap-center">
                            <div class="flex items-center justify-between w-full mb-6">
                                <span class="bg-[#dfe0ff] text-[#000e5e] px-3 py-1 rounded-[4px] font-bold text-[12px] tracking-wider uppercase">Eksekutif</span>
                                <i class="fas fa-star text-yellow-400"></i>
                            </div>
                            <div class="flex items-center gap-4 mb-4">
                                <span class="font-unbounded font-semibold text-[#1c1b1b] text-[20px]">Kuningan</span>
                                <i class="fas fa-arrow-right text-gray-400"></i>
                                <span class="font-unbounded font-semibold text-[#1c1b1b] text-[20px]">Jakarta</span>
                            </div>
                            <p class="font-normal text-[#454652] text-[16px] mb-6">Keberangkatan Pagi & Malam</p>
                            <div class="border-t border-[#ebe7e7] pt-4 mt-auto">
                                <p class="font-bold text-[#454652] text-[12px] tracking-wider uppercase mb-1">Mulai Dari</p>
                                <p class="font-unbounded font-semibold text-[#10207a] text-[24px]">Rp 150.000</p>
                                <Link :href="route('frontend.booking.index', { origin: 'Kuningan', destination: 'Jakarta' })" class="mt-4 w-full bg-[#10207a] text-white py-3 rounded-[8px] font-semibold text-[14px] text-center block hover:bg-[#0c185e] transition-colors">
                                    Pesan Tiket
                                </Link>
                            </div>
                        </div>

                        <!-- Example Card 2 -->
                        <div class="bg-white border border-[#ebe7e7] drop-shadow-sm rounded-[8px] w-[360px] flex flex-col p-6 snap-center">
                            <div class="flex items-center justify-between w-full mb-6">
                                <span class="bg-[#dfe0ff] text-[#000e5e] px-3 py-1 rounded-[4px] font-bold text-[12px] tracking-wider uppercase">Super Eksekutif</span>
                                <i class="fas fa-star text-yellow-400"></i>
                            </div>
                            <div class="flex items-center gap-4 mb-4">
                                <span class="font-unbounded font-semibold text-[#1c1b1b] text-[20px]">Cirebon</span>
                                <i class="fas fa-arrow-right text-gray-400"></i>
                                <span class="font-unbounded font-semibold text-[#1c1b1b] text-[20px]">Bandung</span>
                            </div>
                            <p class="font-normal text-[#454652] text-[16px] mb-6">Via Tol Cipali</p>
                            <div class="border-t border-[#ebe7e7] pt-4 mt-auto">
                                <p class="font-bold text-[#454652] text-[12px] tracking-wider uppercase mb-1">Mulai Dari</p>
                                <p class="font-unbounded font-semibold text-[#10207a] text-[24px]">Rp 120.000</p>
                                <Link :href="route('frontend.booking.index', { origin: 'Cirebon', destination: 'Bandung' })" class="mt-4 w-full bg-[#10207a] text-white py-3 rounded-[8px] font-semibold text-[14px] text-center block hover:bg-[#0c185e] transition-colors">
                                    Pesan Tiket
                                </Link>
                            </div>
                        </div>

                        <!-- Example Card 3 -->
                        <div class="bg-white border border-[#ebe7e7] drop-shadow-sm rounded-[8px] w-[360px] flex flex-col p-6 snap-center">
                            <div class="flex items-center justify-between w-full mb-6">
                                <span class="bg-[#dfe0ff] text-[#000e5e] px-3 py-1 rounded-[4px] font-bold text-[12px] tracking-wider uppercase">Eksekutif</span>
                                <i class="fas fa-star text-yellow-400"></i>
                            </div>
                            <div class="flex items-center gap-4 mb-4">
                                <span class="font-unbounded font-semibold text-[#1c1b1b] text-[20px]">Majalengka</span>
                                <i class="fas fa-arrow-right text-gray-400"></i>
                                <span class="font-unbounded font-semibold text-[#1c1b1b] text-[20px]">Bekasi</span>
                            </div>
                            <p class="font-normal text-[#454652] text-[16px] mb-6">Keberangkatan Sore</p>
                            <div class="border-t border-[#ebe7e7] pt-4 mt-auto">
                                <p class="font-bold text-[#454652] text-[12px] tracking-wider uppercase mb-1">Mulai Dari</p>
                                <p class="font-unbounded font-semibold text-[#10207a] text-[24px]">Rp 130.000</p>
                                <Link :href="route('frontend.booking.index', { origin: 'Majalengka', destination: 'Bekasi' })" class="mt-4 w-full bg-[#10207a] text-white py-3 rounded-[8px] font-semibold text-[14px] text-center block hover:bg-[#0c185e] transition-colors">
                                    Pesan Tiket
                                </Link>
                            </div>
                        </div>

                        <!-- Example Card 4 -->
                        <div class="bg-white border border-[#ebe7e7] drop-shadow-sm rounded-[8px] w-[360px] flex flex-col p-6 snap-center">
                            <div class="flex items-center justify-between w-full mb-6">
                                <span class="bg-[#f3e72b] text-[#1e1c00] px-3 py-1 rounded-[4px] font-bold text-[12px] tracking-wider uppercase">Sleeper</span>
                                <i class="fas fa-star text-yellow-400"></i>
                            </div>
                            <div class="flex items-center gap-4 mb-4">
                                <span class="font-unbounded font-semibold text-[#1c1b1b] text-[20px]">Kuningan</span>
                                <i class="fas fa-arrow-right text-gray-400"></i>
                                <span class="font-unbounded font-semibold text-[#1c1b1b] text-[20px]">Tangerang</span>
                            </div>
                            <p class="font-normal text-[#454652] text-[16px] mb-6">Fasilitas Penuh</p>
                            <div class="border-t border-[#ebe7e7] pt-4 mt-auto">
                                <p class="font-bold text-[#454652] text-[12px] tracking-wider uppercase mb-1">Mulai Dari</p>
                                <p class="font-unbounded font-semibold text-[#10207a] text-[24px]">Rp 250.000</p>
                                <Link :href="route('frontend.booking.index', { origin: 'Kuningan', destination: 'Tangerang' })" class="mt-4 w-full bg-[#10207a] text-white py-3 rounded-[8px] font-semibold text-[14px] text-center block hover:bg-[#0c185e] transition-colors">
                                    Pesan Tiket
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- TESTIMONIALS SECTION -->
        <div class="bg-[#f6f3f2] border-y border-[#ebe7e7] flex flex-col items-center py-[96px]">
            <div class="flex flex-col gap-[48px] items-center max-w-[1280px] px-[64px] w-full">
                <div class="flex flex-col gap-[16px] items-center w-full text-center">
                    <h2 class=" font-unboundedfont-bold text-[#1c1b1b] text-[32px] tracking-[-0.32px] m-0">Testimoni Pelanggan</h2>
                    <p class="font-normal text-[#454652] text-[16px] m-0 max-w-2xl">
                        Pengalaman nyata dari pelanggan setia yang telah mempercayakan perjalanannya bersama kami.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-[32px] w-full">
                    <!-- Testimonial 1 -->
                    <div class="bg-white border border-[#e5e2e1] drop-shadow-sm rounded-[8px] p-8 flex flex-col">
                        <div class="flex text-yellow-400 mb-6">
                            <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                        </div>
                        <p class="italic font-normal text-[#454652] text-[16px] leading-[24px] mb-8 grow">
                            "Perjalanan dari Kuningan ke Jakarta terasa sangat cepat dan nyaman. AC dingin, kursi super empuk, dan supir sangat profesional. Sangat direkomendasikan!"
                        </p>
                        <div class="flex items-center gap-4">
                            <div class="bg-[#dfe0ff] text-[#000e5e] font-bold rounded-full w-[48px] h-[48px] flex items-center justify-center text-[16px]">R</div>
                            <div>
                                <p class="font-semibold text-[#1c1b1b] m-0">Rizky Ananda</p>
                                <p class="font-normal text-[#454652] text-[14px] m-0">Rute Kuningan - Jakarta</p>
                            </div>
                        </div>
                    </div>

                    <!-- Testimonial 2 -->
                    <div class="bg-white border border-[#e5e2e1] drop-shadow-sm rounded-[8px] p-8 flex flex-col">
                        <div class="flex text-yellow-400 mb-6">
                            <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                        </div>
                        <p class="italic font-normal text-[#454652] text-[16px] leading-[24px] mb-8 grow">
                            "Fasilitas sleeper class-nya juara! Bisa tidur nyenyak sepanjang perjalanan. Snack yang diberikan juga enak. Pengalaman bus terbaik sejauh ini."
                        </p>
                        <div class="flex items-center gap-4">
                            <div class="bg-[#ffdad6] text-[#410002] font-bold rounded-full w-[48px] h-[48px] flex items-center justify-center text-[16px]">S</div>
                            <div>
                                <p class="font-semibold text-[#1c1b1b] m-0">Siti Fatimah</p>
                                <p class="font-normal text-[#454652] text-[14px] m-0">Rute Cirebon - Bandung</p>
                            </div>
                        </div>
                    </div>

                    <!-- Testimonial 3 -->
                    <div class="bg-white border border-[#e5e2e1] drop-shadow-sm rounded-[8px] p-8 flex flex-col">
                        <div class="flex text-yellow-400 mb-6">
                            <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star-half-alt"></i>
                        </div>
                        <p class="italic font-normal text-[#454652] text-[16px] leading-[24px] mb-8 grow">
                            "Harga terjangkau tapi fasilitas berani diadu dengan yang premium. Keberangkatan selalu on-time, sangat menghargai waktu penumpang."
                        </p>
                        <div class="flex items-center gap-4">
                            <div class="bg-[#f3e72b] text-[#1e1c00] font-bold rounded-full w-[48px] h-[48px] flex items-center justify-center text-[16px]">D</div>
                            <div>
                                <p class="font-semibold text-[#1c1b1b] m-0">Deni Saputra</p>
                                <p class="font-normal text-[#454652] text-[14px] m-0">Rute Majalengka - Bekasi</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- BERITA & UPDATE SECTION -->
        <div class="bg-[#fcf9f8] flex flex-col items-center py-[128px]">
            <div class="flex flex-col gap-[48px] items-start max-w-[1280px] px-[64px] w-full">
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
                <div class="grid grid-cols-1 md:grid-cols-3 gap-[32px] w-full">
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

        <!-- ARMADA PREMIUM KAMI SECTION -->
        <div class="bg-[#111111] flex flex-col items-center py-[128px]">
            <div class="flex flex-col gap-[96px] items-center max-w-[1280px] px-[64px] w-full">
                
                <!-- Section Header -->
                <div class="flex flex-col gap-[24px] items-center text-center w-full max-w-3xl">
                    <div class="border border-[#767683] px-[16px] py-[6px] rounded-[12px]">
                        <span class="font-bold text-[#c6c5d3] text-[12px] tracking-[1.2px] uppercase">ARMADA PREMIUM KAMI</span>
                    </div>
                    <h2 class=" font-unboundedfont-unbounded font-black text-[48px] text-white tracking-[-0.96px] m-0 leading-tight">Kemewahan dalam Setiap Perjalanan</h2>
                    <p class=" text-[16px] text-[#e5e2e1] opacity-90 m-0 leading-relaxed">
                        Pilih layanan yang sesuai dengan kebutuhan Anda. Dari perjalanan antarkota kelas eksekutif hingga sewa bus pariwisata premium.
                    </p>
                </div>

                <!-- Fleet Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-[64px] w-full">
                    <!-- Kelas Eksekutif -->
                    <div class="flex flex-col w-full group">
                        <div class="relative h-[400px] w-full rounded-[16px] overflow-hidden mb-[32px] shadow-2xl border border-white/10">
                            <img src="/img/primadona.webp" alt="Kelas Eksekutif" class="absolute w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" />
                            <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/20 to-transparent"></div>
                            <div class="absolute bottom-[24px] left-[24px] right-[24px]">
                                <h3 class="font-unbounded text-white text-[24px] font-bold">Kelas Eksekutif (AKAP)</h3>
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
                        <a href="#" class="border border-white hover:bg-white hover:text-black text-white px-[32px] py-[12px] rounded-[12px] font-semibold text-[14px] text-center tracking-wide uppercase transition-colors self-start">
                            Jadwal & Tiket
                        </a>
                    </div>

                    <!-- Bus Pariwisata -->
                    <div class="flex flex-col w-full group">
                        <div class="relative h-[400px] w-full rounded-[16px] overflow-hidden mb-[32px] shadow-2xl border border-white/10">
                            <img src="/img/interiorBus.png" alt="Sewa Bus Pariwisata" class="absolute w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" onerror="this.src='https://placehold.co/600x400?text=Interior+Bus'" />
                            <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/20 to-transparent"></div>
                            <div class="absolute bottom-[24px] left-[24px] right-[24px]">
                                <h3 class="font-unbounded text-white text-[24px] font-bold">Sewa Bus Pariwisata</h3>
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
                        <a href="#" class="bg-white text-black hover:bg-gray-200 px-[32px] py-[12px] rounded-[12px] font-semibold text-[14px] text-center tracking-wide uppercase transition-colors self-start shadow-md">
                            Pesan Bus Pariwisata
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
/* Optional: Adding some smooth scrolling to snap containers */
.snap-x {
    scroll-snap-type: x mandatory;
    scroll-behavior: smooth;
    -webkit-overflow-scrolling: touch;
}
</style>
