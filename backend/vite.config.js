import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import vue from "@vitejs/plugin-vue";
import { VitePWA } from "vite-plugin-pwa";

export default defineConfig({
    plugins: [
        laravel({
            input: ["resources/css/app.css", "resources/js/app.js"],
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
        VitePWA({
            registerType: "autoUpdate",
            outDir: "public",
            includeAssets: ["favicon.ico", "img/*.png", "video/*.mp4"],
            manifest: {
                name: "Tunggal Jaya Transport",
                short_name: "Tunggal Jaya",
                description: "Layanan Pemesanan Tiket Bus Premium",
                theme_color: "#e11d48",
                background_color: "#ffffff",
                display: "standalone",
                orientation: "portrait",
                icons: [
                    {
                        src: "/img/logoNoBg.png",
                        sizes: "512x512",
                        type: "image/png",
                        purpose: "any maskable",
                    },
                ],
            },
            workbox: {
                cleanupOutdatedCaches: true,
                navigateFallback: null,
            },
        }),
    ],
    // Using Vite defaults for the dev server (no custom `server` block)
    resolve: {
        alias: {
            "@": "/resources/js",
        },
    },
});
