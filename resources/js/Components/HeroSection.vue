<script setup>
import { ref, computed, onMounted } from 'vue';
import { usePropertyFilters } from '@/Composables/usePropertyFilters';
import { useLocationSuggest, applyLocationSuggestion } from '@/Composables/useLocationSuggest';
import LocationSuggestionList from '@/Components/Location/LocationSuggestionList.vue';

const { filters, runSearch } = usePropertyFilters();
const {
    suggestions,
    showSuggestions,
    isLoading,
    listedCities,
    fetchSuggestions,
    hideSuggestions,
    loadListedCities,
} = useLocationSuggest();

const categories = [
    { id: 'buy', label: 'BUY', status: 'sale' },
    { id: 'rent', label: 'RENT', status: 'rent' },
    { id: 'commercial', label: 'COMMERCIAL', type: ['office', 'commercial'] },
    { id: 'pg', label: 'PG/CO-LIVING', type: ['pg'] },
    { id: 'plots', label: 'PLOTS', type: ['plot'] },
];

const activeCategory = ref(categories.find((c) => c.status === filters.status) || categories[0]);
const commercialListingType = ref(filters.status === 'rent' ? 'lease' : 'buy');
const showCityDropdown = ref(false);

const bgGradients = {
    buy: 'from-violet-950/85 via-violet-900/75 to-indigo-950/80',
    rent: 'from-blue-950/85 via-blue-900/75 to-slate-950/80',
    commercial: 'from-emerald-950/85 via-emerald-900/75 to-teal-950/80',
    pg: 'from-amber-950/85 via-orange-900/75 to-stone-950/80',
    plots: 'from-lime-950/85 via-green-900/75 to-emerald-950/80',
};

const activeGradient = computed(() => bgGradients[activeCategory.value.id] ?? bgGradients.buy);
const cityLabel = computed(() => filters.city || 'India');
const headline = computed(
    () => `Properties to ${activeCategory.value.id === 'rent' ? 'rent' : 'buy'} in ${cityLabel.value}`,
);

onMounted(() => {
    const load = () => loadListedCities();
    if (typeof requestIdleCallback === 'function') {
        requestIdleCallback(load, { timeout: 2000 });
    } else {
        setTimeout(load, 1);
    }
});

const onKeywordInput = () => fetchSuggestions(filters.keyword);

