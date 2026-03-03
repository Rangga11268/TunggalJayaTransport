<script setup>
import { Head, Link, useForm } from "@inertiajs/vue3";
import FrontendLayout from "@/Layouts/FrontendLayout.vue";
import { useMagnetic } from "@/Composables/useMagnetic";
import { ref } from "vue";

defineOptions({ layout: FrontendLayout });

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const submitBtn = ref(null);
useMagnetic(submitBtn);

const showPassword = ref(false);

const form = useForm({
    login: "",
    password: "",
    remember: false,
});

const submit = () => {
    form.post(route("login"), {
        onFinish: () => form.reset("password"),
    });
};
</script>

<template>
    <Head title="Masuk - TUJAGO (Tunggal Jaya Go)" />

    <div
        class="min-h-screen grid grid-cols-1 lg:grid-cols-2 bg-white dark:bg-gray-950 transition-colors duration-500"
    >
        <!-- Left Side: Visual -->
        <div
            class="relative hidden lg:flex flex-col justify-center items-center bg-gray-100 dark:bg-black overflow-hidden border-r border-gray-200 dark:border-white/5 transition-colors duration-500"
        >
            <div class="absolute inset-0">
                <div
                    class="absolute inset-0 bg-[url('/img/hero-bus.jpg')] bg-cover bg-center opacity-70 dark:opacity-40 scale-110 animate-slow-zoom transition-opacity duration-500"
                ></div>
                <div
                    class="absolute inset-0 bg-gradient-to-br from-white/90 via-white/50 to-brand-red/10 dark:from-black dark:via-black/80 dark:to-brand-red/20 transition-colors duration-500"
                ></div>
            </div>

            <div class="relative z-10 text-center px-12">
                <div class="mb-12 flex justify-center">
                    <div
                        class="w-32 h-32 rounded-full bg-white/20 dark:bg-white/5 backdrop-blur-xl flex items-center justify-center border border-gray-200 dark:border-white/10 shadow-[0_0_50px_rgba(220,38,38,0.1)] dark:shadow-[0_0_50px_rgba(220,38,38,0.2)] p-4 group hover:scale-110 transition-transform duration-500"
                    >
                        <img
                            src="/img/logoNoBg.png"
                            alt="Logo TUJAGO"
                            class="w-full h-full object-contain drop-shadow-[0_0_15px_rgba(220,38,38,0.4)] dark:drop-shadow-[0_0_15px_rgba(255,255,255,0.5)]"
                        />
                    </div>
                </div>
                <h2
                    class="text-5xl font-black text-gray-900 dark:text-white mb-6 font-unbounded tracking-tighter transition-colors duration-500"
                >
                    SELAMAT <span class="text-brand-red">DATANG</span>
                </h2>
                <p
                    class="text-lg text-gray-600 dark:text-gray-400 leading-relaxed max-w-md mx-auto font-manrope font-medium transition-colors duration-500"
                >
                    Akses akun Anda untuk mengelola pemesanan tiket dan nikmati
                    layanan prioritas dari TUJAGO.
                </p>
            </div>
        </div>

        <!-- Right Side: Form -->
        <div
            class="flex flex-col justify-center items-center p-6 sm:p-12 pt-32 lg:pt-32 relative overflow-hidden bg-gray-50 dark:bg-transparent"
        >
            <!-- Decorative Background Element -->
            <div
                class="absolute -top-24 -right-24 w-96 h-96 bg-brand-red/10 rounded-full blur-[100px]"
            ></div>

            <div class="w-full max-w-md space-y-8 relative z-10">
                <!-- Mobile Header -->
                <div class="lg:hidden text-center mb-12">
                    <img
                        src="/img/logoNoBg.png"
                        alt="Logo TUJAGO"
                        class="h-16 w-auto mx-auto mb-6"
                    />
                    <h2
                        class="text-3xl font-black text-gray-900 dark:text-white font-unbounded tracking-tighter"
                    >
                        MASUK <span class="text-brand-red">AKUN</span>
                    </h2>
                </div>

                <div class="text-center lg:text-left">
                    <h2
                        class="hidden lg:block text-4xl font-black text-gray-900 dark:text-white mb-3 font-unbounded tracking-tighter"
                    >
                        LOGIN <span class="text-brand-red">DI SINI</span>
                    </h2>
                    <p
                        class="text-gray-500 dark:text-gray-400 font-manrope font-medium tracking-wide"
                    >
                        Silakan masukkan kredensial akun Anda.
                    </p>
                </div>

                <!-- Status Message -->
                <div
                    v-if="status"
                    class="p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-600 dark:text-emerald-400 text-sm font-bold font-manrope"
                >
                    {{ status }}
                </div>

                <form @submit.prevent="submit" class="mt-8 space-y-6">
                    <div>
                        <label
                            for="email"
                            class="block text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-[0.2em] font-unbounded mb-3 ml-1"
                            >Alamat Email / Phone</label
                        >
                        <div class="relative group">
                            <div
                                class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none group-focus-within:text-brand-red transition-colors"
                            >
                                <i
                                    class="fas fa-envelope text-gray-400 dark:text-gray-500"
                                ></i>
                            </div>
                            <input
                                id="email"
                                type="text"
                                v-model="form.login"
                                required
                                autofocus
                                autocomplete="username"
                                class="w-full bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-2xl py-4 pl-12 pr-5 text-gray-900 dark:text-white font-manrope focus:ring-2 focus:ring-brand-red/20 focus:border-brand-red transition-all placeholder:text-gray-400 dark:placeholder:text-gray-600 shadow-sm dark:shadow-none"
                                placeholder="nama@email.com"
                            />
                        </div>
                        <p
                            class="mt-2 text-xs font-bold text-brand-red font-manrope"
                            v-if="form.errors.login"
                        >
                            {{ form.errors.login }}
                        </p>
                    </div>

                    <div>
                        <div
                            class="flex justify-between items-center mb-3 ml-1"
                        >
                            <label
                                for="password"
                                class="block text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-[0.2em] font-unbounded"
                                >Kata Sandi</label
                            >
                            <Link
                                v-if="canResetPassword"
                                :href="route('password.request')"
                                class="text-[10px] font-black text-brand-red uppercase tracking-wider hover:text-red-500 transition-colors font-unbounded"
                            >
                                Lupa?
                            </Link>
                        </div>
                        <div class="relative group">
                            <div
                                class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none group-focus-within:text-brand-red transition-colors"
                            >
                                <i
                                    class="fas fa-lock text-gray-400 dark:text-gray-500"
                                ></i>
                            </div>
                            <input
                                id="password"
                                :type="showPassword ? 'text' : 'password'"
                                v-model="form.password"
                                required
                                autocomplete="current-password"
                                class="w-full bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-2xl py-4 pl-12 pr-12 text-gray-900 dark:text-white font-manrope focus:ring-2 focus:ring-brand-red/20 focus:border-brand-red transition-all placeholder:text-gray-400 dark:placeholder:text-gray-600 shadow-sm dark:shadow-none"
                                placeholder="••••••••"
                            />
                            <button
                                type="button"
                                @click="showPassword = !showPassword"
                                class="absolute inset-y-0 right-0 pr-5 flex items-center text-gray-400 hover:text-brand-red transition-colors"
                            >
                                <i
                                    :class="[
                                        'fas',
                                        showPassword
                                            ? 'fa-eye-slash'
                                            : 'fa-eye',
                                    ]"
                                ></i>
                            </button>
                        </div>
                        <p
                            class="mt-2 text-xs font-bold text-brand-red font-manrope"
                            v-if="form.errors.password"
                        >
                            {{ form.errors.password }}
                        </p>
                    </div>

                    <div class="flex items-center">
                        <label
                            class="relative flex items-center cursor-pointer group"
                        >
                            <input
                                type="checkbox"
                                v-model="form.remember"
                                class="peer sr-only"
                            />
                            <div
                                class="h-5 w-5 rounded-md border-2 border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 transition-all peer-checked:bg-brand-red peer-checked:border-brand-red shadow-sm dark:shadow-none"
                            ></div>
                            <i
                                class="fas fa-check absolute left-1 text-[10px] text-white opacity-0 peer-checked:opacity-100 transition-opacity"
                            ></i>
                            <span
                                class="ml-3 text-sm font-bold text-gray-500 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white transition-colors font-manrope"
                                >Ingat Perangkat Ini</span
                            >
                        </label>
                    </div>

                    <div class="pt-2">
                        <button
                            ref="submitBtn"
                            type="submit"
                            :disabled="form.processing"
                            class="w-full py-5 bg-brand-red text-white rounded-2xl font-black font-unbounded text-xs uppercase tracking-[0.2em] shadow-[0_10px_30px_rgba(220,38,38,0.3)] no-hover-effect disabled:opacity-50"
                        >
                            <span v-if="!form.processing">MASUK SEKARANG</span>
                            <span
                                v-else
                                class="flex items-center justify-center"
                            >
                                <i class="fas fa-circle-notch fa-spin mr-3"></i>
                                MEMPROSES...
                            </span>
                        </button>
                    </div>

                    <div class="text-center mt-10">
                        <p
                            class="text-sm text-gray-500 font-manrope font-medium"
                        >
                            Belum punya akun?
                            <Link
                                :href="route('register')"
                                class="font-black text-brand-red hover:text-red-500 transition-colors font-unbounded ml-2 text-xs"
                            >
                                DAFTAR SEKARANG
                            </Link>
                        </p>
                    </div>
                </form>

                <!-- Divider -->
                <div class="relative my-10">
                    <div class="absolute inset-0 flex items-center">
                        <div
                            class="w-full border-t border-gray-200 dark:border-white/5"
                        ></div>
                    </div>
                    <div class="relative flex justify-center text-[10px]">
                        <span class="auth-divider">ATAU MASUK DENGAN</span>
                    </div>
                </div>

                <!-- Social Login -->
                <div class="grid grid-cols-2 gap-4">
                    <a
                        href="/auth/google"
                        class="social-btn flex items-center justify-center py-4 rounded-2xl transition-colors"
                    >
                        <img
                            src="https://www.svgrepo.com/show/475656/google-color.svg"
                            class="h-5 w-5 mr-3 group-hover:scale-110 transition-transform"
                            alt="Google"
                        />
                        <span
                            class="text-xs font-black text-gray-700 dark:text-gray-300 font-unbounded uppercase tracking-wider"
                            >GOOGLE</span
                        >
                    </a>
                    <button
                        disabled
                        aria-disabled="true"
                        title="Facebook login coming soon"
                        class="social-btn disabled flex items-center justify-center py-4 rounded-2xl cursor-not-allowed"
                    >
                        <i
                            class="fab fa-facebook text-blue-500 text-lg mr-3"
                        ></i>
                        <span
                            class="text-xs font-black text-gray-700 dark:text-gray-400 font-unbounded uppercase tracking-wider"
                            >SEGERA</span
                        >
                    </button>
                </div>
            </div>

            <!-- Footer -->
            <div
                class="mt-16 text-center text-[10px] text-gray-600 dark:text-gray-500 font-black font-unbounded uppercase tracking-[0.3em] pb-12"
            >
                &copy; {{ new Date().getFullYear() }} TUJAGO &bull; TUNGGAL JAYA
                GO
            </div>
        </div>
    </div>
</template>
