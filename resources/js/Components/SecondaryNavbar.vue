<script setup>
import { Link } from '@inertiajs/vue3';
import NavbarSearch from './NavbarSearch.vue';
import { computed } from 'vue';
import { usePropertyFilters } from '@/Composables/usePropertyFilters';

const { filters, runSearch } = usePropertyFilters();

const categoryLabel = computed(() => {
    let label = '';
    
    if (filters.type && filters.type.length > 0) {
        label += filters.type[0].charAt(0).toUpperCase() + filters.type[0].slice(1);
    } else {
        label += 'Properties';
    }

    if (filters.city) {
        label += ` in ${filters.city}`;
    } else {
        label += ' Everywhere';
    }

    return label;
});

const handleSearch = () => {
    runSearch();
};
</script>

<template>
    <nav class="sticky top-0 w-full z-[100] bg-navy h-16 flex items-center shadow-2xl border-b border-white/5">
        <div class="container max-w-full mx-auto px-4 flex items-center justify-between">
            <!-- Left Side: Logo & Category Selector -->
            <div class="flex items-center space-x-8">
                <Link href="/" class="flex items-center shrink-0 group">
                    <div class="w-10 h-10 bg-primary rounded-xl flex items-center justify-center mr-3 shadow-lg group-hover:scale-110 transition-transform">
                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-sm font-black text-white leading-tight tracking-tight uppercase">Ocean</span>
                        <span class="text-xs font-bold text-primary/80 leading-tight tracking-widest uppercase">Realtors</span>
                    </div>
                </Link>

                <div class="hidden xl:flex items-center bg-white/5 rounded-xl px-4 py-2 border border-white/10 cursor-pointer hover:bg-white/10 transition-all group">
                    <span class="text-xs font-bold text-white/90 mr-3 tracking-wide">{{ categoryLabel }}</span>
                    <svg class="h-3.5 w-3.5 text-white/40 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" /></svg>
                </div>
            </div>

            <!-- Center: Search Bar (Now simplified by using singleton) -->
            <NavbarSearch @search="handleSearch" />

            <!-- Right Side -->
            <div class="flex items-center space-x-6">
                <a href="tel:+1234567890" class="hidden md:flex items-center text-white/80 hover:text-white transition-colors">
                    <div class="w-8 h-8 rounded-lg bg-white/5 flex items-center justify-center mr-2.5">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" /></svg>
                    </div>
                    <span class="text-xs font-bold tracking-wider">+123 456 7890</span>
                </a>
            </div>
        </div>
    </nav>
</template>
