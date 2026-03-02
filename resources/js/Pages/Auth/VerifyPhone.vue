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

// Track whether OTP has been sent (step 1 → step 2)
// If status or debugOtp is already present (e.g. page refresh), skip to step 2
const otpSent = ref(!!(props.status || props.debugOtp));
const selectedMethod = ref("whatsapp");

// Form for sending OTP (step 1)
const sendForm = useForm({ method: "whatsapp" });

// Form for verifying OTP (step 2)
const verifyForm = useForm({ otp: "", method: "whatsapp" });

const setMethod = (method) => {
    selectedMethod.value = method;
    sendForm.method = method;
    verifyForm.method = method;
};

const sendOtp = () => {
    sendForm.post(route("verification.phone.send"), {
        preserveScroll: true,
        onSuccess: () => {
            otpSent.value = true;
        },
    });
};

const submit = () => {
    verifyForm.post(route("verification.phone.verify"), {
        onFinish: () => verifyForm.reset("otp"),
    });
};

const resendOtp = () => {
    sendForm.post(route("verification.phone.resend"), {
        preserveScroll: true,
        onSuccess: () => {
            otpSent.value = true;
        },
    });
};
</script>

<template>
    <Head title="Verifikasi Telepon - TUJAGO (Tunggal Jaya Go)" />

    <div
        class="min-h-screen grid grid-cols-1 lg:grid-cols-2 bg-gray-50 dark:bg-gray-950"
    >
        <!-- Left Side: Visual -->
        <div
            class="relative hidden lg:flex flex-col justify-center items-center bg-white dark:bg-black overflow-hidden border-r border-gray-200 dark:border-white/5"
        >
            <div class="absolute inset-0">
                <div
                    class="absolute inset-0 bg-[url('/img/hero-bus.jpg')] bg-cover bg-center opacity-40 scale-110 animate-slow-zoom"
                ></div>
                <div
                    class="absolute inset-0 bg-gradient-to-br from-white dark:from-black via-white/90 dark:via-black/80 to-brand-red/20"
                ></div>
            </div>

            <div class="relative z-10 text-center px-12">
                <div class="mb-12 flex justify-center">
                    <div
                        class="w-32 h-32 rounded-full bg-gray-100 dark:bg-white/5 backdrop-blur-xl flex items-center justify-center border border-gray-300 dark:border-white/10 shadow-[0_0_50px_rgba(220,38,38,0.2)]"
                    >
                        <i
                            class="fas fa-shield-alt text-6xl text-brand-red drop-shadow-lg"
                        ></i>
                    </div>
                </div>
                <h2
                    class="text-4xl font-black text-gray-900 dark:text-white mb-6 font-unbounded tracking-tighter uppercase"
                >
                    AMANKAN <span class="text-brand-red">AKUN</span>
                </h2>
                <p
                    class="text-lg text-gray-600 dark:text-gray-400 leading-relaxed max-w-md mx-auto font-manrope font-medium"
                >
                    Verifikasi nomor telepon atau email Anda untuk meningkatkan
                    keamanan akun dan akses layanan prioritas TUJAGO.
                </p>
            </div>
        </div>

        <!-- Right Side: Form -->
        <div
            class="flex flex-col justify-center items-center p-6 sm:p-12 pt-32 lg:pt-32 relative overflow-hidden bg-white dark:bg-gray-950"
        >
            <!-- Decorative Background Element -->
            <div
                class="absolute -top-24 -right-24 w-96 h-96 bg-brand-red/5 dark:bg-brand-red/10 rounded-full blur-[100px]"
            ></div>

            <div class="w-full max-w-md space-y-8 relative z-10">
                <!-- Mobile Header -->
                <div class="lg:hidden text-center mb-12">
                    <div
                        class="inline-flex w-20 h-20 rounded-full bg-gray-100 dark:bg-white/5 items-center justify-center mb-6 border border-gray-300 dark:border-white/10 shadow-xl"
                    >
                        <i
                            class="fas fa-shield-alt text-3xl text-brand-red"
                        ></i>
                    </div>
                    <h2
                        class="text-3xl font-black text-gray-900 dark:text-white font-unbounded tracking-tighter"
                    >
                        VERIFIKASI <span class="text-brand-red">AKUN</span>
                    </h2>
                </div>

                <div class="text-center lg:text-left">
                    <h2
                        class="hidden lg:block text-4xl font-black text-gray-900 dark:text-white mb-3 font-unbounded tracking-tighter"
                    >
                        VERIFIKASI <span class="text-brand-red">AKUN</span>
                    </h2>
                    <p
                        class="text-gray-600 dark:text-gray-400 font-manrope font-medium tracking-wide"
                    >
                        Pilih metode verifikasi untuk mengirimkan kode OTP.
                    </p>
                </div>

                <!-- Method Selection Tabs -->
                <div
                    class="flex p-1 bg-gray-100 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-2xl mb-6"
                >
                    <button
                        @click="setMethod('whatsapp')"
                        class="flex-1 py-3 rounded-xl text-xs font-black transition-all duration-300 font-unbounded uppercase tracking-wider"
                        :class="
                            selectedMethod === 'whatsapp'
                                ? 'bg-brand-red text-white shadow-lg'
                                : 'text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white'
                        "
                    >
                        <i class="fab fa-whatsapp mr-2"></i> WhatsApp
                    </button>
                    <button
                        @click="setMethod('email')"
                        class="flex-1 py-3 rounded-xl text-xs font-black transition-all duration-300 font-unbounded uppercase tracking-wider"
                        :class="
                            selectedMethod === 'email'
                                ? 'bg-brand-red text-white shadow-lg'
                                : 'text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white'
                        "
                    >
                        <i class="fas fa-envelope mr-2"></i> Email
                    </button>
                </div>

                <!-- Status Message -->
                <div
                    v-if="status"
                    class="p-4 rounded-xl bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-500/20 text-emerald-700 dark:text-emerald-400 text-sm font-bold font-manrope flex items-center"
                >
                    <i class="fas fa-check-circle mr-3"></i>
                    <span>{{ status }}</span>
                </div>

                <!-- Debug OTP Alert -->
                <div
                    v-if="debugOtp"
                    class="p-4 rounded-xl bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-500/20 text-yellow-800 dark:text-yellow-400 text-sm font-manrope"
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
                        <strong class="text-yellow-900 dark:text-white">{{
                            debugOtp
                        }}</strong>
                    </p>
                </div>

                <!-- STEP 1: Send OTP -->
                <div v-if="!otpSent" class="mt-8 space-y-6">
                    <p
                        class="text-sm text-gray-500 dark:text-gray-400 font-manrope text-center"
                    >
                        Klik tombol di bawah untuk mengirim kode OTP ke
                        <span class="font-bold text-gray-800 dark:text-white">
                            {{
                                selectedMethod === "whatsapp"
                                    ? "WhatsApp"
                                    : "Email"
                            }}
                        </span>
                        Anda.
                    </p>
                    <button
                        @click="sendOtp"
                        :disabled="sendForm.processing"
                        class="w-full py-5 bg-brand-red text-white rounded-2xl font-black font-unbounded text-xs uppercase tracking-[0.2em] shadow-[0_10px_30px_rgba(220,38,38,0.3)] hover:bg-red-700 dark:hover:bg-red-700 transition-all active:scale-[0.98] disabled:opacity-50"
                    >
                        <span v-if="!sendForm.processing">
                            <i class="fas fa-paper-plane mr-2"></i>
                            KIRIM KODE OTP
                        </span>
                        <span v-else class="flex items-center justify-center">
                            <i class="fas fa-circle-notch fa-spin mr-3"></i>
                            MENGIRIM...
                        </span>
                    </button>
                    <p
                        v-if="sendForm.errors.method"
                        class="text-xs font-bold text-brand-red font-manrope text-center"
                    >
                        {{ sendForm.errors.method }}
                    </p>
                </div>

                <!-- STEP 2: Verify OTP -->
                <form
                    v-if="otpSent"
                    @submit.prevent="submit"
                    class="mt-8 space-y-8"
                >
                    <div>
                        <label
                            for="otp"
                            class="block text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-[0.2em] font-unbounded mb-3 ml-1"
                            >Kode OTP (via
                            {{
                                selectedMethod === "email"
                                    ? "Email"
                                    : "WhatsApp"
                            }})</label
                        >
                        <div class="relative group">
                            <div
                                class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-gray-400 dark:text-gray-500 group-focus-within:text-brand-red transition-colors"
                            >
                                <i
                                    class="fas"
                                    :class="
                                        selectedMethod === 'email'
                                            ? 'fa-envelope-open-text'
                                            : 'fa-mobile-alt'
                                    "
                                ></i>
                            </div>
                            <input
                                id="otp"
                                type="text"
                                v-model="verifyForm.otp"
                                required
                                autofocus
                                autocomplete="one-time-code"
                                class="w-full bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-2xl py-5 pl-12 pr-5 text-gray-900 dark:text-white font-unbounded focus:ring-2 focus:ring-brand-red/20 focus:border-brand-red transition-all tracking-[0.5em] text-center text-2xl font-black placeholder:text-gray-400 dark:placeholder:text-gray-700 placeholder:tracking-normal"
                                placeholder="······"
                                maxlength="6"
                            />
                        </div>
                        <p
                            class="mt-2 text-xs font-bold text-brand-red font-manrope"
                            v-if="verifyForm.errors.otp"
                        >
                            {{ verifyForm.errors.otp }}
                        </p>
                    </div>

                    <div class="space-y-6 pt-2">
                        <button
                            ref="submitBtn"
                            type="submit"
                            :disabled="verifyForm.processing"
                            class="w-full py-5 bg-brand-red text-white rounded-2xl font-black font-unbounded text-xs uppercase tracking-[0.2em] shadow-[0_10px_30px_rgba(220,38,38,0.3)] hover:bg-red-700 dark:hover:bg-red-700 transition-all active:scale-[0.98] disabled:opacity-50"
                        >
                            <span v-if="!verifyForm.processing"
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
                                :disabled="sendForm.processing"
                                class="text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest hover:text-brand-red dark:hover:text-brand-red transition-colors font-unbounded"
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
                        class="text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest hover:text-gray-900 dark:hover:text-white transition-colors font-unbounded"
                    >
                        KELUAR AKUN
                    </Link>
                </div>
            </div>

            <!-- Footer -->
            <div
                class="mt-16 text-center text-[10px] text-gray-600 dark:text-gray-400 font-black font-unbounded uppercase tracking-[0.3em] pb-12"
            >
                &copy; {{ new Date().getFullYear() }} TUJAGO &bull; TUNGGAL JAYA
                GO
            </div>
        </div>
    </div>
</template>
