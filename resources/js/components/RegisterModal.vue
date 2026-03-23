<script setup>
import { useForm } from "@inertiajs/vue3";
import { ref } from "vue";

const props = defineProps({
    show: Boolean,
});

const emit = defineEmits(["close", "switchToLogin"]);

const showPassword = ref(false);
const showConfirmPassword = ref(false);

const form = useForm({
    name: "",
    email: "",
    phone: "",
    password: "",
    password_confirmation: "",
    terms: true,
});

const submit = () => {
    form.post(route("register"), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
            emit("close");
        },
        onFinish: () => form.reset("password", "password_confirmation"),
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
                <div v-if="show" class="relative bg-white dark:bg-[#111] w-full max-w-md max-h-[90vh] overflow-y-auto rounded-[2rem] shadow-2xl border border-gray-100 dark:border-white/10 custom-scrollbar">
                    
                    <!-- Close Button -->
                    <button @click="emit('close')" class="absolute top-4 right-4 w-10 h-10 flex items-center justify-center rounded-full bg-gray-50 dark:bg-white/5 text-gray-500 hover:text-brand-red transition-colors z-20">
                        <i class="fas fa-times"></i>
                    </button>

                    <!-- Decorative Blob -->
                    <div class="absolute -bottom-24 -left-24 w-64 h-64 bg-brand-red/10 rounded-full blur-[80px] pointer-events-none"></div>

                    <div class="p-8 relative z-10">
                        <div class="text-center mb-8">
                            <h2 class="text-2xl font-black text-gray-900 dark:text-white font-unbounded tracking-tighter">DAFTAR <span class="text-brand-red">BARU</span></h2>
                            <p class="text-sm text-gray-500 dark:text-gray-400 font-manrope mt-2">Buat akun untuk kemudahan pemesanan.</p>
                        </div>

                        <form @submit.prevent="submit" class="space-y-4">
                            <!-- Name -->
                            <div>
                                <label class="block text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-[0.2em] font-unbounded mb-1.5 ml-1">Nama Lengkap</label>
                                <div class="relative group">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none group-focus-within:text-brand-red transition-colors">
                                        <i class="fas fa-user text-gray-400"></i>
                                    </div>
                                    <input type="text" v-model="form.name" required class="w-full bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl py-3 pl-10 pr-4 text-sm text-gray-900 dark:text-white font-manrope focus:ring-2 focus:ring-brand-red/20 focus:border-brand-red transition-all" placeholder="John Doe" />
                                </div>
                                <p class="mt-1 text-xs font-bold text-brand-red" v-if="form.errors.name">{{ form.errors.name }}</p>
                            </div>

                            <!-- Email -->
                            <div>
                                <label class="block text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-[0.2em] font-unbounded mb-1.5 ml-1">Email</label>
                                <div class="relative group">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none group-focus-within:text-brand-red transition-colors">
                                        <i class="fas fa-envelope text-gray-400"></i>
                                    </div>
                                    <input type="email" v-model="form.email" required class="w-full bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl py-3 pl-10 pr-4 text-sm text-gray-900 dark:text-white font-manrope focus:ring-2 focus:ring-brand-red/20 focus:border-brand-red transition-all" placeholder="name@example.com" />
                                </div>
                                <p class="mt-1 text-xs font-bold text-brand-red" v-if="form.errors.email">{{ form.errors.email }}</p>
                            </div>

                            <!-- Phone -->
                            <div>
                                <label class="block text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-[0.2em] font-unbounded mb-1.5 ml-1">Nomor Telepon</label>
                                <div class="relative group">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none group-focus-within:text-brand-red transition-colors">
                                        <i class="fas fa-phone text-gray-400"></i>
                                    </div>
                                    <input type="text" v-model="form.phone" required class="w-full bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl py-3 pl-10 pr-4 text-sm text-gray-900 dark:text-white font-manrope focus:ring-2 focus:ring-brand-red/20 focus:border-brand-red transition-all" placeholder="08xxxxxxxx" />
                                </div>
                                <p class="mt-1 text-xs font-bold text-brand-red" v-if="form.errors.phone">{{ form.errors.phone }}</p>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <!-- Password -->
                                <div>
                                    <label class="block text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-[0.2em] font-unbounded mb-1.5 ml-1">Sandi</label>
                                    <div class="relative group">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none group-focus-within:text-brand-red transition-colors">
                                            <i class="fas fa-lock text-gray-400 text-xs"></i>
                                        </div>
                                        <input :type="showPassword ? 'text' : 'password'" v-model="form.password" required class="w-full bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl py-2.5 pl-8 pr-8 text-sm text-gray-900 dark:text-white font-manrope focus:ring-2 focus:ring-brand-red/20 focus:border-brand-red transition-all" placeholder="••••••••" />
                                        <button type="button" @click="showPassword = !showPassword" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-brand-red">
                                            <i :class="['fas text-xs', showPassword ? 'fa-eye-slash' : 'fa-eye']"></i>
                                        </button>
                                    </div>
                                    <p class="mt-1 text-xs font-bold text-brand-red" v-if="form.errors.password">{{ form.errors.password }}</p>
                                </div>

                                <!-- Confirm Password -->
                                <div>
                                    <label class="block text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-[0.2em] font-unbounded mb-1.5 ml-1">Konfirmasi</label>
                                    <div class="relative group">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none group-focus-within:text-brand-red transition-colors">
                                            <i class="fas fa-check-circle text-gray-400 text-xs"></i>
                                        </div>
                                        <input :type="showConfirmPassword ? 'text' : 'password'" v-model="form.password_confirmation" required class="w-full bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl py-2.5 pl-8 pr-8 text-sm text-gray-900 dark:text-white font-manrope focus:ring-2 focus:ring-brand-red/20 focus:border-brand-red transition-all" placeholder="••••••••" />
                                        <button type="button" @click="showConfirmPassword = !showConfirmPassword" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-brand-red">
                                            <i :class="['fas text-xs', showConfirmPassword ? 'fa-eye-slash' : 'fa-eye']"></i>
                                        </button>
                                    </div>
                                    <p class="mt-1 text-xs font-bold text-brand-red" v-if="form.errors.password_confirmation">{{ form.errors.password_confirmation }}</p>
                                </div>
                            </div>

                            <button type="submit" :disabled="form.processing" class="w-full py-3.5 mt-4 bg-brand-red text-white rounded-xl font-black font-unbounded text-xs uppercase tracking-[0.2em] shadow-lg shadow-brand-red/30 hover:scale-[1.02] active:scale-[0.98] transition-transform disabled:opacity-50">
                                <span v-if="!form.processing">DAFTAR SEKARANG</span>
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
                            <p class="text-xs text-gray-500 font-manrope">Sudah punya akun? <button @click="emit('switchToLogin')" class="font-black text-brand-red hover:underline font-unbounded ml-1 text-[10px] uppercase">MASUK SEKARANG</button></p>
                        </div>
                    </div>
                </div>
            </transition>
        </div>
    </transition>
</template>
