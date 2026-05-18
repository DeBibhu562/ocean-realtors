<script setup>
import AppLayout from '@/Components/AppLayout.vue';
import { Head } from '@inertiajs/vue3';

const adOptions = [
    { title: 'Premium Listings', desc: 'Pin your property to the top of search results for maximum visibility.', price: 'Starts at ₹999' },
    { title: 'Banner Ads', desc: 'Display your brand across our high-traffic home and listing pages.', price: 'Starts at ₹4,999' },
    { title: 'Social Integration', desc: 'Feature your project on our social media handles and newsletters.', price: 'Starts at ₹2,499' },
];
</script>

<template>
    <AppLayout title="Advertisements">
        <Head title="Advertisements" />
        
        <div class="bg-gray-50 min-h-screen py-24">
            <div class="container max-w-5xl mx-auto px-4 text-center">
                <h1 class="text-4xl md:text-5xl font-black text-gray-900 mb-8">Reach Your Target Audience</h1>
                <p class="text-gray-500 text-lg max-w-2xl mx-auto mb-16 leading-relaxed">Advertise with Ocean Realtors and get your property in front of thousands of high-intent buyers every day.</p>

                <div class="grid md:grid-cols-3 gap-8">
                    <div v-for="ad in adOptions" :key="ad.title" class="bg-white p-8 rounded-3xl shadow-xl border border-gray-100 flex flex-col justify-between group hover:-translate-y-2 transition-all duration-300">
                        <div>
                            <div class="w-12 h-12 bg-primary/10 rounded-xl flex items-center justify-center mb-6 mx-auto">
                                <svg class="h-6 w-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" /></svg>
                            </div>
                            <h3 class="text-xl font-black text-gray-900 mb-4">{{ ad.title }}</h3>
                            <p class="text-gray-500 text-sm leading-relaxed mb-6">{{ ad.desc }}</p>
                        </div>
                        <div class="pt-6 border-t border-gray-50">
                            <div class="text-primary font-black text-lg mb-4">{{ ad.price }}</div>
                            <button class="w-full py-3 rounded-xl border-2 border-primary text-primary font-bold hover:bg-primary hover:text-white transition-all">Get Started</button>
                        </div>
                    </div>
                </div>

                <div class="mt-20 p-12 bg-navy rounded-[3rem] text-white flex flex-col md:flex-row items-center justify-between gap-8">
                    <div class="text-left">
                        <h2 class="text-3xl font-black mb-2">Custom Media Packages</h2>
                        <p class="text-white/60">Need something more tailored? Contact our sales team for custom enterprise solutions.</p>
                    </div>
                    <button class="bg-white text-navy px-10 py-4 rounded-2xl font-black uppercase tracking-widest text-xs hover:bg-primary hover:text-white transition-all shadow-xl">Contact Sales</button>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
