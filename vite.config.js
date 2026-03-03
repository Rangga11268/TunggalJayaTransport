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
    server: {
        host: true,
        hmr: {
            // Use the LAN IP so devices on the same network can connect to Vite HMR.
            // Replace with your local IP if different (e.g. 192.168.1.15)
            host: "192.168.1.15",
            protocol: "ws",
            port: 5173,
        },
    },
    resolve: {
        alias: {
            "@": "/resources/js",
        },
    },
});
