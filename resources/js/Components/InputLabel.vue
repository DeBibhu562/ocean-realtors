<script setup>
defineProps({
    value: {
        type: String,
    },
    compact: {
        type: Boolean,
        default: false,
    },
});
</script>

<template>
    <label
        class="block font-medium text-gray-700 dark:text-gray-300"
        :class="compact ? 'text-[11px] uppercase tracking-wide text-gray-500 mb-0.5' : 'text-sm mb-1'"
    >
        <span v-if="value">{{ value }}</span>
        <span v-else><slot /></span>
    </label>
</template>
