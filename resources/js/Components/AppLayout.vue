<script setup>
import { ref, computed } from 'vue';
import { Link, Head } from '@inertiajs/vue3';
import Footer from './Footer.vue';
import NewsletterSection from './Home/NewsletterSection.vue';
import SecondaryNavbar from './SecondaryNavbar.vue';

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
                <Link href="/" class="flex items-center">
                    <span class="text-lg font-bold text-text-primary">Ocean</span>
                    <span class="text-lg font-bold text-primary">Realtors</span>
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

                <!-- Actions Removed as requested -->
                <div class="hidden lg:flex items-center space-x-3">
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
