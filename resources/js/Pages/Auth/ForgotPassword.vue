<script setup>
import { Head, useForm, Link } from "@inertiajs/vue3";
import FrontendLayout from "@/Layouts/FrontendLayout.vue";

defineOptions({ layout: FrontendLayout });

defineProps({
    status: {
        type: String,
    },
});

const form = useForm({
    email: "",
});

const submit = () => {
    form.post(route("password.email"));
};
</script>

<template>
    <Head title="Lupa Kata Sandi - TUJAGO (Tunggal Jaya Go)" />

    <div class="min-h-screen grid grid-cols-1 lg:grid-cols-2">
        <!-- Left Side: Visual -->
        <div
            class="relative hidden lg:flex flex-col justify-center items-center bg-primary-950 overflow-hidden"
        >
            <div class="absolute inset-0">
                <div class="absolute inset-0 hero-pattern opacity-10"></div>
                <div class="stars absolute inset-0 opacity-40"></div>
                <!-- Gradient Overlay -->
                <div
                    class="absolute top-0 right-0 w-full h-full bg-gradient-to-r from-primary-900/50 to-primary-950/90"
                ></div>
            </div>

            <div class="relative z-10 text-center px-12">
                <div class="mb-8 flex justify-center">
                    <div
                        class="w-24 h-24 rounded-full bg-white/10 backdrop-blur-md flex items-center justify-center border border-white/20 shadow-2xl shadow-brand-red/20"
                    >
                        <i class="fas fa-lock-open text-5xl text-brand-red"></i>
                    </div>
                </div>
                <h2 class="text-4xl font-serif font-bold text-white mb-6">
                    Pulihkan Akun Anda
                </h2>
                <p
                    class="text-lg text-gray-300 leading-relaxed max-w-md mx-auto"
                >
                    Jangan khawatir jika lupa kata sandi. Kami akan membantu
                    Anda mengatur ulang kata sandi dengan cepat dan aman.
                </p>
            </div>
        </div>

        <!-- Right Side: Form -->
        <div
            class="flex flex-col justify-center items-center bg-gray-50 dark:bg-gray-950 p-6 sm:p-12 relative"
        >
            <div class="w-full max-w-md space-y-8">
                <!-- Mobile Logo -->
                <div class="lg:hidden text-center mb-8">
                    <div
                        class="inline-flex w-16 h-16 rounded-full bg-primary-900/10 items-center justify-center mb-4"
                    >
                        <i class="fas fa-bus text-3xl text-brand-red"></i>
                    </div>
                </div>

                <div class="text-center lg:text-left">
                    <h2
                        class="text-3xl font-bold text-gray-900 dark:text-white mb-2"
                    >
                        Lupa Kata Sandi?
                    </h2>
                    <p class="text-gray-500 dark:text-gray-400">
                        Masukkan email Anda dan kami akan mengirimkan tautan
                        untuk mengatur ulang kata sandi.
                    </p>
                </div>

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
                                type="email"
                                v-model="form.email"
                                required
                                autofocus
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

                    <div class="flex flex-col space-y-4">
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="btn-premium w-full py-3.5 shadow-lg shadow-brand-red/30"
                        >
                            <span v-if="!form.processing"
                                >Kirim Tautan Reset</span
                            >
                            <span v-else
                                ><i
                                    class="fas fa-circle-notch fa-spin mr-2"
                                ></i>
                                Memproses...</span
                            >
                        </button>

                        <Link
                            :href="route('login')"
                            class="text-center text-sm font-bold text-brand-red hover:underline"
                        >
                            <i class="fas fa-arrow-left mr-1"></i> Kembali ke
                            Masuk
                        </Link>
                    </div>
                </form>
            </div>

            <div
                class="mt-8 lg:absolute lg:bottom-8 text-center text-xs text-gray-400"
            >
                &copy; {{ new Date().getFullYear() }} TUJAGO (Tunggal Jaya Go).
                All rights reserved.
            </div>
        </div>
    </div>
</template>
