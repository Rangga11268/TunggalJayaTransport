<script setup>
import { computed } from "vue";
import { Link, useForm, usePage } from "@inertiajs/vue3";

defineProps({ mustVerifyEmail: Boolean, status: String });

const page = usePage();
const user = computed(() => page.props.auth?.user || { name: "", email: "" });

const form = useForm({ name: user.value.name, email: user.value.email });
</script>

<template>
    <section>
        <h2 class="font-bold text-[18px] text-[#1c1b1b] mb-1">Informasi Profil</h2>
        <p class="text-[13px] text-[#454652] mb-6">Perbarui informasi akun Anda.</p>

        <form @submit.prevent="form.patch(route('profile.update'))" class="space-y-5">
            <div>
                <label class="text-xs font-bold text-[#454652] mb-1.5 block">Nama Lengkap</label>
                <input type="text" v-model="form.name" required
                    class="w-full px-4 py-3 bg-[#f6f3f2] border border-[#e5e2e1] focus:border-[#10207a] focus:bg-white focus:ring-0 rounded-[10px] text-[#1c1b1b] text-sm outline-none transition-all" />
                <p v-if="form.errors.name" class="mt-1 text-xs text-red-600">{{ form.errors.name }}</p>
            </div>
            <div>
                <label class="text-xs font-bold text-[#454652] mb-1.5 block">Alamat Email</label>
                <input type="email" v-model="form.email" required
                    class="w-full px-4 py-3 bg-[#f6f3f2] border border-[#e5e2e1] focus:border-[#10207a] focus:bg-white focus:ring-0 rounded-[10px] text-[#1c1b1b] text-sm outline-none transition-all" />
                <p v-if="form.errors.email" class="mt-1 text-xs text-red-600">{{ form.errors.email }}</p>
            </div>

            <div v-if="mustVerifyEmail && user.email_verified_at === null"
                class="p-4 rounded-[10px] bg-amber-50 border border-amber-200 text-amber-700 text-sm">
                <p>Email belum diverifikasi. <Link :href="route('verification.send')" method="post" as="button" class="underline font-semibold hover:text-amber-800">Kirim ulang</Link>.</p>
                <p v-show="status === 'verification-link-sent'" class="mt-1 text-emerald-600 font-semibold">Tautan verifikasi telah dikirim.</p>
            </div>

            <div class="flex items-center gap-4 pt-2">
                <button type="submit" :disabled="form.processing"
                    class="px-6 py-3 bg-[#10207a] text-white rounded-[10px] font-bold text-[13px] hover:bg-[#0c185e] transition-all shadow-sm disabled:opacity-50">
                    Simpan
                </button>
                <Transition enter-active-class="transition" enter-from-class="opacity-0" leave-active-class="transition" leave-to-class="opacity-0">
                    <p v-if="form.recentlySuccessful" class="text-xs text-emerald-600 font-semibold uppercase">Tersimpan!</p>
                </Transition>
            </div>
        </form>
    </section>
</template>
