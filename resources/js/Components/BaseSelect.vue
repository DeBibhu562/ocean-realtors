<script setup>
defineProps({
    modelValue: [String, Number],
    options: Array, // [{ label: 'Option', value: 'val' }]
    label: String,
    placeholder: String,
    error: String,
});

defineEmits(['update:modelValue']);
</script>

<template>
    <div class="w-full">
        <label v-if="label" class="block mb-2 text-sm font-semibold text-primary/80">
            {{ label }}
        </label>
        <div class="relative">
            <select
                :value="modelValue"
                @change="$emit('update:modelValue', $event.target.value)"
                class="w-full appearance-none rounded-xl border-gray-200 bg-surface-gray py-3 pl-4 pr-10 transition-all duration-200 focus:border-accent focus:bg-white focus:ring-4 focus:ring-accent/10"
                :class="{ 'border-red-500': error }"
            >
                <option v-if="placeholder" value="" disabled selected>{{ placeholder }}</option>
                <option v-for="option in options" :key="option.value" :value="option.value">
                    {{ option.label }}
                </option>
            </select>
            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-primary/40">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </div>
        </div>
        <p v-if="error" class="mt-1 text-xs text-red-500 font-medium">{{ error }}</p>
    </div>
</template>
