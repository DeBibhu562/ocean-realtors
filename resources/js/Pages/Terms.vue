<script setup>
import AppLayout from '@/Components/AppLayout.vue';
import { Head } from '@inertiajs/vue3';

const terms = [
    { title: 'User Accounts', content: 'You must be at least 18 years old to use this platform. You are responsible for maintaining the confidentiality of your account credentials.' },
    { title: 'Property Listings', content: 'Ocean Realtors does not guarantee the accuracy of every listing, though we verify most. Users are advised to perform their own due diligence.' },
    { title: 'Platform Usage', content: 'Any unauthorized scraping, data mining, or malicious activity on this platform will result in an immediate permanent ban and potential legal action.' },
];
</script>

<template>
    <AppLayout title="Terms & Conditions">
        <Head title="Terms & Conditions" />
        
        <div class="bg-gray-50 min-h-screen py-24">
            <div class="container max-w-3xl mx-auto px-4 bg-white p-12 rounded-3xl shadow-xl border border-gray-100">
                <h1 class="text-4xl font-black text-gray-900 mb-4">Terms & Conditions</h1>
                <p class="text-gray-400 text-sm mb-12">Agreement effective as of May 15, 2026</p>

                <div class="space-y-12">
                    <section v-for="t in terms" :key="t.title">
                        <h2 class="text-xl font-bold text-gray-900 mb-4">{{ t.title }}</h2>
                        <p class="text-gray-600 leading-relaxed text-sm">{{ t.content }}</p>
                    </section>

                    <div class="pt-12 border-t border-gray-100">
                        <p class="text-gray-400 text-xs leading-relaxed italic">By using Ocean Realtors, you agree to abide by these terms in full. If you do not agree with any part of these terms, please discontinue use of the platform immediately.</p>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
