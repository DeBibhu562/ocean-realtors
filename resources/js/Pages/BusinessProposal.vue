<script setup>
import AppLayout from '@/Components/AppLayout.vue';
import { Head } from '@inertiajs/vue3';

const steps = [
    { title: 'Digital Transformation', desc: 'Migrate your offline inventory to our high-performance digital ecosystem.' },
    { title: 'Targeted Outreach', desc: 'Reach thousands of verified buyers through our AI-driven matching algorithms.' },
    { title: 'Lead Automation', desc: 'Receive real-time notifications and manage leads through a centralized dashboard.' },
];
</script>

<template>
    <AppLayout title="Business Proposal">
        <Head title="Business Proposal" />
        
        <div class="bg-white min-h-screen">
            <!-- Hero -->
            <section class="bg-primary py-24 text-center px-4 overflow-hidden relative">
                <div class="absolute inset-0 opacity-10 bg-[radial-gradient(circle_at_center,_var(--tw-gradient-stops))] from-white via-transparent to-transparent"></div>
                <div class="max-w-3xl mx-auto relative z-10">
                    <span class="bg-white/20 text-white px-4 py-1 rounded-full text-xs font-bold uppercase tracking-widest mb-6 inline-block">Partner with Excellence</span>
                    <h1 class="text-4xl md:text-6xl font-black text-white mb-6 leading-tight">Elevate Your Real Estate Business</h1>
                    <p class="text-xl text-white/80 font-medium">Ocean Realtors provides the technology, reach, and trust to scale your agency to new heights.</p>
                </div>
            </section>

            <!-- Proposal Details -->
            <section class="py-24">
                <div class="container max-w-5xl mx-auto px-4">
                    <div class="grid md:grid-cols-2 gap-16 items-center">
                        <div class="space-y-8">
                            <h2 class="text-3xl font-black text-gray-900">Why Partner with Ocean?</h2>
                            <p class="text-gray-500 leading-relaxed font-medium">We aren't just a listing portal; we are a growth engine for developers and agencies. Our platform handles the complexity of lead qualification, media optimization, and search visibility so you can focus on closing deals.</p>
                            
                            <div class="space-y-6">
                                <div v-for="s in steps" :key="s.title" class="flex items-start space-x-4">
                                    <div class="w-10 h-10 bg-primary/10 rounded-xl flex items-center justify-center shrink-0">
                                        <svg class="h-5 w-5 text-primary" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-gray-900">{{ s.title }}</h4>
                                        <p class="text-sm text-gray-400">{{ s.desc }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-navy rounded-3xl p-10 text-white shadow-2xl relative overflow-hidden">
                            <div class="absolute top-0 right-0 w-32 h-32 bg-primary/20 blur-3xl rounded-full -mr-16 -mt-16"></div>
                            <h3 class="text-2xl font-black mb-6">Request a Proposal</h3>
                            <form class="space-y-4">
                                <div><input type="text" placeholder="Full Name" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-sm focus:ring-primary focus:border-primary" /></div>
                                <div><input type="email" placeholder="Business Email" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-sm focus:ring-primary focus:border-primary" /></div>
                                <div><input type="text" placeholder="Company Name" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-sm focus:ring-primary focus:border-primary" /></div>
                                <div><textarea placeholder="Tell us about your inventory..." class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-sm h-32 focus:ring-primary focus:border-primary"></textarea></div>
                                <button class="w-full bg-primary hover:bg-primary-hover text-white py-4 rounded-xl font-black uppercase tracking-widest text-xs transition-all active:scale-95 shadow-xl shadow-primary/20">Submit Request</button>
                            </form>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </AppLayout>
</template>
