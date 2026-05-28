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
    if (props.compact) return 'text-lg md:text-xl';
    const map = {
        sm: 'text-lg',
        md: 'text-xl',
        lg: 'text-2xl',
        xl: 'text-3xl',
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
        <span :class="[sizeClass, 'inline-flex items-center leading-none font-bold tracking-tight']">
            <span :class="theme === 'dark' ? 'text-white' : 'text-text-primary'">Ocean</span>
            <span class="text-primary">Realtors</span>
        </span>
    </component>
</template>
