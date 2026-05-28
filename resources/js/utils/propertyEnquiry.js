import { formatIndianPrice } from '@/utils/formatIndianPrice';

/** @param {number} price */
export function formatPriceForMessage(price, isRental = false) {
    return formatIndianPrice(price, { isRental });
}

/**
 * @param {{ bhk?: string, bedrooms?: number, type?: string, title?: string }} property
 */
export function buildListingLabel(property) {
    if (property.bhk) {
        const bhk = String(property.bhk).trim();
        const type = property.type ? ` ${property.type}` : '';
        return /bhk/i.test(bhk) ? `${bhk}${type}`.trim() : `${bhk} BHK${type}`.trim();
    }
    if (property.bedrooms) {
        const type = property.type || 'Property';
        return `${property.bedrooms} BHK ${type}`;
    }
    return property.type || property.title || 'property';
}

/**
 * @param {{ city?: string, address?: string, society_name?: string }} property
 */
export function buildLocationLabel(property) {
    const parts = [property.society_name, property.address, property.city].filter(Boolean);
    const unique = [...new Set(parts.map((p) => String(p).trim()).filter(Boolean))];
    return unique.join(', ') || property.city || 'Gurgaon';
}

/**
 * @param {string} agentName
 * @param {object} property
 * @param {string} listingUrl
 */
export function buildPropertyEnquiryWhatsAppMessage(agentName, property, listingUrl) {
    const label = buildListingLabel(property);
    const price = formatPriceForMessage(property.price, !!property.is_rental);
    const location = buildLocationLabel(property);
    const lines = [
        `Hi ${agentName},`,
        '',
        `I came across your ${label} listed at Ocean Realtors for ${price} in ${location}`,
        listingUrl,
        '',
        'I am interested in the property. Please let me know if it is available. Thank you!',
    ];
    return lines.join('\n');
}

/** @param {string} phone */
export function normalizeWhatsAppNumber(phone) {
    return String(phone || '').replace(/\D/g, '');
}

/** @param {string} phone */
export function telHref(phone) {
    const cleaned = String(phone || '').replace(/[^\d+]/g, '');
    return cleaned ? `tel:${cleaned}` : '#';
}

/** @param {string} phone */
export function formatPhoneDisplay(phone) {
    const digits = String(phone || '').replace(/\D/g, '');
    if (digits.length === 12 && digits.startsWith('91')) {
        return `+91 ${digits.slice(2, 7)} ${digits.slice(7)}`;
    }
    if (digits.length === 10) {
        return `+91 ${digits.slice(0, 5)} ${digits.slice(5)}`;
    }
    return phone || '';
}

/**
 * @param {string} phone
 * @param {string} message
 */
export function whatsAppHref(phone, message) {
    const num = normalizeWhatsAppNumber(phone);
    if (!num) return '#';
    const text = encodeURIComponent(message || '');
    return `https://wa.me/${num}?text=${text}`;
}
