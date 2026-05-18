<script setup>
import { ref } from 'vue';
import AppLayout from '@/Components/AppLayout.vue';
import FilterSidebar from '@/Components/Filters/FilterSidebar.vue';
import ListingToolbar from '@/Components/Listing/ListingToolbar.vue';
import PropertyGrid from '@/Components/Listing/PropertyGrid.vue';
import { usePropertyFilters } from '@/Composables/usePropertyFilters';

const {
    filters,
    results,
    total,
    isLoading,
    clearFilters,
    activeFilterCount,
    loadMore,
    hasMore,
    runSearch,
} = usePropertyFilters();

const viewMode = ref('grid');
const isMobileFiltersOpen = ref(false);
</script>

<template>
    <AppLayout title="Properties" useSecondaryNavbar :filters="filters" @search="runSearch">
        <div class="bg-surface min-h-screen">
            <div class="container max-w-6xl mx-auto px-4 py-8">
                <div class="flex flex-col lg:flex-row gap-8">
                    <!-- Desktop Sidebar -->
                    <aside class="hidden lg:block w-64 shrink-0">
                        <div class="sticky top-20 h-[calc(100vh-100px)] overflow-y-auto pr-2">
                            <div class="flex items-center justify-between mb-4">
                                <h2 class="text-base font-semibold text-text-primary">Filters</h2>
                                <button @click="clearFilters" class="text-xs text-primary font-medium hover:underline">Clear all</button>
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
                            v-model:view="viewMode" 
                            v-model:sort="filters.sort"
                            :total="total"
                        />

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
    </AppLayout>
</template>
