<script setup>
import { Link } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    size: { type: String, default: 'md' },
    theme: { type: String, default: 'light' },
    href: { type: String, default: '/' },
    showLink: { type: Boolean, default: true },
    compact: { type: Boolean, default: false },
});

const sizeClass = computed(() => {
    if (props.compact) return 'h-7 md:h-8';
    const map = {
        sm: 'h-7',
        md: 'h-8',
        lg: 'h-10',
        xl: 'h-12',
    };
    return map[props.size] || map.md;
});
</script>

<template>
    <component
        :is="showLink ? Link : 'div'"
        :href="showLink ? href : undefined"
        class="inline-flex shrink-0 items-center rounded-lg focus:outline-none focus-visible:ring-2 focus-visible:ring-primary/40"
        :aria-label="showLink ? 'Ocean Realtors — Home' : undefined"
    >
        <img
            src="/img/logo_oceanrealtors.webp"
            alt="Ocean Realtors"
            :class="[sizeClass, 'w-auto object-contain']"
            width="264"
            height="80"
            loading="eager"
            decoding="async"
        />
    </component>
</template>
