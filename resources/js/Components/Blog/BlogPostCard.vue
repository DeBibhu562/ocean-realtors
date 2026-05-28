<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    post: { type: Object, required: true },
    featured: { type: Boolean, default: false },
});

const formatDate = (iso) => {
    if (!iso) return '';
    return new Date(iso).toLocaleDateString('en-IN', { day: 'numeric', month: 'short', year: 'numeric' });
};

const placeholder = 'https://images.unsplash.com/photo-1560518883-ce09059eeffa?auto=format&fit=crop&q=80&w=800';
</script>

<template>
    <article
        class="group flex flex-col overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm transition-all hover:border-primary/20 hover:shadow-lg"
        :class="featured ? 'md:flex-row md:min-h-[280px]' : ''"
    >
        <Link
            :href="route('blog.show', post.slug)"
            class="relative block overflow-hidden shrink-0"
            :class="featured ? 'md:w-[45%] aspect-[16/10] md:aspect-auto md:min-h-[280px]' : 'aspect-[16/9] sm:aspect-[16/10]'"
        >
            <img
                :src="post.featured_image || placeholder"
                :alt="post.title"
                class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105"
                loading="lazy"
            />
            <span
                v-if="featured"
                class="absolute left-3 top-3 rounded-md bg-primary px-2.5 py-1 text-[10px] font-bold uppercase tracking-wide text-white"
            >
                Featured
            </span>
        </Link>

        <div class="flex flex-1 flex-col p-5 md:p-6" :class="featured ? 'justify-center' : ''">
            <div class="flex flex-wrap items-center gap-2 text-xs text-text-muted mb-2">
                <time :datetime="post.published_at">{{ formatDate(post.published_at) }}</time>
                <span aria-hidden="true">·</span>
                <span>{{ post.reading_time_minutes }} min read</span>
                <span v-if="post.author?.name" aria-hidden="true">·</span>
                <span v-if="post.author?.name" class="inline-flex items-center gap-1.5">
                    <img
                        v-if="post.author.photo"
                        :src="post.author.photo"
                        :alt="post.author.name"
                        class="h-5 w-5 rounded-full object-cover ring-1 ring-gray-200"
                    />
                    {{ post.author.name }}
                </span>
            </div>

            <h2
                class="font-bold text-navy leading-snug group-hover:text-primary transition-colors"
                :class="featured ? 'text-xl md:text-2xl line-clamp-3' : 'text-lg line-clamp-2'"
            >
                <Link :href="route('blog.show', post.slug)">{{ post.title }}</Link>
            </h2>

            <p class="mt-2 text-sm text-text-secondary line-clamp-3 flex-1" :class="featured ? 'md:text-base' : ''">
                {{ post.excerpt }}
            </p>

            <Link
                :href="route('blog.show', post.slug)"
                class="mt-4 inline-flex items-center gap-1 text-sm font-bold text-primary hover:underline"
            >
                Read article
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </Link>
        </div>
    </article>
</template>
