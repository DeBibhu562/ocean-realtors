<script setup>
import { computed, ref } from 'vue';
import axios from 'axios';
import StarRating from '@/Components/Reviews/StarRating.vue';

const props = defineProps({
    reviews: { type: Array, default: () => [] },
    reviewStats: {
        type: Object,
        default: () => ({ average_rating: 0, total_count: 0 }),
    },
    targetType: { type: String, default: null },
    targetId: { type: Number, default: null },
    heading: { type: String, default: 'Reviews' },
    emptyMessage: { type: String, default: 'No reviews yet. Be the first to share your experience.' },
    /** grid = testimonial cards; list = compact stacked (property/blog) */
    layout: { type: String, default: 'list' },
    showHeading: { type: Boolean, default: true },
});

const items = ref([...props.reviews]);
const stats = ref({ ...props.reviewStats });
const loading = ref(false);
const offset = ref(props.reviews.length);

const isGrid = computed(() => props.layout === 'grid');
const hasMore = computed(() => items.value.length < stats.value.total_count);

const loadMore = async () => {
    if (!props.targetType || loading.value || !hasMore.value) return;
    loading.value = true;
    try {
        const { data } = await axios.get('/api/reviews', {
            params: {
                target_type: props.targetType,
                target_id: props.targetId,
                offset: offset.value,
                limit: 10,
            },
        });
        items.value.push(...data.reviews);
        stats.value = data.stats;
        offset.value = items.value.length;
    } finally {
        loading.value = false;
    }
};

const parseSubtitle = (title) => {
    if (!title) return { role: '', city: '' };
    const parts = title.split(' · ');
    if (parts.length >= 2) {
        return { role: parts[0], city: parts.slice(1).join(' · ') };
    }
    return { role: title, city: '' };
};
</script>

