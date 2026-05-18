<script setup>
import DangerButton from '@/Components/DangerButton.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import Modal from '@/Components/Modal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { useForm } from '@inertiajs/vue3';
import { nextTick, ref } from 'vue';

const confirmingUserDeletion = ref(false);
const passwordInput = ref(null);

const form = useForm({
    password: '',
});

const confirmUserDeletion = () => {
    confirmingUserDeletion.value = true;

    nextTick(() => passwordInput.value.focus());
};

const deleteUser = () => {
    form.delete(route('profile.destroy'), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
        onError: () => passwordInput.value.focus(),
        onFinish: () => form.reset(),
    });
};

const closeModal = () => {
    confirmingUserDeletion.value = false;

    form.clearErrors();
    form.reset();
};
</script>

<template>
    <section>
        <DangerButton @click="confirmUserDeletion" class="px-8 py-3 rounded-xl shadow-lg shadow-red-500/20 text-xs font-black uppercase tracking-widest">
            Deactivate Account
        </DangerButton>

        <Modal :show="confirmingUserDeletion" @close="closeModal">
            <div class="p-10">
                <h2 class="text-2xl font-black text-gray-900 leading-tight">
                    Permanent Account Deletion
                </h2>

                <p class="mt-4 text-sm text-gray-500 font-medium leading-relaxed">
                    Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.
                </p>

                <div class="mt-8">
                    <InputLabel for="password" value="Password" class="sr-only" />

                    <TextInput
                        id="password"
                        ref="passwordInput"
                        v-model="form.password"
                        type="password"
                        class="block w-full bg-gray-50 border-gray-100 rounded-2xl py-3 focus:ring-red-500/20 focus:border-red-500 transition-all font-medium"
                        placeholder="Enter your password to confirm"
                        @keyup.enter="deleteUser"
                    />

                    <InputError :message="form.errors.password" class="mt-2" />
                </div>

                <div class="mt-10 flex justify-end gap-4">
                    <SecondaryButton @click="closeModal" class="px-8 py-3 rounded-xl text-xs font-black uppercase tracking-widest border-gray-200">
                        Cancel
                    </SecondaryButton>

                    <DangerButton
                        class="px-8 py-3 rounded-xl shadow-lg shadow-red-500/20 text-xs font-black uppercase tracking-widest"
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                        @click="deleteUser"
                    >
                        Confirm Deletion
                    </DangerButton>
                </div>
            </div>
        </Modal>
    </section>
</template>
