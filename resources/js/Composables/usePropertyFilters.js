import { reactive, ref, watch, computed, onMounted, onUnmounted } from 'vue';
import axios from 'axios';
import { router, usePage } from '@inertiajs/vue3';

const DEFAULT_MAX_PRICE = 10000000;
const FILTER_KEYS = [
    'keyword', 'city', 'status', 'type', 'min_price', 'max_price', 
    'beds', 'baths', 'min_area', 'max_area', 'amenities', 'sort', 'featured'
];

// SHARED SINGLETON STATE
const filters = reactive({
    keyword: '',
    city: '',
    status: 'all',
    type: [],
    min_price: 0,
    max_price: DEFAULT_MAX_PRICE,
    beds: null,
    baths: null,
    min_area: null,
    max_area: null,
    amenities: [],
    sort: 'newest',
    page: 1,
    featured: false,
});

const results = ref([]);
const total = ref(0);
const isLoading = ref(false);
const error = ref(null);
const hasMore = ref(true);

// Control flags
let isUpdatingFromUrl = false;
let isInitialized = false; // NEW: Prevent clobbering during re-mounts
let abortController = null;
let debounceTimeout = null;

// HELPER: Build Clean Query Params
function buildQueryParams(f, { includePage = true } = {}) {
    const params = {};
    if (f.keyword?.trim()) params.keyword = f.keyword.trim();
    if (f.city?.trim()) params.city = f.city.trim();
    if (f.status && f.status !== 'all') params.status = f.status;
    if (f.type?.length > 0) params.type = f.type;
    if (f.min_price > 0) params.min_price = f.min_price;
    if (f.max_price > 0 && f.max_price < DEFAULT_MAX_PRICE) params.max_price = f.max_price;
    if (f.beds !== null) params.beds = f.beds;
    if (f.baths !== null) params.baths = f.baths;
    if (f.min_area) params.min_area = f.min_area;
    if (f.max_area) params.max_area = f.max_area;
    if (f.amenities?.length > 0) params.amenities = f.amenities;
    if (f.sort && f.sort !== 'newest') params.sort = f.sort;
    if (f.featured) params.featured = 1;
    if (includePage && f.page > 1) params.page = f.page;
    return params;
}

// HELPER: Normalize Array Inputs from URL
function parseArrayParam(params, key) {
    const fromGetAll = params.getAll(`${key}[]`).length ? params.getAll(`${key}[]`) : params.getAll(key);
    if (fromGetAll.length > 0) {
        return fromGetAll.flatMap(entry => String(entry).split(',')).map(t => t.trim()).filter(Boolean);
    }
    const single = params.get(key);
    if (!single) return [];
    return String(single).split(',').map(t => t.trim()).filter(Boolean);
}

// CORE: Fetch Properties from API
const fetchProperties = async (append = false) => {
    if (abortController) abortController.abort();
    abortController = new AbortController();

    isLoading.value = true;
    error.value = null;

    const params = buildQueryParams(filters);

    try {
        const response = await axios.get('/api/properties', {
            params,
            signal: abortController.signal,
            paramsSerializer: { indexes: null },
        });

        const payload = response.data;
        if (append) {
            results.value = [...results.value, ...(payload.data || [])];
        } else {
            results.value = payload.data || [];
        }

        total.value = payload.total ?? 0;
        hasMore.value = (payload.current_page ?? 1) < (payload.last_page ?? 1);
        
        // Sync page back from server if needed
        isUpdatingFromUrl = true;
        filters.page = payload.current_page ?? filters.page;
        setTimeout(() => { isUpdatingFromUrl = false; }, 100);

    } catch (err) {
        if (axios.isCancel(err)) return;
        error.value = 'Failed to fetch properties.';
        console.error('[Filters] Fetch error:', err);
    } finally {
        isLoading.value = false;
    }
};

// CORE: Sync current filters to URL
const syncUrl = () => {
    if (isUpdatingFromUrl) return;

    const query = buildQueryParams(filters);
    const searchParams = new URLSearchParams();

    Object.entries(query).forEach(([key, val]) => {
        if (Array.isArray(val)) {
            val.forEach(item => searchParams.append(`${key}[]`, item));
        } else {
            searchParams.set(key, String(val));
        }
    });

    const isPropertiesPage = window.location.pathname.startsWith('/properties');
    if (!isPropertiesPage) {
        router.get('/properties', query, { preserveState: true });
        return;
    }

    const newSearch = searchParams.toString();
    const currentSearch = window.location.search.replace(/^\?/, '');
    
    if (newSearch !== currentSearch) {
        isUpdatingFromUrl = true;
        router.replace('/properties?' + newSearch, {
            preserveScroll: true,
            preserveState: true,
            onFinish: () => { 
                setTimeout(() => { isUpdatingFromUrl = false; }, 150);
            },
        });
    }
};

