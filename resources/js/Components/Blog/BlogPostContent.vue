<script setup>
import BlogInlineCta from '@/Components/Blog/BlogInlineCta.vue';
import { splitBlogContentForCta } from '@/utils/blogContentSegments';
import { computed } from 'vue';

const props = defineProps({
    content: { type: String, default: '' },
    title: { type: String, default: '' },
});

/** Drop a leading H1 when it duplicates the page title (editor often repeats the title). */
const normalizedHtml = computed(() => {
    let html = props.content || '';
    if (!html || !props.title) {
        return html;
    }

    const normalizedTitle = props.title.trim().toLowerCase().replace(/\s+/g, ' ');
    const h1Match = html.match(/^\s*<h1[^>]*>([\s\S]*?)<\/h1>/i);
    if (!h1Match) {
        return html;
    }

    const h1Text = h1Match[1].replace(/<[^>]+>/g, '').trim().toLowerCase().replace(/\s+/g, ' ');
    if (h1Text === normalizedTitle || normalizedTitle.startsWith(h1Text) || h1Text.startsWith(normalizedTitle)) {
        return html.replace(/^\s*<h1[^>]*>[\s\S]*?<\/h1>/i, '').trim();
    }

    return html;
});

const segments = computed(() => splitBlogContentForCta(normalizedHtml.value));
</script>

<template>
    <div class="blog-article-body">
        <template v-for="(segment, index) in segments" :key="index">
            <div
                v-if="segment.type === 'html'"
                class="blog-content"
                v-html="segment.content"
            />
            <BlogInlineCta
                v-else
                :article-title="title"
                :variant="segment.variant ?? 0"
            />
        </template>
    </div>
</template>
