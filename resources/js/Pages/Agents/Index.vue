<script setup>
import { Link, router } from '@inertiajs/vue3';
import AppLayout from '@/Components/AppLayout.vue';
import PageSeoHead from '@/Components/PageSeoHead.vue';
import { ref } from 'vue';

const props = defineProps({
    agents: Array,
    cities: Array,
    filters: Object,
});

const city = ref(props.filters?.city || '');

const applyFilter = () => {
    router.get(route('agents.index'), { city: city.value || undefined }, { preserveState: true, replace: true });
};
</script>

<template>
    <AppLayout title="Our Agents">
        <PageSeoHead
            title="Our Agents"
            description="Meet Ocean Realtors property consultants in Gurgaon and NCR — call or WhatsApp your dedicated agent for viewings."
            path="/agents"
        />

        <section class="bg-gradient-to-br from-navy via-slate-900 to-primary/90 text-white pt-20 pb-10 md:pt-28 md:pb-16">
            <div class="container max-w-6xl mx-auto px-4 text-center">
                <p class="text-xs font-bold uppercase tracking-[0.25em] text-white/60 mb-3">Meet the team</p>
                <h1 class="text-3xl md:text-4xl font-black">Property experts you can trust</h1>
                <p class="mt-3 text-white/70 max-w-xl mx-auto text-sm md:text-base">
                    Every listing on Ocean Realtors is handled by a dedicated consultant — reach out directly for viewings and advice.
                </p>
            </div>
        </section>

        <section class="container-page max-w-6xl py-6 md:py-10 -mt-6 md:-mt-8">
            <form @submit.prevent="applyFilter" class="bg-white rounded-2xl shadow-lg border border-border card-pad flex flex-col sm:flex-row gap-3 mb-6 md:mb-10">
                <select v-model="city" class="flex-1 rounded-xl border-gray-200 text-sm focus:border-primary focus:ring-primary">
                    <option value="">All cities</option>
                    <option v-for="c in cities" :key="c" :value="c">{{ c }}</option>
                </select>
                <button type="submit" class="px-6 py-2.5 bg-primary text-white rounded-xl font-bold text-sm hover:bg-primary-hover transition-colors">
                    Filter
                </button>
            </form>

            <div v-if="agents.length" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 md:gap-6">
                <Link
                    v-for="agent in agents"
                    :key="agent.id"
                    :href="route('agents.show', agent.slug)"
                    class="group bg-white rounded-2xl border border-border overflow-hidden shadow-sm hover:shadow-xl hover:border-primary/20 transition-all duration-300"
                >
                    <div class="p-6 text-center">
                        <img
                            :src="agent.avatar"
                            :alt="agent.name"
                            class="w-24 h-24 mx-auto rounded-full object-cover ring-4 ring-primary/10 group-hover:ring-primary/30 transition-all"
                        />
                        <h2 class="mt-4 text-lg font-bold text-navy group-hover:text-primary transition-colors">{{ agent.name }}</h2>
                        <p class="text-sm text-text-secondary">{{ agent.designation }}</p>
                        <p class="text-xs text-text-muted mt-1">{{ agent.city }}</p>
                        <div class="mt-4 flex items-center justify-center gap-2 text-sm">
                            <span class="font-bold text-star">{{ agent.rating }} ★</span>
                            <span class="text-text-muted">({{ agent.reviews_count }} reviews)</span>
                        </div>
                        <p class="mt-3 text-xs font-semibold text-primary">{{ agent.listed_count }} active listings</p>
                    </div>
                </Link>
            </div>

            <div v-else class="text-center py-20 text-text-secondary">
                <p class="text-lg font-semibold">No agents found for this city.</p>
            </div>
        </section>
    </AppLayout>
</template>
