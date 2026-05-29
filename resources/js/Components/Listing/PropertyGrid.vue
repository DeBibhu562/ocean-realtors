<script setup>
import { onMounted, ref } from 'vue';
import PropertyCard from '../PropertyCard.vue';
import PropertyListCard from './PropertyListCard.vue';

const props = defineProps({
    properties: Array,
    isLoading: Boolean,
    view: String,
    hasMore: Boolean
});

const emit = defineEmits(['loadMore', 'clearFilters']);

const sentinel = ref(null);

onMounted(() => {
    const observer = new IntersectionObserver((entries) => {
        if (entries[0].isIntersecting && props.hasMore && !props.isLoading) {
            emit('loadMore');
        }
    }, { threshold: 0.1 });

    if (sentinel.value) observer.observe(sentinel.value);
});
</script>

<template>
    <div class="relative">
        <!-- Results Grid/List -->
        <div 
            v-if="properties.length > 0"
            class="transition-all duration-300"
            :class="[
                view === 'grid' 
                    ? 'grid grid-cols-1 gap-4 md:grid-cols-2 md:gap-5' 
                    : 'flex flex-col gap-4'
            ]"
        >
            <template v-for="(property, idx) in properties" :key="property.id">
                <Transition
                    enter-active-class="transition duration-200 ease-out"
                    enter-from-class="opacity-0 translate-y-2"
                    enter-to-class="opacity-100 translate-y-0"
                >
                    <PropertyCard v-if="view === 'grid'" :property="property" :lazy-image="idx !== 0" />
                    <PropertyListCard v-else :property="property" />
                </Transition>
            </template>
        </div>

        <!-- Skeleton Loaders -->
        <div 
            v-if="isLoading" 
            class="mt-4"
            :class="[
                view === 'grid' 
                    ? 'grid grid-cols-1 gap-4 md:grid-cols-2 md:gap-5' 
                    : 'flex flex-col gap-4'
            ]"
        >
            <div v-for="i in 6" :key="i" class="bg-white rounded-xl border border-border overflow-hidden animate-pulse">
                <div class="aspect-video bg-gray-100"></div>
                <div class="p-4 space-y-3">
                    <div class="h-4 bg-gray-100 rounded w-1/3"></div>
                    <div class="h-3 bg-gray-100 rounded w-full"></div>
                    <div class="h-3 bg-gray-100 rounded w-2/3"></div>
                    <div class="border-t border-gray-50 pt-3 flex gap-4">
                        <div class="h-3 bg-gray-100 rounded w-10"></div>
                        <div class="h-3 bg-gray-100 rounded w-10"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Empty State -->
        <div v-if="!isLoading && properties.length === 0" class="py-16 text-center">
            <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="h-10 w-10 text-text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-text-primary mb-1">No properties found</h3>
            <p class="text-sm text-text-secondary mb-6">Try adjusting your filters or search terms.</p>
            <button 
                @click="emit('clearFilters')" 
                class="text-sm font-semibold bg-primary text-white px-6 py-2 rounded-lg hover:bg-primary-hover shadow-sm"
            >
                Clear All Filters
            </button>
        </div>

        <!-- Infinite Scroll Sentinel -->
        <div ref="sentinel" class="h-10 w-full"></div>
    </div>
</template>
