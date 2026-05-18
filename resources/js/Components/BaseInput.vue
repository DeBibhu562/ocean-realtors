<script setup>
import { onMounted, ref, useAttrs } from 'vue';

const props = defineProps({
    modelValue: [String, Number],
    label: String,
    placeholder: String,
    type: {
        type: String,
        default: 'text',
    },
    error: String,
    id: {
        type: String,
        default: () => `input-${Math.random().toString(36).substr(2, 9)}`
    }
});

defineEmits(['update:modelValue']);
const input = ref(null);

onMounted(() => {
    if (input.value?.hasAttribute('autofocus')) {
        input.value.focus();
    }
});

defineExpose({ focus: () => input.value?.focus() });
</script>

<template>
    <div class="w-full">
        <label v-if="label" :for="id" class="block mb-2 text-sm font-semibold text-primary/80">
            {{ label }}
        </label>
        <div class="relative">
            <div v-if="$slots.icon" class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-primary/40">
                <slot name="icon" />
            </div>
            <input
                :id="id"
                :type="type"
                :value="modelValue"
                @input="$emit('update:modelValue', $event.target.value)"
                ref="input"
                class="w-full rounded-xl border-gray-200 bg-surface-gray py-3 transition-all duration-200 focus:border-accent focus:bg-white focus:ring-4 focus:ring-accent/10"
                :class="{ 'pl-11': $slots.icon, 'border-red-500': error }"
                :placeholder="placeholder"
                v-bind="$attrs"
            />
        </div>
        <p v-if="error" class="mt-1 text-xs text-red-500 font-medium">{{ error }}</p>
    </div>
</template>
