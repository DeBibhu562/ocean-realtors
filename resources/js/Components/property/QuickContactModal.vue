<script setup>
import { reactive, ref, computed, watch } from 'vue';
import axios from 'axios';

const props = defineProps({
    show: { type: Boolean, default: false },
    channel: { type: String, default: 'whatsapp' },
    agentName: { type: String, default: '' },
    propertyId: { type: Number, required: true },
    propertyTitle: { type: String, default: '' },
    whatsappHref: { type: String, default: '#' },
    telHref: { type: String, default: '#' },
});

const emit = defineEmits(['close', 'success']);

const form = reactive({
    name: '',
    dialCode: '+91',
    phoneLocal: '',
});

const errors = reactive({ name: '', phoneLocal: '' });
const submitting = ref(false);
const dialOptions = ['+91', '+1', '+44', '+971'];

const fullPhone = computed(() => `${form.dialCode}${form.phoneLocal.replace(/\D/g, '')}`);

const title = computed(() => (props.channel === 'call' ? 'Call agent' : 'WhatsApp agent'));

const subtitle = computed(() =>
    props.channel === 'call'
        ? `Enter your details to call ${props.agentName} and so we can follow up if needed.`
        : `Enter your details to message ${props.agentName} on WhatsApp with this listing.`,
);

watch(
    () => props.show,
    (visible) => {
        if (!visible) return;
        errors.name = '';
        errors.phoneLocal = '';
        submitting.value = false;
        try {
            const saved = JSON.parse(sessionStorage.getItem('ocean_contact_profile') || '{}');
            if (saved.name) form.name = saved.name;
            if (saved.dialCode) form.dialCode = saved.dialCode;
            if (saved.phoneLocal) form.phoneLocal = saved.phoneLocal;
        } catch {
            /* ignore */
        }
    },
);

const validate = () => {
    let ok = true;
    errors.name = '';
    errors.phoneLocal = '';
    if (!form.name.trim()) {
        errors.name = 'Please enter your name.';
        ok = false;
    }
    if (!form.phoneLocal.trim() || form.phoneLocal.replace(/\D/g, '').length < 6) {
        errors.phoneLocal = 'Enter a valid phone number.';
        ok = false;
    }
    return ok;
};

const proceed = async () => {
    if (!validate()) return;
    submitting.value = true;
    try {
        sessionStorage.setItem(
            'ocean_contact_profile',
            JSON.stringify({
                name: form.name.trim(),
                dialCode: form.dialCode,
                phoneLocal: form.phoneLocal,
            }),
        );
        sessionStorage.setItem(`ocean_contact_ok_${props.propertyId}`, '1');

        await axios.post('/api/leads', {
            property_id: props.propertyId,
            name: form.name.trim(),
            phone: fullPhone.value,
            message: `Quick ${props.channel} from property page: ${props.propertyTitle}`,
            source: 'web',
            contact_channel: props.channel,
        });

        emit('success', { channel: props.channel });
        if (props.channel === 'call' && props.telHref && props.telHref !== '#') {
            window.location.href = props.telHref;
        } else if (props.whatsappHref && props.whatsappHref !== '#') {
            window.open(props.whatsappHref, '_blank', 'noopener,noreferrer');
        }
        emit('close');
    } catch (e) {
        const msg =
            e.response?.data?.message ||
            (e.response?.status === 422
                ? Object.values(e.response.data.errors || {})
                      .flat()
                      .join(' ')
                : 'Could not save your details. Please try again.');
        errors.phoneLocal = msg;
    } finally {
        submitting.value = false;
    }
};
</script>

<template>
    <Teleport to="body">
        <div
            v-if="show"
            class="fixed inset-0 z-[100] flex items-end sm:items-center justify-center p-0 sm:p-4"
            role="dialog"
            aria-modal="true"
            :aria-label="title"
        >
            <div class="absolute inset-0 bg-navy/60 backdrop-blur-sm" @click="emit('close')" />
            <div
                class="relative w-full max-w-md rounded-t-2xl sm:rounded-2xl bg-white shadow-2xl p-6 sm:p-8"
                @click.stop
            >
                <button
                    type="button"
                    class="absolute right-4 top-4 rounded-lg p-1 text-primary/40 hover:bg-primary/5 hover:text-primary"
                    aria-label="Close"
                    @click="emit('close')"
                >
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

                <h3 class="text-lg font-bold text-primary pr-8">{{ title }}</h3>
                <p class="mt-1 text-sm text-primary/60">{{ subtitle }}</p>

                <form class="mt-6 space-y-4" @submit.prevent="proceed">
                    <div>
                        <label for="qc-name" class="mb-1 block text-xs font-bold uppercase tracking-wide text-primary/50">Your name *</label>
                        <input
                            id="qc-name"
                            v-model="form.name"
                            type="text"
                            autocomplete="name"
                            class="w-full rounded-xl border border-primary/15 px-3 py-2.5 text-sm outline-none focus:ring-2 focus:ring-accent/30"
                        />
                        <p v-if="errors.name" class="mt-1 text-xs font-medium text-red-600">{{ errors.name }}</p>
                    </div>

                    <div>
                        <label for="qc-phone" class="mb-1 block text-xs font-bold uppercase tracking-wide text-primary/50">Your phone *</label>
                        <div class="flex gap-2">
                            <select
                                v-model="form.dialCode"
                                class="w-24 shrink-0 rounded-xl border border-primary/15 px-2 py-2.5 text-sm"
                                aria-label="Country code"
                            >
                                <option v-for="d in dialOptions" :key="d" :value="d">{{ d }}</option>
                            </select>
                            <input
                                id="qc-phone"
                                v-model="form.phoneLocal"
                                type="tel"
                                inputmode="tel"
                                autocomplete="tel-national"
                                class="min-w-0 flex-1 rounded-xl border border-primary/15 px-3 py-2.5 text-sm outline-none focus:ring-2 focus:ring-accent/30"
                                placeholder="9876543210"
                            />
                        </div>
                        <p v-if="errors.phoneLocal" class="mt-1 text-xs font-medium text-red-600">{{ errors.phoneLocal }}</p>
                    </div>

                    <button
                        type="submit"
                        class="flex w-full items-center justify-center gap-2 rounded-xl py-3 text-sm font-bold text-white transition disabled:opacity-60"
                        :class="channel === 'call' ? 'bg-primary hover:bg-primary-hover' : 'bg-emerald-500 hover:bg-emerald-600'"
                        :disabled="submitting"
                    >
                        {{ submitting ? 'Please wait...' : channel === 'call' ? 'Continue to call' : 'Continue to WhatsApp' }}
                    </button>
                </form>
            </div>
        </div>
    </Teleport>
</template>
