<script setup>
import AppLayout from '@/Components/AppLayout.vue';
import { Head } from '@inertiajs/vue3';

const reasons = [
    { 
        title: 'Verified Listings', 
        desc: 'Every property on our platform goes through a rigorous multi-step verification process to ensure zero fraud.',
        icon: 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04M12 21.48V11.5' 
    },
    { 
        title: 'Zero Brokerage', 
        desc: 'Connect directly with owners and developers without any hidden costs or middleman commissions.',
        icon: 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z' 
    },
    { 
        title: 'Smart Tech', 
        desc: 'Our AI-driven search helps you find properties based on your lifestyle, commute, and neighborhood preferences.',
        icon: 'M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z' 
    },
];
</script>

<template>
    <AppLayout title="Why Choose Us">
        <Head title="Why Choose Us" />
        
        <div class="bg-white min-h-screen py-24">
            <div class="container max-w-5xl mx-auto px-4 text-center">
                <span class="text-primary font-black text-xs uppercase tracking-widest mb-4 block">The Ocean Advantage</span>
                <h1 class="text-4xl md:text-5xl font-black text-gray-900 mb-8 leading-tight">Why Smart Home Seekers <br/>Choose Ocean Realtors</h1>
                <p class="text-gray-500 text-lg max-w-2xl mx-auto mb-20 leading-relaxed font-medium">We've simplified the property journey so you can focus on building your future, not managing paperwork.</p>

                <div class="grid md:grid-cols-3 gap-12 text-left">
                    <div v-for="r in reasons" :key="r.title" class="group">
                        <div class="w-14 h-14 bg-gray-50 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-primary/10 transition-colors">
                            <svg class="h-6 w-6 text-gray-400 group-hover:text-primary transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="r.icon" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-4">{{ r.title }}</h3>
                        <p class="text-gray-500 text-sm leading-relaxed">{{ r.desc }}</p>
                    </div>
                </div>

                <!-- Comparison Table (Compact) -->
                <div class="mt-24 max-w-4xl mx-auto overflow-hidden rounded-3xl border border-gray-100 shadow-2xl">
                    <table class="w-full text-left border-collapse bg-white">
                        <thead>
                            <tr class="bg-navy text-white">
                                <th class="px-8 py-6 font-black tracking-tight text-lg">Feature Comparison</th>
                                <th class="px-8 py-6 text-center text-primary font-black">Ocean Realtors</th>
                                <th class="px-8 py-6 text-center text-white/40 font-bold">Traditional Broker</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            <tr v-for="f in ['Verified Properties', 'Direct Owner Access', 'Zero Commission', 'AI Neighborhood Insights']" :key="f">
                                <td class="px-8 py-5 text-sm font-bold text-gray-700">{{ f }}</td>
                                <td class="px-8 py-5 text-center"><svg class="h-5 w-5 text-green-500 mx-auto" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg></td>
                                <td class="px-8 py-5 text-center"><svg class="h-5 w-5 text-red-300 mx-auto" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" /></svg></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
