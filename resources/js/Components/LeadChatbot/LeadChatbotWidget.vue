<script setup>
import { onMounted, onUnmounted, provide, watch } from 'vue';
import { LEAD_CHATBOT_KEY, useLeadChatbot } from '@/Composables/useLeadChatbot';
import LeadChatbotTeaser from './LeadChatbotTeaser.vue';
import LeadChatbotPanel from './LeadChatbotPanel.vue';
const api = useLeadChatbot();
provide(LEAD_CHATBOT_KEY, api);
const {
    chat,
    isVisible,
    isPanelOpen,
    currentTeaserSlide,
    startTeaser,
    stopTimers,
    openPanel,
    closePanel,
    dismissTeaser,
} = api;

const onKeydown = (e) => {
    if (e.key === 'Escape' && isPanelOpen.value) {
        closePanel();
    }
};

onMounted(() => {
    window.addEventListener('keydown', onKeydown);
    if (isVisible.value) {
        startTeaser();
    }
});

onUnmounted(() => {
    window.removeEventListener('keydown', onKeydown);
    stopTimers();
});

watch(isVisible, (visible) => {
    if (visible) {
        startTeaser();
    } else {
        stopTimers();
        chat.showTeaser = false;
        chat.phase = 'idle';
    }
});
</script>

<template>
    <div
        v-if="isVisible"
        class="fixed bottom-5 left-4 z-[190] sm:bottom-6 sm:left-6 pointer-events-none"
    >
        <div class="pointer-events-auto relative">
            <Transition
                enter-active-class="transition duration-300 ease-out"
                enter-from-class="opacity-0 translate-y-4 scale-95"
                enter-to-class="opacity-100 translate-y-0 scale-100"
                leave-active-class="transition duration-200 ease-in"
                leave-from-class="opacity-100 translate-y-0 scale-100"
                leave-to-class="opacity-0 translate-y-4 scale-95"
            >
                <div
                    v-if="isPanelOpen"
                    class="absolute bottom-full left-0 mb-3 w-[min(360px,calc(100vw-2rem))]"
                >
                    <LeadChatbotPanel @close="closePanel" />
                </div>
            </Transition>

            <LeadChatbotTeaser
                :slide="currentTeaserSlide"
                :greeting="api.chatbotConfig.teaserGreeting"
                :agent-name="api.chatbotConfig.assistantName"
                :agent-label="api.chatbotConfig.teaserAgentLabel"
                :visible="!isPanelOpen && chat.showTeaser"
                @open="openPanel"
                @dismiss="dismissTeaser"
            />

            <button
                type="button"
                class="group relative flex h-14 w-14 items-center justify-center rounded-full bg-gradient-to-br from-primary to-navy text-white shadow-lg shadow-primary/30 transition hover:scale-105 hover:shadow-xl focus:outline-none focus-visible:ring-2 focus-visible:ring-primary focus-visible:ring-offset-2 motion-reduce:transition-none"
                :class="{ 'animate-pulse ring-4 ring-primary/20': !isPanelOpen && chat.showTeaser }"
                aria-label="Open property assistant chat"
                :aria-expanded="isPanelOpen"
                @click="openPanel"
            >
                <span
                    v-if="!isPanelOpen && chat.showTeaser"
                    class="absolute -right-0.5 -top-0.5 h-3.5 w-3.5 rounded-full border-2 border-white bg-accent"
                    aria-hidden="true"
                />
                <svg
                    v-if="!isPanelOpen"
                    class="h-7 w-7"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                    aria-hidden="true"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"
                    />
                </svg>
                <svg v-else class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>
        </div>
    </div>
</template>
