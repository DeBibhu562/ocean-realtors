<script setup>
import { computed } from 'vue';

const props = defineProps({
    area: { type: String, required: true },
    guideLead: { type: String, required: true },
    guideBody: { type: String, required: true },
    locationDetail: { type: String, default: '' },
    highlights: { type: Array, default: () => [] },
});

const processSteps = computed(() => [
    { title: 'Requirement call', description: 'Budget, floor preference, loan readiness, and move-in timeline.' },
    { title: 'Curated shortlist', description: `On-market and off-market 3 BHK options in ${props.area}, shared with clear price rationale.` },
    { title: 'Site visits', description: 'Coordinated tours with your dedicated Ocean Realtors agent.' },
    { title: 'Paper review', description: 'Initial title and society transfer checks before token.' },
    { title: 'Negotiation & close', description: 'Support through agreement, NOC, and handover milestones.' },
]);
</script>

<template>
    <section class="rounded-2xl border border-gray-100 bg-white shadow-sm overflow-hidden" aria-labelledby="buyer-guide-heading">
        <div class="border-b border-gray-100 bg-gradient-to-r from-primary/5 to-transparent px-5 py-5 sm:px-8 sm:py-6">
            <p class="text-[11px] sm:text-xs font-bold uppercase tracking-[0.18em] text-primary mb-2">Buyer guide</p>
            <h2 id="buyer-guide-heading" class="text-xl sm:text-2xl font-black text-navy leading-tight">3 BHK builder floors for sale in {{ area }} — what buyers should know</h2>
        </div>
        <div class="px-5 py-6 sm:px-8 sm:py-8 space-y-8">
            <div class="space-y-4 text-sm sm:text-base text-text-secondary leading-relaxed">
                <p>{{ guideLead }}</p>
                <div class="rounded-xl border border-primary/15 bg-primary/5 px-4 py-4 sm:px-5 sm:py-5">
                    <p class="text-sm sm:text-[15px] text-navy leading-relaxed">
                        A typical <strong class="font-bold text-navy">3 BHK builder floor for sale in {{ area }}</strong>
                        offers spacious family layouts — often with terrace access, dedicated parking, and strong Gurgaon connectivity.
                        <span v-if="locationDetail" class="block mt-2 text-text-secondary font-normal">{{ locationDetail }}.</span>
                    </p>
                </div>
                <p>{{ guideBody }}</p>
            </div>
            <div v-if="highlights.length">
                <div class="flex items-center gap-3 mb-4">
                    <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-emerald-100 text-emerald-600" aria-hidden="true">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </span>
                    <h3 class="text-lg sm:text-xl font-bold text-navy leading-snug">What Ocean Realtors checks before you visit</h3>
                </div>
                <ul class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <li v-for="(item, i) in highlights" :key="i" class="flex items-start gap-3 rounded-xl border border-gray-100 bg-surface p-4">
                        <span class="mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-primary text-[10px] font-bold text-white" aria-hidden="true">✓</span>
                        <span class="text-sm text-navy/90 leading-relaxed">{{ item }}</span>
                    </li>
                </ul>
            </div>
            <div>
                <div class="flex items-center gap-3 mb-5">
                    <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-primary/10 text-primary" aria-hidden="true">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
                    </span>
                    <h3 class="text-lg sm:text-xl font-bold text-navy leading-snug">How our buying process works</h3>
                </div>
                <ol class="space-y-3">
                    <li v-for="(step, index) in processSteps" :key="step.title" class="flex gap-4 rounded-xl border border-gray-100 bg-white p-4 sm:p-5 shadow-sm">
                        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-navy text-sm font-bold text-white" aria-hidden="true">{{ index + 1 }}</span>
                        <div class="min-w-0 pt-0.5">
                            <p class="font-bold text-navy text-sm sm:text-base">{{ step.title }}</p>
                            <p class="mt-1 text-sm text-text-secondary leading-relaxed">{{ step.description }}</p>
                        </div>
                    </li>
                </ol>
            </div>
        </div>
    </section>
</template>
