<script setup>
import { Head, useForm, Link } from "@inertiajs/vue3";
import FrontendLayout from "@/Layouts/FrontendLayout.vue";
import { ref } from "vue";

defineOptions({ layout: FrontendLayout });

defineProps({ status: { type: String } });

const form = useForm({ email: "" });
const submit = () => { form.post(route("password.email")); };
</script>

<template>
    <Head title="Lupa Kata Sandi" />

    <div class="min-h-screen bg-[#fcf9f8] flex flex-col justify-center items-center p-4 sm:p-8 pt-32">
        <div class="w-full max-w-md">
            <div class="text-center mb-8">
                <div class="w-16 h-16 rounded-2xl bg-[#10207a]/10 flex items-center justify-center mx-auto mb-5">
                    <i class="fas fa-lock-open text-2xl text-[#10207a]"></i>
                </div>
                <h1 class="font-unbounded font-black text-2xl text-[#1c1b1b]">Lupa Kata Sandi?</h1>
                <p class="text-[#454652] text-sm mt-1">Masukkan email Anda untuk menerima tautan reset.</p>
            </div>

            <div v-if="status" class="p-4 rounded-[10px] bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm font-semibold mb-4">
                {{ status }}
            </div>

            <form @submit.prevent="submit" class="space-y-5">
                <div>
                    <label class="text-xs font-bold text-[#454652] mb-1.5 block">Alamat Email</label>
                    <input id="email" type="email" v-model="form.email" required autofocus placeholder="nama@email.com"
                        class="w-full px-4 py-3.5 bg-white border border-[#e5e2e1] focus:border-[#10207a] focus:ring-0 rounded-[10px] text-[#1c1b1b] text-sm outline-none transition-all placeholder:text-gray-400" />
                    <p v-if="form.errors.email" class="mt-1.5 text-xs font-semibold text-red-600">{{ form.errors.email }}</p>
                </div>
                <button type="submit" :disabled="form.processing"
                    class="w-full py-4 bg-[#10207a] text-white rounded-[10px] font-bold text-sm hover:bg-[#0c185e] transition-all shadow-sm disabled:opacity-50 flex items-center justify-center gap-2">
                    <span v-if="!form.processing"><i class="fas fa-paper-plane"></i> Kirim Tautan Reset</span>
                    <span v-else><i class="fas fa-circle-notch fa-spin"></i> Memproses...</span>
                </button>
                <Link :href="route('login')"
                    class="block text-center text-xs font-semibold text-[#454652] hover:text-[#10207a] transition-colors">
                    <i class="fas fa-arrow-left mr-1"></i> Kembali ke Masuk
                </Link>
            </form>

            <p class="text-center text-[10px] text-[#454652] font-semibold uppercase tracking-wider mt-10 pb-4">
                &copy; {{ new Date().getFullYear() }} Tunggal Jaya Transport
            </p>
        </div>
    </div>
</template>
