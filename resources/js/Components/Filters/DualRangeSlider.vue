<script setup>
import { computed, ref, watch } from 'vue';

const props = defineProps({
    min: { type: Number, default: 0 },
    max: { type: Number, default: 10000000 },
    step: { type: Number, default: 1000 },
    minValue: { type: Number, required: true },
    maxValue: { type: Number, required: true },
    currency: { type: String, default: '₹' }
});

const emit = defineEmits(['update:minValue', 'update:maxValue']);

const minVal = ref(props.minValue);
const maxVal = ref(props.maxValue);

watch(() => props.minValue, (newVal) => minVal.value = newVal);
watch(() => props.maxValue, (newVal) => maxVal.value = newVal);

const minPercent = computed(() => ((minVal.value - props.min) / (props.max - props.min)) * 100);
const maxPercent = computed(() => ((maxVal.value - props.min) / (props.max - props.min)) * 100);

const handleMin = () => {
    if (minVal.value > maxVal.value - props.step) {
        minVal.value = maxVal.value - props.step;
    }
    emit('update:minValue', minVal.value);
};

const handleMax = () => {
    if (maxVal.value < minVal.value + props.step) {
        maxVal.value = minVal.value + props.step;
    }
    emit('update:maxValue', maxVal.value);
};

const formatPrice = (value) => {
    if (value >= 10000000) return '1Cr+';
    if (value >= 100000) return (value / 100000).toFixed(1) + 'L';
    if (value >= 1000) return (value / 1000).toFixed(0) + 'K';
    return value;
};
</script>

<template>
    <div class="relative w-full py-6">
        <div class="h-1.5 w-full bg-gray-200 rounded-full absolute top-1/2 -translate-y-1/2"></div>
        <div 
            class="h-1.5 bg-accent rounded-full absolute top-1/2 -translate-y-1/2"
            :style="{ left: minPercent + '%', right: (100 - maxPercent) + '%' }"
        ></div>

        <input 
            type="range" 
            :min="min" 
            :max="max" 
            :step="step"
            v-model.number="minVal"
            @input="handleMin"
            class="absolute top-1/2 -translate-y-1/2 w-full appearance-none bg-transparent pointer-events-none z-20 slider-thumb"
        />
        <input 
            type="range" 
            :min="min" 
            :max="max" 
            :step="step"
            v-model.number="maxVal"
            @input="handleMax"
            class="absolute top-1/2 -translate-y-1/2 w-full appearance-none bg-transparent pointer-events-none z-20 slider-thumb"
        />

        <div class="flex justify-between mt-8 text-xs font-bold text-primary/60">
            <span>{{ currency }}{{ formatPrice(minVal) }}</span>
            <span>{{ currency }}{{ formatPrice(maxVal) }}</span>
        </div>
    </div>
</template>

<style scoped>
.slider-thumb::-webkit-slider-thumb {
    @apply appearance-none h-5 w-5 rounded-full bg-white border-2 border-accent cursor-pointer pointer-events-auto shadow-md transition-transform active:scale-125;
}
.slider-thumb::-moz-range-thumb {
    @apply appearance-none h-5 w-5 rounded-full bg-white border-2 border-accent cursor-pointer pointer-events-auto shadow-md transition-transform active:scale-125;
}
</style>
