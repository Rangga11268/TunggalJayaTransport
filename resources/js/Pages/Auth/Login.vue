<script setup>
import { Head, Link, useForm } from "@inertiajs/vue3";
import FrontendLayout from "@/Layouts/FrontendLayout.vue";

defineOptions({ layout: FrontendLayout });

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

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
                        <img
                            src="/img/logoNoBg.png"
                            alt="Logo TUJAGO"
                            class="w-full h-full object-contain drop-shadow-lg"
                        />
                    </div>
                </div>
                <h2 class="text-4xl font-serif font-bold text-white mb-6">
                    Selamat Datang Kembali
                </h2>
                <p
                    class="text-lg text-gray-300 leading-relaxed max-w-md mx-auto"
                >
                    Akses akun Anda untuk mengelola pemesanan tiket, melihat
                    riwayat perjalanan, dan nikmati layanan prioritas dari
                    TUJAGO (Tunggal Jaya Go).
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
                        Masuk ke Akun
                    </h2>
                </div>

                <div class="text-center lg:text-left">
                    <h2
                        class="hidden lg:block text-3xl font-bold text-gray-900 dark:text-white mb-2"
                    >
                        Masuk ke Akun
                    </h2>
                    <p class="text-gray-500 dark:text-gray-400">
                        Silakan masukkan email dan kata sandi Anda.
                    </p>
                </div>

                <!-- Status Message -->
                <div
                    v-if="status"
                    class="mb-4 font-medium text-sm text-green-600"
                >
                    {{ status }}
                </div>

                <form @submit.prevent="submit" class="mt-8 space-y-6">
                    <div>
                        <label
                            for="email"
                            class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2"
                            >Email</label
                        >
                        <div class="relative">
                            <i
                                class="fas fa-envelope absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"
                            ></i>
                            <input
                                id="email"
                                type="text"
                                v-model="form.login"
                                required
                                autofocus
                                autocomplete="username"
                                class="input-premium pl-12 w-full bg-white dark:bg-gray-900 border-gray-200 dark:border-gray-800 focus:border-brand-red focus:ring-brand-red"
                                placeholder="name@example.com"
                            />
                        </div>
                        <p
                            class="mt-2 text-sm text-red-600"
                            v-if="form.errors.login"
                        >
                            {{ form.errors.login }}
                        </p>
                    </div>

                    <div>
                        <div class="flex justify-between items-center mb-2">
                            <label
                                for="password"
                                class="block text-sm font-bold text-gray-700 dark:text-gray-300"
                                >Kata Sandi</label
                            >
                            <Link
                                v-if="canResetPassword"
                                :href="route('password.request')"
                                class="text-xs font-bold text-brand-red hover:underline tabindex='-1'"
                            >
                                Lupa Kata Sandi?
                            </Link>
                        </div>
                        <div class="relative">
                            <i
                                class="fas fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"
                            ></i>
                            <input
                                id="password"
                                type="password"
                                v-model="form.password"
                                required
                                autocomplete="current-password"
                                class="input-premium pl-12 w-full bg-white dark:bg-gray-900 border-gray-200 dark:border-gray-800 focus:border-brand-red focus:ring-brand-red"
                                placeholder="••••••••"
                            />
                        </div>
                        <p
                            class="mt-2 text-sm text-red-600"
                            v-if="form.errors.password"
                        >
                            {{ form.errors.password }}
                        </p>
                    </div>

                    <div class="flex items-center">
                        <label class="flex items-center cursor-pointer group">
                            <div class="relative flex items-center">
                                <input
                                    type="checkbox"
                                    v-model="form.remember"
                                    class="peer h-5 w-5 cursor-pointer appearance-none rounded-md border border-gray-300 transition-all checked:border-brand-red checked:bg-brand-red hover:border-brand-red"
                                />
                                <i
                                    class="fas fa-check absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 text-white opacity-0 peer-checked:opacity-100 text-xs pointer-events-none"
                                ></i>
                            </div>
                            <span
                                class="ml-2 text-sm text-gray-600 dark:text-gray-400 group-hover:text-brand-red transition-colors"
                                >Ingat Saya</span
                            >
                        </label>
                    </div>

                    <div>
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="btn-premium w-full py-3.5 shadow-lg shadow-brand-red/30"
                        >
                            <span v-if="!form.processing">Masuk Sekarang</span>
                            <span v-else
                                ><i
                                    class="fas fa-circle-notch fa-spin mr-2"
                                ></i>
                                Memproses...</span
                            >
                        </button>
                    </div>

                    <div class="text-center mt-6">
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            Belum punya akun?
                            <Link
                                :href="route('register')"
                                class="font-bold text-brand-red hover:underline"
                            >
                                Daftar Sekarang
                            </Link>
                        </p>
                    </div>
                </form>

                <!-- Divider -->
                <div class="relative my-8">
                    <div class="absolute inset-0 flex items-center">
                        <div
                            class="w-full border-t border-gray-200 dark:border-gray-800"
                        ></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span
                            class="px-2 bg-gray-50 dark:bg-gray-950 text-gray-500"
                            >Atau masuk dengan</span
                        >
                    </div>
                </div>

                <!-- Social Login Placeholders (Optional) -->
                <div class="grid grid-cols-2 gap-4">
                    <button
                        class="flex items-center justify-center py-2.5 border border-gray-200 dark:border-gray-800 rounded-xl hover:bg-white dark:hover:bg-gray-900 hover:shadow-md transition-all"
                    >
                        <img
                            src="https://www.svgrepo.com/show/475656/google-color.svg"
                            class="h-5 w-5 mr-2"
                            alt="Google"
                        />
                        <span
                            class="text-sm font-medium text-gray-700 dark:text-gray-300"
                            >Google</span
                        >
                    </button>
                    <button
                        class="flex items-center justify-center py-2.5 border border-gray-200 dark:border-gray-800 rounded-xl hover:bg-white dark:hover:bg-gray-900 hover:shadow-md transition-all"
                    >
                        <i
                            class="fab fa-facebook text-blue-600 text-lg mr-2"
                        ></i>
                        <span
                            class="text-sm font-medium text-gray-700 dark:text-gray-300"
                            >Facebook</span
                        >
                    </button>
                </div>
            </div>

            <!-- Footer Links -->
            <div
                class="mt-8 lg:absolute lg:bottom-8 text-center text-xs text-gray-400"
            >
                &copy; {{ new Date().getFullYear() }} TUJAGO (Tunggal Jaya Go).
                All rights reserved.
            </div>
        </div>
    </div>
</template>
