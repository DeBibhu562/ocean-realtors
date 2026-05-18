<script setup>
import { reactive, ref, computed, watch } from 'vue';
import axios from 'axios';
import ToastNotification from '@/Components/ToastNotification.vue';

const props = defineProps({
    propertyId: { type: Number, required: true },
    propertyTitle: { type: String, default: '' },
});

const form = reactive({
    name: '',
    dialCode: '+91',
    phoneLocal: '',
    email: '',
    message: "I'm interested in this property...",
    visitDate: '',
    agree: false,
    priceDropAlert: false,
});

const errors = reactive({
    name: '',
    phoneLocal: '',
    email: '',
    message: '',
    visitDate: '',
    agree: '',
});

const submitting = ref(false);
const success = ref(false);
const toast = ref({ show: false, message: '', type: 'error' });
const toastKey = ref(0);

const dialOptions = ['+91', '+1', '+44', '+971'];

const minDate = computed(() => {
    const d = new Date();
    return d.toISOString().slice(0, 10);
});

const fullPhone = computed(() => `${form.dialCode}${form.phoneLocal.replace(/\D/g, '')}`);

const clearFieldError = (key) => {
    if (errors[key] !== undefined) errors[key] = '';
};

watch(
    () => [form.name, form.phoneLocal, form.email, form.message, form.visitDate, form.agree],
    () => {
        if (toast.value.show) toast.value = { ...toast.value, show: false };
    },
);

const validate = () => {
    let ok = true;
    errors.name = '';
    errors.phoneLocal = '';
    errors.email = '';
    errors.message = '';
    errors.visitDate = '';
    errors.agree = '';

    if (!form.name.trim()) {
        errors.name = 'Please enter your full name.';
        ok = false;
    }
    if (!form.phoneLocal.trim() || form.phoneLocal.replace(/\D/g, '').length < 6) {
        errors.phoneLocal = 'Enter a valid phone number.';
        ok = false;
    }
    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(form.email.trim())) {
        errors.email = 'Enter a valid email address.';
        ok = false;
    }
    if (!form.message.trim()) {
        errors.message = 'Please add a short message.';
        ok = false;
    }
    if (form.visitDate && form.visitDate < minDate.value) {
        errors.visitDate = 'Visit date cannot be in the past.';
        ok = false;
    }
    if (!form.agree) {
        errors.agree = 'Please agree to be contacted by the agent.';
        ok = false;
    }
    return ok;
};

const showToast = (message, type = 'error') => {
    toastKey.value += 1;
    toast.value = { show: true, message, type };
};

const submit = async () => {
    if (!validate()) return;
    submitting.value = true;
    try {
        await axios.post('/api/leads', {
            property_id: props.propertyId,
            name: form.name.trim(),
            phone: fullPhone.value,
            email: form.email.trim(),
            message: form.message.trim(),
            visit_date: form.visitDate || null,
        });
        success.value = true;

        if (form.priceDropAlert) {
            try {
                await axios.post('/api/alerts', {
                    property_id: props.propertyId,
                    email: form.email.trim(),
                    phone: fullPhone.value,
                    name: form.name.trim(),
                });
            } catch {
                showToast('Enquiry sent, but price alert could not be saved. Try again later.', 'info');
            }
        }
    } catch (e) {
        const msg =
            e.response?.data?.message ||
            (e.response?.status === 422
                ? Object.values(e.response.data.errors || {})
                      .flat()
                      .join(' ')
                : 'Something went wrong. Please try again.');
        showToast(msg, 'error');
    } finally {
        submitting.value = false;
    }
};
</script>

