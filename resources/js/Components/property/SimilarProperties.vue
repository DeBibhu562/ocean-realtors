<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { Link } from '@inertiajs/vue3';
import SimilarPropertyCard from '@/Components/property/SimilarPropertyCard.vue';

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
    <section
        class="rounded-xl border border-gray-100 bg-white p-4 shadow-sm sm:p-5"
        aria-label="Similar properties"
    >
        <div class="mb-3 flex items-center justify-between gap-3">
            <h2 class="text-base font-bold text-navy sm:text-lg">Similar properties</h2>
            <Link
                v-if="city"
                :href="route('properties.index', { city })"
                class="shrink-0 text-xs font-semibold text-primary hover:underline sm:text-sm"
            >
                View all
            </Link>
        </div>

        <p v-if="loading" class="text-sm text-gray-400">Loading…</p>
        <p v-else-if="error" class="text-sm text-red-600">{{ error }}</p>
        <p v-else-if="!items.length" class="text-sm text-gray-400">No similar listings right now.</p>

        <template v-else>
            <!-- Mobile: horizontal scroll -->
            <ul class="-mx-1 flex list-none gap-3 overflow-x-auto px-1 pb-1 snap-x snap-mandatory md:hidden">
                <li
                    v-for="p in items"
                    :key="p.id"
                    class="w-[min(88vw,22rem)] shrink-0 snap-start"
                >
                    <SimilarPropertyCard :property="p" />
                </li>
            </ul>

            <!-- Desktop: compact stacked list -->
            <ul class="hidden list-none space-y-2.5 md:block">
                <li v-for="p in items" :key="p.id">
                    <SimilarPropertyCard :property="p" />
                </li>
            </ul>
        </template>
    </section>
</template>
