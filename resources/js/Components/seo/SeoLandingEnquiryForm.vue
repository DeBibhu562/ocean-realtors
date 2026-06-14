<script setup>
import { reactive, ref, computed, watch } from 'vue';
import axios from 'axios';
import ToastNotification from '@/Components/ToastNotification.vue';

const props = defineProps({
    pageTitle: { type: String, required: true },
    pageSlug: { type: String, required: true },
    areaName: { type: String, required: true },
    intent: { type: String, default: 'buy' },
    city: { type: String, default: 'Gurgaon' },
    listingType: { type: String, default: 'builder_floor' },
});

const formSubtitle = computed(() => props.listingType === 'plot'
    ? `${props.areaName} · Curated plot shortlist`
    : props.listingType === '4bhk_builder_floor'
        ? `${props.areaName} · Curated 4 BHK shortlist`
        : `${props.areaName} · Curated 3 BHK shortlist`);

const submitLabel = computed(() => props.listingType === 'plot'
    ? 'Get plot shortlist'
    : props.listingType === '4bhk_builder_floor'
        ? 'Get 4 BHK shortlist'
        : 'Get shortlist');

const defaultMessage = computed(() => `Interested in: ${props.pageTitle}. Please share options and schedule a visit.`);

const form = reactive({
    name: '',
    dialCode: '+91',
    phoneLocal: '',
    email: '',
    message: defaultMessage.value,
    visitDate: '',
    agree: false,
});

const errors = reactive({ name: '', phoneLocal: '', email: '', message: '', visitDate: '', agree: '' });
const submitting = ref(false);
const success = ref(false);
const toast = ref({ show: false, message: '', type: 'error' });
const toastKey = ref(0);
const dialOptions = ['+91', '+1', '+44', '+971'];
const minDate = computed(() => new Date().toISOString().slice(0, 10));
const fullPhone = computed(() => `${form.dialCode}${form.phoneLocal.replace(/\D/g, '')}`);

watch(defaultMessage, (msg) => {
    if (!form.message || form.message.startsWith('Interested in:')) form.message = msg;
});

watch(() => [form.name, form.phoneLocal, form.email, form.message, form.visitDate, form.agree], () => {
    if (toast.value.show) toast.value = { ...toast.value, show: false };
});

const clearFieldError = (key) => { if (errors[key] !== undefined) errors[key] = ''; };

const validate = () => {
    let ok = true;
    Object.keys(errors).forEach((k) => { errors[k] = ''; });
    if (!form.name.trim()) { errors.name = 'Please enter your full name.'; ok = false; }
    if (!form.phoneLocal.trim() || form.phoneLocal.replace(/\D/g, '').length < 6) { errors.phoneLocal = 'Enter a valid phone number.'; ok = false; }
    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(form.email.trim())) { errors.email = 'Enter a valid email address.'; ok = false; }
    if (!form.message.trim()) { errors.message = 'Please add a short message.'; ok = false; }
    if (form.visitDate && form.visitDate < minDate.value) { errors.visitDate = 'Visit date cannot be in the past.'; ok = false; }
    if (!form.agree) { errors.agree = 'Please agree to be contacted by Ocean Realtors.'; ok = false; }
    return ok;
};

const showToast = (message, type = 'error') => { toastKey.value += 1; toast.value = { show: true, message, type }; };

const submit = async () => {
    if (!validate()) return;
    submitting.value = true;
    try {
        await axios.post('/api/leads', {
            name: form.name.trim(),
            phone: fullPhone.value,
            email: form.email.trim(),
            message: [`${form.message.trim()}`, `Landing page: ${props.pageSlug}`, `Page: ${props.pageTitle}`].join('\n'),
            visit_date: form.visitDate || null,
            intent: props.intent,
            city: props.city,
            source: 'web',
        });
        success.value = true;
    } catch (e) {
        const msg = e.response?.data?.message || (e.response?.status === 422 ? Object.values(e.response.data.errors || {}).flat().join(' ') : 'Something went wrong. Please try again.');
        showToast(msg, 'error');
    } finally {
        submitting.value = false;
    }
};
</script>

