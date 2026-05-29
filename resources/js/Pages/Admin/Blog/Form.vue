<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import RichTextEditor from '@/Components/Admin/RichTextEditor.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';

const props = defineProps({
    post: { type: Object, default: null },
    writers: { type: Array, default: () => [] },
});

const isEdit = !!props.post;
const featuredPreview = ref(props.post?.featured_image || null);
const ogPreview = ref(props.post?.og_image || null);

const form = useForm({
    title: props.post?.title || '',
    slug: props.post?.slug || '',
    excerpt: props.post?.excerpt || '',
    content: props.post?.content || '',
    status: props.post?.status || 'draft',
    meta_title: props.post?.meta_title || '',
    meta_description: props.post?.meta_description || '',
    meta_keywords: props.post?.meta_keywords || '',
    canonical_url: props.post?.canonical_url || '',
    noindex: props.post?.noindex ?? false,
    blog_writer_id: props.post?.blog_writer_id ?? props.writers[0]?.id ?? '',
    featured_image: null,
    og_image: null,
});

const metaTitleLen = computed(() => form.meta_title.length);
const metaDescLen = computed(() => form.meta_description.length);

const onFeaturedChange = (e) => {
    const file = e.target.files?.[0];
    if (!file) return;
    form.featured_image = file;
    featuredPreview.value = URL.createObjectURL(file);
};

const onOgChange = (e) => {
    const file = e.target.files?.[0];
    if (!file) return;
    form.og_image = file;
    ogPreview.value = URL.createObjectURL(file);
};

const submit = () => {
    const opts = { forceFormData: true, preserveScroll: true };
    if (isEdit) {
        form.transform((data) => ({ ...data, _method: 'put' })).post(route('admin.blog.update', props.post.id), opts);
    } else {
        form.post(route('admin.blog.store'), opts);
    }
};
</script>

