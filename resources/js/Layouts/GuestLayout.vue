<script setup>
import SiteLogo from '@/Components/SiteLogo.vue';
</script>

<template>
    <div class="min-h-screen flex items-center justify-center bg-primary relative overflow-hidden">
        <!-- Background Accents -->
        <div class="absolute top-0 right-0 w-1/3 h-full bg-accent/5 -skew-x-12 transform translate-x-1/2"></div>
        <div class="absolute bottom-0 left-0 w-1/4 h-1/2 bg-accent/5 skew-y-12 transform -translate-x-1/4"></div>

        <div class="container mx-auto px-6 relative z-10">
            <div class="max-w-md mx-auto">
                <!-- Logo -->
                <div class="text-center mb-10 flex justify-center">
                    <SiteLogo size="xl" theme="dark" />
                </div>

                <!-- Form Card -->
                <div class="bg-white rounded-[2.5rem] shadow-2xl p-8 md:p-12 border border-white/10">
                    <slot />
                </div>

                <!-- Footer Links -->
                <div class="mt-8 text-center">
                    <p class="text-white/40 text-sm font-medium">
                        &copy; {{ new Date().getFullYear() }} Ocean Realtors. All rights reserved.
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>
