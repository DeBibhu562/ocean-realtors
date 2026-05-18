<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

const leads = [
    { id: 1, name: 'Vikram Singh', email: 'vikram@example.com', phone: '+91 98765 43210', property: 'Ultra Luxury 4BHK in Gurgaon', status: 'New', date: 'May 15, 2026' },
    { id: 2, name: 'Anjali Rao', email: 'anjali@example.com', phone: '+91 88888 77777', property: 'Independent Floor in Delhi', status: 'Contacted', date: 'May 14, 2026' },
    { id: 3, name: 'Siddharth M.', email: 'sid@example.com', phone: '+91 99999 00000', property: 'Modern Penthouse in Vasant Vihar', status: 'Qualified', date: 'May 14, 2026' },
];

const getStatusColor = (status) => {
    switch (status) {
        case 'New': return 'bg-blue-50 text-blue-600';
        case 'Contacted': return 'bg-amber-50 text-amber-600';
        case 'Qualified': return 'bg-green-50 text-green-600';
        default: return 'bg-gray-50 text-gray-600';
    }
};
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Leads Management" />
        
        <template #header>
            <h2 class="text-2xl font-black text-gray-900 leading-tight">Leads Management</h2>
        </template>

        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-0 overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50/50 text-xs font-bold text-gray-400 uppercase tracking-wider">
                            <th class="px-8 py-5">Customer</th>
                            <th class="px-8 py-5">Property Interest</th>
                            <th class="px-8 py-5">Status</th>
                            <th class="px-8 py-5">Date Received</th>
                            <th class="px-8 py-5 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        <tr v-for="lead in leads" :key="lead.id" class="hover:bg-gray-50/50 transition-colors group">
                            <td class="px-8 py-5">
                                <div class="font-bold text-gray-900">{{ lead.name }}</div>
                                <div class="text-xs text-gray-400 font-medium">{{ lead.email }} • {{ lead.phone }}</div>
                            </td>
                            <td class="px-8 py-5 text-sm font-medium text-gray-600">{{ lead.property }}</td>
                            <td class="px-8 py-5">
                                <span :class="getStatusColor(lead.status)" class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-tight">
                                    {{ lead.status }}
                                </span>
                            </td>
                            <td class="px-8 py-5 text-xs text-gray-400 font-bold">{{ lead.date }}</td>
                            <td class="px-8 py-5 text-right">
                                <button class="p-2 text-gray-400 hover:text-primary transition-colors"><svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg></button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
