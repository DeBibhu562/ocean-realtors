<script setup>
import PropertyCard from '@/Components/PropertyCard.vue';

defineProps({
    property: { type: Object, required: true },
});
</script>

<template>
    <PropertyCard :property="property" />
</template>
