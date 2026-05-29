<script setup>
import { computed } from 'vue';
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/Components/AppLayout.vue';
import PropertyGallery from '@/Components/property/PropertyGallery.vue';
import PropertyDetails from '@/Components/property/PropertyDetails.vue';
import AgentCard from '@/Components/property/AgentCard.vue';
import EnquiryForm from '@/Components/property/EnquiryForm.vue';
import SimilarProperties from '@/Components/property/SimilarProperties.vue';
import QuickContactModal from '@/Components/property/QuickContactModal.vue';
import PropertyContactBar from '@/Components/property/PropertyContactBar.vue';
import { usePropertySeo } from '@/Composables/usePropertySeo';
import { usePropertyContactLead } from '@/Composables/usePropertyContactLead';

const props = defineProps({
    property: {
        type: Object,
        required: true,
    },
});

const { pageTitle, description, ogImage, canonical, jsonLd } = usePropertySeo(props.property);

const propertyRef = computed(() => props.property);
const agentRef = computed(() => props.property.agent);

const {
    modalOpen,
    modalReady,
    channel,
    requestContact,
    closeModal,
    handleLeadSuccess,
    waLink,
    callLink,
} = usePropertyContactLead(agentRef, propertyRef);

modalReady.value = true;

const openContactModal = (nextChannel) => {
    requestContact(nextChannel);
};

const onLeadSuccess = (payload) => {
    handleLeadSuccess(payload);
};
</script>

<template>
    <AppLayout :title="property.title" :use-secondary-navbar="true">
        <Head>
            <meta head-key="description" name="description" :content="description" />
            <link head-key="canonical" rel="canonical" :href="canonical" />
            <meta head-key="og:title" property="og:title" :content="pageTitle" />
            <meta head-key="og:description" property="og:description" :content="description" />
            <meta head-key="og:type" property="og:type" content="website" />
            <meta head-key="og:url" property="og:url" :content="canonical" />
            <meta v-if="ogImage" head-key="og:image" property="og:image" :content="ogImage" />
            <meta head-key="twitter:card" name="twitter:card" content="summary_large_image" />
            <meta head-key="twitter:title" name="twitter:title" :content="pageTitle" />
            <meta head-key="twitter:description" name="twitter:description" :content="description" />
            <meta v-if="ogImage" head-key="twitter:image" name="twitter:image" :content="ogImage" />
            <component :is="'script'" v-if="jsonLd" type="application/ld+json" v-html="jsonLd" />
        </Head>
        <div class="bg-surface-gray min-h-screen page-offset pb-24 md:pb-20">
            <div class="container-page sm:px-6">
                <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:gap-8">
                    <!-- Main column (~65%) -->
                    <div class="w-full min-w-0 space-y-4 md:space-y-6 lg:w-[65%] lg:max-w-[65%]">
                        <PropertyGallery
                            :images="property.gallery"
                            :virtual-tour-url="property.virtual_tour_url"
                        />
                        <PropertyDetails :property="property" />
                        <SimilarProperties
                            :property-id="property.id"
                            :city="property.city"
                        />
                    </div>

                    <!-- Sticky sidebar (~35%) -->
                    <aside class="w-full shrink-0 space-y-4 lg:w-[35%] lg:max-w-[35%] lg:sticky lg:top-24 lg:space-y-5">
                        <AgentCard :agent="property.agent" :property="property" @contact="openContactModal" />
                        <div
                            id="schedule-visit"
                            class="rounded-2xl border border-primary/10 bg-white p-4 shadow-premium"
                        >
                            <h3 class="text-base font-bold text-primary sm:text-lg">Schedule a visit</h3>
                            <p class="mt-1 text-sm leading-relaxed text-primary/60">
                                Pick a preferred date in the enquiry form — we will confirm shortly.
                            </p>
                            <a
                                href="#enquiry-form"
                                class="mt-3 inline-flex w-full items-center justify-center rounded-xl bg-primary px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-primary/90"
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

        <PropertyContactBar :agent="property.agent" :property="property" @contact="openContactModal" />

        <QuickContactModal
            v-if="modalReady"
            :show="modalOpen"
            :channel="channel"
            :agent-name="property.agent?.name || ''"
            :property-id="property.id"
            :property-title="property.title"
            :whatsapp-href="waLink"
            :tel-href="callLink"
            @close="closeModal"
            @success="onLeadSuccess"
        />
    </AppLayout>
</template>
