<script setup>
import { ref, computed } from 'vue';
import { Link } from '@inertiajs/vue3';
import { formatIndianPrice } from '@/utils/formatIndianPrice';

const props = defineProps({
    property: {
        type: Object,
        required: true,
    },
});

const expanded = ref(false);
const activeTab = ref('overview');
const floorZoom = ref(false);

const isRental = computed(() => !!props.property.is_rental);

const formatCompactNumber = (value) => {
    const num = Number(value ?? 0);
    if (!Number.isFinite(num)) return '0';
    return new Intl.NumberFormat('en-IN', { maximumFractionDigits: 0 }).format(num);
};

const postedLabel = computed(() => {
    if (!props.property.posted_at) return '';
    const d = new Date(props.property.posted_at);
    return d.toLocaleDateString('en-IN', { day: 'numeric', month: 'short', year: 'numeric' });
});

const amenityIcon = (label) => {
    const key = String(label).toLowerCase();
    if (key.includes('pool') || key.includes('swim')) return 'pool';
    if (key.includes('park')) return 'parking';
    if (key.includes('gym') || key.includes('fitness')) return 'gym';
    if (key.includes('wifi') || key.includes('internet')) return 'wifi';
    if (key.includes('security') || key.includes('cctv')) return 'security';
    if (key.includes('lift') || key.includes('elevator')) return 'lift';
    if (key.includes('club')) return 'club';
    if (key.includes('power') || key.includes('backup')) return 'power';
    return 'default';
};
</script>