<template>
    <section class="space-y-5 md:space-y-8" aria-label="Reviews list">
        <!-- Summary strip -->
        <div
            v-if="stats.total_count > 0"
            class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 md:gap-4 rounded-2xl border border-primary/10 bg-white px-4 py-3 md:px-6 md:py-5 shadow-sm"
            :class="isGrid ? 'max-w-2xl mx-auto w-full text-center sm:text-left' : ''"
        >
            <div v-if="showHeading && !isGrid" class="min-w-0">
                <h2 class="text-xl font-bold text-navy">{{ heading }}</h2>
            </div>
            <div
                class="flex items-center gap-4"
                :class="isGrid ? 'mx-auto sm:mx-0' : showHeading ? '' : 'w-full justify-between'"
            >
                <div class="flex items-center gap-3">
                    <div
                        class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-primary to-primary/80 text-xl font-black text-white shadow-md shadow-primary/20"
                    >
                        {{ stats.average_rating }}
                    </div>
                    <div class="text-left">
                        <StarRating :model-value="Math.round(stats.average_rating)" readonly />
                        <p class="mt-1 text-sm text-text-secondary">
                            Based on <span class="font-semibold text-navy">{{ stats.total_count }}</span>
                            {{ stats.total_count === 1 ? 'review' : 'reviews' }}
                        </p>
                    </div>
                </div>
                <span
                    v-if="isGrid"
                    class="hidden sm:inline-flex items-center gap-1.5 rounded-full bg-emerald-50 border border-emerald-100 px-3 py-1 text-[11px] font-bold uppercase tracking-wider text-emerald-700"
                >
                    <svg class="h-3.5 w-3.5" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    Verified clients
                </span>
            </div>
        </div>

        <h2 v-else-if="showHeading && !isGrid" class="text-xl font-bold text-navy">{{ heading }}</h2>

        <p
            v-if="items.length === 0"
            class="rounded-2xl border border-dashed border-gray-200 bg-white px-4 py-8 md:px-6 md:py-12 text-center text-sm text-text-muted"
        >
            {{ emptyMessage }}
        </p>

        <!-- Grid layout (testimonials page) -->
        <div
            v-else-if="isGrid"
            class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6"
        >
            <article
                v-for="review in items"
                :key="review.id"
                class="group flex flex-col rounded-2xl border border-gray-100/80 bg-white card-pad shadow-sm transition-all duration-300 hover:-translate-y-0.5 hover:border-primary/15 hover:shadow-[0_12px_40px_-12px_rgba(26,86,219,0.18)]"
            >
                <div class="mb-4 flex text-amber-400/90">
                    <svg class="h-8 w-8 opacity-40" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M4.583 17.321C3.553 16.227 3 15 3 13.011c0-3.5 2.457-6.637 6.03-8.188l.893 1.378c-3.335 1.804-3.987 4.145-4.247 5.621.537-.278 1.24-.375 1.929-.311 1.804.167 3.226 1.648 3.226 3.489a3.5 3.5 0 01-3.5 3.5c-1.073 0-2.099-.49-2.748-1.179zm10 0C13.553 16.227 13 15 13 13.011c0-3.5 2.457-6.637 6.03-8.188l.893 1.378c-3.335 1.804-3.987 4.145-4.247 5.621.537-.278 1.24-.375 1.929-.311 1.804.167 3.226 1.648 3.226 3.489a3.5 3.5 0 01-3.5 3.5c-1.073 0-2.099-.49-2.748-1.179z" />
                    </svg>
                </div>

                <div class="mb-3">
                    <StarRating :model-value="review.rating" readonly size="sm" />
                </div>

                <blockquote class="flex-1 text-sm text-text-primary/90 leading-relaxed">
                    “{{ review.body }}”
                </blockquote>

                <footer class="mt-6 flex items-center gap-3 border-t border-gray-50 pt-5">
                    <div
                        class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-primary/15 to-primary/5 text-sm font-bold text-primary ring-2 ring-white shadow-sm"
                    >
                        {{ review.initials }}
                    </div>
                    <div class="min-w-0">
                        <p class="font-bold text-navy text-sm truncate">{{ review.name }}</p>
                        <p v-if="review.title" class="text-xs text-text-secondary leading-snug mt-0.5 line-clamp-2">
                            {{ parseSubtitle(review.title).role }}
                            <template v-if="parseSubtitle(review.title).city">
                                <span class="text-text-muted"> · </span>{{ parseSubtitle(review.title).city }}
                            </template>
                        </p>
                    </div>
                </footer>
            </article>
        </div>

        <!-- List layout (property / blog) -->
        <div v-else class="space-y-4">
            <article
                v-for="review in items"
                :key="review.id"
                class="rounded-2xl border border-gray-100 bg-white card-pad shadow-sm"
            >
                <div class="flex gap-4">
                    <div
                        class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-primary/15 to-primary/5 text-sm font-bold text-primary"
                    >
                        {{ review.initials }}
                    </div>
                    <div class="min-w-0 flex-1">
                        <div class="flex flex-wrap items-center gap-x-3 gap-y-1">
                            <p class="font-bold text-navy">{{ review.name }}</p>
                            <StarRating :model-value="review.rating" readonly size="sm" />
                        </div>
                        <p v-if="review.title" class="mt-0.5 text-xs text-text-secondary">
                            {{ parseSubtitle(review.title).role }}
                            <template v-if="parseSubtitle(review.title).city">
                                · {{ parseSubtitle(review.title).city }}
                            </template>
                        </p>
                        <blockquote class="mt-3 text-sm text-text-primary/85 leading-relaxed">
                            “{{ review.body }}”
                        </blockquote>
                    </div>
                </div>
            </article>
        </div>

        <div v-if="hasMore && targetType" class="text-center pt-2">
            <button
                type="button"
                class="inline-flex items-center justify-center rounded-xl bg-white border border-primary/20 px-8 py-2.5 text-sm font-semibold text-primary shadow-sm hover:bg-primary/5 hover:border-primary/30 disabled:opacity-60 transition-colors"
                :disabled="loading"
                @click="loadMore"
            >
                {{ loading ? 'Loading...' : 'Load more reviews' }}
            </button>
        </div>
    </section>
</template>