<template>
    <section id="enquiry-form" class="scroll-mt-24 md:scroll-mt-28 rounded-xl border border-gray-200 bg-white p-4 shadow-md" aria-label="Enquiry form for Ocean Realtors">
        <Teleport to="body">
            <ToastNotification v-if="toast.show" :key="toastKey" :message="toast.message" :type="toast.type" />
        </Teleport>
        <div v-if="success" class="flex flex-col items-center py-5 text-center">
            <div class="mb-3 flex h-12 w-12 items-center justify-center rounded-full bg-emerald-100 text-emerald-600">
                <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
            </div>
            <h3 class="text-sm font-bold text-navy">Thank you — we will call you soon</h3>
            <p class="mt-1.5 max-w-xs text-xs text-text-secondary leading-relaxed">Ocean Realtors will contact you within two business hours for {{ areaName }}.</p>
        </div>
        <form v-else class="space-y-2.5" @submit.prevent="submit">
            <header class="text-center border-b border-gray-100 pb-2.5 mb-0.5">
                <h2 class="text-[13px] sm:text-sm font-bold text-navy leading-tight tracking-tight whitespace-nowrap">Enquire with Ocean Realtors</h2>
                <p class="mt-1 text-[10px] sm:text-[11px] text-text-secondary leading-snug">{{ formSubtitle }}</p>
            </header>
            <div>
                <label for="seo-lead-name" class="mb-0.5 block text-[10px] font-semibold uppercase tracking-wide text-navy/55">Name *</label>
                <input id="seo-lead-name" v-model="form.name" type="text" autocomplete="name" class="w-full rounded-lg border border-gray-200 px-2.5 py-2 text-sm outline-none focus:border-primary focus:ring-1 focus:ring-primary/25" @input="clearFieldError('name')" />
                <p v-if="errors.name" class="mt-0.5 text-[11px] font-medium text-red-600">{{ errors.name }}</p>
            </div>
            <div>
                <label for="seo-lead-phone" class="mb-0.5 block text-[10px] font-semibold uppercase tracking-wide text-navy/55">Phone *</label>
                <div class="flex gap-1.5">
                    <select v-model="form.dialCode" class="w-[4.25rem] shrink-0 rounded-lg border border-gray-200 px-1.5 py-2 text-xs outline-none focus:ring-1 focus:ring-primary/25" aria-label="Country code">
                        <option v-for="d in dialOptions" :key="d" :value="d">{{ d }}</option>
                    </select>
                    <input id="seo-lead-phone" v-model="form.phoneLocal" type="tel" inputmode="tel" autocomplete="tel-national" class="min-w-0 flex-1 rounded-lg border border-gray-200 px-2.5 py-2 text-sm outline-none focus:border-primary focus:ring-1 focus:ring-primary/25" placeholder="9876543210" @input="clearFieldError('phoneLocal')" />
                </div>
                <p v-if="errors.phoneLocal" class="mt-0.5 text-[11px] font-medium text-red-600">{{ errors.phoneLocal }}</p>
            </div>
            <div>
                <label for="seo-lead-email" class="mb-0.5 block text-[10px] font-semibold uppercase tracking-wide text-navy/55">Email *</label>
                <input id="seo-lead-email" v-model="form.email" type="email" autocomplete="email" class="w-full rounded-lg border border-gray-200 px-2.5 py-2 text-sm outline-none focus:border-primary focus:ring-1 focus:ring-primary/25" @input="clearFieldError('email')" />
                <p v-if="errors.email" class="mt-0.5 text-[11px] font-medium text-red-600">{{ errors.email }}</p>
            </div>
            <div>
                <label for="seo-lead-msg" class="mb-0.5 block text-[10px] font-semibold uppercase tracking-wide text-navy/55">Requirement *</label>
                <textarea id="seo-lead-msg" v-model="form.message" rows="2" class="w-full resize-none rounded-lg border border-gray-200 px-2.5 py-2 text-sm leading-snug outline-none focus:border-primary focus:ring-1 focus:ring-primary/25" @input="clearFieldError('message')" />
                <p v-if="errors.message" class="mt-0.5 text-[11px] font-medium text-red-600">{{ errors.message }}</p>
            </div>
            <div>
                <label for="seo-lead-visit" class="mb-0.5 block text-[10px] font-semibold uppercase tracking-wide text-navy/55">Visit date</label>
                <input id="seo-lead-visit" v-model="form.visitDate" type="date" :min="minDate" class="w-full rounded-lg border border-gray-200 px-2.5 py-2 text-sm outline-none focus:border-primary focus:ring-1 focus:ring-primary/25" @input="clearFieldError('visitDate')" />
                <p v-if="errors.visitDate" class="mt-0.5 text-[11px] font-medium text-red-600">{{ errors.visitDate }}</p>
            </div>
            <label class="flex items-start justify-center gap-2 text-center text-[11px] text-text-secondary leading-snug">
                <input v-model="form.agree" type="checkbox" class="mt-0.5 shrink-0 rounded border-gray-300 text-primary focus:ring-primary" />
                <span class="min-w-0">I agree to be contacted by Ocean Realtors</span>
            </label>
            <p v-if="errors.agree" class="text-center text-[11px] font-medium text-red-600">{{ errors.agree }}</p>
            <button type="submit" class="flex w-full items-center justify-center gap-2 rounded-lg bg-primary py-2.5 text-xs font-bold text-white transition hover:bg-primary-hover disabled:cursor-not-allowed disabled:opacity-60" :disabled="submitting">
                <svg v-if="submitting" class="h-4 w-4 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" /><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" /></svg>
                {{ submitting ? 'Sending...' : submitLabel }}
            </button>
        </form>
    </section>
</template>
