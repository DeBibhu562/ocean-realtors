<script setup>
import AppLayout from '@/Components/AppLayout.vue';
import { Head } from '@inertiajs/vue3';

const jobs = [
    { title: 'Senior UX Designer', dept: 'Design', type: 'Full-time', loc: 'Remote / Gurgaon' },
    { title: 'Backend Engineer (Laravel)', dept: 'Engineering', type: 'Full-time', loc: 'Gurgaon' },
    { title: 'Real Estate Analyst', dept: 'Strategy', type: 'Contract', loc: 'Mumbai' },
];
</script>

<template>
    <AppLayout title="Join Our Team">
        <Head title="Careers" />
        
        <div class="bg-white min-h-screen">
            <!-- Hero -->
            <section class="bg-navy py-32 text-center px-4">
                <div class="max-w-3xl mx-auto">
                    <span class="text-primary font-black text-xs uppercase tracking-widest mb-6 block">We're Hiring</span>
                    <h1 class="text-4xl md:text-6xl font-black text-white mb-8">Build the Future of <br/>Real Estate with Us</h1>
                    <p class="text-xl text-white/50 max-w-2xl mx-auto font-medium leading-relaxed">Join a team of visionaries, engineers, and designers working to redefine how the world finds home.</p>
                </div>
            </section>

            <!-- Open Positions -->
            <section class="py-24 bg-gray-50">
                <div class="container max-w-4xl mx-auto px-4">
                    <h2 class="text-3xl font-black text-gray-900 mb-12 text-center">Open Positions</h2>
                    
                    <div class="space-y-4">
                        <div v-for="j in jobs" :key="j.title" class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex flex-col md:flex-row items-center justify-between gap-6 hover:border-primary transition-colors group">
                            <div>
                                <h3 class="text-xl font-bold text-gray-900 mb-1">{{ j.title }}</h3>
                                <div class="flex items-center space-x-3 text-xs font-bold text-gray-400 uppercase tracking-tight">
                                    <span>{{ j.dept }}</span>
                                    <span>•</span>
                                    <span>{{ j.type }}</span>
                                    <span>•</span>
                                    <span>{{ j.loc }}</span>
                                </div>
                            </div>
                            <button class="w-full md:w-auto bg-primary text-white px-8 py-3 rounded-xl font-black uppercase tracking-widest text-[10px] shadow-lg shadow-primary/20 group-hover:scale-105 transition-transform">Apply Now</button>
                        </div>
                    </div>

                    <div class="mt-20 text-center">
                        <p class="text-gray-500 font-medium">Don't see a role that fits? Send us your CV at <br/> <a href="mailto:careers@oceanrealtors.com" class="text-primary font-black hover:underline mt-2 inline-block">careers@oceanrealtors.com</a></p>
                    </div>
                </div>
            </section>
        </div>
    </AppLayout>
</template>
