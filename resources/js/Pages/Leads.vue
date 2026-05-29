<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

const props = defineProps({
    leads: {
        type: Array,
        default: () => [],
    },
});

const expandedId = ref(null);

const getStatusColor = (status) => {
    const key = (status || '').toLowerCase();
    switch (key) {
        case 'new':
            return 'bg-blue-50 text-blue-700';
        case 'contacted':
            return 'bg-amber-50 text-amber-700';
        case 'qualified':
            return 'bg-emerald-50 text-emerald-700';
        default:
            return 'bg-gray-50 text-gray-600';
    }
};

const channelLabel = (channel) => {
    switch (channel) {
        case 'call':
            return 'Call';
        case 'whatsapp':
            return 'WhatsApp';
        case 'view_phone':
            return 'View number';
        default:
            return channel ? String(channel) : '—';
    }
};

const agentBadge = (value) => {
    if (value === true) return { text: 'Agent: Yes', class: 'bg-violet-50 text-violet-700' };
    if (value === false) return { text: 'Agent: No', class: 'bg-slate-100 text-slate-600' };
    return null;
};

const totalLeads = computed(() => props.leads.length);
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Leads Management" />

        <template #header>
            <div class="flex flex-col gap-1 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <h2 class="text-2xl font-black leading-tight text-gray-900">Leads Management</h2>
                    <p class="mt-1 text-sm text-gray-500">{{ totalLeads }} enquiries from website &amp; property contacts</p>
                </div>
            </div>
        </template>

        <div v-if="leads.length === 0" class="rounded-3xl border border-gray-100 bg-white px-8 py-16 text-center shadow-sm">
            <p class="text-lg font-bold text-gray-800">No leads yet</p>
            <p class="mt-2 text-sm text-gray-500">Leads from property cards and enquiry forms will appear here.</p>
        </div>

        <div v-else class="overflow-hidden rounded-3xl border border-gray-100 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full min-w-[900px] border-collapse text-left">
                    <thead>
                        <tr class="bg-gray-50/80 text-xs font-bold uppercase tracking-wider text-gray-400">
                            <th class="px-6 py-4">Customer</th>
                            <th class="px-6 py-4">Property</th>
                            <th class="px-6 py-4">Channel</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4">Received</th>
                            <th class="px-6 py-4 text-right">Details</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        <template v-for="lead in leads" :key="lead.id">
                            <tr class="transition-colors hover:bg-gray-50/60">
                                <td class="px-6 py-4">
                                    <div class="font-bold text-gray-900">{{ lead.name }}</div>
                                    <div class="mt-0.5 text-xs text-gray-500">{{ lead.email }}</div>
                                    <div class="text-xs font-medium text-gray-600">{{ lead.phone }}</div>
                                    <span
                                        v-if="agentBadge(lead.is_real_estate_agent)"
                                        class="mt-2 inline-block rounded-full px-2 py-0.5 text-[10px] font-bold uppercase"
                                        :class="agentBadge(lead.is_real_estate_agent).class"
                                    >
                                        {{ agentBadge(lead.is_real_estate_agent).text }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="max-w-[220px] text-sm font-medium text-gray-700">{{ lead.property }}</div>
                                    <div v-if="lead.agent" class="mt-1 text-xs text-gray-400">Agent: {{ lead.agent }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="rounded-full bg-primary/10 px-2.5 py-1 text-[10px] font-bold uppercase text-primary">
                                        {{ channelLabel(lead.contact_channel) }}
                                    </span>
                                    <div v-if="lead.source" class="mt-1 text-[10px] font-semibold uppercase text-gray-400">
                                        {{ lead.source }}
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        :class="getStatusColor(lead.status)"
                                        class="rounded-full px-3 py-1 text-[10px] font-black uppercase tracking-tight"
                                    >
                                        {{ lead.status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-xs font-semibold text-gray-500">{{ lead.date }}</td>
                                <td class="px-6 py-4 text-right">
                                    <button
                                        v-if="lead.message"
                                        type="button"
                                        class="text-xs font-bold text-primary hover:underline"
                                        @click="expandedId = expandedId === lead.id ? null : lead.id"
                                    >
                                        {{ expandedId === lead.id ? 'Hide' : 'View' }}
                                    </button>
                                </td>
                            </tr>
                            <tr v-if="expandedId === lead.id && lead.message" :key="`${lead.id}-msg`">
                                <td colspan="6" class="bg-gray-50/80 px-6 py-4">
                                    <p class="whitespace-pre-wrap text-sm leading-relaxed text-gray-600">{{ lead.message }}</p>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
