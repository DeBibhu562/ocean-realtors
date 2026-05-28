<script setup>
defineProps({
    agentName: { type: String, default: '' },
    agentDesignation: { type: String, default: '' },
    agentAvatar: { type: String, default: '' },
});
</script>

<template>
    <div class="flex items-center gap-2 bg-slate-800 px-3 py-2">
        <img
            :src="agentAvatar || `https://ui-avatars.com/api/?name=${encodeURIComponent(agentName || 'Ocean Realtors')}&background=1a56db&color=fff`"
            :alt="agentName || 'Ocean Realtors'"
            class="h-7 w-7 shrink-0 rounded-full bg-white object-contain p-0.5"
            width="28"
            height="28"
            loading="lazy"
        />
        <span class="truncate text-sm font-semibold text-white">{{ agentName || 'Ocean Realtors Team' }}</span>
        <span
            v-if="agentDesignation"
            class="ml-auto max-w-[45%] truncate text-[11px] font-semibold text-white/80"
        >
            {{ agentDesignation }}
        </span>
    </div>
</template>
