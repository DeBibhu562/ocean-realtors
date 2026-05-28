<script setup>
import { ref, computed } from 'vue';
import { Link, Head } from '@inertiajs/vue3';
import Footer from './Footer.vue';
import NewsletterSection from './Home/NewsletterSection.vue';
import SecondaryNavbar from './SecondaryNavbar.vue';
import WhatsAppIcon from '@/Components/WhatsAppIcon.vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { siteContact, whatsappUrl } from '@/config/site';

import { usePropertyFilters } from '@/Composables/usePropertyFilters';

const props = defineProps({
    title: String,
    useSecondaryNavbar: {
        type: Boolean,
        default: false,
    },
});

const { runSearch } = usePropertyFilters();

const emit = defineEmits(['search']);

const handleSearch = () => {
    emit('search');
    runSearch();
};

const isMobileMenuOpen = ref(false);

const navLinks = [
    { name: 'Home', href: '/' },
    { name: 'Properties', href: '/properties' },
    { name: 'Agents', href: '/agents' },
    { name: 'About', href: '/about' },
    { name: 'Contact', href: '/contact' },
];
</script>

<template>
    <div class="min-h-screen bg-surface font-sans antialiased text-text-primary">
        <Head :title="title" />

        <!-- Navbar -->
        <SecondaryNavbar 
            v-if="useSecondaryNavbar" 
            @search="handleSearch"
        />
        <nav v-else class="sticky top-0 w-full z-[100] bg-white border-b border-border h-14 flex items-center">
            <div class="container max-w-6xl mx-auto px-4 flex items-center justify-between">
                <!-- Logo -->
                <Link href="/" class="flex items-center gap-2.5" aria-label="Ocean Realtors home">
                    <span class="inline-flex h-8 w-8 items-center justify-center rounded-md bg-primary/10 p-1.5 text-primary">
                        <ApplicationLogo class="h-full w-full fill-current" />
                    </span>
                    <span class="text-base font-bold text-text-primary sm:text-lg">Ocean Realtors</span>
                </Link>

                <!-- Desktop Nav -->
                <div class="hidden lg:flex items-center space-x-8">
                    <Link 
                        v-for="link in navLinks" 
                        :key="link.name" 
                        :href="link.href"
                        class="text-sm font-medium text-text-secondary hover:text-primary transition-colors duration-200"
                    >
                        {{ link.name }}
                    </Link>
                </div>

                <div class="hidden lg:flex items-center space-x-3">
                    <a
                        :href="`tel:${siteContact.phoneTel}`"
                        class="inline-flex items-center text-text-secondary hover:text-primary transition-colors whitespace-nowrap"
                        :aria-label="`Call ${siteContact.phoneDisplay}`"
                    >
                        <span class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center mr-2">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" /></svg>
                        </span>
                        <span class="text-xs font-bold tracking-wider">{{ siteContact.phoneDisplay }}</span>
                    </a>
                    <a
                        :href="whatsappUrl()"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="inline-flex items-center gap-1.5 rounded-lg bg-emerald-500 px-2.5 py-2 text-white transition-colors hover:bg-emerald-600 sm:px-3"
                        :aria-label="`WhatsApp ${siteContact.phoneDisplay}`"
                    >
                        <WhatsAppIcon class="h-4 w-4 shrink-0" />
                        <span class="text-xs font-bold">WhatsApp</span>
                    </a>
                </div>

                <!-- Mobile Toggle -->
                <button 
                    @click="isMobileMenuOpen = !isMobileMenuOpen"
                    class="lg:hidden p-2 text-text-secondary hover:bg-gray-100 rounded-lg"
                    aria-label="Toggle menu"
                >
                    <svg v-if="!isMobileMenuOpen" class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    <svg v-else class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l18 18" />
                    </svg>
                </button>
            </div>
        </nav>

        <!-- Mobile Drawer -->
        <Transition
            enter-active-class="transition duration-200 ease-out"
            enter-from-class="transform -translate-y-4 opacity-0"
            enter-to-class="transform translate-y-0 opacity-100"
            leave-active-class="transition duration-150 ease-in"
            leave-from-class="transform translate-y-0 opacity-100"
            leave-to-class="transform -translate-y-4 opacity-0"
        >
            <div v-if="isMobileMenuOpen" class="fixed inset-x-0 top-14 z-[90] lg:hidden bg-white border-b border-border shadow-xl">
                <div class="flex flex-col p-4 space-y-4">
                    <Link 
                        v-for="link in navLinks" 
                        :key="link.name" 
                        :href="link.href"
                        class="text-base font-medium text-text-primary hover:text-primary transition-colors"
                        @click="isMobileMenuOpen = false"
                    >
                        {{ link.name }}
                    </Link>
                    <!-- Mobile Auth/Listing Links Removed -->
                </div>
            </div>
        </Transition>

        <!-- Main Content -->
        <main id="main-content">
            <slot />
        </main>

        <!-- Footer -->
        <Footer />
    </div>
</template>
