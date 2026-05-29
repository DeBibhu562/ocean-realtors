<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const props = defineProps({
    posts: Object,
    filters: Object,
});

const search = ref(props.filters?.search || '');

const applySearch = () => {
    router.get(route('admin.blog.index'), { search: search.value || undefined }, { preserveState: true, replace: true });
};

const togglePublish = (id) => {
    router.patch(route('admin.blog.toggle', id), {}, { preserveScroll: true });
};

const deletePost = (post) => {
    if (!confirm(`Delete "${post.title}"?`)) return;
    router.delete(route('admin.blog.destroy', post.id), { preserveScroll: true });
};

const formatDate = (iso) => {
    if (!iso) return '—';
    return new Date(iso).toLocaleDateString('en-IN', { day: 'numeric', month: 'short', year: 'numeric' });
};
</script>

<template>
    <Head title="Blog posts" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h2 class="text-2xl font-black text-gray-900">Blog</h2>
                    <p class="text-sm text-gray-500 mt-1">Create and manage articles with SEO settings.</p>
                </div>
                <Link :href="route('admin.blog.create')">
                    <PrimaryButton>Add post</PrimaryButton>
                </Link>
            </div>
        </template>

        <div class="space-y-6">
            <div class="flex items-center justify-end gap-3">
                <Link
                    :href="route('admin.blog-writers.index')"
                    class="rounded-xl border border-gray-200 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50"
                >
                    Manage Writers
                </Link>
                <Link :href="route('admin.blog.create')">
                    <PrimaryButton>Add New Blog</PrimaryButton>
                </Link>
            </div>
            <form @submit.prevent="applySearch" class="flex gap-2 max-w-md">
                <input
                    v-model="search"
                    type="search"
                    placeholder="Search by title..."
                    class="flex-1 min-w-0 rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder:text-gray-400 focus:border-primary focus:ring-2 focus:ring-primary/20"
                />
                <button type="submit" class="px-4 py-2 rounded-xl bg-gray-100 text-sm font-semibold hover:bg-gray-200">Search</button>
            </form>

            <div class="hidden md:block bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <table class="w-full text-left text-sm">
                    <thead class="bg-gray-50 text-xs font-bold uppercase text-gray-400">
                        <tr>
                            <th class="px-6 py-4">Post</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4">Published</th>
                            <th class="px-6 py-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        <tr v-for="post in posts.data" :key="post.id" class="hover:bg-gray-50/80">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <img
                                        v-if="post.featured_image"
                                        :src="post.featured_image"
                                        class="w-12 h-12 rounded-lg object-cover shrink-0"
                                        alt=""
                                    />
                                    <div class="min-w-0">
                                        <p class="font-bold text-gray-900 truncate max-w-xs">{{ post.title }}</p>
                                        <p class="text-xs text-gray-400">/blog/{{ post.slug }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span
                                    class="inline-flex px-2.5 py-1 rounded-full text-xs font-bold uppercase"
                                    :class="post.status === 'published'
                                        ? 'bg-emerald-100 text-emerald-700'
                                        : 'bg-amber-100 text-amber-700'"
                                >
                                    {{ post.status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-gray-500">{{ formatDate(post.published_at) }}</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-2 flex-wrap">
                                    <a
                                        v-if="post.status === 'published'"
                                        :href="post.public_url"
                                        target="_blank"
                                        class="text-xs font-semibold text-primary hover:underline"
                                    >
                                        View
                                    </a>
                                    <button type="button" class="text-xs font-semibold text-gray-600 hover:text-primary" @click="togglePublish(post.id)">
                                        {{ post.status === 'published' ? 'Unpublish' : 'Publish' }}
                                    </button>
                                    <Link :href="route('admin.blog.edit', post.id)" class="text-xs font-semibold text-primary hover:underline">Edit</Link>
                                    <button type="button" class="text-xs font-semibold text-red-500 hover:underline" @click="deletePost(post)">Delete</button>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="!posts.data?.length">
                            <td colspan="4" class="px-6 py-12 text-center text-gray-400">No posts yet. Create your first article.</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Mobile cards -->
            <div class="md:hidden space-y-3">
                <div v-for="post in posts.data" :key="post.id" class="bg-white rounded-xl border p-4 space-y-3">
                    <p class="font-bold text-gray-900">{{ post.title }}</p>
                    <div class="flex flex-wrap gap-2">
                        <Link :href="route('admin.blog.edit', post.id)" class="text-xs font-bold text-primary">Edit</Link>
                        <button type="button" class="text-xs font-bold text-gray-600" @click="togglePublish(post.id)">Toggle publish</button>
                        <button type="button" class="text-xs font-bold text-red-500" @click="deletePost(post)">Delete</button>
                    </div>
                </div>
            </div>

            <div v-if="posts.links?.length > 3" class="flex flex-wrap justify-center gap-1">
                <Link
                    v-for="link in posts.links"
                    :key="link.label"
                    :href="link.url || '#'"
                    class="px-3 py-1.5 rounded-lg text-sm"
                    :class="link.active ? 'bg-primary text-white' : link.url ? 'bg-gray-100 hover:bg-gray-200' : 'text-gray-300 pointer-events-none'"
                    v-html="link.label"
                />
            </div>
        </div>
    </AuthenticatedLayout>
</template>
