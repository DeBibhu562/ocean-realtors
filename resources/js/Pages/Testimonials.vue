<script setup>
import AppLayout from '@/Components/AppLayout.vue';
import { Head } from '@inertiajs/vue3';

const testimonials = [
    { name: 'Rahul Sharma', role: 'Home Buyer', content: 'Ocean Realtors made my home search so easy. The verification process gave me peace of mind.', rating: 5 },
    { name: 'Priya Verma', role: 'Property Owner', content: 'Listing my apartment was seamless. I got verified leads within 24 hours without any brokerage.', rating: 5 },
    { name: 'Amit Patel', role: 'Investor', content: 'The analytics and neighborhood insights are world-class. Truly a tech-first platform.', rating: 4 },
];
</script>

<template>
    <AppLayout title="Testimonials">
        <Head title="Testimonials" />
        
        <div class="bg-white min-h-screen py-24">
            <div class="container max-w-5xl mx-auto px-4 text-center">
                <span class="text-primary font-black text-xs uppercase tracking-widest mb-4 block">Client Success Stories</span>
                <h1 class="text-4xl md:text-5xl font-black text-gray-900 mb-16 leading-tight">What Our Clients Say</h1>

                <div class="grid md:grid-cols-3 gap-8 text-left">
                    <div v-for="t in testimonials" :key="t.name" class="bg-gray-50 p-8 rounded-3xl border border-gray-100 hover:bg-white hover:shadow-2xl transition-all duration-500 group">
                        <div class="flex text-amber-400 mb-6">
                            <svg v-for="i in 5" :key="i" class="h-5 w-5 fill-current" :class="i <= t.rating ? 'opacity-100' : 'opacity-20'" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>
                        </div>
                        <p class="text-gray-600 leading-relaxed italic mb-8">"{{ t.content }}"</p>
                        <div class="flex items-center space-x-4 pt-6 border-t border-gray-100">
                            <div class="w-12 h-12 rounded-full bg-primary/10 flex items-center justify-center text-primary font-black uppercase tracking-tighter">
                                {{ t.name.split(' ').map(n => n[0]).join('') }}
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-900">{{ t.name }}</h4>
                                <p class="text-xs text-gray-400 font-bold uppercase">{{ t.role }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