<template>
    <section
        id="enquiry-form"
        class="scroll-mt-28 rounded-2xl border border-primary/10 bg-white p-6 shadow-premium"
        aria-label="Property enquiry form"
    >
        <Teleport to="body">
            <ToastNotification v-if="toast.show" :key="toastKey" :message="toast.message" :type="toast.type" />
        </Teleport>

        <div v-if="success" class="flex flex-col items-center py-10 text-center">
            <div class="mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-emerald-100 text-emerald-600">
                <svg class="h-9 w-9" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            <h3 class="text-xl font-bold text-primary">Enquiry sent!</h3>
            <p class="mt-2 max-w-xs text-sm text-primary/60">Agent will contact you within 2 hours.</p>
        </div>

        <form v-else class="space-y-4" @submit.prevent="submit">
            <div>
                <h3 class="text-lg font-bold text-primary">Enquire about this property</h3>
                <p v-if="propertyTitle" class="mt-1 line-clamp-2 text-xs text-primary/50">{{ propertyTitle }}</p>
            </div>

            <div>
                <label for="lead-name" class="mb-1 block text-xs font-bold uppercase tracking-wide text-primary/50">Full name *</label>
                <input
                    id="lead-name"
                    v-model="form.name"
                    type="text"
                    autocomplete="name"
                    class="w-full rounded-xl border border-primary/15 px-3 py-2.5 text-sm outline-none ring-accent/30 focus:ring-2"
                    @input="clearFieldError('name')"
                />
                <p v-if="errors.name" class="mt-1 text-xs font-medium text-red-600">{{ errors.name }}</p>
            </div>

            <div>
                <label for="lead-phone" class="mb-1 block text-xs font-bold uppercase tracking-wide text-primary/50">Phone *</label>
                <div class="flex gap-2">
                    <select
                        v-model="form.dialCode"
                        class="w-24 shrink-0 rounded-xl border border-primary/15 px-2 py-2.5 text-sm outline-none focus:ring-2 focus:ring-accent/30"
                        aria-label="Country code"
                    >
                        <option v-for="d in dialOptions" :key="d" :value="d">{{ d }}</option>
                    </select>
                    <input
                        id="lead-phone"
                        v-model="form.phoneLocal"
                        type="tel"
                        inputmode="tel"
                        autocomplete="tel-national"
                        class="min-w-0 flex-1 rounded-xl border border-primary/15 px-3 py-2.5 text-sm outline-none ring-accent/30 focus:ring-2"
                        placeholder="9876543210"
                        @input="clearFieldError('phoneLocal')"
                    />
                </div>
                <p v-if="errors.phoneLocal" class="mt-1 text-xs font-medium text-red-600">{{ errors.phoneLocal }}</p>
            </div>

            <div>
                <label for="lead-email" class="mb-1 block text-xs font-bold uppercase tracking-wide text-primary/50">Email *</label>
                <input
                    id="lead-email"
                    v-model="form.email"
                    type="email"
                    autocomplete="email"
                    class="w-full rounded-xl border border-primary/15 px-3 py-2.5 text-sm outline-none ring-accent/30 focus:ring-2"
                    @input="clearFieldError('email')"
                />
                <p v-if="errors.email" class="mt-1 text-xs font-medium text-red-600">{{ errors.email }}</p>
            </div>

            <div>
                <label for="lead-msg" class="mb-1 block text-xs font-bold uppercase tracking-wide text-primary/50">Message *</label>
                <textarea
                    id="lead-msg"
                    v-model="form.message"
                    rows="4"
                    class="w-full rounded-xl border border-primary/15 px-3 py-2.5 text-sm outline-none ring-accent/30 focus:ring-2"
                    @input="clearFieldError('message')"
                />
                <p v-if="errors.message" class="mt-1 text-xs font-medium text-red-600">{{ errors.message }}</p>
            </div>

            <div>
                <label for="lead-visit" class="mb-1 block text-xs font-bold uppercase tracking-wide text-primary/50">Preferred visit date</label>
                <input
                    id="lead-visit"
                    v-model="form.visitDate"
                    type="date"
                    :min="minDate"
                    class="w-full rounded-xl border border-primary/15 px-3 py-2.5 text-sm outline-none ring-accent/30 focus:ring-2"
                    @input="clearFieldError('visitDate')"
                />
                <p v-if="errors.visitDate" class="mt-1 text-xs font-medium text-red-600">{{ errors.visitDate }}</p>
            </div>

            <label class="flex items-start gap-3 text-sm text-primary/70">
                <input v-model="form.agree" type="checkbox" class="mt-1 rounded border-primary/20 text-accent focus:ring-accent" />
                <span>I agree to be contacted by the agent</span>
            </label>
            <p v-if="errors.agree" class="text-xs font-medium text-red-600">{{ errors.agree }}</p>

            <button
                type="submit"
                class="flex w-full items-center justify-center gap-2 rounded-xl bg-primary py-3 text-sm font-bold text-white transition hover:bg-primary/90 disabled:cursor-not-allowed disabled:opacity-60"
                :disabled="submitting"
            >
                <svg
                    v-if="submitting"
                    class="h-5 w-5 animate-spin"
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    aria-hidden="true"
                >
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" />
                </svg>
                {{ submitting ? 'Sending...' : 'Send enquiry' }}
            </button>

            <label class="flex cursor-pointer items-center justify-between gap-3 rounded-xl border border-primary/10 bg-surface-gray px-4 py-3">
                <span class="text-sm font-semibold text-primary">Price drop alert</span>
                <input v-model="form.priceDropAlert" type="checkbox" class="rounded border-primary/20 text-accent focus:ring-accent" />
            </label>
            <p class="text-xs text-primary/45">If enabled, we will email you when this listing&apos;s price changes.</p>
        </form>
    </section>
</template>
