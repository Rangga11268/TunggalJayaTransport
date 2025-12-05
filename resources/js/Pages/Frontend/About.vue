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
        answer: "Anda dapat memesan tiket melalui website kami, aplikasi mobile, atau dengan mengunjungi salah satu kantor penjualan tiket kami. Sistem pemesanan online kami tersedia 24/7 untuk kenyamanan Anda.",
    },
    {
        id: 2,
        question: "Metode pembayaran apa yang Anda terima?",
        answer: "Kami menerima uang tunai, kartu kredit, kartu debit, dan pembayaran digital melalui aplikasi mobile kami. Semua kartu kredit utama termasuk Visa, Mastercard, dan American Express diterima.",
    },
    {
        id: 3,
        question: "Bisakah saya membatalkan atau mengganti pemesanan saya?",
        answer: "Ya, Anda dapat membatalkan atau mengganti pemesanan Anda hingga 2 jam sebelum keberangkatan, tergantung pada kebijakan pembatalan kami. Pembatalan yang dilakukan kurang dari 24 jam sebelum keberangkatan mungkin dikenakan biaya.",
    },
    {
        id: 4,
        question: "Fasilitas apa yang tersedia di bus Anda?",
        answer: "Bus-bus kami dilengkapi dengan kursi nyaman yang dapat direbahkan, AC, toilet di dalam bus, WiFi gratis, port pengisian daya, dan sistem hiburan. Layanan premium mungkin termasuk makanan dan minuman.",
    },
];
</script>

