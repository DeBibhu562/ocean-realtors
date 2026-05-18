<script setup>
import { Link } from '@inertiajs/vue3';

const categories = [
    { name: 'Apartment', value: 'apartment', count: 1240, icon: 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4' },
    { name: 'Independent House', value: 'house', count: 850, icon: 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6' },
    { name: 'Villa', value: 'villa', count: 420, icon: 'M8 14v20c0 4.418 7.163 8 16 8 1.387 0 2.717-.087 3.998-.253V21.747c-1.281.166-2.611.253-3.998.253-8.837 0-16-3.582-16-8z' },
    { name: 'Office Space', value: 'office', count: 310, icon: 'M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745V6a2 2 0 012-2h14a2 2 0 012 2v7.255z' },
    { name: 'Plot / Land', value: 'plot', count: 180, icon: 'M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0121 18.382V7.618a1 1 0 01-1.447-.894L15 9m0 8V9' },
    { name: 'Commercial Shop', value: 'commercial', count: 95, icon: 'M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z' },
];
</script>

<template>
    <section class="py-12 bg-white">
        <div class="container max-w-6xl mx-auto px-4">
            <div class="mb-8">
                <h2 class="text-xl font-semibold text-text-primary">
                    Browse by property type
                </h2>
                <p class="text-sm text-text-secondary">Discover the perfect property category for your needs.</p>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
                <Link 
                    v-for="cat in categories" 
                    :key="cat.value"
                    :href="route('properties.index', { type: cat.value })"
                    class="group p-4 rounded-xl bg-surface border border-border hover:border-primary hover:shadow-sm transition-all duration-200 text-center flex flex-col items-center"
                >
                    <div class="w-10 h-10 rounded-lg bg-white flex items-center justify-center mb-3 border border-border group-hover:border-primary/20 transition-colors">
                        <svg class="h-6 w-6 text-text-secondary group-hover:text-primary transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="cat.icon" />
                        </svg>
                    </div>
                    <h3 class="text-sm font-medium text-text-primary transition-colors group-hover:text-primary">
                        {{ cat.name }}
                    </h3>
                    <span class="text-xs text-text-muted mt-0.5">
                        {{ cat.count }} Listings
                    </span>
                </Link>
            </div>
        </div>
    </section>
</template>
