<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    reviews: { type: Object, default: () => ({ data: [], links: [] }) },
    filters: { type: Object, default: () => ({ status: null, target_type: null }) },
    counts: { type: Object, default: () => ({ pending: 0, approved: 0, rejected: 0 }) },
});

const status = ref(props.filters?.status || '');
const targetType = ref(props.filters?.target_type || '');

const applyFilters = () => {
    router.get(route('admin.reviews.index'), {
        status: status.value || undefined,
        target_type: targetType.value || undefined,
    }, { preserveState: true, replace: true });
};

const approveReview = (review) => {
    router.patch(route('admin.reviews.approve', review.id), {}, { preserveScroll: true });
};

const rejectReview = (review) => {
    const reason = prompt('Reason for rejection (optional):', review.rejected_reason || '');
    if (reason === null) return;
    router.patch(route('admin.reviews.reject', review.id), { rejected_reason: reason || '' }, { preserveScroll: true });
};

const deleteReview = (review) => {
    if (!confirm(`Delete review by "${review.name}"?`)) return;
    router.delete(route('admin.reviews.destroy', review.id), { preserveScroll: true });
};
</script>

<template>
    <Head title="Manage reviews" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h2 class="text-2xl font-black text-gray-900">Reviews</h2>
                    <p class="text-sm text-gray-500 mt-1">Moderate testimonials and property/blog reviews.</p>
                </div>
                <div class="flex items-center gap-2 text-xs font-bold">
                    <span class="px-2 py-1 rounded-full bg-amber-100 text-amber-700">Pending {{ counts.pending }}</span>
                    <span class="px-2 py-1 rounded-full bg-emerald-100 text-emerald-700">Approved {{ counts.approved }}</span>
                    <span class="px-2 py-1 rounded-full bg-red-100 text-red-700">Rejected {{ counts.rejected }}</span>
                </div>
            </div>
        </template>

        <div class="space-y-6">
            <form @submit.prevent="applyFilters" class="flex flex-col sm:flex-row gap-2">
                <select v-model="status" class="rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm focus:border-primary focus:ring-2 focus:ring-primary/20">
                    <option value="">All statuses</option>
                    <option value="pending">Pending</option>
                    <option value="approved">Approved</option>
                    <option value="rejected">Rejected</option>
                </select>
                <select v-model="targetType" class="rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm focus:border-primary focus:ring-2 focus:ring-primary/20">
                    <option value="">All types</option>
                    <option value="site">Site</option>
                    <option value="property">Property</option>
                    <option value="blog_post">Blog post</option>
                </select>
                <button type="submit" class="px-4 py-2 rounded-xl bg-gray-100 text-sm font-semibold hover:bg-gray-200">Apply</button>
            </form>

            <div class="space-y-3">
                <article v-for="review in reviews.data" :key="review.id" class="bg-white rounded-2xl border border-gray-100 p-4 sm:p-5">
                    <div class="flex flex-wrap items-start justify-between gap-3">
                        <div>
                            <h3 class="font-bold text-gray-900">{{ review.title || 'Review' }}</h3>
                            <p class="text-xs text-gray-500 mt-1">{{ review.name }} • {{ review.email }} • {{ review.rating }}★</p>
                            <p class="text-xs text-primary mt-1">{{ review.target_label }}</p>
                        </div>
                        <span
                            class="inline-flex px-2.5 py-1 rounded-full text-xs font-bold uppercase"
                            :class="review.status === 'approved' ? 'bg-emerald-100 text-emerald-700' : review.status === 'rejected' ? 'bg-red-100 text-red-700' : 'bg-amber-100 text-amber-700'"
                        >
                            {{ review.status }}
                        </span>
                    </div>
                    <p class="mt-3 text-sm text-gray-700 leading-relaxed">{{ review.body }}</p>
                    <p v-if="review.rejected_reason" class="mt-2 text-xs text-red-600">Reason: {{ review.rejected_reason }}</p>

                    <div class="mt-4 flex flex-wrap items-center gap-3 text-xs font-semibold">
                        <a v-if="review.target_url" :href="review.target_url" target="_blank" class="text-primary hover:underline">Open target</a>
                        <button v-if="review.status !== 'approved'" type="button" class="text-emerald-600 hover:underline" @click="approveReview(review)">Approve</button>
                        <button v-if="review.status !== 'rejected'" type="button" class="text-amber-600 hover:underline" @click="rejectReview(review)">Reject</button>
                        <button type="button" class="text-red-500 hover:underline" @click="deleteReview(review)">Delete</button>
                    </div>
                </article>

                <div v-if="!reviews.data?.length" class="bg-white rounded-2xl border border-gray-100 p-12 text-center text-gray-400">
                    No reviews found for selected filters.
                </div>
            </div>

            <div v-if="reviews.links?.length > 3" class="flex flex-wrap justify-center gap-1">
                <Link
                    v-for="link in reviews.links"
                    :key="link.label"
                    :href="link.url || '#'"
                    class="px-3 py-1.5 rounded-lg text-sm"
                    :class="link.active ? 'bg-primary text-white' : link.url ? 'bg-gray-100 hover:bg-gray-200' : 'text-gray-300 pointer-events-none'"
                    v-html="link.label"
                />
            </div>
        </div>
    </AuthenticatedLayout>
</template>
