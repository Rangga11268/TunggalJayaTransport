<script setup>
import InputError from "@/Components/InputError.vue";
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
            if (form.errors.password) {
                form.reset("password", "password_confirmation");
                passwordInput.value.focus();
            }
            if (form.errors.current_password) {
                form.reset("current_password");
                currentPasswordInput.value.focus();
            }
        },
    });
};
</script>

<template>
    <section>
        <header class="mb-8">
            <h2
                class="text-xl font-black text-gray-900 dark:text-white font-unbounded"
            >
                Keamanan Akun
            </h2>
            <p
                class="mt-2 text-sm text-gray-500 dark:text-gray-400 font-manrope"
            >
                Gunakan kata sandi yang kuat untuk menjaga keamanan akun TUJAGO
                Anda.
            </p>
        </header>

        <form @submit.prevent="updatePassword" class="space-y-6">
            <div>
                <label
                    for="current_password"
                    class="block text-[10px] font-black text-gray-400 uppercase tracking-widest font-unbounded mb-2"
                    >Kata Sandi Saat Ini</label
                >
                <input
                    id="current_password"
                    ref="currentPasswordInput"
                    v-model="form.current_password"
                    type="password"
                    class="w-full px-5 py-4 rounded-xl bg-gray-50 dark:bg-black/50 border-gray-100 dark:border-white/5 focus:border-rose-500 focus:ring-rose-500/20 transition-all font-manrope text-gray-900 dark:text-white"
                    autocomplete="current-password"
                />
                <InputError
                    :message="form.errors.current_password"
                    class="mt-2"
                />
            </div>

            <div>
                <label
                    for="password"
                    class="block text-[10px] font-black text-gray-400 uppercase tracking-widest font-unbounded mb-2"
                    >Kata Sandi Baru</label
                >
                <input
                    id="password"
                    ref="passwordInput"
                    v-model="form.password"
                    type="password"
                    class="w-full px-5 py-4 rounded-xl bg-gray-50 dark:bg-black/50 border-gray-100 dark:border-white/5 focus:border-rose-500 focus:ring-rose-500/20 transition-all font-manrope text-gray-900 dark:text-white"
                    autocomplete="new-password"
                />
                <InputError :message="form.errors.password" class="mt-2" />
            </div>

            <div>
                <label
                    for="password_confirmation"
                    class="block text-[10px] font-black text-gray-400 uppercase tracking-widest font-unbounded mb-2"
                    >Konfirmasi Kata Sandi Baru</label
                >
                <input
                    id="password_confirmation"
                    v-model="form.password_confirmation"
                    type="password"
                    class="w-full px-5 py-4 rounded-xl bg-gray-50 dark:bg-black/50 border-gray-100 dark:border-white/5 focus:border-rose-500 focus:ring-rose-500/20 transition-all font-manrope text-gray-900 dark:text-white"
                    autocomplete="new-password"
                />
                <InputError
                    :message="form.errors.password_confirmation"
                    class="mt-2"
                />
            </div>

            <div class="flex items-center gap-6 pt-4">
                <button
                    type="submit"
                    :disabled="form.processing"
                    class="px-8 py-4 bg-rose-600 text-white font-black font-unbounded text-xs rounded-xl shadow-lg shadow-rose-600/30 hover:bg-rose-700 transition-all active:scale-95 disabled:opacity-50"
                >
                    Perbarui Kata Sandi
                </button>

                <Transition
                    enter-active-class="transition ease-in-out"
                    enter-from-class="opacity-0"
                    leave-active-class="transition ease-in-out"
                    leave-to-class="opacity-0"
                >
                    <p
                        v-if="form.recentlySuccessful"
                        class="text-xs text-emerald-600 dark:text-emerald-400 font-black font-unbounded uppercase tracking-widest"
                    >
                        Berhasil!
                    </p>
                </Transition>
            </div>
        </form>
    </section>
</template>
