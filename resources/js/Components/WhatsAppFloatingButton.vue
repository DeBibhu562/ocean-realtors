<script setup>
import WhatsAppIcon from '@/Components/WhatsAppIcon.vue';
import { siteContact, whatsappUrl } from '@/config/site';

defineProps({
    message: { type: String, default: '' },
});
</script>

<template>
    <a
        :href="whatsappUrl(message || undefined)"
        target="_blank"
        rel="noopener noreferrer"
        class="fixed bottom-5 right-5 z-[200] flex items-center gap-2.5 rounded-full bg-emerald-500 px-4 py-3.5 text-white shadow-lg shadow-emerald-900/30 transition-all hover:scale-105 hover:bg-emerald-600 hover:shadow-xl focus:outline-none focus-visible:ring-2 focus-visible:ring-emerald-400 focus-visible:ring-offset-2 md:bottom-6 md:right-6 md:px-5"
        :aria-label="`Chat on WhatsApp — ${siteContact.phoneDisplay}`"
    >
        <span class="flex h-10 w-10 items-center justify-center rounded-full bg-white/20">
            <WhatsAppIcon class="h-6 w-6" />
        </span>
        <span class="hidden max-w-[140px] flex-col pr-1 text-left sm:flex">
            <span class="text-[10px] font-semibold uppercase tracking-wide text-white/80">WhatsApp</span>
            <span class="text-sm font-bold leading-tight">{{ siteContact.phoneDisplay }}</span>
        </span>
    </a>
</template>
