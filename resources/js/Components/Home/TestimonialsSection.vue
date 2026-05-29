<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';
import { Link } from '@inertiajs/vue3';
import axios from 'axios';

const props = defineProps({
    reviews: { type: Array, default: () => [] },
    reviewStats: {
        type: Object,
        default: () => ({ average_rating: 0, total_count: 0 }),
    },
});

const reviews = ref([...props.reviews]);
const reviewStats = ref({ ...props.reviewStats });
const isLoadingReviews = ref(false);

const mapReview = (review) => {
    const parts = (review.title || '').split(' · ');
    return {
        name: review.name,
        role: parts[0] || review.title || 'Client',
        city: parts.length > 1 ? parts.slice(1).join(' · ') : '',
        avatar: review.initials,
        quote: review.body,
        rating: review.rating,
    };
};

const testimonials = computed(() => reviews.value.map(mapReview));

const currentIndex = ref(0);
const isPaused = ref(false);
const isAnimating = ref(false);
let intervalId = null;

const active = computed(() => testimonials.value[currentIndex.value] || null);
const averageRating = computed(() => {
    if (reviewStats.value.total_count > 0) {
        return Number(reviewStats.value.average_rating).toFixed(1);
    }
    if (testimonials.value.length === 0) return '0.0';
    const sum = testimonials.value.reduce((acc, t) => acc + t.rating, 0);
    return (sum / testimonials.value.length).toFixed(1);
});

const goTo = (index) => {
    if (testimonials.value.length === 0 || isAnimating.value || index === currentIndex.value) return;
    isAnimating.value = true;
    currentIndex.value = index;
    window.setTimeout(() => { isAnimating.value = false; }, 400);
};

const next = () => {
    if (testimonials.value.length === 0) return;
    goTo((currentIndex.value + 1) % testimonials.value.length);
};
const prev = () => {
    if (testimonials.value.length === 0) return;
    goTo((currentIndex.value - 1 + testimonials.value.length) % testimonials.value.length);
};

const startAutoAdvance = () => {
    intervalId = window.setInterval(() => {
        if (!isPaused.value && !isAnimating.value && testimonials.value.length > 1) next();
    }, 6000);
};

async function fetchSiteReviews() {
    if (reviews.value.length > 0) return;

    isLoadingReviews.value = true;
    try {
        const { data } = await axios.get('/api/reviews', {
            params: { target_type: 'site', limit: 10 },
        });
        reviews.value = data.reviews ?? [];
        reviewStats.value = data.stats ?? { average_rating: 0, total_count: 0 };
    } catch (err) {
        console.error('[Testimonials] Failed to load reviews:', err);
    } finally {
        isLoadingReviews.value = false;
    }
}

onMounted(async () => {
    await fetchSiteReviews();
    if (testimonials.value.length > 0) startAutoAdvance();
});
onUnmounted(() => {
    if (intervalId) window.clearInterval(intervalId);
});
</script>

