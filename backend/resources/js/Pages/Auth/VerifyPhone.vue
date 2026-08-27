<script setup>
import { Head, Link, useForm } from "@inertiajs/vue3";
import FrontendLayout from "@/Layouts/FrontendLayout.vue";
import { ref } from "vue";

defineOptions({ layout: FrontendLayout });

const props = defineProps({
    status: { type: String },
    debugOtp: { type: [String, Number], default: null },
    debugIdentifier: { type: String, default: null },
    needsPhone: { type: Boolean, default: false },
});

const otpSent = ref(!!(props.status || props.debugOtp));
const selectedMethod = ref("whatsapp");
const otpDigits = ref(['', '', '', '', '', '']);
const otpRefs = ref([]);

const sendForm = useForm({ method: "whatsapp", phone: "" });
const verifyForm = useForm({ otp: "", method: "whatsapp" });

const setMethod = (method) => {
    selectedMethod.value = method;
    sendForm.method = method;
    verifyForm.method = method;
};

const sendOtp = () => {
    sendForm.post(route("verification.phone.send"), {
        preserveScroll: true,
        onSuccess: () => { otpSent.value = true; },
    });
};

const onOtpInput = (index, event) => {
    const val = event.target.value;
    // Only allow single digit
    const digit = val.replace(/\D/g, '').slice(0, 1);
    otpDigits.value[index] = digit;
    
    // Auto focus next
    if (digit && index < 5) {
        otpRefs.value[index + 1]?.focus();
    }
    
    // Update form otp
    verifyForm.otp = otpDigits.value.join('');
};

const onOtpKeydown = (index, event) => {
    if (event.key === 'Backspace' && !otpDigits.value[index] && index > 0) {
        otpRefs.value[index - 1]?.focus();
    }
    if (event.key === 'ArrowLeft' && index > 0) {
        otpRefs.value[index - 1]?.focus();
    }
    if (event.key === 'ArrowRight' && index < 5) {
        otpRefs.value[index + 1]?.focus();
    }
};

const onOtpPaste = (event) => {
    const paste = event.clipboardData.getData('text').replace(/\D/g, '').slice(0, 6);
    if (!paste) return;
    event.preventDefault();
    paste.split('').forEach((d, i) => {
        if (i < 6) otpDigits.value[i] = d;
    });
    verifyForm.otp = otpDigits.value.join('');
    const nextIndex = Math.min(paste.length, 5);
    otpRefs.value[nextIndex]?.focus();
};

const submit = () => {
    if (verifyForm.otp.length < 6) return;
    verifyForm.post(route("verification.phone.verify"), {
        onFinish: () => {
            verifyForm.reset("otp");
            otpDigits.value = ['', '', '', '', '', ''];
        },
    });
};

const resendOtp = () => {
    sendForm.post(route("verification.phone.resend"), {
        preserveScroll: true,
        onSuccess: () => { otpSent.value = true; },
    });
};
</script>

