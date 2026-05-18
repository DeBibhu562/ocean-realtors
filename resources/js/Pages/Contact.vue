<script setup>
import { AppLayout, BaseButton, BaseInput, ToastNotification } from '@/Components';
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';

const form = ref({
    name: '',
    email: '',
    subject: '',
    message: ''
});

const isSubmitting = ref(false);
const showToast = ref(false);

const handleSubmit = () => {
    isSubmitting.value = true;
    // Simulate API call
    setTimeout(() => {
        isSubmitting.value = false;
        showToast.value = true;
        form.value = { name: '', email: '', subject: '', message: '' };
    }, 1500);
};

const contactInfo = [
    {
        title: 'Office Address',
        details: '123 Real Estate Ave, Luxury City, NY 10001',
        icon: '📍',
        color: 'bg-blue-50'
    },
    {
        title: 'Phone Number',
        details: '+1 (234) 567-890',
        icon: '📞',
        color: 'bg-green-50'
    },
    {
        title: 'Email Address',
        details: 'hello@oceanrealtors.com',
        icon: '✉️',
        color: 'bg-orange-50'
    }
];
</script>

<template>
    <Head title="Contact Us" />
    <AppLayout>
        <ToastNotification v-if="showToast" message="Your message has been sent successfully!" />

        <!-- Header Section -->
        <section class="pt-40 pb-20 bg-primary relative overflow-hidden">
            <div class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1497366216548-37526070297c?auto=format&fit=crop&q=80&w=1200')] bg-cover bg-center opacity-20"></div>
            <div class="container mx-auto px-6 relative z-10 text-center">
                <h1 class="text-5xl md:text-7xl font-black text-white italic uppercase tracking-tighter mb-6">
                    Get In <span class="text-accent">Touch</span>
                </h1>
                <p class="text-xl text-white/60 max-w-2xl mx-auto">
                    Have questions about a property or want to list your own? Our team of experts is here to help you.
                </p>
            </div>
        </section>

        <!-- Content Section -->
        <section class="py-24 bg-surface-gray -mt-10 relative z-20">
            <div class="container mx-auto px-6">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-20">
                    <div v-for="info in contactInfo" :key="info.title" class="bg-white p-8 rounded-3xl shadow-premium border border-gray-100 flex flex-col items-center text-center transition-transform duration-300 hover:-translate-y-2">
                        <div class="w-16 h-16 rounded-2xl flex items-center justify-center text-3xl mb-6" :class="info.color">
                            {{ info.icon }}
                        </div>
                        <h3 class="text-xl font-bold text-primary mb-2">{{ info.title }}</h3>
                        <p class="text-primary/40 font-medium">{{ info.details }}</p>
                    </div>
                </div>

                <div class="bg-white rounded-[3rem] shadow-2xl overflow-hidden flex flex-col lg:flex-row">
                    <!-- Form Side -->
                    <div class="lg:w-1/2 p-8 md:p-16">
                        <h2 class="text-3xl font-black text-primary italic uppercase tracking-tighter mb-8">
                            Send us a <span class="text-accent">Message</span>
                        </h2>
                        
                        <form @submit.prevent="handleSubmit" class="space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <BaseInput v-model="form.name" label="Full Name" placeholder="John Doe" required />
                                <BaseInput v-model="form.email" type="email" label="Email Address" placeholder="john@example.com" required />
                            </div>
                            <BaseInput v-model="form.subject" label="Subject" placeholder="Inquiry about Property ID #123" required />
                            
                            <div>
                                <label class="block mb-2 text-sm font-semibold text-primary/80">Your Message</label>
                                <textarea 
                                    v-model="form.message"
                                    rows="5"
                                    class="w-full rounded-xl border-gray-200 bg-surface-gray py-3 px-4 transition-all duration-200 focus:border-accent focus:bg-white focus:ring-4 focus:ring-accent/10 outline-none"
                                    placeholder="Tell us how we can help..."
                                    required
                                ></textarea>
                            </div>

                            <BaseButton 
                                type="submit" 
                                variant="primary" 
                                size="lg" 
                                class="w-full"
                                :loading="isSubmitting"
                            >
                                Send Message
                            </BaseButton>
                        </form>
                    </div>

                    <!-- Visual Side -->
                    <div class="lg:w-1/2 bg-primary relative overflow-hidden flex items-center justify-center p-16">
                        <div class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?auto=format&fit=crop&q=80&w=800')] bg-cover bg-center opacity-30"></div>
                        <div class="relative z-10 text-white">
                            <div class="mb-12">
                                <h3 class="text-2xl font-bold mb-4 italic text-accent">Customer Support</h3>
                                <p class="text-white/60 leading-relaxed">Our dedicated support team is available 24/7 to answer your queries and provide technical assistance.</p>
                            </div>
                            <div class="mb-12">
                                <h3 class="text-2xl font-bold mb-4 italic text-accent">Sales Inquiry</h3>
                                <p class="text-white/60 leading-relaxed">Looking to buy or sell? Our sales specialists are ready to guide you through every step of the process.</p>
                            </div>
                            <div class="pt-8 border-t border-white/10">
                                <p class="font-bold">Follow Us</p>
                                <div class="flex space-x-6 mt-4">
                                    <a href="#" class="text-white/40 hover:text-accent transition-colors">Facebook</a>
                                    <a href="#" class="text-white/40 hover:text-accent transition-colors">Twitter</a>
                                    <a href="#" class="text-white/40 hover:text-accent transition-colors">Instagram</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Map Placeholder -->
        <section class="h-[400px] bg-gray-200 relative">
            <div class="absolute inset-0 flex items-center justify-center bg-surface-gray border-t border-gray-100">
                <div class="text-center">
                    <div class="text-5xl mb-4">🗺️</div>
                    <p class="font-bold text-primary/20 uppercase tracking-widest">Interactive Map Placeholder</p>
                </div>
            </div>
        </section>
    </AppLayout>
</template>
