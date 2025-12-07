<script setup>
import { Head, Link, useForm } from "@inertiajs/vue3";
// FrontendLayout removed to avoid confusion with navbar while verifying

const props = defineProps({
    status: {
        type: String,
    },
    debugOtp: {
        type: [String, Number],
        default: null,
    },
});

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

    <div class="min-h-screen grid grid-cols-1 lg:grid-cols-2">
        <!-- Left Side: Visual -->
        <div
            class="relative hidden lg:flex flex-col justify-center items-center bg-primary-950 overflow-hidden"
        >
            <div class="absolute inset-0">
                <div class="absolute inset-0 hero-pattern opacity-10"></div>
                <div class="stars absolute inset-0 opacity-40"></div>
                <div
                    class="absolute top-0 right-0 w-full h-full bg-gradient-to-br from-primary-900/50 to-primary-950/90"
                ></div>
            </div>

            <div class="relative z-10 text-center px-12">
                <div class="mb-8 flex justify-center">
                    <div
                        class="w-32 h-32 rounded-full bg-white/10 backdrop-blur-md flex items-center justify-center border border-white/20 shadow-2xl shadow-brand-red/20 animate-pulse-slow p-4"
                    >
                        <i
                            class="fas fa-shield-alt text-6xl text-white drop-shadow-lg"
                        ></i>
                    </div>
                </div>
                <h2 class="text-4xl font-serif font-bold text-white mb-6">
                    Amankan Akun Anda
                </h2>
                <p
                    class="text-lg text-gray-300 leading-relaxed max-w-md mx-auto"
                >
                    Verifikasi nomor telepon atau email Anda untuk meningkatkan
                    keamanan akun dan memastikan Anda mendapatkan notifikasi
                    penting seputar perjalanan Anda.
                </p>
            </div>
        </div>

        <!-- Right Side: Form -->
        <div
            class="flex flex-col justify-center items-center bg-gray-50 dark:bg-gray-950 p-6 sm:p-12 relative"
        >
            <div class="w-full max-w-md space-y-8">
                <!-- Mobile Logo (Visible only on small screens) -->
                <div class="lg:hidden text-center mb-8">
                    <div
                        class="inline-flex w-20 h-20 rounded-full bg-primary-900/10 items-center justify-center mb-4 p-3"
                    >
                        <img
                            src="/img/logoNoBg.png"
                            alt="Logo TUJAGO"
                            class="w-full h-full object-contain"
                        />
                    </div>
                    <h2
                        class="text-2xl font-bold text-gray-900 dark:text-white"
                    >
                        Verifikasi Telepon
                    </h2>
                </div>

                <div class="text-center lg:text-left">
                    <h2
                        class="hidden lg:block text-3xl font-bold text-gray-900 dark:text-white mb-2"
                    >
                        Verifikasi Telepon
                    </h2>
                    <p class="text-gray-500 dark:text-gray-400 mb-4">
                        Pilih metode verifikasi untuk mengirimkan kode OTP.
                    </p>
                </div>

                <!-- Method Selection Tabs -->
                <div
                    class="flex p-1 bg-gray-100 dark:bg-gray-800 rounded-xl mb-6"
                >
                    <button
                        @click="setMethod('whatsapp')"
                        class="flex-1 py-2.5 rounded-lg text-sm font-medium transition-all duration-200"
                        :class="
                            form.method === 'whatsapp'
                                ? 'bg-white dark:bg-gray-700 shadow text-brand-red'
                                : 'text-gray-500 hover:text-gray-700 dark:text-gray-400'
                        "
                    >
                        <i class="fab fa-whatsapp mr-2"></i> WhatsApp
                    </button>
                    <button
                        @click="setMethod('email')"
                        class="flex-1 py-2.5 rounded-lg text-sm font-medium transition-all duration-200"
                        :class="
                            form.method === 'email'
                                ? 'bg-white dark:bg-gray-700 shadow text-brand-red'
                                : 'text-gray-500 hover:text-gray-700 dark:text-gray-400'
                        "
                    >
                        <i class="fas fa-envelope mr-2"></i> Email
                    </button>
                </div>

                <!-- Status Message -->
                <div
                    v-if="status"
                    class="mb-4 font-medium text-sm text-green-600 p-3 bg-green-50 dark:bg-green-900/10 rounded-lg flex items-start"
                >
                    <i class="fas fa-check-circle mt-0.5 mr-2"></i>
                    <span>{{ status }}</span>
                </div>

                <!-- Debug OTP Alert (Only if debugOtp is present) -->
                <div
                    v-if="debugOtp"
                    class="mb-4 p-4 rounded-lg bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800"
                >
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i
                                class="fas fa-bug text-yellow-600 dark:text-yellow-400"
                            ></i>
                        </div>
                        <div class="ml-3">
                            <h3
                                class="text-sm font-medium text-yellow-800 dark:text-yellow-300"
                            >
                                Debug Mode
                            </h3>
                            <div
                                class="mt-2 text-sm text-yellow-700 dark:text-yellow-400"
                            >
                                <p>
                                    Kode OTP Anda adalah:
                                    <strong>{{ debugOtp }}</strong>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <form @submit.prevent="submit" class="mt-8 space-y-6">
                    <div>
                        <label
                            for="otp"
                            class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2"
                            >Kode OTP (via
                            {{
                                form.method === "email" ? "Email" : "WhatsApp"
                            }})</label
                        >
                        <div class="relative">
                            <i
                                class="fas absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"
                                :class="
                                    form.method === 'email'
                                        ? 'fa-envelope-open-text'
                                        : 'fa-mobile-alt'
                                "
                            ></i>
                            <input
                                id="otp"
                                type="text"
                                v-model="form.otp"
                                required
                                autofocus
                                autocomplete="one-time-code"
                                class="input-premium pl-12 w-full bg-white dark:bg-gray-900 border-gray-200 dark:border-gray-800 focus:border-brand-red focus:ring-brand-red tracking-widest text-center text-lg font-bold"
                                placeholder="000000"
                                maxlength="6"
                            />
                        </div>
                        <p
                            class="mt-2 text-sm text-red-600"
                            v-if="form.errors.otp"
                        >
                            {{ form.errors.otp }}
                        </p>
                    </div>

                    <div>
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="btn-premium w-full py-3.5 shadow-lg shadow-brand-red/30"
                        >
                            <span v-if="!form.processing">Verifikasi</span>
                            <span v-else
                                ><i
                                    class="fas fa-circle-notch fa-spin mr-2"
                                ></i>
                                Memproses...</span
                            >
                        </button>
                    </div>

                    <div class="text-center mt-6">
                        <p
                            class="text-sm text-gray-600 dark:text-gray-400 flex flex-col gap-2"
                        >
                            <span>Tidak menerima kode?</span>
                            <button
                                type="button"
                                @click="resendOtp"
                                :disabled="form.processing"
                                class="font-bold text-brand-red hover:underline focus:outline-none"
                            >
                                Kirim Ulang OTP via
                                {{
                                    form.method === "email"
                                        ? "Email"
                                        : "WhatsApp"
                                }}
                            </button>
                        </p>
                    </div>
                </form>

                <!-- Logout/Back Link -->
                <div class="text-center mt-4">
                    <Link
                        :href="route('logout')"
                        method="post"
                        as="button"
                        class="text-sm text-gray-500 hover:text-gray-700 dark:hover:text-gray-300 transition-colors"
                    >
                        Keluar
                    </Link>
                </div>
            </div>
        </div>
    </div>
</template>
