<script setup>
import AppLayout from '@/Components/AppLayout.vue';
import { Head } from '@inertiajs/vue3';

const values = [
    { title: 'Integrity', desc: 'We operate with absolute transparency and honesty in every transaction.' },
    { title: 'Innovation', desc: 'Leveraging cutting-edge technology to simplify the property search journey.' },
    { title: 'Excellence', desc: 'Committed to delivering world-class service to buyers, sellers, and agents.' },
];
</script>

<template>
    <AppLayout title="Mission & Vision">
        <Head title="Mission & Vision" />
        
        <div class="bg-white min-h-screen">
            <!-- Hero Section -->
            <section class="relative bg-navy py-24 overflow-hidden">
                <div class="absolute inset-0 opacity-10">
                    <div class="absolute top-0 right-0 w-1/2 h-full bg-no-repeat bg-contain bg-right-bottom" style="background-image: url('https://housing.com/assets/cityscape-light.5f1f91b7.svg')"></div>
                </div>
                <div class="container max-w-4xl mx-auto px-4 relative z-10 text-center">
                    <h1 class="text-4xl md:text-5xl font-black text-white mb-6 leading-tight">Our Mission & Vision</h1>
                    <p class="text-xl text-white/70 max-w-2xl mx-auto font-medium">Redefining the future of real estate through trust, technology, and transparency.</p>
                </div>
            </section>

            <!-- Mission & Vision Cards -->
            <section class="py-20 -mt-16 relative z-20">
                <div class="container max-w-5xl mx-auto px-4">
                    <div class="grid md:grid-cols-2 gap-8">
                        <div class="bg-white p-10 rounded-3xl shadow-xl border border-gray-100 group hover:border-primary transition-all duration-500">
                            <div class="w-16 h-16 bg-primary/10 rounded-2xl flex items-center justify-center mb-8 group-hover:scale-110 transition-transform">
                                <svg class="h-8 w-8 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                            </div>
                            <h2 class="text-3xl font-black text-gray-900 mb-4">Our Mission</h2>
                            <p class="text-gray-600 leading-relaxed text-lg font-medium italic">
                                "To empower every individual with the data and tools they need to find their perfect home with absolute confidence and ease."
                            </p>
                        </div>

                        <div class="bg-white p-10 rounded-3xl shadow-xl border border-gray-100 group hover:border-primary transition-all duration-500">
                            <div class="w-16 h-16 bg-accent/10 rounded-2xl flex items-center justify-center mb-8 group-hover:scale-110 transition-transform">
                                <svg class="h-8 w-8 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                            </div>
                            <h2 class="text-3xl font-black text-gray-900 mb-4">Our Vision</h2>
                            <p class="text-gray-600 leading-relaxed text-lg font-medium italic">
                                "To become the world's most trusted real estate ecosystem, connecting dreams with reality through seamless human-centric innovation."
                            </p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Core Values -->
            <section class="py-20 bg-gray-50">
                <div class="container max-w-5xl mx-auto px-4 text-center">
                    <h2 class="text-3xl font-black text-gray-900 mb-12">Core Values</h2>
                    <div class="grid md:grid-cols-3 gap-8">
                        <div v-for="v in values" :key="v.title" class="p-8 bg-white rounded-2xl shadow-sm border border-gray-100">
                            <h3 class="text-xl font-bold text-gray-900 mb-3">{{ v.title }}</h3>
                            <p class="text-gray-500 text-sm leading-relaxed">{{ v.desc }}</p>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </AppLayout>
</template>
