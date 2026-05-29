<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/Components/AppLayout.vue';
import BlogInlineCta from '@/Components/Blog/BlogInlineCta.vue';
import BlogPostContent from '@/Components/Blog/BlogPostContent.vue';
import BlogRelatedSidebarItem from '@/Components/Blog/BlogRelatedSidebarItem.vue';
import { useBlogSeo } from '@/Composables/useBlogSeo';
import { siteContact } from '@/config/site';
import ReviewForm from '@/Components/Reviews/ReviewForm.vue';
import ReviewList from '@/Components/Reviews/ReviewList.vue';
import { computed, ref } from 'vue';

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

const FEATURED_PLACEHOLDER = 'https://images.unsplash.com/photo-1560518883-ce09059eeffa?auto=format&fit=crop&q=80&w=1400';

const featuredImageUrl = computed(() => {
    const img = props.post.featured_image;
    if (!img) {
        return FEATURED_PLACEHOLDER;
    }
    if (img.startsWith('http://') || img.startsWith('https://')) {
        return img;
    }

    return img.startsWith('/') ? img : `/${img}`;
});

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

        <article class="pb-12 md:pb-20">
            <header class="bg-navy text-white pt-14 pb-6 md:pb-8">
                <div class="container-page max-w-4xl">
                    <nav class="text-sm text-white/70 mb-4 md:mb-5" aria-label="Breadcrumb">
                        <Link href="/" class="hover:text-white transition-colors">Home</Link>
                        <span class="mx-2 text-white/40">/</span>
                        <Link href="/blog" class="hover:text-white transition-colors">Blog</Link>
                    </nav>

                    <h1 class="text-2xl sm:text-3xl md:text-4xl font-black leading-tight tracking-tight">
                        {{ post.title }}
                    </h1>

                    <div class="mt-4 flex flex-wrap items-center gap-x-3 gap-y-2 text-sm text-white/80">
                        <time :datetime="post.published_at" class="font-medium">
                            {{ post.published_at_formatted }}
                        </time>
                        <span aria-hidden="true" class="text-white/40">·</span>
                        <span>{{ post.reading_time_minutes }} min read</span>
                        <template v-if="post.author?.name">
                            <span aria-hidden="true" class="text-white/40">·</span>
                            <span class="inline-flex items-center gap-2">
                                <img
                                    v-if="post.author.photo"
                                    :src="post.author.photo"
                                    :alt="post.author.name"
                                    class="h-7 w-7 rounded-full object-cover ring-2 ring-white/30"
                                />
                                <span
                                    v-else
                                    class="flex h-7 w-7 items-center justify-center rounded-full bg-white/20 text-xs font-bold"
                                >
                                    {{ post.author.name.charAt(0) }}
                                </span>
                                {{ post.author.name }}
                            </span>
                        </template>
                    </div>
                </div>
            </header>

            <div class="bg-navy pb-6 md:pb-8">
                <div class="container-page max-w-4xl">
                    <figure class="overflow-hidden rounded-xl sm:rounded-2xl shadow-xl ring-1 ring-white/10">
                        <img
                            :src="featuredImageUrl"
                            :alt="post.title"
                            class="block w-full aspect-[16/10] sm:aspect-[2/1] object-cover bg-slate-800"
                            width="1200"
                            height="600"
                            loading="eager"
                            fetchpriority="high"
                            decoding="async"
                        />
                    </figure>
                </div>
            </div>

            <div class="container-page max-w-6xl mt-6 md:mt-8 relative z-10">
                <div class="grid grid-cols-1 lg:grid-cols-[minmax(0,1fr)_minmax(260px,300px)] gap-8 lg:gap-10 items-start lg:items-stretch">
                    <!-- Main article (wide left column on desktop) -->
                    <div class="min-w-0 lg:col-start-1 lg:row-start-1">
                        <div class="rounded-2xl md:rounded-3xl border border-gray-100 bg-white shadow-premium p-5 sm:p-8 md:p-10 lg:p-12">
                            <p
                                v-if="post.excerpt"
                                class="text-lg md:text-xl text-text-secondary font-medium leading-relaxed border-l-4 border-primary pl-4 mb-8 md:mb-10"
                            >
                                {{ post.excerpt }}
                            </p>

                            <BlogInlineCta
                                :article-title="post.title"
                                :variant="0"
                                lead
                            />

                            <BlogPostContent :content="post.content" :title="post.title" />

                            <div class="mt-10 pt-8 border-t border-gray-100 flex flex-wrap items-center gap-3">
                                <span class="text-sm font-bold text-navy w-full sm:w-auto">Share this article</span>
                                <button
                                    type="button"
                                    class="px-4 py-2.5 rounded-xl bg-gray-100 text-sm font-semibold text-navy hover:bg-gray-200 transition-colors"
                                    @click="copyLink"
                                >
                                    {{ copied ? 'Link copied!' : 'Copy link' }}
                                </button>
                                <button
                                    type="button"
                                    class="px-4 py-2.5 rounded-xl bg-[#25D366] text-white text-sm font-semibold hover:opacity-90 transition-opacity"
                                    @click="whatsappShare"
                                >
                                    Share on WhatsApp
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Sidebar: sticks while reading; scrolls inside panel if taller than viewport -->
                    <aside class="min-w-0 lg:col-start-2 lg:row-start-1 lg:flex lg:flex-col">
                        <div class="space-y-5 lg:sticky lg:top-14 lg:z-10 lg:max-h-[calc(100vh-3.5rem-1rem)] lg:overflow-y-auto lg:overscroll-contain blog-sidebar-sticky">
                            <div
                                v-if="post.author?.name"
                                class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm"
                            >
                                <p class="text-[10px] font-bold uppercase tracking-widest text-text-muted">Written by</p>
                                <div class="mt-3 flex items-center gap-3">
                                    <img
                                        v-if="post.author.photo"
                                        :src="post.author.photo"
                                        :alt="post.author.name"
                                        class="h-14 w-14 shrink-0 rounded-full object-cover ring-2 ring-gray-100"
                                    />
                                    <div
                                        v-else
                                        class="flex h-14 w-14 shrink-0 items-center justify-center rounded-full bg-primary/10 text-lg font-bold text-primary"
                                    >
                                        {{ post.author.name.charAt(0) }}
                                    </div>
                                    <div class="min-w-0">
                                        <p class="font-bold text-navy">{{ post.author.name }}</p>
                                        <p v-if="post.author.bio" class="mt-1 text-xs text-text-secondary leading-relaxed">
                                            {{ post.author.bio }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <section v-if="related.length" class="rounded-2xl border border-gray-100 bg-white p-4 shadow-sm">
                                <h2 class="text-sm font-black text-navy mb-3">Related articles</h2>
                                <div class="space-y-3">
                                    <BlogRelatedSidebarItem
                                        v-for="item in related"
                                        :key="item.id"
                                        :post="item"
                                    />
                                </div>
                            </section>

                            <section class="rounded-2xl border border-gray-100 bg-white p-4 shadow-sm space-y-4">
                                <ReviewList
                                    :reviews="reviews"
                                    :review-stats="review_stats"
                                    target-type="blog_post"
                                    :target-id="post.id"
                                    layout="sidebar"
                                    heading="Article reviews"
                                />
                                <ReviewForm
                                    target-type="blog_post"
                                    :target-id="post.id"
                                    heading="Review this article"
                                    :show-title="false"
                                    compact
                                />
                            </section>

                            <Link
                                href="/blog"
                                class="flex items-center justify-center gap-2 rounded-xl border border-gray-200 bg-white py-3 text-sm font-semibold text-navy hover:border-primary/30 hover:text-primary transition-colors shadow-sm"
                            >
                                ← All articles
                            </Link>
                        </div>
                    </aside>
                </div>
            </div>
        </article>
    </AppLayout>
</template>
