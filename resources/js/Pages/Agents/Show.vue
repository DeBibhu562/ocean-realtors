<script setup>
import { toRef } from 'vue';
import { Link } from '@inertiajs/vue3';
import AppLayout from '@/Components/AppLayout.vue';
import PropertyCard from '@/Components/PropertyCard.vue';
import PageSeoHead from '@/Components/PageSeoHead.vue';
import QuickContactModal from '@/Components/property/QuickContactModal.vue';
import { useAgentContactLead } from '@/Composables/useAgentContactLead';

const props = defineProps({
    agent: Object,
    properties: Array,
});

const agentRef = toRef(props, 'agent');

const {
    modalOpen,
    modalReady,
    channel,
    showPhone,
    isUnlocked,
    phoneDisplay,
    hasContact,
    callLink,
    waLink,
    requestContact,
    closeModal,
    handleLeadSuccess,
} = useAgentContactLead(agentRef);

const onCall = () => requestContact('call');
const onWhatsApp = () => requestContact('whatsapp');
const onLeadSuccess = (payload) => handleLeadSuccess(payload);
</script>

<template>
    <AppLayout :title="agent.name">
        <PageSeoHead
            :title="`${agent.name} — Property Consultant`"
            :description="`Contact ${agent.name}, ${agent.designation || 'Ocean Realtors agent'} in ${agent.city || 'Gurgaon'}. Enquire for property viewings and expert advice.`"
            :path="`/agents/${agent.slug}`"
            :image="agent.avatar"
        />

        <section class="bg-gradient-to-br from-navy to-primary text-white pt-20 pb-8 md:pt-28 md:pb-12">
            <div class="container max-w-6xl mx-auto px-4">
                <Link :href="route('agents.index')" class="text-sm text-white/70 hover:text-white mb-6 inline-block">← All agents</Link>
                <div class="flex flex-col md:flex-row stack-gap items-center md:items-start">
                    <img :src="agent.avatar" :alt="agent.name" class="w-32 h-32 md:w-40 md:h-40 rounded-2xl object-cover ring-4 ring-white/20 shadow-2xl" />
                    <div class="flex-1 text-center md:text-left">
                        <h1 class="text-3xl md:text-4xl font-black">{{ agent.name }}</h1>
                        <p class="text-lg text-white/80 mt-1">{{ agent.designation }}</p>
                        <p class="text-sm text-white/60 mt-1">{{ agent.city }} · {{ agent.experience_years }}+ years experience</p>
                        <div class="mt-4 flex flex-wrap items-center justify-center md:justify-start gap-4 text-sm">
                            <span class="font-bold">{{ agent.rating }} ★ ({{ agent.reviews_count }} reviews)</span>
                            <span>{{ agent.listed_count }} listings</span>
                        </div>
                        <p v-if="agent.bio" class="mt-4 text-white/75 max-w-2xl text-sm leading-relaxed">{{ agent.bio }}</p>

                        <p
                            v-if="showPhone && phoneDisplay"
                            class="mt-6 text-lg font-bold tracking-wide text-white"
                        >
                            {{ phoneDisplay }}
                        </p>

                        <div class="mt-6 flex flex-col sm:flex-row gap-3 justify-center md:justify-start">
                            <template v-if="isUnlocked">
                                <a
                                    :href="callLink"
                                    class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-white text-primary rounded-xl font-bold text-sm hover:bg-white/90 transition-colors"
                                >
                                    Call {{ phoneDisplay }}
                                </a>
                                <a
                                    :href="waLink"
                                    target="_blank"
                                    rel="noopener"
                                    class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-emerald-500 text-white rounded-xl font-bold text-sm hover:bg-emerald-600 transition-colors"
                                >
                                    WhatsApp
                                </a>
                            </template>
                            <template v-else>
                                <button
                                    type="button"
                                    class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-white text-primary rounded-xl font-bold text-sm hover:bg-white/90 transition-colors disabled:cursor-not-allowed disabled:opacity-50"
                                    :disabled="!hasContact"
                                    @click="onCall"
                                >
                                    Call agent
                                </button>
                                <button
                                    type="button"
                                    class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-emerald-500 text-white rounded-xl font-bold text-sm hover:bg-emerald-600 transition-colors disabled:cursor-not-allowed disabled:opacity-50"
                                    :disabled="!hasContact"
                                    @click="onWhatsApp"
                                >
                                    WhatsApp
                                </button>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="container-page max-w-6xl section-y-sm">
            <h2 class="text-xl font-bold text-navy mb-6">Properties by {{ agent.name }}</h2>
            <div v-if="properties.length" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <PropertyCard v-for="property in properties" :key="property.id" :property="property" />
            </div>
            <p v-else class="text-center text-text-secondary py-12">No active listings at the moment.</p>
        </section>

        <QuickContactModal
            v-if="modalReady"
            :show="modalOpen"
            :channel="channel"
            :agent-name="agent.name"
            :agent-id="agent.id"
            :whatsapp-href="waLink"
            :tel-href="callLink"
            @close="closeModal"
            @success="onLeadSuccess"
        />
    </AppLayout>
</template>
