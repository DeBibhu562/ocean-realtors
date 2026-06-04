<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';
import { siteContact, whatsappUrl } from '@/config/site';

const props = defineProps({
    headline: { type: String, default: 'Talk to Ocean Realtors today' },
    subline: { type: String, default: '' },
    areaName: { type: String, default: 'Gurgaon' },
    variant: { type: String, default: 'inline' },
    whatsappMessage: { type: String, default: '' },
});

const defaultSubline = computed(() => props.subline || `Dedicated consultants for ${props.areaName} builder floors — call, WhatsApp, or submit the enquiry form.`);
const defaultWhatsapp = computed(() => props.whatsappMessage || `Hi Ocean Realtors, I need 3 BHK builder floors for sale in ${props.areaName}.`);

const scrollToForm = () => {
    document.getElementById('enquiry-form')?.scrollIntoView({ behavior: 'smooth', block: 'start' });
};
</script>

<template>
    <section :class="variant === 'banner' ? 'rounded-2xl bg-navy p-6 sm:p-8 lg:p-10 text-white shadow-xl' : 'rounded-2xl border border-primary/15 bg-white p-6 sm:p-8 shadow-sm'" :aria-label="headline">
        <div class="w-full max-w-none">
            <h2 :class="variant === 'banner' ? 'text-xl sm:text-2xl font-bold leading-snug text-white' : 'text-lg sm:text-xl font-bold leading-snug text-navy'">{{ headline }}</h2>
            <p :class="variant === 'banner' ? 'mt-3 text-sm sm:text-base leading-relaxed text-white/80 max-w-3xl' : 'mt-2 text-sm sm:text-base leading-relaxed text-text-secondary max-w-3xl'">{{ defaultSubline }}</p>
        </div>
        <div class="mt-6 grid w-full grid-cols-1 gap-3 sm:grid-cols-2" :class="variant === 'banner' ? 'lg:grid-cols-4' : 'lg:max-w-2xl'">
            <button type="button" class="inline-flex min-h-[3rem] w-full items-center justify-center rounded-xl px-4 py-3 text-sm font-bold transition sm:col-span-2 lg:col-span-1" :class="variant === 'banner' ? 'bg-white text-navy hover:bg-white/90' : 'bg-primary text-white hover:bg-primary-hover'" @click="scrollToForm">Enquire — Ocean Realtors</button>
            <a :href="whatsappUrl(defaultWhatsapp)" target="_blank" rel="noopener noreferrer" class="inline-flex min-h-[3rem] w-full items-center justify-center rounded-xl px-4 py-3 text-sm font-bold transition" :class="variant === 'banner' ? 'bg-emerald-500 text-white hover:bg-emerald-600' : 'border-2 border-emerald-500 text-emerald-700 hover:bg-emerald-50'">WhatsApp</a>
            <a :href="`tel:${siteContact.phoneTel}`" class="inline-flex min-h-[3rem] w-full items-center justify-center rounded-xl px-4 py-3 text-sm font-bold transition" :class="variant === 'banner' ? 'border border-white/30 text-white hover:bg-white/10' : 'border border-gray-200 text-navy hover:border-primary/30 hover:bg-surface'">{{ siteContact.phoneDisplay }}</a>
            <Link href="/contact" class="inline-flex min-h-[3rem] w-full items-center justify-center rounded-xl px-4 py-3 text-sm font-semibold transition sm:col-span-2 lg:col-span-1" :class="variant === 'banner' ? 'border border-white/20 text-white/90 hover:bg-white/10' : 'text-primary hover:underline'">Visit office</Link>
        </div>
    </section>
</template>
