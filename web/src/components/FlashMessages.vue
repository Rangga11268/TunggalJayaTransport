<script setup>
import { usePage } from "@inertiajs/vue3";
import { ref, watch, onMounted } from "vue";

const page = usePage();
const showSuccess = ref(false);
const showError = ref(false);
const showWarning = ref(false);
const showInfo = ref(false);

const closeAlert = (type) => {
    if (type === "success") showSuccess.value = false;
    if (type === "error") showError.value = false;
    if (type === "warning") showWarning.value = false;
    if (type === "info") showInfo.value = false;
};

watch(
    () => page.props.flash,
    (flash) => {
        showSuccess.value = !!flash.success;
        showError.value = !!flash.error;
        showWarning.value = !!flash.warning;
        showInfo.value = !!flash.info;

        // Auto-hide after 5 seconds
        if (flash.success || flash.error || flash.warning || flash.info) {
            setTimeout(() => {
                showSuccess.value = false;
                showError.value = false;
                showWarning.value = false;
                showInfo.value = false;
            }, 5000);
        }
    },
    { deep: true, immediate: true }
);
</script>

<template>
    <div class="fixed top-20 right-4 z-50 space-y-2 max-w-sm">
        <!-- Success Message -->
        <transition
            enter-active-class="transition duration-300 ease-out"
            enter-from-class="opacity-0 translate-x-full"
            enter-to-class="opacity-100 translate-x-0"
            leave-active-class="transition duration-200 ease-in"
            leave-from-class="opacity-100 translate-x-0"
            leave-to-class="opacity-0 translate-x-full"
        >
            <div
                v-if="showSuccess && page.props.flash.success"
                class="flex items-center gap-3 px-4 py-3 rounded-xl bg-gradient-to-r from-green-500 to-emerald-600 text-white shadow-lg shadow-green-500/25"
            >
                <i class="fas fa-check-circle text-lg"></i>
                <span class="flex-1 text-sm font-medium">{{
                    page.props.flash.success
                }}</span>
                <button
                    @click="closeAlert('success')"
                    class="hover:bg-white/20 rounded-full p-1 transition-colors"
                >
                    <i class="fas fa-times text-sm"></i>
                </button>
            </div>
        </transition>

        <!-- Error Message -->
        <transition
            enter-active-class="transition duration-300 ease-out"
            enter-from-class="opacity-0 translate-x-full"
            enter-to-class="opacity-100 translate-x-0"
            leave-active-class="transition duration-200 ease-in"
            leave-from-class="opacity-100 translate-x-0"
            leave-to-class="opacity-0 translate-x-full"
        >
            <div
                v-if="showError && page.props.flash.error"
                class="flex items-center gap-3 px-4 py-3 rounded-xl bg-gradient-to-r from-red-500 to-rose-600 text-white shadow-lg shadow-red-500/25"
            >
                <i class="fas fa-exclamation-circle text-lg"></i>
                <span class="flex-1 text-sm font-medium">{{
                    page.props.flash.error
                }}</span>
                <button
                    @click="closeAlert('error')"
                    class="hover:bg-white/20 rounded-full p-1 transition-colors"
                >
                    <i class="fas fa-times text-sm"></i>
                </button>
            </div>
        </transition>

        <!-- Warning Message -->
        <transition
            enter-active-class="transition duration-300 ease-out"
            enter-from-class="opacity-0 translate-x-full"
            enter-to-class="opacity-100 translate-x-0"
            leave-active-class="transition duration-200 ease-in"
            leave-from-class="opacity-100 translate-x-0"
            leave-to-class="opacity-0 translate-x-full"
        >
            <div
                v-if="showWarning && page.props.flash.warning"
                class="flex items-center gap-3 px-4 py-3 rounded-xl bg-gradient-to-r from-amber-500 to-orange-600 text-white shadow-lg shadow-amber-500/25"
            >
                <i class="fas fa-exclamation-triangle text-lg"></i>
                <span class="flex-1 text-sm font-medium">{{
                    page.props.flash.warning
                }}</span>
                <button
                    @click="closeAlert('warning')"
                    class="hover:bg-white/20 rounded-full p-1 transition-colors"
                >
                    <i class="fas fa-times text-sm"></i>
                </button>
            </div>
        </transition>

        <!-- Info Message -->
        <transition
            enter-active-class="transition duration-300 ease-out"
            enter-from-class="opacity-0 translate-x-full"
            enter-to-class="opacity-100 translate-x-0"
            leave-active-class="transition duration-200 ease-in"
            leave-from-class="opacity-100 translate-x-0"
            leave-to-class="opacity-0 translate-x-full"
        >
            <div
                v-if="showInfo && page.props.flash.info"
                class="flex items-center gap-3 px-4 py-3 rounded-xl bg-gradient-to-r from-blue-500 to-indigo-600 text-white shadow-lg shadow-blue-500/25"
            >
                <i class="fas fa-info-circle text-lg"></i>
                <span class="flex-1 text-sm font-medium">{{
                    page.props.flash.info
                }}</span>
                <button
                    @click="closeAlert('info')"
                    class="hover:bg-white/20 rounded-full p-1 transition-colors"
                >
                    <i class="fas fa-times text-sm"></i>
                </button>
            </div>
        </transition>
    </div>
</template>
