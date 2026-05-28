<script setup>
import { ref, onMounted } from 'vue';
import { Link } from '@inertiajs/vue3';

const categories = [
    {
        name: 'Apartment',
        value: 'apartment',
        count: 1240,
        icon: 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4',
        card: 'bg-[#f4f7fa]',
        iconBg: 'bg-[#dce8f2] text-[#4a6d8f]',
        hover: 'hover:border-[#b8cfe0]',
    },
    {
        name: 'Independent House',
        value: 'house',
        count: 850,
        icon: 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6',
        card: 'bg-[#f3f5f0]',
        iconBg: 'bg-[#dce6d4] text-[#5a7350]',
        hover: 'hover:border-[#c5d4bc]',
    },
    {
        name: 'Villa',
        value: 'villa',
        count: 420,
        icon: 'M8 21h8m-4-4v4M3 10l9-7 9 7M5 10v10h14V10',
        card: 'bg-[#f5f2ee]',
        iconBg: 'bg-[#ebe4d9] text-[#8a7358]',
        hover: 'hover:border-[#ddd0c0]',
    },
    {
        name: 'Office Space',
        value: 'office',
        count: 310,
        icon: 'M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745V6a2 2 0 012-2h14a2 2 0 012 2v7.255z',
        card: 'bg-[#f2f1f5]',
        iconBg: 'bg-[#e2dfe8] text-[#625a75]',
        hover: 'hover:border-[#ccc6d6]',
    },
    {
        name: 'Plot / Land',
        value: 'plot',
        count: 180,
        icon: 'M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0121 18.382V7.618a1 1 0 01-1.447-.894L15 9m0 8V9',
        card: 'bg-[#eef3f2]',
        iconBg: 'bg-[#d4e8e4] text-[#4a756e]',
        hover: 'hover:border-[#b8d4ce]',
    },
    {
        name: 'Commercial Shop',
        value: 'commercial',
        count: 95,
        icon: 'M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z',
        card: 'bg-[#f4f3f6]',
        iconBg: 'bg-[#e4e2ea] text-[#5e5a6b]',
        hover: 'hover:border-[#cdc9d4]',
    },
];

const sectionRef = ref(null);
const isVisible = ref(false);

onMounted(() => {
    const observer = new IntersectionObserver(
        (entries) => {
            if (entries[0].isIntersecting) {
                isVisible.value = true;
                observer.disconnect();
            }
        },
        { threshold: 0.1 },
    );
    if (sectionRef.value) observer.observe(sectionRef.value);
});
</script>

<template>
    <section ref="sectionRef" class="section-y-sm relative overflow-hidden bg-[#f8f9fb]">
        <div
            class="pointer-events-none absolute inset-0 bg-[radial-gradient(ellipse_at_top,_#eef2f6_0%,_transparent_55%)]"
            aria-hidden="true"
        />

        <div class="container-page relative max-w-6xl">
            <div
                class="mb-8 md:mb-10 transition-all duration-700 ease-out"
                :class="isVisible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-6'"
            >
                <span class="mb-2 inline-block text-[11px] font-bold uppercase tracking-[0.2em] text-[#6b7c8f]">
                    Explore categories
                </span>
                <h2 class="text-xl font-bold tracking-tight text-[#2c3e50] md:text-2xl">
                    Browse by property type
                </h2>
                <p class="mt-1.5 max-w-lg text-sm text-[#6b7785]">
                    Discover the perfect property category for your needs.
                </p>
            </div>

            <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 sm:gap-4 lg:grid-cols-6">
                <Link
                    v-for="(cat, i) in categories"
                    :key="cat.value"
                    :href="route('properties.index', { type: cat.value })"
                    class="category-card group relative flex flex-col items-center overflow-hidden rounded-2xl border border-transparent p-4 text-center shadow-sm transition-all duration-500 ease-out sm:p-5"
                    :class="[
                        cat.card,
                        cat.hover,
                        isVisible ? 'category-card--visible' : 'category-card--hidden',
                    ]"
                    :style="{ transitionDelay: `${120 + i * 70}ms` }"
                >
                    <div
                        class="relative mb-3 flex h-12 w-12 items-center justify-center rounded-2xl transition-all duration-300 group-hover:scale-110 group-hover:shadow-md"
                        :class="cat.iconBg"
                    >
                        <svg
                            class="h-6 w-6 transition-transform duration-300 group-hover:-translate-y-0.5"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                            aria-hidden="true"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" :d="cat.icon" />
                        </svg>
                    </div>

                    <h3 class="relative text-sm font-semibold text-[#2c3e50] transition-colors duration-300 group-hover:text-[#1a56db]">
                        {{ cat.name }}
                    </h3>
                    <span class="relative mt-1 text-xs font-medium text-[#7a8794] transition-colors group-hover:text-[#5c6b7a]">
                        {{ cat.count.toLocaleString() }} Listings
                    </span>

                    <span
                        class="relative mt-3 flex items-center gap-1 text-[10px] font-semibold uppercase tracking-wider text-[#1a56db] opacity-0 transition-all duration-300 group-hover:opacity-100"
                    >
                        View all
                        <svg class="h-3 w-3 transition-transform group-hover:translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                        </svg>
                    </span>
                </Link>
            </div>
        </div>
    </section>
</template>

<style scoped>
.category-card--hidden {
    opacity: 0;
    transform: translateY(24px) scale(0.97);
}

.category-card--visible {
    opacity: 1;
    transform: translateY(0) scale(1);
}

.category-card:hover {
    transform: translateY(-6px) scale(1);
    box-shadow: 0 16px 32px -12px rgba(44, 62, 80, 0.15);
    border-color: rgba(26, 86, 219, 0.15);
}

.category-card--hidden:hover {
    transform: translateY(24px) scale(0.97);
}
</style>
