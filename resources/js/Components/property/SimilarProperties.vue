<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { Link } from '@inertiajs/vue3';
import PropertyCard from '@/Components/PropertyCard.vue';

const props = defineProps({
    propertyId: { type: Number, required: true },
    city: { type: String, default: '' },
});

const items = ref([]);
const loading = ref(true);
const error = ref(null);

onMounted(async () => {
    loading.value = true;
    error.value = null;
    try {
        const { data } = await axios.get(`/api/properties/${props.propertyId}/similar`);
        items.value = data.data || [];
    } catch (e) {
        error.value = 'Could not load similar properties.';
        console.error(e);
    } finally {
        loading.value = false;
    }
});
</script>

<template>
    <section class="rounded-2xl border border-primary/10 bg-white p-6 shadow-premium sm:p-8" aria-label="Similar properties">
        <div class="mb-4 flex items-center justify-between gap-4">
            <h2 class="text-lg font-bold text-primary">Similar properties</h2>
            <Link
                :href="`/property?city=${encodeURIComponent(city)}`"
                class="shrink-0 text-sm font-bold text-accent hover:underline"
            >
                View all similar
            </Link>
        </div>

        <p v-if="loading" class="text-sm text-primary/50">Loading…</p>
        <p v-else-if="error" class="text-sm text-red-600">{{ error }}</p>
        <p v-else-if="!items.length" class="text-sm text-primary/50">No similar listings right now.</p>

        <template v-else>
            <!-- Mobile: horizontal scroll -->
            <div class="-mx-2 flex gap-4 overflow-x-auto px-2 pb-2 snap-x snap-mandatory md:hidden">
                <div v-for="p in items" :key="p.id" class="w-[min(85vw,320px)] shrink-0 snap-center">
                    <Link :href="`/property/${p.id}`" class="block h-full">
                        <PropertyCard :property="p" />
                    </Link>
                </div>
            </div>

            <!-- Desktop: 3 columns -->
            <div class="hidden gap-6 md:grid md:grid-cols-3">
                <Link v-for="p in items" :key="p.id" :href="`/property/${p.id}`" class="block">
                    <PropertyCard :property="p" />
                </Link>
            </div>
        </template>
    </section>
</template>
