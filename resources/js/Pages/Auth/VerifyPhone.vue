<script setup>
import { Head, Link, useForm } from "@inertiajs/vue3";
import FrontendLayout from "@/Layouts/FrontendLayout.vue";
import { useMagnetic } from "@/Composables/useMagnetic";
import { ref } from "vue";

defineOptions({ layout: FrontendLayout });

const props = defineProps({
    status: {
        type: String,
    },
    debugOtp: {
        type: [String, Number],
        default: null,
    },
});

const submitBtn = ref(null);
useMagnetic(submitBtn);

const form = useForm({
    otp: "",
    method: "whatsapp", // default
});

const setMethod = (method) => {
    form.method = method;
};

const submit = () => {
    form.post(route("verification.phone.verify"), {
        onFinish: () => form.reset("otp"),
    });
};

const resendOtp = () => {
    form.post(route("verification.phone.resend"), {
        preserveScroll: true,
        data: { method: form.method },
    });
};
</script>

<template>
    <Head title="Verifikasi Telepon - TUJAGO (Tunggal Jaya Go)" />

    <div class="min-h-screen grid grid-cols-1 lg:grid-cols-2 bg-gray-950">
        <!-- Left Side: Visual -->
        <div
            class="relative hidden lg:flex flex-col justify-center items-center bg-black overflow-hidden border-r border-white/5"
        >
            <div class="absolute inset-0">
                <div
                    class="absolute inset-0 bg-[url('/img/hero-bus.jpg')] bg-cover bg-center opacity-40 scale-110 animate-slow-zoom"
                ></div>
                <div
                    class="absolute inset-0 bg-gradient-to-br from-black via-black/80 to-brand-red/20"
                ></div>
            </div>

            <div class="relative z-10 text-center px-12">
                <div class="mb-12 flex justify-center">
                    <div
                        class="w-32 h-32 rounded-full bg-white/5 backdrop-blur-xl flex items-center justify-center border border-white/10 shadow-[0_0_50px_rgba(220,38,38,0.2)]"
                    >
                        <i
                            class="fas fa-shield-alt text-6xl text-brand-red drop-shadow-lg"
                        ></i>
                    </div>
                </div>
                <h2
                    class="text-4xl font-black text-white mb-6 font-unbounded tracking-tighter uppercase"
                >
                    AMANKAN <span class="text-brand-red">AKUN</span>
                </h2>
                <p
                    class="text-lg text-gray-400 leading-relaxed max-w-md mx-auto font-manrope font-medium"
                >
                    Verifikasi nomor telepon atau email Anda untuk meningkatkan
                    keamanan akun dan akses layanan prioritas TUJAGO.
                </p>
            </div>
        </div>

        <!-- Right Side: Form -->
        <div
            class="flex flex-col justify-center items-center p-6 sm:p-12 pt-32 lg:pt-32 relative overflow-hidden"
        >
            <!-- Decorative Background Element -->
            <div
                class="absolute -top-24 -right-24 w-96 h-96 bg-brand-red/10 rounded-full blur-[100px]"
            ></div>

            <div class="w-full max-w-md space-y-8 relative z-10">
                <!-- Mobile Header -->
                <div class="lg:hidden text-center mb-12">
                    <div
                        class="inline-flex w-20 h-20 rounded-full bg-white/5 items-center justify-center mb-6 border border-white/10 shadow-xl"
                    >
                        <i
                            class="fas fa-shield-alt text-3xl text-brand-red"
                        ></i>
                    </div>
                    <h2
                        class="text-3xl font-black text-white font-unbounded tracking-tighter"
                    >
                        VERIFIKASI <span class="text-brand-red">AKUN</span>
                    </h2>
                </div>

                <div class="text-center lg:text-left">
                    <h2
                        class="hidden lg:block text-4xl font-black text-white mb-3 font-unbounded tracking-tighter"
                    >
                        VERIFIKASI <span class="text-brand-red">AKUN</span>
                    </h2>
                    <p
                        class="text-gray-400 font-manrope font-medium tracking-wide"
                    >
                        Pilih metode verifikasi untuk mengirimkan kode OTP.
                    </p>
                </div>

                <!-- Method Selection Tabs -->
                <div
                    class="flex p-1 bg-white/5 border border-white/10 rounded-2xl mb-6"
                >
                    <button
                        @click="setMethod('whatsapp')"
                        class="flex-1 py-3 rounded-xl text-xs font-black transition-all duration-300 font-unbounded uppercase tracking-wider"
                        :class="
                            form.method === 'whatsapp'
                                ? 'bg-brand-red text-white shadow-lg'
                                : 'text-gray-500 hover:text-white'
                        "
                    >
                        <i class="fab fa-whatsapp mr-2"></i> WhatsApp
                    </button>
                    <button
                        @click="setMethod('email')"
                        class="flex-1 py-3 rounded-xl text-xs font-black transition-all duration-300 font-unbounded uppercase tracking-wider"
                        :class="
                            form.method === 'email'
                                ? 'bg-brand-red text-white shadow-lg'
                                : 'text-gray-500 hover:text-white'
                        "
                    >
                        <i class="fas fa-envelope mr-2"></i> Email
                    </button>
                </div>

                <!-- Status Message -->
                <div
                    v-if="status"
                    class="p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-sm font-bold font-manrope flex items-center"
                >
                    <i class="fas fa-check-circle mr-3"></i>
                    <span>{{ status }}</span>
                </div>

                <!-- Debug OTP Alert -->
                <div
                    v-if="debugOtp"
                    class="p-4 rounded-xl bg-yellow-500/10 border border-yellow-500/20 text-yellow-400 text-sm font-manrope"
                >
                    <div class="flex items-center mb-1">
                        <i class="fas fa-bug mr-2"></i>
                        <span
                            class="font-black uppercase text-[10px] tracking-widest font-unbounded"
                            >DEBUG MODE</span
                        >
                    </div>
                    <p>
                        Kode OTP Anda adalah:
                        <strong class="text-white">{{ debugOtp }}</strong>
                    </p>
                </div>

                <form @submit.prevent="submit" class="mt-8 space-y-8">
                    <div>
                        <label
                            for="otp"
                            class="block text-[10px] font-black text-gray-500 uppercase tracking-[0.2em] font-unbounded mb-3 ml-1"
                            >Kode OTP (via
                            {{
                                form.method === "email" ? "Email" : "WhatsApp"
                            }})</label
                        >
                        <div class="relative group">
                            <div
                                class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none group-focus-within:text-brand-red transition-colors"
                            >
                                <i
                                    class="fas text-gray-500"
                                    :class="
                                        form.method === 'email'
                                            ? 'fa-envelope-open-text'
                                            : 'fa-mobile-alt'
                                    "
                                ></i>
                            </div>
                            <input
                                id="otp"
                                type="text"
                                v-model="form.otp"
                                required
                                autofocus
                                autocomplete="one-time-code"
                                class="w-full bg-white/5 border-white/10 rounded-2xl py-5 pl-12 pr-5 text-white font-unbounded focus:ring-2 focus:ring-brand-red/20 focus:border-brand-red transition-all tracking-[0.5em] text-center text-2xl font-black placeholder:text-gray-800 placeholder:tracking-normal"
                                placeholder="******"
                                maxlength="6"
                            />
                        </div>
                        <p
                            class="mt-2 text-xs font-bold text-brand-red font-manrope"
                            v-if="form.errors.otp"
                        >
                            {{ form.errors.otp }}
                        </p>
                    </div>

                    <div class="space-y-6 pt-2">
                        <button
                            ref="submitBtn"
                            type="submit"
                            :disabled="form.processing"
                            class="w-full py-5 bg-brand-red text-white rounded-2xl font-black font-unbounded text-xs uppercase tracking-[0.2em] shadow-[0_10px_30px_rgba(220,38,38,0.3)] hover:bg-red-700 transition-all active:scale-[0.98] disabled:opacity-50"
                        >
                            <span v-if="!form.processing"
                                >VERIFIKASI SEKARANG</span
                            >
                            <span
                                v-else
                                class="flex items-center justify-center"
                            >
                                <i class="fas fa-circle-notch fa-spin mr-3"></i>
                                MEMPROSES...
                            </span>
                        </button>

                        <div class="text-center">
                            <button
                                type="button"
                                @click="resendOtp"
                                :disabled="form.processing"
                                class="text-[10px] font-black text-gray-500 uppercase tracking-widest hover:text-brand-red transition-colors font-unbounded"
                            >
                                <i class="fas fa-redo-alt mr-2"></i> Kirim Ulang
                                OTP
                            </button>
                        </div>
                    </div>
                </form>

                <!-- Logout Link -->
                <div class="text-center mt-12">
                    <Link
                        :href="route('logout')"
                        method="post"
                        as="button"
                        class="text-[10px] font-black text-gray-600 uppercase tracking-widest hover:text-white transition-colors font-unbounded"
                    >
                        KELUAR AKUN
                    </Link>
                </div>
            </div>

            <!-- Footer -->
            <div
                class="mt-16 text-center text-[10px] text-gray-600 font-black font-unbounded uppercase tracking-[0.3em] pb-12"
            >
                &copy; {{ new Date().getFullYear() }} TUJAGO &bull; TUNGGAL JAYA
                GO
            </div>
        </div>
    </div>
</template>
