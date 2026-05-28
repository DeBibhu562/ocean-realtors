<script setup>
import { defineAsyncComponent } from 'vue';
import AppLayout from '@/Components/AppLayout.vue';
import HeroSection from '@/Components/HeroSection.vue';
import PageSeoHead from '@/Components/PageSeoHead.vue';
import { useSeoMeta } from '@/Composables/useSeoMeta';

const StatsSection = defineAsyncComponent(() => import('@/Components/Home/StatsSection.vue'));
const CategoryBrowse = defineAsyncComponent(() => import('@/Components/Home/CategoryBrowse.vue'));
const FeaturedProperties = defineAsyncComponent(() => import('@/Components/Home/FeaturedProperties.vue'));
const CTASection = defineAsyncComponent(() => import('@/Components/Home/CTASection.vue'));
const TestimonialsSection = defineAsyncComponent(() => import('@/Components/Home/TestimonialsSection.vue'));
const NewsletterSection = defineAsyncComponent(() => import('@/Components/Home/NewsletterSection.vue'));

defineProps({
    siteReviews: { type: Array, default: () => [] },
    siteReviewStats: { type: Object, default: () => ({ average_rating: 0, total_count: 0 }) },
    featuredProperties: { type: Array, default: () => [] },
});

const { organizationJsonLd } = useSeoMeta();

const homeTitle = 'Real Estate in Gurgaon | Rent Property in Gurgaon| OceanRealtors.co.in';
const homeDescription =
    'Real Estate Gurgaon - Browse best properties for rent in Gurgaon - View ✓Top Localities. ✓Bachelor Friendly Properties. ✓Owners Listings. Visit Now!';
</script>

<template>
    <AppLayout>
        <PageSeoHead
            :title="homeTitle"
            :description="homeDescription"
            path="/"
            :exact-title="true"
            :json-ld="organizationJsonLd()"
        />

        <HeroSection />

        <StatsSection />

        <CategoryBrowse />

        <FeaturedProperties :featured-properties="featuredProperties" />

        <CTASection />

        <TestimonialsSection
            :reviews="siteReviews"
            :review-stats="siteReviewStats"
        />

        <NewsletterSection />
    </AppLayout>
</template>
