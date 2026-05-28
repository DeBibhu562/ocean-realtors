<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';

const photoPreview = ref(null);

const form = useForm({
    name: '',
    bio: '',
    photo: null,
});

const onPhotoChange = (e) => {
    const file = e.target.files?.[0];
    if (!file) return;
    form.photo = file;
    photoPreview.value = URL.createObjectURL(file);
};

const submit = () => {
    form.post(route('admin.blog-writers.store'), {
        forceFormData: true,
        preserveScroll: true,
    });
};
</script>

<template>
    <Head title="Create writer" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between gap-4">
                <div>
                    <h2 class="text-2xl font-black text-gray-900">Create writer</h2>
                    <p class="mt-1 text-sm text-gray-500">Add a new author profile for blog cards and article pages.</p>
                </div>
                <Link :href="route('admin.blog-writers.index')" class="text-sm font-semibold text-primary hover:underline">
                    ← All writers
                </Link>
            </div>
        </template>

        <form class="max-w-xl" @submit.prevent="submit">
            <div class="space-y-5 rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
                <div class="flex flex-col items-center gap-4 sm:flex-row sm:items-start">
                    <img
                        v-if="photoPreview"
                        :src="photoPreview"
                        :alt="form.name"
                        class="h-24 w-24 shrink-0 rounded-full object-cover ring-2 ring-gray-100"
                    />
                    <div
                        v-else
                        class="flex h-24 w-24 shrink-0 items-center justify-center rounded-full bg-gray-100 text-2xl font-bold text-gray-400"
                    >
                        {{ form.name?.charAt(0) || '?' }}
                    </div>
                    <div class="w-full flex-1">
                        <InputLabel value="Profile photo" />
                        <input
                            type="file"
                            accept="image/*"
                            class="mt-1 block w-full text-xs text-gray-500"
                            @change="onPhotoChange"
                        />
                        <InputError :message="form.errors.photo" class="mt-1" />
                    </div>
                </div>

                <div>
                    <InputLabel value="Display name *" />
                    <TextInput v-model="form.name" class="mt-1 w-full" required />
                    <InputError :message="form.errors.name" />
                </div>

                <div>
                    <InputLabel value="Bio" />
                    <textarea
                        v-model="form.bio"
                        rows="4"
                        maxlength="500"
                        class="mt-1 w-full rounded-xl border-gray-200 text-sm focus:border-primary focus:ring-primary"
                        placeholder="Short author bio shown on article pages"
                    />
                    <InputError :message="form.errors.bio" />
                </div>

                <PrimaryButton type="submit" :disabled="form.processing">
                    Create writer
                </PrimaryButton>
            </div>
        </form>
    </AuthenticatedLayout>
</template>
