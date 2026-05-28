<script setup>
defineProps({
    /** light = under white navbar; dark = under navy properties navbar */
    variant: { type: String, default: 'light' },
});

const tagline = "India's No. 1 Property Portal";
</script>

<template>
    <div
        role="region"
        aria-label="Site tagline"
        :class="[
            'w-full border-b flex items-center justify-center',
            variant === 'dark'
                ? 'bg-gradient-to-r from-primary via-[#1e4fd4] to-primary border-white/10'
                : 'bg-gradient-to-r from-navy via-[#1e293b] to-navy border-primary/20',
        ]"
    >
        <div class="container-page max-w-6xl w-full flex items-center justify-center gap-2 sm:gap-3 px-1 py-2 md:py-2.5 min-h-[2rem] md:min-h-[2.25rem]">
            <span
                class="hidden sm:inline-flex shrink-0 items-center justify-center w-6 h-6 rounded-full bg-white/10 text-amber-300"
                aria-hidden="true"
            >
                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                </svg>
            </span>

            <p class="text-center text-[11px] sm:text-xs md:text-sm font-semibold text-white tracking-wide leading-tight whitespace-nowrap">
                {{ tagline }}
            </p>

            <span
                class="hidden sm:inline-flex shrink-0 items-center justify-center w-6 h-6 rounded-full bg-white/10 text-amber-300"
                aria-hidden="true"
            >
                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                </svg>
            </span>
        </div>
    </div>
</template>
