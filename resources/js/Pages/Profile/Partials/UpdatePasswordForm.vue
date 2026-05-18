<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const passwordInput = ref(null);
const currentPasswordInput = ref(null);

const form = useForm({
    current_password: '',
    password: '',
    password_confirmation: '',
});

const updatePassword = () => {
    form.put(route('password.update'), {
        preserveScroll: true,
        onSuccess: () => form.reset(),
        onError: () => {
            if (form.errors.password) {
                form.reset('password', 'password_confirmation');
                passwordInput.value.focus();
            }
            if (form.errors.current_password) {
                form.reset('current_password');
                currentPasswordInput.value.focus();
            }
        },
    });
};
</script>

<template>
    <section>
        <form @submit.prevent="updatePassword" class="space-y-6">
            <div class="space-y-4">
                <div>
                    <InputLabel for="current_password" value="Current Password" class="text-xs font-black uppercase tracking-widest text-gray-400 mb-2" />
                    <TextInput
                        id="current_password"
                        ref="currentPasswordInput"
                        v-model="form.current_password"
                        type="password"
                        class="mt-1 block w-full bg-gray-50/50 border-gray-100 rounded-2xl py-3 focus:ring-primary/20 focus:border-primary transition-all font-medium"
                        autocomplete="current-password"
                    />
                    <InputError :message="form.errors.current_password" class="mt-2" />
                </div>

                <div>
                    <InputLabel for="password" value="New Password" class="text-xs font-black uppercase tracking-widest text-gray-400 mb-2" />
                    <TextInput
                        id="password"
                        ref="passwordInput"
                        v-model="form.password"
                        type="password"
                        class="mt-1 block w-full bg-gray-50/50 border-gray-100 rounded-2xl py-3 focus:ring-primary/20 focus:border-primary transition-all font-medium"
                        autocomplete="new-password"
                    />
                    <InputError :message="form.errors.password" class="mt-2" />
                </div>

                <div>
                    <InputLabel for="password_confirmation" value="Confirm New Password" class="text-xs font-black uppercase tracking-widest text-gray-400 mb-2" />
                    <TextInput
                        id="password_confirmation"
                        v-model="form.password_confirmation"
                        type="password"
                        class="mt-1 block w-full bg-gray-50/50 border-gray-100 rounded-2xl py-3 focus:ring-primary/20 focus:border-primary transition-all font-medium"
                        autocomplete="new-password"
                    />
                    <InputError :message="form.errors.password_confirmation" class="mt-2" />
                </div>
            </div>

            <div class="flex items-center gap-4 pt-4">
                <PrimaryButton :disabled="form.processing" class="px-10 py-3 rounded-xl shadow-lg shadow-primary/20 text-xs font-black uppercase tracking-widest bg-navy hover:bg-navy-dark">
                    Secure Password
                </PrimaryButton>

                <Transition
                    enter-active-class="transition ease-in-out"
                    enter-from-class="opacity-0"
                    leave-active-class="transition ease-in-out"
                    leave-to-class="opacity-0"
                >
                    <p v-if="form.recentlySuccessful" class="text-sm font-bold text-green-600">Password secured!</p>
                </Transition>
            </div>
        </form>
    </section>
</template>
