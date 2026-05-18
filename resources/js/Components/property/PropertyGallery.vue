<script setup>
import { ref, watch, onBeforeUnmount, nextTick, computed } from 'vue';

const props = defineProps({
    images: {
        type: Array,
        default: () => [],
    },
    virtualTourUrl: {
        type: String,
        default: null,
    },
});

const list = computed(() => (Array.isArray(props.images) && props.images.length ? props.images : []));

const activeIndex = ref(0);
const lightboxOpen = ref(false);
const lightboxRoot = ref(null);
const mainImgRef = ref(null);

let previousFocus = null;
let tabHandler = null;

const activeSrc = computed(() => list.value[activeIndex.value] ?? '');

const openLightbox = (index = activeIndex.value) => {
    activeIndex.value = Math.min(Math.max(0, index), Math.max(0, list.value.length - 1));
    previousFocus = document.activeElement;
    lightboxOpen.value = true;
};

const closeLightbox = () => {
    lightboxOpen.value = false;
    nextTick(() => {
        previousFocus?.focus?.();
        previousFocus = null;
    });
};

const go = (delta) => {
    const len = list.value.length;
    if (!len) return;
    activeIndex.value = (activeIndex.value + delta + len) % len;
};

const getFocusable = (root) => {
    if (!root) return [];
    const sel = 'a[href], button:not([disabled]), [tabindex]:not([tabindex="-1"])';
    return Array.from(root.querySelectorAll(sel)).filter((el) => el.offsetParent !== null || el === document.activeElement);
};

const trapFocus = (e) => {
    if (e.key !== 'Tab' || !lightboxRoot.value) return;
    if (!lightboxRoot.value.contains(document.activeElement)) return;
    const nodes = getFocusable(lightboxRoot.value);
    if (nodes.length === 0) return;
    const first = nodes[0];
    const last = nodes[nodes.length - 1];
    if (e.shiftKey) {
        if (document.activeElement === first) {
            e.preventDefault();
            last.focus();
        }
    } else if (document.activeElement === last) {
        e.preventDefault();
        first.focus();
    }
};

watch(lightboxOpen, async (open) => {
    if (open) {
        document.body.style.overflow = 'hidden';
        await nextTick();
        const nodes = getFocusable(lightboxRoot.value);
        (nodes[0] ?? lightboxRoot.value)?.focus?.();
        tabHandler = (e) => trapFocus(e);
        window.addEventListener('keydown', onGlobalKey);
        window.addEventListener('keydown', tabHandler);
    } else {
        document.body.style.overflow = '';
        window.removeEventListener('keydown', onGlobalKey);
        if (tabHandler) {
            window.removeEventListener('keydown', tabHandler);
            tabHandler = null;
        }
    }
});

const onGlobalKey = (e) => {
    if (!lightboxOpen.value) return;
    if (e.key === 'Escape') {
        e.preventDefault();
        closeLightbox();
    }
    if (e.key === 'ArrowLeft') {
        e.preventDefault();
        go(-1);
    }
    if (e.key === 'ArrowRight') {
        e.preventDefault();
        go(1);
    }
};

/* Touch swipe */
const touchStartX = ref(0);
const touchEndX = ref(0);

const onTouchStart = (e) => {
    touchStartX.value = e.changedTouches[0].screenX;
};
const onTouchEnd = (e) => {
    touchEndX.value = e.changedTouches[0].screenX;
    const dx = touchEndX.value - touchStartX.value;
    if (Math.abs(dx) < 50) return;
    if (dx < 0) go(1);
    else go(-1);
};

onBeforeUnmount(() => {
    document.body.style.overflow = '';
    window.removeEventListener('keydown', onGlobalKey);
    if (tabHandler) window.removeEventListener('keydown', tabHandler);
});
</script>

