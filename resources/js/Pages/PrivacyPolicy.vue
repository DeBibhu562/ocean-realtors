<script setup>
import AppLayout from '@/Components/AppLayout.vue';
import { Head } from '@inertiajs/vue3';

const sections = [
    { title: 'Information We Collect', content: 'We collect information you provide directly to us when you create an account, search for properties, or communicate with agents.' },
    { title: 'How We Use Your Data', content: 'We use your data to personalize your property search, facilitate transactions, and improve our platform algorithms.' },
    { title: 'Data Sharing & Security', content: 'Your data is encrypted and only shared with verified property owners or agents upon your explicit request.' },
];
</script>

<template>
    <AppLayout title="Privacy Policy">
        <Head title="Privacy Policy" />
        
        <div class="bg-gray-50 min-h-screen py-24">
            <div class="container max-w-3xl mx-auto px-4 bg-white p-12 rounded-3xl shadow-xl border border-gray-100">
                <h1 class="text-4xl font-black text-gray-900 mb-4">Privacy Policy</h1>
                <p class="text-gray-400 text-sm mb-12">Last Updated: May 15, 2026</p>

                <div class="space-y-12">
                    <section v-for="s in sections" :key="s.title">
                        <h2 class="text-xl font-bold text-gray-900 mb-4">{{ s.title }}</h2>
                        <p class="text-gray-600 leading-relaxed text-sm">{{ s.content }}</p>
                    </section>

                    <div class="pt-12 border-t border-gray-100">
                        <h2 class="text-xl font-bold text-gray-900 mb-4">Contact Our DPO</h2>
                        <p class="text-gray-600 text-sm mb-6">If you have any questions about your data, please contact our Data Protection Officer at:</p>
                        <a href="mailto:privacy@oceanrealtors.com" class="text-primary font-bold hover:underline">privacy@oceanrealtors.com</a>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
