import { ref } from 'vue';
import axios from 'axios';
import { FALLBACK_CITIES } from '@/config/locationFallbackCities.js';

export const MIN_LOCATION_QUERY_LENGTH = 3;

let citiesCache = null;
let citiesPromise = null;

/**
 * Fetch cities that have at least one property listing.
 */
export async function fetchListedCities() {
    if (citiesCache) {
        return citiesCache;
    }

    if (!citiesPromise) {
        citiesPromise = axios
            .get('/api/locations/cities')
            .then((res) => {
                const list = Array.isArray(res.data) ? res.data : [];
                citiesCache = list.length > 0 ? list : [...FALLBACK_CITIES];
                return citiesCache;
            })
            .catch(() => {
                citiesCache = [...FALLBACK_CITIES];
                return citiesCache;
            })
            .finally(() => {
                citiesPromise = null;
            });
    }

    return citiesPromise;
}

/**
 * Apply a location suggestion to property filters.
 */
export function applyLocationSuggestion(suggestion, filters) {
    if (!suggestion) return;

    if (suggestion.type === 'CITY') {
        filters.city = suggestion.city || suggestion.name;
        filters.keyword = '';
        return;
    }

    filters.city = suggestion.city || filters.city;
    filters.keyword = suggestion.name;
}

export function useLocationSuggest() {
    const suggestions = ref([]);
    const showSuggestions = ref(false);
    const isLoading = ref(false);
    const listedCities = ref([]);

    let debounceTimeout = null;

    const fetchSuggestions = (query) => {
        if (debounceTimeout) clearTimeout(debounceTimeout);

        const trimmed = (query ?? '').trim();

        if (trimmed.length < MIN_LOCATION_QUERY_LENGTH) {
            suggestions.value = [];
            showSuggestions.value = false;
            isLoading.value = false;
            return;
        }

        debounceTimeout = setTimeout(async () => {
            isLoading.value = true;
            try {
                const response = await axios.get('/api/locations', { params: { q: trimmed } });
                suggestions.value = response.data ?? [];
                showSuggestions.value = suggestions.value.length > 0;
            } catch (error) {
                console.error('Error fetching location suggestions:', error);
                suggestions.value = [];
                showSuggestions.value = false;
            } finally {
                isLoading.value = false;
            }
        }, 400);
    };

    const hideSuggestions = (delayMs = 200) => {
        setTimeout(() => {
            showSuggestions.value = false;
        }, delayMs);
    };

    const loadListedCities = async () => {
        listedCities.value = await fetchListedCities();
    };

    return {
        suggestions,
        showSuggestions,
        isLoading,
        listedCities,
        fetchSuggestions,
        hideSuggestions,
        loadListedCities,
        applyLocationSuggestion,
    };
}
