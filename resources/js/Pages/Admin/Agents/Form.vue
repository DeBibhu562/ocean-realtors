<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';

const props = defineProps({
    agent: { type: Object, default: null },
    properties: { type: Array, default: () => [] },
    assignedPropertyIds: { type: Array, default: () => [] },
});

const isEdit = !!props.agent;
const languageInput = ref('');
const avatarPreview = ref(props.agent?.avatar || null);

const form = useForm({
    name: props.agent?.name || '',
    slug: props.agent?.slug || '',
    email: props.agent?.email || '',
    phone: props.agent?.phone || '',
    whatsapp_phone: props.agent?.whatsapp_phone || '',
    designation: props.agent?.designation || 'Property Consultant',
    bio: props.agent?.bio || '',
    city: props.agent?.city || '',
    rating: props.agent?.rating ?? 4.8,
    reviews_count: props.agent?.reviews_count ?? 0,
    experience_years: props.agent?.experience_years ?? 5,
    languages: props.agent?.languages?.length ? [...props.agent.languages] : ['English', 'Hindi'],
    is_active: props.agent?.is_active ?? true,
    avatar: null,
    property_ids: [...(props.assignedPropertyIds || [])],
});

const onAvatarChange = (e) => {
    const file = e.target.files?.[0];
    if (!file) return;
    form.avatar = file;
    avatarPreview.value = URL.createObjectURL(file);
};

const addLanguage = () => {
    const lang = languageInput.value.trim();
    if (!lang || form.languages.includes(lang)) return;
    form.languages.push(lang);
    languageInput.value = '';
};

const removeLanguage = (lang) => {
    form.languages = form.languages.filter((l) => l !== lang);
};

const submit = () => {
    if (isEdit) {
        form.transform((data) => ({ ...data, _method: 'put' })).post(route('admin.agents.update', props.agent.slug), {
            forceFormData: true,
        });
    } else {
        form.post(route('admin.agents.store'), { forceFormData: true });
    }
};
</script>

