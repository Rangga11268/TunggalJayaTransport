<script setup>
import { useForm } from "@inertiajs/vue3";
import { ref } from "vue";

const props = defineProps({ show: Boolean });
const emit = defineEmits(["close", "switchToLogin"]);
const showPassword = ref(false);
const showConfirmPassword = ref(false);

const form = useForm({
    name: "", email: "", phone: "", password: "", password_confirmation: "", terms: true,
});

const submit = () => {
    form.post(route("register"), {
        preserveScroll: true,
        onSuccess: () => { form.reset(); emit("close"); },
        onFinish: () => form.reset("password", "password_confirmation"),
    });
};
</script>

<template>
    <teleport to="body">
        <transition enter-active-class="transition duration-300 ease-out" enter-from-class="opacity-0" enter-to-class="opacity-100"
            leave-active-class="transition duration-200 ease-in" leave-from-class="opacity-100" leave-to-class="opacity-0">
            <div v-if="show" class="fixed inset-0 z-[100] flex items-center justify-center p-4">
                <div class="fixed inset-0 bg-black/30 backdrop-blur-sm" @click="emit('close')"></div>
                <transition enter-active-class="transition duration-300 ease-out" enter-from-class="opacity-0 translate-y-8 scale-95"
                    enter-to-class="opacity-100 translate-y-0 scale-100"
                    leave-active-class="transition duration-200 ease-in" leave-from-class="opacity-100 translate-y-0 scale-100"
                    leave-to-class="opacity-0 translate-y-8 scale-95">
                    <div v-if="show" class="relative bg-white w-full max-w-md max-h-[90vh] overflow-y-auto rounded-[12px] shadow-xl border border-[#ebe7e7]">
                        <button @click="emit('close')" class="absolute top-4 right-4 w-8 h-8 flex items-center justify-center rounded-full bg-[#f6f3f2] text-gray-500 hover:text-gray-700 transition-colors z-20">
                            <i class="fas fa-times text-sm"></i>
                        </button>
                        <div class="p-6 md:p-8">
                            <div class="text-center mb-6">
                                <div class="w-12 h-12 mx-auto bg-[#10207a]/10 rounded-xl flex items-center justify-center mb-3">
                                    <i class="fas fa-user-plus text-2xl text-[#10207a]"></i>
                                </div>
                                <h2 class="text-xl font-bold text-[#1c1b1b]">Daftar Akun Baru</h2>
                                <p class="text-sm text-[#454652] mt-1">Buat akun untuk kemudahan pemesanan.</p>
                            </div>

                            <form @submit.prevent="submit" class="space-y-4">
                                <div>
                                    <label class="text-xs font-bold text-[#454652] mb-1.5 block">Nama Lengkap</label>
                                    <input type="text" v-model="form.name" required placeholder="Nama Anda"
                                        class="w-full px-4 py-3 bg-[#f6f3f2] border border-[#e5e2e1] focus:border-[#10207a] focus:bg-white focus:ring-0 rounded-[10px] text-sm text-[#1c1b1b] outline-none transition-all" />
                                    <p v-if="form.errors.name" class="mt-1 text-xs text-red-600 font-semibold">{{ form.errors.name }}</p>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    <div>
                                        <label class="text-xs font-bold text-[#454652] mb-1.5 block">Email</label>
                                        <input type="email" v-model="form.email" required placeholder="name@email.com"
                                            class="w-full px-4 py-3 bg-[#f6f3f2] border border-[#e5e2e1] focus:border-[#10207a] focus:bg-white focus:ring-0 rounded-[10px] text-sm text-[#1c1b1b] outline-none transition-all" />
                                        <p v-if="form.errors.email" class="mt-1 text-xs text-red-600 font-semibold">{{ form.errors.email }}</p>
                                    </div>
                                    <div>
                                        <label class="text-xs font-bold text-[#454652] mb-1.5 block">Telepon</label>
                                        <input type="text" v-model="form.phone" required placeholder="08xxxxxxxx"
                                            class="w-full px-4 py-3 bg-[#f6f3f2] border border-[#e5e2e1] focus:border-[#10207a] focus:bg-white focus:ring-0 rounded-[10px] text-sm text-[#1c1b1b] outline-none transition-all" />
                                        <p v-if="form.errors.phone" class="mt-1 text-xs text-red-600 font-semibold">{{ form.errors.phone }}</p>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    <div>
                                        <label class="text-xs font-bold text-[#454652] mb-1.5 block">Kata Sandi</label>
                                        <div class="relative">
                                            <input :type="showPassword ? 'text' : 'password'" v-model="form.password" required placeholder="••••••••"
                                                class="w-full px-4 py-3 bg-[#f6f3f2] border border-[#e5e2e1] focus:border-[#10207a] focus:bg-white focus:ring-0 rounded-[10px] text-sm text-[#1c1b1b] outline-none transition-all pr-10" />
                                            <button type="button" @click="showPassword = !showPassword" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400">
                                                <i :class="['fas text-sm', showPassword ? 'fa-eye-slash' : 'fa-eye']"></i>
                                            </button>
                                        </div>
                                        <p v-if="form.errors.password" class="mt-1 text-xs text-red-600 font-semibold">{{ form.errors.password }}</p>
                                    </div>
                                    <div>
                                        <label class="text-xs font-bold text-[#454652] mb-1.5 block">Konfirmasi</label>
                                        <div class="relative">
                                            <input :type="showConfirmPassword ? 'text' : 'password'" v-model="form.password_confirmation" required placeholder="••••••••"
                                                class="w-full px-4 py-3 bg-[#f6f3f2] border border-[#e5e2e1] focus:border-[#10207a] focus:bg-white focus:ring-0 rounded-[10px] text-sm text-[#1c1b1b] outline-none transition-all pr-10" />
                                            <button type="button" @click="showConfirmPassword = !showConfirmPassword" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400">
                                                <i :class="['fas text-sm', showConfirmPassword ? 'fa-eye-slash' : 'fa-eye']"></i>
                                            </button>
                                        </div>
                                        <p v-if="form.errors.password_confirmation" class="mt-1 text-xs text-red-600 font-semibold">{{ form.errors.password_confirmation }}</p>
                                    </div>
                                </div>

                                <button type="submit" :disabled="form.processing"
                                    class="w-full py-3.5 bg-[#10207a] text-white rounded-[10px] font-bold text-sm hover:bg-[#0c185e] transition-all shadow-sm disabled:opacity-50">
                                    <span v-if="!form.processing">Daftar</span>
                                    <span v-else class="flex items-center justify-center gap-2"><i class="fas fa-circle-notch fa-spin"></i> Memproses...</span>
                                </button>
                            </form>

                            <div class="relative my-5">
                                <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-[#ebe7e7]"></div></div>
                                <div class="relative flex justify-center"><span class="bg-white px-3 text-[10px] text-[#454652] uppercase tracking-wider">Atau</span></div>
                            </div>

                            <a href="/auth/google" class="flex items-center justify-center py-3 w-full rounded-[10px] border border-[#e5e2e1] hover:bg-[#f6f3f2] transition-colors text-sm text-[#454652] font-semibold gap-2">
                                <i class="fab fa-google text-[#DB4437]"></i> Lanjutkan dengan Google
                            </a>

                            <p class="text-center text-xs text-[#454652] mt-5">
                                Sudah punya akun?
                                <button @click="emit('switchToLogin')" class="font-semibold text-[#10207a] hover:underline ml-1">Masuk</button>
                            </p>
                        </div>
                    </div>
                </transition>
            </div>
        </transition>
    </teleport>
</template>
