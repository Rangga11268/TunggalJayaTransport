<template>
    <Head title="Semua Notifikasi" />

    <AdminLayout title="Semua Notifikasi">
        <div
            class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden"
        >
            <div
                class="p-6 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center"
            >
                <div>
                    <h2
                        class="text-lg font-black text-gray-900 dark:text-white font-unbounded"
                    >
                        Riwayat Notifikasi
                    </h2>
                    <p
                        class="text-sm text-gray-500 dark:text-gray-400 font-manrope"
                    >
                        Liputan lengkap aktivitas sistem
                    </p>
                </div>
                <button
                    @click="markAllAsRead"
                    class="px-5 py-2.5 bg-brand-red text-white text-xs font-black font-unbounded uppercase tracking-wider rounded-xl shadow-lg shadow-brand-red/30 hover:bg-red-700 transition-all active:scale-95"
                >
                    <i class="fas fa-check-double mr-2"></i>
                    Tandai Semua Dibaca
                </button>
            </div>

            <div v-if="notifications.data.length > 0">
                <div class="divide-y divide-gray-100 dark:divide-gray-700">
                    <div
                        v-for="notification in notifications.data"
                        :key="notification.id"
                        class="p-6 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors flex gap-4 items-start group relative"
                        :class="
                            !notification.read_at
                                ? 'bg-red-50/30 dark:bg-red-900/10'
                                : ''
                        "
                    >
                        <div class="flex-shrink-0 mt-1">
                            <div
                                class="w-10 h-10 rounded-full bg-brand-red/10 text-brand-red flex items-center justify-center"
                            >
                                <i class="fas fa-ticket-alt text-lg"></i>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0 z-10">
                            <div class="flex justify-between items-start">
                                <h3
                                    class="text-base font-black text-gray-900 dark:text-white mb-1 font-unbounded"
                                >
                                    {{ notification.data.message }}
                                </h3>
                                <span
                                    class="text-xs text-gray-500 dark:text-gray-400 whitespace-nowrap ml-4"
                                >
                                    {{
                                        new Date(
                                            notification.created_at,
                                        ).toLocaleString("id-ID", {
                                            dateStyle: "full",
                                            timeStyle: "short",
                                        })
                                    }}
                                </span>
                            </div>
                            <p
                                class="text-sm text-gray-600 dark:text-gray-300 mb-2 font-manrope"
                            >
                                Rute: {{ notification.data.route }} | Total: Rp
                                {{
                                    new Intl.NumberFormat("id-ID").format(
                                        notification.data.amount,
                                    )
                                }}
                            </p>
                            <div class="flex items-center gap-4 mt-2">
                                <Link
                                    v-if="
                                        notification.data &&
                                        notification.data.booking_id
                                    "
                                    :href="
                                        route(
                                            'admin.notifications.markAsRead',
                                            notification.id,
                                        )
                                    "
                                    method="post"
                                    :data="{
                                        redirect_to: route(
                                            'admin.bookings.show',
                                            notification.data.booking_id.toString(),
                                        ),
                                    }"
                                    class="text-sm font-bold text-brand-red hover:text-red-700 flex items-center gap-1"
                                >
                                    Lihat Detail
                                    <i class="fas fa-arrow-right text-xs"></i>
                                </Link>
                                <button
                                    v-if="!notification.read_at"
                                    @click="markAsRead(notification)"
                                    class="text-xs text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 font-manrope font-bold"
                                >
                                    Tandai dibaca
                                </button>
                            </div>
                        </div>
                        <div
                            v-if="!notification.read_at"
                            class="absolute top-6 right-6 w-3 h-3 bg-brand-red rounded-full ring-4 ring-white dark:ring-gray-800"
                        ></div>
                    </div>
                </div>

                <!-- Pagination -->
                <div
                    class="p-6 border-t border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50"
                >
                    <div class="flex justify-center gap-2">
                        <template
                            v-for="(link, index) in notifications.links"
                            :key="index"
                        >
                            <Link
                                v-if="link.url"
                                :href="link.url"
                                v-html="link.label"
                                class="px-4 py-2 text-sm rounded-lg transition-colors font-medium border"
                                :class="[
                                    link.active
                                        ? 'bg-brand-red text-white border-brand-red shadow-lg shadow-brand-red/30'
                                        : 'bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-300 border-gray-200 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white',
                                ]"
                            />
                            <span
                                v-else
                                v-html="link.label"
                                class="px-4 py-2 text-sm rounded-lg border bg-gray-50 dark:bg-gray-800/50 text-gray-400 dark:text-gray-600 border-gray-100 dark:border-gray-700 cursor-not-allowed hidden md:inline-block"
                            ></span>
                        </template>
                    </div>
                </div>
            </div>

            <div v-else class="p-12 text-center">
                <div
                    class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-gray-100 dark:bg-gray-700 mb-6"
                >
                    <i
                        class="far fa-bell-slash text-4xl text-gray-400 dark:text-gray-500"
                    ></i>
                </div>
                <h3
                    class="text-xl font-bold text-gray-900 dark:text-white mb-2"
                >
                    Tidak ada notifikasi
                </h3>
                <p class="text-gray-500 dark:text-gray-400">
                    Anda belum memiliki notifikasi apapun saat ini.
                </p>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { Head, Link, router, usePage } from "@inertiajs/vue3";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import axios from "axios";

const props = defineProps({
    notifications: Object,
});

const page = usePage();

const markAsRead = async (notification) => {
    try {
        await axios.post(
            route("admin.notifications.markAsRead", notification.id),
            {},
            {
                headers: { Accept: "application/json" },
            },
        );

        notification.read_at = new Date().toISOString();
        if (page.props.auth.unread_notifications_count > 0) {
            page.props.auth.unread_notifications_count--;
        }

        // Also update the global layout notifications state if matching
        const layoutNotif = page.props.auth.notifications?.find(
            (n) => n.id === notification.id,
        );
        if (layoutNotif) layoutNotif.read_at = notification.read_at;
    } catch (error) {
        console.error("Gagal menandai notifikasi:", error);
    }
};

const markAllAsRead = async () => {
    try {
        await axios.post(
            route("admin.notifications.markAllRead"),
            {},
            {
                headers: { Accept: "application/json" },
            },
        );

        if (props.notifications && props.notifications.data) {
            props.notifications.data.forEach((n) => {
                n.read_at = new Date().toISOString();
            });
        }

        page.props.auth.unread_notifications_count = 0;
        if (page.props.auth.notifications) {
            page.props.auth.notifications.forEach((n) => {
                n.read_at = new Date().toISOString();
            });
        }
    } catch (error) {
        console.error("Gagal menandai semua notifikasi:", error);
    }
};
</script>
