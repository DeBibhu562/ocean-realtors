<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';

const props = defineProps({
    writer: { type: Object, required: true },
});

const photoPreview = ref(props.writer.photo || null);

const form = useForm({
    name: props.writer.name || '',
    bio: props.writer.bio || '',
    photo: null,
});

const onPhotoChange = (e) => {
    const file = e.target.files?.[0];
    if (!file) return;
    form.photo = file;
    photoPreview.value = URL.createObjectURL(file);
};

const submit = () => {
    form.transform((data) => ({ ...data, _method: 'put' })).post(
        route('admin.blog-writers.update', props.writer.id),
        { forceFormData: true, preserveScroll: true },
    );
};
</script>

<template>
    <Head :title="`Edit ${writer.name}`" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between gap-4">
                <div>
                    <h2 class="text-2xl font-black text-gray-900">Edit writer</h2>
                    <p class="text-sm text-gray-500 mt-1">Update display name, photo, and bio for blog bylines.</p>
                </div>
                <Link :href="route('admin.blog-writers.index')" class="text-sm font-semibold text-primary hover:underline">
                    ← All writers
                </Link>
            </div>
        </template>

        <form @submit.prevent="submit" class="max-w-xl">
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 space-y-5">
                <div class="flex flex-col items-center sm:flex-row sm:items-start gap-4">
                    <img
                        v-if="photoPreview"
                        :src="photoPreview"
                        :alt="form.name"
                        class="h-24 w-24 rounded-full object-cover ring-2 ring-gray-100 shrink-0"
                    />
                    <div
                        v-else
                        class="h-24 w-24 rounded-full bg-gray-100 flex items-center justify-center text-2xl font-bold text-gray-400 shrink-0"
                    >
                        {{ form.name?.charAt(0) || '?' }}
                    </div>
                    <div class="flex-1 w-full">
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
                    <TextInput v-model="form.name" class="w-full mt-1" required />
                    <InputError :message="form.errors.name" />
                </div>

                <div>
                    <InputLabel value="Bio" />
                    <textarea
                        v-model="form.bio"
                        rows="4"
                        maxlength="500"
                        class="w-full mt-1 rounded-xl border-gray-200 text-sm focus:border-primary focus:ring-primary"
                        placeholder="Short author bio shown on article pages"
                    />
                    <InputError :message="form.errors.bio" />
                </div>

                <PrimaryButton type="submit" :disabled="form.processing">
                    Save profile
                </PrimaryButton>
            </div>
        </form>
    </AuthenticatedLayout>
</template>