<template>
    <section class="space-y-3" aria-label="Property gallery">
        <div class="relative overflow-hidden rounded-2xl bg-primary/5 shadow-premium">
            <div class="aspect-video w-full overflow-hidden bg-primary/10">
                <img
                    ref="mainImgRef"
                    :src="activeSrc"
                    alt=""
                    class="h-full w-full cursor-zoom-in object-cover"
                    role="button"
                    tabindex="0"
                    @click="openLightbox(activeIndex)"
                    @keydown.enter.prevent="openLightbox(activeIndex)"
                    @keydown.space.prevent="openLightbox(activeIndex)"
                />
            </div>

            <div
                v-if="list.length"
                class="pointer-events-none absolute inset-x-0 bottom-0 flex justify-end p-3"
            >
                <button
                    type="button"
                    class="pointer-events-auto rounded-xl bg-primary/90 px-4 py-2 text-sm font-semibold text-white shadow-lg backdrop-blur transition hover:bg-primary"
                    @click="openLightbox(activeIndex)"
                >
                    View All {{ list.length }} Photos
                </button>
            </div>
        </div>

        <div
            v-if="list.length > 1"
            class="flex gap-2 overflow-x-auto pb-1 [-ms-overflow-style:none] [scrollbar-width:none] [&::-webkit-scrollbar]:hidden"
            role="tablist"
            aria-label="Photo thumbnails"
        >
            <button
                v-for="(src, idx) in list"
                :key="idx"
                type="button"
                role="tab"
                :aria-selected="idx === activeIndex"
                class="relative h-16 w-[calc(20%-9.6px)] min-w-[calc(20%-9.6px)] shrink-0 overflow-hidden rounded-xl border-2 transition sm:h-20"
                :class="idx === activeIndex ? 'border-accent ring-2 ring-accent/40' : 'border-transparent opacity-70 hover:opacity-100'"
                @click="activeIndex = idx"
            >
                <img :src="src" class="h-full w-full object-cover" alt="" />
            </button>
        </div>

        <div class="flex flex-wrap gap-3">
            <a
                v-if="virtualTourUrl"
                :href="virtualTourUrl"
                target="_blank"
                rel="noopener noreferrer"
                class="inline-flex items-center gap-2 rounded-xl border border-primary/15 bg-white px-4 py-2 text-sm font-semibold text-primary shadow-sm transition hover:border-accent/50 hover:text-accent"
            >
                <svg class="h-5 w-5 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                </svg>
                Virtual Tour
            </a>
            <button
                v-else
                type="button"
                disabled
                class="inline-flex cursor-not-allowed items-center gap-2 rounded-xl border border-gray-200 bg-gray-50 px-4 py-2 text-sm font-semibold text-gray-400"
                aria-disabled="true"
            >
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                </svg>
                Virtual Tour
            </button>
        </div>

        <Teleport to="body">
            <div
                v-if="lightboxOpen"
                ref="lightboxRoot"
                class="fixed inset-0 z-[200] flex items-center justify-center bg-black/90"
                role="dialog"
                aria-modal="true"
                aria-label="Property photo gallery lightbox"
                tabindex="-1"
                @touchstart.passive="onTouchStart"
                @touchend.passive="onTouchEnd"
            >
                <button
                    type="button"
                    class="absolute right-4 top-4 z-10 rounded-full bg-white/10 p-2 text-white ring-1 ring-white/30 transition hover:bg-white/20"
                    aria-label="Close gallery"
                    @click="closeLightbox"
                >
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

                <button
                    type="button"
                    class="absolute left-2 top-1/2 z-10 -translate-y-1/2 rounded-full bg-white/10 p-3 text-white ring-1 ring-white/30 transition hover:bg-white/20 sm:left-6"
                    aria-label="Previous photo"
                    @click="go(-1)"
                >
                    <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>

                <button
                    type="button"
                    class="absolute right-2 top-1/2 z-10 -translate-y-1/2 rounded-full bg-white/10 p-3 text-white ring-1 ring-white/30 transition hover:bg-white/20 sm:right-6"
                    aria-label="Next photo"
                    @click="go(1)"
                >
                    <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>

                <div class="flex max-h-[85vh] w-full max-w-6xl flex-col items-center px-12 sm:px-16">
                    <img :src="activeSrc" class="max-h-[75vh] w-auto max-w-full object-contain" alt="" />
                    <p class="mt-4 text-sm font-medium text-white/80" aria-live="polite">
                        {{ activeIndex + 1 }} / {{ list.length }}
                    </p>
                </div>
            </div>
        </Teleport>
    </section>
</template>
