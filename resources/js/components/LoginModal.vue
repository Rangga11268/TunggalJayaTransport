<script setup>
import { useForm, Link } from "@inertiajs/vue3";
import { ref } from "vue";

const props = defineProps({
    show: Boolean,
});

const emit = defineEmits(["close", "switchToRegister"]);

const showPassword = ref(false);

const form = useForm({
    login: "",
    password: "",
    remember: false,
});

const submit = () => {
    form.post(route("login"), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
            emit("close");
        },
        onFinish: () => form.reset("password"),
    });
};
</script>

<template>
    <transition
        enter-active-class="transition duration-300 ease-out"
        enter-from-class="opacity-0"
        enter-to-class="opacity-100"
        leave-active-class="transition duration-200 ease-in"
        leave-from-class="opacity-100"
        leave-to-class="opacity-0"
    >
        <div v-if="show" class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-0">
            <!-- Modal Backdrop -->
            <div class="fixed inset-0 bg-gray-900/40 dark:bg-black/60 backdrop-blur-sm" @click="emit('close')"></div>

            <!-- Modal Content -->
            <transition
                enter-active-class="transition duration-300 ease-out"
                enter-from-class="opacity-0 translate-y-8 scale-95"
                enter-to-class="opacity-100 translate-y-0 scale-100"
                leave-active-class="transition duration-200 ease-in"
                leave-from-class="opacity-100 translate-y-0 scale-100"
                leave-to-class="opacity-0 translate-y-8 scale-95"
            >
                <div v-if="show" class="relative bg-white dark:bg-[#111] w-full max-w-md rounded-[2rem] shadow-2xl overflow-hidden border border-gray-100 dark:border-white/10">
                    
                    <!-- Close Button -->
                    <button @click="emit('close')" class="absolute top-4 right-4 w-10 h-10 flex items-center justify-center rounded-full bg-gray-50 dark:bg-white/5 text-gray-500 hover:text-brand-red transition-colors z-20">
                        <i class="fas fa-times"></i>
                    </button>

                    <!-- Decorative Blob -->
                    <div class="absolute -top-24 -right-24 w-64 h-64 bg-brand-red/10 rounded-full blur-[80px] pointer-events-none"></div>

                    <div class="p-8 relative z-10">
                        <div class="text-center mb-8">
                            <div class="w-16 h-16 mx-auto bg-brand-red/10 rounded-2xl flex items-center justify-center mb-4">
                                <i class="fas fa-user-circle text-3xl text-brand-red"></i>
                            </div>
                            <h2 class="text-2xl font-black text-gray-900 dark:text-white font-unbounded tracking-tighter">MASUK <span class="text-brand-red">AKUN</span></h2>
                            <p class="text-sm text-gray-500 dark:text-gray-400 font-manrope mt-2">Masuk untuk melanjutkan pesanan Anda.</p>
                        </div>

                        <form @submit.prevent="submit" class="space-y-5">
                            <div>
                                <label class="block text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-[0.2em] font-unbounded mb-2 ml-1">Email / Phone</label>
                                <div class="relative group">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none group-focus-within:text-brand-red transition-colors">
                                        <i class="fas fa-envelope text-gray-400"></i>
                                    </div>
                                    <input type="text" v-model="form.login" required autofocus class="w-full bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl py-3 pl-10 pr-4 text-sm text-gray-900 dark:text-white font-manrope focus:ring-2 focus:ring-brand-red/20 focus:border-brand-red transition-all" placeholder="nama@email.com" />
                                </div>
                                <p class="mt-1 text-xs font-bold text-brand-red font-manrope" v-if="form.errors.login">{{ form.errors.login }}</p>
                            </div>

                            <div>
                                <div class="flex justify-between items-center mb-2 ml-1">
                                    <label class="block text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-[0.2em] font-unbounded">Kata Sandi</label>
                                    <Link :href="route('password.request')" class="text-[10px] font-black text-brand-red uppercase tracking-wider hover:text-red-500 transition-colors font-unbounded">Lupa?</Link>
                                </div>
                                <div class="relative group">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none group-focus-within:text-brand-red transition-colors">
                                        <i class="fas fa-lock text-gray-400"></i>
                                    </div>
                                    <input :type="showPassword ? 'text' : 'password'" v-model="form.password" required class="w-full bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl py-3 pl-10 pr-10 text-sm text-gray-900 dark:text-white font-manrope focus:ring-2 focus:ring-brand-red/20 focus:border-brand-red transition-all" placeholder="••••••••" />
                                    <button type="button" @click="showPassword = !showPassword" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-brand-red transition-colors">
                                        <i :class="['fas', showPassword ? 'fa-eye-slash' : 'fa-eye']"></i>
                                    </button>
                                </div>
                                <p class="mt-1 text-xs font-bold text-brand-red font-manrope" v-if="form.errors.password">{{ form.errors.password }}</p>
                            </div>

                            <div class="flex items-center">
                                <label class="relative flex items-center cursor-pointer group">
                                    <input type="checkbox" v-model="form.remember" class="peer sr-only" />
                                    <div class="h-4 w-4 rounded border-2 border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 transition-all peer-checked:bg-brand-red peer-checked:border-brand-red"></div>
                                    <i class="fas fa-check absolute left-[2px] text-[8px] text-white opacity-0 peer-checked:opacity-100 transition-opacity"></i>
                                    <span class="ml-2 text-xs font-semibold text-gray-500 dark:text-gray-400 font-manrope">Ingat Saya</span>
                                </label>
                            </div>

                            <button type="submit" :disabled="form.processing" class="w-full py-3.5 mt-2 bg-brand-red text-white rounded-xl font-black font-unbounded text-xs uppercase tracking-[0.2em] shadow-lg shadow-brand-red/30 hover:scale-[1.02] active:scale-[0.98] transition-transform disabled:opacity-50">
                                <span v-if="!form.processing">MASUK SEKARANG</span>
                                <span v-else class="flex items-center justify-center"><i class="fas fa-circle-notch fa-spin mr-2"></i> MEMPROSES...</span>
                            </button>
                        </form>

                        <div class="relative my-6">
                            <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-gray-100 dark:border-white/5"></div></div>
                            <div class="relative flex justify-center text-[10px]"><span class="bg-white dark:bg-[#111] px-4 text-gray-400 font-unbounded tracking-wider uppercase">ATAU</span></div>
                        </div>

                        <a href="/auth/google" class="flex items-center justify-center py-3 w-full rounded-xl border border-gray-200 dark:border-white/10 hover:bg-gray-50 dark:hover:bg-white/5 transition-colors group">
                            <img src="https://www.svgrepo.com/show/475656/google-color.svg" class="h-4 w-4 mr-3 group-hover:scale-110 transition-transform" alt="Google" />
                            <span class="text-xs font-black text-gray-700 dark:text-gray-300 font-unbounded uppercase tracking-wider">Lanjutkan dengan Google</span>
                        </a>

                        <div class="text-center mt-6">
                            <p class="text-xs text-gray-500 font-manrope">Belum punya akun? <button @click="emit('switchToRegister')" class="font-black text-brand-red hover:underline font-unbounded ml-1 text-[10px] uppercase">DAFTAR SEKARANG</button></p>
                        </div>
                    </div>
                </div>
            </transition>
        </div>
    </transition>
</template>
