<script setup>
import { onMounted, ref, watch } from 'vue';
import { usePropertyFilters } from '@/Composables/usePropertyFilters';
import { useLocationSuggest, applyLocationSuggestion } from '@/Composables/useLocationSuggest';
import LocationSuggestionList from '@/Components/Location/LocationSuggestionList.vue';

const emit = defineEmits(['search']);

const { filters, setKeywordFieldFocused } = usePropertyFilters();
const {
    suggestions,
    showSuggestions,
    isLoading,
    listedCities,
    fetchSuggestions,
    hideSuggestions,
    loadListedCities,
} = useLocationSuggest();

/** Local draft — avoids URL round-trip resetting the field while typing on /properties */
const keywordInput = ref('');

onMounted(() => {
    loadListedCities();
    keywordInput.value = filters.keyword ?? '';
});

watch(
    () => filters.keyword,
    (value) => {
        if (document.activeElement?.dataset?.navbarKeyword !== 'true') {
            keywordInput.value = value ?? '';
        }
    },
);

const onKeywordInput = () => {
    fetchSuggestions(keywordInput.value);
};

const onKeywordFocus = () => {
    setKeywordFieldFocused(true);
    fetchSuggestions(keywordInput.value);
};

const onKeywordBlur = () => {
    setKeywordFieldFocused(false);
    hideSuggestions();
};

const commitSearch = () => {
    filters.keyword = keywordInput.value.trim();
    emit('search');
};

const selectSuggestion = (s) => {
    applyLocationSuggestion(s, filters);
    keywordInput.value = filters.keyword ?? '';
    showSuggestions.value = false;
    commitSearch();
};
</script>

<template>
    <div class="relative mx-1 flex h-10 min-w-0 max-w-full flex-1 items-center rounded-xl border border-white/10 bg-white shadow-sm transition-all group focus-within:ring-2 focus-within:ring-primary/20 sm:mx-2 md:mx-3 md:h-11 lg:max-w-xl xl:max-w-2xl">
        <div class="relative hidden h-full shrink-0 sm:block">
            <select
                v-model="filters.city"
                class="h-full max-w-[7.5rem] cursor-pointer truncate rounded-l-xl border-r border-gray-100 bg-gray-50/50 pl-3 pr-7 text-[10px] font-black uppercase tracking-wide text-navy transition-colors hover:bg-gray-100 focus:border-gray-100 focus:ring-0 md:max-w-[8.5rem] md:pl-4 md:text-[11px] md:tracking-widest"
            >
                <option value="">Everywhere</option>
                <option v-for="c in listedCities" :key="c.name" :value="c.name">
                    {{ c.name }} ({{ c.count }})
                </option>
            </select>
        </div>

        <div class="relative h-full min-w-0 flex-1">
            <input
                v-model="keywordInput"
                type="text"
                data-navbar-keyword="true"
                placeholder="Search locality or project…"
                class="h-full w-full min-w-0 truncate border-none pl-3 pr-2 text-[12px] font-bold text-navy transition-all placeholder:text-text-muted/60 focus:ring-0 md:pl-4 md:text-[13px]"
                autocomplete="off"
                @input="onKeywordInput"
                @focus="onKeywordFocus"
                @blur="onKeywordBlur"
                @keyup.enter="commitSearch"
            />

            <LocationSuggestionList
                v-if="showSuggestions || (isLoading && keywordInput.trim().length >= 3)"
                :suggestions="suggestions"
                :is-loading="isLoading"
                :query="keywordInput"
                variant="compact"
                @select="selectSuggestion"
            />
        </div>

        <button
            type="button"
            class="group flex h-full shrink-0 items-center rounded-r-xl bg-primary px-3.5 text-white shadow-inner transition-all hover:bg-primary-hover sm:px-4 md:px-5"
            @click="commitSearch"
        >
            <svg class="h-5 w-5 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
        </button>
    </div>
</template>
