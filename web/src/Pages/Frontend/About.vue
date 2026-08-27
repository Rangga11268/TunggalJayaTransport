<script setup>
import FrontendLayout from "@/Layouts/FrontendLayout.vue";
import { Head, Link } from "@inertiajs/vue3";
import { ref } from "vue";

defineOptions({ layout: FrontendLayout });

const activeFaq = ref(null);

const toggleFaq = (id) => {
    activeFaq.value = activeFaq.value === id ? null : id;
};

const faqs = [
    {
        id: 1,
        question: "Bagaimana cara memesan tiket?",
        answer: "Anda dapat memesan tiket melalui website kami dengan memilih rute, tanggal, dan kursi. Pembayaran dapat dilakukan secara online dengan berbagai metode yang tersedia.",
    },
    {
        id: 2,
        question: "Apakah tiket bisa di-refund?",
        answer: "Kebijakan pengembalian dana (refund) bergantung pada jenis tiket dan waktu pembatalan. Tiket Executive dan di atasnya umumnya mendukung refund dengan potongan biaya administrasi.",
    },
    {
        id: 3,
        question: "Apakah ada fasilitas makan?",
        answer: "Ya, untuk perjalanan jarak jauh kelas Executive dan Suite, kami menyediakan layanan makan prasmanan gratis satu kali di rest area mitra kami.",
    },
    {
        id: 4,
        question: "Berapa kapasitas bagasi per penumpang?",
        answer: "Setiap penumpang berhak membawa bagasi kabin maksimal 7kg dan bagasi bawah maksimal 20kg tanpa biaya tambahan.",
    },
];

const stats = [
    { label: "Armada Modern", value: "100+" },
    { label: "Rute Tujuan", value: "50+" },
    { label: "Tahun Pengalaman", value: "50+" },
    { label: "Penumpang Puas", value: "1M+" },
];

const videoRef = ref(null);
const isPlaying = ref(true); // Default autoplay

const toggleVideo = () => {
    if (videoRef.value) {
        if (isPlaying.value) {
            videoRef.value.pause();
        } else {
            videoRef.value.play();
        }
        isPlaying.value = !isPlaying.value;
    }
};
</script>

