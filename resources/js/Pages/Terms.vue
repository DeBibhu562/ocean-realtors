<script setup>
import AppLayout from '@/Components/AppLayout.vue';
import StaticPageHero from '@/Components/StaticPageHero.vue';
import { Link } from '@inertiajs/vue3';
import PageSeoHead from '@/Components/PageSeoHead.vue';
import { siteContact } from '@/config/site';

const terms = [
    { title: 'Acceptance of terms', content: 'By accessing oceanrealtors.co.in or using our services, you agree to these Terms & Conditions. If you do not agree, please do not use the platform.' },
    { title: 'Platform role', content: 'Ocean Realtors is a property listing and lead platform. We facilitate connections between property owners, seekers, and our registered agents. We are not a party to lease or sale agreements unless explicitly stated in writing.' },
    { title: 'Listing accuracy', content: 'We strive to keep listings accurate but do not guarantee that every detail (price, availability, size, amenities) is error-free. Users should verify information during site visits and through official documents.' },
    { title: 'User conduct', content: 'You may not misuse the site, scrape data without permission, post false listings, harass agents, or attempt to disrupt our systems. Violations may result in account suspension and legal action.' },
    { title: 'Fees & brokerage', content: 'Brokerage, deposits, and fees are as agreed between you and the agent or owner. Ocean Realtors\' website does not itself charge visitors for browsing listings unless stated for a specific paid service.' },
    { title: 'Limitation of liability', content: 'To the fullest extent permitted by law, Ocean Realtors is not liable for indirect damages arising from use of the platform, property disputes, or actions of third-party agents or owners.' },
    { title: 'Governing law', content: 'These terms are governed by the laws of India. Courts at Gurgaon, Haryana shall have jurisdiction for disputes, subject to applicable consumer protection rules.' },
];
</script>

<template>
    <AppLayout title="Terms & Conditions">
        <PageSeoHead title="Terms & Conditions" description="Terms and conditions for using the Ocean Realtors website and property services." path="/terms" />

        <StaticPageHero
            badge="Legal"
            title="Terms & Conditions"
            subtitle="Please read these terms carefully before using Ocean Realtors website and services."
        />

        <section class="section-y-sm bg-surface-gray">
            <div class="container max-w-3xl mx-auto px-4">
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-8 md:p-10">
                    <p class="text-xs text-text-muted mb-10">Effective: May 2026</p>
                    <div class="space-y-10">
                        <section v-for="t in terms" :key="t.title">
                            <h2 class="text-lg font-bold text-navy mb-3">{{ t.title }}</h2>
                            <p class="text-sm text-text-secondary leading-relaxed">{{ t.content }}</p>
                        </section>
                        <p class="text-xs text-text-muted pt-6 border-t border-gray-100">
                            Questions? <Link href="/contact" class="text-primary font-semibold hover:underline">Contact us</Link>
                            or call <a :href="`tel:${siteContact.phoneTel}`" class="text-primary font-semibold">{{ siteContact.phoneDisplay }}</a>.
                        </p>
                    </div>
                </div>
            </div>
        </section>
    </AppLayout>
</template>
