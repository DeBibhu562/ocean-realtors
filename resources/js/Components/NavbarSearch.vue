<script setup>
import { ref, watch, onMounted } from 'vue';
import axios from 'axios';
import { usePropertyFilters } from '@/Composables/usePropertyFilters';

const emit = defineEmits(['search']);

const { filters, runSearch } = usePropertyFilters();
const suggestions = ref([]);
const showSuggestions = ref(false);
const cities = ['Gurgaon', 'Delhi', 'Noida', 'Faridabad', 'Ghaziabad'];

let debounceTimeout = null;

const fetchSuggestions = (val) => {
    if (debounceTimeout) window.clearTimeout(debounceTimeout);
    
    if (!val || val.length < 2) {
        suggestions.value = [];
        showSuggestions.value = false;
        return;
    }

    debounceTimeout = window.setTimeout(async () => {
        try {
            const response = await axios.get('/api/locations', { params: { q: val } });
            suggestions.value = response.data;
            showSuggestions.value = suggestions.value.length > 0;
        } catch (error) {
            console.error('Error fetching suggestions:', error);
        }
    }, 300);
};

const handleBlur = () => {
    window.setTimeout(() => {
        showSuggestions.value = false;
    }, 200);
};

const selectSuggestion = (s) => {
    if (s.type === 'CITY') {
        filters.city = s.name;
        filters.keyword = '';
    } else {
        filters.keyword = s.name;
    }
    showSuggestions.value = false;
    emit('search');
};
</script>

<template>
    <div class="flex-1 flex items-center bg-white rounded-xl h-11 max-w-2xl mx-6 shadow-sm border border-white/10 group focus-within:ring-2 focus-within:ring-primary/20 transition-all relative">
        <!-- City Selector (Premium Dropdown) -->
        <div class="relative h-full hidden sm:block">
            <select 
                v-model="filters.city"
                class="h-full px-4 border-r border-gray-100 bg-gray-50/50 rounded-l-xl text-[11px] font-black text-navy uppercase tracking-widest cursor-pointer focus:ring-0 focus:border-gray-100 transition-colors hover:bg-gray-100"
            >
                <option value="">Everywhere</option>
                <option v-for="c in cities" :key="c" :value="c">{{ c }}</option>
            </select>
        </div>
        
        <!-- Search Input -->
        <div class="flex-1 relative h-full">
            <input 
                v-model="filters.keyword"
                type="text" 
                placeholder="Search for locality, project, landmark..."
                class="w-full h-full border-none focus:ring-0 text-[13px] font-bold text-navy placeholder:text-text-muted/60 pl-4 transition-all"
                @input="fetchSuggestions($event.target.value)"
                @focus="showSuggestions = suggestions.length > 0"
                @blur="handleBlur"
                @keyup.enter="emit('search')"
            />

            <!-- Suggestions (Premium Dropdown) -->
            <div v-if="showSuggestions" class="absolute left-0 right-0 top-full mt-2 bg-white rounded-2xl shadow-2xl z-[500] border border-gray-100 overflow-hidden py-2 shadow-primary/10">
                <div class="px-4 py-2 border-b border-gray-50 mb-1">
                    <span class="text-[9px] font-black text-text-muted uppercase tracking-widest">Suggestions</span>
                </div>
                <ul class="divide-y divide-gray-50/50">
                    <li 
                        v-for="s in suggestions" 
                        :key="s.name"
                        @click="selectSuggestion(s)"
                        class="px-4 py-3 hover:bg-primary/5 cursor-pointer flex items-center group transition-colors"
                    >
                        <div class="w-8 h-8 rounded-lg bg-gray-50 flex items-center justify-center mr-3 shrink-0 group-hover:bg-primary group-hover:text-white transition-all">
                            <svg v-if="s.type === 'CITY'" class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                            <svg v-else-if="s.type === 'PROJECT'" class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                            <svg v-else class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /></svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="text-[12px] font-bold text-navy truncate group-hover:text-primary transition-colors">{{ s.name }}</div>
                            <div class="text-[9px] font-bold text-text-muted flex items-center mt-0.5 uppercase tracking-tighter">
                                <span class="text-primary/70 mr-1.5">{{ s.type }}</span>
                                <span class="w-1 h-1 bg-gray-200 rounded-full mr-1.5"></span>
                                <span class="truncate">{{ s.subtitle }}</span>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>

        <button 
            @click="emit('search')"
            class="px-6 bg-primary text-white h-full rounded-r-xl hover:bg-primary-hover transition-all flex items-center group shadow-inner"
        >
            <svg class="h-5 w-5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
        </button>
    </div>
</template>
