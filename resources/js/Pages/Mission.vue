<script setup>
import AppLayout from '@/Components/AppLayout.vue';
import StaticPageHero from '@/Components/StaticPageHero.vue';
import { Link } from '@inertiajs/vue3';
import PageSeoHead from '@/Components/PageSeoHead.vue';
import { siteContact } from '@/config/site';

const values = [
    { title: 'Transparency', desc: 'Clear pricing, verified listings, and honest advice at every step of your property journey.' },
    { title: 'Local expertise', desc: 'Deep knowledge of Gurgaon, Delhi NCR, and surrounding markets — sectors, societies, and fair valuations.' },
    { title: 'Client-first service', desc: 'Dedicated agents who respond quickly on call, WhatsApp, and email until you find the right home or tenant.' },
    { title: 'Technology', desc: 'Smart search, rich photos, and instant enquiries so you spend less time searching and more time deciding.' },
];
</script>

<template>
    <AppLayout title="Mission & Vision">
        <PageSeoHead title="Mission & Vision" description="Ocean Realtors mission and vision — transparent, client-first real estate services across Gurgaon and Delhi NCR." path="/mission" />

        <StaticPageHero
            badge="Who we are"
            title="Mission & Vision"
            subtitle="Ocean Realtors exists to make renting and buying property in Gurgaon and NCR simple, transparent, and stress-free."
        />

        <section class="section-y-sm -mt-8 relative z-10">
            <div class="container max-w-5xl mx-auto px-4">
                <div class="grid md:grid-cols-2 gap-6">
                    <div class="bg-white p-8 rounded-2xl shadow-lg border border-gray-100">
                        <div class="w-12 h-12 rounded-xl bg-primary/10 flex items-center justify-center mb-5">
                            <svg class="h-6 w-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                        </div>
                        <h2 class="text-2xl font-bold text-navy mb-3">Our mission</h2>
                        <p class="text-text-secondary leading-relaxed text-sm">
                            To connect buyers, tenants, and owners through verified listings and expert consultants — so every family and business finds the right space with confidence, without hidden costs or misleading information.
                        </p>
                    </div>
                    <div class="bg-white p-8 rounded-2xl shadow-lg border border-gray-100">
                        <div class="w-12 h-12 rounded-xl bg-emerald-500/10 flex items-center justify-center mb-5">
                            <svg class="h-6 w-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                        </div>
                        <h2 class="text-2xl font-bold text-navy mb-3">Our vision</h2>
                        <p class="text-text-secondary leading-relaxed text-sm">
                            To be the most trusted name in NCR real estate — known for integrity, responsive service, and a platform where every listing and every agent represents the Ocean Realtors standard of quality.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <section class="section-y-sm bg-surface-gray">
            <div class="container max-w-5xl mx-auto px-4">
                <h2 class="text-2xl font-bold text-navy text-center mb-10">Core values</h2>
                <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-5">
                    <div v-for="v in values" :key="v.title" class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm">
                        <h3 class="font-bold text-navy mb-2">{{ v.title }}</h3>
                        <p class="text-sm text-text-secondary leading-relaxed">{{ v.desc }}</p>
                    </div>
                </div>
                <p class="text-center mt-10 text-sm text-text-secondary">
                    Visit us at {{ siteContact.address.full }} or call
                    <a :href="`tel:${siteContact.phoneTel}`" class="text-primary font-semibold hover:underline">{{ siteContact.phoneDisplay }}</a>
                </p>
            </div>
        </section>
    </AppLayout>
</template>
