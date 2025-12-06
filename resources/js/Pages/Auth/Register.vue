<script setup>
import { Head, Link, useForm } from "@inertiajs/vue3";
import FrontendLayout from "@/Layouts/FrontendLayout.vue";

defineOptions({ layout: FrontendLayout });

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

    <div class="min-h-screen grid grid-cols-1 lg:grid-cols-2">
        <!-- Left Side: Visual -->
        <div
            class="relative hidden lg:flex flex-col justify-center items-center bg-primary-950 overflow-hidden"
        >
            <div class="absolute inset-0">
                <div class="absolute inset-0 hero-pattern opacity-10"></div>
                <div class="stars absolute inset-0 opacity-40"></div>
                <div
                    class="absolute top-0 right-0 w-full h-full bg-gradient-to-bl from-primary-900/50 to-primary-950/90"
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
                    Bergabung Bersama Kami
                </h2>
                <p
                    class="text-lg text-gray-300 leading-relaxed max-w-md mx-auto"
                >
                    Buat akun baru untuk menikmati kemudahan pemesanan tiket
                    bus, promo eksklusif, dan layanan pelanggan prioritas.
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
                        Buat Akun Baru
                    </h2>
                </div>

                <div class="text-center lg:text-left">
                    <h2
                        class="hidden lg:block text-3xl font-bold text-gray-900 dark:text-white mb-2"
                    >
                        Buat Akun Baru
                    </h2>
                    <p class="text-gray-500 dark:text-gray-400">
                        Silakan lengkapi data diri Anda untuk mendaftar.
                    </p>
                </div>

                <form @submit.prevent="submit" class="mt-8 space-y-5">
                    <!-- Name -->
                    <div>
                        <label
                            for="name"
                            class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2"
                            >Nama Lengkap</label
                        >
                        <div class="relative">
                            <i
                                class="fas fa-user absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"
                            ></i>
                            <input
                                id="name"
                                type="text"
                                v-model="form.name"
                                required
                                autofocus
                                autocomplete="name"
                                class="input-premium pl-12 w-full bg-white dark:bg-gray-900 border-gray-200 dark:border-gray-800 focus:border-brand-red focus:ring-brand-red"
                                placeholder="Jhon Doe"
                            />
                        </div>
                        <p
                            class="mt-2 text-sm text-red-600"
                            v-if="form.errors.name"
                        >
                            {{ form.errors.name }}
                        </p>
                    </div>

                    <!-- Email -->
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
                                type="email"
                                v-model="form.email"
                                required
                                autocomplete="username"
                                class="input-premium pl-12 w-full bg-white dark:bg-gray-900 border-gray-200 dark:border-gray-800 focus:border-brand-red focus:ring-brand-red"
                                placeholder="name@example.com"
                            />
                        </div>
                        <p
                            class="mt-2 text-sm text-red-600"
                            v-if="form.errors.email"
                        >
                            {{ form.errors.email }}
                        </p>
                    </div>

                    <!-- Phone -->
                    <div>
                        <label
                            for="phone"
                            class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2"
                            >Nomor Telepon</label
                        >
                        <div class="relative">
                            <i
                                class="fas fa-phone absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"
                            ></i>
                            <input
                                id="phone"
                                type="text"
                                v-model="form.phone"
                                required
                                class="input-premium pl-12 w-full bg-white dark:bg-gray-900 border-gray-200 dark:border-gray-800 focus:border-brand-red focus:ring-brand-red"
                                placeholder="08xxxxxxxxxx"
                            />
                        </div>
                        <p
                            class="mt-2 text-sm text-red-600"
                            v-if="form.errors.phone"
                        >
                            {{ form.errors.phone }}
                        </p>
                    </div>

                    <!-- Password -->
                    <div>
                        <label
                            for="password"
                            class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2"
                            >Kata Sandi</label
                        >
                        <div class="relative">
                            <i
                                class="fas fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"
                            ></i>
                            <input
                                id="password"
                                type="password"
                                v-model="form.password"
                                required
                                autocomplete="new-password"
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

                    <!-- Confirm Password -->
                    <div>
                        <label
                            for="password_confirmation"
                            class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2"
                            >Konfirmasi Kata Sandi</label
                        >
                        <div class="relative">
                            <i
                                class="fas fa-check-circle absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"
                            ></i>
                            <input
                                id="password_confirmation"
                                type="password"
                                v-model="form.password_confirmation"
                                required
                                autocomplete="new-password"
                                class="input-premium pl-12 w-full bg-white dark:bg-gray-900 border-gray-200 dark:border-gray-800 focus:border-brand-red focus:ring-brand-red"
                                placeholder="••••••••"
                            />
                        </div>
                        <p
                            class="mt-2 text-sm text-red-600"
                            v-if="form.errors.password_confirmation"
                        >
                            {{ form.errors.password_confirmation }}
                        </p>
                    </div>

                    <div class="py-2">
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="btn-premium w-full py-3.5 shadow-lg shadow-brand-red/30"
                        >
                            <span v-if="!form.processing">Daftar Sekarang</span>
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
                            Sudah punya akun?
                            <Link
                                :href="route('login')"
                                class="font-bold text-brand-red hover:underline"
                            >
                                Masuk Sekarang
                            </Link>
                        </p>
                    </div>
                </form>
            </div>

            <!-- Footer Links -->
            <div
                class="mt-8 lg:absolute lg:bottom-8 text-center text-xs text-gray-400"
            >
                &copy; {{ new Date().getFullYear() }} TUJAGO (Tunggal Jaya Go).
                All rights reserved.
            </div>
        </div>
</template>
