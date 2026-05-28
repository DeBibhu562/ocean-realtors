<script setup>
import { ref, watch } from 'vue';

const props = defineProps({
    slide: { type: Object, required: true },
    greeting: { type: String, default: 'Hi there! 👋' },
    agentName: { type: String, required: true },
    agentLabel: { type: String, default: '' },
    visible: { type: Boolean, default: false },
});

const emit = defineEmits(['open', 'dismiss']);

const typing = ref(false);

watch(
    () => props.slide?.headline,
    () => {
        typing.value = true;
        const t = setTimeout(() => {
            typing.value = false;
        }, 600);
        return () => clearTimeout(t);
    },
);
</script>

<template>
    <Transition
        enter-active-class="transition duration-500 ease-out"
        enter-from-class="opacity-0 translate-y-6 scale-90"
        enter-to-class="opacity-100 translate-y-0 scale-100"
        leave-active-class="transition duration-250 ease-in"
        leave-from-class="opacity-100 translate-y-0 scale-100"
        leave-to-class="opacity-0 translate-y-4 scale-95"
    >
        <div
            v-if="visible"
            class="absolute bottom-full left-0 z-10 mb-4 w-[min(320px,calc(100vw-2.5rem))] origin-bottom-left"
        >
            <!-- Launcher connector tail -->
            <span
                class="absolute -bottom-2 left-7 h-4 w-4 rotate-45 bg-white shadow-sm ring-1 ring-primary/10"
                aria-hidden="true"
            />

            <div
                role="button"
                tabindex="0"
                class="group relative cursor-pointer overflow-hidden rounded-2xl bg-white shadow-2xl shadow-navy/20 ring-1 ring-primary/15 transition hover:shadow-primary/25 hover:ring-primary/30 focus:outline-none focus-visible:ring-2 focus-visible:ring-primary"
                @click="emit('open')"
                @keydown.enter="emit('open')"
                @keydown.space.prevent="emit('open')"
            >
                <!-- Accent top bar -->
                <div class="h-1 bg-gradient-to-r from-primary via-blue-500 to-accent" />

                <button
                    type="button"
                    class="absolute right-2 top-3 z-10 flex h-7 w-7 items-center justify-center rounded-full bg-white/90 text-gray-400 shadow-sm ring-1 ring-gray-100 hover:bg-gray-50 hover:text-gray-600"
                    aria-label="Dismiss message"
                    @click.stop="emit('dismiss')"
                >
                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

                <div class="flex items-center gap-3 border-b border-gray-100 bg-gradient-to-r from-slate-50 to-primary/5 px-4 py-3">
                    <div class="relative shrink-0">
                        <div
                            class="flex h-11 w-11 items-center justify-center rounded-full bg-gradient-to-br from-navy to-primary text-sm font-bold text-white shadow-md"
                        >
                            OR
                        </div>
                        <span
                            class="absolute -bottom-0.5 -right-0.5 h-3.5 w-3.5 rounded-full border-2 border-white bg-emerald-500"
                            aria-hidden="true"
                        />
                        <span
                            class="absolute -bottom-0.5 -right-0.5 flex h-3.5 w-3.5 animate-ping rounded-full bg-emerald-400 opacity-60"
                            aria-hidden="true"
                        />
                    </div>
                    <div class="min-w-0 flex-1 pr-6">
                        <p class="truncate text-sm font-bold text-navy">{{ agentName }}</p>
                        <p class="flex items-center gap-1.5 text-[11px] font-medium text-emerald-600">
                            <span class="inline-block h-1.5 w-1.5 rounded-full bg-emerald-500" />
                            {{ agentLabel }}
                        </p>
                    </div>
                </div>

                <div class="px-4 pb-4 pt-3">
                    <p class="text-sm font-semibold text-primary">{{ greeting }}</p>

                    <div class="mt-2 min-h-[4.5rem]">
                        <Transition
                            mode="out-in"
                            enter-active-class="transition duration-300 ease-out"
                            enter-from-class="opacity-0 translate-y-1"
                            enter-to-class="opacity-100 translate-y-0"
                            leave-active-class="transition duration-200 ease-in"
                            leave-from-class="opacity-100"
                            leave-to-class="opacity-0"
                        >
                            <div :key="slide.headline">
                                <p v-if="typing" class="flex items-center gap-1 py-2">
                                    <span
                                        v-for="i in 3"
                                        :key="i"
                                        class="h-2 w-2 animate-bounce rounded-full bg-gray-300"
                                        :style="{ animationDelay: `${(i - 1) * 150}ms` }"
                                    />
                                </p>
                                <template v-else>
                                    <p class="text-[15px] font-bold leading-snug text-navy">
                                        {{ slide.headline }}
                                    </p>
                                    <p class="mt-1.5 text-sm leading-relaxed text-gray-600">
                                        {{ slide.message }}
                                    </p>
                                </template>
                            </div>
                        </Transition>
                    </div>

                    <button
                        type="button"
                        class="mt-3 flex w-full items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-primary to-navy px-4 py-3 text-sm font-bold text-white shadow-lg shadow-primary/30 transition group-hover:scale-[1.02] active:scale-[0.98]"
                        @click.stop="emit('open')"
                    >
                        {{ slide.cta }}
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                        </svg>
                    </button>

                    <p class="mt-2 text-center text-[10px] text-gray-400">
                        Tap anywhere · Free · Reply in minutes
                    </p>
                </div>
            </div>
        </div>
    </Transition>
</template>
