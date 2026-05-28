<script setup>
import AppLayout from '@/Components/AppLayout.vue';
import StaticPageHero from '@/Components/StaticPageHero.vue';
import { Link } from '@inertiajs/vue3';
import PageSeoHead from '@/Components/PageSeoHead.vue';
import { siteContact } from '@/config/site';

const benefits = [
    { title: 'Verified digital listings', desc: 'Professional photos, watermarked branding, and structured data for rent, sale, and commercial inventory.' },
    { title: 'Qualified leads', desc: 'Enquiries go to your assigned agent with property context — name, phone, and message in one place.' },
    { title: 'NCR visibility', desc: 'Reach buyers and tenants searching Gurgaon, Delhi, Noida, and nearby markets.' },
    { title: 'Dedicated support', desc: 'Onboarding help from our Sector 14 team — upload, pricing guidance, and listing optimisation.' },
];
</script>

<template>
    <AppLayout title="Business Proposal">
        <PageSeoHead title="Business Proposal" description="Partner with Ocean Realtors — list inventory and receive qualified property leads across Gurgaon and NCR." path="/proposal" />

        <StaticPageHero
            badge="Partnerships"
            title="Partner with Ocean Realtors"
            subtitle="Developers, builders, and agencies — list inventory on our platform and grow with verified leads across NCR."
        />

        <section class="section-y-sm bg-white">
            <div class="container max-w-5xl mx-auto px-4">
                <div class="grid md:grid-cols-2 gap-12 items-start">
                    <div>
                        <h2 class="text-2xl font-bold text-navy mb-4">Why list with us?</h2>
                        <p class="text-text-secondary text-sm leading-relaxed mb-8">
                            Ocean Realtors is built for the NCR market — fast search, mobile-friendly listings, and human agents behind every property. We help you present projects professionally and convert interest into site visits.
                        </p>
                        <ul class="space-y-5">
                            <li v-for="b in benefits" :key="b.title" class="flex gap-4">
                                <span class="w-8 h-8 rounded-lg bg-primary/10 text-primary flex items-center justify-center shrink-0 font-bold text-sm">✓</span>
                                <div>
                                    <h3 class="font-bold text-navy text-sm">{{ b.title }}</h3>
                                    <p class="text-xs text-text-secondary mt-1">{{ b.desc }}</p>
                                </div>
                            </li>
                        </ul>
                    </div>

                    <div class="bg-navy rounded-2xl p-8 text-white shadow-xl">
                        <h3 class="text-xl font-bold mb-2">Request a proposal</h3>
                        <p class="text-white/60 text-sm mb-6">Share your details and our team will call you within one business day.</p>
                        <form class="space-y-4" @submit.prevent>
                            <input type="text" placeholder="Your name" class="w-full rounded-xl bg-white/10 border border-white/15 px-4 py-3 text-sm placeholder:text-white/40 focus:ring-primary focus:border-primary" />
                            <input type="tel" placeholder="Mobile number" class="w-full rounded-xl bg-white/10 border border-white/15 px-4 py-3 text-sm placeholder:text-white/40 focus:ring-primary focus:border-primary" />
                            <input type="email" placeholder="Business email" class="w-full rounded-xl bg-white/10 border border-white/15 px-4 py-3 text-sm placeholder:text-white/40 focus:ring-primary focus:border-primary" />
                            <input type="text" placeholder="Company / project name" class="w-full rounded-xl bg-white/10 border border-white/15 px-4 py-3 text-sm placeholder:text-white/40 focus:ring-primary focus:border-primary" />
                            <textarea placeholder="Tell us about your inventory..." rows="4" class="w-full rounded-xl bg-white/10 border border-white/15 px-4 py-3 text-sm placeholder:text-white/40 focus:ring-primary focus:border-primary" />
                            <Link
                                :href="`/contact`"
                                class="block w-full text-center bg-primary hover:bg-primary-hover text-white py-3.5 rounded-xl font-bold text-sm transition-colors"
                            >
                                Submit via contact page
                            </Link>
                        </form>
                        <p class="mt-4 text-xs text-white/50 text-center">
                            Or call <a :href="`tel:${siteContact.phoneTel}`" class="text-white font-semibold">{{ siteContact.phoneDisplay }}</a>
                        </p>
                    </div>
                </div>
            </div>
        </section>
    </AppLayout>
</template>
