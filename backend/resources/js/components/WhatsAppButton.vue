<script setup>
import { onMounted, ref } from "vue";
import gsap from "gsap";

const buttonRef = ref(null);

onMounted(() => {
    gsap.from(buttonRef.value, {
        scale: 0,
        opacity: 0,
        duration: 1,
        delay: 3, // Delay to let the preloader finish
        ease: "back.out(1.7)",
    });
});
</script>

<template>
    <a
        ref="buttonRef"
        href="https://wa.me/628123456789"
        target="_blank"
        class="fixed bottom-8 right-8 z-[90] w-14 h-14 bg-white dark:bg-[#111] rounded-full flex items-center justify-center shadow-2xl border border-gray-100 dark:border-white/10 group overflow-hidden"
    >
        <!-- Background Glow -->
        <div
            class="absolute inset-0 bg-[#25D366] translate-y-full group-hover:translate-y-0 transition-transform duration-500"
        ></div>

        <i
            class="fab fa-whatsapp text-2xl text-[#25D366] group-hover:text-white relative z-10 transition-colors duration-500"
        ></i>

        <!-- Tooltip -->
        <div
            class="absolute right-full mr-4 px-4 py-2 bg-white dark:bg-[#111] border border-gray-100 dark:border-white/10 rounded-xl text-xs font-bold text-gray-900 dark:text-white whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none"
        >
            Butuh Bantuan?
        </div>
    </a>
</template>
