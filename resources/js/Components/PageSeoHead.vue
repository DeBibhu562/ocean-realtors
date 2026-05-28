<script setup>
import { Head } from '@inertiajs/vue3';
import { useSeoMeta } from '@/Composables/useSeoMeta';

const props = defineProps({
    title: { type: String, default: '' },
    description: { type: String, default: '' },
    /** Path only, e.g. /about — used for canonical & og:url */
    path: { type: String, default: '' },
    image: { type: String, default: '' },
    type: { type: String, default: 'website' },
    noindex: { type: Boolean, default: false },
    exactTitle: { type: Boolean, default: false },
    jsonLd: { type: String, default: '' },
});

const { buildSeo } = useSeoMeta();
const seo = buildSeo({
    title: props.title,
    description: props.description,
    path: props.path,
    image: props.image,
    type: props.type,
    noindex: props.noindex,
    exactTitle: props.exactTitle,
});
</script>

<template>
    <Head>
        <title>{{ seo.title }}</title>
        <meta head-key="description" name="description" :content="seo.description" />
        <meta head-key="robots" name="robots" :content="seo.robots" />
        <link head-key="canonical" rel="canonical" :href="seo.canonical" />
        <meta head-key="og:title" property="og:title" :content="seo.title" />
        <meta head-key="og:description" property="og:description" :content="seo.description" />
        <meta head-key="og:type" property="og:type" :content="seo.type" />
        <meta head-key="og:url" property="og:url" :content="seo.canonical" />
        <meta v-if="seo.image" head-key="og:image" property="og:image" :content="seo.image" />
        <meta head-key="twitter:card" name="twitter:card" content="summary_large_image" />
        <meta head-key="twitter:title" name="twitter:title" :content="seo.title" />
        <meta head-key="twitter:description" name="twitter:description" :content="seo.description" />
        <meta v-if="seo.image" head-key="twitter:image" name="twitter:image" :content="seo.image" />
        <component :is="'script'" v-if="jsonLd" type="application/ld+json" v-html="jsonLd" />
    </Head>
</template>
