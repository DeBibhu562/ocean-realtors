<script setup>
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/Components/AppLayout.vue';
import HeroSection from '@/Components/HeroSection.vue';
import StatsSection from '@/Components/Home/StatsSection.vue';
import CategoryBrowse from '@/Components/Home/CategoryBrowse.vue';
import FeaturedProperties from '@/Components/Home/FeaturedProperties.vue';
import TestimonialsSection from '@/Components/Home/TestimonialsSection.vue';
import NewsletterSection from '@/Components/Home/NewsletterSection.vue';
import CTASection from '@/Components/Home/CTASection.vue';
import { useSeoMeta } from '@/Composables/useSeoMeta';

const { getMeta } = useSeoMeta();
const seo = getMeta({
    title: 'Home',
    description: 'Welcome to Ocean Realtors, the leading platform for premium real estate listings.'
});
</script>

<template>
    <AppLayout>
        <Head>
            <title>{{ seo.title }}</title>
            <meta name="description" :content="seo.description" />
            <meta property="og:title" :content="seo.title" />
            <meta property="og:description" :content="seo.description" />
            <meta property="og:type" :content="seo.type" />
            <meta property="og:url" :content="seo.url" />
            <meta name="twitter:card" :content="seo.twitterCard" />
        </Head>

        <HeroSection />
        
        <StatsSection />
        
        <CategoryBrowse />
        
        <FeaturedProperties />
        
        <CTASection />
        
        <TestimonialsSection />
        
        <NewsletterSection />
    </AppLayout>
</template>
