<script setup>
import { ref, computed, watch } from 'vue';
import axios from 'axios';
import { usePropertyFilters } from '@/Composables/usePropertyFilters';

const { filters, runSearch } = usePropertyFilters();

const categories = [
    { id: 'buy', label: 'BUY', status: 'sale' },
    { id: 'rent', label: 'RENT', status: 'rent' },
    { id: 'commercial', label: 'COMMERCIAL', type: ['office', 'commercial'] },
    { id: 'pg', label: 'PG/CO-LIVING', type: ['pg'] },
    { id: 'plots', label: 'PLOTS', type: ['plot'] },
];

const activeCategory = ref(categories.find(c => c.status === filters.status) || categories[0]);
const commercialListingType = ref(filters.status === 'rent' ? 'lease' : 'buy');

const suggestions = ref([]);
const showSuggestions = ref(false);
const isSearching = ref(false);

const cities = ['Gurgaon', 'Delhi', 'Noida', 'Mumbai', 'Bangalore', 'Pune'];
const showCityDropdown = ref(false);

const bgGradients = {
    buy: 'from-[#4c1d95] via-[#5b21b6] to-[#6d28d9]', // Purple
    rent: 'from-[#1e3a8a] via-[#1e40af] to-[#1d4ed8]', // Blue
    commercial: 'from-[#065f46] via-[#047857] to-[#059669]', // Green
    pg: 'from-[#92400e] via-[#b45309] to-[#d97706]', // Orange
    plots: 'from-[#3f6212] via-[#4d7c0f] to-[#65a30d]', // Lime/Green
};

const activeGradient = computed(() => bgGradients[activeCategory.value.id]);

let debounceTimeout = null;

watch(() => filters.keyword, (newVal) => {
    // Don't fetch suggestions if we're just syncing from URL or if it's too short
    if (!newVal || newVal.length < 2) {
        suggestions.value = [];
        showSuggestions.value = false;
        return;
    }

    if (debounceTimeout) clearTimeout(debounceTimeout);
    debounceTimeout = setTimeout(async () => {
        isSearching.value = true;
        try {
            const response = await axios.get('/api/locations', { params: { q: newVal } });
            suggestions.value = response.data;
            showSuggestions.value = suggestions.value.length > 0;
        } catch (error) {
            console.error('Error fetching suggestions:', error);
        } finally {
            isSearching.value = false;
        }
    }, 300);
});

const selectSuggestion = (s) => {
    if (s.type === 'CITY') {
        filters.city = s.name;
        filters.keyword = '';
    } else {
        filters.keyword = s.name;
    }
    showSuggestions.value = false;
};

const handleSearch = () => {
    if (activeCategory.value.id === 'commercial') {
        filters.status = commercialListingType.value === 'lease' ? 'rent' : 'sale';
        filters.type = ['office', 'commercial'];
    } else {
        filters.status = activeCategory.value.status || 'all';
        filters.type = activeCategory.value.type || [];
    }

    runSearch();
};

const selectCity = (c) => {
    filters.city = c;
    showCityDropdown.value = false;
};

const closeAllDropdowns = () => {
    showSuggestions.value = false;
    showCityDropdown.value = false;
};
</script>

