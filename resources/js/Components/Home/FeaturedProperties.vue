<script setup>
import { ref, onMounted, watch } from 'vue';
import { Link } from '@inertiajs/vue3';
import axios from 'axios';
import PropertyCard from '../PropertyCard.vue';

const tabs = [
    { label: 'All', value: '' },
    { label: 'Apartment', value: 'apartment' },
    { label: 'House', value: 'house' },
    { label: 'Villa', value: 'villa' },
];

const activeTab = ref('');
const properties = ref([]);
const isLoading = ref(false);

const fetchFeatured = async () => {
    isLoading.value = true;
    try {
        const response = await axios.get('/api/properties', {
            params: { featured: 1, type: activeTab.value, limit: 6 }
        });
        properties.value = response.data.data;
    } catch (err) {
        console.error(err);
    } finally {
        isLoading.value = false;
    }
};

onMounted(fetchFeatured);
watch(activeTab, fetchFeatured);
</script>

<template>
    <section class="py-12 bg-white border-t border-border">
        <div class="container max-w-6xl mx-auto px-4">
            <div class="flex items-end justify-between mb-8 gap-4">
                <div>
                    <h2 class="text-xl font-semibold text-text-primary">
                        Featured properties
                    </h2>
                    <p class="text-sm text-text-secondary">Discover handpicked premium properties across prime locations.</p>
                </div>
                <Link 
                    :href="route('properties.index', { featured: 1 })" 
                    class="text-primary font-semibold text-sm flex items-center hover:underline"
                >
                    View all
                    <svg class="h-4 w-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                </Link>
            </div>

            <!-- Tab Filters -->
            <div class="flex flex-wrap gap-2 mb-8">
                <button 
                    v-for="tab in tabs" 
                    :key="tab.value"
                    @click="activeTab = tab.value"
                    class="px-4 py-1.5 rounded-lg text-sm font-medium transition-all"
                    :class="activeTab === tab.value ? 'bg-primary text-white shadow-sm' : 'bg-gray-50 text-text-secondary hover:bg-gray-100'"
                >
                    {{ tab.label }}
                </button>
            </div>

            <!-- Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                <template v-if="!isLoading">
                    <PropertyCard 
                        v-for="property in properties" 
                        :key="property.id" 
                        :property="property"
                    />
                </template>
                <template v-else>
                    <div v-for="i in 3" :key="i" class="bg-gray-50 rounded-xl aspect-video animate-pulse border border-border"></div>
                </template>
            </div>
        </div>
    </section>
</template>