<template>
    <section
        class="relative py-10 md:py-24 overflow-hidden bg-gradient-to-b from-slate-50 via-white to-slate-50"
        aria-labelledby="testimonials-heading"
        @mouseenter="isPaused = true"
        @mouseleave="isPaused = false"
    >
        <div class="pointer-events-none absolute inset-0" aria-hidden="true">
            <div class="absolute -top-24 right-0 h-72 w-72 rounded-full bg-primary/5 blur-3xl" />
            <div class="absolute bottom-0 left-0 h-64 w-64 rounded-full bg-accent/5 blur-3xl" />
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_1px_1px,rgb(15_23_42/0.04)_1px,transparent_0)] bg-[length:24px_24px]" />
        </div>

        <div class="container-page relative max-w-6xl">
            <div class="text-center max-w-2xl mx-auto mb-12 md:mb-14">
                <span class="inline-flex items-center gap-2 rounded-full bg-primary/10 px-4 py-1.5 text-[11px] font-bold uppercase tracking-[0.2em] text-primary mb-4">
                    <svg class="h-3.5 w-3.5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                    </svg>
                    Client stories
                </span>
                <h2 id="testimonials-heading" class="text-2xl md:text-3xl lg:text-4xl font-bold text-navy tracking-tight">
                    What our clients say
                </h2>
                <p class="mt-3 text-sm md:text-base text-text-secondary leading-relaxed">
                    Trusted by homeowners and investors across Gurgaon and NCR.
                </p>

                <div class="mt-6 inline-flex flex-wrap items-center justify-center gap-4 md:gap-8 text-xs font-semibold text-text-muted">
                    <span class="flex items-center gap-2">
                        <span class="flex text-star">
                            <svg v-for="n in 5" :key="n" class="h-4 w-4 fill-current" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                        </span>
                        <span class="text-navy">{{ averageRating }} average rating</span>
                    </span>
                    <span v-if="reviewStats.total_count > 0" class="hidden sm:block w-px h-4 bg-border" />
                    <span v-if="reviewStats.total_count > 0">{{ reviewStats.total_count }} verified reviews</span>
                    <span v-else-if="isLoadingReviews" class="text-text-muted">Loading reviews…</span>
                </div>
            </div>

            <p v-if="testimonials.length === 0" class="text-center text-sm text-text-muted max-w-md mx-auto">
                Client stories will appear here soon.
                <Link href="/testimonials" class="text-primary font-semibold hover:underline">Share yours</Link>
            </p>

            <div v-else class="relative max-w-3xl mx-auto">
                <button
                    v-if="testimonials.length > 1"
                    type="button"
                    class="hidden md:flex absolute -left-4 lg:-left-16 top-1/2 -translate-y-1/2 z-10 w-11 h-11 items-center justify-center rounded-full bg-white border border-border text-text-secondary shadow-md hover:text-primary hover:border-primary/30 hover:shadow-lg transition-all focus:outline-none focus-visible:ring-2 focus-visible:ring-primary/40"
                    aria-label="Previous testimonial"
                    @click="prev"
                >
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>
                <button
                    v-if="testimonials.length > 1"
                    type="button"
                    class="hidden md:flex absolute -right-4 lg:-right-16 top-1/2 -translate-y-1/2 z-10 w-11 h-11 items-center justify-center rounded-full bg-white border border-border text-text-secondary shadow-md hover:text-primary hover:border-primary/30 hover:shadow-lg transition-all focus:outline-none focus-visible:ring-2 focus-visible:ring-primary/40"
                    aria-label="Next testimonial"
                    @click="next"
                >
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>

                <div class="relative rounded-2xl md:rounded-3xl bg-white border border-border/80 shadow-[0_8px_40px_-12px_rgba(15,23,42,0.12)] overflow-hidden">
                    <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-primary via-primary/80 to-accent" />

                    <div class="px-4 py-6 md:px-10 md:py-12 min-h-[220px] md:min-h-[300px] flex flex-col justify-center">
                        <Transition name="testimonial" mode="out-in">
                            <article v-if="active" :key="currentIndex" class="text-center md:text-left">
                                <div class="flex justify-center md:justify-start gap-0.5 mb-5" :aria-label="`${active.rating} out of 5 stars`">
                                    <svg
                                        v-for="star in 5"
                                        :key="star"
                                        class="h-5 w-5"
                                        :class="star <= active.rating ? 'text-star' : 'text-gray-200'"
                                        fill="currentColor"
                                        viewBox="0 0 20 20"
                                    >
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                </div>

                                <blockquote class="text-base md:text-lg text-text-primary/90 leading-relaxed font-medium">
                                    “{{ active.quote }}”
                                </blockquote>

                                <footer class="mt-8 flex flex-col md:flex-row md:items-center gap-4 md:gap-5">
                                    <div class="flex items-center justify-center md:justify-start gap-4">
                                        <div class="relative w-14 h-14 rounded-full bg-gradient-to-br from-primary to-primary-hover flex items-center justify-center text-white text-sm font-bold ring-4 ring-white shadow-md shrink-0">
                                            {{ active.avatar }}
                                        </div>
                                        <div class="text-center md:text-left">
                                            <cite class="not-italic text-base font-bold text-navy">{{ active.name }}</cite>
                                            <p class="text-sm text-text-secondary mt-0.5">
                                                {{ active.role }}
                                                <span v-if="active.city" class="text-text-muted mx-1">·</span>
                                                <span v-if="active.city">{{ active.city }}</span>
                                            </p>
                                        </div>
                                    </div>
                                </footer>
                            </article>
                        </Transition>
                    </div>
                </div>

                <div v-if="testimonials.length > 1" class="flex justify-center items-center gap-2 mt-8">
                    <button
                        v-for="(_, i) in testimonials"
                        :key="i"
                        type="button"
                        class="group relative h-2 rounded-full transition-all duration-300 focus:outline-none focus-visible:ring-2 focus-visible:ring-primary/40"
                        :class="currentIndex === i ? 'w-8 bg-primary' : 'w-2 bg-gray-200 hover:bg-gray-300'"
                        :aria-label="`Go to testimonial ${i + 1}`"
                        :aria-current="currentIndex === i ? 'true' : 'false'"
                        @click="goTo(i)"
                    />
                </div>

                <p class="text-center mt-8">
                    <Link href="/testimonials" class="text-sm font-semibold text-primary hover:underline">Read all testimonials</Link>
                </p>
            </div>
        </div>
    </section>
</template>

<style scoped>
.testimonial-enter-active,
.testimonial-leave-active {
    transition: opacity 0.35s ease, transform 0.35s ease;
}
.testimonial-enter-from {
    opacity: 0;
    transform: translateY(12px);
}
.testimonial-leave-to {
    opacity: 0;
    transform: translateY(-8px);
}
</style>
