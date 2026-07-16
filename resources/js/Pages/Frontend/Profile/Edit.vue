<script setup>
import FrontendLayout from "@/Layouts/FrontendLayout.vue";
import DeleteUserForm from "./Partials/DeleteUserForm.vue";
import UpdatePasswordForm from "./Partials/UpdatePasswordForm.vue";
import UpdateProfileInformationForm from "./Partials/UpdateProfileInformationForm.vue";
import { Head } from "@inertiajs/vue3";

defineOptions({ layout: FrontendLayout });

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});
</script>

<template>
    <Head title="Profil Saya" />

    <div
        class="bg-gray-50 dark:bg-[#050505] min-h-screen font-sans selection:bg-rose-600 selection:text-white pb-32"
    >
        <!-- Profile Header -->
        <div
            class="relative pt-32 pb-12 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto text-center overflow-hidden"
        >
            <!-- Abstract Decor -->
            <div
                class="absolute top-0 right-0 w-[400px] h-[400px] bg-rose-600/5 rounded-full blur-[100px] -z-10"
            ></div>

            <span
                class="inline-block py-1 px-3 rounded-full bg-rose-50 dark:bg-rose-900/10 text-rose-600 border border-rose-100 dark:border-rose-900/20 text-xs font-bold tracking-widest uppercase mb-6 font-unbounded"
            >
                Akun Saya
            </span>
            <h1
                class="text-4xl md:text-6xl font-black text-gray-900 dark:text-white mb-6 font-unbounded"
            >
                Pengaturan <span class="text-rose-600">Profil</span>
            </h1>
            <p
                class="text-lg text-gray-500 dark:text-gray-400 max-w-2xl mx-auto "
            >
                Kelola informasi pribadi, keamanan akun, dan preferensi profil
                Anda.
            </p>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-12">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                <!-- Profile Information -->
                <div
                    class="p-8 md:p-12 bg-white dark:bg-[#111] rounded-[2.5rem] shadow-xl border border-gray-100 dark:border-white/5 h-fit hover:shadow-2xl hover:shadow-rose-600/5 transition-all duration-500"
                >
                    <UpdateProfileInformationForm
                        :must-verify-email="mustVerifyEmail"
                        :status="status"
                        class="max-w-full"
                    />
                </div>

                <!-- Update Password -->
                <div
                    class="p-8 md:p-12 bg-white dark:bg-[#111] rounded-[2.5rem] shadow-xl border border-gray-100 dark:border-white/5 h-fit hover:shadow-2xl hover:shadow-rose-600/5 transition-all duration-500"
                >
                    <UpdatePasswordForm class="max-w-full" />
                </div>

                <!-- Delete Account -->
                <div
                    class="p-8 md:p-12 bg-rose-50/30 dark:bg-rose-900/5 rounded-[2.5rem] shadow-xl border border-rose-100 dark:border-rose-900/20 lg:col-span-2 group"
                >
                    <DeleteUserForm class="max-w-full" />
                </div>
            </div>
        </div>
    </div>
</template>
