<script setup>
import { computed } from 'vue';
import { buildListingLabel } from '@/utils/propertyEnquiry';

const props = defineProps({
    property: { type: Object, required: true },
    columns: { type: Number, default: 2 },
});

const listingLabel = computed(() => {
    const label = buildListingLabel(props.property);
    const baths = Number(props.property.baths ?? props.property.bathrooms ?? 0);
    if (!label) return null;
    return baths > 0 ? `${label} + ${baths} Bath` : label;
});

const areaLabel = computed(() => {
    const area = Number(props.property.area ?? props.property.built_up_area ?? 0);
    if (!area) return null;
    const text = Number.isInteger(area) ? String(area) : area.toFixed(1);
    return `${text} sq.ft (Built-up)`;
});

const items = computed(() => {
    const rows = [];
    if (listingLabel.value) rows.push({ id: 'bed', text: listingLabel.value });
    if (areaLabel.value) rows.push({ id: 'area', text: areaLabel.value });
    return rows;
});
</script>

<template>
    <ul
        v-if="items.length"
        class="grid gap-2 text-xs text-text-secondary"
        :class="columns === 2 ? 'grid-cols-1 sm:grid-cols-2' : 'grid-cols-1'"
    >
        <li v-for="item in items" :key="item.id" class="flex min-w-0 items-start gap-2">
            <span
                class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-sky-50 text-primary"
                aria-hidden="true"
            >
                <svg
                    v-if="item.id === 'bed'"
                    class="h-4 w-4"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"
                    />
                </svg>
                <svg
                    v-else
                    class="h-4 w-4"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"
                    />
                </svg>
            </span>
            <span class="min-w-0 pt-1 font-medium leading-snug text-navy">{{ item.text }}</span>
        </li>
    </ul>
</template>
