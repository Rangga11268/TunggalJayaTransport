<script setup>
import { computed } from "vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import { Link, useForm, usePage } from "@inertiajs/vue3";

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const page = usePage();
const user = computed(() => page.props.auth?.user || { name: "", email: "" });

const form = useForm({
    name: user.value.name,
    email: user.value.email,
});
</script>

<template>
    <section>
        <header class="mb-8">
            <h2
                class="text-xl font-black text-gray-900 dark:text-white font-unbounded"
            >
                Informasi Profil
            </h2>
            <p
                class="mt-2 text-sm text-gray-500 dark:text-gray-400 font-manrope"
            >
                Perbarui informasi akun dan alamat email resmi Anda.
            </p>
        </header>

        <form
            @submit.prevent="form.patch(route('profile.update'))"
            class="space-y-6"
        >
            <div>
                <label
                    for="name"
                    class="block text-[10px] font-black text-gray-400 uppercase tracking-widest font-unbounded mb-2"
                    >Nama Lengkap</label
                >
                <input
                    id="name"
                    type="text"
                    class="w-full px-5 py-4 rounded-xl bg-gray-50 dark:bg-black/50 border-gray-100 dark:border-white/5 focus:border-rose-500 focus:ring-rose-500/20 transition-all font-manrope text-gray-900 dark:text-white"
                    v-model="form.name"
                    required
                    autofocus
                    autocomplete="name"
                />
                <InputError class="mt-2" :message="form.errors.name" />
            </div>

            <div>
                <label
                    for="email"
                    class="block text-[10px] font-black text-gray-400 uppercase tracking-widest font-unbounded mb-2"
                    >Alamat Email</label
                >
                <input
                    id="email"
                    type="email"
                    class="w-full px-5 py-4 rounded-xl bg-gray-50 dark:bg-black/50 border-gray-100 dark:border-white/5 focus:border-rose-500 focus:ring-rose-500/20 transition-all font-manrope text-gray-900 dark:text-white"
                    v-model="form.email"
                    required
                    autocomplete="username"
                />
                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div v-if="mustVerifyEmail && user.email_verified_at === null">
                <div
                    class="p-4 rounded-xl bg-amber-50 dark:bg-amber-900/10 border border-amber-100 dark:border-amber-900/20"
                >
                    <p
                        class="text-xs text-amber-700 dark:text-amber-400 font-manrope font-bold"
                    >
                        Email Anda belum diverifikasi.
                        <Link
                            :href="route('verification.send')"
                            method="post"
                            as="button"
                            class="underline hover:text-amber-800 transition-colors"
                        >
                            Kirim ulang email verifikasi.
                        </Link>
                    </p>

                    <div
                        v-show="status === 'verification-link-sent'"
                        class="mt-2 text-xs font-bold text-emerald-600 dark:text-emerald-400"
                    >
                        Tautan verifikasi baru telah dikirim.
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-6 pt-4">
                <button
                    type="submit"
                    :disabled="form.processing"
                    class="px-8 py-4 bg-rose-600 text-white font-black font-unbounded text-xs rounded-xl shadow-lg shadow-rose-600/30 hover:bg-rose-700 transition-all active:scale-95 disabled:opacity-50"
                >
                    Simpan Perubahan
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
                        Tersimpan!
                    </p>
                </Transition>
            </div>
        </form>
    </section>
</template>
