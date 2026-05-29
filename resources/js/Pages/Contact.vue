<script setup>
import AppLayout from '@/Components/AppLayout.vue';
import BaseButton from '@/Components/BaseButton.vue';
import BaseInput from '@/Components/BaseInput.vue';
import ToastNotification from '@/Components/ToastNotification.vue';
import StaticPageHero from '@/Components/StaticPageHero.vue';
import { Link } from '@inertiajs/vue3';
import PageSeoHead from '@/Components/PageSeoHead.vue';
import { ref } from 'vue';
import { siteContact, whatsappUrl } from '@/config/site';

const form = ref({ name: '', email: '', phone: '', subject: '', message: '' });
const isSubmitting = ref(false);
const showToast = ref(false);

const handleSubmit = () => {
    isSubmitting.value = true;
    setTimeout(() => {
        isSubmitting.value = false;
        showToast.value = true;
        form.value = { name: '', email: '', phone: '', subject: '', message: '' };
    }, 1200);
};

const contactCards = [
    {
        title: 'Visit Our Office',
        detail: siteContact.address.full,
        href: siteContact.mapEmbedUrl,
        external: true,
        icon: 'location',
    },
    {
        title: 'Call Us',
        detail: siteContact.phoneDisplay,
        href: `tel:${siteContact.phoneTel}`,
        icon: 'phone',
    },
    {
        title: 'WhatsApp',
        detail: 'Chat with our team',
        href: whatsappUrl(),
        external: true,
        icon: 'whatsapp',
    },
    {
        title: 'Email',
        detail: siteContact.email,
        href: `mailto:${siteContact.email}`,
        icon: 'email',
    },
];
</script>

<template>
    <PageSeoHead title="Contact Us" description="Contact Ocean Realtors in Sector 14, Gurgaon. Call or WhatsApp +91 99906 33797 for property enquiries." path="/contact" />
    <AppLayout>
        <ToastNotification v-if="showToast" message="Thank you! We will get back to you shortly." />

        <StaticPageHero
            title="Contact Ocean Realtors"
            subtitle="Visit our Sector 14, Gurgaon office or reach us by phone, WhatsApp, or email — we're here to help with rent, buy, and commercial requirements across NCR."
            badge="Get in touch"
            image="https://images.unsplash.com/photo-1497366216548-37526070297c?auto=format&fit=crop&q=80&w=1600"
        />

        <section class="section-y-sm bg-surface-gray">
            <div class="container max-w-6xl mx-auto px-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-12">
                    <component
                        :is="card.external ? 'a' : 'a'"
                        v-for="card in contactCards"
                        :key="card.title"
                        :href="card.href"
                        :target="card.external ? '_blank' : undefined"
                        :rel="card.external ? 'noopener noreferrer' : undefined"
                        class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md hover:border-primary/20 transition-all group"
                    >
                        <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center mb-3 group-hover:bg-primary group-hover:text-white text-primary transition-colors">
                            <svg v-if="card.icon === 'location'" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /></svg>
                            <svg v-else-if="card.icon === 'phone'" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" /></svg>
                            <svg v-else-if="card.icon === 'whatsapp'" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.435 9.884-9.881 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" /></svg>
                            <svg v-else class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                        </div>
                        <h3 class="text-sm font-bold text-navy mb-1">{{ card.title }}</h3>
                        <p class="text-xs text-text-secondary leading-relaxed">{{ card.detail }}</p>
                    </component>
                </div>

                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden flex flex-col lg:flex-row">
                    <div class="lg:w-1/2 p-6 md:p-10">
                        <h2 class="text-2xl font-bold text-navy mb-2">Send us a message</h2>
                        <p class="text-sm text-text-secondary mb-6">Share your requirement and our Gurgaon team will respond within one business day.</p>
                        <form @submit.prevent="handleSubmit" class="space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <BaseInput v-model="form.name" label="Full name" required />
                                <BaseInput v-model="form.phone" label="Mobile number" placeholder="9990633797" required />
                            </div>
                            <BaseInput v-model="form.email" type="email" label="Email" required />
                            <BaseInput v-model="form.subject" label="Subject" placeholder="Rent / Buy / Commercial" required />
                            <div>
                                <label class="block mb-1.5 text-sm font-semibold text-navy/80">Message</label>
                                <textarea
                                    v-model="form.message"
                                    rows="4"
                                    required
                                    class="w-full rounded-xl border-gray-200 bg-surface-gray py-3 px-4 text-sm focus:border-primary focus:ring-primary/20 outline-none"
                                    placeholder="Tell us about your budget, location, and timeline..."
                                />
                            </div>
                            <BaseButton type="submit" variant="primary" size="lg" class="w-full" :loading="isSubmitting">
                                Send message
                            </BaseButton>
                        </form>
                    </div>

                    <div class="lg:w-1/2 bg-navy p-6 md:p-10 text-white flex flex-col justify-center">
                        <h3 class="text-xl font-bold mb-4">Office hours</h3>
                        <p class="text-white/70 text-sm mb-6">{{ siteContact.hours }}</p>
                        <ul class="space-y-4 text-sm">
                            <li class="flex gap-3">
                                <span class="text-primary font-bold shrink-0">Address</span>
                                <span class="text-white/80">{{ siteContact.address.line1 }}, {{ siteContact.address.line2 }}, {{ siteContact.address.state }}</span>
                            </li>
                            <li class="flex gap-3">
                                <span class="text-primary font-bold shrink-0">Phone</span>
                                <a :href="`tel:${siteContact.phoneTel}`" class="text-white/80 hover:text-white">{{ siteContact.phoneDisplay }}</a>
                            </li>
                        </ul>
                        <div class="mt-8 flex flex-wrap gap-3">
                            <a
                                :href="`https://wa.me/${siteContact.whatsapp}`"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="inline-flex items-center gap-2 rounded-xl bg-emerald-500 px-5 py-2.5 text-sm font-bold hover:bg-emerald-600 transition-colors"
                            >
                                WhatsApp us
                            </a>
                            <Link href="/properties" class="inline-flex items-center gap-2 rounded-xl border border-white/20 px-5 py-2.5 text-sm font-bold hover:bg-white/10 transition-colors">
                                Browse listings
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="h-[360px] md:h-[420px] bg-gray-100">
            <iframe
                :src="siteContact.mapEmbedUrl"
                class="w-full h-full border-0"
                loading="lazy"
                referrerpolicy="no-referrer-when-downgrade"
                title="Ocean Realtors office location — Sector 14 Gurgaon"
            />
        </section>
    </AppLayout>
</template>
