<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    writers: { type: Array, default: () => [] },
});
</script>

<template>
    <Head title="Blog writers" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h2 class="text-2xl font-black text-gray-900">Blog writers</h2>
                    <p class="text-sm text-gray-500 mt-1">
                        Display-only author profiles shown on blog cards and articles.
                    </p>
                </div>
                <div class="flex items-center gap-3">
                    <Link
                        :href="route('admin.blog-writers.create')"
                        class="rounded-xl bg-primary px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-primary/90"
                    >
                        + New writer
                    </Link>
                    <Link :href="route('admin.blog.index')" class="text-sm font-semibold text-primary hover:underline">
                        ← Back to blog
                    </Link>
                </div>
            </div>
        </template>

        <div class="space-y-6">
            <div class="flex items-center justify-end gap-3">
                <Link
                    :href="route('admin.blog.index')"
                    class="rounded-xl border border-gray-200 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50"
                >
                    Back to Blog
                </Link>
                <Link
                    :href="route('admin.blog-writers.create')"
                    class="rounded-xl bg-primary px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-primary/90"
                >
                    Add New Writer
                </Link>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div
                v-for="writer in writers"
                :key="writer.id"
                class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 flex flex-col items-center text-center"
            >
                <img
                    v-if="writer.photo"
                    :src="writer.photo"
                    :alt="writer.name"
                    class="h-20 w-20 rounded-full object-cover ring-2 ring-gray-100"
                />
                <div
                    v-else
                    class="h-20 w-20 rounded-full bg-gray-100 flex items-center justify-center text-xl font-bold text-gray-400"
                >
                    {{ writer.name?.charAt(0) }}
                </div>
                <h3 class="mt-4 text-lg font-bold text-gray-900">{{ writer.name }}</h3>
                <p class="mt-2 text-sm text-gray-500 line-clamp-4 flex-1">{{ writer.bio || 'No bio yet.' }}</p>
                <Link
                    :href="route('admin.blog-writers.edit', writer.id)"
                    class="mt-4 text-sm font-semibold text-primary hover:underline"
                >
                    Edit profile
                </Link>
            </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