<template>
    <Head :title="isEdit ? 'Edit Agent' : 'Add Agent'" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between gap-4">
                <div>
                    <h2 class="text-2xl font-black text-gray-900">{{ isEdit ? 'Edit Agent' : 'Add Agent' }}</h2>
                    <p class="text-sm text-gray-500 mt-1">Profile details shown on property listings.</p>
                </div>
                <Link :href="route('admin.agents.index')" class="text-sm font-semibold text-primary hover:underline">← Back to agents</Link>
            </div>
        </template>

        <form @submit.prevent="submit" class="max-w-3xl space-y-6">
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 space-y-6">
                <div class="flex flex-col sm:flex-row gap-6 items-start">
                    <div class="shrink-0">
                        <img :src="avatarPreview || 'https://ui-avatars.com/api/?name=Agent&background=1a56db&color=fff'" class="w-24 h-24 rounded-2xl object-cover ring-4 ring-gray-50" alt="" />
                        <label class="mt-3 block">
                            <span class="text-xs font-bold text-gray-500 uppercase tracking-wide">Photo</span>
                            <input type="file" accept="image/*" class="mt-1 block w-full text-xs" @change="onAvatarChange" />
                        </label>
                        <InputError :message="form.errors.avatar" />
                    </div>
                    <div class="flex-1 grid grid-cols-1 sm:grid-cols-2 gap-4 w-full">
                        <div class="sm:col-span-2">
                            <InputLabel value="Full name *" />
                            <TextInput v-model="form.name" class="w-full mt-1" required />
                            <InputError :message="form.errors.name" />
                        </div>
                        <div>
                            <InputLabel value="Phone *" />
                            <TextInput v-model="form.phone" class="w-full mt-1" required />
                            <InputError :message="form.errors.phone" />
                        </div>
                        <div>
                            <InputLabel value="WhatsApp" />
                            <TextInput v-model="form.whatsapp_phone" class="w-full mt-1" placeholder="Same as phone if empty" />
                        </div>
                        <div>
                            <InputLabel value="Email" />
                            <TextInput v-model="form.email" type="email" class="w-full mt-1" />
                        </div>
                        <div>
                            <InputLabel value="City" />
                            <TextInput v-model="form.city" class="w-full mt-1" />
                        </div>
                        <div class="sm:col-span-2">
                            <InputLabel value="Designation" />
                            <TextInput v-model="form.designation" class="w-full mt-1" />
                        </div>
                    </div>
                </div>

                <div>
                    <InputLabel value="Bio" />
                    <textarea v-model="form.bio" rows="4" class="w-full mt-1 rounded-xl border-gray-200 focus:border-primary focus:ring-primary text-sm" placeholder="Short introduction for clients..."></textarea>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <InputLabel value="Rating (1-5)" />
                        <TextInput v-model="form.rating" type="number" step="0.1" min="1" max="5" class="w-full mt-1" />
                    </div>
                    <div>
                        <InputLabel value="Reviews count" />
                        <TextInput v-model="form.reviews_count" type="number" min="0" class="w-full mt-1" />
                    </div>
                    <div>
                        <InputLabel value="Experience (years)" />
                        <TextInput v-model="form.experience_years" type="number" min="0" class="w-full mt-1" />
                    </div>
                </div>

                <div>
                    <InputLabel value="Languages" />
                    <div class="flex gap-2 mt-1">
                        <TextInput v-model="languageInput" class="flex-1" placeholder="Add language" @keyup.enter.prevent="addLanguage" />
                        <SecondaryButton type="button" @click="addLanguage">Add</SecondaryButton>
                    </div>
                    <div class="flex flex-wrap gap-2 mt-2">
                        <span v-for="lang in form.languages" :key="lang" class="inline-flex items-center gap-1 px-3 py-1 bg-primary/10 text-primary rounded-full text-xs font-semibold">
                            {{ lang }}
                            <button type="button" @click="removeLanguage(lang)" class="hover:text-red-600">×</button>
                        </span>
                    </div>
                </div>

                <div v-if="isEdit && properties.length" class="space-y-3 border-t border-gray-100 pt-6">
                    <InputLabel value="Assigned properties" />
                    <p class="text-xs text-gray-500">Link listings to this agent. Unselected properties previously assigned here move to the default agency agent.</p>
                    <div class="max-h-56 overflow-y-auto rounded-xl border border-gray-200 divide-y">
                        <label
                            v-for="property in properties"
                            :key="property.id"
                            class="flex items-start gap-3 px-4 py-3 hover:bg-gray-50 cursor-pointer"
                        >
                            <input
                                v-model="form.property_ids"
                                type="checkbox"
                                :value="property.id"
                                class="mt-1 rounded text-primary focus:ring-primary"
                            />
                            <span class="text-sm">
                                <span class="font-semibold text-gray-900">{{ property.title }}</span>
                                <span v-if="property.city" class="text-gray-500"> · {{ property.city }}</span>
                            </span>
                        </label>
                    </div>
                </div>

                <label class="flex items-center gap-2">
                    <input type="checkbox" v-model="form.is_active" class="rounded text-primary focus:ring-primary" />
                    <span class="text-sm font-semibold text-gray-700">Active (visible on site & assignable to listings)</span>
                </label>
            </div>

            <div class="flex flex-col sm:flex-row gap-3">
                <PrimaryButton :disabled="form.processing" class="justify-center sm:flex-1">
                    {{ isEdit ? 'Save changes' : 'Create agent' }}
                </PrimaryButton>
                <Link :href="route('admin.agents.index')" class="sm:flex-1">
                    <SecondaryButton type="button" class="w-full justify-center">Cancel</SecondaryButton>
                </Link>
            </div>
        </form>
    </AuthenticatedLayout>
</template>
