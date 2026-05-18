<script setup>
import { ref, onMounted } from 'vue';

const stats = [
    { label: 'Properties Listed', value: 2500, suffix: '+' },
    { label: 'Cities Covered', value: 48, suffix: '+' },
    { label: 'Happy Clients', value: 12000, suffix: '+' },
    { label: 'Expert Agents', value: 350, suffix: '+' },
];

const counts = ref(stats.map(() => 0));
const statsSection = ref(null);

const animateValue = (index, target) => {
    const duration = 1500;
    const startTime = performance.now();
    const update = (currentTime) => {
        const elapsed = currentTime - startTime;
        const progress = Math.min(elapsed / duration, 1);
        const ease = 1 - Math.pow(1 - progress, 3);
        counts.value[index] = Math.floor(ease * target);
        if (progress < 1) requestAnimationFrame(update);
    };
    requestAnimationFrame(update);
};

onMounted(() => {
    const observer = new IntersectionObserver((entries) => {
        if (entries[0].isIntersecting) {
            stats.forEach((stat, i) => animateValue(i, stat.value));
            observer.disconnect();
        }
    }, { threshold: 0.1 });
    if (statsSection.value) observer.observe(statsSection.value);
});

const formatNumber = (num) => new Intl.NumberFormat().format(num);
</script>

<template>
    <section ref="statsSection" class="bg-white border-b border-border py-10">
        <div class="container max-w-6xl mx-auto px-4">
            <div class="grid grid-cols-2 md:grid-cols-4">
                <div 
                    v-for="(stat, i) in stats" 
                    :key="stat.label"
                    class="text-center relative py-4"
                >
                    <!-- Vertical Separator -->
                    <div 
                        v-if="i !== 0" 
                        class="hidden md:block absolute left-0 top-1/2 -translate-y-1/2 w-px h-10 bg-border"
                    ></div>
                    
                    <div class="text-2xl font-bold text-text-primary leading-none">
                        {{ formatNumber(counts[i]) }}{{ stat.suffix }}
                    </div>
                    <div class="text-xs text-text-secondary font-semibold uppercase tracking-wider mt-1.5">
                        {{ stat.label }}
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
