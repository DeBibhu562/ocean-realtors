<script setup>
defineProps({
    suggestions: { type: Array, default: () => [] },
    isLoading: { type: Boolean, default: false },
    query: { type: String, default: '' },
    variant: { type: String, default: 'default' },
});

defineEmits(['select']);
</script>

<template>
    <div
        class="absolute left-0 right-0 top-full z-[500] mt-2 max-h-[min(280px,50vh)] overflow-hidden border border-gray-100 bg-white shadow-2xl"
        :class="variant === 'compact' ? 'rounded-xl py-1' : 'rounded-2xl py-2 shadow-primary/10'"
    >
        <div v-if="variant !== 'compact'" class="mb-1 border-b border-gray-50 px-4 py-2">
            <span class="text-[9px] font-black uppercase tracking-widest text-text-muted">Suggestions</span>
        </div>
        <div v-if="isLoading" class="px-4 py-3 text-xs text-text-muted">Searching locations…</div>
        <p
            v-else-if="query.trim().length >= 3 && suggestions.length === 0"
            class="px-4 py-3 text-xs text-text-muted"
        >
            No locations with listings match your search.
        </p>
        <ul v-else class="max-h-[min(240px,45vh)] divide-y divide-gray-50/50 overflow-y-auto overscroll-contain">
            <li
                v-for="s in suggestions"
                :key="`${s.type}-${s.name}-${s.city}`"
                class="flex cursor-pointer items-center px-4 transition-colors hover:bg-primary/5"
                :class="variant === 'compact' ? 'py-2.5' : 'py-3'"
                @mousedown.prevent="$emit('select', s)"
            >
                <div
                    class="mr-3 flex shrink-0 items-center justify-center rounded-lg bg-gray-50"
                    :class="variant === 'compact' ? 'h-7 w-7' : 'h-8 w-8'"
                >
                    <svg v-if="s.type === 'CITY'" class="h-4 w-4 text-text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    <svg v-else class="h-4 w-4 text-text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                    </svg>
                </div>
                <div class="min-w-0 flex-1">
                    <div class="truncate text-[12px] font-bold text-navy">{{ s.name }}</div>
                    <div class="mt-0.5 flex items-center text-[9px] font-bold uppercase tracking-tighter text-text-muted">
                        <span class="mr-1.5 text-primary/70">{{ s.type }}</span>
                        <span class="mr-1.5 h-1 w-1 rounded-full bg-gray-200" />
                        <span class="truncate">{{ s.subtitle }}</span>
                    </div>
                </div>
            </li>
        </ul>
    </div>
</template>
