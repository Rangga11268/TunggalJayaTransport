<script setup>
import { useForm } from "@inertiajs/vue3";
import { ref } from "vue";

const passwordInput = ref(null);
const currentPasswordInput = ref(null);

const form = useForm({
    current_password: "",
    password: "",
    password_confirmation: "",
});

const updatePassword = () => {
    form.put(route("password.update"), {
        preserveScroll: true,
        onSuccess: () => form.reset(),
        onError: () => {
            if (form.errors.password) { form.reset("password", "password_confirmation"); passwordInput.value.focus(); }
            if (form.errors.current_password) { form.reset("current_password"); currentPasswordInput.value.focus(); }
        },
    });
};
</script>

<template>
    <section>
        <h2 class="font-bold text-[18px] text-[#1c1b1b] mb-1">Keamanan Akun</h2>
        <p class="text-[13px] text-[#454652] mb-6">Gunakan kata sandi yang kuat untuk keamanan akun Anda.</p>

        <form @submit.prevent="updatePassword" class="space-y-5">
            <div>
                <label class="text-xs font-bold text-[#454652] mb-1.5 block">Kata Sandi Saat Ini</label>
                <input ref="currentPasswordInput" v-model="form.current_password" type="password" autocomplete="current-password"
                    class="w-full px-4 py-3 bg-[#f6f3f2] border border-[#e5e2e1] focus:border-[#10207a] focus:bg-white focus:ring-0 rounded-[10px] text-[#1c1b1b] text-sm outline-none transition-all" />
                <p v-if="form.errors.current_password" class="mt-1 text-xs text-red-600">{{ form.errors.current_password }}</p>
            </div>
            <div>
                <label class="text-xs font-bold text-[#454652] mb-1.5 block">Kata Sandi Baru</label>
                <input ref="passwordInput" v-model="form.password" type="password" autocomplete="new-password"
                    class="w-full px-4 py-3 bg-[#f6f3f2] border border-[#e5e2e1] focus:border-[#10207a] focus:bg-white focus:ring-0 rounded-[10px] text-[#1c1b1b] text-sm outline-none transition-all" />
                <p v-if="form.errors.password" class="mt-1 text-xs text-red-600">{{ form.errors.password }}</p>
            </div>
            <div>
                <label class="text-xs font-bold text-[#454652] mb-1.5 block">Konfirmasi Kata Sandi Baru</label>
                <input v-model="form.password_confirmation" type="password" autocomplete="new-password"
                    class="w-full px-4 py-3 bg-[#f6f3f2] border border-[#e5e2e1] focus:border-[#10207a] focus:bg-white focus:ring-0 rounded-[10px] text-[#1c1b1b] text-sm outline-none transition-all" />
                <p v-if="form.errors.password_confirmation" class="mt-1 text-xs text-red-600">{{ form.errors.password_confirmation }}</p>
            </div>

            <div class="flex items-center gap-4 pt-2">
                <button type="submit" :disabled="form.processing"
                    class="px-6 py-3 bg-[#10207a] text-white rounded-[10px] font-bold text-[13px] hover:bg-[#0c185e] transition-all shadow-sm disabled:opacity-50">
                    Perbarui
                </button>
                <Transition enter-active-class="transition" enter-from-class="opacity-0" leave-active-class="transition" leave-to-class="opacity-0">
                    <p v-if="form.recentlySuccessful" class="text-xs text-emerald-600 font-semibold uppercase">Berhasil!</p>
                </Transition>
            </div>
        </form>
    </section>
</template>
