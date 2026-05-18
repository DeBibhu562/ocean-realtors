<script setup>
import { Head } from '@inertiajs/vue3';
import { AppLayout, BaseButton } from '@/Components';
import StatsSection from '@/Components/Home/StatsSection.vue';
import { useSeoMeta } from '@/Composables/useSeoMeta';

const { getMeta } = useSeoMeta();
const seo = getMeta({
    title: 'About Us',
    description: 'Learn more about Ocean Realtors, our mission, our values, and the expert team behind our success.'
});

const team = [
    { name: 'David Wilson', role: 'CEO & Founder', image: 'https://images.unsplash.com/photo-1560250097-0b93528c311a?auto=format&fit=crop&q=80&w=400', linkedin: '#' },
    { name: 'Sarah Jenkins', role: 'Director of Sales', image: 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&q=80&w=400', linkedin: '#' },
    { name: 'Michael Chen', role: 'Chief Architect', image: 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?auto=format&fit=crop&q=80&w=400', linkedin: '#' },
    { name: 'Priya Patel', role: 'Lead Agent', image: 'https://images.unsplash.com/photo-1580489944761-15a19d654956?auto=format&fit=crop&q=80&w=400', linkedin: '#' },
    { name: 'James Taylor', role: 'Property Consultant', image: 'https://images.unsplash.com/photo-1519085360753-af0119f7cbe7?auto=format&fit=crop&q=80&w=400', linkedin: '#' },
    { name: 'Emma Watson', role: 'Marketing Head', image: 'https://images.unsplash.com/photo-1598550874175-4d0fe4a2c906?auto=format&fit=crop&q=80&w=400', linkedin: '#' },
    { name: 'Alex Rivera', role: 'Client Relations', image: 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&q=80&w=400', linkedin: '#' },
    { name: 'Linda Song', role: 'Legal Advisor', image: 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&q=80&w=400', linkedin: '#' },
];

const features = [
    { title: 'Market Leaders', desc: 'Consistently ranked #1 in premium real estate transactions.', icon: '🏆' },
    { title: 'Global Reach', desc: 'Direct access to an international network of luxury buyers.', icon: '🌍' },
    { title: 'Expert Valuation', desc: 'Proprietary data models for precise property pricing.', icon: '📊' },
    { title: 'Seamless Closing', desc: 'Dedicated legal and documentation support for hassle-free deals.', icon: '🔐' },
];

const locations = [
    { city: 'Mumbai', address: 'Bandra Kurla Complex, Mumbai, MH', phone: '+91 22 1234 5678' },
    { city: 'Delhi', address: 'Connaught Place, New Delhi, DL', phone: '+91 11 1234 5678' },
    { city: 'Bangalore', address: 'MG Road, Bangalore, KA', phone: '+91 80 1234 5678' },
];
</script>

<template>
    <AppLayout>
        <Head>
            <title>{{ seo.title }}</title>
            <meta name="description" :content="seo.description" />
        </Head>

        <!-- Hero Section -->
        <section class="pt-48 pb-32 bg-primary relative overflow-hidden">
            <div class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?auto=format&fit=crop&q=80&w=1200')] bg-cover bg-center opacity-10"></div>
            <div class="container mx-auto px-6 relative z-10 text-center">
                <h1 class="text-5xl md:text-7xl font-black text-white italic uppercase tracking-tighter mb-6">
                    Our <span class="text-accent">Story</span>
                </h1>
                <nav class="flex justify-center space-x-4 text-white/40 font-bold uppercase tracking-widest text-xs">
                    <a href="/" class="hover:text-accent transition-colors">Home</a>
                    <span>/</span>
                    <span class="text-white">About Us</span>
                </nav>
            </div>
        </section>

        <!-- Mission -->
        <section class="py-24 bg-white">
            <div class="container mx-auto px-6">
                <div class="max-w-4xl mx-auto text-center">
                    <div class="text-6xl text-accent/20 mb-8 font-serif leading-none">"</div>
                    <p class="text-3xl md:text-4xl font-black text-primary italic leading-tight mb-8">
                        We don't just sell properties; we find the place where your <span class="text-accent">future begins.</span> Our mission is to redefine real estate through absolute transparency and unmatched expertise.
                    </p>
                    <div class="w-24 h-1 bg-accent mx-auto"></div>
                </div>
            </div>
        </section>

        <!-- Animated Stats -->
        <StatsSection class="!bg-surface-gray border-y border-gray-100" />

        <!-- Why Choose Us -->
        <section class="py-24 bg-white">
            <div class="container mx-auto px-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10">
                    <div v-for="f in features" :key="f.title" class="p-10 rounded-[2.5rem] bg-surface-gray border border-gray-50 hover:border-accent hover:bg-white hover:shadow-premium transition-all duration-300">
                        <div class="text-5xl mb-6">{{ f.icon }}</div>
                        <h3 class="text-xl font-bold text-primary mb-4 italic uppercase tracking-tighter">{{ f.title }}</h3>
                        <p class="text-primary/40 font-medium leading-relaxed">{{ f.desc }}</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Team Grid -->
        <section class="py-24 bg-surface-gray">
            <div class="container mx-auto px-6">
                <div class="text-center mb-16">
                    <h2 class="text-4xl md:text-5xl font-black text-primary italic uppercase tracking-tighter mb-4">
                        The <span class="text-accent">Visionaries</span>
                    </h2>
                    <p class="text-primary/40">Meet the experts committed to your real estate success.</p>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-10">
                    <div v-for="m in team" :key="m.name" class="group">
                        <div class="aspect-square rounded-full overflow-hidden mb-6 border-8 border-white shadow-xl transition-all duration-500 group-hover:scale-105 group-hover:rotate-3 group-hover:border-accent">
                            <img :src="m.image" :alt="m.name" class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition-all duration-700">
                        </div>
                        <div class="text-center">
                            <h3 class="text-xl font-bold text-primary italic uppercase tracking-tighter">{{ m.name }}</h3>
                            <p class="text-sm font-bold text-accent uppercase tracking-widest mb-4">{{ m.role }}</p>
                            <a :href="m.linkedin" class="inline-flex w-10 h-10 bg-primary rounded-xl items-center justify-center text-white hover:bg-accent transition-colors">
                                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Trust Badges -->
        <section class="py-16 bg-white border-y border-gray-100">
            <div class="container mx-auto px-6">
                <div class="flex flex-wrap justify-center items-center gap-16 grayscale opacity-30 hover:grayscale-0 transition-all">
                    <div v-for="i in 5" :key="i" class="w-32 h-16 bg-gray-200 rounded-xl flex items-center justify-center font-black text-xs">LOGO {{ i }}</div>
                </div>
            </div>
        </section>

        <!-- Office Locations -->
        <section class="py-24 bg-white">
            <div class="container mx-auto px-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
                    <div v-for="loc in locations" :key="loc.city" class="p-10 rounded-[2.5rem] bg-primary text-white shadow-2xl relative overflow-hidden group">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-white/5 rounded-full -translate-y-1/2 translate-x-1/2 group-hover:scale-150 transition-transform duration-700"></div>
                        <h3 class="text-3xl font-black italic uppercase tracking-tighter mb-6 text-accent">{{ loc.city }}</h3>
                        <p class="text-white/60 mb-8 font-bold leading-relaxed">{{ loc.address }}</p>
                        <div class="space-y-2 mb-10">
                            <p class="font-bold flex items-center"><span class="mr-3 opacity-50">📞</span> {{ loc.phone }}</p>
                        </div>
                        <BaseButton variant="outline" size="sm" class="!border-white/20 !text-white hover:!bg-white hover:!text-primary">
                            Get Directions
                        </BaseButton>
                    </div>
                </div>
            </div>
        </section>
    </AppLayout>
</template>
