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
    <section ref="statsSection" class="relative border-b border-indigo-100/60 bg-gradient-to-b from-indigo-50/70 via-white to-white py-12">
        <div class="container max-w-6xl mx-auto px-4">
            <div class="grid grid-cols-2 gap-3 md:grid-cols-4 md:gap-4">
                <div 
                    v-for="(stat, i) in stats" 
                    :key="stat.label"
                    class="group relative rounded-2xl border border-indigo-100/70 bg-white/95 px-3 py-5 text-center shadow-sm transition-all hover:-translate-y-0.5 hover:shadow-md"
                >
                    <div class="mx-auto mb-2 h-1.5 w-10 rounded-full bg-primary/20 transition-all group-hover:w-14 group-hover:bg-primary/35"></div>
                    <div class="text-2xl font-black text-navy leading-none md:text-[2rem]">
                        {{ formatNumber(counts[i]) }}{{ stat.suffix }}
                    </div>
                    <div class="mt-2 text-[11px] font-bold uppercase tracking-[0.18em] text-slate-500 md:text-xs">
                        {{ stat.label }}
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
