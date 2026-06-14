<script setup>
import { reactive, ref, computed, watch } from 'vue';
import axios from 'axios';

const props = defineProps({
    show: { type: Boolean, default: false },
    channel: { type: String, default: 'whatsapp' },
    agentName: { type: String, default: '' },
    propertyId: { type: Number, default: null },
    agentId: { type: Number, default: null },
    propertyTitle: { type: String, default: '' },
    whatsappHref: { type: String, default: '#' },
    telHref: { type: String, default: '#' },
});

const emit = defineEmits(['close', 'success']);

const STORAGE_KEY = 'ocean_contact_profile';

const form = reactive({
    name: '',
    email: '',
    dialCode: '+91',
    phoneLocal: '',
    isRealEstateAgent: null,
});

const errors = reactive({
    name: '',
    email: '',
    phoneLocal: '',
    isRealEstateAgent: '',
    form: '',
});

const submitting = ref(false);
const dialOptions = ['+91', '+1', '+44', '+971'];

const fullPhone = computed(() => `${form.dialCode}${form.phoneLocal.replace(/\D/g, '')}`);

const isAgentProfile = computed(() => !!props.agentId && !props.propertyId);

const channelMeta = computed(() => {
    const name = props.agentName || 'the agent';

    if (isAgentProfile.value) {
        switch (props.channel) {
            case 'call':
                return {
                    title: 'Call agent',
                    subtitle: `Share your details to call ${name}.`,
                    cta: 'Continue to call',
                    accent: 'bg-primary hover:bg-primary-hover',
                };
            case 'view_phone':
                return {
                    title: 'View phone number',
                    subtitle: `Enter your details to see ${name}'s contact number.`,
                    cta: 'View number',
                    accent: 'bg-slate-800 hover:bg-slate-900',
                };
            default:
                return {
                    title: 'WhatsApp agent',
                    subtitle: `Share your details to message ${name} on WhatsApp.`,
                    cta: 'Continue to WhatsApp',
                    accent: 'bg-emerald-500 hover:bg-emerald-600',
                };
        }
    }

    switch (props.channel) {
        case 'call':
            return {
                title: 'Call agent',
                subtitle: `Share your details to call ${name} about this listing.`,
                cta: 'Continue to call',
                accent: 'bg-primary hover:bg-primary-hover',
            };
        case 'view_phone':
            return {
                title: 'View phone number',
                subtitle: 'Enter your details to see the agent’s contact number for this property.',
                cta: 'View number',
                accent: 'bg-slate-800 hover:bg-slate-900',
            };
        default:
            return {
                title: 'WhatsApp agent',
                subtitle: `Share your details to message ${name} on WhatsApp with this listing.`,
                cta: 'Continue to WhatsApp',
                accent: 'bg-emerald-500 hover:bg-emerald-600',
            };
    }
});

const disclaimerText = computed(() =>
    isAgentProfile.value
        ? 'By continuing, you agree we may contact you about your enquiry. Your details are shared with this agent.'
        : 'By continuing, you agree we may contact you about this property. Your details are shared with the listing agent.',
);

const contactStorageKey = computed(() => {
    if (props.propertyId) {
        return `ocean_contact_ok_${props.propertyId}`;
    }
    if (props.agentId) {
        return `ocean_contact_ok_agent_${props.agentId}`;
    }
    return null;
});

const loadSavedProfile = () => {
    try {
        const saved = JSON.parse(sessionStorage.getItem(STORAGE_KEY) || '{}');
        if (saved.name) form.name = saved.name;
        if (saved.email) form.email = saved.email;
        if (saved.dialCode) form.dialCode = saved.dialCode;
        if (saved.phoneLocal) form.phoneLocal = saved.phoneLocal;
        if (saved.isRealEstateAgent === true || saved.isRealEstateAgent === false) {
            form.isRealEstateAgent = saved.isRealEstateAgent;
        }
    } catch {
        /* ignore */
    }
};

watch(
    () => props.show,
    (visible) => {
        if (!visible) return;
        errors.name = '';
        errors.email = '';
        errors.phoneLocal = '';
        errors.isRealEstateAgent = '';
        errors.form = '';
        submitting.value = false;
        loadSavedProfile();
    },
);

