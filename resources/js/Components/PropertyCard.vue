<script setup>
import { computed, onMounted, onUnmounted, ref, watch } from 'vue';
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
const currentImageIndex = ref(0);
const isDragging = ref(false);
const dragStartX = ref(0);
const dragDeltaX = ref(0);
const suppressNextClick = ref(false);
const mobileAutoSlideEnabled = ref(false);
let autoSlideTimer = null;
let visibilityObserver = null;
const imageRoot = ref(null);
const inViewport = ref(false);
const pageVisible = ref(true);

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

const cardSizes = '(max-width: 640px) 360px, (max-width: 1024px) 46vw, 378px';
const galleryImages = computed(() => {
    const photos = Array.isArray(props.property?.photos) ? props.property.photos : [];
    const merged = [props.property?.image, ...photos]
        .filter((img) => typeof img === 'string' && img.trim() !== '')
        .map((img) => img.trim());

    const unique = [...new Set(merged)];

    return unique.length > 0 ? unique : ['/images/property-placeholder.svg'];
});
const activeImage = computed(() =>
    galleryImages.value[currentImageIndex.value] || galleryImages.value[0],
);

const goPrevImage = () => {
    if (galleryImages.value.length <= 1) return;
    currentImageIndex.value = (currentImageIndex.value - 1 + galleryImages.value.length) % galleryImages.value.length;
};

const goNextImage = () => {
    if (galleryImages.value.length <= 1) return;
    currentImageIndex.value = (currentImageIndex.value + 1) % galleryImages.value.length;
};

const startAutoSlide = () => {
    stopAutoSlide();
    if (!mobileAutoSlideEnabled.value || galleryImages.value.length <= 1 || !inViewport.value || !pageVisible.value) return;

    autoSlideTimer = window.setInterval(() => {
        goNextImage();
    }, 3500);
};

const stopAutoSlide = () => {
    if (autoSlideTimer) {
        window.clearInterval(autoSlideTimer);
        autoSlideTimer = null;
    }
};

const onPointerDown = (event) => {
    if (galleryImages.value.length <= 1) return;
    isDragging.value = true;
    dragStartX.value = event.clientX;
    dragDeltaX.value = 0;
    stopAutoSlide();
};

const onPointerMove = (event) => {
    if (!isDragging.value) return;
    dragDeltaX.value = event.clientX - dragStartX.value;
};

const finishDrag = () => {
    if (!isDragging.value) return;

    const threshold = 45;
    if (dragDeltaX.value > threshold) {
        goPrevImage();
        suppressNextClick.value = true;
    } else if (dragDeltaX.value < -threshold) {
        goNextImage();
        suppressNextClick.value = true;
    }

    isDragging.value = false;
    dragStartX.value = 0;
    dragDeltaX.value = 0;
    startAutoSlide();
};

const onImageClick = (event) => {
    if (suppressNextClick.value) {
        event.preventDefault();
        event.stopPropagation();
        suppressNextClick.value = false;
    }
};

watch(
    () => galleryImages.value.length,
    () => {
        currentImageIndex.value = 0;
        startAutoSlide();
    },
);

onMounted(() => {
    mobileAutoSlideEnabled.value = window.matchMedia('(pointer: coarse)').matches;
    pageVisible.value = document.visibilityState === 'visible';
    document.addEventListener('visibilitychange', handleVisibilityChange, { passive: true });
    visibilityObserver = new IntersectionObserver(
        (entries) => {
            const isVisible = entries.some((entry) => entry.isIntersecting);
            if (isVisible !== inViewport.value) {
                inViewport.value = isVisible;
                if (isVisible) startAutoSlide();
                else stopAutoSlide();
            }
        },
        { rootMargin: '120px', threshold: 0.2 },
    );
    if (imageRoot.value) visibilityObserver.observe(imageRoot.value);
    startAutoSlide();
});

onUnmounted(() => {
    stopAutoSlide();
    document.removeEventListener('visibilitychange', handleVisibilityChange);
    visibilityObserver?.disconnect();
});

const handleVisibilityChange = () => {
    pageVisible.value = document.visibilityState === 'visible';
    if (pageVisible.value) startAutoSlide();
    else stopAutoSlide();
};
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

        <Link
            ref="imageRoot"
            :href="detailHref"
            class="relative block aspect-[4/3] touch-pan-y overflow-hidden bg-slate-100"
            @click="onImageClick"
            @pointerdown="onPointerDown"
            @pointermove="onPointerMove"
            @pointerup="finishDrag"
            @pointercancel="finishDrag"
            @pointerleave="finishDrag"
        >
            <img
                :src="activeImage"
                :srcset="property.image_srcset || undefined"
                :sizes="property.image_srcset ? cardSizes : undefined"
                :alt="property.title"
                class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105"
                width="480"
                height="360"
                :loading="lazyImage ? 'lazy' : 'eager'"
                :fetchpriority="lazyImage ? 'auto' : 'high'"
                decoding="async"
            />

            <template v-if="galleryImages.length > 1">
                <button
                    type="button"
                    class="absolute left-2 top-1/2 z-20 flex h-8 w-8 -translate-y-1/2 items-center justify-center rounded-full bg-black/35 text-white transition hover:bg-black/55"
                    aria-label="Previous image"
                    @click.prevent.stop="goPrevImage"
                >
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>
                <button
                    type="button"
                    class="absolute right-2 top-1/2 z-20 flex h-8 w-8 -translate-y-1/2 items-center justify-center rounded-full bg-black/35 text-white transition hover:bg-black/55"
                    aria-label="Next image"
                    @click.prevent.stop="goNextImage"
                >
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>

                <div class="absolute bottom-3 left-1/2 z-20 flex -translate-x-1/2 items-center gap-1.5">
                    <span
                        v-for="(img, idx) in galleryImages.slice(0, 5)"
                        :key="`${property.id}-dot-${idx}`"
                        class="h-1.5 w-1.5 rounded-full"
                        :class="idx === currentImageIndex ? 'bg-white' : 'bg-white/60'"
                        aria-hidden="true"
                    />
                    <span
                        v-if="galleryImages.length > 5"
                        class="rounded-full bg-black/35 px-1.5 py-0.5 text-[9px] font-semibold text-white"
                    >
                        +{{ galleryImages.length - 5 }}
                    </span>
                </div>
            </template>

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