const selectSuggestion = (s) => {
    applyLocationSuggestion(s, filters);
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

const selectCity = (city) => {
    filters.city = typeof city === 'string' ? city : city.name;
    showCityDropdown.value = false;
};
</script>

<template>
    <section
        class="hero-section relative z-20 flex min-h-[430px] flex-col justify-start sm:min-h-[540px] sm:justify-center lg:min-h-[620px]"
        aria-label="Property search"
    >
        <div class="absolute inset-0 overflow-hidden" aria-hidden="true">
            <picture>
                <source media="(max-width: 768px)" type="image/webp" srcset="/images/hero/hero-mobile.webp" />
                <source media="(max-width: 768px)" type="image/jpeg" srcset="/images/hero/hero-mobile.jpg" />
                <source type="image/webp" srcset="/images/hero/hero-desktop.webp" />
                <img
                    src="/images/hero/hero-desktop.jpg"
                    alt=""
                    class="h-full w-full object-cover object-[center_35%] sm:object-center"
                    width="1628"
                    height="1080"
                    sizes="100vw"
                    fetchpriority="high"
                    decoding="async"
                />
            </picture>
        </div>
        <div class="absolute inset-0 bg-black/35" aria-hidden="true" />
        <div
            class="absolute inset-0 bg-gradient-to-br transition-all duration-700 ease-out"
            :class="activeGradient"
            aria-hidden="true"
        />
        <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-transparent to-black/20" aria-hidden="true" />

        <div class="container relative z-10 mx-auto max-w-6xl px-3 pb-2 pt-4 sm:px-4 sm:pb-8 sm:pt-12 lg:pb-12 lg:pt-16">
            <div class="mx-auto mb-3 max-w-3xl text-center sm:mb-6">
                <h1 class="text-xl font-bold leading-tight tracking-tight text-white drop-shadow-md sm:text-4xl md:text-5xl lg:text-[3.25rem]">
                    {{ headline }}
                </h1>
                <div class="mt-2 flex flex-wrap items-center justify-center gap-x-3 gap-y-1 text-xs font-medium text-white/90 sm:mt-3 sm:gap-x-4 sm:text-sm">
                    <span><strong class="text-white">8K+</strong> listings added daily</span>
                    <span class="hidden h-1 w-1 rounded-full bg-white/40 sm:inline-block" aria-hidden="true" />
                    <span><strong class="text-white">76K+</strong> total verified</span>
                </div>
            </div>

            <div class="relative z-30 mx-auto w-full max-w-4xl">
                <div class="overflow-x-auto rounded-t-2xl border-b border-white/10 bg-black/25 backdrop-blur-md no-scrollbar">
                    <div class="flex min-w-max sm:min-w-0">
                        <button
                            v-for="cat in categories"
                            :key="cat.id"
                            type="button"
                            class="relative whitespace-nowrap px-3 py-2.5 text-[10px] font-bold tracking-wider transition-all sm:px-8 sm:py-4 sm:text-xs"
                            :class="activeCategory.id === cat.id ? 'text-white' : 'text-white/60 hover:text-white/85'"
                            @click="activeCategory = cat"
                        >
                            {{ cat.label }}
                            <span
                                v-if="activeCategory.id === cat.id"
                                class="absolute bottom-0 left-1/2 h-1 w-8 -translate-x-1/2 rounded-full bg-white"
                            />
                        </button>
                    </div>
                </div>

                <div class="relative overflow-visible rounded-b-2xl bg-white p-1.5 shadow-2xl shadow-black/25 sm:p-2">
                    <div class="flex flex-col items-stretch gap-2 overflow-visible md:flex-row">
                        <div class="relative hidden min-w-[140px] border-r border-gray-100 md:block">
                            <button
                                type="button"
                                class="flex h-14 w-full items-center justify-between px-4 text-sm font-semibold text-text-primary transition-colors hover:bg-gray-50"
                                @click="showCityDropdown = !showCityDropdown"
                            >
                                <span class="truncate">{{ filters.city || 'Select City' }}</span>
                                <svg
                                    class="h-4 w-4 shrink-0 text-text-muted transition-transform"
                                    :class="{ 'rotate-180': showCityDropdown }"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                    aria-hidden="true"
                                >
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div
                                v-if="showCityDropdown"
                                class="absolute left-0 top-full z-50 mt-2 w-48 rounded-xl border border-gray-100 bg-white py-2 shadow-xl"
                            >
                                <button
                                    type="button"
                                    class="w-full px-4 py-2 text-left text-sm text-text-secondary transition-colors hover:bg-gray-50 hover:text-primary"
                                    @click="selectCity('')"
                                >
                                    All cities
                                </button>
                                <button
                                    v-for="c in listedCities"
                                    :key="c.name"
                                    type="button"
                                    class="w-full px-4 py-2 text-left text-sm text-text-secondary transition-colors hover:bg-gray-50 hover:text-primary"
                                    @click="selectCity(c)"
                                >
                                    {{ c.name }}
                                    <span v-if="c.count" class="text-text-muted"> ({{ c.count }})</span>
                                </button>
                            </div>
                        </div>

                        <div class="relative border-b border-gray-100 pb-2 md:hidden">
                            <label class="mb-1 block px-2 text-[10px] font-bold uppercase tracking-wide text-text-muted">City</label>
                            <select
                                v-model="filters.city"
                                class="w-full rounded-lg border-gray-200 bg-gray-50 px-3 py-2.5 text-sm font-semibold text-text-primary focus:border-primary focus:ring-primary"
                            >
                                <option value="">Select city</option>
                                <option v-for="c in listedCities" :key="c.name" :value="c.name">{{ c.name }}</option>
                            </select>
                        </div>

                        <div class="relative z-40 min-w-0 flex-1">
                            <div class="pointer-events-none absolute left-4 top-1/2 -translate-y-1/2" aria-hidden="true">
                                <svg class="h-5 w-5 text-text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                            <input
                                v-model="filters.keyword"
                                type="search"
                                placeholder="Type at least 3 characters — locality, project, landmark…"
                                class="h-14 w-full border-none pl-12 pr-4 text-base font-medium placeholder:text-text-muted/60 focus:ring-0"
                                autocomplete="off"
                                @input="onKeywordInput"
                                @focus="onKeywordInput"
                                @blur="hideSuggestions()"
                            />
                            <LocationSuggestionList
                                v-if="showSuggestions || (isLoading && filters.keyword.trim().length >= 3)"
                                :suggestions="suggestions"
                                :is-loading="isLoading"
                                :query="filters.keyword"
                                variant="default"
                                @select="selectSuggestion"
                            />
                        </div>

                        <div v-if="activeCategory.id === 'commercial'" class="hidden items-center space-x-6 border-l border-gray-100 px-4 lg:flex">
                            <label class="group flex cursor-pointer items-center">
                                <input v-model="commercialListingType" type="radio" value="buy" class="hidden" />
                                <span
                                    class="mr-2 flex h-5 w-5 items-center justify-center rounded-full border-2 transition-colors"
                                    :class="commercialListingType === 'buy' ? 'border-primary' : 'border-gray-200 group-hover:border-gray-300'"
                                >
                                    <span v-if="commercialListingType === 'buy'" class="h-2.5 w-2.5 rounded-full bg-primary" />
                                </span>
                                <span class="text-sm font-semibold" :class="commercialListingType === 'buy' ? 'text-primary' : 'text-text-secondary'">Buy</span>
                            </label>
                            <label class="group flex cursor-pointer items-center">
                                <input v-model="commercialListingType" type="radio" value="lease" class="hidden" />
                                <span
                                    class="mr-2 flex h-5 w-5 items-center justify-center rounded-full border-2 transition-colors"
                                    :class="commercialListingType === 'lease' ? 'border-primary' : 'border-gray-200 group-hover:border-gray-300'"
                                >
                                    <span v-if="commercialListingType === 'lease'" class="h-2.5 w-2.5 rounded-full bg-primary" />
                                </span>
                                <span class="text-sm font-semibold" :class="commercialListingType === 'lease' ? 'text-primary' : 'text-text-secondary'">Lease</span>
                            </label>
                        </div>

                        <div class="p-1">
                            <button
                                type="button"
                                class="flex h-12 w-full items-center justify-center rounded-xl bg-primary text-base font-bold text-white shadow-lg shadow-primary/25 transition-all hover:bg-primary-hover active:scale-[0.98] md:h-full md:w-32"
                                @click="handleSearch"
                            >
                                Search
                            </button>
                        </div>
                    </div>
                </div>
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
