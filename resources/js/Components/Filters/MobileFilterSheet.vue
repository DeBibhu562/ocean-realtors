<script setup>
import FilterSidebar from '@/Components/Filters/FilterSidebar.vue';

defineProps({
    filters: { type: Object, required: true },
    activeFilterCount: { type: Number, default: 0 },
    total: { type: Number, default: 0 },
});

const emit = defineEmits(['close', 'clear']);
</script>

<template>
    <Transition
        enter-active-class="transition duration-300 ease-out"
        enter-from-class="transform translate-y-full"
        enter-to-class="transform translate-y-0"
        leave-active-class="transition duration-200 ease-in"
        leave-from-class="transform translate-y-0"
        leave-to-class="transform translate-y-full"
    >
        <div class="fixed inset-0 z-[100] flex flex-col">
            <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" @click="emit('close')"></div>
            <div class="relative mt-auto bg-white rounded-t-2xl shadow-2xl flex flex-col max-h-[85vh]">
                <div class="px-6 py-4 border-b border-border flex items-center justify-between">
                    <h2 class="text-base font-semibold text-text-primary">Filters</h2>
                    <button type="button" class="p-2 text-text-muted hover:text-text-primary" @click="emit('close')">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l18 18" /></svg>
                    </button>
                </div>

                <div class="px-6 py-4 overflow-y-auto flex-1">
                    <FilterSidebar
                        :filters="filters"
                        :activeFilterCount="activeFilterCount"
                        @clear="emit('clear')"
                    />
                </div>

                <div class="p-6 bg-white border-t border-border">
                    <button
                        type="button"
                        class="w-full bg-primary text-white py-3 rounded-lg font-semibold text-sm"
                        @click="emit('close')"
                    >
                        Show {{ total }} Properties
                    </button>
                </div>
            </div>
        </div>
    </Transition>
</template>