<template>
    <Head title="Tentang Kami" />

    <div
        class="font-sans antialiased text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-950 overflow-x-hidden"
    >
        <!-- Hero Section: Immersive & Clean -->
        <div class="relative h-[70vh] min-h-[500px] flex items-center">
            <!-- Background Image with Parallax-like fix -->
            <div class="absolute inset-0 z-0">
                <div
                    class="absolute inset-0 bg-gradient-to-r from-black/80 via-black/50 to-transparent z-10"
                ></div>
                <img
                    src="/img/heroImg.jpg"
                    alt="Luxury Bus"
                    class="w-full h-full object-cover"
                />
            </div>

            <div class="container mx-auto px-6 relative z-20">
                <div class="max-w-3xl animate-fade-in-up">
                    <span
                        class="text-brand-red font-bold tracking-[0.2em] uppercase mb-4 block"
                        >Sejak 1973</span
                    >
                    <h1
                        class="text-5xl md:text-7xl font-black text-white leading-tight mb-8"
                    >
                        Perjalanan
                        <span
                            class="text-transparent bg-clip-text bg-gradient-to-r from-brand-red to-rose-600"
                            >Legendaris</span
                        ><br />
                        Melintasi Waktu.
                    </h1>
                    <p
                        class="text-xl text-gray-300 leading-relaxed max-w-2xl mb-10 border-l-4 border-brand-red pl-6"
                    >
                        Dari sepeda penjual ubi hingga menjadi raja jalanan
                        Sumatera. Sebuah kisah tentang mimpi, kerja keras, dan
                        dedikasi melayani negeri.
                    </p>
                    <a
                        href="#history"
                        class="group inline-flex items-center text-white font-semibold tracking-wide border-b border-transparent hover:border-brand-red transition-all"
                    >
                        Telusuri Sejarah Kami
                        <i
                            class="fas fa-arrow-down ml-3 group-hover:translate-y-1 transition-transform"
                        ></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- The Legacy Section (History) -->
        <div id="history" class="py-24 relative bg-white dark:bg-gray-950">
            <!-- Decorative "1973" watermark -->
            <div
                class="absolute top-0 right-0 text-[15rem] font-black text-gray-50 dark:text-gray-900/50 leading-none select-none z-0 pointer-events-none opacity-50 overflow-hidden"
            >
                1973
            </div>

            <div class="container mx-auto px-6 relative z-10">
                <div
                    class="grid grid-cols-1 lg:grid-cols-2 gap-20 items-center"
                >
                    <!-- Text Content -->
                    <div class="space-y-8">
                        <div>
                            <h2
                                class="text-4xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-gray-900 to-gray-600 dark:from-white dark:to-gray-400 mb-2"
                            >
                                Awal Mula yang Sederhana
                            </h2>
                            <div
                                class="h-1 w-20 bg-brand-red rounded-full"
                            ></div>
                        </div>

                        <div
                            class="prose prose-lg text-gray-600 dark:text-gray-300"
                        >
                            <p class="leading-relaxed">
                                Semua bermula dari tekad
                                <strong>H. Asim</strong> pada tahun 1973. Jauh
                                sebelum armada bus gagah melintasi jalan tol,
                                beliau merintis jalan dengan sepeda onthel,
                                menjual ubi dari Sumatera hingga ke hiruk pikuk
                                Jakarta.
                            </p>
                            <p class="leading-relaxed">
                                Mimpi itu mahal, namun tekadnya tak ternilai.
                                Dengan modal <strong>Rp100 juta</strong> hasil
                                keringat bertahun-tahun, beliau membeli satu
                                unit bus medium seharga Rp49 juta. Itulah
                                tonggak pertama berdirinya
                                <strong>Tunggal Jaya Transport</strong>—nama
                                yang diambil dari sang Ayah sebagai doa
                                keberkahan abadi.
                            </p>
                        </div>

                        <div
                            class="flex items-center gap-8 py-6 border-y border-gray-100 dark:border-gray-800"
                        >
                            <div>
                                <span
                                    class="block text-3xl font-bold text-gray-900 dark:text-white"
                                    >50+</span
                                >
                                <span
                                    class="text-sm text-gray-500 uppercase tracking-wider"
                                    >Tahun Pengalaman</span
                                >
                            </div>
                            <div
                                class="h-12 w-px bg-gray-200 dark:bg-gray-700"
                            ></div>
                            <div>
                                <span
                                    class="block text-3xl font-bold text-gray-900 dark:text-white"
                                    >Generasi ke-2</span
                                >
                                <span
                                    class="text-sm text-gray-500 uppercase tracking-wider"
                                    >Dipimpin Bpk. Didi</span
                                >
                            </div>
                        </div>
                    </div>

                    <!-- Media Content -->
                    <div class="relative">
                        <div
                            class="aspect-[4/3] rounded-3xl overflow-hidden shadow-2xl relative group"
                        >
                            <!-- Overlay Play Button -->
                            <div
                                class="absolute inset-0 flex items-center justify-center bg-black/30 group-hover:bg-black/20 transition-all z-20 pointer-events-none"
                            >
                                <div
                                    class="w-20 h-20 bg-white/10 backdrop-blur-md rounded-full flex items-center justify-center border border-white/50 group-hover:scale-110 transition-transform"
                                >
                                    <i
                                        class="fas fa-play text-white text-2xl ml-1"
                                    ></i>
                                </div>
                            </div>
                            <!-- Video -->
                            <video
                                class="w-full h-full object-cover transform scale-100 group-hover:scale-105 transition-transform duration-[1.5s]"
                                autoplay
                                muted
                                loop
                                playsinline
                            >
                                <source
                                    src="/video/cinematiic 2.mp4"
                                    type="video/mp4"
                                />
                            </video>
                        </div>
                        <!-- Decorative Blob -->
                        <div
                            class="absolute -bottom-10 -left-10 w-full h-full bg-brand-red/5 rounded-3xl -z-10"
                        ></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Vision & Mission (Dark Mode Premium) -->
        <div class="py-24 bg-gray-900 text-white relative overflow-hidden">
            <!-- Abstract Patterns -->
            <div
                class="absolute top-0 left-0 w-full h-full overflow-hidden opacity-20"
            >
                <div
                    class="absolute -top-24 -left-24 w-96 h-96 bg-brand-red/30 rounded-full blur-[100px]"
                ></div>
                <div
                    class="absolute bottom-0 right-0 w-80 h-80 bg-blue-600/30 rounded-full blur-[100px]"
                ></div>
            </div>

            <div class="container mx-auto px-6 relative z-10">
                <div class="text-center mb-16">
                    <h2 class="text-3xl md:text-4xl font-bold mb-4">
                        Visi & Misi
                    </h2>
                    <p class="text-gray-400 max-w-2xl mx-auto">
                        Filosofi yang menggerakkan setiap roda armada kami untuk
                        melayani Anda lebih baik setiap harinya.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 lg:gap-12">
                    <!-- Vision Card -->
                    <div
                        class="group bg-white/5 border border-white/10 p-8 rounded-3xl hover:bg-white/10 transition-all duration-300"
                    >
                        <div
                            class="w-16 h-16 bg-gradient-to-br from-brand-red to-pink-600 rounded-2xl flex items-center justify-center mb-8 shadow-lg shadow-brand-red/20 group-hover:scale-110 transition-transform"
                        >
                            <i class="fas fa-eye text-3xl text-white"></i>
                        </div>
                        <h3 class="text-2xl font-bold mb-4">Visi Kami</h3>
                        <p class="text-gray-300 leading-relaxed mb-6">
                            "Menjadi penyedia transportasi terkemuka di kawasan
                            ini, dikenal karena komitmen kami terhadap
                            keselamatan, kenyamanan, dan tanggung jawab
                            lingkungan."
                        </p>
                        <div
                            class="pl-4 border-l-2 border-brand-red/50 italic text-gray-400"
                        >
                            Menghubungkan orang, menghubungkan masa depan.
                        </div>
                    </div>

                    <!-- Mission Card -->
                    <div
                        class="group bg-white/5 border border-white/10 p-8 rounded-3xl hover:bg-white/10 transition-all duration-300"
                    >
                        <div
                            class="w-16 h-16 bg-gradient-to-br from-blue-600 to-indigo-600 rounded-2xl flex items-center justify-center mb-8 shadow-lg shadow-blue-600/20 group-hover:scale-110 transition-transform"
                        >
                            <i class="fas fa-rocket text-3xl text-white"></i>
                        </div>
                        <h3 class="text-2xl font-bold mb-6">Misi Kami</h3>
                        <ul class="space-y-4">
                            <li class="flex items-start">
                                <i
                                    class="fas fa-check-circle text-blue-500 mt-1 mr-3"
                                ></i>
                                <span class="text-gray-300"
                                    >Layanan aman dan andal prioritas
                                    utama.</span
                                >
                            </li>
                            <li class="flex items-start">
                                <i
                                    class="fas fa-check-circle text-blue-500 mt-1 mr-3"
                                ></i>
                                <span class="text-gray-300"
                                    >Kenyamanan & kepuasan penumpang tanpa
                                    kompromi.</span
                                >
                            </li>
                            <li class="flex items-start">
                                <i
                                    class="fas fa-check-circle text-blue-500 mt-1 mr-3"
                                ></i>
                                <span class="text-gray-300"
                                    >Inovasi armada berkelanjutan.</span
                                >
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Counter -->
        <div class="py-16 bg-brand-red">
            <div class="container mx-auto px-6">
                <div
                    class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center divide-x divide-white/20"
                >
                    <div class="p-4">
                        <div class="text-5xl font-black text-white mb-2">
                            50+
                        </div>
                        <div class="text-white/80 font-medium tracking-wide">
                            Destinasi
                        </div>
                    </div>
                    <div class="p-4">
                        <div class="text-5xl font-black text-white mb-2">
                            100+
                        </div>
                        <div class="text-white/80 font-medium tracking-wide">
                            Armada Modern
                        </div>
                    </div>
                    <div class="p-4">
                        <div class="text-5xl font-black text-white mb-2">
                            200+
                        </div>
                        <div class="text-white/80 font-medium tracking-wide">
                            Tim Profesional
                        </div>
                    </div>
                    <div class="p-4">
                        <div class="text-5xl font-black text-white mb-2">
                            1J+
                        </div>
                        <div class="text-white/80 font-medium tracking-wide">
                            Penumpang
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- FAQ Section -->
        <div class="py-24 bg-gray-50 dark:bg-gray-950">
            <div class="container mx-auto px-6 max-w-4xl">
                <div class="text-center mb-16">
                    <span
                        class="text-brand-red font-bold uppercase tracking-wider text-sm"
                        >Pusat Bantuan</span
                    >
                    <h2
                        class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mt-2"
                    >
                        Sering Ditanyakan
                    </h2>
                </div>

                <div class="space-y-4">
                    <div
                        v-for="faq in faqs"
                        :key="faq.id"
                        class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm overflow-hidden transition-all duration-300"
                        :class="{
                            'shadow-md border-brand-red/30':
                                activeFaq === faq.id,
                        }"
                    >
                        <button
                            @click="toggleFaq(faq.id)"
                            class="w-full flex items-center justify-between p-6 text-left"
                        >
                            <span
                                class="font-bold text-gray-800 dark:text-gray-100 text-lg"
                                >{{ faq.question }}</span
                            >
                            <span
                                class="w-8 h-8 rounded-full bg-gray-50 dark:bg-gray-800 flex items-center justify-center transition-transform duration-300"
                                :class="{
                                    'bg-brand-red text-white rotate-180':
                                        activeFaq === faq.id,
                                }"
                            >
                                <i class="fas fa-chevron-down text-sm"></i>
                            </span>
                        </button>
                        <div
                            v-show="activeFaq === faq.id"
                            class="px-6 pb-6 text-gray-600 dark:text-gray-400 leading-relaxed border-t border-gray-50 dark:border-gray-800 pt-4"
                        >
                            {{ faq.answer }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
/* Optional specific overrides if tailwind isn't enough */
.hero-text-shadow {
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
}
</style>
