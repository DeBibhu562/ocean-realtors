<script setup>
defineProps({
    title: { type: String, required: true },
    subtitle: { type: String, default: '' },
    badge: { type: String, default: '' },
    image: {
        type: String,
        default: 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?auto=format&fit=crop&q=80&w=1600',
    },
});
</script>

<template>
    <section class="relative pt-20 pb-10 md:pt-32 md:pb-20 overflow-hidden bg-navy">
        <div
            class="absolute inset-0 bg-cover bg-center opacity-25"
            :style="{ backgroundImage: `url('${image}')` }"
            aria-hidden="true"
        />
        <div class="absolute inset-0 bg-gradient-to-b from-navy/80 via-navy/90 to-navy" aria-hidden="true" />
        <div class="container max-w-4xl mx-auto px-3 md:px-4 relative z-10 text-center">
            <p v-if="badge" class="text-[10px] md:text-xs font-bold uppercase tracking-[0.2em] text-primary mb-2 md:mb-3">{{ badge }}</p>
            <h1 class="text-2xl md:text-5xl font-black text-white leading-tight">{{ title }}</h1>
            <p v-if="subtitle" class="mt-2 md:mt-4 text-sm md:text-lg text-white/70 max-w-2xl mx-auto leading-relaxed">
                {{ subtitle }}
            </p>
            <slot />
        </div>
    </section>
</template>
