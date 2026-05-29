<script setup>
import { reactive, ref } from 'vue';
import axios from 'axios';
import StarRating from '@/Components/Reviews/StarRating.vue';

const props = defineProps({
    targetType: { type: String, required: true },
    targetId: { type: Number, default: null },
    heading: { type: String, default: 'Write a review' },
    showTitle: { type: Boolean, default: true },
    compact: { type: Boolean, default: false },
});

const form = reactive({
    name: '',
    email: '',
    rating: 0,
    title: '',
    body: '',
    website: '',
});

const errors = reactive({});
const submitting = ref(false);
const success = ref(false);
const errorMessage = ref('');

const validate = () => {
    Object.keys(errors).forEach((k) => delete errors[k]);
    let ok = true;

    if (!form.name.trim()) {
        errors.name = 'Please enter your name.';
        ok = false;
    }
    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(form.email.trim())) {
        errors.email = 'Enter a valid email address.';
        ok = false;
    }
    if (form.rating < 1 || form.rating > 5) {
        errors.rating = 'Please select a star rating.';
        ok = false;
    }
    if (!form.body.trim() || form.body.trim().length < 10) {
        errors.body = 'Please write at least 10 characters.';
        ok = false;
    }
    return ok;
};

const submit = async () => {
    if (!validate()) return;
    submitting.value = true;
    errorMessage.value = '';

    try {
        await axios.post('/api/reviews', {
            target_type: props.targetType,
            target_id: props.targetId,
            name: form.name.trim(),
            email: form.email.trim(),
            rating: form.rating,
            title: form.title.trim() || null,
            body: form.body.trim(),
            website: form.website,
        });
        success.value = true;
    } catch (e) {
        errorMessage.value =
            e.response?.data?.message ||
            (e.response?.status === 422
                ? Object.values(e.response.data.errors || {})
                      .flat()
                      .join(' ')
                : 'Something went wrong. Please try again.');
    } finally {
        submitting.value = false;
    }
};
</script>

<template>
    <section
        class="bg-white"
        :class="compact ? 'p-0' : 'rounded-2xl border border-primary/10 card-pad shadow-premium'"
        aria-label="Review form"
    >
        <div v-if="success" class="flex flex-col items-center text-center" :class="compact ? 'py-4' : 'py-6 md:py-8'">
            <div class="mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-emerald-100 text-emerald-600">
                <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            <h3 class="text-lg font-bold text-primary">Thank you!</h3>
            <p class="mt-2 max-w-sm text-sm text-primary/60">
                Your review has been submitted and will appear after our team approves it.
            </p>
        </div>

        <form v-else class="space-y-3" @submit.prevent="submit">
            <div>
                <h3 class="font-bold text-primary" :class="compact ? 'text-sm' : 'text-lg'">{{ heading }}</h3>
                <p class="mt-1 text-primary/50" :class="compact ? 'text-[11px] leading-snug' : 'text-xs'">
                    Share your experience. Reviews are moderated before publishing.
                </p>
            </div>

            <p v-if="errorMessage" class="rounded-xl bg-red-50 px-3 py-2 text-sm font-medium text-red-700">{{ errorMessage }}</p>

            <div>
                <label class="mb-1 block text-xs font-bold uppercase tracking-wide text-primary/50">Your rating *</label>
                <StarRating v-model="form.rating" />
                <p v-if="errors.rating" class="mt-1 text-xs font-medium text-red-600">{{ errors.rating }}</p>
            </div>

            <div class="hidden" aria-hidden="true">
                <input v-model="form.website" type="text" name="website" tabindex="-1" autocomplete="off" />
            </div>

            <div>
                <label for="review-name" class="mb-1 block text-xs font-bold uppercase tracking-wide text-primary/50">Name *</label>
                <input
                    id="review-name"
                    v-model="form.name"
                    type="text"
                    autocomplete="name"
                    class="w-full rounded-xl border border-primary/15 px-3 py-2.5 text-sm outline-none ring-accent/30 focus:ring-2"
                />
                <p v-if="errors.name" class="mt-1 text-xs font-medium text-red-600">{{ errors.name }}</p>
            </div>

            <div>
                <label for="review-email" class="mb-1 block text-xs font-bold uppercase tracking-wide text-primary/50">Email *</label>
                <input
                    id="review-email"
                    v-model="form.email"
                    type="email"
                    autocomplete="email"
                    class="w-full rounded-xl border border-primary/15 px-3 py-2.5 text-sm outline-none ring-accent/30 focus:ring-2"
                />
                <p v-if="errors.email" class="mt-1 text-xs font-medium text-red-600">{{ errors.email }}</p>
            </div>

            <div v-if="showTitle">
                <label for="review-title" class="mb-1 block text-xs font-bold uppercase tracking-wide text-primary/50">Headline (optional)</label>
                <input
                    id="review-title"
                    v-model="form.title"
                    type="text"
                    maxlength="255"
                    placeholder="e.g. Great experience"
                    class="w-full rounded-xl border border-primary/15 px-3 py-2.5 text-sm outline-none ring-accent/30 focus:ring-2"
                />
            </div>

            <div>
                <label for="review-body" class="mb-1 block text-xs font-bold uppercase tracking-wide text-primary/50">Your review *</label>
                <textarea
                    id="review-body"
                    v-model="form.body"
                    :rows="compact ? 3 : 4"
                    maxlength="2000"
                    class="w-full rounded-xl border border-primary/15 px-3 py-2.5 text-sm outline-none ring-accent/30 focus:ring-2"
                />
                <p v-if="errors.body" class="mt-1 text-xs font-medium text-red-600">{{ errors.body }}</p>
            </div>

            <button
                type="submit"
                class="flex w-full items-center justify-center rounded-xl bg-primary py-3 text-sm font-bold text-white transition hover:bg-primary/90 disabled:cursor-not-allowed disabled:opacity-60"
                :disabled="submitting"
            >
                {{ submitting ? 'Submitting...' : 'Submit review' }}
            </button>
        </form>
    </section>
</template>
