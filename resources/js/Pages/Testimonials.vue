<script setup>
import AppLayout from '@/Components/AppLayout.vue';
import StaticPageHero from '@/Components/StaticPageHero.vue';
import ReviewForm from '@/Components/Reviews/ReviewForm.vue';
import ReviewList from '@/Components/Reviews/ReviewList.vue';
import { Link } from '@inertiajs/vue3';
import PageSeoHead from '@/Components/PageSeoHead.vue';

defineProps({
    reviews: { type: Array, default: () => [] },
    review_stats: {
        type: Object,
        default: () => ({ average_rating: 0, total_count: 0 }),
    },
});
</script>

<template>
    <AppLayout title="Testimonials">
        <PageSeoHead title="Testimonials" description="Read reviews from renters, buyers, and owners who worked with Ocean Realtors in Gurgaon and NCR." path="/testimonials" />

        <StaticPageHero
            badge="Client stories"
            title="What our clients say"
            subtitle="Renters, buyers, and owners across Gurgaon and NCR share their experience with Ocean Realtors."
        />

        <section class="section-y-sm md:py-20 bg-gradient-to-b from-surface-gray via-white to-surface-gray">
            <div class="container-page max-w-6xl">
                <ReviewList
                    :reviews="reviews"
                    :review-stats="review_stats"
                    target-type="site"
                    layout="grid"
                    :show-heading="false"
                    empty-message="No testimonials yet. Share your experience below."
                />

                <div class="mt-10 md:mt-16 lg:mt-20 grid lg:grid-cols-5 gap-6 lg:gap-12 items-start">
                    <div class="lg:col-span-2 space-y-4">
                        <h2 class="text-2xl font-bold text-navy tracking-tight">Share your story</h2>
                        <p class="text-sm text-text-secondary leading-relaxed">
                            Had a great experience buying, renting, or listing with us? Tell others in Gurgaon and NCR — we publish reviews after a quick check.
                        </p>
                        <ul class="space-y-2 text-sm text-text-muted">
                            <li class="flex items-center gap-2">
                                <span class="h-1.5 w-1.5 rounded-full bg-primary shrink-0" />
                                Star rating and honest feedback welcome
                            </li>
                            <li class="flex items-center gap-2">
                                <span class="h-1.5 w-1.5 rounded-full bg-primary shrink-0" />
                                Usually approved within 1–2 business days
                            </li>
                        </ul>
                    </div>
                    <div class="lg:col-span-3">
                        <ReviewForm
                            target-type="site"
                            heading="Submit your testimonial"
                            :show-title="true"
                        />
                    </div>
                </div>

                <p class="text-center mt-14">
                    <Link
                        href="/properties"
                        class="inline-flex items-center justify-center rounded-xl bg-primary px-8 py-3.5 text-sm font-bold text-white shadow-lg shadow-primary/25 hover:bg-primary-hover transition-colors"
                    >
                        Browse properties
                    </Link>
                </p>
            </div>
        </section>
    </AppLayout>
</template>
