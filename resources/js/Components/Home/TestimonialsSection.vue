<script setup>
import { ref, onMounted, onUnmounted } from 'vue';

const testimonials = [
    {
        name: 'Sarah Johnson',
        role: 'Homeowner',
        city: 'Mumbai',
        avatar: 'SJ',
        quote: 'Finding my dream home with Ocean Realtors was the best decision. Their attention to detail and market knowledge is unmatched.',
        rating: 5
    },
    {
        name: 'Michael Chen',
        role: 'Investor',
        city: 'Delhi',
        avatar: 'MC',
        quote: 'The data-driven approach Ocean Realtors uses helped me secure a high-yield property in record time. Highly professional team!',
        rating: 5
    },
    {
        name: 'Priya Sharma',
        role: 'Tenant',
        city: 'Bangalore',
        avatar: 'PS',
        quote: 'Excellent service! They found me a perfect apartment that fits my budget and is close to my office. Highly recommend them.',
        rating: 4
    },
];

const currentIndex = ref(0);
const interval = ref(null);
const isPaused = ref(false);
const windowWidth = ref(typeof window !== 'undefined' ? window.innerWidth : 1200);

const handleResize = () => {
    windowWidth.value = window.innerWidth;
};

const startAutoAdvance = () => {
    interval.value = setInterval(() => {
        if (!isPaused.value) {
            next();
        }
    }, 5000);
};

const next = () => {
    currentIndex.value = (currentIndex.value + 1) % testimonials.length;
};

const prev = () => {
    currentIndex.value = (currentIndex.value - 1 + testimonials.length) % testimonials.length;
};

onMounted(() => {
    startAutoAdvance();
    window.addEventListener('resize', handleResize);
});

onUnmounted(() => {
    clearInterval(interval.value);
    window.removeEventListener('resize', handleResize);
});
</script>

<template>
    <section class="py-12 bg-surface overflow-hidden">
        <div class="container max-w-6xl mx-auto px-4">
            <div class="mb-10 text-center">
                <h2 class="text-xl font-semibold text-text-primary">
                    What our clients say
                </h2>
                <p class="text-sm text-text-secondary">Trusted by thousands of happy homeowners and investors.</p>
            </div>

            <div 
                class="relative"
                @mouseenter="isPaused = true"
                @mouseleave="isPaused = false"
            >
                <div class="flex transition-transform duration-500 ease-in-out" :style="{ transform: `translateX(-${currentIndex * 100}%)` }">
                    <div 
                        v-for="(t, i) in testimonials" 
                        :key="i"
                        class="w-full shrink-0 flex justify-center"
                    >
                        <div class="max-w-sm w-full bg-white p-6 rounded-xl border border-border shadow-sm flex flex-col justify-between">
                            <div>
                                <div class="flex text-star mb-4">
                                    <svg v-for="star in 5" :key="star" class="h-4 w-4" :class="star <= t.rating ? 'fill-current' : 'fill-gray-200'" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                </div>
                                <p class="text-sm italic text-text-secondary leading-relaxed mb-6">
                                    "{{ t.quote }}"
                                </p>
                            </div>
                            
                            <div class="flex items-center">
                                <div class="w-8 h-8 rounded-full bg-primary flex items-center justify-center text-white text-[10px] font-bold">
                                    {{ t.avatar }}
                                </div>
                                <div class="ml-3">
                                    <h4 class="text-sm font-medium text-text-primary leading-tight">{{ t.name }}</h4>
                                    <p class="text-xs text-text-muted mt-0.5">{{ t.role }} • {{ t.city }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Controls -->
                <div class="hidden md:flex justify-between absolute top-1/2 -inset-x-4 -translate-y-1/2 pointer-events-none">
                    <button @click="prev" class="w-8 h-8 rounded-full bg-white border border-border flex items-center justify-center text-text-secondary hover:text-primary hover:border-primary shadow-sm pointer-events-auto transition-all">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                    </button>
                    <button @click="next" class="w-8 h-8 rounded-full bg-white border border-border flex items-center justify-center text-text-secondary hover:text-primary hover:border-primary shadow-sm pointer-events-auto transition-all">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                    </button>
                </div>
            </div>

            <!-- Indicators -->
            <div class="flex justify-center mt-6 space-x-1.5">
                <button 
                    v-for="(_, i) in testimonials" 
                    :key="i"
                    @click="currentIndex = i"
                    class="w-1.5 h-1.5 rounded-full transition-all duration-300"
                    :class="currentIndex === i ? 'bg-primary w-4' : 'bg-gray-200'"
                ></button>
            </div>
        </div>
    </section>
</template>