<template>
    <article class="space-y-3 rounded-xl border border-slate-200 bg-white p-3 shadow-sm sm:p-4 md:space-y-4">
        <nav class="text-[11px] text-slate-500" aria-label="Breadcrumb">
            <ol class="flex flex-wrap items-center gap-1.5">
                <li>
                    <Link href="/" class="hover:text-accent">Home</Link>
                </li>
                <li aria-hidden="true">/</li>
                <li>
                    <Link :href="route('properties.index')" class="hover:text-accent">Properties</Link>
                </li>
                <li aria-hidden="true">/</li>
                <li>
                    <Link
                        :href="route('properties.index', { city: property.city })"
                        class="hover:text-accent"
                    >
                        {{ property.city }}
                    </Link>
                </li>
                <li aria-hidden="true">/</li>
                <li class="font-medium text-slate-700 line-clamp-1 max-w-full">{{ property.title }}</li>
            </ol>
        </nav>

        <header class="space-y-2.5">
            <h1 class="text-lg font-bold leading-snug tracking-tight text-slate-900 sm:text-xl md:text-2xl">
                {{ property.title }}
            </h1>

            <div class="flex flex-wrap items-center gap-1.5 text-xs text-slate-600">
                <svg class="h-3.5 w-3.5 shrink-0 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <span>{{ property.address }}, {{ property.city }}</span>
                <span class="rounded-full bg-slate-100 px-2 py-0.5 text-[10px] font-semibold uppercase tracking-wide text-slate-600">
                    {{ property.city }}
                </span>
            </div>

            <div class="flex flex-wrap items-baseline gap-1.5">
                <p class="text-base font-bold tabular-nums leading-none text-emerald-600 sm:text-lg md:text-xl">
                    {{ formatIndianPrice(property.price, { isRental: isRental }) }}
                </p>
            </div>

            <div class="grid grid-cols-2 gap-2 sm:grid-cols-4">
                <div class="rounded-lg border border-slate-200 bg-white p-2.5">
                    <div class="mb-1 flex items-center gap-1 text-slate-500">
                        <svg class="h-3.5 w-3.5 shrink-0 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
                        <span class="text-[10px] font-bold uppercase tracking-wide">Bedrooms</span>
                    </div>
                    <p class="text-lg font-bold leading-none text-slate-900">{{ formatCompactNumber(property.bedrooms) }}</p>
                </div>
                <div class="rounded-lg border border-slate-200 bg-white p-2.5">
                    <div class="mb-1 flex items-center gap-1 text-slate-500">
                        <svg class="h-3.5 w-3.5 shrink-0 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                        <span class="text-[10px] font-bold uppercase tracking-wide">Bathrooms</span>
                    </div>
                    <p class="text-lg font-bold leading-none text-slate-900">{{ formatCompactNumber(property.bathrooms) }}</p>
                </div>
                <div class="rounded-lg border border-slate-200 bg-white p-2.5">
                    <div class="mb-1 flex items-center gap-1 text-slate-500">
                        <svg class="h-3.5 w-3.5 shrink-0 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4" /></svg>
                        <span class="text-[10px] font-bold uppercase tracking-wide">Area</span>
                    </div>
                    <p class="text-lg font-bold leading-none text-slate-900 sm:text-xl">{{ formatCompactNumber(Math.round(property.area_display || 0)) }}</p>
                    <p class="mt-0.5 text-[10px] font-semibold uppercase tracking-wide text-slate-500">sq ft</p>
                </div>
                <div class="rounded-lg border border-slate-200 bg-white p-2.5">
                    <div class="mb-1 flex items-center gap-1 text-slate-500">
                        <svg class="h-3.5 w-3.5 shrink-0 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                        <span class="text-[10px] font-bold uppercase tracking-wide">Type</span>
                    </div>
                    <p class="text-sm font-semibold leading-tight text-slate-900 line-clamp-2">{{ property.type }}</p>
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-2 text-xs">
                <span
                    class="rounded-full px-2.5 py-0.5 text-[10px] font-bold uppercase tracking-wide"
                    :class="isRental ? 'bg-emerald-100 text-emerald-700' : 'bg-blue-600 text-white'"
                >
                    {{ isRental ? 'For Rent' : 'For Sale' }}
                </span>
                <span v-if="postedLabel" class="text-xs text-slate-500">Posted {{ postedLabel }}</span>
                <span class="text-xs text-slate-500">{{ formatCompactNumber(property.views_count ?? 0) }} views</span>
            </div>
        </header>

        <div class="flex gap-1.5 border-b border-slate-200 pb-1.5" role="tablist" aria-label="Property information">
            <button
                type="button"
                role="tab"
                :aria-selected="activeTab === 'overview'"
                class="rounded-md px-2.5 py-1 text-xs font-semibold transition"
                :class="activeTab === 'overview' ? 'bg-blue-600 text-white' : 'text-slate-600 hover:bg-slate-100'"
                @click="activeTab = 'overview'"
            >
                Overview
            </button>
            <button
                v-if="property.floor_plan_image"
                type="button"
                role="tab"
                :aria-selected="activeTab === 'floor'"
                class="rounded-md px-2.5 py-1 text-xs font-semibold transition"
                :class="activeTab === 'floor' ? 'bg-blue-600 text-white' : 'text-slate-600 hover:bg-slate-100'"
                @click="activeTab = 'floor'"
            >
                Floor plan
            </button>
        </div>

        <div v-show="activeTab === 'overview'" class="space-y-3">
            <section v-if="property.description" aria-labelledby="desc-heading">
                <h2 id="desc-heading" class="mb-1.5 text-sm font-bold uppercase tracking-wide text-slate-500">Description</h2>
                <p class="whitespace-pre-line text-sm leading-6 text-slate-700" :class="expanded ? '' : 'line-clamp-4'">{{ property.description }}</p>
                <button
                    type="button"
                    class="mt-1.5 text-xs font-semibold text-blue-600 hover:underline"
                    @click="expanded = !expanded"
                >
                    {{ expanded ? 'Show less' : 'Read more' }}
                </button>
            </section>

            <section v-if="property.amenities?.length" aria-labelledby="amen-heading">
                <h2 id="amen-heading" class="mb-2 text-sm font-bold uppercase tracking-wide text-slate-500">Amenities</h2>
                <div class="flex flex-wrap gap-1.5">
                    <span
                        v-for="a in property.amenities"
                        :key="a"
                        class="inline-flex items-center gap-1 rounded-full border border-slate-200 bg-slate-50 px-2 py-0.5 text-[10px] font-semibold uppercase tracking-wide text-slate-700"
                    >
                        <template v-if="amenityIcon(a) === 'pool'">
                            <svg class="h-3 w-3 shrink-0 text-emerald-600" fill="currentColor" viewBox="0 0 24 24"><path d="M2 15c1.67 1 3.33 1 5 0s3.33-1 5 0 3.33 1 5 0 3.33-1 5 0v2c-1.67-1-3.33-1-5 0s-3.33 1-5 0-3.33-1-5 0-3.33 1-5 0-3.33-1-5 0v-2zM2 10c1.67 1 3.33 1 5 0s3.33-1 5 0 3.33 1 5 0 3.33-1 5 0 3.33 1 5 0v2c-1.67-1-3.33-1-5 0s-3.33 1-5 0-3.33-1-5 0-3.33 1-5 0-3.33-1-5 0v-2z"/></svg>
                        </template>
                        <template v-else-if="amenityIcon(a) === 'parking'">
                            <svg class="h-3 w-3 shrink-0 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" /></svg>
                        </template>
                        <template v-else-if="amenityIcon(a) === 'gym'">
                            <svg class="h-3 w-3 shrink-0 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" /></svg>
                        </template>
                        <template v-else-if="amenityIcon(a) === 'wifi'">
                            <svg class="h-3 w-3 shrink-0 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0" /></svg>
                        </template>
                        <template v-else-if="amenityIcon(a) === 'security'">
                            <svg class="h-3 w-3 shrink-0 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                        </template>
                        <template v-else-if="amenityIcon(a) === 'lift'">
                            <svg class="h-3 w-3 shrink-0 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" /></svg>
                        </template>
                        <template v-else-if="amenityIcon(a) === 'club'">
                            <svg class="h-3 w-3 shrink-0 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5" /></svg>
                        </template>
                        <template v-else-if="amenityIcon(a) === 'power'">
                            <svg class="h-3 w-3 shrink-0 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                        </template>
                        <template v-else>
                            <svg class="h-3 w-3 shrink-0 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                        </template>
                        {{ a }}
                    </span>
                </div>
            </section>

            <section v-if="property.highlights?.length" aria-labelledby="high-heading">
                <h2 id="high-heading" class="mb-1.5 text-sm font-bold uppercase tracking-wide text-slate-500">Highlights</h2>
                <ul class="flex flex-wrap gap-1.5">
                    <li v-for="h in property.highlights" :key="h" class="rounded-md bg-blue-50 px-2 py-0.5 text-xs font-medium text-blue-800">
                        {{ h }}
                    </li>
                </ul>
            </section>
        </div>

        <div v-show="activeTab === 'floor' && property.floor_plan_image">
            <button type="button" class="block w-full overflow-hidden rounded-xl border border-primary/10" @click="floorZoom = true">
                <img :src="property.floor_plan_image" alt="Floor plan" class="h-auto w-full object-contain" />
                <span class="sr-only">Open floor plan zoom</span>
            </button>
            <p class="mt-1.5 text-[11px] text-slate-500">Click image to zoom</p>
        </div>

        <Teleport to="body">
            <div
                v-if="floorZoom && property.floor_plan_image"
                class="fixed inset-0 z-[190] flex items-center justify-center bg-black/80 p-4"
                role="dialog"
                aria-modal="true"
                aria-label="Floor plan"
                @click.self="floorZoom = false"
            >
                <button type="button" class="absolute right-4 top-4 rounded-full bg-white/10 p-2 text-white" aria-label="Close" @click="floorZoom = false">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
                <img :src="property.floor_plan_image" class="max-h-full max-w-full object-contain" alt="Floor plan enlarged" />
            </div>
        </Teleport>
    </article>
</template>
