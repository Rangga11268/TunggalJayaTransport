<script setup>
import { Head, Link, useForm } from "@inertiajs/vue3";
import FrontendLayout from "@/Layouts/FrontendLayout.vue";
import { useMagnetic } from "@/Composables/useMagnetic";
import { ref } from "vue";

defineOptions({ layout: FrontendLayout });

const submitBtn = ref(null);
useMagnetic(submitBtn);

const showPassword = ref(false);
const showConfirmPassword = ref(false);

const form = useForm({
    name: "",
    email: "",
    phone: "",
    password: "",
    password_confirmation: "",
    terms: false,
});

const submit = () => {
    form.post(route("register"), {
        onFinish: () => form.reset("password", "password_confirmation"),
    });
};
</script>

<template>
    <Head title="Daftar Akun - TUJAGO (Tunggal Jaya Go)" />

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
                    class="absolute inset-0 bg-gradient-to-bl from-white/90 via-white/50 to-brand-red/10 dark:from-black dark:via-black/80 dark:to-brand-red/20 transition-colors duration-500"
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
                    BERGABUNG <span class="text-brand-red">KAMI</span>
                </h2>
                <p
                    class="text-lg text-gray-600 dark:text-gray-400 leading-relaxed max-w-md mx-auto font-manrope font-medium transition-colors duration-500"
                >
                    Buat akun baru untuk menikmati kemudahan pemesanan tiket bus
                    dan promo eksklusif dari TUJAGO.
                </p>
            </div>
        </div>

        <!-- Right Side: Form -->
        <div
            class="flex flex-col justify-center items-center p-6 sm:p-12 pt-32 lg:pt-32 relative overflow-hidden bg-gray-50 dark:bg-transparent"
        >
            <!-- Decorative Background Element -->
            <div
                class="absolute -bottom-24 -left-24 w-96 h-96 bg-brand-red/10 rounded-full blur-[100px]"
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
                        DAFTAR <span class="text-brand-red">BARU</span>
                    </h2>
                </div>

                <div class="text-center lg:text-left">
                    <h2
                        class="hidden lg:block text-4xl font-black text-gray-900 dark:text-white mb-3 font-unbounded tracking-tighter"
                    >
                        BUAT <span class="text-brand-red">AKUN</span>
                    </h2>
                    <p
                        class="text-gray-500 dark:text-gray-400 font-manrope font-medium tracking-wide"
                    >
                        Silakan lengkapi data diri Anda untuk mendaftar.
                    </p>
                </div>

                <form @submit.prevent="submit" class="mt-8 space-y-5">
                    <!-- Name -->
                    <div>
                        <label
                            for="name"
                            class="block text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-[0.2em] font-unbounded mb-3 ml-1"
                            >Nama Lengkap</label
                        >
                        <div class="relative group">
                            <div
                                class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none group-focus-within:text-brand-red transition-colors"
                            >
                                <i
                                    class="fas fa-user text-gray-400 dark:text-gray-500"
                                ></i>
                            </div>
                            <input
                                id="name"
                                type="text"
                                v-model="form.name"
                                required
                                autofocus
                                autocomplete="name"
                                class="w-full bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-2xl py-4 pl-12 pr-5 text-gray-900 dark:text-white font-manrope focus:ring-2 focus:ring-brand-red/20 focus:border-brand-red transition-all placeholder:text-gray-400 dark:placeholder:text-gray-600 shadow-sm dark:shadow-none"
                                placeholder="Jhon Doe"
                            />
                        </div>
                        <p
                            class="mt-2 text-xs font-bold text-brand-red font-manrope"
                            v-if="form.errors.name"
                        >
                            {{ form.errors.name }}
                        </p>
                    </div>

                    <!-- Email -->
                    <div>
                        <label
                            for="email"
                            class="block text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-[0.2em] font-unbounded mb-3 ml-1"
                            >Email</label
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
                                type="email"
                                v-model="form.email"
                                required
                                autocomplete="username"
                                class="w-full bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-2xl py-4 pl-12 pr-5 text-gray-900 dark:text-white font-manrope focus:ring-2 focus:ring-brand-red/20 focus:border-brand-red transition-all placeholder:text-gray-400 dark:placeholder:text-gray-600 shadow-sm dark:shadow-none"
                                placeholder="name@example.com"
                            />
                        </div>
                        <p
                            class="mt-2 text-xs font-bold text-brand-red font-manrope"
                            v-if="form.errors.email"
                        >
                            {{ form.errors.email }}
                        </p>
                    </div>

                    <!-- Phone -->
                    <div>
                        <label
                            for="phone"
                            class="block text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-[0.2em] font-unbounded mb-3 ml-1"
                            >Nomor Telepon</label
                        >
                        <div class="relative group">
                            <div
                                class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none group-focus-within:text-brand-red transition-colors"
                            >
                                <i
                                    class="fas fa-phone text-gray-400 dark:text-gray-500"
                                ></i>
                            </div>
                            <input
                                id="phone"
                                type="text"
                                v-model="form.phone"
                                required
                                class="w-full bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-2xl py-4 pl-12 pr-5 text-gray-900 dark:text-white font-manrope focus:ring-2 focus:ring-brand-red/20 focus:border-brand-red transition-all placeholder:text-gray-400 dark:placeholder:text-gray-600 shadow-sm dark:shadow-none"
                                placeholder="08xxxxxxxxxx"
                            />
                        </div>
                        <p
                            class="mt-2 text-xs font-bold text-brand-red font-manrope"
                            v-if="form.errors.phone"
                        >
                            {{ form.errors.phone }}
                        </p>
                    </div>

                    <!-- Password Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Password -->
                        <div>
                            <label
                                for="password"
                                class="block text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-[0.2em] font-unbounded mb-3 ml-1"
                                >Kata Sandi</label
                            >
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
                                    autocomplete="new-password"
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
                        </div>

                        <!-- Confirm Password -->
                        <div>
                            <label
                                for="password_confirmation"
                                class="block text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-[0.2em] font-unbounded mb-3 ml-1"
                                >Konfirmasi</label
                            >
                            <div class="relative group">
                                <div
                                    class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none group-focus-within:text-brand-red transition-colors"
                                >
                                    <i
                                        class="fas fa-check-circle text-gray-400 dark:text-gray-500"
                                    ></i>
                                </div>
                                <input
                                    id="password_confirmation"
                                    :type="
                                        showConfirmPassword
                                            ? 'text'
                                            : 'password'
                                    "
                                    v-model="form.password_confirmation"
                                    required
                                    autocomplete="new-password"
                                    class="w-full bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-2xl py-4 pl-12 pr-12 text-gray-900 dark:text-white font-manrope focus:ring-2 focus:ring-brand-red/20 focus:border-brand-red transition-all placeholder:text-gray-400 dark:placeholder:text-gray-600 shadow-sm dark:shadow-none"
                                    placeholder="••••••••"
                                />
                                <button
                                    type="button"
                                    @click="
                                        showConfirmPassword =
                                            !showConfirmPassword
                                    "
                                    class="absolute inset-y-0 right-0 pr-5 flex items-center text-gray-400 hover:text-brand-red transition-colors"
                                >
                                    <i
                                        :class="[
                                            'fas',
                                            showConfirmPassword
                                                ? 'fa-eye-slash'
                                                : 'fa-eye',
                                        ]"
                                    ></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="flex gap-4">
                        <p
                            class="text-xs font-bold text-brand-red font-manrope"
                            v-if="form.errors.password"
                        >
                            {{ form.errors.password }}
                        </p>
                        <p
                            class="text-xs font-bold text-brand-red font-manrope"
                            v-if="form.errors.password_confirmation"
                        >
                            {{ form.errors.password_confirmation }}
                        </p>
                    </div>

                    <div class="pt-4">
                        <button
                            ref="submitBtn"
                            type="submit"
                            :disabled="form.processing"
                            class="w-full py-5 bg-brand-red text-white rounded-2xl font-black font-unbounded text-xs uppercase tracking-[0.2em] shadow-[0_10px_30px_rgba(220,38,38,0.3)] no-hover-effect disabled:opacity-50"
                        >
                            <span v-if="!form.processing">DAFTAR SEKARANG</span>
                            <span
                                v-else
                                class="flex items-center justify-center"
                            >
                                <i class="fas fa-circle-notch fa-spin mr-3"></i>
                                MEMPROSES...
                            </span>
                        </button>
                    </div>

                    <!-- Divider -->
                    <div class="relative my-8">
                        <div class="absolute inset-0 flex items-center">
                            <div
                                class="w-full border-t border-gray-200 dark:border-white/5"
                            ></div>
                        </div>
                        <div class="relative flex justify-center text-[10px]">
                            <span class="auth-divider">ATAU DAFTAR DENGAN</span>
                        </div>
                    </div>

                    <!-- Social Login -->
                    <a
                        href="/auth/google"
                        class="social-btn flex items-center justify-center py-4 w-full rounded-2xl"
                    >
                        <img
                            src="https://www.svgrepo.com/show/475656/google-color.svg"
                            class="h-5 w-5 mr-3"
                            alt="Google"
                        />
                        <span
                            class="text-xs font-black font-unbounded uppercase tracking-wider"
                            >GOOGLE</span
                        >
                    </a>
                </form>

                <!-- Already have account? -->
                <div class="text-center mt-8">
                    <p
                        class="text-sm text-gray-500 dark:text-gray-400 font-manrope font-medium"
                    >
                        Sudah punya akun?
                        <Link
                            :href="route('login')"
                            class="font-black text-brand-red hover:text-red-500 dark:hover:text-red-400 transition-colors font-unbounded ml-2 text-xs"
                        >
                            MASUK SEKARANG
                        </Link>
                    </p>
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
