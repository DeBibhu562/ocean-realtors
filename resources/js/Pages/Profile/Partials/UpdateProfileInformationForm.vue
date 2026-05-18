<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Link, useForm, usePage } from '@inertiajs/vue3';

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const user = usePage().props.auth.user;

const form = useForm({
    name: user.name,
    email: user.email,
});
</script>

<template>
    <section>
        <form
            @submit.prevent="form.patch(route('profile.update'))"
            class="space-y-6"
        >
            <div class="space-y-4">
                <div>
                    <InputLabel for="name" value="Full Name" class="text-xs font-black uppercase tracking-widest text-gray-400 mb-2" />
                    <TextInput
                        id="name"
                        type="text"
                        class="mt-1 block w-full bg-gray-50/50 border-gray-100 rounded-2xl py-3 focus:ring-primary/20 focus:border-primary transition-all font-medium"
                        v-model="form.name"
                        required
                        autofocus
                        autocomplete="name"
                    />
                    <InputError class="mt-2" :message="form.errors.name" />
                </div>

                <div>
                    <InputLabel for="email" value="Email Address" class="text-xs font-black uppercase tracking-widest text-gray-400 mb-2" />
                    <TextInput
                        id="email"
                        type="email"
                        class="mt-1 block w-full bg-gray-50/50 border-gray-100 rounded-2xl py-3 focus:ring-primary/20 focus:border-primary transition-all font-medium"
                        v-model="form.email"
                        required
                        autocomplete="username"
                    />
                    <InputError class="mt-2" :message="form.errors.email" />
                </div>
            </div>

            <div v-if="mustVerifyEmail && user.email_verified_at === null">
                <p class="mt-2 text-sm text-amber-600 font-medium">
                    Your email address is unverified.
                    <Link
                        :href="route('verification.send')"
                        method="post"
                        as="button"
                        class="ml-2 underline hover:text-amber-700 transition-colors"
                    >
                        Re-send verification email.
                    </Link>
                </p>

                <div
                    v-show="status === 'verification-link-sent'"
                    class="mt-2 text-sm font-bold text-green-600"
                >
                    A new verification link has been sent.
                </div>
            </div>

            <div class="flex items-center gap-4 pt-4">
                <PrimaryButton :disabled="form.processing" class="px-10 py-3 rounded-xl shadow-lg shadow-primary/20 text-xs font-black uppercase tracking-widest">
                    Update Information
                </PrimaryButton>

                <Transition
                    enter-active-class="transition ease-in-out"
                    enter-from-class="opacity-0"
                    leave-active-class="transition ease-in-out"
                    leave-to-class="opacity-0"
                >
                    <p v-if="form.recentlySuccessful" class="text-sm font-bold text-green-600">Saved successfully!</p>
                </Transition>
            </div>
        </form>
    </section>
</template>
