<script setup>
import { ref, defineAsyncComponent } from 'vue';
import AppLayout from '@/Components/AppLayout.vue';
import PageSeoHead from '@/Components/PageSeoHead.vue';
import ListingToolbar from '@/Components/Listing/ListingToolbar.vue';
import PropertyGrid from '@/Components/Listing/PropertyGrid.vue';
import FilterSidebar from '@/Components/Filters/FilterSidebar.vue';
import { usePropertyFilters } from '@/Composables/usePropertyFilters';

const MobileFilterSheet = defineAsyncComponent(() => import('@/Components/Filters/MobileFilterSheet.vue'));

defineProps({
    initialListings: { type: Array, default: () => [] },
    initialTotal: { type: Number, default: 0 },
    initialPage: { type: Number, default: 1 },
    initialLastPage: { type: Number, default: 1 },
    initialHasMore: { type: Boolean, default: false },
});

const {
    filters,
    results,
    total,
    isLoading,
    error,
    clearFilters,
    activeFilterCount,
    loadMore,
    hasMore,
    runSearch,
} = usePropertyFilters({ autoFetch: true });

const viewMode = ref('grid');
const isMobileFiltersOpen = ref(false);
</script>

<template>
    <AppLayout title="Search Properties" use-secondary-navbar>
        <PageSeoHead
            title="Search Properties"
            description="Browse verified apartments, villas, and commercial properties for rent and sale across Gurgaon, Delhi, and NCR."
            path="/properties"
        />
        <div class="bg-surface min-h-screen">
            <div class="container-page max-w-6xl py-4 pb-28 md:py-8 md:pb-8">
                <div class="flex flex-col lg:flex-row stack-gap">
                    <!-- Desktop Sidebar -->
                    <aside class="hidden lg:block w-64 shrink-0">
                        <div class="sticky top-20 h-[calc(100vh-100px)] overflow-y-auto pr-2">
                            <div class="flex items-center justify-between mb-4">
                                <h2 class="text-base font-semibold text-text-primary">Filters</h2>
                                <button v-if="activeFilterCount > 0" @click="clearFilters" class="text-xs text-primary font-medium hover:underline">Clear all</button>
                            </div>
                            <FilterSidebar 
                                :filters="filters" 
                                :activeFilterCount="activeFilterCount"
                                @clear="clearFilters"
                            />
                        </div>
                    </aside>

                    <!-- Main Content -->
                    <main class="flex-1">
                        <ListingToolbar 
                            v-model:sort="filters.sort"
                            :view="viewMode"
                            :total="total"
                        />

                        <p v-if="error" class="mb-3 text-sm text-red-600 bg-red-50 border border-red-100 rounded-lg px-3 py-2">
                            {{ error }}
                        </p>

                        <div class="mt-4">
                            <PropertyGrid 
                                :properties="results"
                                :isLoading="isLoading"
                                :view="viewMode"
                                :hasMore="hasMore"
                                @loadMore="loadMore"
                                @clearFilters="clearFilters"
                            />
                        </div>
                    </main>
                </div>
            </div>
        </div>

        <!-- Mobile Filter Trigger -->
        <div class="lg:hidden fixed bottom-6 left-1/2 -translate-x-1/2 z-50">
            <button 
                @click="isMobileFiltersOpen = true"
                class="bg-primary text-white px-6 py-3 rounded-full shadow-lg font-semibold flex items-center gap-2 active:scale-95 transition-all"
            >
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                </svg>
                <span class="text-sm">Filters</span>
                <span v-if="activeFilterCount > 0" class="flex items-center justify-center w-5 h-5 bg-white text-primary text-[10px] rounded-full">{{ activeFilterCount }}</span>
            </button>
        </div>

        <!-- Mobile Filter Sheet -->
        <MobileFilterSheet
            v-if="isMobileFiltersOpen"
            :filters="filters"
            :active-filter-count="activeFilterCount"
            :total="total"
            @close="isMobileFiltersOpen = false"
            @clear="clearFilters"
        />
    </AppLayout>
</template>
