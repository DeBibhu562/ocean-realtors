<script setup>
import { Link } from '@inertiajs/vue3';

const ctas = [
    {
        title: 'Looking to Buy or Rent?',
        description: 'Explore the finest properties curated specifically for your lifestyle and needs.',
        buttonText: 'Find Your Home',
        link: '/properties',
        icon: 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'
    }
];
</script>

<template>
    <section class="section-y-sm bg-gray-100">
        <div class="container-page max-w-6xl flex justify-center">
            <div 
                v-for="cta in ctas" 
                :key="cta.title"
                class="bg-[#f9fafb] rounded-xl p-8 border border-border flex flex-col items-center text-center group transition-all duration-300 max-w-xl w-full"
            >
                <div class="w-12 h-12 rounded-lg bg-primary/10 flex items-center justify-center mb-6 transition-transform group-hover:scale-110">
                    <svg class="h-8 w-8 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="cta.icon" />
                    </svg>
                </div>
                
                <h3 class="text-lg font-semibold text-text-primary mb-3">
                    {{ cta.title }}
                </h3>
                <p class="text-sm text-text-secondary mb-8 max-w-xs">
                    {{ cta.description }}
                </p>
                
                <Link 
                    :href="cta.link" 
                    class="text-sm font-semibold border border-primary text-primary px-6 py-2 rounded-lg hover:bg-primary hover:text-white transition-all shadow-sm"
                >
                    {{ cta.buttonText }}
                </Link>
            </div>
        </div>
    </section>
</template>
