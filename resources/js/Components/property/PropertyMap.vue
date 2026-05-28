<script setup>
import { ref, onMounted, onBeforeUnmount, shallowRef, watch, nextTick } from 'vue';

const props = defineProps({
    latitude: { type: Number, required: true },
    longitude: { type: Number, required: true },
    title: { type: String, default: '' },
});

const root = ref(null);
const visible = ref(false);
const mapEl = ref(null);
const L = shallowRef(null);
const map = shallowRef(null);
const layers = shallowRef({});

const toggles = ref({
    schools: false,
    hospitals: false,
    transit: false,
    shopping: false,
});

const poiButtons = [
    { key: 'schools', label: 'Schools' },
    { key: 'hospitals', label: 'Hospitals' },
    { key: 'transit', label: 'Transit' },
    { key: 'shopping', label: 'Shopping' },
];

let observer;

const offsetMarkers = (baseLat, baseLng, count, spread = 0.012) => {
    const out = [];
    for (let i = 0; i < count; i++) {
        const angle = (i / count) * Math.PI * 2;
        const r = spread * (0.4 + (i % 3) * 0.2);
        out.push({
            lat: baseLat + Math.sin(angle) * r,
            lng: baseLng + Math.cos(angle) * r,
        });
    }
    return out;
};

const buildPoiLayer = (_kind, color) => {
    if (!L.value || !map.value) return null;
    const group = L.value.layerGroup();
    const pts = offsetMarkers(props.latitude, props.longitude, 6, 0.015);
    pts.forEach((p) => {
        L.value
            .circleMarker([p.lat, p.lng], { radius: 6, color, fillColor: color, fillOpacity: 0.85, weight: 1 })
            .addTo(group);
    });
    L.value
        .circle([props.latitude, props.longitude], {
            radius: 1400,
            color,
            fillColor: color,
            weight: 1,
            opacity: 0.35,
            fillOpacity: 0.08,
        })
        .addTo(group);
    return group;
};

const ensureMap = async () => {
    if (map.value || !mapEl.value) return;
    const leaflet = await import('leaflet');
    await import('leaflet/dist/leaflet.css');
    const lib = leaflet.default;
    L.value = lib;

    map.value = lib.map(mapEl.value, {
        center: [props.latitude, props.longitude],
        zoom: 14,
        scrollWheelZoom: true,
    });

    lib.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap',
    }).addTo(map.value);

    const html = `
      <div class="flex h-10 w-10 items-center justify-center rounded-full border-2 border-white bg-accent shadow-lg ring-2 ring-accent/30">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
        </svg>
      </div>`;
    const icon = lib.divIcon({
        className: 'bg-transparent border-0',
        html,
        iconSize: [40, 40],
        iconAnchor: [20, 40],
    });

    lib.marker([props.latitude, props.longitude], { icon }).addTo(map.value).bindPopup(props.title || 'Property');

    layers.value = {
        schools: buildPoiLayer('schools', '#2563eb'),
        hospitals: buildPoiLayer('hospitals', '#dc2626'),
        transit: buildPoiLayer('transit', '#7c3aed'),
        shopping: buildPoiLayer('shopping', '#059669'),
    };

    Object.entries(toggles.value).forEach(([k, on]) => {
        if (on) layers.value[k]?.addTo(map.value);
    });

    await nextTick();
    map.value.invalidateSize();
};

const destroyMap = () => {
    if (map.value) {
        map.value.remove();
        map.value = null;
    }
    L.value = null;
    layers.value = {};
};

watch(
    toggles,
    (nv) => {
        if (!map.value || !L.value) return;
        Object.entries(nv).forEach(([key, on]) => {
            const layer = layers.value[key];
            if (!layer) return;
            if (on) layer.addTo(map.value);
            else map.value.removeLayer(layer);
        });
    },
    { deep: true },
);

onMounted(() => {
    observer = new IntersectionObserver(
        (entries) => {
            if (entries.some((e) => e.isIntersecting)) {
                visible.value = true;
                observer?.disconnect();
            }
        },
        { rootMargin: '120px', threshold: 0.05 },
    );
    if (root.value) observer.observe(root.value);
});

watch(visible, async (v) => {
    if (v) {
        await nextTick();
        await ensureMap();
    }
});

onBeforeUnmount(() => {
    observer?.disconnect();
    destroyMap();
});
</script>

<template>
    <section ref="root" class="rounded-2xl border border-primary/10 bg-white p-4 shadow-premium sm:p-5" aria-label="Location map">
        <div class="mb-3 flex flex-col gap-2.5 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="text-lg font-bold text-primary">Location & nearby</h2>
            <div class="flex flex-wrap gap-2">
                <button
                    v-for="btn in poiButtons"
                    :key="btn.key"
                    type="button"
                    class="rounded-full border px-3 py-1 text-xs font-bold uppercase tracking-wide transition"
                    :class="toggles[btn.key] ? 'border-accent bg-accent/15 text-primary' : 'border-primary/15 text-primary/50 hover:border-accent/40'"
                    @click="toggles[btn.key] = !toggles[btn.key]"
                >
                    {{ btn.label }}
                </button>
            </div>
        </div>
        <div
            v-show="!visible"
            class="flex h-[280px] items-center justify-center rounded-xl bg-surface-gray text-sm text-primary/40 md:h-[360px]"
        >
            Map loads as you scroll…
        </div>
        <div v-show="visible" ref="mapEl" class="z-0 h-[280px] w-full overflow-hidden rounded-xl border border-primary/10 md:h-[360px]" />
        <p class="mt-2 text-xs text-primary/40">
            Nearby markers are illustrative. Connect Google Places for live POI data.
        </p>
    </section>
</template>

<style scoped>
:deep(.leaflet-container) {
    font-family: inherit;
}
</style>
