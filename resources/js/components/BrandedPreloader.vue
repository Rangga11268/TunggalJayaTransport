<script setup>
import { ref, onMounted } from "vue";
import gsap from "gsap";

const show = ref(true);
const logoRef = ref(null);
const containerRef = ref(null);
const progress = ref(0);

onMounted(() => {
    const tl = gsap.timeline({
        onComplete: () => {
            gsap.to(containerRef.value, {
                opacity: 0,
                duration: 1,
                ease: "power4.inOut",
                onComplete: () => (show.value = false),
            });
        },
    });

    // Initial State
    gsap.set(logoRef.value, { scale: 0.8, opacity: 0 });

    // Progress Simulation
    tl.to(progress, {
        value: 100,
        duration: 2.5,
        ease: "power2.inOut",
        onUpdate: () => (progress.value = Math.floor(progress.value)),
    });

    // Logo Reveal
    tl.to(
        logoRef.value,
        {
            opacity: 1,
            scale: 1,
            duration: 1.5,
            ease: "expo.out",
        },
        "-=2"
    );

    // Subtle Glitch Effect
    tl.to(
        logoRef.value,
        {
            skewX: 20,
            duration: 0.1,
            ease: "power4.inOut",
            repeat: 3,
            yoyo: true,
        },
        "-=0.5"
    );
    tl.to(logoRef.value, { skewX: 0, duration: 0.1 });
});
</script>

<template>
    <div
        v-if="show"
        ref="containerRef"
        class="fixed inset-0 z-[9999] bg-[#050505] flex flex-col items-center justify-center overflow-hidden"
    >
        <!-- Background Glow -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div
                class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[500px] h-[500px] bg-rose-600/10 rounded-full blur-[120px]"
            ></div>
        </div>

        <!-- Logo Container -->
        <div ref="logoRef" class="relative mb-12">
            <div class="flex flex-col items-center gap-4">
                <img src="/img/logoNoBg.png" alt="Logo" class="w-24 h-24" />
                <h1
                    class="text-4xl font-black font-unbounded text-white tracking-tighter"
                >
                    TUJAGO
                </h1>
            </div>
            <!-- Glitch overlays -->
            <div
                class="absolute inset-0 flex flex-col items-center gap-4 opacity-50 mix-blend-screen animate-pulse pointer-events-none"
                style="color: cyan"
            >
                <div
                    class="w-24 h-24 rounded-full border-4 border-cyan-500/20"
                ></div>
            </div>
        </div>

        <!-- Progress Bar -->
        <div
            class="w-48 h-[2px] bg-white/10 rounded-full overflow-hidden relative"
        >
            <div
                class="absolute inset-y-0 left-0 bg-rose-600 transition-all duration-300"
                :style="{ width: progress + '%' }"
            ></div>
        </div>

        <div class="mt-4 flex flex-col items-center">
            <span
                class="text-[10px] font-black font-unbounded text-white/40 uppercase tracking-[0.5em]"
            >
                {{ progress }}%
            </span>
            <span
                class="text-[8px] font-bold font-manrope text-white/20 uppercase tracking-[0.3em] mt-2"
            >
                Initializing Premium Experience
            </span>
        </div>
    </div>
</template>

<style scoped>
@keyframes pulse {
    0%,
    100% {
        transform: translate(0, 0);
    }
    33% {
        transform: translate(-2px, 2px);
    }
    66% {
        transform: translate(2px, -2px);
    }
}
.animate-pulse {
    animation: pulse 0.2s infinite;
}
</style>
