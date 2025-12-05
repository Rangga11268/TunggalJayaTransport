<script setup>
import { Head, useForm } from "@inertiajs/vue3";
import FrontendLayout from "@/Layouts/FrontendLayout.vue";
import InputError from "@/Components/InputError.vue";

defineOptions({ layout: FrontendLayout });

const form = useForm({
    name: "",
    email: "",
    subject: "",
    message: "",
});

const submit = () => {
    form.post(route("contact.store"), {
        preserveScroll: true,
        onSuccess: () => form.reset(),
    });
};
</script>

<template>
    <Head title="Hubungi Kami" />

    <div class="bg-gray-50 dark:bg-gray-950 min-h-screen font-sans">
        <!-- Header Section -->
        <div class="relative bg-white dark:bg-gray-900 pt-32 pb-20 px-6">
            <div class="max-w-4xl mx-auto text-center relative z-10">
                <span
                    class="text-brand-red font-bold tracking-widest uppercase text-sm mb-4 block animate-fade-in-up"
                    >24/7 Support</span
                >
                <h1
                    class="text-5xl md:text-6xl font-black text-gray-900 dark:text-white mb-6 leading-tight animate-fade-in-up stagger-1"
                >
                    Kami Siap<br />
                    <span
                        class="text-transparent bg-clip-text bg-gradient-to-r from-brand-red to-orange-500"
                        >Mendengar Anda.</span
                    >
                </h1>
                <p
                    class="text-xl text-gray-500 dark:text-gray-400 max-w-2xl mx-auto animate-fade-in-up stagger-2"
                >
                    Apapun pertanyaan atau kebutuhan perjalanan Anda, tim
                    profesional kami siap membantu kapan saja.
                </p>
            </div>

            <!-- Background Decorative Blob -->
            <div
                class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[500px] h-[500px] bg-gradient-to-tr from-blue-100 to-indigo-100 dark:from-blue-900/10 dark:to-indigo-900/10 rounded-full blur-[100px] -z-0"
            ></div>
        </div>

        <!-- Main Content: Split Layout -->
        <div
            class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-24 -mt-10 relative z-20"
        >
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12">
                <!-- Left: Contact Info & Map (Visual) -->
                <div class="space-y-8">
                    <!-- Floating Info Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div
                            class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 hover:shadow-lg transition-shadow"
                        >
                            <div
                                class="w-12 h-12 bg-blue-50 dark:bg-blue-900/30 rounded-full flex items-center justify-center text-blue-600 dark:text-blue-400 mb-4"
                            >
                                <i class="fas fa-phone-alt text-xl"></i>
                            </div>
                            <h3
                                class="font-bold text-gray-900 dark:text-white text-lg"
                            >
                                Telepon
                            </h3>
                            <p
                                class="text-gray-500 dark:text-gray-400 text-sm mt-1"
                            >
                                +62 21 1234 5678
                            </p>
                            <span
                                class="text-xs text-green-600 font-medium mt-2 block"
                                >Senin-Jumat: 8 pagi - 8 malam<br />Sabtu-Minggu:
                                9 pagi - 5 sore</span
                            >
                        </div>

                        <div
                            class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 hover:shadow-lg transition-shadow"
                        >
                            <div
                                class="w-12 h-12 bg-orange-50 dark:bg-orange-900/30 rounded-full flex items-center justify-center text-orange-600 dark:text-orange-400 mb-4"
                            >
                                <i class="fas fa-envelope text-xl"></i>
                            </div>
                            <h3
                                class="font-bold text-gray-900 dark:text-white text-lg"
                            >
                                Email
                            </h3>
                            <p
                                class="text-gray-500 dark:text-gray-400 text-sm mt-1"
                            >
                                info@tunggaljayatransport.com
                            </p>
                            <span
                                class="text-xs text-gray-400 font-medium mt-2 block"
                                >Kami biasanya merespon dalam waktu 24 jam</span
                            >
                        </div>
                    </div>

                    <!-- Map Container -->
                    <div
                        class="relative h-[300px] rounded-3xl overflow-hidden shadow-2xl border-4 border-white dark:border-gray-800 group"
                    >
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d253840.4878845067!2d106.68942964135938!3d-6.229728025238589!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f3e945e34b9d!2sJakarta%2C%20Special%20Capital%20Region%20of%20Jakarta!5e0!3m2!1sen!2sid!4v1633072877145!5m2!1sen!2sid"
                            width="100%"
                            height="100%"
                            style="
                                border: 0;
                                filter: grayscale(100%) invert(0%);
                            "
                            allowfullscreen=""
                            loading="lazy"
                            class="group-hover:filter-none transition-all duration-700"
                        >
                        </iframe>

                        <!-- Location Overlay -->
                        <div class="absolute bottom-4 left-4 right-4">
                            <div
                                class="bg-white/90 dark:bg-gray-900/90 backdrop-blur-md p-3 rounded-xl shadow-lg flex items-start"
                            >
                                <i
                                    class="fas fa-map-marker-alt text-brand-red text-xl mt-1 mr-3"
                                ></i>
                                <div>
                                    <h4
                                        class="font-bold text-gray-900 dark:text-white text-sm"
                                    >
                                        Kantor Pusat
                                    </h4>
                                    <p
                                        class="text-xs text-gray-600 dark:text-gray-400"
                                    >
                                        Jl. Transportation No. 123, Jakarta
                                        12345
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Social Media (Added from Blade) -->
                    <div
                        class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700"
                    >
                        <h3
                            class="font-bold text-gray-900 dark:text-white mb-4"
                        >
                            Ikuti Kami
                        </h3>
                        <div class="flex gap-4">
                            <a
                                href="#"
                                class="w-10 h-10 rounded-full bg-blue-600 hover:bg-blue-700 text-white flex items-center justify-center transition-colors"
                            >
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a
                                href="#"
                                class="w-10 h-10 rounded-full bg-blue-400 hover:bg-blue-500 text-white flex items-center justify-center transition-colors"
                            >
                                <i class="fab fa-twitter"></i>
                            </a>
                            <a
                                href="#"
                                class="w-10 h-10 rounded-full bg-pink-600 hover:bg-pink-700 text-white flex items-center justify-center transition-colors"
                            >
                                <i class="fab fa-instagram"></i>
                            </a>
                            <a
                                href="#"
                                class="w-10 h-10 rounded-full bg-blue-700 hover:bg-blue-800 text-white flex items-center justify-center transition-colors"
                            >
                                <i class="fab fa-linkedin-in"></i>
                            </a>
                        </div>
                    </div>

                    <!-- Emergency Callout -->
                    <div
                        class="bg-gradient-to-r from-gray-900 to-gray-800 text-white rounded-2xl p-6 flex items-center justify-between shadow-xl"
                    >
                        <div>
                            <p
                                class="text-xs uppercase tracking-wider text-gray-400 mb-1"
                            >
                                Butuh Bantuan Mendesak?
                            </p>
                            <p class="text-2xl font-bold">+62 21 1234 5678</p>
                            <p class="text-xs text-gray-400 mt-1">
                                Layanan Pelanggan 24/7
                            </p>
                        </div>
                        <div
                            class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center animate-pulse"
                        >
                            <i class="fas fa-phone text-white"></i>
                        </div>
                    </div>
                </div>

                <!-- Right: Contact Form (Functional) -->
                <div
                    class="bg-white dark:bg-gray-900 rounded-3xl p-8 lg:p-10 shadow-xl border border-gray-100 dark:border-gray-800 h-fit"
                >
                    <h2
                        class="text-2xl font-bold text-gray-900 dark:text-white mb-8"
                    >
                        Kirim Pesan
                    </h2>

                    <form @submit.prevent="submit" class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label
                                    for="name"
                                    class="text-sm font-semibold text-gray-700 dark:text-gray-300"
                                    >Nama Lengkap</label
                                >
                                <input
                                    id="name"
                                    v-model="form.name"
                                    type="text"
                                    class="w-full px-4 py-3 rounded-xl bg-gray-50 dark:bg-gray-800 border-transparent focus:bg-white focus:border-brand-red focus:ring-0 transition-all font-medium"
                                    placeholder="John Doe"
                                    required
                                />
                                <InputError :message="form.errors.name" />
                            </div>
                            <div class="space-y-2">
                                <label
                                    for="email"
                                    class="text-sm font-semibold text-gray-700 dark:text-gray-300"
                                    >Email Address</label
                                >
                                <input
                                    id="email"
                                    v-model="form.email"
                                    type="email"
                                    class="w-full px-4 py-3 rounded-xl bg-gray-50 dark:bg-gray-800 border-transparent focus:bg-white focus:border-brand-red focus:ring-0 transition-all font-medium"
                                    placeholder="john@example.com"
                                    required
                                />
                                <InputError :message="form.errors.email" />
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label
                                for="subject"
                                class="text-sm font-semibold text-gray-700 dark:text-gray-300"
                                >Subjek</label
                            >
                            <input
                                id="subject"
                                v-model="form.subject"
                                type="text"
                                class="w-full px-4 py-3 rounded-xl bg-gray-50 dark:bg-gray-800 border-transparent focus:bg-white focus:border-brand-red focus:ring-0 transition-all font-medium"
                                placeholder="Perihal pesan Anda"
                                required
                            />
                            <InputError :message="form.errors.subject" />
                        </div>

                        <div class="space-y-2">
                            <label
                                for="message"
                                class="text-sm font-semibold text-gray-700 dark:text-gray-300"
                                >Pesan</label
                            >
                            <textarea
                                id="message"
                                v-model="form.message"
                                rows="5"
                                class="w-full px-4 py-3 rounded-xl bg-gray-50 dark:bg-gray-800 border-transparent focus:bg-white focus:border-brand-red focus:ring-0 transition-all font-medium resize-none"
                                placeholder="Tuliskan pesan Anda disini..."
                                required
                            ></textarea>
                            <InputError :message="form.errors.message" />
                        </div>

                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="w-full bg-brand-red text-white font-bold py-4 rounded-xl shadow-lg shadow-brand-red/30 hover:bg-red-700 hover:shadow-brand-red/50 hover:-translate-y-1 transition-all duration-300 flex items-center justify-center"
                        >
                            <span
                                v-if="form.processing"
                                class="flex items-center"
                                ><i class="fas fa-spinner fa-spin mr-2"></i>
                                Mengirim...</span
                            >
                            <span v-else
                                >Kirim Pesan Sekarang
                                <i class="fas fa-paper-plane ml-2"></i
                            ></span>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Alternative Connections Section -->
            <div class="mt-24 grid grid-cols-1 md:grid-cols-3 gap-6">
                <a
                    href="#"
                    class="group bg-white dark:bg-gray-800 p-8 rounded-3xl border border-gray-100 dark:border-gray-700 hover:border-brand-red hover:shadow-xl transition-all duration-300"
                >
                    <div
                        class="w-14 h-14 bg-green-50 dark:bg-green-900/20 rounded-2xl flex items-center justify-center text-green-600 mb-6 group-hover:scale-110 transition-transform"
                    >
                        <i class="fas fa-comments text-2xl"></i>
                    </div>
                    <h3
                        class="font-bold text-xl text-gray-900 dark:text-white mb-2"
                    >
                        Live Chat
                    </h3>
                    <p class="text-gray-500 text-sm mb-4">
                        Ngobrol langsung dengan agen kami.
                    </p>
                    <span
                        class="text-brand-red font-bold text-sm group-hover:underline"
                        >Mulai Chat &rarr;</span
                    >
                </a>

                <a
                    href="#"
                    class="group bg-white dark:bg-gray-800 p-8 rounded-3xl border border-gray-100 dark:border-gray-700 hover:border-brand-red hover:shadow-xl transition-all duration-300"
                >
                    <div
                        class="w-14 h-14 bg-purple-50 dark:bg-purple-900/20 rounded-2xl flex items-center justify-center text-purple-600 mb-6 group-hover:scale-110 transition-transform"
                    >
                        <i class="fas fa-book-open text-2xl"></i>
                    </div>
                    <h3
                        class="font-bold text-xl text-gray-900 dark:text-white mb-2"
                    >
                        Help Center
                    </h3>
                    <p class="text-gray-500 text-sm mb-4">
                        Panduan lengkap pertanyaan umum.
                    </p>
                    <span
                        class="text-brand-red font-bold text-sm group-hover:underline"
                        >Lihat Panduan &rarr;</span
                    >
                </a>

                <a
                    href="#"
                    class="group bg-white dark:bg-gray-800 p-8 rounded-3xl border border-gray-100 dark:border-gray-700 hover:border-brand-red hover:shadow-xl transition-all duration-300"
                >
                    <div
                        class="w-14 h-14 bg-indigo-50 dark:bg-indigo-900/20 rounded-2xl flex items-center justify-center text-indigo-600 mb-6 group-hover:scale-110 transition-transform"
                    >
                        <i class="fas fa-mobile-android-alt text-2xl"></i>
                    </div>
                    <h3
                        class="font-bold text-xl text-gray-900 dark:text-white mb-2"
                    >
                        Get the App
                    </h3>
                    <p class="text-gray-500 text-sm mb-4">
                        Download untuk kemudahan transaksi.
                    </p>
                    <span
                        class="text-brand-red font-bold text-sm group-hover:underline"
                        >Download Sekarang &rarr;</span
                    >
                </a>
            </div>
        </div>
    </div>
</template>