// CORE: Parse URL to state
const parseUrlParams = (force = false) => {
    // Prevent clobbering state if already initialized, unless forced (Back button)
    if (isInitialized && !force) return;

    const params = new URLSearchParams(window.location.search);
    isUpdatingFromUrl = true;

    filters.keyword = params.get('keyword') || '';
    filters.city = params.get('city') || '';
    filters.status = params.get('status') || 'all';
    filters.sort = params.get('sort') || 'newest';
    filters.page = Number(params.get('page')) || 1;
    filters.featured = params.get('featured') === '1' || params.get('featured') === 'true';
    filters.min_price = params.has('min_price') ? Number(params.get('min_price')) : 0;
    filters.max_price = params.has('max_price') ? Number(params.get('max_price')) : DEFAULT_MAX_PRICE;
    filters.beds = params.get('beds') ? Number(params.get('beds')) : null;
    filters.baths = params.get('baths') ? Number(params.get('baths')) : null;
    filters.min_area = params.get('min_area') ? Number(params.get('min_area')) : null;
    filters.max_area = params.get('max_area') ? Number(params.get('max_area')) : null;
    filters.type = parseArrayParam(params, 'type');
    filters.amenities = parseArrayParam(params, 'amenities');

    isInitialized = true;
    setTimeout(() => { isUpdatingFromUrl = false; }, 150);
};

export function usePropertyFilters(options = { autoFetch: false }) {
    const page = usePage();
    let localWatcher = null;

    const activeFilterCount = computed(() => {
        let count = 0;
        if (filters.keyword?.trim()) count++;
        if (filters.city) count++;
        if (filters.status !== 'all') count++;
        if (filters.type.length > 0) count++;
        if (filters.min_price > 0 || (filters.max_price > 0 && filters.max_price < DEFAULT_MAX_PRICE)) count++;
        if (filters.beds !== null) count++;
        if (filters.baths !== null) count++;
        if (filters.min_area || filters.max_area) count++;
        if (filters.amenities.length > 0) count++;
        if (filters.featured) count++;
        return count;
    });

    const runSearch = () => {
        filters.page = 1;
        syncUrl();
        fetchProperties(false);
    };

    const clearFilters = () => {
        isUpdatingFromUrl = true;
        Object.assign(filters, {
            keyword: '', city: '', status: 'all', type: [], 
            min_price: 0, max_price: DEFAULT_MAX_PRICE,
            beds: null, baths: null, min_area: null, max_area: null,
            amenities: [], sort: 'newest', page: 1, featured: false
        });
        syncUrl();
        fetchProperties(false);
        setTimeout(() => { isUpdatingFromUrl = false; }, 150);
    };

    const loadMore = () => {
        if (isLoading.value || !hasMore.value) return;
        filters.page += 1;
        syncUrl();
        fetchProperties(true);
    };

    // Watcher logic - only active if autoFetch is requested (usually Index.vue)
    if (options.autoFetch) {
        localWatcher = watch(
            () => ({ ...filters }), // Watch a clone to detect changes accurately
            (newF, oldF) => {
                if (isUpdatingFromUrl) return;

                // Check if any filter besides page changed
                const filtersChanged = FILTER_KEYS.filter(k => k !== 'page').some(k => {
                    if (Array.isArray(newF[k])) return JSON.stringify(newF[k]) !== JSON.stringify(oldF[k]);
                    return newF[k] !== oldF[k];
                });

                if (filtersChanged) filters.page = 1;

                if (debounceTimeout) clearTimeout(debounceTimeout);
                debounceTimeout = setTimeout(() => {
                    syncUrl();
                    fetchProperties(false);
                }, 400);
            },
            { deep: true }
        );

        // Also watch page URL for back/forward navigation
        watch(() => page.url, (newUrl, oldUrl) => {
            if (newUrl !== oldUrl && !isUpdatingFromUrl) {
                parseUrlParams(true); // Force update from URL on external change
                fetchProperties(false);
            }
        });
    }

    onMounted(() => {
        const isCurrentlyPropertiesPage = window.location.pathname.startsWith('/properties');
        if (isCurrentlyPropertiesPage || options.autoFetch) {
            parseUrlParams(); // Only clobbers if !isInitialized
            if (options.autoFetch && results.value.length === 0) fetchProperties(false);
        }
    });

    onUnmounted(() => {
        if (localWatcher) localWatcher();
    });

    return {
        filters,
        results,
        total,
        isLoading,
        error,
        fetchProperties,
        runSearch,
        clearFilters,
        activeFilterCount,
        loadMore,
        hasMore,
    };
}
