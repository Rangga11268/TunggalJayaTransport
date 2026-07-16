<script setup>
import { Head, useForm } from "@inertiajs/vue3";
import FrontendLayout from "@/Layouts/FrontendLayout.vue";
import InputError from "@/Components/InputError.vue";

defineOptions({ layout: FrontendLayout });

const form = useForm({
    name: "",
    email: "",
    subject: "",
    message: "",
});

const submit = () => {
    form.post(route("contact.store"), {
        preserveScroll: true,
        onSuccess: () => form.reset(),
    });
};
</script>

<template>
    <Head title="Hubungi Kami" />

    <div class="min-h-screen bg-[#fcf9f8]">
        <!-- Hero -->
        <div class="pt-28 pb-12 px-4 sm:px-6 lg:px-8 text-center">
            <span class="inline-block px-4 py-1.5 rounded-full bg-white border border-[#ebe7e7] text-[#10207a] text-[11px] font-bold tracking-widest uppercase mb-5 shadow-sm">
                Bantuan & Dukungan
            </span>
            <h1 class="font-unbounded font-black text-4xl md:text-5xl text-[#1c1b1b] mb-3">Hubungi Kami</h1>
            <p class="text-[#454652] text-[16px] max-w-xl mx-auto">
                Punya pertanyaan atau butuh bantuan perjalanan? Tim support kami siap melayani Anda.
            </p>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-24">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
                <!-- Left: Info -->
                <div class="space-y-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="bg-white border border-[#ebe7e7] rounded-[12px] p-6 shadow-sm">
                            <div class="w-10 h-10 rounded-xl bg-[#10207a]/10 flex items-center justify-center mb-4">
                                <i class="fas fa-phone-alt text-sm text-[#10207a]"></i>
                            </div>
                            <h3 class="font-bold text-[15px] text-[#1c1b1b] mb-0.5">Telepon</h3>
                            <p class="text-sm text-[#454652]">+62 21 1234 5678</p>
                            <span class="inline-block mt-3 text-[10px] font-bold uppercase tracking-wider text-emerald-600 bg-emerald-50 px-2 py-1 rounded-lg">Online 24 Jam</span>
                        </div>
                        <div class="bg-white border border-[#ebe7e7] rounded-[12px] p-6 shadow-sm">
                            <div class="w-10 h-10 rounded-xl bg-[#10207a]/10 flex items-center justify-center mb-4">
                                <i class="fas fa-envelope text-sm text-[#10207a]"></i>
                            </div>
                            <h3 class="font-bold text-[15px] text-[#1c1b1b] mb-0.5">Email</h3>
                            <p class="text-sm text-[#454652]">support@tujago.com</p>
                            <span class="inline-block mt-3 text-[10px] font-bold uppercase tracking-wider text-[#10207a] bg-[#10207a]/10 px-2 py-1 rounded-lg">Respon Cepat</span>
                        </div>
                    </div>

                    <!-- Map -->
                    <div class="relative h-[350px] rounded-[12px] overflow-hidden shadow-sm border border-[#ebe7e7]">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d253840.4878845067!2d106.68942964135938!3d-6.229728025238589!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f3e945e34b9d!2sJakarta!5e0!3m2!1sen!2sid!4v1633072877145!5m2!1sen!2sid"
                            width="100%" height="100%" style="border:0; filter:grayscale(100%)" allowfullscreen="" loading="lazy"></iframe>
                        <div class="absolute bottom-4 left-4 right-4">
                            <div class="bg-white/95 backdrop-blur-sm p-4 rounded-[10px] shadow-sm border border-[#ebe7e7] flex items-center gap-3">
                                <i class="fas fa-map-marker-alt text-[#10207a]"></i>
                                <div>
                                    <h4 class="font-bold text-sm text-[#1c1b1b]">Kantor Pusat</h4>
                                    <p class="text-xs text-[#454652]">Jl. Transportasi No. 88, Jakarta Selatan</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right: Form -->
                <div class="bg-white border border-[#ebe7e7] rounded-[12px] p-8 shadow-sm h-fit">
                    <h2 class="font-unbounded font-bold text-[22px] text-[#1c1b1b] mb-6">Kirim Pesan</h2>
                    <form @submit.prevent="submit" class="space-y-5">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label class="text-xs font-bold text-[#454652] uppercase tracking-wider mb-1.5 block">Nama Lengkap</label>
                                <input v-model="form.name" type="text" class="w-full px-4 py-3 bg-[#f6f3f2] border border-[#e5e2e1] focus:border-[#10207a] focus:bg-white focus:ring-0 rounded-[10px] text-[#1c1b1b] text-sm outline-none transition-all" placeholder="Nama Anda" required />
                                <InputError :message="form.errors.name" />
                            </div>
                            <div>
                                <label class="text-xs font-bold text-[#454652] uppercase tracking-wider mb-1.5 block">Email</label>
                                <input v-model="form.email" type="email" class="w-full px-4 py-3 bg-[#f6f3f2] border border-[#e5e2e1] focus:border-[#10207a] focus:bg-white focus:ring-0 rounded-[10px] text-[#1c1b1b] text-sm outline-none transition-all" placeholder="email@anda.com" required />
                                <InputError :message="form.errors.email" />
                            </div>
                        </div>
                        <div>
                            <label class="text-xs font-bold text-[#454652] uppercase tracking-wider mb-1.5 block">Subjek</label>
                            <input v-model="form.subject" type="text" class="w-full px-4 py-3 bg-[#f6f3f2] border border-[#e5e2e1] focus:border-[#10207a] focus:bg-white focus:ring-0 rounded-[10px] text-[#1c1b1b] text-sm outline-none transition-all" placeholder="Perihal" required />
                            <InputError :message="form.errors.subject" />
                        </div>
                        <div>
                            <label class="text-xs font-bold text-[#454652] uppercase tracking-wider mb-1.5 block">Pesan</label>
                            <textarea v-model="form.message" rows="4" class="w-full px-4 py-3 bg-[#f6f3f2] border border-[#e5e2e1] focus:border-[#10207a] focus:bg-white focus:ring-0 rounded-[10px] text-[#1c1b1b] text-sm outline-none transition-all resize-none" placeholder="Tulis pesan Anda..." required></textarea>
                            <InputError :message="form.errors.message" />
                        </div>
                        <button type="submit" :disabled="form.processing"
                            class="w-full py-4 bg-[#10207a] text-white rounded-[10px] font-bold text-[15px] hover:bg-[#0c185e] transition-all shadow-sm disabled:opacity-50 flex items-center justify-center gap-2">
                            <span v-if="form.processing"><i class="fas fa-spinner fa-spin"></i> Mengirim...</span>
                            <span v-else>Kirim Pesan <i class="fas fa-paper-plane"></i></span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>