<template>
    <Head title="Tentang Kami" />

    <div class="min-h-screen bg-[#fcf9f8]">
        <!-- Hero -->
        <div class="pt-28 pb-16 px-4 sm:px-6 lg:px-8 text-center">
            <span class="inline-block px-4 py-1.5 rounded-full bg-white border border-[#ebe7e7] text-[#10207a] text-[11px] font-bold tracking-widest uppercase mb-5 shadow-sm">
                Sejak 1973
            </span>
            <h1 class="font-unbounded font-black text-4xl md:text-6xl text-[#1c1b1b] mb-4">
                Evolusi Transportasi
            </h1>
            <p class="text-[#454652] text-[16px] max-w-3xl mx-auto">
                Dari sepeda penjual ubi hingga menjadi raja jalanan modern. Kisah ketangguhan, inovasi, dan dedikasi melayani perjalanan nusantara.
            </p>
        </div>

        <!-- Story -->
        <section class="py-16 bg-white border-y border-[#ebe7e7]">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                    <div>
                        <h2 class="font-unbounded font-bold text-[22px] sm:text-[24px] md:text-[28px] text-[#1c1b1b] mb-6">Berawal dari Tekad Kuat</h2>
                        <div class="space-y-4 text-[#454652] text-[15px] leading-relaxed">
                            <p>Semua bermula dari H. Asim pada tahun 1973. Tanpa modal besar, hanya bermodalkan keberanian dan sepeda onthel, beliau merintis usaha dengan berjualan ubi dari Sumatera hingga Jakarta.</p>
                            <p>Mimpi itu mahal, namun kerja keras tak pernah mengkhianati. Dengan tabungan 100 juta rupiah hasil keringat bertahun-tahun, beliau membeli satu unit bus medium. Itulah awal lahirnya Tunggal Jaya Transport.</p>
                        </div>
                        <div class="mt-8 pt-8 border-t border-[#ebe7e7] grid grid-cols-2 gap-6">
                            <div>
                                <span class="block text-3xl font-bold text-[#1c1b1b] font-unbounded">Gen-2</span>
                                <span class="text-xs text-[#454652] uppercase tracking-wider">Era Kepemimpinan Baru</span>
                            </div>
                            <div>
                                <span class="block text-3xl font-bold text-[#1c1b1b] font-unbounded">Digital</span>
                                <span class="text-xs text-[#454652] uppercase tracking-wider">Transformasi Teknologi</span>
                            </div>
                        </div>
                    </div>
                    <div class="relative">
                        <div class="aspect-[4/3] rounded-[12px] overflow-hidden shadow-sm border border-[#ebe7e7]">
                            <video ref="videoRef" class="w-full h-full object-cover" autoplay muted loop playsinline>
                                <source src="/video/cinematic 1.mp4" type="video/mp4" />
                            </video>
                            <div class="absolute inset-0 cursor-pointer" @click="toggleVideo"></div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Stats -->
        <section class="py-16 bg-[#10207a]">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
                    <div v-for="stat in stats" :key="stat.label">
                        <div class="text-3xl md:text-5xl font-bold text-white font-unbounded mb-1">{{ stat.value }}</div>
                        <div class="text-white/70 text-xs font-bold uppercase tracking-widest">{{ stat.label }}</div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Vision & Mission -->
        <section class="py-16 bg-[#fcf9f8]">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-white border border-[#ebe7e7] rounded-[12px] p-8 shadow-sm">
                        <div class="w-12 h-12 rounded-xl bg-[#10207a]/10 flex items-center justify-center mb-5">
                            <i class="fas fa-eye text-lg text-[#10207a]"></i>
                        </div>
                        <h3 class="font-unbounded font-bold text-[16px] sm:text-[18px] md:text-[20px] text-[#1c1b1b] mb-3">Visi Kami</h3>
                        <p class="text-[#454652] text-[14px] leading-relaxed">"Menjadi pionir transportasi darat modern yang menghubungkan setiap sudut nusantara dengan standar keselamatan dan kenyamanan kelas dunia."</p>
                    </div>
                    <div class="bg-white border border-[#ebe7e7] rounded-[12px] p-8 shadow-sm">
                        <div class="w-12 h-12 rounded-xl bg-[#10207a]/10 flex items-center justify-center mb-5">
                            <i class="fas fa-rocket text-lg text-[#10207a]"></i>
                        </div>
                        <h3 class="font-unbounded font-bold text-[16px] sm:text-[18px] md:text-[20px] text-[#1c1b1b] mb-3">Misi Kami</h3>
                        <ul class="space-y-3 text-[#454652] text-[14px]">
                            <li class="flex items-start gap-3">
                                <i class="fas fa-check-circle text-[#10207a] mt-0.5 text-sm"></i>
                                <span>Inovasi armada berkelanjutan dengan teknologi terbaru.</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <i class="fas fa-check-circle text-[#10207a] mt-0.5 text-sm"></i>
                                <span>Pelayanan prima yang memprioritaskan kepuasan penumpang.</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        <!-- FAQ -->
        <section class="py-16 bg-white border-t border-[#ebe7e7]">
            <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-10">
                    <span class="inline-block px-3 py-1 rounded-full bg-[#f6f3f2] border border-[#ebe7e7] text-[#454652] text-[9px] font-bold tracking-widest uppercase mb-3">Pusat Bantuan</span>
                    <h2 class="font-unbounded font-bold text-[20px] sm:text-[22px] md:text-[24px] text-[#1c1b1b]">Pertanyaan Umum</h2>
                </div>
                <div class="space-y-3">
                    <div v-for="faq in faqs" :key="faq.id"
                        class="bg-[#fcf9f8] border border-[#ebe7e7] rounded-[12px] overflow-hidden transition-all"
                        :class="{ 'border-[#10207a] shadow-sm': activeFaq === faq.id }">
                        <button @click="toggleFaq(faq.id)"
                            class="w-full flex items-center justify-between p-5 text-left">
                            <span class="font-bold text-sm text-[#1c1b1b]">{{ faq.question }}</span>
                            <i class="fas fa-chevron-down text-gray-400 transition-transform duration-300 text-xs"
                                :class="{ 'rotate-180 text-[#10207a]': activeFaq === faq.id }"></i>
                        </button>
                        <div v-show="activeFaq === faq.id"
                            class="px-5 pb-5 text-sm text-[#454652] leading-relaxed border-t border-[#ebe7e7] pt-4">
                            {{ faq.answer }}
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</template>
