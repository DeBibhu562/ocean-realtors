<script setup>
import { computed } from 'vue';
import AppLayout from '@/Components/AppLayout.vue';
import PageSeoHead from '@/Components/PageSeoHead.vue';
import StaticPageHero from '@/Components/StaticPageHero.vue';
import SeoLandingCta from '@/Components/seo/SeoLandingCta.vue';
import SeoLandingEnquiryForm from '@/Components/seo/SeoLandingEnquiryForm.vue';
import SeoLandingGuideSection from '@/Components/seo/SeoLandingGuideSection.vue';
import { siteContact, whatsappUrl } from '@/config/site';

const props = defineProps({ page: { type: Object, required: true } });

const listingType = computed(() => props.page.listing_type || 'builder_floor');
const isPlot = computed(() => listingType.value === 'plot');
const isFourBhk = computed(() => listingType.value === '4bhk_builder_floor');
const cta = computed(() => props.page.cta ?? {});

const whyHeading = computed(() => props.page.why_heading || (isPlot.value
    ? `Why buy a plot in ${props.page.area} with Ocean Realtors?`
    : isFourBhk.value
        ? `Why buy a 4 BHK builder floor in ${props.page.area} with Ocean Realtors?`
        : `Why buy a 3 BHK builder floor in ${props.page.area} with Ocean Realtors?`));

const heroWhatsapp = computed(() => cta.value.whatsapp || (isPlot.value
    ? `Hi Ocean Realtors, I am looking for plots for sale in ${props.page.area}.`
    : isFourBhk.value
        ? `Hi Ocean Realtors, I need 4 BHK builder floors for sale in ${props.page.area}.`
        : `Hi Ocean Realtors, I need 3 BHK builder floors for sale in ${props.page.area}.`));

const featureIcons = {
    spark: 'M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z',
    tag: 'M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z',
    agent: 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z',
    grid: 'M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z',
    chart: 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z',
    shield: 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z',
};

const jsonLd = computed(() => {
    const faqs = props.page.faqs ?? [];
    const graph = [
        { '@context': 'https://schema.org', '@type': 'WebPage', name: props.page.meta_title || props.page.title, description: props.page.meta_description, url: typeof window !== 'undefined' ? `${window.location.origin}${props.page.path}` : props.page.path, isPartOf: { '@type': 'WebSite', name: 'Ocean Realtors' } },
        { '@context': 'https://schema.org', '@type': 'RealEstateAgent', name: 'Ocean Realtors', url: typeof window !== 'undefined' ? window.location.origin : 'https://oceanrealtors.co.in', telephone: siteContact.phoneTel, address: { '@type': 'PostalAddress', streetAddress: siteContact.address.line1, addressLocality: 'Gurgaon', addressRegion: 'Haryana', postalCode: '122001', addressCountry: 'IN' }, areaServed: `${props.page.area}, Gurgaon` },
    ];
    if (faqs.length) {
        graph.push({ '@context': 'https://schema.org', '@type': 'FAQPage', mainEntity: faqs.map((f) => ({ '@type': 'Question', name: f.question, acceptedAnswer: { '@type': 'Answer', text: f.answer } })) });
    }
    return JSON.stringify(graph.length === 1 ? graph[0] : { '@context': 'https://schema.org', '@graph': graph });
});

const scrollToEnquiry = () => {
    if (typeof document === 'undefined') return;
    document.getElementById('enquiry-form')?.scrollIntoView({ behavior: 'smooth', block: 'start' });
};
</script>

