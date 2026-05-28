<script setup>
import { inject } from 'vue';
import { Link } from '@inertiajs/vue3';
import { whatsappUrl } from '@/config/site';
import { LEAD_CHATBOT_KEY } from '@/Composables/useLeadChatbot';

const api = inject(LEAD_CHATBOT_KEY);

defineEmits(['close']);

const dialOptions = ['+91', '+1', '+44', '+971'];
</script>

<template>
    <div
        class="flex max-h-[min(520px,85vh)] flex-col overflow-hidden rounded-2xl bg-white shadow-2xl shadow-navy/20 ring-1 ring-primary/10"
        role="dialog"
        aria-labelledby="lead-chatbot-title"
        aria-modal="true"
    >
        <div class="flex shrink-0 items-center gap-3 bg-gradient-to-r from-navy to-primary px-4 py-3.5 text-white">
            <div class="relative flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-white/15 text-lg font-bold">
                OR
                <span class="absolute bottom-0 right-0 h-3 w-3 rounded-full border-2 border-navy bg-emerald-400" aria-hidden="true" />
            </div>
            <div class="min-w-0 flex-1">
                <h2 id="lead-chatbot-title" class="truncate text-sm font-bold">{{ api.chatbotConfig.assistantName }}</h2>
                <p class="text-[11px] text-white/70">{{ api.chatbotConfig.assistantTitle }} · Online</p>
            </div>
            <button
                type="button"
                class="rounded-lg p-2 text-white/80 hover:bg-white/10 hover:text-white"
                aria-label="Close chat"
                @click="$emit('close')"
            >
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l18 18" />
                </svg>
            </button>
        </div>

        <div class="flex-1 overflow-y-auto bg-gradient-to-b from-slate-50 to-white p-4">
            <div v-if="api.chat.phase === 'success'" class="flex flex-col items-center py-6 text-center">
                <div class="mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-emerald-100 text-emerald-600">
                    <svg class="h-9 w-9" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-navy">{{ api.chatbotConfig.success.title }}</h3>
                <p class="mt-2 text-sm text-gray-600">
                    Thanks, <span class="font-semibold text-primary">{{ api.firstName }}</span>!
                    {{ api.chatbotConfig.success.subtitle }}
                </p>
                <div class="mt-6 flex w-full flex-col gap-2">
                    <Link
                        href="/properties"
                        class="inline-flex w-full items-center justify-center rounded-xl bg-primary px-4 py-3 text-sm font-bold text-white hover:bg-primary/90"
                    >
                        {{ api.chatbotConfig.success.browseLabel }}
                    </Link>
                    <a
                        :href="whatsappUrl()"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-emerald-500 px-4 py-3 text-sm font-bold text-white hover:bg-emerald-600"
                    >
                        {{ api.chatbotConfig.success.whatsappLabel }}
                    </a>
                </div>
            </div>

            <template v-else>
                <div v-if="['open', 'contact', 'preference'].includes(api.chat.phase)" class="mb-4 space-y-3">
                    <div
                        v-for="(line, i) in api.chatbotConfig.welcomeLines"
                        :key="'w' + i"
                        class="max-w-[90%] rounded-2xl rounded-tl-sm bg-white px-3.5 py-2.5 text-sm text-gray-700 shadow-sm ring-1 ring-gray-100"
                    >
                        {{ line }}
                    </div>
                    <div
                        v-if="api.intentReply && api.chat.phase !== 'open'"
                        class="max-w-[90%] rounded-2xl rounded-tl-sm bg-primary/5 px-3.5 py-2.5 text-sm text-navy ring-1 ring-primary/10"
                    >
                        {{ api.intentReply }}
                    </div>
                    <p
                        v-if="api.propertyContext?.title"
                        class="max-w-[90%] rounded-xl bg-amber-50 px-3 py-2 text-xs font-medium text-amber-900 ring-1 ring-amber-200/60"
                    >
                        Viewing: {{ api.propertyContext.title }}
                    </p>
                </div>

                <div v-if="api.chat.phase === 'open'" class="space-y-3">
                    <p class="text-xs font-bold uppercase tracking-wide text-gray-400">I am looking to</p>
                    <div class="grid grid-cols-2 gap-2">
                        <button
                            v-for="opt in api.chatbotConfig.intentOptions"
                            :key="opt.id"
                            type="button"
                            class="flex flex-col items-start rounded-xl border border-gray-200 bg-white px-3 py-3 text-left text-sm font-semibold text-navy shadow-sm transition hover:border-primary hover:ring-2 hover:ring-primary/20 active:scale-[0.98]"
                            @click="api.selectIntent(opt.id)"
                        >
                            <span class="mb-1 text-lg">{{ opt.emoji }}</span>
                            {{ opt.label }}
                        </button>
                    </div>
                    <p class="text-center text-[11px] text-gray-400">{{ api.chatbotConfig.trustLine }}</p>
                </div>

                <form v-if="api.chat.phase === 'contact'" class="space-y-3" @submit.prevent="api.goToPreference()">
                    <div class="flex items-center justify-between text-xs font-bold text-gray-500">
                        <span>Step 1 of 2</span>
                        <span class="text-primary">Your details</span>
                    </div>
                    <div class="h-1.5 w-full overflow-hidden rounded-full bg-gray-100">
                        <div class="h-full w-1/2 rounded-full bg-primary transition-all" />
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-bold text-gray-500">Full name *</label>
                        <input
                            v-model="api.chat.form.name"
                            type="text"
                            autocomplete="name"
                            class="w-full rounded-xl border border-gray-200 px-3 py-2.5 text-sm focus:border-primary focus:ring-2 focus:ring-primary/20"
                            placeholder="Your name"
                        />
                        <p v-if="api.chat.errors.name" class="mt-1 text-xs text-red-600">{{ api.chat.errors.name }}</p>
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-bold text-gray-500">Phone *</label>
                        <div class="flex gap-2">
                            <select v-model="api.chat.form.dialCode" class="w-20 rounded-xl border border-gray-200 px-2 py-2.5 text-sm">
                                <option v-for="d in dialOptions" :key="d" :value="d">{{ d }}</option>
                            </select>
                            <input
                                v-model="api.chat.form.phoneLocal"
                                type="tel"
                                autocomplete="tel"
                                class="min-w-0 flex-1 rounded-xl border border-gray-200 px-3 py-2.5 text-sm focus:border-primary focus:ring-2 focus:ring-primary/20"
                                placeholder="99906 33797"
                            />
                        </div>
                        <p v-if="api.chat.errors.phoneLocal" class="mt-1 text-xs text-red-600">{{ api.chat.errors.phoneLocal }}</p>
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-bold text-gray-500">Email <span class="font-normal text-gray-400">(optional)</span></label>
                        <input
                            v-model="api.chat.form.email"
                            type="email"
                            autocomplete="email"
                            class="w-full rounded-xl border border-gray-200 px-3 py-2.5 text-sm focus:border-primary focus:ring-2 focus:ring-primary/20"
                            placeholder="you@email.com"
                        />
                        <p v-if="api.chat.errors.email" class="mt-1 text-xs text-red-600">{{ api.chat.errors.email }}</p>
                    </div>
                    <button
                        type="submit"
                        class="w-full rounded-xl bg-primary py-3 text-sm font-bold text-white shadow-lg shadow-primary/25 hover:bg-primary/90"
                    >
                        Continue →
                    </button>
                </form>

                <form v-if="api.chat.phase === 'preference'" class="space-y-3" @submit.prevent="api.submitLead()">
                    <div class="flex items-center justify-between text-xs font-bold text-gray-500">
                        <span>Step 2 of 2</span>
                        <span class="text-primary">Preferences</span>
                    </div>
                    <div class="h-1.5 w-full overflow-hidden rounded-full bg-gray-100">
                        <div class="h-full w-full rounded-full bg-primary transition-all" />
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-bold text-gray-500">Preferred city / area</label>
                        <select
                            v-model="api.chat.form.city"
                            class="w-full rounded-xl border border-gray-200 px-3 py-2.5 text-sm focus:border-primary focus:ring-2 focus:ring-primary/20"
                        >
                            <option v-for="c in api.chatbotConfig.cityOptions" :key="c" :value="c">{{ c }}</option>
                        </select>
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-bold text-gray-500">Anything else? <span class="font-normal text-gray-400">(optional)</span></label>
                        <textarea
                            v-model="api.chat.form.note"
                            rows="2"
                            class="w-full rounded-xl border border-gray-200 px-3 py-2.5 text-sm focus:border-primary focus:ring-2 focus:ring-primary/20"
                            placeholder="Budget, BHK, sector..."
                        />
                    </div>
                    <p v-if="api.chat.submitError" class="text-xs font-medium text-red-600">{{ api.chat.submitError }}</p>
                    <button
                        type="submit"
                        :disabled="api.chat.submitting"
                        class="w-full rounded-xl bg-gradient-to-r from-primary to-navy py-3.5 text-sm font-bold text-white shadow-lg disabled:opacity-60"
                    >
                        {{ api.chat.submitting ? 'Sending...' : 'Get my free callback' }}
                    </button>
                    <p class="text-center text-[11px] text-gray-400">{{ api.chatbotConfig.trustLine }}</p>
                </form>
            </template>
        </div>
    </div>
</template>