const validate = () => {
    let ok = true;
    errors.name = '';
    errors.email = '';
    errors.phoneLocal = '';
    errors.isRealEstateAgent = '';
    errors.form = '';

    if (!form.name.trim()) {
        errors.name = 'Please enter your name.';
        ok = false;
    }
    const email = form.email.trim();
    if (!email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
        errors.email = 'Enter a valid email address.';
        ok = false;
    }
    if (!form.phoneLocal.trim() || form.phoneLocal.replace(/\D/g, '').length < 6) {
        errors.phoneLocal = 'Enter a valid phone number.';
        ok = false;
    }
    if (form.isRealEstateAgent !== true && form.isRealEstateAgent !== false) {
        errors.isRealEstateAgent = 'Please select Yes or No.';
        ok = false;
    }

    return ok;
};

const channelLabel = () => {
    if (props.channel === 'call') return 'Phone call';
    if (props.channel === 'view_phone') return 'View phone number';
    return 'WhatsApp';
};

const buildLeadMessage = () => {
    if (isAgentProfile.value) {
        return `Agent profile contact (${channelLabel()}): ${props.agentName || 'Agent'}`;
    }
    return `Property contact (${channelLabel()}): ${props.propertyTitle}`;
};

const proceed = async () => {
    if (!validate()) return;
    submitting.value = true;
    errors.form = '';

    const profile = {
        name: form.name.trim(),
        email: form.email.trim(),
        dialCode: form.dialCode,
        phoneLocal: form.phoneLocal,
        isRealEstateAgent: form.isRealEstateAgent,
    };

    try {
        sessionStorage.setItem(STORAGE_KEY, JSON.stringify(profile));
        if (contactStorageKey.value) {
            sessionStorage.setItem(contactStorageKey.value, '1');
        }

        const payload = {
            name: profile.name,
            email: profile.email,
            phone: fullPhone.value,
            is_real_estate_agent: profile.isRealEstateAgent,
            message: buildLeadMessage(),
            source: 'web',
            contact_channel: props.channel,
        };

        if (props.propertyId) {
            payload.property_id = props.propertyId;
        } else if (props.agentId) {
            payload.agent_id = props.agentId;
        }

        const { data } = await axios.post('/api/leads', payload);

        emit('success', {
            channel: props.channel,
            contact: data.contact ?? null,
        });
        emit('close');
    } catch (e) {
        const msg =
            e.response?.data?.message ||
            (e.response?.status === 422
                ? Object.values(e.response.data.errors || {})
                      .flat()
                      .join(' ')
                : 'Could not save your details. Please try again.');
        errors.form = msg;
    } finally {
        submitting.value = false;
    }
};

const setAgentAnswer = (value) => {
    form.isRealEstateAgent = value;
    errors.isRealEstateAgent = '';
};
</script>