<template>
    <AppLayout :title="page.title">
        <PageSeoHead :title="page.meta_title || page.title" :description="page.meta_description" :path="page.path" :image="page.hero_image" exact-title :json-ld="jsonLd" />
        <StaticPageHero :badge="page.hero_badge" :title="page.title" :subtitle="page.hero_subtitle" :image="page.hero_image">
            <div class="mt-6 flex w-full max-w-md mx-auto flex-col sm:flex-row items-stretch sm:items-center justify-center gap-3 px-2">
                <button type="button" class="w-full sm:flex-1 inline-flex items-center justify-center rounded-xl bg-primary px-6 py-3.5 text-sm font-bold text-white shadow-lg hover:bg-primary-hover transition" @click="scrollToEnquiry">
                    {{ cta.hero_primary || (isPlot ? 'Get plot shortlist from Ocean Realtors' : isFourBhk ? 'Get 4 BHK shortlist from Ocean Realtors' : 'Get shortlist from Ocean Realtors') }}
                </button>
                <a :href="whatsappUrl(heroWhatsapp)" target="_blank" rel="noopener noreferrer" class="w-full sm:flex-1 inline-flex items-center justify-center rounded-xl border border-white/40 bg-emerald-500 px-6 py-3.5 text-sm font-bold text-white hover:bg-emerald-600 transition">WhatsApp Ocean Realtors</a>
            </div>
        </StaticPageHero>
        <section class="bg-white border-b border-gray-100 py-3 sm:py-4">
            <div class="container max-w-6xl mx-auto px-4 text-center text-xs sm:text-sm text-text-secondary leading-relaxed">
                Trusted by {{ isPlot ? 'land buyers' : isFourBhk ? 'spacious-home buyers' : 'buyers' }} across Gurgaon — <strong class="text-navy">Ocean Realtors</strong> · Off-market access · Verified papers
            </div>
        </section>
        <section class="section-y-sm bg-surface pb-24 lg:pb-12">
            <div class="container max-w-6xl mx-auto px-4">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-10">
                    <aside class="lg:col-span-4 xl:col-span-4 order-1 lg:order-2">
                        <div class="lg:sticky lg:top-24 space-y-5">
                            <SeoLandingEnquiryForm :page-title="page.title" :page-slug="page.slug" :area-name="page.area" :intent="page.intent || 'buy'" :city="page.city || 'Gurgaon'" :listing-type="page.listing_type || 'builder_floor'" />
                            <div class="hidden sm:block rounded-2xl border border-gray-100 bg-white p-5 text-sm text-text-secondary shadow-sm">
                                <p class="font-bold text-navy mb-2">Ocean Realtors office</p>
                                <p class="leading-relaxed">{{ siteContact.address.full }}</p>
                                <p class="mt-2"><a :href="`tel:${siteContact.phoneTel}`" class="text-primary font-semibold hover:underline">{{ siteContact.phoneDisplay }}</a></p>
                                <p class="mt-1 text-xs text-text-muted">{{ siteContact.hours }}</p>
                            </div>
                        </div>
                    </aside>
                    <div class="lg:col-span-8 xl:col-span-8 order-2 lg:order-1 space-y-8 sm:space-y-10 min-w-0">
                        <div>
                            <h2 class="text-xl sm:text-2xl md:text-3xl font-black text-navy mb-3 leading-tight">{{ whyHeading }}</h2>
                            <p class="text-sm sm:text-base text-text-secondary leading-relaxed mb-6 sm:mb-8">{{ page.why_intro }}</p>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-5">
                                <article v-for="feature in page.features" :key="feature.title" class="p-5 rounded-2xl bg-white border border-gray-100 shadow-sm hover:shadow-md hover:border-primary/20 transition-all">
                                    <div class="w-11 h-11 rounded-xl bg-primary/10 text-primary flex items-center justify-center mb-3">
                                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="featureIcons[feature.icon] || featureIcons.shield" /></svg>
                                    </div>
                                    <h3 class="font-bold text-navy mb-1 text-base">{{ feature.title }}</h3>
                                    <p class="text-sm text-text-secondary leading-relaxed">{{ feature.description }}</p>
                                </article>
                            </div>
                        </div>
                        <SeoLandingCta
                            :area-name="page.area"
                            :headline="`Ready for your ${page.area} shortlist? Ocean Realtors is one form away.`"
                            :subline="cta.mid_subline || (isPlot ? `Tell us your budget and preferred plot size — we match you with verified land parcels in ${page.area}, including off-market listings.` : isFourBhk ? `Tell us your budget, bedroom layout, and possession timeline — we match you with verified 4 BHK builder floors in ${page.area}, including off-market homes.` : `Tell us your budget and possession timeline — we match you with verified 3 BHK builder floors, including off-market homes.`)"
                        />
                        <SeoLandingGuideSection
                            :area="page.area"
                            :guide-lead="page.guide_lead"
                            :guide-body="page.guide_body"
                            :location-detail="page.location_detail"
                            :highlights="page.highlights"
                            :listing-type="page.listing_type || 'builder_floor'"
                            :guide-heading="page.guide_heading"
                            :guide-highlight="page.guide_highlight"
                            :process-steps="page.process_steps"
                        />
                        <SeoLandingCta
                            variant="banner"
                            :area-name="page.area"
                            :headline="`Ocean Realtors — your ${page.area} ${isPlot ? 'land buying' : isFourBhk ? '4 BHK buying' : 'buying'} desk`"
                            :subline="cta.banner_subline || (isPlot ? '20% smarter land pricing · Off-market parcels · Dedicated agent · Papers checked before you pay token.' : isFourBhk ? '20% smarter pricing · Off-market spacious floors · Dedicated agent · Papers checked before you pay token.' : '20% smarter pricing · Off-market inventory · Dedicated agent · Papers checked before you pay token.')"
                            :whatsapp-message="cta.banner_whatsapp || heroWhatsapp"
                        />
                        <div>
                            <h2 class="text-xl sm:text-2xl font-bold text-navy mb-4 sm:mb-6">Frequently asked questions</h2>
                            <div class="space-y-3 sm:space-y-4">
                                <details v-for="(faq, idx) in page.faqs" :key="idx" class="group rounded-2xl border border-gray-100 bg-white p-4 sm:p-5 open:shadow-sm">
                                    <summary class="cursor-pointer font-semibold text-navy text-sm sm:text-base list-none flex justify-between items-start gap-4">
                                        <span class="min-w-0 flex-1">{{ faq.question }}</span>
                                        <span class="text-primary shrink-0 text-xs group-open:rotate-180 transition-transform mt-1" aria-hidden="true">▼</span>
                                    </summary>
                                    <p class="mt-3 text-sm text-text-secondary leading-relaxed">{{ faq.answer }}</p>
                                </details>
                            </div>
                        </div>
                        <SeoLandingCta
                            :area-name="page.area"
                            :headline="cta.footer_headline || (isPlot ? `Still comparing plots in ${page.area}? Let Ocean Realtors narrow it down.` : isFourBhk ? `Still comparing 4 BHK floors in ${page.area}? Let Ocean Realtors narrow it down.` : `Still comparing 3 BHK floors in ${page.area}? Let Ocean Realtors narrow it down.`)"
                            subline="No spam — one dedicated consultant, multiple verified options, and transparent next steps."
                        />
                    </div>
                </div>
            </div>
        </section>
        <div class="fixed bottom-0 inset-x-0 z-40 border-t border-gray-200 bg-white/95 backdrop-blur-md p-3 lg:hidden shadow-[0_-4px_20px_rgba(0,0,0,0.08)]">
            <div class="flex gap-2 max-w-lg mx-auto">
                <button type="button" class="flex-1 rounded-xl bg-primary py-3 text-sm font-bold text-white" @click="scrollToEnquiry">Enquire now</button>
                <a :href="`tel:${siteContact.phoneTel}`" class="shrink-0 rounded-xl border border-gray-200 px-4 py-3 text-sm font-bold text-navy" aria-label="Call Ocean Realtors">Call</a>
            </div>
        </div>
    </AppLayout>
</template>
