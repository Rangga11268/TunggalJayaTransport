<script setup>
defineProps({
    className: {
        type: String,
        default: "",
    },
});
</script>

<template>
    <div
        :class="[
            'animate-shimmer bg-gradient-to-r from-gray-200 via-gray-100 to-gray-200 dark:from-[#1a1a1a] dark:via-[#252525] dark:to-[#1a1a1a] bg-[length:200%_100%] rounded-xl',
            className,
        ]"
    ></div>
</template>

<style scoped>
@keyframes shimmer {
    0% {
        background-position: 200% 0;
    }
    100% {
        background-position: -200% 0;
    }
}
.animate-shimmer {
    animation: shimmer 2s infinite linear;
}
</style>
