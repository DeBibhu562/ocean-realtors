import { Head } from '@inertiajs/vue3';
import { computed } from 'vue';

export function useSeoMeta() {
    const defaultMeta = {
        title: 'Ocean Realtors - Find Your Dream Home',
        description: 'Explore premium properties, luxury villas, and modern apartments with Ocean Realtors. Your trusted partner in real estate.',
        image: '/img/og-image.jpg',
        url: window.location.href,
        type: 'website',
        twitterCard: 'summary_large_image',
    };

    const getMeta = (customMeta = {}) => {
        return {
            ...defaultMeta,
            ...customMeta,
            title: customMeta.title ? `${customMeta.title} | Ocean Realtors` : defaultMeta.title
        };
    };

    return {
        getMeta
    };
}
