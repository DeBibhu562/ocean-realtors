<script setup>
import { ref, watch, onBeforeUnmount, nextTick } from 'vue';
import { setOptions, importLibrary } from '@googlemaps/js-api-loader';
import axios from 'axios';

const props = defineProps({
    latitude: { type: [String, Number], default: '' },
    longitude: { type: [String, Number], default: '' },
    city: { type: String, default: '' },
    /** Mount map only when step is visible (avoids zero-size init inside v-show) */
    active: { type: Boolean, default: true },
});

const emit = defineEmits(['geocoded']);

const mapEl = ref(null);
const searchInput = ref(null);
const searchQuery = ref('');
const geocodeError = ref('');
const locating = ref(false);
const mapReady = ref(false);
const mapLoading = ref(false);

const DEFAULT_LAT = 28.4595;
const DEFAULT_LNG = 77.0266;

const apiKey = import.meta.env.VITE_GOOGLE_MAPS_API_KEY;

/** @type {google.maps.Map | null} */
let map = null;
/** @type {google.maps.Marker | null} */
let marker = null;
/** @type {google.maps.places.Autocomplete | null} */
let autocomplete = null;
let initStarted = false;
let loaderConfigured = false;

function parseCoord(value, fallback) {
    const n = Number.parseFloat(String(value ?? '').trim());
    return Number.isFinite(n) ? n : fallback;
}

function currentCenter() {
    return {
        lat: parseCoord(props.latitude, DEFAULT_LAT),
        lng: parseCoord(props.longitude, DEFAULT_LNG),
    };
}

function hasSavedCoords() {
    return (
        String(props.latitude ?? '').trim() !== '' && String(props.longitude ?? '').trim() !== ''
    );
}

function configureLoader() {
    if (loaderConfigured || !apiKey) {
        return;
    }
    setOptions({ key: apiKey, v: 'weekly' });
    loaderConfigured = true;
}

function setMarker(lat, lng, pan = true) {
    if (!marker || !map) return;
    const pos = { lat, lng };
    marker.setPosition(pos);
    if (pan) {
        map.panTo(pos);
        map.setZoom(Math.max(map.getZoom() ?? 15, 15));
    }
}

async function reverseGeocode(lat, lng) {
    geocodeError.value = '';
    try {
        const { data } = await axios.post(route('dashboard.geocode.reverse'), { lat, lng });
        emit('geocoded', data);
    } catch (e) {
        geocodeError.value = e.response?.data?.message || 'Could not resolve address for this pin.';
    }
}

function onPlaceSelected() {
    if (!autocomplete) return;

    const place = autocomplete.getPlace();
    if (!place?.geometry?.location) {
        geocodeError.value = 'Choose a suggestion from the dropdown list.';
        return;
    }

    geocodeError.value = '';
    const lat = place.geometry.location.lat();
    const lng = place.geometry.location.lng();
    searchQuery.value = place.formatted_address || place.name || searchQuery.value;
    setMarker(lat, lng);
    reverseGeocode(lat, lng);
}

function resizeMap() {
    if (!map || !mapEl.value) return;
    const center = marker?.getPosition() || map.getCenter();
    google.maps.event.trigger(map, 'resize');
    if (center) {
        map.setCenter(center);
    }
}

async function initGoogleMap() {
    if (!props.active || initStarted || mapReady.value) return;
    if (!apiKey) {
        geocodeError.value =
            'Add VITE_GOOGLE_MAPS_API_KEY to .env, then run npm run build. Enable Maps JavaScript API + Places API.';
        return;
    }

    await nextTick();
    if (!mapEl.value || !searchInput.value) return;

    initStarted = true;
    mapLoading.value = true;
    geocodeError.value = '';

    window.gm_authFailure = () => {
        geocodeError.value =
            'Google Maps blocked this site. In Google Cloud → Credentials → your key → Application restrictions, add HTTP referrer: https://oceanrealtors.co.in/* and https://www.oceanrealtors.co.in/*';
        mapLoading.value = false;
    };

    try {
        configureLoader();
        await importLibrary('maps');
        await importLibrary('places');

        const { lat, lng } = currentCenter();

        map = new google.maps.Map(mapEl.value, {
            center: { lat, lng },
            zoom: 14,
            mapTypeControl: false,
            streetViewControl: false,
            fullscreenControl: true,
        });

        marker = new google.maps.Marker({
            map,
            position: { lat, lng },
            draggable: true,
        });

        marker.addListener('dragend', () => {
            const pos = marker.getPosition();
            if (!pos) return;
            reverseGeocode(pos.lat(), pos.lng());
        });

        autocomplete = new google.maps.places.Autocomplete(searchInput.value, {
            componentRestrictions: { country: 'in' },
            fields: ['geometry', 'formatted_address', 'address_components', 'name', 'place_id'],
        });
        autocomplete.addListener('place_changed', onPlaceSelected);

        mapReady.value = true;
        await nextTick();
        resizeMap();

        if (!hasSavedCoords()) {
            await reverseGeocode(lat, lng);
        }
    } catch (err) {
        console.error('Google Maps init failed', err);
        geocodeError.value =
            err?.message || 'Failed to load Google Maps. Check API key, billing, and referrer restrictions.';
        initStarted = false;
    } finally {
        mapLoading.value = false;
    }
}

