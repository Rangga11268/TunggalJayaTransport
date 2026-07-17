<script setup>
import FrontendLayout from "@/Layouts/FrontendLayout.vue";
import DeleteUserForm from "./Partials/DeleteUserForm.vue";
import UpdatePasswordForm from "./Partials/UpdatePasswordForm.vue";
import UpdateProfileInformationForm from "./Partials/UpdateProfileInformationForm.vue";
import { Head, usePage } from "@inertiajs/vue3";
import { computed } from "vue";

defineOptions({ layout: FrontendLayout });

const page = usePage();
const user = computed(() => page.props.auth?.user || {});
const getInitial = (name) => (name || 'U').charAt(0).toUpperCase();
</script>

<template>
    <Head title="Profil Saya" />

    <div class="min-h-screen bg-[#fcf9f8] pb-32">
        <!-- Header -->
        <div class="pt-28 pb-8 px-4 sm:px-6 lg:px-8 text-center">
            <div class="w-20 h-20 rounded-2xl bg-[#10207a]/10 flex items-center justify-center mx-auto mb-4 border-2 border-[#10207a]/20">
                <span class="text-3xl font-bold text-[#10207a]">{{ getInitial(user.name) }}</span>
            </div>
            <h1 class="font-unbounded font-bold text-2xl text-[#1c1b1b]">{{ user.name || 'User' }}</h1>
            <p class="text-[#454652] text-sm">{{ user.email }}</p>
        </div>

        <!-- Stats -->
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 mb-6">
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                <div class="bg-white border border-[#ebe7e7] rounded-[12px] p-4 text-center shadow-sm">
                    <div class="text-xl font-bold text-[#1c1b1b] font-unbounded">-</div>
                    <div class="text-[10px] text-[#454652] uppercase tracking-wider font-semibold mt-0.5">Perjalanan</div>
                </div>
                <div class="bg-white border border-[#ebe7e7] rounded-[12px] p-4 text-center shadow-sm">
                    <div class="text-xl font-bold text-[#1c1b1b] font-unbounded">-</div>
                    <div class="text-[10px] text-[#454652] uppercase tracking-wider font-semibold mt-0.5">Rute</div>
                </div>
                <div class="bg-white border border-[#ebe7e7] rounded-[12px] p-4 text-center shadow-sm">
                    <div class="text-xl font-bold text-emerald-600 font-unbounded">-</div>
                    <div class="text-[10px] text-[#454652] uppercase tracking-wider font-semibold mt-0.5">Poin</div>
                </div>
                <div class="bg-white border border-[#ebe7e7] rounded-[12px] p-4 text-center shadow-sm">
                    <div class="text-sm font-bold text-[#454652] font-unbounded">-</div>
                    <div class="text-[10px] text-[#454652] uppercase tracking-wider font-semibold mt-0.5">Bergabung</div>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white border border-[#ebe7e7] rounded-[12px] p-6 md:p-8 shadow-sm">
                    <UpdateProfileInformationForm :must-verify-email="mustVerifyEmail" :status="status" />
                </div>
                <div class="bg-white border border-[#ebe7e7] rounded-[12px] p-6 md:p-8 shadow-sm">
                    <UpdatePasswordForm />
                </div>
                <div class="lg:col-span-2 bg-white border border-red-200 rounded-[12px] p-6 md:p-8 shadow-sm">
                    <DeleteUserForm />
                </div>
            </div>
        </div>
    </div>
</template>