<template>
    <section 
        class="relative min-h-[580px] flex flex-col justify-center transition-colors duration-500 overflow-hidden"
        :class="activeGradient ? `bg-gradient-to-br ${activeGradient}` : 'bg-navy'"
    >
        <!-- Background Elements -->
        <div class="absolute inset-0 opacity-20 pointer-events-none">
            <div class="absolute top-0 right-0 w-1/2 h-full bg-no-repeat bg-contain bg-right-bottom" style="background-image: url('https://housing.com/assets/cityscape-light.5f1f91b7.svg')"></div>
        </div>

        <!-- Main Content -->
        <div class="container max-w-6xl mx-auto px-4 relative z-10 pt-20 pb-12">
            <div class="text-center mb-10">
                <h1 class="text-4xl md:text-5xl font-bold text-white mb-4 drop-shadow-sm">
                    Properties to {{ activeCategory.id === 'rent' ? 'rent' : 'buy' }} in {{ city }}
                </h1>
                <div class="flex items-center justify-center space-x-4 text-white/90 text-sm font-medium">
                    <span><strong class="text-white">8K+</strong> listings added daily</span>
                    <span class="w-1 h-1 bg-white/40 rounded-full"></span>
                    <span><strong class="text-white">76K+</strong> total verified</span>
                </div>
            </div>

            <!-- Search Card -->
            <div class="max-w-4xl mx-auto">
                <div class="bg-black/20 backdrop-blur-md rounded-t-2xl flex overflow-x-auto no-scrollbar border-b border-white/10">
                    <button 
                        v-for="cat in categories" 
                        :key="cat.id"
                        @click="activeCategory = cat"
                        class="px-8 py-4 text-xs font-bold tracking-wider transition-all whitespace-nowrap relative"
                        :class="activeCategory.id === cat.id ? 'text-white' : 'text-white/60 hover:text-white/80'"
                    >
                        {{ cat.label }}
                        <div v-if="activeCategory.id === cat.id" class="absolute bottom-0 left-1/2 -translate-x-1/2 w-8 h-1 bg-white rounded-full"></div>
                    </button>
                </div>

                <div class="bg-white p-2 rounded-b-2xl shadow-2xl relative">
                    <div class="flex flex-col md:flex-row items-stretch gap-2">
                        <!-- City Selector -->
                        <div class="relative min-w-[140px] border-r border-gray-100 hidden md:block">
                            <button 
                                @click="showCityDropdown = !showCityDropdown"
                                class="w-full h-14 px-4 flex items-center justify-between text-sm font-semibold text-text-primary hover:bg-gray-50 transition-colors"
                            >
                                <span>{{ filters.city || 'Select City' }}</span>
                                <svg class="h-4 w-4 text-text-muted transition-transform" :class="{ 'rotate-180': showCityDropdown }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>

                            <div v-if="showCityDropdown" class="absolute left-0 top-full mt-2 w-48 bg-white rounded-xl shadow-xl z-50 border border-gray-100 py-2">
                                <button 
                                    v-for="c in cities" 
                                    :key="c"
                                    @click="selectCity(c)"
                                    class="w-full px-4 py-2 text-left text-sm text-text-secondary hover:bg-gray-50 hover:text-primary transition-colors"
                                >
                                    {{ c }}
                                </button>
                            </div>
                        </div>

                        <!-- Search Input -->
                        <div class="flex-1 relative">
                            <div class="absolute left-4 top-1/2 -translate-y-1/2">
                                <svg class="h-5 w-5 text-text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                            <input 
                                v-model="filters.keyword"
                                type="text" 
                                placeholder="Search for locality, landmark, project, or builder"
                                class="w-full h-14 pl-12 pr-4 text-base border-none focus:ring-0 placeholder:text-text-muted/60 font-medium"
                                @focus="showSuggestions = suggestions.length > 0"
                                @blur="setTimeout(() => showSuggestions = false, 200)"
                            />

                            <!-- Suggestions Dropdown -->
                            <div v-if="showSuggestions" class="absolute left-0 right-0 top-full mt-2 bg-white rounded-xl shadow-2xl z-50 border border-gray-100 overflow-hidden">
                                <ul class="divide-y divide-gray-50">
                                    <li 
                                        v-for="s in suggestions" 
                                        :key="s.name"
                                        @click="selectSuggestion(s)"
                                        class="px-4 py-3 hover:bg-primary/5 cursor-pointer flex items-start group transition-colors"
                                    >
                                        <div class="w-9 h-9 rounded-lg bg-gray-100 flex items-center justify-center mr-3 shrink-0 group-hover:bg-primary/10 transition-colors">
                                            <svg v-if="s.type === 'CITY'" class="h-5 w-5 text-text-muted group-hover:text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                                            <svg v-else-if="s.type === 'PROJECT'" class="h-5 w-5 text-text-muted group-hover:text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                                            <svg v-else class="h-5 w-5 text-text-muted group-hover:text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /></svg>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="text-sm font-semibold text-text-primary truncate">{{ s.name }}</div>
                                            <div class="text-[10px] font-bold text-text-muted flex items-center mt-0.5">
                                                <span class="text-primary/70 mr-1.5">{{ s.type }}</span>
                                                <span class="w-1 h-1 bg-gray-300 rounded-full mr-1.5"></span>
                                                <span class="truncate">{{ s.subtitle }}</span>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <!-- Special Options (Radio Buttons) -->
                        <div v-if="activeCategory.id === 'commercial'" class="hidden lg:flex items-center space-x-6 px-4 border-l border-gray-100">
                            <label class="flex items-center cursor-pointer group">
                                <input type="radio" value="buy" v-model="commercialListingType" class="hidden" />
                                <div class="w-5 h-5 rounded-full border-2 flex items-center justify-center mr-2 transition-colors" :class="commercialListingType === 'buy' ? 'border-primary' : 'border-gray-200 group-hover:border-gray-300'">
                                    <div v-if="commercialListingType === 'buy'" class="w-2.5 h-2.5 bg-primary rounded-full"></div>
                                </div>
                                <span class="text-sm font-semibold" :class="commercialListingType === 'buy' ? 'text-primary' : 'text-text-secondary'">Buy</span>
                            </label>
                            <label class="flex items-center cursor-pointer group">
                                <input type="radio" value="lease" v-model="commercialListingType" class="hidden" />
                                <div class="w-5 h-5 rounded-full border-2 flex items-center justify-center mr-2 transition-colors" :class="commercialListingType === 'lease' ? 'border-primary' : 'border-gray-200 group-hover:border-gray-300'">
                                    <div v-if="commercialListingType === 'lease'" class="w-2.5 h-2.5 bg-primary rounded-full"></div>
                                </div>
                                <span class="text-sm font-semibold" :class="commercialListingType === 'lease' ? 'text-primary' : 'text-text-secondary'">Lease</span>
                            </label>
                        </div>

                        <!-- Search Button -->
                        <div class="p-1">
                            <button 
                                @click="handleSearch"
                                class="w-full md:w-32 h-12 md:h-full bg-primary hover:bg-primary-hover text-white rounded-xl font-bold text-base shadow-lg shadow-primary/20 transition-all active:scale-95 flex items-center justify-center space-x-2"
                            >
                                <span>Search</span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Banner Removed as requested -->
            </div>
        </div>

        <!-- Floating Accent Images (Cutouts) -->
        <div class="absolute right-0 bottom-0 w-1/3 h-2/3 pointer-events-none hidden xl:block">
            <div class="relative w-full h-full">
                <img 
                    src="/Users/ashishtayal/.gemini/antigravity/brain/1fe0d535-aed3-4752-8d1c-28b4177c7b31/premium_luxury_villa_hero_1778845711975.png" 
                    class="absolute bottom-0 right-0 w-[80%] h-[90%] object-cover rounded-tl-[100px] border-8 border-white/10 shadow-2xl"
                    alt="Luxury Property"
                />
                <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent rounded-tl-[100px]"></div>
            </div>
        </div>
    </section>
</template>

<style scoped>
.no-scrollbar::-webkit-scrollbar {
    display: none;
}
.no-scrollbar {
    -ms-overflow-style: none;
    scrollbar-width: none;
}
</style>