async function useMyLocation() {
    if (!navigator.geolocation) {
        geocodeError.value = 'Geolocation is not supported in this browser.';
        return;
    }

    if (!mapReady.value) {
        await initGoogleMap();
    }

    locating.value = true;
    geocodeError.value = '';

    navigator.geolocation.getCurrentPosition(
        async (pos) => {
            try {
                const lat = pos.coords.latitude;
                const lng = pos.coords.longitude;
                setMarker(lat, lng);
                if (map) {
                    map.setCenter({ lat, lng });
                    map.setZoom(16);
                }
                await reverseGeocode(lat, lng);
            } finally {
                locating.value = false;
            }
        },
        () => {
            geocodeError.value = 'Location permission denied or unavailable.';
            locating.value = false;
        },
        { enableHighAccuracy: true, timeout: 15000, maximumAge: 0 },
    );
}

watch(
    () => props.active,
    async (isActive) => {
        if (isActive) {
            await initGoogleMap();
        }
    },
    { immediate: true },
);

watch(
    () => [props.latitude, props.longitude],
    () => {
        if (!marker || !mapReady.value) return;
        const { lat, lng } = currentCenter();
        setMarker(lat, lng, false);
    },
);

onBeforeUnmount(() => {
    if (marker) {
        marker.setMap(null);
        marker = null;
    }
    map = null;
    autocomplete = null;
    initStarted = false;
    mapReady.value = false;
    if (window.gm_authFailure) {
        delete window.gm_authFailure;
    }
});
</script>

<template>
    <div class="space-y-4 min-w-0">
        <div class="min-w-0">
            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                Search address (Google)
            </label>
            <input
                ref="searchInput"
                v-model="searchQuery"
                type="text"
                class="box-border w-full min-w-0 max-w-full rounded-lg border-gray-300 px-3 py-2.5 text-sm shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 dark:border-gray-600 dark:bg-gray-900"
                placeholder="Start typing — pick from Google suggestions"
                autocomplete="off"
            />
            <p class="mt-1.5 text-xs text-gray-400">Pick a suggestion to auto-fill address fields</p>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            <button
                type="button"
                class="inline-flex min-h-[2.5rem] items-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 shadow-sm transition hover:bg-gray-50 disabled:opacity-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200"
                :disabled="locating || mapLoading"
                @click="useMyLocation"
            >
                {{ locating ? 'Locating…' : 'Use my location' }}
            </button>
            <span class="text-xs text-gray-400">Drag the pin to refine</span>
        </div>

        <div class="relative min-w-0">
            <div
                ref="mapEl"
                class="h-56 w-full min-w-0 overflow-hidden rounded-xl border border-gray-200 bg-slate-100 sm:h-64 dark:border-gray-700"
            />
            <p
                v-if="mapLoading"
                class="absolute inset-0 flex items-center justify-center rounded-xl bg-white/80 text-sm text-gray-500"
            >
                Loading Google Map…
            </p>
        </div>

        <p v-if="geocodeError" class="rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-sm text-red-700">{{ geocodeError }}</p>
    </div>
</template>

<style scoped>
:deep(.pac-container) {
    z-index: 10000;
    font-family: inherit;
    border-radius: 0.375rem;
    margin-top: 2px;
    box-shadow: 0 4px 12px rgb(0 0 0 / 0.12);
}
</style>
