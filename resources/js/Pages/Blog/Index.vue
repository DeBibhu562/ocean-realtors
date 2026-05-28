<script setup>
import { Link, router } from '@inertiajs/vue3';
import AppLayout from '@/Components/AppLayout.vue';
import BlogPostCard from '@/Components/Blog/BlogPostCard.vue';
import PageSeoHead from '@/Components/PageSeoHead.vue';
import { computed, ref } from 'vue';

const props = defineProps({
    featured: { type: Object, default: null },
    posts: Object,
    filters: Object,
});

const ogImage = computed(() => {
    const img = props.featured?.featured_image;
    if (img) {
        if (img.startsWith('http')) return img;
        return `${window.location.origin}${img.startsWith('/') ? img : `/${img}`}`;
    }
    return 'https://images.unsplash.com/photo-1560518883-ce09059eeffa?auto=format&fit=crop&w=1200&q=80';
});

const q = ref(props.filters?.q || '');

const search = () => {
    router.get(route('blog.index'), { q: q.value || undefined }, { preserveState: true, replace: true });
};
</script>

<template>
    <AppLayout title="Blog">
        <PageSeoHead
            title="Blog"
            description="Real estate insights, Gurgaon market updates, and property buying guides from Ocean Realtors."
            path="/blog"
            :image="ogImage"
        />

        <section class="bg-gradient-to-br from-navy via-slate-900 to-primary/90 text-white pt-20 pb-8 md:pt-28 md:pb-14">
            <div class="container max-w-6xl mx-auto px-4 text-center">
                <p class="text-xs font-bold uppercase tracking-[0.25em] text-white/60 mb-3">Insights & guides</p>
                <h1 class="text-3xl md:text-4xl font-black">Ocean Realtors Blog</h1>
                <p class="mt-3 text-white/70 max-w-xl mx-auto text-sm md:text-base">
                    Market trends, neighbourhood guides, and tips for buyers and renters in Gurgaon & NCR.
                </p>
                <form @submit.prevent="search" class="mt-8 max-w-md mx-auto flex gap-2">
                    <input
                        v-model="q"
                        type="search"
                        placeholder="Search articles..."
                        class="flex-1 rounded-xl border-0 px-4 py-3 text-sm text-gray-900 focus:ring-2 focus:ring-white/30"
                    />
                    <button type="submit" class="px-5 py-3 rounded-xl bg-white text-navy font-bold text-sm hover:bg-white/90 transition-colors">
                        Search
                    </button>
                </form>
            </div>
        </section>

        <section class="container-page max-w-6xl section-y-sm">
            <BlogPostCard v-if="featured" :post="featured" featured class="mb-12" />

            <div v-if="posts.data?.length" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <BlogPostCard v-for="post in posts.data" :key="post.id" :post="post" />
            </div>

            <div v-else-if="!featured" class="text-center py-20">
                <p class="text-lg font-semibold text-navy">No articles found.</p>
                <p class="text-sm text-text-secondary mt-2">Try a different search term.</p>
            </div>

            <div v-if="posts.links?.length > 3" class="mt-12 flex flex-wrap justify-center gap-1">
                <Link
                    v-for="link in posts.links"
                    :key="link.label"
                    :href="link.url || '#'"
                    class="px-3 py-1.5 rounded-lg text-sm"
                    :class="link.active ? 'bg-primary text-white' : link.url ? 'bg-gray-100 hover:bg-gray-200 text-navy' : 'text-gray-300 pointer-events-none'"
                    v-html="link.label"
                />
            </div>
        </section>
    </AppLayout>
</template>
