<script setup>
import InputError from "@/Components/InputError.vue";
import Modal from "@/Components/Modal.vue";
import { useForm } from "@inertiajs/vue3";
import { nextTick, ref } from "vue";

const confirmingUserDeletion = ref(false);
const passwordInput = ref(null);

const form = useForm({
    password: "",
});

const confirmUserDeletion = () => {
    confirmingUserDeletion.value = true;

    nextTick(() => passwordInput.value.focus());
};

const deleteUser = () => {
    form.delete(route("profile.destroy"), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
        onError: () => passwordInput.value.focus(),
        onFinish: () => form.reset(),
    });
};

const closeModal = () => {
    confirmingUserDeletion.value = false;

    form.reset();
};
</script>

<template>
    <section>
        <header class="mb-8">
            <h2 class="text-xl font-black text-rose-600 font-unbounded">
                Zona Bahaya
            </h2>
            <p
                class="mt-2 text-sm text-gray-500 dark:text-gray-400 "
            >
                Penghapusan akun bersifat permanen. Semua data pemesanan dan
                profil Anda akan hilang selamanya.
            </p>
        </header>

        <button
            @click="confirmUserDeletion"
            class="px-8 py-4 border-2 border-rose-600/20 text-rose-600 font-black font-unbounded text-xs rounded-xl hover:bg-rose-600 hover:text-white transition-all duration-300 active:scale-95"
        >
            Hapus Akun Permanen
        </button>

        <Modal :show="confirmingUserDeletion" @close="closeModal">
            <div
                class="p-10 bg-white dark:bg-[#050505] rounded-[2.5rem] border border-gray-100 dark:border-white/5 shadow-2xl"
            >
                <h2
                    class="text-2xl font-black text-gray-900 dark:text-white font-unbounded mb-4"
                >
                    Konfirmasi Penghapusan
                </h2>

                <p
                    class="text-sm text-gray-500 dark:text-gray-400  mb-8"
                >
                    Apakah Anda benar-benar yakin? Tindakan ini tidak dapat
                    dibatalkan. Harap masukkan kata sandi Anda untuk
                    mengonfirmasi.
                </p>

                <div class="mb-8">
                    <label
                        for="password"
                        class="block text-[10px] font-black text-gray-400 uppercase tracking-widest font-unbounded mb-2"
                        >Kata Sandi Anda</label
                    >
                    <input
                        id="password"
                        ref="passwordInput"
                        v-model="form.password"
                        type="password"
                        class="w-full px-5 py-4 rounded-xl bg-gray-50 dark:bg-black border-gray-100 dark:border-white/5 focus:border-rose-500 focus:ring-rose-500/20 transition-all  text-gray-900 dark:text-white"
                        placeholder="••••••••"
                        @keyup.enter="deleteUser"
                    />

                    <InputError :message="form.errors.password" class="mt-2" />
                </div>

                <div class="flex flex-col sm:flex-row justify-end gap-4">
                    <button
                        @click="closeModal"
                        class="px-8 py-4 bg-gray-100 dark:bg-white/5 text-gray-600 dark:text-white/60 font-black font-unbounded text-xs rounded-xl hover:bg-gray-200 dark:hover:bg-white/10 transition-all"
                    >
                        Batal
                    </button>

                    <button
                        :disabled="form.processing"
                        @click="deleteUser"
                        class="px-8 py-4 bg-rose-600 text-white font-black font-unbounded text-xs rounded-xl shadow-lg shadow-rose-600/30 hover:bg-rose-700 transition-all disabled:opacity-50"
                    >
                        Ya, Hapus Akun Saya
                    </button>
                </div>
            </div>
        </Modal>
    </section>
</template>
