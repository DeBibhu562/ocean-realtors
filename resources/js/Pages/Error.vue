<script setup>
import { computed } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import BaseButton from '@/Components/BaseButton.vue';

const props = defineProps({
    status: Number,
});

const title = computed(() => {
    return {
        503: '503: Service Unavailable',
        500: '500: Server Error',
        404: '404: Page Not Found',
        403: '403: Forbidden',
    }[props.status];
});

const description = computed(() => {
    return {
        503: 'Sorry, we are doing some maintenance. Please check back soon.',
        500: 'Whoops, something went wrong on our servers.',
        404: 'Sorry, the page you are looking for could not be found.',
        403: 'Sorry, you are forbidden from accessing this page.',
    }[props.status];
});
</script>

<template>
    <div class="min-h-screen bg-primary flex items-center justify-center p-6 text-center">
        <Head :title="title" />
        
        <div class="max-w-md">
            <div class="text-9xl font-black text-accent/20 mb-8 italic italic tracking-tighter">
                {{ status }}
            </div>
            
            <h1 class="text-4xl font-black text-white italic uppercase tracking-tighter mb-4">
                {{ title }}
            </h1>
            
            <p class="text-white/40 font-bold mb-10 text-lg">
                {{ description }}
            </p>
            
            <Link href="/">
                <BaseButton variant="secondary" size="lg" class="w-full !bg-white !text-primary hover:!bg-accent hover:!text-white">
                    Back to Home
                </BaseButton>
            </Link>
        </div>
    </div>
</template>