<template>
    <Head title="Verifikasi Akun" />

    <div class="min-h-screen bg-[#fcf9f8] flex flex-col justify-center items-center p-4 sm:p-8 pt-32">
        <div class="w-full max-w-md">
            <!-- Logo / Header -->
            <div class="text-center mb-8">
                <div class="w-16 h-16 rounded-2xl bg-[#10207a]/10 flex items-center justify-center mx-auto mb-5">
                    <i class="fas fa-shield-alt text-2xl text-[#10207a]"></i>
                </div>
                <h1 class="font-unbounded font-black text-2xl text-[#1c1b1b]">Verifikasi Akun</h1>
                <p class="text-[#454652] text-sm mt-1">Pilih metode verifikasi untuk mengirimkan kode OTP.</p>
            </div>

            <!-- Method Tabs -->
            <div class="flex p-1 bg-white border border-[#ebe7e7] rounded-[12px] mb-6 shadow-sm">
                <button @click="setMethod('whatsapp')"
                    class="flex-1 py-3 rounded-[10px] text-xs font-bold uppercase tracking-wider transition-all"
                    :class="selectedMethod === 'whatsapp' ? 'bg-[#10207a] text-white shadow-sm' : 'text-[#454652] hover:text-[#1c1b1b]'">
                    <i class="fab fa-whatsapp mr-1.5"></i> WhatsApp
                </button>
                <button @click="setMethod('email')"
                    class="flex-1 py-3 rounded-[10px] text-xs font-bold uppercase tracking-wider transition-all"
                    :class="selectedMethod === 'email' ? 'bg-[#10207a] text-white shadow-sm' : 'text-[#454652] hover:text-[#1c1b1b]'">
                    <i class="fas fa-envelope mr-1.5"></i> Email
                </button>
            </div>

            <!-- Status Message -->
            <div v-if="status"
                class="p-4 rounded-[10px] bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm font-semibold flex items-center gap-2.5 mb-4">
                <i class="fas fa-check-circle"></i> <span>{{ status }}</span>
            </div>

            <!-- Debug OTP -->
            <div v-if="debugOtp"
                class="p-4 rounded-[10px] bg-amber-50 border border-amber-200 text-amber-800 text-sm mb-4">
                <div class="flex items-center gap-2 mb-1">
                    <i class="fas fa-bug text-xs"></i>
                    <span class="text-[10px] font-bold uppercase tracking-wider">DEBUG — OTP</span>
                </div>
                <p class="font-bold text-lg tracking-[0.3em] font-mono">{{ debugOtp }}</p>
                <p v-if="debugIdentifier" class="text-xs text-amber-600 mt-1">Tujuan: {{ debugIdentifier }}</p>
            </div>

            <!-- Error Message -->
            <div v-if="sendForm.errors.phone || sendForm.errors.otp || sendForm.errors.method"
                class="p-4 rounded-[10px] bg-red-50 border border-red-200 text-red-700 text-sm font-semibold flex items-start gap-2.5 mb-4">
                <i class="fas fa-exclamation-circle mt-0.5"></i>
                <span>{{ sendForm.errors.phone || sendForm.errors.otp || sendForm.errors.method }}</span>
            </div>

            <!-- STEP 1: Send OTP -->
            <div v-if="!otpSent" class="space-y-5">
                <div v-if="needsPhone && selectedMethod === 'whatsapp'">
                    <label class="text-xs font-bold text-[#454652] mb-1.5 block">Nomor WhatsApp</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-400">
                            <i class="fab fa-whatsapp text-sm"></i>
                        </div>
                        <input id="phone" type="tel" v-model="sendForm.phone" placeholder="Contoh: 08123456789"
                            class="w-full pl-10 pr-4 py-3.5 bg-white border border-[#e5e2e1] focus:border-[#10207a] focus:ring-0 rounded-[10px] text-[#1c1b1b] text-sm outline-none transition-all placeholder:text-gray-400" />
                    </div>
                </div>
                <p class="text-sm text-[#454652] text-center">
                    Klik tombol untuk mengirim kode OTP ke
                    <strong>{{ selectedMethod === 'whatsapp' ? 'WhatsApp' : 'Email' }}</strong> Anda.
                </p>
                <button @click="sendOtp" :disabled="sendForm.processing"
                    class="w-full py-4 bg-[#10207a] text-white rounded-[10px] font-bold text-sm hover:bg-[#0c185e] transition-all shadow-sm disabled:opacity-50 flex items-center justify-center gap-2">
                    <span v-if="!sendForm.processing"><i class="fas fa-paper-plane"></i> Kirim Kode OTP</span>
                    <span v-else><i class="fas fa-circle-notch fa-spin"></i> Mengirim...</span>
                </button>
            </div>

            <!-- STEP 2: Verify OTP -->
            <form v-if="otpSent" @submit.prevent="submit" class="space-y-5">
                <div>
                    <label class="text-xs font-bold text-[#454652] mb-3 block text-center">Masukkan kode OTP 6 digit</label>
                    <div class="flex gap-2.5 justify-center" @paste="onOtpPaste">
                        <input v-for="(_, i) in 6" :key="i"
                            :ref="el => { if (el) otpRefs[i] = el; }"
                            :value="otpDigits[i]"
                            @input="onOtpInput(i, $event)"
                            @keydown="onOtpKeydown(i, $event)"
                            type="text" inputmode="numeric" maxlength="1"
                            class="w-12 h-14 sm:w-14 sm:h-16 text-center text-xl font-bold bg-white border-2 rounded-[10px] outline-none transition-all"
                            :class="otpDigits[i] ? 'border-[#10207a] shadow-sm' : 'border-[#e5e2e1] focus:border-[#10207a]'"
                            autocomplete="off" />
                    </div>
                    <p v-if="verifyForm.errors.otp" class="mt-2 text-xs font-semibold text-red-600 text-center">{{ verifyForm.errors.otp }}</p>
                </div>
                <button type="submit" :disabled="verifyForm.processing || verifyForm.otp.length < 6"
                    class="w-full py-4 bg-[#10207a] text-white rounded-[10px] font-bold text-sm hover:bg-[#0c185e] transition-all shadow-sm disabled:opacity-40 flex items-center justify-center gap-2">
                    <span v-if="!verifyForm.processing"><i class="fas fa-check-circle"></i> Verifikasi</span>
                    <span v-else><i class="fas fa-circle-notch fa-spin"></i> Memproses...</span>
                </button>
                <div class="text-center">
                    <button type="button" @click="resendOtp" :disabled="sendForm.processing"
                        class="text-xs font-semibold text-[#454652] hover:text-[#10207a] transition-colors">
                        <i class="fas fa-redo-alt mr-1"></i> Kirim Ulang OTP
                    </button>
                    <p v-if="sendForm.errors.otp" class="mt-1.5 text-xs text-red-600">{{ sendForm.errors.otp }}</p>
                </div>
            </form>

            <!-- Logout -->
            <div class="text-center mt-10">
                <Link :href="route('logout')" method="post" as="button"
                    class="text-xs font-semibold text-[#454652] hover:text-red-600 transition-colors">
                    Keluar Akun
                </Link>
            </div>

            <p class="text-center text-[10px] text-[#454652] font-semibold uppercase tracking-wider mt-8 pb-4">
                &copy; {{ new Date().getFullYear() }} Tunggal Jaya Transport
            </p>
        </div>
    </div>
</template>
