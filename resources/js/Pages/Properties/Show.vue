<script setup>
import { AppLayout } from '@/Components';
import PropertyGallery from '@/Components/property/PropertyGallery.vue';
import PropertyDetails from '@/Components/property/PropertyDetails.vue';
import PropertyMap from '@/Components/property/PropertyMap.vue';
import AgentCard from '@/Components/property/AgentCard.vue';
import EnquiryForm from '@/Components/property/EnquiryForm.vue';
import SimilarProperties from '@/Components/property/SimilarProperties.vue';

defineProps({
    property: {
        type: Object,
        required: true,
    },
});
</script>

<template>
    <AppLayout :title="property.title">
        <div class="bg-surface-gray min-h-screen pt-28 pb-20">
            <div class="container mx-auto px-4 sm:px-6">
                <div class="flex flex-col lg:flex-row lg:items-start gap-8 lg:gap-10">
                    <!-- Main column (~65%) -->
                    <div class="w-full lg:w-[65%] lg:max-w-[65%] space-y-10 min-w-0">
                        <PropertyGallery
                            :images="property.gallery"
                            :virtual-tour-url="property.virtual_tour_url"
                        />
                        <PropertyDetails :property="property" />
                        <PropertyMap
                            :latitude="property.latitude"
                            :longitude="property.longitude"
                            :title="property.title"
                        />
                        <SimilarProperties
                            :property-id="property.id"
                            :city="property.city"
                        />
                    </div>

                    <!-- Sticky sidebar (~35%) -->
                    <aside class="w-full lg:w-[35%] lg:max-w-[35%] shrink-0 space-y-6 lg:sticky lg:top-24">
                        <AgentCard :agent="property.agent" />
                        <div
                            id="schedule-visit"
                            class="rounded-2xl border border-primary/10 bg-white p-5 shadow-premium"
                        >
                            <h3 class="text-lg font-bold text-primary">Schedule a visit</h3>
                            <p class="mt-1 text-sm text-primary/60">
                                Pick a preferred date in the enquiry form — we will confirm shortly.
                            </p>
                            <a
                                href="#enquiry-form"
                                class="mt-4 inline-flex w-full items-center justify-center rounded-xl bg-primary px-4 py-3 text-sm font-semibold text-white transition hover:bg-primary/90"
                            >
                                Go to enquiry form
                            </a>
                        </div>
                        <EnquiryForm
                            :property-id="property.id"
                            :property-title="property.title"
                        />
                    </aside>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
