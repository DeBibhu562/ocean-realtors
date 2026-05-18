<script setup>
const props = defineProps({
    total: Number,
    view: { type: String, default: 'grid' },
    sort: String
});

const emit = defineEmits(['update:view', 'update:sort']);

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

            <!-- View Toggle -->
            <div class="flex bg-gray-50 p-1 rounded-md border border-border">
                <button 
                    @click="emit('update:view', 'grid')"
                    class="p-1.5 rounded transition-all"
                    :class="view === 'grid' ? 'bg-white text-primary shadow-sm' : 'text-text-muted hover:text-text-primary'"
                    title="Grid View"
                >
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                    </svg>
                </button>
                <button 
                    @click="emit('update:view', 'list')"
                    class="p-1.5 rounded transition-all"
                    :class="view === 'list' ? 'bg-white text-primary shadow-sm' : 'text-text-muted hover:text-text-primary'"
                    title="List View"
                >
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
</template>
