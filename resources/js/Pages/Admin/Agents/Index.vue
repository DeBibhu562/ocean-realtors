<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const props = defineProps({
    agents: { type: Array, default: () => [] },
    filters: { type: Object, default: () => ({ search: '' }) },
});

const search = ref(props.filters?.search || '');

const applySearch = () => {
    router.get(route('admin.agents.index'), { search: search.value || undefined }, { preserveState: true, replace: true });
};

const toggleStatus = (agent) => {
    router.patch(route('admin.agents.toggle', agent.id), {}, { preserveScroll: true });
};

const deleteAgent = (agent) => {
    if (!confirm(`Delete "${agent.name}"?`)) return;
    router.delete(route('admin.agents.destroy', agent.id), { preserveScroll: true });
};
</script>

<template>
    <Head title="Manage agents" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h2 class="text-2xl font-black text-gray-900">Agents</h2>
                    <p class="text-sm text-gray-500 mt-1">Manage consultant profiles and active status.</p>
                </div>
                <Link :href="route('admin.agents.create')">
                    <PrimaryButton>Add agent</PrimaryButton>
                </Link>
            </div>
        </template>

        <div class="space-y-6">
            <div class="flex items-center justify-end">
                <Link :href="route('admin.agents.create')">
                    <PrimaryButton>Add New Agent</PrimaryButton>
                </Link>
            </div>
            <form @submit.prevent="applySearch" class="flex gap-2 max-w-md">
                <input
                    v-model="search"
                    type="search"
                    placeholder="Search by name, email, city..."
                    class="flex-1 min-w-0 rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder:text-gray-400 focus:border-primary focus:ring-2 focus:ring-primary/20"
                />
                <button type="submit" class="px-4 py-2 rounded-xl bg-gray-100 text-sm font-semibold hover:bg-gray-200">Search</button>
            </form>

            <div class="hidden md:block bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <table class="w-full text-left text-sm">
                    <thead class="bg-gray-50 text-xs font-bold uppercase text-gray-400">
                        <tr>
                            <th class="px-6 py-4">Agent</th>
                            <th class="px-6 py-4">Contact</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        <tr v-for="agent in agents" :key="agent.id" class="hover:bg-gray-50/80">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <img
                                        :src="agent.avatar || `https://ui-avatars.com/api/?name=${encodeURIComponent(agent.name)}&background=1a56db&color=fff`"
                                        class="w-12 h-12 rounded-full object-cover shrink-0"
                                        alt=""
                                    />
                                    <div class="min-w-0">
                                        <p class="font-bold text-gray-900 truncate max-w-xs">{{ agent.name }}</p>
                                        <p class="text-xs text-gray-400">{{ agent.designation || 'Consultant' }} • {{ agent.city || 'N/A' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-gray-600">
                                <p>{{ agent.phone || '—' }}</p>
                                <p class="text-xs text-gray-400">{{ agent.email || '—' }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <span
                                    class="inline-flex px-2.5 py-1 rounded-full text-xs font-bold uppercase"
                                    :class="agent.is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-200 text-gray-700'"
                                >
                                    {{ agent.is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-2 flex-wrap">
                                    <button type="button" class="text-xs font-semibold text-gray-600 hover:text-primary" @click="toggleStatus(agent)">
                                        {{ agent.is_active ? 'Deactivate' : 'Activate' }}
                                    </button>
                                    <Link :href="route('admin.agents.edit', agent.id)" class="text-xs font-semibold text-primary hover:underline">Edit</Link>
                                    <button type="button" class="text-xs font-semibold text-red-500 hover:underline" @click="deleteAgent(agent)">Delete</button>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="!agents.length">
                            <td colspan="4" class="px-6 py-12 text-center text-gray-400">No agents found.</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="md:hidden space-y-3">
                <div v-for="agent in agents" :key="agent.id" class="bg-white rounded-xl border p-4 space-y-3">
                    <div class="flex items-center gap-3">
                        <img
                            :src="agent.avatar || `https://ui-avatars.com/api/?name=${encodeURIComponent(agent.name)}&background=1a56db&color=fff`"
                            class="w-10 h-10 rounded-full object-cover"
                            alt=""
                        />
                        <div class="min-w-0">
                            <p class="font-bold text-gray-900 truncate">{{ agent.name }}</p>
                            <p class="text-xs text-gray-500">{{ agent.phone || '—' }}</p>
                        </div>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <Link :href="route('admin.agents.edit', agent.id)" class="text-xs font-bold text-primary">Edit</Link>
                        <button type="button" class="text-xs font-bold text-gray-600" @click="toggleStatus(agent)">Toggle status</button>
                        <button type="button" class="text-xs font-bold text-red-500" @click="deleteAgent(agent)">Delete</button>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
