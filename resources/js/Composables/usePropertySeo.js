import { computed } from 'vue';

/**
 * Build meta + JSON-LD for a property detail page.
 */
export function usePropertySeo(property) {
    const seo = computed(() => property?.seo ?? {});

    const pageTitle = computed(() => {
        const t = property?.title || 'Property';
        return t.includes('Ocean Realtors') ? t : `${t} | Ocean Realtors`;
    });

    const description = computed(() => seo.value.description || property?.description || '');

    const ogImage = computed(() => {
        const img = seo.value.image || property?.gallery?.[0];
        if (!img) return null;
        if (img.startsWith('http')) return img;
        return `${window.location.origin}${img.startsWith('/') ? img : '/'+img}`;
    });

    const canonical = computed(() => {
        if (seo.value.canonical) return seo.value.canonical;
        if (property?.slug) return `${window.location.origin}/${property.slug}`;
        return window.location.href;
    });

    const jsonLd = computed(() => {
        if (!property?.title) return null;

        const offer = {
            '@type': 'Offer',
            price: property.price,
            priceCurrency: 'INR',
        };

        return JSON.stringify({
            '@context': 'https://schema.org',
            '@type': 'RealEstateListing',
            name: property.title,
            description: description.value,
            image: ogImage.value ? [ogImage.value] : undefined,
            url: canonical.value,
            offers: offer,
            address: {
                '@type': 'PostalAddress',
                streetAddress: property.address,
                addressLocality: property.city,
            },
        });
    });

    return {
        pageTitle,
        description,
        ogImage,
        canonical,
        jsonLd,
    };
}
