<script setup>
import AppLayout from '@/Components/AppLayout.vue';
import StaticPageHero from '@/Components/StaticPageHero.vue';
import { Link } from '@inertiajs/vue3';
import PageSeoHead from '@/Components/PageSeoHead.vue';
import { siteContact } from '@/config/site';

const reasons = [
    { title: 'Verified listings', desc: 'Photos, location, and availability are checked before publish — fewer surprises on site visits.' },
    { title: 'Named agents', desc: 'Every property has a dedicated consultant you can call or WhatsApp directly.' },
    { title: 'NCR coverage', desc: 'Strong inventory and local knowledge across Gurgaon, Delhi, Noida, and nearby corridors.' },
    { title: 'Fast responses', desc: 'Enquiries routed to agents instantly — typical callback within two business hours.' },
    { title: 'Rent & buy', desc: 'Residential, commercial, PG, and plots — one platform for owners and seekers.' },
    { title: 'Office support', desc: 'Walk in to our Sector 14, Gurgaon office for in-person guidance and paperwork help.' },
];

const comparison = [
    'Verified property details',
    'Direct agent contact on listing',
    'Transparent rent / sale pricing',
    'Site visit scheduling',
    'Gurgaon & NCR focus',
];
</script>

<template>
    <AppLayout title="Why Choose Us">
        <PageSeoHead title="Why Choose Us" description="Why home seekers and owners trust Ocean Realtors for verified listings, named agents, and fast NCR support." path="/why-choose-us" />

        <StaticPageHero
            badge="The Ocean advantage"
            title="Why choose Ocean Realtors?"
            subtitle="We combine verified listings, local expertise, and responsive agents — so your next move in Gurgaon or NCR is simpler from day one."
        />

        <section class="section-y-sm bg-white">
            <div class="container max-w-5xl mx-auto px-4">
                <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div
                        v-for="r in reasons"
                        :key="r.title"
                        class="p-6 rounded-2xl border border-gray-100 bg-surface-gray/50 hover:bg-white hover:shadow-md hover:border-primary/20 transition-all"
                    >
                        <h3 class="font-bold text-navy mb-2">{{ r.title }}</h3>
                        <p class="text-sm text-text-secondary leading-relaxed">{{ r.desc }}</p>
                    </div>
                </div>

                <div class="mt-16 overflow-hidden rounded-2xl border border-gray-100 shadow-lg">
                    <table class="w-full text-left text-sm">
                        <thead>
                            <tr class="bg-navy text-white">
                                <th class="px-6 py-4 font-bold">What you get</th>
                                <th class="px-6 py-4 text-center font-bold text-primary">Ocean Realtors</th>
                                <th class="px-6 py-4 text-center text-white/50 font-medium">Typical broker</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 bg-white">
                            <tr v-for="f in comparison" :key="f">
                                <td class="px-6 py-4 font-medium text-navy">{{ f }}</td>
                                <td class="px-6 py-4 text-center text-emerald-500">✓</td>
                                <td class="px-6 py-4 text-center text-gray-300">—</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <p class="text-center mt-10 text-sm text-text-secondary">
                    Questions? Call
                    <a :href="`tel:${siteContact.phoneTel}`" class="text-primary font-semibold">{{ siteContact.phoneDisplay }}</a>
                    or <Link href="/contact" class="text-primary font-semibold hover:underline">contact us</Link>.
                </p>
            </div>
        </section>
    </AppLayout>
</template>