<template>
    <Teleport to="body">
        <div
            v-if="show"
            class="fixed inset-0 z-[100] flex items-end justify-center p-0 sm:items-center sm:p-4"
            role="dialog"
            aria-modal="true"
            :aria-label="channelMeta.title"
        >
            <div class="absolute inset-0 bg-navy/60 backdrop-blur-sm" @click="emit('close')" />
            <div
                class="relative flex max-h-[92dvh] w-full max-w-md flex-col overflow-hidden rounded-t-2xl bg-white shadow-2xl sm:max-h-[90vh] sm:rounded-2xl"
                @click.stop
            >
                <div class="border-b border-gray-100 bg-gradient-to-br from-primary/5 to-white px-5 pb-4 pt-5 sm:px-6 sm:pt-6">
                    <button
                        type="button"
                        class="absolute right-3 top-3 rounded-lg p-1.5 text-slate-400 transition hover:bg-slate-100 hover:text-slate-700"
                        aria-label="Close"
                        @click="emit('close')"
                    >
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                    <p class="text-[10px] font-bold uppercase tracking-widest text-primary">Quick enquiry</p>
                    <h3 class="mt-1 pr-8 text-lg font-bold text-navy">{{ channelMeta.title }}</h3>
                    <p class="mt-1 text-sm leading-relaxed text-text-secondary">{{ channelMeta.subtitle }}</p>
                </div>

                <form class="flex-1 space-y-4 overflow-y-auto px-5 py-5 sm:px-6" @submit.prevent="proceed">
                    <div>
                        <label for="qc-name" class="mb-1.5 block text-xs font-bold text-navy">Full name <span class="text-red-500">*</span></label>
                        <input
                            id="qc-name"
                            v-model="form.name"
                            type="text"
                            autocomplete="name"
                            class="w-full rounded-xl border border-gray-200 px-3.5 py-2.5 text-sm text-navy outline-none transition focus:border-primary focus:ring-2 focus:ring-primary/20"
                            placeholder="Your name"
                        />
                        <p v-if="errors.name" class="mt-1 text-xs font-medium text-red-600">{{ errors.name }}</p>
                    </div>

                    <div>
                        <label for="qc-email" class="mb-1.5 block text-xs font-bold text-navy">Email <span class="text-red-500">*</span></label>
                        <input
                            id="qc-email"
                            v-model="form.email"
                            type="email"
                            autocomplete="email"
                            inputmode="email"
                            class="w-full rounded-xl border border-gray-200 px-3.5 py-2.5 text-sm text-navy outline-none transition focus:border-primary focus:ring-2 focus:ring-primary/20"
                            placeholder="you@example.com"
                        />
                        <p v-if="errors.email" class="mt-1 text-xs font-medium text-red-600">{{ errors.email }}</p>
                    </div>

                    <div>
                        <label for="qc-phone" class="mb-1.5 block text-xs font-bold text-navy">Phone number <span class="text-red-500">*</span></label>
                        <div class="flex gap-2">
                            <select
                                v-model="form.dialCode"
                                class="w-[5.5rem] shrink-0 rounded-xl border border-gray-200 px-2 py-2.5 text-sm text-navy focus:border-primary focus:ring-2 focus:ring-primary/20"
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
                                class="min-w-0 flex-1 rounded-xl border border-gray-200 px-3.5 py-2.5 text-sm text-navy outline-none transition focus:border-primary focus:ring-2 focus:ring-primary/20"
                                placeholder="9876543210"
                            />
                        </div>
                        <p v-if="errors.phoneLocal" class="mt-1 text-xs font-medium text-red-600">{{ errors.phoneLocal }}</p>
                    </div>

                    <fieldset>
                        <legend class="mb-2 block text-xs font-bold text-navy">
                            Are you a real estate agent? <span class="text-red-500">*</span>
                        </legend>
                        <div class="grid grid-cols-2 gap-2">
                            <button
                                type="button"
                                class="rounded-xl border px-3 py-2.5 text-sm font-semibold transition"
                                :class="
                                    form.isRealEstateAgent === true
                                        ? 'border-primary bg-primary text-white shadow-sm'
                                        : 'border-gray-200 bg-white text-navy hover:border-primary/40'
                                "
                                @click="setAgentAnswer(true)"
                            >
                                Yes
                            </button>
                            <button
                                type="button"
                                class="rounded-xl border px-3 py-2.5 text-sm font-semibold transition"
                                :class="
                                    form.isRealEstateAgent === false
                                        ? 'border-primary bg-primary text-white shadow-sm'
                                        : 'border-gray-200 bg-white text-navy hover:border-primary/40'
                                "
                                @click="setAgentAnswer(false)"
                            >
                                No
                            </button>
                        </div>
                        <p v-if="errors.isRealEstateAgent" class="mt-1 text-xs font-medium text-red-600">{{ errors.isRealEstateAgent }}</p>
                    </fieldset>

                    <p v-if="errors.form" class="rounded-lg bg-red-50 px-3 py-2 text-xs font-medium text-red-700">{{ errors.form }}</p>

                    <p class="text-[11px] leading-relaxed text-text-muted">
                        {{ disclaimerText }}
                    </p>

                    <button
                        type="submit"
                        class="flex w-full items-center justify-center gap-2 rounded-xl py-3.5 text-sm font-bold text-white shadow-md transition disabled:cursor-not-allowed disabled:opacity-60"
                        :class="channelMeta.accent"
                        :disabled="submitting"
                    >
                        <svg
                            v-if="submitting"
                            class="h-4 w-4 animate-spin"
                            fill="none"
                            viewBox="0 0 24 24"
                            aria-hidden="true"
                        >
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" />
                        </svg>
                        {{ submitting ? 'Saving…' : channelMeta.cta }}
                    </button>
                </form>
            </div>
        </div>
    </Teleport>
</template>
