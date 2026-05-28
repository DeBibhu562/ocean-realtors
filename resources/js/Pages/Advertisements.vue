<script setup>
import AppLayout from '@/Components/AppLayout.vue';
import StaticPageHero from '@/Components/StaticPageHero.vue';
import { Link } from '@inertiajs/vue3';
import { siteContact, whatsappUrl } from '@/config/site';
import PageSeoHead from '@/Components/PageSeoHead.vue';

const adOptions = [
    { title: 'Featured listing', desc: 'Highlight your property at the top of search results in Gurgaon and NCR.', price: 'From ₹999 / month' },
    { title: 'Homepage banner', desc: 'Brand visibility on our homepage and property search pages.', price: 'From ₹4,999 / month' },
    { title: 'Agent spotlight', desc: 'Promote your consultant profile and active inventory to high-intent visitors.', price: 'From ₹2,499 / month' },
];
</script>

<template>
    <AppLayout title="Advertisements">
        <PageSeoHead title="Advertisements" description="Advertise on Ocean Realtors — featured listings, homepage banners, and agent spotlights for NCR audiences." path="/ads" />

        <StaticPageHero
            badge="Grow your reach"
            title="Advertise with Ocean Realtors"
            subtitle="Put your projects and brand in front of thousands of property seekers across Gurgaon, Delhi, and Noida."
        />

        <section class="section-y-sm bg-surface-gray">
            <div class="container max-w-5xl mx-auto px-4">
                <div class="grid md:grid-cols-3 gap-6 mb-14">
                    <div
                        v-for="ad in adOptions"
                        :key="ad.title"
                        class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex flex-col hover:shadow-md transition-shadow"
                    >
                        <h3 class="text-lg font-bold text-navy mb-2">{{ ad.title }}</h3>
                        <p class="text-sm text-text-secondary flex-1 mb-4">{{ ad.desc }}</p>
                        <p class="text-primary font-bold mb-4">{{ ad.price }}</p>
                        <Link
                            href="/contact"
                            class="block text-center py-2.5 rounded-xl border-2 border-primary text-primary text-sm font-bold hover:bg-primary hover:text-white transition-colors"
                        >
                            Enquire now
                        </Link>
                    </div>
                </div>

                <div class="bg-navy rounded-2xl p-8 md:p-10 text-white flex flex-col md:flex-row items-center justify-between gap-6">
                    <div>
                        <h2 class="text-2xl font-bold mb-2">Custom media packages</h2>
                        <p class="text-white/60 text-sm max-w-md">Bulk listings, project launches, or developer campaigns — we’ll tailor a plan for your goals and budget.</p>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-3 shrink-0">
                        <a
                            :href="whatsappUrl('Hi, I am interested in advertising on Ocean Realtors.')"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="inline-flex justify-center px-6 py-3 rounded-xl bg-emerald-500 font-bold text-sm hover:bg-emerald-600 transition-colors"
                        >
                            WhatsApp sales
                        </a>
                        <Link href="/contact" class="inline-flex justify-center px-6 py-3 rounded-xl bg-white text-navy font-bold text-sm hover:bg-primary hover:text-white transition-colors">
                            Contact sales
                        </Link>
                    </div>
                </div>
            </div>
        </section>
    </AppLayout>
</template>
