<script setup>
defineProps({
    type: {
        type: String,
        default: 'button',
    },
    variant: {
        type: String,
        default: 'primary', // primary, secondary, outline, ghost
    },
    size: {
        type: String,
        default: 'md', // sm, md, lg
    },
    loading: {
        type: Boolean,
        default: false,
    },
    disabled: {
        type: Boolean,
        default: false,
    }
});

const variants = {
    primary: 'bg-accent text-white hover:bg-accent-600 shadow-lg shadow-accent/20 active:scale-[0.98]',
    secondary: 'bg-primary text-white hover:bg-primary-700 shadow-lg shadow-primary/20 active:scale-[0.98]',
    outline: 'bg-transparent border-2 border-primary text-primary hover:bg-primary hover:text-white active:scale-[0.98]',
    ghost: 'bg-transparent text-primary hover:bg-primary/5 active:scale-[0.98]',
};

const sizes = {
    sm: 'px-4 py-2 text-sm',
    md: 'px-6 py-3 text-base',
    lg: 'px-8 py-4 text-lg font-bold',
};
</script>

<template>
    <button
        v-bind="$attrs"
        :type="type"
        :disabled="disabled || loading"
        class="inline-flex items-center justify-center rounded-xl font-semibold transition-all duration-200 disabled:opacity-60 disabled:cursor-not-allowed group"
        :class="[variants[variant], sizes[size]]"
    >
        <span v-if="loading" class="mr-2">
            <svg class="animate-spin h-5 w-5" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        </span>
        <slot />
    </button>
</template>
