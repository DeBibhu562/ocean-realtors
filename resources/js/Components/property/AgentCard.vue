<script setup>
import { computed } from 'vue';

const props = defineProps({
    agent: {
        type: Object,
        required: true,
    },
});

const waLink = computed(() => {
    const num = String(props.agent.whatsapp_phone || '').replace(/\D/g, '');
    if (!num) return '#';
    const text = encodeURIComponent('Hi, I saw your listing on Ocean Realtors and would like more details.');
    return `https://wa.me/${num}?text=${text}`;
});

const telLink = computed(() => {
    const raw = props.agent.phone || '';
    const cleaned = raw.replace(/[^\d+]/g, '');
    return cleaned ? `tel:${cleaned}` : '#';
});
</script>

<template>
    <section class="rounded-2xl border border-primary/10 bg-white p-6 shadow-premium" aria-label="Listing agent">
        <div class="flex items-start gap-4">
            <div class="relative shrink-0">
                <img
                    :src="agent.avatar || `https://ui-avatars.com/api/?name=${encodeURIComponent(agent.name)}&background=F59E0B&color=fff`"
                    :alt="agent.name"
                    class="h-20 w-20 rounded-full object-cover ring-2 ring-accent/30"
                    width="80"
                    height="80"
                />
                <span class="absolute bottom-1 right-1 h-3.5 w-3.5 rounded-full border-2 border-white bg-emerald-500" title="Online" />
            </div>
            <div class="min-w-0 flex-1">
                <h3 class="text-lg font-bold text-primary">{{ agent.name }}</h3>
                <p class="text-sm text-primary/55">{{ agent.designation }}</p>
                <div class="mt-2 flex flex-wrap items-center gap-2 text-sm">
                    <span class="font-bold text-accent">{{ agent.rating }} ★</span>
                    <span class="text-primary/45">({{ agent.reviews_count }} reviews)</span>
                </div>
                <p class="mt-1 text-xs font-semibold uppercase tracking-wide text-primary/40">
                    {{ agent.listed_count }} listed properties
                </p>
            </div>
        </div>

        <div class="mt-6 flex flex-col gap-3">
            <a
                :href="telLink"
                class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-accent px-4 py-3 text-sm font-bold text-white shadow transition hover:bg-accent/90"
            >
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                </svg>
                Call Now
            </a>
            <a
                :href="waLink"
                target="_blank"
                rel="noopener noreferrer"
                class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-emerald-500 px-4 py-3 text-sm font-bold text-white shadow transition hover:bg-emerald-600"
            >
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.435 9.884-9.881 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" />
                </svg>
                WhatsApp
            </a>
            <a
                href="#enquiry-form"
                class="inline-flex w-full items-center justify-center gap-2 rounded-xl border-2 border-primary bg-transparent px-4 py-3 text-sm font-bold text-primary transition hover:bg-primary/5"
            >
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
                Send Email
            </a>
        </div>
    </section>
</template>
