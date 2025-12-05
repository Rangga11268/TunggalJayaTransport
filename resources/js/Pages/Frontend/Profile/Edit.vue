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

    <div class="py-12 bg-gray-50 dark:bg-gray-950 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <h1
                class="text-3xl font-black text-gray-900 dark:text-white mb-8 flex items-center"
            >
                <i class="fas fa-user-circle text-brand-red mr-4"></i>
                Profil Saya
            </h1>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Profile Information -->
                <div
                    class="p-4 sm:p-8 bg-white dark:bg-gray-900 shadow-xl shadow-black/5 sm:rounded-3xl border border-gray-100 dark:border-gray-800"
                >
                    <UpdateProfileInformationForm
                        :must-verify-email="mustVerifyEmail"
                        :status="status"
                        class="max-w-xl"
                    />
                </div>

                <!-- Update Password -->
                <div
                    class="p-4 sm:p-8 bg-white dark:bg-gray-900 shadow-xl shadow-black/5 sm:rounded-3xl border border-gray-100 dark:border-gray-800"
                >
                    <UpdatePasswordForm class="max-w-xl" />
                </div>

                <!-- Delete Account -->
                <div
                    class="p-4 sm:p-8 bg-white dark:bg-gray-900 shadow-xl shadow-black/5 sm:rounded-3xl border border-gray-100 dark:border-gray-800 md:col-span-2"
                >
                    <DeleteUserForm class="max-w-xl" />
                </div>
            </div>
        </div>
    </div>
</template>
