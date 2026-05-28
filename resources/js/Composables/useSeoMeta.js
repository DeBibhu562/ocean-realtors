import { siteLogo } from '@/config/site';

const SITE_NAME = 'Ocean Realtors';

function siteOrigin() {
    if (typeof window !== 'undefined' && window.location?.origin) {
        return window.location.origin;
    }
    return '';
}

function absoluteUrl(pathOrUrl) {
    if (!pathOrUrl) return '';
    if (pathOrUrl.startsWith('http')) return pathOrUrl;
    const origin = siteOrigin();
    return `${origin}${pathOrUrl.startsWith('/') ? pathOrUrl : '/'+pathOrUrl}`;
}

/**
 * @param {object} customMeta
 * @returns {object}
 */
function buildSeo(customMeta = {}) {
    const origin = siteOrigin();
    const path = customMeta.path ?? (typeof window !== 'undefined' ? window.location.pathname : '/');
    const canonical = customMeta.canonical ?? absoluteUrl(path);
    const rawTitle = customMeta.title || 'Find Your Dream Property';
    const title = customMeta.exactTitle
        ? rawTitle
        : (rawTitle.includes(SITE_NAME) ? rawTitle : `${rawTitle} | ${SITE_NAME}`);
    const description =
        customMeta.description ||
        'Explore premium properties for rent and sale in Gurgaon and Delhi NCR with Ocean Realtors — verified listings and dedicated agents.';
    const image = absoluteUrl(customMeta.image || siteLogo.src);

    return {
        title,
        description,
        canonical,
        image,
        type: customMeta.type || 'website',
        robots: customMeta.noindex ? 'noindex, nofollow' : 'index, follow',
        twitterCard: 'summary_large_image',
    };
}

export function useSeoMeta() {
    const defaultMeta = buildSeo({ title: 'Home' });

    /** @deprecated Use buildSeo via PageSeoHead or buildSeo directly */
    const getMeta = (customMeta = {}) => {
        const seo = buildSeo(customMeta);
        return {
            ...seo,
            url: seo.canonical,
        };
    };

    const organizationJsonLd = () => {
        const origin = siteOrigin();
        if (!origin) return '';

        return JSON.stringify({
            '@context': 'https://schema.org',
            '@type': 'RealEstateAgent',
            name: SITE_NAME,
            url: origin,
            logo: absoluteUrl(siteLogo.src),
            telephone: '+919990633797',
            address: {
                '@type': 'PostalAddress',
                streetAddress: 'SCO M26, Second Floor, Sector 14',
                addressLocality: 'Gurgaon',
                addressRegion: 'Haryana',
                postalCode: '122001',
                addressCountry: 'IN',
            },
        });
    };

    return {
        defaultMeta,
        getMeta,
        buildSeo,
        organizationJsonLd,
        absoluteUrl,
    };
}
