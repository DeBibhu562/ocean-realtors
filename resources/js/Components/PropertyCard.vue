<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    property: {
        type: Object,
        required: true,
    }
});

const formatPrice = (price) => {
    return new Intl.NumberFormat('en-IN', {
        style: 'currency',
        currency: 'INR',
        maximumFractionDigits: 0,
    }).format(price);
};
</script>

<template>
    <div class="group bg-white rounded-2xl overflow-hidden border border-gray-100 shadow-sm hover:shadow-xl transition-all duration-500 transform hover:-translate-y-1">
        <!-- Image Container -->
        <div class="relative aspect-[4/3] overflow-hidden">
            <img 
                :src="property.image || 'https://images.unsplash.com/photo-1518780664697-55e3ad937233?auto=format&fit=crop&q=80&w=800'" 
                :alt="property.title"
                class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
            />
            
            <!-- Badges -->
            <div class="absolute top-4 left-4 flex flex-col gap-2">
                <span 
                    v-if="property.status === 'rent'" 
                    class="bg-primary/90 backdrop-blur-md text-white text-[10px] font-black px-3 py-1 rounded-full uppercase tracking-widest shadow-lg"
                >
                    FOR RENT
                </span>
                <span 
                    v-else 
                    class="bg-accent/90 backdrop-blur-md text-white text-[10px] font-black px-3 py-1 rounded-full uppercase tracking-widest shadow-lg"
                >
                    FOR SALE
                </span>
                <span 
                    v-if="property.isFeatured" 
                    class="bg-yellow-400 text-navy text-[9px] font-black px-3 py-1 rounded-full uppercase tracking-widest shadow-lg flex items-center"
                >
                    <svg class="h-3 w-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>
                    FEATURED
                </span>
            </div>

            <!-- Price Overlay (Glassmorphism) -->
            <div class="absolute bottom-4 left-4 right-4">
                <div class="bg-navy/60 backdrop-blur-xl border border-white/10 rounded-xl p-3 flex items-center justify-between shadow-2xl">
                    <div class="flex flex-col">
                        <span class="text-white text-lg font-black leading-none">{{ formatPrice(property.price) }}</span>
                        <span v-if="property.status === 'rent'" class="text-white/60 text-[10px] mt-1 font-bold">per month</span>
                    </div>
                    <div class="h-8 w-8 rounded-lg bg-primary flex items-center justify-center text-white shadow-lg">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="p-5">
            <h3 class="text-base font-bold text-navy truncate group-hover:text-primary transition-colors duration-300">
                <Link :href="route('properties.show', property.id)">{{ property.title }}</Link>
            </h3>

            <p class="text-xs text-text-secondary flex items-center gap-1.5 mt-2 font-medium">
                <svg class="h-4 w-4 text-primary/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                </svg>
                {{ property.address }}, {{ property.city }}
            </p>

            <!-- Divider -->
            <div class="h-px bg-gray-50 my-4"></div>

            <!-- Stats Row -->
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="flex items-center gap-1.5 text-xs text-navy font-bold">
                        <div class="w-7 h-7 rounded-lg bg-gray-50 flex items-center justify-center text-primary">
                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
                        </div>
                        <span>{{ property.beds }}</span>
                    </div>
                    <div class="flex items-center gap-1.5 text-xs text-navy font-bold">
                        <div class="w-7 h-7 rounded-lg bg-gray-50 flex items-center justify-center text-primary">
                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                        </div>
                        <span>{{ property.baths }}</span>
                    </div>
                </div>
                
                <div class="flex items-center gap-1.5 text-xs text-navy font-bold">
                    <div class="w-7 h-7 rounded-lg bg-gray-50 flex items-center justify-center text-primary">
                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4" /></svg>
                    </div>
                    <span>{{ property.area }} <span class="text-[9px] font-medium text-text-muted">sqft</span></span>
                </div>
            </div>
        </div>
    </div>
</template>

