<script setup>
defineProps({
    variant: {
        type: String,
        default: 'rent', // rent, sale, featured
    }
});

const variants = {
    rent: 'bg-primary text-white',
    sale: 'bg-accent text-white', // Green
    featured: 'bg-navy text-white shadow-sm',
};
</script>

<template>
    <span 
        class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-semibold uppercase tracking-wider"
        :class="variants[variant]"
    >
        <slot />
    </span>
</template>
