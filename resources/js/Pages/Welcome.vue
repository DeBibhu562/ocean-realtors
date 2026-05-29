<script setup>
import { defineAsyncComponent, onMounted, onUnmounted, ref } from 'vue';
import AppLayout from '@/Components/AppLayout.vue';
import HeroSection from '@/Components/HeroSection.vue';
import StatsSection from '@/Components/Home/StatsSection.vue';
import PageSeoHead from '@/Components/PageSeoHead.vue';
import { useSeoMeta } from '@/Composables/useSeoMeta';

const CategoryBrowse = defineAsyncComponent(() => import('@/Components/Home/CategoryBrowse.vue'));
const FeaturedProperties = defineAsyncComponent(() => import('@/Components/Home/FeaturedProperties.vue'));
const CTASection = defineAsyncComponent(() => import('@/Components/Home/CTASection.vue'));
const TestimonialsSection = defineAsyncComponent(() => import('@/Components/Home/TestimonialsSection.vue'));
const NewsletterSection = defineAsyncComponent(() => import('@/Components/Home/NewsletterSection.vue'));

defineProps({
    featuredProperties: { type: Array, default: () => [] },
});

const belowFoldReady = ref(false);
const belowFoldSentinel = ref(null);
let belowFoldObserver = null;
let idleFallbackId = null;

const { organizationJsonLd } = useSeoMeta();

const homeTitle = 'Real Estate in Gurgaon | Rent Property in Gurgaon| OceanRealtors.co.in';
const homeDescription =
    'Real Estate Gurgaon - Browse best properties for rent in Gurgaon - View ✓Top Localities. ✓Bachelor Friendly Properties. ✓Owners Listings. Visit Now!';

const enableBelowFold = () => {
    if (belowFoldReady.value) return;
    belowFoldReady.value = true;
    belowFoldObserver?.disconnect();
    belowFoldObserver = null;
    if (idleFallbackId !== null) {
        window.clearTimeout(idleFallbackId);
        idleFallbackId = null;
    }
};

onMounted(() => {
    if (typeof IntersectionObserver === 'function' && belowFoldSentinel.value) {
        belowFoldObserver = new IntersectionObserver(
            (entries) => {
                if (entries.some((e) => e.isIntersecting)) {
                    enableBelowFold();
                }
            },
            { rootMargin: '200px 0px', threshold: 0 },
        );
        belowFoldObserver.observe(belowFoldSentinel.value);
    }

    const scheduleIdleFallback = () => {
        idleFallbackId = window.setTimeout(enableBelowFold, 400);
    };

    if (typeof requestIdleCallback === 'function') {
        requestIdleCallback(scheduleIdleFallback, { timeout: 400 });
    } else {
        scheduleIdleFallback();
    }
});

onUnmounted(() => {
    belowFoldObserver?.disconnect();
    if (idleFallbackId !== null) {
        window.clearTimeout(idleFallbackId);
    }
});
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

        <div ref="belowFoldSentinel" class="h-px w-full" aria-hidden="true" />

        <template v-if="belowFoldReady">
            <CategoryBrowse />
            <FeaturedProperties :featured-properties="featuredProperties" />
            <CTASection />
            <TestimonialsSection />
            <NewsletterSection />
        </template>
    </AppLayout>
</template>
