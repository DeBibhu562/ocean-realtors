<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    post: { type: Object, required: true },
});

const formatDate = (iso) => {
    if (!iso) return '';
    return new Date(iso).toLocaleDateString('en-IN', { day: 'numeric', month: 'short', year: 'numeric' });
};

const placeholder = 'https://images.unsplash.com/photo-1560518883-ce09059eeffa?auto=format&fit=crop&q=80&w=400';
</script>

<template>
    <article class="group overflow-hidden rounded-xl border border-gray-100 bg-white shadow-sm transition-all hover:border-primary/20 hover:shadow-md">
        <Link :href="route('blog.show', post.slug)" class="flex gap-3 p-3">
            <img
                :src="post.featured_image || placeholder"
                :alt="post.title"
                class="h-16 w-20 shrink-0 rounded-lg object-cover"
                loading="lazy"
            />
            <div class="min-w-0 flex-1">
                <p class="text-[10px] text-text-muted">{{ formatDate(post.published_at) }}</p>
                <h3 class="mt-0.5 text-sm font-bold text-navy leading-snug line-clamp-2 group-hover:text-primary transition-colors">
                    {{ post.title }}
                </h3>
                <span class="mt-1.5 inline-flex items-center gap-0.5 text-xs font-semibold text-primary">
                    Read
                    <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </span>
            </div>
        </Link>
    </article>
</template>
