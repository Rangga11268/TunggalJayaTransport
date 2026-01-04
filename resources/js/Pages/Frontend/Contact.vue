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

    <div
        class="bg-gray-50 dark:bg-[#050505] min-h-screen font-sans selection:bg-rose-600 selection:text-white"
    >
        <!-- Hero Header -->
        <div class="relative pt-32 pb-12 text-center overflow-hidden">
            <!-- Decor -->
            <div
                class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[800px] h-[400px] bg-rose-600/10 rounded-full blur-[120px] -z-10"
            ></div>

            <span
                class="inline-block py-1 px-3 rounded-full bg-rose-50 dark:bg-rose-900/10 text-rose-600 border border-rose-100 dark:border-rose-900/20 text-xs font-bold tracking-widest uppercase mb-6 font-unbounded animate-fade-in-up"
            >
                Bantuan & Dukungan
            </span>
            <h1
                class="text-3xl sm:text-4xl md:text-6xl font-black text-gray-900 dark:text-white mb-6 font-unbounded animate-fade-in-up"
            >
                Hubungi <span class="text-rose-600">Kami</span>
            </h1>
            <p
                class="text-lg text-gray-500 dark:text-gray-400 max-w-2xl mx-auto font-manrope animate-fade-in-up"
                style="animation-delay: 0.1s"
            >
                Punya pertanyaan atau butuh bantuan perjalanan? Tim support kami
                siap melayani Anda 24/7.
            </p>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-24">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-20">
                <!-- Left Column: Contact Info & Map -->
                <div
                    class="space-y-8 animate-fade-in-up"
                    style="animation-delay: 0.2s"
                >
                    <!-- Info Cards -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div
                            class="bg-white dark:bg-[#111] p-6 md:p-8 rounded-3xl border border-gray-100 dark:border-white/5 shadow-xl hover:translate-y-[-5px] transition-transform duration-300"
                        >
                            <div
                                class="w-12 h-12 bg-rose-50 dark:bg-rose-900/10 rounded-2xl flex items-center justify-center text-rose-600 mb-6"
                            >
                                <i class="fas fa-phone-alt text-xl"></i>
                            </div>
                            <h3
                                class="font-bold text-lg text-gray-900 dark:text-white font-unbounded mb-1"
                            >
                                Telepon
                            </h3>
                            <p
                                class="text-gray-500 dark:text-gray-400 font-manrope text-sm"
                            >
                                +62 21 1234 5678
                            </p>
                            <span
                                class="inline-block mt-4 text-[10px] font-bold uppercase tracking-wider text-emerald-500 bg-emerald-50 dark:bg-emerald-900/10 px-2 py-1 rounded-lg"
                                >Online 24 Jam</span
                            >
                        </div>

                        <div
                            class="bg-white dark:bg-[#111] p-6 md:p-8 rounded-3xl border border-gray-100 dark:border-white/5 shadow-xl hover:translate-y-[-5px] transition-transform duration-300"
                        >
                            <div
                                class="w-12 h-12 bg-blue-50 dark:bg-blue-900/10 rounded-2xl flex items-center justify-center text-blue-600 mb-6"
                            >
                                <i class="fas fa-envelope text-xl"></i>
                            </div>
                            <h3
                                class="font-bold text-lg text-gray-900 dark:text-white font-unbounded mb-1"
                            >
                                Email
                            </h3>
                            <p
                                class="text-gray-500 dark:text-gray-400 font-manrope text-sm"
                            >
                                support@tujago.com
                            </p>
                            <span
                                class="inline-block mt-4 text-[10px] font-bold uppercase tracking-wider text-blue-500 bg-blue-50 dark:bg-blue-900/10 px-2 py-1 rounded-lg"
                                >Respon Cepat</span
                            >
                        </div>
                    </div>

                    <!-- Map -->
                    <div
                        class="relative h-[400px] rounded-[2.5rem] overflow-hidden shadow-2xl border border-gray-200 dark:border-white/10 group"
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
                            class="group-hover:filter-none transition-all duration-700 opacity-80 group-hover:opacity-100"
                        >
                        </iframe>
                        <div class="absolute bottom-6 left-6 right-6">
                            <div
                                class="bg-white/90 dark:bg-black/80 backdrop-blur-md p-4 rounded-2xl flex items-center gap-4"
                            >
                                <i
                                    class="fas fa-map-marker-alt text-rose-600 text-2xl"
                                ></i>
                                <div>
                                    <h4
                                        class="font-bold text-gray-900 dark:text-white font-unbounded text-sm"
                                    >
                                        Kantor Pusat
                                    </h4>
                                    <p
                                        class="text-xs text-gray-500 dark:text-gray-400 font-manrope"
                                    >
                                        Jl. Transportasi No. 88, Jakarta Selatan
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Form -->
                <div
                    class="bg-white dark:bg-[#111] p-6 md:p-12 rounded-[2.5rem] shadow-2xl border border-gray-100 dark:border-white/5 h-fit animate-fade-in-up"
                    style="animation-delay: 0.3s"
                >
                    <h2
                        class="text-2xl font-black font-unbounded text-gray-900 dark:text-white mb-8"
                    >
                        Kirim Pesan
                    </h2>

                    <form @submit.prevent="submit" class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label
                                    class="text-xs font-bold text-gray-500 uppercase tracking-wider font-unbounded ml-1"
                                    >Nama Lengkap</label
                                >
                                <input
                                    v-model="form.name"
                                    type="text"
                                    class="w-full px-6 py-4 rounded-2xl bg-gray-50 dark:bg-[#1A1A1A] border-transparent focus:bg-white focus:dark:bg-black focus:border-rose-500 focus:ring-0 transition-all font-manrope text-gray-900 dark:text-white"
                                    placeholder="Nama Anda"
                                    required
                                />
                                <InputError :message="form.errors.name" />
                            </div>
                            <div class="space-y-2">
                                <label
                                    class="text-xs font-bold text-gray-500 uppercase tracking-wider font-unbounded ml-1"
                                    >Email</label
                                >
                                <input
                                    v-model="form.email"
                                    type="email"
                                    class="w-full px-6 py-4 rounded-2xl bg-gray-50 dark:bg-[#1A1A1A] border-transparent focus:bg-white focus:dark:bg-black focus:border-rose-500 focus:ring-0 transition-all font-manrope text-gray-900 dark:text-white"
                                    placeholder="email@anda.com"
                                    required
                                />
                                <InputError :message="form.errors.email" />
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label
                                class="text-xs font-bold text-gray-500 uppercase tracking-wider font-unbounded ml-1"
                                >Subjek</label
                            >
                            <input
                                v-model="form.subject"
                                type="text"
                                class="w-full px-6 py-4 rounded-2xl bg-gray-50 dark:bg-[#1A1A1A] border-transparent focus:bg-white focus:dark:bg-black focus:border-rose-500 focus:ring-0 transition-all font-manrope text-gray-900 dark:text-white"
                                placeholder="Perihal"
                                required
                            />
                            <InputError :message="form.errors.subject" />
                        </div>

                        <div class="space-y-2">
                            <label
                                class="text-xs font-bold text-gray-500 uppercase tracking-wider font-unbounded ml-1"
                                >Pesan</label
                            >
                            <textarea
                                v-model="form.message"
                                rows="5"
                                class="w-full px-6 py-4 rounded-2xl bg-gray-50 dark:bg-[#1A1A1A] border-transparent focus:bg-white focus:dark:bg-black focus:border-rose-500 focus:ring-0 transition-all font-manrope text-gray-900 dark:text-white resize-none"
                                placeholder="Tulis pesan Anda disini..."
                                required
                            ></textarea>
                            <InputError :message="form.errors.message" />
                        </div>

                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="w-full py-5 bg-rose-600 text-white font-black font-unbounded rounded-2xl shadow-lg shadow-rose-600/30 hover:bg-rose-700 hover:scale-[1.02] hover:shadow-rose-600/50 transition-all duration-300 disabled:opacity-70 flex items-center justify-center gap-3"
                        >
                            <span v-if="form.processing"
                                ><i class="fas fa-spinner fa-spin"></i>
                                Mengirim...</span
                            >
                            <span v-else
                                >Kirim Pesan <i class="fas fa-paper-plane"></i
                            ></span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>
