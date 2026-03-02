<script setup>
import { Head, useForm, Link } from "@inertiajs/vue3";
import FrontendLayout from "@/Layouts/FrontendLayout.vue";
import { useMagnetic } from "@/Composables/useMagnetic";
import { ref } from "vue";

defineOptions({ layout: FrontendLayout });

defineProps({
    status: {
        type: String,
    },
});

const submitBtn = ref(null);
useMagnetic(submitBtn);

const form = useForm({
    email: "",
});

const submit = () => {
    form.post(route("password.email"));
};
</script>

<template>
    <Head title="Lupa Kata Sandi - TUJAGO (Tunggal Jaya Go)" />

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
                    class="absolute inset-0 bg-gradient-to-r from-white dark:from-black via-white/90 dark:via-black/80 to-brand-red/20"
                ></div>
            </div>

            <div class="relative z-10 text-center px-12">
                <div class="mb-12 flex justify-center">
                    <div
                        class="w-32 h-32 rounded-full bg-gray-100 dark:bg-white/5 backdrop-blur-xl flex items-center justify-center border border-gray-300 dark:border-white/10 shadow-[0_0_50px_rgba(220,38,38,0.2)]"
                    >
                        <i class="fas fa-lock-open text-5xl text-brand-red"></i>
                    </div>
                </div>
                <h2
                    class="text-4xl font-black text-gray-900 dark:text-white mb-6 font-unbounded tracking-tighter uppercase"
                >
                    PULIHKAN <span class="text-brand-red">AKUN</span>
                </h2>
                <p
                    class="text-lg text-gray-600 dark:text-gray-400 leading-relaxed max-w-md mx-auto font-manrope font-medium"
                >
                    Jangan khawatir jika lupa kata sandi. Kami akan membantu
                    Anda mengatur ulang kata sandi dengan cepat dan aman.
                </p>
            </div>
        </div>

        <!-- Right Side: Form -->
        <div
            class="flex flex-col justify-center items-center p-6 sm:p-12 pt-32 lg:pt-32 relative overflow-hidden bg-white dark:bg-gray-950"
        >
            <!-- Decorative Background Element -->
            <div
                class="absolute -top-24 -left-24 w-96 h-96 bg-brand-red/5 dark:bg-brand-red/10 rounded-full blur-[100px]"
            ></div>

            <div class="w-full max-w-md space-y-8 relative z-10">
                <!-- Mobile Logo -->
                <div class="lg:hidden text-center mb-12">
                    <div
                        class="inline-flex w-20 h-20 rounded-full bg-gray-100 dark:bg-white/5 items-center justify-center mb-6 border border-gray-300 dark:border-white/10 shadow-xl"
                    >
                        <i class="fas fa-key text-3xl text-brand-red"></i>
                    </div>
                    <h2
                        class="text-3xl font-black text-gray-900 dark:text-white font-unbounded tracking-tighter"
                    >
                        LUPA <span class="text-brand-red">SANDI?</span>
                    </h2>
                </div>

                <div class="text-center lg:text-left">
                    <h2
                        class="hidden lg:block text-4xl font-black text-gray-900 dark:text-white mb-3 font-unbounded tracking-tighter"
                    >
                        LUPA <span class="text-brand-red">SANDI?</span>
                    </h2>
                    <p
                        class="text-gray-600 dark:text-gray-400 font-manrope font-medium tracking-wide"
                    >
                        Masukkan email Anda untuk menerima tautan reset.
                    </p>
                </div>

                <div
                    v-if="status"
                    class="p-4 rounded-xl bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-500/20 text-emerald-700 dark:text-emerald-400 text-sm font-bold font-manrope"
                >
                    {{ status }}
                </div>

                <form @submit.prevent="submit" class="mt-8 space-y-6">
                    <div>
                        <label
                            for="email"
                            class="block text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-[0.2em] font-unbounded mb-3 ml-1"
                            >Alamat Email</label
                        >
                        <div class="relative group">
                            <div
                                class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-gray-400 dark:text-gray-500 group-focus-within:text-brand-red transition-colors"
                            >
                                <i class="fas fa-envelope"></i>
                            </div>
                            <input
                                id="email"
                                type="email"
                                v-model="form.email"
                                required
                                autofocus
                                autocomplete="username"
                                class="w-full bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-2xl py-4 pl-12 pr-5 text-gray-900 dark:text-white font-manrope focus:ring-2 focus:ring-brand-red/20 focus:border-brand-red transition-all placeholder:text-gray-400 dark:placeholder:text-gray-700"
                                placeholder="nama@email.com"
                            />
                        </div>
                        <p
                            class="mt-2 text-xs font-bold text-brand-red font-manrope"
                            v-if="form.errors.email"
                        >
                            {{ form.errors.email }}
                        </p>
                    </div>

                    <div class="flex flex-col space-y-6 pt-2">
                        <button
                            ref="submitBtn"
                            type="submit"
                            :disabled="form.processing"
                            class="w-full py-5 bg-brand-red text-white rounded-2xl font-black font-unbounded text-xs uppercase tracking-[0.2em] shadow-[0_10px_30px_rgba(220,38,38,0.3)] hover:bg-red-700 dark:hover:bg-red-700 transition-all active:scale-[0.98] disabled:opacity-50"
                        >
                            <span v-if="!form.processing"
                                >KIRIM TAUTAN RESET</span
                            >
                            <span
                                v-else
                                class="flex items-center justify-center"
                            >
                                <i class="fas fa-circle-notch fa-spin mr-3"></i>
                                MEMPROSES...
                            </span>
                        </button>

                        <Link
                            :href="route('login')"
                            class="text-center text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest hover:text-brand-red dark:hover:text-brand-red transition-colors font-unbounded"
                        >
                            <i class="fas fa-arrow-left mr-2"></i> Kembali ke
                            Masuk
                        </Link>
                    </div>
                </form>
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
