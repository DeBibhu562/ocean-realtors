<script setup>
import BaseBadge from '../BaseBadge.vue';
import BaseButton from '../BaseButton.vue';

defineProps({
    property: {
        type: Object,
        required: true
    }
});

const formatPrice = (price) => {
    return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD',
        maximumFractionDigits: 0,
    }).format(price);
};
</script>

<template>
    <div class="group bg-white rounded-2xl overflow-hidden shadow-premium hover:shadow-premium-hover transition-all duration-300 flex flex-col sm:flex-row">
        <!-- Image -->
        <div class="relative w-full sm:w-[35%] aspect-[4/3] sm:aspect-auto overflow-hidden">
            <img 
                :src="property.image || 'https://via.placeholder.com/400x300?text=No+Image'" 
                :alt="property.title"
                class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
            />
            <div class="absolute top-4 left-4 flex flex-col space-y-2">
                <BaseBadge variant="rent" v-if="property.status === 'rent'">FOR RENT</BaseBadge>
                <BaseBadge variant="sale" v-else-if="property.status === 'sale'">FOR SALE</BaseBadge>
            </div>
        </div>

        <!-- Content -->
        <div class="p-6 flex-1 flex flex-col justify-between">
            <div>
                <div class="flex justify-between items-start mb-2">
                    <h3 class="text-2xl font-black text-primary">
                        {{ formatPrice(property.price) }}<span v-if="property.status === 'rent'" class="text-sm font-medium text-primary/40">/mo</span>
                    </h3>
                    <button class="text-primary/20 hover:text-red-500 transition-colors">
                        <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z" />
                        </svg>
                    </button>
                </div>
                
                <h4 class="text-xl font-bold text-primary group-hover:text-accent transition-colors truncate mb-1">
                    {{ property.title }}
                </h4>
                <p class="flex items-center text-primary/50 text-sm mb-4">
                    <svg class="h-4 w-4 mr-1 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    {{ property.address }}
                </p>

                <p class="text-sm text-primary/60 line-clamp-2 mb-6 leading-relaxed">
                    {{ property.description || 'This premium property offers a sophisticated living experience with modern architecture and high-end finishes throughout the entire space.' }}
                </p>

                <!-- Stats -->
                <div class="flex items-center space-x-6 mb-6">
                    <div class="flex items-center text-sm font-bold text-primary">
                        <span class="w-8 h-8 rounded-lg bg-surface-gray flex items-center justify-center mr-2 text-accent">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                        </span>
                        {{ property.beds }} Beds
                    </div>
                    <div class="flex items-center text-sm font-bold text-primary">
                        <span class="w-8 h-8 rounded-lg bg-surface-gray flex items-center justify-center mr-2 text-accent">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </span>
                        {{ property.baths }} Baths
                    </div>
                    <div class="flex items-center text-sm font-bold text-primary">
                        <span class="w-8 h-8 rounded-lg bg-surface-gray flex items-center justify-center mr-2 text-accent">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4" />
                            </svg>
                        </span>
                        {{ property.area }} sqft
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-between pt-4 border-t border-gray-50">
                <div class="flex items-center">
                    <img :src="property.agent?.avatar || 'https://via.placeholder.com/40'" class="w-8 h-8 rounded-full border-2 border-white shadow-sm mr-2" />
                    <span class="text-xs font-bold text-primary/60">{{ property.agent?.name || 'Agent Name' }}</span>
                </div>
                <BaseButton variant="primary" size="sm">View Details</BaseButton>
            </div>
        </div>
    </div>
</template>
