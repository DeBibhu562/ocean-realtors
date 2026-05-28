<script setup>
import { ref, onMounted, watch } from 'vue';
import { Link } from '@inertiajs/vue3';
import axios from 'axios';
import PropertyCard from '@/Components/PropertyCard.vue';

const props = defineProps({
    featuredProperties: { type: Array, default: () => [] },
});

const tabs = [
    { label: 'All', value: '' },
    { label: 'Apartment', value: 'apartment' },
    { label: 'Builder Floor', value: 'builder_floor' },
    { label: 'Plot', value: 'plot' },
    { label: 'Villa', value: 'villa' },
];

const activeTab = ref('');
const properties = ref([]);
const isLoading = ref(false);
const loadError = ref(null);

function applyServerProps() {
    if (activeTab.value !== '') return;

    const fromServer = Array.isArray(props.featuredProperties) ? props.featuredProperties : [];
    if (fromServer.length > 0) {
        properties.value = [...fromServer];
    }
}

async function fetchFeatured() {
    loadError.value = null;

    if (activeTab.value === '') {
        applyServerProps();
        if (properties.value.length > 0) return;
    }

    isLoading.value = true;
    try {
        const response = await axios.get('/api/properties/featured', {
            params: { type: activeTab.value || undefined, limit: 6 },
        });
        properties.value = response.data?.data ?? [];
    } catch (err) {
        console.error(err);
        loadError.value = 'Could not load featured properties.';
        properties.value = [];
    } finally {
        isLoading.value = false;
    }
}

onMounted(() => {
    applyServerProps();
    if (properties.value.length === 0) {
        fetchFeatured();
    }
});

watch(() => props.featuredProperties, applyServerProps, { deep: true });
watch(activeTab, fetchFeatured);
</script>

<template>
    <section class="section-y-sm border-t border-border bg-white">
        <div class="container-page max-w-6xl">
            <div class="mb-4 flex flex-col gap-3 sm:mb-5 md:mb-6 md:flex-row md:items-end md:justify-between md:gap-4">
                <div class="min-w-0">
                    <h2 class="text-xl font-semibold text-text-primary md:text-2xl">Featured properties</h2>
                    <p class="mt-0.5 text-sm text-text-secondary">
                        Discover handpicked premium properties across prime locations.
                    </p>
                </div>
                <Link
                    href="/properties?featured=1"
                    class="inline-flex shrink-0 items-center gap-1 self-start whitespace-nowrap rounded-lg border border-primary/20 bg-primary/5 px-3 py-2 text-sm font-semibold text-primary transition hover:border-primary/40 hover:bg-primary/10 md:border-0 md:bg-transparent md:px-0 md:py-0 md:hover:bg-transparent md:hover:underline"
                >
                    View all
                    <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </Link>
            </div>

            <div class="-mx-1 mb-6 flex gap-2 overflow-x-auto scroll-smooth px-1 pb-2 pr-6 snap-x snap-mandatory md:mb-8 md:flex-wrap md:overflow-visible md:pb-0 md:pr-0">
                <button
                    v-for="tab in tabs"
                    :key="tab.value || 'all'"
                    type="button"
                    class="shrink-0 snap-start rounded-lg px-4 py-1.5 text-sm font-medium transition-all"
                    :class="
                        activeTab === tab.value
                            ? 'bg-primary text-white shadow-sm'
                            : 'bg-gray-50 text-text-secondary hover:bg-gray-100'
                    "
                    @click="activeTab = tab.value"
                >
                    {{ tab.label }}
                </button>
            </div>

            <p v-if="loadError" class="mb-4 text-sm text-red-600">{{ loadError }}</p>

            <div v-if="isLoading" class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                <div
                    v-for="i in 3"
                    :key="i"
                    class="aspect-video animate-pulse rounded-xl border border-border bg-gray-50"
                />
            </div>

            <div v-else-if="properties.length" class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                <PropertyCard v-for="property in properties" :key="property.id" :property="property" />
            </div>

            <div
                v-else
                class="rounded-xl border border-dashed border-border bg-gray-50 px-6 py-12 text-center text-sm text-text-secondary"
            >
                No featured listings in this category yet.
            </div>
        </div>
    </section>
</template>
