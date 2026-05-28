<script setup>
import AppLayout from '@/Components/AppLayout.vue';
import StaticPageHero from '@/Components/StaticPageHero.vue';
import { Link } from '@inertiajs/vue3';
import PageSeoHead from '@/Components/PageSeoHead.vue';
import { siteContact } from '@/config/site';

const jobs = [
    { title: 'Property Consultant', dept: 'Sales', type: 'Full-time', loc: 'Gurgaon — Sector 14 office' },
    { title: 'Listing Coordinator', dept: 'Operations', type: 'Full-time', loc: 'Gurgaon' },
    { title: 'Digital Marketing Executive', dept: 'Marketing', type: 'Full-time', loc: 'Gurgaon / hybrid' },
    { title: 'Field Associate (Site visits)', dept: 'Operations', type: 'Full-time', loc: 'Gurgaon & NCR' },
];

const perks = [
    'Work with a growing NCR real estate brand',
    'Training on listings, CRM, and customer handling',
    'Performance incentives on closures',
    'Supportive team based in Sector 14, Gurgaon',
];
</script>

<template>
    <AppLayout title="Join Our Team">
        <PageSeoHead title="Careers" description="Join Ocean Realtors — property consultant and operations careers in Gurgaon, Sector 14." path="/careers" />

        <StaticPageHero
            badge="We're hiring"
            title="Join our team"
            subtitle="Build your career in real estate with Ocean Realtors — sales, operations, and marketing roles in Gurgaon."
        />

        <section class="section-y-sm bg-white">
            <div class="container max-w-4xl mx-auto px-4">
                <h2 class="text-xl font-bold text-navy text-center mb-8">Why work with us?</h2>
                <ul class="grid sm:grid-cols-2 gap-3 mb-14">
                    <li v-for="p in perks" :key="p" class="flex items-start gap-2 text-sm text-text-secondary">
                        <span class="text-primary font-bold">✓</span> {{ p }}
                    </li>
                </ul>

                <h2 class="text-xl font-bold text-navy mb-6">Open positions</h2>
                <div class="space-y-4">
                    <div
                        v-for="j in jobs"
                        :key="j.title"
                        class="bg-surface-gray/80 p-5 rounded-xl border border-gray-100 flex flex-col md:flex-row md:items-center justify-between gap-4 hover:border-primary/30 transition-colors"
                    >
                        <div>
                            <h3 class="font-bold text-navy">{{ j.title }}</h3>
                            <p class="text-xs text-text-muted mt-1 uppercase tracking-wide font-semibold">
                                {{ j.dept }} · {{ j.type }} · {{ j.loc }}
                            </p>
                        </div>
                        <a
                            :href="`mailto:${siteContact.careersEmail}?subject=${encodeURIComponent('Application: ' + j.title)}`"
                            class="shrink-0 inline-flex justify-center px-6 py-2.5 rounded-xl bg-primary text-white text-xs font-bold uppercase tracking-wide hover:bg-primary-hover transition-colors"
                        >
                            Apply now
                        </a>
                    </div>
                </div>

                <p class="text-center mt-12 text-sm text-text-secondary">
                    Send your CV to
                    <a :href="`mailto:${siteContact.careersEmail}`" class="text-primary font-bold hover:underline">{{ siteContact.careersEmail }}</a>
                    or WhatsApp
                    <a :href="`https://wa.me/${siteContact.whatsapp}`" target="_blank" rel="noopener" class="text-primary font-bold hover:underline">{{ siteContact.phoneDisplay }}</a>
                </p>
                <p class="text-center mt-4">
                    <Link href="/contact" class="text-sm text-primary font-semibold hover:underline">Visit our office →</Link>
                </p>
            </div>
        </section>
    </AppLayout>
</template>
