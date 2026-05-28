<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/Components/AppLayout.vue';
import BlogPostCard from '@/Components/Blog/BlogPostCard.vue';
import { useBlogSeo } from '@/Composables/useBlogSeo';
import { siteContact } from '@/config/site';
import ReviewForm from '@/Components/Reviews/ReviewForm.vue';
import ReviewList from '@/Components/Reviews/ReviewList.vue';
import { ref } from 'vue';

const props = defineProps({
    post: { type: Object, required: true },
    related: { type: Array, default: () => [] },
    reviews: { type: Array, default: () => [] },
    review_stats: {
        type: Object,
        default: () => ({ average_rating: 0, total_count: 0 }),
    },
});

const { pageTitle, description, ogImage, canonical, robots, jsonLd } = useBlogSeo(props.post);
const copied = ref(false);

const copyLink = async () => {
    try {
        await navigator.clipboard.writeText(window.location.href);
        copied.value = true;
        setTimeout(() => { copied.value = false; }, 2000);
    } catch {
        /* ignore */
    }
};

const whatsappShare = () => {
    const text = encodeURIComponent(`${props.post.title} — ${window.location.href}`);
    window.open(`https://wa.me/${siteContact.whatsapp}?text=${text}`, '_blank', 'noopener');
};

const placeholder = 'https://images.unsplash.com/photo-1560518883-ce09059eeffa?auto=format&fit=crop&q=80&w=1200';
</script>

<template>
    <AppLayout :title="post.title">
        <Head>
            <title>{{ pageTitle }}</title>
            <meta head-key="description" name="description" :content="description" />
            <meta head-key="robots" name="robots" :content="robots" />
            <link head-key="canonical" rel="canonical" :href="canonical" />
            <meta head-key="og:title" property="og:title" :content="pageTitle" />
            <meta head-key="og:description" property="og:description" :content="description" />
            <meta head-key="og:type" property="og:type" content="article" />
            <meta head-key="og:url" property="og:url" :content="canonical" />
            <meta v-if="ogImage" head-key="og:image" property="og:image" :content="ogImage" />
            <meta head-key="twitter:card" name="twitter:card" content="summary_large_image" />
            <meta head-key="twitter:title" name="twitter:title" :content="pageTitle" />
            <meta head-key="twitter:description" name="twitter:description" :content="description" />
            <meta v-if="ogImage" head-key="twitter:image" name="twitter:image" :content="ogImage" />
            <script v-if="jsonLd" type="application/ld+json" v-html="jsonLd" />
        </Head>

        <article class="page-offset pb-10 md:pb-16">
            <div class="container max-w-3xl mx-auto px-3 md:px-4">
                <nav class="text-sm text-text-muted mb-6" aria-label="Breadcrumb">
                    <Link href="/" class="hover:text-primary">Home</Link>
                    <span class="mx-2">/</span>
                    <Link href="/blog" class="hover:text-primary">Blog</Link>
                    <span class="mx-2">/</span>
                    <span class="text-navy font-medium line-clamp-1">{{ post.title }}</span>
                </nav>

                <header class="mb-8">
                    <h1 class="text-3xl md:text-4xl font-black text-navy leading-tight">{{ post.title }}</h1>
                    <div class="mt-4 flex flex-wrap items-center gap-3 text-sm text-text-secondary">
                        <time :datetime="post.published_at">{{ post.published_at_formatted }}</time>
                        <span aria-hidden="true">·</span>
                        <span>{{ post.reading_time_minutes }} min read</span>
                    </div>
                </header>

                <div
                    v-if="post.author?.name"
                    class="mb-8 flex items-start gap-4 rounded-2xl border border-[#e2e6eb] bg-gradient-to-br from-[#f6f7f9] to-[#eef2f6] p-4 md:p-5"
                >
                    <img
                        v-if="post.author.photo"
                        :src="post.author.photo"
                        :alt="post.author.name"
                        class="h-14 w-14 shrink-0 rounded-full object-cover ring-2 ring-white shadow-sm"
                    />
                    <div
                        v-else
                        class="flex h-14 w-14 shrink-0 items-center justify-center rounded-full bg-[#dce4ed] text-lg font-bold text-[#4a6278]"
                    >
                        {{ post.author.name.charAt(0) }}
                    </div>
                    <div class="min-w-0">
                        <p class="text-[10px] font-bold uppercase tracking-widest text-[#6b7785]">Written by</p>
                        <p class="mt-0.5 text-base font-bold text-[#2c3e50]">{{ post.author.name }}</p>
                        <p v-if="post.author.bio" class="mt-1.5 text-sm leading-relaxed text-[#6b7785]">
                            {{ post.author.bio }}
                        </p>
                    </div>
                </div>

                <img
                    :src="post.featured_image || placeholder"
                    :alt="post.title"
                    class="w-full aspect-[16/9] object-cover rounded-2xl shadow-lg mb-10"
                />

                <div
                    class="prose prose-lg max-w-none prose-headings:text-navy prose-a:text-primary prose-img:rounded-xl"
                    v-html="post.content"
                />

                <div class="mt-10 pt-8 border-t border-gray-100 flex flex-wrap items-center gap-4">
                    <span class="text-sm font-semibold text-navy">Share:</span>
                    <button
                        type="button"
                        class="px-4 py-2 rounded-xl bg-gray-100 text-sm font-semibold hover:bg-gray-200 transition-colors"
                        @click="copyLink"
                    >
                        {{ copied ? 'Copied!' : 'Copy link' }}
                    </button>
                    <button
                        type="button"
                        class="px-4 py-2 rounded-xl bg-[#25D366] text-white text-sm font-semibold hover:opacity-90 transition-opacity"
                        @click="whatsappShare"
                    >
                        WhatsApp
                    </button>
                </div>

                <div class="mt-12 space-y-8 pt-8 border-t border-gray-100">
                    <ReviewList
                        :reviews="reviews"
                        :review-stats="review_stats"
                        target-type="blog_post"
                        :target-id="post.id"
                        layout="list"
                        heading="Article reviews"
                    />
                    <ReviewForm
                        target-type="blog_post"
                        :target-id="post.id"
                        heading="Review this article"
                        :show-title="false"
                    />
                </div>
            </div>

            <section v-if="related.length" class="container max-w-6xl mx-auto px-4 mt-16 pt-12 border-t border-gray-100">
                <h2 class="text-xl font-black text-navy mb-8">Related articles</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <BlogPostCard v-for="item in related" :key="item.id" :post="item" />
                </div>
            </section>
        </article>
    </AppLayout>
</template>
