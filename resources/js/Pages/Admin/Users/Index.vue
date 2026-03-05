<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { Head, Link, router } from "@inertiajs/vue3";
import { ref, watch } from "vue";
import Swal from "sweetalert2";
import axios from "axios";

const props = defineProps({
    users: Object,
    filters: Object,
});

const search = ref(props.filters?.search || "");
const localUsers = ref(props.users); // Reactive local state for users data
let timeout = null;

// Search via Axios (No Inertia Reload)
const applyFilters = async () => {
    try {
        const { data } = await axios.get(route("admin.users.index"), {
            params: { search: search.value || "" },
            headers: { Accept: "application/json" },
        });
        localUsers.value = data.users;

        // Sync URL optionally without reloading
        const newUrl = new URL(window.location.href);
        if (search.value) {
            newUrl.searchParams.set("search", search.value);
        } else {
            newUrl.searchParams.delete("search");
        }
        window.history.replaceState({}, "", newUrl);
    } catch (error) {
        console.error("Filter failed:", error);
    }
};

watch(search, (value) => {
    clearTimeout(timeout);
    timeout = setTimeout(() => {
        applyFilters();
    }, 500);
});

// Pagination via Axios (No Inertia Reload)
const fetchPage = async (url) => {
    if (!url) return;
    try {
        const { data } = await axios.get(url, {
            headers: { Accept: "application/json" },
        });
        localUsers.value = data.users;

        // Sync URL with pagination parameter
        window.history.replaceState({}, "", url);
        window.scrollTo({ top: 0, behavior: "smooth" });
    } catch (error) {
        console.error("Pagination failed:", error);
    }
};

const deleteUser = (id) => {
    Swal.fire({
        title: "Hapus Pengguna?",
        text: "Data yang dihapus tidak dapat dikembalikan!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
        confirmButtonText: "Ya, hapus!",
        cancelButtonText: "Batal",
    }).then((result) => {
        if (result.isConfirmed) {
            router.delete(route("admin.users.destroy", id), {
                onSuccess: () => {
                    // Success handled by layout flash message
                },
            });
        }
    });
};

const formatDate = (dateString) => {
    if (!dateString) return "-";
    return new Date(dateString).toLocaleDateString("id-ID", {
        year: "numeric",
        month: "short",
        day: "numeric",
    });
};
</script>

<template>
    <Head title="Manajemen Pengguna" />

    <AdminLayout title="Manajemen Pengguna">
        <div
            class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8"
        >
            <div>
                <h2
                    class="text-2xl font-bold text-gray-900 dark:text-white font-serif"
                >
                    Daftar Pengguna (Admin & Staff)
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    Kelola akun administrator dan staff manajemen jadwal.
                </p>
            </div>

            <div class="flex items-center gap-3">
                <div class="relative">
                    <input
                        type="text"
                        v-model="search"
                        placeholder="Cari Nama/Email..."
                        class="pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm focus:ring-2 focus:ring-brand-red/50 outline-none transition-all w-full md:w-64"
                    />
                    <div
                        class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400"
                    >
                        <i class="fas fa-search"></i>
                    </div>
                </div>

                <Link
                    :href="route('admin.users.create')"
                    class="px-5 py-2.5 rounded-xl bg-brand-red text-white font-semibold shadow-lg shadow-brand-red/30 hover:bg-red-700 hover:shadow-brand-red/50 transition-all duration-300 flex items-center gap-2 whitespace-nowrap"
                >
                    <i class="fas fa-plus"></i>
                    <span class="hidden md:inline">Tambah Pengguna</span>
                </Link>
            </div>
        </div>

        <div
            class="bg-white dark:bg-gray-800 rounded-3xl shadow-xl shadow-gray-100/50 dark:shadow-black/30 border border-gray-100 dark:border-gray-700/50 overflow-hidden"
        >
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead
                        class="bg-gray-50/50 dark:bg-gray-900/20 text-gray-500 dark:text-gray-400 text-xs uppercase font-bold tracking-wider"
                    >
                        <tr>
                            <th class="px-6 py-4">Nama</th>
                            <th class="px-6 py-4">Email</th>
                            <th class="px-6 py-4">Role</th>
                            <th class="px-6 py-4">Terdaftar</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody
                        class="divide-y divide-gray-100 dark:divide-gray-700/50"
                    >
                        <tr
                            v-for="user in localUsers?.data"
                            :key="user.id"
                            class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors"
                        >
                            <td class="px-6 py-4">
                                <div
                                    class="font-bold text-gray-900 dark:text-white"
                                >
                                    {{ user.name }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div
                                    class="text-sm text-gray-600 dark:text-gray-300"
                                >
                                    {{ user.email }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-wrap gap-1">
                                    <span
                                        v-for="role in user.roles"
                                        :key="role.id"
                                        class="px-2 py-1 rounded text-xs font-bold uppercase tracking-wider bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400"
                                    >
                                        {{ role.name.replace("_", " ") }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-500">
                                    {{ formatDate(user.created_at) }}
                                </div>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div
                                    class="flex items-center justify-end gap-2"
                                >
                                    <Link
                                        :href="
                                            route('admin.users.edit', user.id)
                                        "
                                        class="p-2 rounded-lg text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-colors tooltip"
                                        title="Edit"
                                    >
                                        <i class="fas fa-edit"></i>
                                    </Link>
                                    <button
                                        @click="deleteUser(user.id)"
                                        class="p-2 rounded-lg text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors tooltip"
                                        title="Hapus"
                                        :disabled="
                                            user.id === $page.props.auth.user.id
                                        "
                                        :class="{
                                            'opacity-50 cursor-not-allowed':
                                                user.id ===
                                                $page.props.auth.user.id,
                                        }"
                                    >
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="localUsers?.data?.length === 0">
                            <td
                                colspan="5"
                                class="px-6 py-12 text-center text-gray-400"
                            >
                                <div class="flex flex-col items-center">
                                    <i
                                        class="fas fa-users text-4xl mb-3 opacity-30"
                                    ></i>
                                    <p>Belum ada data pengguna.</p>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div
                class="px-6 py-4 border-t border-gray-100 dark:border-gray-700 flex items-center justify-between"
                v-if="localUsers?.links?.length > 3"
            >
                <div class="text-xs text-gray-500">
                    Menampilkan {{ localUsers.from }} - {{ localUsers.to }} dari
                    {{ localUsers.total }} data
                </div>
                <div class="flex gap-1">
                    <template v-for="(link, k) in localUsers.links" :key="k">
                        <button
                            v-if="link.url"
                            @click.prevent="fetchPage(link.url)"
                            v-html="link.label"
                            :class="[
                                'px-3 py-1 rounded-lg text-xs font-bold transition-all',
                                link.active
                                    ? 'bg-brand-red text-white shadow-md shadow-brand-red/20'
                                    : 'text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700',
                            ]"
                        />
                        <span
                            v-else
                            v-html="link.label"
                            class="px-3 py-1 rounded-lg text-xs font-bold text-gray-400 cursor-not-allowed opacity-50"
                        ></span>
                    </template>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
