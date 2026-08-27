<script setup>
import { Head, Link } from "@inertiajs/vue3";

defineProps({
    message: String,
});
</script>

<template>
    <Head title="Page Not Found" />

    <div
        class="min-h-screen flex items-center justify-center bg-gray-100 dark:bg-gray-900 px-4 sm:px-6 lg:px-8"
    >
        <div class="max-w-md w-full text-center">
            <h1
                class="text-9xl font-extrabold text-gray-800 dark:text-gray-100 tracking-widest"
            >
                404
            </h1>
            <div
                class="bg-[#FF2D20] px-2 text-sm rounded rotate-12 absolute top-1/3 left-1/2 transform -translate-x-1/2 -translate-y-1/2 text-white font-bold"
            >
                Page Not Found
            </div>
            <div class="mt-8">
                <h3
                    class="text-2xl font-semibold text-gray-800 dark:text-gray-200"
                >
                    Oops! Halaman tidak ditemukan.
                </h3>
                <p class="mt-4 text-gray-600 dark:text-gray-400">
                    Maaf, halaman yang Anda cari mungkin telah dihapus, namanya
                    diubah, atau sementara tidak tersedia.
                </p>
                <div class="mt-8">
                    <Link
                        href="/"
                        class="inline-flex items-center px-4 py-2 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    >
                        Kembali ke Beranda
                    </Link>
                </div>
            </div>
        </div>
    </div>
</template>
