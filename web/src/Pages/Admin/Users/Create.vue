<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";

const props = defineProps({
    roles: Array,
});

const form = useForm({
    name: "",
    email: "",
    password: "",
    password_confirmation: "",
    roles: [],
});

const submit = () => {
    form.post(route("admin.users.store"), {
        preserveScroll: true,
        onSuccess: () => form.reset("password", "password_confirmation"),
    });
};
</script>

<template>
    <Head title="Tambah Pengguna" />

    <AdminLayout title="Tambah Pengguna">
        <div class="max-w-2xl mx-auto">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h2
                        class="text-2xl font-bold text-gray-900 dark:text-white font-serif"
                    >
                        Tambah Pengguna Baru
                    </h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                        Buat akun baru untuk administrator atau staff.
                    </p>
                </div>
                <Link
                    :href="route('admin.users.index')"
                    class="px-5 py-2.5 rounded-xl bg-gray-100 text-gray-700 font-semibold hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700 transition-all duration-300 flex items-center gap-2"
                >
                    <i class="fas fa-arrow-left"></i>
                    <span>Kembali</span>
                </Link>
            </div>

            <form
                @submit.prevent="submit"
                class="bg-white dark:bg-gray-800 rounded-3xl p-8 shadow-xl shadow-gray-100/50 dark:shadow-black/30 border border-gray-100 dark:border-gray-700/50 space-y-6"
            >
                <!-- Name -->
                <div>
                    <label
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
                        >Nama Lengkap</label
                    >
                    <input
                        v-model="form.name"
                        type="text"
                        placeholder="Nama Lengkap"
                        class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-brand-red/50 focus:border-brand-red outline-none transition-all"
                        :class="{
                            'border-red-500 focus:ring-red-500/50':
                                form.errors.name,
                        }"
                    />
                    <p
                        v-if="form.errors.name"
                        class="text-red-500 text-xs mt-1"
                    >
                        {{ form.errors.name }}
                    </p>
                </div>

                <!-- Email -->
                <div>
                    <label
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
                        >Email</label
                    >
                    <input
                        v-model="form.email"
                        type="email"
                        placeholder="email@contoh.com"
                        class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-brand-red/50 focus:border-brand-red outline-none transition-all"
                        :class="{
                            'border-red-500 focus:ring-red-500/50':
                                form.errors.email,
                        }"
                    />
                    <p
                        v-if="form.errors.email"
                        class="text-red-500 text-xs mt-1"
                    >
                        {{ form.errors.email }}
                    </p>
                </div>

                <!-- Password -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
                            >Password</label
                        >
                        <input
                            v-model="form.password"
                            type="password"
                            placeholder="********"
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-brand-red/50 focus:border-brand-red outline-none transition-all"
                            :class="{
                                'border-red-500 focus:ring-red-500/50':
                                    form.errors.password,
                            }"
                        />
                        <p
                            v-if="form.errors.password"
                            class="text-red-500 text-xs mt-1"
                        >
                            {{ form.errors.password }}
                        </p>
                    </div>
                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
                            >Konfirmasi Password</label
                        >
                        <input
                            v-model="form.password_confirmation"
                            type="password"
                            placeholder="********"
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-brand-red/50 focus:border-brand-red outline-none transition-all"
                        />
                    </div>
                </div>

                <!-- Roles -->
                <div>
                    <label
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
                        >Peran (Role)</label
                    >
                    <div class="grid grid-cols-2 gap-4">
                        <div
                            v-for="role in roles"
                            :key="role.id"
                            class="flex items-center p-3 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors cursor-pointer"
                            @click="
                                form.roles.includes(role.id)
                                    ? (form.roles = form.roles.filter(
                                          (id) => id !== role.id
                                      ))
                                    : form.roles.push(role.id)
                            "
                        >
                            <div class="flex items-center h-5">
                                <input
                                    type="checkbox"
                                    :value="role.id"
                                    v-model="form.roles"
                                    class="w-4 h-4 text-brand-red border-gray-300 rounded focus:ring-brand-red dark:focus:ring-brand-red dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600 cursor-pointer pointer-events-none"
                                />
                            </div>
                            <div class="ml-2 text-sm">
                                <label
                                    class="font-medium text-gray-900 dark:text-gray-300 cursor-pointer pointer-events-none capitalize"
                                    >{{ role.name.replace("_", " ") }}</label
                                >
                            </div>
                        </div>
                    </div>
                    <p
                        v-if="form.errors.roles"
                        class="text-red-500 text-xs mt-1"
                    >
                        {{ form.errors.roles }}
                    </p>
                    <p class="text-xs text-gray-500 mt-2">
                        * Pilih minimal satu peran untuk pengguna.
                    </p>
                </div>

                <!-- Actions -->
                <div
                    class="flex justify-end gap-4 pt-4 border-t border-gray-100 dark:border-gray-700"
                >
                    <Link
                        :href="route('admin.users.index')"
                        class="px-6 py-3 rounded-xl border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 font-semibold hover:bg-gray-100 dark:hover:bg-gray-800 transition-all"
                    >
                        Batal
                    </Link>
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="px-8 py-3 rounded-xl bg-brand-red text-white font-bold shadow-lg shadow-brand-red/30 hover:bg-red-700 hover:shadow-brand-red/50 transition-all duration-300 disabled:opacity-70 disabled:cursor-not-allowed flex items-center gap-2"
                    >
                        <i
                            v-if="form.processing"
                            class="fas fa-spinner fa-spin"
                        ></i>
                        <span v-else>Simpan Pengguna</span>
                    </button>
                </div>
            </form>
        </div>
    </AdminLayout>
</template>
