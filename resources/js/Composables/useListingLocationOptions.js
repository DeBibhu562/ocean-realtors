import { ref, watch } from 'vue';
import axios from 'axios';
import { fetchListedCities } from '@/Composables/useLocationSuggest.js';

export const LOCATION_OTHER = '__other__';

/** Normalize geocoder / Google city names to match dropdown labels */
const CANONICAL_CITY = {
    gurugram: 'Gurgaon',
    gurgaon: 'Gurgaon',
    delhi: 'New Delhi',
    'new delhi': 'New Delhi',
    bangalore: 'Bengaluru',
    bombay: 'Mumbai',
};

function canonicalCity(name) {
    if (!name) return '';
    const key = String(name).trim().toLowerCase();
    return CANONICAL_CITY[key] ?? String(name).trim();
}

function namesMatch(a, b) {
    if (!a || !b) return false;
    return canonicalCity(a).toLowerCase() === canonicalCity(b).toLowerCase();
}

export function useListingLocationOptions(form) {
    const cityOptions = ref([]);
    const areaOptions = ref([]);
    const localityOptions = ref([]);
    const loadingAreas = ref(false);
    const loadingLocalities = ref(false);

    const citySelect = ref('');
    const areaSelect = ref('');
    const localitySelect = ref('');
    let syncingLocation = false;

    const loadCities = async () => {
        cityOptions.value = await fetchListedCities();
        if (form.city) {
            syncSelectsFromForm();
        }
    };

    const loadAreas = async (city) => {
        const resolved = canonicalCity(city);
        if (!resolved || city === LOCATION_OTHER) {
            areaOptions.value = [];
            return;
        }
        loadingAreas.value = true;
        try {
            const { data } = await axios.get('/api/locations/areas', { params: { city: resolved } });
            areaOptions.value = data ?? [];
        } catch {
            areaOptions.value = [];
        } finally {
            loadingAreas.value = false;
        }
    };

    const loadLocalities = async (city, area) => {
        const resolved = canonicalCity(city);
        if (!resolved || city === LOCATION_OTHER) {
            localityOptions.value = [];
            return;
        }
        loadingLocalities.value = true;
        try {
            const params = { city: resolved };
            if (area && area !== LOCATION_OTHER) {
                params.area = area;
            }
            const { data } = await axios.get('/api/locations/localities', { params });
            localityOptions.value = data ?? [];
        } catch {
            localityOptions.value = [];
        } finally {
            loadingLocalities.value = false;
        }
    };

    const findOption = (options, value) =>
        options.find((o) => namesMatch(o.name, value) || o.name.toLowerCase() === String(value).toLowerCase());

    const syncSelectsFromForm = () => {
        if (form.city) {
            const cityMatch = findOption(cityOptions.value, form.city);
            citySelect.value = cityMatch ? cityMatch.name : LOCATION_OTHER;
        } else {
            citySelect.value = '';
        }

        if (form.area) {
            const areaMatch = findOption(areaOptions.value, form.area);
            areaSelect.value = areaMatch ? areaMatch.name : LOCATION_OTHER;
        } else {
            areaSelect.value = '';
        }

        if (form.locality) {
            const locMatch = findOption(localityOptions.value, form.locality);
            localitySelect.value = locMatch ? locMatch.name : LOCATION_OTHER;
        } else {
            localitySelect.value = '';
        }
    };

    watch(citySelect, async (val) => {
        if (syncingLocation) return;
        if (val === LOCATION_OTHER) {
            areaSelect.value = '';
            localitySelect.value = '';
            areaOptions.value = [];
            localityOptions.value = [];
            return;
        }
        if (val) {
            form.city = val;
            form.area = '';
            form.locality = '';
            areaSelect.value = '';
            localitySelect.value = '';
            await loadAreas(val);
            await loadLocalities(val, null);
        }
    });

    watch(areaSelect, async (val) => {
        if (syncingLocation) return;
        if (val === LOCATION_OTHER) {
            localitySelect.value = '';
            localityOptions.value = [];
            return;
        }
        if (val && citySelect.value && citySelect.value !== LOCATION_OTHER) {
            form.area = val;
            form.locality = '';
            localitySelect.value = '';
            await loadLocalities(citySelect.value, val);
        }
    });

    watch(localitySelect, (val) => {
        if (syncingLocation) return;
        if (val === LOCATION_OTHER) {
            return;
        }
        if (val) {
            form.locality = val;
        }
    });

    const applyGeocodeResult = async (result) => {
        if (!result) return;

        syncingLocation = true;

        if (result.lat != null) form.latitude = String(result.lat);
        if (result.lng != null) form.longitude = String(result.lng);

        const formatted = result.formatted_address || '';
        const line = result.address_line || formatted;
        if (line) {
            form.address = line;
        }

        if (result.society_name) {
            form.society_name = result.society_name;
        }
        if (result.landmark) {
            form.landmark = result.landmark;
        }

        const city = canonicalCity(result.city);
        const area = result.area ? String(result.area).trim() : '';
        const locality = result.locality ? String(result.locality).trim() : '';

        if (city) {
            form.city = city;
            await loadCities();
            const cityMatch = findOption(cityOptions.value, city);
            citySelect.value = cityMatch ? cityMatch.name : LOCATION_OTHER;
            if (cityMatch) {
                form.city = cityMatch.name;
            }
        }

        if (area) {
            form.area = area;
            await loadAreas(form.city);
            const areaMatch = findOption(areaOptions.value, area);
            areaSelect.value = areaMatch ? areaMatch.name : LOCATION_OTHER;
            if (areaMatch) {
                form.area = areaMatch.name;
            }
        }

        if (locality) {
            form.locality = locality;
            await loadLocalities(form.city, form.area || null);
            const locMatch = findOption(localityOptions.value, locality);
            localitySelect.value = locMatch ? locMatch.name : LOCATION_OTHER;
            if (locMatch) {
                form.locality = locMatch.name;
            }
        }

        if (!form.address && formatted) {
            form.address = formatted;
        }

        syncingLocation = false;
    };

    const initLocationStep = async () => {
        syncingLocation = true;
        await loadCities();
        if (form.city) {
            const resolved = canonicalCity(form.city);
            form.city = resolved || form.city;
            const cityMatch = findOption(cityOptions.value, form.city);
            citySelect.value = cityMatch ? cityMatch.name : LOCATION_OTHER;
            await loadAreas(form.city);
            if (form.area) {
                const areaMatch = findOption(areaOptions.value, form.area);
                areaSelect.value = areaMatch ? areaMatch.name : LOCATION_OTHER;
            }
            await loadLocalities(form.city, form.area || null);
            if (form.locality) {
                const locMatch = findOption(localityOptions.value, form.locality);
                localitySelect.value = locMatch ? locMatch.name : LOCATION_OTHER;
            }
        }
        syncingLocation = false;
    };

    return {
        cityOptions,
        areaOptions,
        localityOptions,
        loadingAreas,
        loadingLocalities,
        citySelect,
        areaSelect,
        localitySelect,
        LOCATION_OTHER,
        loadCities,
        initLocationStep,
        applyGeocodeResult,
    };
}