<template>
    <Head :title="isEdit ? 'Edit post' : 'New post'" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between gap-4">
                <div>
                    <h2 class="text-2xl font-black text-gray-900">{{ isEdit ? 'Edit post' : 'New post' }}</h2>
                    <p class="text-sm text-gray-500 mt-1">Write content and configure SEO before publishing.</p>
                </div>
                <Link :href="route('admin.blog.index')" class="text-sm font-semibold text-primary hover:underline">← Back to blog</Link>
            </div>
        </template>

        <form @submit.prevent="submit" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 space-y-4">
                    <div>
                        <InputLabel value="Title *" />
                        <TextInput v-model="form.title" class="w-full mt-1 text-lg font-semibold" required />
                        <InputError :message="form.errors.title" />
                    </div>
                    <div>
                        <InputLabel value="Slug" />
                        <TextInput v-model="form.slug" class="w-full mt-1 font-mono text-sm" placeholder="best-property-agent-in-gurgaon" />
                        <p class="text-xs text-gray-400 mt-1">Use letters, numbers, and hyphens only (no leading slash). Auto-generated from the title if empty.</p>
                        <InputError :message="form.errors.slug" />
                    </div>
                    <div>
                        <InputLabel value="Content *" />
                        <RichTextEditor v-model="form.content" class="mt-2" />
                        <InputError :message="form.errors.content" class="mt-2" />
                    </div>
                    <div>
                        <InputLabel value="Excerpt" />
                        <textarea
                            v-model="form.excerpt"
                            rows="3"
                            class="w-full mt-1 rounded-xl border-gray-200 text-sm focus:border-primary focus:ring-primary"
                            placeholder="Short summary for cards (auto-generated from content if empty)"
                        />
                        <InputError :message="form.errors.excerpt" />
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 space-y-4">
                    <h3 class="text-sm font-bold uppercase tracking-wide text-gray-500">Publish</h3>
                    <div>
                        <InputLabel value="Display author *" />
                        <select
                            v-model="form.blog_writer_id"
                            class="w-full mt-1 rounded-xl border-gray-200 text-sm focus:border-primary focus:ring-primary"
                            required
                        >
                            <option v-for="writer in writers" :key="writer.id" :value="writer.id">
                                {{ writer.name }}
                            </option>
                        </select>
                        <div
                            v-if="writers.find((w) => w.id === form.blog_writer_id)"
                            class="mt-3 flex items-center gap-3 rounded-xl bg-gray-50 p-3"
                        >
                            <img
                                v-if="writers.find((w) => w.id === form.blog_writer_id)?.photo"
                                :src="writers.find((w) => w.id === form.blog_writer_id).photo"
                                alt=""
                                class="h-10 w-10 rounded-full object-cover"
                            />
                            <p class="text-xs text-gray-500 line-clamp-2">
                                {{ writers.find((w) => w.id === form.blog_writer_id)?.bio }}
                            </p>
                        </div>
                        <InputError :message="form.errors.blog_writer_id" />
                        <Link
                            :href="route('admin.blog-writers.index')"
                            class="mt-2 inline-block text-xs font-semibold text-primary hover:underline"
                        >
                            Manage writer profiles
                        </Link>
                    </div>
                    <div>
                        <InputLabel value="Status" />
                        <select v-model="form.status" class="w-full mt-1 rounded-xl border-gray-200 text-sm focus:border-primary focus:ring-primary">
                            <option value="draft">Draft</option>
                            <option value="published">Published</option>
                        </select>
                        <InputError :message="form.errors.status" />
                        <p class="text-xs text-gray-400 mt-1">Published posts go live immediately. Drafts stay hidden.</p>
                    </div>
                    <PrimaryButton type="submit" class="w-full justify-center" :disabled="form.processing">
                        {{ isEdit ? 'Update post' : 'Create post' }}
                    </PrimaryButton>
                </div>

                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 space-y-4">
                    <h3 class="text-sm font-bold uppercase tracking-wide text-gray-500">Featured image</h3>
                    <img v-if="featuredPreview" :src="featuredPreview" alt="" class="w-full aspect-video object-cover rounded-xl" />
                    <input type="file" accept="image/*" class="block w-full text-xs" @change="onFeaturedChange" />
                    <InputError :message="form.errors.featured_image" />
                </div>

                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 space-y-4">
                    <h3 class="text-sm font-bold uppercase tracking-wide text-gray-500">SEO</h3>
                    <div>
                        <div class="flex justify-between items-center">
                            <InputLabel value="Meta title" />
                            <span class="text-xs" :class="metaTitleLen > 70 ? 'text-red-500' : 'text-gray-400'">{{ metaTitleLen }}/70</span>
                        </div>
                        <TextInput v-model="form.meta_title" class="w-full mt-1" maxlength="70" />
                        <InputError :message="form.errors.meta_title" />
                    </div>
                    <div>
                        <div class="flex justify-between items-center">
                            <InputLabel value="Meta description" />
                            <span class="text-xs" :class="metaDescLen > 160 ? 'text-red-500' : 'text-gray-400'">{{ metaDescLen }}/160</span>
                        </div>
                        <textarea v-model="form.meta_description" rows="3" maxlength="160" class="w-full mt-1 rounded-xl border-gray-200 text-sm focus:border-primary focus:ring-primary" />
                        <InputError :message="form.errors.meta_description" />
                    </div>
                    <div>
                        <InputLabel value="Meta keywords" />
                        <TextInput v-model="form.meta_keywords" class="w-full mt-1" placeholder="gurgaon, real estate, rent" />
                    </div>
                    <div>
                        <InputLabel value="OG image" />
                        <img v-if="ogPreview" :src="ogPreview" alt="" class="w-full aspect-video object-cover rounded-xl mb-2" />
                        <input type="file" accept="image/*" class="block w-full text-xs" @change="onOgChange" />
                        <p class="text-xs text-gray-400 mt-1">Falls back to featured image if empty.</p>
                        <InputError :message="form.errors.og_image" />
                    </div>
                    <div>
                        <InputLabel value="Canonical URL" />
                        <TextInput v-model="form.canonical_url" type="url" class="w-full mt-1" placeholder="https://..." />
                        <InputError :message="form.errors.canonical_url" />
                    </div>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input v-model="form.noindex" type="checkbox" class="rounded border-gray-300 text-primary focus:ring-primary" />
                        <span class="text-sm text-gray-700">Noindex (hide from search engines)</span>
                    </label>
                </div>
            </div>
        </form>
    </AuthenticatedLayout>
</template>
