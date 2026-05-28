<script setup>
import AppLayout from '@/Components/AppLayout.vue';
import StaticPageHero from '@/Components/StaticPageHero.vue';
import PageSeoHead from '@/Components/PageSeoHead.vue';
import { siteContact } from '@/config/site';

const sections = [
    { title: 'Information we collect', content: 'When you search properties, submit an enquiry, or contact us, we may collect your name, phone number, email, property preferences, and messages you send. We also collect technical data such as IP address and browser type for security and analytics.' },
    { title: 'How we use your information', content: 'We use your details to respond to enquiries, connect you with the assigned property agent, improve our website, send updates you opt into, and comply with legal obligations. We do not sell your personal data to third parties.' },
    { title: 'Sharing with agents & partners', content: 'When you enquire about a listing, your contact information is shared with the Ocean Realtors agent assigned to that property so they can call, WhatsApp, or email you. We require agents to use your data only for that transaction.' },
    { title: 'Cookies & analytics', content: 'Our site may use cookies to remember preferences and measure traffic. You can control cookies through your browser settings.' },
    { title: 'Data retention & security', content: 'We retain enquiry records as long as needed for business and legal purposes. We use reasonable technical measures to protect data; no online system is 100% secure.' },
    { title: 'Your rights', content: 'You may request access, correction, or deletion of your personal data by contacting us. We will respond within a reasonable time as required under applicable Indian law.' },
];
</script>

<template>
    <AppLayout title="Privacy Policy">
        <PageSeoHead title="Privacy Policy" description="Ocean Realtors privacy policy — how we collect, use, and protect your personal information." path="/privacy-policy" />

        <StaticPageHero
            badge="Legal"
            title="Privacy Policy"
            subtitle="How Ocean Realtors collects, uses, and protects your information when you use our website and services."
        />

        <section class="section-y-sm bg-surface-gray">
            <div class="container max-w-3xl mx-auto px-4">
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-8 md:p-10">
                    <p class="text-xs text-text-muted mb-10">Last updated: May 2026</p>
                    <div class="space-y-10">
                        <section v-for="s in sections" :key="s.title">
                            <h2 class="text-lg font-bold text-navy mb-3">{{ s.title }}</h2>
                            <p class="text-sm text-text-secondary leading-relaxed">{{ s.content }}</p>
                        </section>
                        <section class="pt-8 border-t border-gray-100">
                            <h2 class="text-lg font-bold text-navy mb-3">Contact us</h2>
                            <p class="text-sm text-text-secondary mb-4">
                                For privacy-related questions, write to
                                <a :href="`mailto:${siteContact.privacyEmail}`" class="text-primary font-semibold hover:underline">{{ siteContact.privacyEmail }}</a>
                                or visit {{ siteContact.address.full }}.
                            </p>
                            <p class="text-sm text-text-secondary">
                                Phone: <a :href="`tel:${siteContact.phoneTel}`" class="text-primary font-semibold">{{ siteContact.phoneDisplay }}</a>
                            </p>
                        </section>
                    </div>
                </div>
            </div>
        </section>
    </AppLayout>
</template>
