<script setup>
const props = defineProps({
    total: Number,
    view: { type: String, default: 'grid' },
    sort: String
});

const emit = defineEmits(['update:sort']);

const sortOptions = [
    { label: 'Newest First', value: 'newest' },
    { label: 'Price: Low to High', value: 'price_asc' },
    { label: 'Price: High to Low', value: 'price_desc' },
];
</script>

<template>
    <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-4 gap-3 bg-white p-3 rounded-lg border border-border shadow-sm">
        <div>
            <p class="text-sm font-semibold text-text-primary">
                {{ total }} <span class="text-text-secondary font-normal">Properties Found</span>
            </p>
        </div>

        <div class="flex items-center gap-3">
            <!-- Sort -->
            <div class="relative">
                <select 
                    :value="sort"
                    @change="emit('update:sort', $event.target.value)"
                    class="h-9 min-w-[160px] pl-3 pr-8 text-sm font-medium text-text-primary border-border rounded-md focus:ring-primary focus:border-primary appearance-none bg-white"
                >
                    <option v-for="opt in sortOptions" :key="opt.value" :value="opt.value">
                        {{ opt.label }}
                    </option>
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-2.5 flex items-center text-text-muted">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                </div>
            </div>

            <div class="inline-flex items-center gap-1 rounded-md border border-border bg-gray-50 px-2 py-1 text-[11px] font-semibold text-text-muted">
                <svg class="h-4 w-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                </svg>
                Grid view
            </div>
        </div>
    </div>
</template>
