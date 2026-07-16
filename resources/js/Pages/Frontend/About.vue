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

    <div
        class="bg-gray-50 dark:bg-[#050505] min-h-screen font-sans selection:bg-rose-600 selection:text-white"
    >
        <!-- Hero Section -->
        <div
            class="relative pt-24 md:pt-32 pb-20 px-4 sm:px-6 lg:px-8 overflow-hidden"
        >
            <!-- Abstract Background -->
            <div
                class="absolute top-0 right-0 w-[600px] h-[600px] bg-rose-600/5 rounded-full blur-[120px] -z-10"
            ></div>
            <div
                class="absolute bottom-0 left-0 w-[500px] h-[500px] bg-rose-600/5 rounded-full blur-[100px] -z-10"
            ></div>

            <div class="max-w-7xl mx-auto text-center relative z-10">
                <span
                    class="inline-block py-1 px-3 rounded-full bg-rose-50 dark:bg-rose-900/10 text-rose-600 border border-rose-100 dark:border-rose-900/20 text-xs font-bold tracking-widest uppercase mb-6 font-unbounded animate-fade-in-up"
                >
                    Sejak 1973
                </span>
                <h1
                    class="text-3xl sm:text-5xl md:text-7xl font-black text-gray-900 dark:text-white mb-8 font-unbounded animate-fade-in-up"
                    style="animation-delay: 0.1s"
                >
                    Evolusi <span class="text-rose-600">Transportasi</span>
                </h1>
                <p
                    class="text-lg md:text-xl text-gray-500 dark:text-gray-400 max-w-3xl mx-auto  leading-relaxed animate-fade-in-up"
                    style="animation-delay: 0.2s"
                >
                    Dari sepeda penjual ubi hingga menjadi raja jalanan modern.
                    Kisah ketangguhan, inovasi, dan dedikasi melayani perjalanan
                    nusantara.
                </p>
            </div>
        </div>

        <!-- Story / History Section -->
        <section class="py-20 bg-white dark:bg-[#111] relative overflow-hidden">
            <div
                class="absolute -right-20 top-20 text-[20rem] font-black text-gray-100 dark:text-white/5 font-unbounded select-none pointer-events-none opacity-50"
            >
                1973
            </div>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                <div
                    class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center"
                >
                    <div>
                        <h2
                            class="text-2xl sm:text-3xl md:text-5xl font-black text-gray-900 dark:text-white font-unbounded mb-8 leading-tight"
                        >
                            Berawal dari <br /><span class="text-rose-600"
                                >Tekad Kuat</span
                            >
                        </h2>
                        <div
                            class="space-y-6 text-gray-600 dark:text-gray-400  text-lg leading-relaxed"
                        >
                            <p>
                                Semua bermula dari H. Asim pada tahun 1973.
                                Tanpa modal besar, hanya bermodalkan keberanian
                                dan sepeda onthel, beliau merintis usaha dengan
                                berjualan ubi dari Sumatera hingga Jakarta.
                            </p>
                            <p>
                                Mimpi itu mahal, namun kerja keras tak pernah
                                mengkhianati. Dengan tabungan 100 juta rupiah
                                hasil keringat bertahun-tahun, beliau membeli
                                satu unit bus medium. Itulah awal lahirnya
                                Tunggal Jaya Transport, nama yang diambil dari
                                sang Ayah sebagai doa keberkahan abadi.
                            </p>
                        </div>

                        <div
                            class="mt-10 pt-10 border-t border-gray-100 dark:border-white/10 grid grid-cols-2 gap-8"
                        >
                            <div>
                                <span
                                    class="block text-4xl font-black text-gray-900 dark:text-white font-unbounded mb-1"
                                    >Gen-2</span
                                >
                                <span
                                    class="text-xs font-bold text-gray-500 uppercase tracking-widest font-unbounded"
                                    >Era Kepemimpinan Baru</span
                                >
                            </div>
                            <div>
                                <span
                                    class="block text-4xl font-black text-gray-900 dark:text-white font-unbounded mb-1"
                                    >Digital</span
                                >
                                <span
                                    class="text-xs font-bold text-gray-500 uppercase tracking-widest font-unbounded"
                                    >Transformasi Teknologi</span
                                >
                            </div>
                        </div>
                    </div>

                    <div class="relative">
                        <div
                            class="aspect-[4/3] rounded-[2rem] overflow-hidden shadow-2xl shadow-rose-600/20 group"
                        >
                            <video
                                ref="videoRef"
                                class="w-full h-full object-cover transform scale-100 group-hover:scale-105 transition-transform duration-[1.5s]"
                                autoplay
                                muted
                                loop
                                playsinline
                            >
                                <source
                                    src="/video/cinematic 1.mp4"
                                    type="video/mp4"
                                />
                            </video>

                            <!-- Invisible Play/Pause Toggle -->
                            <div
                                class="absolute inset-0 cursor-pointer"
                                title="Klik untuk Putar/Jeda"
                                @click="toggleVideo"
                            ></div>
                        </div>

                        <!-- Blob Decor -->
                        <div
                            class="absolute -bottom-8 -left-8 w-64 h-64 bg-rose-600/20 rounded-full blur-3xl -z-10"
                        ></div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Stats Section -->
        <section class="py-20 bg-rose-600 relative overflow-hidden">
            <!-- Pattern -->
            <div
                class="absolute inset-0 opacity-10"
                style="
                    background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'1\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');
                "
            ></div>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                <div
                    class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center divide-x divide-white/20"
                >
                    <div v-for="stat in stats" :key="stat.label" class="group">
                        <div
                            class="text-3xl sm:text-4xl md:text-6xl font-black text-white font-unbounded mb-2 group-hover:scale-110 transition-transform duration-300"
                        >
                            {{ stat.value }}
                        </div>
                        <div
                            class="text-rose-100 text-sm font-bold uppercase tracking-widest font-unbounded"
                        >
                            {{ stat.label }}
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Vision & Mission -->
        <section class="py-24 bg-gray-50 dark:bg-[#050505] relative">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 md:gap-16">
                    <!-- Vision -->
                    <div
                        class="group bg-white dark:bg-[#111] p-6 md:p-10 rounded-[2.5rem] shadow-xl border border-gray-100 dark:border-white/5 hover:border-rose-600/30 transition-all duration-500 relative overflow-hidden"
                    >
                        <div
                            class="absolute top-0 right-0 w-32 h-32 bg-rose-600/10 rounded-full blur-3xl -mr-10 -mt-10 transition-all group-hover:bg-rose-600/20"
                        ></div>
                        <i
                            class="fas fa-eye text-4xl text-rose-600 mb-6 block"
                        ></i>
                        <h3
                            class="text-2xl font-black font-unbounded text-gray-900 dark:text-white mb-4"
                        >
                            Visi Kami
                        </h3>
                        <p
                            class="text-gray-600 dark:text-gray-400  leading-relaxed"
                        >
                            "Menjadi pionir transportasi darat modern yang
                            menghubungkan setiap sudut nusantara dengan standar
                            keselamatan dan kenyamanan kelas dunia."
                        </p>
                    </div>

                    <!-- Mission -->
                    <div
                        class="group bg-white dark:bg-[#111] p-6 md:p-10 rounded-[2.5rem] shadow-xl border border-gray-100 dark:border-white/5 hover:border-rose-600/30 transition-all duration-500 relative overflow-hidden"
                    >
                        <div
                            class="absolute top-0 right-0 w-32 h-32 bg-blue-600/10 rounded-full blur-3xl -mr-10 -mt-10 transition-all group-hover:bg-blue-600/20"
                        ></div>
                        <i
                            class="fas fa-rocket text-4xl text-blue-600 mb-6 block"
                        ></i>
                        <h3
                            class="text-2xl font-black font-unbounded text-gray-900 dark:text-white mb-4"
                        >
                            Misi Kami
                        </h3>
                        <ul
                            class="space-y-4 text-gray-600 dark:text-gray-400 "
                        >
                            <li class="flex items-start gap-3">
                                <i
                                    class="fas fa-check-circle text-blue-600 mt-1"
                                ></i>
                                <span
                                    >Inovasi armada berkelanjutan dengan
                                    teknologi terbaru.</span
                                >
                            </li>
                            <li class="flex items-start gap-3">
                                <i
                                    class="fas fa-check-circle text-blue-600 mt-1"
                                ></i>
                                <span
                                    >Pelayanan prima yang memprioritaskan
                                    kepuasan penumpang.</span
                                >
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        <!-- FAQ Section -->
        <section class="py-24 bg-white dark:bg-[#111]">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <span
                        class="inline-block py-1 px-3 rounded-full bg-gray-100 dark:bg-white/10 text-gray-600 dark:text-gray-300 text-xs font-bold tracking-widest uppercase mb-4 font-unbounded"
                    >
                        Pusat Bantuan
                    </span>
                    <h2
                        class="text-3xl font-black font-unbounded text-gray-900 dark:text-white"
                    >
                        Pertanyaan Umum
                    </h2>
                </div>

                <div class="space-y-4">
                    <div
                        v-for="faq in faqs"
                        :key="faq.id"
                        class="bg-gray-50 dark:bg-[#161616] rounded-2xl border border-gray-100 dark:border-white/5 overflow-hidden transition-all duration-300"
                        :class="{
                            'border-rose-600 dark:border-rose-600 shadow-lg shadow-rose-600/10':
                                activeFaq === faq.id,
                        }"
                    >
                        <button
                            @click="toggleFaq(faq.id)"
                            class="w-full flex items-center justify-between p-6 text-left"
                        >
                            <span
                                class="font-bold font-unbounded text-sm md:text-base text-gray-900 dark:text-white"
                                >{{ faq.question }}</span
                            >
                            <i
                                class="fas fa-chevron-down text-gray-400 transition-transform duration-300"
                                :class="{
                                    'rotate-180 text-rose-600':
                                        activeFaq === faq.id,
                                }"
                            ></i>
                        </button>
                        <div
                            v-show="activeFaq === faq.id"
                            class="px-6 pb-6 text-sm text-gray-600 dark:text-gray-400  leading-relaxed border-t border-gray-200 dark:border-white/5 pt-4"
                        >
                            {{ faq.answer }}
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</template>
