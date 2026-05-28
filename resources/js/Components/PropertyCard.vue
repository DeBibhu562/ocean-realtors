<script setup>
import { computed, ref } from 'vue';
import { Link } from '@inertiajs/vue3';
import PropertyCardBrandBar from '@/Components/PropertyCardBrandBar.vue';
import PropertyListingSpecs from '@/Components/PropertyListingSpecs.vue';
import PropertyCardContactActions from '@/Components/property/PropertyCardContactActions.vue';
import { propertyShowPath } from '@/config/listingRouting';
import { formatIndianPrice } from '@/utils/formatIndianPrice';
import { buildLocationLabel } from '@/utils/propertyEnquiry';
import { usePropertyContact } from '@/Composables/usePropertyContact';

const props = defineProps({
    property: { type: Object, required: true },
    lazyImage: { type: Boolean, default: true },
});

const showPhone = ref(false);

const isRental = computed(
    () => props.property.status === 'rent' || props.property.is_rental === true,
);

const detailHref = computed(() =>
    propertyShowPath(props.property.slug || props.property.id),
);

const locationLabel = computed(() => buildLocationLabel(props.property));

const priceLabel = computed(() =>
    formatIndianPrice(props.property.price, { isRental: isRental.value }),
);

const agentRef = computed(() => props.property?.agent || null);
const propertyRef = computed(() => props.property);
const { phoneDisplay, hasContact } = usePropertyContact(agentRef, propertyRef);

const cardSizes = '(max-width: 640px) 100vw, (max-width: 1024px) 50vw, 480px';
</script>

<template>
    <article
        class="group flex h-full flex-col overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm transition-all duration-300 hover:-translate-y-0.5 hover:border-primary/15 hover:shadow-lg"
    >
        <PropertyCardBrandBar
            :agent-name="property.agent?.name"
            :agent-designation="property.agent?.designation"
            :agent-avatar="property.agent?.avatar"
        />

        <Link :href="detailHref" class="relative block aspect-[4/3] overflow-hidden bg-slate-100">
            <img
                :src="property.image || '/images/property-placeholder.svg'"
                :srcset="property.image_srcset || undefined"
                :sizes="property.image_srcset ? cardSizes : undefined"
                :alt="property.title"
                class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105"
                width="480"
                height="360"
                :loading="lazyImage ? 'lazy' : 'eager'"
                decoding="async"
            />

            <div class="absolute left-3 top-3 flex flex-col gap-1.5">
                <span
                    v-if="isRental"
                    class="rounded-md bg-primary/90 px-2.5 py-0.5 text-[10px] font-black uppercase tracking-widest text-white shadow-md"
                >
                    For rent
                </span>
                <span
                    v-else
                    class="rounded-md bg-emerald-600 px-2.5 py-0.5 text-[10px] font-black uppercase tracking-widest text-white shadow-md"
                >
                    For sale
                </span>
                <span
                    v-if="property.isFeatured"
                    class="flex items-center rounded-md bg-amber-400 px-2.5 py-0.5 text-[9px] font-black uppercase tracking-widest text-navy shadow-md"
                >
                    <svg class="mr-0.5 h-3 w-3" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                        <path
                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"
                        />
                    </svg>
                    Featured
                </span>
            </div>
        </Link>

        <div class="flex flex-1 flex-col p-4 sm:p-5">
            <h3 class="line-clamp-2 text-base font-bold leading-snug text-navy sm:text-lg">
                <Link :href="detailHref" class="hover:text-primary">{{ property.title }}</Link>
            </h3>

            <p class="mt-1 text-xs font-semibold uppercase tracking-wide text-text-secondary">
                {{ locationLabel }}
            </p>

            <p v-if="Number(property.price) > 0" class="mt-2 text-xl font-black leading-none text-navy sm:text-2xl">
                {{ priceLabel }}
            </p>

            <PropertyListingSpecs class="mt-3" :property="property" :columns="2" />

            <div v-if="property.description_preview" class="mt-3">
                <p class="line-clamp-2 text-xs leading-relaxed text-text-secondary">
                    {{ property.description_preview }}
                </p>
                <Link :href="detailHref" class="mt-1 inline-block text-xs font-bold text-primary hover:underline">
                    Read more
                </Link>
            </div>

            <div class="mt-auto flex flex-wrap items-center justify-end gap-2 border-t border-gray-100 pt-3">
                <p
                    v-if="showPhone && phoneDisplay"
                    class="mr-auto text-sm font-bold text-navy"
                >
                    {{ phoneDisplay }}
                </p>
                <PropertyCardContactActions :property="property" />
                <button
                    type="button"
                    class="inline-flex h-10 items-center justify-center rounded-lg bg-slate-800 px-3 text-[11px] font-bold text-white shadow-sm transition hover:bg-slate-900 disabled:cursor-not-allowed disabled:opacity-40"
                    :disabled="!hasContact"
                    @click="showPhone = !showPhone"
                >
                    View number
                </button>
            </div>
        </div>
    </article>
</template>
