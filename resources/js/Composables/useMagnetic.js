import { onMounted, onUnmounted } from "vue";
import gsap from "gsap";

export function useMagnetic(elRef, options = { strength: 40, duration: 0.8 }) {
    const getElement = () => {
        if (!elRef.value) return null;
        // If it's a Vue component instance, it will have $el
        return elRef.value.$el || elRef.value;
    };

    const handleMouseMove = (e) => {
        const el = getElement();
        if (!el || !el.getBoundingClientRect) return;

        const { clientX, clientY } = e;
        const { left, top, width, height } = el.getBoundingClientRect();

        const x = clientX - (left + width / 2);
        const y = clientY - (top + height / 2);

        gsap.to(el, {
            x: x * (options.strength / 100),
            y: y * (options.strength / 100),
            duration: options.duration,
            ease: "power2.out",
        });
    };

    const handleMouseLeave = () => {
        const el = getElement();
        if (!el) return;

        gsap.to(el, {
            x: 0,
            y: 0,
            duration: options.duration,
            ease: "elastic.out(1, 0.3)",
        });
    };

    onMounted(() => {
        const el = getElement();
        if (el && el.addEventListener) {
            el.addEventListener("mousemove", handleMouseMove);
            el.addEventListener("mouseleave", handleMouseLeave);
        }
    });

    onUnmounted(() => {
        const el = getElement();
        if (el && el.removeEventListener) {
            el.removeEventListener("mousemove", handleMouseMove);
            el.removeEventListener("mouseleave", handleMouseLeave);
        }
    });
}
