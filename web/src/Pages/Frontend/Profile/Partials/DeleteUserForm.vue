<script setup>
import { useForm } from "@inertiajs/vue3";
import { nextTick, ref } from "vue";

const confirmingUserDeletion = ref(false);
const passwordInput = ref(null);

const form = useForm({ password: "" });

const confirmUserDeletion = () => { confirmingUserDeletion.value = true; nextTick(() => passwordInput.value.focus()); };
const deleteUser = () => {
    form.delete(route("profile.destroy"), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
        onError: () => passwordInput.value.focus(),
        onFinish: () => form.reset(),
    });
};
const closeModal = () => { confirmingUserDeletion.value = false; form.reset(); };
</script>

<template>
    <section>
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="font-bold text-[18px] text-red-600">Hapus Akun</h2>
                <p class="text-[13px] text-[#454652] mt-0.5">Penghapusan bersifat permanen. Semua data akan hilang.</p>
            </div>
            <button @click="confirmUserDeletion"
                class="px-6 py-3 border border-red-200 text-red-600 font-bold text-[13px] rounded-[10px] hover:bg-red-600 hover:text-white hover:border-red-600 transition-all shrink-0">
                Hapus Akun
            </button>
        </div>

        <!-- Modal -->
        <Teleport to="body">
            <div v-if="confirmingUserDeletion" class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 p-4" @click.self="closeModal">
                <div class="bg-white rounded-[12px] p-8 max-w-md w-full shadow-xl border border-[#ebe7e7]">
                    <h3 class="font-bold text-[20px] text-[#1c1b1b] mb-2">Konfirmasi Hapus Akun</h3>
                    <p class="text-sm text-[#454652] mb-6">Tindakan ini tidak dapat dibatalkan. Masukkan kata sandi untuk konfirmasi.</p>
                    <input ref="passwordInput" v-model="form.password" type="password" placeholder="Kata sandi Anda"
                        class="w-full px-4 py-3 bg-[#f6f3f2] border border-[#e5e2e1] focus:border-red-500 focus:bg-white focus:ring-0 rounded-[10px] text-sm outline-none transition-all mb-2"
                        @keyup.enter="deleteUser" />
                    <p v-if="form.errors.password" class="text-xs text-red-600 mb-4">{{ form.errors.password }}</p>
                    <div class="flex justify-end gap-3">
                        <button @click="closeModal" class="px-5 py-3 bg-[#f6f3f2] text-[#454652] font-semibold text-[13px] rounded-[10px] hover:bg-[#ebe7e7] transition-all">Batal</button>
                        <button :disabled="form.processing" @click="deleteUser"
                            class="px-5 py-3 bg-red-600 text-white font-bold text-[13px] rounded-[10px] hover:bg-red-700 transition-all shadow-sm disabled:opacity-50">
                            Ya, Hapus
                        </button>
                    </div>
                </div>
            </div>
        </Teleport>
    </section>
</template>
